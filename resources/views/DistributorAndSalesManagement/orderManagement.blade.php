@extends('layouts.app')

@section('title', 'Order Management')

@php
    // Calculate summary from real orders
    $ordersCollection = collect($orders);

    $summary = [
        'totalOrders' => $ordersCollection->count(),
        'totalValue' => $ordersCollection->sum(function ($order) {
            return (float) str_replace(',', '', $order['grand_total']);
        }),
        'inProduction' => $ordersCollection->where('status', 'in-production')->count(),
        'readyForPickup' => $ordersCollection->where('status', 'ready-for-pickup')->count(),
        'outForDelivery' => $ordersCollection->where('status', 'out-for-delivery')->count(),
        'ordersToday' => $ordersCollection
            ->filter(function ($order) {
                return \Carbon\Carbon::parse($order['created_at'])->isToday();
            })
            ->count(),
        'ordersTodayValue' => $ordersCollection
            ->filter(function ($order) {
                return \Carbon\Carbon::parse($order['created_at'])->isToday();
            })
            ->sum(function ($order) {
                return (float) str_replace(',', '', $order['grand_total']);
            }),
        'ordersByStatus' => $ordersCollection->groupBy('status')->map->count()->toArray(),
    ];

    // Helper functions for display
    function getChannelConfig($orderType)
    {
        $configs = [
            1 => ['color' => 'bg-blue-100 text-blue-700 border-blue-300', 'label' => 'POS Pickup', 'icon' => 'store'],
            2 => [
                'color' => 'bg-purple-100 text-purple-700 border-purple-300',
                'label' => 'Special Order',
                'icon' => 'gift',
            ],
            3 => [
                'color' => 'bg-orange-100 text-orange-700 border-orange-300',
                'label' => 'Scheduled',
                'icon' => 'repeat',
            ],
            4 => [
                'color' => 'bg-teal-100 text-teal-700 border-teal-300',
                'label' => 'Agent Order',
                'icon' => 'agent',
            ],
        ];
        return $configs[$orderType] ?? $configs[1];
    }

    function getStatusConfig($status)
    {
        $configs = [
            'draft' => [
                'color' => 'bg-gray-100 text-gray-700 border-gray-300',
                'label' => 'Draft',
                'icon' => 'file-text',
            ],
            'pending-approval' => [
                'color' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                'label' => 'Pending Approval',
                'icon' => 'clock',
            ],
            'approved' => [
                'color' => 'bg-blue-100 text-blue-700 border-blue-300',
                'label' => 'Approved',
                'icon' => 'check-circle',
            ],
            'in-production' => [
                'color' => 'bg-indigo-100 text-indigo-700 border-indigo-300',
                'label' => 'In Production',
                'icon' => 'chef-hat',
            ],
            'ready-for-pickup' => [
                'color' => 'bg-teal-100 text-teal-700 border-teal-300',
                'label' => 'Ready',
                'icon' => 'package',
            ],
            'out-for-delivery' => [
                'color' => 'bg-cyan-100 text-cyan-700 border-cyan-300',
                'label' => 'Out for Delivery',
                'icon' => 'truck',
            ],
            'completed' => [
                'color' => 'bg-green-100 text-green-700 border-green-300',
                'label' => 'Completed',
                'icon' => 'check-circle',
            ],
            'cancelled' => ['color' => 'bg-red-100 text-red-700 border-red-300', 'label' => 'Cancelled', 'icon' => 'x'],
            'on-hold' => [
                'color' => 'bg-orange-100 text-orange-700 border-orange-300',
                'label' => 'On Hold',
                'icon' => 'alert-triangle',
            ],
        ];
        return $configs[$status] ?? $configs['draft'];
    }
