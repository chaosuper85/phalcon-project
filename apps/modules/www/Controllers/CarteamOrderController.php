<?php
use Library\Log\Logger;
use Library\Helper\ArrayHelper;

/**
 * @RoutePrefix("/carteam/order")
 */
class CarteamOrderController extends ControllerBase
{

    /**  车队
     *  完善订单信息的页面
     *
     * @Get("/complete")
     */
    public function  completeAction()
    {
        $orderId = $this->request->getQuery("orderid");
        $user    = $this->getUser();
        $result = array();
        $return = false;
        if (empty($orderId)) {
            $result['error_code'] = 1000005;
            $result['error_msg'] = "参数格式不正确。";
        } else {
            $order = OrderFreight::findFirst("id=$orderId");
            if (empty($order)) { // 订单找不到
                $result['error_code'] = 1000001;
                $result['error_msg'] = "订单找不到。";
            } else if ($order->order_status > 1) {
//                $result['error_code'] = 1000002;
//                $result['error_msg'] = "订单已经被接单，不能重复接单。";
                return $this->response->redirect("order/details?orderid=".$orderId);
            } else {
                try {
                    $return = $this->OrderFreightService->orderBaseInfo($orderId, $user->id, $result);
                } catch (Exception $e) {
                    Logger::warn("用户:{%s} completeAction error msg:%s", $user->id, $e->getMessage());
                    $result['error_code'] = 404;
                    $result['error_msg'] = "系统内部错误。";
                }
            }
        }
        $this->data['data'] = $result;
        if ($return) {
            return $this->view->pick("order/page/order_complete")->setVar("data", $this->data);
        } else {
            $this->forwardError($result);
        }

    }


    /**
     *  产装联系单列表
     * @Route("/product_address_list", methods={"GET", "POST"})
     */
    public function  productAddressListAction()
    {
        $user = $this->session->get('login_user');
        $userid = $user->id;
        $orderId = $this->request->getQuery("orderid");
        $result = array();
        $return = false;
        $checkUser = $this->OrderService->checkUserOrder($userid, $orderId);
        if ($checkUser) {
            try {
                $result = $this->OrderFreightBoxListService->getMsgByOrderFreightId($orderId);
                $return = true;
            } catch (\Exception $e) {
                Logger::info("$orderId productAddressListAction error:" . $e->getMessage());
                $result['error_code'] = 404;
                $result['error_msg'] = "网络错误！";
                $return = false;
            }
            $this->data['data'] = $result;
        } else
            $result = array(
                'error_code' => 100002,
                'error_msg' => '您无权访问此页面',
                'data' => '',
            );
        if ($return) {
            return $this->view->pick("order/page/product_address_list")->setVar("data", $this->data);
        } else {
            $this->forwardError($result);
        }
    }


    /**
     *  产装联系单 详情
     *
     * @Get("/product_address")
     */
    public function  productAddressAction()
    {
        $user = $this->session->get('login_user');
        $userid = $user->id;
        $orderFreightBoxId = $this->request->getQuery('orderboxid');
        $msg = $this->CarteamOrderAddressListValidation->validate(array('orderboxid' => $orderFreightBoxId));
        $return = false;
        if (count($msg)) {
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        } else {
            $checkUser = $this->OrderService->checkUserOrder($userid, 0, $orderFreightBoxId);
            if ($checkUser) {
                $data = $this->OrderFreightBoxDetailService->getMsg($orderFreightBoxId);
                $this->data['data'] = $data;
                $return = true;
            } else
                $result = array(
                    'error_code' => 10002,
                    'error_msg' => '您无权访问此页面',
                    'data' => ''
                );
        }
        Logger::info('data: ' . var_export($this->data, true));
        if ($return)
            return $this->view->pick("order/page/print_product_address")->setVar("data", $this->data);
        else
            $this->forwardError($result);
    }

    /** 车队下载产装联系单
     *    提单579622617张师傅装货联系单
     * @Get("/download/product_address")
     */
    public function downloadProAddressAction()
    {
        $boxid = $this->request->get("boxid");
        $result = array('error_code' => 10001,'error_msg'=>"您查找的箱子找不到。");
        Logger::info("downloadProAddressAction: boxId:%s", $boxid);
        $path = "";
        if (!empty($boxid)) {
            try {
                $path = $this->WordDocService->createProAddresContact($boxid, $result);
            } catch (\Exception $e) {
                Logger::warn("downloadProAddressAction error :%s", $e->getMessage());
            }
        }

        if (empty($path)) {
            $this->forwardError($result);
        } else {
            $fileName = isset($result['fileName']) ? $result['fileName'] . "装货联系单" : "箱子-$boxid-产装联系单";
            $this->response->setContentType("Application/msword", "UTF-8")->setContent(file_get_contents($path));
            unlink($path);
            return $this->response->setHeader("Content-Disposition", "inline; filename=" . $fileName . ".doc");
        }
    }

