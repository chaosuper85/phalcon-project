<?php
/**
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/25
 * Time: 下午3:43
 */

namespace Services\LogicService;

use Library\Log\Logger;
use Exception;
use Phalcon\Mvc\User\Component;

class AppOrderService extends Component
{

    /**
     * 司机app首页数据
     */
    public function getOrderIndexInfoByDriverId($driver_id, $status_list)
    {
        $return_result = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array()
        );

        if (empty($driver_id)) {
            $return_result['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $return_result['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $return_result;
        }

        $data = array();

        $order_data_service = $this->di->get('AppOrderDataService');
        $assigns = $order_data_service->getAssignInfoByUseridAndStatusList($driver_id, $status_list);
        if (count($assigns) > 0) {
            foreach ($assigns as $key => $value) {
                $res = array();

                $res['order_freight_id'] = $value->order_freight_id;
                $res['assign_id'] = $value->id;

                # 获取箱子信息
                $box_info = $order_data_service->getBoxInfo($value->order_freight_boxid);

                # 获取产装地和时间id
                $ids_info = $order_data_service->getAddressIdAndTimeIdByUseridAndBoxid(
                    $driver_id,
                    $value->order_freight_boxid
                );

                # 获取产装地
                $address_info = $order_data_service->getAddressInfoById(
                    $ids_info[0]->order_product_addressid,
                    'address_detail, address_townid'
                );

                $address_town_info = $this->di->get('CityService')->getFullNameById($address_info->address_townid);
                $res['address_info'] = !empty($address_info->address_detail) ? $address_town_info . $address_info->address_detail : "";
                $res['address_total'] = count($ids_info) > 0 ? count($ids_info) . '个' : 0;

                # 获取时间
                $time_info = $order_data_service->getTimeInfoById(
                    $ids_info[0]->order_product_timeid,
                    'product_supply_time'
                );
                $res['product_supply_time'] = !empty($time_info->product_supply_time) ? date('m-d H:i', strtotime($time_info->product_supply_time)) : "";

                # 状态timeline
                $res['status_timeline'] = $order_data_service->getBoxTimeline(
                    $value->order_freight_id,
                    $value->order_freight_boxid
                );

                # 获取订单信息
                $order_info = $order_data_service->getOrderInfoById(
                    $value->order_freight_id,
                    'yard_id, product_box_type, product_desc'
                );
                $res['product_box_type'] = !empty($order_info->product_box_type) ? $this->order_config->box_type_define[$order_info->product_box_type] : "";

                # 获取堆场信息
                $yard_info = $order_data_service->getOrderYardInfoById(
                    $order_info->yard_id,
                    'yard_name'
                );
                $res['yard_name'] = !empty($yard_info->yard_name) ? $yard_info->yard_name : "";

                $res['notice_info'] = !empty($order_info->product_desc) ? $order_info->product_desc : "";

                # $res['status'] = $value->assign_status;
                $res['status'] = $box_info['box_status'];

                $data[] = array_merge($res, $box_info);
            }

        }
        $return_result['data'] = $data;

        # 记录log
        Logger::info(
            $this->constant->LOG_SEPORATER . "APPORDER INDEX: driver_id " . $driver_id .
            $this->constant->LOG_SEPORATER . var_export($return_result['data'], true)
        );
        return $return_result;
    }

    /**
     * 详情数据
     */
    public function getOrderDetailInfoByAssignId($driver_id, $assign_id)
    {
        $return_result = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array()
        );

