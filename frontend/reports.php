<?php // frontend/reports.php
$conn = require __DIR__ . "/../config/database.php";

$invSummary = $conn->query("SELECT * FROM product_inventory_summary ORDER BY product_id")->fetchAll();
$profSummary = $conn->query("SELECT * FROM product_profit_summary ORDER BY product_id")->fetchAll();

$fmt = fn($n) => '₱' . number_format((float)$n, 2);

$totalCapital = array_sum(array_column($invSummary, 'total_capital_invested'));
$totalInventoryValue = array_sum(array_column($invSummary, 'inventory_value'));
$totalRevenue = array_sum(array_column($profSummary, 'total_revenue'));
$totalProfit = array_sum(array_column($profSummary, 'total_profit'));
?>

<div class="p-6 space-y-6">

    <div>
        <h1 class="text-xl font-bold text-white">Reports</h1>
        <p class="text-sm text-slate-500 mt-0.5">Inventory & profit breakdown by product</p>
    </div>

    <!-- Summary Banner -->
    <div class="grid grid-cols-4 gap-4">
        <div class="bg-slate-900 border border-slate-800 rounded-xl px-5 py-4 flex items-center gap-3">
            <div class="w-8 h-8 bg-sky-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Capital Invested</p>
                <p class="text-base font-bold text-white"><?= $fmt($totalCapital) ?></p>
            </div>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-xl px-5 py-4 flex items-center gap-3">
            <div class="w-8 h-8 bg-violet-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Stock Value</p>
                <p class="text-base font-bold text-white"><?= $fmt($totalInventoryValue) ?></p>
            </div>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-xl px-5 py-4 flex items-center gap-3">
            <div class="w-8 h-8 bg-amber-500/10 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Total Revenue</p>
                <p class="text-base font-bold text-white"><?= $fmt($totalRevenue) ?></p>
            </div>
        </div>
        <div class="bg-slate-900 border <?= $totalProfit >= 0 ? 'border-emerald-800/40' : 'border-rose-800/40' ?> rounded-xl px-5 py-4 flex items-center gap-3">
            <div class="w-8 h-8 <?= $totalProfit >= 0 ? 'bg-emerald-500/10' : 'bg-rose-500/10' ?> rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 <?= $totalProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500">Net Profit</p>
                <p class="text-base font-bold <?= $totalProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' ?>"><?= $fmt($totalProfit) ?></p>
            </div>
        </div>
    </div>

    <!-- Inventory Summary Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-800">
            <h2 class="text-sm font-semibold text-white">Inventory Summary by Product</h2>
            <p class="text-xs text-slate-500 mt-0.5">How much you've invested vs what's still in stock</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800">
                        <th class="text-left px-5 py-3 font-medium">Product</th>
                        <th class="text-center px-3 py-3 font-medium">Purchased</th>
                        <th class="text-center px-3 py-3 font-medium">Remaining</th>
                        <th class="text-center px-3 py-3 font-medium">Sold</th>
                        <th class="text-right px-3 py-3 font-medium">Capital Invested</th>
                        <th class="text-right px-5 py-3 font-medium">Stock Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    <?php if (empty($invSummary)): ?>
                    <tr><td colspan="6" class="text-center py-10 text-slate-600">No inventory data yet.</td></tr>
                    <?php else: foreach ($invSummary as $r):
                        $sold = $r['total_quantity_purchased'] - $r['total_stock_remaining'];
                        $soldPct = $r['total_quantity_purchased'] > 0 ? round(($sold / $r['total_quantity_purchased']) * 100) : 0;
                    ?>
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="text-slate-200 font-medium"><?= htmlspecialchars($r['product_name']) ?></p>
                            <p class="text-slate-600 mt-0.5">ID #<?= $r['product_id'] ?></p>
                        </td>
                        <td class="px-3 py-3.5 text-center text-slate-400"><?= number_format($r['total_quantity_purchased']) ?></td>
                        <td class="px-3 py-3.5 text-center">
                            <span class="font-semibold <?= $r['total_stock_remaining'] <= 5 ? 'text-amber-400' : 'text-slate-300' ?>">
                                <?= number_format($r['total_stock_remaining']) ?>
                            </span>
                        </td>
                        <td class="px-3 py-3.5 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-slate-400"><?= number_format($sold) ?></span>
                                <div class="w-16 bg-slate-800 rounded-full h-1">
                                    <div class="bg-emerald-500 h-1 rounded-full" style="width:<?= $soldPct ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 py-3.5 text-right text-slate-300"><?= $fmt($r['total_capital_invested']) ?></td>
                        <td class="px-5 py-3.5 text-right text-slate-300"><?= $fmt($r['inventory_value']) ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Profit Summary Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-800">
            <h2 class="text-sm font-semibold text-white">Profit Summary by Product</h2>
            <p class="text-xs text-slate-500 mt-0.5">Revenue, cost of goods sold, and profit per product</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800">
                        <th class="text-left px-5 py-3 font-medium">Product</th>
                        <th class="text-center px-3 py-3 font-medium">Units Sold</th>
                        <th class="text-right px-3 py-3 font-medium">COGS</th>
                        <th class="text-right px-3 py-3 font-medium">Revenue</th>
                        <th class="text-right px-3 py-3 font-medium">Profit</th>
                        <th class="text-right px-5 py-3 font-medium">Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    <?php if (empty($profSummary)): ?>
                    <tr><td colspan="6" class="text-center py-10 text-slate-600">No sales data yet.</td></tr>
                    <?php else: foreach ($profSummary as $r):
                        $margin = $r['total_revenue'] > 0 ? round(($r['total_profit'] / $r['total_revenue']) * 100, 1) : 0;
                    ?>
                    <tr class="hover:bg-slate-800/40 transition-colors">
                        <td class="px-5 py-3.5">
                            <p class="text-slate-200 font-medium"><?= htmlspecialchars($r['product_name']) ?></p>
                        </td>
                        <td class="px-3 py-3.5 text-center text-slate-400"><?= number_format($r['total_quantity_sold']) ?></td>
                        <td class="px-3 py-3.5 text-right text-rose-400/80"><?= $fmt($r['total_cost']) ?></td>
                        <td class="px-3 py-3.5 text-right text-slate-300"><?= $fmt($r['total_revenue']) ?></td>
                        <td class="px-3 py-3.5 text-right font-semibold <?= $r['total_profit'] >= 0 ? 'text-emerald-400' : 'text-rose-400' ?>">
                            <?= $fmt($r['total_profit']) ?>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <span class="<?= $margin >= 0 ? 'text-emerald-400 bg-emerald-500/10' : 'text-rose-400 bg-rose-500/10' ?> font-bold px-2 py-0.5 rounded-md">
                                <?= $margin ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
