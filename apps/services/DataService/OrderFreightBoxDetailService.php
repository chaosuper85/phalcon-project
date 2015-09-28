<?php
//产装联系单 详情
namespace Services\DataService;

use Library\Helper\PageHelper;
use Library\Helper\StringHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;

class OrderFreightBoxDetailService extends Component{
    //已知参数 order_freight_box_id

    //从表`order_freight_box`表中获取相应信息 箱子铅封号、箱号、箱子重良
    public function getInfoFromOrderFreightBox($id){
        $sql = 'SELECT `id`, `order_freight_id`, `box_ensupe`, `box_code`, `box_size_type`, `box_weight`, `address_townid`, `address_detail` FROM `order_freight_box` WHERE `id` = ?';
        return $this->db->fetchOne($sql, 2, [$id]);
    }
    //从表`order_freight` 中获取相应信息 运单号，起运港，提单号，船名字id，船次，船次信息，货物名称， 箱子。
    public function getInfoFromOrderFreight($id){
        $sql = 'SELECT `yundan_code`, `freightagent_user`, `cock_city_code`, `carrier_userid`, `tidan_code`, `ship_name_id`, `shipping_company_id`, `ship_ticket`, `ship_ticket_desc`,
                `yard_id`, `product_name`, `product_desc`, `product_weight`, `box_20gp_count`, `box_40gp_count`, `box_40hq_count`, `create_time` FROM `order_freight` WHERE `id` = ?';
        return $this->db->fetchOne($sql, 2, [$id]);
    }
    //获取 船名字及船公司名字
    public function getShipNameAndCompanyName($ship_name_id, $shipping_company_id){
        $sql = 'SELECT `china_name`, `eng_name` FROM `ship_name` WHERE `id` = ?';
        $sql1 = 'SELECT `china_name`, `english_name` FROM `shipping_company` WHERE `id` = ?';
        $result = array(
            'ship_name' => '',
            'ship_company_name' => '',
        );
        $shipNameArr = $this->db->fetchOne($sql, 2, [$ship_name_id]);
        if(!empty($shipNameArr))
            $result['ship_name'] = $shipNameArr['china_name'].' '.$shipNameArr['eng_name'];
        $companyNameArr = $this->db->fetchOne($sql1, 2, [$shipping_company_id]);
        if(!empty($companyNameArr))
            $result['ship_company_name'] = $companyNameArr['china_name'].' '.$companyNameArr['english_name'];
        return $result;
    }
    //获取堆场名字通过id
    public function getYardName($id){
        $sql = 'SELECT yard_name FROM `yard_info` WHERE `id` = ?';
        $yardArr = $this->db->fetchOne($sql, 2, [$id]);
        $str = '';
        if(!empty($yardArr))
            $str = $yardArr['yard_name'];
        return $str;
    }
    //通过id获取城市名字
    public function getCityNameById($id){
        $sql = 'SELECT cityName FROM `tbl_province` WHERE `id` = ?';
        $cityNameArr = $this->db->fetchOne($sql, 2, [$id]);
        $cityName = '';
        if(!empty($cityNameArr))
            $cityName = $cityNameArr['cityName'];
        return $cityName;
    }
    //通过 省id， 市id， 县id获取全名
    public function getProfectCityName($provinceid, $cityid, $townid){
        $province = $this->getCityNameById($provinceid);
        $city = $this->getCityNameById($cityid);
        $town = $this->getCityNameById($townid);
        $str = '';
        if(empty($province) || empty($city) || empty($town)){
            Logger::warn('获取产装联系单详情页--获取省市县全名出错!');
            return $str;
        }
        if(empty(strcmp($province, $city)))
            $str .= ($city.' '.$town);
        else
            $str .= ($province.' '.$city.' '.$town);
        return  $str;

    }
//获取 单个司机的产状地址信息
    public function getProductOneDriver($order_freight_box_id){
        $sql1 = 'SELECT `order_product_addressid`, `order_product_timeid` FROM `order_assign_driver` WHERE `order_freight_boxid` = ? AND `enable` = 1';
        $sql2 = 'SELECT `product_supply_time` FROM `order_product_time` WHERE `id` = ? AND `enable` = 1';
        $sql3 = 'SELECT `contact_name`, `contact_number`, `address_provinceid`, `address_cityid`, `address_townid`, `address_detail` FROM `order_product_address` WHERE `id` = ? AND `enable` = 1';
        $result = array(
            array(
                'contactName' => '',
                'contactNumber' => '',
                'city_name' => '',
                'address_detail' => '',
                'box_date' => '',
                'box_address' => '',
            )
        );
        $arr1 = $this->db->fetchAll($sql1, 2, [$order_freight_box_id]);
        if(!empty($arr1)){
            $result = array();
            foreach($arr1 as $key => $value){
                $address = array(
                    'contactName' => '',
                    'contactNumber' => '',
                    'city_name' => '',
                    'address_detail' => '',
                    'box_date' => '',
                    'box_address' => '',
                );
                $addressid = $value['order_product_addressid'];
                $timeid = $value['order_product_timeid'];
                $arr2 = $this->db->fetchOne($sql2, 2, [$timeid]);
                $arr3 = $this->db->fetchOne($sql3, 2, [$addressid]);
                if(!empty($arr2))
                    $address['box_date'] = $arr2['product_supply_time'];
                if(!empty($arr3)){
                    $address['contactName'] = $arr3['contact_name'];
                    $address['contactNumber'] = $arr3['contact_number'];
                    $address['city_name'] = $this->getProfectCityName($arr3['address_provinceid'], $arr3['address_cityid'], $arr3['address_townid']);
                    $address['address_detail'] = $arr3['address_detail'];
                    $address['box_address'] = $address['city_name'].' '.$address['address_detail'];
                }
                $result[] = $address;
            }
        }
        return $result;
    }
    //通过order_freight_box_id 获取箱子类型 及 数量
    public function getBoxInfo($order_freight_box_id){
        $sql1 = 'SELECT `order_freight_id`, `box_size_type` FROM `order_freight_box` WHERE `id` = ?';
        $sql2 = 'SELECT `product_box_type` FROM `order_freight` WHERE `id` = ?';
        $result = array(
            'box_size_type' => '',
            'product_box_type' => '',
        );
        $arr1 = $this->db->fetchOne($sql1, 2, [$order_freight_box_id]);
        if(!empty($arr1)){
            $order_id = $arr1['order_freight_id'];
            $result['box_size_type'] = $arr1['box_size_type'];
            $arr2 = $this->db->fetchOne($sql2, 2, [$order_id]);
            if(!empty($arr2))
                $result['product_box_type'] = $arr2['product_box_type'];
        }
        return $result;
    }
    //通过 order_freight_box_id 获取 物流公司名字
    public function getCarrierCompanyName($order_freight_box_id){
        $sql1 = 'SELECT `order_freight_id` FROM `order_freight_box` WHERE `id` = ?';
        $sql2 = 'SELECT `carrier_userid` FROM `order_freight` WHERE `id` = ?';
        $sql3 = 'SELECt `unverify_enterprisename` FROM `users` WHERE `id` = ?';
        $result = '';
        $arr1 = $this->db->fetchOne($sql1, 2, [$order_freight_box_id]);
        if(!empty($arr1)){
            $arr2 = $this->db->fetchOne($sql2, 2, [$arr1['order_freight_id']]);
            if(!empty($arr2)){
                $arr3 = $this->db->fetchOne($sql3, 2, [$arr2['carrier_userid']]);
                if(!empty($arr3))
                    $result = $arr3['unverify_enterprisename'];
            }
        }
        return $result;
    }
    //总信息
    public function getMsg($order_freight_box_id){
        //箱子铅封号、箱号、箱子重良
        $box = $this->getInfoFromOrderFreightBox($order_freight_box_id);
        if(empty($box)){
            Logger::warn('获取产装联系单详情页--由箱子id获取箱子信息出错！');
            return false;
        }
        $data = array();
        $ship_info = array();
        $product_info = array();
        $carteam_info = array();
        $data['id'] = $box['id'];
        $data['box_ensupe'] = $box['box_ensupe'];
        $data['box_code'] = $box['box_code'];
        $data['orderid'] = $box['order_freight_id'];
        //运单号，起运港，提单号，船名字id，船次，船次信息，货物名称， 箱子
        $orderFreight = $this->getInfoFromOrderFreight($box['order_freight_id']);
        if(empty($orderFreight)){
            Logger::warn('获取产装联系单详情页--利用订单id获取订单出错！');
            return false;
        }
        $ship_info['dock_city_code'] = '天津';//$orderFreight['cock_city_code'];
        $ship_info['tidan_code'] = $orderFreight['tidan_code'];
        $ship = $this->getShipNameAndCompanyName($orderFreight['ship_name_id'], $orderFreight['shipping_company_id']);
        $ship_info['ship_name'] = $ship['ship_name'];
        $ship_info['ship_company_name'] = $ship['ship_company_name'];
        $ship_info['ship_ticket'] = $orderFreight['ship_ticket'];
        $ship_info['yard_id'] = $orderFreight['yard_id'];
        $ship_info['yard_name'] = $this->getYardName($ship_info['yard_id']);

        $product_info['product_name'] = $orderFreight['product_name'];
        $product_info['product_desc'] = $orderFreight['product_desc'];
        $product_info['product_weight'] = $box['box_weight'];
        $product_info['box_type_number'] = $this->getBoxInfo($order_freight_box_id);
        $address_info = $this->getProductOneDriver($order_freight_box_id);
        $driverInfo = $this->OrderFreightBoxListService->getDriverInfo($order_freight_box_id);
        $carteam_info['name'] = $driverInfo['driver_name'];
        $carteam_info['driver_number'] = $driverInfo['driver_mobile'];
        $carteam_info['car_number'] = $this->FreightTransportService->getCarNumByOrderFreightBoxId($order_freight_box_id);
        $carteam_info['box_number'] = $box['box_code'];
        $carteam_info['box_type'] = $box['box_size_type'];
        $data['ship_info'] = $ship_info;
        $data['carrier_company_name'] = $this->getCarrierCompanyName($order_freight_box_id);
        $data['product_info'] = $product_info;
        $data['address_info'] = $address_info;
        $data['carteam_info'] = $carteam_info;
        $data['create_time'] = date('Y-m-d', strtotime($orderFreight['create_time']));
        $data['yundan_code'] = $orderFreight['yundan_code'];
        return $data;
    }
}