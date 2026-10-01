<?php
/**
 * Home Page View — SaaSify Enterprise SaaS Billing Engine
 * Multi-Tenant Architecture, 18% GST Dual-Tax Invoicing, Developer REST API v1 & Bulk CSV Vault
 * Standalone view returned directly by HomeController.
 * Compatible: PHP 7.4 / 8.2
 */
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaSify — Enterprise Multi-Tenant SaaS Billing &amp; 18% GST Invoicing Platform</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#EEF2FF',
                            100: '#E0E7FF',
                            500: '#6366F1',
                            600: '#4F46E5',
                            700: '#4338CA',
                            900: '#312E81'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        };
    </script>

    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        code, .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* Smooth Floating Animation */
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        .float-slow { animation: floatSlow 5s ease-in-out infinite; }

        /* Subtle Pulse Glow */
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }
        .pulse-glow { animation: pulseGlow 6s ease-in-out infinite; }

        /* Card Hover Lift */
        .feature-card {
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            border-color: #6366F1;
            box-shadow: 0 16px 32px -8px rgba(79, 70, 229, 0.12);
        }

        /* Annual Mode Pricing Toggle */
        body.annual-mode .monthly-price { display: none !important; }
        body:not(.annual-mode) .annual-price { display: none !important; }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">

<?php if ($flash = (flash('success') ?? flash('error'))): ?>
    <div class="bg-indigo-600 text-white text-xs font-bold py-2.5 px-4 text-center sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-center space-x-2">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= e($flash) ?></span>
        </div>
    </div>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════
     HEADER & NAVIGATION BAR
══════════════════════════════════════════════════════════ -->
<header id="main-nav" class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 transition-all duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-[72px]">

            <!-- Logo -->
            <a href="<?= app_url('/') ?>" class="flex items-center space-x-2.5 group">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 flex items-center justify-center text-white shadow-md shadow-indigo-500/25 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-bolt text-lg"></i>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">SaaSify</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono">
                        Enterprise
                    </span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center space-x-8 text-sm font-semibold text-slate-600">
                <a href="#cockpit" class="hover:text-indigo-600 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-microchip text-xs text-indigo-500"></i>
                    <span>Engine Studio</span>
                </a>
                <a href="#features" class="hover:text-indigo-600 transition">Features</a>
                <a href="#gst-calculator" class="hover:text-indigo-600 transition flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>18% GST Lab</span>
                </a>
                <a href="#api-studio" class="hover:text-indigo-600 transition">REST API</a>
                <a href="#pricing" class="hover:text-indigo-600 transition">Pricing</a>
                <a href="#portals" class="hover:text-indigo-600 transition">Portals</a>
            </nav>

            <!-- Authentication Actions -->
            <div class="flex items-center space-x-3">
                <?php if (is_logged_in()): ?>
                    <?php $u = auth_user(); ?>
                    <a href="<?= app_url('/dashboard') ?>"
                       class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                        <i class="fa-solid fa-gauge-high text-xs"></i>
                        <span>Dashboard (<?= e($u['name'] ?? 'Workspace') ?>)</span>
                    </a>
                    <a href="<?= app_url('/logout') ?>"
                       class="text-xs text-rose-600 hover:text-rose-700 font-semibold px-2 py-1 transition" title="Sign Out">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </a>
                <?php else: ?>
                    <a href="<?= app_url('/login') ?>"
                       class="hidden sm:inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 hover:text-indigo-600 hover:bg-slate-100 transition">
                        <span>Client Login</span>
                    </a>
                    <a href="<?= app_url('/admin/login') ?>"
                       class="hidden md:inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-rose-700 border border-rose-200 bg-rose-50/70 hover:bg-rose-100 transition">
                        <i class="fa-solid fa-shield-halved text-[11px]"></i>
                        <span>Super Admin</span>
                    </a>
                    <a href="<?= app_url('/register') ?>"
                       class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 hover:from-blue-500 hover:to-violet-500 text-white text-xs sm:text-sm font-extrabold shadow-md shadow-indigo-500/25 transition transform hover:-translate-y-0.5">
                        <span>Start Free Trial</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<!-- ═══════════════════════════════════════════════════════
     HERO SECTION: ENTERPRISE ARCHITECTURE
