-- MySQL dump 10.13  Distrib 5.6.24, for osx10.10 (x86_64)
--
-- Host: 123.59.59.233    Database: phalcon
-- ------------------------------------------------------
-- Server version	5.5.22-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tb_driver`
--

DROP TABLE IF EXISTS `tb_driver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tb_driver` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) unsigned NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `work_status` int(11) NOT NULL DEFAULT '0' COMMENT '司机工作状态',
  `team_id` int(11) DEFAULT NULL COMMENT '司机隶属车队id',
  `driver_name` varchar(32) NOT NULL DEFAULT '' COMMENT '司机名字',
  `id_number` varchar(20) NOT NULL DEFAULT '' COMMENT '身份证号',
  `car_number` varchar(64) NOT NULL DEFAULT '' COMMENT '车牌号',
  `Industry_auth` varchar(64) NOT NULL DEFAULT '' COMMENT '从业资格证图片地址',
  `drive_number` varchar(64) NOT NULL DEFAULT '' COMMENT '行驶证号',
  `car_trans_auth` varchar(64) NOT NULL DEFAULT '' COMMENT '车辆运营证照片地址',
  `enable` int(1) unsigned NOT NULL DEFAULT '1',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='司机表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_driver`
--

LOCK TABLES `tb_driver` WRITE;
/*!40000 ALTER TABLE `tb_driver` DISABLE KEYS */;
INSERT INTO `tb_driver` VALUES (1,1,0,1,866714828,'陈博文','1','京A 12345','','18611768664','',1,'2015-09-06 05:55:56','2015-08-28 08:33:11'),(2,866714811,1,1,866714695,'陈博文2号','1','京东','','23423432','',1,'2015-09-06 05:56:14','0000-00-00 00:00:00'),(3,866714812,1,1,866714695,'陈博文3号','1','京东商城','','324234','',1,'2015-09-06 05:56:13','0000-00-00 00:00:00'),(4,866714813,1,1,866714695,'司机1','2','车牌号1','','123','',1,'2015-09-06 05:56:12','0000-00-00 00:00:00'),(5,866714814,1,1,866714695,'司机2','2','车牌号2','','123','',1,'2015-09-06 05:56:12','0000-00-00 00:00:00'),(6,866714815,1,1,866714695,'陈博文dark','3','车牌号6','锁定','324','324',1,'2015-09-06 05:56:11','0000-00-00 00:00:00'),(7,866714695,1,1,1,'陈博文4号','4','车牌号5','','','',1,'2015-09-06 06:06:08','0000-00-00 00:00:00'),(8,866714695,0,1,866714828,'马鑫亮','1','京123456','','15010447505','',1,'2015-09-06 05:56:10','2015-09-02 08:46:01');
/*!40000 ALTER TABLE `tb_driver` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-06 14:18:17
