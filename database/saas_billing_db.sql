-- MariaDB dump 10.19  Distrib 10.4.27-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: saas_billing_db
-- ------------------------------------------------------
-- Server version	10.4.27-MariaDB

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
-- Current Database: `saas_billing_db`
--
CREATE DATABASE /*!32312 IF NOT EXISTS*/ `saas_billing_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `saas_billing_db`;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(36) NOT NULL,
  `user_id` varchar(36) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_audit_tenant` (`tenant_id`),
  KEY `idx_audit_created` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,'tenant-demo-uuid-001','user-demo-uuid-001','John Doe (Acme CEO)','test_action','Automated test of audit log mechanism','2026-09-28 11:18:27'),(2,'tenant-demo-uuid-001','user-demo-uuid-001','John Doe (Acme CEO)','test_action','Automated test of audit log mechanism','2026-09-28 11:18:54'),(3,'tenant-demo-uuid-001','user-demo-uuid-001','John Doe (Acme CEO)','subscription_upgraded','Upgraded subscription to Starter Plan (Invoice #INV-2026-0008)','2026-09-28 12:27:33'),(4,'tenant-demo-uuid-001','user-demo-uuid-001','John Doe (Acme CEO)','subscription_upgraded','Upgraded subscription to Professional Plan (Invoice #INV-2026-0009)','2026-09-28 12:28:09'),(5,'tenant-demo-uuid-001','user-demo-uuid-001','John Doe (Acme CEO)','subscription_canceled','Cancelled subscription effective at end of current period','2026-09-28 12:28:41'),(6,'tenant-demo-uuid-001','user-demo-uuid-001','John Doe (Acme CEO)','subscription_upgraded','Upgraded subscription to Starter Plan (Invoice #INV-2026-0010)','2026-09-28 12:41:23'),(7,'tenant-demo-uuid-001','user-demo-uuid-001','John Doe (Acme CEO)','invoice.created','Generated B2B Tax Invoice INV-2026-TEST for \'Adani Logistics Ltd\' totaling ₹56,500.00','2026-09-30 12:43:21'),(8,'tenant-demo-uuid-001','user-demo-uuid-001','John Doe (Acme CEO)','invoice.created','Generated B2B Tax Invoice INV-2026-TEST-8103 for \'Adani Logistics Ltd\' totaling ₹56,500.00','2026-09-30 12:44:33'),(9,'tenant-demo-uuid-001',NULL,'Client Checkout','invoice.paid_online','Invoice INV-2026-TEST-8103 settled online via upi. Txn: TXN_C52BBC9C0413','2026-09-30 12:44:44');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` varchar(36) NOT NULL,
  `tenant_id` varchar(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `company_name` varchar(150) DEFAULT NULL,
  `gstin` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_cust_tenant` (`tenant_id`),
  KEY `idx_cust_email` (`email`),
  CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES ('2e6394c84a5380ddc85d692bdc9e1ce9','tenant-demo-uuid-001','Adani Logistics Ltd','billing@adani.in','+91 99090 11223','Adani Ports & SEZ','24AAACA0000A1Z1','Adani Corporate House, Shantigram, Ahmedabad','','','','2026-09-30 12:43:21','2026-09-30 12:52:02'),('7596d6812f81687c9020242d1e5cbd04','tenant-demo-uuid-001','Amitabh Sengupta','amitabh@tcs.com','+91 98310 54321','Tata Consultancy Services','19AAACT2727Q1ZW','Sector V, Salt Lake','Kolkata','West Bengal','700091','2026-09-30 12:34:46','2026-09-30 12:52:02'),('9e9971d48dc0e80ecc952acca2a40a33','tenant-demo-uuid-001','Rajesh Sharma','rajesh@infosys.com','+91 98200 12345','Infosys Technologies Ltd','27AAACI1681G1ZM','Plot 44, Electronic City, Phase 1','Bengaluru','Karnataka','560100','2026-09-30 12:34:46','2026-09-30 12:52:02'),('cc5c5978e6f9127deaba9844a3d36fcc','tenant-demo-uuid-001','Priya Patel','priya@reliance.in','+91 98790 67890','Reliance Retail Ventures','24AAACR5055K1Z4','Reliance Corporate Park, Ghansoli','Navi Mumbai','Maharashtra','400701','2026-09-30 12:34:46','2026-09-30 12:52:02'),('fbe5a19da97f668a1fa11171752532c7','tenant-demo-uuid-001','Adani Logistics Ltd','billing@adani.in','+91 99090 11223','Adani Ports & SEZ','24AAACA0000A1Z1','Adani Corporate House, Shantigram, Ahmedabad','','','','2026-09-30 12:44:33','2026-09-30 12:52:02');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` varchar(36) NOT NULL,
  `tenant_id` varchar(36) NOT NULL,
  `subscription_id` varchar(36) DEFAULT NULL,
  `customer_id` varchar(36) DEFAULT NULL,
  `customer_name` varchar(150) DEFAULT NULL,
  `customer_email` varchar(150) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `customer_gstin` varchar(50) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `subtotal_cents` int(11) NOT NULL,
  `tax_cents` int(11) NOT NULL DEFAULT 0,
  `discount_cents` int(11) NOT NULL DEFAULT 0,
  `total_cents` int(11) NOT NULL DEFAULT 0,
  `amount_paid_cents` int(11) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'USD',
  `status` enum('paid','open','void','uncollectible') NOT NULL DEFAULT 'open',
  `billing_reason` varchar(100) NOT NULL DEFAULT 'subscription_cycle',
  `notes` text DEFAULT NULL,
  `items_json` longtext DEFAULT NULL,
  `payment_token` varchar(64) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  UNIQUE KEY `idx_inv_payment_token` (`payment_token`),
  KEY `idx_inv_tenant` (`tenant_id`),
  KEY `idx_inv_status` (`status`),
  KEY `idx_inv_number` (`invoice_number`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES ('03d3280090d6a79f587a90f6a5a52eb6','tenant-demo-uuid-001','sub-demo-uuid-001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0010',249900,44982,0,294882,294882,'INR','paid','subscription_update',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":249900,\"amount_cents\":249900,\"tax_rate\":18}]','c356f5828d57417cb8244bba53667a7e',NULL,NULL,'2026-09-28 12:41:23','2026-09-28 12:41:23'),('2957bd5aeb24784a8a7338a165e9a94c','tenant-demo-uuid-001','sub_live_mock_acme_7900',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0003',6695,1205,0,7900,7900,'INR','paid','subscription_cycle',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":6695,\"amount_cents\":6695,\"tax_rate\":18}]','1d7186d154852c73a92981d0edf11ab3',NULL,NULL,'2026-09-28 10:00:19','2026-09-28 10:00:19'),('31704984760bdc2fbf3bde8e6d45d3bf','1e9c5a86b450b57b154cb776dc903cfc','sub_live_mock_acme_7900',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0006',6695,1205,0,7900,7900,'INR','paid','subscription_cycle',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":6695,\"amount_cents\":6695,\"tax_rate\":18}]','03030324cf883bf17ac9365b906ed71d',NULL,NULL,'2026-09-28 10:04:02','2026-09-28 10:04:02'),('36a34409e98a880a7080df1fa4cfb602','tenant-demo-uuid-001',NULL,'fbe5a19da97f668a1fa11171752532c7','Adani Logistics Ltd','billing@adani.in','+91 99090 11223','Adani Corporate House, Shantigram, Ahmedabad','24AAACA0000A1Z1','2026-10-15','INV-2026-TEST-8103',5000000,900000,250000,5650000,5650000,'INR','paid','client_b2b_invoice','Test B2B Invoice generated for Adani Logistics.','[{\"description\":\"Cloud Infrastructure & SaaS Integration Retainer\",\"qty\":2,\"rate_cents\":2500000,\"tax_rate\":18,\"tax_cents\":900000,\"amount_cents\":5900000}]','8fbb27309b8f7cd35394caaed57d964a8759817a290771a7','upi',NULL,'2026-09-30 12:44:44','2026-09-30 12:44:33'),('483f78a5634f8bafd56730ad1a593a2d','tenant-demo-uuid-001','sub-demo-uuid-001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0009',649900,116982,0,766882,766882,'INR','paid','subscription_update',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":649900,\"amount_cents\":649900,\"tax_rate\":18}]','3514d6bdddd2b030483f82140284f67c',NULL,NULL,'2026-09-28 12:28:09','2026-09-28 12:28:09'),('60964c26d15ea1488e304d6df15350a7','tenant-demo-uuid-001','sub-demo-uuid-001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0008',249900,44982,0,294882,294882,'INR','paid','subscription_update',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":249900,\"amount_cents\":249900,\"tax_rate\":18}]','b332f0191fa674563b14af29480be7e0',NULL,NULL,'2026-09-28 12:27:33','2026-09-28 12:27:33'),('79856980c6e4c204c96cabac84cbaa5e','tenant-demo-uuid-001',NULL,'2e6394c84a5380ddc85d692bdc9e1ce9','Adani Logistics Ltd','billing@adani.in','+91 99090 11223','Adani Corporate House, Shantigram, Ahmedabad','24AAACA0000A1Z1','2026-10-15','INV-2026-TEST',5000000,900000,250000,5650000,0,'INR','open','client_b2b_invoice','Test B2B Invoice generated for Adani Logistics.','[{\"description\":\"Cloud Infrastructure & SaaS Integration Retainer\",\"qty\":2,\"rate_cents\":2500000,\"tax_rate\":18,\"tax_cents\":900000,\"amount_cents\":5900000}]','5f0eee550a49832168800fdcd92516a7b09827fb6fc0e5b3',NULL,NULL,NULL,'2026-09-30 12:43:21'),('9e4df6eda70430f89e7781d6acc3718b','1e9c5a86b450b57b154cb776dc903cfc','sub_live_mock_acme_7900',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0007',6695,1205,0,7900,7900,'INR','paid','subscription_cycle',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":6695,\"amount_cents\":6695,\"tax_rate\":18}]','abeb2569ec271bba0968b7f39b2d15f4',NULL,NULL,'2026-09-28 10:04:07','2026-09-28 10:04:07'),('acf5d2aeddda561b055241e347aad881','1e9c5a86b450b57b154cb776dc903cfc','ddcb2fd1bbb1b40dd56a7e7ef9bb1807',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0004',7900,1422,0,9322,9322,'INR','paid','subscription_update',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":7900,\"amount_cents\":7900,\"tax_rate\":18}]','f34a466e7325a3c74ac00d22b89f7222',NULL,NULL,'2026-09-28 10:03:13','2026-09-28 10:03:13'),('b4cd38740babad0761a4517af0dc103f','1e9c5a86b450b57b154cb776dc903cfc','sub_live_mock_acme_7900',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0005',6695,1205,0,7900,7900,'INR','paid','subscription_cycle',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":6695,\"amount_cents\":6695,\"tax_rate\":18}]','a0e324705023bb5252f8f665b3b47a62',NULL,NULL,'2026-09-28 10:04:01','2026-09-28 10:04:01'),('d4d7bc9e2b50fd6e393a2aaef4c28b40','26e90825b7a1671ba41a61b1b3255909','beffa8ad2920426536c9fef0303e706e',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0011',649900,116982,0,766882,766882,'INR','paid','subscription_update',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":649900,\"amount_cents\":649900,\"tax_rate\":18}]','fc0f1b4595f3a7c64f98a134f1de82b6',NULL,NULL,'2026-09-28 09:16:39','2026-09-28 12:46:39'),('inv-demo-uuid-001','tenant-demo-uuid-001','sub-demo-uuid-001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0001',649900,116982,0,766882,766882,'INR','paid','subscription_create',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":649900,\"amount_cents\":649900,\"tax_rate\":18}]','c80c99f3c056847efa8788212562f914',NULL,NULL,'2026-09-28 09:53:22','2026-09-28 09:53:22'),('inv-demo-uuid-002','tenant-demo-uuid-001','sub-demo-uuid-001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'INV-2026-0002',649900,116982,0,766882,766882,'INR','paid','subscription_cycle',NULL,'[{\"description\":\"Enterprise SaaS Platform Subscription & Cloud API Access\",\"qty\":1,\"rate_cents\":649900,\"amount_cents\":649900,\"tax_rate\":18}]','8250773091652658a044fcaaa35d366b',NULL,NULL,'2026-08-29 09:53:22','2026-08-29 09:53:22');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plans`
