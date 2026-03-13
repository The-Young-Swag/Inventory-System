<?php
$conn = require __DIR__ . "/../config/database.php";
$type = $_POST['type'] ?? '';

// ── Shared helpers ──────────────────────────────────────────────────────────
function modal($title, $subtitle, $content, $icon = '') {
    echo <<<HTML
<div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl w-full max-w-md pointer-events-auto">
    <!-- Header -->
    <div class="flex items-start justify-between px-6 py-5 border-b border-slate-800">
        <div class="flex items-center gap-3">
            {$icon}
            <div>
                <h2 class="text-sm font-bold text-white">{$title}</h2>
                <p class="text-xs text-slate-500 mt-0.5">{$subtitle}</p>
            </div>
        </div>
        <button onclick="closeModal()" class="text-slate-600 hover:text-slate-300 transition-colors ml-4 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div class="px-6 py-5">{$content}</div>
</div>
HTML;
}

function formGroup($label, $field, $hint = '') {
    $h = $hint ? "<p class='text-xs text-slate-600 mt-1'>{$hint}</p>" : '';
    return "<div class='space-y-1.5'><label class='text-xs font-semibold text-slate-400 uppercase tracking-wide'>{$label}</label>{$field}{$h}</div>";
}

function inp($name, $type='text', $placeholder='', $value='', $required=true, $extra='') {
    $r = $required ? 'required' : '';
    return "<input type='{$type}' name='{$name}' id='{$name}' value='".htmlspecialchars($value)."' placeholder='{$placeholder}'
        class='w-full bg-slate-800 border border-slate-700 hover:border-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/40 text-slate-100 text-sm rounded-lg px-3.5 py-2.5 transition-all outline-none placeholder:text-slate-600' {$r} {$extra}>";
}

function btn($label, $id, $color='emerald') {
    $colors = [
        'emerald' => 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-900/40',
        'rose'    => 'bg-rose-600 hover:bg-rose-500 text-white',
        'slate'   => 'bg-slate-700 hover:bg-slate-600 text-slate-200 border border-slate-600'
    ];
    return "<button type='button' id='{$id}' class='flex-1 py-2.5 px-4 rounded-lg text-sm font-semibold transition-all {$colors[$color]}'>{$label}</button>";
}

$icon_product = '<div class="w-9 h-9 bg-emerald-500/10 rounded-xl flex items-center justify-center shrink-0"><svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 8V5a2 2 0 012-2z"/></svg></div>';
$icon_batch   = '<div class="w-9 h-9 bg-violet-500/10 rounded-xl flex items-center justify-center shrink-0"><svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg></div>';
$icon_sale    = '<div class="w-9 h-9 bg-amber-500/10 rounded-xl flex items-center justify-center shrink-0"><svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>';
$icon_delete  = '<div class="w-9 h-9 bg-rose-500/10 rounded-xl flex items-center justify-center shrink-0"><svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></div>';


// ════════════════════════════════════════════════════════════════════════════
// ADD PRODUCT
// ════════════════════════════════════════════════════════════════════════════
if ($type === 'addProduct') {
    $content = '
    <div class="space-y-4">
        '.formGroup('Product Name', inp('product_name','text','e.g. Coca-Cola 1L')).'
        '.formGroup('SKU / Code', inp('product_sku','text','e.g. COKE-1L-001'), 'Unique identifier for this product').'
        '.formGroup('Default Selling Price (₱)', inp('selling_price','number','0.00','',true,'step="0.01" min="0"'), 'Price per unit when sold').'
        '.formGroup('Description', '<textarea name="product_description" rows="2" placeholder="Optional notes about this product..."
            class="w-full bg-slate-800 border border-slate-700 hover:border-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/40 text-slate-100 text-sm rounded-lg px-3.5 py-2.5 transition-all outline-none placeholder:text-slate-600 resize-none"></textarea>').'
        <div class="flex gap-3 pt-2">
            '.btn('Cancel', 'btnCancelProduct', 'slate').'
            '.btn('Add Product', 'btnAddProduct', 'emerald').'
        </div>
        <div id="productError" class="hidden text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-lg px-3 py-2"></div>
    </div>
    <script>
    $("#btnCancelProduct").on("click", closeModal);
    $("#btnAddProduct").on("click", function(){
        const btn = $(this).text("Saving...").prop("disabled", true);
        $.post("backend/bk_products.php", {
            request: "addProduct",
            product_name:        $("#product_name").val(),
            product_sku:         $("#product_sku").val(),
            selling_price:       $("#selling_price").val(),
            product_description: $("[name=product_description]").val()
        }, function(res) {
            const r = JSON.parse(res);
            if (r.success) {
                closeModal();
                toast("Product added successfully!", "success");
                if(typeof reloadInventoryData === "function") reloadInventoryData();
                else reloadPage();
            } else {
                $("#productError").text(r.message).removeClass("hidden");
                btn.text("Add Product").prop("disabled", false);
            }
        });
    });
    </script>';
    modal('Add New Product', 'Fill in the product details', $content, $icon_product);
}


