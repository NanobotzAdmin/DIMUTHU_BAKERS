@extends('layouts.app')
@section('title', 'Bakery Section')

@section('content')

{{-- 
    -------------------------------------------------------------------------
    MOCK DATA (Simulating Controller)
    -------------------------------------------------------------------------
--}}
@php
    $categories = [
        ['id' => 'all', 'name' => 'All Products', 'icon' => 'package'],
        ['id' => 'bread', 'name' => 'Bread', 'icon' => 'croissant'],
        ['id' => 'rolls', 'name' => 'Rolls', 'icon' => 'archive'],
        ['id' => 'pastries', 'name' => 'Pastries', 'icon' => 'box'],
        ['id' => 'specialty', 'name' => 'Specialty', 'icon' => 'package-open'],
    ];

    $inventory = [
        [
            'id' => 'BKY-001', 'name' => 'White Bread Loaves', 'category' => 'bread', 'categoryName' => 'Bread',
            'currentStock' => 45, 'unit' => 'loaves', 'minStock' => 30, 'maxStock' => 100, 'reorderPoint' => 40,
            'shelfLife' => 3, 'batchNumber' => 'BATCH-20241204-01', 'productionDate' => '2024-12-04', 'expiryDate' => '2024-12-07',
            'hoursRemaining' => 68, 'status' => 'ok', 'productionQuantity' => 60, 'soldQuantity' => 12, 'wasteQuantity' => 3,
            'location' => 'Display Shelf A1', 'unitPrice' => 180, 'sellingPrice' => 280, 'icon' => '🍞'
        ],
        [
            'id' => 'BKY-003', 'name' => 'Butter Croissants', 'category' => 'pastries', 'categoryName' => 'Pastries',
            'currentStock' => 18, 'unit' => 'pcs', 'minStock' => 20, 'maxStock' => 100, 'reorderPoint' => 25,
            'shelfLife' => 2, 'batchNumber' => 'BATCH-20241203-15', 'productionDate' => '2024-12-03', 'expiryDate' => '2024-12-05',
            'hoursRemaining' => 20, 'status' => 'expiring-soon', 'productionQuantity' => 50, 'soldQuantity' => 30, 'wasteQuantity' => 2,
            'location' => 'Display Shelf B1', 'unitPrice' => 85, 'sellingPrice' => 180, 'icon' => '🥐'
        ],
        [
            'id' => 'BKY-004', 'name' => 'Dinner Rolls', 'category' => 'rolls', 'categoryName' => 'Rolls',
            'currentStock' => 85, 'unit' => 'pcs', 'minStock' => 50, 'maxStock' => 200, 'reorderPoint' => 70,
            'shelfLife' => 2, 'batchNumber' => 'BATCH-20241204-05', 'productionDate' => '2024-12-04', 'expiryDate' => '2024-12-06',
            'hoursRemaining' => 44, 'status' => 'ok', 'productionQuantity' => 120, 'soldQuantity' => 32, 'wasteQuantity' => 3,
            'location' => 'Display Shelf C1', 'unitPrice' => 35, 'sellingPrice' => 70, 'icon' => '🥖'
        ],
        [
            'id' => 'BKY-007', 'name' => 'Baguettes', 'category' => 'bread', 'categoryName' => 'Bread',
            'currentStock' => 16, 'unit' => 'pcs', 'minStock' => 15, 'maxStock' => 60, 'reorderPoint' => 20,
            'shelfLife' => 1, 'batchNumber' => 'BATCH-20241204-12', 'productionDate' => '2024-12-04', 'expiryDate' => '2024-12-05',
            'hoursRemaining' => 10, 'status' => 'critical', 'productionQuantity' => 30, 'soldQuantity' => 12, 'wasteQuantity' => 2,
            'location' => 'Display Shelf A3', 'unitPrice' => 150, 'sellingPrice' => 320, 'icon' => '🥖'
        ],
        [
            'id' => 'BKY-009', 'name' => 'Chocolate Muffins', 'category' => 'pastries', 'categoryName' => 'Pastries',
            'currentStock' => 36, 'unit' => 'pcs', 'minStock' => 25, 'maxStock' => 100, 'reorderPoint' => 30,
            'shelfLife' => 4, 'batchNumber' => 'BATCH-20241204-06', 'productionDate' => '2024-12-04', 'expiryDate' => '2024-12-08',
            'hoursRemaining' => 92, 'status' => 'ok', 'productionQuantity' => 50, 'soldQuantity' => 12, 'wasteQuantity' => 2,
            'location' => 'Display Shelf B3', 'unitPrice' => 75, 'sellingPrice' => 180, 'icon' => '🧁'
        ],
        // Add more items as needed...
    ];

    $wasteRecords = [
        [
            'id' => 'WASTE-001', 'itemName' => 'Butter Croissants', 'batchNumber' => 'BATCH-20241202-15',
            'quantity' => 8, 'unit' => 'pcs', 'reason' => 'Expired - Not sold', 'value' => 680,
            'timestamp' => '2024-12-03 08:30 PM', 'recordedBy' => 'Nimali Silva'
        ],
        [
            'id' => 'WASTE-002', 'itemName' => 'White Bread Loaves', 'batchNumber' => 'BATCH-20241203-01',
            'quantity' => 5, 'unit' => 'loaves', 'reason' => 'Quality Issue - Burnt edges', 'value' => 900,
            'timestamp' => '2024-12-04 09:15 AM', 'recordedBy' => 'Kasun Perera'
        ],
    ];

    // Stats Calculation
    $totalItems = count($inventory);
    $totalStock = array_reduce($inventory, fn($s, $i) => $s + $i['currentStock'], 0);
    $expiringSoon = count(array_filter($inventory, fn($i) => $i['hoursRemaining'] <= 24));
    $todayProduction = array_reduce($inventory, fn($s, $i) => $s + $i['productionQuantity'], 0);
    $todaySales = array_reduce($inventory, fn($s, $i) => $s + $i['soldQuantity'], 0);
    $todayWaste = array_reduce($inventory, fn($s, $i) => $s + $i['wasteQuantity'], 0);
    $todayRevenue = array_reduce($inventory, fn($s, $i) => $s + ($i['soldQuantity'] * $i['sellingPrice']), 0);
    $totalWasteValue = array_reduce($wasteRecords, fn($s, $w) => $s + $w['value'], 0);
