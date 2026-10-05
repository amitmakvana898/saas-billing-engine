<?php

if (php_sapi_name() !== 'cli') {
    die("Error: This script can only be run from the command line (CLI).\n");
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\SchedulerService;

echo "====================================================\n";
echo "       SAASIFY AUTOMATED BILLING CRON SCHEDULER     \n";
echo "====================================================\n";
echo "Started execution at: " . date('Y-m-d H:i:s') . "\n";
echo "Running multi-tenant recurring billing cycle...\n";
echo "----------------------------------------------------\n";

$scheduler = new SchedulerService();
$start = microtime(true);
$report = $scheduler->runAllDailyTasks();
$duration = round(microtime(true) - $start, 3);

echo "Execution Completed in {$duration}s\n\n";
echo "[REPORT SUMMARY]\n";
echo " - Tenants Inspected: {$report['tenants_processed']}\n";
echo " - Recurring Invoices Generated: {$report['recurring_invoices_generated']}\n";
echo " - Overdue Invoices Flagged: {$report['overdue_invoices_flagged']}\n";
echo " - Stale Webhooks Cleaned: {$report['stale_webhooks_pruned']}\n";
echo " - Background Jobs Executed: {$report['jobs_processed']}\n";

if (!empty($report['errors'])) {
    echo "\n[WARNINGS / ERRORS]\n";
    foreach ($report['errors'] as $err) {
        echo " ⚠️  {$err}\n";
    }
} else {
    echo "\n✅ All scheduled tasks executed with ZERO errors.\n";
}

echo "====================================================\n";
