@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order Requests</h1>
            <p class="text-sm text-gray-500">Track and request stock load allocations from the bakery.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openCreateOrderDrawer()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition-colors cursor-pointer shadow-sm">
                + Create Order Request
            </button>
            <a href="{{ route('agent-panel.dashboard') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                ← Back
            </a>
        </div>
    </div>

    <!-- Stats Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Main Stats Card (Spans 2 columns) -->
        <div class="lg:col-span-2 bg-slate-900 text-white p-6 rounded-2xl shadow-md flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Orders Value</span>
                <h2 class="text-3xl font-black mt-1">Rs. {{ number_format($stats['total_value'] ?? 0, 2) }}</h2>
            </div>
            <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4 relative z-10">
                <span class="text-xs text-slate-400 font-semibold">{{ $orders->total() }} Total Order Requests</span>
                <span class="px-2.5 py-0.5 bg-white/10 rounded-full text-[10px] font-bold uppercase tracking-wider text-slate-300">Active History</span>
            </div>
            <!-- Decorative icon background -->
            <div class="absolute right-0 bottom-0 translate-x-6 translate-y-6 opacity-[0.03] pointer-events-none">
                <i class="bi bi-cart-check text-[150px]"></i>
            </div>
        </div>

        <!-- Pending Card -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-clock-history"></i>
                </span>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending Approval</span>
                    <h4 class="text-lg font-extrabold text-slate-900 mt-0.5">Rs. {{ number_format($stats['pending_value'] ?? 0, 2) }}</h4>
                </div>
            </div>
            <span class="text-xs text-slate-400 font-medium mt-4">{{ $stats['pending_count'] ?? 0 }} Requests pending</span>
        </div>

        <!-- Completed Card -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-check-circle"></i>
                </span>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Completed / Settled</span>
                    <h4 class="text-lg font-extrabold text-slate-900 mt-0.5">Rs. {{ number_format($stats['completed_value'] ?? 0, 2) }}</h4>
                </div>
            </div>
            <span class="text-xs text-slate-400 font-medium mt-4">{{ $stats['completed_count'] ?? 0 }} Requests completed</span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-3 border border-slate-100 rounded-2xl shadow-sm">
        <div class="flex flex-wrap items-center gap-1.5 w-full sm:w-auto">
            @php
                $activeStatus = request('status', 'all');
            @endphp
            <a href="{{ route('agent-panel.orders', ['status' => 'all', 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all no-underline {{ $activeStatus === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100' }}">
                All Requests
            </a>
            <a href="{{ route('agent-panel.orders', ['status' => 'pending', 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all no-underline {{ $activeStatus === 'pending' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100' }}">
                Pending
            </a>
            <a href="{{ route('agent-panel.orders', ['status' => '1', 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all no-underline {{ $activeStatus === '1' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100' }}">
                Approved
            </a>
            <a href="{{ route('agent-panel.orders', ['status' => '5', 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all no-underline {{ $activeStatus === '5' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100' }}">
                Dispatched
            </a>
            <a href="{{ route('agent-panel.orders', ['status' => '7', 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all no-underline {{ $activeStatus === '7' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100' }}">
                Completed
            </a>
            <a href="{{ route('agent-panel.orders', ['status' => '2', 'search' => request('search')]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all no-underline {{ $activeStatus === '2' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100' }}">
                Rejected
            </a>
        </div>
        
        <form method="GET" action="{{ route('agent-panel.orders') }}" class="relative w-full sm:w-64">
            <input type="hidden" name="status" value="{{ request('status', 'all') }}">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search request number..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
        </form>
    </div>

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/75">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order / Request #</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Delivery Date</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Order Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total Value</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Paid Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($orders as $order)
                        @php
                            $orderStatus = $order->status;
                            $orderStatusLabel = 'Pending';
                            $orderStatusClass = 'bg-amber-50 text-amber-700 border border-amber-100';
                            if ($orderStatus == 7 || $orderStatus === 'completed') {
                                $orderStatusLabel = 'Completed';
                                $orderStatusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                            } elseif ($orderStatus == 1 || $orderStatus === 'Approved') {
                                $orderStatusLabel = 'Approved';
                                $orderStatusClass = 'bg-purple-50 text-purple-700 border border-purple-100';
                            } elseif ($orderStatus == 2 || $orderStatus === 'Rejected') {
                                $orderStatusLabel = 'Rejected';
                                $orderStatusClass = 'bg-rose-50 text-rose-700 border border-rose-100';
                            } elseif ($orderStatus == 5 || $orderStatus === 'Dispatched') {
                                $orderStatusLabel = 'Dispatched';
                                $orderStatusClass = 'bg-blue-50 text-blue-700 border border-blue-100';
                            }

                            $paymentStatus = $order->payment_completed;
                            $paymentStatusLabel = 'Unpaid';
                            $paymentStatusClass = 'bg-slate-100 text-slate-600 border border-slate-200';
                            if ($paymentStatus == 2) {
                                $paymentStatusLabel = 'Paid';
                                $paymentStatusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                            } elseif ($paymentStatus == 1) {
                                $paymentStatusLabel = 'Partial';
                                $paymentStatusClass = 'bg-amber-50 text-amber-700 border border-amber-100';
                            } elseif ($paymentStatus == 3) {
                                $paymentStatusLabel = 'Credit';
                                $paymentStatusClass = 'bg-indigo-50 text-indigo-700 border border-indigo-100';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 font-mono">
                                {{ $order->order_number ?? 'REQ-'.$order->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="flex items-center gap-1.5">
                                    <i class="bi bi-calendar-event text-slate-400"></i>
                                    {{ $order->delivery_date ? $order->delivery_date->format('Y-m-d') : 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full border {{ $orderStatusClass }}">
                                    {{ $orderStatusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full border {{ $paymentStatusClass }}">
                                    {{ $paymentStatusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-right text-slate-900">
                                Rs. {{ number_format($order->grand_total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-emerald-600">
                                Rs. {{ number_format($order->paid_amount ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate" title="{{ $order->notes }}">
                                {{ $order->notes ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="openViewOrderModal({{ $order->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold flex items-center gap-1 bg-transparent border-none cursor-pointer">
                                    <i class="bi bi-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                <p class="mt-4 font-bold text-gray-700">No Order Requests Found</p>
                                <p class="text-xs mt-1">Start by creating a new request above.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

<!-- RIGHT-SIDE SLIDE-OVER DRAWER MODAL -->
<div id="drawerBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300" onclick="closeCreateOrderDrawer()"></div>

<div id="createOrderDrawer" class="fixed inset-y-0 right-0 z-50 w-full max-w-2xl bg-white shadow-2xl border-l border-slate-100 flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">
    <!-- Header -->
    <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-gray-900">New Order Request</h3>
            <p class="text-xs text-slate-500 mt-0.5">Submit a stock allocation request or Goods Received Note (GRN).</p>
        </div>
        <button type="button" onclick="closeCreateOrderDrawer()" class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 rounded-lg">
            <i class="bi bi-x-lg text-lg leading-none"></i>
        </button>
    </div>

    <!-- Progress Steps Indicator -->
    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center relative overflow-hidden shrink-0">
        <!-- Progress track line -->
        <div class="absolute left-10 right-10 top-1/2 -translate-y-4 h-0.5 bg-slate-100 z-0"></div>
        <div id="stepProgressTrack" class="absolute left-10 top-1/2 -translate-y-4 h-0.5 bg-indigo-600 transition-all duration-300 z-0" style="width: 0%;"></div>

        <!-- Step 1: Info -->
        <div class="step-indicator-item flex flex-col items-center gap-1 z-10" data-step="1">
            <div class="step-circle w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center shadow-md transition-colors border border-indigo-600 text-xs">
                <i class="bi bi-calendar-event"></i>
            </div>
            <span class="text-[10px] font-bold text-slate-900">Delivery Info</span>
        </div>

        <!-- Step 2: Products -->
        <div class="step-indicator-item flex flex-col items-center gap-1 z-10" data-step="2">
            <div class="step-circle w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold flex items-center justify-center transition-colors border border-slate-200 text-xs">
                <i class="bi bi-box-seam"></i>
            </div>
            <span class="text-[10px] font-bold text-slate-400">Add Products</span>
        </div>

        <!-- Step 3: Review -->
        <div class="step-indicator-item flex flex-col items-center gap-1 z-10" data-step="3">
            <div class="step-circle w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold flex items-center justify-center transition-colors border border-slate-200 text-xs">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <span class="text-[10px] font-bold text-slate-400">Review & Submit</span>
        </div>
    </div>

    <!-- Drawer Content (Scrollable) -->
    <div class="flex-1 overflow-y-auto p-6">
        <!-- STEP 1: DELIVERY INFO -->
        <div id="stepView1" class="step-view space-y-5">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-calendar-event-fill text-base"></i>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Specify Target Delivery</h3>
                    <p class="text-[11px] text-gray-400">Identify delivery schedule below.</p>
                </div>
            </div>

            <!-- Holidays List Banner -->
            <div id="holidayBanner" class="hidden p-3.5 bg-amber-50/50 border border-amber-100 rounded-xl">
                <div class="flex gap-2">
                    <i class="bi bi-info-circle-fill text-amber-600 text-sm mt-0.5"></i>
                    <div>
                        <h4 class="text-[10px] font-bold text-amber-800 uppercase tracking-wider mb-1">Upcoming Holidays (Non-operational)</h4>
                        <ul id="holidayList" class="text-[10px] text-amber-700 space-y-0.5 list-disc pl-4"></ul>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="delivery_date" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Delivery Date *</label>
                    <input type="date" id="delivery_date" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    <span class="text-[9px] text-slate-400 mt-1 block">Sundays and holidays are non-operational.</span>
                </div>
                <div>
                    <label for="delivery_time" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Delivery Time *</label>
                    <input type="time" id="delivery_time" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div>
                <label for="notes" class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Special Notes</label>
                <textarea id="notes" rows="3" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" placeholder="Provide any specifications or notes here..."></textarea>
            </div>

            <div class="pt-4 border-t border-slate-50 flex justify-end">
                <button type="button" onclick="goToStep2()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-colors flex items-center gap-1">
                    Next: Add Products <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: PRODUCT SELECTION -->
        <div id="stepView2" class="step-view hidden space-y-5">
            <div class="flex justify-between items-center bg-slate-50 p-3 rounded-xl border border-slate-100 shrink-0">
                <div class="flex items-center gap-2">
                    <i class="bi bi-cart3 text-indigo-600"></i>
                    <span id="cartCount" class="text-xs font-extrabold text-indigo-900">0 Items</span>
                    <span id="cartTotal" class="text-xs font-bold text-indigo-600">Rs. 0</span>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                <div class="w-full sm:w-60 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="productSearch" oninput="filterProductGrid()" placeholder="Search products..." class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>

                <div class="w-full sm:flex-1 overflow-x-auto py-1 flex items-center gap-1.5 scrollbar-none" id="categoryTabs">
                    <button onclick="setCategoryFilter('all')" class="category-tab px-3 py-1.5 bg-indigo-600 text-white rounded-full text-[10px] font-bold whitespace-nowrap shadow-sm border border-indigo-600 transition-all" data-category="all">
                        🏢 All
                    </button>
                </div>
            </div>

            <!-- Grid -->
            <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Loaded dynamically -->
            </div>

            <!-- Bottom Row -->
            <div class="pt-4 border-t border-slate-50 flex justify-between items-center">
                <button type="button" onclick="setStep(1)" class="px-3.5 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold rounded-xl transition-all flex items-center gap-1">
                    <i class="bi bi-chevron-left"></i> Back
                </button>
                <button type="button" onclick="goToStep3()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-colors flex items-center gap-1 shadow-sm">
                    Next: Review <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: REVIEW & SUBMIT -->
        <div id="stepView3" class="step-view hidden space-y-5">
            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 font-bold block mb-0.5">Delivery Date</span>
                    <span id="reviewDeliveryDate" class="font-bold text-slate-800">-</span>
                </div>
                <div>
                    <span class="text-slate-400 font-bold block mb-0.5">Delivery Time</span>
                    <span id="reviewDeliveryTime" class="font-bold text-slate-800">-</span>
                </div>
                <div class="col-span-2 border-t border-slate-200/50 pt-2">
                    <span class="text-slate-400 font-bold block mb-0.5">Instructions</span>
                    <p id="reviewNotes" class="text-slate-600 italic mt-0.5 bg-white p-2 border border-slate-100 rounded-lg"></p>
                </div>
            </div>

            <!-- Review Items Table -->
            <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-slate-100 text-xs">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-2 text-left font-bold text-slate-500 uppercase text-[9px]">Product</th>
                            <th class="px-3 py-2 text-center font-bold text-slate-500 uppercase text-[9px] w-20">Qty</th>
                            <th class="px-3 py-2 text-right font-bold text-slate-500 uppercase text-[9px] w-24">Price</th>
                            <th class="px-3 py-2 text-right font-bold text-slate-500 uppercase text-[9px] w-24">Subtotal</th>
                            <th class="px-3 py-2 text-center font-bold text-slate-500 uppercase text-[9px] w-12"></th>
                        </tr>
                    </thead>
                    <tbody id="reviewTableBody" class="divide-y divide-slate-100 bg-white">
                        <!-- Injected dynamically -->
                    </tbody>
                    <tfoot class="bg-slate-50/50 font-bold">
                        <tr>
                            <td colspan="3" class="px-3 py-3 text-right text-slate-800">Grand Total:</td>
                            <td id="reviewGrandTotal" class="px-3 py-3 text-right text-slate-900 text-sm font-extrabold">Rs. 0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="pt-4 border-t border-slate-50 flex justify-between items-center">
                <button type="button" onclick="setStep(2)" class="px-3.5 py-2 border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold rounded-xl transition-all flex items-center gap-1">
                    <i class="bi bi-chevron-left"></i> Back
                </button>
                <button type="button" onclick="submitOrderRequest()" id="submitBtn" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors flex items-center gap-1.5 shadow-sm cursor-pointer">
                    <i class="bi bi-check2-circle"></i> Submit Request
                </button>
            </div>
        </div>

        <!-- SUCCESS VIEW -->
        <div id="successView" class="hidden space-y-5 text-center py-6">
            <div class="w-12 h-12 bg-emerald-50 border border-emerald-100 rounded-full flex items-center justify-center text-emerald-600 text-2xl mx-auto shadow-sm animate-bounce">
                <i class="bi bi-check-lg"></i>
            </div>

            <div class="space-y-1">
                <h3 class="text-base font-bold text-gray-900">Order Request Submitted!</h3>
                <p class="text-xs text-gray-500 font-medium">Your request is queued for bakery approval.</p>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2.5 text-xs text-left max-w-sm mx-auto">
                <div class="flex justify-between">
                    <span class="text-slate-500">Order ID:</span>
                    <span id="successOrderNo" class="font-bold text-slate-800 font-mono">REQ-00000</span>
                </div>
                <div class="flex justify-between text-base font-extrabold border-t border-slate-200/50 pt-2">
                    <span class="text-slate-800">Total Value:</span>
                    <span id="successTotal" class="text-indigo-600">Rs. 0.00</span>
                </div>
            </div>

            <div class="pt-4 flex gap-2 justify-center">
                <button onclick="closeCreateOrderDrawer(); window.location.reload();" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                    Done
                </button>
            </div>
        </div>
    </div>
</div>
<!-- VIEW ORDER DETAIL MODAL -->
<div id="viewOrderModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeViewOrderModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full w-full">
            <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="detailOrderNumber">REQ-00000</h3>
                    <p class="text-xs text-slate-500 mt-0.5" id="detailOrderDate">Created At: -</p>
                </div>
                <button type="button" onclick="closeViewOrderModal()" class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 rounded-lg">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                <!-- Metadata Info Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100 text-xs">
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Status</span>
                        <span id="detailOrderStatusBadge" class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase border">Pending</span>
                    </div>
                    <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100 text-xs">
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Delivery Target</span>
                        <span id="detailOrderDeliveryDate" class="font-bold text-slate-700">-</span>
                    </div>
                    <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100 text-xs">
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Payment Status</span>
                        <span id="detailOrderPaymentBadge" class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase border">Unpaid</span>
                    </div>
                </div>

                <!-- Notes -->
                <div id="detailOrderNotesContainer">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Instructions / Notes</h4>
                    <p id="detailOrderNotes" class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 italic">No notes provided.</p>
                </div>

                <!-- Products Requested -->
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Requested Products</h4>
                    <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm">
                        <table class="min-w-full divide-y divide-slate-100 text-xs">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-500 uppercase text-[9px]">Product Item</th>
                                    <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase text-[9px] w-24">Requested Qty</th>
                                    <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase text-[9px] w-24">Confirmed Qty</th>
                                    <th class="px-4 py-2.5 text-right font-bold text-slate-500 uppercase text-[9px] w-28">Unit Price</th>
                                    <th class="px-4 py-2.5 text-right font-bold text-slate-500 uppercase text-[9px] w-28">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detailOrderProductsTable" class="divide-y divide-slate-100 bg-white">
                                <!-- Injected -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment History -->
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Payment Records</h4>
                    <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm">
                        <table class="min-w-full divide-y divide-slate-100 text-xs">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-500 uppercase text-[9px]">Date / Time</th>
                                    <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase text-[9px] w-28">Payment ID</th>
                                    <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase text-[9px] w-28">Method</th>
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-500 uppercase text-[9px]">Notes</th>
                                    <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase text-[9px] w-28">Status</th>
                                    <th class="px-4 py-2.5 text-right font-bold text-slate-500 uppercase text-[9px] w-32">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="detailOrderPaymentsTable" class="divide-y divide-slate-100 bg-white">
                                <!-- Injected -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Audit Trail / Timeline -->
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Audit Trail / History</h4>
                    <div class="relative border-l-2 border-slate-100 pl-4 space-y-4 ml-1" id="detailOrderHistoryTimeline">
                        <!-- Injected -->
                    </div>
                </div>
            </div>

            <!-- Footer Details -->
            <div class="bg-slate-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                <div>
                    <span class="text-xs text-slate-500 font-medium">Grand Total</span>
                    <h4 class="text-xl font-black text-indigo-600" id="detailOrderTotalVal">Rs. 0.00</h4>
                </div>
                <button type="button" onclick="closeViewOrderModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // State management
    let step = 1;
    let products = [];
    let categories = [];
    let cart = [];
    let holidaysList = [];
    let selectedCategory = 'all';

    function openCreateOrderDrawer() {
        // Reset states
        step = 1;
        cart = [];
        $('#delivery_date').val('');
        $('#delivery_time').val('');
        $('#notes').val('');
        $('#successView').addClass('hidden');
        $('#stepView1').removeClass('hidden');
        updateCartSummary();

        // Fetch Holidays & Products if not already loaded
        if (holidaysList.length === 0) fetchHolidays();
        if (products.length === 0) fetchProducts();

        setStep(1);

        // Slide In Drawer
        $('#drawerBackdrop').removeClass('hidden').addClass('flex');
        setTimeout(() => {
            $('#drawerBackdrop').removeClass('opacity-0').addClass('opacity-100');
            $('#createOrderDrawer').removeClass('translate-x-full').addClass('translate-x-0');
        }, 50);
        document.body.style.overflow = 'hidden';
    }

    function closeCreateOrderDrawer() {
        // Slide Out Drawer
        $('#createOrderDrawer').removeClass('translate-x-0').addClass('translate-x-full');
        $('#drawerBackdrop').removeClass('opacity-100').addClass('opacity-0');
        setTimeout(() => {
            $('#drawerBackdrop').removeClass('flex').addClass('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    function setStep(newStep) {
        step = newStep;
        
        // Update View Containers
        $('.step-view').addClass('hidden');
        $(`#stepView${step}`).removeClass('hidden');

        // Update indicators
        $('.step-indicator-item').each(function() {
            const indStep = parseInt($(this).data('step'));
            const circle = $(this).find('.step-circle');
            const label = $(this).find('span');

            if (indStep < step) {
                circle.removeClass('bg-slate-100 text-slate-400 bg-indigo-600 text-white border-slate-200 border-indigo-600')
                      .addClass('bg-emerald-50 text-emerald-600 border-emerald-200');
                circle.html('<i class="bi bi-check-lg"></i>');
                label.removeClass('text-slate-400 text-indigo-600').addClass('text-slate-500 font-bold');
            } else if (indStep === step) {
                circle.removeClass('bg-slate-100 text-slate-400 bg-emerald-50 text-emerald-600 border-slate-200 border-emerald-200')
                      .addClass('bg-indigo-600 text-white border-indigo-600');
                if (indStep === 1) circle.html('<i class="bi bi-calendar-event"></i>');
                if (indStep === 2) circle.html('<i class="bi bi-box-seam"></i>');
                if (indStep === 3) circle.html('<i class="bi bi-clipboard-check"></i>');
                label.removeClass('text-slate-400 text-slate-500').addClass('text-slate-900 font-extrabold');
            } else {
                circle.removeClass('bg-indigo-600 text-white bg-emerald-50 text-emerald-600 border-indigo-600 border-emerald-200')
                      .addClass('bg-slate-100 text-slate-400 border-slate-200');
                if (indStep === 1) circle.html('<i class="bi bi-calendar-event"></i>');
                if (indStep === 2) circle.html('<i class="bi bi-box-seam"></i>');
                if (indStep === 3) circle.html('<i class="bi bi-clipboard-check"></i>');
                label.removeClass('text-indigo-600 text-slate-900 font-extrabold').addClass('text-slate-400 font-bold');
            }
        });

        const pct = (step - 1) * 50;
        $('#stepProgressTrack').css('width', pct + '%');
    }

    function fetchHolidays() {
        $.getJSON('/agent-panel/api/holidays')
            .done(function(response) {
                if (response.status && response.data) {
                    holidaysList = response.data;
                    if (holidaysList.length > 0) {
                        $('#holidayBanner').removeClass('hidden');
                        const list = $('#holidayList');
                        list.empty();
                        holidaysList.slice(0, 5).forEach(h => {
                            list.append(`<li><strong>${h.date.split('T')[0]}:</strong> ${h.description}</li>`);
                        });
                    }
                }
            });
    }

    function fetchProducts() {
        $.getJSON('/agent-panel/api/products')
            .done(function(response) {
                if (response.status && response.data) {
                    products = response.data;
                    categories = response.categories || [];
                    
                    const tabs = $('#categoryTabs');
                    tabs.find('.category-tab:not([data-category="all"])').remove();
                    
                    categories.forEach(cat => {
                        let emoji = '📦';
                        if (cat.label.includes('Bread')) emoji = '🍞';
                        else if (cat.label.includes('Bun')) emoji = '🥯';
                        else if (cat.label.includes('Cake')) emoji = '🍰';
                        else if (cat.label.includes('Pastr')) emoji = '🥐';

                        tabs.append(`
                            <button onclick="setCategoryFilter('${cat.id}')" class="category-tab px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100 rounded-full text-[10px] font-bold whitespace-nowrap transition-all" data-category="${cat.id}">
                                ${emoji} ${cat.label}
                            </button>
                        `);
                    });

                    renderProductGrid();
                }
            });
    }

    function setCategoryFilter(catId) {
        selectedCategory = catId;
        $('.category-tab').removeClass('bg-indigo-600 text-white border-indigo-600 shadow-sm')
                         .addClass('bg-slate-50 hover:bg-slate-100 text-slate-600 border-slate-100');
        $(`.category-tab[data-category="${catId}"]`).addClass('bg-indigo-600 text-white border-indigo-600 shadow-sm')
                                                    .removeClass('bg-slate-50 hover:bg-slate-100 text-slate-600 border-slate-100');
        filterProductGrid();
    }

    function filterProductGrid() {
        const searchVal = $('#productSearch').val().toLowerCase().trim();
        
        $('.product-grid-card').each(function() {
            const name = $(this).data('name').toLowerCase();
            const ref = $(this).data('ref').toLowerCase();
            const cat = $(this).data('category').toString();

            const matchesSearch = name.includes(searchVal) || ref.includes(searchVal);
            const matchesCategory = selectedCategory === 'all' || cat === selectedCategory;

            if (matchesSearch && matchesCategory) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        });
    }

    function renderProductGrid() {
        const grid = $('#productGrid');
        grid.empty();

        if (products.length === 0) {
            grid.html(`<div class="col-span-full py-12 text-center text-slate-400"><i class="bi bi-box-seam text-2xl"></i><p class="mt-2 text-xs">No products found</p></div>`);
            return;
        }

        products.forEach(p => {
            const inCart = cart.find(item => item.id === p.id);
            const quantity = inCart ? inCart.quantity : 1;

            const card = `
                <div class="product-grid-card bg-slate-50/50 hover:bg-white border border-slate-100 hover:border-indigo-100 rounded-2xl p-4 transition-all duration-300 flex flex-col justify-between group shadow-sm hover:shadow-md"
                     data-id="${p.id}"
                     data-name="${p.product_name}"
                     data-ref="${p.reference_number}"
                     data-category="${p.pm_product_category_id || ''}">
                    
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[9px] text-slate-400 font-semibold font-mono">#${p.reference_number}</span>
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 text-[9px] font-bold rounded uppercase tracking-wider">${p.category}</span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800 line-clamp-2 mb-1 group-hover:text-indigo-600 transition-colors">${p.product_name}</h4>
                        <div class="flex items-baseline gap-1 mt-2 mb-4">
                            <span class="text-slate-400 text-[9px]">Price:</span>
                            <span class="text-xs font-extrabold text-slate-900">Rs. ${p.distributor_price.toLocaleString()}</span>
                        </div>
                    </div>

                    <div class="space-y-3 pt-3 border-t border-slate-100/50">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] text-slate-400">Req Qty:</span>
                            <div class="flex items-center border border-slate-200 rounded-xl bg-white overflow-hidden shadow-sm">
                                <button type="button" onclick="adjustInputQty(${p.id}, -1)" class="px-2 py-0.5 text-indigo-600 hover:bg-slate-50 transition-colors font-bold text-xs"><i class="bi bi-minus"></i></button>
                                <input type="number" id="qtyInput_${p.id}" value="${quantity}" min="1" class="w-10 text-center text-xs font-bold text-slate-800 outline-none border-none">
                                <button type="button" onclick="adjustInputQty(${p.id}, 1)" class="px-2 py-0.5 text-indigo-600 hover:bg-slate-50 transition-colors font-bold text-xs"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>

                        <button type="button" onclick="handleAddToCart(${p.id})" id="addBtn_${p.id}" class="w-full py-2 ${inCart ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-indigo-600 hover:bg-indigo-700 text-white'} text-[10px] font-bold rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer border-none">
                            <i class="bi ${inCart ? 'bi-check-circle' : 'bi-plus-lg'}"></i> ${inCart ? 'Update Qty' : 'Add to Request'}
                        </button>
                    </div>
                </div>
            `;
            grid.append(card);
        });
    }

    function adjustInputQty(productId, amount) {
        const input = $(`#qtyInput_${productId}`);
        const currentVal = parseInt(input.val()) || 1;
        input.val(Math.max(1, currentVal + amount));
    }

    function handleAddToCart(productId) {
        const product = products.find(p => p.id === productId);
        if (!product) return;

        const qty = parseInt($(`#qtyInput_${productId}`).val()) || 1;
        const existing = cart.find(item => item.id === productId);

        if (existing) {
            existing.quantity = qty;
            toastr.success(`Updated quantity for ${product.product_name}`);
        } else {
            cart.push({
                ...product,
                quantity: qty
            });
            toastr.success(`Added ${product.product_name} to request`);
        }

        updateCartSummary();
        renderProductGrid();
    }

    function updateCartSummary() {
        const count = cart.length;
        const total = cart.reduce((sum, item) => sum + (item.quantity * item.distributor_price), 0);

        $('#cartCount').text(`${count} Items`);
        $('#cartTotal').text(`Rs. ${total.toLocaleString()}`);
    }

    function goToStep2() {
        const date = $('#delivery_date').val();
        const time = $('#delivery_time').val();

        if (!date || !time) {
            toastr.error('Please specify both Delivery Date and Time.');
            return;
        }

        const target = new Date(date + 'T' + time);
        
        if (target < new Date()) {
            toastr.error('You cannot select a past date/time.');
            return;
        }

        if (target.getDay() === 0) {
            Swal.fire({
                title: 'Delivery Target Notice',
                text: 'Sundays are non-operational holidays. Do you want to proceed anyway?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Proceed',
                cancelButtonText: 'Change Date'
            }).then((result) => {
                if (result.isConfirmed) {
                    setStep(2);
                }
            });
            return;
        }

        const combined = `${date} ${time}`;
        const nextBtn = $('#stepView1 button');
        nextBtn.prop('disabled', true).html('<i class="animate-spin bi bi-arrow-repeat"></i> Validating...');

        $.ajax({
            url: '/agent-panel/api/orders/validate-date',
            type: 'POST',
            data: {
                delivery_date: combined,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                nextBtn.prop('disabled', false).html('Next: Add Products <i class="bi bi-chevron-right"></i>');
                if (response.status) {
                    setStep(2);
                } else {
                    // Soft warning confirmation - allow proceeding anyway
                    Swal.fire({
                        title: 'Delivery Target Notice',
                        text: (response.message || 'The selected delivery date is not recommended.') + ' Do you want to proceed anyway?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, Proceed',
                        cancelButtonText: 'Change Date'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            setStep(2);
                        }
                    });
                }
            },
            error: function(xhr) {
                nextBtn.prop('disabled', false).html('Next: Add Products <i class="bi bi-chevron-right"></i>');
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Date validation failed.';
                
                // Allow proceeding on error as well for soft bypass
                Swal.fire({
                    title: 'Bypass Validation?',
                    text: msg + ' Do you still want to proceed to product selection?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Proceed',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        setStep(2);
                    }
                });
            }
        });
    }

    // Auto open modal on load if create parameter is passed
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('create')) {
            openCreateOrderDrawer();
            // clean up url query param without refreshing the page
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });

    function goToStep3() {
        if (cart.length === 0) {
            toastr.error('Please add at least one product item to your request.');
            return;
        }

        $('#reviewDeliveryDate').text($('#delivery_date').val());
        $('#reviewDeliveryTime').text($('#delivery_time').val());
        
        const noteText = $('#notes').val().trim();
        $('#reviewNotes').text(noteText ? noteText : 'No instructions provided');

        const reviewBody = $('#reviewTableBody');
        reviewBody.empty();

        let grandTotal = 0;
        cart.forEach(item => {
            const subtotal = item.quantity * item.distributor_price;
            grandTotal += subtotal;

            const row = `
                <tr>
                    <td class="px-3 py-2.5 font-semibold text-slate-800">
                        ${item.product_name}
                        <p class="text-[9px] text-slate-400 font-mono mt-0.5">#${item.reference_number}</p>
                    </td>
                    <td class="px-3 py-2.5 text-center font-bold text-slate-700">${item.quantity}</td>
                    <td class="px-3 py-2.5 text-right text-slate-600">Rs. ${item.distributor_price.toLocaleString()}</td>
                    <td class="px-3 py-2.5 text-right font-extrabold text-slate-800">Rs. ${subtotal.toLocaleString()}</td>
                    <td class="px-3 py-2.5 text-center">
                        <button type="button" onclick="removeFromCart(${item.id})" class="text-rose-500 hover:text-rose-700 p-1 rounded transition-colors border-none bg-transparent cursor-pointer"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `;
            reviewBody.append(row);
        });

        $('#reviewGrandTotal').text('Rs. ' + grandTotal.toLocaleString());
        setStep(3);
    }

    function removeFromCart(productId) {
        cart = cart.filter(item => item.id !== productId);
        updateCartSummary();
        toastr.info('Item removed from request');
        
        if (cart.length === 0) {
            setStep(2);
        } else {
            goToStep3();
        }
    }

    function submitOrderRequest() {
        Swal.fire({
            title: 'Submit Order Request?',
            text: "Are you sure you want to submit this Goods Received Note (GRN) to the bakery?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Submit Request',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true).html('<i class="animate-spin bi bi-arrow-repeat"></i> Submitting...');

                const date = $('#delivery_date').val();
                const time = $('#delivery_time').val();

                const payload = {
                    delivery_date: `${date} ${time}`,
                    notes: $('#notes').val(),
                    items: cart.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity
                    })),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '/agent-panel/api/orders/create',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(payload),
                    success: function(response) {
                        if (response.status && response.data) {
                            const data = response.data;
                            $('#successOrderNo').text(data.order_number || 'REQ-' + data.id);
                            
                            const totalVal = cart.reduce((sum, item) => sum + (item.quantity * item.distributor_price), 0);
                            $('#successTotal').text('Rs. ' + totalVal.toLocaleString());

                            $('#stepProgressTrack').css('width', '100%');
                            $('.step-indicator-item .step-circle').removeClass('bg-indigo-600 bg-slate-100 text-slate-400')
                                                                 .addClass('bg-emerald-50 text-emerald-600 border-emerald-200')
                                                                 .html('<i class="bi bi-check-lg"></i>');
                            
                            $('.step-view').addClass('hidden');
                            $('#successView').removeClass('hidden');
                            
                            Swal.fire(
                                'Order Submitted!',
                                'Your request has been successfully created and queued.',
                                'success'
                            );
                        } else {
                            submitBtn.prop('disabled', false).html('<i class="bi bi-check2-circle text-base"></i> Submit Request');
                            toastr.error(response.message || 'Failed to submit order request.');
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('<i class="bi bi-check2-circle text-base"></i> Submit Request');
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error submitting request.';
                        toastr.error(msg);
                        Swal.fire('Failed', msg, 'error');
                    }
                });
            }
        });
    }

    function openViewOrderModal(orderId) {
        console.log("openViewOrderModal called for ID:", orderId);
        $.getJSON(`/agent-panel/api/orders/${orderId}`)
            .done(function(response) {
                console.log("API response received:", response);
                if (response.status && response.data) {
                    const order = response.data;
                    $('#detailOrderNumber').text(order.order_number || 'REQ-' + order.id);
                    $('#detailOrderDate').text('Created At: ' + window.formatDateTimeGMT(order.created_at));
                    
                    // Status Badge
                    let statusLabel = 'Pending';
                    let statusClass = 'bg-amber-50 text-amber-700 border-amber-100';
                    if (order.status == 7 || order.status === 'completed') {
                        statusLabel = 'Completed';
                        statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                    } else if (order.status == 1 || order.status === 'Approved') {
                        statusLabel = 'Approved';
                        statusClass = 'bg-purple-50 text-purple-700 border-purple-100';
                    } else if (order.status == 2 || order.status === 'Rejected') {
                        statusLabel = 'Rejected';
                        statusClass = 'bg-rose-50 text-rose-700 border-rose-100';
                    } else if (order.status == 5 || order.status === 'Dispatched') {
                        statusLabel = 'Dispatched';
                        statusClass = 'bg-blue-50 text-blue-700 border-blue-100';
                    }
                    $('#detailOrderStatusBadge').text(statusLabel).removeClass().addClass(`px-2.5 py-0.5 rounded text-[10px] font-bold uppercase border ${statusClass}`);

                    // Delivery Target Date
                    $('#detailOrderDeliveryDate').text(order.delivery_date ? order.delivery_date.split('T')[0] : 'N/A');

                    // Payment Badge
                    let payLabel = 'Unpaid';
                    let payClass = 'bg-slate-100 text-slate-600 border-slate-200';
                    if (order.payment_completed == 2) {
                        payLabel = 'Paid';
                        payClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                    } else if (order.payment_completed == 1) {
                        payLabel = 'Partial';
                        payClass = 'bg-amber-50 text-amber-700 border-amber-100';
                    } else if (order.payment_completed == 3) {
                        payLabel = 'Credit';
                        payClass = 'bg-indigo-50 text-indigo-700 border-indigo-100';
                    }
                    $('#detailOrderPaymentBadge').text(payLabel).removeClass().addClass(`px-2.5 py-0.5 rounded text-[10px] font-bold uppercase border ${payClass}`);

                    // Notes
                    $('#detailOrderNotes').text(order.notes ? order.notes : 'No instructions provided.');

                    // Products Table
                    const tbody = $('#detailOrderProductsTable');
                    tbody.empty();
                    if (order.order_products && order.order_products.length > 0) {
                        order.order_products.forEach(p => {
                            const sub = parseFloat(p.subtotal) || 0;
                            tbody.append(`
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3 font-semibold text-slate-800">
                                        ${p.product_name || 'Product'}
                                        <p class="text-[9px] text-slate-400 font-mono mt-0.5">#${p.product ? p.product.reference_number : ''}</p>
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-600">${parseFloat(p.quantity).toLocaleString()}</td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-500">${p.confirmed_quantity !== null ? parseFloat(p.confirmed_quantity).toLocaleString() : '-'}</td>
                                    <td class="px-4 py-3 text-right text-slate-600">Rs. ${parseFloat(p.unit_price).toLocaleString()}</td>
                                    <td class="px-4 py-3 text-right font-bold text-slate-800">Rs. ${sub.toLocaleString()}</td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.html('<tr><td colspan="5" class="px-4 py-4 text-center text-slate-400 italic">No products found in this request.</td></tr>');
                    }

                    // Payments Table
                    const ptbody = $('#detailOrderPaymentsTable');
                    ptbody.empty();
                    if (order.payments && order.payments.length > 0) {
                        order.payments.forEach(p => {
                            let payStatusLabel = 'Pending';
                            let payStatusClass = 'bg-amber-50 text-amber-700 border-amber-100';
                            if (p.status == 2 || p.status === 'Approved' || p.status === 'completed') {
                                payStatusLabel = 'Approved';
                                payStatusClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                            } else if (p.status == 3 || p.status === 'Rejected') {
                                payStatusLabel = 'Rejected';
                                payStatusClass = 'bg-rose-50 text-rose-700 border-rose-100';
                            }
                            const payAmt = parseFloat(p.payment_amount) || 0;
                            ptbody.append(`
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3 text-slate-600">${window.formatDateTimeGMT(p.payment_date)}</td>
                                    <td class="px-4 py-3 text-center font-mono text-slate-500">PAY-${p.ad_agent_payment_id || p.id}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            ${p.payment_method || 'N/A'}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-500">${p.notes || '-'}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase border ${payStatusClass}">
                                            ${payStatusLabel}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-slate-800">Rs. ${payAmt.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                </tr>
                            `);
                        });
                    } else {
                        ptbody.html('<tr><td colspan="6" class="px-4 py-4 text-center text-slate-400 italic">No payment records found.</td></tr>');
                    }

                    // Total
                    $('#detailOrderTotalVal').text('Rs. ' + (parseFloat(order.grand_total) || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }));

                    // Timeline
                    const timeline = $('#detailOrderHistoryTimeline');
                    timeline.empty();
                    if (order.history && order.history.length > 0) {
                        order.history.forEach(h => {
                            const user = h.user ? (h.user.first_name + ' ' + h.user.last_name) : 'System';
                            timeline.append(`
                                <div class="relative pl-5 text-xs">
                                    <span class="absolute left-[-21px] top-1 w-2.5 h-2.5 rounded-full bg-slate-400 border-2 border-white shadow-sm"></span>
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <span class="font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded text-[10px]">${h.action}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">${window.formatDateTimeGMT(h.created_at)}</span>
                                    </div>
                                    <p class="text-slate-500 text-[10px]">${h.description || ''} (By: <strong>${user}</strong>)</p>
                                </div>
                            `);
                        });
                    } else {
                        timeline.html('<p class="text-xs text-slate-400 italic pl-1">No history records found.</p>');
                    }

                    // Show Modal
                    console.log("Removing hidden class from viewOrderModal");
                    $('#viewOrderModal').removeClass('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    console.error("Response status or data is missing:", response);
                    toastr.error('Order details returned invalid structure.');
                }
            })
            .fail(function(xhr, textStatus, errorThrown) {
                console.error("AJAX call failed:", textStatus, errorThrown, xhr);
                toastr.error('Failed to load order request details: ' + (xhr.responseJSON ? xhr.responseJSON.message : textStatus));
            });
    }

    function closeViewOrderModal() {
        $('#viewOrderModal').addClass('hidden');
        document.body.style.overflow = '';
    }

    // Close on Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCreateOrderDrawer();
            closeViewOrderModal();
        }
    });
</script>
@endsection
