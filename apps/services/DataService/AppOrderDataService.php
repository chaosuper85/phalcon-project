<?php
/**
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/9/9
 * Time: 上午11:48
 */

namespace Services\DataService;

use Phalcon\Mvc\User\Component;
use Library\Log\Logger;

class AppOrderDataService extends Component {

    /**
     * 获取assign info
     */
    public function getAssignInfoByUseridAndStatusList($driver_id, $status_list) {
        $assigns = array();

        $assigns = \OrderAssignDriver::find(
            array(
                "conditions"    => "driver_user_id = ?1 AND assign_status IN(" . implode(',', $status_list) . ") AND enable = 1",
                "bind"          => array(1 => $driver_id),
                "group"         => "order_freight_boxid",
                "order"         => "create_time DESC"
            )
        );

        return $assigns;
    }

    /**
     * 获取产装地址和时间id
     */
    public function getAddressIdAndTimeIdByUseridAndBoxid($driver_id, $box_id) {
        $ids_info = array();

        $ids_info = \OrderAssignDriver::find(
            array(
                "columns"       => "order_product_addressid, order_product_timeid",
                "conditions"    => "driver_user_id = ?1 AND order_freight_boxid = ?2 AND enable = 1",
                "bind"          => array(1 => $driver_id, 2 => $box_id),
                "order"         => "create_time ASC"
            )
        );

        return $ids_info;
    }

    /**
     * 获取产装地信息
     */
    public function getAddressInfoById($id, $columns = '*') {
        $address_info = \OrderProductAddress::findFirst(
            array(
                "columns"       => $columns,
                "conditions"    => "id = ?1 AND enable = 1",
                "bind"          => array(1 => $id)
            )
        );

        return $address_info;
    }

    /**
     * 获取产装时间信息
     */
    public function getTimeInfoById($id, $columns = '*') {
        $time_info = \OrderProductTime::findFirst(
            array(
                "columns"       => $columns,
                "conditions"    => "id = ?1 AND enable = 1",
                "bind"          => array(1 => $id)
            )
        );

        return $time_info;
    }

    /**
     * 获取订单信息
     */
    public function getOrderInfoById($id, $columns = '*') {
        $order_info = \OrderFreight::findFirst(
            array(
                "columns"       => $columns,
                "conditions"    => "id = ?1",
                "bind"          => array(1 => $id)
            )
        );

        return $order_info;
    }

    /**
     * 获取堆场信息
     */
    public function getOrderYardInfoById($id, $columns = '*') {
        $yard_info = \YardInfo::findFirst(
            array(
                "columns"       => $columns,
                "conditions"    => "id = ?1",
                "bind"          => array(1 => $id)
            )
        );

        return $yard_info;
    }

    /**
     * 取得箱子信息
     */
    public function getBoxInfo($box_id) {
        $res = array();

        $box_info = \OrderFreightBox::findFirst($box_id);
        switch($box_info->box_size_type) {
            case 1:
                $res['box_type'] = '20GP';
                break;
            case 2:
                $res['box_type'] = '40GP';
                break;
            case 3:
                $res['box_type'] = '40HQ';
                break;
            default:
                break;
        }

        $res['box_id'] = !empty($box_info->id) ? $box_info->id : 0;
        $res['box_code'] = !empty($box_info->box_code) ? $box_info->box_code : "";
        $res['box_ensupe'] = !empty($box_info->box_ensupe) ? $box_info->box_ensupe : "";
        $res['box_type'] = !empty($res['box_type']) ? $res['box_type'] : "";
        $res['box_status'] = !empty($box_info->box_status) ? $box_info->box_status : 0;

        return $res;
    }


    /**
     * 箱子状态信息（app只需要下发三种状态: 2、3、4）
     */
    public function getBoxTimeline($order_freight_id, $order_freight_boxid) {
        $data = array();

        $res = \OrderBoxTimeline::find(
            array(
                "conditions"    => "order_freight_id = ?1 AND order_freight_boxid = ?2",
                "bind"          => array(1 => $order_freight_id, 2 => $order_freight_boxid),
                "order"         => "create_time ASC"
            )
        );
        # 返回相应状态信息生成时间
        if (count($res)) {
            $type_list = array(
                # $this->order_config->assign_status_enum->TO_TIXIANG,
                $this->order_config->assign_status_enum->TO_CHANZHUANG,
                $this->order_config->assign_status_enum->TO_YUNDI,
                $this->order_config->assign_status_enum->LUOXIANG
            );

            foreach ($res as $key => $value) {
                $type = $value->boxline_type;
                if(in_array($type, $type_list)) {
                    $data[] = date('H:i m/d', strtotime($value->create_time));
                }
            }
        }

        return $data;
    }
}