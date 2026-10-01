<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Pay Tax Invoice - SaaSify Payment Gateway') ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        .rzp-checkout-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 1.25rem;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: #000 !important; }
            .rzp-checkout-card { border: none !important; box-shadow: none !important; padding: 0 !important; border-radius: 0 !important; }
        }
    </style>
</head>
<body class="bg-[#F4F6F8] text-slate-800 antialiased min-h-screen py-8 sm:py-12 px-4 sm:px-6 selection:bg-blue-600 selection:text-white">

    <?php 
        $isPaid = ($invoice['status'] === 'paid');
        $isVoid = ($invoice['status'] === 'void');
        $items = json_decode($invoice['items_json'] ?? '[]', true) ?: [];
        $totalCents = (int)($invoice['total_cents'] ?? ($invoice['subtotal_cents'] + $invoice['tax_cents']));
        $dueCents = ($isPaid || $isVoid) ? 0 : $totalCents;

        $tenantGstin = $invoice['tenant_gstin'] ?? '';
        $custGstin = $invoice['cust_gstin'] ?? $invoice['customer_gstin'] ?? '';
        $tenantState = substr(trim($tenantGstin), 0, 2);
        $custState = substr(trim($custGstin), 0, 2);
        $isInterstate = (!empty($tenantState) && !empty($custState) && $tenantState !== $custState);

        $tenantUpi = !empty($invoice['tenant_upi_id']) ? $invoice['tenant_upi_id'] : 'billing@saasify.app';
        $upiString = "upi://pay?pa=" . urlencode($tenantUpi) . "&pn=" . urlencode($invoice['tenant_name']) . "&am=" . number_format($totalCents / 100, 2, '.', '') . "&cu=INR&tn=" . urlencode("Tax Invoice " . $invoice['invoice_number']);
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" . urlencode($upiString);
    ?>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Top Issuer Bar -->
        <div class="flex items-center justify-between no-print">
            <div class="flex items-center space-x-3">
                <?php if (!empty($invoice['tenant_logo_url'])): ?>
                    <img src="<?= e($invoice['tenant_logo_url']) ?>" alt="Logo" class="h-10 max-w-[140px] object-contain rounded-xl border border-slate-200 p-1 bg-white shadow-xs">
                <?php else: ?>
                    <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-sm font-bold text-lg">
                        <i class="fa-solid fa-bolt-lightning text-sm"></i>
                    </div>
                <?php endif; ?>
                <div>
                    <h1 class="text-sm font-extrabold text-slate-900"><?= e($invoice['tenant_name']) ?></h1>
                    <span class="text-[11px] text-slate-500 block font-mono flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                        <span>Verified Merchant &bull; Secure Checkout</span>
                    </span>
                </div>
            </div>

            <button onclick="window.print()" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg bg-white border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-xs transition">
                <i class="fa-solid fa-print text-[11px]"></i>
                <span class="hidden sm:inline">Print / Save as PDF</span>
            </button>
        </div>

        <!-- Flash Notification -->
        <?php if ($success = flash('success')): ?>
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start space-x-3 shadow-xs no-print">
                <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-lg"></i>
                <div class="text-xs font-semibold leading-relaxed"><?= e($success) ?></div>
            </div>
        <?php endif; ?>

        <?php if ($error = flash('error')): ?>
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start space-x-3 shadow-xs no-print">
                <i class="fa-solid fa-circle-exclamation text-rose-600 mt-0.5 text-lg"></i>
                <div class="text-xs font-semibold leading-relaxed"><?= e($error) ?></div>
            </div>
        <?php endif; ?>

        <!-- Invoice Checkout Card -->
        <div class="rzp-checkout-card overflow-hidden">
            <!-- Accent Top Line -->
            <div class="h-1.5 w-full <?= $isPaid ? 'bg-emerald-500' : ($isVoid ? 'bg-slate-400' : 'bg-[#0C66E4]') ?>"></div>

            <div class="p-6 sm:p-10 space-y-8">
                <!-- Status Banner & Invoice Meta -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-100">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Tax Invoice</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-mono <?= $isVoid ? 'line-through text-slate-400' : '' ?>"><?= e($invoice['invoice_number']) ?></h2>
                        <div class="text-xs text-slate-500 mt-1">
                            Issued on <strong class="text-slate-800"><?= date('F d, Y', strtotime($invoice['created_at'])) ?></strong> &bull; Due by <strong class="text-slate-800"><?= date('F d, Y', strtotime($invoice['due_date'] ?? '+15 days')) ?></strong>
                        </div>
                    </div>

                    <div>
                        <?php if ($isPaid): ?>
                            <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-xs uppercase tracking-wider shadow-xs">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                                <span>Paid in Full</span>
                            </div>
                        <?php elseif ($isVoid): ?>
                            <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-slate-100 border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider shadow-xs">
                                <i class="fa-solid fa-ban text-slate-500 text-sm"></i>
                                <span>Invoice Voided</span>
                            </div>
                        <?php else: ?>
                            <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-amber-50 border border-amber-200 text-amber-700 font-bold text-xs uppercase tracking-wider shadow-xs">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                <span>Payment Pending</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Billed From & Billed To Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Billed By</span>
                        <div class="text-sm font-bold text-slate-900"><?= e($invoice['tenant_name']) ?></div>
                        <div class="text-slate-600 mt-1"><?= nl2br(e($invoice['tenant_address'] ?? 'Official Business Entity')) ?></div>
                        <?php if (!empty($invoice['tenant_gstin'])): ?>
                            <div class="text-slate-700 font-mono mt-1">GSTIN: <strong class="text-slate-900"><?= e($invoice['tenant_gstin']) ?></strong></div>
                        <?php endif; ?>
                        <?php if (!empty($invoice['tenant_phone'])): ?>
                            <div class="text-slate-600 mt-0.5">Phone: <?= e($invoice['tenant_phone']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-1.5">Billed To (Customer)</span>
                        <div class="text-sm font-bold text-slate-900">
                            <?= e($invoice['cust_name'] ?? $invoice['customer_name'] ?? 'Direct Customer') ?>
                        </div>
                        <?php if (!empty($invoice['cust_company'])): ?>
                            <div class="text-blue-700 font-semibold mt-0.5"><?= e($invoice['cust_company']) ?></div>
                        <?php endif; ?>
                        <div class="text-slate-600 mt-1"><?= e($invoice['cust_email'] ?? $invoice['customer_email'] ?? '') ?></div>
                        <?php if (!empty($invoice['cust_phone'] ?? $invoice['customer_phone'])): ?>
                            <div class="text-slate-600 font-mono"><?= e($invoice['cust_phone'] ?? $invoice['customer_phone']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($invoice['cust_address'] ?? $invoice['customer_address'])): ?>
                            <div class="text-slate-500 mt-1 leading-relaxed"><?= nl2br(e($invoice['cust_address'] ?? $invoice['customer_address'])) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($invoice['cust_gstin'] ?? $invoice['customer_gstin'])): ?>
                            <div class="text-slate-700 font-mono mt-1">GSTIN: <strong class="text-slate-900"><?= e($invoice['cust_gstin'] ?? $invoice['customer_gstin']) ?></strong></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Line Items Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-[11px] font-bold border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-3">Description</th>
                                <th class="px-4 py-3 text-center">Qty</th>
                                <th class="px-4 py-3 text-right">Unit Rate</th>
                                <th class="px-4 py-3 text-center">GST %</th>
                                <th class="px-5 py-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td class="px-5 py-4">
                                        <div class="font-medium text-slate-900"><?= ucwords(str_replace('_', ' ', $invoice['billing_reason'])) ?></div>
                                        <span class="text-[10px] font-mono text-slate-500 block">HSN/SAC: 998311</span>
                                    </td>
                                    <td class="px-4 py-4 text-center font-bold">1</td>
                                    <td class="px-4 py-4 text-right font-mono"><?= format_cents((int)$invoice['subtotal_cents']) ?></td>
                                    <td class="px-4 py-4 text-center font-bold">18%</td>
                                    <td class="px-5 py-4 text-right font-mono font-bold text-slate-900"><?= format_cents((int)$invoice['total_cents']) ?></td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-slate-900"><?= e($item['description']) ?></div>
                                            <?php if (!empty($item['hsn'])): ?>
                                                <span class="text-[10px] font-mono text-slate-500 block">HSN/SAC: <?= e($item['hsn']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-4 text-center font-bold"><?= (float)($item['qty'] ?? 1) ?></td>
                                        <td class="px-4 py-4 text-right font-mono"><?= format_cents((int)($item['rate_cents'] ?? 0)) ?></td>
                                        <td class="px-4 py-4 text-center font-bold"><?= (float)($item['tax_rate'] ?? 18) ?>%</td>
                                        <td class="px-5 py-4 text-right font-mono font-bold text-slate-900"><?= format_cents((int)($item['amount_cents'] ?? 0)) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Totals Breakdown -->
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6 pt-4 border-t border-slate-100">
                    <div class="text-xs text-slate-500 max-w-sm space-y-1">
                        <span class="font-bold text-slate-700 block">Terms &amp; Conditions:</span>
                        <p><?= nl2br(e($invoice['notes'] ?? 'All payments are securely processed and GST-compliant. Please retain this invoice for your tax filing.')) ?></p>
                    </div>

                    <div class="w-full sm:w-72 space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600 font-medium">Subtotal</span>
                            <span class="font-mono font-bold text-slate-900"><?= format_cents((int)$invoice['subtotal_cents']) ?></span>
                        </div>
                        <?php if ((int)($invoice['discount_cents'] ?? 0) > 0): ?>
                            <div class="flex items-center justify-between text-rose-600">
                                <span class="font-medium">Discount</span>
                                <span class="font-mono font-bold">-<?= format_cents((int)$invoice['discount_cents']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($isInterstate): ?>
                            <div class="flex items-center justify-between text-blue-700 font-bold">
                                <span>IGST (Integrated 18%)</span>
                                <span class="font-mono"><?= format_cents((int)$invoice['tax_cents']) ?></span>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600 font-medium">CGST (9%)</span>
                                <span class="font-mono font-bold text-slate-800"><?= format_cents((int)round((int)$invoice['tax_cents'] / 2)) ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-600 font-medium">SGST (9%)</span>
                                <span class="font-mono font-bold text-slate-800"><?= format_cents((int)round((int)$invoice['tax_cents'] / 2)) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="pt-2 border-t border-slate-200 flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-900">Total Due</span>
                            <span class="text-2xl font-black text-[#0C66E4] font-mono"><?= format_cents($totalCents) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Direct B2B Bank Transfer Box -->
                <?php if (!empty($invoice['tenant_bank_name']) || !empty($invoice['tenant_bank_account_no'])): ?>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-1.5">
                        <span class="font-bold text-slate-900 block">
                            <i class="fa-solid fa-building-columns text-blue-600 mr-1"></i> B2B Direct Settlement Details (NEFT / RTGS / IMPS):
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 text-[11px] pt-1">
                            <?php if (!empty($invoice['tenant_bank_name'])): ?>
                                <div><span class="text-slate-500 block">Bank Name</span><strong class="text-slate-900"><?= e($invoice['tenant_bank_name']) ?></strong></div>
                            <?php endif; ?>
                            <?php if (!empty($invoice['tenant_bank_account_no'])): ?>
                                <div><span class="text-slate-500 block">Account Number</span><strong class="font-mono text-slate-900"><?= e($invoice['tenant_bank_account_no']) ?></strong></div>
                            <?php endif; ?>
                            <?php if (!empty($invoice['tenant_bank_ifsc'])): ?>
                                <div><span class="text-slate-500 block">IFSC Code</span><strong class="font-mono text-slate-900"><?= e($invoice['tenant_bank_ifsc']) ?></strong></div>
                            <?php endif; ?>
                            <?php if (!empty($invoice['tenant_upi_id'])): ?>
                                <div><span class="text-slate-500 block">UPI VPA</span><strong class="font-mono text-emerald-700"><?= e($invoice['tenant_upi_id']) ?></strong></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Payment Action or Settlement Receipt -->
                <?php if ($isPaid): ?>
                    <div class="p-6 rounded-xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-base shadow-xs">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <span class="text-xs font-black text-emerald-900 uppercase tracking-wide block">Payment Completed</span>
                                <span class="text-xs text-emerald-700">
                                    Settled on <?= date('M d, Y H:i', strtotime($invoice['paid_at'] ?? 'now')) ?> &bull; Method: <strong><?= strtoupper(e($invoice['payment_method'] ?? 'ONLINE')) ?></strong>
                                </span>
                            </div>
                        </div>

                        <button onclick="window.print()" class="px-5 py-2.5 rounded-lg bg-white border border-emerald-300 text-emerald-800 font-bold text-xs shadow-xs hover:bg-emerald-50 transition flex items-center gap-2">
                            <i class="fa-solid fa-download"></i>
                            <span>Download / Print Receipt</span>
                        </button>
                    </div>
                <?php elseif ($isVoid): ?>
                    <div class="p-6 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-base shadow-xs">
                                <i class="fa-solid fa-ban"></i>
                            </div>
                            <div>
                                <span class="text-xs font-black text-slate-800 uppercase tracking-wide block">Invoice Cancelled / Voided</span>
                                <span class="text-xs text-slate-500">
                                    This invoice has been voided by the issuer. No payment is required.
                                </span>
                            </div>
                        </div>

                        <button onclick="window.print()" class="px-5 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 font-bold text-xs shadow-xs hover:bg-slate-50 transition flex items-center gap-2">
                            <i class="fa-solid fa-print"></i>
                            <span>Print Record</span>
                        </button>
                    </div>
                <?php else: ?>
                    <!-- Razorpay Standard Interactive Checkout -->
                    <div class="mt-8 pt-8 border-t border-slate-200 space-y-6 no-print">
                        <div class="text-center sm:text-left">
                            <h3 class="text-lg font-black text-slate-900">Complete Payment Online</h3>
                            <p class="text-xs text-slate-500">Choose your preferred Indian payment method to settle this tax invoice instantly.</p>
                        </div>

                        <!-- Payment Method Selector Tabs -->
                        <div class="flex items-center space-x-2 border-b border-slate-200 text-xs font-bold">
                            <button type="button" onclick="switchTab('upi')" id="tab-upi" class="pay-tab py-2.5 px-4 border-b-2 border-blue-600 text-blue-700 font-bold flex items-center space-x-2">
                                <i class="fa-solid fa-mobile-screen"></i>
                                <span>UPI (GPay / PhonePe)</span>
                            </button>
                            <button type="button" onclick="switchTab('card')" id="tab-card" class="pay-tab py-2.5 px-4 border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-credit-card"></i>
                                <span>Debit / Credit Card</span>
                            </button>
                            <button type="button" onclick="switchTab('netbanking')" id="tab-netbanking" class="pay-tab py-2.5 px-4 border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center space-x-2">
                                <i class="fa-solid fa-building-columns"></i>
                                <span>Net Banking</span>
                            </button>
                        </div>

                        <!-- Payment Form -->
                        <form action="<?= app_url('/pay/' . $invoice['payment_token']) ?>" method="POST" id="checkoutForm" class="space-y-4">
                            <?= csrf_field() ?>
                            <input type="hidden" name="payment_method" id="selectedMethod" value="upi">

                            <!-- UPI Tab Pane -->
                            <div id="pane-upi" class="tab-pane space-y-4">
                                <div class="p-5 rounded-xl bg-blue-50/60 border border-blue-100 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm shrink-0 text-center">
                                        <img src="<?= $qrUrl ?>" alt="UPI QR Code" class="w-36 h-36 mx-auto rounded-lg">
                                        <span class="text-[10px] text-slate-600 font-bold block mt-1.5">Scan with any UPI App</span>
                                    </div>
                                    <div class="space-y-3 flex-1 text-center sm:text-left">
                                        <div>
                                            <span class="text-xs font-black text-slate-900 block">Instant QR &amp; Mobile UPI Payment</span>
                                            <p class="text-xs text-slate-600 mt-0.5">Scan this QR code with Google Pay, PhonePe, Paytm, or BHIM app on your phone to settle this invoice.</p>
                                        </div>
                                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                                            <span class="px-2.5 py-1 rounded-md bg-white border border-slate-200 text-[11px] font-bold text-slate-700 shadow-2xs">Google Pay</span>
                                            <span class="px-2.5 py-1 rounded-md bg-white border border-slate-200 text-[11px] font-bold text-purple-700 shadow-2xs">PhonePe</span>
                                            <span class="px-2.5 py-1 rounded-md bg-white border border-slate-200 text-[11px] font-bold text-blue-700 shadow-2xs">Paytm</span>
                                            <span class="px-2.5 py-1 rounded-md bg-white border border-slate-200 text-[11px] font-bold text-emerald-700 shadow-2xs">BHIM UPI</span>
                                        </div>
                                        <div class="pt-2">
                                            <div class="text-[11px] text-slate-600 mb-1">
                                                Merchant UPI ID: <strong class="font-mono text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200"><?= e($tenantUpi) ?></strong>
                                            </div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Or Enter Client UPI ID (VPA)</label>
                                            <input type="text" name="upi_id" placeholder="e.g. client@okhdfcbank" value="client@okhdfcbank" 
                                                   class="w-full px-3.5 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-900 text-xs font-mono font-medium outline-none focus:border-blue-600 shadow-2xs">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Tab Pane -->
                            <div id="pane-card" class="tab-pane hidden space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Card Number</label>
                                    <div class="relative">
                                        <i class="fa-solid fa-credit-card absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                                        <input type="text" placeholder="4242 •••• •••• 4242" value="4242 4242 4242 4242" 
                                               class="w-full pl-9 pr-4 py-3 rounded-lg bg-slate-50 border border-slate-300 text-slate-900 text-xs font-mono font-medium outline-none focus:bg-white focus:border-blue-600">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Valid Thru</label>
                                        <input type="text" placeholder="MM/YY" value="12/28" 
                                               class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-slate-300 text-slate-900 text-xs font-mono font-medium outline-none focus:bg-white focus:border-blue-600">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">CVV Code</label>
                                        <input type="password" placeholder="123" value="123" maxlength="4" 
                                               class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-slate-300 text-slate-900 text-xs font-mono font-medium outline-none focus:bg-white focus:border-blue-600">
                                    </div>
                                </div>
                            </div>

                            <!-- Net Banking Pane -->
                            <div id="pane-netbanking" class="tab-pane hidden space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Select Your Indian Bank</label>
                                    <select class="w-full px-3.5 py-3 rounded-lg bg-slate-50 border border-slate-300 text-slate-900 text-xs font-bold outline-none focus:bg-white focus:border-blue-600">
                                        <option value="HDFC">HDFC Bank</option>
                                        <option value="SBI">State Bank of India</option>
                                        <option value="ICICI">ICICI Bank</option>
                                        <option value="AXIS">Axis Bank</option>
                                        <option value="KOTAK">Kotak Mahindra Bank</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Pay Button -->
                            <div class="pt-4">
                                <button type="submit" 
                                        class="w-full py-3.5 px-6 rounded-lg bg-[#0C66E4] hover:bg-[#0055CC] text-white font-extrabold text-sm shadow-md shadow-blue-500/25 transition flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-lock text-xs"></i>
                                    <span>Pay <?= format_cents($totalCents) ?> Securely Now &rarr;</span>
                                </button>
                                <span class="block text-center text-[11px] text-slate-400 mt-2">
                                    <i class="fa-solid fa-shield-halved text-emerald-600 mr-1"></i> 256-Bit SSL Encrypted &bull; 100% Secure Payment Processing
                                </span>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-slate-400 no-print">
            Powered by <strong class="text-slate-700">SaaSify Invoicing Gateway</strong> &bull; Secured with SHA-256 HMAC
        </div>
    </div>

    <script>
    function switchTab(type) {
        document.querySelectorAll('.pay-tab').forEach(t => {
            t.className = 'pay-tab py-2.5 px-4 border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center space-x-2';
        });
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));

        document.getElementById('tab-' + type).className = 'pay-tab py-2.5 px-4 border-b-2 border-blue-600 text-blue-700 font-bold flex items-center space-x-2';
        document.getElementById('pane-' + type).classList.remove('hidden');
        document.getElementById('selectedMethod').value = type;
    }
    </script>
</body>
</html>
