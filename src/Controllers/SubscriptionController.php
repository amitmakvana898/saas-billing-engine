<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\SubscriptionService;
use App\Repositories\PlanRepository;

class SubscriptionController
{
    private SubscriptionService $subService;
    private PlanRepository $planRepo;

    public function __construct()
    {
        $this->subService = new SubscriptionService();
        $this->planRepo = new PlanRepository();
    }

    public function showPlans(Request $request): Response
    {
        $tenant = current_tenant();
        $plans = $this->planRepo->allActive();
        $currentSub = $this->subService->getTenantSubscription($tenant['id']);

        return view('subscriptions.plans', [
            'title' => 'Subscription Plans & Tiers - SaaSify',
            'plans' => $plans,
            'currentSub' => $currentSub,
            'tenant' => $tenant,
        ]);
    }

    public function upgrade(Request $request): Response
    {
        $tenant = current_tenant();
        $planId = (int)$request->input('plan_id');

        try {
            $result = $this->subService->upgradePlan($tenant['id'], $planId);
            $plan = $this->planRepo->findById($planId);
            $planName = $plan['name'] ?? 'Custom';
            audit_log('subscription_upgraded', "Upgraded subscription to {$planName} Plan (Invoice #{$result['invoice_number']})");

            flash('success', "Payment Succeeded! Your subscription has been upgraded to {$planName} Plan. Official GST Tax Invoice #{$result['invoice_number']} has been generated.");
            return redirect('/invoices');
        } catch (\Throwable $e) {
            flash('error', 'Plan update failed: ' . $e->getMessage());
            return redirect('/plans');
        }
    }

    public function cancel(Request $request): Response
    {
        $tenant = current_tenant();
        $this->subService->cancelSubscription($tenant['id']);
        audit_log('subscription_canceled', 'Cancelled subscription effective at end of current period');

        flash('info', 'Your subscription has been canceled. Your services remain active until the end of the current billing cycle.');
        return redirect('/dashboard');
    }

    public function runRecurringBilling(Request $request): Response
    {
        $tenant = current_tenant();
        try {
            $invoices = $this->subService->processRecurringBilling($tenant['id']);
            $count = count($invoices);
            if ($count > 0) {
                $firstInv = $invoices[0]['invoice_number'];
                flash('success', "Auto-Billing Scheduler executed! Successfully generated {$count} recurring renewal invoice ({$firstInv}) with automated 18% GST.");
            } else {
                flash('info', "Auto-Billing Scheduler executed: All subscriptions are currently up to date.");
            }
        } catch (\Throwable $e) {
            flash('error', "Auto-billing process failed: " . $e->getMessage());
        }
        return redirect('/invoices');
    }
}