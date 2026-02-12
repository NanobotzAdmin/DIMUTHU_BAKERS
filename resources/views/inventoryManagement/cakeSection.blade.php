@extends('layouts.app')
@section('title', 'Cake Section')

@section('content')

{{-- 
    -------------------------------------------------------------------------
    MOCK DATA 
    -------------------------------------------------------------------------
--}}
@php
    $categories = [
        ['id' => 'all', 'name' => 'All Items', 'icon' => 'package'],
        ['id' => 'fondant', 'name' => 'Fondant', 'icon' => 'palette'],
        ['id' => 'decorations', 'name' => 'Decorations', 'icon' => 'sparkles'],
        ['id' => 'icing', 'name' => 'Icing', 'icon' => 'cake'],
        ['id' => 'packaging', 'name' => 'Packaging', 'icon' => 'box'],
        ['id' => 'tools', 'name' => 'Tools', 'icon' => 'archive'],
    ];

    $inventory = [
        [
            'id' => 'CAKE-001', 'name' => 'White Fondant', 'category' => 'fondant', 'categoryName' => 'Fondant',
            'currentStock' => 15, 'unit' => 'kg', 'minStock' => 10, 'maxStock' => 50, 'reorderPoint' => 12,
            'unitPrice' => 2500, 'supplier' => 'Bakels Lanka', 'expiryDate' => '2025-06-15', 'daysUntilExpiry' => 193,
            'status' => 'ok', 'location' => 'Cake Storage A1', 'lastRestocked' => '2024-11-20', 'usageRate' => 2.5, 'icon' => '⚪'
        ],
        [
            'id' => 'CAKE-003', 'name' => 'Gold Edible Glitter', 'category' => 'decorations', 'categoryName' => 'Decorations',
            'currentStock' => 3, 'unit' => 'bottles', 'minStock' => 2, 'maxStock' => 15, 'reorderPoint' => 3,
            'unitPrice' => 1800, 'supplier' => 'Cake Decor LK', 'expiryDate' => '2026-01-30', 'daysUntilExpiry' => 422,
            'status' => 'low', 'location' => 'Cake Storage B1', 'lastRestocked' => '2024-10-05', 'usageRate' => 0.8, 'icon' => '✨'
        ],
        [
            'id' => 'CAKE-006', 'name' => 'Buttercream Icing', 'category' => 'icing', 'categoryName' => 'Icing',
            'currentStock' => 12, 'unit' => 'kg', 'minStock' => 8, 'maxStock' => 40, 'reorderPoint' => 10,
            'unitPrice' => 1200, 'supplier' => 'Bakels Lanka', 'expiryDate' => '2025-02-28', 'daysUntilExpiry' => 86,
            'status' => 'expiring-soon', 'location' => 'Cake Fridge R1', 'lastRestocked' => '2024-11-30', 'usageRate' => 4.2, 'icon' => '🧈'
        ],
        [
            'id' => 'CAKE-010', 'name' => 'Cake Boxes (Medium)', 'category' => 'packaging', 'categoryName' => 'Cake Boxes',
            'currentStock' => 85, 'unit' => 'pcs', 'minStock' => 50, 'maxStock' => 300, 'reorderPoint' => 70,
            'unitPrice' => 180, 'supplier' => 'Pack Master', 'expiryDate' => null, 'daysUntilExpiry' => null,
            'status' => 'ok', 'location' => 'Cake Storage C2', 'lastRestocked' => '2024-11-22', 'usageRate' => 12, 'icon' => '📦'
        ],
    ];

    $totalItems = count($inventory);
    $lowStock = count(array_filter($inventory, fn($i) => $i['status'] === 'low'));
    $expiringSoon = count(array_filter($inventory, fn($i) => $i['daysUntilExpiry'] !== null && $i['daysUntilExpiry'] <= 90));
    $totalValue = array_reduce($inventory, fn($c, $i) => $c + ($i['currentStock'] * $i['unitPrice']), 0);

    $movements = [
         [
            'id' => 'MOV-001', 'itemName' => 'White Fondant', 'type' => 'usage', 'quantity' => 2.5, 'unit' => 'kg',
            'reason' => 'Used for Wedding Cake Order #WED-445', 'performedBy' => 'Kasun Perera', 'timestamp' => '2024-12-04 09:30 AM', 'reference' => 'ORDER-WED-445'
         ],
         [
            'id' => 'MOV-003', 'itemName' => 'Buttercream Icing', 'type' => 'restock', 'quantity' => 5, 'unit' => 'kg',
            'reason' => 'New delivery from supplier', 'performedBy' => 'Saman Fernando', 'timestamp' => '2024-12-03 02:45 PM', 'reference' => 'PO-2024-156'
         ]
    ];
