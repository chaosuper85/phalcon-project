<?php

use Phalcon\Http\Response;
use Library\Log\Logger;

/**
 * @RoutePrefix("/company")
 */
class CompanyController extends ControllerBase
{

    /**
     * @Get("/")
     */
     public function  indexAction()
     {
         // 管理员 =》公司信息
         $user   = $this->session->get('login_user');
         $enterprise  = $this->EnterpriseService->isAdmin( $user->user_id );
         if( empty($enterprise) ){ // 普通员工 ->收到的 企业邀请
              $type   = $this->constant->INVITE_TYPE->SEARCH_INVITE; // 搜索邀请
              $status = $this->constant->INVITE_STATUS->INVITING;    // 邀请中
              $data   = $this->InviteService->getMyInvitationInfo( $user->id, $status ,$type );
         }else{  // 管理员
             $adminApply  = $this->ApplyAdminService->getByUserId($user->id);
             $data = array(
                 "enterprise_name" => $enterprise->enterprise_name,
                 "licenceNum"      => $adminApply->enterprise_licence,
                 "admin_name"      => $user->ownerName,
                 "admin_idCard"    => $adminApply->ownerIdentityCardId,
                 "admin_mobile"    => $user->mobile
             );
         }
         $this->data['data'] =  $data;
         return $this->view->pick("user/page/company")->setVar("data", $this->data);
     }
}