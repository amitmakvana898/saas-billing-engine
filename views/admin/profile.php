<?php
// views/admin/profile.php
?>
<div class="space-y-8 max-w-6xl mx-auto">

    <!-- PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-rose-600">Sovereign Control</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Admin Profile &amp; Security</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 font-normal">Manage master credentials, change admin password, and review platform security privileges.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-mono font-bold">
                <i class="fa-solid fa-lock text-rose-600 text-[10px]"></i>
                Root Access Active
            </span>
            <a href="<?= app_url('admin/dashboard') ?>" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold shadow-xs transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                Dashboard
            </a>
        </div>
    </div>

    <!-- MASTER PROFILE OVERVIEW CARD - Executive Deep Navy -->
    <div class="relative overflow-hidden rounded-2xl bg-[#0B1A30] border border-slate-800 p-6 sm:p-8 shadow-xs text-white">
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-rose-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-rose-600 text-white flex items-center justify-center text-3xl font-black shadow-md shrink-0 border border-white/20">
                    <?= strtoupper(substr($admin['name'] ?? 'A', 0, 1)) ?>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-xl font-bold text-white"><?= e($admin['name'] ?? 'Platform Super Admin') ?></h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/30">
                            Super Administrator
                        </span>
                    </div>
                    <p class="text-xs font-mono text-slate-300 mt-1"><?= e($admin['email'] ?? '') ?></p>
                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-fingerprint text-rose-400"></i>
                            ID: <span class="font-mono text-slate-200"><?= e($admin['id'] ?? 'root') ?></span>
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-shield text-emerald-400"></i>
                            MFA Status: <span class="text-emerald-400 font-semibold">Protected</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white/5 backdrop-blur-xs rounded-xl border border-white/10 p-4 flex flex-row md:flex-col justify-around gap-4 shrink-0 text-left">
                <div>
                    <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400 block">Security Tier</span>
                    <span class="text-xs font-bold text-white">Tier 0 (Root Master)</span>
                </div>
                <div>
                    <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400 block">Session Status</span>
                    <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Active &amp; Verified
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TWO-COLUMN FORMS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- LEFT COLUMN: EDIT ADMIN PROFILE (5 cols) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 pb-5 mb-5 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Profile Details</h3>
                        <p class="text-xs text-slate-500">Update your public administrative name and email.</p>
                    </div>
                </div>

                <form action="<?= app_url('admin/profile') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <div>
                        <label for="admin_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Full Name
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" id="admin_name" name="name" required
                                   value="<?= e($admin['name'] ?? '') ?>"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-medium placeholder-slate-400 focus:outline-none focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition">
                        </div>
                    </div>

                    <div>
                        <label for="admin_email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Admin Email Address
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" id="admin_email" name="email" required
                                   value="<?= e($admin['email'] ?? '') ?>"
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-mono placeholder-slate-400 focus:outline-none focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-1">Used for master authentication and emergency alerts.</p>
                    </div>

                    <div class="pt-3">
                        <button type="submit" 
                                class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Save Profile Changes</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Notice box -->
            <div class="mt-6 p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-[11px] text-slate-600 flex items-start gap-2.5">
                <i class="fa-solid fa-circle-info text-rose-500 mt-0.5 text-xs shrink-0"></i>
                <span>Changing your admin email will require you to use the new email address for future platform logins.</span>
            </div>
        </div>

        <!-- RIGHT COLUMN: CHANGE PASSWORD (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs">
            <div class="flex items-center gap-3 pb-5 mb-5 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Change Master Password</h3>
                    <p class="text-xs text-slate-500">Rotate your sovereign super admin access key.</p>
                </div>
            </div>

            <form id="passwordForm" action="<?= app_url('admin/password') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Current Master Password
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" id="current_password" name="current_password" required
                               placeholder="Enter your current password"
                               class="w-full pl-9 pr-10 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-mono placeholder-slate-400 focus:outline-none focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition">
                        <button type="button" onclick="togglePass('current_password', 'eye1')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                            <i class="fa-regular fa-eye text-xs" id="eye1"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            New Password
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                <i class="fa-solid fa-shield-halved"></i>
                            </span>
                            <input type="password" id="new_password" name="new_password" required minlength="8"
                                   placeholder="Minimum 8 characters"
                                   oninput="checkPasswordStrength()"
                                   class="w-full pl-9 pr-10 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-mono placeholder-slate-400 focus:outline-none focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition">
                            <button type="button" onclick="togglePass('new_password', 'eye2')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                                <i class="fa-regular fa-eye text-xs" id="eye2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="confirm_password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                <i class="fa-solid fa-circle-check"></i>
                            </span>
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
                                   placeholder="Re-type new password"
                                   oninput="checkPasswordMatch()"
                                   class="w-full pl-9 pr-10 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 text-xs font-mono placeholder-slate-400 focus:outline-none focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition">
                            <button type="button" onclick="togglePass('confirm_password', 'eye3')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                                <i class="fa-regular fa-eye text-xs" id="eye3"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Password Validation Checklist -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5 text-[11px]">
                    <div id="check-len" class="flex items-center gap-2 text-slate-500">
                        <i class="fa-solid fa-circle text-[8px] transition-colors" id="icon-len"></i>
                        <span>Minimum 8 characters length</span>
                    </div>
                    <div id="check-match" class="flex items-center gap-2 text-slate-500">
                        <i class="fa-solid fa-circle text-[8px] transition-colors" id="icon-match"></i>
                        <span>New password and confirmation match</span>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" id="submitPwBtn"
                            class="w-full py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-extrabold shadow-xs transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-key text-xs"></i>
                        <span>Update Master Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- RECENT SECURITY AUDIT TRAIL -->
    <?php if (!empty($auditLogs)): ?>
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Recent Administrative &amp; Security Audits</h3>
                        <p class="text-xs text-slate-500">Cryptographically verifiable actions taken across the platform.</p>
                    </div>
                </div>
                <span class="text-[11px] font-mono text-slate-500 font-semibold">Realtime Audit Ledger</span>
            </div>

            <div class="divide-y divide-slate-100">
                <?php foreach ($auditLogs as $log): ?>
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span class="font-mono font-bold text-rose-700"><?= e($log['action']) ?></span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-slate-700"><?= e($log['description']) ?></span>
                        </div>
                        <div class="text-[11px] font-mono text-slate-500 shrink-0">
                            <?= date('M d, Y H:i:s', strtotime($log['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<script>
function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function checkPasswordStrength() {
    const p1 = document.getElementById('new_password').value;
    const checkLen = document.getElementById('check-len');
    const iconLen = document.getElementById('icon-len');

    if (p1.length >= 8) {
        checkLen.className = 'flex items-center gap-2 text-emerald-700 font-semibold';
        iconLen.className = 'fa-solid fa-circle-check text-[10px] text-emerald-600';
    } else {
        checkLen.className = 'flex items-center gap-2 text-slate-500';
        iconLen.className = 'fa-solid fa-circle text-[8px] text-slate-400';
    }
    checkPasswordMatch();
}

function checkPasswordMatch() {
    const p1 = document.getElementById('new_password').value;
    const p2 = document.getElementById('confirm_password').value;
    const checkMatch = document.getElementById('check-match');
    const iconMatch = document.getElementById('icon-match');

    if (p2.length > 0 && p1 === p2) {
        checkMatch.className = 'flex items-center gap-2 text-emerald-700 font-semibold';
        iconMatch.className = 'fa-solid fa-circle-check text-[10px] text-emerald-600';
    } else if (p2.length > 0) {
        checkMatch.className = 'flex items-center gap-2 text-rose-700 font-semibold';
        iconMatch.className = 'fa-solid fa-circle-xmark text-[10px] text-rose-600';
    } else {
        checkMatch.className = 'flex items-center gap-2 text-slate-500';
        iconMatch.className = 'fa-solid fa-circle text-[8px] text-slate-400';
    }
}
</script>
