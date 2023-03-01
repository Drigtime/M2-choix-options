-- MariaDB dump 10.19  Distrib 10.9.4-MariaDB, for debian-linux-gnu (aarch64)
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `annee_formation`
--

LOCK TABLES `annee_formation` WRITE;
/*!40000 ALTER TABLE `annee_formation` DISABLE KEYS */;
INSERT INTO `annee_formation` VALUES
(1,'M1'),
(3,'M2');
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloc_option`
--

LOCK TABLES `bloc_option` WRITE;
/*!40000 ALTER TABLE `bloc_option` DISABLE KEYS */;
INSERT INTO `bloc_option` VALUES
(21,8,44,4),
(22,8,43,1),
(24,9,43,1),
(26,10,44,1),
(27,10,43,1),
(28,7,43,1),
(29,7,47,1);
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
(21,8),
(21,9),
(22,4),
(22,5),
(26,9),
(26,10),
(27,5),
(28,4),
(29,8),
(29,9);
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
  `bloc_uecategory_id` int(11) NOT NULL,
  `parcours_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C4F2840B9865110C` (`bloc_uecategory_id`),
  KEY `IDX_C4F2840B6E38C0DB` (`parcours_id`),
  CONSTRAINT `FK_C4F2840B6E38C0DB` FOREIGN KEY (`parcours_id`) REFERENCES `parcours` (`id`),
  CONSTRAINT `FK_C4F2840B9865110C` FOREIGN KEY (`bloc_uecategory_id`) REFERENCES `bloc_ue_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloc_ue`
--

LOCK TABLES `bloc_ue` WRITE;
/*!40000 ALTER TABLE `bloc_ue` DISABLE KEYS */;
INSERT INTO `bloc_ue` VALUES
(43,2,4),
(44,1,4),
(46,2,11),
(47,1,11);
/*!40000 ALTER TABLE `bloc_ue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bloc_ue_category`
--

DROP TABLE IF EXISTS `bloc_ue_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bloc_ue_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloc_ue_category`
--

LOCK TABLES `bloc_ue_category` WRITE;
/*!40000 ALTER TABLE `bloc_ue_category` DISABLE KEYS */;
INSERT INTO `bloc_ue_category` VALUES
(1,'Informatique'),
(2,'Ingénierie des Systèmes d’Information'),
(3,'Gestion des organisations'),
(4,'Compétences transverses'),
(5,'Professionnalisation');
/*!40000 ALTER TABLE `bloc_ue_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bloc_ue_ue`
--

DROP TABLE IF EXISTS `bloc_ue_ue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bloc_ue_ue` (
  `bloc_ue_id` int(11) NOT NULL,
  `ue_id` int(11) NOT NULL,
  PRIMARY KEY (`bloc_ue_id`,`ue_id`),
  KEY `IDX_F73889A16648E46A` (`bloc_ue_id`),
  KEY `IDX_F73889A162E883B1` (`ue_id`),
  CONSTRAINT `FK_F73889A162E883B1` FOREIGN KEY (`ue_id`) REFERENCES `ue` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_F73889A16648E46A` FOREIGN KEY (`bloc_ue_id`) REFERENCES `bloc_ue` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloc_ue_ue`
--

LOCK TABLES `bloc_ue_ue` WRITE;
/*!40000 ALTER TABLE `bloc_ue_ue` DISABLE KEYS */;
INSERT INTO `bloc_ue_ue` VALUES
(43,4),
(43,5),
(44,8),
(44,9),
(44,10),
(46,4),
(46,5),
(47,8),
(47,9),
(47,10);
/*!40000 ALTER TABLE `bloc_ue_ue` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campagne_choix`
--

