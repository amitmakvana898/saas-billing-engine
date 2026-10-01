<div class="space-y-8 pb-12">
    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 1: INVOICE STUDIO COMMAND CROWN
         ══════════════════════════════════════════════════════════════════ -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 text-white p-6 sm:p-8 shadow-2xl border border-slate-800">
        <!-- Ambient Background Glow Accents -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center flex-wrap gap-2.5">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/30 text-blue-400 font-mono text-[11px] font-bold tracking-wider uppercase">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>Commercial Invoicing Studio &bull; Precision Engine</span>
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full bg-white/10 text-slate-300 border border-white/10 text-[10px] font-bold uppercase tracking-wider font-mono">
                        HSN / SAC Auto-Code Compliant
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-3">
                    <span>Forge B2B Tax Invoice</span>
                    <span class="font-mono text-sm px-3 py-1 rounded-xl bg-blue-600/40 border border-blue-400/40 text-blue-200 font-bold">
                        #<?= e($nextInvoiceNumber) ?>
                    </span>
                </h1>
                <p class="text-xs text-slate-400 max-w-xl">
                    Generate digitally signed, GST-compliant commercial invoices with real-time CGST/SGST/IGST tax calculation, dynamic multi-item billing, and one-tap payment link provisioning.
                </p>
            </div>

            <!-- Instant Actions Dock -->
            <div class="flex items-center space-x-3">
                <a href="<?= app_url('/invoices') ?>" 
                   class="px-4 py-3 rounded-xl bg-white/10 hover:bg-white/15 text-slate-200 border border-white/10 font-bold text-xs transition flex items-center space-x-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Invoices Ledger</span>
                </a>
                <a href="<?= app_url('/customers') ?>" 
                   class="px-4 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 font-bold text-xs transition flex items-center space-x-2">
                    <i class="fa-solid fa-users text-blue-400 text-xs"></i>
                    <span>Client Directory</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════
         LAYER 2: MAIN INVOICING WORKBENCH
         ══════════════════════════════════════════════════════════════════ -->
    <form action="<?= app_url('/invoices') ?>" method="POST" id="invoiceForm" class="space-y-6">
        <?= csrf_field() ?>

        <!-- CARD 1: ISSUER & RECIPIENT TELEMETRY -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Billed From: Issuer (5/12 cols) -->
                <div class="lg:col-span-5 p-5 rounded-2xl bg-slate-900 text-white space-y-3 relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-48 h-48 bg-blue-600/20 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-blue-400">Issuer / Organization</span>
                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-bold font-mono">Verified Root</span>
                    </div>

                    <div class="space-y-1">
                        <div class="text-lg font-black tracking-tight text-white"><?= e($tenant['name']) ?></div>
                        <div class="text-xs text-slate-400 leading-relaxed"><?= e($tenant['billing_address'] ?? 'Primary Corporate Location') ?></div>
                    </div>

                    <div class="pt-2 border-t border-slate-800 space-y-1.5 text-xs font-mono">
                        <div class="flex items-center justify-between text-slate-400">
                            <span>GSTIN:</span>
                            <strong class="text-blue-300"><?= e($tenant['tax_id'] ?? '24AAACT0000A1Z5') ?></strong>
                        </div>
                        <?php if (!empty($tenant['phone'])): ?>
                            <div class="flex items-center justify-between text-slate-400">
                                <span>Support Contact:</span>
                                <span class="text-slate-200"><?= e($tenant['phone']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Billed To: Client Selector & Inputs (7/12 cols) -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-building text-blue-600"></i>
                            <span>Billed To (Corporate Client) *</span>
                        </span>
                        <a href="<?= app_url('/customers') ?>" target="_blank" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline">
                            + Open Client Vault
                        </a>
                    </div>

                    <!-- Client Select Dropdown -->
                    <div>
                        <select id="customerSelect" name="customer_id" class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-bold focus:bg-white focus:border-blue-600 focus:ring-1 focus:ring-blue-600 outline-none transition">
                            <option value="">-- Choose Existing Client (or enter new details below) --</option>
                            <?php foreach ($customers as $cust): ?>
                                <option value="<?= e($cust['id']) ?>" 
                                        data-name="<?= e($cust['name']) ?>"
                                        data-email="<?= e($cust['email']) ?>"
                                        data-phone="<?= e($cust['phone'] ?? '') ?>"
                                        data-company="<?= e($cust['company_name'] ?? '') ?>"
                                        data-gstin="<?= e($cust['gstin'] ?? '') ?>"
                                        data-address="<?= e($cust['address'] ?? '') ?>">
                                    <?= e($cust['name']) ?> <?= !empty($cust['company_name']) ? '(' . e($cust['company_name']) . ')' : '' ?> &bull; <?= e($cust['email']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Contact Name *</label>
                            <input type="text" id="custName" name="customer_name" required placeholder="Rajesh Patel" 
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Company Name</label>
                            <input type="text" id="custCompany" name="customer_company" placeholder="Acme Technologies" 
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Work Email *</label>
                            <input type="email" id="custEmail" name="customer_email" required placeholder="client@company.com" 
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 outline-none transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">WhatsApp / Phone</label>
                            <input type="text" id="custPhone" name="customer_phone" placeholder="+91 98200 12345" 
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Client GSTIN (15 Digits)</label>
                            <input type="text" id="custGstin" name="customer_gstin" placeholder="24AAACR5055K1Z4" maxlength="15" 
                                   class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-mono font-bold uppercase placeholder-slate-400 focus:bg-white focus:border-blue-600 outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Billing Address</label>
                        <input type="text" id="custAddress" name="customer_address" placeholder="Corporate Towers, Ring Road, Ahmedabad" 
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium placeholder-slate-400 focus:bg-white focus:border-blue-600 outline-none transition">
                    </div>

                    <div class="flex items-center space-x-2 pt-1">
                        <input type="checkbox" name="save_customer" value="1" id="saveCust" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" checked>
                        <label for="saveCust" class="text-xs text-slate-600 font-medium">Save this client to directory for future automated invoicing</label>
                    </div>
                </div>
            </div>

            <!-- Invoice Meta Timeline Information -->
            <div class="pt-6 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Invoice Sequence #</label>
                    <input type="text" name="invoice_number" value="<?= e($nextInvoiceNumber) ?>" required 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs font-bold focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tax Invoice Issue Date</label>
                    <input type="date" name="issue_date" value="<?= date('Y-m-d') ?>" required 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Settlement Due Date</label>
                    <input type="date" name="due_date" value="<?= date('Y-m-d', strtotime('+15 days')) ?>" required 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 text-xs font-medium focus:bg-white focus:border-blue-600 outline-none transition">
                </div>
            </div>
        </div>

        <!-- CARD 2: DYNAMIC LINE ITEMS BUILDER -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-black text-slate-900">Line Items &amp; Commercial Scope</h3>
                    <p class="text-xs text-slate-500">Configure deliverables, software licenses, or consulting packages.</p>
                </div>
                <button type="button" onclick="addLineItem()" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold border border-blue-200 transition shadow-2xs">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>+ Add Line Item</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="itemsTable">
                    <thead class="bg-slate-50/90 text-slate-600 text-[11px] uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 min-w-[240px]">Service / Product Description *</th>
                            <th class="px-2 py-3 w-28">HSN / SAC</th>
                            <th class="px-3 py-3 w-20 text-center">Qty</th>
                            <th class="px-3 py-3 w-28">Rate (₹)</th>
                            <th class="px-3 py-3 w-24">GST %</th>
                            <th class="px-4 py-3 w-32 text-right">Line Total (₹)</th>
                            <th class="px-2 py-3 w-10 text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody" class="divide-y divide-slate-100 text-slate-800">
                        <!-- Default Line Item 1 -->
                        <tr class="item-row">
                            <td class="px-4 py-3">
                                <input type="text" name="items[0][description]" value="Enterprise Cloud Software Implementation &amp; Consulting" required placeholder="Service description..." 
                                       class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-medium text-xs outline-none focus:bg-white focus:border-blue-600">
                            </td>
                            <td class="px-2 py-3">
                                <input type="text" name="items[0][hsn]" value="998311" placeholder="998311" 
                                       class="w-full px-2 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs outline-none focus:bg-white focus:border-blue-600">
                            </td>
                            <td class="px-3 py-3">
                                <input type="number" step="1" min="1" name="items[0][qty]" value="1" oninput="calculateTotals()" 
                                       class="item-qty w-full px-2.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-bold text-xs text-center outline-none focus:bg-white focus:border-blue-600 font-mono">
                            </td>
                            <td class="px-3 py-3">
                                <input type="number" step="0.01" min="0" name="items[0][rate]" value="10000.00" oninput="calculateTotals()" 
                                       class="item-rate w-full px-2.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-bold text-xs outline-none focus:bg-white focus:border-blue-600 font-mono">
                            </td>
                            <td class="px-3 py-3">
                                <select name="items[0][tax_rate]" onchange="calculateTotals()" class="item-tax w-full px-2 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-bold text-xs outline-none">
                                    <option value="18" selected>18% GST</option>
                                    <option value="12">12% GST</option>
                                    <option value="5">5% GST</option>
                                    <option value="28">28% GST</option>
                                    <option value="0">0% (Exempt)</option>
                                </select>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="item-total font-mono font-bold text-slate-900 text-sm">₹11,800.00</span>
                            </td>
                            <td class="px-2 py-3 text-center">
                                <button type="button" onclick="removeLineItem(this)" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Remove Item">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Bottom Calculation & Totals Section -->
            <div class="pt-6 border-t border-slate-100 flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                <!-- Notes and Terms -->
                <div class="flex-1 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Payment Terms &amp; Settlement Instructions</label>
                        <textarea name="notes" rows="3" 
                                  class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-800 text-xs font-medium outline-none focus:bg-white focus:border-blue-600">Payment requested within 15 days of invoice generation. Instant online settlement via UPI QR, Cards, and Net Banking available through the provided secure link.</textarea>
                    </div>

                    <!-- Mark as Paid Checkbox -->
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200 flex items-start space-x-3">
                        <input type="checkbox" name="mark_paid" value="1" id="markPaid" class="mt-0.5 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                        <div>
                            <label for="markPaid" class="text-xs font-bold text-amber-900 block">Record invoice as already Settled / Bank Realized</label>
                            <span class="text-[11px] text-amber-700">Check if client has cleared this invoice via offline NEFT, RTGS, cheque, or cash.</span>
                        </div>
                    </div>
                </div>

                <!-- Real-time Thermal Ledger Card -->
                <div class="w-full md:w-84 p-6 rounded-3xl bg-slate-900 text-white space-y-3.5 shadow-xl border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-1">
                        Real-time Computation
                    </span>

                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">Subtotal Amount:</span>
                        <span class="font-mono font-bold text-white" id="subtotalDisplay">₹10,000.00</span>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-1.5">
                            <span class="text-slate-400">Discount:</span>
                            <input type="number" name="discount_percent" id="discountPercent" value="0" min="0" max="100" oninput="calculateTotals()" 
                                   class="w-14 px-1.5 py-0.5 rounded-lg bg-slate-800 border border-slate-700 text-white font-bold text-xs text-center outline-none font-mono">
                            <span class="text-slate-500">%</span>
                        </div>
                        <span class="font-mono font-bold text-rose-400" id="discountDisplay">-₹0.00</span>
                    </div>

                    <div id="taxBreakdownContainer" class="space-y-2 pt-2 border-t border-slate-800">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400" id="cgstLabel">CGST (9%)</span>
                            <span class="font-mono font-bold text-blue-300" id="cgstDisplay">₹900.00</span>
                        </div>

                        <div class="flex items-center justify-between text-xs" id="sgstRow">
                            <span class="text-slate-400" id="sgstLabel">SGST (9%)</span>
                            <span class="font-mono font-bold text-blue-300" id="sgstDisplay">₹900.00</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-black uppercase tracking-wider text-white block">Grand Total</span>
                            <span class="text-[10px] text-slate-400" id="taxModeSubtext">Intra-State GST</span>
                        </div>
                        <span class="text-2xl font-black text-emerald-400 font-mono tracking-tight" id="grandTotalDisplay">₹11,800.00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Submission Actions -->
        <div class="flex items-center justify-end space-x-3">
            <a href="<?= app_url('/invoices') ?>" class="px-5 py-3 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="px-7 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black text-xs shadow-lg shadow-blue-500/25 transition flex items-center space-x-2">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                <span>Save &amp; Dispatch Invoice &rarr;</span>
            </button>
        </div>
    </form>
</div>

<script>
let rowCount = 1;

// Client Selector Auto-fill
document.getElementById('customerSelect')?.addEventListener('change', function(e) {
    const selected = e.target.options[e.target.selectedIndex];
    if (selected && selected.value) {
        document.getElementById('custName').value = selected.getAttribute('data-name') || '';
        document.getElementById('custCompany').value = selected.getAttribute('data-company') || '';
        document.getElementById('custEmail').value = selected.getAttribute('data-email') || '';
        document.getElementById('custPhone').value = selected.getAttribute('data-phone') || '';
        document.getElementById('custGstin').value = selected.getAttribute('data-gstin') || '';
        document.getElementById('custAddress').value = selected.getAttribute('data-address') || '';
        document.getElementById('saveCust').checked = false; // already saved
        calculateTotals();
    }
});

document.getElementById('custGstin')?.addEventListener('input', calculateTotals);

function addLineItem() {
    const tbody = document.getElementById('itemsBody');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td class="px-4 py-3">
            <input type="text" name="items[${rowCount}][description]" required placeholder="Service / Item description..." 
                   class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-medium text-xs outline-none focus:bg-white focus:border-blue-600">
        </td>
        <td class="px-2 py-3">
            <input type="text" name="items[${rowCount}][hsn]" value="998311" placeholder="998311" 
                   class="w-full px-2 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-mono text-xs outline-none focus:bg-white focus:border-blue-600">
        </td>
        <td class="px-3 py-3">
            <input type="number" step="1" min="1" name="items[${rowCount}][qty]" value="1" oninput="calculateTotals()" 
                   class="item-qty w-full px-2.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-bold text-xs text-center outline-none focus:bg-white focus:border-blue-600 font-mono">
        </td>
        <td class="px-3 py-3">
            <input type="number" step="0.01" min="0" name="items[${rowCount}][rate]" value="1000.00" oninput="calculateTotals()" 
                   class="item-rate w-full px-2.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-bold text-xs outline-none focus:bg-white focus:border-blue-600 font-mono">
        </td>
        <td class="px-3 py-3">
            <select name="items[${rowCount}][tax_rate]" onchange="calculateTotals()" class="item-tax w-full px-2 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 font-bold text-xs outline-none">
                <option value="18" selected>18% GST</option>
                <option value="12">12% GST</option>
                <option value="5">5% GST</option>
                <option value="28">28% GST</option>
                <option value="0">0% (Exempt)</option>
            </select>
        </td>
        <td class="px-4 py-3 text-right">
            <span class="item-total font-mono font-bold text-slate-900 text-sm">₹1,180.00</span>
        </td>
        <td class="px-2 py-3 text-center">
            <button type="button" onclick="removeLineItem(this)" class="p-1 text-slate-400 hover:text-rose-600 transition" title="Remove Item">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    rowCount++;
    calculateTotals();
}

function removeLineItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('tr').remove();
        calculateTotals();
    } else {
        alert('An invoice must have at least one line item.');
    }
}

