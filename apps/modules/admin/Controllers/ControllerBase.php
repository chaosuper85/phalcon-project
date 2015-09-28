<?php

use Phalcon\Mvc\Controller;
use Library\Log\Logger;
use Library\Helper\ArrayHelper;





class ControllerBase extends Controller
{
    protected $ret;


    public function beforeExecuteRoute($dispatcher)
    {
        return ;

        //前后台数据结构定义
//        $this->ret = array(
//            'data' => '',    //数据
//            'data_sum' => 0,      //数据总数
//            'page_num' => 1, //第几页
//            'page_sum' => 1, //总页数
//            'page_size'=> 10,//每页个数

//            'user' => null,  //登录的用户对象
//        );

    }

    public function afterExecuteRoute($dispatcher)
    {

    }

    //验证所有参数
    protected function paramVerify($validation)
    {
        $reqList = $this->request->get();

        $reqArr = array();
        foreach ( $reqList as $k => $v ) {
            $reqArr[$k] = $v;
        }
        unset($reqArr['_url']);

        $msg = $validation->validate($reqArr);
        if( count($msg)) {

            $data = ArrayHelper::validateMessages($msg);
            $this->ret['data'] = $data;
            $this->ret['total_count']  = 0;

            Logger::warn('参数校验出错'.var_export($data,true));
            return false;
        }
        else{
            return true;
        }

    }

    /**
     * 功能: 返回页面
     * @param string $page_url
     */
    protected function sendBack($page_url='')
    {
        //返回前ret自动加上user对象
        $this->ret['user'] = $this->session->get('login_user');

        $this->view->pick($page_url)->setVar('data',$this->ret);
        $this->response->send();
        return true;
    }

    /** 跳转到错误页面
     * @param $data
     */
    public function forwardError( $data=false ){
        if( !$data) {
            $data['error_code'] = 1;
            $data['error_msg'] = $this->ret['data'];
        }

        return $this->dispatcher->forward(array("controller" => "index", "action" => "error","params" => $data));
    }

    /**
     * 功能:var_dump
     * staging不die
     */
    function bo_dump($data=false)
    {
        $rand = rand(1,2);
        if('10.10.93.31'==$this->request->getServerAddress())
            return ;
        else {
            if ($rand==2)
                return ;
            if( !$data)
                $data = $this->ret;
            var_dump($data);
            die;
        }
    }

}
