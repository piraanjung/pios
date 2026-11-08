-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2026 at 08:23 AM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pios`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `acc_user_id` bigint(20) UNSIGNED NOT NULL,
  `deposit` float NOT NULL COMMENT 'ยอดฝาก',
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `annual_trashs`
--

CREATE TABLE `annual_trashs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bin_code` varchar(191) DEFAULT NULL,
  `bin_type` varchar(191) DEFAULT NULL,
  `location_description` varchar(191) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` enum('active','inactive','damaged','removed') NOT NULL DEFAULT 'active',
  `is_active_for_annual_collection` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `annual_trashs`
--

INSERT INTO `annual_trashs` (`id`, `org_id_fk`, `user_id`, `bin_code`, `bin_type`, `location_description`, `latitude`, `longitude`, `status`, `is_active_for_annual_collection`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 'HSG1-B0001', '1', NULL, 13.7549756, 100.5202675, 'active', 1, '2026-10-26 11:58:00', '2026-10-26 12:15:37'),
(2, 1, 7, 'HSG1-B0002', '1', NULL, 13.7549756, 100.5202675, 'active', 1, '2026-10-26 12:01:12', '2026-10-26 12:27:15'),
(3, 1, 1, 'HSG1-B0003', '1', NULL, 13.7513034, 100.5044746, 'active', 1, '2026-10-26 12:03:46', '2026-10-26 12:29:14'),
(4, 1, 1, 'HSG1-B0004', '1', NULL, 13.7527731, 100.5029297, 'active', 1, '2026-10-26 12:04:33', '2026-10-26 12:39:34'),
(5, 1, 6, 'HSG1-B0005', '1', NULL, 13.7643513, 100.4991531, 'active', 1, '2026-10-26 12:47:44', '2026-10-26 12:47:44');

-- --------------------------------------------------------

--
-- Table structure for table `annual_trash_members`
--

CREATE TABLE `annual_trash_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `member_number` int(11) NOT NULL,
  `user_group_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `zone_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subzone_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `recorder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `annual_trash_members`
--

INSERT INTO `annual_trash_members` (`id`, `org_id_fk`, `user_id`, `member_number`, `user_group_id_fk`, `zone_id`, `subzone_id`, `status`, `recorder_id`, `created_at`, `updated_at`) VALUES
(6, 1, 1, 1, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:38', '2026-10-26 09:48:38'),
(7, 1, 2, 2, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(8, 1, 3, 3, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(9, 1, 4, 4, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(10, 1, 5, 5, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(11, 1, 6, 6, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(12, 1, 7, 7, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(13, 1, 8, 8, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(14, 1, 9, 9, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(15, 1, 10, 10, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(16, 1, 11, 11, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(17, 1, 12, 12, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(18, 1, 13, 13, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(19, 1, 14, 14, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(20, 1, 15, 15, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(21, 1, 16, 16, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(22, 1, 17, 17, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(23, 1, 18, 18, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(24, 1, 19, 19, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(25, 1, 20, 20, 1, 1, 1, 'active', 3388, '2026-10-26 09:48:39', '2026-10-26 09:48:39');

-- --------------------------------------------------------

--
-- Table structure for table `annual_trash_payments`
--

CREATE TABLE `annual_trash_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `wbs_id` bigint(20) UNSIGNED NOT NULL,
  `pay_mon` tinyint(3) UNSIGNED NOT NULL,
  `pay_yr` smallint(5) UNSIGNED NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `pay_date` date NOT NULL,
  `notes` varchar(191) DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `annual_trash_payments`
--

INSERT INTO `annual_trash_payments` (`id`, `org_id_fk`, `wbs_id`, `pay_mon`, `pay_yr`, `amount_paid`, `pay_date`, `notes`, `staff_id`, `created_at`, `updated_at`) VALUES
(1, 1, 21, 10, 2026, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(2, 1, 21, 11, 2026, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(3, 1, 21, 12, 2026, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(4, 1, 21, 1, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(5, 1, 21, 2, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(6, 1, 21, 3, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(7, 1, 21, 4, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(8, 1, 21, 5, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(9, 1, 21, 6, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(10, 1, 21, 7, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(11, 1, 21, 8, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(12, 1, 21, 9, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:29:07', '2026-10-26 12:29:07'),
(13, 1, 22, 10, 2026, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:38:20', '2026-10-26 12:38:20'),
(14, 1, 22, 11, 2026, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:38:20', '2026-10-26 12:38:20'),
(15, 1, 22, 12, 2026, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:38:20', '2026-10-26 12:38:20'),
(16, 1, 22, 1, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:38:20', '2026-10-26 12:38:20'),
(17, 1, 22, 2, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:38:20', '2026-10-26 12:38:20'),
(18, 1, 22, 3, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:38:20', '2026-10-26 12:38:20'),
(19, 1, 22, 4, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:38:20', '2026-10-26 12:38:20'),
(20, 1, 23, 3, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:16', '2026-10-26 12:39:16'),
(21, 1, 23, 4, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:16', '2026-10-26 12:39:16'),
(22, 1, 23, 5, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:16', '2026-10-26 12:39:16'),
(23, 1, 23, 6, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:16', '2026-10-26 12:39:16'),
(24, 1, 23, 7, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:16', '2026-10-26 12:39:16'),
(25, 1, 23, 8, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:16', '2026-10-26 12:39:16'),
(26, 1, 23, 9, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:16', '2026-10-26 12:39:16'),
(27, 1, 24, 1, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:45', '2026-10-26 12:39:45'),
(28, 1, 24, 2, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:45', '2026-10-26 12:39:45'),
(29, 1, 24, 3, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:45', '2026-10-26 12:39:45'),
(30, 1, 24, 4, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:45', '2026-10-26 12:39:45'),
(31, 1, 24, 5, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:45', '2026-10-26 12:39:45'),
(32, 1, 24, 6, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:45', '2026-10-26 12:39:45'),
(33, 1, 24, 7, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:45', '2026-10-26 12:39:45'),
(34, 1, 24, 8, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:45', '2026-10-26 12:39:45'),
(35, 1, 24, 9, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:39:45', '2026-10-26 12:39:45'),
(36, 1, 22, 5, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:46:37', '2026-10-26 12:46:37'),
(37, 1, 22, 6, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:46:37', '2026-10-26 12:46:37'),
(38, 1, 22, 7, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:46:37', '2026-10-26 12:46:37'),
(39, 1, 22, 8, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:46:37', '2026-10-26 12:46:37'),
(40, 1, 22, 9, 2027, 20.00, '2026-10-26', NULL, 3388, '2026-10-26 12:46:37', '2026-10-26 12:46:37'),
(41, 1, 25, 10, 2026, 20.00, '2026-10-27', NULL, 3388, '2026-10-26 18:28:36', '2026-10-26 18:28:36'),
(42, 1, 25, 11, 2026, 20.00, '2026-10-27', NULL, 3388, '2026-10-26 18:28:36', '2026-10-26 18:28:36'),
(43, 1, 25, 12, 2026, 20.00, '2026-10-27', NULL, 3388, '2026-10-26 18:35:48', '2026-10-26 18:35:48'),
(44, 1, 25, 1, 2027, 20.00, '2026-10-27', NULL, 3388, '2026-10-26 18:35:48', '2026-10-26 18:35:48'),
(45, 1, 24, 10, 2026, 20.00, '2026-10-27', NULL, 3388, '2026-10-26 18:39:27', '2026-10-26 18:39:27'),
(46, 1, 24, 11, 2026, 20.00, '2026-10-27', NULL, 3388, '2026-10-26 18:39:27', '2026-10-26 18:39:27'),
(47, 1, 24, 12, 2026, 20.00, '2026-10-27', NULL, 3388, '2026-10-26 18:39:27', '2026-10-26 18:39:27');

-- --------------------------------------------------------

--
-- Table structure for table `annual_trash_payrate_permonth`
--

CREATE TABLE `annual_trash_payrate_permonth` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `kp_usergroup_idfk` bigint(20) UNSIGNED NOT NULL,
  `budgetyear_idfk` bigint(20) UNSIGNED NOT NULL,
  `payrate_permonth` float NOT NULL,
  `vat` float NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `annual_trash_payrate_permonth`
--

INSERT INTO `annual_trash_payrate_permonth` (`id`, `org_id_fk`, `kp_usergroup_idfk`, `budgetyear_idfk`, `payrate_permonth`, `vat`, `status`, `deleted`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 20, 0, 'active', 0, '2026-10-18 16:45:58', '2026-10-18 16:45:58'),
(2, 1, 3, 1, 15, 0, 'active', 0, '2026-10-18 16:48:42', '2026-10-18 16:48:42');

-- --------------------------------------------------------

--
-- Table structure for table `annual_trash_subscriptions`
--

CREATE TABLE `annual_trash_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `fiscal_year` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `waste_bin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payrate_permonth_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `annual_fee` decimal(10,2) NOT NULL,
  `month_fee` decimal(10,2) NOT NULL,
  `total_paid_amt` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `annual_trash_subscriptions`
--

INSERT INTO `annual_trash_subscriptions` (`id`, `org_id_fk`, `fiscal_year`, `user_id`, `waste_bin_id`, `payrate_permonth_id_fk`, `annual_fee`, `month_fee`, `total_paid_amt`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2570', 1, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(2, 1, '2570', 2, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(3, 1, '2570', 3, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(4, 1, '2570', 4, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(5, 1, '2570', 5, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(6, 1, '2570', 6, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(7, 1, '2570', 7, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(8, 1, '2570', 8, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(9, 1, '2570', 9, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(10, 1, '2570', 10, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(11, 1, '2570', 11, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(12, 1, '2570', 12, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(13, 1, '2570', 13, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(14, 1, '2570', 14, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(15, 1, '2570', 15, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(16, 1, '2570', 16, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(17, 1, '2570', 17, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(18, 1, '2570', 18, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(19, 1, '2570', 19, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(20, 1, '2570', 20, NULL, 2, 180.00, 15.00, 0.00, 'pending', '2026-10-26 09:48:39', '2026-10-26 09:48:39'),
(21, 1, '2570', 7, 1, 1, 240.00, 20.00, 240.00, 'paid', '2026-10-26 12:15:37', '2026-10-26 12:29:07'),
(22, 1, '2570', 7, 2, 1, 240.00, 20.00, 240.00, 'paid', '2026-10-26 12:27:15', '2026-10-26 12:46:37'),
(23, 1, '2570', 1, 3, 1, 240.00, 20.00, 140.00, 'partially_paid', '2026-10-26 12:29:14', '2026-10-26 12:39:16'),
(24, 1, '2570', 1, 4, 1, 240.00, 20.00, 240.00, 'paid', '2026-10-26 12:39:34', '2026-10-26 18:39:27'),
(25, 1, '2570', 6, 5, 1, 240.00, 20.00, 80.00, 'partially_paid', '2026-10-26 17:58:57', '2026-10-26 18:35:48');

-- --------------------------------------------------------

--
-- Table structure for table `approval_workflows`
--

CREATE TABLE `approval_workflows` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `approval_workflows`
--

INSERT INTO `approval_workflows` (`id`, `org_id_fk`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'คลังพัสดุงานประปา', '', 1, '2026-10-19 07:50:20', '2026-10-19 07:50:20');

-- --------------------------------------------------------

--
-- Table structure for table `approval_workflow_steps`
--

CREATE TABLE `approval_workflow_steps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `workflow_id` bigint(20) UNSIGNED NOT NULL,
  `step_order` int(11) NOT NULL,
  `role_name` varchar(191) DEFAULT NULL,
  `specific_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `approval_workflow_steps`
--

INSERT INTO `approval_workflow_steps` (`id`, `workflow_id`, `step_order`, `role_name`, `specific_user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Tabwater Header', NULL, '2026-10-19 07:50:20', '2026-10-19 07:50:20');

-- --------------------------------------------------------

--
-- Table structure for table `budget_year`
--

CREATE TABLE `budget_year` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `budgetyear_name` varchar(191) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `status` enum('inactive','active') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budget_year`
--

INSERT INTO `budget_year` (`id`, `org_id_fk`, `budgetyear_name`, `startdate`, `enddate`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2570', '2026-10-01', '2027-09-30', 'active', '2026-10-27 21:22:11', '2026-10-27 21:22:11'),
(2, 4, '2570', '2026-10-01', '2027-09-30', 'active', '2026-10-28 18:25:55', '2026-10-28 18:25:55');

-- --------------------------------------------------------

--
-- Table structure for table `compost_bins`
--

CREATE TABLE `compost_bins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bin_code` varchar(191) NOT NULL,
  `bin_size` varchar(191) DEFAULT NULL,
  `registration_date` date NOT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cp_sensor_data`
--

CREATE TABLE `cp_sensor_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `temperature` decimal(8,2) NOT NULL,
  `humidity` decimal(8,2) NOT NULL,
  `methane_gas` decimal(8,2) NOT NULL,
  `weight` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `district_name` varchar(191) NOT NULL,
  `province_id` bigint(20) UNSIGNED NOT NULL,
  `go_id` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `district_name`, `province_id`, `go_id`, `created_at`, `updated_at`) VALUES
(3401, 'เมืองอุบลราชธานี', 34, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3405, 'เขมราฐ', 34, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3410, 'บุณฑริก', 34, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3501, 'เมืองยโสธร', 35, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3502, 'ทรายมูล', 35, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3503, 'กุดชุม', 35, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3504, 'คำเขื่อนแก้ว', 35, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3505, 'ป่าติ้ว', 35, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3506, 'มหาชนะชัย', 35, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3507, 'ค้อวัง', 35, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3508, 'เลิงนกทา', 35, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(3509, 'ไทยเจริญ', 35, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(4701, 'เมืองสกลนคร', 47, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(4714, 'เต่างอย', 47, 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36');

-- --------------------------------------------------------

--
-- Table structure for table `emission_factors`
--

CREATE TABLE `emission_factors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `material_name` varchar(191) NOT NULL,
  `unit` varchar(191) NOT NULL DEFAULT 'kg',
  `ef_value` decimal(10,5) NOT NULL,
  `source` varchar(191) DEFAULT NULL,
  `example` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `emission_factors`
--

INSERT INTO `emission_factors` (`id`, `material_name`, `unit`, `ef_value`, `source`, `example`, `created_at`, `updated_at`) VALUES
(1, 'เหล็ก', 'kgCO2e/kg', 3.23140, 'TGO', NULL, '2026-06-23 09:04:59', '2026-06-23 09:04:59'),
(6, 'พลาสติกรวม', 'kgCO2e/kg', 3.23140, 'TGO', NULL, '2026-06-23 09:04:59', '2026-06-23 09:04:59');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodwaste_bins`
--

CREATE TABLE `foodwaste_bins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bin_code` varchar(191) NOT NULL,
  `bin_type` varchar(191) NOT NULL,
  `location_description` varchar(191) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `status` enum('active','inactive','damaged','removed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `u_pref_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `bin_code_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `iotbox_id_fk` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodwaste_bin_stocks`
--

CREATE TABLE `foodwaste_bin_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bin_code` varchar(191) DEFAULT NULL,
  `bin_type` enum('compost','annual') NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `status` enum('active','pending','damaged','removed') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foodwaste_bin_stocks`
--

INSERT INTO `foodwaste_bin_stocks` (`id`, `bin_code`, `bin_type`, `org_id_fk`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'TWS-KUCSC0001', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(2, 'TWS-KUCSC0002', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(3, 'TWS-KUCSC0003', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(4, 'TWS-KUCSC0004', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(5, 'TWS-KUCSC0005', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(6, 'TWS-KUCSC0006', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(7, 'TWS-KUCSC0007', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(8, 'TWS-KUCSC0008', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(9, 'TWS-KUCSC0009', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(10, 'TWS-KUCSC0010', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(11, 'TWS-KUCSC0011', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(12, 'TWS-KUCSC0012', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(13, 'TWS-KUCSC0013', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(14, 'TWS-KUCSC0014', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(15, 'TWS-KUCSC0015', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(16, 'TWS-KUCSC0016', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(17, 'TWS-KUCSC0017', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(18, 'TWS-KUCSC0018', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(19, 'TWS-KUCSC0019', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(20, 'TWS-KUCSC0020', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(21, 'TWS-KUCSC0021', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(22, 'TWS-KUCSC0022', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(23, 'TWS-KUCSC0023', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(24, 'TWS-KUCSC0024', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(25, 'TWS-KUCSC0025', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(26, 'TWS-KUCSC0026', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(27, 'TWS-KUCSC0027', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(28, 'TWS-KUCSC0028', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(29, 'TWS-KUCSC0029', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(30, 'TWS-KUCSC0030', 'compost', 2, 's', 'active', '2026-07-15 04:58:25', '2026-07-15 04:58:25'),
(31, 'HSG1-B0001', 'annual', 1, NULL, 'pending', '2026-10-26 11:40:46', '2026-10-26 11:40:46'),
(32, 'HSG1-B0002', 'annual', 1, NULL, 'pending', '2026-10-26 11:40:46', '2026-10-26 11:40:46'),
(33, 'HSG1-B0003', 'annual', 1, NULL, 'pending', '2026-10-26 11:40:46', '2026-10-26 11:40:46'),
(34, 'HSG1-B0004', 'annual', 1, NULL, 'pending', '2026-10-26 11:40:46', '2026-10-26 11:40:46'),
(35, 'HSG1-B0005', 'annual', 1, NULL, 'pending', '2026-10-26 11:40:46', '2026-10-26 11:40:46'),
(36, 'HSG1-B0006', 'annual', 1, NULL, 'pending', '2026-10-26 11:40:46', '2026-10-26 11:40:46'),
(37, 'HSG1-B0007', 'annual', 1, NULL, 'pending', '2026-10-26 11:40:46', '2026-10-26 11:40:46'),
(38, 'HSG1-B0008', 'annual', 1, NULL, 'pending', '2026-10-26 11:40:46', '2026-10-26 11:40:46'),
(39, 'HSG1-B0009', 'annual', 1, NULL, 'pending', '2026-10-26 11:40:46', '2026-10-26 11:40:46'),
(40, 'HSG1-B0010', 'annual', 1, NULL, 'pending', '2026-10-26 11:40:46', '2026-10-26 11:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `foodwaste_compost_batches`
--

CREATE TABLE `foodwaste_compost_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `batch_code` varchar(191) NOT NULL,
  `start_date` date NOT NULL,
  `closed_date` date DEFAULT NULL,
  `status` enum('filling','composting','ready') NOT NULL DEFAULT 'filling',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foodwaste_compost_batches`
--

INSERT INTO `foodwaste_compost_batches` (`id`, `user_id`, `batch_code`, `start_date`, `closed_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 3895, 'LOT-2607-700', '2026-07-15', NULL, 'filling', '2026-07-14 23:25:17', '2026-07-14 23:25:17'),
(2, 3896, 'LOT-2607-771', '2026-07-15', NULL, 'filling', '2026-07-15 00:05:13', '2026-07-15 00:05:13'),
(3, 3897, 'LOT-2607-941', '2026-07-16', NULL, 'filling', '2026-07-16 08:40:54', '2026-07-16 08:40:54'),
(4, 3892, 'LOT-2607-320', '2026-07-17', NULL, 'filling', '2026-07-17 02:50:46', '2026-07-17 02:50:46'),
(5, 3894, 'LOT-2607-116', '2026-07-17', NULL, 'filling', '2026-07-17 05:33:28', '2026-07-17 05:33:28'),
(6, 3898, 'LOT-2607-261', '2026-07-17', NULL, 'filling', '2026-07-17 05:52:51', '2026-07-17 05:52:51'),
(7, 3901, 'LOT-2607-243', '2026-07-17', NULL, 'filling', '2026-07-17 05:53:10', '2026-07-17 05:53:10'),
(8, 3914, 'LOT-2607-132', '2026-07-17', NULL, 'filling', '2026-07-17 05:53:14', '2026-07-17 05:53:14'),
(9, 3905, 'LOT-2607-322', '2026-07-17', NULL, 'filling', '2026-07-17 05:53:33', '2026-07-17 05:53:33'),
(10, 3908, 'LOT-2607-438', '2026-07-17', NULL, 'filling', '2026-07-17 05:53:51', '2026-07-17 05:53:51'),
(11, 3900, 'LOT-2607-483', '2026-07-17', NULL, 'filling', '2026-07-17 05:53:51', '2026-07-17 05:53:51'),
(12, 3913, 'LOT-2607-229', '2026-07-17', NULL, 'filling', '2026-07-17 05:53:53', '2026-07-17 05:53:53'),
(13, 3906, 'LOT-2607-270', '2026-07-17', NULL, 'filling', '2026-07-17 05:54:26', '2026-07-17 05:54:26'),
(14, 3910, 'LOT-2607-606', '2026-07-17', NULL, 'filling', '2026-07-17 05:54:30', '2026-07-17 05:54:30'),
(15, 3912, 'LOT-2607-666', '2026-07-17', NULL, 'filling', '2026-07-17 05:55:00', '2026-07-17 05:55:00'),
(16, 3902, 'LOT-2607-583', '2026-07-17', NULL, 'filling', '2026-07-17 05:55:11', '2026-07-17 05:55:11'),
(17, 3903, 'LOT-2607-637', '2026-07-17', NULL, 'filling', '2026-07-17 05:55:14', '2026-07-17 05:55:14'),
(18, 3904, 'LOT-2607-326', '2026-07-17', NULL, 'filling', '2026-07-17 05:55:19', '2026-07-17 05:55:19'),
(19, 3911, 'LOT-2607-445', '2026-07-17', NULL, 'filling', '2026-07-17 05:55:42', '2026-07-17 05:55:42'),
(20, 3918, 'LOT-2607-987', '2026-07-17', NULL, 'filling', '2026-07-17 05:56:22', '2026-07-17 05:56:22'),
(21, 3916, 'LOT-2607-421', '2026-07-17', NULL, 'filling', '2026-07-17 05:57:16', '2026-07-17 05:57:16'),
(22, 3909, 'LOT-2607-979', '2026-07-17', NULL, 'filling', '2026-07-17 05:58:56', '2026-07-17 05:58:56'),
(23, 3921, 'LOT-2607-679', '2026-07-17', NULL, 'filling', '2026-07-17 06:36:26', '2026-07-17 06:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `foodwaste_issue_reports`
--

CREATE TABLE `foodwaste_issue_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `assigned_staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `batch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `issue_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `status` enum('pending','in_progress','resolved','cancel') NOT NULL DEFAULT 'pending',
  `admin_note` varchar(191) DEFAULT NULL,
  `staff_comment` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodwaste_issue_types`
--

CREATE TABLE `foodwaste_issue_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `color` varchar(9) NOT NULL DEFAULT '#dc2626'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foodwaste_issue_types`
--

INSERT INTO `foodwaste_issue_types` (`id`, `org_id_fk`, `name`, `is_active`, `created_at`, `updated_at`, `color`) VALUES
(5, 3, 'ถังมีกลิ่นเหม็นเน่า', 1, NULL, NULL, '#dc2626'),
(6, 3, 'ถังหมักไม่ร้อน', 1, NULL, NULL, '#f97316'),
(7, 3, 'ถังหมักมีหนอน', 1, NULL, NULL, '#eab308'),
(8, 3, 'ถังแฉะ มีน้ำเยิ้ม', 1, NULL, NULL, '#8b5cf6'),
(9, 3, 'อื่นๆ', 1, NULL, NULL, '#0ea5e9');

-- --------------------------------------------------------

--
-- Table structure for table `foodwaste_reward_settings`
--

CREATE TABLE `foodwaste_reward_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `unit` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodwaste_transactions`
--

CREATE TABLE `foodwaste_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fw_pref_id_fk` bigint(20) UNSIGNED NOT NULL,
  `waste_log_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_type` varchar(191) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `note` varchar(191) DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodwaste_user_bin_iotbox_matchings`
--

CREATE TABLE `foodwaste_user_bin_iotbox_matchings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fw_user_id_fk` bigint(20) UNSIGNED NOT NULL,
  `bin_id_fk` bigint(20) UNSIGNED NOT NULL,
  `iotbox_id_fk` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodwaste_user_preferences`
--

CREATE TABLE `foodwaste_user_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodwaste_waste_logs`
--

CREATE TABLE `foodwaste_waste_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `batch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `weight_kg` decimal(8,2) NOT NULL,
  `photo_path` varchar(191) DEFAULT NULL,
  `is_mixed` tinyint(1) NOT NULL DEFAULT 0,
  `moisture` enum('dry','good','wet') NOT NULL,
  `temperature_feel` varchar(191) DEFAULT NULL,
  `carbon_saved_kg` decimal(8,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estimated_weight` decimal(8,2) NOT NULL,
  `actual_moisture_avg` decimal(5,2) DEFAULT NULL,
  `verified_dry_weight` decimal(8,2) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foodwaste_waste_logs`
--

INSERT INTO `foodwaste_waste_logs` (`id`, `batch_id`, `user_id`, `weight_kg`, `photo_path`, `is_mixed`, `moisture`, `temperature_feel`, `carbon_saved_kg`, `created_at`, `updated_at`, `estimated_weight`, `actual_moisture_avg`, `verified_dry_weight`, `is_verified`) VALUES
(1, 4, 3892, 0.20, 'food_wastes/1784256646_acda0361-8944-4dfc-b8c4-872b3c69d469.jpeg', 1, 'good', NULL, 136.0000, '2026-07-17 02:50:46', '2026-07-17 02:50:46', 0.20, NULL, NULL, 0),
(2, 1, 3895, 0.20, 'food_wastes/1784266315_liff_6896609304316544295_camera.jpg', 1, 'good', NULL, 136.0000, '2026-07-17 05:31:57', '2026-07-17 05:31:57', 0.20, NULL, NULL, 0),
(3, 5, 3894, 0.05, 'food_wastes/1784266406_liff_477413800999903820_camera.jpg', 1, 'good', NULL, 34.0000, '2026-07-17 05:33:28', '2026-07-17 05:33:28', 0.05, NULL, NULL, 0),
(4, 2, 3896, 0.05, 'food_wastes/1784267080_liff_6224318725288136679_camera.jpg', 1, 'good', NULL, 34.0000, '2026-07-17 05:44:41', '2026-07-17 05:44:41', 0.05, NULL, NULL, 0),
(5, 6, 3898, 0.20, 'food_wastes/1784267570_liff_7193980393704848305_camera.jpg', 1, 'good', NULL, 136.0000, '2026-07-17 05:52:51', '2026-07-17 05:52:51', 0.20, NULL, NULL, 0),
(6, 7, 3901, 0.10, 'food_wastes/1784267589_liff_6351228725829904992_camera.jpg', 0, 'good', NULL, 68.0000, '2026-07-17 05:53:10', '2026-07-17 05:53:10', 0.10, NULL, NULL, 0),
(7, 8, 3914, 0.20, 'food_wastes/1784267594_liff_8775755343868979286_camera.jpg', 1, 'good', NULL, 136.0000, '2026-07-17 05:53:14', '2026-07-17 05:53:14', 0.20, NULL, NULL, 0),
(8, 9, 3905, 0.10, 'food_wastes/1784267612_liff_3575939470943889062_camera.jpg', 0, 'good', NULL, 68.0000, '2026-07-17 05:53:33', '2026-07-17 05:53:33', 0.10, NULL, NULL, 0),
(9, 10, 3908, 0.20, 'food_wastes/1784267630_liff_8261614946496812977_camera.jpg', 1, 'good', NULL, 136.0000, '2026-07-17 05:53:51', '2026-07-17 05:53:51', 0.20, NULL, NULL, 0),
(10, 11, 3900, 0.20, 'food_wastes/1784267630_image.jpg', 1, 'good', NULL, 136.0000, '2026-07-17 05:53:51', '2026-07-17 05:53:51', 0.20, NULL, NULL, 0),
(11, 12, 3913, 0.10, 'food_wastes/1784267632_liff_1112512253156216808_camera.jpg', 0, 'good', NULL, 68.0000, '2026-07-17 05:53:53', '2026-07-17 05:53:53', 0.10, NULL, NULL, 0),
(12, 13, 3906, 0.20, 'food_wastes/1784267665_1000012338.jpg', 1, 'good', NULL, 136.0000, '2026-07-17 05:54:26', '2026-07-17 05:54:26', 0.20, NULL, NULL, 0),
(13, 2, 3896, 0.03, 'food_wastes/1784267666_liff_4040334269159764362_camera.jpg', 1, 'wet', NULL, 20.4000, '2026-07-17 05:54:27', '2026-07-17 05:54:27', 0.03, NULL, NULL, 0),
(14, 14, 3910, 0.20, 'food_wastes/1784267668_liff_8617543867382770920_camera.jpg', 1, 'good', NULL, 136.0000, '2026-07-17 05:54:30', '2026-07-17 05:54:30', 0.20, NULL, NULL, 0),
(15, 15, 3912, 0.10, 'food_wastes/1784267699_liff_6487976928584635216_camera.jpg', 0, 'good', NULL, 68.0000, '2026-07-17 05:55:00', '2026-07-17 05:55:00', 0.10, NULL, NULL, 0),
(16, 16, 3902, 0.15, 'food_wastes/1784267709_liff_3322342423305729800_camera.jpg', 0, 'good', NULL, 102.0000, '2026-07-17 05:55:11', '2026-07-17 05:55:11', 0.15, NULL, NULL, 0),
(17, 17, 3903, 0.20, 'food_wastes/1784267713_liff_4996225086785561471_camera.jpg', 0, 'good', NULL, 136.0000, '2026-07-17 05:55:14', '2026-07-17 05:55:14', 0.20, NULL, NULL, 0),
(18, 18, 3904, 0.20, 'food_wastes/1784267717_liff_3709731178128833825_camera.jpg', 0, 'good', NULL, 136.0000, '2026-07-17 05:55:19', '2026-07-17 05:55:19', 0.20, NULL, NULL, 0),
(19, 19, 3911, 0.13, 'food_wastes/1784267741_liff_3227373067411859428_camera.jpg', 0, 'good', NULL, 88.4000, '2026-07-17 05:55:42', '2026-07-17 05:55:42', 0.13, NULL, NULL, 0),
(20, 20, 3918, 0.20, 'food_wastes/1784267781_liff_7629863647494610064_camera.jpg', 1, 'good', NULL, 136.0000, '2026-07-17 05:56:22', '2026-07-17 05:56:22', 0.20, NULL, NULL, 0),
(21, 21, 3916, 0.10, 'food_wastes/1784267835_liff_3278401354923858129_camera.jpg', 0, 'good', NULL, 68.0000, '2026-07-17 05:57:16', '2026-07-17 05:57:16', 0.10, NULL, NULL, 0),
(22, 22, 3909, 0.20, 'food_wastes/1784267935_liff_3539202053547154589_camera.jpg', 0, 'good', NULL, 136.0000, '2026-07-17 05:58:56', '2026-07-17 05:58:56', 0.20, NULL, NULL, 0),
(23, 23, 3921, 0.50, 'food_wastes/1784270185_image.jpg', 0, 'good', NULL, 340.0000, '2026-07-17 06:36:26', '2026-07-17 06:36:26', 0.50, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `foodwast_iotboxes`
--

CREATE TABLE `foodwast_iotboxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iotbox_code` varchar(100) NOT NULL,
  `temp_humid_sensor` enum('0','1') NOT NULL,
  `gas_sensor` enum('0','1') NOT NULL,
  `weight_sensor` enum('0','1') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foodwast_meal_logs`
--

CREATE TABLE `foodwast_meal_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `photo_path` varchar(191) NOT NULL,
  `total_calories` int(11) NOT NULL DEFAULT 0,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foodwast_meal_logs`
--

INSERT INTO `foodwast_meal_logs` (`id`, `user_id`, `photo_path`, `total_calories`, `status`, `created_at`, `updated_at`) VALUES
(1, 3895, 'meals/1784095885_Screenshot 2569-07-15 at 12.55.17.png', 730, 'binned', '2026-07-14 23:11:51', '2026-07-14 23:27:21');

-- --------------------------------------------------------

--
-- Table structure for table `food_waste_accounts`
--

CREATE TABLE `food_waste_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `points_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `money_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_weight_kg` double NOT NULL DEFAULT 0,
  `last_contributed_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_period`
--

CREATE TABLE `invoice_period` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `inv_p_name` varchar(191) NOT NULL,
  `budgetyear_id` bigint(20) UNSIGNED NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `old_inv_p_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_period`
--

INSERT INTO `invoice_period` (`id`, `org_id_fk`, `inv_p_name`, `budgetyear_id`, `startdate`, `enddate`, `status`, `created_at`, `updated_at`, `old_inv_p_id`) VALUES
(1, 1, 'ตุลาคม 2569', 1, '2026-10-01', '2026-10-31', 'active', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(2, 1, 'พฤศจิกายน 2569', 1, '2026-11-01', '2026-11-30', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(3, 1, 'ธันวาคม 2569', 1, '2026-12-01', '2026-12-31', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(4, 1, 'มกราคม 2570', 1, '2027-01-01', '2027-01-31', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(5, 1, 'กุมภาพันธ์ 2570', 1, '2027-02-01', '2027-02-28', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(6, 1, 'มีนาคม 2570', 1, '2027-03-01', '2027-03-31', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(7, 1, 'เมษายน 2570', 1, '2027-04-01', '2027-04-30', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(8, 1, 'พฤษภาคม 2570', 1, '2027-05-01', '2027-05-31', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(9, 1, 'มิถุนายน 2570', 1, '2027-06-01', '2027-06-30', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(10, 1, 'กรกฎาคม 2570', 1, '2027-07-01', '2027-07-31', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(11, 1, 'สิงหาคม 2570', 1, '2027-08-01', '2027-08-31', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(12, 1, 'กันยายน 2570', 1, '2027-09-01', '2027-09-30', 'inactive', '2026-10-27 21:22:11', '2026-10-27 21:22:11', 0),
(13, 4, 'ตุลาคม 2569', 2, '2026-10-01', '2026-10-31', 'active', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(14, 4, 'พฤศจิกายน 2569', 2, '2026-11-01', '2026-11-30', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(15, 4, 'ธันวาคม 2569', 2, '2026-12-01', '2026-12-31', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(16, 4, 'มกราคม 2570', 2, '2027-01-01', '2027-01-31', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(17, 4, 'กุมภาพันธ์ 2570', 2, '2027-02-01', '2027-02-28', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(18, 4, 'มีนาคม 2570', 2, '2027-03-01', '2027-03-31', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(19, 4, 'เมษายน 2570', 2, '2027-04-01', '2027-04-30', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(20, 4, 'พฤษภาคม 2570', 2, '2027-05-01', '2027-05-31', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(21, 4, 'มิถุนายน 2570', 2, '2027-06-01', '2027-06-30', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(22, 4, 'กรกฎาคม 2570', 2, '2027-07-01', '2027-07-31', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(23, 4, 'สิงหาคม 2570', 2, '2027-08-01', '2027-08-31', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0),
(24, 4, 'กันยายน 2570', 2, '2027-09-01', '2027-09-30', 'inactive', '2026-10-28 18:25:55', '2026-10-28 18:25:55', 0);

-- --------------------------------------------------------

--
-- Table structure for table `inv_categories`
--

CREATE TABLE `inv_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `approval_workflow_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inv_categories`
--

INSERT INTO `inv_categories` (`id`, `org_id_fk`, `name`, `approval_workflow_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'วัสดุวิทยาศาสตร์', 1, '2026-10-19 08:00:28', '2026-10-19 08:00:28'),
(2, 1, 'วัสดุประปา', 1, '2026-10-19 08:00:28', '2026-10-19 08:00:28'),
(3, 1, 'วัสดุไฟฟ้า(งานประปา)', 1, '2026-10-19 08:00:28', '2026-10-19 08:00:28'),
(4, 1, 'วัสดุไฟฟ้า(งานไฟฟ้า)', 1, '2026-10-19 08:00:28', '2026-10-19 08:00:28');

-- --------------------------------------------------------

--
-- Table structure for table `inv_hazard_levels`
--

CREATE TABLE `inv_hazard_levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inv_items`
--

CREATE TABLE `inv_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `inv_category_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(191) DEFAULT NULL,
  `min_stock` int(11) NOT NULL DEFAULT 0,
  `item_type` varchar(191) NOT NULL DEFAULT 'GENERAL',
  `image_path` varchar(191) DEFAULT NULL,
  `is_chemical` tinyint(1) NOT NULL DEFAULT 0,
  `return_required` tinyint(1) NOT NULL DEFAULT 0,
  `expire_date` date DEFAULT NULL,
  `cas_number` varchar(191) DEFAULT NULL,
  `chemical_formula` varchar(191) DEFAULT NULL,
  `grade` varchar(191) DEFAULT NULL,
  `storage_condition` varchar(191) DEFAULT NULL,
  `msds_link` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inv_items`
--

INSERT INTO `inv_items` (`id`, `org_id_fk`, `inv_category_id_fk`, `name`, `code`, `description`, `unit`, `min_stock`, `item_type`, `image_path`, `is_chemical`, `return_required`, `expire_date`, `cas_number`, `chemical_formula`, `grade`, `storage_condition`, `msds_link`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'คลอรีนผงเข้มข้น 70% ขนาด 40 กก.', 'SC-001', NULL, 'ถัง', 6, 'GENERAL', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 21:21:33'),
(2, 1, 1, 'สารส้มก้อนใส ขนาด 30 กก.', 'SC-002', NULL, '2', 2, 'GENERAL', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(3, 1, 1, 'สารส้มก้อนขุ่น ขนาด 30 กก.', 'SC-003', NULL, '2', 2, 'GENERAL', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(4, 1, 1, 'สารส้ม ชนิดผง ขนาด 30 กก.', 'SC-004', NULL, '2', 2, 'GENERAL', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(5, 1, 1, 'สารโพลีเมอร์สำหรับช่วยตกตะกอน (polymer) ขนาด 25 กก.', 'SC-005', NULL, '2', 2, 'GENERAL', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(6, 1, 1, 'ปูนขาว ขนาด 25 กก.', 'SC-006', NULL, '2', 2, 'GENERAL', NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(7, 1, 2, 'ท่อ PVC ชั้น 8.5 ขนาด 1/2\"', 'PI-001', NULL, '3', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(8, 1, 2, 'ท่อ PVC ชั้น 8.5 ขนาด 3/4\"', 'PI-002', NULL, '3', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(9, 1, 2, 'ท่อ PVC ชั้น 8.5 ขนาด 1\"', 'PI-003', NULL, '3', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(10, 1, 2, 'ท่อ PVC ชั้น 8.5 ขนาด 1 1/2\"', 'PI-004', NULL, '3', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(11, 1, 2, 'ท่อ PVC ชั้น 8.5 ขนาด 2\"', 'PI-005', NULL, '3', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(12, 1, 2, 'ท่อ PVC ชั้น 8.5 ขนาด 2 1/2\"', 'PI-006', NULL, '3', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(13, 1, 2, 'ท่อ PVC ชั้น 8.5 ขนาด 3\"', 'PI-007', NULL, '3', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(14, 1, 2, 'ท่อ PVC ชั้น 8.5 ขนาด 4\"', 'PI-008', NULL, '3', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(15, 1, 2, 'ท่อ PVC ชั้น 8.5 ขนาด 6\"', 'PI-009', NULL, '3', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(16, 1, 2, 'ข้อต่อตรง PVC ชั้น 13.5 ขนาด 1/2\"', 'PI-010', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(17, 1, 2, 'ข้อต่อตรง PVC ชั้น 13.5 ขนาด 3/4\"', 'PI-011', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(18, 1, 2, 'ข้อต่อตรง PVC ชั้น 13.5 ขนาด 1\"', 'PI-012', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(19, 1, 2, 'ข้อต่อตรง PVC ชั้น 13.5 ขนาด 1 1/2\"', 'PI-013', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(20, 1, 2, 'ข้อต่อตรง PVC ชั้น 13.5 ขนาด 2\"', 'PI-014', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(21, 1, 2, 'ข้อต่อตรง PVC ชั้น 13.5 ขนาด 2 1/2\"', 'PI-015', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(22, 1, 2, 'ข้อต่อตรง PVC ชั้น 13.5 ขนาด 3\"', 'PI-016', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(23, 1, 2, 'ข้อต่อตรง PVC ชั้น 13.5 ขนาด 4\"', 'PI-017', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(24, 1, 2, 'ข้อต่อตรง PVC ชั้น 13.5 ขนาด 6\"', 'PI-018', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(25, 1, 2, 'ข้องอ 90 องศา PVC ชั้น 13.5 ขนาด 1/2\"', 'PI-019', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(26, 1, 2, 'ข้องอ 90 องศา PVC ชั้น 13.5 ขนาด 3/4\"', 'PI-020', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(27, 1, 2, 'ข้องอ 90 องศา PVC ชั้น 13.5 ขนาด 1\"', 'PI-021', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(28, 1, 2, 'ข้องอ 90 องศา PVC ชั้น 13.5 ขนาด 1 1/2\"', 'PI-022', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(29, 1, 2, 'ข้องอ 90 องศา PVC ชั้น 13.5 ขนาด 2\"', 'PI-023', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(30, 1, 2, 'ข้องอ 90 องศา PVC ชั้น 13.5 ขนาด 2 1/2\"', 'PI-024', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(31, 1, 2, 'ข้องอ 90 องศา PVC ชั้น 13.5 ขนาด 3\"', 'PI-025', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(32, 1, 2, 'ข้องอ 90 องศา PVC ชั้น 13.5 ขนาด 4\"', 'PI-026', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(33, 1, 2, 'ข้องอ 90 องศา PVC ชั้น 13.5 ขนาด 6\"', 'PI-027', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(34, 1, 2, 'ข้อต่อสามทาง PVC ชั้น 13.5 ขนาด 1/2\"', 'PI-028', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(35, 1, 2, 'ข้อต่อสามทาง PVC ชั้น 13.5 ขนาด 3/4\"', 'PI-029', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(36, 1, 2, 'ข้อต่อสามทาง PVC ชั้น 13.5 ขนาด 1\"', 'PI-030', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(37, 1, 2, 'ข้อต่อสามทาง PVC ชั้น 13.5 ขนาด 1 1/2\"', 'PI-031', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(38, 1, 2, 'ข้อต่อสามทาง PVC ชั้น 13.5 ขนาด 2\"', 'PI-032', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(39, 1, 2, 'ข้อต่อสามทาง PVC ชั้น 13.5 ขนาด 2 1/2\"', 'PI-033', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(40, 1, 2, 'ข้อต่อสามทาง PVC ชั้น 13.5 ขนาด 3\"', 'PI-034', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(41, 1, 2, 'ข้อต่อสามทาง PVC ชั้น 13.5 ขนาด 4\"', 'PI-035', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(42, 1, 2, 'ข้อต่อสามทาง PVC ชั้น 13.5 ขนาด 6\"', 'PI-036', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(43, 1, 2, 'ข้อต่อเกลียวใน PVC ชั้น 13.5 ขนาด 3/4\"', 'PI-037', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(44, 1, 2, 'ข้อต่อเกลียวใน PVC ชั้น 13.5 ขนาด 1\"', 'PI-038', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(45, 1, 2, 'ข้อต่อเกลียวใน PVC ชั้น 13.5 ขนาด 1 1/2\"', 'PI-039', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(46, 1, 2, 'ข้อต่อเกลียวใน PVC ชั้น 13.5 ขนาด 2\"', 'PI-040', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(47, 1, 2, 'ข้อต่อเกลียวใน PVC ชั้น 13.5 ขนาด 2 1/2\"', 'PI-041', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(48, 1, 2, 'ข้อต่อเกลียวใน PVC ชั้น 13.5 ขนาด 3\"', 'PI-042', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(49, 1, 2, 'ข้อต่อเกลียวใน PVC ชั้น 13.5 ขนาด 4\"', 'PI-043', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(50, 1, 2, 'ข้อต่อเกลียวใน PVC ชั้น 13.5 ขนาด 6\"', 'PI-044', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(51, 1, 2, 'ข้อต่อเกลียวนอก PVC ชั้น 13.5 ขนาด 3/4\"', 'PI-045', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(52, 1, 2, 'ข้อต่อเกลียวนอก PVC ชั้น 13.5 ขนาด 1\"', 'PI-046', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(53, 1, 2, 'ข้อต่อเกลียวนอก PVC ชั้น 13.5 ขนาด 1 1/2\"', 'PI-047', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(54, 1, 2, 'ข้อต่อเกลียวนอก PVC ชั้น 13.5 ขนาด 2\"', 'PI-048', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(55, 1, 2, 'ข้อต่อเกลียวนอก PVC ชั้น 13.5 ขนาด 2 1/2\"', 'PI-049', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(56, 1, 2, 'ข้อต่อเกลียวนอก PVC ชั้น 13.5 ขนาด 3\"', 'PI-050', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(57, 1, 2, 'ข้อต่อเกลียวนอก PVC ชั้น 13.5 ขนาด 4\"', 'PI-051', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(58, 1, 2, 'ข้อต่อเกลียวนอก PVC ชั้น 13.5 ขนาด 6\"', 'PI-052', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(59, 1, 2, 'ข้อต่อตรงลด PVC ชั้น 13.5 ขนาด 3x2\"', 'PI-053', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(60, 1, 2, 'ข้อต่อตรงลด PVC ชั้น 13.5 ขนาด 4x3\"', 'PI-054', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(61, 1, 2, 'ข้อต่อตรงลด PVC ชั้น 13.5 ขนาด 4x2\"', 'PI-055', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(62, 1, 2, 'บอลวาล์ว PVC ขนาด 1/2\"', 'PI-056', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(63, 1, 2, 'บอลวาล์ว PVC ขนาด 3/4\"', 'PI-057', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(64, 1, 2, 'บอลวาล์ว PVC ขนาด 1\"', 'PI-058', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(65, 1, 2, 'บอลวาล์ว PVC ขนาด 1 1/2\"', 'PI-059', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(66, 1, 2, 'บอลวาล์ว PVC ขนาด 2\"', 'PI-060', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(67, 1, 2, 'บอลวาล์ว PVC ขนาด 2 1/2\"', 'PI-061', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(68, 1, 2, 'บอลวาล์ว PVC ขนาด 3\"', 'PI-062', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(69, 1, 2, 'บอลวาล์ว PVC ขนาด 4\"', 'PI-063', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(70, 1, 2, 'บอลวาล์ว PVC ขนาด 6\"', 'PI-064', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(71, 1, 2, 'ประตูน้ำทองเหลือง ขนาด 1\"', 'PI-065', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(72, 1, 2, 'ประตูน้ำทองเหลือง ขนาด 1 1/2\"', 'PI-066', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(73, 1, 2, 'ประตูน้ำทองเหลือง ขนาด 2\"', 'PI-067', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(74, 1, 2, 'ประตูน้ำทองเหลือง ขนาด 2 1/2\"', 'PI-068', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(75, 1, 2, 'ประตูน้ำทองเหลือง ขนาด 3\"', 'PI-069', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(76, 1, 2, 'ประตูน้ำทองเหลือง ขนาด 4\"', 'PI-070', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(77, 1, 2, 'ท่อดูดน้ำทองเหลือง (ชนิดรังผึ้ง) ขนาด 3\"', 'PI-071', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(78, 1, 2, 'ท่อดูดน้ำทองเหลือง (ชนิดรังผึ้ง) ขนาด 4\"', 'PI-072', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(79, 1, 2, 'ท่อ PE ขนาด 3\"', 'PI-073', NULL, '5', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(80, 1, 2, 'ท่อ PE ขนาด 4\"', 'PI-074', NULL, '5', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(81, 1, 2, 'น้ำยาประสานท่อ ขนาด 250 กรัม', 'PI-075', NULL, '6', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(82, 1, 2, 'เทปพันท่อ', 'PI-076', NULL, '7', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(83, 1, 2, 'สายยางอ่อน ขนาด 3/4\"', 'PI-077', NULL, '5', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(84, 1, 2, 'กระดาษความร้อน ขนาด 57x40', 'PI-078', NULL, '7', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(85, 1, 2, 'ฝาครอบ หนา PVC ขนาด 1/2\"', 'PI-079', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(86, 1, 2, 'ข้อต่อหางปลาไหล ขนาด 3\"', 'PI-080', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(87, 1, 2, 'เข็มขัดรัดท่อ ขนาด 4\"', 'PI-081', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(88, 1, 2, 'ข้อต่อ 45 องศา pvc 3\"', 'PI-082', NULL, '4', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(89, 1, 2, 'ยี่โบลท์(gibault joint) dia 3\"', 'PI-083', NULL, '8', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(90, 1, 2, 'ยี่โบลท์(gibault joint) dia 4\"', 'PI-084', NULL, '8', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(91, 1, 2, 'ยี่โบลท์(gibault joint) dia 6\"', 'PI-085', NULL, '8', 5, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(92, 1, 3, 'แมกเนติกคอนแทคเตอร์ (Magnetic Contactor) S-T25', 'EL-001', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(93, 1, 3, 'OVERLOAD RELAY โอเวอร์โหลด TH-T25 9A', 'EL-002', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(94, 1, 3, 'OVERLOAD RELAY โอเวอร์โหลด TH-T25 11A', 'EL-003', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(95, 1, 3, 'OVERLOAD RELAY โอเวอร์โหลด TH-T25 15A', 'EL-004', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(96, 1, 3, 'OVERLOAD RELAY โอเวอร์โหลด TH-T25 22A', 'EL-005', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(97, 1, 3, 'Phase Protector หรือ เฟสโปรเทคชั่น 380A', 'EL-006', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(98, 1, 3, 'Phase Protector หรือ เฟสโปรเทคชั่น 220A', 'EL-007', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(99, 1, 3, 'Timer Switch (ทามเมอร์สวิตช์)', 'EL-008', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(100, 1, 3, 'Timer Relay (ทามเมอร์รีเลย์)', 'EL-009', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(101, 1, 3, 'คาปาซิเตอร์ขนาด 100 ไมโครฟารัด (100 µF)', 'EL-010', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(102, 1, 3, 'คาปาซิเตอร์ขนาด 80 ไมโครฟารัด (80 µF)', 'EL-011', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(103, 1, 3, 'คาปาซิเตอร์ขนาด 50 ไมโครฟารัด (50 µF)', 'EL-012', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(104, 1, 3, 'ฟิวส์คอนโทรล (Control Fuse) 2A', 'EL-013', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(105, 1, 3, 'สวิตช์แสงแดด (Photo Switch)', 'EL-014', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(106, 1, 3, 'สวิตช์ลูกลอยไฟฟ้า (Float Switch)', 'EL-015', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(107, 1, 3, 'โวลต์มิเตอร์ (Volt Meter) 0-500V', 'EL-016', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(108, 1, 3, 'แอมป์มิเตอร์ (Ammeter) 0-30A', 'EL-017', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(109, 1, 3, 'แอมป์มิเตอร์ (Ammeter) 0-50A', 'EL-018', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(110, 1, 3, 'ไฟแสดงสถานะ (Pilot Lamp) สีแดง', 'EL-019', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(111, 1, 3, 'ไฟแสดงสถานะ (Pilot Lamp) สีเหลือง', 'EL-020', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(112, 1, 3, 'ไฟแสดงสถานะ (Pilot Lamp) สีเขียว', 'EL-021', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(113, 1, 3, 'สวิตช์ลูกศร หรือ ซีเล็คเตอร์สวิตช์ (Selector Switch)', 'EL-022', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(114, 1, 3, 'สวิตช์ (Switch)', 'EL-023', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(115, 1, 3, 'เบรกเกอร์ขนาดเล็ก หรือที่เรียกว่า Miniature Circuit Breaker (MCB) 30A', 'EL-024', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(116, 1, 3, 'เบรกเกอร์ NF63-Cw 40A', 'EL-025', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(117, 1, 3, 'เบรกเกอร์ 3P 30A', 'EL-026', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(118, 1, 3, 'สายไฟ VAF 2x1.5 ตร.มม.', 'EL-027', NULL, '5', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(119, 1, 3, 'สายไฟ VCT 2x2.5 ตร.มม.', 'EL-028', NULL, '5', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(120, 1, 3, 'สายไฟ VCT 4x6 ตร.มม.', 'EL-029', NULL, '5', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(121, 1, 3, 'สายไฟ THW 2x1.5 ตร.มม.', 'EL-030', NULL, '5', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(122, 1, 3, 'สายไฟ THW-A 1x16 ตร.มม.', 'EL-031', NULL, '5', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(123, 1, 3, 'สายไฟ NYY 2x6 ตร.มม.', 'EL-032', NULL, '5', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(124, 1, 3, 'หลอดไฟ LED', 'EL-033', NULL, '9', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(125, 1, 3, 'แร็คเหล็ก 3 ช่อง (Secondary Racks หรือแร็คร้อยสายไฟ)', 'EL-034', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(126, 1, 3, 'ลูกถ้วยขนาด 7 x 7.2 ซม. (หรือลูกถ้วยแร็ค / Spool Insulator)', 'EL-035', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(127, 1, 3, 'ปลั๊กคู่', 'EL-036', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(128, 1, 3, 'หน้ากากปลั๊ก', 'EL-037', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(129, 1, 3, 'บล็อก 2x4', 'EL-038', NULL, '8', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(130, 1, 3, 'เทปพันสายไฟ ยาว 10 m.', 'EL-039', NULL, '7', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(131, 1, 3, 'เทปพันสายไฟ ยาว 20 m.', 'EL-040', NULL, '7', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13'),
(132, 1, 3, 'ทปพันสายไฟแบบละลาย (หรือเทปยางละลาย)', 'EL-041', NULL, '7', 10, 'GENERAL', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 08:51:13', '2026-10-19 08:51:13');

-- --------------------------------------------------------

--
-- Table structure for table `inv_item_details`
--

CREATE TABLE `inv_item_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inv_item_id_fk` bigint(20) UNSIGNED NOT NULL,
  `supplier_id_fk` bigint(20) UNSIGNED NOT NULL,
  `lot_number` varchar(191) DEFAULT NULL,
  `reference_doc` varchar(191) DEFAULT NULL,
  `serial_number` varchar(191) DEFAULT NULL,
  `initial_qty` decimal(10,2) NOT NULL,
  `current_qty` decimal(10,2) NOT NULL,
  `expire_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `location_id_fk` bigint(20) UNSIGNED NOT NULL,
  `conversion_rate` decimal(10,2) NOT NULL DEFAULT 1.00,
  `status` enum('ACTIVE','EMPTY','EXPIRED','DISPOSED') NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inv_item_details`
--

INSERT INTO `inv_item_details` (`id`, `inv_item_id_fk`, `supplier_id_fk`, `lot_number`, `reference_doc`, `serial_number`, `initial_qty`, `current_qty`, `expire_date`, `received_date`, `received_by`, `location_id_fk`, `conversion_rate`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Cr001', NULL, NULL, 12.00, 9.00, '2026-10-20', '2026-10-20', 3410, 1, 1.00, 'ACTIVE', '2026-10-19 21:21:06', '2026-10-20 05:45:51');

-- --------------------------------------------------------

--
-- Table structure for table `inv_item_hazard`
--

CREATE TABLE `inv_item_hazard` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inv_item_id` bigint(20) UNSIGNED NOT NULL,
  `inv_hazard_level_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inv_locations`
--

CREATE TABLE `inv_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `department` varchar(191) DEFAULT NULL,
  `location` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inv_locations`
--

INSERT INTO `inv_locations` (`id`, `org_id_fk`, `department`, `location`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'งานประปา', 'โรงเก็บ A', NULL, '2026-10-19 21:12:58', '2026-10-19 21:12:58');

-- --------------------------------------------------------

--
-- Table structure for table `inv_transactions`
--

CREATE TABLE `inv_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_no` varchar(191) DEFAULT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `requester_id_fk` bigint(20) UNSIGNED NOT NULL,
  `requester_name` varchar(191) DEFAULT NULL,
  `approver_name` varchar(191) DEFAULT NULL,
  `approve_workflow_id_fk` bigint(20) UNSIGNED NOT NULL,
  `inv_item_id_fk` bigint(20) UNSIGNED NOT NULL,
  `inv_item_detail_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `purpose` varchar(191) DEFAULT NULL,
  `status` enum('PENDING','APPROVED','REJECTED','COMPLETED','RETURNED') NOT NULL DEFAULT 'PENDING',
  `current_step` int(11) NOT NULL DEFAULT 1,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejected_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `canceled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `canceled_at` timestamp NULL DEFAULT NULL,
  `dispensed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `dispensed_at` timestamp NULL DEFAULT NULL,
  `completed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `returned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `returned_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inv_transactions`
--

INSERT INTO `inv_transactions` (`id`, `ref_no`, `org_id_fk`, `requester_id_fk`, `requester_name`, `approver_name`, `approve_workflow_id_fk`, `inv_item_id_fk`, `inv_item_detail_id_fk`, `quantity`, `purpose`, `status`, `current_step`, `transaction_date`, `created_at`, `updated_at`, `approved_at`, `approved_by`, `rejected_by`, `rejected_at`, `canceled_by`, `canceled_at`, `dispensed_by`, `dispensed_at`, `completed_by`, `completed_at`, `returned_by`, `returned_at`) VALUES
(2, 'WD-MULTI-20261020-0001', 1, 3410, NULL, NULL, 1, 1, NULL, 1, 'ปรับสภาพน้ำดีโรง 17', 'COMPLETED', 1, '2026-10-19 22:01:04', '2026-10-19 22:01:04', '2026-10-20 05:45:51', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'WD-MULTI-20261020-0001', 1, 3408, NULL, NULL, 1, 1, NULL, 2, 'ปรับสภาพน้ำ2', 'COMPLETED', 1, '2026-10-20 05:39:00', '2026-10-20 05:39:00', '2026-10-20 05:45:51', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inv_transaction_approvals`
--

CREATE TABLE `inv_transaction_approvals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ref_no` varchar(191) NOT NULL,
  `module_name` varchar(50) NOT NULL DEFAULT 'inventory',
  `approval_workflow_id` bigint(20) UNSIGNED NOT NULL,
  `step_order` int(11) NOT NULL DEFAULT 1,
  `approver_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('PENDING','APPROVED','REJECTED') NOT NULL DEFAULT 'PENDING',
  `comment` text DEFAULT NULL,
  `action_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inv_transaction_approvals`
--

INSERT INTO `inv_transaction_approvals` (`id`, `ref_no`, `module_name`, `approval_workflow_id`, `step_order`, `approver_id`, `status`, `comment`, `action_at`, `created_at`, `updated_at`) VALUES
(6, 'WD-MULTI-20261020-0001', 'inventory', 1, 1, 3408, 'APPROVED', 'สสส', '2026-10-20 05:44:07', '2026-10-19 22:01:04', '2026-10-20 05:44:07'),
(7, 'WD-MULTI-20261020-0001', 'inventory', 1, 1, 3408, 'APPROVED', NULL, '2026-10-20 05:44:32', '2026-10-20 05:39:00', '2026-10-20 05:44:32');

-- --------------------------------------------------------

--
-- Table structure for table `inv_units`
--

CREATE TABLE `inv_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inv_units`
--

INSERT INTO `inv_units` (`id`, `org_id_fk`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'ถัง', '2026-10-19 08:32:36', '2026-10-19 08:32:36'),
(2, 1, 'กระสอบ', '2026-10-19 08:32:36', '2026-10-19 08:32:36'),
(3, 1, 'เส้น', '2026-10-19 08:32:36', '2026-10-19 08:32:36'),
(4, 1, 'อัน', '2026-10-19 08:32:36', '2026-10-19 08:32:36'),
(5, 1, 'เมตร', '2026-10-19 08:32:36', '2026-10-19 08:32:36'),
(6, 1, 'กระป๋อง', '2026-10-19 08:32:36', '2026-10-19 08:32:36'),
(7, 1, 'ม้วน', '2026-10-19 08:32:36', '2026-10-19 08:32:36'),
(8, 1, 'ตัว', '2026-10-19 08:32:36', '2026-10-19 08:32:36'),
(9, 1, 'ชุด', '2026-10-19 08:32:36', '2026-10-19 08:32:36');

-- --------------------------------------------------------

--
-- Table structure for table `issue_types`
--

CREATE TABLE `issue_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_type` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_suggested` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `color` varchar(9) NOT NULL DEFAULT '#dc2626'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `issue_types`
--

INSERT INTO `issue_types` (`id`, `system_type`, `name`, `is_active`, `is_suggested`, `created_at`, `updated_at`, `color`) VALUES
(1, 'water', 'ท่อแตก / ท่อรั่ว', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#dc2626'),
(2, 'water', 'น้ำไม่ไหล', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#f97316'),
(3, 'water', 'น้ำไหลอ่อน', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#eab308'),
(4, 'water', 'น้ำขุ่น / มีกลิ่น / มีตะกอน', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#8b5cf6'),
(5, 'water', 'มาตรวัดน้ำชำรุด / เสียหาย', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#0ea5e9'),
(6, 'recycle_trash', 'ขอแจ้งนัดรถรับซื้อขยะรีไซเคิล', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#ec4899'),
(7, 'recycle_trash', 'ปัญหาบัญชีสมาชิก / สมุดคู่ฝาก', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#14b8a6'),
(8, 'recycle_trash', 'ถังขยะรีไซเคิลล้น / ชำรุด', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#6b7280'),
(9, 'food_waste', 'ถังหมักเศษอาหารมีกลิ่นเหม็นรุนแรง', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#dc2626'),
(10, 'food_waste', 'ถังหมักเศษอาหารเต็ม / ขอรับปุ๋ย', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#f97316'),
(11, 'food_waste', 'อุปกรณ์ถังหมักชำรุด', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#eab308'),
(12, 'public_health', 'ไฟถนน / หลอดไฟสาธารณะดับ', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#8b5cf6'),
(13, 'public_health', 'ถังขยะทั่วไปล้น / ไม่ได้รับเก็บ', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#0ea5e9'),
(14, 'public_health', 'เหตุรำคาญ / กลิ่นขยะทั่วไป', 1, 0, '2026-08-25 18:40:55', '2026-08-25 18:40:55', '#ec4899'),
(15, 'recycle_trash', 'มีหนอน', 1, 1, '2026-08-25 21:23:31', '2026-08-25 21:23:31', '#14b8a6');

-- --------------------------------------------------------

--
-- Table structure for table `kiosks`
--

CREATE TABLE `kiosks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `status` enum('idle','active','maintenance','offline') NOT NULL DEFAULT 'offline',
  `current_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_waste_count` int(11) NOT NULL DEFAULT 0,
  `last_online_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kiosks`
--

INSERT INTO `kiosks` (`id`, `org_id_fk`, `name`, `lat`, `lng`, `status`, `current_user_id`, `total_waste_count`, `last_online_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'K-TNG1-01', NULL, NULL, 'offline', NULL, 0, NULL, NULL, NULL),
(2, 2, 'BIN-BKK-001', NULL, NULL, 'active', NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kiosk_matches`
--

CREATE TABLE `kiosk_matches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kiosk_id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','active','completed','expired') NOT NULL DEFAULT 'pending',
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_bank_accounts`
--

CREATE TABLE `kp_bank_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_no` varchar(191) NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `entity_type` enum('recycle_bank','foodwaste_bank','org_recycle') NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `balance` float NOT NULL DEFAULT 0,
  `points` float NOT NULL DEFAULT 0,
  `status` enum('active','inactive','deleted') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_bulk_sales`
--

CREATE TABLE `kp_bulk_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `sale_date` date NOT NULL,
  `buyer_name` varchar(191) NOT NULL,
  `total_cost` decimal(12,2) NOT NULL,
  `total_revenue` decimal(12,2) NOT NULL,
  `profit_amount` decimal(12,2) NOT NULL,
  `receipt_image` varchar(191) DEFAULT NULL,
  `recorder_id` bigint(20) UNSIGNED NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_bulk_sale_details`
--

CREATE TABLE `kp_bulk_sale_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bulk_sale_id` bigint(20) UNSIGNED NOT NULL,
  `kp_tbank_item_id` bigint(20) UNSIGNED NOT NULL,
  `weight_kg` decimal(10,2) NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `sub_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_kiosk_unknown_items`
--

CREATE TABLE `kp_kiosk_unknown_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kp_purchase_trans_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ไอดีอ้างอิงใบเสร็จ/ธุรกรรมหลัก',
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ไอดีหน่วยงาน/องค์กร',
  `kiosk_id_fk` bigint(20) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'ไอดีประจำตู้คีออส',
  `user_id_fk` varchar(50) DEFAULT NULL COMMENT 'รหัสผู้ใช้งานที่หย่อนสิ่งแปลกปลอม',
  `detected_label` varchar(191) NOT NULL COMMENT 'ชื่อคลาสขยะที่ AI อ่านได้ ณ ตอนนั้น',
  `confidence_score` int(11) NOT NULL DEFAULT 0 COMMENT 'ค่าความมั่นใจของ AI (0-100)',
  `image_path` varchar(191) DEFAULT NULL COMMENT 'พาร์ทเก็บรูปภาพขยะสิ่งแปลกปลอมในโฟลเดอร์ public',
  `status` varchar(30) NOT NULL DEFAULT 'pending_review' COMMENT 'สถานะ: pending_review, verified, rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_kiosk_unknown_items`
--

INSERT INTO `kp_kiosk_unknown_items` (`id`, `kp_purchase_trans_id`, `org_id_fk`, `kiosk_id_fk`, `user_id_fk`, `detected_label`, `confidence_score`, `image_path`, `status`, `created_at`, `updated_at`) VALUES
(1, 44, 1, 2, '10', 'UNKNOWN', 99, 'kiosk_unknown_items/1783266098_6a4a7b3228917.jpg', 'pending_review', '2026-07-05 08:41:38', '2026-07-05 08:41:38'),
(2, 44, 1, 2, '10', 'UNKNOWN', 98, 'kiosk_unknown_items/1783266098_6a4a7b3229df9.jpg', 'pending_review', '2026-07-05 08:41:38', '2026-07-05 08:41:38'),
(3, 44, 1, 2, '10', 'UNKNOWN', 99, 'kiosk_unknown_items/1783266098_6a4a7b322a4f8.jpg', 'pending_review', '2026-07-05 08:41:38', '2026-07-05 08:41:38'),
(4, 45, 1, 2, '10', 'UNKNOWN', 99, 'kiosk_unknown_items/1783266154_6a4a7b6aaafc5.jpg', 'pending_review', '2026-07-05 08:42:34', '2026-07-05 08:42:34'),
(5, 45, 1, 2, '10', 'UNKNOWN', 98, 'kiosk_unknown_items/1783266154_6a4a7b6aabe2c.jpg', 'pending_review', '2026-07-05 08:42:34', '2026-07-05 08:42:34'),
(6, 45, 1, 2, '10', 'UNKNOWN', 99, 'kiosk_unknown_items/1783266154_6a4a7b6aac380.jpg', 'pending_review', '2026-07-05 08:42:34', '2026-07-05 08:42:34'),
(7, 46, 1, 2, '10', 'UNKNOWN', 99, 'kiosk_unknown_items/1783266263_6a4a7bd72d47f.jpg', 'pending_review', '2026-07-05 08:44:23', '2026-07-05 08:44:23'),
(8, 46, 1, 2, '10', 'UNKNOWN', 98, 'kiosk_unknown_items/1783266263_6a4a7bd72e4c1.jpg', 'pending_review', '2026-07-05 08:44:23', '2026-07-05 08:44:23'),
(9, 46, 1, 2, '10', 'UNKNOWN', 99, 'kiosk_unknown_items/1783266263_6a4a7bd72eb24.jpg', 'pending_review', '2026-07-05 08:44:23', '2026-07-05 08:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `kp_money_requests`
--

CREATE TABLE `kp_money_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `verification_code` varchar(6) NOT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `is_proxy` tinyint(1) NOT NULL DEFAULT 0,
  `proxy_name` varchar(191) DEFAULT NULL,
  `payout_date` date DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_point_transfers`
--

CREATE TABLE `kp_point_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `amount` int(11) NOT NULL,
  `note` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_purchase_routes`
--

CREATE TABLE `kp_purchase_routes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `route_name` varchar(191) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_purchase_routes`
--

INSERT INTO `kp_purchase_routes` (`id`, `org_id_fk`, `route_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'zoneหมู่ 1-4', 'active', '2026-10-18 09:30:11', '2026-10-18 09:30:11'),
(2, 1, 'zoneหมู่ 5-7', 'active', '2026-10-18 09:30:31', '2026-10-18 09:30:31');

-- --------------------------------------------------------

--
-- Table structure for table `kp_purchase_route_zones`
--

CREATE TABLE `kp_purchase_route_zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kp_purchase_route_id` bigint(20) UNSIGNED NOT NULL,
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_purchase_route_zones`
--

INSERT INTO `kp_purchase_route_zones` (`id`, `kp_purchase_route_id`, `zone_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 2, 5, NULL, NULL),
(6, 2, 6, NULL, NULL),
(7, 2, 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kp_purchase_shops`
--

CREATE TABLE `kp_purchase_shops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shop_name` varchar(191) NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `contact_person` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_purchase_shops`
--

INSERT INTO `kp_purchase_shops` (`id`, `shop_name`, `org_id_fk`, `contact_person`, `phone`, `address`, `status`, `comment`, `created_at`, `updated_at`) VALUES
(1, 'อรุณโรจน์ สกลนคร ค้าของเก่า', 2, 'อรุณโรจน์', '042 714 109', '180/1 ถ.สกล-นาแก ต.ธาตุเชิงชุม อ.เมือง สกลนคร 47000, Sakhon Nakhon, Thailand, 47000', 'active', NULL, '2026-06-03 01:53:01', '2026-06-03 01:53:01'),
(2, 'ร้าน ไพศาลรับซื้อของเก่า', 2, 'ไพศาล', '082 224 7496', '280 ต บึงทวาย Tao Ngoi District, Sakon Nakhon 47260', 'active', NULL, '2026-06-03 01:54:30', '2026-06-03 01:54:30'),
(3, 'ร้านวงศ์พาณิชย์ เลิงนกทา', 1, 'นายสมหมาย', '0333239233', 'บ้านเลิงเก่า', 'active', NULL, '2026-10-18 09:29:15', '2026-10-18 09:29:15');

-- --------------------------------------------------------

--
-- Table structure for table `kp_purchase_transactions`
--

CREATE TABLE `kp_purchase_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `kp_u_trans_no` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `kiosk_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `total_weight` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_points` int(11) NOT NULL DEFAULT 0,
  `total_carbon_saved` decimal(12,4) NOT NULL DEFAULT 0.0000 COMMENT 'ยอดรวมคาร์บอนที่ลดได้ทั้งบิล (kgCO2e)',
  `cash_back` tinyint(4) NOT NULL DEFAULT 0,
  `status` varchar(191) NOT NULL DEFAULT 'completed',
  `recorder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_purchase_transactions`
--

INSERT INTO `kp_purchase_transactions` (`id`, `org_id_fk`, `kp_u_trans_no`, `user_id`, `kiosk_id_fk`, `transaction_date`, `total_weight`, `total_amount`, `total_points`, `total_carbon_saved`, `cash_back`, `status`, `recorder_id`, `created_at`, `updated_at`, `deleted`) VALUES
(2, 1, 'T-2610181640400003T04', 3, NULL, '2026-10-18', 9.00, 42.00, 3600, 30.5300, 0, '1', 3388, '2026-10-18 09:40:40', '2026-10-18 09:40:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kp_purchase_transactions_details`
--

CREATE TABLE `kp_purchase_transactions_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(22) UNSIGNED NOT NULL,
  `kp_purchase_trans_id` bigint(20) UNSIGNED NOT NULL,
  `kp_recycle_item_id` bigint(20) UNSIGNED NOT NULL,
  `kp_tbank_items_pricepoint_id` bigint(20) UNSIGNED DEFAULT NULL,
  `items_pricepoint_id` bigint(20) UNSIGNED DEFAULT NULL,
  `recorder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kp_units_idfk` bigint(20) UNSIGNED NOT NULL,
  `amount_in_units` decimal(10,2) NOT NULL,
  `price_per_unit` decimal(8,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `carbon_saved` decimal(12,4) NOT NULL DEFAULT 0.0000 COMMENT 'ปริมาณคาร์บอนที่ลดได้ (kgCO2e)',
  `image_path` text DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_purchase_transactions_details`
--

INSERT INTO `kp_purchase_transactions_details` (`id`, `org_id_fk`, `kp_purchase_trans_id`, `kp_recycle_item_id`, `kp_tbank_items_pricepoint_id`, `items_pricepoint_id`, `recorder_id`, `kp_units_idfk`, `amount_in_units`, `price_per_unit`, `amount`, `points`, `carbon_saved`, `image_path`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 54, 51, NULL, 3388, 10, 6.00, 5.00, 30.00, 3000, 20.2300, NULL, NULL, '2026-10-18 09:40:40', '2026-10-18 09:40:40'),
(2, 1, 2, 55, 53, NULL, 3388, 10, 3.00, 4.00, 12.00, 600, 10.3000, NULL, NULL, '2026-10-18 09:40:40', '2026-10-18 09:40:40');

-- --------------------------------------------------------

--
-- Table structure for table `kp_recycle_bank_members`
--

CREATE TABLE `kp_recycle_bank_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `member_number` int(11) NOT NULL,
  `zone_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_points` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `recorder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_recycle_bank_members`
--

INSERT INTO `kp_recycle_bank_members` (`id`, `org_id_fk`, `user_id`, `member_number`, `zone_id`, `total_points`, `balance`, `status`, `recorder_id`, `created_at`, `updated_at`) VALUES
(3, 1, 1, 1, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(4, 1, 2, 2, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(5, 1, 3, 3, 1, 3600.00, 42.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:40:40'),
(6, 1, 4, 4, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(7, 1, 5, 5, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(8, 1, 6, 6, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(9, 1, 7, 7, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(10, 1, 8, 8, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(11, 1, 9, 9, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(12, 1, 10, 10, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(13, 1, 11, 11, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(14, 1, 12, 12, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(15, 1, 13, 13, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(16, 1, 14, 14, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(17, 1, 15, 15, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(18, 1, 16, 16, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(19, 1, 17, 17, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(20, 1, 18, 18, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(21, 1, 19, 19, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29'),
(22, 1, 20, 20, 1, 0.00, 0.00, 'active', 3388, '2026-10-18 09:12:29', '2026-10-18 09:12:29');

-- --------------------------------------------------------

--
-- Table structure for table `kp_sell_details`
--

CREATE TABLE `kp_sell_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kp_sell_trans_id` bigint(20) UNSIGNED NOT NULL,
  `kp_recycle_item_id` bigint(20) UNSIGNED NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `price_per_unit` decimal(8,2) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_sell_details`
--

INSERT INTO `kp_sell_details` (`id`, `kp_sell_trans_id`, `kp_recycle_item_id`, `weight`, `price_per_unit`, `amount`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 21, 3.00, 6.00, 18.00, NULL, '2026-06-03 06:20:51', '2026-06-03 06:20:51'),
(2, 2, 4, 0.06, 5.00, 0.30, NULL, '2026-06-11 07:21:03', '2026-06-11 07:21:03');

-- --------------------------------------------------------

--
-- Table structure for table `kp_sell_transactions`
--

CREATE TABLE `kp_sell_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kp_u_trans_no` varchar(191) NOT NULL,
  `shop_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `total_weight` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(191) NOT NULL DEFAULT 'completed',
  `recorder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_settings`
--

CREATE TABLE `kp_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `key_value` text DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_shop_categories`
--

CREATE TABLE `kp_shop_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_shop_orders`
--

CREATE TABLE `kp_shop_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_no` varchar(191) NOT NULL,
  `user_wpref_id` bigint(20) UNSIGNED NOT NULL,
  `order_type` enum('points','cash') NOT NULL,
  `points_per_unit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cash_per_unit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_points` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_cash` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `recorder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_shop_order_details`
--

CREATE TABLE `kp_shop_order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kp_shop_order_id` bigint(20) UNSIGNED NOT NULL,
  `kp_shop_product_id` bigint(20) UNSIGNED NOT NULL,
  `order_type` enum('points','cash') NOT NULL,
  `quantity` int(11) NOT NULL,
  `points_at_purchase` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cash_at_purchase` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','complete','cancled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_shop_products`
--

CREATE TABLE `kp_shop_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_code` varchar(191) NOT NULL,
  `product_name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(191) DEFAULT NULL,
  `kp_shop_category_id` bigint(20) UNSIGNED NOT NULL,
  `point_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cash_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_tbank_items`
--

CREATE TABLE `kp_tbank_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kp_itemscode` varchar(191) NOT NULL,
  `kp_itemsname` varchar(191) NOT NULL,
  `unit_bank_idfk` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_kiosk_idfk` bigint(20) UNSIGNED DEFAULT NULL,
  `kp_items_group_idfk` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `ef_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `favorite` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_tbank_items`
--

INSERT INTO `kp_tbank_items` (`id`, `kp_itemscode`, `kp_itemsname`, `unit_bank_idfk`, `unit_kiosk_idfk`, `kp_items_group_idfk`, `org_id_fk`, `ef_id_fk`, `favorite`, `status`, `image`, `deleted`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Iron-001', 'เหล็ก', 1, NULL, 1, 2, 6, 1, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(2, 'Iron-002', 'สังกะสี', 1, 1, 1, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(3, 'Plas-001', 'พลาสติกรวม', 1, 1, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(4, 'Plas-002', 'พลาสติกแข็ง', 1, 1, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(5, 'Plas-003', 'สายยางเขียว', 1, 1, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(6, 'Plas-004', 'สายยางขาว', 1, 1, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(7, 'Plas-005', 'รองเท้าบูท', 1, 1, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(8, 'Plas-006', 'ท่อ PVC ฟ้า', 1, 1, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(9, 'Plas-007', 'ท่อ PVC เหลือง-เทา', 1, 1, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(10, 'Oth-001', 'แผ่น CD', 1, 1, 7, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(11, 'Plas-008', 'ขวดเพทใส', 1, NULL, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(13, 'Glass-001', 'ขวดแก้วขุ่น', 1, 1, 3, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(14, 'Glass-002', 'ช้าง(ลัง)', 5, NULL, 3, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(15, 'Glass-003', 'ลีโอ', 5, 5, 3, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-08-18 05:13:40', NULL),
(16, 'Glass-004', 'เหล้าขาวใหญ่(ลัง)', 5, NULL, 3, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(17, 'Glass-005', 'เหล้าขาวเล็ก(ลัง)', 5, NULL, 3, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(18, 'Glass-006', 'น้ำปลา(ขวด)', 2, NULL, 3, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(19, 'Glass-007', 'น้ำปลา(ลัง)', 5, NULL, 3, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(20, 'Peper-001', 'กระดาษขาว-ดำ', 1, NULL, 2, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(21, 'Peper-002', 'กระดาษแข็ง', 1, NULL, 2, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(22, 'Peper-003', 'กระดาษย่อย', 1, NULL, 2, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(23, 'Iron-003', 'โลหะกระป๋อง/โลหะบาง', 1, NULL, 1, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(24, 'Iron-004', 'โลหะติดเหล็ก', 1, NULL, 1, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(25, 'Iron-005', 'โลหะหนา', 1, NULL, 1, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(26, 'Nfm-001', 'ทองแดงปลอก', 1, NULL, 5, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(27, 'Oth-002', 'แบตเตอรี่ เล็ก', 8, NULL, 7, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(28, 'Oth-003', 'แบตเตอรี่ ใหญ่', 8, NULL, 7, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(29, 'Oth-004', 'แบตเตอรี่มอเตอร์ไซค์', 8, NULL, 7, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(30, 'Oth-005', 'แบตเตอรี่รถยนต์', 8, NULL, 7, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(31, 'Nfm-002', 'สายไฟทองแดง', 1, NULL, 5, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(32, 'Iron-006', 'โลหะกระทะ', 1, NULL, 1, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(33, 'Ewaste-001', 'พัดลม เล็ก(ตัว)', 7, NULL, 6, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(34, 'Ewaste-002', 'พัดลม ใหญ่(ตัว)', 8, NULL, 6, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(35, 'Ewaste-003', 'เครื่องซักผ้าใหญ่(เครื่อง)', 6, NULL, 6, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(36, 'Ewaste-004', 'เครื่องซักผ้าเล็ก(เครื่อง)', 6, NULL, 6, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(37, 'Glass-008', 'ไฮเนเก้น(ลัง)', 5, NULL, 3, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(38, 'Nfm-003', 'ทองแดงสวย', 1, NULL, 5, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(39, 'Nfm-004', 'ทองแดงช็อต', 1, NULL, 5, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(40, 'Oth-006', 'นุ่น', 1, NULL, 7, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(41, 'Nfm-003', 'ทองเหลือง', 1, NULL, 5, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(42, 'Ewaste-005', 'ทีวีใหญ่ 21 นิ้ว', 6, NULL, 6, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(43, 'Ewaste-006', 'ทีวีเล็ก14 นิ้ว', 6, NULL, 6, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(44, 'Ewaste-007', 'ตู้เย็น', 6, NULL, 6, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(45, 'Glass-009', 'ขวดสกรีน', 1, NULL, 3, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(46, 'Nfm-005', 'ทองแดงเผา', 1, NULL, 5, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(47, 'PET1500ml', 'ขวดเพทใส 1500 ml.', 1, 2, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-23 18:37:31', NULL),
(48, 'PET250ml', 'ขวดเพทใส 250 ml.', 1, 2, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(49, 'PET350ml', 'ขวดเพทใส 350 ml.', 1, 2, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-23 18:37:16', NULL),
(50, 'PET750ml', 'ขวดPET ใส 750 ml.', 1, 2, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-23 18:36:43', '2026-06-23 18:36:43', NULL),
(51, 'PET500ml', 'ขวดPET ใส 500 ml.', 1, 2, 4, 2, 6, 0, 'active', NULL, '0', '2026-06-23 18:36:43', '2026-06-23 18:36:43', NULL),
(53, 'Glass-008', 'ขวดแก้วใส', 1, NULL, 3, 2, 6, 0, 'active', NULL, '0', '2026-06-03 01:39:40', '2026-06-03 01:39:40', NULL),
(54, 'PET01', 'ขวด PET', 1, NULL, 8, 1, NULL, 0, 'active', NULL, '0', '2026-10-18 09:25:26', '2026-10-18 09:25:26', NULL),
(55, 'PAPERW01', 'กระดาษขาว', 1, NULL, 9, 1, NULL, 0, 'active', NULL, '0', '2026-10-18 09:25:26', '2026-10-18 09:25:26', NULL),
(56, 'PET002', 'ขวด PET (ซื้อเป็นขวด)', 2, 2, 8, 1, NULL, 0, 'active', NULL, '0', '2026-10-18 09:26:30', '2026-10-18 09:26:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kp_tbank_items_groups`
--

CREATE TABLE `kp_tbank_items_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `kp_items_groupname` varchar(191) NOT NULL,
  `kp_items_group_code` varchar(191) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sequence_number` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_tbank_items_groups`
--

INSERT INTO `kp_tbank_items_groups` (`id`, `org_id_fk`, `kp_items_groupname`, `kp_items_group_code`, `status`, `deleted`, `created_at`, `updated_at`, `sequence_number`) VALUES
(1, 2, 'เศษเหล็ก', 'Steel', 'active', '0', '2026-05-20 02:43:05', '2026-05-20 02:43:05', 1),
(2, 2, 'เศษกระดาษ', 'Paper', 'active', '0', '2026-05-20 02:43:05', '2026-05-20 02:43:05', 1),
(3, 2, 'ขวดแก้ว', 'Glass', 'active', '0', '2026-05-20 02:43:05', '2026-05-20 02:43:05', 1),
(4, 2, 'พลาสติก', 'Plastic', 'active', '0', '2026-05-20 02:43:05', '2026-05-20 02:43:05', 1),
(5, 2, 'โลหะที่\0มีค่าสูง', 'NonferrousMetals', 'active', '0', '2026-05-20 02:43:05', '2026-05-20 02:43:05', 1),
(6, 2, 'เครื่\0องใช้สํานักงานและเครื่องใช้ไฟฟ้า', 'E-waste', 'active', '0', '2026-05-20 02:43:05', '2026-05-20 02:43:05', 1),
(7, 2, 'อื่นๆ', 'Others', 'active', '0', '2026-05-20 02:43:05', '2026-05-20 02:43:05', 1),
(8, 1, 'ขวด PET', 'PET', 'active', '0', '2026-10-18 09:23:14', '2026-10-18 09:23:14', 1),
(9, 1, 'กระดาษ', 'PAPER', 'active', '0', '2026-10-18 09:23:14', '2026-10-18 09:23:14', 1),
(10, 1, 'เหล็ก', 'IRON', 'active', '0', '2026-10-18 09:23:14', '2026-10-18 09:23:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kp_tbank_items_pricepoint`
--

CREATE TABLE `kp_tbank_items_pricepoint` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `kp_items_idfk` bigint(20) UNSIGNED NOT NULL,
  `price_from_dealer` float NOT NULL,
  `price_for_member` float NOT NULL,
  `point` double NOT NULL,
  `type` enum('tbank','tbox') NOT NULL,
  `kp_units_idfk` bigint(20) UNSIGNED NOT NULL,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `recorder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_tbank_items_pricepoint`
--

INSERT INTO `kp_tbank_items_pricepoint` (`id`, `org_id_fk`, `kp_items_idfk`, `price_from_dealer`, `price_for_member`, `point`, `type`, `kp_units_idfk`, `effective_date`, `end_date`, `status`, `deleted`, `recorder_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 8, 5, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(2, 2, 2, 3, 2, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(3, 2, 3, 2, 2, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(4, 2, 4, 4, 1, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(5, 2, 5, 1, 0.5, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(6, 2, 6, 2, 1, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(7, 2, 7, 5, 3, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(8, 2, 8, 3, 1, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(9, 2, 9, 1, 0.5, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(10, 2, 10, 3, 1, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(11, 2, 11, 8, 5, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(12, 2, 53, 1, 0.5, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(13, 2, 13, 1, 0.5, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(14, 2, 15, 11, 6, 20, 'tbank', 5, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(15, 2, 14, 12, 6, 20, 'tbank', 5, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(16, 2, 17, 19, 12, 20, 'tbank', 5, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(17, 2, 16, 21, 16, 20, 'tbank', 5, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(18, 2, 18, 1, 1, 20, 'tbank', 2, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(19, 2, 19, 18, 16, 20, 'tbank', 5, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(20, 2, 20, 4, 2, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(21, 2, 21, 2.7, 1.5, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(22, 2, 22, 1.5, 1, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(23, 2, 23, 55, 20, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(24, 2, 24, 13, 8, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(25, 2, 25, 42, 30, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(26, 2, 26, 235, 180, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(27, 2, 27, 18, 10, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(28, 2, 28, 20, 15, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(29, 2, 29, 20, 18, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(30, 2, 30, 300, 280, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(31, 2, 31, 17, 15, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(32, 2, 32, 17, 15, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(33, 2, 33, 20, 15, 20, 'tbank', 7, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(34, 2, 34, 35, 20, 20, 'tbank', 7, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(35, 2, 36, 200, 150, 20, 'tbank', 6, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(36, 2, 35, 300, 200, 20, 'tbank', 6, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(37, 2, 37, 7, 5, 20, 'tbank', 5, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(38, 2, 38, 180, 120, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(39, 2, 39, 180, 120, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(40, 2, 40, 4, 2, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(41, 2, 41, 100, 60, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(42, 2, 42, 100, 70, 20, 'tbank', 6, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(43, 2, 43, 50, 25, 20, 'tbank', 6, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(44, 2, 44, 300, 250, 20, 'tbank', 6, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(45, 2, 45, 1, 0.5, 20, 'tbank', 2, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(46, 2, 46, 180, 120, 20, 'tbank', 1, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(47, 2, 47, 0.06, 0.04, 20, 'tbox', 2, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(48, 2, 49, 0.04, 0.02, 20, 'tbox', 2, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(49, 2, 50, 0.06, 0.04, 20, 'tbox', 2, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(50, 2, 51, 0.04, 0.02, 20, 'tbox', 2, '2026-08-18', NULL, 'active', '0', NULL, '2026-08-18 05:25:59', '2026-08-18 05:25:59'),
(51, 1, 54, 7, 5, 500, 'tbank', 10, '2026-10-18', NULL, 'active', '0', 3388, '2026-10-18 09:28:08', '2026-10-18 09:28:08'),
(52, 1, 54, 0.3, 0.15, 10, 'tbank', 9, '2026-10-18', NULL, 'active', '0', 3388, '2026-10-18 09:28:08', '2026-10-18 09:28:08'),
(53, 1, 55, 5, 4, 200, 'tbank', 10, '2026-10-18', NULL, 'active', '0', 3388, '2026-10-18 09:28:08', '2026-10-18 09:28:08');

-- --------------------------------------------------------

--
-- Table structure for table `kp_tbank_items_units`
--

CREATE TABLE `kp_tbank_items_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `unitname` varchar(191) NOT NULL,
  `unit_short_name` varchar(191) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_tbank_items_units`
--

INSERT INTO `kp_tbank_items_units` (`id`, `org_id_fk`, `unitname`, `unit_short_name`, `status`, `deleted`, `created_at`, `updated_at`) VALUES
(1, 2, 'กิโลกรัม', 'กก.', 'active', '0', '2026-05-20 02:36:48', '2026-05-20 02:36:48'),
(2, 2, 'ขวด', 'ขวด', 'active', '0', '2026-05-20 02:36:48', '2026-05-20 02:36:48'),
(3, 2, 'ชิ้น', 'ชิ้น', 'active', '0', '2026-06-02 23:23:44', '2026-06-02 23:23:44'),
(4, 2, 'แผ่น', 'แผ่น', 'active', '0', '2026-06-02 23:23:44', '2026-06-02 23:23:44'),
(5, 2, 'ลัง', 'ลัง', 'active', '0', '2026-06-02 23:23:44', '2026-06-02 23:23:44'),
(6, 2, 'เครื่อง', 'เครื่อง', 'active', '0', '2026-06-02 23:23:44', '2026-06-02 23:23:44'),
(7, 2, 'ตัว', 'ตัว', 'active', '0', '2026-06-02 23:23:44', '2026-06-02 23:23:44'),
(8, 2, 'ก้อน', 'ก้อน', 'active', '0', '2026-06-02 23:23:44', '2026-06-02 23:23:44'),
(9, 1, 'ขวด', 'ขวด', 'active', '0', '2026-10-18 09:23:54', '2026-10-18 09:23:54'),
(10, 1, 'กิโลกรัม', 'กก.', 'active', '0', '2026-10-18 09:23:54', '2026-10-18 09:23:54');

-- --------------------------------------------------------

--
-- Table structure for table `kp_usergroups`
--

CREATE TABLE `kp_usergroups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usergroup_name` varchar(191) NOT NULL,
  `status` enum('active','invactive') NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kp_usergroups`
--

INSERT INTO `kp_usergroups` (`id`, `usergroup_name`, `status`, `deleted`, `created_at`, `updated_at`) VALUES
(1, 'ครัวเรือน', 'active', '0', '2026-10-18 16:45:24', '2026-10-18 16:45:24'),
(2, 'โรงเรียน', 'active', '0', '2026-10-18 16:45:32', '2026-10-18 16:45:32'),
(3, 'วัด', 'active', '0', '2026-10-18 16:45:40', '2026-10-18 16:45:40');

-- --------------------------------------------------------

--
-- Table structure for table `kp_user_waste_preferences`
--

CREATE TABLE `kp_user_waste_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `is_annual_collection` tinyint(1) NOT NULL DEFAULT 0,
  `is_waste_bank` tinyint(1) NOT NULL DEFAULT 0,
  `is_tabwater` tinyint(1) NOT NULL DEFAULT 0,
  `address` text DEFAULT NULL COMMENT 'ที่อยู่เฉพาะประจำองค์กร',
  `zone_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subzone_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tambon_code` bigint(20) UNSIGNED DEFAULT NULL,
  `district_code` bigint(20) UNSIGNED DEFAULT NULL,
  `province_code` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_waste_bank_transactions`
--

CREATE TABLE `kp_waste_bank_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_member_payout_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estimated_factory_revenue` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transaction_date` date NOT NULL,
  `receipt_code` varchar(191) DEFAULT NULL,
  `status` enum('completed','pending','cancelled') NOT NULL DEFAULT 'completed',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_waste_groups`
--

CREATE TABLE `kp_waste_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_waste_types`
--

CREATE TABLE `kp_waste_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `waste_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `default_unit` varchar(191) NOT NULL DEFAULT 'kg',
  `factory_buy_price_per_kg` decimal(10,2) DEFAULT 0.00,
  `member_buy_price_per_kg` decimal(10,2) DEFAULT 0.00,
  `factory_buy_price_per_piece` decimal(10,2) DEFAULT 0.00,
  `member_buy_price_per_piece` decimal(10,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `is_recyclable` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kp_welfare_payouts`
--

CREATE TABLE `kp_welfare_payouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `deceased_name` varchar(191) NOT NULL,
  `beneficiary_name` varchar(191) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `death_certificate_img` varchar(191) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `approver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `local_foods`
--

CREATE TABLE `local_foods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(191) NOT NULL,
  `menu_name` varchar(191) NOT NULL,
  `calories` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `local_foods`
--

INSERT INTO `local_foods` (`id`, `category`, `menu_name`, `calories`, `created_at`, `updated_at`) VALUES
(1, 'ผัด/ทอด', 'ผัดกะเพราหมูสับ', 550, NULL, NULL),
(2, 'ต้ม/แกง', 'แกงอ่อมไก่', 150, NULL, NULL),
(3, 'ต้ม/แกง', 'แกงหน่อไม้', 120, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `machine_id` varchar(191) NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `current_user_active_id` bigint(20) UNSIGNED NOT NULL,
  `has_new_object` tinyint(4) NOT NULL COMMENT 'สถานะขวดใหม่ (0: ไม่มี, 1: แจ้งเตือนแล้ว/รอ AI, 2: AI ตัดสินใจแล้ว)',
  `pending_command` tinyint(4) NOT NULL COMMENT 'คำสั่งที่ AI ตัดสินใจแล้ว (null: ไม่มีคำสั่ง, 0: ACCEPT, 1: REJECT)',
  `buycomplete` enum('0','1') DEFAULT NULL,
  `status` enum('wakeup','pending_login','object_detected','ready','active_session','sleep') NOT NULL DEFAULT 'sleep',
  `machine_ready` tinyint(1) NOT NULL DEFAULT 0,
  `last_heartbeat_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meal_items`
--

CREATE TABLE `meal_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meal_log_id` bigint(20) UNSIGNED NOT NULL,
  `menu_name` varchar(191) NOT NULL,
  `category` varchar(191) DEFAULT NULL,
  `calories` int(11) NOT NULL DEFAULT 0,
  `status` enum('verified','pending_review') NOT NULL DEFAULT 'verified',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_03_01_151919_create_permission_tables', 1),
(6, '2023_10_27_171314_create_number_sequences_table', 1),
(7, '2023_10_27_182335_create_meter_types_table', 1),
(8, '2023_10_27_184222_create_provinces_table', 1),
(9, '2023_10_27_184223_create_districts_table', 1),
(10, '2023_10_27_184223_create_tambons_table', 1),
(11, '2023_10_27_184224_create_zones_table', 1),
(12, '2023_10_27_184225_create_subzones_table', 1),
(13, '2023_10_30_040857_create_budget_year', 1),
(14, '2023_10_30_130210_create_tw_merter_infos_table', 1),
(15, '2023_10_30_132633_create_invoice_period', 1),
(16, '2023_10_31_074745_create_settings_table', 1),
(17, '2023_11_01_083652_create_accounts_table', 1),
(18, '2023_11_01_083653_create_acc_transactions_table', 1),
(19, '2023_11_01_083655_create_invoice_table', 1),
(20, '2023_11_01_083656_create_invoice_temp_table', 1),
(21, '2023_11_03_084012_create_undertaker_subzones_table', 1),
(22, '2024_04_16_005012_create_cutmeter_table', 1),
(23, '2025_04_25_084012_create_organizations_table', 1),
(24, '2025_04_25_141151_create_kp_tbank_items_units_table', 1),
(25, '2025_04_25_143303_create_kp_tbank_items_groups_table', 1),
(26, '2025_04_25_143434_create_kp_tbank_items_table', 1),
(27, '2025_04_25_171102_create_staff_table', 1),
(28, '2025_04_25_171119_create_kp_tbank_items_price_point_table', 1),
(29, '2025_05_28_175914_create_pricing_types_table', 1),
(30, '2025_05_28_180217_create_meter_type_rate_configs_table', 1),
(31, '2025_05_28_180229_create_meter_type_rate_tiers_table', 1),
(32, '2025_07_21_044920_create_kiosks_table', 1),
(33, '2025_07_23_020202_create_kp_user_waste_preferences_table', 1),
(34, '2025_07_23_020236_create_annual_trashs_table', 1),
(35, '2025_07_23_020324_create_waste_bank_transactions_table', 1),
(36, '2025_07_23_062840_create_waste_groups_table', 1),
(37, '2025_07_23_063015_create_waste_types_table', 1),
(38, '2025_07_23_063259_create_transaction_details_table', 1),
(39, '2025_08_11_065735_create_machines_table', 1),
(40, '2025_08_11_112538_create_kp_purchase_transactions_table', 1),
(41, '2025_08_11_123535_create_kp_purchase_details_table', 1),
(42, '2025_08_12_034322_create_kp_purchase_shops_table', 1),
(43, '2025_08_12_041717_create_kp_sell_transactions_table', 1),
(44, '2025_08_12_041741_create_kp_sell_details_table', 1),
(45, '2025_08_15_141549_create_cp_sensor_data_table', 1),
(46, '2025_08_15_164438_create_compost_bins_table', 1),
(47, '2025_09_07_070256_add_org_id_fk_to_users_table', 1),
(48, '2025_09_12_061429_add_new_column_to_kp_tbank_items_groups_table', 1),
(49, '2025_09_15_162206_create_kp_accounts_table', 1),
(50, '2025_09_15_233548_create_org_accounts_table', 1),
(51, '2025_09_15_234725_create_kp_shop_categories_table', 1),
(52, '2025_09_15_234922_create_kp_shop_products_table', 1),
(53, '2025_09_15_235415_create_kp_shop_orders_table', 1),
(54, '2025_09_15_235444_create_kp_shop_order_details_table', 1),
(55, '2025_10_07_004125_create_kp_usergroups_table', 1),
(56, '2025_10_07_005831_create_annual_trash_payrate_permonth_table', 1),
(57, '2025_10_07_005925_create_annual_trash_subscriptions_table', 1),
(58, '2025_10_08_033219_create_foodwast_iotboxes_table', 1),
(59, '2025_10_08_040939_create_foodwaste_bins_table', 1),
(60, '2025_10_08_044226_create_user_bin_iotbox_matchings_table', 1),
(61, '2025_10_25_061648_add_column_to_zone_table', 1),
(62, '2025_10_25_065456_add_column_to_tw_meter_infos_table', 1),
(63, '2025_10_25_082842_add_column_in_users_table', 1),
(64, '2025_10_26_211119_add_org_id_fk_intw_meter_type_rate_configs_table', 1),
(65, '2025_11_12_133602_add_lat_long_column_in_organizations_table', 1),
(66, '2025_11_12_145208_add_lat_long_column_in_zones_table', 1),
(67, '2025_11_25_002201_create_tw_notifies_table', 1),
(68, '2025_11_25_003016_create_tw_notify_staff_table', 1),
(69, '2026_01_04_053935_add_org_id_to_twinvoice_table', 1),
(70, '2026_01_04_060851_add_org_id_to_invoiceperiod_table', 1),
(71, '2026_01_04_060852_add_org_id_to_subzone_table', 1),
(72, '2026_01_04_060853_add_org_id_to_ tw_meter_types_table', 1),
(73, '2026_01_04_060854_add_org_id_to_kp_purchase_transactions_table', 1),
(74, '2026_01_07_161730_create_tw_invoice_history_table', 1),
(75, '2026_02_03_001852_create_emission_factors_table', 1),
(76, '2026_02_03_013605_add_carbon_saved_to_transaction_details', 1),
(77, '2026_02_03_020557_add_emission_factor_id_to_kp_tbank_items', 1),
(78, '2026_02_03_025050_add_total_carbon_saved_to_kp_purchase_transactions', 1),
(79, '2026_02_15_071146_create_foodwaste_user_preferences_table', 1),
(80, '2026_02_17_051427_create_kiosk_matches_table', 1),
(81, '2026_03_15_032044_create_airo_logs_tables', 1),
(82, '2026_03_15_044640_create_local_foods_table', 1),
(83, '2026_03_15_085233_create_new_meal_structure', 1),
(84, '2026_03_15_111052_add_waste_logs_table', 1),
(85, '2026_03_15_111942_create_compost_batches_table', 1),
(86, '2026_03_15_113838_add_user_id_to_compost_batches_table', 1),
(87, '2026_03_15_120213_add_batch_id_to_waste_logs_table', 1),
(88, '2026_03_15_121935_add_temperature_to_waste_logs_table', 1),
(89, '2026_03_15_124413_create_foodwaste_issue_types_table', 1),
(90, '2026_03_18_044452_create_foodwaste_issue_report_table', 1),
(91, '2026_03_18_084630_create_foodwaste_transactions_table', 1),
(92, '2026_03_18_101201_add_waste_log_id_to_foodwaste_transactions_table', 1),
(93, '2026_03_18_124658_add_metrics_to_users_table', 1),
(94, '2026_03_18_174606_create_foodwaste_reward_settings_table', 1),
(95, '2026_03_19_160122_update_kp_tbank_items_table', 1),
(96, '2026_03_19_161027_update_foodwaste_issue_reports_table', 1),
(97, '2026_03_20_005533_add_deleted_to_kp_purchase_transactions_table', 1),
(98, '2026_03_20_013608_update_kp_tbank_items_table', 1),
(99, '2026_03_20_040311_update_kp_tbank_items', 1),
(100, '2026_03_20_042837_add_soft_deletes_to_kp_tbank_items_table', 1),
(101, '2026_03_23_102155_create_recycle_bank_accounts_table', 1),
(102, '2026_03_23_102244_create_recycle_transactions_table', 1),
(103, '2026_03_23_102311_create_food_waste_accounts_table', 1),
(104, '2026_03_25_010630_create_organization_types_table', 1),
(105, '2026_03_25_132057_add_unit_and_item_to_kp_purchase_transactions_details', 1),
(106, '2026_03_26_145827_create_kp_money_requests_table', 1),
(107, '2026_03_26_151038_create_kp_point_transfers_table', 1),
(108, '2026_03_26_153822_create_kp_welfare_payouts_table', 1),
(109, '2026_03_26_154032_create_kp_bulk_sales_table', 1),
(110, '2026_03_26_154112_create_kp_bulk_sale_details_table', 1),
(111, '2026_03_26_164105_create_kp_welfare_configs_table', 1),
(112, '2026_05_19_040514_add_columns_to_kp_user_waste_preferences_table', 1),
(113, '2026_06_11_134609_create_kp_kiosk_unknown_items_table', 2),
(114, '2026_01_12_081344_create_inv_units_table', 3),
(115, '2026_01_12_092055_create_inv_categories_table', 3),
(116, '2026_01_12_092056_create_inv_items_table', 3),
(117, '2026_01_12_092058_create_inv_transactions_table', 3),
(118, '2026_01_12_094524_create_inv_item_details_table', 3),
(119, '2026_01_12_155337_add_detail_id_to_inv_transactions_table', 3),
(120, '2026_01_12_165229_add_approver_to_inv_transactions_table', 3),
(121, '2026_01_12_170128_create_inv_hazard_levels_table', 3),
(122, '2026_01_12_170303_create_inv_item_hazard_table', 3),
(123, '2026_01_12_174237_add_status_ref_to_transactions', 3),
(124, '2026_08_25_000000_fix_meal_items_table_name', 3),
(125, '2026_08_26_013357_create_issue_types_table', 3),
(126, '2026_08_26_014508_add_system_type_and_custom_issue_to_tw_notifies_table', 4),
(127, '2026_08_26_015000_update_columns_in_tw_notifies_table', 5),
(128, '2026_08_26_015830_create_system_modules_table', 6),
(129, '2026_08_28_025204_add_column_charge_min_only_zero_use_in_meter_type_rate_configs_table', 7),
(130, '2026_08_28_031004_add_column_tier_calculation_type_in_meter_type_rate_configs_table', 8),
(131, '2026_10_18_155250_create_kp_recycle_bank_members_table', 9),
(132, '2026_10_18_232715_create_annual_trash_members_table', 10),
(133, '2026_10_26_000001_create_org_invoice_sequences_table', 11),
(134, '2026_10_26_000002_add_org_id_fk_to_annual_trash_tables', 12),
(135, '2026_10_29_143353_create_smart_utility_tables', 13),
(136, '2026_11_01_000001_add_color_to_issue_types_and_member_to_foodwaste_bins', 14);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 99),
(1, 'App\\Models\\User', 105),
(1, 'App\\Models\\User', 3857),
(2, 'App\\Models\\User', 99),
(2, 'App\\Models\\User', 105),
(2, 'App\\Models\\User', 3857),
(6, 'App\\Models\\User', 3857);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(7, 'App\\Models\\User', 3),
(7, 'App\\Models\\User', 4),
(7, 'App\\Models\\User', 5),
(7, 'App\\Models\\User', 6),
(7, 'App\\Models\\User', 7),
(7, 'App\\Models\\User', 8),
(7, 'App\\Models\\User', 9),
(7, 'App\\Models\\User', 10),
(7, 'App\\Models\\User', 11),
(7, 'App\\Models\\User', 12),
(7, 'App\\Models\\User', 13),
(7, 'App\\Models\\User', 14),
(7, 'App\\Models\\User', 15),
(7, 'App\\Models\\User', 16),
(7, 'App\\Models\\User', 17),
(7, 'App\\Models\\User', 18),
(7, 'App\\Models\\User', 19),
(7, 'App\\Models\\User', 20),
(7, 'App\\Models\\User', 21),
(7, 'App\\Models\\User', 22),
(7, 'App\\Models\\User', 23),
(7, 'App\\Models\\User', 24),
(7, 'App\\Models\\User', 25),
(7, 'App\\Models\\User', 26),
(7, 'App\\Models\\User', 27),
(7, 'App\\Models\\User', 28),
(7, 'App\\Models\\User', 29),
(7, 'App\\Models\\User', 30),
(7, 'App\\Models\\User', 31),
(7, 'App\\Models\\User', 32),
(7, 'App\\Models\\User', 33),
(7, 'App\\Models\\User', 34),
(7, 'App\\Models\\User', 35),
(7, 'App\\Models\\User', 36),
(7, 'App\\Models\\User', 37),
(7, 'App\\Models\\User', 38),
(7, 'App\\Models\\User', 39),
(7, 'App\\Models\\User', 40),
(7, 'App\\Models\\User', 41),
(7, 'App\\Models\\User', 42),
(7, 'App\\Models\\User', 43),
(7, 'App\\Models\\User', 44),
(7, 'App\\Models\\User', 45),
(7, 'App\\Models\\User', 46),
(7, 'App\\Models\\User', 47),
(7, 'App\\Models\\User', 48),
(7, 'App\\Models\\User', 49),
(7, 'App\\Models\\User', 50),
(7, 'App\\Models\\User', 51),
(7, 'App\\Models\\User', 52),
(7, 'App\\Models\\User', 53),
(7, 'App\\Models\\User', 54),
(7, 'App\\Models\\User', 55),
(7, 'App\\Models\\User', 56),
(7, 'App\\Models\\User', 57),
(7, 'App\\Models\\User', 58),
(7, 'App\\Models\\User', 59),
(7, 'App\\Models\\User', 60),
(7, 'App\\Models\\User', 61),
(7, 'App\\Models\\User', 62),
(7, 'App\\Models\\User', 63),
(7, 'App\\Models\\User', 64),
(7, 'App\\Models\\User', 65),
(7, 'App\\Models\\User', 66),
(7, 'App\\Models\\User', 67),
(7, 'App\\Models\\User', 68),
(7, 'App\\Models\\User', 69),
(7, 'App\\Models\\User', 70),
(7, 'App\\Models\\User', 71),
(7, 'App\\Models\\User', 72),
(7, 'App\\Models\\User', 73),
(7, 'App\\Models\\User', 74),
(7, 'App\\Models\\User', 75),
(7, 'App\\Models\\User', 76),
(7, 'App\\Models\\User', 77),
(7, 'App\\Models\\User', 78),
(7, 'App\\Models\\User', 79),
(7, 'App\\Models\\User', 80),
(7, 'App\\Models\\User', 81),
(7, 'App\\Models\\User', 82),
(7, 'App\\Models\\User', 83),
(7, 'App\\Models\\User', 84),
(7, 'App\\Models\\User', 85),
(7, 'App\\Models\\User', 86),
(7, 'App\\Models\\User', 87),
(7, 'App\\Models\\User', 88),
(7, 'App\\Models\\User', 89),
(7, 'App\\Models\\User', 90),
(7, 'App\\Models\\User', 91),
(7, 'App\\Models\\User', 92),
(7, 'App\\Models\\User', 93),
(7, 'App\\Models\\User', 94),
(7, 'App\\Models\\User', 95),
(7, 'App\\Models\\User', 96),
(7, 'App\\Models\\User', 97),
(7, 'App\\Models\\User', 98),
(7, 'App\\Models\\User', 99),
(7, 'App\\Models\\User', 100),
(7, 'App\\Models\\User', 101),
(7, 'App\\Models\\User', 102),
(7, 'App\\Models\\User', 103),
(7, 'App\\Models\\User', 104),
(7, 'App\\Models\\User', 105),
(7, 'App\\Models\\User', 106),
(7, 'App\\Models\\User', 107),
(7, 'App\\Models\\User', 108),
(7, 'App\\Models\\User', 109),
(7, 'App\\Models\\User', 110),
(7, 'App\\Models\\User', 111),
(7, 'App\\Models\\User', 112),
(7, 'App\\Models\\User', 113),
(7, 'App\\Models\\User', 114),
(7, 'App\\Models\\User', 115),
(7, 'App\\Models\\User', 116),
(7, 'App\\Models\\User', 117),
(7, 'App\\Models\\User', 118),
(7, 'App\\Models\\User', 119),
(7, 'App\\Models\\User', 120),
(7, 'App\\Models\\User', 121),
(7, 'App\\Models\\User', 122),
(7, 'App\\Models\\User', 123),
(7, 'App\\Models\\User', 124),
(7, 'App\\Models\\User', 125),
(7, 'App\\Models\\User', 126),
(7, 'App\\Models\\User', 127),
(7, 'App\\Models\\User', 128),
(7, 'App\\Models\\User', 129),
(7, 'App\\Models\\User', 130),
(7, 'App\\Models\\User', 131),
(7, 'App\\Models\\User', 132),
(7, 'App\\Models\\User', 133),
(7, 'App\\Models\\User', 134),
(7, 'App\\Models\\User', 135),
(7, 'App\\Models\\User', 136),
(7, 'App\\Models\\User', 137),
(7, 'App\\Models\\User', 138),
(7, 'App\\Models\\User', 139),
(7, 'App\\Models\\User', 140),
(7, 'App\\Models\\User', 141),
(7, 'App\\Models\\User', 142),
(7, 'App\\Models\\User', 143),
(7, 'App\\Models\\User', 144),
(7, 'App\\Models\\User', 145),
(7, 'App\\Models\\User', 146),
(7, 'App\\Models\\User', 147),
(7, 'App\\Models\\User', 148),
(7, 'App\\Models\\User', 149),
(7, 'App\\Models\\User', 150),
(7, 'App\\Models\\User', 151),
(7, 'App\\Models\\User', 152),
(7, 'App\\Models\\User', 153),
(7, 'App\\Models\\User', 154),
(7, 'App\\Models\\User', 155),
(7, 'App\\Models\\User', 156),
(7, 'App\\Models\\User', 157),
(7, 'App\\Models\\User', 158),
(7, 'App\\Models\\User', 159),
(7, 'App\\Models\\User', 160),
(7, 'App\\Models\\User', 161),
(7, 'App\\Models\\User', 162),
(7, 'App\\Models\\User', 163),
(7, 'App\\Models\\User', 164),
(7, 'App\\Models\\User', 165),
(7, 'App\\Models\\User', 166),
(7, 'App\\Models\\User', 167),
(7, 'App\\Models\\User', 168),
(7, 'App\\Models\\User', 169),
(7, 'App\\Models\\User', 170),
(7, 'App\\Models\\User', 171),
(7, 'App\\Models\\User', 172),
(7, 'App\\Models\\User', 173),
(7, 'App\\Models\\User', 174),
(7, 'App\\Models\\User', 175),
(7, 'App\\Models\\User', 176),
(7, 'App\\Models\\User', 177),
(7, 'App\\Models\\User', 178),
(7, 'App\\Models\\User', 179),
(7, 'App\\Models\\User', 180),
(7, 'App\\Models\\User', 181),
(7, 'App\\Models\\User', 182),
(7, 'App\\Models\\User', 183),
(7, 'App\\Models\\User', 184),
(7, 'App\\Models\\User', 185),
(7, 'App\\Models\\User', 186),
(7, 'App\\Models\\User', 187),
(7, 'App\\Models\\User', 188),
(7, 'App\\Models\\User', 189),
(7, 'App\\Models\\User', 190),
(7, 'App\\Models\\User', 191),
(7, 'App\\Models\\User', 192),
(7, 'App\\Models\\User', 193),
(7, 'App\\Models\\User', 194),
(7, 'App\\Models\\User', 195),
(7, 'App\\Models\\User', 196),
(7, 'App\\Models\\User', 197),
(7, 'App\\Models\\User', 198),
(7, 'App\\Models\\User', 199),
(7, 'App\\Models\\User', 200),
(7, 'App\\Models\\User', 201),
(7, 'App\\Models\\User', 202),
(7, 'App\\Models\\User', 203),
(7, 'App\\Models\\User', 204),
(7, 'App\\Models\\User', 205),
(7, 'App\\Models\\User', 206),
(7, 'App\\Models\\User', 207),
(7, 'App\\Models\\User', 208),
(7, 'App\\Models\\User', 209),
(7, 'App\\Models\\User', 210),
(7, 'App\\Models\\User', 211),
(7, 'App\\Models\\User', 212),
(7, 'App\\Models\\User', 213),
(7, 'App\\Models\\User', 214),
(7, 'App\\Models\\User', 215),
(7, 'App\\Models\\User', 216),
(7, 'App\\Models\\User', 217),
(7, 'App\\Models\\User', 218),
(7, 'App\\Models\\User', 219),
(7, 'App\\Models\\User', 220),
(7, 'App\\Models\\User', 221),
(7, 'App\\Models\\User', 222),
(7, 'App\\Models\\User', 223),
(7, 'App\\Models\\User', 224),
(7, 'App\\Models\\User', 225),
(7, 'App\\Models\\User', 226),
(7, 'App\\Models\\User', 227),
(7, 'App\\Models\\User', 228),
(7, 'App\\Models\\User', 229),
(7, 'App\\Models\\User', 230),
(7, 'App\\Models\\User', 231),
(7, 'App\\Models\\User', 232),
(7, 'App\\Models\\User', 233),
(7, 'App\\Models\\User', 234),
(7, 'App\\Models\\User', 235),
(7, 'App\\Models\\User', 236),
(7, 'App\\Models\\User', 237),
(7, 'App\\Models\\User', 238),
(7, 'App\\Models\\User', 239),
(7, 'App\\Models\\User', 240),
(7, 'App\\Models\\User', 241),
(7, 'App\\Models\\User', 242),
(7, 'App\\Models\\User', 243),
(7, 'App\\Models\\User', 244),
(7, 'App\\Models\\User', 245),
(7, 'App\\Models\\User', 246),
(7, 'App\\Models\\User', 247),
(7, 'App\\Models\\User', 248),
(7, 'App\\Models\\User', 249),
(7, 'App\\Models\\User', 250),
(7, 'App\\Models\\User', 251),
(7, 'App\\Models\\User', 252),
(7, 'App\\Models\\User', 253),
(7, 'App\\Models\\User', 254),
(7, 'App\\Models\\User', 255),
(7, 'App\\Models\\User', 256),
(7, 'App\\Models\\User', 257),
(7, 'App\\Models\\User', 258),
(7, 'App\\Models\\User', 259),
(7, 'App\\Models\\User', 260),
(7, 'App\\Models\\User', 261),
(7, 'App\\Models\\User', 262),
(7, 'App\\Models\\User', 263),
(7, 'App\\Models\\User', 264),
(7, 'App\\Models\\User', 265),
(7, 'App\\Models\\User', 266),
(7, 'App\\Models\\User', 267),
(7, 'App\\Models\\User', 268),
(7, 'App\\Models\\User', 269),
(7, 'App\\Models\\User', 270),
(7, 'App\\Models\\User', 271),
(7, 'App\\Models\\User', 272),
(7, 'App\\Models\\User', 273),
(7, 'App\\Models\\User', 274),
(7, 'App\\Models\\User', 275),
(7, 'App\\Models\\User', 276),
(7, 'App\\Models\\User', 277),
(7, 'App\\Models\\User', 278),
(7, 'App\\Models\\User', 279),
(7, 'App\\Models\\User', 280),
(7, 'App\\Models\\User', 281),
(7, 'App\\Models\\User', 282),
(7, 'App\\Models\\User', 283),
(7, 'App\\Models\\User', 284),
(7, 'App\\Models\\User', 285),
(7, 'App\\Models\\User', 286),
(7, 'App\\Models\\User', 287),
(7, 'App\\Models\\User', 288),
(7, 'App\\Models\\User', 289),
(7, 'App\\Models\\User', 290),
(7, 'App\\Models\\User', 291),
(7, 'App\\Models\\User', 292),
(7, 'App\\Models\\User', 293),
(7, 'App\\Models\\User', 294),
(7, 'App\\Models\\User', 295),
(7, 'App\\Models\\User', 296),
(7, 'App\\Models\\User', 297),
(7, 'App\\Models\\User', 298),
(7, 'App\\Models\\User', 299),
(7, 'App\\Models\\User', 300),
(7, 'App\\Models\\User', 301),
(7, 'App\\Models\\User', 302),
(7, 'App\\Models\\User', 303),
(7, 'App\\Models\\User', 304),
(7, 'App\\Models\\User', 305),
(7, 'App\\Models\\User', 306),
(7, 'App\\Models\\User', 307),
(7, 'App\\Models\\User', 308),
(7, 'App\\Models\\User', 309),
(7, 'App\\Models\\User', 310),
(7, 'App\\Models\\User', 311),
(7, 'App\\Models\\User', 312),
(7, 'App\\Models\\User', 313),
(7, 'App\\Models\\User', 314),
(7, 'App\\Models\\User', 315),
(7, 'App\\Models\\User', 316),
(7, 'App\\Models\\User', 317),
(7, 'App\\Models\\User', 318),
(7, 'App\\Models\\User', 319),
(7, 'App\\Models\\User', 320),
(7, 'App\\Models\\User', 321),
(7, 'App\\Models\\User', 322),
(7, 'App\\Models\\User', 323),
(7, 'App\\Models\\User', 324),
(7, 'App\\Models\\User', 325),
(7, 'App\\Models\\User', 326),
(7, 'App\\Models\\User', 327),
(7, 'App\\Models\\User', 328),
(7, 'App\\Models\\User', 329),
(7, 'App\\Models\\User', 330),
(7, 'App\\Models\\User', 331),
(7, 'App\\Models\\User', 332),
(7, 'App\\Models\\User', 333),
(7, 'App\\Models\\User', 334),
(7, 'App\\Models\\User', 335),
(7, 'App\\Models\\User', 336),
(7, 'App\\Models\\User', 337),
(7, 'App\\Models\\User', 338),
(7, 'App\\Models\\User', 339),
(7, 'App\\Models\\User', 340),
(7, 'App\\Models\\User', 341),
(7, 'App\\Models\\User', 342),
(7, 'App\\Models\\User', 343),
(7, 'App\\Models\\User', 344),
(7, 'App\\Models\\User', 345),
(7, 'App\\Models\\User', 346),
(7, 'App\\Models\\User', 347),
(7, 'App\\Models\\User', 348),
(7, 'App\\Models\\User', 349),
(7, 'App\\Models\\User', 350),
(7, 'App\\Models\\User', 351),
(7, 'App\\Models\\User', 352),
(7, 'App\\Models\\User', 353),
(7, 'App\\Models\\User', 354),
(7, 'App\\Models\\User', 355),
(7, 'App\\Models\\User', 356),
(7, 'App\\Models\\User', 357),
(7, 'App\\Models\\User', 358),
(7, 'App\\Models\\User', 359),
(7, 'App\\Models\\User', 360),
(7, 'App\\Models\\User', 361),
(7, 'App\\Models\\User', 362),
(7, 'App\\Models\\User', 363),
(7, 'App\\Models\\User', 364),
(7, 'App\\Models\\User', 365),
(7, 'App\\Models\\User', 366),
(7, 'App\\Models\\User', 367),
(7, 'App\\Models\\User', 368),
(7, 'App\\Models\\User', 369),
(7, 'App\\Models\\User', 370),
(7, 'App\\Models\\User', 371),
(7, 'App\\Models\\User', 372),
(7, 'App\\Models\\User', 373),
(7, 'App\\Models\\User', 374),
(7, 'App\\Models\\User', 375),
(7, 'App\\Models\\User', 376),
(7, 'App\\Models\\User', 377),
(7, 'App\\Models\\User', 378),
(7, 'App\\Models\\User', 379),
(7, 'App\\Models\\User', 380),
(7, 'App\\Models\\User', 381),
(7, 'App\\Models\\User', 382),
(7, 'App\\Models\\User', 383),
(7, 'App\\Models\\User', 384),
(7, 'App\\Models\\User', 385),
(7, 'App\\Models\\User', 386),
(7, 'App\\Models\\User', 387),
(7, 'App\\Models\\User', 388),
(7, 'App\\Models\\User', 389),
(7, 'App\\Models\\User', 390),
(7, 'App\\Models\\User', 391),
(7, 'App\\Models\\User', 392),
(7, 'App\\Models\\User', 393),
(7, 'App\\Models\\User', 394),
(7, 'App\\Models\\User', 395),
(7, 'App\\Models\\User', 396),
(7, 'App\\Models\\User', 397),
(7, 'App\\Models\\User', 398),
(7, 'App\\Models\\User', 399),
(7, 'App\\Models\\User', 400),
(7, 'App\\Models\\User', 401),
(7, 'App\\Models\\User', 402),
(7, 'App\\Models\\User', 403),
(7, 'App\\Models\\User', 404),
(7, 'App\\Models\\User', 405),
(7, 'App\\Models\\User', 406),
(7, 'App\\Models\\User', 407),
(7, 'App\\Models\\User', 408),
(7, 'App\\Models\\User', 409),
(7, 'App\\Models\\User', 410),
(7, 'App\\Models\\User', 411),
(7, 'App\\Models\\User', 412),
(7, 'App\\Models\\User', 413),
(7, 'App\\Models\\User', 414),
(7, 'App\\Models\\User', 415),
(7, 'App\\Models\\User', 416),
(7, 'App\\Models\\User', 417),
(7, 'App\\Models\\User', 418),
(7, 'App\\Models\\User', 419),
(7, 'App\\Models\\User', 420),
(7, 'App\\Models\\User', 421),
(7, 'App\\Models\\User', 422),
(7, 'App\\Models\\User', 423),
(7, 'App\\Models\\User', 424),
(7, 'App\\Models\\User', 425),
(7, 'App\\Models\\User', 426),
(7, 'App\\Models\\User', 427),
(7, 'App\\Models\\User', 428),
(7, 'App\\Models\\User', 429),
(7, 'App\\Models\\User', 430),
(7, 'App\\Models\\User', 431),
(7, 'App\\Models\\User', 432),
(7, 'App\\Models\\User', 433),
(7, 'App\\Models\\User', 434),
(7, 'App\\Models\\User', 435),
(7, 'App\\Models\\User', 436),
(7, 'App\\Models\\User', 437),
(7, 'App\\Models\\User', 438),
(7, 'App\\Models\\User', 439),
(7, 'App\\Models\\User', 440),
(7, 'App\\Models\\User', 441),
(7, 'App\\Models\\User', 442),
(7, 'App\\Models\\User', 443),
(7, 'App\\Models\\User', 444),
(7, 'App\\Models\\User', 445),
(7, 'App\\Models\\User', 446),
(7, 'App\\Models\\User', 447),
(7, 'App\\Models\\User', 448),
(7, 'App\\Models\\User', 449),
(7, 'App\\Models\\User', 450),
(7, 'App\\Models\\User', 451),
(7, 'App\\Models\\User', 452),
(7, 'App\\Models\\User', 453),
(7, 'App\\Models\\User', 454),
(7, 'App\\Models\\User', 455),
(7, 'App\\Models\\User', 456),
(7, 'App\\Models\\User', 457),
(7, 'App\\Models\\User', 458),
(7, 'App\\Models\\User', 459),
(7, 'App\\Models\\User', 460),
(7, 'App\\Models\\User', 461),
(7, 'App\\Models\\User', 462),
(7, 'App\\Models\\User', 463),
(7, 'App\\Models\\User', 464),
(7, 'App\\Models\\User', 465),
(7, 'App\\Models\\User', 466),
(7, 'App\\Models\\User', 467),
(7, 'App\\Models\\User', 468),
(7, 'App\\Models\\User', 469),
(7, 'App\\Models\\User', 470),
(7, 'App\\Models\\User', 471),
(7, 'App\\Models\\User', 472),
(7, 'App\\Models\\User', 473),
(7, 'App\\Models\\User', 474),
(7, 'App\\Models\\User', 475),
(7, 'App\\Models\\User', 476),
(7, 'App\\Models\\User', 477),
(7, 'App\\Models\\User', 478),
(7, 'App\\Models\\User', 479),
(7, 'App\\Models\\User', 480),
(7, 'App\\Models\\User', 481),
(7, 'App\\Models\\User', 482),
(7, 'App\\Models\\User', 483),
(7, 'App\\Models\\User', 484),
(7, 'App\\Models\\User', 485),
(7, 'App\\Models\\User', 486),
(7, 'App\\Models\\User', 487),
(7, 'App\\Models\\User', 488),
(7, 'App\\Models\\User', 489),
(7, 'App\\Models\\User', 490),
(7, 'App\\Models\\User', 491),
(7, 'App\\Models\\User', 492),
(7, 'App\\Models\\User', 493),
(7, 'App\\Models\\User', 494),
(7, 'App\\Models\\User', 495),
(7, 'App\\Models\\User', 496),
(7, 'App\\Models\\User', 497),
(7, 'App\\Models\\User', 498),
(7, 'App\\Models\\User', 499),
(7, 'App\\Models\\User', 500),
(7, 'App\\Models\\User', 501),
(7, 'App\\Models\\User', 502),
(7, 'App\\Models\\User', 503),
(7, 'App\\Models\\User', 504),
(7, 'App\\Models\\User', 505),
(7, 'App\\Models\\User', 506),
(7, 'App\\Models\\User', 507),
(7, 'App\\Models\\User', 508),
(7, 'App\\Models\\User', 509),
(7, 'App\\Models\\User', 510),
(7, 'App\\Models\\User', 511),
(7, 'App\\Models\\User', 512),
(7, 'App\\Models\\User', 513),
(7, 'App\\Models\\User', 514),
(7, 'App\\Models\\User', 515),
(7, 'App\\Models\\User', 516),
(7, 'App\\Models\\User', 517),
(7, 'App\\Models\\User', 518),
(7, 'App\\Models\\User', 519),
(7, 'App\\Models\\User', 520),
(7, 'App\\Models\\User', 521),
(7, 'App\\Models\\User', 522),
(7, 'App\\Models\\User', 523),
(7, 'App\\Models\\User', 524),
(7, 'App\\Models\\User', 525),
(7, 'App\\Models\\User', 526),
(7, 'App\\Models\\User', 527),
(7, 'App\\Models\\User', 528),
(7, 'App\\Models\\User', 529),
(7, 'App\\Models\\User', 530),
(7, 'App\\Models\\User', 531),
(7, 'App\\Models\\User', 532),
(7, 'App\\Models\\User', 533),
(7, 'App\\Models\\User', 534),
(7, 'App\\Models\\User', 535),
(7, 'App\\Models\\User', 536),
(7, 'App\\Models\\User', 537),
(7, 'App\\Models\\User', 538),
(7, 'App\\Models\\User', 539),
(7, 'App\\Models\\User', 540),
(7, 'App\\Models\\User', 541),
(7, 'App\\Models\\User', 542),
(7, 'App\\Models\\User', 543),
(7, 'App\\Models\\User', 544),
(7, 'App\\Models\\User', 545),
(7, 'App\\Models\\User', 546),
(7, 'App\\Models\\User', 547),
(7, 'App\\Models\\User', 548),
(7, 'App\\Models\\User', 549),
(7, 'App\\Models\\User', 550),
(7, 'App\\Models\\User', 551),
(7, 'App\\Models\\User', 552),
(7, 'App\\Models\\User', 553),
(7, 'App\\Models\\User', 554),
(7, 'App\\Models\\User', 555),
(7, 'App\\Models\\User', 556),
(7, 'App\\Models\\User', 557),
(7, 'App\\Models\\User', 558),
(7, 'App\\Models\\User', 559),
(7, 'App\\Models\\User', 560),
(7, 'App\\Models\\User', 561),
(7, 'App\\Models\\User', 562),
(7, 'App\\Models\\User', 563),
(7, 'App\\Models\\User', 564),
(7, 'App\\Models\\User', 565),
(7, 'App\\Models\\User', 566),
(7, 'App\\Models\\User', 567),
(7, 'App\\Models\\User', 568),
(7, 'App\\Models\\User', 569),
(7, 'App\\Models\\User', 570),
(7, 'App\\Models\\User', 571),
(7, 'App\\Models\\User', 572),
(7, 'App\\Models\\User', 573),
(7, 'App\\Models\\User', 574),
(7, 'App\\Models\\User', 575),
(7, 'App\\Models\\User', 576),
(7, 'App\\Models\\User', 577),
(7, 'App\\Models\\User', 578),
(7, 'App\\Models\\User', 579),
(7, 'App\\Models\\User', 580),
(7, 'App\\Models\\User', 581),
(7, 'App\\Models\\User', 582),
(7, 'App\\Models\\User', 583),
(7, 'App\\Models\\User', 584),
(7, 'App\\Models\\User', 585),
(7, 'App\\Models\\User', 586),
(7, 'App\\Models\\User', 587),
(7, 'App\\Models\\User', 588),
(7, 'App\\Models\\User', 589),
(7, 'App\\Models\\User', 590),
(7, 'App\\Models\\User', 591),
(7, 'App\\Models\\User', 592),
(7, 'App\\Models\\User', 593),
(7, 'App\\Models\\User', 594);

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_code` varchar(100) NOT NULL,
  `org_type_id` bigint(20) UNSIGNED NOT NULL,
  `org_name` varchar(191) NOT NULL,
  `org_address` text DEFAULT NULL,
  `org_zone_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `org_tambon_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `org_district_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `org_province_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `org_route` varchar(191) DEFAULT NULL,
  `org_zipcode` varchar(10) DEFAULT NULL,
  `org_phone` varchar(191) DEFAULT NULL,
  `org_dept_name` varchar(191) DEFAULT NULL,
  `org_dept_short_name` varchar(191) DEFAULT NULL,
  `org_dept_phone` varchar(191) DEFAULT NULL,
  `org_head_name` varchar(191) DEFAULT NULL,
  `fin_head_name` varchar(191) DEFAULT NULL,
  `tw_head_name` varchar(191) DEFAULT NULL,
  `fin_head_license_img` varchar(191) DEFAULT NULL,
  `org_head_license_img` varchar(191) DEFAULT NULL,
  `tw_head_license_img` varchar(191) DEFAULT NULL,
  `org_logo_img` varchar(191) DEFAULT NULL,
  `meternumbercode` varchar(191) DEFAULT NULL,
  `org_database` varchar(191) DEFAULT NULL,
  `cutmeter_count` int(11) NOT NULL DEFAULT 0,
  `vat` float NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lat` varchar(191) NOT NULL,
  `lng` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `org_code`, `org_type_id`, `org_name`, `org_address`, `org_zone_id_fk`, `org_tambon_id_fk`, `org_district_id_fk`, `org_province_id_fk`, `org_route`, `org_zipcode`, `org_phone`, `org_dept_name`, `org_dept_short_name`, `org_dept_phone`, `org_head_name`, `fin_head_name`, `tw_head_name`, `fin_head_license_img`, `org_head_license_img`, `tw_head_license_img`, `org_logo_img`, `meternumbercode`, `org_database`, `cutmeter_count`, `vat`, `created_at`, `updated_at`, `lat`, `lng`) VALUES
(1, 'HSG1', 1, 'ห้องแซง', '222', 19, 350805, 3508, 35, NULL, '35120', NULL, 'งานประปา', 'ปป.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'hs_logo_circle.png', NULL, 'envsogo_main', 0, 0, '2026-05-19 01:55:38', '2026-05-19 01:55:38', '16.2685302', '104.4011436'),
(2, 'TNG1', 5, 'เต่างอย', '80', 21, 471401, 4714, 47, NULL, '47260', NULL, 'งานประปา', 'ปป.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'tng_logo.png', NULL, 'envoso_hs1', 0, 0, '2026-05-19 01:55:38', '2026-05-19 01:55:38', '17.2916162', '104.1129242'),
(3, 'KUCSC', 6, 'เกษตรศาสตร์ วิทยาเขตเฉลิมพระเกียรติ จ.สกลนคร', '59 หมู่ 1 ', 21, 470106, 4701, 47, NULL, '47000', NULL, 'สำนักวิทยบริการ', 'สนว.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ku_csc.png', NULL, 'envoso_main', 0, 0, '2026-05-19 01:55:38', '2026-05-19 01:55:38', '17.2916162', '104.1129242'),
(4, 'SWAT1', 2, 'สวาท', 'ถนนสวาท-หนองแสง', 19, 350803, 3508, 35, NULL, '35120', NULL, 'งานประปา', 'ปป.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'swat.png', NULL, 'envsogo_main', 0, 0, '2026-05-19 01:55:38', '2026-10-31 03:16:08', '16.18967', '104.51682');

-- --------------------------------------------------------

--
-- Table structure for table `organization_types`
--

CREATE TABLE `organization_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL COMMENT 'เช่น เทศบาลตำบล, อบต., เทศบาลเมือง',
  `code` varchar(191) DEFAULT NULL COMMENT 'รหัสย่อถ้ามี',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_types`
--

INSERT INTO `organization_types` (`id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'เทศบาลตำบล', 'ทต.', 1, '2026-05-19 01:13:08', '2026-05-19 01:13:08'),
(2, 'องค์การบริหารส่วนตำบล', 'อบต.', 1, '2026-05-19 01:13:08', '2026-05-19 01:13:08'),
(3, 'เทศบาลเมือง', 'ทม.', 1, '2026-05-19 01:13:08', '2026-05-19 01:13:08'),
(4, 'โรงเรียน', 'รร.', 1, '2026-05-19 01:13:08', '2026-05-19 01:13:08'),
(5, 'โรงพยาบาล', 'รพ.', 1, '2026-05-19 01:13:08', '2026-05-19 01:13:08'),
(6, 'มหาวิทยาลัย', 'ม.', 1, '2026-05-19 01:13:08', '2026-05-19 01:13:08');

-- --------------------------------------------------------

--
-- Table structure for table `org_accounts`
--

CREATE TABLE `org_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `acc_number` varchar(191) NOT NULL,
  `tabwater_balance` float NOT NULL DEFAULT 0,
  `annaul_balance` float NOT NULL DEFAULT 0,
  `recycle_bank_balance` float NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `org_invoice_sequences`
--

CREATE TABLE `org_invoice_sequences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `year` smallint(5) UNSIGNED NOT NULL,
  `last_running_no` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `org_invoice_sequences`
--

INSERT INTO `org_invoice_sequences` (`id`, `org_id_fk`, `year`, `last_running_no`, `created_at`, `updated_at`) VALUES
(1, 1, 2026, 3, '2026-10-27 22:33:35', '2026-10-27 22:57:36'),
(2, 4, 2026, 879, '2026-10-28 22:47:26', '2026-11-04 18:43:56');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'access recycle bank', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(2, 'access recycle bank mobile', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(3, 'access annual fee', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(4, 'access tabwater', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(5, 'access tabwater mobile', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(6, 'access food waste', 'web', '2026-07-15 00:32:59', '2026-07-15 00:32:59'),
(7, 'access to settings', 'web', '2026-08-16 19:03:02', '2026-08-16 19:03:02');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `province_name` varchar(191) NOT NULL,
  `go_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `province_name`, `go_id`, `created_at`, `updated_at`) VALUES
(34, 'อุบลราชธานี', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(35, 'ยโสธร', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(36, 'ชัยภูมิ', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(37, 'อำนาจเจริญ', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(39, 'หนองบัวลำภู', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(40, 'ขอนแก่น', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(41, 'อุดรธานี', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(42, 'เลย', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(43, 'หนองคาย', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(44, 'มหาสารคาม', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(45, 'ร้อยเอ็ด', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(46, 'กาฬสินธุ์', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(47, 'สกลนคร', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(48, 'นครพนม', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36'),
(97, 'บึงกาฬ', 3, '2026-05-19 01:39:36', '2026-05-19 01:39:36');

-- --------------------------------------------------------

--
-- Table structure for table `recycle_transactions`
--

CREATE TABLE `recycle_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('deposit','withdraw') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `weight_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(2, 'Admin', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(3, 'Tabwater Header', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(4, 'Tabwater Staff', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(5, 'Finance Header', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(6, 'Finance Staff', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(7, 'User', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(8, 'Recycle Bank Staff', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(9, 'Annual Fee Staff', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(10, 'Food Waste Staff', 'web', '2026-07-15 00:32:32', '2026-07-15 00:32:32'),
(11, 'Recycle Bank User', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(12, 'Food Waste User', 'web', '2026-07-15 00:32:32', '2026-07-15 00:32:32'),
(13, 'Tabwater User', 'web', '2026-07-15 00:32:32', '2026-07-15 00:32:32'),
(14, 'Annual Fee User', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54'),
(15, 'Staff', 'web', '2026-05-19 02:04:54', '2026-05-19 02:04:54');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 2),
(2, 2),
(4, 2),
(7, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sequence_number`
--

CREATE TABLE `sequence_number` (
  `id` int(11) NOT NULL,
  `tabmeter` int(11) NOT NULL,
  `user` int(11) NOT NULL COMMENT 'ผู้ใช้น้ำเท่านั้น',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `values` text DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `values`, `comment`, `created_at`, `updated_at`) VALUES
(1, 'organization', '{\"organization_name\":\"\\u0e40\\u0e17\\u0e28\\u0e1a\\u0e32\\u0e25\\u0e15\\u0e33\\u0e1a\\u0e25\\u0e2a\\u0e27\\u0e32\\u0e17\",\"organization_short_name\":\"\\u0e17\\u0e15.\\u0e2a\\u0e27\\u0e32\\u0e17\",\"organize_address\":\"99\",\"organize_zone\":\"\\u0e2b\\u0e21\\u0e39\\u0e48 19\",\"organize_road\":\"\\u0e1e\\u0e23\\u0e30\\u0e22\\u0e37\\u0e19-\\u0e42\\u0e1e\\u0e19\\u0e17\\u0e2d\\u0e07\",\"organize_tambon\":\"\\u0e02\\u0e32\\u0e21\\u0e1b\\u0e49\\u0e2d\\u0e21\",\"organize_district\":\"\\u0e1e\\u0e23\\u0e30\\u0e22\\u0e37\\u0e19\",\"organize_province\":\"\\u0e02\\u0e2d\\u0e19\\u0e41\\u0e01\\u0e48\\u0e19\",\"organize_zipcode\":\"40003\",\"organize_phone\":\"0452394234\",\"department_name\":\"\\u0e07\\u0e32\\u0e19\\u0e1b\\u0e23\\u0e30\\u0e1b\\u0e32\\u0e40\\u0e17\\u0e28\\u0e1a\\u0e32\\u0e25\\u0e15\\u0e33\\u0e1a\\u0e25\\u0e2a\\u0e27\\u0e32\\u0e17\",\"department_short_name\":\"\\u0e1b\\u0e1b.\\u0e02\\u0e32\\u0e21\\u0e1b\\u0e49\\u0e2d\\u0e21\",\"department_phone\":null,\"organize_email\":\"hs_tabwater@gmail.com\"}', NULL, '2026-10-27 01:57:31', '2026-10-27 01:57:31'),
(2, 'meternumber_code', NULL, NULL, '2026-10-27 01:57:31', '2026-10-27 01:57:31'),
(3, 'owe_count', '0', NULL, '2026-10-27 01:57:31', '2026-10-27 01:57:31'),
(4, 'payment_expired_date', NULL, NULL, '2026-10-27 01:57:31', '2026-10-27 01:57:31');

-- --------------------------------------------------------

--
-- Table structure for table `smart_utility_configs`
--

CREATE TABLE `smart_utility_configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `org_name` varchar(191) NOT NULL DEFAULT 'อบต.เสม็ดใต้',
  `area_name` varchar(191) DEFAULT NULL,
  `elec_prefix` varchar(10) NOT NULL DEFAULT 'SMT',
  `water_prefix` varchar(10) NOT NULL DEFAULT 'WTR',
  `road_prefix` varchar(10) NOT NULL DEFAULT 'RD',
  `bldg_prefix` varchar(10) NOT NULL DEFAULT 'BD',
  `center_lat` decimal(10,6) NOT NULL DEFAULT 13.690000,
  `center_lng` decimal(10,6) NOT NULL DEFAULT 101.200000,
  `staff_pass` varchar(191) NOT NULL DEFAULT '3350553',
  `extra` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `smart_utility_configs`
--

INSERT INTO `smart_utility_configs` (`id`, `org_id_fk`, `org_name`, `area_name`, `elec_prefix`, `water_prefix`, `road_prefix`, `bldg_prefix`, `center_lat`, `center_lng`, `staff_pass`, `extra`, `created_at`, `updated_at`) VALUES
(1, 1, 'ห้องแซง', '222', 'SMT', 'WTR', 'RD', 'BD', 16.268530, 104.401144, '3350553', NULL, '2026-10-29 08:10:05', '2026-10-29 08:10:05'),
(2, 4, 'สวาท', 'ถนนสวาท-หนองแสง', 'SMT', 'WTR', 'RD', 'BD', 16.189670, 104.516820, '3350553', NULL, '2026-10-29 08:10:53', '2026-10-29 08:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `smart_utility_items`
--

CREATE TABLE `smart_utility_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `module` varchar(20) NOT NULL,
  `item_code` varchar(50) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `item_type` varchar(50) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `village` varchar(50) DEFAULT NULL,
  `lat` decimal(10,6) NOT NULL,
  `lng` decimal(10,6) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'ok',
  `condition` varchar(30) DEFAULT NULL,
  `year_be` varchar(10) DEFAULT NULL,
  `area_sqm` decimal(12,2) DEFAULT NULL,
  `value_baht` decimal(14,2) DEFAULT NULL,
  `owner` varchar(191) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `smart_utility_items`
--

INSERT INTO `smart_utility_items` (`id`, `org_id_fk`, `module`, `item_code`, `name`, `item_type`, `category`, `village`, `lat`, `lng`, `status`, `condition`, `year_be`, `area_sqm`, `value_baht`, `owner`, `note`, `meta`, `created_at`, `updated_at`) VALUES
(1, 4, 'elec', 'SMT-001', NULL, 'pole', NULL, '1', 16.189353, 104.516140, 'ok', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 08:12:21', '2026-10-29 08:12:21'),
(2, 4, 'elec', 'SMT-002', NULL, 'pole', NULL, '1', 16.194522, 104.514786, 'ok', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 08:12:23', '2026-10-29 08:48:44'),
(4, 4, 'elec', 'ELEC-0003', NULL, NULL, NULL, NULL, 16.189660, 104.516802, 'good', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 08:36:36', '2026-10-29 08:36:36'),
(5, 4, 'elec', 'ELEC-0004', NULL, NULL, NULL, NULL, 16.189849, 104.511504, 'good', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 08:38:31', '2026-10-29 08:38:31'),
(6, 4, 'elec', 'ELEC-0005', NULL, NULL, NULL, NULL, 16.191993, 104.513567, 'good', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 08:38:45', '2026-10-29 08:38:45'),
(7, 4, 'elec', 'ELEC-0006', NULL, NULL, NULL, NULL, 16.191993, 104.513567, 'good', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 08:38:56', '2026-10-29 08:38:56'),
(8, 4, 'elec', 'ELEC-0007', NULL, NULL, NULL, NULL, 20.192873, 104.513546, 'good', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 08:39:06', '2026-10-29 08:39:25'),
(9, 4, 'elec', 'ELEC-0008', NULL, NULL, NULL, NULL, 16.194165, 104.517637, 'good', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 08:39:48', '2026-10-29 08:39:48'),
(10, 4, 'elec', 'ELEC-0009', 'เสาไฟคอนกรีต 8ม.', 'เสาไฟคอนกรีต 8ม.', NULL, NULL, 16.195749, 104.517028, 'good', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 08:48:19', '2026-10-29 08:48:19'),
(11, 4, 'elec', 'ELEC-0010', 'เสาไฟคอนกรีต 8ม.', 'เสาไฟคอนกรีต 8ม.', NULL, NULL, 16.195656, 104.520235, 'good', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 08:48:30', '2026-10-29 08:48:30'),
(12, 4, 'water', 'WATER-0001', 'มิเตอร์น้ำ 1/2 นิ้ว', 'มิเตอร์น้ำ 1/2 นิ้ว', NULL, NULL, 16.186760, 104.516587, 'good', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 09:01:47', '2026-10-29 09:01:47'),
(13, 4, 'water', 'WATER-0002', 'มิเตอร์น้ำ 1/2 นิ้ว', 'มิเตอร์น้ำ 1/2 นิ้ว', NULL, NULL, 16.191806, 104.517145, 'good', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-29 09:01:52', '2026-10-29 09:01:52');

-- --------------------------------------------------------

--
-- Table structure for table `smart_utility_shapes`
--

CREATE TABLE `smart_utility_shapes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `module` varchar(20) NOT NULL,
  `shape_type` varchar(30) NOT NULL,
  `kind` varchar(50) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `coordinates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`coordinates`)),
  `radius` double(8,2) DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smart_utility_tickets`
--

CREATE TABLE `smart_utility_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `module` varchar(20) NOT NULL,
  `ticket_code` varchar(30) NOT NULL,
  `ref_code` varchar(50) DEFAULT NULL,
  `village` varchar(50) DEFAULT NULL,
  `location` varchar(191) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `lat` decimal(10,6) DEFAULT NULL,
  `lng` decimal(10,6) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'new',
  `cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `photos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`photos`)),
  `messages` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`messages`)),
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`id`, `org_id_fk`, `user_id`, `status`, `deleted`, `created_at`, `updated_at`) VALUES
(1, 1, 2859, 'active', '0', NULL, '2024-03-25 09:43:14'),
(2, 1, 2860, 'active', '0', NULL, '2024-03-25 09:43:14'),
(3, 1, 2878, 'active', '0', '2021-06-15 19:38:21', '2026-10-27 02:40:08'),
(4, 1, 2880, 'active', '0', '2021-06-15 23:32:43', '2026-10-27 00:57:37'),
(5, 1, 2881, 'active', '0', '2021-06-15 23:41:15', '2026-10-22 05:12:13'),
(6, 1, 2882, 'active', '0', '2021-06-15 23:46:15', '2026-10-26 01:43:02'),
(7, 1, 2883, 'active', '0', '2021-06-15 23:48:56', '2026-10-27 01:53:33'),
(8, 1, 2884, 'active', '0', '2021-06-16 00:24:08', '2026-10-23 08:58:28'),
(9, 1, 2885, 'active', '0', '2021-06-16 00:26:57', '2026-10-26 01:57:03'),
(10, 1, 2886, 'active', '0', '2021-06-16 00:28:18', '2026-10-26 02:26:53'),
(11, 1, 2887, 'active', '0', '2021-06-16 00:30:28', '2026-10-27 08:24:36'),
(12, 1, 2905, 'active', '0', '2021-07-22 19:40:56', '2024-03-25 09:43:14'),
(13, 1, 2906, 'active', '0', '2021-07-27 02:00:19', '2024-03-25 10:02:28'),
(14, 1, 2999, 'active', '0', '2021-09-07 01:53:10', '2024-03-25 10:02:28'),
(15, 1, 3009, 'active', '0', '2021-09-13 15:25:59', '2024-03-25 09:43:14'),
(16, 1, 3141, 'active', '0', '2022-06-02 03:10:00', '2024-03-25 09:43:14'),
(17, 1, 3183, 'active', '0', '2022-11-21 03:05:13', '2026-10-22 03:34:42'),
(18, 1, 3335, 'active', '0', '2023-04-27 02:53:44', '2024-03-25 10:02:28'),
(19, 1, 3336, 'active', '0', '2023-04-27 02:53:53', '2024-03-25 10:02:28'),
(20, 1, 3411, 'active', '0', '2023-07-16 11:17:14', '2024-03-25 09:43:14'),
(21, 1, 3677, 'active', '0', '2025-04-29 04:40:37', '2025-04-28 21:48:56'),
(22, 1, 3868, 'active', '0', NULL, NULL),
(23, 4, 480, 'active', '0', NULL, '2024-03-25 09:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `subzones`
--

CREATE TABLE `subzones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `subzone_name` varchar(191) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subzones`
--

INSERT INTO `subzones` (`id`, `org_id_fk`, `zone_id`, `subzone_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'หมู่1', 'active', NULL, NULL),
(2, 1, 2, 'หมู่2', 'active', NULL, NULL),
(3, 1, 3, 'หมู่3', 'active', NULL, NULL),
(4, 1, 4, 'หมู่4', 'active', NULL, NULL),
(5, 1, 5, 'หมู่5', 'active', NULL, NULL),
(6, 1, 6, 'หมู่6', 'active', NULL, NULL),
(7, 1, 7, 'หมู่ 7', 'active', NULL, NULL),
(8, 1, 8, 'หมู่8', 'active', NULL, NULL),
(9, 1, 9, 'หมู่9', 'active', NULL, NULL),
(10, 1, 10, 'หมู่10', 'active', NULL, NULL),
(11, 1, 11, 'หมู่11', 'active', NULL, NULL),
(12, 1, 12, 'หมู่12', 'active', NULL, NULL),
(14, 1, 14, 'หมู่14', 'active', NULL, NULL),
(15, 1, 15, 'หมู่15', 'active', NULL, NULL),
(16, 1, 16, 'หมู่16', 'active', NULL, NULL),
(17, 1, 17, 'หมู่17', 'active', NULL, NULL),
(18, 1, 18, 'หมู่18', 'active', NULL, NULL),
(19, 1, 19, 'หมู่19', 'active', NULL, NULL),
(20, 1, 13, 'เส้น 13-1', 'active', NULL, NULL),
(21, 1, 13, 'เส้น 13-2', 'active', NULL, NULL),
(35, 1, 3, 'หมู่3-1', 'active', NULL, NULL),
(36, 4, 20, 'หมู่ 8', 'active', NULL, NULL),
(37, 4, 21, 'หมู่ 7', 'active', '2026-10-28 22:02:07', '2026-10-28 22:02:07'),
(39, 4, 23, 'หมู่ 15', 'active', '2026-10-28 22:02:40', '2026-10-28 22:02:40'),
(41, 4, 25, 'หมู่ 16 ', 'active', '2026-10-28 22:02:40', '2026-10-28 22:02:40');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `department` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `contact_person` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_number` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `org_id_fk`, `department`, `name`, `contact_person`, `phone`, `address`, `tax_number`, `created_at`, `updated_at`) VALUES
(1, 1, 'งานประปา', 'แสงทองเคมีภัณฑ์', 'คุณแสงทอง', '0533233232', 'บ้านสามแยก อำเภอเลิงนกทา ยโสธร', NULL, '2026-10-19 08:54:58', '2026-10-19 08:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `system_modules`
--

CREATE TABLE `system_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_type` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `icon` varchar(191) NOT NULL DEFAULT '?',
  `description` text DEFAULT NULL,
  `color` varchar(191) NOT NULL DEFAULT 'primary',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_modules`
--

INSERT INTO `system_modules` (`id`, `system_type`, `title`, `icon`, `description`, `color`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'water', 'งานระบบน้ำประปา', '💧', 'แจ้งท่อแตก น้ำไม่ไหล น้ำขุ่น หรือมาตรวัดน้ำชำรุด', 'primary', 1, 1, '2026-08-25 19:00:21', '2026-08-25 19:00:21'),
(2, 'recycle_trash', 'ธนาคารขยะรีไซเคิล', '♻️', 'นัดรถรับซื้อขยะ สมุดคู่ฝาก หรือแจ้งถังขยะเต็ม', 'success', 1, 2, '2026-08-25 19:00:21', '2026-08-25 19:00:21'),
(3, 'food_waste', 'ธนาคารขยะเศษอาหาร', '🍃', 'แจ้งปัญหาถังหมักมีกลิ่น เติมปุ๋ย หรืออุปกรณ์ชำรุด', 'warning', 1, 3, '2026-08-25 19:00:21', '2026-08-25 19:00:21'),
(4, 'public_health', 'งานสาธารณสุข / ไฟฟ้า', '💡', 'แจ้งไฟถนนดับ ถังขยะทั่วไปล้น หรือเหตุรำคาญ', 'danger', 1, 4, '2026-08-25 19:00:21', '2026-08-25 19:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `tabwater_devices`
--

CREATE TABLE `tabwater_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `station_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> tabwater_stations.id',
  `device_code` varchar(50) NOT NULL COMMENT 'รหัสอุปกรณ์ เช่น PUMP-RAW-01',
  `name` varchar(191) NOT NULL COMMENT 'ชื่ออุปกรณ์ เช่น ปั๊มน้ำดิบ 5 กม., วาล์วถังใส',
  `type` enum('pump_raw','pump_clear','dosing_pump','electric_valve','relay') NOT NULL DEFAULT 'pump_raw',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'สถานะปัจจุบัน (0=ปิด, 1=เปิด)',
  `control_mode` enum('auto','manual') NOT NULL DEFAULT 'auto' COMMENT 'โหมด Auto หรือ Manual',
  `auto_control_enabled` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'เปิด/ปิดการตัดอัตโนมัติ',
  `mqtt_topic` varchar(191) DEFAULT NULL COMMENT 'Topic สำหรับสั่งงาน IoT',
  `relay_channel` int(11) DEFAULT NULL COMMENT 'ช่อง Relay',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabwater_device_logs`
--

CREATE TABLE `tabwater_device_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> tabwater_devices.id',
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ผู้สั่งงาน (NULL กรณี Auto System สั่ง)',
  `action` enum('ON','OFF') NOT NULL COMMENT 'คำสั่งเปิด หรือ ปิด',
  `mode` enum('auto','manual') NOT NULL DEFAULT 'manual' COMMENT 'สั่งด้วยโหมด Auto หรือ Manual',
  `trigger_reason` varchar(191) NOT NULL COMMENT 'สาเหตุ เช่น ระดับน้ำเต็ม 95% (Auto), นายสมชาย สั่งปิด (Manual)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabwater_pipelines`
--

CREATE TABLE `tabwater_pipelines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `station_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> tabwater_stations.id',
  `pipe_code` varchar(50) NOT NULL COMMENT 'รหัสเส้นท่อ เช่น PIPE-M01',
  `name` varchar(191) NOT NULL COMMENT 'ชื่อสายท่อ',
  `from_node_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> tabwater_pipe_nodes.id',
  `to_node_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> tabwater_pipe_nodes.id',
  `level` enum('main','sub_main','distribution') NOT NULL DEFAULT 'main' COMMENT 'ท่อเมน / ท่อรอง / ท่อซอย',
  `size_mm` int(11) NOT NULL DEFAULT 110 COMMENT 'ขนาดท่อ (มม.) เช่น 110, 63, 32',
  `material` varchar(50) NOT NULL DEFAULT 'PE' COMMENT 'ประเภทวัสดุ เช่น PE, PVC, STEEL',
  `length_m` decimal(8,2) DEFAULT NULL COMMENT 'ความยาวท่อ (เมตร)',
  `path_coordinates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'พิกัดแนวท่อผ่าน' CHECK (json_valid(`path_coordinates`)),
  `deleted` enum('0','1') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabwater_pipe_nodes`
--

CREATE TABLE `tabwater_pipe_nodes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `station_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> tabwater_stations.id (สถานีต้นทาง)',
  `node_code` varchar(50) NOT NULL COMMENT 'รหัสจุด เช่น JNC-01',
  `name` varchar(191) NOT NULL COMMENT 'ชื่อจุดเชื่อมต่อ/จุดกระจายน้ำ',
  `type` enum('station','main_junction','sub_junction','valve_point','tank','end_point') NOT NULL DEFAULT 'main_junction' COMMENT 'ประเภทโหนด',
  `lat` decimal(10,7) NOT NULL COMMENT 'ละติจูด',
  `lng` decimal(10,7) NOT NULL COMMENT 'ลองจิจูด',
  `remark` text DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tabwater_pipe_nodes`
--

INSERT INTO `tabwater_pipe_nodes` (`id`, `station_id`, `node_code`, `name`, `type`, `lat`, `lng`, `remark`, `deleted`, `created_at`, `updated_at`) VALUES
(1, 3, 'JNC-01', 'โหนด 1', 'sub_junction', 16.2739140, 104.3946650, NULL, '0', '2026-10-18 06:43:27', '2026-10-18 06:43:27'),
(2, 4, 'JNC-02', 'โหนด 2', 'sub_junction', 16.2749860, 104.3887850, NULL, '0', '2026-10-18 06:43:27', '2026-10-18 06:43:27'),
(3, 5, 'JNC-03', 'โหนด 3', 'sub_junction', 16.2707010, 104.4073680, NULL, '0', '2026-10-18 06:43:27', '2026-10-18 06:43:27'),
(4, 3, 'JNC-04', 'โหนด 4', 'sub_junction', 16.2646860, 104.3968960, NULL, '0', '2026-10-18 06:43:27', '2026-10-18 06:43:27'),
(5, 3, 'N01', 'qq', 'main_junction', 16.2687086, 104.3976045, NULL, '0', '2026-10-29 07:48:39', '2026-10-29 07:48:39'),
(6, 3, 'N01', 'qqq', 'main_junction', 16.2654133, 104.3975830, NULL, '0', '2026-10-29 07:48:50', '2026-10-29 07:48:50'),
(7, 3, 'N01', 'qq', 'main_junction', 16.2710152, 104.3989778, NULL, '0', '2026-10-29 08:05:25', '2026-10-29 08:05:25');

-- --------------------------------------------------------

--
-- Table structure for table `tabwater_stations`
--

CREATE TABLE `tabwater_stations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL COMMENT 'รหัสเทศบาล/องค์กร',
  `code` varchar(50) DEFAULT NULL COMMENT 'รหัสสถานี เช่น ST-01',
  `name` varchar(191) NOT NULL COMMENT 'ชื่อสถานีผลิตน้ำ',
  `lat` decimal(10,7) DEFAULT NULL COMMENT 'พิกัด ละติจูด',
  `lng` decimal(10,7) DEFAULT NULL COMMENT 'พิกัด ลองจิจูด',
  `max_turbidity_ntu` decimal(5,2) NOT NULL DEFAULT 5.00 COMMENT 'เกณฑ์ความขุ่นวิกฤต (NTU)',
  `min_ph` decimal(3,1) NOT NULL DEFAULT 6.5 COMMENT 'ค่า pH ต่ำสุด',
  `max_ph` decimal(3,1) NOT NULL DEFAULT 8.5 COMMENT 'ค่า pH สูงสุด',
  `max_tds_ppm` decimal(6,2) NOT NULL DEFAULT 300.00 COMMENT 'เกณฑ์ TDS สูงสุด',
  `min_water_level_percent` decimal(5,2) NOT NULL DEFAULT 30.00 COMMENT 'ระดับน้ำต่ำเตือนเร่งสูบ (%)',
  `max_water_level_percent` decimal(5,2) NOT NULL DEFAULT 95.00 COMMENT 'ระดับน้ำสูงเตือนตัดปั๊ม (%)',
  `secchi_k_factor` decimal(6,2) NOT NULL DEFAULT 350.00 COMMENT 'ค่า K สำหรับคำนวณไม้จุ่ม',
  `status` enum('normal','warning','danger') NOT NULL DEFAULT 'normal',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tabwater_stations`
--

INSERT INTO `tabwater_stations` (`id`, `org_id_fk`, `code`, `name`, `lat`, `lng`, `max_turbidity_ntu`, `min_ph`, `max_ph`, `max_tds_ppm`, `min_water_level_percent`, `max_water_level_percent`, `secchi_k_factor`, `status`, `deleted`, `created_at`, `updated_at`) VALUES
(3, 1, 'station1', 'โรงน้ำ 1', 16.2685302, 104.4011436, 5.00, 6.5, 8.5, 300.00, 30.00, 95.00, 350.00, 'normal', 0, '2026-10-18 06:31:47', '2026-10-18 06:31:47'),
(4, 1, 'station2', 'โรงผลิตน้ำ2', 16.2733650, 104.4117670, 5.00, 6.5, 8.5, 300.00, 30.00, 95.00, 350.00, 'normal', 0, '2026-10-18 06:40:03', '2026-10-18 06:40:03'),
(5, 1, 'station3', 'โรงผลิตน้ำ3', 16.2781980, 104.3884850, 5.00, 6.5, 8.5, 300.00, 30.00, 95.00, 350.00, 'normal', 0, '2026-10-18 06:41:04', '2026-10-18 06:41:04');

-- --------------------------------------------------------

--
-- Table structure for table `tabwater_station_staffs`
--

CREATE TABLE `tabwater_station_staffs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `station_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> tabwater_stations.id',
  `staff_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> staffs.id',
  `role` enum('primary','secondary') NOT NULL DEFAULT 'secondary' COMMENT 'ระดับการรับผิดชอบ หลัก/รอง',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tabwater_station_staffs`
--

INSERT INTO `tabwater_station_staffs` (`id`, `station_id`, `staff_id`, `role`, `created_at`, `updated_at`) VALUES
(1, 4, 18, 'secondary', '2026-10-18 06:40:03', '2026-10-18 06:40:03'),
(2, 5, 16, 'secondary', '2026-10-18 06:41:04', '2026-10-18 06:41:04');

-- --------------------------------------------------------

--
-- Table structure for table `tambons`
--

CREATE TABLE `tambons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tambon_name` varchar(191) NOT NULL,
  `district_id` bigint(20) UNSIGNED NOT NULL,
  `zipcode` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tambons`
--

INSERT INTO `tambons` (`id`, `tambon_name`, `district_id`, `zipcode`, `created_at`, `updated_at`) VALUES
(340501, 'เขมราฐ', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340502, 'กองโพน', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340503, 'ขามป้อม', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340504, 'เจียด', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340505, 'พังเคน', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340506, 'นาตาล', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340507, 'หนองผือ', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340508, 'นาแวง', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340509, 'พะลาน', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340510, 'แก้งเหนือ', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340511, 'หนองนกทา', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340512, 'หนองสิม', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(340513, 'หัวนา', 3405, '34170', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(350803, 'สวาท', 3508, '35120', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(350805, 'ห้องแซง', 3508, '35120', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(350806, 'สามัคคี', 3508, '35120', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(350807, 'กุดเชียงหมี', 3508, '35120', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470101, 'ธาตุเชิงชุม', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470102, 'ขมิ้น', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470103, 'งิ้วด่อน', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470104, 'โนนหอม', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470105, 'นาตงวัฒนา', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470106, 'เชียงเครือ', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470107, 'ท่าแร่', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470108, 'บ้านโพน', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470109, 'ม่วงลาย', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470110, 'กกปลาซิว', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470111, 'ดงชน', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470112, 'ห้วยยาง', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470113, 'พังขว้าง', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470114, 'นาแก้ว', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470115, 'ดงมะไฟ', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470116, 'ธาตุนาเวง', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470117, 'เหล่าปอแดง', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470118, 'หนองลาด', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470119, 'บ้านแป้น', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470120, 'ฮางโฮง', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(470121, 'โคกก่อง', 4701, '47000', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(471401, 'เต่างอย', 4714, '47260', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(471402, 'บึงทวาย', 4714, '47260', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(471403, 'นาตาล', 4714, '47260', '2026-05-19 01:53:16', '2026-05-19 01:53:16'),
(471404, 'จันทร์เพ็ญ', 4714, '47260', '2026-05-19 01:53:16', '2026-05-19 01:53:16');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `waste_bank_transaction_id` bigint(20) UNSIGNED NOT NULL,
  `waste_type_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_used` varchar(191) NOT NULL,
  `member_price_per_unit` decimal(10,2) NOT NULL,
  `total_member_amount` decimal(10,2) NOT NULL,
  `factory_price_per_unit` decimal(10,2) NOT NULL,
  `total_factory_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tw_acc_transactions`
--

CREATE TABLE `tw_acc_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) DEFAULT NULL,
  `meter_id_fk` bigint(20) UNSIGNED NOT NULL,
  `paidsum` float NOT NULL DEFAULT 0,
  `vatsum` float NOT NULL DEFAULT 0,
  `reserve_meter_sum` float NOT NULL DEFAULT 0,
  `totalpaidsum` float NOT NULL DEFAULT 0,
  `status` enum('1','0') NOT NULL COMMENT '1= active, 0=deleted',
  `cashier` bigint(20) UNSIGNED NOT NULL COMMENT 'ผู้รับเงิน',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_acc_transactions`
--

INSERT INTO `tw_acc_transactions` (`id`, `org_id_fk`, `meter_id_fk`, `paidsum`, `vatsum`, `reserve_meter_sum`, `totalpaidsum`, `status`, `cashier`, `created_at`, `updated_at`) VALUES
(1, NULL, 8001, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(2, NULL, 8002, 72, 0, 0, 72, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(3, NULL, 8003, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(4, NULL, 8004, 24, 0, 0, 24, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(5, NULL, 8005, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(6, NULL, 8006, 8, 0, 0, 8, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(7, NULL, 8007, 48, 0, 0, 48, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(8, NULL, 8008, 248, 0, 0, 248, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(9, NULL, 8009, 16, 0, 0, 16, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(10, NULL, 8010, 24, 0, 0, 24, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(11, NULL, 8011, 112, 0, 0, 112, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(12, NULL, 8012, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(13, NULL, 8013, 32, 0, 0, 32, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(14, NULL, 8014, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(15, NULL, 8015, 8, 0, 0, 8, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(16, NULL, 8016, 152, 0, 0, 152, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(17, NULL, 8017, 104, 0, 0, 104, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(18, NULL, 8018, 104, 0, 0, 104, '1', 2, '2026-11-02 23:05:05', '2026-11-02 23:05:05'),
(19, NULL, 8020, 128, 0, 0, 128, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(20, NULL, 8022, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(21, NULL, 8023, 24, 0, 0, 24, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(22, NULL, 8025, 48, 0, 0, 48, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(23, NULL, 8028, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(24, NULL, 8029, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(25, NULL, 8030, 16, 0, 0, 16, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(26, NULL, 8031, 32, 0, 0, 32, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(27, NULL, 8032, 16, 0, 0, 16, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(28, NULL, 8036, 88, 0, 0, 88, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(29, NULL, 8037, 56, 0, 0, 56, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(30, NULL, 8038, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(31, NULL, 8039, 56, 0, 0, 56, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(32, NULL, 8041, 40, 0, 0, 40, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(33, NULL, 8042, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(34, NULL, 8043, 48, 0, 0, 48, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(35, NULL, 8046, 48, 0, 0, 48, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(36, NULL, 8047, 136, 0, 0, 136, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(37, NULL, 8048, 40, 0, 0, 40, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(38, NULL, 8049, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(39, NULL, 8050, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(40, NULL, 8051, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(41, NULL, 8052, 192, 0, 0, 192, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(42, NULL, 8053, 24, 0, 0, 24, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(43, NULL, 8054, 72, 0, 0, 72, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(44, NULL, 8057, 192, 0, 0, 192, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(45, NULL, 8058, 0, 0, 0, 0, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(46, NULL, 8061, 120, 0, 0, 120, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(47, NULL, 8062, 48, 0, 0, 48, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(48, NULL, 8063, 48, 0, 0, 48, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(49, NULL, 8064, 24, 0, 0, 24, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(50, NULL, 8065, 88, 0, 0, 88, '1', 2, '2026-11-02 23:05:06', '2026-11-02 23:05:06'),
(51, NULL, 8066, 80, 0, 0, 80, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(52, NULL, 8067, 104, 0, 0, 104, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(53, NULL, 8069, 136, 0, 0, 136, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(54, NULL, 8070, 0, 0, 0, 0, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(55, NULL, 8071, 24, 0, 0, 24, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(56, NULL, 8072, 64, 0, 0, 64, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(57, NULL, 8073, 56, 0, 0, 56, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(58, NULL, 8074, 0, 0, 0, 0, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(59, NULL, 8075, 24, 0, 0, 24, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(60, NULL, 8077, 152, 0, 0, 152, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(61, NULL, 8078, 40, 0, 0, 40, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(62, NULL, 8079, 32, 0, 0, 32, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(63, NULL, 8080, 0, 0, 0, 0, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(64, NULL, 8081, 72, 0, 0, 72, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(65, NULL, 8082, 0, 0, 0, 0, '1', 2, '2026-11-02 23:08:44', '2026-11-02 23:08:44'),
(66, NULL, 8085, 16, 0, 0, 16, '1', 2, '2026-11-02 23:08:45', '2026-11-02 23:08:45'),
(67, NULL, 8086, 64, 0, 0, 64, '1', 2, '2026-11-02 23:08:45', '2026-11-02 23:08:45'),
(68, NULL, 8087, 72, 0, 0, 72, '1', 2, '2026-11-02 23:08:45', '2026-11-02 23:08:45'),
(69, NULL, 8088, 8, 0, 0, 8, '1', 2, '2026-11-02 23:08:45', '2026-11-02 23:08:45'),
(70, NULL, 8089, 112, 0, 0, 112, '1', 2, '2026-11-02 23:08:45', '2026-11-02 23:08:45'),
(71, NULL, 8091, 32, 0, 0, 32, '1', 2, '2026-11-02 23:08:45', '2026-11-02 23:08:45'),
(72, NULL, 8092, 192, 0, 0, 192, '1', 2, '2026-11-02 23:08:45', '2026-11-02 23:08:45'),
(73, NULL, 8093, 8, 0, 0, 8, '1', 2, '2026-11-02 23:08:45', '2026-11-02 23:08:45'),
(74, NULL, 8094, 72, 0, 0, 72, '1', 2, '2026-11-02 23:08:45', '2026-11-02 23:08:45'),
(75, NULL, 8095, 48, 0, 0, 48, '1', 2, '2026-11-02 23:08:45', '2026-11-02 23:08:45'),
(76, NULL, 8096, 0, 0, 0, 0, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(77, NULL, 8097, 40, 0, 0, 40, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(78, NULL, 8098, 88, 0, 0, 88, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(79, NULL, 8099, 24, 0, 0, 24, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(80, NULL, 8101, 96, 0, 0, 96, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(81, NULL, 8102, 64, 0, 0, 64, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(82, NULL, 8103, 40, 0, 0, 40, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(83, NULL, 8105, 120, 0, 0, 120, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(84, NULL, 8107, 0, 0, 0, 0, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(85, NULL, 8108, 96, 0, 0, 96, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(86, NULL, 8109, 24, 0, 0, 24, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(87, NULL, 8110, 0, 0, 0, 0, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(88, NULL, 8112, 48, 0, 0, 48, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(89, NULL, 8115, 24, 0, 0, 24, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(90, NULL, 8117, 0, 0, 0, 0, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(91, NULL, 8120, 64, 0, 0, 64, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(92, NULL, 8121, 0, 0, 0, 0, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(93, NULL, 8122, 40, 0, 0, 40, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(94, NULL, 8123, 0, 0, 0, 0, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(95, NULL, 8125, 0, 0, 0, 0, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(96, NULL, 8126, 72, 0, 0, 72, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(97, NULL, 8127, 24, 0, 0, 24, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(98, NULL, 8129, 112, 0, 0, 112, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(99, NULL, 8132, 0, 0, 0, 0, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(100, NULL, 8133, 0, 0, 0, 0, '1', 2, '2026-11-02 23:13:12', '2026-11-02 23:13:12'),
(101, NULL, 8134, 32, 0, 0, 32, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(102, NULL, 8139, 72, 0, 0, 72, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(103, NULL, 8140, 0, 0, 0, 0, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(104, NULL, 8141, 136, 0, 0, 136, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(105, NULL, 8142, 24, 0, 0, 24, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(106, NULL, 8143, 32, 0, 0, 32, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(107, NULL, 8144, 40, 0, 0, 40, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(108, NULL, 8145, 168, 0, 0, 168, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(109, NULL, 8146, 168, 0, 0, 168, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(110, NULL, 8147, 16, 0, 0, 16, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(111, NULL, 8148, 56, 0, 0, 56, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(112, NULL, 8149, 48, 0, 0, 48, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(113, NULL, 8150, 16, 0, 0, 16, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(114, NULL, 8151, 176, 0, 0, 176, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(115, NULL, 8152, 56, 0, 0, 56, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(116, NULL, 8153, 176, 0, 0, 176, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(117, NULL, 8154, 128, 0, 0, 128, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(118, NULL, 8156, 0, 0, 0, 0, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(119, NULL, 8157, 24, 0, 0, 24, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(120, NULL, 8158, 0, 0, 0, 0, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(121, NULL, 8159, 112, 0, 0, 112, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(122, NULL, 8160, 64, 0, 0, 64, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(123, NULL, 8161, 24, 0, 0, 24, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(124, NULL, 8162, 16, 0, 0, 16, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(125, NULL, 8163, 280, 0, 0, 280, '1', 2, '2026-11-02 23:17:16', '2026-11-02 23:17:16'),
(126, NULL, 8164, 16, 0, 0, 16, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(127, NULL, 8165, 0, 0, 0, 0, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(128, NULL, 8166, 32, 0, 0, 32, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(129, NULL, 8167, 72, 0, 0, 72, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(130, NULL, 8169, 0, 0, 0, 0, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(131, NULL, 8170, 40, 0, 0, 40, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(132, NULL, 8171, 0, 0, 0, 0, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(133, NULL, 8172, 72, 0, 0, 72, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(134, NULL, 8173, 32, 0, 0, 32, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(135, NULL, 8174, 88, 0, 0, 88, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(136, NULL, 8175, 48, 0, 0, 48, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(137, NULL, 8176, 32, 0, 0, 32, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(138, NULL, 8177, 120, 0, 0, 120, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(139, NULL, 8178, 0, 0, 0, 0, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(140, NULL, 8179, 0, 0, 0, 0, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(141, NULL, 8180, 72, 0, 0, 72, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(142, NULL, 8181, 144, 0, 0, 144, '1', 2, '2026-11-02 23:20:29', '2026-11-02 23:20:29'),
(143, NULL, 8182, 8, 0, 0, 8, '1', 2, '2026-11-02 23:20:30', '2026-11-02 23:20:30'),
(144, NULL, 8183, 32, 0, 0, 32, '1', 2, '2026-11-02 23:20:30', '2026-11-02 23:20:30'),
(145, NULL, 8184, 192, 0, 0, 192, '1', 2, '2026-11-02 23:20:30', '2026-11-02 23:20:30'),
(146, NULL, 8185, 112, 0, 0, 112, '1', 2, '2026-11-02 23:20:30', '2026-11-02 23:20:30'),
(147, NULL, 15001, 0, 0, 0, 0, '1', 2, '2026-11-02 23:20:30', '2026-11-02 23:20:30'),
(148, NULL, 15002, 152, 0, 0, 152, '1', 2, '2026-11-02 23:20:30', '2026-11-02 23:20:30'),
(149, NULL, 15003, 0, 0, 0, 0, '1', 2, '2026-11-02 23:20:30', '2026-11-02 23:20:30'),
(150, NULL, 15004, 160, 0, 0, 160, '1', 2, '2026-11-02 23:20:30', '2026-11-02 23:20:30'),
(151, NULL, 15005, 192, 0, 0, 192, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(152, NULL, 15006, 128, 0, 0, 128, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(153, NULL, 15008, 8, 0, 0, 8, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(154, NULL, 15009, 0, 0, 0, 0, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(155, NULL, 15010, 0, 0, 0, 0, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(156, NULL, 15011, 88, 0, 0, 88, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(157, NULL, 15012, 64, 0, 0, 64, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(158, NULL, 15014, 80, 0, 0, 80, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(159, NULL, 15016, 96, 0, 0, 96, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(160, NULL, 15017, 120, 0, 0, 120, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(161, NULL, 15019, 64, 0, 0, 64, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(162, NULL, 15020, 0, 0, 0, 0, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(163, NULL, 15021, 88, 0, 0, 88, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(164, NULL, 15024, 56, 0, 0, 56, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(165, NULL, 15025, 128, 0, 0, 128, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(166, NULL, 15026, 200, 0, 0, 200, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(167, NULL, 15027, 72, 0, 0, 72, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(168, NULL, 15028, 120, 0, 0, 120, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(169, NULL, 15029, 152, 0, 0, 152, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(170, NULL, 15030, 112, 0, 0, 112, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(171, NULL, 15031, 8, 0, 0, 8, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(172, NULL, 15032, 0, 0, 0, 0, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(173, NULL, 15033, 0, 0, 0, 0, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(174, NULL, 15034, 224, 0, 0, 224, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(175, NULL, 15036, 64, 0, 0, 64, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(176, NULL, 15037, 16, 0, 0, 16, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(177, NULL, 15038, 264, 0, 0, 264, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(178, NULL, 15039, 128, 0, 0, 128, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(179, NULL, 15040, 56, 0, 0, 56, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(180, NULL, 15041, 56, 0, 0, 56, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(181, NULL, 15042, 248, 0, 0, 248, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(182, NULL, 15043, 120, 0, 0, 120, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(183, NULL, 15044, 216, 0, 0, 216, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(184, NULL, 15045, 24, 0, 0, 24, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(185, NULL, 15046, 32, 0, 0, 32, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(186, NULL, 15047, 160, 0, 0, 160, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(187, NULL, 15048, 0, 0, 0, 0, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(188, NULL, 15049, 32, 0, 0, 32, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(189, NULL, 15050, 344, 0, 0, 344, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(190, NULL, 15051, 112, 0, 0, 112, '1', 2, '2026-11-02 23:26:14', '2026-11-02 23:26:14'),
(191, NULL, 15053, 192, 0, 0, 192, '1', 2, '2026-11-02 23:26:15', '2026-11-02 23:26:15'),
(192, NULL, 15054, 56, 0, 0, 56, '1', 2, '2026-11-02 23:26:15', '2026-11-02 23:26:15'),
(193, NULL, 15055, 24, 0, 0, 24, '1', 2, '2026-11-02 23:26:15', '2026-11-02 23:26:15'),
(194, NULL, 15056, 0, 0, 0, 0, '1', 2, '2026-11-02 23:26:15', '2026-11-02 23:26:15'),
(195, NULL, 15057, 104, 0, 0, 104, '1', 2, '2026-11-02 23:26:15', '2026-11-02 23:26:15'),
(196, NULL, 15058, 200, 0, 0, 200, '1', 2, '2026-11-02 23:26:15', '2026-11-02 23:26:15'),
(197, NULL, 15059, 40, 0, 0, 40, '1', 2, '2026-11-02 23:26:15', '2026-11-02 23:26:15'),
(198, NULL, 15060, 80, 0, 0, 80, '1', 2, '2026-11-02 23:26:15', '2026-11-02 23:26:15'),
(199, NULL, 15061, 176, 0, 0, 176, '1', 2, '2026-11-02 23:26:15', '2026-11-02 23:26:15'),
(200, NULL, 15062, 144, 0, 0, 144, '1', 2, '2026-11-02 23:26:15', '2026-11-02 23:26:15'),
(201, NULL, 15064, 40, 0, 0, 40, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(202, NULL, 15065, 40, 0, 0, 40, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(203, NULL, 15066, 40, 0, 0, 40, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(204, NULL, 15067, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(205, NULL, 15068, 88, 0, 0, 88, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(206, NULL, 15069, 176, 0, 0, 176, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(207, NULL, 15070, 32, 0, 0, 32, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(208, NULL, 15071, 216, 0, 0, 216, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(209, NULL, 15072, 64, 0, 0, 64, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(210, NULL, 15073, 224, 0, 0, 224, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(211, NULL, 15074, 144, 0, 0, 144, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(212, NULL, 15075, 128, 0, 0, 128, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(213, NULL, 15076, 48, 0, 0, 48, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(214, NULL, 15077, 120, 0, 0, 120, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(215, NULL, 15078, 64, 0, 0, 64, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(216, NULL, 15079, 152, 0, 0, 152, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(217, NULL, 15080, 264, 0, 0, 264, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(218, NULL, 15081, 24, 0, 0, 24, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(219, NULL, 15082, 168, 0, 0, 168, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(220, NULL, 15083, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(221, NULL, 15084, 144, 0, 0, 144, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(222, NULL, 15085, 184, 0, 0, 184, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(223, NULL, 15086, 168, 0, 0, 168, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(224, NULL, 15087, 128, 0, 0, 128, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(225, NULL, 15089, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(226, NULL, 15090, 120, 0, 0, 120, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(227, NULL, 15091, 128, 0, 0, 128, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(228, NULL, 15093, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(229, NULL, 15094, 136, 0, 0, 136, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(230, NULL, 15095, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(231, NULL, 15096, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(232, NULL, 15097, 8, 0, 0, 8, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(233, NULL, 15098, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(234, NULL, 15099, 160, 0, 0, 160, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(235, NULL, 15100, 64, 0, 0, 64, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(236, NULL, 15101, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(237, NULL, 15102, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(238, NULL, 15103, 64, 0, 0, 64, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(239, NULL, 15104, 40, 0, 0, 40, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(240, NULL, 15105, 248, 0, 0, 248, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(241, NULL, 15106, 48, 0, 0, 48, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(242, NULL, 15107, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:39', '2026-11-02 23:29:39'),
(243, NULL, 15108, 16, 0, 0, 16, '1', 2, '2026-11-02 23:29:40', '2026-11-02 23:29:40'),
(244, NULL, 15109, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:40', '2026-11-02 23:29:40'),
(245, NULL, 15110, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:40', '2026-11-02 23:29:40'),
(246, NULL, 15112, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:40', '2026-11-02 23:29:40'),
(247, NULL, 15113, 24, 0, 0, 24, '1', 2, '2026-11-02 23:29:40', '2026-11-02 23:29:40'),
(248, NULL, 15114, 136, 0, 0, 136, '1', 2, '2026-11-02 23:29:40', '2026-11-02 23:29:40'),
(249, NULL, 15116, 152, 0, 0, 152, '1', 2, '2026-11-02 23:29:40', '2026-11-02 23:29:40'),
(250, NULL, 15117, 0, 0, 0, 0, '1', 2, '2026-11-02 23:29:40', '2026-11-02 23:29:40'),
(251, NULL, 15118, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(252, NULL, 15119, 72, 0, 0, 72, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(253, NULL, 15121, 144, 0, 0, 144, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(254, NULL, 15122, 296, 0, 0, 296, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(255, NULL, 15123, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(256, NULL, 15124, 40, 0, 0, 40, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(257, NULL, 15125, 72, 0, 0, 72, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(258, NULL, 15126, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(259, NULL, 15127, 120, 0, 0, 120, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(260, NULL, 15128, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(261, NULL, 15130, 72, 0, 0, 72, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(262, NULL, 15131, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(263, NULL, 15132, 24, 0, 0, 24, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(264, NULL, 15133, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(265, NULL, 15134, 256, 0, 0, 256, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(266, NULL, 15135, 104, 0, 0, 104, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(267, NULL, 15136, 16, 0, 0, 16, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(268, NULL, 15137, 128, 0, 0, 128, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(269, NULL, 15138, 80, 0, 0, 80, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(270, NULL, 15140, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(271, NULL, 15141, 80, 0, 0, 80, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(272, NULL, 15142, 216, 0, 0, 216, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(273, NULL, 15143, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(274, NULL, 15144, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(275, NULL, 15145, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(276, NULL, 15146, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(277, NULL, 15147, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(278, NULL, 15152, 112, 0, 0, 112, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(279, NULL, 15153, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(280, NULL, 15154, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(281, NULL, 15156, 400, 0, 0, 400, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(282, NULL, 15158, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(283, NULL, 15159, 200, 0, 0, 200, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(284, NULL, 15160, 24, 0, 0, 24, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(285, NULL, 15161, 80, 0, 0, 80, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(286, NULL, 15162, 32, 0, 0, 32, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(287, NULL, 15164, 64, 0, 0, 64, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(288, NULL, 15165, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(289, NULL, 15166, 40, 0, 0, 40, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(290, NULL, 15167, 16, 0, 0, 16, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(291, NULL, 15168, 128, 0, 0, 128, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(292, NULL, 15169, 88, 0, 0, 88, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(293, NULL, 15170, 16, 0, 0, 16, '1', 2, '2026-11-02 23:31:49', '2026-11-02 23:31:49'),
(294, NULL, 15171, 328, 0, 0, 328, '1', 2, '2026-11-02 23:31:50', '2026-11-02 23:31:50'),
(295, NULL, 15172, 0, 0, 0, 0, '1', 2, '2026-11-02 23:31:50', '2026-11-02 23:31:50'),
(296, NULL, 15173, 344, 0, 0, 344, '1', 2, '2026-11-02 23:31:50', '2026-11-02 23:31:50'),
(297, NULL, 15174, 320, 0, 0, 320, '1', 2, '2026-11-02 23:31:50', '2026-11-02 23:31:50'),
(298, NULL, 15175, 328, 0, 0, 328, '1', 2, '2026-11-02 23:31:50', '2026-11-02 23:31:50'),
(299, NULL, 15118, 0, 0, 0, 0, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(300, NULL, 15119, 72, 0, 0, 72, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(301, NULL, 15121, 144, 0, 0, 144, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(302, NULL, 15122, 296, 0, 0, 296, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(303, NULL, 15123, 0, 0, 0, 0, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(304, NULL, 15124, 40, 0, 0, 40, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(305, NULL, 15125, 72, 0, 0, 72, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(306, NULL, 15126, 0, 0, 0, 0, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(307, NULL, 15127, 120, 0, 0, 120, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(308, NULL, 15128, 0, 0, 0, 0, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(309, NULL, 15130, 72, 0, 0, 72, '1', 2, '2026-11-03 00:06:52', '2026-11-03 00:06:52'),
(310, NULL, 8036, 88, 0, 0, 88, '1', 2, '2026-11-03 00:12:11', '2026-11-03 00:12:11'),
(311, NULL, 8037, 56, 0, 0, 56, '1', 2, '2026-11-03 00:12:11', '2026-11-03 00:12:11'),
(312, NULL, 8038, 0, 0, 0, 0, '1', 2, '2026-11-03 00:12:11', '2026-11-03 00:12:11'),
(313, NULL, 7001, 176, 0, 0, 176, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(314, NULL, 7002, 296, 0, 0, 296, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(315, NULL, 7003, 168, 0, 0, 168, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(316, NULL, 7004, 104, 0, 0, 104, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(317, NULL, 7005, 200, 0, 0, 200, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(318, NULL, 7006, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(319, NULL, 7007, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(320, NULL, 7008, 96, 0, 0, 96, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(321, NULL, 7009, 160, 0, 0, 160, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(322, NULL, 7011, 216, 0, 0, 216, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(323, NULL, 7012, 72, 0, 0, 72, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(324, NULL, 7013, 48, 0, 0, 48, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(325, NULL, 7014, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(326, NULL, 7015, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(327, NULL, 7016, 312, 0, 0, 312, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(328, NULL, 7017, 216, 0, 0, 216, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(329, NULL, 7018, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(330, NULL, 7019, 72, 0, 0, 72, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(331, NULL, 7020, 160, 0, 0, 160, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(332, NULL, 7022, 40, 0, 0, 40, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(333, NULL, 7023, 80, 0, 0, 80, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(334, NULL, 7024, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(335, NULL, 7025, 192, 0, 0, 192, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(336, NULL, 7026, 72, 0, 0, 72, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(337, NULL, 7027, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(338, NULL, 7028, 48, 0, 0, 48, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(339, NULL, 7029, 136, 0, 0, 136, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(340, NULL, 7030, 56, 0, 0, 56, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(341, NULL, 7031, 72, 0, 0, 72, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(342, NULL, 7032, 40, 0, 0, 40, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(343, NULL, 7033, 184, 0, 0, 184, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(344, NULL, 7034, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(345, NULL, 7035, 16, 0, 0, 16, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(346, NULL, 7036, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(347, NULL, 7037, 216, 0, 0, 216, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(348, NULL, 7038, 136, 0, 0, 136, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(349, NULL, 7039, 96, 0, 0, 96, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(350, NULL, 7040, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(351, NULL, 7041, 168, 0, 0, 168, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(352, NULL, 7042, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(353, NULL, 7043, 32, 0, 0, 32, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(354, NULL, 7045, 120, 0, 0, 120, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(355, NULL, 7046, 136, 0, 0, 136, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(356, NULL, 7047, 16, 0, 0, 16, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(357, NULL, 7048, 256, 0, 0, 256, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(358, NULL, 7049, 168, 0, 0, 168, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(359, NULL, 7050, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(360, NULL, 7051, 128, 0, 0, 128, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(361, NULL, 7052, 184, 0, 0, 184, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(362, NULL, 7053, 120, 0, 0, 120, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(363, NULL, 7054, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(364, NULL, 7055, 80, 0, 0, 80, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(365, NULL, 7056, 192, 0, 0, 192, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(366, NULL, 7057, 56, 0, 0, 56, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(367, NULL, 7058, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(368, NULL, 7059, 112, 0, 0, 112, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(369, NULL, 7060, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(370, NULL, 7061, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(371, NULL, 7064, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(372, NULL, 7065, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(373, NULL, 7066, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(374, NULL, 7067, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:51', '2026-11-04 05:42:51'),
(375, NULL, 7068, 280, 0, 0, 280, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(376, NULL, 7069, 128, 0, 0, 128, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(377, NULL, 7070, 40, 0, 0, 40, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(378, NULL, 7071, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(379, NULL, 7072, 104, 0, 0, 104, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(380, NULL, 7073, 104, 0, 0, 104, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(381, NULL, 7074, 80, 0, 0, 80, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(382, NULL, 7075, 80, 0, 0, 80, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(383, NULL, 7076, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(384, NULL, 7077, 32, 0, 0, 32, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(385, NULL, 7078, 168, 0, 0, 168, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(386, NULL, 7079, 40, 0, 0, 40, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(387, NULL, 7080, 416, 0, 0, 416, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(388, NULL, 7081, 152, 0, 0, 152, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(389, NULL, 7082, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(390, NULL, 7083, 16, 0, 0, 16, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(391, NULL, 7085, 136, 0, 0, 136, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(392, NULL, 7087, 232, 0, 0, 232, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(393, NULL, 7088, 48, 0, 0, 48, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(394, NULL, 7089, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(395, NULL, 7090, 208, 0, 0, 208, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(396, NULL, 7092, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(397, NULL, 7093, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(398, NULL, 7094, 40, 0, 0, 40, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(399, NULL, 7095, 64, 0, 0, 64, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(400, NULL, 7096, 64, 0, 0, 64, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(401, NULL, 7097, 112, 0, 0, 112, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(402, NULL, 7098, 112, 0, 0, 112, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(403, NULL, 7101, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(404, NULL, 7102, 72, 0, 0, 72, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(405, NULL, 7103, 136, 0, 0, 136, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(406, NULL, 7104, 56, 0, 0, 56, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(407, NULL, 7105, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(408, NULL, 7107, 80, 0, 0, 80, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(409, NULL, 7108, 40, 0, 0, 40, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(410, NULL, 7109, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(411, NULL, 7110, 32, 0, 0, 32, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(412, NULL, 7111, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(413, NULL, 7112, 40, 0, 0, 40, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(414, NULL, 7113, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(415, NULL, 7114, 208, 0, 0, 208, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(416, NULL, 7115, 32, 0, 0, 32, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(417, NULL, 7116, 72, 0, 0, 72, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(418, NULL, 7117, 136, 0, 0, 136, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(419, NULL, 7118, 232, 0, 0, 232, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(420, NULL, 7119, 48, 0, 0, 48, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(421, NULL, 7120, 32, 0, 0, 32, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(422, NULL, 7121, 104, 0, 0, 104, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(423, NULL, 7122, 96, 0, 0, 96, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(424, NULL, 7123, 64, 0, 0, 64, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(425, NULL, 7124, 88, 0, 0, 88, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(426, NULL, 7125, 32, 0, 0, 32, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(427, NULL, 7126, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(428, NULL, 7127, 104, 0, 0, 104, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(429, NULL, 7128, 136, 0, 0, 136, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(430, NULL, 7130, 120, 0, 0, 120, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(431, NULL, 7132, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(432, NULL, 7133, 80, 0, 0, 80, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(433, NULL, 7134, 40, 0, 0, 40, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(434, NULL, 7136, 64, 0, 0, 64, '1', 2, '2026-11-04 05:42:52', '2026-11-04 05:42:52'),
(435, NULL, 7137, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(436, NULL, 7138, 16, 0, 0, 16, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(437, NULL, 7139, 264, 0, 0, 264, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(438, NULL, 7141, 32, 0, 0, 32, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(439, NULL, 7142, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(440, NULL, 7143, 128, 0, 0, 128, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(441, NULL, 7144, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(442, NULL, 7145, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(443, NULL, 7146, 96, 0, 0, 96, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(444, NULL, 7147, 24, 0, 0, 24, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(445, NULL, 7148, 112, 0, 0, 112, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(446, NULL, 7149, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(447, NULL, 7150, 40, 0, 0, 40, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(448, NULL, 7151, 56, 0, 0, 56, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(449, NULL, 7152, 64, 0, 0, 64, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(450, NULL, 7153, 88, 0, 0, 88, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(451, NULL, 7154, 64, 0, 0, 64, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(452, NULL, 7155, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(453, NULL, 7156, 176, 0, 0, 176, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(454, NULL, 7158, 160, 0, 0, 160, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(455, NULL, 7159, 168, 0, 0, 168, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(456, NULL, 7160, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(457, NULL, 7161, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(458, NULL, 7162, 64, 0, 0, 64, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(459, NULL, 7164, 96, 0, 0, 96, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(460, NULL, 7165, 56, 0, 0, 56, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(461, NULL, 7166, 152, 0, 0, 152, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(462, NULL, 7168, 176, 0, 0, 176, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(463, NULL, 7169, 128, 0, 0, 128, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(464, NULL, 7170, 144, 0, 0, 144, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(465, NULL, 7171, 136, 0, 0, 136, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(466, NULL, 7172, 16, 0, 0, 16, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(467, NULL, 7173, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(468, NULL, 7174, 16, 0, 0, 16, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(469, NULL, 7175, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(470, NULL, 7176, 224, 0, 0, 224, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(471, NULL, 7179, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(472, NULL, 7180, 56, 0, 0, 56, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(473, NULL, 7181, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(474, NULL, 7182, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(475, NULL, 7183, 16, 0, 0, 16, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(476, NULL, 7184, 104, 0, 0, 104, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(477, NULL, 7185, 40, 0, 0, 40, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(478, NULL, 7186, 48, 0, 0, 48, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(479, NULL, 7187, 72, 0, 0, 72, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(480, NULL, 7188, 80, 0, 0, 80, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(481, NULL, 7189, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(482, NULL, 7190, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(483, NULL, 7191, 8, 0, 0, 8, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(484, NULL, 7192, 64, 0, 0, 64, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(485, NULL, 7193, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(486, NULL, 7195, 160, 0, 0, 160, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(487, NULL, 7196, 168, 0, 0, 168, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(488, NULL, 7197, 48, 0, 0, 48, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(489, NULL, 7198, 56, 0, 0, 56, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(490, NULL, 7199, 432, 0, 0, 432, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(491, NULL, 7200, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(492, NULL, 7201, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(493, NULL, 7203, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(494, NULL, 7204, 184, 0, 0, 184, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(495, NULL, 7205, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(496, NULL, 7206, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(497, NULL, 7207, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(498, NULL, 7208, 88, 0, 0, 88, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(499, NULL, 7209, 96, 0, 0, 96, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(500, NULL, 7210, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(501, NULL, 7211, 888, 0, 0, 888, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(502, NULL, 7212, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(503, NULL, 7213, 232, 0, 0, 232, '1', 2, '2026-11-04 05:42:53', '2026-11-04 05:42:53'),
(504, NULL, 7214, 72, 0, 0, 72, '1', 2, '2026-11-04 05:42:54', '2026-11-04 05:42:54'),
(505, NULL, 7215, 168, 0, 0, 168, '1', 2, '2026-11-04 05:42:54', '2026-11-04 05:42:54'),
(506, NULL, 7216, 56, 0, 0, 56, '1', 2, '2026-11-04 05:42:54', '2026-11-04 05:42:54'),
(507, NULL, 7217, 144, 0, 0, 144, '1', 2, '2026-11-04 05:42:54', '2026-11-04 05:42:54'),
(508, NULL, 7218, 0, 0, 0, 0, '1', 2, '2026-11-04 05:42:54', '2026-11-04 05:42:54'),
(509, NULL, 7219, 152, 0, 0, 152, '1', 2, '2026-11-04 05:42:54', '2026-11-04 05:42:54'),
(510, NULL, 16001, 56, 0, 0, 56, '1', 2, '2026-11-04 18:43:53', '2026-11-04 18:43:53'),
(511, NULL, 16002, 16, 0, 0, 16, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(512, NULL, 16003, 24, 0, 0, 24, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(513, NULL, 16004, 24, 0, 0, 24, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(514, NULL, 16005, 40, 0, 0, 40, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(515, NULL, 16006, 280, 0, 0, 280, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(516, NULL, 16007, 8, 0, 0, 8, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(517, NULL, 16008, 56, 0, 0, 56, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(518, NULL, 16009, 32, 0, 0, 32, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(519, NULL, 16010, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(520, NULL, 16011, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(521, NULL, 16012, 16, 0, 0, 16, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(522, NULL, 16013, 32, 0, 0, 32, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(523, NULL, 16014, 480, 0, 0, 480, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(524, NULL, 16015, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(525, NULL, 16016, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(526, NULL, 16017, 40, 0, 0, 40, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(527, NULL, 16018, 80, 0, 0, 80, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(528, NULL, 16019, 8, 0, 0, 8, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(529, NULL, 16020, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(530, NULL, 16021, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(531, NULL, 16022, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(532, NULL, 16023, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(533, NULL, 16024, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(534, NULL, 16026, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(535, NULL, 16027, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(536, NULL, 16028, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(537, NULL, 16029, 104, 0, 0, 104, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(538, NULL, 16030, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(539, NULL, 16031, 16, 0, 0, 16, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(540, NULL, 16032, 88, 0, 0, 88, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(541, NULL, 16033, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(542, NULL, 16034, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(543, NULL, 16035, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(544, NULL, 16036, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(545, NULL, 16038, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(546, NULL, 16039, 128, 0, 0, 128, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(547, NULL, 16040, 104, 0, 0, 104, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(548, NULL, 16041, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(549, NULL, 16042, 40, 0, 0, 40, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(550, NULL, 16043, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(551, NULL, 16044, 80, 0, 0, 80, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(552, NULL, 16045, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(553, NULL, 16046, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(554, NULL, 16047, 280, 0, 0, 280, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(555, NULL, 16048, 56, 0, 0, 56, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(556, NULL, 16049, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:54', '2026-11-04 18:43:54'),
(557, NULL, 16050, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(558, NULL, 16051, 16, 0, 0, 16, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(559, NULL, 16052, 128, 0, 0, 128, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(560, NULL, 16053, 8, 0, 0, 8, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(561, NULL, 16054, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(562, NULL, 16055, 88, 0, 0, 88, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(563, NULL, 16056, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(564, NULL, 16057, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(565, NULL, 16058, 8, 0, 0, 8, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(566, NULL, 16059, 152, 0, 0, 152, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(567, NULL, 16060, 64, 0, 0, 64, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(568, NULL, 16061, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(569, NULL, 16062, 120, 0, 0, 120, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(570, NULL, 16063, 24, 0, 0, 24, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(571, NULL, 16064, 8, 0, 0, 8, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(572, NULL, 16065, 288, 0, 0, 288, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(573, NULL, 16066, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(574, NULL, 16067, 120, 0, 0, 120, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(575, NULL, 16069, 8, 0, 0, 8, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(576, NULL, 16070, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(577, NULL, 16072, 8, 0, 0, 8, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(578, NULL, 16073, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(579, NULL, 16074, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(580, NULL, 16075, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(581, NULL, 16076, 64, 0, 0, 64, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(582, NULL, 16077, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(583, NULL, 16078, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(584, NULL, 16079, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55');
INSERT INTO `tw_acc_transactions` (`id`, `org_id_fk`, `meter_id_fk`, `paidsum`, `vatsum`, `reserve_meter_sum`, `totalpaidsum`, `status`, `cashier`, `created_at`, `updated_at`) VALUES
(585, NULL, 16080, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(586, NULL, 16081, 24, 0, 0, 24, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(587, NULL, 16082, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(588, NULL, 16083, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(589, NULL, 16084, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(590, NULL, 16085, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(591, NULL, 16086, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(592, NULL, 16087, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(593, NULL, 16088, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(594, NULL, 16089, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(595, NULL, 16090, 16, 0, 0, 16, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(596, NULL, 16091, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(597, NULL, 16093, 40, 0, 0, 40, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(598, NULL, 16095, 16, 0, 0, 16, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(599, NULL, 16097, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(600, NULL, 16098, 136, 0, 0, 136, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(601, NULL, 16099, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(602, NULL, 16100, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(603, NULL, 16104, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(604, NULL, 16105, 208, 0, 0, 208, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(605, NULL, 16106, 0, 0, 0, 0, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55'),
(606, NULL, 16108, 64, 0, 0, 64, '1', 2, '2026-11-04 18:43:55', '2026-11-04 18:43:55');

-- --------------------------------------------------------

--
-- Table structure for table `tw_cutmeter`
--

CREATE TABLE `tw_cutmeter` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meter_id_fk` bigint(20) UNSIGNED NOT NULL,
  `owe_count` int(11) NOT NULL DEFAULT 0,
  `warning_print` int(11) NOT NULL DEFAULT 0,
  `status` enum('pending','cutmeter','install','complete','cancel') NOT NULL,
  `operate_by` varchar(191) NOT NULL COMMENT 'รหัส staffs ถ้า >1 ใช้ | แยก',
  `comment` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tw_invoice`
--

CREATE TABLE `tw_invoice` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inv_no` int(22) NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `meter_id_fk` bigint(20) UNSIGNED NOT NULL,
  `inv_period_id_fk` bigint(20) UNSIGNED NOT NULL,
  `lastmeter` float NOT NULL,
  `currentmeter` float NOT NULL,
  `water_used` float NOT NULL,
  `inv_type` enum('r','u') DEFAULT NULL COMMENT 'r =รักษามิเตอร์, u=ใช้น้ำ',
  `paid` float NOT NULL,
  `reserve_meter` float NOT NULL,
  `vat` float NOT NULL,
  `totalpaid` float NOT NULL,
  `previous_balance` decimal(8,2) DEFAULT NULL,
  `status` enum('init','invoice','paid','owe','deleted') NOT NULL,
  `acc_trans_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `comment` varchar(191) DEFAULT NULL,
  `recorder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_invoice`
--

INSERT INTO `tw_invoice` (`id`, `inv_no`, `org_id_fk`, `meter_id_fk`, `inv_period_id_fk`, `lastmeter`, `currentmeter`, `water_used`, `inv_type`, `paid`, `reserve_meter`, `vat`, `totalpaid`, `previous_balance`, `status`, `acc_trans_id_fk`, `comment`, `recorder_id`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 8001, 13, 31, 31, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 1, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(2, 2, 4, 8002, 13, 1904, 1913, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 2, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(3, 3, 4, 8003, 13, 926, 926, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 3, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(4, 4, 4, 8004, 13, 3072, 3075, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 4, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(5, 5, 4, 8005, 13, 4996, 4996, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 5, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(6, 6, 4, 8006, 13, 2040, 2041, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 6, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(7, 7, 4, 8007, 13, 247, 253, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 7, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(8, 8, 4, 8008, 13, 2010, 2041, 31, 'u', 248, 0, 0, 248, 0.00, 'paid', 8, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(9, 9, 4, 8009, 13, 1217, 1219, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 9, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(10, 10, 4, 8010, 13, 994, 997, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 10, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(11, 11, 4, 8011, 13, 1475, 1489, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 11, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(12, 12, 4, 8012, 13, 1588, 1588, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 12, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(13, 13, 4, 8013, 13, 1220, 1224, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 13, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(14, 14, 4, 8014, 13, 2827, 2827, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 14, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(15, 15, 4, 8015, 13, 2823, 2824, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 15, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(16, 16, 4, 8016, 13, 3490, 3509, 19, 'u', 152, 0, 0, 152, 0.00, 'paid', 16, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(17, 17, 4, 8017, 13, 1797, 1810, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 17, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(18, 18, 4, 8018, 13, 1241, 1254, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 18, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(19, 19, 4, 8020, 13, 2985, 3001, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 19, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(20, 20, 4, 8022, 13, 1022, 1022, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 20, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(21, 21, 4, 8023, 13, 889, 892, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 21, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(22, 22, 4, 8025, 13, 1016, 1022, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 22, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(23, 23, 4, 8028, 13, 1385, 1385, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 23, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(24, 24, 4, 8029, 13, 1656, 1656, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 24, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(25, 25, 4, 8030, 13, 3537, 3539, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 25, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(26, 26, 4, 8031, 13, 2992, 2996, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 26, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(27, 27, 4, 8032, 13, 1743, 1745, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 27, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(28, 28, 4, 8036, 13, 626, 637, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 310, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(29, 29, 4, 8037, 13, 1157, 1164, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 311, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(30, 30, 4, 8038, 13, 1427, 1427, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 312, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(31, 31, 4, 8039, 13, 902, 909, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 31, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(32, 32, 4, 8041, 13, 625, 630, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 32, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(33, 33, 4, 8042, 13, 665, 665, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 33, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(34, 34, 4, 8043, 13, 3199, 3205, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 34, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(35, 35, 4, 8046, 13, 2363, 2369, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 35, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(36, 36, 4, 8047, 13, 6195, 6212, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 36, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(37, 37, 4, 8048, 13, 1985, 1990, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 37, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(38, 38, 4, 8049, 13, 1741, 1741, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 38, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(39, 39, 4, 8050, 13, 18, 18, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 39, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(40, 40, 4, 8051, 13, 4486, 4486, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 40, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(41, 41, 4, 8052, 13, 6860, 6884, 24, 'u', 192, 0, 0, 192, 0.00, 'paid', 41, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(42, 42, 4, 8053, 13, 2661, 2664, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 42, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(43, 43, 4, 8054, 13, 3229, 3238, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 43, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(44, 44, 4, 8057, 13, 5005, 5029, 24, 'u', 192, 0, 0, 192, 0.00, 'paid', 44, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(45, 45, 4, 8058, 13, 85, 85, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 45, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(46, 46, 4, 8061, 13, 1842, 1857, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 46, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(47, 47, 4, 8062, 13, 1504, 1510, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 47, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(48, 48, 4, 8063, 13, 1704, 1710, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 48, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(49, 49, 4, 8064, 13, 405, 408, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 49, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(50, 50, 4, 8065, 13, 2140, 2151, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 50, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(51, 51, 4, 8066, 13, 2535, 2545, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 51, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(52, 52, 4, 8067, 13, 5234, 5247, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 52, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(53, 53, 4, 8069, 13, 5023, 5040, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 53, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(54, 54, 4, 8070, 13, 4493, 4493, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 54, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(55, 55, 4, 8071, 13, 2420, 2423, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 55, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(56, 56, 4, 8072, 13, 2447, 2455, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 56, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(57, 57, 4, 8073, 13, 450, 457, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 57, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(58, 58, 4, 8074, 13, 1414, 1414, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 58, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(59, 59, 4, 8075, 13, 2555, 2558, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 59, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(60, 60, 4, 8077, 13, 4294, 4313, 19, 'u', 152, 0, 0, 152, 0.00, 'paid', 60, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(61, 61, 4, 8078, 13, 2681, 2686, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 61, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(62, 62, 4, 8079, 13, 1509, 1513, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 62, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(63, 63, 4, 8080, 13, 1924, 1924, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 63, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(64, 64, 4, 8081, 13, 4038, 4047, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 64, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(65, 65, 4, 8082, 13, 2154, 2154, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 65, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(66, 66, 4, 8085, 13, 3256, 3258, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 66, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(67, 67, 4, 8086, 13, 2363, 2371, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 67, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(68, 68, 4, 8087, 13, 2515, 2524, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 68, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(69, 69, 4, 8088, 13, 840, 841, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 69, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(70, 70, 4, 8089, 13, 4168, 4182, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 70, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(71, 71, 4, 8091, 13, 2300, 2304, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 71, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(72, 72, 4, 8092, 13, 2131, 2155, 24, 'u', 192, 0, 0, 192, 0.00, 'paid', 72, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(73, 73, 4, 8093, 13, 308, 309, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 73, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(74, 74, 4, 8094, 13, 1501, 1510, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 74, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(75, 75, 4, 8095, 13, 1157, 1163, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 75, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(76, 76, 4, 8096, 13, 165, 165, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 76, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(77, 77, 4, 8097, 13, 2262, 2267, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 77, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(78, 78, 4, 8098, 13, 1001, 1012, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 78, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(79, 79, 4, 8099, 13, 2538, 2541, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 79, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(80, 80, 4, 8101, 13, 792, 804, 12, 'u', 96, 0, 0, 96, 0.00, 'paid', 80, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(81, 81, 4, 8102, 13, 1021, 1029, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 81, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(82, 82, 4, 8103, 13, 2713, 2718, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 82, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(83, 83, 4, 8105, 13, 2225, 2240, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 83, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(84, 84, 4, 8107, 13, 370, 370, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 84, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(85, 85, 4, 8108, 13, 1321, 1333, 12, 'u', 96, 0, 0, 96, 0.00, 'paid', 85, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(86, 86, 4, 8109, 13, 1925, 1928, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 86, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(87, 87, 4, 8110, 13, 1038, 1038, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 87, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(88, 88, 4, 8112, 13, 507, 513, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 88, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(89, 89, 4, 8115, 13, 1979, 1982, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 89, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(90, 90, 4, 8117, 13, 588, 588, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 90, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(91, 91, 4, 8120, 13, 1296, 1304, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 91, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(92, 92, 4, 8121, 13, 4119, 4119, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 92, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(93, 93, 4, 8122, 13, 1698, 1703, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 93, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(94, 94, 4, 8123, 13, 1007, 1007, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 94, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(95, 95, 4, 8125, 13, 2525, 2525, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 95, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(96, 96, 4, 8126, 13, 541, 550, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 96, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(97, 97, 4, 8127, 13, 361, 364, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 97, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(98, 98, 4, 8129, 13, 3030, 3044, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 98, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(99, 99, 4, 8132, 13, 286, 286, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 99, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(100, 100, 4, 8133, 13, 2338, 2338, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 100, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(101, 101, 4, 8134, 13, 2186, 2190, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 101, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(102, 102, 4, 8139, 13, 2931, 2940, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 102, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(103, 103, 4, 8140, 13, 413, 413, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 103, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(104, 104, 4, 8141, 13, 2165, 2182, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 104, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(105, 105, 4, 8142, 13, 203, 206, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 105, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(106, 106, 4, 8143, 13, 412, 416, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 106, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(107, 107, 4, 8144, 13, 115, 120, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 107, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(108, 108, 4, 8145, 13, 3142, 3163, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 108, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(109, 109, 4, 8146, 13, 256, 277, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 109, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(110, 110, 4, 8147, 13, 1384, 1386, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 110, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(111, 111, 4, 8148, 13, 3180, 3187, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 111, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(112, 112, 4, 8149, 13, 1211, 1217, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 112, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(113, 113, 4, 8150, 13, 37, 39, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 113, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(114, 114, 4, 8151, 13, 1274, 1296, 22, 'u', 176, 0, 0, 176, 0.00, 'paid', 114, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(115, 115, 4, 8152, 13, 103, 110, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 115, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(116, 116, 4, 8153, 13, 1288, 1310, 22, 'u', 176, 0, 0, 176, 0.00, 'paid', 116, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(117, 117, 4, 8154, 13, 5050, 5066, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 117, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(118, 118, 4, 8156, 13, 120, 120, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 118, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(119, 119, 4, 8157, 13, 117, 120, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 119, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(120, 120, 4, 8158, 13, 1962, 1962, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 120, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(121, 121, 4, 8159, 13, 1210, 1224, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 121, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(122, 122, 4, 8160, 13, 496, 504, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 122, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(123, 123, 4, 8161, 13, 34, 37, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 123, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(124, 124, 4, 8162, 13, 130, 132, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 124, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(125, 125, 4, 8163, 13, 25, 60, 35, 'u', 280, 0, 0, 280, 0.00, 'paid', 125, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(126, 126, 4, 8164, 13, 583, 585, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 126, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(127, 127, 4, 8165, 13, 71, 71, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 127, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(128, 128, 4, 8166, 13, 2036, 2040, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 128, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(129, 129, 4, 8167, 13, 634, 643, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 129, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(130, 130, 4, 8169, 13, 2604, 2604, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 130, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(131, 131, 4, 8170, 13, 899, 904, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 131, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(132, 132, 4, 8171, 13, 80, 80, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 132, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(133, 133, 4, 8172, 13, 166, 175, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 133, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(134, 134, 4, 8173, 13, 175, 179, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 134, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(135, 135, 4, 8174, 13, 2202, 2213, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 135, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(136, 136, 4, 8175, 13, 5195, 5201, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 136, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(137, 137, 4, 8176, 13, 140, 144, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 137, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(138, 138, 4, 8177, 13, 210, 225, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 138, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(139, 139, 4, 8178, 13, 257, 257, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 139, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(140, 140, 4, 8179, 13, 159, 159, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 140, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(141, 141, 4, 8180, 13, 176, 185, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 141, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(142, 142, 4, 8181, 13, 174, 192, 18, 'u', 144, 0, 0, 144, 0.00, 'paid', 142, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(143, 143, 4, 8182, 13, 28, 29, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 143, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(144, 144, 4, 8183, 13, 905, 909, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 144, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(145, 145, 4, 8184, 13, 21, 45, 24, 'u', 192, 0, 0, 192, 0.00, 'paid', 145, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(146, 146, 4, 8185, 13, 3203, 3217, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 146, NULL, 2, '2026-10-30 19:25:47', '2026-11-05 17:00:00'),
(147, 147, 4, 15001, 13, 3359, 3359, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 147, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(148, 148, 4, 15002, 13, 3550, 3569, 19, 'u', 152, 0, 0, 152, 0.00, 'paid', 148, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(149, 149, 4, 15003, 13, 129, 129, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 149, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(150, 150, 4, 15004, 13, 908, 928, 20, 'u', 160, 0, 0, 160, 0.00, 'paid', 150, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(151, 151, 4, 15005, 13, 3014, 3038, 24, 'u', 192, 0, 0, 192, 0.00, 'paid', 151, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(152, 152, 4, 15006, 13, 1914, 1930, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 152, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(153, 153, 4, 15008, 13, 323, 324, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 153, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(154, 154, 4, 15009, 13, 1290, 1290, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 154, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(155, 155, 4, 15010, 13, 2327, 2327, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 155, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(156, 156, 4, 15011, 13, 1974, 1985, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 156, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(157, 157, 4, 15012, 13, 3287, 3295, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 157, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(158, 158, 4, 15014, 13, 3386, 3396, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 158, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(159, 159, 4, 15016, 13, 2954, 2966, 12, 'u', 96, 0, 0, 96, 0.00, 'paid', 159, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(160, 160, 4, 15017, 13, 4755, 4770, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 160, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(161, 161, 4, 15019, 13, 1904, 1912, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 161, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(162, 162, 4, 15020, 13, 1540, 1540, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 162, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(163, 163, 4, 15021, 13, 2928, 2939, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 163, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(164, 164, 4, 15024, 13, 2284, 2291, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 164, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(165, 165, 4, 15025, 13, 3144, 3160, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 165, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(166, 166, 4, 15026, 13, 5024, 5049, 25, 'u', 200, 0, 0, 200, 0.00, 'paid', 166, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(167, 167, 4, 15027, 13, 987, 996, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 167, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(168, 168, 4, 15028, 13, 3004, 3019, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 168, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(169, 169, 4, 15029, 13, 5717, 5736, 19, 'u', 152, 0, 0, 152, 0.00, 'paid', 169, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(170, 170, 4, 15030, 13, 2432, 2446, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 170, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(171, 171, 4, 15031, 13, 2115, 2116, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 171, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(172, 172, 4, 15032, 13, 46, 46, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 172, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(173, 173, 4, 15033, 13, 1725, 1725, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 173, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(174, 174, 4, 15034, 13, 3890, 3918, 28, 'u', 224, 0, 0, 224, 0.00, 'paid', 174, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(175, 175, 4, 15036, 13, 1627, 1635, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 175, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(176, 176, 4, 15037, 13, 3254, 3256, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 176, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(177, 177, 4, 15038, 13, 4344, 4377, 33, 'u', 264, 0, 0, 264, 0.00, 'paid', 177, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(178, 178, 4, 15039, 13, 2953, 2969, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 178, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(179, 179, 4, 15040, 13, 1450, 1457, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 179, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(180, 180, 4, 15041, 13, 670, 677, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 180, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(181, 181, 4, 15042, 13, 827, 858, 31, 'u', 248, 0, 0, 248, 0.00, 'paid', 181, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(182, 182, 4, 15043, 13, 3086, 3101, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 182, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(183, 183, 4, 15044, 13, 1212, 1239, 27, 'u', 216, 0, 0, 216, 0.00, 'paid', 183, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(184, 184, 4, 15045, 13, 3164, 3167, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 184, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(185, 185, 4, 15046, 13, 4474, 4478, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 185, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(186, 186, 4, 15047, 13, 1412, 1432, 20, 'u', 160, 0, 0, 160, 0.00, 'paid', 186, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(187, 187, 4, 15048, 13, 2005, 2005, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 187, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(188, 188, 4, 15049, 13, 132, 136, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 188, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(189, 189, 4, 15050, 13, 2446, 2489, 43, 'u', 344, 0, 0, 344, 0.00, 'paid', 189, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(190, 190, 4, 15051, 13, 2334, 2348, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 190, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(191, 191, 4, 15053, 13, 3298, 3322, 24, 'u', 192, 0, 0, 192, 0.00, 'paid', 191, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(192, 192, 4, 15054, 13, 1487, 1494, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 192, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(193, 193, 4, 15055, 13, 2587, 2590, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 193, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(194, 194, 4, 15056, 13, 52, 52, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 194, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(195, 195, 4, 15057, 13, 4275, 4288, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 195, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(196, 196, 4, 15058, 13, 3448, 3473, 25, 'u', 200, 0, 0, 200, 0.00, 'paid', 196, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(197, 197, 4, 15059, 13, 1102, 1107, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 197, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(198, 198, 4, 15060, 13, 1349, 1359, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 198, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(199, 199, 4, 15061, 13, 5402, 5424, 22, 'u', 176, 0, 0, 176, 0.00, 'paid', 199, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(200, 200, 4, 15062, 13, 3404, 3422, 18, 'u', 144, 0, 0, 144, 0.00, 'paid', 200, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(201, 201, 4, 15064, 13, 3315, 3320, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 201, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(202, 202, 4, 15065, 13, 1301, 1306, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 202, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(203, 203, 4, 15066, 13, 387, 392, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 203, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(204, 204, 4, 15067, 13, 4143, 4143, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 204, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(205, 205, 4, 15068, 13, 1234, 1245, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 205, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(206, 206, 4, 15069, 13, 5971, 5993, 22, 'u', 176, 0, 0, 176, 0.00, 'paid', 206, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(207, 207, 4, 15070, 13, 1055, 1059, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 207, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(208, 208, 4, 15071, 13, 4763, 4790, 27, 'u', 216, 0, 0, 216, 0.00, 'paid', 208, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(209, 209, 4, 15072, 13, 3769, 3777, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 209, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(210, 210, 4, 15073, 13, 4721, 4749, 28, 'u', 224, 0, 0, 224, 0.00, 'paid', 210, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(211, 211, 4, 15074, 13, 4186, 4204, 18, 'u', 144, 0, 0, 144, 0.00, 'paid', 211, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(212, 212, 4, 15075, 13, 4196, 4212, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 212, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(213, 213, 4, 15076, 13, 2067, 2073, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 213, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(214, 214, 4, 15077, 13, 4001, 4016, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 214, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(215, 215, 4, 15078, 13, 927, 935, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 215, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(216, 216, 4, 15079, 13, 3741, 3760, 19, 'u', 152, 0, 0, 152, 0.00, 'paid', 216, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(217, 217, 4, 15080, 13, 3905, 3938, 33, 'u', 264, 0, 0, 264, 0.00, 'paid', 217, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(218, 218, 4, 15081, 13, 1455, 1458, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 218, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(219, 219, 4, 15082, 13, 3478, 3499, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 219, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(220, 220, 4, 15083, 13, 856, 856, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 220, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(221, 221, 4, 15084, 13, 1793, 1811, 18, 'u', 144, 0, 0, 144, 0.00, 'paid', 221, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(222, 222, 4, 15085, 13, 4195, 4218, 23, 'u', 184, 0, 0, 184, 0.00, 'paid', 222, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(223, 223, 4, 15086, 13, 2544, 2565, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 223, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(224, 224, 4, 15087, 13, 5554, 5570, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 224, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(225, 225, 4, 15089, 13, 555, 555, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 225, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(226, 226, 4, 15090, 13, 2657, 2672, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 226, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(227, 227, 4, 15091, 13, 1849, 1865, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 227, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(228, 228, 4, 15093, 13, 1324, 1324, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 228, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(229, 229, 4, 15094, 13, 1245, 1262, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 229, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(230, 230, 4, 15095, 13, 254, 254, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 230, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(231, 231, 4, 15096, 13, 158, 158, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 231, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(232, 232, 4, 15097, 13, 188, 189, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 232, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(233, 233, 4, 15098, 13, 1423, 1423, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 233, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(234, 234, 4, 15099, 13, 2588, 2608, 20, 'u', 160, 0, 0, 160, 0.00, 'paid', 234, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(235, 235, 4, 15100, 13, 2583, 2591, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 235, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(236, 236, 4, 15101, 13, 64, 64, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 236, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(237, 237, 4, 15102, 13, 111, 111, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 237, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(238, 238, 4, 15103, 13, 896, 904, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 238, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(239, 239, 4, 15104, 13, 1577, 1582, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 239, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(240, 240, 4, 15105, 13, 987, 1018, 31, 'u', 248, 0, 0, 248, 0.00, 'paid', 240, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(241, 241, 4, 15106, 13, 695, 701, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 241, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(242, 242, 4, 15107, 13, 1123, 1123, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 242, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(243, 243, 4, 15108, 13, 233, 235, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 243, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(244, 244, 4, 15109, 13, 594, 594, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 244, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(245, 245, 4, 15110, 13, 100, 100, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 245, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(246, 246, 4, 15112, 13, 1677, 1677, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 246, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(247, 247, 4, 15113, 13, 345, 348, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 247, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(248, 248, 4, 15114, 13, 1246, 1263, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 248, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(249, 249, 4, 15116, 13, 2445, 2464, 19, 'u', 152, 0, 0, 152, 0.00, 'paid', 249, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(250, 250, 4, 15117, 13, 1626, 1626, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 250, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(251, 251, 4, 15118, 13, 1545, 1545, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 299, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(252, 252, 4, 15119, 13, 2997, 3006, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 300, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(253, 253, 4, 15121, 13, 4847, 4865, 18, 'u', 144, 0, 0, 144, 0.00, 'paid', 301, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(254, 254, 4, 15122, 13, 4369, 4406, 37, 'u', 296, 0, 0, 296, 0.00, 'paid', 302, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(255, 255, 4, 15123, 13, 261, 261, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 303, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(256, 256, 4, 15124, 13, 725, 730, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 304, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(257, 257, 4, 15125, 13, 1631, 1640, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 305, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(258, 258, 4, 15126, 13, 296, 296, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 306, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(259, 259, 4, 15127, 13, 1498, 1513, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 307, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(260, 260, 4, 15128, 13, 1145, 1145, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 308, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(261, 261, 4, 15130, 13, 431, 440, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 309, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(262, 262, 4, 15131, 13, 1260, 1260, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 262, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(263, 263, 4, 15132, 13, 1503, 1506, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 263, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(264, 264, 4, 15133, 13, 38, 38, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 264, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(265, 265, 4, 15134, 13, 592, 624, 32, 'u', 256, 0, 0, 256, 0.00, 'paid', 265, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(266, 266, 4, 15135, 13, 878, 891, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 266, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(267, 267, 4, 15136, 13, 110, 112, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 267, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(268, 268, 4, 15137, 13, 2378, 2394, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 268, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(269, 269, 4, 15138, 13, 560, 570, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 269, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(270, 270, 4, 15140, 13, 156, 156, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 270, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(271, 271, 4, 15141, 13, 307, 317, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 271, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(272, 272, 4, 15142, 13, 424, 451, 27, 'u', 216, 0, 0, 216, 0.00, 'paid', 272, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(273, 273, 4, 15143, 13, 854, 854, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 273, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(274, 274, 4, 15144, 13, 1158, 1158, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 274, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(275, 275, 4, 15145, 13, 346, 346, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 275, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(276, 276, 4, 15146, 13, 217, 217, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 276, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(277, 277, 4, 15147, 13, 512, 512, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 277, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(278, 278, 4, 15152, 13, 1363, 1377, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 278, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(279, 279, 4, 15153, 13, 1360, 1360, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 279, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(280, 280, 4, 15154, 13, 150, 150, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 280, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(281, 281, 4, 15156, 13, 822, 872, 50, 'u', 400, 0, 0, 400, 0.00, 'paid', 281, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(282, 282, 4, 15158, 13, 6, 6, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 282, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(283, 283, 4, 15159, 13, 318, 343, 25, 'u', 200, 0, 0, 200, 0.00, 'paid', 283, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(284, 284, 4, 15160, 13, 784, 787, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 284, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(285, 285, 4, 15161, 13, 2524, 2534, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 285, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(286, 286, 4, 15162, 13, 171, 175, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 286, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(287, 287, 4, 15164, 13, 178, 186, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 287, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(288, 288, 4, 15165, 13, 67, 67, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 288, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(289, 289, 4, 15166, 13, 149, 154, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 289, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(290, 290, 4, 15167, 13, 368, 370, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 290, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(291, 291, 4, 15168, 13, 183, 199, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 291, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(292, 292, 4, 15169, 13, 206, 217, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 292, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(293, 293, 4, 15170, 13, 1463, 1465, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 293, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(294, 294, 4, 15171, 13, 190, 231, 41, 'u', 328, 0, 0, 328, 0.00, 'paid', 294, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(295, 295, 4, 15172, 13, 26, 26, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 295, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(296, 296, 4, 15173, 13, 24, 67, 43, 'u', 344, 0, 0, 344, 0.00, 'paid', 296, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(297, 297, 4, 15174, 13, 19, 59, 40, 'u', 320, 0, 0, 320, 0.00, 'paid', 297, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(298, 298, 4, 15175, 13, 0, 41, 41, 'u', 328, 0, 0, 328, 0.00, 'paid', 298, NULL, 2, '2026-10-30 22:00:18', '2026-11-05 17:00:00'),
(299, 299, 4, 7001, 13, 4863, 4885, 22, 'u', 176, 0, 0, 176, 0.00, 'paid', 313, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(300, 300, 4, 7002, 13, 3280, 3317, 37, 'u', 296, 0, 0, 296, 0.00, 'paid', 314, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(301, 301, 4, 7003, 13, 3014, 3035, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 315, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(302, 302, 4, 7004, 13, 2023, 2036, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 316, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(303, 303, 4, 7005, 13, 2104, 2129, 25, 'u', 200, 0, 0, 200, 0.00, 'paid', 317, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(304, 304, 4, 7006, 13, 2867, 2870, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 318, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(305, 305, 4, 7007, 13, 1026, 1027, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 319, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(306, 306, 4, 7008, 13, 2588, 2600, 12, 'u', 96, 0, 0, 96, 0.00, 'paid', 320, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(307, 307, 4, 7009, 13, 4285, 4305, 20, 'u', 160, 0, 0, 160, 0.00, 'paid', 321, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(308, 308, 4, 7011, 13, 7376, 7403, 27, 'u', 216, 0, 0, 216, 0.00, 'paid', 322, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(309, 309, 4, 7012, 13, 5469, 5478, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 323, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(310, 310, 4, 7013, 13, 942, 948, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 324, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(311, 311, 4, 7014, 13, 680, 681, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 325, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(312, 312, 4, 7015, 13, 1899, 1902, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 326, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(313, 313, 4, 7016, 13, 2781, 2820, 39, 'u', 312, 0, 0, 312, 0.00, 'paid', 327, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(314, 314, 4, 7017, 13, 1816, 1843, 27, 'u', 216, 0, 0, 216, 0.00, 'paid', 328, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(315, 315, 4, 7018, 13, 3006, 3009, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 329, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(316, 316, 4, 7019, 13, 1426, 1435, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 330, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(317, 317, 4, 7020, 13, 2940, 2960, 20, 'u', 160, 0, 0, 160, 0.00, 'paid', 331, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(318, 318, 4, 7022, 13, 3031, 3036, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 332, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(319, 319, 4, 7023, 13, 6183, 6193, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 333, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(320, 320, 4, 7024, 13, 1226, 1226, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 334, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(321, 321, 4, 7025, 13, 1777, 1801, 24, 'u', 192, 0, 0, 192, 0.00, 'paid', 335, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(322, 322, 4, 7026, 13, 4099, 4108, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 336, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(323, 323, 4, 7027, 13, 1967, 1967, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 337, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(324, 324, 4, 7028, 13, 1119, 1125, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 338, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(325, 325, 4, 7029, 13, 2519, 2536, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 339, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(326, 326, 4, 7030, 13, 3331, 3338, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 340, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(327, 327, 4, 7031, 13, 3563, 3572, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 341, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(328, 328, 4, 7032, 13, 2168, 2173, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 342, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(329, 329, 4, 7033, 13, 7169, 7192, 23, 'u', 184, 0, 0, 184, 0.00, 'paid', 343, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(330, 330, 4, 7034, 13, 1363, 1364, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 344, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(331, 331, 4, 7035, 13, 1430, 1432, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 345, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(332, 332, 4, 7036, 13, 1595, 1595, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 346, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(333, 333, 4, 7037, 13, 8381, 8408, 27, 'u', 216, 0, 0, 216, 0.00, 'paid', 347, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(334, 334, 4, 7038, 13, 3189, 3206, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 348, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(335, 335, 4, 7039, 13, 2455, 2467, 12, 'u', 96, 0, 0, 96, 0.00, 'paid', 349, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(336, 336, 4, 7040, 13, 697, 697, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 350, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(337, 337, 4, 7041, 13, 6755, 6776, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 351, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(338, 338, 4, 7042, 13, 1732, 1732, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 352, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(339, 339, 4, 7043, 13, 2109, 2113, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 353, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(340, 340, 4, 7045, 13, 3149, 3164, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 354, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(341, 341, 4, 7046, 13, 4216, 4233, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 355, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(342, 342, 4, 7047, 13, 486, 488, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 356, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(343, 343, 4, 7048, 13, 5730, 5762, 32, 'u', 256, 0, 0, 256, 0.00, 'paid', 357, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(344, 344, 4, 7049, 13, 4006, 4027, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 358, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(345, 345, 4, 7050, 13, 314, 314, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 359, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(346, 346, 4, 7051, 13, 1917, 1933, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 360, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(347, 347, 4, 7052, 13, 3564, 3587, 23, 'u', 184, 0, 0, 184, 0.00, 'paid', 361, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(348, 348, 4, 7053, 13, 4463, 4478, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 362, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(349, 349, 4, 7054, 13, 228, 228, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 363, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(350, 350, 4, 7055, 13, 2338, 2348, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 364, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(351, 351, 4, 7056, 13, 4057, 4081, 24, 'u', 192, 0, 0, 192, 0.00, 'paid', 365, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(352, 352, 4, 7057, 13, 790, 797, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 366, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(353, 353, 4, 7058, 13, 1392, 1393, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 367, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(354, 354, 4, 7059, 13, 2325, 2339, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 368, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(355, 355, 4, 7060, 13, 1234, 1234, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 369, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(356, 356, 4, 7061, 13, 1026, 1029, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 370, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(357, 357, 4, 7064, 13, 531, 532, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 371, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(358, 358, 4, 7065, 13, 992, 992, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 372, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(359, 359, 4, 7066, 13, 1383, 1383, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 373, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(360, 360, 4, 7067, 13, 1169, 1169, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 374, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:51'),
(361, 361, 4, 7068, 13, 4816, 4851, 35, 'u', 280, 0, 0, 280, 0.00, 'paid', 375, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(362, 362, 4, 7069, 13, 3506, 3522, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 376, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(363, 363, 4, 7070, 13, 719, 724, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 377, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(364, 364, 4, 7071, 13, 1799, 1802, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 378, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(365, 365, 4, 7072, 13, 1747, 1760, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 379, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(366, 366, 4, 7073, 13, 385, 398, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 380, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(367, 367, 4, 7074, 13, 2936, 2946, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 381, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(368, 368, 4, 7075, 13, 516, 526, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 382, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(369, 369, 4, 7076, 13, 1097, 1097, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 383, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(370, 370, 4, 7077, 13, 768, 772, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 384, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(371, 371, 4, 7078, 13, 2181, 2202, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 385, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(372, 372, 4, 7079, 13, 1214, 1219, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 386, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(373, 373, 4, 7080, 13, 4311, 4363, 52, 'u', 416, 0, 0, 416, 0.00, 'paid', 387, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(374, 374, 4, 7081, 13, 3365, 3384, 19, 'u', 152, 0, 0, 152, 0.00, 'paid', 388, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(375, 375, 4, 7082, 13, 1502, 1503, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 389, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(376, 376, 4, 7083, 13, 1943, 1945, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 390, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(377, 377, 4, 7085, 13, 3341, 3358, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 391, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(378, 378, 4, 7087, 13, 4970, 4999, 29, 'u', 232, 0, 0, 232, 0.00, 'paid', 392, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(379, 379, 4, 7088, 13, 1155, 1161, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 393, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(380, 380, 4, 7089, 13, 1843, 1843, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 394, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(381, 381, 4, 7090, 13, 1606, 1632, 26, 'u', 208, 0, 0, 208, 0.00, 'paid', 395, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52');
INSERT INTO `tw_invoice` (`id`, `inv_no`, `org_id_fk`, `meter_id_fk`, `inv_period_id_fk`, `lastmeter`, `currentmeter`, `water_used`, `inv_type`, `paid`, `reserve_meter`, `vat`, `totalpaid`, `previous_balance`, `status`, `acc_trans_id_fk`, `comment`, `recorder_id`, `created_at`, `updated_at`) VALUES
(382, 382, 4, 7092, 13, 346, 346, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 396, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(383, 383, 4, 7093, 13, 969, 972, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 397, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(384, 384, 4, 7094, 13, 2002, 2007, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 398, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(385, 385, 4, 7095, 13, 1671, 1679, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 399, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(386, 386, 4, 7096, 13, 1121, 1129, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 400, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(387, 387, 4, 7097, 13, 1760, 1774, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 401, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(388, 388, 4, 7098, 13, 2365, 2379, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 402, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(389, 389, 4, 7101, 13, 3012, 3015, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 403, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(390, 390, 4, 7102, 13, 2303, 2312, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 404, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(391, 391, 4, 7103, 13, 5264, 5281, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 405, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(392, 392, 4, 7104, 13, 2028, 2035, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 406, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(393, 393, 4, 7105, 13, 1054, 1054, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 407, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(394, 394, 4, 7107, 13, 1170, 1180, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 408, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(395, 395, 4, 7108, 13, 1183, 1188, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 409, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(396, 396, 4, 7109, 13, 755, 758, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 410, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(397, 397, 4, 7110, 13, 901, 905, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 411, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(398, 398, 4, 7111, 13, 540, 540, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 412, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(399, 399, 4, 7112, 13, 1054, 1059, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 413, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(400, 400, 4, 7113, 13, 2330, 2330, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 414, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(401, 401, 4, 7114, 13, 3452, 3478, 26, 'u', 208, 0, 0, 208, 0.00, 'paid', 415, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(402, 402, 4, 7115, 13, 1174, 1178, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 416, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(403, 403, 4, 7116, 13, 1640, 1649, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 417, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(404, 404, 4, 7117, 13, 939, 956, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 418, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(405, 405, 4, 7118, 13, 2720, 2749, 29, 'u', 232, 0, 0, 232, 0.00, 'paid', 419, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(406, 406, 4, 7119, 13, 262, 268, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 420, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(407, 407, 4, 7120, 13, 1122, 1126, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 421, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(408, 408, 4, 7121, 13, 2905, 2918, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 422, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(409, 409, 4, 7122, 13, 2239, 2251, 12, 'u', 96, 0, 0, 96, 0.00, 'paid', 423, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(410, 410, 4, 7123, 13, 1251, 1259, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 424, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(411, 411, 4, 7124, 13, 728, 739, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 425, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(412, 412, 4, 7125, 13, 1754, 1758, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 426, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(413, 413, 4, 7126, 13, 1176, 1179, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 427, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(414, 414, 4, 7127, 13, 7519, 7532, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 428, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(415, 415, 4, 7128, 13, 1212, 1229, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 429, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(416, 416, 4, 7130, 13, 2365, 2380, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 430, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(417, 417, 4, 7132, 13, 1069, 1070, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 431, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(418, 418, 4, 7133, 13, 2644, 2654, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 432, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(419, 419, 4, 7134, 13, 1479, 1484, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 433, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(420, 420, 4, 7136, 13, 1358, 1366, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 434, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:52'),
(421, 421, 4, 7137, 13, 1114, 1117, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 435, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(422, 422, 4, 7138, 13, 2667, 2669, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 436, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(423, 423, 4, 7139, 13, 3498, 3531, 33, 'u', 264, 0, 0, 264, 0.00, 'paid', 437, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(424, 424, 4, 7141, 13, 1839, 1843, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 438, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(425, 425, 4, 7142, 13, 130, 130, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 439, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(426, 426, 4, 7143, 13, 1624, 1640, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 440, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(427, 427, 4, 7144, 13, 389, 389, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 441, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(428, 428, 4, 7145, 13, 1136, 1136, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 442, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(429, 429, 4, 7146, 13, 1140, 1152, 12, 'u', 96, 0, 0, 96, 0.00, 'paid', 443, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(430, 430, 4, 7147, 13, 3164, 3167, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 444, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(431, 431, 4, 7148, 13, 347, 361, 14, 'u', 112, 0, 0, 112, 0.00, 'paid', 445, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(432, 432, 4, 7149, 13, 1524, 1524, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 446, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(433, 433, 4, 7150, 13, 1329, 1334, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 447, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(434, 434, 4, 7151, 13, 762, 769, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 448, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(435, 435, 4, 7152, 13, 510, 518, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 449, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(436, 436, 4, 7153, 13, 1473, 1484, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 450, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(437, 437, 4, 7154, 13, 1429, 1437, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 451, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(438, 438, 4, 7155, 13, 163, 164, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 452, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(439, 439, 4, 7156, 13, 1426, 1448, 22, 'u', 176, 0, 0, 176, 0.00, 'paid', 453, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(440, 440, 4, 7158, 13, 1039, 1059, 20, 'u', 160, 0, 0, 160, 0.00, 'paid', 454, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(441, 441, 4, 7159, 13, 1338, 1359, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 455, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(442, 442, 4, 7160, 13, 377, 377, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 456, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(443, 443, 4, 7161, 13, 126, 127, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 457, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(444, 444, 4, 7162, 13, 1024, 1032, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 458, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(445, 445, 4, 7164, 13, 282, 294, 12, 'u', 96, 0, 0, 96, 0.00, 'paid', 459, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(446, 446, 4, 7165, 13, 892, 899, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 460, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(447, 447, 4, 7166, 13, 894, 913, 19, 'u', 152, 0, 0, 152, 0.00, 'paid', 461, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(448, 448, 4, 7168, 13, 2078, 2100, 22, 'u', 176, 0, 0, 176, 0.00, 'paid', 462, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(449, 449, 4, 7169, 13, 945, 961, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 463, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(450, 450, 4, 7170, 13, 1344, 1362, 18, 'u', 144, 0, 0, 144, 0.00, 'paid', 464, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(451, 451, 4, 7171, 13, 2401, 2418, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 465, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(452, 452, 4, 7172, 13, 1586, 1588, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 466, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(453, 453, 4, 7173, 13, 1915, 1916, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 467, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(454, 454, 4, 7174, 13, 444, 446, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 468, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(455, 455, 4, 7175, 13, 151, 151, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 469, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(456, 456, 4, 7176, 13, 2102, 2130, 28, 'u', 224, 0, 0, 224, 0.00, 'paid', 470, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(457, 457, 4, 7179, 13, 113, 113, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 471, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(458, 458, 4, 7180, 13, 1095, 1102, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 472, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(459, 459, 4, 7181, 13, 37, 38, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 473, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(460, 460, 4, 7182, 13, 875, 876, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 474, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(461, 461, 4, 7183, 13, 87, 89, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 475, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(462, 462, 4, 7184, 13, 1060, 1073, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 476, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(463, 463, 4, 7185, 13, 492, 497, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 477, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(464, 464, 4, 7186, 13, 470, 476, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 478, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(465, 465, 4, 7187, 13, 720, 729, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 479, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(466, 466, 4, 7188, 13, 678, 688, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 480, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(467, 467, 4, 7189, 13, 460, 460, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 481, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(468, 468, 4, 7190, 13, 33, 33, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 482, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(469, 469, 4, 7191, 13, 196, 197, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 483, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(470, 470, 4, 7192, 13, 159, 167, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 484, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(471, 471, 4, 7193, 13, 5, 5, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 485, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(472, 472, 4, 7195, 13, 984, 1004, 20, 'u', 160, 0, 0, 160, 0.00, 'paid', 486, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(473, 473, 4, 7196, 13, 806, 827, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 487, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(474, 474, 4, 7197, 13, 406, 412, 6, 'u', 48, 0, 0, 48, 0.00, 'paid', 488, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(475, 475, 4, 7198, 13, 131, 138, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 489, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(476, 476, 4, 7199, 13, 1665, 1719, 54, 'u', 432, 0, 0, 432, 0.00, 'paid', 490, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(477, 477, 4, 7200, 13, 216, 216, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 491, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(478, 478, 4, 7201, 13, 127, 127, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 492, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(479, 479, 4, 7203, 13, 1097, 1097, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 493, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(480, 480, 4, 7204, 13, 456, 479, 23, 'u', 184, 0, 0, 184, 0.00, 'paid', 494, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(481, 481, 4, 7205, 13, 103, 103, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 495, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(482, 482, 4, 7206, 13, 1304, 1304, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 496, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(483, 483, 4, 7207, 13, 1058, 1058, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 497, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(484, 484, 4, 7208, 13, 654, 665, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 498, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(485, 485, 4, 7209, 13, 1645, 1657, 12, 'u', 96, 0, 0, 96, 0.00, 'paid', 499, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(486, 486, 4, 7210, 13, 133, 133, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 500, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(487, 487, 4, 7211, 13, 1918, 2029, 111, 'u', 888, 0, 0, 888, 0.00, 'paid', 501, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(488, 488, 4, 7212, 13, 7, 7, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 502, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(489, 489, 4, 7213, 13, 321, 350, 29, 'u', 232, 0, 0, 232, 0.00, 'paid', 503, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:53'),
(490, 490, 4, 7214, 13, 93, 102, 9, 'u', 72, 0, 0, 72, 0.00, 'paid', 504, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:54'),
(491, 491, 4, 7215, 13, 143, 164, 21, 'u', 168, 0, 0, 168, 0.00, 'paid', 505, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:54'),
(492, 492, 4, 7216, 13, 138, 145, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 506, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:54'),
(493, 493, 4, 7217, 13, 364, 382, 18, 'u', 144, 0, 0, 144, 0.00, 'paid', 507, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:54'),
(494, 494, 4, 7218, 13, 3, 3, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 508, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:54'),
(495, 495, 4, 7219, 13, 13, 32, 19, 'u', 152, 0, 0, 152, 0.00, 'paid', 509, NULL, 2, '2026-10-30 23:01:41', '2026-11-04 05:42:54'),
(496, 496, 4, 16001, 13, 3041, 3048, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 510, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(497, 497, 4, 16002, 13, 2328, 2330, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 511, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(498, 498, 4, 16003, 13, 739, 742, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 512, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(499, 499, 4, 16004, 13, 841, 844, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 513, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(500, 500, 4, 16005, 13, 1617, 1622, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 514, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(501, 501, 4, 16006, 13, 5219, 5254, 35, 'u', 280, 0, 0, 280, 0.00, 'paid', 515, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(502, 502, 4, 16007, 13, 805, 806, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 516, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(503, 503, 4, 16008, 13, 2284, 2291, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 517, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(504, 504, 4, 16009, 13, 1706, 1710, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 518, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(505, 505, 4, 16010, 13, 557, 557, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 519, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(506, 506, 4, 16011, 13, 1263, 1263, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 520, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(507, 507, 4, 16012, 13, 1619, 1621, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 521, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(508, 508, 4, 16013, 13, 4079, 4083, 4, 'u', 32, 0, 0, 32, 0.00, 'paid', 522, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(509, 509, 4, 16014, 13, 711, 771, 60, 'u', 480, 0, 0, 480, 0.00, 'paid', 523, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(510, 510, 4, 16015, 13, 1117, 1117, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 524, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(511, 511, 4, 16016, 13, 14436, 14436, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 525, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(512, 512, 4, 16017, 13, 2863, 2868, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 526, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(513, 513, 4, 16018, 13, 2132, 2142, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 527, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(514, 514, 4, 16019, 13, 901, 902, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 528, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(515, 515, 4, 16020, 13, 2523, 2523, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 529, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(516, 516, 4, 16021, 13, 2068, 2068, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 530, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(517, 517, 4, 16022, 13, 1923, 1923, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 531, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(518, 518, 4, 16023, 13, 589, 589, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 532, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(519, 519, 4, 16024, 13, 698, 698, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 533, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(520, 520, 4, 16026, 13, 1617, 1617, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 534, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(521, 521, 4, 16027, 13, 959, 959, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 535, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(522, 522, 4, 16028, 13, 3683, 3683, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 536, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(523, 523, 4, 16029, 13, 1591, 1604, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 537, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(524, 524, 4, 16030, 13, 2387, 2387, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 538, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(525, 525, 4, 16031, 13, 2013, 2015, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 539, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(526, 526, 4, 16032, 13, 3194, 3205, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 540, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(527, 527, 4, 16033, 13, 1496, 1496, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 541, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(528, 528, 4, 16034, 13, 177, 177, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 542, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(529, 529, 4, 16035, 13, 785, 785, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 543, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(530, 530, 4, 16036, 13, 2024, 2024, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 544, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(531, 531, 4, 16038, 13, 926, 926, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 545, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(532, 532, 4, 16039, 13, 3515, 3531, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 546, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(533, 533, 4, 16040, 13, 2585, 2598, 13, 'u', 104, 0, 0, 104, 0.00, 'paid', 547, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(534, 534, 4, 16041, 13, 1741, 1741, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 548, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(535, 535, 4, 16042, 13, 1148, 1153, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 549, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(536, 536, 4, 16043, 13, 920, 920, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 550, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(537, 537, 4, 16044, 13, 1839, 1849, 10, 'u', 80, 0, 0, 80, 0.00, 'paid', 551, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(538, 538, 4, 16045, 13, 44, 44, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 552, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(539, 539, 4, 16046, 13, 843, 843, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 553, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(540, 540, 4, 16047, 13, 5497, 5532, 35, 'u', 280, 0, 0, 280, 0.00, 'paid', 554, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(541, 541, 4, 16048, 13, 1296, 1303, 7, 'u', 56, 0, 0, 56, 0.00, 'paid', 555, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(542, 542, 4, 16049, 13, 310, 310, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 556, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(543, 543, 4, 16050, 13, 1230, 1230, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 557, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(544, 544, 4, 16051, 13, 1944, 1946, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 558, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(545, 545, 4, 16052, 13, 1704, 1720, 16, 'u', 128, 0, 0, 128, 0.00, 'paid', 559, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(546, 546, 4, 16053, 13, 2130, 2131, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 560, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(547, 547, 4, 16054, 13, 3183, 3183, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 561, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(548, 548, 4, 16055, 13, 1720, 1731, 11, 'u', 88, 0, 0, 88, 0.00, 'paid', 562, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(549, 549, 4, 16056, 13, 1264, 1264, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 563, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(550, 550, 4, 16057, 13, 751, 751, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 564, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(551, 551, 4, 16058, 13, 142, 143, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 565, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(552, 552, 4, 16059, 13, 724, 743, 19, 'u', 152, 0, 0, 152, 0.00, 'paid', 566, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(553, 553, 4, 16060, 13, 89, 97, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 567, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(554, 554, 4, 16061, 13, 433, 433, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 568, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(555, 555, 4, 16062, 13, 1572, 1587, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 569, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(556, 556, 4, 16063, 13, 204, 207, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 570, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(557, 557, 4, 16064, 13, 29, 30, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 571, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(558, 558, 4, 16065, 13, 931, 967, 36, 'u', 288, 0, 0, 288, 0.00, 'paid', 572, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(559, 559, 4, 16066, 13, 4962, 4962, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 573, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(560, 560, 4, 16067, 13, 597, 612, 15, 'u', 120, 0, 0, 120, 0.00, 'paid', 574, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(561, 561, 4, 16069, 13, 747, 748, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 575, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(562, 562, 4, 16070, 13, 1679, 1679, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 576, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(563, 563, 4, 16072, 13, 1875, 1876, 1, 'u', 8, 0, 0, 8, 0.00, 'paid', 577, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(564, 564, 4, 16073, 13, 571, 571, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 578, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(565, 565, 4, 16074, 13, 1294, 1294, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 579, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(566, 566, 4, 16075, 13, 1897, 1897, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 580, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(567, 567, 4, 16076, 13, 3830, 3838, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 581, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(568, 568, 4, 16077, 13, 2633, 2633, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 582, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(569, 569, 4, 16078, 13, 1442, 1442, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 583, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(570, 570, 4, 16079, 13, 1628, 1628, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 584, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(571, 571, 4, 16080, 13, 763, 763, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 585, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(572, 572, 4, 16081, 13, 1272, 1275, 3, 'u', 24, 0, 0, 24, 0.00, 'paid', 586, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(573, 573, 4, 16082, 13, 2028, 2028, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 587, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(574, 574, 4, 16083, 13, 2712, 2712, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 588, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(575, 575, 4, 16084, 13, 579, 579, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 589, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(576, 576, 4, 16085, 13, 1933, 1933, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 590, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(577, 577, 4, 16086, 13, 1314, 1314, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 591, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(578, 578, 4, 16087, 13, 720, 720, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 592, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(579, 579, 4, 16088, 13, 2541, 2541, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 593, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(580, 580, 4, 16089, 13, 470, 470, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 594, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(581, 581, 4, 16090, 13, 396, 398, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 595, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(582, 582, 4, 16091, 13, 807, 807, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 596, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(583, 583, 4, 16093, 13, 6241, 6246, 5, 'u', 40, 0, 0, 40, 0.00, 'paid', 597, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(584, 584, 4, 16095, 13, 941, 943, 2, 'u', 16, 0, 0, 16, 0.00, 'paid', 598, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(585, 585, 4, 16097, 13, 906, 906, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 599, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(586, 586, 4, 16098, 13, 2587, 2604, 17, 'u', 136, 0, 0, 136, 0.00, 'paid', 600, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(587, 587, 4, 16099, 13, 597, 597, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 601, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(588, 588, 4, 16100, 13, 182, 182, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 602, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(589, 589, 4, 16104, 13, 919, 919, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 603, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(590, 590, 4, 16105, 13, 2442, 2468, 26, 'u', 208, 0, 0, 208, 0.00, 'paid', 604, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(591, 591, 4, 16106, 13, 1866, 1866, 0, 'r', 0, 0, 0, 0, 0.00, 'paid', 605, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00'),
(592, 592, 4, 16108, 13, 1127, 1135, 8, 'u', 64, 0, 0, 64, 0.00, 'paid', 606, NULL, 2, '2026-10-31 00:11:52', '2026-11-05 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tw_invoice_history`
--

CREATE TABLE `tw_invoice_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inv_no` int(22) NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `meter_id_fk` bigint(20) UNSIGNED NOT NULL,
  `inv_period_id_fk` bigint(20) UNSIGNED NOT NULL,
  `lastmeter` float NOT NULL,
  `currentmeter` float NOT NULL,
  `water_used` float NOT NULL,
  `inv_type` enum('r','u') NOT NULL COMMENT 'r =รักษามิเตอร์, u=ใช้น้ำ',
  `paid` float NOT NULL,
  `reserve_meter` float NOT NULL,
  `vat` float NOT NULL,
  `totalpaid` float NOT NULL,
  `status` enum('init','invoice','paid','owe','deleted') NOT NULL,
  `acc_trans_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `comment` varchar(191) DEFAULT NULL,
  `recorder_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tw_invoice_temp`
--

CREATE TABLE `tw_invoice_temp` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meter_id_fk` bigint(20) UNSIGNED NOT NULL,
  `inv_period_id_fk` bigint(20) UNSIGNED NOT NULL,
  `lastmeter` float NOT NULL,
  `currentmeter` float NOT NULL,
  `water_used` float NOT NULL,
  `inv_type` enum('r','u') NOT NULL COMMENT 'r =รักษามิเตอร์, u=ใช้น้ำ',
  `paid` float NOT NULL,
  `reserve_meter` float NOT NULL,
  `vat` float NOT NULL,
  `totalpaid` float NOT NULL,
  `status` enum('init','invoice','paid','owe','deleted') NOT NULL,
  `acc_trans_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `comment` varchar(191) DEFAULT NULL,
  `recorder_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tw_meter_infos`
--

CREATE TABLE `tw_meter_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meter_id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `factory_no` varchar(191) NOT NULL,
  `submeter_name` varchar(191) DEFAULT NULL,
  `meter_address` varchar(191) DEFAULT NULL,
  `undertake_zone_id` bigint(20) UNSIGNED NOT NULL,
  `undertake_subzone_id` bigint(20) UNSIGNED NOT NULL,
  `acceptance_date` date NOT NULL,
  `status` enum('active','inactive','deleted') NOT NULL DEFAULT 'active',
  `comment` varchar(191) DEFAULT NULL,
  `metertype_id` bigint(20) UNSIGNED DEFAULT NULL,
  `owe_count` int(11) NOT NULL DEFAULT 0,
  `cutmeter` enum('1','0') NOT NULL DEFAULT '0',
  `inv_no_index` int(11) DEFAULT 0,
  `payment_id` int(11) NOT NULL DEFAULT 0,
  `recorder_id` int(11) DEFAULT NULL,
  `last_meter_recording` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `old_meter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_meter_infos`
--

INSERT INTO `tw_meter_infos` (`id`, `meter_id`, `org_id_fk`, `user_id`, `factory_no`, `submeter_name`, `meter_address`, `undertake_zone_id`, `undertake_subzone_id`, `acceptance_date`, `status`, `comment`, `metertype_id`, `owe_count`, `cutmeter`, `inv_no_index`, `payment_id`, `recorder_id`, `last_meter_recording`, `created_at`, `updated_at`, `old_meter_id`) VALUES
(1, 8001, 4, 3, '', '', '2', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 274, 1, 1, 31, '2026-10-30 19:25:36', '2026-11-02 23:05:05', NULL),
(2, 8002, 4, 4, '', '', '258', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 275, 1, 1, 1913, '2026-10-30 19:25:37', '2026-11-02 23:05:05', NULL),
(3, 8003, 4, 5, '', '', '39', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 276, 1, 1, 926, '2026-10-30 19:25:37', '2026-11-02 23:05:05', NULL),
(4, 8004, 4, 6, '', '', '63', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 277, 1, 1, 3075, '2026-10-30 19:25:37', '2026-11-02 23:05:05', NULL),
(5, 8005, 4, 7, '', '', '58', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 278, 1, 1, 4996, '2026-10-30 19:25:37', '2026-11-02 23:05:05', NULL),
(6, 8006, 4, 8, '', '', '55', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 279, 1, 1, 2041, '2026-10-30 19:25:37', '2026-11-02 23:05:05', NULL),
(7, 8007, 4, 9, '', '', '199', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 280, 1, 1, 253, '2026-10-30 19:25:37', '2026-11-02 23:05:05', NULL),
(8, 8008, 4, 10, '', '', '46', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 281, 1, 1, 2041, '2026-10-30 19:25:37', '2026-11-02 23:05:05', NULL),
(9, 8009, 4, 11, '', '', '66', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 282, 1, 1, 1219, '2026-10-30 19:25:37', '2026-11-02 23:05:05', NULL),
(10, 8010, 4, 12, '', '', '73', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 283, 1, 1, 997, '2026-10-30 19:25:37', '2026-11-02 23:05:05', NULL),
(11, 8011, 4, 13, '', '', '75', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 284, 1, 1, 1489, '2026-10-30 19:25:37', '2026-11-02 23:05:05', NULL),
(12, 8012, 4, 14, '', '', '76', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 285, 1, 1, 1588, '2026-10-30 19:25:38', '2026-11-02 23:05:05', NULL),
(13, 8013, 4, 15, '', '', '60', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 286, 1, 1, 1224, '2026-10-30 19:25:38', '2026-11-02 23:05:05', NULL),
(14, 8014, 4, 16, '', '', '19', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 287, 1, 1, 2827, '2026-10-30 19:25:38', '2026-11-02 23:05:05', NULL),
(15, 8015, 4, 17, '', '', '319', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 288, 1, 1, 2824, '2026-10-30 19:25:38', '2026-11-02 23:05:05', NULL),
(16, 8016, 4, 18, '', '', '93', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 289, 1, 1, 3509, '2026-10-30 19:25:38', '2026-11-02 23:05:05', NULL),
(17, 8017, 4, 19, '', '', '21', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 290, 1, 1, 1810, '2026-10-30 19:25:38', '2026-11-02 23:05:05', NULL),
(18, 8018, 4, 20, '', '', '208', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 291, 1, 1, 1254, '2026-10-30 19:25:38', '2026-11-02 23:05:05', NULL),
(19, 8020, 4, 21, '', '', '126', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 292, 1, 1, 3001, '2026-10-30 19:25:38', '2026-11-02 23:05:06', NULL),
(20, 8022, 4, 22, '', '', '77', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 293, 1, 1, 1022, '2026-10-30 19:25:38', '2026-11-02 23:05:06', NULL),
(21, 8023, 4, 23, '', '', '301', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 294, 1, 1, 892, '2026-10-30 19:25:38', '2026-11-02 23:05:06', NULL),
(22, 8025, 4, 24, '', '', '241', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 295, 1, 1, 1022, '2026-10-30 19:25:38', '2026-11-02 23:05:06', NULL),
(23, 8028, 4, 25, '', '', '7', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 296, 1, 1, 1385, '2026-10-30 19:25:38', '2026-11-02 23:05:06', NULL),
(24, 8029, 4, 26, '', '', '7', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 297, 1, 1, 1656, '2026-10-30 19:25:38', '2026-11-02 23:05:06', NULL),
(25, 8030, 4, 27, '', '', '116', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 298, 1, 1, 3539, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(26, 8031, 4, 28, '', '', '8', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 299, 1, 1, 2996, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(27, 8032, 4, 29, '', '', '252', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 300, 1, 1, 1745, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(28, 8036, 4, 30, '', '', '382', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 583, 1, 1, 637, '2026-10-30 19:25:39', '2026-11-03 00:12:11', NULL),
(29, 8037, 4, 31, '', '', '224', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 584, 1, 1, 1164, '2026-10-30 19:25:39', '2026-11-03 00:12:11', NULL),
(30, 8038, 4, 32, '', '', '129', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 585, 1, 1, 1427, '2026-10-30 19:25:39', '2026-11-03 00:12:11', NULL),
(31, 8039, 4, 33, '', '', '36', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 304, 1, 1, 909, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(32, 8041, 4, 34, '', '', '202', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 305, 1, 1, 630, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(33, 8042, 4, 35, '', '', '328', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 306, 1, 1, 665, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(34, 8043, 4, 36, '', '', '321', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 307, 1, 1, 3205, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(35, 8046, 4, 37, '', '', '45', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 308, 1, 1, 2369, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(36, 8047, 4, 38, '', '', '318', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 309, 1, 1, 6212, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(37, 8048, 4, 39, '', '', '184', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 310, 1, 1, 1990, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(38, 8049, 4, 40, '', '', '29', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 311, 1, 1, 1741, '2026-10-30 19:25:39', '2026-11-02 23:05:06', NULL),
(39, 8050, 4, 41, '', '', '254', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 312, 1, 1, 18, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(40, 8051, 4, 42, '', '', '11', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 313, 1, 1, 4486, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(41, 8052, 4, 43, '', '', '236', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 314, 1, 1, 6884, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(42, 8053, 4, 44, '', '', '238', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 315, 1, 1, 2664, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(43, 8054, 4, 45, '', '', '242', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 316, 1, 1, 3238, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(44, 8057, 4, 46, '', '', '112', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 317, 1, 1, 5029, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(45, 8058, 4, 47, '', '', '66', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 318, 1, 1, 85, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(46, 8061, 4, 48, '', '', '176', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 319, 1, 1, 1857, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(47, 8062, 4, 49, '', '', '97', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 320, 1, 1, 1510, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(48, 8063, 4, 50, '', '', '37', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 321, 1, 1, 1710, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(49, 8064, 4, 51, '', '', '101', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 322, 1, 1, 408, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(50, 8065, 4, 52, '', '', '69', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 323, 1, 1, 2151, '2026-10-30 19:25:40', '2026-11-02 23:05:06', NULL),
(51, 8066, 4, 53, '', '', '232', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 324, 1, 1, 2545, '2026-10-30 19:25:40', '2026-11-02 23:08:44', NULL),
(52, 8067, 4, 54, '', '', '248', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 325, 1, 1, 5247, '2026-10-30 19:25:40', '2026-11-02 23:08:44', NULL),
(53, 8069, 4, 55, '', '', '297', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 326, 1, 1, 5040, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(54, 8070, 4, 56, '', '', '239', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 327, 1, 1, 4493, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(55, 8071, 4, 57, '', '', '166', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 328, 1, 1, 2423, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(56, 8072, 4, 58, '', '', '54', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 329, 1, 1, 2455, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(57, 8073, 4, 59, '', '', '273', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 330, 1, 1, 457, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(58, 8074, 4, 60, '', '', '231', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 331, 1, 1, 1414, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(59, 8075, 4, 61, '', '', '38', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 332, 1, 1, 2558, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(60, 8077, 4, 62, '', '', '51', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 333, 1, 1, 4313, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(61, 8078, 4, 63, '', '', '19', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 334, 1, 1, 2686, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(62, 8079, 4, 64, '', '', '54', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 335, 1, 1, 1513, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(63, 8080, 4, 65, '', '', '263', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 336, 1, 1, 1924, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(64, 8081, 4, 66, '', '', '134', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 337, 1, 1, 4047, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(65, 8082, 4, 67, '', '', '49', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 338, 1, 1, 2154, '2026-10-30 19:25:41', '2026-11-02 23:08:44', NULL),
(66, 8085, 4, 68, '', '', '217', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 339, 1, 1, 3258, '2026-10-30 19:25:41', '2026-11-02 23:08:45', NULL),
(67, 8086, 4, 69, '', '', '288', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 340, 1, 1, 2371, '2026-10-30 19:25:41', '2026-11-02 23:08:45', NULL),
(68, 8087, 4, 70, '', '', '76', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 341, 1, 1, 2524, '2026-10-30 19:25:42', '2026-11-02 23:08:45', NULL),
(69, 8088, 4, 71, '', '', '197', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 342, 1, 1, 841, '2026-10-30 19:25:42', '2026-11-02 23:08:45', NULL),
(70, 8089, 4, 72, '', '', '93', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 343, 1, 1, 4182, '2026-10-30 19:25:42', '2026-11-02 23:08:45', NULL),
(71, 8091, 4, 73, '', '', '164', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 344, 1, 1, 2304, '2026-10-30 19:25:42', '2026-11-02 23:08:45', NULL),
(72, 8092, 4, 74, '', '', '353', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 345, 1, 1, 2155, '2026-10-30 19:25:42', '2026-11-02 23:08:45', NULL),
(73, 8093, 4, 75, '', '', '', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 346, 1, 1, 309, '2026-10-30 19:25:42', '2026-11-02 23:08:45', NULL),
(74, 8094, 4, 76, '', '', '181', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 347, 1, 1, 1510, '2026-10-30 19:25:42', '2026-11-02 23:08:45', NULL),
(75, 8095, 4, 77, '', '', '143', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 348, 1, 1, 1163, '2026-10-30 19:25:42', '2026-11-02 23:08:45', NULL),
(76, 8096, 4, 78, '', '', '129', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 349, 1, 1, 165, '2026-10-30 19:25:42', '2026-11-02 23:13:12', NULL),
(77, 8097, 4, 79, '', '', '179', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 350, 1, 1, 2267, '2026-10-30 19:25:42', '2026-11-02 23:13:12', NULL),
(78, 8098, 4, 80, '', '', '36', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 351, 1, 1, 1012, '2026-10-30 19:25:42', '2026-11-02 23:13:12', NULL),
(79, 8099, 4, 81, '', '', '', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 352, 1, 1, 2541, '2026-10-30 19:25:42', '2026-11-02 23:13:12', NULL),
(80, 8101, 4, 82, '', '', '248', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 353, 1, 1, 804, '2026-10-30 19:25:42', '2026-11-02 23:13:12', NULL),
(81, 8102, 4, 83, '', '', '251', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 354, 1, 1, 1029, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(82, 8103, 4, 84, '', '', '18', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 355, 1, 1, 2718, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(83, 8105, 4, 85, '', '', '187', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 356, 1, 1, 2240, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(84, 8107, 4, 86, '', '', '298', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 357, 1, 1, 370, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(85, 8108, 4, 87, '', '', '326', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 358, 1, 1, 1333, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(86, 8109, 4, 88, '', '', '319', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 359, 1, 1, 1928, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(87, 8110, 4, 89, '', '', '236', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 360, 1, 1, 1038, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(88, 8112, 4, 90, '', '', '205', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 361, 1, 1, 513, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(89, 8115, 4, 91, '', '', '1', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 362, 1, 1, 1982, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(90, 8117, 4, 92, '', '', '36', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 363, 1, 1, 588, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(91, 8120, 4, 93, '', '', '54', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 364, 1, 1, 1304, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(92, 8121, 4, 94, '', '', '66', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 365, 1, 1, 4119, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(93, 8122, 4, 95, '', '', '25', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 366, 1, 1, 1703, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(94, 8123, 4, 96, '', '', '174', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 367, 1, 1, 1007, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(95, 8125, 4, 97, '', '', '26', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 368, 1, 1, 2525, '2026-10-30 19:25:43', '2026-11-02 23:13:12', NULL),
(96, 8126, 4, 98, '', '', '20', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 369, 1, 1, 550, '2026-10-30 19:25:44', '2026-11-02 23:13:12', NULL),
(97, 8127, 4, 99, '', '', '', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 370, 1, 1, 364, '2026-10-30 19:25:44', '2026-11-02 23:13:12', NULL),
(98, 8129, 4, 100, '', '', '268', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 371, 1, 1, 3044, '2026-10-30 19:25:44', '2026-11-02 23:13:12', NULL),
(99, 8132, 4, 101, '', '', '247', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 372, 1, 1, 286, '2026-10-30 19:25:44', '2026-11-02 23:13:12', NULL),
(100, 8133, 4, 102, '', '', '121', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 373, 1, 1, 2338, '2026-10-30 19:25:44', '2026-11-02 23:13:12', NULL),
(101, 8134, 4, 103, '', '', '90', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 374, 1, 1, 2190, '2026-10-30 19:25:44', '2026-11-02 23:17:16', NULL),
(102, 8139, 4, 104, '', '', '136', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 375, 1, 1, 2940, '2026-10-30 19:25:44', '2026-11-02 23:17:16', NULL),
(103, 8140, 4, 105, '', '', '', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 376, 1, 1, 413, '2026-10-30 19:25:44', '2026-11-02 23:17:16', NULL),
(104, 8141, 4, 106, '', '', '', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 377, 1, 1, 2182, '2026-10-30 19:25:44', '2026-11-02 23:17:16', NULL),
(105, 8142, 4, 107, '', '', '242', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 378, 1, 1, 206, '2026-10-30 19:25:44', '2026-11-02 23:17:16', NULL),
(106, 8143, 4, 108, '', '', '121', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 379, 1, 1, 416, '2026-10-30 19:25:44', '2026-11-02 23:17:16', NULL),
(107, 8144, 4, 109, '', '', '72', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 380, 1, 1, 120, '2026-10-30 19:25:44', '2026-11-02 23:17:16', NULL),
(108, 8145, 4, 110, '', '', '210', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 381, 1, 1, 3163, '2026-10-30 19:25:44', '2026-11-02 23:17:16', NULL),
(109, 8146, 4, 111, '', '', '37', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 382, 1, 1, 277, '2026-10-30 19:25:44', '2026-11-02 23:17:16', NULL),
(110, 8147, 4, 112, '', '', '92', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 383, 1, 1, 1386, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(111, 8148, 4, 113, '', '', '249', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 384, 1, 1, 3187, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(112, 8149, 4, 114, '', '', '229', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 385, 1, 1, 1217, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(113, 8150, 4, 115, '', '', '262', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 386, 1, 1, 39, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(114, 8151, 4, 116, '', '', '302', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 387, 1, 1, 1296, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(115, 8152, 4, 117, '', '', '54', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 388, 1, 1, 110, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(116, 8153, 4, 118, '', '', '219', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 389, 1, 1, 1310, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(117, 8154, 4, 119, '', '', '336', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 390, 1, 1, 5066, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(118, 8156, 4, 120, '', '', '221', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 391, 1, 1, 120, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(119, 8157, 4, 121, '', '', '206', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 392, 1, 1, 120, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(120, 8158, 4, 122, '', '', '59', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 393, 1, 1, 1962, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(121, 8159, 4, 123, '', '', '85', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 394, 1, 1, 1224, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(122, 8160, 4, 124, '', '', '322', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 395, 1, 1, 504, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(123, 8161, 4, 125, '', '', '10', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 396, 1, 1, 37, '2026-10-30 19:25:45', '2026-11-02 23:17:16', NULL),
(124, 8162, 4, 126, '', '', '27', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 397, 1, 1, 132, '2026-10-30 19:25:46', '2026-11-02 23:17:16', NULL),
(125, 8163, 4, 127, '', '', '194', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 398, 1, 1, 60, '2026-10-30 19:25:46', '2026-11-02 23:17:16', NULL),
(126, 8164, 4, 128, '', '', '293', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 399, 1, 1, 585, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(127, 8165, 4, 129, '', '', '184', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 400, 1, 1, 71, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(128, 8166, 4, 130, '', '', '46', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 401, 1, 1, 2040, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(129, 8167, 4, 131, '', '', '178', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 402, 1, 1, 643, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(130, 8169, 4, 132, '', '', '265', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 403, 1, 1, 2604, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(131, 8170, 4, 133, '', '', '225', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 404, 1, 1, 904, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(132, 8171, 4, 134, '', '', '337', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 405, 1, 1, 80, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(133, 8172, 4, 135, '', '', '92', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 406, 1, 1, 175, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(134, 8173, 4, 136, '', '', '177', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 407, 1, 1, 179, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(135, 8174, 4, 137, '', '', '51', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 408, 1, 1, 2213, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(136, 8175, 4, 138, '', '', '', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 409, 1, 1, 5201, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(137, 8176, 4, 139, '', '', '', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 410, 1, 1, 144, '2026-10-30 19:25:46', '2026-11-02 23:20:29', NULL),
(138, 8177, 4, 140, '', '', '11', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 411, 1, 1, 225, '2026-10-30 19:25:47', '2026-11-02 23:20:29', NULL),
(139, 8178, 4, 141, '', '', '55', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 412, 1, 1, 257, '2026-10-30 19:25:47', '2026-11-02 23:20:29', NULL),
(140, 8179, 4, 142, '', '', '392', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 413, 1, 1, 159, '2026-10-30 19:25:47', '2026-11-02 23:20:29', NULL),
(141, 8180, 4, 143, '', '', '21', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 414, 1, 1, 185, '2026-10-30 19:25:47', '2026-11-02 23:20:29', NULL),
(142, 8181, 4, 144, '', '', '143', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 415, 1, 1, 192, '2026-10-30 19:25:47', '2026-11-02 23:20:29', NULL),
(143, 8182, 4, 145, '', '', '55', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 416, 1, 1, 29, '2026-10-30 19:25:47', '2026-11-02 23:20:30', NULL),
(144, 8183, 4, 146, '', '', '96', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 417, 1, 1, 909, '2026-10-30 19:25:47', '2026-11-02 23:20:30', NULL),
(145, 8184, 4, 147, '', '', '21', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 418, 1, 1, 45, '2026-10-30 19:25:47', '2026-11-02 23:20:30', NULL),
(146, 8185, 4, 148, '', '', '240', 20, 36, '2026-10-31', 'active', NULL, 1, 0, '0', 419, 1, 1, 3217, '2026-10-30 19:25:47', '2026-11-02 23:20:30', NULL),
(147, 15001, 4, 149, '', '', '41', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 420, 1, 1, 0, '2026-10-30 22:00:02', '2026-11-02 23:20:30', NULL),
(148, 15002, 4, 150, '', '', '103', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 421, 1, 1, 0, '2026-10-30 22:00:02', '2026-11-02 23:20:30', NULL),
(149, 15003, 4, 151, '', '', '69', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 422, 1, 1, 0, '2026-10-30 22:00:02', '2026-11-02 23:20:30', NULL),
(150, 15004, 4, 152, '', '', '21', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 423, 1, 1, 0, '2026-10-30 22:00:02', '2026-11-02 23:20:30', NULL),
(151, 15005, 4, 153, '', '', '71', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 424, 1, 1, 0, '2026-10-30 22:00:02', '2026-11-02 23:26:14', NULL),
(152, 15006, 4, 154, '', '', '59', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 425, 1, 1, 0, '2026-10-30 22:00:02', '2026-11-02 23:26:14', NULL),
(153, 15008, 4, 155, '', '', '110', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 426, 1, 1, 0, '2026-10-30 22:00:02', '2026-11-02 23:26:14', NULL),
(154, 15009, 4, 156, '', '', '172', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 427, 1, 1, 0, '2026-10-30 22:00:02', '2026-11-02 23:26:14', NULL),
(155, 15010, 4, 157, '', '', '158', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 428, 1, 1, 0, '2026-10-30 22:00:02', '2026-11-02 23:26:14', NULL),
(156, 15011, 4, 158, '', '', '134', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 429, 1, 1, 0, '2026-10-30 22:00:03', '2026-11-02 23:26:14', NULL),
(157, 15012, 4, 159, '', '', '2', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 430, 1, 1, 0, '2026-10-30 22:00:03', '2026-11-02 23:26:14', NULL),
(158, 15014, 4, 160, '', '', '35', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 431, 1, 1, 0, '2026-10-30 22:00:03', '2026-11-02 23:26:14', NULL),
(159, 15016, 4, 161, '', '', '86', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 432, 1, 1, 0, '2026-10-30 22:00:03', '2026-11-02 23:26:14', NULL),
(160, 15017, 4, 162, '', '', '76', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 433, 1, 1, 0, '2026-10-30 22:00:03', '2026-11-02 23:26:14', NULL),
(161, 15019, 4, 163, '', '', '140', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 434, 1, 1, 0, '2026-10-30 22:00:03', '2026-11-02 23:26:14', NULL),
(162, 15020, 4, 164, '', '', '145', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 435, 1, 1, 0, '2026-10-30 22:00:03', '2026-11-02 23:26:14', NULL),
(163, 15021, 4, 165, '', '', '84', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 436, 1, 1, 0, '2026-10-30 22:00:03', '2026-11-02 23:26:14', NULL),
(164, 15024, 4, 166, '', '', '102', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 437, 1, 1, 0, '2026-10-30 22:00:03', '2026-11-02 23:26:14', NULL),
(165, 15025, 4, 167, '', '', '93', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 438, 1, 1, 0, '2026-10-30 22:00:04', '2026-11-02 23:26:14', NULL),
(166, 15026, 4, 168, '', '', '73', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 439, 1, 1, 0, '2026-10-30 22:00:04', '2026-11-02 23:26:14', NULL),
(167, 15027, 4, 169, '', '', '48', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 440, 1, 1, 0, '2026-10-30 22:00:04', '2026-11-02 23:26:14', NULL),
(168, 15028, 4, 170, '', '', '129', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 441, 1, 1, 0, '2026-10-30 22:00:04', '2026-11-02 23:26:14', NULL),
(169, 15029, 4, 171, '', '', '148', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 442, 1, 1, 0, '2026-10-30 22:00:04', '2026-11-02 23:26:14', NULL),
(170, 15030, 4, 172, '', '', '101', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 443, 1, 1, 0, '2026-10-30 22:00:04', '2026-11-02 23:26:14', NULL),
(171, 15031, 4, 173, '', '', '94', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 444, 1, 1, 0, '2026-10-30 22:00:04', '2026-11-02 23:26:14', NULL),
(172, 15032, 4, 174, '', '', '46', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 445, 1, 1, 0, '2026-10-30 22:00:04', '2026-11-02 23:26:14', NULL),
(173, 15033, 4, 175, '', '', '154', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 446, 1, 1, 0, '2026-10-30 22:00:04', '2026-11-02 23:26:14', NULL),
(174, 15034, 4, 176, '', '', '4', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 447, 1, 1, 0, '2026-10-30 22:00:05', '2026-11-02 23:26:14', NULL),
(175, 15036, 4, 177, '', '', '165', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 448, 1, 1, 0, '2026-10-30 22:00:05', '2026-11-02 23:26:14', NULL),
(176, 15037, 4, 178, '', '', '60', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 449, 1, 1, 0, '2026-10-30 22:00:05', '2026-11-02 23:26:14', NULL),
(177, 15038, 4, 179, '', '', '97', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 450, 1, 1, 0, '2026-10-30 22:00:05', '2026-11-02 23:26:14', NULL),
(178, 15039, 4, 180, '', '', '67', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 451, 1, 1, 0, '2026-10-30 22:00:05', '2026-11-02 23:26:14', NULL),
(179, 15040, 4, 181, '', '', '147', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 452, 1, 1, 0, '2026-10-30 22:00:05', '2026-11-02 23:26:14', NULL),
(180, 15041, 4, 182, '', '', '123', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 453, 1, 1, 0, '2026-10-30 22:00:05', '2026-11-02 23:26:14', NULL),
(181, 15042, 4, 183, '', '', '34', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 454, 1, 1, 0, '2026-10-30 22:00:05', '2026-11-02 23:26:14', NULL),
(182, 15043, 4, 184, '', '', '105', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 455, 1, 1, 0, '2026-10-30 22:00:05', '2026-11-02 23:26:14', NULL),
(183, 15044, 4, 185, '', '', '235', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 456, 1, 1, 0, '2026-10-30 22:00:05', '2026-11-02 23:26:14', NULL),
(184, 15045, 4, 186, '', '', '64', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 457, 1, 1, 0, '2026-10-30 22:00:06', '2026-11-02 23:26:14', NULL),
(185, 15046, 4, 187, '', '', '141', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 458, 1, 1, 0, '2026-10-30 22:00:06', '2026-11-02 23:26:14', NULL),
(186, 15047, 4, 188, '', '', '82', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 459, 1, 1, 0, '2026-10-30 22:00:06', '2026-11-02 23:26:14', NULL),
(187, 15048, 4, 189, '', '', '89', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 460, 1, 1, 0, '2026-10-30 22:00:06', '2026-11-02 23:26:14', NULL),
(188, 15049, 4, 190, '', '', '128', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 461, 1, 1, 0, '2026-10-30 22:00:06', '2026-11-02 23:26:14', NULL),
(189, 15050, 4, 191, '', '', '106', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 462, 1, 1, 0, '2026-10-30 22:00:06', '2026-11-02 23:26:14', NULL),
(190, 15051, 4, 192, '', '', '162', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 463, 1, 1, 0, '2026-10-30 22:00:06', '2026-11-02 23:26:14', NULL),
(191, 15053, 4, 193, '', '', '42', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 464, 1, 1, 0, '2026-10-30 22:00:06', '2026-11-02 23:26:15', NULL),
(192, 15054, 4, 194, '', '', '32', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 465, 1, 1, 0, '2026-10-30 22:00:06', '2026-11-02 23:26:15', NULL),
(193, 15055, 4, 195, '', '', '53', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 466, 1, 1, 0, '2026-10-30 22:00:06', '2026-11-02 23:26:15', NULL),
(194, 15056, 4, 196, '', '', '121', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 467, 1, 1, 0, '2026-10-30 22:00:07', '2026-11-02 23:26:15', NULL),
(195, 15057, 4, 197, '', '', '3', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 468, 1, 1, 0, '2026-10-30 22:00:07', '2026-11-02 23:26:15', NULL),
(196, 15058, 4, 198, '', '', '14', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 469, 1, 1, 0, '2026-10-30 22:00:07', '2026-11-02 23:26:15', NULL),
(197, 15059, 4, 199, '', '', '79', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 470, 1, 1, 0, '2026-10-30 22:00:07', '2026-11-02 23:26:15', NULL),
(198, 15060, 4, 200, '', '', '109', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 471, 1, 1, 0, '2026-10-30 22:00:07', '2026-11-02 23:26:15', NULL),
(199, 15061, 4, 201, '', '', '88', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 472, 1, 1, 0, '2026-10-30 22:00:07', '2026-11-02 23:26:15', NULL),
(200, 15062, 4, 202, '', '', '87', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 473, 1, 1, 0, '2026-10-30 22:00:07', '2026-11-02 23:26:15', NULL),
(201, 15064, 4, 203, '', '', '209', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 474, 1, 1, 0, '2026-10-30 22:00:07', '2026-11-02 23:29:39', NULL),
(202, 15065, 4, 204, '', '', '65', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 475, 1, 1, 0, '2026-10-30 22:00:07', '2026-11-02 23:29:39', NULL),
(203, 15066, 4, 205, '', '', '57', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 476, 1, 1, 0, '2026-10-30 22:00:08', '2026-11-02 23:29:39', NULL),
(204, 15067, 4, 206, '', '', '127', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 477, 1, 1, 0, '2026-10-30 22:00:08', '2026-11-02 23:29:39', NULL),
(205, 15068, 4, 207, '', '', '51', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 478, 1, 1, 0, '2026-10-30 22:00:08', '2026-11-02 23:29:39', NULL),
(206, 15069, 4, 208, '', '', '68', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 479, 1, 1, 0, '2026-10-30 22:00:08', '2026-11-02 23:29:39', NULL),
(207, 15070, 4, 209, '', '', '33', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 480, 1, 1, 0, '2026-10-30 22:00:08', '2026-11-02 23:29:39', NULL),
(208, 15071, 4, 210, '', '', '112', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 481, 1, 1, 0, '2026-10-30 22:00:08', '2026-11-02 23:29:39', NULL),
(209, 15072, 4, 211, '', '', '45', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 482, 1, 1, 0, '2026-10-30 22:00:08', '2026-11-02 23:29:39', NULL),
(210, 15073, 4, 212, '', '', '115', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 483, 1, 1, 0, '2026-10-30 22:00:08', '2026-11-02 23:29:39', NULL),
(211, 15074, 4, 213, '', '', '25', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 484, 1, 1, 0, '2026-10-30 22:00:08', '2026-11-02 23:29:39', NULL),
(212, 15075, 4, 214, '', '', '37', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 485, 1, 1, 0, '2026-10-30 22:00:08', '2026-11-02 23:29:39', NULL),
(213, 15076, 4, 215, '', '', '122', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 486, 1, 1, 0, '2026-10-30 22:00:09', '2026-11-02 23:29:39', NULL),
(214, 15077, 4, 216, '', '', '7', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 487, 1, 1, 0, '2026-10-30 22:00:09', '2026-11-02 23:29:39', NULL),
(215, 15078, 4, 217, '', '', '72', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 488, 1, 1, 0, '2026-10-30 22:00:09', '2026-11-02 23:29:39', NULL),
(216, 15079, 4, 218, '', '', '18', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 489, 1, 1, 0, '2026-10-30 22:00:09', '2026-11-02 23:29:39', NULL),
(217, 15080, 4, 219, '', '', '121', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 490, 1, 1, 0, '2026-10-30 22:00:09', '2026-11-02 23:29:39', NULL),
(218, 15081, 4, 220, '', '', '136', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 491, 1, 1, 0, '2026-10-30 22:00:09', '2026-11-02 23:29:39', NULL),
(219, 15082, 4, 221, '', '', '96', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 492, 1, 1, 0, '2026-10-30 22:00:09', '2026-11-02 23:29:39', NULL),
(220, 15083, 4, 222, '', '', '22', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 493, 1, 1, 0, '2026-10-30 22:00:09', '2026-11-02 23:29:39', NULL),
(221, 15084, 4, 223, '', '', '22', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 494, 1, 1, 0, '2026-10-30 22:00:09', '2026-11-02 23:29:39', NULL),
(222, 15085, 4, 224, '', '', '75', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 495, 1, 1, 0, '2026-10-30 22:00:10', '2026-11-02 23:29:39', NULL),
(223, 15086, 4, 225, '', '', '36', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 496, 1, 1, 0, '2026-10-30 22:00:10', '2026-11-02 23:29:39', NULL),
(224, 15087, 4, 226, '', '', '10', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 497, 1, 1, 0, '2026-10-30 22:00:10', '2026-11-02 23:29:39', NULL),
(225, 15089, 4, 227, '', '', '69', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 498, 1, 1, 0, '2026-10-30 22:00:10', '2026-11-02 23:29:39', NULL),
(226, 15090, 4, 228, '', '', '5', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 499, 1, 1, 0, '2026-10-30 22:00:10', '2026-11-02 23:29:39', NULL),
(227, 15091, 4, 229, '', '', '70', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 500, 1, 1, 0, '2026-10-30 22:00:10', '2026-11-02 23:29:39', NULL),
(228, 15093, 4, 230, '', '', '16', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 501, 1, 1, 0, '2026-10-30 22:00:10', '2026-11-02 23:29:39', NULL),
(229, 15094, 4, 231, '', '', '135', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 502, 1, 1, 0, '2026-10-30 22:00:10', '2026-11-02 23:29:39', NULL),
(230, 15095, 4, 232, '', '', '251', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 503, 1, 1, 0, '2026-10-30 22:00:10', '2026-11-02 23:29:39', NULL),
(231, 15096, 4, 233, '', '', '250', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 504, 1, 1, 0, '2026-10-30 22:00:10', '2026-11-02 23:29:39', NULL),
(232, 15097, 4, 234, '', '', '104', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 505, 1, 1, 0, '2026-10-30 22:00:11', '2026-11-02 23:29:39', NULL),
(233, 15098, 4, 235, '', '', '83', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 506, 1, 1, 0, '2026-10-30 22:00:11', '2026-11-02 23:29:39', NULL),
(234, 15099, 4, 236, '', '', '183', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 507, 1, 1, 0, '2026-10-30 22:00:11', '2026-11-02 23:29:39', NULL),
(235, 15100, 4, 237, '', '', '229', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 508, 1, 1, 0, '2026-10-30 22:00:11', '2026-11-02 23:29:39', NULL),
(236, 15101, 4, 238, '', '', '10', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 509, 1, 1, 0, '2026-10-30 22:00:11', '2026-11-02 23:29:39', NULL),
(237, 15102, 4, 239, '', '', '104', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 510, 1, 1, 0, '2026-10-30 22:00:11', '2026-11-02 23:29:39', NULL),
(238, 15103, 4, 240, '', '', '63', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 511, 1, 1, 0, '2026-10-30 22:00:11', '2026-11-02 23:29:39', NULL),
(239, 15104, 4, 241, '', '', '5', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 512, 1, 1, 0, '2026-10-30 22:00:11', '2026-11-02 23:29:39', NULL),
(240, 15105, 4, 242, '', '', '46', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 513, 1, 1, 0, '2026-10-30 22:00:11', '2026-11-02 23:29:39', NULL),
(241, 15106, 4, 243, '', '', '205', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 514, 1, 1, 0, '2026-10-30 22:00:12', '2026-11-02 23:29:39', NULL),
(242, 15107, 4, 244, '', '', '6', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 515, 1, 1, 0, '2026-10-30 22:00:12', '2026-11-02 23:29:40', NULL),
(243, 15108, 4, 245, '', '', '47', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 516, 1, 1, 0, '2026-10-30 22:00:12', '2026-11-02 23:29:40', NULL),
(244, 15109, 4, 246, '', '', '91', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 517, 1, 1, 0, '2026-10-30 22:00:12', '2026-11-02 23:29:40', NULL),
(245, 15110, 4, 247, '', '', '131', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 518, 1, 1, 0, '2026-10-30 22:00:12', '2026-11-02 23:29:40', NULL),
(246, 15112, 4, 248, '', '', '166', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 519, 1, 1, 0, '2026-10-30 22:00:12', '2026-11-02 23:29:40', NULL),
(247, 15113, 4, 249, '', '', '209', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 520, 1, 1, 0, '2026-10-30 22:00:12', '2026-11-02 23:29:40', NULL),
(248, 15114, 4, 250, '', '', '50', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 521, 1, 1, 0, '2026-10-30 22:00:12', '2026-11-02 23:29:40', NULL),
(249, 15116, 4, 251, '', '', '131', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 522, 1, 1, 0, '2026-10-30 22:00:12', '2026-11-02 23:29:40', NULL),
(250, 15117, 4, 252, '', '', '52', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 523, 1, 1, 0, '2026-10-30 22:00:12', '2026-11-02 23:29:40', NULL),
(251, 15118, 4, 253, '', '', '19', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 572, 1, 1, 0, '2026-10-30 22:00:13', '2026-11-03 00:06:52', NULL),
(252, 15119, 4, 254, '', '', '121', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 573, 1, 1, 0, '2026-10-30 22:00:13', '2026-11-03 00:06:52', NULL),
(253, 15121, 4, 255, '', '', '116', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 574, 1, 1, 0, '2026-10-30 22:00:13', '2026-11-03 00:06:52', NULL),
(254, 15122, 4, 256, '', '', '126', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 575, 1, 1, 0, '2026-10-30 22:00:13', '2026-11-03 00:06:52', NULL),
(255, 15123, 4, 257, '', '', '146', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 576, 1, 1, 0, '2026-10-30 22:00:13', '2026-11-03 00:06:52', NULL),
(256, 15124, 4, 258, '', '', '81', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 577, 1, 1, 0, '2026-10-30 22:00:13', '2026-11-03 00:06:52', NULL),
(257, 15125, 4, 259, '', '', '12', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 578, 1, 1, 0, '2026-10-30 22:00:13', '2026-11-03 00:06:52', NULL),
(258, 15126, 4, 260, '', '', '113', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 579, 1, 1, 0, '2026-10-30 22:00:13', '2026-11-03 00:06:52', NULL),
(259, 15127, 4, 261, '', '', '111', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 580, 1, 1, 0, '2026-10-30 22:00:13', '2026-11-03 00:06:52', NULL),
(260, 15128, 4, 262, '', '', '9', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 581, 1, 1, 0, '2026-10-30 22:00:14', '2026-11-03 00:06:52', NULL),
(261, 15130, 4, 263, '', '', '153', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 582, 1, 1, 0, '2026-10-30 22:00:14', '2026-11-03 00:06:52', NULL),
(262, 15131, 4, 264, '', '', '90', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 535, 1, 1, 0, '2026-10-30 22:00:14', '2026-11-02 23:31:49', NULL),
(263, 15132, 4, 265, '', '', '160', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 536, 1, 1, 0, '2026-10-30 22:00:14', '2026-11-02 23:31:49', NULL),
(264, 15133, 4, 266, '', '', '107', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 537, 1, 1, 0, '2026-10-30 22:00:14', '2026-11-02 23:31:49', NULL),
(265, 15134, 4, 267, '', '', '28', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 538, 1, 1, 0, '2026-10-30 22:00:14', '2026-11-02 23:31:49', NULL),
(266, 15135, 4, 268, '', '', '234', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 539, 1, 1, 0, '2026-10-30 22:00:14', '2026-11-02 23:31:49', NULL),
(267, 15136, 4, 269, '', '', '8', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 540, 1, 1, 0, '2026-10-30 22:00:14', '2026-11-02 23:31:49', NULL),
(268, 15137, 4, 270, '', '', '56', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 541, 1, 1, 0, '2026-10-30 22:00:14', '2026-11-02 23:31:49', NULL),
(269, 15138, 4, 271, '', '', '80', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 542, 1, 1, 0, '2026-10-30 22:00:14', '2026-11-02 23:31:49', NULL),
(270, 15140, 4, 272, '', '', '195', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 543, 1, 1, 0, '2026-10-30 22:00:15', '2026-11-02 23:31:49', NULL),
(271, 15141, 4, 273, '', '', '13', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 544, 1, 1, 0, '2026-10-30 22:00:15', '2026-11-02 23:31:49', NULL),
(272, 15142, 4, 274, '', '', '197', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 545, 1, 1, 0, '2026-10-30 22:00:15', '2026-11-02 23:31:49', NULL),
(273, 15143, 4, 275, '', '', '130', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 546, 1, 1, 0, '2026-10-30 22:00:15', '2026-11-02 23:31:49', NULL),
(274, 15144, 4, 276, '', '', '59', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 547, 1, 1, 0, '2026-10-30 22:00:15', '2026-11-02 23:31:49', NULL),
(275, 15145, 4, 277, '', '', '132', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 548, 1, 1, 0, '2026-10-30 22:00:15', '2026-11-02 23:31:49', NULL),
(276, 15146, 4, 278, '', '', '-', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 549, 1, 1, 0, '2026-10-30 22:00:15', '2026-11-02 23:31:49', NULL),
(277, 15147, 4, 279, '', '', '132', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 550, 1, 1, 0, '2026-10-30 22:00:15', '2026-11-02 23:31:49', NULL),
(278, 15152, 4, 280, '', '', '40', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 551, 1, 1, 0, '2026-10-30 22:00:15', '2026-11-02 23:31:49', NULL),
(279, 15153, 4, 281, '', '', '171', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 552, 1, 1, 0, '2026-10-30 22:00:16', '2026-11-02 23:31:49', NULL),
(280, 15154, 4, 282, '', '', '85', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 553, 1, 1, 0, '2026-10-30 22:00:16', '2026-11-02 23:31:49', NULL),
(281, 15156, 4, 283, '', '', '9', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 554, 1, 1, 0, '2026-10-30 22:00:16', '2026-11-02 23:31:49', NULL),
(282, 15158, 4, 284, '', '', '37', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 555, 1, 1, 0, '2026-10-30 22:00:16', '2026-11-02 23:31:49', NULL),
(283, 15159, 4, 285, '', '', '206', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 556, 1, 1, 0, '2026-10-30 22:00:16', '2026-11-02 23:31:49', NULL),
(284, 15160, 4, 286, '', '', '144', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 557, 1, 1, 0, '2026-10-30 22:00:16', '2026-11-02 23:31:49', NULL),
(285, 15161, 4, 287, '', '', '77', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 558, 1, 1, 0, '2026-10-30 22:00:16', '2026-11-02 23:31:49', NULL),
(286, 15162, 4, 288, '', '', '219', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 559, 1, 1, 0, '2026-10-30 22:00:16', '2026-11-02 23:31:49', NULL),
(287, 15164, 4, 289, '', '', '118', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 560, 1, 1, 0, '2026-10-30 22:00:16', '2026-11-02 23:31:49', NULL),
(288, 15165, 4, 290, '', '', '97', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 561, 1, 1, 0, '2026-10-30 22:00:16', '2026-11-02 23:31:49', NULL),
(289, 15166, 4, 291, '', '', '199', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 562, 1, 1, 0, '2026-10-30 22:00:17', '2026-11-02 23:31:49', NULL),
(290, 15167, 4, 292, '', '', '214', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 563, 1, 1, 0, '2026-10-30 22:00:17', '2026-11-02 23:31:49', NULL),
(291, 15168, 4, 293, '', '', '27', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 564, 1, 1, 0, '2026-10-30 22:00:17', '2026-11-02 23:31:49', NULL),
(292, 15169, 4, 294, '', '', '54', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 565, 1, 1, 0, '2026-10-30 22:00:17', '2026-11-02 23:31:49', NULL),
(293, 15170, 4, 295, '', '', '198', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 566, 1, 1, 0, '2026-10-30 22:00:17', '2026-11-02 23:31:50', NULL),
(294, 15171, 4, 296, '', '', '150', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 567, 1, 1, 0, '2026-10-30 22:00:17', '2026-11-02 23:31:50', NULL),
(295, 15172, 4, 297, '', '', '108', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 568, 1, 1, 0, '2026-10-30 22:00:17', '2026-11-02 23:31:50', NULL),
(296, 15173, 4, 298, '', '', '210', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 569, 1, 1, 0, '2026-10-30 22:00:17', '2026-11-02 23:31:50', NULL),
(297, 15174, 4, 299, '', '', '-', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 570, 1, 1, 0, '2026-10-30 22:00:17', '2026-11-02 23:31:50', NULL),
(298, 15175, 4, 300, '', '', '260', 23, 39, '2026-10-31', 'active', NULL, 1, 0, '0', 571, 1, 1, 0, '2026-10-30 22:00:18', '2026-11-02 23:31:50', NULL),
(299, 7001, 4, 301, '', '', '121', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 586, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(300, 7002, 4, 302, '', '', '162', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 587, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(301, 7003, 4, 303, '', '', '141', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 588, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(302, 7004, 4, 304, '', '', '58', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 589, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(303, 7005, 4, 305, '', '', '14-ม.ค.', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 590, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(304, 7006, 4, 306, '', '', '27', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 591, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(305, 7007, 4, 307, '', '', '193', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 592, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(306, 7008, 4, 308, '', '', '202', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 593, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(307, 7009, 4, 309, '', '', '91', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 594, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(308, 7011, 4, 310, '', '', '79', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 595, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(309, 7012, 4, 311, '', '', '55', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 596, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(310, 7013, 4, 312, '', '', '240', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 597, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(311, 7014, 4, 313, '', '', '211', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 598, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(312, 7015, 4, 314, '', '', '225', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 599, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(313, 7016, 4, 315, '', '', '83', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 600, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(314, 7017, 4, 316, '', '', '24', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 601, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(315, 7018, 4, 317, '', '', '112', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 602, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(316, 7019, 4, 318, '', '', '191', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 603, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(317, 7020, 4, 319, '', '', '64', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 604, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(318, 7022, 4, 320, '', '', '187', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 605, 1, 1, 0, '2026-10-30 23:01:32', '2026-11-04 05:42:51', NULL),
(319, 7023, 4, 321, '', '', '26', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 606, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(320, 7024, 4, 322, '', '', '54', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 607, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(321, 7025, 4, 323, '', '', '89', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 608, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(322, 7026, 4, 324, '', '', '64', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 609, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(323, 7027, 4, 325, '', '', '123', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 610, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(324, 7028, 4, 326, '', '', '213', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 611, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(325, 7029, 4, 327, '', '', '128', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 612, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(326, 7030, 4, 328, '', '', '47', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 613, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(327, 7031, 4, 329, '', '', '80', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 614, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(328, 7032, 4, 330, '', '', '120', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 615, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(329, 7033, 4, 331, '', '', '71', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 616, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(330, 7034, 4, 332, '', '', '71', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 617, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(331, 7035, 4, 333, '', '', '7', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 618, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(332, 7036, 4, 334, '', '', '122', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 619, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL);
INSERT INTO `tw_meter_infos` (`id`, `meter_id`, `org_id_fk`, `user_id`, `factory_no`, `submeter_name`, `meter_address`, `undertake_zone_id`, `undertake_subzone_id`, `acceptance_date`, `status`, `comment`, `metertype_id`, `owe_count`, `cutmeter`, `inv_no_index`, `payment_id`, `recorder_id`, `last_meter_recording`, `created_at`, `updated_at`, `old_meter_id`) VALUES
(333, 7037, 4, 335, '', '', '44', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 620, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(334, 7038, 4, 336, '', '', '72/1', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 621, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(335, 7039, 4, 337, '', '', '130', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 622, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(336, 7040, 4, 338, '', '', '172', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 623, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(337, 7041, 4, 339, '', '', '33', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 624, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(338, 7042, 4, 340, '', '', '38', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 625, 1, 1, 0, '2026-10-30 23:01:33', '2026-11-04 05:42:51', NULL),
(339, 7043, 4, 341, '', '', '35', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 626, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(340, 7045, 4, 342, '', '', '53', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 627, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(341, 7046, 4, 343, '', '', '159', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 628, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(342, 7047, 4, 344, '', '', '39', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 629, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(343, 7048, 4, 345, '', '', '67', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 630, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(344, 7049, 4, 346, '', '', '126', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 631, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(345, 7050, 4, 347, '', '', '126', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 632, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(346, 7051, 4, 348, '', '', '139', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 633, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(347, 7052, 4, 349, '', '', '133', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 634, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(348, 7053, 4, 350, '', '', '147', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 635, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(349, 7054, 4, 351, '', '', '7', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 636, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(350, 7055, 4, 352, '', '', '4', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 637, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(351, 7056, 4, 353, '', '', '203', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 638, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(352, 7057, 4, 354, '', '', '62', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 639, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(353, 7058, 4, 355, '', '', '116', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 640, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(354, 7059, 4, 356, '', '', '111', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 641, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(355, 7060, 4, 357, '', '', '132', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 642, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(356, 7061, 4, 358, '', '', '149', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 643, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(357, 7064, 4, 359, '', '', '22', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 644, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(358, 7065, 4, 360, '', '', '95', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 645, 1, 1, 0, '2026-10-30 23:01:34', '2026-11-04 05:42:51', NULL),
(359, 7066, 4, 361, '', '', '150', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 646, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:51', NULL),
(360, 7067, 4, 362, '', '', '56', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 647, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:51', NULL),
(361, 7068, 4, 363, '', '', '158', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 648, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(362, 7069, 4, 364, '', '', '9', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 649, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(363, 7070, 4, 365, '', '', '73', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 650, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(364, 7071, 4, 366, '', '', '48', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 651, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(365, 7072, 4, 367, '', '', '134', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 652, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(366, 7073, 4, 368, '', '', '106', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 653, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(367, 7074, 4, 369, '', '', '106', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 654, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(368, 7075, 4, 370, '', '', '74', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 655, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(369, 7076, 4, 371, '', '', '113', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 656, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(370, 7077, 4, 372, '', '', '82', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 657, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(371, 7078, 4, 373, '', '', '66', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 658, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(372, 7079, 4, 374, '', '', '19', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 659, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(373, 7080, 4, 375, '', '', '30', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 660, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(374, 7081, 4, 376, '', '', '18', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 661, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(375, 7082, 4, 377, '', '', '8', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 662, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(376, 7083, 4, 378, '', '', '10', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 663, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(377, 7085, 4, 379, '', '', '105', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 664, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(378, 7087, 4, 380, '', '', '76', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 665, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(379, 7088, 4, 381, '', '', '15', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 666, 1, 1, 0, '2026-10-30 23:01:35', '2026-11-04 05:42:52', NULL),
(380, 7089, 4, 382, '', '', '102', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 667, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(381, 7090, 4, 383, '', '', '103', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 668, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(382, 7092, 4, 384, '', '', '98', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 669, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(383, 7093, 4, 385, '', '', '98', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 670, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(384, 7094, 4, 386, '', '', '143', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 671, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(385, 7095, 4, 387, '', '', '154', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 672, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(386, 7096, 4, 388, '', '', '51', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 673, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(387, 7097, 4, 389, '', '', '16', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 674, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(388, 7098, 4, 390, '', '', '208', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 675, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(389, 7101, 4, 391, '', '', '125', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 676, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(390, 7102, 4, 392, '', '', '207', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 677, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(391, 7103, 4, 393, '', '', '70', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 678, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(392, 7104, 4, 394, '', '', '196', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 679, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(393, 7105, 4, 395, '', '', '60', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 680, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(394, 7107, 4, 396, '', '', '230', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 681, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(395, 7108, 4, 397, '', '', '138', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 682, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(396, 7109, 4, 398, '', '', '50', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 683, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(397, 7110, 4, 399, '', '', '100', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 684, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(398, 7111, 4, 400, '', '', '77', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 685, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(399, 7112, 4, 401, '', '', '32', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 686, 1, 1, 0, '2026-10-30 23:01:36', '2026-11-04 05:42:52', NULL),
(400, 7113, 4, 402, '', '', '78', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 687, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(401, 7114, 4, 403, '', '', '45', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 688, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(402, 7115, 4, 404, '', '', '75', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 689, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(403, 7116, 4, 405, '', '', '146', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 690, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(404, 7117, 4, 406, '', '', '68', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 691, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(405, 7118, 4, 407, '', '', '52', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 692, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(406, 7119, 4, 408, '', '', '192', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 693, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(407, 7120, 4, 409, '', '', '69', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 694, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(408, 7121, 4, 410, '', '', '3', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 695, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(409, 7122, 4, 411, '', '', '34', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 696, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(410, 7123, 4, 412, '', '', '119', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 697, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(411, 7124, 4, 413, '', '', '94', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 698, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(412, 7125, 4, 414, '', '', '2', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 699, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(413, 7126, 4, 415, '', '', '118', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 700, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(414, 7127, 4, 416, '', '', '694', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 701, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(415, 7128, 4, 417, '', '', '1', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 702, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(416, 7130, 4, 418, '', '', '117', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 703, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(417, 7132, 4, 419, '', '', '6', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 704, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(418, 7133, 4, 420, '', '', '46', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 705, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(419, 7134, 4, 421, '', '', '49', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 706, 1, 1, 0, '2026-10-30 23:01:37', '2026-11-04 05:42:52', NULL),
(420, 7136, 4, 422, '', '', '13', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 707, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:52', NULL),
(421, 7137, 4, 423, '', '', '153', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 708, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(422, 7138, 4, 424, '', '', '59', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 709, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(423, 7139, 4, 425, '', '', '36', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 710, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(424, 7141, 4, 426, '', '', '160', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 711, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(425, 7142, 4, 427, '', '', '7', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 712, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(426, 7143, 4, 428, '', '', '225', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 713, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(427, 7144, 4, 429, '', '', '30', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 714, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(428, 7145, 4, 430, '', '', '23', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 715, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(429, 7146, 4, 431, '', '', '38', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 716, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(430, 7147, 4, 432, '', '', '43', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 717, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(431, 7148, 4, 433, '', '', '30-ม.ค.', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 718, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(432, 7149, 4, 434, '', '', '74', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 719, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(433, 7150, 4, 435, '', '', '20', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 720, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(434, 7151, 4, 436, '', '', '118', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 721, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(435, 7152, 4, 437, '', '', '30/2', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 722, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(436, 7153, 4, 438, '', '', '239', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 723, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(437, 7154, 4, 439, '', '', '19', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 724, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(438, 7155, 4, 440, '', '', '79', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 725, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(439, 7156, 4, 441, '', '', '190', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 726, 1, 1, 0, '2026-10-30 23:01:38', '2026-11-04 05:42:53', NULL),
(440, 7158, 4, 442, '', '', '210', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 727, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(441, 7159, 4, 443, '', '', '107', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 728, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(442, 7160, 4, 444, '', '', '129', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 729, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(443, 7161, 4, 445, '', '', '111', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 730, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(444, 7162, 4, 446, '', '', '4', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 731, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(445, 7164, 4, 447, '', '', '57', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 732, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(446, 7165, 4, 448, '', '', '28', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 733, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(447, 7166, 4, 449, '', '', '224', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 734, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(448, 7168, 4, 450, '', '', '23', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 735, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(449, 7169, 4, 451, '', '', '86', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 736, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(450, 7170, 4, 452, '', '', '154', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 737, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(451, 7171, 4, 453, '', '', '86', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 738, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(452, 7172, 4, 454, '', '', '165', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 739, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(453, 7173, 4, 455, '', '', '137', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 740, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(454, 7174, 4, 456, '', '', '11', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 741, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(455, 7175, 4, 457, '', '', '129', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 742, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(456, 7176, 4, 458, '', '', '87', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 743, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(457, 7179, 4, 459, '', '', '76', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 744, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(458, 7180, 4, 460, '', '', '96', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 745, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(459, 7181, 4, 461, '', '', '261', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 746, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(460, 7182, 4, 462, '', '', '221', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 747, 1, 1, 0, '2026-10-30 23:01:39', '2026-11-04 05:42:53', NULL),
(461, 7183, 4, 463, '', '', '46', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 748, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(462, 7184, 4, 464, '', '', '70', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 749, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(463, 7185, 4, 465, '', '', '53', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 750, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(464, 7186, 4, 466, '', '', '39', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 751, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(465, 7187, 4, 467, '', '', '11', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 752, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(466, 7188, 4, 468, '', '', '52', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 753, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(467, 7189, 4, 469, '', '', '76', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 754, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(468, 7190, 4, 470, '', '', '55', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 755, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(469, 7191, 4, 471, '', '', '27', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 756, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(470, 7192, 4, 472, '', '', '108', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 757, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(471, 7193, 4, 473, '', '', '104', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 758, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(472, 7195, 4, 474, '', '', '198', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 759, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(473, 7196, 4, 475, '', '', '21', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 760, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(474, 7197, 4, 476, '', '', '123', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 761, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(475, 7198, 4, 477, '', '', '6', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 762, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(476, 7199, 4, 478, '', '', '133', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 763, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(477, 7200, 4, 479, '', '', '159', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 764, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(478, 7201, 4, 480, '', '', '308', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 765, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(479, 7203, 4, 481, '', '', '49', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 766, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(480, 7204, 4, 482, '', '', '40', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 767, 1, 1, 0, '2026-10-30 23:01:40', '2026-11-04 05:42:53', NULL),
(481, 7205, 4, 483, '', '', '307', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 768, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:53', NULL),
(482, 7206, 4, 484, '', '', '', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 769, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:53', NULL),
(483, 7207, 4, 485, '', '', '29', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 770, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:53', NULL),
(484, 7208, 4, 486, '', '', '156', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 771, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:53', NULL),
(485, 7209, 4, 487, '', '', '41', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 772, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:53', NULL),
(486, 7210, 4, 488, '', '', '28', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 773, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:53', NULL),
(487, 7211, 4, 489, '', '', '199', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 774, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:53', NULL),
(488, 7212, 4, 490, '', '', '31', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 775, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:53', NULL),
(489, 7213, 4, 491, '', '', '136', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 776, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:53', NULL),
(490, 7214, 4, 492, '', '', '132', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 777, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:54', NULL),
(491, 7215, 4, 493, '', '', '164', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 778, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:54', NULL),
(492, 7216, 4, 494, '', '', '21', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 779, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:54', NULL),
(493, 7217, 4, 495, '', '', '17', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 780, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:54', NULL),
(494, 7218, 4, 496, '', '', '1', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 781, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:54', NULL),
(495, 7219, 4, 497, '', '', '35', 21, 37, '2026-10-31', 'active', NULL, 1, 0, '0', 782, 1, 1, 0, '2026-10-30 23:01:41', '2026-11-04 05:42:54', NULL),
(496, 16001, 4, 498, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 783, 1, 1, 3048, '2026-10-31 00:11:47', '2026-11-04 18:43:54', NULL),
(497, 16002, 4, 499, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 784, 1, 1, 2330, '2026-10-31 00:11:47', '2026-11-04 18:43:54', NULL),
(498, 16003, 4, 500, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 785, 1, 1, 742, '2026-10-31 00:11:47', '2026-11-04 18:43:54', NULL),
(499, 16004, 4, 501, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 786, 1, 1, 844, '2026-10-31 00:11:47', '2026-11-04 18:43:54', NULL),
(500, 16005, 4, 502, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 787, 1, 1, 1622, '2026-10-31 00:11:47', '2026-11-04 18:43:54', NULL),
(501, 16006, 4, 503, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 788, 1, 1, 5254, '2026-10-31 00:11:47', '2026-11-04 18:43:54', NULL),
(502, 16007, 4, 504, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 789, 1, 1, 806, '2026-10-31 00:11:47', '2026-11-04 18:43:54', NULL),
(503, 16008, 4, 505, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 790, 1, 1, 2291, '2026-10-31 00:11:47', '2026-11-04 18:43:54', NULL),
(504, 16009, 4, 506, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 791, 1, 1, 1710, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(505, 16010, 4, 507, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 792, 1, 1, 557, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(506, 16011, 4, 508, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 793, 1, 1, 1263, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(507, 16012, 4, 509, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 794, 1, 1, 1621, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(508, 16013, 4, 510, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 795, 1, 1, 4083, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(509, 16014, 4, 511, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 796, 1, 1, 771, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(510, 16015, 4, 512, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 797, 1, 1, 1117, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(511, 16016, 4, 513, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 798, 1, 1, 14436, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(512, 16017, 4, 514, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 799, 1, 1, 2868, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(513, 16018, 4, 515, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 800, 1, 1, 2142, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(514, 16019, 4, 516, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 801, 1, 1, 902, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(515, 16020, 4, 517, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 802, 1, 1, 2523, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(516, 16021, 4, 518, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 803, 1, 1, 2068, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(517, 16022, 4, 519, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 804, 1, 1, 1923, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(518, 16023, 4, 520, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 805, 1, 1, 589, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(519, 16024, 4, 521, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 806, 1, 1, 698, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(520, 16026, 4, 522, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 807, 1, 1, 1617, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(521, 16027, 4, 523, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 808, 1, 1, 959, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(522, 16028, 4, 524, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 809, 1, 1, 3683, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(523, 16029, 4, 525, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 810, 1, 1, 1604, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(524, 16030, 4, 526, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 811, 1, 1, 2387, '2026-10-31 00:11:48', '2026-11-04 18:43:54', NULL),
(525, 16031, 4, 527, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 812, 1, 1, 2015, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(526, 16032, 4, 528, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 813, 1, 1, 3205, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(527, 16033, 4, 529, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 814, 1, 1, 1496, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(528, 16034, 4, 530, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 815, 1, 1, 177, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(529, 16035, 4, 531, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 816, 1, 1, 785, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(530, 16036, 4, 532, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 817, 1, 1, 2024, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(531, 16038, 4, 533, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 818, 1, 1, 926, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(532, 16039, 4, 534, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 819, 1, 1, 3531, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(533, 16040, 4, 535, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 820, 1, 1, 2598, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(534, 16041, 4, 536, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 821, 1, 1, 1741, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(535, 16042, 4, 537, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 822, 1, 1, 1153, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(536, 16043, 4, 538, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 823, 1, 1, 920, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(537, 16044, 4, 539, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 824, 1, 1, 1849, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(538, 16045, 4, 540, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 825, 1, 1, 44, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(539, 16046, 4, 541, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 826, 1, 1, 843, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(540, 16047, 4, 542, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 827, 1, 1, 5532, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(541, 16048, 4, 543, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 828, 1, 1, 1303, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(542, 16049, 4, 544, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 829, 1, 1, 310, '2026-10-31 00:11:49', '2026-11-04 18:43:54', NULL),
(543, 16050, 4, 545, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 830, 1, 1, 1230, '2026-10-31 00:11:49', '2026-11-04 18:43:55', NULL),
(544, 16051, 4, 546, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 831, 1, 1, 1946, '2026-10-31 00:11:49', '2026-11-04 18:43:55', NULL),
(545, 16052, 4, 547, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 832, 1, 1, 1720, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(546, 16053, 4, 548, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 833, 1, 1, 2131, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(547, 16054, 4, 549, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 834, 1, 1, 3183, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(548, 16055, 4, 550, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 835, 1, 1, 1731, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(549, 16056, 4, 551, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 836, 1, 1, 1264, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(550, 16057, 4, 552, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 837, 1, 1, 751, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(551, 16058, 4, 553, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 838, 1, 1, 143, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(552, 16059, 4, 554, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 839, 1, 1, 743, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(553, 16060, 4, 555, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 840, 1, 1, 97, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(554, 16061, 4, 556, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 841, 1, 1, 433, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(555, 16062, 4, 557, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 842, 1, 1, 1587, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(556, 16063, 4, 558, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 843, 1, 1, 207, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(557, 16064, 4, 559, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 844, 1, 1, 30, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(558, 16065, 4, 560, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 845, 1, 1, 967, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(559, 16066, 4, 561, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 846, 1, 1, 4962, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(560, 16067, 4, 562, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 847, 1, 1, 612, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(561, 16069, 4, 563, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 848, 1, 1, 748, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(562, 16070, 4, 564, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 849, 1, 1, 1679, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(563, 16072, 4, 565, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 850, 1, 1, 1876, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(564, 16073, 4, 566, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 851, 1, 1, 571, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(565, 16074, 4, 567, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 852, 1, 1, 1294, '2026-10-31 00:11:50', '2026-11-04 18:43:55', NULL),
(566, 16075, 4, 568, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 853, 1, 1, 1897, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(567, 16076, 4, 569, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 854, 1, 1, 3838, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(568, 16077, 4, 570, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 855, 1, 1, 2633, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(569, 16078, 4, 571, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 856, 1, 1, 1442, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(570, 16079, 4, 572, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 857, 1, 1, 1628, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(571, 16080, 4, 573, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 858, 1, 1, 763, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(572, 16081, 4, 574, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 859, 1, 1, 1275, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(573, 16082, 4, 575, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 860, 1, 1, 2028, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(574, 16083, 4, 576, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 861, 1, 1, 2712, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(575, 16084, 4, 577, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 862, 1, 1, 579, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(576, 16085, 4, 578, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 863, 1, 1, 1933, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(577, 16086, 4, 579, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 864, 1, 1, 1314, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(578, 16087, 4, 580, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 865, 1, 1, 720, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(579, 16088, 4, 581, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 866, 1, 1, 2541, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(580, 16089, 4, 582, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 867, 1, 1, 470, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(581, 16090, 4, 583, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 868, 1, 1, 398, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(582, 16091, 4, 584, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 869, 1, 1, 807, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(583, 16093, 4, 585, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 870, 1, 1, 6246, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(584, 16095, 4, 586, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 871, 1, 1, 943, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(585, 16097, 4, 587, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 872, 1, 1, 906, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(586, 16098, 4, 588, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 873, 1, 1, 2604, '2026-10-31 00:11:51', '2026-11-04 18:43:55', NULL),
(587, 16099, 4, 589, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 874, 1, 1, 597, '2026-10-31 00:11:52', '2026-11-04 18:43:55', NULL),
(588, 16100, 4, 590, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 875, 1, 1, 182, '2026-10-31 00:11:52', '2026-11-04 18:43:55', NULL),
(589, 16104, 4, 591, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 876, 1, 1, 919, '2026-10-31 00:11:52', '2026-11-04 18:43:55', NULL),
(590, 16105, 4, 592, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 877, 1, 1, 2468, '2026-10-31 00:11:52', '2026-11-04 18:43:55', NULL),
(591, 16106, 4, 593, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 878, 1, 1, 1866, '2026-10-31 00:11:52', '2026-11-04 18:43:55', NULL),
(592, 16108, 4, 594, '', '', '', 25, 41, '2026-10-31', 'active', NULL, 1, 0, '0', 879, 1, 1, 1135, '2026-10-31 00:11:52', '2026-11-04 18:43:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tw_meter_types`
--

CREATE TABLE `tw_meter_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `meter_type_name` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `metersize` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_meter_types`
--

INSERT INTO `tw_meter_types` (`id`, `org_id_fk`, `meter_type_name`, `description`, `metersize`, `created_at`, `updated_at`) VALUES
(1, 1, 'ครัวเรือน', NULL, 3, '2026-10-27 21:22:37', '2026-10-27 21:22:37'),
(2, 4, 'ครัวเรือน', NULL, 3, '2026-10-28 20:07:15', '2026-10-28 20:07:15');

-- --------------------------------------------------------

--
-- Table structure for table `tw_meter_type_rate_configs`
--

CREATE TABLE `tw_meter_type_rate_configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meter_type_id_fk` bigint(20) UNSIGNED NOT NULL,
  `pricing_type_id` bigint(20) UNSIGNED NOT NULL COMMENT 'การคิดเงิน fixed หรือ progess',
  `tier_calculation_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Progressive =>1=คิดเงินซอยคิดทีละช่วง, 2เอาหน่วยน้ำ x อัตราของช่วงนั้น',
  `min_usage_charge` float DEFAULT NULL COMMENT 'ค่ารักษามิเตอร์',
  `charge_min_only_zero_use` tinyint(4) NOT NULL DEFAULT 0 COMMENT ' 0 = คิดรวมกับค่าน้ำเสมอ, 1 = คิดเฉพาะเมื่อใช้น้ำ 0 หน่วย ',
  `vat` float NOT NULL DEFAULT 0,
  `fixed_rate_per_unit` float DEFAULT NULL,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_meter_type_rate_configs`
--

INSERT INTO `tw_meter_type_rate_configs` (`id`, `meter_type_id_fk`, `pricing_type_id`, `tier_calculation_type`, `min_usage_charge`, `charge_min_only_zero_use`, `vat`, `fixed_rate_per_unit`, `effective_date`, `end_date`, `is_active`, `comment`, `created_at`, `updated_at`, `org_id_fk`) VALUES
(1, 1, 2, 2, 10, 0, 0.07, NULL, '2026-08-01', NULL, 1, NULL, NULL, NULL, 2),
(3, 1, 1, 1, 10, 1, 7, 8, '2026-10-28', NULL, 1, NULL, '2026-10-27 21:23:03', '2026-10-27 21:23:03', 1),
(4, 2, 1, 1, 0, 0, 0, 8, '2026-10-29', NULL, 1, NULL, '2026-10-28 20:07:45', '2026-10-28 20:07:45', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tw_meter_type_rate_tiers`
--

CREATE TABLE `tw_meter_type_rate_tiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meter_type_rate_config_id` bigint(20) UNSIGNED NOT NULL,
  `min_units` int(11) NOT NULL,
  `max_units` int(11) DEFAULT NULL,
  `rate_per_unit` float NOT NULL,
  `tier_order` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_meter_type_rate_tiers`
--

INSERT INTO `tw_meter_type_rate_tiers` (`id`, `meter_type_rate_config_id`, `min_units`, `max_units`, `rate_per_unit`, `tier_order`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 100, 8, 1, NULL, NULL, NULL),
(2, 1, 101, 9999, 9, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tw_notifies`
--

CREATE TABLE `tw_notifies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `org_id_fk` varchar(191) DEFAULT NULL,
  `reporter_name` varchar(191) NOT NULL,
  `reporter_phone` varchar(191) NOT NULL,
  `system_type` varchar(191) NOT NULL DEFAULT 'water',
  `issue_type` bigint(20) NOT NULL,
  `custom_issue_type` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `photo_path` varchar(191) DEFAULT NULL,
  `status` enum('pending','processing','complete','cancel') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_notifies`
--

INSERT INTO `tw_notifies` (`id`, `user_id`, `org_id_fk`, `reporter_name`, `reporter_phone`, `system_type`, `issue_type`, `custom_issue_type`, `description`, `latitude`, `longitude`, `photo_path`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, '3', 'อนงค์ กุมมารสิทธิ์', '0000000000', 'recycle_trash', 15, '15', 'มีหนอน\n[บันทึกการซ่อม]: เสร็จแล้ว', 17.29451600, 104.11764500, '\"[\\\"notify\\\\\\/CKVIh6FyYc2pmvcchVYV8XBDDUYNdWxP9l1BOhjI.png\\\",\\\"notify\\\\\\/mLwJ17ojvQqZ5eUiMx5Q1rSLXw3to5OlgRfnfCsL.png\\\"]\"', 'complete', '2026-08-25 21:23:31', '2026-08-26 03:34:21');

-- --------------------------------------------------------

--
-- Table structure for table `tw_notify_staff`
--

CREATE TABLE `tw_notify_staff` (
  `notify_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `staff_status` enum('accepted','working','completed','rejected') NOT NULL DEFAULT 'accepted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_notify_staff`
--

INSERT INTO `tw_notify_staff` (`notify_id`, `user_id`, `staff_status`, `created_at`, `updated_at`) VALUES
(1, 3857, 'working', '2026-08-26 03:33:26', '2026-08-26 03:33:26');

-- --------------------------------------------------------

--
-- Table structure for table `tw_pricing_types`
--

CREATE TABLE `tw_pricing_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tw_pricing_types`
--

INSERT INTO `tw_pricing_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'fixed', 'อัตราคงที่', NULL, NULL),
(2, 'progressive', 'อัตราก้าวหน้า', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `undertaker_subzones`
--

CREATE TABLE `undertaker_subzones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `twman_id` bigint(20) UNSIGNED NOT NULL,
  `subzone_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `undertaker_subzones`
--

INSERT INTO `undertaker_subzones` (`id`, `twman_id`, `subzone_id`, `created_at`, `updated_at`) VALUES
(3, 9, 1, NULL, NULL),
(4, 9, 3, NULL, NULL),
(5, 9, 5, NULL, NULL),
(6, 4, 2, NULL, NULL),
(7, 4, 15, NULL, NULL),
(9, 10, 6, NULL, NULL),
(10, 10, 11, NULL, NULL),
(11, 7, 7, NULL, NULL),
(12, 7, 19, NULL, NULL),
(13, 17, 8, NULL, NULL),
(14, 8, 9, NULL, NULL),
(15, 8, 10, NULL, NULL),
(16, 5, 12, NULL, NULL),
(17, 5, 17, NULL, NULL),
(18, 11, 14, NULL, NULL),
(19, 11, 16, NULL, NULL),
(20, 11, 18, NULL, NULL),
(21, 11, 20, NULL, NULL),
(22, 6, 21, NULL, NULL),
(23, 11, 35, NULL, NULL),
(24, 1, 36, NULL, NULL),
(25, 1, 41, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED DEFAULT NULL,
  `username` varchar(191) DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `prefix` varchar(191) DEFAULT NULL,
  `firstname` varchar(191) DEFAULT NULL,
  `lastname` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `subzone_id` bigint(20) UNSIGNED NOT NULL,
  `tambon_code` bigint(20) UNSIGNED NOT NULL,
  `district_code` bigint(20) UNSIGNED NOT NULL,
  `province_code` bigint(20) UNSIGNED NOT NULL,
  `age` int(11) NOT NULL DEFAULT 0,
  `weight` double NOT NULL DEFAULT 0,
  `height` double NOT NULL DEFAULT 0,
  `gender` enum('m','w') DEFAULT NULL,
  `line_id` varchar(191) DEFAULT NULL,
  `line_user_id` varchar(191) DEFAULT NULL COMMENT 'มาจาก Api',
  `id_card` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive','deleted') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `org_id_fk`, `username`, `password`, `prefix`, `firstname`, `lastname`, `email`, `zone_id`, `subzone_id`, `tambon_code`, `district_code`, `province_code`, `age`, `weight`, `height`, `gender`, `line_id`, `line_user_id`, `id_card`, `phone`, `image`, `address`, `email_verified_at`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'superadmin', '$2y$10$2U0dcCUijcIqSS2iOkMrYe6vAepzFKUccWxAvOwPnHVIUvHk1WZZm', 'นาย', 'super', 'admin', 'superadmin@gmail.com', 1, 1, 350805, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, 'x', '2026-10-30 19:25:36', NULL, 'active', '2026-10-30 19:25:36', '2026-10-30 19:25:36'),
(2, 4, 'swadmin1', '$2y$10$GWc3AEN9Dj2186olerrryuYArenDF3Tmy34y5Jl9uahWhqVNOCv8e', 'นาย', 'sw', 'admin', 'swadmin1@gmail.com', 1, 1, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, 'x', '2026-10-30 19:25:36', NULL, 'active', '2026-10-30 19:25:36', '2026-10-30 19:25:36'),
(3, 4, 'user_swat3', '$2y$10$eNVH6fnZR9Vw3g973gfHp.bGuih.gUr/KdhLxl/O0lJW1Ed3g2mSq', '', 'นายล้ำ ด้วงคำจันทร์', '', 'user_swat3@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '2', '2026-10-30 19:25:36', NULL, 'active', '2026-10-30 19:25:36', '2026-10-30 19:25:36'),
(4, 4, 'user_swat4', '$2y$10$WVHDEAmzfqRi/AL.5FD3s.VJPmtLNwK.Q1YbzOckN/hXEZTN7qSru', '', 'นายอำพร ทาสีเพชร', '', 'user_swat4@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '258', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(5, 4, 'user_swat5', '$2y$10$.QZM4tYauv1/ED99PXHHQujhBiOHDU8NwHOUZHkme.njT3/fJTdLW', '', 'นางคุณ จากผา', '', 'user_swat5@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '39', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(6, 4, 'user_swat6', '$2y$10$aU.6l.iwv8jN64sRvb5GxuYV57bf6rayXIKFcJ.zvy.qCqzvoaX16', '', 'นายเสี่ยงชอบ พรมภักดี', '', 'user_swat6@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '63', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(7, 4, 'user_swat7', '$2y$10$efWsECDIHVyNTgkUxN3oN.zfwGiiO20B4gyuQmiMkbMo4.hDKnyZm', '', 'นางทิพย์ จากผา', '', 'user_swat7@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '58', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(8, 4, 'user_swat8', '$2y$10$iN4nE3FzIz4sTWqa9vFMXux8tCCTAaixc85JjOOxexa8D25zgrdHC', '', 'นางหนูแพง ศรีมีวงษ์', '', 'user_swat8@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '55', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(9, 4, 'user_swat9', '$2y$10$SSmtq/w4r5R5f.NuVb1RxeZrGYQuh4GChKHshmTq4uMdXSsBhMCL.', '', 'นางศรัญญา เคยสนิท', '', 'user_swat9@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '199', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(10, 4, 'user_swat10', '$2y$10$zw9M4cz/xLqpxQTzvf5VUOQQTgsmtVWrtT3Ic3iycHOMzV5SdcOyC', '', 'นายเกตุจันทร์ แสงฉวี', '', 'user_swat10@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '46', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(11, 4, 'user_swat11', '$2y$10$4i1.95ZzDRbyO22Ap84osOANV7dILqRADg1tXD1JsIxnEcn54uIPa', '', 'นางสมบูรณ์ ผ่านเมือง', '', 'user_swat11@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '66', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(12, 4, 'user_swat12', '$2y$10$kat73qE3DABCD1kAWY/P3Orn1jaMe.5jLWF4G/3gmHY1VCbobFMjm', '', 'นายสวาท สลับศรี', '', 'user_swat12@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '73', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(13, 4, 'user_swat13', '$2y$10$LuFoSVK0XWlPuEQiwkGwN.U0MGTdXKa1j6VO4JksMl03sz1g6wT5C', '', 'นายคำปั่น แสงฉวี', '', 'user_swat13@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '75', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(14, 4, 'user_swat14', '$2y$10$M1qrgtdwveGIxEcAbtvAJuCABsv7d30RdFysduByhKOlacomC4bVS', '', 'นางลี อยู่สุข', '', 'user_swat14@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '76', '2026-10-30 19:25:37', NULL, 'active', '2026-10-30 19:25:37', '2026-10-30 19:25:37'),
(15, 4, 'user_swat15', '$2y$10$UKOb5rlKM7atfmPwOy6/zuMMVdzlzDlYK1.KdguetdxcZF9kGpdJi', '', 'นางทิพย์สุดา จากผา', '', 'user_swat15@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '60', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(16, 4, 'user_swat16', '$2y$10$60mMZLCF8a7RxqW4TIwYg.i8JxGPBuc3ZVIsnXxwYRdAzM.W.3GfK', '', 'นายสมพงษ์ ยิ่งยง', '', 'user_swat16@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '19', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(17, 4, 'user_swat17', '$2y$10$3MYGVN5v12c/RCmbeDWONOxXGkc7pQPv6czD/yy3V6ttyGNE1c.fW', '', 'นายชาดี ลาสินธิ', '', 'user_swat17@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '319', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(18, 4, 'user_swat18', '$2y$10$a148U/CPRGOSzf9Q3atM5OIHj5sWJg2S5RJlE9Xe06Et5kE8yzK8G', '', 'นายสุนทร แสงฉวี', '', 'user_swat18@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '93', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(19, 4, 'user_swat19', '$2y$10$6vqIJuOV2JFDYU3HtsUaQ.0ya2dtUJO5TnZgl.XacEs2WQjZFnf2O', '', 'นายบัวเรียน หาหลัก', '', 'user_swat19@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '21', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(20, 4, 'user_swat20', '$2y$10$X8DO41oCe0wlmg4n9tEoa.fwLgNhKcvuNL8SwPVailo1CX2iuwzcW', '', 'นางวานิช แสงฉวี', '', 'user_swat20@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '208', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(21, 4, 'user_swat21', '$2y$10$ccGTwo5H02ji.gCrCysLdeBEBaoEeOUW7U6jpTSKGivmq5gi8LyYu', '', 'นายแสงชัย มณีล้ำ', '', 'user_swat21@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '126', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(22, 4, 'user_swat22', '$2y$10$pdUHLl6w5DqhF3TqWSsF0.sGnazll4hSLZG1lBah/DxW7nK8RDv/u', '', 'นายดำรงค์  เคยสนิท', '', 'user_swat22@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '77', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(23, 4, 'user_swat23', '$2y$10$Scj/1AEwGSRqdSYGK84eIOBA4jwboAHBC1vaqYsV82CyJ74vR5WT.', '', 'นายประไพ แสงฉวี', '', 'user_swat23@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '301', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(24, 4, 'user_swat24', '$2y$10$rJtMJR.23pzGFoqp3z/xKuGqJLK0POU55ODIEDwbSDsAV40VmW4rS', '', 'นายทักษิญ สังฆพรม', '', 'user_swat24@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '241', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(25, 4, 'user_swat25', '$2y$10$BuYkuwkgnMLLi6Cg3s3fTOXj8rPCRvJUFXvhhV1iQdd0rKzlisBgG', '', 'นางคำผล วงษ์ศาจารย์ (1)', '', 'user_swat25@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '7', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(26, 4, 'user_swat26', '$2y$10$SoS1jaWSvRdFw.iW1VOPzed0ExNLGiDtPnk1BD3.1iH0ajkBU2V8a', '', 'นางคำผล วงษ์ศาจารย์ (2)', '', 'user_swat26@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '7', '2026-10-30 19:25:38', NULL, 'active', '2026-10-30 19:25:38', '2026-10-30 19:25:38'),
(27, 4, 'user_swat27', '$2y$10$J/YEH2KiBHhgaD5tQ6kWZeRHYXPdG6yaLnG6IxM0eT3ESTikO4RA.', '', 'นายอุบล วงษ์ศาจารย์', '', 'user_swat27@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '116', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(28, 4, 'user_swat28', '$2y$10$EzwBdw2RhNsGddnp7d6tauJbuAzWGY1K52VsIkRgXEx27k/AavG4G', '', 'นายเคน หอมหวน', '', 'user_swat28@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '8', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(29, 4, 'user_swat29', '$2y$10$eXZYYnBc6o/999YEQABFcORyuevbsiKFzaHjB8QkoYHhMpjqYLf0m', '', 'นางอรพิน หลวงโคตร', '', 'user_swat29@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '252', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(30, 4, 'user_swat30', '$2y$10$TzOt8gSo58/hqy7lqbsuhewnqXo8yOOtkLyZ4iH9FvU3THGmW/bQ6', '', 'นายฉัตรชัย ผ่านเมือง', '', 'user_swat30@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '382', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(31, 4, 'user_swat31', '$2y$10$GdYzpxutAmk808Uy6lVRqeDYcJc8FOl3M61EvrMn/JGaKgiE.NUoK', '', 'นายสมจิตร โคตรวันทา', '', 'user_swat31@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '224', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(32, 4, 'user_swat32', '$2y$10$zfcTpbfU0DtUgimY5TPqauG3Uh8PzGPXn8LtZmUSfwppQusU7ZMYe', '', 'นายวิชัย ไชยสัจ', '', 'user_swat32@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '129', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(33, 4, 'user_swat33', '$2y$10$qN/ouJN3KF/7d/FbP6OwdekAXG0KSqlcS9fsvGzM.l4zQbn0i1Svi', '', 'นายฤดี โพธ์สาพิมพ์', '', 'user_swat33@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '36', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(34, 4, 'user_swat34', '$2y$10$PflynpaWaT9Xwlux8uMLHONOfk1xiDfHbSU0Tug.S2wy.oPiG30iu', '', 'นายวีระ คุณุ', '', 'user_swat34@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '202', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(35, 4, 'user_swat35', '$2y$10$jjz4OcGJofa0cArMkT7JLuqsKTqz3feZWWocK3/ORtaiOrV.I/S7S', '', 'นางสาวแพรว จากผา', '', 'user_swat35@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '328', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(36, 4, 'user_swat36', '$2y$10$Ojvl7tMqNlKpUf5XkPpAWuFpQvkIvt6eEE9Oml.krOSRzFaGyh.Me', '', 'นายประสิทธิ ชัยรักษ์', '', 'user_swat36@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '321', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(37, 4, 'user_swat37', '$2y$10$8UgzqXz9dEmjPU5nbVRRc.H2M2whX/Q4YUYJxs2v/tb5fp0pzNTKa', '', 'นางหนูเจรียร แสงฉวี', '', 'user_swat37@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '45', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(38, 4, 'user_swat38', '$2y$10$oT4ZOcsq1R66YeUBR82M6ePLWOTpgty71C4Sfh/h4pM69TuwCTSi2', '', 'นายบุตรดี ดวงเคน', '', 'user_swat38@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '318', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(39, 4, 'user_swat39', '$2y$10$4COK5RsHLPt6OGuwRHeZ3Oa1JEhMQXo1hbXf8VNCLQILE/gOsjSC2', '', 'นางนารี ดวงเคน', '', 'user_swat39@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '184', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(40, 4, 'user_swat40', '$2y$10$34PUsi4nnq53sekwiukAn.KVXiETK2ti/LNmF55UunxQs1lGkodTS', '', 'นายสมบิน รักถิ่น', '', 'user_swat40@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '29', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(41, 4, 'user_swat41', '$2y$10$2P8AXjTyj8gCZPqRRW0FS.Ms.faM4d/cYjfP2teJj95KwlI.pN8Z.', '', 'นางสดใส จากผา', '', 'user_swat41@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '254', '2026-10-30 19:25:39', NULL, 'active', '2026-10-30 19:25:39', '2026-10-30 19:25:39'),
(42, 4, 'user_swat42', '$2y$10$lTzeMZ/5siPlIHmsCsBvXOtqcYELjKwIt/RDbV/04p.aP28kmOKPK', '', 'นายบุญเสริม บุญกล้า', '', 'user_swat42@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '11', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(43, 4, 'user_swat43', '$2y$10$Y6UIQHCh2ba4irS6.FaeBOpsuqiYFoZ05WfT56wPNw5etXhTF.ICm', '', 'นายจินรา จากผา', '', 'user_swat43@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '236', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(44, 4, 'user_swat44', '$2y$10$B3ZWH6A4A317EVTgXTmIZOe3pl0//Wk27KeDIvm92zxcLDV.9WzxK', '', 'นายชาญยุทธ ผันผ่อน', '', 'user_swat44@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '238', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(45, 4, 'user_swat45', '$2y$10$EuJ6kpbHCl8BzhfN5.h/BOhJ9Gua/eBlY4LGWj4borko3.m8I0e4a', '', 'นายประภาส จันทร์น้อย', '', 'user_swat45@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '242', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(46, 4, 'user_swat46', '$2y$10$oV84TuYYWa.jOeaO2nOrx.6dWNNYMEIKSYl3lXwBWqvyaEZ0flWge', '', 'นายฉลาด แสงฉวี', '', 'user_swat46@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '112', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(47, 4, 'user_swat47', '$2y$10$.jfsqyRkGagt./r7bynyT.A/Lgy0vJuWm//XOYn9XO6REZIcVq3uu', '', 'นางสาวไลรักษ์ ผ่านเมือง', '', 'user_swat47@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '66', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(48, 4, 'user_swat48', '$2y$10$jgdL.AF8JtL1C6N4yXjnLefUjycYdb/hmSieG5lR1X0zbNvUg/yIm', '', 'นายสมชิต ทาสีเพชร', '', 'user_swat48@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '176', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(49, 4, 'user_swat49', '$2y$10$NxyN34cl/pGH7KbYcmUFhOe5cJRLhx15VQwvZe.WrKY7FBv00e14G', '', 'นายวิรัตน์ โสมณวัตร', '', 'user_swat49@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '97', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(50, 4, 'user_swat50', '$2y$10$2wRAWFTyZa8o9U.BGRbVieGnbWObtJM7yNMRMzdXQMAWaXCdh1wgO', '', 'นายอิทธิพล อินเรืองนาม', '', 'user_swat50@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '37', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(51, 4, 'user_swat51', '$2y$10$NB45jMsjs.ZJmi64uzQUU.7vb2.Ke7oc5RnbZf3yag/3c.jzrW8JS', '', 'นางทองใส พวงผกา', '', 'user_swat51@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '101', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(52, 4, 'user_swat52', '$2y$10$W22cBtdv4n566wBa1eDyS.x9XEV.YD0tz00DHMRtxEkR68WD2U1Jm', '', 'นางลาต้า เถาว์ที', '', 'user_swat52@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '69', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(53, 4, 'user_swat53', '$2y$10$BzO4Q9Q/EqEe6L8qHiQa8.ZfuA0Cwyp2lDjOfvbMnUz6y4VINsB5u', '', 'นายทวีศักดิ์ เนตรวงค์', '', 'user_swat53@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '232', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(54, 4, 'user_swat54', '$2y$10$fsuYzFgJTAydk4bopHAi3.lStmA3oWiX87cUuLOAqRV2OttH01oBa', '', 'นายแดง พันธ์สุวรรณ', '', 'user_swat54@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '248', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(55, 4, 'user_swat55', '$2y$10$qqCWci5J5pUigKNQ9K4YsO1e47yef2MwK9l38om201o5icsi/8766', '', 'นายมูลเสรี เนตรวงส์', '', 'user_swat55@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '297', '2026-10-30 19:25:40', NULL, 'active', '2026-10-30 19:25:40', '2026-10-30 19:25:40'),
(56, 4, 'user_swat56', '$2y$10$qDJIpWh9xA/1KoYB0bbGvueFpR1tPUE1I53ptLOVdHB4sOl6cPIfC', '', 'นางศศิมา เนตรวงค์', '', 'user_swat56@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '239', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(57, 4, 'user_swat57', '$2y$10$T2TaOOHsBDtaTni5it7juuHolBXAIShFLl3ZbPmC4mARIeTj57MO.', '', 'นายประสงค์ จันทร์สำราญ', '', 'user_swat57@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '166', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(58, 4, 'user_swat58', '$2y$10$UyACNdx.sqLpfweTdwxFueqrpbWzUtxGsGNjQU4FV/R9En9VovvbO', '', 'นางดวงใจ วรรณสุข', '', 'user_swat58@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '54', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(59, 4, 'user_swat59', '$2y$10$tFKnVWOqy.Pq45YwplqN3OPHTd2wQdVeRw1i.vgnxotHLPUbuqjG.', '', 'นายลำพูล ทางาม', '', 'user_swat59@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '273', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(60, 4, 'user_swat60', '$2y$10$asW3uT45qoPtoO3/M3Mnduz86RzhVTEQ54j52wVMKHlOCJEQ4//2e', '', 'นายสำราญ โสภาบุญ', '', 'user_swat60@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '231', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(61, 4, 'user_swat61', '$2y$10$AZFM1XjwgmWntrGoHnMgx.1sFeMDdUjDJ8pw8HyesbGtKy5ixJiWC', '', 'นายลัย แนวกันยา', '', 'user_swat61@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '38', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(62, 4, 'user_swat62', '$2y$10$49q3X1T1rHy6JO7NFxbM7eeZKPa4H4PiRTMCIpWq4uyP9HPXBw3du', '', 'นายดวงใจ จากผา', '', 'user_swat62@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '51', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(63, 4, 'user_swat63', '$2y$10$gZOyRGDmpj.ZadRW.Rtfa.c0bnuollwjRafc3.qbFrcfnpOQ09CZS', '', 'นายสมพงษ์ ยิ่งยง', '', 'user_swat63@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '19', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(64, 4, 'user_swat64', '$2y$10$v.qFkmp2bXJIUqBqrfazRul8n6aTZ6pvtebGatuMOc7o9xVpu1Nci', '', 'นางเยาวเรศ สลับศรี', '', 'user_swat64@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '54', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(65, 4, 'user_swat65', '$2y$10$T7/qaWwneSSKCRBYEvQtf.ItL26gMyWcuWJloGKZ4YHuv4oxMnCKO', '', 'นายสมพัด แสงฉวี', '', 'user_swat65@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '263', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(66, 4, 'user_swat66', '$2y$10$U8NbJ0uNdTgI5RD1cMf3VuWuvzJfHP4YL/tWuxoC3GAjoRdPILVCK', '', 'นางสีสุพรรณ ดวงเคน', '', 'user_swat66@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '134', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(67, 4, 'user_swat67', '$2y$10$F7fEMbQRBO/eOgtfZd0KW.LWxbhyxfWYGy/S9qHrVaqevyGBGHDI2', '', 'นางหนูกร ฝอยทอง', '', 'user_swat67@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '49', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(68, 4, 'user_swat68', '$2y$10$BSdpxT4gaHuqf0o0GOgYZOQJtWiUU5a98z3qvv2R8tV72YFatg2fa', '', 'นายสายันต์ จากผา', '', 'user_swat68@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '217', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(69, 4, 'user_swat69', '$2y$10$fiKBk6twDTmW/iIYDX3qLe5eylz2OuuSK926ZCu6M1x/FBHpOaKoK', '', 'นางชมเดือน ทาสีเพชร', '', 'user_swat69@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '288', '2026-10-30 19:25:41', NULL, 'active', '2026-10-30 19:25:41', '2026-10-30 19:25:41'),
(70, 4, 'user_swat70', '$2y$10$cM38gqKOnNjxhBeC2qfM.upotqNggDMlDlwFXiNQjBDnw0qypKyyi', '', 'นายวิวัฒน์ อยู่สุข', '', 'user_swat70@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '76', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(71, 4, 'user_swat71', '$2y$10$.l8H9jIolqU53J9GR1aeheUGZj8mE8Ybrm2IO3h6O.9JMjpTBh2AG', '', 'นายอำนวย จันทร์สำราญ', '', 'user_swat71@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '197', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(72, 4, 'user_swat72', '$2y$10$geepeGB2jYbh2t1xUEmEzeVccUPh9mXN04CVdKBta5pOPF/AZ43Vm', '', 'นายพิทยา ลาสนธิ', '', 'user_swat72@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '93', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(73, 4, 'user_swat73', '$2y$10$/eYmFpsys8cR5IEg8Y5.sOuYyxfBjlGxQ0grmBaCkfoRX49yiflIq', '', 'นายปริญญา หลักทองคำ', '', 'user_swat73@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '164', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(74, 4, 'user_swat74', '$2y$10$nRSn64NO51qiCcWG3YRzNeeKBfdBqJZ7v3yV2C0wdn5ECTrYnPDx6', '', 'นายฉัตรชัย เนตรวงค์', '', 'user_swat74@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '353', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(75, 4, 'user_swat75', '$2y$10$c9bUn8NmWcBgQluCS2NsOeCY4PlW0lyM1zSmpu1kyiFUHDUr1AR.u', '', 'นางหนูเจียร แสงฉวี', '', 'user_swat75@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(76, 4, 'user_swat76', '$2y$10$uw4TcUkRj7XSZLgVu.JvCulh0RvdhhIyxcXM342CcbBELdtBqCAjS', '', 'นายสาคร บุญศิลป์', '', 'user_swat76@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '181', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(77, 4, 'user_swat77', '$2y$10$vQpwyEiFYTVmsrSi6gAvueLDx4JP8wKPRZGCmdK9sX6Th/aHWLJai', '', 'นายตรีเพชร ทาสีเพชร', '', 'user_swat77@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '143', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(78, 4, 'user_swat78', '$2y$10$JcFW1vgOp3Y5LUNhcCG4FO1iSvISfznzg8rPQCrug0UeKs1b8av9C', '', 'นายวิชัย ไชยสัจ', '', 'user_swat78@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '129', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(79, 4, 'user_swat79', '$2y$10$5DJ.b8M/lbNbCii4uDm9/.WK6B3O9vTL8SKH8k5rxytfGrjKldlUO', '', 'นางต้อม จากผา', '', 'user_swat79@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '179', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(80, 4, 'user_swat80', '$2y$10$ocWlN1eDxtz3z3k70GE0oOZqO0SXPXgEKr4QIguddlQA4OjFZdBeS', '', 'นายกวีพจน์ ผ่านเมือง', '', 'user_swat80@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '36', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(81, 4, 'user_swat81', '$2y$10$mNaGfLu9jDPbqJdOJ/uSUeNVb1pw9gLr3UrD7AWyJPTMZOsOPVgGy', '', 'นายปราโมทย์ โสภาบุญ', '', 'user_swat81@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(82, 4, 'user_swat82', '$2y$10$LpQkk1v1qiLoksn9swSxfeS7DBUq4PPA2Y4hWYr4/sk6Gml/OWada', '', 'นางแดง ขุมคำ', '', 'user_swat82@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '248', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(83, 4, 'user_swat83', '$2y$10$f.9CCbMDUUeBB27naj43he4gNHsLaOxR0YvNZg6AANODtFABSv1nm', '', 'นายสุพจน์ ธาตุหลง', '', 'user_swat83@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '251', '2026-10-30 19:25:42', NULL, 'active', '2026-10-30 19:25:42', '2026-10-30 19:25:42'),
(84, 4, 'user_swat84', '$2y$10$2/Z2STn.0aWU9ZJ1ydhbzOHIZUmJT6vcyGd6Gba4MDfBEXi6OT1OG', '', 'นายอดุลย์ สมาทอง', '', 'user_swat84@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '18', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(85, 4, 'user_swat85', '$2y$10$TPzMlTDybtFLQdEZduaHzOOmlui5tlyiVHQid0T5TVyybWXZ7zG1q', '', 'นางเฉลิม อิสสะภัย', '', 'user_swat85@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '187', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(86, 4, 'user_swat86', '$2y$10$k2crMAexlHQm9ZGHnkwCi.AKNdrt5.EpFwIIOfOX.p8vG6rxz68Pe', '', 'นายวิชัย ศรีโย', '', 'user_swat86@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '298', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(87, 4, 'user_swat87', '$2y$10$VkqRD.wrmxmK2u31l2VHb.pNk7t8zA7Hp8LXsc/tQufocjuQXlhEG', '', 'นายหนา หลักทองคำ', '', 'user_swat87@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '326', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(88, 4, 'user_swat88', '$2y$10$.P56ALOoNX4nt8cMZkStje2oWH4kRk0s.EXwLRsBg0lGUI7BJwZ5q', '', 'นางชาดี ลาสนธิ (2)', '', 'user_swat88@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '319', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(89, 4, 'user_swat89', '$2y$10$BuKDYTWshI6E5tJ6SA82iuMMQS7kadrNwCgf9NEYNlu8z0Rhl3uby', '', 'นายวิชัย อยู่สุข', '', 'user_swat89@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '236', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(90, 4, 'user_swat90', '$2y$10$mRx4e8PLUhRJFZKqdQ2fteoUqnrp4NIbNnu7eY4XwG96crWRVOTD6', '', 'นายคมสันต์ อยู่สุข', '', 'user_swat90@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '205', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(91, 4, 'user_swat91', '$2y$10$PPK7qyNmaNSt5lxrArAq7.FK2PXAz83xUPm98uzr/ooRVf81r3uKi', '', 'นายเปี่ยง แสงฉวี', '', 'user_swat91@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '1', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(92, 4, 'user_swat92', '$2y$10$M1sSQtzYy4wat4Nbzn/uRe.jz8N8cz5asFWMsHA0Th33fATeh8MPO', '', 'นายประทีพ เสวะนาม', '', 'user_swat92@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '36', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(93, 4, 'user_swat93', '$2y$10$/9zmYWFloVgdfYP.bkg0v.ZqfNswYP0S8kZVhKgmt7D/L1k1JBvie', '', 'นายทองเนตร ผ่านเนตร', '', 'user_swat93@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '54', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(94, 4, 'user_swat94', '$2y$10$MzIuhEU4Cdsmlrbs4DFtuen9CPAuxeDN7Y/cDbM9wL/UdHQPnXBjm', '', 'นางมะลิวัล ผ่านเมือง', '', 'user_swat94@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '66', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(95, 4, 'user_swat95', '$2y$10$vjh6A2reffMYEwHogPeCzee3EWWd2Pi6cvXMhw8SVNby3b9.71r3e', '', 'นางลัดดา ละลี', '', 'user_swat95@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '25', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(96, 4, 'user_swat96', '$2y$10$M2oGITx1FRE.CCMB6HBPqOJz9yjou0CsljsxuLcUthoi6eIQX3/Ia', '', 'นางมินตรา สายรัตน์', '', 'user_swat96@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '174', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(97, 4, 'user_swat97', '$2y$10$Jr48oaTdtTP1gF6ZWXW.iOVFcbZOc3hhvq/1ex4e6J9iXlAIKRrWK', '', 'นายสุทธิโสม รักถิ่น', '', 'user_swat97@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '26', '2026-10-30 19:25:43', NULL, 'active', '2026-10-30 19:25:43', '2026-10-30 19:25:43'),
(98, 4, 'user_swat98', '$2y$10$Q6Vu5vDgrKhaO4HIVsE4E.bz5ZNhQEevRgE5hpJZge.ku4/WX0Ugu', '', 'นางแขก แสงฉวี', '', 'user_swat98@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '20', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(99, 4, 'user_swat99', '$2y$10$bEFQMHwB0GbSewmMbnbTqOteErUosWu8p0f.zZgWQOyyTNlaUbH/u', '', 'นายสมาน ทุ่มโมง', '', 'user_swat99@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(100, 4, 'user_swat100', '$2y$10$Y4Syx/wchuPhxdpmOIB5F.WZRjEELOKbJPFjqdUuhQXks1vnjbFve', '', 'นายประจวบ พันธ์พิพัฒน์', '', 'user_swat100@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '268', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(101, 4, 'user_swat101', '$2y$10$zctvpPVQWT6gPizl.w0XaejlbRWIKMY9SRE5c0pgd.m2blVotPEeS', '', 'นางเล็ก ทรงสังขาร', '', 'user_swat101@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '247', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(102, 4, 'user_swat102', '$2y$10$Y40iziAwkLZl8B8xJ4GPc.rbSmY3gX39z4tOvBI.FdWTdBc2tRxSa', '', 'นายหนูจร อยู่สุข', '', 'user_swat102@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '121', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(103, 4, 'user_swat103', '$2y$10$EmM3Pbt4er.DYVxKyLoLGu6sCkrQpNBXQwYWQ/ttsXPa0NGmFrY7.', '', 'นายองอาจ จากผา', '', 'user_swat103@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '90', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(104, 4, 'user_swat104', '$2y$10$nhZvc3biFq4r8U9oYputB.P4wdo6rsUBEqYj3ldxP6Dow7OIg.yWm', '', 'นายเดชา จากผา', '', 'user_swat104@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '136', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(105, 4, 'user_swat105', '$2y$10$pKAwKftPfrkHBoDNlji1yupVXjZIpxGCcuHLdQlmJIXBYRAATXWT2', '', 'นางรัตนา  โสภาส', '', 'user_swat105@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(106, 4, 'user_swat106', '$2y$10$/K6OyPoiT5UBokEHBKKZZuUJFMVnE7u4MxLRFrb5LtNs9IDR.gpbG', '', 'นางสาวมยุรา จักรสาน', '', 'user_swat106@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(107, 4, 'user_swat107', '$2y$10$fDYxPuwvNHqmULeRYUF0JepOcsQkdmYbfHmwusfMyCi0wH8OcSNZu', '', 'นายอัศวดล ค่ำคูณ', '', 'user_swat107@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '242', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(108, 4, 'user_swat108', '$2y$10$p1pTthWj.q1GlhgpkPL7P.lqeJeH2qmpxx.P4Yg0YeGu9VDCAkGRi', '', 'นางสาวศิราณี  กะมุทะ', '', 'user_swat108@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '121', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(109, 4, 'user_swat109', '$2y$10$l/FUzagq3Kq5IvJE5SeYReSi9SDH1ZxMILLV/mVuhYRyo1lrV388O', '', 'นายวิรมย์ศักดิ์  ยิ่งยง', '', 'user_swat109@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '72', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(110, 4, 'user_swat110', '$2y$10$YObVOGmjNQZFH2OEAqhv/.5nWzTy9Mw4/Ia0z1s2v1lXWkB5rV5ji', '', 'นายจวงจันทร์  ทองโสม', '', 'user_swat110@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '210', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(111, 4, 'user_swat111', '$2y$10$Nr8Gis5vds661gJVkpvPlelKhOYxdpZ2ViqR9ENHBIU48wqfKZQ8O', '', 'นายอิทธิพล อินทร์เรืองนาม', '', 'user_swat111@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '37', '2026-10-30 19:25:44', NULL, 'active', '2026-10-30 19:25:44', '2026-10-30 19:25:44'),
(112, 4, 'user_swat112', '$2y$10$U5DAgt89sLQ5QdlKqBiy.uXUIXzWzn2UXtOaUM3UR17JWDBqAsHpu', '', 'นายสวน มณีล้ำ', '', 'user_swat112@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '92', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(113, 4, 'user_swat113', '$2y$10$txe0RrzeOesiQLKnUuDqpuoQF8SpwAMKhrURHyxAty.ugTzM1huJq', '', 'นางทองนาค จันทร์บูรณ์', '', 'user_swat113@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '249', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(114, 4, 'user_swat114', '$2y$10$m/7kfswl/MJ0xphocxoRgeU8AgnW9FPbcARR7JNeGmjQCaBsAZpcu', '', 'นายพิพัฒน์ เวชตมา', '', 'user_swat114@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '229', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(115, 4, 'user_swat115', '$2y$10$EeqoNm0F6WqyMf7Uqayf3eUSd0UYFtjYmnRXLyah7PuYtsoHzR8lm', '', 'นายชรินทร์  นามคำ', '', 'user_swat115@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '262', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(116, 4, 'user_swat116', '$2y$10$k0gsGM762SSXwIk/mZo9wOxphl9ANYum82SmAZvJcw/CyyICrYtXW', '', 'นายจอมศักดิ์  ทรงสังขาร', '', 'user_swat116@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '302', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(117, 4, 'user_swat117', '$2y$10$h9dbiss4/GleThXob4LsUOOx6ez6axAC5fU7dskJDB9hz41Qk.5C.', '', 'นายสามารถ  สลับศรี', '', 'user_swat117@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '54', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(118, 4, 'user_swat118', '$2y$10$wJQE7gNg5kH5DAiUN/BFMu9UjXFzkrx/YcdVJssZnBTmy37tCLgJu', '', 'นายฉวีวรรณ์  โสภาบุญ', '', 'user_swat118@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '219', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(119, 4, 'user_swat119', '$2y$10$3/v3Hb56iPk4YSdIqTjnuemuMaFsO2RrYAH4e6HFGpNWwDEmP6jPG', '', 'นายอนุชัย เนตรวงค์', '', 'user_swat119@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '336', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(120, 4, 'user_swat120', '$2y$10$zrh8cxgv/MYApdIsGc29wuAZEahz/VMzIQvtkgJszErAwIdI5C.qe', '', 'นายจตุรพร  ทาสีเพชร', '', 'user_swat120@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '221', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(121, 4, 'user_swat121', '$2y$10$QZpcCH0bVfAg2e1Xk0WThuwN9i3H3FfitLhrz1VbwWy/5iwSOk9Uu', '', 'นายโกมล  ผ่านเมือง', '', 'user_swat121@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '206', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(122, 4, 'user_swat122', '$2y$10$aG5rLw/hhecP.VSkxlBQgO3igeO7jJRjQoxVawJ2vzQ8eCBQjB5lC', '', 'นางสาวอำพร  ปัดภัย', '', 'user_swat122@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '59', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(123, 4, 'user_swat123', '$2y$10$U0dfcSb74cw8xkljUTjb8OlNSrIX2cl6PjYGGwwtqeh5vOGTQPOiO', '', 'นางเทียนหอม  ธรรมบุตร', '', 'user_swat123@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '85', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(124, 4, 'user_swat124', '$2y$10$Yi9q3JxaGimMANGlZPXYC.g7tpBP.9R8fbECcRWiGQ1M8tz2lDXIO', '', 'นายนพพร  คุณุ', '', 'user_swat124@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '322', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(125, 4, 'user_swat125', '$2y$10$r67rqJgVzfDlGsOia7IRGulSuv7TTGx2QLzggr66zTjqmptEWRg5G', '', 'นางสาวธนภร ลาสนธิ', '', 'user_swat125@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '10', '2026-10-30 19:25:45', NULL, 'active', '2026-10-30 19:25:45', '2026-10-30 19:25:45'),
(126, 4, 'user_swat126', '$2y$10$aF0TT3/Pm0p4aWglhIMxJ.QJtnr70kxpnrcJuredistxI05.IhxWe', '', 'นางหวานใจ  ภูชนะศรี', '', 'user_swat126@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '27', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(127, 4, 'user_swat127', '$2y$10$xuGglCJXaDXhiAGcJqC3fO5QfFTbHy5hMnhY6ymHB8CnWPmoTzRMi', '', 'นายอัมพร สมหวัง', '', 'user_swat127@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '194', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(128, 4, 'user_swat128', '$2y$10$s8X2DAHN9dyPmwwlVppwkOGOi6WKuAeeNwJQXZVDwCRG7EdEvLyQy', '', 'นายณรงวิทย์ ไชยสัจ', '', 'user_swat128@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '293', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(129, 4, 'user_swat129', '$2y$10$8UjPKkQluaHY9cCnAMJiPOlzuTVBalDtn8Fd0wyEYDTB/MfKLr8sK', '', 'น.ส. นารี ดวงเคน', '', 'user_swat129@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '184', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(130, 4, 'user_swat130', '$2y$10$P7zMPcJxlD3FQDfDM1zwqeqCrARxtZqre5wLimy9NIuxuvgicb6FC', '', 'นายธนิต ทันติพัฒน์', '', 'user_swat130@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '46', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(131, 4, 'user_swat131', '$2y$10$vjsTxdTaq2VW1563aDIjTeuSPaathfc.2SByS3oDGWsLeaIbLFtbK', '', 'นางไพรวรรณ  อยู่สุข', '', 'user_swat131@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '178', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(132, 4, 'user_swat132', '$2y$10$avY1Aiv69tVgCxRfq61xs.c9hI9yC6AZL523uOs0CjONXbwYAwuc2', '', 'นายสีทูล  ทุ่มโมง', '', 'user_swat132@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '265', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(133, 4, 'user_swat133', '$2y$10$44vLm8eT4DVgZ/mtZP4t.OT9dPJroUfsYxAZFO8KVvScgeB.oNWOm', '', 'นางสมหมาย พูลสวัสดิ์', '', 'user_swat133@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '225', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(134, 4, 'user_swat134', '$2y$10$h.wT6NGVy6.R0hQOhuBPqekmW3cocBaYookiSht8OEy6fKoXsoCr6', '', 'นางสาวสุวรรณี  จากผา', '', 'user_swat134@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '337', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(135, 4, 'user_swat135', '$2y$10$cV4vRtlm3hrG37kl7mGN4OXarSCZg5RFBy8yqZFmb.vgdqtpqmO.2', '', 'นางรัตน์สุดา  ขานไข', '', 'user_swat135@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '92', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(136, 4, 'user_swat136', '$2y$10$mfDo2u77xuLrpgtcKAgawO3U2RWaBVsKnwkDVxMJYUNQH8y82JCxK', '', 'นางลัดดา  สุระพร', '', 'user_swat136@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '177', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(137, 4, 'user_swat137', '$2y$10$2c8sQI0BOtIE8wBpsP749eJkTfXZOZqDk6QTXMQdapjyzceCBhvJy', '', 'นางอินแปลง จากผา', '', 'user_swat137@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '51', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(138, 4, 'user_swat138', '$2y$10$.DxPJdVLsuj11LI4PxmWsOPhktmdhn3WhXJErEA/bIPm/q5rJutXi', '', 'นายมิตรตรี สมคะเณย์', '', 'user_swat138@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(139, 4, 'user_swat139', '$2y$10$jxJRo.P8zM6.tTRSHQ00iejZRMAtVBoCU7BjUgKMz6RgJ6Hv.XI02', '', 'นางสาวศตพร  ทรงสังขาร', '', 'user_swat139@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-30 19:25:46', NULL, 'active', '2026-10-30 19:25:46', '2026-10-30 19:25:46'),
(140, 4, 'user_swat140', '$2y$10$8Kc29IJ4sb5kHJiFn0XEP.W7KWi4UKWjQJlfBP6H0HnzMSbnvwaNG', '', 'นายชัยพร ลาจำปา', '', 'user_swat140@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '11', '2026-10-30 19:25:47', NULL, 'active', '2026-10-30 19:25:47', '2026-10-30 19:25:47'),
(141, 4, 'user_swat141', '$2y$10$TrB.2U/xhWZB8BfiCX7Ja.SJT8mFH9MAp7zAbZYhz.flfh.NGCqZO', '', 'นายนิยม สัมฤทธิ์', '', 'user_swat141@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '55', '2026-10-30 19:25:47', NULL, 'active', '2026-10-30 19:25:47', '2026-10-30 19:25:47'),
(142, 4, 'user_swat142', '$2y$10$N5M8rbZ4Ug3c9a7GEKA9p.gJKYdySzUz2ma/UQaN0pa6dFkNsS6h6', '', 'นายกรวี ลาสนธิ', '', 'user_swat142@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '392', '2026-10-30 19:25:47', NULL, 'active', '2026-10-30 19:25:47', '2026-10-30 19:25:47'),
(143, 4, 'user_swat143', '$2y$10$0eRR3vF9LR6juTgA4RFyteTMYvAvxX.M3aFzl2MxniAMDoZZ9zgKy', '', 'นายอำนาจ  หินอ่อน', '', 'user_swat143@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '21', '2026-10-30 19:25:47', NULL, 'active', '2026-10-30 19:25:47', '2026-10-30 19:25:47'),
(144, 4, 'user_swat144', '$2y$10$9GfOsIj4IzxyXaVjfr9DsuhtMk1yXgZJN3M/PANtlolgliNpqruBm', '', 'นายปัญญา  เคยสนิท', '', 'user_swat144@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '143', '2026-10-30 19:25:47', NULL, 'active', '2026-10-30 19:25:47', '2026-10-30 19:25:47'),
(145, 4, 'user_swat145', '$2y$10$cYnyDb/BjMGxBw//tD2y.uaxOQJlFvkqFqALx7yuvG5lB6UPBongG', '', 'นายนิยม สัมฤทธิ์ (2)', '', 'user_swat145@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '55', '2026-10-30 19:25:47', NULL, 'active', '2026-10-30 19:25:47', '2026-10-30 19:25:47'),
(146, 4, 'user_swat146', '$2y$10$FVyDvDHhAKEYHgEzu034a..6rqSKLQKPJ3S2PXl3CZ9C2yD/fj6rG', '', 'นางธนพร  เคยสนิท', '', 'user_swat146@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '96', '2026-10-30 19:25:47', NULL, 'active', '2026-10-30 19:25:47', '2026-10-30 19:25:47'),
(147, 4, 'user_swat147', '$2y$10$3oPLupmajqNu9MaFRIH0mOXYPHAXtWLVvILkQUba6eO8m4yU05ENO', '', 'นายประคองรัตน์ จักรสาน', '', 'user_swat147@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '21', '2026-10-30 19:25:47', NULL, 'active', '2026-10-30 19:25:47', '2026-10-30 19:25:47'),
(148, 4, 'user_swat148', '$2y$10$pRcg.mQSlJI5qHnMWQkULuRVQmZAk0.XK2ceI1N3JnIyP8MjSIQK6', '', 'นายภัควัฒน์  เขตต์คำแดง', '', 'user_swat148@gmail.com', 20, 36, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '240', '2026-10-30 19:25:47', NULL, 'active', '2026-10-30 19:25:47', '2026-10-30 19:25:47'),
(149, 4, 'user_swat149', '$2y$10$IFDDvQ9wZjqLVKOitM2z6etpBrujZSHfSQkqa3snN2kYCTXGxYYq2', '', 'นายบุญโฮม แสงคำ', '', 'user_swat149@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '41', '2026-10-30 22:00:02', NULL, 'active', '2026-10-30 22:00:02', '2026-10-30 22:00:02'),
(150, 4, 'user_swat150', '$2y$10$4CicRSxZ0x4m9UfnQKFb3ePsYYkV6k9O582LlFLANQX/Tr9UL.7Wa', '', 'นายอั๋ว ผิวเงิน', '', 'user_swat150@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '103', '2026-10-30 22:00:02', NULL, 'active', '2026-10-30 22:00:02', '2026-10-30 22:00:02'),
(151, 4, 'user_swat151', '$2y$10$A..wVbAqSNNs30ItqISCeuF0Qrc0SzWObpZ0FXWLUT6t7PpRmrAsK', '', 'นายพิทักษ์ อุปชัย', '', 'user_swat151@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '69', '2026-10-30 22:00:02', NULL, 'active', '2026-10-30 22:00:02', '2026-10-30 22:00:02'),
(152, 4, 'user_swat152', '$2y$10$zKZScLJuWKLbknC4MKtsIOwo6DXxpkPlsRdXl2RQ0LeXibix0rCjK', '', 'นายสวน สร้อยแก้ว', '', 'user_swat152@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '21', '2026-10-30 22:00:02', NULL, 'active', '2026-10-30 22:00:02', '2026-10-30 22:00:02'),
(153, 4, 'user_swat153', '$2y$10$JTuDPyBHrKDF6wsOE0pz/.ckJQPa.7ruKLfaqD/ieFIp..Z/JNwWa', '', 'นายสติ ลาบุตร', '', 'user_swat153@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '71', '2026-10-30 22:00:02', NULL, 'active', '2026-10-30 22:00:02', '2026-10-30 22:00:02'),
(154, 4, 'user_swat154', '$2y$10$6FnSTv21ybkrLAbJ8iohreNprLJZO6fhAwkmCas5Sq0OXZa7I3gmu', '', 'นางจำรูญ เรืองทองหลาง', '', 'user_swat154@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '59', '2026-10-30 22:00:02', NULL, 'active', '2026-10-30 22:00:02', '2026-10-30 22:00:02'),
(155, 4, 'user_swat155', '$2y$10$9.waoOQScHSKwOJtmeKUBOnSZm.U/jql3IBBcUefjCXV3u1npC.l.', '', 'นส.กองศรี ภาวะนิตย์', '', 'user_swat155@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '110', '2026-10-30 22:00:02', NULL, 'active', '2026-10-30 22:00:02', '2026-10-30 22:00:02'),
(156, 4, 'user_swat156', '$2y$10$GGW9hw8YNkIBDz25d1V6K.Tj05m4cDCXXOHmZu5nw4I/ISie486Be', '', 'นายฉลอง เนตรวงค์', '', 'user_swat156@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '172', '2026-10-30 22:00:02', NULL, 'active', '2026-10-30 22:00:02', '2026-10-30 22:00:02');
INSERT INTO `users` (`id`, `org_id_fk`, `username`, `password`, `prefix`, `firstname`, `lastname`, `email`, `zone_id`, `subzone_id`, `tambon_code`, `district_code`, `province_code`, `age`, `weight`, `height`, `gender`, `line_id`, `line_user_id`, `id_card`, `phone`, `image`, `address`, `email_verified_at`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(157, 4, 'user_swat157', '$2y$10$gMIcXri8uj3O/NRk7UPNne/hEr.yvu50tOjE2Bz9WcDWKm4.juXeG', '', 'นายบุญเส้ง ลาบุตร', '', 'user_swat157@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '158', '2026-10-30 22:00:02', NULL, 'active', '2026-10-30 22:00:02', '2026-10-30 22:00:02'),
(158, 4, 'user_swat158', '$2y$10$mpZWbI.ieH0gbfwDsH9UGO6oJSRkn0ABMTJoHSuarrWcQQQaD5YPS', '', 'นายอำนวย ชินสิทธ์', '', 'user_swat158@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '134', '2026-10-30 22:00:03', NULL, 'active', '2026-10-30 22:00:03', '2026-10-30 22:00:03'),
(159, 4, 'user_swat159', '$2y$10$5vLoDVcrFof49ReQK7HskO7o8RyvJlHGJrzZDhn.Lj0GQq.zp9b0u', '', 'นางจันทร์เพ็ญ คมกล้า', '', 'user_swat159@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '2', '2026-10-30 22:00:03', NULL, 'active', '2026-10-30 22:00:03', '2026-10-30 22:00:03'),
(160, 4, 'user_swat160', '$2y$10$ycCwhYIptPPAD5IdxQw29urpnsqvxyVWTi0uwyFABFEf2hNyZmDDq', '', 'นางทองสุข เปรยรัตน์', '', 'user_swat160@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '35', '2026-10-30 22:00:03', NULL, 'active', '2026-10-30 22:00:03', '2026-10-30 22:00:03'),
(161, 4, 'user_swat161', '$2y$10$VQlnG.TZbcalNMTChRWkqOrAOG1Mupit6RALW8hIeKnRo6zFwYu7m', '', 'นายสุพล เนตรวงค์', '', 'user_swat161@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '86', '2026-10-30 22:00:03', NULL, 'active', '2026-10-30 22:00:03', '2026-10-30 22:00:03'),
(162, 4, 'user_swat162', '$2y$10$bu3pZJfccVlKvLTT8zLc3.36wVfvhBzfVNDudhCQumXXyzDaFmNt6', '', 'นส.อัมพร โสมาบุตร', '', 'user_swat162@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '76', '2026-10-30 22:00:03', NULL, 'active', '2026-10-30 22:00:03', '2026-10-30 22:00:03'),
(163, 4, 'user_swat163', '$2y$10$apw.IPKG7gujB9K8kRpeHeGK71HJAK3eJ9wZuw5uZ4kDtSXN71kX6', '', 'นายสวน ลาบุตร', '', 'user_swat163@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '140', '2026-10-30 22:00:03', NULL, 'active', '2026-10-30 22:00:03', '2026-10-30 22:00:03'),
(164, 4, 'user_swat164', '$2y$10$QyEmVXnBqVNgDgQavy4wR.ZDjCVmtnxeU88L.GM19BgH5l9Begh4q', '', 'นางสมปอง ภาวะนิตย์', '', 'user_swat164@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '145', '2026-10-30 22:00:03', NULL, 'active', '2026-10-30 22:00:03', '2026-10-30 22:00:03'),
(165, 4, 'user_swat165', '$2y$10$h0N5WqlwdR0Vl2RE9C5BIOrU8p6557UOeJB2vH3MeAGzF9eJrtu/K', '', 'นางเหมือน การินทร์', '', 'user_swat165@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '84', '2026-10-30 22:00:03', NULL, 'active', '2026-10-30 22:00:03', '2026-10-30 22:00:03'),
(166, 4, 'user_swat166', '$2y$10$.UCEQUs2nz29ie.RxfFX9.fh7can92Q1NHz47hJvUTfMjAFxn/uE2', '', 'นายเฉลียว อรพรม', '', 'user_swat166@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '102', '2026-10-30 22:00:03', NULL, 'active', '2026-10-30 22:00:03', '2026-10-30 22:00:03'),
(167, 4, 'user_swat167', '$2y$10$Ic90N3k8nH7d1IXNZUyh2.yMuIyg8CmFlpmE8YXiJCiKXXssHtUpi', '', 'นางหนูพัฒน์ จารุจิต', '', 'user_swat167@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '93', '2026-10-30 22:00:04', NULL, 'active', '2026-10-30 22:00:04', '2026-10-30 22:00:04'),
(168, 4, 'user_swat168', '$2y$10$JNmdoi4OYHbTkytO4iO.PeHixIbk85Mlv0ZIbfRA9/KzQSXLelw/y', '', 'นางสมหมาย สุวรรณศรี', '', 'user_swat168@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '73', '2026-10-30 22:00:04', NULL, 'active', '2026-10-30 22:00:04', '2026-10-30 22:00:04'),
(169, 4, 'user_swat169', '$2y$10$cMQXc//QfT0j3mKCvrzDGu7uPvE0fsDT.6bpGVX6Za0repaYBN0QK', '', 'นางสุวรรณ เนตรวงค์', '', 'user_swat169@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '48', '2026-10-30 22:00:04', NULL, 'active', '2026-10-30 22:00:04', '2026-10-30 22:00:04'),
(170, 4, 'user_swat170', '$2y$10$rgeBRB0zAHXpSVo1QZRHq.BhfXgu1b4XHtNHRIoYmckxUp7m/zuI6', '', 'นางวิเชียร ทองจันทร์', '', 'user_swat170@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '129', '2026-10-30 22:00:04', NULL, 'active', '2026-10-30 22:00:04', '2026-10-30 22:00:04'),
(171, 4, 'user_swat171', '$2y$10$DKQI.0nmWzyKH3vWt36XoOYNN627UMTsDsqpqmipLzRConnS.MKhm', '', 'นางวีระกุล วงค์หาพิมพ์', '', 'user_swat171@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '148', '2026-10-30 22:00:04', NULL, 'active', '2026-10-30 22:00:04', '2026-10-30 22:00:04'),
(172, 4, 'user_swat172', '$2y$10$76PlPBsPGvsOKGxtLENEtO8g.JILkGF2EKTW46W2j8UjNT0npwQt.', '', 'นายสำรวย สร้อยแก้ว', '', 'user_swat172@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '101', '2026-10-30 22:00:04', NULL, 'active', '2026-10-30 22:00:04', '2026-10-30 22:00:04'),
(173, 4, 'user_swat173', '$2y$10$0.qaA/22pzIo7nxJqixDzum3JBo7HrLoHJGSJg63o9UuFMd8YGVw6', '', 'นางผัน ศรีสมยา', '', 'user_swat173@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '94', '2026-10-30 22:00:04', NULL, 'active', '2026-10-30 22:00:04', '2026-10-30 22:00:04'),
(174, 4, 'user_swat174', '$2y$10$yvDDJvQNX7PS07Ukx4AZyOBsUrSXQOpZimFKsxI0nDYHmmtD016SK', '', 'นายคำพลู ธนาชาติ', '', 'user_swat174@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '46', '2026-10-30 22:00:04', NULL, 'active', '2026-10-30 22:00:04', '2026-10-30 22:00:04'),
(175, 4, 'user_swat175', '$2y$10$SoI8uvTdAlIWphhlE.pXTe2MuyC1uWD3pmSxcCyJZOEg9Io0b19bm', '', 'นายบุญมี บุญกาญจน์', '', 'user_swat175@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '154', '2026-10-30 22:00:04', NULL, 'active', '2026-10-30 22:00:04', '2026-10-30 22:00:04'),
(176, 4, 'user_swat176', '$2y$10$aF0gjTe.snOxvRBLcYz2IO8Ho8eSyHaKophGF5tcLOhUO1S.j0gBK', '', 'นายรั้ว ผิวเงิน', '', 'user_swat176@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '4', '2026-10-30 22:00:04', NULL, 'active', '2026-10-30 22:00:04', '2026-10-30 22:00:04'),
(177, 4, 'user_swat177', '$2y$10$Sr8svHs8xA5Xh47UFTVnGeM0Vlqo14GoGTWE/NEY/Og/NOvk4dkQC', '', 'นางอารี ผิวเงิน', '', 'user_swat177@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '165', '2026-10-30 22:00:05', NULL, 'active', '2026-10-30 22:00:05', '2026-10-30 22:00:05'),
(178, 4, 'user_swat178', '$2y$10$V7e3HHA92c.tRTZH8VjGGuDQ3.unu9g9lJxfk5VIh88o9YlPErNQC', '', 'นายสุรศักดิ์ จารุจิต', '', 'user_swat178@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '60', '2026-10-30 22:00:05', NULL, 'active', '2026-10-30 22:00:05', '2026-10-30 22:00:05'),
(179, 4, 'user_swat179', '$2y$10$/ViAfZJCMfXXqhPZ8DTTPOEB4KbP1RFjA.siimqWaDJLLGr8ydFu6', '', 'นายจำปี บัวเขียว', '', 'user_swat179@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '97', '2026-10-30 22:00:05', NULL, 'active', '2026-10-30 22:00:05', '2026-10-30 22:00:05'),
(180, 4, 'user_swat180', '$2y$10$3ay7XDnMR.m6TxtRw0u83ORhZ4uNytr4GE2WuPjulram.q1lWXI5W', '', 'นางจันที สลับศรี', '', 'user_swat180@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '67', '2026-10-30 22:00:05', NULL, 'active', '2026-10-30 22:00:05', '2026-10-30 22:00:05'),
(181, 4, 'user_swat181', '$2y$10$6gGv8sp78tNZCwlzMNAYuORDMSku9sHlVHy77Mym9MVdGR44HXG/u', '', 'นางนวลลออง หันตุลา', '', 'user_swat181@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '147', '2026-10-30 22:00:05', NULL, 'active', '2026-10-30 22:00:05', '2026-10-30 22:00:05'),
(182, 4, 'user_swat182', '$2y$10$hc2sndt679bDnXesxzoCRe1pZVvRAVCVGDCrwAPwb9p/5UkuOk.kW', '', 'นายถวิล อุนาพันธ์', '', 'user_swat182@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '123', '2026-10-30 22:00:05', NULL, 'active', '2026-10-30 22:00:05', '2026-10-30 22:00:05'),
(183, 4, 'user_swat183', '$2y$10$gWm6xD5wkCkRmNq7CMzDX.xcw1nPRPtoYaQcy/WrscP0qn2k6H1ua', '', 'นางประคอง อุนาพันธ์', '', 'user_swat183@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '34', '2026-10-30 22:00:05', NULL, 'active', '2026-10-30 22:00:05', '2026-10-30 22:00:05'),
(184, 4, 'user_swat184', '$2y$10$LMLojyg04le/E5YrdZ/eoOr8NPiVPswl7Ljzs5MAhucG2Uv592gz6', '', 'นางบุญมา เพ็ญเนตร', '', 'user_swat184@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '105', '2026-10-30 22:00:05', NULL, 'active', '2026-10-30 22:00:05', '2026-10-30 22:00:05'),
(185, 4, 'user_swat185', '$2y$10$QQcU./2jHXdNfHHh4wXA1Oq0mFgPjBrzP.BtdXwP0pBKmazIN/U6C', '', 'นางจิรัฐติกาล เนตรวงค์', '', 'user_swat185@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '235', '2026-10-30 22:00:05', NULL, 'active', '2026-10-30 22:00:05', '2026-10-30 22:00:05'),
(186, 4, 'user_swat186', '$2y$10$wu/9Tkqr1oPO9Hgqory4P.pq87HqEguz1WyOXZaY4BAYm.k5V/XA.', '', 'นายชูวง อินอุ่นโชติ', '', 'user_swat186@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '64', '2026-10-30 22:00:06', NULL, 'active', '2026-10-30 22:00:06', '2026-10-30 22:00:06'),
(187, 4, 'user_swat187', '$2y$10$LEn4S.XklIPxFxSow8sbjubU4VxJqZmktp1ycQFZST7H7Qf5i2LM6', '', 'นางบานชื่น บุญจันทร์ดา', '', 'user_swat187@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '141', '2026-10-30 22:00:06', NULL, 'active', '2026-10-30 22:00:06', '2026-10-30 22:00:06'),
(188, 4, 'user_swat188', '$2y$10$Nm3AgESxjIi4L88inCcYUOwYApIxp4ImFxqSRkKfWzEbIa32P3xPe', '', 'นางประยูร ภักดียุทธ์', '', 'user_swat188@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '82', '2026-10-30 22:00:06', NULL, 'active', '2026-10-30 22:00:06', '2026-10-30 22:00:06'),
(189, 4, 'user_swat189', '$2y$10$Wf/FUUf5NnJz6Df5aDFZBuBvJipthF3UXfj1AntrTA9sn4wkLbwpS', '', 'นายจำปา บุญทวี', '', 'user_swat189@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '89', '2026-10-30 22:00:06', NULL, 'active', '2026-10-30 22:00:06', '2026-10-30 22:00:06'),
(190, 4, 'user_swat190', '$2y$10$u8.vcpHK7mFWVNqlzY6mne4Ow2vp7d22J6moG2H5ix59oxTcPuXh2', '', 'นางสมร ขุนทอง 1995', '', 'user_swat190@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '128', '2026-10-30 22:00:06', NULL, 'active', '2026-10-30 22:00:06', '2026-10-30 22:00:06'),
(191, 4, 'user_swat191', '$2y$10$2XIG3xSRUAtKd1PIhiYkOOtAsX1lU/p13vNhu0RMMaGFssmUn6.Q.', '', 'นางไพวัลย์ มุขยงค์', '', 'user_swat191@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '106', '2026-10-30 22:00:06', NULL, 'active', '2026-10-30 22:00:06', '2026-10-30 22:00:06'),
(192, 4, 'user_swat192', '$2y$10$NCq....yAghvN9Mm0N.sJ./3yILNr9thhB.MoVbNBTPJbYloHjtC6', '', 'นายพิเชฐ แสงคำ', '', 'user_swat192@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '162', '2026-10-30 22:00:06', NULL, 'active', '2026-10-30 22:00:06', '2026-10-30 22:00:06'),
(193, 4, 'user_swat193', '$2y$10$9rxafWr6inZv9FoBmi8SiOCRkI4.OHZoolHVKKZUXSfgzw0srZa16', '', 'นางสาวอ่อนสา นามษร', '', 'user_swat193@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '42', '2026-10-30 22:00:06', NULL, 'active', '2026-10-30 22:00:06', '2026-10-30 22:00:06'),
(194, 4, 'user_swat194', '$2y$10$Ed7ZQ0r5w1u6DQxEOT83YuL6BhPAkzWqlDarspl5IQmSBVl0hdoMO', '', 'นายเคน ป้องคำสิงห์', '', 'user_swat194@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '32', '2026-10-30 22:00:06', NULL, 'active', '2026-10-30 22:00:06', '2026-10-30 22:00:06'),
(195, 4, 'user_swat195', '$2y$10$CAeGC4zjFCWFhiRTZyOnw.2VP/Q4sSxG9UVV8df9QMFmvxfC9WEh6', '', 'นายทวี เนตรวงศ์', '', 'user_swat195@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '53', '2026-10-30 22:00:06', NULL, 'active', '2026-10-30 22:00:06', '2026-10-30 22:00:06'),
(196, 4, 'user_swat196', '$2y$10$8OX6mSNhjljlm6U.M1ZOFeI4Qv01oy44Q9/5Ijo/MQQhds5jp3ZYy', '', 'นางนวลศรี สุวะพันธ์', '', 'user_swat196@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '121', '2026-10-30 22:00:07', NULL, 'active', '2026-10-30 22:00:07', '2026-10-30 22:00:07'),
(197, 4, 'user_swat197', '$2y$10$gYLSUF2CY3ui.nHbOiINAehnciZN2r4HWhUvBh8Pt6RUWv85zdCwO', '', 'นางรำพรรณ ทานะปัทม์', '', 'user_swat197@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '3', '2026-10-30 22:00:07', NULL, 'active', '2026-10-30 22:00:07', '2026-10-30 22:00:07'),
(198, 4, 'user_swat198', '$2y$10$/aFSQNWqfqH/QQ8De7vtPORkwZggSh.RU4ABdRj.ctMQ85xmb9KbK', '', 'นางอภัย ผ่านเมือง', '', 'user_swat198@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '14', '2026-10-30 22:00:07', NULL, 'active', '2026-10-30 22:00:07', '2026-10-30 22:00:07'),
(199, 4, 'user_swat199', '$2y$10$wDx4zogWxB7/mYNrlVWPCuJTZtYayIO8hUIVuq0HFagbQJNyqFCh6', '', 'นางรำพรรณ ทองนาค', '', 'user_swat199@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '79', '2026-10-30 22:00:07', NULL, 'active', '2026-10-30 22:00:07', '2026-10-30 22:00:07'),
(200, 4, 'user_swat200', '$2y$10$/OlkGaTIfbWLeL0lv17haOxkO7nN74LVvPZPvlZ3vK.PpEOWhVSyK', '', 'นางคำพันธ์ สร้อยแก้ว', '', 'user_swat200@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '109', '2026-10-30 22:00:07', NULL, 'active', '2026-10-30 22:00:07', '2026-10-30 22:00:07'),
(201, 4, 'user_swat201', '$2y$10$6Np68wJO0BQcV5DZ.SN1KOQ/YavXLbu/k06PTj8I/.xSY0Fsk4YYa', '', 'นางหนูศิลป์ ลุนบุตร', '', 'user_swat201@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '88', '2026-10-30 22:00:07', NULL, 'active', '2026-10-30 22:00:07', '2026-10-30 22:00:07'),
(202, 4, 'user_swat202', '$2y$10$gYP8Cxm2YuO2QMzv1MpIxOk0ttH7nZPU6/0V.GEvPgoDmE4P.W.mK', '', 'นางเกิ่ง  สร้อยแก้ว', '', 'user_swat202@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '87', '2026-10-30 22:00:07', NULL, 'active', '2026-10-30 22:00:07', '2026-10-30 22:00:07'),
(203, 4, 'user_swat203', '$2y$10$KoGxvkZHuu18lt4VcORlw.kHlDeTUEy.p0w/66sjg.G1fDugBqJ6y', '', 'น.ส.อทิตติยา งามวงค์', '', 'user_swat203@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '209', '2026-10-30 22:00:07', NULL, 'active', '2026-10-30 22:00:07', '2026-10-30 22:00:07'),
(204, 4, 'user_swat204', '$2y$10$0tUrD4Z0vd0Y7AggqEm1f.J2dyGELmxFkyQFaydzKsyKsdmSuXWoq', '', 'นายสุด สุวะพันธ์', '', 'user_swat204@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '65', '2026-10-30 22:00:07', NULL, 'active', '2026-10-30 22:00:07', '2026-10-30 22:00:07'),
(205, 4, 'user_swat205', '$2y$10$vlqsyCr6zWKfuU/itTJuouxGiMaKpuPOxvTU8jXDhNWSbsDYu0Z/2', '', 'นายบุญเย็น เนตรวงศ์', '', 'user_swat205@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '57', '2026-10-30 22:00:08', NULL, 'active', '2026-10-30 22:00:08', '2026-10-30 22:00:08'),
(206, 4, 'user_swat206', '$2y$10$4suEV.M/oHRRoHpJ9qyou.xxmFfpmd0xs6wMr1DBB3SJOzXMSfe.O', '', 'นางสำราญ นิติสุข', '', 'user_swat206@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '127', '2026-10-30 22:00:08', NULL, 'active', '2026-10-30 22:00:08', '2026-10-30 22:00:08'),
(207, 4, 'user_swat207', '$2y$10$AgBR6Qw3Ee.CwutF9KfWUOiu8jE6Oe7TyPTkS/IyCpp.mEJ9qYBum', '', 'นางทองลา สังเกต', '', 'user_swat207@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '51', '2026-10-30 22:00:08', NULL, 'active', '2026-10-30 22:00:08', '2026-10-30 22:00:08'),
(208, 4, 'user_swat208', '$2y$10$FBTd5Ldzhabq3X7XZmEYKehRHPNgY8Grvl4f6mmUaV56kpRIZ5hWG', '', 'นายสุรชาติ ไชยสัจ', '', 'user_swat208@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '68', '2026-10-30 22:00:08', NULL, 'active', '2026-10-30 22:00:08', '2026-10-30 22:00:08'),
(209, 4, 'user_swat209', '$2y$10$Tgg/a4/tVOYmwQFFDuob6enDWkL9l4dCK7jr573AJgQJ9rBhI.8CC', '', 'นายพรมมา โพธิ์มาด', '', 'user_swat209@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '33', '2026-10-30 22:00:08', NULL, 'active', '2026-10-30 22:00:08', '2026-10-30 22:00:08'),
(210, 4, 'user_swat210', '$2y$10$DB7QeyWxIms8OaJZK1O39un.DYlF2.DEie5vy/GkAoTrls.C6d.mG', '', 'นายสง่า มุละชีวะ', '', 'user_swat210@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '112', '2026-10-30 22:00:08', NULL, 'active', '2026-10-30 22:00:08', '2026-10-30 22:00:08'),
(211, 4, 'user_swat211', '$2y$10$umjbDNZocX6XMc7rJm97HOcRgMc17IK8rf7ag5tY9x1MZmhD2OtA2', '', 'นายศิลป์พิทักษ์ สุวรรณเพชร', '', 'user_swat211@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '45', '2026-10-30 22:00:08', NULL, 'active', '2026-10-30 22:00:08', '2026-10-30 22:00:08'),
(212, 4, 'user_swat212', '$2y$10$ccAoBptlYXBwOSFnZz2DZej6/tgnx8Zin7g64aS.4DcRGdgsih9Hq', '', 'นางสมคิด บุญทวี', '', 'user_swat212@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '115', '2026-10-30 22:00:08', NULL, 'active', '2026-10-30 22:00:08', '2026-10-30 22:00:08'),
(213, 4, 'user_swat213', '$2y$10$T2r/Lg2oAVfZe9OlCa1Mwe7CGOJfxXAMAFwxnVY1eaGOvUzVaNvTW', '', 'นางสมหมาย แสงคำ', '', 'user_swat213@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '25', '2026-10-30 22:00:08', NULL, 'active', '2026-10-30 22:00:08', '2026-10-30 22:00:08'),
(214, 4, 'user_swat214', '$2y$10$H.BA0wtXtQ2ofzmRZ0IKzep73fKeKsHmb3OO65OWFJV62BgoW8j2a', '', 'นางกัน ผิวเงิน', '', 'user_swat214@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '37', '2026-10-30 22:00:08', NULL, 'active', '2026-10-30 22:00:08', '2026-10-30 22:00:08'),
(215, 4, 'user_swat215', '$2y$10$9EnVM1I3naJCyeT2..D5serazS9llEJt5NAnMtcKKOWBOr9yokG1O', '', 'นายกุหลาบ พันธ์คำ', '', 'user_swat215@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '122', '2026-10-30 22:00:09', NULL, 'active', '2026-10-30 22:00:09', '2026-10-30 22:00:09'),
(216, 4, 'user_swat216', '$2y$10$G.2FVZ0xkr4LYW8UUgqLquqpF.FHMn3I75B9v.uImQKGDXi6ycOea', '', 'น.ส.พันธ์จันท โสมาบุตร', '', 'user_swat216@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '7', '2026-10-30 22:00:09', NULL, 'active', '2026-10-30 22:00:09', '2026-10-30 22:00:09'),
(217, 4, 'user_swat217', '$2y$10$2LKdqVhp875CSOnOlHh50.F2ddlH8VNzsZTYDEAM5ZthNYwhtHAyK', '', 'นางทองจันทร์ เนตรวงค์', '', 'user_swat217@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '72', '2026-10-30 22:00:09', NULL, 'active', '2026-10-30 22:00:09', '2026-10-30 22:00:09'),
(218, 4, 'user_swat218', '$2y$10$Yb.9rsKsbCrI1mhfXzDSYOTdtnSPCrEkYOMgVAtXLAbEFJ71RbJtm', '', 'นายทองจันทร์ ขุนทอง', '', 'user_swat218@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '18', '2026-10-30 22:00:09', NULL, 'active', '2026-10-30 22:00:09', '2026-10-30 22:00:09'),
(219, 4, 'user_swat219', '$2y$10$8HIIhAbR3nV9YywId6TcYugz4G1/Rggi7zTkKfpJCtewV.uFAw7/a', '', 'นางยัง โสมาบุตร', '', 'user_swat219@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '121', '2026-10-30 22:00:09', NULL, 'active', '2026-10-30 22:00:09', '2026-10-30 22:00:09'),
(220, 4, 'user_swat220', '$2y$10$VIMcrc6OZDLpPTbaEjEXleoV6wRRqDVZnKVRj9gjLOSvjrxfehJ0S', '', 'นายสันติสุข ปากหวาน', '', 'user_swat220@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '136', '2026-10-30 22:00:09', NULL, 'active', '2026-10-30 22:00:09', '2026-10-30 22:00:09'),
(221, 4, 'user_swat221', '$2y$10$HLbaVwQYbOjfnEvE21WsQOuHRDreuA74.3caA/Hk9yLLHddlM2S8e', '', 'นางบัวลม เจริญบุญ', '', 'user_swat221@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '96', '2026-10-30 22:00:09', NULL, 'active', '2026-10-30 22:00:09', '2026-10-30 22:00:09'),
(222, 4, 'user_swat222', '$2y$10$iLrpYMQHNPvn4k9hBWffQezFReRX8rmh0D2vxTkJ1EZIKBw3H8.MO', '', 'นายประเสริญ สวัสดิรัตน์(1)', '', 'user_swat222@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '22', '2026-10-30 22:00:09', NULL, 'active', '2026-10-30 22:00:09', '2026-10-30 22:00:09'),
(223, 4, 'user_swat223', '$2y$10$LsJyhbgqmZP57fT3BUxkLeeZYfKfjmlYmx92cl6Nz./1mXICy6rQi', '', 'นายประเสริญ สวัสดิรัตน์(2)', '', 'user_swat223@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '22', '2026-10-30 22:00:09', NULL, 'active', '2026-10-30 22:00:09', '2026-10-30 22:00:09'),
(224, 4, 'user_swat224', '$2y$10$5yu4u47ZjWOxREvrRb2MhO4AMEsgnjHSMPqgqzjxYoaI.RalPGuCC', '', 'นายถาวร ทาดาวุช(1)', '', 'user_swat224@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '75', '2026-10-30 22:00:09', NULL, 'active', '2026-10-30 22:00:09', '2026-10-30 22:00:09'),
(225, 4, 'user_swat225', '$2y$10$t1G3byAiXU6Hw02luIfuQuoH4pgM83vBlp7jEI4broW4JLoAMuBVy', '', 'นายประนม มลศิลป์(1)', '', 'user_swat225@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '36', '2026-10-30 22:00:10', NULL, 'active', '2026-10-30 22:00:10', '2026-10-30 22:00:10'),
(226, 4, 'user_swat226', '$2y$10$QCG54C7257yVJJzSuBEjou0ubAProjqWpPAWiBcykDOCUIpk1gDRe', '', 'นางนาง อักษรพิมพ์(1)', '', 'user_swat226@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '10', '2026-10-30 22:00:10', NULL, 'active', '2026-10-30 22:00:10', '2026-10-30 22:00:10'),
(227, 4, 'user_swat227', '$2y$10$Wz2pIG5FuiZKRLF5H3nJvuR0hGm2ckiQInfAZ9eQ8oGUZew/JLmd6', '', 'นางมลที อุปชัย', '', 'user_swat227@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '69', '2026-10-30 22:00:10', NULL, 'active', '2026-10-30 22:00:10', '2026-10-30 22:00:10'),
(228, 4, 'user_swat228', '$2y$10$d7yYcNPoJB3SgJFaeAPqt.cKRSyH2hQ/1ALhUsjHC/bEAZET04ahO', '', 'นางธงชัย วงค์เพ็ญ(1)', '', 'user_swat228@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '5', '2026-10-30 22:00:10', NULL, 'active', '2026-10-30 22:00:10', '2026-10-30 22:00:10'),
(229, 4, 'user_swat229', '$2y$10$FlEFlky7wLdp46yq7YgBxOsHhhAX4se1mcvtZSSqKf2aLp/s6KfzK', '', 'นายประสงค์ สร้อยแก้ว', '', 'user_swat229@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '70', '2026-10-30 22:00:10', NULL, 'active', '2026-10-30 22:00:10', '2026-10-30 22:00:10'),
(230, 4, 'user_swat230', '$2y$10$w.bQxlV3qpxotvaH0QejwuRzeVEV0XyB.rPitwT.LRltJ3OH6xFxW', '', 'นางพินิต ปากหวาน(บุญทิพย์)', '', 'user_swat230@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '16', '2026-10-30 22:00:10', NULL, 'active', '2026-10-30 22:00:10', '2026-10-30 22:00:10'),
(231, 4, 'user_swat231', '$2y$10$8YH0IjyW1aADYWA3Wd21suQYiljWs1VyMAKvy.lluCEyatPbWnaU2', '', 'นางอินอร ทากุ', '', 'user_swat231@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '135', '2026-10-30 22:00:10', NULL, 'active', '2026-10-30 22:00:10', '2026-10-30 22:00:10'),
(232, 4, 'user_swat232', '$2y$10$1aDqky.BWKkzW73vJac6U.XfuvlXUVk634jjqAeOsCweY5lCeVNl2', '', 'นางยุภาพรณ์ อรกุล', '', 'user_swat232@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '251', '2026-10-30 22:00:10', NULL, 'active', '2026-10-30 22:00:10', '2026-10-30 22:00:10'),
(233, 4, 'user_swat233', '$2y$10$s.tylHkcMizYUM9Id5rW/uMk8BBf.P5Uqo1HMwCXyW9h11CGcM7/O', '', 'นางสมัน โพธิ์พาธ', '', 'user_swat233@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '250', '2026-10-30 22:00:10', NULL, 'active', '2026-10-30 22:00:10', '2026-10-30 22:00:10'),
(234, 4, 'user_swat234', '$2y$10$GjAFLhirG9e7GIP/7BPhCerT6kRnTG8hETiB9WyinvU4.iPiLk0FG', '', 'นายเฉลิมชัย สุวะพันธ์', '', 'user_swat234@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '104', '2026-10-30 22:00:11', NULL, 'active', '2026-10-30 22:00:11', '2026-10-30 22:00:11'),
(235, 4, 'user_swat235', '$2y$10$U./.5mWgjhKRK7CjyUQ54OMTIUxgRyN3/nDew/fjBHvbEiq2x46fG', '', 'นางอุดม โคตรชุม(2)', '', 'user_swat235@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '83', '2026-10-30 22:00:11', NULL, 'active', '2026-10-30 22:00:11', '2026-10-30 22:00:11'),
(236, 4, 'user_swat236', '$2y$10$9PJ.QfbFPDN7PIrMQQMpOeBWkMXDzrIY5KxnznU4KgwKT02gOng.y', '', 'นางนฤมล นวลเพ็ญ', '', 'user_swat236@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '183', '2026-10-30 22:00:11', NULL, 'active', '2026-10-30 22:00:11', '2026-10-30 22:00:11'),
(237, 4, 'user_swat237', '$2y$10$84EhU4Uu3WP7HtaXmZy/Z.Z3gb0y539B6NTEqc1L59oIlKyMjBcDa', '', 'นางวิไลภรณ์ ยอดสง่า', '', 'user_swat237@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '229', '2026-10-30 22:00:11', NULL, 'active', '2026-10-30 22:00:11', '2026-10-30 22:00:11'),
(238, 4, 'user_swat238', '$2y$10$EnSOxfMJ56pEd7PEwc9diOPiCiR7FV5IABE7H5FdRvulPCBn0D5bW', '', 'นางนาง อักษรพิมพ์(2)', '', 'user_swat238@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '10', '2026-10-30 22:00:11', NULL, 'active', '2026-10-30 22:00:11', '2026-10-30 22:00:11'),
(239, 4, 'user_swat239', '$2y$10$vZybDuuvMeSK6WG0rxVjcurDFSnvGiCxvt2NOuBNjWybV.hldprR2', '', 'นายเฉลิมชัย สุวะพันธ์(2)', '', 'user_swat239@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '104', '2026-10-30 22:00:11', NULL, 'active', '2026-10-30 22:00:11', '2026-10-30 22:00:11'),
(240, 4, 'user_swat240', '$2y$10$FzhJNNwm6/vrH2qxMNam..gbeiar4VP1Oz8VV6X2xH42vKCsORWuS', '', 'นายปรีชา ศรีสมยา', '', 'user_swat240@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '63', '2026-10-30 22:00:11', NULL, 'active', '2026-10-30 22:00:11', '2026-10-30 22:00:11'),
(241, 4, 'user_swat241', '$2y$10$S.appdyHRneMLzVw.hLzp.aa8bpt88IF73vHHMTaxkcWliYRIDaOe', '', 'นางธงชัย วงศ์เพ็ญ(2)', '', 'user_swat241@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '5', '2026-10-30 22:00:11', NULL, 'active', '2026-10-30 22:00:11', '2026-10-30 22:00:11'),
(242, 4, 'user_swat242', '$2y$10$n5gxPqZ57CwdMhEg0DhiAOkaftFZ92PUVjNCZctxKpnWm7TrozXi.', '', 'นายสมนึก  โพธิพันธ์', '', 'user_swat242@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '46', '2026-10-30 22:00:11', NULL, 'active', '2026-10-30 22:00:11', '2026-10-30 22:00:11'),
(243, 4, 'user_swat243', '$2y$10$vWD11ahxDA/.h20hLGlaCOv8FP00C7JBVyz3cqOfABbn0PaLmFzfS', '', 'นางมาลัย เปรยรัตน์', '', 'user_swat243@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '205', '2026-10-30 22:00:12', NULL, 'active', '2026-10-30 22:00:12', '2026-10-30 22:00:12'),
(244, 4, 'user_swat244', '$2y$10$Ux./Dzel9N6pxOwL7ZnxtOXTLNwmWW3vamF97DFJjZZGX4PhwL0eC', '', 'นายสุวรรณ ถาวร**', '', 'user_swat244@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '6', '2026-10-30 22:00:12', NULL, 'active', '2026-10-30 22:00:12', '2026-10-30 22:00:12'),
(245, 4, 'user_swat245', '$2y$10$lvLnIxOYzqMvW11E7RXOR.aWtGod6x61FYQmNZcAz7.exjPgeIcQq', '', 'นางคำแปลง อาจวิชัย', '', 'user_swat245@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '47', '2026-10-30 22:00:12', NULL, 'active', '2026-10-30 22:00:12', '2026-10-30 22:00:12'),
(246, 4, 'user_swat246', '$2y$10$UoVVj0X1LNG130CQ67YJ0u18pVs0R4hTw45AdZRpRKCiPV9WbeHIy', '', 'นายคำแสน บูญการจน์', '', 'user_swat246@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '91', '2026-10-30 22:00:12', NULL, 'active', '2026-10-30 22:00:12', '2026-10-30 22:00:12'),
(247, 4, 'user_swat247', '$2y$10$jgR.0Db/LYVxTQO6QbuvruD16E.jlrM48267xBu0jDEbB8ocqEMxG', '', 'นางลำใย บุญจันทร์', '', 'user_swat247@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '131', '2026-10-30 22:00:12', NULL, 'active', '2026-10-30 22:00:12', '2026-10-30 22:00:12'),
(248, 4, 'user_swat248', '$2y$10$YQA90Jo140JaWReamYZMIOH9MaAdLYj1E5lCWz1rKq5DUAKqQQ13q', '', 'นางสาวพิมพกานต์ อุสาหะ', '', 'user_swat248@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '166', '2026-10-30 22:00:12', NULL, 'active', '2026-10-30 22:00:12', '2026-10-30 22:00:12'),
(249, 4, 'user_swat249', '$2y$10$eaWXeVdbyvHqv3wxt1GUfe0OSyXcRECJWtElUl3vs4nXEO8tkO9My', '', 'นางยุพา แสงคำ', '', 'user_swat249@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '209', '2026-10-30 22:00:12', NULL, 'active', '2026-10-30 22:00:12', '2026-10-30 22:00:12'),
(250, 4, 'user_swat250', '$2y$10$GEAstkHTQ4JeVYcTdXc.feBK5vnhZa6UruB7D03AUWo4kWTs3kdzO', '', 'นายทองใส ดีดวงพันธ์', '', 'user_swat250@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '50', '2026-10-30 22:00:12', NULL, 'active', '2026-10-30 22:00:12', '2026-10-30 22:00:12'),
(251, 4, 'user_swat251', '$2y$10$vjPJV7BPY8HyHijIaEfEiuoB.BCVn3TR2duHBEU04Im9Maqh5nVq.', '', 'นายทองศรี ศรีมาตร', '', 'user_swat251@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '131', '2026-10-30 22:00:12', NULL, 'active', '2026-10-30 22:00:12', '2026-10-30 22:00:12'),
(252, 4, 'user_swat252', '$2y$10$ZiJPfQXenZinOAXUtW/J4OZPwA6AAVR/rDtSHG0mKoZlX/flIA2I.', '', 'นายกาวี บุญจันทร์', '', 'user_swat252@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '52', '2026-10-30 22:00:12', NULL, 'active', '2026-10-30 22:00:12', '2026-10-30 22:00:12'),
(253, 4, 'user_swat253', '$2y$10$nqrOObx9XsL4FYpvycmHDOKAkZtL5.kcpSDVWjMu6EKR8YyrJPKRe', '', 'นายบุญสำ แสงคำ', '', 'user_swat253@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '19', '2026-10-30 22:00:13', NULL, 'active', '2026-10-30 22:00:13', '2026-10-30 22:00:13'),
(254, 4, 'user_swat254', '$2y$10$df5SbtJp3CB.TOmbMRjNuuxvmsmmyMohZeGU1FGIl9J4iDLfsaXYK', '', 'นางนารี แสงคำ', '', 'user_swat254@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '121', '2026-10-30 22:00:13', NULL, 'active', '2026-10-30 22:00:13', '2026-10-30 22:00:13'),
(255, 4, 'user_swat255', '$2y$10$gm2f6S9wdp2wQUT/76KTQ.SSCMfSvBe2rsfjA3xaiI541srA4HsdC', '', 'วัดบ้านนาจาน (2)', '', 'user_swat255@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '116', '2026-10-30 22:00:13', NULL, 'active', '2026-10-30 22:00:13', '2026-10-30 22:00:13'),
(256, 4, 'user_swat256', '$2y$10$Wf4/u6v5Ef762hF.UdP02eS5f/mQZUaHrwK.eUN4XA0ff/4ppaziG', '', 'นางวิไล สุวะพันธ์', '', 'user_swat256@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '126', '2026-10-30 22:00:13', NULL, 'active', '2026-10-30 22:00:13', '2026-10-30 22:00:13'),
(257, 4, 'user_swat257', '$2y$10$px5TGSNpVX1XmjkQnpSR0ev5so2ssqNNGbI28VchV5enCA8bNADy.', '', 'นางมุกดา ขุนทอง', '', 'user_swat257@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '146', '2026-10-30 22:00:13', NULL, 'active', '2026-10-30 22:00:13', '2026-10-30 22:00:13'),
(258, 4, 'user_swat258', '$2y$10$rxuwgR1R7EoAA4xIfhy6U.fzIPrVdro2xAGyG6Gw9z9gKuqmCWiPe', '', 'นางประไพ กาญจนีย์', '', 'user_swat258@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '81', '2026-10-30 22:00:13', NULL, 'active', '2026-10-30 22:00:13', '2026-10-30 22:00:13'),
(259, 4, 'user_swat259', '$2y$10$Kg6t4YebaWf./AoDPRXcK.5BbcMjqS77mZTOY82zUCOnd97vbLZle', '', 'นายบุญเลียบ สร้อยแก้ว', '', 'user_swat259@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '12', '2026-10-30 22:00:13', NULL, 'active', '2026-10-30 22:00:13', '2026-10-30 22:00:13'),
(260, 4, 'user_swat260', '$2y$10$ltX52ZA.82sbH6SWJbRnL.tBi/aNNsTJtov3WIZ8QF8wSnM9obD0.', '', 'นางคำไข อรวงษ์', '', 'user_swat260@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '113', '2026-10-30 22:00:13', NULL, 'active', '2026-10-30 22:00:13', '2026-10-30 22:00:13'),
(261, 4, 'user_swat261', '$2y$10$ggIj7oe.C.i.BqkV/AG6fO4G36bWP.oCPXMm5iNVXMxlqwsbkiIWW', '', 'นางสินชัย ทาดาวุธ', '', 'user_swat261@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '111', '2026-10-30 22:00:13', NULL, 'active', '2026-10-30 22:00:13', '2026-10-30 22:00:13'),
(262, 4, 'user_swat262', '$2y$10$eRnBP3TarplFw3oYJawLRuquPOGosgld7WpYCv844D/48uwHehBqq', '', 'นายอ๋อง หวังผล', '', 'user_swat262@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '9', '2026-10-30 22:00:14', NULL, 'active', '2026-10-30 22:00:14', '2026-10-30 22:00:14'),
(263, 4, 'user_swat263', '$2y$10$TReRjq7dtWQTh2hMsQNwFehR7n5vWm5m4sKOZmciQU4Da.R3IUpFW', '', 'นางหนูกร ผิวเงิน (2)', '', 'user_swat263@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '153', '2026-10-30 22:00:14', NULL, 'active', '2026-10-30 22:00:14', '2026-10-30 22:00:14'),
(264, 4, 'user_swat264', '$2y$10$7z8get0wMxiZkc.Tw9tTee3DA8tXFTuz2dv5F.d7ZT.KMF90WL1FS', '', 'นางละมุล สร้อยแก้ว', '', 'user_swat264@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '90', '2026-10-30 22:00:14', NULL, 'active', '2026-10-30 22:00:14', '2026-10-30 22:00:14'),
(265, 4, 'user_swat265', '$2y$10$sV5f23u.kL69iGQ2ArYUJOXVlN5H0ZgQAwx68we0lrvMIshIm/sDO', '', 'นายปัญญา ขันทอง', '', 'user_swat265@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '160', '2026-10-30 22:00:14', NULL, 'active', '2026-10-30 22:00:14', '2026-10-30 22:00:14'),
(266, 4, 'user_swat266', '$2y$10$pg6MU2iQEsSWvgg0.q6dzOcCZxJ3YMVeAK0oTvcA2CJJdy3R9kVtK', '', 'นางจันทร์เพ็ญ ลีกุล', '', 'user_swat266@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '107', '2026-10-30 22:00:14', NULL, 'active', '2026-10-30 22:00:14', '2026-10-30 22:00:14'),
(267, 4, 'user_swat267', '$2y$10$dcUZt1D7sPN.a/L4uJ010e0hdwZPHirZbdrea0nbSaxUkwwpv/PZC', '', 'นางพิมพา นามษร', '', 'user_swat267@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '28', '2026-10-30 22:00:14', NULL, 'active', '2026-10-30 22:00:14', '2026-10-30 22:00:14'),
(268, 4, 'user_swat268', '$2y$10$nyE3QXRF7gBBv0fHGrcW7OD/W8tQnQtg8uhgRqwaKXJIwoDMUTM1i', '', 'นายจันทร์ลา อุดมศรี', '', 'user_swat268@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '234', '2026-10-30 22:00:14', NULL, 'active', '2026-10-30 22:00:14', '2026-10-30 22:00:14'),
(269, 4, 'user_swat269', '$2y$10$y16W2EEwe33loNJt9eFPv.Zycl4oA5ja/wz17gbU/qlTEVWEIxdkO', '', 'นางอุมาพร เวียนมานะ', '', 'user_swat269@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '8', '2026-10-30 22:00:14', NULL, 'active', '2026-10-30 22:00:14', '2026-10-30 22:00:14'),
(270, 4, 'user_swat270', '$2y$10$RfhLwjNlXE4iLNeO/Y8sPOiQYm6PaxJCf6H9AYlmTqV1ouC82ZvXG', '', 'นายขันทอง ครองบุญ', '', 'user_swat270@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '56', '2026-10-30 22:00:14', NULL, 'active', '2026-10-30 22:00:14', '2026-10-30 22:00:14'),
(271, 4, 'user_swat271', '$2y$10$u8zbnDa.4UccbaNLu.CAVuVSk165ByZNAC8q4NUOgpSNdMsAcHH3i', '', 'นายสุรสิทธิ์ โพธิ์สาพิมพ์', '', 'user_swat271@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '80', '2026-10-30 22:00:14', NULL, 'active', '2026-10-30 22:00:14', '2026-10-30 22:00:14'),
(272, 4, 'user_swat272', '$2y$10$tvoHscPGJOcv1TLnHxAg6u./fprJIAnQBDLJ.MiBV5fkm5f5IDCxS', '', 'นางบัวผัน โพธิ์พาด', '', 'user_swat272@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '195', '2026-10-30 22:00:15', NULL, 'active', '2026-10-30 22:00:15', '2026-10-30 22:00:15'),
(273, 4, 'user_swat273', '$2y$10$uQREeoNJOgdmHLRVbRXAKepVW5HeBZylJ4wPXrmKr.e6U18.w8R5.', '', 'นางอรพิมล อรพรม', '', 'user_swat273@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '13', '2026-10-30 22:00:15', NULL, 'active', '2026-10-30 22:00:15', '2026-10-30 22:00:15'),
(274, 4, 'user_swat274', '$2y$10$PBiRxxWeXKzeSd.nrWjuN.BslQZf/0ZL5pQn/Fmz0TyVhta0fgIOS', '', 'นายวิชัย บุญจันทร์ดา', '', 'user_swat274@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '197', '2026-10-30 22:00:15', NULL, 'active', '2026-10-30 22:00:15', '2026-10-30 22:00:15'),
(275, 4, 'user_swat275', '$2y$10$YlOVHB/BFRxn9aikB5jwwOpaiBTR6znjl/HNgiW6x5OJBtsnFPS/u', '', 'นายอุทัย คำลัยวงศ์', '', 'user_swat275@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '130', '2026-10-30 22:00:15', NULL, 'active', '2026-10-30 22:00:15', '2026-10-30 22:00:15'),
(276, 4, 'user_swat276', '$2y$10$y5L555JgX0sCDjD8jZXQUOuCzWEkCKcjcOk0QhAfGlNN0NjsjMoBe', '', 'นางยุพาพร เเนตรวงศ์', '', 'user_swat276@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '59', '2026-10-30 22:00:15', NULL, 'active', '2026-10-30 22:00:15', '2026-10-30 22:00:15'),
(277, 4, 'user_swat277', '$2y$10$KWAMKrBCkQEShb4DoyDhkOzBMNsdQikKskzGfcrfK.ByKaL.xCA52', '', 'นางจำปี แสนศรี', '', 'user_swat277@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '132', '2026-10-30 22:00:15', NULL, 'active', '2026-10-30 22:00:15', '2026-10-30 22:00:15'),
(278, 4, 'user_swat278', '$2y$10$Mg4GhA9a1eGZTUHmDxtVcuZiwuNB6ww/oM5qndgWvYQKCAeDvOsoq', '', 'นางศรีนวน เคยชิน', '', 'user_swat278@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '-', '2026-10-30 22:00:15', NULL, 'active', '2026-10-30 22:00:15', '2026-10-30 22:00:15'),
(279, 4, 'user_swat279', '$2y$10$qxieMEEcowzpb/Zf03sqZ.TqxzV.KDpLT31p1Kjsn/hy/10k0.Xbq', '', 'นางสุดาพร สีสมยา', '', 'user_swat279@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '132', '2026-10-30 22:00:15', NULL, 'active', '2026-10-30 22:00:15', '2026-10-30 22:00:15'),
(280, 4, 'user_swat280', '$2y$10$Q7IO9D5W7u9FdjroRFeaI.fRNHRRjESxaUHTKYrRK1yKMHJz53Deq', '', 'นางนิตย์ ภาวะนิตย์', '', 'user_swat280@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '40', '2026-10-30 22:00:15', NULL, 'active', '2026-10-30 22:00:15', '2026-10-30 22:00:15'),
(281, 4, 'user_swat281', '$2y$10$KZFuLMXfnNl6P.5JKOZCZODKGypE9kZTtWi7TRidOOHvhGKTPih8K', '', 'โรงเรียนบ้านนาจาน', '', 'user_swat281@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '171', '2026-10-30 22:00:16', NULL, 'active', '2026-10-30 22:00:16', '2026-10-30 22:00:16'),
(282, 4, 'user_swat282', '$2y$10$sGGx9AUBnHd9DJX/sJasvOA7setmeyhlrsnNhVPdehy2mA3luwlIi', '', 'นางสำ พันธ์ภักดิ์', '', 'user_swat282@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '85', '2026-10-30 22:00:16', NULL, 'active', '2026-10-30 22:00:16', '2026-10-30 22:00:16'),
(283, 4, 'user_swat283', '$2y$10$yCgAskNZ2LKtVHmM4XzDCef23CI/LlScoxa/VhZIiJeCLqLHkYp2m', '', 'นายอุเทพ พันธ์สาย', '', 'user_swat283@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '9', '2026-10-30 22:00:16', NULL, 'active', '2026-10-30 22:00:16', '2026-10-30 22:00:16'),
(284, 4, 'user_swat284', '$2y$10$If1LwQOlWIWxBnoF.QuLRu1OxIi26.0lK6GXF9sVRBeg8iAo12TWe', '', 'นางสาววาสนา  สร้อยแก้ว', '', 'user_swat284@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '37', '2026-10-30 22:00:16', NULL, 'active', '2026-10-30 22:00:16', '2026-10-30 22:00:16'),
(285, 4, 'user_swat285', '$2y$10$Qhn9Oz8Y6uoe5M1F0AM.f.z1iqPqbW4REVCJB4GWTP6UyYyYL/4X.', '', 'นายดำรงค์ สำเภาทอง', '', 'user_swat285@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '206', '2026-10-30 22:00:16', NULL, 'active', '2026-10-30 22:00:16', '2026-10-30 22:00:16'),
(286, 4, 'user_swat286', '$2y$10$WMHCHjhMW0f7rks4EPbLhes6.eSYTIvbK4daVRXMrjRmW3j.E2znm', '', 'นางอั้ว นิติสุข', '', 'user_swat286@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '144', '2026-10-30 22:00:16', NULL, 'active', '2026-10-30 22:00:16', '2026-10-30 22:00:16'),
(287, 4, 'user_swat287', '$2y$10$7i9V5mXsxfA8itNWhHz0VOKXHKS/XMVJU4tfVRXkOSTDPICExvbXW', '', 'นายสมัย โยรบุตร', '', 'user_swat287@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '77', '2026-10-30 22:00:16', NULL, 'active', '2026-10-30 22:00:16', '2026-10-30 22:00:16'),
(288, 4, 'user_swat288', '$2y$10$svPhCdL74EqWsrE1CN59vucSqJCqZuFlMAFdrq5YiU8W4feL7BuYC', '', 'นางสาวรณพร  จันทวัฒน์', '', 'user_swat288@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '219', '2026-10-30 22:00:16', NULL, 'active', '2026-10-30 22:00:16', '2026-10-30 22:00:16'),
(289, 4, 'user_swat289', '$2y$10$kPJ3l56XdJVUX1BkLoUdu.KI9zRTglug6lB/ttF2W6RUBvatXgUQW', '', 'นางวัลลภา  กำไรรวย', '', 'user_swat289@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '118', '2026-10-30 22:00:16', NULL, 'active', '2026-10-30 22:00:16', '2026-10-30 22:00:16'),
(290, 4, 'user_swat290', '$2y$10$g5ilSMM0DAOXDFOYi9vxm.mDqWfRcLTX.5WMu313CdZ4FrX0XPsy6', '', 'นายหวัด  บัวเขียว', '', 'user_swat290@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '97', '2026-10-30 22:00:16', NULL, 'active', '2026-10-30 22:00:16', '2026-10-30 22:00:16'),
(291, 4, 'user_swat291', '$2y$10$z9Js3trS5/FMEGelrl83aOhKPOJAAc.9Rh9NrN9ohK4Nhu4PSw2jy', '', 'นายสดสวย  ลาบุตร', '', 'user_swat291@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '199', '2026-10-30 22:00:17', NULL, 'active', '2026-10-30 22:00:17', '2026-10-30 22:00:17'),
(292, 4, 'user_swat292', '$2y$10$jpZ6ftuWcwemJ1/DBM3lvebV3M3ErY.lswzngMB/nL81fG38oEhNy', '', 'นางสาวพรทิพย์  สมคะเณย์', '', 'user_swat292@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '214', '2026-10-30 22:00:17', NULL, 'active', '2026-10-30 22:00:17', '2026-10-30 22:00:17'),
(293, 4, 'user_swat293', '$2y$10$swWLAqmQ2NZ.E/LeC65ZP.bjBAcF..UgOppRhilfl0LSLIFgIG1ge', '', 'นายเคน  นามษร', '', 'user_swat293@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '27', '2026-10-30 22:00:17', NULL, 'active', '2026-10-30 22:00:17', '2026-10-30 22:00:17'),
(294, 4, 'user_swat294', '$2y$10$VNE.9sodCfqbDIJKnF.obeGMxk50.zJwcIw4dxXQQDcitBtrDF7eW', '', 'นางหนู  ลาบุตร', '', 'user_swat294@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '54', '2026-10-30 22:00:17', NULL, 'active', '2026-10-30 22:00:17', '2026-10-30 22:00:17'),
(295, 4, 'user_swat295', '$2y$10$Mb2rwDI1pFG3rLAUJnjtzOPtLFfz1Nl3oK1IK8yaNWYB1WJ10ke0q', '', 'นางแอ๋ว แสนศรี', '', 'user_swat295@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '198', '2026-10-30 22:00:17', NULL, 'active', '2026-10-30 22:00:17', '2026-10-30 22:00:17'),
(296, 4, 'user_swat296', '$2y$10$5pHeT7YKEL1fZcjKI5CuKuBD1q58vYcaXzI59nAn4QiL/fmGllsNq', '', 'นายทวีศักดิ์ ลาบุตร', '', 'user_swat296@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '150', '2026-10-30 22:00:17', NULL, 'active', '2026-10-30 22:00:17', '2026-10-30 22:00:17'),
(297, 4, 'user_swat297', '$2y$10$dBdAqAMACq0lnQdFTBH1.OJ3NSjIa/oR7qOWP55km0Thz4ykokHaW', '', 'นายหนูออน เนตรวงศ์', '', 'user_swat297@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '108', '2026-10-30 22:00:17', NULL, 'active', '2026-10-30 22:00:17', '2026-10-30 22:00:17'),
(298, 4, 'user_swat298', '$2y$10$oBT2VYiHVn3rWz.qWvr3g.Gqr4iaV8in2NnJdkAts4tGlMm9TeN0y', '', 'นายชัยชนะ ขุนทอง', '', 'user_swat298@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '210', '2026-10-30 22:00:17', NULL, 'active', '2026-10-30 22:00:17', '2026-10-30 22:00:17'),
(299, 4, 'user_swat299', '$2y$10$gz0BDNSrYCRBQf2CoLiCEO8Azxh3S9MlD5Q6VkXBxcVOTY0aGvLB2', '', 'วัดบ้านนาจาน (1)', '', 'user_swat299@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '-', '2026-10-30 22:00:17', NULL, 'active', '2026-10-30 22:00:17', '2026-10-30 22:00:17'),
(300, 4, 'user_swat300', '$2y$10$N2POs7.Jeus88j5Fi8fZX.D0Tc2mnreQ8cTsmIQu3hZLdZtyJsyi.', '', 'นางสาวน้อย สร้อยแก้ว', '', 'user_swat300@gmail.com', 23, 39, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '260', '2026-10-30 22:00:17', NULL, 'active', '2026-10-30 22:00:17', '2026-10-30 22:00:17'),
(301, 4, 'user_swat301', '$2y$10$08RSZ6DQopQN0o54hgdDdu/InTpMF/SIpKRvGK16VagAw.b29okrG', '', 'นายประแจง จากผา', '', 'user_swat301@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '121', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(302, 4, 'user_swat302', '$2y$10$1oId1nDqA5a.Gv5eWD1c6.xD/Xl87O32WgekJR3wQsaTBUvLbpt6m', '', 'นายไพรัช สุวะรักษ์', '', 'user_swat302@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '162', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(303, 4, 'user_swat303', '$2y$10$HCtnZWl4K5AQoU49EOvQ..521gy4D0nyWBRSGHqS4MdRW4u2o3eAO', '', 'นายสานิต เชื้อชม', '', 'user_swat303@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '141', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(304, 4, 'user_swat304', '$2y$10$5pfm.7iXIbRvv1jp8hvxrOAGiAc2fFw9BRLQHQ0bvonopsjsso2AW', '', 'นายบอ สมตน', '', 'user_swat304@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '58', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(305, 4, 'user_swat305', '$2y$10$8tofuyVT0WuSDa9WPguVHOeMWvshvoba4OH/fJUyaL0ITz6y8E7/W', '', 'นายบุญเพิ่ม สุจะรักษ์', '', 'user_swat305@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '14-ม.ค.', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(306, 4, 'user_swat306', '$2y$10$adk3eciTBpxZWi6nAErJCugPf4UYgzTFwb85cx2HCHV.0NcezKmN2', '', 'นางสาวกองมี สุจะรักษ์', '', 'user_swat306@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '27', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(307, 4, 'user_swat307', '$2y$10$3d/FI7vf1GVcRXQlgMW2DOMZBWgyAHIFwYPBzNPcmwtCkSxNodQpm', '', 'นายศรีคุณ ศรีวะโสม', '', 'user_swat307@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '193', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(308, 4, 'user_swat308', '$2y$10$5qdPSqZgNVJL7JKy4lTKuOZQhFNqJpcqyrFUtNQ1NqOL.ayRMiiF6', '', 'นายสำเนียง สัมพวงค์', '', 'user_swat308@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '202', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(309, 4, 'user_swat309', '$2y$10$74hhLAxMTHbNAKwV6xXEke3lTey0sXNvFK4nEKiqlNG0KGY/GkUF6', '', 'นายหนูหล้า ศรีวะสุทธ์', '', 'user_swat309@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '91', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(310, 4, 'user_swat310', '$2y$10$AvazxCEsUYDGqmr8VlPu7.jktZO/XIAqjBidZ4yjLkXAGgfR1TU/i', '', 'นายคำสิงห์ ทุมมา (1)', '', 'user_swat310@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '79', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(311, 4, 'user_swat311', '$2y$10$EPHe14wGxd1TIpDM5Costemiv0xJP233wPynFfSMsy4XPuH4BHNJ2', '', 'นายนิยม สัมฤทธ์', '', 'user_swat311@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '55', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32');
INSERT INTO `users` (`id`, `org_id_fk`, `username`, `password`, `prefix`, `firstname`, `lastname`, `email`, `zone_id`, `subzone_id`, `tambon_code`, `district_code`, `province_code`, `age`, `weight`, `height`, `gender`, `line_id`, `line_user_id`, `id_card`, `phone`, `image`, `address`, `email_verified_at`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(312, 4, 'user_swat312', '$2y$10$b5DluMcuPDNO3ORZLyeeSOHCbYQl0o5/JNnEZbYXUn98QusUyFZUS', '', 'นางสาวจันทร์เพ็ญ สัมฤทธ์', '', 'user_swat312@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '240', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(313, 4, 'user_swat313', '$2y$10$a3DtBfBBSmrum0JxOmvATeJv6ox4aimYGk02v9rotoqxOahFQncz.', '', 'นายพิชัย สมฤทธ์', '', 'user_swat313@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '211', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(314, 4, 'user_swat314', '$2y$10$iGAGlIOwSDJ.5joYOd9MQucjd7WXGwZJ/4s54cs7z01d3Xrg17wUa', '', 'นายธีระยุทธ์ รัตน์จินดา', '', 'user_swat314@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '225', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(315, 4, 'user_swat315', '$2y$10$2fxjhVx9oP.rupSNup9jhOHt.E3siaG7Bn7yHeKwiEJqC9IHxP/uC', '', 'นางจูมลี สีมาเมือง', '', 'user_swat315@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '83', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(316, 4, 'user_swat316', '$2y$10$NGxlME05HdxfbIiDprl0VOmyD9eFofW3xivdeOdz6mC3nlLKLif.C', '', 'นางสุดาภา วงษ์ธนาชัย', '', 'user_swat316@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '24', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(317, 4, 'user_swat317', '$2y$10$wi0Yi2RtrHFp9hq4B/tpFO2NiWRQydAox0ctI6HaczWhHH5iIGyvC', '', 'นายสุนา ผสมทรัพย์', '', 'user_swat317@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '112', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(318, 4, 'user_swat318', '$2y$10$jy2MIfoQvp7s2JM0qj4s/.LWDyDaqoJ9rEVC37ZD.BdpIaZ.TtKD6', '', 'นายวัฒนา สมนึก', '', 'user_swat318@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '191', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(319, 4, 'user_swat319', '$2y$10$pPWFH9S30.dij973SxeMGO1O7fO5pID/nY7kf3vbE8ZwLxOjvXHy.', '', 'นายหนูเวช สีมาเมือง', '', 'user_swat319@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '64', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(320, 4, 'user_swat320', '$2y$10$s93NI5NyLdRneDNRZYUQA.ghA7bXFOskDQh36EhHz5dy/WGxiSyZS', '', 'นายสัมฤทธิ์ สุจรักษ์', '', 'user_swat320@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '187', '2026-10-30 23:01:32', NULL, 'active', '2026-10-30 23:01:32', '2026-10-30 23:01:32'),
(321, 4, 'user_swat321', '$2y$10$wQnat0fdoS7j2hNeOy9ZtOJRUEKAurtoi46APXt7JM6YdDAgMjFIy', '', 'นายคำพันธ์ ลาสนธิ', '', 'user_swat321@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '26', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(322, 4, 'user_swat322', '$2y$10$2HWYm4StmdPlgelGYf48D.FIVJylotTGGL7ul88e1E7j896tyoTaK', '', 'นางดวงใจ วรรณสุข', '', 'user_swat322@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '54', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(323, 4, 'user_swat323', '$2y$10$rMC36CLmSevLCLMwC7toa.ZDSiFXkHZuuS0sQIL6HczFK.KGs3G7G', '', 'นางวิศัยยา เปี่ยมช้าง', '', 'user_swat323@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '89', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(324, 4, 'user_swat324', '$2y$10$lRfddrZhbJcxWnf3CxcwTOg4mJbgPpl19J/ZI9/DUnWNUmqY2u7d2', '', 'นางนิตยา จากผา', '', 'user_swat324@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '64', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(325, 4, 'user_swat325', '$2y$10$1J51/77jsAbIKBXeFNDtK./3iIj19jFXeGOA7OJG1lvIKRsg8GtgC', '', 'นางสุพรรณ์ ดวงแก้ว(1)', '', 'user_swat325@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '123', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(326, 4, 'user_swat326', '$2y$10$LYgPihKITsJmyFEIsmv5Q.J5j9h4vmh.nhkz4siFZqWX0KkCBKc3y', '', 'ศาลากลางบ้าน(สวรรณ์)', '', 'user_swat326@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '213', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(327, 4, 'user_swat327', '$2y$10$doyHQzbTpjlfqf1A6ebmtecFj1VaQuwD7AhGt7J2IDMLg3mSsZFIS', '', 'นายสายญาติ จันทร์น้อย', '', 'user_swat327@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '128', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(328, 4, 'user_swat328', '$2y$10$D1wY53O1Kx6pqsvTSXCAqea169V.BG08NX/ywHCwiA0SwnQAF0jLa', '', 'นายนัด นันทะสอน', '', 'user_swat328@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '47', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(329, 4, 'user_swat329', '$2y$10$ZXPAQ6r0avtqQWcIGWAy9uPEPUqvKpOZocdzYSvcazdWPJj8jx39K', '', 'นายทองมา บุญทวี', '', 'user_swat329@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '80', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(330, 4, 'user_swat330', '$2y$10$b.6KKuOjVw2kjF5BIKpbC.04jgAmuRWDQO/AJqcC2rSo3Dy92VhSm', '', 'นายวิรัช บัววาศ', '', 'user_swat330@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '120', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(331, 4, 'user_swat331', '$2y$10$LihwqsavZ07GsUaxPEmlD.GrMRK9NoMlSc7Va1b..2DtmmrEGBSbq', '', 'นายสมัย สุจะรักษ์(1)', '', 'user_swat331@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '71', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(332, 4, 'user_swat332', '$2y$10$gi27XUj0C9v8FOn.N8TILupGwp24H5mbuDLOGpP2nxNCFy73QTsp2', '', 'นายสมัย สุจะรักษ์(2)', '', 'user_swat332@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '71', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(333, 4, 'user_swat333', '$2y$10$rgHbX57wvSbke0C972FDIesItQAlOEZHB0r8UfYWUudl/3sngOlXm', '', 'นางรสสา ชมภูเทพ', '', 'user_swat333@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '7', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(334, 4, 'user_swat334', '$2y$10$efYYofpDFweQvKpk8/LU/OTTnn/KT1UTXecPdEqmnR61j/m9vMc3q', '', 'นางประภา ชมภูเทพ', '', 'user_swat334@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '122', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(335, 4, 'user_swat335', '$2y$10$/PeZFvIrtDX0lgDZz3LVqeD5TGajbnH/6ApBzayBN6xcrLwClgZCO', '', 'นางดารา ชินพรม', '', 'user_swat335@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '44', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(336, 4, 'user_swat336', '$2y$10$3HjQrigVMVYkrZ.esnLlCO8I2w5mib/idVtDhfuld7s/GLdQrRYZO', '', 'นายวิชัย ทุมมา', '', 'user_swat336@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '72/1', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(337, 4, 'user_swat337', '$2y$10$9IyUhR62NK6G2vLaf2F5b.Rg1KF9LcCV8GTT8l/ljWgkvCEScM1vu', '', 'นายเกษม ทุมมา', '', 'user_swat337@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '130', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(338, 4, 'user_swat338', '$2y$10$boU1xhJtFwqEmUfYZdQJtuKGud8p9jB3ehu3jypjCLQCzLosQP9Py', '', 'นายสวาท ทุ่มมา', '', 'user_swat338@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '172', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(339, 4, 'user_swat339', '$2y$10$TdPel7yXHwLsg4TDsKR0ce4jj.YEBk8Hn8ydk6oJxRvP6GripMN6O', '', 'นางผม จากผา', '', 'user_swat339@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '33', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(340, 4, 'user_swat340', '$2y$10$rrZzgeglqeBEmp/98feoH.YcH3Wpxt2YUOIv.7OSm7VpXn631/8Ra', '', 'นางวาสนา บุญทวี', '', 'user_swat340@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '38', '2026-10-30 23:01:33', NULL, 'active', '2026-10-30 23:01:33', '2026-10-30 23:01:33'),
(341, 4, 'user_swat341', '$2y$10$vqqsMHe.7Dab7lh/QX3qp.iPTekCt3VJpjhy9xhBooIgfpg5YuN36', '', 'นายน้อย ชื่นตา', '', 'user_swat341@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '35', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(342, 4, 'user_swat342', '$2y$10$be6i9X55kzu2qV9C1R16meRzQ7hka/aNlJnTsxg1TjL12aEvr83aa', '', 'นายศึกษา บุญทวี', '', 'user_swat342@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '53', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(343, 4, 'user_swat343', '$2y$10$abEMEniYW2dgpTAxJnBcDO/Aw/sdXKVi/2RLfnhh2/9rvNh.BkznK', '', 'นายทวี วรรณจันทร์', '', 'user_swat343@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '159', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(344, 4, 'user_swat344', '$2y$10$0Reb2Y7AJiLqYKwJc2N2qecg9nUFVbGGXN6XRv/rgcMlgro4hulIm', '', 'นางอรญา ทองนาค', '', 'user_swat344@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '39', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(345, 4, 'user_swat345', '$2y$10$kmPpaPKADE7YpPR.wYdOCessaEPxu3pmyHbzm6N6gblQX9Dyuwgz2', '', 'นางสุพัฒตรา ดวงแก้ว', '', 'user_swat345@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '67', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(346, 4, 'user_swat346', '$2y$10$cqfGZYEmmBYo8UXcXSDBu.V95tL6unkM3ea6uJIGXnNOpSUsn73ti', '', 'นายทองสุข ทองนาค (1)', '', 'user_swat346@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '126', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(347, 4, 'user_swat347', '$2y$10$iDj1GzFbwnDN84m0RUjNE.qEDXSCLieVqCdvV3mANMRwmCZQZ1Kka', '', 'นายทองสุข ทองนาค (2)', '', 'user_swat347@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '126', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(348, 4, 'user_swat348', '$2y$10$94Msi4h7GJYxDrKwi0rl2.rQB6CCA7Dz9Ce1gR3YN2omMY4iRTv9m', '', 'นายวิชัย ลาสนธิ', '', 'user_swat348@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '139', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(349, 4, 'user_swat349', '$2y$10$bYMQHZ4t5wmK5hh3R20u7uBj5SI0zGNjYqAxZILvXbExe.Lhci48O', '', 'นายอดุล ลาสนธิ', '', 'user_swat349@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '133', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(350, 4, 'user_swat350', '$2y$10$Qd0KUhlUOr0X6yc3/QHbR.d/xhxgDVWfURGXxV8Y6xsOXDQBHYy6m', '', 'นายประมวล ลาสนธิ', '', 'user_swat350@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '147', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(351, 4, 'user_swat351', '$2y$10$jMDMlkiLCZuEmQhXufB1BOYuLah1shXnCmQn8wiZHa7D2d1vTjMqW', '', 'นางดวงตา สมตน', '', 'user_swat351@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '7', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(352, 4, 'user_swat352', '$2y$10$cDf1cVM3ZFq7cQKGt8NEJuNL0l53rqqB3M7Ypw8DngtuwVxWO9QWS', '', 'นายสวัสดิ์ ดวงแก้ว (1)', '', 'user_swat352@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '4', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(353, 4, 'user_swat353', '$2y$10$9f24vO2yOU/lxjkUe/VSRuTYKHMT7Z56yONm6uMT7tc2Ie7.XhzVO', '', 'นายสำรี โคตรวงศ์', '', 'user_swat353@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '203', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(354, 4, 'user_swat354', '$2y$10$JIcTy6nooq4sA2sIkwwbPOScG1JbJqZA9a1dmoUPHK4sjQLrQMYqW', '', 'นายประสิทธิ์ นามคำ', '', 'user_swat354@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '62', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(355, 4, 'user_swat355', '$2y$10$yMHJEOUh3pju6pLyeXwGmeaRyZv9aFqY5Rg1bM9wKElDeclevamym', '', 'นายสุวิทย์ กิ่งแก้ว', '', 'user_swat355@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '116', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(356, 4, 'user_swat356', '$2y$10$Ijimcxxma6jUeQI9IAaXUuWQsjnqcqm3/jvFJ4eSDK3UM3QLxxz4K', '', 'นายสุรี กิ่งแก้ว (1)', '', 'user_swat356@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '111', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(357, 4, 'user_swat357', '$2y$10$LzfnT5pYYjWlVBir6WIqnOVZZ7g5YKjz/Lxi90Qe.z.NdLI72T1au', '', 'นายครรชิต สัมฤทธิ์', '', 'user_swat357@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '132', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(358, 4, 'user_swat358', '$2y$10$.FYiBzao3.Jg2B7OqYlV0.w1.treDh0MydrQRs0pcx5aK5f4RIsQ6', '', 'นางวัลภา หมื่นสาย', '', 'user_swat358@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '149', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(359, 4, 'user_swat359', '$2y$10$r3om8vpoBtdMZmBk.j1uAuAKWEdUxxP.I7KdavMaAdLAYObCAUbBW', '', 'นางแปลง พวงผกา', '', 'user_swat359@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '22', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(360, 4, 'user_swat360', '$2y$10$9m6T3CNbBLLVzhDNy43EZelE5VmjcgSFaHi0NGa1oV820fby/uGX2', '', 'นางสุพิตย์ อยู่สุข', '', 'user_swat360@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '95', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(361, 4, 'user_swat361', '$2y$10$obk77espH3e8xdqcU7DHL.b0YdxqWFFE2KV8UvIoGZATLn5r2q9r2', '', 'นางสำรอง ศรีวะรมย์', '', 'user_swat361@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '150', '2026-10-30 23:01:34', NULL, 'active', '2026-10-30 23:01:34', '2026-10-30 23:01:34'),
(362, 4, 'user_swat362', '$2y$10$ovJ60a6/12NiZQgPiWxiRus5/xN88BeYOeSqMFNg/cdq5WhxHHk/O', '', 'นางทองพูล นามคำ', '', 'user_swat362@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '56', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(363, 4, 'user_swat363', '$2y$10$xnFz7awt2O2yVFj/qtL.mepgMPCk0bK3PFqFId1v712Aj6oK63d/G', '', 'นางบรรจง ทุ่มโมง', '', 'user_swat363@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '158', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(364, 4, 'user_swat364', '$2y$10$q5eplH5TsrYpxUoEXj26zu0CRTVvrXkJ8HOjpZzCgQr2hyPlv3/R.', '', 'นางอริวรรณ มุ่งงาม', '', 'user_swat364@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '9', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(365, 4, 'user_swat365', '$2y$10$hLMr13cd2fzYU4cnL9IZa.uGrnbT//kKnd.cbQr/Le8NKxKAbwULS', '', 'นายสมจิตร พวงผกา', '', 'user_swat365@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '73', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(366, 4, 'user_swat366', '$2y$10$TuD9pSk8ILH.hv0nmsM5kedJBL3npNTOOk2gVgXpj9aq.l2sM96sW', '', 'นายสุนา จันทรภา', '', 'user_swat366@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '48', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(367, 4, 'user_swat367', '$2y$10$vJWKRNbg7MInuCiSHH9UDOpZyz.WWRq5hZMDt9RxPm.tG/2sroV6m', '', 'นางสาววิจิตรา อยู่สุข', '', 'user_swat367@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '134', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(368, 4, 'user_swat368', '$2y$10$HAr0ajm4/1I7cKqrQOmu1eqsjVtV2JJ3BP.UHrqnsExYs5upO/dt.', '', 'นางอุไรวรรณ ศิริโสม', '', 'user_swat368@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '106', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(369, 4, 'user_swat369', '$2y$10$e89nMteYWkWZeawtLFUJdeQlxPLzDQ/LfS97RYz2ETdWmLtY4fkHu', '', 'นายสมัคร นามเจริญ', '', 'user_swat369@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '106', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(370, 4, 'user_swat370', '$2y$10$3jNCkIllbXHF3rvru6x6auylwdien3Dwh56uAtYRxSuUjBcOdz1VW', '', 'นายอุทร มุ่งงาม', '', 'user_swat370@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '74', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(371, 4, 'user_swat371', '$2y$10$DBjH.CQHyP8sXDHbEO4PxOozMlviRIQcVM4LHbUgEqy1gdBN0.l5m', '', 'นางบุญน้อม ทาขุนทศ', '', 'user_swat371@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '113', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(372, 4, 'user_swat372', '$2y$10$KYGUWhYKUKaoRNp/khk1AeGz5cvxt2bJVK54YcEAtiixkw1DoaCUK', '', 'นางหนูกุล แสงฉวี', '', 'user_swat372@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '82', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(373, 4, 'user_swat373', '$2y$10$KK.fBIqm3BAsjy4uRe83WODWyzg/RaolJzfGGF2QptIg5CwtM9Gea', '', 'นางแก้วตา งามฉลวย', '', 'user_swat373@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '66', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(374, 4, 'user_swat374', '$2y$10$jaa3PT6ik2T2AjkNtwqAZOCB5.duQ7LVWlFBJcvQrKDs7d7E7HOGW', '', 'นางอุดม คุญไชย', '', 'user_swat374@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '19', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(375, 4, 'user_swat375', '$2y$10$T37.nlDYVX2GFWcQIcmLQOri6qju2Nsk/9b8Qla5irlFo8utmFzN2', '', 'นายประเทือง ขันสุปัฎ (1)', '', 'user_swat375@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '30', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(376, 4, 'user_swat376', '$2y$10$TgrchPK4LF.NMa3KJ9kIleVTm9XRTCvyoxgx33ipLzYaIFH.8UwTi', '', 'นายรันดร ผสมทรัพย์', '', 'user_swat376@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '18', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(377, 4, 'user_swat377', '$2y$10$G4UXvISIeftJ8tTsDNB5xOM9MyVv7WWznpQqfGyyL7xAJJNhGmQFq', '', 'นางทองจันทร์ ทับแสง', '', 'user_swat377@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '8', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(378, 4, 'user_swat378', '$2y$10$j/dnm0wKk5UD73yd1z9NeeY35u7ILnu4IrFtjrZ0zrD9H7HgBxN/y', '', 'นางสุปราณี ภูพวก', '', 'user_swat378@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '10', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(379, 4, 'user_swat379', '$2y$10$ugcZ.eDlzQRoaCqR1DRa5e9raqEvYgMdaxDim208HXvnCnmShs2se', '', 'นางเหรียญทอง นำพา (1)', '', 'user_swat379@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '105', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(380, 4, 'user_swat380', '$2y$10$P3eShNf7sQnSav4m23A7Ie5w/omputGOOSwJOugFgarxmXqVTNl.K', '', 'นายถนอม มลศิลป์ (1)', '', 'user_swat380@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '76', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(381, 4, 'user_swat381', '$2y$10$SOuIXZNstWFguXOcdy5evOKrjn0CAf8QnWdLmE9v0trmCsK8uZ4oy', '', 'นางมณีกานต์ พันธ์จันทร์', '', 'user_swat381@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '15', '2026-10-30 23:01:35', NULL, 'active', '2026-10-30 23:01:35', '2026-10-30 23:01:35'),
(382, 4, 'user_swat382', '$2y$10$MTYAS3tJOgRhBtGYKImvwu60FRcxVagDpFvPpf6vT..CbaZdFBJ/.', '', 'นายอาทิตย์ ทุมมา', '', 'user_swat382@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '102', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(383, 4, 'user_swat383', '$2y$10$9KlvgQMbNa3/wLBcegreEeK.pdJxiwgGc0w6SXTVwBn4r4m2Smsby', '', 'นางดอกแก้ว โคตรวงค์', '', 'user_swat383@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '103', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(384, 4, 'user_swat384', '$2y$10$tQBlXGrJow1h/ufqSrQtOOqb.P2roNpdK.UJajPzzoggkULxg0PE6', '', 'นายเทพพิทักษ์ ศรีวะสุทธ์(1)', '', 'user_swat384@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '98', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(385, 4, 'user_swat385', '$2y$10$hoW6SltMR1wWdStc1CODp.4hxpXyVEKhxHnHGQw82WZOJY.fR7kGa', '', 'นายเทพพิทักษ์ ศรีวะสุทธ์(2)', '', 'user_swat385@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '98', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(386, 4, 'user_swat386', '$2y$10$j1bW15vnvjNj2wajJ7WXye8tXoA.tAjTYO5OCEP4awQA5kAdqioE2', '', 'นายทองดว้ง ภูมิอ่อน', '', 'user_swat386@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '143', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(387, 4, 'user_swat387', '$2y$10$snFey49B/TlkJ/348VsipuEATI5JBzy7J9001hAnSwCWS1y5l9mrq', '', 'นายเขียววรณ์ มุ่งงาม (1)', '', 'user_swat387@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '154', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(388, 4, 'user_swat388', '$2y$10$xOIF/jkk3sBzl0RDJAKnPO3p5vRV4TShfNkiQhwrVeQM7MPYr/3/K', '', 'นางจิตรจำนงค์ บุญเปรี้ยว', '', 'user_swat388@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '51', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(389, 4, 'user_swat389', '$2y$10$Ei2zOTzidnO6R//HwGPI4uhtZO0CPLs0CDjmljm4ynCI9voTBjKn2', '', 'นายทวีทรัพย์ สุขนา', '', 'user_swat389@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '16', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(390, 4, 'user_swat390', '$2y$10$srGNSB46F4dqXgt9AFvXjeoN40krXMEGCkwGU68osLaubxMi3SJR6', '', 'นางกองศรี รัตน์วิชัย', '', 'user_swat390@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '208', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(391, 4, 'user_swat391', '$2y$10$OcXuO.0HUhLK07WKQALKUejvhehCNZ4JK9GUBgOGvnDLLHitv56Ia', '', 'นางบุญยัง มุ่งงาม (1)', '', 'user_swat391@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '125', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(392, 4, 'user_swat392', '$2y$10$ca5bhspXRi4PZUvRcGfevOi66GzU2aTVBHl4AlmxpCz.DqagaOzxC', '', 'นางศรีประไพร มลศิลป์', '', 'user_swat392@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '207', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(393, 4, 'user_swat393', '$2y$10$DEYAIlM75jHXW6xnGptBWOeeBLlP9JOIvTUQI10tEMXffalFk6Fbi', '', 'นายอำนวย ทาสีเพชร', '', 'user_swat393@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '70', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(394, 4, 'user_swat394', '$2y$10$sW/Lt/tGc7yoDbwG2uGEiOd/yl3Vu31l0pDB/jKVXt7PT5M4Y9EZy', '', 'นางดอกจันทร์ มุ่งงาม', '', 'user_swat394@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '196', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(395, 4, 'user_swat395', '$2y$10$Nefk7C2kBz1vHqFOk9Uj9.WeOFFdURgTgqmEIt2zspG8Kfk8Ze/4G', '', 'นางกุหลาบ สุจะรักษ์ (1)', '', 'user_swat395@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '60', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(396, 4, 'user_swat396', '$2y$10$THmZ.xKlrNFgxYNKYXFiY.BHLshg4cJbxB8KUD7ea2OAmwoSVMSwO', '', 'นางสังฉันท์ สีคำ', '', 'user_swat396@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '230', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(397, 4, 'user_swat397', '$2y$10$6/YgiJlwwFU3y4pe.xj9n.8x.EtfxOhYbHsWgRbaRRc4MMqJxsg3.', '', 'นายทองจันทร์ จากผา', '', 'user_swat397@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '138', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(398, 4, 'user_swat398', '$2y$10$0b73lLdlRgbiy0b5XkTsbu1GD3zoStX7KMemvODYqFqEDxPou9z2u', '', 'นายประยงค์ ศรีวะสุทธ์', '', 'user_swat398@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '50', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(399, 4, 'user_swat399', '$2y$10$gfR55Z15UFRM.Xo9IwjFW.kx/NicnCcDPAnsZ0gQ.GFyzdScaxbeW', '', 'นายพิกุล สีดำ', '', 'user_swat399@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '100', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(400, 4, 'user_swat400', '$2y$10$lkO6BjUDaX6C2bhreOdywuAe6/qHJho84Zg8a0Wyt1BT0S4l9kC5O', '', 'นายเสน สุขา', '', 'user_swat400@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '77', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(401, 4, 'user_swat401', '$2y$10$Fge03v1Ad7XdIrjGF8WcE.3wMbSDqI7b95f4TPhVmHoSgiicR5oxy', '', 'นายทศพล สุขา', '', 'user_swat401@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '32', '2026-10-30 23:01:36', NULL, 'active', '2026-10-30 23:01:36', '2026-10-30 23:01:36'),
(402, 4, 'user_swat402', '$2y$10$.57qCwxafd71VW2x6EwFnun/Rmf19iJD0nTcZ.9uwjoKVXeitDca6', '', 'นางสมคิด สิงท์ทอง', '', 'user_swat402@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '78', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(403, 4, 'user_swat403', '$2y$10$XNej47zSKc0.iMPZPdLf1OE6rfcJkf3yJigasYnjE0hlcPzIEd1rG', '', 'นางสี สีดำ', '', 'user_swat403@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '45', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(404, 4, 'user_swat404', '$2y$10$RDQF/Gzqp.5LkXOuwEzppu5lhf4uP0g/nSS.EmSbX2h698MbeXM/e', '', 'นางไพบูลย์ มิ่งขวัญ', '', 'user_swat404@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '75', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(405, 4, 'user_swat405', '$2y$10$TQfOtQRCNy1ui8.D0cLy3unVbgIwrcuPddb5UNkEO5bk0Ik5bWclS', '', 'นายประชา จักรสาน', '', 'user_swat405@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '146', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(406, 4, 'user_swat406', '$2y$10$UQ0cMyCwjioah.S7hQBZs.wbpFwKcvDmbKZYaxUZZRS/oXldK2LQO', '', 'นางจูมพร บุญทวี', '', 'user_swat406@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '68', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(407, 4, 'user_swat407', '$2y$10$aKYC8ZyrR9RcUtrtlXZoheU4Bso1U3axVgfWpxh3RDfBvDNrIiYkG', '', 'นางบุญมี มหาชัย', '', 'user_swat407@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '52', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(408, 4, 'user_swat408', '$2y$10$RZtxwAXI9Qo8jW03LZGcleCyxhQvdG78dM90YLalSclSAjW7EMId2', '', 'นายไพบูลย์ ทางาม', '', 'user_swat408@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '192', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(409, 4, 'user_swat409', '$2y$10$tf5a0IU1srlaOQkg8BxR3.h4uWUrxU.v3g57JORrbncHYij59xDhW', '', 'นางบุญถำ ภูมิอ่อน', '', 'user_swat409@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '69', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(410, 4, 'user_swat410', '$2y$10$m4zQ2kwdICwPxDjKNs/.EeQeS0r6nWBAlqtG.4sIjRgOFm2TLa0B6', '', 'นายศักดิ์ดา งามฉลวย', '', 'user_swat410@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '3', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(411, 4, 'user_swat411', '$2y$10$M7nwYCUd1ohd96LN3t.QBO46QSm.ZAjqrdQCKbewt9DycLTV3phbe', '', 'นายทวีศักดิ์ มุละสิวะ', '', 'user_swat411@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '34', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(412, 4, 'user_swat412', '$2y$10$XCU1trloBOeqzBF7frfROuqa0UtscQKSspPPCuIGvAbbP57X66rO.', '', 'นายดอกไม้ จันทะชัย', '', 'user_swat412@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '119', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(413, 4, 'user_swat413', '$2y$10$34xgVuXMjEvhR5ixEXjY0eAs0XZlhCdpFQL2GQhniXFjCDghqPyta', '', 'นายเสงียม ทาสีเพชร', '', 'user_swat413@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '94', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(414, 4, 'user_swat414', '$2y$10$liAtE6g6tgkVtLPNuMkNNOeKhmcp1TXJaRl10N6QJ4CweYioW5Qqu', '', 'นางนารี มโนบุตร', '', 'user_swat414@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '2', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(415, 4, 'user_swat415', '$2y$10$idO627f13dOf4kT7bTMrEuT8lWrASqivMdEnZ5uCNVuFLs0d5BVe.', '', 'นายศักดิ์ดา ขันสุปัฎ (1)', '', 'user_swat415@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '118', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(416, 4, 'user_swat416', '$2y$10$i1PAgGvCX1NGLbjpMFn93eZDsXmWL0F8yWSvkerJRc/ZWGP1ocQJm', '', 'โรงเรียนบ้านพรหมนิยม', '', 'user_swat416@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '694', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(417, 4, 'user_swat417', '$2y$10$3i5TwP.C4fFvfc.P1RIea.zEpRz80gwOoqHUWADoIKo6q8wp.45vK', '', 'นายโสภณ บุญอาจ (1)', '', 'user_swat417@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '1', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(418, 4, 'user_swat418', '$2y$10$8htffYEL1Azo1nvNgEbqSe9s/J5tpJEHLzIqGvPTdlxbmwMdx0Nqe', '', 'นายจำเริญ นามกา', '', 'user_swat418@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '117', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(419, 4, 'user_swat419', '$2y$10$qsdVinAHO3g.sdGXP.pH.uCqZ0RQuHny8mwGjIHvu..nlCNqi2vcG', '', 'นายอุดร ศรีวะสุทธิ์', '', 'user_swat419@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '6', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(420, 4, 'user_swat420', '$2y$10$o58iczPbONY3PZOCHPFdLOGGYHNXrVlMt4AfYzgbbWkPBgBmrxSpC', '', 'นายลุน ทางาม', '', 'user_swat420@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '46', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(421, 4, 'user_swat421', '$2y$10$644GsQA/cX/YL4ataM27ye8Pa1pD4xrN1OHXS6G93k/pMl3g9DFl.', '', 'นางสมรัก ศรีวะสุทธ์', '', 'user_swat421@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '49', '2026-10-30 23:01:37', NULL, 'active', '2026-10-30 23:01:37', '2026-10-30 23:01:37'),
(422, 4, 'user_swat422', '$2y$10$Oinl2DWsWIXjd8nNVEQeL.qaeC.qGLe.SJfA6a4leP3IP6QBpqFx.', '', 'นางทองสี บุญทอง', '', 'user_swat422@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '13', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(423, 4, 'user_swat423', '$2y$10$aYPei1w0uJUmeff5kIr2BOXxEx9Kwq.HwM2/HpKcMlcapvqV8In4q', '', 'นางหอม สายชาลี', '', 'user_swat423@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '153', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(424, 4, 'user_swat424', '$2y$10$krVsTuV4nBmxYPXGNfp0feeCsh/BCjZr/UCDWwOS9kKT8DhoiHSnC', '', 'นายนึก นันทะสอน', '', 'user_swat424@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '59', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(425, 4, 'user_swat425', '$2y$10$7XLEPEaUJqM7U/JySgFKB.vGAB7RVncWoglfVJCWvHutoA.jFZbly', '', 'นางบัวลอย หัสดร', '', 'user_swat425@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '36', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(426, 4, 'user_swat426', '$2y$10$gg4iFKa5bzUcpSCoQIMLxe/hjIwrb9ZMHGz04QRH1vPbVCyyANvX6', '', 'นายวิเศษ กะมุทะ', '', 'user_swat426@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '160', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(427, 4, 'user_swat427', '$2y$10$.L53QH7vfZjvcHV2dozLkuB2kAH1v26YuBFz.yTpqmdHhZracOnX2', '', 'นางนิตยา ชมภูเทพ', '', 'user_swat427@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '7', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(428, 4, 'user_swat428', '$2y$10$G4QNb.zFKUB465ZVuFNG4eSDNNa1PkJyEktFNweQgikHBUgIau9lu', '', 'นายสุภาพ ทาสีเพชร (2)', '', 'user_swat428@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '225', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(429, 4, 'user_swat429', '$2y$10$NEQjPhu.3Vf5dFFuFsfygOacXB/Fckq8j6zGQ1m4FXHFbPUKLMmMa', '', 'นายประเทือง ขันสุปัฎ (2)', '', 'user_swat429@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '30', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(430, 4, 'user_swat430', '$2y$10$rsSHQL/Y5Jg86ud94W/vd.sVW4kHc0AmbGCPMog/W3CjUQITMBP4O', '', 'นางสุบรรณ์ ชอุ่มพันธ์ (2)', '', 'user_swat430@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '23', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(431, 4, 'user_swat431', '$2y$10$GPRFsafkG5hYu1feloGTPOKPsMB.2aGvgQ4VFbNashhEIvpdDw/YG', '', 'นางวาสนา บุญทวี', '', 'user_swat431@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '38', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(432, 4, 'user_swat432', '$2y$10$NDyHJbLVJovGg.jdGIMj3.AmdwUZkHUaPkY476r8wzrmf2V8pdlM2', '', 'นายสมจิตร สัมฦทธ์', '', 'user_swat432@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '43', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(433, 4, 'user_swat433', '$2y$10$GDj1uN6A177IPFg3a4cHeu5IJIv7hRzPv9YaP0R8.uWf2PyOclAnO', '', 'นางทองศูนย์ ขันสุปัฎ (2)', '', 'user_swat433@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '30-ม.ค.', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(434, 4, 'user_swat434', '$2y$10$YIajqXOVxKKpY4nSyEG5.ulptHoVnA2EPr8YW2LbpmlvUTQ5qkRFy', '', 'นางสุภาพร มุ่งงาม', '', 'user_swat434@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '74', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(435, 4, 'user_swat435', '$2y$10$yUsJd.n.JzUDmtfpaDAaKuJw/2hFe7eptjrubILjnAmHDw4MhyvmW', '', 'นายประครอง สีมาเมือง', '', 'user_swat435@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '20', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(436, 4, 'user_swat436', '$2y$10$dOAz4hjDUb817SJVpruVxuJ4otZEeh3EGfnDAIXaBoExGNqMsmXia', '', 'นายศักดิ์ดา ขันสุปัฎ (2)', '', 'user_swat436@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '118', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(437, 4, 'user_swat437', '$2y$10$Z.QWCOz.k.fM/Lv2dq8j6.gOzMBLRFkU5BseUeOaVIvSWQ50GuKTi', '', 'นางทองศูนย์ ขันสุปัฎ (3)', '', 'user_swat437@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '30/2', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(438, 4, 'user_swat438', '$2y$10$b/Dvbc/n8U/HTdC1Wcoy6uT3Ngrk5uMG9.6d3N2jKE2ISQrlh1aLy', '', 'นายรัศมี บุญทศ', '', 'user_swat438@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '239', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(439, 4, 'user_swat439', '$2y$10$yJg1gtlYBJa9kn377ALmmOWJ9EB.LQms5sUi/0XmKWNkr2X5Kh9h6', '', 'นายสมชาย ขันสุปัฎ', '', 'user_swat439@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '19', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(440, 4, 'user_swat440', '$2y$10$E.Swxg9HJPYAyIJQkiIvWepaGDB8cLKbyJ1pt88.4MIxGLnpU7uT2', '', 'นายคำสิงห์ ทุมมา (2)', '', 'user_swat440@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '79', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(441, 4, 'user_swat441', '$2y$10$gmtE2L.259l564U.lmh.TefLoPoOWvLQynjc0LLbaRtpNneXxXWta', '', 'นายประจวบ จากผา', '', 'user_swat441@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '190', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(442, 4, 'user_swat442', '$2y$10$y.LyKA5mYIM1rLDOKQyovO/D0us4/BshqNzxVer0GWvGMXnpUfSm.', '', 'นางสาวศรัญญา ศรีวะสุทธิ์', '', 'user_swat442@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '210', '2026-10-30 23:01:38', NULL, 'active', '2026-10-30 23:01:38', '2026-10-30 23:01:38'),
(443, 4, 'user_swat443', '$2y$10$2h3BNDdYQdKoIiDlHS842ecRK0DA1NMMTHDmCUL7W4uCW/JcCosDu', '', 'นายจันทรดี จักรสาน', '', 'user_swat443@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '107', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(444, 4, 'user_swat444', '$2y$10$SsPjjqVrjfJb2oQraYIJ4OZxjRAKqt5MCc0O3gvmXnmKZ1LA67tU2', '', 'นายภักดี บุญทอง (2)', '', 'user_swat444@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '129', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(445, 4, 'user_swat445', '$2y$10$RBn9K0hzbXVcCKoCpJtwHOqDX6GqZlZ71T7kOBTT7jsphRTKecGxy', '', 'นายสุธี กิ่งแก้ว (2)', '', 'user_swat445@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '111', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(446, 4, 'user_swat446', '$2y$10$TlABG8F5C0AFkq33PQ3aluZUfr/rI9uOa9MzuDneK4JdjhjykdpWm', '', 'นายสวัสดิ์ ดวงแก้ว (2)', '', 'user_swat446@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '4', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(447, 4, 'user_swat447', '$2y$10$CNriUODeJX2EUJCRzqZb9eSaprDGWlN4UUkUjADHvr3qSgefyPj76', '', 'นางทองจันทร์  เกี้ยงเก่า', '', 'user_swat447@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '57', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(448, 4, 'user_swat448', '$2y$10$GiR/JafPJCNslU6FWR.lqeQ94KOg0gYwCzV3si12dRjWThos9vISO', '', 'นายล้อม สุจรักษ์ (2)', '', 'user_swat448@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '28', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(449, 4, 'user_swat449', '$2y$10$CbMnr0hxG0rdZfE8QgZPX.ZcYUSAvInylw8OZdkgRsh2flWAQdJOe', '', 'นางบุษยา จากผา', '', 'user_swat449@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '224', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(450, 4, 'user_swat450', '$2y$10$xLcizVSOTbPmUBTauyIkQ.abqCCjjRRvz34CY5EPKnzFhvpzhoOMq', '', 'นายวันดี ชอุ้มพันธ์ (3)', '', 'user_swat450@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '23', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(451, 4, 'user_swat451', '$2y$10$zT5Pcd9Pc.Whg2ixWqVhz.MONzodOMeG7xk9xZNvOLh1nstGsc.ye', '', 'นายวิทยา สมตน (2)', '', 'user_swat451@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '86', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(452, 4, 'user_swat452', '$2y$10$7EA.TBxPfTW9JcYAjSNAbeovUYQbERmqCEZlUaBDRl8PH2WCrk/Ze', '', 'นายเขียววรณ์ มุ่งงาม (2)', '', 'user_swat452@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '154', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(453, 4, 'user_swat453', '$2y$10$vQOH.KnPYgNalElPmHOYWu81.owoSkcT4gpCliacq6vXcvOoExc4W', '', 'นายวิทยา สมตน (1)', '', 'user_swat453@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '86', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(454, 4, 'user_swat454', '$2y$10$6k2P33d.n7EOojviFISHG.KCcoRJcGwz488x9sWGn6bbRXSEWNTUe', '', 'นายเฉวต นามคำ', '', 'user_swat454@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '165', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(455, 4, 'user_swat455', '$2y$10$.fE/xUCgDzIwXn67/NnCXeyxLlTK4VvPuIgByaOYS2cXvBw3rJxIS', '', 'นางละมัย นามคำ', '', 'user_swat455@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '137', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(456, 4, 'user_swat456', '$2y$10$UZplVkz.qN6mYQf57Lo1/.1lbZFODOhU7Kq/0iBS/Dv63R18izAQC', '', 'นายสี สิงห์ทอง', '', 'user_swat456@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '11', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(457, 4, 'user_swat457', '$2y$10$I.rKB0f444Xuay/3p9mO8eJutBMKMg31krsHoKR8v7c9.T6dvmHTu', '', 'นายภักดี บุญทอง (1)', '', 'user_swat457@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '129', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(458, 4, 'user_swat458', '$2y$10$ql8IRPu2iZ9/E087JMnmNuxYHjVrEtUlWEkzGuLwxn1TAS8R9pYUq', '', 'นายสมบูรณ์ เกี้ยงเก่า', '', 'user_swat458@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '87', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(459, 4, 'user_swat459', '$2y$10$4X5cHOUJSg2YKWMjXEK7m.lU1GIXTyImIOshdqFp4P/NvZvy9a/Oi', '', 'นายถนอม มลศิลป์(2)', '', 'user_swat459@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '76', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(460, 4, 'user_swat460', '$2y$10$191W6GDKzrJ3Oc5utjKGjub3hvUJmVcp9DdN36Gve9EsqWKjRCkE2', '', 'นายสมปอง มลศิลป์', '', 'user_swat460@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '96', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(461, 4, 'user_swat461', '$2y$10$PNti1XQcJqAjsLDAiQuaHOuU0nPBoEg6FFNGUfm7iLAioSpYtDk3C', '', 'นางสาวนริศรา ทองนาค', '', 'user_swat461@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '261', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(462, 4, 'user_swat462', '$2y$10$IYswomhwgbq5F19wKUZVx.kbk5g/PGRDbOeBlsHcsrLyrUFCQCNLO', '', 'นายจตุพร ทาสีเพชร', '', 'user_swat462@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '221', '2026-10-30 23:01:39', NULL, 'active', '2026-10-30 23:01:39', '2026-10-30 23:01:39'),
(463, 4, 'user_swat463', '$2y$10$8zepMr6sSirZWx.QJRkA8.WIvm8hBjlqZGiUN2PkXDCCWGETAR2pe', '', 'นายสมบัติ ทางาม', '', 'user_swat463@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '46', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(464, 4, 'user_swat464', '$2y$10$8sDBhpGfHDKR/I4vUkvp3OjYmr0PUsvyp539AswLF0IC02jOFQ4Vy', '', 'นายบุญช่วย ทาสีเพชร', '', 'user_swat464@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '70', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(465, 4, 'user_swat465', '$2y$10$t8O0kHCY9HfiG/o9HLHJUePuKDQtgg3Ox878fsn48rGrTfvcURV/a', '', 'นางไพบูลย์ ทองนาค', '', 'user_swat465@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '53', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40');
INSERT INTO `users` (`id`, `org_id_fk`, `username`, `password`, `prefix`, `firstname`, `lastname`, `email`, `zone_id`, `subzone_id`, `tambon_code`, `district_code`, `province_code`, `age`, `weight`, `height`, `gender`, `line_id`, `line_user_id`, `id_card`, `phone`, `image`, `address`, `email_verified_at`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(466, 4, 'user_swat466', '$2y$10$prPvUyYQrmp5./OOlx1oKekRBLLy/2vmkWQ7W9RU4rK88ii8lrHVm', '', 'นางทองพูล ทองนาค(2)', '', 'user_swat466@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '39', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(467, 4, 'user_swat467', '$2y$10$RqunRBExDCcHSehftRf2K.sNeM42x20EO05fg.0m8UJbKaMFMXacK', '', 'นางสุมาลี สิงห์ทอง', '', 'user_swat467@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '11', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(468, 4, 'user_swat468', '$2y$10$rNNMgpl.DCvURtjpdX2nRuVyYMj9LHmijN68HXmUu8i/ANUVQ2EUS', '', 'นางวิไล คุณไชย', '', 'user_swat468@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '52', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(469, 4, 'user_swat469', '$2y$10$W5tFFczQJNYNn/70ykhqLeoI2wn15e.79uWr/4uCerQ44ZRxYEIo6', '', 'นางสุภาพ ผสมทรัพย์', '', 'user_swat469@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '76', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(470, 4, 'user_swat470', '$2y$10$rrt1UUV2NF9heWsFYwmXZOhOgwLGUWDDlO27VkFJ4P6CI6VD7j4SS', '', 'นายนิยม สัมฤทธิ์(2)', '', 'user_swat470@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '55', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(471, 4, 'user_swat471', '$2y$10$9/e7j9MAjCd9bPJHcLqRUeKz9x8DLL6KnKgSKqZ1/O6yd0P55CySq', '', 'นางสาวลดา สุจรักษ์', '', 'user_swat471@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '27', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(472, 4, 'user_swat472', '$2y$10$HRjekU87X4YLncgmAtluO.Kkbb.9T8ODXDSxIDKjm/4sQspuhKYh6', '', 'นายสุรพล เจริญตา', '', 'user_swat472@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '108', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(473, 4, 'user_swat473', '$2y$10$1lcvBStUk0u3Wm25bMVRg.8a9VWNEmdAEa1AfR31FulD7.lCI0UoG', '', 'นางพุฒแดง พวงผกา', '', 'user_swat473@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '104', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(474, 4, 'user_swat474', '$2y$10$SsvicFhoqkCkRFDeO.xQWOFImXI3vrKD1HWU5XeDUM3PeqLV89sc.', '', 'นายกิตติพงษ์ สุจรักษ์', '', 'user_swat474@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '198', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(475, 4, 'user_swat475', '$2y$10$KftDFhtBIEEW9TtiufW9Ruz870LEPKOaLQmRaD6I//3qMt1QGCyRW', '', 'นางรอนดาว  จักรสาน', '', 'user_swat475@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '21', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(476, 4, 'user_swat476', '$2y$10$RsF5g5LRb4Mutx7JLg3zteTOvDIHccm3yocBAe4o4khtoaJTscR8y', '', 'นางสุพรรณ์ ดวงแก้ว(2)', '', 'user_swat476@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '123', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(477, 4, 'user_swat477', '$2y$10$PLlHsMztRE4eiN93tygZe.9I612mMG2.9UdkKvN2P.kXFFPQEm8ZW', '', 'นายอุดร ศรีวะสุทธิ์ ***', '', 'user_swat477@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '6', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(478, 4, 'user_swat478', '$2y$10$oF2AYgTH.eYXuFcthwHI2.Pb.RcBiwiek5LSt0yK1tB0HAQ4ZABN.', '', 'นางสุคนธ์ ลาสนธิ', '', 'user_swat478@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '133', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(479, 4, 'user_swat479', '$2y$10$l3QaPFlHAhTuTQ5bsFr.OeG.gKRluLdNrfeb6.y8a.q8vIOVyOmBC', '', 'นางฉวีวรรณ วรรณจันทร์', '', 'user_swat479@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '159', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(480, 4, 'user_swat480', '$2y$10$zCe1Xy04IDAMPr9dL9ke2.mnlz.axOh02pa9dJTxolAJLeuP.N4x2', '', 'นายณัฐพงศ์  เทพช่วย', '', 'user_swat480@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '308', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(481, 4, 'user_swat481', '$2y$10$EsCMK0meC4Y1rhad/x/y8OQZzEIdyREDoqPDJp8IQFFgxA5W5yYxu', '', 'นายวินิจ นันทะสอน', '', 'user_swat481@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '49', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(482, 4, 'user_swat482', '$2y$10$cZiN7wNH9fI4QtqbP.VQWuMpjmrvgM.dgENta14UnZvybywNCznoC', '', 'นายสุภาพ  ทาสีเพชร', '', 'user_swat482@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '40', '2026-10-30 23:01:40', NULL, 'active', '2026-10-30 23:01:40', '2026-10-30 23:01:40'),
(483, 4, 'user_swat483', '$2y$10$dMIipAP7gfVWPa8FUwL/j.MDWnvs.IFG3lJRkd25q8U91WG.3n.7i', '', 'นางสาวเบญจวรรณ วรรณจันทร์', '', 'user_swat483@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '307', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(484, 4, 'user_swat484', '$2y$10$PTHG4fn.9/VVCJteI.ouWuEqCYKn0da2N09aGlvaipO5NHjNTh.Uq', '', 'นายอภิสิทธิ์ จักรสาน', '', 'user_swat484@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(485, 4, 'user_swat485', '$2y$10$O.i.B55yzleb5hNzfZDYSu3KpSPGHiKkuEiNtqpYlnZ7ThC4lUh.G', '', 'นางวันณี แสงขันข์', '', 'user_swat485@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '29', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(486, 4, 'user_swat486', '$2y$10$udqZJkr4oNkO9mG4Kn8pw.qRi/.pJz31MmjJFz1i0IEFyjXRzmhPC', '', 'นายประพจน์  ขันสุปัฏ ', '', 'user_swat486@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '156', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(487, 4, 'user_swat487', '$2y$10$6ckURKBejnkn.6L/MN5LwOFJLBKSsBwKWsNjxGvnYao81dgsMp.BK', '', 'นายประยงค์ โคตรวงค์', '', 'user_swat487@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '41', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(488, 4, 'user_swat488', '$2y$10$c0nhVHJodCaNCyLpfRe..eynjecH6kNfBx4UrVO4Qt/8eqS12OJl6', '', 'นายล้อม สุจรักษ์', '', 'user_swat488@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '28', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(489, 4, 'user_swat489', '$2y$10$LMmS64me3Jqp3UewrYDdX.qaEgx7TSbyDfvsS1P6B9EYpwGAvPfnu', '', 'นาย สุทิน สัญจร', '', 'user_swat489@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '199', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(490, 4, 'user_swat490', '$2y$10$F6moLEQCT6ODhTPpl6oF.ulOlJcJ9bkeMxKKbOvthSuJKCdfyhlX.', '', 'นายอาวร งามฉลวย', '', 'user_swat490@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '31', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(491, 4, 'user_swat491', '$2y$10$kmCttVkHT1HVR9lpEIxJ5ennRVRvtBneRJ0pWQf8dTbO5MXqecFLq', '', 'นายสุดตา ชื่นตา', '', 'user_swat491@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '136', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(492, 4, 'user_swat492', '$2y$10$/gV3uBGJlibKY.pgMzyLcuE321c.w7g3QhLNh9Tq5rqlu.FpAztYW', '', 'นางรัตติกร  สุจะรักษ์', '', 'user_swat492@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '132', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(493, 4, 'user_swat493', '$2y$10$ZGYrMImLvSqrX/gYl9w/SOJHE2IZYddREunP0eN0/ejgP3eT/N1Vi', '', 'นางสาวสมใจ มลศิลป์', '', 'user_swat493@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '164', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(494, 4, 'user_swat494', '$2y$10$dWHrURv9hRMi6hY/mVdr9u4YbgPOQJJC3ElD3SjUAXCbr46IigVnm', '', 'นายสม จักรสาน', '', 'user_swat494@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '21', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(495, 4, 'user_swat495', '$2y$10$30uNmSaQG53J77WKR8FFkOd.XyQmzw..IGoF55mvRdUxOFwV/ihWq', '', 'นายอุทัย มลศิลป์', '', 'user_swat495@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '17', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(496, 4, 'user_swat496', '$2y$10$2x/c0CnfK.FY61CEjnbI7uu1dc5KMWQYgC7194zeLpAYbz69sU2SO', '', 'นายโสพณ บุญอาจ ', '', 'user_swat496@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '1', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(497, 4, 'user_swat497', '$2y$10$mADHgeSoC7r7.hfyro8wiOucJxnNlI/qE7DvuNLQfQakACXrlzx6q', '', 'นางยุพิน  ศรีชนะ', '', 'user_swat497@gmail.com', 21, 37, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '35', '2026-10-30 23:01:41', NULL, 'active', '2026-10-30 23:01:41', '2026-10-30 23:01:41'),
(498, 4, 'user_swat498', '$2y$10$TwNTiT.bWtjkWMhS1vDDguoJO15u0.Dc7raOQevkfXPL/LIjJQAJu', '', 'นายจำรัส บุญทศ', '', 'user_swat498@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:47', NULL, 'active', '2026-10-31 00:11:47', '2026-10-31 00:11:47'),
(499, 4, 'user_swat499', '$2y$10$fiVfaArwtOU0K/sWlcY.j.3zBnENdM0n/8pSMkd3lwe7Rpa8bvxda', '', 'นายสีหา ผ่านเมือง', '', 'user_swat499@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:47', NULL, 'active', '2026-10-31 00:11:47', '2026-10-31 00:11:47'),
(500, 4, 'user_swat500', '$2y$10$F5J8hDKfbE00dLfvRzSq7.de9LOIC2k5HHvAXyPE3YcuD1dBL9QSK', '', 'นางประกายแก้ว ยิ่งยง(ห้อง1)', '', 'user_swat500@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:47', NULL, 'active', '2026-10-31 00:11:47', '2026-10-31 00:11:47'),
(501, 4, 'user_swat501', '$2y$10$QsisCwdC8pjYmdB/EzRfMuCQVCfWcJU47m6ILxflt3Iao.uORd7na', '', 'นางประกายแก้ว ยิ่งยง(ห้อง2)', '', 'user_swat501@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:47', NULL, 'active', '2026-10-31 00:11:47', '2026-10-31 00:11:47'),
(502, 4, 'user_swat502', '$2y$10$cxi6wq5d.I9TPVEzW2MJhem6r90hOlP2omKBnYwAHZv/44n/XTxCS', '', 'นางประกายแก้ว ยิ่งยง (ห้อง3)', '', 'user_swat502@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:47', NULL, 'active', '2026-10-31 00:11:47', '2026-10-31 00:11:47'),
(503, 4, 'user_swat503', '$2y$10$bPC8fdaOp4Rfq2FXMi0gfe./wjsvbVXB5VQerRHVztMYkvPEaVf.m', '', 'นายอิธิพล อินเรืองนาม', '', 'user_swat503@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:47', NULL, 'active', '2026-10-31 00:11:47', '2026-10-31 00:11:47'),
(504, 4, 'user_swat504', '$2y$10$pvPIlDAmifh7VPn5OVb8rOXKHNuXLmUbreKvvAs6f3u6YbAHfM3/e', '', 'นายพรประเสริฐศักดิ์ ทรงสังขาร', '', 'user_swat504@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:47', NULL, 'active', '2026-10-31 00:11:47', '2026-10-31 00:11:47'),
(505, 4, 'user_swat505', '$2y$10$badGhBzUL7ze7.jhsKmJAuaipt.pwYNOVWD742hZ1.B9cAykxDXCm', '', 'นายวิรัส วิลาวรรณ', '', 'user_swat505@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:47', NULL, 'active', '2026-10-31 00:11:47', '2026-10-31 00:11:47'),
(506, 4, 'user_swat506', '$2y$10$8BOaJSZqOIatOCbbKzf0reYSHS2kTaSzg7yJUlnoUcK8sSV02HplW', '', 'นายคำอูน บุญทศ(1)', '', 'user_swat506@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(507, 4, 'user_swat507', '$2y$10$MFaZKcngB.BoACRJHM6DweQz.b.rvVIteR9eH/bjtEa3T1H51paF6', '', 'นายคำอูน บุญทศ(2)', '', 'user_swat507@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(508, 4, 'user_swat508', '$2y$10$yEbRDNRgKgliIxhzIz3Afu1nRtRdFnVrTT9Sz5Rc2d..OG4dJhuF2', '', 'นางสมพร บุญเพิ่ม', '', 'user_swat508@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(509, 4, 'user_swat509', '$2y$10$fdaBmelhWlP0Q1dAAlxwHuBXUu0ZmyyyoeOhHijylEaqATxaNtNpe', '', 'นายคำหมื่น ขุมคำ', '', 'user_swat509@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(510, 4, 'user_swat510', '$2y$10$or7gc1JK8pNfqYCSD3mO8OrATGCi0IpQqlZ.g9MH09/jE/VCUQiq.', '', 'นางพิลัยรัก แสนทวีสุข', '', 'user_swat510@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(511, 4, 'user_swat511', '$2y$10$601/97iZR9VvGF2oYVUZJufH4oeeBdThNuzo2UOK.ozG5/tqMpiHq', '', 'นายหยาย แก้วโมรี', '', 'user_swat511@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(512, 4, 'user_swat512', '$2y$10$yuHuA2TuFr5rKEmNJxFsFOb42Eu16TAT.lCnzZ46SVNT3X5zHRR7W', '', 'นายประภา แก้วโมรี', '', 'user_swat512@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(513, 4, 'user_swat513', '$2y$10$BGlqkb4xCYKzmPyijs7B4OXMoDJ2sLy7fTl7VuDQ6ZrV4xKtn5Nj2', '', 'นางลัดดาวัลย์ แก้วโมรี', '', 'user_swat513@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(514, 4, 'user_swat514', '$2y$10$JB8KyANO5WXqWIEuNsgPKepPJ50h4kuA3rgeDiolR2gbKvSa5OXxe', '', 'นางสมเวียง จากผา', '', 'user_swat514@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(515, 4, 'user_swat515', '$2y$10$gg.YGcNEvZGT045N58RCgu4zrNs/pS2CBjC7wm/U.ROGyxwPTQoa2', '', 'นางอรชร ดีแสน', '', 'user_swat515@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(516, 4, 'user_swat516', '$2y$10$HsqVuX/VTvw.MCoU029wE.kSI1lynr6rI.u5JdxPwpVfrnX0MZHB6', '', 'นางสุมาลี พะโคไช', '', 'user_swat516@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(517, 4, 'user_swat517', '$2y$10$S4dZ/XGNSxn.sJ7kbXuEe.XwEj3ktXP.wmLWkzoqiZMX9xPHkw856', '', 'นางสุขสันต์ พูลพัน', '', 'user_swat517@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(518, 4, 'user_swat518', '$2y$10$UH4Qi7JCfyeSi927uJEfR.CsEsV94o6IpUwLBwIRarlV7KMrLeooq', '', 'นายสายยนต์ วงศาจารย์', '', 'user_swat518@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(519, 4, 'user_swat519', '$2y$10$LG2c2d61VIbfzGsQ1FFvU.zuKf/FDd2G99k0X/GY/8hOjRvHb1YX6', '', 'นายสำเริง ทาสีเพชร', '', 'user_swat519@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(520, 4, 'user_swat520', '$2y$10$0zW9p7xgX9Pnoch7HQMoPu7wuJo4jSPQDKa04oEfHqZdSThpVpVDG', '', 'นางลัดดา  สุระพร', '', 'user_swat520@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(521, 4, 'user_swat521', '$2y$10$L371yip2h/j14vxXylmS5Od6/4x/44gWNTQly49swtSXpQIw5Z6je', '', 'นายมุกดา บุญทิพย์', '', 'user_swat521@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(522, 4, 'user_swat522', '$2y$10$AfvdnK.5QW0CH2hnj2ajm.prfhL5CW.w2T11dC/mV6yJhdN8rb1i2', '', 'นายโสม ผ่านเมือง', '', 'user_swat522@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(523, 4, 'user_swat523', '$2y$10$.c5SXw7GUwAyI3D7y7aDGOwuleqVCwi7Xw.ds2EeMeOghMoIvTAGq', '', 'นายแดนไพร อนุกุลประชา', '', 'user_swat523@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(524, 4, 'user_swat524', '$2y$10$PaIjdiUBUNvbuQJXIWWsKunKf08FFLqWiV9yexQihePy5xfjnvyn.', '', 'นางคำมี จันลาชัย', '', 'user_swat524@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(525, 4, 'user_swat525', '$2y$10$ki46AfsSsfuc/luZUThiy.6Jl8N0MtHxc5klmtwDftRGYF.I2pOlG', '', 'นางสุปนา ผ่านเมือง', '', 'user_swat525@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(526, 4, 'user_swat526', '$2y$10$GtI2PvnxPNcFP4Mxm6wlWeS6k/DVmmvZTdv8WuGaotoRjOhgmMsKi', '', 'นางลำดวน อินเรืองนาม', '', 'user_swat526@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:48', NULL, 'active', '2026-10-31 00:11:48', '2026-10-31 00:11:48'),
(527, 4, 'user_swat527', '$2y$10$VInzdoFTj33WaHdGhi5zE.u4WtycvRJVnkOmhv7Vpd3aapwTgQBje', '', 'นายอนุทัต ทาสีเพชร', '', 'user_swat527@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(528, 4, 'user_swat528', '$2y$10$F/weyVFww6qTYdDpjHeYa.WO9P6cLWw/cwOpuyCtcL7fPuh4zAC3G', '', 'นายคำไข แนวจำปา', '', 'user_swat528@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(529, 4, 'user_swat529', '$2y$10$gzp./bbqRWUYX0MpGrOeaurn0bgsFjeyIBvs31TxAGVt72tiIxSya', '', 'นางสุภรักษ์ ผ่านเมือง', '', 'user_swat529@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(530, 4, 'user_swat530', '$2y$10$DqcWNevdBvINK.5BjWJoOO4bmZ1cF1m/zKqhm1SQT4qe/69V4e91q', '', 'นางเสลียง เคยสนิจ', '', 'user_swat530@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(531, 4, 'user_swat531', '$2y$10$FXVGKxPOzdCYA8hCwdSPLunKep6UruJWKL9XwSgBfcw5wzdkHh7Tq', '', 'นางไพบูลย์ แก้วแสน', '', 'user_swat531@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(532, 4, 'user_swat532', '$2y$10$NXd/UChMW96LLciDao5yI.lrHzmr7FrnfTSxt7XnSeOC/d3KBlIpS', '', 'นายทอง โสมอินทร์', '', 'user_swat532@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(533, 4, 'user_swat533', '$2y$10$CMbJIpc1bjD5TKWXVMwH1uB0czwO9TXQWN655lbiborplLCU0xpAS', '', 'นางอภิวรรณ ทาสีเพชร', '', 'user_swat533@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(534, 4, 'user_swat534', '$2y$10$/8/Z7d4OtFD0hLJIlZ30r.PDtpX8CxLiZ8z8IfX8Xe03NLRIn8hWe', '', 'นางประภาพร แนตรวงศ์', '', 'user_swat534@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(535, 4, 'user_swat535', '$2y$10$JjVOE9IbXV9D4pxJha03..tazyG.EADkXoVSpcJk8rcV8yfrM0v.a', '', 'นายเฉลิม  ขยันหา', '', 'user_swat535@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(536, 4, 'user_swat536', '$2y$10$nHX1C8V2ee0/pn3dRfXx/uSf/drhCgLX1WjGlmJG1VrKv8cRzfCPS', '', 'นางอุบลรัตน์ คำนันดา', '', 'user_swat536@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(537, 4, 'user_swat537', '$2y$10$QNcPVQJhrcDr5y6BOSvDxuYMNiUnN/1.bRpIoELNcmmeRsI/eXNlG', '', 'นายธเนตร  ศรีโย', '', 'user_swat537@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(538, 4, 'user_swat538', '$2y$10$wBMWxMhUR.mLddDf77j1rOryQPvKQjAOs3sw1MKacKiIPM5L6uII.', '', 'นางจันทร์เรือง สรรพศิริมงคล', '', 'user_swat538@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(539, 4, 'user_swat539', '$2y$10$ski0A3/w3ZRiO.y1kLS1ruHBiRS9lMR6pQbOCNynYQjdF8kjgsqAW', '', 'นางลัดดาวัลย์ แก้วโมรี', '', 'user_swat539@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(540, 4, 'user_swat540', '$2y$10$8haO1yjovsmy6vMQ.EdUxuA8NGgj1yQWygkaklKm5mCrVF78RomU2', '', 'นางลอนดอน ทาสีเพชร', '', 'user_swat540@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(541, 4, 'user_swat541', '$2y$10$1Ogcpg3Td0uvsH9PuXy0JuxYob8kezbrfaUqVc3tgWaXrgP5Oy3Zu', '', 'นางจวงจันทร์ ทาสีเพชร', '', 'user_swat541@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(542, 4, 'user_swat542', '$2y$10$HjqqIHp5sczMAYPpj5fUbObIlUBEHFHpPzOKYGR/KEpB4EGvUEKma', '', 'นายธีระศักดิ์  โคตรพันธ', '', 'user_swat542@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(543, 4, 'user_swat543', '$2y$10$WIzN2ROB9zaBru5Cggr/GeZcAEi7cfYxjRik5oB/9FXzDwc54d.eK', '', 'นายวิวัฒน์  ศรีบุญนำ', '', 'user_swat543@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(544, 4, 'user_swat544', '$2y$10$2ydGGpR5UpSdmcuDQNBRm.SVlMLeDUX2VRgS21wtOr0VPMxLbbo.G', '', 'นางมี นามคำ', '', 'user_swat544@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(545, 4, 'user_swat545', '$2y$10$5uQR2h21MiYllDaneboEb.RZeMKKhSMjlhyGLfDUuWg.bolh.AjGi', '', 'นายวินิจฉัย  ไชยสิทธิ์', '', 'user_swat545@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(546, 4, 'user_swat546', '$2y$10$GYY77zuk2D80fTLFm12aMeS3eC9hsLjsVXjC5QE9UYpgEiIW3GlE6', '', 'นายทองจันทร์ นัยจิต', '', 'user_swat546@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:49', NULL, 'active', '2026-10-31 00:11:49', '2026-10-31 00:11:49'),
(547, 4, 'user_swat547', '$2y$10$ya9cI2vTEI8cMEGy0s5cteCx3YPrB2C.slRSke1mlSlftdX/XAkfu', '', 'นายวิเชียร ทองเหลา', '', 'user_swat547@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(548, 4, 'user_swat548', '$2y$10$tBK2FOmNTd9ZZclNfNyUq.T4PfHHfECx0BFwHTxHlq37z9v.fU2N6', '', 'นางสุวรรณ์ ผ่านเมือง ', '', 'user_swat548@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(549, 4, 'user_swat549', '$2y$10$t.1UX8R1Se13RcRvZagLceu01JeV4q1pxw4ru33eixqfRcf5zhte2', '', 'นายคำนัย เนตรวงศ์', '', 'user_swat549@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(550, 4, 'user_swat550', '$2y$10$j0TbfH1sQkACHcteBVmiZepX.WFqj2yxbqCn4lJyxCEUF6XtMaIWW', '', 'นางทองสาย สมคะเณย์', '', 'user_swat550@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(551, 4, 'user_swat551', '$2y$10$sB7ob.UM5p22lhctx8AxNea4udaSbcsXTaxA1vFE.D.JHFQFQrSVq', '', 'นายเรืองเดช นัยจิต', '', 'user_swat551@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(552, 4, 'user_swat552', '$2y$10$fWTYo.v9r1DgF0pi8TlKnukr7CtLv/QTvEc0TBJ879J8b5niQZP.i', '', 'นายสนิท  โพธิ์มาตย์ ', '', 'user_swat552@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(553, 4, 'user_swat553', '$2y$10$vBYSoOkIgSYHjTqoehqyGuCPEFAWUuvMCKXwFm1lII6jSlRFt47fq', '', 'นายบุญอุ้ม โสมอินทร์', '', 'user_swat553@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(554, 4, 'user_swat554', '$2y$10$aEOYT7dPjAnyPSbK0F.SBO.JYwjhV7RoKB8o6ptqi8Qk1D3MrcKr6', '', 'นางสาวลักษณา ผงทอง', '', 'user_swat554@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(555, 4, 'user_swat555', '$2y$10$ZiUln32mWHUOgWj4EXnxMucZJZtYTngy5vK9jr8s6LfTGxHhgPije', '', 'นางเสาวลักษณ์  สมคะเณย์', '', 'user_swat555@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(556, 4, 'user_swat556', '$2y$10$uCuQs4iqcu2nSI4ogNb.Le9V8Yk3vysJes5Vq4JYl7MOxjsXPrOnO', '', 'นางรจนา สุขสุพรรณ', '', 'user_swat556@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(557, 4, 'user_swat557', '$2y$10$oXQA4nAr/gReDTk3x3LK7esUsCpkKD4B0Ac3EdHE8.kZIBKoiTw.6', '', 'นางสาวรุ่งทิวา ไชยสัจ', '', 'user_swat557@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(558, 4, 'user_swat558', '$2y$10$kqngcs.gbkihWJDTcj1eUe0QCL5YhdqCtZtNytdKoPISVXFHke/Xq', '', 'นางคำศรี ศรีสมบูรณ์', '', 'user_swat558@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(559, 4, 'user_swat559', '$2y$10$bRre05Ju/ykCxMoO1Y0UUOfAgSWQ8fiMUMRVIxxarAn.e5rOMkSFi', '', 'นางยอดแก้ว โสมอินทร์ ', '', 'user_swat559@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(560, 4, 'user_swat560', '$2y$10$TNT42JxTluXSGIZevdcpcOkp5CTUTsljcffmGvKmhP/MzCpm24qxW', '', 'นายวินิจฉัย  ไชยสิทธิ์', '', 'user_swat560@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(561, 4, 'user_swat561', '$2y$10$T.Rz1FCK0307LZ640pjmce1qtj4mZ5JXYXBSz64pJ083OTuvk1/fC', '', 'นางสำราญ สุริรักษ์', '', 'user_swat561@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(562, 4, 'user_swat562', '$2y$10$GutaauDOauDp1RhB/UsWQu3nurdEn/1LH8N.05J/XzFtHI6TmL5m2', '', 'นางสาวอำพร นารีรบ', '', 'user_swat562@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(563, 4, 'user_swat563', '$2y$10$nxdOZWMhTgS4yxx3x.v/yuJ2CPFzf.NCg7fnS.iol8/SgSBjBwPLu', '', 'นายสวัสดิ์ นามคำ', '', 'user_swat563@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(564, 4, 'user_swat564', '$2y$10$xYI6EXK6oCZgwcv6wUIcVusFjiu.9KVG/iMVGtHuwTRSeNjkQHdUi', '', 'นางพิชารักษ์ ศรีสมบูรณ์', '', 'user_swat564@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(565, 4, 'user_swat565', '$2y$10$ZPZ354pO0dEqJel5KqtNZe.Zdexn63kwPwQeqcj4TItm2SUhHR9mC', '', 'นางอัมภา แสงฉวี', '', 'user_swat565@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(566, 4, 'user_swat566', '$2y$10$QY6UO/l2OuSNXbx/dZZOkeVzEDiRFEq1XMDCPSbtCSqaPLPvghDQG', '', 'นางจันทร์ นามคำ', '', 'user_swat566@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(567, 4, 'user_swat567', '$2y$10$q1zhHYqzkzBosaOTQUkReunnFP1CmfCysYSXaqyWZS4sPIiYpVU3i', '', 'นางทองศรี คูณสิงห์', '', 'user_swat567@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:50', NULL, 'active', '2026-10-31 00:11:50', '2026-10-31 00:11:50'),
(568, 4, 'user_swat568', '$2y$10$rgLXOgot6bdOAEKrE6SuB.Ycmb1xV2HLIM3e9/9im5EhPzlSZWeei', '', 'นางอรทิน โคตรวันทา', '', 'user_swat568@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(569, 4, 'user_swat569', '$2y$10$MOxzUYhUGnMiH42LxsTVI.NWB7MCillAhh3ecb.SwEtZHwkqo/Keu', '', 'นางอรทัย ชื่นตา', '', 'user_swat569@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(570, 4, 'user_swat570', '$2y$10$Sfz.VKkoHeKjscBHf2fXuO8WkWPVhIUoRGGa63QabAUXQFP77KjQW', '', 'นางประกายแก้ว ยิ่งยง', '', 'user_swat570@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(571, 4, 'user_swat571', '$2y$10$9SWMjr1zxyijJ9C.8c6jkursh8gSNkgYffaOtNK6DvUsPl.YXYLFa', '', 'นางพิสมัย ยิ่งยง', '', 'user_swat571@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(572, 4, 'user_swat572', '$2y$10$2lbNLfSp.xJfLXDnqlSPH.O4R98nFCNu7i/JPk4xLMyJdCJoWqsEO', '', 'นายลำเลียง แสงฉวี', '', 'user_swat572@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(573, 4, 'user_swat573', '$2y$10$0xgjZv7zItd/GZjuUNNb/u1nN/0ZZIN7zniV5laZXN9TNQy7EMOqS', '', 'นายอ่อนตา พรมจันทร์', '', 'user_swat573@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(574, 4, 'user_swat574', '$2y$10$z1f6bt3LNq4/FTkIvhrW.O3YAV8vCUZfqGWcdaHHy45HI.sKJRC3e', '', 'นายแถลง บุญทศ', '', 'user_swat574@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(575, 4, 'user_swat575', '$2y$10$RtSGgGqk.6JMWT1.gjgpLuHPj5T/m6Aog6bg6hxq4Cv5.L044XCq.', '', 'นางธนิกา มาลาพันธ์', '', 'user_swat575@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(576, 4, 'user_swat576', '$2y$10$DojLxRgdJyyj8fXe8N5EvOtGfJBsOkMJbcjOAYqMKPSxrXGBjk93e', '', 'นางบูรนวีย์ จันทร์นัย', '', 'user_swat576@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(577, 4, 'user_swat577', '$2y$10$9oWvtyceUdGLycUW8Nc75.0VnzmwPHc/pi3bAU9WjnE.xavol1Pl2', '', 'นายประดิษฐ์ บรรหารบุตร', '', 'user_swat577@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(578, 4, 'user_swat578', '$2y$10$cPXQVyMDJtfd8OS.XP8kzuFvYzW4A5hYFcynwtubmhafc/LJCViyO', '', 'นางกลอยใจ บรรหารบุตร', '', 'user_swat578@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(579, 4, 'user_swat579', '$2y$10$eEIiUyTCVhZWWn32xULUj.DOv2D53qx19HqNEp2IfHm8FyRTLDoZa', '', 'นายบรรยงค์ ประเชิญสุข', '', 'user_swat579@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(580, 4, 'user_swat580', '$2y$10$XRQMFzRNLeqmxhuXXEa3puOhZgTcAM0oV3tiKS.uusr5ervRDuTya', '', 'นายธวัชชัย กลบกลิ่น', '', 'user_swat580@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(581, 4, 'user_swat581', '$2y$10$tNJXP5mObqgyC33XQdeFtumlVGboA9sAeuiExzqZlvM4A5rqmqNKC', '', 'นางทองจันทร์ สายหมั่น', '', 'user_swat581@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(582, 4, 'user_swat582', '$2y$10$xzRWWBpYJRjPYs.h32PLJOsFPztzLBTbPw5UgJm9kvFcWMyW8VsMi', '', 'นายวิมลศักดิ์ ยิ่งยง', '', 'user_swat582@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(583, 4, 'user_swat583', '$2y$10$KaIPjtKbymyXv3xCY4pEeuFV1GUuWV8g5n6R7VZzeDXDPQ/xhae0q', '', 'นายโอรอน เวชกามา', '', 'user_swat583@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(584, 4, 'user_swat584', '$2y$10$hwLDnB./zS2byZWwM5JrAeoBzb7gNFkNEbchqLHvUA79VxQAr0Ive', '', 'นางอุดมศรี อยู่สุข', '', 'user_swat584@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(585, 4, 'user_swat585', '$2y$10$j.NViduTaNim/Q.sqftEgOZYkX9xpRmRSNq6dbJck5Tpj/s7gmPM2', '', 'นางกร จากผา', '', 'user_swat585@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(586, 4, 'user_swat586', '$2y$10$9ev2APwW/TWQoWXW3GVWYOpcOC70dvkGPhXaLCX1/ZFlJd3OyXZCC', '', 'นายจีระศักดิ์ จารุสาร', '', 'user_swat586@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(587, 4, 'user_swat587', '$2y$10$/U6e4XUq2GEOWdUcDRvXduDGo5U6LZe.M3Ozv8p3S6zXhmFPnzwTC', '', 'นายรุ่งระพี นำพา', '', 'user_swat587@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(588, 4, 'user_swat588', '$2y$10$6ftjQGmFippcTBjDow0SquGIricBZUsSLcaEsH.yIzu4a0D1IuMQi', '', 'นางพิม โสมอินทร์', '', 'user_swat588@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:51', NULL, 'active', '2026-10-31 00:11:51', '2026-10-31 00:11:51'),
(589, 4, 'user_swat589', '$2y$10$Et37bKk/W1BYOQASaXYvV.Wg41eA3cQ6fmo3yCO190Z1PLB07uX.O', '', 'นางไข่มุก พรมนา', '', 'user_swat589@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:52', NULL, 'active', '2026-10-31 00:11:52', '2026-10-31 00:11:52'),
(590, 4, 'user_swat590', '$2y$10$LFrfhHtAipbHqwy7wAOFvOMwBRjU49Ham5F6SXNXDoE5SQd.H6lum', '', 'นายอำนวยผล จากผา(2)', '', 'user_swat590@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:52', NULL, 'active', '2026-10-31 00:11:52', '2026-10-31 00:11:52'),
(591, 4, 'user_swat591', '$2y$10$crVsc3ZW3Qhqnc6IdKuyNuljenbOCAN.F96wRWGSoI4ZyH06cu4He', '', 'นายอำนวยผล จากผา(1)', '', 'user_swat591@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:52', NULL, 'active', '2026-10-31 00:11:52', '2026-10-31 00:11:52'),
(592, 4, 'user_swat592', '$2y$10$8kLyNHKmt/GVKxSxKQZgG.mWNxVoVVrYvyvhA0zLQ3d.l6d7Hb30a', '', 'นายสิริภูมิ จากผา(2)', '', 'user_swat592@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:52', NULL, 'active', '2026-10-31 00:11:52', '2026-10-31 00:11:52'),
(593, 4, 'user_swat593', '$2y$10$aPMAzpUj22g6KSuiAFPDJuimf99.q/Ycz9rraFfOByl.3YUuyRv7K', '', 'นายเสวียง จากผา', '', 'user_swat593@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'm', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:52', NULL, 'active', '2026-10-31 00:11:52', '2026-10-31 00:11:52'),
(594, 4, 'user_swat594', '$2y$10$sJHbfzuL/4.1EXVy7f4WBu73YiBtLNGTNRrtxDcBF8ZD4cetxIFB.', '', 'นางเกตุมะณี คำเสมอ', '', 'user_swat594@gmail.com', 25, 41, 350803, 3508, 35, 0, 0, 0, 'w', NULL, NULL, '111111111', '0999999999', NULL, '', '2026-10-31 00:11:52', NULL, 'active', '2026-10-31 00:11:52', '2026-10-31 00:11:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_meter_infos`
--

CREATE TABLE `user_meter_infos` (
  `meter_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `meternumber` varchar(191) NOT NULL,
  `factory_no` varchar(191) NOT NULL,
  `submeter_name` varchar(191) DEFAULT NULL,
  `meter_address` varchar(191) NOT NULL,
  `undertake_zone_id` bigint(20) UNSIGNED NOT NULL,
  `undertake_subzone_id` bigint(20) UNSIGNED NOT NULL,
  `acceptace_date` date NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `comment` varchar(191) DEFAULT NULL,
  `metertype_id` bigint(20) UNSIGNED NOT NULL,
  `owe_count` int(11) NOT NULL DEFAULT 0,
  `cutmeter` enum('1','0') NOT NULL DEFAULT '0',
  `inv_no_index` int(11) NOT NULL DEFAULT 0,
  `payment_id` int(11) NOT NULL DEFAULT 0,
  `discounttype` int(11) NOT NULL DEFAULT 0,
  `recorder_id` int(11) NOT NULL,
  `last_meter_recording` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `welfare_configs`
--

CREATE TABLE `welfare_configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `min_months_active` int(11) NOT NULL DEFAULT 6,
  `min_total_weight` int(11) NOT NULL DEFAULT 50,
  `default_payout_amount` decimal(12,2) NOT NULL DEFAULT 5000.00,
  `criteria_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `welfare_configs`
--

INSERT INTO `welfare_configs` (`id`, `org_id_fk`, `min_months_active`, `min_total_weight`, `default_payout_amount`, `criteria_description`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 50, 5000.00, NULL, '2026-07-22 04:45:24', '2026-07-22 04:45:24');

-- --------------------------------------------------------

--
-- Table structure for table `welfare_members`
--

CREATE TABLE `welfare_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `membership_type` enum('both','waste_only','contribution_only') NOT NULL DEFAULT 'both',
  `status` enum('active','suspended','terminated') NOT NULL DEFAULT 'active',
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `welfare_payouts`
--

CREATE TABLE `welfare_payouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `deceased_name` varchar(191) NOT NULL,
  `beneficiary_name` varchar(191) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `death_certificate_img` varchar(191) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `approver_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `current_step_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `zone_name` varchar(191) NOT NULL,
  `location` text NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `tambon_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `org_id_fk` bigint(20) UNSIGNED NOT NULL,
  `lat` varchar(191) NOT NULL,
  `long` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`id`, `zone_name`, `location`, `status`, `tambon_id`, `created_at`, `updated_at`, `org_id_fk`, `lat`, `long`) VALUES
(1, 'หมู่ 1', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(2, 'หมู่ 2', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(3, 'หมู่ 3', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(4, 'หมู่ 4', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(5, 'หมู่ 5', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(6, 'หมู่ 6', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(7, 'หมู่ 7', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(8, 'หมู่ 8', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(9, 'หมู่ 9', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(10, 'หมู่ 10', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(11, 'หมู่ 11', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(12, 'หมู่ 12', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(13, 'หมู่ 13', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(14, 'หมู่ 14', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(15, 'หมู่ 15', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(16, 'หมู่ 16', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(17, 'หมู่ 17', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(18, 'หมู่ 18', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(19, 'หมู่ 19', 'ตำบลห้องแซง อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350805, NULL, NULL, 1, '', ''),
(20, 'หมู่ 8 บ้านหนองสมบูรณ์', 'ตำบลสวาท อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350803, NULL, NULL, 4, '13.749030', '100.473291'),
(21, 'หมู่ 7 บ้านพรหมนิยม', 'ตำบลสวาท อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350803, '2026-10-28 22:01:26', '2026-10-28 22:01:26', 4, '13.749030', '100.473291'),
(23, 'หมู่ 15 บ้านนาจาน', 'ตำบลสวาท อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350803, '2026-10-28 22:01:26', '2026-10-28 22:01:26', 4, '13.743887', '100.488424'),
(25, 'หมู่ 16 บ้านหนองสมบูรณ์', 'ตำบลสวาท อำเภอเลิงนกทา จังหวัดยโสธร', 'active', 350803, '2026-10-28 22:01:26', '2026-10-28 22:01:26', 4, '13.743887', '100.488424');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`acc_user_id`);

--
-- Indexes for table `annual_trashs`
--
ALTER TABLE `annual_trashs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `annual_trashs_bin_code_unique` (`bin_code`),
  ADD KEY `annual_trashs_user_id_foreign` (`user_id`),
  ADD KEY `annual_trashs_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `annual_trash_members`
--
ALTER TABLE `annual_trash_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `annual_trash_members_user_id_foreign` (`user_id`),
  ADD KEY `annual_trash_members_recorder_id_foreign` (`recorder_id`);

--
-- Indexes for table `annual_trash_payments`
--
ALTER TABLE `annual_trash_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `annual_trash_payments_wbs_id_pay_mon_pay_yr_unique` (`wbs_id`,`pay_mon`,`pay_yr`),
  ADD KEY `annual_trash_payments_staff_id_foreign` (`staff_id`),
  ADD KEY `annual_trash_payments_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `annual_trash_payrate_permonth`
--
ALTER TABLE `annual_trash_payrate_permonth`
  ADD PRIMARY KEY (`id`),
  ADD KEY `annual_trash_payrate_permonth_kp_usergroup_idfk_foreign` (`kp_usergroup_idfk`),
  ADD KEY `annual_trash_payrate_permonth_budgetyear_idfk_foreign` (`budgetyear_idfk`),
  ADD KEY `annual_trash_payrate_permonth_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `annual_trash_subscriptions`
--
ALTER TABLE `annual_trash_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `annual_trash_subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `annual_trash_subscriptions_waste_bin_id_foreign` (`waste_bin_id`),
  ADD KEY `annual_trash_subscriptions_payrate_permonth_id_fk_foreign` (`payrate_permonth_id_fk`),
  ADD KEY `annual_trash_subscriptions_foreignorg_id_fk` (`org_id_fk`);

--
-- Indexes for table `approval_workflows`
--
ALTER TABLE `approval_workflows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approval_workflows_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `approval_workflow_steps`
--
ALTER TABLE `approval_workflow_steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approval_workflow_steps_workflow_id_foreign` (`workflow_id`);

--
-- Indexes for table `budget_year`
--
ALTER TABLE `budget_year`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_id_fk` (`org_id_fk`);

--
-- Indexes for table `compost_bins`
--
ALTER TABLE `compost_bins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `compost_bins_bin_code_unique` (`bin_code`),
  ADD KEY `compost_bins_user_id_foreign` (`user_id`);

--
-- Indexes for table `cp_sensor_data`
--
ALTER TABLE `cp_sensor_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `districts_province_id_foreign` (`province_id`);

--
-- Indexes for table `emission_factors`
--
ALTER TABLE `emission_factors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `foodwaste_bins`
--
ALTER TABLE `foodwaste_bins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `foodwaste_bins_bin_code_unique` (`bin_code`),
  ADD KEY `foodwaste_bins_org_id_fk_index` (`org_id_fk`),
  ADD KEY `foodwaste_bins_u_pref_id_fk_index` (`u_pref_id_fk`);

--
-- Indexes for table `foodwaste_bin_stocks`
--
ALTER TABLE `foodwaste_bin_stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `waste_bins_bin_code_unique` (`bin_code`);

--
-- Indexes for table `foodwaste_compost_batches`
--
ALTER TABLE `foodwaste_compost_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foodwaste_compost_batches_user_id_foreign` (`user_id`);

--
-- Indexes for table `foodwaste_issue_reports`
--
ALTER TABLE `foodwaste_issue_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foodwaste_issue_reports_user_id_foreign` (`user_id`),
  ADD KEY `foodwaste_issue_reports_batch_id_foreign` (`batch_id`),
  ADD KEY `foodwaste_issue_reports_issue_type_id_foreign` (`issue_type_id`),
  ADD KEY `foodwaste_issue_reports_assigned_staff_id_foreign` (`assigned_staff_id`);

--
-- Indexes for table `foodwaste_issue_types`
--
ALTER TABLE `foodwaste_issue_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foodwaste_issue_types_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `foodwaste_reward_settings`
--
ALTER TABLE `foodwaste_reward_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `foodwaste_reward_settings_key_unique` (`key`);

--
-- Indexes for table `foodwaste_transactions`
--
ALTER TABLE `foodwaste_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foodwaste_transactions_fw_pref_id_fk_foreign` (`fw_pref_id_fk`),
  ADD KEY `foodwaste_transactions_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `foodwaste_user_bin_iotbox_matchings`
--
ALTER TABLE `foodwaste_user_bin_iotbox_matchings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_matching_combination` (`fw_user_id_fk`,`bin_id_fk`,`iotbox_id_fk`),
  ADD KEY `foodwaste_user_bin_iotbox_matchings_bin_id_fk_foreign` (`bin_id_fk`),
  ADD KEY `foodwaste_user_bin_iotbox_matchings_iotbox_id_fk_foreign` (`iotbox_id_fk`);

--
-- Indexes for table `foodwaste_user_preferences`
--
ALTER TABLE `foodwaste_user_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foodwaste_user_preferences_user_id_foreign` (`user_id`);

--
-- Indexes for table `foodwaste_waste_logs`
--
ALTER TABLE `foodwaste_waste_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foodwaste_waste_logs_user_id_foreign` (`user_id`),
  ADD KEY `foodwaste_waste_logs_batch_id_foreign` (`batch_id`);

--
-- Indexes for table `foodwast_iotboxes`
--
ALTER TABLE `foodwast_iotboxes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `foodwast_iotboxes_iotbox_code_unique` (`iotbox_code`);

--
-- Indexes for table `foodwast_meal_logs`
--
ALTER TABLE `foodwast_meal_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foodwast_meal_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `food_waste_accounts`
--
ALTER TABLE `food_waste_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `food_waste_accounts_user_id_foreign` (`user_id`);

--
-- Indexes for table `invoice_period`
--
ALTER TABLE `invoice_period`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_period_budgetyear_id_foreign` (`budgetyear_id`),
  ADD KEY `invoice_period_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `inv_categories`
--
ALTER TABLE `inv_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_categories_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `approval_workflow_id_Fk` (`approval_workflow_id`);

--
-- Indexes for table `inv_hazard_levels`
--
ALTER TABLE `inv_hazard_levels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_hazard_levels_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `inv_items`
--
ALTER TABLE `inv_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_items_inv_category_id_fk_foreign` (`inv_category_id_fk`);

--
-- Indexes for table `inv_item_details`
--
ALTER TABLE `inv_item_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_item_details_inv_item_id_fk_foreign` (`inv_item_id_fk`),
  ADD KEY `inv_item_details_supplier_id_fk_foreign` (`supplier_id_fk`),
  ADD KEY `inv_item_details_received_by_foreign` (`received_by`),
  ADD KEY `inv_item_details_location_id_fk_foreign` (`location_id_fk`);

--
-- Indexes for table `inv_item_hazard`
--
ALTER TABLE `inv_item_hazard`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_item_hazard_inv_item_id_foreign` (`inv_item_id`),
  ADD KEY `inv_item_hazard_inv_hazard_level_id_foreign` (`inv_hazard_level_id`);

--
-- Indexes for table `inv_locations`
--
ALTER TABLE `inv_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inv_transactions`
--
ALTER TABLE `inv_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_transactions_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `inv_transactions_requester_id_fk_foreign` (`requester_id_fk`),
  ADD KEY `inv_transactions_inv_item_id_fk_foreign` (`inv_item_id_fk`);

--
-- Indexes for table `inv_transaction_approvals`
--
ALTER TABLE `inv_transaction_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_transaction_approvals_approval_workflow_id_foreign` (`approval_workflow_id`);

--
-- Indexes for table `inv_units`
--
ALTER TABLE `inv_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inv_units_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `issue_types`
--
ALTER TABLE `issue_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kiosks`
--
ALTER TABLE `kiosks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kiosks_current_user_id_foreign` (`current_user_id`),
  ADD KEY `kiosks_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `kiosk_matches`
--
ALTER TABLE `kiosk_matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kiosk_matches_user_id_foreign` (`user_id`),
  ADD KEY `kiosk_matches_kiosk_id_index` (`kiosk_id`);

--
-- Indexes for table `kp_bank_accounts`
--
ALTER TABLE `kp_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_bank_accounts_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `kp_bank_accounts_user_id_foreign` (`user_id`);

--
-- Indexes for table `kp_bulk_sales`
--
ALTER TABLE `kp_bulk_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_bulk_sales_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `kp_bulk_sales_recorder_id_foreign` (`recorder_id`);

--
-- Indexes for table `kp_bulk_sale_details`
--
ALTER TABLE `kp_bulk_sale_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_bulk_sale_details_bulk_sale_id_foreign` (`bulk_sale_id`),
  ADD KEY `kp_bulk_sale_details_kp_tbank_item_id_foreign` (`kp_tbank_item_id`);

--
-- Indexes for table `kp_kiosk_unknown_items`
--
ALTER TABLE `kp_kiosk_unknown_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_kiosk_unknown_items_kp_purchase_trans_id_index` (`kp_purchase_trans_id`),
  ADD KEY `kp_kiosk_unknown_items_user_id_fk_index` (`user_id_fk`),
  ADD KEY `kp_kiosk_unknown_items_status_index` (`status`);

--
-- Indexes for table `kp_money_requests`
--
ALTER TABLE `kp_money_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_money_requests_user_id_foreign` (`user_id`),
  ADD KEY `kp_money_requests_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `kp_money_requests_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `kp_point_transfers`
--
ALTER TABLE `kp_point_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_point_transfers_sender_id_foreign` (`sender_id`),
  ADD KEY `kp_point_transfers_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `kp_purchase_routes`
--
ALTER TABLE `kp_purchase_routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_purchase_routes_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `kp_purchase_route_zones`
--
ALTER TABLE `kp_purchase_route_zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_purchase_route_zones_kp_purchase_route_id_foreign` (`kp_purchase_route_id`),
  ADD KEY `kp_purchase_route_zones_zone_id_foreign` (`zone_id`);

--
-- Indexes for table `kp_purchase_shops`
--
ALTER TABLE `kp_purchase_shops`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kp_purchase_shops_shop_name_unique` (`shop_name`),
  ADD KEY `kp_purchase_shops_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `kp_purchase_transactions`
--
ALTER TABLE `kp_purchase_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kp_purchase_transactions_kp_u_trans_no_unique` (`kp_u_trans_no`),
  ADD KEY `kp_purchase_transactions_kp_user_w_pref_id_fk_foreign` (`user_id`),
  ADD KEY `kp_purchase_transactions_recorder_id_foreign` (`recorder_id`),
  ADD KEY `kp_purchase_transactions_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `kp_purchase_transactions_kiosk_id_foreign` (`kiosk_id_fk`);

--
-- Indexes for table `kp_purchase_transactions_details`
--
ALTER TABLE `kp_purchase_transactions_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_purchase_transactions_details_kp_purchase_trans_id_foreign` (`kp_purchase_trans_id`),
  ADD KEY `kp_purchase_transactions_details_kp_recycle_item_id_foreign` (`kp_recycle_item_id`),
  ADD KEY `kp_purchase_transactions_details_items_pricepoint_id_foreign` (`items_pricepoint_id`),
  ADD KEY `kp_purchase_transactions_details_recorder_id_foreign` (`recorder_id`),
  ADD KEY `kp_purchase_transactions_details_kp_units_idfk_foreign` (`kp_units_idfk`),
  ADD KEY `org_idf_fk` (`org_id_fk`);

--
-- Indexes for table `kp_recycle_bank_members`
--
ALTER TABLE `kp_recycle_bank_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_recycle_bank_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `kp_sell_details`
--
ALTER TABLE `kp_sell_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_sell_details_kp_sell_trans_id_foreign` (`kp_sell_trans_id`),
  ADD KEY `kp_sell_details_kp_recycle_item_id_foreign` (`kp_recycle_item_id`);

--
-- Indexes for table `kp_sell_transactions`
--
ALTER TABLE `kp_sell_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kp_sell_transactions_kp_u_trans_no_unique` (`kp_u_trans_no`),
  ADD KEY `kp_sell_transactions_shop_id_fk_foreign` (`shop_id_fk`),
  ADD KEY `kp_sell_transactions_recorder_id_foreign` (`recorder_id`);

--
-- Indexes for table `kp_settings`
--
ALTER TABLE `kp_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kp_settings_org_id_fk_key_name_unique` (`org_id_fk`,`key_name`);

--
-- Indexes for table `kp_shop_categories`
--
ALTER TABLE `kp_shop_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kp_shop_orders`
--
ALTER TABLE `kp_shop_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_shop_orders_user_wpref_id_foreign` (`user_wpref_id`),
  ADD KEY `kp_shop_orders_recorder_id_foreign` (`recorder_id`);

--
-- Indexes for table `kp_shop_order_details`
--
ALTER TABLE `kp_shop_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_shop_order_details_kp_shop_order_id_foreign` (`kp_shop_order_id`),
  ADD KEY `kp_shop_order_details_kp_shop_product_id_foreign` (`kp_shop_product_id`);

--
-- Indexes for table `kp_shop_products`
--
ALTER TABLE `kp_shop_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kp_shop_products_product_code_unique` (`product_code`),
  ADD KEY `kp_shop_products_kp_shop_category_id_foreign` (`kp_shop_category_id`);

--
-- Indexes for table `kp_tbank_items`
--
ALTER TABLE `kp_tbank_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_tbank_items_kp_items_group_idfk_foreign` (`kp_items_group_idfk`),
  ADD KEY `kp_tbank_items_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `kp_tbank_items_ef_id_fk_foreign` (`ef_id_fk`),
  ADD KEY `kp_tbank_items_unit_bank_idfk_foreign` (`unit_bank_idfk`),
  ADD KEY `kp_tbank_items_unit_kiosk_idfk_foreign` (`unit_kiosk_idfk`);

--
-- Indexes for table `kp_tbank_items_groups`
--
ALTER TABLE `kp_tbank_items_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `org_id_fk_eref` (`org_id_fk`);

--
-- Indexes for table `kp_tbank_items_pricepoint`
--
ALTER TABLE `kp_tbank_items_pricepoint`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_tbank_items_pricepoint_kp_items_idfk_foreign` (`kp_items_idfk`),
  ADD KEY `kp_tbank_items_pricepoint_kp_units_idfk_foreign` (`kp_units_idfk`),
  ADD KEY `kp_tbank_items_pricepoint_recorder_id_foreign` (`recorder_id`),
  ADD KEY `org_id_fk_ref` (`org_id_fk`);

--
-- Indexes for table `kp_tbank_items_units`
--
ALTER TABLE `kp_tbank_items_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_tbank_items_units_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `kp_usergroups`
--
ALTER TABLE `kp_usergroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kp_user_waste_preferences`
--
ALTER TABLE `kp_user_waste_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_user_waste_preferences_zone_id_foreign` (`zone_id`),
  ADD KEY `kp_user_waste_preferences_subzone_id_foreign` (`subzone_id`),
  ADD KEY `kp_user_waste_preferences_org_id_foreign` (`org_id_fk`),
  ADD KEY `kp_user_waste_preferences_user_id_unique` (`user_id`) USING BTREE;

--
-- Indexes for table `kp_waste_bank_transactions`
--
ALTER TABLE `kp_waste_bank_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kp_waste_bank_transactions_receipt_code_unique` (`receipt_code`),
  ADD KEY `kp_waste_bank_transactions_user_id_foreign` (`user_id`),
  ADD KEY `kp_waste_bank_transactions_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `kp_waste_groups`
--
ALTER TABLE `kp_waste_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kp_waste_groups_name_unique` (`name`);

--
-- Indexes for table `kp_waste_types`
--
ALTER TABLE `kp_waste_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kp_waste_types_name_unique` (`name`),
  ADD KEY `kp_waste_types_waste_group_id_foreign` (`waste_group_id`);

--
-- Indexes for table `kp_welfare_payouts`
--
ALTER TABLE `kp_welfare_payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_welfare_payouts_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `kp_welfare_payouts_requester_id_foreign` (`requester_id`),
  ADD KEY `kp_welfare_payouts_approver_id_foreign` (`approver_id`);

--
-- Indexes for table `local_foods`
--
ALTER TABLE `local_foods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `local_foods_menu_name_unique` (`menu_name`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `machines_machine_id_unique` (`machine_id`),
  ADD KEY `machines_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `machines_current_user_active_id_foreign` (`current_user_active_id`);

--
-- Indexes for table `meal_items`
--
ALTER TABLE `meal_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foodwaste_meal_items_meal_log_id_foreign` (`meal_log_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `organizations_org_code_unique` (`org_type_id`),
  ADD KEY `organizations_org_tambon_id_fk_foreign` (`org_tambon_id_fk`),
  ADD KEY `organizations_org_district_id_fk_foreign` (`org_district_id_fk`),
  ADD KEY `organizations_org_province_id_fk_foreign` (`org_province_id_fk`);

--
-- Indexes for table `organization_types`
--
ALTER TABLE `organization_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `org_accounts`
--
ALTER TABLE `org_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `org_invoice_sequences`
--
ALTER TABLE `org_invoice_sequences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `org_invoice_sequences_org_id_fk_year_unique` (`org_id_fk`,`year`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recycle_transactions`
--
ALTER TABLE `recycle_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recycle_transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sequence_number`
--
ALTER TABLE `sequence_number`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `smart_utility_configs`
--
ALTER TABLE `smart_utility_configs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `smart_utility_configs_org_id_fk_index` (`org_id_fk`);

--
-- Indexes for table `smart_utility_items`
--
ALTER TABLE `smart_utility_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `smart_utility_items_org_id_fk_index` (`org_id_fk`),
  ADD KEY `smart_utility_items_module_index` (`module`),
  ADD KEY `smart_utility_items_item_code_index` (`item_code`);

--
-- Indexes for table `smart_utility_shapes`
--
ALTER TABLE `smart_utility_shapes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `smart_utility_shapes_org_id_fk_index` (`org_id_fk`),
  ADD KEY `smart_utility_shapes_module_index` (`module`);

--
-- Indexes for table `smart_utility_tickets`
--
ALTER TABLE `smart_utility_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `smart_utility_tickets_ticket_code_unique` (`ticket_code`),
  ADD KEY `smart_utility_tickets_org_id_fk_index` (`org_id_fk`),
  ADD KEY `smart_utility_tickets_module_index` (`module`),
  ADD KEY `smart_utility_tickets_ref_code_index` (`ref_code`),
  ADD KEY `smart_utility_tickets_status_index` (`status`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `staffs_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `user_id_ref` (`user_id`);

--
-- Indexes for table `subzones`
--
ALTER TABLE `subzones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subzones_zone_id_foreign` (`zone_id`),
  ADD KEY `subzones_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_modules`
--
ALTER TABLE `system_modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_modules_system_type_unique` (`system_type`);

--
-- Indexes for table `tabwater_devices`
--
ALTER TABLE `tabwater_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tabwater_devices_station_id_foreign` (`station_id`);

--
-- Indexes for table `tabwater_device_logs`
--
ALTER TABLE `tabwater_device_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tabwater_device_logs_device_id_foreign` (`device_id`),
  ADD KEY `tabwater_device_logs_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `tabwater_pipelines`
--
ALTER TABLE `tabwater_pipelines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tabwater_pipelines_station_id_foreign` (`station_id`),
  ADD KEY `tabwater_pipelines_from_node_id_foreign` (`from_node_id`),
  ADD KEY `tabwater_pipelines_to_node_id_foreign` (`to_node_id`);

--
-- Indexes for table `tabwater_pipe_nodes`
--
ALTER TABLE `tabwater_pipe_nodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tabwater_pipe_nodes_station_id_foreign` (`station_id`);

--
-- Indexes for table `tabwater_stations`
--
ALTER TABLE `tabwater_stations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tabwater_station_staffs`
--
ALTER TABLE `tabwater_station_staffs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tabwater_station_staffs_station_id_staff_id_unique` (`station_id`,`staff_id`),
  ADD KEY `tabwater_station_staffs_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `tambons`
--
ALTER TABLE `tambons`
  ADD PRIMARY KEY (`id`,`district_id`),
  ADD KEY `tambons_district_id_foreign` (`district_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_details_waste_bank_transaction_id_foreign` (`waste_bank_transaction_id`),
  ADD KEY `transaction_details_waste_type_id_foreign` (`waste_type_id`);

--
-- Indexes for table `tw_acc_transactions`
--
ALTER TABLE `tw_acc_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tw_acc_transactions_cashier_foreign` (`cashier`);

--
-- Indexes for table `tw_cutmeter`
--
ALTER TABLE `tw_cutmeter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tw_cutmeter_meter_id_fk_foreign` (`meter_id_fk`);

--
-- Indexes for table `tw_invoice`
--
ALTER TABLE `tw_invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tw_invoice_meter_id_fk_foreign` (`meter_id_fk`),
  ADD KEY `tw_invoice_inv_period_id_fk_foreign` (`inv_period_id_fk`),
  ADD KEY `tw_invoice_acc_trans_id_fk_foreign` (`acc_trans_id_fk`),
  ADD KEY `tw_invoice_recorder_id_foreign` (`recorder_id`),
  ADD KEY `tw_invoice_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `tw_invoice_history`
--
ALTER TABLE `tw_invoice_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tw_invoice_meter_id_fk_foreign` (`meter_id_fk`),
  ADD KEY `tw_invoice_inv_period_id_fk_foreign` (`inv_period_id_fk`),
  ADD KEY `tw_invoice_acc_trans_id_fk_foreign` (`acc_trans_id_fk`),
  ADD KEY `tw_invoice_recorder_id_foreign` (`recorder_id`),
  ADD KEY `tw_invoice_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `tw_invoice_temp`
--
ALTER TABLE `tw_invoice_temp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tw_invoice_temp_meter_id_fk_foreign` (`meter_id_fk`),
  ADD KEY `tw_invoice_temp_inv_period_id_fk_foreign` (`inv_period_id_fk`),
  ADD KEY `tw_invoice_temp_acc_trans_id_fk_foreign` (`acc_trans_id_fk`),
  ADD KEY `tw_invoice_temp_recorder_id_foreign` (`recorder_id`);

--
-- Indexes for table `tw_meter_infos`
--
ALTER TABLE `tw_meter_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tw_meter_infos_user_id_foreign` (`user_id`),
  ADD KEY `tw_meter_infos_undertake_zone_id_foreign` (`undertake_zone_id`),
  ADD KEY `tw_meter_infos_undertake_subzone_id_foreign` (`undertake_subzone_id`),
  ADD KEY `tw_meter_infos_metertype_id_foreign` (`metertype_id`);

--
-- Indexes for table `tw_meter_types`
--
ALTER TABLE `tw_meter_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tw_meter_types_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `tw_meter_type_rate_configs`
--
ALTER TABLE `tw_meter_type_rate_configs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tw_meter_type_rate_configs_meter_type_id_fk_foreign` (`meter_type_id_fk`),
  ADD KEY `tw_meter_type_rate_configs_pricing_type_id_foreign` (`pricing_type_id`),
  ADD KEY `tw_meter_type_rate_configs_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `tw_meter_type_rate_tiers`
--
ALTER TABLE `tw_meter_type_rate_tiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tier_order_unique_per_config` (`meter_type_rate_config_id`,`tier_order`);

--
-- Indexes for table `tw_notifies`
--
ALTER TABLE `tw_notifies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tw_notifies_user_id_foreign` (`user_id`);

--
-- Indexes for table `tw_notify_staff`
--
ALTER TABLE `tw_notify_staff`
  ADD PRIMARY KEY (`notify_id`,`user_id`),
  ADD KEY `tw_notify_staff_user_id_foreign` (`user_id`);

--
-- Indexes for table `tw_pricing_types`
--
ALTER TABLE `tw_pricing_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tw_pricing_types_name_unique` (`name`);

--
-- Indexes for table `undertaker_subzones`
--
ALTER TABLE `undertaker_subzones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subzone_fk` (`subzone_id`),
  ADD KEY `cxx` (`twman_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `users_zone_id_foreign` (`zone_id`),
  ADD KEY `users_subzone_id_foreign` (`subzone_id`),
  ADD KEY `users_tambon_code_foreign` (`tambon_code`),
  ADD KEY `users_district_code_foreign` (`district_code`),
  ADD KEY `users_province_code_foreign` (`province_code`);

--
-- Indexes for table `user_meter_infos`
--
ALTER TABLE `user_meter_infos`
  ADD PRIMARY KEY (`meter_id`),
  ADD KEY `user_meter_infos_user_id_foreign` (`user_id`);

--
-- Indexes for table `welfare_configs`
--
ALTER TABLE `welfare_configs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kp_welfare_configs_org_id_fk_foreign` (`org_id_fk`);

--
-- Indexes for table `welfare_members`
--
ALTER TABLE `welfare_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `welfare_members_org_id_fk_user_id_unique` (`org_id_fk`,`user_id`),
  ADD KEY `welfare_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `welfare_payouts`
--
ALTER TABLE `welfare_payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `welfare_payouts_org_id_fk_foreign` (`org_id_fk`),
  ADD KEY `welfare_payouts_user_id_foreign` (`user_id`),
  ADD KEY `welfare_payouts_requester_id_foreign` (`requester_id`),
  ADD KEY `welfare_payouts_approver_id_foreign` (`approver_id`),
  ADD KEY `welfare_payouts_current_step_id_foreign` (`current_step_id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zones_tambon_id_foreign` (`tambon_id`),
  ADD KEY `zones_org_id_fk_foreign` (`org_id_fk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `annual_trashs`
--
ALTER TABLE `annual_trashs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `annual_trash_members`
--
ALTER TABLE `annual_trash_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `annual_trash_payments`
--
ALTER TABLE `annual_trash_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `annual_trash_payrate_permonth`
--
ALTER TABLE `annual_trash_payrate_permonth`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `annual_trash_subscriptions`
--
ALTER TABLE `annual_trash_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `approval_workflows`
--
ALTER TABLE `approval_workflows`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `approval_workflow_steps`
--
ALTER TABLE `approval_workflow_steps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `budget_year`
--
ALTER TABLE `budget_year`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `compost_bins`
--
ALTER TABLE `compost_bins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cp_sensor_data`
--
ALTER TABLE `cp_sensor_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emission_factors`
--
ALTER TABLE `emission_factors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foodwaste_bins`
--
ALTER TABLE `foodwaste_bins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `foodwaste_bin_stocks`
--
ALTER TABLE `foodwaste_bin_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `foodwaste_compost_batches`
--
ALTER TABLE `foodwaste_compost_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `foodwaste_issue_reports`
--
ALTER TABLE `foodwaste_issue_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `foodwaste_issue_types`
--
ALTER TABLE `foodwaste_issue_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `foodwaste_reward_settings`
--
ALTER TABLE `foodwaste_reward_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foodwaste_transactions`
--
ALTER TABLE `foodwaste_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foodwaste_user_bin_iotbox_matchings`
--
ALTER TABLE `foodwaste_user_bin_iotbox_matchings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foodwaste_user_preferences`
--
ALTER TABLE `foodwaste_user_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `foodwaste_waste_logs`
--
ALTER TABLE `foodwaste_waste_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `foodwast_iotboxes`
--
ALTER TABLE `foodwast_iotboxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foodwast_meal_logs`
--
ALTER TABLE `foodwast_meal_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `food_waste_accounts`
--
ALTER TABLE `food_waste_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_period`
--
ALTER TABLE `invoice_period`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `inv_categories`
--
ALTER TABLE `inv_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inv_hazard_levels`
--
ALTER TABLE `inv_hazard_levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_items`
--
ALTER TABLE `inv_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `inv_item_details`
--
ALTER TABLE `inv_item_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inv_item_hazard`
--
ALTER TABLE `inv_item_hazard`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inv_locations`
--
ALTER TABLE `inv_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inv_transactions`
--
ALTER TABLE `inv_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inv_transaction_approvals`
--
ALTER TABLE `inv_transaction_approvals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `inv_units`
--
ALTER TABLE `inv_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `issue_types`
--
ALTER TABLE `issue_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `kiosks`
--
ALTER TABLE `kiosks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kiosk_matches`
--
ALTER TABLE `kiosk_matches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_bank_accounts`
--
ALTER TABLE `kp_bank_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_bulk_sales`
--
ALTER TABLE `kp_bulk_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_bulk_sale_details`
--
ALTER TABLE `kp_bulk_sale_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_kiosk_unknown_items`
--
ALTER TABLE `kp_kiosk_unknown_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kp_money_requests`
--
ALTER TABLE `kp_money_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_point_transfers`
--
ALTER TABLE `kp_point_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_purchase_routes`
--
ALTER TABLE `kp_purchase_routes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kp_purchase_route_zones`
--
ALTER TABLE `kp_purchase_route_zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kp_purchase_shops`
--
ALTER TABLE `kp_purchase_shops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kp_purchase_transactions`
--
ALTER TABLE `kp_purchase_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kp_purchase_transactions_details`
--
ALTER TABLE `kp_purchase_transactions_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kp_recycle_bank_members`
--
ALTER TABLE `kp_recycle_bank_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `kp_sell_details`
--
ALTER TABLE `kp_sell_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kp_sell_transactions`
--
ALTER TABLE `kp_sell_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_settings`
--
ALTER TABLE `kp_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_shop_categories`
--
ALTER TABLE `kp_shop_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_shop_orders`
--
ALTER TABLE `kp_shop_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_shop_order_details`
--
ALTER TABLE `kp_shop_order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_shop_products`
--
ALTER TABLE `kp_shop_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_tbank_items`
--
ALTER TABLE `kp_tbank_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `kp_tbank_items_groups`
--
ALTER TABLE `kp_tbank_items_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kp_tbank_items_pricepoint`
--
ALTER TABLE `kp_tbank_items_pricepoint`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `kp_tbank_items_units`
--
ALTER TABLE `kp_tbank_items_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kp_usergroups`
--
ALTER TABLE `kp_usergroups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kp_user_waste_preferences`
--
ALTER TABLE `kp_user_waste_preferences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kp_waste_bank_transactions`
--
ALTER TABLE `kp_waste_bank_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_waste_groups`
--
ALTER TABLE `kp_waste_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_waste_types`
--
ALTER TABLE `kp_waste_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kp_welfare_payouts`
--
ALTER TABLE `kp_welfare_payouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `local_foods`
--
ALTER TABLE `local_foods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meal_items`
--
ALTER TABLE `meal_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `organization_types`
--
ALTER TABLE `organization_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `org_accounts`
--
ALTER TABLE `org_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `org_invoice_sequences`
--
ALTER TABLE `org_invoice_sequences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recycle_transactions`
--
ALTER TABLE `recycle_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `smart_utility_configs`
--
ALTER TABLE `smart_utility_configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `smart_utility_items`
--
ALTER TABLE `smart_utility_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `smart_utility_shapes`
--
ALTER TABLE `smart_utility_shapes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `smart_utility_tickets`
--
ALTER TABLE `smart_utility_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staffs`
--
ALTER TABLE `staffs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `subzones`
--
ALTER TABLE `subzones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_modules`
--
ALTER TABLE `system_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tabwater_devices`
--
ALTER TABLE `tabwater_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabwater_device_logs`
--
ALTER TABLE `tabwater_device_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabwater_pipelines`
--
ALTER TABLE `tabwater_pipelines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabwater_pipe_nodes`
--
ALTER TABLE `tabwater_pipe_nodes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tabwater_stations`
--
ALTER TABLE `tabwater_stations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tabwater_station_staffs`
--
ALTER TABLE `tabwater_station_staffs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tw_acc_transactions`
--
ALTER TABLE `tw_acc_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=607;

--
-- AUTO_INCREMENT for table `tw_cutmeter`
--
ALTER TABLE `tw_cutmeter`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tw_invoice`
--
ALTER TABLE `tw_invoice`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=593;

--
-- AUTO_INCREMENT for table `tw_invoice_history`
--
ALTER TABLE `tw_invoice_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tw_invoice_temp`
--
ALTER TABLE `tw_invoice_temp`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tw_meter_infos`
--
ALTER TABLE `tw_meter_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=593;

--
-- AUTO_INCREMENT for table `tw_meter_types`
--
ALTER TABLE `tw_meter_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tw_meter_type_rate_configs`
--
ALTER TABLE `tw_meter_type_rate_configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tw_meter_type_rate_tiers`
--
ALTER TABLE `tw_meter_type_rate_tiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tw_notifies`
--
ALTER TABLE `tw_notifies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tw_pricing_types`
--
ALTER TABLE `tw_pricing_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `undertaker_subzones`
--
ALTER TABLE `undertaker_subzones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=598;

--
-- AUTO_INCREMENT for table `welfare_configs`
--
ALTER TABLE `welfare_configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `welfare_members`
--
ALTER TABLE `welfare_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `welfare_payouts`
--
ALTER TABLE `welfare_payouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_acc_user_id_foreign` FOREIGN KEY (`acc_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `annual_trashs`
--
ALTER TABLE `annual_trashs`
  ADD CONSTRAINT `annual_trashs_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `annual_trashs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `annual_trash_members`
--
ALTER TABLE `annual_trash_members`
  ADD CONSTRAINT `annual_trash_members_recorder_id_foreign` FOREIGN KEY (`recorder_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `annual_trash_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `annual_trash_payments`
--
ALTER TABLE `annual_trash_payments`
  ADD CONSTRAINT `annual_trash_payments_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `annual_trash_payments_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `annual_trash_payments_wbs_id_foreign` FOREIGN KEY (`wbs_id`) REFERENCES `annual_trash_subscriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `annual_trash_payrate_permonth`
--
ALTER TABLE `annual_trash_payrate_permonth`
  ADD CONSTRAINT `annual_trash_payrate_permonth_budgetyear_idfk_foreign` FOREIGN KEY (`budgetyear_idfk`) REFERENCES `budget_year` (`id`),
  ADD CONSTRAINT `annual_trash_payrate_permonth_kp_usergroup_idfk_foreign` FOREIGN KEY (`kp_usergroup_idfk`) REFERENCES `kp_usergroups` (`id`),
  ADD CONSTRAINT `annual_trash_payrate_permonth_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `annual_trash_subscriptions`
--
ALTER TABLE `annual_trash_subscriptions`
  ADD CONSTRAINT `annual_trash_subscriptions_foreignorg_id_fk` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `annual_trash_subscriptions_payrate_permonth_id_fk_foreign` FOREIGN KEY (`payrate_permonth_id_fk`) REFERENCES `annual_trash_payrate_permonth` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `annual_trash_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `annual_trash_subscriptions_waste_bin_id_foreign` FOREIGN KEY (`waste_bin_id`) REFERENCES `annual_trashs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `budget_year`
--
ALTER TABLE `budget_year`
  ADD CONSTRAINT `org_id_fk` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `compost_bins`
--
ALTER TABLE `compost_bins`
  ADD CONSTRAINT `compost_bins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `foodwaste_compost_batches`
--
ALTER TABLE `foodwaste_compost_batches`
  ADD CONSTRAINT `foodwaste_compost_batches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `foodwaste_issue_reports`
--
ALTER TABLE `foodwaste_issue_reports`
  ADD CONSTRAINT `foodwaste_issue_reports_assigned_staff_id_foreign` FOREIGN KEY (`assigned_staff_id`) REFERENCES `staffs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `foodwaste_issue_reports_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `foodwaste_compost_batches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `foodwaste_issue_reports_issue_type_id_foreign` FOREIGN KEY (`issue_type_id`) REFERENCES `foodwaste_issue_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `foodwaste_issue_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `foodwaste_issue_types`
--
ALTER TABLE `foodwaste_issue_types`
  ADD CONSTRAINT `foodwaste_issue_types_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `foodwaste_transactions`
--
ALTER TABLE `foodwaste_transactions`
  ADD CONSTRAINT `foodwaste_transactions_fw_pref_id_fk_foreign` FOREIGN KEY (`fw_pref_id_fk`) REFERENCES `foodwaste_user_preferences` (`id`),
  ADD CONSTRAINT `foodwaste_transactions_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `foodwaste_user_bin_iotbox_matchings`
--
ALTER TABLE `foodwaste_user_bin_iotbox_matchings`
  ADD CONSTRAINT `foodwaste_user_bin_iotbox_matchings_bin_id_fk_foreign` FOREIGN KEY (`bin_id_fk`) REFERENCES `foodwaste_bins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `foodwaste_user_bin_iotbox_matchings_fw_user_id_fk_foreign` FOREIGN KEY (`fw_user_id_fk`) REFERENCES `kp_user_waste_preferences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `foodwaste_user_bin_iotbox_matchings_iotbox_id_fk_foreign` FOREIGN KEY (`iotbox_id_fk`) REFERENCES `foodwast_iotboxes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `foodwaste_user_preferences`
--
ALTER TABLE `foodwaste_user_preferences`
  ADD CONSTRAINT `foodwaste_user_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `foodwaste_waste_logs`
--
ALTER TABLE `foodwaste_waste_logs`
  ADD CONSTRAINT `foodwaste_waste_logs_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `foodwaste_compost_batches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `foodwaste_waste_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `foodwast_meal_logs`
--
ALTER TABLE `foodwast_meal_logs`
  ADD CONSTRAINT `foodwast_meal_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `food_waste_accounts`
--
ALTER TABLE `food_waste_accounts`
  ADD CONSTRAINT `food_waste_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_period`
--
ALTER TABLE `invoice_period`
  ADD CONSTRAINT `invoice_period_budgetyear_id_foreign` FOREIGN KEY (`budgetyear_id`) REFERENCES `budget_year` (`id`),
  ADD CONSTRAINT `invoice_period_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inv_categories`
--
ALTER TABLE `inv_categories`
  ADD CONSTRAINT `approval_workflow_id_Fk` FOREIGN KEY (`approval_workflow_id`) REFERENCES `approval_workflows` (`id`),
  ADD CONSTRAINT `inv_categories_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inv_hazard_levels`
--
ALTER TABLE `inv_hazard_levels`
  ADD CONSTRAINT `inv_hazard_levels_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inv_items`
--
ALTER TABLE `inv_items`
  ADD CONSTRAINT `inv_items_inv_category_id_fk_foreign` FOREIGN KEY (`inv_category_id_fk`) REFERENCES `inv_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inv_item_hazard`
--
ALTER TABLE `inv_item_hazard`
  ADD CONSTRAINT `inv_item_hazard_inv_hazard_level_id_foreign` FOREIGN KEY (`inv_hazard_level_id`) REFERENCES `inv_hazard_levels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inv_item_hazard_inv_item_id_foreign` FOREIGN KEY (`inv_item_id`) REFERENCES `inv_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inv_units`
--
ALTER TABLE `inv_units`
  ADD CONSTRAINT `inv_units_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kiosks`
--
ALTER TABLE `kiosks`
  ADD CONSTRAINT `kiosks_current_user_id_foreign` FOREIGN KEY (`current_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kiosks_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kiosk_matches`
--
ALTER TABLE `kiosk_matches`
  ADD CONSTRAINT `kiosk_matches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kp_bulk_sales`
--
ALTER TABLE `kp_bulk_sales`
  ADD CONSTRAINT `kp_bulk_sales_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `kp_bulk_sales_recorder_id_foreign` FOREIGN KEY (`recorder_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `kp_bulk_sale_details`
--
ALTER TABLE `kp_bulk_sale_details`
  ADD CONSTRAINT `kp_bulk_sale_details_bulk_sale_id_foreign` FOREIGN KEY (`bulk_sale_id`) REFERENCES `kp_bulk_sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kp_bulk_sale_details_kp_tbank_item_id_foreign` FOREIGN KEY (`kp_tbank_item_id`) REFERENCES `kp_tbank_items` (`id`);

--
-- Constraints for table `kp_money_requests`
--
ALTER TABLE `kp_money_requests`
  ADD CONSTRAINT `kp_money_requests_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `kp_money_requests_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kp_money_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kp_point_transfers`
--
ALTER TABLE `kp_point_transfers`
  ADD CONSTRAINT `kp_point_transfers_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `kp_point_transfers_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `kp_purchase_shops`
--
ALTER TABLE `kp_purchase_shops`
  ADD CONSTRAINT `kp_purchase_shops_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kp_purchase_transactions`
--
ALTER TABLE `kp_purchase_transactions`
  ADD CONSTRAINT `kp_purchase_transactions_kiosk_id_foreign` FOREIGN KEY (`kiosk_id_fk`) REFERENCES `kiosks` (`id`),
  ADD CONSTRAINT `kp_purchase_transactions_kp_user_w_pref_id_fk_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kp_purchase_transactions_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_fk` FOREIGN KEY (`recorder_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `kp_purchase_transactions_details`
--
ALTER TABLE `kp_purchase_transactions_details`
  ADD CONSTRAINT `kp_purchase_transactions_details_items_pricepoint_id_foreign` FOREIGN KEY (`items_pricepoint_id`) REFERENCES `kp_tbank_items_pricepoint` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kp_purchase_transactions_details_kp_purchase_trans_id_foreign` FOREIGN KEY (`kp_purchase_trans_id`) REFERENCES `kp_purchase_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kp_purchase_transactions_details_kp_recycle_item_id_foreign` FOREIGN KEY (`kp_recycle_item_id`) REFERENCES `kp_tbank_items` (`id`),
  ADD CONSTRAINT `kp_purchase_transactions_details_kp_units_idfk_foreign` FOREIGN KEY (`kp_units_idfk`) REFERENCES `kp_tbank_items_units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kp_purchase_transactions_details_recorder_id_foreign` FOREIGN KEY (`recorder_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `org_idf_fk` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `kp_recycle_bank_members`
--
ALTER TABLE `kp_recycle_bank_members`
  ADD CONSTRAINT `kp_recycle_bank_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kp_sell_details`
--
ALTER TABLE `kp_sell_details`
  ADD CONSTRAINT `kp_sell_details_kp_recycle_item_id_foreign` FOREIGN KEY (`kp_recycle_item_id`) REFERENCES `kp_tbank_items` (`id`),
  ADD CONSTRAINT `kp_sell_details_kp_sell_trans_id_foreign` FOREIGN KEY (`kp_sell_trans_id`) REFERENCES `kp_sell_transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kp_sell_transactions`
--
ALTER TABLE `kp_sell_transactions`
  ADD CONSTRAINT `kp_sell_transactions_shop_id_fk_foreign` FOREIGN KEY (`shop_id_fk`) REFERENCES `kp_purchase_shops` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kp_shop_orders`
--
ALTER TABLE `kp_shop_orders`
  ADD CONSTRAINT `kp_shop_orders_user_wpref_id_foreign` FOREIGN KEY (`user_wpref_id`) REFERENCES `kp_user_waste_preferences` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kp_shop_order_details`
--
ALTER TABLE `kp_shop_order_details`
  ADD CONSTRAINT `kp_shop_order_details_kp_shop_order_id_foreign` FOREIGN KEY (`kp_shop_order_id`) REFERENCES `kp_shop_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kp_shop_order_details_kp_shop_product_id_foreign` FOREIGN KEY (`kp_shop_product_id`) REFERENCES `kp_shop_products` (`id`);

--
-- Constraints for table `kp_shop_products`
--
ALTER TABLE `kp_shop_products`
  ADD CONSTRAINT `kp_shop_products_kp_shop_category_id_foreign` FOREIGN KEY (`kp_shop_category_id`) REFERENCES `kp_shop_categories` (`id`);

--
-- Constraints for table `kp_tbank_items`
--
ALTER TABLE `kp_tbank_items`
  ADD CONSTRAINT `kp_tbank_items_ef_id_fk_foreign` FOREIGN KEY (`ef_id_fk`) REFERENCES `emission_factors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kp_tbank_items_kp_items_group_idfk_foreign` FOREIGN KEY (`kp_items_group_idfk`) REFERENCES `kp_tbank_items_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kp_tbank_items_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kp_tbank_items_unit_bank_idfk_foreign` FOREIGN KEY (`unit_bank_idfk`) REFERENCES `kp_tbank_items_units` (`id`),
  ADD CONSTRAINT `kp_tbank_items_unit_kiosk_idfk_foreign` FOREIGN KEY (`unit_kiosk_idfk`) REFERENCES `kp_tbank_items_units` (`id`);

--
-- Constraints for table `kp_tbank_items_groups`
--
ALTER TABLE `kp_tbank_items_groups`
  ADD CONSTRAINT `org_id_fk_eref` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `kp_tbank_items_pricepoint`
--
ALTER TABLE `kp_tbank_items_pricepoint`
  ADD CONSTRAINT `kp_tbank_items_pricepoint_kp_items_idfk_foreign` FOREIGN KEY (`kp_items_idfk`) REFERENCES `kp_tbank_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kp_tbank_items_pricepoint_kp_units_idfk_foreign` FOREIGN KEY (`kp_units_idfk`) REFERENCES `kp_tbank_items_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `org_id_fk_ref` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `kp_tbank_items_units`
--
ALTER TABLE `kp_tbank_items_units`
  ADD CONSTRAINT `kp_tbank_items_units_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kp_user_waste_preferences`
--
ALTER TABLE `kp_user_waste_preferences`
  ADD CONSTRAINT `kp_user_waste_preferences_org_id_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `kp_user_waste_preferences_subzone_id_foreign` FOREIGN KEY (`subzone_id`) REFERENCES `subzones` (`id`),
  ADD CONSTRAINT `kp_user_waste_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kp_user_waste_preferences_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`);

--
-- Constraints for table `kp_waste_bank_transactions`
--
ALTER TABLE `kp_waste_bank_transactions`
  ADD CONSTRAINT `kp_waste_bank_transactions_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kp_waste_bank_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kp_waste_types`
--
ALTER TABLE `kp_waste_types`
  ADD CONSTRAINT `kp_waste_types_waste_group_id_foreign` FOREIGN KEY (`waste_group_id`) REFERENCES `kp_waste_groups` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `kp_welfare_payouts`
--
ALTER TABLE `kp_welfare_payouts`
  ADD CONSTRAINT `kp_welfare_payouts_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `kp_welfare_payouts_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `kp_welfare_payouts_requester_id_foreign` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `machines`
--
ALTER TABLE `machines`
  ADD CONSTRAINT `machines_current_user_active_id_foreign` FOREIGN KEY (`current_user_active_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `machines_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `meal_items`
--
ALTER TABLE `meal_items`
  ADD CONSTRAINT `foodwaste_meal_items_meal_log_id_foreign` FOREIGN KEY (`meal_log_id`) REFERENCES `foodwast_meal_logs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `org_type_id_ref` FOREIGN KEY (`org_type_id`) REFERENCES `organization_types` (`id`),
  ADD CONSTRAINT `organizations_org_district_id_fk_foreign` FOREIGN KEY (`org_district_id_fk`) REFERENCES `districts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `organizations_org_province_id_fk_foreign` FOREIGN KEY (`org_province_id_fk`) REFERENCES `provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `organizations_org_tambon_id_fk_foreign` FOREIGN KEY (`org_tambon_id_fk`) REFERENCES `tambons` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `recycle_transactions`
--
ALTER TABLE `recycle_transactions`
  ADD CONSTRAINT `recycle_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staffs`
--
ALTER TABLE `staffs`
  ADD CONSTRAINT `staffs_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_id_ref` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `subzones`
--
ALTER TABLE `subzones`
  ADD CONSTRAINT `subzones_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subzones_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tambons`
--
ALTER TABLE `tambons`
  ADD CONSTRAINT `tambons_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_waste_bank_transaction_id_foreign` FOREIGN KEY (`waste_bank_transaction_id`) REFERENCES `kp_waste_bank_transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_waste_type_id_foreign` FOREIGN KEY (`waste_type_id`) REFERENCES `kp_waste_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `undertaker_subzones`
--
ALTER TABLE `undertaker_subzones`
  ADD CONSTRAINT `cxx` FOREIGN KEY (`twman_id`) REFERENCES `staffs` (`id`),
  ADD CONSTRAINT `subzone_fk` FOREIGN KEY (`subzone_id`) REFERENCES `subzones` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user_fk_district` FOREIGN KEY (`district_code`) REFERENCES `districts` (`id`),
  ADD CONSTRAINT `user_fk_org` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `user_fk_province` FOREIGN KEY (`province_code`) REFERENCES `provinces` (`id`),
  ADD CONSTRAINT `user_fk_subzone` FOREIGN KEY (`subzone_id`) REFERENCES `subzones` (`id`),
  ADD CONSTRAINT `user_fk_tambon` FOREIGN KEY (`tambon_code`) REFERENCES `tambons` (`id`);

--
-- Constraints for table `user_meter_infos`
--
ALTER TABLE `user_meter_infos`
  ADD CONSTRAINT `user_meter_infos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `welfare_configs`
--
ALTER TABLE `welfare_configs`
  ADD CONSTRAINT `kp_welfare_configs_org_id_fk_foreign` FOREIGN KEY (`org_id_fk`) REFERENCES `organizations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
