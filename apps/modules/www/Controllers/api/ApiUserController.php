<?php

use Modules\www\Validation\BaseValidation;
use Phalcon\Http\Response;
use Services\Services as Services;
use Library\Log\Logger;
use Library\Helper\StringHelper;

use Library\Helper\ArrayHelper;

/**
 * @RoutePrefix("/api/user")
 */
class ApiUserController extends ApiControllerBase
{

    const TEN_MINUTES = 600;


    /**
     * @Route("/", methods={"GET", "POST"})
     */
    public function indexAction()
    {
        /*
         * 参考的例子
         */
        try {

            $name = $this->request->getQuery('name');
            $pwd = $this->request->getQuery('pwd');

            $msg = $this->TestValidation->validate(array('name' => $name, 'pwd' => $pwd));
            if (count($msg)) {

                $error_code = 10001;
                $error_msg = '参数校验出错';
                $data = ArrayHelper::validateMessages($msg);

                $ret = array(
                    'error_code' => $error_code,
                    'error_msg' => $error_msg,
                    'data' => $data,
                );
            } else {

                //处理业务逻辑
            }


            $this->response->setJsonContent($ret)->send();

            return;
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }


    /**
     * @Route("/do_name/{name}/{pwd}", methods={"GET", "POST"})
     */
    public function  do_nameAction()
    {
        var_dump($this->dispatcher->getParam("name"));
        var_dump($this->dispatcher->getParam("pwd"));

    }

    /**
     * @Route("/do_register", methods={"GET", "POST"})
     */
    public function  do_registerAction()
    {
        $mobile = $this->request->getPost('mobile');
        $pwd = $this->request->getPost('pwd');
        $smsCode = $this->request->getPost('smsCode');
        $userName = $this->request->getPost('userName');
        $userType = $this->request->getPost('type');
        $result = array('error_code' => 1); //失败

        if (!$this->checkWhenRegister($mobile, $pwd, $userName, $smsCode, $userType, $result)) {
            return $this->response->setJsonContent($result)->send();
        }
        $salt = StringHelper::quickRandom();
        $token = '';
        $user = $this->UserService->create($mobile, md5($pwd . $salt), $userType, $userName, $salt, $token, $this->request->getClientAddress(),"");
        if (empty($user)) {
            $result['error_code'] = 1;
            $result['error_msg'] = "注册失败，请重新注册。";
        } else { // 创建成功
            $result['error_code'] = 0;
            $result['error_msg'] = "注册成功";

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
                Logger::info('invite_userid: ' . $invite_userid . ', invitee_userid: ' . $invitee_userid . ', invite_token: ' . $invite_token . ' ret: ' . $ret);
            }


            $this->session->set('login_user', $user);
            $token = $this->UserLoginService->updateRememberToken($user->id);

            $token = $this->getDI()->get('aes')->encrypt($token);
            $time = $this->constant->LOGIN_SESSION_SECONDS;
            $this->cookies->set("xdd-token", $token, time() + $time)->send();

            $result['data'] = array(
                'token' => $token,
                'user' => array(
                    'id' => $user->id,
                    'username' => $user->username,
                    'type' => $user->usertype
                )
            );
        }
        Logger::info('user register success:' . var_export($result, true));
        $this->response->setJsonContent($result)->send();

        return;
    }


    /**
     * @Route("/changeBind", methods={"GET", "POST"})
     */
    public function changeBindAction()
    {
        $usr = $this->session->get('login_user');
        $mobile = $this->request->get('mobile');
        $code = $this->request->get('code');
        $type = $this->request->get('smsType');
        $msg = $this->UserChangeBindValidation->validate(array('mobile' => $mobile, 'code' => $code, 'smsType' => $type));
        if ( count($msg) ) {
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        } else {
            $isOk = false;
            try{
                if ( $this->ComSmsService->existCode($mobile, $type) ) {   //存在 修改手机号的 验证码
                    $isOk = $this->ComSmsService->checkCode($mobile, $type, $code);
                }
                if ( empty($isOk) ) {
                    $result['error_code'] = 10000002;
                    $result['error_msg'] = '验证码错误，请重新输入。';
                } else {   // 手机验证码正确
                    $ret = $this->UserService->changeMobile($usr->id, $mobile);   //修改绑定的手机号
                    if ( $ret ) {
                        $result['error_code'] = 0;
                        $result['error_msg'] = '您绑定的手机号已更改';
                        $this->session->remove('CHANGE_MOBILE');
                        $this->session->remove('PWD_AUTH');
                        $this->ComSmsService->deleteCode($mobile, $type);
                        $this->UserService->updateUserSession($usr->id);
                    } else {
                        $result['error_code'] = 10000003;
                        $result['error_msg'] = '失败，请稍候重试';
                    }
                }
            }catch (\Exception $e){
                Logger::warn("change mobile:{%s} error msg:{%s}",$mobile,$e->getMessage());
                $result['error_code'] = 10000004;
                $result['error_msg'] = '失败，请稍候重试';
            }
        }
        return $this->response->setJsonContent($result);
    }


