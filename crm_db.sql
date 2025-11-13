-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 31, 2025 at 08:47 AM
-- Server version: 9.1.0
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

DROP TABLE IF EXISTS `banks`;
CREATE TABLE IF NOT EXISTS `banks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `branch_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ac_no` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ifsc_code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `company_id`, `name`, `branch_address`, `ac_no`, `ifsc_code`, `created_at`) VALUES
(6, 8, 'Shinhan Bank', 'D-5, South Extn, Part-2, New Delhi', '701000033485', 'SHBK0000004', '2025-09-23 22:00:31'),
(7, 8, 'Shinhan Bank', 'D-5, South Extn. Part-2, New Delhi', '701000036450', 'SHBK0000004', '2025-09-24 21:44:15');

-- --------------------------------------------------------

--
-- Table structure for table `bank_details`
--

DROP TABLE IF EXISTS `bank_details`;
CREATE TABLE IF NOT EXISTS `bank_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `branch_address` text COLLATE utf8mb4_general_ci,
  `account_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ifsc_code` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(4, 'Digital', '2025-09-24 05:05:18'),
(5, 'ATL', '2025-09-24 05:05:27'),
(6, 'BTL', '2025-09-24 05:05:33'),
(7, 'IOC', '2025-09-24 05:15:40');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gst_no` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pan_card` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `pin_code` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gstin` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state_code` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `company_id`, `name`, `mobile`, `email`, `gst_no`, `pan_card`, `address`, `pin_code`, `country`, `state`, `gstin`, `pan`, `state_code`, `city`, `created_at`) VALUES
(7, 8, 'LG Electronics India Ltd', '', '', '09AAACL1745Q2Z1', 'AAACL1745Q', 'C-001B 12th  Floor to 20th Floor KP Towers/ Tower-1, Sector 16B,Noida, Gautam Buddha Nagar, Uttar Pradesh, 201301', '', 'India', 'Uttar Pradesh', NULL, NULL, '09', 'Noida', '2025-09-24 09:09:13'),
(8, 8, 'ADS Spirits Private Limited', NULL, NULL, '06AAICA3943K1ZJ', 'AAICA3943K', 'ADS Tower,Dharampura,Land Mark-MLA Wali Gali District,Jhajjar Bahadurgarh,124507', '124507', 'India', 'Haryana', NULL, NULL, '06', 'Bahadurgarh', '2025-09-25 04:47:32'),
(9, 8, 'HI-M.SOLUTEK INDIA PVT. LTD.', NULL, NULL, '09AAHCH0456M1Z9', 'AAHCH0456M', '11th Floor,C-001,KK Projects,Tower D,Sector 16B,Noida-201301', '201301', 'India', 'Uttar Pradesh', NULL, NULL, '09', 'Noida', '2025-09-25 04:48:55'),
(10, 8, 'LG Electronics India Ltd', NULL, NULL, '07AAACL1745Q1Z6', 'AAACL1745Q', 'A-24/6,Mohan Co-Operative Industrial Estate,Near Sarita Vihar Metro Station,Pillar No-284, Delhi', '110076', 'India', 'DELHI', NULL, NULL, '07', 'NEW DELHI', '2025-09-25 04:53:01'),
(11, 8, 'LG Electronics India Ltd', NULL, NULL, '20AAACL1745Q1ZI', 'AAACL1745Q', '203 & 204,Jokhiram Chambers 2nd Floor,J D Compound,Main Road,Ranchi 834001', '834001', 'India', 'Jharkhand', NULL, NULL, '20', 'Ranchi', '2025-09-25 04:54:10'),
(12, 8, 'LG Electronics India Ltd', NULL, NULL, '27AAACL1745Q1Z4', 'AAACL1745Q', '7th Floor,Near Mirador Hotel,Chakala,Andheri Ghatkopar Link Road,Andheri(East),Mumbai-400099', '400099', 'India', 'Maharashtra', NULL, NULL, '27', 'Mumbai', '2025-09-25 04:54:55'),
(13, 8, 'LG Electronics India Ltd', NULL, NULL, '29AAACL1745Q1Z0', 'AAACL1745Q', '9th Floor \'D\' Tower IBC Knowledge Park,Bannerghatta Road,Bangalore, Karnataka, 560029', '560029', 'India', 'Karnataka', NULL, NULL, '29', 'Banglore', '2025-09-25 04:55:45'),
(14, 8, 'LG Electronics India Ltd Cochin', NULL, NULL, '32AAACL1745Q1ZD', 'AAACL1745Q', '34/565-B,NH By Pass,Padivattom ,Edapally,Cochin,Kerala', '682011', 'India', 'Kerala', NULL, NULL, '32', 'Cochin', '2025-09-25 04:58:10');

