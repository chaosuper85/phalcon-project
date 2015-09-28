<?php

use Library\Log\Logger;
use Pheanstalk\Pheanstalk;
class LocationTask  extends \Phalcon\CLI\Task
{
    public function executeAction(){
        $start  = microtime();
        $config = $this->queue->beanstalkd;
        $ip   = $config->ip;
        $port = $config->port;
        $talk= new Pheanstalk( $ip,$port );
        $tubes = $talk->listTubes();
        Logger::info("tubes:{%s}",var_export( $tubes,true));
        foreach( $tubes as $tube ){
            while( ( $job = $talk->watch( $tube)->reserve() )!=null ){
                $data = $job->getData();
                $id   = $job->getId();
                Logger::info(" jod:{%s} data:{}",$job->getId(),var_export( $job->getData(),true));
                //
                $talk->delete( $job );
            }
        }
        $end    = microtime();
        Logger::info("total time:{%s}",$end-$start );

        return ;
    }
}