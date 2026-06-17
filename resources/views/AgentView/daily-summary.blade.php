@extends('layouts.app')

@section('title', 'Daily Summary')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Header Section -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Daily Summary ✨</h1>
                <p class="text-slate-500 text-sm mt-1">Overview of sales, returns, and daily loads.</p>
            </div>

            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
                <!-- Agent ID Hidden Input (Get login agent details, no need to select agent) -->
                <input type="hidden" id="summary-agent" value="{{ $agent->id ?? '' }}">

                <!-- Date Picker -->
                <div class="relative">
                    <input id="summary-date"
                        class="form-input pl-9 text-slate-500 hover:text-slate-600 font-medium focus:border-slate-300 w-full md:w-48 bg-white border-slate-200 rounded-lg shadow-sm px-3 py-2"
                        type="date" />
                    <div class="absolute inset-0 right-auto flex items-center pointer-events-none">
                        <svg class="w-4 h-4 fill-current text-slate-500 ml-3" viewBox="0 0 16 16">
                            <path
                                d="M15 2h-2V0h-2v2H9V0H7v2H5V0H3v2H1a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V3a1 1 0 00-1-1zm-1 12H2V6h12v8z" />
                        </svg>
                    </div>
                </div>
                <button id="search-btn"
                    class="btn bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg px-4 py-2 font-medium shadow-sm flex items-center gap-2 transition-colors">
                    <i class="bi bi-search"></i> Search
                </button>
                <a href="{{ route('agent-panel.dashboard') }}"
                    class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors no-underline flex items-center bg-white shadow-sm">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Loader -->
        <div id="loader" class="hidden flex justify-center items-center py-20">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-500"></div>
        </div>

        <!-- Content Area -->
        <div id="summary-content" class="hidden space-y-6">

            <!-- Top Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Sales Card -->
                <div
                    class="flex flex-col bg-slate-800 shadow-lg rounded-xl overflow-hidden border border-slate-700 relative">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 pointer-events-none">
                    </div>
                    <div class="px-5 pt-5 pb-4 relative z-10">
                        <header class="flex justify-between items-start mb-2">
                            <h2 class="text-slate-300 font-semibold text-sm uppercase tracking-wider">Total Sales</h2>
                            <div class="p-2 bg-emerald-500/20 text-emerald-400 rounded-lg"><i
                                    class="bi bi-graph-up-arrow"></i></div>
                        </header>
                        <div class="text-3xl font-bold text-white mb-1" id="stat-sales">Rs. 0.00</div>
                        <div class="text-sm text-slate-400" id="stat-items">0 items sold</div>
                    </div>
                </div>

                <!-- Collected Card -->
                <div class="flex flex-col bg-white shadow-sm rounded-xl overflow-hidden border border-slate-200">
                    <div class="px-5 pt-5 pb-4">
                        <header class="flex justify-between items-start mb-2">
                            <h2 class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Total Collection
                                (Cash/Cheque/Bank)</h2>
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg"><i class="bi bi-cash-stack"></i></div>
                        </header>
                        <div class="text-2xl font-bold text-slate-800 mb-1" id="stat-collected">Rs. 0.00</div>
                        <div class="text-sm text-slate-500">Cash, Cheque & Bank Transfer</div>
                    </div>
                </div>

                <!-- Credit Card -->
                <div class="flex flex-col bg-white shadow-sm rounded-xl overflow-hidden border border-slate-200">
                    <div class="px-5 pt-5 pb-4">
                        <header class="flex justify-between items-start mb-2">
                            <h2 class="text-slate-500 font-semibold text-sm uppercase tracking-wider">Credit Sales</h2>
                            <div class="p-2 bg-amber-100 text-amber-600 rounded-lg"><i class="bi bi-credit-card"></i></div>
                        </header>
                        <div class="text-2xl font-bold text-slate-800 mb-1" id="stat-credit">Rs. 0.00</div>
                        <div class="text-sm text-slate-500">Outstanding amount</div>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Returns Impact -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mr-3">
                            <i class="bi bi-arrow-return-left text-lg"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-slate-800">Customer Returns</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-600">Items Returned</span>
                            <span class="font-semibold text-slate-800" id="ret-count">0 records</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-600">Returns Value</span>
                            <span class="font-semibold text-slate-800" id="ret-value">Rs. 0.00</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Breakdown -->
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mr-3">
                            <i class="bi bi-pie-chart-fill text-lg"></i>
                        </div>
                        <h2 class="text-lg font-semibold text-slate-800">Payment Breakdown</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center py-2 border-b border-slate-100">
                            <div
                                class="w-8 h-8 rounded bg-emerald-50 text-emerald-500 flex items-center justify-center mr-3">
                                <i class="bi bi-cash"></i></div>
                            <span class="text-slate-600 flex-1">Cash</span>
                            <span class="font-semibold text-slate-800" id="pay-cash">Rs. 0.00</span>
                        </div>
                        <div class="flex items-center py-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded bg-blue-50 text-blue-500 flex items-center justify-center mr-3"><i
                                    class="bi bi-credit-card"></i></div>
                            <span class="text-slate-600 flex-1">Credit</span>
                            <span class="font-semibold text-slate-800" id="pay-credit">Rs. 0.00</span>
                        </div>
                        <div class="flex items-center py-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded bg-amber-50 text-amber-500 flex items-center justify-center mr-3"><i
                                    class="bi bi-journal-check"></i></div>
                            <span class="text-slate-600 flex-1">Cheque</span>
                            <span class="font-semibold text-slate-800" id="pay-cheque">Rs. 0.00</span>
                        </div>
                        <div class="flex items-center py-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded bg-purple-50 text-purple-500 flex items-center justify-center mr-3">
                                <i class="bi bi-bank"></i></div>
                            <span class="text-slate-600 flex-1">Bank Transfer</span>
                            <span class="font-semibold text-slate-800" id="pay-bank">Rs. 0.00</span>
                        </div>
                        <div class="flex justify-between items-center py-3 mt-2 bg-slate-50 rounded-lg px-4">
                            <span class="font-bold text-slate-800">Total Collection</span>
                            <span class="font-bold text-emerald-600 text-lg" id="pay-total">Rs. 0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Loads -->
            <div class="bg-white shadow-sm rounded-xl border border-slate-200 mt-6">
                <header class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h2 id="load-header-title" class="font-semibold text-slate-800">Daily Loads on this Date</h2>
                    <span class="text-xs bg-slate-100 text-slate-500 font-medium px-2.5 py-1 rounded-full" id="load-count">0
                        Loads</span>
                </header>
                <div class="p-3">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead
                                class="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 whitespace-nowrap text-left">
                                        <div class="font-semibold text-left">Load ID</div>
                                    </th>
                                    <th class="px-4 py-3 whitespace-nowrap text-left">
                                        <div class="font-semibold text-left">Route Name</div>
                                    </th>
                                    <th class="px-4 py-3 whitespace-nowrap text-left">
                                        <div class="font-semibold text-left">Vehicle</div>
                                    </th>
                                    <th class="px-4 py-3 whitespace-nowrap text-center">
                                        <div class="font-semibold text-center">Status</div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100" id="loads-table-body">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>
                    <div id="no-loads-msg" class="hidden text-center py-8 text-slate-500">
                        No daily loads found for the selected date.
                    </div>
                </div>
            </div>

        </div>

    </div>


    <!-- Load Details Modal -->
    <div id="load-modal"
        class="fixed inset-0 bg-slate-900/60 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 id="modal-title" class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="bi bi-box-seam text-indigo-600"></i> Load Summary
                </h3>
                <button id="close-modal"
                    class="text-slate-400 hover:text-slate-600 transition-colors p-1 rounded-md hover:bg-slate-100">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="p-6">
                <div id="modal-loader" class="py-12 flex flex-col items-center justify-center space-y-4">
                    <div class="w-8 h-8 border-3 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                    <p class="text-slate-500 text-sm font-medium animate-pulse">Loading details...</p>
                </div>

                <div id="modal-content" class="hidden space-y-6">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                            <p class="text-slate-500 text-xs font-medium uppercase tracking-wider mb-1">Total Sales</p>
                            <p id="modal-sales" class="text-xl font-bold text-slate-800">Rs. 0.00</p>
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                            <p class="text-emerald-600/80 text-xs font-medium uppercase tracking-wider mb-1">Total
                                Collection</p>
                            <p id="modal-collected" class="text-xl font-bold text-emerald-700">Rs. 0.00</p>
                        </div>
                    </div>

                    <!-- Returns & Payment Breakdown Side by Side -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Returns -->
                        <div>
                            <h4 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                                <i class="bi bi-arrow-return-left text-amber-500"></i> Returns
                            </h4>
                            <div class="bg-white border border-slate-100 rounded-xl divide-y divide-slate-100">
                                <div class="p-3 flex justify-between items-center hover:bg-slate-50 transition-colors">
                                    <span class="text-slate-600 text-sm">Returns Value</span>
                                    <span id="modal-return-val" class="font-medium text-slate-800">Rs. 0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Breakdown -->
                        <div>
                            <h4 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                                <i class="bi bi-pie-chart text-indigo-500"></i> Payment Breakdown
                            </h4>
                            <div class="bg-white border border-slate-100 rounded-xl divide-y divide-slate-100">
                                <div class="p-3 flex justify-between items-center hover:bg-slate-50 transition-colors">
                                    <span class="text-slate-600 text-sm flex items-center gap-2"><i
                                            class="bi bi-cash text-emerald-500"></i> Cash</span>
                                    <span id="modal-pay-cash" class="font-medium text-slate-800">Rs. 0.00</span>
                                </div>
                                <div class="p-3 flex justify-between items-center hover:bg-slate-50 transition-colors">
                                    <span class="text-slate-600 text-sm flex items-center gap-2"><i
                                            class="bi bi-credit-card text-blue-500"></i> Credit</span>
                                    <span id="modal-pay-credit" class="font-medium text-slate-800">Rs. 0.00</span>
                                </div>
                                <div class="p-3 flex justify-between items-center hover:bg-slate-50 transition-colors">
                                    <span class="text-slate-600 text-sm flex items-center gap-2"><i
                                            class="bi bi-bank text-amber-500"></i> Cheque</span>
                                    <span id="modal-pay-cheque" class="font-medium text-slate-800">Rs. 0.00</span>
                                </div>
                                <div class="p-3 flex justify-between items-center hover:bg-slate-50 transition-colors">
                                    <span class="text-slate-600 text-sm flex items-center gap-2"><i
                                            class="bi bi-arrow-left-right text-cyan-500"></i> Bank Transfer</span>
                                    <span id="modal-pay-bank" class="font-medium text-slate-800">Rs. 0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Transactions Accordion -->
                    <div>
                        <h4 class="text-sm font-semibold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="bi bi-shop text-indigo-500"></i> Customer Transactions
                        </h4>
                        <div id="modal-transactions-container" class="space-y-2 max-h-60 overflow-y-auto pr-1">
                            <!-- Populated by JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('summary-date');
            const agentInput = document.getElementById('summary-agent');
            const searchBtn = document.getElementById('search-btn');
            const loader = document.getElementById('loader');
            const content = document.getElementById('summary-content');

            const modal = document.getElementById('load-modal');
            const closeModalBtn = document.getElementById('close-modal');
            const modalLoader = document.getElementById('modal-loader');
            const modalContent = document.getElementById('modal-content');

            // Format Currency
            const formatCurrency = (amount) => {
                return `Rs. ${parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            };

            // Status Map
            const getStatusBadge = (status) => {
                switch (status) {
                    case 1: return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Loading</span>';
                    case 2: return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Loaded</span>';
                    case 3: return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Started</span>';
                    case 4: return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Completed</span>';
                    case 5: return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Finished</span>';
                    default: return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>';
                }
            };

            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            dateInput.value = today;

            const fetchData = async () => {
                const date = dateInput.value;
                const agentId = agentInput.value;
                if (!date) return;

                content.classList.add('hidden');
                loader.classList.remove('hidden');

                try {
                    let url = `/api/web-daily-summary?date=${date}&agent_id=${agentId}`;
                    const response = await fetch(url);
                    const result = await response.json();

                    if (result.status && result.data) {
                        const data = result.data;

                        // Update Top Stats
                        document.getElementById('stat-sales').innerText = formatCurrency(data.summary.total_sales);
                        document.getElementById('stat-items').innerText = `${data.summary.item_count} items sold`;
                        document.getElementById('stat-collected').innerText = formatCurrency(data.payments.total_collected);
                        document.getElementById('stat-credit').innerText = formatCurrency(data.payments.credit);

                        // Update Returns
                        document.getElementById('ret-count').innerText = `${data.returns.count} records`;
                        document.getElementById('ret-value').innerText = formatCurrency(data.returns.total_value);

                        // Update Payments
                        document.getElementById('pay-cash').innerText = formatCurrency(data.payments.cash);
                        document.getElementById('pay-credit').innerText = formatCurrency(data.payments.credit);
                        document.getElementById('pay-cheque').innerText = formatCurrency(data.payments.cheque);
                        document.getElementById('pay-bank').innerText = formatCurrency(data.payments.bank_transfer);
                        document.getElementById('pay-total').innerText = formatCurrency(data.payments.total_collected);

                        // Update Loads Table
                        const tbody = document.getElementById('loads-table-body');
                        const noMsg = document.getElementById('no-loads-msg');

                        const loadHeader = document.getElementById('load-header-title');
                        document.getElementById('load-count').innerText = `${data.loads.length} Loads`;
                        if (loadHeader) loadHeader.innerText = `Daily Loads on this Date`;

                        tbody.innerHTML = '';
                        if (data.loads.length > 0) {
                            data.loads.forEach(load => {
                                const tr = document.createElement('tr');
                                tr.className = "hover:bg-slate-50 transition-colors group cursor-pointer";
                                tr.onclick = () => openLoadModal(load.id);
                                tr.innerHTML = `
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded bg-slate-100 text-slate-500 flex items-center justify-center mr-3 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                                                <i class="bi bi-box-seam"></i>
                                            </div>
                                            <div class="font-medium text-slate-800">#${load.id}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-slate-800">${load.route_name}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-slate-500">${load.vehicle}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        ${getStatusBadge(load.status)}
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            });
                            tbody.parentElement.classList.remove('hidden');
                            noMsg.classList.add('hidden');
                        } else {
                            tbody.parentElement.classList.add('hidden');
                            noMsg.classList.remove('hidden');
                        }

                        content.classList.remove('hidden');
                    } else {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(result.message || 'Failed to load summary');
                        }
                    }
                } catch (error) {
                    console.error(error);
                    if (typeof toastr !== 'undefined') {
                        toastr.error('An error occurred while fetching data.');
                    }
                } finally {
                    loader.classList.add('hidden');
                }
            };

            const openLoadModal = async (loadId) => {
                modal.classList.remove('hidden');
                modalContent.classList.add('hidden');
                modalLoader.classList.remove('hidden');
                document.getElementById('modal-title').innerHTML = `<i class="bi bi-box-seam text-indigo-600"></i> Load #${loadId} Summary`;

                const date = dateInput.value;

                try {
                    const response = await fetch(`/api/web-daily-summary?date=${date}&load_id=${loadId}`);
                    const result = await response.json();

                    if (result.status && result.data) {
                        const data = result.data;
                        document.getElementById('modal-sales').innerText = formatCurrency(data.summary.total_sales);
                        document.getElementById('modal-collected').innerText = formatCurrency(data.payments.total_collected);
                        document.getElementById('modal-return-val').innerText = formatCurrency(data.returns.total_value);
                        document.getElementById('modal-pay-cash').innerText = formatCurrency(data.payments.cash);
                        document.getElementById('modal-pay-credit').innerText = formatCurrency(data.payments.credit);
                        document.getElementById('modal-pay-cheque').innerText = formatCurrency(data.payments.cheque);
                        document.getElementById('modal-pay-bank').innerText = formatCurrency(data.payments.bank_transfer);

                        // Render Transactions Accordion
                        const transContainer = document.getElementById('modal-transactions-container');
                        transContainer.innerHTML = '';
                        if (data.load_transactions && data.load_transactions.length > 0) {
                            data.load_transactions.forEach((tx, idx) => {
                                const txDiv = document.createElement('div');
                                txDiv.className = "border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm";

                                const contentId = `tx-content-${idx}`;

                                let salesHtml = tx.sales_items.map(item => `
                                    <div class="flex justify-between items-center py-1.5 text-xs text-slate-600 border-b border-slate-50 last:border-0">
                                        <span>${item.product_name} <span class="text-slate-400">x${item.quantity}</span></span>
                                        <span class="font-semibold text-slate-700">${formatCurrency(item.total_price)}</span>
                                    </div>
                                `).join('');
                                if (tx.sales_items.length === 0) {
                                    salesHtml = '<div class="text-xs text-slate-400 italic py-1">No sales items</div>';
                                }

                                let returnsHtml = tx.return_items.map(item => `
                                    <div class="flex justify-between items-center py-1.5 text-xs text-rose-600 border-b border-slate-50 last:border-0">
                                        <span>${item.product_name} <span class="text-rose-400">x${item.quantity}</span> (Return)</span>
                                        <span class="font-semibold">-${formatCurrency(item.total_price)}</span>
                                    </div>
                                `).join('');
                                if (tx.return_items.length === 0) {
                                    returnsHtml = '<div class="text-xs text-slate-400 italic py-1">No returns</div>';
                                }

                                txDiv.innerHTML = `
                                    <button type="button" class="w-full flex items-center justify-between p-3.5 bg-slate-50/50 hover:bg-slate-50 transition-colors text-left font-medium text-slate-800" onclick="document.getElementById('${contentId}').classList.toggle('hidden'); this.querySelector('.arrow-icon').classList.toggle('rotate-180')">
                                        <div class="flex-1">
                                            <div class="text-sm font-bold text-slate-800">${tx.business_name}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">Inv: #${tx.invoice_number}</div>
                                        </div>
                                        <div class="flex items-center gap-3 mr-1">
                                            <div class="text-right">
                                                <span class="text-[10px] text-slate-400 block uppercase tracking-wider">Sales / Returns</span>
                                                <span class="text-xs font-bold text-emerald-600">${formatCurrency(tx.sales_amount)}</span>
                                                ${tx.return_amount > 0 ? `<span class="text-xs font-bold text-rose-500 ml-1">/ -${formatCurrency(tx.return_amount)}</span>` : ''}
                                            </div>
                                            <i class="bi bi-chevron-down arrow-icon text-slate-400 transition-transform duration-200"></i>
                                        </div>
                                    </button>
                                    <div id="${contentId}" class="hidden p-3.5 border-t border-slate-100 bg-white space-y-3.5">
                                        <div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Sales Details</div>
                                            <div class="bg-slate-50/50 rounded-lg p-2.5">${salesHtml}</div>
                                        </div>
                                        <div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Returns Details</div>
                                            <div class="bg-rose-50/20 rounded-lg p-2.5">${returnsHtml}</div>
                                        </div>
                                    </div>
                                `;
                                transContainer.appendChild(txDiv);
                            });
                        } else {
                            transContainer.innerHTML = '<div class="text-center py-6 text-slate-400 text-sm">No completed customer transactions found for this load.</div>';
                        }

                        modalLoader.classList.add('hidden');
                        modalContent.classList.remove('hidden');
                    } else {
                        modal.classList.add('hidden');
                        if (typeof toastr !== 'undefined') toastr.error('Failed to load details');
                    }
                } catch (err) {
                    modal.classList.add('hidden');
                    if (typeof toastr !== 'undefined') toastr.error('An error occurred');
                }
            };

            closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            searchBtn.addEventListener('click', () => {
                fetchData();
            });

            // Initial fetch
            fetchData();
        });
    </script>
@endsection