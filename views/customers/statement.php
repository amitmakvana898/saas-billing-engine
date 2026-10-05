<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Client Statement & Billing Ledger') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; color: #000 !important; }
            .statement-card { border: none !important; box-shadow: none !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 min-h-screen p-4 sm:p-8 antialiased selection:bg-blue-600 selection:text-white">

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Top Actions Bar -->
        <div class="flex items-center justify-between no-print gap-4">
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-[#0C66E4] font-mono text-xs font-bold uppercase">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <span>Client Self-Service Portal</span>
                </span>
            </div>

            <div class="flex items-center space-x-2">
                <button onclick="window.print()" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-1.5">
                    <i class="fa-solid fa-print text-slate-500"></i>
                    <span>Print Statement</span>
                </button>
            </div>
        </div>

        <!-- Main Statement Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm statement-card space-y-8">
            
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6 pb-6 border-b border-slate-100">
                <div class="space-y-2">
                    <div class="flex items-center space-x-3">
                        <?php if (!empty($tenant['logo_url'])): ?>
                            <img src="<?= e($tenant['logo_url']) ?>" alt="Logo" class="h-12 max-w-[160px] object-contain rounded-lg border border-slate-200 p-1 bg-white">
                        <?php else: ?>
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-lg shadow-md">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h1 class="text-xl font-black text-slate-900"><?= e($tenant['name'] ?? 'SaaSify Organization') ?></h1>
                            <span class="text-xs text-slate-500">Official Commercial Billing &amp; Tax Department</span>
                        </div>
                    </div>

                    <?php if (!empty($tenant['tax_id'])): ?>
                        <div class="text-xs font-mono font-bold text-slate-700">
                            Organization GSTIN: <span class="text-[#0C66E4]"><?= e($tenant['tax_id']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($tenant['billing_address'])): ?>
                        <p class="text-xs text-slate-500 max-w-sm leading-relaxed"><?= nl2br(e($tenant['billing_address'])) ?></p>
                    <?php endif; ?>
                </div>

                <div class="sm:text-right space-y-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Statement of Account</span>
                    <div class="text-xs text-slate-500">Generated: <strong><?= date('M d, Y') ?></strong></div>
                    <div class="text-xs text-slate-500">Currency: <strong>INR (₹)</strong></div>
                </div>
            </div>

            <!-- Client Account Profile & Ledger Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                <div>
                    <span class="font-bold text-slate-400 uppercase tracking-wider block mb-1">Account Holder / Client:</span>
                    <div class="text-base font-extrabold text-slate-900"><?= e($customer['name']) ?></div>
                    <?php if (!empty($customer['company_name'])): ?>
                        <div class="font-bold text-[#0C66E4] mt-0.5"><?= e($customer['company_name']) ?></div>
                    <?php endif; ?>
                    <div class="text-slate-600 mt-1"><?= e($customer['email']) ?></div>
                    <?php if (!empty($customer['phone'])): ?>
                        <div class="text-slate-600 font-mono mt-0.5"><?= e($customer['phone']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($customer['gstin'])): ?>
                        <div class="font-mono font-bold text-slate-800 mt-1">Client GSTIN: <?= e($customer['gstin']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="sm:text-right sm:border-l sm:border-slate-200 sm:pl-6 space-y-2">
                    <span class="font-bold text-slate-400 uppercase tracking-wider block">Outstanding Exposure</span>
                    <div class="text-3xl font-black font-mono <?= ($totalOutstanding > 0) ? 'text-rose-600' : 'text-emerald-600' ?>">
                        <?= format_cents($totalOutstanding) ?>
                    </div>
                    <span class="text-[11px] font-medium text-slate-500 block">
                        <?= ($totalOutstanding > 0) ? '● Pending settlement across ' . $countOpen . ' invoice(s)' : '✓ All past commercial bills are paid in full' ?>
                    </span>
                </div>
            </div>

            <!-- 3 Core Financial Metric Pills -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 rounded-2xl bg-white border border-slate-200 text-center shadow-2xs">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Billed Volume</span>
                    <div class="text-xl font-black text-slate-900 font-mono mt-0.5"><?= format_cents($totalInvoiced) ?></div>
                    <span class="text-[10px] text-slate-500"><?= count($invoices) ?> Total Invoices</span>
                </div>

                <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100 text-center shadow-2xs">
                    <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Total Amount Settled</span>
                    <div class="text-xl font-black text-emerald-700 font-mono mt-0.5"><?= format_cents($totalPaid) ?></div>
                    <span class="text-[10px] text-emerald-600"><?= $countPaid ?> Invoices Paid</span>
                </div>

                <div class="p-4 rounded-2xl bg-rose-50/70 border border-rose-100 text-center shadow-2xs">
                    <span class="text-[10px] font-bold text-rose-800 uppercase tracking-wider block">Net Balance Due</span>
                    <div class="text-xl font-black text-rose-700 font-mono mt-0.5"><?= format_cents($totalOutstanding) ?></div>
                    <span class="text-[10px] text-rose-600"><?= $countOverdue ?> Overdue Invoices</span>
                </div>
            </div>

            <!-- Complete Invoice Ledger Table -->
            <div class="space-y-3">
                <h3 class="text-base font-extrabold text-slate-900 flex items-center justify-between">
                    <span>Tax Invoices &amp; Transaction Ledger</span>
                    <span class="text-xs font-normal text-slate-400"><?= count($invoices) ?> Records</span>
                </h3>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-3">Invoice #</th>
                                <th class="px-4 py-3">Issued Date</th>
                                <th class="px-4 py-3">Due Date</th>
                                <th class="px-4 py-3 text-right">Invoice Amount</th>
                                <th class="px-4 py-3 text-right">Settled</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-right no-print">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            <?php if (empty($invoices)): ?>
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                                        No invoices recorded for this account.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($invoices as $inv): ?>
                                    <?php
                                        $isPaid = ($inv['status'] === 'paid');
                                        $isVoid = ($inv['status'] === 'void');
                                        $isLate = (!$isPaid && !$isVoid && !empty($inv['due_date']) && strtotime($inv['due_date']) < strtotime('today'));
                                        $payUrl = app_url('/pay/' . ($inv['payment_token'] ?? $inv['id']));
                                    ?>
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="px-5 py-3.5 font-mono font-bold text-slate-900">
                                            <?= e($inv['invoice_number']) ?>
                                        </td>
                                        <td class="px-4 py-3.5 text-slate-600">
                                            <?= date('M d, Y', strtotime($inv['created_at'])) ?>
                                        </td>
                                        <td class="px-4 py-3.5 text-slate-600">
                                            <?= !empty($inv['due_date']) ? date('M d, Y', strtotime($inv['due_date'])) : '—' ?>
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-mono font-bold text-slate-900">
                                            <?= format_cents((int)$inv['total_cents']) ?>
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-mono font-bold text-emerald-700">
                                            <?= format_cents((int)$inv['amount_paid_cents']) ?>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <?php if ($isPaid): ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    Paid
                                                </span>
                                            <?php elseif ($isVoid): ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                                    Void
                                                </span>
                                            <?php elseif ($isLate): ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                                    Overdue
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                    Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-5 py-3.5 text-right no-print">
                                            <div class="flex items-center justify-end space-x-2">
                                                <?php if (!$isPaid && !$isVoid): ?>
                                                    <a href="<?= $payUrl ?>" target="_blank" 
                                                       class="px-2.5 py-1 rounded-lg bg-[#0C66E4] hover:bg-[#0055CC] text-white font-bold text-xs shadow-2xs transition">
                                                        Pay Online &rarr;
                                                    </a>
                                                <?php else: ?>
                                                    <a href="<?= app_url('/invoices/' . $inv['id'] . '/print') ?>" target="_blank" 
                                                       class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                                                        <i class="fa-solid fa-print mr-1"></i> Print
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Settlement & Support Footer -->
            <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>For any reconciliation inquiries, please contact <strong><?= e($tenant['name'] ?? 'Support') ?></strong>.</p>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center gap-1.5 font-bold text-slate-700">
                        <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                        <span>100% Tax Compliant Ledger</span>
                    </span>
                </div>
            </div>

        </div>

        <div class="text-center text-xs text-slate-400 no-print py-2">
            Powered by <strong>SaaSify Enterprise Billing Engine</strong>
        </div>

    </div>

</body>
</html>
