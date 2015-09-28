<?php

use Library\Log\Logger;


/**
 * @RoutePrefix("/enterprise")
 */
class EnterpriseController extends ControllerBase
{
    /*
    |--------------------------------------------------------------------------
    | 企业管理 Controller
    | auth：haibo
    | 待测试
    |--------------------------------------------------------------------------
    */


    /**
     * @Route("/staff", methods={"GET", "POST"})
     */
    public function staffAction()
    {
        if( !$this->paramVerify()) {
            return false;
        }

        $com_id = $this->request->get('com_id');

        $ret = $this->EnterpriseService->staff($com_id);

        Logger::info( 'staff  sum:'. count($ret) );
        $this->sendBack('carTeam/list',$ret);
    }

    /**
     * @Route("/adminApplies", methods={"GET", "POST"})
     */
    public function adminAppliesAction( )
    {
        if( !$this->paramVerify()) {
            return false;
        }

        $id = $this->request->get('com_id');
        $ret = $this->ApplyAdminService->applies($id);
    }


    /**
     * @Route("/adminApplies", methods={"GET", "POST"})
     */
    public function imagineStaffAction()
    {
        $this->EnterpriseService->imagineStaff($this->ret, $this->request->get('com_id'));


    }

    /**
     * @Route("/appeals", methods={"GET", "POST"})
     */
    public function appealsAction()
    {

        if( !$this->paramVerify())
            $this->sendBack('',true);

        $ret = $this->TicketService->tickets($this->ret);

        $this->sendBack('',true);
    }









    //管理员审核驳回
    public function adminAuditRejectAction()
    {
        $userid = $this->request->get('user_id');
        $msg = $this->request->get('msg');
        if( empty($userid) || empty($msg))
            return false;

        $ret = $this->ApplyAdminService->rejectAdminApply($userid, $msg);

        Logger::info('adminAuditReject: '.var_export($ret,true));
        $this->response->setContentType('application/json')->setJsonContent($ret)->send();
    }

    //管理员审核通过
    public function adminAuditPassAction()
    {
        $userid = $this->request->get('user_id');
        if( empty($userid))
            return false;

        $ret = $this->ApplyAdminService->passAdminApply($userid);

        Logger::info('adminAuditPass: '.var_export($ret,true));
        $this->response->setContentType('application/json')->setJsonContent($ret)->send();
    }

    //同意加入公司
    public function staffAuditPassAction()
    {
        $uid = $this->request->get('user_id');
        $staff_id = $this->request->get('staff_id');
        $com_id = $this->UserService->comId($uid);

        if( empty($staff_id) || empty($com_id))
            return false;

        $ret = $this->UserService->setEnterprise($staff_id, $com_id);

        Logger::info('staffAuditPass: '.var_export($ret,true));
        return $ret;
    }



    //拒绝申诉
    public function appealRejectAction()
    {
        $msg = $this->request->get('msg');
        $id  = $this->request->get('id');
        if(empty($msg) || empty($id))
            return false;

        $ret = $this->EnterpriseService->appealAudit($id, false, $msg);

        Logger::info('appealReject: '.var_export($ret,true));
        return $ret;
    }

    //通过申诉
    public function  appealPassAction()
    {
        $id  = $this->request->get('id');
        if( empty($id))
            return false;

        $ret = $this->EnterpriseService->appealAudit($id);

        Logger::info('appealPass: '.var_export($ret,true));
        return $ret;
    }



}