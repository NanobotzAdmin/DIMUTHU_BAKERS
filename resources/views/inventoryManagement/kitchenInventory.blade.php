@extends('layouts.app')
@section('title', 'Kitchen Inventory')

@section('content')

{{-- 
    LOGIC BLOCK: Data & Calculations 
--}}
@php
    // 1. Mock Kitchen Items
    $kitchenItems = [
        [
            'id' => '1', 'name' => 'All-Purpose Flour', 'category' => 'Dry Goods', 'icon' => 'leaf', 'currentStock' => 45, 'unit' => 'kg',
            'maxStock' => 100, 'minStock' => 20, 'status' => 'good', 'temperature' => null, 'lastUsed' => '2 hours ago', 'avgDailyUsage' => 8.5,
            'batches' => [
                ['id' => 'B001', 'quantity' => 25, 'expiryDate' => '2024-12-20', 'daysUntilExpiry' => 16, 'location' => 'Shelf A1'],
                ['id' => 'B002', 'quantity' => 20, 'expiryDate' => '2025-01-15', 'daysUntilExpiry' => 42, 'location' => 'Shelf A2']
            ]
        ],
        [
            'id' => '2', 'name' => 'Fresh Eggs', 'category' => 'Refrigerated', 'icon' => 'egg', 'currentStock' => 120, 'unit' => 'pcs',
            'maxStock' => 300, 'minStock' => 50, 'status' => 'warning', 'temperature' => 4, 'tempStatus' => 'good', 'lastUsed' => '30 mins ago', 'avgDailyUsage' => 45,
            'batches' => [
                ['id' => 'B003', 'quantity' => 60, 'expiryDate' => '2024-12-08', 'daysUntilExpiry' => 4, 'location' => 'Fridge A'],
                ['id' => 'B004', 'quantity' => 60, 'expiryDate' => '2024-12-12', 'daysUntilExpiry' => 8, 'location' => 'Fridge B']
            ]
        ],
        [
            'id' => '3', 'name' => 'Butter', 'category' => 'Refrigerated', 'icon' => 'droplet', 'currentStock' => 8, 'unit' => 'kg',
            'maxStock' => 25, 'minStock' => 10, 'status' => 'critical', 'temperature' => 3, 'tempStatus' => 'good', 'lastUsed' => '1 hour ago', 'avgDailyUsage' => 3.2,
            'batches' => [
                ['id' => 'B005', 'quantity' => 8, 'expiryDate' => '2024-12-25', 'daysUntilExpiry' => 21, 'location' => 'Fridge C']
            ]
        ],
        [
            'id' => '4', 'name' => 'Sugar', 'category' => 'Dry Goods', 'icon' => 'archive', 'currentStock' => 65, 'unit' => 'kg',
            'maxStock' => 100, 'minStock' => 30, 'status' => 'good', 'temperature' => null, 'lastUsed' => '3 hours ago', 'avgDailyUsage' => 12,
            'batches' => [
                ['id' => 'B006', 'quantity' => 40, 'expiryDate' => '2025-06-30', 'daysUntilExpiry' => 208, 'location' => 'Shelf B1'],
                ['id' => 'B007', 'quantity' => 25, 'expiryDate' => '2025-08-15', 'daysUntilExpiry' => 254, 'location' => 'Shelf B2']
            ]
        ],
        [
            'id' => '5', 'name' => 'Milk', 'category' => 'Refrigerated', 'icon' => 'droplet', 'currentStock' => 15, 'unit' => 'L',
            'maxStock' => 40, 'minStock' => 10, 'status' => 'warning', 'temperature' => 4, 'tempStatus' => 'good', 'lastUsed' => '45 mins ago', 'avgDailyUsage' => 6.5,
            'batches' => [
                ['id' => 'B008', 'quantity' => 10, 'expiryDate' => '2024-12-06', 'daysUntilExpiry' => 2, 'location' => 'Fridge A'],
                ['id' => 'B009', 'quantity' => 5, 'expiryDate' => '2024-12-09', 'daysUntilExpiry' => 5, 'location' => 'Fridge B']
            ]
        ],
        [
            'id' => '6', 'name' => 'Yeast', 'category' => 'Dry Goods', 'icon' => 'chef-hat', 'currentStock' => 2.5, 'unit' => 'kg',
            'maxStock' => 5, 'minStock' => 1, 'status' => 'good', 'temperature' => null, 'lastUsed' => '5 hours ago', 'avgDailyUsage' => 0.8,
            'batches' => [
                ['id' => 'B010', 'quantity' => 2.5, 'expiryDate' => '2025-03-15', 'daysUntilExpiry' => 101, 'location' => 'Shelf C1']
            ]
        ],
        [
            'id' => '7', 'name' => 'Cream Cheese', 'category' => 'Refrigerated', 'icon' => 'beef', 'currentStock' => 6, 'unit' => 'kg',
            'maxStock' => 15, 'minStock' => 5, 'status' => 'warning', 'temperature' => 2, 'tempStatus' => 'good', 'lastUsed' => '2 hours ago', 'avgDailyUsage' => 2.1,
            'batches' => [
                ['id' => 'B011', 'quantity' => 6, 'expiryDate' => '2024-12-10', 'daysUntilExpiry' => 6, 'location' => 'Fridge C']
            ]
        ],
        [
            'id' => '8', 'name' => 'Vanilla Extract', 'category' => 'Dry Goods', 'icon' => 'archive', 'currentStock' => 1.2, 'unit' => 'L',
            'maxStock' => 3, 'minStock' => 0.5, 'status' => 'good', 'temperature' => null, 'lastUsed' => '6 hours ago', 'avgDailyUsage' => 0.15,
            'batches' => [
                ['id' => 'B012', 'quantity' => 1.2, 'expiryDate' => '2026-01-30', 'daysUntilExpiry' => 422, 'location' => 'Shelf D1']
            ]
        ]
    ];

    // 2. Mock Recipes
    $activeRecipes = [
        [
            'id' => 'R1', 'name' => 'Chocolate Cake', 'icon' => '🎂', 'batchSize' => 10,
            'ingredients' => [
                ['name' => 'All-Purpose Flour', 'needed' => 5, 'unit' => 'kg'],
                ['name' => 'Sugar', 'needed' => 3, 'unit' => 'kg'],
                ['name' => 'Fresh Eggs', 'needed' => 30, 'unit' => 'pcs'],
                ['name' => 'Butter', 'needed' => 2, 'unit' => 'kg']
            ]
        ],
        [
            'id' => 'R2', 'name' => 'Bread Loaves', 'icon' => '🍞', 'batchSize' => 20,
            'ingredients' => [
                ['name' => 'All-Purpose Flour', 'needed' => 8, 'unit' => 'kg'],
                ['name' => 'Yeast', 'needed' => 0.4, 'unit' => 'kg'],
                ['name' => 'Milk', 'needed' => 2, 'unit' => 'L']
            ]
        ]
    ];

    // 3. Stats Calculations
    $criticalCount = count(array_filter($kitchenItems, fn($i) => $i['status'] === 'critical'));
    $tempMonitoredCount = count(array_filter($kitchenItems, fn($i) => $i['temperature'] !== null));
    
    $expiringItems = [];
    foreach($kitchenItems as $item) {
        foreach($item['batches'] as $batch) {
            if ($batch['daysUntilExpiry'] <= 7) {
                $itemCopy = $item;
                $itemCopy['batch'] = $batch; 
                $expiringItems[] = $itemCopy;
            }
        }
    }
    usort($expiringItems, fn($a, $b) => $a['batch']['daysUntilExpiry'] <=> $b['batch']['daysUntilExpiry']);
    $expiringCount = count($expiringItems);

    // 4. Helpers
    function getStatusClass($status) {
        return match($status) {
            'critical' => 'bg-red-500',
            'warning' => 'bg-orange-500',
            'good' => 'bg-green-500',
            default => 'bg-gray-500',
        };
    }

    function getIconSVG($name, $class = "w-6 h-6") {
        // Simple SVG mapping for demo purposes
        $icons = [
            'leaf' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.77 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>',
            'egg' => '<path d="M12 22c6.23-.05 7.87-5.57 7.5-10-.36-4.34-3.95-9.67-7.5-10-3.55.33-7.14 5.66-7.5 10-.37 4.43 1.27 9.95 7.5 10z"/>',
            'droplet' => '<path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>',
            'archive' => '<rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/>',
            'chef-hat' => '<path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" x2="18" y1="17" y2="17"/>',
            'beef' => '<circle cx="12.5" cy="8.5" r="2.5"/><path d="M12.5 2a6.5 6.5 0 0 0-6.22 4.6c-1.1 3.13-.78 6.64 3.18 9.77L4.5 21.39a1 1 0 0 0 1.3 1.4L10.8 17.8a6 6 0 0 0 3.15.2h0c2.45-.3 4.35-2.15 4.88-4.57a6.5 6.5 0 0 0-6.33-11.43Z"/>',
        ];
        $path = $icons[$name] ?? $icons['leaf'];
        return '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'.$path.'</svg>';
    }
