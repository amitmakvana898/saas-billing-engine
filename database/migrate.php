<?php

$dbConfig = require __DIR__ . '/../config/database.php';

echo "=== SaaS Billing Engine - Database Migrator ===\n";

try {
    $dsn = sprintf("mysql:host=%s;port=%s;charset=%s", $dbConfig['host'], $dbConfig['port'], $dbConfig['charset']);
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
    ]);
    
    echo "[1/3] Ensuring database '{$dbConfig['database']}' exists...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbConfig['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $pdo->exec("USE `{$dbConfig['database']}`;");

    echo "[2/3] Executing schema.sql...\n";
    $schemaSql = file_get_contents(__DIR__ . '/schema.sql');
    // Remove BOM if present
    $schemaSql = preg_replace('/^\xEF\xBB\xBF/', '', $schemaSql);
    $pdo->exec($schemaSql);

    echo "[3/3] Seeding initial plans, demo tenant, user, and subscription...\n";
    $seedSql = file_get_contents(__DIR__ . '/seed.sql');
    $seedSql = preg_replace('/^\xEF\xBB\xBF/', '', $seedSql);
    $pdo->exec($seedSql);

    echo "\n>>> SUCCESS: Database migrated and seeded cleanly!\n";
    echo "Default Demo Login:\n";
    echo "  Email:    owner@acme.com\n";
    echo "  Password: Password123!\n";
} catch (PDOException $e) {
    echo "\n>>> ERROR: Database migration failed: " . $e->getMessage() . "\n";
    exit(1);
}