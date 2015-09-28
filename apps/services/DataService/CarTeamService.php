<?php  namespace Services\DataService;
/**
 * Created by PhpStorm.
 * auth : haibo
 * Date: 15/7/22
 * Time: 下午7:43
 */


use CarTeamUser;
use Library\Log\Logger;
use Library\Helper\StatusHelper;
use Phalcon\Mvc\User\Component;

/** 车队服务
 * Class CarTeamService
 * @package Modules\services\DataService
 */
class CarTeamService extends Component
{


    public function create($userId, $team_name=''){
        $carTem = new CarTeamUser();
        $carTem->userid = $userId;
        $carTem->teamName = $team_name;
        $carTem->teamPic = "";
        $carTem->status = 1;
        $carTem->audit_status = 1;
        $carTem->ownerName = "";
        $carTem->ownerIdentityCardId = "";
        $carTem->idcard_pic = "";
        $result = $carTem->save();
        Logger::info("create carTeam:",var_export($carTem->toArray(),true));
        return $result?$carTem:false;
    }

    public function getByUserId( $userId ){
        $sql = 'select id,userid,teamName,teamPic,ownerName,ownerIdentityCardId,status,idcard_pic  from CarTeamUser WHERE userid = ?1';
        $query = $this->modelsManager->createQuery($sql);
        $agent = $query->execute( array('1' => $userId))->getFirst();
        return $agent ;
    }

    public function updateByUserId($params,$userId){
        $carTeam = CarTeamUser::findFirst("userid ='$userId' ");
        if( empty($carTeam) || empty( $params)){
            return false;
        }

        $params['updated_at'] = date('Y-m-d h:i:s',time());
        $res = $carTeam->update($params);
        Logger::info(" update Carteam meg:" .var_export($carTeam->getMessages(),true));
        return $res ;
    }

    /**
     * 功能: 审核：通过、驳回
     * @param $id 车队id
     * @param $isPass
     * @param string $msg
     * @return array
     */
    public  function  audit($id, $isPass, $msg='')
    {
        $ret = false;
        $carteam = \CarTeamUser::findFirst( $id);
        if ($carteam)
            $status = new StatusHelper($carteam);

        if (isset($status) && $status->auditing)
        {
            //通过或驳回
            $ret = $status->shift('auditing', $isPass ? 2 : 0.5);
            $ret = $ret && $status->saveModel();

            if ($ret) {
                // sms.text init
                $contact = \Users::findFirst([$carteam->userid,'columns'=>'mobile,contactName']);// not 某些字段
                $mobile = $contact->mobile;
                $msg = $isPass ? ':您的车队已通过认证！' : ':您的车队未通过认证:' . $msg;
                $content = $contact->contactName . $msg;
                $content = iconv('UTF-8', 'GBK', $content);

                // sms send
                $ret_code = $this->SmsService->sendSMS(array($mobile), $content); //not
                if ($ret_code == '0') {
                    Logger::info('send sms to ' . $mobile . ' success ');
                } else {
                    Logger::error('send sms to ' . $mobile . ' fail ');
                }

            } else {
                Logger::info('audit-carteam: ' . var_export($carteam->getMessages(), true));
            }
        }

        $result = array(
            'error_code'=> $ret ? 0:3,
            'error_msg'=>  $ret ? '完成':'不是待审核用户或系统出错',
            'data'=> array(),
        );

        return $ret;
    }


    /**
     * 功能: 各状态数据总数，给前台用
     * @return mixed
     */
    private function statusCount()
    {
        $data = \CarTeamUser::find([
            'columns' => 'count(id)as sum,audit_status',
            'group' => 'audit_status',
        ])->toArray();

        $ret = array();
        foreach($data as $k=>$v) {
            $ret[$v['audit_status']] = $v['sum'];
        }

        return $ret;
    }

