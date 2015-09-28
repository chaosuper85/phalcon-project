<?php
namespace Modules\admin\Events;

use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;
use Phalcon\Events\Manager as EventsManager;


/**
 *  获取 地址 经纬度，并更新
 */
class AddressComponent implements  EventsAwareInterface
{

    protected   $eventsManager;

    protected   $data;

    public function __construct()
    {
        $eventsManager = new EventsManager();
        $this->eventsManager = $eventsManager;
        $eventsManager->attach('address-component', new AddressListener());
    }
    /**
     * Sets the events manager
     *
     * @param \Phalcon\Events\ManagerInterface $eventsManager
     */
    public function setEventsManager(ManagerInterface $eventsManager)
    {
        $this->eventsManager = $eventsManager;
    }

    /**
     * Returns the internal event manager
     *
     * @return \Phalcon\Events\ManagerInterface
     */
    public function getEventsManager()
    {
        return $this->eventsManager;
    }

    /** 获取经纬度,并更新
     * @param $data
     */
    public function getLocationEvent( $data )
    {
        $this->data = $data;

        $this->eventsManager->fire("address-component:getLocation", $this,$data);
    }
}