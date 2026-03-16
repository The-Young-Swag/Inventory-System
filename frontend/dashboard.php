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
            <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 animate-pulse space-y-3">
                <div class="h-3 bg-slate-800 rounded w-2/3"></div>
                <div class="h-2 bg-slate-800 rounded w-1/2"></div>
                <div class="h-1 bg-slate-800 rounded mt-2"></div>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    <div class="h-10 bg-slate-800 rounded-xl"></div>
                    <div class="h-10 bg-slate-800 rounded-xl"></div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="h-9 bg-slate-800 rounded-xl"></div>
                    <div class="h-9 bg-slate-800 rounded-xl"></div>
                </div>
            </div>
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
    'use strict';

    // === CONFIG =======================================================
    const BACKEND = 'backend/bk_dashboard.php';

    // === DOM SELECTOR =================================================
    const $id = id => $('#' + id);

    // === ID MAP (grouped by feature) ==================================
    const ID = {
        clock: 'dashClock',
        kpi: {
            capital: 'kpi-capital', stock: 'kpi-stockval',
            revenue: 'kpi-revenue', profit: 'kpi-profit'
        },
        products: {
            grid: 'productGrid', count: 'productCount',
            search: 'productSearch', addBtn: 'btnAddProduct'
        },
        txn: 'txnLog',
        add: {
            name: 'ap-name', code: 'ap-code', desc: 'ap-desc',
            qty: 'ap-qty', cost: 'ap-cost', totalCapital: 'ap-totalCapital',
            submit: 'btnSubmitAddProduct'
        },
        sell: {
            id: 'sell-productId', label: 'sell-productLabel', available: 'sell-available',
            qty: 'sell-qty', price: 'sell-price',
            cost: 'sell-previewCost', revenue: 'sell-previewRevenue',
            profit: 'sell-previewProfit', error: 'sell-previewError',
            submit: 'btnSubmitSell'
        },
        restock: {
            id: 'restock-productId', label: 'restock-productLabel',
            qty: 'restock-qty', cost: 'restock-cost',
            capital: 'restock-previewCapital', submit: 'btnSubmitRestock'
        }
    };

    // === MODAL CONTROLLER =============================================
    const Modal = {
        open: id => $('#' + id).removeClass('hidden').addClass('flex'),
        close: id => $('#' + id).addClass('hidden').removeClass('flex')
    };

    // === UTILITIES =====================================================
    const peso = val => '₱' + parseFloat(val || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    const parseJSON = raw => { try { return JSON.parse(raw) } catch { return null } };

    // API call that auto‑parses response
    const apiJSON = (action, data = {}) =>
        $.post(BACKEND, { action, ...data }).then(parseJSON);

    // Returns product from state by ID
    const getProduct = id => State.products.find(p => p.product_id === id);

    // Resets sell preview fields (used in multiple places)
    const resetSellPreview = () => {
        $id(ID.sell.cost).text('—');
        $id(ID.sell.revenue).text('—');
        $id(ID.sell.profit)
            .text('—')
            .removeClass('text-emerald-400 text-rose-400')
            .addClass('text-slate-400');
    };

    // Empty product grid HTML (extracted for readability)
    const EMPTY_PRODUCTS_HTML = `
        <div class="col-span-4 flex flex-col items-center justify-center py-16 gap-3">
            <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-slate-800 flex items-center justify-center">
                <svg class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
            </div>
            <div class="text-center">
                <p class="text-sm text-slate-500 font-medium">No products yet</p>
                <p class="text-xs text-slate-600 mt-0.5">Click "Add Product" to get started</p>
            </div>
        </div>`;

    // === STATE =========================================================
    const State = {
        products: [],
        clockTimer: null,
        previewTimer: null
    };

    // === CORE APPLICATION ==============================================
    const App = {
        // ---- stock badge & product card builder -----------------------
        stockBadge(stock) {
            if (stock <= 0) return `<span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20"><span class="w-1.5 h-1.5 rounded-full bg-rose-400 shrink-0"></span>Out of stock</span>`;
            if (stock <= 5) return `<span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20"><span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>${stock} left — low</span>`;
            return `<span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>${stock} in stock</span>`;
        },

        buildCard(p) {
            const stock = p.stock_remaining;
            const out = stock <= 0;
            const pct = p.total_purchased > 0 ? Math.min(100, (stock / p.total_purchased) * 100) : 0;
            const bar = out ? 'bg-rose-500' : stock <= 5 ? 'bg-amber-400' : 'bg-emerald-400';
            return `
            <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-4 flex flex-col gap-3.5 hover:border-slate-700">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-white truncate">${p.product_name}</p>
                        <p class="text-[10px] text-slate-500 mt-0.5 truncate">${p.product_code} · ${p.product_description || '—'}</p>
                    </div>
                    ${App.stockBadge(stock)}
                </div>
                <div class="w-full h-1 bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full ${bar} rounded-full" style="width:${pct}%"></div>
                </div>
                <div class="bg-slate-800/50 rounded-xl p-2.5">
                    <p class="text-[10px] text-slate-500 mb-0.5">Last sell price</p>
                    <p class="text-sm font-bold text-emerald-400">${p.last_sell_price ? peso(p.last_sell_price) : '<span class="text-slate-600 text-xs">No sales yet</span>'}</p>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button class="js-open-sell py-2.5 rounded-xl text-xs font-bold ${out ? 'bg-slate-800/50 text-slate-600 cursor-not-allowed' : 'bg-slate-800 hover:bg-emerald-500 hover:text-slate-950 text-slate-300'}" data-product-id="${p.product_id}" ${out ? 'disabled' : ''}>Sell</button>
                    <button class="js-open-restock py-2.5 rounded-xl text-xs font-bold bg-slate-800 hover:bg-blue-500 hover:text-white text-slate-300 flex items-center justify-center gap-1" data-product-id="${p.product_id}"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>Restock</button>
                </div>
            </div>`;
        },

        // ---- clock -----------------------------------------------------
        startClock() {
            clearInterval(State.clockTimer);
            const tick = () => $id(ID.clock).text(new Date().toLocaleString('en-PH', { dateStyle: 'medium', timeStyle: 'short' }));
            tick();
            State.clockTimer = setInterval(tick, 30000);
        },

        // ---- data loading & rendering ----------------------------------
        refresh() { App.loadKPIs(); App.loadProducts(); App.loadLog(); },

        loadKPIs() {
            apiJSON('getKPIs').then(res => {
                if (!res?.success) return;
                $id(ID.kpi.capital).text(peso(res.capital));
                $id(ID.kpi.stock).text(peso(res.stock_value));
                $id(ID.kpi.revenue).text(peso(res.revenue));
                $id(ID.kpi.profit)
                    .text(peso(res.profit))
                    .removeClass('text-emerald-400 text-rose-400')
                    .addClass(res.profit >= 0 ? 'text-emerald-400' : 'text-rose-400');
            });
        },

        loadProducts() {
            apiJSON('getProductCards').then(res => {
                if (!res?.success) {
                    $id(ID.products.grid).html('<p class="col-span-4 text-center text-slate-600 py-10 text-xs">Could not load products.</p>');
                    return;
                }
                State.products = res.products;
                App.renderProducts(res.products);
            });
        },

        renderProducts(prods) {
            $id(ID.products.count).text(prods.length);
            if (!prods.length) {
                $id(ID.products.grid).html(EMPTY_PRODUCTS_HTML);
                return;
            }
            $id(ID.products.grid).html(prods.map(p => App.buildCard(p)).join(''));
        },

        loadLog() {
            apiJSON('getTransactionLog').then(res => {
                if (!res?.success || !res.transactions.length) {
                    $id(ID.txn).html('<tr><td colspan="7" class="text-center text-slate-600 py-10 text-xs">No transactions yet</td></tr>');
                    return;
                }
                const rows = res.transactions.map(t => `
                    <tr class="border-b border-slate-800/40 hover:bg-slate-800/30">
                        <td class="px-5 py-3 text-slate-600 whitespace-nowrap">#${t.sale_id}</td>
                        <td class="px-5 py-3 text-slate-300 font-medium whitespace-nowrap">${t.product_name}</td>
                        <td class="px-5 py-3 text-slate-300 whitespace-nowrap">${t.quantity_sold}</td>
                        <td class="px-5 py-3 text-slate-300 whitespace-nowrap">${peso(t.unit_price)}</td>
                        <td class="px-5 py-3 text-sky-400 font-semibold whitespace-nowrap">${peso(t.total_revenue)}</td>
                        <td class="px-5 py-3 font-bold ${t.total_profit >= 0 ? 'text-emerald-400' : 'text-rose-400'} whitespace-nowrap">${peso(t.total_profit)}</td>
                        <td class="px-5 py-3 text-slate-600 text-[10px] whitespace-nowrap">${t.sale_date}</td>
                    </tr>`).join('');
                $id(ID.txn).html(rows);
            });
        },

        // ---- add product -----------------------------------------------
        openAdd() {
            $id(ID.add.name).val(''); $id(ID.add.code).val(''); $id(ID.add.desc).val('');
            $id(ID.add.qty).val(''); $id(ID.add.cost).val(''); $id(ID.add.totalCapital).text('₱0.00');
            Modal.open('modalAddProduct');
            setTimeout(() => $id(ID.add.name).focus(), 100);
        },

        submitAdd() {
            const name = $id(ID.add.name).val().trim(), code = $id(ID.add.code).val().trim(),
                  desc = $id(ID.add.desc).val().trim(), qty = $id(ID.add.qty).val(),
                  cost = $id(ID.add.cost).val();
            if (!name || !code || !qty || !cost)
                return window.showToast('Please fill in all required fields', 'error');

            apiJSON('addProduct', { name, code, desc, qty, cost }).then(res => {
                if (res?.success) {
                    Modal.close('modalAddProduct');
                    window.showToast('Product added!', 'success');
                    App.refresh();
                } else {
                    window.showToast(res?.error || 'Failed to add product', 'error');
                }
            });
        },

        // ---- sell ------------------------------------------------------
        openSell(id) {
            const p = getProduct(id);
            if (!p) return;
            $id(ID.sell.id).val(id);
            $id(ID.sell.label).text(`${p.product_name} · ${p.product_code}`);
            $id(ID.sell.available).text(p.stock_remaining);
            // Reset form + preview
            $id(ID.sell.qty).val(''); $id(ID.sell.price).val('');
            resetSellPreview();
            $id(ID.sell.error).addClass('hidden').text('');
            $id(ID.sell.submit).prop('disabled', false);
            Modal.open('modalSell');
            setTimeout(() => $id(ID.sell.qty).focus(), 100);
        },

        previewSell() {
            clearTimeout(State.previewTimer);
            const pid = parseInt($id(ID.sell.id).val()) || 0;
            const qty = parseInt($id(ID.sell.qty).val()) || 0;
            const price = parseFloat($id(ID.sell.price).val()) || 0;
            if (!pid || qty < 1 || price <= 0) {
                resetSellPreview();
                $id(ID.sell.error).addClass('hidden').text('');
                return;
            }
            State.previewTimer = setTimeout(() => {
                apiJSON('previewSale', { product_id: pid, quantity: qty, sell_price: price }).then(res => {
                    if (!res?.success) {
                        resetSellPreview();
                        $id(ID.sell.error).removeClass('hidden').text(res.error);
                        $id(ID.sell.submit).prop('disabled', true);
                        return;
                    }
                    $id(ID.sell.error).addClass('hidden').text('');
                    $id(ID.sell.submit).prop('disabled', false);
                    $id(ID.sell.cost).text(peso(res.total_cost));
                    $id(ID.sell.revenue).text(peso(res.total_revenue));
                    $id(ID.sell.profit)
                        .text(peso(res.total_profit))
                        .removeClass('text-emerald-400 text-rose-400 text-slate-400')
                        .addClass(res.total_profit >= 0 ? 'text-emerald-400' : 'text-rose-400');
                });
            }, 350);
        },

        submitSell() {
            const pid = $id(ID.sell.id).val(), qty = $id(ID.sell.qty).val(), price = $id(ID.sell.price).val();
            if (!qty || !price) return window.showToast('Enter a quantity and sell price', 'error');
            apiJSON('sellProduct', { product_id: pid, quantity: qty, sell_price: price }).then(res => {
                if (res?.success) {
                    Modal.close('modalSell');
                    window.showToast('Sale recorded!', 'success');
                    App.refresh();
                } else {
                    window.showToast(res?.error || 'Sale failed', 'error');
                }
            });
        },

        // ---- restock ---------------------------------------------------
        openRestock(id) {
            const p = getProduct(id);
            if (!p) return;
            $id(ID.restock.id).val(id);
            $id(ID.restock.label).text(`${p.product_name} · ${p.product_code}`);
            $id(ID.restock.qty).val(''); $id(ID.restock.cost).val(''); $id(ID.restock.capital).text('₱0.00');
            Modal.open('modalRestock');
            setTimeout(() => $id(ID.restock.qty).focus(), 100);
        },

        submitRestock() {
            const pid = $id(ID.restock.id).val(), qty = $id(ID.restock.qty).val(), cost = $id(ID.restock.cost).val();
            if (!qty || !cost) return window.showToast('Enter a quantity and unit cost', 'error');
            apiJSON('restockProduct', { product_id: pid, quantity: qty, unit_cost: cost }).then(res => {
                if (res?.success) {
                    Modal.close('modalRestock');
                    window.showToast('Stock updated!', 'success');
                    App.refresh();
                } else {
                    window.showToast(res?.error || 'Restock failed', 'error');
                }
            });
        }
    };

    // === EVENT BINDINGS (grouped by feature) ===========================
    $(document).off('.dash');

    // ----- Products -----------------------------------------------------
    $id(ID.products.search).on('input.dash', function () {
        const q = $(this).val().toLowerCase().trim();
        App.renderProducts(q ? State.products.filter(p => p.product_name.toLowerCase().includes(q) || p.product_code.toLowerCase().includes(q)) : State.products);
    });

    // ----- Add Product --------------------------------------------------
    $id(ID.products.addBtn).on('click.dash', App.openAdd);
    $(document).on('input.dash', '#ap-qty, #ap-cost', () => {
        $id(ID.add.totalCapital).text(peso(($id(ID.add.qty).val()||0) * ($id(ID.add.cost).val()||0)));
    });
    $id(ID.add.submit).on('click.dash', App.submitAdd);

    // ----- Sell ---------------------------------------------------------
    $(document).on('click.dash', '.js-open-sell', e => App.openSell(parseInt($(e.currentTarget).data('product-id'))));
    $(document).on('input.dash', '#sell-qty, #sell-price', App.previewSell);
    $id(ID.sell.submit).on('click.dash', App.submitSell);

    // ----- Restock ------------------------------------------------------
    $(document).on('click.dash', '.js-open-restock', e => App.openRestock(parseInt($(e.currentTarget).data('product-id'))));
    $(document).on('input.dash', '#restock-qty, #restock-cost', () => {
        $id(ID.restock.capital).text(peso(($id(ID.restock.qty).val()||0) * ($id(ID.restock.cost).val()||0)));
    });
    $id(ID.restock.submit).on('click.dash', App.submitRestock);

    // ----- Modals -------------------------------------------------------
    $(document).on('click.dash', '.js-close-modal', e => Modal.close($(e.currentTarget).data('modal')));
    $(document).on('click.dash', '#modalAddProduct, #modalSell, #modalRestock', e => {
        if (e.target === e.currentTarget) Modal.close(e.currentTarget.id);
    });

    // === BOOT ===========================================================
    App.startClock();
    App.refresh();
});
</script>