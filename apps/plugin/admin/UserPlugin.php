<?php

namespace Plugin\admin;

use Phalcon\Events\Event,
    Phalcon\Mvc\User\Plugin,
    Phalcon\Mvc\Dispatcher,
    Library\Helper\StringHelper;

class UserPlugin extends  Plugin
{

    public function __construct( $di)
    {
        $this->_dependencyInjector = $di;

    }


    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {

        //除去不需要校验登录的URL
        $uri = $this->request->getURI();
        $uri = StringHelper::getUri($uri);

        $noUri = $this->di->get('admin_cfg')->NO_LOGIN_URL;
        $noUri = \Library\Helper\ArrayHelper::objectToArray( $noUri);
        if( in_array($uri,$noUri))
            return ;

        //登录校验
        $isOk = $this->AdminUserService->checkLogin( );
        if( !$isOk)
        {
            if( $this->request->isAjax() || StringHelper::startWith($uri,"api") || StringHelper::startWith($uri,"/api") )
            {
                $result = array(
                    'error_code' => $this->di->get('admin_cfg')->ERR_CODE->NO_LOGIN,
                    'error_msg'  => '请先登录'
                );
                $this->response->setJsonContent($result)->send();
                exit(0);
            }else{
                $this->response->redirect('login?from='.$uri)->send();
                exit(0) ;
            }
        }

//        //权限校验---先注释掉
//            if ($this->AclService->hasAccFun(substr($uri, 1)))
//            {
//                if ($this->request->isAjax()) {
//                    $result = array(
//                        'error_code' => 1,
//                        'error_msg' => '无此权限'
//                    );
//                    $this->response->setJsonContent($result)->send();
//                    $this->flash->error('无权限访问！');
//                    die;
//                } else {
//                    $this->flash->error('无权限访问！');
//                    return false;
//                }
//            }


    }


}