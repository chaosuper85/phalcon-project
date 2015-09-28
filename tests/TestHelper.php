<?php

use Phalcon\DI;
use Phalcon\DI\FactoryDefault;

use Phalcon\Loader;
use Phalcon\DiInterface;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;

use Library\Log\Logger;
use Library\Log\Log_Abstract;

use Library\Cache\Cache;

use Phalcon\Events\Manager as EventsManager;

use Library\Helper\AesCrypt;

use Library\Helper\Security ;


    
ini_set('display_errors',1);
error_reporting(E_ALL);


define('ROOT_PATH', __DIR__);

define('VENDOR_PATH', __DIR__ . '/../vendor');
define('APP_PATH', __DIR__ . '/../apps');

define('CONFIG_DIR',  APP_PATH . '/config');
define('LIBRARY_DIR', APP_PATH . '/library/');
define('SERVICES_DIR', APP_PATH . '/services/');
define('MODELS_DIR', APP_PATH . '/models/');

define('LOG_DIR',  APP_PATH . '/var/logs/www/');

set_include_path(
    ROOT_PATH . PATH_SEPARATOR . get_include_path()
);

// required for phalcon/incubator
include VENDOR_PATH . "/autoload.php";



// use the application autoloader to autoload the classes
// autoload the dependencies found in composer
$loader = new \Phalcon\Loader();

$loader->registerDirs(array(
    ROOT_PATH,
    MODELS_DIR
));

$loader->registerNamespaces(array(
    'Library' => LIBRARY_DIR,
    'Services' => SERVICES_DIR,
    'Phalcon' => VENDOR_PATH . '/phalcon/incubator/Library/Phalcon'
));


$loader->register();

$di = new FactoryDefault();
DI::reset();


//======================================================================================================
/**
 * 初始化所有配置
 */
$configDir = CONFIG_DIR;

$filesnames = scandir( $configDir );

foreach ($filesnames as $name) {
    $locate = strpos($name, '.');
    if( $locate ){
        $config = include $configDir."/".$name;
        $name = substr($name, 0, $locate);
        $di->set($name, $config);

    }
}


$di->set('security', function () {

    $security = new Security();

    // Set the password hashing factor to 12 rounds
    $security->setWorkFactor(12);

    return $security;
}, true);



/*
 * 初始化db
 */
$di->set('db', function() use ($di){

    $eventsManager = new EventsManager();

    //Listen all the database events
    $eventsManager->attach('db', function($event, $connection) {
        if ($event->getType() == 'beforeQuery') {
            Logger::info($connection->getSQLStatement());
        }
    });

    $connection = new DbAdapter(array(
        "host" => $di->get('config')->database->host,
        "username" => $di->get('config')->database->username,
        "password" => $di->get('config')->database->password,
        "dbname" => $di->get('config')->database->dbname
    ));

    //Assign the eventsManager to the db adapter instance
    $connection->setEventsManager($eventsManager);

    return $connection;
});

/*
 * 初始化log
 */
$logDIR = LOG_DIR;

$ret = Logger::init(Logger::LOG_FILE, Log_Abstract::LL_TRACE ,   $logDIR);


/*
 * 初始化aes对称加密解密算法
 */
$di->set('aes', function ()  use ($di) {
    $aes = new AesCrypt( $di->get('constant')->TOKEN_CRYPT_KEY );
    return $aes;
});



/*
 * cache服务
 */
$di['cache'] = function () {
    return new Cache();
};

/*
 *
 * 加载 services下面的所有逻辑
 *
 */
$dataServiceDir = SERVICES_DIR.'/DataService';
$serviceDir = SERVICES_DIR.'/Service';

$filesnames = scandir( $dataServiceDir );

foreach ($filesnames as $name) {

    $locate = strpos($name, '.');
    if( $locate ){
        $name = substr($name, 0, $locate);

        $di[$name] = function () use ( $di, $name ) {
            $className = "\\Services\\DataService\\{$name}";
            return new $className( $di );
        };
    }
}

$filesnames = scandir( $serviceDir );

foreach ($filesnames as $name) {

    $locate = strpos($name, '.');
    if( $locate ){
        $name = substr($name, 0, $locate);

        $di[$name] = function () use ( $di, $name ) {
            $className = "\\Services\\Service\\{$name}";
            return new $className( $di );
        };

    }
}

// add any needed services to the DI here

DI::setDefault($di);