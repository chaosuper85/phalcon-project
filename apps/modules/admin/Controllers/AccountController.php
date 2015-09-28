<?php

use Library\Log\Logger;
use Modules\admin\Validation\userValidation;

/**
 * auth: haibo
 * 后台用户管理
 * @RoutePrefix("/account")
 */
class AccountController extends ControllerBase
{

    /**
     * 获取所有后台账户——管理员权限
     * @Route("/users", methods={"GET", "POST"})
     */
    public function usersAction()
    {
        try {
            //        if( $this->paramVerify( new YardValidation('delCarTeam')))
            //            return $this->sendBack();
            $this->ret = $this->AdminUserService->users();

            Logger::info('queryUsers  sum:'.$this->ret['data_sum']);
        } catch(\Exception $e) {
            Logger::error('queryUsers :'.$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return  $this->sendBack('page/admin/user');
    }


    /**
     * 账户个人信息
     * @Route("/info", methods={"GET", "POST"})
     */
    public function infoAction()
    {
        try {
            //        if( $this->paramVerify( new YardValidation('delCarTeam')))
            //            return $this->sendBack();

            $id = intval($this->request->get('id', null, false));
            if( !$id) {
                $usr = $this->AdminUserService->getSessionUser();
                $id = $usr['id'];
            }

            $this->ret = $this->AdminUserService->users();

            Logger::info('queryUsers  sum:'.$this->ret['data_sum']);

        } catch(\Exception $e) {
            Logger::error('queryUsers :'.$e->getMessage());
            $this->ret['data_sum'] = 0;
            $this->ret['data'] = false;
        }

        return  $this->sendBack('page/admin/userinfo');
    }




}