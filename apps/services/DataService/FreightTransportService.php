<?php
namespace Services\DataService;

use Library\Helper\PageHelper;
use Library\Helper\StringHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;

//返回物流相信信息
class FreightTransportService extends Component{
    //test
    public function test($id){
        $sql = 'select * from tbl_province where id = ?';
        return $this->db->fetchAll($sql, 2, [$id]);
    }
    //通过 order_freight_id 获取订单 timeLine
    public function getOrderStatusById($order_freight_id){
        $sql = 'SELECT ordertimeline_type, verify_ream_type, create_time FROM `order_freight_timeline` WHERE `order_freight_id` = ? ORDER BY `ordertimeline_type` DESC';
        return $this->db->fetchAll($sql, 2, [$order_freight_id]);
    }
    //通过 order_freight_id 获取所有集装箱列表
    public function getBoxList($orderFreightId){
        $sql = 'SELECT id, box_ensupe, box_code, box_weight, address_provinceid, address_cityid, address_townid, address_detail FROM `order_freight_box` WHERE `order_freight_id` = ? ORDER BY `id` ASC';
        $arr = $this->db->fetchAll($sql, 2, [$orderFreightId]);
        Logger::info('查看物流详情--获得集装箱列表');
        return $arr;
    }
    /*
     * 产装联系单-集装箱 id 是 唯一的，和箱子上写的号码 不是一回事
     * 通过 箱子 id 找到唯一的 userid，order_product_addressid，
     */
    public function getDriverIdANDOtherByBoxId($orderFreightBoxId){
        $sql = 'SELECT order_freight_id, order_product_addressid, driver_user_id, assign_status, address_status FROM `order_assign_driver` WHERE `order_freight_boxid` = ? AND `enable` = 1';
        return $this->db->fetchAll($sql, 2, [$orderFreightBoxId]);
    }
    //通过 order_freight_box_id 反查 产装详细地址
    public function getDetailAddressByOrderFreightBoxId($orderFreightBoxId){
        $sql = 'SELECT box_ensupe, box_code, address_detail, box_size_type FROM `order_freight_box` WHERE `id` = ?';
        return $this->db->fetchAll($sql, 2, [$orderFreightBoxId]);
    }
    //通过 orderid 查看箱子类型
    public function getBoxType($order_freight_id){
        $sql = 'SELECT `product_box_type` FROM `order_freight` WHERE `id` = ?';
        $result = '';
        $arr = $this->db->fetchAll($sql, 2, [$order_freight_id]);
        if(!empty($arr))
            $result = $arr[0]['product_box_type'];
        return $result;
    }
    //通过 userid获取 企业id、联系人名字 及 联系电话
    public function getDriverInfoByUserid($userid){
        $sql = 'SELECT enterpriseid, real_name, contactName, mobile, contactNumber, unverify_enterprisename FROM `users` WHERE `id` = ?';
        return $this->db->fetchAll($sql, 2, [$userid]);
    }
    //通过userid获取司机名字
    public function getDriverName($userid){
        $sql = 'SELECT `driver_name`, `car_number` FROM `tb_driver` WHERE `userid` = ?';
        $result = array(
            'driver_name' => '',
            'car_number' => '',
        );
        $arr = $this->db->fetchOne($sql, 2, [$userid]);
        if(!empty($arr)) {
            $result['driver_name'] = $arr['driver_name'];
            $result['car_number'] = $arr['car_number'];
        }
        return $result;
    }
    //对 order_freight 表进行查询
    public function getOrderFreightInfoById($orderFreightId){
        $sql1 = 'SELECT `tidan_code`, `yundan_code`, `freightagent_user`, `carrier_userid`, `yard_id` FROM `order_freight` WHERE `id` = ?';
        return $this->db->fetchOne($sql1, 2, [$orderFreightId]);
    }
    //通过 order_freight_id 获取堆场 信息字符串
    public function getYardInfoFromOrderFreightId($orderFreightId){
        $yardArr = $this->getOrderFreightInfoById($orderFreightId);
        if(empty($yardArr)){
            Logger::warn('堆场数据为空');
            return false;
        }
        $yard_id = $yardArr['yard_id'];
        $sql2 = 'SELECT cock_city_code, yard_name FROM `yard_info` WHERE `id` = ?';
        $yardInfoArr = $this->db->fetchOne($sql2, 2, [$yard_id]);
        $str = '';
        if(!empty($yardInfoArr))
            $str .= $yardInfoArr['yard_name'];
        return $str;
    }
    //通过-userid- 获取(id，联系人名字，联系手机号，公司名字)
    public function getEnterpriseNameNumberById($id){
        $driverInfoArr = $this->getDriverInfoByUserid($id);
        $result = array();
        if(!empty($driverInfoArr)){
            $result['id'] = $id;
            $result['contactName'] = $driverInfoArr[0]['contactName'];
            $result['contactNumber'] = $driverInfoArr[0]['contactNumber'];
            $result['name'] = $driverInfoArr[0]['unverify_enterprisename'];
        }
        return $result;
    }
    //返回箱子状态
    public function getBoxStatus($order_freight_box_id){
        $sql = 'SELECT `box_status` FROM `order_freight_box` WHERE `id` = ?';
        $arr = $this->db->fetchOne($sql, 2, [$order_freight_box_id]);
        $boxStatus = -1;
        if(!empty($arr))
            $boxStatus = $arr['box_status'];
        return $boxStatus;
    }
    //通过 箱号 获取 (司机 车队名字、司机名字、司机手机号、产装地址、产装箱子类型、堆场名字)
    public function getDriverInfoByOrderFreightBoxId($orderFreightBoxId){
        $driverArr = array();
        $driverArr['yard_name'] = '';
        $driverArr['box_address'] = '';
        $driverArr['box_size_type'] = '';
        $driverArr['box_type'] = '';
        $driverArr['box_ensupe'] = '';
        $driverArr['box_code'] = '';
        $driverArr['box_status'] = $this->getBoxStatus($orderFreightBoxId);
        $driverArr['driver_name'] = '';
        $driverArr['driver_mobile'] = '';
        $driverArr['car_number'] = '';
        $driverArr['team_name'] = '';
        $driverArr['address'] = '';
        $driverArr['create_time'] = '';
        $arr = $this->getDriverIdANDOtherByBoxId($orderFreightBoxId);
        if(!empty($arr)){
            $driverArr['box_type'] = $this->getBoxType($arr[0]['order_freight_id']);
            $yardName = $this->getYardInfoFromOrderFreightId($arr[0]['order_freight_id']);
            if(!empty($yardName))
                $driverArr['yard_name'] = $yardName;
            $addressArr = $this->OrderFreightBoxDetailService->getProductOneDriver($orderFreightBoxId);
            $driverArr['box_address'] = $addressArr[0]['city_name'].' '.$addressArr[0]['address_detail'];
            $detailAddressArr = $this->getDetailAddressByOrderFreightBoxId($orderFreightBoxId);
            if(!empty($detailAddressArr)) {
                $driverArr['box_size_type'] = $detailAddressArr[0]['box_size_type'];
                $driverArr['box_ensupe'] = $detailAddressArr[0]['box_ensupe'];
                $driverArr['box_code'] = $detailAddressArr[0]['box_code'];
            }
            $driverId = $arr[0]['driver_user_id'];
            $carInfo = $this->getDriverName($driverId);
            $driverArr['driver_name'] = $carInfo['driver_name'];
            $driverArr['car_number'] = $carInfo['car_number'];
            $arr1 = $this->getDriverInfoByUserid($driverId);
            if(!empty($arr1))
                $driverArr['driver_mobile'] = $arr1[0]['mobile'];
            $sql = 'SELECT teamName FROM `car_team_user` WHERE `userid` = ?';
            $teamArr = $this->db->fetchAll($sql, 2, [$driverId]);
            if(!empty($teamArr))
                $driverArr['team_name'] = $teamArr[0]['teamName'];
            $address = $this->getBoxAdressById($orderFreightBoxId);
            if(!empty($address)){
                $driverArr['address'] = $address['address'];
                $driverArr['create_time'] = $address['create_time'];
            }
        }
        return $driverArr;
    }

