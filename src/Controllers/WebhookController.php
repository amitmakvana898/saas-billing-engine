<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\WebhookEngineService;

class WebhookController
{
    private WebhookEngineService $webhookEngine;

    public function __construct()
    {
        $this->webhookEngine = new WebhookEngineService();
    }

    public function handleStripe(Request $request): Response
    {
        $payload = $request->getRawBody();
        $sigHeader = $request->getHeader('Stripe-Signature');

        // 1. Cryptographic Signature Verification
        if (!$this->webhookEngine->verifySignature($payload, $sigHeader)) {
            return json_response([
                'error' => 'Invalid or missing cryptographic webhook signature.',
            ], 401);
        }

        $event = json_decode($payload, true);
        if (!$event) {
            return json_response(['error' => 'Invalid JSON payload format.'], 400);
        }

        try {
            // 2. Idempotent Processing
            $result = $this->webhookEngine->processEvent($event);
            return json_response($result, 200);
        } catch (\Throwable $e) {
            return json_response([
                'error' => 'Webhook processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook Simulator View for local live testing & interview demo
     */
    public function showSimulator(Request $request): Response
    {
        return view('dashboard.simulator', [
            'title' => 'Stripe Webhook Event Simulator - SaaSify',
            'tenant' => current_tenant(),
        ]);
    }
}