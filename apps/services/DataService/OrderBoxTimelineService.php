<?php
namespace Services\DataService;


use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use OrderBoxTimeline;
use TbDriver;
use Users;


class OrderBoxTimelineService extends Component
{
    public function  save($order_freight_id, $order_freight_boxid, $boxline_type, $verify_ream_type, $verify_ream_id, $jsonContent )
    {
        //记录箱子的timeline，
        $orderBoxTimeline = new OrderBoxTimeline();
        $orderBoxTimeline->order_freight_id = $order_freight_id;
        $orderBoxTimeline->order_freight_boxid = $order_freight_boxid;
        $orderBoxTimeline->boxline_type = $boxline_type;
        $orderBoxTimeline->verify_ream_type = $verify_ream_type;
        $orderBoxTimeline->verify_ream_id = $verify_ream_id;
        $orderBoxTimeline->location_msg = $this->getLocationMsg($order_freight_id, $order_freight_boxid);
        $orderBoxTimeline->jsonContent = $jsonContent;
        $ret = $orderBoxTimeline->save();

        return $ret;
    }


    //通过 箱子 order_freight_box_id 获取箱子 产装详细地址及堆场信息
    public function getBoxAddress($order_freight_box_id){
        $sql = 'SELECT `address_detail` FROM `order_freight_box` WHERE `id` = ?';
        $str = '';
        $arr = $this->db->fetchAll($sql, 2, [$order_freight_box_id]);
        if(!empty($arr))
            $str .= $arr[0]['address_detail'];
        return $str;
    }
    //通过 订单 order_freight_id 获取堆场名字
    public function getYardName($order_freight_id){
        $sql1 = 'SELECT `yard_id` FROM `order_freight` WHERE `id` = ?';
        $sql2 = 'SELECT `yard_name` FROM `yard_info` WHERE `id` = ?';
        $str = '';
        $arr1 = $this->db->fetchAll($sql1, 2, [$order_freight_id]);
        if(!empty($arr1)){
            $arr2 = $this->db->fetchAll($sql2, 2, [$arr1[0]['yard_id']]);
            if(!empty($arr2))
                $str .= $arr2[0]['yard_name'];
        }
        return $str;
    }
    //获取完整的 location_msg
    public function getLocationMsg($order_freight_id, $order_freight_box_id){
        $boxAddress = $this->getBoxAddress($order_freight_box_id);
        $yardName = $this->getYardName($order_freight_id);
        $msg = $this->order_config->box_timeline_template->CHANZHUANG;
        return str_replace(array('{address_detail}', '{yard_name}'), array($boxAddress, $yardName), $msg);
    }

    public function boxTimelineUpdate($order_freight_id, $order_freight_boxid,  $box_ensupe, $box_code )
    {

        $msg = "已确认箱号及铅封号，箱   号：".$box_code."  铅封号：".$box_ensupe;

        //记录箱子的timeline，
        $orderBoxTimeline = new OrderBoxTimeline();
        $orderBoxTimeline->order_freight_id = $order_freight_id;
        $orderBoxTimeline->order_freight_boxid = $order_freight_boxid;
        $orderBoxTimeline->location_msg = $msg ;
        $ret = $orderBoxTimeline->save();

        return $ret;

    }


    public function boxTimelineDriverUpdate($order_freight_id, $order_freight_boxid,  $driver_user_id )
    {

        $driver = TbDriver::findFirst(
            array(
                "conditions" => "userid = ?1",
                "bind" => array(1 => $driver_user_id)
            )
        );

        if( empty($driver) ){
            Logger::warn("driver user id: %s not found", $driver_user_id);
            return false;
        }

        $driverName = $driver->driver_name;


        $conditions = " id = ?1  and enable=?2 ";
        $bind = array(1 => $driver_user_id, 2 => 1);
        $user = Users::findFirst(array(
            $conditions,
            "bind" => $bind
        ));

        if( empty($user) ){
            Logger::warn("user id :%s, not found", $driver_user_id);
            return false;
        }

        $driverMobile = $user->mobile;

        $msg = "完成配车，司机:".$driverName."，联系电话: ".$driverMobile;

        //记录箱子的timeline，
        $orderBoxTimeline = new OrderBoxTimeline();
        $orderBoxTimeline->order_freight_id = $order_freight_id;
        $orderBoxTimeline->order_freight_boxid = $order_freight_boxid;
        $orderBoxTimeline->location_msg = $msg ;
        $ret = $orderBoxTimeline->save();

        return $ret;

    }
}