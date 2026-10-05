<?php

namespace App\Jobs;

use App\Core\Database;
use PDO;

class SendInvoiceEmailJob
{
    public function handle(array $payload): void
    {
        $invoiceId = $payload['invoice_id'] ?? null;
        $recipientEmail = $payload['recipient_email'] ?? null;

        if (!$invoiceId || !$recipientEmail) {
            throw new \InvalidArgumentException('Invoice ID and Recipient Email are required for SendInvoiceEmailJob.');
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM invoices WHERE id = :id");
        $stmt->execute([':id' => $invoiceId]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$invoice) {
            throw new \RuntimeException("Invoice {$invoiceId} not found.");
        }

        // Simulating robust background SMTP dispatch or SES / SendGrid API call
        // In production, integration with PHPMailer / Symfony Mailer / AWS SES goes here
        error_log("[Queue Worker] Successfully dispatched Tax Invoice #{$invoice['invoice_number']} to {$recipientEmail}");
    }
}