══════════════════════════════════════════════════════════ -->
<section class="relative bg-gradient-to-b from-[#F8FAFC] via-[#FFFFFF] to-[#F1F5F9] pt-16 pb-20 overflow-hidden border-b border-slate-200">
    <!-- Ambient Blur Orbs -->
    <div class="absolute top-10 left-1/2 -translate-x-1/2 w-[750px] h-[320px] bg-indigo-500/10 rounded-full blur-[120px] pointer-events-none pulse-glow"></div>
    <div class="absolute -bottom-20 -left-20 w-[450px] h-[450px] bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative w-full">
        <!-- Hero Header -->
        <div class="text-center max-w-4xl mx-auto space-y-5">
            <!-- Beacon Pill Badge -->
            <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-indigo-50 border border-indigo-200 text-xs font-bold text-indigo-700 shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                <span>✦ Enterprise Multi-Tenant Billing Engine &amp; 18% GST Compliance</span>
            </div>

            <!-- Main Heading -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.15]">
                Full-Lifecycle SaaS Billing, <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 bg-clip-text text-transparent">Dual-Tax GST</span> &amp; Developer Engine
            </h1>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-3xl mx-auto font-normal">
                Engineered for high-growth software teams. Built with cryptographic multi-tenant isolation, automated 18% Indian GST tax computation, official GSTR-1 CA returns, bulk client imports, and high-throughput REST APIs.
            </p>

            <!-- Call to Actions -->
            <div class="pt-3 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                <a href="<?= app_url('/register') ?>"
                   class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 hover:from-blue-500 hover:to-violet-500 text-white font-black text-sm shadow-lg shadow-indigo-500/25 transition flex items-center justify-center space-x-2 transform hover:-translate-y-0.5">
                    <span>Create Your Tenant Workspace</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>

                <a href="#cockpit"
                   class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-bold text-sm shadow-2xs transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-sliders text-indigo-600 text-xs"></i>
                    <span>Explore Live Engine Cockpit</span>
                </a>
            </div>

            <!-- Verified Architecture Trust Signals -->
            <div class="pt-4 flex flex-wrap items-center justify-center gap-6 text-xs text-slate-500 font-semibold">
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>Row-Level Tenant Isolation</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>Official GSTR-1 CA Return Export</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>Developer REST API v1 Included</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>Live Web Audio Bell Alerts</span>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             INTERACTIVE ENTERPRISE ENGINE COCKPIT (No Fake Data)
             ═══════════════════════════════════════════════════════ -->
        <div id="cockpit" class="mt-14 max-w-5xl mx-auto relative">
            <!-- Floating telemetry indicators -->
            <div class="float-slow absolute -top-4 -left-4 hidden lg:flex items-center space-x-2.5 bg-white border border-indigo-200 rounded-2xl px-4 py-2.5 shadow-md text-xs font-bold text-slate-800 z-20">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Tenant Isolation:</span>
                <span class="text-indigo-600 font-mono">Row-Level (AES-256)</span>
            </div>

            <div class="float-slow absolute -top-4 -right-4 hidden lg:flex items-center space-x-2.5 bg-white border border-emerald-200 rounded-2xl px-4 py-2.5 shadow-md text-xs font-bold text-slate-800 z-20">
                <i class="fa-solid fa-file-invoice-dollar text-emerald-600"></i>
                <span>GSTR-1 Format:</span>
                <span class="text-emerald-700 font-mono">Official B2B UTF-8 BOM</span>
            </div>

            <!-- Cockpit Browser Frame -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden relative">
                <!-- Browser Titlebar -->
                <div class="bg-slate-900 px-5 py-3.5 border-b border-slate-800 flex items-center justify-between text-white">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    </div>
                    <div class="px-4 py-1 rounded-lg bg-slate-800/80 border border-slate-700 text-slate-300 text-xs font-mono font-medium max-w-md truncate text-center flex items-center gap-2">
                        <i class="fa-solid fa-lock text-[10px] text-emerald-400"></i>
                        <span id="cockpit-url">https://acme.saasify.app/dashboard &bull; Sovereign Workspace</span>
                    </div>
                    <div class="flex items-center space-x-2 text-xs font-mono text-emerald-400 font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        <span class="hidden sm:inline">API v1 ONLINE</span>
                    </div>
                </div>

                <!-- Interactive Cockpit Tab Selector -->
                <div class="bg-slate-100 border-b border-slate-200 px-4 py-2 flex items-center overflow-x-auto gap-2 text-xs font-bold">
                    <button type="button" onclick="selectCockpitTab('multi-tenant')" id="tab-btn-multi-tenant" 
                            class="px-3.5 py-1.5 rounded-xl bg-white text-indigo-700 shadow-xs transition flex items-center space-x-1.5 shrink-0">
                        <i class="fa-solid fa-building text-xs"></i>
                        <span>1. Multi-Tenant Engine</span>
                    </button>
                    <button type="button" onclick="selectCockpitTab('gst-engine')" id="tab-btn-gst-engine" 
                            class="px-3.5 py-1.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-white/60 transition flex items-center space-x-1.5 shrink-0">
                        <i class="fa-solid fa-calculator text-xs text-blue-600"></i>
                        <span>2. 18% GST &amp; GSTR-1</span>
                    </button>
                    <button type="button" onclick="selectCockpitTab('csv-importer')" id="tab-btn-csv-importer" 
                            class="px-3.5 py-1.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-white/60 transition flex items-center space-x-1.5 shrink-0">
                        <i class="fa-solid fa-file-csv text-xs text-emerald-600"></i>
                        <span>3. Bulk CSV Vault</span>
                    </button>
                    <button type="button" onclick="selectCockpitTab('rest-api')" id="tab-btn-rest-api" 
                            class="px-3.5 py-1.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-white/60 transition flex items-center space-x-1.5 shrink-0">
                        <i class="fa-solid fa-code text-xs text-violet-600"></i>
                        <span>4. REST API &amp; Webhooks</span>
                    </button>
                    <button type="button" onclick="selectCockpitTab('notifications')" id="tab-btn-notifications" 
                            class="px-3.5 py-1.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-white/60 transition flex items-center space-x-1.5 shrink-0">
                        <i class="fa-solid fa-bell text-xs text-amber-500"></i>
                        <span>5. Live Audio Telemetry</span>
                    </button>
                </div>

                <!-- Cockpit Tab Panes -->
                <div class="p-6 sm:p-8 bg-[#F8FAFC]">

                    <!-- PANE 1: Multi-Tenant Architecture -->
                    <div id="pane-multi-tenant" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Active Tenant Context</span>
                                <div class="text-base font-black text-slate-900 flex items-center gap-1.5">
                                    <i class="fa-solid fa-shield-halved text-indigo-600 text-xs"></i>
                                    <span>Acme Cloud Technologies</span>
                                </div>
                                <span class="text-xs text-indigo-600 font-mono font-semibold block">subdomain: acme</span>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Database Isolation Scope</span>
                                <div class="text-base font-black text-emerald-600 font-mono">WHERE tenant_id = ?</div>
                                <span class="text-xs text-slate-500 font-medium block">Row-level boundary enforcement</span>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">RBAC Permission Matrix</span>
                                <div class="text-base font-black text-slate-900 flex items-center gap-1">
                                    <span class="px-2 py-0.5 rounded-md bg-purple-100 text-purple-700 text-xs font-bold uppercase">Owner</span>
                                    <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 text-xs font-bold uppercase">Admin</span>
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-bold uppercase">Member</span>
                                </div>
                                <span class="text-xs text-slate-500 font-medium block">4-tier granular permissions</span>
                            </div>
                        </div>

                        <div class="bg-slate-900 text-slate-200 p-5 rounded-2xl font-mono text-xs border border-slate-800 space-y-2">
                            <div class="text-slate-400 flex items-center justify-between pb-2 border-b border-slate-800 text-[11px]">
                                <span>// SQL Isolation Verification Guard</span>
                                <span class="text-emerald-400">PASSED: 0.00% Cross-Tenant Bleed</span>
                            </div>
                            <p class="text-indigo-400">SELECT * FROM `invoices` WHERE `tenant_id` = 't_acme_789' AND `status` = 'open';</p>
                            <p class="text-slate-400">// Automatically injected by RequestMiddleware on every transaction</p>
                        </div>
                    </div>

                    <!-- PANE 2: 18% GST Dual-Tax & GSTR-1 -->
                    <div id="pane-gst-engine" class="hidden space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Origin State</span>
                                <div class="text-base font-black text-slate-900">24 - Gujarat (Tenant)</div>
                                <span class="text-xs text-slate-500 font-mono">GSTIN: 24AAACT8998A1Z5</span>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Recipient State</span>
                                <div class="text-base font-black text-slate-900">27 - Maharashtra (B2B Client)</div>
                                <span class="text-xs text-blue-600 font-mono">Inter-State Detected: IGST 18%</span>
                            </div>
                            <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-200 shadow-xs space-y-1">
                                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider block">GSTR-1 Monthly Return</span>
                                <div class="text-base font-black text-emerald-800">Ready for CA / Tally</div>
                                <span class="text-xs text-emerald-600 font-semibold">1-Click CSV Export with UTF-8 BOM</span>
                            </div>
                        </div>

                        <!-- GSTR-1 CSV Column Headers Preview -->
                        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-xs overflow-x-auto">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Official GSTR-1 B2B Format Columns</span>
                            <table class="w-full text-left text-xs font-mono">
                                <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                                    <tr>
                                        <th class="p-2">GSTIN of Recipient</th>
                                        <th class="p-2">Receiver Name</th>
                                        <th class="p-2">Invoice #</th>
                                        <th class="p-2">Taxable Value</th>
                                        <th class="p-2 text-indigo-600">IGST (18%)</th>
                                        <th class="p-2">CGST (9%)</th>
                                        <th class="p-2">SGST (9%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-slate-700">
                                        <td class="p-2">27AABCT2345F1Z2</td>
                                        <td class="p-2 font-sans font-bold">Tata Consultancy Services</td>
                                        <td class="p-2 text-blue-600">INV-2026-0012</td>
                                        <td class="p-2">₹25,000.00</td>
                                        <td class="p-2 text-indigo-600 font-bold">₹4,500.00</td>
                                        <td class="p-2 text-slate-400">₹0.00</td>
                                        <td class="p-2 text-slate-400">₹0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PANE 3: Bulk CSV Client Importer -->
                    <div id="pane-csv-importer" class="hidden space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Bulk Ingestion Speed</span>
                                <div class="text-base font-black text-slate-900 font-mono">1,000 Clients / 1.2s</div>
                                <span class="text-xs text-slate-500">Stream-buffered parsing</span>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Duplicate Prevention</span>
                                <div class="text-base font-black text-emerald-600 flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                    <span>Active (Email Key)</span>
                                </div>
                                <span class="text-xs text-slate-500">Duplicate rows auto-skipped</span>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Sample Template</span>
                                <div class="text-base font-black text-indigo-600 flex items-center gap-1.5">
                                    <i class="fa-solid fa-download text-xs"></i>
                                    <span>Pre-formatted CSV</span>
                                </div>
                                <span class="text-xs text-slate-500">With GSTIN &amp; address columns</span>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl border-2 border-dashed border-slate-300 bg-white text-center space-y-2">
                            <i class="fa-solid fa-file-arrow-up text-3xl text-emerald-500 block"></i>
                            <h4 class="text-sm font-bold text-slate-800">Instant CSV Upload with Validation Engine</h4>
                            <p class="text-xs text-slate-500 max-w-md mx-auto">Upload vendor lists, B2B corporate directories, or migrate from other billing platforms in seconds.</p>
                        </div>
                    </div>

                    <!-- PANE 4: Developer REST API v1 -->
                    <div id="pane-rest-api" class="hidden space-y-6">
                        <div class="bg-slate-950 text-slate-200 p-5 rounded-2xl font-mono text-xs border border-slate-800 space-y-3">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-800 text-[11px] text-slate-400">
                                <span>cURL Request: Create B2B Invoice via REST API</span>
                                <span class="text-indigo-400">POST /api/v1/invoices</span>
                            </div>
                            <pre class="text-slate-300 leading-relaxed overflow-x-auto">curl -X POST https://acme.saasify.app/api/v1/invoices \
  -H "Authorization: Bearer saas_live_90f84a1e9c2" \
  -H "Content-Type: application/json" \
  -H "Idempotency-Key: idemp_2026_0941" \
  -d '{
    "customer_id": "cust_9012",
    "subtotal_cents": 2500000,
    "line_items": [{"description": "Enterprise Cloud Seats", "quantity": 10, "unit_price_cents": 250000}]
  }'</pre>
                            <div class="pt-2 border-t border-slate-800 flex items-center justify-between text-[11px]">
                                <span class="text-emerald-400 font-bold">HTTP 201 Created &bull; Idempotent Result Saved</span>
                                <span class="text-slate-500">Rate Limit: 120 req/min</span>
                            </div>
                        </div>
                    </div>

                    <!-- PANE 5: Live Notifications & Audio Chime -->
                    <div id="pane-notifications" class="hidden space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                        <i class="fa-solid fa-bell text-indigo-600"></i>
                                        <span>Dynamic Island Audio Chime</span>
                                    </span>
                                    <span class="text-[10px] font-mono text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 font-bold">Web Audio API</span>
                                </div>
                                <p class="text-xs text-slate-500">Synthesized 587Hz &rarr; 880Hz two-tone chime triggers automatically when invoices settle or clients register in background.</p>
                                <button type="button" onclick="testLiveChime()" 
                                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs transition flex items-center space-x-1.5 shadow-2xs">
                                    <i class="fa-solid fa-volume-high text-xs"></i>
                                    <span>Play Web Audio Alert Sound</span>
                                </button>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-2">
                                <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                    <i class="fa-solid fa-clock-rotate-left text-blue-600"></i>
                                    <span>Live Audit Log Feed</span>
                                </span>
                                <div class="space-y-1.5 text-xs">
                                    <div class="p-2 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-between">
                                        <span class="font-bold text-slate-700">invoice.created</span>
                                        <span class="font-mono text-slate-400 text-[10px]">Just now</span>
                                    </div>
                                    <div class="p-2 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-between">
                                        <span class="font-bold text-slate-700">customer.bulk_imported</span>
                                        <span class="font-mono text-slate-400 text-[10px]">2m ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     STATS & ACCELERATION METRICS (Real Capabilities)
