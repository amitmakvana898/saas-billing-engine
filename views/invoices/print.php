<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tax Invoice <?= e($invoice['invoice_number']) ?> - <?= e($tenant['name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: #000 !important; }
            .print-card { border: none !important; box-shadow: none !important; padding: 0 !important; border-radius: 0 !important; }
        }
    </style>
</head>
<body class="bg-[#F8FAFC] p-4 sm:p-8 font-sans antialiased text-slate-800 min-h-screen">
    <?php 
        $isPaid = ($invoice['status'] === 'paid');
        $items = json_decode($invoice['items_json'] ?? '[]', true) ?: [];
        $totalCents = (int)($invoice['total_cents'] ?? ($invoice['subtotal_cents'] + $invoice['tax_cents']));
        $clientName = $invoice['cust_name'] ?? $invoice['customer_name'] ?? 'Direct Client';

        $tenantGstin = $tenant['tax_id'] ?? $invoice['tenant_gstin'] ?? '';
        $custGstin = $invoice['cust_gstin'] ?? $invoice['customer_gstin'] ?? '';
        $tenantState = substr(trim($tenantGstin), 0, 2);
        $custState = substr(trim($custGstin), 0, 2);
        $isInterstate = (!empty($tenantState) && !empty($custState) && $tenantState !== $custState);
    ?>

    <div class="max-w-3xl mx-auto mb-6 no-print flex justify-between items-center">
        <a href="<?= app_url('/invoices/' . $invoice['id']) ?>" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-700 font-semibold hover:bg-slate-50 hover:text-slate-900 transition shadow-xs">
            <i class="fa-solid fa-arrow-left text-[10px] text-slate-500"></i>
            <span>Back to Invoice Details</span>
        </a>
        <div class="space-x-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-[#0C66E4] hover:bg-[#0055CC] text-white rounded-xl text-xs font-bold shadow-xs transition flex items-center space-x-2">
                <i class="fa-solid fa-print"></i>
                <span>Print / Save as PDF</span>
            </button>
        </div>
    </div>

    <div class="max-w-3xl mx-auto bg-white p-8 sm:p-12 rounded-2xl shadow-sm print-card border border-slate-200">
        <!-- Header -->
        <div class="flex justify-between items-start border-b border-slate-200 pb-6">
            <div>
                <?php if (!empty($tenant['logo_url'])): ?>
                    <img src="<?= e($tenant['logo_url']) ?>" alt="Logo" class="h-12 max-w-[180px] object-contain mb-3 rounded-lg border border-slate-200 p-1 bg-white">
                <?php endif; ?>
                <h1 class="text-2xl font-black text-slate-900"><?= e($tenant['name'] ?? $invoice['tenant_name']) ?></h1>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                    <?= nl2br(e($tenant['billing_address'] ?? 'Official Commercial Entity')) ?>
                </p>
                <?php if (!empty($tenant['tax_id'] ?? $invoice['tenant_gstin'])): ?>
                    <div class="text-xs font-mono font-bold text-slate-800 mt-1">
                        GSTIN: <?= e($tenant['tax_id'] ?? $invoice['tenant_gstin']) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($tenant['phone'] ?? $invoice['tenant_phone'])): ?>
                    <div class="text-xs text-slate-500">Phone: <?= e($tenant['phone'] ?? $invoice['tenant_phone']) ?></div>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <div class="text-xl font-mono font-black text-slate-900"><?= e($invoice['invoice_number']) ?></div>
                <div class="text-xs text-slate-500 mt-1">Issue Date: <strong><?= date('M d, Y', strtotime($invoice['created_at'])) ?></strong></div>
                <?php if (!empty($invoice['due_date'])): ?>
                    <div class="text-xs text-slate-500">Due Date: <strong><?= date('M d, Y', strtotime($invoice['due_date'])) ?></strong></div>
                <?php endif; ?>
                <div class="mt-2">
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider <?= $isPaid ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' ?>">
                        <?= $isPaid ? 'PAID IN FULL' : 'PAYMENT PENDING' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Billed To -->
        <div class="py-6 border-b border-slate-200 grid grid-cols-2 text-xs gap-6">
            <div>
                <span class="font-bold text-slate-400 uppercase tracking-wider block mb-1">Billed To (Customer):</span>
                <div class="font-bold text-slate-900 text-sm"><?= e($clientName) ?></div>
                <?php if (!empty($invoice['cust_company'])): ?>
                    <div class="text-[#0C66E4] font-semibold"><?= e($invoice['cust_company']) ?></div>
                <?php endif; ?>
                <?php if (!empty($invoice['cust_email'] ?? $invoice['customer_email'])): ?>
                    <div class="text-slate-600 mt-0.5"><?= e($invoice['cust_email'] ?? $invoice['customer_email']) ?></div>
                <?php endif; ?>
                <?php if (!empty($invoice['cust_phone'] ?? $invoice['customer_phone'])): ?>
                    <div class="text-slate-600 font-mono"><?= e($invoice['cust_phone'] ?? $invoice['customer_phone']) ?></div>
                <?php endif; ?>
                <?php if (!empty($invoice['cust_address'] ?? $invoice['customer_address'])): ?>
                    <div class="text-slate-500 mt-1 leading-relaxed"><?= nl2br(e($invoice['cust_address'] ?? $invoice['customer_address'])) ?></div>
                <?php endif; ?>
                <?php if (!empty($invoice['cust_gstin'] ?? $invoice['customer_gstin'])): ?>
                    <div class="text-slate-800 font-mono font-bold mt-1">Client GSTIN: <?= e($invoice['cust_gstin'] ?? $invoice['customer_gstin']) ?></div>
                <?php endif; ?>
            </div>
            <div>
                <span class="font-bold text-slate-400 uppercase tracking-wider block mb-1">Payment Information:</span>
                <div class="text-slate-700">Tax Type: <span class="font-bold text-slate-900"><?= $isInterstate ? 'Inter-State IGST (18%)' : 'Intra-State CGST (9%) + SGST (9%)' ?></span></div>
                <div class="text-slate-700 mt-0.5">Currency: <span class="font-bold text-slate-900"><?= e($invoice['currency']) ?> (&#8377;)</span></div>
                <?php if ($isPaid): ?>
                    <div class="text-slate-700 mt-0.5">Payment Method: <span class="font-bold text-emerald-700"><?= strtoupper(e($invoice['payment_method'] ?? 'Online Checkout')) ?></span></div>
                    <div class="text-slate-700 mt-0.5">Settlement Date: <span class="font-bold text-slate-900"><?= date('M d, Y', strtotime($invoice['paid_at'] ?? 'now')) ?></span></div>
                <?php else: ?>
                    <div class="text-amber-700 font-bold mt-0.5">Status: Pending Settlement</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Table -->
        <div class="py-6 border-b border-slate-200">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 uppercase font-bold text-[11px] bg-slate-50">
                        <th class="p-3">Description</th>
                        <th class="p-3 text-center w-16">Qty</th>
                        <th class="p-3 text-right w-28">Rate (&#8377;)</th>
                        <th class="p-3 text-center w-20">GST %</th>
                        <th class="p-3 text-right w-32">Total (&#8377;)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php if (empty($items)): ?>
                        <tr>
                            <td class="p-3 font-semibold text-slate-900">
                                <?= ucwords(str_replace('_', ' ', $invoice['billing_reason'])) ?>
                                <span class="text-[10px] font-mono text-slate-500 block">HSN/SAC: 998311</span>
                            </td>
                            <td class="p-3 text-center">1</td>
                            <td class="p-3 text-right font-mono"><?= format_cents((int)$invoice['subtotal_cents']) ?></td>
                            <td class="p-3 text-center">18%</td>
                            <td class="p-3 text-right font-mono font-bold text-slate-900"><?= format_cents((int)$invoice['total_cents']) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="p-3 font-semibold text-slate-900">
                                    <?= e($item['description']) ?>
                                    <?php if (!empty($item['hsn'])): ?>
                                        <span class="text-[10px] font-mono text-slate-500 block">HSN/SAC: <?= e($item['hsn']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 text-center font-bold"><?= (float)($item['qty'] ?? 1) ?></td>
                                <td class="p-3 text-right font-mono"><?= format_cents((int)($item['rate_cents'] ?? 0)) ?></td>
                                <td class="p-3 text-center font-bold"><?= (float)($item['tax_rate'] ?? 18) ?>%</td>
                                <td class="p-3 text-right font-mono font-bold text-slate-900"><?= format_cents((int)($item['amount_cents'] ?? 0)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Calculations -->
            <div class="mt-4 pt-4 border-t border-slate-200 space-y-1.5 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal:</span>
                    <span class="font-mono font-bold text-slate-900"><?= format_cents((int)$invoice['subtotal_cents']) ?></span>
                </div>
                <?php if ((int)($invoice['discount_cents'] ?? 0) > 0): ?>
                    <div class="flex justify-between text-rose-600">
                        <span>Discount:</span>
                        <span class="font-mono font-bold">-<?= format_cents((int)$invoice['discount_cents']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($isInterstate): ?>
                    <div class="flex justify-between text-slate-600">
                        <span>IGST (Integrated 18%):</span>
                        <span class="font-mono font-bold text-slate-900"><?= format_cents((int)$invoice['tax_cents']) ?></span>
                    </div>
                <?php else: ?>
                    <div class="flex justify-between text-slate-600">
                        <span>CGST (9%):</span>
                        <span class="font-mono font-bold text-slate-900"><?= format_cents((int)round((int)$invoice['tax_cents'] / 2)) ?></span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>SGST (9%):</span>
                        <span class="font-mono font-bold text-slate-900"><?= format_cents((int)round((int)$invoice['tax_cents'] / 2)) ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between text-base font-black text-slate-900 pt-2.5 border-t border-slate-200">
                    <span>Grand Total:</span>
                    <span class="text-[#0C66E4] font-mono text-xl"><?= format_cents($totalCents) ?></span>
                </div>
                <?php if ($isPaid): ?>
                    <div class="flex justify-between text-xs font-bold text-emerald-700 pt-1">
                        <span>Amount Paid:</span>
                        <span class="font-mono"><?= format_cents((int)$invoice['amount_paid_cents']) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- B2B Bank Transfer & Embedded UPI QR Code Details -->
        <?php 
            $upiVpa = !empty($tenant['upi_id']) ? $tenant['upi_id'] : 'billing@saasify.app';
            $payAmountRupees = number_format($totalCents / 100, 2, '.', '');
            $payeeName = urlencode($tenant['name'] ?? 'SaaSify Organization');
            $upiUri = "upi://pay?pa={$upiVpa}&pn={$payeeName}&am={$payAmountRupees}&cu=INR&tn=" . urlencode($invoice['invoice_number']);
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($upiUri);
        ?>
        <div class="mt-6 pt-6 border-t border-slate-200 text-xs text-slate-700">
            <span class="font-bold text-slate-900 block mb-2">
                <i class="fa-solid fa-building-columns text-blue-600 mr-1"></i> B2B Payment &amp; Instant UPI Settlement:
            </span>
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="grid grid-cols-2 gap-3 text-[11px] flex-1">
                    <?php if (!empty($tenant['bank_name'])): ?>
                        <div><span class="text-slate-500 block">Bank Name</span><strong class="text-slate-900"><?= e($tenant['bank_name']) ?></strong></div>
                    <?php endif; ?>
                    <?php if (!empty($tenant['bank_account_no'])): ?>
                        <div><span class="text-slate-500 block">Account Number</span><strong class="font-mono text-slate-900"><?= e($tenant['bank_account_no']) ?></strong></div>
                    <?php endif; ?>
                    <?php if (!empty($tenant['bank_ifsc'])): ?>
                        <div><span class="text-slate-500 block">IFSC Code</span><strong class="font-mono text-slate-900"><?= e($tenant['bank_ifsc']) ?></strong></div>
                    <?php endif; ?>
                    <div><span class="text-slate-500 block">UPI VPA</span><strong class="font-mono text-emerald-700"><?= e($upiVpa) ?></strong></div>
                </div>

                <?php if (!$isPaid): ?>
                    <!-- High-Res Live UPI QR Code -->
                    <div class="flex items-center gap-3 pl-0 sm:pl-4 border-t sm:border-t-0 sm:border-l border-slate-200 pt-3 sm:pt-0 shrink-0">
                        <img src="<?= $qrCodeUrl ?>" alt="Scan to Pay UPI" class="w-20 h-20 rounded-lg border border-slate-200 shadow-2xs bg-white p-1">
                        <div class="text-[10px] space-y-0.5">
                            <span class="font-bold text-slate-900 block flex items-center gap-1">
                                <i class="fa-solid fa-qrcode text-emerald-600"></i>
                                <span>Scan &amp; Pay via UPI</span>
                            </span>
                            <span class="text-slate-500 block">GPay, PhonePe, Paytm</span>
                            <span class="font-mono font-bold text-emerald-700 block"><?= format_cents($totalCents) ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-2 pl-4 border-l border-slate-200 text-emerald-700 font-bold text-xs">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                        <span>Settled &amp; Reconciled</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="pt-6 text-center text-xs text-slate-400">
            <p>Thank you for your business. For any invoice queries, contact <?= e($tenant['name']) ?>.</p>
        </div>
    </div>
</body>
</html>