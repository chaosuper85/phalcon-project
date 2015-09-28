<?php

namespace Modules\admin;

use Phalcon\Loader;
use Phalcon\DiInterface;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;

use Phalcon\DI\FactoryDefault;

use Library\Cache\Cache;
use Library\Log\Logger;
use Library\Log\Log_Abstract;
use Library\Helper\StringHelper;

use Plugin\admin\UserPlugin;

use Phalcon\Events\Manager as EventsManager;



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

        $ret = Logger::init(Logger::LOG_FILE, Log_Abstract::LL_TRACE ,  __DIR__.'/../../var/logs/admin' );

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
         * 初始化aes对称加密解密算法
         */
        $di->set('aes', function () use ($di) {
            $aes = new AesCrypt($di->get('constant')->TOKEN_CRYPT_KEY);
            return $aes;
        });

        

        /**
         * Plugin-服务
         */
        $di->set('dispatcher', function() use ($di) {
            $eventsManager = $di->getShared('eventsManager');
            $security = new UserPlugin($di);
            /**
             * We listen for events in the dispatcher using the Security plugin
             */
            $eventsManager->attach('dispatch', $security);

            $dispatcher = new \Phalcon\Mvc\Dispatcher();
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });



        /*
         *
         * 加载 services下面的所有逻辑
         *
         */
        $logicServiceDir = __DIR__ . "/../../services/LogicService";
        $dataServiceDir = __DIR__ . "/../../services/DataService";
        $serviceDir = __DIR__ . "/../../services/Service";
        $validationDir = __DIR__ . "/Validation";
        $EventsDir = __DIR__ . "/Events";

        $filesnames = scandir($logicServiceDir );
        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if( $locate ){
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ( $name ) {
                    $className = "\\Services\\LogicService\\{$name}";
                    return new $className(  );
                };
            }
        }

        $filesnames = scandir( $dataServiceDir );

        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if( $locate ){
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ( $name ) {
                    $className = "\\Services\\DataService\\{$name}";
                    return new $className(  );
                };
            }
        }

        $filesnames = scandir( $serviceDir );

        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if( $locate ){
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ( $name ) {
                    $className = "\\Services\\Service\\{$name}";
                    return new $className(  );
                };

            }
        }

        $filesnames = scandir( $validationDir );

        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if( $locate ){
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ( $name ) {
                    $className = "\\Modules\\admin\\Validation\\{$name}";
                    return new $className(  );
                };

            }
        }

        $filesnames = scandir($EventsDir);
        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if ($locate) {
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ($di, $name) {
                    $className = "\\Modules\\admin\\Events\\{$name}";
                    return new $className();
                };
            }
        }


    }

}