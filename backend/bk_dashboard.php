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

function showSalesTransaction() {
    global $conn;

    $sql = "SELECT sale_id, batch_id, quantity_sold, 
                   unit_cost, unit_price, total_cost, total_revenue, total_profit, sale_date
            FROM sales_transactions
            ORDER BY sale_id";

    $stmt = $conn->query($sql);

    while ($row = $stmt->fetch()) {
        echo "<tr data-role-id='".htmlspecialchars($row['sale_id'])."'>
                <td>".htmlspecialchars($row['sale_id'])."</td>
                <td>".htmlspecialchars($row['batch_id'])."</td>
                <td>".htmlspecialchars($row['quantity_sold'])."</td>
                <td>".htmlspecialchars($row['unit_cost'])."</td>
                <td>".htmlspecialchars($row['unit_price'])."</td>
                <td>".htmlspecialchars($row['total_cost'])."</td>
                <td>".htmlspecialchars($row['total_revenue'])."</td>
                <td>".htmlspecialchars($row['total_profit'])."</td>
                <td>".htmlspecialchars($row['sale_date'])."</td>
              </tr>";
    }
}

function showInventorySummary() {
    global $conn;

    $sql = "SELECT product_id, product_name, total_quantity_purchased, 
                   total_stock_remaining, total_capital_invested, inventory_value
            FROM product_inventory_summary
            ORDER BY product_id";

    $stmt = $conn->query($sql);

    while ($row = $stmt->fetch()) {
        echo "<tr data-role-id='".htmlspecialchars($row['product_id'])."'>
                <td>".htmlspecialchars($row['product_id'])."</td>
                <td>".htmlspecialchars($row['product_name'])."</td>
                <td>".htmlspecialchars($row['total_quantity_purchased'])."</td>
                <td>".htmlspecialchars($row['total_stock_remaining'])."</td>
                <td>".htmlspecialchars($row['total_capital_invested'])."</td>
                <td>".htmlspecialchars($row['inventory_value'])."</td>
              </tr>";
    }
}

function showProfitSummary() {
    global $conn;

    $sql = "SELECT product_id, product_name, total_quantity_sold, 
                   total_cost, total_revenue, total_profit
            FROM product_profit_summary
            ORDER BY product_id";

    $stmt = $conn->query($sql);

    while ($row = $stmt->fetch()) {
        echo "<tr data-role-id='".htmlspecialchars($row['product_id'])."'>
                <td>".htmlspecialchars($row['product_id'])."</td>
                <td>".htmlspecialchars($row['product_name'])."</td>
                <td>".htmlspecialchars($row['total_quantity_sold'])."</td>
                <td>".htmlspecialchars($row['total_cost'])."</td>
                <td>".htmlspecialchars($row['total_revenue'])."</td>
                <td>".htmlspecialchars($row['total_profit'])."</td>
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

    case "getSalesTransaction":
        showSalesTransaction();
        exit;

    case "getInventorySummary":
        showInventorySummary();
        exit;

    case "getProfitSummary":
        showProfitSummary();
        exit;

        
}