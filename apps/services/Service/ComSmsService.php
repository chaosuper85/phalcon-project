<?php
namespace Services\Service;

use Library\Log\Logger;
use Phalcon\Mvc\User\Component;

/**
 *   重构发送短信
 *     短信类型 和 通道类型
 *    // 发送短信用途
 * 'SMS_TYPE' =>
 * [
 * 'REGISTER' => '0',     // 注册
 * 'CHANGE_PWD' => '1',   // 找回密码
 * 'APPLY_ADMIN' => '2',  // 申请管理员
 * 'CHANGE_MOBILE' => 3,  //换绑新手机
 * 'NORMAL_AUTH' => 4,    // 普通身份认证
 * ],
 */
class ComSmsService extends Component
{
    /**
     *  发送短信  有验证码
     */
    public function  sendSmsWithCode($mobile, $type, &$result = array())
    {
        $ip = $this->request->getClientAddress();
        // 对每一个 iP发送短信做限制
        if ($this->SmsHistoryService->checkSmsLimit($mobile, $ip)) {
            $result['error_code'] = '200';
            $result['error_msg'] = "来自同一个ip的发送短信的数量在一段时间内超过了限制.";
            Logger::warn(" mobile:{%s} ip:{%s} over limit ");
            return false;
        }
        $code = rand(1001, 9999);

        if($this->constant->application_mode != 'prod' ){
            $code = '1111';
        }

        try {
            switch ($type) {  // todo
                case 'REGISTER':
                    break;
                case 'CHANGE_PWD':
                    break;
                case 'APPLY_ADMIN':
                    break;
                case 'CHANGE_MOBILE':
                    break;
                case 'NORMAL_AUTH':
                    break;
                default :
                    $result['error_code'] = 3000001;
                    $result['error_msg'] = "发送短信类型错误";
                    return false;
            }
            $this->saveCode( $mobile,$code,$type );
            $content = vsprintf($this->constant->SMS_PATTERN[$type], [$code]);
            $res = $this->send($mobile, $content);
        } catch (\Exception $e) {
            Logger::warn("sendSmsWithCode error:{%s}", $e->getMessage());
            $result['error_code'] = 3000002;
            $result['error_msg'] = "发送短信失败。";
            $res = false;
        }
        if ($res) {
            $result['error_code'] = 0;
            $result['error_msg'] = "发送短信成功。";
        }
        Logger::info("sendSmsWithCode: mobile:{%s} type:{%s} result:{%s}", $mobile, $type, var_export($result, true));
        return $res;
    }

    /**
     *  发送短信 没有验证码
     */
    public function  sendSms($mobile, $type, $params = array(), &$result = array())
    {
        $ip = $this->request->getClientAddress();
        // 对每一个 iP发送短信做限制
        if ($this->SmsHistoryService->checkSmsLimit($mobile, $ip)) {
            $result['error_code'] = '200';
            $result['error_msg'] = "来自同一个ip的发送短信的数量在一段时间内超过了限制.";
            Logger::warn(" mobile:{%s} ip:{%s} over limit ");
            return false;
        }
        try {
            if (isset($this->constant->SMS_TYPE[$type])) {
                if (empty($params)) {
                    $content = $this->constant->SMS_PATTERN[$type];
                } else {
                    $content = vsprintf($this->constant->SMS_PATTERN[$type], $params);
                }
                $res = $this->send($mobile, $content,true);
                if (!$res) {
                    $result['error_code'] = 3000003;
                    $result['error_msg'] = "发送短信失败。";
                }
            } else {
                $result['error_code'] = 3000002;
                $result['error_msg'] = "发送短信类型错误";
                $res = false;
            }
        } catch (\Exception $e) {
            Logger::warn("sendSmsWithCode error:{%s}", $e->getMessage());
            $result['error_code'] = 3000001;
            $result['error_msg'] = "发送短信失败。";
            $res = false;
        }
        if ( $res ) {
            $result['error_code'] = 0;
            $result['error_msg'] = "发送短信成功。";
        }
        Logger::info("sendSmsWithCode: mobile:{%s} type:{%s} result:{%s}", $mobile, $type, var_export($result, true));
        return $res;
    }

    /**  统一验证接口
     *  验证短信码 是否正确
     */
    public function  checkCode($mobile, $type, $code, &$result = array())
    {
        if (empty($code) || empty($type) || empty($mobile)) {
            $result['error_code'] = 3000001;
            $result['error_msg'] = '短信验证码不正确';
            return false;
        } else {
            $codeInCache = $this->getCode( $mobile,$type );
            Logger::info("mobile:{%s} type:{%s} checkCode: code:{%s} codeInCache:{%s}", $mobile, $type, $code, $codeInCache);
            if (!empty($codeInCache) && $code == $codeInCache) {
                $result['error_code'] = 0;
                $result['error_msg'] = '短信验证码正确';
                return true;
            } else {
                $result['error_code'] = 3000002;
                $result['error_msg'] = '短信验证码不正确';
                return false;
            }
        }
    }

    /**
     *   删除验证码
     */
    public function  deleteCode($mobile, $type)
    {
        $key = $this->getKey( $mobile , $type );
        if ($this->cache->exists($key)) {
            $this->cache->delete($key);
        }
    }

    public function  existCode( $mobile, $type ){
        $key = $this->getKey( $mobile, $type );
        return $this->cache->exists($key);
    }


    /**
     *  channel  1=> 默认 一美
     */
    private function send($mobile, $content, $async = false)
    {
        if( $this->constant->application_mode != 'prod' ){
            return true ;
        }
        $result = $this->SmsService->sendMs($mobile, $content);
        $ip = $this->request->getClientAddress();
        if (empty ($result)) {
            Logger::warn("  yimei smsService failed");
            $result = $this->SmsBackupService->sendMessage($mobile, $content);
            if ( empty( $result ) ) {
                Logger::warn("SmsBackupService failed");
            } else if ( $async ) {
                $this->SmsService->asyncSendSMS([ $mobile],$content);
                $this->SmsHistoryService->saveSmsHistory($ip, $mobile, $content, 1, $this->constant->PLATFORM_TYPE->PC, 1.0);
                $result = true;
            } else {
                $this->SmsHistoryService->saveSmsHistory($ip, $mobile, $content, 2, $this->constant->PLATFORM_TYPE->PC, 1.0);
            }
        } else {
            $this->SmsHistoryService->saveSmsHistory($ip, $mobile, $content, 1, $this->constant->PLATFORM_TYPE->PC, 1.0);
        }
        Logger::info("send SMS to mobile:{%s} content:{%s}", $mobile, $content);
        return $result;
    }



    public  function  saveCode( $mobile, $code , $type , $continue= 600 ){
        $this->cache->set( $this->getKey( $mobile, $type ), $code, $continue );
    }

    public function  getCode( $mobile, $type ){
        $codeInCache = $this->cache->get( $this->getKey( $mobile, $type ));
        return $codeInCache;
    }

    private function getKey( $mobile, $type ){
        $key = 'smsCode_' . $type . "_" . $mobile;
        return $key;
    }

}