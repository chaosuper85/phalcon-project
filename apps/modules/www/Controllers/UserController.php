<?php

use Library\Helper\OrderLogHelper;
use Phalcon\Http\Response;
use Services\Services as Services;
use Library\Log\Logger;
use Library\Helper\StringHelper;
use Library\Helper\XssFilter;

use Modules\www\Validation\TestValidation;
use Library\Helper\ArrayHelper;


/**
 * @RoutePrefix("/user")
 */
class UserController extends ControllerBase
{

    /**
     * @Get("/")
     */
    public function indexAction()
    {
        try {
            /*
            $data = array(
                'action_type'=>17,
                'reamId'=>123,
                'reamType'=>1,
                'targetReamId' =>123,
                'targetReamType'=>1,
               'json_content' => array(
                    'orerid'=>'123',
                    'address'=>array(
                        "provinceid"=> 1,
                        "cityid"=> 2,
                        "townid"=> 3,
                        "address"=> "黄村镇清源西里9号"
                    ),
                    'address_id'=>'',
                    'product_supply_time'=>'',
                    'product_supply_time_id'=>'',
                    'driver_id'=>'',
                    'box_info'=>array(
                        'box_code'=>'',
                        'box_ensupe'=>'',
                    ),
                    'box_id'=>123,
            ));
            $this->ActivityLogComponent->orderHistroyLog( $data );
            */


            return;
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
        }
    }


    /** 注册页面
     * @Get("/register")
     */
    public function registerAction()
    {
        $token = $this->cookies->get('xdd-token')->getValue();
        if (!empty($token)) {
            $token = $this->getDI()->get('aes')->decrypt($token);
        }

        $result = $this->getDI()->get('UserLoginService')->checkLogin($token);
        if ( !empty($token) && isset($result['error_code']) && $result['error_code'] == 0) {
            return $this->response->redirect("/");//
        } else {
            $type = $this->request->getQuery('type');
            return $this->view->pick('index/page/reg')->setVar('data', array("type" => $type));
        }
    }

    /**
     * @Get("/changeBind")
     */
    public function changeBindAction()
    {
        $this->view->pick('user/page/change_phone')->setVar("data",$this->data);

        return $this->view;
    }

    /**
     * @Get("/changeBindPass")
     */
    public function changePassBindAction()
    {
        $this->view->pick('user/page/change_pass')->setVar('data', $this->data);

        return $this->view;
    }


}