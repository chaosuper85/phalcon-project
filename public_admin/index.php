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
    require CONFIG_DIR.'/modules/admin/services_admin.php';

    /**
     * Handle the request
     */
    $application = new Application();

    /**
     * Assign the DI
     */
    $application->setDI($di);

    /**
     * Include modules
     */
    require CONFIG_DIR.'/modules/admin/modules_admin.php';

    echo $application->handle()->getContent();

} catch (Phalcon\Exception $e) {
    echo $e->getMessage();
} catch (PDOException $e) {
    echo $e->getMessage();
}
