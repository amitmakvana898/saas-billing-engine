<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Queue;
use PDO;
use Throwable;

class SchedulerService
{
    private SubscriptionService $subService;

    public function __construct()
    {
        $this->subService = new SubscriptionService();
    }

    /**
     * Execute all periodic automated background maintenance, billing and reconciliation tasks
     */
    public function runAllDailyTasks(): array
    {
        $db = Database::getConnection();
        $report = [
            'started_at' => date('Y-m-d H:i:s'),
            'tenants_processed' => 0,
            'recurring_invoices_generated' => 0,
            'overdue_invoices_flagged' => 0,
            'stale_webhooks_pruned' => 0,
            'jobs_processed' => 0,
            'errors' => [],
        ];

        // 1. Process Recurring Billing renewals for all active / trialing tenants
        $tenants = $db->query("SELECT id, name FROM tenants WHERE status != 'suspended'")->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tenants as $tenant) {
            $report['tenants_processed']++;
            try {
                $generated = $this->subService->processRecurringBilling($tenant['id']);
                $report['recurring_invoices_generated'] += count($generated);
            } catch (Throwable $e) {
                $report['errors'][] = "Tenant {$tenant['name']} ({$tenant['id']}) recurring billing error: " . $e->getMessage();
            }
        }

        // 2. Mark and reconcile overdue open invoices
        try {
            // If an invoice has been open for more than 15 days without payment, flag as past due / uncollectible warning
            $stmt = $db->prepare("
                UPDATE invoices 
                SET billing_reason = CONCAT(billing_reason, ' [OVERDUE_ALERT]') 
                WHERE status = 'open' 
                  AND created_at < DATE_SUB(NOW(), INTERVAL 15 DAY)
                  AND billing_reason NOT LIKE '%[OVERDUE_ALERT]%'
            ");
            $stmt->execute();
            $report['overdue_invoices_flagged'] = $stmt->rowCount();
        } catch (Throwable $e) {
            $report['errors'][] = "Overdue invoice marking error: " . $e->getMessage();
        }

        // 3. Prune old processed webhook idempotency records (> 30 days)
        try {
            $stmt = $db->prepare("
                DELETE FROM webhook_events 
                WHERE status = 'processed' 
                  AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)
            ");
            $stmt->execute();
            $report['stale_webhooks_pruned'] = $stmt->rowCount();
        } catch (Throwable $e) {
            $report['errors'][] = "Webhook pruning error: " . $e->getMessage();
        }

        // 4. Process waiting asynchronous queue jobs
        try {
            $report['jobs_processed'] = Queue::processAll('default', 50);
        } catch (Throwable $e) {
            $report['errors'][] = "Queue processing error: " . $e->getMessage();
        }

        $report['completed_at'] = date('Y-m-d H:i:s');
        return $report;
    }
}
