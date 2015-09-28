<?php   namespace Services\DataService;
/**
 * Created by PhpStorm.
 * auth: haibo wanghui
 * Date: 15/7/23
 * Time: 下午7:53
 */

use \Phalcon\DiInterface;
use FreightagentUser;
use Library\Log\Logger;
use Library\Helper\StatusHelper;
use Phalcon\Mvc\User\Component;

/** 货代
 * Class AgentService
 * @package Modules\services\DataService
 */
class AgentService extends Component
{
    public  function  create( $userId, $com_name=''){
        $agent = new FreightagentUser();
        $agent->userid = $userId;
        //$agent->enterpriseName = $com_name;
        $agent->status = 1;
        $agent->audit_status =1;
        $agent->enable = 1;
        $agent->avartar_idcard_pic = "";
        $agent->idcard_back_pic = "";
        $agent->cargo_pic = "";
        $result = $agent->save();
        Logger::info("create agent message :".var_export($agent->toArray(),true));
        return $result?$agent:false;
    }

    private function statusCount()
    {
        $data = \FreightagentUser::find([
            'columns' => 'count(id)as sum,audit_status',
            'group' => 'audit_status',
        ])->toArray();

        $ret = array();
        foreach($data as $k=>$v) {
            $ret[$v['audit_status']] = $v['sum'];
        }

        return $ret;
    }

    public function queryCargoes( $begin_time, $end_time, $platform, $version, $com_name, $name, $mobile, $audit_status, $status, $page=1, $page_size=10)
    {
        empty($page) && $page=1 ;
        empty($page_size) && $page_size=10;
        $page_start = intval( ($page-1)*$page_size);

        $sql = "SELECT freightagent_user.userid,freightagent_user.id, freightagent_user.status,freightagent_user.audit_status, users.unverify_enterprisename,".
            "users.telephone_number, users.mobile, users.username, users.created_at as regist_time, users.regist_platform, users.regist_version".
            " FROM users INNER JOIN freightagent_user ON  users.id = freightagent_user.userid  WHERE TRUE ";

        $bind = array();
        $count = $this->queryAgentCond($sql, $bind, $begin_time, $end_time, $platform, $version, $com_name, $name, $mobile, $audit_status, $status);

        if( $page>0)
            $sql .= " LIMIT $page_start,$page_size";

        $data = $this->db->fetchAll($sql,2,$bind);

        //把int转化成中文
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
            'data' => $data,
            'data_head'=> $this->statusCount(),
            'data_sum' => $count,
            'page_no' => $page,
            'page_sum' => $total_page,
            'paras' => array(   //前台比较懒=.=
                'audit_status'   => $audit_status,
                'status'   => $status,
                'agent_name' => $com_name,
                'name'     => $name,
                'platform' => $platform,
                'version'  => $version,
                'mobile'   => $mobile,
                'begin_time' => $begin_time,
                'end_time' => $end_time,
            ),
        );

