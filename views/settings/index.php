<?php
$flash_success = flash('success');
$flash_error   = flash('error');
?>
<div class="space-y-6">

    <?php if ($flash_success): ?>
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold shadow-xs">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span><?= e($flash_success) ?></span>
        </div>
    <?php endif; ?>

    <?php if ($flash_error): ?>
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold shadow-xs">
            <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
            <span><?= e($flash_error) ?></span>
        </div>
    <?php endif; ?>

    <div class="flex items-center justify-between">
        <a href="<?= app_url('/dashboard') ?>" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-lg bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-blue-700 transition shadow-xs">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Back to Dashboard</span>
        </a>

        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-blue-50 border border-blue-200 text-xs font-bold text-blue-700 shadow-2xs">
            <i class="fa-solid fa-building-circle-check text-blue-600"></i>
            <span>Tenant Account Management</span>
        </div>
    </div>

    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Organization &amp; Account Settings</h1>
        <p class="text-xs text-slate-500 mt-1">Manage your legal company billing info, GST tax registration, and security credentials.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- Card 1: Company Profile & Legal GST Compliance -->
        <div class="lg:col-span-7 rzp-card p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                <div class="flex items-center space-x-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-lg shadow-2xs">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Organization &amp; GST Details</h2>
                        <p class="text-xs text-slate-500">Legal B2B tax info printed on all official invoices</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= can_manage_team() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' ?>">
                    <?= can_manage_team() ? 'Editable' : 'Read Only' ?>
                </span>
            </div>

            <?php if (can_manage_team()): ?>
                <form action="<?= app_url('/settings/company') ?>" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    <?= csrf_field() ?>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Company / Legal Workspace Name *</label>
                        <input type="text" name="name" value="<?= e($tenant['name']) ?>" required
                            class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Workspace Subdomain</label>
                            <div class="flex rounded-lg border border-slate-200 bg-slate-100 px-3.5 py-2.5 text-xs items-center">
                                <span class="font-mono font-bold text-blue-700"><?= e($tenant['subdomain']) ?></span>
                                <span class="text-slate-500 ml-0.5">.saasify.app</span>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Tenant unique identifier</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                GSTIN / Tax ID Number
                                <span class="text-blue-600 font-bold ml-1">(18% GST)</span>
                            </label>
                            <input type="text" name="tax_id" value="<?= e($tenant['tax_id'] ?? '') ?>" placeholder="e.g. 24AAACA1234A1Z5"
                                class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm uppercase font-mono font-bold focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                            <p class="text-[11px] text-slate-400 mt-1">Required for Indian B2B Input Tax Credit (ITC)</p>
                        </div>
                    </div>

                    <!-- Company Logo Upload & URL -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Company Brand Logo</label>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2.5">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <div class="flex-1">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Option 1: Upload Image File</span>
                                    <input type="file" name="logo_file" accept="image/png,image/jpeg,image/svg+xml,image/webp"
                                           class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer">
                                </div>
                                <div class="hidden sm:block text-slate-300 font-bold text-xs uppercase self-center pt-3">OR</div>
                                <div class="flex-1">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase block mb-1">Option 2: Direct Image URL</span>
                                    <input type="url" name="logo_url" id="logoUrlInput" value="<?= e($tenant['logo_url'] ?? '') ?>" placeholder="https://example.com/logo.png"
                                           class="w-full px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-900 placeholder-slate-400 text-xs font-medium focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                                </div>
                                <?php if (!empty($tenant['logo_url'])): ?>
                                    <div class="shrink-0 self-center">
                                        <img src="<?= e($tenant['logo_url']) ?>" alt="Logo Preview" class="h-10 w-10 object-contain rounded-lg border border-slate-200 bg-white p-1 shadow-2xs">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">PNG, JPG, SVG, or WebP. Printed automatically on all your client invoices, receipts, and checkout portals.</p>
                    </div>

                    <!-- Direct Settlement Banking Details -->
                    <div class="pt-3 border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-900 uppercase tracking-wider block mb-3">
                            <i class="fa-solid fa-building-columns text-blue-600 mr-1"></i> B2B Bank Transfer &amp; UPI Settlement Details
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Bank Name</label>
                                <input type="text" name="bank_name" value="<?= e($tenant['bank_name'] ?? '') ?>" placeholder="e.g. HDFC Bank Ltd"
                                    class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Account Number</label>
                                <input type="text" name="bank_account_no" value="<?= e($tenant['bank_account_no'] ?? '') ?>" placeholder="e.g. 50200012345678"
                                    class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 font-mono placeholder-slate-400 text-sm font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">IFSC Code</label>
                                <input type="text" name="bank_ifsc" value="<?= e($tenant['bank_ifsc'] ?? '') ?>" placeholder="e.g. HDFC0000123"
                                    class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 uppercase font-mono placeholder-slate-400 text-sm font-bold focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Company UPI VPA / ID</label>
                                <input type="text" name="upi_id" value="<?= e($tenant['upi_id'] ?? '') ?>" placeholder="e.g. company@okhdfcbank"
                                    class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 font-mono placeholder-slate-400 text-sm font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-2">These banking details are displayed on invoices so corporate clients can execute direct NEFT/RTGS payments.</p>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#0C66E4] hover:bg-[#0055CC] text-white font-bold text-xs shadow-sm shadow-blue-500/25 transition flex items-center space-x-2">
                            <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                            <span>Save Organization Settings</span>
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="mt-6 space-y-4 text-xs text-slate-600">
                    <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                        <div class="flex justify-between items-center pb-2.5 border-b border-slate-200">
                            <span class="text-slate-500 font-semibold">Company:</span>
                            <span class="font-bold text-slate-900"><?= e($tenant['name']) ?></span>
                        </div>
                        <div class="flex justify-between items-center pb-2.5 border-b border-slate-200">
                            <span class="text-slate-500 font-semibold">Subdomain:</span>
                            <span class="font-mono font-bold text-blue-700"><?= e($tenant['subdomain']) ?>.saasify.app</span>
                        </div>
                        <div class="flex justify-between items-center pb-2.5 border-b border-slate-200">
                            <span class="text-slate-500 font-semibold">GSTIN:</span>
                            <span class="font-mono font-bold text-slate-900"><?= e($tenant['tax_id'] ?: 'Not Provided') ?></span>
                        </div>
                        <div class="flex justify-between items-center pb-2.5 border-b border-slate-200">
                            <span class="text-slate-500 font-semibold">Phone:</span>
                            <span class="font-medium text-slate-900"><?= e($tenant['phone'] ?: 'Not Provided') ?></span>
                        </div>
                        <div>
                            <span class="text-slate-500 font-semibold block mb-1">Billing Address:</span>
                            <span class="text-slate-800 leading-relaxed"><?= nl2br(e($tenant['billing_address'] ?: 'None specified')) ?></span>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 italic flex items-center gap-1.5">
                        <i class="fa-solid fa-lock text-slate-400"></i>
                        <span>Only Organization Owners and Admins have permission to modify company billing details.</span>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Card 2: Personal Profile & Security Credentials -->
        <div class="lg:col-span-5 rzp-card p-6 sm:p-8 space-y-6">
            <div class="flex items-center space-x-3.5 pb-5 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 text-lg shadow-2xs">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Personal Credentials</h2>
                    <p class="text-xs text-slate-500">Update your name &amp; login password</p>
                </div>
            </div>

            <form action="<?= app_url('/settings/profile') ?>" method="POST" class="mt-6 space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Your Full Name *</label>
                    <input type="text" name="name" value="<?= e($user['name']) ?>" required
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm font-medium focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Email Address</label>
                    <input type="email" value="<?= e($user['email']) ?>" disabled
                        class="w-full px-4 py-2.5 rounded-lg bg-slate-100 border border-slate-200 text-slate-500 text-sm font-medium cursor-not-allowed">
                    <p class="text-[11px] text-slate-400 mt-1">Contact your Organization Owner to change email ID</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Assigned RBAC Role</label>
                    <div class="px-3.5 py-1.5 rounded-lg bg-purple-50 border border-purple-200 text-xs font-bold text-purple-700 uppercase tracking-wider inline-flex items-center">
                        <i class="fa-solid fa-shield-halved text-purple-600 mr-1.5"></i>
                        <?= strtoupper(e($user['role'])) ?>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-key text-blue-600"></i> Change Account Password
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Current Password (to verify)</label>
                            <input type="password" name="current_password" placeholder="••••••••"
                                class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">New Password (min 8 chars)</label>
                            <input type="password" name="new_password" placeholder="••••••••"
                                class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">Confirm New Password</label>
                            <input type="password" name="confirm_password" placeholder="••••••••"
                                class="w-full px-3.5 py-2.5 rounded-lg bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full px-5 py-2.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-check mr-1.5"></i>
                        <span>Update Personal Profile</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Card 3: Developer REST API & Webhooks -->
    <?php 
    $new_api_key = \App\Core\Session::get('new_api_key');
    if ($new_api_key) {
        \App\Core\Session::remove('new_api_key');
    }
    ?>
    <div class="rzp-card p-6 sm:p-8 space-y-6">
        <div class="flex items-center justify-between pb-5 border-b border-slate-100">
            <div class="flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 text-lg shadow-2xs">
                    <i class="fa-solid fa-code"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Developer REST API &amp; Secret Keys</h2>
                    <p class="text-xs text-slate-500">Programmatically forge invoices, retrieve customer telemetry, and integrate external backends.</p>
                </div>
            </div>
            <a href="<?= app_url('/api/v1/docs') ?>" target="_blank" class="px-3 py-1.5 rounded-lg bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-700 text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-book text-xs"></i>
                <span>API Docs (JSON)</span>
            </a>
        </div>

        <?php if ($new_api_key): ?>
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-300 text-amber-950 space-y-2 shadow-xs">
                <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-amber-900">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
                    <span>Save Your New API Key Now!</span>
                </div>
                <p class="text-xs text-amber-800 leading-relaxed">
                    This key will only be displayed <strong>once</strong>. For security reasons, we do not store raw API keys. Please copy and store it securely.
                </p>
                <div class="flex items-center gap-2 pt-1">
                    <input type="text" id="rawApiKeyInput" readonly value="<?= e($new_api_key) ?>" 
                           class="flex-1 px-3.5 py-2 rounded-lg bg-white border border-amber-300 font-mono text-xs font-bold text-slate-900 select-all outline-none">
                    <button onclick="copyToClipboard('rawApiKeyInput', this)" 
                            class="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                        <i class="fa-regular fa-copy"></i>
                        <span>Copy Key</span>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Generate New Key Form -->
            <div class="lg:col-span-5 bg-slate-50 border border-slate-200 rounded-xl p-5 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-key text-purple-600"></i> Generate New Secret Key
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Create an authenticated token to authorize requests via the <code class="px-1.5 py-0.5 rounded bg-slate-200 text-slate-800 font-mono text-[11px]">Authorization: Bearer ak_live_...</code> header.
                </p>
                <form action="<?= app_url('/settings/api-keys') ?>" method="POST" class="space-y-3">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Key Name / Description</label>
                        <input type="text" name="key_name" placeholder="e.g. Production Billing Microservice" required
                               class="w-full px-3.5 py-2 rounded-lg bg-white border border-slate-300 text-xs text-slate-900 font-medium focus:border-purple-600 focus:ring-1 focus:ring-purple-600 outline-none transition">
                    </div>
                    <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Generate Live API Key</span>
                    </button>
                </form>
            </div>

            <!-- Existing Keys List -->
            <div class="lg:col-span-7 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center justify-between">
                    <span>Active API Tokens (<?= count($apiKeys ?? []) ?>)</span>
                    <span class="text-[11px] text-slate-400 font-normal">SHA-256 Hashed</span>
                </h3>

                <?php if (empty($apiKeys)): ?>
                    <div class="p-6 rounded-xl border border-dashed border-slate-300 text-center text-xs text-slate-500 bg-slate-50">
                        <i class="fa-solid fa-key text-slate-400 text-xl mb-2 block"></i>
                        No API keys generated yet. Click "Generate Live API Key" to create your first developer token.
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden bg-white">
                        <?php foreach ($apiKeys as $key): ?>
                            <div class="p-3.5 flex items-center justify-between gap-3 text-xs <?= $key['status'] === 'revoked' ? 'opacity-50 bg-slate-50' : '' ?>">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900 truncate"><?= e($key['name']) ?></span>
                                        <?php if ($key['status'] === 'active'): ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Revoked</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                        Prefix: <?= e($key['key_prefix']) ?> &bull; Created: <?= date('M j, Y', strtotime($key['created_at'])) ?>
                                        <?= !empty($key['last_used_at']) ? ' &bull; Last used: ' . date('M j, H:i', strtotime($key['last_used_at'])) : ' &bull; Never used' ?>
                                    </div>
                                </div>
                                <?php if ($key['status'] === 'active'): ?>
                                    <form action="<?= app_url('/settings/api-keys/revoke/' . $key['id']) ?>" method="POST" onsubmit="return confirm('Are you sure you want to revoke this API key? Any integration using it will immediately stop working.')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition">
                                            Revoke
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- cURL Snippet Example -->
                <div class="mt-4 p-4 rounded-xl bg-slate-900 text-slate-200 text-xs space-y-2 font-mono">
                    <div class="flex items-center justify-between text-slate-400 text-[11px]">
                        <span><i class="fa-solid fa-terminal mr-1"></i> Quick cURL Test</span>
                        <span class="text-purple-400">REST v1</span>
                    </div>
                    <pre class="overflow-x-auto text-[11px] text-emerald-400 selection:bg-purple-600">curl -X GET "<?= app_url('/api/v1/invoices') ?>" \
  -H "Authorization: Bearer &lt;YOUR_API_KEY&gt;"</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(elementId, btn) {
    const input = document.getElementById(elementId);
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value).then(() => {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-check"></i> <span>Copied!</span>';
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    });
}
</script>
