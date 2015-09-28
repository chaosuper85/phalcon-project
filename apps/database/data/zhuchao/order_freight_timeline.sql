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
-- Table structure for table `order_freight_timeline`
--

DROP TABLE IF EXISTS `order_freight_timeline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_freight_timeline` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_freight_id` int(11) unsigned NOT NULL COMMENT 'order_freight_id',
  `ordertimeline_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单的timeline类型',
  `verify_ream_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '确认类型',
  `verify_ream_id` varchar(64) NOT NULL DEFAULT '',
  `jsonContent` varchar(1024) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='订单timeline';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_freight_timeline`
--

LOCK TABLES `order_freight_timeline` WRITE;
/*!40000 ALTER TABLE `order_freight_timeline` DISABLE KEYS */;
INSERT INTO `order_freight_timeline` VALUES (1,104,3,0,'','','0000-00-00 00:00:00','2015-09-05 10:34:09'),(2,104,4,0,'','','0000-00-00 00:00:00','2015-09-05 10:34:09'),(3,104,5,0,'','','0000-00-00 00:00:00','2015-09-05 10:34:09'),(4,104,6,0,'','','0000-00-00 00:00:00','2015-09-05 10:34:09'),(5,104,7,0,'','','0000-00-00 00:00:00','2015-09-05 10:34:09'),(7,77,5,0,'','','2015-09-02 10:09:08','2015-09-02 10:09:08'),(8,77,5,0,'','','2015-09-02 10:22:49','2015-09-02 10:22:49'),(9,77,5,0,'','','2015-09-02 10:25:08','2015-09-02 10:25:08'),(10,77,5,0,'','','2015-09-02 10:37:56','2015-09-02 10:37:56'),(11,77,5,0,'','','2015-09-02 10:42:33','2015-09-02 10:42:33'),(12,77,5,0,'','','2015-09-02 10:45:27','2015-09-02 10:45:27'),(13,77,5,0,'','','2015-09-02 10:45:32','2015-09-02 10:45:32'),(14,77,5,0,'','','2015-09-02 10:45:57','2015-09-02 10:45:57'),(15,77,5,0,'','','2015-09-02 10:46:04','2015-09-02 10:46:04'),(16,77,5,0,'','','2015-09-02 10:47:08','2015-09-02 10:47:08'),(17,77,5,0,'','','2015-09-02 10:47:35','2015-09-02 10:47:35'),(18,77,5,0,'','','2015-09-02 10:55:45','2015-09-02 10:55:45');
/*!40000 ALTER TABLE `order_freight_timeline` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-05 20:30:55
