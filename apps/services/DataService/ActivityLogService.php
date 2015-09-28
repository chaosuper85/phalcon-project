<?php namespace Services\DataService;


use  \ActivityLog;

use Library\Helper\StringHelper;
use Library\Log\Logger;
use Library\Helper\PageHelper;
use Library\Helper\QueryHelper;
use Phalcon\Mvc\User\Component;


/**
 * Class ActivityLogService
 * @package Services\DataService
 * 迁移：haibo
 */
class ActivityLogService extends Component
{

    public function insertActionLog($actionType,  // 事件类型
                                    $ip,
                                    $reamId,      // 产生事件的id
                                    $reamType,   // 产生事件的角色类型
                                    $targetReamId, // 事件目标的id
                                    $targetReamType, // 事件目标的type类型
                                    $jsonContent,   // result 之类的结果  {"result":false , "info":"something" , "userid":13 }
                                    $deviceId = "", $platform = 0, $version = "1.0")
    {

        $log = new ActivityLog();
        $log->ip = $ip;
        $log->action_type = $actionType;
        $log->reamId = $reamId;
        $log->reamType = $reamType;
        $log->targetReamId = $targetReamId;
        $log->targetReamType = $targetReamType;
        $log->platform = $platform;
        $log->version = $version;
        $log->deviceId = $deviceId;
        $log->platform = $platform;
        $log->jsonContent = $jsonContent;
        return $log->save();
    }

    /**
     * 查询user的事件
     * @param $userid
     * @param $user_mobile
     * @param $start_time
     * @param $end_time
     * @param $activity_type
     * @param PageHelper $pageHelper
     */
    public function  getActivityByUser($user_id, $mobile, $type=false, $begin_time=false, $end_time=false, $page_size=10, $page_no=false)
    {
        // todo more test by haibo
        if( empty($user_id))
        {
            if( !empty($mobile)) {
                $usr = \Users::findFirst(["mobile=?1",'bind'=>[1=>$mobile],'columns'=>'id']);
                if( $usr)
                    $user_id = $usr->id;
            }
            if( $user_id < 1) {
                Logger::info('getActivityByUser 缺少uid或mobil1e');
                return false;
            }
        }

        $ret = array('active'=>array(),'aim'=>array());
        $param = array();

        $param['conditions'][0] = "reamId=$user_id";
        $param['order'][] = 'created_at asc';
        $param['columns'] = 'id,created_at,ip,reamType,targetReamId,targetReamType,jsonContent';
        $param['model'] = '\ActivityLog';

        $type && $param['conditions'][] = "action_type=$type";
        $begin_time && $param['begin_time'] = $begin_time;
        $end_time && $param['end_time'] = $end_time;
        $page_size && $param['page_size'] = $page_size;
        $page_no && $param['page_no'] = $page_no;

        //查询用户发起的log
        $sum = QueryHelper::query($param, $ret['active']);
        Logger::info('getActivityByUser active-sum'.$sum);

        //查询用户是操作目标的log
        $param['conditions'][0] = "targetReamId=$user_id";
        $sum = QueryHelper::query($param, $ret['aim']);
        Logger::info('getActivityByUser aim-sum'.$sum);

        return $ret;
    }


    public function picVerifyStat( /*$begin_time, $end_time, $platform, $version, $page*/)
    {

        $type = $this->constant->ACTION_TYPE->PIC_CAPTCHA;
        $_REQUEST['action_type'] = $type;
        $res = QueryHelper::cond('\ActivityLog', $this->request);

        return $res;
    }

