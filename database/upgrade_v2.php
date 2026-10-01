<?php

$dbConfig = require __DIR__ . '/../config/database.php';

echo "=== Running Enterprise Upgrade Migrations ===\n";

try {
    $dsn = sprintf("mysql:host=%s;port=%s;dbname=%s;charset=%s", 
        $dbConfig['host'], $dbConfig['port'], $dbConfig['database'], $dbConfig['charset']
    );
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // 1. Add columns to tenants table
    echo "1. Checking and adding columns to `tenants`...\n";
    $tenantCols = $pdo->query("SHOW COLUMNS FROM `tenants`")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('tax_id', $tenantCols)) {
        $pdo->exec("ALTER TABLE `tenants` ADD COLUMN `tax_id` VARCHAR(50) NULL AFTER `subdomain`");
        echo "   - Added `tax_id` to `tenants`\n";
    }
    if (!in_array('billing_address', $tenantCols)) {
        $pdo->exec("ALTER TABLE `tenants` ADD COLUMN `billing_address` TEXT NULL AFTER `tax_id`");
        echo "   - Added `billing_address` to `tenants`\n";
    }
    if (!in_array('phone', $tenantCols)) {
        $pdo->exec("ALTER TABLE `tenants` ADD COLUMN `phone` VARCHAR(30) NULL AFTER `billing_address`");
        echo "   - Added `phone` to `tenants`\n";
    }

    // 2. Add columns to plans table
    echo "2. Checking and adding columns to `plans`...\n";
    $planCols = $pdo->query("SHOW COLUMNS FROM `plans`")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('max_seats', $planCols)) {
        $pdo->exec("ALTER TABLE `plans` ADD COLUMN `max_seats` INT NOT NULL DEFAULT 5 AFTER `features_json`");
        echo "   - Added `max_seats` to `plans`\n";
    }

    // Update plan seat limits
    $pdo->exec("UPDATE `plans` SET `max_seats` = 5 WHERE `id` = 1");
    $pdo->exec("UPDATE `plans` SET `max_seats` = 25 WHERE `id` = 2");
    $pdo->exec("UPDATE `plans` SET `max_seats` = 999 WHERE `id` = 3");
    echo "   - Updated seat limits: Plan 1 (5), Plan 2 (25), Plan 3 (999)\n";

    // 3. Create audit_logs table
    echo "3. Creating `audit_logs` table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `audit_logs` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `tenant_id` VARCHAR(36) NOT NULL,
        `user_id` VARCHAR(36) NULL,
        `user_name` VARCHAR(100) NULL,
        `action` VARCHAR(100) NOT NULL,
        `description` TEXT NOT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_audit_tenant` (`tenant_id`),
        INDEX `idx_audit_created` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    echo "   - `audit_logs` table is ready\n";

    // 4. Update demo tenant with default GSTIN and Address
    $pdo->exec("UPDATE `tenants` SET 
        `tax_id` = '24AAACA1234A1Z5', 
        `billing_address` = 'Plot 45, SG Highway, Bodakdev, Ahmedabad, Gujarat 380054',
        `phone` = '+91 98765 43210'
        WHERE `id` = 'tenant-demo-uuid-001' AND `tax_id` IS NULL");
    echo "   - Updated demo tenant Acme with Indian GSTIN & Ahmedabad address\n";

    echo ">>> UPGRADE MIGRATION COMPLETED SUCCESSFULLY!\n";
} catch (Exception $e) {
    echo ">>> ERROR: Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
