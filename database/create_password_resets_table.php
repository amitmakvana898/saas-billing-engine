<?php

require_once __DIR__ . '/../src/Core/Database.php';

use App\Core\Database;

try {
    $pdo = Database::getConnection();
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS `password_resets` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `email` VARCHAR(150) NOT NULL,
            `token` VARCHAR(64) NOT NULL UNIQUE,
            `created_at` DATETIME NOT NULL,
            `expires_at` DATETIME NOT NULL,
            INDEX idx_token (`token`),
            INDEX idx_email (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ');
    echo "SUCCESS: password_resets table created.\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
