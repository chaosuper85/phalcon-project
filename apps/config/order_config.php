<?php

return new \Phalcon\Config(array(


    'order_status_enum'         => [
        'TO_CONFIRM'        => '1',
        'TO_ASSIGN'         => '2',
        'TO_TIXIANG'        => '3',
        'TO_CHANZHUANG'     => '4',
        'TO_LUOXIANG'       => '5',
        'TO_YUNDI'          => '5',
        'TO_COMMENT'        => '6',
        'TRADE_SUCCESS'     => '7',
        'TRADE_CLOSE'       => '8',
    ],


    'order_status'              => [
        '1' => '待确认',
        '2' => '待分派',
        '3' => '待提箱',
        '4' => '待产装',
        '5' => '待运抵',
        '6' => '待评价',
        '7' => '交易成功',
        '8' => '交易关闭',
    ],

    'box_status'                => [
        '1' => '待提箱',
        '2' => '待产装',
        '3' => '待运抵',
        '4' => '已落箱，待运抵',
        '5' => '已运抵',
        '6' => '取消',
        '100' => '司机app产装完成，待后台车队确认',
    ],

    'box_status_enum'            => [
        'TO_TIXIANG'        => '1',
        'TO_CHANZHUANG'     => '2',
        'TO_YUNDI'          => '3',
        'LUOXIANG'          => '4',
        'YUNDI'             => '5',
        'CANCEL'            => '6',

        'APP_CHANZHUANG_COMPLETE'   => '100',   //司机app产装完成，待后台车队确认
    ],

    'order_comment_type'          => [
        '1' => '派车及时',
        '2' => '提箱及时',
        '3' => '产装及时',
        '4' => '落箱及时',
        '5' => '司机服务态度',
    ],

    'import_type'          => [
        '1' => '出口',
        '2' => '进口',
    ],


    'ordertimeline_type_enum'   => [
        'IS_CANCELED'   => '0',
        'IS_GOT'        => '1',
        'IS_PRODUCTED'  => '2',
        'IS_DROPED'     => '3',
        'IS_ARRIVED'    => '4',
    ],

    'ordertimeline_type'  => [
        '0' => '被退载',
        '1' => '提箱',
        '2' => '产装',
        '3' => '落箱',
        '4' => '运抵',
    ],

    //分派状态枚举
    'assign_status'  => [
        '1' => '待提箱',
        '2' => '待产装',
        '3' => '待运抵',
        '4' => '已落箱，待运抵',
        '5' => '已运抵',
        '6' => '取消',
    ],

    'assign_status_enum'  => [
        'TO_TIXIANG'    => '1',
        'TO_CHANZHUANG' => '2',
        'TO_YUNDI'      => '3',
        'LUOXIANG'      => '4',
        'YUNDI'         => '5',
        'CANCEL'        => '6',

        'APP_CHANZHUANG_COMPLETE'   => '100',   //司机app产装完成，待后台车队确认
    ],

    //产装地址状态
    'product_address_status'  => [
        '1' => '产装开始',
        '2' => '产装完成',
    ],

    //司机的工作状态枚举值
    'driver_work_status'  => [
        '1' => '空闲',
        '2' => '在途',
        '3' => '未知',
    ],


    // 货物箱型的定义
    'box_type_define'  => [
        '1' => '普货箱',
        '2' => '开顶箱', //以横向木质活动铺板(木条),另铺设帆布顶罩,多用以装运整体,粗重或大件机械
        '3' => '框架箱', //没有箱顶和两侧，其特点是从集装箱侧面进行装卸。以超重货物为主要运载对象，还便于装载牲畜，以及诸如钢材之类可以免除外包装的裸装货。
        '4' => '冷藏箱', //是一具有良好隔热、气密，且能维持一定低温要求，柜体有隔热性能, 柜门另一端装有冷冻机具,适用于各类易腐食品的运送、贮存的特殊集装箱。
        '5' => '危险品', //一般用于专门用来挂衣服的集装箱，分为绳挂和杠挂两种。这种集装箱的特点是，在箱内上侧梁上装有许多根横杆，每根横杆上垂下若干条皮带扣、尼龙带扣或绳索，成衣利用衣架上的钩，直接挂在带扣或绳索上。
        '6' => '罐箱',
    ],

    'box_type_enum'  => [
        'KAI_DING' => '1',
        'KUANG_JIA' => '2',
        'LENG_DONG' => '3',
        'GUA_YI' => '4',
    ],

    'order_list_pageSize' => 6,

    'car_manage_pageSize' => 6,

    # box timeline template(对应verify_ream_type)
    'box_timeline_template' => [
        'TIXIANG'           => '已上传箱号及铅封号',     # 已提箱
        'CHANZHUANG'        => '{address_detail}已完成产装，即将前往{yard_name}堆场落箱',     # 已产装
        'LUOXIANG'          => '已完成落箱，等待运抵报文',     # 已落箱
        'YUNDI'             => '提单号{tidan_code}已运抵，本次交易完成',    # 已运抵

        'ARRIVE_CHANZHUANG' => '车辆{car_number}已经到达{app_locate_content}',   # 到达产装地附近
        'ARRIVE_PORT'       => '车辆{car_number}已经到达{app_locate_content}',       # 已达到港口附近
        'TUIZAI' => '订单已被车队退载',
        'TUIZAICHONGJIAN' => '订单已被车队退载，重建订单的提单号为{tidan_code}',
    ],


    # push template
    'push_template' => [
        'TO_GET_TASK'           => '您有新的集装箱任务到达，请查看',
        'BOX_STATUS_CHANGED'    => '您运送的集装箱有变化，请查看',
        'ORDER_CANCELED'        => '有集装箱任务被取消，请查看',
        'TO_WEB'                => '点此查看详情',
        'TUIZAI'                => '订单被车队退载，查看详情',
        'TUIZAICHONGJIAN'       => '订单被车队退载，重建订单已完成，请查看',

        'BOX_INFO_CHANGED'      => '您运送的集装箱有变化，请查看',
    ],

    'push_action_type' => [
        'app',
        'web'
    ],

    'push_to_where' => [
        'TO_GET_TASK'           => 'detail',        # app详情
        'BOX_STATUS_CHANGED'    => 'detail',        # app详情
        'ORDER_CANCELED'        => 'completeList',  # app历史订单
        'BOX_INFO_CHANGED'      => 'detail',        # app详情

        'TO_WEB'                => 'toweb'          # 外部http url
    ],
    # end


    /*
     * 操作的事件type，1修改产装地址，2修改分派司机
     */
    'order_modify_log_type' => [
        'MODIFY_PRODUCT_ADDRESS' => '1',
        'MODIFY_ASSIGN_DRIVER' => '2',
    ],


    /*
     * box time line的一些定义
     */
    'verify_ream_type' => [
        'TIXIANG' => '1',               //已提箱
        'ARRIVE_CHANZHUANG' => '2',     //到达产装地附近
        'CHANZHUANG' => '3',            //已产装
        'ARRIVE_PORT' => '4',           //已到达港口附近
        'LUOXIANG' => '5',              //已落箱，待运抵
        'YUNDI' => '6',                 //已 运抵
    ],


    /**
     *  船公司 船名 堆场名 创建类型定义
     */
    'CREATE_TYPE' =>[
        'CHECKED' => 1, // 已经审核通过
        'WWW'     => 2, // www 用户创建待审核
    ],

    /**     订单修改事件 actionType = 17 时
     *      child_action_type 类型枚举定义
     */
    'ORDER_HISTORY_TARGET_ENUM' =>[
        1 => "增加产装地址",
        2 => "增加分派司机",
        3 => "增加箱号、铅封号",
        4 => "修改产装地址",
        5 => "修改分派司机",
        6 => "修改箱号铅封号",
        7 => "增加产装时间",
        8 => "修改产装时间",
        9 => "删除产装地址",
        10 => "删除产装时间",

    ],

    /**  订单修改事件 actionType = 17 时
     *   child_action_type 类型定义
     */
    'ORDER_HISTORY_TARGET_TYPE' => [
        "ADD_PRO_ADDRESS"       => 1,// "增加产装地址",
        "ADD_ASSIGN_DRIVER"     => 2,// "增加分派司机",
        "ADD_BOX_CODE_SEAL"     => 3,//"增加箱号、铅封号",
        "MODIFY_PRO_ADDRESS"    => 4,//"修改产装地址",
        "MODIFY_DRIVER"         => 5,// 修改分派司机
        "MPDIFY_BOX_CODE_SEAL"  => 6,//"修改箱号铅封号",
        "ADD_PRO_TIME"          => 7,// 增加产装时间，
        "UPDATE_PRO_TIME"       => 8,// 修改产装时间，
        "DEL_PRO_ADDRESS"       => 9,// 删除产装地址，
        "DEL_PRO_TIME"          => 10,// 删除产装时间，
    ],





));