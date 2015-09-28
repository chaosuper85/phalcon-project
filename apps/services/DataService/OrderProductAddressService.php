<?php
/**
 * Created by PhpStorm.
 * User: gyao
 * Date: 15/8/19
 * Time: 下午12:12
 */

namespace Services\DataService;

use Library\Helper\ArrayHelper;
use Library\Helper\OrderLogHelper;
use Library\Helper\StringHelper;
use Library\Log\Logger;
use \Exception;
use Phalcon\Mvc\User\Component;
use OrderProductAddress;

class OrderProductAddressService extends Component {

    /**
     *  订单新增产装地址时，先选择装箱日期（一个地址 可能有多个 装箱日期 ）
     *  1 先创建  产装地址
     *  2 再建    产装时间
     */
    public function create( $orderId,$productDate,$address=array(),$addressDetail,$contactName,$contactMobile){
         $ret = true;
        try{
            Logger::info("orderId:%s", $orderId);
            $productAddress = $this->createProductAddress( $orderId,$address,$contactName,$contactMobile,$addressDetail);
            if( !empty($productAddress) ){
                if (is_array($productDate)) { // 时间数组
                    // 过滤一个地址  相同的时间
                    $filterDateArr = ArrayHelper::filterSameTime($productDate);
                    foreach ($filterDateArr as $date) {
                        $ret = $this->OrderProductTimeService->createProductTime($orderId, $productAddress->id, StringHelper::strToDate($date));
                        if (!$ret) {
                            break;
                        }
                    }
                } else {
                    $ret = $this->OrderProductTimeService->createProductTime($orderId, $productAddress->id, StringHelper::strToDate($productDate));
                }
            }else{
                $ret = false;
            }
        }catch (Exception $e){
            Logger::warn(" create Product Address error:".$e->getMessage()." orderId:".$orderId);
            $ret = false;
        }
        if( $ret ){ // 创建成功
            $this->ActivityLogComponent->orderHistroyLog(OrderLogHelper::addProAddress( $orderId,$productAddress->id,$address['provinceid'],$address['cityid'],$address['townid'],$addressDetail,$contactName,$contactMobile ) );
            return $productAddress;
        }else{
            return false;
        }
    }


    //创建 一条产装地址
    public function createProductAddress($orderFreightId, $address = array(), $contactName = "", $contactMobile = "", $addressDetail = "") {
        $productAddress = new OrderProductAddress();
        if( empty( $orderFreightId ) || empty($address) || empty( $contactMobile ) || empty( $contactName ) || empty( $addressDetail )  ) {
            Logger::warn(" createProductAddress error:order{%s} address:{%s} contactMobile:{%s} contactName:{%s} addressDetail:{%s}", $orderFreightId,var_export( $address,true),$contactMobile,$contactName,$addressDetail );
            return true;
        }
        $productAddress->order_freight_id = $orderFreightId;
        $productAddress->contact_name = $contactName;
        $productAddress->contact_number = $contactMobile;
        $productAddress->address_provinceid = $address['provinceid'];
        $productAddress->address_cityid = $address['cityid'];
        $productAddress->address_townid = $address['townid'];
        $productAddress->address_detail = $addressDetail;
        $productAddress->enable = 1;
        $res = $productAddress->create();
        if(!$res)
            Logger::warn("return: %s :create ProductAddress error message:%s", $res, var_export($productAddress->getMessages(), true));
        else{
            $this->AddressComponent->getLocationEvent( $productAddress->id );
        }
        return $res ? $productAddress: false ;
    }
    
