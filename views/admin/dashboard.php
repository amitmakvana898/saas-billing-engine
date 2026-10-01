<?php
// views/admin/dashboard.php

// Safely normalize variables from controller
$mrrCents = $mrrCents ?? 0;
$totalRevenueCents = $totalRevenueCents ?? 0;
$totalTenants = $totalTenants ?? count($tenants ?? []);
$activeSubs = $activeSubs ?? 0;
$trialingSubs = $trialingSubs ?? 0;
$tenants = $tenants ?? [];
$webhooks = $webhooks ?? [];

function admin_badge(string $status): string {
    $status = strtolower($status);
    $map = [
        'active'    => 'bg-emerald-50 text-emerald-800 border border-emerald-200',
        'trial'     => 'bg-amber-50 text-amber-800 border border-amber-200',
        'trialing'  => 'bg-amber-50 text-amber-800 border border-amber-200',
        'suspended' => 'bg-rose-50 text-rose-800 border border-rose-200',
        'canceled'  => 'bg-slate-100 text-slate-700 border border-slate-200',
    ];
    $cls = $map[$status] ?? 'bg-slate-100 text-slate-700 border border-slate-200';
    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider ' . $cls . '">' . e($status) . '</span>';
}

function admin_wh_pill(string $status): string {
    $status = strtolower($status);
    if ($status === 'processed' || $status === 'delivered') {
        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>' . e($status) . '</span>';
    } elseif ($status === 'failed') {
        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-800 border border-rose-200"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>' . e($status) . '</span>';
    } else {
        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>' . e($status) . '</span>';
    }
}
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Platform Master Overview</h1>
        <p class="text-slate-500 text-xs sm:text-sm mt-1">Real-time revenue telemetry, organization directory, and webhook idempotency status.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= app_url('admin/tenants') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 border border-slate-200 shadow-xs transition">
            <i class="fa-solid fa-users text-slate-500 text-xs"></i>
            <span>Manage Tenants</span>
        </a>
        <a href="<?= app_url('admin/webhooks') ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-xs font-semibold text-rose-700 border border-rose-200 shadow-xs transition">
            <i class="fa-solid fa-bolt text-rose-500 text-xs"></i>
            <span>Webhook Logs</span>
        </a>
    </div>
</div>

<!-- 4 Core Metric Cards with Colored Top Borders -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <!-- Card 1: Platform Global MRR -->
    <div class="bg-white border border-slate-200 border-t-4 border-t-rose-500 rounded-2xl p-6 relative overflow-hidden shadow-xs">
        <div class="absolute top-4 right-4 text-rose-500/10 text-4xl">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Global Platform MRR</p>
        <p class="text-3xl font-black text-slate-900 font-mono"><?= format_cents($mrrCents) ?></p>
        <div class="flex items-center gap-1.5 mt-3 text-[11px] text-emerald-700 font-semibold">
            <i class="fa-solid fa-arrow-trend-up"></i>
            <span>Active recurring billing runs</span>
        </div>
    </div>

    <!-- Card 2: Total Settled Revenue -->
    <div class="bg-white border border-slate-200 border-t-4 border-t-emerald-500 rounded-2xl p-6 relative overflow-hidden shadow-xs">
        <div class="absolute top-4 right-4 text-emerald-500/10 text-4xl">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Total Settled Invoices</p>
        <p class="text-3xl font-black text-slate-900 font-mono"><?= format_cents($totalRevenueCents) ?></p>
        <div class="flex items-center gap-1.5 mt-3 text-[11px] text-slate-500 font-medium">
            <i class="fa-solid fa-shield-check text-emerald-600"></i>
            <span>18% GST Compliant B2B tax</span>
        </div>
    </div>

    <!-- Card 3: Total Tenants -->
    <div class="bg-white border border-slate-200 border-t-4 border-t-[#0C66E4] rounded-2xl p-6 relative overflow-hidden shadow-xs">
        <div class="absolute top-4 right-4 text-blue-500/10 text-4xl">
            <i class="fa-solid fa-building"></i>
        </div>
        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Total Organizations</p>
        <p class="text-3xl font-black text-slate-900 font-mono"><?= (int)$totalTenants ?></p>
        <div class="flex items-center gap-1.5 mt-3 text-[11px] text-[#0C66E4] font-semibold">
            <i class="fa-solid fa-database"></i>
            <span>Isolated row-level tenant scopes</span>
        </div>
    </div>

    <!-- Card 4: Active Subscriptions -->
    <div class="bg-white border border-slate-200 border-t-4 border-t-amber-500 rounded-2xl p-6 relative overflow-hidden shadow-xs">
        <div class="absolute top-4 right-4 text-amber-500/10 text-4xl">
            <i class="fa-solid fa-arrows-spin"></i>
        </div>
        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-2">Active / Trial Subs</p>
        <p class="text-3xl font-black text-slate-900 font-mono">
            <?= (int)$activeSubs ?> <span class="text-sm font-normal text-slate-400">/ <?= (int)$trialingSubs ?> trial</span>
        </p>
        <div class="flex items-center gap-1.5 mt-3 text-[11px] text-amber-700 font-semibold">
            <i class="fa-solid fa-clock"></i>
            <span>Automated 14-day expiry engine</span>
        </div>
    </div>

