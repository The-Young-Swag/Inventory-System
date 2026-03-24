<?php // frontend/dashboard.php ?>

<div class="h-full flex flex-col">

    <!-- ── Top Bar ────────────────────────────────────────────────────── -->
    <div class="flex-shrink-0 px-6 pt-6 pb-4 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-white tracking-tight">Dashboard</h1>
            <p class="text-xs text-slate-500 mt-0.5" id="dashClock">—</p>
        </div>
        <button id="btnAddProduct"
            class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-150 active:scale-95 shadow-lg shadow-emerald-500/20">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Product
        </button>
    </div>

    <!-- ── KPI Cards ──────────────────────────────────────────────────── -->
    <div class="flex-shrink-0 px-6 pb-5 grid grid-cols-2 xl:grid-cols-4 gap-3">

        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Capital Invested</span>
                <div class="w-7 h-7 rounded-lg bg-amber-400/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none" id="kpi-capital">₱0.00</p>
                <p class="text-[10px] text-slate-600 mt-1">Total amount spent</p>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Stock Value</span>
                <div class="w-7 h-7 rounded-lg bg-blue-400/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none" id="kpi-stockval">₱0.00</p>
                <p class="text-[10px] text-slate-600 mt-1">Current on-hand inventory</p>
            </div>
        </div>

        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Total Revenue</span>
                <div class="w-7 h-7 rounded-lg bg-sky-400/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-2xl font-bold text-white leading-none" id="kpi-revenue">₱0.00</p>
                <p class="text-[10px] text-slate-600 mt-1">From all completed sales</p>
            </div>
        </div>

        <div class="bg-slate-900 border border-emerald-900/50 rounded-2xl p-4 flex flex-col gap-3 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent pointer-events-none"></div>
            <div class="flex items-center justify-between relative">
                <span class="text-[10px] font-semibold uppercase tracking-widest text-emerald-500/60">Net Profit</span>
                <div class="w-7 h-7 rounded-lg bg-emerald-400/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="relative">
                <p class="text-2xl font-bold text-emerald-400 leading-none" id="kpi-profit">₱0.00</p>
                <p class="text-[10px] text-emerald-900 mt-1">Revenue minus costs</p>
            </div>
        </div>

    </div>

    <!-- ── Products Header ────────────────────────────────────────────── -->
    <div class="flex-shrink-0 px-6 pb-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <p class="text-sm font-semibold text-white"><span id="productCount">0</span> products</p>
            <div class="hidden sm:flex items-center gap-1">
                <span class="inline-flex items-center gap-1 bg-emerald-500/10 text-emerald-400 text-[10px] px-2 py-0.5 rounded-full border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> In stock
                </span>
                <span class="inline-flex items-center gap-1 bg-amber-500/10 text-amber-400 text-[10px] px-2 py-0.5 rounded-full border border-amber-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Low
                </span>
                <span class="inline-flex items-center gap-1 bg-rose-500/10 text-rose-400 text-[10px] px-2 py-0.5 rounded-full border border-rose-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Out
                </span>
            </div>
        </div>
        <div class="relative">
            <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input id="productSearch" type="text" placeholder="Search products…"
                class="bg-slate-900 border border-slate-800 text-slate-300 text-xs pl-8 pr-3 py-2 rounded-lg w-44 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 placeholder:text-slate-600 transition-all">
        </div>
    </div>

    <!-- ── Product Cards Grid ─────────────────────────────────────────── -->