    /** 图片验证码
     * @Route("/getCap", methods={"GET", "POST"})
     */
    public function getCapAction()
    {
        $captcha = $this->di->get('CaptchaService');
        $string = $captcha->create('default');
        $this->response->setHeader('Content-type', 'image/png')->setContent($string);
        $this->response->send();
    }


    /**
     * @Route("/sendSms", methods={"GET", "POST"})
     */
    public function  sendSmsAction()
    {
        $result = array();
        $mobile = $this->request->getQuery('mobile');
        $smsType = $this->request->getQuery('smsType');
        $user = $this->session->get('login_user');
        if (empty($mobile) && !empty($user)) {
            $mobile = $user->mobile;
        }

        $ip = $this->request->getClientAddress();
        $messages = $this->SmsValidation->validate(array("mobile" => $mobile, "smsType" => $smsType));
        if (count($messages)) {
            $error_msg = ArrayHelper::validateMessages($messages);
            $result['error_msg'] = " 参数格式不正确。";
            $result['error_code'] = "1000001";
            $result['data'] = $error_msg;
            Logger::info(" sendSmsAction error:" . var_export($result, true));
            return $this->response->setJsonContent($result);
        }

        if (!$this->UserService->sendSmsWithCacheAndIpLimit($mobile, $smsType , $ip, $result)) { // false 失败
            return $this->response->setJsonContent($result);
        }
        // 发送成功
        $result['error_code'] = 0;//
        $result['error_msg'] = '';
        return $this->response->setJsonContent($result);
    }

