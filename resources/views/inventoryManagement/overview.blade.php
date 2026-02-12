@extends('layouts.app')
@section('title', 'Inventory Management')

@section('content')

{{-- 
    LOGIC BLOCK: Data & Calculations
    In a real app, this logic belongs in your Controller (e.g., InventoryController).
--}}
@php
    // 1. Mock Inventory Items
    $inventoryItems = [
        [
            'id' => 'item-001', 'itemCode' => 'FLR-APF-001', 'name' => 'All Purpose Flour', 'category' => 'Flour', 'section' => 'bakery', 'unit' => 'kg',
            'currentStock' => 450, 'reorderPoint' => 200, 'maxStock' => 1000, 'unitCost' => 1.20, 'totalValue' => 540.00, 'status' => 'in-stock',
            'supplier' => 'ABC Mills', 'lastUpdated' => '2024-12-04T10:30:00',
            'batches' => [
                ['id' => 'b1', 'batchNumber' => 'FLR-241201-A', 'quantity' => 250, 'unitCost' => 1.20, 'manufacturedDate' => '2024-12-01', 'expiryDate' => '2025-06-01', 'receivedDate' => '2024-12-02', 'supplier' => 'ABC Mills', 'location' => 'Bakery-A3', 'status' => 'active', 'daysUntilExpiry' => 179],
                ['id' => 'b2', 'batchNumber' => 'FLR-241125-B', 'quantity' => 200, 'unitCost' => 1.18, 'manufacturedDate' => '2024-11-25', 'expiryDate' => '2025-05-25', 'receivedDate' => '2024-11-26', 'supplier' => 'ABC Mills', 'location' => 'Bakery-A2', 'status' => 'active', 'daysUntilExpiry' => 172]
            ]
        ],
        [
            'id' => 'item-002', 'itemCode' => 'BTR-UNS-001', 'name' => 'Unsalted Butter', 'category' => 'Dairy', 'section' => 'kitchen', 'unit' => 'kg',
            'currentStock' => 35, 'reorderPoint' => 50, 'maxStock' => 150, 'unitCost' => 8.50, 'totalValue' => 297.50, 'status' => 'low-stock',
            'supplier' => 'Dairy Fresh Co.', 'lastUpdated' => '2024-12-04T09:15:00',
            'batches' => [
                ['id' => 'b3', 'batchNumber' => 'BTR-241203-A', 'quantity' => 20, 'unitCost' => 8.50, 'manufacturedDate' => '2024-12-03', 'expiryDate' => '2025-01-15', 'receivedDate' => '2024-12-03', 'supplier' => 'Dairy Fresh Co.', 'location' => 'Kitchen-Cooler-1', 'status' => 'active', 'daysUntilExpiry' => 42],
                ['id' => 'b4', 'batchNumber' => 'BTR-241128-B', 'quantity' => 15, 'unitCost' => 8.40, 'manufacturedDate' => '2024-11-28', 'expiryDate' => '2025-01-10', 'receivedDate' => '2024-11-29', 'supplier' => 'Dairy Fresh Co.', 'location' => 'Kitchen-Cooler-1', 'status' => 'active', 'daysUntilExpiry' => 37]
            ]
        ],
        [
            'id' => 'item-003', 'itemCode' => 'CHC-DRK-001', 'name' => 'Dark Chocolate (70%)', 'category' => 'Chocolate', 'section' => 'cake', 'unit' => 'kg',
            'currentStock' => 85, 'reorderPoint' => 40, 'maxStock' => 200, 'unitCost' => 12.75, 'totalValue' => 1083.75, 'status' => 'in-stock',
            'supplier' => 'Premium Cocoa Ltd.', 'lastUpdated' => '2024-12-04T08:45:00',
            'batches' => [
                ['id' => 'b5', 'batchNumber' => 'CHC-241130-A', 'quantity' => 50, 'unitCost' => 12.75, 'manufacturedDate' => '2024-11-30', 'expiryDate' => '2025-11-30', 'receivedDate' => '2024-12-01', 'supplier' => 'Premium Cocoa Ltd.', 'location' => 'Cake-Dry-2', 'status' => 'active', 'daysUntilExpiry' => 361],
                ['id' => 'b6', 'batchNumber' => 'CHC-241115-B', 'quantity' => 35, 'unitCost' => 12.50, 'manufacturedDate' => '2024-11-15', 'expiryDate' => '2025-11-15', 'receivedDate' => '2024-11-16', 'supplier' => 'Premium Cocoa Ltd.', 'location' => 'Cake-Dry-2', 'status' => 'active', 'daysUntilExpiry' => 346]
            ]
        ],
        [
            'id' => 'item-005', 'itemCode' => 'CRM-HVY-001', 'name' => 'Heavy Cream (35%)', 'category' => 'Dairy', 'section' => 'cake', 'unit' => 'L',
            'currentStock' => 28, 'reorderPoint' => 30, 'maxStock' => 100, 'unitCost' => 6.50, 'totalValue' => 182.00, 'status' => 'low-stock',
            'supplier' => 'Dairy Fresh Co.', 'lastUpdated' => '2024-12-04T11:00:00',
            'batches' => [
                ['id' => 'b9', 'batchNumber' => 'CRM-241203-A', 'quantity' => 18, 'unitCost' => 6.50, 'manufacturedDate' => '2024-12-03', 'expiryDate' => '2024-12-17', 'receivedDate' => '2024-12-03', 'supplier' => 'Dairy Fresh Co.', 'location' => 'Cake-Cooler-1', 'status' => 'active', 'daysUntilExpiry' => 13],
                ['id' => 'b10', 'batchNumber' => 'CRM-241130-B', 'quantity' => 10, 'unitCost' => 6.45, 'manufacturedDate' => '2024-11-30', 'expiryDate' => '2024-12-14', 'receivedDate' => '2024-12-01', 'supplier' => 'Dairy Fresh Co.', 'location' => 'Cake-Cooler-1', 'status' => 'active', 'daysUntilExpiry' => 10]
            ]
        ],
        [
            'id' => 'item-007', 'itemCode' => 'VNL-EXT-001', 'name' => 'Pure Vanilla Extract', 'category' => 'Flavoring', 'section' => 'cake', 'unit' => 'L',
            'currentStock' => 3.5, 'reorderPoint' => 5, 'maxStock' => 20, 'unitCost' => 45.00, 'totalValue' => 157.50, 'status' => 'low-stock',
            'supplier' => 'Flavor Essence Co.', 'lastUpdated' => '2024-12-04T10:00:00',
            'batches' => [
                ['id' => 'b13', 'batchNumber' => 'VNL-241115-A', 'quantity' => 2.0, 'unitCost' => 45.00, 'manufacturedDate' => '2024-11-15', 'expiryDate' => '2026-11-15', 'receivedDate' => '2024-11-20', 'supplier' => 'Flavor Essence Co.', 'location' => 'Cake-Dry-1', 'status' => 'active', 'daysUntilExpiry' => 711]
            ]
        ]
    ];

    // 2. Mock Transfers
    $transfers = [
        ['id' => 'tf-001', 'transferNumber' => 'TRF-2024-001', 'fromSection' => 'bakery', 'toSection' => 'kitchen', 'requestedBy' => 'Mike Perera', 'requestedDate' => '2024-12-04T09:00:00', 'status' => 'completed', 'items' => [['itemName' => 'All Purpose Flour', 'quantity' => 25, 'unit' => 'kg']]],
        ['id' => 'tf-002', 'transferNumber' => 'TRF-2024-002', 'fromSection' => 'kitchen', 'toSection' => 'cake', 'requestedBy' => 'Sarah Fernando', 'requestedDate' => '2024-12-04T11:00:00', 'status' => 'pending', 'items' => [['itemName' => 'Unsalted Butter', 'quantity' => 10, 'unit' => 'kg']]],
        ['id' => 'tf-003', 'transferNumber' => 'TRF-2024-003', 'fromSection' => 'cake', 'toSection' => 'bakery', 'requestedBy' => 'John Silva', 'requestedDate' => '2024-12-03T14:30:00', 'status' => 'in-transit', 'items' => [['itemName' => 'Dark Chocolate', 'quantity' => 15, 'unit' => 'kg']]]
    ];

    // 3. Mock Alerts
    $reorderAlerts = [
        ['id' => 'ro-001', 'itemName' => 'Unsalted Butter', 'section' => 'kitchen', 'currentStock' => 35, 'reorderPoint' => 50, 'suggestedOrderQty' => 100, 'supplier' => 'Dairy Fresh Co.', 'urgency' => 'high', 'daysOfStock' => 4],
        ['id' => 'ro-003', 'itemName' => 'Heavy Cream (35%)', 'section' => 'cake', 'currentStock' => 28, 'reorderPoint' => 30, 'suggestedOrderQty' => 60, 'supplier' => 'Dairy Fresh Co.', 'urgency' => 'high', 'daysOfStock' => 3],
        ['id' => 'ro-005', 'itemName' => 'Pure Vanilla Extract', 'section' => 'cake', 'currentStock' => 3.5, 'reorderPoint' => 5, 'suggestedOrderQty' => 15, 'supplier' => 'Flavor Essence Co.', 'urgency' => 'critical', 'daysOfStock' => 2]
    ];

    // 4. Mock Stock Counts
    $stockCounts = [
        ['id' => 'sc-001', 'countNumber' => 'SC-2024-001', 'section' => 'bakery', 'scheduledDate' => '2024-12-05', 'status' => 'scheduled', 'totalVariance' => 0],
        ['id' => 'sc-002', 'countNumber' => 'SC-2024-002', 'section' => 'kitchen', 'scheduledDate' => '2024-12-01', 'completedDate' => '2024-12-01T16:00:00', 'status' => 'completed', 'totalVariance' => -2]
    ];

    // 5. Calculations
    $totalInventoryValue = array_reduce($inventoryItems, fn($s, $i) => $s + $i['totalValue'], 0);
    $lowStockItems = count(array_filter($inventoryItems, fn($i) => in_array($i['status'], ['low-stock', 'out-of-stock'])));
    $expiringItems = 0;
    foreach($inventoryItems as $item) {
        $expiringItems += count(array_filter($item['batches'], fn($b) => $b['daysUntilExpiry'] <= 30 && $b['status'] === 'active'));
    }
    $totalActiveBatches = array_reduce($inventoryItems, fn($s, $i) => $s + count(array_filter($i['batches'], fn($b) => $b['status'] === 'active')), 0);

    // 6. Helpers
    function getSectionColor($section) {
        return match($section) {
            'kitchen' => 'bg-purple-100 text-purple-700 border-purple-200',
            'cake' => 'bg-pink-100 text-pink-700 border-pink-200',
            'bakery' => 'bg-amber-100 text-amber-700 border-amber-200',
            default => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }
    function getStatusColor($status) {
        return match($status) {
            'in-stock' => 'bg-green-100 text-green-700 border-green-200',
            'low-stock' => 'bg-orange-100 text-orange-700 border-orange-200',
            'out-of-stock' => 'bg-red-100 text-red-700 border-red-200',
            'expiring-soon' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            default => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }
    function getSectionStats($items, $section) {
        $sectionItems = array_filter($items, fn($i) => $i['section'] === $section);
        return [
            'totalItems' => count($sectionItems),
            'totalValue' => array_reduce($sectionItems, fn($s, $i) => $s + $i['totalValue'], 0),
            'inStock' => count(array_filter($sectionItems, fn($i) => $i['status'] === 'in-stock')),
            'lowStock' => count(array_filter($sectionItems, fn($i) => in_array($i['status'], ['low-stock', 'out-of-stock']))),
        ];
    }
@endphp

<div class="min-h-screen bg-[#F5F5F7] pb-10">
    
    {{-- Header --}}
    <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6 sticky top-0 z-30">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                        {{-- Icon: Package --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Inventory Management</h1>
                        <p class="text-gray-500 text-xs sm:text-sm">Multi-section inventory with FEFO tracking</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                <button onclick="openModal('scanModal')" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>
                    <span class="hidden sm:inline">Scan</span>
                </button>
                <button onclick="openModal('countModal')" class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                    <span class="hidden sm:inline">Stock Count</span>
                </button>
                <button onclick="openModal('transferModal')" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-blue-500 to-purple-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 3 4 4-4 4"/><path d="M20 7H4"/><path d="m8 21-4-4 4-4"/><path d="M4 17h16"/></svg>
                    <span class="hidden sm:inline">Transfer</span>
                </button>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 max-w-[1800px] mx-auto">
        
        {{-- Top Statistics Bar --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-4 sm:mb-6">
            {{-- Value --}}
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-xs sm:text-sm mb-1">Total Inventory Value</div>
                        <div class="text-xl sm:text-3xl font-semibold text-gray-900">Rs {{ number_format($totalInventoryValue) }}</div>
                        <div class="text-xs mt-1 text-gray-400">Last updated today</div>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="1" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                </div>
            </div>
            {{-- Low Stock --}}
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-xs sm:text-sm mb-1">Low Stock Items</div>
                        <div class="text-xl sm:text-3xl font-semibold text-gray-900">{{ $lowStockItems }}</div>
                        <div class="text-xs mt-1 text-gray-400">{{ count($reorderAlerts) }} reorder alerts</div>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                    </div>
                </div>
            </div>
            {{-- Expiring --}}
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-xs sm:text-sm mb-1">Expiring Soon</div>
                        <div class="text-xl sm:text-3xl font-semibold text-gray-900">{{ $expiringItems }}</div>
                        <div class="text-xs mt-1 text-gray-400">Within 30 days</div>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                </div>
            </div>
            {{-- Total Items --}}
            <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-gray-500 text-xs sm:text-sm mb-1">Total Items</div>
                        <div class="text-xl sm:text-3xl font-semibold text-gray-900">{{ count($inventoryItems) }}</div>
                        <div class="text-xs mt-1 text-gray-400">{{ $totalActiveBatches }} active batches</div>
                    </div>
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Selector Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @foreach(['kitchen', 'cake', 'bakery'] as $section)
                @php $stats = getSectionStats($inventoryItems, $section); @endphp
                <div onclick="filterSection('{{ $section }}')" class="section-card bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all cursor-pointer border-2 border-gray-100 group" data-section="{{ $section }}">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl {{ getSectionColor($section) }} flex items-center justify-center">
                                @if($section == 'kitchen')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" x2="18" y1="17" y2="17"/></svg>
                                @elseif($section == 'cake')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="10" r="7"/><path d="M21 21a2 2 0 0 0-2-2h-2a2 2 0 0 1-2-2v-2a2 2 0 0 0-2-2 2 2 0 0 1-2-2v-2a2 2 0 0 0-2-2"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg text-gray-900 font-medium capitalize">{{ $section }} Section</h3>
                                <p class="text-sm text-gray-500">{{ $stats['totalItems'] }} items</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="text-xs text-gray-500 mb-1">Value</div>
                            <div class="text-lg font-medium text-gray-900">Rs {{ number_format($stats['totalValue']) }}</div>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="text-xs text-gray-500 mb-1">In Stock</div>
                            <div class="text-lg font-medium text-green-600">{{ $stats['inStock'] }}</div>
                        </div>
                    </div>
                    @if($stats['lowStock'] > 0)
                        <div class="mt-3 flex items-center gap-2 text-sm text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                            <span>{{ $stats['lowStock'] }} items need reorder</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Tabs --}}
        <div class="space-y-6" id="inventoryTabs">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                    <button onclick="switchTab('overview')" id="tab-overview" class="tab-btn border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                        Inventory
                    </button>
                    <button onclick="switchTab('batches')" id="tab-batches" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                        Batch Tracking
                    </button>
                    <button onclick="switchTab('transfers')" id="tab-transfers" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 3 4 4-4 4"/><path d="M20 7H4"/><path d="m8 21-4-4 4-4"/><path d="M4 17h16"/></svg>
                        Transfers
                    </button>
                    <button onclick="switchTab('reorder')" id="tab-reorder" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        Reorder Alerts
                    </button>
                    <button onclick="switchTab('reports')" id="tab-reports" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                        Reports
                    </button>
                </nav>
            </div>

            {{-- 1. Overview Tab Content --}}
            <div id="content-overview" class="tab-content block">
                {{-- Search & Filters Bar --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="relative flex-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <input type="text" placeholder="Search by item name or code..." class="w-full pl-9 h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div class="flex items-center gap-2 flex-wrap lg:flex-nowrap">
                            <select class="h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>All Categories</option>
                                <option>Flour</option>
                                <option>Dairy</option>
                                <option>Chocolate</option>
                            </select>
                            <select class="h-10 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>All Status</option>
                                <option>In Stock</option>
                                <option>Low Stock</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Inventory Table --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left p-4 text-sm font-semibold text-gray-700">Item</th>
                                    <th class="text-left p-4 text-sm font-semibold text-gray-700">Section</th>
                                    <th class="text-left p-4 text-sm font-semibold text-gray-700">Current Stock</th>
                                    <th class="text-left p-4 text-sm font-semibold text-gray-700">Status</th>
                                    <th class="text-left p-4 text-sm font-semibold text-gray-700">Unit Cost</th>
                                    <th class="text-left p-4 text-sm font-semibold text-gray-700">Total Value</th>
                                    <th class="text-left p-4 text-sm font-semibold text-gray-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($inventoryItems as $item)
                                    <tr class="hover:bg-gray-50 cursor-pointer transition-colors inventory-row" data-section="{{ $item['section'] }}" onclick="openItemModal({{ json_encode($item) }})">
                                        <td class="p-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                                </div>
                                                <div>
                                                    <div class="text-sm text-gray-900 font-medium">{{ $item['name'] }}</div>
                                                    <div class="text-xs text-gray-500">{{ $item['itemCode'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ getSectionColor($item['section']) }}">
                                                {{ ucfirst($item['section']) }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item['currentStock'] }} {{ $item['unit'] }}</div>
                                            <div class="w-20 h-1.5 bg-gray-200 rounded-full mt-1 overflow-hidden">
                                                <div class="h-full bg-blue-500" style="width: {{ min(($item['currentStock'] / $item['maxStock']) * 100, 100) }}%"></div>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ getStatusColor($item['status']) }}">
                                                {{ ucwords(str_replace('-', ' ', $item['status'])) }}
                                            </span>
                                        </td>
                                        <td class="p-4">
                                            <div class="text-sm text-gray-700">Rs {{ number_format($item['unitCost'], 2) }}</div>
                                        </td>
                                        <td class="p-4">
                                            <div class="text-sm font-medium text-green-600">Rs {{ number_format($item['totalValue'], 2) }}</div>
                                        </td>
                                        <td class="p-4">
                                            <button class="text-gray-400 hover:text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 2. Batches Tab Content --}}
            <div id="content-batches" class="tab-content hidden">
                 <div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-6 border border-purple-200 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg text-gray-900 mb-2 font-medium">FEFO Batch Management</h3>
                            <p class="text-sm text-gray-700">First Expired, First Out (FEFO) ensures oldest batches are used first.</p>
                        </div>
                    </div>
                </div>
                {{-- Mock Batch List --}}
                 @foreach($inventoryItems as $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
                        <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                             <div class="flex items-center gap-3">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ getSectionColor($item['section']) }}">{{ ucfirst($item['section']) }}</span>
                                <h3 class="font-medium text-gray-900">{{ $item['name'] }}</h3>
                            </div>
                            <span class="text-sm text-gray-600">{{ count($item['batches']) }} active batches</span>
                        </div>
                        <div class="p-4 space-y-3">
                            @foreach($item['batches'] as $batch)
                                <div class="p-4 rounded-lg border-2 {{ $loop->first ? 'border-blue-300 bg-blue-50' : 'border-gray-200 bg-gray-50' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-2">
                                            @if($loop->first) <span class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded">Use First</span> @endif
                                            <span class="font-medium text-gray-900">#{{ $batch['batchNumber'] }}</span>
                                        </div>
                                        <span class="font-bold text-gray-900">{{ $batch['quantity'] }} {{ $item['unit'] }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm text-gray-600">
                                        <div>Exp: {{ date('M d, Y', strtotime($batch['expiryDate'])) }}</div>
                                        <div class="{{ $batch['daysUntilExpiry'] <= 30 ? 'text-orange-600 font-medium' : 'text-green-600' }}">{{ $batch['daysUntilExpiry'] }} days left</div>
                                        <div>Loc: {{ $batch['location'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                 @endforeach
            </div>

            {{-- 3. Transfers Tab Content --}}
            <div id="content-transfers" class="tab-content hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg text-gray-900 font-medium mb-4">Recent Transfers</h3>
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left p-3 text-sm">Transfer #</th>
                                <th class="text-left p-3 text-sm">From</th>
                                <th class="text-left p-3 text-sm">To</th>
                                <th class="text-left p-3 text-sm">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transfers as $tf)
                                <tr class="border-b">
                                    <td class="p-3 text-sm font-medium">{{ $tf['transferNumber'] }}</td>
                                    <td class="p-3"><span class="capitalize text-sm">{{ $tf['fromSection'] }}</span></td>
                                    <td class="p-3"><span class="capitalize text-sm">{{ $tf['toSection'] }}</span></td>
                                    <td class="p-3"><span class="px-2 py-1 rounded text-xs bg-gray-100">{{ $tf['status'] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

             {{-- 4. Reorder Tab Content --}}
            <div id="content-reorder" class="tab-content hidden">
                 @foreach($reorderAlerts as $alert)
                    <div class="bg-white rounded-2xl shadow-sm border-2 border-orange-200 p-6 mb-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded uppercase">{{ $alert['urgency'] }}</span>
                                    <span class="text-xs font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded capitalize">{{ $alert['section'] }}</span>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $alert['itemName'] }}</h3>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-orange-600">{{ $alert['currentStock'] }}</div>
                                <div class="text-xs text-gray-500">current stock</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                            <div>
                                <div class="text-gray-500 text-xs">Reorder Point</div>
                                <div class="font-medium">{{ $alert['reorderPoint'] }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 text-xs">Suggested Order</div>
                                <div class="font-medium text-blue-600">{{ $alert['suggestedOrderQty'] }}</div>
                            </div>
                            <div>
                                <div class="text-gray-500 text-xs">Days Left</div>
                                <div class="font-medium text-red-600">{{ $alert['daysOfStock'] }} days</div>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t flex gap-3">
                            <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg text-sm hover:bg-blue-700">Create Purchase Order</button>
                        </div>
                    </div>
                 @endforeach
            </div>

            {{-- 5. Reports Tab Content --}}
            <div id="content-reports" class="tab-content hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Stock Valuation Report --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg text-gray-900 font-medium">Stock Valuation</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="1" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                <div class="text-sm text-green-700 mb-1">Total Inventory Value</div>
                                <div class="text-3xl font-bold text-green-900">Rs {{ number_format($totalInventoryValue, 2) }}</div>
                            </div>

                            <div class="space-y-2">
                                @foreach(['kitchen', 'cake', 'bakery'] as $section)
                                    @php $stats = getSectionStats($inventoryItems, $section); @endphp
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <span class="capitalize text-sm text-gray-700 font-medium">{{ $section }}</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">Rs {{ number_format($stats['totalValue'], 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <button class="w-full inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                Download Valuation Report
                            </button>
                        </div>
                    </div>

                    {{-- Stock Count History Report --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg text-gray-900 font-medium">Stock Count History</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                        </div>

                        <div class="space-y-3">
                            @foreach($stockCounts as $count)
                                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold {{ getSectionColor($count['section']) }}">
                                                {{ ucfirst($count['section']) }}
                                            </span>
                                            <span class="text-sm font-medium text-gray-900">{{ $count['countNumber'] }}</span>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $count['status'] === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($count['status']) }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $count['status'] === 'completed' ? 'Completed' : 'Scheduled' }} {{ date('M d, Y', strtotime(isset($count['completedDate']) ? $count['completedDate'] : $count['scheduledDate'])) }}
                                    </div>
                                    @if($count['status'] === 'completed')
                                        <div class="mt-2 pt-2 border-t border-gray-200 flex justify-between text-xs">
                                            <span class="text-gray-600">Variance</span>
                                            <span class="font-medium {{ $count['totalVariance'] < 0 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $count['totalVariance'] > 0 ? '+' : '' }}{{ $count['totalVariance'] }} units
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Movement Report --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg text-gray-900 font-medium">Movement Analysis</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        </div>
                        <div class="space-y-4">
                            <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <div class="text-sm text-purple-700 mb-1">Total Transfers (Month)</div>
                                <div class="text-3xl font-bold text-purple-900">{{ count($transfers) }}</div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Completed</span>
                                    <span class="font-medium text-green-600">{{ count(array_filter($transfers, fn($t) => $t['status'] === 'completed')) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">In Transit</span>
                                    <span class="font-medium text-blue-600">{{ count(array_filter($transfers, fn($t) => $t['status'] === 'in-transit')) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Pending</span>
                                    <span class="font-medium text-orange-600">{{ count(array_filter($transfers, fn($t) => $t['status'] === 'pending')) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Expiry Report --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg text-gray-900 font-medium">Expiry Tracking</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        
                        @php
                            $criticalExpiry = 0;
                            $warningExpiry = 0;
                            $safeExpiry = 0;
                            foreach($inventoryItems as $item) {
                                foreach($item['batches'] as $batch) {
                                    if($batch['status'] === 'active') {
                                        if($batch['daysUntilExpiry'] <= 7) $criticalExpiry++;
                                        elseif($batch['daysUntilExpiry'] <= 30) $warningExpiry++;
                                        else $safeExpiry++;
                                    }
                                }
                            }
                        @endphp

                        <div class="space-y-4">
                            <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="text-sm text-red-700 mb-1">Critical (≤7 days)</div>
                                <div class="text-3xl font-bold text-red-900">{{ $criticalExpiry }}</div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Warning (8-30 days)</span>
                                    <span class="font-medium text-orange-600">{{ $warningExpiry }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Safe (>30 days)</span>
                                    <span class="font-medium text-green-600">{{ $safeExpiry }}</span>
                                </div>
                            </div>
                            <button class="w-full inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                Download Expiry Report
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: Item Details --}}
    <div id="itemDetailModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('itemDetailModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto m-4 p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 id="modalItemName" class="text-2xl font-bold text-gray-900"></h2>
                    <p id="modalItemCode" class="text-sm text-gray-500"></p>
                </div>
                <button onclick="closeModal('itemDetailModal')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500 mb-1">Current Stock</div>
                    <div id="modalCurrentStock" class="text-2xl font-bold text-gray-900"></div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500 mb-1">Reorder Point</div>
                    <div id="modalReorderPoint" class="text-2xl font-bold text-orange-600"></div>
                </div>
                 <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500 mb-1">Unit Cost</div>
                    <div id="modalUnitCost" class="text-2xl font-bold text-gray-900"></div>
                </div>
                 <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500 mb-1">Total Value</div>
                    <div id="modalTotalValue" class="text-2xl font-bold text-green-600"></div>
                </div>
            </div>

            <h3 class="text-lg font-medium text-gray-900 mb-3">Batches</h3>
            <div id="modalBatchesList" class="space-y-3">
                </div>
        </div>
    </div>

    {{-- MODAL: Scan (Placeholder) --}}
    <div id="scanModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('scanModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 text-center">
             <div class="mb-4 flex justify-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Barcode Scanner</h3>
            <p class="text-gray-500 mb-6">Camera access would be requested here.</p>
            <button onclick="closeModal('scanModal')" class="w-full bg-blue-600 text-white py-2 rounded-lg">Close</button>
        </div>
    </div>

    {{-- MODAL: Transfer (Placeholder) --}}
    <div id="transferModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('transferModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Create Transfer</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">From Section</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2"><option>Kitchen</option><option>Bakery</option></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">To Section</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2"><option>Bakery</option><option>Kitchen</option></select>
                </div>
                <button onclick="closeModal('transferModal')" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 rounded-lg mt-4">Create Transfer</button>
            </div>
        </div>
    </div>
    
     {{-- MODAL: Count (Placeholder) --}}
    <div id="countModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('countModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Stock Count</h3>
            <p class="text-gray-500 mb-4">Start a physical stock reconciliation.</p>
            <button onclick="closeModal('countModal')" class="w-full bg-green-600 text-white py-2 rounded-lg">Start Count</button>
        </div>
    </div>

</div>

<script>
    // Tab Switching Logic
    function switchTab(tabId) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('block'));
        
        // Reset all buttons
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('border-blue-500', 'text-blue-600');
            el.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected content
        document.getElementById('content-' + tabId).classList.remove('hidden');
        document.getElementById('content-' + tabId).classList.add('block');

        // Highlight selected button
        const btn = document.getElementById('tab-' + tabId);
        btn.classList.remove('border-transparent', 'text-gray-500');
        btn.classList.add('border-blue-500', 'text-blue-600');
    }

    // Modal Logic
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Item Detail Modal Population
    function openItemModal(item) {
        document.getElementById('modalItemName').textContent = item.name;
        document.getElementById('modalItemCode').textContent = item.itemCode;
        
        document.getElementById('modalCurrentStock').textContent = `${item.currentStock} ${item.unit}`;
        document.getElementById('modalReorderPoint').textContent = `${item.reorderPoint} ${item.unit}`;
        document.getElementById('modalUnitCost').textContent = `Rs ${parseFloat(item.unitCost).toFixed(2)}`;
        document.getElementById('modalTotalValue').textContent = `Rs ${parseFloat(item.totalValue).toFixed(2)}`;

        const batchList = document.getElementById('modalBatchesList');
        batchList.innerHTML = ''; // Clear previous

        item.batches.forEach((batch, index) => {
            const isFirst = index === 0;
            const html = `
                <div class="p-4 rounded-lg border-2 ${isFirst ? 'border-blue-300 bg-blue-50' : 'border-gray-200 bg-gray-50'}">
                    <div class="flex justify-between mb-2">
                        <div class="font-medium text-gray-900">#${batch.batchNumber}</div>
                        <div class="font-bold text-gray-900">${batch.quantity} ${item.unit}</div>
                    </div>
                    <div class="text-sm text-gray-600 flex gap-4">
                        <span>Exp: ${batch.expiryDate}</span>
                        <span class="${batch.daysUntilExpiry <= 30 ? 'text-orange-600' : 'text-green-600'}">${batch.daysUntilExpiry} days left</span>
                    </div>
                </div>
            `;
            batchList.innerHTML += html;
        });

        openModal('itemDetailModal');
    }

    // Section Filtering Logic (Client-side simulation)
    function filterSection(section) {
        // Highlight card
        document.querySelectorAll('.section-card').forEach(el => {
            if(el.dataset.section === section) {
                el.classList.add('border-blue-500', 'ring-2', 'ring-blue-100');
                el.classList.remove('border-gray-100');
            } else {
                el.classList.remove('border-blue-500', 'ring-2', 'ring-blue-100');
                el.classList.add('border-gray-100');
            }
        });

        // Filter Table Rows
        document.querySelectorAll('.inventory-row').forEach(row => {
            if (row.dataset.section === section) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>

@endsection