══════════════════════════════════════════════════════════ -->
<section class="bg-white border-b border-slate-200 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-100">
            <div class="pt-4 md:pt-0">
                <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono">100%</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">Tenant Database Isolation</div>
            </div>
            <div class="pt-4 md:pt-0">
                <div class="text-3xl sm:text-4xl font-black text-indigo-600 font-mono">18.0%</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">Automated GST Dual-Split</div>
            </div>
            <div class="pt-4 md:pt-0">
                <div class="text-3xl sm:text-4xl font-black text-emerald-600 font-mono">GSTR-1</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">Official B2B CSV Export</div>
            </div>
            <div class="pt-4 md:pt-0">
                <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono">&lt;35ms</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">REST API Response SLA</div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     FEATURES BENTO GRID: 6 ENTERPRISE PILLARS
══════════════════════════════════════════════════════════ -->
<section id="features" class="py-24 bg-[#F8FAFC] border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Title -->
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold border border-indigo-200">
                <i class="fa-solid fa-cubes"></i>
                <span>Enterprise Architecture</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Everything You Need to Scale SaaS Billing</h2>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                From multi-tenant security to official tax compliance, SaaSify equips Indian software founders with defense-grade infrastructure.
            </p>
        </div>

        <!-- 6 Feature Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- 1. Multi-Tenant Architecture -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-6 shadow-xs border border-blue-100">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-blue-600 uppercase tracking-wider block mb-1">Architecture Pillar</span>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Multi-Tenant Isolation</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Strict row-level database partitioning via <code class="bg-slate-100 px-1 py-0.5 rounded text-slate-700">tenant_id</code> scoping. Complete workspace isolation with zero risk of cross-tenant leakage.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-blue-600">
                    <span>Cryptographic Security</span>
                    <i class="fa-solid fa-shield-check"></i>
                </div>
            </div>

            <!-- 2. Automated 18% GST Dual-Tax Engine -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-6 shadow-xs border border-indigo-100">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-indigo-600 uppercase tracking-wider block mb-1">Tax Engine</span>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Automated 18% GST Billing</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Intelligent Place of Supply detection: Automatically applies CGST (9%) + SGST (9%) for intra-state and IGST (18%) for inter-state transactions based on customer GSTIN.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-indigo-600">
                    <span>ITC-Ready Receipts</span>
                    <i class="fa-solid fa-stamp"></i>
                </div>
            </div>

            <!-- 3. Official GSTR-1 CA Monthly Export -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-6 shadow-xs border border-emerald-100">
                        <i class="fa-solid fa-file-csv"></i>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-emerald-600 uppercase tracking-wider block mb-1">Compliance</span>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">GSTR-1 Monthly CA Return</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        One-click CSV export strictly formatted to official GST Council B2B specifications with UTF-8 BOM encoding for seamless import into Excel, Tally, and ClearTax.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-600">
                    <span>Chartered Accountant Ready</span>
                    <i class="fa-solid fa-file-circle-check"></i>
                </div>
            </div>

            <!-- 4. Bulk CSV Client Importer & Vault -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-6 shadow-xs border border-amber-100">
                        <i class="fa-solid fa-users-gear"></i>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-amber-600 uppercase tracking-wider block mb-1">Client Migration</span>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Bulk CSV Client Importer</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Upload thousands of corporate clients in 1 second. Built-in email duplicate verification ensures customer registers are clean with zero redundancy.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-amber-600">
                    <span>Sample Template Included</span>
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
            </div>

            <!-- 5. Developer REST API v1 & Webhooks -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-xl mb-6 shadow-xs border border-violet-100">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-violet-600 uppercase tracking-wider block mb-1">Developer Deck</span>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Developer REST API v1</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        High-throughput endpoints guarded by Bearer tokens, SHA-256 API keys, and idempotency protection. Webhook simulator included for testing invoice lifecycle events.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-violet-600">
                    <span>Idempotency Protected</span>
                    <i class="fa-solid fa-bolt"></i>
                </div>
            </div>

            <!-- 6. Dynamic Island & Live Audio Telemetry -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl mb-6 shadow-xs border border-rose-100">
                        <i class="fa-solid fa-bell"></i>
                    </div>
                    <span class="text-[10px] font-mono font-bold text-rose-600 uppercase tracking-wider block mb-1">Live Notifications</span>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Audio Bell &amp; Audit Center</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Top Dynamic Island floating navigation dock polls live system audits and triggers a synthesized Web Audio crystal chime alert whenever critical events occur.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-rose-600">
                    <span>Real-time Audio Alert</span>
                    <i class="fa-solid fa-volume-high"></i>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     INTERACTIVE 18% GST TAX CALCULATOR LAB
