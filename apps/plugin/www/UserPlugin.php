<?php

namespace Plugin\wwww;


use Phalcon\Exception;
use Library\Log\Logger;

use Library\Helper\StringHelper;
use Library\Helper\ArrayHelper;
use Library\Helper\XssFilter;

use Phalcon\Mvc\User\Plugin;


class UserPlugin extends  Plugin
{

    public function beforeDispatchLoop($event, $dispatcher)
    {
        //过滤xss
        $this->filterXss();
        //csrf校验
        $uri = $this->request->getURI();
        $param = $this->request->getQuery();
        unset( $param['_url'] );

        $quey = '' ;
        $flag = 0  ;
        foreach($param as $key => $p){
            $flag++;
            if( $flag > 1 ){
                $quey.= "&".$key."=".$p;
            }else{
                $quey.= $key."=".$p;
            }
        }

        $uri = StringHelper::getUri($uri);
        // check csrf
        $this->handleCsrf($uri);

        $this->handleUserLogin($uri, $quey);
    }



    public function beforeExecuteRoute($event, $dispatcher)
    {
/*
 * 临时加入的日志记录请求的参数
 */

        $reqQuery = $this->request->getQuery();
        if ( isset($reqQuery['_url']) ) unset($reqQuery['_url']);
        $reqPost = $this->request->getPost();
        if ( isset($reqPost['_url']) ) unset($reqPost['_url']);
        $reqJson = $this->request->getJsonRawBody(true);
        if( !empty($reqQuery) ){
            Logger::info('reqQuery: '.var_export($reqQuery,true));
        }else if( !empty($reqPost)  ){
            Logger::info('reqPost: '.var_export($reqPost,true));
        }else{
            Logger::info('reqJson: '.var_export($reqJson,true));
        }

        if( $this->request->isAjax() ){ // 设置 application/json
            $this->response->setContentType('application/json');
        }
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


    public function handleUserLogin( $uri, $query )
    {
        $uriArr = $this->di->get('config')->nologin_url;
        $uriArr = ArrayHelper::objectToArray($uriArr);
        $originUrl = StringHelper::getUri( $uri ); // 原生的URL
        if ( !in_array($originUrl, $uriArr) ) {

            $token = $this->cookies->get('xdd-token')->getValue();

            if( !empty($token) ){
                $token = $this->getDI()->get('aes')->decrypt($token);
            }

            $result = $this->getDI()->get('UserLoginService')->checkLogin($token);

            if ( empty($token) || ( isset($result['error_code']) && $result['error_code'] != 0 ))
            {
                //
                if ($this->request->isAjax() || StringHelper::startWith($uri,"api") || StringHelper::startWith($uri,"/api")) {
                    $result['from'] = $uri;
                    $this->response->setJsonContent($result)->send();
                    exit(0);
                } else {
                    $uri = urlencode( substr($uri,1).'?'.$query );
                    $this->response->redirect('index/login?from='.$uri);
                    return ;
                }
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