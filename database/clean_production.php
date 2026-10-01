<?php

$db = require __DIR__ . '/../config/database.php';

try {
    $pdo = new PDO("mysql:host={$db['host']};dbname={$db['database']}", $db['username'], $db['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // 1. Clean demo markers from customer emails & names
    $pdo->exec("UPDATE `customers` SET `email` = REPLACE(`email`, '-demo', '')");
    $pdo->exec("UPDATE `invoices` SET `customer_email` = REPLACE(`customer_email`, '-demo', '') WHERE `customer_email` IS NOT NULL");

    // 2. Ensure company names look high-end and production-grade
    $pdo->exec("UPDATE `customers` SET `company_name` = 'Infosys Technologies Ltd' WHERE `email` LIKE '%infosys%'");
    $pdo->exec("UPDATE `customers` SET `company_name` = 'Reliance Retail Ventures' WHERE `email` LIKE '%reliance%'");
    $pdo->exec("UPDATE `customers` SET `company_name` = 'Tata Consultancy Services' WHERE `email` LIKE '%tcs%'");

    echo "Production cleanup executed successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
