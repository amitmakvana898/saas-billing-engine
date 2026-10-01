<?php
require_once __DIR__ . '/../vendor/autoload.php';

$db = \App\Core\Database::getConnection();

try {
    // Drop old index if exists
    $indexes = $db->query("SHOW INDEX FROM invoices WHERE Key_name = 'invoice_number'")->fetchAll();
    if (!empty($indexes)) {
        $db->exec("ALTER TABLE `invoices` DROP INDEX `invoice_number`");
        echo "Dropped old global index 'invoice_number'.\n";
    }

    // Add composite index if not exists
    $composite = $db->query("SHOW INDEX FROM invoices WHERE Key_name = 'uq_tenant_invoice_number'")->fetchAll();
    if (empty($composite)) {
        $db->exec("ALTER TABLE `invoices` ADD UNIQUE KEY `uq_tenant_invoice_number` (`tenant_id`, `invoice_number`)");
        echo "Added composite unique index 'uq_tenant_invoice_number' (`tenant_id`, `invoice_number`).\n";
    }

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
}
