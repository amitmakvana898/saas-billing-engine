<?php

namespace App\Services;

use App\Repositories\InvoiceRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\AuditLogRepository;
use App\Core\Database;

class InvoiceService
{
    private InvoiceRepository $invoiceRepo;
    private CustomerRepository $customerRepo;
    private AuditLogRepository $auditRepo;

    public function __construct()
    {
        $this->invoiceRepo = new InvoiceRepository();
        $this->customerRepo = new CustomerRepository();
        $this->auditRepo = new AuditLogRepository();
    }

    public function getTenantInvoices(string $tenantId, ?string $status = null): array
    {
        return $this->invoiceRepo->listByTenant($tenantId, $status);
    }

    public function getInvoiceDetails(string $id, string $tenantId): ?array
    {
        return $this->invoiceRepo->findById($id, $tenantId);
    }

    public function getPublicInvoiceByToken(string $token): ?array
    {
        return $this->invoiceRepo->findByPaymentToken($token);
    }

    public function getNextInvoiceNumber(string $tenantId): string
    {
        return $this->invoiceRepo->getNextInvoiceNumber($tenantId);
    }

    public function getAnalytics(string $tenantId): array
    {
        return $this->invoiceRepo->getTenantBillingAnalytics($tenantId);
    }

    public function createInvoice(string $tenantId, array $data, ?array $user = null): array
    {
        // 1. Process customer information
        $customerId = !empty($data['customer_id']) ? $data['customer_id'] : null;
        $custName = $data['customer_name'] ?? '';
        $custEmail = $data['customer_email'] ?? '';
        $custPhone = $data['customer_phone'] ?? '';
        $custAddress = $data['customer_address'] ?? '';
        $custGstin = $data['customer_gstin'] ?? '';

        if ($customerId) {
            $existing = $this->customerRepo->findById($customerId, $tenantId);
            if ($existing) {
                $custName = $existing['name'];
                $custEmail = $existing['email'];
                $custPhone = $existing['phone'];
                $custAddress = $existing['address'];
                $custGstin = $existing['gstin'];
            }
        } elseif (!empty($custName) && !empty($custEmail)) {
            // Auto-save new customer if checked
            if (!empty($data['save_customer'])) {
                $customerId = $this->customerRepo->create([
                    'tenant_id' => $tenantId,
                    'name' => $custName,
                    'email' => $custEmail,
                    'phone' => $custPhone,
                    'company_name' => $data['customer_company'] ?? '',
                    'gstin' => $custGstin,
                    'address' => $custAddress,
                ]);
            }
        }

        // 2. Process Line Items
        $items = [];
        $rawItems = $data['items'] ?? [];
        $subtotalCents = 0;
        $taxCents = 0;

        foreach ($rawItems as $raw) {
            $desc = trim($raw['description'] ?? '');
            if (empty($desc)) continue;

            $qty = max(1, (float)($raw['qty'] ?? $raw['quantity'] ?? 1));
            $rate = max(0, (float)($raw['rate'] ?? $raw['unit_price'] ?? 0));
            $taxRate = max(0, (float)($raw['tax_rate'] ?? 18)); // default 18% GST

            $lineSubtotal = round($qty * $rate * 100); // in cents/paise
            $lineTax = round($lineSubtotal * ($taxRate / 100));
            $lineTotal = $lineSubtotal + $lineTax;

            $subtotalCents += $lineSubtotal;
            $taxCents += $lineTax;

            $items[] = [
                'description' => $desc,
                'hsn' => trim($raw['hsn'] ?? '998311'),
                'qty' => $qty,
                'rate_cents' => round($rate * 100),
                'tax_rate' => $taxRate,
                'tax_cents' => $lineTax,
                'amount_cents' => $lineTotal,
            ];
        }

        // Fallback default item if none provided
        if (empty($items)) {
            $defaultSubtotal = 100000; // ₹1,000
            $defaultTax = 18000; // 18% GST = ₹180
            $subtotalCents = $defaultSubtotal;
            $taxCents = $defaultTax;
            $items[] = [
                'description' => 'Professional Software Consulting & Technical Services',
                'qty' => 1,
                'rate_cents' => $defaultSubtotal,
                'tax_rate' => 18,
                'tax_cents' => $defaultTax,
                'amount_cents' => $defaultSubtotal + $defaultTax,
            ];
        }

        $discountPercent = max(0, min(100, (float)($data['discount_percent'] ?? 0)));
        $discountCents = round($subtotalCents * ($discountPercent / 100));
        $totalCents = max(0, $subtotalCents + $taxCents - $discountCents);

        $invoiceNumber = !empty($data['invoice_number']) ? trim($data['invoice_number']) : $this->getNextInvoiceNumber($tenantId);
        $dueDate = !empty($data['due_date']) ? $data['due_date'] : date('Y-m-d', strtotime('+15 days'));
        $status = (!empty($data['mark_paid']) && $data['mark_paid'] == '1') ? 'paid' : 'open';

        $invoiceId = $this->invoiceRepo->create([
            'tenant_id' => $tenantId,
            'customer_id' => $customerId,
            'customer_name' => $custName,
            'customer_email' => $custEmail,
            'customer_phone' => $custPhone,
            'customer_address' => $custAddress,
            'customer_gstin' => $custGstin,
            'invoice_number' => $invoiceNumber,
            'due_date' => $dueDate,
            'subtotal_cents' => $subtotalCents,
            'tax_cents' => $taxCents,
            'discount_cents' => $discountCents,
            'total_cents' => $totalCents,
            'amount_paid_cents' => ($status === 'paid') ? $totalCents : 0,
            'currency' => 'INR',
            'status' => $status,
            'billing_reason' => 'client_b2b_invoice',
            'notes' => $data['notes'] ?? 'Payment is requested within the due date. Thank you for choosing us!',
            'items_json' => $items,
            'payment_token' => bin2hex(random_bytes(24)),
            'payment_method' => ($status === 'paid') ? ($data['payment_method'] ?? 'cash_manual') : null,
        ]);

        // Audit log
        $this->auditRepo->log(
            $tenantId,
            $user['id'] ?? null,
            $user['name'] ?? 'System',
            'invoice.created',
            "Generated B2B Tax Invoice {$invoiceNumber} for '{$custName}' totaling ₹" . number_format($totalCents / 100, 2)
        );

        return [
            'id' => $invoiceId,
            'invoice_number' => $invoiceNumber,
            'status' => $status,
            'total_cents' => $totalCents,
        ];
    }

