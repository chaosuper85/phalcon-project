<?php
/**
 * 订单检测任务
 *
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/9/1
 * Time: 上午11:43
 */

use Library\Log\Logger;

class OrderchekTask extends \Phalcon\CLI\Task
{
    # 日志文件名
    const LOG_FILENAME = 'orderCheck';

    # 查询某个天数之内的订单的状态
    const ORDER_WITHIN_DAYS = 30;

    ## 集装箱的没有location的阈值 ， 触发阈值就去获取运营商的location
    const BOX_WITHOUT_LOCATION_MINUTES = 60;

    /**
     * check订单是否已经运抵
     */
    public function checkOrderIsArrivedAction() {
        try {
            Logger::info(
                $this->constant->LOG_SEPORATER . " TASK ORDER_CHECK STRTING" . $this->constant->LOG_SEPORATER,
                $this->constant->LNS,
                self::LOG_FILENAME
            );

            # 查找订单表，获取带运抵的订单数据
            $start_time = date('Y-m-d H:i:s', time() - self::ORDER_WITHIN_DAYS * 24 * 3600);
            $end_time   = date('Y-m-d H:i:s');
            $status = $this->order_config->order_status_enum->TO_YUNDI;

            $order_list = OrderFreight::find(
                array(
                    "columns"       => "id, tidan_code",
                    "conditions"    => "order_status = ?1 AND create_time >= ?2 AND create_time <= ?3",
                    "bind"          => array(1 => $status, 2 => $start_time, 3 => $end_time)
                )
            );
            if (count($order_list) <= 0) {
                Logger::info(
                    $this->constant->LOG_SEPORATER . " TASK ORDER_CHECK: no data",
                    $this->constant->LNS,
                    self::LOG_FILENAME
                );
                return false;
            }

            # 调用service去天津港口局查询
            $order_check_service = $this->di->get('AppOrderCheckService');

            foreach ($order_list as $i => $order) {

                $res = $order_check_service->getResult($order->tidan_code);
                if (count($res) > 0) {
                    # 再次确定箱号与铅封是否与表中的匹配
                    $box_ids = array();

                    $all_match_ok = $this->_checkBoxInfo(
                        $order->id,
                        $res,
                        $order->tidan_code,
                        $box_ids
                    );
                    if ($all_match_ok) {
                        # 对box_ids去重
                        $box_ids = array_unique($box_ids);
                        # 更新box、order状态为已运抵; 更新box、order的timeline为已运抵
                        $this->_updateBoxAndOrder($order->id, $box_ids);
                    }
                }
                else {
                    Logger::info(
                        $this->constant->LOG_SEPORATER . " TASK ORDER_CHECK: order_id  " . $order->id . ", tidan_code  " . $order->tidan_code . " is not found",
                        $this->constant->LNS,
                        self::LOG_FILENAME
                    );
                }
            }

            Logger::info(
                $this->constant->LOG_SEPORATER . " TASK ORDER_CHECK ENDING" . $this->constant->LOG_SEPORATER,
                $this->constant->LNS,
                self::LOG_FILENAME
            );

        } catch (\Exception $e) {
            Logger::warn(
                $this->constant->LOG_SEPORATER . $e->getCode() . $this->constant->LOG_SEPORATER . $e->getMessage(),
                $this->constant->LNS,
                self::LOG_FILENAME
            );
            return  false;
        }
    }


