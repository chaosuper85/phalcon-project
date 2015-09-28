<?php

use Library\Helper\StringHelper;
use Library\Log\Logger;
use Library\Helper\PageHelper;
use Phalcon\Http\Client\Exception;

/**
 * @RoutePrefix("/api/employee")
 */
class ApiEmployeeController extends ApiControllerBase
{


    /**
     * @Route("/findEmployer", methods={"GET", "POST"})
     */
    public function  findEmployerAction()
    {
        $admin   = $this->session->get('login_user');
        $mobileOrName = $this->request->getQuery('mobileOrName');
        $result = array(
            "error_code" => 1,//
            "error_msg"  => ""
        );
        if( empty($mobileOrName) ){
            return $this->response->setJsonContent($result);
        }
        try {
            $user = $this->UserService->getByNameOrMobile($mobileOrName);
            if (!empty($user)) {
                $userInfo = array(
                    "username" => $user->username,
                    "user_id" => $user->id,
                    "mobile" => StringHelper::markStr($user->mobile, 3, 4),
                    "enterpriseId" => $user->enterpriseid,
                    "avatarpicurl" => $user->avatarpicurl,
                );
                $result['error_msg'] = "查找成功";
                $result['data'] = array(
                    "user" => $userInfo
                );
            }
        }catch (\Exception $e){
            Logger::warn("findEmployerAction : ".var_export($e->getMessage(),true));
            $result = array(
                "error_code" => '100003',//
                "error_msg"  => "网络异常"
            );
        }
        return $this->response->setJsonContent($result);
    }

    /**
     * @Route("/auditEmployer", methods={"GET", "POST"})
     */
    public  function  auditEmployerAction(){
         $admin   = $this->session->get('login_user');//  todo 默认是管理员的 企业Id
         $result  = array(
             'error_code' => 1,
             'error_msg'  => ""
         );
         $select   = $this->request->getQuery("select");   // 选择
         $inviteId = $this->request->getQuery("inviteId"); // 邀请的Id
         // 员工加入 企业
         $record = $this->InviteService->getById($inviteId);
        try{
            switch( strtoupper($select) ){
                case 'CONFIRM': // 同意
                    $res = $this->InviteService->confirmAddEmp($admin->id,$this->request->getClientAddress(),$admin->enterpriseid,$inviteId);
                    break;
                case  'REJECT': // 拒绝
                    $res = $this->InviteService->refuseInvite( $inviteId, $record->invitee_userid );
                    break;
                default:
                    break;
            }
        }catch (Exception $e){
            Logger::info(" auditEmployerAction:".var_export($e->getMessage(),true));
        }

         if( $res ){
             $result['error_code'] = 0;
             $result['error_msg' ] = " 操作成功 ";
         }else{
             $result['error_code'] = 1;
             $result['error_msg' ] = " 操作失败";
         }
         return $this->response->setJsonContent($result);
    }

    /**
     *
     * @Route("/checkRecommended", methods={"GET", "POST"})
     */
    public function checkRecommendedAction(){
        $admin   = $this->session->get('login_user');//   默认是管理员的 企业Id todo
        $comId   = $admin->enterpriseid;
        $select  = $this->request->getQuery("select");   // 选择
        $userId  = $this->request->getQuery("userId"); // userId
        $content = $this->request->getQuery("reason"); // 申述的原因
        try{
            switch( strtoupper($select) ){
                case 'CONFIRM': // 纳入员工
                    $res = $this->UserService->setEnterprise($userId, $comId);
                    break;
                case 'APPEAL':  // 申述 todo
                     // create Ticket
                    break;
                default:
                    break;
            }
            if( $res ){
                $result = array(
                    "error_code" => 0,//
                    "error_msg"  => " 操作成功"
                );
            }else{
                $result['error_code'] = 1;
                $result['error_msg'] = "操作失败";
            }
        } catch (\Exception $e){
            Logger::warn("checkRecommendedAction : ".var_export($e->getMessage(),true));
            $result = array(
                "error_code" => '100003',//
                "error_msg"  => "网络异常"
            );
        }
        return $this->response->setJsonContent($result);
    }

