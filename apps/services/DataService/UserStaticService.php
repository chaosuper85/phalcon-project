<?php   namespace Services\DataService;

use Phalcon\Mvc\User\Component;
use Library\Helper\QueryHelper;

/**
 * Class UserStaticService
 * @package Services\DataService
 * 用户登录 、状态、统计信息
 * 迁移：haibo  未完成
 */
class UserStaticService extends  Component
{

    public function queryUserStat( $begin_time, $end_time, $platform, $version, $user_type )
    {
        $status = $this->constant->CarTeamUser_Status;

        $regist_count = $this->queryStatusCount($status->guest,$user_type);
        $login_count = $this->queryLoginCount( $begin_time, $end_time, $platform, $version, $user_type  );
        $audit_success_count = $this->queryStatusCount($status->audit_pass,$user_type);
        $to_audit_count = $this->queryStatusCount($status->auditing,$user_type);
        $audit_fail_count = $this->queryStatusCount($status->audit_reject,$user_type);
        $lock_count = $this->queryStatusCount($status->locked,$user_type);

        $results = array(
            'begin_time' => $begin_time,
            'end_time' => $end_time,
            'platform' => $platform,
            'version' => $version,
            'user_type' => $user_type,

            'data' => array(
                'regist_count' => $regist_count,
                'login_count' => $login_count,
                'audit_success_count' => $audit_success_count,
                'to_audit_count' => $to_audit_count,
                'audit_fail_count' => $audit_fail_count,
                'lock_count' => $lock_count,
            ),
        );

        return $results;
    }

    /*
     * 查询登录量
    */
    public function queryLoginCount( $begin_time, $end_time, $platform, $version, $user_type )
    {
        $count = 0;
        $login = $this->constant->ACTION_TYPE->LOGIN;
        $sql = "SELECT COUNT(*) as count FROM activity_log WHERE  action_type = $login ";

        if( !empty($begin_time) ) {
            $sql .= " AND created_at > '$begin_time' ";
        }

        if( !empty($end_time) ) {
            $sql .= " AND created_at < '$end_time' ";
        }

        if( !empty($platform) ) {
            $sql .= " AND platform = '$platform' ";
        }

        if( !empty($version) ) {
            $sql .= " AND version = '$version' ";
        }

        if( isset($user_type) && $user_type != '' ) {
            $sql .= " AND reamType = '$user_type' ";
        }

        $results = $this->db->fetchOne($sql);
        return $results['count'];
    }


    /*
     * 查询某状态的用户量
    */
    public  function queryStatusCount($status,$user_type)
    {
        $count = 0;

        if($user_type == $this->constant->usertype->carteam)
        {
            $cond = QueryHelper::cond('\CarTeamUser',$this->request,false,false);
            if( empty($cond['conditions']))
                $cond['conditions'] = "status & $status";
            else
                $cond['contitions'] = " AND status & $status";
            $count += \CarTeamUser::count($cond);
        }
        else if($user_type == $this->constant->usertype->freight_agent)
        {
            $cond = QueryHelper::cond('\FreightagentUser', $this->request,false,false);
            if( empty($cond['conditions']))
                $cond['conditions'] = "status & $status";
            else
                $cond['contitions'] = " AND status & $status";
            $count += \FreightagentUser::count($cond);
        }

        return $count;
    }


    // /*
    //  * 查询审核未通过量
    // */
    // public function queryAuditFailCount( $begin_time, $end_time, $platform, $version, $user_type )
    // {
    //     $count = 0;

    //     if(  isset($user_type) || $user_type == $this->constant->usertype->carteam  )
    //     {
    //         $audit_reject = $this->constant->CarTeamUser_Status->audit_reject;
    //         $sql = "SELECT COUNT(*) AS count FROM users INNER JOIN car_team ON users.id=car_team.id WHERE car_team.status & $audit_reject ";

    //         $arr_params = array(  );

    //         if( !empty($begin_time) ) {
    //             $sql .= " AND users.created_at > '$begin_time' ";
    //         }

    //         if( !empty($end_time) ) {
    //             $sql .= " AND users.created_at < '$end_time' ";
    //         }

