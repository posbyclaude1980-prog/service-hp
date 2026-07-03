-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for service_hp
CREATE DATABASE IF NOT EXISTS `service_hp` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `service_hp`;

-- Dumping structure for table service_hp.activity_logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `log_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_branch_id_foreign` (`branch_id`),
  KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  KEY `activity_logs_user_id_created_at_index` (`user_id`,`created_at`),
  CONSTRAINT `activity_logs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.activity_logs: ~0 rows (approximately)

-- Dumping structure for table service_hp.branches
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_main` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_uuid_unique` (`uuid`),
  UNIQUE KEY `branches_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.branches: ~0 rows (approximately)
INSERT INTO `branches` (`id`, `uuid`, `code`, `name`, `phone`, `email`, `address`, `city`, `is_active`, `is_main`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '66cefbde-9d3d-49e3-8590-e5c7ec9d62f0', 'HQ', 'Cabang Utama', '081234567890', 'cabang@servicehp.test', 'Jl. Contoh No. 1', NULL, 1, 0, '2026-06-30 21:41:18', '2026-06-30 21:41:18', NULL);

-- Dumping structure for table service_hp.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_name_unique` (`name`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.brands: ~0 rows (approximately)
INSERT INTO `brands` (`id`, `name`, `slug`, `country`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Samsung', 'samsung', NULL, 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(2, 'Xiaomi', 'xiaomi', NULL, 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(3, 'OPPO', 'oppo', NULL, 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(4, 'Vivo', 'vivo', NULL, 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(5, 'Apple', 'apple', NULL, 1, '2026-06-30 21:41:21', '2026-07-01 07:11:58', '2026-07-01 07:11:58'),
	(6, 'Realme 2', 'realme', NULL, 1, '2026-06-30 21:41:21', '2026-07-01 07:11:47', NULL);

-- Dumping structure for table service_hp.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.cache: ~0 rows (approximately)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('sistem-service-hp-cache-app_settings', 'O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:0:{}s:28:"\0*\0escapeWhenCastingToString";b:0;}', 1814453767);

-- Dumping structure for table service_hp.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.cache_locks: ~0 rows (approximately)

-- Dumping structure for table service_hp.company_profiles
CREATE TABLE IF NOT EXISTS `company_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `legal_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trade_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npwp` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_prefix` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INV',
  `warranty_policy` text COLLATE utf8mb4_unicode_ci,
  `invoice_footer` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `company_profiles_branch_id_unique` (`branch_id`),
  CONSTRAINT `company_profiles_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.company_profiles: ~0 rows (approximately)

-- Dumping structure for table service_hp.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_branch_id_code_unique` (`branch_id`,`code`),
  UNIQUE KEY `customers_uuid_unique` (`uuid`),
  KEY `customers_phone_index` (`phone`),
  CONSTRAINT `customers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.customers: ~0 rows (approximately)
