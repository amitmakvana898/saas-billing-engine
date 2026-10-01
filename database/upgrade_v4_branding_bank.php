<?php

require_once __DIR__ . '/../src/Core/Database.php';

use App\Core\Database;

try {
    $pdo = Database::getConnection();
    
    // Add columns to tenants table if not already existing
    $columns = $pdo->query('SHOW COLUMNS FROM tenants')->fetchAll(PDO::FETCH_COLUMN);

    $addColumns = [
        'logo_url' => 'VARCHAR(255) NULL AFTER `phone`',
        'bank_name' => 'VARCHAR(100) NULL AFTER `logo_url`',
        'bank_account_no' => 'VARCHAR(50) NULL AFTER `bank_name`',
        'bank_ifsc' => 'VARCHAR(20) NULL AFTER `bank_account_no`',
        'upi_id' => 'VARCHAR(100) NULL AFTER `bank_ifsc`',
    ];

    foreach ($addColumns as $col => $definition) {
        if (!in_array($col, $columns)) {
            $pdo->exec("ALTER TABLE tenants ADD COLUMN `{$col}` {$definition}");
            echo "Added column `{$col}` to `tenants` table.\n";
        }
    }

    echo "SUCCESS: Tenants branding and banking schema updated.\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
