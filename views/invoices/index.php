<?php
$summary = $analytics['summary'] ?? [];
$totalVol = (int)($summary['total_volume_cents'] ?? 0);
$totalCollected = (int)($summary['total_collected_cents'] ?? 0);
$totalUnpaid = (int)($summary['total_unpaid_cents'] ?? 0);
$totalGst = (int)($summary['total_gst_cents'] ?? 0);
$countPaid = (int)($summary['count_paid'] ?? 0);
$countOpen = (int)($summary['count_open'] ?? 0);
$countVoid = (int)($summary['count_void'] ?? 0);
$totalInvoicesCount = (int)($summary['total_invoices'] ?? count($invoices));

$collectionRate = ($totalVol > 0) ? min(100, round(($totalCollected / $totalVol) * 100, 1)) : 100;
$circumference = 251.2;
$strokeOffset = round($circumference - ($circumference * ($collectionRate / 100)), 1);

// Find count overdue in current list
$countOverdue = 0;
foreach ($invoices as $inv) {
    if ($inv['status'] === 'open' && !empty($inv['due_date']) && strtotime($inv['due_date']) < strtotime('today')) {
        $countOverdue++;
    }
}
?>

<div class="space-y-8 pb-12">
    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 1: FINANCIAL TRAFFIC COMMAND CROWN
         ══════════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white p-6 sm:p-8 shadow-2xl border border-slate-800">
        <!-- Ambient Glow Accents -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-emerald-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center flex-wrap gap-2.5">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 font-mono text-[11px] font-bold tracking-wider uppercase">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>Financial Traffic &bull; Invoicing Deck</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-white/10 text-slate-300 border border-white/10 text-[10px] font-bold uppercase tracking-wider font-mono">
                        GSTIN: <?= e($tenant['tax_id'] ?? '24AAACT0000A1Z5') ?>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                    <span>Commercial Invoices &amp; Receivables</span>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-blue-600/40 border border-blue-400/40 text-blue-200 font-mono font-bold">
                        <?= $totalInvoicesCount ?> Total
                    </span>
                </h1>
                <p class="text-xs text-slate-400 max-w-xl">
                    Full-lifecycle B2B commercial ledger. Issue tax-compliant invoices, track overdue settlements with automated late penalization radar, and dispatch one-tap UPI payment links.
                </p>
            </div>

            <!-- Instant Actions Dock -->
            <div class="flex items-center flex-wrap gap-3">
                <a href="<?= app_url('/invoices/create') ?>" 
                   class="px-5 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-400 hover:to-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 flex items-center space-x-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Forge New Invoice</span>
                </a>

                <a href="<?= app_url('/invoices/export') ?>" 
                   class="px-4 py-3 rounded-xl bg-white/10 hover:bg-white/15 text-slate-200 border border-white/10 font-bold text-xs transition flex items-center space-x-2">
                    <i class="fa-solid fa-file-csv text-emerald-400 text-xs"></i>
                    <span>Export CSV</span>
                </a>

                <a href="<?= app_url('/customers') ?>" 
                   class="px-4 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 font-bold text-xs transition flex items-center space-x-2">
                    <i class="fa-solid fa-users text-blue-400 text-xs"></i>
                    <span>Clients</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 2: CAPITAL SETTLEMENT TELEMETRY RIBBON
         ══════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Circular Collection Reactor Dial -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="relative w-16 h-16 shrink-0 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="10" fill="transparent"></circle>
                    <circle cx="50" cy="50" r="40" stroke="#10B981" stroke-width="10" stroke-linecap="round" fill="transparent"
                            stroke-dasharray="251.2" stroke-dashoffset="<?= $strokeOffset ?>"></circle>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <span class="text-xs font-black text-slate-900 font-mono"><?= $collectionRate ?>%</span>
                </div>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Efficiency</span>
                <span class="text-sm font-black text-slate-900 block">Collection Rate</span>
                <span class="text-[10px] text-emerald-600 font-bold"><?= $countPaid ?>/<?= $totalInvoicesCount ?> Settled</span>
            </div>
        </div>

        <!-- Grand Invoiced -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Invoiced</span>
            <div class="text-2xl font-black text-slate-900 font-mono"><?= format_cents($totalVol) ?></div>
            <span class="text-[11px] text-slate-500 font-medium"><?= $totalInvoicesCount ?> B2B Dispatches</span>
        </div>

        <!-- Bank Settled -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1 relative overflow-hidden">
            <div class="h-1 w-full bg-emerald-500 absolute top-0 left-0"></div>
            <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">Bank Realized</span>
            <div class="text-2xl font-black text-emerald-600 font-mono"><?= format_cents($totalCollected) ?></div>
            <span class="text-[11px] text-emerald-600 font-semibold"><?= $countPaid ?> Paid Invoices</span>
        </div>

        <!-- Pending In-Flight -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1 relative overflow-hidden">
            <div class="h-1 w-full bg-amber-500 absolute top-0 left-0"></div>
            <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider block">Pending Receivables</span>
            <div class="text-2xl font-black text-amber-600 font-mono"><?= format_cents($totalUnpaid) ?></div>
            <span class="text-[11px] text-amber-600 font-semibold"><?= $countOpen ?> Awaiting Pay</span>
        </div>

        <!-- Indian GST Pool -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1 relative overflow-hidden">
            <div class="h-1 w-full bg-blue-600 absolute top-0 left-0"></div>
            <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider block">GST Tax Pool (18%)</span>
            <div class="text-2xl font-black text-slate-900 font-mono"><?= format_cents($totalGst) ?></div>
            <span class="text-[11px] text-blue-600 font-semibold">100% ITC Eligible</span>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 3: TRAFFIC FILTER DECK & DUAL-MODE CONTROLLER
         ══════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Status Filter Capsules -->
        <div class="flex items-center space-x-1.5 text-xs font-bold flex-wrap gap-y-2">
            <a href="<?= app_url('/invoices') ?>" 
               class="px-3.5 py-2 rounded-xl transition flex items-center space-x-1.5 <?= empty($currentFilter) ? 'bg-slate-900 text-white shadow-xs' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' ?>">
                <span>All Matrices</span>
                <span class="px-1.5 py-0.5 rounded-full <?= empty($currentFilter) ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' ?> text-[10px] font-mono"><?= $totalInvoicesCount ?></span>
            </a>

            <a href="<?= app_url('/invoices?status=paid') ?>" 
               class="px-3.5 py-2 rounded-xl transition flex items-center space-x-1.5 <?= ($currentFilter === 'paid') ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200' ?>">
                <i class="fa-solid fa-circle-check text-[10px]"></i>
                <span>Settled (<?= $countPaid ?>)</span>
            </a>

            <a href="<?= app_url('/invoices?status=open') ?>" 
               class="px-3.5 py-2 rounded-xl transition flex items-center space-x-1.5 <?= ($currentFilter === 'open') ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200' ?>">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span>In-Flight (<?= $countOpen ?>)</span>
            </a>

            <?php if ($countOverdue > 0): ?>
                <a href="<?= app_url('/invoices?status=open') ?>" 
                   class="px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition flex items-center space-x-1.5 animate-pulse">
                    <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                    <span>Overdue Alert (<?= $countOverdue ?>)</span>
                </a>
            <?php endif; ?>

            <a href="<?= app_url('/invoices?status=void') ?>" 
               class="px-3.5 py-2 rounded-xl transition flex items-center space-x-1.5 <?= ($currentFilter === 'void') ? 'bg-slate-700 text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200' ?>">
                <i class="fa-solid fa-ban text-[10px]"></i>
                <span>Void (<?= $countVoid ?>)</span>
            </a>
        </div>

        <!-- Controls: Live Search & View Switcher -->
        <div class="flex items-center space-x-3 w-full md:w-auto">
            <div class="relative flex-1 md:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="invoiceSearch" placeholder="Search #, client, company..." 
                       class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
            </div>

            <!-- Dual View Switcher -->
            <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-bold shrink-0">
                <button type="button" id="btnViewCards" onclick="switchInvoiceMode('cards')" class="px-3 py-1.5 rounded-lg bg-white shadow-xs text-slate-900 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-grip text-xs text-blue-600"></i>
                    <span class="hidden sm:inline">Passes</span>
                </button>
                <button type="button" id="btnViewTable" onclick="switchInvoiceMode('table')" class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-list text-xs"></i>
                    <span class="hidden sm:inline">Ledger</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 4: MODE A - KINETIC FINANCIAL BOARDING PASSES MATRIX
         ══════════════════════════════════════════════════════════════════ -->
    <div id="kineticCardsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        <?php if (empty($invoices)): ?>
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                <i class="fa-solid fa-file-invoice text-4xl text-slate-300 mb-3 block"></i>
                <h4 class="text-base font-bold text-slate-800">No Invoices Located</h4>
                <p class="text-xs text-slate-500 mt-1">There are no tax invoices matching the selected filter state.</p>
                <div class="mt-4">
                    <a href="<?= app_url('/invoices/create') ?>" class="px-4 py-2 rounded-xl bg-[#0C66E4] text-white font-bold text-xs shadow-xs hover:bg-[#0055CC]">
                        + Forge First Invoice
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($invoices as $inv): 
                $status = strtolower($inv['status']);
                $isPaid = ($status === 'paid');
                $isVoid = ($status === 'void');
                $isOverdue = (!$isPaid && !$isVoid && !empty($inv['due_date']) && strtotime($inv['due_date']) < strtotime('today'));
                $daysOverdue = $isOverdue ? (int)floor((time() - strtotime($inv['due_date'])) / 86400) : 0;
                $total = (int)($inv['total_cents'] ?? ($inv['subtotal_cents'] + $inv['tax_cents']));
                $clientName = $inv['client_display_name'] ?? $inv['customer_name'] ?? 'Direct Client';
                $company = $inv['client_company'] ?? '';
                $publicPayUrl = app_url('/pay/' . ($inv['payment_token'] ?? $inv['id']));
                $waText = urlencode("Hello, please find your tax invoice {$inv['invoice_number']} for " . format_cents($total) . ". You can view and pay online here: {$publicPayUrl}");
            ?>
                <div class="inv-card bg-white rounded-3xl p-5 border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col justify-between relative overflow-hidden group <?= $isVoid ? 'opacity-65' : '' ?>"
                     data-search="<?= strtolower(e($inv['invoice_number'] . ' ' . $clientName . ' ' . $company)) ?>">
                    <!-- Top Status Band -->
                    <div class="h-1.5 w-full <?= $isPaid ? 'bg-emerald-500' : ($isVoid ? 'bg-slate-300' : ($isOverdue ? 'bg-rose-500' : 'bg-[#0C66E4]')) ?> absolute top-0 left-0"></div>

                    <div class="space-y-3 pt-2">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between">
                            <a href="<?= app_url('/invoices/' . $inv['id']) ?>" class="font-mono font-black text-xs text-blue-600 hover:text-blue-800 tracking-tight flex items-center gap-1.5 <?= $isVoid ? 'line-through text-slate-500' : '' ?>">
                                <i class="fa-solid fa-receipt text-[10px] text-slate-400"></i>
                                <span><?= e($inv['invoice_number']) ?></span>
                            </a>

                            <?php if ($isPaid): ?>
                                <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="fa-solid fa-circle-check text-[9px]"></i>
                                    <span>Settled</span>
                                </span>
                            <?php elseif ($isVoid): ?>
                                <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wide bg-slate-100 text-slate-600 border border-slate-200">
                                    <i class="fa-solid fa-ban text-[9px]"></i>
                                    <span>Void</span>
                                </span>
                            <?php elseif ($isOverdue): ?>
                                <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
                                    <i class="fa-solid fa-triangle-exclamation text-[9px]"></i>
                                    <span><?= $daysOverdue ?>d Late</span>
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span>In-Flight</span>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Client Capsule -->
                        <div>
                            <span class="font-black text-slate-900 text-sm block truncate"><?= e($clientName) ?></span>
                            <span class="text-[11px] text-slate-400 block truncate flex items-center gap-1 mt-0.5">
                                <i class="fa-regular fa-building text-[10px]"></i>
                                <span><?= e($company ?: 'Direct Commercial Account') ?></span>
                            </span>
                        </div>

                        <!-- Amount Pass Ticket -->
                        <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 block">Total Billed</span>
                                <div class="font-mono font-black text-slate-900 text-base <?= $isVoid ? 'line-through text-slate-400' : '' ?>">
                                    <?= format_cents($total) ?>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-bold uppercase tracking-wider text-blue-600 block">18% GST</span>
                                <span class="font-mono text-xs font-bold text-slate-600"><?= format_cents((int)$inv['tax_cents']) ?></span>
                            </div>
                        </div>

                        <!-- Lifecycle Dates -->
                        <div class="text-[11px] text-slate-500 flex items-center justify-between font-mono pt-1">
                            <span>Issued: <?= date('M d, Y', strtotime($inv['created_at'])) ?></span>
                            <?php if (!empty($inv['due_date'])): ?>
                                <span class="<?= $isOverdue ? 'text-rose-600 font-bold' : '' ?>">Due: <?= date('M d', strtotime($inv['due_date'])) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center space-x-1">
                            <?php if (!$isVoid): ?>
                                <button type="button" onclick="copyToClipboard('<?= $publicPayUrl ?>', '<?= e($inv['invoice_number']) ?>')" 
                                        class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Copy Payment Link">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                                <a href="https://api.whatsapp.com/send?text=<?= $waText ?>" target="_blank" 
                                   class="p-2 rounded-xl text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Share via WhatsApp">
                                    <i class="fa-brands fa-whatsapp text-xs"></i>
                                </a>
                            <?php endif; ?>

                            <a href="<?= app_url('/invoices/' . $inv['id'] . '/print') ?>" target="_blank" 
                               class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Print PDF">
                                <i class="fa-solid fa-print text-xs"></i>
                            </a>

                            <?php if (!$isPaid && !$isVoid): ?>
                                <a href="<?= app_url('/invoices/' . $inv['id']) ?>" 
                                   class="p-2 rounded-xl text-emerald-600 hover:bg-emerald-50 transition" title="Record Manual Payment">
                                    <i class="fa-solid fa-receipt text-xs"></i>
                                </a>
                            <?php endif; ?>
                        </div>

                        <a href="<?= app_url('/invoices/' . $inv['id']) ?>" 
                           class="px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition flex items-center gap-1">
                            <span>Inspect</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 4: MODE B - HIGH-DENSITY TERMINAL LEDGER
         ══════════════════════════════════════════════════════════════════ -->
    <div id="compactLedgerTable" class="hidden bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="invoicesTable">
                <thead class="bg-slate-50/90 text-slate-600 text-[11px] uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Invoice #</th>
                        <th class="px-6 py-4">Client / Organization</th>
                        <th class="px-6 py-4">Timeline</th>
                        <th class="px-6 py-4 text-right">Taxable Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Action Dock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach ($invoices as $inv): 
                        $status = strtolower($inv['status']);
                        $isPaid = ($status === 'paid');
                        $isVoid = ($status === 'void');
                        $isOverdue = (!$isPaid && !$isVoid && !empty($inv['due_date']) && strtotime($inv['due_date']) < strtotime('today'));
                        $daysOverdue = $isOverdue ? (int)floor((time() - strtotime($inv['due_date'])) / 86400) : 0;
                        $total = (int)($inv['total_cents'] ?? ($inv['subtotal_cents'] + $inv['tax_cents']));
                        $clientName = $inv['client_display_name'] ?? $inv['customer_name'] ?? 'Direct Client';
                        $company = $inv['client_company'] ?? '';
                        $publicPayUrl = app_url('/pay/' . ($inv['payment_token'] ?? $inv['id']));
                        $waText = urlencode("Hello, please find your tax invoice {$inv['invoice_number']} for " . format_cents($total) . ". You can view and pay online here: {$publicPayUrl}");
                    ?>
                        <tr class="inv-row hover:bg-blue-50/20 transition <?= $isVoid ? 'opacity-60 bg-slate-50/50' : '' ?>" data-search="<?= strtolower(e($inv['invoice_number'] . ' ' . $clientName . ' ' . $company)) ?>">
                            <td class="px-6 py-3.5 font-mono font-bold text-blue-600">
                                <a href="<?= app_url('/invoices/' . $inv['id']) ?>" class="hover:underline flex items-center gap-1.5 <?= $isVoid ? 'line-through text-slate-400' : '' ?>">
                                    <span><?= e($inv['invoice_number']) ?></span>
                                </a>
                                <span class="block text-[10px] text-slate-400 font-sans font-medium">HSN/SAC 998313</span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="font-bold text-slate-900"><?= e($clientName) ?></div>
                                <?php if (!empty($company)): ?>
                                    <div class="text-[11px] text-slate-500"><?= e($company) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5 font-mono text-[11px]">
                                <div>Issued: <?= date('M d, Y', strtotime($inv['created_at'])) ?></div>
                                <?php if (!empty($inv['due_date'])): ?>
                                    <div class="<?= $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-400' ?>">Due: <?= date('M d, Y', strtotime($inv['due_date'])) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5 text-right font-mono">
                                <div class="font-bold text-slate-900 text-sm <?= $isVoid ? 'line-through text-slate-400' : '' ?>"><?= format_cents($total) ?></div>
                                <div class="text-[10px] text-blue-600 font-sans">GST: <?= format_cents((int)$inv['tax_cents']) ?></div>
                            </td>
                            <td class="px-6 py-3.5 text-center">
                                <?php if ($isPaid): ?>
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[9px]"></i>
                                        <span>Settled</span>
                                    </span>
                                <?php elseif ($isVoid): ?>
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                        <i class="fa-solid fa-ban text-[9px]"></i>
                                        <span>Void</span>
                                    </span>
                                <?php elseif ($isOverdue): ?>
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
                                        <span><?= $daysOverdue ?>d Late</span>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        <span>In-Flight</span>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <?php if (!$isVoid): ?>
                                        <button onclick="copyToClipboard('<?= $publicPayUrl ?>', '<?= e($inv['invoice_number']) ?>')" 
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Copy Pay Link">
                                            <i class="fa-regular fa-copy text-xs"></i>
                                        </button>
                                        <a href="https://api.whatsapp.com/send?text=<?= $waText ?>" target="_blank" 
                                           class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Share WhatsApp">
                                            <i class="fa-brands fa-whatsapp text-xs"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= app_url('/invoices/' . $inv['id'] . '/print') ?>" target="_blank" 
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Print PDF">
                                        <i class="fa-solid fa-print text-xs"></i>
                                    </a>
                                    <a href="<?= app_url('/invoices/' . $inv['id']) ?>" 
                                       class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition">
                                        View &rarr;
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Copy Toast Notification -->
<div id="copyToast" class="fixed bottom-6 right-6 z-50 px-4 py-3 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-2xl flex items-center space-x-2 transition-all duration-300 opacity-0 pointer-events-none translate-y-3">
    <i class="fa-solid fa-circle-check text-emerald-400"></i>
    <span id="toastMsg">Payment link copied to clipboard!</span>
