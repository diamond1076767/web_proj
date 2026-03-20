-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: tpamc
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.22.04.1

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = visible , 1 = hidden',
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Electronics',0),(2,'Furniture',0),(3,'Apparel',0),(4,'Toys',0),(5,'Books',0),(6,'Stationery',0),(7,'Sports',0),(8,'Automotive',0),(9,'Beauty',0),(10,'Food',0);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colour`
--

DROP TABLE IF EXISTS `colour`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colour` (
  `_id` int NOT NULL AUTO_INCREMENT,
  `colourName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = visible, 1= hidden',
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colour`
--

LOCK TABLES `colour` WRITE;
/*!40000 ALTER TABLE `colour` DISABLE KEYS */;
INSERT INTO `colour` VALUES (1,'Red',0),(2,'Blue',0),(3,'Green',0),(4,'Yellow',0),(5,'Orange',0),(6,'Purple',0),(7,'Pink',0),(8,'Black',0),(9,'White',0),(10,'Gray',0);
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
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;
INSERT INTO `customer` VALUES (3,'rnicrosoft','Howard','MTIzNDU2Nzg5MDEyMzQ1Nkxqd1pJVGhDcnR4UDhsZnBrbzZDd2xzSVlaWk43S1ZOUEVVdEttd04yL0k9','MTIzNDU2Nzg5MDEyMzQ1NmhndXA4UXZyM29ENmlBZEt5STYybGc9PQ==','2026-03-19 08:45:02','2026-03-19 08:45:02'),(4,'banana','Monkey','MTIzNDU2Nzg5MDEyMzQ1NmxhdEUzc3BXVzBHeWJTMWFuQUE2Sjh3R2RXUU45OTNRV2t4ZGZVbWE4Rzg9','MTIzNDU2Nzg5MDEyMzQ1NjNBNVNDYzdBbHpBdlpxSTN3YkhtUUE9PQ==','2026-03-19 18:24:13','2026-03-19 18:24:13');
/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `_id` int NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`_id`),
  KEY `fk_inventory_category_idx` (`categoryID`),
  KEY `fk_inventory_colour_idx` (`colourID`),
  CONSTRAINT `fk_inventory_category` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`_id`),
  CONSTRAINT `fk_inventory_colour` FOREIGN KEY (`colourID`) REFERENCES `colour` (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (4,4,1,'Pringles Can',9,2.5,'Yums','assets/uploads/products/1773569960.jpg',0,'2026-03-15 18:19:20','2026-03-15 18:19:20'),(5,4,4,'Sponges',9,2.5,'Spongy Sponge','assets/uploads/products/1773570129.jpg',0,'2026-03-15 18:22:09','2026-03-19 18:53:59'),(6,4,7,'Baby Oil',8,10,'diddy','assets/uploads/products/1773570145.jpg',0,'2026-03-15 18:22:25','2026-03-15 18:22:25'),(10,4,2,'Gloves',5,2.5,'Gloves','assets/uploads/products/1773572059.jpg',0,'2026-03-15 18:53:38','2026-03-19 18:59:33');
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
  `cost` decimal(10,2) DEFAULT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`_id`),
  KEY `fk_items_order_idx` (`orderID`),
  KEY `fk_items_inventory_idx` (`inventoryID`),
  CONSTRAINT `fk_items_inventory` FOREIGN KEY (`inventoryID`) REFERENCES `inventory` (`_id`),
  CONSTRAINT `fk_items_order` FOREIGN KEY (`orderID`) REFERENCES `sales_order` (`_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,6,10.00,2),(2,1,4,2.50,1),(3,2,5,2.50,1),(4,3,10,2.50,5);
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
  `cost` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`_id`),
  KEY `fk_request_items_order_idx` (`orderID`),
  KEY `fk_request_items_inventory_idx` (`inventoryID`),
  CONSTRAINT `fk_request_items_inventory` FOREIGN KEY (`inventoryID`) REFERENCES `inventory` (`_id`),
  CONSTRAINT `fk_request_items_order` FOREIGN KEY (`orderID`) REFERENCES `request_order` (`_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_request_items`
--

LOCK TABLES `order_request_items` WRITE;
/*!40000 ALTER TABLE `order_request_items` DISABLE KEYS */;
INSERT INTO `order_request_items` VALUES (1,1,5,2.50,1),(3,3,10,2.50,5),(4,4,5,2.50,1);
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
  `total_amount` decimal(10,2) NOT NULL,
  `payment_mode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '''cash, online''',
  `status` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`_id`),
  KEY `fk_request_user_idx` (`userID`),
  KEY `fk_request_customer_idx` (`customerID`),
  CONSTRAINT `fk_request_customer` FOREIGN KEY (`customerID`) REFERENCES `customer` (`_id`),
  CONSTRAINT `fk_request_user` FOREIGN KEY (`userID`) REFERENCES `user` (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_order`
--

LOCK TABLES `request_order` WRITE;
/*!40000 ALTER TABLE `request_order` DISABLE KEYS */;
INSERT INTO `request_order` VALUES (1,3,4,2.50,'Cash Payment','Approved','2026-03-19 18:52:50','2026-03-19 18:52:50'),(3,3,4,12.50,'Online Payment','Approved','2026-03-19 18:58:27','2026-03-19 18:58:27'),(4,3,3,2.50,'Cash Payment','Pending','2026-03-19 19:00:09','2026-03-19 19:00:09');
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
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'Admin'),(2,'Manager'),(3,'Staff');
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
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_date` date NOT NULL,
  `payment_mode` varchar(100) NOT NULL COMMENT 'cash, online',
  `order_status` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`_id`),
  KEY `fk_sale_user_idx` (`userID`),
  KEY `fk_sale_customer_idx` (`customerID`),
  CONSTRAINT `fk_sale_customer` FOREIGN KEY (`customerID`) REFERENCES `customer` (`_id`),
  CONSTRAINT `fk_sale_user` FOREIGN KEY (`userID`) REFERENCES `user` (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales_order`
