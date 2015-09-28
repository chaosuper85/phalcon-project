<?php

use Library\Log\Logger;

/**
 * @RoutePrefix("/")
 */
class MainController extends ControllerBase
{


    /**
     * @Route("/", methods={"GET", "POST"})
     */
    public function indexAction()
    {
        try {
            if( !empty( $this->user ) ){ // 已经登录
                $auth = $this->user['status']  ;// 用户是否认证
                if( $auth == 4 ){ // 认证通过
                    return $this->response->redirect("order/list"); // 订单列表
                 }else{
                    return $this->response->redirect("account/personalInfo"); // 账户
                 }
            }else{ // 未登录
                return $this->view->pick('index/page/index')->setVar('data', $this->data);//首页
            }

        } catch (\Exception $e) {
            Logger::error($e->getTraceAsString());
        }
    }


}