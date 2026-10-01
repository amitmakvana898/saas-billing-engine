<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class ApiKeyRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function listByTenant(string $tenantId): array
    {
        $stmt = $this->db->prepare('
            SELECT id, tenant_id, name, key_prefix, status, last_used_at, created_at
            FROM `api_keys`
            WHERE tenant_id = :tenant_id
            ORDER BY created_at DESC
        ');
        $stmt->execute(['tenant_id' => $tenantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $tenantId, string $name, string $keyPrefix, string $keyHash): string
    {
        $id = bin2hex(random_bytes(16));
        $stmt = $this->db->prepare('
            INSERT INTO `api_keys` (id, tenant_id, name, key_prefix, key_hash, status, created_at)
            VALUES (:id, :tenant_id, :name, :key_prefix, :key_hash, "active", NOW())
        ');
        $stmt->execute([
            'id' => $id,
            'tenant_id' => $tenantId,
            'name' => $name,
            'key_prefix' => $keyPrefix,
            'key_hash' => $keyHash,
        ]);
        return $id;
    }

    public function findByHash(string $keyHash): ?array
    {
        $stmt = $this->db->prepare('
            SELECT k.*, t.id as tenant_id, t.name as tenant_name, t.subdomain, t.status as tenant_status
            FROM `api_keys` k
            JOIN `tenants` t ON t.id = k.tenant_id
            WHERE k.key_hash = :hash AND k.status = "active" AND t.status = "active"
            LIMIT 1
        ');
        $stmt->execute(['hash' => $keyHash]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function updateLastUsed(string $id): void
    {
        $stmt = $this->db->prepare('UPDATE `api_keys` SET last_used_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function revoke(string $id, string $tenantId): bool
    {
        $stmt = $this->db->prepare('UPDATE `api_keys` SET status = "revoked" WHERE id = :id AND tenant_id = :tenant_id');
        return $stmt->execute([
            'id' => $id,
            'tenant_id' => $tenantId,
        ]);
    }
}
