<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Database;

$db = Database::getConnection();

echo "Running Migration: upgrade_v5_queue.php\n";

$db->exec("
CREATE TABLE IF NOT EXISTS `jobs` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `queue` VARCHAR(50) NOT NULL DEFAULT 'default',
    `handler` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` INT NOT NULL DEFAULT 0,
    `max_attempts` INT NOT NULL DEFAULT 3,
    `status` ENUM('pending', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    `error_message` TEXT NULL,
    `available_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `reserved_at` DATETIME NULL,
    `completed_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_queue_status` (`queue`, `status`, `available_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
");

echo "Migration v5 (Jobs Queue Table) completed successfully.\n";
