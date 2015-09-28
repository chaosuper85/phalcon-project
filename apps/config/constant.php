<?php

return new \Phalcon\Config(array(

    'application_mode' => 'staging',

    'INVITE_STATUS'  =>[
         'INVITING' => 1, // 邀请中，等待 被邀请人 同意
         'AGREED'   => 2, // 同意 ，被邀请人 同意，
         'REJECTED' => 3, // 被拒绝
    ],

    'INVITE_TYPE'  =>[
        'SEARCH_INVITE' => 1, // 搜索邀请 ->1 对 1
        'LINK_INVITE'   => 2, // 链接邀请 -》
    ],

    'qr_code' => array(
        'content'  => 'null content',
        'size'     => 200,
        'padding' => 10,
        'errorCorrection' => 'high',
        'foreground_color'     => array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0),
        'bk_color'     => array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0),
        'label'     => '',
        'label_fontsize'     => 16,
        'img_type'     => 'png',
    ),

    'qiniuDomain'      => 'http://7xjljc.com2.z0.glb.qiniucdn.com/',

    'tempFile'         => '/tempFile',

    'TOKEN_CRYPT_KEY' => md5('123456'),

    'LOGIN_SESSION_SECONDS' => 86400,

    'INVITE_COOKIE_SECONDS' => 86400,

    'Register_Code' => '56xdd',

    'LOGIN_RECORD' => [
        'LOGIN_SUCCESS' =>  '1',
        'KICKED' =>  '2',
        'LOGIN_EXPIRED' =>  '3',
        'LOGOUT' =>  '4',
    ],

    // 用户类型 枚举 (将数字 转换 字符串)
    'userType_enum' =>array(
        1 => "carteam",
        2 => "freight_agent",
        3 => "driver"
    ),

    // 用户类型
    'usertype' =>array(
        'carteam' =>1 ,
        'freight_agent' =>2 ,
        'driver' =>3,

        1 => "车队",
        2 => "货代",
        3 => "司机"
    ),

    // 映射表 map to table
    'usertype_table_map' =>array(
        1 => "car_team_user",
        2 => "freightagent_user",
        3 => "tb_driver"
    ),

     // 平台
    'PLATFORM_ENUM' => array(
        1 => 'PC',
        2 => 'Android',
        3 => 'IOS',
        4 => 'H5',
        5 => 'WeChat',
    ),

    'boxType' =>[
        '20GP' => 1,
        '40GP' => 2,
        '40HQ' => 3,
        '45HQ' => 4,
    ],

    'boxType_Enum' =>[
        1 => '20GP',
        2 => "40GP",
        3 => "40HQ",
        4 => "45HQ",
    ],

    // 发送短信用途
    'SMS_TYPE' =>  [
        'REGISTER' => '0',     // 注册
        'CHANGE_PWD' => '1',   // 找回密码
        'APPLY_ADMIN' => '2',  // 申请管理员
        'CHANGE_MOBILE' => 3,  //换绑新手机
        'NORMAL_AUTH' => 4,    // 普通身份认证




        // app 端 通知
        'NOTICE_CARTEAM_ACCEPT'  => 100,
        'NOTICE_DRIVER_NEW_TASK' => 101,

    ],

    // 发送短信的 模板
    'SMS_PATTERN' =>[
        'DEFAULT' =>  '您的验证码是：%s,十分钟内有效。',// 默认
        "REGISTER" => '您此次注册的验证码是：%s,十分钟内有效。',// 注册
        "CHANGE_PWD" => '您正在找回密码，验证码是：%s,若不是本人操作，请忽略此短信。',// 找回密码
        "APPLY_ADMIN" => '您的验证码是：%s,十分钟内有效。',
        "CHANGE_MOBILE" => '您正在换绑手机号，验证码是：%s,十分钟内有效。',
        "NORMAL_AUTH"=> '您的验证码是：%s,十分钟内有效。',







        // 通知 app 端
        'NOTICE_CARTEAM_ACCEPT' => "您接到一份来自%s的陆运订单，请尽快登录箱典典系统确认",// 通知车队 接单
        'NOTICE_DRIVER_NEW_TASK'=> "您接到一份来自%s的装货信息，请打开箱典典APP查看",// 通知司机
    ],

    // 短信验证码redis key前缀
    'SMSCODE_REDIS_KEY_PREFIX'  => 'smscode_',

    // 客户端下发token name
    'CLINET_TOKEN'              => 'xdd-token',

    // 日志分隔符,便于分析日志
    'LOG_SEPORATER'               => '----------',

    // 日志名字确认符
    'LNS'                         => '///...///',


    // 平台类型
    'PLATFORM_TYPE' =>  [
        'PC' => 1,
        'ANDROID' => 2,
        'IOS' => 3,
        'H5' => 4,
        'WeChat' => 5
    ],

    'tempUploadFileDir' => '/Users/xdd/temp',

    //( 车队报价状态枚举:0，线上报价，1，编辑中报价，2，已下架报价，3，已删除报价)
    'CAR_TEAM_BID_STATUS' =>  [
        'ON_LINE' => '0',
        'EDITING' => '1',  // 保存并未上架
        'OFF_LINE' => '2',
        'DELETE' => '3',
    ],

    //  事件类型
    'ACTION_TYPE' => [
        'REG' => 0,  //  注册
        'LOGIN' => 1, // 登陆
        'BID_CREATE' => 2,
        'BID_EDIT' => 3,
        'PIC_CAPTCHA' => 4,  // 触发图片验证码
        'SMS_SEND' => 5,  // 发送短信
        'BID_QUERY' => 6,  //报价查询
        'BID_VIEWDETAIL' => 7,
        'ACCOUNT_EDIT' => 8,
        'BID_AUTHOR' => 9,
        'CARTEAM_AUTHOR' => 10,
        'EDIT_PWD' => 11,   //更改密码
        'LOGOUT' => 12,   // 登出
        'FORGET_PWD' => 13,  // 密码找回
        'CHECK_SMS' => 14,  // 验证短信
        'ADMIN_ADD_EMP'=> 15, // 管理员 同意添加员工
        'FREIGHT_CREATE_ORDER'=> 16, // 货代创建订单
        'CARTEAM_CONFIRM_ORDER'=>18, // 车队接单
        'ORDER_MODIFY_HISTORY'=> 17, // 车队修改订单的历史
        'ORDER_RECONSTRUCT'=>19, //订单退载重建
        'ORDER_TUIZAI' => 25,

        # 司机app相关
        'BOX_PRODUCT_COMPLETE'  => 20, //箱子产装完成，状态为100，需车队确认后才会更改为最终状态
        'BOX_DROP_COMPLETE'     => 21, //箱子落箱完成
        'BOX_ARRIVED'           => 22,    //箱子已运抵
        'ORDER_DROP_COMPLETE'   => 23, //订单落箱完成
        'ORDER_ARRIVED'         => 24, //订单已运抵
        'PUSH_DATA'             => 25,  //推送数据
        # end

        'APPEAL_AUDIT' => 100, //申诉审核
    ],

    'ACTION_TYPE_NAME' => [
        0 => '注册' ,
        1 => '登陆' ,
        2 => '添加报价',
        16 => '货代创建订单',
    ],




    //  事件角色类型
    'ACTION_REAM_TYPE' => [
        // 车队
        'CARTEAM' => 1,
        // 货代
        'CARGOER' => 2,
        // driver
        'DRIVER' => 3,
        'ORDER'  => 4,
        'SYSTEM_AUTO' => 5,
        'OTHER' => 6,
    ],

    //   事件角色类型名字
    'ACTION_REAM_TYPE_NAME' => [
        '2' => '货代',
        '1' => '车队',
        '3' => '司机',
        '4' => '订单',
        '5' => '系统自动',
        '6' => '其他',
        '7' => '集装箱',
        '8' => '产装地址',
        '9' => '产装时间',

    ],




    //   收到站内信时间期限
    'MSG_P2P_TIME' => 60,


    //   站内信一页显示条数
    'MSG_P2P_SHOWNUM_ONEPAGE' => 5,

    //   站内信保存时间
    'MSG_P2P_SAVE_TIME' => 7,


    //   个人对个人站内信类型
    'MSG_P2P_TYPE' => [
        'APPLY_FRIEND' => 1,    //申请好友
        'ASK_BID_PRICE' => 2,   // 询价
    ],


    //   个人对个人站内信类型
    'MSG_P2P_STATUS' => [
        'INIT' => 1,  // 初始化
        'READED' => 2, //  已经阅读
        'EXPED' => 3, // 已经过期
        'DELETED' => 4, //接受者 删除站内信
    ],

    //   合作好友关系形成的方式
    'USER_FRIENDSHIP_TYPE' => [
        'SEARCH' => 1,  // 搜索成为好友
        'INVITE_REGIST' => 2, //  邀请注册成为好友
    ],

    //合作好友关系状态
    'USER_FRIENDSHIP_STATUS' => [
        'APPLY' => 1,  // 申请中
        'REJECT' => 2,  // 拒绝
        'AGREE' => 3, //  同意  成为了合作好友
        'EXPED' => 4, // 已经过期
    ],

    //  cookie session有效时间

    'COOKIE_MAX_MIN' => '60',
    'SESSION_MAX_MIN' => '60',

    "USER_NAME"   =>[   // 用户 登录名 长度限制 4 -12
        "MIN_LEN" =>  4,
        "MAX_LEN" =>  12
    ],

    //车队/货代状态
    'USER_STATUS' => [
        'REGISTER'     => 1,
        'AUDIT_REJECT' => 2,
        'AUDITING'     => 3,
        'AUDIT_PASS'   => 4,
    ],
    'USER_STATUS_WORD' => [
        1 => '注册用户',
        2 => '审核驳回',
        3 => '待审核',
        4 => '审核通过',
    ],

    //账户状态
    'ACCOUNT_STATUS' => [
        'ENABLE'    => 1,
        'DISABLE'    => 2,
        'DELETED'   => 3,
    ],
    'ACCOUNT_STATUS_WORD' => [
        1 => '正常',
        2 => '已锁定',
        3 => '已删除',
    ],

    //司机状态
    'DRIVER_STATUS' => [
        'FREE' => 1,
        'WORK' => 2,
        'UNKNOW' => 3,
    ],

    //企业管理员审核级别
    'COM_STATUS' => [
        'REGISTER'     => 1,
        'AUDIT_REJECT' => 2,
        'AUDITING'     => 3,
        'AUDIT_PASS'   => 4,
    ],

    //堆场location位置类型
    'yard_location_type' => [
        'entrance' => 1,    //  进口
        'exit'     => 2,    //  出口
    ],

    //堆场车型信息
    'yard_car_type' => [
        'full_car'  => 1, //重车
        'empty_car' => 2, //空车
    ],

    //堆场角度类型
    'yard_degree_type' => [
        'center'    => 1, //中心
    ],

    //  重车 入口 中心
    //  开通的 码头城市 信息
    'citys' => [
        'tianjin' => [
            'name' => '天津',
            'extraFeeType' => [
                'tixiang' => '提箱',
                'yundi' => '运抵费',
                'dongjiangExtra' => '东疆附加费',
            ],
            'docks' => [
                'dongjiang' => [
                    'desc' => '需要附加费的码头',
                    'name' => '东疆码头',
                    // 报价公式
                    'extraFeePara' => [
                        'import' => [
                            'dongjiangExtra'
                        ],
                        'export' => [
                            'tixiang' ,'yundi' , 'dongjiangExtra'
                        ],
                    ],
                ],
                'beijiang' => [
                    'desc' => '不需要附加费的码头',
                    'name' => '北疆码头',
                    // 报价公式
                    'extraFeePara' => [
                        'import' => [
                            ''
                        ],
                        'export' => [
                            'tixiang' ,'yundi'
                        ],
                    ],
                ],
            ],
        ],
    ],





));