    /**
     * @Route("/validatePwd", methods={"GET", "POST"})
     *
     * 认证有效期： session->set('PWD_AUTH',time())
     */
    public function  pwdAuthAction()
    {
        $result = array('error_code' => 0, 'error_msg' => '验证成功');
        $usr = $this->session->get('login_user');
        $pwd = $this->request->getPost('pwd');

        $msg = $this->UserValidatePwdValidation->validate(array('pwd' => $pwd));
        if(count($msg)) {
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );

            Logger::info(" pwdAuthAction error:" . var_export($result, true));
            return $this->response->setJsonContent($result);
        }
        $res = $this->UserService->validatePwd($usr->mobile, $pwd);
        if( !$res ){
            $result['error_code'] = 100003;
            $result['error_msg']  ="原密码输入有误，请重新输入";
        }
        $this->session->set('PWD_AUTH',time());
        return $this->response->setJsonContent($result);
    }

    /**
     * @Route("/validateSmsCode", methods={"GET", "POST"})
     */
    public function  validateSmsCodeAction()
    {

        $result = array('error_code' => 0, 'error_msg' => "验证成功"); // 正确
        $mobile = $this->request->get('mobile');
        $code   = $this->request->get('code');
        $type   = $this->request->get('smsType');
        Logger::info('check code mobile{%s} code:{%s} type:{%s}');
        $user = $this->getUser();
        if ( empty($mobile) && !empty($user)) { // 已登录情况
            $mobile = $user->mobile;
        }
        if ( !$this->ComSmsService->checkCode($mobile, $type, $code)) {
            $result['error_code'] = 1000003;// 不正确
            $result['error_msg'] = '短信验证码不正确.';
        }else{
            // type = CHANGE_PWD
            if( strcmp('CHANGE_PWD',$type) == 0 ){ // 有找回密码
                $this->session->set('CHANGE_PWD_SUCCESS', time());
                $this->ComSmsService->deleteCode( $mobile , $type );
            }
        }
        return $this->response->setJsonContent($result);
    }


    /**
     * @Route("/changePwd", methods={"GET", "POST"})
     */
    public function changePwdAction()
    {
        $newPwd = $this->request->get("newPwd");
        $mobile = $this->request->get("mobile");
        $user   = $this->session->get('login_user');
        Logger::info("mobile:".$mobile." newPwd:".$newPwd);
        if ( empty($mobile) &&!empty($user) ) { // 已登录情况
            $mobile = $user->mobile;
        }

        $user = $this->UserService->getUserByMobile($mobile);
        $result = array('error_code' => 0);
        $isValid = $this->session->get('CHANGE_PWD_SUCCESS');
        Logger::info($mobile." isValid:".$isValid );
        if (empty($isValid) || empty($user)) { //   确认用户 已验证短信,
            $result['error_msg'] = " 修改密码失败。";
            $result['error_code'] = " 1000001";
            return $this->response->setJsonContent($result);
        }
//        Logger::info($mobile." 过期否：".($isValid + self::TEN_MINUTES) > time());
//        if (($isValid + self::TEN_MINUTES) > time()) {
//            $result['error_msg'] = " 修改密码失败,验证短信码已过期。";
//            $result['error_code'] = " 1";
//            $this->session->remove('CHANGE_PWD_SUCCESS');
//            return $this->response->setJsonContent($result);
//        }

        // 密码 至少是 数字 和字母 结合
        if (!StringHelper::hasNumAndChar($newPwd)) {
            $result['error_msg'] = "密码至少包含数字和字母，请重新输入。";
            $result['error_code'] = " 102";
            return $this->response->setJsonContent($result);
        }
        // 更新用户的密码
        $newPwd = md5($newPwd . $user->salt);
        $this->UserService->updateById(['pwd' => $newPwd], $user->id);
        // 添加事件记录
        $this->ActivityLogService->insertActionLog($this->constant->ACTION_TYPE->EDIT_PWD,
            $this->request->getClientAddress(), $user->id, 1, "", 2, "CHANGE_PWD_SUCCESS");
        $result['error_msg'] = "修改密码成功。";
        $result['error_code'] = 0;
        $data = array("mobile" => $user->mobile, "username" => $user->username);
        $result['data'] = array("user" => $data);
        $this->session->remove('CHANGE_PWD_SUCCESS');
        return $this->response->setJsonContent($result);
    }


    public function  checkWhenRegister($mobile, $pwd, $userName, $smsCode, $userType, &$result)
    {
        $messages = $this->RegisterValidation->validate(array(
            "mobile" => $mobile,
            "userName" => $userName,
            "pwd" => $pwd,
            "smsCode" => $smsCode,
            "userType" => $userType
        ));
        if (count($messages)) {
            $error_msg = ArrayHelper::validateMessages($messages);
            $result['error_msg'] = " 参数格式不正确。";
            $result['error_code'] = " 100";
            $result['data'] = $error_msg;
            Logger::info(" checkWhenRegister error:" . var_export($result, true));
            return false;
        }

        // 用户名 过滤 单词
        if ($this->SpamUserNameService->filterName($userName, $result)) { // 有垃圾单词
            $word = $result['filterWord'];
            $result['error_code'] = "102";
            $result["error_msg"] = "用户名中不能包含'.$word.'关键词。";
            return false;
        }
        if ($this->UserService->checkNameExist($userName)) {
            $result['error_msg'] = "用户名已经存在，请重新填写。";
            $result['error_code'] = "103";
            return false;
        }

        if ($this->UserService->checkMobileExist($mobile)) { // 存在
            $result['error_msg'] = "手机号已经存在，请重新填写。";
            $result['error_code'] = "105";
            return false;
        }

        // 密码 至少是 数字 和字母 结合
        if (!StringHelper::hasNumAndChar($pwd)) {
            $result['error_msg'] = " 密码至少包含数字和字母，请重新输入。";
            $result['error_code'] = " 107";
            return false;
        }

        // 检查短信验证码
        $type = 'REGISTER';
        if ( !$this->ComSmsService->checkCode( $mobile, $type, $smsCode ) ) {
            $result['error_msg'] = "短信验证码错误，请重新填写。";
            $result['error_code'] = "108";
            return false;
        }
        $this->ComSmsService->deleteCode( $mobile , $type );
        return true;
    }
    /**
     * @Route("/checkregister", methods={"GET", "POST"})
     */
    public function checkRegisterCodeAction(){
        $req = $this->request->getJsonRawBody(true);
        $registerCode = $req['register_code'];

        $result = array(
            'error_code' => 0,
            'error_msg' => '',
            'data' => '',
        );
        Logger::info('register_code = %s', $registerCode);
        $msg = $this->UserCheckRegisterValidation->validate(array('register_code' => $registerCode));
        if(count($msg)) {
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            $result['data'] = $this->UserService->checkRegisterCode($registerCode);
            if (!$result['data']) {
                $result['error_code'] = 100005;
                $result['error_msg'] = '注册码输入错误';
            }
        }
        return $this->response->setJsonContent($result);
    }
}