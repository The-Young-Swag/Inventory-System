<?php // frontend/inventory.php ?>

<div class="p-8 space-y-8">

    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-white">Inventory</h1>
            <p class="text-sm text-slate-500 mt-1">Manage your products and stock batches</p>
        </div>
        <div class="flex items-center gap-2">
            <button id="btnAddProduct"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Product
            </button>
            <button id="btnAddBatch"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium bg-emerald-500 hover:bg-emerald-400 text-white shadow-lg shadow-emerald-500/20 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Batch
            </button>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-slate-900 border border-slate-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/60">
            <div class="flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-violet-400"></div>
                <h2 class="text-sm font-semibold text-white">Products</h2>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input id="searchProducts" type="text" placeholder="Search products…"
                        class="bg-slate-800 border border-slate-700 rounded-lg pl-8 pr-3 py-1.5 text-xs text-slate-300 placeholder-slate-600 focus:outline-none focus:border-slate-500 w-44">
                </div>
                <button id="refreshProducts" class="p-1.5 rounded-lg hover:bg-slate-800 text-slate-500 hover:text-slate-300 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="productsTable" class="w-full text-xs
                [&_thead_th]:px-5 [&_thead_th]:py-3.5 [&_thead_th]:text-left [&_thead_th]:text-[10px] [&_thead_th]:uppercase [&_thead_th]:tracking-wider [&_thead_th]:text-slate-500 [&_thead_th]:font-semibold [&_thead_th]:bg-slate-800/50 [&_thead_th]:whitespace-nowrap
                [&_tbody_td]:px-5 [&_tbody_td]:py-3.5 [&_tbody_td]:text-slate-300 [&_tbody_td]:whitespace-nowrap [&_tbody_td]:border-b [&_tbody_td]:border-slate-800/40
                [&_tbody_tr:last-child_td]:border-0
                [&_tbody_tr]:transition-colors [&_tbody_tr:hover]:bg-slate-800/30 [&_tbody_tr]:cursor-default">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    <tr><td colspan="6" class="text-center text-slate-600 py-10">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-5 h-5 border-2 border-slate-700 border-t-violet-400 rounded-full animate-spin"></div>
                            <span class="text-xs">Loading products…</span>
                        </div>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inventory Batches Table -->
    <div class="bg-slate-900 border border-slate-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/60">
            <div class="flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                <h2 class="text-sm font-semibold text-white">Inventory Batches</h2>
            </div>
            <button id="refreshBatches" class="p-1.5 rounded-lg hover:bg-slate-800 text-slate-500 hover:text-slate-300 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs
                [&_thead_th]:px-5 [&_thead_th]:py-3.5 [&_thead_th]:text-left [&_thead_th]:text-[10px] [&_thead_th]:uppercase [&_thead_th]:tracking-wider [&_thead_th]:text-slate-500 [&_thead_th]:font-semibold [&_thead_th]:bg-slate-800/50 [&_thead_th]:whitespace-nowrap
                [&_tbody_td]:px-5 [&_tbody_td]:py-3.5 [&_tbody_td]:text-slate-300 [&_tbody_td]:whitespace-nowrap [&_tbody_td]:border-b [&_tbody_td]:border-slate-800/40
                [&_tbody_tr:last-child_td]:border-0
                [&_tbody_tr]:transition-colors [&_tbody_tr:hover]:bg-slate-800/30">
                <thead>
                    <tr>
                        <th>Batch ID</th>
                        <th>Product ID</th>
                        <th>Qty Purchased</th>
                        <th>Qty Remaining</th>
                        <th>Unit Cost</th>
                        <th>Total Capital</th>
                        <th>Purchase Date</th>
                    </tr>
                </thead>
                <tbody id="inventoryTableBody">
                    <tr><td colspan="7" class="text-center text-slate-600 py-10">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-5 h-5 border-2 border-slate-700 border-t-blue-400 rounded-full animate-spin"></div>
                            <span class="text-xs">Loading batches…</span>
                        </div>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
(function(){

    function loadEmpty(tbodyId, cols, color) {
        $(`#${tbodyId}`).html(`<tr><td colspan="${cols}" class="text-center text-slate-600 py-8 text-xs">No records found</td></tr>`);
    }

    function loadProducts() {
        $.post('backend/bk_dashboard.php', { request: 'getProducts' }, function(data) {
            $('#productsTableBody').html(data || '');
            if (!data.trim()) loadEmpty('productsTableBody', 6);
        });
    }

    function loadBatches() {
        $.post('backend/bk_dashboard.php', { request: 'getInventoryBatch' }, function(data) {
            $('#inventoryTableBody').html(data || '');
            if (!data.trim()) loadEmpty('inventoryTableBody', 7);

            // Style low-stock rows
            $('#inventoryTableBody tr').each(function(){
                const tds = $(this).find('td');
                if (tds.length >= 4) {
                    const purchased  = parseInt(tds.eq(2).text()) || 0;
                    const remaining  = parseInt(tds.eq(3).text()) || 0;
                    const pct = purchased > 0 ? remaining / purchased : 1;
                    if (pct <= 0.1) {
                        tds.eq(3).addClass('text-rose-400 font-semibold');
                    } else if (pct <= 0.3) {
                        tds.eq(3).addClass('text-amber-400 font-semibold');
                    } else {
                        tds.eq(3).addClass('text-emerald-400');
                    }
                }
            });
        });
    }

    loadProducts();
    loadBatches();

    // Refresh buttons
    $('#refreshProducts').on('click', function() {
        $('#productsTableBody').html('<tr><td colspan="6" class="text-center text-slate-600 py-8 text-xs"><div class="w-4 h-4 border-2 border-slate-700 border-t-violet-400 rounded-full animate-spin mx-auto"></div></td></tr>');
        loadProducts();
    });

    $('#refreshBatches').on('click', function() {
        $('#inventoryTableBody').html('<tr><td colspan="7" class="text-center text-slate-600 py-8 text-xs"><div class="w-4 h-4 border-2 border-slate-700 border-t-blue-400 rounded-full animate-spin mx-auto"></div></td></tr>');
        loadBatches();
    });

    // Search products
    $('#searchProducts').on('input', function() {
        const q = $(this).val().toLowerCase();
        $('#productsTableBody tr').each(function(){
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(q));
        });
    });

    // Open Add Batch modal
    $('#btnAddBatch').on('click', function() {
        $.post('frontend/modals.php', { modal: 'addBatch' }, function(data) {
            $('#modalContainer').html(data).removeClass('hidden');
        });
    });

    $('#btnAddProduct').on('click', function() {
        $.post('frontend/modals.php', { modal: 'addProduct' }, function(data) {
            $('#modalContainer').html(data).removeClass('hidden');
        });
    });

})();
</script>