<?php

use Library\Helper\StringHelper;
use Library\Log\Logger;

/**
 * @RoutePrefix("/index")
 */
class IndexController extends ControllerBase
{

    /**
     * @Route("/show404", methods={"GET", "POST"})
     */
    public function show404Action()
    {
        try {

            $data = array();
            $this->data['data'] =  $data;

            Logger::info( var_export($this->data, true) );
            $this->view->pick( 'index/404' )->setVar('data', $this->data);

        } catch (\Exception $e) {
            Logger::error($e->getTraceAsString());
        }

        return ;
    }

    /**
     * @Route("", methods={"GET", "POST"})
     */
    public function indexAction()
    {
        try {

            $data = array();
            $this->data['data'] =  $data;

            Logger::info( var_export($this->data, true) );

            $this->view->pick('index/page/index')->setVar('data', $this->data);//首页
            return $this->view;
        } catch (\Exception $e) {
            Logger::error($e->getTraceAsString());
        }
    }

    /**  登录
     * @Route("/login", methods={"GET", "POST"})
     */
    public function loginAction(){
        if( $this->request->isGet()){
            $from = $this->request->getQuery("from");
        }else{
            $from = $this->request->getPost("from");
        }

        //校验是否已登录
        $result = $this->UserLoginService->checkLogin();
        if( $result['error_code'] == 0) {
            return $this->response->redirect('/');
        }

        $this->view->pick('index/page/login')->setVar("data",array("from" => StringHelper::filterUri($from)));
        return $this->view;
    }


//    /**
//     * @Route("/findPassword", methods={"GET", "POST"})
//     */
//    public function  findPasswordAction(){
//        $this->view->pick('index/page/find_password');
//        return $this->view;
//    }

    /**
     * @Route("/agreement", methods={"GET", "POST"})
     */
    public function agreementAction(){
        $this->view->pick('index/page/agreement');
        return  $this->view;
    }

    /**
     *  @Route("/findPwd", methods={"GET", "POST"})
     */
    public function findPwdAction(){

        return $this->view->pick("index/page/forgot_pwd")->setVar("data",$this->data);
    }

    /**
     *  @Route("/error", methods={"GET", "POST"})
     */
    public function  errorAction(){
        $errorData = $this->dispatcher->getParams();
        $this->data['data'] = $errorData;
        return $this->view->pick("index/page/error")->setVar("data",$this->data);
    }
}