--

DROP TABLE IF EXISTS `plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `slug` varchar(60) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price_cents` int(11) NOT NULL,
  `billing_interval` enum('monthly','yearly') NOT NULL DEFAULT 'monthly',
  `features_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`features_json`)),
  `max_seats` int(11) NOT NULL DEFAULT 5,
  `gateway_plan_id` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plans`
--

LOCK TABLES `plans` WRITE;
/*!40000 ALTER TABLE `plans` DISABLE KEYS */;
INSERT INTO `plans` VALUES (1,'Starter','starter-monthly','Ideal for early startups and small teams.',2900,'monthly','[\"Up to 5 Team Members\", \"10,000 API Requests/day\", \"Standard Email Support\", \"Automated PDF Invoices\"]',5,'price_starter_monthly_01',1,'2026-09-28 09:53:22'),(2,'Professional','pro-monthly','Everything a growing company needs to scale operations.',7900,'monthly','[\"Up to 25 Team Members\", \"100,000 API Requests/day\", \"Priority 24/7 Support\", \"Custom Webhooks & Integrations\", \"Audit Logs\"]',25,'price_pro_monthly_02',1,'2026-09-28 09:53:22'),(3,'Enterprise','enterprise-yearly','Full-throttle performance, custom SLA and dedicated infrastructure.',29900,'yearly','[\"Unlimited Team Members\", \"Unlimited API Requests\", \"Dedicated Account Manager\", \"Custom SLA & 99.99% Uptime\", \"Advanced Security & SSO\"]',999,'price_enterprise_yearly_03',1,'2026-09-28 09:53:22');
/*!40000 ALTER TABLE `plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `id` varchar(36) NOT NULL,
  `tenant_id` varchar(36) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `gateway_subscription_id` varchar(100) DEFAULT NULL,
  `status` enum('trialing','active','past_due','canceled','unpaid') NOT NULL DEFAULT 'trialing',
  `trial_ends_at` datetime DEFAULT NULL,
  `current_period_start` datetime NOT NULL,
  `current_period_end` datetime NOT NULL,
  `canceled_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `plan_id` (`plan_id`),
  KEY `idx_sub_tenant` (`tenant_id`),
  KEY `idx_sub_status` (`status`),
  CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES ('b99c0a95fbc4043c7ae277167d7d15e1','19bfa72cb487cbfbd38354f9ededf056',1,NULL,'trialing','2026-10-12 06:30:32','2026-09-28 06:30:32','2026-10-12 06:30:32',NULL,'2026-09-28 10:00:32','2026-09-28 10:00:32'),('beffa8ad2920426536c9fef0303e706e','26e90825b7a1671ba41a61b1b3255909',2,NULL,'active','2026-10-12 08:50:13','2026-09-28 09:16:39','2026-10-28 09:16:39',NULL,'2026-09-28 12:20:13','2026-09-28 12:46:39'),('ddcb2fd1bbb1b40dd56a7e7ef9bb1807','1e9c5a86b450b57b154cb776dc903cfc',2,NULL,'past_due','2026-10-12 10:02:37','2026-09-28 10:03:13','2026-10-28 10:03:13',NULL,'2026-09-28 10:02:37','2026-09-28 10:04:03'),('sub-demo-uuid-001','tenant-demo-uuid-001',1,'sub_live_mock_acme_7900','active',NULL,'2026-09-28 12:41:23','2026-10-28 12:41:23','2026-09-28 12:28:41','2026-09-28 09:53:22','2026-09-28 12:41:23');
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `super_admins`
--

DROP TABLE IF EXISTS `super_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `super_admins` (
  `id` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `super_admins`
--

LOCK TABLES `super_admins` WRITE;
/*!40000 ALTER TABLE `super_admins` DISABLE KEYS */;
INSERT INTO `super_admins` VALUES ('superadmin-uuid-001','Platform Super Admin','superadmin@saasify.app','$2y$10$EZg5Zv0IHt76C7VvLbOTUefK0xPBO0TOsZAAhlVia.eLLcuWujWMK','2026-09-28 10:14:55');
/*!40000 ALTER TABLE `super_admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tenants` (
  `id` varchar(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `subdomain` varchar(60) NOT NULL,
  `tax_id` varchar(50) DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `status` enum('trial','active','suspended') DEFAULT 'trial',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `subdomain` (`subdomain`),
  KEY `idx_tenant_subdomain` (`subdomain`),
  KEY `idx_tenant_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES ('19bfa72cb487cbfbd38354f9ededf056','Cyberdyne Systems','cyberdyne',NULL,NULL,NULL,'trial','2026-09-28 10:00:32','2026-09-28 10:00:32'),('1e9c5a86b450b57b154cb776dc903cfc','abc','stark',NULL,NULL,NULL,'active','2026-09-28 10:02:36','2026-09-28 10:16:39'),('26e90825b7a1671ba41a61b1b3255909','Acme Test Startup','org1790578213',NULL,NULL,NULL,'active','2026-09-28 12:20:13','2026-09-28 12:35:25'),('tenant-demo-uuid-001','Acme Cloud Technologies','acme','24AAACA1234A1Z5','Plot 45, SG Highway, Bodakdev, Ahmedabad, Gujarat 380054','+91 98765 43210','active','2026-09-28 09:53:22','2026-09-30 12:33:20');
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `tenant_id` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('owner','admin','billing_manager','member') NOT NULL DEFAULT 'member',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_tenant_email` (`tenant_id`,`email`),
  KEY `idx_user_email` (`email`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('0904892db616d64e196e4dc66d7fc9c9','1e9c5a86b450b57b154cb776dc903cfc','tony','tony@gmail.com','$2y$12$/xzO8QICCVhHmcROrjGjLOcBfLdaMr/QeYNIGD/uBVHRAd.ZqZFBS','owner','2026-09-28 10:02:37','2026-09-28 11:21:24'),('4ffca7d9992d910f1e6f20a14c899aa4','26e90825b7a1671ba41a61b1b3255909','Startup Founder','founder1790578213@company.com','$2y$12$pUH65TeyyE1BwMMpKLdVZeBZqmgVrSR724p3ZhRVVOJVdeFfI06iS','owner','2026-09-28 12:20:13','2026-09-28 12:20:13'),('dc041a18762e1740f55265bd3c6dcf75','19bfa72cb487cbfbd38354f9ededf056','Miles Dyson','dyson@cyberdyne.com','$2y$12$/xzO8QICCVhHmcROrjGjLOcBfLdaMr/QeYNIGD/uBVHRAd.ZqZFBS','owner','2026-09-28 10:00:32','2026-09-28 11:21:24'),('user-demo-uuid-001','tenant-demo-uuid-001','John Doe (Acme CEO)','owner@acme.com','$2y$12$/xzO8QICCVhHmcROrjGjLOcBfLdaMr/QeYNIGD/uBVHRAd.ZqZFBS','owner','2026-09-28 09:53:22','2026-09-28 11:21:24'),('user-demo-uuid-002','tenant-demo-uuid-001','Sarah Connor (Billing)','billing@acme.com','$2y$12$/xzO8QICCVhHmcROrjGjLOcBfLdaMr/QeYNIGD/uBVHRAd.ZqZFBS','billing_manager','2026-09-28 09:53:22','2026-09-28 11:21:24');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webhook_events`
--

DROP TABLE IF EXISTS `webhook_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webhook_events` (
  `id` varchar(36) NOT NULL,
  `gateway` varchar(50) NOT NULL,
  `external_event_id` varchar(150) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `payload` longtext NOT NULL,
  `status` enum('pending','processed','failed') NOT NULL DEFAULT 'pending',
  `error_message` text DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_event_id` (`external_event_id`),
  KEY `idx_webhook_ext_id` (`external_event_id`),
  KEY `idx_webhook_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webhook_events`
--

LOCK TABLES `webhook_events` WRITE;
/*!40000 ALTER TABLE `webhook_events` DISABLE KEYS */;
INSERT INTO `webhook_events` VALUES ('26b47e5c8816b086cfd665615a1874ff','stripe','evt_test_1790589619.39425','invoice.payment_succeeded','{\"created\":1790589619,\"data\":{\"object\":{\"subscription\":\"sub_live_mock_acme_7900\",\"tenant_id\":\"tenant-demo-uuid-001\",\"customer\":\"tenant-demo-uuid-001\",\"amount_paid\":7900,\"currency\":\"usd\"}},\"id\":\"evt_test_1790589619.39425\",\"type\":\"invoice.payment_succeeded\"}','processed',NULL,'2026-09-28 10:00:19','2026-09-28 10:00:19'),('99ede8b624dc38aacc43bf774ca87a38','stripe','evt_fixed_test_repeat_01','invoice.payment_succeeded','{\"id\":\"evt_fixed_test_repeat_01\",\"type\":\"invoice.payment_succeeded\",\"created\":1790570047,\"data\":{\"object\":{\"tenant_id\":\"1e9c5a86b450b57b154cb776dc903cfc\",\"customer\":\"1e9c5a86b450b57b154cb776dc903cfc\",\"amount_paid\":7900,\"currency\":\"usd\",\"subscription\":\"sub_live_mock_acme_7900\"}}}','processed',NULL,'2026-09-28 10:04:07','2026-09-28 10:04:07'),('a7b0f1fa17cf2b829b3057cc09622cce','stripe','evt_test_1790570043888','invoice.payment_failed','{\"id\":\"evt_test_1790570043888\",\"type\":\"invoice.payment_failed\",\"created\":1790570043,\"data\":{\"object\":{\"tenant_id\":\"1e9c5a86b450b57b154cb776dc903cfc\",\"customer\":\"1e9c5a86b450b57b154cb776dc903cfc\",\"amount_paid\":7900,\"currency\":\"usd\",\"subscription\":\"sub_live_mock_acme_7900\"}}}','processed',NULL,'2026-09-28 10:04:03','2026-09-28 10:04:03'),('d4bc400a3284282b51038ed5b412d368','stripe','evt_test_1790570041427','invoice.payment_succeeded','{\"id\":\"evt_test_1790570041427\",\"type\":\"invoice.payment_succeeded\",\"created\":1790570041,\"data\":{\"object\":{\"tenant_id\":\"1e9c5a86b450b57b154cb776dc903cfc\",\"customer\":\"1e9c5a86b450b57b154cb776dc903cfc\",\"amount_paid\":7900,\"currency\":\"usd\",\"subscription\":\"sub_live_mock_acme_7900\"}}}','processed',NULL,'2026-09-28 10:04:01','2026-09-28 10:04:01'),('e35e2ab673a13527abdf4c44d6cbd357','stripe','evt_test_1790570042815','invoice.payment_succeeded','{\"id\":\"evt_test_1790570042815\",\"type\":\"invoice.payment_succeeded\",\"created\":1790570042,\"data\":{\"object\":{\"tenant_id\":\"1e9c5a86b450b57b154cb776dc903cfc\",\"customer\":\"1e9c5a86b450b57b154cb776dc903cfc\",\"amount_paid\":7900,\"currency\":\"usd\",\"subscription\":\"sub_live_mock_acme_7900\"}}}','processed',NULL,'2026-09-28 10:04:02','2026-09-28 10:04:02');
/*!40000 ALTER TABLE `webhook_events` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-30 13:57:05