INSERT INTO `customers` (`id`, `uuid`, `branch_id`, `code`, `name`, `phone`, `email`, `address`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '50aefccc-1d37-483c-8a60-14313cb04726', 1, 'CUST-001', 'Budi Santoso', '081211111111', NULL, NULL, NULL, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(2, '915d1bb2-8791-4eef-8990-d0d62aa65b95', 1, 'CUST-002', 'Siti Aminah', '081222222222', NULL, NULL, NULL, '2026-06-30 21:41:22', '2026-06-30 21:41:22', NULL),
	(3, '09befae5-f584-451e-9d40-a0400ee1a394', 1, 'CUST-003', 'Andi Wijaya', '081233333333', NULL, NULL, NULL, '2026-06-30 21:41:22', '2026-06-30 21:41:22', NULL);

-- Dumping structure for table service_hp.devices
CREATE TABLE IF NOT EXISTS `devices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `device_model_id` bigint unsigned DEFAULT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imei` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `devices_uuid_unique` (`uuid`),
  KEY `devices_customer_id_foreign` (`customer_id`),
  KEY `devices_brand_id_foreign` (`brand_id`),
  KEY `devices_device_model_id_foreign` (`device_model_id`),
  KEY `devices_imei_index` (`imei`),
  CONSTRAINT `devices_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `devices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `devices_device_model_id_foreign` FOREIGN KEY (`device_model_id`) REFERENCES `device_models` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.devices: ~0 rows (approximately)

-- Dumping structure for table service_hp.device_models
CREATE TABLE IF NOT EXISTS `device_models` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specifications` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_models_brand_id_name_unique` (`brand_id`,`name`),
  CONSTRAINT `device_models_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.device_models: ~0 rows (approximately)
INSERT INTO `device_models` (`id`, `brand_id`, `name`, `year`, `specifications`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 'Samsung A Series', NULL, NULL, 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(2, 2, 'Xiaomi A Series', NULL, NULL, 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(3, 3, 'OPPO A Series', NULL, NULL, 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(4, 4, 'Vivo A Series', NULL, NULL, 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(5, 5, 'Apple A Series', NULL, NULL, 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(6, 6, 'Realme A Series', NULL, NULL, 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL);

-- Dumping structure for table service_hp.expenses
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `expense_category_id` bigint unsigned NOT NULL,
  `payment_method_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `expense_date` date NOT NULL,
  `receipt_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expenses_uuid_unique` (`uuid`),
  KEY `expenses_expense_category_id_foreign` (`expense_category_id`),
  KEY `expenses_payment_method_id_foreign` (`payment_method_id`),
  KEY `expenses_user_id_foreign` (`user_id`),
  KEY `expenses_branch_id_expense_date_index` (`branch_id`,`expense_date`),
  CONSTRAINT `expenses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `expenses_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.expenses: ~0 rows (approximately)

-- Dumping structure for table service_hp.expense_categories
CREATE TABLE IF NOT EXISTS `expense_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expense_categories_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.expense_categories: ~0 rows (approximately)

-- Dumping structure for table service_hp.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table service_hp.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.jobs: ~0 rows (approximately)

-- Dumping structure for table service_hp.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.job_batches: ~0 rows (approximately)

-- Dumping structure for table service_hp.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.migrations: ~1 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_07_01_000001_create_branches_table', 1),
	(5, '2026_07_01_000002_create_roles_table', 1),
	(6, '2026_07_01_000003_create_permissions_table', 1),
	(7, '2026_07_01_000004_create_permission_role_table', 1),
	(8, '2026_07_01_000005_create_company_profiles_table', 1),
	(9, '2026_07_01_000006_upgrade_users_table', 1),
	(10, '2026_07_01_000007_create_expense_categories_table', 1),
	(11, '2026_07_01_000008_create_payment_methods_table', 1),
	(12, '2026_07_01_000009_create_suppliers_table', 1),
	(13, '2026_07_01_000010_create_brands_table', 1),
	(14, '2026_07_01_000011_create_device_models_table', 1),
	(15, '2026_07_01_000012_create_customers_table', 1),
	(16, '2026_07_01_000013_create_devices_table', 1),
	(17, '2026_07_01_000014_create_technicians_table', 1),
	(18, '2026_07_01_000015_create_spareparts_table', 1),
	(19, '2026_07_01_000016_create_printers_table', 1),
	(20, '2026_07_01_000017_create_settings_table', 1),
	(21, '2026_07_01_000018_create_service_orders_table', 1),
	(22, '2026_07_01_000019_create_service_status_histories_table', 1),
	(23, '2026_07_01_000020_create_service_items_table', 1),
	(24, '2026_07_01_000021_create_service_images_table', 1),
	(25, '2026_07_01_000022_create_warranties_table', 1),
	(26, '2026_07_01_000023_create_purchase_spareparts_table', 1),
	(27, '2026_07_01_000024_create_purchase_sparepart_items_table', 1),
	(28, '2026_07_01_000025_create_stock_transactions_table', 1),
	(29, '2026_07_01_000026_create_payments_table', 1),
	(30, '2026_07_01_000027_create_expenses_table', 1),
	(31, '2026_07_01_000028_create_notifications_table', 1),
	(32, '2026_07_01_000029_create_activity_logs_table', 1),
	(33, '2026_07_01_100000_add_intake_fields_to_service_orders_table', 2),
	(34, '2026_07_01_200000_create_service_work_logs_table', 3),
	(35, '2026_07_01_300000_create_service_invoices_table', 4),
	(36, '2026_07_01_400000_create_purchases_tables', 5);

-- Dumping structure for table service_hp.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notifications_uuid_unique` (`uuid`),
  KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.notifications: ~0 rows (approximately)

-- Dumping structure for table service_hp.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table service_hp.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_order_id` bigint unsigned NOT NULL,
  `payment_method_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `received_by` bigint unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `reference_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_uuid_unique` (`uuid`),
  KEY `payments_payment_method_id_foreign` (`payment_method_id`),
  KEY `payments_branch_id_foreign` (`branch_id`),
  KEY `payments_received_by_foreign` (`received_by`),
  KEY `payments_service_order_id_payment_date_index` (`service_order_id`,`payment_date`),
  CONSTRAINT `payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `payments_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.payments: ~0 rows (approximately)

-- Dumping structure for table service_hp.payment_methods
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_methods_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.payment_methods: ~0 rows (approximately)

-- Dumping structure for table service_hp.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.permissions: ~0 rows (approximately)

-- Dumping structure for table service_hp.permission_role
CREATE TABLE IF NOT EXISTS `permission_role` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_role_role_id_permission_id_unique` (`role_id`,`permission_id`),
  KEY `permission_role_permission_id_foreign` (`permission_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.permission_role: ~0 rows (approximately)

-- Dumping structure for table service_hp.printers
CREATE TABLE IF NOT EXISTS `printers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'thermal',
  `connection` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usb',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paper_size` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '80mm',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `printers_branch_id_foreign` (`branch_id`),
  CONSTRAINT `printers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.printers: ~0 rows (approximately)

-- Dumping structure for table service_hp.purchases
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_invoice_number_unique` (`invoice_number`),
  KEY `purchases_supplier_id_foreign` (`supplier_id`),
  KEY `purchases_user_id_foreign` (`user_id`),
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.purchases: ~0 rows (approximately)

-- Dumping structure for table service_hp.purchase_items
CREATE TABLE IF NOT EXISTS `purchase_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint unsigned NOT NULL,
  `spare_part_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  KEY `purchase_items_spare_part_id_foreign` (`spare_part_id`),
  CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_items_spare_part_id_foreign` FOREIGN KEY (`spare_part_id`) REFERENCES `spareparts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.purchase_items: ~0 rows (approximately)

-- Dumping structure for table service_hp.purchase_spareparts
CREATE TABLE IF NOT EXISTS `purchase_spareparts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_spareparts_branch_id_invoice_number_unique` (`branch_id`,`invoice_number`),
  UNIQUE KEY `purchase_spareparts_uuid_unique` (`uuid`),
  KEY `purchase_spareparts_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_spareparts_created_by_foreign` (`created_by`),
  CONSTRAINT `purchase_spareparts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_spareparts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `purchase_spareparts_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.purchase_spareparts: ~0 rows (approximately)

-- Dumping structure for table service_hp.purchase_sparepart_items
CREATE TABLE IF NOT EXISTS `purchase_sparepart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_sparepart_id` bigint unsigned NOT NULL,
  `sparepart_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_sparepart_items_purchase_sparepart_id_foreign` (`purchase_sparepart_id`),
  KEY `purchase_sparepart_items_sparepart_id_foreign` (`sparepart_id`),
  CONSTRAINT `purchase_sparepart_items_purchase_sparepart_id_foreign` FOREIGN KEY (`purchase_sparepart_id`) REFERENCES `purchase_spareparts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_sparepart_items_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.purchase_sparepart_items: ~0 rows (approximately)

-- Dumping structure for table service_hp.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.roles: ~0 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `is_system`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Owner', 'owner', NULL, 1, '2026-06-30 21:41:18', '2026-06-30 21:41:18', NULL),
	(2, 'Admin', 'admin', NULL, 1, '2026-06-30 21:41:18', '2026-06-30 21:41:18', NULL),
	(3, 'Kasir', 'kasir', NULL, 1, '2026-06-30 21:41:18', '2026-06-30 21:41:18', NULL),
	(4, 'Teknisi', 'teknisi', NULL, 1, '2026-06-30 21:41:18', '2026-06-30 21:41:18', NULL);

-- Dumping structure for table service_hp.service_images
CREATE TABLE IF NOT EXISTS `service_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_order_id` bigint unsigned NOT NULL,
  `service_status_history_id` bigint unsigned DEFAULT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_images_service_status_history_id_foreign` (`service_status_history_id`),
  KEY `service_images_uploaded_by_foreign` (`uploaded_by`),
  KEY `service_images_service_order_id_type_index` (`service_order_id`,`type`),
  CONSTRAINT `service_images_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `service_images_service_status_history_id_foreign` FOREIGN KEY (`service_status_history_id`) REFERENCES `service_status_histories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `service_images_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.service_images: ~0 rows (approximately)

-- Dumping structure for table service_hp.service_invoices
CREATE TABLE IF NOT EXISTS `service_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_order_id` bigint unsigned NOT NULL,
  `invoice_number` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `generated_by` bigint unsigned DEFAULT NULL,
  `generated_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_invoices_service_order_id_unique` (`service_order_id`),
  UNIQUE KEY `service_invoices_uuid_unique` (`uuid`),
  UNIQUE KEY `service_invoices_invoice_number_unique` (`invoice_number`),
  KEY `service_invoices_generated_by_foreign` (`generated_by`),
  CONSTRAINT `service_invoices_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `service_invoices_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.service_invoices: ~0 rows (approximately)
INSERT INTO `service_invoices` (`id`, `uuid`, `service_order_id`, `invoice_number`, `pdf_path`, `subtotal`, `discount`, `tax`, `total`, `paid_amount`, `generated_by`, `generated_at`, `created_at`, `updated_at`) VALUES
	(1, 'c7357142-f9a1-4577-a26d-ee9abd2b2cea', 1, 'INV-20260701-000001', 'invoices/1/pending.pdf', 0.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-06-30 22:07:27', '2026-06-30 22:07:27', '2026-06-30 22:07:27');

-- Dumping structure for table service_hp.service_items
CREATE TABLE IF NOT EXISTS `service_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_order_id` bigint unsigned NOT NULL,
  `sparepart_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_items_service_order_id_foreign` (`service_order_id`),
  KEY `service_items_sparepart_id_foreign` (`sparepart_id`),
  CONSTRAINT `service_items_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `service_items_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.service_items: ~0 rows (approximately)

-- Dumping structure for table service_hp.service_orders
CREATE TABLE IF NOT EXISTS `service_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `ticket_number` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `contact_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_id` bigint unsigned DEFAULT NULL,
  `device_brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imei` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `technician_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `complaint` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `completeness` text COLLATE utf8mb4_unicode_ci,
  `diagnosis` text COLLATE utf8mb4_unicode_ci,
  `service_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `estimated_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `parts_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'received',
  `entry_date` date NOT NULL,
  `estimated_completion` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `warranty_start_date` date DEFAULT NULL,
  `closed_date` date DEFAULT NULL,
  `warranty_days` int NOT NULL DEFAULT '30',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_orders_branch_id_ticket_number_unique` (`branch_id`,`ticket_number`),
  UNIQUE KEY `service_orders_uuid_unique` (`uuid`),
  KEY `service_orders_customer_id_foreign` (`customer_id`),
  KEY `service_orders_device_id_foreign` (`device_id`),
  KEY `service_orders_technician_id_foreign` (`technician_id`),
  KEY `service_orders_created_by_foreign` (`created_by`),
  KEY `service_orders_status_index` (`status`),
  KEY `service_orders_entry_date_index` (`entry_date`),
  CONSTRAINT `service_orders_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `service_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `service_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `service_orders_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `service_orders_technician_id_foreign` FOREIGN KEY (`technician_id`) REFERENCES `technicians` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.service_orders: ~0 rows (approximately)
INSERT INTO `service_orders` (`id`, `uuid`, `branch_id`, `ticket_number`, `customer_id`, `contact_phone`, `device_id`, `device_brand`, `device_model`, `imei`, `color`, `device_password`, `technician_id`, `created_by`, `complaint`, `completeness`, `diagnosis`, `service_fee`, `estimated_cost`, `parts_total`, `discount`, `tax`, `total_cost`, `paid_amount`, `status`, `entry_date`, `estimated_completion`, `completion_date`, `pickup_date`, `warranty_start_date`, `closed_date`, `warranty_days`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'c8138889-7bf8-48f5-8887-46d453b1cdf0', 1, 'SRV-20260701-000001', 1, '081211111111', NULL, 'Samsung', 'Galaxy A54', '123456789012345', 'Hitam', '1234', NULL, 1, 'LCD retak', 'Unit saja', NULL, 0.00, 500000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'received', '2026-07-01', NULL, NULL, NULL, NULL, NULL, 30, NULL, '2026-06-30 21:41:55', '2026-06-30 21:41:55', NULL);

-- Dumping structure for table service_hp.service_status_histories
CREATE TABLE IF NOT EXISTS `service_status_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_order_id` bigint unsigned NOT NULL,
  `from_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_at` datetime NOT NULL,
  `changed_by` bigint unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_status_histories_changed_by_foreign` (`changed_by`),
  KEY `service_status_histories_service_order_id_changed_at_index` (`service_order_id`,`changed_at`),
  CONSTRAINT `service_status_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `service_status_histories_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.service_status_histories: ~0 rows (approximately)
INSERT INTO `service_status_histories` (`id`, `service_order_id`, `from_status`, `to_status`, `changed_at`, `changed_by`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 'received', '2026-07-01 04:41:55', 1, 'Penerimaan service HP', '2026-06-30 21:41:55', '2026-06-30 21:41:55');

-- Dumping structure for table service_hp.service_work_logs
CREATE TABLE IF NOT EXISTS `service_work_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `logged_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_work_logs_user_id_foreign` (`user_id`),
  KEY `service_work_logs_service_order_id_logged_at_index` (`service_order_id`,`logged_at`),
  KEY `service_work_logs_action_index` (`action`),
  CONSTRAINT `service_work_logs_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `service_work_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.service_work_logs: ~0 rows (approximately)

-- Dumping structure for table service_hp.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.sessions: ~0 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('1GVuGQ10D8S5OXHwWb32xDQmoCz6IXTl6mGARpLx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.125.1 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'eyJfdG9rZW4iOiJ2NFNoVk1QYmM2NjJYYjZqeWJtbzBOcGJwQ2c5ejU1MkhmYmlzR0tiIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3JlcG9ydHNcL3JldmVudWUifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1782914574),
	('qlMoNLUIdgp9IsIUjMAqiEnGUeELIJMFf1jHArBC', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJOMDJBdUtaOEQ2U0FNTlNRWHlKb0NwNTZhYkk5emwxOHRKR21CZlVxIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9zdXBwbGllcnMiLCJyb3V0ZSI6InN1cHBsaWVycy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1782943463),
	('toahzPu9J3Rzo6WpiQnqqAMlwTDfm7DdhYyVNEKN', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.125.1 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'eyJfdG9rZW4iOiJKYXNDUmZKMWkyZ2NuUmU1QmVUdDQ5SmFFZHhTRm1xa25RUXRoa1FwIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJkYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1782917767),
	('uPbnaZrB7mfo0XKOgdmoBcQ5nsBCnpjcDFXy7uP0', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.125.1 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'eyJfdG9rZW4iOiJhT3FGWjRWOTFwV1ZOZ094UG1rVW1lN3hyb3d2M3lZd2JBY3gzanA0IiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jdXN0b21lcnMiLCJyb3V0ZSI6ImN1c3RvbWVycy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1782910399),
	('WK5AZ4X5syIbc59vzxlezEyDjddt0A4c5nEUvjFu', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.125.1 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'eyJfdG9rZW4iOiJqMmZOdDRoZFpqMzljM0drTDFLbHpDVmwwbjV2VVc4YTlIY3dRQUhFIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9icmFuZHNcLzYiLCJyb3V0ZSI6ImJyYW5kcy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1782916518),
	('yOtXF1TriZtCejMdnHIenFcN9PpnCNIws0biZ51E', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJmN1FwMnRYVThoakVWbHJtbDltcnYzclNWeVljQURtUmxXanRxNnY2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJkYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1782921240);

-- Dumping structure for table service_hp.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned DEFAULT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_branch_id_key_unique` (`branch_id`,`key`),
  CONSTRAINT `settings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.settings: ~0 rows (approximately)

-- Dumping structure for table service_hp.spareparts
CREATE TABLE IF NOT EXISTS `spareparts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `unit` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pcs',
  `stock` int NOT NULL DEFAULT '0',
  `min_stock` int NOT NULL DEFAULT '5',
  `purchase_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spareparts_branch_id_code_unique` (`branch_id`,`code`),
  UNIQUE KEY `spareparts_uuid_unique` (`uuid`),
  KEY `spareparts_supplier_id_foreign` (`supplier_id`),
  KEY `spareparts_branch_id_stock_index` (`branch_id`,`stock`),
  CONSTRAINT `spareparts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `spareparts_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.spareparts: ~0 rows (approximately)

-- Dumping structure for table service_hp.stock_mutations
CREATE TABLE IF NOT EXISTS `stock_mutations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `spare_part_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `stock_before` int NOT NULL DEFAULT '0',
  `stock_after` int NOT NULL DEFAULT '0',
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_mutations_spare_part_id_foreign` (`spare_part_id`),
  KEY `stock_mutations_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_mutations_spare_part_id_foreign` FOREIGN KEY (`spare_part_id`) REFERENCES `spareparts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_mutations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.stock_mutations: ~0 rows (approximately)

-- Dumping structure for table service_hp.stock_transactions
CREATE TABLE IF NOT EXISTS `stock_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sparepart_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('in','out','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `stock_before` int NOT NULL,
  `stock_after` int NOT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_transactions_branch_id_foreign` (`branch_id`),
  KEY `stock_transactions_user_id_foreign` (`user_id`),
  KEY `stock_transactions_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  KEY `stock_transactions_sparepart_id_created_at_index` (`sparepart_id`,`created_at`),
  CONSTRAINT `stock_transactions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_transactions_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.stock_transactions: ~0 rows (approximately)

-- Dumping structure for table service_hp.suppliers
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `suppliers_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.suppliers: ~0 rows (approximately)

-- Dumping structure for table service_hp.technicians
CREATE TABLE IF NOT EXISTS `technicians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `employee_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `technicians_user_id_unique` (`user_id`),
  UNIQUE KEY `technicians_branch_id_employee_code_unique` (`branch_id`,`employee_code`),
  CONSTRAINT `technicians_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `technicians_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.technicians: ~0 rows (approximately)
INSERT INTO `technicians` (`id`, `user_id`, `branch_id`, `employee_code`, `specialization`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 4, 1, 'TEC-004', 'Smartphone', 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(2, 5, 1, 'TEC-005', 'Smartphone', 1, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL);

-- Dumping structure for table service_hp.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `role_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_uuid_unique` (`uuid`),
  KEY `users_branch_id_foreign` (`branch_id`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.users: ~0 rows (approximately)
INSERT INTO `users` (`id`, `uuid`, `branch_id`, `role_id`, `name`, `email`, `phone`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '8295b6e6-1870-4c23-b202-ffa27dd6e1c1', 1, 1, 'Owner', 'owner@servicehp.test', '081260566368', 1, NULL, '$2y$12$FlV9eJvl8SMecqG22RUyv.WuYClRzdAtVST9Uq8fFJzz5jqadgOLC', NULL, '2026-06-30 21:41:19', '2026-06-30 21:41:19', NULL),
	(2, '522e1ca8-54b6-4e17-891a-64d6541db752', 1, 2, 'Admin', 'admin@servicehp.test', '081229116932', 1, NULL, '$2y$12$MN0winN3OydNWrowD7NQ4eivTKisIXTW9qvnbPFvdr.UEhFXsBB8.', NULL, '2026-06-30 21:41:20', '2026-06-30 21:41:20', NULL),
	(3, '2fcfdb00-389e-4218-a3fc-03faebd00548', 1, 3, 'Kasir', 'kasir@servicehp.test', '081225295503', 1, NULL, '$2y$12$BOFwWStepK5w4SZs1/pgi.xuE5Ji6yestiLx4OfbZ2vh1nZnXu63a', NULL, '2026-06-30 21:41:20', '2026-06-30 21:41:20', NULL),
	(4, 'bf1076af-2ab2-4953-861d-007e19510e63', 1, 4, 'Teknisi Satu', 'teknisi@servicehp.test', '081267983147', 1, NULL, '$2y$12$bEtsqfVtgrvuPYJRClTKxeRCdEoRVwDiL7Ay6D0Rrw9.C8gFgAYtq', NULL, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL),
	(5, '3db62610-847c-4a84-a213-8a49437ab6ed', 1, 4, 'Teknisi Dua', 'teknisi2@servicehp.test', '081260153796', 1, NULL, '$2y$12$IKjzDZuY/KsfXiN7o9lmiOudhfxrxaOEIj/UZcg1nxBBGksP3RKc2', NULL, '2026-06-30 21:41:21', '2026-06-30 21:41:21', NULL);

-- Dumping structure for table service_hp.warranties
CREATE TABLE IF NOT EXISTS `warranties` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_order_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration_days` int NOT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warranties_service_order_id_unique` (`service_order_id`),
  CONSTRAINT `warranties_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table service_hp.warranties: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