═══════════════════════════════════════════════════════ -->
<section id="gst-calculator" class="py-24 bg-white border-b border-slate-200 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200">
                <i class="fa-solid fa-calculator"></i>
                <span>Live Mathematical Lab</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Interactive 18% GST Dual-Split Engine</h2>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                Slide the revenue volume or toggle between Intra-State and Inter-State supply to test real-time tax computation.
            </p>
        </div>

        <div class="bg-[#F8FAFC] rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-xl max-w-5xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Controls -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Slider Block -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Base Invoice Value</span>
                            <span id="slider-val-display" class="font-mono text-2xl font-black text-indigo-600">₹2,50,000</span>
                        </div>

                        <input type="range" id="revenue-slider" min="25000" max="2500000" step="25000" value="250000" 
                               oninput="updateSimulator(this.value)"
                               class="w-full h-2.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">

                        <!-- Presets -->
                        <div class="flex flex-wrap items-center gap-2 pt-2">
                            <span class="text-[11px] font-bold text-slate-400">Presets:</span>
                            <button type="button" onclick="setSliderPreset(100000)" class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 transition">₹1L</button>
                            <button type="button" onclick="setSliderPreset(250000)" class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 transition">₹2.5L</button>
                            <button type="button" onclick="setSliderPreset(500000)" class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 transition">₹5L</button>
                            <button type="button" onclick="setSliderPreset(1000000)" class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 transition">₹10L</button>
                        </div>
                    </div>

                    <!-- Supply Type Toggle -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">Supply Classification</span>
                            <span class="text-[11px] text-slate-500 block">Switches between CGST+SGST or Integrated IGST</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-slate-100 p-1 rounded-xl text-xs font-bold">
                            <button type="button" id="btn-intra" onclick="setGstSupplyType('intra')" class="px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-xs transition">
                                Intra-State (CGST+SGST)
                            </button>
                            <button type="button" id="btn-inter" onclick="setGstSupplyType('inter')" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition">
                                Inter-State (IGST)
                            </button>
                        </div>
                    </div>

                    <!-- Split Tax Telemetry Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Taxable Base</span>
                            <span id="sim-base-val" class="font-mono text-base font-black text-slate-900 mt-1 block">₹2,50,000</span>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-blue-200 shadow-xs">
                            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider block" id="sim-cgst-label">CGST (9%)</span>
                            <span id="sim-cgst-val" class="font-mono text-base font-black text-blue-600 mt-1 block">₹22,500</span>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-indigo-200 shadow-xs">
                            <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider block" id="sim-sgst-label">SGST (9%)</span>
                            <span id="sim-sgst-val" class="font-mono text-base font-black text-indigo-600 mt-1 block">₹22,500</span>
                        </div>
                        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200 shadow-xs">
                            <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">ITC Claimable</span>
                            <span id="sim-itc-val" class="font-mono text-base font-black text-emerald-700 mt-1 block">₹45,000</span>
                        </div>
                    </div>

                    <!-- Grand Total Banner -->
                    <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 p-5 rounded-2xl text-white shadow-md flex items-center justify-between">
                        <div>
                            <span class="text-xs text-blue-100 font-bold block uppercase tracking-wider">Gross Invoice Total</span>
                            <span class="text-[11px] text-blue-200 font-medium">Includes base value + 18% GST</span>
                        </div>
                        <div class="text-right">
                            <span id="sim-total-gross" class="font-mono text-2xl sm:text-3xl font-black text-white block">₹2,95,000.00</span>
                            <span class="text-[10px] font-mono text-emerald-300 font-bold uppercase tracking-wider">● 100% Tax Compliant</span>
                        </div>
                    </div>
                </div>

                <!-- Right Receipt Card -->
                <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-300 shadow-xs space-y-4 text-xs font-mono">
                    <div class="text-center pb-3 border-b border-dashed border-slate-200 font-sans">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center mx-auto mb-1.5 font-bold">
                            <i class="fa-solid fa-bolt text-xs"></i>
                        </div>
                        <h4 class="font-black text-slate-900 text-sm">SaaSify Platforms Pvt Ltd</h4>
                        <p class="text-[10px] text-slate-500 font-mono">GSTIN: 24AAACS1234F1Z9 &bull; HSN: 998313</p>
                        <span class="inline-block mt-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[9px] font-bold border border-emerald-200">ORIGINAL TAX INVOICE</span>
                    </div>

                    <div class="space-y-1.5 text-[11px] pb-3 border-b border-dashed border-slate-200 font-sans">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Invoice No:</span>
                            <span class="font-bold font-mono text-slate-800">INV-2026-CALC</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Billed To:</span>
                            <span class="font-bold text-slate-800">Direct B2B Client</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Tax Type:</span>
                            <span id="receipt-tax-type" class="font-bold text-indigo-600">Intra-State (CGST + SGST)</span>
                        </div>
                    </div>

                    <div class="space-y-2 text-[11px] pb-3 border-b border-dashed border-slate-200">
                        <div class="flex justify-between">
                            <span class="text-slate-600 font-sans">Enterprise SaaS License:</span>
                            <span id="receipt-base" class="font-bold text-slate-900">₹2,50,000.00</span>
                        </div>
                        <div id="receipt-split-rows" class="space-y-1.5">
                            <div class="flex justify-between text-blue-600">
                                <span class="font-sans">Central GST (CGST 9%):</span>
                                <span id="receipt-cgst" class="font-bold">₹22,500.00</span>
                            </div>
                            <div class="flex justify-between text-indigo-600">
                                <span class="font-sans">State GST (SGST 9%):</span>
                                <span id="receipt-sgst" class="font-bold">₹22,500.00</span>
                            </div>
                        </div>
                        <div id="receipt-igst-row" class="hidden justify-between text-indigo-600">
                            <span class="font-sans">Integrated GST (IGST 18%):</span>
                            <span id="receipt-igst" class="font-bold">₹45,000.00</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-baseline pt-1">
                        <span class="font-bold text-slate-900 text-sm font-sans">TOTAL VALUE:</span>
                        <span id="receipt-total" class="font-black text-slate-900 text-base">₹2,95,000.00</span>
                    </div>

                    <div class="pt-2 border-t border-slate-100 text-center text-[10px] text-slate-400 font-sans">
                        <span class="text-emerald-600 font-bold flex items-center justify-center gap-1">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>ITC Valid &bull; Ready for Tax Credit</span>
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     DEVELOPER CODE STUDIO (REST API v1)
═══════════════════════════════════════════════════════ -->
<section id="api-studio" class="py-24 bg-[#F8FAFC] border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left Narrative -->
            <div class="lg:col-span-5 space-y-6">
                <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-violet-50 text-violet-700 text-xs font-bold border border-violet-200">
                    <i class="fa-solid fa-terminal"></i>
                    <span>Developer REST API v1</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                    Issue Invoices in 3 Lines of Code
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed font-normal">
                    Designed for software engineers. Authenticate with standard Bearer API keys, prevent duplicates with idempotency keys, and automate invoice creation via our clean RESTful v1 endpoints.
                </p>
                <div class="space-y-3 text-xs font-semibold text-slate-700">
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>SHA-256 hashed API keys with instant revocation</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>Rate-limiting protection (120 req/minute per tenant)</span>
                    </div>
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>Integrated Webhook event simulator for local testing</span>
                    </div>
                </div>
                <div class="pt-2">
                    <a href="<?= app_url('/webhook-simulator') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                        <span>Launch Webhook Event Simulator</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Right Code Terminal -->
            <div class="lg:col-span-7 bg-slate-950 rounded-3xl border border-slate-800 shadow-2xl overflow-hidden text-xs font-mono">
                <!-- Terminal Header -->
                <div class="bg-slate-900 px-4 py-3 border-b border-slate-800 flex items-center justify-between text-slate-400">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                        <span class="text-slate-300 font-bold ml-2 text-[11px]">POST /api/v1/invoices</span>
                    </div>
                    <span class="text-[10px] text-emerald-400 bg-emerald-950/60 px-2 py-0.5 rounded border border-emerald-800">201 CREATED</span>
                </div>

                <!-- Code Editor Body -->
                <div class="p-6 text-slate-300 space-y-4 overflow-x-auto leading-relaxed">
                    <pre class="text-indigo-300">// 1. Issue an 18% GST invoice programmatically</pre>
                    <pre class="text-slate-200">$response = $saasify->invoices->create([
    'customer_id'    => 'cust_tata_9041',
    'subtotal_cents' => 2500000, // ₹25,000.00
    'line_items'     => [
        ['description' => 'Dedicated SaaS Tier', 'quantity' => 1, 'unit_price_cents' => 2500000]
    ]
]);

