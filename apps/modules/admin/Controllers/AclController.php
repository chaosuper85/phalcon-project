<?php

use Library\Log\Logger;
use Modules\admin\Validation\PowerValidation;

/**
 * Auth haibo
 * @RoutePrefix("/acl")
 */
class AclController extends ControllerBase
{

    /**
     * 返回权限组和每组的所有用户
     * @Route("/groupUsers", methods={"GET", "POST"})
     */
    public function userAclAction()
    {
        $groups = array();
        $sum = $this->AdminGroupService->groups($groups);
        if( $sum == 0)
            return false;

        foreach($groups['data'] as $k=>$v) {
            $users = $this->AclService->getAdminUserByGroupName($v['group_name']);
            $groups['data'][$k]['user'] = $users->toArray();
        }

$this->bo_dump($groups);
        $this->ret = $groups;
        return  $this->sendBack('page/admin/group');
    }

//    /**
//     * @Route("/acl", methods={"GET", "POST"})
//     */
//    public function aclAction()
//    {
//        $this->ret = $this->AclService->funList();
//        return  $this->sendBack('page/acl/index');
//    }

    /**
     * @Route("/groupAcl", methods={"GET", "POST"})
     */
    public function groupsAction()
    {
        $validator = new PowerValidation('groups');
        if( !$this->paramVerify($validator))
            return $this->sendBack('page/admin/role');

        $sum = $this->AdminGroupService->groups($this->ret);
        foreach($this->ret['data'] as $k=>&$v) {
            $v['acl'] = $this->AclService->listMenu( $v['group_name']);
        }
$this->bo_dump($this->ret);
        Logger::info('groups sum:'.$sum);
        return $this->sendBack('page/admin/role');
    }


}