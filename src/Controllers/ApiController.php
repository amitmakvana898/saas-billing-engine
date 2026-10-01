<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\InvoiceService;
use App\Repositories\CustomerRepository;
use App\Repositories\PlanRepository;

class ApiController
{
    private InvoiceService $invoiceService;
    private CustomerRepository $customerRepo;
    private PlanRepository $planRepo;

    public function __construct()
    {
        $this->invoiceService = new InvoiceService();
        $this->customerRepo = new CustomerRepository();
        $this->planRepo = new PlanRepository();
    }

    private function getTenantId(): string
    {
        return Session::get('tenant_id') ?? '';
    }

    /**
     * GET /api/v1/invoices
     */
    public function getInvoices(Request $request): Response
    {
        $tenantId = $this->getTenantId();
        $status = $request->query('status');
        $invoices = $this->invoiceService->getTenantInvoices($tenantId, $status);

        $formatted = array_map(function ($inv) {
            return [
                'id' => $inv['id'],
                'invoice_number' => $inv['invoice_number'],
                'customer_name' => $inv['customer_name'] ?? $inv['client_display_name'] ?? 'N/A',
                'customer_email' => $inv['customer_email'] ?? '',
                'customer_gstin' => $inv['customer_gstin'] ?? '',
                'amount_subtotal' => (int)$inv['subtotal_cents'] / 100,
                'amount_tax' => (int)$inv['tax_cents'] / 100,
                'amount_total' => (int)($inv['total_cents'] ?? ($inv['subtotal_cents'] + $inv['tax_cents'])) / 100,
                'amount_paid' => (int)($inv['amount_paid_cents'] ?? 0) / 100,
                'currency' => $inv['currency'] ?? 'INR',
                'status' => $inv['status'],
                'due_date' => $inv['due_date'],
                'created_at' => $inv['created_at'],
                'public_payment_url' => !empty($inv['payment_token']) ? app_url('pay/' . $inv['payment_token']) : null,
            ];
        }, $invoices);

        return Response::json([
            'success' => true,
            'count' => count($formatted),
            'data' => $formatted,
        ]);
    }

    /**
     * GET /api/v1/invoices/{id}
     */
    public function getInvoice(Request $request, string $id): Response
    {
        $tenantId = $this->getTenantId();
        $invoice = $this->invoiceService->getInvoiceDetails($id, $tenantId);

        if (!$invoice) {
            return Response::json([
                'success' => false,
                'error' => 'Invoice not found or access denied.',
            ], 404);
        }

        $items = json_decode($invoice['items_json'] ?? '[]', true) ?: [];

        return Response::json([
            'success' => true,
            'data' => [
                'id' => $invoice['id'],
                'invoice_number' => $invoice['invoice_number'],
                'customer_name' => $invoice['customer_name'],
                'customer_email' => $invoice['customer_email'],
                'customer_gstin' => $invoice['customer_gstin'],
                'customer_address' => $invoice['customer_address'],
                'amount_subtotal' => (int)$invoice['subtotal_cents'] / 100,
                'amount_tax' => (int)$invoice['tax_cents'] / 100,
                'amount_total' => (int)$invoice['total_cents'] / 100,
                'amount_paid' => (int)$invoice['amount_paid_cents'] / 100,
                'currency' => $invoice['currency'],
                'status' => $invoice['status'],
                'items' => $items,
                'due_date' => $invoice['due_date'],
                'notes' => $invoice['notes'],
                'payment_url' => !empty($invoice['payment_token']) ? app_url('pay/' . $invoice['payment_token']) : null,
                'created_at' => $invoice['created_at'],
            ]
        ]);
    }

    /**
     * POST /api/v1/invoices
     */
    public function createInvoice(Request $request): Response
    {
        $tenantId = $this->getTenantId();
        $payload = $request->all();

        // Basic validation
        if (empty($payload['customer_name']) && empty($payload['customer_id'])) {
            return Response::json([
                'success' => false,
                'error' => 'Either customer_name or customer_id is required.',
            ], 422);
        }

        if (empty($payload['items']) || !is_array($payload['items'])) {
            return Response::json([
                'success' => false,
                'error' => 'items must be an array with at least one item.',
            ], 422);
        }

        $result = $this->invoiceService->createInvoice($tenantId, $payload, [
            'id' => 'api_key_system',
            'name' => 'REST API Integration',
        ]);

        $created = $this->invoiceService->getInvoiceDetails($result['id'], $tenantId);

        return Response::json([
            'success' => true,
            'message' => 'Invoice forged and registered successfully.',
            'data' => [
                'id' => $result['id'],
                'invoice_number' => $result['invoice_number'],
                'status' => $result['status'],
                'amount_total' => (int)$result['total_cents'] / 100,
                'amount_subtotal' => (int)($created['subtotal_cents'] ?? 0) / 100,
                'amount_tax' => (int)($created['tax_cents'] ?? 0) / 100,
                'public_payment_url' => !empty($created['payment_token']) ? app_url('pay/' . $created['payment_token']) : null,
            ]
        ], 201);
    }

    /**
     * GET /api/v1/customers
     */
    public function getCustomers(Request $request): Response
    {
        $tenantId = $this->getTenantId();
        $customers = $this->customerRepo->listByTenant($tenantId);

        return Response::json([
            'success' => true,
            'count' => count($customers),
            'data' => $customers,
        ]);
    }

    /**
     * GET /api/v1/plans
     */
    public function getPlans(Request $request): Response
    {
        $plans = $this->planRepo->allActive();

        return Response::json([
            'success' => true,
            'count' => count($plans),
            'data' => array_map(function ($p) {
                return [
                    'id' => $p['id'],
                    'name' => $p['name'],
                    'slug' => $p['slug'],
                    'price' => (int)$p['price_cents'] / 100,
                    'currency' => $p['currency'] ?? 'INR',
                    'billing_interval' => $p['billing_interval'],
                    'max_seats' => $p['max_seats'],
                ];
            }, $plans),
        ]);
    }

    /**
     * GET /api/v1/docs
     */
    public function getDocs(Request $request): Response
    {
        return Response::json([
            'title' => 'SaaSify Developer REST API Reference',
            'version' => 'v1',
            'authentication' => 'Header "Authorization: Bearer <your_api_key>"',
            'endpoints' => [
                'GET /api/v1/invoices' => 'List all tenant invoices (optional ?status=open|paid|void)',
                'POST /api/v1/invoices' => 'Create a new GST-compliant invoice with line items',
                'GET /api/v1/invoices/{id}' => 'Retrieve complete invoice details by ID',
                'GET /api/v1/customers' => 'List all registered CRM customers',
                'GET /api/v1/plans' => 'List all available subscription plans',
            ]
        ]);
    }
}
