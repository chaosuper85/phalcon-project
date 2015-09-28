<?php


namespace Services\LogicService;


use Library\Helper\OrderLogHelper;
use Library\Log\Logger;
use Exception;
use Phalcon\Mvc\User\Component;

use OrderFreight;
use OrderAssignDriver;
use OrderFreightBox;
use OrderBoxTimeline;
use OrderFreightTimeline;


class CarteamOrderEquipmentCompleteService extends Component
{

    /*  产装完成
      1、检查箱号和铅封号，是否都填写。
      2、 检查基础状态 待产装 == 2
      3、 assign status 完成产装
      =》 4 检查 箱子的 状态 完成产装
    $req = array(
                'assign_id'      => '1',
            );
     */
    public function equipmentComplete($req, &$error_info)
    {

        $assign_id = $req['assign_id'];

        $userid = $this->session->get('login_user')->id;
        $usertype = $this->session->get('login_user')->usertype;

        $conditions = "id = :id:";

        $parameters = array(
            "id" => $assign_id,
        );

        $orderAssignDriver = OrderAssignDriver::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));

        if (empty($orderAssignDriver)) {
            Logger::warn("assign_id: %s, not foud", $assign_id);
            return false;
        }

        //检查箱号和铅封号，是否都填写。
        $boxid = $orderAssignDriver->order_freight_boxid;

        $conditions = "id = :id:";
        $parameters = array(
            "id" => $boxid,
        );

        $orderFreightBox = OrderFreightBox::findFirst(
            array(
                $conditions,
                "bind" => $parameters
            )
        );

        if (empty($orderFreightBox) || empty($orderFreightBox->box_code) || empty($orderFreightBox->box_ensupe)) {
            $error_info = array(
                'error_code' => '100003',
                'error_msg' => '箱号或铅封号未填写',
                'data' => array(),
            );
            Logger::warn("boxid: %s, not complete", $boxid);
            return false;
        }

        //检查司机是否分派。
        $this->db->begin();

        try {
            $assign_status = $orderAssignDriver->assign_status;
            if ($assign_status == '2' || $assign_status == '100') { // 待产装的 情况下
                $orderAssignDriver->assign_status = '3';
                $ret = $orderAssignDriver->save();
                if (!$ret) {
                    $error_info = array(
                        'error_code' => '100004',
                        'error_msg' => '确认产装完成错误',
                        'data' => array(),
                    );
                    $this->db->rollback();
                    Logger::warn("assign_id: %s, equipmentComplete failed", $assign_id);
                    return false;
                } else {

                    //记录箱子的timeline，
                    $ret = $this->orderboxEquipmentComplete($userid, $orderAssignDriver->order_freight_id, $orderAssignDriver->order_freight_boxid);
                    if (!$ret) {
                        $this->db->rollback();
                        Logger::warn("orderboxEquipmentComplete, userid:%s, order_freight_id:%s, order_freight_boxid:%s ", $userid, $orderAssignDriver->order_freight_id, $orderAssignDriver->order_freight_boxid);
                        return false;
                    }
                    Logger::info("equipmentComplete success:orderId:{%s} boxId:{%s} assign:{%s} assignStatus:{%s} oldStatus:{%s}",$orderAssignDriver->order_freight_id,$boxid,$orderAssignDriver->id,$orderAssignDriver->assign_status,$assign_status);

                    //assign 确认产装完成 =》 箱子 产装完成
                    $this->orderboxEquipmentComplete($userid, $orderAssignDriver->order_freight_id, $orderAssignDriver->order_freight_boxid);
                }
            } else {
                $error_info = array(
                    'error_code' => '100005',
                    'error_msg' => '状态错误',
                    'data' => array(),
                );
                $this->db->rollback();
                Logger::warn("assign_id: %s assign_status:{%s} can not change", $assign_id,$assign_status);
                return false;
            }



        } catch (Exception $e) {
            $error_info = array(
                'error_code' => '100004',
                'error_msg' => '确认产装完成错误',
                'data' => array(),
            );
            $this->db->rollback();
            Logger::warn("exception: %s", var_export($e->getTraceAsString(), true));
            return false;
        }

        $this->db->commit();
        Logger::info("assign_id: %s equipmentComplete return ok", $assign_id);
        return true;
    }


    /*
     * 检查箱子是否产装完成，如果订单的箱子的分派都产装完成
     *  1 箱子所有的  分派任务 全部变成 '3' => '待运抵', 产装完成 =》 箱子状态 修改为 '3' => '待运抵',
     */
    public function orderboxEquipmentComplete($userid, $orderid, $boxid)
    {

        //如果$orderId, 的所有箱子id,boxId的状态为产装完成。则修改订单的状态为产装完成。
        $conditions = "order_freight_boxid = :order_freight_boxid: AND enable = 1 ";
        $parameters = array(
            "order_freight_boxid" => $boxid,
        );

        $orderAssignDriverRes = OrderAssignDriver::find(array(
            $conditions,
            "bind" => $parameters
        ));

        $complete = false;
        if (count($orderAssignDriverRes) > 0) {
            $complete = true;
        }
        $driverId = $orderAssignDriverRes[0]->driver_user_id;

        // 箱子的 分派任务
        foreach ($orderAssignDriverRes as $orderAssignDriver) {
            //箱子的几次的分派的状态
            if ($orderAssignDriver->assign_status == '100' || $orderAssignDriver->assign_status <= 2 ) {
                Logger::warn("order:{%s} box:{%s} can not complete product assignId:{%s} assignStatus:{} < 3(100 < 3 )", $orderid,$boxid,$orderAssignDriver->id,$orderAssignDriver->assign_status );
                $complete = false; // 有一个 任务 未完成。
                break;
            }
        }

        /*
         * 查询箱子的分派，满足条件下修改状态
         */
        if ($complete == true) {
            //修改箱子为产装完成。
            $conditions = "id = :id:";
            $parameters = array(
                "id" => $boxid,
            );


            $orderFreightBox = OrderFreightBox::findFirst(
                array(
                    $conditions,
                    "bind" => $parameters
                )
            );
            if ($orderFreightBox->box_status == '2' || $orderFreightBox->box_status == '100') {
                $orderFreightBox->box_status = '3';
                $ret = $orderFreightBox->save();
                if (!$ret) {
                    Logger::warn("orderFreightBox->save, boxid: %s, return false", $boxid);
                    return false;

                }
            } else {
                Logger::warn("orderFreightBox->box_status: %s error", $orderFreightBox->box_status);
                return false;
            }

            //tudo wanghui,箱子产装完成，订单产装完成， 记录到activity_log
            $this->ActivityLogComponent->noticeDriverWhenBoxChange($boxid, $orderid, $driverId);

            //箱子产装完成，修改箱子的timeline
            $ret = $this->OrderBoxTimelineService->save(
                $orderAssignDriver->order_freight_id,
                $orderAssignDriver->order_freight_boxid,
                $this->order_config->box_status_enum->TO_YUNDI,
                $this->order_config->verify_ream_type->CHANZHUANG,
                $userid, '');
            if (!$ret) {
                Logger::warn('OrderBoxTimelineService->save: return false');

                return false;
            } else {
                $ret = $this->orderEquipmentComplete($userid, $orderid);
                if (!$ret) {
                    Logger::warn("orderEquipmentComplete return false");
                    return false;
                }
            }
        }

        return true;
    }


    /*
     * 检查订单是否产装完成，如果订单的所有箱子都产装完成
     */
    public function orderEquipmentComplete($userid, $orderid)
    {

        //如果$orderId, 的所有箱子id,boxId的状态为产装完成。则修改订单的状态为产装完成。
        $conditions = "order_freight_id = :order_freight_id:";
        $parameters = array(
            "order_freight_id" => $orderid,
        );

        //先找订单的所有箱子，再看箱子的分派情况

        $orderFreightBoxRes = OrderFreightBox::find(
            array(
                $conditions,
                "bind" => $parameters
            )
        );

        $complete = true;

        if (count($orderFreightBoxRes) == 0) {
            Logger::info("orderid: %s, orderFreightBoxRes has no record", $orderid);
            return true;
        }

        $complete = true;
        foreach ($orderFreightBoxRes as $orderFreightBox) {

            //有一个箱子未产装完成，则不修改订单的状态，
            if ($orderFreightBox->box_status <= '2' || $orderFreightBox->box_status == '100') {
                $complete = false;
                break;
            }
            //几个箱子都产装完成，则修改
        }

        /*
         * 查询订单，满足条件下修改状态
         */
        if ($complete == true) {
            $conditions = "id = :id:";
            $parameters = array(
                "id" => $orderid,
            );

            $orderFreightRes = OrderFreight::findFirst(array(
                $conditions,
                "bind" => $parameters
            ));

            if ($orderFreightRes->order_status != '4') {
                Logger::warn("orderid:%s, status:%s error", $orderid, $orderFreightRes->order_status);
                return false;
            }

            //订单的状态
            $orderFreightRes->order_status = '5';
            $ret = $orderFreightRes->save();
            if (!$ret) {
                Logger::warn('orderFreightRes->save return: ' . var_export($orderFreightRes->getMessages(), true));
                return false;
            } else {
                //记录订单的timeline
                $ret = $this->OrderFreightTimelineService->save($orderid,
                    '2',
                    $this->order_config->verify_ream_type->CHANZHUANG,
                    $userid, '');
                if (!$ret) {
                    Logger::warn('orderFreightTimeline->save: return false');
                    return false;
                }
            }
        }

        Logger::info("orderid: %s orderEquipmentComplete done", $orderid);

        return true;
    }


}
