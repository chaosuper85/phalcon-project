<?php

use Library\Helper\StringHelper;
use Library\Log\Logger;

/**
 * @RoutePrefix("/account")
 */
class AccountController extends ControllerBase
{

    /**
     * @Get("/")
     */
    public function indexAction()
    {
        $user = $this->session->get('login_user');
        $data = $this->UserService->getDetails($user->id);
        $this->data['data'] = $data;
        return $this->view->pick('account/page/index')->setVar('data', $this->data);
    }


    /**
     * @Get("/management")
     */
    public function managementAction()
    {

        $data = array();

        $this->data['data'] = $data;
        return $this->view->pick('user/page/account_manager_apply')->setVar('data', $this->data);
    }

    /** 认证信息
     *
     * @Get("/confirmInfo")
     */
    public function confirmInfoAction()
    {
        $data = array();

        $this->data['data'] = $data;
        return $this->view->pick('user/page/account_manager_confirm')->setVar('data', $this->data);
    }

    /** 个人信息
     *
     * @Get("/personalInfo")
     */
    public function personalInfoAction()
    {

        $data = array();
        $user = $this->getUser();
        $data['personalInfo'] = $this->UserService->getDetails($user->id);
        $this->data['data'] = $data;
        return $this->view->pick('user/page/account_info')->setVar('data', $this->data);
    }

    /** 企业信息(申请管理员信息)
     *
     * @Get("/enterpriseInfo")
     */
    public function enterpriseInfoAction()
    {
        $data = array();
        $user = $this->getUser();
        $enterprise = array();
        try {
            $applyAdmin = $this->ApplyAdminService->getByUserId($user->id);
            if (!empty($applyAdmin)) {
                $cityName = $this->CityService->getFullNameById($applyAdmin->city_id);
                $enterprise = array(
                    "enterpriseName" => $applyAdmin->enterprise_name,
                    "licenceNumber" => $applyAdmin->enterprise_licence,
                    "licencePic" => $applyAdmin->cargo_pic,
                    'fileType' => StringHelper::getExtension(  $applyAdmin->cargo_pic ),
                    "cityCode" => $applyAdmin->city_id,
                    "provinceId" =>  $applyAdmin->official_letter,
                    "contactMobile" => $applyAdmin->ownerIdentityCardId,// 联系人手机
                    "contactMobile_city" => "",
                    "contactMobile_number" => "",
                    "contactMobile_fenji" => "",
                    "type" => $applyAdmin->account_type,
                    "status" => $applyAdmin->status,
                    "cityName" => $cityName,
                    "buildDate" => StringHelper::strToDate($applyAdmin->established_date, "Y-m-d")
                );
                $phone = $applyAdmin->ownerIdentityCardId;
                if( !empty( $phone )){
                    $phones = explode("-",$phone);
                    if( count( $phones ) == 3 ){
                        $enterprise['contactMobile_city']   =   $phones[0];
                        $enterprise['contactMobile_number'] =   $phones[1];
                        $enterprise['contactMobile_fenji']  =  trim($phones[2]);
                    }else if( count( $phones ) == 2 ){
                        $enterprise['contactMobile_city']   =   $phones[0];
                        $enterprise['contactMobile_number'] =   $phones[1];
                    }
                }
            }
        } catch (\Exception $e) {
            Logger::warn("enterpriseInfoAction error:%s", $e->getMessage());
            $this->forwardError(array('error_code' => 1000001, "error_msg" => "网络错误！"));
            return;
        }
        $data["companyInfo"] = array("enterprise" => $enterprise);
        $this->data['data'] = $data;
        return $this->view->pick('user/page/account_company')->setVar('data', $this->data);

    }


    /** 账户安全
     *
     * @Get("/accountSecurity")
     */
    public function accountSecurityAction()
    {
        return $this->view->pick('user/page/account_security')->setVar('data', $this->data);
    }

    /**
     * @Get("/accountManager")
     */
    public function accountManagerAction()
    {

        $data = array();

        $this->data['data'] = $data;
        return $this->view->pick('user/page/account_manager')->setVar('data', $this->data);
    }


    /**
     * @Get("/applyManager")
     */
    public function applyManagerAction()
    {
        $user = $this->session->get('login_user');
        try {
            switch ($user->usertype) {
                case 2: //freight_agent 货代
                    $agent = $this->AgentService->getByUserId($user->id);
                    $data = array(
                        'enterpriseName' => $user->unverify_enterprisename,
                        'licenceNumber' => $user->enterprise_licence,
                        'licencePic' => $agent->cargo_pic,
                        'idCard' => "",
                        'realName' => "",
                    );

                    break;
                case 1:// carteam 车队
                    $carTeam = $this->CarTeamService->getByUserId($user->id);
                    $data = array(
                        'enterpriseName' => $carTeam->teamName,
                        'licenceNumber' => $user->enterprise_licence,
                        'licencePic' => $carTeam->teamPic,
                        'idCard' => $carTeam->ownerIdentityCardId,
                        'realName' => $carTeam->ownerName,
                    );
            }
            $data['mobile'] = $user->mobile;
        } catch (\Exception $e) {
            Logger::warn("enterpriseInfoAction error:%s", $e->getMessage());
        }

        $this->data['data'] = $data;
        return $this->view->pick('user/page/apply_manager')->setVar('data', $this->data);
    }


}
