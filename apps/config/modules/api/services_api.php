<?php

/**
 * Services are globally registered in this file
 */

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\DI\FactoryDefault;
use Library\Session\Redis as SessionAdapter;
use Phalcon\Loader;

use Phalcon\Mvc\Model;

define('MODULES_DIR',   __DIR__ . '/../../../modules');
define('MODEL_DIR',     __DIR__ . '/../../../models');
define('PLUGIN_DIR',    __DIR__ . '/../../../plugin');
define('SERVICE_DIR',   __DIR__ . '/../../../services/');
define('LIBRARY_DIR',   __DIR__ . '/../../../library/');

Model::setup( array(
    'notNullValidations' => false,
) );

$loader = new Loader(  );

$loader->registerDirs(array(
    MODULES_DIR.'/api/Controllers/',
    MODEL_DIR
))->register();

$loader->registerNamespaces(array(
    'Modules\api\Validation'    => MODULES_DIR.'/api/Validation/',
    'Plugin\api'                => PLUGIN_DIR.'/api/',
    'Services'                  => SERVICE_DIR,
    'Library'                   => LIBRARY_DIR,
));

$loader->register();


/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

$di->set('router',function () {

    // Use the annotations router
    $router = new \Phalcon\Mvc\Router\Annotations(false);
    $router->removeExtraSlashes(true);
    $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);

    //解决访问不存在的路由报错
//    $router->setDefaultController('error');
//    $router->setDefaultAction('error');

    /*
     * controller名字的前缀和url的匹配
     */
    $router->addModuleResource('api', 'Index',       '/index');
    $router->addModuleResource('api', 'Location',     '/location');
    $router->addModuleResource('api', 'AppUser',     '/appuser');
    $router->addModuleResource('api', 'AppOrder',     '/apporder');
    $router->addModuleResource('api', 'AppLocate',     '/applocate');
    # 临时测试接口
    $router->addModuleResource('api', 'TestLocate',     '/testlocate');


    return $router;
} );



$di->set('view', function( ) use ($config) {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir( $config->application->apiViewsDir );
    return $view;
});


$di['url'] = function () {
    $url = new UrlResolver();
    $url->setBaseUri('/');

    return $url;
};


$di['session'] = function () {

    $session = new SessionAdapter(array(
        'path' => "tcp://127.0.0.1:6379?weight=1"
    ));

    $session->start();


    return $session;
};