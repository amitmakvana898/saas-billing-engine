<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'SaaSify - Cyber-Kinetic Enterprise Billing Cockpit') ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js for Live Financial Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        .rzp-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 1.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .rzp-card:hover {
            border-color: #CBD5E1;
            box-shadow: 0 6px 16px 0 rgba(0, 0, 0, 0.05);
        }

        .cmd-item.active-cmd, .cmd-item:hover {
            background: #EFF6FF;
            color: #1D4ED8;
        }

        /* Ambient Island Glow Animation */
        @keyframes islandPulse {
            0%, 100% { box-shadow: 0 20px 50px rgba(0,0,0,0.5), 0 0 25px rgba(59,130,246,0.18); }
            50% { box-shadow: 0 22px 55px rgba(0,0,0,0.55), 0 0 40px rgba(99,102,241,0.28); }
        }
        .dynamic-island-glow {
            animation: islandPulse 4s ease-in-out infinite;
        }

        /* ═══════════════════════════════════════════════════════
           PERMANENT STICKY DYNAMIC ISLAND (ALWAYS FULLY VISIBLE)
           ═══════════════════════════════════════════════════════ */
        #floatingDynamicIsland {
            transition: all 0.25s ease;
            max-width: 82rem;
            width: calc(100% - 1.5rem);
        }

        #islandInner {
            transition: all 0.25s ease;
        }

        /* When scrolled, add deeper shadow and sleek glassmorphism border */
        #floatingDynamicIsland.is-scrolled #islandInner {
            background: rgba(11, 15, 25, 0.98) !important;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.7), 0 0 25px rgba(59, 130, 246, 0.25) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        /* Circle orb and dock close button are disabled so menu never collapses */
        #islandCircleOrb,
        #islandDockCloseBtn {
            display: none !important;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased min-h-screen flex flex-col selection:bg-blue-600 selection:text-white relative">

    <?php 
        $currentUri = $_SERVER['REQUEST_URI'] ?? '';
        $tenant = current_tenant();
        $user = auth_user();
        $role = $user['role'] ?? 'member';

        if (!function_exists('is_active')) {
            function is_active(string $path, string $currentUri): bool {
                return str_contains($currentUri, $path);
            }
        }

        // Active page identification for the compact side pill
        $activePageName = 'Dashboard';
        if (is_active('invoices', $currentUri) && !is_active('create', $currentUri)) $activePageName = 'Invoices';
        elseif (is_active('create', $currentUri)) $activePageName = 'Forge Invoice';
        elseif (is_active('customers', $currentUri)) $activePageName = 'Clients Vault';
        elseif (is_active('plans', $currentUri)) $activePageName = 'Plans & Tiers';
        elseif (is_active('team', $currentUri)) $activePageName = 'Team & RBAC';
        elseif (is_active('settings', $currentUri)) $activePageName = 'Settings';
    ?>

    <!-- ═══════════════════════════════════════════════════════
         REVOLUTIONARY SCROLL-MORPHING DYNAMIC ISLAND (ORB DOCK)
         ═══════════════════════════════════════════════════════ -->
    <header id="floatingDynamicIsland" class="fixed top-3 sm:top-4 inset-x-0 mx-auto max-w-7xl z-50 px-2 sm:px-4 pointer-events-none">
        <div id="islandInner" class="pointer-events-auto bg-[#0B0F19]/95 backdrop-blur-2xl border border-white/15 rounded-full px-3 sm:px-4 py-2 dynamic-island-glow flex items-center justify-between gap-1.5 sm:gap-2 ring-1 ring-white/10 hover:ring-blue-500/40 flex-nowrap whitespace-nowrap overflow-visible">
            
            <!-- 0. THE SMALL CIRCLE ORB (Appears ONLY when scrolled down & collapsed) -->
            <div id="islandCircleOrb" class="hidden relative w-full h-full items-center justify-center text-white select-none cursor-pointer" title="Quick Navigation (Hover or Click to open)">
                <i class="fa-solid fa-bolt-lightning text-white text-base"></i>
                <!-- Glowing Green Operational Status Beacon -->
                <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-emerald-400 border-2 border-[#0B0F19] shadow-sm animate-ping"></span>
                <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-emerald-400 border-2 border-[#0B0F19] shadow-sm"></span>
            </div>

            <!-- 1. Left: Ambient Brand Mark & Workspace Telemetry -->
            <div id="islandBrandLeft" class="flex items-center space-x-2 shrink-0">
                <a href="<?= app_url('/dashboard') ?>" class="flex items-center space-x-2 group">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 via-indigo-600 to-violet-600 flex items-center justify-center text-white shadow-md shadow-blue-500/30 group-hover:scale-105 transition-transform font-black text-sm">
                        <i class="fa-solid fa-bolt-lightning text-xs"></i>
                    </div>
                    <span class="hidden md:inline-block font-black text-white text-sm tracking-tight">SaaSify</span>
                </a>

                <!-- Workspace Live Capsule -->
                <div id="islandWorkspaceText" class="hidden 2xl:flex items-center space-x-1.5 px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-slate-300 text-[11px] font-mono font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    <span class="truncate max-w-[110px]"><?= e($tenant['name'] ?? 'Workspace') ?></span>
                </div>
            </div>

            <!-- 2. Center: Kinetic Navigation Portals -->
            <nav id="islandNavLinks" class="hidden lg:flex items-center space-x-0.5 xl:space-x-1 text-xs font-bold transition-all duration-300 shrink-0">
                <a href="<?= app_url('/dashboard') ?>" 
                   class="px-2.5 xl:px-3 py-1.5 rounded-full transition flex items-center space-x-1.5 <?= is_active('dashboard', $currentUri) ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 text-white shadow-md shadow-blue-500/30 font-extrabold' : 'text-slate-400 hover:text-white hover:bg-white/10' ?>">
                    <i class="fa-solid fa-chart-pie text-[11px] <?= is_active('dashboard', $currentUri) ? 'text-white' : 'text-slate-400' ?>"></i>
                    <span>Dashboard</span>
                </a>

                <a href="<?= app_url('/invoices') ?>" 
                   class="px-2.5 xl:px-3 py-1.5 rounded-full transition flex items-center space-x-1.5 <?= (is_active('invoices', $currentUri) && !is_active('create', $currentUri)) ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 text-white shadow-md shadow-blue-500/30 font-extrabold' : 'text-slate-400 hover:text-white hover:bg-white/10' ?>">
                    <i class="fa-solid fa-file-invoice text-[11px] <?= (is_active('invoices', $currentUri) && !is_active('create', $currentUri)) ? 'text-white' : 'text-slate-400' ?>"></i>
                    <span>Invoices</span>
                </a>

                <a href="<?= app_url('/customers') ?>" 
                   class="px-2.5 xl:px-3 py-1.5 rounded-full transition flex items-center space-x-1.5 <?= is_active('customers', $currentUri) ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 text-white shadow-md shadow-blue-500/30 font-extrabold' : 'text-slate-400 hover:text-white hover:bg-white/10' ?>">
                    <i class="fa-solid fa-users text-[11px] <?= is_active('customers', $currentUri) ? 'text-white' : 'text-slate-400' ?>"></i>
                    <span>Clients</span>
                </a>

                <a href="<?= app_url('/plans') ?>" 
                   class="px-2.5 xl:px-3 py-1.5 rounded-full transition flex items-center space-x-1.5 <?= is_active('plans', $currentUri) ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 text-white shadow-md shadow-blue-500/30 font-extrabold' : 'text-slate-400 hover:text-white hover:bg-white/10' ?>">
                    <i class="fa-solid fa-tags text-[11px] <?= is_active('plans', $currentUri) ? 'text-white' : 'text-slate-400' ?>"></i>
                    <span>Plans</span>
                </a>

                <a href="<?= app_url('/team') ?>" 
                   class="px-2.5 xl:px-3 py-1.5 rounded-full transition flex items-center space-x-1.5 <?= is_active('team', $currentUri) ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 text-white shadow-md shadow-blue-500/30 font-extrabold' : 'text-slate-400 hover:text-white hover:bg-white/10' ?>">
                    <i class="fa-solid fa-user-shield text-[11px] <?= is_active('team', $currentUri) ? 'text-white' : 'text-slate-400' ?>"></i>
                    <span>Team</span>
                </a>

                <a href="<?= app_url('/settings') ?>" 
                   class="px-2.5 xl:px-3 py-1.5 rounded-full transition flex items-center space-x-1.5 <?= is_active('settings', $currentUri) ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 text-white shadow-md shadow-blue-500/30 font-extrabold' : 'text-slate-400 hover:text-white hover:bg-white/10' ?>">
                    <i class="fa-solid fa-sliders text-[11px] <?= is_active('settings', $currentUri) ? 'text-white' : 'text-slate-400' ?>"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <!-- 3. Right: Spotlight Command, Fast Forge Action, Profile & Collapse Button -->
            <div id="islandActions" class="flex items-center space-x-1.5 sm:space-x-2 shrink-0 transition-all duration-300">
                <!-- Command Palette Trigger (⌘K) -->
                <button type="button" onclick="openCommandPalette()" 
                        class="px-2.5 sm:px-3 py-1.5 rounded-full bg-white/5 hover:bg-white/15 text-slate-300 hover:text-white text-xs font-medium border border-white/10 transition flex items-center space-x-1.5 shadow-2xs"
                        title="Search Command Deck (Ctrl + K)">
                    <i class="fa-solid fa-magnifying-glass text-[10px] text-blue-400"></i>
                    <span class="hidden sm:inline text-[11px] font-mono">⌘K</span>
                </button>

                <!-- Notification Center Bell -->
                <div class="relative" id="notifCenterContainer">
                    <button type="button" id="notifBellBtn" onclick="toggleNotifDrawer()" 
                            class="relative p-1.5 sm:p-2 rounded-full bg-white/5 hover:bg-white/15 text-slate-300 hover:text-white border border-white/10 transition flex items-center justify-center shadow-2xs"
                            title="Live Activity & Audit Logs">
                        <i class="fa-regular fa-bell text-xs"></i>
                        <span id="notifBadge" class="hidden absolute -top-1 -right-1 min-w-[16px] h-4 px-1 bg-rose-500 text-white rounded-full text-[9px] font-black flex items-center justify-center border border-slate-900 animate-pulse">0</span>
                    </button>

                    <!-- Notifications Dropdown Flyout -->
                    <div id="notifDrawer" class="hidden absolute right-0 mt-3 w-80 sm:w-96 bg-[#0E131F] border border-white/15 rounded-2xl shadow-2xl shadow-black/80 backdrop-blur-2xl z-50 overflow-hidden text-left">
                        <div class="p-3.5 border-b border-white/10 flex items-center justify-between bg-white/[0.02]">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-bell text-blue-400 text-xs"></i>
                                <span class="text-xs font-bold text-white tracking-wide">Live Audit Telemetry</span>
                                <span id="notifCountPill" class="text-[10px] font-mono px-1.5 py-0.5 rounded-full bg-blue-500/20 text-blue-400 font-bold border border-blue-500/30">0</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" id="notifMarkAllReadBtn" onclick="markAllNotificationsAsRead()" class="text-[11px] text-blue-400 hover:text-blue-300 font-medium transition flex items-center space-x-1" title="Mark all as read">
                                    <i class="fa-solid fa-check-double text-[10px]"></i>
                                    <span class="hidden sm:inline">Mark read</span>
                                </button>
                                <button type="button" onclick="refreshNotifications(true)" class="text-[11px] text-slate-400 hover:text-blue-400 transition" title="Refresh Feed">
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                </button>
                            </div>
                        </div>
                        <div id="notifList" class="max-h-72 overflow-y-auto divide-y divide-white/5 text-xs">
                            <div class="p-4 text-center text-slate-500 text-xs">Loading activity feed...</div>
                        </div>
                        <div class="p-2.5 border-t border-white/10 bg-white/[0.02] flex items-center justify-between px-3">
                            <span class="text-[10px] text-slate-500 flex items-center space-x-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                                <span>Real-time Audio Alert Synced</span>
                            </span>
                            <span id="unreadCountSummary" class="text-[10px] text-slate-400 font-mono">0 unread</span>
                        </div>
                    </div>
                </div>

                <!-- Fast Forge Invoice Capsule -->
                <a href="<?= app_url('/invoices/create') ?>" 
                   class="px-3 py-1.5 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-400 hover:to-indigo-500 text-white font-black text-xs shadow-md shadow-blue-500/30 transition flex items-center space-x-1.5 transform hover:scale-105">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span class="hidden sm:inline">Forge</span>
                </a>

                <!-- User Profile & Integrated Logout Station (Strictly inside black pill) -->
                <div class="flex items-center space-x-1.5 pl-2 border-l border-white/10 shrink-0">
                    <!-- User Name & Avatar (Clearly visible with crisp typography) -->
                    <div class="flex items-center space-x-2 py-1 px-2.5 rounded-full bg-white/10 border border-white/15 shadow-xs" 
                         title="<?= e($user['name'] ?? 'User') ?> (<?= strtoupper($role) ?>)">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-600 text-white font-black text-[11px] flex items-center justify-center shrink-0 shadow-xs">
                            <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <span class="text-xs font-bold text-white max-w-[120px] sm:max-w-[140px] truncate inline-block">
                            <?= e($user['name'] ?? 'User') ?>
                        </span>
                    </div>

                    <!-- Clear, High-Contrast Logout Button -->
                    <a href="<?= app_url('/logout') ?>" 
                       class="px-2.5 sm:px-3 py-1.5 rounded-full bg-rose-500/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 hover:border-rose-500 transition-all duration-200 flex items-center space-x-1.5 text-xs font-bold shrink-0 shadow-xs group" 
                       title="Sign Out">
                        <i class="fa-solid fa-arrow-right-from-bracket text-xs group-hover:-translate-x-0.5 transition-transform duration-200"></i>
                        <span>Logout</span>
                    </a>
                </div>

                <!-- Close Button (Appears only when expanded in docked state) -->
                <button id="islandDockCloseBtn" type="button" class="p-1.5 text-slate-400 hover:text-white rounded-full hover:bg-white/10 transition" title="Collapse back to Small Circle">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>

                <!-- Mobile Drawer Trigger -->
                <button onclick="toggleMobileNav()" class="lg:hidden p-1.5 text-slate-300 hover:text-white rounded-full hover:bg-white/10 transition">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════════════════════════════
         MOBILE BOTTOM ERGONOMIC DYNAMIC DOCK (For Phones)
         ═══════════════════════════════════════════════════════ -->
    <nav id="mobileDynamicDock" class="fixed bottom-4 inset-x-4 z-50 lg:hidden pointer-events-auto bg-[#0B0F19]/95 backdrop-blur-2xl border border-white/15 rounded-3xl p-2 shadow-[0_15px_40px_rgba(0,0,0,0.6)] flex items-center justify-around text-white">
        <a href="<?= app_url('/dashboard') ?>" class="flex flex-col items-center py-1 px-2 rounded-2xl transition <?= is_active('dashboard', $currentUri) ? 'text-blue-400 font-extrabold' : 'text-slate-400 hover:text-white' ?>">
            <i class="fa-solid fa-chart-pie text-sm"></i>
            <span class="text-[9px] mt-0.5">Home</span>
        </a>
        <a href="<?= app_url('/invoices') ?>" class="flex flex-col items-center py-1 px-2 rounded-2xl transition <?= (is_active('invoices', $currentUri) && !is_active('create', $currentUri)) ? 'text-blue-400 font-extrabold' : 'text-slate-400 hover:text-white' ?>">
            <i class="fa-solid fa-file-invoice text-sm"></i>
            <span class="text-[9px] mt-0.5">Bills</span>
        </a>
        <a href="<?= app_url('/invoices/create') ?>" class="w-11 h-11 -mt-5 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/40 border-2 border-[#0B0F19] transition transform active:scale-95">
            <i class="fa-solid fa-plus text-sm"></i>
        </a>
        <a href="<?= app_url('/customers') ?>" class="flex flex-col items-center py-1 px-2 rounded-2xl transition <?= is_active('customers', $currentUri) ? 'text-blue-400 font-extrabold' : 'text-slate-400 hover:text-white' ?>">
            <i class="fa-solid fa-users text-sm"></i>
            <span class="text-[9px] mt-0.5">Clients</span>
        </a>
        <button onclick="toggleMobileNav()" class="flex flex-col items-center py-1 px-2 rounded-2xl text-slate-400 hover:text-white">
            <i class="fa-solid fa-ellipsis text-sm"></i>
            <span class="text-[9px] mt-0.5">More</span>
        </button>
    </nav>

    <!-- Mobile Drawer for small devices (Expanded) -->
    <div id="mobileDrawer" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden lg:hidden">
        <div class="fixed inset-y-0 left-0 w-72 bg-[#0B0F19] text-white p-6 flex flex-col justify-between shadow-2xl border-r border-white/10">
            <div>
                <div class="flex items-center justify-between pb-5 border-b border-white/10">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow">
                            <i class="fa-solid fa-bolt-lightning text-xs"></i>
                        </div>
                        <div>
                            <span class="text-base font-black text-white block leading-tight">SaaSify</span>
                            <span class="text-[10px] text-blue-400 font-bold font-mono uppercase tracking-wider"><?= e($tenant['name'] ?? '') ?></span>
                        </div>
                    </div>
                    <button onclick="toggleMobileNav()" class="text-slate-400 hover:text-white p-1.5">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <nav class="mt-6 space-y-2 text-xs font-bold">
                    <a href="<?= app_url('/dashboard') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl transition <?= is_active('dashboard', $currentUri) ? 'bg-white/15 text-white border border-white/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' ?>">
                        <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= app_url('/invoices') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl transition <?= is_active('invoices', $currentUri) ? 'bg-white/15 text-white border border-white/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' ?>">
                        <i class="fa-solid fa-file-invoice w-4 text-center"></i>
                        <span>Invoices Hub</span>
                    </a>
                    <a href="<?= app_url('/invoices/create') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl transition text-white bg-blue-600/30 border border-blue-500/40">
                        <i class="fa-solid fa-plus-circle w-4 text-center text-blue-400"></i>
                        <span>+ Forge Invoice</span>
                    </a>
                    <a href="<?= app_url('/customers') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl transition <?= is_active('customers', $currentUri) ? 'bg-white/15 text-white border border-white/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' ?>">
                        <i class="fa-solid fa-users w-4 text-center"></i>
                        <span>Client Vault</span>
                    </a>
                    <a href="<?= app_url('/plans') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl transition <?= is_active('plans', $currentUri) ? 'bg-white/15 text-white border border-white/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' ?>">
                        <i class="fa-solid fa-tags w-4 text-center"></i>
                        <span>Subscription Plans</span>
                    </a>
                    <a href="<?= app_url('/team') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl transition <?= is_active('team', $currentUri) ? 'bg-white/15 text-white border border-white/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' ?>">
                        <i class="fa-solid fa-user-shield w-4 text-center"></i>
                        <span>Team &amp; RBAC</span>
                    </a>
                    <a href="<?= app_url('/settings') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl transition <?= is_active('settings', $currentUri) ? 'bg-white/15 text-white border border-white/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' ?>">
                        <i class="fa-solid fa-sliders w-4 text-center"></i>
                        <span>Organization Settings</span>
                    </a>
                </nav>
            </div>

            <div class="pt-5 border-t border-white/10 space-y-3">
                <div class="flex items-center space-x-3 px-3 py-2 rounded-2xl bg-white/5 border border-white/10">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-600 text-white font-black text-xs flex items-center justify-center shadow-xs">
                        <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-xs font-bold text-white truncate"><?= e($user['name'] ?? 'User') ?></div>
                        <div class="text-[10px] text-slate-400 font-mono uppercase tracking-wider"><?= strtoupper($role) ?></div>
                    </div>
                </div>
                <a href="<?= app_url('/logout') ?>" 
                   class="w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-2xl bg-rose-500/20 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 font-bold text-xs transition-all shadow-md shadow-rose-950/40">
                    <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                    <span>Sign Out / Logout</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         MAIN WORKSPACE CONTENT CANVAS (With Top Island Offset)
         ═══════════════════════════════════════════════════════ -->
    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 sm:pt-28 pb-24 sm:pb-12">
        <!-- Flash Alerts -->
        <?php if ($success = flash('success')): ?>
            <div class="mb-6 p-4 rounded-3xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start space-x-3 shadow-xs">
                <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-base"></i>
                <div class="text-xs font-semibold leading-relaxed"><?= e($success) ?></div>
            </div>
        <?php endif; ?>

        <?php if ($error = flash('error')): ?>
            <div class="mb-6 p-4 rounded-3xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start space-x-3 shadow-xs">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 mt-0.5 text-base"></i>
                <div class="text-xs font-semibold leading-relaxed"><?= e($error) ?></div>
            </div>
        <?php endif; ?>

        <?php if ($info = flash('info')): ?>
            <div class="mb-6 p-4 rounded-3xl bg-blue-50 border border-blue-200 text-blue-900 flex items-start space-x-3 shadow-xs">
                <i class="fa-solid fa-circle-info text-blue-600 mt-0.5 text-base"></i>
                <div class="text-xs font-semibold leading-relaxed"><?= e($info) ?></div>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <!-- ═══════════════════════════════════════════════════════
         SPOTLIGHT COMMAND PALETTE MODAL (Ctrl + K)
         ═══════════════════════════════════════════════════════ -->
    <div id="cmdPaletteModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden flex items-start justify-center pt-16 sm:pt-24 px-4 overflow-y-auto" onclick="handleBackdropClick(event)">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl max-w-2xl w-full overflow-hidden text-slate-800" onclick="event.stopPropagation()">
            
            <!-- Command Input Box -->
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center space-x-3">
                <i class="fa-solid fa-magnifying-glass text-blue-600 text-base"></i>
                <input type="text" id="cmdInput" 
                       placeholder="Type a command or jump to page (e.g. 'invoice', 'create', 'plans', 'team')..." 
                       class="w-full bg-transparent text-slate-900 text-sm font-semibold outline-none placeholder-slate-400">
                <kbd class="px-2 py-1 rounded-xl bg-slate-100 text-slate-600 font-mono text-[11px] font-bold border border-slate-200">ESC</kbd>
            </div>

            <!-- Command List -->
            <div class="p-3 max-h-96 overflow-y-auto space-y-4" id="cmdList">
                <!-- Section: Quick Actions -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Instant Actions</span>
                    <div class="space-y-1">
                        <a href="<?= app_url('/invoices/create') ?>" class="cmd-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold text-slate-700 hover:text-blue-700 transition">
                            <div class="flex items-center space-x-3">
                                <span class="w-7 h-7 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-200"><i class="fa-solid fa-plus text-xs"></i></span>
                                <span>Forge New Tax Invoice</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">Jump <i class="fa-solid fa-arrow-right text-[9px]"></i></span>
                        </a>

                        <a href="<?= app_url('/customers') ?>" class="cmd-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold text-slate-700 hover:text-blue-700 transition">
                            <div class="flex items-center space-x-3">
                                <span class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200"><i class="fa-solid fa-user-plus text-xs"></i></span>
                                <span>Register New Customer</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">Jump <i class="fa-solid fa-arrow-right text-[9px]"></i></span>
                        </a>

                        <a href="<?= app_url('/invoices/export') ?>" class="cmd-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold text-slate-700 hover:text-blue-700 transition">
                            <div class="flex items-center space-x-3">
                                <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center border border-slate-200"><i class="fa-solid fa-file-csv text-xs"></i></span>
                                <span>Export GST Ledger (CSV)</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">Download <i class="fa-solid fa-download text-[9px]"></i></span>
                        </a>
                    </div>
                </div>

                <!-- Section: Navigation Jumps -->
                <div>
                    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Navigation Portals</span>
                    <div class="space-y-1">
                        <a href="<?= app_url('/dashboard') ?>" class="cmd-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold text-slate-700 hover:text-blue-700 transition">
                            <div class="flex items-center space-x-3">
                                <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center border border-slate-200"><i class="fa-solid fa-chart-pie text-xs"></i></span>
                                <span>Dashboard &amp; Revenue Velocity</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">Open <i class="fa-solid fa-arrow-right text-[9px]"></i></span>
                        </a>

                        <a href="<?= app_url('/invoices') ?>" class="cmd-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold text-slate-700 hover:text-blue-700 transition">
                            <div class="flex items-center space-x-3">
                                <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center border border-slate-200"><i class="fa-solid fa-file-invoice text-xs"></i></span>
                                <span>Invoices Directory &amp; Payment Links</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">Open <i class="fa-solid fa-arrow-right text-[9px]"></i></span>
                        </a>

                        <a href="<?= app_url('/customers') ?>" class="cmd-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold text-slate-700 hover:text-blue-700 transition">
                            <div class="flex items-center space-x-3">
                                <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center border border-slate-200"><i class="fa-solid fa-users text-xs"></i></span>
                                <span>Customer Directory &amp; GSTIN Ledger</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">Open <i class="fa-solid fa-arrow-right text-[9px]"></i></span>
                        </a>

                        <a href="<?= app_url('/plans') ?>" class="cmd-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold text-slate-700 hover:text-blue-700 transition">
                            <div class="flex items-center space-x-3">
                                <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center border border-slate-200"><i class="fa-solid fa-tags text-xs"></i></span>
                                <span>Subscription Plans &amp; Tiers</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">Open <i class="fa-solid fa-arrow-right text-[9px]"></i></span>
                        </a>

                        <a href="<?= app_url('/team') ?>" class="cmd-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold text-slate-700 hover:text-blue-700 transition">
                            <div class="flex items-center space-x-3">
                                <span class="w-7 h-7 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center border border-slate-200"><i class="fa-solid fa-user-shield text-xs"></i></span>
                                <span>Team Members &amp; RBAC Control</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">Open <i class="fa-solid fa-arrow-right text-[9px]"></i></span>
                        </a>

                        <a href="<?= app_url('/settings') ?>" class="cmd-item flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold text-slate-700 hover:text-blue-700 transition">
                            <div class="flex items-center space-x-3">
                                <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center border border-slate-200"><i class="fa-solid fa-sliders text-xs"></i></span>
                                <span>Organization Profile &amp; GST Setup</span>
                            </div>
                            <span class="text-[10px] font-semibold text-slate-400 flex items-center gap-1">Open <i class="fa-solid fa-arrow-right text-[9px]"></i></span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Command Palette Footer -->
            <div class="p-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-medium">
                <div class="flex items-center space-x-3">
                    <span><kbd class="font-mono bg-white text-slate-700 px-1.5 py-0.5 rounded border border-slate-200">↑</kbd> <kbd class="font-mono bg-white text-slate-700 px-1.5 py-0.5 rounded border border-slate-200">↓</kbd> navigate</span>
                    <span><kbd class="font-mono bg-white text-slate-700 px-1.5 py-0.5 rounded border border-slate-200">↵</kbd> select</span>
                </div>
                <span class="font-mono text-blue-600 font-bold">Fast Executive Jump</span>
            </div>
        </div>
    </div>

    <!-- Minimal Modern Fintech Footer -->
    <footer class="mt-auto py-6 bg-white border-t border-slate-200/90 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
                <span class="font-bold text-slate-800">SaaSify</span>
                <span>&bull;</span>
                <span>&copy; <?= date('Y') ?> SaaSify Platforms Inc.</span>
            </div>
            <div class="flex items-center space-x-4 font-semibold text-slate-600">
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> 99.99% Bank Uptime</span>
                <span>&bull;</span>
                <span class="flex items-center gap-1"><i class="fa-solid fa-shield-halved text-blue-600"></i> 100% Indian GST Compliant</span>
            </div>
        </div>
    </footer>

    <!-- Interactive Scripts for Morphing Dynamic Island & Command Palette -->
    <script>
    function toggleMobileNav() {
        document.getElementById('mobileDrawer').classList.toggle('hidden');
    }

    function openCommandPalette() {
        const modal = document.getElementById('cmdPaletteModal');
        modal.classList.remove('hidden');
        const input = document.getElementById('cmdInput');
        input.value = '';
        input.focus();
        filterCommands('');
    }

    function closeCommandPalette() {
        document.getElementById('cmdPaletteModal').classList.add('hidden');
    }

    function handleBackdropClick(event) {
        if (event.target.id === 'cmdPaletteModal') {
            closeCommandPalette();
        }
    }

    // ═══════════════════════════════════════════════════════════
    // SMART SCROLL-MORPHING SMALL CIRCLE DOCK PHYSICS
    // ═══════════════════════════════════════════════════════════
    const island = document.getElementById('floatingDynamicIsland');
    const circleOrb = document.getElementById('islandCircleOrb');
    const closeBtn = document.getElementById('islandDockCloseBtn');
    let isHovered = false;
    // Scroll Listener: Add sleek glassmorphism depth shadow when scrolled
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            island?.classList.add('is-scrolled');
        } else {
            island?.classList.remove('is-scrolled');
        }
    }, { passive: true });

    // Global Keyboard Listener for Ctrl+K, Cmd+K, and Esc
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            const modal = document.getElementById('cmdPaletteModal');
            if (modal.classList.contains('hidden')) {
                openCommandPalette();
            } else {
                closeCommandPalette();
            }
        } else if (e.key === 'Escape') {
            closeCommandPalette();
        }
    });

    // Real-time Command Filter
    document.getElementById('cmdInput')?.addEventListener('input', function(e) {
        filterCommands(e.target.value.toLowerCase().trim());
    });

    function filterCommands(query) {
        const items = document.querySelectorAll('.cmd-item');
        items.forEach(item => {
            const text = item.innerText.toLowerCase();
            if (query === '' || text.includes(query)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // ==========================================
    // LIVE NOTIFICATION CENTER & SEEN TRACKING
    // ==========================================
    const SEEN_NOTIF_KEY = 'saasify_seen_notif_ids_v1';
    let currentNotifList = [];
    let notifDrawerOpen = false;
    let autoSeenTimer = null;

    function getSeenNotifIds() {
        try {
            const raw = localStorage.getItem(SEEN_NOTIF_KEY);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    function saveSeenNotifIds(ids) {
        try {
            // Keep at most last 150 IDs
            const trimmed = ids.slice(-150);
            localStorage.setItem(SEEN_NOTIF_KEY, JSON.stringify(trimmed));
        } catch (e) {}
    }

    function isNotifSeen(id) {
        const seen = getSeenNotifIds();
        return seen.includes(String(id));
    }

    function applySeenStyleToItem(id) {
        const row = document.getElementById('notif-item-' + id);
        if (!row) return;
        // Remove high-light active styles (high light no thavu joy)
        row.classList.remove('bg-indigo-950/40', 'border-indigo-400', 'hover:bg-indigo-900/40');
        row.classList.add('bg-transparent', 'border-transparent', 'hover:bg-white/[0.04]', 'opacity-80', 'hover:opacity-100');
        row.title = 'Read';

        // Remove 'New' glowing badge
        const badge = row.querySelector('.notif-new-badge');
        if (badge) badge.remove();

        // Mute title
        const title = row.querySelector('.notif-title');
        if (title) {
            title.classList.remove('text-white', 'font-bold');
            title.classList.add('text-slate-300', 'font-medium');
        }
    }

    function markNotifAsSeen(id) {
        const seen = getSeenNotifIds();
        const strId = String(id);
        if (!seen.includes(strId)) {
            seen.push(strId);
            saveSeenNotifIds(seen);
        }
        applySeenStyleToItem(id);
        updateUnreadBadges();
    }

    function markAllNotificationsAsRead() {
        if (!Array.isArray(currentNotifList) || currentNotifList.length === 0) return;
        const seen = getSeenNotifIds();
        currentNotifList.forEach(item => {
            const strId = String(item.id);
            if (!seen.includes(strId)) {
                seen.push(strId);
            }
            applySeenStyleToItem(item.id);
        });
        saveSeenNotifIds(seen);
        updateUnreadBadges();
    }

    function updateUnreadBadges() {
        if (!Array.isArray(currentNotifList)) return;
        const seen = getSeenNotifIds();
        const unreadItems = currentNotifList.filter(item => !seen.includes(String(item.id)));
        const unreadCount = unreadItems.length;

        const badge = document.getElementById('notifBadge');
        const summary = document.getElementById('unreadCountSummary');

        if (summary) {
            summary.innerText = unreadCount > 0 ? `${unreadCount} unread` : 'All read';
            summary.className = unreadCount > 0 ? 'text-[10px] text-indigo-400 font-mono font-bold' : 'text-[10px] text-slate-500 font-mono';
        }

        if (badge) {
            if (unreadCount > 0) {
                badge.innerText = unreadCount > 9 ? '9+' : unreadCount;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }

    function playChimeAlert() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            if (ctx.state === 'suspended') {
                ctx.resume();
            }
            
            // Crystal two-tone bell chime: E5 (659Hz) -> A5 (880Hz)
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            
            const now = ctx.currentTime;
            osc.frequency.setValueAtTime(659.25, now);
            osc.frequency.exponentialRampToValueAtTime(880.00, now + 0.12);
            
            gain.gain.setValueAtTime(0.2, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.6);
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            osc.start(now);
            osc.stop(now + 0.6);
        } catch (e) {
            // AudioContext silent fallback
        }
    }

    function toggleNotifDrawer() {
        const drawer = document.getElementById('notifDrawer');
        if (!drawer) return;
        notifDrawerOpen = !notifDrawerOpen;
        if (notifDrawerOpen) {
            drawer.classList.remove('hidden');
            // When opened, the user sees the messages!
            // Give a 1.2s viewing window so user spots what was highlighted as new,
            // then automatically un-highlight them and mark them as seen!
            if (autoSeenTimer) clearTimeout(autoSeenTimer);
            autoSeenTimer = setTimeout(() => {
                markAllNotificationsAsRead();
            }, 1200);
        } else {
            drawer.classList.add('hidden');
            if (autoSeenTimer) {
                clearTimeout(autoSeenTimer);
                markAllNotificationsAsRead();
            }
        }
    }

    document.addEventListener('click', function(e) {
        const container = document.getElementById('notifCenterContainer');
        if (container && !container.contains(e.target)) {
            const drawer = document.getElementById('notifDrawer');
            if (drawer && !drawer.classList.contains('hidden')) {
                drawer.classList.add('hidden');
                notifDrawerOpen = false;
                if (autoSeenTimer) {
                    clearTimeout(autoSeenTimer);
                    markAllNotificationsAsRead();
                }
            }
        }
    });

    async function refreshNotifications(manual = false) {
        try {
            const res = await fetch('<?= app_url('/api/notifications') ?>', {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) return;
            const data = await res.json();
            if (!data.success || !Array.isArray(data.data)) return;

            currentNotifList = data.data;

            const list = document.getElementById('notifList');
            const countPill = document.getElementById('notifCountPill');

            if (countPill) countPill.innerText = currentNotifList.length;

            if (currentNotifList.length === 0) {
                if (list) list.innerHTML = '<div class="p-4 text-center text-slate-500 text-xs">No recent activity detected.</div>';
                updateUnreadBadges();
                return;
            }

            // On first-time initialization of client storage, mark current existing items as seen
            // so ancient audit records don't light up as fake unread alerts on fresh load
            let seenIds = getSeenNotifIds();
            const isFirstInit = localStorage.getItem(SEEN_NOTIF_KEY) === null;
            if (isFirstInit) {
                seenIds = currentNotifList.map(i => String(i.id));
                saveSeenNotifIds(seenIds);
            }

            // Check if there are newly arrived events that haven't been seen
            const unreadItems = currentNotifList.filter(i => !seenIds.includes(String(i.id)));
            if (unreadItems.length > 0 && !manual && !isFirstInit) {
                playChimeAlert();
            }

            if (list) {
                list.innerHTML = currentNotifList.map(item => {
                    const strId = String(item.id);
                    const isSeen = seenIds.includes(strId);

                    let iconClass = 'fa-circle-info text-blue-400 bg-blue-500/10';
                    const act = (item.action || '').toLowerCase();
                    if (act.includes('invoice') || act.includes('payment')) {
                        iconClass = 'fa-file-invoice-dollar text-emerald-400 bg-emerald-500/10';
                    } else if (act.includes('delete') || act.includes('void') || act.includes('fail')) {
                        iconClass = 'fa-triangle-exclamation text-rose-400 bg-rose-500/10';
                    } else if (act.includes('customer') || act.includes('client')) {
                        iconClass = 'fa-user-check text-indigo-400 bg-indigo-500/10';
                    }

                    // Highlight style for unread vs Clean muted style for seen
                    const containerStyle = isSeen 
                        ? 'bg-transparent border-l-2 border-transparent hover:bg-white/[0.04] opacity-80 hover:opacity-100'
                        : 'bg-indigo-950/40 border-l-2 border-indigo-400 hover:bg-indigo-900/40';

                    const titleStyle = isSeen 
                        ? 'font-medium text-slate-300' 
                        : 'font-bold text-white';

                    const newBadge = isSeen 
                        ? '' 
                        : `<span class="notif-new-badge inline-flex items-center px-1.5 py-0.2 rounded-full text-[9px] font-bold bg-indigo-500/25 text-indigo-300 border border-indigo-400/30 shrink-0"><span class="w-1.5 h-1.5 rounded-full bg-indigo-400 mr-1 animate-pulse"></span>New</span>`;

                    return `
                        <div id="notif-item-${item.id}"
                             onclick="markNotifAsSeen('${item.id}')"
                             class="p-3 transition-all duration-300 flex items-start space-x-2.5 cursor-pointer ${containerStyle}"
                             title="${isSeen ? 'Read' : 'Click to mark as read'}">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 text-xs ${iconClass}">
                                <i class="fa-solid ${iconClass.split(' ')[0]}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1.5">
                                    <div class="flex items-center space-x-1.5 truncate">
                                        <span class="notif-title text-xs truncate ${titleStyle}">${item.action}</span>
                                        ${newBadge}
                                    </div>
                                    <span class="text-[10px] text-slate-500 shrink-0 font-mono">${item.time_ago}</span>
                                </div>
                                <p class="text-[11px] text-slate-400 line-clamp-2 mt-0.5">${item.description || ''}</p>
                                <span class="text-[10px] text-slate-600 font-mono mt-0.5 block">By ${item.user}</span>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            updateUnreadBadges();
        } catch (err) {
            console.error('Failed to poll notifications:', err);
        }
    }

    // Initial load & 20s interval background polling
    document.addEventListener('DOMContentLoaded', () => {
        refreshNotifications();
        setInterval(() => refreshNotifications(), 20000);
    });
    </script>
</body>
</html>