@endphp

<div class="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-blue-50 p-4 md:p-6 font-sans">

    {{-- HEADER SECTION --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-8a2 2 0 00-2-2H6a2 2 0 00-2 2v8M4 21h16M7 11V7a5 5 0 0110 0v4M7 7l4-4 4 4" /></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Cake Section Inventory</h1>
                    <p class="text-gray-600">Manage cake-specific ingredients and decorations</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export
                </button>
                <button class="h-12 px-5 bg-gradient-to-br from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    New Item
                </button>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Total Items</span>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $totalItems }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-orange-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Low Stock</span>
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div class="text-3xl font-bold text-orange-600">{{ $lowStock }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-yellow-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Expiring Soon</span>
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <div class="text-3xl font-bold text-yellow-600">{{ $expiringSoon }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-green-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Total Value</span>
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
                <div class="text-3xl font-bold text-green-600">Rs {{ number_format($totalValue / 1000, 1) }}K</div>
            </div>
        </div>

        {{-- VIEW TABS --}}
        <div class="flex flex-col md:flex-row gap-2 mb-4" id="view-tabs">
            <button data-view="stock" class="view-tab active flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all font-medium bg-gradient-to-br from-pink-500 to-purple-600 text-white shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                All Stock
            </button>
            <button data-view="low-stock" class="view-tab flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all font-medium bg-white text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                Low Stock
                @if($lowStock > 0) <span class="px-2 py-0.5 text-xs rounded-full bg-orange-500 text-white">{{ $lowStock }}</span> @endif
            </button>
            <button data-view="expiring" class="view-tab flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all font-medium bg-white text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Expiring
                @if($expiringSoon > 0) <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-500 text-white">{{ $expiringSoon }}</span> @endif
            </button>
            <button data-view="movements" class="view-tab flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all font-medium bg-white text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                Movements
            </button>
        </div>

        {{-- FILTERS (Search & Category) --}}
        <div id="filter-container">
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100 mb-4">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" id="search-input" placeholder="Search items by name or category..." 
                           class="flex-1 text-xl outline-none placeholder:text-gray-300">
                    <button class="hidden md:flex h-12 px-5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl items-center gap-2 font-medium transition-all">
                        Scan Barcode
                    </button>
                </div>
            </div>

            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide" id="category-filters">
                @foreach($categories as $cat)
                    <button data-category="{{ $cat['id'] }}"
                            class="category-btn h-12 px-5 rounded-xl flex items-center gap-2 font-medium transition-all whitespace-nowrap {{ $cat['id'] === 'all' ? 'bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-md active' : 'bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200' }}">
                        <span>{{ $cat['name'] }}</span>
                        @php
                            $count = $cat['id'] === 'all' ? count($inventory) : count(array_filter($inventory, fn($i) => $i['category'] === $cat['id']));
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs bg-white/20 text-inherit">{{ $count }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- INVENTORY GRID VIEW --}}
    <div id="inventory-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($inventory as $item)
            @php
                $isLowStock = $item['currentStock'] <= $item['reorderPoint'];
                $expiryDays = $item['daysUntilExpiry'];
                $statusColor = match($item['status']) {
                    'ok' => 'bg-green-500',
                    'low' => 'bg-orange-500',
                    'expiring-soon' => 'bg-yellow-500',
                    default => 'bg-gray-500'
                };
            @endphp
            <div class="inventory-card bg-white rounded-2xl p-5 shadow-sm border-2 transition-all hover:shadow-md {{ $isLowStock ? 'border-orange-300' : 'border-gray-100' }}"
                 data-id="{{ $item['id'] }}"
                 data-name="{{ strtolower($item['name']) }}"
                 data-category="{{ $item['category'] }}"
                 data-status="{{ $item['status'] }}"
                 data-expiry="{{ $expiryDays ?? 9999 }}">
                
                {{-- Card Header --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="text-4xl">{{ $item['icon'] }}</div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $item['name'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $item['categoryName'] }}</p>
                        </div>
                    </div>
                    <div class="w-4 h-4 rounded-full shadow-md {{ $statusColor }}"></div>
                </div>

                {{-- Stock Progress --}}
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Stock Level</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $item['currentStock'] }} / {{ $item['maxStock'] }} {{ $item['unit'] }}
                        </span>
                    </div>
                    <div class="h-3 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gray-900 transition-all duration-500" 
                             style="width: {{ ($item['currentStock'] / $item['maxStock']) * 100 }}%"></div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="space-y-2 mb-4 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Reorder Point:</span>
                        <span class="font-medium text-gray-900">{{ $item['reorderPoint'] }} {{ $item['unit'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Location:</span>
                        <span class="font-medium text-gray-900">{{ $item['location'] }}</span>
                    </div>
                    @if($item['expiryDate'])
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Expiry:</span>
                            <span class="font-medium {{ $expiryDays <= 30 ? 'text-red-600' : ($expiryDays <= 90 ? 'text-orange-600' : 'text-green-600') }}">
                                {{ $expiryDays }} days
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Low Stock Alert --}}
                @if($isLowStock)
                <div class="bg-orange-50 border-2 border-orange-200 rounded-xl p-3 mb-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span class="text-sm font-medium text-orange-900">Low Stock</span>
                    </div>
                </div>
                @endif

                {{-- Actions --}}
                <div class="flex gap-2">
                    <button type="button" onclick="openAdjustModal('{{ $item['id'] }}', 'remove')" class="flex-1 h-10 bg-red-100 hover:bg-red-200 text-red-700 rounded-xl flex items-center justify-center gap-2 font-medium transition-all">
                        Use
                    </button>
                    <button type="button" onclick="openAdjustModal('{{ $item['id'] }}', 'add')" class="flex-1 h-10 bg-green-100 hover:bg-green-200 text-green-700 rounded-xl flex items-center justify-center gap-2 font-medium transition-all">
                        Add
                    </button>
                    <button type="button" onclick="openItemModal('{{ $item['id'] }}')" class="h-10 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl flex items-center justify-center transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </button>
                </div>
            </div>
        @endforeach
        
        <div id="no-items-message" class="hidden col-span-full bg-white rounded-2xl p-12 text-center">
            <h3 class="text-xl text-gray-900 mb-2">No Items Found</h3>
            <p class="text-gray-600">Try adjusting your search or filters</p>
        </div>
    </div>

    {{-- MOVEMENTS VIEW (Hidden by default) --}}
    <div id="movements-view" class="hidden">
        <div class="bg-white rounded-xl p-5 border-2 border-gray-100">
            <h3 class="text-xl font-medium text-gray-900 mb-4">Recent Stock Movements</h3>
            <div class="space-y-3">
                @foreach($movements as $mov)
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition-all">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $mov['type'] == 'usage' ? 'bg-red-100 text-red-600' : ($mov['type'] == 'restock' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600') }}">
                             @if($mov['type'] == 'usage') <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                            @elseif($mov['type'] == 'restock') <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            @else <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $mov['itemName'] }}</h4>
                            <p class="text-sm text-gray-600">{{ $mov['reason'] }}</p>
                            <div class="text-xs text-gray-500 mt-1">{{ $mov['performedBy'] }} • {{ $mov['timestamp'] }}</div>
                        </div>
                        <div class="text-right">
                             <div class="text-xl font-bold {{ $mov['type'] == 'usage' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $mov['type'] == 'usage' ? '-' : '+' }} {{ $mov['quantity'] }} {{ $mov['unit'] }}
                             </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ITEM DETAIL MODAL --}}
