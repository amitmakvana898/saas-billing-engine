<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Resource Not Found · SaaSify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 min-h-screen flex items-center justify-center p-4 selection:bg-[#0C66E4] selection:text-white antialiased">
    <div class="text-center max-w-md w-full bg-white border border-slate-200 rounded-2xl p-8 sm:p-10 shadow-xs">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-200 text-[#0C66E4] flex items-center justify-center text-2xl mx-auto mb-4 shadow-xs">
            <i class="fa-solid fa-compass"></i>
        </div>
        <div class="text-6xl font-black text-[#0C66E4] mb-2 font-mono">404</div>
        <h2 class="text-xl font-bold text-slate-900 mb-2">Page or Endpoint Not Found</h2>
        <p class="text-slate-500 mb-6 text-xs leading-relaxed font-normal">The route or resource you are attempting to dispatch does not exist in the platform routing table.</p>
        <div class="flex items-center justify-center space-x-3">
            <a href="<?= app_url('/') ?>" class="inline-flex items-center space-x-2 py-2.5 px-4 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition shadow-xs">
                <i class="fa-solid fa-house text-xs text-slate-500"></i>
                <span>Back to Home</span>
            </a>
            <a href="<?= app_url('/dashboard') ?>" class="inline-flex items-center space-x-2 py-2.5 px-4 rounded-xl bg-[#0C66E4] hover:bg-[#0055CC] text-white font-bold text-xs shadow-xs transition">
                <span>Go to Dashboard</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</body>
</html>