<?php


namespace Services\LogicService;


use Library\Helper\ArrayHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Exception;


class OrderAddressConfirmService extends Component
{

    public function orderAddressConfirm( $req )
    {

        $orderid = $req['orderid'];
        $address_info = $req['address_info'];

        /*
         *
         * 1，删除订单的产装地址
         * 2，删除订单的产装时间
         * 3，插入新的产装地址和产装时间
         * 4，记录产装地址和产装时间的历史，
         *
         *
         * 2015年9月7日，修改
         *
         * 根据address_info数组的内容，进行处理
         *
         * 产装地址和产装时间的逻辑操作，严格按照顺序
         *
         * 1，如果订单的product_address_id在数据库，不在传递列表，则删除产装信息。
         * 2，如果有product_address_id，则修改，对应的信息，（如果已产装，则不允许修改。）
         * 3，如果没有，则增加产装信息，
         *
         * 1，插入activity_log的方式变化，
         * 2，查看修改记录的变化，
         * 3，分派已产装的产装地址和时间，不允许修改，只传address_id,time_id，其他不传，其他部分的允许修改，逻辑增加
         *
         */

        $product_address_id_arr = array();
        //增加的产装地址信息
        $add_address_info = array();
        foreach( $address_info as $key => $value )
        {
            if( !empty($value['product_address_id']) ){
                $product_address_id_arr[] = $value['product_address_id'];
            }else if( empty($value['product_address_id']) ){
                $add_address_info[]  = $value;
                unset( $address_info[$key] );
            }

        }

        //一个订单在数据库里的产装地址信息
        $order_product_address_id = array();
        $order_address_info = $this->OrderProductAddressService->getByOrderId( $orderid );
        foreach( $order_address_info as $key => $value ){
            $order_product_address_id[]  = $value->id;
        }

        //启用事务，异常处理
        try{

            $this->db->begin();

            //$del_address_id_arr,订单的product_address_id在数据库，不在传递列表，则删除产装地址信息和产装时间信息。
            $del_address_id_arr = array_diff( $order_product_address_id, $product_address_id_arr );
            //执行订单的产装地址和产装时间的删除
            foreach($del_address_id_arr as $key=>$value){
                //success return true, fail return false
                $ret = $this->OrderProductAddressService->delProductAddressById( $value );
                if( !$ret ){
                    $this->db->rollback();
                    Logger::warn("OrderProductAddressService->delProductAddressById, value: %s", $value);
                    return false;
                }

                //success return true, fail return false
                $ret = $this->OrderProductTimeService->delOrderProductTimeByAddressId( $value );
                if( !$ret ){
                    $this->db->rollback();
                    Logger::warn("OrderProductTimeService->delOrderProductTimeByAddressId, value: %s", $value);
                    return false;
                }
            }

            //$add_address_info,执行订单的产装地址和产装时间的增加，
            foreach($add_address_info as $key=>$value){
                // success return true, fail return false
                $ret = $this->OrderProductAddressService->saveNewProductAddress($orderid, $value['box_date'], $value['box_address'], $value['box_address']['address'], $value['contactName'], $value['contactNumber'] );
                if( !$ret ){
                    $this->db->rollback();
                    Logger::warn("OrderProductAddressService->saveNewProductAddress return false");
                    return false;
                }
            }

            //$address_info,订单的产装地址和产装时间的修改,可能会删除订单的产装时间。
            //执行订单的产装地址和产装时间的修改
            foreach($address_info as $key => $value){

                $product_address_id = $value['product_address_id'];
                //success return true, fail return false
                $box_address = isset( $value['box_address'] )?$value['box_address']:array();
                $contactName = isset( $value['contactName'] )?$value['contactName']:"";
                $contactNumber = isset( $value['contactNumber'] )?$value['contactNumber']:"";
                $address = isset( $value['box_address']['address'] )?$value['box_address']['address']:"";

                if( empty($box_address) && empty($contactName) && empty($contactNumber) && empty($address) ){
                    //订单的产装地址信息，不允许修改，前端也没传递产装地址信息的case
                    Logger::info("orderid: %s, product_address_id:%s not change", $orderid, $product_address_id);
                }else{
                    $ret = $this->OrderProductAddressService->updateProductAddress($orderid, $product_address_id, $box_address,  $contactName, $contactNumber, $address  );
                    if( !$ret ){
                        $this->db->rollback();
                        Logger::warn("OrderProductAddressService->updateProductAddress return false");
                        return false;
                    }
                }

                //修改产装地址的时候，可能会增加，修改，删除产装时间。
                $product_time_arr = $value['box_date'];

                //依次执行产装地址对应的产装时间列表的，1，删除，2，修改，3，增加，操作。
                $add_product_time_info = array();
                $product_time_id_arr = array();
                //需要增加的产装时间
                $add_product_time_arr = array();
                foreach( $product_time_arr as $key=>$value ){
                    if( !empty($value['product_time_id']) ){
                        $product_time_id_arr[] = $value['product_time_id'];
                    }else if( empty($value['product_time_id']) && !empty($value['product_supply_time']) ){
                        $add_product_time_arr[] = $value['product_supply_time'];
                        unset( $product_time_arr[$key] );
                    }
                }

                //一个订单的一个产装地址在数据库里的产装时间列表
                $productTime = $this->OrderProductTimeService->getProductTime( $orderid, $product_address_id );
                $productTimeIdArr = array();
                foreach( $productTime as $key=>$value ){
                    $productTimeIdArr[] = $value['id'];
                }

                //$del_product_time在数据库里，不在传递列表，删除产装时间信息
                $del_product_time = array_diff($productTimeIdArr, $product_time_id_arr);
                foreach($del_product_time as $key=>$value)
                {
                    //success return true, fail return false
                    $ret = $this->OrderProductTimeService->delOrderProductTimeById( $value );
                    if( !$ret ){
                        $this->db->rollback();
                        Logger::warn("OrderProductTimeService->delOrderProductTimeById return false");
                        return false;
                    }
                }

                //$product_time_arr需要修改，产装时间
                foreach( $product_time_arr as $key=>$value ){
                    //success return true, fail return false

                    $product_time_id = $value['product_time_id'];
                    $product_supply_time = isset( $value['product_supply_time'] )?$value['product_supply_time']:"";
                    if( empty($product_supply_time) ){
                        //针对产装时间，不允许修改的情况下，前端也没有传递的case
                        Logger::info("orderid: %s, product_address_id:%s,product_time_id:%s not change", $orderid, $product_address_id, $product_time_id);
                    }else{
                        $ret = $this->OrderProductTimeService->updateProductTime( $value['product_time_id'], $value['product_supply_time'] );
                        if( !$ret ){
                            $this->db->rollback();
                            Logger::warn("OrderProductTimeService->updateProductTime return false");
                            return false;
                        }
                    }
                }

                //$add_product_time_arr需要新增，产装时间
                foreach( $add_product_time_arr as $key=>$value ){
                    $ret = $this->OrderProductTimeService->createProductTime($orderid, $product_address_id, $value);
                    if( !$ret ){
                        $this->db->rollback();
                        Logger::warn("OrderProductTimeService->createProductTime return false");
                        return false;
                    }
                }
            }
        }catch (Exception $e){
            Logger::warn("orderAddressConfirm exception: %s", var_export($e->getTraceAsString(),true));
            $this->db->rollback();
            return false;
        }

        $this->db->commit();
        Logger::info("orderid: ".$orderid." orderAddressConfirm return ok");

        return true;
    }


}
