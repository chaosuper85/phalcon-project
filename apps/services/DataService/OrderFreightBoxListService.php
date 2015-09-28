<?php
//产装联系单列表 车队用户查看 详情
namespace Services\DataService;

use Library\Log\Logger;
use Phalcon\Mvc\User\Component;

class OrderFreightBoxListService extends Component {

    //通过 userid--carrier_userid 获取订单箱子数目及订单id
    public function getBoxTotalNumber($carrier_userid){
        $sql = 'SELECT id, box_20gp_count, box_40gp_count, box_40hq_count FROM `order_freight` WHERE `carrier_userid` = ?';
        return $this->db->fetchAll($sql, 2, [$carrier_userid]);
    }

    //通过id获取
    public function getBoxTotalNumber1($order_freight_id){
        $sql = 'SELECT id, yundan_code, product_box_type, box_20gp_count, box_40gp_count, box_40hq_count FROM `order_freight` WHERE `id` = ?';
        return $this->db->fetchOne($sql, 2, [$order_freight_id]);
    }

    //通过 order_freight_id 获取 箱子信息
    public function getBoxInfo($order_freight_id){
        $sql = 'SELECT id, box_ensupe, box_code, box_size_type FROM `order_freight_box` WHERE `order_freight_id` = ?';
        return $this->db->fetchAll($sql, 2, [$order_freight_id]);
    }

    //通过箱号获取 司机信息 及车牌号
    public function getCarInfo($order_freight_box_id){
        $result = array();
        $driverInfo = $this->getDriverInfo($order_freight_box_id);
        $carNum = $this->FreightTransportService->getCarNumByOrderFreightBoxId($order_freight_box_id);
        if(!empty($driverInfo)){
            $result['driver_name'] = $driverInfo['driver_name'];
            $result['driver_number'] = $driverInfo['driver_mobile'];
        }else{
            $result['driver_name'] = '';
            $result['driver_number'] = '';
        }
        if(!empty($carNum))
            $result['car_number'] = $carNum;
        else
            $result['car_number'] = '';
        return $result;
    }
    //对 单个订单 添加多余箱型
    public function addIncompleteBox($order_freight, $order_freight_box_arr){
        if(!empty($order_freight_box_arr)) {
            foreach ($order_freight_box_arr as $key => $value) {
                if ($value['box_size_type'] == 1)
                    $order_freight['box_20gp_count']--;
                elseif ($value['box_size_type'] == 2)
                    $order_freight['box_40gp_count']--;
                elseif ($value['box_size_type'] == 3)
                    $order_freight['box_40hq_count']--;
            }
        }
        return $order_freight;
    }

