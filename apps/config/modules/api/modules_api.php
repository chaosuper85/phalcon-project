<?php
/**
 * Register application modules
 */

$application->registerModules(array(
    'api' => array(
        'className' => 'Modules\api\Module',
        'path' => __DIR__ . '/../../../modules/api/Module.php'
    )
));
