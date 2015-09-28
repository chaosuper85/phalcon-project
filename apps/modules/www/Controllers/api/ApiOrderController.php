<?php

use Phalcon\Http\Response;
use Library\Log\Logger;
use Library\Helper\ArrayHelper;


/**
 * @RoutePrefix("/api/order")
 */
class ApiOrderController extends ApiControllerBase
{

    /**
     * @Route("/test", methods={"GET", "POST"})
     */
    public function testAction()
    {

        /*

        $shipName = new ShipName();

        $shipName->setup( array(
            'notNullValidations' => false,
        ) );

        $shipName->china_name ='';
        $shipName->com_adress ='';
        $shipName->shipname_code ='';
        $shipName->eng_name = 'abcdef';
        $shipName->shipping_companyid=0;

        $ret = $shipName->save();

        var_dump($shipName->getMessages());

        echo "123";
        die;

        */


    }
    /**
     * 通过 order_box_id 来获取 box timeLine
     * @Route("/boxtimeline", methods={"GET", "POST"})
     */
    public function getTimeLineAction(){
        $orderBoxId = $this->request->get('orderboxid');
        $result = array(
            'error_code' => 0,
            'error_msg' => '',
            'data' => '',
        );
        $msg = $this->OrderGetboxTimelineValidation->validate(array('orderboxid'=>$orderBoxId));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            try {
                $ret = $this->FreightTransportService->getBoxTimelineByBoxId($orderBoxId);
                $result['data'] = $ret;
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100005',
                    'error_msg' => '系统异常',
                    'data' => '',
                );
            }
        }
        $this->response->setJsonContent($result)->send();
        return;
    }

    /**
     *  产装完成之前，获取assign Status
     *
     * @Route("/assignStatus", methods={"GET", "POST"})
     */
    public function  getAssignStatusAction(){
        $orderId = $this->request->get("orderId");
        $assignId = $this->request->get("assignId");
        $result = array( 'error_code' => 100001, 'error_msg' => '获取失败', 'data' => '',);
        try{
            $sql = " SELECT assign_status from order_assign_driver WHERE order_freight_id=? AND id=? ";
            $data = $this->db->fetchOne( $sql,2,[ $orderId,$assignId]);
            if( !empty( $data ) && count( $data ) >0 ){
                $result['error_code'] = 0;
                $result['data'] = array("status" => $data['assign_status'] );
                $result['error_msg'] = "获取状态成功";
            }
        }catch (\Exception $e){
            Logger::warn("getAssignStatus error msg:%s",$e->getMessage());
        }
        return $this->response->setJsonContent( $result );
    }

}
