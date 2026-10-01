<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class CustomerRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * List all customers scoped to tenant with their billing aggregates
     */
    public function listByTenant(string $tenantId): array
    {
        $stmt = $this->db->prepare('
            SELECT c.*, 
                   COUNT(i.id) as total_invoices,
                   COALESCE(SUM(CASE WHEN i.status = "paid" THEN i.amount_paid_cents ELSE 0 END), 0) as paid_cents,
                   COALESCE(SUM(CASE WHEN i.status = "open" THEN i.total_cents ELSE 0 END), 0) as outstanding_cents
            FROM `customers` c
            LEFT JOIN `invoices` i ON i.customer_id = c.id AND i.tenant_id = c.tenant_id
            WHERE c.tenant_id = :tenant_id
            GROUP BY c.id
            ORDER BY c.created_at DESC
        ');
        $stmt->execute(['tenant_id' => $tenantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(string $id, string $tenantId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT * FROM `customers` 
            WHERE `id` = :id AND `tenant_id` = :tenant_id 
            LIMIT 1
        ');
        $stmt->execute(['id' => $id, 'tenant_id' => $tenantId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findByEmail(string $email, string $tenantId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT * FROM `customers` 
            WHERE LOWER(email) = LOWER(:email) AND `tenant_id` = :tenant_id 
            LIMIT 1
        ');
        $stmt->execute(['email' => trim($email), 'tenant_id' => $tenantId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): string
    {
        $id = $data['id'] ?? bin2hex(random_bytes(16));
        $stmt = $this->db->prepare('
            INSERT INTO `customers`
            (`id`, `tenant_id`, `name`, `email`, `phone`, `company_name`, `gstin`, `address`, `city`, `state`, `pincode`, `created_at`)
            VALUES
            (:id, :tenant_id, :name, :email, :phone, :company_name, :gstin, :address, :city, :state, :pincode, NOW())
        ');
        $stmt->execute([
            'id' => $id,
            'tenant_id' => $data['tenant_id'],
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'phone' => trim($data['phone'] ?? ''),
            'company_name' => trim($data['company_name'] ?? ''),
            'gstin' => strtoupper(trim($data['gstin'] ?? '')),
            'address' => trim($data['address'] ?? ''),
            'city' => trim($data['city'] ?? ''),
            'state' => trim($data['state'] ?? ''),
            'pincode' => trim($data['pincode'] ?? ''),
        ]);
        return $id;
    }

    public function update(string $id, string $tenantId, array $data): bool
    {
        $stmt = $this->db->prepare('
            UPDATE `customers` SET
                `name` = :name,
                `email` = :email,
                `phone` = :phone,
                `company_name` = :company_name,
                `gstin` = :gstin,
                `address` = :address,
                `city` = :city,
                `state` = :state,
                `pincode` = :pincode,
                `updated_at` = NOW()
            WHERE `id` = :id AND `tenant_id` = :tenant_id
        ');
        return $stmt->execute([
            'id' => $id,
            'tenant_id' => $tenantId,
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'phone' => trim($data['phone'] ?? ''),
            'company_name' => trim($data['company_name'] ?? ''),
            'gstin' => strtoupper(trim($data['gstin'] ?? '')),
            'address' => trim($data['address'] ?? ''),
            'city' => trim($data['city'] ?? ''),
            'state' => trim($data['state'] ?? ''),
            'pincode' => trim($data['pincode'] ?? ''),
        ]);
    }

    public function delete(string $id, string $tenantId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM `customers` WHERE `id` = :id AND `tenant_id` = :tenant_id');
        return $stmt->execute(['id' => $id, 'tenant_id' => $tenantId]);
    }

    public function getStats(string $tenantId): array
    {
        $stmt = $this->db->prepare('
            SELECT 
                COUNT(*) as total_customers,
                COUNT(CASE WHEN phone IS NOT NULL AND phone != "" THEN 1 END) as with_phone,
                COUNT(CASE WHEN gstin IS NOT NULL AND gstin != "" THEN 1 END) as gst_registered
            FROM `customers`
            WHERE `tenant_id` = :tenant_id
        ');
        $stmt->execute(['tenant_id' => $tenantId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'total_customers' => 0,
            'with_phone' => 0,
            'gst_registered' => 0,
        ];
    }
}
