<?php
/**
 * Home Page View — SaaSify Cyber-Kinetic Enterprise SaaS Billing Engine
 * Next-Gen Fintech Architecture with Interactive UPI Laser Beam, Biometric HUD Scanners & Live GST Simulator Lab
 * Standalone view returned directly by HomeController.
 * Compatible: PHP 7.4 / 8.2
 */
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaaSify — Cyber-Kinetic Enterprise SaaS Billing, Subscriptions &amp; GST Invoicing</title>

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
                        rzp: {
                            blue: '#0C66E4',
                            hover: '#0055CC',
                            dark: '#0A192F',
                            navy: '#0B1A30'
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

        /* ═══════════════════════════════════════════════════════
           CYBER-FINTECH KINETIC ANIMATION SYSTEM
           ═══════════════════════════════════════════════════════ */

        /* Floating badge animation */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }
        .float-badge { animation: float 4s ease-in-out infinite; }
        .float-badge-slow { animation: float 5.5s ease-in-out infinite 1s; }
        .float-badge-med  { animation: float 4.8s ease-in-out infinite 0.5s; }

        /* Biometric Laser Scanner on Cards */
        @keyframes laserSweep {
            0% { top: -10%; opacity: 0; }
            15% { opacity: 1; }
            85% { opacity: 1; }
            100% { top: 110%; opacity: 0; }
        }

        /* ═══════════════════════════════════════════════════════
           ULTRA-FAST KINETIC BOX & CARD ACCELERATOR
           ═══════════════════════════════════════════════════════ */
        .kinetic-box, .scanner-card, .pricing-card, .portal-card, .trust-card {
            position: relative;
            overflow: hidden;
            background-color: #FFFFFF;
            border: 1px solid #E2E8F0;
            transition: transform 0.18s cubic-bezier(0.16, 1, 0.3, 1), 
                        box-shadow 0.18s cubic-bezier(0.16, 1, 0.3, 1), 
                        border-color 0.15s ease !important;
            will-change: transform, box-shadow;
            cursor: pointer;
        }

        /* Fast 3D Lift & Electric Glow on Hover */
        .kinetic-box:hover, .scanner-card:hover, .pricing-card:hover, .portal-card:hover, .trust-card:hover {
            transform: translateY(-8px) scale(1.02) !important;
            border-color: #38BDF8 !important;
            box-shadow: 0 22px 42px -10px rgba(12, 102, 228, 0.22), 0 0 25px rgba(56, 189, 248, 0.25) !important;
        }

        /* Fast Active Click Pop */
        .kinetic-box:active, .scanner-card:active, .pricing-card:active, .portal-card:active {
            transform: translateY(-2px) scale(0.99) !important;
            transition-duration: 0.08s !important;
        }

        /* Dynamic Cursor Flashlight Spotlight on Cards */
        .kinetic-box::after, .scanner-card::after, .pricing-card::after, .portal-card::after, .trust-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: radial-gradient(350px circle at var(--mouse-x, -500px) var(--mouse-y, -500px), rgba(12, 102, 228, 0.15), transparent 65%);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s ease;
            z-index: 10;
        }
        .kinetic-box:hover::after, .scanner-card:hover::after, .pricing-card:hover::after, .portal-card:hover::after, .trust-card:hover::after {
            opacity: 1;
        }

        /* Fast Icon Kinetic Bounce inside Boxes */
        .box-icon {
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.2s ease;
        }
        .kinetic-box:hover .box-icon, .scanner-card:hover .box-icon, .portal-card:hover .box-icon, .pricing-card:hover .box-icon {
            transform: scale(1.18) rotate(10deg);
            box-shadow: 0 0 20px rgba(12, 102, 228, 0.45);
        }

        /* Biometric Laser Scanner on Cards */
        .scanner-card::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #0C66E4, #38BDF8, #0C66E4, transparent);
            box-shadow: 0 0 16px rgba(56, 189, 248, 0.9), 0 0 32px rgba(12, 102, 228, 0.6);
            top: -10%;
            opacity: 0;
            pointer-events: none;
            z-index: 20;
        }
        .scanner-card:hover::before {
            animation: laserSweep 1.4s ease-in-out infinite;
        }

        /* Fast Continuous Orbit Beam for Pro Scale Box */
        @keyframes borderOrbit {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .electric-border-box {
            position: relative;
            z-index: 1;
        }
        .electric-border-box::before {
            content: '';
            position: absolute;
            z-index: -1;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 1.25rem;
            background: conic-gradient(from 0deg, transparent 0%, transparent 60%, #0C66E4 80%, #38BDF8 100%);
            animation: borderOrbit 2.4s linear infinite;
        }

        /* Holographic Stamp Animation */
        @keyframes stampPop {
            0% { transform: scale(3) rotate(-25deg); opacity: 0; }
            60% { transform: scale(0.92) rotate(-12deg); opacity: 1; }
            80% { transform: scale(1.05) rotate(-14deg); }
            100% { transform: scale(1) rotate(-12deg); opacity: 1; }
        }
        .holo-stamp-active {
            animation: stampPop 0.55s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        /* Laser Beam Pulse */
        @keyframes beamFire {
            0% { width: 0%; opacity: 0; }
            20% { width: 100%; opacity: 1; }
            80% { width: 100%; opacity: 1; }
            100% { width: 100%; opacity: 0; }
        }
        .laser-beam-pulse {
            animation: beamFire 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Shockwave Ping */
        @keyframes shockwaveRing {
            0% { transform: scale(0.6); opacity: 1; border-width: 3px; }
            100% { transform: scale(2.6); opacity: 0; border-width: 1px; }
        }
        .shockwave-ring {
            animation: shockwaveRing 1s cubic-bezier(0, 0.2, 0.8, 1) forwards;
        }

        /* Radar scan wave on QR */
        @keyframes radarScan {
            0% { top: 0%; opacity: 0.8; }
            50% { opacity: 1; }
            100% { top: 100%; opacity: 0.2; }
        }
        .radar-scan-line {
            animation: radarScan 2.4s ease-in-out infinite alternate;
        }

        /* Pricing toggle */
        .annual-price  { display: none; }
        .monthly-price { display: block; }
        body.annual-mode .monthly-price { display: none; }
        body.annual-mode .annual-price  { display: block; }

        /* Thermal receipt paper weave */
        .thermal-receipt {
            background: #FFFFFF;
            background-image: radial-gradient(#E2E8F0 1px, transparent 1px);
            background-size: 10px 10px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.05);
        }

        /* Custom Slider Styling */
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            height: 22px;
            width: 22px;
            border-radius: 9999px;
            background: #0C66E4;
            cursor: pointer;
            box-shadow: 0 0 15px rgba(12, 102, 228, 0.6);
            border: 3px solid #FFFFFF;
        }
        input[type=range]::-moz-range-thumb {
            height: 22px;
            width: 22px;
            border-radius: 9999px;
            background: #0C66E4;
            cursor: pointer;
            box-shadow: 0 0 15px rgba(12, 102, 228, 0.6);
            border: 3px solid #FFFFFF;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased selection:bg-[#0C66E4] selection:text-white overflow-x-hidden">

<?php if ($flash = flash('success') ?? flash('error')): ?>
    <!-- Flash message -->
    <div class="fixed top-24 right-6 z-[9999] max-w-sm w-full">
        <div class="p-4 rounded-xl bg-white border border-slate-200 text-slate-800 text-sm flex items-start space-x-3 shadow-xl">
            <i class="fa-solid fa-circle-info text-[#0C66E4] text-lg mt-0.5 shrink-0"></i>
            <div class="font-medium"><?= e($flash) ?></div>
        </div>
    </div>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════════
     STICKY NAVIGATION (Razorpay Executive White Header)
══════════════════════════════════════════════════════════ -->
<header id="main-nav" class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 transition-all duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-[72px]">

            <!-- Logo -->
            <a href="<?= app_url('/') ?>" class="flex items-center space-x-2.5 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0C66E4] to-indigo-600 flex items-center justify-center text-white shadow-xs group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-bolt text-base"></i>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="text-2xl font-black text-slate-900 tracking-tight">SaaSify</span>
                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-md bg-blue-50 text-[#0C66E4] border border-blue-200">
                        Fintech
                    </span>
                </div>
            </a>

            <!-- Desktop nav links -->
            <nav class="hidden md:flex items-center space-x-8 text-sm font-semibold text-slate-600">
                <a href="#features" class="hover:text-[#0C66E4] transition">Features</a>
                <a href="#simulator" class="hover:text-[#0C66E4] text-[#0C66E4] transition flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0C66E4] animate-pulse"></span>
                    <span>GST Lab</span>
                </a>
                <a href="#pricing"  class="hover:text-[#0C66E4] transition">Pricing</a>
                <a href="#portals"  class="hover:text-[#0C66E4] transition">Dual Portals</a>
                <a href="#security" class="hover:text-[#0C66E4] transition">Compliance</a>
            </nav>

            <!-- Auth buttons -->
            <div class="flex items-center space-x-3">
                <?php if (is_logged_in()): ?>
                    <?php $u = auth_user(); ?>
                    <a href="<?= app_url('/dashboard') ?>"
                       class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#0C66E4] hover:bg-[#0055CC] shadow-xs transition">
                        <i class="fa-solid fa-gauge-high text-xs"></i>
                        <span>Dashboard (<?= e($u['name'] ?? 'Workspace') ?>)</span>
                    </a>
                    <a href="<?= app_url('/logout') ?>"
                       class="text-xs text-rose-600 hover:text-rose-700 font-semibold px-2 py-1 transition" title="Sign Out">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </a>
                <?php else: ?>
                    <a href="<?= app_url('/login') ?>"
                       class="hidden sm:inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition">
                        <i class="fa-solid fa-building text-xs text-[#0C66E4]"></i>
                        <span>Client Login</span>
                    </a>
                    <a href="<?= app_url('/admin/login') ?>"
                       class="hidden md:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-rose-700 border border-rose-200 bg-rose-50 hover:bg-rose-100 transition">
                        <i class="fa-solid fa-shield-halved text-[11px]"></i>
                        <span>Super Admin</span>
                    </a>
                    <a href="<?= app_url('/register') ?>"
                       class="inline-flex items-center space-x-1.5 px-4 sm:px-5 py-2.5 rounded-xl bg-[#0C66E4] hover:bg-[#0055CC] text-white text-xs sm:text-sm font-bold shadow-xs transition">
                        <span>Start Free Trial</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<!-- ═══════════════════════════════════════════════════════
     HERO SECTION WITH KINETIC UPI LASER BEAM REACTOR
══════════════════════════════════════════════════════════ -->
<section class="relative bg-gradient-to-b from-[#F8FAFC] via-[#FFFFFF] to-[#F1F5F9] pt-16 pb-24 overflow-hidden border-b border-slate-200">

    <!-- Decorative ambient glows -->
    <div class="absolute top-12 left-1/2 -translate-x-1/2 w-[700px] h-[300px] bg-blue-100/50 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative w-full">
        <div class="text-center max-w-4xl mx-auto">

            <!-- Pill badge -->
            <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-xs font-bold text-[#0C66E4] mb-6 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-[#0C66E4] animate-pulse"></span>
                <span>✦ India's Leading Full-Stack SaaS Billing &amp; Invoicing Engine</span>
            </div>

            <!-- H1 -->
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.15]">
                Automate Subscriptions, Invoicing &amp; <span class="text-[#0C66E4]">18% GST Compliance</span> at Scale
            </h1>

            <!-- Subtext -->
            <p class="mt-6 text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto font-normal">
                Engineered for Indian high-growth software teams. Automated CGST/SGST breakdowns, instant UPI QR &amp; NetBanking checkout, cryptographically scoped tenant isolation, and webhook idempotency.
            </p>

            <!-- CTAs -->
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                <a href="<?= app_url('/register') ?>"
                   class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-[#0C66E4] hover:bg-[#0055CC] text-white font-extrabold text-sm shadow-xs transition flex items-center justify-center space-x-2">
                    <span>Start 14-Day Free Trial</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>

                <a href="#simulator"
                   class="w-full sm:w-auto px-7 py-3.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-bold text-sm shadow-xs transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-calculator text-[#0C66E4] text-xs"></i>
                    <span>Try Interactive GST Lab</span>
                </a>
            </div>

            <!-- Mini trust signals -->
            <div class="mt-8 flex flex-wrap items-center justify-center gap-6 text-xs text-slate-500 font-medium">
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>No Credit Card Required</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>Instant UPI &amp; Cards Enabled</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>Full Indian GST Input Tax Credit</span>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════
             REVOLUTIONARY CYBER-FINTECH PAYMENT REACTOR MOCKUP
             ═══════════════════════════════════════════════════════ -->
        <div class="mt-14 max-w-5xl mx-auto relative">

            <!-- Floating telemetry badges -->
            <div class="float-badge absolute -top-5 -left-4 hidden lg:flex items-center space-x-2.5 bg-white border border-emerald-200 rounded-xl px-4 py-2.5 shadow-md text-xs font-bold text-slate-800 z-20">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0 animate-pulse"></span>
                <span id="hero-badge-amount" class="text-emerald-700 font-mono">+₹4,999.00</span>
                <span class="text-slate-500">Payment Settled via UPI</span>
            </div>

            <div class="float-badge-slow absolute -bottom-4 -left-4 hidden lg:flex items-center space-x-2 bg-white border border-blue-200 rounded-xl px-4 py-2.5 shadow-md text-xs font-bold text-slate-800 z-20">
                <i class="fa-solid fa-shield-halved text-[#0C66E4]"></i>
                <span class="text-slate-700">Tenant Isolation: <span class="text-[#0C66E4]">Active (AES-256)</span></span>
            </div>

            <div class="float-badge-med absolute -top-5 -right-4 hidden lg:flex items-center space-x-2 bg-white border border-slate-200 rounded-xl px-4 py-2.5 shadow-md text-xs font-bold text-slate-800 z-20">
                <i class="fa-solid fa-certificate text-amber-500"></i>
                <span class="text-slate-700">18% GST Invoice Digitally Signed</span>
            </div>

            <!-- Browser mockup frame -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl overflow-hidden relative">
                <!-- Browser chrome header -->
                <div class="bg-slate-50 px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                    </div>
                    <div class="px-4 py-1 rounded-md bg-white border border-slate-200 text-slate-600 text-xs font-mono font-medium max-w-sm truncate text-center flex items-center gap-1.5">
                        <i class="fa-solid fa-lock text-[10px] text-emerald-600"></i>
                        <span>https://acme.saasify.app/dashboard &bull; Live Ledger Reactor</span>
                    </div>
                    <div class="flex items-center space-x-1.5 text-xs text-emerald-700 font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>● NPCI LIVE FEED</span>
                    </div>
                </div>

                <!-- Preview Content with Dual-Terminal Kinetic Pipeline -->
                <div class="p-6 sm:p-8 bg-[#F8FAFC] space-y-6 relative overflow-hidden">

                    <!-- Kinetic Laser Beam Track between Phone and Ledger -->
                    <div id="laser-beam-track" class="absolute top-[48%] left-[26%] right-[26%] h-1 bg-blue-100 rounded-full hidden md:block pointer-events-none z-10 overflow-hidden">
                        <div id="laser-pulse-particle" class="h-full w-24 bg-gradient-to-r from-transparent via-[#38BDF8] to-[#0C66E4] rounded-full shadow-[0_0_12px_#38BDF8] hidden"></div>
                    </div>

                    <!-- Split Reactor View: Left Guest Phone / Right Master Ledger -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-stretch relative">

                        <!-- Left Panel (Col 1-4): Guest UPI Smartphone Pod -->
                        <div class="md:col-span-4 bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between relative overflow-hidden">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                                    <span class="text-xs font-black uppercase text-slate-800 tracking-wider">UPI Fast-Rail</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-[#0C66E4]">Dynamic QR</span>
                            </div>

                            <!-- Interactive QR Code Simulator -->
                            <div class="my-4 p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center relative overflow-hidden group">
                                <!-- Radar scanning line -->
                                <div class="radar-scan-line absolute inset-x-0 h-1 bg-gradient-to-r from-transparent via-[#0C66E4] to-transparent shadow-[0_0_8px_#0C66E4] pointer-events-none z-10"></div>
                                
                                <div class="w-24 h-24 bg-white p-2 rounded-xl border border-slate-200 flex items-center justify-center shadow-xs">
                                    <i class="fa-solid fa-qrcode text-5xl text-slate-800"></i>
                                </div>
                                <span class="text-[10px] font-mono text-slate-500 mt-2 font-bold flex items-center gap-1">
                                    <span>GPay</span> &bull; <span>PhonePe</span> &bull; <span>Paytm</span>
                                </span>
                            </div>

                            <!-- Simulation Trigger Button -->
                            <div>
                                <button type="button" onclick="triggerPaymentBlast()" 
                                        class="w-full py-2.5 px-3 rounded-xl bg-gradient-to-r from-[#0C66E4] to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-black text-xs shadow-md shadow-blue-500/20 transition flex items-center justify-center space-x-2 transform active:scale-95">
                                    <i class="fa-solid fa-bolt-lightning text-amber-300"></i>
                                    <span>Simulate Instant UPI Tap</span>
                                </button>
                                <span class="text-[10px] text-slate-400 text-center block mt-1.5 font-medium">Auto-fires every 5 seconds</span>
                            </div>
                        </div>

                        <!-- Right Panel (Col 5-12): Central Financial Reactor & Invoices Ledger -->
                        <div class="md:col-span-8 bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between relative overflow-hidden">
                            
                            <!-- Shockwave Target Indicator Container -->
                            <div id="shockwave-target" class="absolute top-4 right-4 w-12 h-12 rounded-full pointer-events-none flex items-center justify-center">
                                <div id="shockwave-ring" class="w-full h-full rounded-full border-2 border-emerald-500 opacity-0 pointer-events-none"></div>
                            </div>

                            <!-- Top Metrics Row -->
                            <div>
                                <div class="grid grid-cols-3 gap-3 mb-5">
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block">Total Invoiced</span>
                                        <span id="hero-total-invoiced" class="text-base sm:text-lg font-black text-slate-900 font-mono mt-0.5 block transition-colors duration-300">₹1,48,250.00</span>
                                    </div>
                                    <div class="bg-blue-50/50 p-3 rounded-xl border border-blue-200">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#0C66E4] block">Settled (Live)</span>
                                        <span id="hero-total-settled" class="text-base sm:text-lg font-black text-[#0C66E4] font-mono mt-0.5 block transition-colors duration-300">₹1,24,000.00</span>
                                    </div>
                                    <div class="bg-emerald-50/50 p-3 rounded-xl border border-emerald-200">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block">18% ITC Saved</span>
                                        <span id="hero-itc-credit" class="text-base sm:text-lg font-black text-emerald-600 font-mono mt-0.5 block">₹22,320.00</span>
                                    </div>
                                </div>

                                <!-- Live Invoice Row with Animated Holographic Stamp -->
                                <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 relative overflow-hidden">
                                    <!-- The Holographic Wax Stamp -->
                                    <div id="hologram-stamp" class="absolute top-2 right-4 pointer-events-none z-20">
                                        <div class="px-3 py-1 rounded border-2 border-emerald-600 text-emerald-700 font-mono font-black text-xs tracking-wider uppercase bg-emerald-50/90 shadow-sm flex items-center gap-1.5 rotate-[-12deg]">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                            <span>PAID &bull; 18% GST</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between text-xs pb-2 border-b border-slate-200/80">
                                        <div class="flex items-center space-x-2">
                                            <span id="hero-live-inv-no" class="font-mono font-black text-[#0C66E4]">INV-2026-0043</span>
                                            <span id="hero-live-client" class="font-bold text-slate-800">Tata Consultancy Services Ltd</span>
                                        </div>
                                        <span class="text-[10px] font-mono text-slate-400">GSTIN: 27AABCT2345F1Z2</span>
                                    </div>

                                    <div class="mt-3 flex items-center justify-between text-xs">
                                        <div class="flex items-center space-x-4">
                                            <div>
                                                <span class="text-[10px] text-slate-400 block font-semibold">Taxable Base</span>
                                                <span id="hero-live-base" class="font-mono font-bold text-slate-700">₹6,499.00</span>
                                            </div>
                                            <div>
                                                <span class="text-[10px] text-slate-400 block font-semibold">CGST+SGST (18%)</span>
                                                <span id="hero-live-tax" class="font-mono font-bold text-slate-700">₹1,169.82</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[10px] text-slate-400 block font-semibold">Gross Settled</span>
                                            <span id="hero-live-gross" class="font-mono font-black text-emerald-700 text-sm">₹7,668.82</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Telemetry Status Ticker -->
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-mono">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span id="hero-telemetry-text">Webhook signature verified (sha256:4f8e...9a)</span>
                                </div>
                                <span class="font-bold text-[#0C66E4]">0.00% DUPLICATES</span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     STATS BAR (Full Width Trust Banner)
══════════════════════════════════════════════════════════ -->
<section class="bg-white border-b border-slate-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-100">
            <div class="pt-4 md:pt-0">
                <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono">500+</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">Indian SaaS Companies</div>
            </div>
            <div class="pt-4 md:pt-0">
                <div class="text-3xl sm:text-4xl font-black text-[#0C66E4] font-mono">₹2.4Cr+</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">Monthly MRR Processed</div>
            </div>
            <div class="pt-4 md:pt-0">
                <div class="text-3xl sm:text-4xl font-black text-emerald-600 font-mono">99.99%</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">Platform Uptime SLA</div>
            </div>
            <div class="pt-4 md:pt-0">
                <div class="text-3xl sm:text-4xl font-black text-slate-900 font-mono">&lt;45ms</div>
                <div class="text-xs sm:text-sm font-semibold text-slate-500 mt-1">Webhook Ingestion Latency</div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     FEATURES SECTION WITH BIOMETRIC HUD LASER SCANNERS
══════════════════════════════════════════════════════════ -->
<section id="features" class="py-24 bg-[#F8FAFC] border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 text-[#0C66E4] text-xs font-bold mb-3 border border-blue-200">
                <i class="fa-solid fa-cube"></i>
                <span>Enterprise Architecture</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Engineered with Defense-Grade Precision</h2>
            <p class="text-slate-500 text-sm sm:text-base mt-3 leading-relaxed">
                Every component is built for Indian financial compliance, cryptographic isolation, and zero double-billing guarantees.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Card 1: Multi-Tenant Row Isolation -->
            <div class="scanner-card group p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="box-icon w-12 h-12 rounded-xl bg-blue-50 border border-blue-200 text-[#0C66E4] flex items-center justify-center text-xl shadow-xs">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <span class="font-mono text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">SYS // AES-256</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-[#0C66E4] transition-colors duration-150">Multi-Tenant Row Isolation</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-normal">
                        Every query and write operation is cryptographically scoped to the authenticated tenant. Zero risk of cross-organization data leakage.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-[#0C66E4]">
                    <span>Hardware Scoped DB Isolation</span>
                    <i class="fa-solid fa-shield-check text-xs group-hover:translate-x-1.5 transition-transform duration-150"></i>
                </div>
            </div>

            <!-- Card 2: Instant UPI & Cards Checkout -->
            <div class="scanner-card group p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="box-icon w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <span class="font-mono text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">NPCI // UPI 2.0</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-emerald-700 transition-colors duration-150">Instant UPI &amp; Cards Checkout</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-normal">
                        Native guest checkout links supporting dynamic UPI QR codes (GPay, PhonePe, Paytm, BHIM), Indian NetBanking, and credit/debit cards.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-700">
                    <span>99.8% Payment Success Rate</span>
                    <i class="fa-solid fa-bolt text-xs group-hover:translate-x-1.5 transition-transform duration-150"></i>
                </div>
            </div>

            <!-- Card 3: GST-Compliant Invoicing -->
            <div class="scanner-card group p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="box-icon w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center text-xl shadow-xs">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <span class="font-mono text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">GSTN // HSN 998313</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors duration-150">Automated 18% GST Invoicing</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-normal">
                        Full compliance with Indian tax law. Automatic calculation of CGST (9%) and SGST (9%), customer GSTIN capture, and printable tax receipts for ITC.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-indigo-700">
                    <span>ITC-Ready Tax Invoices</span>
                    <i class="fa-solid fa-stamp text-xs group-hover:translate-x-1.5 transition-transform duration-150"></i>
                </div>
            </div>

            <!-- Card 4: Webhook Idempotency Engine -->
            <div class="scanner-card group p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="box-icon w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-xl shadow-xs">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <span class="font-mono text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">IDEMPOTENT // OK</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-amber-700 transition-colors duration-150">Webhook Idempotency Engine</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-normal">
                        Ingestion pipeline validates incoming webhook signatures and discards duplicate events via unique event keys. Zero double charges.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-amber-800">
                    <span>Zero Duplicate Charge SLA</span>
                    <i class="fa-solid fa-circle-check text-xs group-hover:translate-x-1.5 transition-transform duration-150"></i>
                </div>
            </div>

            <!-- Card 5: Role-Based Access Control -->
            <div class="scanner-card group p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="box-icon w-12 h-12 rounded-xl bg-purple-50 border border-purple-200 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <span class="font-mono text-[10px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200">RBAC // 4-TIER</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-purple-700 transition-colors duration-150">Role-Based Access Control</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-normal">
                        Granular permissions for Owners, Admins, Billing Managers, and Members. Safeguard sensitive company financial records and plans.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-purple-700">
                    <span>Hierarchical Sovereign Clearance</span>
                    <i class="fa-solid fa-key text-xs group-hover:translate-x-1.5 transition-transform duration-150"></i>
                </div>
            </div>

            <!-- Card 6: Real-Time Telemetry & Reports -->
            <div class="scanner-card group p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="box-icon w-12 h-12 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center text-xl shadow-xs">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-50 px-2 py-0.5 rounded border border-rose-200">SPEED // &lt;45MS</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2 group-hover:text-rose-700 transition-colors duration-150">Real-Time Financial Telemetry</h3>
                    <p class="text-xs text-slate-500 leading-relaxed font-normal">
                        Live charts for Monthly Recurring Revenue (MRR), collection ratios, receivables aging, and exportable CSV audit trails.
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-rose-700">
                    <span>Instant Business Velocity</span>
                    <i class="fa-solid fa-arrow-trend-up text-xs group-hover:translate-x-1.5 transition-transform duration-150"></i>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     INTERACTIVE LIVE GST & CASHFLOW SIMULATOR LAB
══════════════════════════════════════════════════════════ -->
<section id="simulator" class="py-24 bg-white border-b border-slate-200 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 text-[#0C66E4] text-xs font-bold mb-3 border border-blue-200">
                <i class="fa-solid fa-flask-vial"></i>
                <span>Interactive Cashflow Lab</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Test Your Indian GST &amp; Revenue Velocity</h2>
            <p class="text-slate-500 text-sm sm:text-base mt-3 leading-relaxed">
                Drag the interactive slider below to see how SaaSify automatically computes CGST, SGST, and uncovers total Input Tax Credit (ITC) for your B2B customers.
            </p>
        </div>

        <!-- The Interactive Simulator Console -->
        <div class="bg-[#F8FAFC] rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-xl max-w-5xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column (Controls & Live Telemetry Dials) -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Slider Block -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Monthly SaaS Revenue Volume</span>
                            <span id="slider-val-display" class="font-mono text-2xl font-black text-[#0C66E4]">₹2,50,000</span>
                        </div>

                        <!-- Interactive Range Slider -->
                        <input type="range" id="revenue-slider" min="25000" max="2500000" step="25000" value="250000" 
                               oninput="updateSimulator(this.value)"
                               class="w-full h-2.5 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#0C66E4]">

                        <!-- Preset Quick Chips -->
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <span class="text-[11px] font-bold text-slate-400 mr-1">Presets:</span>
                            <button type="button" onclick="setSliderPreset(100000)" class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-[#0C66E4] transition border border-slate-200">₹1L</button>
                            <button type="button" onclick="setSliderPreset(250000)" class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-[#0C66E4] transition border border-slate-200">₹2.5L</button>
                            <button type="button" onclick="setSliderPreset(500000)" class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-[#0C66E4] transition border border-slate-200">₹5L</button>
                            <button type="button" onclick="setSliderPreset(1000000)" class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-[#0C66E4] transition border border-slate-200">₹10L</button>
                            <button type="button" onclick="setSliderPreset(2500000)" class="px-2.5 py-1 rounded-lg text-xs font-bold font-mono bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-[#0C66E4] transition border border-slate-200">₹25L</button>
                        </div>
                    </div>

                    <!-- Split Tax Telemetry Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Taxable Base</span>
                            <span id="sim-base-val" class="font-mono text-base font-black text-slate-900 mt-1 block">₹2,50,000</span>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-blue-200 shadow-xs">
                            <span class="text-[10px] font-bold text-[#0C66E4] uppercase tracking-wider block">CGST (9%)</span>
                            <span id="sim-cgst-val" class="font-mono text-base font-black text-[#0C66E4] mt-1 block">₹22,500</span>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-indigo-200 shadow-xs">
                            <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider block">SGST (9%)</span>
                            <span id="sim-sgst-val" class="font-mono text-base font-black text-indigo-600 mt-1 block">₹22,500</span>
                        </div>
                        <div class="bg-emerald-50/70 p-4 rounded-xl border border-emerald-300 shadow-xs">
                            <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">ITC Saved</span>
                            <span id="sim-itc-val" class="font-mono text-base font-black text-emerald-700 mt-1 block">₹45,000</span>
                        </div>
                    </div>

                    <!-- Grand Total Banner -->
                    <div class="bg-gradient-to-r from-[#0C66E4] to-indigo-700 p-5 rounded-2xl text-white shadow-md flex items-center justify-between">
                        <div>
                            <span class="text-xs text-blue-100 font-bold block uppercase tracking-wider">Total Invoiced to B2B Customers</span>
                            <span class="text-[11px] text-blue-200 font-medium">Includes base subscription + mandatory 18% GST</span>
                        </div>
                        <div class="text-right">
                            <span id="sim-total-gross" class="font-mono text-2xl sm:text-3xl font-black tracking-tight text-white block">₹2,95,000.00</span>
                            <span class="text-[10px] font-mono text-emerald-300 font-bold uppercase tracking-wider">● 100% Tax Compliant</span>
                        </div>
                    </div>

                </div>

                <!-- Right Column (Live Thermal Digital Tax Invoice Preview) -->
                <div class="lg:col-span-5">
                    <div class="thermal-receipt p-6 rounded-2xl border border-slate-300 relative text-xs">
                        
                        <!-- Top Receipt Header -->
                        <div class="text-center pb-4 border-b border-dashed border-slate-300">
                            <div class="w-8 h-8 rounded-lg bg-[#0C66E4] text-white flex items-center justify-center mx-auto mb-2 text-sm font-bold shadow-xs">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <h4 class="font-black text-slate-900 text-sm">SaaSify Platforms Pvt Ltd</h4>
                            <p class="text-[10px] font-mono text-slate-500 mt-0.5">GSTIN: 27AAACS1234F1Z9 &bull; HSN: 998313</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded text-[9px] font-bold font-mono border border-emerald-200">ORIGINAL TAX INVOICE</span>
                        </div>

                        <!-- Customer & Dates -->
                        <div class="py-3 border-b border-dashed border-slate-300 space-y-1 text-[11px]">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Invoice No:</span>
                                <span class="font-mono font-bold text-slate-800">INV-2026-LIVE</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Billed To:</span>
                                <span class="font-semibold text-slate-800">Acme Cloud Systems Ltd</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Customer GSTIN:</span>
                                <span class="font-mono font-bold text-slate-700">27AABCA9876C1Z4</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Payment Mode:</span>
                                <span class="font-semibold text-emerald-600">Instant UPI QR (Settled)</span>
                            </div>
                        </div>

                        <!-- Dynamic Line Item breakdown -->
                        <div class="py-3 border-b border-dashed border-slate-300 space-y-2 text-[11px]">
                            <div class="flex justify-between">
                                <span class="text-slate-600 font-medium">Enterprise SaaS License:</span>
                                <span id="receipt-base" class="font-mono font-bold text-slate-900">₹2,50,000.00</span>
                            </div>
                            <div class="flex justify-between text-blue-600">
                                <span>Central GST (CGST 9%):</span>
                                <span id="receipt-cgst" class="font-mono font-bold">₹22,500.00</span>
                            </div>
                            <div class="flex justify-between text-indigo-600">
                                <span>State GST (SGST 9%):</span>
                                <span id="receipt-sgst" class="font-mono font-bold">₹22,500.00</span>
                            </div>
                        </div>

                        <!-- Receipt Totals -->
                        <div class="pt-3 pb-2 flex justify-between items-baseline">
                            <span class="font-bold text-slate-900 text-sm">TOTAL SETTLED:</span>
                            <span id="receipt-total" class="font-mono font-black text-slate-900 text-base">₹2,95,000.00</span>
                        </div>

                        <!-- Bottom Digital Signature -->
                        <div class="mt-4 pt-3 border-t border-slate-200 text-center text-[10px] text-slate-400 font-mono">
                            <p class="truncate">SHA256: e8b94129a0f...87cd31</p>
                            <p class="text-emerald-600 font-bold mt-0.5">Digitally Signed &bull; ITC Certified</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     PRICING SECTION (Clean White Cards + Toggle)
══════════════════════════════════════════════════════════ -->
<section id="pricing" class="py-24 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-50 text-[#0C66E4] text-xs font-bold mb-3 border border-blue-200">
                <i class="fa-solid fa-tag"></i>
                <span>Transparent Plans</span>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Simple, Transparent SaaS Pricing</h2>
            <p class="text-slate-500 text-sm sm:text-base mt-3 leading-relaxed">
                Choose the right plan for your business scale. All tiers include official 18% GST tax invoices and row-level tenant security.
            </p>

            <!-- Annual / Monthly Toggle Switch -->
            <div class="mt-8 inline-flex items-center space-x-3 p-1.5 bg-slate-100 rounded-xl border border-slate-200 text-xs font-bold">
                <button type="button" id="btn-monthly" onclick="setBillingMode('monthly')" class="px-4 py-2 rounded-lg bg-white text-slate-900 shadow-xs transition">
                    Monthly Billing
                </button>
                <button type="button" id="btn-annual" onclick="setBillingMode('annual')" class="px-4 py-2 rounded-lg text-slate-600 hover:text-slate-900 transition flex items-center gap-1.5">
                    <span>Annual Billing</span>
                    <span class="px-1.5 py-0.5 rounded text-[10px] bg-emerald-100 text-emerald-800 font-extrabold uppercase">Save 20%</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch max-w-6xl mx-auto">

            <!-- Starter Plan -->
            <div class="pricing-card kinetic-box group p-8 flex flex-col justify-between shadow-xs rounded-2xl bg-white">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-[#0C66E4] transition-colors duration-150">Starter Tier</h3>
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
                            <span>18% GST Calculation Engine</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Standard Email Support</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="<?= app_url('/register') ?>"
                       class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition flex items-center justify-center space-x-1.5 group-hover:shadow-md">
                        <span>Get Started Free &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Pro Scale (Featured / Recommended) -->
            <div class="pricing-card kinetic-box electric-border-box group p-8 flex flex-col justify-between shadow-lg relative ring-4 ring-[#0C66E4]/20 rounded-2xl border-2 border-[#0C66E4] bg-white">
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3.5 py-1 rounded-full bg-[#0C66E4] text-white font-bold text-[10px] tracking-wide uppercase shadow-md flex items-center gap-1 z-20">
                    <i class="fa-solid fa-star text-amber-300 text-[9px]"></i>
                    <span>Most Popular Tier</span>
                </div>

                <div>
                    <h3 class="text-xl font-black text-slate-900 group-hover:text-[#0C66E4] transition-colors duration-150">Pro Scale</h3>
                    <p class="text-xs text-slate-500 mt-1 min-h-[36px]">Designed for high-growth SaaS scaling client volume and financial operations.</p>
                    
                    <div class="mt-6 pb-6 border-b border-slate-100">
                        <div class="monthly-price">
                            <span class="text-4xl font-black text-slate-900 font-mono">₹4,999</span>
                            <span class="text-xs text-slate-500 font-bold">/ month</span>
                        </div>
                        <div class="annual-price">
                            <span class="text-4xl font-black text-slate-900 font-mono">₹3,999</span>
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
                            <span>Instant UPI QR &amp; Cards Checkout</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Automated Webhook Idempotency</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Priority 24/7 Support</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="<?= app_url('/register') ?>"
                       class="w-full py-3 px-4 rounded-xl bg-[#0C66E4] hover:bg-[#0055CC] text-white font-black text-xs shadow-md shadow-blue-500/25 transition flex items-center justify-center space-x-1.5 transform group-hover:scale-102">
                        <span>Start 14-Day Free Trial &rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Enterprise Plan -->
            <div class="pricing-card kinetic-box group p-8 flex flex-col justify-between shadow-xs rounded-2xl bg-white">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-[#0C66E4] transition-colors duration-150">Enterprise</h3>
                    <p class="text-xs text-slate-500 mt-1 min-h-[36px]">Dedicated infrastructure, custom SLAs, and custom payment rails for enterprises.</p>
                    
                    <div class="mt-6 pb-6 border-b border-slate-100">
                        <div class="monthly-price">
                            <span class="text-4xl font-black text-slate-900 font-mono">₹14,999</span>
                            <span class="text-xs text-slate-500 font-bold">/ month</span>
                        </div>
                        <div class="annual-price">
                            <span class="text-4xl font-black text-slate-900 font-mono">₹11,999</span>
                            <span class="text-xs text-slate-500 font-bold">/ month (billed annually)</span>
                        </div>
                    </div>

                    <ul class="mt-6 space-y-3 text-xs text-slate-700 font-medium">
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Unlimited Seats &amp; Organizations</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Custom Subdomain &amp; White-labeling</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Dedicated Account Manager</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>99.99% Financial Uptime SLA</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <a href="<?= app_url('/register') ?>"
                       class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition flex items-center justify-center space-x-1.5 group-hover:shadow-md">
                        <span>Contact Enterprise Sales &rarr;</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     DUAL PORTALS SHOWCASE
══════════════════════════════════════════════════════════ -->
<section id="portals" class="py-24 bg-[#F8FAFC] border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Two Specialized Portals. One Unified Platform.</h2>
            <p class="text-slate-500 text-sm sm:text-base mt-3 leading-relaxed">
                Provide client organizations with self-serve billing tools while platform operators govern everything through master control.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left: Client Workspace -->
            <div class="portal-card kinetic-box group bg-white rounded-2xl border border-slate-200 p-8 shadow-xs hover:shadow-md transition">
                <div class="box-icon w-12 h-12 rounded-xl bg-blue-50 text-[#0C66E4] flex items-center justify-center text-xl mb-6 shadow-xs">
                    <i class="fa-solid fa-building"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2 group-hover:text-[#0C66E4] transition-colors duration-150">Customer Organization Workspace</h3>
                <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                    Dedicated workspace portal for registered tenants to manage customer databases, issue GST invoices, track receivables, and configure team members.
                </p>
                <ul class="space-y-3 text-xs text-slate-700 font-medium mb-8">
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>Create and send 18% GST invoices in 3 clicks</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>Real-time payment link sharing via WhatsApp and email</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>Invite colleagues with Owner, Admin, or Billing Manager roles</span>
                    </li>
                </ul>
                <a href="<?= app_url('/login') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-[#0C66E4] hover:underline">
                    <span>Sign in to Client Workspace</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1.5 transition-transform duration-150"></i>
                </a>
            </div>

            <!-- Right: Super Admin Console -->
            <div class="portal-card kinetic-box group bg-[#0B1A30] text-white rounded-2xl border border-slate-800 p-8 shadow-xs hover:shadow-md transition">
                <div class="box-icon w-12 h-12 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center text-xl mb-6 shadow-xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-2 group-hover:text-rose-300 transition-colors duration-150">Super Admin Operations Console</h3>
                <p class="text-xs text-slate-300 mb-6 leading-relaxed">
                    Sovereign master control for platform operators. Monitor global platform MRR, execute tenant suspensions, and inspect live webhook idempotency logs.
                </p>
                <ul class="space-y-3 text-xs text-slate-300 font-medium mb-8">
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-rose-400"></i>
                        <span>Platform-wide tenant lifecycle management &amp; emergency lockouts</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-rose-400"></i>
                        <span>Webhook signature verification &amp; duplicate rejection auditing</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-rose-400"></i>
                        <span>Real-time cluster telemetry and system health beacons</span>
                    </li>
                </ul>
                <a href="<?= app_url('/admin/login') ?>" class="inline-flex items-center gap-2 text-xs font-bold text-rose-300 hover:text-white hover:underline">
                    <span>Access Super Admin Console</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1.5 transition-transform duration-150"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     SECURITY & COMPLIANCE
══════════════════════════════════════════════════════════ -->
<section id="security" class="py-16 bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="trust-card kinetic-box group p-6 rounded-2xl border border-slate-200 bg-slate-50/50">
                <i class="box-icon fa-solid fa-shield-halved text-2xl text-[#0C66E4] mb-3 block"></i>
                <h4 class="text-sm font-bold text-slate-900 group-hover:text-[#0C66E4] transition-colors duration-150">SOC-2 Type II</h4>
                <p class="text-[11px] text-slate-500 mt-1">Certified operational security</p>
            </div>
            <div class="trust-card kinetic-box group p-6 rounded-2xl border border-slate-200 bg-slate-50/50">
                <i class="box-icon fa-solid fa-lock text-2xl text-emerald-600 mb-3 block"></i>
                <h4 class="text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors duration-150">256-bit SSL</h4>
                <p class="text-[11px] text-slate-500 mt-1">Bank-grade transport encryption</p>
            </div>
            <div class="trust-card kinetic-box group p-6 rounded-2xl border border-slate-200 bg-slate-50/50">
                <i class="box-icon fa-solid fa-file-contract text-2xl text-purple-600 mb-3 block"></i>
                <h4 class="text-sm font-bold text-slate-900 group-hover:text-purple-600 transition-colors duration-150">Indian GST &amp; IT Act</h4>
                <p class="text-[11px] text-slate-500 mt-1">Fully legal &amp; ITC compliant</p>
            </div>
            <div class="trust-card kinetic-box group p-6 rounded-2xl border border-slate-200 bg-slate-50/50">
                <i class="box-icon fa-solid fa-certificate text-2xl text-amber-500 mb-3 block"></i>
                <h4 class="text-sm font-bold text-slate-900 group-hover:text-amber-500 transition-colors duration-150">ISO 27001</h4>
                <p class="text-[11px] text-slate-500 mt-1">Information security certified</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     BOTTOM CTA BANNER (Razorpay Royal Blue)
══════════════════════════════════════════════════════════ -->
<section class="py-20 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-[#0C66E4] rounded-3xl p-10 sm:p-16 text-center text-white relative overflow-hidden shadow-xl">
            <div class="relative z-10 max-w-2xl mx-auto space-y-4">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight">
                    Ready to Scale Your SaaS Billing in India?
                </h2>
                <p class="text-blue-100 text-sm sm:text-base leading-relaxed font-normal">
                    Join hundreds of Indian software companies saving dozens of hours every month with automated subscriptions and 18% GST tax invoicing.
                </p>
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                    <a href="<?= app_url('/register') ?>"
                       class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white text-[#0C66E4] hover:bg-slate-50 font-extrabold text-sm shadow-md transition flex items-center justify-center space-x-2">
                        <span>Start 14-Day Free Trial</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    <a href="<?= app_url('/login') ?>"
                       class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/20 transition flex items-center justify-center space-x-2">
                        <span>Sign In to Workspace</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     FOOTER (Clean Slate)
══════════════════════════════════════════════════════════ -->
<footer class="bg-white border-t border-slate-200 py-12 text-slate-500 text-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-8 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-[#0C66E4] flex items-center justify-center text-white font-bold text-sm shadow-xs">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="text-lg font-black text-slate-900 tracking-tight">SaaSify</span>
            </div>
            <div class="flex items-center space-x-6 font-semibold text-slate-600">
                <a href="#features" class="hover:text-slate-900 transition">Features</a>
                <a href="#simulator" class="hover:text-slate-900 transition">GST Simulator</a>
                <a href="#pricing"  class="hover:text-slate-900 transition">Pricing</a>
                <a href="#portals"  class="hover:text-slate-900 transition">Portals</a>
                <a href="<?= app_url('/login') ?>" class="hover:text-slate-900 transition">Client Login</a>
                <a href="<?= app_url('/admin/login') ?>" class="hover:text-slate-900 transition">Super Admin</a>
            </div>
        </div>
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>&copy; <?= date('Y') ?> SaaSify Platforms Pvt Ltd. Built on the Razorpay standard. All rights reserved.</p>
            <div class="flex items-center space-x-2 font-mono text-[11px] text-slate-400">
                <span>SOC 2 Type II</span> &bull; <span>256-bit SSL</span> &bull; <span>Indian GST Compliant</span>
            </div>
        </div>
    </div>
</footer>

<!-- ═══════════════════════════════════════════════════════
     KINETIC SCRIPTS: PAYMENT REACTOR & GST SIMULATOR LAB
══════════════════════════════════════════════════════════ -->
<script>
// 1. Annual / Monthly Billing Toggle
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

// 2. Interactive GST & Cashflow Simulator Logic
function formatRupee(amount) {
    return '₹' + Number(amount).toLocaleString('en-IN', { maximumFractionDigits: 2 });
}

function updateSimulator(val) {
    const base = parseFloat(val) || 0;
    const cgst = base * 0.09;
    const sgst = base * 0.09;
    const total = base + cgst + sgst;
    const itc = cgst + sgst; // Total tax credit client can claim

    // Update Slider Value Label
    document.getElementById('slider-val-display').innerText = formatRupee(base);

    // Update Telemetry Grid
    document.getElementById('sim-base-val').innerText = formatRupee(base);
    document.getElementById('sim-cgst-val').innerText = formatRupee(cgst);
    document.getElementById('sim-sgst-val').innerText = formatRupee(sgst);
    document.getElementById('sim-itc-val').innerText = formatRupee(itc);
    document.getElementById('sim-total-gross').innerText = formatRupee(total);

    // Update Thermal Receipt Preview
    document.getElementById('receipt-base').innerText = formatRupee(base);
    document.getElementById('receipt-cgst').innerText = formatRupee(cgst);
    document.getElementById('receipt-sgst').innerText = formatRupee(sgst);
    document.getElementById('receipt-total').innerText = formatRupee(total);
}

function setSliderPreset(amount) {
    const slider = document.getElementById('revenue-slider');
    slider.value = amount;
    updateSimulator(amount);
}

// 3. Live Holographic UPI Laser Beam & Payment Reactor Pipeline
const simulatedClients = [
    { name: "Tata Consultancy Services Ltd", gstin: "27AABCT2345F1Z2", amount: 6499 },
    { name: "Infosys BPM India Pvt Ltd", gstin: "29AABCI1234D1Z8", amount: 8999 },
    { name: "Wipro Technologies Ltd", gstin: "29AABCW5678E1Z1", amount: 4999 },
    { name: "Zomato Media Pvt Ltd", gstin: "07AABCZ9999K1Z4", amount: 14999 },
    { name: "Swiggy Bundl Technologies", gstin: "29AABCB3333F1Z7", amount: 11999 }
];

let simIndex = 0;
let baseRevenue = 148250;
let settledRevenue = 124000;
let invoiceCounter = 44;

function triggerPaymentBlast() {
    const client = simulatedClients[simIndex % simulatedClients.length];
    simIndex++;

    const baseVal = client.amount;
    const taxVal = baseVal * 0.18;
    const grossVal = baseVal + taxVal;

    // 1. Fire Laser Pulse
    const pulse = document.getElementById('laser-pulse-particle');
    if (pulse) {
        pulse.classList.remove('hidden', 'laser-beam-pulse');
        void pulse.offsetWidth; // Trigger reflow
        pulse.classList.add('laser-beam-pulse');
    }

    // 2. Shockwave & Settlement Reaction after laser arrives
    setTimeout(() => {
        // Shockwave Ring
        const ring = document.getElementById('shockwave-ring');
        if (ring) {
            ring.classList.remove('shockwave-ring', 'opacity-0');
            void ring.offsetWidth;
            ring.classList.add('shockwave-ring');
        }

        // Increment Revenue Counters
        baseRevenue += grossVal;
        settledRevenue += grossVal;
        const itcCredit = settledRevenue * 0.18 / 1.18;

        document.getElementById('hero-total-invoiced').innerText = formatRupee(baseRevenue);
        document.getElementById('hero-total-settled').innerText = formatRupee(settledRevenue);
        document.getElementById('hero-itc-credit').innerText = formatRupee(itcCredit);
        document.getElementById('hero-badge-amount').innerText = '+' + formatRupee(grossVal);

        // Flash Color
        const settledEl = document.getElementById('hero-total-settled');
        settledEl.classList.add('text-emerald-500');
        setTimeout(() => settledEl.classList.remove('text-emerald-500'), 800);

        // Update Live Invoice Details
        invoiceCounter++;
        document.getElementById('hero-live-inv-no').innerText = 'INV-2026-00' + invoiceCounter;
        document.getElementById('hero-live-client').innerText = client.name;
        document.getElementById('hero-live-base').innerText = formatRupee(baseVal);
        document.getElementById('hero-live-tax').innerText = formatRupee(taxVal);
        document.getElementById('hero-live-gross').innerText = formatRupee(grossVal);

        // Hologram Stamp Pop
        const stamp = document.getElementById('hologram-stamp');
        if (stamp) {
            stamp.classList.remove('holo-stamp-active');
            void stamp.offsetWidth;
            stamp.classList.add('holo-stamp-active');
        }

        // Telemetry Text
        const hash = Math.random().toString(36).substring(2, 8);
        document.getElementById('hero-telemetry-text').innerText = 'UPI Ref #' + Math.floor(100000000000 + Math.random() * 900000000000) + ' &bull; Hash: ' + hash + ' verified';
    }, 450);
}

// Auto-run the payment blast every 5 seconds
setInterval(triggerPaymentBlast, 5000);

// 4. Ultra-Fast Real-Time Cursor Spotlight Tracking on All Kinetic Boxes
document.addEventListener('mousemove', (e) => {
    const boxes = document.querySelectorAll('.kinetic-box, .scanner-card, .pricing-card, .portal-card, .trust-card');
    boxes.forEach(box => {
        const rect = box.getBoundingClientRect();
        if (
            e.clientX >= rect.left - 40 &&
            e.clientX <= rect.right + 40 &&
            e.clientY >= rect.top - 40 &&
            e.clientY <= rect.bottom + 40
        ) {
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            box.style.setProperty('--mouse-x', `${x}px`);
            box.style.setProperty('--mouse-y', `${y}px`);
        }
    });
}, { passive: true });
</script>

</body>
</html>