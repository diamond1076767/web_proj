CREATE DATABASE  IF NOT EXISTS `tpamc` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `tpamc`;
-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: tpamc
-- ------------------------------------------------------
-- Server version	9.6.0

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
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '376990f7-1941-11f1-a9b4-03cf06ad559f:1-70';

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = visible , 1 = hidden',
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colour`
--

DROP TABLE IF EXISTS `colour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colour` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = visible, 1= hidden',
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colour`
--

LOCK TABLES `colour` WRITE;
/*!40000 ALTER TABLE `colour` DISABLE KEYS */;
/*!40000 ALTER TABLE `colour` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `companyName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `customerName` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`_id`),
  KEY `fk_customer_user_idx` (`email`,`telephone`),
  CONSTRAINT `fk_customer_user` FOREIGN KEY (`email`, `telephone`) REFERENCES `user` (`email`, `telephone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `_Id` int NOT NULL AUTO_INCREMENT,
  `categoryID` int NOT NULL,
  `colourID` int NOT NULL,
  `title` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quantity` int NOT NULL,
  `cost` float NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=visible, 1=hidden',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`_Id`),
  KEY `fk_inventory_category_idx` (`categoryID`),
  KEY `fk_inventory_colour_idx` (`colourID`),
  CONSTRAINT `fk_inventory_category` FOREIGN KEY (`categoryID`) REFERENCES `category` (`_id`),
  CONSTRAINT `fk_inventory_colour` FOREIGN KEY (`colourID`) REFERENCES `colour` (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `orderID` int NOT NULL,
  `inventoryID` int NOT NULL,
  `cost` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`_id`),
  KEY `fk_items_order_idx` (`orderID`),
  KEY `fk_items_inventory_idx` (`inventoryID`),
  CONSTRAINT `fk_items_inventory` FOREIGN KEY (`inventoryID`) REFERENCES `inventory` (`_Id`),
  CONSTRAINT `fk_items_order` FOREIGN KEY (`orderID`) REFERENCES `request_order` (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_request_items`
--

DROP TABLE IF EXISTS `order_request_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_request_items` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `orderID` int NOT NULL,
  `inventoryID` int NOT NULL,
  `cost` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`_id`),
  KEY `fk_request_items_order_idx` (`orderID`),
  KEY `fk_request_items_inventory_idx` (`inventoryID`),
  CONSTRAINT `fk_request_items_inventory` FOREIGN KEY (`inventoryID`) REFERENCES `inventory` (`_Id`),
  CONSTRAINT `fk_request_items_order` FOREIGN KEY (`orderID`) REFERENCES `request_order` (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_request_items`
--

LOCK TABLES `order_request_items` WRITE;
/*!40000 ALTER TABLE `order_request_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_request_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_order`
--

DROP TABLE IF EXISTS `request_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_order` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `customerID` int NOT NULL,
  `total_amount` int NOT NULL,
  `payment_mode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '''cash, online''',
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`_id`),
  KEY `fk_request_user_idx` (`userID`),
  KEY `fk_request_customer_idx` (`customerID`),
  CONSTRAINT `fk_request_customer` FOREIGN KEY (`customerID`) REFERENCES `customer` (`_id`),
  CONSTRAINT `fk_request_user` FOREIGN KEY (`userID`) REFERENCES `user` (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_order`
--

LOCK TABLES `request_order` WRITE;
/*!40000 ALTER TABLE `request_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_user`
--

DROP TABLE IF EXISTS `request_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_user` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `roleId` int NOT NULL,
  `userName` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fullName` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`_id`),
  KEY `fk_req_user_orig_idx` (`userID`),
  KEY `fk_req_user_role_idx` (`roleId`),
  CONSTRAINT `fk_req_user_orig` FOREIGN KEY (`userID`) REFERENCES `user` (`_id`),
  CONSTRAINT `fk_req_user_role` FOREIGN KEY (`roleId`) REFERENCES `role` (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_user`
--

LOCK TABLES `request_user` WRITE;
/*!40000 ALTER TABLE `request_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `request_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `roleName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`_id`),
  CONSTRAINT `fk_role_user` FOREIGN KEY (`_id`) REFERENCES `user` (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales_order`
--

DROP TABLE IF EXISTS `sales_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales_order` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `customerID` int NOT NULL,
  `tracking_no` varchar(100) NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  `total_amount` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `payment_mode` varchar(100) NOT NULL COMMENT 'cash, online',
  `order_status` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`_id`),
  KEY `fk_sale_user_idx` (`userID`),
  KEY `fk_sale_customer_idx` (`customerID`),
  CONSTRAINT `fk_sale_customer` FOREIGN KEY (`customerID`) REFERENCES `customer` (`_id`),
  CONSTRAINT `fk_sale_user` FOREIGN KEY (`userID`) REFERENCES `user` (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_order`
--

LOCK TABLES `sales_order` WRITE;
/*!40000 ALTER TABLE `sales_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `roleID` int NOT NULL,
  `userName` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fullName` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date DEFAULT NULL,
  `telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '../assets/profile/default.png',
  `failed_attempts` int NOT NULL DEFAULT '0' COMMENT '0 = login, 1 =first_login',
  `first_log` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = not_lock, 1= lock',
  `auth_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lock_acc` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = not_lock, 1= lock',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logTime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `telephone_UNIQUE` (`telephone`),
  UNIQUE KEY `idx_user_contact_unique` (`email`,`telephone`),
  KEY `fk_user_role_idx` (`roleID`),
  KEY `fk_user_request_user_idx` (`userName`,`email`),
  CONSTRAINT `fk_user_role` FOREIGN KEY (`roleID`) REFERENCES `role` (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-07  0:22:39
