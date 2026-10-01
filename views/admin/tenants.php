<?php
// views/admin/tenants.php
$tenants = $tenants ?? [];
$flash_msg = function_exists('flash') ? (flash('success') ?? flash('error')) : null;

function tenant_badge(string $status): string {
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
?>

<!-- Flash Message -->
<?php if (!empty($flash_msg)): ?>
<div class="mb-6 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-5 py-4 text-sm shadow-xs font-medium">
    <i class="fa-solid fa-circle-check mt-0.5 text-base text-emerald-600"></i>
    <span><?= e($flash_msg) ?></span>
</div>
<?php endif; ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Organizations &amp; Tenants Directory</h1>
            <span class="bg-rose-50 text-rose-700 border border-rose-200 text-xs font-mono font-bold px-2.5 py-0.5 rounded-full">
                <?= count($tenants) ?> Total
            </span>
        </div>
        <p class="text-slate-500 text-xs sm:text-sm mt-1">Manage tenant lifecycles, inspect subscription status, and enforce platform governance.</p>
    </div>
</div>

<!-- Instant Search Filter -->
<div class="mb-6">
    <div class="relative max-w-md">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
        <input
            type="text"
            id="tenant-search"
            placeholder="Search by company name, subdomain, or plan..."
            class="w-full bg-white border border-slate-300 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#0C66E4] focus:ring-2 focus:ring-[#0C66E4]/20 transition shadow-xs"
        >
    </div>
</div>

<!-- Tenants Table -->
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <?php if (empty($tenants)): ?>
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <i class="fa-solid fa-building text-slate-300 text-5xl mb-4"></i>
            <p class="text-slate-700 font-semibold mb-1">No organizations found</p>
            <p class="text-slate-400 text-xs">New organizations will appear here as soon as they register.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs" id="tenant-table">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 uppercase font-bold text-[11px] bg-slate-50">
                        <th class="px-6 py-3.5">Company Workspace</th>
                        <th class="px-5 py-3.5">Subdomain</th>
                        <th class="px-5 py-3.5">Subscription Plan</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Seats</th>
                        <th class="px-5 py-3.5">Total Revenue</th>
                        <th class="px-5 py-3.5">Created</th>
                        <th class="px-6 py-3.5 text-right">Emergency Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($tenants as $t): ?>
                        <?php
                        $companyName = $t['name'] ?? $t['company_name'] ?? 'Unnamed Organization';
                        $subdomain = $t['subdomain'] ?? '';
                        $plan = $t['plan_name'] ?? 'Starter';
                        $status = $t['status'] ?? 'trial';
                        ?>
                        <tr class="hover:bg-slate-50/70 transition-colors"
                            data-search="<?= e(strtolower($companyName . ' ' . $subdomain . ' ' . $plan . ' ' . $status)) ?>">
                            
                            <!-- Company -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-sm"><?= e($companyName) ?></div>
                                <div class="font-mono text-[10px] text-slate-400 mt-0.5">ID: <?= e(substr($t['id'], 0, 14)) ?>...</div>
                            </td>

                            <!-- Subdomain -->
                            <td class="px-5 py-4 font-mono text-[#0C66E4] text-xs font-bold">
                                <?= e($subdomain) ?>.saasify.app
                            </td>

                            <!-- Plan -->
                            <td class="px-5 py-4 text-slate-700">
                                <span class="font-bold text-slate-900"><?= e($plan) ?></span>
                                <span class="block text-[10px] text-slate-400 capitalize"><?= e($t['subscription_status'] ?? 'active') ?></span>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4">
                                <?= tenant_badge($status) ?>
                            </td>

                            <!-- Seats / Users -->
                            <td class="px-5 py-4 font-mono text-slate-700 font-semibold">
                                <?= (int)($t['user_count'] ?? 1) ?> users
                            </td>

                            <!-- Revenue -->
                            <td class="px-5 py-4 font-mono font-bold text-emerald-700">
                                <?= format_cents((int)($t['total_revenue_cents'] ?? 0)) ?>
                            </td>

                            <!-- Joined Date -->
                            <td class="px-5 py-4 text-slate-500 text-[11px]">
                                <?= !empty($t['created_at']) ? date('M j, Y', strtotime($t['created_at'])) : 'N/A' ?>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <?php if ($status === 'suspended'): ?>
                                    <form method="POST" action="<?= app_url('admin/tenants/status') ?>" class="inline-block" onsubmit="return confirm('Reactivate access for <?= e($companyName) ?>?')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="tenant_id" value="<?= e($t['id']) ?>">
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold transition shadow-xs">
                                            <i class="fa-solid fa-circle-check text-[10px] text-emerald-600"></i>
                                            <span>Reactivate</span>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="<?= app_url('admin/tenants/status') ?>" class="inline-block" onsubmit="return confirm('Suspend <?= e($companyName) ?> immediately? Their users will be locked out.')">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="tenant_id" value="<?= e($t['id']) ?>">
                                        <input type="hidden" name="status" value="suspended">
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-800 border border-rose-200 text-xs font-bold transition shadow-xs">
                                            <i class="fa-solid fa-ban text-[10px] text-rose-600"></i>
                                            <span>Suspend</span>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- No search results row -->
        <div id="no-search-results" class="hidden flex-col items-center justify-center py-12 text-center border-t border-slate-100">
            <i class="fa-solid fa-filter text-slate-300 text-3xl mb-2"></i>
            <p class="text-slate-500 text-xs font-semibold">No organizations match your search filter</p>
        </div>
    <?php endif; ?>
</div>

<script>
(function () {
    var searchInput = document.getElementById('tenant-search');
    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();
        var rows = document.querySelectorAll('#tenant-table tbody tr');
        var matched = 0;

        rows.forEach(function (row) {
            var searchStr = (row.getAttribute('data-search') || '').toLowerCase();
            var isMatch = q === '' || searchStr.indexOf(q) !== -1;
            row.style.display = isMatch ? '' : 'none';
            if (isMatch) matched++;
        });

        var noRes = document.getElementById('no-search-results');
        if (noRes) {
            noRes.style.display = (matched === 0 && q !== '') ? 'flex' : 'none';
        }
    });
})();
</script>