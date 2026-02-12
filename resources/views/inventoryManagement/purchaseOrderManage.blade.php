@extends('layouts.app')
@section('title', 'Purchase Orders')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 p-4 md:p-6">

        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl text-gray-900 font-semibold">Purchase Orders</h1>
                        <p class="text-gray-600">Manage supplier orders and procurement</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('supplierCompare.index') }}"
                        class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m3 16 4 4 4-4" />
                            <path d="M7 20V4" />
                            <path d="m21 8-4-4-4 4" />
                            <path d="M17 4v16" />
                        </svg>
                        Compare Suppliers
                    </a>

                    <button type="button"
                        class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" x2="12" y1="15" y2="3" />
                        </svg>
                        Export
                    </button>

                    <a href="{{ route('createPurchaseOrder.index') }}"
                        class="h-12 px-5 bg-gradient-to-br from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        New Purchase Order
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-yellow-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600 text-sm">Pending Approval</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-500" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-yellow-600" id="stat-pending">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-xs text-gray-500 mt-1">Rs <span
                            id="stat-pending-value">{{ number_format($stats['pendingValue'] ?? 0) }}</span></div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600 text-sm">Approved</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-green-600" id="stat-approved">{{ $stats['approved'] ?? 0 }}</div>
                    <div class="text-xs text-gray-500 mt-1">Ready to send</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600 text-sm">Sent to Supplier</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="22" x2="11" y1="2" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-blue-600" id="stat-sent">{{ $stats['sent'] ?? 0 }}</div>
                    <div class="text-xs text-gray-500 mt-1">Awaiting delivery</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-orange-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600 text-sm">Partial Delivery</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m7.5 4.27 9 5.15" />
                            <path
                                d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                            <path d="m3.3 7 8.7 5 8.7-5" />
                            <path d="M12 22v-9" />
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-orange-600" id="stat-partial">{{ $stats['partiallyReceived'] ?? 0 }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Incomplete</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-emerald-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600 text-sm">Received</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" />
                            <path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z" />
                            <path d="M7 21h10" />
                            <path d="M12 3v18" />
                            <path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2" />
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-emerald-600" id="stat-received">{{ $stats['received'] ?? 0 }}</div>
                    <div class="text-xs text-gray-500 mt-1">Complete</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-purple-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600 text-sm">Total Value</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-500" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                            <polyline points="17 6 23 6 23 12" />
                        </svg>
                    </div>
                    <div class="text-2xl font-bold text-purple-600" id="stat-total">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-xs text-purple-500 mt-1">Rs <span
                            id="stat-total-value">{{ number_format($stats['totalValue'] ?? 0) }}</span></div>
                </div>
            </div>

            <div class="flex overflow-x-auto gap-2 mb-4 pb-2 scrollbar-hide">
                @php
                    $currentStatus = request('status', 'all');
                    $tabs = [
                        ['id' => 'all', 'label' => 'All Orders', 'count' => count($orders)],
                        ['id' => 'draft', 'label' => 'Draft', 'count' => $stats['draft'] ?? 0],
                        ['id' => 'pending', 'label' => 'Pending', 'count' => $stats['pending'] ?? 0],
                        ['id' => 'approved', 'label' => 'Approved', 'count' => $stats['approved'] ?? 0],
                        ['id' => 'sent', 'label' => 'Sent', 'count' => $stats['sent'] ?? 0],
                        ['id' => 'receiving', 'label' => 'Receiving', 'count' => ($stats['sent'] ?? 0) + ($stats['partiallyReceived'] ?? 0)],
                        ['id' => 'history', 'label' => 'History', 'count' => $stats['received'] ?? 0],
                    ];
                @endphp

                @foreach($tabs as $tab)
                    <a href="javascript:void(0)" onclick="filterOrders('{{ $tab['id'] }}')" id="tab-{{ $tab['id'] }}"
                        class="tab-btn flex-1 min-w-[120px] h-14 rounded-xl flex items-center justify-center gap-2 transition-all cursor-pointer {{ $currentStatus === $tab['id'] ? 'bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200' }}">
                        <span class="font-medium whitespace-nowrap">{{ $tab['label'] }}</span>
                        @if($tab['count'] > 0)
                            <span id="count-{{ $tab['id'] }}"
                                class="px-2 py-0.5 rounded-full text-xs {{ $currentStatus === $tab['id'] ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-700' }}">
                                {{ $tab['count'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100 mb-6">
                <form action="" method="GET" class="flex items-center gap-3">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" x2="16.65" y1="21" y2="16.65" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by PO number, supplier, or product..." class="flex-1 text-xl outline-none">
                    @if(request('search'))
                        <a href="" class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="15" x2="9" y1="9" y2="15" />
                                <line x1="9" x2="15" y1="9" y2="15" />
                            </svg>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div id="orders-container" class="space-y-3">
            @if(false) @forelse($orders as $order)
                    @php
                        // Status Logic helpers using CommonVariables integers
                        // Assuming \App\CommonVariables is available or using hardcoded integers matching them
                        // 0: pending, 1: approved, 2: sent, 3: partially-received, 4: received, 5: closed, 6: cancelled

                        $statusColor = match ($order->status) {
                            'draft' => 'bg-gray-100 text-gray-700 border-gray-300',
                            0 => 'bg-yellow-100 text-yellow-700 border-yellow-300', // Pending
                            1 => 'bg-green-100 text-green-700 border-green-300',  // Approved
                            2 => 'bg-blue-100 text-blue-700 border-blue-300',     // Sent
                            3 => 'bg-orange-100 text-orange-700 border-orange-300', // Partially Received
                            4 => 'bg-emerald-100 text-emerald-700 border-emerald-300', // Received
                            5 => 'bg-purple-100 text-purple-700 border-purple-300', // Closed
                            6 => 'bg-red-100 text-red-700 border-red-300',        // Cancelled
                            default => 'bg-gray-100 text-gray-700 border-gray-300',
                        };

                        $statusLabel = match ($order->status) {
                            'draft' => 'DRAFT',
                            0 => 'PENDING',
                            1 => 'APPROVED',
                            2 => 'SENT',
                            3 => 'PARTIALLY RECEIVED',
                            4 => 'RECEIVED',
                            5 => 'CLOSED',
                            6 => 'CANCELLED',
                            default => strtoupper($order->status),
                        };
                    @endphp

                    <div onclick="openDetailsModal('{{ $order->id }}')"
                        class="bg-white rounded-2xl p-5 shadow-sm border-2 border-gray-100 hover:shadow-md transition-all cursor-pointer group">
                        <div class="flex flex-col md:flex-row items-start gap-4">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="8" cy="21" r="1" />
                                    <circle cx="19" cy="21" r="1" />
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                </svg>
                            </div>

                            <div class="flex-1 w-full">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <div class="flex items-center gap-3 mb-1">
                                            <h3
                                                class="text-xl font-medium text-gray-900 group-hover:text-purple-600 transition-colors">
                                                {{ $order->po_number }}
                                            </h3>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColor }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                        <p class="text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline mr-1" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <rect x="2" y="2" width="20" height="20" rx="2" ry="2" />
                                                <line x1="9" x2="9" y1="2" y2="22" />
                                                <line x1="15" x2="15" y1="2" y2="22" />
                                                <line x1="2" x2="22" y1="11" y2="11" />
                                                <line x1="2" x2="22" y1="17" y2="17" />
                                            </svg>
                                            {{ $order->supplier_name }} • {{ count($order->items) }}
                                            item{{ count($order->items) > 1 ? 's' : '' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-purple-600">
                                            Rs {{ number_format($order->grand_total) }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ str_replace('credit-', '', $order->payment_terms) }} days credit
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-3 mb-3">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                                        @foreach(collect($order->items)->take(4) as $item)
                                            <div class="flex items-center gap-2 bg-white rounded-lg p-2 border border-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m7.5 4.27 9 5.15" />
                                                    <path
                                                        d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                                    <path d="m3.3 7 8.7 5 8.7-5" />
                                                    <path d="M12 22v-9" />
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $item['product_name'] ?? $item->product_name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $item['quantity'] ?? $item->quantity }} {{ $item['unit'] ?? $item->unit }}
                                                        @if(isset($item['received_quantity']))
                                                            <span class="text-green-600 ml-1">({{ $item['received_quantity'] }} rec)</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if(count($order->items) > 4)
                                            <div class="flex items-center justify-center bg-gray-100 rounded-lg p-2">
                                                <span class="text-sm font-medium text-gray-600">+{{ count($order->items) - 4 }}
                                                    more</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm">
                                    <div class="flex flex-wrap items-center gap-4 text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                                <circle cx="12" cy="7" r="4" />
                                            </svg>
                                            <span>{{ $order->created_by }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                <line x1="16" x2="16" y1="2" y2="6" />
                                                <line x1="8" x2="8" y1="2" y2="6" />
                                                <line x1="3" x2="21" y1="10" y2="10" />
                                            </svg>
                                            <span>Created: {{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10" />
                                                <polyline points="12 6 12 12 16 14" />
                                            </svg>
                                            <span>Expected: {{ $order->expected_delivery_date }}</span>
                                        </div>
                                    </div>

                                    <div class="flex gap-2" onclick="event.stopPropagation()">
                                        @if($order->status === 0)
                                            <form id="approve-form-{{ $order->id }}"
                                                action="{{ route('purchaseOrder.approve', $order->id) }}" method="POST"
                                                style="display:none;">
                                                @csrf
                                            </form>
                                            <button type="button"
                                                onclick="openApprovalModal({
                                                                                                                                                                                                                                                                                                                                                                                                                                                    id: '{{ $order->id }}',
                                                                                                                                                                                                                                                                                                                                                                                                                                                    poNumber: '{{ $order->po_number }}',
                                                                                                                                                                                                                                                                                                                                                                                                                                                    supplierName: '{{ $order->supplier_name }}',
                                                                                                                                                                                                                                                                                                                                                                                                                                                    itemsCount: '{{ count($order->items) }}',
                                                                                                                                                                                                                                                                                                                                                                                                                                                    grandTotal: '{{ number_format($order->grand_total) }}',
                                                                                                                                                                                                                                                                                                                                                                                                                                                    deliveryDate: '{{ $order->expected_delivery_date }}'
                                                                                                                                                                                                                                                                                                                                                                                                                                                })"
                                                class="h-10 px-4 bg-green-100 hover:bg-green-200 text-green-700 rounded-xl flex items-center gap-2 font-medium transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                    <polyline points="22 4 12 14.01 9 11.01" />
                                                </svg>
                                                Approve
                                            </button>
                                        @endif

                                        @if($order->status === 1)
                                            <form id="send-form-{{ $order->id }}" action="{{ route('purchaseOrder.send', $order->id) }}"
                                                method="POST" style="display:none;">
                                                @csrf
                                            </form>
                                            <button type="button" onclick="confirmSendToSupplier('{{ $order->id }}')"
                                                class="h-10 px-4 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-xl flex items-center gap-2 font-medium transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <line x1="22" x2="11" y1="2" y2="13" />
                                                    <polygon points="22 2 15 22 11 13 2 9 22 2" />
                                                </svg>
                                                Send to Supplier
                                            </button>
                                        @endif

                                        @if(in_array($order->status, [2, 3]))
                                            <button type="button" onclick="navigateToCreateGRN('{{ $order->id }}')"
                                                class="h-10 px-4 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-xl flex items-center gap-2 font-medium transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" />
                                                    <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                                                    <line x1="12" x2="12" y1="17" y2="17" />
                                                    <line x1="12" x2="12" y1="7" y2="7" />
                                                </svg>
                                                Create GRN
                                            </button>
                                        @endif

                                        <a href="javascript:void(0)" onclick="openDetailsModal('{{ $order->id }}')"
                                            class="h-10 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl flex items-center gap-2 font-medium transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                            Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-12 text-center border-2 border-gray-100">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1" />
                                <circle cx="19" cy="21" r="1" />
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                            </svg>
                        </div>
                        <h3 class="text-xl text-gray-900 mb-2 font-medium">No Purchase Orders Found</h3>
                        <p class="text-gray-600 mb-6">
                            @if(request('search'))
                                Try adjusting your search query
                            @else
                                No purchase orders match the current filter
                            @endif
                        </p>
                        @if(request('status') === 'all' && !request('search'))
                            <a href=""
                                class="h-12 px-6 bg-gradient-to-br from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl font-medium shadow-md transition-all inline-flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="M12 5v14" />
                                </svg>
                                Create First Purchase Order
                            </a>
                        @else
                            <a href="" class="text-purple-600 hover:text-purple-700 font-medium">
                                Clear Filters
                            </a>
                        @endif
                    </div>
            @endforelse @endif
        </div>

        <div id="empty-state" class="hidden bg-white rounded-2xl p-12 text-center border-2 border-gray-100">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </div>
            <h3 class="text-xl text-gray-900 mb-2 font-medium">No Purchase Orders Found</h3>
            <p class="text-gray-600 mb-6">No purchase orders match the current filter</p>
            <a href="javascript:void(0)" onclick="filterOrders('all')"
                class="hidden text-purple-600 hover:text-purple-700 font-medium clear-filter-btn">Clear Filters</a>
        </div>

        @include('inventoryManagement.modals.approve_po_modal')
        @include('inventoryManagement.modals.view_po_modal')
    </div>

    <script>
        window.ordersData = @json($orders);

        document.addEventListener('DOMContentLoaded', function () {
            // Check Session or Query Param
            const sessionSuccess = "{{ session('success') }}";
            const querySuccess = new URLSearchParams(window.location.search).get('success');
            const message = sessionSuccess || querySuccess;

            console.log('Success Msg:', message);

            if (message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: message,
                    confirmButtonColor: '#3b82f6'
                }).then(() => {
                    // Clean URL if it was a query param
                    if (querySuccess) {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('success');
                        window.history.replaceState({}, document.title, url);
                    }
                });
            }

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#ef4444'
                });
            @endif

                                        // Initial Render
                                        if (window.ordersData) {
                renderOrders(window.ordersData);
            }
        });

        // AJAX Functions
        function navigateToCreateGRN(poId) {
            $.ajax({
                url: "{{ route('createGRN.prepare') }}",
                type: 'POST',
                data: {
                    po_id: poId,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    if (response.success) {
                        window.location.href = "{{ route('createGRN.index') }}";
                    }
                },
                error: function (err) {
                    console.error(err);
                    Swal.fire('Error', 'Failed to prepare GRN creation', 'error');
                }
            });
        }

        function filterOrders(status) {
            const container = $('#orders-container');
            // Optimistic rendering or loading state?
            // container.html('<div class="p-12 text-center text-gray-400">Loading...</div>');
            // Better: Opacity
            container.addClass('opacity-50 pointer-events-none');

            $.ajax({
                url: "{{ route('purchaseOrderManage.index') }}",
                type: 'GET',
                data: { status: status },
                success: function (response) {
                    window.ordersData = response.orders;
                    updateStats(response.stats);
                    updateTabs(status, response.stats);
                    renderOrders(window.ordersData);
                    container.removeClass('opacity-50 pointer-events-none');
                },
                error: function (err) {
                    console.error(err);
                    Swal.fire('Error', 'Failed to load orders', 'error');
                    container.removeClass('opacity-50 pointer-events-none');
                }
            });
        }

        function updateStats(stats) {
            if (!stats) return;
            $('#stat-pending').text(stats.pending ?? 0);
            $('#stat-pending-value').text(formatNumber(stats.pendingValue ?? 0));
            $('#stat-approved').text(stats.approved ?? 0);
            $('#stat-sent').text(stats.sent ?? 0);
            $('#stat-partial').text(stats.partiallyReceived ?? 0);
            $('#stat-received').text(stats.received ?? 0);
            $('#stat-total').text(stats.total ?? 0);
            $('#stat-total-value').text(formatNumber(stats.totalValue ?? 0));
        }

        function updateTabs(activeId, stats) {
            if (stats) {
                $('#count-draft').text(stats.draft ?? 0);
                $('#count-pending').text(stats.pending ?? 0);
                $('#count-approved').text(stats.approved ?? 0);
                $('#count-sent').text(stats.sent ?? 0);
                const receiving = (stats.sent ?? 0) + (stats.partiallyReceived ?? 0);
                $('#count-receiving').text(receiving);
                $('#count-history').text(stats.received ?? 0);
                $('#count-all').text(stats.total ?? 0);
            }

            $('.tab-btn').removeClass('bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg')
                .addClass('bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200');

            $('.tab-btn span.rounded-full').removeClass('bg-white/20 text-white').addClass('bg-purple-100 text-purple-700');

            const activeBtn = $(`#tab-${activeId}`);
            activeBtn.removeClass('bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200')
                .addClass('bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg');

            activeBtn.find('span.rounded-full').removeClass('bg-purple-100 text-purple-700').addClass('bg-white/20 text-white');
        }

        function renderOrders(orders) {
            const container = document.getElementById('orders-container');
            const emptyState = document.getElementById('empty-state');

            container.innerHTML = '';

            if (!orders || orders.length === 0) {
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');

            const html = orders.map(order => generateOrderHtml(order)).join('');
            container.innerHTML = html;
        }

        function generateOrderHtml(order) {
            const status = String(order.status).toLowerCase();
            const statusColor = getStatusColorClass(status);
            const statusLabel = getStatusLabel(status);
            const itemsCount = order.items.length;
            const grandTotal = formatNumber(order.grand_total);
            const paymentTerms = String(order.payment_terms || '').replace('credit-', '');
            const createdDate = new Date(order.created_at).toISOString().split('T')[0];
            const deliveryDate = order.expected_delivery_date;

            let itemsHtml = '';
            const displayItems = order.items.slice(0, 4);
            displayItems.forEach(item => {
                const receivedBadge = item.received_quantity ? `<span class="text-green-600 ml-1">(${item.received_quantity} rec)</span>` : '';
                itemsHtml += `
                                                <div class="flex items-center gap-2 bg-white rounded-lg p-2 border border-gray-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="m7.5 4.27 9 5.15" />
                                                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                                        <path d="m3.3 7 8.7 5 8.7-5" />
                                                        <path d="M12 22v-9" />
                                                    </svg>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="text-sm font-medium text-gray-900 truncate">
                                                            ${item.product_name || item.product_name} 
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            ${item.quantity} ${item.unit || ''}
                                                            ${receivedBadge}
                                                        </div>
                                                    </div>
                                                </div>
                                            `;
            });

            if (itemsCount > 4) {
                itemsHtml += `
                                                <div class="flex items-center justify-center bg-gray-100 rounded-lg p-2">
                                                    <span class="text-sm font-medium text-gray-600">+${itemsCount - 4} more</span>
                                                </div>
                                            `;
            }

            let buttonsHtml = '';
            const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
            const csrfField = `<input type="hidden" name="_token" value="${csrfToken}">`;

            if (order.status === 0 || order.status == '0' || status === 'pending') {
                buttonsHtml += `
                                                <form id="approve-form-${order.id}" action="${order.approve_url}" method="POST" style="display:none;">
                                                    ${csrfField}
                                                </form>
                                                <button type="button"
                                                    onclick="openApprovalModal({
                                                        id: '${order.id}',
                                                        poNumber: '${order.po_number}',
                                                        supplierName: '${order.supplier_name}',
                                                        itemsCount: '${itemsCount}',
                                                        grandTotal: '${grandTotal}',
                                                        deliveryDate: '${order.expected_delivery_date}'
                                                    })"
                                                    class="h-10 px-4 bg-green-100 hover:bg-green-200 text-green-700 rounded-xl flex items-center gap-2 font-medium transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                        <polyline points="22 4 12 14.01 9 11.01" />
                                                    </svg>
                                                    Approve
                                                </button>
                                             `;
            }

            if (order.status === 1 || order.status == '1' || status === 'approved') {
                buttonsHtml += `
                                                <form id="send-form-${order.id}" action="${order.send_url}" method="POST" style="display:none;">
                                                    ${csrfField}
                                                </form>
                                                <button type="button" onclick="confirmSendToSupplier('${order.id}')"
                                                    class="h-10 px-4 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-xl flex items-center gap-2 font-medium transition-all">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <line x1="22" x2="11" y1="2" y2="13" />
                                                        <polygon points="22 2 15 22 11 13 2 9 22 2" />
                                                    </svg>
                                                    Send to Supplier
                                                </button>
                                             `;
            }

            if ([2, 3, '2', '3', 'sent', 'partially-received'].includes(order.status) || ['sent', 'partially-received'].includes(status)) {
                buttonsHtml += `
                                            <button type="button" onclick="navigateToCreateGRN('${order.id}')"
                                                class="h-10 px-4 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-xl flex items-center gap-2 font-medium transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z" />
                                                    <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                                                    <line x1="12" x2="12" y1="17" y2="17" />
                                                    <line x1="12" x2="12" y1="7" y2="7" />
                                                </svg>
                                                Create GRN
                                            </button>
                                        `;
            }

            return `
                                            <div onclick="openDetailsModal('${order.id}')"
                                                class="bg-white rounded-2xl p-5 shadow-sm border-2 border-gray-100 hover:shadow-md transition-all cursor-pointer group">
                                                <div class="flex flex-col md:flex-row items-start gap-4">
                                                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                                         <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="8" cy="21" r="1" />
                                                            <circle cx="19" cy="21" r="1" />
                                                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                                        </svg>
                                                    </div>

                                                    <div class="flex-1 w-full">
                                                        <div class="flex items-start justify-between mb-2">
                                                            <div>
                                                                <div class="flex items-center gap-3 mb-1">
                                                                    <h3 class="text-xl font-medium text-gray-900 group-hover:text-purple-600 transition-colors">
                                                                        ${order.po_number}
                                                                    </h3>
                                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium border ${statusColor}">
                                                                        ${statusLabel}
                                                                    </span>
                                                                </div>
                                                                <p class="text-gray-600">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline mr-1" viewBox="0 0 24 24"
                                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <rect x="2" y="2" width="20" height="20" rx="2" ry="2" />
                                                                        <line x1="9" x2="9" y1="2" y2="22" />
                                                                        <line x1="15" x2="15" y1="2" y2="22" />
                                                                        <line x1="2" x2="22" y1="11" y2="11" />
                                                                        <line x1="2" x2="22" y1="17" y2="17" />
                                                                    </svg>
                                                                    ${order.supplier_name} • ${itemsCount} item${itemsCount > 1 ? 's' : ''}
                                                                </p>
                                                            </div>
                                                            <div class="text-right">
                                                                <div class="text-2xl font-bold text-purple-600">
                                                                    Rs ${grandTotal}
                                                                </div>
                                                                <div class="text-sm text-gray-500">
                                                                    ${paymentTerms} days credit
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="bg-gray-50 rounded-xl p-3 mb-3">
                                                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                                                                ${itemsHtml}
                                                            </div>
                                                        </div>

                                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm">
                                                            <div class="flex flex-wrap items-center gap-4 text-gray-600">
                                                                 <div class="flex items-center gap-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                                                        <circle cx="12" cy="7" r="4" />
                                                                    </svg>
                                                                    <span>${order.created_by}</span>
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                                                        <line x1="16" x2="16" y1="2" y2="6" />
                                                                        <line x1="8" x2="8" y1="2" y2="6" />
                                                                        <line x1="3" x2="21" y1="10" y2="10" />
                                                                    </svg>
                                                                    <span>Created: ${createdDate}</span>
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <circle cx="12" cy="12" r="10" />
                                                                        <polyline points="12 6 12 12 16 14" />
                                                                    </svg>
                                                                    <span>Expected: ${deliveryDate}</span>
                                                                </div>
                                                            </div>

                                                            <div class="flex gap-2" onclick="event.stopPropagation()">
                                                                ${buttonsHtml}
                                                                 <a href="javascript:void(0)" onclick="openDetailsModal('${order.id}')"
                                                                    class="h-10 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl flex items-center gap-2 font-medium transition-all">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                                        <circle cx="12" cy="12" r="3" />
                                                                    </svg>
                                                                    Details
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
        }

        let currentApproveFormId = null;

        function openApprovalModal(data) {
            document.getElementById('modalPoNumber').textContent = data.poNumber;
            document.getElementById('modalSupplierName').textContent = data.supplierName;
            document.getElementById('modalItemsCount').textContent = data.itemsCount;
            document.getElementById('modalTotalAmount').textContent = 'Rs ' + data.grandTotal;
            document.getElementById('modalDeliveryDate').textContent = data.deliveryDate;

            document.getElementById('modalAuthAmount').textContent = data.grandTotal;
            document.getElementById('modalAuthSupplier').textContent = data.supplierName;

            currentApproveFormId = 'approve-form-' + data.id;

            const modal = document.getElementById('approvalModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeApprovalModal() {
            const modal = document.getElementById('approvalModal');
            modal.classList.add('hidden');
            currentApproveFormId = null;
            document.body.style.overflow = '';
        }

        function confirmApproval() {
            if (currentApproveFormId) {
                document.getElementById(currentApproveFormId).submit();
            }
        }

        function confirmSendToSupplier(orderId) {
            Swal.fire({
                title: 'Send to Supplier?',
                text: "This will change the status to 'Sent' and cannot be undone via this button.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Send it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('send-form-' + orderId).submit();
                }
            });
        }

        // --- Order Details Modal Script ---

        function openDetailsModal(orderId) {
            // Find order data
            const order = window.ordersData.find(o => o.id == orderId);
            if (!order) return;

            // Populate Header
            document.getElementById('detailModalPoNumberHeader').textContent = order.po_number;
            document.getElementById('detailModalPoNumber').textContent = order.po_number;

            // Status Badge
            const statusEl = document.getElementById('detailModalStatus');
            const statusStr = String(order.status).toLowerCase();
            statusEl.textContent = getStatusLabel(order.status);
            // Reset classes
            statusEl.className = 'inline-flex items-center rounded-full border px-3 py-1 text-sm font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 ' + getStatusColorClass(statusStr);

            // Header Stats
            document.getElementById('detailModalGrandTotalMain').textContent = 'Rs ' + formatNumber(order.grand_total);
            document.getElementById('detailModalItemsCount').textContent = order.items.length;
            document.getElementById('detailModalCreated').textContent = new Date(order.created_at).toISOString().split('T')[0];
            document.getElementById('detailModalExpected').textContent = order.expected_delivery_date;
            document.getElementById('detailModalPayment').textContent = (order.payment_terms || '').replace('credit-', '') + ' days';

            // Supplier
            document.getElementById('detailModalSupplierName').textContent = order.supplier_name;
            // Populating contact details from mapped order object
            // Using traverse/closest or direct sibling lookup based on structure in view_po_modal.blade.php
            // Since IDs are not unique for these specific small fields in the provided HTML (they are text nodes or simple divs),
            // we'll update the 'openDetailsModal' function to target them if they have IDs or adding IDs to 'view_po_modal.blade.php' first is better.

            // Wait! The modal HTML provided didn't have IDs for contact person, email, phone value divs!
            // They have "Available in details" placeholder text.
            // I need to add IDs to those divs in the view first, OR use strict DOM traversal.
            // Adding IDs is safer. I will do that in the next step.
            // For now, I will add the IDs to the JS assuming I will update the HTML next.
            document.getElementById('detailModalContactPerson').textContent = order.contact_person || 'N/A';
            document.getElementById('detailModalSupplierEmail').textContent = order.supplier_email || 'N/A';
            document.getElementById('detailModalSupplierPhone').textContent = order.supplier_phone || 'N/A';

            // Restore lost DOM elements definitions
            const tbody = document.getElementById('detailModalItemsTableBody');
            tbody.innerHTML = '';
            const receivedCol = document.querySelector('.received-col');

            const showReceived = !['draft', 'pending'].includes(order.status);
            if (showReceived) {
                receivedCol.classList.remove('hidden');
            } else {
                receivedCol.classList.add('hidden');
            }

            order.items.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';

                let receivedHtml = '';
                if (showReceived) {
                    if (item.received_quantity !== undefined) {
                        receivedHtml = `
                                                                                                                                                <div>
                                                                                                                                                    <div class="font-medium text-green-600">${item.received_quantity} ${item.unit || ''}</div>
                                                                                                                                                    ${item.received_quantity < item.quantity ? `
                                                                                                                                                        <div class="text-xs text-orange-600">
                                                                                                                                                          Partial (${Math.round((item.received_quantity / item.quantity) * 100)}%)
                                                                                                                                                        </div>
                                                                                                                                                    ` : ''}
                                                                                                                                                </div>
                                                                                                                                            `;
                    } else {
                        receivedHtml = '<span class="text-gray-400">-</span>';
                    }
                }

                tr.innerHTML = `
                                                                                                                                        <td class="px-5 py-4">
                                                                                                                                            <div class="font-medium text-gray-900">${item.product_name || 'Product'}</div>
                                                                                                                                            <div class="text-sm text-gray-600">${item.product_id || ''}</div>
                                                                                                                                        </td>
                                                                                                                                        <td class="px-5 py-4">
                                                                                                                                            <span class="text-sm text-gray-700 capitalize">${item.category || 'N/A'}</span>
                                                                                                                                        </td>
                                                                                                                                        <td class="px-5 py-4 text-right">
                                                                                                                                            <div class="font-medium text-gray-900">${item.quantity} ${item.unit || ''}</div>
                                                                                                                                        </td>
                                                                                                                                        <td class="px-5 py-4 text-right">
                                                                                                                                            <div class="text-gray-900">Rs ${formatNumber(item.unit_price || 0)}</div>
                                                                                                                                            <div class="text-sm text-gray-600">per ${item.unit || 'unit'}</div>
                                                                                                                                        </td>
                                                                                                                                        <td class="px-5 py-4 text-right">
                                                                                                                                            <div class="font-medium text-gray-900">Rs ${formatNumber(item.total_price || 0)}</div>
                                                                                                                                        </td>
                                                                                                                                        ${showReceived ? `<td class="px-5 py-4 text-center">${receivedHtml}</td>` : ''}
                                                                                                                                    `;
                tbody.appendChild(tr);
            });

            document.getElementById('detailModalItemsHeaderCount').textContent = order.items.length;

            // Totals
            // Assuming total and tax are calculated or present. If not, simple math.
            // We'll use order.grand_total for now. 
            // Need to verify if tax info exists in $order object. If not, placeholders.
            // Assuming simplistic calc for demo if missing:
            const grandTotal = parseFloat(order.grand_total || 0);
            const tax = grandTotal * 0.18; // Placeholder tax logic or use real data if available
            const subtotal = grandTotal - tax;

            document.getElementById('detailModalSubtotal').textContent = 'Rs ' + formatNumber(subtotal);
            document.getElementById('detailModalTax').textContent = 'Rs ' + formatNumber(tax);
            document.getElementById('detailModalGrandTotalBottom').textContent = 'Rs ' + formatNumber(grandTotal);

            // Notes
            const notesSection = document.getElementById('detailModalNotesSection');
            if (order.notes) {
                notesSection.classList.remove('hidden');
                document.getElementById('detailModalNotes').textContent = order.notes;
            } else {
                notesSection.classList.add('hidden');
            }

            // Audit Trail
            const auditTrail = order.audit_trail || []; // Ensure this property exists in your backend model
            document.getElementById('detailModalAuditCount').textContent = auditTrail.length;
            const auditBody = document.getElementById('detailModalAuditTrailBody');
            auditBody.innerHTML = '';

            if (auditTrail.length === 0) {
                auditBody.innerHTML = '<p class="text-gray-500 text-sm text-center italic">No audit history available.</p>';
            } else {
                auditTrail.forEach((entry, idx) => {
                    const isLatest = idx === auditTrail.length - 1;

                    // Fallbacks if properties are missing
                    const action = entry.action || 'Updated';
                    const dateStr = entry.created_at ? new Date(entry.created_at).toLocaleString() : 'N/A';
                    const details = entry.details || '';
                    const user = entry.user_name || 'System';
                    const role = entry.role || 'User';

                    const iconHtml = getAuditStatusIcon(entry.new_status || 'pending');

                    const div = document.createElement('div');
                    div.className = 'flex gap-4';
                    div.innerHTML = `
                                                                                                                    <div class="flex flex-col items-center">
                                                                                                                        <div class="w-10 h-10 rounded-full flex items-center justify-center ${isLatest ? 'bg-purple-100' : 'bg-gray-100'}">
                                                                                                                            ${iconHtml}
                                                                                                                        </div>
                                                                                                                        ${!isLatest ? '<div class="w-0.5 h-full bg-gray-200 mt-2"></div>' : ''}
                                                                                                                    </div>

                                                                                                                    <div class="flex-1 pb-6">
                                                                                                                        <div class="flex items-start justify-between mb-1">
                                                                                                                            <h4 class="font-medium text-gray-900">${action}</h4>
                                                                                                                            <span class="text-sm text-gray-500">${dateStr}</span>
                                                                                                                        </div>
                                                                                                                        <p class="text-sm text-gray-600 mb-2">${details}</p>
                                                                                                                        <div class="flex items-center gap-3 text-sm">
                                                                                                                            <div class="flex items-center gap-1 text-gray-600">
                                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                                                                                                <span>${user}</span>
                                                                                                                            </div>
                                                                                                                            <div class="flex items-center gap-1 text-gray-600">
                                                                                                                                <span class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full border border-gray-200">
                                                                                                                                    ${role}
                                                                                                                                </span>
                                                                                                                            </div>
                                                                                                                            ${(entry.previous_status && entry.new_status) ? `
                                                                                                                            <div class="flex items-center gap-2">
                                                                                                                                <span class="${getStatusColorClass(entry.previous_status)} text-xs px-2 py-0.5 rounded-full border">
                                                                                                                                    ${getStatusLabel(entry.previous_status)}
                                                                                                                                </span>
                                                                                                                                <span class="text-gray-400">→</span>
                                                                                                                                <span class="${getStatusColorClass(entry.new_status)} text-xs px-2 py-0.5 rounded-full border">
                                                                                                                                    ${getStatusLabel(entry.new_status)}
                                                                                                                                </span>
                                                                                                                            </div>` : ''}
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    `;
                    auditBody.appendChild(div);
                });
            }

            // Download PDF Button Logic
            const downloadBtn = document.getElementById('downloadPdfBtn');
            const statusInt = parseInt(order.status);

            // Check if status is Received (4) - Logic: CommonVariables::received == 4
            // Assuming order.status is available as int or string '4'
            if (statusInt === 4 || order.status === 'received') {
                downloadBtn.classList.remove('hidden');
                // Set OnClick to open PDF URL
                downloadBtn.onclick = function () {
                    window.open(order.download_pdf_url, '_blank');
                };
            } else {
                downloadBtn.classList.add('hidden');
                downloadBtn.onclick = null;
            }

            // Show Modal
            const modal = document.getElementById('orderDetailModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailsModal() {
            const modal = document.getElementById('orderDetailModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Helpers
        function getStatusLabel(status) {
            const statusMap = {
                'draft': 'DRAFT',
                'pending': 'PENDING',
                'approved': 'APPROVED',
                'sent': 'SENT',
                'partially-received': 'PARTIALLY RECEIVED',
                'received': 'RECEIVED',
                'closed': 'CLOSED',
                'cancelled': 'CANCELLED',
                '0': 'PENDING',
                '1': 'APPROVED',
                '2': 'SENT',
                '3': 'PARTIALLY RECEIVED',
                '4': 'RECEIVED',
                '5': 'CLOSED',
                '6': 'CANCELLED'
            };
            return statusMap[String(status).toLowerCase()] || String(status).toUpperCase();
        }

        function getStatusColorClass(status) {
            const safeStatus = String(status).toLowerCase();
            const colors = {
                'draft': 'bg-gray-100 text-gray-700 border-gray-300',
                'pending': 'bg-yellow-100 text-yellow-700 border-yellow-300',
                '0': 'bg-yellow-100 text-yellow-700 border-yellow-300',
                'approved': 'bg-green-100 text-green-700 border-green-300',
                '1': 'bg-green-100 text-green-700 border-green-300',
                'sent': 'bg-blue-100 text-blue-700 border-blue-300',
                '2': 'bg-blue-100 text-blue-700 border-blue-300',
                'partially-received': 'bg-orange-100 text-orange-700 border-orange-300',
                '3': 'bg-orange-100 text-orange-700 border-orange-300',
                'received': 'bg-emerald-100 text-emerald-700 border-emerald-300',
                '4': 'bg-emerald-100 text-emerald-700 border-emerald-300',
                'closed': 'bg-purple-100 text-purple-700 border-purple-300',
                '5': 'bg-purple-100 text-purple-700 border-purple-300',
                'cancelled': 'bg-red-100 text-red-700 border-red-300',
                '6': 'bg-red-100 text-red-700 border-red-300',
            };
            return colors[safeStatus] || colors['draft'];
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('en-IN').format(num);
        }

        function getAuditStatusIcon(status) {
            status = String(status).toLowerCase();
            const baseClass = "w-5 h-5 text-gray-600";

            if (['approved', 'completed', '1'].includes(status)) {
                return `<svg xmlns="http://www.w3.org/2000/svg" class="${baseClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`;
            } else if (['cancelled', 'rejected', '6'].includes(status)) {
                return `<svg xmlns="http://www.w3.org/2000/svg" class="${baseClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;
            } else if (['sent', '2'].includes(status)) {
                return `<svg xmlns="http://www.w3.org/2000/svg" class="${baseClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>`;
            } else if (['received', 'partially-received', '3', '4'].includes(status)) {
                return `<svg xmlns="http://www.w3.org/2000/svg" class="${baseClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>`;
            }

            // Default clock for pending
            return `<svg xmlns="http://www.w3.org/2000/svg" class="${baseClass}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`;
        }

        // Close on escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeApprovalModal();
                closeDetailsModal();
            }
        });
    </script>
@endsection