@endphp

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 p-4 md:p-6 font-sans text-slate-800">

        {{-- HEADER --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="text-white">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Order Management</h1>
                        <p class="text-gray-600">Manage pickup orders, special orders & scheduled production</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="openCreateOrderModal('pos-pickup')"
                        class="hidden h-12 px-5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl flex items-center gap-2 shadow-lg transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7" />
                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" />
                            <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4" />
                            <path d="M2 7h20" />
                            <path
                                d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2 2 0 0 1 4 12v0a2 2 0 0 1-2-2V7" />
                        </svg>
                        <span class="hidden sm:inline">POS Pickup</span>
                    </button>
                    <button onclick="openCreateOrderModal('special-order')"
                        class="h-12 px-5 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl flex items-center gap-2 shadow-lg transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <rect x="3" y="8" width="18" height="4" rx="1" />
                            <path d="M12 8v13" />
                            <path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7" />
                            <path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5" />
                        </svg>
                        <span class="hidden sm:inline">Special Order</span>
                    </button>
                    <button onclick="openCreateOrderModal('scheduled-production')"
                        class="h-12 px-5 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl flex items-center gap-2 shadow-lg transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m17 2 4 4-4 4" />
                            <path d="M3 11v-1a4 4 0 0 1 4-4h14" />
                            <path d="m7 22-4-4 4-4" />
                            <path d="M21 13v1a4 4 0 0 1-4 4H3" />
                        </svg>
                        <span class="hidden sm:inline">Scheduled</span>
                    </button>
                </div>
            </div>

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                {{-- Total --}}
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-purple-600">
                                <circle cx="8" cy="21" r="1" />
                                <circle cx="19" cy="21" r="1" />
                                <path
                                    d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-gray-600 mb-1">Total Orders</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['totalOrders'] }}</p>
                    <p class="text-sm text-gray-500">Rs {{ number_format($summary['totalValue'], 2) }}</p>
                </div>

                {{-- In Production --}}
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-indigo-600">
                                <path
                                    d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z" />
                                <line x1="6" y1="17" x2="18" y2="17" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-gray-600 mb-1">In Production</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['inProduction'] }}</p>
                    <p class="text-sm text-gray-500">orders</p>
                </div>

                {{-- Ready --}}
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-teal-600">
                                <path d="m7.5 4.27 9 5.15" />
                                <path
                                    d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                <path d="m3.3 7 8.7 5 8.7-5" />
                                <path d="M12 22v-9" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-gray-600 mb-1">Ready</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['readyForPickup'] }}</p>
                    <p class="text-sm text-gray-500">for pickup</p>
                </div>

                {{-- Out for Delivery --}}
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-cyan-600">
                                <rect x="1" y="3" width="15" height="13" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-gray-600 mb-1">Out for Delivery</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['outForDelivery'] }}</p>
                    <p class="text-sm text-gray-500">orders</p>
                </div>

                {{-- Today's Orders --}}
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-green-600">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                <polyline points="17 6 23 6 23 12" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-gray-600 mb-1">Today's Orders</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['ordersToday'] }}</p>
                    <p class="text-sm text-gray-500">Rs {{ number_format($summary['ordersTodayValue'], 2) }}</p>
                </div>
            </div>

            {{-- FILTERS: Channel --}}
            <div class="bg-white rounded-2xl p-2 shadow-sm border border-gray-100 flex gap-2 overflow-x-auto mb-4"
                id="channel-filters">
                <button onclick="filterData('channel', 'all', this)"
                    class="filter-btn active flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                    </svg>
                    <span>All Channels</span>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-white/20">{{ $summary['totalOrders'] }}</span>
                </button>
                <button onclick="filterData('channel', 'pos-pickup', this)"
                    class="filter-btn hidden flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 text-gray-600 hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7" />
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" />
                        <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4" />
                        <path d="M2 7h20" />
                        <path
                            d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2 2 0 0 1 4 12v0a2 2 0 0 1-2-2V7" />
                    </svg>
                    <span>POS Pickup</span>
                </button>
                <button onclick="filterData('channel', 'agent-order', this)"
                    class="filter-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 text-gray-600 hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                    <span>Agent Orders</span>
                </button>
                <button onclick="filterData('channel', 'special-order', this)"
                    class="filter-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 text-gray-600 hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="8" width="18" height="4" rx="1" />
                        <path d="M12 8v13" />
                        <path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7" />
                        <path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5" />
                    </svg>
                    <span>Special Orders</span>
                </button>
                <button onclick="filterData('channel', 'scheduled-production', this)"
                    class="filter-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 text-gray-600 hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m17 2 4 4-4 4" />
                        <path d="M3 11v-1a4 4 0 0 1 4-4h14" />
                        <path d="m7 22-4-4 4-4" />
                        <path d="M21 13v1a4 4 0 0 1-4 4H3" />
                    </svg>
                    <span>Scheduled</span>
                </button>
            </div>

            {{-- FILTERS: Status --}}
            <div class="bg-white rounded-2xl p-2 shadow-sm border border-gray-100 flex gap-2 overflow-x-auto"
                id="status-filters">
                <button onclick="filterData('status', 'all', this)"
                    class="filter-btn active flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-all duration-300 bg-purple-100 text-purple-700">
                    <span>All Status</span>
                </button>
                <button onclick="filterData('status', 'pending-approval', this)"
                    class="filter-btn flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-all duration-300 text-gray-600 hover:bg-gray-100">
                    <span>Pending Approval</span>
                    <span
                        class="px-1.5 py-0.5 bg-gray-200 rounded text-xs ml-1">{{ $summary['ordersByStatus']['pending-approval'] ?? 0 }}</span>
                </button>
                <button onclick="filterData('status', 'in-production', this)"
                    class="filter-btn flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-all duration-300 text-gray-600 hover:bg-gray-100">
                    <span>In Production</span>
                    <span
                        class="px-1.5 py-0.5 bg-gray-200 rounded text-xs ml-1">{{ $summary['ordersByStatus']['in-production'] ?? 0 }}</span>
                </button>
                <button onclick="filterData('status', 'ready-for-pickup', this)"
                    class="filter-btn flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-all duration-300 text-gray-600 hover:bg-gray-100">
                    <span>Ready</span>
                    <span
                        class="px-1.5 py-0.5 bg-gray-200 rounded text-xs ml-1">{{ $summary['ordersByStatus']['ready-for-pickup'] ?? 0 }}</span>
                </button>
                <button onclick="filterData('status', 'out-for-delivery', this)"
                    class="filter-btn flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-all duration-300 text-gray-600 hover:bg-gray-100">
                    <span>Delivery</span>
                    <span
                        class="px-1.5 py-0.5 bg-gray-200 rounded text-xs ml-1">{{ $summary['ordersByStatus']['out-for-delivery'] ?? 0 }}</span>
                </button>
            </div>




        </div>

        {{-- SEARCH BAR --}}
        <div class="mb-6">
            <div class="relative">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <input type="text" id="search-input" onkeyup="filterSearch(this.value)"
                    placeholder="Search by order number, customer name, phone..."
                    class="w-full h-12 pl-12 pr-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
            </div>
        </div>

        {{-- ORDERS LIST --}}
        <div id="orders-container" class="space-y-4">
            @foreach ($orders as $order)
                @php
                    $channelConfig = getChannelConfig($order['order_type']);
                    $statusConfig = getStatusConfig($order['status']);
                    $channelSlugs = [
                        1 => 'pos-pickup',
                        2 => 'special-order',
                        3 => 'scheduled-production',
                        4 => 'agent-order',
                    ];
                @endphp

                <div class="order-item bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300"
                    data-channel="{{ $channelSlugs[$order['order_type']] ?? '' }}" data-status="{{ $order['status'] }}"
                    data-search="{{ strtolower($order['order_number'] . ' ' . $order['customer_name'] . ' ' . $order['customer_phone']) }}">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        {{-- Left --}}
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                {{-- Icon --}}
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    @if ($channelConfig['icon'] == 'store')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="text-purple-600">
                                            <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7" />
                                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" />
                                            <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4" />
                                            <path d="M2 7h20" />
                                            <path
                                                d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2 2 0 0 1 4 12v0a2 2 0 0 1-2-2V7" />
                                        </svg>
                                    @elseif($channelConfig['icon'] == 'gift')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="text-purple-600">
                                            <rect x="3" y="8" width="18" height="4" rx="1" />
                                            <path d="M12 8v13" />
                                            <path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7" />
                                            <path
                                                d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5" />
                                        </svg>
                                    @elseif($channelConfig['icon'] == 'agent')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="text-purple-600">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="8.5" cy="7" r="4"></circle>
                                            <polyline points="17 11 19 13 23 9"></polyline>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="text-purple-600">
                                            <path d="m17 2 4 4-4 4" />
                                            <path d="M3 11v-1a4 4 0 0 1 4-4h14" />
                                            <path d="m7 22-4-4 4-4" />
                                            <path d="M21 13v1a4 4 0 0 1-4 4H3" />
                                        </svg>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                                        <h3 class="text-xl text-gray-900 font-bold">{{ $order['order_number'] }}</h3>

                                        <span
                                            class="{{ $channelConfig['color'] }} border px-2 py-1 rounded-lg flex items-center gap-1.5 text-xs font-medium">
                                            {{ $channelConfig['label'] }}
                                        </span>
                                        <span
                                            class="{{ $statusConfig['color'] }} border px-2 py-1 rounded-lg flex items-center gap-1.5 text-xs font-medium">
                                            {{ $statusConfig['label'] }}
                                        </span>
                                        @if ($order['recurrence_pattern'])
                                            <span
                                                class="bg-orange-100 text-orange-700 border border-orange-300 px-2 py-1 rounded-lg flex items-center gap-1.5 text-xs font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="m17 2 4 4-4 4" />
                                                    <path d="M3 11v-1a4 4 0 0 1 4-4h14" />
                                                    <path d="m7 22-4-4 4-4" />
                                                    <path d="M21 13v1a4 4 0 0 1-4 4H3" />
                                                </svg>
                                                {{ $order['recurrence_text'] }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center gap-4 mb-3 text-gray-600 text-sm">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                                <circle cx="12" cy="7" r="4" />
                                            </svg>
                                            <span class="font-medium">{{ $order['customer_name'] }}</span>
                                        </div>
                                        @if ($order['customer_phone'] !== '-')
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                                </svg>
                                                <span>{{ $order['customer_phone'] }}</span>
                                            </div>
                                        @endif
                                        @if ($order['event_type_text'] !== '-')
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="3" y="8" width="18" height="4" rx="1" />
                                                    <path d="M12 8v13" />
                                                    <path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7" />
                                                    <path
                                                        d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5" />
                                                </svg>
                                                <span>{{ $order['event_type_text'] }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center gap-4 text-gray-600 text-sm">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2"
                                                    ry="2" />
                                                <line x1="16" y1="2" x2="16" y2="6" />
                                                <line x1="8" y1="2" x2="8" y2="6" />
                                                <line x1="3" y1="10" x2="21" y2="10" />
                                            </svg>
                                            <span>{{ $order['delivery_date'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                                <circle cx="12" cy="10" r="3" />
                                            </svg>
                                            <span>{{ $order['delivery_type_text'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="8" cy="21" r="1" />
                                                <circle cx="19" cy="21" r="1" />
                                                <path
                                                    d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                            </svg>
                                            <span>{{ count($order['products']) }} items</span>
                                        </div>
                                        <div class="flex items-center gap-2" title="Requesting Branch">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 21h18" />
                                                <path d="M5 21V7" />
                                                <path d="M19 21V11" />
                                                <path d="M17 21v-8" />
                                                <path d="M9 9l3 3-3 3" />
                                                <path d="M9 12h12" />
                                            </svg>
                                            <span><span class="font-medium">Req:</span>
                                                {{ $order['request_branch_name'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-2" title="Source Branch">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z" />
                                                <line x1="6" y1="17" x2="18" y2="17" />
                                            </svg>
                                            <span><span class="font-medium">From:</span>
                                                {{ $order['req_from_branch_name'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right --}}
                        <div class="flex flex-col items-end gap-3">
                            <div class="text-right">
                                <p class="text-gray-600 text-sm">Total Amount</p>
                                <p class="text-2xl font-bold text-gray-900">Rs {{ $order['grand_total'] }}</p>
                            </div>
                            <button onclick="openViewOrderModal({{ json_encode($order) }})"
                                class="h-10 px-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-xl flex items-center gap-2 shadow-md transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- NO RESULTS --}}
        <div id="no-results" class="hidden bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100 mt-4">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </div>
            <h3 class="text-xl text-gray-600 mb-2">No orders found</h3>
            <p class="text-gray-500">Try adjusting your filters or search query</p>
        </div>
    </div>
    @include('DistributorAndSalesManagement.modals.viewOrder')
    @include('DistributorAndSalesManagement.modals.createOrder')

    <script>
        // 1. STATE MANAGEMENT
        const currentFilters = {
            channel: 'all',
            status: 'all',
            location: 'all',
            date: 'all',
            search: ''
        };

        // 2. STYLE CONSTANTS
        const activeClassesGradient = ['bg-gradient-to-r', 'from-purple-600', 'to-pink-600', 'text-white', 'shadow-md'];
        const activeClassesSolid = ['bg-purple-100', 'text-purple-700']; // For status filter
        const inactiveClasses = ['text-gray-600', 'hover:bg-gray-100'];

        // 3. MAIN FILTER FUNCTIONS
        function filterData(type, value, clickedElement) {
            // Update state
            currentFilters[type] = value;

            // Update visuals (Buttons)
            const parentContainer = clickedElement.parentElement;
            const siblings = parentContainer.querySelectorAll('.filter-btn');

            siblings.forEach(btn => {
                // Determine styling based on container ID
                let activeSet = activeClassesGradient;
                if (parentContainer.id === 'status-filters') {
                    activeSet = activeClassesSolid;
                }

                if (btn === clickedElement) {
                    btn.classList.remove(...inactiveClasses);
                    btn.classList.add(...activeSet);
                    btn.classList.add('active');
                } else {
                    btn.classList.remove(...activeSet);
                    btn.classList.remove('active');
                    btn.classList.add(...inactiveClasses);
                }
            });

            applyFilters();
        }

        function filterSearch(value) {
            currentFilters.search = value.toLowerCase();
            applyFilters();
        }

        // 4. LOGIC ENGINE
        function applyFilters() {
            const orderItems = document.querySelectorAll('.order-item');
            let visibleCount = 0;

            orderItems.forEach(item => {
                // Check Channel
                const channelMatch = currentFilters.channel === 'all' || item.dataset.channel === currentFilters
                    .channel;

                // Check Status
                const statusMatch = currentFilters.status === 'all' || item.dataset.status === currentFilters
                    .status;

                // Check Location
                const locationMatch = currentFilters.location === 'all' || item.dataset.location === currentFilters
                    .location;

                // Check Date (using category for this demo)
                const dateMatch = currentFilters.date === 'all' || item.dataset.date === currentFilters.date;

                // Check Search
                const searchMatch = currentFilters.search === '' || item.dataset.search.includes(currentFilters
                    .search);

                if (channelMatch && statusMatch && locationMatch && dateMatch && searchMatch) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Toggle "No Results" view
            const noResults = document.getElementById('no-results');
            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        // Open View Order Modal - wrapper to work with our data structure
        function openViewOrderModal(orderData) {
            // Map new field names to old field names that the modal expects
            const mappedData = {
                id: orderData.id,
                orderNumber: orderData.order_number,
                channel: getChannelFromType(orderData.order_type), // Convert integer to string
                status: orderData.status,
                priority: 'normal', // Default priority since we don't have this field
                outletId: 'loc1',
                outletCode: 'Main', // We don't have this in new schema
                customerName: orderData.customer_name,
                customerPhone: orderData.customer_phone,
                eventType: orderData.event_type_text,
                deliveryMethod: orderData.delivery_type == 1 ? 'pickup' : 'delivery',
                pickupDate: orderData.delivery_date,
                pickupTime: '',
                deliveryDate: orderData.delivery_date,
                deliveryTime: '',
                productionDeadline: orderData.end_date,
                lineItemCount: orderData.products ? orderData.products.length : 0,
                grandTotal: parseFloat(orderData.grand_total.replace(/,/g, '')),
                paymentStatus: orderData.paymentStatus,
                amountPaid: orderData.amountPaid,
                paymentReference: orderData.paymentReference,
                isRecurring: orderData.recurrence_pattern ? true : false,
                instanceNumber: 1,
                created_at: orderData.created_at,
                requestBranchName: orderData.request_branch_name,
                reqFromBranchName: orderData.req_from_branch_name,
                umBranchId: orderData.um_branch_id,
                reqFromBranchId: orderData.req_from_branch_id,
                products: orderData.products // Pass products array
            };

            // Create a temporary button element to pass the data
            const tempButton = document.createElement('button');
            tempButton.dataset.order = JSON.stringify(mappedData);

            // Call the existing openOrderModal function from viewOrder.blade.php
            if (typeof openOrderModal === 'function') {
                openOrderModal(tempButton);
            } else {
                console.error('openOrderModal function not found');
            }
        }

        // Helper function to convert order type integer to channel string
        function getChannelFromType(orderType) {
            const typeMap = {
                1: 'pos-pickup',
                2: 'special-order',
                3: 'scheduled-production',
                4: 'agent-order'
            };
            return typeMap[orderType] || 'pos-pickup';
        }
    </script>

    {{-- Include View Order Modal --}}
    @include('DistributorAndSalesManagement.modals.viewOrder')
@endsection
