<?php

/*
 *
 *php  apps/cli/tools/cli.php EventStatistic
 */
use Library\Log\Logger;
use Library\Helper\ArrayHelper;


/***
 *
 * 定时计算事件次数
 */
class EventstatTask extends \Phalcon\CLI\Task
{

    public function testAction()
    {
       // echo 'sdfsdf';
        $ret = $this->di->get('EventStatisticService')->statTimesHourly(3);

        var_dump($ret);
    }

    // hourly
    public function collectHourlyEventAction($hour_int)
    {
        if( !isset($hour_int[0])) {
            Logger::warn('collectHourlyEvent :error param');
            return false;
        }
        var_dump($hour_int);
        $ret = $this->di->get('EventStatisticService')->statTimesHourly(intval($hour_int[0]));
    }

    // daily
    public function collectDailyEventAction($day_int)
    {
        if( !isset($day_int[0])) {
            Logger::warn('collectDailyEvent :error param');
            return false;
        }
        var_dump($day_int);
        $ret = $this->di->get('EventStatisticService')->statTimesDaily(intval($day_int[0]));
    }


}