    public function queryCarTeams( $begin_time, $end_time, $platform, $version, $name, $team_name, $mobile, $audit_status,$status, $page=1, $page_size=10 )
    {
        empty($page) && $page=1 ;
        empty($page_size) && $page_size=10;
        $page_start = ($page-1)*$page_size;

        $userid = 0;
        if( !empty($name) )
        {
            $userid = \Users::findFirst( array('username LIKE ?1 ', 'bind'=>[1=>'%'.$name.'%'], 'columns'=>'id'));

        }elseif( !empty($mobile) ){
            $userid = \Users::findFirst( array('mobile LIKE ?1 ', 'bind'=>[1=>'%'.$mobile.'%'], 'columns'=>'id'));
        }
        $userid = $userid ? $userid->id:0;

        $sql = "SELECT users.id as uid, car_team_user.id, users.created_at as regist_time, users.mobile, users.regist_platform, users.regist_version, users.username,"
            ."users.telephone_number, users.unverify_enterprisename as teamName, car_team_user.ownerName, car_team_user.status, car_team_user.audit_status"
            ." FROM users INNER JOIN car_team_user ON users.id = car_team_user.userid WHERE TRUE ";

        $bind = array();
        $count = $this->queryCarTeamCond($sql, $bind, $begin_time, $end_time, $platform, $version, $audit_status, $status, $userid, $team_name);

        $sql .= " ORDER BY car_team_user.updated_at DESC";
        if( $page > 0)
            $sql.=" LIMIT $page_start,$page_size";

        $data = $this->db->fetchAll($sql,2,$bind);

        //把code转化成中文 todo
        $hashPlatForm = $this->constant['platform'];

        if( !empty($data)) {
            $hashPlatForm = $this->constant->PLATFORM_ENUM;
            foreach($data as $k=>&$v) {
                if( isset($v['regist_platform']))
                    $v['regist_platform'] = $hashPlatForm[$v['regist_platform']];
//            $v['status'] = StatusHelper::numToWord('ACCOUNT', intval($v['status']));
//            $v['audit_status'] = StatusHelper::numToWord('USER', intval($v['audit_status']));
            }
        }

        $total_page = intval( ceil( $count/$page_size));
        $result = array(
            'page_no' => $page,
            'page_sum' => $total_page,
            'data_sum' => $count,
            'data' => $data,
            'data_head' => $this->statusCount(),
            'paras' => array(   //前台比较懒=.=
                'status' => $status,
                'audit_status' => $audit_status,
                'team_name'=> $team_name,
                'name'   => $name,
                'platform' => $platform,
                'version'  => $version,
                'mobile' => $mobile,
                'begin_time' => $begin_time,
                'end_time' => $end_time,
            ),
        );

        return $result;
    }


    private  function queryCarTeamCond(&$sql, &$bind, $begin_time, $end_time, $platform, $version,$audit_status, $status, $userid, $team_name)
    {
        //todo 绑定参数

        $where = '';
        if( !empty($begin_time) )
        {
            $where .= " AND users.created_at > ? ";
            $bind[] = $begin_time;
        }

        if( !empty($end_time) )
        {
            $where .= " AND users.created_at < ? ";
            $bind[] = $end_time;
        }

        if( !empty($platform) )
        {
            $where .= " AND users.regist_platform = ? ";
            $bind[] = $platform;
        }

        if( !empty($version) )
        {
            $where .= " AND users.regist_version = ? ";
            $bind[] = $version;
        }

        if( !empty($userid) )
        {
            $userid = intval($userid);
            $where .= " AND users.id = $userid ";
        }

        if( !empty($team_name))
        {
            $sql .= " AND car_team_user.teamName = ? ";
            $bind[] = $team_name;
        }

        if( !empty($audit_status) )
        {
            $audit_status = intval($audit_status);
//            $status = $status % 10;
//            $status = 1<<($status-1);
//            $where .= " AND car_team_user.status & $status ";
            $where .= " AND car_team_user.audit_status = $audit_status ";
        }

        if( !empty($status) )
        {
            $status = intval($status);
//            $status = $status % 10;
//            $status = 1<<($status-1);
//            $where .= " AND car_team_user.status & $status ";
            $where .= " AND car_team_user.status = $status ";
        }

        if( 0) {
            $where .= " AND users.status <> $this->admin_cfg['usr_deleted'] ";
        }

        $sql .= $where;

        $sql_count = "SELECT COUNT(car_team_user.id) as sum FROM users INNER JOIN car_team_user ON users.id = car_team_user.userid WHERE TRUE ";
        $count = $this->db->fetchAll($sql_count.$where, 2, $bind);

        return  isset($count[0]['sum']) ? intval($count[0]['sum']):0;
    }