function calculateTotals() {
    let subtotal = 0;
    let totalTax = 0;

    const rows = document.querySelectorAll('.item-row');
    rows.forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const rate = parseFloat(row.querySelector('.item-rate').value) || 0;
        const taxRate = parseFloat(row.querySelector('.item-tax').value) || 0;

        const lineSubtotal = qty * rate;
        const lineTax = lineSubtotal * (taxRate / 100);
        const lineTotal = lineSubtotal + lineTax;

        subtotal += lineSubtotal;
        totalTax += lineTax;

        row.querySelector('.item-total').innerText = '₹' + lineTotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    });

    const discountPercent = Math.min(100, Math.max(0, parseFloat(document.getElementById('discountPercent').value) || 0));
    const discountAmount = subtotal * (discountPercent / 100);
    const grandTotal = Math.max(0, subtotal + totalTax - discountAmount);

    document.getElementById('subtotalDisplay').innerText = '₹' + subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    document.getElementById('discountDisplay').innerText = '-₹' + discountAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    // Check Inter-State vs Intra-State GST
    const tenantGstin = '<?= substr(e($tenant['tax_id'] ?? '24'), 0, 2) ?>';
    const custGstinVal = (document.getElementById('custGstin')?.value || '').trim();
    const custState = custGstinVal.length >= 2 ? custGstinVal.substring(0, 2) : '';
    const isInterstate = (custState !== '' && custState !== tenantGstin);

    const taxContainer = document.getElementById('taxBreakdownContainer');
    const taxSubtext = document.getElementById('taxModeSubtext');

    if (isInterstate) {
        taxSubtext.innerText = `Inter-State GST (IGST 18% - State Code: ${custState})`;
        taxContainer.innerHTML = `
            <div class="flex items-center justify-between text-xs">
                <span class="text-blue-300 font-bold">IGST (Integrated 18%)</span>
                <span class="font-mono font-bold text-blue-300">₹${totalTax.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
            </div>
        `;
    } else {
        const cgst = totalTax / 2;
        const sgst = totalTax / 2;
        taxSubtext.innerText = `Intra-State GST (CGST 9% + SGST 9%)`;
        taxContainer.innerHTML = `
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-400 font-semibold">CGST (9%)</span>
                <span class="font-mono font-bold text-blue-300">₹${cgst.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-400 font-semibold">SGST (9%)</span>
                <span class="font-mono font-bold text-blue-300">₹${sgst.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
            </div>
        `;
    }

    document.getElementById('grandTotalDisplay').innerText = '₹' + grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

calculateTotals();
</script>
