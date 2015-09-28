<?php


namespace Services;


use Library\Log\Logger;
use \UnitTestCase as UnitTestCase;


class EventStatServiceTest extends UnitTestCase
{
    public function testMain()
    {
        $i = 0;
        for(;$i<25;$i++)
        {
            var_dump($i%24);echo "</br>";
        }



        //$this->di->get('EventStatisticService')->statTimesHourly(0);
    }


}


