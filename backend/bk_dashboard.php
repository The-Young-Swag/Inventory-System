<?php
$conn = require __DIR__ . "/../config/database.php"; // PDO instance

function showInventoryBatch() {
    global $conn;

    $sql = "SELECT batch_id, product_id, quantity_purchased, 
                   quantity_remaining, unit_cost, total_capital, purchase_date
            FROM inventory_batches
            ORDER BY batch_id";

    $stmt = $conn->query($sql);

    while ($row = $stmt->fetch()) {
        echo "<tr data-role-id='".htmlspecialchars($row['batch_id'])."'>
                <td>".htmlspecialchars($row['batch_id'])."</td>
                <td>".htmlspecialchars($row['product_id'])."</td>
                <td>".htmlspecialchars($row['quantity_purchased'])."</td>
                <td>".htmlspecialchars($row['quantity_remaining'])."</td>
                <td>".htmlspecialchars($row['unit_cost'])."</td>
                <td>".htmlspecialchars($row['total_capital'])."</td>
                <td>".htmlspecialchars($row['purchase_date'])."</td>
              </tr>";
    }
}

function showProducts() {
    global $conn;

    $sql = "SELECT product_id, product_name, product_sku, 
                   product_description, created_at, updated_at
            FROM products
            ORDER BY product_id";

    $stmt = $conn->query($sql);

    while ($row = $stmt->fetch()) {
        echo "<tr data-role-id='".htmlspecialchars($row['product_id'])."'>
                <td>".htmlspecialchars($row['product_id'])."</td>
                <td>".htmlspecialchars($row['product_name'])."</td>
                <td>".htmlspecialchars($row['product_sku'])."</td>
                <td>".htmlspecialchars($row['product_description'])."</td>
                <td>".htmlspecialchars($row['created_at'])."</td>
                <td>".htmlspecialchars($row['updated_at'])."</td>
              </tr>";
    }
}

$request = $_POST['request'] ?? '';

switch ($request) {
    case "getInventoryBatch":
        showInventoryBatch();
        exit;

    case "getProducts":
        showProducts();
        exit;
}