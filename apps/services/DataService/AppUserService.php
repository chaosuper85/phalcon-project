<?php
/**
 * 司机app用户信息服务
 *
 * Created by PhpStorm.
 * User: maxinliang
 * Date: 15/8/24
 * Time: 下午3:01
 */

namespace Services\DataService;

use Phalcon\Mvc\User\Component;
use SmsHistory;
use Users;
use Library\Log\Logger;

class AppUserService extends Component {

    # 时间间隔
    private $_intervals     = null;
    # ip访问限制
    private $_ip_limit      = null;
    # 手机访问限制
    private $_mobile_limit  = null;

    # 验证码缓存时间12小时
    const SMSCODE_CACHE_TIME    = 43200;


    public function setVar($intervals, $ip_limit, $mobile_limit) {
        $this->_intervals       = $intervals;
        $this->_ip_limit        = $ip_limit;
        $this->_mobile_limit    = $mobile_limit;
    }

    /**
     * 通过手机号获取用户信息
     */
    public function checkUserMobile($mobile, &$user = array(), $usertype = 3) {
        try {
            $conditions = array('mobile = ?1', 'enable = 1');
            $bind = array(1 => $mobile);
            if ($usertype) {
                $conditions[] = 'usertype = ?2';
                $bind[2] = $usertype;
            }

            $res = Users::FindFirst(
                array(
                    "conditions"    => implode(' AND ', $conditions),
                    "bind"          => $bind
                )
            );

            if (empty($res)) {
                return false;
            }
            else {
                $user = $res;
                return true;
            }
        }catch (\Exception $e) {
            Logger::warn("app warn:" . $e->getMessage());
        }
    }

    /**
     * @param $from
     * @param $to
     * @param $mobile
     * @param  $ip
     * @return int
     */
    public function countByMobileOrIpAndPeriod($from=false, $to=false, $mobile=false, $ip=null)
    {
        $condtions = 'TRUE';
        $condtions .= $from ? " AND created_at>='$from'":'';
        $condtions .= $to ? " AND created_at<='$to'":'';
        $condtions .= $mobile ? " AND mobile='$mobile'":'';
        $condtions .= $ip ? " AND ip='$ip'":'';

        return SmsHistory::count($condtions);
    }



    /**
     *  通过ip 或 手机 检查 发送短信限制
     * @return bool 若超过限制 返回true  否则 false
     */
    public function checkSmsLimit($mobile, $ip)
    {

        if($this->constant->application_mode != 'prod' ){
            return false;
        }

        // 对每一个 iP发送短信做限制
        $to   = date('Y-m-d H:i:s',time());
        $from = date('Y-m-d H:i:s',time()-$this->_intervals);
        try{
            //$ipLimit = $this->countByMobileOrIpAndPeriod($from,$to,null,$ip);
            $ipLimit = 0;
            $mobileLimit = $this->countByMobileOrIpAndPeriod($from,$to,$mobile,null);

            if( $ipLimit > $this->_ip_limit || $mobileLimit >= $this->_mobile_limit ){
                return true;//超过限制
            }else{
                return false;//正常
            }
        }catch (\Exception $e){
            Logger::error("checkSmsLimit ip:".$ip." mobile".$mobile);
            return  false;
        }
    }


    /**
     * @param $mobile
     * @param $smsType  0/1
     * @param $clientIp
     * @param $platform_type
     * @param $result 返回的结果
     * @return bool 发送失败 false  发送成功 true
     */
    public function sendSmsWithCacheAndIpLimit($mobile,$smsType,$clientIp, $platform_type, &$result=array())
    {
        // 对发送短信做限制(仅对手机号做处理)
        if ($this->checkSmsLimit($mobile,$clientIp)) {
            $result['error_code'] = $this->response_code->app->BEYOND_SEND_SMS_COUNT[0];
            $result['error_msg']  = $this->response_code->app->BEYOND_SEND_SMS_COUNT[1];
            Logger::warn("来自iP".$clientIp." 手机号".$mobile."发送短信在一段时间内超过了限制。");
            return  false;
        }

        $user_info = array();
        $exist  = $this->checkUserMobile($mobile, $user_info, 0);

        if( !$exist) {
            # 需将用户手机记录
            $result['error_code'] = $this->response_code->app->USER_IS_NOT_EXISTS[0];
            $result['error_msg'] = $this->response_code->app->USER_IS_NOT_EXISTS[1];

            # 将未注册的手机号，写入数据表
            $unregister_users = array('mobile' => $mobile, 'source_platform' => $this->constant->PLATFORM_TYPE->$platform_type);
            $unregister_model = new \UnregisterUsers();
            $unregister_model->save($unregister_users);

            return false;
        }

        // 发送短信
        $channel = 1;

        # 检测redis是否有该验证码
        $is_cached = false;
        $code = $this->di->get('cache')->get($this->constant->SMSCODE_REDIS_KEY_PREFIX . $mobile);
        if (empty($code)) {
            $code    = rand(1001, 9999);
        }
        else {
            $is_cached = true;
        }

        $content = '您的验证码是:' . $code;
        Logger::info('send sms to '.$mobile.' 验证码是：'.$code);
        if( $this->sendSms($mobile,$content,true,$code,$channel, $is_cached)){ // 保存 短信历史
            $this->SmsHistoryService->saveSmsHistory( $clientIp,$mobile,$content,$channel,$this->constant->PLATFORM_TYPE->$platform_type ,1.0);
            return true;
        }else{
            return false;
        }
    }


    /** 通用发送短信
     * @param $mobile
     * @param bool|true  $isCache 是否缓存 code ，true 缓存code, 否则，不缓存
     * @param int $channel
     * @param  $content
     * @return bool
     */
    public function sendSms($mobile,&$content,$isCache = false,$code = null,&$channel =1, $is_cached = false)
    {
        if($this->constant->application_mode != 'prod' ){
            $code = '1111';
        }

        if( $isCache && isset($code) && $is_cached === false ){ // 缓存
            $this->cache->set($this->constant->SMSCODE_REDIS_KEY_PREFIX . $mobile, $code, self::SMSCODE_CACHE_TIME); // 缓存 code
        }

        $res  = $this->SmsService->sendMs($mobile,$content);
        if( empty($res) ){ // fail
            $res     = $this->SmsBackupService->sendMessage($mobile,$content);
            $channel = 2;
            Logger::info("sendSms 使用通道2： result:".var_export($res,true));
            $res     = $res['success'];
        }
        return $res;
    }


}