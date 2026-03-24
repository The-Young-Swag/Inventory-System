<?php
// bk_dashboard.php — InvTrack Dashboard Backend
// Tables: products, inventory_batches, sales_transactions

$db = require __DIR__ . '/../config/database.php';

date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method.']); exit;
}

// ── Helpers ──────────────────────────────────────────────────────────────────

function postInt(string $key): int    { return (int)   ($_POST[$key] ?? 0);  }
function postFloat(string $key): float { return (float) ($_POST[$key] ?? 0); }
function postStr(string $key): string  { return trim($_POST[$key] ?? '');     }
function esc(mixed $v): string         { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }

// Both echo + exit — no split between "build array" and "send it"
function ok(array $data = []): void { echo json_encode(['success' => true] + $data); exit; }
function fail(string $msg): void    { echo json_encode(['success' => false, 'error' => $msg]); exit; }

// ── FIFO (shared by previewSale + sellProduct) ────────────────────────────────

function fetchBatches(PDO $db, int $productId, bool $lock = false): array
{
    $sql = "SELECT batch_id, quantity_remaining, unit_cost
            FROM   inventory_batches
            WHERE  product_id = ? AND quantity_remaining > 0
            ORDER  BY batch_id ASC" . ($lock ? ' FOR UPDATE' : '');

    $s = $db->prepare($sql);
    $s->execute([$productId]);
    return $s->fetchAll(PDO::FETCH_ASSOC);
}

function fifoCost(array $batches, int $qty): ?float
{
    if ($qty > array_sum(array_column($batches, 'quantity_remaining'))) return null;

    $cost = 0.0;
    foreach ($batches as $b) {
        if ($qty <= 0) break;
        $units = min($qty, (int) $b['quantity_remaining']);
        $cost += $units * (float) $b['unit_cost'];
        $qty  -= $units;
    }
    return $cost;
}

// ════════════════════════════════════════════════════════════════════════════
//  JSON HANDLERS
// ════════════════════════════════════════════════════════════════════════════

function getKPIs(PDO $db): void
{
    ok([
        'capital'     => (float) $db->query("SELECT COALESCE(SUM(total_capital), 0)                  FROM inventory_batches")->fetchColumn(),
        'stock_value' => (float) $db->query("SELECT COALESCE(SUM(quantity_remaining * unit_cost), 0) FROM inventory_batches")->fetchColumn(),
        'revenue'     => (float) $db->query("SELECT COALESCE(SUM(total_revenue), 0)                  FROM sales_transactions")->fetchColumn(),
        'profit'      => (float) $db->query("SELECT COALESCE(SUM(total_profit), 0)                   FROM sales_transactions")->fetchColumn(),
    ]);
}

// Add this function in the LEGACY HANDLERS section

