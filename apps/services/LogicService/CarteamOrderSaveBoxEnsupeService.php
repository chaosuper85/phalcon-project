<?php


namespace Services\LogicService;


use Library\Helper\OrderLogHelper;
use Library\Log\Logger;
use Exception;
use Phalcon\Mvc\User\Component;

use OrderFreightBox;
use OrderFreight;


class CarteamOrderSaveBoxEnsupeService extends Component
{

/*
$req = array(
            'order_box_id'      => '123',
            'order_freight_id'  => '123',
            'box_ensupe'        => '1234',
            'box_code'          => '12345',
        );
 */
    public function save( $req )
    {

        $order_box_id       = $req['order_box_id'];
        $order_freight_id   = $req['order_freight_id'];
        $box_ensupe         = $req['box_ensupe'];
        $box_code           = $req['box_code'];


        $this->db->begin();
        try{
            $reamId =  $this->session->get('login_user')->id;

            // 如果订单的状态是已运抵，则不能进行修改箱子的箱号/铅封号
            $conditions = "id = :id:";
            $parameters = array(
                "id" => $order_freight_id,
            );
            $orderFreight = OrderFreight::findFirst(array(
                $conditions,
                "bind" => $parameters
            ));

            if( $orderFreight->order_status >= $this->order_config->order_status_enum->TO_COMMENT ){
                $this->db->rollback();
                Logger::warn("orderid: %s, boxid: %s can not modify", $order_freight_id, $order_box_id);
                return false;
            }

            $conditions = "id = :id:";
            $parameters = array(
                "id" => $order_box_id,
            );
            $orderFreightBoxs = OrderFreightBox::find(array(
                $conditions,
                "bind" => $parameters
            ));

            $addBoxTimeline = false;

            foreach( $orderFreightBoxs as $key => $orderFreightBox )
            {
                $box_id = $orderFreightBox->id;
                $orderFreightBox->order_freight_id = $order_freight_id;

                if( empty($orderFreightBox->box_ensupe) && empty($orderFreightBox->box_code) ){
                    // 添加 箱号 铅封号
                    $data = OrderLogHelper::addBoxCodeAndSeal( $orderFreightBox->order_freight_id,$orderFreightBox->id,$box_code,$box_ensupe);
                    $this->ActivityLogComponent->orderHistroyLog( $data );
                    $addBoxTimeline = true;
                }else if( $orderFreightBox->box_code == $box_code && $orderFreightBox->box_ensupe == $box_ensupe  ) {
                    continue;
                    // 如果提交的和数据库一致，则不修改。
                }else{
                        // 修改箱号铅封号
                        $data = OrderLogHelper::updateBoxCodeAndSeal( $orderFreightBox->order_freight_id,$orderFreightBox->id,$box_code,$box_ensupe);
                        $this->ActivityLogComponent->orderHistroyLog( $data );
                }

                $orderFreightBox->box_ensupe = $box_ensupe;
                $orderFreightBox->box_code = $box_code;

                $ret = $orderFreightBox->save();
                if( !$ret )
                {
                    $this->db->rollback();
                    Logger::warn( "box_id: %s save failed", $box_id );
                    return false;
                }else
                {
                    //修改该orderid,boxid的所有分派情况
                    $ret = $this->OrderAssignDriverService->saveBoxAssignCompleteCheck($reamId, $order_freight_id, $box_id );
                    if( !$ret ){
                        $this->db->rollback();
                        Logger::warn( "saveBoxAssignCompleteCheck, order_freight_id: %s, box_id: %s return false", $order_freight_id, $box_id );
                        return false;
                    }
                }

                if( $addBoxTimeline ){
                    $ret = $this->OrderBoxTimelineService->boxTimelineUpdate( $order_freight_id, $order_box_id, $box_ensupe, $box_code );
                    if( !$ret ){
                        $this->db->rollback();
                        Logger::warn( "boxTimelineUpdate, box_ensupe: %s, box_code: %s return false", $box_ensupe, $box_code );
                        return false;
                    }
                }

                $ret = $this->OrderAssignDriverService->orderBoxAssignComplete($reamId, $order_freight_id, $box_id );
                if( !$ret ){
                    $this->db->rollback();
                    Logger::warn( "orderBoxAssignComplete, order_freight_id: %s, box_id: %s return false", $order_freight_id, $box_id );
                    return false;
                }
            }

        }catch (Exception $e){
            $this->db->rollback();
            Logger::warn("exception: %s", var_export($e->getTraceAsString(),true));
            return false;
        }

        $this->db->commit();
        Logger::info("orderid: ".$order_freight_id." save return ".var_export($ret,true));

        return true;
    }


}