</div>

<!-- Split Section: Recent Organizations & Webhook Feed -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    <!-- Recent Organizations (7/12 cols) -->
    <div class="lg:col-span-7 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#0C66E4] flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Recent Organizations</h2>
                    <p class="text-xs text-slate-500">Registered SaaS company workspaces</p>
                </div>
            </div>
            <a href="<?= app_url('admin/tenants') ?>" class="text-xs font-bold text-rose-600 hover:text-rose-700">
                View all &rarr;
            </a>
        </div>

        <?php if (empty($tenants)): ?>
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-building text-3xl mb-3 text-slate-300"></i>
                <p class="text-xs font-semibold">No organizations registered yet.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-500 uppercase font-bold text-[11px] bg-slate-50">
                            <th class="px-5 py-3">Company Workspace</th>
                            <th class="px-4 py-3">Plan Tier</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($tenants as $t): ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-900"><?= e($t['name'] ?? $t['company_name'] ?? 'Unnamed') ?></div>
                                    <div class="font-mono text-[10px] text-slate-400"><?= e($t['subdomain']) ?>.saasify.app</div>
                                </td>
                                <td class="px-4 py-3.5 text-slate-700">
                                    <span class="font-medium"><?= e($t['plan_name'] ?? 'Starter') ?></span>
                                    <span class="block text-[10px] text-slate-400"><?= e($t['user_count'] ?? 1) ?> seats</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <?= admin_badge($t['status'] ?? 'trial') ?>
                                </td>
                                <td class="px-4 py-3.5 text-right font-mono font-bold text-slate-900">
                                    <?= format_cents((int)($t['total_revenue_cents'] ?? 0)) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Webhook Events Feed (5/12 cols) -->
    <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Live Webhook Feed</h2>
                    <p class="text-xs text-slate-500">Idempotency &amp; gateway events</p>
                </div>
            </div>
            <a href="<?= app_url('admin/webhooks') ?>" class="text-xs font-bold text-rose-600 hover:text-rose-700">
                Audit log &rarr;
            </a>
        </div>

        <?php if (empty($webhooks)): ?>
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-bolt text-3xl mb-3 text-slate-300"></i>
                <p class="text-xs font-semibold">No webhook events recorded yet.</p>
                <p class="text-[11px] text-slate-400 mt-1">Use Simulator in client portal to test.</p>
            </div>
        <?php else: ?>
            <div class="divide-y divide-slate-100">
                <?php foreach ($webhooks as $w): ?>
                    <div class="p-4 hover:bg-slate-50/70 transition text-xs">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-mono font-bold text-[#0C66E4] truncate max-w-[200px]">
                                <?= e($w['event_type']) ?>
                            </span>
                            <?= admin_wh_pill($w['status'] ?? 'processed') ?>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 font-mono">
                            <span>ID: <?= e(substr($w['external_event_id'] ?? $w['id'], 0, 18)) ?>...</span>
                            <span><?= date('M d, H:i', strtotime($w['created_at'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>