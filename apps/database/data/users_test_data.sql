/*
 Navicat MySQL Data Transfer

 Source Server         : local-mysql
 Source Server Type    : MySQL
 Source Server Version : 50624
 Source Host           : localhost
 Source Database       : phalcon

 Target Server Type    : MySQL
 Target Server Version : 50624
 File Encoding         : utf-8

 Date: 07/26/2015 18:01:32 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `usertype` int(11) NOT NULL COMMENT '用户类型（货代 车队 司机）',
  `mobile` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `contactName` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '联系人',
  `contactNumber` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `pwd` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `salt` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效',
  `regist_platform` tinyint(4) DEFAULT NULL,
  `remember_token` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `regist_version` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户名',
  `email` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '邮箱',
  `invite_userid` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '邀请的userid',
  `avatarpicurl` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '头像',
  `enterpriseid` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '企业id',
  `enterprise_groupid` int(11) unsigned DEFAULT NULL COMMENT 'groupid',
  `invite_token` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '个人邀请码',
  `real_name` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '真实姓名',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `uni_name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Records of `users`  测试数据：用户名 zhuchao,密码 123456
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES ('22', '1234567', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1', '18600502569', 'zhuchao', '02700502569', '34c0200ecd6bfc8505b54ceeadf448b8', '654321', '1', '1', null, '1', 'zhuchao', '123@163.com', '1', '1', '', null, null, null);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
