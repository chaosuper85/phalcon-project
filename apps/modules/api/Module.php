<?php

namespace Modules\api;

use Phalcon\Loader;
use Phalcon\DiInterface;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;

use Phalcon\DI\FactoryDefault;

use Library\Log\Logger;
use Library\Log\Log_Abstract;
use Library\Helper\StringHelper;

use Library\Cache\Cache;
use Library\Helper\AesCrypt;

use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher as Dispatcher;

use Plugin\api\UserPlugin;


class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     */
    public function registerAutoloaders( DiInterface $di = null )
    {


    }

    /**
     * Registers the module-only services
     *
     * @param Phalcon\DI $di
     */
    public function registerServices( DiInterface $di = null )
    {
        /**
         * 初始化所有配置
         */
        $configDir = __DIR__ . "/../../config";

        $filesnames = scandir( $configDir );

        foreach ($filesnames as $name) {
            $locate = strpos($name, '.');
            if( $locate ){
                $config = include $configDir."/".$name;
                $name = substr($name, 0, $locate);
                $di->set($name, $config);

            }
        }


        /*
         * 初始化aes对称加密解密算法
        */
        $di->set('aes', function () use ($di) {
            $aes = new AesCrypt($di->get('constant')->TOKEN_CRYPT_KEY);
            return $aes;
        });


        /*
         * dispatch错误处理
        */
        $di->setShared('dispatcher', function () {

            //Create/Get an EventManager
            $eventsManager = new EventsManager();

            $t = new UserPlugin();
            $eventsManager->attach('dispatch', $t);

            $dispatcher = new Dispatcher();
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });


        /*
         * 初始化log
         */
        $ret = Logger::init(Logger::LOG_FILE, Log_Abstract::LL_TRACE ,  __DIR__.'/../../var/logs/api' );


        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di->set('db', function() use ($di){

            $eventsManager = new EventsManager();

            //Listen all the database events
            $eventsManager->attach('db', function($event, $connection) {
                if ($event->getType() == 'beforeQuery') {
                    $sql       = $connection->getSQLStatement();
                    $paramsArr = $connection->getSQLVariables();
                    StringHelper::buildSql( $sql,$paramsArr);
                    Logger::db(" 执行SQL :".$sql );
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
        $dataServiceDir = __DIR__ . "/../../services/DataService";
        $logicServiceDir = __DIR__ . "/../../services/LogicService";
        $serviceDir = __DIR__ . "/../../services/Service";
        $validationDir = __DIR__ . "/Validation";

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

        $filesnames = scandir($logicServiceDir);

        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if ($locate) {
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ($di, $name) {
                    $className = "\\Services\\LogicService\\{$name}";
                    return new $className();
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

        $filesnames = scandir( $validationDir );

        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if( $locate ){
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ( $di, $name ) {
                    $className = "\\Modules\\api\\Validation\\{$name}";
                    return new $className( $di );
                };

            }
        }


    }

}