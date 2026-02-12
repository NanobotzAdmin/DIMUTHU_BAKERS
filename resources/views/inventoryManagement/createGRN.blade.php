@extends('layouts.app')
@section('title', 'Create GRN')

@section('content')

@php
    // Use the purchaseOrder passed from the controller, or default to null/empty if accessed directly without PO
    $purchaseOrder = $purchaseOrder ?? null;
@endphp

@if(!$purchaseOrder)
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h2 class="text-xl font-bold text-gray-700">No Purchase Order Selected</h2>
            <p class="text-gray-500 mb-4">Please select a Purchase Order to create a GRN.</p>
            <a href="{{ route('purchaseOrderManage.index') }}" class="text-blue-600 hover:underline">Go to Purchase
                Orders</a>
        </div>
    </div>
@else

    <div class="max-w-full mx-auto p-4 sm:p-6 lg:p-8" id="grn-app">

        <div class="mb-6">
            <h1 class="text-2xl font-bold flex items-center gap-3 text-gray-900">
                <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Create Goods Received Note (GRN)
            </h1>
            <p class="text-base text-gray-600 mt-1">
                {{ $purchaseOrder['poNumber'] }} • {{ $purchaseOrder['supplierName'] }}
            </p>
        </div>

        <form id="create-grn-form" method="POST" action="{{ route('createGRN.store') }}">
            @csrf
            <input type="hidden" name="po_id" value="{{ $purchaseOrder['id'] }}">
            <input type="hidden" name="items_json" id="input-items-json">
            <input type="hidden" name="overall_status" id="input-overall-status">

            <div class="space-y-6">

                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-5 border-2 border-emerald-200">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-lg p-3">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="text-sm">Supplier</span>
                            </div>
                            <div class="font-medium text-gray-900 truncate" title="{{ $purchaseOrder['supplierName'] }}">
                                {{ $purchaseOrder['supplierName'] }}
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-3">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm">PO Number</span>
                            </div>
                            <div class="font-medium text-gray-900">{{ $purchaseOrder['poNumber'] }}</div>
                        </div>

                        <div class="bg-white rounded-lg p-3">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm">Expected</span>
                            </div>
                            <div class="font-medium text-gray-900">{{ $purchaseOrder['expectedDeliveryDate'] }}</div>
                        </div>

                        <div class="bg-white rounded-lg p-3">
                            <div class="flex items-center gap-2 text-gray-600 mb-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span class="text-sm">Items</span>
                            </div>
                            <div class="font-medium text-gray-900">{{ count($purchaseOrder['items']) }}</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Received Date *</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <input type="date" name="received_date" value="{{ date('Y-m-d') }}"
                                class="w-full h-12 pl-11 pr-4 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Received By</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <input type="text" name="received_by"
                                value="{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}" readonly
                                class="w-full h-12 pl-11 pr-4 rounded-xl border-2 border-gray-200 bg-gray-50 text-gray-700 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Invoice Number *</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <input type="text" name="invoice_number" id="invoice-number" onkeyup="validateForm()"
                                placeholder="Enter invoice number..."
                                class="w-full h-12 pl-11 pr-4 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Amount (Rs) *</label>
                        <input type="number" name="invoice_amount" id="invoice-amount" value="0.00" placeholder="0.00"
                            class="w-full h-12 px-4 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none">
                        <p id="amount-mismatch-warning" class="hidden text-sm text-orange-600 mt-1">
                            Calculated based on received items.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-5 py-3 border-b-2 border-gray-200">
                        <h3 class="font-medium text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Verify Received Items
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-700">Product</th>
                                    <th class="px-5 py-3 text-right text-sm font-medium text-gray-700">Ordered QTY</th>
                                    <th class="px-5 py-3 text-center text-sm font-medium text-gray-700">Received QTY</th>
                                    <th class="px-5 py-3 text-center text-sm font-medium text-gray-700">Cost Price</th>
                                    <th class="px-5 py-3 text-center text-sm font-medium text-gray-700">Selling Price</th>
                                    <th class="px-5 py-3 text-center text-sm font-medium text-gray-700">Quality Check</th>
                                    <th class="px-5 py-3 text-center text-sm font-medium text-gray-700">Expiry Type</th>
                                    <th class="px-5 py-3 text-center text-sm font-medium text-gray-700">Expiry Date/Period
                                    </th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-700">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="items-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Completed Items Section -->
                <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden shadow-sm"
                    id="completed-items-section" style="display: none;">
                    <div class="bg-gray-50 px-5 py-3 border-b-2 border-gray-200">
                        <h3 class="font-medium text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Completed Items
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-700">Product</th>
                                    <th class="px-5 py-3 text-center text-sm font-medium text-gray-700">Received / Ordered
                                    </th>
                                    <th class="px-5 py-3 text-center text-sm font-medium text-gray-700">Status</th>
                                    <th class="px-5 py-3 text-left text-sm font-medium text-gray-700">Related GRNs</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200" id="completed-items-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="overall-status-panel"
                    class="rounded-xl p-4 border-2 bg-green-50 border-green-200 transition-colors duration-300">
                    <div class="flex items-start gap-3">
                        <div id="status-icon">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium mb-1 text-green-900" id="status-title">Overall Quality Status: PASSED
                            </h4>
                            <div id="discrepancies-list" class="space-y-1 mt-2 hidden">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea name="notes" rows="3"
                        placeholder="Add any additional observations or comments about the delivery..."
                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none resize-none"></textarea>
                </div>

                <div class="flex gap-3 pt-4 border-t-2 border-gray-200">
                    <a href="#"
                        class="h-14 px-6 flex items-center bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl font-medium transition-all">
                        Cancel
                    </a>
                    <div class="flex-1"></div>
                    <button type="submit" id="btn-submit" disabled
                        class="h-14 px-8 bg-gradient-to-br from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl font-medium shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Create GRN & Update Inventory
                    </button>
                </div>

            </div>
        </form>
    </div>

    <script>
        // --- 1. INITIALIZE DATA ---
        const purchaseOrder = @json($purchaseOrder);
        // Initialize items with default received = quantity, status = passed
        let items = purchaseOrder.items.map(item => ({
            ...item,
            actualReceived: Math.max(0, item.quantity - (item.receivedQuantity || 0)),
            qualityStatus: 'passed',
            qualityNotes: '',
            expiryType: '',
            manufacturingDate: '',
            expiryDate: '',
            expiryPeriod: ''
        }));

        // --- 2. RENDER FUNCTIONS ---
        function renderItemsTable() {
            const tbody = document.getElementById('items-table-body');
            tbody.innerHTML = '';

            items.forEach((item, index) => {
                // Skip if item is completed
                if (item.is_completed) return;

                const remainingQty = Math.max(0, item.quantity - (item.receivedQuantity || 0));

                // Status Logic: Compare against remaining balance, not total quantity
                // If I am receiving the full remaining balance, it is NOT short.
                // It is short only if I receive LESS than the remaining balance.
                const isShort = item.actualReceived < remainingQty;

                // Complete means I am receiving exactly the remaining balance
                const isComplete = item.actualReceived == remainingQty;

                // Excess logic
                const isExcess = item.actualReceived > remainingQty;

                // Border color for received input
                let inputBorder = 'border-gray-200 focus:border-emerald-500';
                if (isShort) inputBorder = 'border-orange-300 focus:border-orange-500';
                if (isComplete) inputBorder = 'border-green-300 focus:border-green-500';

                // Badges
                let badgeHtml = '';
                if (isShort) badgeHtml = `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">Short</span>`;
                if (isComplete) badgeHtml = `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">Complete</span>`;

                // Quality Buttons Classes
                const getBtnClass = (status, type) => {
                    const isActive = item.qualityStatus === type;
                    if (type === 'passed') return isActive ? 'bg-green-100 text-green-700 ring-2 ring-green-500' : 'bg-gray-100 text-gray-400 hover:bg-green-50';
                    if (type === 'partial') return isActive ? 'bg-orange-100 text-orange-700 ring-2 ring-orange-500' : 'bg-gray-100 text-gray-400 hover:bg-orange-50';
                    if (type === 'failed') return isActive ? 'bg-red-100 text-red-700 ring-2 ring-red-500' : 'bg-gray-100 text-gray-400 hover:bg-red-50';
                };

                const html = `
                                                                <tr class="hover:bg-gray-50 transition-colors">
                                                                    <td class="px-5 py-4 text-start">
                                                                        <div class="font-medium text-gray-900">${item.productName}</div>
                                                                        <div class="text-sm text-gray-600 capitalize">${item.category}</div>
                                                                    </td>
                                                                    <td class="px-5 py-4 text-center">
                                                                        <div class="font-medium text-gray-900 text-lg">${item.quantity} <span class="text-sm text-gray-500">${item.unit}</span></div>
                                                                        ${item.receivedQuantity > 0 ? `
                                                                            <div class="mt-1 flex flex-col gap-1 items-center">
                                                                                 <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100" title="Previously Received">
                                                                                    Prev: ${item.receivedQuantity}
                                                                                </span>
                                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200" title="Remaining Balance">
                                                                                    Bal: ${Math.max(0, item.quantity - item.receivedQuantity)}
                                                                                </span>
                                                                            </div>
                                                                        ` : ''}
                                                                    </td>
                                                                    <td class="px-5 py-4 text-center">
                                                                        <div class="flex flex-col items-center justify-center gap-1">
                                                                        <div class="flex items-center justify-center gap-1">
                                                                            <button type="button" onclick="updateQty(${index}, -1)" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center">-</button>

                                                                            <input type="number"
                                                                                value="${item.actualReceived}"
                                                                                onchange="setQty(${index}, this.value)"
                                                                                class="w-20 h-8 text-center border-2 rounded-lg outline-none ${inputBorder}"
                                                                                min="0" max="${Math.max(0, item.quantity - (item.receivedQuantity || 0))}">

                                                                            <button type="button" onclick="updateQty(${index}, 1)" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center">+</button>

                                                                            <span class="text-sm text-gray-600 w-0">${item.unit}</span>
                                                                            </div>
                                                                            ${badgeHtml}
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 py-4 text-center">
                                                                        <input type="number" value="${item.costing_price}" onchange="setCostingPrice(${index}, this.value)" class="w-20 h-8 text-center border-2 rounded-lg outline-none ${inputBorder}" min="0" step="0.01">
                                                                    </td>
                                                                    <td class="px-5 py-4 text-center">
                                                                        <input type="number" value="${item.selling_price}" onchange="setSellingPrice(${index}, this.value)" class="w-20 h-8 text-center border-2 rounded-lg outline-none ${inputBorder}" min="0" step="0.01">
                                                                    </td>
                                                                    <td class="px-5 py-4 text-center">
                                                                        <div class="flex items-center justify-center gap-2">
                                                                            <button type="button" onclick="setQuality(${index}, 'passed')" class="w-6 h-6 rounded-lg flex items-center justify-center transition-all ${getBtnClass(item.qualityStatus, 'passed')}" title="Passed">
                                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                                            </button>
                                                                            <button type="button" onclick="setQuality(${index}, 'partial')" class="w-6 h-6 rounded-lg flex items-center justify-center transition-all ${getBtnClass(item.qualityStatus, 'partial')}" title="Partial Issues">
                                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                                            </button>
                                                                            <button type="button" onclick="setQuality(${index}, 'failed')" class="w-6 h-6 rounded-lg flex items-center justify-center transition-all ${getBtnClass(item.qualityStatus, 'failed')}" title="Failed">
                                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 py-4 text-center">
                                                                        <select onchange="setExpiryType(${index}, this.value)" class="w-full h-8 border-2 rounded-lg outline-none ${inputBorder} text-sm">
                                                                            <option value="">Select</option>
                                                                            <option value="1" ${item.expiryType == '1' ? 'selected' : ''}>Date</option>
                                                                            <option value="2" ${item.expiryType == '2' ? 'selected' : ''}>Period</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="px-5 py-4 text-center">
                                                                        ${item.expiryType == '1' ? `
                                                                            <div class="flex items-center gap-1">
                                                                                <input type="date" value="${item.manufacturingDate || ''}" onchange="setManufacturingDate(${index}, this.value)" class="w-24 h-8 text-center border-2 rounded-lg outline-none ${inputBorder} text-xs">
                                                                                <span class="text-xs text-gray-500">to</span>
                                                                                <input type="date" value="${item.expiryDate || ''}" onchange="setExpiryDate(${index}, this.value)" class="w-24 h-8 text-center border-2 rounded-lg outline-none ${inputBorder} text-xs">
                                                                            </div>
                                                                        ` : (item.expiryType == '2' ? `
                                                                            <div class="flex items-center gap-1">
                                                                                <input type="number" placeholder="Days" value="${item.expiryPeriod || ''}" onchange="setExpiryPeriod(${index}, this.value)" class="w-20 h-8 text-center border-2 rounded-lg outline-none ${inputBorder} text-sm">
                                                                            </div>
                                                                        ` : `
                                                                            <div class="text-xs text-gray-400 text-center">-</div>
                                                                        `)}
                                                                    </td>
                                                                    <td class="px-5 py-4 text-center">
                                                                        <input type="text"
                                                                            value="${item.qualityNotes}"
                                                                            onkeyup="setNotes(${index}, this.value)"
                                                                            placeholder="Quality notes..."
                                                                            class="w-full h-8 px-3 border-2 border-gray-200 rounded-lg outline-none focus:border-emerald-500 text-sm">
                                                                    </td>
                                                                </tr>
                                                            `;
                tbody.innerHTML += html;
            });

            renderCompletedItems(); // Call to render completed items
            calculateInvoiceTotal(); // Auto-calculate invoice total
            calculateOverallStatus();
        }

        function renderCompletedItems() {
            const tbody = document.getElementById('completed-items-table-body');
            const section = document.getElementById('completed-items-section');
            const sectionTitle = section.querySelector('h3'); // Get title element
            tbody.innerHTML = '';

            // Update Section Title
            sectionTitle.innerHTML = `
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Received Items History
                    `;

            // Filter items that have ANY received quantity (Complete or Partial)
            const receivedItems = items.filter(item => item.receivedQuantity > 0);

            if (receivedItems.length > 0) {
                section.style.display = 'block';
                receivedItems.forEach(item => {
                    // Badge Logic
                    let badgeClass = item.is_completed
                        ? 'bg-green-100 text-green-800'
                        : 'bg-orange-100 text-orange-800';
                    let badgeText = item.is_completed ? 'Completed' : 'Partial';
                    let badgeIcon = item.is_completed
                        ? '<circle cx="4" cy="4" r="3" />'
                        : '<path d="M4 8a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm0-1.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z"/>'; // Simple circle vs partial

                    // History Details Table
                    let historyHtml = '<div class="text-sm text-gray-500 italic">No history available</div>';
                    if (item.reception_history && item.reception_history.length > 0) {
                        historyHtml = `
                                    <table class="w-full text-xs text-left text-gray-600 border rounded-lg overflow-hidden">
                                        <thead class="bg-gray-100 font-medium text-gray-700">
                                            <tr>
                                                <th class="px-2 py-1">GRN #</th>
                                                <th class="px-2 py-1">Date</th>
                                                <th class="px-2 py-1 text-center">Qty</th>
                                                <th class="px-2 py-1 text-right">Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            ${item.reception_history.map(rec => `
                                                <tr>
                                                    <td class="px-2 py-1 font-medium text-emerald-600">${rec.grn_number}</td>
                                                    <td class="px-2 py-1">${rec.date}</td>
                                                    <td class="px-2 py-1 text-center font-medium">${rec.quantity}</td>
                                                    <td class="px-2 py-1 text-right">Rs ${Number(rec.price).toFixed(2)}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                `;
                    }

                    const html = `
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-4 text-start align-top">
                                        <div class="font-medium text-gray-900">${item.productName}</div>
                                        <div class="text-sm text-gray-600 capitalize">${item.category}</div>
                                    </td>
                                    <td class="px-5 py-4 text-center align-top">
                                        <span class="font-medium text-gray-900">${item.receivedQuantity}</span> / <span class="text-gray-500">${item.quantity} ${item.unit}</span>
                                    </td>
                                    <td class="px-5 py-4 text-center align-top">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeClass}">
                                            <svg class="mr-1.5 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                                                ${badgeIcon}
                                            </svg>
                                            ${badgeText}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-left align-top">
                                        ${historyHtml}
                                    </td>
                                </tr>
                            `;
                    tbody.innerHTML += html;
                });
            } else {
                section.style.display = 'none';
            }
        }

        // --- 3. STATE UPDATERS ---
        function updateQty(index, delta) {
            let newQty = items[index].actualReceived + delta;
            const item = items[index];
            const maxQty = Math.max(0, item.quantity - (item.receivedQuantity || 0));

            // Validation: Restrict to max balance
            if (newQty > maxQty) newQty = maxQty;
            if (newQty < 0) newQty = 0;

            items[index].actualReceived = newQty;
            renderItemsTable();
        }

        function setQty(index, value) {
            const item = items[index];
            const maxQty = Math.max(0, item.quantity - (item.receivedQuantity || 0));

            let qty = parseInt(value);
            // Validation: Restrict to max balance
            if (qty > maxQty) qty = maxQty;
            if (qty < 0) qty = 0;

            items[index].actualReceived = qty;
            renderItemsTable();
        }

        function setCostingPrice(index, value) {
            items[index].costing_price = parseFloat(value) || 0;
            calculateInvoiceTotal();
        }

        function setSellingPrice(index, value) {
            items[index].selling_price = parseFloat(value) || 0;
        }

        function setQuality(index, status) {
            items[index].qualityStatus = status;
            renderItemsTable();
        }

        function setExpiryType(index, value) {
            items[index].expiryType = value;
            renderItemsTable();
        }

        function setManufacturingDate(index, value) {
            items[index].manufacturingDate = value;
        }

        function setExpiryDate(index, value) {
            items[index].expiryDate = value;
        }

        function setExpiryPeriod(index, value) {
            items[index].expiryPeriod = value;
        }

        function setNotes(index, value) {
            items[index].qualityNotes = value;
            // No need to re-render table for notes, just update state
        }

        function calculateInvoiceTotal() {
            let total = 0;
            items.forEach(item => {
                if (!item.is_completed) {
                    total += (item.actualReceived * item.costing_price);
                }
            });

            const input = document.getElementById('invoice-amount');
            input.value = total.toFixed(2);
        }

        // Removed validateInvoiceAmount as it is now auto-calculated
        function validateInvoiceAmount() {
            return false;
        }

        function calculateOverallStatus() {
            const panel = document.getElementById('overall-status-panel');
            const iconContainer = document.getElementById('status-icon');
            const title = document.getElementById('status-title');
            const discrepancyList = document.getElementById('discrepancies-list'); // Fixed variable name
            const discrepancies = [];

            // 1. Calculate Status
            let status = 'passed';
            if (items.some(i => i.qualityStatus === 'failed')) status = 'failed';
            else if (items.some(i => i.qualityStatus === 'partial')) status = 'partial';
            else if (items.every(i => i.qualityStatus === 'passed')) status = 'passed';
            else status = 'pending';

            // 2. Collect Discrepancies
            items.forEach(item => {
                if (item.actualReceived < item.quantity) {
                    discrepancies.push(`${item.productName}: Ordered ${item.quantity} ${item.unit}, received ${item.actualReceived} ${item.unit}`);
                }
                if (item.qualityStatus === 'failed') discrepancies.push(`${item.productName}: Quality check failed`);
                if (item.qualityStatus === 'partial') discrepancies.push(`${item.productName}: Partial quality issues`);
            });

            // Invoice Mismatch Discrepancy (Updated to use calculated total)
            /*
            if (validateInvoiceAmount()) {
                const val = parseFloat(document.getElementById('invoice-amount').value) || 0;
                discrepancies.push(`Invoice mismatch with original PO`);
            }
            */

            // 3. Update UI based on Status
            panel.className = `rounded-xl p-4 border-2 transition-colors duration-300`;
            if (status === 'passed') panel.classList.add('bg-green-50', 'border-green-200');
            if (status === 'failed') panel.classList.add('bg-red-50', 'border-red-200');
            if (status === 'partial') panel.classList.add('bg-orange-50', 'border-orange-200');

            let textColor = status === 'passed' ? 'text-green-900' : (status === 'failed' ? 'text-red-900' : 'text-orange-900');
            title.className = `font-medium mb-1 ${textColor}`;
            title.innerText = `Overall Quality Status: ${status.toUpperCase()}`;

            // Icon
            let iconHtml = '';
            if (status === 'passed') iconHtml = `<svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
            else if (status === 'failed') iconHtml = `<svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
            else iconHtml = `<svg class="w-6 h-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`;
            iconContainer.innerHTML = iconHtml;

            // Discrepancies List
            if (discrepancies.length > 0) {
                discrepancyList.classList.remove('hidden'); // Corrected variable usage
                let listColor = status === 'passed' ? 'text-green-800' : (status === 'failed' ? 'text-red-800' : 'text-orange-800');
                discrepancyList.innerHTML = discrepancies.map(d => `<div class="text-sm ${listColor}">• ${d}</div>`).join('');
            } else {
                discrepancyList.classList.add('hidden');
            }

            // Update hidden inputs for submission
            document.getElementById('input-items-json').value = JSON.stringify(items);
            document.getElementById('input-overall-status').value = status;

            validateForm();
        }

        function validateForm() {
            const invoice = document.getElementById('invoice-number').value.trim();
            const btn = document.getElementById('btn-submit');

            if (invoice) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        // Initialize View
        renderItemsTable();

        // --- 4. AJAX SUBMISSION ---
        document.getElementById('create-grn-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('btn-submit');
            const originalBtnText = submitBtn.innerHTML;

            // Add the items array and overall status to the form data
            formData.set('items_json', JSON.stringify(items));
            // Recalculate status right before submission to ensure it's up-to-date
            calculateOverallStatus(); // This will update the internal status logic
            const overallStatus = document.getElementById('status-title').innerText.split(': ')[1].toLowerCase();
            formData.append('overall_status', overallStatus);


            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Processing...
                                        `;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) throw response;
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message || 'GRN Created Successfully',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#10B981'
                        }).then(() => {
                            window.location.href = "{{ route('purchaseOrderManage.index') }}";
                        });
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    let errorMessage = 'Something went wrong. Please try again.';
                    if (err instanceof Response) {
                        err.json().then(errorData => {
                            if (errorData.errors) {
                                // Validation errors
                                errorMessage = Object.values(errorData.errors).flat().join('\n');
                            } else {
                                errorMessage = errorData.message || errorMessage;
                            }
                            Swal.fire('Error', errorMessage, 'error');
                        });
                    } else {
                        Swal.fire('Error', err.message || errorMessage, 'error');
                    }
                });
        });
    </script>
    @endsection
@endif
```