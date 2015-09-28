<?php
use Library\Log\Logger;
use Library\Helper\ArrayHelper;


/**
 * @RoutePrefix("/order")
 */
class OrderController extends ControllerBase
{



    /**
     *  订单详情
     *
     * @Get("/details")
     */
    public function  detailsAction(){

        $orderId  = $this->request->get("orderid");// 订单号
        $dispatch = $this->request->get("dispatch");
        $user = $this->getUser();
        $result = array();
        try{
            $order = OrderFreight::findFirst(array("conditions" => " id = ?1", "bind" =>  array( 1 => $orderId ),));
            if( empty( $order ) ){
                $result['error_code'] = 404;
                $result['error_msg']  =" 订单找不到。。。";
                $return = false;
            }else{
                $isOrderUser = $user->id == $order->freightagent_user || $user->id == $order->carrier_userid;
                if( $isOrderUser ){
                    $return = $this->OrderFreightService->orderInfo( $orderId,$result);
                }else{
                    $result['error_code'] = 100001;
                    $result['error_msg']  =" 您无权查看此订单。。。";
                    $return = false;
                }
            }
        }catch (\Exception $e){
            Logger::info("details error msg:".$e->getMessage());
            $result['error_code'] = 1000001;
            $result['error_msg']  ="系统内部错误。";
            $return = false;
        }
        $this->data['data']     = $result;
        $this->data['dispatch'] = $dispatch;

        if( $return ){ // true
            return $this->view->pick("order/page/order_details")->setVar("data", $this->data);
        }else{
           $this->forwardError( $result );
        }
    }


    /** 订单列表
     * @Get("/list")
     */
    public function  ordersListAction()
    {
        $user = $this->session->get('login_user');
        $userId = $user->id;
        $userType = $user->usertype;
        $page = $this->request->getQuery('page');
        $orderStatus = $this->request->getQuery('status');
        $searchType = $this->request->getQuery('searchType');
        $searchValue = $this->request->getQuery('searchValue');
        $msg = $this->OrderListValidation->validate(array('login_user' => $user));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            if (empty($searchValue) || empty($searchType))
                $searchParams = array();
            else
                $searchParams = array(
                    'searchType' => $searchType, //搜索 类型
                    'searchValue' => $searchValue, //搜索 参数
                );
            if (empty($page))
                $page = 1;
            if (empty($orderStatus))
                $orderStatus = 0;
            try {
                $orderList = $this->OrderFreightListService->getMsg($userId, $userType, $orderStatus, $page, $searchParams);
                $this->data['data'] = $orderList;
            }catch (\Exception $e){
                Logger::error($e->getMessage());
                $this->data['err_msg'] = '系统异常!';
            }

        }
        Logger::info('data: '.var_export($this->data, true));
        return $this->view->pick("order/page/order_list")->setVar("data",$this->data);
    }




    /** 订单物流
     *
     * @Get("/trace")
     */
    public function traceAction(){
        $user = $this->session->get('login_user');
        $userid = $user->id;
        $orderFreightId = $this->request->getQuery('orderid');
        $list = $this->request->getQuery('list');
        if(empty($list))
            $list = 0;
        $msg = $this->OrderTraceValidation->validate(array('orderid' => $orderFreightId));
        $return = false;
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }
        else{
            $checkUser = $this->OrderService->checkUserOrder($userid, $orderFreightId);
            if($checkUser) {
                $result = array(
                    'error_code' => 0,
                    'error_msg' => '',
                    'data' => '',
                );
                try {
                    $data = $this->FreightTransportService->getMsg($orderFreightId, $list);
                    $this->data['data'] = $data;
                    $result['data'] = $data;
                    $return = true;
                } catch (\Exception $e) {
                    Logger::error($e->getMessage());
                    $this->data['error_msg'] = '系统异常';
                }
            }
            else
                $result = array(
                    'error_code' => 10002,
                    'error_msg' => '您无权访问此订单',
                    'data' => '',
                );
        }
        Logger::info('data: '.var_export($this->data, true));
        if($return){ // true
            return $this->view->pick("order/page/order_trace")->setVar("data",$this->data);
        }else{
            $this->forwardError( $result );
        }
    }

    /** 订单的修改记录
     *
     * @Get("/records")
     */
    public function  recordsAction(){
        $orderId = $this->request->get("orderid");
        $result = array();
        try{
            $this->OrderLogService->getOrderLogRecords($orderId, $result );
        }catch (\Exception $e){
            Logger::warn(" order records error msg:%s",$e->getMessage());
        }
        $this->data['data'] = $result;
        return $this->view->pick("order/page/modify_record")->setVar("data",$this->data);
    }


    /** 订单的提箱单，产装联系单，打包下载
     *
     * @Get("/downLoadAll")
     */
    public function  downLoadAllAction()
    {
        $orderId = $this->request->get("orderid");
        $user = $this->getUser();
        if ( empty($orderId) || empty($user) ) {
            $result = array('error_code' => 1000001, 'error_msg' => "你访问的订单找不到");
        } else {
            try {
                $checkUser = $this->OrderService->checkUserOrder($user->id, $orderId);
                if ( $checkUser ) { // true
                    $ret = $this->OrderDownLoadAllService->downLoadAll( $orderId );
                    if ( empty( $ret ) ) {
                        $result['error_code'] = 100003;
                        $result['error_msg']  = "网络错误，请重新下载。";
                    } else {
                        $result['error_code'] = 0;
                        $fileName = "订单-" . $orderId . "-产装联系单、提箱单.zip";
                        $this->response->setContent(file_get_contents($ret))->setHeader('Content-Length', filesize($ret));
                        unlink($ret);
                    }
                } else {
                    $result['error_code'] = 100002;
                    $result['error_msg'] = "您无权进行此操作。";
                }
            }catch (\Exception $e) {
                Logger::warn(" order records error msg:%s", $e->getMessage());
                $result['error_code'] = 100003;
                $result['error_msg'] = "网络错误，请重试。";
            }
        }
        if( $result['error_code'] > 0  ){
            $this->forwardError( $result );
        }else{
            return $this->response->setHeader("Content-disposition", "attachment; filename=" . $fileName)
                ->setHeader('Content-Type', 'application/zip');
        }
        return;
    }


}
