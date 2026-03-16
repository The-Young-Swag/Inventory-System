<?php
// backend/bk_products.php
$conn = require __DIR__ . "/../config/database.php";

header('Content-Type: application/json');

$request = $_POST['request'] ?? '';

function jsonOk($msg = 'OK', $extra = []) { echo json_encode(array_merge(['success' => true, 'message' => $msg], $extra)); exit; }
function jsonErr($msg)                     { echo json_encode(['success' => false, 'message' => $msg]); exit; }


// ── Add Product ───────────────────────────────────────────────────────────────
if ($request === 'addProduct') {
    $name  = trim($_POST['product_name']        ?? '');
    $code   = trim($_POST['product_code']         ?? '');
    $price = (float)($_POST['selling_price']    ?? 0);
    $desc  = trim($_POST['product_description'] ?? '');

    if (!$name)  jsonErr('Product name is required.');
    if (!$code)   jsonErr('code is required.');
    if ($price < 0) jsonErr('Selling price must be 0 or greater.');

    $check = $conn->prepare("SELECT COUNT(*) FROM products WHERE product_code = ?");
    $check->execute([$code]);
    if ($check->fetchColumn() > 0) jsonErr("code '{$code}' is already in use.");

    $stmt = $conn->prepare("INSERT INTO products (product_name, product_code, selling_price, product_description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $code, $price, $desc ?: null]);

    // Return the new product_id so the caller can immediately add the first batch
    $newId = (int)$conn->lastInsertId();
    jsonOk('Product added successfully.', ['product_id' => $newId]);
}


// ── Edit Product ──────────────────────────────────────────────────────────────
if ($request === 'editProduct') {
    $pid   = (int)($_POST['product_id']         ?? 0);
    $name  = trim($_POST['product_name']        ?? '');
    $code   = trim($_POST['product_code']         ?? '');
    $price = (float)($_POST['selling_price']    ?? 0);
    $desc  = trim($_POST['product_description'] ?? '');

    if (!$pid)  jsonErr('Invalid product.');
    if (!$name) jsonErr('Product name is required.');
    if (!$code)  jsonErr('code is required.');

    $check = $conn->prepare("SELECT COUNT(*) FROM products WHERE product_code = ? AND product_id != ?");
    $check->execute([$code, $pid]);
    if ($check->fetchColumn() > 0) jsonErr("code '{$code}' is already used by another product.");

    $stmt = $conn->prepare("UPDATE products SET product_name=?, product_code=?, selling_price=?, product_description=? WHERE product_id=?");
    $stmt->execute([$name, $code, $price, $desc ?: null, $pid]);

    jsonOk('Product updated.');
}


// ── Delete Product ─────────────────────────────────────────────────────────────
if ($request === 'deleteProduct') {
    $pid = (int)($_POST['product_id'] ?? 0);
    if (!$pid) jsonErr('Invalid product ID.');

    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$pid]);

    jsonOk('Product deleted.');
}