        #验证参数
        if (empty($driver_id) || empty($assign_id)) {
            $return_result['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $return_result['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $return_result;
        }

        # 验证此assign_id是否属于该司机
        $assign_info = \OrderAssignDriver::findFirst($assign_id);
        if ($driver_id != $assign_info->driver_user_id) {
            $return_result['error_code'] = $this->response_code->app->BOX_NOT_BELONG_YOU[0];
            $return_result['error_msg'] = $this->response_code->app->BOX_NOT_BELONG_YOU[1];

            return $return_result;
        }

        $data = array();
        $data['order_freight_id'] = $assign_info->order_freight_id;
        $data['assign_id'] = $assign_info->id;
        $data['box_id'] = $assign_info->order_freight_boxid;

        $order_data_service = $this->di->get('AppOrderDataService');
        # 获取箱子信息
        $box_info = $order_data_service->getBoxInfo($assign_info->order_freight_boxid);
        $data['status'] = $box_info['box_status'];

        # 状态timeline
        $data['status_timeline'] = $order_data_service->getBoxTimeline(
            $assign_info->order_freight_id,
            $assign_info->order_freight_boxid
        );

        # 获取产装地和时间id
        $ids_info = $order_data_service->getAddressIdAndTimeIdByUseridAndBoxid(
            $assign_info->driver_user_id,
            $assign_info->order_freight_boxid
        );

        foreach ($ids_info as $key => $val) {
            $res = array();

            # 获取产装地与时间
            $address_info = $order_data_service->getAddressInfoById($val->order_product_addressid);
            $time_info = $order_data_service->getTimeInfoById($val->order_product_timeid);

            $res['position'] = ($key + 1);

            $address_town_info = $this->di->get('CityService')->getFullNameById($address_info->address_townid);
            $res['address_id'] = !empty($val->order_product_addressid) ? $val->order_product_addressid : 0;
            $res['address_info'] = !empty($address_info->address_detail) ? $address_town_info . $address_info->address_detail : '';

            $res['time_id'] = !empty($val->order_product_timeid) ? $val->order_product_timeid : 0;
            $res['product_supply_time'] = !empty($time_info->product_supply_time) ? $time_info->product_supply_time : '';
            $res['contact_name'] = !empty($address_info->contact_name) ? $address_info->contact_name : '';
            $res['phone'] = !empty($address_info->contact_number) ? $address_info->contact_number : '';

            $data['address_list'][] = $res;
        }
        $data['address_total'] = count($ids_info) > 0 ? count($ids_info) . '个' : 0;

        # 获取堆场信息
        $order_info = $order_data_service->getOrderInfoById($assign_info->order_freight_id);
        $yard_info = $order_data_service->getOrderYardInfoById($order_info->yard_id);
        $yard_location = \YardLocation::findFirst($order_info->yard_id);

        $data['yard_info']['yard_name'] = !empty($yard_info->yard_name) ? $yard_info->yard_name : '';
        $data['yard_info']['date_time'] = !empty($yard_info->create_time) ? $yard_info->create_time : '';
        $data['yard_info']['yard_address'] = !empty($yard_location->yard_address) ? $yard_location->yard_address : '';

        $data['order_info']['yundan_code'] = !empty($order_info->yundan_code) ? $order_info->yundan_code : '';
        $data['order_info']['tidan_code'] = !empty($order_info->tidan_code) ? $order_info->tidan_code : '';

        # 获取箱子类型（如：开顶箱）
        $data['product_box_type'] = !empty($order_info->product_box_type) ? $this->order_config->box_type_define[$order_info->product_box_type] : "";

        # 获取船信息
        $ship_name_info = \ShipName::findFirst($order_info->ship_name_id);
        if ($order_info->shipping_company_id) {
            $shipping_company_info = \ShippingCompany::findFirst($order_info->shipping_company_id);
        }

        $data['ship_info']['ship_company_name'] = !empty($shipping_company_info->china_name) ? $shipping_company_info->china_name : (!empty($shipping_company_info->english_name) ? $shipping_company_info->english_name : "");
        $data['ship_info']['ship_name'] = !empty($ship_name_info->china_name) ? $ship_name_info->china_name : (!empty($ship_name_info->eng_name) ? $ship_name_info->eng_name : "");
        $data['ship_info']['ship_ticket'] = !empty($order_info->ship_ticket) ? $order_info->ship_ticket : "";

        # $data['notice_info'] = "";
        $data['notice_info'] = !empty($order_info->product_desc) ? $order_info->product_desc : "";

        $return_result['data'] = array_merge($data, $box_info);

        # 记录log
        Logger::info(
            $this->constant->LOG_SEPORATER . "APPORDER DETAIL: driver_id " . $driver_id .
            $this->constant->LOG_SEPORATER . var_export($return_result['data'], true)
        );
        return $return_result;
    }


