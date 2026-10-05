<?php

namespace App\Jobs;

use App\Core\Database;
use PDO;

class DispatchWebhookJob
{
    public function handle(array $payload): void
    {
        $targetUrl = $payload['url'] ?? null;
        $eventData = $payload['event'] ?? [];
        $secret = $payload['secret'] ?? '';

        if (!$targetUrl) {
            throw new \InvalidArgumentException('Target webhook URL is required.');
        }

        $jsonPayload = json_encode($eventData, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', $jsonPayload, $secret);

        $ch = curl_init($targetUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-SaaSify-Signature: ' . $signature,
            'User-Agent: SaaSify-Webhook-Dispatcher/2.0'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new \RuntimeException("Webhook dispatch failed with network error: {$curlError}");
        }

        if ($httpCode >= 400 || $httpCode === 0) {
            throw new \RuntimeException("Webhook endpoint returned failure HTTP status {$httpCode}");
        }

        error_log("[Queue Worker] Webhook successfully delivered to {$targetUrl} (HTTP {$httpCode})");
    }
}
