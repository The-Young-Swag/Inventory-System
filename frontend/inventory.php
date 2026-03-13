<?php // frontend/inventory.php ?>

<div class="p-6 space-y-5">

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-white">Inventory</h1>
            <p class="text-sm text-slate-500 mt-0.5">Manage products, batches and sales</p>
        </div>
        <div class="flex items-center gap-2.5">
            <button onclick="showModal('recordSale')"
                class="flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-sm font-medium rounded-lg border border-slate-700 transition-all">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Record Sale
            </button>
            <button onclick="showModal('addBatch')"
                class="flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-sm font-medium rounded-lg border border-slate-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Batch
            </button>
            <button onclick="showModal('addProduct')"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-emerald-900/40">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Product
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-1 bg-slate-900 border border-slate-800 p-1 rounded-xl w-fit">
        <button class="inv-tab active-tab px-4 py-2 text-sm font-medium rounded-lg transition-all" data-tab="products">Products</button>
        <button class="inv-tab px-4 py-2 text-sm font-medium rounded-lg text-slate-500 hover:text-slate-300 transition-all" data-tab="batches">Inventory Batches</button>
        <button class="inv-tab px-4 py-2 text-sm font-medium rounded-lg text-slate-500 hover:text-slate-300 transition-all" data-tab="sales">Sales Transactions</button>
    </div>

    <!-- Tab Panels -->

    <!-- Products Tab -->
    <div id="tab-products" class="inv-panel bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
            <h2 class="text-sm font-semibold text-white">All Products</h2>
            <span class="text-xs text-slate-500" id="products-count"></span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800">
                        <th class="text-left px-5 py-3 font-medium">#</th>
                        <th class="text-left px-5 py-3 font-medium">Product</th>
                        <th class="text-left px-3 py-3 font-medium">SKU</th>
                        <th class="text-left px-3 py-3 font-medium">Description</th>
                        <th class="text-right px-3 py-3 font-medium">Selling Price</th>
                        <th class="text-right px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody id="productsTable" class="divide-y divide-slate-800/60">
                    <tr><td colspan="6" class="text-center py-10 text-slate-600">
                        <div class="w-5 h-5 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Batches Tab (hidden) -->
    <div id="tab-batches" class="inv-panel hidden bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
            <h2 class="text-sm font-semibold text-white">Inventory Batches</h2>
            <span class="text-xs text-slate-500">Each row = one purchase batch</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800">
                        <th class="text-left px-5 py-3 font-medium">Batch ID</th>
                        <th class="text-left px-5 py-3 font-medium">Product</th>
                        <th class="text-center px-3 py-3 font-medium">Purchased</th>
                        <th class="text-center px-3 py-3 font-medium">Remaining</th>
                        <th class="text-right px-3 py-3 font-medium">Unit Cost</th>
                        <th class="text-right px-3 py-3 font-medium">Total Capital</th>
                        <th class="text-right px-3 py-3 font-medium">Stock Value</th>
                        <th class="text-left px-5 py-3 font-medium">Purchase Date</th>
                    </tr>
                </thead>
                <tbody id="inventoryTable" class="divide-y divide-slate-800/60">
                    <tr><td colspan="8" class="text-center py-10 text-slate-600">
                        <div class="w-5 h-5 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sales Tab (hidden) -->
    <div id="tab-sales" class="inv-panel hidden bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
            <h2 class="text-sm font-semibold text-white">Sales Transactions</h2>
            <span class="text-xs text-slate-500">All recorded sales</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-800">
                        <th class="text-left px-5 py-3 font-medium">Sale ID</th>
                        <th class="text-left px-3 py-3 font-medium">Product / Batch</th>
                        <th class="text-center px-3 py-3 font-medium">Qty</th>
                        <th class="text-right px-3 py-3 font-medium">Unit Cost</th>
                        <th class="text-right px-3 py-3 font-medium">Unit Price</th>
                        <th class="text-right px-3 py-3 font-medium">Revenue</th>
                        <th class="text-right px-3 py-3 font-medium">Profit</th>
                        <th class="text-left px-5 py-3 font-medium">Date</th>
                        <th class="text-right px-5 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody id="salesTable" class="divide-y divide-slate-800/60">
                    <tr><td colspan="9" class="text-center py-10 text-slate-600">
                        <div class="w-5 h-5 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
.inv-tab.active-tab {
    background: rgba(16,185,129,0.12);
    color: rgb(52,211,153);
    font-weight: 600;
}
</style>

<script>
$(function () {

    // ── Tabs ────────────────────────────────────────────────────────────────
    $(".inv-tab").on("click", function () {
        $(".inv-tab").removeClass("active-tab").addClass("text-slate-500 hover:text-slate-300");
        $(this).addClass("active-tab").removeClass("text-slate-500 hover:text-slate-300");
        $(".inv-panel").addClass("hidden");
        $("#tab-" + $(this).data("tab")).removeClass("hidden");
    });

    // ── Load helpers ────────────────────────────────────────────────────────
    function loadProducts() {
        $.post("backend/bk_dashboard.php", { request: "getProducts" }, function(html) {
            $("#productsTable").html(html);
        });
    }

    function loadBatches() {
        $.post("backend/bk_dashboard.php", { request: "getInventoryBatch" }, function(html) {
            $("#inventoryTable").html(html);
        });
    }

    function loadSales() {
        $.post("backend/bk_dashboard.php", { request: "getSalesTransaction" }, function(html) {
            $("#salesTable").html(html);
        });
    }

    // Load all on init
    loadProducts();
    loadBatches();
    loadSales();

    // Expose reload for modals to call
    window.reloadInventoryData = function() {
        loadProducts();
        loadBatches();
        loadSales();
    };

    // ── Delete handlers ─────────────────────────────────────────────────────
    $(document).on("click", ".btn-delete-product", function () {
        const id = $(this).data("id");
        const name = $(this).data("name");
        showModal("confirmDeleteProduct", { product_id: id, product_name: name });
    });

    $(document).on("click", ".btn-delete-sale", function () {
        const id = $(this).data("id");
        showModal("confirmDeleteSale", { sale_id: id });
    });

    $(document).on("click", ".btn-sell-batch", function () {
        const batchId = $(this).data("batch-id");
        showModal("recordSale", { prefill_batch: batchId });
    });

    $(document).on("click", ".btn-edit-product", function () {
        const id = $(this).data("id");
        showModal("editProduct", { product_id: id });
    });
});
</script>