-- --------------------------------------------------------

--
-- Table structure for table `client_contacts`
--

DROP TABLE IF EXISTS `client_contacts`;
CREATE TABLE IF NOT EXISTS `client_contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_client_id` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `website` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `job_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `pin_code` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gstin` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gst_no` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cin_no` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pan_card` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_general_ci,
  `terms_conditions` text COLLATE utf8mb4_general_ci,
  `logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `mobile`, `email`, `website`, `job_no`, `address`, `pin_code`, `country`, `state`, `gstin`, `pan`, `city`, `gst_no`, `cin_no`, `pan_card`, `note`, `terms_conditions`, `logo`, `created_at`) VALUES
(8, 'GIIR Communication India Pvt. Ltd.', '1204624900', 'info@hsadindia.com', 'https://hsad.co.in/', '', 'C - 001B, 12 Floor, KP Towers - 1, Sector-16B, \r\nNoida, Gautam Buddha Nagar, Uttar Pradesh, 201301', '201301', 'India', 'Uttar Pradesh', NULL, NULL, 'Noida', '09AADCG6293R1ZP', 'U74300DL2010FTC19764', 'AADCG6293R', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', '1758689921.png', '2025-09-23 21:58:41');

-- --------------------------------------------------------

--
-- Table structure for table `company_profiles`
--

DROP TABLE IF EXISTS `company_profiles`;
CREATE TABLE IF NOT EXISTS `company_profiles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci,
  `pin_code` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gst_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cin_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pan_card` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `job_no` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_general_ci,
  `terms_conditions` text COLLATE utf8mb4_general_ci,
  `logo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

DROP TABLE IF EXISTS `email_queue`;
CREATE TABLE IF NOT EXISTS `email_queue` (
  `id` int NOT NULL AUTO_INCREMENT,
  `to_email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `cc_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bcc_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `priority` tinyint DEFAULT '1',
  `status` enum('pending','sent','failed') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `attempts` tinyint DEFAULT '0',
  `error_message` text COLLATE utf8mb4_general_ci,
  `scheduled_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_scheduled_at` (`scheduled_at`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `mail_logs`
--

DROP TABLE IF EXISTS `mail_logs`;
CREATE TABLE IF NOT EXISTS `mail_logs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `quotation_id` int NOT NULL,
  `to_emails` text COLLATE utf8mb4_general_ci NOT NULL,
  `cc_emails` text COLLATE utf8mb4_general_ci,
  `subject` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `attachment` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sent_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('sent','failed') COLLATE utf8mb4_general_ci DEFAULT 'sent',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `parent_id` int DEFAULT NULL,
  `role` enum('Super Admin','Admin','Viewer') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `title`, `url`, `icon`, `parent_id`, `role`) VALUES
(1, 'Dashboard', 'dashboard', 'fas fa-tachometer-alt', NULL, 'Super Admin'),
(2, 'Company Profile', 'company', 'fas fa-building', NULL, 'Super Admin'),
(3, 'Bank Details', 'bank', 'fas fa-university', NULL, 'Super Admin'),
(4, 'Client Details', 'client', 'fas fa-users', NULL, 'Admin'),
(5, 'Client Contacts', 'contact', 'fas fa-address-book', NULL, 'Admin'),
(6, 'Modes', 'mode', 'fas fa-clock', NULL, 'Super Admin'),
(7, 'Product/Service', 'product-service', 'fas fa-boxes', NULL, 'Super Admin'),
(8, 'User Management', 'user', 'fas fa-user-cog', NULL, 'Super Admin'),
(9, 'Logout', 'auth/logout', 'fas fa-sign-out-alt', NULL, 'Viewer');

