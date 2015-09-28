<?php   namespace Services\DataService;
/**
 * Created by PhpStorm.
 * 迁移：haibo
 * Date: 15/7/23
 * Time: 下午1:14
 */


use SmsHistory;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Library\Helper\QueryHelper;

class SmsHistoryService extends Component
{
    const intervals = 300; // 发送短信的 时间 间隔 5分钟
    const IP_LIMIT = 20 ; // 5 分钟 同一个Ip 发送短信 次数
    const MOBILE_LIMIT = 10;// 5 分钟 同一个手机号  发送短信 次数


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

    public function saveSmsHistory( $ip,$mobile, $msgContent,$channel=1,$platform = 1,$version=1.0,$deviceId = "PC")
    {
        $smsHistory = new SmsHistory();
        $smsHistory->ip = $ip;
        $smsHistory->platform = $platform;
        $smsHistory->version = $version;
        $smsHistory->deviceId = $deviceId;
        $smsHistory->mobile =  $mobile;
        $smsHistory->msgContent = $msgContent;
        $smsHistory->created_at = date('Y-m-d H-i-s');
        $smsHistory->channel = $channel;
        $smsHistory->objectId = 0;
        $smsHistory->save();
    }

    /**
     *  通过ip 或 手机 检查 发送短信限制
     * @return bool 若超过限制 返回true  否则 false
     */
    public function checkSmsLimit($mobile, $ip)
    {
        // 对每一个 iP发送短信做限制
        $to   = date('Y-m-d H:i:s',time());
        $from = date('Y-m-d H:i:s',time()-self::intervals);
        try{
            $ipLimit = $this->countByMobileOrIpAndPeriod($from,$to,null,$ip);
            $mobileLimit = $this->countByMobileOrIpAndPeriod($from,$to,$mobile,null);
            if( $ipLimit > self::IP_LIMIT || $mobileLimit >= self::MOBILE_LIMIT ){
                Logger::info("checkSmsLimit mobile:".$mobile.' ip:'.$ip." $ipLimit:".$ipLimit." $mobileLimit:".$mobileLimit." 超过限制");
                return true;//超过限制
            }else{
                return false;//正常
            }
        }catch (\Exception $e){
            Logger::error("checkSmsLimit ip:".$ip." mobile".$mobile);
            return  false;
        }
    }

    public function querySmsHistory()
    {
        $cond['columns'] = 'objectId, platform, version';
        $data = QueryHelper::cond('\SmsHistory',$this->request, $cond);

        //状态字段数字转中文
        $hashArr = $this->constant->PLATFORM_ENUM;
        foreach($data as $k=>$v) {
            $data[$k]['platform'] = $hashArr[$v['platform']];
        }

        return $data;
    }


}