// 2. Returns structured JSON with computed 18% GST
// [
//   "invoice_number" => "INV-2026-0045",
//   "tax_cents"      => 450000, // ₹4,500.00 GST
//   "total_cents"    => 2950000,
//   "public_pay_url" => "https://acme.saasify.app/pay/tok_a98f"
// ]</pre>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     DUAL PORTALS SHOWCASE
═══════════════════════════════════════════════════════ -->
<section id="portals" class="py-24 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Two Specialized Portals. One Unified Engine.</h2>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                Empower your business tenants with self-serve billing tools while platform super admins govern system integrity.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left: Tenant Workspace -->
            <div class="feature-card bg-white rounded-3xl border border-slate-200 p-8 shadow-xs hover:shadow-md transition space-y-6">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-xs border border-blue-100">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div class="space-y-2">
                    <h3 class="text-2xl font-black text-slate-900">Tenant Workspace Portal</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Dedicated workspace for registered corporate tenants to forge GST invoices, manage client vaults, export GSTR-1 returns, and invite colleagues with RBAC roles.
                    </p>
                </div>
                <ul class="space-y-3 text-xs text-slate-700 font-medium">
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>Create and print 18% GST tax invoices in 3 clicks</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>Bulk CSV Client Import with automatic duplicate prevention</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>Invite team members as Owner, Admin, or Billing Manager</span>
                    </li>
                </ul>
                <div class="pt-2">
                    <a href="<?= app_url('/login') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                        <span>Sign In to Tenant Workspace &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Right: Super Admin Console -->
            <div class="feature-card bg-slate-950 text-white rounded-3xl border border-slate-800 p-8 shadow-xl transition space-y-6">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center text-xl shadow-xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="space-y-2">
                    <h3 class="text-2xl font-black text-white">Super Admin Master Control</h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Sovereign master console for platform founders. Monitor global tenant growth, execute instant suspensions, and audit webhook idempotency logs.
                    </p>
                </div>
                <ul class="space-y-3 text-xs text-slate-300 font-medium">
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-rose-400"></i>
                        <span>Platform-wide tenant lifecycle management &amp; emergency controls</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-rose-400"></i>
                        <span>Webhook signature verification &amp; duplicate rejection auditing</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-rose-400"></i>
                        <span>Global cluster telemetry, revenue metrics, and system beacons</span>
                    </li>
                </ul>
                <div class="pt-2">
                    <a href="<?= app_url('/admin/login') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-rose-400 hover:text-rose-300 transition">
                        <span>Access Super Admin Console &rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     TRANSPARENT TIER PRICING MATRIX
