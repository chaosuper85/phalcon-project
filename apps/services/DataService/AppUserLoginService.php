<?php
/**
 * 司机app用户登录服务
 *
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/24
 * Time: 下午3:01
 */

namespace Services\DataService;

use Phalcon\Mvc\User\Component;
use Users;
use \LoginRecord as LoginRecord;
use Library\Helper\StringHelper;
use Library\Log\Logger;

use \Phalcon\DiInterface;

use \Phalcon\Crypt;

class AppUserLoginService extends Component {

    public function updateRememberToken( $user_id )
    {

        $time = time() + $this->constant->LOGIN_SESSION_SECONDS ;
        $token = $user_id . '-' . $time;

        $conditions = "id = :id:";
        $parameters = array(
            "id" => $user_id,
        );

        $user = Users::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));

        $user->remember_token = $token;
        $user->save();

        /*if ($user->save() == false) {
            foreach ($user->getMessages() as $message) {
                echo $message;
            }
        }*/

        //把其他的登录用户踢出登录
        $loginSuccess = $this->constant->LOGIN_RECORD->LOGIN_SUCCESS;
        $loginKicked = $this->constant->LOGIN_RECORD->KICKED;
        $expireTime = $this->constant->LOGIN_SESSION_SECONDS;

        $this->LoginRecordService->updateLoginRecord($user_id, $loginSuccess, $loginKicked  );

        $loginRecord = new LoginRecord();
        $loginRecord->token = $token;
        $loginRecord->user_id = $user_id;
        $loginRecord->expire_date = date('Y-m-d H:i:s', $time);
        $loginRecord->status =  $loginSuccess;

        $loginRecord->save();
        $this->cache->set( $token, $loginSuccess, $expireTime );

        return $token;
    }

    public function getUserStatusByToken( $token )
    {
        $status =  $this->constant->LOGIN_RECORD->LOGIN_EXPIRED;
        if ( $this->cache->exists($token) )
        {
            $status = $this->cache->get($token);
        }

        return $status;
    }


    public function getUserByToken( $token )
    {
        $jsonRet = array(
            'code' => 0,
            'data' => 'success'
        );

        if ( !empty($token) ) {
            // 先解密

            $rememberToken = $token;
            $tokenArr = explode("-", $rememberToken);

            if (sizeof($tokenArr) == 2)
            {
                $user_id = $tokenArr[0];
                $tokenExpire = $tokenArr[1];

                $conditions = "id = :user_id: AND remember_token = :remember_token:";

                $parameters = array(
                    "user_id" => $user_id,
                    "remember_token" => $user_id . '-' . $tokenExpire
                );

                $user = Users::findFirst(array(
                    $conditions,
                    "bind" => $parameters
                ));

                if( !$user ){
                    $jsonRet = array(
                        'code' => 2,
                        'data' => 'token is invalid user'
                    );
                    Logger::info(' jsonRet:' . var_export($jsonRet,true));
                    return $jsonRet;
                }

                if (time() > $tokenExpire) {

                    //记录到缓存
                    $status = $this->constant->LOGIN_RECORD->EXPIRED;
                    $expireTime = $this->constant->LOGIN_SESSION_SECONDS;

                    $this->cache->set($token, $status, $expireTime);

                    // 校验是否失效
                    $jsonRet = array(
                        'code' => 3,
                        'data' => 'token is expired'
                    );
                    Logger::info(' jsonRet:' . var_export($jsonRet,true));
                    return $jsonRet;
                }

                //验证是否被踢出
                $conditions = "user_id = :user_id: AND status = :status:  AND token = :token:";

                $parameters = array(
                    "user_id" => $user_id,
                    "status" => $this->constant->LOGIN_RECORD->KICKED,
                    "token" => $token
                );

                $res = LoginRecord::findFirst(array(
                    $conditions,
                    "bind" => $parameters
                ));

                if( $res )
                {
                    $jsonRet = array(
                        'code' => 4,
                        'data' => 'user is kicked out'
                    );
                    Logger::info(' jsonRet:' . var_export($jsonRet,true));
                    return $jsonRet;
                }

                $jsonRet['user'] = $user;
            } else {
                $jsonRet = array(
                    'code' => 1,
                    'data' => 'token format error'
                );

            }

        } else {
            $jsonRet = array(
                'code' => 2,
                'data' => 'token empty'
            );

        }

        return $jsonRet;

    }


    public function checkLogin( $token )
    {
        /*
         * 0,登录成功
         * 1,用户账号登录过期
         * 2,用户账号在其他地方登录
         */
        $isLogin = false;
        $status = $this->getUserStatusByToken( $token );

        $kicked = $this->constant->LOGIN_RECORD->KICKED;
        $loginSuccess = $this->constant->LOGIN_RECORD->LOGIN_SUCCESS;

        if( empty($status) || $status == $this->constant->LOGIN_RECORD->LOGIN_EXPIRED )
        {
            $result = array(
                'error_code' => 1003,
                'error_msg' => '用户账号登录过期',
                'data' => array(),
            );
            return $result;
        }else if( $status == $kicked )
        {
            $result = array(
                'error_code' => 1002,
                'error_msg' => '用户账号在其他地方登录',
                'data' => array(),
            );

            return $result;
        }else if( $status == $loginSuccess )
        {
            $ret = $this->getUserByToken( $token );
            if ( $ret['code'] == 0 && !empty($ret['user']) ) {
                $isLogin = true;
            }
        }

        if( $isLogin )
        {
            return $result = array(
                'error_code' => 0,
                'error_msg' => '',
                'data' => array(),
            );
        }else{
            return $result = array(
                'error_code' => 1004,
                'error_msg' => '用户登录错误',
                'data' => array(),
            );
        }
    }


}