    /**
     * 产装列表接口
     */
    public function getProductList($order_freight_id, $driver_id, $box_id)
    {
        $return_result = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array()
        );

        if (empty($driver_id) || empty($order_freight_id) || empty($box_id)) {
            $return_result['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $return_result['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $return_result;
        }

        # 验证箱子的属主
        if (!$this->checkBoxOwner($order_freight_id, $box_id, $driver_id)) {
            $res['error_code'] = $this->response_code->app->BOX_NOT_BELONG_YOU[0];
            $res['error_msg'] = $this->response_code->app->BOX_NOT_BELONG_YOU[1];

            return $res;
        }

        $data = array();

        $order_data_service = $this->di->get('AppOrderDataService');
        # 获取箱子信息
        $box_info = $order_data_service->getBoxInfo($box_id);

        $data['order_freight_id'] = $order_freight_id;
        $data['box_id'] = $box_id;

        # 获取产装地址和时间
        $data['address_list'] = array();
        $conditions = array(
            'order_freight_id = ?1',
            'driver_user_id = ?2',
            'order_freight_boxid = ?3',
            'assign_status IN (' . $this->order_config->assign_status_enum->TO_CHANZHUANG . ', ' . $this->order_config->assign_status_enum->TO_YUNDI . ', ' . $this->order_config->assign_status_enum->APP_CHANZHUANG_COMPLETE . ')'
        );
        $bind = array(
            1 => $order_freight_id,
            2 => $driver_id,
            3 => $box_id
        );
        $assigns = \OrderAssignDriver::find(
            array(
                "conditions" => implode(' AND ', $conditions),
                "bind" => $bind,
                "order" => "create_time DESC"
            )
        );
        foreach ($assigns as $key => $val) {
            $res = array();

            $address_info = $order_data_service->getAddressInfoById($val->order_product_addressid);
            $time_info = $order_data_service->getTimeInfoById($val->order_product_timeid);

            $address_town_info = $this->di->get('CityService')->getFullNameById($address_info->address_townid);
            $res['address_id'] = !empty($val->order_product_addressid) ? $val->order_product_addressid : 0;
            $res['address_info'] = !empty($address_info->address_detail) ? $address_town_info . $address_info->address_detail : '';

            $res['contact_name'] = !empty($address_info->contact_name) ? $address_info->contact_name : '';
            $res['phone'] = !empty($address_info->contact_number) ? $address_info->contact_number : '';

            $res['time_id'] = !empty($val->order_product_timeid) ? $val->order_product_timeid : 0;
            $res['product_supply_time'] = !empty($time_info->product_supply_time) ? $time_info->product_supply_time : '';

            $res['status'] = $val->assign_status;
            $res['position'] = ($key + 1);

            $data['address_list'][] = $res;
        }

        # 获取箱子类型（如：开顶箱）
        $order_info = $order_data_service->getOrderInfoById($data['order_freight_id']);
        $data['product_box_type'] = !empty($order_info->product_box_type) ? $this->order_config->box_type_define[$order_info->product_box_type] : "";

        $return_result['data'] = array_merge($data, $box_info);

        # 记录log
        Logger::info(
            $this->constant->LOG_SEPORATER . "APPORDER PRODUCTLIST: driver_id " . $driver_id .
            $this->constant->LOG_SEPORATER . var_export($return_result['data'], true)
        );
        return $return_result;
    }

    /**
     * 确认产装完成接口
     */
    public function doProductConfirm($driver_id, $order_freight_id, $box_id, $address_id, $time_id)
    {
        $res = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array()
        );