LOCK TABLES `campagne_choix` WRITE;
/*!40000 ALTER TABLE `campagne_choix` DISABLE KEYS */;
INSERT INTO `campagne_choix` VALUES
(7,11,'2023-01-14 00:35:00','2023-01-14 00:35:00'),
(8,4,'2023-01-14 00:47:00','2023-01-14 00:47:00'),
(9,4,'2023-01-14 03:05:00','2023-01-14 03:05:00'),
(10,4,'2023-01-14 03:07:00','2023-01-14 03:07:00');
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
  `ue_id` int(11) DEFAULT NULL,
  `ordre` int(11) NOT NULL,
  `response_campagne_id` int(11) DEFAULT NULL,
  `bloc_option_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4F48809162E883B1` (`ue_id`),
  KEY `IDX_4F488091B6FB69FB` (`response_campagne_id`),
  KEY `IDX_4F488091B26386A2` (`bloc_option_id`),
  CONSTRAINT `FK_4F48809162E883B1` FOREIGN KEY (`ue_id`) REFERENCES `ue` (`id`),
  CONSTRAINT `FK_4F488091B26386A2` FOREIGN KEY (`bloc_option_id`) REFERENCES `bloc_option` (`id`),
  CONSTRAINT `FK_4F488091B6FB69FB` FOREIGN KEY (`response_campagne_id`) REFERENCES `response_campagne` (`id`)
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
('DoctrineMigrations\\Version20221228025851','2022-12-28 15:54:02',81),
('DoctrineMigrations\\Version20221230171139','2022-12-30 17:11:42',125),
('DoctrineMigrations\\Version20221230193304','2022-12-30 19:33:07',177),
('DoctrineMigrations\\Version20221230194728','2022-12-30 19:49:16',57),
('DoctrineMigrations\\Version20230111223913','2023-01-11 22:39:56',169),
('DoctrineMigrations\\Version20230111225008','2023-01-11 22:51:15',113),
('DoctrineMigrations\\Version20230111235211','2023-01-11 23:52:53',119),
('DoctrineMigrations\\Version20230113202026','2023-01-13 20:21:03',142),
('DoctrineMigrations\\Version20230113202341','2023-01-13 20:24:07',89),
('DoctrineMigrations\\Version20230113202623','2023-01-13 20:26:48',71),
('DoctrineMigrations\\Version20230114010125','2023-01-14 01:04:28',57),
('DoctrineMigrations\\Version20230114153954','2023-01-14 15:40:25',148),
('DoctrineMigrations\\Version20230207100329','2023-02-07 10:04:25',200);
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
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_717E22E36E38C0DB` (`parcours_id`),
  CONSTRAINT `FK_717E22E36E38C0DB` FOREIGN KEY (`parcours_id`) REFERENCES `parcours` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etudiant`
--

