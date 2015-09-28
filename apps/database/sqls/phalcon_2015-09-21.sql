# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 123.59.59.233 (MySQL 5.6.26-log)
# Database: phalcon
# Generation Time: 2015-09-21 06:49:45 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table activity_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `activity_log`;

CREATE TABLE `activity_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action_type` int(11) NOT NULL DEFAULT '0',
  `child_action_type` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `reamId` varchar(64) COLLATE utf8_unicode_ci DEFAULT '',
  `reamType` int(11) DEFAULT NULL,
  `targetReamId` varchar(64) COLLATE utf8_unicode_ci DEFAULT '',
  `targetReamType` int(11) DEFAULT NULL,
  `platform` tinyint(4) DEFAULT '0',
  `version` varchar(24) COLLATE utf8_unicode_ci DEFAULT '',
  `deviceId` varchar(256) COLLATE utf8_unicode_ci DEFAULT '',
  `jsonContent` varchar(1024) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table admin_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_access`;

CREATE TABLE `admin_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '的ID',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '功能的ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '标识是用户组还是用户1为用户组2为用户,默认为用户组',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';



# Dump of table admin_function
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_function`;

CREATE TABLE `admin_function` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '功能的路由',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '节点的名字',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '节点的父节点，此值一般用于输出树形结构，0则为顶级',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `public` tinyint(1) NOT NULL DEFAULT '1' COMMENT '功能的可见性。 0:私有 1:公有',
  `display` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为显示为菜单，0则不显示',
  `level` tinyint(2) NOT NULL DEFAULT '1' COMMENT '第几级菜单',
  `mark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限节点表';



# Dump of table admin_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_group`;