        if (empty($driver_id) || empty($order_freight_id) || empty($box_id) || empty($address_id) || empty($time_id)) {
            $res['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $res['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $res;
        }

        # 验证箱子的属主
        if (!$this->checkBoxOwner($order_freight_id, $box_id, $driver_id)) {
            $res['error_code'] = $this->response_code->app->BOX_NOT_BELONG_YOU[0];
            $res['error_msg'] = $this->response_code->app->BOX_NOT_BELONG_YOU[1];

            return $res;
        }

        # 更新箱子在某地某时间下的状态为100:临时产装完成，须待车队确认
        $product_status = $this->order_config->assign_status_enum->APP_CHANZHUANG_COMPLETE;

        $confirm_status = $this->confirmBoxStatus(
            $order_freight_id,
            $box_id,
            $driver_id,
            $product_status,
            $address_id,
            $time_id
        );
        if (!$confirm_status) {
            $res['error_code'] = $this->response_code->app->BOX_PRODUCT_CONFIRM_FAIL[0];
            $res['error_msg'] = $this->response_code->app->BOX_PRODUCT_CONFIRM_FAIL[1];

            return $res;
        }

        # activity log
        $this->di->get('ActivityLogService')->insertActionLog(
            $this->constant->ACTION_TYPE->BOX_PRODUCT_COMPLETE,
            $this->request->getClientAddress(),
            $driver_id,
            $this->constant->ACTION_REAM_TYPE->DRIVER,
            $box_id,
            0,
            json_encode($res),
            "",
            $this->constant->PLATFORM_TYPE->ANDROID
        );
        # end

        /**
         * box产装状态更新成功，则
         */
        $res['data']['isok'] = "1";
        $res['data']['towhere'] = "2";

        # 1、检测box是否在所有产装地均完成产装
        $all_addresses_ok = $this->checkOrderAssignStatus(
            $order_freight_id,
            $box_id,
            $product_status
        );

        if ($all_addresses_ok) {
            # 更新order_freight_box中的box_status为司机确认完成
            $box_info = \OrderFreightBox::findFirst($box_id);
            $box_info->box_status = $this->order_config->box_status_enum->APP_CHANZHUANG_COMPLETE;
            $box_info->save();

            $res['data']['towhere'] = "1";
        }

        # 记录log
        Logger::info(
            $this->constant->LOG_SEPORATER . "APPORDER PRODUCTCONFIRM: driver_id " . $driver_id .
            $this->constant->LOG_SEPORATER . var_export($res, true)
        );
        return $res;
    }


    /**
     * 落箱详情接口
     */
    public function getDropDetailData($driver_id, $order_freight_id, $box_id)
    {
        $res = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array()
        );

        if (empty($driver_id) || empty($order_freight_id) || empty($box_id)) {
            $res['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $res['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $res;
        }

        # 验证箱子的属主
        if (!$this->checkBoxOwner($order_freight_id, $box_id, $driver_id)) {
            $res['error_code'] = $this->response_code->app->BOX_NOT_BELONG_YOU[0];
            $res['error_msg'] = $this->response_code->app->BOX_NOT_BELONG_YOU[1];

            return $res;
        }

        $data = array();
        $order_data_service = $this->di->get('AppOrderDataService');

        $data['order_freight_id'] = $order_freight_id;
        $data['box_id'] = $box_id;

        # 获取箱子信息
        $box_info = $order_data_service->getBoxInfo($box_id);

        # 获取堆场信息
        $order_info = $order_data_service->getOrderInfoById($order_freight_id);
        $yard_info = $order_data_service->getOrderYardInfoById($order_info->yard_id);
        $yard_location = \YardLocation::findFirst($order_info->yard_id);

        $data['product_box_type'] = !empty($order_info->product_box_type) ? $this->order_config->box_type_define[$order_info->product_box_type] : "";
        $data['yard_name'] = !empty($yard_info->yard_name) ? $yard_info->yard_name : "";
        $data['yard_address'] = !empty($yard_location->yard_address) ? $yard_location->yard_address : '';
        $data['status'] = !empty($order_info->order_status) ? $order_info->order_status : $this->order_config->assign_status_enum->LUOXIANG;

        $res['data'] = array_merge($data, $box_info);

        # 记录log
        Logger::info(
            $this->constant->LOG_SEPORATER . "APPORDER DROPDETAIL: driver_id " . $driver_id .
            $this->constant->LOG_SEPORATER . var_export($res['data'], true)
        );
        return $res;
    }