LOCK TABLES `etudiant` WRITE;
/*!40000 ALTER TABLE `etudiant` DISABLE KEYS */;
INSERT INTO `etudiant` VALUES
(2,5,'Varlet','William','etudiant@gmail.com'),
(3,5,'Leturgez','Felix',''),
(4,5,'Ingleton','Amara','aingleton0@salon.com'),
(5,5,'Clutram','Gunther','gclutram1@mozilla.org'),
(6,5,'Philippeaux','Cortie','cphilippeaux2@theguardian.com'),
(7,5,'Mycroft','Carlin','cmycroft3@ycombinator.com'),
(8,5,'Raspison','Dasya','draspison4@theglobeandmail.com'),
(9,4,'Colleymore','Hall','hcolleymore5@intel.com'),
(10,4,'Baudesson','Andras','abaudesson6@timesonline.co.uk'),
(11,4,'Cartlidge','Colette','ccartlidge7@cnbc.com'),
(12,4,'Fawloe','Wrennie','wfawloe8@sphinn.com'),
(13,4,'Fairy','Sallyann','sfairy9@ycombinator.com'),
(14,4,'Bratten','Jeannie','jbrattena@amazon.co.jp'),
(15,5,'Mewrcik','Winnah','wmewrcikb@phpbb.com'),
(16,11,'Peealess','Ashlin','apeealessc@google.com.au'),
(17,11,'Dunseith','Garv','gdunseithd@ow.ly'),
(18,11,'McDuall','Huntlee','hmcdualle@answers.com'),
(19,11,'Pimme','Alayne','apimmef@bigcartel.com'),
(20,11,'Hufton','Ruddy','rhuftong@nymag.com'),
(21,5,'Elmer','Rosalynd','relmerh@uiuc.edu'),
(22,5,'Winterton','Whitney','wwintertoni@xing.com'),
(23,5,'Carson','Shay','scarsonj@wufoo.com'),
(24,5,'Treherne','Luke','ltrehernek@behance.net'),
(25,5,'Mieville','Alexandro','amievillel@seattletimes.com'),
(26,5,'Labbett','Amalie','alabbettm@oracle.com'),
(27,5,'Sogg','Daphne','dsoggn@washington.edu'),
(28,11,'Collip','Adelheid','acollipo@twitpic.com'),
(29,11,'Fausch','Georgianna','gfauschp@cafepress.com'),
(30,11,'Deackes','Maggie','mdeackesq@apple.com'),
(31,11,'Whal','Scarface','swhalr@wired.com'),
(32,11,'Stronack','Dulci','dstronacks@dion.ne.jp'),
(33,11,'Izac','Wilone','wizact@github.com'),
(34,11,'Phateplace','Hasty','hphateplaceu@columbia.edu'),
(35,4,'McLaughlan','Winston','wmclaughlanv@home.pl'),
(36,4,'Goodlett','Karlens','kgoodlettw@army.mil'),
(37,1,'Lebourn','Karoly','klebournx@vimeo.com'),
(38,1,'Kerins','Jacqui','jkerinsy@yellowpages.com'),
(39,1,'Teggin','Lynett','ltegginz@360.cn'),
(40,1,'Lewry','Bond','blewry10@gmpg.org'),
(41,1,'Fallanche','Jillayne','jfallanche11@discuz.net'),
(42,1,'Pear','Morten','mpear12@blogger.com'),
(43,1,'Goade','Alberta','agoade13@latimes.com');
/*!40000 ALTER TABLE `etudiant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etudiant_ue`
--

DROP TABLE IF EXISTS `etudiant_ue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etudiant_ue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etudiant_id` int(11) DEFAULT NULL,
  `ue_id` int(11) DEFAULT NULL,
  `acquis` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4C9ADC68DDEAB1A3` (`etudiant_id`),
  KEY `IDX_4C9ADC6862E883B1` (`ue_id`),
  CONSTRAINT `FK_4C9ADC6862E883B1` FOREIGN KEY (`ue_id`) REFERENCES `ue` (`id`),
  CONSTRAINT `FK_4C9ADC68DDEAB1A3` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiant` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etudiant_ue`
--

LOCK TABLES `etudiant_ue` WRITE;
/*!40000 ALTER TABLE `etudiant_ue` DISABLE KEYS */;
/*!40000 ALTER TABLE `etudiant_ue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(45) NOT NULL,
  `ue_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4B98C2162E883B1` (`ue_id`),
  CONSTRAINT `FK_4B98C2162E883B1` FOREIGN KEY (`ue_id`) REFERENCES `ue` (`id`)
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
-- Table structure for table `groupe_etudiant`
--

DROP TABLE IF EXISTS `groupe_etudiant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groupe_etudiant` (
  `groupe_id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  PRIMARY KEY (`groupe_id`,`etudiant_id`),
  KEY `IDX_E0DC29937A45358C` (`groupe_id`),
  KEY `IDX_E0DC2993DDEAB1A3` (`etudiant_id`),
  CONSTRAINT `FK_E0DC29937A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `groupe` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_E0DC2993DDEAB1A3` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiant` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupe_etudiant`
--

LOCK TABLES `groupe_etudiant` WRITE;
/*!40000 ALTER TABLE `groupe_etudiant` DISABLE KEYS */;
/*!40000 ALTER TABLE `groupe_etudiant` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
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
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_99B1DEE33A687361` (`annee_formation_id`),
  CONSTRAINT `FK_99B1DEE33A687361` FOREIGN KEY (`annee_formation_id`) REFERENCES `annee_formation` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcours`
--

LOCK TABLES `parcours` WRITE;
/*!40000 ALTER TABLE `parcours` DISABLE KEYS */;
INSERT INTO `parcours` VALUES
(1,1,'S1'),
(4,1,'S2_Alternants'),
(5,1,'S2_Stagiaires'),
(11,3,'SIO');
/*!40000 ALTER TABLE `parcours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `passage_annee`
--

DROP TABLE IF EXISTS `passage_annee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `passage_annee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `passage_annee`
--

LOCK TABLES `passage_annee` WRITE;
/*!40000 ALTER TABLE `passage_annee` DISABLE KEYS */;
/*!40000 ALTER TABLE `passage_annee` ENABLE KEYS */;
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
-- Table structure for table `response_campagne`
--

