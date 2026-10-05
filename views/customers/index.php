<?php
$totalPaid = (int)array_sum(array_column($customers, 'paid_cents'));
$totalDue = (int)array_sum(array_column($customers, 'outstanding_cents'));
$gstCount = (int)($stats['gst_registered'] ?? 0);
$phoneCount = (int)($stats['with_phone'] ?? 0);
$clientCount = count($customers);
$gstPercentage = ($clientCount > 0) ? round(($gstCount / $clientCount) * 100) : 0;
?>

<div class="space-y-8 pb-12">
    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 1: ENTERPRISE CLIENT VAULT CROWN
         ══════════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white p-6 sm:p-8 shadow-2xl border border-slate-800">
        <!-- Ambient Background Glow Accents -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center flex-wrap gap-2.5">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 font-mono text-[11px] font-bold tracking-wider uppercase">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>B2B Corporate Ledger &bull; Client Vault</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-white/10 text-slate-300 border border-white/10 text-[10px] font-bold uppercase tracking-wider font-mono">
                        <?= $clientCount ?> Registered Accounts
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                    <span>Corporate Clients &amp; Receivables Directory</span>
                </h1>
                <p class="text-xs text-slate-400 max-w-xl">
                    Manage corporate clients, tax credentials (GSTIN / PAN), billing profiles, and monitor real-time client debt exposure with instant invoice dispatch.
                </p>
            </div>

            <!-- Instant Actions Dock -->
            <div class="flex items-center flex-wrap gap-3">
                <button type="button" onclick="document.getElementById('addClientModal').classList.remove('hidden')" 
                        class="px-5 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-400 hover:to-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 flex items-center space-x-2">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>+ Register Client</span>
                </button>

                <button type="button" onclick="document.getElementById('importClientsModal').classList.remove('hidden')" 
                        class="px-4 py-3 rounded-xl bg-white/10 hover:bg-white/15 text-slate-200 border border-white/10 font-bold text-xs transition flex items-center space-x-2">
                    <i class="fa-solid fa-file-arrow-up text-emerald-400 text-xs"></i>
                    <span>Import CSV</span>
                </button>

                <a href="<?= app_url('/invoices/create') ?>" 
                   class="px-4 py-3 rounded-xl bg-white/10 hover:bg-white/15 text-slate-200 border border-white/10 font-bold text-xs transition flex items-center space-x-2">
                    <i class="fa-solid fa-file-invoice text-blue-400 text-xs"></i>
                    <span>Create Invoice</span>
                </a>

                <a href="<?= app_url('/dashboard') ?>" 
                   class="p-3 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 transition" 
                   title="Return to Dashboard">
                    <i class="fa-solid fa-house text-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 2: CAPITAL EXPOSURE & GST COMPLIANCE RADAR
         ══════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Active Clients -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1 relative overflow-hidden">
            <div class="h-1 w-full bg-blue-600 absolute top-0 left-0"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Enterprise Accounts</span>
            <div class="text-3xl font-black text-slate-900 font-mono"><?= $clientCount ?></div>
            <span class="text-[11px] text-blue-600 font-semibold block">Active Billing Profiles</span>
        </div>

        <!-- GST Registered -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1 relative overflow-hidden">
            <div class="h-1 w-full bg-emerald-500 absolute top-0 left-0"></div>
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider">GST Registered</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-mono text-[10px] font-bold"><?= $gstPercentage ?>%</span>
            </div>
            <div class="text-3xl font-black text-emerald-600 font-mono"><?= $gstCount ?></div>
            <span class="text-[11px] text-emerald-600 font-semibold block">Validated 15-Digit GSTIN</span>
        </div>

        <!-- WhatsApp / Phone Reminders -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1 relative overflow-hidden">
            <div class="h-1 w-full bg-indigo-500 absolute top-0 left-0"></div>
            <span class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider block">Direct WhatsApp Ready</span>
            <div class="text-3xl font-black text-indigo-600 font-mono"><?= $phoneCount ?></div>
            <span class="text-[11px] text-indigo-600 font-semibold block">1-Tap Payment Links</span>
        </div>

        <!-- Lifetime Collections Realized -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1 relative overflow-hidden">
            <div class="h-1 w-full bg-purple-500 absolute top-0 left-0"></div>
            <span class="text-[10px] font-bold text-purple-700 uppercase tracking-wider block">Lifetime Client Spend</span>
            <div class="text-2xl font-black text-purple-700 font-mono"><?= format_cents($totalPaid) ?></div>
            <span class="text-[11px] text-purple-600 font-semibold block">
                <?php if ($totalDue > 0): ?>
                    <strong class="text-amber-600 font-mono"><?= format_cents($totalDue) ?></strong> Pending
                <?php else: ?>
                    All Receivables Cleared
                <?php endif; ?>
            </span>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 3: SEARCH & DUAL-MODE DIRECTORY CONTROLLER
         ══════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <!-- Live Instant Search -->
        <div class="relative flex-1 max-w-md">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="clientSearch" placeholder="Search by name, company, email, phone, city, or GSTIN..." 
                   class="w-full pl-9 pr-8 py-2.5 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 outline-none transition shadow-2xs">
            <button type="button" id="clearClientSearchBtn" onclick="clearClientSearch()" 
                    class="hidden absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1" title="Clear Search (Esc)">
                <i class="fa-solid fa-circle-xmark text-xs"></i>
            </button>
        </div>

        <div class="flex items-center space-x-3 justify-between md:justify-end">
            <span class="text-xs text-slate-500 font-medium">
                Active: <strong class="text-slate-900 font-mono" id="clientCount"><?= $clientCount ?></strong> clients
            </span>

            <!-- Dual View Switcher -->
            <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-bold">
                <button type="button" id="btnViewDossier" onclick="switchClientMode('dossier')" class="px-3 py-1.5 rounded-lg bg-white shadow-xs text-slate-900 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-address-card text-xs text-blue-600"></i>
                    <span>Dossiers</span>
                </button>
                <button type="button" id="btnViewLedger" onclick="switchClientMode('ledger')" class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-table-list text-xs"></i>
                    <span>Table</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 4: MODE A - ENTERPRISE CLIENT DOSSIER CARDS
         ══════════════════════════════════════════════════════════════════ -->
    <div id="clientDossierGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php if (empty($customers)): ?>
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                <i class="fa-solid fa-users text-4xl text-slate-300 mb-3 block"></i>
                <h4 class="text-base font-bold text-slate-800">No Client Profiles Yet</h4>
                <p class="text-xs text-slate-500 mt-1">Register your first corporate customer to start issuing automated GST tax invoices.</p>
                <div class="mt-4">
                    <button onclick="document.getElementById('addClientModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-[#0C66E4] text-white font-bold text-xs shadow-xs hover:bg-[#0055CC]">
                        + Register First Client
                    </button>
                </div>
            </div>
        <?php else: ?>
            <!-- Search Zero Results Empty State (Cards) -->
            <div id="clientSearchZeroCards" class="hidden col-span-full bg-white rounded-3xl p-10 text-center border border-slate-200 shadow-sm space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-500 mx-auto flex items-center justify-center text-xl font-bold shadow-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h4 class="text-base font-bold text-slate-900">No Clients Located</h4>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">
                    No client profile matches "<span id="clientZeroQueryCards" class="font-bold text-slate-800 font-mono"></span>". Try searching by name, company, email, phone, city, or GSTIN.
                </p>
                <div class="pt-2">
                    <button type="button" onclick="clearClientSearch()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition inline-flex items-center space-x-1.5">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        <span>Reset Search</span>
                    </button>
                </div>
            </div>

            <?php foreach ($customers as $c): 
                $hasGstin = !empty($c['gstin']);
                $cleanPhone = preg_replace('/[^0-9]/', '', $c['phone'] ?? '');
                $paidCents = (int)($c['paid_cents'] ?? 0);
                $dueCents = (int)($c['outstanding_cents'] ?? 0);
                $initial = strtoupper(substr($c['name'] ?? 'C', 0, 1));
                $clientSearchKeywords = strtolower(implode(' ', array_filter([
                    $c['name'] ?? '',
                    $c['company_name'] ?? '',
                    $c['email'] ?? '',
                    $c['phone'] ?? '',
                    $c['gstin'] ?? '',
                    $c['city'] ?? '',
                    $c['state'] ?? '',
                    $c['address'] ?? '',
                    $hasGstin ? 'gst gstin verified' : 'unregistered individual',
                ])));
            ?>
                <div class="client-card bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col justify-between relative group"
                     data-search="<?= e($clientSearchKeywords) ?>">
                    <div class="space-y-4">
                        <!-- Card Top: Avatar & Action Pill -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center font-black text-base shadow-md shadow-blue-500/20 shrink-0">
                                    <?= $initial ?>
                                </div>
                                <div class="truncate">
                                    <h3 class="font-black text-slate-900 text-sm truncate"><?= e($c['name']) ?></h3>
                                    <?php if (!empty($c['company_name'])): ?>
                                        <span class="text-[11px] font-bold text-blue-600 block truncate flex items-center gap-1 mt-0.5">
                                            <i class="fa-regular fa-building text-[10px]"></i>
                                            <span><?= e($c['company_name']) ?></span>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-[11px] text-slate-400 block italic">Individual Account</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <a href="<?= app_url('/invoices/create?customer_id=' . $c['id']) ?>" 
                               class="px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-extrabold text-[11px] border border-blue-200/80 transition flex items-center space-x-1 shrink-0" 
                               title="Generate New Invoice">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                                <span>Bill</span>
                            </a>
                        </div>

                        <!-- GSTIN Identification Capsule -->
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                                <i class="fa-solid fa-shield-halved text-[10px] text-slate-400"></i>
                                <span>Tax Identity</span>
                            </span>
                            <?php if ($hasGstin): ?>
                                <button type="button" onclick="copyText('<?= e($c['gstin']) ?>', 'GSTIN')" class="font-mono font-bold text-blue-700 hover:underline flex items-center gap-1" title="Click to copy GSTIN">
                                    <span><?= e($c['gstin']) ?></span>
                                    <i class="fa-regular fa-copy text-[10px] text-slate-400"></i>
                                </button>
                            <?php else: ?>
                                <span class="text-slate-400 text-[11px] italic">Unregistered / Consumer</span>
                            <?php endif; ?>
                        </div>

                        <!-- Contact Channels -->
                        <div class="space-y-1.5 text-xs text-slate-600">
                            <div class="flex items-center space-x-2 truncate">
                                <i class="fa-regular fa-envelope text-slate-400 text-xs shrink-0 w-4"></i>
                                <span class="truncate font-medium"><?= e($c['email']) ?></span>
                            </div>
                            <?php if (!empty($c['phone'])): ?>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2 font-mono text-slate-700">
                                        <i class="fa-solid fa-phone text-slate-400 text-xs shrink-0 w-4"></i>
                                        <span><?= e($c['phone']) ?></span>
                                    </div>
                                    <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" 
                                       class="px-2 py-0.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[10px] flex items-center gap-1 border border-emerald-200 transition">
                                        <i class="fa-brands fa-whatsapp text-xs"></i>
                                        <span>WhatsApp</span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Capital Exposure Ledger -->
                        <div class="pt-3 border-t border-slate-100 grid grid-cols-2 gap-3 text-xs">
                            <div class="p-2.5 rounded-xl bg-emerald-50/60 border border-emerald-100">
                                <span class="text-[9px] font-bold text-emerald-800 uppercase tracking-wider block">Realized Paid</span>
                                <div class="font-mono font-black text-emerald-700 text-sm"><?= format_cents($paidCents) ?></div>
                                <span class="text-[10px] text-emerald-600"><?= (int)$c['total_invoices'] ?> Invoices Issued</span>
                            </div>

                            <div class="p-2.5 rounded-xl <?= $dueCents > 0 ? 'bg-amber-50/70 border-amber-200' : 'bg-slate-50 border-slate-100' ?> border">
                                <span class="text-[9px] font-bold <?= $dueCents > 0 ? 'text-amber-800' : 'text-slate-400' ?> uppercase tracking-wider block">Pending Due</span>
                                <div class="font-mono font-black <?= $dueCents > 0 ? 'text-amber-700' : 'text-slate-400' ?> text-sm"><?= format_cents($dueCents) ?></div>
                                <span class="text-[10px] <?= $dueCents > 0 ? 'text-amber-600 font-bold' : 'text-slate-400' ?>">
                                    <?= $dueCents > 0 ? 'Settlement Awaited' : 'Zero Debt' ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400 font-mono">
                            <?= !empty($c['city']) ? e($c['city']) . ', ' . e($c['state'] ?? '') : 'India' ?>
                        </span>

                        <div class="flex items-center space-x-1.5">
                            <?php
                            $statementUrl = app_url('/statement/' . $c['id']);
                            $tenantName = $tenant['name'] ?? 'SaaSify';
                            $statementWaText = urlencode("Hello " . ($c['name'] ?? 'Client') . ",\nHere is your official account ledger statement from " . $tenantName . ".\nTotal Invoiced: " . format_cents($paidCents + $dueCents) . "\nPending Due: " . format_cents($dueCents) . "\nView full ledger statement: " . $statementUrl);
                            $statementWaLink = !empty($cleanPhone) ? "https://wa.me/{$cleanPhone}?text={$statementWaText}" : "https://api.whatsapp.com/send?text={$statementWaText}";
                            ?>
                            <a href="<?= $statementWaLink ?>" target="_blank"
                               class="p-2 rounded-xl text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 transition" title="Share Ledger Statement on WhatsApp">
                                <i class="fa-brands fa-whatsapp text-xs"></i>
                            </a>
                            <a href="<?= $statementUrl ?>" target="_blank"
                               class="p-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition" title="View Public Statement &amp; Ledger">
                                <i class="fa-solid fa-file-invoice text-xs"></i>
                            </a>
                            <button type="button" 
                                    onclick="openEditModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                    class="p-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition" title="Edit Client Profile">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <form action="<?= app_url('/customers/delete/' . $c['id']) ?>" method="POST" onsubmit="return confirm('Are you sure you want to remove this client?');" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete Client">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 4: MODE B - HIGH-DENSITY ENTERPRISE DIRECTORY TABLE
         ══════════════════════════════════════════════════════════════════ -->
    <div id="clientLedgerTable" class="hidden bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="clientTable">
                <thead class="bg-slate-50/90 text-slate-600 text-[11px] uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Client / Corporate Name</th>
                        <th class="px-6 py-4">Contact Coordinates</th>
                        <th class="px-6 py-4">GSTIN / Identification</th>
                        <th class="px-6 py-4">Collections / Ledger</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <!-- Search Zero Results Row (Table) -->
                    <tr id="clientSearchZeroTable" class="hidden">
                        <td colspan="5" class="px-6 py-12 text-center bg-white">
                            <div class="space-y-2 max-w-xs mx-auto">
                                <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300 block"></i>
                                <span class="text-xs font-bold text-slate-800 block">No Clients Located</span>
                                <p class="text-[11px] text-slate-400 block">No profiles match "<span id="clientZeroQueryTable" class="font-bold font-mono text-slate-600"></span>".</p>
                                <button type="button" onclick="clearClientSearch()" class="mt-2 px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition inline-flex items-center space-x-1">
                                    <i class="fa-solid fa-rotate-left text-xs"></i>
                                    <span>Clear Filter</span>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <?php foreach ($customers as $c): 
                        $hasGstin = !empty($c['gstin']);
                        $cleanPhone = preg_replace('/[^0-9]/', '', $c['phone'] ?? '');
                        $paidCents = (int)($c['paid_cents'] ?? 0);
                        $dueCents = (int)($c['outstanding_cents'] ?? 0);
                        $clientSearchKeywords = strtolower(implode(' ', array_filter([
                            $c['name'] ?? '',
                            $c['company_name'] ?? '',
                            $c['email'] ?? '',
                            $c['phone'] ?? '',
                            $c['gstin'] ?? '',
                            $c['city'] ?? '',
                            $c['state'] ?? '',
                            $c['address'] ?? '',
                            $hasGstin ? 'gst gstin verified' : 'unregistered individual',
                        ])));
                    ?>
                        <tr class="client-row hover:bg-blue-50/20 transition" data-search="<?= e($clientSearchKeywords) ?>">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-sm"><?= e($c['name']) ?></div>
                                <?php if (!empty($c['company_name'])): ?>
                                    <div class="text-[11px] font-semibold text-blue-600 flex items-center gap-1 mt-0.5">
                                        <i class="fa-regular fa-building text-[10px]"></i>
                                        <span><?= e($c['company_name']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900"><?= e($c['email']) ?></div>
                                <?php if (!empty($c['phone'])): ?>
                                    <div class="text-[11px] text-slate-500 font-mono mt-0.5 flex items-center gap-1">
                                        <i class="fa-solid fa-phone text-[9px] text-slate-400"></i>
                                        <span><?= e($c['phone']) ?></span>
                                        <a href="https://wa.me/<?= $cleanPhone ?>" target="_blank" class="text-emerald-600 hover:text-emerald-700 ml-1" title="WhatsApp Chat">
                                            <i class="fa-brands fa-whatsapp text-xs"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($hasGstin): ?>
                                    <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100 border border-slate-200 font-mono font-bold text-[11px] text-slate-800">
                                        <?= e($c['gstin']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-400 text-xs italic">Unregistered / Consumer</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-900 font-bold"><?= (int)$c['total_invoices'] ?> Invoices</div>
                                <div class="text-[11px] text-slate-500">
                                    Paid: <strong class="text-emerald-600 font-mono"><?= format_cents($paidCents) ?></strong>
                                    <?php if ($dueCents > 0): ?>
                                        &bull; Due: <strong class="text-amber-600 font-mono"><?= format_cents($dueCents) ?></strong>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <?php
                                    $statementUrl = app_url('/statement/' . $c['id']);
                                    $tenantName = $tenant['name'] ?? 'SaaSify';
                                    $statementWaText = urlencode("Hello " . ($c['name'] ?? 'Client') . ",\nHere is your official account ledger statement from " . $tenantName . ".\nTotal Invoiced: " . format_cents($paidCents + $dueCents) . "\nPending Due: " . format_cents($dueCents) . "\nView full ledger statement: " . $statementUrl);
                                    $statementWaLink = !empty($cleanPhone) ? "https://wa.me/{$cleanPhone}?text={$statementWaText}" : "https://api.whatsapp.com/send?text={$statementWaText}";
                                    ?>
                                    <a href="<?= $statementWaLink ?>" target="_blank"
                                       class="p-1.5 text-slate-500 hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition" title="Share Ledger Statement on WhatsApp">
                                        <i class="fa-brands fa-whatsapp text-xs"></i>
                                    </a>
                                    <a href="<?= $statementUrl ?>" target="_blank"
                                       class="p-1.5 text-slate-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition" title="View Public Statement & Ledger">
                                        <i class="fa-solid fa-file-invoice text-xs"></i>
                                    </a>
                                    <a href="<?= app_url('/invoices/create?customer_id=' . $c['id']) ?>" 
                                       class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs border border-blue-200 transition" title="Bill Client">
                                        <i class="fa-solid fa-plus text-[10px]"></i>
                                        <span>Bill</span>
                                    </a>
                                    <button type="button" 
                                            onclick="openEditModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                            class="p-1.5 text-slate-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="<?= app_url('/customers/delete/' . $c['id']) ?>" method="POST" onsubmit="return confirm('Are you sure you want to remove this client?');" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL 1: ADD NEW CLIENT (CYBER-PRECISION ARCHITECTURE)
     ══════════════════════════════════════════════════════════════════ -->
<div id="addClientModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-xl w-full p-6 sm:p-8 my-auto relative text-slate-800">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg border border-blue-100">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900">Register Enterprise Client</h3>
                    <p class="text-xs text-slate-500">Configure corporate identity &amp; 15-digit GST credentials.</p>
                </div>
            </div>
            <button onclick="document.getElementById('addClientModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="<?= app_url('/customers') ?>" method="POST" class="mt-6 space-y-4">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Client / Contact Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Rajesh Sharma" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Work Email Address *</label>
                    <input type="email" name="email" required placeholder="e.g. billing@client.com" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Company / Business Name</label>
                    <input type="text" name="company_name" placeholder="e.g. Acme Tech Pvt Ltd" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mobile / WhatsApp Number</label>
                    <input type="text" name="phone" placeholder="e.g. +91 98200 12345" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">GSTIN / Tax ID (15 Characters)</label>
                <input type="text" name="gstin" placeholder="e.g. 24AAACR5055K1Z4" maxlength="15" 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-mono font-bold uppercase placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Corporate Billing Address</label>
                <textarea name="address" rows="2" placeholder="Street, Building, Corporate Office..." 
                          class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">City</label>
                    <input type="text" name="city" placeholder="Ahmedabad" 
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">State</label>
                    <input type="text" name="state" placeholder="Gujarat" 
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Pincode</label>
                    <input type="text" name="pincode" placeholder="380015" 
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-mono font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('addClientModal').classList.add('hidden')" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs shadow-md shadow-blue-500/25 transition">
                    <i class="fa-solid fa-check mr-1.5"></i> Register Client
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     MODAL 2: EDIT CLIENT PROFILE
     ══════════════════════════════════════════════════════════════════ -->
<div id="editClientModal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-xl w-full p-6 sm:p-8 my-auto relative text-slate-800">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg border border-indigo-100">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900">Edit Client Credentials</h3>
                    <p class="text-xs text-slate-500">Update corporate tax &amp; billing address details</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('editClientModal').classList.add('hidden')" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="editClientForm" action="" method="POST" class="space-y-4 text-xs">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Client / Contact Name *</label>
                    <input type="text" name="name" id="edit_name" required 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Work Email Address *</label>
                    <input type="email" name="email" id="edit_email" required 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Company / Business Name</label>
                    <input type="text" name="company_name" id="edit_company_name" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mobile / WhatsApp Number</label>
                    <input type="text" name="phone" id="edit_phone" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">GSTIN / Tax ID</label>
                <input type="text" name="gstin" id="edit_gstin" maxlength="15" 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-mono font-bold uppercase focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Billing Address</label>
                <textarea name="address" id="edit_address" rows="2" 
                          class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">City</label>
                    <input type="text" name="city" id="edit_city" 
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">State</label>
                    <input type="text" name="state" id="edit_state" 
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Pincode</label>
                    <input type="text" name="pincode" id="edit_pincode" 
                           class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-mono font-medium focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('editClientModal').classList.add('hidden')" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/25 transition">
                    <i class="fa-solid fa-check mr-1.5"></i> Update Client Profile
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Import Clients via CSV -->
<div id="importClientsModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                    <i class="fa-solid fa-file-csv"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Bulk Client Import</h3>
                    <p class="text-xs text-slate-500">Upload CSV with corporate clients &amp; GSTIN details</p>
                </div>
            </div>
            <button onclick="document.getElementById('importClientsModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="<?= app_url('/customers/import') ?>" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            <?= csrf_field() ?>

            <!-- Sample Download Banner -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                <div class="space-y-0.5">
                    <span class="font-bold text-slate-900 block text-xs">Need the CSV Template?</span>
                    <span class="text-slate-500 text-[11px] block">Download our pre-formatted sample with headers.</span>
                </div>
                <a href="<?= app_url('/customers/sample-csv') ?>" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 border border-slate-300 font-bold text-xs text-blue-700 transition flex items-center gap-1.5 shadow-2xs whitespace-nowrap">
                    <i class="fa-solid fa-download text-xs"></i>
                    <span>Template</span>
                </a>
            </div>

            <!-- File Upload Input -->
            <div class="space-y-1.5">
                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[11px]">Choose CSV File *</label>
                <div class="p-4 rounded-2xl border-2 border-dashed border-slate-300 hover:border-emerald-500 bg-slate-50/50 text-center space-y-2 transition cursor-pointer">
                    <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-400 block"></i>
                    <input type="file" name="csv_file" accept=".csv" required 
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
                    <p class="text-[11px] text-slate-400">Supported columns: Name, Company, Email, Phone, GSTIN, Address, City, State, Pincode</p>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('importClientsModal').classList.add('hidden')" 
                        class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold transition shadow-xs flex items-center space-x-1.5">
                    <i class="fa-solid fa-upload"></i>
                    <span>Start Bulk Import</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Copy Toast Notification -->
<div id="copyToast" class="fixed bottom-6 right-6 z-50 px-4 py-3 rounded-xl bg-slate-900 text-white text-xs font-bold shadow-2xl flex items-center space-x-2 transition-all duration-300 opacity-0 pointer-events-none translate-y-3">
    <i class="fa-solid fa-circle-check text-emerald-400"></i>
    <span id="toastMsg">Copied to clipboard!</span>
</div>

<script>
function copyText(text, label) {
    navigator.clipboard.writeText(text).then(() => {
        const toast = document.getElementById('copyToast');
        document.getElementById('toastMsg').innerText = `${label} copied to clipboard!`;
        toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-3');
        toast.classList.add('opacity-100', 'translate-y-0');
        setTimeout(() => {
            toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-3');
            toast.classList.remove('opacity-100', 'translate-y-0');
        }, 2200);
    });
}

function openEditModal(c) {
    document.getElementById('edit_name').value = c.name || '';
    document.getElementById('edit_email').value = c.email || '';
    document.getElementById('edit_company_name').value = c.company_name || '';
    document.getElementById('edit_phone').value = c.phone || '';
    document.getElementById('edit_gstin').value = c.gstin || '';
    document.getElementById('edit_address').value = c.address || '';
    document.getElementById('edit_city').value = c.city || '';
    document.getElementById('edit_state').value = c.state || '';
    document.getElementById('edit_pincode').value = c.pincode || '';
    
    document.getElementById('editClientForm').action = '<?= app_url('/customers/update/') ?>' + c.id;
    document.getElementById('editClientModal').classList.remove('hidden');
}

function switchClientMode(mode) {
    const cards = document.getElementById('clientDossierGrid');
    const table = document.getElementById('clientLedgerTable');
    const btnDossier = document.getElementById('btnViewDossier');
    const btnLedger = document.getElementById('btnViewLedger');

    if (mode === 'ledger') {
        cards.classList.add('hidden');
        table.classList.remove('hidden');
        btnLedger.className = 'px-3 py-1.5 rounded-lg bg-white shadow-xs text-slate-900 transition flex items-center space-x-1.5 font-bold';
        btnDossier.className = 'px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition flex items-center space-x-1.5';
    } else {
        cards.classList.remove('hidden');
        table.classList.add('hidden');
        btnDossier.className = 'px-3 py-1.5 rounded-lg bg-white shadow-xs text-slate-900 transition flex items-center space-x-1.5 font-bold';
        btnLedger.className = 'px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition flex items-center space-x-1.5';
    }
}

function clearClientSearch() {
    const input = document.getElementById('clientSearch');
    if (!input) return;
    input.value = '';
    applyClientFilter('');
    input.focus();
}

function applyClientFilter(rawQuery) {
    const query = (rawQuery || '').toLowerCase().trim();
    const clearBtn = document.getElementById('clearClientSearchBtn');
    const zeroCards = document.getElementById('clientSearchZeroCards');
    const zeroTable = document.getElementById('clientSearchZeroTable');
    const zeroQueryCards = document.getElementById('clientZeroQueryCards');
    const zeroQueryTable = document.getElementById('clientZeroQueryTable');
    const countDisplay = document.getElementById('clientCount');

    if (clearBtn) {
        if (query.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }

    const words = query.split(/\s+/).filter(Boolean);
    let visibleCards = 0;
    let visibleRows = 0;

    // Filter Dossier Cards
    document.querySelectorAll('.client-card').forEach(card => {
        const text = card.getAttribute('data-search') || '';
        const isMatch = words.length === 0 || words.every(w => text.includes(w));
        card.style.display = isMatch ? '' : 'none';
        if (isMatch) visibleCards++;
    });

    // Filter Ledger Table
    document.querySelectorAll('.client-row').forEach(row => {
        const text = row.getAttribute('data-search') || '';
        const isMatch = words.length === 0 || words.every(w => text.includes(w));
        row.style.display = isMatch ? '' : 'none';
        if (isMatch) visibleRows++;
    });

    if (countDisplay) {
        countDisplay.innerText = visibleCards;
    }

    // Zero match empty states
    if (words.length > 0 && visibleCards === 0) {
        if (zeroCards) {
            zeroCards.classList.remove('hidden');
            if (zeroQueryCards) zeroQueryCards.innerText = query;
        }
    } else {
        if (zeroCards) zeroCards.classList.add('hidden');
    }

    if (words.length > 0 && visibleRows === 0) {
        if (zeroTable) {
            zeroTable.classList.remove('hidden');
            if (zeroQueryTable) zeroQueryTable.innerText = query;
        }
    } else {
        if (zeroTable) zeroTable.classList.add('hidden');
    }
}

document.getElementById('clientSearch')?.addEventListener('input', function(e) {
    applyClientFilter(e.target.value);
});

document.getElementById('clientSearch')?.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        clearClientSearch();
    }
});
</script>