--

LOCK TABLES `sales_order` WRITE;
/*!40000 ALTER TABLE `sales_order` DISABLE KEYS */;
INSERT INTO `sales_order` VALUES (1,2,4,'82676','INV-124456',22.50,'2026-03-19','Cash Payment','Order Placed','2026-03-19 18:39:07'),(2,2,4,'83886','INV-434731',2.50,'2026-03-19','Cash Payment','Order Placed','2026-03-19 18:53:59'),(3,2,4,'40644','INV-835961',12.50,'2026-03-19','Online Payment','Order Placed','2026-03-19 18:59:33');
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
  `fullName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '../assets/profile/default.png',
  `dob` date DEFAULT NULL,
  `failed_attempts` int NOT NULL DEFAULT '0' COMMENT '0 = login, 1 =first_login',
  `first_log` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = not_lock, 1= lock',
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,1,'Admin_p17','MTIzNDU2Nzg5MDEyMzQ1NlhaNUJBUmhTbTFDaGdUVllOT3FodHc9PQ==','$2y$12$NP91CujNDSg0LqfM.YKPLO0QWIZ9iKh5139mywzBATwUMhRKUTxn.','MTIzNDU2Nzg5MDEyMzQ1NnI5QkpEdGVxR1ZLQXREQ3M5NjZDcEZhdXdNRlBBckk3OFdMcW1zNlk4YTZ6N3B0V0YrMTFhVUpTNmFCSVNLdHE=','MTIzNDU2Nzg5MDEyMzQ1NmtMYzZMVUszRXN2dXB5RG9hNzJGWmc9PQ==','../assets/profile/default.png',NULL,0,0,0,'2026-03-14 15:35:33','2026-03-15 11:40:08','2026-03-19 18:20:04'),(2,2,'Manager_H','MTIzNDU2Nzg5MDEyMzQ1NlhaNUJBUmhTbTFDaGdUVllOT3FodHc9PQ==','$2y$12$O7i9/1ar5olgFBTAhULfyeRLVh6NnV3xHWowUruOxyLuwQ/xslZ8G','MTIzNDU2Nzg5MDEyMzQ1NmVVbHg0WTFwRjBjUEZZa1dkRkhoYzJ2TDRhdkxsYngyenB4WHFVRk4xRDg9','MTIzNDU2Nzg5MDEyMzQ1NjFXdUVmYTRUYmx5eVM5VzJwQjFTS3c9PQ==','../assets/profile/default.png','2026-03-19',0,0,0,'2026-03-14 16:16:32','2026-03-19 18:23:28','2026-03-19 18:58:36'),(3,3,'Staff_H','MTIzNDU2Nzg5MDEyMzQ1NlhaNUJBUmhTbTFDaGdUVllOT3FodHc9PQ==','$2y$12$YyfWhIlqnZG1LHKqEe5xEOtMlOf6DihJ1q1EJcR2HJLYPpKR3noYO','MTIzNDU2Nzg5MDEyMzQ1NnhFYjF1MDd6aDZXSmJ3TUtIeUc5NnVoTmFvaERILzRzRmtCQU8xa2dMaVk9','MTIzNDU2Nzg5MDEyMzQ1NmNWR3FQUzFtVVpUOHZSczJocmppa0E9PQ==','../assets/profile/default.png','2026-03-20',0,0,0,'2026-03-14 17:09:44','2026-03-19 18:01:04','2026-03-19 18:59:57');
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

-- Dump completed on 2026-03-20 11:07:29
