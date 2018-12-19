-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 127.0.0.1    Database: ogsdb
-- ------------------------------------------------------
-- Server version	5.6.27-log

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
-- Table structure for table `report_test_table`
--

DROP TABLE IF EXISTS `report_test_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_test_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_user_id` varchar(45) NOT NULL,
  `child_user_id` varchar(45) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `imagename` varchar(100) DEFAULT NULL,
  `imagefiledetail` varchar(255) DEFAULT NULL,
  `open_flg` tinyint(4) DEFAULT '1',
  `open_date` datetime DEFAULT NULL,
  `close_date` datetime DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `insert_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_test_table`
--

LOCK TABLES `report_test_table` WRITE;
/*!40000 ALTER TABLE `report_test_table` DISABLE KEYS */;
INSERT INTO `report_test_table` VALUES (1,'10000','200000',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(2,'11rfaf1r','fafa1',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(3,'bvadfa','gafa',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),(4,'1000','2000',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),(5,'11rfaf','fafa',NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL),(8,'ando','atsushi',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(9,'xxxxxx','123456','aaaa@bbb.co.jp',NULL,NULL,1,NULL,NULL,NULL,NULL,NULL),(13,'imageinsert','imageinsert',NULL,'テスト',NULL,0,'2018-12-13 00:00:00','2018-12-20 00:00:00',1,NULL,NULL),(14,'imageinsert','imageinsert',NULL,'テスト',NULL,1,'2018-12-13 00:00:00','2018-12-20 00:00:00',1,NULL,NULL),(15,'imageinsert','imageinsert',NULL,'テスト','[{\"filename\":\"banner_1.png\",\"mimetype\":\"image\\/png\",\"filesize\":530188,\"linkurl\":\"http:\\/\\/hoge.jp\",\"link_new_window\":\"1\"}]',1,'2018-12-13 00:00:00','2018-12-20 00:00:00',1,NULL,NULL);
/*!40000 ALTER TABLE `report_test_table` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-12-19 15:20:27