function legacyProductCards(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');

    $rows = $db->query("
        SELECT
            p.product_id,
            p.product_name,
            p.product_code,
            p.product_description,
            COALESCE(SUM(ib.quantity_remaining), 0)                       AS stock_remaining,
            COALESCE(SUM(ib.quantity_purchased), 0)                       AS total_purchased,
            COALESCE(
                SUM(ib.quantity_remaining * ib.unit_cost) /
                NULLIF(SUM(ib.quantity_remaining), 0)
            , 0)                                                           AS avg_unit_cost,
            (
                SELECT st.unit_price
                FROM   sales_transactions st
                JOIN   inventory_batches  b ON b.batch_id = st.batch_id
                WHERE  b.product_id = p.product_id
                ORDER  BY st.sale_id DESC LIMIT 1
            ) AS last_sell_price
        FROM products p
        LEFT JOIN inventory_batches ib ON ib.product_id = p.product_id
        GROUP  BY p.product_id, p.product_name, p.product_code, p.product_description
        ORDER  BY p.product_name ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $r) {
        $id        = (int)   $r['product_id'];
        $name      = esc($r['product_name']);
        $code      = esc($r['product_code']);
        $desc      = esc($r['product_description']);
        $stock     = (int)   $r['stock_remaining'];
        $unitCost  = (float) $r['avg_unit_cost'];
        $sellPrice = $r['last_sell_price'] !== null ? (float) $r['last_sell_price'] : null;

        // ── Stock badge ──────────────────────────────────────────────
        if ($stock <= 0) {
            $badge = '<span class="inline-flex items-center gap-1.5 bg-rose-500/15 text-rose-400 border border-rose-500/25 text-[10px] font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>Out of stock</span>';
            $sellDisabled = 'opacity-40 pointer-events-none cursor-not-allowed';
        } elseif ($stock <= 5) {
            $badge = "<span class='inline-flex items-center gap-1.5 bg-amber-500/15 text-amber-400 border border-amber-500/25 text-[10px] font-semibold px-2.5 py-1 rounded-full whitespace-nowrap'>
                        <span class='w-1.5 h-1.5 rounded-full bg-amber-400'></span>{$stock} left &mdash; low</span>";
            $sellDisabled = '';
        } else {
            $badge = "<span class='inline-flex items-center gap-1.5 bg-emerald-500/15 text-emerald-400 border border-emerald-500/25 text-[10px] font-semibold px-2.5 py-1 rounded-full whitespace-nowrap'>
                        <span class='w-1.5 h-1.5 rounded-full bg-emerald-400'></span>{$stock} in stock</span>";
            $sellDisabled = '';
        }

        $costDisplay  = $unitCost > 0  ? '&#8369;' . number_format($unitCost,  2) : '—';
        $priceDisplay = $sellPrice !== null ? '&#8369;' . number_format($sellPrice, 2) : '—';

        echo "
        <div class='bg-slate-900 border border-slate-800/80 rounded-2xl p-4 flex flex-col gap-3 hover:border-slate-700/80 transition-colors'
             data-product-id='{$id}'
             data-product-name='{$name}'
             data-stock-remaining='{$stock}'>

            <!-- Header -->
            <div class='flex items-start justify-between gap-2'>
                <div class='min-w-0'>
                    <p class='text-sm font-bold text-white leading-tight truncate'>{$name}</p>
                    <p class='text-[11px] text-slate-500 mt-0.5 truncate'>{$code} &middot; {$desc}</p>
                </div>
                {$badge}
            </div>

            <!-- Stats -->
            <div class='grid grid-cols-2 gap-2'>
                <div class='bg-slate-800/60 rounded-xl px-3 py-2.5'>
                    <p class='text-[10px] text-slate-500 mb-0.5'>Unit cost</p>
                    <p class='text-sm font-bold text-slate-200'>{$costDisplay}</p>
                </div>
                <div class='bg-slate-800/60 rounded-xl px-3 py-2.5'>
                    <p class='text-[10px] text-slate-500 mb-0.5'>Sell price</p>
                    <p class='text-sm font-bold text-slate-200'>{$priceDisplay}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class='grid grid-cols-2 gap-2'>
                <button class='btn-sell {$sellDisabled} bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-semibold py-2 rounded-xl transition-colors active:scale-95'>
                    Sell
                </button>
                <button class='btn-restock bg-slate-800 hover:bg-blue-500/20 hover:text-blue-300 hover:border-blue-500/30 text-slate-200 text-sm font-semibold py-2 rounded-xl border border-transparent transition-colors active:scale-95'>
                    + Restock
                </button>
            </div>

        </div>";
    }

    if (empty($rows)) {
        echo "<div class='col-span-full flex flex-col items-center justify-center gap-2 py-16 text-slate-600'>
                <svg class='w-10 h-10 text-slate-800' fill='none' viewBox='0 0 24 24' stroke='currentColor'>
                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1' d='M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10'/>
                </svg>
                <p class='text-sm'>No products yet — add one to get started.</p>
              </div>";
    }

    exit;
}

function getProductCards(PDO $db): void
{
    $rows = $db->query("
        SELECT
            p.product_id,
            p.product_name,
            p.product_code,
            p.product_description,
            COALESCE(SUM(ib.quantity_purchased), 0) AS total_purchased,
            COALESCE(SUM(ib.quantity_remaining),  0) AS stock_remaining,
            (
                SELECT st.unit_price
                FROM   sales_transactions st
                JOIN   inventory_batches  b ON b.batch_id = st.batch_id
                WHERE  b.product_id = p.product_id
                ORDER  BY st.sale_id DESC LIMIT 1
            ) AS last_sell_price
        FROM products p
        LEFT JOIN inventory_batches ib ON ib.product_id = p.product_id
        GROUP  BY p.product_id, p.product_name, p.product_code, p.product_description
        ORDER  BY p.product_name ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as &$row) {
        $row['product_id']      = (int)   $row['product_id'];
        $row['total_purchased'] = (int)   $row['total_purchased'];
        $row['stock_remaining'] = (int)   $row['stock_remaining'];
        $row['last_sell_price'] = $row['last_sell_price'] !== null ? (float) $row['last_sell_price'] : null;
    }

    ok(['products' => array_values($rows)]);
}

