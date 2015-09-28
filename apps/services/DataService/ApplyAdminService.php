<?php
namespace Services\DataService;

use ApplyAdmin;
use Library\Helper\Status;
use Library\Helper\StatusHelper;
use Library\Helper\StringHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use TbEnterprise;

/**
 *  认证 相关
 *  15-7-28
 *  haibo/wanghui
 */
class ApplyAdminService extends  Component
{


    /** 申请管理员 更新公司信息
     * @return bool
     */
    public function applyAdmin($userId, $enterpriseName, $licenceNumber, $licencePic,$cityCode,$provinceId,$buildDate,$mobile,$type, &$result =array()){
        $user   = \Users::findFirst("id = $userId");
        if( empty($user) ){
            $result['error_msg']  = "申请失败，用户未登录。";
            $result['error_code'] = 200001;
            return false;
         }
        // 更新 用户信息
        $user->enterprise_licence       = $licenceNumber;
        $user->unverify_enterprisename  = $enterpriseName;
        if( empty( $user->telephone_number) ){
            $user->telephone_number        = $mobile;//   区号-座机-分机
        }

        $admin = $this->getByUserId($userId);
        if( empty($admin) ){ // 创建
            $admin         = new ApplyAdmin();
            $admin->status = $this->constant->USER_STATUS->AUDITING;
        }else{ // 已经创建，检查状态 ，拒绝的可以再次申请
            if( $admin->status == $this->constant->USER_STATUS->AUDIT_REJECT ){
                $admin->status = $this->constant->USER_STATUS->AUDITING;
            }
        }
        // 更新企业信息
        $enterprise = TbEnterprise::findFirst("admin_userid=$userId");
        if( !empty( $enterprise ) ){
            $enterprise->enterprise_name = $enterpriseName;
            $enterprise->city_id = $cityCode;
            $enterprise->established_date =$buildDate;
            $enterprise->update();
        }
        $this->updateStatus( $userId, $user->usertype , $enterpriseName);
        if( $user->usertype != $type ){
            Logger::info("user:%s,update accountType:old->%s,new->%s",$userId,$user->usertype,$type);
        }
        $user->update();
        $admin->ownerIdentityCardId = $mobile; // 区号-座机-分机
        $admin->account_type        = $type;
        $admin->enterprise_name    = $enterpriseName;
        $admin->enterprise_licence = ''.$licenceNumber;
        $admin->cargo_pic          = $licencePic;
        $admin->established_date   = StringHelper::strToDate( $buildDate ,"Y-m-d");
        $admin->city_id            = $cityCode;
        $admin->official_letter    = $provinceId; // 存储 省id
        $admin->ownerName          = $user->real_name;
        $admin->userid = $userId;
        $res = $admin->save();
        $result['error_code'] = 0;
        $result['error_msg' ] = "申请成功，等待审核 ";
        Logger::info(" applyAdmin message:".var_export($admin->getMessages(),true));
        return $res? $admin:false;
    }


    // 企业管理员申请列表
    // com_id:企业uuid
    public  function  applies($com_id)
    {
        $com = \TbEnterprise::findFirst("id = '$com_id'");

        if( !$com)
            return false;

        $auditing = $this->admin_cfg['comadmin_audit_status']['auditing'];
        $data = \ApplyAdmin::find("enterprise_name = '$com->enterprise_name' AND status=$auditing");

        return $data->toArray();
    }