    //获取总消息
    public function getMsg($userid){
        $result = array();
        $boxTotal = $this->getBoxTotalNumber($userid);
        if(!empty($boxTotal)){
            foreach($boxTotal as $key => $value){
                $boxInfo = $this->getBoxInfo($value['id']); //一个订单箱子信息
                if(!empty($boxInfo)){
                    foreach($boxInfo as $key1 => $value1){
                        $car = $this->getCarInfo($value1['id']);
                        $result[] = array(
                            'id' => $value1['id'],
                            'box_type' => $value1['box_size_type'],
                            'box_ensupe' => $value1['box_ensupe'],
                            'box_code' => $value1['box_code'],
                            'driver_name' => $car['driver_name'],
                            'driver_number' => $car['driver_number'],
                            'car_number' => $car['car_number'],
                        );
                    }
                }
            }
            $arr = array();
            $boxInfo = $this->getBoxInfo($boxTotal['id']); //一个订单箱子信息
            $arr[] = $this->addIncompleteBox($boxTotal, $boxInfo);
            foreach($arr as $key => $value){
                while($value['box_20gp_count'] > 0){
                    $result[] = array(
                        'box_type' => '20gp',
                        'box_ensupe' => '',
                        'box_code' => '',
                        'driver_name' => '',
                        'driver_number' => '',
                        'car_number' => '',
                    );
                    $value['box_20gp_count']--;
                }
                while($value['box_40gp_count'] > 0){
                    $result[] = array(
                        'box_type' => '40gp',
                        'box_ensupe' => '',
                        'box_code' => '',
                        'driver_name' => '',
                        'driver_number' => '',
                        'car_number' => '',
                    );
                    $value['box_40gp_count']--;
                }
                while($value['box_40hq_count'] > 0){
                    $result[] = array(
                        'box_type' => '40hq',
                        'box_ensupe' => '',
                        'box_code' => '',
                        'driver_name' => '',
                        'driver_number' => '',
                        'car_number' => '',
                    );
                    $value['box_40hq_count']--;
                }
            }
        }
        return $result;

    }
    //通过 order_freight_box_id 获取司机信息
    public function getDriverInfo($order_freight_box_id){
        $sql1 = 'SELECT `driver_user_id` FROM `order_assign_driver` WHERE `order_freight_boxid` = ?';
        $sql2 = 'SELECT `driver_name` FROM `tb_driver` WHERE `userid` = ?';
        $sql3 = 'SELECT `mobile` FROM `users` WHERE `id` = ?';
        $arr1 = $this->db->fetchOne($sql1, 2, [$order_freight_box_id]);
        $result = array(
            'driver_name' => '',
            'driver_mobile' => '',
        );
        if(!empty($arr1)){
            $arr2 = $this->db->fetchOne($sql2, 2, [$arr1['driver_user_id']]);
            $arr3 = $this->db->fetchOne($sql3, 2, [$arr1['driver_user_id']]);
            if(!empty($arr2))
                $result['driver_name'] = $arr2['driver_name'];
            if(!empty($arr3))
                $result['driver_mobile'] = $arr3['mobile'];
        }
        return $result;
    }
    //获取总消息
    public function getMsgByOrderFreightId($order_freight_id){
        $result = array();
        $boxTotal = $this->getBoxTotalNumber1($order_freight_id);
        $data = array();
        if(!empty($boxTotal)){
            $orderInfo = array(
                'orderid' => $order_freight_id,
                'yundan_code' => $boxTotal['yundan_code'],
            );
            $boxInfo = $this->getBoxInfo($boxTotal['id']); //一个订单箱子信息
            if(!empty($boxInfo)){
                foreach($boxInfo as $key1 => $value1){
                    $car = $this->getCarInfo($value1['id']);
                    $result[] = array(
                        'box_id' => $value1['id'],
                        'box_type' => $boxTotal['product_box_type'],
                        'box_size_type' => $value1['box_size_type'],
                        'box_ensupe' => $value1['box_ensupe'],
                        'box_code' => $value1['box_code'],
                        'driver_name' => $car['driver_name'],
                        'contactNumber' => $car['driver_number'],
                        'car_number' => $car['car_number'],
                    );
                }
            }
            $arr = array();
            $boxInfo = $this->getBoxInfo($boxTotal['id']); //一个订单箱子信息
            $arr[] = $this->addIncompleteBox($boxTotal, $boxInfo);
            foreach($arr as $key => $value){
                while($value['box_20gp_count'] > 0){
                    $result[] = array(
                        'box_type' => $boxTotal['product_box_type'],
                        'box_size_type' => '1',
                        'box_ensupe' => '',
                        'box_code' => '',
                        'driver_name' => '',
                        'driver_number' => '',
                        'car_number' => '',
                    );
                    $value['box_20gp_count']--;
                }
                while($value['box_40gp_count'] > 0){
                    $result[] = array(
                        'box_type' => $boxTotal['product_box_type'],
                        'box_size_type' => '2',
                        'box_ensupe' => '',
                        'box_code' => '',
                        'driver_name' => '',
                        'driver_number' => '',
                        'car_number' => '',
                    );
                    $value['box_40gp_count']--;
                }
                while($value['box_40hq_count'] > 0){
                    $result[] = array(
                        'box_type' => $boxTotal['product_box_type'],
                        'box_size_type' => '3',
                        'box_ensupe' => '',
                        'box_code' => '',
                        'driver_name' => '',
                        'driver_number' => '',
                        'car_number' => '',
                    );
                    $value['box_40hq_count']--;
                }
            }
            $data = array(
                'assign_list' => $result,
                'orderInfo' => $orderInfo,
            );
        }
        return $data;
    }

}