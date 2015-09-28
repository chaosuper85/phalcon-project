<?php

use Library\Log\Logger;
use Modules\admin\Validation\CarTeamUserValidation;
use Library\Helper\PageHelper;

/**
 * @RoutePrefix("/logisticDetail")
 */
class LogisticDetailController extends ControllerBase
{
    /*
    |--------------------------------------------------------------------------
    | 跟单相关管理 Controller
    |--------------------------------------------------------------------------
    */


    /**
     * 物流详情
     *
     * @Route("", methods={"GET", "POST"})
     *
     */
    public function indexAction()
    {
        $order_id = $this->request->getQuery('order_id');    // 订单号

        $usr = $this->AdminUserService->getSessionUser();    // 跟单员权限验证
        $id = $usr['id'];

        $name = $this->admin_cfg->ACL_ROLE_TYPE->ROOT_NAME;   // 超级管理员权限验证
        $isOk = $name==$usr['username'];
        $isOk || $isOk = \AdminOrder::findFirst("admin_id=$id AND order_id=$order_id");

        if( !$isOk)
            return  $this->forwardError( array("error_code" => 404,"error_msg" => "您无权查看该订单详情"));

        try {
            $ret = $this->FreightTransportService->getMsg(intval($order_id));
            $this->ret['data'] = $ret;

            Logger::info('data: sum'.count($this->ret['data']));
        }catch (\Exception $e) {
            Logger::error('logisticDetail :'.$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return $this->sendBack('page/order/trace');
    }




}