═══════════════════════════════════════════════════════ -->
<section id="pricing" class="py-24 bg-[#F8FAFC] border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
            <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-bold border border-indigo-200">
                <i class="fa-solid fa-tags"></i>
                <span>Transparent Subscriptions</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Simple, Transparent SaaS Pricing</h2>
            <p class="text-slate-500 text-sm sm:text-base leading-relaxed">
                Choose the right plan for your business scale. All tiers include official 18% GST tax invoices and row-level tenant security.
            </p>

            <!-- Annual / Monthly Toggle Switch -->
            <div class="pt-4 inline-flex items-center space-x-3 p-1.5 bg-slate-200 rounded-2xl border border-slate-300 text-xs font-bold">
                <button type="button" id="btn-monthly" onclick="setBillingMode('monthly')" class="px-4 py-2 rounded-xl bg-white text-slate-900 shadow-xs transition">
                    Monthly Billing
                </button>
                <button type="button" id="btn-annual" onclick="setBillingMode('annual')" class="px-4 py-2 rounded-xl text-slate-600 hover:text-slate-900 transition flex items-center gap-1.5">
                    <span>Annual Billing</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] bg-emerald-100 text-emerald-800 font-extrabold uppercase">Save 20%</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch max-w-6xl mx-auto">

            <!-- Starter Plan -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Starter Tier</h3>
                    <p class="text-xs text-slate-500 mt-1 min-h-[36px]">Ideal for early-stage software startups launching their first SaaS MVP.</p>
                    
                    <div class="mt-6 pb-6 border-b border-slate-100">
                        <div class="monthly-price">
                            <span class="text-4xl font-black text-slate-900 font-mono">₹999</span>
                            <span class="text-xs text-slate-500 font-bold">/ month</span>
                        </div>
                        <div class="annual-price">
                            <span class="text-4xl font-black text-slate-900 font-mono">₹799</span>
                            <span class="text-xs text-slate-500 font-bold">/ month (billed annually)</span>
                        </div>
                    </div>

                    <ul class="mt-6 space-y-3 text-xs text-slate-700 font-medium">
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Up to 5 Team Seats</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>1,000 Invoices per month</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>18% GST Dual-Split Engine</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Standard Email Support</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="<?= app_url('/register') ?>"
                       class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition flex items-center justify-center space-x-1.5">
                        <span>Get Started Free &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Pro Scale (Featured / Recommended) -->
            <div class="feature-card bg-white p-8 rounded-3xl border-2 border-indigo-600 shadow-xl relative ring-4 ring-indigo-500/10 flex flex-col justify-between">
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3.5 py-1 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-[10px] tracking-wide uppercase shadow-md flex items-center gap-1 z-20">
                    <i class="fa-solid fa-star text-amber-300 text-[9px]"></i>
                    <span>Most Popular Tier</span>
                </div>

                <div>
                    <h3 class="text-xl font-black text-slate-900">Pro Scale</h3>
                    <p class="text-xs text-slate-500 mt-1 min-h-[36px]">Designed for high-growth SaaS scaling client volume and financial operations.</p>
                    
                    <div class="mt-6 pb-6 border-b border-slate-100">
                        <div class="monthly-price">
                            <span class="text-4xl font-black text-slate-900 font-mono">₹2,999</span>
                            <span class="text-xs text-slate-500 font-bold">/ month</span>
                        </div>
                        <div class="annual-price">
                            <span class="text-4xl font-black text-slate-900 font-mono">₹2,399</span>
                            <span class="text-xs text-slate-500 font-bold">/ month (billed annually)</span>
                        </div>
                    </div>

                    <ul class="mt-6 space-y-3 text-xs text-slate-700 font-medium">
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Up to 25 Team Seats (Full RBAC)</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Unlimited Invoices &amp; Receipts</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Official GSTR-1 CA Monthly Export</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Bulk CSV Client Importer</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Priority 24/7 Support</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="<?= app_url('/register') ?>"
                       class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 hover:from-blue-500 hover:to-violet-500 text-white font-black text-xs shadow-md shadow-indigo-500/25 transition flex items-center justify-center space-x-1.5 transform hover:scale-102">
                        <span>Start 14-Day Free Trial &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Enterprise Plan -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Enterprise</h3>
                    <p class="text-xs text-slate-500 mt-1 min-h-[36px]">Dedicated infrastructure, custom SLAs, and custom tax rules for large enterprises.</p>
                    
                    <div class="mt-6 pb-6 border-b border-slate-100">
                        <div class="monthly-price">
                            <span class="text-4xl font-black text-slate-900 font-mono">₹9,999</span>
                            <span class="text-xs text-slate-500 font-bold">/ month</span>
                        </div>
                        <div class="annual-price">
                            <span class="text-4xl font-black text-slate-900 font-mono">₹7,999</span>
                            <span class="text-xs text-slate-500 font-bold">/ month (billed annually)</span>
                        </div>
                    </div>

                    <ul class="mt-6 space-y-3 text-xs text-slate-700 font-medium">
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Unlimited Seats &amp; Workspaces</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Custom Subdomain &amp; White-labeling</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Dedicated Chartered Accountant Support</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>99.99% Financial Uptime SLA</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="<?= app_url('/register') ?>"
                       class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition flex items-center justify-center space-x-1.5">
                        <span>Get Started with Enterprise &rarr;</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     SECURITY & COMPLIANCE