    /**
     * 确认落箱完成接口
     */
    public function doDropConfirm($driver_id, $order_freight_id, $box_id)
    {
        $res = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array()
        );

        if (empty($driver_id) || empty($order_freight_id) || empty($box_id)) {
            $res['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $res['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $res;
        }

        # 验证箱子的属主
        if (!$this->checkBoxOwner($order_freight_id, $box_id, $driver_id)) {
            $res['error_code'] = $this->response_code->app->BOX_NOT_BELONG_YOU[0];
            $res['error_msg'] = $this->response_code->app->BOX_NOT_BELONG_YOU[1];

            return $res;
        }

        # 更新箱子在某地某时间下的状态为4:已落箱，待运抵
        $luoxiang_status = $this->order_config->assign_status_enum->LUOXIANG;

        $confirm_status = $this->confirmBoxStatus(
            $order_freight_id,
            $box_id,
            $driver_id,
            $luoxiang_status
        );
        if (!$confirm_status) {
            $res['error_code'] = $this->response_code->app->BOX_LUOXIANG_CONFIRM_FAIL[0];
            $res['error_msg'] = $this->response_code->app->BOX_LUOXIANG_CONFIRM_FAIL[1];

            return $res;
        }

        # 更新order_freight_box中的box_status为落箱完成
        $box_info = \OrderFreightBox::findFirst($box_id);
        $box_info->box_status = $this->order_config->box_status_enum->LUOXIANG;
        $box_info->save();

        # activity log
        $this->di->get('ActivityLogService')->insertActionLog(
            $this->constant->ACTION_TYPE->BOX_DROP_COMPLETE,
            $this->request->getClientAddress(),
            $driver_id,
            $this->constant->ACTION_REAM_TYPE->DRIVER,
            $box_id,
            0,
            json_encode($res),
            "",
            $this->constant->PLATFORM_TYPE->ANDROID
        );
        # end

        /**
         * box落箱状态更新成功，则
         */
        $res['data']['isok'] = "1";

        if ($res['data']['isok']) {

            # 2、如果落箱完成，则更新箱子的timeline
            $add_timeline_result = $this->updateBoxTimeLine(
                $order_freight_id,
                $box_id,
                'LUOXIANG',
                $driver_id
            );

            # 3、检测box所属订单下的所有箱子是否均落箱完成
            $all_box_ok = $this->checkOrderAssignStatus(
                $order_freight_id,
                0,
                $luoxiang_status
            );

            if ($all_box_ok) {
                # 4、如果完成，则更新订单的状态、订单的timeline
                $order_status = $this->order_config->order_status_enum->TO_YUNDI;
                $this->updateOrderStatus(
                    $order_freight_id,
                    $order_status
                );

                # $order_timeline_status = 3;
                $order_timeline_status = $this->order_config->ordertimeline_type_enum->IS_DROPED;
                $this->updateOrderTimeLine(
                    $order_freight_id,
                    $order_timeline_status
                );

                # activity log
                $this->di->get('ActivityLogService')->insertActionLog(
                    $this->constant->ACTION_TYPE->ORDER_DROP_COMPLETE,
                    $this->request->getClientAddress(),
                    $driver_id,
                    $this->constant->ACTION_REAM_TYPE->DRIVER,
                    $order_freight_id,
                    0,
                    "",
                    "",
                    $this->constant->PLATFORM_TYPE->ANDROID
                );
                # end

            }
        }

        # 记录log
        Logger::info(
            $this->constant->LOG_SEPORATER . "APPORDER DROPDCONFIRM: driver_id " . $driver_id .
            $this->constant->LOG_SEPORATER . var_export($res, true)
        );
        return $res;
    }


    /**
     * 取得完成的历史箱子列表
     */
    public function getCompleteBoxList($driver_id, $status_list, $current_page = 1, $page_size = 10, $time_interval = 30)
    {
        $res = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array()
        );

