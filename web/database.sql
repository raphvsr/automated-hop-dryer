-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: rpsklproject
-- ------------------------------------------------------
-- Server version	8.0.39
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
-- Table structure for table `alerts`
--
DROP TABLE IF EXISTS `alerts`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `alerts` (
    `id` int NOT NULL AUTO_INCREMENT,
    `campaign_id` int NOT NULL,
    `alert_time` datetime NOT NULL,
    `alert_type` enum ('') NOT NULL,
    `message` text NOT NULL,
    PRIMARY KEY (`id`),
    KEY `alerts_campaign_id_foreign` (`campaign_id`),
    CONSTRAINT `alerts_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `drying_campaigns` (`id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `alerts`
--
LOCK TABLES `alerts` WRITE;

/*!40000 ALTER TABLE `alerts` DISABLE KEYS */;

/*!40000 ALTER TABLE `alerts` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `drying_campaigns`
--
DROP TABLE IF EXISTS `drying_campaigns`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `drying_campaigns` (
    `id` int NOT NULL AUTO_INCREMENT,
    `variety_id` int NOT NULL,
    `start_time` datetime NOT NULL,
    `end_time` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `drying_campaigns_variety_id_foreign` (`variety_id`),
    CONSTRAINT `drying_campaigns_variety_id_foreign` FOREIGN KEY (`variety_id`) REFERENCES `hop_varieties` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `drying_campaigns`
--
LOCK TABLES `drying_campaigns` WRITE;

/*!40000 ALTER TABLE `drying_campaigns` DISABLE KEYS */;

INSERT INTO
  `drying_campaigns`
VALUES
  (
    1,
    1,
    '2024-01-01 08:00:00',
    '2024-01-01 14:00:00'
  ),
  (
    2,
    2,
    '2024-01-02 09:00:00',
    '2024-01-02 15:00:00'
  ),
  (
    3,
    3,
    '2024-01-03 10:00:00',
    '2024-01-03 16:00:00'
  ),
  (
    4,
    4,
    '2024-01-04 11:00:00',
    '2024-01-04 17:00:00'
  );

/*!40000 ALTER TABLE `drying_campaigns` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `hop_varieties`
--
DROP TABLE IF EXISTS `hop_varieties`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `hop_varieties` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `max_temperature` decimal(5, 2) NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `hop_varieties_name_unique` (`name`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hop_varieties`
--
LOCK TABLES `hop_varieties` WRITE;

/*!40000 ALTER TABLE `hop_varieties` DISABLE KEYS */;

INSERT INTO
  `hop_varieties`
VALUES
  (1, 'Cascade', 55.00, '2025-03-12 18:37:53'),
  (2, 'Centennial', 57.50, '2025-03-12 18:37:53'),
  (3, 'Chinook', 60.00, '2025-03-12 18:37:53'),
  (4, 'Amarillo', 58.00, '2025-03-12 18:37:53');

/*!40000 ALTER TABLE `hop_varieties` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `sensor_data`
--
DROP TABLE IF EXISTS `sensor_data`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `sensor_data` (
    `id` int NOT NULL AUTO_INCREMENT,
    `campaign_id` int NOT NULL,
    `timestamp` datetime NOT NULL,
    `sensor_location` varchar(50) NOT NULL,
    `temperature` decimal(5, 2) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sensor_data_campaign_id_foreign` (`campaign_id`),
    CONSTRAINT `sensor_data_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `drying_campaigns` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 9 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sensor_data`
--
LOCK TABLES `sensor_data` WRITE;

/*!40000 ALTER TABLE `sensor_data` DISABLE KEYS */;

INSERT INTO
  `sensor_data`
VALUES
  (1, 1, '2024-01-01 08:15:00', 'Top Left', 52.30),
  (2, 1, '2024-01-01 08:30:00', 'Top Right', 53.10),
  (3, 1, '2024-01-01 08:45:00', 'Bottom Left', 51.80),
  (
    4,
    1,
    '2024-01-01 09:00:00',
    'Bottom Right',
    52.50
  ),
  (5, 2, '2024-01-02 09:15:00', 'Top Left', 56.00),
  (6, 2, '2024-01-02 09:30:00', 'Top Right', 56.50),
  (7, 2, '2024-01-02 09:45:00', 'Bottom Left', 55.80),
  (
    8,
    2,
    '2024-01-02 10:00:00',
    'Bottom Right',
    56.20
  );

/*!40000 ALTER TABLE `sensor_data` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `system_config`
--
DROP TABLE IF EXISTS `system_config`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `system_config` (
    `id` int NOT NULL AUTO_INCREMENT,
    `key_name` varchar(100) NOT NULL,
    `value` text NOT NULL,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `system_config_key_name_unique` (`key_name`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_config`
--
LOCK TABLES `system_config` WRITE;

/*!40000 ALTER TABLE `system_config` DISABLE KEYS */;

INSERT INTO
  `system_config`
VALUES
  (
    1,
    'max_temperature_alert_threshold',
    '60.00',
    '2025-03-12 19:00:54'
  ),
  (
    2,
    'min_temperature_alert_threshold',
    '50.00',
    '2025-03-12 19:00:54'
  ),
  (
    3,
    'default_drying_time',
    '360',
    '2025-03-12 19:00:54'
  ),
  (
    4,
    'default_hop_variety',
    '1',
    '2025-03-12 19:00:54'
  );

/*!40000 ALTER TABLE `system_config` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;

/*!40101 SET @saved_cs_client     = @@character_set_client */;

/*!50503 SET character_set_client = utf8mb4 */;

CREATE TABLE
  `users` (
    `id` int NOT NULL AUTO_INCREMENT,
    `username` varchar(50) NOT NULL,
    `password_hash` varchar(255) NOT NULL,
    `role` tinyint NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_username_unique` (`username`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 10 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--
LOCK TABLES `users` WRITE;

/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO
  `users`
VALUES
  (
    7,
    'admin',
    '$2y$10$fxW/BV8ZwU/qvF4/.Thv7uIkdOuNSlLQYBGe4CsRrzihtIcRuPa4C',
    1,
    '2025-03-12 18:36:09'
  ),
  (
    8,
    'operator1',
    '$2y$2y$10$fxW/BV8ZwU/qvF4/.Thv7uIkdOuNSlLQYBGe4CsRrzihtIcRuPa4C',
    0,
    '2025-03-12 18:36:09'
  ),
  (
    9,
    'operator2',
    '$2y$2y$10$fxW/BV8ZwU/qvF4/.Thv7uIkdOuNSlLQYBGe4CsRrzihtIcRuPa4C',
    0,
    '2025-03-12 18:36:09'
  );

/*!40000 ALTER TABLE `users` ENABLE KEYS */;

UNLOCK TABLES;

--
-- Dumping routines for database 'rpsklproject'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-13 17:05:20
