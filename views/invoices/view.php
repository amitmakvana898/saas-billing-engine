<?php
$inv_status = strtolower($invoice['status'] ?? 'open');
$isPaid = ($inv_status === 'paid' || $inv_status === 'settled');
$isVoid = ($inv_status === 'void');
$isOverdue = (!$isPaid && !$isVoid && !empty($invoice['due_date']) && strtotime($invoice['due_date']) < strtotime('today'));
$daysOverdue = $isOverdue ? (int)floor((time() - strtotime($invoice['due_date'])) / 86400) : 0;

if ($isPaid) {
    $status_class = 'bg-emerald-50 text-emerald-700 border-emerald-200';
    $status_label = '● Paid in Full';
    $accent_bar = 'bg-emerald-500';
} elseif ($isVoid) {
    $status_class = 'bg-slate-100 text-slate-700 border-slate-300';
    $status_label = '✕ Void / Cancelled';
    $accent_bar = 'bg-slate-400';
} elseif ($isOverdue) {
    $status_class = 'bg-rose-50 text-rose-700 border-rose-200';
    $status_label = "● Overdue ({$daysOverdue}d late)";
    $accent_bar = 'bg-rose-500';
} else {
    $status_class = 'bg-amber-50 text-amber-700 border-amber-200';
    $status_label = '● Payment Pending';
    $accent_bar = 'bg-[#0C66E4]';
}

$items = json_decode($invoice['items_json'] ?? '[]', true) ?: [];
$totalCents = (int)($invoice['total_cents'] ?? ($invoice['subtotal_cents'] + $invoice['tax_cents']));
$publicPayUrl = app_url('/pay/' . ($invoice['payment_token'] ?? $invoice['id']));
$clientName = $invoice['cust_name'] ?? $invoice['customer_name'] ?? 'Direct Client';
$waText = urlencode("Hello {$clientName}, please find your tax invoice {$invoice['invoice_number']} for " . format_cents($totalCents) . ". You can view details and pay online here: {$publicPayUrl}");

