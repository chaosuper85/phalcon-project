<?php

namespace Services\DataService;

use Library\Log\Logger;
use \Library\Helper\Tokener;
use InviteRecord;
use Phalcon\Mvc\User\Component;
/**
 *   邀请用户
 */
class InviteService extends  Component
{

    /*
     *
     *  1, // 搜索邀请
        2, // 链接邀请
     */
    public function sendInvite( $user_id, $inviteer_enterpriseid, $invitee_userid, $invite_type )
    {

        $inviteRecord = new InviteRecord();
        $inviteRecord->inviter_userid = $user_id;
        $inviteRecord->invitee_userid = $invitee_userid;
        $inviteRecord->invite_type = $invite_type;
        $inviteRecord->inviteer_enterpriseid = $inviteer_enterpriseid;
        $inviteRecord->status = $this->constant->INVITE_STATUS->INVITING;

        return $inviteRecord->save();
    }


    /*
     * 在登录或者注册完成后调用
     */
    public function recvInviteFromLink(  $invite_userid, $invitee_userid, $invite_token )
    {

        //自己不能邀请自己
        if( $invite_userid == $invitee_userid ){
            return false;
        }

        $enterprise_id = $this->UserService->comId( $invitee_userid );

        $enterprise = $this->EnterpriseService->getEnterprise( $enterprise_id );

        $enterprise_invite_token = $enterprise->invite_token;

        if( $enterprise_invite_token == $invite_token ){
            $inviteRecord = new InviteRecord();

            $inviteRecord->inviter_userid = $invite_userid;
            $inviteRecord->invitee_userid = $invitee_userid;
            $inviteRecord->invite_type = $this->constant->INVITE_TYPE->LINK_INVITE;
            $inviteRecord->inviteer_enterpriseid = $enterprise_id;
            $inviteRecord->status = $this->constant->INVITE_STATUS->INVITING;

            return $inviteRecord->save();
        }else{
            return false;
        }

    }

    public function confirmInvite( $invite_id, $user_id )
    {

        $conditions = "id = :id: AND invitee_userid = :invitee_userid: AND status=:status:";
        $parameters = array(
            "id" => $invite_id,
            "invitee_userid" => $user_id,
            "status"=>'1'
        );

        $invite_record = InviteRecord::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));//通过条件来查询
        $invite_record = InviteRecord::findFirst(array(
            'conditions' => $conditions,
            'bind' => $parameters
        ));

        $invite_record->status = 2;
        $invite_record->save();


        return true;
    }

    public function refuseInvite( $invite_id, $user_id )
    {

        $conditions = "id = :id: AND invitee_userid = :invitee_userid: AND status=:status:";

        $parameters = array(
            "id" => $invite_id,
            "invitee_userid" => $user_id,
            "status"=>'1'
        );

        $invite_record = InviteRecord::findFirst(array(
            $conditions,
            "bind" => $parameters
        ));

        $invite_record->status = 3;
        $invite_record->save();


        return true;
    }

    /**
     *   (我是邀请人) 获取我邀请的人  ->用户信息
     */
    public function getInvitedUsersInfo( $userId, $status = null ,$type = null ){
        $params = array( $userId );
        $sql ="  select u.id,u.avatarpicurl,u.enterprise_licence,u.mobile,u.real_name,u.enterprise_licence,u.enterpriseid,r.id as inviteRecordId ,r.status as inviteStatus from users u
                inner join invite_record r on u.id = r.invitee_userid where r.inviter_userid = ? " ;
        if( isset( $status ) ){ //
            $sql.= "  and r.status =? ";
            $params[] = $status;
        }
        if( isset( $type) ){
           $sql.="  and r.invite_type = ? ";
            $params[] = $type;
        }
        $sql.= " order by r.created_at desc ";
        return $this->db->query($sql,$params)->fetchAll();
    }

    /**
     *  (我是被邀请人) 查询 我收到的  邀请人的信息
     */
    public function getMyInvitationInfo( $userId,$status = null ,$type = null ){
        $params = array( $userId );
        $sql ="  select u.id,u.avatarpicurl,u.enterprise_licence,u.mobile,u.real_name,u.enterprise_licence,u.enterpriseid,r.id as inviteRecordId ,r.status as inviteStatus from users u
                inner join invite_record r on u.id = r.inviter_userid  where r.invitee_userid = ? " ;
        if( isset( $status ) ){ //
            $sql.= "  and r.status =? ";
            $params[] = $status;
        }
        if( isset( $type) ){
            $sql.="  and r.invite_type = ? ";
            $params[] = $type;
        }
        $sql.= " order by r.created_at desc ";
        return $this->db->query($sql,$params)->fetchAll();
    }

    public function updateById( $inviteRecordId, $params =array() ){
        $record = InviteRecord::findFirst(" id = '$inviteRecordId'");
        if( empty($record) || empty($params) ){
            return false;
        }
        $params['updated_at'] = date('Y-m-d h:i:s',time());
        $res = $record->update($params);
        Logger::info(" update InviteRecord  params:".var_export($params,true)."  message:".var_export($record->getMessages(),true));
        return $res;
    }

    public function getById( $inviteRecordId){
        return  $record = InviteRecord::findFirst(" id = '$inviteRecordId'");
    }

    /**
     *  管理员 同意添加员工
     */
    public  function  confirmAddEmp($operatorUserId,$ip,$enterpriseId,$inviteRecordId){
        $this->db->begin();
        $inviteRecord = $this->getById( $inviteRecordId );
        if( empty ($inviteRecordId) ){
            return false; //  // 邀请人 没有同意
        }else{
            try{
                // 更新 被邀请人的 企业
                $this->UserService->updateById(["enterpriseid" => $enterpriseId],$inviteRecord->invitee_userid);
                // 更新 邀请记录的状态
                $status = $this->constant->INVITE_STATUS->AGREED; // 管理员 同意
                $this->updateById( $inviteRecordId, array("status" => $status));
                Logger::info("confirmAddEmp :ip:".$ip." enterprisrId:".$enterpriseId." inviteRecord:".$inviteRecordId. " success .");
            }catch (\Exception $e){
                $this->db->rollback();
                Logger::warn(" confirmAddEmp failure: recordId ".$inviteRecordId."  error:".var_export($e->getMessage(),true));
                return false;
            }
            $this->db->commit();
            $this->ActivityLogService->insertActionLog($this->constant->ACTION_TYPE->ADMIN_ADD_EMP,$ip,$operatorUserId,1,$inviteRecord->inviter_userid,2,"ADD_EMP_SUCCESS");
            return true;
        }
    }

    /**
     * @param $invitorUid 邀请人 userId
     * @param $receiverUid 被邀请的 userId
     * @param $invitorComId 邀请人 企业Id
     * @param $type 邀请的类型
     */
    public function create( $invitorUid,$receiverUid,$invitorComId,$type){
        $record = new InviteRecord();
        $statu  = $this->constant->INVITE_STATUS->INVITING;// 邀请中
        $res = $record->save(array(
                'inviter_userid'        => $invitorUid,
                'invitee_userid'        => $receiverUid,
                'inviteer_enterpriseid' => $invitorComId,
                'invite_type'           => $type,
                'status'                => $statu,
                'updated_at'            => date('Y-m-d h:i:s',time()),
                'created_at'            => date('Y-m-d h:i:s',time())
            )
        );
        Logger::info(" create InviteRecord Msg :".var_export($record->getMessages(),true));
        return  $res? $record : $res ;
    }



}