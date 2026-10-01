<?php
// views/admin/webhooks.php
$events = $events ?? ($webhooks ?? []);

function wh_event_badge(string $event): string {
    if (strpos($event, 'payment.') === 0 || strpos($event, 'invoice.payment_succeeded') !== false) {
        $cls = 'bg-emerald-50 text-emerald-800 border border-emerald-200';
    } elseif (strpos($event, 'subscription.') === 0) {
        $cls = 'bg-blue-50 text-[#0C66E4] border border-blue-200';
    } elseif (strpos($event, 'tenant.') === 0) {
        $cls = 'bg-rose-50 text-rose-800 border border-rose-200';
    } elseif (strpos($event, 'payment_failed') !== false) {
        $cls = 'bg-rose-50 text-rose-800 border border-rose-200';
    } else {
        $cls = 'bg-amber-50 text-amber-800 border border-amber-200';
    }
    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold font-mono ' . $cls . '">' . e($event) . '</span>';
}

function wh_status_badge(string $status): string {
    $status = strtolower($status);
    if ($status === 'processed' || $status === 'delivered') {
        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>' . e($status) . '</span>';
    } elseif ($status === 'failed') {
        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-800 border border-rose-200"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>' . e($status) . '</span>';
    } else {
        return '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>' . e($status) . '</span>';
    }
}
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Webhook &amp; Idempotency Audit Log</h1>
            <span class="bg-rose-50 text-rose-700 border border-rose-200 text-xs font-mono font-bold px-2.5 py-0.5 rounded-full">
                <?= count($events) ?> Events
            </span>
        </div>
        <p class="text-slate-500 text-xs sm:text-sm mt-1">Real-time audit log of all incoming gateway webhooks, signature validations, and duplicate prevention state machine transitions.</p>
    </div>
</div>

<!-- Webhooks Table -->
<div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
    <?php if (empty($events)): ?>
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <i class="fa-solid fa-bolt text-slate-300 text-5xl mb-4"></i>
            <p class="text-slate-700 font-semibold mb-1">No webhook events logged yet</p>
            <p class="text-slate-400 text-xs">Trigger test events from the Webhook Simulator in the client portal to see live idempotency logs.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 uppercase font-bold text-[11px] bg-slate-50">
                        <th class="px-6 py-3.5">Event Type</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Gateway</th>
                        <th class="px-5 py-3.5">External Event ID</th>
                        <th class="px-6 py-3.5">Payload Preview</th>
                        <th class="px-5 py-3.5 text-right">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($events as $e): ?>
                        <?php
                        $payloadRaw = $e['payload'] ?? '';
                        if (is_array($payloadRaw)) {
                            $payloadFormatted = json_encode($payloadRaw);
                        } else {
                            $payloadFormatted = (string)$payloadRaw;
                        }
                        $preview = strlen($payloadFormatted) > 70 ? substr($payloadFormatted, 0, 70) . '...' : $payloadFormatted;
                        ?>
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Event Type -->
                            <td class="px-6 py-4">
                                <?= wh_event_badge($e['event_type'] ?? 'unknown') ?>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4">
                                <?= wh_status_badge($e['status'] ?? 'processed') ?>
                                <?php if (!empty($e['error_message'])): ?>
                                    <span class="block text-[10px] text-rose-600 font-mono mt-0.5 truncate max-w-[150px]"><?= e($e['error_message']) ?></span>
                                <?php endif; ?>
                            </td>

                            <!-- Gateway -->
                            <td class="px-5 py-4">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-mono text-[10px] uppercase font-bold border border-slate-200">
                                    <?= e($e['gateway'] ?? 'stripe') ?>
                                </span>
                            </td>

                            <!-- Event ID -->
                            <td class="px-5 py-4 font-mono text-slate-700 text-xs font-semibold">
                                <?= e($e['external_event_id'] ?? $e['id']) ?>
                            </td>

                            <!-- Payload Preview -->
                            <td class="px-6 py-4 font-mono text-[11px] text-slate-600">
                                <span class="bg-slate-50 px-2 py-1 rounded border border-slate-200 select-all" title="<?= e($payloadFormatted) ?>">
                                    <?= e($preview) ?>
                                </span>
                            </td>

                            <!-- Timestamp -->
                            <td class="px-5 py-4 text-right font-mono text-[11px] text-slate-500">
                                <?= !empty($e['created_at']) ? date('M j, Y H:i:s', strtotime($e['created_at'])) : 'N/A' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>