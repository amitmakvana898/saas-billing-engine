<?php

namespace App\Services;

use App\Core\Currency;

class PdfInvoiceService
{
    /**
     * Generate standalone, self-contained HTML document ready for email attachments or PDF conversion
     */
    public function renderStandaloneInvoiceHtml(array $invoice, array $tenant, array $items = [], ?array $customer = null): string
    {
        $currency = $invoice['currency'] ?? 'INR';
        $subtotal = Currency::format((int)($invoice['subtotal_cents'] ?? 0), $currency);
        $tax = Currency::format((int)($invoice['tax_cents'] ?? 0), $currency);
        $total = Currency::format((int)($invoice['amount_paid_cents'] ?? 0), $currency);

        $companyName = htmlspecialchars($tenant['name'] ?? 'SaaSify Organization', ENT_QUOTES, 'UTF-8');
        $companyGst = htmlspecialchars($tenant['tax_id'] ?? 'URP (Unregistered)', ENT_QUOTES, 'UTF-8');
        $companyAddress = nl2br(htmlspecialchars($tenant['billing_address'] ?? 'HQ Suite, Tech Park', ENT_QUOTES, 'UTF-8'));
        
        $customerName = htmlspecialchars($customer['name'] ?? $invoice['customer_name'] ?? 'Direct Customer', ENT_QUOTES, 'UTF-8');
        $customerGst = htmlspecialchars($customer['tax_id'] ?? $invoice['customer_tax_id'] ?? 'Unregistered', ENT_QUOTES, 'UTF-8');
        $customerEmail = htmlspecialchars($customer['email'] ?? $invoice['customer_email'] ?? '', ENT_QUOTES, 'UTF-8');
        
        $invoiceNumber = htmlspecialchars($invoice['invoice_number'] ?? 'INV-0000', ENT_QUOTES, 'UTF-8');
        $issueDate = date('d M Y', strtotime($invoice['created_at'] ?? 'now'));
        $status = strtoupper($invoice['status'] ?? 'OPEN');
        $statusColor = ($status === 'PAID') ? '#059669' : (($status === 'VOID') ? '#dc2626' : '#d97706');

        $rowsHtml = '';
        if (!empty($items)) {
            foreach ($items as $idx => $item) {
                $num = $idx + 1;
                $desc = htmlspecialchars($item['description'] ?? 'Service Line Item', ENT_QUOTES, 'UTF-8');
                $sac = htmlspecialchars($item['sac_code'] ?? '998313', ENT_QUOTES, 'UTF-8');
                $qty = (int)($item['quantity'] ?? 1);
                $unit = Currency::format((int)($item['unit_price_cents'] ?? 0), $currency);
                $lineTotal = Currency::format((int)($item['total_cents'] ?? 0), $currency);

                $rowsHtml .= "
                <tr style=\"border-bottom: 1px solid #e2e8f0;\">
                    <td style=\"padding: 12px; font-size: 13px; color: #64748b;\">{$num}</td>
                    <td style=\"padding: 12px; font-size: 13px; font-weight: 600; color: #1e293b;\">{$desc}</td>
                    <td style=\"padding: 12px; font-size: 13px; color: #64748b; font-family: monospace;\">{$sac}</td>
                    <td style=\"padding: 12px; font-size: 13px; text-align: center; color: #334155;\">{$qty}</td>
                    <td style=\"padding: 12px; font-size: 13px; text-align: right; color: #334155;\">{$unit}</td>
                    <td style=\"padding: 12px; font-size: 13px; text-align: right; font-weight: 600; color: #0f172a;\">{$lineTotal}</td>
                </tr>";
            }
        } else {
            $rowsHtml = "
            <tr style=\"border-bottom: 1px solid #e2e8f0;\">
                <td style=\"padding: 12px; font-size: 13px; color: #64748b;\">1</td>
                <td style=\"padding: 12px; font-size: 13px; font-weight: 600; color: #1e293b;\">SaaS Platform Subscription Cycle</td>
                <td style=\"padding: 12px; font-size: 13px; color: #64748b; font-family: monospace;\">998313</td>
                <td style=\"padding: 12px; font-size: 13px; text-align: center; color: #334155;\">1</td>
                <td style=\"padding: 12px; font-size: 13px; text-align: right; color: #334155;\">{$subtotal}</td>
                <td style=\"padding: 12px; font-size: 13px; text-align: right; font-weight: 600; color: #0f172a;\">{$subtotal}</td>
            </tr>";
        }

        return "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Tax Invoice {$invoiceNumber}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 40px; background-color: #f8fafc; color: #0f172a; }
        .invoice-box { max-width: 800px; margin: auto; background: #ffffff; padding: 36px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .header-table { width: 100%; margin-bottom: 30px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        .items-table th { background: #f1f5f9; padding: 12px; font-size: 12px; text-transform: uppercase; color: #475569; letter-spacing: 0.5px; text-align: left; }
    </style>
</head>
<body>
<div class=\"invoice-box\">
    <table class=\"header-table\">
        <tr>
            <td>
                <h1 style=\"margin: 0; color: #4f46e5; font-size: 26px; font-weight: 800;\">{$companyName}</h1>
                <p style=\"margin: 4px 0; color: #64748b; font-size: 13px;\">GSTIN: <strong>{$companyGst}</strong></p>
                <p style=\"margin: 4px 0; color: #64748b; font-size: 13px;\">{$companyAddress}</p>
            </td>
            <td style=\"text-align: right;\">
                <span class=\"badge\" style=\"background: {$statusColor}15; color: {$statusColor}; border: 1px solid {$statusColor}40;\">{$status}</span>
                <h2 style=\"margin: 8px 0 2px 0; font-size: 20px; font-weight: 700;\">TAX INVOICE</h2>
                <p style=\"margin: 0; font-size: 14px; font-weight: 600; color: #475569;\">#{$invoiceNumber}</p>
                <p style=\"margin: 2px 0 0 0; font-size: 13px; color: #94a3b8;\">Date: {$issueDate}</p>
            </td>
        </tr>
    </table>

    <hr style=\"border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;\">

    <table style=\"width: 100%; margin-bottom: 24px;\">
        <tr>
            <td style=\"width: 50%; vertical-align: top;\">
                <h3 style=\"margin: 0 0 6px 0; font-size: 12px; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.5px;\">Billed To (Client):</h3>
                <p style=\"margin: 0; font-size: 15px; font-weight: 700; color: #1e293b;\">{$customerName}</p>
                <p style=\"margin: 4px 0; font-size: 13px; color: #64748b;\">GSTIN: <strong>{$customerGst}</strong></p>
                <p style=\"margin: 2px 0; font-size: 13px; color: #64748b;\">{$customerEmail}</p>
            </td>
            <td style=\"width: 50%; vertical-align: top; text-align: right;\">
                <h3 style=\"margin: 0 0 6px 0; font-size: 12px; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.5px;\">Supply Category:</h3>
                <p style=\"margin: 0; font-size: 14px; font-weight: 600; color: #1e293b;\">B2B SaaS IT Services</p>
                <p style=\"margin: 4px 0; font-size: 13px; color: #64748b;\">Place of Supply: Intra/Inter-State</p>
            </td>
        </tr>
    </table>

    <table class=\"items-table\">
        <thead>
            <tr>
                <th style=\"width: 5%;\">#</th>
                <th style=\"width: 45%;\">Description</th>
                <th style=\"width: 15%;\">HSN/SAC</th>
                <th style=\"width: 10%; text-align: center;\">Qty</th>
                <th style=\"width: 12%; text-align: right;\">Rate</th>
                <th style=\"width: 13%; text-align: right;\">Amount</th>
            </tr>
        </thead>
        <tbody>
            {$rowsHtml}
        </tbody>
    </table>

    <table style=\"width: 100%; margin-top: 24px;\">
        <tr>
            <td style=\"width: 60%; vertical-align: top; font-size: 12px; color: #64748b;\">
                <p style=\"margin: 0; font-weight: 600; color: #475569;\">Terms & Electronic Signature:</p>
                <p style=\"margin: 4px 0;\">This is a computer generated digitally verified tax invoice issued under Rule 48 of CGST Rules, 2017.</p>
            </td>
            <td style=\"width: 40%; vertical-align: top;\">
                <table style=\"width: 100%; font-size: 14px;\">
                    <tr>
                        <td style=\"padding: 4px 0; color: #64748b;\">Subtotal:</td>
                        <td style=\"padding: 4px 0; text-align: right; font-weight: 600; color: #1e293b;\">{$subtotal}</td>
                    </tr>
                    <tr>
                        <td style=\"padding: 4px 0; color: #64748b;\">GST (18%):</td>
                        <td style=\"padding: 4px 0; text-align: right; font-weight: 600; color: #1e293b;\">{$tax}</td>
                    </tr>
                    <tr style=\"border-top: 2px solid #0f172a;\">
                        <td style=\"padding: 8px 0; font-weight: 800; color: #0f172a; font-size: 16px;\">Grand Total:</td>
                        <td style=\"padding: 8px 0; text-align: right; font-weight: 800; color: #4f46e5; font-size: 18px;\">{$total}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>";
    }
}