<div id="modal-item-detail" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 shadow-2xl relative">
        
        {{-- Close Button --}}
        <button onclick="closeModals()" class="absolute top-6 right-6 p-2 bg-gray-100 rounded-full hover:bg-gray-200">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <span id="detail-icon" class="text-5xl"></span>
            <div>
                <h2 id="detail-name" class="text-2xl font-bold text-gray-900"></h2>
                <p id="detail-meta" class="text-gray-600"></p>
            </div>
        </div>

        <div class="space-y-5">
            {{-- Stock Overview Section --}}
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-5 border-2 border-purple-200">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Stock Overview</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Current Stock</div>
                        <div id="detail-stock" class="text-3xl font-bold text-purple-600"></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Max Capacity</div>
                        <div id="detail-max" class="text-3xl font-bold text-gray-900"></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Reorder Point</div>
                        <div id="detail-reorder" class="text-2xl font-bold text-orange-600"></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Usage Rate</div>
                        <div id="detail-usage" class="text-2xl font-bold text-blue-600"></div>
                    </div>
                </div>
            </div>

            {{-- Details Grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-600 mb-1">Unit Price</div>
                        <div id="detail-price" class="text-xl font-bold text-gray-900"></div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-600 mb-1">Total Value</div>
                        <div id="detail-total-value" class="text-xl font-bold text-green-600"></div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-600 mb-1">Supplier</div>
                        <div id="detail-supplier" class="text-base font-medium text-gray-900"></div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-600 mb-1">Last Restocked</div>
                        <div id="detail-last-restocked" class="text-base font-medium text-gray-900"></div>
                </div>
            </div>

            {{-- Expiry Info (Hidden by default) --}}
            <div id="detail-expiry-container" class="hidden rounded-xl p-4 border-2">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-600 mb-1">Expiry Date</div>
                        <div id="detail-expiry-date" class="text-lg font-bold text-gray-900"></div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600 mb-1">Days Until Expiry</div>
                        <div id="detail-days-remaining" class="text-2xl font-bold"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- ADJUST MODAL --}}
    <div id="modal-adjust" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl w-full max-w-xl p-6 shadow-2xl relative">
            <div class="mb-6">
                <h2 class="text-2xl font-bold flex items-center gap-2">
                    <span id="adjust-sign" class="text-lg"></span>
                    <span id="adjust-title">Adjust Stock</span>
                </h2>
                <p id="adjust-subtitle" class="text-gray-600"></p>
            </div>

            <input type="hidden" id="adjust-item-id">
            <input type="hidden" id="adjust-type">

            <div class="space-y-4">
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">Quantity</label>
                    <div class="flex items-center gap-3">
                        <input type="number" id="adjust-quantity" step="0.1" class="flex-1 h-14 px-4 text-xl rounded-xl border-2 border-gray-200 focus:border-purple-500 outline-none">
                        <span id="adjust-unit" class="text-xl text-gray-600"></span>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">Reason</label>
                    <div id="adjust-reasons-container" class="grid grid-cols-2 gap-2">
                        </div>
                    <input type="hidden" id="adjust-reason-val">
                </div>

                <div class="flex gap-3 pt-4 border-t-2 border-gray-200 mt-4">
                    <button onclick="closeModals()" class="flex-1 h-14 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium">Cancel</button>
                    <button onclick="submitAdjustment()" id="adjust-confirm-btn" disabled class="flex-1 h-14 text-white rounded-xl font-medium shadow-lg disabled:opacity-50 disabled:cursor-not-allowed bg-gray-600">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Pass PHP data to JS
    const inventoryData = @json($inventory);
    
    // Global State
    let currentSearch = '';
    let currentCategory = 'all';
    let currentView = 'stock'; // stock, low-stock, expiring, movements

    // DOM Elements
    const searchInput = document.getElementById('search-input');
    const categoryBtns = document.querySelectorAll('.category-btn');
    const viewTabs = document.querySelectorAll('.view-tab');
    const inventoryGrid = document.getElementById('inventory-grid');
    const movementsView = document.getElementById('movements-view');
    const filterContainer = document.getElementById('filter-container');
    const inventoryCards = document.querySelectorAll('.inventory-card');
    const noItemsMsg = document.getElementById('no-items-message');

    // --- 1. Filtering Logic ---
    function filterItems() {
        let visibleCount = 0;

        inventoryCards.forEach(card => {
            const name = card.dataset.name;
            const category = card.dataset.category;
            const status = card.dataset.status;
            const expiry = parseInt(card.dataset.expiry);

            const matchesSearch = name.includes(currentSearch) || category.includes(currentSearch);
            const matchesCategory = currentCategory === 'all' || category === currentCategory;
            
            let matchesView = true;
            if (currentView === 'low-stock') {
                matchesView = status === 'low';
            } else if (currentView === 'expiring') {
                matchesView = expiry <= 90;
            }

            if (matchesSearch && matchesCategory && matchesView) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            noItemsMsg.classList.remove('hidden');
        } else {
            noItemsMsg.classList.add('hidden');
        }
    }

    // --- 2. Event Listeners ---

    // Search
    searchInput.addEventListener('input', (e) => {
        currentSearch = e.target.value.toLowerCase();
        filterItems();
    });

    // Category Tabs
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // UI Toggle
            categoryBtns.forEach(b => {
                b.classList.remove('bg-gradient-to-br', 'from-purple-500', 'to-pink-600', 'text-white', 'shadow-md', 'active');
                b.classList.add('bg-white', 'text-gray-700', 'border-2', 'border-gray-200');
            });
            btn.classList.remove('bg-white', 'text-gray-700', 'border-2', 'border-gray-200');
            btn.classList.add('bg-gradient-to-br', 'from-purple-500', 'to-pink-600', 'text-white', 'shadow-md', 'active');

            currentCategory = btn.dataset.category;
            filterItems();
        });
    });

    // View Tabs
    viewTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const view = tab.dataset.view;
            currentView = view;

            // UI Toggle
            viewTabs.forEach(t => {
                t.classList.remove('bg-gradient-to-br', 'from-pink-500', 'to-purple-600', 'text-white', 'shadow-lg');
                t.classList.add('bg-white', 'text-gray-700');
            });
            tab.classList.remove('bg-white', 'text-gray-700');
            tab.classList.add('bg-gradient-to-br', 'from-pink-500', 'to-purple-600', 'text-white', 'shadow-lg');

            if (view === 'movements') {
                inventoryGrid.classList.add('hidden');
                filterContainer.classList.add('hidden');
                movementsView.classList.remove('hidden');
            } else {
                movementsView.classList.add('hidden');
                inventoryGrid.classList.remove('hidden');
                filterContainer.classList.remove('hidden');
                filterItems();
            }
        });
    });

    // --- 3. Modal Logic ---

    function closeModals() {
        document.getElementById('modal-item-detail').classList.add('hidden');
        document.getElementById('modal-adjust').classList.add('hidden');
    }

    function getItem(id) {
        return inventoryData.find(item => item.id === id);
    }

    // Item Detail Modal
    // Updated Item Detail Modal Function
