<?php
/**
 * Created by PhpStorm.
 * User: 后台
 * Date: 15/9/7
 * Time: 下午3:43
 * Auth: haibo
 */

namespace Services\LogicService;

use Library\Helper\StatusHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;



class LGAuditService extends Component
{
    const ERR_SYS1 = '信息缺失，请联系管理员';
    const ERR_AUDIT_1 = '账户当前不能被做此审核';
    const ERR_AUDIT_2 = '修改账户数据失败，请稍后再试或联系管理员';
    const ERR_AUDIT_3 = '用户审核-申请表状态修改失败';
    const ERR_AUDIT_4 = '用户审核-创建公司失败';
    const MSG_AUDIT_PASS = '您的账户已通过认证！';
    const MSG_AUDIT_REJECT = '您的账户未通过认证。';
    const ERR_SYS2 = '更换账户类型失败，请联系管理员';


    /**
     * 功能: 审核：通过、驳回
     * @param $id 货代id
     * @param $isPass
     * @param string $msg
     * @return string 空为正确 不为空则表示错误信息
     */
    public  function  agentAudit($id, $isPass, $msg='')
    {
        //todo 增加拒绝理由字段

        //数据准备
        $status = $this->constant->USER_STATUS;
        $agent = \FreightagentUser::findFirst( $id);
        $usr = \Users::findFirst($agent->userid);
        if( !$usr || !$agent) {
            Logger::warn('agentAudit : 未找到users/agent表用户记录');
            return  self::ERR_SYS1;
        }

        //能否被审核
        if( $isPass) {
            if( $status->REGISTER==$agent->audit_status || $status->AUDIT_PASS==$agent->audit_status) {
                Logger::warn('agentAudit : 用户不在待审核状态');
                return  self::ERR_AUDIT_1;
            }
        }else {
            if( $status->REGISTER==$agent->audit_status || $status->AUDIT_REJECT==$agent->audit_status) {
                Logger::warn('agentAudit : 用户不在待审核状态');
                return  self::ERR_AUDIT_1;
            }
        }

        try{
            $this->db->begin();

            //踢出用户
            if( !$isPass && $agent->audit_status == $status->AUDIT_PASS) {
                $loginSuccess = $this->constant->LOGIN_RECORD->LOGIN_SUCCESS;
                $loginKicked = $this->constant->LOGIN_RECORD->KICKED;
                $this->LoginRecordService->updateLoginRecord($usr->id, $loginSuccess, $loginKicked);
            }

            //改货代账户的状态
            $agent->audit_status = $isPass ? $status->AUDIT_PASS:$status->AUDIT_REJECT;
            $isOk = $agent->update();
            if( !$isOk) {
                Logger::error('agentAudit-updateStatus: '.var_export($agent->getMessages(),true));
                return  self::ERR_AUDIT_2;
            }

            //改申请表的状态
            $apply_admin = $this->setApplyAdmin( $usr->id, $isPass, $msg);
            if( !$apply_admin) {
                return  self::ERR_AUDIT_3;
            }

            //如果需要则修改帐号类型------------这需求真是坑啊!
            if( 0 || $isPass && $apply_admin->account_type!=$this->constant->usertype->freight_agent) {
                $isOk = $this->changeUsrType($agent, $usr);
                if( !$isOk) {
                    return  self::ERR_SYS2;
                }
            }

            //审核通过要创建公司
            $isOk=$this->createCompany( $usr, $apply_admin, $isPass);
            if( !$isOk) {
                return  self::ERR_AUDIT_4;
            }
            $this->db->commit();

            //发送含审核结果的短信
            $isOk = $this->sendAuditSms( $usr, $isPass, $msg);

        }catch (\Exception $e) {
            $this->db->rollback();
            Logger::error('agentAudit :'.$e->getMessage());
            return  '请稍后再试，或联系管理员'.$e->getMessage();
        }

        Logger::info('agentAudit-成功');
        return '';
    }



