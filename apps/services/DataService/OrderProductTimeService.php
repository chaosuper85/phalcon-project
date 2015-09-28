<?php
namespace Services\DataService;

use Library\Helper\OrderLogHelper;
use Library\Helper\StringHelper;
use Phalcon\Mvc\User\Component;
use OrderProductTime;
use Library\Log\Logger;

/**
 *  产装时间
 */
class OrderProductTimeService extends Component
{



    //创建 产地产装时间
    public function createProductTime($orderFreightId, $orderProductAddressId, $productSipplyTime){
        $orderProductTime = new OrderProductTime();
        if( empty($orderFreightId) || empty($orderProductAddressId) || empty($productSipplyTime)){
            Logger::warn("参数传递出错，请查看!");
            return false;
        }
        $orderProductTime->order_freight_id = $orderFreightId;
        $orderProductTime->order_product_addressid = $orderProductAddressId;
        $orderProductTime->product_supply_time = $productSipplyTime;    //前台传递时间格式
        $res = $orderProductTime->create();
        if(!$res)
            Logger::warn("create ProductTime: %s",var_export($orderProductTime->getMessages(), true));
        else{
            $data  = OrderLogHelper::addProDate( $orderFreightId, $productSipplyTime,$orderProductTime->id,$orderProductAddressId );
            $this->ActivityLogComponent->orderHistroyLog( $data );
        }
        return $res ? $orderProductTime : false;
    }

    //通过 orderFreightId 及 orderProductAddressId获取 产装时间
    public function getProductTime($orderFreightId, $orderProductAddressId) {
        if(empty($orderFreightId) || empty($orderProductAddressId)){
            Logger::warn("参数传递出错，请查看！");
            return false;
        }
        $sql = "select * from order_product_time where order_freight_id = ? and order_product_addressid = ?";
        $res = $this->db->query($sql, [$orderFreightId, $orderProductAddressId])->fetchAll();
        if(!$res)
            Logger::info("get Product Time: %s", var_export($res,true));
        return $res;
    }

    //更新 产装时间表
    public function updateProductTime( $id, $productSipplyTime){
        if( empty($id) ){
            Logger::warn("id 传入错误， 请检查！");
            return false;
        }
        $orderProductTime = $this->getProductTimeById($id);
        $address_id = $orderProductTime->order_product_addressid;

        //判断产装地址信息，是否可修改
        $ret = $this->OrderAssignDriverService->canBeUpdated( $address_id, $id );
        if( !$ret ){
            Logger::info("product_address_id: %s, time_id: %s, cannot be updated", $address_id, $id);
            return true;
        }

        //如果修改的内容和数据库一样，则不修改
        $newProTime = strtotime( $productSipplyTime );
        $oldProTime = strtotime( $orderProductTime->product_supply_time );
        if(   $newProTime == $oldProTime   ){
            Logger::info("productSipplyTime:%s no change, return true",$productSipplyTime);
            return true;
        }

        $orderProductTime->product_supply_time = StringHelper::strToDate( $newProTime );
        $res = $orderProductTime->update();
        if( !$res ){
            Logger::warn("update Product Time: %s return false", var_export($orderProductTime->getMessages(), true));
            return false;
        } else{
            $data  = OrderLogHelper::updateProDate( $orderProductTime->order_freight_id, $orderProductTime->product_supply_time,$orderProductTime->id,$orderProductTime->order_product_addressid );
            $this->ActivityLogComponent->orderHistroyLog( $data );
        }

        return true;
    }

    //由id 得到唯一的一个 产装时间表
    public function getProductTimeById($id){
        $conditions = "id = ?1 and enable =1 ";
        $params = array(1 => $id);
        return OrderProductTime::findFirst(array(
            'conditions' => $conditions,
            'bind' => $params
        ));
    }

    //通过 order_freight_id 查询 产装时间
    public function getSupplyTimeByFreightId($orderFreughId){
        $sql = 'select order_product_addressid from order_product_time where order_freight_id = ?';
        return $this->db->query($sql, [$orderFreughId])->fetchAll();
    }

    /*
     * 删除某个订单的产装时间
     */
    public function delOrderProductTime( $orderId )
    {
        // 产装时间列表
        $productTimeArr = OrderProductTime::find(
            array(
            "conditions" => "order_freight_id = ?1",
            "bind"       =>  array( 1 => $orderId ),
        ));

        foreach( $productTimeArr as $productTime ){
            $productTime->enable = 0;
            $res  = $productTime->save();
            if( $res ){
                $data  = OrderLogHelper::delProDate( $productTime->order_freight_id, $productTime->product_supply_time,$productTime->id,$productTime->order_product_addressid );
                $this->ActivityLogComponent->orderHistroyLog( $data );
            }
        }

        return true;
    }

    /*
    * 删除product_address_id的产装时间
    */
    public function delOrderProductTimeByAddressId( $product_address_id )
    {
        // 产装时间列表
        $productTimeArr = OrderProductTime::find(
            array(
                "conditions" => "order_product_addressid = ?1",
                "bind"       =>  array( 1 => $product_address_id ),
            ));

        if( count($productTimeArr) ){
            foreach( $productTimeArr as $productTime ){
                $productTime->enable = 0;
                $ret = $productTime->save();
                if( !$ret ){
                    Logger::warn('productTime->delete return fail, %s', var_export($productTime->getMessages(),true));
                    return false;
                }else{
                    $data  = OrderLogHelper::delProDate( $productTime->order_freight_id, $productTime->product_supply_time,$productTime->id,$productTime->order_product_addressid );
                    $this->ActivityLogComponent->orderHistroyLog( $data );
                }
            }
        }

        return true;
    }

    /*
    * 删除某个订单的产装时间
    */
    public function delOrderProductTimeById( $id )
    {
        // 产装时间列表
        $productTime = OrderProductTime::findFirst(
            array(
                "conditions" => "id = ?1",
                "bind"       =>  array( 1 => $id ),
            ));

        if( empty($productTime) ){
            Logger::warn('OrderProductTime::findFirst return no record');
            return false;
        }

        $productTime->enable = 0;
        $ret = $productTime->save();
        if( !$ret ){
            Logger::warn('productTime->delete error: %s',var_export($productTime->getMessages(),true));
            return false;
        }else{
            $data  = OrderLogHelper::delProDate( $productTime->order_freight_id, $productTime->product_supply_time,$productTime->id,$productTime->order_product_addressid );
            $this->ActivityLogComponent->orderHistroyLog( $data );
        }

        return true;
    }





    public function listProDate( $orderId, $proAddressId ){
        $sql ="SELECT date_format( product_supply_time,'%Y-%m-%d %H:%i:%s') FROM order_product_time WHERE order_freight_id = ? AND order_product_addressid = ?";
        $dateList = $this->db->fetchAll( $sql,2,[ $orderId,$proAddressId]);
        return $dateList;
    }


}