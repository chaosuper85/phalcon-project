<?php

use Phalcon\Http\Response;
use Library\Log\Logger;
use Library\Helper\ArrayHelper;


/**
 * @RoutePrefix("/api/order")
 */
class FeOrderController extends ApiControllerBase
{

    /**
     * 通过 order_box_id 来获取 box timeLine
     * @Route("/boxtimeline", methods={"GET", "POST"})
     */
    public function getTimeLineAction(){
        $orderBoxId = $this->request->get('orderboxid');
        //$msg = $this->OrderGetboxTimelineValidation->validate(array('orderboxid'=>$orderBoxId));
        if(0){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => '',
            );
        }else {
            try {
                $this->ret['data'] = $this->FreightTransportService->getBoxTimelineByBoxId($orderBoxId);

            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100005',
                    'error_msg' => '系统异常',
                    'data' => '',
                );
            }
        }

        return $this->sendBack();
    }

}