function getTransactionLog(PDO $db): void
{
    $rows = $db->query("
        SELECT
            st.sale_id, p.product_name, st.quantity_sold,
            st.unit_cost, st.unit_price,
            st.total_cost, st.total_revenue, st.total_profit, st.sale_date
        FROM sales_transactions st
        JOIN inventory_batches ib ON ib.batch_id  = st.batch_id
        JOIN products          p  ON p.product_id = ib.product_id
        ORDER BY st.sale_id DESC LIMIT 50
    ")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as &$row) {
        $row['sale_id']       = (int)   $row['sale_id'];
        $row['quantity_sold'] = (int)   $row['quantity_sold'];
        $row['unit_cost']     = (float) $row['unit_cost'];
        $row['unit_price']    = (float) $row['unit_price'];
        $row['total_cost']    = (float) $row['total_cost'];
        $row['total_revenue'] = (float) $row['total_revenue'];
        $row['total_profit']  = (float) $row['total_profit'];
    }

    ok(['transactions' => array_values($rows)]);
}

function previewSale(PDO $db): void
{
    $productId = postInt('product_id');
    $qty       = postInt('quantity');
    $price     = postFloat('sell_price');

    if ($productId < 1) fail('Invalid product.');
    if ($qty       < 1) fail('Quantity must be at least 1.');
    if ($price    <= 0) fail('Sell price must be greater than zero.');

    $batches   = fetchBatches($db, $productId);
    $available = (int) array_sum(array_column($batches, 'quantity_remaining'));

    if ($qty > $available) fail("Only {$available} unit(s) available in stock.");

    $cost    = fifoCost($batches, $qty);
    $revenue = $qty * $price;

    ok([
        'available'     => $available,
        'total_cost'    => round($cost,          2),
        'total_revenue' => round($revenue,       2),
        'total_profit'  => round($revenue - $cost, 2),
    ]);
}

function addProduct(PDO $db): void
{
    $name     = postStr('name');
    $code     = postStr('code');
    $category = postStr('desc');
    $qty      = postInt('qty');
    $cost     = postFloat('cost');

    if ($name === '') fail('Product name is required.');
    if ($code === '') fail('Product code is required.');
    if ($qty   < 1)  fail('Quantity must be at least 1.');
    if ($cost <= 0)  fail('Unit cost must be greater than zero.');

    $dup = $db->prepare("SELECT COUNT(*) FROM products WHERE product_code = ?");
    $dup->execute([$code]);
    if ((int) $dup->fetchColumn()) fail("Product code \"{$code}\" is already in use.");

    $db->beginTransaction();
    try {
        $db->prepare("INSERT INTO products (product_name, product_code, product_description) VALUES (?, ?, ?)")
           ->execute([$name, $code, $category]);

        $newId = (int) $db->lastInsertId();

        $db->prepare("INSERT INTO inventory_batches (product_id, quantity_purchased, quantity_remaining, unit_cost, total_capital, purchase_date) VALUES (?, ?, ?, ?, ?, NOW())")
           ->execute([$newId, $qty, $qty, $cost, $qty * $cost]);

        $db->commit();
        ok(['product_id' => $newId]);

    } catch (Exception $e) {
        $db->rollBack();
        fail('Failed to save product: ' . $e->getMessage());
    }
}