// ════════════════════════════════════════════════════════════════════════════
// EDIT PRODUCT
// ════════════════════════════════════════════════════════════════════════════
elseif ($type === 'editProduct') {
    $pid = (int)($_POST['product_id'] ?? 0);
    $p = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $p->execute([$pid]);
    $prod = $p->fetch();

    if (!$prod) { echo "<div class='text-rose-400 p-6 text-sm'>Product not found.</div>"; exit; }

    $content = "
    <div class='space-y-4'>
        ".formGroup('Product Name', inp('product_name','text','Product name',$prod['product_name']))."
        ".formGroup('SKU / Code', inp('product_sku','text','SKU',$prod['product_sku']), 'Must be unique')."
        ".formGroup('Default Selling Price (₱)', inp('selling_price','number','0.00',$prod['selling_price'],true,'step=\"0.01\" min=\"0\"'))."
        ".formGroup('Description', '<textarea name=\"product_description\" rows=\"2\"
            class=\"w-full bg-slate-800 border border-slate-700 hover:border-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/40 text-slate-100 text-sm rounded-lg px-3.5 py-2.5 transition-all outline-none placeholder:text-slate-600 resize-none\">".htmlspecialchars($prod['product_description'])."</textarea>')."
        <input type='hidden' id='edit_product_id' value='{$pid}'>
        <div class='flex gap-3 pt-2'>
            ".btn('Cancel', 'btnCancelEdit', 'slate')."
            ".btn('Save Changes', 'btnSaveProduct', 'emerald')."
        </div>
        <div id='editError' class='hidden text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-lg px-3 py-2'></div>
    </div>
    <script>
    \$('#btnCancelEdit').on('click', closeModal);
    \$('#btnSaveProduct').on('click', function(){
        const btn = \$(this).text('Saving...').prop('disabled', true);
        \$.post('backend/bk_products.php', {
            request: 'editProduct',
            product_id:          \$('#edit_product_id').val(),
            product_name:        \$('#product_name').val(),
            product_sku:         \$('#product_sku').val(),
            selling_price:       \$('#selling_price').val(),
            product_description: \$('[name=product_description]').val()
        }, function(res){
            const r = JSON.parse(res);
            if(r.success){ closeModal(); toast('Product updated!','success'); if(typeof reloadInventoryData==='function') reloadInventoryData(); else reloadPage(); }
            else { \$('#editError').text(r.message).removeClass('hidden'); btn.text('Save Changes').prop('disabled',false); }
        });
    });
    </script>";
    modal('Edit Product', 'Update product information', $content, $icon_product);
}


