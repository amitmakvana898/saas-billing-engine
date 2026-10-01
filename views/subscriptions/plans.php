<?php
// views/subscriptions/plans.php
$tenantName = $tenant['name'] ?? 'Your Organization';
$currentPlanName = $currentSub['plan_name'] ?? 'Free Starter';
?>
<div class="max-w-7xl mx-auto space-y-8 pb-12">
    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 1: ENTERPRISE SCALING COCKPIT CROWN
         ══════════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white p-6 sm:p-8 shadow-2xl border border-slate-800">
        <!-- Ambient Glow Accents -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center flex-wrap gap-2.5">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 font-mono text-[11px] font-bold tracking-wider uppercase">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>Resource Matrix &bull; Enterprise Scaling</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold uppercase tracking-wider font-mono">
                        Active Tier: <?= strtoupper(e($currentPlanName)) ?>
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                    <span>Scalable SaaS Billing Tiers &amp; Quotas</span>
                </h1>
                <p class="text-xs text-slate-400 max-w-xl">
                    Switch subscription tiers dynamically. Instant payment authorization generates official 18% GST Tax Invoices for Indian B2B Input Tax Credit with immediate tenant provisioning.
                </p>
            </div>

            <!-- Instant Actions Dock -->
            <div class="flex items-center space-x-3">
                <a href="<?= app_url('/dashboard') ?>" 
                   class="px-4 py-3 rounded-xl bg-white/10 hover:bg-white/15 text-slate-200 border border-white/10 font-bold text-xs transition flex items-center space-x-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= app_url('/invoices') ?>" 
                   class="px-4 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 font-bold text-xs transition flex items-center space-x-2">
                    <i class="fa-solid fa-file-invoice text-blue-400 text-xs"></i>
                    <span>Billing Invoices</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Flash message display -->
    <?php if ($msg = flash('success')): ?>
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
            <span><?= e($msg) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($msg = flash('error')): ?>
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base shrink-0"></i>
            <span><?= e($msg) ?></span>
        </div>
    <?php endif; ?>

    <!-- RBAC Notice for standard members -->
    <?php if (!can_manage_billing()): ?>
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center space-x-3 shadow-xs">
            <i class="fa-solid fa-shield-halved text-amber-600 text-lg shrink-0"></i>
            <div>
                <span class="font-bold">Restricted Financial Authority:</span> Your current role is <strong><?= strtoupper(user_role()) ?></strong>. 
                Only Workspace Owners, Admins, or Billing Managers are authorized to upgrade plans or execute checkouts.
            </div>
        </div>
    <?php endif; ?>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 2: COMPLIANCE & RESOURCE QUOTA TELEMETRY
         ══════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0 border border-emerald-100">
                <i class="fa-solid fa-shield-check"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 block">18% GST Compliant</span>
                <span class="text-xs font-extrabold text-slate-900 block mt-0.5">Input Tax Credit Guaranteed</span>
                <span class="text-[10px] text-slate-500">Official GST invoice on every upgrade</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0 border border-blue-100">
                <i class="fa-solid fa-bolt-lightning"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-800 block">Instant Activation</span>
                <span class="text-xs font-extrabold text-slate-900 block mt-0.5">Zero-Downtime Provisioning</span>
                <span class="text-[10px] text-slate-500">Limits &amp; seats scale immediately</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0 border border-purple-100">
                <i class="fa-solid fa-building-lock"></i>
            </div>
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-purple-800 block">Enterprise Security</span>
                <span class="text-xs font-extrabold text-slate-900 block mt-0.5">PCI-DSS Level 1 Gateway</span>
                <span class="text-[10px] text-slate-500">256-bit encrypted bank checkout</span>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 3: FUTURISTIC PLAN TIERS GRID
         ══════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch pt-2">
        <?php foreach ($plans as $plan): 
            $isCurrent = ($currentSub && (int)$currentSub['plan_id'] === (int)$plan['id']);
            $features = json_decode($plan['features_json'], true) ?: [];
            $isFeatured = (strtolower($plan['name']) === 'pro' || strtolower($plan['name']) === 'scale');
        ?>
            <div class="bg-white rounded-3xl border <?= $isCurrent ? 'border-emerald-500 ring-4 ring-emerald-500/10 shadow-lg' : ($isFeatured ? 'border-blue-600 ring-4 ring-blue-600/10 shadow-lg' : 'border-slate-200 shadow-sm') ?> p-7 sm:p-8 flex flex-col justify-between relative transition duration-200 hover:shadow-xl">
                <!-- Top Badge -->
                <?php if ($isCurrent): ?>
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-emerald-600 text-white font-extrabold text-[10px] tracking-wider uppercase shadow-md flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fa-solid fa-circle-check text-[10px]"></i>
                        <span>Current Active Plan</span>
                    </div>
                <?php elseif ($isFeatured): ?>
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black text-[10px] tracking-wider uppercase shadow-md flex items-center gap-1 whitespace-nowrap">
                        <i class="fa-solid fa-bolt text-amber-300 text-[10px]"></i>
                        <span>Recommended Tier</span>
                    </div>
                <?php endif; ?>

                <div class="space-y-6">
                    <div class="flex items-center justify-between pt-1">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block">Workspace Tier</span>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight mt-0.5"><?= e($plan['name']) ?></h3>
                        </div>
                        <span class="text-xs font-mono font-bold text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1 rounded-xl">
                            <?= (int)$plan['max_seats'] >= 999 ? '&infin; Seats' : (int)$plan['max_seats'] . ' Seats' ?>
                        </span>
                    </div>

                    <p class="text-xs text-slate-500 font-medium min-h-[36px] leading-relaxed"><?= e($plan['description']) ?></p>

                    <!-- Price Block -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-baseline justify-between">
                        <div>
                            <span class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight font-mono"><?= format_cents((int)$plan['price_cents']) ?></span>
                            <span class="text-slate-500 text-xs font-bold font-sans">/ <?= e($plan['billing_interval']) ?></span>
                        </div>
                        <span class="text-[10px] font-bold text-blue-600 font-mono">+18% GST</span>
                    </div>

                    <!-- Included Capabilities Checklist -->
                    <div class="space-y-3">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Included Capabilities:</span>
                        <ul class="space-y-3 text-xs text-slate-700 font-medium">
                            <?php foreach ($features as $feat): ?>
                                <li class="flex items-start space-x-2.5">
                                    <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] mt-0.5 shrink-0">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <span class="leading-snug"><?= e($feat) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Card Action Footer -->
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <?php if ($isCurrent): ?>
                        <button disabled class="w-full py-3.5 px-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-black text-xs cursor-default flex items-center justify-center space-x-2 shadow-xs">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Active Subscription</span>
                        </button>
                    <?php elseif (!can_manage_billing()): ?>
                        <button disabled class="w-full py-3.5 px-4 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed flex items-center justify-center space-x-2 border border-slate-200">
                            <i class="fa-solid fa-lock text-slate-400"></i>
                            <span>Admin Permission Required</span>
                        </button>
                    <?php else: ?>
                        <button type="button"
                                data-plan-id="<?= (int)$plan['id'] ?>"
                                onclick="openCheckoutModal(<?= (int)$plan['id'] ?>, '<?= e($plan['name']) ?>', <?= (int)$plan['price_cents'] ?>, '<?= e($plan['billing_interval']) ?>')"
                                class="w-full py-3.5 px-4 rounded-xl <?= $isFeatured ? 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white shadow-lg shadow-blue-500/25' : 'bg-slate-900 hover:bg-slate-800 text-white' ?> font-black text-xs transition flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-credit-card text-[11px]"></i>
                            <span>Upgrade to <?= e($plan['name']) ?> &rarr;</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         CANCEL SUBSCRIPTION SECTION (ONLY FOR BILLING ADMINS)
         ══════════════════════════════════════════════════════════════════ -->
    <?php if ($currentSub && $currentSub['status'] !== 'canceled' && can_manage_billing()): ?>
        <div class="mt-12 bg-white rounded-3xl border border-rose-200 p-6 sm:p-7 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">
            <div>
                <h4 class="text-sm font-bold text-rose-900">Need to cancel your organization subscription?</h4>
                <p class="text-xs text-slate-500 mt-1">Your access will remain fully functional until the end of your current cycle (<?= date('M d, Y', strtotime($currentSub['current_period_end'] ?? '+30 days')) ?>).</p>
            </div>
            <form action="<?= app_url('/plans/cancel') ?>" method="POST" onsubmit="return confirm('Are you sure you want to cancel your organization subscription?');">
                <?= csrf_field() ?>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition flex items-center space-x-2">
                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                    <span>Cancel Subscription</span>
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- ══════════════════════════════════════════════════════════════════
     RAZORPAY SECURE CHECKOUT MODAL (2-COLUMN ARCHITECTURE)
     ══════════════════════════════════════════════════════════════════ -->