@endphp

<div class="min-h-screen bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 p-4 md:p-6" x-data="{ activeView: 'stock' }">
    
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                    {{-- Icon: ChefHat --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" x2="18" y1="17" y2="17"/></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Kitchen Inventory</h1>
                    <p class="text-gray-600">Quick stock management</p>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="flex gap-3 overflow-x-auto pb-2 md:pb-0">
                <div class="bg-white rounded-xl p-4 shadow-sm min-w-[120px]">
                    <div class="flex items-center gap-2 mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                        <span class="text-2xl font-bold text-gray-900">{{ $criticalCount }}</span>
                    </div>
                    <div class="text-sm text-gray-600">Critical</div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm min-w-[120px]">
                    <div class="flex items-center gap-2 mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span class="text-2xl font-bold text-gray-900">{{ $expiringCount }}</span>
                    </div>
                    <div class="text-sm text-gray-600">Expiring</div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm min-w-[120px]">
                    <div class="flex items-center gap-2 mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z"/></svg>
                        <span class="text-2xl font-bold text-gray-900">{{ $tempMonitoredCount }}</span>
                    </div>
                    <div class="text-sm text-gray-600">Monitored</div>
                </div>
            </div>
        </div>

        {{-- Search Bar with Scanner & Keyboard --}}
        <div class="space-y-3 relative">
            <div class="flex gap-3">
                <div class="flex-1 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" id="searchInput" onfocus="showKeyboard(true)" oninput="filterItems()" placeholder="Search ingredients..." 
                        class="w-full h-16 pl-14 pr-12 text-lg rounded-xl border-2 border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all">
                    <button onclick="clearSearch()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                <button onclick="openModal('scannerModal')" class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl flex items-center justify-center shadow-lg transition-all active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                </button>
            </div>

            {{-- On-Screen Keyboard --}}
            <div id="virtualKeyboard" class="hidden absolute top-full left-0 right-0 z-50 mt-2 bg-white rounded-xl p-4 shadow-lg border-2 border-gray-200">
                <div class="flex justify-end mb-2">
                    <button onclick="showKeyboard(false)" class="w-8 h-8 bg-red-500 hover:bg-red-600 rounded-full flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach([
                        ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'],
                        ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'],
                        ['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'],
                        ['Z', 'X', 'C', 'V', 'B', 'N', 'M', 'DEL', 'SPC']
                    ] as $row)
                    <div class="flex gap-2 justify-center">
                        @foreach($row as $key)
                            <button onclick="keyPress('{{ $key }}')" class="min-w-[48px] h-14 bg-gray-100 hover:bg-gray-200 active:bg-gray-300 rounded-lg font-medium text-lg transition-colors shadow-sm">
                                {{ $key === 'SPC' ? '___' : $key }}
                            </button>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- View Tabs --}}
        <div class="flex gap-2 mt-4 overflow-x-auto pb-2 md:pb-0">
            <button onclick="switchView('stock')" id="btn-stock" class="tab-btn flex-1 min-w-[140px] h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                <span class="font-medium">Stock Levels</span>
            </button>
            <button onclick="switchView('expiry')" id="btn-expiry" class="tab-btn flex-1 min-w-[140px] h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-white text-gray-700 hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span class="font-medium">Expiring Soon</span>
                @if($expiringCount > 0)
                    <span class="bg-red-500 text-white rounded-full px-2 py-0.5 text-xs">{{ $expiringCount }}</span>
                @endif
            </button>
            <button onclick="switchView('recipes')" id="btn-recipes" class="tab-btn flex-1 min-w-[140px] h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-white text-gray-700 hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" x2="18" y1="17" y2="17"/></svg>
                <span class="font-medium">Active Recipes</span>
            </button>
            <button onclick="switchView('temp')" id="btn-temp" class="tab-btn flex-1 min-w-[140px] h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-white text-gray-700 hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z"/></svg>
                <span class="font-medium">Temperature</span>
            </button>
        </div>
    </div>

    {{-- Content Views --}}
    
    {{-- 1. Stock Levels View --}}
    <div id="view-stock" class="view-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($kitchenItems as $item)
            @php
                $stockPercentage = ($item['currentStock'] / $item['maxStock']) * 100;
                $daysRemaining = floor($item['currentStock'] / $item['avgDailyUsage']);
                $hasExpiryWarning = false;
                foreach($item['batches'] as $b) { if($b['daysUntilExpiry'] <= 7) $hasExpiryWarning = true; }
            @endphp
            <div onclick="openItemModal({{ json_encode($item) }})" class="inventory-item bg-white rounded-2xl p-5 shadow-sm hover:shadow-xl transition-all cursor-pointer border-2 border-gray-100 hover:border-amber-300" data-name="{{ strtolower($item['name']) }}" data-cat="{{ strtolower($item['category']) }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br {{ $item['category'] == 'Refrigerated' ? 'from-blue-400 to-blue-500' : 'from-amber-400 to-orange-500' }} flex items-center justify-center shadow-md text-white">
                            {!! getIconSVG($item['icon'], "w-7 h-7") !!}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 leading-tight">{{ $item['name'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $item['category'] }}</p>
                        </div>
                    </div>
                    <div class="w-3 h-3 rounded-full {{ getStatusClass($item['status']) }} shadow-lg"></div>
                </div>

                <div class="mb-4">
                    <div class="flex items-baseline gap-2 mb-1">
                        <span class="text-4xl text-gray-900">{{ $item['currentStock'] }}</span>
                        <span class="text-xl text-gray-500">/ {{ $item['maxStock'] }}</span>
                        <span class="text-lg text-gray-400">{{ $item['unit'] }}</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all {{ $stockPercentage > 50 ? 'bg-green-500' : ($stockPercentage > 25 ? 'bg-orange-500' : 'bg-red-500') }}" style="width: {{ min($stockPercentage, 100) }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="bg-gray-50 rounded-lg p-2">
                        <div class="text-xs text-gray-500 mb-1">Days Left</div>
                        <div class="font-medium {{ $daysRemaining < 2 ? 'text-red-600' : ($daysRemaining < 5 ? 'text-orange-600' : 'text-green-600') }}">~{{ $daysRemaining }}d</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2">
                        <div class="text-xs text-gray-500 mb-1">Batches</div>
                        <div class="font-medium text-gray-900">{{ count($item['batches']) }}</div>
                    </div>
                </div>

                @if($item['temperature'] !== null)
                    <div class="flex items-center gap-2 p-2 bg-blue-50 rounded-lg mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z"/></svg>
                        <span class="text-sm font-medium text-blue-900">{{ $item['temperature'] }}°C</span>
                        <span class="ml-auto bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded">Normal</span>
                    </div>
                @endif

                @if($hasExpiryWarning)
                    <div class="flex items-center gap-2 p-2 bg-red-50 rounded-lg mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                        <span class="text-sm font-medium text-red-900">Check Expiry</span>
                    </div>
                @endif

                <div class="grid grid-cols-3 gap-2">
                    <button onclick="event.stopPropagation(); openAdjustmentModal({{ json_encode($item) }}, 'usage')" class="h-10 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg flex items-center justify-center gap-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                        <span class="text-xs font-medium">Use</span>
                    </button>
                    <button onclick="event.stopPropagation(); openAdjustmentModal({{ json_encode($item) }}, 'waste')" class="h-10 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg flex items-center justify-center gap-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        <span class="text-xs font-medium">Waste</span>
                    </button>
                    <button onclick="event.stopPropagation()" class="h-10 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg flex items-center justify-center gap-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                        <span class="text-xs font-medium">Order</span>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 2. Expiry View --}}
    <div id="view-expiry" class="view-section hidden space-y-3">
        @if(count($expiringItems) === 0)
            <div class="bg-white rounded-2xl p-12 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <h3 class="text-xl text-gray-900 mb-2">All Clear!</h3>
                <p class="text-gray-600">No items expiring in the next 7 days</p>
            </div>
        @else
            @foreach($expiringItems as $idx => $item)
                @php $batch = $item['batch']; @endphp
                <div class="bg-white rounded-2xl p-5 shadow-sm border-2 {{ $batch['daysUntilExpiry'] <= 2 ? 'border-red-300 bg-red-50' : 'border-orange-300 bg-orange-50' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold shadow-md {{ $batch['daysUntilExpiry'] <= 2 ? 'bg-red-500 text-white' : 'bg-orange-500 text-white' }}">
                            {{ $idx + 1 }}
                        </div>
                        <div class="w-14 h-14 rounded-xl bg-white flex items-center justify-center shadow-sm text-gray-700">
                            {!! getIconSVG($item['icon'], "w-7 h-7") !!}
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl text-gray-900 mb-1">{{ $item['name'] }}</h3>
                            <div class="flex items-center gap-3">
                                <span class="bg-white text-gray-700 px-2 py-0.5 rounded text-sm border">Batch {{ $batch['id'] }}</span>
                                <span class="text-sm text-gray-600">{{ $batch['quantity'] }} {{ $item['unit'] }}</span>
                                <span class="text-sm text-gray-500">{{ $batch['location'] }}</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="text-5xl font-bold mb-1 {{ $batch['daysUntilExpiry'] <= 2 ? 'text-red-600' : 'text-orange-600' }}">{{ $batch['daysUntilExpiry'] }}</div>
                            <div class="text-sm text-gray-600">days left</div>
                            <div class="text-xs text-gray-500 mt-1">{{ date('Y-m-d', strtotime($batch['expiryDate'])) }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- 3. Recipes View --}}
    <div id="view-recipes" class="view-section hidden space-y-4">
        @foreach($activeRecipes as $recipe)
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <div class="flex items-center gap-4 mb-6">
                    <div class="text-6xl">{{ $recipe['icon'] }}</div>
                    <div class="flex-1">
                        <h3 class="text-2xl text-gray-900 mb-1">{{ $recipe['name'] }}</h3>
                        <p class="text-gray-600">Batch Size: {{ $recipe['batchSize'] }} units</p>
                    </div>
                    <button class="h-14 px-6 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-medium shadow-lg transition-all">Start Production</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach($recipe['ingredients'] as $ing)
                        @php
                            $stockItem = null;
                            foreach($kitchenItems as $kItem) { if($kItem['name'] === $ing['name']) $stockItem = $kItem; }
                            $hasEnough = $stockItem && $stockItem['currentStock'] >= $ing['needed'];
                        @endphp
                        <div class="p-4 rounded-xl border-2 {{ $hasEnough ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                            <div class="flex items-center gap-2 mb-2">
                                @if($hasEnough)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                                @endif
                                <span class="font-medium text-gray-900">{{ $ing['name'] }}</span>
                            </div>
                            <div class="text-sm text-gray-700">Need: <span class="font-medium">{{ $ing['needed'] }} {{ $ing['unit'] }}</span></div>
                            @if($stockItem)
                                <div class="text-sm {{ $hasEnough ? 'text-green-700' : 'text-red-700' }}">Available: <span class="font-medium">{{ $stockItem['currentStock'] }} {{ $stockItem['unit'] }}</span></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- 4. Temperature View --}}
    <div id="view-temp" class="view-section hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($kitchenItems as $item)
            @if($item['temperature'] !== null)
                <div class="bg-white rounded-2xl p-5 shadow-sm border-2 border-blue-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center shadow-md text-white">
                            {!! getIconSVG($item['icon'], "w-7 h-7") !!}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item['name'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $item['batches'][0]['location'] ?? 'Fridge' }}</p>
                        </div>
                    </div>
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-blue-600 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4v10.54a4 4 0 1 1-4 0V4a2 2 0 0 1 4 0Z"/></svg>
                        <div class="text-6xl font-bold text-blue-600 mb-2">{{ $item['temperature'] }}°C</div>
                        <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">Normal Range</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-xs text-gray-500 mb-1">Stock</div>
                            <div class="font-medium text-gray-900">{{ $item['currentStock'] }} {{ $item['unit'] }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-xs text-gray-500 mb-1">Batches</div>
                            <div class="font-medium text-gray-900">{{ count($item['batches']) }}</div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- MODALS --}}

    {{-- Scanner Modal --}}
    <div id="scannerModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('scannerModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Scan Barcode / QR Code</h3>
            <p class="text-sm text-gray-500 mb-6">Scan a barcode or QR code to quickly look up ingredients</p>
            <div class="aspect-video bg-gray-900 rounded-xl flex items-center justify-center mb-6">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-white mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                    <p class="text-white text-lg">Camera view would appear here</p>
                    <p class="text-gray-400 text-sm mt-2">Position barcode within frame</p>
                </div>
            </div>
            <div class="text-center text-sm text-gray-500 mb-2">Or enter code manually:</div>
            <input type="text" placeholder="Enter barcode number..." class="w-full h-14 px-4 text-lg rounded-xl border-2 border-gray-200 focus:border-amber-500 outline-none">
            <button onclick="closeModal('scannerModal')" class="mt-4 w-full h-12 bg-gray-100 rounded-lg font-medium">Close</button>
        </div>
    </div>

    {{-- Item Details Modal --}}
    <div id="itemDetailsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('itemDetailsModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto p-6 m-4">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 text-amber-600" id="modal-icon-container"></div>
                <h3 class="text-2xl font-bold text-gray-900" id="modal-item-name"></h3>
            </div>
            <p class="text-sm text-gray-500 mb-6">View stock details and batches</p>

            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                    <div class="text-sm text-blue-700 mb-1">Current Stock</div>
                    <div class="text-3xl text-blue-900" id="modal-current-stock"></div>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4">
                    <div class="text-sm text-orange-700 mb-1">Daily Usage</div>
                    <div class="text-3xl text-orange-900" id="modal-daily-usage"></div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4">
                    <div class="text-sm text-green-700 mb-1">Days Remaining</div>
                    <div class="text-3xl text-green-900" id="modal-days-remaining"></div>
                </div>
            </div>

            <h3 class="text-lg text-gray-900 mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                Available Batches (FEFO Order)
            </h3>
            <div id="modal-batches-list" class="space-y-3">
                </div>
            
            <div class="grid grid-cols-3 gap-3 mt-6">
                <button onclick="triggerAdjustment('usage')" class="h-14 bg-blue-500 hover:bg-blue-600 text-white rounded-xl flex items-center justify-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg> Record Usage
                </button>
                <button onclick="triggerAdjustment('waste')" class="h-14 bg-red-500 hover:bg-red-600 text-white rounded-xl flex items-center justify-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg> Log Waste
                </button>
                <button class="h-14 bg-green-500 hover:bg-green-600 text-white rounded-xl flex items-center justify-center gap-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg> Request Reorder
                </button>
            </div>
        </div>
    </div>

    {{-- Adjustment Modal --}}
    <div id="adjustmentModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal('adjustmentModal')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6 m-4">
            <h3 class="text-2xl font-bold text-gray-900 mb-1" id="adj-title">Record Usage</h3>
            <p class="text-sm text-gray-500 mb-6" id="adj-desc">Record the amount of stock used.</p>

            <div class="bg-gray-50 rounded-xl p-4 mb-6 flex items-center gap-3">
                <div id="adj-icon-container" class="w-10 h-10 text-amber-600"></div>
                <div>
                    <h3 class="font-medium text-gray-900" id="adj-item-name"></h3>
                    <p class="text-sm text-gray-600" id="adj-available"></p>
                </div>
            </div>

            <div id="adj-batch-section" class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Batch (FEFO)</label>
                <div id="adj-batch-list" class="space-y-2"></div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                <input type="number" id="adj-amount" class="w-full h-16 px-4 text-2xl rounded-xl border-2 border-gray-200 focus:border-amber-500 outline-none" placeholder="0.0">
            </div>

            <div id="adj-waste-section" class="hidden mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Waste Reason</label>
                <div class="grid grid-cols-2 gap-2">
                    <button class="waste-reason p-3 rounded-xl border-2 border-gray-200 hover:bg-gray-50">Expired</button>
                    <button class="waste-reason p-3 rounded-xl border-2 border-gray-200 hover:bg-gray-50">Spoiled</button>
                    <button class="waste-reason p-3 rounded-xl border-2 border-gray-200 hover:bg-gray-50">Damaged</button>
                    <button class="waste-reason p-3 rounded-xl border-2 border-gray-200 hover:bg-gray-50">Other</button>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button onclick="closeModal('adjustmentModal')" class="flex-1 h-14 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium">Cancel</button>
                <button onclick="closeModal('adjustmentModal')" class="flex-1 h-14 bg-gradient-to-br from-amber-500 to-orange-600 text-white rounded-xl font-medium">Confirm</button>
            </div>
        </div>
    </div>

