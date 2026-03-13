<?php
// backend/bk_sales.php
$conn = require __DIR__ . "/../config/database.php";

header('Content-Type: application/json');

$request = $_POST['request'] ?? '';

function jsonOk($msg = 'OK', $extra = []) { echo json_encode(array_merge(['success' => true, 'message' => $msg], $extra)); exit; }
function jsonErr($msg)                     { echo json_encode(['success' => false, 'message' => $msg]); exit; }


// ── Record Sale ───────────────────────────────────────────────────────────────
if ($request === 'recordSale') {
    $batchId   = (int)($_POST['batch_id']    ?? 0);
    $qty       = (int)($_POST['qty']         ?? 0);
    $unitPrice = (float)($_POST['unit_price'] ?? 0);

    if (!$batchId)      jsonErr('Please select a batch.');
    if ($qty <= 0)      jsonErr('Quantity sold must be at least 1.');
    if ($unitPrice <= 0) jsonErr('Selling price must be greater than 0.');

    // Get batch details and verify stock
    $batchStmt = $conn->prepare("SELECT * FROM inventory_batches WHERE batch_id = ?");
    $batchStmt->execute([$batchId]);
    $batch = $batchStmt->fetch();

    if (!$batch) jsonErr('Batch not found.');

    // The before_sale_insert trigger handles the stock check, but we validate here too for a better UX message
    if ((int)$batch['quantity_remaining'] < $qty) {
        jsonErr("Insufficient stock. Only {$batch['quantity_remaining']} units available in this batch.");
    }

    $unitCost = (float)$batch['unit_cost'];

    try {
        $stmt = $conn->prepare("
            INSERT INTO sales_transactions (batch_id, quantity_sold, unit_cost, unit_price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$batchId, $qty, $unitCost, $unitPrice]);
        $saleId = $conn->lastInsertId();

        $profit  = ($unitPrice - $unitCost) * $qty;
        $revenue = $unitPrice * $qty;

        jsonOk('Sale recorded successfully!', [
            'sale_id' => $saleId,
            'profit'  => $profit,
            'revenue' => $revenue
        ]);
    } catch (PDOException $e) {
        // Catch trigger errors (e.g., insufficient stock)
        $msg = str_contains($e->getMessage(), 'Insufficient') ? 'Insufficient stock for this batch.' : 'Database error: ' . $e->getMessage();
        jsonErr($msg);
    }
}


// ── Delete / Void Sale ────────────────────────────────────────────────────────
if ($request === 'deleteSale') {
    $saleId = (int)($_POST['sale_id'] ?? 0);
    if (!$saleId) jsonErr('Invalid sale ID.');

    // after_sale_delete trigger will automatically restore the stock
    $stmt = $conn->prepare("DELETE FROM sales_transactions WHERE sale_id = ?");
    $stmt->execute([$saleId]);

    if ($stmt->rowCount() === 0) jsonErr('Sale not found.');

    jsonOk('Sale voided. Stock has been restored to the batch.');
}
