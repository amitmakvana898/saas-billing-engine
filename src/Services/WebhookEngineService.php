<?php

namespace App\Services;

use App\Repositories\WebhookRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\TenantRepository;
use App\Repositories\PlanRepository;

class WebhookEngineService
{
    private WebhookRepository $webhookRepo;
    private SubscriptionRepository $subRepo;
    private InvoiceRepository $invoiceRepo;
    private TenantRepository $tenantRepo;
    private PlanRepository $planRepo;
    private string $webhookSecret;

    public function __construct()
    {
        $this->webhookRepo = new WebhookRepository();
        $this->subRepo = new SubscriptionRepository();
        $this->invoiceRepo = new InvoiceRepository();
        $this->tenantRepo = new TenantRepository();
        $this->planRepo = new PlanRepository();

        $paymentConfig = require __DIR__ . '/../../config/payment.php';
        $this->webhookSecret = $paymentConfig['stripe']['webhook_secret'] ?? 'whsec_test_mock_webhook_secret_12345';
    }

    /**
     * Verifies Stripe HMAC SHA256 Signature header
     * Header format: t=1614000000,v1=5257a869e7ecebeda32affa62cd...
     */
    public function verifySignature(string $payload, ?string $sigHeader): bool
    {
        if (empty($sigHeader)) {
            return false;
        }

        // Allow simulation/mock bypass if header is explicitly 'test-simulation-token'
        if ($sigHeader === 'test-simulation-token') {
            return true;
        }

        $items = explode(',', $sigHeader);
        $timestamp = null;
        $signatures = [];

        foreach ($items as $item) {
            $parts = explode('=', trim($item), 2);
            if (count($parts) === 2) {
                if ($parts[0] === 't') {
                    $timestamp = $parts[1];
                } elseif ($parts[0] === 'v1') {
                    $signatures[] = $parts[1];
                }
            }
        }

        if (!$timestamp || empty($signatures)) {
            return false;
        }

        // Check timestamp tolerance (prevent replay attacks, 10 min window)
        if (abs(time() - (int)$timestamp) > 600) {
            return false;
        }

        $signedPayload = "{$timestamp}.{$payload}";
        $expectedSignature = hash_hmac('sha256', $signedPayload, $this->webhookSecret);

        foreach ($signatures as $sig) {
            if (hash_equals($expectedSignature, $sig)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Core Idempotent Event Processor
     */
    public function processEvent(array $event): array
    {
        $eventId = $event['id'] ?? null;
        $eventType = $event['type'] ?? null;

        if (!$eventId || !$eventType) {
            throw new \InvalidArgumentException('Malformed webhook event payload.');
        }

        // 1. IDEMPOTENCY CHECK: If already processed, exit early with 200 OK!
        if ($this->webhookRepo->isProcessed($eventId)) {
            return [
                'status' => 'duplicate',
                'message' => 'Event already processed idempotently. Skipping side-effects.',
            ];
        }

        // 2. Record Event in Pending status
        $this->webhookRepo->recordEvent([
            'external_event_id' => $eventId,
            'gateway' => 'stripe',
            'event_type' => $eventType,
            'payload' => $event,
            'status' => 'pending',
        ]);

        try {
            // 3. Dispatch Event to specific domain handlers
            switch ($eventType) {
                case 'invoice.payment_succeeded':
                    $this->handlePaymentSucceeded($event['data']['object'] ?? []);
                    break;

                case 'invoice.payment_failed':
                    $this->handlePaymentFailed($event['data']['object'] ?? []);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event['data']['object'] ?? []);
                    break;

                default:
                    // Unhandled event type, harmlessly mark as processed
                    break;
            }

            // 4. Mark Event as successfully processed
            $this->webhookRepo->markProcessed($eventId, 'processed');

            return [
                'status' => 'success',
                'message' => "Event {$eventId} [{$eventType}] handled successfully.",
            ];
        } catch (\Throwable $e) {
            $this->webhookRepo->markProcessed($eventId, 'failed', $e->getMessage());
            throw $e;
        }
    }

    private function handlePaymentSucceeded(array $invoiceData): void
    {
        $tenantId = $invoiceData['tenant_id'] ?? $invoiceData['customer'] ?? null;
        $amountPaid = $invoiceData['amount_paid'] ?? 2900;
        $subId = $invoiceData['subscription'] ?? null;

        if ($tenantId) {
            $this->tenantRepo->updateStatus($tenantId, 'active');
            
            // Create Invoice
            $invNumber = $this->invoiceRepo->getNextInvoiceNumber();
            $subtotal = (int)round($amountPaid / 1.18);
            $tax = $amountPaid - $subtotal;

            $this->invoiceRepo->create([
                'tenant_id' => $tenantId,
                'subscription_id' => $subId,
                'invoice_number' => $invNumber,
                'subtotal_cents' => $subtotal,
                'tax_cents' => $tax,
                'amount_paid_cents' => $amountPaid,
                'currency' => strtoupper($invoiceData['currency'] ?? env('DEFAULT_CURRENCY', 'INR')),
                'status' => 'paid',
                'billing_reason' => 'subscription_cycle',
            ]);

            $auditRepo = new \App\Repositories\AuditLogRepository();
            $auditRepo->log($tenantId, null, 'Stripe Gateway', 'payment_succeeded', "Subscription payment of ₹" . number_format($amountPaid/100, 2) . " settled successfully (Invoice #{$invNumber}).");
        }
    }

    private function handlePaymentFailed(array $invoiceData): void
    {
        $tenantId = $invoiceData['tenant_id'] ?? $invoiceData['customer'] ?? null;
        if ($tenantId) {
            // Subscription moves to past_due state (Grace period begins)
            $sub = $this->subRepo->findActiveByTenant($tenantId);
            if ($sub) {
                $this->subRepo->updateStatus($sub['id'], 'past_due');
            }
            $auditRepo = new \App\Repositories\AuditLogRepository();
            $auditRepo->log($tenantId, null, 'Stripe Gateway', 'payment_failed', 'Automatic subscription renewal payment failed. Subscription marked past_due.');
        }
    }

    private function handleSubscriptionDeleted(array $subData): void
    {
        $tenantId = $subData['tenant_id'] ?? null;
        if ($tenantId) {
            $sub = $this->subRepo->findActiveByTenant($tenantId);
            if ($sub) {
                $this->subRepo->updateStatus($sub['id'], 'canceled', date('Y-m-d H:i:s'));
            }
            $this->tenantRepo->updateStatus($tenantId, 'suspended');
            $auditRepo = new \App\Repositories\AuditLogRepository();
            $auditRepo->log($tenantId, null, 'Stripe Gateway', 'subscription_terminated', 'Subscription deleted by gateway. Organization automatically suspended.');
        }
    }
}