</div>

<script>
function copyToClipboard(url, invNum) {
    navigator.clipboard.writeText(url).then(() => {
        const toast = document.getElementById('copyToast');
        document.getElementById('toastMsg').innerText = `Payment link for ${invNum} copied!`;
        toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-3');
        toast.classList.add('opacity-100', 'translate-y-0');
        setTimeout(() => {
            toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-3');
            toast.classList.remove('opacity-100', 'translate-y-0');
        }, 2500);
    });
}

function switchInvoiceMode(mode) {
    const cards = document.getElementById('kineticCardsGrid');
    const table = document.getElementById('compactLedgerTable');
    const btnCards = document.getElementById('btnViewCards');
    const btnTable = document.getElementById('btnViewTable');

    if (mode === 'table') {
        cards.classList.add('hidden');
        table.classList.remove('hidden');
        btnTable.className = 'px-3 py-1.5 rounded-lg bg-white shadow-xs text-slate-900 transition flex items-center space-x-1.5 font-bold';
        btnCards.className = 'px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition flex items-center space-x-1.5';
    } else {
        cards.classList.remove('hidden');
        table.classList.add('hidden');
        btnCards.className = 'px-3 py-1.5 rounded-lg bg-white shadow-xs text-slate-900 transition flex items-center space-x-1.5 font-bold';
        btnTable.className = 'px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition flex items-center space-x-1.5';
    }
}

// Live Search for both Boarding Passes & Compact Ledger
document.getElementById('invoiceSearch')?.addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase().trim();
    
    // Filter cards
    document.querySelectorAll('.inv-card').forEach(card => {
        const text = card.getAttribute('data-search') || '';
        card.style.display = text.includes(query) ? '' : 'none';
    });

    // Filter table rows
    document.querySelectorAll('.inv-row').forEach(row => {
        const text = row.getAttribute('data-search') || '';
        row.style.display = text.includes(query) ? '' : 'none';
    });
});
</script>