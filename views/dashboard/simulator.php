<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="<?= app_url('/dashboard') ?>" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition shadow-xs">
            <i class="fa-solid fa-arrow-left text-[11px] text-slate-500"></i>
            <span>Back to Dashboard</span>
        </a>

        <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-xs font-bold text-amber-800 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            <span>Developer Sandbox Environment</span>
        </div>
    </div>

    <!-- Header Banner -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-xs relative overflow-hidden">
        <div class="flex items-center space-x-3 mb-2 relative z-10">
            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-lg shadow-xs">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Razorpay &amp; Stripe Webhook Event Simulator</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Test real-time webhook event ingestion, idempotency, and state transitions</p>
            </div>
        </div>
        <p class="text-xs text-slate-600 leading-relaxed mt-3 relative z-10">
            This developer utility allows you to simulate incoming production webhook payloads directly against
            <code class="text-[#0C66E4] bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded font-mono font-bold">/api/webhooks/stripe</code>. Test signature verification, idempotency duplicate rejection, and state machine transitions in real time!
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Action 1: Successful Payment Event -->
        <div class="bg-white border-t-4 border-t-emerald-500 border border-slate-200 rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between">
            <div class="p-6">
                <div class="flex items-center space-x-2 text-emerald-700 font-extrabold text-sm mb-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>invoice.payment_succeeded</span>
                </div>
                <p class="text-xs text-slate-500 font-medium mb-4 leading-relaxed">
                    Simulates a recurring invoice payment of &#8377;6,499.00. Generates an instant tax invoice with 18% GST and sets tenant status to active.
                </p>
            </div>
            <div class="px-6 pb-6">
                <button onclick="triggerWebhook('invoice.payment_succeeded')" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-xs flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Dispatch Payment Succeeded Event</span>
                </button>
            </div>
        </div>

        <!-- Action 2: Payment Failed Event -->
        <div class="bg-white border-t-4 border-t-rose-500 border border-slate-200 rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between">
            <div class="p-6">
                <div class="flex items-center space-x-2 text-rose-700 font-extrabold text-sm mb-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                    <span>invoice.payment_failed</span>
                </div>
                <p class="text-xs text-slate-500 font-medium mb-4 leading-relaxed">
                    Simulates card declined on renewal. Subscription state machine transitions immediately into <code class="text-rose-700 bg-rose-50 border border-rose-200 px-1.5 py-0.5 rounded font-mono font-bold">past_due</code>.
                </p>
            </div>
            <div class="px-6 pb-6">
                <button onclick="triggerWebhook('invoice.payment_failed')" class="w-full py-3 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition shadow-xs flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-ban text-xs"></i>
                    <span>Dispatch Payment Failed Event</span>
                </button>
            </div>
        </div>

        <!-- Action 3: Test Idempotency with Same ID -->
        <div class="bg-white border-t-4 border-t-[#0C66E4] border border-slate-200 rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition md:col-span-2">
            <div class="p-6">
                <div class="flex items-center space-x-2 text-[#0C66E4] font-extrabold text-sm mb-2">
                    <i class="fa-solid fa-repeat text-[#0C66E4]"></i>
                    <span>Idempotency Test: Fixed Event ID (evt_fixed_test_repeat_01)</span>
                </div>
                <p class="text-xs text-slate-600 font-medium mb-4 leading-relaxed">
                    Click this button multiple times. The <strong class="text-slate-900 font-bold">first click</strong> processes the event.
                    The <strong class="text-slate-900 font-bold">second and subsequent clicks</strong> recognize the duplicate event ID, bypass side-effects, and safely return <code class="text-[#0C66E4] bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded font-mono font-bold">duplicate</code> with HTTP 200 OK.
                </p>
                <button onclick="triggerWebhook('idempotency_repeat')" class="py-3 px-5 rounded-xl bg-[#0C66E4] hover:bg-[#0055CC] text-white font-bold text-xs transition shadow-xs flex items-center space-x-2">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                    <span>Test Idempotency Processing (Duplicate Event)</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Live Execution Output Log -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider font-mono flex items-center gap-2">
                <i class="fa-solid fa-terminal text-[#0C66E4]"></i> Real-Time Webhook Engine Log
            </span>
            <button onclick="clearLogs()" class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-200 transition">
                Clear
            </button>
        </div>
        <pre id="outputLog" class="font-mono text-xs text-emerald-400 bg-slate-900 border border-slate-800 p-4 rounded-xl overflow-x-auto min-h-[120px]"><span class="inline-block w-2 h-2 rounded-full bg-emerald-400 mr-2 align-middle" style="animation: pulse 2s cubic-bezier(0.4,0,0.6,1) infinite;">&#9679;</span>Ready to dispatch test webhooks. Click any button above...</pre>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
</style>

<script>
async function triggerWebhook(type) {
    const logBox = document.getElementById('outputLog');
    logBox.innerText = `[${new Date().toLocaleTimeString()}] Dispatching ${type} to /api/webhooks/stripe...`;

    let eventId = (type === 'idempotency_repeat') ? 'evt_fixed_test_repeat_01' : 'evt_test_' + Date.now();
    let eventType = (type === 'invoice.payment_failed') ? 'invoice.payment_failed' : 'invoice.payment_succeeded';

    const payload = {
        id: eventId,
        type: eventType,
        created: Math.floor(Date.now() / 1000),
        data: {
            object: {
                tenant_id: '<?= e($tenant['id']) ?>',
                customer: '<?= e($tenant['id']) ?>',
                amount_paid: 649900,
                currency: 'inr',
                subscription: 'sub_live_mock_acme_7900'
            }
        }
    };

    try {
        const res = await fetch('<?= app_url('/api/webhooks/stripe') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Stripe-Signature': 'test-simulation-token'
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();
        logBox.innerText = `[${new Date().toLocaleTimeString()}] HTTP Status: ${res.status}\n` + JSON.stringify(data, null, 2);
    } catch (err) {
        logBox.innerText = `[${new Date().toLocaleTimeString()}] Request Error: ` + err.message;
    }
}

function clearLogs() {
    document.getElementById('outputLog').innerText = 'Log cleared. Ready for next trigger...';
}
</script>