<?php

use Phalcon\Mvc\Application;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('date.timezone','Asia/Shanghai');

define('VENDOR_DIR',  __DIR__ . '/../vendor');
define('CONFIG_DIR',  __DIR__ . '/../apps/config');


try {


    require VENDOR_DIR.'/autoload.php';

    $config = require CONFIG_DIR. '/config.php';
    /**
     * Include services
     */
    require CONFIG_DIR.'/modules/www/services_www.php';

    /**
     * Handle the request
     */
    $application = new Application();


    /**
     * Include modules
     */
    require CONFIG_DIR.'/modules/www/modules_www.php';

    /**
     * Assign the DI
     */
    $application->setDI($di);

    echo $application->handle()->getContent();

} catch (Phalcon\Exception $e) {
    echo $e->getMessage();
} catch (PDOException $e) {
    echo $e->getMessage();
}