function sellProduct(PDO $db): void
{
    $productId = postInt('product_id');
    $qty       = postInt('quantity');
    $price     = postFloat('sell_price');

    if ($productId < 1) fail('Invalid product.');
    if ($qty       < 1) fail('Quantity must be at least 1.');
    if ($price    <= 0) fail('Sell price must be greater than zero.');

    $db->beginTransaction();
    try {
        $batches   = fetchBatches($db, $productId, lock: true);
        $available = (int) array_sum(array_column($batches, 'quantity_remaining'));

        if ($qty > $available) { $db->rollBack(); fail("Only {$available} unit(s) in stock."); }

        $remaining = $qty;
        foreach ($batches as $b) {
            if ($remaining <= 0) break;

            $units   = min($remaining, (int) $b['quantity_remaining']);
            $cost    = (float) $b['unit_cost'];
            $lineCost    = $units * $cost;
            $lineRevenue = $units * $price;

            $db->prepare("INSERT INTO sales_transactions (batch_id, quantity_sold, unit_cost, unit_price, total_cost, total_revenue, total_profit, sale_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())")
               ->execute([$b['batch_id'], $units, $cost, $price, $lineCost, $lineRevenue, $lineRevenue - $lineCost]);

            $db->prepare("UPDATE inventory_batches SET quantity_remaining = quantity_remaining - ? WHERE batch_id = ?")
               ->execute([$units, $b['batch_id']]);

            $remaining -= $units;
        }

        $db->commit();
        ok();

    } catch (Exception $e) {
        $db->rollBack();
        fail('Sale failed: ' . $e->getMessage());
    }
}

function restockProduct(PDO $db): void
{
    $productId = postInt('product_id');
    $qty       = postInt('quantity');
    $cost      = postFloat('unit_cost');

    if ($productId < 1) fail('Invalid product.');
    if ($qty       < 1) fail('Quantity must be at least 1.');
    if ($cost     <= 0) fail('Unit cost must be greater than zero.');

    $check = $db->prepare("SELECT COUNT(*) FROM products WHERE product_id = ?");
    $check->execute([$productId]);
    if (!(int) $check->fetchColumn()) fail('Product not found.');

    $db->prepare("INSERT INTO inventory_batches (product_id, quantity_purchased, quantity_remaining, unit_cost, total_capital, purchase_date) VALUES (?, ?, ?, ?, ?, NOW())")
       ->execute([$productId, $qty, $qty, $cost, $qty * $cost]);

    ok();
}

// ════════════════════════════════════════════════════════════════════════════
//  LEGACY HANDLERS  (HTML row responses — kept for older pages)
// ════════════════════════════════════════════════════════════════════════════

function legacyInventoryBatch(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');
    $rows = $db->query("SELECT batch_id, product_id, quantity_purchased, quantity_remaining, unit_cost, total_capital, purchase_date FROM inventory_batches ORDER BY batch_id")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        echo "<tr data-id='" . esc($r['batch_id']) . "'>
            <td>" . esc($r['batch_id'])          . "</td>
            <td>" . esc($r['product_id'])         . "</td>
            <td>" . esc($r['quantity_purchased']) . "</td>
            <td>" . esc($r['quantity_remaining']) . "</td>
            <td>" . esc($r['unit_cost'])          . "</td>
            <td>" . esc($r['total_capital'])      . "</td>
            <td>" . esc($r['purchase_date'])      . "</td>
        </tr>";
    }
    exit;
}

function legacyProducts(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');
    $rows = $db->query("SELECT product_id, product_name, product_code, product_description, created_at, updated_at FROM products ORDER BY product_id")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        echo "<tr data-id='" . esc($r['product_id']) . "'>
            <td>" . esc($r['product_id'])          . "</td>
            <td>" . esc($r['product_name'])        . "</td>
            <td>" . esc($r['product_code'])        . "</td>
            <td>" . esc($r['product_description']) . "</td>
            <td>" . esc($r['created_at'])          . "</td>
            <td>" . esc($r['updated_at'])          . "</td>
        </tr>";
    }
    exit;
}

function legacySalesTransactions(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');
    $rows = $db->query("SELECT sale_id, batch_id, quantity_sold, unit_cost, unit_price, total_cost, total_revenue, total_profit, sale_date FROM sales_transactions ORDER BY sale_id")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        echo "<tr data-id='" . esc($r['sale_id']) . "'>
            <td>" . esc($r['sale_id'])       . "</td>
            <td>" . esc($r['batch_id'])      . "</td>
            <td>" . esc($r['quantity_sold']) . "</td>
            <td>" . esc($r['unit_cost'])     . "</td>
            <td>" . esc($r['unit_price'])    . "</td>
            <td>" . esc($r['total_cost'])    . "</td>
            <td>" . esc($r['total_revenue']) . "</td>
            <td>" . esc($r['total_profit'])  . "</td>
            <td>" . esc($r['sale_date'])     . "</td>
        </tr>";
    }
    exit;
}

