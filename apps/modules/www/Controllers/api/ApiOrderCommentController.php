<?php

use Phalcon\Http\Response;
use Library\Log\Logger;

/**
 * @RoutePrefix("/api/order/comment")
 */
class ApiOrderCommentController extends ApiControllerBase
{

    /**
     * @Route("/detail", methods={"GET", "POST"})
     */
    public function detailAction(){
        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );
        $req = $this->request->getJsonRawBody(true);
        $orderId = $req['order_id'];
        $score = $req['score'];
        $msg = $this->OrderCommentUpdateValidation->validate($req);
        if(count($msg)){
            $result = array(
                'error_code'=>10002,
                'error_msg'=>'传递参数错误',
                'data'=> array(),
            );
        }else {
            try {
                $res = $this->OrderFreightService->changeOrderPercent($orderId, $score);
                if (!$res) {
                    $result['error_msg'] = '评价失败';
                    $result['error_code'] = 1002;
                }
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100003',
                    'error_msg' => '网络异常',
                    'data' => array(),
                );
            }
        }
        $result['data'] = $score;
        $this->response->setJsonContent( $result )->send();
        return ;
    }

    /**
     * @Route("/new", methods={"GET", "POST"})
     */
    public function newAction(){
        $orderId = $this->request->getQuery('order_id');
        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );
        $msg = $this->OrderCommentDisplayValidation->validate(array('order_id' => $orderId));
        if(count($msg)){
            $result = array(
                'error_code'=>1002,
                'error_msg'=>'参数校验出错',
                'data'=> array(),
            );
        }else {
            try {
                $score = $this->OrderFreightService->getOrderTotalPercent($orderId);
                $result['data'] = $score;
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100003',
                    'error_msg' => '网络异常',
                    'data' => array(),
                );
            }
        }
        $this->response->setJsonContent( $result )->send();
        return ;
    }
}
