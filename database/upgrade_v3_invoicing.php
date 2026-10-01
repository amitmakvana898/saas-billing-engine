<?php

$dbConfig = require __DIR__ . '/../config/database.php';

echo "=== Running Invoicing Suite Upgrade Migrations (v3) ===\n";

try {
    $dsn = sprintf("mysql:host=%s;port=%s;dbname=%s;charset=%s", 
        $dbConfig['host'], $dbConfig['port'], $dbConfig['database'], $dbConfig['charset']
    );
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // 1. Create customers table
    echo "1. Creating `customers` table...\n";
    $pdo->exec("CREATE TABLE IF NOT EXISTS `customers` (
        `id` VARCHAR(36) PRIMARY KEY,
        `tenant_id` VARCHAR(36) NOT NULL,
        `name` VARCHAR(150) NOT NULL,
        `email` VARCHAR(150) NOT NULL,
        `phone` VARCHAR(50) NULL,
        `company_name` VARCHAR(150) NULL,
        `gstin` VARCHAR(50) NULL,
        `address` TEXT NULL,
        `city` VARCHAR(100) NULL,
        `state` VARCHAR(100) NULL,
        `pincode` VARCHAR(20) NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`) ON DELETE CASCADE,
        INDEX `idx_cust_tenant` (`tenant_id`),
        INDEX `idx_cust_email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    echo "   - `customers` table ready.\n";

    // 2. Add columns to invoices table
    echo "2. Checking and adding columns to `invoices`...\n";
    $invCols = $pdo->query("SHOW COLUMNS FROM `invoices`")->fetchAll(PDO::FETCH_COLUMN);

    $columnsToAdd = [
        'customer_id' => "ALTER TABLE `invoices` ADD COLUMN `customer_id` VARCHAR(36) NULL AFTER `subscription_id`",
        'customer_name' => "ALTER TABLE `invoices` ADD COLUMN `customer_name` VARCHAR(150) NULL AFTER `customer_id`",
        'customer_email' => "ALTER TABLE `invoices` ADD COLUMN `customer_email` VARCHAR(150) NULL AFTER `customer_name`",
        'customer_phone' => "ALTER TABLE `invoices` ADD COLUMN `customer_phone` VARCHAR(50) NULL AFTER `customer_email`",
        'customer_address' => "ALTER TABLE `invoices` ADD COLUMN `customer_address` TEXT NULL AFTER `customer_phone`",
        'customer_gstin' => "ALTER TABLE `invoices` ADD COLUMN `customer_gstin` VARCHAR(50) NULL AFTER `customer_address`",
        'due_date' => "ALTER TABLE `invoices` ADD COLUMN `due_date` DATE NULL AFTER `customer_gstin`",
        'discount_cents' => "ALTER TABLE `invoices` ADD COLUMN `discount_cents` INT NOT NULL DEFAULT 0 AFTER `tax_cents`",
        'total_cents' => "ALTER TABLE `invoices` ADD COLUMN `total_cents` INT NOT NULL DEFAULT 0 AFTER `discount_cents`",
        'notes' => "ALTER TABLE `invoices` ADD COLUMN `notes` TEXT NULL AFTER `billing_reason`",
        'items_json' => "ALTER TABLE `invoices` ADD COLUMN `items_json` LONGTEXT NULL AFTER `notes`",
        'payment_token' => "ALTER TABLE `invoices` ADD COLUMN `payment_token` VARCHAR(64) NULL AFTER `items_json`",
        'payment_method' => "ALTER TABLE `invoices` ADD COLUMN `payment_method` VARCHAR(50) NULL AFTER `payment_token`",
    ];

    foreach ($columnsToAdd as $col => $sql) {
        if (!in_array($col, $invCols)) {
            $pdo->exec($sql);
            echo "   - Added column `{$col}` to `invoices`.\n";
        }
    }

    // Ensure payment_token index
    try {
        $pdo->exec("ALTER TABLE `invoices` ADD UNIQUE INDEX `idx_inv_payment_token` (`payment_token`)");
    } catch (\Exception $e) {
        // Index might already exist
    }

    // 3. Seed demo customers for default tenant
    echo "3. Seeding demo customers for Acme Cloud...\n";
    $tenantStmt = $pdo->query("SELECT id FROM `tenants` WHERE `subdomain` = 'acme' LIMIT 1");
    $acmeTenant = $tenantStmt->fetch(PDO::FETCH_ASSOC);

    if ($acmeTenant) {
        $tId = $acmeTenant['id'];
        
        $count = $pdo->query("SELECT COUNT(*) FROM `customers` WHERE `tenant_id` = '{$tId}'")->fetchColumn();
        if ($count == 0) {
            $cust1 = bin2hex(random_bytes(16));
            $cust2 = bin2hex(random_bytes(16));
            $cust3 = bin2hex(random_bytes(16));

            $ins = $pdo->prepare("INSERT INTO `customers` 
                (`id`, `tenant_id`, `name`, `email`, `phone`, `company_name`, `gstin`, `address`, `city`, `state`, `pincode`)
                VALUES 
                (:id, :tenant_id, :name, :email, :phone, :company_name, :gstin, :address, :city, :state, :pincode)
            ");

            $ins->execute([
                'id' => $cust1,
                'tenant_id' => $tId,
                'name' => 'Rajesh Sharma',
                'email' => 'rajesh@infosys-demo.com',
                'phone' => '+91 98200 12345',
                'company_name' => 'Infosys Digital Solutions',
                'gstin' => '27AAACI1681G1ZM',
                'address' => 'Plot 44, Electronic City, Phase 1',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'pincode' => '560100'
            ]);

            $ins->execute([
                'id' => $cust2,
                'tenant_id' => $tId,
                'name' => 'Priya Patel',
                'email' => 'priya@reliance-demo.in',
                'phone' => '+91 98790 67890',
                'company_name' => 'Reliance Retail Ventures',
                'gstin' => '24AAACR5055K1Z4',
                'address' => 'Reliance Corporate Park, Ghansoli',
                'city' => 'Navi Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400701'
            ]);

            $ins->execute([
                'id' => $cust3,
                'tenant_id' => $tId,
                'name' => 'Amitabh Sengupta',
                'email' => 'amitabh@tcs-demo.com',
                'phone' => '+91 98310 54321',
                'company_name' => 'Tata Consultancy Works',
                'gstin' => '19AAACT2727Q1ZW',
                'address' => 'Sector V, Salt Lake',
                'city' => 'Kolkata',
                'state' => 'West Bengal',
                'pincode' => '700091'
            ]);

            echo "   - Created 3 demo corporate clients.\n";
        }
    }

    // 4. Update any existing invoices that lack payment_token or total_cents or items_json
    $invoices = $pdo->query("SELECT id, subtotal_cents, tax_cents, payment_token, items_json FROM `invoices`")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($invoices as $inv) {
        $token = $inv['payment_token'] ?: bin2hex(random_bytes(16));
        $totalCents = (int)$inv['subtotal_cents'] + (int)$inv['tax_cents'];
        $items = $inv['items_json'] ?: json_encode([
            [
                'description' => 'Enterprise SaaS Platform Subscription & Cloud API Access',
                'qty' => 1,
                'rate_cents' => (int)$inv['subtotal_cents'],
                'amount_cents' => (int)$inv['subtotal_cents'],
                'tax_rate' => 18
            ]
        ]);

        $up = $pdo->prepare("UPDATE `invoices` SET `payment_token` = :token, `total_cents` = :total, `items_json` = :items WHERE `id` = :id");
        $up->execute([
            'token' => $token,
            'total' => $totalCents,
            'items' => $items,
            'id' => $inv['id']
        ]);
    }
    echo "   - Updated existing invoices with payment tokens and line-items.\n";

    echo ">>> Invoicing Suite Migrations completed successfully!\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
