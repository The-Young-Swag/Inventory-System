<?php // frontend/settings.php ?>

<div class="p-8 space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-white">Settings</h1>
        <p class="text-sm text-slate-500 mt-1">System preferences and configuration</p>
    </div>

    <!-- Settings Sections -->
    <div class="max-w-2xl space-y-6">

        <!-- General -->
        <div class="bg-slate-900 border border-slate-800/60 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800/60">
                <h2 class="text-sm font-semibold text-white">General</h2>
                <p class="text-xs text-slate-500 mt-0.5">Basic system information</p>
            </div>
            <div class="divide-y divide-slate-800/40">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-200 font-medium">System Name</p>
                        <p class="text-xs text-slate-500 mt-0.5">Display name for the application</p>
                    </div>
                    <input type="text" value="InvTrack"
                        class="bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-slate-500 w-44 text-right">
                </div>
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-200 font-medium">Currency</p>
                        <p class="text-xs text-slate-500 mt-0.5">Default currency symbol</p>
                    </div>
                    <select class="bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-slate-500 w-44">
                        <option value="PHP" selected>₱ Philippine Peso</option>
                        <option value="USD">$ US Dollar</option>
                        <option value="EUR">€ Euro</option>
                    </select>
                </div>
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-200 font-medium">Timezone</p>
                        <p class="text-xs text-slate-500 mt-0.5">Server timezone setting</p>
                    </div>
                    <select class="bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-slate-500 w-44">
                        <option value="Asia/Manila" selected>Asia/Manila (PHT)</option>
                        <option value="UTC">UTC</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Inventory Alerts -->
        <div class="bg-slate-900 border border-slate-800/60 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800/60">
                <h2 class="text-sm font-semibold text-white">Inventory Alerts</h2>
                <p class="text-xs text-slate-500 mt-0.5">Configure low stock thresholds</p>
            </div>
            <div class="divide-y divide-slate-800/40">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-200 font-medium">Low Stock Threshold</p>
                        <p class="text-xs text-slate-500 mt-0.5">Warn when stock falls below this %</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" value="30" min="1" max="100"
                            class="bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-slate-500 w-20 text-right">
                        <span class="text-xs text-slate-500">%</span>
                    </div>
                </div>
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-200 font-medium">Critical Stock Threshold</p>
                        <p class="text-xs text-slate-500 mt-0.5">Mark as critical below this %</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="number" value="10" min="1" max="100"
                            class="bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-xs text-slate-200 focus:outline-none focus:border-slate-500 w-20 text-right">
                        <span class="text-xs text-slate-500">%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Info -->
        <div class="bg-slate-900 border border-slate-800/60 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800/60">
                <h2 class="text-sm font-semibold text-white">Database</h2>
                <p class="text-xs text-slate-500 mt-0.5">Connection information</p>
            </div>
            <div class="divide-y divide-slate-800/40">
                <div class="px-6 py-4 flex items-center justify-between">
                    <p class="text-xs text-slate-500">Host</p>
                    <span class="text-xs text-slate-300 font-mono bg-slate-800 px-2 py-1 rounded">127.0.0.1</span>
                </div>
                <div class="px-6 py-4 flex items-center justify-between">
                    <p class="text-xs text-slate-500">Database</p>
                    <span class="text-xs text-slate-300 font-mono bg-slate-800 px-2 py-1 rounded">inventory_profit_system</span>
                </div>
                <div class="px-6 py-4 flex items-center justify-between">
                    <p class="text-xs text-slate-500">Engine</p>
                    <span class="text-xs text-emerald-400 font-mono bg-emerald-400/10 border border-emerald-400/20 px-2 py-1 rounded">MariaDB 10.4.32</span>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end">
            <button id="btnSaveSettings"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium bg-emerald-500 hover:bg-emerald-400 text-white shadow-lg shadow-emerald-500/20 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Save Settings
            </button>
        </div>
    </div>

</div>

<script>
(function(){
    $('#btnSaveSettings').on('click', function() {
        if (window.showToast) showToast('Settings saved successfully', 'success');
    });
})();
</script>