DROP TABLE IF EXISTS `response_campagne`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `response_campagne` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `etudiant_id` int(11) NOT NULL,
  `campagne_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E48DBB6BDDEAB1A3` (`etudiant_id`),
  KEY `IDX_E48DBB6B16227374` (`campagne_id`),
  CONSTRAINT `FK_E48DBB6B16227374` FOREIGN KEY (`campagne_id`) REFERENCES `campagne_choix` (`id`),
  CONSTRAINT `FK_E48DBB6BDDEAB1A3` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiant` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `response_campagne`
--

LOCK TABLES `response_campagne` WRITE;
/*!40000 ALTER TABLE `response_campagne` DISABLE KEYS */;
/*!40000 ALTER TABLE `response_campagne` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ue`
--

DROP TABLE IF EXISTS `ue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(45) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ue`
--

LOCK TABLES `ue` WRITE;
/*!40000 ALTER TABLE `ue` DISABLE KEYS */;
INSERT INTO `ue` VALUES
(4,'ISI_02 Conduite de projet',1),
(5,'ISI_05 Ingénierie du logiciel',1),
(6,'ISI_01 Architecture client-serveur',1),
(7,'ISI_04 Contrôle qualité et green IT',1),
(8,'INFO_02 Analyse et décision en entreprise',1),
(9,'INFO_04 Architecture des SI',1),
(10,'INFO_03 Architecture Web des SI',1),
(11,'INFO_06 BD avancées',1),
(12,'INFO_09 UX Design',1);
/*!40000 ALTER TABLE `ue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ue_bloc_uecategory`
--

DROP TABLE IF EXISTS `ue_bloc_uecategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ue_bloc_uecategory` (
  `ue_id` int(11) NOT NULL,
  `bloc_uecategory_id` int(11) NOT NULL,
  PRIMARY KEY (`ue_id`,`bloc_uecategory_id`),
  KEY `IDX_3F1210A662E883B1` (`ue_id`),
  KEY `IDX_3F1210A69865110C` (`bloc_uecategory_id`),
  CONSTRAINT `FK_3F1210A662E883B1` FOREIGN KEY (`ue_id`) REFERENCES `ue` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3F1210A69865110C` FOREIGN KEY (`bloc_uecategory_id`) REFERENCES `bloc_ue_category` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ue_bloc_uecategory`
--

LOCK TABLES `ue_bloc_uecategory` WRITE;
/*!40000 ALTER TABLE `ue_bloc_uecategory` DISABLE KEYS */;
INSERT INTO `ue_bloc_uecategory` VALUES
(4,2),
(4,3),
(5,2),
(6,2),
(7,2),
(8,1),
(9,1),
(10,1),
(11,1),
(12,1);
/*!40000 ALTER TABLE `ue_bloc_uecategory` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'admin@gmail.com','[\"ROLE_ADMIN\"]','$2y$13$nQZgD2X6K6/1SkgVvlbZMeZWlG2qX1Mo9bQDdgYqoZL0HWXE.zCUe',1),
(2,'etudiant@gmail.com','[]','$2y$13$nQZgD2X6K6/1SkgVvlbZMeZWlG2qX1Mo9bQDdgYqoZL0HWXE.zCUe',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_gestion`
--

DROP TABLE IF EXISTS `user_gestion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_gestion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_gestion`
--

LOCK TABLES `user_gestion` WRITE;
/*!40000 ALTER TABLE `user_gestion` DISABLE KEYS */;
INSERT INTO `user_gestion` VALUES
(2,'Di Biasio','Eleni','edibiasio0@psu.edu'),
(3,'Mix','Mariel','mmix1@who.int'),
(4,'Dimanche','Cullen','cdimanche2@umich.edu'),
(5,'Suter','Winnifred','wsuter3@trellian.com'),
(6,'Rouke','Ingunna','irouke4@arstechnica.com'),
(7,'Hassell','Margalit','mhassell5@sitemeter.com'),
(8,'Wake','Shanie','swake6@unicef.org'),
(9,'Storres','Enrico','estorres7@livejournal.com'),
(10,'Barradell','Shea','sbarradell8@usnews.com'),
(11,'Darling','Payton','pdarling9@economist.com'),
(12,'Seville','Sebastian','ssevillea@ed.gov'),
(13,'Basil','Pearle','pbasilb@google.fr'),
(14,'Aggio','Fredericka','faggioc@deliciousdays.com'),
(15,'De Biaggi','Irv','idebiaggid@businessweek.com'),
(16,'Joly','Grissel','gjolye@myspace.com'),
(17,'Halkyard','Dud','dhalkyardf@epa.gov'),
(18,'Bayless','Debora','dbaylessg@e-recht24.de'),
(19,'Skurray','Tina','tskurrayh@pagesperso-orange.fr'),
(20,'Fincher','Lurlene','lfincheri@addtoany.com'),
(21,'Burchall','Alli','aburchallj@wunderground.com'),
(22,'Burdikin','Daisy','dburdikink@cnn.com'),
(23,'Campione','Hatty','hcampionel@over-blog.com'),
(24,'Scotchbrook','Bette','bscotchbrookm@bizjournals.com'),
(25,'Aime','Jarrett','jaimen@ed.gov'),
(26,'Burle','Pauly','pburleo@wired.com'),
(27,'Tennant','Lazar','ltennantp@paginegialle.it'),
(28,'Cote','Kelsey','kcoteq@pagesperso-orange.fr'),
(29,'Sinfield','Teresita','tsinfieldr@google.nl'),
(30,'Mocker','Nikaniki','nmockers@hexun.com'),
(31,'Celli','Maryanna','mcellit@typepad.com'),
(32,'Cuel','Alair','acuelu@free.fr'),
(33,'Atyea','Portia','patyeav@last.fm'),
(34,'Alder','Karly','kalderw@opensource.org'),
(35,'Muller','Misty','mmullerx@linkedin.com'),
(36,'Fomichkin','Fraze','ffomichkiny@princeton.edu'),
(37,'Penhaligon','Abbot','apenhaligonz@icio.us'),
(38,'Duke','Astrix','aduke10@ask.com'),
(39,'Dewis','Ailene','adewis11@imgur.com'),
(40,'Griston','Jodee','jgriston12@cbslocal.com'),
(41,'Crowch','Maximo','mcrowch13@yandex.ru'),
(42,'Boate','Cymbre','cboate14@rambler.ru'),
(43,'Stuehmeier','Bebe','bstuehmeier15@uiuc.edu'),
(44,'Innerstone','Dannye','dinnerstone16@techcrunch.com'),
(45,'Gallandre','Layla','lgallandre17@infoseek.co.jp'),
(46,'Ragsdall','Aurie','aragsdall18@dot.gov'),
(47,'Aslin','Gaspar','gaslin19@over-blog.com'),
(48,'Thebe','Morry','mthebe1a@unc.edu'),
(49,'Brewster','Cherilyn','cbrewster1b@mapy.cz'),
(50,'Radband','Kalle','kradband1c@nbcnews.com'),
(51,'Edy','Hercule','hedy1d@exblog.jp'),
(52,'Stoltz','Hewie','hstoltz1e@telegraph.co.uk'),
(53,'Clench','Irina','iclench1f@ocn.ne.jp'),
(54,'Jikylls','Doe','djikylls1g@apache.org'),
(55,'Northridge','Garvey','gnorthridge1h@goo.gl'),
(56,'Tomasian','Em','etomasian1i@epa.gov'),
(57,'Westpfel','Clarance','cwestpfel1j@dailymotion.com'),
(58,'Cleal','Matilda','mcleal1k@amazonaws.com'),
(59,'Thayre','Bordy','bthayre1l@comsenz.com'),
(60,'Kleinberer','Eolande','ekleinberer1m@nifty.com'),
(61,'Dunn','Dorolisa','ddunn1n@blogspot.com'),
(62,'Stoves','Alphonse','astoves1o@google.com'),
(63,'Stockley','Gladys','gstockley1p@livejournal.com'),
(64,'Gerrietz','Cordell','cgerrietz1q@scribd.com'),
(65,'Marcroft','Merla','mmarcroft1r@mayoclinic.com'),
(66,'MacPadene','Lev','lmacpadene1s@wikimedia.org'),
(67,'Nisen','Frazier','fnisen1t@quantcast.com'),
(68,'Westmorland','Egbert','ewestmorland1u@comcast.net'),
(69,'Lutas','Udell','ulutas1v@apple.com'),
(70,'Morit','Harriett','hmorit1w@theguardian.com'),
(71,'Netherclift','Dilly','dnetherclift1x@booking.com'),
(72,'Raund','Berti','braund1y@nature.com'),
(73,'Putnam','Kathye','kputnam1z@godaddy.com'),
(74,'Hucker','Krista','khucker20@whitehouse.gov'),
(75,'Marjoribanks','Burke','bmarjoribanks21@studiopress.com'),
(76,'Todarello','Ralina','rtodarello22@i2i.jp'),
(77,'Lunn','Francklin','flunn23@yellowbook.com'),
(78,'Tolputt','Erinna','etolputt24@dagondesign.com'),
(79,'Behnke','Gerome','gbehnke25@dedecms.com'),
(80,'Maybury','Georgie','gmaybury26@youtube.com'),
(81,'Willerson','Deena','dwillerson27@wikispaces.com'),
(82,'Cordon','Piper','pcordon28@hao123.com'),
(83,'Taffrey','Caldwell','ctaffrey29@hud.gov'),
(84,'Checci','Lesli','lchecci2a@marketwatch.com'),
(85,'Yoslowitz','Buckie','byoslowitz2b@taobao.com'),
(86,'Vian','Herman','hvian2c@wiley.com'),
(87,'Lambird','Rosemaria','rlambird2d@icq.com'),
(88,'Walas','Wang','wwalas2e@disqus.com'),
(89,'Nutton','Issie','inutton2f@youku.com'),
(90,'Amery','Godard','gamery2g@123-reg.co.uk'),
(91,'Pennino','Codie','cpennino2h@nationalgeographic.com'),
(92,'Dregan','Ddene','ddregan2i@so-net.ne.jp'),
(93,'Brankley','Jordan','jbrankley2j@jugem.jp'),
(94,'Margrett','Clive','cmargrett2k@indiegogo.com'),
(95,'Cuningham','Delora','dcuningham2l@pinterest.com'),
(96,'Van Oord','Lorianne','lvanoord2m@thetimes.co.uk'),
(97,'Wagenen','Tony','twagenen2n@webnode.com'),
(98,'Daniels','Abeu','adaniels2o@prlog.org'),
(99,'Clementi','Windham','wclementi2p@nba.com'),
(100,'Janman','Erma','ejanman2q@tripod.com'),
(101,'Coopper','Pavlov','pcoopper2r@dyndns.org');
/*!40000 ALTER TABLE `user_gestion` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-02-28 15:19:35
