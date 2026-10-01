<?php
// views/layouts/admin.php
$uri = $_SERVER['REQUEST_URI'] ?? '';
$isDashboard = strpos($uri, 'admin/dashboard') !== false;
$isTenants   = strpos($uri, 'admin/tenants') !== false;
$isWebhooks  = strpos($uri, 'admin/webhooks') !== false;
$isProfile   = strpos($uri, 'admin/profile') !== false;
$adminSession = \App\Core\Session::get('super_admin');
$adminEmail = $adminSession['email'] ?? 'superadmin@saasify.app';
$adminName = $adminSession['name'] ?? 'Super Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title ?? 'Super Admin Console - SaaSify Operations') ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script>
tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'sans-serif'],
                mono: ['JetBrains Mono', 'monospace'],
            }
        }
    }
};
</script>
<style>
body { font-family: 'Plus Jakarta Sans', sans-serif; }
.font-mono { font-family: 'JetBrains Mono', monospace; }
</style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 min-h-screen flex flex-col antialiased selection:bg-rose-500 selection:text-white">

<!-- HEADER - Deep Executive Operations Navy -->
<header class="bg-[#0B1A30] border-b border-slate-800 sticky top-0 z-50 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
        <!-- Brand -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="<?= app_url('admin/dashboard') ?>" class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-rose-600 flex items-center justify-center text-white shadow-sm">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                </div>
                <span class="text-xl font-black tracking-tight text-white">SaaSify</span>
            </a>
            <span class="bg-rose-500/20 text-rose-300 border border-rose-500/30 text-[10px] font-bold uppercase tracking-widest px-2.5 py-0.5 rounded-full">
                Operations Console
            </span>
        </div>

        <!-- Telemetry Beacons -->
        <div class="hidden md:flex items-center gap-5 text-xs font-semibold">
            <span class="flex items-center gap-1.5 text-emerald-400">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Platform: 99.99% Operational
            </span>
            <span class="flex items-center gap-1.5 text-slate-400 font-mono text-[11px]">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                Cluster: Asia-South (BOM)
            </span>
        </div>

        <!-- Admin Profile & Switcher -->
        <div class="flex items-center gap-3 shrink-0">
            <!-- Jump to Tenant Workspace -->
            <a href="<?= app_url('login') ?>" class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-medium text-slate-300 hover:text-white hover:bg-white/10 border border-white/10 transition">
                <i class="fa-solid fa-building text-[10px] text-blue-400"></i>
                <span>Customer Portal</span>
            </a>

            <!-- Admin Badge (Clickable to Profile) -->
            <a href="<?= app_url('admin/profile') ?>" class="flex items-center gap-2 bg-white/10 hover:bg-white/15 border border-white/10 hover:border-rose-400/50 rounded-full px-3.5 py-1.5 shadow-xs transition group" title="Account Settings & Password">
                <div class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] font-bold">
                    <?= strtoupper(substr($adminName, 0, 1)) ?>
                </div>
                <span class="text-xs font-semibold text-slate-200 max-w-[140px] truncate"><?= e($adminEmail) ?></span>
                <i class="fa-solid fa-gear text-[10px] text-slate-400 group-hover:text-rose-300 transition"></i>
            </a>

            <!-- Logout -->
            <a href="<?= app_url('admin/logout') ?>" class="flex items-center gap-1.5 text-xs text-slate-400 hover:text-rose-300 transition font-medium px-2 py-1 rounded-lg hover:bg-white/5" title="End Session">
                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                <span class="hidden sm:inline">Logout</span>
            </a>
        </div>
    </div>
</header>

<!-- NAVIGATION TABS - Clean White Strip -->
<nav class="bg-white border-b border-slate-200 sticky top-16 z-40 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-1 sm:gap-2 overflow-x-auto">
        <a href="<?= app_url('admin/dashboard') ?>"
           class="inline-flex items-center gap-2 px-4 py-3.5 text-xs font-bold transition-all border-b-2 whitespace-nowrap <?= $isDashboard ? 'border-rose-600 text-rose-600 bg-rose-50/50' : 'border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300' ?>">
            <i class="fa-solid fa-gauge-high text-xs"></i>
            <span>Platform Overview</span>
        </a>

        <a href="<?= app_url('admin/tenants') ?>"
           class="inline-flex items-center gap-2 px-4 py-3.5 text-xs font-bold transition-all border-b-2 whitespace-nowrap <?= $isTenants ? 'border-rose-600 text-rose-600 bg-rose-50/50' : 'border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300' ?>">
            <i class="fa-solid fa-building text-xs"></i>
            <span>Organizations Directory</span>
        </a>

        <a href="<?= app_url('admin/webhooks') ?>"
           class="inline-flex items-center gap-2 px-4 py-3.5 text-xs font-bold transition-all border-b-2 whitespace-nowrap <?= $isWebhooks ? 'border-rose-600 text-rose-600 bg-rose-50/50' : 'border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300' ?>">
            <i class="fa-solid fa-bolt text-xs"></i>
            <span>Webhook Ingestion Logs</span>
        </a>

        <a href="<?= app_url('admin/profile') ?>"
           class="inline-flex items-center gap-2 px-4 py-3.5 text-xs font-bold transition-all border-b-2 whitespace-nowrap <?= $isProfile ? 'border-rose-600 text-rose-600 bg-rose-50/50' : 'border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300' ?>">
            <i class="fa-solid fa-user-shield text-xs"></i>
            <span>Admin Profile &amp; Security</span>
        </a>
    </div>
</nav>

<!-- MAIN CONTENT -->
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <?= $content ?>
</main>

<!-- FOOTER -->
<footer class="bg-white border-t border-slate-200 py-6 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
        <div class="flex items-center space-x-2">
            <span class="font-bold text-slate-700">SaaSify Operations Engine</span>
            <span>&bull;</span>
            <span>Version 2.4.0-fintech</span>
        </div>
        <div class="flex items-center space-x-4">
            <span class="flex items-center gap-1.5 text-emerald-700 font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                All Systems Operational
            </span>
            <span>&bull;</span>
            <span>&copy; <?= date('Y') ?> Sovereign Master Control</span>
        </div>
    </div>
</footer>

</body>
</html>