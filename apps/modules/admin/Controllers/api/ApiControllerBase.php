<?php

use Phalcon\Mvc\Controller;
use Library\Log\Logger;
use Library\Helper\ArrayHelper;

class ApiControllerBase extends Controller
{
    protected $ret;


    public function beforeExecuteRoute($dispatcher)
    {
        //返回的数据结构定义
        //1：参数错误 -1 崩溃 -2未登录
        //1：参数错误 -1 崩溃 -2未登录
        //1：参数错误 -1 崩溃 -2未登录
        $this->ret = array(
            'error_code'  => 0,
            'error_msg'   => '成功',
            'data'        => array(),
        );

    }

    public function afterExecuteRoute($dispatcher)
    {

    }

    //验证参数
    protected function paramVerify($validation)
    {
        $reqList = $this->request->get();

        $reqArr = array();
        foreach ( $reqList as $k => $v ) {
            $reqArr[$k] = $v;
        }
        unset($reqArr['_url']); //有点危险

        $msg = $validation->validate($reqArr);
        if( count($msg)) {

            Logger::warn('参数校验出错');
            $data = ArrayHelper::validateMessages($msg);
            $this->ret['error_code'] = 1;
            $this->ret['error_msg'] = '参数校验出错';
            $this->ret['data'] = $data;

            return false;
        }
        else{
            return true;
        }
    }

    //返回结果
    protected function sendBack($err_msg = NULL)
    {
        if( !empty($err_msg)) {
            $this->ret['error_code'] = 101;
            if( !is_string($err_msg))
                $err_msg = $err_msg->getMessage();
            $this->ret['error_msg'] = $err_msg;
        }

        return  $this->response->setContentType('application/json')
            ->setJsonContent( $this->ret)
            ->send();
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
