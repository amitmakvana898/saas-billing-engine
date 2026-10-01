<?php
/**
 * Customer / Tenant Organization Login View
 * Razorpay Indian Fintech Redesign
 * Renders inside layouts/auth.php
 */
$error = flash('error');
$success = flash('success');
?>

<div class="min-h-screen w-full flex flex-col lg:flex-row bg-[#F8FAFC]">

    <!-- Left Showcase Panel (Desktop) - Razorpay Deep Enterprise Navy -->
    <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 bg-[#0B1A30] p-12 xl:p-16 flex-col justify-between relative overflow-hidden text-white border-r border-slate-800">
        <!-- Ambient Decorative Pattern -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#0C66E4]/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-blue-600/15 rounded-full blur-[100px] pointer-events-none"></div>

        <!-- Brand Top Header -->
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

        <!-- Center Showcase Content -->
        <div class="relative z-10 max-w-lg my-auto py-10">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/15 border border-blue-400/25 text-blue-300 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Customer Workspace Portal &bull; RazorpayX Standard</span>
            </div>

            <h2 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight leading-tight">
                Enterprise B2B Subscription &amp; Indian GST Billing
            </h2>
            <p class="mt-4 text-slate-300 text-sm leading-relaxed font-normal">
                Access your isolated tenant workspace to manage plans, instantly collect payments via UPI QR &amp; Cards, and download 18% GST tax invoices for ITC claims.
            </p>

            <!-- Real-World Tenant Card Preview -->
            <div class="mt-8 p-5 rounded-2xl bg-white/5 border border-white/10 shadow-xl backdrop-blur-md">
                <div class="flex items-center justify-between text-xs pb-3 border-b border-white/10">
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span class="font-bold text-white">Acme Technologies Pvt Ltd</span>
                        <span class="text-slate-400 font-mono text-[11px]">(acme.saasify.app)</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold uppercase">Active Tier</span>
                </div>
                <div class="grid grid-cols-3 gap-3 mt-4 text-left">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Plan</span>
                        <span class="text-sm font-bold text-white">Pro Scale</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Billing</span>
                        <span class="text-sm font-bold text-blue-300 font-mono">&#8377;4,999/mo</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Team</span>
                        <span class="text-sm font-bold text-white">8/10 Seats</span>
                    </div>
                </div>
            </div>

            <!-- Feature Checklist -->
            <div class="mt-8 space-y-3 text-xs text-slate-300 font-medium">
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Row-level tenant isolation ensures zero cross-company data leakage</span>
                </div>
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Official 18% GST tax invoicing with CGST/SGST itemized breakdowns</span>
                </div>
                <div class="flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-400"></i>
                    <span>Instant checkout via UPI (Google Pay, PhonePe, Paytm) &amp; NetBanking</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="relative z-10 flex items-center justify-between text-xs text-slate-400 border-t border-white/10 pt-6">
            <span>&copy; <?= date('Y') ?> SaaSify Platforms Pvt Ltd.</span>
            <div class="flex items-center space-x-2 text-emerald-400 font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Razorpay Gateway 99.99% Live</span>
            </div>
        </div>
    </div>

    <!-- Right Form Panel - Crisp White Light Theme -->
    <div class="flex-1 flex flex-col justify-between p-6 sm:p-10 lg:p-14 xl:p-16 bg-[#F8FAFC] overflow-y-auto">

        <!-- Top Nav / Back Link & Role Switcher -->
        <div class="flex items-center justify-between pb-6">
            <a href="<?= app_url('/') ?>" class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition px-3 py-1.5 rounded-lg hover:bg-slate-200/60">
                <i class="fa-solid fa-arrow-left text-[11px] text-slate-500"></i>
                <span>Back to Home</span>
            </a>

            <!-- Portal Switcher Indicator -->
            <div class="flex items-center space-x-2">
                <span class="text-xs text-slate-500 hidden sm:inline">Platform Operator?</span>
                <a href="<?= app_url('/admin/login') ?>" class="inline-flex items-center space-x-1.5 text-xs font-bold text-rose-700 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-3 py-1.5 rounded-xl transition shadow-xs">
                    <i class="fa-solid fa-shield-halved text-[11px]"></i>
                    <span>Super Admin Console &rarr;</span>
                </a>
            </div>
        </div>

        <!-- Form Center Container -->
        <div class="max-w-md w-full mx-auto my-auto py-6">

            <!-- Dual Portal Switcher Tabs -->
            <div class="mb-7 p-1 bg-slate-200/80 rounded-xl flex items-center text-xs font-bold border border-slate-300/70">
                <a href="<?= app_url('/login') ?>" class="flex-1 py-2 px-3 rounded-lg bg-white text-[#0C66E4] shadow-xs flex items-center justify-center space-x-2 text-center transition">
                    <i class="fa-solid fa-building text-xs"></i>
                    <span>Client Workspace</span>
                </a>
                <a href="<?= app_url('/admin/login') ?>" class="flex-1 py-2 px-3 rounded-lg text-slate-600 hover:text-slate-900 flex items-center justify-center space-x-2 text-center transition">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                    <span>Super Admin</span>
                </a>
            </div>

            <!-- Header Titles -->
            <div class="mb-6">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Sign in to your organization</h1>
                <p class="text-sm text-slate-500 mt-2 font-normal">Enter your registered work email and password to access your isolated workspace.</p>
            </div>

            <!-- Active Session Notice (if already logged in) -->
            <?php if (!empty($currentUser)): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 text-xs flex items-center justify-between shadow-xs">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-info text-[#0C66E4]"></i>
                        <span>Logged in as <strong><?= e($currentUser['email']) ?></strong></span>
                    </div>
                    <div class="space-x-2 shrink-0">
                        <a href="<?= app_url('/dashboard') ?>" class="text-xs font-bold text-[#0C66E4] hover:underline">Dashboard &rarr;</a>
                        <a href="<?= app_url('/logout') ?>" class="text-xs text-rose-600 hover:underline">Sign Out</a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Flash Alerts -->
            <?php if (!empty($success)): ?>
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center space-x-2.5 shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                    <span><?= e($success) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center space-x-2.5 shadow-xs">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base shrink-0"></i>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Sign In Form Card -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                <form id="loginForm" action="<?= app_url('/login') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <!-- Work Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Work Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fa-regular fa-envelope text-sm"></i>
                            </span>
                            <input type="email" name="email" id="email" required
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm transition"
                                   placeholder="name@company.com"
                                   value="<?= e(old('email', '')) ?>">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                            <a href="<?= app_url('/forgot-password') ?>" class="text-[11px] font-bold text-[#0C66E4] hover:underline">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fa-solid fa-lock text-sm"></i>
                            </span>
                            <input type="password" name="password" id="password" required
                                   class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm transition"
                                   placeholder="Enter your account password">
                            <button type="button" onclick="togglePasswordVisibility('password', 'passwordToggleIcon')" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                                <i id="passwordToggleIcon" class="fa-regular fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- 1-Click Demo Login Helper -->
                    <div class="pt-1">
                        <button type="button" onclick="fillCredentials('tony@gmail.com', 'Password123!')"
                                class="w-full py-1.5 px-3 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#0C66E4] text-xs font-semibold border border-blue-200 transition flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                            <span>Autofill Demo Workspace (tony@gmail.com)</span>
                        </button>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn"
                            class="w-full mt-2 py-3 px-4 rounded-xl bg-[#0C66E4] hover:bg-[#0055CC] text-white font-bold text-sm shadow-xs transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-right-to-bracket text-xs"></i>
                        <span>Sign In to Organization Workspace</span>
                    </button>
                </form>
            </div>

            <!-- Register Link -->
            <div class="mt-6 pt-5 border-t border-slate-200 text-center">
                <p class="text-xs text-slate-600">
                    New company?
                    <a href="<?= app_url('/register') ?>" class="text-[#0C66E4] font-bold hover:underline ml-1">
                        Register organization (14-Day Free Trial) &rarr;
                    </a>
                </p>
            </div>

            <!-- Super Admin Prompt -->
            <div class="mt-3 text-center">
                <p class="text-[11px] text-slate-500">
                    Looking for the Platform Master Control?
                    <a href="<?= app_url('/admin/login') ?>" class="text-rose-600 hover:underline font-semibold ml-1">
                        Sign in as Super Admin
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer helper -->
        <div class="text-center text-xs text-slate-500 py-2">
            Protected by Row-Level Security &bull; 256-bit SSL Encryption &bull; Indian GST Compliant
        </div>
    </div>
</div>

<script>
function fillCredentials(email, password) {
    const emailField = document.getElementById('email');
    const pwdField = document.getElementById('password');
    emailField.value = email;
    pwdField.value = password;

    // Visual pulse highlight
    [emailField, pwdField].forEach(el => {
        el.classList.add('ring-2', 'ring-[#0C66E4]', 'bg-blue-50');
        setTimeout(() => {
            el.classList.remove('ring-2', 'ring-[#0C66E4]', 'bg-blue-50');
        }, 600);
    });
}

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