<div class="flex-shrink-0 px-6 pb-5">
    <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-3">
        {{-- skeleton --}}
        <?php foreach (range(1, 3) as $_): ?>
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 animate-pulse space-y-3">
            <div class="flex items-start justify-between">
                <div class="space-y-1.5">
                    <div class="h-3.5 bg-slate-800 rounded w-32"></div>
                    <div class="h-2.5 bg-slate-800 rounded w-20"></div>
                </div>
                <div class="h-5 bg-slate-800 rounded-full w-20"></div>
            </div>
            <div class="grid grid-cols-2 gap-2 pt-1">
                <div class="h-12 bg-slate-800 rounded-xl"></div>
                <div class="h-12 bg-slate-800 rounded-xl"></div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="h-9 bg-slate-800 rounded-xl"></div>
                <div class="h-9 bg-slate-800 rounded-xl"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

    <!-- ── Transaction Log ────────────────────────────────────────────── -->
    <div class="flex-1 min-h-0 px-6 pb-6">
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl overflow-hidden h-full flex flex-col">
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-800/80 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                    <h2 class="text-sm font-semibold text-white">Transaction Log</h2>
                </div>
                <span class="text-[10px] text-slate-600 bg-slate-800 px-2 py-0.5 rounded-full">Recent 50 sales</span>
            </div>
            <div class="overflow-y-auto flex-1">
                <table class="w-full text-xs">
                    <thead class="sticky top-0 bg-slate-800/60 backdrop-blur-sm z-10">
                        <tr>
                            <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-slate-500 font-semibold">#</th>
                            <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Product</th>
                            <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Qty</th>
                            <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Unit Price</th>
                            <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Revenue</th>
                            <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Profit</th>
                            <th class="px-5 py-2.5 text-left text-[10px] uppercase tracking-widest text-slate-500 font-semibold">Date</th>
                        </tr>
                    </thead>
                    <tbody id="txnLog">
                        <tr><td colspan="7" class="text-center text-slate-600 py-10">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-8 h-8 text-slate-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>No transactions yet</span>
                            </div>
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ══ ADD PRODUCT MODAL ════════════════════════════════════════════════ -->
<div id="modalAddProduct" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-md shadow-2xl shadow-black/60 flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800 flex-shrink-0">
            <div>
                <h3 class="text-sm font-bold text-white">Add New Product</h3>
                <p class="text-xs text-slate-500 mt-0.5">Create product and set initial stock</p>
            </div>
            <button class="js-close-modal w-7 h-7 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors" data-modal="modalAddProduct">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="overflow-y-auto flex-1 px-6 py-5 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-[10px] font-semibold uppercase tracking-widest text-slate-500 mb-1.5">Product Name *</label>
                    <input id="ap-name" type="text" placeholder="e.g. Coca-Cola 1L"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-emerald-500/60 focus:ring-1 focus:ring-emerald-500/20 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold uppercase tracking-widest text-slate-500 mb-1.5">Product Code *</label>
                    <input id="ap-code" type="text" placeholder="e.g. COKE-1L"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-emerald-500/60 focus:ring-1 focus:ring-emerald-500/20 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold uppercase tracking-widest text-slate-500 mb-1.5">Category</label>
                    <input id="ap-desc" type="text" placeholder="e.g. Soft drink"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-emerald-500/60 focus:ring-1 focus:ring-emerald-500/20 transition-all">
                </div>
            </div>
            <div class="border-t border-slate-800 pt-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500 mb-3">Initial Stock</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] text-slate-500 mb-1.5">Quantity *</label>
                        <input id="ap-qty" type="number" min="1" placeholder="0"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-emerald-500/60 focus:ring-1 focus:ring-emerald-500/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-slate-500 mb-1.5">Unit Cost (₱) *</label>
                        <input id="ap-cost" type="number" min="0" step="0.01" placeholder="0.00"
                            class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-emerald-500/60 focus:ring-1 focus:ring-emerald-500/20 transition-all">
                    </div>
                </div>
            </div>
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-3.5 flex items-center justify-between">
                <span class="text-xs text-slate-500">Total Capital Required</span>
                <span class="text-sm font-bold text-amber-400" id="ap-totalCapital">₱0.00</span>
            </div>
        </div>
        <div class="flex gap-2 px-6 py-4 border-t border-slate-800 flex-shrink-0">
            <button class="js-close-modal flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-medium py-2.5 rounded-xl transition-colors" data-modal="modalAddProduct">Cancel</button>
            <button id="btnSubmitAddProduct" class="flex-1 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-sm font-bold py-2.5 rounded-xl transition-all active:scale-95 shadow-lg shadow-emerald-500/20">Add Product</button>
        </div>
    </div>
