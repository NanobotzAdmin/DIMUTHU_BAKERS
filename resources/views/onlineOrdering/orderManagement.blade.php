@extends('layouts.app')

@section('content')
<div class="space-y-6 p-6">
    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Order Management</h1>
        <p class="text-gray-600 mt-1">Manage and process online orders from customers</p>
    </div>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @php
            $stats = [
                ['label' => 'Pending', 'status' => 'pending', 'icon' => 'clock', 'color' => 'yellow'],
                ['label' => 'Confirmed', 'status' => 'confirmed', 'icon' => 'check-circle', 'color' => 'blue'],
                ['label' => 'Preparing', 'status' => 'preparing', 'icon' => 'package', 'color' => 'purple'],
                ['label' => 'Ready', 'status' => 'ready', 'icon' => 'check-circle-2', 'color' => 'green'],
                ['label' => 'Completed', 'status' => 'completed', 'icon' => 'archive', 'color' => 'gray'],
            ];
            
            // Helper to get count
            $getCount = function($status) use ($orders) {
                return count(array_filter($orders, fn($o) => $o->status === $status));
            };
        @endphp

        @foreach($stats as $stat)
            <div class="bg-white rounded-lg border border-gray-200 p-6 cursor-pointer hover:shadow-md transition-shadow"
                 onclick="filterByStatus('{{ $stat['status'] }}')">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">{{ $stat['label'] }}</span>
                    {{-- Icons (Inline SVG) --}}
                    @if($stat['icon'] === 'clock')
                        <svg class="w-4 h-4 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @elseif($stat['icon'] === 'package')
                        <svg class="w-4 h-4 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    @elseif($stat['icon'] === 'archive')
                        <svg class="w-4 h-4 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    @else
                        <svg class="w-4 h-4 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </div>
                <div class="text-2xl font-bold text-gray-900">{{ $getCount($stat['status']) }}</div>
            </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" id="searchInput" placeholder="Search by order number, customer name, email..."
                       class="w-full p-3 bg-gray-50 pl-10 h-10 rounded-md border border-gray-300 focus:ring-2 focus:ring-black focus:outline-none text-sm transition-shadow">
            </div>
            <div class="flex gap-3">
                <select id="statusFilter" class="max-w-[160px] p-3 bg-gray-50 h-10 rounded-md border border-gray-300 text-sm focus:ring-2 focus:ring-black">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <select id="outletFilter" class="max-w-[180px] p-3 bg-gray-50 h-10 rounded-md border border-gray-300 text-sm focus:ring-2 focus:ring-black">
                    <option value="all">All Outlets</option>
                    @foreach($locations as $location)
                        @if($location->hasRetail)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Orders List --}}
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="font-semibold text-lg" id="ordersTitle">Orders</h3>
            <p class="text-sm text-gray-500" id="ordersDescription">Manage all incoming orders</p>
        </div>
        <div class="p-6">
            <div id="noOrders" class="hidden text-center py-12">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <h3 class="font-medium text-gray-900 mb-1">No orders found</h3>
                <p class="text-sm text-gray-500">Try adjusting your search or filters</p>
            </div>

            <div class="space-y-3" id="ordersList">
                @foreach($orders as $order)
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'preparing' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'ready' => 'bg-green-100 text-green-800 border-green-200',
                            'completed' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                        ];
                        $badgeClass = $statusColors[$order->status] ?? $statusColors['pending'];
                        
                        // Serialize details for JS
                        $orderDetailsJson = json_encode($order);
                    @endphp
                    <div class="order-item border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors"
                         data-status="{{ $order->status }}"
                         data-outlet="{{ $order->pickup->outletId }}"
                         data-search="{{ strtolower($order->orderNumber . ' ' . $order->customer->name . ' ' . $order->customer->phone . ' ' . ($order->customer->email ?? '')) }}">
                        
                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                            {{-- Order Info --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="font-semibold text-gray-900 text-lg">{{ $order->orderNumber }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border flex items-center {{ $badgeClass }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    @if($order->payment->status === 'succeeded')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                            Paid
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500 block">Customer:</span>
                                        <div class="font-medium text-gray-900">{{ $order->customer->name }}</div>
                                        <div class="text-gray-600">{{ $order->customer->phone }}</div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block">Pickup:</span>
                                        <div class="font-medium text-gray-900">{{ $order->pickup->outletName }}</div>
                                        <div class="text-gray-600">
                                            {{ \Carbon\Carbon::parse($order->pickup->scheduledDate)->format('d/m/Y') }} 
                                            {{ $order->pickup->scheduledTime }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block">Items:</span>
                                        <div class="font-medium text-gray-900">{{ count($order->items) }} item(s)</div>
                                        <div class="text-gray-600 font-semibold">{{ number_format($order->summary->total, 2) }} LKR</div>
                                    </div>
                                </div>
                                
                                <div class="text-xs text-gray-500 mt-3">
                                    Ordered {{ \Carbon\Carbon::parse($order->createdAt)->format('d/m/Y h:i A') }}
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-row md:flex-col gap-2">
                                @if($order->status === 'pending')
                                    <button onclick='openActionModal("accept", {!! $orderDetailsJson !!})' class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800 transition-colors">
                                        Accept
                                    </button>
                                    <button onclick='openActionModal("reject", {!! $orderDetailsJson !!})' class="inline-flex items-center justify-center px-4 py-2 border border-red-200 text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 transition-colors">
                                        Reject
                                    </button>
                                @elseif($order->status === 'confirmed')
                                    <button onclick='openActionModal("preparing", {!! $orderDetailsJson !!})' class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800 transition-colors">
                                        Start Preparing
                                    </button>
                                @elseif($order->status === 'preparing')
                                    <button onclick='openActionModal("ready", {!! $orderDetailsJson !!})' class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                                        Mark Ready
                                    </button>
                                @elseif($order->status === 'ready')
                                    <button onclick='openActionModal("complete", {!! $orderDetailsJson !!})' class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800 transition-colors">
                                        Complete
                                    </button>
                                @endif

                                <button onclick='openDetailModal({!! $orderDetailsJson !!})' class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    Details
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Order Detail Modal --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeDetailModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="detailModalTitle">Order Details</h3>
                            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div id="detailModalContent" class="space-y-6">
                            <!-- Populated via JS -->
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" onclick="closeDetailModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Confirmation Modal --}}
    <div id="actionModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeActionModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="actionModalTitle">Confirm Action</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" id="actionModalDescription">Are you sure?</p>
                                </div>
                                <div id="actionNoteContainer" class="mt-4 hidden">
                                    <label for="actionNote" class="block text-sm font-medium leading-6 text-gray-900">Reason / Note (Optional)</label>
                                    <div class="mt-2">
                                        <textarea id="actionNote" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-black sm:text-sm sm:leading-6"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                        <button type="button" id="confirmActionButton" class="inline-flex w-full justify-center rounded-md bg-black px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 sm:ml-3 sm:w-auto">Confirm</button>
                        <button type="button" onclick="closeActionModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // State management
    let currentPendingAction = null;
    let currentPendingOrder = null;

    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const outletFilter = document.getElementById('outletFilter');
        const confirmBtn = document.getElementById('confirmActionButton');

        // Event Listeners
        searchInput.addEventListener('input', filterOrders);
        statusFilter.addEventListener('change', filterOrders);
        outletFilter.addEventListener('change', filterOrders);
        confirmBtn.addEventListener('click', executeAction);

        // Make filterByStatus globally available for stats cards
        window.filterByStatus = (status) => {
            statusFilter.value = status;
            filterOrders();
        };

        // Initialize
        filterOrders();
    });

    function filterOrders() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('statusFilter').value;
        const outlet = document.getElementById('outletFilter').value;
        const orderItems = document.querySelectorAll('.order-item');
        let visibleCount = 0;

        orderItems.forEach(item => {
            const itemStatus = item.dataset.status;
            const itemOutlet = item.dataset.outlet;
            const itemSearch = item.dataset.search;

            const matchesSearch = itemSearch.includes(searchTerm);
            const matchesStatus = status === 'all' || itemStatus === status;
            const matchesOutlet = outlet === 'all' || itemOutlet === outlet;

            if (matchesSearch && matchesStatus && matchesOutlet) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/Hide No Orders Message
        const noOrdersMsg = document.getElementById('noOrders');
        const ordersList = document.getElementById('ordersList');
        if (visibleCount === 0) {
            noOrdersMsg.classList.remove('hidden');
            ordersList.classList.add('hidden');
        } else {
            noOrdersMsg.classList.add('hidden');
            ordersList.classList.remove('hidden');
        }
        
        // Update Title
        const titleSpan = document.querySelector('#ordersTitle');
        if(status !== 'all') {
            titleSpan.innerHTML = `Orders (${visibleCount}) - ${status.charAt(0).toUpperCase() + status.slice(1)}`;
        } else {
            titleSpan.innerHTML = `Orders (${visibleCount})`;
        }
    }

    // Modal Functions
    window.openDetailModal = (order) => {
        const modal = document.getElementById('detailModal');
        const title = document.getElementById('detailModalTitle');
        const content = document.getElementById('detailModalContent');
        
        title.textContent = `Order Details - ${order.orderNumber}`;
        
        // Format Items HTML
        const itemsHtml = order.items.map(item => `
            <div class="flex items-center justify-between p-3 border-b border-gray-100 last:border-0">
                <div class="flex-1">
                    <div class="font-medium text-gray-900">${item.productName}</div>
                    ${item.notes ? `<div class="text-sm text-gray-500">Note: ${item.notes}</div>` : ''}
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Qty: ${item.quantity}</div>
                    <div class="font-medium">${parseFloat(item.subtotal).toFixed(2)} LKR</div>
                </div>
            </div>
        `).join('');

        content.innerHTML = `
            <div class="space-y-6">
                <!-- Status Badge -->
                <div>
                    <h3 class="font-semibold text-sm text-gray-900 mb-2">Status</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border bg-gray-100 text-gray-800 border-gray-200">
                        ${order.status.toUpperCase()}
                    </span>
                </div>

                <!-- Customer Info -->
                <div>
                    <h3 class="font-semibold text-sm text-gray-900 mb-2">Customer Information</h3>
                    <div class="bg-gray-50 p-4 rounded-lg text-sm border border-gray-100">
                        <div class="mb-1"><span class="text-gray-500 w-16 inline-block">Name:</span> <span class="font-medium">${order.customer.name}</span></div>
                        <div class="mb-1"><span class="text-gray-500 w-16 inline-block">Phone:</span> <span class="font-medium">${order.customer.phone}</span></div>
                        ${order.customer.email ? `<div><span class="text-gray-500 w-16 inline-block">Email:</span> <span class="font-medium">${order.customer.email}</span></div>` : ''}
                    </div>
                </div>

                <!-- Items -->
                <div>
                    <h3 class="font-semibold text-sm text-gray-900 mb-2">Order Items</h3>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        ${itemsHtml}
                    </div>
                </div>

                <!-- Summary -->
                <div>
                    <h3 class="font-semibold text-sm text-gray-900 mb-2">Order Summary</h3>
                    <div class="bg-gray-50 p-4 rounded-lg text-sm border border-gray-100 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Subtotal:</span>
                            <span class="font-medium">${parseFloat(order.summary.subtotal).toFixed(2)} LKR</span>
                        </div>
                        ${order.summary.discount > 0 ? `
                        <div class="flex justify-between text-green-600">
                            <span>Discount:</span>
                            <span>-${parseFloat(order.summary.discount).toFixed(2)} LKR</span>
                        </div>` : ''}
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tax:</span>
                            <span class="font-medium">${parseFloat(order.summary.tax).toFixed(2)} LKR</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-200 text-base">
                            <span class="font-bold text-gray-900">Total:</span>
                            <span class="font-bold text-gray-900">${parseFloat(order.summary.total).toFixed(2)} LKR</span>
                        </div>
                    </div>
                </div>

                <!-- Pickup Info -->
                <div>
                    <h3 class="font-semibold text-sm text-gray-900 mb-2">Pickup Information</h3>
                    <div class="bg-gray-50 p-4 rounded-lg text-sm border border-gray-100">
                        <div class="mb-1"><span class="text-gray-500 w-16 inline-block">Outlet:</span> <span class="font-medium">${order.pickup.outletName}</span></div>
                        <div class="mb-1"><span class="text-gray-500 w-16 inline-block">Date:</span> <span class="font-medium">${new Date(order.pickup.scheduledDate).toLocaleDateString()}</span></div>
                        <div><span class="text-gray-500 w-16 inline-block">Time:</span> <span class="font-medium">${order.pickup.scheduledTime}</span></div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div>
                    <h3 class="font-semibold text-sm text-gray-900 mb-2">Payment Information</h3>
                    <div class="bg-gray-50 p-4 rounded-lg text-sm border border-gray-100">
                        <div class="mb-1"><span class="text-gray-500 w-16 inline-block">Method:</span> <span class="font-medium">${order.payment.method}</span></div>
                        <div>
                            <span class="text-gray-500 w-16 inline-block">Status:</span> 
                            <span class="px-2 py-0.5 rounded text-xs font-medium border bg-white border-gray-200 ml-1">
                                ${order.payment.status}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        modal.classList.remove('hidden');
    }

    window.closeDetailModal = () => {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Action Modal Functions
    window.openActionModal = (action, order) => {
        currentPendingAction = action;
        currentPendingOrder = order;

        const modal = document.getElementById('actionModal');
        const title = document.getElementById('actionModalTitle');
        const desc = document.getElementById('actionModalDescription');
        const noteContainer = document.getElementById('actionNoteContainer');
        const confirmBtn = document.getElementById('confirmActionButton');

        // Reset
        document.getElementById('actionNote').value = '';
        
        let titleText = '';
        let descText = '';
        let btnColor = 'bg-black hover:bg-gray-800';

        switch(action) {
            case 'accept':
                titleText = 'Confirm Order';
                descText = `Are you sure you want to accept order ${order.orderNumber}?`;
                noteContainer.classList.add('hidden');
                break;
            case 'reject':
                titleText = 'Reject Order';
                descText = `Are you sure you want to reject order ${order.orderNumber}? The customer will be notified.`;
                noteContainer.classList.remove('hidden');
                btnColor = 'bg-red-600 hover:bg-red-700';
                break;
            case 'preparing':
                titleText = 'Start Preparing';
                descText = `Mark order ${order.orderNumber} as preparing?`;
                noteContainer.classList.add('hidden');
                break;
            case 'ready':
                titleText = 'Mark Ready';
                descText = `Mark order ${order.orderNumber} as ready for pickup? Customer will be notified.`;
                noteContainer.classList.add('hidden');
                btnColor = 'bg-green-600 hover:bg-green-700';
                break;
            case 'complete':
                titleText = 'Complete Order';
                descText = `Mark order ${order.orderNumber} as completed?`;
                noteContainer.classList.add('hidden');
                break;
        }

        title.textContent = titleText;
        desc.textContent = descText;
        confirmBtn.className = `inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto ${btnColor}`;
        
        modal.classList.remove('hidden');
    }

    window.closeActionModal = () => {
        document.getElementById('actionModal').classList.add('hidden');
        currentPendingAction = null;
        currentPendingOrder = null;
    }

    function executeAction() {
        if(!currentPendingAction || !currentPendingOrder) return;

        const note = document.getElementById('actionNote').value;
        
        // Simulating API Call
        console.log(`Executing ${currentPendingAction} on ${currentPendingOrder.orderNumber} with note: ${note}`);

        // Close Modal
        closeActionModal();

        // Show Success Feedback (using Swal if available, else alert)
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: `Order ${currentPendingOrder.orderNumber} status updated successfully!`,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
             // Fallback toast-like notification
             alert(`Success: Order ${currentPendingOrder.orderNumber} updated!`);
        }

        // Ideally here we would reload the page or update the DOM to reflect the change.
        // For this dummy demo, we can just reload to reset state or maybe manually update the DOM if we were persisting state.
        // Since it's dummy data driven from Controller, a reload is the easiest way to "simulate" a refresh, 
        // BUT resetting to dummy data might be confusing if the user expects persistence. 
        // So let's just update the UI visually for the demo.
        
        const card = document.querySelector(`.order-item[data-search*="${currentPendingOrder.orderNumber.toLowerCase()}"]`);
        if(card) {
            // Very basic visual update logic for demo purposes
            if(currentPendingAction === 'reject' || currentPendingAction === 'cancel') {
                 card.remove(); 
            } else {
                // For other statuses, we'd ideally re-render the card, but that's complex without a framework.
                // We'll just reload for now as it's the cleanest "reset" to the dummy state (or in a real app, fetch fresh data).
                // Actually, let's just show the alert and do nothing to the DOM to avoid breaking the dummy data illusion too much.
            }
        }
    }
</script>
@endsection