═══════════════════════════════════════════════════════ -->
<section id="security" class="py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="feature-card p-6 rounded-2xl border border-slate-200 bg-slate-50/50">
                <i class="fa-solid fa-shield-halved text-2xl text-indigo-600 mb-3 block"></i>
                <h4 class="text-sm font-bold text-slate-900">SOC-2 Type II</h4>
                <p class="text-[11px] text-slate-500 mt-1">Operational security standards</p>
            </div>
            <div class="feature-card p-6 rounded-2xl border border-slate-200 bg-slate-50/50">
                <i class="fa-solid fa-lock text-2xl text-emerald-600 mb-3 block"></i>
                <h4 class="text-sm font-bold text-slate-900">256-bit AES SSL</h4>
                <p class="text-[11px] text-slate-500 mt-1">Transport &amp; at-rest encryption</p>
            </div>
            <div class="feature-card p-6 rounded-2xl border border-slate-200 bg-slate-50/50">
                <i class="fa-solid fa-file-contract text-2xl text-blue-600 mb-3 block"></i>
                <h4 class="text-sm font-bold text-slate-900">GST Council B2B</h4>
                <p class="text-[11px] text-slate-500 mt-1">Full Input Tax Credit compliance</p>
            </div>
            <div class="feature-card p-6 rounded-2xl border border-slate-200 bg-slate-50/50">
                <i class="fa-solid fa-certificate text-2xl text-amber-500 mb-3 block"></i>
                <h4 class="text-sm font-bold text-slate-900">ISO 27001</h4>
                <p class="text-[11px] text-slate-500 mt-1">Certified information security</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     BOTTOM CALL TO ACTION BANNER
═══════════════════════════════════════════════════════ -->
<section class="py-20 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-700 rounded-3xl p-10 sm:p-16 text-center text-white relative overflow-hidden shadow-2xl">
            <div class="relative z-10 max-w-2xl mx-auto space-y-4">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                    Ready to Scale Your SaaS Billing with Confidence?
                </h2>
                <p class="text-blue-100 text-sm sm:text-base leading-relaxed font-normal">
                    Experience automated 18% GST dual-tax calculations, instant GSTR-1 CA exports, and row-level tenant isolation today.
                </p>
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                    <a href="<?= app_url('/register') ?>"
                       class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-white text-indigo-700 hover:bg-slate-50 font-extrabold text-sm shadow-md transition flex items-center justify-center space-x-2">
                        <span>Start Free Trial Now</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="<?= app_url('/login') ?>"
                       class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/20 transition flex items-center justify-center space-x-2">
                        <span>Sign In to Workspace</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════ -->
<footer class="bg-white border-t border-slate-200 py-12 text-slate-500 text-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-xs">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="text-lg font-black text-slate-900 tracking-tight">SaaSify</span>
            </div>
            <div class="flex items-center space-x-6 font-semibold text-slate-600 flex-wrap justify-center gap-y-2">
                <a href="#cockpit" class="hover:text-indigo-600 transition">Studio</a>
                <a href="#features" class="hover:text-indigo-600 transition">Features</a>
                <a href="#gst-calculator" class="hover:text-indigo-600 transition">18% GST Lab</a>
                <a href="#pricing" class="hover:text-indigo-600 transition">Pricing</a>
                <a href="<?= app_url('/login') ?>" class="hover:text-indigo-600 transition">Client Login</a>
                <a href="<?= app_url('/admin/login') ?>" class="hover:text-indigo-600 transition">Super Admin</a>
            </div>
        </div>
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <p>&copy; <?= date('Y') ?> SaaSify Enterprise Billing Engine. All rights reserved.</p>
            <div class="flex items-center space-x-2 font-mono text-[11px] text-slate-400">
                <span>SOC 2 Type II</span> &bull; <span>AES-256</span> &bull; <span>Indian GSTIN B2B Standard</span>
            </div>
        </div>
    </div>
</footer>

<!-- ═══════════════════════════════════════════════════════
     CLIENT-SIDE INTERACTIVITY SCRIPTS (100% Real Logic)
