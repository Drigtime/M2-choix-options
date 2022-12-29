-- MariaDB dump 10.19  Distrib 10.9.4-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: choix_option
-- ------------------------------------------------------
-- Server version	10.9.4-MariaDB-1:10.9.4+maria~ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `annee_formation`
--

DROP TABLE IF EXISTS `annee_formation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `annee_formation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `annee_formation`
--

LOCK TABLES `annee_formation` WRITE;
/*!40000 ALTER TABLE `annee_formation` DISABLE KEYS */;
INSERT INTO `annee_formation` VALUES
(1,'2021');
/*!40000 ALTER TABLE `annee_formation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bloc_option`
--

DROP TABLE IF EXISTS `bloc_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bloc_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campagne_choix_id` int(11) DEFAULT NULL,
  `bloc_ue_id` int(11) DEFAULT NULL,
  `nb_uechoix` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4B83AB8B81F88642` (`campagne_choix_id`),
  KEY `IDX_4B83AB8B6648E46A` (`bloc_ue_id`),
  CONSTRAINT `FK_4B83AB8B6648E46A` FOREIGN KEY (`bloc_ue_id`) REFERENCES `bloc_ue` (`id`),
  CONSTRAINT `FK_4B83AB8B81F88642` FOREIGN KEY (`campagne_choix_id`) REFERENCES `campagne_choix` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloc_option`
--

LOCK TABLES `bloc_option` WRITE;
/*!40000 ALTER TABLE `bloc_option` DISABLE KEYS */;
INSERT INTO `bloc_option` VALUES
(2,2,1,2),
(3,2,2,1);
/*!40000 ALTER TABLE `bloc_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bloc_option_ue`
--

DROP TABLE IF EXISTS `bloc_option_ue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bloc_option_ue` (
  `bloc_option_id` int(11) NOT NULL,
  `ue_id` int(11) NOT NULL,
  PRIMARY KEY (`bloc_option_id`,`ue_id`),
  KEY `IDX_2313EB69B26386A2` (`bloc_option_id`),
  KEY `IDX_2313EB6962E883B1` (`ue_id`),
  CONSTRAINT `FK_2313EB6962E883B1` FOREIGN KEY (`ue_id`) REFERENCES `ue` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2313EB69B26386A2` FOREIGN KEY (`bloc_option_id`) REFERENCES `bloc_option` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloc_option_ue`
--

LOCK TABLES `bloc_option_ue` WRITE;
/*!40000 ALTER TABLE `bloc_option_ue` DISABLE KEYS */;
INSERT INTO `bloc_option_ue` VALUES
(2,8),
(2,9),
(2,10),
(2,14),
(2,15),
(2,16),
(3,6),
(3,7);
/*!40000 ALTER TABLE `bloc_option_ue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bloc_ue`
--

DROP TABLE IF EXISTS `bloc_ue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bloc_ue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloc_ue`
--

LOCK TABLES `bloc_ue` WRITE;
/*!40000 ALTER TABLE `bloc_ue` DISABLE KEYS */;
INSERT INTO `bloc_ue` VALUES
(1,'Informatique'),
(2,'Ingénierie des Systèmes d’Information');
/*!40000 ALTER TABLE `bloc_ue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campagne_choix`
--

DROP TABLE IF EXISTS `campagne_choix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campagne_choix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parcours_id` int(11) DEFAULT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D4C770BD6E38C0DB` (`parcours_id`),
  CONSTRAINT `FK_D4C770BD6E38C0DB` FOREIGN KEY (`parcours_id`) REFERENCES `parcours` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campagne_choix`
--

LOCK TABLES `campagne_choix` WRITE;
/*!40000 ALTER TABLE `campagne_choix` DISABLE KEYS */;
INSERT INTO `campagne_choix` VALUES
(1,1,'2022-12-29 17:50:00','2022-12-30 17:50:00'),
(2,2,'2022-12-29 20:40:00','2022-12-30 20:40:00');
/*!40000 ALTER TABLE `campagne_choix` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `choix`
--

DROP TABLE IF EXISTS `choix`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `choix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etudiant_id` int(11) DEFAULT NULL,
  `ue_id` int(11) DEFAULT NULL,
  `campagne_choix_id` int(11) DEFAULT NULL,
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4F488091DDEAB1A3` (`etudiant_id`),
  KEY `IDX_4F48809162E883B1` (`ue_id`),
  KEY `IDX_4F48809181F88642` (`campagne_choix_id`),
  CONSTRAINT `FK_4F48809162E883B1` FOREIGN KEY (`ue_id`) REFERENCES `ue` (`id`),
  CONSTRAINT `FK_4F48809181F88642` FOREIGN KEY (`campagne_choix_id`) REFERENCES `campagne_choix` (`id`),
  CONSTRAINT `FK_4F488091DDEAB1A3` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiant` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `choix`
--

LOCK TABLES `choix` WRITE;
/*!40000 ALTER TABLE `choix` DISABLE KEYS */;
/*!40000 ALTER TABLE `choix` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES
('DoctrineMigrations\\Version20221221173516','2022-12-21 17:35:33',578),
('DoctrineMigrations\\Version20221223165248','2022-12-23 16:52:55',34),
('DoctrineMigrations\\Version20221223165943','2022-12-23 16:59:52',36),
('DoctrineMigrations\\Version20221224121241','2022-12-24 12:12:49',60),
('DoctrineMigrations\\Version20221225210815','2022-12-26 12:03:27',122),
('DoctrineMigrations\\Version20221225211108','2022-12-26 12:03:27',33),
('DoctrineMigrations\\Version20221228025851','2022-12-28 15:54:02',81);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etudiant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parcours_id` int(11) DEFAULT NULL,
  `groupe_id` int(11) DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_717E22E36E38C0DB` (`parcours_id`),
  KEY `IDX_717E22E37A45358C` (`groupe_id`),
  CONSTRAINT `FK_717E22E36E38C0DB` FOREIGN KEY (`parcours_id`) REFERENCES `parcours` (`id`),
  CONSTRAINT `FK_717E22E37A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `groupe` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etudiant`
--

LOCK TABLES `etudiant` WRITE;
/*!40000 ALTER TABLE `etudiant` DISABLE KEYS */;
/*!40000 ALTER TABLE `etudiant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parcours_id` int(11) DEFAULT NULL,
  `label` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4B98C216E38C0DB` (`parcours_id`),
  CONSTRAINT `FK_4B98C216E38C0DB` FOREIGN KEY (`parcours_id`) REFERENCES `parcours` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupe`
--

LOCK TABLES `groupe` WRITE;
/*!40000 ALTER TABLE `groupe` DISABLE KEYS */;
/*!40000 ALTER TABLE `groupe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
INSERT INTO `messenger_messages` VALUES
(11,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:6:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}s:57:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\TransportMessageIdStamp\\\";a:4:{i:0;O:57:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\TransportMessageIdStamp\\\":1:{s:61:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\TransportMessageIdStamp\\0id\\\";i:1;}i:1;O:57:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\TransportMessageIdStamp\\\":1:{s:61:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\TransportMessageIdStamp\\0id\\\";i:8;}i:2;O:57:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\TransportMessageIdStamp\\\":1:{s:61:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\TransportMessageIdStamp\\0id\\\";i:9;}i:3;O:57:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\TransportMessageIdStamp\\\":1:{s:61:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\TransportMessageIdStamp\\0id\\\";i:10;}}s:51:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\ErrorDetailsStamp\\\";a:1:{i:0;O:51:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\ErrorDetailsStamp\\\":4:{s:67:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\ErrorDetailsStamp\\0exceptionClass\\\";s:22:\\\"Twig\\\\Error\\\\LoaderError\\\";s:66:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\ErrorDetailsStamp\\0exceptionCode\\\";i:0;s:69:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\ErrorDetailsStamp\\0exceptionMessage\\\";s:170:\\\"Unable to find template \\\"registration/confirmation_email.html.twig\\\" (looked into: /var/www/html/templates, /var/www/html/vendor/symfony/twig-bridge/Resources/views/Form).\\\";s:69:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\ErrorDetailsStamp\\0flattenException\\\";O:57:\\\"Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\\":12:{s:66:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0message\\\";s:170:\\\"Unable to find template \\\"registration/confirmation_email.html.twig\\\" (looked into: /var/www/html/templates, /var/www/html/vendor/symfony/twig-bridge/Resources/views/Form).\\\";s:63:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0code\\\";i:0;s:67:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0previous\\\";N;s:64:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0trace\\\";a:39:{i:0;a:8:{s:9:\\\"namespace\\\";s:0:\\\"\\\";s:11:\\\"short_class\\\";s:0:\\\"\\\";s:5:\\\"class\\\";s:0:\\\"\\\";s:4:\\\"type\\\";s:0:\\\"\\\";s:8:\\\"function\\\";s:0:\\\"\\\";s:4:\\\"file\\\";s:62:\\\"/var/www/html/vendor/twig/twig/src/Loader/FilesystemLoader.php\\\";s:4:\\\"line\\\";i:227;s:4:\\\"args\\\";a:0:{}}i:1;a:8:{s:9:\\\"namespace\\\";s:11:\\\"Twig\\\\Loader\\\";s:11:\\\"short_class\\\";s:16:\\\"FilesystemLoader\\\";s:5:\\\"class\\\";s:28:\\\"Twig\\\\Loader\\\\FilesystemLoader\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:12:\\\"findTemplate\\\";s:4:\\\"file\\\";s:62:\\\"/var/www/html/vendor/twig/twig/src/Loader/FilesystemLoader.php\\\";s:4:\\\"line\\\";i:131;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:6:\\\"string\\\";i:1;s:41:\\\"registration/confirmation_email.html.twig\\\";}}}i:2;a:8:{s:9:\\\"namespace\\\";s:11:\\\"Twig\\\\Loader\\\";s:11:\\\"short_class\\\";s:16:\\\"FilesystemLoader\\\";s:5:\\\"class\\\";s:28:\\\"Twig\\\\Loader\\\\FilesystemLoader\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:11:\\\"getCacheKey\\\";s:4:\\\"file\\\";s:50:\\\"/var/www/html/vendor/twig/twig/src/Environment.php\\\";s:4:\\\"line\\\";i:261;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:6:\\\"string\\\";i:1;s:41:\\\"registration/confirmation_email.html.twig\\\";}}}i:3;a:8:{s:9:\\\"namespace\\\";s:4:\\\"Twig\\\";s:11:\\\"short_class\\\";s:11:\\\"Environment\\\";s:5:\\\"class\\\";s:16:\\\"Twig\\\\Environment\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:16:\\\"getTemplateClass\\\";s:4:\\\"file\\\";s:50:\\\"/var/www/html/vendor/twig/twig/src/Environment.php\\\";s:4:\\\"line\\\";i:309;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:6:\\\"string\\\";i:1;s:41:\\\"registration/confirmation_email.html.twig\\\";}}}i:4;a:8:{s:9:\\\"namespace\\\";s:4:\\\"Twig\\\";s:11:\\\"short_class\\\";s:11:\\\"Environment\\\";s:5:\\\"class\\\";s:16:\\\"Twig\\\\Environment\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:4:\\\"load\\\";s:4:\\\"file\\\";s:50:\\\"/var/www/html/vendor/twig/twig/src/Environment.php\\\";s:4:\\\"line\\\";i:277;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:6:\\\"string\\\";i:1;s:41:\\\"registration/confirmation_email.html.twig\\\";}}}i:5;a:8:{s:9:\\\"namespace\\\";s:4:\\\"Twig\\\";s:11:\\\"short_class\\\";s:11:\\\"Environment\\\";s:5:\\\"class\\\";s:16:\\\"Twig\\\\Environment\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:6:\\\"render\\\";s:4:\\\"file\\\";s:62:\\\"/var/www/html/vendor/symfony/twig-bridge/Mime/BodyRenderer.php\\\";s:4:\\\"line\\\";i:65;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"string\\\";i:1;s:41:\\\"registration/confirmation_email.html.twig\\\";}i:1;a:2:{i:0;s:5:\\\"array\\\";i:1;a:4:{s:9:\\\"signedUrl\\\";a:2:{i:0;s:6:\\\"string\\\";i:1;s:173:\\\"http://localhost:8101/verify/email?expires=1671818554&signature=bC5PKBeEBi%2FsdSW%2F0HN5iPvNWPffA3m4ku63y7buxAA%3D&token=%2FaQ4fKFYadLpSTSb23Ft%2FPsWDQjT3C%2B4zW7POUncRjs%3D\\\";}s:19:\\\"expiresAtMessageKey\\\";a:2:{i:0;s:6:\\\"string\\\";i:1;s:26:\\\"%count% hour|%count% hours\\\";}s:20:\\\"expiresAtMessageData\\\";a:2:{i:0;s:5:\\\"array\\\";i:1;a:1:{s:7:\\\"%count%\\\";a:2:{i:0;s:7:\\\"integer\\\";i:1;i:1;}}}s:5:\\\"email\\\";a:2:{i:0;s:6:\\\"object\\\";i:1;s:46:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\WrappedTemplatedEmail\\\";}}}}}i:6;a:8:{s:9:\\\"namespace\\\";s:24:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\";s:11:\\\"short_class\\\";s:12:\\\"BodyRenderer\\\";s:5:\\\"class\\\";s:37:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\BodyRenderer\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:6:\\\"render\\\";s:4:\\\"file\\\";s:69:\\\"/var/www/html/vendor/symfony/mailer/EventListener/MessageListener.php\\\";s:4:\\\"line\\\";i:125;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\";}}}i:7;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Component\\\\Mailer\\\\EventListener\\\";s:11:\\\"short_class\\\";s:15:\\\"MessageListener\\\";s:5:\\\"class\\\";s:54:\\\"Symfony\\\\Component\\\\Mailer\\\\EventListener\\\\MessageListener\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:13:\\\"renderMessage\\\";s:4:\\\"file\\\";s:69:\\\"/var/www/html/vendor/symfony/mailer/EventListener/MessageListener.php\\\";s:4:\\\"line\\\";i:72;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\";}}}i:8;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Component\\\\Mailer\\\\EventListener\\\";s:11:\\\"short_class\\\";s:15:\\\"MessageListener\\\";s:5:\\\"class\\\";s:54:\\\"Symfony\\\\Component\\\\Mailer\\\\EventListener\\\\MessageListener\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:9:\\\"onMessage\\\";s:4:\\\"file\\\";s:71:\\\"/var/www/html/vendor/symfony/event-dispatcher/Debug/WrappedListener.php\\\";s:4:\\\"line\\\";i:115;s:4:\\\"args\\\";a:3:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:43:\\\"Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent\\\";}i:1;a:2:{i:0;s:6:\\\"string\\\";i:1;s:43:\\\"Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent\\\";}i:2;a:2:{i:0;s:6:\\\"object\\\";i:1;s:59:\\\"Symfony\\\\Component\\\\HttpKernel\\\\Debug\\\\TraceableEventDispatcher\\\";}}}i:9;a:8:{s:9:\\\"namespace\\\";s:39:\\\"Symfony\\\\Component\\\\EventDispatcher\\\\Debug\\\";s:11:\\\"short_class\\\";s:15:\\\"WrappedListener\\\";s:5:\\\"class\\\";s:55:\\\"Symfony\\\\Component\\\\EventDispatcher\\\\Debug\\\\WrappedListener\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:8:\\\"__invoke\\\";s:4:\\\"file\\\";s:65:\\\"/var/www/html/vendor/symfony/event-dispatcher/EventDispatcher.php\\\";s:4:\\\"line\\\";i:206;s:4:\\\"args\\\";a:3:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:43:\\\"Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent\\\";}i:1;a:2:{i:0;s:6:\\\"string\\\";i:1;s:43:\\\"Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent\\\";}i:2;a:2:{i:0;s:6:\\\"object\\\";i:1;s:59:\\\"Symfony\\\\Component\\\\HttpKernel\\\\Debug\\\\TraceableEventDispatcher\\\";}}}i:10;a:8:{s:9:\\\"namespace\\\";s:33:\\\"Symfony\\\\Component\\\\EventDispatcher\\\";s:11:\\\"short_class\\\";s:15:\\\"EventDispatcher\\\";s:5:\\\"class\\\";s:49:\\\"Symfony\\\\Component\\\\EventDispatcher\\\\EventDispatcher\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:13:\\\"callListeners\\\";s:4:\\\"file\\\";s:65:\\\"/var/www/html/vendor/symfony/event-dispatcher/EventDispatcher.php\\\";s:4:\\\"line\\\";i:56;s:4:\\\"args\\\";a:3:{i:0;a:2:{i:0;s:5:\\\"array\\\";i:1;a:4:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:55:\\\"Symfony\\\\Component\\\\EventDispatcher\\\\Debug\\\\WrappedListener\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:55:\\\"Symfony\\\\Component\\\\EventDispatcher\\\\Debug\\\\WrappedListener\\\";}i:2;a:2:{i:0;s:6:\\\"object\\\";i:1;s:55:\\\"Symfony\\\\Component\\\\EventDispatcher\\\\Debug\\\\WrappedListener\\\";}i:3;a:2:{i:0;s:6:\\\"object\\\";i:1;s:55:\\\"Symfony\\\\Component\\\\EventDispatcher\\\\Debug\\\\WrappedListener\\\";}}}i:1;a:2:{i:0;s:6:\\\"string\\\";i:1;s:43:\\\"Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent\\\";}i:2;a:2:{i:0;s:6:\\\"object\\\";i:1;s:43:\\\"Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent\\\";}}}i:11;a:8:{s:9:\\\"namespace\\\";s:33:\\\"Symfony\\\\Component\\\\EventDispatcher\\\";s:11:\\\"short_class\\\";s:15:\\\"EventDispatcher\\\";s:5:\\\"class\\\";s:49:\\\"Symfony\\\\Component\\\\EventDispatcher\\\\EventDispatcher\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:8:\\\"dispatch\\\";s:4:\\\"file\\\";s:80:\\\"/var/www/html/vendor/symfony/event-dispatcher/Debug/TraceableEventDispatcher.php\\\";s:4:\\\"line\\\";i:127;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:43:\\\"Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent\\\";}i:1;a:2:{i:0;s:6:\\\"string\\\";i:1;s:43:\\\"Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent\\\";}}}i:12;a:8:{s:9:\\\"namespace\\\";s:39:\\\"Symfony\\\\Component\\\\EventDispatcher\\\\Debug\\\";s:11:\\\"short_class\\\";s:24:\\\"TraceableEventDispatcher\\\";s:5:\\\"class\\\";s:64:\\\"Symfony\\\\Component\\\\EventDispatcher\\\\Debug\\\\TraceableEventDispatcher\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:8:\\\"dispatch\\\";s:4:\\\"file\\\";s:67:\\\"/var/www/html/vendor/symfony/mailer/Transport/AbstractTransport.php\\\";s:4:\\\"line\\\";i:75;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:43:\\\"Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent\\\";}}}i:13;a:8:{s:9:\\\"namespace\\\";s:34:\\\"Symfony\\\\Component\\\\Mailer\\\\Transport\\\";s:11:\\\"short_class\\\";s:17:\\\"AbstractTransport\\\";s:5:\\\"class\\\";s:52:\\\"Symfony\\\\Component\\\\Mailer\\\\Transport\\\\AbstractTransport\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:4:\\\"send\\\";s:4:\\\"file\\\";s:68:\\\"/var/www/html/vendor/symfony/mailer/Transport/Smtp/SmtpTransport.php\\\";s:4:\\\"line\\\";i:137;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:40:\\\"Symfony\\\\Component\\\\Mailer\\\\DelayedEnvelope\\\";}}}i:14;a:8:{s:9:\\\"namespace\\\";s:39:\\\"Symfony\\\\Component\\\\Mailer\\\\Transport\\\\Smtp\\\";s:11:\\\"short_class\\\";s:13:\\\"SmtpTransport\\\";s:5:\\\"class\\\";s:53:\\\"Symfony\\\\Component\\\\Mailer\\\\Transport\\\\Smtp\\\\SmtpTransport\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:4:\\\"send\\\";s:4:\\\"file\\\";s:60:\\\"/var/www/html/vendor/symfony/mailer/Transport/Transports.php\\\";s:4:\\\"line\\\";i:51;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\";}i:1;a:2:{i:0;s:4:\\\"null\\\";i:1;N;}}}i:15;a:8:{s:9:\\\"namespace\\\";s:34:\\\"Symfony\\\\Component\\\\Mailer\\\\Transport\\\";s:11:\\\"short_class\\\";s:10:\\\"Transports\\\";s:5:\\\"class\\\";s:45:\\\"Symfony\\\\Component\\\\Mailer\\\\Transport\\\\Transports\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:4:\\\"send\\\";s:4:\\\"file\\\";s:64:\\\"/var/www/html/vendor/symfony/mailer/Messenger/MessageHandler.php\\\";s:4:\\\"line\\\";i:31;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\";}i:1;a:2:{i:0;s:4:\\\"null\\\";i:1;N;}}}i:16;a:8:{s:9:\\\"namespace\\\";s:34:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\";s:11:\\\"short_class\\\";s:14:\\\"MessageHandler\\\";s:5:\\\"class\\\";s:49:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\MessageHandler\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:8:\\\"__invoke\\\";s:4:\\\"file\\\";s:77:\\\"/var/www/html/vendor/symfony/messenger/Middleware/HandleMessageMiddleware.php\\\";s:4:\\\"line\\\";i:157;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\";}}}i:17;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\";s:11:\\\"short_class\\\";s:23:\\\"HandleMessageMiddleware\\\";s:5:\\\"class\\\";s:62:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\HandleMessageMiddleware\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:11:\\\"callHandler\\\";s:4:\\\"file\\\";s:77:\\\"/var/www/html/vendor/symfony/messenger/Middleware/HandleMessageMiddleware.php\\\";s:4:\\\"line\\\";i:96;s:4:\\\"args\\\";a:4:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:7:\\\"Closure\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\";}i:2;a:2:{i:0;s:4:\\\"null\\\";i:1;N;}i:3;a:2:{i:0;s:4:\\\"null\\\";i:1;N;}}}i:18;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\";s:11:\\\"short_class\\\";s:23:\\\"HandleMessageMiddleware\\\";s:5:\\\"class\\\";s:62:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\HandleMessageMiddleware\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:6:\\\"handle\\\";s:4:\\\"file\\\";s:75:\\\"/var/www/html/vendor/symfony/messenger/Middleware/SendMessageMiddleware.php\\\";s:4:\\\"line\\\";i:77;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:53:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack\\\";}}}i:19;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\";s:11:\\\"short_class\\\";s:21:\\\"SendMessageMiddleware\\\";s:5:\\\"class\\\";s:60:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\SendMessageMiddleware\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:6:\\\"handle\\\";s:4:\\\"file\\\";s:87:\\\"/var/www/html/vendor/symfony/messenger/Middleware/FailedMessageProcessingMiddleware.php\\\";s:4:\\\"line\\\";i:34;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:53:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack\\\";}}}i:20;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\";s:11:\\\"short_class\\\";s:33:\\\"FailedMessageProcessingMiddleware\\\";s:5:\\\"class\\\";s:72:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\FailedMessageProcessingMiddleware\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:6:\\\"handle\\\";s:4:\\\"file\\\";s:87:\\\"/var/www/html/vendor/symfony/messenger/Middleware/DispatchAfterCurrentBusMiddleware.php\\\";s:4:\\\"line\\\";i:68;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:53:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack\\\";}}}i:21;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\";s:11:\\\"short_class\\\";s:33:\\\"DispatchAfterCurrentBusMiddleware\\\";s:5:\\\"class\\\";s:72:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\DispatchAfterCurrentBusMiddleware\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:6:\\\"handle\\\";s:4:\\\"file\\\";s:88:\\\"/var/www/html/vendor/symfony/messenger/Middleware/RejectRedeliveredMessageMiddleware.php\\\";s:4:\\\"line\\\";i:41;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:53:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack\\\";}}}i:22;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\";s:11:\\\"short_class\\\";s:34:\\\"RejectRedeliveredMessageMiddleware\\\";s:5:\\\"class\\\";s:73:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\RejectRedeliveredMessageMiddleware\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:6:\\\"handle\\\";s:4:\\\"file\\\";s:79:\\\"/var/www/html/vendor/symfony/messenger/Middleware/AddBusNameStampMiddleware.php\\\";s:4:\\\"line\\\";i:37;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:53:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack\\\";}}}i:23;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\";s:11:\\\"short_class\\\";s:25:\\\"AddBusNameStampMiddleware\\\";s:5:\\\"class\\\";s:64:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\AddBusNameStampMiddleware\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:6:\\\"handle\\\";s:4:\\\"file\\\";s:73:\\\"/var/www/html/vendor/symfony/messenger/Middleware/TraceableMiddleware.php\\\";s:4:\\\"line\\\";i:40;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:53:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack\\\";}}}i:24;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\";s:11:\\\"short_class\\\";s:19:\\\"TraceableMiddleware\\\";s:5:\\\"class\\\";s:58:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableMiddleware\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:6:\\\"handle\\\";s:4:\\\"file\\\";s:53:\\\"/var/www/html/vendor/symfony/messenger/MessageBus.php\\\";s:4:\\\"line\\\";i:70;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:53:\\\"Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack\\\";}}}i:25;a:8:{s:9:\\\"namespace\\\";s:27:\\\"Symfony\\\\Component\\\\Messenger\\\";s:11:\\\"short_class\\\";s:10:\\\"MessageBus\\\";s:5:\\\"class\\\";s:38:\\\"Symfony\\\\Component\\\\Messenger\\\\MessageBus\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:8:\\\"dispatch\\\";s:4:\\\"file\\\";s:62:\\\"/var/www/html/vendor/symfony/messenger/TraceableMessageBus.php\\\";s:4:\\\"line\\\";i:38;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}i:1;a:2:{i:0;s:5:\\\"array\\\";i:1;a:0:{}}}}i:26;a:8:{s:9:\\\"namespace\\\";s:27:\\\"Symfony\\\\Component\\\\Messenger\\\";s:11:\\\"short_class\\\";s:19:\\\"TraceableMessageBus\\\";s:5:\\\"class\\\";s:47:\\\"Symfony\\\\Component\\\\Messenger\\\\TraceableMessageBus\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:8:\\\"dispatch\\\";s:4:\\\"file\\\";s:61:\\\"/var/www/html/vendor/symfony/messenger/RoutableMessageBus.php\\\";s:4:\\\"line\\\";i:54;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}i:1;a:2:{i:0;s:5:\\\"array\\\";i:1;a:0:{}}}}i:27;a:8:{s:9:\\\"namespace\\\";s:27:\\\"Symfony\\\\Component\\\\Messenger\\\";s:11:\\\"short_class\\\";s:18:\\\"RoutableMessageBus\\\";s:5:\\\"class\\\";s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\RoutableMessageBus\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:8:\\\"dispatch\\\";s:4:\\\"file\\\";s:49:\\\"/var/www/html/vendor/symfony/messenger/Worker.php\\\";s:4:\\\"line\\\";i:161;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}}}i:28;a:8:{s:9:\\\"namespace\\\";s:27:\\\"Symfony\\\\Component\\\\Messenger\\\";s:11:\\\"short_class\\\";s:6:\\\"Worker\\\";s:5:\\\"class\\\";s:34:\\\"Symfony\\\\Component\\\\Messenger\\\\Worker\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:13:\\\"handleMessage\\\";s:4:\\\"file\\\";s:49:\\\"/var/www/html/vendor/symfony/messenger/Worker.php\\\";s:4:\\\"line\\\";i:110;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\";}i:1;a:2:{i:0;s:6:\\\"string\\\";i:1;s:5:\\\"async\\\";}}}i:29;a:8:{s:9:\\\"namespace\\\";s:27:\\\"Symfony\\\\Component\\\\Messenger\\\";s:11:\\\"short_class\\\";s:6:\\\"Worker\\\";s:5:\\\"class\\\";s:34:\\\"Symfony\\\\Component\\\\Messenger\\\\Worker\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:3:\\\"run\\\";s:4:\\\"file\\\";s:73:\\\"/var/www/html/vendor/symfony/messenger/Command/ConsumeMessagesCommand.php\\\";s:4:\\\"line\\\";i:229;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:5:\\\"array\\\";i:1;a:1:{s:5:\\\"sleep\\\";a:2:{i:0;s:7:\\\"integer\\\";i:1;i:1000000;}}}}}i:30;a:8:{s:9:\\\"namespace\\\";s:35:\\\"Symfony\\\\Component\\\\Messenger\\\\Command\\\";s:11:\\\"short_class\\\";s:22:\\\"ConsumeMessagesCommand\\\";s:5:\\\"class\\\";s:58:\\\"Symfony\\\\Component\\\\Messenger\\\\Command\\\\ConsumeMessagesCommand\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:7:\\\"execute\\\";s:4:\\\"file\\\";s:56:\\\"/var/www/html/vendor/symfony/console/Command/Command.php\\\";s:4:\\\"line\\\";i:312;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:41:\\\"Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:46:\\\"Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput\\\";}}}i:31;a:8:{s:9:\\\"namespace\\\";s:33:\\\"Symfony\\\\Component\\\\Console\\\\Command\\\";s:11:\\\"short_class\\\";s:7:\\\"Command\\\";s:5:\\\"class\\\";s:41:\\\"Symfony\\\\Component\\\\Console\\\\Command\\\\Command\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:3:\\\"run\\\";s:4:\\\"file\\\";s:52:\\\"/var/www/html/vendor/symfony/console/Application.php\\\";s:4:\\\"line\\\";i:1038;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:41:\\\"Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:46:\\\"Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput\\\";}}}i:32;a:8:{s:9:\\\"namespace\\\";s:25:\\\"Symfony\\\\Component\\\\Console\\\";s:11:\\\"short_class\\\";s:11:\\\"Application\\\";s:5:\\\"class\\\";s:37:\\\"Symfony\\\\Component\\\\Console\\\\Application\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:12:\\\"doRunCommand\\\";s:4:\\\"file\\\";s:69:\\\"/var/www/html/vendor/symfony/framework-bundle/Console/Application.php\\\";s:4:\\\"line\\\";i:88;s:4:\\\"args\\\";a:3:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:58:\\\"Symfony\\\\Component\\\\Messenger\\\\Command\\\\ConsumeMessagesCommand\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:41:\\\"Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput\\\";}i:2;a:2:{i:0;s:6:\\\"object\\\";i:1;s:46:\\\"Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput\\\";}}}i:33;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Bundle\\\\FrameworkBundle\\\\Console\\\";s:11:\\\"short_class\\\";s:11:\\\"Application\\\";s:5:\\\"class\\\";s:50:\\\"Symfony\\\\Bundle\\\\FrameworkBundle\\\\Console\\\\Application\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:12:\\\"doRunCommand\\\";s:4:\\\"file\\\";s:52:\\\"/var/www/html/vendor/symfony/console/Application.php\\\";s:4:\\\"line\\\";i:312;s:4:\\\"args\\\";a:3:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:58:\\\"Symfony\\\\Component\\\\Messenger\\\\Command\\\\ConsumeMessagesCommand\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:41:\\\"Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput\\\";}i:2;a:2:{i:0;s:6:\\\"object\\\";i:1;s:46:\\\"Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput\\\";}}}i:34;a:8:{s:9:\\\"namespace\\\";s:25:\\\"Symfony\\\\Component\\\\Console\\\";s:11:\\\"short_class\\\";s:11:\\\"Application\\\";s:5:\\\"class\\\";s:37:\\\"Symfony\\\\Component\\\\Console\\\\Application\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:5:\\\"doRun\\\";s:4:\\\"file\\\";s:69:\\\"/var/www/html/vendor/symfony/framework-bundle/Console/Application.php\\\";s:4:\\\"line\\\";i:77;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:41:\\\"Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:46:\\\"Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput\\\";}}}i:35;a:8:{s:9:\\\"namespace\\\";s:38:\\\"Symfony\\\\Bundle\\\\FrameworkBundle\\\\Console\\\";s:11:\\\"short_class\\\";s:11:\\\"Application\\\";s:5:\\\"class\\\";s:50:\\\"Symfony\\\\Bundle\\\\FrameworkBundle\\\\Console\\\\Application\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:5:\\\"doRun\\\";s:4:\\\"file\\\";s:52:\\\"/var/www/html/vendor/symfony/console/Application.php\\\";s:4:\\\"line\\\";i:168;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:41:\\\"Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:46:\\\"Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput\\\";}}}i:36;a:8:{s:9:\\\"namespace\\\";s:25:\\\"Symfony\\\\Component\\\\Console\\\";s:11:\\\"short_class\\\";s:11:\\\"Application\\\";s:5:\\\"class\\\";s:37:\\\"Symfony\\\\Component\\\\Console\\\\Application\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:3:\\\"run\\\";s:4:\\\"file\\\";s:80:\\\"/var/www/html/vendor/symfony/runtime/Runner/Symfony/ConsoleApplicationRunner.php\\\";s:4:\\\"line\\\";i:54;s:4:\\\"args\\\";a:2:{i:0;a:2:{i:0;s:6:\\\"object\\\";i:1;s:41:\\\"Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput\\\";}i:1;a:2:{i:0;s:6:\\\"object\\\";i:1;s:46:\\\"Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput\\\";}}}i:37;a:8:{s:9:\\\"namespace\\\";s:40:\\\"Symfony\\\\Component\\\\Runtime\\\\Runner\\\\Symfony\\\";s:11:\\\"short_class\\\";s:24:\\\"ConsoleApplicationRunner\\\";s:5:\\\"class\\\";s:65:\\\"Symfony\\\\Component\\\\Runtime\\\\Runner\\\\Symfony\\\\ConsoleApplicationRunner\\\";s:4:\\\"type\\\";s:2:\\\"->\\\";s:8:\\\"function\\\";s:3:\\\"run\\\";s:4:\\\"file\\\";s:41:\\\"/var/www/html/vendor/autoload_runtime.php\\\";s:4:\\\"line\\\";i:29;s:4:\\\"args\\\";a:0:{}}i:38;a:8:{s:9:\\\"namespace\\\";s:0:\\\"\\\";s:11:\\\"short_class\\\";s:0:\\\"\\\";s:5:\\\"class\\\";s:0:\\\"\\\";s:4:\\\"type\\\";s:0:\\\"\\\";s:8:\\\"function\\\";s:12:\\\"require_once\\\";s:4:\\\"file\\\";s:25:\\\"/var/www/html/bin/console\\\";s:4:\\\"line\\\";i:11;s:4:\\\"args\\\";a:1:{i:0;a:2:{i:0;s:6:\\\"string\\\";i:1;s:41:\\\"/var/www/html/vendor/autoload_runtime.php\\\";}}}}s:72:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0traceAsString\\\";s:7962:\\\"#0 /var/www/html/vendor/twig/twig/src/Loader/FilesystemLoader.php(131): Twig\\\\Loader\\\\FilesystemLoader->findTemplate(\\\'registration/co...\\\')\n#1 /var/www/html/vendor/twig/twig/src/Environment.php(261): Twig\\\\Loader\\\\FilesystemLoader->getCacheKey(\\\'registration/co...\\\')\n#2 /var/www/html/vendor/twig/twig/src/Environment.php(309): Twig\\\\Environment->getTemplateClass(\\\'registration/co...\\\')\n#3 /var/www/html/vendor/twig/twig/src/Environment.php(277): Twig\\\\Environment->load(\\\'registration/co...\\\')\n#4 /var/www/html/vendor/symfony/twig-bridge/Mime/BodyRenderer.php(65): Twig\\\\Environment->render(\\\'registration/co...\\\', Array)\n#5 /var/www/html/vendor/symfony/mailer/EventListener/MessageListener.php(125): Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\BodyRenderer->render(Object(Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail))\n#6 /var/www/html/vendor/symfony/mailer/EventListener/MessageListener.php(72): Symfony\\\\Component\\\\Mailer\\\\EventListener\\\\MessageListener->renderMessage(Object(Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail))\n#7 /var/www/html/vendor/symfony/event-dispatcher/Debug/WrappedListener.php(115): Symfony\\\\Component\\\\Mailer\\\\EventListener\\\\MessageListener->onMessage(Object(Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent), \\\'Symfony\\\\\\\\Compone...\\\', Object(Symfony\\\\Component\\\\HttpKernel\\\\Debug\\\\TraceableEventDispatcher))\n#8 /var/www/html/vendor/symfony/event-dispatcher/EventDispatcher.php(206): Symfony\\\\Component\\\\EventDispatcher\\\\Debug\\\\WrappedListener->__invoke(Object(Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent), \\\'Symfony\\\\\\\\Compone...\\\', Object(Symfony\\\\Component\\\\HttpKernel\\\\Debug\\\\TraceableEventDispatcher))\n#9 /var/www/html/vendor/symfony/event-dispatcher/EventDispatcher.php(56): Symfony\\\\Component\\\\EventDispatcher\\\\EventDispatcher->callListeners(Array, \\\'Symfony\\\\\\\\Compone...\\\', Object(Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent))\n#10 /var/www/html/vendor/symfony/event-dispatcher/Debug/TraceableEventDispatcher.php(127): Symfony\\\\Component\\\\EventDispatcher\\\\EventDispatcher->dispatch(Object(Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent), \\\'Symfony\\\\\\\\Compone...\\\')\n#11 /var/www/html/vendor/symfony/mailer/Transport/AbstractTransport.php(75): Symfony\\\\Component\\\\EventDispatcher\\\\Debug\\\\TraceableEventDispatcher->dispatch(Object(Symfony\\\\Component\\\\Mailer\\\\Event\\\\MessageEvent))\n#12 /var/www/html/vendor/symfony/mailer/Transport/Smtp/SmtpTransport.php(137): Symfony\\\\Component\\\\Mailer\\\\Transport\\\\AbstractTransport->send(Object(Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail), Object(Symfony\\\\Component\\\\Mailer\\\\DelayedEnvelope))\n#13 /var/www/html/vendor/symfony/mailer/Transport/Transports.php(51): Symfony\\\\Component\\\\Mailer\\\\Transport\\\\Smtp\\\\SmtpTransport->send(Object(Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail), NULL)\n#14 /var/www/html/vendor/symfony/mailer/Messenger/MessageHandler.php(31): Symfony\\\\Component\\\\Mailer\\\\Transport\\\\Transports->send(Object(Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail), NULL)\n#15 /var/www/html/vendor/symfony/messenger/Middleware/HandleMessageMiddleware.php(157): Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\MessageHandler->__invoke(Object(Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage))\n#16 /var/www/html/vendor/symfony/messenger/Middleware/HandleMessageMiddleware.php(96): Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\HandleMessageMiddleware->callHandler(Object(Closure), Object(Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage), NULL, NULL)\n#17 /var/www/html/vendor/symfony/messenger/Middleware/SendMessageMiddleware.php(77): Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\HandleMessageMiddleware->handle(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope), Object(Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack))\n#18 /var/www/html/vendor/symfony/messenger/Middleware/FailedMessageProcessingMiddleware.php(34): Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\SendMessageMiddleware->handle(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope), Object(Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack))\n#19 /var/www/html/vendor/symfony/messenger/Middleware/DispatchAfterCurrentBusMiddleware.php(68): Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\FailedMessageProcessingMiddleware->handle(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope), Object(Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack))\n#20 /var/www/html/vendor/symfony/messenger/Middleware/RejectRedeliveredMessageMiddleware.php(41): Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\DispatchAfterCurrentBusMiddleware->handle(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope), Object(Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack))\n#21 /var/www/html/vendor/symfony/messenger/Middleware/AddBusNameStampMiddleware.php(37): Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\RejectRedeliveredMessageMiddleware->handle(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope), Object(Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack))\n#22 /var/www/html/vendor/symfony/messenger/Middleware/TraceableMiddleware.php(40): Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\AddBusNameStampMiddleware->handle(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope), Object(Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack))\n#23 /var/www/html/vendor/symfony/messenger/MessageBus.php(70): Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableMiddleware->handle(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope), Object(Symfony\\\\Component\\\\Messenger\\\\Middleware\\\\TraceableStack))\n#24 /var/www/html/vendor/symfony/messenger/TraceableMessageBus.php(38): Symfony\\\\Component\\\\Messenger\\\\MessageBus->dispatch(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope), Array)\n#25 /var/www/html/vendor/symfony/messenger/RoutableMessageBus.php(54): Symfony\\\\Component\\\\Messenger\\\\TraceableMessageBus->dispatch(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope), Array)\n#26 /var/www/html/vendor/symfony/messenger/Worker.php(161): Symfony\\\\Component\\\\Messenger\\\\RoutableMessageBus->dispatch(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope))\n#27 /var/www/html/vendor/symfony/messenger/Worker.php(110): Symfony\\\\Component\\\\Messenger\\\\Worker->handleMessage(Object(Symfony\\\\Component\\\\Messenger\\\\Envelope), \\\'async\\\')\n#28 /var/www/html/vendor/symfony/messenger/Command/ConsumeMessagesCommand.php(229): Symfony\\\\Component\\\\Messenger\\\\Worker->run(Array)\n#29 /var/www/html/vendor/symfony/console/Command/Command.php(312): Symfony\\\\Component\\\\Messenger\\\\Command\\\\ConsumeMessagesCommand->execute(Object(Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput), Object(Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput))\n#30 /var/www/html/vendor/symfony/console/Application.php(1038): Symfony\\\\Component\\\\Console\\\\Command\\\\Command->run(Object(Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput), Object(Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput))\n#31 /var/www/html/vendor/symfony/framework-bundle/Console/Application.php(88): Symfony\\\\Component\\\\Console\\\\Application->doRunCommand(Object(Symfony\\\\Component\\\\Messenger\\\\Command\\\\ConsumeMessagesCommand), Object(Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput), Object(Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput))\n#32 /var/www/html/vendor/symfony/console/Application.php(312): Symfony\\\\Bundle\\\\FrameworkBundle\\\\Console\\\\Application->doRunCommand(Object(Symfony\\\\Component\\\\Messenger\\\\Command\\\\ConsumeMessagesCommand), Object(Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput), Object(Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput))\n#33 /var/www/html/vendor/symfony/framework-bundle/Console/Application.php(77): Symfony\\\\Component\\\\Console\\\\Application->doRun(Object(Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput), Object(Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput))\n#34 /var/www/html/vendor/symfony/console/Application.php(168): Symfony\\\\Bundle\\\\FrameworkBundle\\\\Console\\\\Application->doRun(Object(Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput), Object(Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput))\n#35 /var/www/html/vendor/symfony/runtime/Runner/Symfony/ConsoleApplicationRunner.php(54): Symfony\\\\Component\\\\Console\\\\Application->run(Object(Symfony\\\\Component\\\\Console\\\\Input\\\\ArgvInput), Object(Symfony\\\\Component\\\\Console\\\\Output\\\\ConsoleOutput))\n#36 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\\\Component\\\\Runtime\\\\Runner\\\\Symfony\\\\ConsoleApplicationRunner->run()\n#37 /var/www/html/bin/console(11): require_once(\\\'/var/www/html/v...\\\')\n#38 {main}\\\";s:64:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0class\\\";s:22:\\\"Twig\\\\Error\\\\LoaderError\\\";s:69:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0statusCode\\\";i:500;s:69:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0statusText\\\";s:21:\\\"Internal Server Error\\\";s:66:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0headers\\\";a:0:{}s:63:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0file\\\";s:62:\\\"/var/www/html/vendor/twig/twig/src/Loader/FilesystemLoader.php\\\";s:63:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0line\\\";i:227;s:67:\\\"\\0Symfony\\\\Component\\\\ErrorHandler\\\\Exception\\\\FlattenException\\0asString\\\";N;}}}s:44:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\DelayStamp\\\";a:4:{i:0;O:44:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\DelayStamp\\\":1:{s:51:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\DelayStamp\\0delay\\\";i:1000;}i:1;O:44:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\DelayStamp\\\":1:{s:51:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\DelayStamp\\0delay\\\";i:2000;}i:2;O:44:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\DelayStamp\\\":1:{s:51:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\DelayStamp\\0delay\\\";i:4000;}i:3;O:44:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\DelayStamp\\\":1:{s:51:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\DelayStamp\\0delay\\\";i:0;}}s:49:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\\";a:4:{i:0;O:49:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\\":2:{s:61:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\0retryCount\\\";i:1;s:64:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\0redeliveredAt\\\";O:17:\\\"DateTimeImmutable\\\":3:{s:4:\\\"date\\\";s:26:\\\"2022-12-24 12:50:44.354973\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}i:1;O:49:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\\":2:{s:61:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\0retryCount\\\";i:2;s:64:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\0redeliveredAt\\\";O:17:\\\"DateTimeImmutable\\\":3:{s:4:\\\"date\\\";s:26:\\\"2022-12-24 12:50:45.731686\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}i:2;O:49:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\\":2:{s:61:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\0retryCount\\\";i:3;s:64:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\0redeliveredAt\\\";O:17:\\\"DateTimeImmutable\\\":3:{s:4:\\\"date\\\";s:26:\\\"2022-12-24 12:50:47.742801\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}i:3;O:49:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\\":2:{s:61:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\0retryCount\\\";i:0;s:64:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\RedeliveryStamp\\0redeliveredAt\\\";O:17:\\\"DateTimeImmutable\\\":3:{s:4:\\\"date\\\";s:26:\\\"2022-12-24 12:50:51.757097\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}}s:61:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\SentToFailureTransportStamp\\\";a:1:{i:0;O:61:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\SentToFailureTransportStamp\\\":1:{s:83:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\SentToFailureTransportStamp\\0originalReceiverName\\\";s:5:\\\"async\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":4:{i:0;s:41:\\\"registration/confirmation_email.html.twig\\\";i:1;N;i:2;a:3:{s:9:\\\"signedUrl\\\";s:173:\\\"http://localhost:8101/verify/email?expires=1671818554&signature=bC5PKBeEBi%2FsdSW%2F0HN5iPvNWPffA3m4ku63y7buxAA%3D&token=%2FaQ4fKFYadLpSTSb23Ft%2FPsWDQjT3C%2B4zW7POUncRjs%3D\\\";s:19:\\\"expiresAtMessageKey\\\";s:26:\\\"%count% hour|%count% hours\\\";s:20:\\\"expiresAtMessageData\\\";a:1:{s:7:\\\"%count%\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:20:\\\"choixoption@upjv.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:17:\\\"Choix Option UPJV\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:28:\\\"william.varlet8020@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:25:\\\"Please Confirm your Email\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}','[]','failed','2022-12-24 12:50:51','2022-12-24 12:50:51',NULL);
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parcours`
--

DROP TABLE IF EXISTS `parcours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parcours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `annee_formation_id` int(11) DEFAULT NULL,
  `rythme_id` int(11) DEFAULT NULL,
  `specialisation_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_99B1DEE33A687361` (`annee_formation_id`),
  KEY `IDX_99B1DEE38399A4A6` (`rythme_id`),
  KEY `IDX_99B1DEE35627D44C` (`specialisation_id`),
  CONSTRAINT `FK_99B1DEE33A687361` FOREIGN KEY (`annee_formation_id`) REFERENCES `annee_formation` (`id`),
  CONSTRAINT `FK_99B1DEE35627D44C` FOREIGN KEY (`specialisation_id`) REFERENCES `specialisation` (`id`),
  CONSTRAINT `FK_99B1DEE38399A4A6` FOREIGN KEY (`rythme_id`) REFERENCES `rythme` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcours`
--

LOCK TABLES `parcours` WRITE;
/*!40000 ALTER TABLE `parcours` DISABLE KEYS */;
INSERT INTO `parcours` VALUES
(1,1,1,1),
(2,1,1,2);
/*!40000 ALTER TABLE `parcours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parcours_bloc_ue`
--

DROP TABLE IF EXISTS `parcours_bloc_ue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parcours_bloc_ue` (
  `parcours_id` int(11) NOT NULL,
  `bloc_ue_id` int(11) NOT NULL,
  PRIMARY KEY (`parcours_id`,`bloc_ue_id`),
  KEY `IDX_4B99BA5D6E38C0DB` (`parcours_id`),
  KEY `IDX_4B99BA5D6648E46A` (`bloc_ue_id`),
  CONSTRAINT `FK_4B99BA5D6648E46A` FOREIGN KEY (`bloc_ue_id`) REFERENCES `bloc_ue` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_4B99BA5D6E38C0DB` FOREIGN KEY (`parcours_id`) REFERENCES `parcours` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcours_bloc_ue`
--

LOCK TABLES `parcours_bloc_ue` WRITE;
/*!40000 ALTER TABLE `parcours_bloc_ue` DISABLE KEYS */;
INSERT INTO `parcours_bloc_ue` VALUES
(1,1),
(1,2),
(2,1),
(2,2);
/*!40000 ALTER TABLE `parcours_bloc_ue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reset_password_token`
--

DROP TABLE IF EXISTS `reset_password_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reset_password_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `expired_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_452C9EC5A76ED395` (`user_id`),
  CONSTRAINT `FK_452C9EC5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reset_password_token`
--

LOCK TABLES `reset_password_token` WRITE;
/*!40000 ALTER TABLE `reset_password_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `reset_password_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rythme`
--

DROP TABLE IF EXISTS `rythme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rythme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rythme`
--

LOCK TABLES `rythme` WRITE;
/*!40000 ALTER TABLE `rythme` DISABLE KEYS */;
INSERT INTO `rythme` VALUES
(1,'Alternant'),
(2,'Stagiaire');
/*!40000 ALTER TABLE `rythme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialisation`
--

DROP TABLE IF EXISTS `specialisation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specialisation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(45) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialisation`
--

LOCK TABLES `specialisation` WRITE;
/*!40000 ALTER TABLE `specialisation` DISABLE KEYS */;
INSERT INTO `specialisation` VALUES
(1,'M2 SIO',1),
(2,'M1',1);
/*!40000 ALTER TABLE `specialisation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ue`
--

DROP TABLE IF EXISTS `ue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bloc_ue_id` int(11) DEFAULT NULL,
  `label` varchar(45) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `IDX_2E490A9B6648E46A` (`bloc_ue_id`),
  CONSTRAINT `FK_2E490A9B6648E46A` FOREIGN KEY (`bloc_ue_id`) REFERENCES `bloc_ue` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ue`
--

LOCK TABLES `ue` WRITE;
/*!40000 ALTER TABLE `ue` DISABLE KEYS */;
INSERT INTO `ue` VALUES
(4,2,'ISI_02 Conduite de projet',1),
(5,2,'ISI_05 Ingénierie du logiciel',1),
(6,2,'ISI_01 Architecture client-serveur',1),
(7,2,'ISI_04 Contrôle qualité et green IT',1),
(8,1,'INFO_02 Analyse et décision en entreprise',1),
(9,1,'INFO_04 Architecture des SI',1),
(10,1,'INFO_03 Architecture Web des SI',1),
(11,1,'INFO_06 BD avancées',1),
(12,1,'INFO_09 UX Design',1),
(13,1,'INFO_01 Administration des Systèmes d’exploit',1),
(14,1,'INFO_07 Bio informatique 1',1),
(15,1,'INFO_05 ASI mobiles 1',1),
(16,1,'INFO_08 Conception avancée des Systèmes d’inf',1);
/*!40000 ALTER TABLE `ue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'william.varlet8020@gmail.com','[\"ROLE_ADMIN\"]','$2y$13$nQZgD2X6K6/1SkgVvlbZMeZWlG2qX1Mo9bQDdgYqoZL0HWXE.zCUe',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-12-29 23:53:47
