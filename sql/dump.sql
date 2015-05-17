-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (i386)
--
-- Host: localhost    Database: mydomains_1
-- ------------------------------------------------------
-- Server version	5.1.73

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
-- Table structure for table `tsn_action_log`
--

DROP TABLE IF EXISTS `tsn_action_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_action_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `id_client_form` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `post` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `tsn_action_log_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tsn_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_client`
--

DROP TABLE IF EXISTS `tsn_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `id_status` int(11) NOT NULL DEFAULT '1',
  `id_user` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_status` (`id_status`),
  CONSTRAINT `tsn_client_ibfk_2` FOREIGN KEY (`id_status`) REFERENCES `tsn_client_status` (`id`),
  CONSTRAINT `tsn_client_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tsn_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_client_file`
--

DROP TABLE IF EXISTS `tsn_client_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_client_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_loa` tinyint(1) NOT NULL,
  `is_ifa` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_client` (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_client_form`
--

DROP TABLE IF EXISTS `tsn_client_form`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_client_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_client` (`id_client`),
  CONSTRAINT `tsn_client_form_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `tsn_client` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_client_form_answer`
--

DROP TABLE IF EXISTS `tsn_client_form_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_client_form_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client_form` int(11) NOT NULL,
  `id_form_question` int(11) NOT NULL,
  `id_form_answer` int(11) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_client_form` (`id_client_form`),
  KEY `id_form_question` (`id_form_question`),
  KEY `id_form_answer` (`id_form_answer`),
  CONSTRAINT `tsn_client_form_answer_ibfk_1` FOREIGN KEY (`id_client_form`) REFERENCES `tsn_client_form` (`id`),
  CONSTRAINT `tsn_client_form_answer_ibfk_2` FOREIGN KEY (`id_form_question`) REFERENCES `tsn_form_question` (`id`),
  CONSTRAINT `tsn_client_form_answer_ibfk_3` FOREIGN KEY (`id_form_answer`) REFERENCES `tsn_form_answer` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_client_status`
--

DROP TABLE IF EXISTS `tsn_client_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_client_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tsn_client_status`
--

LOCK TABLES `tsn_client_status` WRITE;
/*!40000 ALTER TABLE `tsn_client_status` DISABLE KEYS */;
INSERT INTO `tsn_client_status` VALUES (1,'New client'),(2,'LOA uploaded'),(3,'Info requested'),(4,'Info uploaded'),(5,'Report ready');
/*!40000 ALTER TABLE `tsn_client_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tsn_client_status_changes`
--

DROP TABLE IF EXISTS `tsn_client_status_changes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_client_status_changes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) NOT NULL,
  `id_status` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_client` (`id_client`),
  KEY `id_status` (`id_status`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `tsn_client_status_changes_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `tsn_user` (`id`),
  CONSTRAINT `tsn_client_status_changes_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `tsn_client` (`id`),
  CONSTRAINT `tsn_client_status_changes_ibfk_2` FOREIGN KEY (`id_status`) REFERENCES `tsn_client_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_documents`
--

DROP TABLE IF EXISTS `tsn_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(8) NOT NULL,
  `title` varchar(255) NOT NULL,
  `version` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_form_answer`
--

DROP TABLE IF EXISTS `tsn_form_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_form_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_form_question` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_form_question` (`id_form_question`),
  CONSTRAINT `tsn_form_answer_ibfk_1` FOREIGN KEY (`id_form_question`) REFERENCES `tsn_form_question` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_form_question`
--

DROP TABLE IF EXISTS `tsn_form_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_form_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_logs`
--

DROP TABLE IF EXISTS `tsn_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `start_ts` int(11) NOT NULL,
  `end_ts` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `id_session` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_session` (`id_session`),
  KEY `id_user_2` (`id_user`),
  CONSTRAINT `tsn_logs_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tsn_user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_menu`
--

DROP TABLE IF EXISTS `tsn_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `order` smallint(6) NOT NULL DEFAULT '0',
  `ifa` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_menu` (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tsn_menu`
--

LOCK TABLES `tsn_menu` WRITE;
/*!40000 ALTER TABLE `tsn_menu` DISABLE KEYS */;
INSERT INTO `tsn_menu` VALUES (1,'dashboard','Dashboard',NULL,0,1,1,0,NULL,'user'),(13,'clients','My clients',NULL,0,1,2,0,NULL,'user'),(15,'profile','My profile',NULL,0,1,3,0,NULL,'user'),(17,'forms','Client forms',13,0,1,4,0,NULL,'user'),(18,'dashboard','Dashboard',NULL,0,1,1,1,NULL,'ifa'),(19,'clients','All clients',NULL,0,1,2,1,NULL,'ifa'),(20,'profile','My profile',NULL,0,1,3,1,NULL,'ifa'),(21,'forms','Client forms',19,0,1,4,1,NULL,'ifa'),(22,'settings','Settings',NULL,0,1,5,1,NULL,'admin'),(23,'logs','Action logs',NULL,0,1,6,0,NULL,'admin'),(24,'dashboard','Dashboard',NULL,0,1,1,0,NULL,'admin'),(25,'clients','All clients',NULL,0,1,2,1,NULL,'admin'),(26,'profile','My profile',NULL,0,1,3,1,NULL,'admin'),(27,'forms','Client forms',25,0,1,4,1,NULL,'admin'),(28,'downloads','Client files',13,0,1,5,0,0,'user'),(29,'downloads','Client files',19,0,1,5,1,0,'ifa'),(30,'downloads','Client files',25,0,1,5,1,0,'admin'),(31,'documents','Documents',NULL,0,1,4,0,NULL,'user'),(32,'documents','Documents',NULL,0,1,4,0,NULL,'ifa'),(33,'documents','Documents',NULL,0,1,4,0,NULL,'admin');
/*!40000 ALTER TABLE `tsn_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tsn_session`
--

DROP TABLE IF EXISTS `tsn_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `last_activity_ts` int(11) NOT NULL,
  `session` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tsn_settings`
--

DROP TABLE IF EXISTS `tsn_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tsn_settings`
--

LOCK TABLES `tsn_settings` WRITE;
/*!40000 ALTER TABLE `tsn_settings` DISABLE KEYS */;
INSERT INTO `tsn_settings` VALUES (1,'site_title','Loyal North Risk Profiling System','Site name','Displayed at the top in the browser',1),(2,'from_email','Info@northern-lion.co.uk','Email, from which send emails','',2),(3,'return_0','3.2','Defensive (return)','',3),(4,'volatility_0','4','Defensive (volatility)','',4),(5,'return_1','4.8','Prudent (return)','',5),(6,'volatility_1','6','Prudent (volatility)','',6),(7,'return_2','7.2','Balanced (return)','',7),(8,'volatility_2','9','Balanced (volatility)','',8),(9,'return_3','9.6','Growth (return)','',9),(10,'volatility_3','12','Growth (volatility)','',10),(11,'return_4','12','Generation (return)','',11),(12,'volatility_4','15','Generation (volatility)','',12),(13,'pdf_header_left','<img src=``/images/logo-pdf.png`` />','PDF header (left)','',13),(14,'pdf_header_right','Northern Lion<br />23 Buckingham Gate<br />London, SW1E 6LB<br /> northern-lion.co.uk','PDF header (right)','',14),(15,'pdf_footer',' Northern Lion is trading name of Fusion Asset Management LLP.  Fusion Asset Management LLP is registered in England at 23 Buckingham Gate, London. SW1E 6LB and is authorized and regulated by the Financial Conduct Authority under ref. 401334.','PDF footer','',15);
/*!40000 ALTER TABLE `tsn_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tsn_user`
--

DROP TABLE IF EXISTS `tsn_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tsn_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(5) NOT NULL,
  `show_count` int(3) NOT NULL DEFAULT '30',
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `ifa` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) DEFAULT NULL,
  `documents_access` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tsn_user`
--

LOCK TABLES `tsn_user` WRITE;
/*!40000 ALTER TABLE `tsn_user` DISABLE KEYS */;
INSERT INTO `tsn_user` VALUES (1,'Alla Mamontova','alla','c100c7f8d8f8aaf3acadb16af9c89dd8','g^sE4',30,'allasergeevna@list.ru','',0,1,0,0,1),(2,'Pavel Poloskov','pavel','c100c7f8d8f8aaf3acadb16af9c89dd8','g^sE4',30,'pavel.poloskov@fusionam.com','',0,1,0,0,1),(3,'Pavel Poloskov','pavel_ifa','c100c7f8d8f8aaf3acadb16af9c89dd8','g^sE4',30,'pavel.poloskov@fusionam.com','',0,1,1,0,1),(4,'Alla Mamontova','alla_ifa','c100c7f8d8f8aaf3acadb16af9c89dd8','g^sE4',30,'allasergeevna@list.ru','',0,1,1,0,1),(5,'Alla','alla_admin','c100c7f8d8f8aaf3acadb16af9c89dd8','g^sE4',30,'allasergeevna@list.ru','',0,1,1,1,1),(6,'Pavel Poloskov','pavel_admin','c100c7f8d8f8aaf3acadb16af9c89dd8','g^sE4',30,'pavel.poloskov@fusionam.com','',0,1,1,1,1);
/*!40000 ALTER TABLE `tsn_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-05-17 13:30:02
