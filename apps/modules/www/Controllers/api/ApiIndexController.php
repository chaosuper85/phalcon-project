<?php

use Library\Helper\XssFilter ;
use Library\Log\Logger;
use Library\Helper\StringHelper;
use Library\Helper\ArrayHelper;

/**
 * @RoutePrefix("/api/index")
 */
class ApiIndexController extends ApiControllerBase
{

    /**
     * @Route("/do_login", methods={"GET", "POST"})
     */
    public function do_loginAction()
    {
        $result = array(
            'error_code'=>'0',
            'error_msg'=>'',
            'data'=> array(),
        );
        if( $this->request->isGet() ){
            $username = $this->request->getQuery('username');
            $password = $this->request->getQuery('password');
        }else if( $this->request->isPost() ) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
        }

        $msg = $this->IndexDologinValidation->validate(array('username' => $username, 'password' => $password));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            try {
                $user = NULL;
                $ret = $this->getDI()->get('UserLoginService')->checkUsernamePassword($username, $password, $user);

                if (!$ret) {

                    $result = array(
                        'error_code' => '100001',
                        'error_msg' => '用户名密码错误',
                        'data' => array(),
                    );
                } else {
                    $this->di->get('session')->set('login_user', $user);
                    $token = $this->di->get('UserLoginService')->updateRememberToken($user->id);

                    /*
                     * 加入通过链接邀请的逻辑，如果此时带有invite时种的cookie
                     */
                    //邀请人的userid
                    $invite_userid = $this->cookies->get('invite_userid')->getValue();
                    //邀请人公司的invite_token
                    $invite_token = $this->cookies->get('invite_token')->getValue();
                    //被邀请人的userid
                    $invitee_userid = $user->id;

                    if (!empty($invite_token) && !empty($invite_userid)) {

                        $invite_userid = $this->aes->decrypt($invite_userid);
                        $invite_token = $this->aes->decrypt($invite_token);

                        $ret = $this->InviteService->recvInviteFromLink($invite_userid, $invitee_userid, $invite_token);
                        Logger::info('invite_userid: ' . $invite_userid . ', invitee_userid: ' . $invitee_userid . ', invite_token: ' . $invite_token . ' ret: ' . var_export($ret, true));
                    }


                    $this->cookies->useEncryption(false);

                    $token = $this->getDI()->get('aes')->encrypt($token);

                    $time = $this->constant->LOGIN_SESSION_SECONDS;
                    $this->cookies->set("xdd-token", $token, time() + $time);
                }

            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100002',
                    'error_msg' => '登录异常',
                    'data' => array(),
                );
            }
        }
        if( !empty($from)){
            $result['data'] = array("from" => urldecode($from));
        }
        $this->response->setJsonContent($result)->send();

        return ;
    }


    /**
     * @Route("/checkExist", methods={"GET"})
     */
    public function checkExistAction()
    {
        $mobileOrName = $this->request->getQuery('mobileOrName');
        $msg = $this->IndexCheckFindValidation->validate(array('mobileOrName' => $mobileOrName));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            $result = array('error_code' => 0, 'error_msg' => '不存在'); // 不存在
            $hasChinaWord = false;
            $hasSpamWord  = false; // 有垃圾单词
            try {
                if (StringHelper::isMobileNumber($mobileOrName)) {
                    $data = $this->UserService->checkMobileExist($mobileOrName); //手机号
                } else {
                    $data = $this->UserService->checkNameExist($mobileOrName);  // 登录名
                    if (!StringHelper::isNumORChar($mobileOrName)) { // 包含其他特殊字符
                        $hasChinaWord = true ;
                    }else{
                        $hasSpamWord = $this->SpamUserNameService->filterName($mobileOrName, $result);
                    }
                }
                if ( $data ) { // 存在
                    $result = array('error_code' => 1, 'error_msg' => '已经存在');// 存在
                }else if ( $hasChinaWord ) { //
                    $result = array('error_code' => 2, 'error_msg' => '用户名只能是数字、字母的组合。');
                }else if ( $hasSpamWord ) { // 有垃圾单词
                    $word   = $result['filterWord'];
                    $result = array('error_code' => 3, 'error_msg' => '用户名中不能包含' . $word . '关键词。');
                }
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array('error_code' => '100002', 'error_msg' => '手机号验证异常');
            }
        }
        return  $this->response->setJsonContent($result);
    }

    /** 退出登录 ajax
     *
     * @Route("/logout", methods={"GET", "POST"})
     */
    public function logoutAction(){
        $this->session->remove('login_user');
        $this->session->remove("user_audit_status");
        $user = $this->session->get('login_user');
        Logger::info("clear user:$user->id");
        $this->session->destroy(); // 清除session;
        if( !empty( $_COOKIE) ){
            foreach( $_COOKIE as $key => $value ){
                $cookie = $this->cookies->get( $key );
                if( !empty( $cookie ) ){
                    $cookie->delete();
                }
            }
        }
        return $this->response->setJsonContent(array("error_code" =>0,"error_msg"=>"退出成功。"));
    }


    /**
     *   @Route("/checkMobileExist", methods={"GET"})
     */
    public function checkMobileExistAction(){
        $mobile = $this->request->getQuery('mobileOrName');
        if( empty( $mobile) || !StringHelper::isMobileNumber( $mobile) ){
            $result = array('error_code' =>100001,"error_msg" => "参数格式错误");
        }else{
           $user = Users::findFirst( array(
                "mobile = ?1 " ,
                "bind"   => [ 1=>$mobile ]
            ));
            if( empty( $user ) ){
                $result['error_code'] = 1000002;
                $result['error_msg']  = "手机号不存在";
            }else if( $user->usertype ==3 ){ // 3 车队
                $result['error_code'] = 1000003;
                $result['error_msg']  = "车队用户禁止此操作。";
            }else{
                $result['error_code'] = 1;
                $result['error_msg']  = "手机号存在";
            }
        }
        return $this->response->setJsonContent( $result );

    }


}