<?php

use Phalcon\Http\Response;
use Library\Log\Logger;

/**
 *  公司信息管理
 * @RoutePrefix("/api/company")
 */
class ApiCompanyController extends ApiControllerBase
{

    /**
     * @Route("/edit", methods={"GET", "POST"})
     */
    public function editAction(){
        $user = $this->session->get('login_user');

    }


}