// ════════════════════════════════════════════════════════════════════════════
// ADD INVENTORY BATCH
// ════════════════════════════════════════════════════════════════════════════
elseif ($type === 'addBatch') {
    $products = $conn->query("SELECT product_id, product_name FROM products ORDER BY product_name")->fetchAll();

    $options = '<option value="">— Choose a product —</option>';
    foreach ($products as $p) {
        $options .= "<option value='{$p['product_id']}' data-product-id='{$p['product_id']}'>" . htmlspecialchars($p['product_name']) . "</option>";
    }

    $selClass = "w-full bg-slate-800 border border-slate-700 hover:border-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/40 text-slate-100 text-sm rounded-lg px-3.5 py-2.5 transition-all outline-none";

    $content = "
    <div class='space-y-4'>
        ".formGroup('Product', "<select id='batch_product_id' class='{$selClass}'>{$options}</select>", 'Select which product this batch belongs to')."
        <div class='grid grid-cols-2 gap-3'>
            ".formGroup('Quantity Purchased', inp('batch_qty','number','e.g. 100','',true,'min=\"1\" step=\"1\"'))."
            ".formGroup('Unit Cost (₱)', inp('batch_unit_cost','number','0.00','',true,'step=\"0.01\" min=\"0\"'), 'How much you paid per unit')."
        </div>
        ".formGroup('Purchase Date', inp('batch_date','datetime-local','',date('Y-m-d\TH:i')))."

        <!-- Live preview -->
        <div id='batchPreview' class='hidden bg-slate-800/60 border border-slate-700 rounded-xl p-4 space-y-2'>
            <p class='text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2'>Capital Preview</p>
            <div class='flex justify-between text-sm'>
                <span class='text-slate-500'>Qty × Unit Cost</span>
                <span class='text-white font-bold' id='previewTotal'>₱0.00</span>
            </div>
        </div>

        <div class='flex gap-3 pt-2'>
            ".btn('Cancel', 'btnCancelBatch', 'slate')."
            ".btn('Add Batch', 'btnAddBatch', 'emerald')."
        </div>
        <div id='batchError' class='hidden text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-lg px-3 py-2'></div>
    </div>
    <script>
    function updatePreview(){
        const qty  = parseFloat(\$('#batch_qty').val())  || 0;
        const cost = parseFloat(\$('#batch_unit_cost').val()) || 0;
        if(qty > 0 && cost > 0){
            \$('#previewTotal').text('₱' + (qty*cost).toLocaleString('en-PH',{minimumFractionDigits:2,maximumFractionDigits:2}));
            \$('#batchPreview').removeClass('hidden');
        } else { \$('#batchPreview').addClass('hidden'); }
    }
    \$('#batch_qty, #batch_unit_cost').on('input', updatePreview);
    \$('#btnCancelBatch').on('click', closeModal);
    \$('#btnAddBatch').on('click', function(){
        const btn = \$(this).text('Saving...').prop('disabled', true);
        \$.post('backend/bk_inventory.php', {
            request:    'addBatch',
            product_id: \$('#batch_product_id').val(),
            qty:        \$('#batch_qty').val(),
            unit_cost:  \$('#batch_unit_cost').val(),
            date:       \$('#batch_date').val()
        }, function(res){
            const r = JSON.parse(res);
            if(r.success){ closeModal(); toast('Inventory batch added!','success'); if(typeof reloadInventoryData==='function') reloadInventoryData(); else reloadPage(); }
            else { \$('#batchError').text(r.message).removeClass('hidden'); btn.text('Add Batch').prop('disabled',false); }
        });
    });
    </script>";
    modal('Add Inventory Batch', 'Record a new stock purchase', $content, $icon_batch);
}


