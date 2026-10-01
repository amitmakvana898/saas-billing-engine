<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Suspended · SaaSify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 min-h-screen flex items-center justify-center p-4 selection:bg-rose-500 selection:text-white antialiased">
    <div class="max-w-lg w-full bg-white border border-slate-200 rounded-2xl p-8 sm:p-10 shadow-xs text-center">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4 shadow-xs">
            <i class="fa-solid fa-ban"></i>
        </div>

        <span class="inline-block px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-rose-50 text-rose-800 border border-rose-200 mb-3">
            Account Inactive / On Hold
        </span>

        <h1 class="text-2xl font-black text-slate-900 mb-2">Organization Workspace Suspended</h1>
        
        <p class="text-xs text-slate-500 mb-6 leading-relaxed font-normal">
            The workspace for <strong class="text-slate-800"><?= e($tenant['name'] ?? 'your company') ?></strong> has been suspended due to overdue invoice settlement or administrative action. Workspace services and team access are temporarily paused.
        </p>

        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 text-left mb-6 space-y-2.5 text-xs">
            <div class="flex justify-between items-center">
                <span class="text-slate-500">Organization:</span>
                <span class="font-bold text-slate-900"><?= e($tenant['name'] ?? '') ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500">Subdomain:</span>
                <span class="font-mono font-bold text-[#0C66E4]"><?= e($tenant['subdomain'] ?? '') ?>.saasify.app</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-slate-500">Status:</span>
                <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 border border-rose-200 text-[10px] font-bold uppercase">Suspended</span>
            </div>
        </div>

        <div class="space-y-3">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="<?= app_url('/invoices') ?>" class="w-full sm:w-auto py-2.5 px-4 rounded-xl bg-[#0C66E4] hover:bg-[#0055CC] text-white font-bold text-xs shadow-xs transition flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-file-invoice text-xs"></i>
                    <span>View Invoices &amp; Settle</span>
                </a>
                <a href="<?= app_url('/plans') ?>" class="w-full sm:w-auto py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-bolt text-xs"></i>
                    <span>Upgrade Plan</span>
                </a>
            </div>
            <div class="pt-2">
                <a href="<?= app_url('/logout') ?>" class="inline-flex items-center space-x-1.5 text-xs text-slate-400 hover:text-slate-700 transition">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                    <span>Sign out of this account</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