    public function getCarNumByOrderFreightBoxId($orderFreightBoxId){
        $arr = $this->getDriverIdANDOtherByBoxId($orderFreightBoxId);
        if(!empty($arr)){
            $driverId = $arr[0]['driver_user_id'];
            $sql = 'SELECT car_number FROM `tb_driver` WHERE `userid` = ?';
            $carNumArr = $this->db->fetchAll($sql, 2, [$driverId]);
            if(!empty($carNumArr))
                return $carNumArr[0]['car_number'];
        }
        return '';
    }
    //通过 id 获取 箱号
    public function getBoxCodeById($id){
        $sql = 'SELECT box_code FROM `order_freight_box` WHERE `id` = ?';
        return $this->db->fetchAll($sql, 2, [$id]);
    }
    //获取 提单号
    public function getTidanCodeById($id){
        $sql = 'SELECT tidan_code FROM `order_freight` WHERE `id` = ?';
        return $this->db->fetchAll($sql, 2, [$id]);
    }
    //通过 order_freight_box_id 获取箱子 timeLine
    public function getBoxTimelineByBoxId($order_freight_box_id){
        $sql = 'SELECT create_time, location_msg FROM `order_box_timeline` WHERE `order_freight_boxid` = ? ORDER BY `create_time` DESC';
        $boxArr = $this->db->fetchAll($sql, 2, [$order_freight_box_id]);
        $result = array();
        if(!empty($boxArr)){
            foreach($boxArr as $key => $value) {
                $isWithin = $this->isWithinResult($value['location_msg'], $result);
                if(!empty($value['location_msg']) && !$isWithin) {
                    $result[] = array(
                        'create_time' => $value['create_time'],
                        'content' => $value['location_msg'],
                    );
                }
            }
        }
        return $result;
    }
    //校验 当前要存储的字符串 是否存在
    public function isWithinResult($content, $result = array()){
        $mark = false;
        if(!empty($result))
            foreach($result as $key => $value)
                if(strcmp($content, $value['content']) == 0){
                    $mark = true;
                    break;
                }
        return $mark;
    }