    // 企业管理员审核-通过
    // 已经迁移至LGAutditSerivce
    public  function  passAdminApply($usrid)
    {
        $ret = array(
            'error_msg'=>'成功',
            'error_code'=>0,
            'data'=>array(),
        );

        $usr = \Users::findFirst("id='$usrid'");
        if( !$usr) {
            $ret['error_code'] = 1;
            $ret['error_msg'] = $this->admin_cfg['err_usr']['no_usr'];
            return $ret;
        }

        try {
            $this->db->begin();

            //企业表要创建一条记录
            $com = $this->EnterpriseService->create($usr->enterprise_name, $usr->usertype, $usrid);
            if (!$com) {
                $ret['error_msg'] = $this->admin_cfg['err_work']['new_com'];
                $ret['error_code'] = 2;
                return $ret;
            }
            //修改用户所属企业
            if (!$this->UserService->setEnterprise($usrid, $com->id)) {
                $ret['error_msg'] = $this->admin_cfg['err_usr']['enterprise'];
                $ret['error_code'] = 3;
                return $ret;
            }
            //通过公司管理员审核要修改管理员申请表
            if (!$this->setStatus($usrid)) {
                $ret['error_msg'] = $this->cfg['err_work']['admin_pass'];
                $ret['error_code'] = 4;
                return $ret;
            }
            //货代/车队用户升级到企业管理员
            if ($usr->usertype == $this->admin_cfg['usrtype_carteam']) {
                $car_team = \CarTeamUser::findFirst("userid='$usrid'");

                $status = new StatusHelper($car_team);

                $isOk = $status->del('audit_pass')
                                ->add('com_admin')
                                ->saveModel();
            } else {
                $agent = \FreightagentUser::findFirst("userid='$usrid'");

                $status = new StatusHelper($agent);

                $isOk = $status->del('audit_pass')
                                ->add('com_admin')
                                ->saveModel();
            }
            if (!$isOk) {
                $ret['error_msg'] = $this->admin_cfg['err_usr']['status'];
                $ret['error_code'] = 5;
                return $ret;
            }

            $this->db->commit();
        }catch (\Exception $e) {
            $this->db->rollback();
            Logger::error(var_export($e->getMessage(),true));
            //$this->flash->error($e->getMessage());
        }

        Logger::info('create enterprise :'.var_export($ret,true));
        return $ret;
    }


    //企业管理员审核-驳回
    public  function  rejectAdminApply($usrid,$msg)
    {
        //todo 拒绝理由

        $ret = $this->setStatus($usrid,false);

        return $ret;
    }

    private  function  setStatus($usrid, $isPass=true)
    {
        $ret = false;
        $auditing = $this->admin_cfg->comadmin_audit_status->auditing;
        $auditRes = $isPass ? $this->admin_cfg->comadmin_audit_status->pass:$this->admin_cfg->comadmin_audit_status->reject;
        $usr      = \Users::findFirst("id='$usrid'");

        if ($usr) {
            $rec = \ApplyAdmin::findFirst("userid='$usrid' AND status=$auditing");

            if($rec) {  //修改状态
                $rec->status = $auditRes;

                if( $rec->update())
                    $ret = true;
                Logger::info('create enterprise :'.var_export($rec->getMessages(),true));
            }
        }

        return  $ret? $rec->enterprise_name:false;
    }

    public function  getByUserId($userId){
       return ApplyAdmin::findFirst("userid =$userId");
    }


    /**
     * 'carteam' =>1 ,
       'freight_agent' =>2 ,
     */
    private  function updateStatus( $userId, $type ,$comName){
        switch( $type ){
            case 1:
                $carteam = \CarTeamUser::findFirst("userid=$userId");
                $carteam->audit_status = 3;
                $carteam->teamName = $comName;
                $carteam->update();
                break;
            case 2:
                $agent  =\FreightagentUser::findFirst("userid=$userId");
                $agent->audit_status = 3;
                $agent->update();
                break;
            default;
                break;
        }
    }

    /**
     *  判断 企业执照号码 是否存在
     */
    public function  checkLicenceExist( $licenceCode , $userId ){
        $exist = true ;
        $sql =" select count(*) as times from apply_admin WHERE enterprise_licence = ? AND userid != ? ";
        try{
            $res = $this->db->fetchOne( $sql,2,[ $licenceCode, $userId ]);
            Logger::info("liecence:{%s} count:{%s}",$licenceCode,$res['times']);
            $exist = $res['times'] > 0 ; // 已经存在
        }catch (\Exception $e){
            Logger::warn("licenceCodeExistAction error:{%s}".$e->getMessage());
        }
        return $exist;
    }


}