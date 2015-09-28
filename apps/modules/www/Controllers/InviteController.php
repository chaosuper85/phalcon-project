<?php

use Library\Log\Logger;
use Phalcon\Http\Cookie;


/**
 * @RoutePrefix("/invite")
 */
class InviteController extends ControllerBase
{

    /**
     * @Route("/", methods={"GET", "POST"})
     */
    public function indexAction()
    {

        $companyinviteUlr = 'http://www.56xdd.com/invite/company';

        $user = $this->session->get('login_user');
        $user_id = $user->id;
        $enterpriseid = $user->enterpriseid;

        $enterprise = $this->EnterpriseService->getEnterprise($enterpriseid);

        $token = $enterprise->invite_token;

        $param = '?';
        $param .= 'from='. $user_id.'&';
        $param .= 'token='.$token;

        $result = array(
            'invite_url'    =>  $companyinviteUlr.$param,
        );

        Logger::info('result: '.var_export($result, true));

        $this->data['data'] =  $result;

        $this->view->pick('index/user/invite');
        $this->view->setVar('data',  $this->data );

        return ;
    }



    /**
     * @Route("/company", methods={"GET", "POST"})
     */
    public function companyAction()
    {

        $invite_userid  = $this->request->getQuery('from');
        $invite_token  = $this->request->getQuery('token');

        $invite_token = $this->aes->encrypt($invite_token);
        $invite_userid = $this->aes->encrypt($invite_userid);

        $t = $this->constant->INVITE_COOKIE_SECONDS;
        $this->cookies->set('invite_userid', $invite_userid, time()+$t );
        $this->cookies->set('invite_token', $invite_token, time()+$t );

        $retArr = array(  );
        $this->data['data'] =  $retArr;

        $this->view->pick('invite/company');
        $this->view->setVar('data',  $this->data );
        return ;
    }

}