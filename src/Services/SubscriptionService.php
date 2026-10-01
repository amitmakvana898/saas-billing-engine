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
}