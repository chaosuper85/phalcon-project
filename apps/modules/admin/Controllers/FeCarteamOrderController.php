<?php
use Library\Log\Logger;
use Library\Helper\ArrayHelper;

/**
 * @RoutePrefix("/carteam/order")
 */
class FeCarteamOrderController extends ControllerBase
{

    /**  车队
     *  完善订单信息的页面
     *
     * @Get("/complete")
     */
    public function  completeAction(){
        $orderId = $this->request->getQuery("orderid");
        $user    = $this->session->get("login_user");
        $result  = array();
        $return  = false;
        $order = OrderFreight::findFirst("id=$orderId");
        if( empty( $order ) ){ // 订单找不到
            $result['error_code'] =  1000001;
            $result['error_msg']  = "订单找不到。";
        }else if( $order->order_status > 1 ){
            $result['error_code'] =  1000002;
            $result['error_msg']  = "订单已经被接单，不能重复接单。";
        }else{
            try{
                $return = $this->OrderFreightService->orderBaseInfo( $orderId,$user->id,$result);
            }catch (Exception $e){
                Logger::info("用户$user->id completeAction error msg:".$e->getMessage());
                $result['error_code'] = 404;
                $result['error_msg']  = "系统内部错误。";
                $return = false;
            }
        }

        $this->ret['data'] = $result;
        if( $return ){
            return $this->view->pick("order/page/order_complete")->setVar("data", $this->ret);
        }else{
            $this->forwardError( $result );
        }

    }


    /**
     *  产装联系单列表
     * @Route("/product_address_list", methods={"GET", "POST"})
     */
    public function  productAddressListAction(){
        $orderId = $this->request->getQuery("orderid");
        echo $orderId;
        $result  = array();
        $return = true;
        try{
            $result = $this->OrderFreightBoxListService->getMsgByOrderFreightId($orderId);
        }catch (\Exception $e){
            Logger::info("$orderId productAddressListAction error:".$e->getMessage());
            $result['error_code'] = 404;
            $result['error_msg']  = "网络错误！";
            $return = false;
        }
        $this->ret['data'] = $result;
        if($return){
            return $this->sendBack("order/page/product_address_list");
        }else{
            $this->forwardError( $result );
        }
    }


    /**
     *  产装联系单 详情
     *
     * @Get("/product_address")
     */
    public function  productAddressAction(){
        $orderFreightBoxId = $this->request->getQuery('orderboxid');
        //$msg = $this->CarteamOrderAddressListValidation->validate(array('orderboxid' => $orderFreightBoxId));
        if(0){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => '',
            );
        }else {
            $data = $this->OrderFreightBoxDetailService->getMsg($orderFreightBoxId);
            $this->ret['data'] = $data;
        }
        Logger::info('data: '.var_export($this->ret, true));
        return $this->view->pick("order/page/print_product_address")->setVar("data", $this->ret);
    }

    /** 车队下载产装联系单
     * @Get("/download/product_address")
     */
    public function downloadProAddressAction(){
        $boxid  = $this->request->get("boxid");
        $result = array();
        Logger::info("downloadProAddressAction: boxId:%s",$boxid);
        $path = "";
        try{
            $path = $this->WordDocService->createProAddresContact( $boxid ,$result);
        }catch (\Exception $e){
            Logger::warn("downloadProAddressAction error :%s",$e->getMessage());
        }
        if( empty( $path) ){
            $this->forwardError( $result );
        }else{
            $fileName ="产装联系单.doc";
            $this->response->setContentType("Application/msword","UTF-8")->setContent( file_get_contents( $path));
            return $this->response->setHeader("Content-Disposition","inline; filename=" . $fileName . ".doc" );
        }
    }

    /**
     *
     * @Route("/reConstruct_msg", methods={"GET", "POST"})
     */
    public function getInfoAction(){
        $orderid = $this->request->getQuery('orderid');
        //$msg = $this->CarteamOrderGetInfoValidation->validate(array('orderid' => $orderid));
        $return = false;
        $data = array();
        if(0) {
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => '',
            );
        }else{
            try{
                $data = $this->OrderFreightReconstructService->getMsg($orderid);
                $result['data'] = $data;
                $return = true;
            }catch(\Exception $e) {
                Logger::warn('退载重建显示信息错误：' . $e->getMessage());
                $result = array(
                    'error_code' => 100005,
                    'error_msg' => '系统错误',
                    'data' => '',
                );
            }
        }
        $this->ret['data'] = $data;
        Logger::info('data: '.var_export($result, true));
        if($return)
            return $this->view->pick("order/page/print_reconstrucct_msg")->setVar("data", $this->ret);
        else
            $this->forwardError( $result );
    }
    /**
     *@Route("/car_manage", methods={"GET", "POST"})
     */
    public function getDriverInfoAction(){
        $user = $this->session->get('login_user');
        $page = $this->request->getQuery('page');
        $userid = $user->id;
        $result = array(
            'error_code' => 0,
            'error_msg' => '',
            'data' => '',
        );
        //$msg = $this->CarteamGetDriverValidation->validate(array('login_user' => $user));
        if( 0) {
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => '',
            );
        }else{
            try {
                $result['data'] = $this->CarTeamService->getCarteamMsg($userid, $page);
            }catch(\Exception $e) {
                Logger::warn('管理司机页面出错：%s', $e->getMessage());
                $result = array(
                    'error_code' => 100005,
                    'error_msg' => '系统错误',
                    'data' => '',
                );
            }
        }
        $this->ret['data'] = $result;
        Logger::info('data: '.var_export($result['data'], true));
        return $this->view->pick("order/page/car_manage")->setVar("data",$this->ret);
    }

    /** 导出箱号铅封号
     * @Get("/export_box_info")
     */
    public function exportBoxAction(){
        $orderId = $this->request->get("orderid");
        $user = $this->getUser();
        $fileName = $this->OrderBoxService->exportAllBoxInfo( $orderId ,$user->id );
        $this->response->setContent( file_get_contents( $fileName));
        unlink( $fileName );// 删除文件
        return $this->response->setContentType("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","UTF-8")
            ->setHeader("Content-Disposition","attachment;filename=箱号铅封号Excel.xls");
    }
}
