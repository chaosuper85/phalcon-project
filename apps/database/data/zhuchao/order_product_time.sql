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
-- Table structure for table `order_product_time`
--

DROP TABLE IF EXISTS `order_product_time`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_product_time` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `order_freight_id` int(11) unsigned NOT NULL COMMENT 'order_freight_id',
  `order_product_addressid` int(11) unsigned NOT NULL COMMENT 'order_product_addressid',
  `product_supply_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '生成时间',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '生成时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'update time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8 COMMENT='产地产装时间';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_product_time`
--

LOCK TABLES `order_product_time` WRITE;
/*!40000 ALTER TABLE `order_product_time` DISABLE KEYS */;
INSERT INTO `order_product_time` VALUES (1,3,1,'2015-09-04 20:00:00','2015-08-28 02:08:09','2015-08-28 02:08:09'),(2,3,1,'2015-09-05 03:00:00','2015-08-28 02:08:09','2015-08-28 02:08:09'),(3,3,1,'2015-09-05 20:00:00','2015-08-28 02:08:09','2015-08-28 02:08:09'),(4,3,1,'2015-09-06 03:00:00','2015-08-28 02:08:09','2015-08-28 02:08:09'),(5,3,2,'2015-09-04 20:00:00','2015-08-28 02:08:09','2015-08-28 02:08:09'),(6,3,2,'2015-09-05 03:00:00','2015-08-28 02:08:09','2015-08-28 02:08:09'),(7,5,3,'2015-07-28 04:00:00','2015-08-28 04:08:03','2015-08-28 04:08:03'),(8,12,4,'2015-09-04 20:00:00','2015-08-28 04:08:58','2015-08-28 04:08:58'),(9,12,4,'2015-09-05 03:00:00','2015-08-28 04:08:58','2015-08-28 04:08:58'),(10,12,4,'2015-09-05 20:00:00','2015-08-28 04:08:58','2015-08-28 04:08:58'),(11,12,4,'2015-09-06 03:00:00','2015-08-28 04:08:58','2015-08-28 04:08:58'),(12,12,5,'2015-09-04 20:00:00','2015-08-28 04:08:58','2015-08-28 04:08:58'),(13,12,5,'2015-09-05 03:00:00','2015-08-28 04:08:58','2015-08-28 04:08:58'),(14,11,6,'2015-09-04 20:00:00','2015-08-28 04:08:24','2015-08-28 04:08:24'),(15,11,6,'2015-09-05 03:00:00','2015-08-28 04:08:24','2015-08-28 04:08:24'),(16,11,6,'2015-09-05 20:00:00','2015-08-28 04:08:24','2015-08-28 04:08:24'),(17,11,6,'2015-09-06 03:00:00','2015-08-28 04:08:24','2015-08-28 04:08:24'),(18,11,7,'2015-09-04 20:00:00','2015-08-28 04:08:24','2015-08-28 04:08:24'),(19,11,7,'2015-09-05 03:00:00','2015-08-28 04:08:24','2015-08-28 04:08:24'),(20,10,8,'2015-09-04 20:00:00','2015-08-28 04:08:33','2015-08-28 04:08:33'),(21,10,8,'2015-09-05 03:00:00','2015-08-28 04:08:33','2015-08-28 04:08:33'),(22,10,8,'2015-09-05 20:00:00','2015-08-28 04:08:33','2015-08-28 04:08:33'),(23,10,8,'2015-09-06 03:00:00','2015-08-28 04:08:33','2015-08-28 04:08:33'),(24,10,9,'2015-09-04 20:00:00','2015-08-28 04:08:33','2015-08-28 04:08:33'),(25,10,9,'2015-09-05 03:00:00','2015-08-28 04:08:33','2015-08-28 04:08:33'),(26,9,10,'2015-09-04 20:00:00','2015-08-28 04:08:57','2015-08-28 04:08:57'),(27,9,10,'2015-09-05 03:00:00','2015-08-28 04:08:57','2015-08-28 04:08:57'),(28,9,10,'2015-09-05 20:00:00','2015-08-28 04:08:57','2015-08-28 04:08:57'),(29,9,10,'2015-09-06 03:00:00','2015-08-28 04:08:57','2015-08-28 04:08:57'),(30,9,11,'2015-09-04 20:00:00','2015-08-28 04:08:57','2015-08-28 04:08:57'),(31,9,11,'2015-09-05 03:00:00','2015-08-28 04:08:57','2015-08-28 04:08:57'),(32,8,12,'2015-09-04 20:00:00','2015-08-28 04:08:15','2015-08-28 04:08:15'),(33,8,12,'2015-09-05 03:00:00','2015-08-28 04:08:15','2015-08-28 04:08:15'),(34,8,12,'2015-09-05 20:00:00','2015-08-28 04:08:15','2015-08-28 04:08:15'),(35,8,12,'2015-09-06 03:00:00','2015-08-28 04:08:15','2015-08-28 04:08:15'),(36,8,13,'2015-09-04 20:00:00','2015-08-28 04:08:15','2015-08-28 04:08:15'),(37,8,13,'2015-09-05 03:00:00','2015-08-28 04:08:15','2015-08-28 04:08:15'),(38,7,14,'2015-09-04 20:00:00','2015-08-28 04:08:23','2015-08-28 04:08:23'),(39,7,14,'2015-09-05 03:00:00','2015-08-28 04:08:23','2015-08-28 04:08:23'),(40,7,14,'2015-09-05 20:00:00','2015-08-28 04:08:23','2015-08-28 04:08:23'),(41,7,14,'2015-09-06 03:00:00','2015-08-28 04:08:23','2015-08-28 04:08:23'),(42,7,15,'2015-09-04 20:00:00','2015-08-28 04:08:23','2015-08-28 04:08:23'),(43,7,15,'2015-09-05 03:00:00','2015-08-28 04:08:23','2015-08-28 04:08:23'),(44,1,16,'2015-07-29 21:00:00','2015-08-29 21:08:36','2015-08-29 21:08:36'),(45,1,17,'2015-07-29 21:00:00','2015-08-29 21:08:41','2015-08-29 21:08:41'),(46,1,18,'2015-07-29 21:00:00','2015-08-29 21:08:50','2015-08-29 21:08:50'),(47,1,19,'2015-07-29 21:00:00','2015-08-29 21:08:02','2015-08-29 21:08:02'),(48,1,20,'2015-07-29 21:00:00','2015-08-29 21:08:53','2015-08-29 21:08:53'),(49,1,21,'2015-07-29 21:00:00','2015-08-29 21:08:08','2015-08-29 21:08:08'),(50,1,22,'2015-07-29 21:00:00','2015-08-29 21:08:13','2015-08-29 21:08:13'),(51,1,23,'2015-07-29 21:00:00','2015-08-29 21:08:36','2015-08-29 21:08:36'),(52,1,24,'2015-07-29 21:00:00','2015-08-29 21:08:03','2015-08-29 21:08:03'),(53,1,25,'2015-07-29 21:00:00','2015-08-29 21:08:43','2015-08-29 21:08:43'),(54,1,26,'2015-07-29 21:00:00','2015-08-29 21:08:16','2015-08-29 21:08:16'),(55,5,27,'2015-07-29 21:00:00','2015-08-29 21:08:56','2015-08-29 21:08:56'),(56,35,28,'2015-07-29 21:00:00','2015-08-29 22:08:39','2015-08-29 22:08:39'),(57,35,29,'2015-07-29 21:00:00','2015-08-29 22:08:58','2015-08-29 22:08:58'),(58,1,30,'2015-07-29 22:00:00','2015-08-29 22:08:50','2015-08-29 22:08:50'),(59,1,31,'2015-07-29 22:00:00','2015-08-29 22:08:15','2015-08-29 22:08:15'),(60,1,32,'2015-07-31 03:00:00','2015-08-31 03:08:05','2015-08-31 03:08:05'),(61,5,33,'2015-07-31 04:00:00','2015-08-31 04:08:56','2015-08-31 04:08:56'),(62,39,34,'2015-04-03 17:00:00','2015-09-01 02:09:04','2015-09-01 02:09:04'),(63,39,34,'2017-03-02 19:30:00','2015-09-01 02:09:04','2015-09-01 02:09:04'),(64,39,34,'2016-03-03 00:00:00','2015-09-01 02:09:04','2015-09-01 02:09:04'),(65,39,34,'2015-10-03 18:00:00','2015-09-01 02:09:04','2015-09-01 02:09:04'),(66,39,35,'2015-09-01 02:00:00','2015-09-01 02:09:04','2015-09-01 02:09:04'),(67,39,35,'2015-02-03 02:30:00','2015-09-01 02:09:04','2015-09-01 02:09:04'),(80,47,38,'2015-09-04 20:00:00','2015-09-01 02:09:35','2015-09-01 02:09:35'),(81,47,38,'2015-09-05 03:00:00','2015-09-01 02:09:35','2015-09-01 02:09:35'),(82,47,38,'2015-09-05 20:00:00','2015-09-01 02:09:35','2015-09-01 02:09:35'),(83,47,38,'2015-09-06 03:00:00','2015-09-01 02:09:35','2015-09-01 02:09:35'),(84,77,39,'2015-09-04 20:00:00','2015-09-01 02:09:35','2015-09-02 08:59:36'),(85,77,39,'2015-09-05 03:00:00','2015-09-01 02:09:35','2015-09-02 08:59:36'),(86,1,40,'2015-08-01 03:00:00','2015-09-01 03:09:20','2015-09-01 03:09:20'),(159,40,69,'2015-10-03 02:00:00','2015-08-31 21:09:44','2015-08-31 21:09:44'),(160,40,70,'2015-08-01 02:00:00','2015-08-31 21:09:44','2015-08-31 21:09:44'),(161,40,70,'2015-08-01 02:00:00','2015-08-31 21:09:44','2015-08-31 21:09:44'),(162,40,70,'2015-08-01 02:00:00','2015-08-31 21:09:44','2015-08-31 21:09:44'),(163,40,70,'2015-08-01 02:00:00','2015-08-31 21:09:44','2015-08-31 21:09:44'),(164,40,71,'2015-08-31 21:00:00','2015-08-31 21:09:44','2015-08-31 21:09:44'),(165,17,72,'2015-09-30 21:00:00','2015-08-31 22:09:14','2015-08-31 22:09:14'),(166,17,73,'2015-08-31 21:00:00','2015-08-31 22:09:14','2015-08-31 22:09:14'),(167,15,74,'2015-02-01 22:00:00','2015-08-31 22:09:21','2015-08-31 22:09:21'),(168,57,75,'2015-09-05 04:00:00','2015-09-05 04:09:26','2015-09-05 04:09:26'),(171,56,77,'2015-09-04 18:00:00','2015-09-04 18:09:24','2015-09-04 18:09:24'),(172,56,77,'2015-09-04 18:00:00','2015-09-04 18:09:24','2015-09-04 18:09:24'),(173,56,77,'2015-09-04 18:00:00','2015-09-04 18:09:24','2015-09-04 18:09:24'),(174,69,78,'2015-09-04 18:00:00','2015-09-04 18:09:19','2015-09-04 18:09:19'),(175,94,79,'2015-09-04 19:00:00','2015-09-04 19:09:11','2015-09-04 19:09:11'),(176,93,80,'2015-09-04 20:00:00','2015-09-04 20:09:15','2015-09-04 20:09:15'),(177,34,81,'2015-09-04 20:00:00','2015-09-04 20:09:11','2015-09-04 20:09:11'),(178,48,82,'2015-09-04 20:00:00','2015-09-04 20:09:29','2015-09-04 20:09:29'),(179,105,83,'2015-09-05 00:00:00','2015-09-05 00:09:07','2015-09-05 00:09:07');
/*!40000 ALTER TABLE `order_product_time` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-05 20:31:07