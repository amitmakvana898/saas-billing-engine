<?php

namespace App\Core;

use PDO;
use Throwable;

class Queue
{
    /**
     * Push a new job onto the database queue
     */
    public static function push(string $handler, array $payload = [], string $queue = 'default', int $delaySeconds = 0): int
    {
        $db = Database::getConnection();
        $availableAt = date('Y-m-d H:i:s', time() + $delaySeconds);

        $stmt = $db->prepare("
            INSERT INTO jobs (queue, handler, payload, status, available_at, created_at)
            VALUES (:queue, :handler, :payload, 'pending', :available_at, NOW())
        ");

        $stmt->execute([
            ':queue' => $queue,
            ':handler' => $handler,
            ':payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
            ':available_at' => $availableAt,
        ]);

        return (int)$db->lastInsertId();
    }

    /**
     * Reserve and fetch the next pending job from the queue
     */
    public static function pop(string $queue = 'default'): ?array
    {
        $db = Database::getConnection();

        return Database::transaction(function (PDO $pdo) use ($queue) {
            $stmt = $pdo->prepare("
                SELECT * FROM jobs 
                WHERE queue = :queue 
                  AND status = 'pending' 
                  AND available_at <= NOW()
                ORDER BY id ASC 
                LIMIT 1 
                FOR UPDATE
            ");
            $stmt->execute([':queue' => $queue]);
            $job = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$job) {
                return null;
            }

            $update = $pdo->prepare("
                UPDATE jobs 
                SET status = 'processing', attempts = attempts + 1, reserved_at = NOW() 
                WHERE id = :id
            ");
            $update->execute([':id' => $job['id']]);

            $job['payload'] = json_decode($job['payload'], true) ?: [];
            return $job;
        });
    }

    /**
     * Process a single job
     */
    public static function processNext(string $queue = 'default'): ?bool
    {
        $job = self::pop($queue);
        if (!$job) {
            return null; // No job waiting
        }

        $db = Database::getConnection();
        $handlerClass = $job['handler'];

        try {
            if (!class_exists($handlerClass)) {
                throw new \RuntimeException("Queue Handler class '{$handlerClass}' does not exist.");
            }

            $instance = new $handlerClass();
            if (!method_exists($instance, 'handle')) {
                throw new \RuntimeException("Handler '{$handlerClass}' must implement a public handle(array \$payload) method.");
            }

            // Execute the job
            $instance->handle($job['payload']);

            // Mark completed
            $stmt = $db->prepare("
                UPDATE jobs 
                SET status = 'completed', completed_at = NOW() 
                WHERE id = :id
            ");
            $stmt->execute([':id' => $job['id']]);

            return true;
        } catch (Throwable $e) {
            $attempts = (int)$job['attempts'];
            $maxAttempts = (int)($job['max_attempts'] ?? 3);
            $newStatus = ($attempts >= $maxAttempts) ? 'failed' : 'pending';
            $retryDelay = $attempts * 60; // Exponential backoff (1m, 2m, 3m)
            $nextAvailable = date('Y-m-d H:i:s', time() + $retryDelay);

            $stmt = $db->prepare("
                UPDATE jobs 
                SET status = :status, 
                    error_message = :error, 
                    available_at = :available_at 
                WHERE id = :id
            ");
            $stmt->execute([
                ':status' => $newStatus,
                ':error' => $e->getMessage() . "\n" . $e->getTraceAsString(),
                ':available_at' => $nextAvailable,
                ':id' => $job['id'],
            ]);

            return false;
        }
    }

    /**
     * Process all waiting pending jobs in batch
     */
    public static function processAll(string $queue = 'default', int $limit = 50): int
    {
        $processed = 0;
        for ($i = 0; $i < $limit; $i++) {
            $result = self::processNext($queue);
            if ($result === null) {
                break;
            }
            $processed++;
        }
        return $processed;
    }
}
