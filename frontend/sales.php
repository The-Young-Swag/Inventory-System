<?php // frontend/sales.php ?>

<div class="p-8 space-y-8">

    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">Sales</h1>
            <p class="text-sm text-slate-500 mt-1">All recorded sales transactions</p>
        </div>
        <button id="btnAddSale"
            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium bg-emerald-500 hover:bg-emerald-400 text-white shadow-lg shadow-emerald-500/20 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Record Sale
        </button>
    </div>

    <!-- Sales Stats Strip -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-slate-900 border border-slate-800/60 rounded-xl px-5 py-4 flex items-center gap-4">
            <div class="w-9 h-9 rounded-xl bg-sky-400/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Total Transactions</p>
                <p class="text-xl font-semibold text-white" id="statTxCount">—</p>
            </div>
        </div>
        <div class="bg-slate-900 border border-slate-800/60 rounded-xl px-5 py-4 flex items-center gap-4">
            <div class="w-9 h-9 rounded-xl bg-amber-400/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Total Units Sold</p>
                <p class="text-xl font-semibold text-white" id="statUnitsSold">—</p>
            </div>
        </div>
        <div class="bg-slate-900 border border-emerald-900/40 rounded-xl px-5 py-4 flex items-center gap-4">
            <div class="w-9 h-9 rounded-xl bg-emerald-400/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-wider text-emerald-500/60 font-semibold">Total Profit</p>
                <p class="text-xl font-semibold text-emerald-400" id="statTotalProfit">—</p>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="bg-slate-900 border border-slate-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/60">
            <div class="flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-sky-400"></div>
                <h2 class="text-sm font-semibold text-white">Transactions</h2>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input id="searchSales" type="text" placeholder="Filter by batch ID…"
                        class="bg-slate-800 border border-slate-700 rounded-lg pl-8 pr-3 py-1.5 text-xs text-slate-300 placeholder-slate-600 focus:outline-none focus:border-slate-500 w-48">
                </div>
                <button id="refreshSales" class="p-1.5 rounded-lg hover:bg-slate-800 text-slate-500 hover:text-slate-300 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs
                [&_thead_th]:px-5 [&_thead_th]:py-3.5 [&_thead_th]:text-left [&_thead_th]:text-[10px] [&_thead_th]:uppercase [&_thead_th]:tracking-wider [&_thead_th]:text-slate-500 [&_thead_th]:font-semibold [&_thead_th]:bg-slate-800/50 [&_thead_th]:whitespace-nowrap
                [&_tbody_td]:px-5 [&_tbody_td]:py-3.5 [&_tbody_td]:text-slate-300 [&_tbody_td]:whitespace-nowrap [&_tbody_td]:border-b [&_tbody_td]:border-slate-800/40
                [&_tbody_tr:last-child_td]:border-0
                [&_tbody_tr]:transition-colors [&_tbody_tr:hover]:bg-slate-800/30">
                <thead>
                    <tr>
                        <th>Sale ID</th>
                        <th>Batch ID</th>
                        <th>Qty Sold</th>
                        <th>Unit Cost</th>
                        <th>Unit Price</th>
                        <th>Total Cost</th>
                        <th>Revenue</th>
                        <th>Profit</th>
                        <th>Sale Date</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <tr><td colspan="9" class="text-center text-slate-600 py-10">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-5 h-5 border-2 border-slate-700 border-t-sky-400 rounded-full animate-spin"></div>
                            <span class="text-xs">Loading transactions…</span>
                        </div>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
(function(){

    function peso(v) {
        return '₱' + parseFloat(v || 0).toLocaleString('en-PH', { minimumFractionDigits:2, maximumFractionDigits:2 });
    }

    function loadSales() {
        $.post('backend/bk_dashboard.php', { request: 'getSalesTransaction' }, function(data) {
            $('#salesTableBody').html(data || '<tr><td colspan="9" class="text-center text-slate-600 py-8 text-xs">No transactions found</td></tr>');

            // Style profit cells + compute stats
            let txCount = 0, unitsSold = 0, totalProfit = 0;
            $('#salesTableBody tr').each(function(){
                const tds = $(this).find('td');
                if (tds.length >= 9) {
                    txCount++;
                    unitsSold   += parseInt(tds.eq(2).text()) || 0;
                    const profit = parseFloat(tds.eq(7).text().replace(/[^0-9.\-]/g,'')) || 0;
                    totalProfit += profit;
                    tds.eq(7).addClass(profit >= 0 ? 'text-emerald-400 font-semibold' : 'text-rose-400 font-semibold');
                }
            });
            $('#statTxCount').text(txCount);
            $('#statUnitsSold').text(unitsSold.toLocaleString());
            $('#statTotalProfit').text(peso(totalProfit));
            if (totalProfit < 0) $('#statTotalProfit').removeClass('text-emerald-400').addClass('text-rose-400');
        });
    }

    loadSales();

    $('#refreshSales').on('click', function() {
        $('#salesTableBody').html('<tr><td colspan="9" class="text-center text-slate-600 py-8 text-xs"><div class="w-4 h-4 border-2 border-slate-700 border-t-sky-400 rounded-full animate-spin mx-auto"></div></td></tr>');
        loadSales();
    });

    $('#searchSales').on('input', function() {
        const q = $(this).val().toLowerCase();
        $('#salesTableBody tr').each(function(){
            $(this).toggle($(this).text().toLowerCase().includes(q));
        });
    });

    $('#btnAddSale').on('click', function() {
        $.post('frontend/modals.php', { modal: 'addSale' }, function(data) {
            $('#modalContainer').html(data).removeClass('hidden');
        });
    });

})();
</script>