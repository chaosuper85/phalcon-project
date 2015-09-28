<?php namespace Modules\admin\Events;

use Library\Log\Logger;
use Phalcon\Mvc\User\Component;

class ActivityLogListener extends Component
{

    /*
$data = array(
                'action_type'=>17,
                'reamId'=>123,
                'reamType'=>1,
                'targetReamId' =>123,
                'targetReamType'=>1,
               'json_content' => array(
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
                    'box_id'=>123,
            ));
     */

    public function orderHistroyLog($event, $myComponent)
    {
        $data = $myComponent->getData();
        Logger::info("add order action log content:%s", var_export($data,true));
        $this->ActivityLogService->addOrderActionLog( $data );


    }



}