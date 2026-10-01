<?php
/**
 * Customer / Tenant Organization Registration View
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

        <!-- Brand Top -->
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
        <div class="relative z-10 max-w-lg my-auto py-8">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-400/25 text-emerald-300 text-xs font-semibold mb-6">
                <i class="fa-solid fa-gift text-sm text-emerald-400"></i>
                <span>14-Day Full Access Free Trial &bull; No Credit Card Required</span>
            </div>

            <h2 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight leading-tight">
                Spin up your isolated multi-tenant organization in seconds.
            </h2>
            <p class="mt-4 text-slate-300 text-sm leading-relaxed font-normal">
                Join high-growth Indian SaaS companies who rely on SaaSify for automatic Indian GST tax billing, row-level data isolation, and instant UPI/Card checkout.
            </p>

            <!-- Provisioning Pipeline Preview -->
            <div class="mt-8 space-y-3.5">
                <div class="flex items-start space-x-3.5 p-4 rounded-2xl bg-white/5 border border-white/10 shadow-sm backdrop-blur-xs">
                    <div class="w-8 h-8 rounded-xl bg-[#0C66E4] text-white flex items-center justify-center shrink-0 text-xs font-bold">1</div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Dedicated Subdomain Routing</h4>
                        <p class="text-[11px] text-slate-300 mt-0.5">Instantly assigns your isolated company workspace at <code class="text-blue-300 font-mono">your-company.saasify.app</code></p>
                    </div>
                </div>

                <div class="flex items-start space-x-3.5 p-4 rounded-2xl bg-white/5 border border-white/10 shadow-sm backdrop-blur-xs">
                    <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 text-xs font-bold">2</div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Row-Level Tenant Isolation</h4>
                        <p class="text-[11px] text-slate-300 mt-0.5">Cryptographically scoped organization ID guarantees zero cross-tenant data leakage.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3.5 p-4 rounded-2xl bg-white/5 border border-white/10 shadow-sm backdrop-blur-xs">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 text-xs font-bold">3</div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Automated GST Invoicing</h4>
                        <p class="text-[11px] text-slate-300 mt-0.5">Fully compliant Indian GST (18%) invoicing ready for B2B Input Tax Credit (ITC).</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="relative z-10 flex items-center justify-between text-xs text-slate-400 border-t border-white/10 pt-6">
            <span>&copy; <?= date('Y') ?> SaaSify Platforms Pvt Ltd.</span>
            <div class="flex items-center space-x-2 text-emerald-400 font-semibold">
                <i class="fa-solid fa-shield-halved"></i>
                <span>SOC-2 Certified Architecture</span>
            </div>
        </div>
    </div>

    <!-- Right Form Panel - Crisp Light Theme -->
    <div class="flex-1 flex flex-col justify-between p-6 sm:p-10 lg:p-14 xl:p-16 bg-[#F8FAFC] overflow-y-auto">

        <!-- Top Nav / Back Link & Sign in link -->
        <div class="flex items-center justify-between pb-6">
            <a href="<?= app_url('/') ?>" class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-600 hover:text-slate-900 transition px-3 py-1.5 rounded-lg hover:bg-slate-200/60">
                <i class="fa-solid fa-arrow-left text-[11px] text-slate-500"></i>
                <span>Back to Home</span>
            </a>

            <div class="text-xs text-slate-600">
                Already registered?
                <a href="<?= app_url('/login') ?>" class="text-[#0C66E4] font-bold hover:underline ml-1">
                    Sign in to workspace &rarr;
                </a>
            </div>
        </div>

        <!-- Form Center Container -->
        <div class="max-w-md w-full mx-auto my-auto py-6">

            <!-- Header -->
            <div class="mb-6">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-[#0C66E4] text-xs font-semibold mb-3">
                    <i class="fa-solid fa-rocket text-[11px]"></i>
                    <span>Self-Service Onboarding</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Create your organization</h1>
                <p class="text-sm text-slate-500 mt-1 font-normal">Start your 14-day free trial on the Starter plan. Ready in 30 seconds.</p>
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
                <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center space-x-2.5 shadow-xs">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                    <span><?= e($success) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium flex items-center space-x-2.5 shadow-xs">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base shrink-0"></i>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Registration Form Card -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                <form id="registerForm" action="<?= app_url('/register') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>

                    <!-- Company Name -->
                    <div>
                        <label for="company_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Company / Organization Name <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <i class="fa-solid fa-building text-sm"></i>
                            </span>
                            <input type="text" name="company_name" id="company_name" required
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm transition"
                                   placeholder="e.g. Apex Software Corp"
                                   value="<?= e(old('company_name', '')) ?>">
                        </div>
                    </div>

                    <!-- Subdomain with Live Preview -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="subdomain" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Workspace Subdomain <span class="text-rose-500">*</span>
                            </label>
                            <span id="subdomainPreview" class="text-[11px] font-mono text-[#0C66E4] font-semibold truncate max-w-[200px]">
                                your-company.saasify.app
                            </span>
                        </div>
                        <div class="flex items-center">
                            <div class="relative flex-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none font-mono text-xs">
                                    https://
                                </span>
                                <input type="text" name="subdomain" id="subdomain" required
                                       pattern="[a-z0-9-]+"
                                       class="w-full pl-20 pr-3 py-2.5 rounded-l-xl bg-slate-50 border border-r-0 border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm font-mono lowercase transition"
                                       placeholder="apex"
                                       value="<?= e(old('subdomain', '')) ?>">
                            </div>
                            <span class="px-3.5 py-2.5 bg-slate-100 border border-slate-300 rounded-r-xl text-xs text-slate-600 font-mono select-none">
                                .saasify.app
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">Lowercase letters, numbers, and hyphens only.</p>
                    </div>

                    <!-- Owner Name & Email (Two Columns) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Your Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm transition"
                                   placeholder="e.g. Rahul Sharma"
                                   value="<?= e(old('name', '')) ?>">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Work Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" required
                                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm transition"
                                   placeholder="rahul@apex.com"
                                   value="<?= e(old('email', '')) ?>">
                        </div>
                    </div>

                    <!-- Password with Eye Toggle -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required minlength="6"
                                   class="w-full pl-4 pr-10 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm transition"
                                   placeholder="Minimum 6 characters">
                            <button type="button" onclick="togglePasswordVisibility('password', 'pwIcon1')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                                <i id="pwIcon1" class="fa-regular fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Confirm Password <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6"
                                   class="w-full pl-4 pr-10 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#0C66E4]/20 focus:border-[#0C66E4] text-sm transition"
                                   placeholder="Re-enter password">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'pwIcon2')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                                <i id="pwIcon2" class="fa-regular fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="registerSubmitBtn"
                            class="w-full mt-3 py-3 px-4 rounded-xl bg-[#0C66E4] hover:bg-[#0055CC] text-white font-bold text-sm shadow-xs transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                        <span>Provision Tenant &amp; Start 14-Day Free Trial</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </div>

            <!-- Legal / Terms Notice -->
            <div class="mt-6 text-center">
                <p class="text-[11px] text-slate-500">
                    By registering, you agree to our Terms of Service &amp; Privacy Policy.<br>
                    No payment details required &bull; Auto-cancels if not upgraded.
                </p>
            </div>
        </div>

        <!-- Footer helper -->
        <div class="text-center text-xs text-slate-500 py-2">
            Multi-Tenant Isolation Architecture &bull; Instant Provisioning Engine
        </div>
    </div>
</div>

<script>
const companyInput = document.getElementById('company_name');
const subdomainInput = document.getElementById('subdomain');
const previewSpan = document.getElementById('subdomainPreview');
let manualEdited = <?= !empty(old('subdomain')) ? 'true' : 'false' ?>;

if (subdomainInput.value) {
    updatePreview(subdomainInput.value);
}

subdomainInput.addEventListener('input', () => {
    manualEdited = true;
    updatePreview(subdomainInput.value);
});

companyInput.addEventListener('input', () => {
    if (!manualEdited) {
        const slug = companyInput.value
            .toLowerCase()
            .replace(/[^a-z0-9]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        subdomainInput.value = slug;
        updatePreview(slug);
    }
});

function updatePreview(val) {
    if (previewSpan) {
        previewSpan.textContent = (val ? val : 'your-company') + '.saasify.app';
    }
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