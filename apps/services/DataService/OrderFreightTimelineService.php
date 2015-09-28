<?php
namespace Services\DataService;


use Phalcon\Mvc\User\Component;
use OrderFreightTimeline;

class OrderFreightTimelineService extends Component
{
    public function  save($order_freight_id, $ordertimeline_type, $verify_ream_type, $verify_ream_id, $jsonContent )
    {
        $orderFreightTimeline = new OrderFreightTimeline();
        $orderFreightTimeline->order_freight_id = $order_freight_id;
        $orderFreightTimeline->ordertimeline_type = $ordertimeline_type;
        $orderFreightTimeline->verify_ream_type = $verify_ream_type;
        $orderFreightTimeline->verify_ream_id = $verify_ream_id;
        $orderFreightTimeline->jsonContent = $jsonContent;
        $ret = $orderFreightTimeline->save();

        return $ret;
    }


}