    public function processPublicPayment(string $token, array $paymentData): array
    {
        return Database::transaction(function() use ($token, $paymentData) {
            $invoice = $this->invoiceRepo->findByPaymentToken($token, true);
            if (!$invoice) {
                return ['success' => false, 'error' => 'Invoice not found or link has expired.'];
            }

            if ($invoice['status'] === 'paid') {
                return ['success' => true, 'already_paid' => true, 'message' => 'This invoice has already been settled.'];
            }

            $method = $paymentData['payment_method'] ?? 'upi';
            $txnId = 'TXN_' . strtoupper(bin2hex(random_bytes(6)));

            $this->invoiceRepo->markAsPaid($invoice['id'], $method, (int)$invoice['total_cents']);

            // Log audit
            $this->auditRepo->log(
                $invoice['tenant_id'],
                null,
                'Client Checkout',
                'invoice.paid_online',
                "Invoice {$invoice['invoice_number']} settled online via {$method}. Txn: {$txnId}"
            );

            return [
                'success' => true,
                'invoice' => $invoice,
                'transaction_id' => $txnId,
                'method' => $method,
                'paid_amount' => $invoice['total_cents'],
            ];
        });
    }

    public function recordManualPayment(string $invoiceId, string $tenantId, array $data, ?array $user = null): array
    {
        return Database::transaction(function() use ($invoiceId, $tenantId, $data, $user) {
            $invoice = $this->invoiceRepo->findById($invoiceId, $tenantId);
            if (!$invoice) {
                return ['success' => false, 'error' => 'Invoice not found or access denied.'];
            }

            if ($invoice['status'] === 'paid') {
                return ['success' => false, 'error' => 'This invoice has already been settled and marked as paid.'];
            }

            if ($invoice['status'] === 'void') {
                return ['success' => false, 'error' => 'Cannot record payment on a voided/cancelled invoice.'];
            }

            $method = trim($data['payment_method'] ?? 'bank_transfer');
            $refNumber = trim($data['reference_number'] ?? '');
            $notes = trim($data['notes'] ?? '');
            $paidDate = !empty($data['payment_date']) ? date('Y-m-d H:i:s', strtotime($data['payment_date'])) : date('Y-m-d H:i:s');
            
            $refText = $refNumber;
            if (!empty($notes)) {
                $refText .= " (Notes: {$notes})";
            }

            $totalCents = (int)($invoice['total_cents'] ?? ($invoice['subtotal_cents'] + $invoice['tax_cents']));
            $amountPaidCents = !empty($data['amount_paid']) ? (int)round((float)$data['amount_paid'] * 100) : $totalCents;

            $this->invoiceRepo->markAsPaid($invoiceId, $method, $amountPaidCents, $paidDate, $refText);

            $this->auditRepo->log(
                $tenantId,
                $user['id'] ?? null,
                $user['name'] ?? 'Accountant',
                'invoice.paid_manual',
                "Invoice {$invoice['invoice_number']} settled manually via " . strtoupper($method) . ". Ref: " . ($refNumber ?: 'N/A') . " (₹" . number_format($amountPaidCents / 100, 2) . ")"
            );

            return [
                'success' => true,
                'message' => "Payment for invoice {$invoice['invoice_number']} recorded successfully!",
                'invoice' => $invoice,
            ];
        });
    }

    public function voidInvoice(string $invoiceId, string $tenantId, ?string $reason = null, ?array $user = null): array
    {
        $invoice = $this->invoiceRepo->findById($invoiceId, $tenantId);
        if (!$invoice) {
            return ['success' => false, 'error' => 'Invoice not found or access denied.'];
        }

        if ($invoice['status'] === 'paid') {
            return ['success' => false, 'error' => 'Cannot void an invoice that has already been settled / paid.'];
        }

        if ($invoice['status'] === 'void') {
            return ['success' => false, 'error' => 'This invoice has already been cancelled / voided.'];
        }

        $cleanReason = trim($reason ?? 'Cancelled by administrator');
        $success = $this->invoiceRepo->markAsVoid($invoiceId, $tenantId, $cleanReason);

        if ($success) {
            $this->auditRepo->log(
                $tenantId,
                $user['id'] ?? null,
                $user['name'] ?? 'User',
                'invoice.voided',
                "Invoice {$invoice['invoice_number']} marked as VOID / CANCELLED. Reason: {$cleanReason}"
            );
            return ['success' => true, 'message' => "Invoice {$invoice['invoice_number']} has been marked as void / cancelled."];
        }

        return ['success' => false, 'error' => 'Failed to void invoice. Please try again.'];
    }
}