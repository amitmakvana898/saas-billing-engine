<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class InvoiceRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Strict Tenant-Scoped Invoices listing (Row-level isolation)
     */
    public function listByTenant(string $tenantId, ?string $status = null): array
    {
        $sql = '
            SELECT i.*, 
                   COALESCE(c.name, i.customer_name, "Subscription Client") as client_display_name,
                   COALESCE(c.company_name, "") as client_company,
                   COALESCE(c.email, i.customer_email, "") as client_email
            FROM `invoices` i
            LEFT JOIN `customers` c ON c.id = i.customer_id
            WHERE i.tenant_id = :tenant_id
        ';
        $params = ['tenant_id' => $tenantId];

        if ($status && in_array($status, ['paid', 'open', 'void', 'uncollectible'])) {
            $sql .= ' AND i.status = :status';
            $params['status'] = $status;
        }

        $sql .= ' ORDER BY i.created_at DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Strict Scoped Single Invoice Lookup
     */
    public function findById(string $id, string $tenantId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT i.*, 
                   t.name as tenant_name, 
                   t.subdomain,
                   t.tax_id as tenant_gstin,
                   t.billing_address as tenant_address,
                   t.phone as tenant_phone,
                   c.name as cust_name,
                   c.company_name as cust_company,
                   c.email as cust_email,
                   c.phone as cust_phone,
                   c.address as cust_address,
                   c.gstin as cust_gstin
            FROM `invoices` i
            JOIN `tenants` t ON t.id = i.tenant_id
            LEFT JOIN `customers` c ON c.id = i.customer_id
            WHERE (i.id = :id OR i.invoice_number = :inv_num) AND i.tenant_id = :tenant_id 
            LIMIT 1
        ');
        $stmt->execute(['id' => $id, 'inv_num' => $id, 'tenant_id' => $tenantId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Public lookup by Payment Token (Unscoped, used by client on public checkout link)
     */
    public function findByPaymentToken(string $token, bool $forUpdate = false): ?array
    {
        $sql = '
            SELECT i.*, 
                   t.name as tenant_name, 
                   t.subdomain,
                   t.tax_id as tenant_gstin,
                   t.billing_address as tenant_address,
                   t.phone as tenant_phone,
                   t.logo_url as tenant_logo_url,
                   t.bank_name as tenant_bank_name,
                   t.bank_account_no as tenant_bank_account_no,
                   t.bank_ifsc as tenant_bank_ifsc,
                   t.upi_id as tenant_upi_id,
                   c.name as cust_name,
                   c.company_name as cust_company,
                   c.email as cust_email,
                   c.phone as cust_phone,
                   c.address as cust_address,
                   c.gstin as cust_gstin
            FROM `invoices` i
            JOIN `tenants` t ON t.id = i.tenant_id
            LEFT JOIN `customers` c ON c.id = i.customer_id
            WHERE i.payment_token = :token 
            LIMIT 1
        ' . ($forUpdate ? ' FOR UPDATE' : '');
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Create Invoice (Full B2B Invoice with Line Items & Payment Token)
     */
    public function create(array $data): string
    {
        $id = $data['id'] ?? bin2hex(random_bytes(16));
        $paymentToken = $data['payment_token'] ?? bin2hex(random_bytes(24));
        $status = $data['status'] ?? 'open';
        $subtotal = (int)($data['subtotal_cents'] ?? 0);
        $tax = (int)($data['tax_cents'] ?? 0);
        $discount = (int)($data['discount_cents'] ?? 0);
        $total = (int)($data['total_cents'] ?? ($subtotal + $tax - $discount));
        $paid = ($status === 'paid') ? $total : (int)($data['amount_paid_cents'] ?? 0);

        $stmt = $this->db->prepare('
            INSERT INTO `invoices`
            (`id`, `tenant_id`, `subscription_id`, `customer_id`, `customer_name`, `customer_email`, `customer_phone`, 
             `customer_address`, `customer_gstin`, `invoice_number`, `due_date`, `subtotal_cents`, `tax_cents`, 
             `discount_cents`, `total_cents`, `amount_paid_cents`, `currency`, `status`, `billing_reason`, 
             `notes`, `items_json`, `payment_token`, `payment_method`, `paid_at`, `created_at`)
            VALUES
            (:id, :tenant_id, :subscription_id, :customer_id, :customer_name, :customer_email, :customer_phone, 
             :customer_address, :customer_gstin, :invoice_number, :due_date, :subtotal_cents, :tax_cents, 
             :discount_cents, :total_cents, :amount_paid_cents, :currency, :status, :billing_reason, 
             :notes, :items_json, :payment_token, :payment_method, :paid_at, NOW())
        ');

        $stmt->execute([
            'id' => $id,
            'tenant_id' => $data['tenant_id'],
            'subscription_id' => $data['subscription_id'] ?? null,
            'customer_id' => !empty($data['customer_id']) ? $data['customer_id'] : null,
            'customer_name' => $data['customer_name'] ?? null,
            'customer_email' => $data['customer_email'] ?? null,
            'customer_phone' => $data['customer_phone'] ?? null,
            'customer_address' => $data['customer_address'] ?? null,
            'customer_gstin' => $data['customer_gstin'] ?? null,
            'invoice_number' => $data['invoice_number'],
            'due_date' => !empty($data['due_date']) ? $data['due_date'] : date('Y-m-d', strtotime('+15 days')),
            'subtotal_cents' => $subtotal,
            'tax_cents' => $tax,
            'discount_cents' => $discount,
            'total_cents' => $total,
            'amount_paid_cents' => $paid,
            'currency' => $data['currency'] ?? 'INR',
            'status' => $status,
            'billing_reason' => $data['billing_reason'] ?? 'manual_invoice',
            'notes' => $data['notes'] ?? null,
            'items_json' => is_array($data['items_json'] ?? null) ? json_encode($data['items_json']) : ($data['items_json'] ?? null),
            'payment_token' => $paymentToken,
            'payment_method' => $data['payment_method'] ?? null,
            'paid_at' => ($status === 'paid') ? date('Y-m-d H:i:s') : null,
        ]);

        return $id;
    }

    /**
     * Mark invoice as paid via online checkout or manual action
     */
    public function markAsPaid(string $id, string $method = 'online_gateway', ?int $amountPaid = null, ?string $paidAt = null, ?string $refNotes = null): bool
    {
        $sql = '
            UPDATE `invoices` 
            SET `status` = "paid", 
                `amount_paid_cents` = COALESCE(:amount_paid, `total_cents`),
                `payment_method` = :method,
                `paid_at` = COALESCE(:paid_at, NOW())' .
                ($refNotes ? ', `notes` = CONCAT(COALESCE(`notes`, ""), :ref_notes)' : '') . '
            WHERE `id` = :id
        ';
        $params = [
            'id' => $id,
            'method' => $method,
            'amount_paid' => $amountPaid,
            'paid_at' => $paidAt ?: null,
        ];
        if ($refNotes) {
            $params['ref_notes'] = "\n[SETTLEMENT REF: {$refNotes}]";
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Mark invoice as void / cancelled
     */
    public function markAsVoid(string $id, string $tenantId, ?string $reason = null): bool
    {
        $cleanReason = trim($reason ?? 'Cancelled by administrator');
        $noteAppend = "\n[CANCELLED / VOID REASON: {$cleanReason}]";

        $stmt = $this->db->prepare('
            UPDATE `invoices` 
            SET `status` = "void",
                `notes` = CONCAT(COALESCE(`notes`, ""), :note_append)
            WHERE `id` = :id AND `tenant_id` = :tenant_id AND `status` != "paid"
        ');
        return $stmt->execute([
            'id' => $id,
            'tenant_id' => $tenantId,
            'note_append' => $noteAppend,
        ]);
    }

    public function getNextInvoiceNumber(string $tenantId): string
    {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT invoice_number FROM `invoices` WHERE `tenant_id` = :tenant_id AND `invoice_number` LIKE 'INV-{$year}-%'");
        $stmt->execute(['tenant_id' => $tenantId]);
        $existing = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $maxNum = 0;
        foreach ($existing as $invNum) {
            if (preg_match('/INV-\d{4}-(\d+)/', $invNum, $m)) {
                $num = (int)$m[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $next = $maxNum + 1;
        return sprintf('INV-%s-%04d', $year, $next);
    }

    /**
     * Financial statistics for charts and dashboard metrics
     */
    public function getTenantBillingAnalytics(string $tenantId): array
    {
        // 1. Overall Totals
        $stmt = $this->db->prepare('
            SELECT 
                COUNT(*) as total_invoices,
                COALESCE(SUM(total_cents), 0) as total_volume_cents,
                COALESCE(SUM(amount_paid_cents), 0) as total_collected_cents,
                COALESCE(SUM(CASE WHEN status = "open" THEN total_cents ELSE 0 END), 0) as total_unpaid_cents,
                COALESCE(SUM(tax_cents), 0) as total_gst_cents,
                COUNT(CASE WHEN status = "paid" THEN 1 END) as count_paid,
                COUNT(CASE WHEN status = "open" THEN 1 END) as count_open,
                COUNT(CASE WHEN status = "void" THEN 1 END) as count_void
            FROM `invoices`
            WHERE `tenant_id` = :tenant_id
        ');
        $stmt->execute(['tenant_id' => $tenantId]);
        $summary = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Monthly Trend (Past 6 Months)
        $monthlyStmt = $this->db->prepare('
            SELECT 
                DATE_FORMAT(created_at, "%b %Y") as month_label,
                DATE_FORMAT(created_at, "%Y-%m") as month_key,
                COALESCE(SUM(total_cents), 0) as billed_cents,
                COALESCE(SUM(amount_paid_cents), 0) as collected_cents
            FROM `invoices`
            WHERE `tenant_id` = :tenant_id AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY month_key, month_label
            ORDER BY month_key ASC
        ');
        $monthlyStmt->execute(['tenant_id' => $tenantId]);
        $monthlyTrends = $monthlyStmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'summary' => $summary,
            'monthly' => $monthlyTrends,
        ];
    }
}