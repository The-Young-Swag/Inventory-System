<?php // frontend/dashboard.php
$conn = require __DIR__ . "/../config/database.php";

$kpi = $conn->query("
    SELECT
        (SELECT COALESCE(SUM(total_capital),0) FROM inventory_batches)        AS total_capital,
        (SELECT COALESCE(SUM(stock_value),0)   FROM inventory_batches)        AS inventory_value,
        (SELECT COALESCE(SUM(total_revenue),0) FROM sales_transactions)       AS total_revenue,
        (SELECT COALESCE(SUM(total_profit),0)  FROM sales_transactions)       AS total_profit,
        (SELECT COUNT(*)                        FROM products)                 AS product_count,
        (SELECT COUNT(*) FROM inventory_batches WHERE quantity_remaining = 0) AS out_of_stock,
        (SELECT COUNT(*) FROM inventory_batches WHERE quantity_remaining > 0 AND quantity_remaining <= 5) AS low_stock,
        (SELECT COUNT(*)                        FROM sales_transactions)       AS total_sales_count
")->fetch();

$fmt       = fn($n) => '₱' . number_format((float)$n, 2);
$profitPct = $kpi['total_revenue'] > 0 ? round(($kpi['total_profit'] / $kpi['total_revenue']) * 100, 1) : 0;

$recentSales = $conn->query("
    SELECT s.sale_id, p.product_name, s.quantity_sold, s.unit_price, s.total_revenue, s.total_profit, s.sale_date
    FROM sales_transactions s
    JOIN inventory_batches b ON s.batch_id = b.batch_id
    JOIN products p ON b.product_id = p.product_id
    ORDER BY s.sale_date DESC LIMIT 8
")->fetchAll();

$lowStock = $conn->query("
    SELECT b.batch_id, p.product_name, b.quantity_remaining, b.unit_cost
    FROM inventory_batches b
    JOIN products p ON b.product_id = p.product_id
    WHERE b.quantity_remaining <= 5 AND b.quantity_remaining > 0
    ORDER BY b.quantity_remaining ASC LIMIT 5
")->fetchAll();

// Top products by profit
$topProducts = $conn->query("
    SELECT product_name, total_profit, total_revenue, total_quantity_sold
    FROM product_profit_summary
    WHERE total_quantity_sold > 0
    ORDER BY total_profit DESC LIMIT 5
")->fetchAll();
?>

<div class="p-6 space-y-5">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white">Dashboard</h1>
            <p class="text-sm text-slate-500 mt-0.5">Overview of your business performance</p>
        </div>
        <!-- Quick action buttons -->
        <div class="flex items-center gap-2.5">
            <button onclick="$('.nav-link[data-page=\'inventory.php\']').trigger('click')"
                class="flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-sm font-medium rounded-lg border border-slate-700 transition-all">
                <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Sell / Restock
            </button>
            <button onclick="showModal('addProduct')"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-emerald-900/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </button>
        </div>
    </div>

    <!-- Alert strip -->
    <?php if (count($lowStock) > 0): ?>
    <div class="bg-amber-500/8 border border-amber-500/25 rounded-xl px-4 py-3 flex items-center gap-3">
        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse shrink-0"></span>
        <span class="text-sm text-amber-300 font-medium">
            Low stock: <strong><?= count($lowStock) ?> batch<?= count($lowStock) > 1 ? 'es' : '' ?></strong> running low —
            <?= implode(', ', array_map(fn($x) => htmlspecialchars($x['product_name']).' ('.$x['quantity_remaining'].')', $lowStock)) ?>
        </span>
        <button onclick="$('.nav-link[data-page=\'inventory.php\']').trigger('click')" class="ml-auto text-xs text-amber-400 hover:text-amber-300 font-semibold shrink-0">
            Go to Inventory →
        </button>
    </div>
    <?php endif; ?>

    <!-- KPI Cards -->
    <div class="grid grid-cols-4 gap-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <div class="flex items-start justify-between mb-4">
                <div class="w-9 h-9 bg-sky-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] text-slate-600 font-medium uppercase tracking-widest">Capital</span>
            </div>
            <p class="text-2xl font-bold text-white"><?= $fmt($kpi['total_capital']) ?></p>
            <p class="text-xs text-slate-500 mt-1">Total amount invested</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <div class="flex items-start justify-between mb-4">
                <div class="w-9 h-9 bg-violet-500/10 rounded-xl flex items-center justify-center">
                    <svg style="width:18px;height:18px" class="text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <span class="text-[10px] text-slate-600 font-medium uppercase tracking-widest">Stock</span>
            </div>
            <p class="text-2xl font-bold text-white"><?= $fmt($kpi['inventory_value']) ?></p>
            <p class="text-xs text-slate-500 mt-1">Current stock value</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
            <div class="flex items-start justify-between mb-4">
                <div class="w-9 h-9 bg-amber-500/10 rounded-xl flex items-center justify-center">
                    <svg style="width:18px;height:18px" class="text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] text-slate-600 font-medium uppercase tracking-widest">Revenue</span>
            </div>
            <p class="text-2xl font-bold text-white"><?= $fmt($kpi['total_revenue']) ?></p>
            <p class="text-xs text-slate-500 mt-1"><?= $kpi['total_sales_count'] ?> sales recorded</p>
        </div>

        <div class="bg-slate-900 border <?= $kpi['total_profit'] >= 0 ? 'border-emerald-800/40' : 'border-rose-800/40' ?> rounded-2xl p-5 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br <?= $kpi['total_profit'] >= 0 ? 'from-emerald-500/5' : 'from-rose-500/5' ?> to-transparent pointer-events-none"></div>
            <div class="flex items-start justify-between mb-4">
                <div class="w-9 h-9 <?= $kpi['total_profit'] >= 0 ? 'bg-emerald-500/10' : 'bg-rose-500/10' ?> rounded-xl flex items-center justify-center">
                    <svg style="width:18px;height:18px" class="<?= $kpi['total_profit'] >= 0 ? 'text-emerald-400' : 'text-rose-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <span class="text-[10px] <?= $kpi['total_profit'] >= 0 ? 'text-emerald-500' : 'text-rose-500' ?> font-bold"><?= $profitPct ?>% margin</span>
            </div>
            <p class="text-2xl font-bold <?= $kpi['total_profit'] >= 0 ? 'text-emerald-400' : 'text-rose-400' ?>"><?= $fmt($kpi['total_profit']) ?></p>
            <p class="text-xs text-slate-500 mt-1">Net profit from all sales</p>
        </div>
    </div>

    <!-- Bottom grid -->
    <div class="grid grid-cols-5 gap-5">

        <!-- Recent Sales -->
        <div class="col-span-3 bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
                <h2 class="text-sm font-semibold text-white">Recent Sales</h2>
                <button onclick="$('.nav-link[data-page=\'inventory.php\']').trigger('click')"
                    class="text-xs text-emerald-400 hover:text-emerald-300 font-medium bg-emerald-500/10 hover:bg-emerald-500/20 px-3 py-1.5 rounded-lg transition-all">
                    + Record Sale
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-slate-500 border-b border-slate-800">
                            <th class="text-left px-5 py-3 font-medium">Product</th>
                            <th class="text-center px-3 py-3 font-medium">Qty</th>
                            <th class="text-right px-3 py-3 font-medium">Revenue</th>
                            <th class="text-right px-5 py-3 font-medium">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        <?php if (empty($recentSales)): ?>
                        <tr><td colspan="4" class="text-center py-10 text-slate-600">No sales yet.</td></tr>
                        <?php else: foreach ($recentSales as $s): ?>
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-5 py-3.5">
                                <p class="text-slate-200 font-medium"><?= htmlspecialchars($s['product_name']) ?></p>
                                <p class="text-slate-600 mt-0.5"><?= date('M d, Y', strtotime($s['sale_date'])) ?></p>
                            </td>
                            <td class="px-3 py-3.5 text-center text-slate-400"><?= $s['quantity_sold'] ?></td>
                            <td class="px-3 py-3.5 text-right text-slate-300"><?= $fmt($s['total_revenue']) ?></td>
                            <td class="px-5 py-3.5 text-right font-semibold <?= (float)$s['total_profit'] >= 0 ? 'text-emerald-400' : 'text-rose-400' ?>">
                                <?= $fmt($s['total_profit']) ?>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right col -->
        <div class="col-span-2 space-y-5">

            <!-- Quick stats -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 space-y-4">
                <h2 class="text-sm font-semibold text-white">Quick Stats</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Products</span>
                        <span class="text-white font-semibold"><?= $kpi['product_count'] ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Out of Stock</span>
                        <span class="<?= $kpi['out_of_stock'] > 0 ? 'text-rose-400' : 'text-slate-400' ?> font-semibold"><?= $kpi['out_of_stock'] ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Low Stock</span>
                        <span class="<?= $kpi['low_stock'] > 0 ? 'text-amber-400' : 'text-slate-400' ?> font-semibold"><?= $kpi['low_stock'] ?></span>
                    </div>
                    <div class="h-px bg-slate-800"></div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Profit Margin</span>
                        <span class="<?= $profitPct > 0 ? 'text-emerald-400' : 'text-rose-400' ?> font-bold"><?= $profitPct ?>%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-1.5">
                        <div class="<?= $profitPct > 0 ? 'bg-emerald-500' : 'bg-rose-500' ?> h-1.5 rounded-full transition-all"
                            style="width:<?= min(abs($profitPct), 100) ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Top products -->
            <?php if (!empty($topProducts)): ?>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-800">
                    <h2 class="text-sm font-semibold text-white">Top Products by Profit</h2>
                </div>
                <div class="divide-y divide-slate-800/60">
                    <?php foreach ($topProducts as $tp): ?>
                    <div class="flex items-center justify-between px-5 py-3 text-xs">
                        <div>
                            <p class="text-slate-300 font-medium"><?= htmlspecialchars($tp['product_name']) ?></p>
                            <p class="text-slate-600 mt-0.5"><?= number_format($tp['total_quantity_sold']) ?> sold</p>
                        </div>
                        <span class="font-bold <?= (float)$tp['total_profit'] >= 0 ? 'text-emerald-400' : 'text-rose-400' ?>">
                            <?= $fmt($tp['total_profit']) ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

</div>

<script>
// Re-bind edit buttons that come from dashboard
$(document).on('click', '.btn-edit-product', function () {
    showModal('editProduct', { product_id: $(this).data('id') });
});
</script>
