-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql312.infinityfree.com
-- Generation Time: Mar 26, 2026 at 10:36 PM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41015595_449`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$n73cksAXkqrrurKHwh6xjO.TsA/.eY0sJqd3UVEDe4F0wF/oHhEY2', 'admin@lifeblood.com', 'System Administrator', '2026-01-29 23:38:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(10) NOT NULL,
  `fk_donor_id` int(10) DEFAULT NULL,
  `fk_doctor_id` int(10) DEFAULT NULL,
  `appointment_time` datetime DEFAULT NULL,
  `status` enum('Pending','Confirmed','Cancelled','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `fk_donor_id`, `fk_doctor_id`, `appointment_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 1, '2026-03-13 17:00:00', 'Completed', '2026-03-05 04:10:28', '2026-03-05 04:19:21'),
(2, 5, 1, '2026-03-31 00:00:00', 'Confirmed', '2026-03-25 21:51:44', '2026-03-26 20:17:25');

-- --------------------------------------------------------

--
-- Table structure for table `blood_distributions`
--

CREATE TABLE `blood_distributions` (
  `id` int(10) NOT NULL,
  `fk_patient_id` int(10) NOT NULL,
  `fk_blood_group_id` int(10) NOT NULL,
  `request_unit` int(10) NOT NULL,
  `approved_unit` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_distributions`
--

INSERT INTO `blood_distributions` (`id`, `fk_patient_id`, `fk_blood_group_id`, `request_unit`, `approved_unit`, `created_at`, `updated_at`) VALUES
(1, 12345, 1, 500, 500, '2026-03-05 04:25:08', '2026-03-05 04:26:14'),
(2, 12345, 7, 40, 40, '2026-03-05 04:27:42', '2026-03-05 04:28:14'),
(3, 12346, 3, 250, NULL, '2026-03-06 06:17:24', '2026-03-06 06:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `blood_groups`
--

CREATE TABLE `blood_groups` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `code` varchar(15) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blood_groups`
--

INSERT INTO `blood_groups` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'A Positive', 'A+', 'Blood type A with Rh factor positive', NULL, NULL),
(2, 'A Negative', 'A-', 'Blood type A with Rh factor negative', NULL, NULL),
(3, 'B Positive', 'B+', 'Blood type B with Rh factor positive', NULL, NULL),
(4, 'B Negative', 'B-', 'Blood type B with Rh factor negative', NULL, NULL),
(5, 'AB Positive', 'AB+', 'Blood type AB with Rh factor positive (universal recipient)', NULL, NULL),
(6, 'AB Negative', 'AB-', 'Blood type AB with Rh factor negative', NULL, NULL),
(7, 'O Positive', 'O+', 'Blood type O with Rh factor positive (most common)', NULL, NULL),
(8, 'O Negative', 'O-', 'Blood type O with Rh factor negative (universal donor)', NULL, NULL),
(9, 'Bombay', 'HH_PHENOTYPE', 'The Bombay blood group, also known as the hh phenotype, is a rare blood type first discovered in Mumbai in 1952.', '2026-03-05 03:26:21', '2026-03-05 03:26:21');

-- --------------------------------------------------------

--
-- Table structure for table `blood_inventory`
--

