<?php

use Library\Log\Logger;
use Modules\admin\Validation\ApiAccountValidation;

/**
 * auth haibo
 * 后台用户管理api
 * @RoutePrefix("/api/account")
 */
class ApiAccountController extends ApiControllerBase
{
    /**
     * 功能: 增加后台账户
     * @Route("/add", methods={"GET","POST"})
     */
    public function addUserAction()
    {
        try{
            if( !$this->paramVerify(new ApiAccountValidation('add'))) {
                return $this->sendBack();
            }

            $name = $this->request->get('name');    //必传
            $pwd = $this->request->get('pwd');      //必传
            $email = $this->request->get('email',null,false);
            $mobile = $this->request->get('mobile',null,false);
            $real_name = $this->request->get('real_name',null,false);
            $avatar = $this->request->get('avatar',null,false);

            $msg = $this->AdminUserService->addUser($name, $real_name, $pwd, $email, $mobile, $avatar);

            $msg || $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->CREATE_USER,
                '',
                0,
                0,
                '创建了用户:'.$name
            );

        }catch( \Exception $e){
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }


    /**
     * 功能: 修改后台账户信息
     * 备注: updateInfo:普通用户修改个人信息 alter:管理员修改某人信息。  有点投机取巧
     * @Route("/alter", methods={"POST"})        管理员修改其他人
     * @Route("/updateInfo", methods={"POST"})   修改自己信息
     */
    public function alterAction()
    {
        try{
            if( !$this->paramVerify(new ApiAccountValidation('alter'))) {
                return $this->sendBack();
            }

            $id = $this->request->get('id');  //必传  用户修改个人信息接口可以不传id
            $real_name = $this->request->get('real_name',null,false);
            $pwd = $this->request->get('pwd',null,false);
            $email = $this->request->get('email',null,false);
            $mobile = $this->request->get('mobile',null,false);
            $avatar = $this->request->get('avatar',null,false);

            $way = $this->request->getURI();
            if( $way == 'api/account/updateInfo') {
                $usr = $this->AdminUserService->getSessionUser();
                if( $usr)
                    $id = $usr['id'];
                else
                    return  $this->sendBack('请先登录');
            }

            $msg = $this->AdminUserService->alterUser($id, $real_name, $pwd, $email, $mobile, $avatar);

        }catch( \Exception $e){
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::error($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }


    /**
     * 功能: 修改后台账户状态
     * @Route("/setStatus", methods={"GET","POST"})
     */
    public function setStatusAction()
    {
        try{
            if( !$this->paramVerify(new ApiAccountValidation('setStatus'))) {
                return $this->sendBack();
            }

            $id = $this->request->get('id');
            $status = $this->request->get('status');   //enable、disable、(delete迁移到logic)

            $msg = $this->AdminUserService->setStatus($id,$status);

            $msg || $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->ALTER_USER,
                json_encode(['admin_user_id'=>$id,'admin_status'=>$status]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->USER,
                $id,
                '修改了用户的状态'
            );
        }catch (\Exception $e){
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }

    /**
     * 功能: 删除后台账户
     * @Route("/delUsr", methods={"GET","POST"})
     */
    public function delUsrAction()
    {
        try{
            if( !$this->paramVerify(new ApiAccountValidation('delUsr'))) {
                return $this->sendBack();
            }

            $id = intval($this->request->get('id'));
            $msg = $this->LGAccChangeService->delAdminUsr($id);

            $msg || $this->ActivityLogService->addAdminLog(
                $this->admin_cfg->ADMIN_ACTION_TYPE->DEL_USER,
                json_encode(['admin_user_id'=>$id]),
                $this->admin_cfg->ADMIN_ACTION_TARGET_TYPE->USER,
                $id,
                '删除了用户'
            );
        }catch (\Exception $e){
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }

    /**
     * 功能: 用于下拉框弹出所有用户
     * @Route("/userList", methods={"GET","POST"})
     */
    public function userListAction()
    {
        try{
            if( !$this->paramVerify(new ApiAccountValidation('list'))) {
                return $this->sendBack();
            }
            $msg = $this->AdminUserService->userList($this->ret['data']);
        }catch (\Exception $e){
            $this->ret['error_msg'] = '网络异常';
            $this->ret['error_code'] = -1;
            Logger::warn($e->getMessage());
            return  $this->sendBack();
        }

        return  $this->sendBack($msg);
    }



}