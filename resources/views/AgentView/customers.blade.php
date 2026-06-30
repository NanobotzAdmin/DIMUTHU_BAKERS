@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Customers</h1>
            <p class="text-sm text-gray-500">Manage route customers, view invoices, returns, and collect payments.</p>
        </div>
        <a href="{{ route('agent-panel.dashboard') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Search & Route Filters -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <div class="w-full sm:w-72 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" id="customerSearch" oninput="filterCustomers()" placeholder="Search customer name or phone..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
        </div>
        <div class="w-full sm:w-64 flex items-center gap-2">
            <span class="text-xs font-semibold text-gray-500 uppercase">Route:</span>
            <select id="routeFilter" onchange="filterCustomers()" class="w-full py-2 px-3 border border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none bg-white">
                <option value="">All Routes</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}">{{ $route->route_name }} ({{ $route->route_code }})</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($customers as $customer)
            @php
                $type = $customer->businessDetails->b2b_customer_type ?? $customer->customer_type;
                $typeLabel = 'Retailer';
                if ($type == 2) {
                    $typeLabel = 'Wholesaler';
                } elseif ($type == 3) {
                    $typeLabel = 'Distributor';
                } elseif (is_string($type) && strlen($type) > 1) {
                    $typeLabel = $type;
                }
                $bizName = $customer->businessDetails->business_name ?? $customer->name;
            @endphp
            <div class="customer-card bg-white border border-gray-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between"
                 data-route-id="{{ $customer->businessDetails->route_id ?? '' }}"
                 data-name="{{ strtolower($customer->name) }} {{ strtolower($customer->businessDetails->business_name ?? '') }}"
                 data-phone="{{ $customer->businessDetails->contact_person_phone ?? $customer->phone ?? '' }}">
                
                <div>
                    <!-- Card Top Header -->
                    <div class="flex gap-4 items-start mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-sm shrink-0 uppercase">
                            {{ substr($bizName, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-1">
                                <h3 class="text-base font-bold text-gray-900 truncate" title="{{ $bizName }}">
                                    {{ $bizName }}
                                </h3>
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-bold rounded-full uppercase tracking-wider shrink-0">
                                    Active
                                </span>
                            </div>
                            @if($customer->businessDetails && $customer->businessDetails->business_name)
                                <p class="text-xs text-gray-400 mt-0.5 truncate">
                                    <span class="font-medium text-gray-500">Owner:</span> {{ $customer->name }}
                                </p>
                            @endif
                            <span class="inline-block px-2 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded text-[10px] font-bold uppercase tracking-wider mt-1.5">
                                {{ $typeLabel }}
                            </span>
                        </div>
                    </div>

                    <!-- Contact & Address Body -->
                    <div class="space-y-2.5 text-sm text-gray-600 border-t border-slate-50 pt-4">
                        @if($customer->businessDetails->contact_person_phone ?? $customer->phone)
                            <a href="tel:{{ $customer->businessDetails->contact_person_phone ?? $customer->phone }}" class="flex items-center gap-2.5 hover:text-indigo-600 transition-colors group no-underline">
                                <span class="w-7 h-7 bg-slate-50 group-hover:bg-indigo-50 text-slate-400 group-hover:text-indigo-600 rounded-lg flex items-center justify-center transition-colors shrink-0">
                                    <i class="bi bi-telephone text-xs"></i>
                                </span>
                                <span class="text-xs font-semibold text-slate-600 group-hover:text-indigo-600 transition-colors">{{ $customer->businessDetails->contact_person_phone ?? $customer->phone }}</span>
                            </a>
                        @else
                            <div class="flex items-center gap-2.5 text-slate-400">
                                <span class="w-7 h-7 bg-slate-50 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="bi bi-telephone text-xs"></i>
                                </span>
                                <span class="text-xs italic">No phone number</span>
                            </div>
                        @endif

                        <div class="flex items-start gap-2.5">
                            <span class="w-7 h-7 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center shrink-0">
                                <i class="bi bi-geo-alt text-xs"></i>
                            </span>
                            <span class="text-xs text-slate-500 line-clamp-2 mt-1 leading-relaxed" title="{{ $customer->businessDetails->address ?? $customer->address ?? 'No Address' }}">
                                {{ $customer->businessDetails->address ?? $customer->address ?? 'No Address' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Button -->
                <div class="mt-6 pt-4 border-t border-slate-50">
                    <button onclick="openCustomerDetails({{ $customer->id }})" class="w-full px-4 py-2.5 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white text-xs font-bold rounded-xl transition-all duration-200 flex items-center justify-center gap-2 border border-indigo-100 hover:border-indigo-600 shadow-sm cursor-pointer">
                        <i class="bi bi-person-badge"></i> View Customer Profile
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-gray-100 rounded-2xl p-12 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <p class="mt-4 font-bold text-gray-700">No Customers Assigned</p>
                <p class="text-xs mt-1">Contact supervisor to assign customers to your route.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Reusable Customer Details Modal -->
<div id="customerDetailsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <!-- Overlay backdrop -->
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeCustomerDetailsModal()"></div>

        <!-- Trick browser to center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full w-full">
            
            <!-- Loading Spinner Overlay -->
            <div id="modalSpinner" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center gap-3">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-600 border-t-transparent"></div>
                <p class="text-sm font-semibold text-slate-600">Loading customer details...</p>
            </div>

            <!-- Error State -->
            <div id="modalError" class="absolute inset-0 bg-white z-50 flex flex-col items-center justify-center gap-4 hidden">
                <i class="bi bi-exclamation-triangle-fill text-red-500 text-4xl"></i>
                <p class="text-sm font-semibold text-slate-600" id="modalErrorMessage">Failed to load customer details.</p>
                <button type="button" onclick="closeCustomerDetailsModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-sm transition-colors">Close</button>
            </div>

            <!-- Header with BG Image and Gradient -->
            <div class="relative h-48 bg-slate-200">
                <img id="detailBgImage" src="https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?w=800&h=600&fit=crop" alt="" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?w=800&h=600&fit=crop'">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                <button type="button" onclick="closeCustomerDetailsModal()" class="absolute top-4 right-4 bg-black/40 hover:bg-black/60 text-white p-2 rounded-full transition-colors">
                    <i class="bi bi-x-lg text-lg leading-none"></i>
                </button>
            </div>

            <!-- Profile Info Overlay -->
            <div class="relative px-6 pb-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 -mt-16 mb-6">
                    <img id="detailProfileImage" src="https://images.unsplash.com/photo-155774698-0b77e0d5fac6?w=200&h=200&fit=crop" alt="" class="w-24 h-24 rounded-2xl border-4 border-white object-cover bg-white shadow-md" onerror="this.src='https://images.unsplash.com/photo-155774698-0b77e0d5fac6?w=200&h=200&fit=crop'">
                    <div class="flex-1 bg-white/95 backdrop-blur-md px-5 py-4 rounded-2xl border border-slate-100 shadow-lg w-full">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 id="detailBusinessName" class="text-xl font-bold text-slate-900">Business Name</h3>
                            <span id="detailRating" class="flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-600 border border-amber-100 text-xs font-bold rounded">
                                <i class="bi bi-star-fill text-[10px]"></i> 4.8
                            </span>
                        </div>
                        <p id="detailSubtitle" class="text-sm text-slate-600 mt-1">Type • Since 2025</p>
                        <p id="detailOwnerName" class="text-xs font-semibold text-indigo-600 mt-0.5">Owner: Name</p>
                        <p id="detailContactPerson" class="text-xs text-slate-500 mt-0.5">Contact Person</p>
                    </div>
                    <div class="flex gap-2 self-end sm:self-auto">
                        <a id="detailPhoneBtn" href="" class="p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-all shadow-sm flex items-center justify-center">
                            <i class="bi bi-telephone-fill text-lg leading-none"></i>
                        </a>
                        <a id="detailSmsBtn" href="" class="p-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-all flex items-center justify-center">
                            <i class="bi bi-chat-dots-fill text-lg leading-none"></i>
                        </a>
                    </div>
                </div>

                <!-- Main Content (Scrollable Grid Layout) -->
                <div class="max-h-[60vh] overflow-y-auto pr-1 space-y-6">
                    <!-- Top section (Full Width) -->
                    <div class="space-y-6">
                        <!-- Address & Map Directions -->
                        <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-geo-alt-fill text-slate-400 text-xl mt-0.5"></i>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Address</h4>
                                    <p id="detailAddress" class="text-sm text-gray-700">Address text</p>
                                </div>
                            </div>
                            <a id="detailDirectionsBtn" href="" target="_blank" class="w-full sm:w-auto px-4 py-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 text-emerald-700 text-xs font-bold rounded-lg transition-colors flex items-center justify-center gap-1.5 whitespace-nowrap">
                                <i class="bi bi-compass"></i> Directions
                            </a>
                        </div>

                        <!-- Financials -->
                        <div class="bg-amber-50/30 p-5 rounded-xl border border-amber-100/50">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                                <div>
                                    <h4 class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-1">Outstanding Balance</h4>
                                    <span id="detailOutstanding" class="text-3xl font-extrabold text-amber-900">Rs. 0</span>
                                </div>
                                <div id="detailCollectPaymentContainer" class="hidden">
                                    <a id="detailCollectPaymentBtn" href="" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                                        <i class="bi bi-cash-coin"></i> Collect Payment
                                    </a>
                                </div>
                            </div>
                            <div class="w-full bg-amber-100/50 rounded-full h-2.5 overflow-hidden mb-2">
                                <div id="detailCreditProgressBar" class="bg-amber-600 h-full transition-all duration-500" style="width: 0%;"></div>
                            </div>
                            <p id="detailCreditLimitText" class="text-xs text-amber-700">0% of Rs. 0 credit limit used</p>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="bg-white p-4 rounded-xl border border-slate-100 flex flex-col gap-1 shadow-sm">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                    <i class="bi bi-bag-fill"></i>
                                </div>
                                <span id="statTotalOrders" class="text-lg font-bold text-slate-800">0</span>
                                <span class="text-xs text-slate-400 font-medium">Total Orders</span>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-slate-100 flex flex-col gap-1 shadow-sm">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                    <i class="bi bi-currency-rupee text-base"></i>
                                </div>
                                <span id="statAvgOrder" class="text-lg font-bold text-slate-800">Rs. 0</span>
                                <span class="text-xs text-slate-400 font-medium">Avg. Order</span>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-slate-100 flex flex-col gap-1 shadow-sm">
                                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <span id="statLastOrder" class="text-lg font-bold text-slate-800 truncate" style="max-width: 100%;">N/A</span>
                                <span class="text-xs text-slate-400 font-medium">Last Order</span>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-slate-100 flex flex-col gap-1 shadow-sm">
                                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </div>
                                <span id="statReturnRate" class="text-lg font-bold text-slate-800">0%</span>
                                <span class="text-xs text-slate-400 font-medium">Returns</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom section (Two Columns: Left for Orders, Right for Payments) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                        <!-- Bottom Left: Recent Orders -->
                        <div>
                            <h4 class="font-bold text-slate-900 mb-3 flex items-center gap-2 text-sm uppercase tracking-wider text-slate-500">
                                <i class="bi bi-box-seam"></i> Recent Orders
                            </h4>
                            <div id="detailRecentOrdersList" class="space-y-2.5 max-h-[30vh] overflow-y-auto pr-1">
                                <!-- Injected dynamically -->
                            </div>
                        </div>

                        <!-- Bottom Right: Recent Payments -->
                        <div>
                            <h4 class="font-bold text-slate-900 mb-3 flex items-center gap-2 text-sm uppercase tracking-wider text-slate-500">
                                <i class="bi bi-cash-stack"></i> Recent Payments
                            </h4>
                            <div id="detailRecentPaymentsList" class="space-y-2.5 max-h-[30vh] overflow-y-auto pr-1">
                                <!-- Injected dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
</div>

<!-- Order Detail Sub-modal -->
<div id="orderDetailSubModal" class="fixed inset-0 z-[60] overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeOrderDetailSubModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full w-full">
            <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-gray-900">Order Details</h3>
                        <span id="subModalPaymentStatusBadge"></span>
                    </div>
                    <p id="subModalInvoiceNumber" class="text-xs text-slate-500 mt-0.5">#INV-000</p>
                </div>
                <button type="button" onclick="closeOrderDetailSubModal()" class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 rounded-lg">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>
            
            <div id="subModalOrderSpinner" class="px-6 py-12 flex flex-col items-center justify-center gap-2">
                <div class="animate-spin rounded-full h-8 w-8 border-2 border-indigo-600 border-t-transparent"></div>
                <p class="text-xs text-slate-400">Loading order items...</p>
            </div>

            <div id="subModalOrderContent" class="px-6 py-6 max-h-[50vh] overflow-y-auto space-y-6 hidden">
                <!-- Ordered Items -->
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ordered Items</h4>
                    <div id="subModalOrderItems" class="divide-y divide-slate-100">
                        <!-- Injected -->
                    </div>
                </div>
                <!-- Returned Items -->
                <div id="subModalReturnItemsContainer" class="hidden">
                    <h4 class="text-xs font-bold text-red-500 uppercase tracking-wider mb-2">Returned Items</h4>
                    <div id="subModalReturnItems" class="divide-y divide-slate-100 bg-red-50/20 rounded-xl p-3 border border-red-50">
                        <!-- Injected -->
                    </div>
                </div>
                <!-- Breakdown -->
                <div class="border-t border-slate-100 pt-4 space-y-2 text-sm">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal</span>
                        <span id="subModalSubtotal">Rs. 0</span>
                    </div>
                    <div id="subModalReturnsRow" class="flex justify-between text-red-600 hidden">
                        <span>Returns Deducted</span>
                        <span id="subModalReturns">Rs. 0</span>
                    </div>
                    <div class="flex justify-between font-bold text-slate-800 text-lg border-t border-slate-100/50 pt-2">
                        <span>Net Total</span>
                        <span id="subModalNetTotal">Rs. 0</span>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-4 border-t border-gray-100 flex gap-2 justify-end">
                <button type="button" onclick="closeOrderDetailSubModal()" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Detail Sub-modal -->
<div id="paymentDetailSubModal" class="fixed inset-0 z-[60] overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closePaymentDetailSubModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full w-full">
            <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Payment Details</h3>
                    <p id="subModalReceiptNumber" class="text-xs text-slate-500 mt-0.5">Receipt: #000</p>
                </div>
                <button type="button" onclick="closePaymentDetailSubModal()" class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 rounded-lg">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>
            <div class="px-6 py-6 space-y-4">
                <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Date:</span>
                        <span id="subModalPaymentDate" class="font-semibold text-slate-800">Date</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Payment Type:</span>
                        <span id="subModalPaymentType" class="font-semibold text-slate-800">Cash</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold">
                        <span class="text-slate-500">Total Paid:</span>
                        <span id="subModalPaymentAmount" class="text-emerald-600">Rs. 0</span>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Applied Invoices</h4>
                    <div id="subModalPaymentInvoices" class="divide-y divide-slate-100 text-sm">
                        <!-- Injected -->
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-4 border-t border-gray-100 flex justify-end">
                <button type="button" onclick="closePaymentDetailSubModal()" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Embed customer data securely into page
    const customersData = @json($customers);
    let currentFetchedCustomerData = null; // Store fetched details for submodals

    function openCustomerDetails(customerId) {
        // Find customer
        const customer = customersData.find(c => c.id === customerId);
        if (!customer) return;

        // Show Modal & Spinner
        const modal = document.getElementById('customerDetailsModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('modalSpinner').classList.remove('hidden');
        document.getElementById('modalError').classList.add('hidden');

        // Business details relation
        const biz = customer.business_details;
        if (!biz) {
            // No B2B business profile
            document.getElementById('modalSpinner').classList.add('hidden');
            document.getElementById('modalErrorMessage').textContent = 'No B2B business profile assigned to this customer.';
            document.getElementById('modalError').classList.remove('hidden');
            return;
        }

        // Fetch details from JSON endpoint
        $.getJSON(`/agent-panel/customers/${biz.id}/detail`)
            .done(function(response) {
                if (response.status && response.data) {
                    const data = response.data;
                    currentFetchedCustomerData = data;

                    // Populate fields
                    document.getElementById('detailBusinessName').textContent = data.business_name || data.name || 'N/A';
                    document.getElementById('detailOwnerName').textContent = data.name ? 'Owner: ' + data.name : '';
                    document.getElementById('detailSubtitle').textContent = (data.type || 'N/A') + ' • Since ' + (data.since || '2025');
                    document.getElementById('detailProfileImage').src = data.image || 'https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?w=400&h=400&fit=crop';
                    document.getElementById('detailBgImage').src = 'https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?w=800&h=600&fit=crop';
                    document.getElementById('detailContactPerson').textContent = data.contact_person_name ? `${data.contact_person_name} • ${data.phone || 'No phone'}` : (data.phone || 'No contact info');
                    
                    // Buttons
                    document.getElementById('detailPhoneBtn').href = data.phone ? `tel:${data.phone}` : '#';
                    document.getElementById('detailSmsBtn').href = data.phone ? `sms:${data.phone}` : '#';
                    
                    // Address
                    document.getElementById('detailAddress').textContent = data.address || 'N/A';
                    if (data.latitude && data.longitude) {
                        document.getElementById('detailDirectionsBtn').href = `https://www.google.com/maps/dir/?api=1&destination=${data.latitude},${data.longitude}`;
                        document.getElementById('detailDirectionsBtn').classList.remove('hidden');
                    } else {
                        document.getElementById('detailDirectionsBtn').classList.add('hidden');
                    }

                    // Outstanding & Credit Limit
                    document.getElementById('detailOutstanding').textContent = 'Rs. ' + (data.outstanding || 0).toLocaleString();
                    
                    // Collect Payment button
                    if (data.is_assigned_to_active_load) {
                        document.getElementById('detailCollectPaymentContainer').classList.remove('hidden');
                        document.getElementById('detailCollectPaymentBtn').href = `/agent-panel/payments`;
                    } else {
                        document.getElementById('detailCollectPaymentContainer').classList.add('hidden');
                    }

                    const creditLimit = data.creditLimit || 1;
                    const outstanding = data.outstanding || 0;
                    const pct = Math.min(Math.round((outstanding / creditLimit) * 100), 100);
                    document.getElementById('detailCreditProgressBar').style.width = pct + '%';
                    document.getElementById('detailCreditLimitText').textContent = pct + '% of Rs. ' + (data.creditLimit || 0).toLocaleString() + ' limit used';

                    // Stats
                    document.getElementById('statTotalOrders').textContent = data.stats.totalOrders || '0';
                    document.getElementById('statAvgOrder').textContent = 'Rs. ' + Math.round(data.stats.avgOrderValue || 0).toLocaleString();
                    document.getElementById('statLastOrder').textContent = data.stats.lastOrder || 'N/A';
                    document.getElementById('statReturnRate').textContent = data.stats.returnRate || '0%';

                    // Recent Orders List
                    const ordersList = document.getElementById('detailRecentOrdersList');
                    ordersList.innerHTML = '';
                    if (data.recentOrders && data.recentOrders.length > 0) {
                        data.recentOrders.forEach(order => {
                            let statusBadge = '';
                            if (order.payment_status === 2) {
                                statusBadge = `<span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-semibold rounded">Paid</span>`;
                            } else if (order.payment_status === 1) {
                                statusBadge = `<span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-100 text-xs font-semibold rounded">Partial</span>`;
                            } else {
                                statusBadge = `<span class="px-2 py-0.5 bg-red-50 text-red-700 border border-red-100 text-xs font-semibold rounded">Unpaid</span>`;
                            }

                            const orderCard = `
                                <div onclick="openOrderDetailSubModal(${order.id})" class="p-3 bg-white hover:bg-slate-50 border border-slate-100 rounded-xl transition-all shadow-sm flex items-center justify-between cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-500">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-semibold text-slate-800">${order.invoice_number || order.id}</h5>
                                            <p class="text-xs text-slate-400 mt-0.5">${order.date} • ${order.items} Items</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-bold text-slate-800">Rs. ${(order.total || 0).toLocaleString()}</span>
                                        <div class="flex items-center gap-1.5 mt-0.5 justify-end">
                                            ${statusBadge}
                                            <i class="bi bi-chevron-right text-slate-300 text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            `;
                            ordersList.insertAdjacentHTML('beforeend', orderCard);
                        });
                    } else {
                        ordersList.innerHTML = `<p class="text-xs text-slate-400 italic py-2">No recent orders found</p>`;
                    }

                    // Recent Payments List
                    const paymentsList = document.getElementById('detailRecentPaymentsList');
                    paymentsList.innerHTML = '';
                    if (data.recentPayments && data.recentPayments.length > 0) {
                        data.recentPayments.forEach(payment => {
                            const paymentCard = `
                                <div onclick="openPaymentDetailSubModal(${payment.id})" class="p-3 bg-white hover:bg-slate-50 border border-slate-100 rounded-xl transition-all shadow-sm flex items-center justify-between cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-semibold text-slate-800">${payment.receipt_number || 'Receipt'}</h5>
                                            <p class="text-xs text-slate-400 mt-0.5">${payment.date} • ${payment.type}</p>
                                        </div>
                                    </div>
                                    <div class="text-right flex items-center gap-1.5">
                                        <span class="text-sm font-bold text-emerald-600">Rs. ${(payment.amount || 0).toLocaleString()}</span>
                                        <i class="bi bi-chevron-right text-slate-300 text-xs"></i>
                                    </div>
                                </div>
                            `;
                            paymentsList.insertAdjacentHTML('beforeend', paymentCard);
                        });
                    } else {
                        paymentsList.innerHTML = `<p class="text-xs text-slate-400 italic py-2">No recent payments found</p>`;
                    }

                    // Hide Spinner
                    document.getElementById('modalSpinner').classList.add('hidden');
                } else {
                    showErrorState(response.message || 'Failed to load details.');
                }
            })
            .fail(function() {
                showErrorState('Network/server error while fetching details.');
            });
    }

    function showErrorState(msg) {
        document.getElementById('modalSpinner').classList.add('hidden');
        document.getElementById('modalErrorMessage').textContent = msg;
        document.getElementById('modalError').classList.remove('hidden');
    }

    function closeCustomerDetailsModal() {
        const modal = document.getElementById('customerDetailsModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Submodals Logic
    function openOrderDetailSubModal(orderId) {
        if (!currentFetchedCustomerData) return;
        const order = currentFetchedCustomerData.recentOrders.find(o => o.id === orderId);
        if (!order) return;

        // Open submodal and show spinner
        document.getElementById('orderDetailSubModal').classList.remove('hidden');
        document.getElementById('subModalOrderSpinner').classList.remove('hidden');
        document.getElementById('subModalOrderContent').classList.add('hidden');

        document.getElementById('subModalInvoiceNumber').textContent = order.invoice_number || 'Internal ID: ' + order.id;

        // Display payment status badge
        let statusBadge = '';
        if (order.payment_status === 2) {
            statusBadge = `<span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-semibold rounded">Paid</span>`;
        } else if (order.payment_status === 1) {
            statusBadge = `<span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-100 text-xs font-semibold rounded">Partially Paid</span>`;
        } else {
            statusBadge = `<span class="px-2 py-0.5 bg-red-50 text-red-700 border border-red-100 text-xs font-semibold rounded">Unpaid</span>`;
        }
        document.getElementById('subModalPaymentStatusBadge').innerHTML = statusBadge;

        // Fetch invoice items
        $.getJSON(`/agent-panel/invoices/${order.id}/items`)
            .done(function(response) {
                if (response.status) {
                    // Ordered Items
                    const orderItemsList = document.getElementById('subModalOrderItems');
                    orderItemsList.innerHTML = '';
                    if (response.items && response.items.length > 0) {
                        response.items.forEach(item => {
                            const qty = item.quantity || 0;
                            const unitPrice = parseFloat(item.unit_price || 0);
                            const total = qty * unitPrice;
                            const itemRow = `
                                <div class="py-2.5 flex justify-between text-sm">
                                    <div>
                                        <p class="font-semibold text-slate-700">${item.product_item ? item.product_item.product_name : 'Product Item'}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">${qty} × Rs. ${unitPrice.toLocaleString()}</p>
                                    </div>
                                    <span class="font-semibold text-slate-800">Rs. ${total.toLocaleString()}</span>
                                </div>
                            `;
                            orderItemsList.insertAdjacentHTML('beforeend', itemRow);
                        });
                    } else {
                        orderItemsList.innerHTML = `<p class="text-xs text-slate-400 italic py-2">No items found</p>`;
                    }

                    // Returned Items
                    const returnItemsContainer = document.getElementById('subModalReturnItemsContainer');
                    const returnItemsList = document.getElementById('subModalReturnItems');
                    returnItemsList.innerHTML = '';
                    if (response.return_items && response.return_items.length > 0) {
                        returnItemsContainer.classList.remove('hidden');
                        response.return_items.forEach(rItem => {
                            const returnRow = `
                                <div class="py-2.5 flex justify-between text-sm">
                                    <div>
                                        <p class="font-semibold text-red-700">${rItem.product ? rItem.product.product_name : 'Returned Product'}</p>
                                        <p class="text-xs text-red-400 mt-0.5">${rItem.return_quantity} × Rs. ${rItem.unit_price.toLocaleString()}</p>
                                        <p class="text-[11px] text-slate-400 italic mt-0.5">Reason: ${rItem.reason || 'N/A'}</p>
                                    </div>
                                    <span class="font-semibold text-red-700">-Rs. ${rItem.total_price.toLocaleString()}</span>
                                </div>
                            `;
                            returnItemsList.insertAdjacentHTML('beforeend', returnRow);
                        });
                    } else {
                        returnItemsContainer.classList.add('hidden');
                    }

                    // Totals
                    document.getElementById('subModalSubtotal').textContent = 'Rs. ' + (order.invoice_price || order.total || 0).toLocaleString();
                    if (order.return_price > 0) {
                        document.getElementById('subModalReturnsRow').classList.remove('hidden');
                        document.getElementById('subModalReturns').textContent = '-Rs. ' + (order.return_price || 0).toLocaleString();
                    } else {
                        document.getElementById('subModalReturnsRow').classList.add('hidden');
                    }
                    document.getElementById('subModalNetTotal').textContent = 'Rs. ' + (order.net_price || 0).toLocaleString();

                    // Show content
                    document.getElementById('subModalOrderSpinner').classList.add('hidden');
                    document.getElementById('subModalOrderContent').classList.remove('hidden');
                }
            })
            .fail(function() {
                alert('Failed to fetch invoice details.');
                closeOrderDetailSubModal();
            });
    }

    function closeOrderDetailSubModal() {
        document.getElementById('orderDetailSubModal').classList.add('hidden');
    }

    function openPaymentDetailSubModal(paymentId) {
        if (!currentFetchedCustomerData) return;
        const payment = currentFetchedCustomerData.recentPayments.find(p => p.id === paymentId);
        if (!payment) return;

        document.getElementById('paymentDetailSubModal').classList.remove('hidden');
        document.getElementById('subModalReceiptNumber').textContent = 'Receipt: ' + (payment.receipt_number || 'N/A');
        document.getElementById('subModalPaymentDate').textContent = payment.date || '-';
        document.getElementById('subModalPaymentType').textContent = payment.type || '-';
        document.getElementById('subModalPaymentAmount').textContent = 'Rs. ' + (payment.amount || 0).toLocaleString();

        const invoicesList = document.getElementById('subModalPaymentInvoices');
        invoicesList.innerHTML = '';
        if (payment.details && payment.details.length > 0) {
            payment.details.forEach(detail => {
                const row = `
                    <div class="py-2 flex justify-between">
                        <span class="text-slate-600">Invoice: ${detail.invoice_number}</span>
                        <span class="font-semibold text-slate-800">Rs. ${(detail.applied_amount || 0).toLocaleString()}</span>
                    </div>
                `;
                invoicesList.insertAdjacentHTML('beforeend', row);
            });
        } else {
            invoicesList.innerHTML = `<p class="text-xs text-slate-400 italic py-2">No invoices linked</p>`;
        }
    }

    // Close Payment Modal
    function closePaymentDetailSubModal() {
        document.getElementById('paymentDetailSubModal').classList.add('hidden');
    }

    // Close on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCustomerDetailsModal();
            closeOrderDetailSubModal();
            closePaymentDetailSubModal();
        }
    });

    function filterCustomers() {
        const searchVal = document.getElementById('customerSearch').value.toLowerCase().trim();
        const routeVal = document.getElementById('routeFilter').value;
        const cards = document.querySelectorAll('.customer-card');

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const phone = card.getAttribute('data-phone') || '';
            const routeId = card.getAttribute('data-route-id') || '';

            const matchesSearch = name.includes(searchVal) || phone.includes(searchVal);
            const matchesRoute = !routeVal || routeId === routeVal;

            if (matchesSearch && matchesRoute) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    }
</script>
@endsection
