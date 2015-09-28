<?php

use Phalcon\DI\FactoryDefault\CLI as CliDI;
use Phalcon\CLI\Console as ConsoleApp;

use Library\Log\Logger;
use Library\Log\Log_Abstract;

use Phalcon\Events\Manager as EventsManager;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Library\Helper\StringHelper;
use Phalcon\Mvc\Model;


define('VERSION', '1.0.0');

// 使用CLI工厂类作为默认的服务容器
$di = new CliDI();

// 定义应用目录路径
define('APPLICATION_PATH', realpath(dirname(__FILE__)));

define('SERVICE_PATH', APPLICATION_PATH . '/../../services/');
define('LIB_PATH', APPLICATION_PATH . '/../../library/');
define('MODEL_PATH', APPLICATION_PATH . '/../../models/');
define('CONF_PATH', APPLICATION_PATH . '/../../config/');

define('VENDOR_PATH', APPLICATION_PATH.'/../../../vendor/' );

Model::setup( array(
    'notNullValidations' => false,
) );

/*
 * 自加载vendor下的第三方lib
 */
require VENDOR_PATH.'/autoload.php';

/**
 * 注册类自动加载器
 */
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    array(
        MODEL_PATH,
        APPLICATION_PATH . '/tasks'
    )
);

/**
 * 命名空间和路径映射
 */
$loader->registerNamespaces(array(
    'Services' => SERVICE_PATH,
    'Library' => LIB_PATH,
));

$loader->register();


// 加载配置文件（如果存在）
if ( is_readable(APPLICATION_PATH . '/config/config.php') ) {
    $config = include APPLICATION_PATH . '/config/config.php';
    $di->set('cli_config', $config);
}


/**
 * 初始化所有配置
 */
$configDir = CONF_PATH;

$filesnames = scandir( $configDir );

foreach ( $filesnames as $name ) {
    $locate = strpos($name, '.');
    if( $locate ){
        $config = include $configDir."/".$name;
        $name = substr($name, 0, $locate);
        $di->set($name, $config);

    }
}


/*
* 初始化db
*/
$di->set('db', function () use ($di) {

    $eventsManager = new EventsManager();

    //Listen all the database events
    $eventsManager->attach('db', function ($event, $connection) {
        if ($event->getType() == 'beforeQuery') {
            $sql       = $connection->getSQLStatement();
            $paramsArr = $connection->getSQLVariables();
            StringHelper::buildSql( $sql,$paramsArr);
            Logger::db("SQL:".$sql );
        }
    });

    $db_cfg = $di->get( 'config-'.$di['config']->application_mode )->database;
    $connection = new DbAdapter(array(
        "host" => $db_cfg->host,
        "username" => $db_cfg->username,
        "password" => $db_cfg->password,
        "dbname" => $db_cfg->dbname
    ));

    //Assign the eventsManager to the db adapter instance
    $connection->setEventsManager($eventsManager);

    return $connection;
});


/*
 *
 * 加载 services下面的所有逻辑
 *
 */
$serviceDir = SERVICE_PATH . 'Service';
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

$dataServiceDir = SERVICE_PATH . 'DataService';
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


$logicServiceDir = SERVICE_PATH . 'LogicService';
$filesnames = scandir( $logicServiceDir );

foreach ($filesnames as $name) {

    $locate = strpos($name, '.');
    if( $locate ){
        $name = substr($name, 0, $locate);

        $di[$name] = function () use ( $di, $name ) {
            $className = "\\Services\\LogicService\\{$name}";
            return new $className( $di );
        };

    }
}



// 创建console应用
$console = new ConsoleApp();
$console->setDI($di);

/*
 * 初始化log
 */
$logDIR = APPLICATION_PATH.'/../../var/logs/cli';
// create log dir if not exist
if (!file_exists($logDIR)) {
    mkdir($logDIR, 0777);
}

$ret = Logger::init(Logger::LOG_FILE, Log_Abstract::LL_TRACE ,   $logDIR);

/**
 * 处理console应用参数
 */
$arguments = array();
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

// 定义全局的参数， 设定当前任务及动作
define('CURRENT_TASK',   (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));



try {

    //使用文件锁，保证同一时刻一个任务只有一个instance在执行
    $strPath = __DIR__ . '/tmp/';
    if (!file_exists($strPath)) {
        mkdir($strPath, 0777);
    }

    $task = CURRENT_TASK . CURRENT_ACTION;
    $strLockFile = md5( $task );
    $strLockFile = $strPath . $strLockFile;
    $fh = fopen($strLockFile, 'ab');
    $lr = flock($fh, LOCK_EX | LOCK_NB);
    if ( defined('NO_LOCK') || $lr )
    {
        // 处理参数
        $console->handle($arguments);

        flock($fh, LOCK_UN);
    } else {
        Logger::warn("script excute fail, lock fail");
    }

    fclose($fh);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}