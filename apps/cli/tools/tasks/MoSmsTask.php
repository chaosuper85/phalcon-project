<?php

/*
 *
 *php  apps/cli/tools/cli.php MoSms test
 */
use Library\Log\Logger;
use Library\Helper\ArrayHelper;


/***
 *
 * 处理上行短信任务
 * Class MoSmsTask
 */
class MoSmsTask extends \Phalcon\CLI\Task
{
    public function moSmsAction()
    {
        echo "\nThis is the moSmsAction action \n";
    }

    // 发送短信
    public function sendSmsAction()
    {
        $mobiles = array('18210286771');
        $content = 'this is content ';
        $ret_code = $this->SmsService->sendSMS($mobiles, $content);
        if ($ret_code == '0') {
            Logger::info('sms send success mobiles: ' . var_export($mobiles, true) . ' content ' . var_export($content, true));
        } else {
            Logger::info('sms send fail mobiles: ' . var_export($mobiles, true) . ' content ' . var_export($content, true));
        }
        echo "\n finish send sms \n";
    }


    public function testLocationAction()
    {

        $data = \Library\Helper\LocationHelper::getAdressByLocation("116.357552,39.990898");
        if ($data) {
            var_dump($data);
        }
        $data = \Library\Helper\LocationHelper::getLocationByAdress("北京颐和园");
        if ($data) {
            var_dump($data);
        }

    }

    public function testAction()
    {
        $ret_code = $this->OrderSuperService->getSupervisorInfoByOrderid(99) ;
        var_dump($ret_code) ;
        die ;

    }



    //获得上行短信
    /**
     * Array
     * (
     * [0] => Array
     * (
     * [addSerial] =>
     * [addSerialRev] =>
     * [channelnumber] => 10690037cm
     * [mobileNumber] => 18210286771
     * [sentTime] => 20150730232439
     * [smsContent] => 你好
     * )
     *
     * [1] => Array
     * (
     * [addSerial] =>
     * [addSerialRev] =>
     * [channelnumber] => 10690037cm
     * [mobileNumber] => 18210286771
     * [sentTime] => 20150730232432
     * [smsContent] => 12
     * )
     * )
     */
    public function dealMoSmsAction()
    {
        echo "\n dealMoSmsAction .. \n";
        $ret_arr = $this->SmsService->getMO();
        print_r($ret_arr);
        Logger::info(var_export($ret_arr, true));

    }

}