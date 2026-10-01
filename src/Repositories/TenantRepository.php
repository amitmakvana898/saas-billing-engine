<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class TenantRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findById(string $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM `tenants` WHERE `id` = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findBySubdomain(string $subdomain): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM `tenants` WHERE `subdomain` = :subdomain LIMIT 1');
        $stmt->execute(['subdomain' => strtolower(trim($subdomain))]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): string
    {
        $id = $data['id'] ?? bin2hex(random_bytes(16));
        $stmt = $this->db->prepare('
            INSERT INTO `tenants` (`id`, `name`, `subdomain`, `status`, `created_at`)
            VALUES (:id, :name, :subdomain, :status, NOW())
        ');
        $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'subdomain' => strtolower(trim($data['subdomain'])),
            'status' => $data['status'] ?? 'trial',
        ]);
        return $id;
    }

    public function updateStatus(string $id, string $status): bool
    {
        $stmt = $this->db->prepare('UPDATE `tenants` SET `status` = :status WHERE `id` = :id');
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public function updateProfile(string $id, array $data): bool
    {
        $stmt = $this->db->prepare('
            UPDATE `tenants` 
            SET `name` = :name,
                `tax_id` = :tax_id,
                `billing_address` = :billing_address,
                `phone` = :phone,
                `logo_url` = :logo_url,
                `bank_name` = :bank_name,
                `bank_account_no` = :bank_account_no,
                `bank_ifsc` = :bank_ifsc,
                `upi_id` = :upi_id
            WHERE `id` = :id
        ');
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'tax_id' => $data['tax_id'] ?? null,
            'billing_address' => $data['billing_address'] ?? null,
            'phone' => $data['phone'] ?? null,
            'logo_url' => $data['logo_url'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'bank_account_no' => $data['bank_account_no'] ?? null,
            'bank_ifsc' => $data['bank_ifsc'] ?? null,
            'upi_id' => $data['upi_id'] ?? null,
        ]);
    }

    /**
     * Platform Super Admin: Get all organizations with live subscription and seat details
     */
    public function getAllWithSubscriptionDetails(): array
    {
        $sql = '
            SELECT 
                t.*,
                s.status as subscription_status,
                s.current_period_end,
                p.name as plan_name,
                p.price_cents as plan_price,
                p.billing_interval,
                (SELECT COUNT(*) FROM `users` u WHERE u.tenant_id = t.id) as user_count,
                (SELECT COALESCE(SUM(amount_paid_cents), 0) FROM `invoices` i WHERE i.tenant_id = t.id) as total_revenue_cents
            FROM `tenants` t
            LEFT JOIN `subscriptions` s ON s.tenant_id = t.id AND s.id = (
                SELECT s2.id FROM `subscriptions` s2 WHERE s2.tenant_id = t.id ORDER BY s2.created_at DESC LIMIT 1
            )
            LEFT JOIN `plans` p ON p.id = s.plan_id
            ORDER BY t.created_at DESC
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getTotalCount(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM `tenants`');
        return (int)$stmt->fetchColumn();
    }
}