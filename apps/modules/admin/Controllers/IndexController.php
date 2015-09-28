<?php

use  Library\Log\Logger;
use Library\Helper\StringHelper;


/**
 * @RoutePrefix("/")
 */

class IndexController extends ControllerBase
{


    ## cache key 前缀 --- start ----
    const EVENT_24HOUR_TIMES = "dashboard_event24hours";


    /**
     * @Route("error", methods={"GET", "POST"})
     */
    public function errorAction()
    {
        try {
            $errorData = $this->dispatcher->getParams();
            if( empty($errorData)) {
                $errorData['error_code'] = 404;
                $errorData['error_msg'] = '无此页面';
            }

            $this->ret['data'] = $errorData;
            return  $this->sendBack('page/index/error');
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }

    }


    /**
     * @Route("", methods={"GET", "POST"})
     */
    public function indexAction()
    {
        $ret = $this->AdminUserService->checkLogin();

        if($ret) {

            // 返回一些大盘 dashboard 信息
            /**
             * 数据放在 redis cache , 缓存 random(4-6) 分钟
             *
             *   1:  截止当前总体车队用户数    货代用户数   司机用户数 ， 截止当前订单数，截止当前完成的订单数
             *   2:  关键事件的24小时内的每个小时事件次数 ( 注册  登陆 创建订单  接受订单  落箱  关闭订单 发送短信)
             *
             *   A:  前端api去拉去数据
             */
            $this->sendBack('page/index/index');
        }else{
            $this->sendBack('page/index/login');
        }
    }

    /**
     * @Route("login", methods={"GET", "POST"})
     */
	public function loginAction()
	{
        $ret = $this->AdminUserService->checkLogin();
        if( $this->request->isGet()) {
            $from = $this->request->getQuery("from");
        }else {
            $from = $this->request->getPost("from");
        }

        if( $ret) {
            $this->sendBack('page/index/index');
        }else {
            $this->view->pick('page/index/login')->setVar("data",array("from" => StringHelper::filterUri($from)));
            return $this->view;
        }
	}

}