    /**
     * 功能: 修改车队状态
     * @param $ids 车队id
     * @param $op 'lock'/'unlock'/'delete'/'undelete'
     * @return $this|bool
     */
    public function setStatus($ids, $op)
    {
        if( !is_array($ids))
            $ids = array($ids);

        $status = $this->constant->ACCOUNT_STATUS;
        $okSum = 0;
        foreach($ids as $v) //todo优化 批量
        {
            $usr = \CarTeamUser::findFirst( intval($v));
            $user_id = \Users::findFirst( [$usr->userid,'columns'=>'id']);
            if( !$usr || !$user_id)
                return false;

            $isOk1 = false; //更新状态是否成功
            $isOk2 = false; //订单处理是否成功

            //修改用户状态，同时报价也要下架或上架
            switch ($op) {
                case 'lock':
                    if ($usr->status == $status->DISABLE)
                        break;

                    $usr->status = $status->DISABLE;

                    $loginSuccess = $this->constant->LOGIN_RECORD->LOGIN_SUCCESS;
                    $loginKicked = $this->constant->LOGIN_RECORD->KICKED;
                    $usr->status = $status->DISABLE;
                    $this->LoginRecordService->updateLoginRecord($user_id->id, $loginSuccess, $loginKicked);
                break;

                case 'unlock':
                    if ($usr->status == $status->ENABLE)
                        break;

                    $usr->status = $status->ENABLE;

                    //$isOk2 = $isOk1 && $this->serveBid( $id);
                break;

                case 'delete':
                    if ($usr->status == $status->DELETED)
                        break;

                    $usr->status = $status->DELETED;

                    //$isOk2 = $isOk1 && $this->dropBid( $id);
                break;

//                case 'undelete':
//                    if (!$status->deleted)
//                        break;
//
//                    $usr->status -= $status->deleted;
//                    $isOk1 = $usr->update();
//
//                    //$isOk2 = $isOk1 && $this->serveBid($id);
//                    break;
            }

            $isOk1 = $usr->update();
            if( !$isOk1) {
                $log_db = '修改状态无效:已经处在此状态. err:';
                $log_db .= var_export($usr->getMessages(), true);
                Logger::error('carteamService-setStatus:' . $log_db);
            }else {
                $okSum += 1;
            }

            if( !$isOk1)
                continue;
        }

        return $okSum;
    }


    //冻结相关。。todo
    public  function   dropBid( $userid)
    {
        return true;
        //TODO
        //1,锁定禁止登录,在登录的地方加
        //2,下架所有该车队报价,在显示报价的地方过滤掉下架的字段.在此处将所有报价下架.
        $carTeamStatusAuditSuccess = $this->constant['CAR_TEAM_STATUS']['AUDIT_SUCCESS'];
        $carTeamStatusLocked = $this->constant['CAR_TEAM_STATUS']['LOCKED'];

        $ret = 0;

        //只有审核通过的才能锁定
        $sql = "SELECT  status  FROM car_team_user WHERE userid = '$userid' ";
        $result = $this->db->fetchAll($sql);

        if( empty($result[0]) || $result[0]->status != $carTeamStatusAuditSuccess ){
            $ret = -1;
        }else{
            //锁定
            $sql = "UPDATE car_team_user SET status = ".$carTeamStatusLocked." WHERE userid = '$userid' ";
            $this->db->query( $sql);
        }

        if( $ret == 0 )
        {
            $result = array(
                'error_code'=>'0',
                'error_msg'=>'',
                'data'=> array(),
            );
        }else{
            $result = array(
                'error_code'=> $ret,
                'error_msg'=> '车队锁定失败',
                'data'=> array(),
            );
        }

        return $result;
    }

