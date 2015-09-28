<?php
/**
 * 司机app用户相关接口
 *
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/24
 * Time: 上午11:56
 */

use Library\Log\Logger;
/**
 * @RoutePrefix("/appuser")
 */
class AppUserController extends ControllerBase {

    /**
     * @Post("/login")
     */
    public function loginAction() {
        try{
            # 获取登录信息
            $mobile = $this->request->getPost('mobile', 'string');
            $code   = $this->request->getPost('code', 'int');

            $res = $this->di->get('AppDriverService')->doLogin($mobile, $code);
            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }


    /**
     * @Post("/sendCode")
     */
    public function sendCodeAction() {
        try{
            # 获取手机号码
            $mobile = $this->request->getPost('mobile', 'string');

            $res = $this->di->get('AppDriverService')->doSendCode($mobile);
            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }


    /**
     * @Post("/logout")
     */
    public function logoutAction() {
        try {
            $token = $this->request->getHeader($this->constant->CLINET_TOKEN);

            if ($token) {
                $token = $this->getDI()->get('aes')->decrypt($token);

                # 设置redis过期
                $this->cache->set(
                    $token,
                    $this->constant->LOGIN_RECORD->LOGIN_EXPIRED,
                    $this->constant->LOGIN_SESSION_SECONDS
                );

                #记录log
                Logger::info("APP INFO: xdd-token:". $token . " is logout");

                return $this->response->setJsonContent(
                    array(
                        "error_code" =>0,
                        "error_msg"=>"",
                        "data" => array('isok' => '1')
                    )
                );
            }
        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getCode() . $e->getMessage());
        }
    }

    /**
     * @Post("/feedback")
     */
    public function feedbackAction() {
        try {
            $driver_id      = $this->request->getPost('driver_id', 'int');
            $driver_mobile  = $this->request->getPost('mobile', 'string');
            $content        = $this->request->getPost('content', 'string');


            $res = $this->di->get('AppDriverService')->doFeedback($driver_id, $driver_mobile, $content);
            return $this->response->setJsonContent($res);

        } catch (\Exception $e) {
            Logger::warn("APP WARNING:" . $e->getMessage());
        }
    }

}