// ════════════════════════════════════════════════════════════════════════════
// RECORD SALE
// ════════════════════════════════════════════════════════════════════════════
elseif ($type === 'recordSale') {
    $prefillBatch = (int)($_POST['prefill_batch'] ?? 0);

    $batches = $conn->query("
        SELECT b.batch_id, p.product_name, p.selling_price, b.quantity_remaining, b.unit_cost
        FROM inventory_batches b
        JOIN products p ON b.product_id = p.product_id
        WHERE b.quantity_remaining > 0
        ORDER BY p.product_name, b.batch_id
    ")->fetchAll();

    $batchData = [];
    $options = '<option value="">— Choose a batch —</option>';
    foreach ($batches as $b) {
        $sel = $prefillBatch === (int)$b['batch_id'] ? 'selected' : '';
        $options .= "<option value='{$b['batch_id']}' data-selling='{$b['selling_price']}' data-cost='{$b['unit_cost']}' data-remaining='{$b['quantity_remaining']}' {$sel}>
            {$b['product_name']} — Batch #{$b['batch_id']} ({$b['quantity_remaining']} in stock @ ₱{$b['unit_cost']} cost)
        </option>";
        $batchData[$b['batch_id']] = $b;
    }

    $selClass = "w-full bg-slate-800 border border-slate-700 hover:border-slate-600 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/40 text-slate-100 text-sm rounded-lg px-3.5 py-2.5 transition-all outline-none";

    $content = "
    <div class='space-y-4'>
        ".formGroup('Batch / Product', "<select id='sale_batch_id' class='{$selClass}'>{$options}</select>", 'Only batches with stock are shown')."
        <div class='grid grid-cols-2 gap-3'>
            ".formGroup('Quantity to Sell', inp('sale_qty','number','e.g. 5','',true,'min=\"1\" step=\"1\"'))."
            ".formGroup('Selling Price per Unit (₱)', inp('sale_price','number','0.00','',true,'step=\"0.01\" min=\"0\"'), 'What you charge the buyer')."
        </div>

        <!-- Live preview -->
        <div id='salePreview' class='hidden bg-slate-800/60 border border-slate-700 rounded-xl p-4 space-y-2.5'>
            <p class='text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1'>Sale Summary</p>
            <div class='flex justify-between text-xs'>
                <span class='text-slate-500'>Cost of Goods</span>
                <span class='text-rose-400 font-medium' id='prevCOGS'>₱0.00</span>
            </div>
            <div class='flex justify-between text-xs'>
                <span class='text-slate-500'>Revenue</span>
                <span class='text-slate-300 font-medium' id='prevRevenue'>₱0.00</span>
            </div>
            <div class='h-px bg-slate-700'></div>
            <div class='flex justify-between text-sm'>
                <span class='text-slate-400 font-semibold'>Profit</span>
                <span class='font-bold' id='prevProfit'>₱0.00</span>
            </div>
        </div>

        <div class='flex gap-3 pt-2'>
            ".btn('Cancel', 'btnCancelSale', 'slate')."
            ".btn('Record Sale', 'btnRecordSale', 'emerald')."
        </div>
        <div id='saleError' class='hidden text-xs text-rose-400 bg-rose-500/10 border border-rose-500/20 rounded-lg px-3 py-2'></div>
    </div>
    <script>
    // Pre-fill price from batch
    \$('#sale_batch_id').on('change', function(){
        const opt = \$(this).find('option:selected');
        const selling = opt.data('selling') || '';
        if(selling) \$('#sale_price').val(parseFloat(selling).toFixed(2));
        updateSalePreview();
    }).trigger('change');

    function fmt(n){ return '₱' + parseFloat(n||0).toLocaleString('en-PH',{minimumFractionDigits:2,maximumFractionDigits:2}); }

    function updateSalePreview(){
        const opt  = \$('#sale_batch_id').find('option:selected');
        const qty  = parseFloat(\$('#sale_qty').val())   || 0;
        const price= parseFloat(\$('#sale_price').val()) || 0;
        const cost = parseFloat(opt.data('cost'))        || 0;
        if(qty>0 && price>0 && cost>0){
            const cogs    = qty*cost;
            const revenue = qty*price;
            const profit  = revenue-cogs;
            \$('#prevCOGS').text(fmt(cogs));
            \$('#prevRevenue').text(fmt(revenue));
            \$('#prevProfit').text(fmt(profit)).removeClass('text-emerald-400 text-rose-400').addClass(profit>=0?'text-emerald-400':'text-rose-400');
            \$('#salePreview').removeClass('hidden');
        } else { \$('#salePreview').addClass('hidden'); }
    }
    \$('#sale_qty, #sale_price').on('input', updateSalePreview);
    \$('#btnCancelSale').on('click', closeModal);
    \$('#btnRecordSale').on('click', function(){
        const btn = \$(this).text('Recording...').prop('disabled',true);
        \$.post('backend/bk_sales.php', {
            request:    'recordSale',
            batch_id:   \$('#sale_batch_id').val(),
            qty:        \$('#sale_qty').val(),
            unit_price: \$('#sale_price').val()
        }, function(res){
            const r = JSON.parse(res);
            if(r.success){ closeModal(); toast('Sale recorded!','success'); if(typeof reloadInventoryData==='function') reloadInventoryData(); else reloadPage(); }
            else { \$('#saleError').text(r.message).removeClass('hidden'); btn.text('Record Sale').prop('disabled',false); }
        });
    });
    </script>";
    modal('Record a Sale', 'Deduct from stock and log the transaction', $content, $icon_sale);
}


// ════════════════════════════════════════════════════════════════════════════
// CONFIRM DELETE PRODUCT
// ════════════════════════════════════════════════════════════════════════════
elseif ($type === 'confirmDeleteProduct') {
    $pid  = (int)($_POST['product_id'] ?? 0);
    $name = htmlspecialchars($_POST['product_name'] ?? '');

    $content = "
    <div class='space-y-5'>
        <div class='bg-rose-500/8 border border-rose-500/20 rounded-xl p-4 text-sm text-rose-300'>
            You are about to delete <strong>{$name}</strong>. This will also remove all inventory batches linked to it (cascading delete). This action <strong>cannot be undone</strong>.
        </div>
        <div class='flex gap-3'>
            ".btn('Cancel', 'btnCancelDel', 'slate')."
            ".btn('Delete Product', 'btnConfirmDel', 'rose')."
        </div>
    </div>
    <script>
    \$('#btnCancelDel').on('click', closeModal);
    \$('#btnConfirmDel').on('click', function(){
        \$(this).text('Deleting...').prop('disabled',true);
        \$.post('backend/bk_products.php', { request:'deleteProduct', product_id:{$pid} }, function(res){
            const r = JSON.parse(res);
            if(r.success){ closeModal(); toast('Product deleted.','info'); if(typeof reloadInventoryData==='function') reloadInventoryData(); else reloadPage(); }
            else { closeModal(); toast(r.message,'error'); }
        });
    });
    </script>";
    modal('Delete Product', 'This is a permanent action', $content, $icon_delete);
}


// ════════════════════════════════════════════════════════════════════════════
// CONFIRM DELETE SALE
// ════════════════════════════════════════════════════════════════════════════
elseif ($type === 'confirmDeleteSale') {
    $sid = (int)($_POST['sale_id'] ?? 0);

    $content = "
    <div class='space-y-5'>
        <div class='bg-rose-500/8 border border-rose-500/20 rounded-xl p-4 text-sm text-rose-300'>
            You are about to void <strong>Sale #$sid</strong>. The quantity will be returned to the batch stock. This cannot be undone.
        </div>
        <div class='flex gap-3'>
            ".btn('Cancel', 'btnCancelDelSale', 'slate')."
            ".btn('Void Sale', 'btnConfirmDelSale', 'rose')."
        </div>
    </div>
    <script>
    \$('#btnCancelDelSale').on('click', closeModal);
    \$('#btnConfirmDelSale').on('click', function(){
        \$(this).text('Voiding...').prop('disabled',true);
        \$.post('backend/bk_sales.php', { request:'deleteSale', sale_id:{$sid} }, function(res){
            const r = JSON.parse(res);
            if(r.success){ closeModal(); toast('Sale voided. Stock restored.','info'); if(typeof reloadInventoryData==='function') reloadInventoryData(); else reloadPage(); }
            else { closeModal(); toast(r.message,'error'); }
        });
    });
    </script>";
    modal('Void Sale', "Sale #{$sid}", $content, $icon_delete);
}

else {
    echo "<div class='bg-slate-900 border border-slate-700 rounded-2xl p-8 text-slate-500 text-sm'>Unknown modal type.</div>";
}