    /** 根据ip或 reamld 和  actionType 和 时间段
     * @param $platform  默认PC端
     * 查询数据记录条数
     * @return int
     */
    public function countTimesByActionTypeAndPeriod($from = '', $to = '', $actionType = 1, $ip = '127.0.0.1', $reamId = null, $jsonResult = null, $platform = 1)
    {
        $sql = "select count(*) as times  from activity_log where true";

        if (!empty($from)) {
            $sql = $sql . " and created_at >= '$from' ";
        }
        if (!empty($to)) {
            $sql = $sql . " and created_at < '$to' ";
        }
        if (!empty($reamId)) {// reamId
            $sql .= " and reamId= '$reamId' ";
        }
        if (!empty($ip)) {// 默认 iP
            $sql .= " and ip='$ip' ";
        }
        if (!empty($jsonResult)) {
            $sql .= " and jsonContent='$jsonResult' ";
        }
        if (!empty($platform)) {//todo like
            $sql .= " and platform=$platform ";
        }

        $sql = $sql . " and action_type=$actionType";
        $result = $this->db->fetchAll($sql);

        return $result[0]['times'];
    }

    /**
     * 功能:增加后台日志
     * 备注:
     * @param $action_type
     * @param $json_content
     * @param int $targetType
     * @param int $targetId
     * @return bool
     */
    public function addAdminLog($action_type,
                                $json_content,
                                $targetType = 0,
                                $targetId = 0,
                                $msg = ''
    )
    {
        $log = new \AdminLog();

        //当前登录用户
        $usr = $this->AdminUserService->getSessionUser();

        $ret = $log->save(
            array(
                'username' => $usr['username'],
                'admin_user_id' => $usr['id'],
                'ip' => $this->request->getClientAddress(),
                'action_type' => $action_type,
                'json_content' => $json_content,
                'targetReamId' => $targetId,
                'targetReamType' => $targetType,
                'log_msg' => $msg
            )
        );

        return $ret;
    }

    /**
     * 功能:返回后台日志
     * 备注:
     * @return bool
     */
    public function listAdminLog()
    {
        $param['order'] = 'created_at DESC,id ASC';
        $data = QueryHelper::cond('\AdminLog', $this->request, $param);

        Logger::info('ships sum:'.$data['data_sum']);
        return $data;
    }


    /**
     * @param int $actionType 事件类型
     * @param int $targetId   操作对象id
     * @return array
     */
    public function listByRealmIdAndTargetIdAndActionType( $actionType , $targetId = null ,$childActionType = null, $from ="", $to ="" , $limit = 15 ){
        $sql = " select id as logId,child_action_type,created_at,reamId,reamType,targetReamId,targetReamType,action_type,jsonContent from activity_log WHERE action_type =? ";
        $params[] = $actionType;
        if( isset( $targetId ) ){
            $sql.="  and targetReamId =? ";
            $params[] = $targetId;
        }
        if( isset( $childActionType ) ){
            $sql.=" and child_action_type =? ";
            $params[] = $childActionType;
        }
        if( !empty( $from ) ){
            $sql.=" and created_at >=".StringHelper::strToDate( $from );
        }
        if( !empty( $to )){
            $sql.=" and created_at <".StringHelper::strToDate( $to );
        }
        $sql.=" order by created_at desc limit $limit ";
        $res = $this->db->fetchAll( $sql,2,$params);
        return $res;
    }


    /**  17 为订单的修改记录
     *   child_action_type { }
     */
    public function  addOrderActionLog( $data = array() ){
        $user  = $this->session->get('login_user');
        if( empty( $user )){
            Logger::warn(" addOrderActionLog:{%s} get user in session is null .",var_export( $data,true));
        }else{
            $reamId   =  $user->id;
            $reamType =  $user->usertype;
            $child_action_type = $data['child_action_type'];
            $ip =   $this->request->getClientAddress();
            $targetReamId   =   $data['targetReamId'];
            $targetReamType =   $data['targetReamType'];
            $jsonContent    =   json_encode( $data['json_content'] ) ;
            $params = [ 17, $ip, $reamId,$reamType, $targetReamId, $targetReamType, $jsonContent, $child_action_type];
            $sql = "INSERT INTO activity_log( created_at, updated_at, action_type, ip, reamId, reamType, targetReamId, targetReamType , jsonContent, child_action_type) VALUES ( NOW(),NOW(),?,?,?,?,?,?,?,?)";
            $res = $this->db->execute( $sql,$params);
            if( !$res ){
                Logger::warn(" save order log true or false ? %s.", $res );
            }
        }
    }








}