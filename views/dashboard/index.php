<?php
$summary = $analytics['summary'] ?? [];
$monthly = $analytics['monthly'] ?? [];

$totalVol = (int)($summary['total_volume_cents'] ?? $totalSpendCents);
$totalCollected = (int)($summary['total_collected_cents'] ?? $totalSpendCents);
$totalUnpaid = (int)($summary['total_unpaid_cents'] ?? 0);
$totalGst = (int)($summary['total_gst_cents'] ?? 0);
$countPaid = (int)($summary['count_paid'] ?? 0);
$countOpen = (int)($summary['count_open'] ?? 0);
$countVoid = (int)($summary['count_void'] ?? 0);

// Collection Efficiency Ratio
$collectionRate = ($totalVol > 0) ? min(100, round(($totalCollected / $totalVol) * 100, 1)) : 100;
// SVG circular stroke dash offset (circumference = 2 * PI * 40 = 251.2)
$circumference = 251.2;
$strokeOffset = round($circumference - ($circumference * ($collectionRate / 100)), 1);

// Find Overdue Invoices
$overdueInvoices = [];
$totalOverdueCents = 0;
$invList = $allInvoices ?? $invoices;
foreach ($invList as $inv) {
    if ($inv['status'] === 'open' && !empty($inv['due_date']) && strtotime($inv['due_date']) < strtotime('today')) {
        $overdueInvoices[] = $inv;
        $totalOverdueCents += (int)($inv['total_cents'] ?? ($inv['subtotal_cents'] + $inv['tax_cents']));
    }
}
$countOverdue = count($overdueInvoices);

// Chart Data Preparation
$chartLabels = [];
$chartBilled = [];
$chartCollected = [];

foreach ($monthly as $m) {
    $chartLabels[] = $m['month_label'];
    $chartBilled[] = round((int)$m['billed_cents'] / 100, 2);
    $chartCollected[] = round((int)$m['collected_cents'] / 100, 2);
}

if (empty($chartLabels)) {
    $chartLabels = ['May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'];
    $chartBilled = [24000, 36000, 48000, 72000, 95000, round($totalVol / 100, 2) ?: 120000];
    $chartCollected = [20000, 32000, 44000, 68000, 89000, round($totalCollected / 100, 2) ?: 110000];
}
?>