function legacyInventorySummary(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');
    try {
        $rows = $db->query("SELECT product_id, product_name, total_quantity_purchased, total_stock_remaining, total_capital_invested, inventory_value FROM product_inventory_summary ORDER BY product_id")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $rows = $db->query("
            SELECT p.product_id, p.product_name,
                   COALESCE(SUM(ib.quantity_purchased), 0)                AS total_quantity_purchased,
                   COALESCE(SUM(ib.quantity_remaining), 0)                AS total_stock_remaining,
                   COALESCE(SUM(ib.total_capital), 0)                     AS total_capital_invested,
                   COALESCE(SUM(ib.quantity_remaining * ib.unit_cost), 0) AS inventory_value
            FROM products p LEFT JOIN inventory_batches ib ON ib.product_id = p.product_id
            GROUP BY p.product_id, p.product_name ORDER BY p.product_id
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
    foreach ($rows as $r) {
        echo "<tr data-id='" . esc($r['product_id']) . "'>
            <td>" . esc($r['product_id'])              . "</td>
            <td>" . esc($r['product_name'])            . "</td>
            <td>" . esc($r['total_quantity_purchased']) . "</td>
            <td>" . esc($r['total_stock_remaining'])    . "</td>
            <td>" . esc($r['total_capital_invested'])   . "</td>
            <td>" . esc($r['inventory_value'])          . "</td>
        </tr>";
    }
    exit;
}

function legacyProfitSummary(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');
    try {
        $rows = $db->query("SELECT product_id, product_name, total_quantity_sold, total_cost, total_revenue, total_profit FROM product_profit_summary ORDER BY product_id")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $rows = $db->query("
            SELECT p.product_id, p.product_name,
                   COALESCE(SUM(st.quantity_sold), 0) AS total_quantity_sold,
                   COALESCE(SUM(st.total_cost), 0)    AS total_cost,
                   COALESCE(SUM(st.total_revenue), 0) AS total_revenue,
                   COALESCE(SUM(st.total_profit), 0)  AS total_profit
            FROM products p
            LEFT JOIN inventory_batches  ib ON ib.product_id = p.product_id
            LEFT JOIN sales_transactions st ON st.batch_id   = ib.batch_id
            GROUP BY p.product_id, p.product_name ORDER BY p.product_id
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
    foreach ($rows as $r) {
        echo "<tr data-id='" . esc($r['product_id']) . "'>
            <td>" . esc($r['product_id'])         . "</td>
            <td>" . esc($r['product_name'])       . "</td>
            <td>" . esc($r['total_quantity_sold']) . "</td>
            <td>" . esc($r['total_cost'])          . "</td>
            <td>" . esc($r['total_revenue'])       . "</td>
            <td>" . esc($r['total_profit'])        . "</td>
        </tr>";
    }
    exit;
}

// ════════════════════════════════════════════════════════════════════════════
//  DISPATCH
// ════════════════════════════════════════════════════════════════════════════

// Note: frontend uses 'request' key — keep consistent with $.post({ request: "..." })
$action = trim($_POST['action'] ?? $_POST['request'] ?? '');

switch ($action) {
    // JSON
    case 'getKPIs':              getKPIs($db);              break;
    case 'getProductCards': legacyProductCards($db); break;
    case 'getProductCards':      getProductCards($db);      break;
    case 'getTransactionLog':    getTransactionLog($db);    break;
    case 'previewSale':          previewSale($db);          break;
    case 'addProduct':           addProduct($db);           break;
    case 'sellProduct':          sellProduct($db);          break;
    case 'restockProduct':       restockProduct($db);       break;
    // Legacy HTML
    case 'getInventoryBatch':    legacyInventoryBatch($db);    break;
    case 'getProducts':          legacyProducts($db);           break;
    case 'getSalesTransaction':  legacySalesTransactions($db);  break;
    case 'getInventorySummary':  legacyInventorySummary($db);   break;
    case 'getProfitSummary':     legacyProfitSummary($db);      break;

    default: echo json_encode(['error' => "Unknown action: '{$action}'."]);
}