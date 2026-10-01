<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('
            SELECT u.*, t.name as tenant_name, t.subdomain, t.status as tenant_status
            FROM `users` u
            JOIN `tenants` t ON t.id = u.tenant_id
            WHERE u.email = :email
            LIMIT 1
        ');
        $stmt->execute(['email' => strtolower(trim($email))]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(string $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM `users` WHERE `id` = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByTenantAndId(string $tenantId, string $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM `users` WHERE `id` = :id AND `tenant_id` = :tenant_id LIMIT 1');
        $stmt->execute(['id' => $userId, 'tenant_id' => $tenantId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByTenantAndEmail(string $tenantId, string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM `users` WHERE `tenant_id` = :tenant_id AND `email` = :email LIMIT 1');
        $stmt->execute(['tenant_id' => $tenantId, 'email' => strtolower(trim($email))]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): string
    {
        $id = $data['id'] ?? bin2hex(random_bytes(16));
        $stmt = $this->db->prepare('
            INSERT INTO `users` (`id`, `tenant_id`, `name`, `email`, `password_hash`, `role`, `created_at`)
            VALUES (:id, :tenant_id, :name, :email, :password_hash, :role, NOW())
        ');
        $stmt->execute([
            'id' => $id,
            'tenant_id' => $data['tenant_id'],
            'name' => $data['name'],
            'email' => strtolower(trim($data['email'])),
            'password_hash' => $data['password_hash'],
            'role' => $data['role'] ?? 'member',
        ]);
        return $id;
    }

    public function listByTenant(string $tenantId): array
    {
        $stmt = $this->db->prepare('SELECT id, name, email, role, created_at FROM `users` WHERE tenant_id = :tenant_id ORDER BY created_at ASC');
        $stmt->execute(['tenant_id' => $tenantId]);
        return $stmt->fetchAll();
    }

    public function delete(string $userId, string $tenantId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM `users` WHERE `id` = :id AND `tenant_id` = :tenant_id AND `role` != "owner"');
        $stmt->execute(['id' => $userId, 'tenant_id' => $tenantId]);
        return $stmt->rowCount() > 0;
    }

    public function updateRole(string $userId, string $tenantId, string $newRole): bool
    {
        $stmt = $this->db->prepare('UPDATE `users` SET `role` = :role WHERE `id` = :id AND `tenant_id` = :tenant_id AND `role` != "owner"');
        $stmt->execute(['role' => $newRole, 'id' => $userId, 'tenant_id' => $tenantId]);
        return $stmt->rowCount() > 0;
    }

    public function updateProfile(string $userId, string $tenantId, string $name): bool
    {
        $stmt = $this->db->prepare('UPDATE `users` SET `name` = :name WHERE `id` = :id AND `tenant_id` = :tenant_id');
        return $stmt->execute(['name' => $name, 'id' => $userId, 'tenant_id' => $tenantId]);
    }

    public function updatePassword(string $userId, string $newPasswordHash): bool
    {
        $stmt = $this->db->prepare('UPDATE `users` SET `password_hash` = :hash WHERE `id` = :id');
        return $stmt->execute(['hash' => $newPasswordHash, 'id' => $userId]);
    }

    public function updatePasswordByEmail(string $email, string $newPasswordHash): bool
    {
        $stmt = $this->db->prepare('UPDATE `users` SET `password_hash` = :hash WHERE `email` = :email');
        return $stmt->execute(['hash' => $newPasswordHash, 'email' => strtolower(trim($email))]);
    }
}