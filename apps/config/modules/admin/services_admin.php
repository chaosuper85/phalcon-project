<?php

/**
 * Services are globally registered in this file
 */

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\DI\FactoryDefault;

use Library\Session\Redis as SessionAdapter;

use Phalcon\Mvc\View;
use Library\Engine\SmartyEngine;
use Phalcon\Loader;
use Phalcon\Http\Response\Cookies;

use Phalcon\Mvc\Model;

define('MODULES_DIR',   __DIR__ . '/../../../modules');
define('MODEL_DIR',     __DIR__ . '/../../../models');
define('PLUGIN_DIR',    __DIR__ . '/../../../plugin');
define('SERVICE_DIR',   __DIR__ . '/../../../services/');
define('LIBRARY_DIR',   __DIR__ . '/../../../library/');

//Phalcon\Db::setup();

Model::setup( array(
    'notNullValidations' => false,
) );

/*
 * 自加载
 */
$loader = new Loader(  );

$loader->registerDirs(array(
    MODULES_DIR.'/admin/Controllers/api/',
    MODULES_DIR.'/admin/Controllers/',
    MODEL_DIR
))->register();

$loader->registerNamespaces(array(
    'Modules\admin\Events'        => MODULES_DIR.'/admin/Events/',
    'Modules\admin\Validation'  => MODULES_DIR.'/admin/Validation/',
    'Plugin\admin'              => PLUGIN_DIR.'/admin/',
    'Services'                  => SERVICE_DIR,
    'Library'                   => LIBRARY_DIR,
));

$loader->register();



/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Registering a router
 */
$di->set('router',function () {

    // Use the annotations router
    $router = new \Phalcon\Mvc\Router\Annotations(false);
    $router->removeExtraSlashes(true);
    $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
    $router->setDefaultAction('error');
    /*
     * controller名字的前缀和url的匹配
     */
    //页面
    $router->addModuleResource('admin', 'Index',            '/');

    $router->addModuleResource('admin', 'Agent',            '/agent');
    $router->addModuleResource('admin', 'CarTeam',          '/carTeam');
    $router->addModuleResource('admin', 'Yard',             '/yard');
    $router->addModuleResource('admin', 'Ship',             '/ship');

    $router->addModuleResource('admin', 'BaseStat',         '/baseStat');
    $router->addModuleResource('admin', 'Acl',              '/acl');
    $router->addModuleResource('admin', 'Account',          '/account');

    $router->addModuleResource('admin', 'OrderDetail',      '/orderDetail');
    $router->addModuleResource('admin', 'LogisticDetail',   '/logisticDetail');
    $router->addModuleResource('admin', 'OrderSuper',       '/ordersuper');

    //api
    $router->addModuleResource('admin', 'ApiIndex',            '/api/');

    $router->addModuleResource('admin', 'ApiAgent',            '/api/agent');
    $router->addModuleResource('admin', 'ApiCarTeam',          '/api/carTeam');
    $router->addModuleResource('admin', 'ApiYard',             '/api/yard');
    $router->addModuleResource('admin', 'ApiShip',             '/api/ship');

    $router->addModuleResource('admin', 'ApiEventStatistic',   '/api/es');
    $router->addModuleResource('admin', 'ApiAcl',              '/api/acl');
    $router->addModuleResource('admin', 'ApiAccount',          '/api/account');

    $router->addModuleResource('admin', 'ApiOrderDetail',      '/api/orderDetail');
    $router->addModuleResource('admin', 'ApiLogisticDetail',   '/api/logisticDetail');
    $router->addModuleResource('admin', 'ApiOrderSuper',       '/api/ordersuper');
    $router->addModuleResource('admin', 'ApiBasicStatic',      '/api/basicstatic');

    //移植前台
    $router->addModuleResource('admin', 'FeOrder',             '/api/order');
    $router->addModuleResource('admin', 'FeApiCarteamOrder',   '/api/carteam/order');
    $router->addModuleResource('admin', 'FeCarteamOrder',      '/carteam/order');
    $router->addModuleResource('admin', 'FeApiCity',           '/api/city');

    return $router;
} );


$di->setShared('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->adminViewsDir);

    $view->registerEngines(array(
        '.tpl' => function ($view, $di) use ($config) {

            $engine = new SmartyEngine($view, $di);

            $engine->setOptions(array(
                'compile_dir' => $config->application->adminCacheDir,
                'config_dir' => $config->application->libraryDir.'Smarty/config/admin',
                'plugins_dir' => $config->application->libraryDir.'Smarty/plugins',
                'template_dir' => $config->application->adminViewsDir,
                'left_delimiter' => '{%',
                'right_delimiter' => '%}',
				'force_compile' => true, // 测试环境强制编译，生产环境请去掉
            ));

            return $engine;
        },
    ));

    return $view;
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
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


/**
 * Start the session the first time some component request the session service
 */
$di['session'] = function () {

    $session = new SessionAdapter(array(
        'path' => "tcp://127.0.0.1:6379?weight=1",
        'lifetime' => 3600,
    ));

    $session->start();


    return $session;
};