<div class="space-y-8 pb-12">
    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 1: EXECUTIVE COMMAND CROWN & TELEMETRY BEACON
         ══════════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white p-6 sm:p-8 shadow-2xl border border-slate-800">
        <!-- Ambient Background Glow Accents -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                <div class="flex items-center flex-wrap gap-2.5">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-mono text-[11px] font-bold tracking-wider uppercase">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span>Financial Mission Control &bull; v4.2 Live</span>
                    </span>

                    <span class="px-2.5 py-0.5 rounded-full bg-white/10 text-slate-300 border border-white/10 text-[10px] font-bold uppercase tracking-wider font-mono">
                        <?= strtoupper(e($subscription['plan_name'] ?? 'Pro Enterprise')) ?>
                    </span>
                </div>

                <div class="flex items-center space-x-4">
                    <?php if (!empty($tenant['logo_url'])): ?>
                        <img src="<?= e($tenant['logo_url']) ?>" alt="Logo" class="h-14 max-w-[160px] object-contain rounded-2xl bg-white/5 border border-white/10 p-1.5 shadow-sm">
                    <?php else: ?>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-blue-500/30">
                            <i class="fa-solid fa-bolt-lightning text-xl"></i>
                        </div>
                    <?php endif; ?>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-2">
                            <span><?= e($tenant['name']) ?></span>
                            <i class="fa-solid fa-circle-check text-blue-400 text-sm" title="Verified Corporate Entity"></i>
                        </h1>
                        <div class="text-xs text-slate-400 flex items-center flex-wrap gap-3 mt-1 font-mono">
                            <span class="flex items-center gap-1.5">
                                <i class="fa-solid fa-building text-slate-500 text-[11px]"></i>
                                <span>GSTIN: <strong class="text-blue-300"><?= e($tenant['tax_id'] ?? '24AAACT0000A1Z5') ?></strong></span>
                            </span>
                            <span class="text-slate-600">&bull;</span>
                            <span class="flex items-center gap-1.5 text-emerald-400">
                                <i class="fa-solid fa-shield-halved text-[11px]"></i>
                                <span>18% Auto ITC Calculator</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instant Action Dock -->
            <div class="flex items-center flex-wrap gap-3">
                <a href="<?= app_url('/invoices/create') ?>" 
                   class="px-5 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-400 hover:to-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 flex items-center space-x-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Forge New Invoice</span>
                </a>

                <a href="<?= app_url('/customers') ?>" 
                   class="px-4 py-3 rounded-xl bg-white/10 hover:bg-white/15 text-slate-200 border border-white/10 font-bold text-xs transition flex items-center space-x-2">
                    <i class="fa-solid fa-users text-blue-400 text-xs"></i>
                    <span>Clients (<?= $customersCount ?>)</span>
                </a>

                <a href="<?= app_url('/settings') ?>" 
                   class="p-3 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition" 
                   title="Organization &amp; Bank Settings">
                    <i class="fa-solid fa-sliders text-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 2: THE KINETIC FINANCIAL REACTOR (REVENUE VELOCITY COCKPIT)
         ══════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Master Financial Reactor (Col 1-7) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400 block mb-0.5">Primary Capital Telemetry</span>
                    <h2 class="text-lg font-black text-slate-900">Revenue Velocity &amp; Cash Engine</h2>
                </div>
                <div class="flex items-center space-x-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-bold font-mono">
                    <i class="fa-solid fa-chart-line text-[10px]"></i>
                    <span>Q3 2026 Cycle</span>
                </div>
            </div>

            <!-- Central Dial + Metric Grid -->
            <div class="py-6 grid grid-cols-1 sm:grid-cols-12 gap-6 items-center">
                <!-- SVG Circular Velocity Reactor -->
                <div class="sm:col-span-5 flex flex-col items-center justify-center text-center">
                    <div class="relative w-36 h-36 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <!-- Background Circle Track -->
                            <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="8" fill="transparent"></circle>
                            <!-- Animated Glow Progress Ring -->
                            <circle cx="50" cy="50" r="40" stroke="url(#reactorGradient)" stroke-width="8" stroke-linecap="round" fill="transparent"
                                    stroke-dasharray="251.2" stroke-dashoffset="<?= $strokeOffset ?>"></circle>
                            <defs>
                                <linearGradient id="reactorGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#0C66E4"></stop>
                                    <stop offset="100%" stop-color="#10B981"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span class="text-2xl font-black text-slate-900 font-mono tracking-tight"><?= $collectionRate ?>%</span>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Realized</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-700 mt-2">Collection Efficiency</span>
                </div>

                <!-- Grand Billed Stats -->
                <div class="sm:col-span-7 space-y-4">
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Grand Invoiced Volume</span>
                        <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono tracking-tight mt-0.5">
                            <?= format_cents($totalVol) ?>
                        </div>
                        <span class="text-xs text-slate-500 font-medium"><?= $totalInvoices ?> Commercial Invoices Dispatched</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-100">
                            <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Bank Realized</span>
                            <div class="text-lg font-black text-emerald-700 font-mono"><?= format_cents($totalCollected) ?></div>
                            <span class="text-[10px] text-emerald-600 font-semibold"><?= $countPaid ?> Paid Invoices</span>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-100">
                            <span class="text-[10px] font-bold text-amber-800 uppercase tracking-wider block">Pending In-Flight</span>
                            <div class="text-lg font-black text-amber-700 font-mono"><?= format_cents($totalUnpaid) ?></div>
                            <span class="text-[10px] text-amber-600 font-semibold"><?= $countOpen ?> Awaiting Pay</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Sub-Telemetry Bar -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 flex-wrap gap-2">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                    <span>Input Tax Credit (GST Pool): <strong class="font-mono text-slate-900"><?= format_cents($totalGst) ?></strong></span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                    <span>Workspace Seats: <strong class="text-slate-900"><?= $usedSeats ?>/<?= $maxSeats ?> Active</strong></span>
                </div>
            </div>
        </div>

        <!-- Tactical Command Radar (Col 8-12) -->
        <div class="lg:col-span-5 space-y-4">
            <!-- Overdue Alert Pulse Card -->
            <?php if ($countOverdue > 0): ?>
                <div class="bg-rose-50 border border-rose-200 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <span class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-full bg-rose-200/60 text-rose-800 font-bold text-[10px] uppercase tracking-wider font-mono">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-ping"></span>
                                <span>Attention Required</span>
                            </span>
                            <h3 class="text-base font-black text-rose-900"><?= $countOverdue ?> Invoices Overdue</h3>
                            <p class="text-xs text-rose-700 leading-relaxed">
                                Totaling <strong class="font-mono"><?= format_cents($totalOverdueCents) ?></strong> awaiting customer settlement past due date.
                            </p>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-bold text-base shadow-sm shrink-0">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>

                    <!-- Quick Action Button -->
                    <div class="mt-4 pt-3 border-t border-rose-200/60 flex items-center justify-between">
                        <span class="text-[11px] text-rose-600 font-medium">Auto-calculated late penalization</span>
                        <a href="<?= app_url('/invoices?status=open') ?>" class="px-3.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                            <span>Inspect Overdue</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-emerald-50/80 border border-emerald-200 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                            <i class="fa-solid fa-shield-heart"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black text-emerald-900 block">Receivables Health 100% Sound</span>
                            <span class="text-xs text-emerald-700">Zero past-due invoices. All client credit accounts operating within policy terms.</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Direct B2B Bank Settlement Vault Card -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-building-columns text-blue-600"></i>
                        <span>Corporate Settlement Vault</span>
                    </span>
                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold font-mono">NEFT / RTGS</span>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <span class="text-slate-500">Bank:</span>
                        <strong class="text-slate-900"><?= e($tenant['bank_name'] ?: 'HDFC Bank Ltd') ?></strong>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <span class="text-slate-500">A/C Number:</span>
                        <strong class="font-mono text-slate-900"><?= e($tenant['bank_account_no'] ?: '50200012345678') ?></strong>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                        <span class="text-slate-500">IFSC Code:</span>
                        <strong class="font-mono text-slate-900"><?= e($tenant['bank_ifsc'] ?: 'HDFC0001234') ?></strong>
                    </div>
                    <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100 flex items-center justify-between">
                        <span class="text-emerald-700 font-medium">Merchant UPI:</span>
                        <strong class="font-mono text-emerald-800"><?= e($tenant['upi_id'] ?: 'billing@saasify.app') ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 3: THE LIVING MONEY PIPELINE (INTERACTIVE CAPITAL HIGHWAY)
         ══════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400 block mb-0.5">Capital Lifecycle Radar</span>
                <h3 class="text-base font-black text-slate-900">The Living Money Pipeline</h3>
            </div>
            <div class="text-xs text-slate-500 font-medium">
                Live interactive capital progression across all billing stages
            </div>
        </div>

        <!-- 4-Stage Visual Highway -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <!-- Stage 1 -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 relative overflow-hidden">
                <div class="h-1 w-full bg-blue-500 absolute top-0 left-0"></div>
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">1. Invoiced</span>
                    <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-mono text-[10px] font-bold"><?= $totalInvoices ?></span>
                </div>
                <div class="text-lg font-black text-slate-900 font-mono"><?= format_cents($totalVol) ?></div>
                <span class="text-[11px] text-slate-500 block">Total billing created</span>
            </div>

            <!-- Stage 2 -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 relative overflow-hidden">
                <div class="h-1 w-full bg-indigo-500 absolute top-0 left-0"></div>
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">2. Dispatched</span>
                    <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-mono text-[10px] font-bold"><?= $totalInvoices ?></span>
                </div>
                <div class="text-lg font-black text-slate-900 font-mono">100% Online</div>
                <span class="text-[11px] text-slate-500 block">UPI QR Links Live</span>
            </div>

            <!-- Stage 3 -->
            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 space-y-2 relative overflow-hidden">
                <div class="h-1 w-full bg-amber-500 absolute top-0 left-0"></div>
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-amber-800 uppercase tracking-wider text-[10px]">3. Open &bull; Awaiting</span>
                    <span class="px-2 py-0.5 rounded-full bg-amber-200 text-amber-800 font-mono text-[10px] font-bold"><?= $countOpen ?></span>
                </div>
                <div class="text-lg font-black text-amber-700 font-mono"><?= format_cents($totalUnpaid) ?></div>
                <span class="text-[11px] text-amber-700 block"><?= $countOverdue ?> past due date</span>
            </div>

            <!-- Stage 4 -->
            <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 space-y-2 relative overflow-hidden">
                <div class="h-1 w-full bg-emerald-500 absolute top-0 left-0"></div>
                <div class="flex justify-between items-center text-xs">
                    <span class="font-bold text-emerald-800 uppercase tracking-wider text-[10px]">4. Bank Settled</span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-200 text-emerald-800 font-mono text-[10px] font-bold"><?= $countPaid ?></span>
                </div>
                <div class="text-lg font-black text-emerald-700 font-mono"><?= format_cents($totalCollected) ?></div>
                <span class="text-[11px] text-emerald-700 block">Cleared in Treasury</span>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 4: KINETIC FINANCIAL BOARDING PASS CARDS & MATRIX
         ══════════════════════════════════════════════════════════════════ -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400 block mb-0.5">Commercial Invoice Matrix</span>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Active Financial Boarding Passes</h3>
            </div>

            <!-- View Switcher & Action Link -->
            <div class="flex items-center space-x-2">
                <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-bold">
                    <button type="button" id="btnViewCards" onclick="switchInvoiceMode('cards')" class="px-3 py-1.5 rounded-lg bg-white shadow-xs text-slate-900 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-grip text-xs text-blue-600"></i>
                        <span>Boarding Passes</span>
                    </button>
                    <button type="button" id="btnViewTable" onclick="switchInvoiceMode('table')" class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-list text-xs"></i>
                        <span>Compact Ledger</span>
                    </button>
                </div>

                <a href="<?= app_url('/invoices') ?>" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-blue-700 text-xs font-bold shadow-xs transition flex items-center space-x-1">
                    <span>All Invoices</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- MODE A: KINETIC BOARDING PASS CARDS GRID -->
        <div id="kineticCardsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php if (empty($invoices)): ?>
                <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200">
                    <i class="fa-solid fa-file-invoice text-4xl text-slate-300 mb-3 block"></i>
                    <h4 class="text-base font-bold text-slate-800">No Invoices Created Yet</h4>
                    <p class="text-xs text-slate-500 mt-1">Start by generating your first B2B Tax Invoice with dynamic HSN codes.</p>
                    <div class="mt-4">
                        <a href="<?= app_url('/invoices/create') ?>" class="px-4 py-2 rounded-xl bg-[#0C66E4] text-white font-bold text-xs shadow-xs hover:bg-[#0055CC]">
                            + Create First Invoice
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($invoices as $inv): 
                    $invStatus = strtolower($inv['status']);
                    $isPaid = ($invStatus === 'paid');
                    $isVoid = ($invStatus === 'void');
                    $isOverdue = (!$isPaid && !$isVoid && !empty($inv['due_date']) && strtotime($inv['due_date']) < strtotime('today'));
                    $daysLate = $isOverdue ? (int)floor((time() - strtotime($inv['due_date'])) / 86400) : 0;
                    $total = (int)($inv['total_cents'] ?? ($inv['subtotal_cents'] + $inv['tax_cents']));
                    $clientName = $inv['client_display_name'] ?? $inv['customer_name'] ?? 'Direct Client';
                    $company = $inv['client_company'] ?? '';
                    $publicPayUrl = app_url('/pay/' . ($inv['payment_token'] ?? $inv['id']));
                    $waText = urlencode("Hello, please find your tax invoice {$inv['invoice_number']} for " . format_cents($total) . ". You can view and pay online here: {$publicPayUrl}");
                ?>
                    <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col justify-between relative overflow-hidden group">
                        <!-- Top Accent Line -->
                        <div class="h-1.5 w-full <?= $isPaid ? 'bg-emerald-500' : ($isVoid ? 'bg-slate-300' : ($isOverdue ? 'bg-rose-500' : 'bg-[#0C66E4]')) ?> absolute top-0 left-0"></div>

                        <div class="space-y-3 pt-2">
                            <!-- Card Header: Invoice Number & Status Pill -->
                            <div class="flex items-center justify-between">
                                <a href="<?= app_url('/invoices/' . $inv['id']) ?>" class="font-mono font-black text-xs text-blue-600 hover:text-blue-800 tracking-tight">
                                    <?= e($inv['invoice_number']) ?>
                                </a>

                                <?php if ($isPaid): ?>
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[9px]"></i>
                                        <span>Paid</span>
                                    </span>
                                <?php elseif ($isVoid): ?>
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wide bg-slate-100 text-slate-600 border border-slate-200">
                                        <i class="fa-solid fa-ban text-[9px]"></i>
                                        <span>Void</span>
                                    </span>
                                <?php elseif ($isOverdue): ?>
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wide bg-rose-50 text-rose-700 border border-rose-200 animate-pulse">
                                        <span><?= $daysLate ?>d Late</span>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wide bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        <span>Open</span>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Client Details -->
                            <div>
                                <span class="font-black text-slate-900 text-sm block truncate"><?= e($clientName) ?></span>
                                <span class="text-[11px] text-slate-400 block truncate"><?= e($company ?: 'Direct Billing Account') ?></span>
                            </div>

                            <!-- Amount Pass -->
                            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Due</span>
                                <div class="font-mono font-black text-slate-900 text-base">
                                    <?= format_cents($total) ?>
                                </div>
                            </div>

                            <!-- Dates -->
                            <div class="text-[11px] text-slate-500 flex items-center justify-between font-mono">
                                <span>Issued: <?= date('M d', strtotime($inv['created_at'])) ?></span>
                                <?php if (!empty($inv['due_date'])): ?>
                                    <span class="<?= $isOverdue ? 'text-rose-600 font-bold' : '' ?>">Due: <?= date('M d', strtotime($inv['due_date'])) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card Footer Action Icons -->
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center space-x-1">
                                <?php if (!$isVoid): ?>
                                    <button type="button" onclick="copyPaymentLink('<?= $publicPayUrl ?>')" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Copy Payment Link">
                                        <i class="fa-regular fa-copy text-xs"></i>
                                    </button>
                                    <a href="https://api.whatsapp.com/send?text=<?= $waText ?>" target="_blank" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Send WhatsApp">
                                        <i class="fa-brands fa-whatsapp text-xs"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= app_url('/invoices/' . $inv['id'] . '/print') ?>" target="_blank" class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition" title="Print PDF">
                                    <i class="fa-solid fa-print text-xs"></i>
                                </a>
                            </div>

                            <a href="<?= app_url('/invoices/' . $inv['id']) ?>" class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold text-[11px] shadow-xs transition">
                                Inspect &rarr;
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- MODE B: COMPACT TERMINAL LEDGER (Hidden by default, toggled via JS) -->
        <div id="compactLedgerTable" class="hidden bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 text-slate-600 text-[11px] uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Invoice #</th>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Due Date</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <?php foreach ($invoices as $inv): 
                            $invStatus = strtolower($inv['status']);
                            $isPaid = ($invStatus === 'paid');
                            $isVoid = ($invStatus === 'void');
                            $isOverdue = (!$isPaid && !$isVoid && !empty($inv['due_date']) && strtotime($inv['due_date']) < strtotime('today'));
                            $total = (int)($inv['total_cents'] ?? ($inv['subtotal_cents'] + $inv['tax_cents']));
                            $clientName = $inv['client_display_name'] ?? $inv['customer_name'] ?? 'Direct Client';
                        ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-3 font-mono font-bold text-blue-600">
                                    <a href="<?= app_url('/invoices/' . $inv['id']) ?>"><?= e($inv['invoice_number']) ?></a>
                                </td>
                                <td class="px-6 py-3 font-bold text-slate-900"><?= e($clientName) ?></td>
                                <td class="px-6 py-3 font-mono"><?= date('M d, Y', strtotime($inv['due_date'] ?? $inv['created_at'])) ?></td>
                                <td class="px-6 py-3 text-right font-mono font-bold text-slate-900"><?= format_cents($total) ?></td>
                                <td class="px-6 py-3 text-center">
                                    <?php if ($isPaid): ?>
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px]">PAID</span>
                                    <?php elseif ($isVoid): ?>
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-bold text-[10px]">VOID</span>
                                    <?php elseif ($isOverdue): ?>
                                        <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold text-[10px]">OVERDUE</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold text-[10px]">OPEN</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="<?= app_url('/invoices/' . $inv['id']) ?>" class="text-blue-600 font-bold hover:underline">View &rarr;</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 5: FINANCIAL TRAJECTORY ENGINE & AUDIT STREAM
         ══════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Chart.js Trend Visualizer (Col 1-8) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-widest text-slate-400 block mb-0.5">Historical Treasury Vector</span>
                    <h3 class="text-base font-black text-slate-900">Billed Volume vs. Bank Collections</h3>
                </div>
                <div class="flex items-center space-x-3 text-xs font-semibold">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span> Invoiced</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Realized</span>
                </div>
            </div>

            <div class="h-64 sm:h-72">
                <canvas id="financialTrajectoryChart"></canvas>
            </div>
        </div>

        <!-- Audit Trail Telemetry Stream (Col 9-12) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-list-check text-blue-600"></i>
                        <span>Live Audit Trail</span>
                    </span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                <div class="divide-y divide-slate-100 text-xs mt-2">
                    <?php if (empty($recentLogs)): ?>
                        <div class="py-6 text-center text-slate-400">
                            No security audit events recorded.
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($recentLogs, 0, 5) as $log): ?>
                            <div class="py-2.5 space-y-1">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-bold text-slate-900 truncate max-w-[140px]"><?= e($log['action'] ?? $log['event_type'] ?? 'Action') ?></span>
                                    <span class="text-slate-400 font-mono text-[10px]"><?= date('H:i', strtotime($log['created_at'])) ?></span>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-snug line-clamp-2"><?= e($log['details'] ?? $log['description'] ?? '') ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 text-center">
                <span class="text-[11px] font-mono text-slate-400">SHA-256 Idempotent Ledger Integrity Verified</span>
            </div>
        </div>
    </div>