-- --------------------------------------------------------

--
-- Table structure for table `modes`
--

DROP TABLE IF EXISTS `modes`;
CREATE TABLE IF NOT EXISTS `modes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `days` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modes`
--

INSERT INTO `modes` (`id`, `name`, `days`, `created_at`) VALUES
(52, '10 Days', 10, '2025-09-24 02:07:39'),
(51, '60 Days', 60, '2025-09-24 02:07:30'),
(50, '45 Days', 45, '2025-09-24 02:07:21'),
(49, '15 Days', 15, '2025-09-24 02:07:10'),
(48, '20 Days', 20, '2025-09-24 02:07:00'),
(45, '30 Days', 30, '2025-06-30 11:01:35');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_token` (`token`),
  KEY `idx_email` (`email`),
  KEY `idx_expires` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'rajvraj121@gmail.com', '29ec877f1afe6627cbced614b2a522c0ea4c5f1b209d9dcfade683e0d8abf2d3', '2025-09-03 07:23:02', '2025-09-03 06:23:02');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `rate_per_unit` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `rate_per_unit`) VALUES
(1, 1, 'yyyy ttt ccc', 200.00),
(2, 1, '4 pages web site', 400.00),
(3, 3, 'eeeeeeeeee', 400.00),
(4, 4, 'mailer', 2000.00),
(5, 5, 'Website', 10.00),
(6, 5, 'Page', 100.00),
(7, 5, 'erth', 6777.00),
(66, 14, 'Social Media Carousel (Maximum 5 frames)', 8490.00),
(67, 14, 'Social Media Post/Story - Static Adaptation', 1061.00),
(68, 14, 'Social Media Post/Story - Static Master', 5306.00),
(69, 12, 'Compressing & Formatting - 46 sec - 60 sec', 13265.00),
(70, 12, 'Compressing & Formatting - 31 sec - 45 sec', 10612.00),
(71, 12, 'Compressing & Formatting - 16 sec - 30 sec', 7959.00),
(72, 12, 'Compressing & Formatting - 15 sec', 5306.00),
(73, 12, 'Video Streaming (15 sec)', 0.00),
(74, 12, 'Animated (Sound / Interactive)', 15918.00),
(75, 12, 'Normal', 7959.00);

-- --------------------------------------------------------

--
-- Table structure for table `products_services`
--

DROP TABLE IF EXISTS `products_services`;
CREATE TABLE IF NOT EXISTS `products_services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `rate_per_unit` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_service_categories`
--

DROP TABLE IF EXISTS `product_service_categories`;
CREATE TABLE IF NOT EXISTS `product_service_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_service_categories`
--

INSERT INTO `product_service_categories` (`id`, `name`, `created_at`) VALUES
(8, 'Website Designing - Adaptations', '2025-09-23 23:23:48'),
(7, 'Website Designing', '2025-09-23 23:10:20'),
(6, 'Banner- New Concept', '2025-09-23 23:10:05'),
(9, 'Emailer', '2025-09-23 23:23:56'),
(10, 'Emailer-Adaptations', '2025-09-23 23:24:05'),
(11, 'Wallpaper', '2025-09-23 23:24:13'),
(12, 'Screen Savers', '2025-09-23 23:24:20'),
(14, 'Social Media', '2025-09-23 23:24:36');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

DROP TABLE IF EXISTS `quotations`;
CREATE TABLE IF NOT EXISTS `quotations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL,
  `company_snapshot` json DEFAULT NULL COMMENT 'Snapshot of company data at quotation creation',
  `client_id` int NOT NULL,
  `client_snapshot` json DEFAULT NULL COMMENT 'Snapshot of client data at quotation creation',
  `bank_id` int NOT NULL,
  `bank_snapshot` json DEFAULT NULL COMMENT 'Snapshot of bank data at quotation creation',
  `contact_person` varchar(255) NOT NULL,
  `department` varchar(255) DEFAULT NULL,
  `state` varchar(255) NOT NULL,
  `mode_id` int NOT NULL,
  `mode_snapshot` json DEFAULT NULL COMMENT 'Snapshot of mode data at quotation creation',
  `hsn_sac` varchar(50) DEFAULT NULL,
  `job_no` varchar(128) DEFAULT NULL,
  `terms` text,
  `notes` text,
  `attachment` varchar(255) DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `gst_type` enum('IGST','CGST+SGST') NOT NULL,
  `gst_rate` decimal(5,2) NOT NULL DEFAULT '18.00',
  `gst_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `grand_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_company_id` (`company_id`),
  KEY `idx_client_id` (`client_id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_company_snapshot` (`company_id`),
  KEY `idx_client_snapshot` (`client_id`),
  KEY `idx_bank_snapshot` (`bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `company_id`, `company_snapshot`, `client_id`, `client_snapshot`, `bank_id`, `bank_snapshot`, `contact_person`, `department`, `state`, `mode_id`, `mode_snapshot`, `hsn_sac`, `job_no`, `terms`, `notes`, `attachment`, `total_amount`, `gst_type`, `gst_rate`, `gst_amount`, `grand_total`, `created_at`, `updated_at`) VALUES