@endphp

<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 p-4 md:p-6 font-sans">
    
    {{-- HEADER --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 1 0 10 10 10 10 0 0 0-10-10zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z" /></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Bakery Section Inventory</h1>
                    <p class="text-gray-600">Real-time stock levels for bakery products</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export
                </button>
                <button class="h-12 px-5 bg-gradient-to-br from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    Sync Production
                </button>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Today's Production</span>
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div class="text-3xl font-bold text-blue-600">{{ $todayProduction }}</div>
                <div class="text-sm text-gray-500 mt-1">Items produced</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-green-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Today's Sales</span>
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div class="text-3xl font-bold text-green-600">{{ $todaySales }}</div>
                <div class="text-sm text-green-500 mt-1">Rs {{ number_format($todayRevenue) }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-red-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Today's Waste</span>
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </div>
                <div class="text-3xl font-bold text-red-600">{{ $todayWaste }}</div>
                <div class="text-sm text-red-500 mt-1">
                    {{ $todayProduction > 0 ? number_format(($todayWaste / $todayProduction) * 100, 1) : '0.0' }}% waste rate
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-yellow-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Expiring Soon</span>
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="text-3xl font-bold text-yellow-600">{{ $expiringSoon }}</div>
                <div class="text-sm text-yellow-500 mt-1">Within 24 hours</div>
            </div>
        </div>

        {{-- VIEW TABS --}}
        <div class="flex flex-col md:flex-row gap-2 mb-4" id="tabs-container">
            <button data-view="stock" class="tab-btn active flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all font-medium bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                Current Stock
            </button>
            <button data-view="expiring" class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all font-medium bg-white text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Expiring Soon
                @if($expiringSoon > 0) <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-yellow-500 text-white animate-pulse">{{ $expiringSoon }}</span> @endif
            </button>
            <button data-view="production" class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all font-medium bg-white text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                Production vs Sales
            </button>
            <button data-view="waste" class="tab-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all font-medium bg-white text-gray-700 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Waste Tracking
            </button>
        </div>

        {{-- FILTERS (Visible on Stock/Expiring) --}}
        <div id="filter-container">
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100 mb-4">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" id="search-input" placeholder="Search products by name or category..." 
                           class="flex-1 text-xl outline-none placeholder:text-gray-300">
                    <button class="hidden md:flex h-12 px-5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl items-center gap-2 font-medium transition-all">
                        Scan Barcode
                    </button>
                </div>
            </div>

            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                @foreach($categories as $cat)
                    <button data-category="{{ $cat['id'] }}"
                            class="category-btn h-12 px-5 rounded-xl flex items-center gap-2 font-medium transition-all whitespace-nowrap {{ $cat['id'] === 'all' ? 'bg-gradient-to-br from-orange-500 to-amber-600 text-white shadow-md active' : 'bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200' }}">
                        <span>{{ $cat['name'] }}</span>
                        @php $catCount = $cat['id'] === 'all' ? count($inventory) : count(array_filter($inventory, fn($i) => $i['category'] === $cat['id'])); @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs bg-white/20 text-inherit">{{ $catCount }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 
        ================= VIEW 1: GRID (STOCK / EXPIRING) ================= 
    --}}
    <div id="view-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($inventory as $item)
            @php
                $stockPct = ($item['currentStock'] / $item['maxStock']) * 100;
                $sellThrough = ($item['soldQuantity'] / $item['productionQuantity']) * 100;
                $isCritical = $item['hoursRemaining'] <= 12;
                $isExpiring = $item['hoursRemaining'] <= 24;
                $borderColor = $isCritical ? 'border-red-300' : ($isExpiring ? 'border-yellow-300' : 'border-gray-100');
            @endphp
            <div class="inventory-card bg-white rounded-2xl p-5 shadow-sm border-2 transition-all hover:shadow-md {{ $borderColor }}"
                 data-id="{{ $item['id'] }}"
                 data-name="{{ strtolower($item['name']) }}"
                 data-category="{{ $item['category'] }}"
                 data-hours="{{ $item['hoursRemaining'] }}">
                
                {{-- Header --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="text-5xl">{{ $item['icon'] }}</div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $item['name'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $item['categoryName'] }}</p>
                        </div>
                    </div>
                    <div class="w-4 h-4 rounded-full shadow-md {{ $item['status'] == 'ok' ? 'bg-green-500' : ($item['status'] == 'critical' ? 'bg-red-500' : 'bg-yellow-500') }}"></div>
                </div>

                {{-- Batch Info --}}
                <div class="bg-gray-50 rounded-xl p-3 mb-4 text-xs space-y-2">
                    <div class="flex justify-between"><span class="text-gray-600">Batch:</span> <span class="font-mono font-medium text-gray-900">{{ $item['batchNumber'] }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Production:</span> <span class="font-medium text-gray-900">{{ $item['productionDate'] }}</span></div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shelf Life:</span> 
                        <span class="font-medium {{ $item['hoursRemaining'] <= 12 ? 'text-red-600' : ($item['hoursRemaining'] <= 24 ? 'text-orange-600' : 'text-green-600') }}">{{ $item['hoursRemaining'] }}h remaining</span>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Current Stock</span>
                        <span class="text-sm font-medium text-gray-900">{{ $item['currentStock'] }} / {{ $item['maxStock'] }} {{ $item['unit'] }}</span>
                    </div>
                    <div class="h-3 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gray-900 transition-all" style="width: {{ $stockPct }}%"></div>
                    </div>
                </div>

                {{-- Production Stats Mini --}}
                <div class="grid grid-cols-3 gap-2 mb-4 text-center">
                    <div class="bg-blue-50 rounded-lg p-2">
                        <div class="text-xs text-gray-600 mb-1">Produced</div>
                        <div class="text-lg font-bold text-blue-600">{{ $item['productionQuantity'] }}</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-2">
                        <div class="text-xs text-gray-600 mb-1">Sold</div>
                        <div class="text-lg font-bold text-green-600">{{ $item['soldQuantity'] }}</div>
                    </div>
                    <div class="bg-red-50 rounded-lg p-2">
                        <div class="text-xs text-gray-600 mb-1">Waste</div>
                        <div class="text-lg font-bold text-red-600">{{ $item['wasteQuantity'] }}</div>
                    </div>
                </div>

                {{-- Alerts --}}
                @if($isCritical)
                    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-3 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm font-medium text-red-900">Critical! Expires in {{ $item['hoursRemaining'] }}h</span>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex gap-2">
                    <button onclick="openWasteModal('{{ $item['id'] }}')" class="flex-1 h-10 bg-red-100 hover:bg-red-200 text-red-700 rounded-xl flex items-center justify-center gap-2 font-medium transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Waste
                    </button>
                    <button onclick="openSaleModal('{{ $item['id'] }}')" class="flex-1 h-10 bg-green-100 hover:bg-green-200 text-green-700 rounded-xl flex items-center justify-center gap-2 font-medium transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Sale
                    </button>
                    <button onclick="openDetailModal('{{ $item['id'] }}')" class="h-10 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl flex items-center justify-center transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 
        ================= VIEW 2: PRODUCTION VS SALES ================= 
    --}}
    <div id="view-production" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($inventory as $item)
            @php
                $sellThrough = ($item['soldQuantity'] / $item['productionQuantity']) * 100;
                $remaining = $item['currentStock'];
                $remPct = ($remaining / $item['productionQuantity']) * 100;
                $wasteRate = ($item['wasteQuantity'] / $item['productionQuantity']) * 100;
            @endphp
            <div class="bg-white rounded-2xl p-5 shadow-sm border-2 border-gray-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="text-4xl">{{ $item['icon'] }}</div>
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-gray-900">{{ $item['name'] }}</h3>
                        <p class="text-sm text-gray-500">Batch: {{ $item['batchNumber'] }}</p>
                    </div>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Produced:</span>
                        <span class="text-lg font-bold text-blue-600">{{ $item['productionQuantity'] }} {{ $item['unit'] }}</span>
                    </div>

                    {{-- Bars --}}
                    <div>
                        <div class="flex justify-between mb-1"><span class="text-sm text-gray-600">Sold:</span> <span class="text-base font-medium text-green-600">{{ $item['soldQuantity'] }} ({{ round($sellThrough) }}%)</span></div>
                        <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden"><div class="h-full bg-green-500" style="width: {{ $sellThrough }}%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1"><span class="text-sm text-gray-600">Remaining:</span> <span class="text-base font-medium text-orange-600">{{ $remaining }} ({{ round($remPct) }}%)</span></div>
                        <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden"><div class="h-full bg-orange-500" style="width: {{ $remPct }}%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1"><span class="text-sm text-gray-600">Waste:</span> <span class="text-base font-medium text-red-600">{{ $item['wasteQuantity'] }} ({{ round($wasteRate) }}%)</span></div>
                        <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden"><div class="h-full bg-red-500" style="width: {{ $wasteRate }}%"></div></div>
                    </div>
                </div>

                <div class="bg-green-50 rounded-xl p-3 border-2 border-green-200 flex justify-between">
                    <span class="text-sm text-gray-700">Revenue Generated:</span>
                    <span class="text-xl font-bold text-green-700">Rs {{ number_format($item['soldQuantity'] * $item['sellingPrice']) }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 
        ================= VIEW 3: WASTE TRACKING ================= 
    --}}
    <div id="view-waste" class="hidden space-y-4">
        <div class="bg-white rounded-xl p-5 border-2 border-gray-100">
            <div class="flex justify-between mb-6">
                <h3 class="text-xl font-medium text-gray-900">Waste Log - Today</h3>
                <div class="text-right">
                    <div class="text-sm text-gray-600">Total Waste Value</div>
                    <div class="text-2xl font-bold text-red-600">Rs {{ number_format($totalWasteValue) }}</div>
                </div>
            </div>
            
            <div class="space-y-3">
                @foreach($wasteRecords as $waste)
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-red-50 border-2 border-red-100">
                        <div class="w-12 h-12 bg-red-200 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="font-medium text-gray-900">{{ $waste['itemName'] }}</h4>
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">{{ $waste['quantity'] }} {{ $waste['unit'] }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">Batch: <span class="font-mono">{{ $waste['batchNumber'] }}</span> • {{ $waste['reason'] }}</p>
                            <div class="text-xs text-gray-500">{{ $waste['recordedBy'] }} • {{ $waste['timestamp'] }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600 mb-1">Value Lost</div>
                            <div class="text-xl font-bold text-red-600">Rs {{ number_format($waste['value']) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-gray-100">
                <div class="text-sm text-gray-600 mb-2">Total Items Wasted</div>
                <div class="text-3xl font-bold text-red-600">{{ array_reduce($wasteRecords, fn($s, $w) => $s + $w['quantity'], 0) }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-gray-100">
                <div class="text-sm text-gray-600 mb-2">Waste Rate Today</div>
                <div class="text-3xl font-bold text-orange-600">{{ $todayProduction > 0 ? number_format(($todayWaste / $todayProduction) * 100, 1) : '0.0' }}%</div>
            </div>
             <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-gray-100">
                <div class="text-sm text-gray-600 mb-2">Value Lost Today</div>
                <div class="text-3xl font-bold text-red-600">Rs {{ number_format($totalWasteValue) }}</div>
            </div>
        </div>
    </div>

    {{-- 
        ================= MODALS ================= 
    --}}
    
    {{-- ITEM DETAIL MODAL --}}
    <div id="modal-detail" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 shadow-2xl relative">
            <button onclick="closeModals()" class="absolute top-6 right-6 p-2 bg-gray-100 rounded-full hover:bg-gray-200">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            
            <div class="flex items-center gap-3 mb-6">
                <span id="dt-icon" class="text-5xl"></span>
                <div>
                    <h2 id="dt-name" class="text-2xl font-bold text-gray-900"></h2>
                    <p id="dt-meta" class="text-gray-600"></p>
                </div>
            </div>

            <div class="space-y-5">
                {{-- Batch Info --}}
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-5 border-2 border-orange-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Batch Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div><div class="text-sm text-gray-600 mb-1">Batch Number</div><div id="dt-batch" class="text-base font-mono font-bold text-gray-900"></div></div>
                        <div><div class="text-sm text-gray-600 mb-1">Production Date</div><div id="dt-prod-date" class="text-base font-bold text-gray-900"></div></div>
                        <div><div class="text-sm text-gray-600 mb-1">Expiry Date</div><div id="dt-exp-date" class="text-base font-bold text-gray-900"></div></div>
                        <div><div class="text-sm text-gray-600 mb-1">Time Remaining</div><div id="dt-hours" class="text-base font-bold"></div></div>
                    </div>
                </div>

                {{-- Stock & Sales --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 rounded-xl p-4 border-2 border-blue-200">
                        <div class="text-sm text-gray-600 mb-1">Current Stock</div>
                        <div id="dt-stock" class="text-3xl font-bold text-blue-600"></div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border-2 border-green-200">
                        <div class="text-sm text-gray-600 mb-1">Sold Today</div>
                        <div id="dt-sold" class="text-3xl font-bold text-green-600"></div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4"><div class="text-sm text-gray-600 mb-1">Unit Cost</div><div id="dt-cost" class="text-xl font-bold text-gray-900"></div></div>
                    <div class="bg-gray-50 rounded-xl p-4"><div class="text-sm text-gray-600 mb-1">Selling Price</div><div id="dt-price" class="text-xl font-bold text-green-600"></div></div>
                    <div class="bg-gray-50 rounded-xl p-4"><div class="text-sm text-gray-600 mb-1">Margin</div><div id="dt-margin" class="text-xl font-bold text-blue-600"></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK SALE MODAL --}}
    <div id="modal-sale" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-xl p-6 shadow-2xl">
            <div class="mb-6">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    Record Sale
                </h2>
                <p id="sale-subtitle" class="text-gray-600"></p>
            </div>
            
            <input type="hidden" id="sale-id">
            
            <div class="space-y-5">
                <div class="bg-gray-50 rounded-xl p-4 flex items-center gap-4">
                    <span id="sale-icon" class="text-5xl"></span>
                    <div>
                        <h3 id="sale-name" class="text-lg font-medium text-gray-900"></h3>
                        <p id="sale-price-info" class="text-sm text-gray-600"></p>
                    </div>
                </div>
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-3">Quantity Sold</label>
                    <div class="flex items-center gap-3">
                        <input type="number" id="sale-qty" class="flex-1 h-14 px-4 text-xl rounded-xl border-2 border-gray-200 focus:border-green-500 outline-none">
                        <span id="sale-unit" class="text-xl text-gray-600"></span>
                    </div>
                </div>
                <div class="flex gap-3 pt-4 border-t-2 border-gray-200">
                    <button onclick="closeModals()" class="flex-1 h-14 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium">Cancel</button>
                    <button onclick="submitSale()" class="flex-1 h-14 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl font-medium shadow-lg">Record Sale</button>
                </div>
            </div>
        </div>
    </div>

    {{-- WASTE LOG MODAL --}}
    <div id="modal-waste" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-xl p-6 shadow-2xl">
            <div class="mb-6">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Log Waste
                </h2>
                <p id="waste-subtitle" class="text-gray-600"></p>
            </div>

            <input type="hidden" id="waste-id">

            <div class="space-y-5">
                 <div class="bg-gray-50 rounded-xl p-4 flex items-center gap-4">
                    <span id="waste-icon" class="text-5xl"></span>
                    <div>
                        <h3 id="waste-name" class="text-lg font-medium text-gray-900"></h3>
                        <p id="waste-info" class="text-sm text-gray-600"></p>
                    </div>
                </div>
                
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-3">Waste Quantity</label>
                    <div class="flex items-center gap-3">
                        <input type="number" id="waste-qty" class="flex-1 h-14 px-4 text-xl rounded-xl border-2 border-gray-200 focus:border-red-500 outline-none">
                        <span id="waste-unit" class="text-xl text-gray-600"></span>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-medium text-gray-700 mb-3">Reason</label>
                    <div class="grid grid-cols-2 gap-2" id="waste-reasons">
                        {{-- Buttons injected via JS --}}
                    </div>
                    <input type="hidden" id="waste-reason-val">
                </div>

                <div class="flex gap-3 pt-4 border-t-2 border-gray-200">
                    <button onclick="closeModals()" class="flex-1 h-14 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium">Cancel</button>
                    <button onclick="submitWaste()" class="flex-1 h-14 bg-gradient-to-br from-red-500 to-red-600 text-white rounded-xl font-medium shadow-lg">Log Waste</button>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- JAVASCRIPT LOGIC --}}
<script>
    const inventoryData = @json($inventory);
    
    // Global State
    let currentView = 'stock';
    let searchQuery = '';
    let selectedCategory = 'all';

    // DOM Elements
    const searchInput = document.getElementById('search-input');
    const tabBtns = document.querySelectorAll('.tab-btn');
    const categoryBtns = document.querySelectorAll('.category-btn');
    const filterContainer = document.getElementById('filter-container');
    const viewGrid = document.getElementById('view-grid');
    const viewProduction = document.getElementById('view-production');
    const viewWaste = document.getElementById('view-waste');
    const inventoryCards = document.querySelectorAll('.inventory-card');

    // --- 1. Tab Switching ---
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const view = btn.dataset.view;
            currentView = view;

            // Update Tab UI
            tabBtns.forEach(b => {
                b.classList.remove('bg-gradient-to-br', 'from-amber-500', 'to-orange-600', 'text-white', 'shadow-lg', 'active');
                b.classList.add('bg-white', 'text-gray-700');
            });
            btn.classList.remove('bg-white', 'text-gray-700');
            btn.classList.add('bg-gradient-to-br', 'from-amber-500', 'to-orange-600', 'text-white', 'shadow-lg', 'active');

            // Toggle Content
            viewGrid.classList.add('hidden');
            viewProduction.classList.add('hidden');
            viewWaste.classList.add('hidden');
            filterContainer.classList.add('hidden');

            if (view === 'waste') {
                viewWaste.classList.remove('hidden');
            } else if (view === 'production') {
                viewProduction.classList.remove('hidden');
            } else {
                // Stock or Expiring
                viewGrid.classList.remove('hidden');
                filterContainer.classList.remove('hidden');
                filterItems();
            }
        });
    });

    // --- 2. Filtering ---
    function filterItems() {
        inventoryCards.forEach(card => {
            const name = card.dataset.name;
            const category = card.dataset.category;
            const hours = parseInt(card.dataset.hours);
            
            const matchesSearch = name.includes(searchQuery) || category.includes(searchQuery);
            const matchesCategory = selectedCategory === 'all' || category === selectedCategory;
            let matchesView = true;
            
            if (currentView === 'expiring') {
                matchesView = hours <= 24;
            }

            if (matchesSearch && matchesCategory && matchesView) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', (e) => {
        searchQuery = e.target.value.toLowerCase();
        filterItems();
    });

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // UI Toggle
            categoryBtns.forEach(b => {
                b.classList.remove('bg-gradient-to-br', 'from-orange-500', 'to-amber-600', 'text-white', 'shadow-md', 'active');
                b.classList.add('bg-white', 'text-gray-700', 'border-2', 'border-gray-200');
            });
            btn.classList.remove('bg-white', 'text-gray-700', 'border-2', 'border-gray-200');
            btn.classList.add('bg-gradient-to-br', 'from-orange-500', 'to-amber-600', 'text-white', 'shadow-md', 'active');

            selectedCategory = btn.dataset.category;
            filterItems();
        });
    });

    // --- 3. Modals ---
    function closeModals() {
        document.getElementById('modal-detail').classList.add('hidden');
        document.getElementById('modal-sale').classList.add('hidden');
        document.getElementById('modal-waste').classList.add('hidden');
    }

    function getItem(id) {
        return inventoryData.find(i => i.id === id);
    }

    window.openDetailModal = function(id) {
        const item = getItem(id);
        if(!item) return;

        document.getElementById('dt-icon').textContent = item.icon;
        document.getElementById('dt-name').textContent = item.name;
        document.getElementById('dt-meta').textContent = `${item.categoryName} • Batch ${item.batchNumber}`;
        document.getElementById('dt-batch').textContent = item.batchNumber;
        document.getElementById('dt-prod-date').textContent = item.productionDate;
        document.getElementById('dt-exp-date').textContent = item.expiryDate;
        
        const hoursEl = document.getElementById('dt-hours');
        hoursEl.textContent = `${item.hoursRemaining} hours`;
        hoursEl.className = item.hoursRemaining <= 12 ? 'text-base font-bold text-red-600' : 'text-base font-bold text-green-600';

        document.getElementById('dt-stock').textContent = `${item.currentStock} ${item.unit}`;
        document.getElementById('dt-sold').textContent = `${item.soldQuantity} ${item.unit}`;
        
        document.getElementById('dt-cost').textContent = `Rs ${item.unitPrice}`;
        document.getElementById('dt-price').textContent = `Rs ${item.sellingPrice}`;
        
        const margin = ((item.sellingPrice - item.unitPrice) / item.sellingPrice) * 100;
        document.getElementById('dt-margin').textContent = `${margin.toFixed(0)}%`;

        document.getElementById('modal-detail').classList.remove('hidden');
    }

    window.openSaleModal = function(id) {
        const item = getItem(id);
        if(!item) return;

        document.getElementById('sale-id').value = id;
        document.getElementById('sale-qty').value = '';
        document.getElementById('sale-subtitle').textContent = `${item.name} • Available: ${item.currentStock} ${item.unit}`;
        document.getElementById('sale-icon').textContent = item.icon;
        document.getElementById('sale-name').textContent = item.name;
        document.getElementById('sale-price-info').textContent = `Rs ${item.sellingPrice} per ${item.unit}`;
        document.getElementById('sale-unit').textContent = item.unit;

        document.getElementById('modal-sale').classList.remove('hidden');
    }

    window.openWasteModal = function(id) {
        const item = getItem(id);
        if(!item) return;

        document.getElementById('waste-id').value = id;
        document.getElementById('waste-qty').value = '';
        document.getElementById('waste-subtitle').textContent = `${item.name} • Batch: ${item.batchNumber}`;
        document.getElementById('waste-icon').textContent = item.icon;
        document.getElementById('waste-name').textContent = item.name;
        document.getElementById('waste-info').textContent = `Available: ${item.currentStock} ${item.unit}`;
        document.getElementById('waste-unit').textContent = item.unit;

        // Generate Reasons
        const reasons = ['Expired - Not sold', 'Quality Issue - Burnt', 'Quality Issue - Undercooked', 'Damaged - Dropped', 'Stale', 'Other'];
        const container = document.getElementById('waste-reasons');
        container.innerHTML = '';
        reasons.forEach(r => {
            const btn = document.createElement('button');
            btn.className = 'p-3 rounded-xl border-2 transition-all text-sm border-gray-200 bg-white text-gray-700 hover:bg-gray-50';
            btn.textContent = r;
            btn.onclick = () => {
                Array.from(container.children).forEach(c => c.className = 'p-3 rounded-xl border-2 transition-all text-sm border-gray-200 bg-white text-gray-700 hover:bg-gray-50');
                btn.className = 'p-3 rounded-xl border-2 transition-all text-sm border-red-500 bg-red-50 text-red-900';
                document.getElementById('waste-reason-val').value = r;
            };
            container.appendChild(btn);
        });

        document.getElementById('modal-waste').classList.remove('hidden');
    }

    window.submitSale = function() {
        alert('Sale Recorded! (Sync with backend would happen here)');
        closeModals();
    }

    window.submitWaste = function() {
        alert('Waste Logged! (Sync with backend would happen here)');
        closeModals();
    }
</script>
@endsection