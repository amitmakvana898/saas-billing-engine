<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class WebhookRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function isProcessed(string $externalEventId): bool
    {
        $stmt = $this->db->prepare('SELECT status FROM `webhook_events` WHERE `external_event_id` = :id LIMIT 1');
        $stmt->execute(['id' => $externalEventId]);
        $row = $stmt->fetch();
        return $row && ($row['status'] === 'processed');
    }

    public function recordEvent(array $data): string
    {
        $id = $data['id'] ?? bin2hex(random_bytes(16));
        $stmt = $this->db->prepare('
            INSERT INTO `webhook_events`
            (`id`, `gateway`, `external_event_id`, `event_type`, `payload`, `status`, `created_at`)
            VALUES
            (:id, :gateway, :external_event_id, :event_type, :payload, :status, NOW())
        ');
        $stmt->execute([
            'id' => $id,
            'gateway' => $data['gateway'] ?? 'stripe',
            'external_event_id' => $data['external_event_id'],
            'event_type' => $data['event_type'],
            'payload' => is_string($data['payload']) ? $data['payload'] : json_encode($data['payload']),
            'status' => $data['status'] ?? 'pending',
        ]);
        return $id;
    }

    public function markProcessed(string $externalEventId, string $status = 'processed', ?string $errorMessage = null): bool
    {
        $stmt = $this->db->prepare('
            UPDATE `webhook_events` 
            SET `status` = :status, `error_message` = :error, `processed_at` = NOW() 
            WHERE `external_event_id` = :id
        ');
        return $stmt->execute([
            'id' => $externalEventId,
            'status' => $status,
            'error' => $errorMessage,
        ]);
    }

    public function listAll(int $limit = 50): array
    {
        $stmt = $this->db->prepare('SELECT * FROM `webhook_events` ORDER BY `created_at` DESC LIMIT :lim');
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}