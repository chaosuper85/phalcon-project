<?php

use  Library\Log\Logger;
use Modules\www\Validation\TestValidation;

/**
 * @RoutePrefix("/api")
 */
class ApiIndexController extends ApiControllerBase
{

    /**
     * 生成验证码图片
     * @Route("/getCap", methods={"POST","GET"})
     */
    public function verifyAction()
    {
//        if( $this->request->get('isVerify',null,false)) {
//            $this->ret['msg']
//            $this->ret['data'] = $this->session->get('isVerify',false);
//            return  $this->sendBack();
//        }
        $image = $this->CaptchaService->create();

        $this->response->setHeader('Content-type', 'image/png')->setContent($image);
        return $this->response->send();
    }




    /**
     * @Route("/dologin", methods={"POST","GET"})
     */
    public function dologinAction()
    {
        try {
            //不需要参数校验

            $name = $this->request->get('username');
            $pwd = $this->request->get('password');
            $verify_code = $this->request->get('code');

            $msg = $this->AdminUserService->login($name, $pwd, $verify_code);

            if ( empty($msg)) {
                $this->ret['data'] = $this->AdminUserService->getSessionAcl();     //从session中获取url权限
                if( !$this->ret['data']) {
                    $this->ret['data'] = $this->AclService->listMenu();
                    if( empty($this->ret['data'])) {
                        Logger::error('!!! acl is null !!!');
                    }
                    $this->session->set('login_acl',$this->ret['data']);      //存储权限到session
                }
            }
            else {
                $this->ret['error_code'] = 2;
                $this->ret['error_msg'] = '用户名或密码错误';
            }

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->LOGIN,
                '',
                0,
                0,
                empty($msg) ? $name.'登录了':$name.'登录失败'
            );

        }catch (\Exception $e){
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }


    /**
     * @Route("/logOut", methods={"GET"})
     */
    public function logOutAction( )
    {
        try{
            $ret = $this->AdminUserService->logOut();

            $ret && $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->LOGOUT,
                '',
                0,
                0,
                $ret['username'].'登出了'
            );

            $this->sendBack($ret?'':'登出失败');
        }catch (\Exception $e){
            $this->ret['error_msg'] = '网络异常';
            Logger::warn($e->getMessage());
            $this->sendBack();
        }
    }

}

