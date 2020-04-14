CREATE DATABASE  IF NOT EXISTS `blog` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `blog`;
-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: blog
-- ------------------------------------------------------
-- Server version	8.0.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `metaTitle` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `parentId` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `slug_UNIQUE` (`slug`),
  KEY `fk_category_category1_idx` (`parentId`),
  CONSTRAINT `fk_category_category1` FOREIGN KEY (`parentId`) REFERENCES `category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Culture',NULL,'Culture5e888d94ac5b1','Tous ce qui est fait par l\'Homme.',NULL),(2,'Arts',NULL,'Arts5e888db60379f','Les arts 7 et plus',NULL),(3,'Cinema',NULL,'2Cinema5e888dca8332c','le 7eme art.',2),(4,'Music',NULL,'2Music5e888de72f5bb','L\'art des sons.',2),(5,'Theatre',NULL,'2Theatre5e888e11b1b7c','L\'art des masques et de la représentation. ',2);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `send` int NOT NULL DEFAULT '0',
  `read` int DEFAULT NULL,
  `content` mediumtext NOT NULL,
  `sendBy` int unsigned NOT NULL,
  `sendTo` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_messages_user1_idx` (`sendBy`),
  KEY `fk_messages_user2_idx` (`sendTo`),
  CONSTRAINT `fk_messages_user1` FOREIGN KEY (`sendBy`) REFERENCES `user` (`id`),
  CONSTRAINT `fk_messages_user2` FOREIGN KEY (`sendTo`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `picture`
--

DROP TABLE IF EXISTS `picture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `picture` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uniqueName` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `description` mediumtext,
  `uploadAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `posted` int unsigned NOT NULL DEFAULT '0',
  `published` int unsigned NOT NULL DEFAULT '0',
  `userId` int unsigned NOT NULL,
  `metadata` text,
  `like` int DEFAULT '0',
  `share` int DEFAULT '0',
  `dislike` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueName_UNIQUE` (`uniqueName`),
  KEY `fk_picture_user1_idx` (`userId`),
  CONSTRAINT `fk_picture_user1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `picture`
--

LOCK TABLES `picture` WRITE;
/*!40000 ALTER TABLE `picture` DISABLE KEYS */;
INSERT INTO `picture` VALUES (5,'gallery_5e8c8b21429b5.jpg','Pastas','Making some good!','2020-04-07 14:16:50',0,0,1,'a:8:{s:8:\"FileName\";s:28:\"sm_gallery_5e8c8b21429b5.jpg\";s:12:\"FileDateTime\";i:1586268986;s:8:\"FileSize\";i:80345;s:8:\"FileType\";i:2;s:8:\"MimeType\";s:10:\"image/jpeg\";s:13:\"SectionsFound\";s:7:\"COMMENT\";s:8:\"COMPUTED\";a:4:{s:4:\"html\";s:24:\"width=\"600\" height=\"397\"\";s:6:\"Height\";i:397;s:5:\"Width\";i:600;s:7:\"IsColor\";i:1;}s:7:\"COMMENT\";a:1:{i:0;s:57:\"CREATOR: gd-jpeg v1.0 (using IJG JPEG v90), quality = 85\n\";}}',0,0,0),(6,'gallery_5e8c8be0e315d.jpg','elephant-1822636_1920.jpg','','2020-04-07 14:19:17',0,0,1,'a:8:{s:8:\"FileName\";s:28:\"sm_gallery_5e8c8be0e315d.jpg\";s:12:\"FileDateTime\";i:1586269157;s:8:\"FileSize\";i:55795;s:8:\"FileType\";i:2;s:8:\"MimeType\";s:10:\"image/jpeg\";s:13:\"SectionsFound\";s:7:\"COMMENT\";s:8:\"COMPUTED\";a:4:{s:4:\"html\";s:24:\"width=\"600\" height=\"409\"\";s:6:\"Height\";i:409;s:5:\"Width\";i:600;s:7:\"IsColor\";i:1;}s:7:\"COMMENT\";a:1:{i:0;s:57:\"CREATOR: gd-jpeg v1.0 (using IJG JPEG v90), quality = 85\n\";}}',0,0,0),(7,'gallery_5e8cb51427d48.jpg','Mariposas blancas','Alimentandose de flores','2020-04-07 17:15:04',0,0,1,'{\"FileName\":\"sm_gallery_5e8cb51427d48.jpg\",\"FileDateTime\":1586279704,\"FileSize\":32655,\"FileType\":2,\"MimeType\":\"image\\/jpeg\",\"SectionsFound\":\"COMMENT\",\"COMPUTED\":{\"html\":\"width=\\\"600\\\" height=\\\"400\\\"\",\"Height\":400,\"Width\":600,\"IsColor\":1},\"COMMENT\":[\"CREATOR: gd-jpeg v1.0 (using IJG JPEG v90), quality = 85\\n\"]}',0,0,0);
/*!40000 ALTER TABLE `picture` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `picture_category`
--

DROP TABLE IF EXISTS `picture_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `picture_category` (
  `id` int unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `metaTitle` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text,
  `parentId` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `slug_UNIQUE` (`slug`),
  KEY `fk_category_category1_idx` (`parentId`),
  CONSTRAINT `fk_category_category10` FOREIGN KEY (`parentId`) REFERENCES `picture_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `picture_category`
--

LOCK TABLES `picture_category` WRITE;
/*!40000 ALTER TABLE `picture_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `picture_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `picture_collection`
--

DROP TABLE IF EXISTS `picture_collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `picture_collection` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `published` int NOT NULL DEFAULT '1',
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userId` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId_idx` (`userId`),
  CONSTRAINT `userId` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='		';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `picture_collection`
--

LOCK TABLES `picture_collection` WRITE;
/*!40000 ALTER TABLE `picture_collection` DISABLE KEYS */;
INSERT INTO `picture_collection` VALUES (1,'Cultura','La vie et bien au dela',1,'2020-04-07 20:42:44','2020-04-07 20:42:44',1),(2,'Cocina','food',1,'2020-04-07 21:14:52','2020-04-07 21:14:52',1),(3,'Deportes','Para mover el cuerpo en tiempos de confinamiento',0,'2020-04-13 11:59:22','2020-04-13 11:59:22',1),(4,'Marseille','La ville phocéenne ',1,'2020-04-13 12:10:58','2020-04-13 12:10:58',1),(5,'Journaux','La presse aujourd’hui ',0,'2020-04-13 12:15:17','2020-04-13 12:15:17',1),(6,'Yoga','Menta sana ',0,'2020-04-13 12:37:07','2020-04-13 12:37:07',1),(7,'Span','dfadfad',0,'2020-04-13 12:40:27','2020-04-13 12:40:27',1),(8,'Musica','',0,'2020-04-13 12:45:46','2020-04-13 12:45:46',1),(9,'Products','',0,'2020-04-13 12:48:17','2020-04-13 12:48:17',1);
/*!40000 ALTER TABLE `picture_collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `picture_has_category`
--

DROP TABLE IF EXISTS `picture_has_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `picture_has_category` (
  `pictureId` int unsigned NOT NULL,
  `picture_categoryId` int unsigned NOT NULL,
  PRIMARY KEY (`pictureId`,`picture_categoryId`),
  KEY `fk_post_picture_category_picture_category1_idx` (`picture_categoryId`),
  CONSTRAINT `fk_post_picture_category_picture1` FOREIGN KEY (`pictureId`) REFERENCES `picture` (`id`),
  CONSTRAINT `fk_post_picture_category_picture_category1` FOREIGN KEY (`picture_categoryId`) REFERENCES `picture_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `picture_has_category`
--

LOCK TABLES `picture_has_category` WRITE;
/*!40000 ALTER TABLE `picture_has_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `picture_has_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `picture_has_collection`
--

DROP TABLE IF EXISTS `picture_has_collection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `picture_has_collection` (
  `pictureId` int unsigned NOT NULL,
  `collectionId` int unsigned NOT NULL,
  PRIMARY KEY (`pictureId`,`collectionId`),
  KEY `collectionId_idx` (`collectionId`),
  CONSTRAINT `collectionId` FOREIGN KEY (`collectionId`) REFERENCES `picture_collection` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pictureId` FOREIGN KEY (`pictureId`) REFERENCES `picture` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `picture_has_collection`
--

LOCK TABLES `picture_has_collection` WRITE;
/*!40000 ALTER TABLE `picture_has_collection` DISABLE KEYS */;
INSERT INTO `picture_has_collection` VALUES (7,1),(5,2),(6,2),(7,2),(5,6),(5,9);
/*!40000 ALTER TABLE `picture_has_collection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `metaTitle` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text,
  `published` int NOT NULL DEFAULT '0',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `publishedAt` datetime DEFAULT NULL,
  `content` text,
  `picture` varchar(255) DEFAULT NULL,
  `authorId` int unsigned NOT NULL,
  `like` int DEFAULT NULL,
  `dislike` int DEFAULT '0',
  `share` int DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  KEY `fk_post_user_idx` (`authorId`),
  CONSTRAINT `fk_post_user` FOREIGN KEY (`authorId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (1,'Chicken Broth','A Step-By-Step Guide to the Best-Ever Homemade Chicken Broth Recipe','chicken-broth','Transfer the chicken and vegetables to a large bowl and season the broth with salt. Shred the meat, discarding the skin and bones, and reserve to serve with soup or use for another recipe.',0,'2020-04-04 09:42:09','2020-04-04 11:42:09',NULL,'t may be easy to buy in the store, but there’s nothing that makes a dish more special than a homemade chicken broth recipe. Whether you’re simmering a fall soup, making a hearty stew, or impressing your dinner guests with a delicious risotto, homemade chicken broth can bring your dishes from average to extraordinary because of its rich flavor. But finding a recipe that doesn’t look complicated can be daunting. Lucky for you, we\'re here to help.\r\n\r\nSo, what are the benefits of making your own chicken broth at home? When you prepare your own chicken broth from start to finish, you can control the ingredients you add and make sure you\'re getting exactly what you want. While it can be tough to monitor sodium and other additives when you’re buying your broth in the store, cooking your own broth means you can precisely measure the salt used (and get nutrients from a wider variety of vegetables, if that\'s your desire!). We also love the fact that homemade chicken broth can keep in the freezer for up to three months. ','post_5e8886248ca23.jpg',1,NULL,0,0),(2,'El Conde Dracula','Aventuras de un vampiro en timpos del coronavirus.','el-conde-dracula','Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...',0,'2020-04-06 09:18:41','2020-04-06 11:18:41',NULL,'Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...Victima de la pandemia el famoso conde adapta sus metodos alimenticios para poder sobrevivir. Su ingenio le conducirá a nombrosas aventuras...','post_5e8b1009df8ca.jpg',1,NULL,0,0),(4,'Alginato de soidum','Alginato de soidum','alginato-de-soidum','Alginato de soidumAlginato de soidumAlginato de soidumAlginato de soidum',0,'2020-04-06 10:31:23','2020-04-06 12:31:23',NULL,'Alginato de soidumAlginato de soidumAlginato de soidumAlginato de soidumAlginato de soidumAlginato de soidumAlginato de soidum','NULL',1,NULL,0,0),(5,'$http','$http->redirectTo(\'/admin/articles/\');$http->redirectTo(\'/admin/articles/\');','-http','$http->redirectTo(\'/admin/articles/\');$http->redirectTo(\'/admin/articles/\');$http->redirectTo(\'/admin/articles/\');$http->redirectTo(\'/admin/articles/\');$http->redirectTo(\'/admin/articles/\');',0,'2020-04-06 11:07:30','2020-04-06 13:07:30',NULL,'$http->redirectTo(\'/admin/articles/\');$http->redirectTo(\'/admin/articles/\');$http->redirectTo(\'/admin/articles/\');$http->redirectTo(\'/admin/articles/\');$http->redirectTo(\'/admin/articles/\');$http->redirectTo(\'/admin/articles/\');','NULL',1,NULL,0,0),(6,'La pasión de Cristo','La muerte como purificación del hombre.','la-pasi-n-de-cristo','La muerte como purificación del hombre.La muerte como purificación del hombre.',1,'2020-04-06 21:44:54','2020-04-06 23:44:54',NULL,'La muerte como purificación del hombre.La muerte como purificación del hombre.La muerte como purificación del hombre.La muerte como purificación del hombre.','',1,NULL,0,0);
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_comment`
--

DROP TABLE IF EXISTS `post_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `published` int NOT NULL,
  `createdAt` datetime NOT NULL,
  `pubishedAt` datetime DEFAULT NULL,
  `content` text NOT NULL,
  `postId` int unsigned NOT NULL,
  `authorId` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_post_comment_post1_idx` (`postId`),
  KEY `fk_post_comment_user1_idx` (`authorId`),
  CONSTRAINT `fk_post_comment_post1` FOREIGN KEY (`postId`) REFERENCES `post` (`id`),
  CONSTRAINT `fk_post_comment_user1` FOREIGN KEY (`authorId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_comment`
--

LOCK TABLES `post_comment` WRITE;
/*!40000 ALTER TABLE `post_comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_has_category`
--

DROP TABLE IF EXISTS `post_has_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_has_category` (
  `categoryId` int unsigned NOT NULL,
  `postId` int unsigned NOT NULL,
  PRIMARY KEY (`categoryId`,`postId`),
  KEY `fk_postCategory_post1_idx` (`postId`),
  CONSTRAINT `fk_postCategory_category1` FOREIGN KEY (`categoryId`) REFERENCES `category` (`id`),
  CONSTRAINT `fk_postCategory_post1` FOREIGN KEY (`postId`) REFERENCES `post` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_has_category`
--

LOCK TABLES `post_has_category` WRITE;
/*!40000 ALTER TABLE `post_has_category` DISABLE KEYS */;
INSERT INTO `post_has_category` VALUES (1,1),(1,5),(4,5),(1,6),(3,6),(5,6);
/*!40000 ALTER TABLE `post_has_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_meta`
--

DROP TABLE IF EXISTS `post_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_meta` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `content` text,
  `postId` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_post_meta_post1_idx` (`postId`),
  CONSTRAINT `fk_post_meta_post1` FOREIGN KEY (`postId`) REFERENCES `post` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_meta`
--

LOCK TABLES `post_meta` WRITE;
/*!40000 ALTER TABLE `post_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_tag`
--

DROP TABLE IF EXISTS `post_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_tag` (
  `postId` int unsigned NOT NULL,
  `tagId` int unsigned NOT NULL,
  PRIMARY KEY (`postId`,`tagId`),
  KEY `fk_post_tag_tag1_idx` (`tagId`),
  CONSTRAINT `fk_post_tag_post1` FOREIGN KEY (`postId`) REFERENCES `post` (`id`),
  CONSTRAINT `fk_post_tag_tag1` FOREIGN KEY (`tagId`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_tag`
--

LOCK TABLES `post_tag` WRITE;
/*!40000 ALTER TABLE `post_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(75) NOT NULL,
  `metaTitle` varchar(100) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title_UNIQUE` (`title`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `registeredAt` timestamp NULL DEFAULT NULL,
  `lastLogin` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `intro` tinytext,
  `profile` text,
  `avatar` varchar(255) DEFAULT NULL,
  `role` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'super','admin','super@admin.com','$2y$10$.XQGK1/gWrVhxBg98sCheeVl9h4lzeIIHHSM0mYFtB3IJ4eMSjb2e','1234567890123','superadmin',NULL,'2020-04-13 05:51:08','Super usuario','El usuario con todos los derechos. El manda más','userAvatar5e875b2eebe9a.jpg',3,1,'2020-04-13 07:51:08'),(2,'clau','hexe','clauhexe@mail.fr','$2y$10$m.tH6s56AkdfcxaCWFtpFuQx5eWvjlevD6XA/Z7sXOJmtP.RO3OY6','','clauhexe','2020-04-01 22:00:00','2020-04-03 13:54:59','','','userAvatar5e87403329a47.jpg',3,1,'2020-04-03 13:54:59'),(3,'heineken','heineken','heineken@mail.com','$2y$10$nu0GLjTupX6zZ7YhPwqYM.cvYatmtJaP./aEXeMXXbp2sXEO1m11i','','heineken','2020-04-01 22:00:00','2020-04-03 13:55:42','','','userAvatar5e87405ede8ea.png',1,1,'2020-04-03 13:55:42');
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

-- Dump completed on 2020-04-14  9:40:32