</div>

<script>
    let currentItem = null;

    // View Switching
    function switchView(view) {
        document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));
        document.getElementById('view-' + view).classList.remove('hidden');
        
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-gradient-to-br', 'from-amber-500', 'to-orange-600', 'text-white', 'shadow-lg');
            btn.classList.add('bg-white', 'text-gray-700', 'hover:bg-gray-50');
        });
        const activeBtn = document.getElementById('btn-' + view);
        activeBtn.classList.remove('bg-white', 'text-gray-700', 'hover:bg-gray-50');
        activeBtn.classList.add('bg-gradient-to-br', 'from-amber-500', 'to-orange-600', 'text-white', 'shadow-lg');
    }

    // Modal Handling
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    // Item Details
    function openItemModal(item) {
        currentItem = item;
        document.getElementById('modal-item-name').textContent = item.name;
        document.getElementById('modal-current-stock').textContent = `${item.currentStock} ${item.unit}`;
        document.getElementById('modal-daily-usage').textContent = `${item.avgDailyUsage} ${item.unit}`;
        document.getElementById('modal-days-remaining').textContent = `~${Math.floor(item.currentStock / item.avgDailyUsage)}d`;
        
        // Simple Icon Clone
        const iconContainer = document.getElementById('modal-icon-container');
        iconContainer.innerHTML = getIconSVGJS(item.icon);

        // Batches
        const list = document.getElementById('modal-batches-list');
        list.innerHTML = '';
        const sortedBatches = item.batches.sort((a, b) => a.daysUntilExpiry - b.daysUntilExpiry);
        
        sortedBatches.forEach((b, idx) => {
            const isFirst = idx === 0;
            const html = `
                <div class="p-4 rounded-xl border-2 ${isFirst ? 'border-blue-300 bg-blue-50' : 'border-gray-200 bg-gray-50'}">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            ${isFirst ? '<span class="bg-blue-500 text-white px-2 py-0.5 rounded text-xs">Use First</span>' : ''}
                            <span class="font-medium text-gray-900">Batch ${b.id}</span>
                            <span class="text-gray-600 text-sm">${b.location}</span>
                        </div>
                        <div class="text-xl font-medium text-gray-900">${b.quantity} ${item.unit}</div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 text-sm">
                        <div><div class="text-gray-500 text-xs">Expiry</div><div class="font-medium">${b.expiryDate}</div></div>
                        <div><div class="text-gray-500 text-xs">Days Left</div><div class="font-medium ${b.daysUntilExpiry <= 7 ? 'text-red-600' : 'text-green-600'}">${b.daysUntilExpiry} days</div></div>
                        <div class="flex justify-end"><button class="px-3 py-1 bg-blue-500 text-white rounded text-xs" onclick="selectBatchForAdj('${b.id}')">Use This</button></div>
                    </div>
                </div>`;
            list.innerHTML += html;
        });

        openModal('itemDetailsModal');
    }

    // Adjustment Logic
    function openAdjustmentModal(item, type) {
        if(item) currentItem = item; // Allows opening directly from card
        
        document.getElementById('adj-title').textContent = type === 'usage' ? 'Record Usage' : (type === 'waste' ? 'Log Waste' : 'Transfer Stock');
        document.getElementById('adj-item-name').textContent = currentItem.name;
        document.getElementById('adj-available').textContent = `Available: ${currentItem.currentStock} ${currentItem.unit}`;
        document.getElementById('adj-icon-container').innerHTML = getIconSVGJS(currentItem.icon);
        
        // Populate Batches for selection
        const batchList = document.getElementById('adj-batch-list');
        batchList.innerHTML = '';
        currentItem.batches.sort((a, b) => a.daysUntilExpiry - b.daysUntilExpiry).forEach((b, idx) => {
            const cls = idx === 0 ? 'border-blue-300 bg-blue-50' : 'border-gray-200 bg-white';
            batchList.innerHTML += `
                <button class="w-full p-3 rounded-xl border-2 text-left ${cls} hover:bg-gray-100" onclick="document.getElementById('adj-amount').value = ${b.quantity}">
                    <div class="flex justify-between">
                        <div><span class="font-medium">Batch ${b.id}</span> <span class="text-sm text-gray-600">(${b.location})</span></div>
                        <div>${b.quantity} ${currentItem.unit}</div>
                    </div>
                </button>
            `;
        });

        // Toggle Waste Section
        if(type === 'waste') {
            document.getElementById('adj-waste-section').classList.remove('hidden');
        } else {
            document.getElementById('adj-waste-section').classList.add('hidden');
        }

        openModal('adjustmentModal');
    }

    function triggerAdjustment(type) {
        closeModal('itemDetailsModal');
        openAdjustmentModal(currentItem, type);
    }

    // Helper to get SVG string in JS (Simplified matching PHP logic)
    function getIconSVGJS(name) {
        const svgs = {
            'leaf': '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.77 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>',
            'egg': '<path d="M12 22c6.23-.05 7.87-5.57 7.5-10-.36-4.34-3.95-9.67-7.5-10-3.55.33-7.14 5.66-7.5 10-.37 4.43 1.27 9.95 7.5 10z"/>',
            'droplet': '<path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/>',
            'archive': '<rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/>',
            'chef-hat': '<path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" x2="18" y1="17" y2="17"/>',
            'beef': '<circle cx="12.5" cy="8.5" r="2.5"/><path d="M12.5 2a6.5 6.5 0 0 0-6.22 4.6c-1.1 3.13-.78 6.64 3.18 9.77L4.5 21.39a1 1 0 0 0 1.3 1.4L10.8 17.8a6 6 0 0 0 3.15.2h0c2.45-.3 4.35-2.15 4.88-4.57a6.5 6.5 0 0 0-6.33-11.43Z"/>',
        };
        const path = svgs[name] || svgs['leaf'];
        return `<svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${path}</svg>`;
    }

    // Keyboard & Search
    function showKeyboard(show) {
        const kb = document.getElementById('virtualKeyboard');
        if(show) kb.classList.remove('hidden');
        else kb.classList.add('hidden');
    }

    function keyPress(key) {
        const input = document.getElementById('searchInput');
        if(key === 'DEL') input.value = input.value.slice(0, -1);
        else if(key === 'SPC') input.value += ' ';
        else input.value += key;
        filterItems();
    }

    function clearSearch() {
        const input = document.getElementById('searchInput');
        input.value = '';
        filterItems();
    }

    function filterItems() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('.inventory-item').forEach(el => {
            const name = el.getAttribute('data-name');
            const cat = el.getAttribute('data-cat');
            if(name.includes(query) || cat.includes(query)) el.classList.remove('hidden');
            else el.classList.add('hidden');
        });
    }
</script>

@endsection