<?php
// backend/bk_inventory.php
$conn = require __DIR__ . "/../config/database.php";

header('Content-Type: application/json');

$request = $_POST['request'] ?? '';

function jsonOk($msg = 'OK') { echo json_encode(['success' => true, 'message' => $msg]); exit; }
function jsonErr($msg)        { echo json_encode(['success' => false, 'message' => $msg]); exit; }


// ── Add Batch ─────────────────────────────────────────────────────────────────
if ($request === 'addBatch') {
    $productId = (int)($_POST['product_id'] ?? 0);
    $qty       = (int)($_POST['qty']        ?? 0);
    $unitCost  = (float)($_POST['unit_cost'] ?? 0);
    $date      = trim($_POST['date']        ?? '');

    if (!$productId) jsonErr('Please select a product.');
    if ($qty <= 0)   jsonErr('Quantity must be at least 1.');
    if ($unitCost <= 0) jsonErr('Unit cost must be greater than 0.');

    // Validate product exists
    $check = $conn->prepare("SELECT COUNT(*) FROM products WHERE product_id = ?");
    $check->execute([$productId]);
    if (!$check->fetchColumn()) jsonErr('Product not found.');

    // Parse date
    $purchaseDate = $date ? date('Y-m-d H:i:s', strtotime($date)) : date('Y-m-d H:i:s');

    $stmt = $conn->prepare("
        INSERT INTO inventory_batches (product_id, quantity_purchased, quantity_remaining, unit_cost, purchase_date)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$productId, $qty, $qty, $unitCost, $purchaseDate]);

    jsonOk('Inventory batch added. Capital: ₱' . number_format($qty * $unitCost, 2));
}


// ── Delete Batch ─────────────────────────────────────────────────────────────
if ($request === 'deleteBatch') {
    $batchId = (int)($_POST['batch_id'] ?? 0);
    if (!$batchId) jsonErr('Invalid batch ID.');

    // Check no sales linked
    $check = $conn->prepare("SELECT COUNT(*) FROM sales_transactions WHERE batch_id = ?");
    $check->execute([$batchId]);
    if ($check->fetchColumn() > 0) jsonErr('Cannot delete a batch that has sales. Void the sales first.');

    $stmt = $conn->prepare("DELETE FROM inventory_batches WHERE batch_id = ?");
    $stmt->execute([$batchId]);

    jsonOk('Batch deleted.');
}