    //通过订单 id 获取订单 timeline列表
    public function getTimelineById($order_freight_id){
        $orderStatusArr = $this->getOrderStatusById($order_freight_id);
        $result = array(
            '1' => array(
                'ok' => 0,
                'create_time' => ' ',
            ),
            '2' => array(
                'ok' => 0,
                'create_time' => ' ',
            ),
            '3' => array(
                'ok' => 0,
                'create_time' => ' ',
            ),
            '4' => array(
                'ok' => 0,
                'create_time' => ' ',
            ),
        );
        if(!empty($orderStatusArr)) {
            foreach($orderStatusArr as $key => $value){
                $status = $value['ordertimeline_type'];
                if($status == 4) {
                    $result['4']['ok'] = 1;
                    $result['3']['ok'] = 1;
                    $result['2']['ok'] = 1;
                    $result['1']['ok'] = 1;
                    $result['4']['create_time'] = $value['create_time'];
                }
                elseif($status == 3){
                    $result['3']['ok'] = 1;
                    $result['2']['ok'] = 1;
                    $result['1']['ok'] = 1;
                    $result['3']['create_time'] = $value['create_time'];
                }
                elseif($status == 2){
                    $result['2']['ok'] = 1;
                    $result['1']['ok'] = 1;
                    $result['2']['create_time'] = $value['create_time'];
                }
                elseif($status == 1){
                    $result['1']['ok'] = 1;
                    $result['1']['create_time'] = $value['create_time'];
                }
            }
        }
        return $result;
    }
    //以箱号 作为参数 获取装箱信息
    public function getBoxAdressById($order_freight_box_id){
        $sql1 = 'SELECT `order_freight_id` FROM `order_freight_box` WHERE `id` = ?';
        $sql2 = 'SELECT `address_provinceid`, `address_cityid`, `address_townid`, `address_detail`, `update_time` FROM `order_product_address` WHERE `order_freight_id` = ?';
        $infoArr = $this->db->fetchAll($sql1, 2, [$order_freight_box_id]);
        $addressInfo = array(
            'address' => '',
            'create_time' => '',
        );
        if(!empty($infoArr)){
            $arr2 = $this->db->fetchAll($sql2, 2, [$infoArr[0]['order_freight_id']]);
            if(!empty($arr2)) {
                $cityName = $this->OrderFreightBoxDetailService->getProfectCityName($arr2[0]['address_provinceid'], $arr2[0]['address_cityid'], $arr2[0]['address_townid']);
                $addressInfo['address'] = $cityName . ' ' . $infoArr[0]['address_detail'];
                $addressInfo['create_time'] = $infoArr[0]['update_time'];
            }
        }
        return $addressInfo;
    }