    //更新一条 产装地址信息
    public function updateProductAddress($orderid, $id, $address = array(), $contactName = null, $contactMobile = null, $addressDetail = null){
        $productAddress = $this->getProductAddressById($id);
        if( empty( $orderid ) || empty($address) || empty( $contactMobile ) || empty( $contactName ) || empty( $addressDetail )  ) {
            Logger::warn(" createProductAddress error:order{%s} address:{%s} contactMobile:{%s} contactName:{%s} addressDetail:{%s}", $orderid,var_export( $address,true),$contactMobile,$contactName,$addressDetail );
            return true;
        }

        //判断产装地址信息，是否可修改
        $ret = $this->OrderAssignDriverService->canBeUpdated( $productAddress->order_freight_id, $id, 0 );
        if( !$ret ){
            Logger::info("product_address_id: %s, cannot be updated", $id);
            return true;
        }

        //如果提交的修改信息和数据库一致，则不做修改，也记录修改产装地址的日志
        if( $productAddress->contact_name == $contactName
            && $productAddress->contact_number == $contactMobile
            && $productAddress->address_provinceid == $address['provinceid']
            && $productAddress->address_cityid == $address['cityid']
            && $productAddress->address_townid == $address['townid']
            && $productAddress->address_detail == $addressDetail ){
            Logger::info("id:%s, address:%s, contactName:%s, contactMobile:%s, addressDetail:%s no changed, return true", $id, var_export($address,true), $contactName, $contactMobile, $addressDetail );
            return true;
        }

        $productAddress->contact_name = $contactName;
        $productAddress->contact_number = $contactMobile;
        $productAddress->address_provinceid = $address['provinceid'];
        $productAddress->address_cityid = $address['cityid'];
        $productAddress->address_townid = $address['townid'];
        $productAddress->address_detail = $addressDetail;
        $res = $productAddress->update();
        if( !$res ){
            Logger::warn("update Product Address: %s", var_export($productAddress->getMessage(), true));
            return false;
        }else{
            // 更新产装地址信息记录到日志
            $data = OrderLogHelper::updateProAddress( $orderid, $id, $address['provinceid'], $address['cityid'], $address['townid'], $addressDetail, $contactName, $contactMobile );
            $this->ActivityLogComponent->orderHistroyLog( $data );

            $this->AddressComponent->getLocationEvent( $productAddress->id );
        }

        return true;
    }

    //根据 id 获取唯一一条 产装地址信息
    public function getProductAddressById($id){
        $conditions = "id = ?1 and enable =1 ";
        $params = array(1 => $id );
        return OrderProductAddress::findFirst(array(
            "conditions" => $conditions,
            "bind" => $params
        ));
    }

    //根据 order_freight_id 获得部分产装信息 --返回可能多个
    public function getSomeInfoByOrderFreightId($orderFreightId){
        $sql = 'select address_provinceid, address_cityid, address_townid, address_detail from order_product_address where order_freight_id = ? AND enable = 1 ';
        $arr = $this->db->query($sql, [$orderFreightId])->fetchAll();
        if(empty($arr)){
            Logger::warn('产装信息为空，检查代码或者参数!');
            return false;
        }
        foreach($arr as $key => $value)
            foreach($value as $key1 => $value1){
                if(strcmp($key1, 'address_detail') != 0){
                    if(empty($cityName = $this->getCityNameById($key1))){
                        Logger::warn('获取cityName 为空!');
                        return false;
                    }
                    $value[$key1] = $cityName;
                }
            }
        return empty($this->arrToStr($arr)) ? false : $arr;
    }

    //通过 id 获取 cityName
    public function getCityNameById($id)
    {
        $sql = 'select cityName from tbl_province where id = ?';
        $arr = $this->db->query($sql, [$id])->fetchAll();
        return $arr[0]['cityName'];
    }

    //将 地址 数组转换成 字符串
    public function arrToStr(&$arr){
        if(empty($arr)){
            Logger::warn('城市数组 为空！');
            return false;
        }
        $str = '';
        foreach($arr as $key => $value){
            foreach($value as $key1 => $value1)
                $str .= $value1;
            $arr[$key] = $str;
        }
        return true;
    }


    /**
     *   查询 订单所有的 产装地址 by orderId
     * @return  OrderProductaddress[]
     */
    public function getByOrderId( $orderId ){
        $addressArr = OrderProductAddress::find(array(
            "conditions" => "order_freight_id = ?1 and enable =1 ",
            "bind"       =>  array( 1 => $orderId ),
        ));
        return  count( $addressArr ) ? $addressArr : array();
    }


