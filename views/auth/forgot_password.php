<?php
/**
 * Customer / Tenant Organization Forgot Password View
 * Renders inside layouts/auth.php
 */
$error = flash('error');
$success = flash('success');
$resetLink = flash('reset_link');
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
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/15 border border-blue-400/25 text-blue-300 text-xs font-semibold mb-6">
                <i class="fa-solid fa-key text-blue-400"></i>
                <span>Self-Service Account Recovery</span>
            </div>

            <h2 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight leading-tight">
                Secure &amp; Instant Password Recovery
            </h2>
            <p class="mt-4 text-slate-300 text-sm leading-relaxed font-normal">
                Forgot your credentials? Enter your verified work email address to receive an encrypted one-time reset token valid for 60 minutes.
            </p>

            <div class="mt-8 space-y-3 text-xs text-slate-300 font-medium">
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Cryptographically signed 256-bit entropy reset token</span>
                </div>
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Automatic expiration after 1 hour or single usage</span>
                </div>
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Bcrypt-hashed credential storage (Google Security Standards)</span>
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
                <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-200 text-[#0C66E4] flex items-center justify-center text-xl mb-4 shadow-xs">
                    <i class="fa-solid fa-unlock-keyhole"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Reset your password</h1>
                <p class="text-sm text-slate-500 mt-2 font-normal">Enter the email associated with your organization account and we'll help you regain access.</p>
            </div>

            <!-- Flash Alerts -->
            <?php if (!empty($success)): ?>
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium space-y-2 shadow-xs">
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                        <span><?= e($success) ?></span>
                    </div>
                    <?php if (!empty($resetLink)): ?>
                        <div class="mt-3 p-3 bg-white rounded-lg border border-emerald-300">
                            <span class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">Direct Reset Link:</span>
                            <a href="<?= e($resetLink) ?>" class="text-xs font-mono text-[#0C66E4] hover:underline break-all block">
                                <?= e($resetLink) ?>
                            </a>
                            <a href="<?= e($resetLink) ?>" class="mt-2 inline-flex items-center space-x-1.5 px-3 py-1 rounded bg-[#0C66E4] text-white text-xs font-bold hover:bg-[#0055CC] transition">
                                <span>Proceed to Set New Password &rarr;</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center space-x-2.5 shadow-xs">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base shrink-0"></i>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                <form action="<?= app_url('/forgot-password') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <!-- Work Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Registered Work Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fa-regular fa-envelope text-sm"></i>
                            </span>
                            <input type="email" name="email" id="email" required
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm transition"
                                   placeholder="name@company.com">
                        </div>
                    </div>

                    <!-- Demo autofill -->
                    <div class="pt-1">
                        <button type="button" onclick="document.getElementById('email').value='tony@gmail.com'"
                                class="w-full py-1.5 px-3 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium transition flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-wand-magic-sparkles text-[10px] text-slate-500"></i>
                            <span>Autofill Demo Email (tony@gmail.com)</span>
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full mt-2 py-3 px-4 rounded-xl bg-[#0C66E4] hover:bg-[#0055CC] text-white font-bold text-sm shadow-xs transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Generate Password Reset Link</span>
                    </button>
                </form>
            </div>

            <!-- Back to Login Link -->
            <div class="mt-6 pt-5 border-t border-slate-200 text-center">
                <p class="text-xs text-slate-600">
                    Remembered your password?
                    <a href="<?= app_url('/login') ?>" class="text-[#0C66E4] font-bold hover:underline ml-1">
                        Sign in here &rarr;
                    </a>
                </p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-500 py-2">
            Protected by Rate Limiting &bull; CSRF Shielded &bull; Multi-Tenant Isolated
        </div>
    </div>
</div>
