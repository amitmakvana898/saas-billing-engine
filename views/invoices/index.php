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
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0B0F19] via-[#0F172A] to-[#0A0D17] text-white p-6 sm:p-8 shadow-2xl border border-white/10 backdrop-blur-2xl">
        <!-- Ambient Glow Accents -->
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-indigo-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <!-- Left Info Section -->
            <div class="space-y-2.5">
                <div class="flex items-center flex-wrap gap-2">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 font-mono text-[11px] font-bold tracking-wider uppercase">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>B2B Commercial Ledger</span>
                    </span>
                    <span class="px-2.5 py-1 rounded-full bg-white/5 text-slate-300 border border-white/10 text-[10px] font-bold font-mono uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-building-shield text-[10px] text-indigo-400"></i>
                        <span>GSTIN: <?= e($tenant['tax_id'] ?? '24AAACT0000A1Z5') ?></span>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                    <span>Commercial Invoices &amp; Receivables</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 font-mono font-bold">
                        <?= $totalInvoicesCount ?> Total
                    </span>
                </h1>
                <p class="text-xs text-slate-400 max-w-xl leading-relaxed">
                    Full-lifecycle B2B commercial invoicing. Issue tax-compliant invoices, track overdue settlements with automated penalization radar, and export official GSTR-1 returns.
                </p>
            </div>

            <!-- Instant Actions Dock (Clean, Symmetric, Aligned) -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0">
                <!-- Primary Action: Forge Invoice -->
                <a href="<?= app_url('/invoices/create') ?>" 
                   class="h-11 px-5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 hover:from-blue-500 hover:to-violet-500 text-white font-extrabold text-xs shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 flex items-center justify-center space-x-2 shrink-0">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Forge New Invoice</span>
                </a>

                <!-- Unified Export Actions Pill Group -->
                <div class="h-11 flex items-center bg-white/[0.06] border border-white/10 rounded-2xl p-1 shadow-inner backdrop-blur-md">
                    <!-- Standard CSV -->
                    <a href="<?= app_url('/invoices/export') ?>" 
                       class="h-full px-3.5 rounded-xl hover:bg-white/10 text-slate-300 hover:text-white font-bold text-xs transition flex items-center space-x-1.5"
                       title="Export all invoices to standard CSV">
                        <i class="fa-solid fa-file-csv text-emerald-400 text-xs"></i>
                        <span>Export CSV</span>
                    </a>

                    <div class="w-px h-5 bg-white/10 mx-0.5"></div>

                    <!-- GSTR-1 CA Tax Export -->
                    <a href="<?= app_url('/invoices/gstr1-export') ?>" 
                       class="h-full px-3.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-300 border border-emerald-500/30 font-bold text-xs transition flex items-center space-x-1.5 shadow-2xs"
                       title="Official GSTR-1 B2B Format for CA and Tally">
                        <i class="fa-solid fa-file-invoice-dollar text-emerald-400 text-xs"></i>
                        <span>GSTR-1 CA Export</span>
                    </a>

                    <div class="w-px h-5 bg-white/10 mx-0.5"></div>

                    <!-- Custom Filter / Date Range Export Modal Trigger -->
                    <button type="button" onclick="openExportModal()"
                            class="h-full px-2.5 rounded-xl hover:bg-white/10 text-slate-300 hover:text-white font-bold text-xs transition flex items-center space-x-1"
                            title="Filter Export by Date Range or Status">
                        <i class="fa-solid fa-sliders text-blue-400 text-xs"></i>
                    </button>
                </div>

                <!-- Customer Vault Quick Link -->
                <a href="<?= app_url('/customers') ?>" 
                   class="h-11 px-4 rounded-2xl bg-white/[0.06] hover:bg-white/10 text-slate-200 border border-white/10 font-bold text-xs transition flex items-center justify-center space-x-2"
                   title="Manage Clients and Customers">
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
                <span>All Invoices</span>
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
                <span>Pending (<?= $countOpen ?>)</span>
            </a>

            <?php if ($countOverdue > 0): ?>
                <a href="<?= app_url('/invoices?status=open') ?>" 
                   class="px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition flex items-center space-x-1.5 animate-pulse">
                    <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                    <span>Overdue (<?= $countOverdue ?>)</span>
                </a>
            <?php endif; ?>

            <a href="<?= app_url('/invoices?status=void') ?>" 
               class="px-3.5 py-2 rounded-xl transition flex items-center space-x-1.5 <?= ($currentFilter === 'void') ? 'bg-slate-700 text-white' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200' ?>">
                <i class="fa-solid fa-ban text-[10px]"></i>
                <span>Void (<?= $countVoid ?>)</span>
            </a>
        </div>

        <!-- Controls: Live Search & View Switcher -->
        <div class="flex items-center space-x-2.5 w-full md:w-auto">
            <div class="relative flex-1 sm:w-72 lg:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="invoiceSearch" placeholder="Search #, client, amount, status..." 
                       class="w-full pl-9 pr-8 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition shadow-2xs">
                <button type="button" id="clearInvoiceSearchBtn" onclick="clearInvoiceSearch()" 
                        class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1" title="Clear Search (Esc)">
                    <i class="fa-solid fa-circle-xmark text-xs"></i>
                </button>
            </div>

            <!-- Live Match Counter Badge -->
            <span id="invoiceMatchBadge" class="hidden px-2.5 py-1.5 rounded-xl bg-blue-50 text-blue-700 border border-blue-200 font-mono text-[11px] font-bold shrink-0">
                0 matches
            </span>

            <!-- Dual View Switcher -->
            <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-bold shrink-0">
                <button type="button" id="btnViewCards" onclick="switchInvoiceMode('cards')" class="px-3 py-1.5 rounded-lg bg-white shadow-xs text-slate-900 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-grip text-xs text-indigo-600"></i>
                    <span class="hidden sm:inline">Cards</span>
                </button>
                <button type="button" id="btnViewTable" onclick="switchInvoiceMode('table')" class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-list text-xs"></i>
                    <span class="hidden sm:inline">Table</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 4: MODE A - KINETIC FINANCIAL BOARDING PASSES MATRIX
         ══════════════════════════════════════════════════════════════════ -->
    <div id="kineticCardsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($invoices)): ?>
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                <i class="fa-solid fa-file-invoice text-4xl text-slate-300 mb-3 block"></i>
                <h4 class="text-base font-bold text-slate-800">No Invoices Located</h4>
                <p class="text-xs text-slate-500 mt-1">There are no tax invoices matching the selected filter state.</p>
                <div class="mt-4">
                    <a href="<?= app_url('/invoices/create') ?>" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs shadow-xs hover:bg-blue-700">
                        + Forge First Invoice
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Search Zero Results Empty State (Cards) -->
            <div id="invoiceSearchZeroCards" class="hidden col-span-full bg-white rounded-3xl p-10 text-center border border-slate-200 shadow-sm space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-500 mx-auto flex items-center justify-center text-xl font-bold shadow-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900">No Matching Invoices Found</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    No invoices found matching "<span id="searchZeroQueryCards" class="font-bold text-slate-800 font-mono"></span>". Try searching by invoice #, client name, amount, date, or status.
                </p>
                <div class="pt-2">
                    <button type="button" onclick="clearInvoiceSearch()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition inline-flex items-center space-x-1.5">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>Reset Search Filter</span>
                    </button>
                </div>
            </div>

            <?php foreach ($invoices as $inv): 
                $status = strtolower($inv['status']);
                $isPaid = ($status === 'paid');
                $isVoid = ($status === 'void');
                $isOverdue = (!$isPaid && !$isVoid && !empty($inv['due_date']) && strtotime($inv['due_date']) < strtotime('today'));
                $daysOverdue = $isOverdue ? (int)floor((time() - strtotime($inv['due_date'])) / 86400) : 0;
                $total = (int)($inv['total_cents'] ?? ($inv['subtotal_cents'] + $inv['tax_cents']));
                $clientName = $inv['client_display_name'] ?? $inv['customer_name'] ?? 'Direct Client';
                $company = $inv['client_company'] ?? '';
                $publicPayUrl = !empty($inv['payment_token']) ? app_url('/pay/' . $inv['payment_token']) : app_url('/invoices/' . $inv['id']);
                $waMsg = urlencode("Hello {$clientName}, please find your official tax invoice {$inv['invoice_number']} for " . format_cents($total) . ". You can view details and settle online here: {$publicPayUrl}");
                $cPhone = preg_replace('/[^0-9]/', '', $inv['customer_phone'] ?? $inv['client_phone'] ?? '');
                if (!empty($cPhone) && strlen($cPhone) === 10) $cPhone = '91' . $cPhone;
                $waUrl = !empty($cPhone) ? "https://wa.me/{$cPhone}?text={$waMsg}" : "https://api.whatsapp.com/send?text={$waMsg}";
                $searchKeywords = strtolower(implode(' ', array_filter([
                    $inv['invoice_number'],
                    $clientName,
                    $company,
                    $inv['client_email'] ?? $inv['customer_email'] ?? '',
                    $inv['customer_phone'] ?? '',
                    $inv['customer_gstin'] ?? '',
                    $status,
                    $isPaid ? 'paid settled complete' : '',
                    $isOverdue ? 'overdue late unpaid' : '',
                    ($status === 'open' && !$isOverdue) ? 'in-flight open pending due' : '',
                    $isVoid ? 'void cancelled' : '',
                    (string)round($total / 100),
                    number_format($total / 100, 2, '.', ''),
                    number_format($total / 100, 2),
                    '₹' . number_format($total / 100, 2),
                    date('d M Y', strtotime($inv['created_at'])),
                    date('M Y', strtotime($inv['created_at'])),
                ])));
            ?>
                <div class="inv-card bg-white rounded-2xl p-5 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-indigo-300 transition-all duration-200 flex flex-col justify-between relative overflow-hidden group <?= $isVoid ? 'opacity-60 bg-slate-50/50' : '' ?>"
                     data-search="<?= e($searchKeywords) ?>">
                    
                    <div class="space-y-4">
                        <!-- Card Header: Invoice Badge & Status -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <a href="<?= app_url('/invoices/' . $inv['id']) ?>" 
                               class="inline-flex items-center space-x-1.5 text-xs font-mono font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50/70 hover:bg-indigo-100/70 px-2.5 py-1 rounded-lg border border-indigo-100 transition <?= $isVoid ? 'line-through text-slate-400 bg-slate-50 border-slate-200' : '' ?>">
                                <i class="fa-solid fa-receipt text-[10px] text-indigo-500"></i>
                                <span><?= e($inv['invoice_number']) ?></span>
                            </a>

                            <?php if ($isPaid): ?>
                                <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Settled</span>
                                </span>
                            <?php elseif ($isVoid): ?>
                                <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                    <i class="fa-solid fa-ban text-[9px]"></i>
                                    <span>Void</span>
                                </span>
                            <?php elseif ($isOverdue): ?>
                                <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
                                    <i class="fa-solid fa-triangle-exclamation text-[9px]"></i>
                                    <span><?= $daysOverdue ?>d Overdue</span>
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span>Pending</span>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Client Capsule -->
                        <div>
                            <a href="<?= app_url('/invoices/' . $inv['id']) ?>" class="font-extrabold text-slate-900 text-base tracking-tight block truncate group-hover:text-indigo-600 transition-colors">
                                <?= e($clientName) ?>
                            </a>
                            <div class="flex items-center space-x-1.5 text-xs text-slate-500 mt-0.5 truncate">
                                <i class="fa-regular fa-building text-[10px] text-slate-400 shrink-0"></i>
                                <span class="truncate"><?= e($company ?: 'Direct Commercial Account') ?></span>
                            </div>
                        </div>

                        <!-- Amount Pass Ticket -->
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Billed</span>
                                <div class="font-mono font-black text-lg <?= $isPaid ? 'text-emerald-700' : ($isVoid ? 'text-slate-400 line-through' : 'text-slate-900') ?>">
                                    <?= format_cents($total) ?>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 block">18% GST</span>
                                <div class="font-mono text-xs font-bold text-slate-600">
                                    <?= format_cents((int)$inv['tax_cents']) ?>
                                </div>
                                <span class="text-[9px] text-slate-400 font-medium block">SAC 998313</span>
                            </div>
                        </div>

                        <!-- Lifecycle Dates -->
                        <div class="text-xs text-slate-500 flex items-center justify-between font-mono pt-0.5">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-regular fa-calendar text-[10px] text-slate-400"></i>
                                <span><?= date('M d, Y', strtotime($inv['created_at'])) ?></span>
                            </span>
                            <?php if (!empty($inv['due_date'])): ?>
                                <span class="flex items-center gap-1.5 <?= $isOverdue ? 'text-rose-600 font-bold' : '' ?>">
                                    <i class="fa-regular fa-clock text-[10px] <?= $isOverdue ? 'text-rose-500' : 'text-slate-400' ?>"></i>
                                    <span>Due <?= date('M d', strtotime($inv['due_date'])) ?></span>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between gap-2">
                        <div class="flex items-center space-x-1.5">
                            <?php if (!$isVoid): ?>
                                <button type="button" onclick="copyToClipboard('<?= $publicPayUrl ?>', '<?= e($inv['invoice_number']) ?>')" 
                                        class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 border border-slate-200/80 transition flex items-center justify-center" title="Copy Payment Link">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                                <a href="<?= $waUrl ?>" target="_blank" 
                                   class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 transition flex items-center justify-center" title="Dispatch to Client WhatsApp">
                                    <i class="fa-brands fa-whatsapp text-xs"></i>
                                </a>
                            <?php endif; ?>

                            <a href="<?= app_url('/invoices/' . $inv['id'] . '/print') ?>" target="_blank" 
                               class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-800 border border-slate-200/80 transition flex items-center justify-center" title="Print PDF / UPI QR">
                                <i class="fa-solid fa-print text-xs"></i>
                            </a>
                        </div>

                        <a href="<?= app_url('/invoices/' . $inv['id']) ?>" 
                           class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-indigo-600 text-white font-bold text-xs transition-colors flex items-center space-x-1.5 shadow-xs">
                            <span>View</span>
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
                    <!-- Search Zero Results Row (Table) -->
                    <tr id="invoiceSearchZeroTable" class="hidden">
                        <td colspan="6" class="px-6 py-12 text-center bg-white">
                            <div class="space-y-2 max-w-xs mx-auto">
                                <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300 block"></i>
                                <span class="text-xs font-bold text-slate-800 block">No Invoices Located</span>
                                <p class="text-[11px] text-slate-400 block">No results match "<span id="searchZeroQueryTable" class="font-bold font-mono text-slate-600"></span>".</p>
                                <button type="button" onclick="clearInvoiceSearch()" class="mt-2 px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition inline-flex items-center space-x-1">
                                    <i class="fa-solid fa-rotate-left text-xs"></i>
                                    <span>Clear Filter</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <?php foreach ($invoices as $inv): 
                        $status = strtolower($inv['status']);
                        $isPaid = ($status === 'paid');
                        $isVoid = ($status === 'void');
                        $isOverdue = (!$isPaid && !$isVoid && !empty($inv['due_date']) && strtotime($inv['due_date']) < strtotime('today'));
                        $daysOverdue = $isOverdue ? (int)floor((time() - strtotime($inv['due_date'])) / 86400) : 0;
                        $total = (int)($inv['total_cents'] ?? ($inv['subtotal_cents'] + $inv['tax_cents']));
                        $clientName = $inv['client_display_name'] ?? $inv['customer_name'] ?? 'Direct Client';
                        $company = $inv['client_company'] ?? '';
                        $publicPayUrl = !empty($inv['payment_token']) ? app_url('/pay/' . $inv['payment_token']) : app_url('/invoices/' . $inv['id']);
                        $waMsg = urlencode("Hello {$clientName}, please find your official tax invoice {$inv['invoice_number']} for " . format_cents($total) . ". You can view details and settle online here: {$publicPayUrl}");
                        $cPhone = preg_replace('/[^0-9]/', '', $inv['customer_phone'] ?? $inv['client_phone'] ?? '');
                        if (!empty($cPhone) && strlen($cPhone) === 10) $cPhone = '91' . $cPhone;
                        $waUrl = !empty($cPhone) ? "https://wa.me/{$cPhone}?text={$waMsg}" : "https://api.whatsapp.com/send?text={$waMsg}";
                        $searchKeywords = strtolower(implode(' ', array_filter([
                            $inv['invoice_number'],
                            $clientName,
                            $company,
                            $inv['client_email'] ?? $inv['customer_email'] ?? '',
                            $inv['customer_phone'] ?? '',
                            $inv['customer_gstin'] ?? '',
                            $status,
                            $isPaid ? 'paid settled complete' : '',
                            $isOverdue ? 'overdue late unpaid' : '',
                            ($status === 'open' && !$isOverdue) ? 'in-flight open pending due' : '',
                            $isVoid ? 'void cancelled' : '',
                            (string)round($total / 100),
                            number_format($total / 100, 2, '.', ''),
                            number_format($total / 100, 2),
                            '₹' . number_format($total / 100, 2),
                            date('d M Y', strtotime($inv['created_at'])),
                            date('M Y', strtotime($inv['created_at'])),
                        ])));
                    ?>
                        <tr class="inv-row hover:bg-blue-50/20 transition <?= $isVoid ? 'opacity-60 bg-slate-50/50' : '' ?>" data-search="<?= e($searchKeywords) ?>">
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
                                        <a href="<?= $waUrl ?>" target="_blank" 
                                           class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Dispatch to Client WhatsApp">
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

// High-Precision Multi-Token Live Search Engine
function clearInvoiceSearch() {
    const input = document.getElementById('invoiceSearch');
    if (!input) return;
    input.value = '';
    applyInvoiceFilter('');
    input.focus();
}

function applyInvoiceFilter(rawQuery) {
    const query = (rawQuery || '').toLowerCase().trim();
    const clearBtn = document.getElementById('clearInvoiceSearchBtn');
    const badge = document.getElementById('invoiceMatchBadge');
    const zeroCards = document.getElementById('invoiceSearchZeroCards');
    const zeroTable = document.getElementById('invoiceSearchZeroTable');
    const zeroQueryCards = document.getElementById('searchZeroQueryCards');
    const zeroQueryTable = document.getElementById('searchZeroQueryTable');

    if (clearBtn) {
        if (query.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }

    const words = query.split(/\s+/).filter(Boolean);
    let matchedCards = 0;
    let matchedRows = 0;

    // Filter cards
    document.querySelectorAll('.inv-card').forEach(card => {
        const text = card.getAttribute('data-search') || '';
        const isMatch = words.length === 0 || words.every(w => text.includes(w));
        card.style.display = isMatch ? '' : 'none';
        if (isMatch) matchedCards++;
    });

    // Filter table rows
    document.querySelectorAll('.inv-row').forEach(row => {
        const text = row.getAttribute('data-search') || '';
        const isMatch = words.length === 0 || words.every(w => text.includes(w));
        row.style.display = isMatch ? '' : 'none';
        if (isMatch) matchedRows++;
    });

    // Update match counter badge
    if (badge) {
        if (words.length > 0) {
            badge.innerText = `${matchedCards} match${matchedCards === 1 ? '' : 'es'}`;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    // Handle zero results empty states
    if (words.length > 0 && matchedCards === 0) {
        if (zeroCards) {
            zeroCards.classList.remove('hidden');
            if (zeroQueryCards) zeroQueryCards.innerText = query;
        }
    } else {
        if (zeroCards) zeroCards.classList.add('hidden');
    }

    if (words.length > 0 && matchedRows === 0) {
        if (zeroTable) {
            zeroTable.classList.remove('hidden');
            if (zeroQueryTable) zeroQueryTable.innerText = query;
        }
    } else {
        if (zeroTable) zeroTable.classList.add('hidden');
    }
}

// Live Search Input Listener
document.getElementById('invoiceSearch')?.addEventListener('input', function(e) {
    applyInvoiceFilter(e.target.value);
});

// Escape key to reset search
document.getElementById('invoiceSearch')?.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        clearInvoiceSearch();
    }
});

// Advanced Export Modal Handlers
function openExportModal() {
    document.getElementById('exportModal')?.classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal')?.classList.add('hidden');
}

function setExportDates(preset) {
    const today = new Date();
    const fromInput = document.getElementById('exportFromDate');
    const toInput = document.getElementById('exportToDate');

    if (preset === 'all') {
        fromInput.value = '';
        toInput.value = '';
    } else if (preset === 'this_month') {
        const start = new Date(today.getFullYear(), today.getMonth(), 1);
        fromInput.value = start.toISOString().split('T')[0];
        toInput.value = today.toISOString().split('T')[0];
    } else if (preset === 'last_month') {
        const start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        const end = new Date(today.getFullYear(), today.getMonth(), 0);
        fromInput.value = start.toISOString().split('T')[0];
        toInput.value = end.toISOString().split('T')[0];
    }
}

function triggerDownloadExport(type) {
    const from = document.getElementById('exportFromDate')?.value || '';
    const to = document.getElementById('exportToDate')?.value || '';
    const status = document.getElementById('exportStatus')?.value || '';

    const base = (type === 'gstr1') ? '<?= app_url('/invoices/gstr1-export') ?>' : '<?= app_url('/invoices/export') ?>';
    const params = new URLSearchParams();
    if (from) params.set('from_date', from);
    if (to) params.set('to_date', to);
    if (status) params.set('status', status);

    const fullUrl = params.toString() ? `${base}?${params.toString()}` : base;
    window.location.href = fullUrl;
    closeExportModal();
}
</script>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL: ADVANCED DATE-RANGE EXPORT & TAX FILING
     ══════════════════════════════════════════════════════════════════ -->
<div id="exportModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-lg w-full p-6 sm:p-7 my-auto relative text-slate-800 animate-in fade-in zoom-in duration-150">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base border border-emerald-100">
                    <i class="fa-solid fa-file-arrow-down"></i>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900">Export Ledger &amp; Tax Returns</h3>
                    <p class="text-xs text-slate-400">Filter transactions by date range for accounting and GSTR-1 audits.</p>
                </div>
            </div>
            <button type="button" onclick="closeExportModal()" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="mt-5 space-y-4">
            <!-- Preset Buttons -->
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Quick Date Range Presets</label>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="setExportDates('all')" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition text-center">
                        All Time
                    </button>
                    <button type="button" onclick="setExportDates('this_month')" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition text-center">
                        This Month
                    </button>
                    <button type="button" onclick="setExportDates('last_month')" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition text-center">
                        Last Month
                    </button>
                </div>
            </div>

            <!-- Date Range Inputs -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">From Date</label>
                    <input type="date" id="exportFromDate"
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-semibold focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">To Date</label>
                    <input type="date" id="exportToDate"
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-semibold focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1">Invoice Status Filter</label>
                <select id="exportStatus"
                        class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-bold focus:bg-white focus:border-blue-600 outline-none transition">
                    <option value="">All Statuses (Paid, Pending, Void)</option>
                    <option value="paid">Settled / Paid Invoices Only</option>
                    <option value="open">Pending / Open Invoices Only</option>
                    <option value="void">Void / Cancelled Invoices Only</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <button type="button" onclick="triggerDownloadExport('csv')"
                        class="py-2.5 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition flex items-center justify-center space-x-1.5 shadow-sm">
                    <i class="fa-solid fa-file-csv text-emerald-400"></i>
                    <span>Export Standard CSV</span>
                </button>
                <button type="button" onclick="triggerDownloadExport('gstr1')"
                        class="py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center justify-center space-x-1.5 shadow-sm">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Export GSTR-1 Format</span>
                </button>
            </div>
        </div>
    </div>
</div>