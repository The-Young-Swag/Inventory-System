<?php // frontend/dashboard.php ?>

<div class="p-8 space-y-8">

    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Overview of your inventory and profit performance</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-900 border border-slate-800 rounded-lg px-3 py-2">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span id="dashboardTime">Loading…</span>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

        <!-- Total Capital -->
        <div class="bg-slate-900 border border-slate-800/60 rounded-2xl p-5 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Capital</span>
                <div class="w-8 h-8 bg-amber-400/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-semibold text-white" id="kpi-capital">—</p>
                <p class="text-xs text-slate-600 mt-0.5">Invested across all batches</p>
            </div>
        </div>

        <!-- Inventory Value -->
        <div class="bg-slate-900 border border-slate-800/60 rounded-2xl p-5 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Inventory Value</span>
                <div class="w-8 h-8 bg-blue-400/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-semibold text-white" id="kpi-invvalue">—</p>
                <p class="text-xs text-slate-600 mt-0.5">Current stock on hand</p>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-slate-900 border border-slate-800/60 rounded-2xl p-5 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Revenue</span>
                <div class="w-8 h-8 bg-sky-400/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-semibold text-white" id="kpi-revenue">—</p>
                <p class="text-xs text-slate-600 mt-0.5">From all sales to date</p>
            </div>
        </div>

        <!-- Total Profit -->
        <div class="bg-slate-900 border border-emerald-900/40 rounded-2xl p-5 space-y-4 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent pointer-events-none"></div>
            <div class="flex items-center justify-between relative">
                <span class="text-xs font-medium text-emerald-400/70 uppercase tracking-wider">Total Profit</span>
                <div class="w-8 h-8 bg-emerald-400/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
            <div class="relative">
                <p class="text-2xl font-semibold text-emerald-400" id="kpi-profit">—</p>
                <p class="text-xs text-emerald-900/80 mt-0.5">Net earnings after cost</p>
            </div>
        </div>

    </div>

    <!-- Summary Tables Row -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <!-- Inventory Summary -->
        <div class="bg-slate-900 border border-slate-800/60 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                    <h2 class="text-sm font-semibold text-white">Inventory Summary</h2>
                </div>
                <span class="text-[10px] text-slate-600 bg-slate-800 px-2 py-0.5 rounded-full">Per Product</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs
                    [&_thead_th]:px-4 [&_thead_th]:py-3 [&_thead_th]:text-left [&_thead_th]:text-[10px] [&_thead_th]:uppercase [&_thead_th]:tracking-wider [&_thead_th]:text-slate-500 [&_thead_th]:font-semibold [&_thead_th]:bg-slate-800/40 [&_thead_th]:whitespace-nowrap
                    [&_tbody_td]:px-4 [&_tbody_td]:py-3 [&_tbody_td]:text-slate-300 [&_tbody_td]:whitespace-nowrap [&_tbody_td]:border-b [&_tbody_td]:border-slate-800/40
                    [&_tbody_tr:last-child_td]:border-0
                    [&_tbody_tr]:transition-colors [&_tbody_tr:hover]:bg-slate-800/40">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Purchased</th>
                            <th>Remaining</th>
                            <th>Capital</th>
                            <th>Stock Value</th>
                        </tr>
                    </thead>
                    <tbody id="dashboardInventorySummary">
                        <tr><td colspan="6" class="text-center text-slate-600 py-8">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-5 h-5 border-2 border-slate-700 border-t-blue-400 rounded-full animate-spin"></div>
                                <span>Loading…</span>
                            </div>
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Profit Summary -->
        <div class="bg-slate-900 border border-slate-800/60 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                    <h2 class="text-sm font-semibold text-white">Profit Summary</h2>
                </div>
                <span class="text-[10px] text-slate-600 bg-slate-800 px-2 py-0.5 rounded-full">Per Product</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs
                    [&_thead_th]:px-4 [&_thead_th]:py-3 [&_thead_th]:text-left [&_thead_th]:text-[10px] [&_thead_th]:uppercase [&_thead_th]:tracking-wider [&_thead_th]:text-slate-500 [&_thead_th]:font-semibold [&_thead_th]:bg-slate-800/40 [&_thead_th]:whitespace-nowrap
                    [&_tbody_td]:px-4 [&_tbody_td]:py-3 [&_tbody_td]:text-slate-300 [&_tbody_td]:whitespace-nowrap [&_tbody_td]:border-b [&_tbody_td]:border-slate-800/40
                    [&_tbody_tr:last-child_td]:border-0
                    [&_tbody_tr]:transition-colors [&_tbody_tr:hover]:bg-slate-800/40">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Sold</th>
                            <th>Cost</th>
                            <th>Revenue</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody id="dashboardProfitSummary">
                        <tr><td colspan="6" class="text-center text-slate-600 py-8">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-5 h-5 border-2 border-slate-700 border-t-emerald-400 rounded-full animate-spin"></div>
                                <span>Loading…</span>
                            </div>
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script>
(function(){

    // Live clock
    function tick() {
        const now = new Date();
        $('#dashboardTime').text(now.toLocaleString('en-PH', { dateStyle:'medium', timeStyle:'short' }));
    }
    tick();
    setInterval(tick, 60000);

    // Currency formatter
    function peso(v) {
        return '₱' + parseFloat(v || 0).toLocaleString('en-PH', { minimumFractionDigits:2, maximumFractionDigits:2 });
    }

    // ── Load Inventory Summary ─────────────────────────────────────────────
    $.post('backend/bk_dashboard.php', { request: 'getInventorySummary' }, function(data) {
        $('#dashboardInventorySummary').html(data || '<tr><td colspan="6" class="text-center text-slate-600 py-6">No data</td></tr>');

        // Compute KPIs from injected rows
        let capital = 0, invVal = 0;
        $('#dashboardInventorySummary tr').each(function(){
            const tds = $(this).find('td');
            if (tds.length >= 6) {
                capital += parseFloat(tds.eq(4).text().replace(/[^0-9.]/g,'')) || 0;
                invVal  += parseFloat(tds.eq(5).text().replace(/[^0-9.]/g,'')) || 0;
            }
        });
        $('#kpi-capital').text(peso(capital));
        $('#kpi-invvalue').text(peso(invVal));
    });

    // ── Load Profit Summary ────────────────────────────────────────────────
    $.post('backend/bk_dashboard.php', { request: 'getProfitSummary' }, function(data) {
        $('#dashboardProfitSummary').html(data || '<tr><td colspan="6" class="text-center text-slate-600 py-6">No data</td></tr>');

        // Color profit cells
        $('#dashboardProfitSummary tr').each(function(){
            const tds  = $(this).find('td');
            if (tds.length >= 6) {
                const profit = parseFloat(tds.eq(5).text().replace(/[^0-9.\-]/g,'')) || 0;
                tds.eq(5).addClass(profit >= 0 ? 'text-emerald-400 font-semibold' : 'text-rose-400 font-semibold');
            }
        });

        // Compute KPIs
        let revenue = 0, profit = 0;
        $('#dashboardProfitSummary tr').each(function(){
            const tds = $(this).find('td');
            if (tds.length >= 6) {
                revenue += parseFloat(tds.eq(4).text().replace(/[^0-9.]/g,'')) || 0;
                profit  += parseFloat(tds.eq(5).text().replace(/[^0-9.\-]/g,'')) || 0;
            }
        });
        $('#kpi-revenue').text(peso(revenue));
        $('#kpi-profit').text(peso(profit));
        if (profit < 0) $('#kpi-profit').removeClass('text-emerald-400').addClass('text-rose-400');
    });

})();
</script>