<div class="usersTable">

<table border="1">

<tr>
<th>batch_id</th>
<th>product_id</th>
<th>quantity_purchased</th>
<th>quantity_remaining</th>
<th>unit_cost</th>
<th>total_capital</th>
<th>purchase_date</th>
</tr>
<tbody id="inventoryTable">
<td>1</td>
<td>2</td>
<td>3</td>
<td>4</td> 
</tbody>

</table>


</div>

<script>
$(function(){
    $.post("backend/bk_dashboard.php",
        { request: "getInventoryBatch" },
        data => $("#inventoryTable").html(data)
    );
});
   
</script>
