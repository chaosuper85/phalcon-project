<?php

return new \Phalcon\Config(array(

    //  staging 测试环境db   :   mysql -h 123.59.59.233 -P 3306 -u root -pwork       database : phalcon
    'database' => array(
        'adapter'  => 'Mysql',
        'host'     => '127.0.0.1',
        'username' => 'root',
        'password' => 'Passw0rd',
        'dbname'     => 'phalcon',
    ),

    'database_slave' => array(
        'adapter'  => 'Mysql',
        'host'     => '127.0.0.1',
        'username' => 'root',
        'password' => 'Passw0rd',
        'dbname'     => 'phalcon',
    ),



));


