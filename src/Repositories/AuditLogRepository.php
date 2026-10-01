<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class AuditLogRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function log(string $tenantId, ?string $userId, ?string $userName, string $action, string $description): void
    {
        $stmt = $this->db->prepare('
            INSERT INTO `audit_logs` (`tenant_id`, `user_id`, `user_name`, `action`, `description`, `created_at`)
            VALUES (:tenant_id, :user_id, :user_name, :action, :description, NOW())
        ');
        $stmt->execute([
            'tenant_id' => $tenantId,
            'user_id' => $userId,
            'user_name' => $userName,
            'action' => $action,
            'description' => $description,
        ]);
    }

    public function listRecent(string $tenantId, int $limit = 10): array
    {
        $stmt = $this->db->prepare('
            SELECT * FROM `audit_logs` 
            WHERE `tenant_id` = :tenant_id 
            ORDER BY `created_at` DESC 
            LIMIT :lim
        ');
        $stmt->bindValue(':tenant_id', $tenantId, PDO::PARAM_STR);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
