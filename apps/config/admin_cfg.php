<?php

return  new Phalcon\Config( array(
    //cache缓存时间
    'CACHE_TIME' => [
        'EVENT_TIMES' => rand(240,360),
    ],


    'ADMIN_ACTION_TYPE' => [    //  事件类型
        'LOGIN' => 1,
        'LOGOUT' => 2,
        'ASSIGN_ORDER_AUTO' => 3,
        'ASSIGN_ORDER_SUPER' => 4,
        'CHANGE_ORDER_SUPER' => 5,
        'QUERY_ORDER_SUPER' => 6,

        'YARD_SAVE' => 7,  // 维护堆场信息
        'YARD_LOCATION_SAVE' => 8, // 维护堆场location
        'IMPORT_DRIVER' => 9,
        'AUDIT_CARTEAM' => 10,
        'AUDIT_AGENT' => 11,
        'FORGET_PWD' => 13,  // 密码找回
        'CHECK_SMS' => 14,  // 验证短信
        'ADMIN_ADD_EMP'=> 15, // 管理员 同意添加员工

        'OP_SHIPCOMPANY'=> 16,
        'OP_YARD'=> 17,
        'OP_SHIPNAME'=> 18,

        'AUDIT' => 100,

        'CREATE_USER' => 1001,
        'CREATE_GROUP' => 1002,
        'ADD_GROUP_USER' => 1003,
        'ADD_GROUP_ACL' => 1004,

        'QUERY_USER_LOG' => 1102,
        'QUERY_AUDIT' => 1103,
        'DUMP_WWW_USER' => 1104,

        'ALTER_USER' => 1201,
        'ALTER_WWW_USER' => 1202,
        'ALTER_GROUP' => 1203,

        'DEL_USER' => 1301,
        'DEL_GROUP' => 1302,
        'DEL_GROUP_USER' => 1303,
        'DEL_WWW_USER' => 1305,
    ],

    'ADMIN_ACTION_TYPE_NAME' => [
        1 => '登陆',
        2 => '登出',

        9=> '导入司机' ,
        10=> '审核车队' ,
        11=> '审核货代' ,

        16=> '操作船公司' ,
        17=> '操作堆场' ,
        18=> '操作船名' ,

        1001 => '创建后台用户',
        1002 => '创建用户组',
        1003 => '增加组内用户',
        1004 => '增加组内权限',

        1102 => '查询后台用户操作历史',
        1103 => '查询WWW用户审核信息',
        1104 => '导出WWW账户到excl',

        1201 => '修改后台用户',
        1202 => '修改WWW用户',
        1203 => '修改用户组',

        1301 => '删除后台用户',
        1302 => '删除用户组',
        1303 => '删除用户组内用户',
        1305 => '删除WWW用户',
    ],

    //  事件目标角色类型
    'ADMIN_ACTION_TARGET_TYPE' => [
        'ORDER' => 1,
        'BOX' => 2,
        'USER' => 101,
        'WWW_USER' => 102,
        'GROUP' => 103,
    ],

    'ADMIN_ACTION_TARGET_TYPE_NAME' => [
        1 => '订单操作',
        2 => '集装箱',

        101 => '后台用户',
        102 => 'WWW用户',
        103 => '后台用户组',
    ],

    //  事件类型
    'EVENT_STATISTIC_TYPE' => [
        'LOGIN' => 1,
        'LOGOUT' => 2,
        'SMS_SEND' => 3,

    ],

    'EVENT_STATISTIC_TYPE_NAME' => [
        1 => '登陆',
        2 => '登出',
        3 => '短信',
    ],


    ## 建议admin强制出图片验证码
    'VERIFY' => [
        'email' => 'emails.password',
        'table' => 'password_resets',
        'expire' => 60,
        'COUNT' => 3,   //验证码错误次数
    ],

    'ERR_CODE' => [
        'NO_LOGIN' => -2,   //前台要求独一无二
    ],
    'NO_LOGIN_URL' => [
        '/login',
        '/login/',
        '/',
        '/index',
        '/index/',
        '/api/getCap',
        '/api/dologin',
        '/favicon.ico',
    ],

    'COOKIE_EXPIRE_TIME' => 86400,
    'LOGIN_SESSION_TIME' => 3600,

    //后台用户状态
    'USER_STATUS' => [
        'ENABLE' => 1,
        'DISABLE' => 2,
        'DELETE' => 3,
    ],

    //后台权限相关
    'ACL_ROLE_TYPE' => [
        'ACL_USERS' => 1,   //是用户组还是用户的权限
        'ACL_GROUP' => 2,   //是用户组还是用户的权限
        'ROOT_NAME' => 'admin',
        'ROOT_ID'   => 1,
        'SYS_ID'    => 0,
        //'ROOT_ID'   => -1,
    ],



    //权限模板
    'POWER_TPL' => [
        'CARTEAM' => -1,
        'AGENT'   => -2,
        'DRIVER'  => -3,
        'ORDER_SUPER' => 14,
    ],

    //申诉单
    'tickets' => [
        'status_start' => 1,
        'status_audit' => 2,
        'status_end'   => 3,
        'result_pass'  => 4,
        'result_reject'=> 5,
        'msg_pass' => '申诉生效,已取消该用户的认证',
        'msg_reject'  => '申诉已被驳回',
        'msg_invalid' => '申诉已过期',
    ],


    //用户分组相关
    'GROUP' => [
        'ASSIGN_TRUE' => 1, //admin_usr_group记录是否有效
        'ASSIGN_FALSE' => 0,//admin_usr_group记录是否有效
    ],

    'ORDER_SUPER' => [
        'NORMAL' => 1,
        'CHANGE' => 2,
    ],

    //事务错误
//    'err_work' => [
//        'new_com'=>'创建企业失败',
//        'admin_pass'=>'管理员审批通过失败',
//        'lack_audit'=>'缺少审核信息',
//        'dim_pic' => '照片不够清晰',
//        'lack_seal' => '缺少公章',
//        'appeal_reject' => '申诉失败，信息不全',
//        'audit_err' => '审核出错',
//    ],
    //车队，货代相关错误
//    'err_usr' => [
//        'status'=>'用户认证级别修改失败',
//        'enterprise'=>'用户所属企业设置失败',
//        'no_usr'=>'没有此用户',
//    ],

//    'work' => [],

//    //用户类型
//    'usrtype_carteam' => 1,
//    'usrtype_cargoAgent' => 2,
//    'usrtype_driver' =>3,

//    //公司企业类型
//    'comtype_carteam' => 1,
//    'comtype_angent' => 2,
));