        # 获取分页数据
        $conditions = array(
            'driver_user_id = ?1',
            'create_time >= ?2',
            'create_time <= ?3',
            'assign_status IN (' . implode(', ', $status_list) . ')'
        );
        $bind = array(
            '1' => $driver_id,
            '2' => date('Y-m-d H:i:s', time() - $time_interval * 24 * 3600),
            '3' => date('Y-m-d H:i:s', time())
        );
        $group = 'order_freight_boxid';
        $order = 'create_time DESC';
        $limit = array('number' => $page_size, 'offset' => ($current_page - 1) * $page_size);

        # 获取箱子total
        $counts = \OrderAssignDriver::count(
            array(
                'conditions' => implode(' AND ', $conditions),
                'bind' => $bind,
                'group' => $group
            )
        );

        if ($counts <= 0) {
            $res['data']['total'] = 0;
            $res['data']['list'] = array();
        } else {
            $res['data']['total'] = count($counts);
            $res['data']['list'] = array();

            $assign_list = \OrderAssignDriver::find(
                array(
                    'conditions' => implode(' AND ', $conditions),
                    'bind' => $bind,
                    'group' => $group,
                    'order' => $order,
                    'limit' => $limit
                )
            );

            if (!empty($assign_list)) {
                $order_data_service = $this->di->get('AppOrderDataService');

                foreach ($assign_list as $value) {
                    $result = array();

                    $result['order_freight_id'] = $value->order_freight_id;
                    $result['assign_id'] = $value->id;
                    $result['create_time'] = date('H:i m-d', strtotime($value->create_time));

                    # 获取箱子信息
                    $box_info = $order_data_service->getBoxInfo($value->order_freight_boxid);
                    $result['status'] = !empty($box_info['box_status']) ? $box_info['box_status'] : 0;

                    # 获取箱子类型
                    $order_info = $order_data_service->getOrderInfoById($value->order_freight_id);
                    $result['product_box_type'] = !empty($order_info->product_box_type) ? $this->order_config->box_type_define[$order_info->product_box_type] : "";

                    $res['data']['list'][] = array_merge($result, $box_info);
                }
            }
        }

