<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class SubscriptionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findActiveByTenant(string $tenantId): ?array
    {
        $stmt = $this->db->prepare('
            SELECT s.*, p.name as plan_name, p.slug as plan_slug, p.price_cents, p.billing_interval, p.features_json, p.max_seats
            FROM `subscriptions` s
            JOIN `plans` p ON p.id = s.plan_id
            WHERE s.tenant_id = :tenant_id
            ORDER BY s.created_at DESC
            LIMIT 1
        ');
        $stmt->execute(['tenant_id' => $tenantId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByGatewaySubscriptionId(string $gatewaySubId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM `subscriptions` WHERE `gateway_subscription_id` = :gid LIMIT 1');
        $stmt->execute(['gid' => $gatewaySubId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): string
    {
        $id = $data['id'] ?? bin2hex(random_bytes(16));
        $stmt = $this->db->prepare('
            INSERT INTO `subscriptions` 
            (`id`, `tenant_id`, `plan_id`, `gateway_subscription_id`, `status`, `trial_ends_at`, `current_period_start`, `current_period_end`, `created_at`)
            VALUES 
            (:id, :tenant_id, :plan_id, :gateway_subscription_id, :status, :trial_ends_at, :current_period_start, :current_period_end, NOW())
        ');
        $stmt->execute([
            'id' => $id,
            'tenant_id' => $data['tenant_id'],
            'plan_id' => $data['plan_id'],
            'gateway_subscription_id' => $data['gateway_subscription_id'] ?? null,
            'status' => $data['status'] ?? 'trialing',
            'trial_ends_at' => $data['trial_ends_at'] ?? null,
            'current_period_start' => $data['current_period_start'],
            'current_period_end' => $data['current_period_end'],
        ]);
        return $id;
    }

    public function updateStatus(string $id, string $status, ?string $canceledAt = null): bool
    {
        $stmt = $this->db->prepare('
            UPDATE `subscriptions` 
            SET `status` = :status, `canceled_at` = :canceled_at 
            WHERE `id` = :id
        ');
        return $stmt->execute([
            'id' => $id,
            'status' => $status,
            'canceled_at' => $canceledAt,
        ]);
    }

    public function updatePlan(string $id, int $planId, string $periodStart, string $periodEnd, string $status = 'active'): bool
    {
        $stmt = $this->db->prepare('
            UPDATE `subscriptions`
            SET `plan_id` = :plan_id,
                `status` = :status,
                `current_period_start` = :start,
                `current_period_end` = :end
            WHERE `id` = :id
        ');
        return $stmt->execute([
            'id' => $id,
            'plan_id' => $planId,
            'status' => $status,
            'start' => $periodStart,
            'end' => $periodEnd,
        ]);
    }
}