        return $result;
    }

    private  function queryAgentCond(&$sql, &$bind, $begin_time, $end_time, $platform, $version, $com_name, $name, $mobile, $audit_status, $status)
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

        if( !empty($com_name)) {
            $where .= " AND users.unverify_enterprisename LIKE ? ";
            $bind[] = '%'.$com_name.'%';
        }

        if( !empty($name)) {
            $where .= " AND users.username LIKE ? ";
            $bind[] = '%'.$name.'%';
        }

        if( !empty($mobile)) {
            $where .= " AND users.mobile LIKE ? ";
            $bind[] = '%'.$mobile.'%';
        }

        if( !empty($audit_status) )
        {
            $audit_status = intval($audit_status);
            $where .= " AND freightagent_user.audit_status = $audit_status";
        }

        if( !empty($status) )
        {
            $status = intval($status);
            $where .= " AND freightagent_user.status = $status";
        }

        $sql .= $where;
        $sql .= " ORDER BY freightagent_user.updated_at DESC";

        $sql_count = "SELECT COUNT(freightagent_user.id) as sum FROM users INNER JOIN freightagent_user ON  users.id = freightagent_user.userid  WHERE TRUE ";
        $count = $this->db->fetchAll($sql_count.$where, 2, $bind);

        return  isset($count[0]['sum']) ? intval($count[0]['sum']):0;
    }


    /**
     *
     */
    public function  getByUserId( $userId ){
        $sql = 'select id,userid,status,avartar_idcard_pic,idcard_back_pic,cargo_pic from FreightagentUser WHERE userid = ?1';
        $query = $this->modelsManager->createQuery($sql);
        $agent = $query->execute( array('1' => $userId))->getFirst();
        return $agent ;
    }

    public function  updateByUserId($params = array(),$userId){
        $agent = FreightagentUser::findFirst("userid = '$userId' ");
        if( empty($agent) || empty($params) ){
            return false;
        }
        return $agent->update($params);
    }

    /**
     * 功能: 用户审核
     * @param $id 货代id
     * @param $isPass
     * @param string $msg
     * @return array
     */
    public  function  audit($id, $isPass, $msg='')
    {
        $ret = false;
        $agent = \FreightagentUser::findFirst( intval($id));
        if( $agent)
            $status = new StatusHelper($agent);

        if( isset($status) && $status->auditing)
        {
            //通过或驳回
            $ret = $status->shift('auditing', $isPass?2:0.5);
            $ret = $ret && $status->saveModel();

            if($ret) {
                // sms.text init
                $contact = \Users::findFirst( [$agent->userid,'columns'=>'mobile,contactName']);// not 某些字段
                $mobile = $contact->mobile;
                $msg = $isPass ? ':您的货代已通过认证！':':您的货代未通过认证:'.$msg;
                $content = $contact->contactName.$msg;
                $content = iconv('UTF-8', 'GBK', $content);

                // sms send
                $ret_code = $this->SmsService->sendSMS(array($mobile), $content); //not
                if ($ret_code == '0') {
                    Logger::info('send sms to ' . $mobile. ' success ');
                } else {
                    Logger::error('send sms to ' . $mobile . ' fail ');
                }

            }else {
                Logger::info('audit-agent update status fail: '.var_export($agent->getMessages(),true));
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
     * 功能: 修改货代状态
     * @param $ids 货代id array()
     * @param $op 'lock'/'unlock'/'delete'/'undelete'
     * @return $this|bool
     */
    public function setStatus($ids, $op)
    {
        if( !is_array($ids))
            $ids = array($ids);

        $status = $this->constant->ACCOUNT_STATUS;
        $okSum = 0;
        foreach($ids as $v)
        {
            $usr = \FreightagentUser::findFirst( intval($v));
            $user_id = \Users::findFirst( [$usr->userid,'columns'=>'id']);
            if (!$usr || !$user_id)
                return false;

            $isOk = false; //更新状态是否成功

            //修改用户状态
            switch ($op) {
                case 'lock':
                    if ($usr->status == $status->DISABLE)
                        break;

                    $usr->status = $status->DISABLE;

                    $loginSuccess = $this->constant->LOGIN_RECORD->LOGIN_SUCCESS;
                    $loginKicked = $this->constant->LOGIN_RECORD->KICKED;
                    $this->LoginRecordService->updateLoginRecord($user_id->id, $loginSuccess, $loginKicked);
                break;

                case 'unlock':
                    if ($usr->status == $status->ENABLE)
                        break;

                    $usr->status = $status->ENABLE;
                break;

                case 'delete':
                    if ($usr->status == $status->DELETED)
                        break;

                    $usr->status = $status->DELETED;

                    $loginSuccess = $this->constant->LOGIN_RECORD->LOGIN_SUCCESS;
                    $loginKicked = $this->constant->LOGIN_RECORD->KICKED;
                    $this->LoginRecordService->updateLoginRecord($usr->id, $loginSuccess, $loginKicked);
                break;
            }

            $isOk = $usr->update();
            if( !$isOk) {
                $log = '修改状态无效:已经处在此状态. err:';
                $log .= var_export($usr->getMessages(), true);
                Logger::error('agentService-setStatus:' . $log);
            }else {
                $okSum += 1;
            }

            if( !$isOk)
                continue;
        }

        return $okSum;
    }




}