    /**
     *
     * @Route("/reConstruct_msg", methods={"GET", "POST"})
     */
    public function getInfoAction()
    {
        $orderid = $this->request->getQuery('orderid');
        $user = $this->session->get('login_user');
        $userid = $user->id;
        $msg = $this->CarteamOrderGetInfoValidation->validate(array('orderid' => $orderid));
        $return = false;
        $data = array();
        if (count($msg) != 0) {
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        } else {
            $checkUser = $this->OrderService->checkUserOrder($userid, $orderid);
            if ($checkUser) {
                try {
                    $data = $this->OrderFreightReconstructService->getMsg($orderid);
                    $result['data'] = $data;
                    $return = true;
                } catch (\Exception $e) {
                    Logger::warn('退载重建显示信息错误：' . $e->getMessage());
                    $result = array(
                        'error_code' => 100005,
                        'error_msg' => '系统错误',
                        'data' => '',
                    );
                }
            } else
                $result = array(
                    'error_code' => 100006,
                    'error_msg' => '请勿访问别人订单',
                    'data' => '',
                );
        }
        $this->data['data'] = $data;
        Logger::info('data: ' . var_export($result, true));
        if ($return)
            return $this->view->pick("order/page/print_reconstrucct_msg")->setVar("data", $this->data);
        else
            $this->forwardError($result);
    }

    /**
     * @Route("/car_manage", methods={"GET", "POST"})
     */
    public function getDriverInfoAction()
    {
        $user = $this->session->get('login_user');
        $page = $this->request->getQuery('page');
        $userid = $user->id;
        $result = array(
            'error_code' => 0,
            'error_msg' => '',
            'data' => '',
        );
        $msg = $this->CarteamGetDriverValidation->validate(array('login_user' => $user));
        if (count($msg) != 0) {
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        } else {
            try {
                $result['data'] = $this->CarTeamService->getCarteamMsg($userid, $page);
            } catch (\Exception $e) {
                Logger::warn('管理司机页面出错：%s', $e->getMessage());
                $result = array(
                    'error_code' => 100005,
                    'error_msg' => '系统错误',
                    'data' => '',
                );
            }
        }
        $this->data['data'] = $result;
        Logger::info('data: ' . var_export($result['data'], true));
        return $this->view->pick("order/page/car_manage")->setVar("data", $this->data);
    }


    /**
     *  车队下载 订单的 全部箱子 产装联系单
     * @Route("/download_all_proContacts", methods={"GET", "POST"})
     */
    public function  downloadAllZipAction()
    {
        $orderId = $this->request->get('orderid');
        $user = $this->getUser();
        $result = array();
        if (empty($orderId) || empty($user)) {
            $result = array('error_code' => "1000001", 'error_msg' => '您查看的订单找不到.');
        } else {
            try {
                $checkUser = $this->OrderService->checkUserOrder($user->id, $orderId);
                if ( $checkUser ) {
                    $path = $this->WordDocService->zipOrderProContacts($orderId, $result);
                    if( !empty( $path ) ){
                        $result['error_code'] = 0;
                        $this->response->setContent( file_get_contents( $path ));
                        unlink( $path );
                        return $this->response->setHeader("Content-disposition","attachment; filename=".$result['fileName'])
                            ->setHeader('Content-Type','application/zip')->setHeader('Content-Length',filesize( $path));
                    }
                } else {
                    $result['error_code'] = 1000004;
                    $result['error_msg']  = "您无权进行此操作。";
                }
            } catch (\Exception $e) {
                Logger::warn("orderId {%s} downloadAllZip error msg:{%s}", $orderId, $e->getMessage());
                $result['error_code'] = 100005;
                $result['error_msg'] = "网络错误";
            }
        }
        if( $result['error_code'] !=0 ){
            return $this->forwardError( $result );
        }else{
            return $this->response->setJsonContent($result);
        }
    }
}