$tenantGstin = $tenant['tax_id'] ?? $invoice['tenant_gstin'] ?? '';
$custGstin = $invoice['cust_gstin'] ?? $invoice['customer_gstin'] ?? '';
$tenantState = substr(trim($tenantGstin), 0, 2);
$custState = substr(trim($custGstin), 0, 2);
$isInterstate = (!empty($tenantState) && !empty($custState) && $tenantState !== $custState);
?>
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Flash Messages -->
    <?php if ($success = flash('success')): ?>
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center space-x-2 shadow-xs">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span><?= e($success) ?></span>
        </div>
    <?php endif; ?>
    <?php if ($error = flash('error')): ?>
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center space-x-2 shadow-xs">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span><?= e($error) ?></span>
        </div>
    <?php endif; ?>

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <a href="<?= app_url('/invoices') ?>" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-lg bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-blue-700 transition shadow-xs w-fit">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Back to Invoices</span>
        </a>

        <div class="flex items-center flex-wrap gap-2">
            <!-- Offline Settlement Action Button -->
            <?php if (!$isPaid && !$isVoid): ?>
                <button type="button" onclick="document.getElementById('recordPaymentModal').classList.remove('hidden')" 
                        class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Record Payment</span>
                </button>

                <button type="button" onclick="document.getElementById('voidInvoiceModal').classList.remove('hidden')" 
                        class="inline-flex items-center space-x-1.5 px-3 py-2 rounded-lg bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-bold shadow-xs transition">
                    <i class="fa-solid fa-ban text-[11px]"></i>
                    <span>Void Invoice</span>
                </button>
            <?php endif; ?>

            <!-- Copy Payment Link Button -->
            <?php if (!$isVoid): ?>
                <button onclick="copyPaymentLink('<?= $publicPayUrl ?>')" 
                        class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-blue-700 shadow-xs transition">
                    <i class="fa-regular fa-copy text-blue-600"></i>
                    <span>Copy Link</span>
                </button>

                <!-- Share WhatsApp -->
                <a href="https://api.whatsapp.com/send?text=<?= $waText ?>" target="_blank" 
                   class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold hover:bg-emerald-100 shadow-xs transition">
                    <i class="fa-brands fa-whatsapp text-emerald-600 text-sm"></i>
                    <span>WhatsApp</span>
                </a>
            <?php endif; ?>

            <!-- Email Client -->
            <button type="button" onclick="document.getElementById('sendEmailModal').classList.remove('hidden')" 
                    class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 text-xs font-bold shadow-xs transition">
                <i class="fa-regular fa-envelope text-indigo-600"></i>
                <span>Email Client</span>
            </button>

            <!-- Download PDF Directly -->
            <button type="button" onclick="downloadInvoicePDF()" id="downloadPdfBtn"
                    class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition">
                <i class="fa-solid fa-file-arrow-down text-rose-400"></i>
                <span id="downloadPdfLabel">Download PDF</span>
            </button>

            <!-- Print View -->
            <a href="<?= app_url('/invoices/' . $invoice['id'] . '/print') ?>" target="_blank" 
               class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs shadow-xs transition">
                <i class="fa-solid fa-print"></i>
                <span>Print</span>
            </a>
        </div>
    </div>

    <!-- Razorpay Online Payment Link Notice Banner -->
    <?php if (!$isPaid && !$isVoid): ?>
        <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs shadow-xs">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center text-xs shrink-0 shadow-xs">
                    <i class="fa-solid fa-link"></i>
                </div>
                <div>
                    <span class="font-bold text-slate-900 block text-xs">Client Online Checkout Portal</span>
                    <span class="text-slate-600 text-[11px]">Instant UPI QR, Debit/Credit Card &amp; Net Banking settlement for this invoice.</span>
                </div>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <button onclick="copyPaymentLink('<?= $publicPayUrl ?>')" 
                        class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-blue-300 hover:text-blue-700 text-slate-700 font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                    <i class="fa-regular fa-copy text-blue-600 text-xs"></i>
                    <span>Copy Link</span>
                </button>
                <a href="<?= $publicPayUrl ?>" target="_blank" class="px-3.5 py-1.5 rounded-lg bg-[#0C66E4] hover:bg-[#0055CC] text-white font-bold text-xs transition shadow-xs whitespace-nowrap">
                    Open Portal &rarr;
                </a>
            </div>
        </div>
    <?php elseif ($isVoid): ?>
        <div class="p-4 rounded-xl bg-slate-100 border border-slate-300 text-slate-700 text-xs font-medium flex items-center space-x-2 shadow-xs">
            <i class="fa-solid fa-ban text-slate-500 text-base"></i>
            <span><strong>Notice:</strong> This invoice has been marked as <strong>VOID / CANCELLED</strong>. It is no longer valid for collection or payment processing.</span>
        </div>
    <?php endif; ?>

    <!-- Main Printable Tax Invoice Card -->
    <div id="invoicePrintableCard" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Top Accent Bar -->
        <div class="h-1.5 w-full <?= $accent_bar ?>"></div>

        <div class="p-6 sm:p-10 space-y-8">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6 pb-6 border-b border-slate-100">
                <div>
                    <?php if (!empty($tenant['logo_url'])): ?>
                        <img src="<?= e($tenant['logo_url']) ?>" alt="Logo" class="h-12 max-w-[180px] object-contain mb-3 rounded-lg border border-slate-200 p-1 bg-white">
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-xl shadow-xs mb-3">
                            <i class="fa-solid fa-bolt-lightning text-sm"></i>
                        </div>
                    <?php endif; ?>
                    <h2 class="text-2xl font-black text-slate-900"><?= e($tenant['name'] ?? $invoice['tenant_name']) ?></h2>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        <?= nl2br(e($tenant['billing_address'] ?? 'Official Commercial Entity')) ?>
                    </p>
                    <?php if (!empty($tenant['tax_id'] ?? $invoice['tenant_gstin'])): ?>
                        <div class="text-xs font-mono font-bold text-blue-700 mt-1">
                            GSTIN: <?= e($tenant['tax_id'] ?? $invoice['tenant_gstin']) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($tenant['phone'] ?? $invoice['tenant_phone'])): ?>
                        <div class="text-xs text-slate-500">Phone: <?= e($tenant['phone'] ?? $invoice['tenant_phone']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="text-left sm:text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider border <?= $status_class ?> mb-2">
                        <?= $status_label ?>
                    </span>
                    <div class="text-2xl font-mono font-black text-slate-900"><?= e($invoice['invoice_number']) ?></div>
                    <div class="text-xs text-slate-500 mt-1">Issue Date: <strong class="text-slate-800"><?= date('F d, Y', strtotime($invoice['created_at'])) ?></strong></div>
                    <?php if (!empty($invoice['due_date'])): ?>
                        <div class="text-xs text-slate-500">Due Date: <strong class="<?= $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-800' ?>"><?= date('F d, Y', strtotime($invoice['due_date'])) ?></strong></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Billed To & Payment Meta -->
            <div class="py-4 grid grid-cols-1 sm:grid-cols-2 gap-8 border-b border-slate-100 text-xs">
                <div>
                    <span class="font-bold uppercase tracking-wider text-slate-400 block mb-2">Billed To (Client):</span>
                    <div class="font-extrabold text-slate-900 text-sm"><?= e($clientName) ?></div>
                    <?php if (!empty($invoice['cust_company'])): ?>
                        <div class="text-blue-700 font-semibold mt-0.5"><?= e($invoice['cust_company']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($invoice['cust_email'] ?? $invoice['customer_email'])): ?>
                        <div class="text-slate-600 mt-1"><?= e($invoice['cust_email'] ?? $invoice['customer_email']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($invoice['cust_phone'] ?? $invoice['customer_phone'])): ?>
                        <div class="text-slate-600 font-mono mt-0.5"><?= e($invoice['cust_phone'] ?? $invoice['customer_phone']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($invoice['cust_address'] ?? $invoice['customer_address'])): ?>
                        <div class="text-slate-500 mt-1 leading-relaxed"><?= nl2br(e($invoice['cust_address'] ?? $invoice['customer_address'])) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($invoice['cust_gstin'] ?? $invoice['customer_gstin'])): ?>
                        <div class="text-slate-800 font-mono font-bold mt-1.5">
                            Client GSTIN: <?= e($invoice['cust_gstin'] ?? $invoice['customer_gstin']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div>
                    <span class="font-bold uppercase tracking-wider text-slate-400 block mb-2">Payment Terms &amp; Status:</span>
                    <div class="text-slate-700">Billing Regime: <span class="font-bold text-slate-900"><?= $isInterstate ? 'Inter-State IGST (18%)' : 'Intra-State CGST (9%) + SGST (9%)' ?></span></div>
                    <div class="text-slate-700 mt-1">Currency: <span class="font-mono font-bold text-slate-900"><?= e($invoice['currency'] ?? 'INR') ?> (₹)</span></div>
                    <?php if ($isPaid): ?>
                        <div class="text-slate-700 mt-1">Paid At: <span class="font-bold text-emerald-700"><?= date('M d, Y H:i', strtotime($invoice['paid_at'] ?? 'now')) ?></span></div>
                        <div class="text-slate-700 mt-1">Payment Method: <span class="font-bold text-slate-900"><?= strtoupper(e($invoice['payment_method'] ?? 'Online Checkout')) ?></span></div>
                    <?php elseif ($isVoid): ?>
                        <div class="text-slate-500 mt-1">Status: <span class="font-bold text-slate-700">Cancelled / Voided</span></div>
                    <?php else: ?>
                        <div class="text-slate-700 mt-1">Current Status: <span class="font-bold <?= $isOverdue ? 'text-rose-600' : 'text-amber-700' ?>"><?= $isOverdue ? "Overdue ({$daysOverdue} days)" : 'Awaiting Settlement' ?></span></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Line Items Table -->
            <div class="py-2">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 uppercase font-bold text-[11px]">
                            <th class="pb-3">Description</th>
                            <th class="pb-3 text-center w-16">Qty</th>
                            <th class="pb-3 text-right w-28">Rate (₹)</th>
                            <th class="pb-3 text-center w-20">GST %</th>
                            <th class="pb-3 text-right w-32">Total (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php if (empty($items)): ?>
                            <tr>
                                <td class="py-4">
                                    <div class="font-bold text-slate-900"><?= ucwords(str_replace('_', ' ', $invoice['billing_reason'])) ?></div>
                                    <div class="text-slate-400 text-[11px] mt-0.5">SaaS Engine Subscription Cycle</div>
                                </td>
                                <td class="py-4 text-center font-bold">1</td>
                                <td class="py-4 text-right font-mono"><?= format_cents((int)$invoice['subtotal_cents']) ?></td>
                                <td class="py-4 text-center font-bold">18%</td>
                                <td class="py-4 text-right font-mono font-bold text-slate-900"><?= format_cents((int)$invoice['total_cents']) ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td class="py-4">
                                        <div class="font-semibold text-slate-900"><?= e($item['description']) ?></div>
                                        <?php if (!empty($item['hsn'])): ?>
                                            <span class="text-[10px] font-mono text-slate-500 block">HSN/SAC: <?= e($item['hsn']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 text-center font-bold"><?= (float)($item['qty'] ?? 1) ?></td>
                                    <td class="py-4 text-right font-mono"><?= format_cents((int)($item['rate_cents'] ?? 0)) ?></td>
                                    <td class="py-4 text-center font-bold"><?= (float)($item['tax_rate'] ?? 18) ?>%</td>
                                    <td class="py-4 text-right font-mono font-bold text-slate-900"><?= format_cents((int)($item['amount_cents'] ?? 0)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Financial Calculations Summary -->
                <div class="mt-6 pt-6 border-t border-slate-200 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600 font-medium">
                        <span>Subtotal:</span>
                        <span class="font-mono font-bold text-slate-900"><?= format_cents((int)$invoice['subtotal_cents']) ?></span>
                    </div>

                    <?php if ((int)($invoice['discount_cents'] ?? 0) > 0): ?>
                        <div class="flex justify-between text-rose-600 font-medium">
                            <span>Discount:</span>
                            <span class="font-mono font-bold">-<?= format_cents((int)$invoice['discount_cents']) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($isInterstate): ?>
                        <div class="flex justify-between text-blue-700 font-bold">
                            <span>IGST (Integrated 18%):</span>
                            <span class="font-mono font-bold"><?= format_cents((int)$invoice['tax_cents']) ?></span>
                        </div>
                    <?php else: ?>
                        <div class="flex justify-between text-slate-600 font-medium">
                            <span>CGST (9%):</span>
                            <span class="font-mono font-bold text-slate-800"><?= format_cents((int)round((int)$invoice['tax_cents'] / 2)) ?></span>
                        </div>

                        <div class="flex justify-between text-slate-600 font-medium">
                            <span>SGST (9%):</span>
                            <span class="font-mono font-bold text-slate-800"><?= format_cents((int)round((int)$invoice['tax_cents'] / 2)) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-between text-base font-black text-slate-900 pt-3 border-t border-slate-200">
                        <span>Grand Total (INR):</span>
                        <span class="text-[#0C66E4] font-mono text-xl"><?= format_cents($totalCents) ?></span>
                    </div>

                    <?php if ($isPaid): ?>
                        <div class="flex justify-between text-xs font-bold text-emerald-700 pt-2">
                            <span>Amount Paid &amp; Settled:</span>
                            <span class="font-mono"><?= format_cents((int)$invoice['amount_paid_cents']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notes & Direct Settlement Section -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <?php if (!empty($tenant['bank_name']) || !empty($tenant['bank_account_no'])): ?>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-1.5">
                        <span class="font-bold text-slate-900 block">
                            <i class="fa-solid fa-building-columns text-blue-600 mr-1"></i> B2B Direct Settlement Details (NEFT / RTGS / IMPS):
                        </span>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
                            <div>Bank: <strong class="text-slate-900"><?= e($tenant['bank_name'] ?? '') ?></strong></div>
                            <div>A/C No: <strong class="font-mono text-slate-900"><?= e($tenant['bank_account_no'] ?? '') ?></strong></div>
                            <div>IFSC: <strong class="font-mono text-slate-900"><?= e($tenant['bank_ifsc'] ?? '') ?></strong></div>
                            <?php if (!empty($tenant['upi_id'])): ?>
                                <div>UPI VPA: <strong class="font-mono text-blue-700"><?= e($tenant['upi_id']) ?></strong></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="text-xs text-slate-500 leading-relaxed">
                    <span class="font-bold text-slate-800 block mb-1">Notes &amp; Terms:</span>
                    <p><?= nl2br(e($invoice['notes'] ?? 'Thank you for your business. For any billing queries, contact support.')) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Record Manual Offline Payment -->
<div id="recordPaymentModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Record Payment Received</h3>
                    <p class="text-xs text-slate-500">Record direct NEFT/RTGS, Cheque, or Cash payment</p>
                </div>
            </div>
            <button onclick="document.getElementById('recordPaymentModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="<?= app_url('/invoices/' . $invoice['id'] . '/record-payment') ?>" method="POST" class="space-y-4 text-xs">
            <?= csrf_field() ?>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Amount Received (INR) *</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-slate-400">₹</span>
                    <input type="number" step="0.01" name="amount_paid" value="<?= number_format($totalCents / 100, 2, '.', '') ?>" required
                           class="w-full pl-8 pr-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 font-mono font-bold text-sm outline-none focus:bg-white focus:border-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Payment Method *</label>
                    <select name="payment_method" class="w-full px-3 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 font-semibold outline-none focus:bg-white focus:border-blue-600">
                        <option value="bank_transfer">NEFT / RTGS Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                        <option value="cash">Cash</option>
                        <option value="upi">Direct UPI (PhonePe / GPay)</option>
                        <option value="imps">IMPS Transfer</option>
                        <option value="other">Other Manual Settlement</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Payment Date *</label>
                    <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" required
                           class="w-full px-3 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 font-medium outline-none focus:bg-white focus:border-blue-600">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Bank Reference / UTR / Cheque Number</label>
                <input type="text" name="reference_number" placeholder="e.g. UTR8291038291 or CHQ-00124"
                       class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs outline-none focus:bg-white focus:border-blue-600">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Internal Settlement Notes</label>
                <textarea name="notes" rows="2" placeholder="Optional notes for audit logs..."
                          class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 text-xs outline-none focus:bg-white focus:border-blue-600"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('recordPaymentModal').classList.add('hidden')" 
                        class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition flex items-center space-x-1.5 shadow-xs">
                    <i class="fa-solid fa-check"></i>
                    <span>Confirm &amp; Mark as Paid</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Void / Cancel Invoice -->
<div id="voidInvoiceModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg font-bold">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Void / Cancel Invoice</h3>
                    <p class="text-xs text-slate-500">Cancel invoice <?= e($invoice['invoice_number']) ?></p>
                </div>
            </div>
            <button onclick="document.getElementById('voidInvoiceModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="<?= app_url('/invoices/' . $invoice['id'] . '/void') ?>" method="POST" class="space-y-4 text-xs">
            <?= csrf_field() ?>

            <div class="p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-[11px] leading-relaxed">
                <strong>Warning:</strong> Voiding this invoice will cancel the receivable balance and disable online payment links. This action cannot be reversed.
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Reason for Cancellation *</label>
                <textarea name="reason" rows="3" required placeholder="e.g. Created with incorrect billing items / Client requested cancellation"
                          class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 text-xs outline-none focus:bg-white focus:border-rose-600"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('voidInvoiceModal').classList.add('hidden')" 
                        class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold transition flex items-center space-x-1.5 shadow-xs">
                    <i class="fa-solid fa-ban"></i>
                    <span>Confirm Void Invoice</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Send Email Invoice -->
<div id="sendEmailModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                    <i class="fa-regular fa-paper-plane"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Email Tax Invoice</h3>
                    <p class="text-xs text-slate-500">Dispatch invoice <?= e($invoice['invoice_number']) ?> to client</p>
                </div>
            </div>
            <button onclick="document.getElementById('sendEmailModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="<?= app_url('/invoices/' . $invoice['id'] . '/send-email') ?>" method="POST" class="space-y-4 text-xs">
            <?= csrf_field() ?>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Recipient Client Email *</label>
                <input type="email" name="recipient_email" required value="<?= e($invoice['customer_email'] ?? '') ?>"
                       placeholder="client@company.com"
                       class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 text-xs outline-none focus:bg-white focus:border-indigo-600">
            </div>

            <div class="p-3.5 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-900 text-[11px] space-y-1.5">
                <div class="font-bold flex items-center gap-1.5 text-indigo-950">
                    <i class="fa-solid fa-bolt text-indigo-600"></i> Delivery Details:
                </div>
                <p>Client will receive the verified GST tax breakdown for <strong><?= format_cents($totalCents) ?></strong> along with their 1-click Razorpay UPI QR code payment portal.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('sendEmailModal').classList.add('hidden')" 
                        class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold transition flex items-center space-x-1.5 shadow-xs">
                    <i class="fa-regular fa-paper-plane"></i>
                    <span>Send Invoice Now</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Copy Toast Notification -->
<div id="copyToast" class="fixed bottom-6 right-6 z-50 px-4 py-3 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-2xl flex items-center space-x-2 transition-all duration-300 opacity-0 pointer-events-none translate-y-3">
    <i class="fa-solid fa-circle-check text-emerald-400"></i>
    <span>Public payment link copied to clipboard!</span>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function copyPaymentLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        const toast = document.getElementById('copyToast');
        toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-3');
        toast.classList.add('opacity-100', 'translate-y-0');
        setTimeout(() => {
            toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-3');
            toast.classList.remove('opacity-100', 'translate-y-0');
        }, 2500);
    });
}

function downloadInvoicePDF() {
    const element = document.getElementById('invoicePrintableCard');
    const btn = document.getElementById('downloadPdfBtn');
    const label = document.getElementById('downloadPdfLabel');

    if (!element) return;

    btn.disabled = true;
    label.textContent = 'Generating PDF...';

    const opt = {
        margin:       [10, 10, 10, 10],
        filename:     'Invoice-<?= e($invoice['invoice_number']) ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true, logging: false },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save().then(() => {
        btn.disabled = false;
        label.textContent = 'Download PDF';
    }).catch(err => {
        console.error('PDF generation error:', err);
        btn.disabled = false;
        label.textContent = 'Download PDF';
        // Fallback to native print dialog
        window.print();
    });
}
</script>