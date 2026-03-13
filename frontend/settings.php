<?php // frontend/settings.php ?>

<div class="p-6 space-y-6 max-w-2xl">
    <div>
        <h1 class="text-xl font-bold text-white">Settings</h1>
        <p class="text-sm text-slate-500 mt-0.5">System configuration and preferences</p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-800">
            <h2 class="text-sm font-semibold text-white">System Info</h2>
        </div>
        <div class="p-5 space-y-4 text-sm">
            <div class="flex items-center justify-between py-2 border-b border-slate-800">
                <span class="text-slate-500">Application</span>
                <span class="text-slate-200 font-medium">StockFlow v1.0</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-slate-800">
                <span class="text-slate-500">Database</span>
                <span class="text-emerald-400 font-medium flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Connected
                </span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-slate-800">
                <span class="text-slate-500">Currency</span>
                <span class="text-slate-200 font-medium">Philippine Peso (₱)</span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-slate-500">Low Stock Threshold</span>
                <span class="text-slate-200 font-medium">≤ 5 units</span>
            </div>
        </div>
    </div>

    <div class="bg-slate-900 border border-rose-800/30 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-800">
            <h2 class="text-sm font-semibold text-rose-400">Danger Zone</h2>
        </div>
        <div class="p-5">
            <p class="text-xs text-slate-500 mb-4">Destructive actions — use with caution. These cannot be undone.</p>
            <div class="flex gap-3">
                <button class="px-4 py-2 text-xs font-semibold text-rose-400 border border-rose-800/50 hover:bg-rose-500/10 rounded-lg transition-all">
                    Clear All Sales
                </button>
                <button class="px-4 py-2 text-xs font-semibold text-rose-400 border border-rose-800/50 hover:bg-rose-500/10 rounded-lg transition-all">
                    Reset Inventory
                </button>
            </div>
        </div>
    </div>
</div>
