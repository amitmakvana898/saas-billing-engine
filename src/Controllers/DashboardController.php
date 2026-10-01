<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\SubscriptionService;
use App\Services\InvoiceService;
use App\Repositories\UserRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\TenantRepository;
use App\Repositories\AuditLogRepository;

class DashboardController
{
    private SubscriptionService $subService;
    private InvoiceService $invoiceService;
    private UserRepository $userRepo;
    private CustomerRepository $customerRepo;

    public function __construct()
    {
        $this->subService = new SubscriptionService();
        $this->invoiceService = new InvoiceService();
        $this->userRepo = new UserRepository();
        $this->customerRepo = new CustomerRepository();
    }

    public function index(Request $request): Response
    {
        $tenant = current_tenant();
        $tenantId = $tenant['id'];

        $tenantRepo = new TenantRepository();
        $freshTenant = $tenantRepo->findById($tenantId) ?? $tenant;

        $subscription = $this->subService->getTenantSubscription($tenantId);
        $invoices = $this->invoiceService->getTenantInvoices($tenantId);
        $team = $this->userRepo->listByTenant($tenantId);
        $customers = $this->customerRepo->listByTenant($tenantId);
        $analytics = $this->invoiceService->getAnalytics($tenantId);

        // Calculate total collections & volume
        $totalSpendCents = array_reduce($invoices, fn($acc, $inv) => $acc + (int)$inv['amount_paid_cents'], 0);

        // Plan Quotas & Seat Utilization
        $maxSeats = (int)($subscription['max_seats'] ?? 5);
        $usedSeats = count($team);
        $seatPercent = min(100, (int)(($usedSeats / max(1, $maxSeats)) * 100));

        // Audit Trail Logs
        $auditRepo = new AuditLogRepository();
        $recentLogs = $auditRepo->listRecent($tenantId, 6);

        return view('dashboard.index', [
            'title' => 'Financial Analytics & Invoicing - ' . ($freshTenant['name'] ?? 'Dashboard'),
            'tenant' => $freshTenant,
            'subscription' => $subscription,
            'invoices' => array_slice($invoices, 0, 8),
            'allInvoices' => $invoices,
            'totalInvoices' => count($invoices),
            'totalSpendCents' => $totalSpendCents,
            'customersCount' => count($customers),
            'analytics' => $analytics,
            'teamMembers' => $team,
            'maxSeats' => $maxSeats,
            'usedSeats' => $usedSeats,
            'seatPercent' => $seatPercent,
            'recentLogs' => $recentLogs,
        ]);
    }

    public function suspended(Request $request): Response
    {
        return view('errors.suspended', [
            'title' => 'Account Suspended - SaaSify'
        ], null);
    }
}