<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class PlanRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function allActive(): array
    {
        $stmt = $this->db->query('SELECT * FROM `plans` WHERE `is_active` = 1 ORDER BY `price_cents` ASC');
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM `plans` WHERE `id` = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByGatewayPlanId(string $gatewayPlanId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM `plans` WHERE `gateway_plan_id` = :pid LIMIT 1');
        $stmt->execute(['pid' => $gatewayPlanId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}