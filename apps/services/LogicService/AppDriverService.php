<?php
/**
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/9/6
 * Time: 下午2:17
 */

namespace Services\LogicService;

use Library\Log\Logger;
use Exception;
use Phalcon\Mvc\User\Component;

class AppDriverService extends Component {

    /**
     * 登陆操作
     */
    public function doLogin($mobile, $code) {
        $res = array(
            'error_code'    => '0',
            'error_msg'     => '',
            'data'          => array()
        );

        # 验证参数
        if (empty($mobile) || empty($code)) {
            $return_result['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $return_result['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $return_result;
        }

        if (!preg_match('/^1[0-9]{10}$/', $mobile)) {
            $res['error_code'] = $this->response_code->app->INVALID_MOBILE[0];
            $res['error_msg'] = $this->response_code->app->INVALID_MOBILE[1];

            return $res;
        }

        $user = array();

        # 验证用户
        $result = $this->di->get('AppUserService')->checkUserMobile($mobile, $user);
        if (!$result) {
            $res['error_code'] = $this->response_code->app->USER_IS_NOT_EXISTS[0];
            $res['error_msg'] = $this->response_code->app->USER_IS_NOT_EXISTS[1];

            return $res;
        }

        # 验证验证码
        $cache = $this->di->get('cache');
        $code_key = $this->constant->SMSCODE_REDIS_KEY_PREFIX . $mobile;
        $cache_code = $cache->get($code_key);
        if ($code != $cache_code) {
            $res['error_code'] = $this->response_code->app->INVALID_CODE[0];
            $res['error_msg'] = $this->response_code->app->INVALID_CODE[1];

            return $res;
        }
        else {
            $cache->delete($code_key);
        }

        # 生成token
        $token = $this->di->get('AppUserLoginService')->updateRememberToken($user->id);
        $token = $this->getDI()->get('aes')->encrypt($token);

        # 返回司机信息
        $driver_info = $this->getDriverInfo($user->id, $mobile, $token);
        $res['data'] = $driver_info;

        return $res;
    }

    /**
     * 获取司机信息
     */
    public  function getDriverInfo($user_id, $mobile, $token) {
        $data = array();

        $driver_info = \TbDriver::findFirst(
            array(
                "conditions" => "userid = ?1 AND enable = 1",
                "bind" => array(1 => $user_id)
            )
        );

        # 获取车队信息
        $carteam_info = \CarTeamUser::findFirst($driver_info->team_id);

        # 获取用户信息
        $user_info = \Users::findFirst(
            array(
                "columns"       => "mobile",
                "conditions"    => "id = ?1 AND enable = 1",
                "bind"          => array(1 => $carteam_info->userid)
            )
        );

        $data['driver_id']      = $user_id;
        $data['phone']          = $mobile;
        $data['name']           = !empty($driver_info->driver_name) ? $driver_info->driver_name : '';
        $data['plate_number']   = !empty($driver_info->car_number) ? $driver_info->car_number : '';
        $data['carteam_mobile'] = !empty($user_info->mobile) ? $user_info->mobile : '';
        $data['token']          = $token;

        return $data;
    }

    /**
     * 发送验证码
     */
    public function doSendCode($mobile) {
        $result = array(
            'error_code'    => '0',
            'error_msg'     => '',
            'data'          => array()
        );

        $smsType = 'NORMAL_AUTH';

        # 信息验证并发送code
        if (!preg_match('/^1[0-9]{10}$/', $mobile)) {
            $result['error_code'] = $this->response_code->app->INVALID_MOBILE[0];
            $result['error_msg'] = $this->response_code->app->INVALID_MOBILE[1];

            return $result;
        }

        $ip = $this->request->getClientAddress();
        $typeArray = $this->constant->SMS_TYPE;

        $app_user_service = $this->di->get('AppUserService');
        $app_user_service->setVar(8 * 3600, 5, 5);
        if (!$app_user_service->sendSmsWithCacheAndIpLimit($mobile, $typeArray[$smsType], $ip, 'ANDROID', $result)) { // false 失败
            return $result;
        }

        // 发送成功
        //$result['data']['code'] = $this->di->get('cache')->get($this->constant->SMSCODE_REDIS_KEY_PREFIX . $mobile);
        $result['data'] = '验证码发送成功';
        return $result;
    }

    /**
     * 反馈
     */
    public function doFeedback($driver_id, $driver_mobile, $content) {
        $res = array(
            'error_code'    => '0',
            'error_msg'     => '',
            'data'          => array()
        );

        # 参数验证
        if (empty($driver_id)) {
            $res['error_code'] = $this->response_code->app->INVALID_PARAMS[0];
            $res['error_msg'] = $this->response_code->app->INVALID_PARAMS[1];

            return $res;
        }

        if (empty($content) || mb_strlen($content, 'UTF-8') > 140) {
            $res['error_code'] = $this->response_code->app->FEEDBACK_ERROR[0];
            $res['error_msg'] = $this->response_code->app->FEEDBACK_ERROR[1];

            return $res;
        }

        $data['driver_id'] = $driver_id;
        $data['driver_mobile'] = '';
        $data['content'] = $content;
        $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s');

        $feedbacak = new \AppDriverFeedback();
        $feedbacak->save($data);

        $res['data']['isok'] = '1';
        return $res;
    }
}