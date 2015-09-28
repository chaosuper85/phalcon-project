<?php namespace Modules\www\Events;

use Library\Log\Logger;
use Phalcon\Mvc\User\Component;

class ActivityLogListener extends Component
{


    /**
     * @param $event
     * @param $myComponent
     */
    public function orderHistroyLog($event, $myComponent)
    {
        $data = $myComponent->getData();
        try{
            Logger::info("add order action log content:%s", var_export($data,true));
            $this->ActivityLogService->addOrderActionLog( $data );
        }catch (\Exception $e){
            Logger::warn("orderHistroyLog Event error msg:%s",$e->getMessage());
        }
    }

    /** 通知车队接单
     */
    public function  noticeCarTeam( $event, $myComponent){
        $data = $myComponent->getData();
        $freight     = $this->UserService->getById( $data['freightId'] );
        $carTeamUser = $this->UserService->getById( $data['carTeamId'] );
        if( !empty( $freight ) && !empty( $carTeamUser ) ){
            $type ='NOTICE_CARTEAM_ACCEPT';
            $params = [ $freight->unverify_enterprisename ] ;
            $this->ComSmsService->sendSms( $carTeamUser->mobile , $type , $params );
        }
    }


    /**
     *  通知司机 app 端
     */
    public function  noticeDriver( $event, $myComponent){
        $data = $myComponent->getData();
        $driverId =  $data['driverId'];
        $boxId    =  $data['boxId'];
        $orderId  =  $data['orderId'];
        $type     =  $data['type'];
        switch( $type ){
            case 'TO_GET_TASK':
                $orderid  = $data['orderId'];
                $driver   = $this->UserService->getById( $driverId );
                $order    = $this->OrderFreightService->getById( $orderid );
                if( !empty($order) && !empty( $driver ) ){
                    $carTeamUser = $this->UserService->getById( $order->carrier_userid );
                    $smsType = 'NOTICE_DRIVER_NEW_TASK';
                    $params = [ $carTeamUser->unverify_enterprisename ];
                    $this->ComSmsService->sendSms( $driver->mobile , $smsType , $params );
                }
            break;

            default:
                break;
        }
        $data = $this->getPushData( $driverId,$orderId,$boxId);
        $this->pushTask( $type,$data);
    }


    /**
         TO_GET_TASK：       您有新的集装箱任务到达，请查看
         BOX_STATUS_CHANGED：您运送的集装箱有变化，请查看
         ORDER_CANCELED：    有集装箱任务被取消，请查看
     */
    private function  pushTask( $type ,$params ){
        $this->di->get("JpushService")->toAlias( $type , json_encode($params));
    }

    private function  getPushData( $driver, $orderId = "", $boxId="", $assignId =0 ,$webUrl=""){
        return array('driver_id' => $driver, 'assign_id' => $assignId, 'order_id'=> $orderId,'box_id' => $boxId, 'weburl' => $webUrl);
    }







}