<?php
/**
 * Customer / Tenant Organization Reset Password View
 * Renders inside layouts/auth.php
 */
$error = flash('error');
$success = flash('success');
?>

<div class="min-h-screen w-full flex flex-col lg:flex-row bg-[#F8FAFC]">

    <!-- Left Showcase Panel (Desktop) -->
    <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 bg-[#0B1A30] p-12 xl:p-16 flex-col justify-between relative overflow-hidden text-white border-r border-slate-800">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#0C66E4]/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-blue-600/15 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="relative z-10">
            <a href="<?= app_url('/') ?>" class="inline-flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-xl bg-[#0C66E4] flex items-center justify-center text-white shadow-md font-extrabold text-lg group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <span class="text-2xl font-black text-white tracking-tight">SaaSify</span>
                    <span class="ml-2 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md bg-white/10 text-blue-200 border border-white/10">
                        Fintech Engine
                    </span>
                </div>
            </a>
        </div>

        <div class="relative z-10 max-w-lg my-auto py-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-500/15 border border-emerald-400/25 text-emerald-300 text-xs font-semibold mb-6">
                <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                <span>Verified Token Authenticated</span>
            </div>

            <h2 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight leading-tight">
                Create a Strong New Password
            </h2>
            <p class="mt-4 text-slate-300 text-sm leading-relaxed font-normal">
                Choose a strong password with at least 6 characters. Once changed, your old password will be permanently superseded and session credentials refreshed.
            </p>

            <div class="mt-8 space-y-3 text-xs text-slate-300 font-medium">
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Minimum 6 characters with mixed complexity recommended</span>
                </div>
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Instant token invalidation prevents replay attacks</span>
                </div>
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Zero plain-text password persistence</span>
                </div>
            </div>
        </div>

        <div class="relative z-10 flex items-center justify-between text-xs text-slate-400 border-t border-white/10 pt-6">
            <span>&copy; <?= date('Y') ?> SaaSify Platforms Pvt Ltd.</span>
            <div class="flex items-center space-x-2 text-emerald-400 font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Security Engine Active</span>
            </div>
        </div>
    </div>

    <!-- Right Form Panel -->
    <div class="flex-1 flex flex-col justify-between p-6 sm:p-10 lg:p-14 xl:p-16 bg-[#F8FAFC] overflow-y-auto">

        <!-- Top Nav / Back Link -->
        <div class="flex items-center justify-between pb-6">
            <a href="<?= app_url('/login') ?>" class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition px-3 py-1.5 rounded-lg hover:bg-slate-200/60">
                <i class="fa-solid fa-arrow-left text-[11px] text-slate-500"></i>
                <span>Back to Sign In</span>
            </a>
        </div>

        <!-- Form Center Container -->
        <div class="max-w-md w-full mx-auto my-auto py-6">

            <!-- Header Titles -->
            <div class="mb-6">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center text-xl mb-4 shadow-xs">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Set new password</h1>
                <p class="text-sm text-slate-500 mt-2 font-normal">
                    Resetting password for <strong class="text-slate-800"><?= e($email ?? 'your account') ?></strong>.
                </p>
            </div>

            <!-- Flash Alerts -->
            <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center space-x-2.5 shadow-xs">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base shrink-0"></i>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                <form action="<?= app_url('/reset-password/' . e($token)) ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <!-- New Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">New Password</label>
                            <span class="text-[11px] text-slate-500">Min 6 characters</span>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input type="password" name="password" id="password" required
                                   class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm transition"
                                   placeholder="Enter new password">
                            <button type="button" onclick="togglePasswordVisibility('password', 'pwdToggleIcon1')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                                <i id="pwdToggleIcon1" class="fa-regular fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Confirm New Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fa-solid fa-lock-open text-sm"></i>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm transition"
                                   placeholder="Re-enter new password">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'pwdToggleIcon2')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                                <i id="pwdToggleIcon2" class="fa-regular fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full mt-3 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-xs transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        <span>Update Password &amp; Continue</span>
                    </button>
                </form>
            </div>

            <!-- Back to Login Link -->
            <div class="mt-6 pt-5 border-t border-slate-200 text-center">
                <p class="text-xs text-slate-600">
                    Know your password?
                    <a href="<?= app_url('/login') ?>" class="text-[#0C66E4] font-bold hover:underline ml-1">
                        Sign in instead &rarr;
                    </a>
                </p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-500 py-2">
            Protected by Row-Level Security &bull; 256-bit SSL Encryption
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(fieldId, iconId) {
    const pwd = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