    //         if( !empty($platform) ) {
    //             $sql .= " AND users.regist_platform = '$platform' ";
    //         }

    //         if( !empty($version) ) {
    //             $sql .= " AND users.regist_version = '$version' ";
    //         }

    //         $results = $this->db->query($sql);
    //         var_dump($results);die;
    //         $count  = $results[0]->count;
    //     }

    //     return $count;
    // }


    // /*
    // * 查询待审核量
    // */
    // public function queryToAuditCount( $begin_time, $end_time, $platform, $version, $user_type )
    // {
    //     $count = 0;

    //     if(  isset($user_type) && $user_type == $this->constant->usertype->carteam  )
    //     {

    //         $auditing = $this->constant->CarTeamUser_Status->auditing;
    //         $sql = "SELECT COUNT(*) AS count FROM users INNER JOIN car_team ON users.id=car_team.id WHERE car_team.status & $auditing ";

    //         $arr_params = array(  );

    //         if( !empty($begin_time) )
    //         {
    //             $sql .= " AND users.created_at > ? ";
    //             $arr_params[] = $begin_time;
    //         }

    //         if( !empty($end_time) )
    //         {
    //             $sql .= " AND users.created_at < ? ";
    //             $arr_params[] = $end_time;
    //         }

    //         if( !empty($platform) )
    //         {
    //             $sql .= " AND users.regist_platform = ? ";
    //             $arr_params[] = $platform;
    //         }

    //         if( !empty($version) )
    //         {
    //             $sql .= " AND users.regist_version = ? ";
    //             $arr_params[] = $version;
    //         }

    //         $results = DB::select( $sql, $arr_params );
    //         $count  = $results[0]->count;
    //     }

    //     return $count;
    // }


    // /*
    //  * 查询审核通过量
    //  */
    // public static function queryAuditSuccessCount( $begin_time, $end_time, $platform, $version, $user_type )
    // {
    //     $count = 0;

    //     if( isset($user_type) && $user_type == Config::get('xddconf.usertype_carteam') )
    //     {
    //         $sql = "SELECT COUNT(*) AS count FROM users INNER JOIN car_team ON users.id=car_team.id WHERE car_team.status='4' ";

    //         $arr_params = array(  );

    //         if( !empty($begin_time) )
    //         {
    //             $sql .= " AND users.created_at > ? ";
    //             $arr_params[] = $begin_time;
    //         }

    //         if( !empty($end_time) )
    //         {
    //             $sql .= " AND users.created_at < ? ";
    //             $arr_params[] = $end_time;
    //         }

    //         if( !empty($platform) )
    //         {
    //             $sql .= " AND users.regist_platform = ? ";
    //             $arr_params[] = $platform;
    //         }

    //         if( !empty($version) )
    //         {
    //             $sql .= " AND users.regist_version = ? ";
    //             $arr_params[] = $version;
    //         }

    //         $results = DB::select( $sql, $arr_params );
    //         $count  = $results[0]->count;
    //     }

    //     return $count;
    // }


    // /*
    // * 查询注册量
    // */
    // public static function queryRegistCount( $begin_time, $end_time, $platform, $version, $user_type )
    // {
    //     $sql = "SELECT COUNT(*) as count FROM users WHERE  TRUE ";

    //     $arr_params = array(  );

    //     if( !empty($begin_time) )
    //     {
    //         $sql .= " AND created_at > ? ";
    //         $arr_params[] = $begin_time;
    //     }

    //     if( !empty($end_time) )
    //     {
    //         $sql .= " AND created_at < ? ";
    //         $arr_params[] = $end_time;
    //     }

    //     if( !empty($platform) )
    //     {
    //         $sql .= " AND regist_platform = ? ";
    //         $arr_params[] = $platform;
    //     }

    //     if( !empty($version) )
    //     {
    //         $sql .= " AND regist_version = ? ";
    //         $arr_params[] = $version;
    //     }

    //     if( isset($user_type) && $user_type != '' )
    //     {
    //         $sql .= " AND usertype = ? ";
    //         $arr_params[] = $user_type;
    //     }

    //     $results = DB::select( $sql, $arr_params );
    //     $regist_count = $results[0]->count;

    //     return $regist_count;
    // }

}