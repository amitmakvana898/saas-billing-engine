<?php

namespace App\Services;

use App\Repositories\SubscriptionRepository;
use App\Repositories\PlanRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\TenantRepository;
use App\Core\Database;

class SubscriptionService
{
    private SubscriptionRepository $subRepo;
    private PlanRepository $planRepo;
    private InvoiceRepository $invoiceRepo;
    private TenantRepository $tenantRepo;

    public function __construct()
    {
        $this->subRepo = new SubscriptionRepository();
        $this->planRepo = new PlanRepository();
        $this->invoiceRepo = new InvoiceRepository();
        $this->tenantRepo = new TenantRepository();
    }

    public function getTenantSubscription(string $tenantId): ?array
    {
        return $this->subRepo->findActiveByTenant($tenantId);
    }

    /**
     * Change or upgrade subscription tier with invoice calculation
     */
    public function upgradePlan(string $tenantId, int $newPlanId): array
    {
        $currentSub = $this->subRepo->findActiveByTenant($tenantId);
        $newPlan = $this->planRepo->findById($newPlanId);

        if (!$newPlan) {
            throw new \InvalidArgumentException('Selected subscription plan not found.');
        }

        return Database::transaction(function() use ($tenantId, $currentSub, $newPlan, $newPlanId) {
            $now = date('Y-m-d H:i:s');
            $periodEnd = ($newPlan['billing_interval'] === 'yearly')
                ? date('Y-m-d H:i:s', strtotime('+1 year'))
                : date('Y-m-d H:i:s', strtotime('+1 month'));

            if ($currentSub) {
                $this->subRepo->updatePlan($currentSub['id'], $newPlanId, $now, $periodEnd, 'active');
                $subId = $currentSub['id'];
            } else {
                $subId = $this->subRepo->create([
                    'tenant_id' => $tenantId,
                    'plan_id' => $newPlanId,
                    'status' => 'active',
                    'current_period_start' => $now,
                    'current_period_end' => $periodEnd,
                ]);
            }

            // Update Tenant Status to Active
            $this->tenantRepo->updateStatus($tenantId, 'active');

            // Generate Instant Paid Invoice for this tier upgrade
            $taxRate = 0.18; // 18% Tax
            $subtotal = $newPlan['price_cents'];
            $tax = (int)round($subtotal * $taxRate);
            $total = $subtotal + $tax;

            $invNumber = $this->invoiceRepo->getNextInvoiceNumber();
            $invoiceId = $this->invoiceRepo->create([
                'tenant_id' => $tenantId,
                'subscription_id' => $subId,
                'invoice_number' => $invNumber,
                'subtotal_cents' => $subtotal,
                'tax_cents' => $tax,
                'amount_paid_cents' => $total,
                'currency' => 'INR',
                'status' => 'paid',
                'billing_reason' => 'subscription_update',
            ]);

            return [
                'subscription_id' => $subId,
                'invoice_id' => $invoiceId,
                'invoice_number' => $invNumber,
                'amount_paid' => $total,
            ];
        });
    }

    public function cancelSubscription(string $tenantId): bool
    {
        $currentSub = $this->subRepo->findActiveByTenant($tenantId);
        if (!$currentSub) {
            return false;
        }

        return $this->subRepo->updateStatus($currentSub['id'], 'canceled', date('Y-m-d H:i:s'));
    }

    /**
     * Process recurring subscription renewals and generate automated cycle invoices
     */
    public function processRecurringBilling(?string $tenantId = null): array
    {
        $pdo = Database::getConnection();
        
        $sql = "
            SELECT s.*, p.name as plan_name, p.price_cents, p.billing_interval, t.name as tenant_name, t.tax_id
            FROM subscriptions s
            JOIN plans p ON p.id = s.plan_id
            JOIN tenants t ON t.id = s.tenant_id
            WHERE s.status IN ('active', 'trialing')
        ";
        if ($tenantId) {
            $sql .= " AND s.tenant_id = :tenant_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['tenant_id' => $tenantId]);
        } else {
            $stmt = $pdo->query($sql);
        }
        
        $dueSubs = $stmt->fetchAll();
        $generatedInvoices = [];
        
        foreach ($dueSubs as $sub) {
            $tid = $sub['tenant_id'];
            $subtotal = (int)$sub['price_cents'];
            if ($subtotal <= 0) $subtotal = 199900; // default ₹1,999 for free/trial
            $tax = (int)round($subtotal * 0.18);
            $total = $subtotal + $tax;
            
            $nextPeriodStart = !empty($sub['current_period_end']) ? $sub['current_period_end'] : date('Y-m-d H:i:s');
            $interval = ($sub['billing_interval'] === 'yearly') ? '+1 year' : '+1 month';
            $nextPeriodEnd = date('Y-m-d H:i:s', strtotime($interval, strtotime($nextPeriodStart)));
            
            // Advance subscription period
            $updateStmt = $pdo->prepare("
                UPDATE subscriptions 
                SET current_period_start = :start, 
                    current_period_end = :end, 
                    status = 'active',
                    updated_at = NOW() 
                WHERE id = :id
            ");
            $updateStmt->execute([
                'start' => $nextPeriodStart,
                'end' => $nextPeriodEnd,
                'id' => $sub['id']
            ]);
            
            // Generate official recurring invoice
            $invNumber = $this->invoiceRepo->getNextInvoiceNumber($tid);
            $itemsJson = json_encode([
                [
                    'description' => "Automated Recurring Subscription - {$sub['plan_name']} (" . ucfirst($sub['billing_interval']) . ")",
                    'hsn' => '998313',
                    'qty' => 1,
                    'rate_cents' => $subtotal,
                    'tax_rate' => 18,
                    'tax_cents' => $tax,
                    'amount_cents' => $total
                ]
            ]);
            
            $paymentToken = bin2hex(random_bytes(24));
            $invId = $this->invoiceRepo->create([
                'tenant_id' => $tid,
                'subscription_id' => $sub['id'],
                'invoice_number' => $invNumber,
                'due_date' => date('Y-m-d', strtotime('+15 days')),
                'subtotal_cents' => $subtotal,
                'tax_cents' => $tax,
                'discount_cents' => 0,
                'total_cents' => $total,
                'amount_paid_cents' => 0,
                'currency' => 'INR',
                'status' => 'open',
                'billing_reason' => 'subscription_cycle',
                'notes' => "Automated billing cycle for {$sub['plan_name']} covering period " . date('M d, Y', strtotime($nextPeriodStart)) . " to " . date('M d, Y', strtotime($nextPeriodEnd)),
                'items_json' => $itemsJson,
                'payment_token' => $paymentToken
            ]);
            
            audit_log('recurring_billing_generated', "Automated recurring invoice {$invNumber} generated for {$sub['plan_name']} (₹" . number_format($total/100, 2) . ")", $tid);
            
            $generatedInvoices[] = [
                'invoice_id' => $invId,
                'invoice_number' => $invNumber,
                'tenant_name' => $sub['tenant_name'],
                'plan_name' => $sub['plan_name'],
                'total_cents' => $total,
                'due_date' => date('Y-m-d', strtotime('+15 days'))
            ];
        }
        
        return $generatedInvoices;
    }
}