(23, 8, NULL, 6, NULL, 6, NULL, 'Anil', 'Digital', 'Delhi', 32, NULL, '998361', NULL, '30 days from the date of dilvery', 'Contract/Lock-in period will be 12 months.', NULL, 2713162.20, 'IGST', 18.00, 413872.20, 0.00, '2025-09-24 06:12:37', '2025-09-23 23:12:37'),
(24, 8, NULL, 7, NULL, 6, NULL, 'Anil', 'Digital', 'Uttar Pradesh', 45, NULL, '998361', NULL, '30 days from the date of dilvery', 'Contract/Lock-in period will be 12 months.', NULL, 1622976.72, 'CGST+SGST', 18.00, 247572.72, 0.00, '2025-09-24 09:10:30', '2025-09-24 02:10:30'),
(25, 8, NULL, 12, NULL, 6, NULL, 'Anil', 'Digital', 'Kerala', 45, NULL, '998361', '', '30 days from the date of dilvery', 'Contract/Lock-in period will be 12 months.', NULL, 1031244.48, 'IGST', 18.00, 157308.48, 0.00, '2025-09-25 04:59:09', '2025-09-24 21:59:09'),
(26, 8, NULL, 14, NULL, 6, NULL, 'Anil', 'BTL', 'Kerala', 45, NULL, '998361', '23456789', '30 days from the date of dilvery', 'Contract/Lock-in period will be 12 months.', NULL, 372921.30, 'IGST', 18.00, 56886.30, 0.00, '2025-09-25 05:00:39', '2025-09-24 22:00:39'),
(27, 8, NULL, 14, NULL, 6, NULL, 'Anil', 'Digital', 'Kerala', 45, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 715117.76, 'IGST', 18.00, 109085.76, 0.00, '2025-09-25 05:34:25', '2025-09-24 22:34:25'),
(28, 8, NULL, 11, NULL, 6, NULL, 'Anil', 'Digital', 'Jharkhand', 45, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', '', 26219.60, 'IGST', 18.00, 3999.60, 0.00, '2025-10-03 10:03:59', '2025-10-16 06:29:22'),
(29, 8, NULL, 10, NULL, 6, NULL, 'Anil', 'Digital', 'Delhi', 45, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 125221.60, 'IGST', 18.00, 19101.60, 0.00, '2025-10-27 05:47:52', '2025-10-26 22:47:52'),
(30, 8, NULL, 13, NULL, 7, NULL, 'Anil', 'Digital', 'Karnataka', 45, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 3684856.80, 'IGST', 18.00, 562096.80, 0.00, '2025-10-27 05:50:28', '2025-10-26 22:50:28'),
(31, 8, NULL, 13, NULL, 7, NULL, 'Anil', 'Digital', 'Karnataka', 45, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 3684856.80, 'IGST', 18.00, 562096.80, 0.00, '2025-10-27 05:51:16', '2025-10-26 22:51:16'),
(32, 8, NULL, 10, NULL, 7, NULL, 'Anil', 'Digital', 'Delhi', 45, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 3684856.80, 'IGST', 18.00, 562096.80, 0.00, '2025-10-27 05:52:06', '2025-10-26 22:52:06'),
(33, 8, NULL, 10, NULL, 7, NULL, 'Anil', 'IOC', 'Delhi', 45, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 3684856.80, 'IGST', 18.00, 562096.80, 0.00, '2025-10-27 05:53:40', '2025-10-26 22:53:40'),
(34, 8, NULL, 10, NULL, 7, NULL, 'Anil', 'IOC', 'Delhi', 45, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 1180.00, 'IGST', 18.00, 180.00, 0.00, '2025-10-27 05:54:24', '2025-10-26 22:54:24'),
(35, 8, NULL, 12, NULL, 7, NULL, 'Anil', 'BTL', 'Maharashtra', 45, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 25145.80, 'IGST', 18.00, 3835.80, 0.00, '2025-10-27 11:42:25', '2025-10-27 04:42:25'),
(36, 8, NULL, 7, NULL, 6, NULL, 'Anil', 'Digital', 'Uttar Pradesh', 52, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 131487.40, 'CGST+SGST', 18.00, 20057.40, 0.00, '2025-10-30 04:57:37', '2025-10-29 21:57:37'),
(37, 8, NULL, 9, NULL, 7, NULL, 'Anil', 'Digital', 'Uttar Pradesh', 45, NULL, '998361', 'PIN250058-J022', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 303661.20, 'CGST+SGST', 18.00, 46321.20, 0.00, '2025-10-30 05:05:55', '2025-10-29 22:05:55'),
(38, 8, NULL, 7, NULL, 6, NULL, 'uuttty', 'BTL', 'Andhra Pradesh', 52, NULL, 'tyuytuytut', 'utyutuytut', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', '2148296213666b04487cc4c59c2fe1ff.png', 25044.32, 'IGST', 18.00, 3820.32, 0.00, '2025-10-30 10:58:01', '2025-10-30 16:28:01'),
(39, 8, NULL, 7, NULL, 6, NULL, 'gjgjgh', 'IOC', 'Arunachal Pradesh', 51, NULL, 'hjhgjhg', 'hgjhgj', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 2010.72, 'IGST', 18.00, 306.72, 0.00, '2025-10-30 11:09:16', '2025-10-30 16:39:16'),
(40, 8, NULL, 7, NULL, 6, NULL, 'tryrtyr', 'IOC', 'Arunachal Pradesh', 52, NULL, 'ryryryr', 'rtyrtyr', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 50091.00, 'IGST', 18.00, 7641.00, 0.00, '2025-10-30 12:05:01', '2025-10-30 17:35:01'),
(41, 8, NULL, 7, NULL, 6, NULL, 'tuyuytu', 'IOC', 'Andhra Pradesh', 52, NULL, '65756567', '56756765756', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', 'b5dedf67b56d260cd30e225065220dcd.png', 37566.48, 'IGST', 18.00, 5730.48, 0.00, '2025-10-31 06:36:20', '2025-10-31 12:06:20'),
(42, 8, '{\"id\": \"8\", \"logo\": \"1758689921.png\", \"name\": \"GIIR Communication India Pvt. Ltd.\", \"email\": \"info@hsadindia.com\", \"state\": \"Uttar Pradesh\", \"cin_no\": \"U74300DL2010FTC19764\", \"gst_no\": \"09AADCG6293R1ZP\", \"mobile\": \"1204624900\", \"address\": \"C - 001B, 12 Floor, KP Towers - 1, Sector-16B, \\r\\nNoida, Gautam Buddha Nagar, Uttar Pradesh, 201301\", \"pan_card\": \"AADCG6293R\", \"captured_at\": \"2025-10-31 06:42:12\"}', 7, '{\"id\": \"7\", \"name\": \"LG Electronics India Ltd\", \"email\": \"\", \"state\": \"Uttar Pradesh\", \"gst_no\": \"09AAACL1745Q2Z1\", \"mobile\": \"\", \"address\": \"C-001B 12th  Floor to 20th Floor KP Towers/ Tower-1, Sector 16B,Noida, Gautam Buddha Nagar, Uttar Pradesh, 201301\", \"pan_card\": \"AAACL1745Q\", \"captured_at\": \"2025-10-31 06:42:12\"}', 6, '{\"id\": \"6\", \"name\": \"Shinhan Bank\", \"ac_no\": \"701000033485\", \"ifsc_code\": \"SHBK0000004\", \"captured_at\": \"2025-10-31 06:42:12\", \"branch_address\": \"D-5, South Extn, Part-2, New Delhi\"}', 'yryuyruyur', 'BTL', 'Andhra Pradesh', 51, '{\"id\": \"51\", \"name\": \"60 Days\", \"captured_at\": \"2025-10-31 06:42:12\"}', 'rtrurur', 'rrutruru', 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost.', 'Kindly Sign the duplicate copy of the Estimate as a token of acknowledgement and return to us.\r\nif any Discrepancy related to the Estimate, Please revert on krishan.k@hsadindia.com.', NULL, 46958.10, 'IGST', 18.00, 7163.10, 0.00, '2025-10-31 06:42:12', '2025-10-31 12:12:12');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_emails`
--

DROP TABLE IF EXISTS `quotation_emails`;
CREATE TABLE IF NOT EXISTS `quotation_emails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quotation_id` int NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `to_email` text COLLATE utf8mb4_general_ci,
  `cc_email` text COLLATE utf8mb4_general_ci,
  `attachment` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('sent','failed') COLLATE utf8mb4_general_ci DEFAULT 'sent',
  `sent_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `quotation_id` (`quotation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

DROP TABLE IF EXISTS `quotation_items`;
CREATE TABLE IF NOT EXISTS `quotation_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `quotation_id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `category_snapshot` json DEFAULT NULL COMMENT 'Snapshot of category data',
  `product_id` int DEFAULT NULL,
  `product_snapshot` json DEFAULT NULL COMMENT 'Snapshot of product data',
  `description` text COMMENT 'Custom description when use_dropdown is 0',
  `use_dropdown` tinyint(1) DEFAULT '1' COMMENT '1 = use dropdowns, 0 = use description field',
  `qty` int NOT NULL,
  `rate` decimal(12,2) NOT NULL,
  `discount` decimal(5,2) DEFAULT '0.00',
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_quotation_id` (`quotation_id`),
  KEY `idx_product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `quotation_items`
--

INSERT INTO `quotation_items` (`id`, `quotation_id`, `category_id`, `category_snapshot`, `product_id`, `product_snapshot`, `description`, `use_dropdown`, `qty`, `rate`, `discount`, `amount`) VALUES
(70, 23, 7, NULL, 11, NULL, NULL, 1, 5, 280908.00, 0.00, 1404540.00),
(71, 23, 6, NULL, 9, NULL, NULL, 1, 10, 2081.00, 0.00, 20810.00),
(72, 23, 7, NULL, 10, NULL, NULL, 1, 4, 156060.00, 0.00, 624240.00),
(73, 23, 6, NULL, 8, NULL, NULL, 1, 20, 12485.00, 0.00, 249700.00),
(74, 24, 12, NULL, 54, NULL, NULL, 1, 10, 10404.00, 0.00, 104040.00),
(75, 24, 7, NULL, 24, NULL, NULL, 1, 2, 421362.00, 0.00, 842724.00),
(76, 24, 10, NULL, 43, NULL, NULL, 1, 15, 832.00, 0.00, 12480.00),
(77, 24, 6, NULL, 22, NULL, NULL, 1, 10, 41616.00, 0.00, 416160.00),
(78, 25, 13, NULL, 37, NULL, NULL, 1, 56, 15606.00, 0.00, 873936.00),
(79, 26, 6, NULL, 23, NULL, NULL, 1, 45, 7023.00, 0.00, 316035.00),
(80, 27, 7, NULL, 27, NULL, NULL, 1, 2, 105341.00, 0.00, 210682.00),
(81, 27, 6, NULL, 20, NULL, NULL, 1, 10, 26010.00, 0.00, 260100.00),
(82, 27, 14, NULL, 35, NULL, NULL, 1, 10, 8323.00, 0.00, 83230.00),
(83, 27, 11, NULL, 41, NULL, NULL, 1, 20, 2601.00, 0.00, 52020.00),
(85, 28, 6, NULL, 57, NULL, NULL, 1, 10, 2122.00, 0.00, 21220.00),
(86, 28, 0, NULL, 0, NULL, 'test', 0, 10, 100.00, 0.00, 1000.00),
(87, 29, 6, NULL, 58, NULL, NULL, 1, 10, 6367.00, 0.00, 63670.00),
(88, 29, 6, NULL, 57, NULL, NULL, 1, 5, 2122.00, 0.00, 10610.00),
(89, 29, 6, NULL, 59, NULL, NULL, 1, 20, 1592.00, 0.00, 31840.00),
(90, 35, NULL, NULL, NULL, NULL, 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost', 0, 5, 100.00, 0.00, 500.00),
(91, 35, NULL, NULL, NULL, NULL, 'Any rectification or changes in estimate should be reported within 7 days.\r\nClient receipt of the estimate will be treated as an final approval for above quoted cost', 0, 10, 2081.00, 0.00, 20810.00),
(92, 36, 14, NULL, 61, NULL, NULL, 1, 5, 5306.00, 0.00, 26530.00),
(93, 36, 14, NULL, 62, NULL, NULL, 1, 10, 8490.00, 0.00, 84900.00),
(94, 37, 14, NULL, 66, NULL, NULL, 1, 5, 8490.00, 0.00, 42450.00),
(95, 37, 12, NULL, 69, NULL, NULL, 1, 7, 13265.00, 0.00, 92855.00),
(96, 37, 14, NULL, 67, NULL, NULL, 1, 15, 1061.00, 0.00, 15915.00),
(97, 37, 12, NULL, 70, NULL, NULL, 1, 10, 10612.00, 0.00, 106120.00),
(98, 38, 14, '{\"id\": \"14\", \"name\": \"Social Media\", \"captured_at\": \"2025-10-30 10:58:01\"}', 65, NULL, NULL, 1, 4, 5306.00, 0.00, 21224.00),
(99, 39, 14, '{\"id\": \"14\", \"name\": \"Social Media\", \"captured_at\": \"2025-10-30 11:09:16\"}', 64, NULL, NULL, 1, 2, 852.00, 0.00, 1704.00),
(100, 40, 14, '{\"id\": \"14\", \"name\": \"Social Media\", \"captured_at\": \"2025-10-30 12:05:01\"}', 66, NULL, NULL, 1, 5, 8490.00, 0.00, 42450.00),
(101, 41, 12, '{\"id\": \"12\", \"name\": \"Screen Savers\", \"captured_at\": \"2025-10-31 06:36:20\"}', 75, NULL, NULL, 1, 4, 7959.00, 0.00, 31836.00),
(102, 42, 12, '{\"id\": \"12\", \"name\": \"Screen Savers\", \"captured_at\": \"2025-10-31 06:42:12\"}', 75, NULL, NULL, 1, 5, 7959.00, 0.00, 39795.00);

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

DROP TABLE IF EXISTS `security_logs`;
CREATE TABLE IF NOT EXISTS `security_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `event_type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `event_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_created_at` (`created_at`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `input_type` enum('text','number','email','password','textarea','select','checkbox') COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  `options` text COLLATE utf8mb4_unicode_ci COMMENT 'For select/checkbox: value1:label1,value2:label2',
  `sort_order` int DEFAULT '999',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `idx_category` (`category`),
  KEY `idx_key` (`setting_key`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `category`, `setting_key`, `setting_value`, `description`, `input_type`, `options`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Application', 'app_name', 'HSAD CRM', 'Application name displayed in headers', 'text', NULL, 1, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(2, 'Application', 'app_version', '1.0.0', 'Current application version', 'text', NULL, 2, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(3, 'Application', 'maintenance_mode', '0', 'Enable maintenance mode', 'select', '0:Disabled,1:Enabled', 3, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(4, 'Application', 'timezone', 'Asia/Kolkata', 'Default application timezone', 'select', 'Asia/Kolkata:India,America/New_York:New York,Europe/London:London', 4, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(5, 'Email', 'smtp_host', 'smtpout.secureserver.net', 'SMTP server host', 'text', NULL, 1, '2025-09-03 07:53:47', '2025-09-23 17:14:56'),
(6, 'Email', 'smtp_port', '587', 'SMTP server port', 'number', NULL, 2, '2025-09-03 07:53:47', '2025-09-23 17:14:56'),
(7, 'Email', 'smtp_username', 'billing@hsad.co.in', 'SMTP username', 'email', NULL, 3, '2025-09-03 07:53:47', '2025-09-23 17:14:56'),
(8, 'Email', 'smtp_password', 'Sdla@8851', 'SMTP password', 'password', NULL, 4, '2025-09-03 07:53:47', '2025-09-23 17:14:56'),
(9, 'Email', 'from_email', 'billing@hsad.co.in', 'Default From Email', 'email', NULL, 5, '2025-09-03 07:53:47', '2025-09-23 17:14:56'),
(10, 'Email', 'from_name', 'HSAD CRM', 'Default From Name', 'text', NULL, 6, '2025-09-03 07:53:47', '2025-09-23 17:14:56'),
(11, 'Security', 'session_timeout', '3600', 'Session timeout in seconds', 'number', NULL, 1, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(12, 'Security', 'password_min_length', '6', 'Minimum password length', 'number', NULL, 2, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(13, 'Security', 'max_login_attempts', '5', 'Maximum login attempts before lockout', 'number', NULL, 3, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(14, 'Security', 'lockout_duration', '900', 'Account lockout duration in seconds', 'number', NULL, 4, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(15, 'Business', 'company_name', 'HSAD Technologies', 'Company name for documents', 'text', NULL, 1, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(16, 'Business', 'company_address', '', 'Company address', 'textarea', NULL, 2, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(17, 'Business', 'company_phone', '', 'Company phone number', 'text', NULL, 3, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(18, 'Business', 'company_email', '', 'Company email address', 'email', NULL, 4, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(19, 'Business', 'gst_number', '', 'GST registration number', 'text', NULL, 5, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(20, 'Business', 'default_currency', 'INR', 'Default currency code', 'select', 'INR:Indian Rupee,USD:US Dollar,EUR:Euro', 6, '2025-09-03 07:53:47', '2025-09-03 07:53:47'),
(21, 'Email', 'smtp_encryption', 'TLS', 'Smtp encryption', 'text', NULL, 999, '2025-09-03 19:56:17', '2025-09-03 20:03:06'),
(22, 'Email', 'reply_to_email', 'billing@hsad.co.in', 'Reply to email', 'text', NULL, 999, '2025-09-03 19:56:17', '2025-09-03 20:03:06'),
(27, 'Email', 'smtp_crypto', 'TLS', 'SMTP encryption (tls/ssl/none)', 'select', NULL, 999, '2025-09-23 17:14:56', '2025-09-23 17:14:56'),
(28, 'Email', 'smtp_timeout', '60', 'SMTP connection timeout (seconds)', 'number', NULL, 999, '2025-09-23 17:14:56', '2025-09-23 17:14:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('Super Admin','Admin','Viewer') COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `mobile`, `password`, `role`, `status`, `created_at`) VALUES
(1, 'Super Admin', 'admin@example.com', '9999999999', '$2y$10$0iKm11Lj.vG3m6RrirIgzeCe44F3GXlUliRI3ys85QznXpTGe2F0.', 'Super Admin', 1, '2025-06-27 08:11:41'),
(8, 'Anil Mishra', 'anil@hsadindia.com', NULL, '$2y$10$ibSgWCHxbxSENEGe5/teoOMBbqQMeh4.htgNmbYlrfRuHCLYNDdfG', 'Super Admin', 1, '2025-10-28 03:20:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

DROP TABLE IF EXISTS `user_activities`;
CREATE TABLE IF NOT EXISTS `user_activities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`)
) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `client_contacts`
--
ALTER TABLE `client_contacts`
  ADD CONSTRAINT `client_contacts_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