</div>

<!-- ══ SELL MODAL ════════════════════════════════════════════════════════ -->
<div id="modalSell" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-sm shadow-2xl shadow-black/60">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
            <div>
                <h3 class="text-sm font-bold text-white">Record Sale</h3>
                <p class="text-xs text-slate-500 mt-0.5" id="sell-productLabel">—</p>
            </div>
            <button class="js-close-modal w-7 h-7 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors" data-modal="modalSell">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <input type="hidden" id="sell-productId">
            <div class="bg-slate-800/60 rounded-xl px-4 py-3 flex items-center justify-between">
                <span class="text-xs text-slate-500">Available Stock</span>
                <span class="text-sm font-bold text-white" id="sell-available">—</span>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] text-slate-500 mb-1.5">Quantity to Sell *</label>
                    <input id="sell-qty" type="number" min="1" placeholder="0"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-emerald-500/60 focus:ring-1 focus:ring-emerald-500/20 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-slate-500 mb-1.5">Sell Price / unit (₱) *</label>
                    <input id="sell-price" type="number" min="0" step="0.01" placeholder="0.00"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-emerald-500/60 focus:ring-1 focus:ring-emerald-500/20 transition-all">
                </div>
            </div>
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl divide-y divide-slate-700/50">
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-xs text-slate-500">Total Cost</span>
                    <span class="text-xs font-semibold text-slate-400" id="sell-previewCost">—</span>
                </div>
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-xs text-slate-500">Total Revenue</span>
                    <span class="text-xs font-semibold text-sky-400" id="sell-previewRevenue">—</span>
                </div>
                <div class="flex items-center justify-between px-4 py-2.5">
                    <span class="text-xs text-slate-500 font-medium">Profit</span>
                    <span class="text-sm font-bold text-slate-400" id="sell-previewProfit">—</span>
                </div>
            </div>
            <p class="text-xs text-rose-400 hidden" id="sell-previewError"></p>
        </div>
        <div class="flex gap-2 px-6 py-4 border-t border-slate-800">
            <button class="js-close-modal flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-medium py-2.5 rounded-xl transition-colors" data-modal="modalSell">Cancel</button>
            <button id="btnSubmitSell" class="flex-1 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-sm font-bold py-2.5 rounded-xl transition-all active:scale-95 shadow-lg shadow-emerald-500/20 disabled:opacity-40 disabled:pointer-events-none">Confirm Sale</button>
        </div>
    </div>
</div>

<!-- ══ RESTOCK MODAL ═════════════════════════════════════════════════════ -->
<div id="modalRestock" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-sm shadow-2xl shadow-black/60">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
            <div>
                <h3 class="text-sm font-bold text-white">Restock Product</h3>
                <p class="text-xs text-slate-500 mt-0.5" id="restock-productLabel">—</p>
            </div>
            <button class="js-close-modal w-7 h-7 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center transition-colors" data-modal="modalRestock">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <input type="hidden" id="restock-productId">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] text-slate-500 mb-1.5">Quantity to Add *</label>
                    <input id="restock-qty" type="number" min="1" placeholder="0"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-blue-500/60 focus:ring-1 focus:ring-blue-500/20 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] text-slate-500 mb-1.5">Unit Cost (₱) *</label>
                    <input id="restock-cost" type="number" min="0" step="0.01" placeholder="0.00"
                        class="w-full bg-slate-800 border border-slate-700 rounded-xl px-3 py-2.5 text-sm text-slate-200 placeholder:text-slate-600 focus:outline-none focus:border-blue-500/60 focus:ring-1 focus:ring-blue-500/20 transition-all">
                </div>
            </div>
            <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-3 flex items-center justify-between">
                <span class="text-xs text-slate-500">Total Capital Needed</span>
                <span class="text-sm font-bold text-amber-400" id="restock-previewCapital">₱0.00</span>
            </div>
        </div>
        <div class="flex gap-2 px-6 py-4 border-t border-slate-800">
            <button class="js-close-modal flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-medium py-2.5 rounded-xl transition-colors" data-modal="modalRestock">Cancel</button>
            <button id="btnSubmitRestock" class="flex-1 bg-blue-500 hover:bg-blue-400 text-white text-sm font-bold py-2.5 rounded-xl transition-all active:scale-95 shadow-lg shadow-blue-500/20">Add Stock</button>
        </div>
    </div>
