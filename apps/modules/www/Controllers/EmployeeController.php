<?php


use Library\Helper\PageHelper;

/**
 * @RoutePrefix("/employee")
 */
class EmployeeController extends ControllerBase
{
    /**
     * @Get("/")
     */
    public function indexAction(){
        $user           = $this->session->get('login_user');// todo 权限管理
        $enterpriseId   = $user->enterpriseid;
        $result         = array();
        $this->EmployeeService->listEmpsGroupBy( $enterpriseId ,$result);

        $this->data['data'] =  $result;
        return $this->view->pick('user/page/employee')->setVar("data",$this->data);
    }

    /** 员工申请
     *
     * @Get("/employeeApply")
     */
    public  function  employeeApplyAction(){
        $pageNo         = $this->request->getQuery("page");
        $pageSize       = $this->request->getQuery("pageSize");
        $invitingStatus = $this->constant->INVITE_STATUS->INVITING; // 邀请中
        $urlType        = $this->constant->INVITE_TYPE->LINK_INVITE;
        $user           = $this->session->get('login_user');// todo 当前用户的 公司员工列表
        $enterpriseId   = $user->enterpriseid;
        $pageHelper     = new PageHelper( $pageNo,$pageSize);
        $this->EmployeeService->getInvitedEmpsWithPage( $enterpriseId , $urlType ,$invitingStatus,$pageHelper);

        $this->data['data'] = array("pageInfo" => $pageHelper->toArray());

        return $this->view->pick("user/page/employee_apply")->setVar("data", $this->data);
    }

}