<?php

/**
 * Services are globally registered in this file
 */

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\DI\FactoryDefault;

use Library\Session\Redis as SessionAdapter;

use Phalcon\Loader;
use Phalcon\Mvc\View;

use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Manager as EventsManager;

use Phalcon\Http\Response\Cookies;

use Phalcon\Crypt;

use Phalcon\Mvc\Dispatcher as Dispatcher;

use Phalcon\Mvc\Model;
use Library\Helper\ArrayHelper;

$loader = new Loader(  );


define('MODULES_DIR',   __DIR__ . '/../../../modules');
define('MODEL_DIR',     __DIR__ . '/../../../models');
define('PLUGIN_DIR',    __DIR__ . '/../../../plugin');
define('SERVICE_DIR',   __DIR__ . '/../../../services/');
define('LIBRARY_DIR',   __DIR__ . '/../../../library/');


Model::setup( array(
    'notNullValidations' => false,
) );

/*
 * 自加载目录
 */
$loader->registerDirs(array(
    MODULES_DIR.'/www/Controllers/api/',
    MODULES_DIR.'/www/Controllers/',
    MODEL_DIR,
))->register();

$loader->register();

/**
 * 命名空间和路径映射
 */
$loader->registerNamespaces(array(
    'Modules\www\Events'        => MODULES_DIR.'/www/Events/',
    'Modules\www\Validation'    => MODULES_DIR.'/www/Validation/',
    'Plugin\www'                =>  PLUGIN_DIR.'/www/',
    'Services'                  => SERVICE_DIR,
    'Library'                   => LIBRARY_DIR,
));

$loader->register();




/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();



$di->set('dispatcher', function() {

    //创建一个事件管理
    $eventsManager = new EventsManager();

    //附上一个侦听者
    $eventsManager->attach("dispatch:beforeDispatchLoop", function($event, $dispatcher) {

        $keyParams = array();
        $params = $dispatcher->getParams();

        $di = $dispatcher->getDI();

        //将每一个参数分解成key、值 对
        foreach ($params as $number => $value) {
            $parts = explode(':', $value);
            $keyParams[$parts[0]] = $parts[1];
        }

        //重写参数
        $dispatcher->setParams($keyParams);
    });

    $dispatcher = new MvcDispatcher();
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
});




$di->set('router',function () {

    // Use the annotations router
    $router = new \Phalcon\Mvc\Router\Annotations(false);
    $router->removeExtraSlashes(true);
    $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);

    $router->addModuleResource('www', 'ApiIndex',       '/api/index');
    $router->addModuleResource('www', 'ApiInvite',      '/api/invite');
    $router->addModuleResource('www', 'ApiMsgP2P',      '/api/msgP2P');
    $router->addModuleResource('www', 'ApiUser',        '/api/user');
    $router->addModuleResource('www', 'ApiEmployee',    '/api/employee');
    $router->addModuleResource('www', 'ApiCompany',     '/api/company');
    $router->addModuleResource('www', 'ApiCity',        '/api/city');
    $router->addModuleResource('www', 'ApiCarteam',     '/api/carteam');
    $router->addModuleResource('www', 'ApiAgent',       '/api/agent');
    $router->addModuleResource('www', 'ApiAccount',     '/api/account');
    $router->addModuleResource('www', 'ApiOrder',       '/api/order');
    $router->addModuleResource('www', 'ApiFreightOrder','/api/freight/order');
    $router->addModuleResource('www', 'ApiCarteamOrder','/api/carteam/order');
    $router->addModuleResource('www', 'ApiOrderComment','/api/order/comment');
/*
 * controller名字的前缀和url的匹配
 */
    $router->addModuleResource('www', 'Main',       '/');
    $router->addModuleResource('www', 'Index',       '/index');
    $router->addModuleResource('www', 'Invite',      '/invite');
    $router->addModuleResource('www', 'MsgP2P',      '/msgP2P');
    $router->addModuleResource('www', 'User',        '/user');
    $router->addModuleResource('www', 'Employee',    '/employee');
    $router->addModuleResource('www', 'Company',     '/company');
    $router->addModuleResource('www', 'City',        '/city');
    $router->addModuleResource('www', 'Carteam',     '/carteam');
    $router->addModuleResource('www', 'Agent',       '/agent');
    $router->addModuleResource('www', 'Account',     '/account');
    $router->addModuleResource('www', 'Order',        '/order');
    $router->addModuleResource('www', 'FreightOrder', '/freight/order');
    $router->addModuleResource('www', 'CarteamOrder', '/carteam/order');
    $router->addModuleResource('www', 'Test',        '/test');

    return $router;
} );

$di->setShared('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.tpl' => function ($view, $di) use ($config) {

            $engine = new Library\Engine\SmartyEngine($view, $di);

            $engine->setOptions( array(
                'compile_dir' => $config->application->cacheDir,
                'config_dir' => $config->application->libraryDir.'Smarty/config/www',
                'plugins_dir' => $config->application->libraryDir.'Smarty/plugins',
                'template_dir' => $config->application->viewsDir,
                'left_delimiter' => '{%',
                'right_delimiter' => '%}',
                'force_compile' => true, // 测试环境强制编译，生产环境请去掉
            ) );

            return $engine;
        },
    ));

    return $view;
});


$di['url'] = function () {
    $url = new UrlResolver();
    $url->setBaseUri('/');

    return $url;
};


$di->set('cookies', function () {
    $cookies = new Cookies();
    $cookies->useEncryption( false );
    return $cookies;
});




$di['session'] = function () {

    $session = new SessionAdapter(array(
        'path' => "tcp://127.0.0.1:6379?weight=1"
    ));

    $session->start();

    return $session;
};