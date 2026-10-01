<?php
/**
 * Admin Super Console Login Page
 * Razorpay Executive Operations Redesign
 * Standalone file — uses null layout (no wrapping layout).
 */
$error = flash('error') ?? ($error ?? null);
$success = flash('success') ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Console &mdash; SaaSify Master Control</title>

    <!-- Google Fonts: Plus Jakarta Sans + JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:  ['Plus Jakarta Sans', 'sans-serif'],
                        mono:  ['JetBrains Mono', 'monospace'],
                    },
                }
            }
        };
    </script>

    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        input:focus { outline: none; }
        .pw-toggle { cursor: pointer; }
    </style>
</head>
<body class="bg-[#F8FAFC] font-sans text-slate-900 antialiased">

<div class="flex flex-col lg:flex-row min-h-screen">

    <!-- ========================================================
         LEFT PANEL — Executive Deep Navy Showcase
         ======================================================== -->
    <div class="relative lg:w-1/2 bg-[#0B1A30] flex flex-col justify-between overflow-hidden px-10 py-10 lg:px-14 lg:py-12 text-white border-r border-slate-800">

        <!-- Decorative background glow -->
        <div class="absolute -top-32 -left-32 w-[450px] h-[450px] rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(225,29,72,0.3) 0%, rgba(225,29,72,0.05) 50%, transparent 70%);"></div>
        <div class="absolute bottom-10 right-[-80px] w-[320px] h-[320px] rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(12,102,228,0.25) 0%, rgba(12,102,228,0.04) 55%, transparent 70%);"></div>

        <!-- Top Navigation -->
        <div class="relative z-10 flex items-center justify-between">
            <a href="<?= app_url('/') ?>"
               class="inline-flex items-center gap-2 text-slate-400 hover:text-white text-xs font-semibold transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                Back to Home
            </a>
            <a href="<?= app_url('login') ?>"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-300 hover:text-white bg-blue-500/15 border border-blue-400/25 px-3 py-1.5 rounded-xl transition">
                <i class="fa-solid fa-building text-[10px]"></i>
                <span>Customer Workspace &rarr;</span>
            </a>
        </div>

        <!-- Main Showcase Content -->
        <div class="relative z-10 my-auto py-10 max-w-lg">
            <!-- Shield Icon -->
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-rose-500/20 border border-rose-500/30 text-rose-400 shadow-md">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-3xl lg:text-4xl font-extrabold text-white leading-tight mb-2 tracking-tight">
                Master <span class="text-rose-400">Control</span>
            </h1>
            <p class="text-xs lg:text-sm text-rose-300 font-bold uppercase tracking-wider mb-2 font-mono">
                Platform Super Admin Console
            </p>
            <p class="text-slate-300 text-xs lg:text-sm leading-relaxed mb-8">
                Sovereign root access to the entire multi-tenant infrastructure, tenant isolation policies, and global Indian GST revenue telemetry.
            </p>

            <!-- Capabilities list -->
            <ul class="space-y-3 mb-8 text-xs text-slate-300">
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 rounded-full bg-rose-500/20 border border-rose-500/40 flex items-center justify-center text-rose-400 text-[10px]">
                        <i class="fa-solid fa-check"></i>
                    </span>
                    <span>Full tenant lifecycle management (Suspend / Reactivate)</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 rounded-full bg-rose-500/20 border border-rose-500/40 flex items-center justify-center text-rose-400 text-[10px]">
                        <i class="fa-solid fa-check"></i>
                    </span>
                    <span>Real-time webhook idempotency &amp; event monitoring</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 rounded-full bg-rose-500/20 border border-rose-500/40 flex items-center justify-center text-rose-400 text-[10px]">
                        <i class="fa-solid fa-check"></i>
                    </span>
                    <span>Platform health beacons &amp; active tenant diagnostics</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 rounded-full bg-rose-500/20 border border-rose-500/40 flex items-center justify-center text-rose-400 text-[10px]">
                        <i class="fa-solid fa-check"></i>
                    </span>
                    <span>Global platform MRR and Indian GST tax ledger analytics</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="w-5 h-5 rounded-full bg-rose-500/20 border border-rose-500/40 flex items-center justify-center text-rose-400 text-[10px]">
                        <i class="fa-solid fa-check"></i>
                    </span>
                    <span>Emergency isolation and administrative security governance</span>
                </li>
            </ul>

            <!-- Security notice pill -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-medium text-slate-400 bg-white/5 border border-white/10">
                <span>&#x1F512;</span>
                <span>MFA-Protected &bull; SOC 2 Compliant &bull; Root Audit Logged</span>
            </div>
        </div>

        <!-- Bottom decoration line -->
        <div class="relative z-10 pt-6 border-t border-white/10">
            <p class="text-slate-400 text-xs font-mono">
                SaaSify Platform &copy; <?= date('Y') ?> &mdash; Restricted Sovereign Access
            </p>
        </div>
    </div>

    <!-- ========================================================
         RIGHT PANEL — Clean Light Form Panel
         ======================================================== -->
    <div class="relative lg:w-1/2 bg-[#F8FAFC] flex flex-col items-center justify-center px-6 py-12 lg:px-14">

        <!-- Form card -->
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl p-8 lg:p-10 shadow-xs">

            <!-- Dual Portal Switcher Tabs -->
            <div class="mb-7 p-1 bg-slate-100 rounded-xl flex items-center text-xs font-bold border border-slate-200">
                <a href="<?= app_url('/login') ?>" class="flex-1 py-2 px-3 rounded-lg text-slate-600 hover:text-slate-900 flex items-center justify-center space-x-2 text-center transition">
                    <i class="fa-solid fa-building text-xs"></i>
                    <span>Client Workspace</span>
                </a>
                <a href="<?= app_url('/admin/login') ?>" class="flex-1 py-2 px-3 rounded-lg bg-white text-rose-600 shadow-xs flex items-center justify-center space-x-2 text-center transition">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                    <span>Super Admin</span>
                </a>
            </div>

            <!-- Card header -->
            <div class="mb-6">
                <span class="inline-block text-xs font-bold text-rose-600 tracking-widest uppercase mb-1 font-mono">
                    <i class="fa-solid fa-lock mr-1"></i> Root Authentication
                </span>
                <h2 class="text-2xl font-black text-slate-900 mb-1">Welcome back, Super Admin</h2>
                <p class="text-slate-500 text-xs font-normal">Sign in with sovereign credentials to access Master Control.</p>
            </div>

            <!-- Success display -->
            <?php if (!empty($success)) : ?>
            <div class="mb-5 flex items-start gap-3 px-4 py-3 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 shrink-0 text-sm"></i>
                <p class="leading-relaxed"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <?php endif; ?>

            <!-- Error display -->
            <?php if (!empty($error)) : ?>
            <div class="mb-5 flex items-start gap-3 px-4 py-3 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 text-xs">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 mt-0.5 shrink-0 text-sm"></i>
                <p class="leading-relaxed"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <?php endif; ?>

            <!-- Login form -->
            <form id="adminLoginForm"
                  action="<?= app_url('admin/login') ?>"
                  method="POST"
                  novalidate
                  autocomplete="off"
                  class="space-y-4">

                <?= csrf_field() ?>

                <!-- Email field -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Admin Email
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none select-none font-bold">
                            @
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="superadmin@saasify.app"
                            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '' ?>"
                            required
                            autocomplete="off"
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl text-sm text-slate-900 placeholder-slate-400 font-mono bg-slate-50 border border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition"
                        >
                    </div>
                </div>

                <!-- Password field -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none select-none">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                            required
                            autocomplete="new-password"
                            class="w-full pl-10 pr-12 py-2.5 rounded-xl text-sm text-slate-900 placeholder-slate-400 font-mono bg-slate-50 border border-slate-300 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition"
                        >
                        <!-- Eye toggle -->
                        <button
                            type="button"
                            id="pwToggle"
                            class="pw-toggle absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-sm"
                            aria-label="Toggle password visibility"
                            tabindex="-1"
                        >
                            <i class="fa-solid fa-eye" id="pwToggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- 1-Click Autofill Button -->
                <div class="pt-1">
                    <button type="button" onclick="autofillAdmin()"
                            class="w-full py-1.5 px-3 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold border border-rose-200 transition flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                        <span>Autofill Super Admin (superadmin@saasify.app)</span>
                    </button>
                </div>

                <!-- Submit button -->
                <button
                    type="submit"
                    id="submitBtn"
                    class="w-full mt-2 py-3 rounded-xl text-sm font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-xs transition flex items-center justify-center gap-2"
                >
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                    <span id="submitLabel">Access Admin Console</span>
                </button>

            </form>

            <!-- Security policy note -->
            <div class="mt-6 flex items-start gap-2.5 p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 text-xs leading-relaxed">
                <i class="fa-solid fa-circle-info text-slate-400 mt-0.5 shrink-0"></i>
                <p>
                    Admin credentials are sovereign. Self-service password reset is disabled. For security policy updates, use the internal Admin Profile console.
                </p>
            </div>
        </div>

        <!-- Version badge -->
        <div class="mt-6 text-center">
            <span class="text-slate-400 text-xs font-mono">
                SaaSify Admin &mdash; Restricted Sovereign Access
            </span>
        </div>
    </div>

