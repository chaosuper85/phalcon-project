<?php

use Library\Log\Logger;
use Modules\admin\Validation\CarTeamUserValidation;
use Library\Helper\PageHelper;

/**
 * @RoutePrefix("/orderDetail")
 */
class OrderDetailController extends ControllerBase
{
    /*
    |--------------------------------------------------------------------------
    | 跟单相关管理 Controller
    |--------------------------------------------------------------------------
    */


    /**
     * 某个订单的详情
     *
     * @Route("", methods={"GET", "POST"})
     *
     */
    public function indexAction()
    {
        $order_id = intval($this->request->get("order_id"));  // 订单号
        $dispatch = intval($this->request->get("dispatch"));  // 前端用

        $usr = $this->AdminUserService->getSessionUser();    // 跟单员权限验证
        $id = $usr['id'];

        $name = $this->admin_cfg->ACL_ROLE_TYPE->ROOT_NAME;   // 超级管理员权限验证
        $isOk = $name==$usr['username'];
        $isOk || $isOk = \AdminOrder::findFirst("admin_id=$id AND order_id=$order_id");

        $result = array();
        try{
            $ret = $isOk && $this->OrderFreightService->orderInfo($order_id, $result);
            if( $ret) {
                $this->ret['data'] = $result;
                $this->ret['data']['dispatch'] = $dispatch;
            }else{
                return  $this->forwardError( array("error_code" => 404,"error_msg" => "您无权查看该订单详情"));
            }
        }catch(\Exception $e) {
            Logger::error("details :".$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return  $this->sendBack('page/order/detail');
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
        $this->ret['data'] = $result;
        return $this->view->pick("order/page/modify_record")->setVar("data",$this->ret);
    }



}