window.openItemModal = function(id) {
    const item = getItem(id); // Assumes getItem helper exists from previous code
    if(!item) return;

    // 1. Basic Info
    document.getElementById('detail-icon').textContent = item.icon;
    document.getElementById('detail-name').textContent = item.name;
    document.getElementById('detail-meta').textContent = `${item.categoryName} • ${item.location}`;

    // 2. Stock Overview
    document.getElementById('detail-stock').textContent = `${item.currentStock} ${item.unit}`;
    document.getElementById('detail-max').textContent = `${item.maxStock} ${item.unit}`;
    document.getElementById('detail-reorder').textContent = `${item.reorderPoint} ${item.unit}`;
    document.getElementById('detail-usage').textContent = `${item.usageRate} ${item.unit}/day`;

    // 3. Details Grid
    // Use .toLocaleString() to add commas (e.g. 1,200)
    document.getElementById('detail-price').textContent = `Rs ${item.unitPrice.toLocaleString()}`;
    
    // Calculate Total Value
    const totalValue = item.currentStock * item.unitPrice;
    document.getElementById('detail-total-value').textContent = `Rs ${totalValue.toLocaleString()}`;
    
    document.getElementById('detail-supplier').textContent = item.supplier;
    document.getElementById('detail-last-restocked').textContent = item.lastRestocked;

    // 4. Expiry Logic
    const expiryContainer = document.getElementById('detail-expiry-container');
    const expiryDateEl = document.getElementById('detail-expiry-date');
    const daysRemainingEl = document.getElementById('detail-days-remaining');

    if (item.expiryDate) {
        expiryContainer.classList.remove('hidden');
        expiryDateEl.textContent = item.expiryDate;
        daysRemainingEl.textContent = `${item.daysUntilExpiry} days`;

        // Reset classes
        expiryContainer.className = 'rounded-xl p-4 border-2';
        daysRemainingEl.className = 'text-2xl font-bold';

        // Apply Color Logic based on React code
        if (item.daysUntilExpiry <= 30) {
            expiryContainer.classList.add('bg-red-50', 'border-red-200');
            daysRemainingEl.classList.add('text-red-600');
        } else if (item.daysUntilExpiry <= 90) {
            expiryContainer.classList.add('bg-yellow-50', 'border-yellow-200');
            daysRemainingEl.classList.add('text-orange-600');
        } else {
            expiryContainer.classList.add('bg-green-50', 'border-green-200');
            daysRemainingEl.classList.add('text-green-600');
        }
    } else {
        // Hide if no expiry date
        expiryContainer.classList.add('hidden');
    }

    // Show the modal
    document.getElementById('modal-item-detail').classList.remove('hidden');
}

    // Adjust Modal
    window.openAdjustModal = function(id, type) {
        const item = getItem(id);
        if(!item) return;

        // Reset Form
        const quantityInput = document.getElementById('adjust-quantity');
        quantityInput.value = '';
        document.getElementById('adjust-reason-val').value = '';
        document.getElementById('adjust-confirm-btn').disabled = true;

        // Set Headers
        document.getElementById('adjust-item-id').value = id;
        document.getElementById('adjust-type').value = type;
        document.getElementById('adjust-title').textContent = type === 'add' ? 'Add Stock' : 'Use Stock';
        document.getElementById('adjust-subtitle').textContent = `${item.name} • Current: ${item.currentStock} ${item.unit}`;
        document.getElementById('adjust-unit').textContent = item.unit;
        
        const sign = document.getElementById('adjust-sign');
        const confirmBtn = document.getElementById('adjust-confirm-btn');
        
        if(type === 'add') {
            sign.textContent = '+';
            sign.className = 'text-green-600 text-3xl font-bold';
            confirmBtn.className = 'flex-1 h-14 text-white rounded-xl font-medium shadow-lg disabled:opacity-50 disabled:cursor-not-allowed bg-green-600 hover:bg-green-700';
        } else {
            sign.textContent = '-';
            sign.className = 'text-red-600 text-3xl font-bold';
            confirmBtn.className = 'flex-1 h-14 text-white rounded-xl font-medium shadow-lg disabled:opacity-50 disabled:cursor-not-allowed bg-red-600 hover:bg-red-700';
        }

        // Generate Reasons
        const reasons = type === 'remove' 
            ? ['Production Use', 'Quality Issue', 'Damaged', 'Expired', 'Sample']
            : ['Supplier Delivery', 'Purchase Order', 'Transfer In', 'Correction'];
            
        const reasonsContainer = document.getElementById('adjust-reasons-container');
        reasonsContainer.innerHTML = '';
        
        reasons.forEach(r => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'p-3 rounded-xl border-2 transition-all text-sm font-medium border-gray-200 hover:bg-gray-50';
            btn.textContent = r;
            btn.onclick = () => {
                // Select visual
                Array.from(reasonsContainer.children).forEach(c => c.className = 'p-3 rounded-xl border-2 transition-all text-sm font-medium border-gray-200 hover:bg-gray-50');
                btn.className = 'p-3 rounded-xl border-2 transition-all text-sm font-medium border-purple-500 bg-purple-50 text-purple-900';
                document.getElementById('adjust-reason-val').value = r;
                checkForm();
            };
            reasonsContainer.appendChild(btn);
        });

        document.getElementById('modal-adjust').classList.remove('hidden');
    }

    // Form Validation
    const quantityInput = document.getElementById('adjust-quantity');
    quantityInput.addEventListener('input', checkForm);

    function checkForm() {
        const qty = document.getElementById('adjust-quantity').value;
        const reason = document.getElementById('adjust-reason-val').value;
        const btn = document.getElementById('adjust-confirm-btn');
        
        if(qty && reason) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }

    window.submitAdjustment = function() {
        const id = document.getElementById('adjust-item-id').value;
        const type = document.getElementById('adjust-type').value;
        const qty = document.getElementById('adjust-quantity').value;
        const reason = document.getElementById('adjust-reason-val').value;
        
        alert(`Submitting: ${type} ${qty} for item ${id}. Reason: ${reason}`);
        // Here you would do an AJAX/Fetch request to your Laravel backend
        
        closeModals();
        // Optional: reload page or update DOM manually
    }
</script>
@endsection