CREATE TABLE `blood_inventory` (
  `id` int(10) NOT NULL,
  `fk_blood_group_id` int(10) NOT NULL,
  `fk_donor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(10) DEFAULT 0,
  `collection_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_inventory`
--

INSERT INTO `blood_inventory` (`id`, `fk_blood_group_id`, `fk_donor_id`, `quantity`, `collection_date`, `expiry_date`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 750, '2026-03-04', '2026-04-14', '2026-03-05 04:03:26', '2026-03-06 06:17:24'),
(2, 7, 4, 1000, '2026-03-04', '2026-04-14', '2026-03-05 04:06:06', '2026-03-05 04:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `address`, `mobile`, `created_at`, `updated_at`) VALUES
(1, 'Mr Jhon Doe', 'Cardiff', '017-282-382821', '2026-03-05 04:18:14', '2026-03-05 04:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fk_blood_group_id` smallint(5) UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `country` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `address_line_1` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `hemoglobin_level` decimal(5,2) DEFAULT NULL,
  `systolic` int(11) DEFAULT NULL,
  `diastolic` int(11) DEFAULT NULL,
  `last_donation_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `fk_blood_group_id`, `name`, `country`, `mobile`, `email`, `password`, `birthdate`, `address_line_1`, `address_line_2`, `hemoglobin_level`, `systolic`, `diastolic`, `last_donation_date`, `created_at`, `updated_at`) VALUES
(1, 3, 'Kazi Tanzim Rahman', 'UK', '+447944708885', 'kazijunior03@gmail.com', '', '2004-02-04', 'Treforest', 'sadsa', '14.00', 120, 80, NULL, NULL, '2026-02-04 11:27:39'),
(2, 1, 'Allun King', 'UK', '+447944709997', 'allun.king@gmail.com', NULL, '2004-05-20', 'Newport', 'Wales', '16.00', 115, 85, NULL, NULL, NULL),
(3, 3, 'ABDULLAH AL MAMUN', 'UK', '+44 7438248399', '30113225@students.southwales.ac.uk', NULL, '2002-06-30', 'CARDIFF', NULL, '14.00', 125, 87, NULL, NULL, NULL),
(4, 7, 'Nowshin Karim', 'Bangladesh', '01873839034', 'karimnowshin34@gmail.com', NULL, '2004-08-27', NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-20 01:14:14', '2026-02-20 01:14:14'),
(5, 3, 'Sheikh Obydullah', 'Bangladesh', '+8801525789841', 'sheikh.obydullah@gmail.com', '$2y$12$qAeYyf82Gnc6yKjjxgzwAeExvyCZ2x9oCrwy/2DI25THSLUhs5bZO', '1990-01-14', '46 Laura Street', NULL, '15.00', 110, 90, '2026-02-04', '2026-03-05 03:43:19', '2026-03-06 10:05:02'),
(6, 4, 'Arnov', 'UK', '0178192222', 'arnov@me.com', '$2y$12$UHZVsUShtYM.FqUnf2uOCeB2fwm7F3hrLt5gcM21LbZ/1LqVl4Aj.', '1995-03-02', NULL, NULL, '16.00', NULL, NULL, NULL, '2026-03-06 10:06:58', '2026-03-06 10:06:58'),
(7, 2, 'ASDSAD', 'UK', '234234', 'dsadsd@sadsd.com', NULL, '1990-03-04', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-06 10:31:34', '2026-03-06 10:31:34'),
(8, 1, 'dfwqe', 'UK', '3432423432434', 'sds@saajj.com', NULL, '1990-03-01', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-06 10:33:28', '2026-03-06 10:33:28'),
(9, NULL, 'test', 'qewq', '123213', 'test@sadhsadh.com', '$2y$12$tOsM94q8iBZge4.wmSOsrOxPaoMuhZsbLs3EPmPMRRXgmquBF1/Ou', '1990-03-01', NULL, NULL, NULL, NULL, NULL, '2026-03-03', '2026-03-06 10:39:39', '2026-03-06 10:39:39');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `medical_history` longtext DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `last_blood_taking_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `email`, `medical_history`, `address`, `last_blood_taking_date`, `created_at`, `updated_at`) VALUES
(2132, 'Full Name', 'SADS@ED.COM', 'Medical History', 'Address', '2026-03-06', '2026-03-06 08:53:19', '2026-03-06 08:53:19'),
(12345, 'Sheikh Obydullah', 'sheikh.obydullah@gmail.com', 'Sheikh Obydullah is a normal guy..but he sometimes procrasinates for no reason...', 'House-18, Magura-1, Jessore, Bangladesh', '2026-03-01', '2026-03-05 04:24:16', '2026-03-05 04:24:16'),
(12346, 'sada', 'saa@as.com', 'Medical History', '23423432', '2026-03-06', '2026-03-06 06:17:24', '2026-03-06 09:01:12');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('DuqxaaV8oFz0ok4hlGp4FG16OAeu1z6FKGd2SfkR', NULL, '103.119.23.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidUhZbVhyWW44ZEhCRVpFWnBGVlVzcklzazhhd3VaWE5Wb21oZ2VpdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vYmxvb2QuZ3JlYXQtc2l0ZS5uZXQvP2k9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1774578957),
('ikZrHKSNisfoDw3flGP2O9e8V3CdXd7fjRXlr5eQ', NULL, '103.119.23.148', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:148.0) Gecko/20100101 Firefox/148.0', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiSTVsNFZ2SVluUmpVNTdZREQydmMwakVMR042bXdXeldhQUJLSXdBdiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cHM6Ly9ibG9vZC5ncmVhdC1zaXRlLm5ldC9hcHBvaW50bWVudHMvMiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI4OiJodHRwczovL2Jsb29kLmdyZWF0LXNpdGUubmV0Ijt9czo1MjoibG9naW5fZG9ub3JfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O3M6ODoiYWRtaW5faWQiO2k6MTtzOjE1OiJhZG1pbl9sb2dnZWRfaW4iO2I6MTtzOjEwOiJhZG1pbl9uYW1lIjtzOjIwOiJTeXN0ZW0gQWRtaW5pc3RyYXRvciI7czoxNDoiYWRtaW5fdXNlcm5hbWUiO3M6NToiYWRtaW4iO30=', 1774531415);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_donor_id` (`fk_donor_id`),
  ADD KEY `fk_doctor_id` (`fk_doctor_id`);

--
-- Indexes for table `blood_distributions`
--
ALTER TABLE `blood_distributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_patient` (`fk_patient_id`),
  ADD KEY `fk_blood_group` (`fk_blood_group_id`);

--
-- Indexes for table `blood_groups`
--
ALTER TABLE `blood_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blood_inventory`
--
ALTER TABLE `blood_inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_blood_group_id` (`fk_blood_group_id`,`expiry_date`),
  ADD KEY `blood_inventory_fk_donor_id_foreign` (`fk_donor_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donors_fk_blood_group_id_foreign` (`fk_blood_group_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blood_distributions`
--
ALTER TABLE `blood_distributions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blood_groups`
--
ALTER TABLE `blood_groups`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `blood_inventory`
--
ALTER TABLE `blood_inventory`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12347;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_inventory`
--
ALTER TABLE `blood_inventory`
  ADD CONSTRAINT `blood_inventory_fk_donor_id_foreign` FOREIGN KEY (`fk_donor_id`) REFERENCES `donors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `donors`
--
ALTER TABLE `donors`
  ADD CONSTRAINT `donors_fk_blood_group_id_foreign` FOREIGN KEY (`fk_blood_group_id`) REFERENCES `blood_groups` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
