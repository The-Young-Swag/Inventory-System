<div class="inventoryBatches">
<table>
<thead>
<tr>
<th>batch_id</th>
<th>product_id</th>
<th>quantity_purchased</th>
<th>quantity_remaining</th>
<th>unit_cost</th>
<th>total_capital</th>
<th>purchase_date</th>
</tr>
</thead>

<tbody id="inventoryTable">
<tr>
<td>1</td>
<td>2</td>
<td>3</td>
<td>4</td>
<td></td>
<td></td>
<td></td>
</tr>
</tbody>
</table>
</div>



<div class="products">
<table>
<thead>
<tr>
<th>product_id</th>
<th>product_name</th>
<th>product_sku</th>
<th>product_description</th>
<th>created_at</th>
<th>updated_at</th>
</tr>
</thead>

<tbody id="productsTable">
<tr>
<td>1</td>
<td>2</td>
<td>3</td>
<td>4</td>
<td></td>
<td></td>
</tr>
</tbody>
</table>
</div>

<div class="btnSection">
    <button id="addInventory">Add Inventory</button>
</div>

<script>
$(function(){
    $.post("backend/bk_dashboard.php",
        { request: "getInventoryBatch" },
        data => $("#inventoryTable").html(data)
    );

    $.post("backend/bk_dashboard.php",
        { request: "getProducts" },
        data => $("#productsTable").html(data)
    );

    $("#addInventory").on("click", function(){
        $.post("frontend/modals.php",
        data => $(".modalContainer").html(data)
    );
});

});
   
</script>