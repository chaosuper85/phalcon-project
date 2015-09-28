<?php

namespace Modules\www;

use Library\Helper\StringHelper;
use Phalcon\Loader;
use Phalcon\DiInterface;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\View;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\ModuleDefinitionInterface;

use Phalcon\DI\FactoryDefault;

use Library\Log\Logger;
use Library\Log\Log_Abstract;

use Library\Cache\Cache;

use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher as Dispatcher;

use Library\Helper\AesCrypt;

use Library\Helper\Security;

use Plugin\wwww\UserPlugin;


class Module implements ModuleDefinitionInterface
{
    /**
     * Registers the module auto-loader
     */
    public function registerAutoloaders(DiInterface $di = null)
    {



    }

    /**
     * Registers the module-only services
     *
     * @param Phalcon\DI $di
     */
    public function registerServices(DiInterface $di = null)
    {

        /**
         * 初始化所有配置
         */
        $configDir = __DIR__ . "/../../config";

        $filesnames = scandir($configDir);

        foreach ($filesnames as $name) {
            $locate = strpos($name, '.');
            if ($locate) {
                $configName = include $configDir . "/" . $name;
                $name = substr($name, 0, $locate);
                $di->set($name, $configName);

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
        $di->set('db', function () use ($di) {

            $envName =  'config-'.$di->get('config')->application_mode;
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

            $connection = new DbAdapter(array(
                "host" => $di->get($envName)->database->host,
                "username" => $di->get($envName)->database->username,
                "password" => $di->get($envName)->database->password,
                "dbname" => $di->get($envName)->database->dbname,
                'options' => array(
                    \PDO::ATTR_TIMEOUT => 5
                )
            ));

            //Assign the eventsManager to the db adapter instance
            $connection->setEventsManager($eventsManager);

            return $connection;
        });


        $di->set('db_slave', function () use ($di) {
            $eventsManager = new EventsManager();
            //Listen all the database events
            $eventsManager->attach('db', function ($event, $connection) {
                if ($event->getType() == 'beforeQuery') {
                    Logger::info($connection->getSQLStatement());
                }
            });

            $connection = new DbAdapter(array(
                "host" => $di->get('config')->database_slave->host,
                "username" => $di->get('config')->database_slave->username,
                "password" => $di->get('config')->database_slave->password,
                "dbname" => $di->get('config')->database_slave->dbname,
                'options' => array(
                    \PDO::ATTR_TIMEOUT => 5
                )
            ));

            $connection->setEventsManager($eventsManager);

            return $connection;
        });


        /*
         * 初始化log
         */
        $logDIR = __DIR__ . '/../../var/logs/www';

        $ret = Logger::init(Logger::LOG_FILE, Log_Abstract::LL_TRACE, $logDIR);


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

            //Attach a listener
            $eventsManager->attach("dispatch", function ($event, $dispatcher, $exception) {

                //The controller exists but the action not
                if ($event->getType() == 'beforeNotFoundAction') {
                    $dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'show404'
                    ));
                    return false;
                }

                //Alternative way, controller or action doesn't exist
                if ($event->getType() == 'beforeException') {
                    switch ($exception->getCode()) {
                        case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward(array(
                                'controller' => 'index',
                                'action' => 'show404'
                            ));
                            return false;
                    }
                }
            });

            $dispatcher = new Dispatcher();
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
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
        $EventsDir = __DIR__ . "/Events";

        $filesnames = scandir($dataServiceDir);

        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if ($locate) {
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ($di, $name) {
                    $className = "\\Services\\DataService\\{$name}";
                    return new $className();
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

        $filesnames = scandir($serviceDir);

        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if ($locate) {
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ($di, $name) {
                    $className = "\\Services\\Service\\{$name}";
                    return new $className();
                };

            }
        }

        $filesnames = scandir($validationDir);

        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if ($locate) {
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ($di, $name) {
                    $className = "\\Modules\\www\\Validation\\{$name}";
                    return new $className();
                };

            }
        }

        $filesnames = scandir($EventsDir);

        foreach ($filesnames as $name) {

            $locate = strpos($name, '.');
            if ($locate) {
                $name = substr($name, 0, $locate);

                $di[$name] = function () use ($di, $name) {
                    $className = "\\Modules\\www\\Events\\{$name}";
                    return new $className();
                };
            }
        }


    }

}