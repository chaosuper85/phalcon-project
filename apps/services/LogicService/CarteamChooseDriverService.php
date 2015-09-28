<?php


namespace Services\LogicService;


use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Library\Helper\ArrayHelper;

use TbDriver;
use Users;
use Phalcon\Mvc\Model\Resultset;

class CarteamChooseDriverService extends Component
{

    public function chooseDriver( $req )
    {
        $carTeamId = $req['carTeamId'];

        $conditions = "work_status = :work_status: AND team_id = :carteamid: AND enable = :enable:";

        $parameters = array(
            "work_status" => '1',
            "carteamid" => $carTeamId,
            "enable" => '1',
        );

        $res = TbDriver::find(array(
            $conditions,
            "bind" => $parameters,
            "columns"=> array('userid','driver_name','car_number'),
        ));


        $ret = array();
        foreach( $res as $key => $value )
        {
            $temp = $value->toArray();

            $userid = $temp['userid'];
            $user = Users::findFirst( array("id = ?1",'bind'=>[1=>$userid]));
            $temp['mobile'] = empty( $user )? "" : $user->mobile;
            $ret[$key] = $temp;
        }

        return $ret;
    }

}