<div id="checkoutModal" class="fixed inset-0 z-[9999] bg-slate-950/70 backdrop-blur-sm hidden overflow-y-auto p-2 sm:p-4 md:p-6 transition-opacity">
    <div class="min-h-full flex items-start md:items-center justify-center py-4 sm:py-6">
        <div id="checkoutModalCard" class="bg-white rounded-3xl max-w-4xl w-full border border-slate-200 shadow-2xl overflow-hidden my-auto transition-all duration-200 text-slate-900">
            
            <!-- Modal Top Header -->
            <div class="bg-slate-900 px-6 py-5 text-white flex items-center justify-between border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-600/30 border border-blue-400/40 text-blue-400 flex items-center justify-center text-base shadow-xs">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-black tracking-tight text-white">Razorpay Secure Checkout &bull; Tier Upgrade</h3>
                        <p class="text-[11px] text-slate-400 font-mono">256-bit SSL Encrypted &bull; PCI-DSS Level 1 Gateway</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="closeCheckoutModal()"
                            class="w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-sm transition"
                            title="Close Checkout">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <!-- Form with 2-Column Responsive Layout -->
            <form id="checkoutForm" action="<?= app_url('/plans/upgrade') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="plan_id" id="checkoutPlanId" value="">

                <div class="grid grid-cols-1 md:grid-cols-12 divide-y md:divide-y-0 md:divide-x divide-slate-200">

                    <!-- LEFT COLUMN: Order Summary & 18% GST Breakdown (5/12 cols) -->
                    <div class="md:col-span-5 p-6 sm:p-7 bg-slate-50 flex flex-col justify-between space-y-4">
                        <div class="space-y-4">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
                                <i class="fa-solid fa-receipt text-blue-600"></i> Order Breakdown
                            </span>

                            <div class="bg-white rounded-2xl border border-slate-200 p-4 space-y-3 shadow-xs">
                                <div class="flex items-center justify-between text-xs pb-2.5 border-b border-slate-100">
                                    <span class="text-slate-500 font-medium">Organization:</span>
                                    <span class="text-slate-900 font-bold truncate max-w-[150px]"><?= e($tenantName) ?></span>
                                </div>

                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Selected Tier:</span>
                                    <span id="checkoutPlanName" class="font-extrabold text-slate-900 text-sm">Pro Scale</span>
                                </div>

                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-slate-500 font-medium">Plan Base Subtotal:</span>
                                    <span id="checkoutSubtotal" class="font-mono font-bold text-slate-800">₹4,999.00</span>
                                </div>

                                <div class="flex items-center justify-between text-xs">
                                    <span class="flex items-center gap-1 text-slate-500 font-medium">
                                        <span>Indian GST (18%):</span>
                                        <i class="fa-solid fa-circle-info text-[10px] text-slate-400" title="18% Goods and Services Tax for B2B input tax credit"></i>
                                    </span>
                                    <span id="checkoutTax" class="font-mono font-bold text-blue-600">+₹899.82</span>
                                </div>

                                <div class="pt-3 border-t border-slate-200 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-black uppercase tracking-wider text-slate-900 block">Total Due</span>
                                        <span class="text-[10px] text-emerald-700 font-bold">Includes 18% GST</span>
                                    </div>
                                    <span id="checkoutTotal" class="text-xl sm:text-2xl font-black font-mono text-blue-600">₹5,898.82</span>
                                </div>
                            </div>
                        </div>

                        <!-- B2B Tax Credit Compliance Box -->
                        <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-xs text-blue-900 flex items-start space-x-2.5">
                            <i class="fa-solid fa-file-invoice text-blue-600 mt-0.5 shrink-0 text-sm"></i>
                            <div class="text-[11px] leading-relaxed">
                                <strong>Official GST Invoice:</strong> Digitally signed tax invoice generated immediately with your registered GSTIN for full B2B Input Tax Credit.
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Payment Authorization & Card Details (7/12 cols) -->
                    <div class="md:col-span-7 p-6 sm:p-7 flex flex-col justify-between space-y-5 bg-white">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1.5">
                                    Payment Method
                                </label>
                                <div class="p-3.5 rounded-2xl border border-blue-200 bg-blue-50/50 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xs shadow-xs">
                                            <i class="fa-solid fa-credit-card"></i>
                                        </div>
                                        <div>
                                            <span class="text-xs font-black text-slate-900 block">Corporate Credit / Debit Card</span>
                                            <span class="text-[10px] text-slate-500">Visa, Mastercard, RuPay &bull; Auto-renewal enabled</span>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold font-mono">Verified</span>
                                </div>
                            </div>

                            <!-- Card Inputs -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Cardholder Name</label>
                                <input type="text" id="cardName" value="<?= e($tenantName) ?>" required 
                                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Card Number</label>
                                <div class="relative">
                                    <input type="text" id="cardNumber" placeholder="4111 2222 3333 4444" maxlength="19" required
                                           class="w-full pl-3.5 pr-10 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-mono font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                                    <i class="fa-brands fa-cc-visa absolute right-3 top-1/2 -translate-y-1/2 text-blue-600 text-lg"></i>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Expiry Date</label>
                                    <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5" required
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-mono font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">CVV / Security Code</label>
                                    <input type="password" id="cardCvv" placeholder="•••" maxlength="4" required
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-mono font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-4 border-t border-slate-100">
                            <button type="submit" 
                                    class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black text-xs shadow-lg shadow-blue-500/25 transition flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-lock text-[11px]"></i>
                                <span id="submitBtnText">Authorize Payment &amp; Provision Tier</span>
                            </button>
                            <button type="button" onclick="closeCheckoutModal()" 
                                    class="w-full py-2.5 text-slate-400 hover:text-slate-600 text-xs font-bold transition">
                                Cancel Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCheckoutModal(planId, planName, priceCents, interval) {
    const subtotal = priceCents / 100;
    const tax = Math.round(subtotal * 0.18 * 100) / 100;
    const total = subtotal + tax;

    document.getElementById('checkoutPlanId').value = planId;
    document.getElementById('checkoutPlanName').innerText = planName + ' Plan';
    document.getElementById('checkoutSubtotal').innerText = '₹' + subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2});
    document.getElementById('checkoutTax').innerText = '+₹' + tax.toLocaleString('en-IN', {minimumFractionDigits: 2});
    document.getElementById('checkoutTotal').innerText = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
    document.getElementById('submitBtnText').innerText = `Authorize ₹${total.toLocaleString('en-IN', {minimumFractionDigits: 2})} & Provision`;

    // Prefill dummy test card
    document.getElementById('cardNumber').value = '4242 4242 4242 4242';
    document.getElementById('cardExpiry').value = '12/28';
    document.getElementById('cardCvv').value = '123';

    document.getElementById('checkoutModal').classList.remove('hidden');
}

function closeCheckoutModal() {
    document.getElementById('checkoutModal').classList.add('hidden');
}
</script>