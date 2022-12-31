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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloc_option`
--

LOCK TABLES `bloc_option` WRITE;
/*!40000 ALTER TABLE `bloc_option` DISABLE KEYS */;
INSERT INTO `bloc_option` VALUES
(4,1,2,1);
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
(4,8),
(4,10);
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
  `parcours_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C4F2840B9865110C` (`bloc_uecategory_id`),
  KEY `IDX_C4F2840B6E38C0DB` (`parcours_id`),
  CONSTRAINT `FK_C4F2840B6E38C0DB` FOREIGN KEY (`parcours_id`) REFERENCES `parcours` (`id`),
  CONSTRAINT `FK_C4F2840B9865110C` FOREIGN KEY (`bloc_uecategory_id`) REFERENCES `bloc_ue_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bloc_ue`
--

LOCK TABLES `bloc_ue` WRITE;
/*!40000 ALTER TABLE `bloc_ue` DISABLE KEYS */;
INSERT INTO `bloc_ue` VALUES
(2,1,1),
(5,2,1);
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
(2,8),
(2,9),
(2,10),
(2,11),
(2,12),
(5,4),
(5,5),
(5,6),
(5,7);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campagne_choix`
--

LOCK TABLES `campagne_choix` WRITE;
/*!40000 ALTER TABLE `campagne_choix` DISABLE KEYS */;
INSERT INTO `campagne_choix` VALUES
(1,1,'2022-12-30 22:40:00','2022-12-31 22:40:00');
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
('DoctrineMigrations\\Version20221228025851','2022-12-28 15:54:02',81),
('DoctrineMigrations\\Version20221230171139','2022-12-30 17:11:42',125),
('DoctrineMigrations\\Version20221230193304','2022-12-30 19:33:07',177),
('DoctrineMigrations\\Version20221230194728','2022-12-30 19:49:16',57);
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
  `rythme_id` int(11) DEFAULT NULL,
  `specialisation_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_99B1DEE33A687361` (`annee_formation_id`),
  KEY `IDX_99B1DEE38399A4A6` (`rythme_id`),
  KEY `IDX_99B1DEE35627D44C` (`specialisation_id`),
  CONSTRAINT `FK_99B1DEE33A687361` FOREIGN KEY (`annee_formation_id`) REFERENCES `annee_formation` (`id`),
  CONSTRAINT `FK_99B1DEE35627D44C` FOREIGN KEY (`specialisation_id`) REFERENCES `specialisation` (`id`),
  CONSTRAINT `FK_99B1DEE38399A4A6` FOREIGN KEY (`rythme_id`) REFERENCES `rythme` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcours`
--

LOCK TABLES `parcours` WRITE;
/*!40000 ALTER TABLE `parcours` DISABLE KEYS */;
INSERT INTO `parcours` VALUES
(1,1,1,1);
/*!40000 ALTER TABLE `parcours` ENABLE KEYS */;
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
  `label` varchar(45) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `bloc_uecategory_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2E490A9B9865110C` (`bloc_uecategory_id`),
  CONSTRAINT `FK_2E490A9B9865110C` FOREIGN KEY (`bloc_uecategory_id`) REFERENCES `bloc_ue_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ue`
--

LOCK TABLES `ue` WRITE;
/*!40000 ALTER TABLE `ue` DISABLE KEYS */;
INSERT INTO `ue` VALUES
(4,'ISI_02 Conduite de projet',1,2),
(5,'ISI_05 Ingénierie du logiciel',1,2),
(6,'ISI_01 Architecture client-serveur',1,2),
(7,'ISI_04 Contrôle qualité et green IT',1,2),
(8,'INFO_02 Analyse et décision en entreprise',1,1),
(9,'INFO_04 Architecture des SI',1,1),
(10,'INFO_03 Architecture Web des SI',1,1),
(11,'INFO_06 BD avancées',1,1),
(12,'INFO_09 UX Design',1,1);
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

-- Dump completed on 2022-12-31  1:47:04