    /**
     * 功能: 审核：通过、驳回
     * @param $id 车队id
     * @param $isPass
     * @param string $msg
     * @return string 空为正确 不为空则表示错误信息
     */
    public  function  carteamAudit($id, $isPass, $msg='')
    {
        //数据准备
        $status = $this->constant->USER_STATUS;
        $carteam = \CarTeamUser::findFirst( $id);
        $usr = \Users::findFirst($carteam->userid);
        if( !$usr || !$carteam) {
            Logger::warn('agentAudit : 未找到users/agent表用户记录');
            return  self::ERR_SYS1;
        }

        //能否被审核
        if( $isPass) {
            if( $status->REGISTER==$carteam->audit_status || $status->AUDIT_PASS==$carteam->audit_status) {
                Logger::warn('agentAudit : 用户不在待审核状态');
                return  self::ERR_AUDIT_1;
            }
        }else {
            if( $status->REGISTER==$carteam->audit_status || $status->AUDIT_REJECT==$carteam->audit_status) {
                Logger::warn('agentAudit : 用户不在待审核状态');
                return  self::ERR_AUDIT_1;
            }
        }

        try{
            $this->db->begin();

            //踢出用户
            if( !$isPass && $carteam->audit_status == $status->AUDIT_PASS) {
                $loginSuccess = $this->constant->LOGIN_RECORD->LOGIN_SUCCESS;
                $loginKicked = $this->constant->LOGIN_RECORD->KICKED;
                $this->LoginRecordService->updateLoginRecord($usr->id, $loginSuccess, $loginKicked);
            }

            //改车队账户的状态
            $carteam->audit_status = $isPass ? $status->AUDIT_PASS:$status->AUDIT_REJECT;
            $isOk = $carteam->update();
            if( !$isOk) {
                Logger::error('carTeamAudit-updateStatus: '.var_export($carteam->getMessages(),true));
                return  self::ERR_AUDIT_2;
            }

            //改申请表的状态
            $apply_admin = $this->setApplyAdmin( $usr->id, $isPass, $msg);
            if( !$apply_admin) {
                return  self::ERR_AUDIT_3;
            }

            //如果需要则修改帐号类型------------这需求真是坑啊!
            if(0 && $isPass && $apply_admin->account_type!=$this->constant->usertype->carteam) {
                $isOk = $this->changeUsrType($carteam, $usr);
                if( !$isOk) {
                    return  self::ERR_SYS2;
                }
            }

            //审核通过要创建公司
            $isOk=$this->createCompany($usr, $apply_admin, $isPass);
            if( !$isOk) {
                return  self::ERR_AUDIT_4;
            }
            $this->db->commit();

            //发送含审核结果的短信
            $isOk = $this->sendAuditSms($usr, $isPass, $msg);

        }catch (\Exception $e) {
            $this->db->rollback();
            Logger::error('carTeamAudit :'.$e->getMessage());
            return  '请稍后再试，或联系管理员';
        }

        Logger::info('carTeamAudit-成功');
        return '';
    }


    //通过审核-审核表
    private function setApplyAdmin($user_id, $isPass, $msg='')
    {
        $isOk = false;
        $auditRes = $isPass ? $this->constant->COM_STATUS->AUDIT_PASS:$this->constant->COM_STATUS->AUDIT_REJECT;

        $apply = \ApplyAdmin::findFirst("userid=$user_id");
        if($apply) {
            $apply->status = $auditRes; //修改审核状态
            $apply->remark = $msg;
            if( $apply->update())
                $isOk = $apply;
            Logger::info('setApplyAdmin :'.var_export($apply->getMessages(),true));
        }else {
            Logger::warn('setApplyAdmin :无此申请记录');
        }

        return $isOk;
    }

    /**
     * 功能:如果审核类型和用户类型不同，通过审核后要修改用户状态
     * 备注:
     * @param $com_usr  car_team_usr 或 freight_usr 表对象
     * @param $usr  users表对象
     * @return string
     */
    private function changeUsrType($com_usr, $usr)
    {
        //delete
        $isOk = $com_usr->delete();

        if($usr->usertype == $this->constant->usertype->freight_agent)
        {
            //mobify users
            $usr->usertype = $this->constant->usertype->carteam;
            $isOk && $isOk = $usr->update();

            //insert carteam
            $isOk && $carteam = $this->CarTeamService->create($usr->id);
            if( !$isOk || !$carteam) {
                Logger::error('更改用户类型失败 :'.var_export($carteam->getMessages(),true));
                return false;
            }

            //改为审核通过的状态
            $carteam->audit_status = $this->constant->USER_STATUS->AUDIT_PASS;
            $isOk = $carteam->update();
        }
        else if($usr->usertype == $this->constant->usertype->carteam)
        {
            //mobify users
            $usr->usertype = $this->constant->usertype->freight_agent;
            $isOk && $isOk = $usr->update();

            //insert freight
            $isOk && $agent = $this->AgentService->create($usr->id);
            if( !$isOk || !$agent) {
                Logger::error('更改用户类型失败 :'.var_export($agent->getMessages(),true));
                return false;
            }

            //改为审核通过的状态
            $agent->audit_status = $this->constant->USER_STATUS->AUDIT_PASS;
            $isOk = $agent->update();
        }

        return $isOk;
    }


