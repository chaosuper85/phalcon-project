<?php namespace Modules\www\Events;

use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;
use Phalcon\Events\Manager as EventsManager;
use Modules\www\Events\ActivityLogListener;

class ActivityLogComponent implements EventsAwareInterface
{
    protected $_eventsManager;



    protected $data;

    public function __construct()
    {
        $eventsManager = new EventsManager();
        $this->_eventsManager = $eventsManager;
        $eventsManager->attach('activitylog-component', new ActivityLogListener());
    }

    public function setEventsManager( ManagerInterface $eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }

    public function getEventsManager()
    {
        return $this->_eventsManager;
    }

    public function getData()
    {
        return $this->data;
    }

    /** 促发 订单修改日志记录事件
     * @param $data
     */
    public function orderHistroyLog( $data )
    {
        $this->data = $data;

        $this->_eventsManager->fire("activitylog-component:orderHistroyLog", $this);
    }

    /**
     *  货代创建订单成功，通知车队接单
     */
    public function noticeCarTeam( $carrier_userid,$freightagent_user  ){
        $this->data = array(
            'freightId' => $freightagent_user,
            'carTeamId' => $carrier_userid,
        );
        $this->_eventsManager->fire("activitylog-component:noticeCarTeam", $this);
    }


    /**
     *  箱子状态变化时 通知司机
            'TO_GET_TASK'           => '您有新的集装箱任务到达，请查看',
            'BOX_STATUS_CHANGED'    => '您运送的集装箱有变化，请查看',
            'ORDER_CANCELED'        => '有集装箱任务被取消，请查看',
            'TO_WEB'                => '点此查看详情',
            'BOX_INFO_CHANGED'      => '您运送的集装箱有变化，请查看',
     */
    public function noticeDriverWhenBoxChange( $boxId ,$orderId ,$driverId){
        $this->data= array(
            'driverId' => $driverId,
            'boxId' => $boxId,
            'orderId' =>$orderId,
            'type'=>'BOX_INFO_CHANGED'
        );
        $this->_eventsManager->fire("activitylog-component:noticeDriver", $this);
    }

    /**
     *  通知 司机新任务
     */
    public function  noticeDriverNewTask( $driverId ,$boxId ,$orderId){
        $this->data= array(
            'driverId' => $driverId,
            'boxId' => $boxId,
            'orderId' =>$orderId,
            'type'=>'TO_GET_TASK'
        );
        $this->_eventsManager->fire("activitylog-component:noticeDriver", $this);
    }

    /**
     *  通知 司机 取消任务
     */
    public function  noticeDriverCancelTask( $driverId ,$boxId ,$orderId){
        $this->data= array(
            'driverId' => $driverId,
            'boxId' => $boxId,
            'orderId' =>$orderId,
            'type'=>'ORDER_CANCELED'
        );
        $this->_eventsManager->fire("activitylog-component:noticeDriver", $this);
    }


}