</div>

<!-- Vanilla JS -->
<script>
function autofillAdmin() {
    const emailField = document.getElementById('email');
    const pwdField = document.getElementById('password');
    emailField.value = 'superadmin@saasify.app';
    pwdField.value = 'AdminPass123!';

    [emailField, pwdField].forEach(el => {
        el.classList.add('ring-2', 'ring-rose-500', 'bg-rose-50');
        setTimeout(() => {
            el.classList.remove('ring-2', 'ring-rose-500', 'bg-rose-50');
        }, 600);
    });
}

(function () {
    'use strict';
    var pwInput      = document.getElementById('password');
    var pwToggle     = document.getElementById('pwToggle');
    var pwToggleIcon = document.getElementById('pwToggleIcon');

    if (pwToggle && pwInput && pwToggleIcon) {
        pwToggle.addEventListener('click', function () {
            var isHidden = pwInput.type === 'password';
            pwInput.type = isHidden ? 'text' : 'password';
            pwToggleIcon.className = isHidden
                ? 'fa-solid fa-eye-slash'
                : 'fa-solid fa-eye';
        });
    }

    var form        = document.getElementById('adminLoginForm');
    var submitBtn   = document.getElementById('submitBtn');
    var submitLabel = document.getElementById('submitLabel');

    if (form && submitBtn && submitLabel) {
        form.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitLabel.textContent = 'Authenticating\u2026';
            submitBtn.innerHTML =
                '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i>' +
                '<span>Authenticating&hellip;</span>';
        });
    }
})();
</script>

</body>
</html>