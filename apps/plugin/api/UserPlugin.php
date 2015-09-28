<?php

namespace Plugin\api;


use Phalcon\Exception;
use Library\Log\Logger;

use Library\Helper\StringHelper;
use Library\Helper\ArrayHelper;
use Library\Helper\XssFilter;

use Phalcon\Mvc\User\Plugin;


class UserPlugin extends  Plugin
{
    public $user_id = null;

    public function beforeDispatchLoop($event, $dispatcher)
    {
        //过滤xss
        $this->filterXss();

        //csrf校验
        $uri = $this->request->getURI();

        $uri = StringHelper::getUri($uri);
        // check csrf
        $this->handleCsrf($uri);

        $this->handleUserLogin($uri);
    }

    public function beforeExecuteRoute($event, $dispatcher)
    {
        $this->dispatcher->setParam('user_id', $this->user_id);

        Logger::info("beforeExecuteRoute");
    }

    public function afterExecuteRoute($dispatcher)
    {
        Logger::info("afterExecuteRoute");
    }


    public function filterXss()
    {
        $xss = new  XssFilter();
        foreach ($_POST as $key => $value) {
            if(!is_array($value)){
                $_POST[$key] = $xss->filter_it($value);
            }
        }

        foreach ($_GET as $key => $value) {
            if(!is_array($value)){
                $_GET[$key] = $xss->filter_it($value);
            }
        }

    }


    public function handleUserLogin( $uri )
    {
        $uriArr = $this->di->get('config')->nologin_url;
        $uriArr = ArrayHelper::objectToArray($uriArr);

        if (!in_array($uri, $uriArr)) {

            $token = $this->request->getHeader($this->constant->CLINET_TOKEN);
            if( !empty($token) ){
                $token = $this->getDI()->get('aes')->decrypt($token);

                $result = $this->getDI()->get('AppUserLoginService')->checkLogin($token);
                if (isset($result['error_code']) && $result['error_code'] != 0) {
                    $result['error_code'] = $this->response_code->app->NEED_LOGIN[0];

                    $this->response->setJsonContent($result)->send();
                    exit;
                }
                else {
                    $user_info = explode('-', $token);
                    $this->user_id = $user_info[0];
                }

            }
            else {
                $result['error_code'] = $this->response_code->app->NEED_LOGIN[0];
                $result['error_msg'] = $this->response_code->app->NEED_LOGIN[1];

                $this->response->setJsonContent($result)->send();
                exit;
            }
        }

    }

    public function handleCsrf($uri)
    {
        $csrfUriArr = $this->di->get('config')->csrf_protect_url;

        $csrfUriArr = ArrayHelper::objectToArray($csrfUriArr);

        if (in_array($uri, $csrfUriArr)) {
            if (!self::csrfTokenMatch($this->request)) {

                if ($this->request->isAjax()) {
                    throw new Exception('handleCsrf fail');

                } else {
                    throw new Exception('handleCsrf fail');
                }

            }

        }

    }


    public function csrfTokenMatch($request)
    {
        $token = $request->getPost('csrf-token');
        $sessionToken = $this->getDI()->get('session')->get('csrf-token');
        if (empty($sessionToken) || empty($token) || ($sessionToken !== $token)) {
            return false;

        }
        return true;
    }


    /**
     *   从注解 获取 uri
     */
    public function  getUriFromAnnos(){
        $controller = $this->dispatcher->getControllerName()."Controller";
        $action     = $this->dispatcher->getActionName()."Action";
        $classAnnos = $this->annotations->get($controller)->getClassAnnotations();
        $method = $this->annotations->getMethod($controller,$action);
        $methodAnnos = $this->annotations->getMethod($controller,$action)->getAnnotations();
        $data = array();
        foreach( $classAnnos as $anno){
            $data[$anno->getName()] = $anno->getArguments()[0]; // RoutePrefix
        }
        $mdata =array();
        foreach( $methodAnnos as $anno ){
            $mdata[ $anno->getName() ] = $anno->getArguments( )[0];
        }
    }

}