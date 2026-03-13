<?php
// backend/bk_dashboard.php
$conn = require __DIR__ . "/../config/database.php";

$request = $_POST['request'] ?? '';

$fmt = fn($n) => '₱' . number_format((float)$n, 2);
$badge = fn($n, $warn=5) => $n == 0
    ? "<span class='font-bold text-rose-400 bg-rose-500/10 px-2 py-0.5 rounded-md'>0 — Out</span>"
    : ($n <= $warn
        ? "<span class='font-bold text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded-md'>{$n} ⚠</span>"
        : "<span class='text-slate-300 font-medium'>{$n}</span>");


// ════════════════════════════════════════════════════════════════════════════
// JSON endpoint used by the card-based inventory UI
// Returns products (with latest batch info) + full transaction log
// ════════════════════════════════════════════════════════════════════════════
if ($request === 'getProductsJson') {
    header('Content-Type: application/json');

    // One row per product — latest active batch (most recent with stock, else most recent overall)
    $products = $conn->query("
        SELECT
            p.product_id,
            p.product_name,
            p.product_sku,
            p.product_description,
            p.selling_price,
            COALESCE(
                (SELECT b2.batch_id FROM inventory_batches b2
                 WHERE b2.product_id = p.product_id AND b2.quantity_remaining > 0
                 ORDER BY b2.purchase_date DESC LIMIT 1),
                (SELECT b3.batch_id FROM inventory_batches b3
                 WHERE b3.product_id = p.product_id
                 ORDER BY b3.purchase_date DESC LIMIT 1)
            ) AS batch_id,
            COALESCE(
                (SELECT b2.unit_cost FROM inventory_batches b2
                 WHERE b2.product_id = p.product_id AND b2.quantity_remaining > 0
                 ORDER BY b2.purchase_date DESC LIMIT 1),
                (SELECT b3.unit_cost FROM inventory_batches b3
                 WHERE b3.product_id = p.product_id
                 ORDER BY b3.purchase_date DESC LIMIT 1),
                0
            ) AS unit_cost,
            COALESCE(
                (SELECT SUM(b4.quantity_remaining) FROM inventory_batches b4
                 WHERE b4.product_id = p.product_id),
                0
            ) AS qty_remaining
        FROM products p
        ORDER BY p.product_name
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Cast types for JS
    foreach ($products as &$p) {
        $p['product_id']    = (int)$p['product_id'];
        $p['batch_id']      = $p['batch_id'] ? (int)$p['batch_id'] : null;
        $p['selling_price'] = (float)$p['selling_price'];
        $p['unit_cost']     = (float)$p['unit_cost'];
        $p['qty_remaining'] = (int)$p['qty_remaining'];
    }
    unset($p);

    // Transaction log — restocks + sales combined, newest first
    $restocks = $conn->query("
        SELECT 'stock' AS type, b.batch_id, p.product_name,
               b.quantity_purchased AS qty, b.unit_cost,
               0 AS revenue, 0 AS cogs, 0 AS unit_price,
               DATE_FORMAT(b.purchase_date, '%b %d, %Y') AS date
        FROM inventory_batches b
        JOIN products p ON b.product_id = p.product_id
    ")->fetchAll(PDO::FETCH_ASSOC);

    $sales = $conn->query("
        SELECT 'sale' AS type, s.sale_id AS batch_id, p.product_name,
               s.quantity_sold AS qty, s.unit_cost,
               s.total_revenue AS revenue, s.total_cost AS cogs,
               s.unit_price,
               DATE_FORMAT(s.sale_date, '%b %d, %Y') AS date
        FROM sales_transactions s
        JOIN inventory_batches b ON s.batch_id = b.batch_id
        JOIN products p ON b.product_id = p.product_id
    ")->fetchAll(PDO::FETCH_ASSOC);

    $transactions = array_merge($restocks, $sales);

    // Cast numeric fields
    foreach ($transactions as &$t) {
        $t['qty']       = (int)$t['qty'];
        $t['unit_cost'] = (float)$t['unit_cost'];
        $t['revenue']   = (float)$t['revenue'];
        $t['cogs']      = (float)$t['cogs'];
        $t['unit_price']= (float)$t['unit_price'];
    }
    unset($t);

    echo json_encode(['products' => $products, 'transactions' => $transactions]);
    exit;
}


// ════════════════════════════════════════════════════════════════════════════
// TABLE ROW renderers (still used by dashboard.php)
// ════════════════════════════════════════════════════════════════════════════

if ($request === 'getProducts') {
    $rows = $conn->query("SELECT * FROM products ORDER BY product_id")->fetchAll();
    if (empty($rows)) {
        echo "<tr><td colspan='6' class='text-center py-12 text-slate-600'>No products yet.</td></tr>";
        exit;
    }
    foreach ($rows as $r) {
        $desc = htmlspecialchars(substr($r['product_description'] ?? '', 0, 60));
        if (strlen($r['product_description'] ?? '') > 60) $desc .= '…';
        echo "<tr class='hover:bg-slate-800/40 transition-colors'>
            <td class='px-5 py-3.5 text-slate-600 font-mono text-xs'>#{$r['product_id']}</td>
            <td class='px-5 py-3.5'><p class='text-slate-200 font-semibold'>".htmlspecialchars($r['product_name'])."</p></td>
            <td class='px-3 py-3.5 text-slate-400 font-mono text-xs'>".htmlspecialchars($r['product_sku'])."</td>
            <td class='px-3 py-3.5 text-slate-500'>{$desc}</td>
            <td class='px-3 py-3.5 text-right text-slate-200 font-semibold'>{$fmt($r['selling_price'])}</td>
            <td class='px-5 py-3.5 text-right'>
                <button class='btn-edit-product text-xs text-slate-400 hover:text-white hover:bg-slate-700 px-2.5 py-1.5 rounded-lg transition-all'
                    data-id='{$r['product_id']}'>Edit</button>
            </td>
        </tr>";
    }
    exit;
}

if ($request === 'getInventoryBatch') {
    $rows = $conn->query("
        SELECT b.*, p.product_name
        FROM inventory_batches b
        JOIN products p ON b.product_id = p.product_id
        ORDER BY b.batch_id DESC
    ")->fetchAll();
    if (empty($rows)) {
        echo "<tr><td colspan='8' class='text-center py-12 text-slate-600'>No batches yet.</td></tr>";
        exit;
    }
    foreach ($rows as $r) {
        echo "<tr class='hover:bg-slate-800/40 transition-colors'>
            <td class='px-5 py-3.5 text-slate-500 font-mono text-xs'>#".(int)$r['batch_id']."</td>
            <td class='px-5 py-3.5 text-slate-200 font-medium'>".htmlspecialchars($r['product_name'])."</td>
            <td class='px-3 py-3.5 text-center text-slate-400'>".(int)$r['quantity_purchased']."</td>
            <td class='px-3 py-3.5 text-center'>".$badge($r['quantity_remaining'])."</td>
            <td class='px-3 py-3.5 text-right text-slate-400'>{$fmt($r['unit_cost'])}</td>
            <td class='px-3 py-3.5 text-right text-slate-300'>{$fmt($r['total_capital'])}</td>
            <td class='px-3 py-3.5 text-right text-violet-400'>{$fmt($r['stock_value'])}</td>
            <td class='px-5 py-3.5 text-slate-500 text-xs'>".date('M d, Y', strtotime($r['purchase_date']))."</td>
        </tr>";
    }
    exit;
}

if ($request === 'getSalesTransaction') {
    $rows = $conn->query("
        SELECT s.*, p.product_name
        FROM sales_transactions s
        JOIN inventory_batches b ON s.batch_id = b.batch_id
        JOIN products p ON b.product_id = p.product_id
        ORDER BY s.sale_id DESC
    ")->fetchAll();
    if (empty($rows)) {
        echo "<tr><td colspan='9' class='text-center py-12 text-slate-600'>No sales yet.</td></tr>";
        exit;
    }
    foreach ($rows as $r) {
        $pc = (float)$r['total_profit'] >= 0 ? 'text-emerald-400' : 'text-rose-400';
        echo "<tr class='hover:bg-slate-800/40 transition-colors'>
            <td class='px-5 py-3.5 text-slate-500 font-mono text-xs'>#".(int)$r['sale_id']."</td>
            <td class='px-3 py-3.5'>
                <p class='text-slate-200 font-medium'>".htmlspecialchars($r['product_name'])."</p>
                <p class='text-slate-600 text-xs mt-0.5'>Batch #".(int)$r['batch_id']."</p>
            </td>
            <td class='px-3 py-3.5 text-center text-slate-400'>".(int)$r['quantity_sold']."</td>
            <td class='px-3 py-3.5 text-right text-slate-500 text-xs'>{$fmt($r['unit_cost'])}</td>
            <td class='px-3 py-3.5 text-right text-slate-300'>{$fmt($r['unit_price'])}</td>
            <td class='px-3 py-3.5 text-right text-slate-300'>{$fmt($r['total_revenue'])}</td>
            <td class='px-3 py-3.5 text-right font-semibold {$pc}'>{$fmt($r['total_profit'])}</td>
            <td class='px-5 py-3.5 text-slate-500 text-xs'>".date('M d, Y', strtotime($r['sale_date']))."</td>
            <td class='px-5 py-3.5 text-right'>
                <button class='btn-delete-sale p-1.5 text-slate-600 hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-all' data-id='".(int)$r['sale_id']."'>
                    <svg class='w-4 h-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg>
                </button>
            </td>
        </tr>";
    }
    exit;
}

if ($request === 'getInventorySummary') {
    $rows = $conn->query("SELECT * FROM product_inventory_summary ORDER BY product_id")->fetchAll();
    foreach ($rows as $r) {
        echo "<tr class='hover:bg-slate-800/40 transition-colors'>
            <td class='px-5 py-3 text-slate-600 text-xs font-mono'>#{$r['product_id']}</td>
            <td class='px-5 py-3 text-slate-200 font-medium'>".htmlspecialchars($r['product_name'])."</td>
            <td class='px-3 py-3 text-center text-slate-400'>{$r['total_quantity_purchased']}</td>
            <td class='px-3 py-3 text-center'>".$badge($r['total_stock_remaining'])."</td>
            <td class='px-3 py-3 text-right text-slate-300'>{$fmt($r['total_capital_invested'])}</td>
            <td class='px-5 py-3 text-right text-violet-400'>{$fmt($r['inventory_value'])}</td>
        </tr>";
    }
    exit;
}

if ($request === 'getProfitSummary') {
    $rows = $conn->query("SELECT * FROM product_profit_summary ORDER BY product_id")->fetchAll();
    foreach ($rows as $r) {
        $pc = (float)$r['total_profit'] >= 0 ? 'text-emerald-400' : 'text-rose-400';
        echo "<tr class='hover:bg-slate-800/40 transition-colors'>
            <td class='px-5 py-3 text-slate-600 text-xs font-mono'>#{$r['product_id']}</td>
            <td class='px-5 py-3 text-slate-200 font-medium'>".htmlspecialchars($r['product_name'])."</td>
            <td class='px-3 py-3 text-center text-slate-400'>{$r['total_quantity_sold']}</td>
            <td class='px-3 py-3 text-right text-rose-400/80'>{$fmt($r['total_cost'])}</td>
            <td class='px-3 py-3 text-right text-slate-300'>{$fmt($r['total_revenue'])}</td>
            <td class='px-5 py-3 text-right font-semibold {$pc}'>{$fmt($r['total_profit'])}</td>
        </tr>";
    }
    exit;
}