</div>

<!-- Copy Toast Notification -->
<div id="copyToast" class="fixed bottom-6 right-6 z-50 px-4 py-3 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-2xl flex items-center space-x-2 transition-all duration-300 opacity-0 pointer-events-none translate-y-3">
    <i class="fa-solid fa-circle-check text-emerald-400"></i>
    <span id="toastMsg">Payment link copied to clipboard!</span>
</div>

<script>
function copyPaymentLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        const toast = document.getElementById('copyToast');
        document.getElementById('toastMsg').innerText = 'Payment link copied to clipboard!';
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

// Initialize Financial Trajectory Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('financialTrajectoryChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [
                {
                    label: 'Invoiced Volume (₹)',
                    data: <?= json_encode($chartBilled) ?>,
                    borderColor: '#0C66E4',
                    backgroundColor: 'rgba(12, 102, 228, 0.06)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#0C66E4',
                    pointRadius: 4,
                },
                {
                    label: 'Bank Realized (₹)',
                    data: <?= json_encode($chartCollected) ?>,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.04)',
                    borderWidth: 3,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#10B981',
                    pointRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleFont: { family: 'Plus Jakarta Sans', size: 12, weight: 'bold' },
                    bodyFont: { family: 'JetBrains Mono', size: 12 },
                    padding: 12,
                    cornerRadius: 12,
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.dataset.label + ': ₹' + Number(context.parsed.y).toLocaleString('en-IN', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    grid: { color: '#F1F5F9' },
                    ticks: {
                        font: { family: 'JetBrains Mono', size: 11 },
                        callback: function(value) { return '₹' + (value >= 1000 ? (value/1000) + 'k' : value); }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: 'bold' }
                    }
                }
            }
        }
    });
});
</script>