<?php
// InvTrack Dashboard Backend
// Handles: KPI reporting, product management, sales, restocking
// Tables: products, inventory_batches, sales_transactions
// Security note: unit_cost is never returned to the client —
//   all financial math (FIFO blending, profit) stays server-side.

$db = require __DIR__ . '/../config/database.php'; // PDO instance

date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(['error' => 'Invalid request method.']);
}

// ============================================================
// UTILITIES
// ============================================================

function sendResponse(array $payload): void
{
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function ok(array $data = []): array
{
    return array_merge(['success' => true], $data);
}

function fail(string $message): array
{
    return ['success' => false, 'error' => $message];
}

function esc(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// ============================================================
// INPUT HELPERS
// ============================================================

function postInt(string $key): int
{
    return (int) ($_POST[$key] ?? 0);
}

function postFloat(string $key): float
{
    return (float) ($_POST[$key] ?? 0);
}

function postString(string $key): string
{
    return trim($_POST[$key] ?? '');
}

// ============================================================
// FIFO HELPERS
// Shared logic used by both PreviewSale and RecordSale
// to walk inventory batches in purchase order (oldest first).
// ============================================================

// Returns all available batches for a product, oldest first.
// Locks rows when $lock = true (use inside an open transaction).
function fetchAvailableBatches(PDO $db, int $productId, bool $lock = false): array
{
    $forUpdate = $lock ? 'FOR UPDATE' : '';

    $stmt = $db->prepare("
        SELECT batch_id, quantity_remaining, unit_cost
        FROM   inventory_batches
        WHERE  product_id        = ?
          AND  quantity_remaining > 0
        ORDER  BY batch_id ASC
        {$forUpdate}
    ");
    $stmt->execute([$productId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Simulates a FIFO deduction without writing to the database.
// Returns the blended cost for the requested quantity,
// or null if stock is insufficient.
function simulateFifoCost(array $batches, int $quantity): ?float
{
    $totalAvailable = (int) array_sum(array_column($batches, 'quantity_remaining'));

    if ($quantity > $totalAvailable) {
        return null;
    }

    $remaining   = $quantity;
    $blendedCost = 0.0;

    foreach ($batches as $batch) {
        if ($remaining <= 0) break;

        $units        = min($remaining, (int) $batch['quantity_remaining']);
        $blendedCost += $units * (float) $batch['unit_cost'];
        $remaining   -= $units;
    }

    return $blendedCost;
}

// ============================================================
// HANDLERS
// ============================================================

// Returns the four KPI totals for the dashboard header cards.
// Capital = all batches ever purchased (not just remaining stock).
// Stock value = remaining units × their original batch cost.
// Revenue and profit are summed from completed sales only.
function GetKPIs(PDO $db): void
{
    $totalCapital  = (float) $db->query("SELECT COALESCE(SUM(total_capital), 0)                      FROM inventory_batches")->fetchColumn();
    $totalStockVal = (float) $db->query("SELECT COALESCE(SUM(quantity_remaining * unit_cost), 0)     FROM inventory_batches")->fetchColumn();
    $totalRevenue  = (float) $db->query("SELECT COALESCE(SUM(total_revenue), 0)                      FROM sales_transactions")->fetchColumn();
    $totalProfit   = (float) $db->query("SELECT COALESCE(SUM(total_profit), 0)                       FROM sales_transactions")->fetchColumn();

    sendResponse(ok([
        'capital'     => $totalCapital,
        'stock_value' => $totalStockVal,
        'revenue'     => $totalRevenue,
        'profit'      => $totalProfit,
    ]));
}

// Returns one row per product with display-safe stock and pricing data.
// avg_unit_cost and raw batch costs are intentionally excluded —
// financial internals must not be exposed to the browser.
function GetProductCards(PDO $db): void
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
                ORDER  BY st.sale_id DESC
                LIMIT  1
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
        $row['last_sell_price'] = $row['last_sell_price'] !== null
            ? (float) $row['last_sell_price']
            : null;
    }

    sendResponse(ok(['products' => array_values($rows)]));
}

// Returns the most recent 50 sales joined with their product name.
// Used to populate the transaction log table on the dashboard.
function GetTransactionLog(PDO $db): void
{
    $rows = $db->query("
        SELECT
            st.sale_id,
            p.product_name,
            st.quantity_sold,
            st.unit_cost,
            st.unit_price,
            st.total_cost,
            st.total_revenue,
            st.total_profit,
            st.sale_date
        FROM sales_transactions st
        JOIN inventory_batches ib ON ib.batch_id  = st.batch_id
        JOIN products          p  ON p.product_id = ib.product_id
        ORDER BY st.sale_id DESC
        LIMIT 50
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

    sendResponse(ok(['transactions' => array_values($rows)]));
}

// Simulates the FIFO cost for a prospective sale without committing anything.
// Called on every keystroke in the sell modal (debounced) so the user
// sees real cost/revenue/profit figures before confirming.
// The client never learns unit cost — only the derived totals are returned.
function PreviewSale(PDO $db): void
{
    $productId = postInt('product_id');
    $quantity  = postInt('quantity');
    $sellPrice = postFloat('sell_price');

    if ($productId < 1) sendResponse(fail('Invalid product.'));
    if ($quantity  < 1) sendResponse(fail('Quantity must be at least 1.'));
    if ($sellPrice <= 0) sendResponse(fail('Sell price must be greater than zero.'));

    $batches        = fetchAvailableBatches($db, $productId);
    $totalAvailable = (int) array_sum(array_column($batches, 'quantity_remaining'));

    if ($quantity > $totalAvailable) {
        sendResponse(fail("Only {$totalAvailable} unit(s) available in stock."));
    }

    $blendedCost  = simulateFifoCost($batches, $quantity);
    $totalRevenue = $quantity * $sellPrice;
    $totalProfit  = $totalRevenue - $blendedCost;

    sendResponse(ok([
        'available'     => $totalAvailable,
        'total_cost'    => round($blendedCost,  2),
        'total_revenue' => round($totalRevenue, 2),
        'total_profit'  => round($totalProfit,  2),
    ]));
}

// Creates a new product and inserts its initial inventory batch atomically.
// Both writes succeed or both roll back — no orphaned products or batches.
function AddProduct(PDO $db): void
{
    $productName = postString('name');
    $productCode = postString('code');
    $category    = postString('desc');
    $quantity    = postInt('qty');
    $unitCost    = postFloat('cost');

    if ($productName === '') sendResponse(fail('Product name is required.'));
    if ($productCode === '') sendResponse(fail('Product code is required.'));
    if ($quantity    <  1)  sendResponse(fail('Quantity must be at least 1.'));
    if ($unitCost    <= 0)  sendResponse(fail('Unit cost must be greater than zero.'));

    // Enforce unique product code before opening a transaction
    $check = $db->prepare("SELECT COUNT(*) FROM products WHERE product_code = ?");
    $check->execute([$productCode]);
    if ((int) $check->fetchColumn() > 0) {
        sendResponse(fail("Product code \"{$productCode}\" is already in use."));
    }

    $db->beginTransaction();

    try {
        $db->prepare("
            INSERT INTO products (product_name, product_code, product_description)
            VALUES (?, ?, ?)
        ")->execute([$productName, $productCode, $category]);

        $newProductId = (int) $db->lastInsertId();
        $totalCapital = $quantity * $unitCost;

        $db->prepare("
            INSERT INTO inventory_batches
                (product_id, quantity_purchased, quantity_remaining, unit_cost, total_capital, purchase_date)
            VALUES (?, ?, ?, ?, ?, NOW())
        ")->execute([$newProductId, $quantity, $quantity, $unitCost, $totalCapital]);

        $db->commit();

        sendResponse(ok(['product_id' => $newProductId]));

    } catch (Exception $e) {
        $db->rollBack();
        sendResponse(fail('Failed to save product: ' . $e->getMessage()));
    }
}

// Deducts sold units from inventory using FIFO (oldest batch first).
// A transaction lock (FOR UPDATE) prevents race conditions when two
// requests hit the same product simultaneously.
// One sales_transactions row is inserted per batch touched.
function RecordSale(PDO $db): void
{
    $productId      = postInt('product_id');
    $quantityToSell = postInt('quantity');
    $sellPrice      = postFloat('sell_price');

    if ($productId      < 1)  sendResponse(fail('Invalid product.'));
    if ($quantityToSell < 1)  sendResponse(fail('Quantity must be at least 1.'));
    if ($sellPrice      <= 0) sendResponse(fail('Sell price must be greater than zero.'));

    $db->beginTransaction();

    try {
        $batches        = fetchAvailableBatches($db, $productId, lock: true);
        $totalAvailable = (int) array_sum(array_column($batches, 'quantity_remaining'));

        if ($quantityToSell > $totalAvailable) {
            $db->rollBack();
            sendResponse(fail("Only {$totalAvailable} unit(s) in stock."));
        }

        $remaining = $quantityToSell;

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $batchId        = (int)   $batch['batch_id'];
            $unitsFromBatch = min($remaining, (int) $batch['quantity_remaining']);
            $batchUnitCost  = (float) $batch['unit_cost'];

            $lineCost    = $unitsFromBatch * $batchUnitCost;
            $lineRevenue = $unitsFromBatch * $sellPrice;
            $lineProfit  = $lineRevenue - $lineCost;

            $db->prepare("
                INSERT INTO sales_transactions
                    (batch_id, quantity_sold, unit_cost, unit_price,
                     total_cost, total_revenue, total_profit, sale_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ")->execute([$batchId, $unitsFromBatch, $batchUnitCost, $sellPrice, $lineCost, $lineRevenue, $lineProfit]);

            $db->prepare("
                UPDATE inventory_batches
                SET    quantity_remaining = quantity_remaining - ?
                WHERE  batch_id = ?
            ")->execute([$unitsFromBatch, $batchId]);

            $remaining -= $unitsFromBatch;
        }

        $db->commit();

        sendResponse(ok());

    } catch (Exception $e) {
        $db->rollBack();
        sendResponse(fail('Sale failed: ' . $e->getMessage()));
    }
}

// Adds a new inventory batch for an existing product (restock event).
// Each restock creates a separate batch so FIFO cost tracking stays accurate.
function RestockProduct(PDO $db): void
{
    $productId = postInt('product_id');
    $quantity  = postInt('quantity');
    $unitCost  = postFloat('unit_cost');

    if ($productId < 1)  sendResponse(fail('Invalid product.'));
    if ($quantity  < 1)  sendResponse(fail('Quantity must be at least 1.'));
    if ($unitCost  <= 0) sendResponse(fail('Unit cost must be greater than zero.'));

    $check = $db->prepare("SELECT COUNT(*) FROM products WHERE product_id = ?");
    $check->execute([$productId]);
    if (!(int) $check->fetchColumn()) {
        sendResponse(fail('Product not found.'));
    }

    $totalCapital = $quantity * $unitCost;

    $db->prepare("
        INSERT INTO inventory_batches
            (product_id, quantity_purchased, quantity_remaining, unit_cost, total_capital, purchase_date)
        VALUES (?, ?, ?, ?, ?, NOW())
    ")->execute([$productId, $quantity, $quantity, $unitCost, $totalCapital]);

    sendResponse(ok());
}

// ============================================================
// LEGACY HANDLERS
// These return raw HTML table rows (not JSON) to stay compatible
// with older pages that inject responses directly into a <tbody>.
// New pages should use the JSON handlers above instead.
// ============================================================

function LegacyInventoryBatch(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');

    $rows = $db->query("
        SELECT batch_id, product_id, quantity_purchased, quantity_remaining,
               unit_cost, total_capital, purchase_date
        FROM   inventory_batches
        ORDER  BY batch_id
    ")->fetchAll(PDO::FETCH_ASSOC);

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

function LegacyProducts(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');

    $rows = $db->query("
        SELECT product_id, product_name, product_code, product_description,
               created_at, updated_at
        FROM   products
        ORDER  BY product_id
    ")->fetchAll(PDO::FETCH_ASSOC);

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

function LegacySalesTransactions(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');

    $rows = $db->query("
        SELECT sale_id, batch_id, quantity_sold, unit_cost, unit_price,
               total_cost, total_revenue, total_profit, sale_date
        FROM   sales_transactions
        ORDER  BY sale_id
    ")->fetchAll(PDO::FETCH_ASSOC);

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

function LegacyInventorySummary(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');

    // Try the view first; fall back to an inline aggregate if the view doesn't exist yet.
    try {
        $rows = $db->query("
            SELECT product_id, product_name, total_quantity_purchased,
                   total_stock_remaining, total_capital_invested, inventory_value
            FROM   product_inventory_summary
            ORDER  BY product_id
        ")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $rows = $db->query("
            SELECT p.product_id,
                   p.product_name,
                   COALESCE(SUM(ib.quantity_purchased), 0)                AS total_quantity_purchased,
                   COALESCE(SUM(ib.quantity_remaining), 0)                AS total_stock_remaining,
                   COALESCE(SUM(ib.total_capital), 0)                     AS total_capital_invested,
                   COALESCE(SUM(ib.quantity_remaining * ib.unit_cost), 0) AS inventory_value
            FROM   products p
            LEFT JOIN inventory_batches ib ON ib.product_id = p.product_id
            GROUP  BY p.product_id, p.product_name
            ORDER  BY p.product_id
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    foreach ($rows as $r) {
        echo "<tr data-id='" . esc($r['product_id']) . "'>
            <td>" . esc($r['product_id'])               . "</td>
            <td>" . esc($r['product_name'])             . "</td>
            <td>" . esc($r['total_quantity_purchased'])  . "</td>
            <td>" . esc($r['total_stock_remaining'])     . "</td>
            <td>" . esc($r['total_capital_invested'])    . "</td>
            <td>" . esc($r['inventory_value'])           . "</td>
        </tr>";
    }

    exit;
}

function LegacyProfitSummary(PDO $db): void
{
    header('Content-Type: text/html; charset=utf-8');

    // Try the view first; fall back to an inline aggregate if the view doesn't exist yet.
    try {
        $rows = $db->query("
            SELECT product_id, product_name, total_quantity_sold,
                   total_cost, total_revenue, total_profit
            FROM   product_profit_summary
            ORDER  BY product_id
        ")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $rows = $db->query("
            SELECT p.product_id,
                   p.product_name,
                   COALESCE(SUM(st.quantity_sold), 0) AS total_quantity_sold,
                   COALESCE(SUM(st.total_cost), 0)    AS total_cost,
                   COALESCE(SUM(st.total_revenue), 0) AS total_revenue,
                   COALESCE(SUM(st.total_profit), 0)  AS total_profit
            FROM   products p
            LEFT JOIN inventory_batches  ib ON ib.product_id = p.product_id
            LEFT JOIN sales_transactions st ON st.batch_id   = ib.batch_id
            GROUP  BY p.product_id, p.product_name
            ORDER  BY p.product_id
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    foreach ($rows as $r) {
        echo "<tr data-id='" . esc($r['product_id']) . "'>
            <td>" . esc($r['product_id'])          . "</td>
            <td>" . esc($r['product_name'])        . "</td>
            <td>" . esc($r['total_quantity_sold'])  . "</td>
            <td>" . esc($r['total_cost'])           . "</td>
            <td>" . esc($r['total_revenue'])        . "</td>
            <td>" . esc($r['total_profit'])         . "</td>
        </tr>";
    }

    exit;
}

// ============================================================
// DISPATCH
// ============================================================

$action = trim($_POST['action'] ?? '');

switch ($action) {
    case 'getKPIs':
        GetKPIs($db);
        break;

    case 'getProductCards':
        GetProductCards($db);
        break;

    case 'getTransactionLog':
        GetTransactionLog($db);
        break;

    case 'previewSale':
        PreviewSale($db);
        break;

    case 'addProduct':
        AddProduct($db);
        break;

    case 'sellProduct':
        RecordSale($db);
        break;

    case 'restockProduct':
        RestockProduct($db);
        break;

    // Legacy — HTML row responses
    case 'getInventoryBatch':
        LegacyInventoryBatch($db);
        break;

    case 'getProducts':
        LegacyProducts($db);
        break;

    case 'getSalesTransaction':
        LegacySalesTransactions($db);
        break;

    case 'getInventorySummary':
        LegacyInventorySummary($db);
        break;

    case 'getProfitSummary':
        LegacyProfitSummary($db);
        break;

    default:
        sendResponse(['error' => "Unknown action: '{$action}'."]);
}