    //上架
    public  function serveBid( $userid)
    {

    }

    /**
     *  所有车队的list carteamId , carteamName , ownerName
     *  审核通过
     */
    public function  listAll(){
        $carTeamType = $this->constant->usertype->carteam;
        $status = $this->constant->USER_STATUS->AUDIT_PASS;
        $sql = "select u.id as carteamId,unverify_enterprisename as enterprisename ,real_name as ownerName ,cu.teamName as carteamName from users u INNER  JOIN car_team_user cu ON cu.userid = u.id  WHERE u.usertype=$carTeamType AND cu.audit_status =$status ";
        $result = $this->db->fetchAll( $sql );
        if( !empty( $result) ){
           foreach( $result as  $key => $carTeam ){
               $value =  trim( $carTeam['carteamName'] );
               $enterprise = $carTeam['enterprisename'];
               if( empty( $value ) ) {
                   $result[ $key ]['carteamName'] = $enterprise;
               }
           }
        }
        return $result;
    }

    //通过userid 获取 teamid
    public function getTeamid($userid){
        $sql = 'SELECT `id` FROM `car_team_user` WHERE `userid` = ?';//暂定一个车队一个用户
        $arr = $this->db->fetchAll($sql, 2, [$userid]);
        $result = 0;
        if(!empty($arr))
            $result = $arr[0]['id'];
        return $result;
    }

    //通过teamid 获取 司机信息
    public function getDriverUserid($teamid, $limit, $pageSize){
        $sql = "SELECT `userid`, `driver_name`, `car_number`, `drive_number` FROM `tb_driver` WHERE `team_id` = ? LIMIT $pageSize OFFSET $limit";
        return $this->db->fetchAll($sql, 2, [$teamid]);
    }

    //获取 司机 页数
    public function getPageCount($teamid, $pageSize){
        $sql = 'SELECT `id` FROM `tb_driver` WHERE `team_id` = ?';
        $arr = $this->db->fetchAll($sql, 2, [$teamid]);
        $count = count($arr);
        $shang = (int)($count / $pageSize);
        $yushu = $count % $pageSize;
        return $yushu == 0 ? $shang : $shang + 1;
    }

    //通过司机userid获取司机手机号
    public function getDriverNumber($userid){
        $sql = 'SELECT `mobile` FROM `users` WHERE `id` = ?';
        $result = '';
        $arr = $this->db->fetchAll($sql, 2, [$userid]);
        if(!empty($arr))
            $result = $arr[0]['mobile'];
        return $result;
    }
    //车辆管理
    public function getCarteamMsg($userid, $page = 0){
        if(empty($page))
            $page = 1;
        $pageSize = $this->di->get('order_config')->car_manage_pageSize;
        $limit = ($page - 1) * $pageSize;
        $teamid = $this->getTeamid($userid);
        $result = array();
        if($teamid != 0){
            $driverArr = $this->getDriverUserid($teamid, $limit, $pageSize);
            foreach($driverArr as $key => $value){
                $result[$key] = $value;
                $driverUserid = $value['userid'];
                $result[$key]['contactNumber'] = $this->getDriverNumber($driverUserid);
            }
        }
        $res['pageCount'] = $this->getPageCount($teamid, $pageSize);
        $res['result'] = $result;
        return $res;
    }
}