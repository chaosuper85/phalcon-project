<?php namespace Modules\admin\Events;

use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;
use Phalcon\Events\Manager as EventsManager;
use Modules\admin\Events\ActivityLogListener;

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

    /*
     * 订单的修改历史 ，data的格式大概是这样
`action_type` ，17为订单的修改记录
`reamId` ，为操作者的userid
`reamType`，为操作者的usertype
`targetReamId` ，为操作的orderid
`targetReamType` ，为操作的事件type，
1增加产装地址，
2增加分派司机，
3增加箱号，铅封号，
4修改产装地址，
5修改分派司机，
6修改箱号，铅封号，
7增加产装时间，
8修改产装时间，
9删除产装地址，
10删除产装时间，

`jsonContent` ，操作的具体内容：
操作人：操作时间：操作内容，（订单id，增加/修改/删除 产装地址id，增加/修改/删除 产装时间 ）

array(
'user_id'=>'12',
'date_time'=>'',
'content'=>array(
'orerid'=>'123',
'address'=>array(
"provinceid"=> 1,
"cityid"=> 2,
"townid"=> 3,
"address"=> "黄村镇清源西里9号"
),
'address_id'=>'',
'product_supply_time'=>'',
'product_supply_time_id'=>'',
'driver_id'=>'',
'box_info'=>array(
'box_code'=>'',
'box_ensupe'=>'',
),
'box_id'=>0
)
);
*/

    public function orderHistroyLog( $data )
    {
        $this->data = $data;

        $this->_eventsManager->fire("activitylog-component:orderHistroyLog", $this);
    }



}