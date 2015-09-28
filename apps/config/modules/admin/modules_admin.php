<?php

/**
 * Register application modules
 */

$application->registerModules(
    array(
    'admin' => array(
        'className' => 'Modules\admin\Module',
        'path' => __DIR__ . '/../../../modules/admin/Module.php'
    )
));
