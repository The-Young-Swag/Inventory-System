<?php // frontend/reports.php ?>

<div class="p-8 space-y-8">

    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">Reports</h1>
            <p class="text-sm text-slate-500 mt-1">Financial performance and inventory analytics</p>
        </div>
        <button id="btnExportCSV"
            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Export CSV
        </button>
    </div>

    <!-- Totals Overview Strip -->
    <div class="bg-slate-900 border border-slate-800/60 rounded-2xl px-6 py-5">
        <p class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold mb-4">Combined Totals — All Products</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div>
                <p class="text-xs text-slate-500 mb-1">Qty Sold</p>
                <p class="text-xl font-semibold text-white" id="rep-qty">—</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Total Cost</p>
                <p class="text-xl font-semibold text-amber-400" id="rep-cost">—</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Total Revenue</p>
                <p class="text-xl font-semibold text-sky-400" id="rep-revenue">—</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Net Profit</p>
                <p class="text-xl font-semibold text-emerald-400" id="rep-profit">—</p>
            </div>
        </div>
    </div>

    <!-- Profit Summary Table -->
    <div class="bg-slate-900 border border-slate-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/60">
            <div class="flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                <h2 class="text-sm font-semibold text-white">Profit by Product</h2>
            </div>
            <button id="refreshProfit" class="p-1.5 rounded-lg hover:bg-slate-800 text-slate-500 hover:text-slate-300 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table id="profitReportTable" class="w-full text-xs
                [&_thead_th]:px-5 [&_thead_th]:py-3.5 [&_thead_th]:text-left [&_thead_th]:text-[10px] [&_thead_th]:uppercase [&_thead_th]:tracking-wider [&_thead_th]:text-slate-500 [&_thead_th]:font-semibold [&_thead_th]:bg-slate-800/50 [&_thead_th]:whitespace-nowrap
                [&_tbody_td]:px-5 [&_tbody_td]:py-4 [&_tbody_td]:text-slate-300 [&_tbody_td]:whitespace-nowrap [&_tbody_td]:border-b [&_tbody_td]:border-slate-800/40
                [&_tbody_tr:last-child_td]:border-0
                [&_tbody_tr]:transition-colors [&_tbody_tr:hover]:bg-slate-800/30">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Qty Sold</th>
                        <th>Total Cost</th>
                        <th>Total Revenue</th>
                        <th>Net Profit</th>
                        <th>Margin</th>
                    </tr>
                </thead>
                <tbody id="profitSummaryBody">
                    <tr><td colspan="7" class="text-center text-slate-600 py-10">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-5 h-5 border-2 border-slate-700 border-t-emerald-400 rounded-full animate-spin"></div>
                            <span class="text-xs">Loading report…</span>
                        </div>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inventory Analysis Table -->
    <div class="bg-slate-900 border border-slate-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/60">
            <div class="flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                <h2 class="text-sm font-semibold text-white">Inventory Analysis</h2>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs
                [&_thead_th]:px-5 [&_thead_th]:py-3.5 [&_thead_th]:text-left [&_thead_th]:text-[10px] [&_thead_th]:uppercase [&_thead_th]:tracking-wider [&_thead_th]:text-slate-500 [&_thead_th]:font-semibold [&_thead_th]:bg-slate-800/50 [&_thead_th]:whitespace-nowrap
                [&_tbody_td]:px-5 [&_tbody_td]:py-4 [&_tbody_td]:text-slate-300 [&_tbody_td]:whitespace-nowrap [&_tbody_td]:border-b [&_tbody_td]:border-slate-800/40
                [&_tbody_tr:last-child_td]:border-0
                [&_tbody_tr]:transition-colors [&_tbody_tr:hover]:bg-slate-800/30">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Total Purchased</th>
                        <th>Stock Remaining</th>
                        <th>Capital Invested</th>
                        <th>Stock Value</th>
                        <th>Stock %</th>
                    </tr>
                </thead>
                <tbody id="invAnalysisBody">
                    <tr><td colspan="7" class="text-center text-slate-600 py-10">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-5 h-5 border-2 border-slate-700 border-t-blue-400 rounded-full animate-spin"></div>
                            <span class="text-xs">Loading…</span>
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

    function pct(v) {
        return (parseFloat(v || 0) * 100).toFixed(1) + '%';
    }

    // ── Profit Summary ─────────────────────────────────────────────────────
    function loadProfitReport() {
        $.post('backend/bk_dashboard.php', { request: 'getProfitSummary' }, function(data) {
            if (!data.trim()) {
                $('#profitSummaryBody').html('<tr><td colspan="7" class="text-center text-slate-600 py-8 text-xs">No data</td></tr>');
                return;
            }

            // Parse injected rows, add computed margin column
            const $tmp = $('<tbody>').html(data);
            let totQty = 0, totCost = 0, totRev = 0, totProfit = 0;
            const rows = [];

            $tmp.find('tr').each(function(){
                const tds = $(this).find('td');
                if (tds.length < 6) return;
                const qty    = parseFloat(tds.eq(2).text()) || 0;
                const cost   = parseFloat(tds.eq(3).text().replace(/[^0-9.]/g,'')) || 0;
                const rev    = parseFloat(tds.eq(4).text().replace(/[^0-9.]/g,'')) || 0;
                const profit = parseFloat(tds.eq(5).text().replace(/[^0-9.\-]/g,'')) || 0;
                const margin = rev > 0 ? profit / rev : 0;

                totQty    += qty;
                totCost   += cost;
                totRev    += rev;
                totProfit += profit;

                const profitClass = profit >= 0 ? 'text-emerald-400 font-semibold' : 'text-rose-400 font-semibold';
                const marginClass = margin >= 0 ? 'text-emerald-400' : 'text-rose-400';

                // Build a progress bar pill for margin
                const barWidth = Math.min(Math.abs(margin) * 100, 100).toFixed(0);
                const barColor = margin >= 0 ? 'bg-emerald-500' : 'bg-rose-500';

                rows.push(`<tr>
                    <td>${tds.eq(0).text()}</td>
                    <td class="font-medium text-slate-200">${tds.eq(1).text()}</td>
                    <td>${qty.toLocaleString()}</td>
                    <td class="text-amber-400">${peso(cost)}</td>
                    <td class="text-sky-400">${peso(rev)}</td>
                    <td class="${profitClass}">${peso(profit)}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-slate-800 rounded-full overflow-hidden flex-shrink-0">
                                <div class="h-full ${barColor} rounded-full" style="width:${barWidth}%"></div>
                            </div>
                            <span class="${marginClass}">${pct(margin)}</span>
                        </div>
                    </td>
                </tr>`);
            });

            $('#profitSummaryBody').html(rows.join(''));

            // Update totals strip
            $('#rep-qty').text(totQty.toLocaleString());
            $('#rep-cost').text(peso(totCost));
            $('#rep-revenue').text(peso(totRev));
            $('#rep-profit').text(peso(totProfit));
            if (totProfit < 0) $('#rep-profit').removeClass('text-emerald-400').addClass('text-rose-400');
        });
    }

    // ── Inventory Analysis ─────────────────────────────────────────────────
    function loadInvAnalysis() {
        $.post('backend/bk_dashboard.php', { request: 'getInventorySummary' }, function(data) {
            if (!data.trim()) {
                $('#invAnalysisBody').html('<tr><td colspan="7" class="text-center text-slate-600 py-8 text-xs">No data</td></tr>');
                return;
            }

            const $tmp = $('<tbody>').html(data);
            const rows = [];

            $tmp.find('tr').each(function(){
                const tds = $(this).find('td');
                if (tds.length < 6) return;
                const purchased  = parseFloat(tds.eq(2).text()) || 0;
                const remaining  = parseFloat(tds.eq(3).text()) || 0;
                const capital    = parseFloat(tds.eq(4).text().replace(/[^0-9.]/g,'')) || 0;
                const stockVal   = parseFloat(tds.eq(5).text().replace(/[^0-9.]/g,'')) || 0;
                const stockPct   = purchased > 0 ? remaining / purchased : 0;
                const barWidth   = (stockPct * 100).toFixed(0);
                const barColor   = stockPct > 0.3 ? 'bg-emerald-500' : stockPct > 0.1 ? 'bg-amber-500' : 'bg-rose-500';
                const pctClass   = stockPct > 0.3 ? 'text-emerald-400' : stockPct > 0.1 ? 'text-amber-400' : 'text-rose-400';

                rows.push(`<tr>
                    <td>${tds.eq(0).text()}</td>
                    <td class="font-medium text-slate-200">${tds.eq(1).text()}</td>
                    <td>${purchased.toLocaleString()}</td>
                    <td class="${pctClass} font-semibold">${remaining.toLocaleString()}</td>
                    <td class="text-amber-400">${peso(capital)}</td>
                    <td class="text-blue-400">${peso(stockVal)}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-slate-800 rounded-full overflow-hidden flex-shrink-0">
                                <div class="h-full ${barColor} rounded-full" style="width:${barWidth}%"></div>
                            </div>
                            <span class="${pctClass}">${(stockPct * 100).toFixed(0)}%</span>
                        </div>
                    </td>
                </tr>`);
            });

            $('#invAnalysisBody').html(rows.join(''));
        });
    }

    loadProfitReport();
    loadInvAnalysis();

    $('#refreshProfit').on('click', loadProfitReport);

    // ── CSV Export ─────────────────────────────────────────────────────────
    $('#btnExportCSV').on('click', function() {
        const rows = [];
        rows.push(['Product ID','Product Name','Qty Sold','Total Cost','Total Revenue','Net Profit'].join(','));
        $('#profitSummaryBody tr').each(function(){
            const cols = [];
            $(this).find('td').slice(0,6).each(function(){ cols.push('"' + $(this).text().trim() + '"'); });
            if (cols.length) rows.push(cols.join(','));
        });
        const blob = new Blob([rows.join('\n')], { type: 'text/csv' });
        const a    = document.createElement('a');
        a.href     = URL.createObjectURL(blob);
        a.download = 'profit_report_' + new Date().toISOString().slice(0,10) + '.csv';
        a.click();
        if (window.showToast) showToast('CSV exported successfully', 'success');
    });

})();
</script>