    //通过详细id获取箱子产装地
    public function getChanzhuang($order_freight_box_id){
        $sql1 = 'SELECT `order_product_addressid` FROM `order_assign_driver` WHERE `order_freight_boxid` = ?';
        $sql2 = 'SELECT `latitude`, `longitude` FROM `order_product_address` WHERE `id` = ?';
        $arr = $this->db->fetchAll($sql1, 2, [$order_freight_box_id]);
        $result = array();
        if(!empty($arr))
            foreach($arr as $key => $value){
                $arr2 = $this->db->fetchOne($sql2, 2, [$value['order_product_addressid']]);
                if (!empty($arr2))
                    $result[] = array(
                        'longitude' => $arr2['longitude'],
                        'latitude' => $arr2['latitude'],
                    );
            }
        if(empty($result))
            $result = array(
                array(
                    'longitude' => 117.1994308610,
                    'latitude' => 39.0849967352,
                ),
            );
        return $result;
    }
    //通过 order_freight_id 获取堆场位置
    public function getDuichang($order_freight_id){
        $sql1 = 'SELECT yard_id FROM `order_freight` WHERE `id` = ?';
        $sql2 = 'SELECT longitude, latitude FROM `yard_location` WHERE `yard_id` = ?';
        $result = array(
            'longitude' => '117.1994308610',
            'latitude' => '39.0849967352',
        );
        $arr1 = $this->db->fetchOne($sql1, 2, [$order_freight_id]);
        if(!empty($arr1)){
            $yardId = $arr1['yard_id'];
            $arr2 = $this->db->fetchOne($sql2, 2, [$yardId]);
            if(!empty($arr2)){
                $result['longitude'] = $arr2['longitude'];
                $result['latitude'] = $arr2['latitude'];
            }
        }
        return $result;
    }
    //获取当前时间经纬度
    public function getCurrent($order_freight_box_id){
        $sql = 'SELECT box_longitude, box_latitude, create_time FROM `order_box_location` WHERE `order_freight_boxid` = ? ORDER BY `create_time` DESC';
        $arr = $this->db->fetchOne($sql, 2, [$order_freight_box_id]);
        $result = array(
            'longitude' => 0,
            'latitude' => 0,
        );
        if(!empty($arr)){
            $result['longitude'] = $arr['box_longitude'];
            $result['latitude'] = $arr['box_latitude'];
            $result['create_time'] = $arr['create_time'];
        }
        return $result;
    }
    //通过箱子id 获取 产装地址及产装时间
    public function getChanzhuangInfo($order_freight_box_id){
        $sql1 = 'SELECT `order_product_addressid`, `order_product_timeid` FROM `order_assign_driver` WHERE `order_freight_boxid` = ?';
        $sql2 = 'SELECT `address_provinceid`, `address_cityid`, `address_townid`, `address_detail` FROM `order_product_address` WHERE `id` = ? AND `enable` = 1';
        $sql3 = 'SELECT `product_supply_time` FROM `order_product_time` WHERE `id` = ? AND `enable` = 1';
        $result = array();
        $arr1 = $this->db->fetchAll($sql1, 2, [$order_freight_box_id]);
        if(!empty($arr1)){
            foreach($arr1 as $key => $value) {
                $str = '';
                $arr2 = $this->db->fetchOne($sql2, 2, [$value['order_product_addressid']]);
                if(!empty($arr2)){
                    $cityName = $this->OrderFreightBoxDetailService->getProfectCityName($arr2['address_provinceid'], $arr2['address_cityid'], $arr2['address_townid']);
                    $str .= ($cityName.$arr2['address_detail']);
                }
                $arr3 = $this->db->fetchOne($sql3, 2, [$value['order_product_timeid']]);
                if(!empty($arr3)){
                    $str .= (' '.$arr3['product_supply_time']);
                }
                if(!empty($str))
                    $result[] = $str;
            }
        }
        return $result;
    }

