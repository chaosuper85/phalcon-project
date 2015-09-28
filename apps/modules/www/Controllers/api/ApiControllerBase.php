<?php

//namespace Modules\www\Controllers;

use Phalcon\Mvc\Controller;


class ApiControllerBase extends Controller
{
    public  $user;

    public function beforeExecuteRoute($dispatcher)
    {

        $user = $this->session->get('login_user');
        if( !empty($user) ){
            $this->user = array(
                'user_id' => $user->id,
                'usertype' => $user->usertype,
                'mobile' => $user->mobile,
                'telephone_number' => $user->telephone_number,
                'contactName' => $user->contactName,
                'contactNumber' => $user->contactNumber,
                'username' => $user->username,
                'email' => $user->email,
                'avatarpicurl' => $user->avatarpicurl,
                'real_name' => $user->real_name,
            );
        }else{
            $this->user = array();
        }

    }


    public function getUser()
    {
        return $this->session->get("login_user");
    }
}