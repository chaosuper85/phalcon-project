<?php

/*
 * php  apps/cli/tools/cli.php sms asyncSend
 */
use Library\Helper\ArrayHelper;
use Library\Log\Logger;

class SmsTask extends \Phalcon\CLI\Task
{

    # 日志文件名
    const LOG_FILENAME = 'SmsSend';

    public function mainAction()
    {
        echo "\nThis is the default task and the default action \n";


        $log_content = "TASK ORDER_CHECK: order_id " . 12;
        $log_content .= ", box_code " . 15 . ", box_ensupe " . 16;
        $log_content .= " is not match the table OrderFreightBox";


        Logger::info(
            $this->constant->LOG_SEPORATER . $log_content,
            $this->constant->LNS,
            self::LOG_FILENAME
        );




    }

    /**
     * @param array $params
     */
    public function asyncSendAction()
    {
        Logger::info("script begin to excute ");

        $time_start = microtime(true);

        $beanstalkdIp = $this->queue->beanstalkd->ip;
        $beanstalkdTube = $this->queue->beanstalkd->smstube;
        $beanstalkdIgnoreTube = $this->queue->beanstalkd->ignore;

        $pheanstalk = new Pheanstalk($beanstalkdIp);

        while ($job = $pheanstalk->watch($beanstalkdTube)->ignore($beanstalkdIgnoreTube)->reserve(0)) {
            $data = $job->getData();

            $smsData = json_decode($data);
            $smsData = ArrayHelper::objectToArray($smsData);

            $mobiles = $smsData['mobiles'];
            $content = $smsData['content'];

            Logger::info('smsData: ' . var_export($smsData, true));

            $ret_code = $this->SmsService->sendSMS($mobiles, $content);
            if ($ret_code == '0') {
                Logger::info('sms send success mobiles: ' . var_export($mobiles, true) . ' content ' . var_export($content, true));
            } else {
                Logger::info('sms send fail mobiles: ' . var_export($mobiles, true) . ' content ' . var_export($content, true));
            }

            $pheanstalk->delete($job);
        }

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        Logger::info('SmsWorker->asyncSendSms cost ' . $time . ' seconds');


        return;
    }
}