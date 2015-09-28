<?php

use Library\Log\Logger;
use Phalcon\Http\Cookie;
use Library\Helper\IdGenerator;
use Library\Helper\ArrayHelper;


/**
 * @RoutePrefix("/api/invite")
 */
class ApiInviteController extends ApiControllerBase
{
    /**
     * @Route("/sendInvite", methods={"GET", "POST"})
     */
    public function sendInviteAction()
    {
        $mobile = $this->request->getQuery('mobile');

        $msg = $this->InviteSendValidation->validate(array('mobile' => $mobile));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            try {
                $user = $this->UserService->getUserByMobile($mobile);

                $invitee_userid = $user->id;

                $user = $this->di->get('session')->get('login_user');

                $user_id = $user->id;
                $enterpriseid = $user->enterpriseid;

                $ret = $this->InviteService->sendInvite(
                    $user_id,
                    $enterpriseid,
                    $invitee_userid,
                    $this->constant->INVITE_TYPE->SEARCH_INVITE
                );

                /*
                 * TODO 发站内信
                 */
                if ($ret) {
                    $result = array(
                        'error_code' => '0',
                        'error_msg' => '',
                        'data' => array(),
                    );
                } else {
                    $result = array(
                        'error_code' => '1001',
                        'error_msg' => '接口调用出错',
                        'data' => array(),
                    );
                }

                Logger::info('result: ' . var_export($result, true));
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => "100004",
                    'error_msg' => '邀请异常'
                );
            }
        }
        $this->response->setJsonContent($result)->send();
        return ;
    }


    /**
     * @Route("/confirmInvite", methods={"GET", "POST"})
     */
    public function confirmInviteAction()
    {
        $invite_id = $this->request->getQuery('invite_id');
        $user_id = $this->di->get('session')->get('login_user')->id;

        $msg = $this->InviteConfirmValidation->validate(array('invite_id' => $invite_id));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            try {
                $ret = $this->InviteService->confirmInvite($invite_id, $user_id);

                if ($ret) {
                    $result = array(
                        'error_code' => '0',
                        'error_msg' => '',
                        'data' => array(),
                    );
                } else {
                    $result = array(
                        'error_code' => '1001',
                        'error_msg' => '接口调用出错',
                        'data' => array(),
                    );
                }

                Logger::info('result: ' . var_export($result, true));
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100004',
                    'error_msg' => '公司邀请异常',
                    'data' => array()
                );
            }
        }
        $this->response->setJsonContent($result)->send();
        return ;
    }

    /**
     * @Route("/refuseInvite", methods={"GET", "POST"})
     */
    public function refuseInviteAction()
    {
        $invite_id = $this->request->getQuery('invite_id');
        $user_id = $this->di->get('session')->get('login_user')->id;

        $msg = $this->InviteRefuseValidation->validate(array('invite_id' => $invite_id));
        if(count($msg)){
            $result = array(
                'error_code' => 10001,
                'error_msg' => '参数校验出错',
                'data' => ArrayHelper::validateMessages($msg),
            );
        }else {
            try {
                $ret = $this->InviteService->refuseInvite($invite_id, $user_id);

                if ($ret) {
                    $result = array(
                        'error_code' => '0',
                        'error_msg' => '',
                        'data' => array(),
                    );
                } else {
                    $result = array(
                        'error_code' => '1001',
                        'error_msg' => '接口调用出错',
                        'data' => array(),
                    );
                }
                Logger::info('result: ' . var_export($result, true));
            } catch (\Exception $e) {
                Logger::warn($e->getMessage());
                $result = array(
                    'error_code' => '100004',
                    'error_msg' => '拒绝邀请异常',
                    'data' => array(),
                );
            }
        }
        $this->response->setJsonContent($result)->send();
        return ;
    }


    /**
     * @Route("/resetInviteUrl", methods={"GET", "POST"})
     */
    public function resetInviteUrlAction()
    {

        $companyinviteUlr = 'http://www.56xdd.com/invite/company';

        $user = $this->di->get('session')->get('login_user');
        $user_id = $user->id;
        $enterpriseid = $user->enterpriseid;

        $token = IdGenerator::guid();
        try {
            $this->EnterpriseService->saveEnterpriseToken($enterpriseid, $token);

            $param = '?';
            $param .= 'from=' . $user_id . '&';
            $param .= 'token=' . $token;

            $result = array(
                'error_code' => '0',
                'error_msg' => '',
                'data' => array(
                    'invite_url' => $companyinviteUlr . $param,
                ),
            );

            Logger::info('result: ' . var_export($result, true));
        }catch (\Exception $e){
            Logger::warn($e->getMessage());
            $result = array(
                'error_code' => '100004',
                'error_msg' => '重置邀请链接异常',
                'data' => array(),
            );
        }
        $this->response->setJsonContent($result)->send();
        return ;
    }
}