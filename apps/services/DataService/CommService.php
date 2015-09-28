<?php namespace Services\DataService;


use Phalcon\Mvc\User\Component;

//迁移 haibo

class CommonService extends Component
{


    public  function getCarTeamUserIdByName( $name )
    {
        $sql = "SELECT  userid  FROM car_team_user WHERE TRUE ";
        $sql .= " AND ( teamName = 'userid' OR ownerName = 'userid' ) ";

        $results = $this->db->fechAll($sql);
        $userid = 0;
        if( !empty($results[0]) )
        {
            $userid = $results[0]->userid;
        }
        return $userid;
    }

    public  function getCarTeamUserIdByMobile( $mobile )
    {
        $sql = "SELECT  id  FROM users WHERE TRUE ";
        $sql .= " AND  mobile = '$mobile' ";
        $results = $this->db->fechAll($sql);
        $userid = 0;
        if( !empty($results[0]) ) {
            $userid = $results[0]->id;
        }
        return $userid;
    }

    public  function queryCargoesUserIdByName(  $name )
    {
        $sql = "SELECT  userid  FROM freightagent_user WHERE TRUE ";
        $sql .= " AND  enterpriseName = '$name'";
        $results = $this->db->fechAll($sql);
        $userid = 0;
        if( !empty($results[0]) )
        {
            $userid = $results[0]->userid;
        }
        return $userid;
    }

    public  function queryCargoesUserIdByMobile(  $mobile )
    {
        $sql = "SELECT  id  FROM users WHERE TRUE ";
        $sql .= " AND  mobile = '$mobile' ";
        $results = $this->db->fechAll($sql);
        $userid = 0;
        if( !empty($results[0]) )
        {
            $userid = $results[0]->userid;
        }
        return $userid;
    }

    public  function getCitysDocksConfig(  )
    {
        $citysDocksConfig = Config::get('xddconf.citys_docks_config');
        return $citysDocksConfig;
    }

    public  function getSubLocationToDetail($locationCityId)
    {
        $allSubLocationIDArr = array();
//        if ( strlen($locationCityId) == 2 ) {
//            $allCityLocationIDArr = DB::table('tbl_province')
//                ->where('parentid', '=', $locationCityId)
//                ->lists('codeid');
//            foreach ($allCityLocationIDArr as $cityid) {
//                $lacationIdArr = DB::table('tbl_province')
//                    ->where('parentid', '=', $cityid)
//                    ->lists('codeid');
//                $allSubLocationIDArr = array_merge($allSubLocationIDArr, $lacationIdArr);
//            }
//        } elseif (strlen($locationCityId) == 4) {
//            $allSubLocationIDArr = DB::table('tbl_province')
//                ->where('parentid', '=', $locationCityId)
//                ->lists('codeid');
//        } else {
//            return array($locationCityId);
//        }
        return $allSubLocationIDArr;
    }
}