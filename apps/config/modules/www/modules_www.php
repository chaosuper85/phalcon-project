<?php
/**
 * Register application modules
 */

$application->registerModules(array(
    'www' => array(
        'className' => 'Modules\www\Module',
        'path' => __DIR__ . '/../../../modules/www/Module.php'
    )
));