        # 记录log
        Logger::info(
            $this->constant->LOG_SEPORATER . "APPORDER COMPLETE: driver_id " . $driver_id .
            $this->constant->LOG_SEPORATER . var_export($res['data'], true)
        );
        return $res;
    }


    /**
     * 完成的箱子详情页
     */
    public function getCompleteBoxDetail($driver_id, $order_freight_id, $box_id)
    {
        $res = array(
            'error_code' => '0',
            'error_msg' => '',
            'data' => array()
        );

        if (empty($driver_id) || empty($order_freight_id) || empty($box_id)) {
            $res['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $res['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $res;
        }

        # 验证箱子的属主
        if (!$this->checkBoxOwner($order_freight_id, $box_id, $driver_id)) {
            $res['error_code'] = $this->response_code->app->BOX_NOT_BELONG_YOU[0];
            $res['error_msg'] = $this->response_code->app->BOX_NOT_BELONG_YOU[1];

            return $res;
        }

        $order_data_service = $this->di->get('AppOrderDataService');
        # 获取箱子信息
        $box_info = $order_data_service->getBoxInfo($box_id);

        # 获取产装地和时间id
        $ids_info = $order_data_service->getAddressIdAndTimeIdByUseridAndBoxid($driver_id, $box_id);

        # 获取产装地与时间列表
        $address_info = $order_data_service->getAddressInfoById($ids_info[0]->order_product_addressid);
        $time_info = $order_data_service->getTimeInfoById($ids_info[0]->order_product_timeid);

        $address_town_info = $this->di->get('CityService')->getFullNameById($address_info->address_townid);
        $result['address_info'] = !empty($address_info->address_detail) ? $address_town_info . $address_info->address_detail : "";
        $result['product_supply_time'] = !empty($time_info->product_supply_time) ? date('m-d H:i', strtotime($time_info->product_supply_time)) : "";

        $result['address_total'] = count($ids_info) > 0 ? count($ids_info) . '个' : 0;

        # 获取堆场信息
        $order_info = $order_data_service->getOrderInfoById($order_freight_id);
        $yard_info = $order_data_service->getOrderYardInfoById($order_info->yard_id);

        $result['yard_name'] = !empty($yard_info->yard_name) ? $yard_info->yard_name : "";
        $result['product_box_type'] = !empty($order_info->product_box_type) ? $this->order_config->box_type_define[$order_info->product_box_type] : "";
        $result['notice_info'] = !empty($order_info->product_desc) ? $order_info->product_desc : "";


        $res['data'] = array_merge($result, $box_info);

        # 记录log
        Logger::info(
            $this->constant->LOG_SEPORATER . "APPORDER COMPLETEDETAIL: driver_id " . $driver_id .
            $this->constant->LOG_SEPORATER . var_export($res['data'], true)
        );
        return $res;
    }


    /**
     * 产装、落箱确定
     */
    public function confirmBoxStatus($order_freight_id, $box_id, $driver_id = 0, $assign_status, $address_id = 0, $time_id = 0)
    {
        $conditions = array(
            "order_freight_id = ?1",
            "order_freight_boxid = ?2"
        );
        $bind = array(
            '1' => $order_freight_id,
            '2' => $box_id
        );

        if (!empty($driver_id)) {
            $conditions[] = "driver_user_id = ?3";
            $bind['3'] = $driver_id;
        }

        if (!empty($address_id)) {
            $conditions[] = "order_product_addressid = ?4";
            $bind['4'] = $address_id;
        }

        if (!empty($time_id)) {
            $conditions[] = "order_product_timeid = ?5";
            $bind['5'] = $time_id;
        }

        # 通过assign_status去更新address_status or drop_box_status
        $address_status = 0;
        $drop_box_status = 0;
        if ($assign_status == '100') {
            $address_status = 1;
        }
        if ($assign_status == '4') {
            $drop_box_status = 1;
        }

        # 开启事务
        $this->db->begin();

        # 更新assign_status
        $assign_info = \OrderAssignDriver::find(
            array(
                "conditions" => implode(' AND ', $conditions),
                "bind" => $bind
            )
        );

        foreach ($assign_info as $info) {
            $info->assign_status = $assign_status;
            $info->address_status = $address_status;
            $info->drop_box_status = $drop_box_status;
            $info->update_time = date('Y-m-d H:i:s');

            $res = $info->save();
            if (!$res) {
                $this->db->rollback();
                Logger::warn(
                    $this->constant->LOG_SEPORATER . "APPORDER confirmBoxStatus: " .
                    $this->constant->LOG_SEPORATER . var_export($conditions, true) .
                    $this->constant->LOG_SEPORATER . var_export($bind, true)
                );
                return false;
            }
        }

        $this->db->commit();
        return $res ? true : false;
    }

    /**
     * 检测此订单下箱子的assign_status
     */
    public function checkOrderAssignStatus($order_freight_id, $box_id = 0, $assign_status)
    {
        $res = true;

        $conditions = array(
            'order_freight_id = ?1'
        );
        $bind = array(
            '1' => $order_freight_id
        );
        if (!empty($box_id)) {
            $conditions[] = 'order_freight_boxid = ?2';
            $bind[2] = $box_id;
        }

        $assign_info = \OrderAssignDriver::find(
            array(
                "conditions" => implode(' AND ', $conditions),
                "bind" => $bind
            )
        );

        # 确定箱子是否已经在所有产装地产装完成或者落箱完成
        foreach ($assign_info as $value) {
            if ($value->assign_status != $assign_status) {
                $res = false;
                break;
            }
        }

        return $res;
    }

    /**
     * 更新箱子timeline
     */
    public function updateBoxTimeLine($order_freight_id, $box_id, $boxline_type, $driver_id = 0, $address_id = 0, $locate_info = '')
    {
        $box_timeline_data = array();

        # 分状态获取数据
        switch ($boxline_type) {
            case 'TIXIANG':
                $box_timeline_data['location_msg'] = $this->order_config->box_timeline_template->TIXIANG;
                break;
            case 'CHANZHUANG':
                $address_info = \OrderProductAddress::findFirst($address_id);
                $address_detail = !empty($address_info->address_detail) ? $address_info->address_detail : '';

                $order_freight_info = \OrderFreight::findFirst($order_freight_id);
                $yard_info = \YardInfo::findFirst($order_freight_info->yard_id);
                $yard_name = !empty($yard_info->yard_name) ? $yard_info->yard_name : '';

                $box_timeline_data['location_msg'] = str_replace(
                    array('{address_detail}', '{yard_name}'),
                    array($address_detail, $yard_name),
                    $this->order_config->box_timeline_template->CHANZHUANG
                );
                break;
            case 'LUOXIANG':
                $order_freight_info = \OrderFreight::findFirst($order_freight_id);
                $box_code = !empty($order_freight_info->box_code) ? $order_freight_info->box_code : '';
                $box_timeline_data['location_msg'] = str_replace(
                    array('{box_code}'),
                    array($box_code),
                    $this->order_config->box_timeline_template->LUOXIANG
                );
                break;
            case 'YUNDI':
                $order_freight_info = \OrderFreight::findFirst($order_freight_id);
                $tidan_code = !empty($order_freight_info->tidan_code) ? $order_freight_info->tidan_code : '';
                $box_timeline_data['location_msg'] = str_replace(
                    array('{tidan_code}'),
                    array($tidan_code),
                    $this->order_config->box_timeline_template->YUNDI
                );
                break;
            case 'ARRIVE_CHANZHUANG':
            case 'ARRIVE_PORT':
                $driver_info = \TbDriver::findFirst($driver_id);
                $car_number = !empty($driver_info->car_number) ? $driver_info->car_number : '';
                $box_timeline_data['location_msg'] = str_replace(
                    array('{car_number}', '{app_locate_content}'),
                    array($car_number, $locate_info),
                    $this->order_config->box_timeline_template->$boxline_type
                );
                break;
            default:
                break;
        }

        $box_timeline_data['order_freight_id'] = $order_freight_id;
        $box_timeline_data['order_freight_boxid'] = $box_id;
        $box_timeline_data['boxline_type'] = $this->order_config->assign_status_enum->$boxline_type ? $this->order_config->assign_status_enum->$boxline_type : 0;
        $box_timeline_data['verify_ream_id'] = $driver_id ? $driver_id : 0;
        $box_timeline_data['create_time'] = $box_timeline_data['update_time'] = date('Y-m-d H:i:s');

        $order_box_timeline = new \OrderBoxTimeline();
        $res = $order_box_timeline->save($box_timeline_data);

        return $res;
    }

    /**
     * 更新订单timeline
     */
    public function updateOrderTimeLine($order_freight_id, $ordertimeline_type)
    {
        $data['order_freight_id'] = $order_freight_id;
        $data['ordertimeline_type'] = $ordertimeline_type;
        $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');

        $order_timeline = new \OrderFreightTimeline();
        return $order_timeline->save($data);
    }

    /**
     * 更新订单状态
     */

    public function updateOrderStatus($order_freight_id, $order_status) {
        /*\OrderFreight::setup(array(
            'notNullValidations' => false
        ));*/

        $order_freight_info = \OrderFreight::findFirst($order_freight_id);

        $order_freight_info->order_status = $order_status;
        $order_freight_info->update_time = date('Y-m-d H:i:s');

        return $order_freight_info->save();

        /*foreach($order_freight_info->getMessages() as $message) {
            echo $message->__toString() . PHP_EOL;
        }*/
    }


    /**
     * 验证箱子是否属于此司机
     */
    public function checkBoxOwner($order_freight_id, $box_id, $driver_id)
    {
        $columns = 'id';
        $conditions = 'order_freight_id = ?1 AND order_freight_boxid = ?2 AND driver_user_id = ?3';
        $bind = array(1 => $order_freight_id, 2 => $box_id, 3 => $driver_id);
        $assign_info = \OrderAssignDriver::findFirst(array(
            'columns' => $columns,
            'conditions' => $conditions,
            'bind' => $bind
        ));

        if (empty($assign_info)) {
            return false;
        }

        return true;
    }

}