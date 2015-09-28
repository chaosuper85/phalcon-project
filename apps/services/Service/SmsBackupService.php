<?php

namespace Services\Service;

use Library\Log\Logger;
use Services\Services;


use \Phalcon\DiInterface;
use Phalcon\Mvc\User\Component;

/**
 * Class SmsBackupService  短信备份通道
 * @package Modules\Services\Service
 */
class SmsBackupService extends Component
{

    protected $sendMsUrl; // 发送短信 接口
    protected $queryBalanceUrl;//查询余额接口
    protected $apiKey;// 秘钥
    protected $isdebug=false;// 是否debug

    const xdd_sign='【箱典典】';

    public function __construct(  ){

        $config= $this->common_config->smsbackup;

        $this->sendMsUrl = $config->SEND_MS_URL; //Config::get('smsBackup.SMS_BACKUP_CONFIG.SEND_MS_URL');
        $this->queryBalanceUrl = $config->QUERY_BALANCE_URL;//;//Config::get('smsBackup.SMS_BACKUP_CONFIG.QUERY_BALANCE_URL');
        $this->apiKey = $config->API_KEY;// Config::get('smsBackup.SMS_BACKUP_CONFIG.API_KEY');
        $this->isdebug =  $config->DEBUG;//Config::get('smsBackup.SMS_BACKUP_CONFIG.DEBUG');
    }



    /**
     * @param $mobile
     * @param $content
     * @return object    true ：发送短信成功 false: 发送失败
     *
     */
    public function sendMessage($mobile, $content)
    {

        if (empty($mobile) || empty($content)) {
            return false;
        }
        // 添加签名
        $content.=self::xdd_sign;
        $data = array('mobile' => $mobile, 'message' => $content);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->sendMsUrl);
        Logger::info('url'.$this->sendMsUrl);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey);

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        Logger::info("send mobile:" . $mobile . "   message:" . $content . "   result:" . var_export($result, true));
        curl_close($ch);
        if ($result != null) {
            $resArr = json_decode($result, true);
            $resObject['message']= $resArr['msg'];
            switch ($resArr['error']) {
                case 0: //successful
                    return true;
                default:
                    Logger::warn("smsBackup failed:{%s}",var_export( $resArr,true));
                    return false;
            }
        } else {
            return false;
        }
    }


    /**
     *  查询余额
     * @Array
     */
    public function queryBalance()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->queryBalanceUrl);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey);
        $res = null;
        $res = curl_exec($ch);
        Logger::info(' queryBalance result:' . var_export($res, true));
        curl_close($ch);
        return json_decode($res, true);
    }

}