    //通过 经纬度 获取地址
    public function getAddressByLocation($current = array()){
        $result = array(
            'address' => '未定位到司机当前位置!',
            'timeDiffer' => '',
        );
        if(!empty($current)){
            if($current['longitude'] != 0 && $current['latitude'] != 0) {
                $location = $current['longitude'] . ',' . $current['latitude'];
                $createTime = $current['create_time'];
                $currentTime = time();
                $data = \Library\Helper\LocationHelper::getAdressByLocation($location);
                if ($data) {
                    var_dump($data);
                    $timeDiffer = $this->timeToRead($currentTime, $createTime);
                    $result = array(
                        'address' => $data,
                        'timeDiffer' => $timeDiffer,
                    );
                }
            }
        }
        return $result;
    }
    //创建时间和当前时间时间差 格式化
    public function timeToRead($current, $createTime){
        $timeDiffer = $current - strtotime($createTime);
        $minute = 0;
        $hour = 0;
        $day = 0;
        $minuteDiffer = (int)($timeDiffer / 60);
        if($minuteDiffer != 0) {
            $minute = $minuteDiffer % 60;
            $hourDiffer = (int)($minuteDiffer / 60);
            if($hourDiffer != 0) {
                $hour = $hourDiffer % 24;
                $day = (int)($hourDiffer / 24);
            }
        }
        if($day > 0)
            $result = date('Y年m月d日 h:i', strtotime($createTime));
        elseif($hour > 0)
            $result = $hour.'小时前';
        elseif($minute > 0)
            $result = $minute.'分钟前';
        else
            $result = '刚刚';
        return $result;
    }
    //总的方法 通过 订单id 获取所有信息
    public function getMsg($order_freight_id, $list = 0){
        //获取订单当前状态
        $result = array();
        Logger::info('order_freight_id = '.$order_freight_id);
        $orderFreightSomeInfo = $this->getOrderFreightInfoById($order_freight_id);
        if(empty($orderFreightSomeInfo)){
            Logger::warn('获取物流详情--通过订单号获取信息错误');
            return false;
        }
        $result['id'] = $order_freight_id;
        $result['box_status'] = 1;
        $result['tidan'] = $orderFreightSomeInfo['tidan_code'];
        $result['yundan'] = $orderFreightSomeInfo['yundan_code'];
        $carrierId = $orderFreightSomeInfo['carrier_userid'];
        $result['freight'] = $this->getEnterpriseNameNumberById($carrierId);
        if(empty($result['freight'])){
            Logger::warn('获取物流详情--获取物流公司信息错误!');
            return false;
        }
        $result['order_time_line'] = $this->getTimelineById($order_freight_id);
        $box_time_line = array();
        $boxArr = $this->getBoxList($order_freight_id);
        if(!empty($boxArr)) {
            foreach($boxArr as $key => $value){
                $order_freight_box_id = $value['id'];
                $box_time_line[$key] = $this->getDriverInfoByOrderFreightBoxId($order_freight_box_id);
                $detailArr = $this->getBoxTimelineByBoxId($order_freight_box_id);
                $box_time_line[$key]['detail'] = $detailArr;
                $detail_count = count($detailArr);
                $box_time_line[$key]['detail_last'] = array(
                    'create_time' => '',
                    'content' => '',
                );
                $box_time_line[$key]['chanzhuanginfo'] = $this->getChanzhuangInfo($order_freight_box_id);
                if(!empty($detail_count))
                    $box_time_line[$key]['detail_last'] = $detailArr[0];
                $current = $this->getCurrent($order_freight_box_id);
                $box_time_line[$key]['location_info'] = array(
                    'chanzhuang' => $this->getChanzhuang($order_freight_box_id),
                    'current' => $current,
                    'currentAddress' => $this->getAddressByLocation($current),
                    'duichang' => $this->getDuichang($order_freight_id),
                );
            }
        }
        $result['box_time_line'] = $box_time_line;
        $result['list'] = $list;
        return $result;
    }
}