CREATE TABLE `admin_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL COMMENT '用户组名',
  `mark` varchar(255) NOT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否禁用',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '用户组等级，预留字段',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_name_unique` (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户组表';



# Dump of table admin_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_log`;

CREATE TABLE `admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '登录的用户名',
  `admin_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '登录的用户ID',
  `ip` varchar(20) NOT NULL COMMENT 'ip地址',
  `action_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '后台事件类型',
  `json_content` varchar(1000) NOT NULL DEFAULT '' COMMENT '事件的结构化数据',
  `log_msg` varchar(600) NOT NULL DEFAULT '' COMMENT 'log_msg',
  `targetReamId` int(11) NOT NULL DEFAULT '0',
  `targetReamType` int(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table admin_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_order`;

CREATE TABLE `admin_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `order_id` int(10) NOT NULL DEFAULT '0',
  `status` int(6) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id_unique` (`order_id`),
  UNIQUE KEY `order_userid_uniq` (`admin_id`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table admin_user_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_user_group`;

CREATE TABLE `admin_user_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `enable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_group_uniq` (`group_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table admin_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `salt` char(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `real_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `phone_number` char(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `email` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '邮箱',
  `avatarpicurl` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '头像',
  `user_status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table app_driver_feedback
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_driver_feedback`;

CREATE TABLE `app_driver_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `driver_id` int(11) NOT NULL DEFAULT '0' COMMENT '司机id',
  `driver_mobile` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '司机手机',
  `content` varchar(500) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '反馈内容',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0隐藏，1展示',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `driver_id` (`driver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='司机app反馈表';



# Dump of table app_versions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `app_versions`;

CREATE TABLE `app_versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `app_url` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `platform` tinyint(4) NOT NULL,
  `version` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table apply_admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `apply_admin`;

CREATE TABLE `apply_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  `enterprise_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '企业名字',
  `enterprise_licence` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '企业执照注册号',
  `cargo_pic` varchar(128) NOT NULL DEFAULT '' COMMENT '营业执照',
  `official_letter` varchar(128) NOT NULL DEFAULT '' COMMENT '企业账户管理公函',
  `contactNumber` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '企业联系电话',
  `ownerName` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ownerIdentityCardId` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '车队负责人身份证号',
  `account_type` int(11) NOT NULL COMMENT '类型（货代 车队）',
  `city_id` int(11) DEFAULT NULL COMMENT '所在的城市',
  `established_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '公司的成立时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table car_team_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `car_team_user`;

CREATE TABLE `car_team_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userid` int(11) unsigned NOT NULL,
  `teamName` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '车队名',
  `teamPic` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '营业执照',
  `ownerName` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ownerIdentityCardId` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '车队负责人身份证号',
  `status` int(11) NOT NULL,
  `audit_status` int(11) NOT NULL,
  `idcard_pic` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '身份证图片',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table cms_content
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cms_content`;

CREATE TABLE `cms_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `content` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cms_type` int(11) NOT NULL,
  `platform` tinyint(4) DEFAULT NULL,
  `version` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `enable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table enterprise_group
# ------------------------------------------------------------

DROP TABLE IF EXISTS `enterprise_group`;

CREATE TABLE `enterprise_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `enterprise_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '企业id',
  `group_name` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(500) DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业分组表';



# Dump of table event_daily_statistic
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event_daily_statistic`;

CREATE TABLE `event_daily_statistic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `year_int` int(8) unsigned NOT NULL DEFAULT '0',
  `month_int` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `day_int` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `static_event_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `child_action_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `times_int` int(10) NOT NULL DEFAULT '0',
  `platform_type` int(6) unsigned NOT NULL DEFAULT '1' COMMENT '1数据来自activilog 2数据来自adminlog',
  `record_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '统计时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table event_hourly_statistic
# ------------------------------------------------------------

DROP TABLE IF EXISTS `event_hourly_statistic`;

CREATE TABLE `event_hourly_statistic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `year_int` int(8) unsigned NOT NULL DEFAULT '0',
  `month_int` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `day_int` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `hour_int` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `static_event_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `child_action_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `times_int` int(10) NOT NULL DEFAULT '0',
  `platform_type` int(6) unsigned NOT NULL DEFAULT '1' COMMENT '1数据来自activilog 2数据来自adminlog',
  `record_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '统计时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table freightagent_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `freightagent_user`;

CREATE TABLE `freightagent_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userid` int(11) unsigned NOT NULL,
  `status` int(11) NOT NULL,
  `audit_status` int(11) NOT NULL,
  `avartar_idcard_pic` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '持证照片',
  `idcard_back_pic` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '身份证照片',
  `cargo_pic` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '营业执照',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='货代信息表';



# Dump of table group_function
# ------------------------------------------------------------

DROP TABLE IF EXISTS `group_function`;

CREATE TABLE `group_function` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` int(11) NOT NULL,
  `functionid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='组权限关联表';



# Dump of table input_sms
# ------------------------------------------------------------

DROP TABLE IF EXISTS `input_sms`;

CREATE TABLE `input_sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mobileNumber` varchar(30) NOT NULL COMMENT '发短信mobile',
  `channelnumber` varchar(30) NOT NULL COMMENT 'channelnumber',
  `addSerialRev` varchar(30) DEFAULT '' COMMENT 'addSerialRev',
  `addSerial` varchar(30) DEFAULT '' COMMENT 'addSerial',
  `smsContent` varchar(150) NOT NULL COMMENT '预计的发短信内容',
  `sentTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `receive_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table invite_record
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invite_record`;

CREATE TABLE `invite_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inviter_userid` int(11) unsigned NOT NULL COMMENT '邀请人',
  `invitee_userid` int(11) unsigned NOT NULL COMMENT '被邀请人',
  `inviteer_enterpriseid` varchar(64) DEFAULT '' COMMENT '邀请人的企业id',
  `invite_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1，搜索邀请加入公司，2，通过链接申请加入公司',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1，邀请中，2，成功,3,失败',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invite_relation_uniq` (`inviter_userid`,`invitee_userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邀请记录表';



# Dump of table login_record
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login_record`;

CREATE TABLE `login_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) unsigned NOT NULL,
  `token` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL,
  `expire_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='登陆日志表';



# Dump of table msg_p2p
# ------------------------------------------------------------

DROP TABLE IF EXISTS `msg_p2p`;

CREATE TABLE `msg_p2p` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` varchar(128) DEFAULT '' COMMENT '发信人',
  `rec_id` varchar(128) DEFAULT '' COMMENT '收信人',
  `msg_type` tinyint(4) NOT NULL COMMENT '消息类型',
  `msg_status` tinyint(4) DEFAULT NULL COMMENT '信息状态',
  `msg_title` varchar(128) NOT NULL DEFAULT '' COMMENT '站内信title',
  `msg_content` varchar(1000) NOT NULL DEFAULT '' COMMENT '内容',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `read_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '阅读时间',
  `exp_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '过期时间',
  `deal_url` varchar(300) NOT NULL DEFAULT '' COMMENT '操作url',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='站内信';



# Dump of table order_assign_driver
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_assign_driver`;

CREATE TABLE `order_assign_driver` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_freight_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'order_freight_id',
  `order_freight_boxid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'order_freight_boxid',
  `order_product_addressid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'order_product_addressid',
  `order_product_timeid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'order_product_timeid',
  `driver_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '司机userid',
  `current_latitude` decimal(20,10) NOT NULL DEFAULT '0.0000000000' COMMENT '当前纬度',
  `current_longitude` decimal(20,10) NOT NULL DEFAULT '0.0000000000' COMMENT '当前经度',
  `assign_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '当前派单的状态',
  `address_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '产装地跟踪状态',
  `drop_box_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '落箱地跟踪状态',
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0删除，1有效',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='派单表';



# Dump of table order_box_location
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_box_location`;

CREATE TABLE `order_box_location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_freight_id` int(11) unsigned NOT NULL COMMENT 'order_freight_id',
  `order_assign_driver` int(11) unsigned NOT NULL COMMENT 'order_assign_driver',
  `order_freight_boxid` int(11) unsigned NOT NULL COMMENT 'order_freight_boxid',
  `box_latitude` decimal(20,10) NOT NULL DEFAULT '0.0000000000' COMMENT '位置纬度',
  `box_longitude` decimal(20,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `location_source_type` tinyint(4) DEFAULT '0' COMMENT 'location来源类型:gps 基站等',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产装联系单-集装箱';



# Dump of table order_box_timeline
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_box_timeline`;

CREATE TABLE `order_box_timeline` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_freight_id` int(11) unsigned NOT NULL COMMENT 'order_freight_id',
  `order_freight_boxid` int(11) unsigned NOT NULL COMMENT 'order_freight_boxid',
  `boxline_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'boxline_type',
  `verify_ream_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '确认类型',
  `location_msg` varchar(1024) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `verify_ream_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `jsonContent` varchar(1024) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='order_box_timeline';



# Dump of table order_freight
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_freight`;

CREATE TABLE `order_freight` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `yundan_code` varchar(20) NOT NULL DEFAULT '' COMMENT '运单号',
  `freightagent_user` int(11) unsigned NOT NULL COMMENT '货代用户id',
  `cock_city_code` varchar(50) NOT NULL COMMENT '港口城市code',
  `import_type` tinyint(4) unsigned NOT NULL COMMENT '进出口类型',
  `carrier_userid` int(11) unsigned NOT NULL COMMENT '承运方用户id',
  `tixiangdan_file_url` varchar(150) DEFAULT NULL COMMENT '提箱单附件url',
  `addresscontact_file_urls` varchar(650) DEFAULT NULL COMMENT '产装联系单附件url',
  `tidan_code` varchar(50) NOT NULL DEFAULT '' COMMENT '提单号',
  `ship_name_id` int(11) NOT NULL DEFAULT '0' COMMENT '船名id',
  `shipping_company_id` int(11) NOT NULL DEFAULT '0' COMMENT '船公司id',
  `ship_ticket` varchar(50) NOT NULL DEFAULT '' COMMENT '船次',
  `ship_ticket_desc` varchar(50) NOT NULL DEFAULT '' COMMENT '船期备注',
  `yard_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '指定堆场id',
  `product_name` varchar(50) NOT NULL DEFAULT '' COMMENT '货品名称',
  `product_desc` varchar(150) NOT NULL DEFAULT '' COMMENT '货物信息备注',
  `product_weight` int(11) NOT NULL DEFAULT '0' COMMENT '货品重量',
  `product_box_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '货物箱型',
  `box_20gp_count` tinyint(4) NOT NULL DEFAULT '0' COMMENT '20GP箱型箱量',
  `box_40gp_count` tinyint(4) NOT NULL DEFAULT '0' COMMENT '40GP箱型箱量',
  `box_40hq_count` tinyint(4) NOT NULL DEFAULT '0' COMMENT '40HQG箱型箱量',
  `box_45hq_count` tinyint(4) NOT NULL DEFAULT '0' COMMENT '45HQG箱型箱量',
  `order_status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态',
  `order_total_percent` double NOT NULL DEFAULT '0' COMMENT '订单总体评分',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `submit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '货代正式下单时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`),
  UNIQUE KEY `yundan_code` (`yundan_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='货代订单表';



# Dump of table order_freight_box
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_freight_box`;

CREATE TABLE `order_freight_box` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_freight_id` int(11) unsigned NOT NULL COMMENT 'order_freight_id',
  `box_ensupe` varchar(50) NOT NULL DEFAULT '' COMMENT '铅封号',
  `box_code` varchar(50) NOT NULL DEFAULT '' COMMENT '箱号',
  `box_size_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '箱子大小类型',
  `box_weight` double NOT NULL DEFAULT '0' COMMENT '箱子重量大小',
  `target_latitude` decimal(20,10) NOT NULL DEFAULT '0.0000000000' COMMENT '目标位置纬度',
  `target_longitude` decimal(20,10) NOT NULL DEFAULT '0.0000000000' COMMENT '目标经度',
  `contact_telephone` varchar(50) NOT NULL DEFAULT '' COMMENT '工厂产装联系人座机号',
  `address_provinceid` int(11) unsigned NOT NULL COMMENT '省id',
  `address_cityid` int(11) unsigned NOT NULL COMMENT '市id',
  `address_townid` int(11) unsigned NOT NULL COMMENT '县id',
  `address_detail` varchar(120) DEFAULT NULL COMMENT '详细地址',
  `box_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '箱子的状态',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产装联系单-集装箱';



# Dump of table order_freight_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_freight_comment`;

CREATE TABLE `order_freight_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `order_freightid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '货代订单表id',
  `order_comment_type` tinyint(4) unsigned NOT NULL COMMENT '评分维度类型',
  `order_comment_score` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '评分, 0,1,2,3,4,5分',
  `order_comment_username` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '发表评价的用户名，为空是匿名评价',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='货代订单细节评价表';



# Dump of table order_freight_timeline
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_freight_timeline`;

CREATE TABLE `order_freight_timeline` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_freight_id` int(11) unsigned NOT NULL COMMENT 'order_freight_id',
  `ordertimeline_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单的timeline类型',
  `verify_ream_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '确认类型',
  `verify_ream_id` varchar(64) NOT NULL DEFAULT '',
  `jsonContent` varchar(1024) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单timeline';



# Dump of table order_product_address
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_product_address`;

CREATE TABLE `order_product_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_freight_id` int(11) unsigned NOT NULL COMMENT 'order_freight_id',
  `contact_name` varchar(50) NOT NULL DEFAULT '' COMMENT '工厂产装联系人',
  `contact_number` varchar(50) NOT NULL DEFAULT '' COMMENT '工厂产装联系人手机号或者座机号',
  `address_provinceid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '省id',
  `address_cityid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '市id',
  `address_townid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '县id',
  `address_detail` varchar(120) DEFAULT NULL COMMENT '详细地址',
  `latitude` decimal(20,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `longitude` decimal(20,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0删除，1有效',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产装地址';



# Dump of table order_product_time
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_product_time`;

CREATE TABLE `order_product_time` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_freight_id` int(11) unsigned NOT NULL COMMENT 'order_freight_id',
  `order_product_addressid` int(11) unsigned NOT NULL COMMENT 'order_product_addressid',
  `product_supply_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0删除，1有效',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产地产装时间';



# Dump of table page_views
# ------------------------------------------------------------

DROP TABLE IF EXISTS `page_views`;

CREATE TABLE `page_views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `page_url` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `platform` tinyint(4) NOT NULL,
  `version` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table password_resets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table ship_name
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ship_name`;

CREATE TABLE `ship_name` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `shipping_companyid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '船公司id',
  `contact_name` varchar(50) NOT NULL DEFAULT '' COMMENT '联系人',
  `com_address` varchar(120) NOT NULL DEFAULT '' COMMENT '联系地址',
  `phone_mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '联系人手机号',
  `shipname_code` varchar(50) NOT NULL DEFAULT '' COMMENT 'code 简写',
  `china_name` varchar(150) NOT NULL DEFAULT '' COMMENT '船全名中文',
  `eng_name` varchar(150) NOT NULL DEFAULT '' COMMENT '船全名英文',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `type` tinyint(4) DEFAULT '0' COMMENT '0时admin 创建的，1时是 www创建的等待审核',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  `pinyin` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='船名table';



# Dump of table shipping_company
# ------------------------------------------------------------

DROP TABLE IF EXISTS `shipping_company`;

CREATE TABLE `shipping_company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `company_code` varchar(150) NOT NULL DEFAULT '' COMMENT 'code 简写',
  `china_name` varchar(150) NOT NULL DEFAULT '' COMMENT '船公司全名中文',
  `english_name` varchar(150) NOT NULL DEFAULT '',
  `companny_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '船公司类型',
  `phone_mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '手机号',
  `com_adress` varchar(150) NOT NULL DEFAULT '' COMMENT '地址',
  `contact_name` varchar(150) NOT NULL DEFAULT '' COMMENT '联系人',
  `type` tinyint(4) DEFAULT '0' COMMENT '0时admin 创建的，1时是 www创建的等待审核',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'update time',
  `pinyin` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='船公司信息';



# Dump of table sms_history
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sms_history`;

CREATE TABLE `sms_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `objectId` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `platform` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `deviceId` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `msgContent` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `channel` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='短信历史';



# Dump of table spam_username
# ------------------------------------------------------------

DROP TABLE IF EXISTS `spam_username`;

CREATE TABLE `spam_username` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `spamword` varchar(50) NOT NULL COMMENT '敏感词内容',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='敏感词';



# Dump of table tb_driver
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tb_driver`;

CREATE TABLE `tb_driver` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `work_status` int(11) NOT NULL DEFAULT '1' COMMENT '司机工作状态',
  `team_id` int(11) DEFAULT NULL COMMENT '司机隶属车队id',
  `driver_name` varchar(32) NOT NULL DEFAULT '' COMMENT '司机名字',
  `id_number` varchar(20) NOT NULL DEFAULT '' COMMENT '身份证号',
  `car_number` varchar(64) NOT NULL DEFAULT '' COMMENT '车牌号',
  `Industry_auth` varchar(64) NOT NULL DEFAULT '' COMMENT '从业资格证图片地址',
  `drive_number` varchar(64) NOT NULL DEFAULT '' COMMENT '行驶证号',
  `car_trans_auth` varchar(64) NOT NULL DEFAULT '' COMMENT '车辆运营证照片地址',
  `enable` int(1) unsigned NOT NULL DEFAULT '1',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_car_number` (`car_number`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='司机表';



# Dump of table tb_enterprise
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tb_enterprise`;

CREATE TABLE `tb_enterprise` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `enterprise_name` varchar(64) NOT NULL DEFAULT '' COMMENT '企业名字',
  `status` tinyint(4) NOT NULL,
  `admin_userid` int(11) unsigned NOT NULL COMMENT '管理员userid',
  `enterprise_type` tinyint(4) NOT NULL COMMENT '类型(货代 车队)',
  `invite_token` varchar(64) NOT NULL DEFAULT '' COMMENT '企业邀请码',
  `city_id` int(11) DEFAULT NULL COMMENT '所在的城市',
  `established_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '公司的成立时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业机构表';



# Dump of table tb_function
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tb_function`;

CREATE TABLE `tb_function` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `function_code` varchar(64) NOT NULL DEFAULT '' COMMENT '功能code',
  `function_name` varchar(64) NOT NULL DEFAULT '' COMMENT '功能名字',
  `function_url` varchar(64) NOT NULL DEFAULT '' COMMENT '功能url入口',
  `description` varchar(1024) NOT NULL DEFAULT '',
  `enterprise_type` tinyint(4) NOT NULL COMMENT '企业类型',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='企业权限表';



# Dump of table tb_tickets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tb_tickets`;

CREATE TABLE `tb_tickets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` varchar(128) NOT NULL DEFAULT '' COMMENT '工单发起人',
  `target_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '工单目标类型',
  `target_id` varchar(128) NOT NULL DEFAULT '' COMMENT '工单目标id',
  `employee_id` varchar(128) NOT NULL DEFAULT '' COMMENT '受理的员工id',
  `ticket_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '工单类型',
  `ticket_title` varchar(128) NOT NULL DEFAULT '' COMMENT 'title',
  `ticket_text_content` varchar(1000) NOT NULL DEFAULT '' COMMENT 'ticket_content',
  `ticket_attach_content_url` varchar(128) NOT NULL DEFAULT '' COMMENT '工单申诉提交的附件',
  `ticket_status` tinyint(4) DEFAULT '0' COMMENT '状态',
  `ticket_result` tinyint(4) NOT NULL DEFAULT '0' COMMENT '工单处理结果',
  `ticket_result_info` varchar(1000) NOT NULL DEFAULT '' COMMENT '工单处理结果内容描述',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update时间',
  `settle_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '处理完毕的时间',
  `exp_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '过期时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='工单申诉';



# Dump of table tbl_province
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbl_province`;

CREATE TABLE `tbl_province` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `codeid` char(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `parentid` char(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cityName` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `longitude` char(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  `latitude` char(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_province_codeid_unique` (`codeid`),
  KEY `tbl_province_parentid_index` (`parentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table test_locate
# ------------------------------------------------------------

DROP TABLE IF EXISTS `test_locate`;

CREATE TABLE `test_locate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `latitude` decimal(20,10) NOT NULL DEFAULT '0.0000000000' COMMENT '位置纬度',
  `longitude` decimal(20,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `locate_info` varchar(800) NOT NULL DEFAULT '' COMMENT '位置信息',
  `locate_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '上报类型：1、位置跟踪 2、围栏定位',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='临时定位表（测试使用）';



# Dump of table unregister_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `unregister_users`;

CREATE TABLE `unregister_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ä¸»é”®',
  `mobile` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'æ‰‹æœºå·ç ',
  `source_platform` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0ã€æœªæ³¨å†Œ 1ã€å·²æˆä¸ºæ³¨å†Œç”¨æˆ·',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'åˆ›å»ºæ—¶é—´',
  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'æ›´æ–°æ—¶é—´',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ms` (`mobile`,`source_platform`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='æœªæ³¨å†Œçš„ç”¨æˆ·è¡¨';



# Dump of table user_friendship
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_friendship`;

CREATE TABLE `user_friendship` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` varchar(128) NOT NULL DEFAULT '' COMMENT '合作主动发起人',
  `rec_id` varchar(128) DEFAULT '' COMMENT '合作被动人',
  `friend_token` varchar(128) NOT NULL DEFAULT '' COMMENT '邀请token',
  `friendship_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '合作关系建立方式类型',
  `invite_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '邀请类型',
  `friendship_status` tinyint(4) DEFAULT '0' COMMENT '状态',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update时间',
  `agree_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'agree时间',
  `exp_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '过期时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_send_rec` (`sender_id`,`rec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='合作关系表';



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usertype` int(11) NOT NULL COMMENT '用户类型（货代 车队 司机）',
  `mobile` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `telephone_number` varchar(48) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '座机电话',
  `contactName` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系人',
  `contactNumber` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `pwd` char(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `salt` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效',
  `regist_platform` tinyint(4) NOT NULL DEFAULT '0',
  `remember_token` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `regist_version` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `invite_userid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '邀请的userid',
  `avatarpicurl` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `enterprise_licence` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '企业执照注册号',
  `enterpriseid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '企业id',
  `unverify_enterprisename` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '待认证的企业名字',
  `enterprise_groupid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'groupid',
  `invite_token` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '合作好友邀请码',
  `real_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uni_name` (`username`),
  UNIQUE KEY `uni_mobile` (`mobile`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table yard_info
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yard_info`;

CREATE TABLE `yard_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cock_city_code` varchar(50) NOT NULL COMMENT '堆场所属城市 province id',
  `yard_name` varchar(50) NOT NULL COMMENT '堆场名字',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1时admin 创建的，2时是 www创建的等待审核',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  `pinyin` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `inx_pinyin` (`pinyin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='堆场信息';



# Dump of table yard_location
# ------------------------------------------------------------

DROP TABLE IF EXISTS `yard_location`;

CREATE TABLE `yard_location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `yard_id` int(10) unsigned NOT NULL COMMENT '堆场所属城市 province id',
  `location_car_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '对应车型:1重车、2空车',
  `location_degree_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '角度类型:1中心',
  `location_type` tinyint(4) unsigned NOT NULL COMMENT '位置类型:1进口、2出口',
  `latitude` decimal(20,10) NOT NULL COMMENT '纬度',
  `longitude` decimal(20,10) NOT NULL COMMENT '经度',
  `yard_address` varchar(200) NOT NULL DEFAULT '' COMMENT 'å †åŽ‚åœ°å€ï¼ŒåæŸ¥é«˜å¾·api',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`),
  KEY `yid` (`yard_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='堆场location信息';


INSERT INTO `admin_group` ( `group_name`, `mark`, `status`, `level`) VALUES ( '跟单组', '', 1, 6);


DROP TABLE IF EXISTS `unarrived_box`;

create table unarrived_box(
`id` int not null auto_increment comment '主键',
`order_freight_id` int not null default 0 comment '订单id',
`tidan_code`  varchar(50)  NOT NULL DEFAULT ''  COMMENT '提单号',
`box_code` varchar(50) NOT NULL DEFAULT '' COMMENT '箱号',
`box_ensupe` varchar(50) NOT NULL  DEFAULT '' COMMENT '铅封号',
`create_time` datetime not null default CURRENT_TIMESTAMP comment '创建时间',
`update_time` datetime not null default CURRENT_TIMESTAMP comment '更新时间',
PRIMARY KEY `id` (`id`)
)engine=innodb DEFAULT CHARSET=utf8 COMMENT='未运抵的箱子表（即：查询天津港口局，抓取的箱子信息与数据表存储不匹配）';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
