<?php

use Phalcon\Mvc\Controller;


class ControllerBase extends Controller
{

    //提供给页面的data数据通用格式
    public  $data;
    public  $user;

    public function beforeExecuteRoute($dispatcher)
    {
        $user = $this->session->get('login_user');
        if( !empty($user) ){
            $status  = $this->UserService->getStatus( $user->id, $user->usertype );
            $user_id = $this->aes->encrypt($user->id);
            $this->user = array(
                'user_id' => $user_id,
                'usertype' => $user->usertype,
                'mobile' => $user->mobile,
                'telephone_number' => $user->telephone_number,
                'contactName' => $user->contactName,
                'contactNumber' => $user->contactNumber,
                'username' => $user->username,
                'email' => $user->email,
                'avatarpicurl' => $user->avatarpicurl,
                'real_name' => $user->real_name,
                'status' => $status,
            );
        }else{
            $this->user = array();
        }

        $this->data['user'] = $this->user;

    }


    public  function validate( $arr )
    {

    }

    public function getUser()
    {
        return $this->session->get("login_user");
    }


    /** 跳转到错误页面
     * @param $data
     */
    public function forwardError( $data ){
        return $this->dispatcher->forward(array("controller" => "index", "action" => "error","params" => $data));
    }


}