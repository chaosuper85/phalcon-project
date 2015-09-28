<?php


namespace Services\LogicService;


use Library\Log\Logger;
use Exception;
use Phalcon\Mvc\User\Component;

use OrderAssignDriver;


class CarteamOrderAssignEditSaveService extends Component
{

    /*
                {
                    "order_assign_info": [
                        {
                            "assign_id": 1,
                            "order_freight_id": 12345,
                            "order_freight_boxid": 123,
                            "order_product_addressid": 1234567,
                            "driver_user_id": 123456789,
                            "order_product_timeid": 12345678
                        },
                        {
                            "order_freight_id": 12345,
                            "order_freight_boxid": 1234,
                            "order_product_addressid": 1234567,
                            "driver_user_id": 123456789,
                            "order_product_timeid": 12345678
                        },
                        {
                            "assign_id": 2
                        }
                    ]
                }
    */
    public function editSave( $req )
    {
        $order_assign_info = $req['order_assign_info'];

        if( count($order_assign_info) == 0 )
        {
            Logger::info(" assign ok ");
            return true;
        }

        $order_freight_id           = $order_assign_info[0]['order_freight_id'];
        $order_freight_boxid        = $order_assign_info[0]['order_freight_boxid'];
        $driver_user_id             = $order_assign_info[0]['driver_user_id'];

        $reamId =  $this->session->get('login_user')->id;

/*
        一个订单的一个箱子分派司机的逻辑操作，严格按照顺序
         * 1，如果传递assign_id,一个分派的order_product_addressid，order_product_timeid在数据库中，不在传递列表中，则删除分派（前提是：可删除）
         * 2，如果传递assign_id,没有传递order_product_addressid，order_product_timeid，则对数据库不做修改。
         * 3, 如果传递assign_id,有传递order_product_addressid，order_product_timeid，则对数据库进行修改。（前提是可修改）
         * 4，如果没有传递assign_id，则增加分派信息，（满足条件）

        5，增加司机的修改，和逻辑判断
 */

        //增加逻辑，如果箱子已经产装完成，则不允许修改分派信息
        $ret = $this->OrderAssignDriverService->saveBoxAssignPreCheck( $order_freight_boxid );
        if( !$ret ){
            Logger::warn( "OrderAssignDriverService->saveBoxAssignPreCheck, order_freight_boxid:%s",$order_freight_boxid );
            return false;
        }


        $add_assign_arr = array();
        $assign_id_arr = array();
        $no_change_assign_arr = array();
        foreach( $order_assign_info as $key=>$order_assign ){
            if( empty($order_assign['assign_id']) ){
                $add_assign_arr[] = $order_assign;
                unset( $order_assign_info[$key] );
            }else if( !empty($order_assign['assign_id']) && empty($order_assign['order_product_addressid']) && empty($order_assign['order_product_timeid']) ){
                $no_change_assign_arr[] = $order_assign;
                unset( $order_assign_info[$key] );
            }else{
                $assign_id_arr[] = $order_assign['assign_id'];
            }
        }
        $change_assign_arr = $order_assign_info;


        $this->db->begin();
        try{
                //删除的分派信息
                $db_assign_id = array();
                $db_assign_info = $this->OrderAssignDriverService->boxAssignInfo( $order_freight_id, $order_freight_boxid );
                foreach( $db_assign_info as $key=>$db_assign ){
                    $db_assign_id[] = $db_assign->id;
                }
                $del_assign_id = array_diff( $db_assign_id, $assign_id_arr );

                Logger::info("del_assign_id :%s",var_export($del_assign_id,true));
                foreach( $del_assign_id as $assign_id ){
                    //
                    $ret = $this->OrderAssignDriverService->delBoxAssignInfo( $assign_id );
                    if( !$ret ){
                        $this->db->rollback();
                        Logger::warn("OrderAssignDriverService->delBoxAssignInfo, assign_id: %s return false", $assign_id );
                        return false;
                    }
                }


                $driver_changeable = $this->OrderAssignDriverService->driverChangeable( $order_freight_id, $order_freight_boxid );

                //修改的分派信息
                Logger::info("change_assign_arr :%s",var_export($change_assign_arr,true));
                foreach( $change_assign_arr as $key => $change_assign_info ){
                    $assign_id = $change_assign_info['assign_id'];
                    $order_product_addressid = $change_assign_info['order_product_addressid'];
                    $order_product_timeid = $change_assign_info['order_product_timeid'];
                    //
                    $ret = $this->OrderAssignDriverService->changeAddressOrTime($assign_id, $driver_user_id, $order_product_addressid, $order_product_timeid, $driver_changeable );
                    if( !$ret ){
                        $this->db->rollback();
                        Logger::warn(" OrderAssignDriverService->changeAddressOrTime, assign_id:%s, order_product_addressid:%s, order_product_timeid:%s return false");
                        return false;
                    }
                }

                //增加的分派信息
                Logger::info("add_assign_arr :%s",var_export($add_assign_arr,true));
                foreach( $add_assign_arr as $key=>$add_assign_info ){
                    $order_freight_id = $add_assign_info['order_freight_id'];
                    $order_freight_boxid = $add_assign_info['order_freight_boxid'];
                    $driver_user_id = $add_assign_info['driver_user_id'];
                    $order_product_addressid = $add_assign_info['order_product_addressid'];
                    $order_product_timeid = $add_assign_info['order_product_timeid'];
                    $ret = $this->OrderAssignDriverService->create($order_freight_id, $order_product_addressid, $order_product_timeid, $order_freight_boxid, $driver_user_id );
                    if( !$ret ){
                        $this->db->rollback();
                        Logger::warn(" OrderAssignDriverService->create, order_freight_id:%s, order_product_addressid:%s, order_product_timeid:%s, order_freight_boxid:%s, driver_user_id:%s , return false ", $order_freight_id, $order_product_addressid, $order_product_timeid, $order_freight_boxid, $driver_user_id);
                       return false;
                    }
                }


            //如果增加分派司机，则记录到箱子的timeline里
                $ret = true;//$this->checkAddAsignDriver( $add_assign_arr );
                if( $ret ){
                    $ret = $this->OrderBoxTimelineService->boxTimelineDriverUpdate( $order_freight_id, $order_freight_boxid, $driver_user_id );
                    if( !$ret ){
                        Logger::warn("OrderBoxTimelineService->boxTimelineDriverUpdate,order_freight_id:%s, order_freight_boxid:%s, driver_user_id:%s, return false,",$order_freight_id, $order_freight_boxid, $driver_user_id);
                        return false;
                    }
                }


                //判断箱子，是否提箱完成，待产装, 以及判断订单，是否是
                $ret = $this->OrderAssignDriverService->orderBoxAssignComplete($reamId, $order_freight_id, $order_freight_boxid );
                if( !$ret ){
                    $this->db->rollback();
                    Logger::warn("OrderAssignDriverService->orderBoxAssignComplete, reamId:%s, order_freight_id:%s, order_freight_boxid:%s", $reamId, $order_freight_id, $order_freight_boxid);
                    return false;
                }

            // 取消司机 任务 时 通知
            if( count( $del_assign_id )>0 ){
                $this->ActivityLogComponent->noticeDriverCancelTask( $order_freight_boxid ,$order_freight_id ,$driver_user_id);
            }

            // 通知 司机任务改变
            if( count( $change_assign_arr  ) > 0 ){
                $this->ActivityLogComponent->noticeDriverWhenBoxChange( $order_freight_boxid ,$order_freight_id ,$driver_user_id);
            }

            // 通知司机分派箱子
            if( count( $add_assign_arr  ) > 0){
                $this->ActivityLogComponent->noticeDriverNewTask( $driver_user_id ,$order_freight_boxid ,$order_freight_id);
            }

        }catch(Exception $e){
            $this->db->rollback();
            Logger::warn( "exception: %s", var_export($e->getTraceAsString(),true) );
            return false;
        }

        $this->db->commit();
        Logger::info("CarteamOrderAssignEditSaveService->editSave return ok");
        return true;
    }

    public function checkInput( $assignInfo )
    {
            $count = count($assignInfo);

            for( $i=0; $i<$count; $i++ ){
                for( $j=$i+1; $j<$count; $j++ ){
                    if(  $assignInfo[$i]['order_product_addressid'] == $assignInfo[$j]['order_product_addressid']
                        &&  $assignInfo[$i]['order_product_timeid'] == $assignInfo[$j]['order_product_timeid']  ){
                            return false;
                    }
                }
            }

            return true;
    }


    public function checkAddAsignDriver( $assignInfo ){
        $count = count($assignInfo);
        for($i = 0; $i < $count; $i++)
            if(empty( $assignInfo[$i]['assign_id']))
                return false;
        return true;
    }
}