    private function createCompany($model_usr, $apply_admin, $isPass)
    {
        $status = $isPass ? $this->constant->COM_STATUS->AUDIT_PASS:$this->constant->COM_STATUS->AUDIT_REJECT;
        $com = $this->EnterpriseService->create($apply_admin->enterprise_name, $apply_admin->account_type, $model_usr->id,$apply_admin->city_id,$apply_admin->established_date, $status);
        if( !$com) {
            Logger::error('createCompany error'.var_export($com->getMessages(),true));
            return false;
        }

        $model_usr->enterpriseid = $com->id;
        $isOk = $model_usr->update();

        return $isOk;
    }

    private function sendAuditSms($model_usr, $isPass, $msg)
    {
        // sms.text init
        $msg_audit = $isPass ? self::MSG_AUDIT_PASS : self::MSG_AUDIT_REJECT;
        $content = $model_usr->username.','.$msg_audit.$msg;
        $content = iconv('UTF-8', 'GBK', $content);

        // sms send
        $mobile = empty($model_usr->contactNumber) ? $model_usr->mobile:$model_usr->contactNumber;
        $ret_code = $this->SmsService->sendSMS(array($mobile), $content); //not
        if ($ret_code == '0') {
            Logger::info('send sms to ' . $mobile . ' success ');
            return true;
        } else {
            Logger::error('send sms to ' . $mobile . ' fail ');
            return false;
        }

    }

    /**
     * 功能:获取车队或货代的审核详情
     * 备注:
     * @param $team_id 车队或货代id
     * @param $type 1-车队 2-货代
     * @return array|bool
     */
    public function auditInfo($team_id, $type)
    {
        $guest = intval($this->constant->USER_STATUS->REGISTER);
        if($type == 1)
        {
            $carteam = \CarTeamUser::findFirst([
                "id = $team_id AND audit_status <> $guest",
                'columns' => 'id,userid,teamPic,idcard_pic,ownerIdentityCardId,ownerName',
            ]);

            if( $carteam)
            {
                $data = array(
                    'team' => $carteam,
                    'user' => $this->getUserInfo($carteam->userid),
                    'com'  => $this->getComInfo($carteam->userid),
                );
                return $data;
            }

            Logger::warn('carteamInfo 审核、用户信息缺失');
        }
        else
        {
            $agent = \FreightagentUser::findFirst([
                "id = $team_id AND audit_status <> $guest",  //todo orm的坑
                'columns' => 'id,userid,avartar_idcard_pic,idcard_back_pic,cargo_pic',
            ]);

            if( $agent)
            {
                $data = array(
                    'team' => $agent,
                    'user' => $this->getUserInfo($agent->userid),
                    'com'  => $this->getComInfo($agent->userid),
                );
                return $data;
            }

            Logger::warn('getAgentInfo 审核、用户信息缺失');
        }

        return false;
    }



    /**
     * @param $user_id
     * @return \ApplyAdmin|bool
     */

    //审核的公司信息
    private function getComInfo($user_id)
    {
        $com = \ApplyAdmin::findFirst([
            "userid=$user_id",
            'columns' => 'enterprise_name,cargo_pic,official_letter,ownerName,account_type,city_id,established_date,enterprise_licence',
        ]);

        if( !$com)
            return false;

        $cfg = $this->order_config->CREATE_TYPE;
        if( isset($cfg[$com->status]))
            $com->status = $cfg[$com->status];

        $cfg = $this->constant->usertype;
        if( isset($cfg[$com->account_type]))
            $com->account_type = $cfg[$com->account_type];

        $com->city_id = $this->CityService->getFullNameById($com->city_id);

        return $com;
    }

    //审核的个人信息
    private function getUserInfo($user_id)
    {
        $user = \Users::findFirst([
            $user_id,
            'columns' => 'username,mobile,telephone_number,contactName,contactNumber,real_name,avatarpicurl',
        ]);

        if( $user)
            return $user->toArray();
        else
            return false;
    }
}