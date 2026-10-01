<?php
$sub = $subscription ?? [];
$maxSeatsVal = (int)($maxSeats ?? 5);
$usedSeatsVal = (int)($usedSeats ?? count($members));
$seatPercentage = ($maxSeatsVal > 0) ? min(100, round(($usedSeatsVal / $maxSeatsVal) * 100)) : 100;
$circumference = 251.2;
$seatStrokeOffset = round($circumference - ($circumference * ($seatPercentage / 100)), 1);
?>

<div class="space-y-8 pb-12">
    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 1: ACCESS GOVERNANCE COMMAND CROWN
         ══════════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white p-6 sm:p-8 shadow-2xl border border-slate-800">
        <!-- Ambient Glow Accents -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center flex-wrap gap-2.5">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/30 text-purple-400 font-mono text-[11px] font-bold tracking-wider uppercase">
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                        <span>Access Governance &bull; Team RBAC Reactor</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-white/10 text-slate-300 border border-white/10 text-[10px] font-bold uppercase tracking-wider font-mono">
                        Workspace: <?= e($tenant['name'] ?? 'Corporate') ?>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                    <span>Identity, Seats &amp; Granular Permissions</span>
                </h1>
                <p class="text-xs text-slate-400 max-w-xl">
                    Configure cryptographic Role-Based Access Control (RBAC), enforce isolation policies, and manage licensed team member capacity in real-time.
                </p>
            </div>

            <!-- Instant Actions Dock -->
            <div class="flex items-center flex-wrap gap-3">
                <?php if (can_manage_team()): ?>
                    <?php if ($usedSeatsVal >= $maxSeatsVal): ?>
                        <a href="<?= app_url('/plans') ?>" 
                           class="px-5 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 text-white font-black text-xs shadow-lg shadow-amber-500/25 transition flex items-center space-x-2">
                            <i class="fa-solid fa-bolt text-xs"></i>
                            <span>Capacity Full &bull; Upgrade</span>
                        </a>
                    <?php else: ?>
                        <button type="button" onclick="document.getElementById('inviteModal').classList.remove('hidden')" 
                                class="px-5 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold text-xs shadow-lg shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 flex items-center space-x-2">
                            <i class="fa-solid fa-user-plus text-xs"></i>
                            <span>+ Invite Member</span>
                        </button>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="<?= app_url('/dashboard') ?>" 
                   class="p-3 rounded-xl bg-white/10 hover:bg-white/15 text-slate-300 border border-white/10 transition" 
                   title="Return to Dashboard">
                    <i class="fa-solid fa-house text-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 2: SEAT REACTOR & ROLE CLEARANCE RADAR
         ══════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Seat Utilization Reactor -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="relative w-16 h-16 shrink-0 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="40" stroke="#F1F5F9" stroke-width="10" fill="transparent"></circle>
                    <circle cx="50" cy="50" r="40" stroke="#6366F1" stroke-width="10" stroke-linecap="round" fill="transparent"
                            stroke-dasharray="251.2" stroke-dashoffset="<?= $seatStrokeOffset ?>"></circle>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                    <span class="text-xs font-black text-slate-900 font-mono"><?= $usedSeatsVal ?>/<?= $maxSeatsVal >= 999 ? '&infin;' : $maxSeatsVal ?></span>
                </div>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Utilization</span>
                <span class="text-sm font-black text-slate-900 block">Workspace Seats</span>
                <span class="text-[10px] <?= ($usedSeatsVal >= $maxSeatsVal) ? 'text-rose-600 font-bold' : 'text-indigo-600 font-semibold' ?>">
                    <?= ($usedSeatsVal >= $maxSeatsVal) ? 'Limit Reached' : ($maxSeatsVal - $usedSeatsVal) . ' seats open' ?>
                </span>
            </div>
        </div>

        <!-- Owners & Administrators -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1 relative overflow-hidden">
            <div class="h-1 w-full bg-purple-600 absolute top-0 left-0"></div>
            <span class="text-[10px] font-bold text-purple-800 uppercase tracking-wider block">Owners &amp; Admins</span>
            <div class="text-2xl font-black text-purple-700 font-mono"><?= ($roleCounts['owner'] ?? 0) + ($roleCounts['admin'] ?? 0) ?></div>
            <span class="text-[11px] text-purple-600 font-semibold block">Full Sovereign Powers</span>
        </div>

        <!-- Billing Managers -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1 relative overflow-hidden">
            <div class="h-1 w-full bg-emerald-500 absolute top-0 left-0"></div>
            <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider block">Billing Managers</span>
            <div class="text-2xl font-black text-emerald-600 font-mono"><?= $roleCounts['billing_manager'] ?? 0 ?></div>
            <span class="text-[11px] text-emerald-600 font-semibold block">Invoicing &amp; Tier Control</span>
        </div>

        <!-- Standard Members -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm space-y-1 relative overflow-hidden">
            <div class="h-1 w-full bg-slate-400 absolute top-0 left-0"></div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Standard Members</span>
            <div class="text-2xl font-black text-slate-800 font-mono"><?= $roleCounts['member'] ?? 0 ?></div>
            <span class="text-[11px] text-slate-400 block">General Operational View</span>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 3: MEMBER DIRECTORY MATRIX TABLE
         ══════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-black text-slate-900 tracking-tight">Active Identity Manifest</h3>
                <p class="text-xs text-slate-500">Cryptographically scoped to tenant: <?= e($tenant['name']) ?></p>
            </div>
            <span class="text-xs text-slate-500 font-medium">
                Total: <strong class="text-slate-900 font-mono"><?= count($members) ?></strong> users provisioned
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/90 text-slate-600 text-[11px] uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">User Identity</th>
                        <th class="px-6 py-4">Work Coordinates</th>
                        <th class="px-6 py-4">Security Clearance</th>
                        <th class="px-6 py-4">Onboarded</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <?php foreach ($members as $member): 
                        $role = $member['role'] ?? 'member';
                        $isSelf = ($currentUser['id'] === $member['id']);
                        $initial = strtoupper(substr($member['name'] ?? 'U', 0, 1));
                    ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3.5">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white font-black text-sm flex items-center justify-center shadow-xs">
                                        <?= $initial ?>
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-900 text-sm flex items-center gap-2">
                                            <span><?= e($member['name']) ?></span>
                                            <?php if ($isSelf): ?>
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                                    You
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-[11px] text-slate-400 font-mono">ID: <?= substr($member['id'], 0, 8) ?>...</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 font-mono font-medium text-slate-700">
                                <?= e($member['email']) ?>
                            </td>

                            <td class="px-6 py-4">
                                <?php if ($role === 'owner'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200 shadow-2xs">
                                        <i class="fa-solid fa-crown text-[10px] mr-1.5 text-purple-600"></i> Workspace Owner
                                    </span>
                                <?php elseif ($role === 'admin'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 shadow-2xs">
                                        <i class="fa-solid fa-shield-halved text-[10px] mr-1.5 text-blue-600"></i> Administrator
                                    </span>
                                <?php elseif ($role === 'billing_manager'): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                        <i class="fa-solid fa-credit-card text-[10px] mr-1.5 text-emerald-600"></i> Billing Manager
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        <i class="fa-solid fa-user text-[10px] mr-1.5 text-slate-400"></i> Member
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 font-mono text-[11px] text-slate-500">
                                <?= date('M d, Y', strtotime($member['created_at'])) ?>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <?php if ($role !== 'owner' && !$isSelf && can_manage_team()): ?>
                                    <form action="<?= app_url('/team/delete/' . $member['id']) ?>" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to remove <?= e($member['name']) ?> from your organization?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold px-3 py-1.5 rounded-xl hover:bg-rose-50 transition text-xs" title="Revoke Access">
                                            <i class="fa-solid fa-trash-can mr-1"></i> Revoke
                                        </button>
                                    </form>
                                <?php elseif ($role === 'owner'): ?>
                                    <span class="text-slate-400 font-medium italic text-[11px]">Primary Tenant Root</span>
                                <?php else: ?>
                                    <span class="text-slate-300">&bull;&bull;&bull;</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 4: RBAC CLEARANCE MATRIX CARDS
         ══════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-blue-600"></i>
            <span>Cryptographic RBAC Authorization Matrix</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-purple-50/70 border border-purple-200 space-y-1.5">
                <div class="flex items-center space-x-2 text-purple-900 font-black">
                    <i class="fa-solid fa-crown text-purple-600"></i>
                    <span>Owner &amp; Admin</span>
                </div>
                <p class="text-purple-800 leading-relaxed text-[11px]">
                    Sovereign power: provision/revoke team seats, change subscription tiers, modify bank &amp; GST credentials, manage client directory, and audit all webhooks.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 space-y-1.5">
                <div class="flex items-center space-x-2 text-emerald-900 font-black">
                    <i class="fa-solid fa-receipt text-emerald-600"></i>
                    <span>Billing Manager</span>
                </div>
                <p class="text-emerald-800 leading-relaxed text-[11px]">
                    Financial operations only: execute tier upgrades, create and issue B2B tax invoices, record offline settlements, and download official 18% GST receipts.
                </p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1.5">
                <div class="flex items-center space-x-2 text-slate-800 font-black">
                    <i class="fa-solid fa-user text-slate-500"></i>
                    <span>Standard Member</span>
                </div>
                <p class="text-slate-600 leading-relaxed text-[11px]">
                    Operational visibility: inspect tenant dashboard, client lists, and invoice status. Zero authority to modify subscriptions or execute financial transactions.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     INVITE TEAM MEMBER MODAL
     ══════════════════════════════════════════════════════════════════ -->
<div id="inviteModal" class="hidden fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm overflow-y-auto p-4 flex items-start sm:items-center justify-center">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-200 relative my-auto text-slate-800">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg border border-indigo-100">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900">Provision User Identity</h3>
                    <p class="text-xs text-slate-500">Invite member to <?= e($tenant['name']) ?></p>
                </div>
            </div>
            <button onclick="document.getElementById('inviteModal').classList.add('hidden')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition border border-slate-200">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="<?= app_url('/team/invite') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Member Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Alex Morgan"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-medium focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Work Email Coordinate</label>
                <input type="email" name="email" required placeholder="alex@company.com"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-medium focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Initial Temporary Password</label>
                <input type="password" name="password" required minlength="6" placeholder="••••••••" value="Password123!"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-mono focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                <span class="text-[10px] text-slate-400 mt-1 block">Default prefill: Password123!</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Security Clearance Role</label>
                <select name="role" required class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-bold focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition">
                    <option value="member">Member (Read-only general telemetry)</option>
                    <option value="billing_manager">Billing Manager (Manage invoices &amp; checkout)</option>
                    <option value="admin">Administrator (Full workspace controls)</option>
                </select>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('inviteModal').classList.add('hidden')"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs shadow-md shadow-blue-500/25 transition">
                    Provision &amp; Send Credentials
                </button>
            </div>
        </form>
    </div>
</div>