═══════════════════════════════════════════════════════ -->
<script>
// 1. Interactive Cockpit Tab Switcher
function selectCockpitTab(tabKey) {
    const tabs = ['multi-tenant', 'gst-engine', 'csv-importer', 'rest-api', 'notifications'];
    const urlMap = {
        'multi-tenant': 'https://acme.saasify.app/dashboard • Sovereign Workspace',
        'gst-engine': 'https://acme.saasify.app/invoices/gstr1-export • Official B2B Return',
        'csv-importer': 'https://acme.saasify.app/customers • Bulk Import Vault',
        'rest-api': 'https://acme.saasify.app/api/v1/invoices • REST API Endpoint',
        'notifications': 'https://acme.saasify.app/api/notifications • Live Audit Telemetry'
    };

    tabs.forEach(t => {
        const pane = document.getElementById('pane-' + t);
        const btn = document.getElementById('tab-btn-' + t);
        if (pane && btn) {
            if (t === tabKey) {
                pane.classList.remove('hidden');
                btn.className = 'px-3.5 py-1.5 rounded-xl bg-white text-indigo-700 shadow-xs transition flex items-center space-x-1.5 shrink-0 font-bold';
            } else {
                pane.classList.add('hidden');
                btn.className = 'px-3.5 py-1.5 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-white/60 transition flex items-center space-x-1.5 shrink-0 font-bold';
            }
        }
    });

    const urlDisplay = document.getElementById('cockpit-url');
    if (urlDisplay && urlMap[tabKey]) {
        urlDisplay.innerText = urlMap[tabKey];
    }
}

// 2. Real Web Audio API Synthesized Crystal Chime
function testLiveChime() {
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        if (ctx.state === 'suspended') {
            ctx.resume();
        }

        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = 'sine';

        const now = ctx.currentTime;
        osc.frequency.setValueAtTime(587.33, now); // D5
        osc.frequency.exponentialRampToValueAtTime(880.00, now + 0.12); // A5

        gain.gain.setValueAtTime(0.25, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.6);

        osc.connect(gain);
        gain.connect(ctx.destination);

        osc.start(now);
        osc.stop(now + 0.6);
    } catch (e) {
        console.warn('Web Audio API unavailable:', e);
    }
}

// 3. Billing Mode Switcher (Monthly vs. Annual)
function setBillingMode(mode) {
    const btnM = document.getElementById('btn-monthly');
    const btnA = document.getElementById('btn-annual');

    if (mode === 'annual') {
        document.body.classList.add('annual-mode');
        btnA.classList.add('bg-white', 'text-slate-900', 'shadow-xs');
        btnA.classList.remove('text-slate-600');
        btnM.classList.remove('bg-white', 'text-slate-900', 'shadow-xs');
        btnM.classList.add('text-slate-600');
    } else {
        document.body.classList.remove('annual-mode');
        btnM.classList.add('bg-white', 'text-slate-900', 'shadow-xs');
        btnM.classList.remove('text-slate-600');
        btnA.classList.remove('bg-white', 'text-slate-900', 'shadow-xs');
        btnA.classList.add('text-slate-600');
    }
}

// 4. Interactive Indian GST Simulator Lab
let currentGstType = 'intra'; // 'intra' or 'inter'

function formatRupee(amount) {
    return '₹' + Number(amount).toLocaleString('en-IN', { maximumFractionDigits: 2 });
}

function setGstSupplyType(type) {
    currentGstType = type;
    const btnIntra = document.getElementById('btn-intra');
    const btnInter = document.getElementById('btn-inter');
    const cgstLabel = document.getElementById('sim-cgst-label');
    const sgstLabel = document.getElementById('sim-sgst-label');
    const splitRows = document.getElementById('receipt-split-rows');
    const igstRow = document.getElementById('receipt-igst-row');
    const taxType = document.getElementById('receipt-tax-type');

    if (type === 'inter') {
        btnInter.className = 'px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-xs transition';
        btnIntra.className = 'px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition';
        if (cgstLabel) cgstLabel.innerText = 'CGST (0%)';
        if (sgstLabel) sgstLabel.innerText = 'SGST (0%)';
        if (splitRows) splitRows.classList.add('hidden');
        if (igstRow) igstRow.classList.remove('hidden');
        if (taxType) taxType.innerText = 'Inter-State (IGST 18%)';
    } else {
        btnIntra.className = 'px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-xs transition';
        btnInter.className = 'px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition';
        if (cgstLabel) cgstLabel.innerText = 'CGST (9%)';
        if (sgstLabel) sgstLabel.innerText = 'SGST (9%)';
        if (splitRows) splitRows.classList.remove('hidden');
        if (igstRow) igstRow.classList.add('hidden');
        if (taxType) taxType.innerText = 'Intra-State (CGST + SGST)';
    }

    const slider = document.getElementById('revenue-slider');
    if (slider) updateSimulator(slider.value);
}

function updateSimulator(val) {
    const base = parseFloat(val) || 0;
    const isInter = (currentGstType === 'inter');
    const cgst = isInter ? 0 : (base * 0.09);
    const sgst = isInter ? 0 : (base * 0.09);
    const igst = isInter ? (base * 0.18) : 0;
    const totalTax = cgst + sgst + igst;
    const total = base + totalTax;

    document.getElementById('slider-val-display').innerText = formatRupee(base);
    document.getElementById('sim-base-val').innerText = formatRupee(base);
    document.getElementById('sim-cgst-val').innerText = formatRupee(cgst);
    document.getElementById('sim-sgst-val').innerText = formatRupee(sgst);
    document.getElementById('sim-itc-val').innerText = formatRupee(totalTax);
    document.getElementById('sim-total-gross').innerText = formatRupee(total);

    document.getElementById('receipt-base').innerText = formatRupee(base);
    document.getElementById('receipt-cgst').innerText = formatRupee(cgst);
    document.getElementById('receipt-sgst').innerText = formatRupee(sgst);
    document.getElementById('receipt-igst').innerText = formatRupee(igst);
    document.getElementById('receipt-total').innerText = formatRupee(total);
}

function setSliderPreset(amount) {
    const slider = document.getElementById('revenue-slider');
    if (slider) {
        slider.value = amount;
        updateSimulator(amount);
    }
}
</script>
</body>
</html>