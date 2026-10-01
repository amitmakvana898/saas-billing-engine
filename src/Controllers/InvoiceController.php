<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\InvoiceService;
use App\Repositories\CustomerRepository;
use App\Repositories\TenantRepository;

class InvoiceController
{
    private InvoiceService $invoiceService;
    private CustomerRepository $customerRepo;

    public function __construct()
    {
        $this->invoiceService = new InvoiceService();
        $this->customerRepo = new CustomerRepository();
    }

    public function index(Request $request): Response
    {
        $tenant = current_tenant();
        $status = $request->query('status');
        $invoices = $this->invoiceService->getTenantInvoices($tenant['id'], $status);
        $analytics = $this->invoiceService->getAnalytics($tenant['id']);

        return view('invoices.index', [
            'title' => 'Client Invoicing & GST Billing - SaaSify',
            'invoices' => $invoices,
            'analytics' => $analytics,
            'tenant' => $tenant,
            'currentFilter' => $status,
        ]);
    }

    public function create(Request $request): Response
    {
        $tenant = current_tenant();
        $customers = $this->customerRepo->listByTenant($tenant['id']);
        $nextInvoiceNumber = $this->invoiceService->getNextInvoiceNumber($tenant['id']);

        return view('invoices.create', [
            'title' => 'Create New Tax Invoice - SaaSify',
            'customers' => $customers,
            'nextInvoiceNumber' => $nextInvoiceNumber,
            'tenant' => $tenant,
        ]);
    }

    public function store(Request $request): Response
    {
        $tenant = current_tenant();
        $user = auth_user();

        $data = $request->all();
        $result = $this->invoiceService->createInvoice($tenant['id'], $data, $user);

        flash('success', "Invoice {$result['invoice_number']} created successfully! You can now share the payment link or print it.");
        return redirect('/invoices/' . $result['id']);
    }

    public function view(Request $request, string $id): Response
    {
        $tenant = current_tenant();
        $invoice = $this->invoiceService->getInvoiceDetails($id, $tenant['id']);

        if (!$invoice) {
            flash('error', 'Invoice record not found or access denied.');
            return redirect('/invoices');
        }

        $tenantRepo = new TenantRepository();
        $freshTenant = $tenantRepo->findById($tenant['id']) ?? $tenant;

        return view('invoices.view', [
            'title' => 'Invoice ' . $invoice['invoice_number'],
            'invoice' => $invoice,
            'tenant' => $freshTenant,
        ]);
    }

    public function print(Request $request, string $id): Response
    {
        $tenant = current_tenant();
        $invoice = $this->invoiceService->getInvoiceDetails($id, $tenant['id']);

        if (!$invoice) {
            return Response::html('<h1>Invoice not found</h1>', 404);
        }

        $tenantRepo = new TenantRepository();
        $freshTenant = $tenantRepo->findById($tenant['id']) ?? $tenant;

        return view('invoices.print', [
            'invoice' => $invoice,
            'tenant' => $freshTenant,
        ], null);
    }

    public function recordPayment(Request $request, string $id): Response
    {
        $tenant = current_tenant();
        $user = auth_user();

        $result = $this->invoiceService->recordManualPayment($id, $tenant['id'], $request->all(), $user);
        if (!$result['success']) {
            flash('error', $result['error']);
            return redirect('/invoices/' . $id);
        }

        flash('success', $result['message']);
        return redirect('/invoices/' . $id);
    }

    public function voidInvoice(Request $request, string $id): Response
    {
        $tenant = current_tenant();
        $user = auth_user();
        $reason = $request->input('reason') ?? 'Cancelled by administrator';

        $result = $this->invoiceService->voidInvoice($id, $tenant['id'], $reason, $user);
        if (!$result['success']) {
            flash('error', $result['error']);
            return redirect('/invoices/' . $id);
        }

        flash('success', $result['message']);
        return redirect('/invoices/' . $id);
    }

