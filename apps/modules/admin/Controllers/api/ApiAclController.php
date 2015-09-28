<?php

use Library\Log\Logger;


/**
 * Auth haibo
 * @RoutePrefix("/api/acl")
 * Class ApiAclController
 */
class ApiAclController extends ApiControllerBase
{
    const EXCEPTION_MSG = '系统环境异常';

    /**
     * @Route("/addGroup", methods={"GET","POST"})
     * 功能: 增加用户组
     */
    public function addGroupAction()
    {
        try{
//            if( !$this->paramVerify()) {
//                return $this->sendBack();
//            }

            $name = $this->request->get('name');
            $level = $this->request->get('level');
            $msg = $this->AdminGroupService->addGroup($name, $level);

            $msg || $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->CREATE_GROUP,
                '',
                0,
                0,
                '增加了用户组:'.$name
            );

        }catch (\Exception $e){
            Logger::error($e->getMessage());
            $this->ret['error_msg'] = '系统环境异常,请查看日志';
            $this->ret['error_code'] = -1;
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }


    /**
     * @Route("/setGroup", methods={"GET","POST"})
     * 功能: 修改用户组
     */
    public function setGroupAction()
    {
        try {

            $id = $this->request->get('id');
            $fun_ids = $this->request->get('fun_id');
            $name = $this->request->get('name');

            if( !is_array($fun_ids))
                $fun_ids = array($fun_ids);
            $msg = $this->AdminGroupService->setGroup($id, $fun_ids, $name);

            $msg || $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->ALTER_GROUP,
                json_encode(['new_name'=>$name /*,'fun_ids'=>$fun_ids*/]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->GROUP,
                $id,
                '修改了用户组信息'
            );

        }catch (\Exception $e){
            $this->ret['error_msg'] = '系统环境异常,请查看日志';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }

    /**
     * @Route("/addGroupUser", methods={"GET","POST"})
     * 功能: 增加组内用户
     */
    public function addGroupUserAction()
    {
        try{
//            if( !$this->paramVerify()) {
//                return $this->sendBack();
//            }

            $uid = intval($this->request->get('user_id'));
            $group = intval($this->request->get('group_id'));
            $msg = $this->AdminGroupService->addGroupUser($uid, $group);

            $msg || $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->ADD_GROUP_USER,
                json_encode(['admin_user_id'=>$uid ,'group_id'=>$group]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->GROUP,
                $group,
                '修改了用户组信息'
            );

        }catch (\Exception $e){
            $this->ret['error_msg'] = '系统环境异常,请查看日志';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack( $msg);
    }

    /**
     * @Route("/delGroupUser", methods={"GET","POST"})
     * 功能: 删除组内用户
     */
    public function delGroupUserAction()
    {
        try{
//            if( !$this->paramVerify()) {
//                return $this->sendBack();
//            }

            $uid = intval($this->request->get('user_id'));
            $group = intval($this->request->get('group_id'));
            $msg = $this->AdminGroupService->delGroupUser($uid, $group);

            $msg || $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->ADD_GROUP_USER,
                json_encode(['admin_user_id'=>$uid ,'group_id'=>$group]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->GROUP,
                $group,
                '修改了用户组信息'
            );

        }catch (\Exception $e){
            $this->ret['error_msg'] = '系统环境异常,请查看日志';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack( $msg);
    }


    /**
     * @Route("/delGroup", methods={"GET","POST"})
     * 功能: 删除组
     */
    public function delGroupAction()
    {
        try{
//            if( !$this->paramVerify()) {
//                return $this->sendBack();
//            }

            $id = $this->request->get('id');
            $msg = $this->AdminGroupService->delGroup( intval($id));

            $msg || $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->DEL_GROUP,
                json_encode(['group_id'=>$id]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->GROUP,
                $id,
                '删除了用户组'
            );

        }catch (\Exception $e){
            $this->ret['error_msg'] = self::EXCEPTION_MSG;
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }

    /**
     * @Route("/listMenu", methods={"GET"})
     * 功能: 返回用户拥有的菜单
     */
    public function listMenuAction()
    {
        try{
            $this->ret['data'] = $this->AclService->listMenu();

        }catch (\Exception $e){
            $this->ret['error_msg'] = '系统环境异常,请查看日志';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack();
    }

//    /**
//     * @Route("/power", methods={"GET","POST"})
//     * 功能: 返回权限模板菜单
//     */
//    public function powerAction()
//    {
//        try{
//            $group = $this->request->get('group_name');
//
////            if( !$this->paramVerify()) {
////                return $this->sendBack();
////            }
//
//            $ret = $this->AclService->listMenu( $group);
//            var_dump($ret);die;
//            $this->ret['data'] = $ret;
//            $this->sendBack();
//        }catch( \Exception $e){
//            $this->ret['error_msg'] = '系统环境异常,请查看日志';
//            $this->ret['error_code'] = -1;
//            $this->sendBack();
//            Logger::warn($e->getMessage());
//        }
//    }

    /**
     * @Route("/powerTmp", methods={"GET","POST"})
     * 功能: 返回权限模板菜单
     */
    public function powerTmpAction()
    {
        try{
            $group = $this->request->get('group_id');

//            if( !$this->paramVerify()) {
//                return $this->sendBack();
//            }

            $ret = $this->AclService->accFunTpl( $group);

            $this->ret['data'] = $ret;
            $this->sendBack();
        }catch( \Exception $e){
            $this->ret['error_msg'] = self::EXCEPTION_MSG;
            $this->ret['error_code'] = -1;
            $this->sendBack();
            Logger::warn($e->getMessage());
        }
    }


//    /**
//     * 功能:获取某分组的所有权限
//     * 备注:
//     * @Route("/groupPower", methods={"GET","POST"})
//     */
//    public function groupPowerAction()
//    {
//        try{
//            $group = $this->request->get('group_id');
//
////            if( !$this->paramVerify()) {
////                return $this->sendBack();
////            }
//            $name = $this->request->get('group_name');
//
//            $this->ret['data'] = $this->AclService->getAccFun($name, true);
//var_dump($this->ret);die;
//            return  $this->sendBack();
//        }catch(\Exception $e) {
//            $this->ret['error_msg'] = '系统环境异常,请查看日志';
//            $this->ret['error_code'] = -1;
//            $this->sendBack();
//            Logger::warn($e->getMessage());
//        }
//
//
//    }



    /**
     * @Route("/addPower", methods={"GET","POST"})
     * 功能: 给用户组添加权限
     */
    public function addPowerAction()
    {

        try{
//            if( !$this->paramVerify()) {
//                return $this->sendBack();
//            }
            $groupId = $this->request->get('id');
            $functionArr = $this->request->get('fun_ids');

            $sum = $this->AclService->addPower($groupId, $functionArr);

            $this->ret['error_msg'] = '插入了'.$sum.'条记录';

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->ADD_GROUP_ACL,
                json_encode(['group_id'=>$groupId]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->GROUP,
                $groupId,
                '为用户组分配了权限.'.$this->ret['error_msg']
            );

        }catch (\Exception $e){
            $this->ret['error_msg'] = self::EXCEPTION_MSG;
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack();
    }




    //group: id function:ids
    /**
     * @Route("/delPower", methods={"GET","POST"})
     * 功能: 删除权限
     */
    public function delPowerAction()
    {
        try{
//            if( !$this->paramVerify()) {
//                return $this->sendBack();
//            }

            $groupId = $this->request->get('id');
            $functionArr = $this->request->get('fun_ids');
            $sum = $this->AclService->delPower($groupId, $functionArr);

            $this->ret['error_msg'] = '删除了'.$sum.'条记录';

            $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->DEL_GROUP_ACL,
                json_encode(['group_id'=>$groupId]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->GROUP,
                $groupId,
                '删除了用户组的一些权限.'.$this->ret['error_msg']
            );

        }catch (\Exception $e){
            $this->ret['error_msg'] = self::EXCEPTION_MSG;
            $this->ret['error_code'] = -1;
            $this->sendBack();
            Logger::warn($e->getMessage());
        }

        return  $this->sendBack();
    }



}