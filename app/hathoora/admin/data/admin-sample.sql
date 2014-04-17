CREATE DATABASE  IF NOT EXISTS `dbname` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `dbname`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: localhost    Database: dbname
-- ------------------------------------------------------
-- Server version	5.5.34

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
-- Table structure for table `translation_key`
--

DROP TABLE IF EXISTS `translation_key`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translation_key` (
  `translation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `translation_key` varchar(105) NOT NULL,
  `notes` varchar(255) NOT NULL,
  PRIMARY KEY (`translation_id`),
  UNIQUE KEY `item_UNIQUE` (`translation_key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translation_key`
--

LOCK TABLES `translation_key` WRITE;
/*!40000 ALTER TABLE `translation_key` DISABLE KEYS */;
INSERT INTO `translation_key` VALUES (1,'hathoora_hello_world',''),(2,'hathoora_route_example_title',''),(3,'hathoora_route_example_body','');
/*!40000 ALTER TABLE `translation_key` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translation_route`
--

DROP TABLE IF EXISTS `translation_route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translation_route` (
  `translation_id` int(11) NOT NULL,
  `route` varchar(150) NOT NULL,
  PRIMARY KEY (`translation_id`,`route`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translation_route`
--

LOCK TABLES `translation_route` WRITE;
/*!40000 ALTER TABLE `translation_route` DISABLE KEYS */;
INSERT INTO `translation_route` VALUES (2,'hathoora_translation_route'),(3,'hathoora_translation_route');
/*!40000 ALTER TABLE `translation_route` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translation_value`
--

DROP TABLE IF EXISTS `translation_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `translation_value` (
  `translation_id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `translation` longtext NOT NULL,
  PRIMARY KEY (`translation_id`,`language`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translation_value`
--

LOCK TABLES `translation_value` WRITE;
/*!40000 ALTER TABLE `translation_value` DISABLE KEYS */;
INSERT INTO `translation_value` VALUES (1,'en_US','Hello, {{name}}'),(1,'fr_FR','Bonjour, {{name}}'),(2,'en_US','Time right now: {{date}}'),(2,'fr_FR','Temps maintenant: {{date}}'),(3,'en_US','Using HTML inside translations for <a href=\"{{link}}\">Hathoora PHP Framework</a>'),(3,'fr_FR','Utilisation de HTML Ã  l\'intÃ©rieur des traductions pour <a href=\"{{link}}\">Framework PHP Hathoora</a>');
/*!40000 ALTER TABLE `translation_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'dbname'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-03-10 22:26:33