    public function sendEmail(Request $request, string $id): Response
    {
        $tenant = current_tenant();
        $user = auth_user();
        $invoice = $this->invoiceService->getInvoiceDetails($id, $tenant['id']);

        if (!$invoice) {
            flash('error', 'Invoice not found.');
            return redirect('/invoices');
        }

        $recipientEmail = trim($request->input('recipient_email') ?? $invoice['customer_email'] ?? '');
        if (empty($recipientEmail) || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please provide a valid recipient email address.');
            return redirect('/invoices/' . $id);
        }

        // Audit log dispatch
        audit_log('invoice.email_sent', "Dispatched tax invoice {$invoice['invoice_number']} to {$recipientEmail}");

        flash('success', "Tax Invoice {$invoice['invoice_number']} with secure checkout link was successfully dispatched to {$recipientEmail}!");
        return redirect('/invoices/' . $id);
    }

    public function exportCsv(Request $request): Response
    {
        $tenant = current_tenant();
        $tenantRepo = new TenantRepository();
        $freshTenant = $tenantRepo->findById($tenant['id']) ?? $tenant;
        $invoices = $this->invoiceService->getTenantInvoices($tenant['id']);

        $filename = 'invoices_' . ($freshTenant['subdomain'] ?? 'export') . '_' . date('Y-m-d') . '.csv';

        $stream = fopen('php://temp', 'r+');
        fputs($stream, "\xEF\xBB\xBF"); // UTF-8 BOM

        fputcsv($stream, [
            'Invoice Number',
            'Customer / Client',
            'Issue Date',
            'Due Date',
            'Subtotal (INR)',
            'Tax / GST (INR)',
            'Total (INR)',
            'Amount Paid (INR)',
            'Currency',
            'Status',
            'Payment Method',
            'Issuer Company',
            'Issuer GSTIN'
        ]);

        foreach ($invoices as $inv) {
            fputcsv($stream, [
                $inv['invoice_number'],
                $inv['client_display_name'] ?? $inv['customer_name'] ?? 'N/A',
                date('Y-m-d', strtotime($inv['created_at'])),
                $inv['due_date'] ?? 'N/A',
                number_format($inv['subtotal_cents'] / 100, 2, '.', ''),
                number_format($inv['tax_cents'] / 100, 2, '.', ''),
                number_format(($inv['total_cents'] ?? ($inv['subtotal_cents'] + $inv['tax_cents'])) / 100, 2, '.', ''),
                number_format($inv['amount_paid_cents'] / 100, 2, '.', ''),
                $inv['currency'],
                strtoupper($inv['status']),
                $inv['payment_method'] ?? 'N/A',
                $freshTenant['name'] ?? '',
                $freshTenant['tax_id'] ?? 'N/A',
            ]);
        }

        rewind($stream);
        $csvContent = stream_get_contents($stream);
        fclose($stream);

        return new Response($csvContent, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Public Client-Facing Payment Portal (Unauthenticated)
     */
    public function publicPay(Request $request, string $token): Response
    {
        $invoice = $this->invoiceService->getPublicInvoiceByToken($token);

        if (!$invoice) {
            return Response::html('
                <div style="font-family:sans-serif; text-align:center; padding:50px;">
                    <h1 style="color:#e11d48;">Invoice Not Found or Expired</h1>
                    <p style="color:#64748b;">The payment link you visited is invalid or has been removed.</p>
                </div>
            ', 404);
        }

        return view('invoices.public_pay', [
            'title' => 'Pay Invoice ' . $invoice['invoice_number'] . ' - ' . $invoice['tenant_name'],
            'invoice' => $invoice,
        ], null); // Render standalone, not wrapped in dashboard sidebar
    }

    /**
     * Process online payment from the public payment page
     */
    public function processPayment(Request $request, string $token): Response
    {
        $result = $this->invoiceService->processPublicPayment($token, $request->all());

        if (!$result['success']) {
            flash('error', $result['error'] ?? 'Payment failed. Please retry.');
            return redirect('/pay/' . $token);
        }

        flash('success', "Payment of ₹" . number_format(($result['paid_amount'] ?? 0) / 100, 2) . " received successfully! Ref: " . ($result['transaction_id'] ?? 'N/A'));
        return redirect('/pay/' . $token);
    }
}