    /**
     *  产装地址详情 by orderId
     *  @return array
     */
    public function addressDetails( $orderId ){
        // 产装地址
        $addressArr = $this->getByOrderId( $orderId);
        $addressInfo = array();
        if( !empty($addressArr) ){
            foreach( $addressArr as $address ){
                // 一个产装地址  ，可能有多个产装时间
                $timeArr = \OrderProductTime::find(array(
                    "conditions" =>" order_product_addressid=?1  and order_freight_id =?2 and enable =1",
                    "bind"       =>  array( 1=> $address->id ,2 => $orderId ),
                    "columns"    => "product_supply_time,id as product_time_id,order_product_addressid",
                    "order"      => "product_supply_time asc"
                ));
                $box_date = array();
                $addressCanBeChanged = true;// 默认地址可以修改
                if( count($timeArr) ){
                    foreach( $timeArr as $time){
                        $timeTemp     = $time->toArray(); // 一个地址 =》 一个时间 若分派司机表有 已产装状态，则 地址、时间不可修改
                        $canBeUpdated = $this->OrderAssignDriverService->canBeUpdated( $orderId, $address->id, $time->product_time_id );
                        if( !$canBeUpdated ){ // false 不能被修改
                            $timeTemp['time_can_change'] =  false ; // 时间不能被修改
                            $addressCanBeChanged         =  false ; // 地址不能修改
                        }else{
                            $timeTemp['time_can_change'] =  true ; // 时间可以修改
                        }
                        $box_date[] = $timeTemp;
                    }
                }else{
                    $box_date[] = array("product_supply_time" =>"", "product_time_id"  => "", "order_product_addressid" =>"", "time_is_changed" => "",);
                }
                $fullName      = $this->CityService->getFullNameById( $address->address_townid );
                $addressDetail = $fullName.$address->address_detail;
                Logger::info($addressDetail."=". $fullName."+".$address->address_detail);
                $addressTemp = array(
                    "product_address_id" => $address->id,
                    "box_address"        => $address->address_detail,
                    "box_address_detail" => $addressDetail,
                    "contactName"        => $address->contact_name,
                    "contactNumber"      => $address->contact_number,
                    "address_provinceid" => $address->address_provinceid,
                    "address_cityid"     => $address->address_cityid,
                    "address_townid"     => $address->address_townid,
                    "address_can_change" => $addressCanBeChanged,
                    "box_date"           => $box_date
                );
                $addressInfo[] = $addressTemp;
            }
        }else{
            $addressInfo[]  = array(
                "product_address_id" => "",
                "box_address"        => "",
                "box_address_detail" => "",
                "contactName"        => "",
                "contactNumber"      => "",
                "address_provinceid" => "",
                "address_cityid"     => "",
                "address_townid"     => "",
                "box_date"           => array(),
            );
        }
        return $addressInfo;
    }

    /*
     * 删除某个订单的产装地址
     */
    public function delOrderProductAddress( $orderId )
    {
        // 产装地址
        $addressArr = OrderProductAddress::find(array(
            "conditions" => "order_freight_id = ?1 AND enable = 1 ",
            "bind"       =>  array( 1 => $orderId ),
        ));

        $count = count($addressArr);
        foreach( $addressArr as $address ){
            $address->enable = 0;
            $ret = $address->save();
            if( !$ret ){
                Logger::warn("orderId:%s, address->save failed", $orderId);
            }
        }

        return $count;
    }


    /**
     *  获取 产装 详细 的 产装地址
     */
    public  function  getDetailAddressById( $id ){
        $address  = $this->getProductAddressById( $id );
        if( empty( $address ) ){
            $addressDetail = "";
        }else{
            $fullName = $this->CityService->getFullNameById( $address->address_townid );
            $addressDetail = $fullName.$address->address_detail;
            Logger::info($addressDetail."=". $fullName."+".$address->address_detail);
        }
        return $addressDetail;
    }


    //根据 id 删除唯一一条 产装地址信息
    public function delProductAddressById( $id ){
        $conditions = "id = ?1 AND enable = 1 ";
        $params = array(1 => $id);
        $orderProductAddress = OrderProductAddress::findFirst(array(
            "conditions" => $conditions,
            "bind" => $params
        ));

        if( !empty($orderProductAddress) ){
            $orderProductAddress->enable = 0;
            $ret = $orderProductAddress->save();
            if( !$ret ){
                Logger::warn('orderProductAddress->delete return fail: %s', var_export($orderProductAddress->getMessages(),true));
                return false;
            }
        }else{
            Logger::warn('OrderProductAddress id: %s, find no record', $id);
            return false;
        }

        return true;
    }

    public function saveNewProductAddress( $orderId,$productDate,$address=array(),$addressDetail,$contactName,$contactMobile){
        $ret = true;
        try{
            $productAddress = $this->createProductAddress( $orderId,$address,$contactName,$contactMobile,$addressDetail);
            if( !empty($productAddress) ){
                if( is_array( $productDate )){ // 时间数组
                    foreach( $productDate as $key => $date){
                        $ret = $this->OrderProductTimeService->createProductTime($orderId, $productAddress->id, $date['product_supply_time']);
                        if( !$ret ){
                            Logger::warn('OrderProductTimeService->createProductTime return false');
                            break;
                        }
                    }
                }
            }else{
                $ret = false;
            }
        }catch (Exception $e){
            Logger::warn(" create Product Address error:".$e->getMessage()." orderId:".$orderId);
            $ret = false;
        }

        if( $ret ){ // 创建成功
            $this->ActivityLogComponent->orderHistroyLog(OrderLogHelper::addProAddress( $orderId,$productAddress->id,$address['provinceid'],$address['cityid'],$address['townid'],$addressDetail,$contactName,$contactMobile ) );
            return true;
        }else{
            return false;
        }
    }

}