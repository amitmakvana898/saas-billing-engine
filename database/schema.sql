CREATE DATABASE IF NOT EXISTS `saas_billing_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `saas_billing_db`;

-- 1. Tenants (Organizations / Companies)
CREATE TABLE IF NOT EXISTS `tenants` (
    `id` VARCHAR(36) PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `subdomain` VARCHAR(60) NOT NULL UNIQUE,
    `tax_id` VARCHAR(50) NULL,
    `billing_address` TEXT NULL,
    `phone` VARCHAR(30) NULL,
    `status` ENUM('trial', 'active', 'suspended') DEFAULT 'trial',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_tenant_subdomain` (`subdomain`),
    INDEX `idx_tenant_status` (`status`)
) ENGINE=InnoDB;

-- 2. Users (Organization Members & Roles)
CREATE TABLE IF NOT EXISTS `users` (
    `id` VARCHAR(36) PRIMARY KEY,
    `tenant_id` VARCHAR(36) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('owner', 'admin', 'billing_manager', 'member') NOT NULL DEFAULT 'member',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `uq_tenant_email` (`tenant_id`, `email`),
    INDEX `idx_user_email` (`email`)
) ENGINE=InnoDB;

-- 3. Subscription Plans (SaaS Tiers)
CREATE TABLE IF NOT EXISTS `plans` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(80) NOT NULL,
    `slug` VARCHAR(60) NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL,
    `price_cents` INT NOT NULL, -- e.g., 2900 = $29.00
    `billing_interval` ENUM('monthly', 'yearly') NOT NULL DEFAULT 'monthly',
    `features_json` JSON NOT NULL,
    `max_seats` INT NOT NULL DEFAULT 5,
    `gateway_plan_id` VARCHAR(100) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. Subscriptions (Tenant Subscriptions & Lifecycle State Machine)
CREATE TABLE IF NOT EXISTS `subscriptions` (
    `id` VARCHAR(36) PRIMARY KEY,
    `tenant_id` VARCHAR(36) NOT NULL,
    `plan_id` INT NOT NULL,
    `gateway_subscription_id` VARCHAR(100) NULL,
    `status` ENUM('trialing', 'active', 'past_due', 'canceled', 'unpaid') NOT NULL DEFAULT 'trialing',
    `trial_ends_at` DATETIME NULL,
    `current_period_start` DATETIME NOT NULL,
    `current_period_end` DATETIME NOT NULL,
    `canceled_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`plan_id`) REFERENCES `plans`(`id`),
    INDEX `idx_sub_tenant` (`tenant_id`),
    INDEX `idx_sub_status` (`status`)
) ENGINE=InnoDB;

-- 5. Invoices (Immutable Billing & Tax Records)
CREATE TABLE IF NOT EXISTS `invoices` (
    `id` VARCHAR(36) PRIMARY KEY,
    `tenant_id` VARCHAR(36) NOT NULL,
    `subscription_id` VARCHAR(36) NULL,
    `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
    `subtotal_cents` INT NOT NULL,
    `tax_cents` INT NOT NULL DEFAULT 0,
    `amount_paid_cents` INT NOT NULL,
    `currency` VARCHAR(10) NOT NULL DEFAULT 'USD',
    `status` ENUM('paid', 'open', 'void', 'uncollectible') NOT NULL DEFAULT 'open',
    `billing_reason` VARCHAR(100) NOT NULL DEFAULT 'subscription_cycle',
    `pdf_path` VARCHAR(255) NULL,
    `paid_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`) ON DELETE CASCADE,
    INDEX `idx_inv_tenant` (`tenant_id`),
    INDEX `idx_inv_status` (`status`),
    INDEX `idx_inv_number` (`invoice_number`)
) ENGINE=InnoDB;

-- 6. Webhook Events (Idempotency Engine & Audit Trail)
CREATE TABLE IF NOT EXISTS `webhook_events` (
    `id` VARCHAR(36) PRIMARY KEY,
    `gateway` VARCHAR(50) NOT NULL, -- e.g., 'stripe', 'razorpay'
    `external_event_id` VARCHAR(150) NOT NULL UNIQUE,
    `event_type` VARCHAR(100) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `status` ENUM('pending', 'processed', 'failed') NOT NULL DEFAULT 'pending',
    `error_message` TEXT NULL,
    `processed_at` DATETIME NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_webhook_ext_id` (`external_event_id`),
    INDEX `idx_webhook_status` (`status`)
) ENGINE=InnoDB;

-- 7. Audit Logs (Compliance & Security Activity Trail)
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `tenant_id` VARCHAR(36) NOT NULL,
    `user_id` VARCHAR(36) NULL,
    `user_name` VARCHAR(100) NULL,
    `action` VARCHAR(100) NOT NULL,
    `description` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_tenant` (`tenant_id`),
    INDEX `idx_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Super Admins (Platform Global Operators)
CREATE TABLE IF NOT EXISTS `super_admins` (
    `id` VARCHAR(36) PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;