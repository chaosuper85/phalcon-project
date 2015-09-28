<?php

/**
 * 对外系统的相关的配置
 */

/** @noinspection PhpUndefinedNamespaceInspection */
return new \Phalcon\Config(array(

    // 短信相关的配置
    'sms_config' => [
        //主要用来,短信验证,通知
        'single_sdk' => [
            //网关地址
            'gwUrl' => 'http://sdk999ws.eucp.b2m.cn:8080/sdk/SDKService',
            //序列号,请通过亿美销售人员获取
            'serialNumber' => '9SDK-EMY-0999-JEVSS',
            //密码,请通过亿美销售人员获取
            'password' => '601462',
            //登录后所持有的SESSION KEY，即可通过login方法时创建
            'sessionKey' => '123456',
            //连接超时时间，单位为秒
            'connectTimeOut' => '2',
            //远程信息读取超时时间，单位为秒
            'readTimeOut' => '10',
            //可选，代理服务器地址，默认为 false ,则不使用代理服务器
            'proxyhost' => false,
            //可选，代理服务器端口，默认为 false
            'proxyport' => false,
            //可选，代理服务器用户名，默认为 false
            'proxyusername' => false,
            //可选，代理服务器密码，默认为 false
            'proxypassword' => false,
        ],
        //主要用来，营销群发
        'multi_sdk' => [
            //网关地址
            'gwUrl' => 'http://sdk4report.eucp.b2m.cn:8080/sdk/SDKService',
            //序列号,请通过亿美销售人员获取
            'serialNumber' => '6SDK-EMY-6688-KJWON',
            //密码,请通过亿美销售人员获取
            'password' => '223656',
            //登录后所持有的SESSION KEY，即可通过login方法时创建
            'sessionKey' => '123456',
            //连接超时时间，单位为秒
            'connectTimeOut' => '2',
            //远程信息读取超时时间，单位为秒
            'readTimeOut' => '10',
            //可选，代理服务器地址，默认为 false ,则不使用代理服务器
            'proxyhost' => false,
            //可选，代理服务器端口，默认为 false
            'proxyport' => false,
            //可选，代理服务器用户名，默认为 false
            'proxyusername' => false,
            //可选，代理服务器密码，默认为 false
            'proxypassword' => false,
        ],

    ],

    'smsbackup' => [
        'DEBUG'             =>  false,                                                  // 是否开启DEBUG,默认不开启（真实发送短信）
        'SEND_MS_URL'       =>  'https://sms-api.luosimao.com/v1/send.json',            // 发送短信的接口
        'QUERY_BALANCE_URL' =>  'https://sms-api.luosimao.com/v1/status.json',          //查询余额
        'API_KEY'           =>  'api:key-9ce73c4824af5752a68bd6a9085e3999'              // 接口秘钥
    ],

    'qiniu' =>  [
        'accessKey' => 'jU4wipkkaxqfrcsgyZ2lcrNuAiXNIu1WPZTwxAkw',
        'secretKey' => '1qDgbngmiAY9-OAp57snQ5BWncHBkpcaQWgIifyE',
    ],

    //基站定位-基站云 jizhanyun.com
    'cell_track' => [
        'url' => 'http://www.jizhanyun.com/api/test.php',
        'user_id' => 209,
        'apikey' => '51c222e24d1590b87caf46938b91146d',
        //'mnc' => 0, //移动还是联通好
        //'lac' => 20857,
        //'cell' => 59051,
        'ishex' => 10,
        'cityinfo' => 1,    //增加显示城市省份
    ],

    //andriod和ios的消息推送使用jpush的配置
    'jpush' =>  [
        # 'app_key' => '9d5eab400ac9390706e9b51d',
        # 'master_secret' => '8737928e36dedaadcca8235f',

        'app_key' => 'cc6f6765448afebcca36d6a4',
        'master_secret' => 'f0fce83699e2456d80f7789d',
    ],

    //offie-excel
    'excel' => [
        'ver' => '2007',

        'carteam' => [
            'form_title' => '车队用户一览表',
            'file_name'  => '车队员工表-'.time(),
            'seek_x' => 'B',
            'seek_y' => 3,

            'uid' => '用户编号',
            'id' => '车队编号',
            'teamName' => '车队名称',
            'mobile' => '注册手机号',
            'status' => '车队状态',
            'teamPic' => '车队审核图片',
            'regist_time' => '注册时间',
            'regist_version' => '版本号',
            'regist_platform' => '注册平台',
            'ownerName' => '车队拥有者',
            'status' => '状态',
            'audit_status' => '审核状态',
            'telephone_number' => '座机',
            'username' => '帐号名称',
        ],

        'agent' => [
            'form_title' => '货代用户一览表',
            'file_name'  => '货代员工表-'.time(),
            'seek_x' => 'B',
            'seek_y' => 3,

            'id' => '车队编号',
            'userid' => '用户编号',
            'username' => '注册用户名',
            'mobile' => '注册手机号',
            'status' => '货代状态',
            'regist_platform' => '注册平台',
            'regist_version' => '版本',
            'username' => '帐号名称',
            'audit_status' => '审核状态',
            'telephone_number' => '座机',
            'regist_time' => '注册时间',
            'unverify_enterprisename' => '企业名称',

        ],

        'driver' => [
            'form_title' => '司机用户一览表',
            'file_name'  => '司机用户表-'.time(),
            'seek_x' => 'B',
            'seek_y' => 3,

            'driver_name'    => '司机名',
            'id_number'      => '身份证号',
            'car_number'     => '车牌号',
            'drive_number'   => '行驶证号',
            'industry_auth'  => '从业资格证',
            'car_trans_auth' => '车辆运营证',

            '手机号'   =>  'mobile',
            "司机名"   =>  'driver_name',
            "身份证号" => 'id_number',
            "车牌号"   => 'car_number',
            "行驶证号" => 'drive_number',
            "从业资格证" => 'industry_auth',
            "车辆营运证" => 'car_trans_auth',
        ],

        'shipCom' => [
            'form_title' => '船公司一览表',
            'file_name'  => '船公司表-'.time(),
            'seek_x' => 'B',
            'seek_y' => 3,

            'china_name'    => '名称',
            'company_code'      => '代码',
//            'eng_name'     => '英文名',
//            'drive_number'   => '行驶证号',
//            'industry_auth'  => '从业资格证',
//            'car_trans_auth' => '车辆运营证',

            '名称'   =>  'china_name',
            '代码'   =>  'company_code',
            //'英文名'   =>  'eng_name',
        ],

    ],

//    'excel_tpl' => [
//        'carteam' => 1,
//        'agent'   => 2,
//    ],


));