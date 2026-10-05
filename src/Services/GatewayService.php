<?php

namespace App\Services;

class GatewayService
{
    /**
     * Verify Stripe Webhook Signature (Stripe-Signature header format: t=timestamp,v1=signature)
     */
    public static function verifyStripeSignature(string $payload, string $sigHeader, string $webhookSecret, int $tolerance = 300): bool
    {
        if (empty($sigHeader) || empty($webhookSecret)) {
            return false;
        }

        $timestamp = null;
        $signatures = [];

        $items = explode(',', $sigHeader);
        foreach ($items as $item) {
            $parts = explode('=', trim($item), 2);
            if (count($parts) === 2) {
                if ($parts[0] === 't') {
                    $timestamp = (int)$parts[1];
                } elseif ($parts[0] === 'v1') {
                    $signatures[] = $parts[1];
                }
            }
        }

        if (!$timestamp || empty($signatures)) {
            return false;
        }

        // Check timestamp replay window
        if (abs(time() - $timestamp) > $tolerance) {
            return false;
        }

        $signedPayload = $timestamp . '.' . $payload;
        $expectedSignature = hash_hmac('sha256', $signedPayload, $webhookSecret);

        foreach ($signatures as $sig) {
            if (hash_equals($expectedSignature, $sig)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verify Razorpay Webhook Signature (X-Razorpay-Signature HMAC SHA256)
     */
    public static function verifyRazorpaySignature(string $payload, string $receivedSignature, string $webhookSecret): bool
    {
        if (empty($receivedSignature) || empty($webhookSecret)) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);
        return hash_equals($expectedSignature, $receivedSignature);
    }

    /**
     * Generate standard UPI Payment URL
     */
    public static function buildUpiIntentUrl(string $vpa, string $payeeName, int $cents, string $invoiceNumber): string
    {
        $amount = number_format($cents / 100, 2, '.', '');
        $params = [
            'pa' => $vpa,
            'pn' => $payeeName,
            'am' => $amount,
            'cu' => 'INR',
            'tn' => 'Tax Invoice ' . $invoiceNumber,
        ];
        return 'upi://pay?' . http_build_query($params);
    }
}