</div>


<script>
$(function () {

    // ── Product Cards ──────────────────────────────────────────────
    function loadProductCards() {
        $.post("backend/bk_dashboard.php",
            { request: "getProductCards" },
            data => $("#productGrid").html(data)
        );
    }
    loadProductCards();

    // ── KPIs ───────────────────────────────────────────────────────
    $.post("backend/bk_dashboard.php",
        { request: "getKPIs" },
        data => {
            const d = JSON.parse(data);
            if (!d.success) return;
            const fmt = n => "₱" + parseFloat(n).toLocaleString("en-PH", { minimumFractionDigits: 2 });
            $("#kpi-capital").text(fmt(d.capital));
            $("#kpi-stockval").text(fmt(d.stock_value));
            $("#kpi-revenue").text(fmt(d.revenue));
            $("#kpi-profit").text(fmt(d.profit));
        }
    );

    // ── Transaction Log ────────────────────────────────────────────
    $.post("backend/bk_dashboard.php",
        { request: "getTransactionLog" },
        data => $("#txnLog").html(data)
    );

    // ── Sell modal open ────────────────────────────────────────────
    $(document).on("click", ".btn-sell", function () {
        const $card = $(this).closest("[data-product-id]");
        $("#sell-productId").val($card.data("product-id"));
        $("#sell-productLabel").text($card.data("product-name"));
        $("#sell-available").text($card.data("stock-remaining"));
        $("#sell-qty, #sell-price").val("");
        $("#sell-previewCost, #sell-previewRevenue").text("—");
        $("#sell-previewProfit").text("—").removeClass("text-emerald-400 text-rose-400").addClass("text-slate-400");
        $("#sell-previewError").addClass("hidden").text("");
        $("#btnSubmitSell").prop("disabled", true);
        $("#modalSell").removeClass("hidden");
    });

    // ── Restock modal open ─────────────────────────────────────────
    $(document).on("click", ".btn-restock", function () {
        const $card = $(this).closest("[data-product-id]");
        $("#restock-productId").val($card.data("product-id"));
        $("#restock-productLabel").text($card.data("product-name"));
        $("#restock-qty, #restock-cost").val("");
        $("#restock-previewCapital").text("₱0.00");
        $("#modalRestock").removeClass("hidden");
    });

    // ── Sell preview (debounced) ───────────────────────────────────
    let sellDebounce;
    $("#sell-qty, #sell-price").on("input", function () {
        clearTimeout(sellDebounce);
        sellDebounce = setTimeout(() => {
            const productId = $("#sell-productId").val();
            const qty   = parseInt($("#sell-qty").val())   || 0;
            const price = parseFloat($("#sell-price").val()) || 0;

            if (qty < 1 || price <= 0) {
                $("#sell-previewCost, #sell-previewRevenue").text("—");
                $("#sell-previewProfit").text("—").removeClass("text-emerald-400 text-rose-400").addClass("text-slate-400");
                $("#sell-previewError").addClass("hidden").text("");
                $("#btnSubmitSell").prop("disabled", true);
                return;
            }

            $.post("backend/bk_dashboard.php",
                { request: "previewSale", product_id: productId, quantity: qty, sell_price: price },
                raw => {
                    const d = JSON.parse(raw);
                    const fmt = n => "₱" + parseFloat(n).toFixed(2);

                    if (!d.success) {
                        $("#sell-previewError").removeClass("hidden").text(d.error);
                        $("#btnSubmitSell").prop("disabled", true);
                        return;
                    }

                    $("#sell-previewError").addClass("hidden").text("");
                    $("#sell-previewCost").text(fmt(d.total_cost));
                    $("#sell-previewRevenue").text(fmt(d.total_revenue));

                    const profitEl = $("#sell-previewProfit");
                    profitEl.text(fmt(d.total_profit))
                        .removeClass("text-slate-400 text-emerald-400 text-rose-400")
                        .addClass(d.total_profit >= 0 ? "text-emerald-400" : "text-rose-400");

                    $("#btnSubmitSell").prop("disabled", false);
                }
            );
        }, 300);
    });

    // ── Confirm sale ───────────────────────────────────────────────
    $("#btnSubmitSell").on("click", function () {
        $.post("backend/bk_dashboard.php", {
            request:    "sellProduct",
            product_id: $("#sell-productId").val(),
            quantity:   $("#sell-qty").val(),
            sell_price: $("#sell-price").val(),
        }, raw => {
            const d = JSON.parse(raw);
            if (!d.success) { alert(d.error); return; }
            $("#modalSell").addClass("hidden");
            loadProductCards();
        });
    });

    // ── Confirm restock ────────────────────────────────────────────
    $("#btnSubmitRestock").on("click", function () {
        $.post("backend/bk_dashboard.php", {
            request:    "restockProduct",
            product_id: $("#restock-productId").val(),
            quantity:   $("#restock-qty").val(),
            unit_cost:  $("#restock-cost").val(),
        }, raw => {
            const d = JSON.parse(raw);
            if (!d.success) { alert(d.error); return; }
            $("#modalRestock").addClass("hidden");
            loadProductCards();
        });
    });

    // ── Restock capital preview ────────────────────────────────────
    $("#restock-qty, #restock-cost").on("input", function () {
        const qty  = parseFloat($("#restock-qty").val())  || 0;
        const cost = parseFloat($("#restock-cost").val()) || 0;
        $("#restock-previewCapital").text("₱" + (qty * cost).toFixed(2));
    });

    // ── Add product capital preview ────────────────────────────────
    $("#ap-qty, #ap-cost").on("input", function () {
        const qty  = parseFloat($("#ap-qty").val())  || 0;
        const cost = parseFloat($("#ap-cost").val()) || 0;
        $("#ap-totalCapital").text("₱" + (qty * cost).toFixed(2));
    });

    // ── Confirm add product ────────────────────────────────────────
    $("#btnSubmitAddProduct").on("click", function () {
        $.post("backend/bk_dashboard.php", {
            request: "addProduct",
            name:    $("#ap-name").val(),
            code:    $("#ap-code").val(),
            desc:    $("#ap-desc").val(),
            qty:     $("#ap-qty").val(),
            cost:    $("#ap-cost").val(),
        }, raw => {
            const d = JSON.parse(raw);
            if (!d.success) { alert(d.error); return; }
            $("#modalAddProduct").addClass("hidden");
            loadProductCards();
        });
    });

    // ── Modal close (any .js-close-modal button) ───────────────────
    $(document).on("click", ".js-close-modal", function () {
        $("#" + $(this).data("modal")).addClass("hidden");
    });

    // ── Live clock ─────────────────────────────────────────────────
    (function tick() {
        $("#dashClock").text(new Date().toLocaleString("en-PH", {
            weekday: "short", year: "numeric", month: "short",
            day: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"
        }));
        setTimeout(tick, 1000);
    })();

    // ── Add product modal open ─────────────────────────────────────
    $("#btnAddProduct").on("click", () => $("#modalAddProduct").removeClass("hidden"));

});

</script>