    /**
     * 检测箱号与铅封号是否ok
     */
    private function _checkBoxInfo($order_freight_id, $port_return_data, $tidan_code, &$box_ids) {
        $res = true;

        # 验证箱号与铅封号
        foreach ($port_return_data as $k => $v) {
            $data = OrderFreightBox::findFirst(
                array(
                    "columns"       => "id",
                    "conditions"    => "order_freight_id = ?1 AND box_code = ?2 AND box_ensupe = ?3",
                    "bind"          => array(1 => $order_freight_id, 2 => $v[0], 3 => $v[1])
                )
            );

            if (!empty($data->id)) {
                $box_ids[] = $data->id;
            }
            else {
                # 将不匹配的数据插入unarrived_box表
                $unarrived_box['order_freight_id'] = $order_freight_id;
                $unarrived_box['tidan_code'] = $tidan_code;
                $unarrived_box['box_code'] = $v[0];
                $unarrived_box['box_ensupe'] = $v[1];
                $unarrived_box['create_time'] = $unarrived_box['update_time'] = date('Y-m-d H:i:s');

                $unarrived_box_model = new UnarrivedBox();
                $unarrived_box_model->save($unarrived_box);

                # 记录log
                $log_content = "TASK ORDER_CHECK: order_id " . $order_freight_id;
                $log_content .= ", box_code " . $v[0] . ", box_ensupe " . $v[1];
                $log_content .= " is not match the table OrderFreightBox";

                Logger::info(
                    $this->constant->LOG_SEPORATER . $log_content,
                    $this->constant->LNS,
                    self::LOG_FILENAME
                );

                return false;
            }
        }

        return $res;
    }

    /**
     * 更新box、order状态为已运抵; 更新box、order的timeline为已运抵
     */
    private function _updateBoxAndOrder($order_freight_id, $box_ids) {
        $app_order_service = $this->di->get("AppOrderService");

        # 更新订单状态
        $order_status = $this->order_config->order_status_enum->TO_COMMENT;
        $order_status_res = $app_order_service->updateOrderStatus($order_freight_id, $order_status);
        if (empty($order_status_res)) {
            $log_content = "TASK ORDER_CHECK: order_id " . $order_freight_id . " updated order_status is failed ";

            Logger::info(
                $this->constant->LOG_SEPORATER . $log_content,
                $this->constant->LNS,
                self::LOG_FILENAME
            );

            return false;
        }

        # order activity log
        $this->di->get('ActivityLogService')->insertActionLog(
            $this->constant->ACTION_TYPE->ORDER_ARRIVED,
            "cli",
            0,
            $this->constant->ACTION_REAM_TYPE->SYSTEM_AUTO,
            $order_freight_id,
            0,
            json_encode($order_status_res),
            "",
            $this->constant->PLATFORM_TYPE->ANDROID
        );
        # end

        # 更新订单timeline
        # $order_timeline_status = 4;
        $order_timeline_status = $this->order_config->ordertimeline_type_enum->IS_ARRIVED;

        $app_order_service->updateOrderTimeLine($order_freight_id, $order_timeline_status);

        # 更新箱子状态与timeline
        $box_status = $this->order_config->assign_status_enum->YUNDI;
        foreach ($box_ids as $i => $box_id) {
            # 更新order_freight_box中的box_status为运抵
            $box_info = \OrderFreightBox::findFirst($box_id);
            $box_info->box_status = $this->order_config->box_status_enum->YUNDI;
            $box_info->save();

            $box_status_res = $app_order_service->confirmBoxStatus($order_freight_id, $box_id, 0, $box_status);
            if (empty($box_status_res)) continue;

            # order activity log
            $this->di->get('ActivityLogService')->insertActionLog(
                $this->constant->ACTION_TYPE->BOX_ARRIVED,
                "cli",
                0,
                $this->constant->ACTION_REAM_TYPE->SYSTEM_AUTO,
                $box_id,
                0,
                json_encode($box_status_res),
                "",
                $this->constant->PLATFORM_TYPE->ANDROID
            );
            # end

            # 更新timeline
            $app_order_service->updateBoxTimeLine(
                $order_freight_id,
                $box_id,
                'YUNDI'
            );
        }
    }



    /**
     * 检查箱子的状态 ， 超过事件没有反馈location的寄去运营商获取
     */
    public function checkBoxLocationAction()
    {
        // todo
    }



}