    /** 分配员工加入 群组
     *
     * @Route("/assignEmployer", methods={"GET", "POST"})
     */
    public function  assignEmployerAction(){
        $result = array(
            "error_code" => 1, //
            "error_msg"  => " 操作失败"
        );
        $enterpriseid   = $this->session->get('login_user')->enterpriseid;//   默认是管理员的 企业Id todo
        $userId  = $this->request->getQuery("userId");
        $groupId = $this->request->getQuery("groupId");
        $comGroup = \EnterpriseGroup::findFirst(" id =$groupId  and enterprise_id ='$enterpriseid' ");
        try {
            if (!empty($comGroup)) {
                $res = $this->UserService->assignUser($userId, $groupId);
            }
            if( $res ){
                $result['error_code'] = 0;
                $result['error_msg']  = "分配成功";
            }
        }catch (\Exception $e){
            Logger::warn("assignEmployerAction : ".var_export($e->getMessage(),true));
            $result = array(
                "error_code" => '100003',//
                "error_msg"  => "网络异常"
            );
        }
        return $this->response->setJsonContent($result);
    }

    /** 推荐联系人
     *
     * @Route("/recommendContact", methods={"GET", "POST"})
     */
    public function recommendContactAction(){
        $result         = array("error_code" => 1, "error_msg" => "", "data" => array());
        $pageNo         = $this->request->getQuery("page");
        $user           = $this->session->get('login_user');// todo 当前用户的 公司员工列表
        $enterpriseId   = $user->enterpriseid;
        $pageHelper     = new PageHelper( $pageNo );
        try{
            $pageHelper           = $this->EmployeeService->getRecommendEmpsWithPage( $enterpriseId ,$pageHelper);
            $result['error_code'] = 0;
            $result['data']       = array( "pageInfo" => $pageHelper->toArray() );
        }catch (Exception $e){
            Logger::info(" recommendContactAction :".var_export($e->getMessage(),true));
        }
        return $this->response->setJsonContent( $result );
    }

    /** 已发出的邀请
     *
     * @Route("/sentInvites", methods={"GET", "POST"})
     */
    public function  sentInvitesAction(){
        $result         = array("error_code" => 1, "error_msg" => "", "data" => array());
        $pageNo         = $this->request->getQuery("page");
        $invitingStatus = $this->constant->INVITE_STATUS->INVITING;    // 邀请中
        $searchType     = $this->constant->INVITE_TYPE->SEARCH_INVITE; // 搜索邀请
        $user           = $this->session->get('login_user');// todo 当前用户的 公司员工列表
        $enterpriseId   = $user->enterpriseid;
        $pageHelper     = new PageHelper( $pageNo );
        try{
            $pageHelper           = $this->EmployeeService->getInvitedEmpsWithPage( $enterpriseId , $searchType ,$invitingStatus,$pageHelper);
            $result['error_code'] = 0;
            $result['data']       = array( "pageInfo" => $pageHelper->toArray() );
        }catch (Exception $e){
            Logger::info(" sentInvitesAction :".var_export($e->getMessage(),true));
        }
        return $this->response->setJsonContent( $result );
    }

    /**  待审核员工（通过URL 邀请过来的）
     *
     * @Route("/checkingEmps", methods={"GET", "POST"})
     */
    public function checkingEmpsAction(){
        $result         = array("error_code" => 1, "error_msg" => "", "data" => array());
        $pageNo         = $this->request->getQuery("page");
        $invitingStatus = $this->constant->INVITE_STATUS->INVITING; // 邀请中
        $urlType        = $this->constant->INVITE_TYPE->LINK_INVITE; //
        $user           = $this->session->get('login_user');// todo 当前用户的 公司员工列表
        $enterpriseId   = $user->enterpriseid;
        $pageHelper     = new PageHelper( $pageNo );
        try{
            $pageHelper     = $this->EmployeeService->getInvitedEmpsWithPage( $enterpriseId , $urlType ,$invitingStatus,$pageHelper);
            $result['error_code'] = 0;
            $result['data']       = array( "pageInfo" => $pageHelper->toArray() );
        }catch (Exception $e){
            Logger::info("checkingEmpsAction:".var_export($e->getMessage(),true));
        }
        return $this->response->setJsonContent( $result );
    }
}