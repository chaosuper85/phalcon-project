<?php

return new \Phalcon\Config(array(


    ##  local staging  prod
    ##公共的配置放这个文件 ，  如果  local staging  prod 的配置不一样 ， 比如 mysql和队列配置放在  config-local.php
    'application_mode'         => 'staging',



    'smarty'  => array(
        'compile_dir' => __DIR__ . '/../var/cache/www/',
        'config_dir' => __DIR__ . '/../library/Smarty/config/www',
        'plugins_dir' => __DIR__ . '/../plugin/Smarty/plugins',
        'template_dir' => __DIR__ . '/../modules/www/views/',
        'left_delimiter' => '{%',
        'right_delimiter' => '%}',
        'force_compile' => true, // 测试环境强制编译，生产环境请去掉
    ),

    'application' => array(
        'tempDir' => __DIR__ . '/../var/temp/',

        'cacheDir' => __DIR__ . '/../var/cache/www/',
        'adminCacheDir' => __DIR__ . '/../var/cache/admin/',

        'viewsDir' => __DIR__ . '/../modules/www/views/',
        'apiViewsDir' => __DIR__ . '/../modules/api/views/',
        'adminViewsDir' => __DIR__ . '/../modules/admin/views/',

        'controllersDir' => __DIR__ . '/../Controllers/',
        'logicDir' => __DIR__ . '/../logic/',
        'modelsDir' => __DIR__ . '/../models/',
        'libraryDir' => __DIR__ . '/../library/', 
        'pluginsDir' => __DIR__ . '/../plugin/', 
        'baseUri' => '/'
    ),

    //  不需要登录的url
    'nologin_url' => [
        '/',
        '/index',
        '/index/login',
        '/index/checkExist',
        '/index/findPassword',
        '/index/agreement',
        '/index/index',
        '/index/findPwd',

        '/api/index/logout',

        '/account/uploadPic',
        '/user/register',
        '/user/findPassword',

        '/api/user/changePwd',
        '/api/index/do_login',
        '/api/user/do_register',
        '/api/user/getCap',
        '/api/user/sendSms',
        '/api/user/validateSmsCode',
        '/api/index/checkExist',
        '/api/index/checkMobileExist',
        '/api/user/checkregister',


        // todo
        "/agent/index",
        "/employee/index",
        "/company/",

        //app
        "/appuser/login",
        "/appuser/sendCode",

        "/testlocate/",
        "/testlocate/add",
        "/testlocate/getLocation",


        "/test"

    ] ,



    // 车队登录用户能操作的URL
    'login_as_carteam' => [
        '/index/do_carteam/',
    ] ,

    // 货代登陆用户能操作的URL
    'login_as_freight' => [
        '/index/do_freight/',
    ] ,


    //被csrf保护的url 的post方法必须带 csrf-token 参数， 且必须和session中的一致
    'csrf_protect_url' => [
    '/index/testCsrf',
] ,



));
