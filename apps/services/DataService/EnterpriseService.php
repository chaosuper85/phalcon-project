<?php

namespace Services\DataService;

use  Library\Helper\IdGenerator;
use Library\Helper\QueryHelper;
use  Library\Log\Logger;
use  TbEnterprise;
use  Users;

use Phalcon\Mvc\User\Component;

//auth haibo

class EnterpriseService extends  Component
{

    //创建企业/公司
    public function create($name, $type, $admin_uid, $cityId, $createDate, $status=1)
    {
        $newCom = TbEnterprise::findFirst( ["enterprise_name = ?1 AND city_id = ?2 ",'bind'=>[1=>$name,2=>$cityId]]);
        if( $newCom) {
            Logger::info('enterprise-create :已有此企业:'.$name);

            $newCom->enterprise_name = $name;
            $newCom->status = $status;
            $newCom->enterprise_type = $type;
            $newCom->admin_userid = $admin_uid;
            $newCom->established_date = date('Y-m-d h:i:s',strtotime( $createDate ));
            $newCom->city_id = $cityId ;
        }else {
            $newCom = new TbEnterprise();
            $newCom->enterprise_name = $name;
            $newCom->enterprise_type = $type;
            $newCom->admin_userid = $admin_uid;
            $newCom->invite_token = IdGenerator::guid();
            $newCom->status = 1;                            //暂时未定
            $newCom->established_date = date('Y-m-d h:i:s',strtotime( $createDate ));
            $newCom->city_id = $cityId ;
        }
        
        Logger::info('create enterprise :'.var_export($newCom->getMessages(),true));
        return $newCom->save() ? $newCom: false;
    }

    //得到公司所有员工
    public function staff($com_id)
    {
        $com = \TbEnterprise::findFirst("id='$com_id'");
        if( !$com)
            return false;

        $users = \Users::find("enterpriseid='$com_id'");

        return $users->toArray();
    }

    public function getEnterprise( $com_id )
    {
        $com = TbEnterprise::findFirst("enterprise_id='$com_id'");
        if( !$com)
            return false;

        return $com;
    }


    public function saveEnterpriseToken( $enterpriseId, $inviteToken )
    {
        $com = TbEnterprise::findFirst("enterprise_id='$enterpriseId'");
        if( !$com)
            return false;

        $com->invite_token = $inviteToken;
        $com->save();
        return true;
    }

    //得到疑似公司员工
    public function imagineStaff(&$ret, $com_id)
    {
        $com = TbEnterprise::findFirst("id='$com_id'");
        if( !$com)
            return false;

        $cond = QueryHelper::cond('\Users',$this->request,false,false);

        if( $cond['conditions'])
            $cond['conditions'] .= "AND unverify_enterprisename='$com->enterprise_name' AND ( enterpriseid='')";
        else
            $cond['conditions'] = "unverify_enterprisename='$com->enterprise_name' AND ( enterpriseid='')";

        $data = Users::find($cond);
        //返回分页信息

        $ret['data'] = $data->toArray();
        $ret['total_count'] = $data->count();
        $ret['total_page'] = intval($data->count()/$ret['page_size']) + 1;

        return $data->count();
    }

    //审核一个申诉
    public function appealAudit($id, $isPass=true, $msg='')
    {
        $ret = array(
            'error_code'=>'1',
            'error_msg' => $this->admin_cfg['tickets']['msg_invalid'],
            'data'=> array(),
        );

        $ticket = \TbTickets::findFirst($id);
        if( !$ticket)
            return $ret;

        if($isPass) {
            if( $ticket->ticket_status != $this->admin_cfg['tickets']['status_start'])
                return $ret;

            //工单update
            $ticket->settle_time = date("Y-m-d H:i:s");
            $ticket->ticket_status = $this->admin_cfg['tickets']['status_end'];
            $ticket->ticket_result = $this->admin_cfg['tickets']['result_pass'];
            $ticket->ticket_result_info = $this->admin_cfg['tickets']['msg_pass'];

            if($ticket->update()) {
                $ret['error_msg'] = $this->admin_cfg['tickets']['msg_pass'];
                $ret['error_code'] = 0;
            }
        } else{
            if( $ticket->ticket_status != $this->admin_cfg['tickets']['status_start'])
                return $ret;

            //工单update
            $ticket->settle_time = date("Y-m-d H:i:s");
            $ticket->ticket_status = $this->admin_cfg['tickets']['status_end'];
            $ticket->ticket_result = $this->admin_cfg['tickets']['result_reject'];
            $ticket->ticket_result_info = '申诉被驳回: '.$msg;

            if($ticket->update()) {
                $ret['error_msg'] = $this->admin_cfg['tickets']['msg_reject'];
                $ret['error_code'] = 0;
            }
        }

        //增加后台日志
//        $who  = $this->AdminUserService->getSessionUser();
//        $how = $this->constant['ACTION_TYPE']['APPEAL_AUDIT'];
//        $where  = $ticket->target_id;
//        $what = $who.' '.$how.' '.$where;
//        $this->ActivityLogService->addAdminLog($who, $how, $where,$what);

        return $ret;
    }

    /** 判断是否是 企业管理员
     *  是 =》 返回 enterprise ;
     *  否则 =》 false
     */
    public function  isAdmin( $userId )
    {
        $enterprise = TbEnterprise::findFirst(" admin_userid ='$userId'");
        if( empty($enterprise) ){ // 不是
            return false;
        }else{
            return $enterprise;
        }
    }

    public function  getByUserId( $userId ){
       return TbEnterprise::query($this->di)
            ->innerJoin("Users","e.id = Users.enterpriseid","e")
            ->where(" Users.user_id =?1",array( 1=> $userId))->execute()->getFirst();
    }



}