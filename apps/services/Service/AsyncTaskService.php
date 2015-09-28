<?php
namespace Services\Service;


use Phalcon\Mvc\User\Component;
use  Pheanstalk\Pheanstalk;

class AsyncTaskService extends Component
{

    protected  $talk;
    protected  $tube;




    public function __construct( $tube )
    {
        $config = $this->queue->beanstalkd;
        $ip   = $config->ip;
        $port = $config->port;
        $this->tube = $tube;
        $this->talk = new Pheanstalk( $ip,$port );
    }

    /**  生产者
     * @param $data
     * @param $tube
     */
    public function  asyncTask( $data ){
        try{
            $jobId  = $this->talk->useTube( $this->tube )->put( json_encode( $data ));
            return $jobId;
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     *  消费者 todo
     */
    public function  executeTask(){
        exec();
        while( ( $job = $this->talk->watch( $this->tube )->reserve()) ){
            $data = $job->getData();
            $id   = $job->getId();
            //
            $this->talk->delete( $job );
        }
    }



}