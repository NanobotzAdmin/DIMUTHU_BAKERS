@extends('layouts.app')
@section('title', 'Sales Overview')

@section('content')

{{-- 
    -------------------------------------------------------------------------
    MOCK DATA 
    -------------------------------------------------------------------------
--}}
@php
    // --- Overall Stats ---
    $stats = [
        'todayTotal' => 125000,
        'totalGrowth' => 12.5,
        'weekTotal' => 850000,
        'monthTotal' => 3200000,
        'todayTransactions' => 45,
        'averageOrderValue' => 2800,
        'activeVans' => 3,
        'activeSalesPeople' => 8
    ];

    // --- Channels ---
    $channels = [
        ['id' => 'pos', 'name' => 'In-Store POS', 'type' => 'pos', 'todaySales' => 45000, 'growth' => 5.2, 'transactionCount' => 20, 'averageOrderValue' => 2250, 'isActive' => true, 'weekSales' => 300000, 'monthSales' => 1200000],
        ['id' => 'online', 'name' => 'Online Store', 'type' => 'online', 'todaySales' => 25000, 'growth' => 15.8, 'transactionCount' => 10, 'averageOrderValue' => 2500, 'isActive' => true, 'weekSales' => 150000, 'monthSales' => 600000],
        ['id' => 'wholesale', 'name' => 'Wholesale', 'type' => 'wholesale', 'todaySales' => 35000, 'growth' => -2.1, 'transactionCount' => 5, 'averageOrderValue' => 7000, 'isActive' => true, 'weekSales' => 250000, 'monthSales' => 900000],
        ['id' => 'van', 'name' => 'Van Sales', 'type' => 'van', 'todaySales' => 20000, 'growth' => 8.4, 'transactionCount' => 10, 'averageOrderValue' => 2000, 'isActive' => true, 'weekSales' => 150000, 'monthSales' => 500000],
    ];

    // --- Top Products ---
    $topProducts = [
        ['productId' => 'P001', 'productName' => 'Chocolate Cake', 'category' => 'Cakes', 'revenue' => 150000, 'unitsSold' => 120, 'trend' => 'up', 'growth' => 12.5],
        ['productId' => 'P002', 'productName' => 'Butter Bun', 'category' => 'Buns', 'revenue' => 80000, 'unitsSold' => 800, 'trend' => 'stable', 'growth' => 1.2],
        ['productId' => 'P003', 'productName' => 'Chicken Roll', 'category' => 'Savory', 'revenue' => 65000, 'unitsSold' => 650, 'trend' => 'up', 'growth' => 5.8],
        ['productId' => 'P004', 'productName' => 'White Bread', 'category' => 'Bread', 'revenue' => 50000, 'unitsSold' => 500, 'trend' => 'down', 'growth' => -3.2],
        ['productId' => 'P005', 'productName' => 'Fish Bun', 'category' => 'Buns', 'revenue' => 45000, 'unitsSold' => 450, 'trend' => 'up', 'growth' => 8.1],
        ['productId' => 'P006', 'productName' => 'Sponge Cake', 'category' => 'Cakes', 'revenue' => 40000, 'unitsSold' => 40, 'trend' => 'stable', 'growth' => 0.5],
    ];

    // --- Customer Segments ---
    $customerSegments = [
        ['segment' => 'Walk-in', 'percentage' => 45, 'totalRevenue' => 1440000, 'customerCount' => 1200, 'growth' => 5.5, 'averageOrderValue' => 1200, 'transactionCount' => 1200],
        ['segment' => 'Loyalty Members', 'percentage' => 30, 'totalRevenue' => 960000, 'customerCount' => 450, 'growth' => 12.2, 'averageOrderValue' => 2133, 'transactionCount' => 450],
        ['segment' => 'Corporate', 'percentage' => 15, 'totalRevenue' => 480000, 'customerCount' => 25, 'growth' => 2.1, 'averageOrderValue' => 19200, 'transactionCount' => 25],
        ['segment' => 'Online', 'percentage' => 10, 'totalRevenue' => 320000, 'customerCount' => 150, 'growth' => 18.5, 'averageOrderValue' => 2133, 'transactionCount' => 150],
    ];

    // --- Geographic Sales ---
    $geographicSales = [
        ['district' => 'Colombo', 'revenue' => 1500000, 'percentage' => 46.8, 'customerCount' => 850, 'transactionCount' => 900, 'topProducts' => ['Chocolate Cake', 'Butter Bun']],
        ['district' => 'Gampaha', 'revenue' => 900000, 'percentage' => 28.1, 'customerCount' => 420, 'transactionCount' => 450, 'topProducts' => ['White Bread', 'Fish Bun']],
        ['district' => 'Kalutara', 'revenue' => 500000, 'percentage' => 15.6, 'customerCount' => 200, 'transactionCount' => 220, 'topProducts' => ['Chicken Roll', 'Sponge Cake']],
    ];

    // --- Sales People ---
    $salesPeople = [
        ['id' => 'SP001', 'name' => 'Nimal Perera', 'role' => 'Sales Executive', 'monthSales' => 450000, 'todaySales' => 15000, 'weekSales' => 110000, 'targetMonth' => 500000, 'achievement' => 90, 'isActive' => true, 'rating' => 4.8, 'ordersCompleted' => 150, 'averageOrderValue' => 3000],
        ['id' => 'SP002', 'name' => 'Kamal Silva', 'role' => 'Van Driver', 'monthSales' => 320000, 'todaySales' => 12000, 'weekSales' => 80000, 'targetMonth' => 300000, 'achievement' => 106.6, 'isActive' => true, 'rating' => 4.5, 'ordersCompleted' => 120, 'averageOrderValue' => 2666],
    ];

    // --- Vans ---
    $vans = [
        ['vanId' => 'V001', 'vanNumber' => 'WP-CAB-1234', 'driverName' => 'Kamal Silva', 'route' => 'Route A', 'status' => 'active', 'district' => 'Colombo', 'todaySales' => 12000, 'weekSales' => 84000, 'monthSales' => 320000, 'ordersToday' => 15, 'ordersWeek' => 90, 'ordersMonth' => 350, 'kilometersToday' => 45, 'lastLocation' => 'Nugegoda'],
        ['vanId' => 'V002', 'vanNumber' => 'WP-CAB-5678', 'driverName' => 'Sunil Perera', 'route' => 'Route B', 'status' => 'active', 'district' => 'Gampaha', 'todaySales' => 8000, 'weekSales' => 56000, 'monthSales' => 210000, 'ordersToday' => 10, 'ordersWeek' => 60, 'ordersMonth' => 240, 'kilometersToday' => 62, 'lastLocation' => 'Wattala'],
    ];

    // --- Targets ---
    $targets = [
        ['id' => 'T001', 'type' => 'daily', 'target' => 150000, 'achieved' => 125000, 'percentage' => 83.3],
        ['id' => 'T002', 'type' => 'weekly', 'target' => 1000000, 'achieved' => 850000, 'percentage' => 85],
        ['id' => 'T003', 'type' => 'monthly', 'target' => 4000000, 'achieved' => 3200000, 'percentage' => 80],
    ];

    // --- Revenue Trends (Mocking last 10 days for chart) ---
    $revenueTrends = [
        ['date' => 'Dec 01', 'revenue' => 110000], ['date' => 'Dec 02', 'revenue' => 135000], ['date' => 'Dec 03', 'revenue' => 120000],
        ['date' => 'Dec 04', 'revenue' => 140000], ['date' => 'Dec 05', 'revenue' => 160000], ['date' => 'Dec 06', 'revenue' => 180000],
        ['date' => 'Dec 07', 'revenue' => 190000], ['date' => 'Dec 08', 'revenue' => 130000], ['date' => 'Dec 09', 'revenue' => 125000],
        ['date' => 'Dec 10', 'revenue' => 145000]
    ];
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 font-sans">
    
    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Sales Overview</h1>
                <p class="text-gray-600">Real-time sales performance & distribution metrics</p>
            </div>
        </div>

        <div class="flex gap-3">
            <button class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Today
            </button>
            <button class="h-12 px-5 bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Export Report
            </button>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-blue-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 text-sm">Today's Sales</span>
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="text-2xl font-bold text-blue-600">Rs {{ number_format($stats['todayTotal']) }}</div>
            <div class="flex items-center gap-1 mt-1 text-xs font-bold text-green-600">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                +{{ $stats['totalGrowth'] }}%
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-purple-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 text-sm">Week Sales</span>
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
            </div>
            <div class="text-2xl font-bold text-purple-600">Rs {{ number_format($stats['weekTotal']) }}</div>
            <div class="text-xs text-gray-500 mt-1">Last 7 days</div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-indigo-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 text-sm">Month Sales</span>
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
            </div>
            <div class="text-2xl font-bold text-indigo-600">Rs {{ number_format($stats['monthTotal']) }}</div>
            <div class="text-xs text-gray-500 mt-1">December</div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-cyan-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 text-sm">Transactions</span>
                <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
            <div class="text-2xl font-bold text-cyan-600">{{ $stats['todayTransactions'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Today</div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-orange-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 text-sm">Avg Order</span>
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
            </div>
            <div class="text-2xl font-bold text-orange-600">Rs {{ number_format($stats['averageOrderValue']) }}</div>
            <div class="text-xs text-gray-500 mt-1">Per transaction</div>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-green-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-600 text-sm">Active Vans</span>
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
            </div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['activeVans'] }}</div>
            <div class="text-xs text-gray-500 mt-1">On route</div>
        </div>
    </div>

    {{-- View Tabs --}}
    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide mb-6">
        @php
            $tabs = [
                ['id' => 'overview', 'label' => 'Overview', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['id' => 'channels', 'label' => 'Sales Channels', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                ['id' => 'products', 'label' => 'Top Products', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['id' => 'customers', 'label' => 'Customers', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['id' => 'team', 'label' => 'Sales Team', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['id' => 'vans', 'label' => 'Van Sales', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                ['id' => 'geography', 'label' => 'Geography', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                ['id' => 'trends', 'label' => 'Revenue Trends', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6']
            ];
        @endphp
        @foreach($tabs as $tab)
            <button onclick="switchTab('{{ $tab['id'] }}')" id="tab-{{ $tab['id'] }}" 
                    class="tab-btn flex-shrink-0 h-12 px-6 rounded-xl flex items-center gap-2 transition-all font-medium border-2 {{ $tab['id'] === 'overview' ? 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg border-transparent' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}" /></svg>
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    {{-- VIEW: OVERVIEW --}}
    <div id="view-overview" class="view-content">
        <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Sales by Channel</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($channels as $channel)
                    <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-4 border-2 border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $channel['growth'] >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $channel['growth'] >= 0 ? '+' : '' }}{{ $channel['growth'] }}%
                            </span>
                        </div>
                        <h3 class="font-medium text-gray-900 mb-1">{{ $channel['name'] }}</h3>
                        <div class="text-2xl font-bold text-blue-600 mb-2">Rs {{ number_format($channel['todaySales']) }}</div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>{{ $channel['transactionCount'] }} orders</span>
                            <span>Rs {{ number_format($channel['averageOrderValue']) }}/avg</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Top Selling Products</h2>
                <div class="space-y-3">
                    @foreach(array_slice($topProducts, 0, 5) as $idx => $product)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0 text-white font-bold text-sm">
                                {{ $idx + 1 }}
                            </div>
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">{{ $product['productName'] }}</div>
                                <div class="text-sm text-gray-600">{{ $product['unitsSold'] }} sold</div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-blue-600">Rs {{ number_format($product['revenue']) }}</div>
                                <div class="text-xs {{ $product['growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $product['growth'] >= 0 ? '+' : '' }}{{ $product['growth'] }}%
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Sales by Customer Segment</h2>
                <div class="space-y-3">
                    @foreach(array_slice($customerSegments, 0, 5) as $segment)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-medium text-gray-900 capitalize">{{ $segment['segment'] }}</span>
                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-bold">
                                        {{ number_format($segment['percentage'], 1) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all" 
                                         style="width: {{ $segment['percentage'] }}%">
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-blue-600">
                                    Rs {{ number_format($segment['totalRevenue'] / 1000000, 1) }}M
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $segment['customerCount'] }} customers
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Sales Targets</h2>
            <div class="grid grid-cols-3 gap-4">
                @foreach($targets as $target)
                    <div class="bg-gradient-to-br from-gray-50 to-indigo-50 rounded-xl p-4 border-2 border-gray-100">
                        <div class="flex justify-between mb-2">
                            <span class="text-xs font-bold bg-gray-200 text-gray-700 px-2 py-0.5 rounded capitalize">{{ $target['type'] }}</span>
                            <span class="text-xs font-bold {{ $target['percentage'] >= 100 ? 'text-green-600' : 'text-orange-600' }}">{{ $target['percentage'] }}%</span>
                        </div>
                        <div class="text-sm text-gray-600">Target: Rs {{ number_format($target['target']) }}</div>
                        <div class="text-lg font-bold text-gray-900 mb-2">Rs {{ number_format($target['achieved']) }}</div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $target['percentage'] >= 100 ? 'bg-green-500' : 'bg-orange-500' }}" style="width: {{ min($target['percentage'], 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- VIEW: CHANNELS --}}
    <div id="view-channels" class="view-content hidden space-y-4">
        @foreach($channels as $channel)
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <div class="flex justify-between mb-4">
                    <div class="flex gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-2xl">🏪</div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $channel['name'] }}</h3>
                            <p class="text-gray-600 capitalize">{{ $channel['type'] }} Channel</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Today's Sales</div>
                        <div class="text-3xl font-bold text-blue-600">Rs {{ number_format($channel['todaySales']) }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 bg-gray-50 rounded-xl p-4">
                    <div><div class="text-sm text-gray-500">Week Sales</div><div class="font-bold text-gray-900">Rs {{ number_format($channel['weekSales']) }}</div></div>
                    <div><div class="text-sm text-gray-500">Month Sales</div><div class="font-bold text-gray-900">Rs {{ number_format($channel['monthSales']) }}</div></div>
                    <div><div class="text-sm text-gray-500">Avg Order</div><div class="font-bold text-gray-900">Rs {{ number_format($channel['averageOrderValue']) }}</div></div>
                    <div><div class="text-sm text-gray-500">Growth</div><div class="font-bold {{ $channel['growth']>=0 ? 'text-green-600' : 'text-red-600' }}">{{ $channel['growth'] }}%</div></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- VIEW: PRODUCTS --}}
    <div id="view-products" class="view-content hidden">
        <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Product Performance Ranking</h2>
            <div class="space-y-3">
                @foreach($topProducts as $idx => $product)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 text-white font-bold text-lg">#{{ $idx + 1 }}</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $product['productName'] }}</h3>
                            <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded text-xs font-bold">{{ $product['category'] }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Revenue</div>
                            <div class="text-2xl font-bold text-blue-600">Rs {{ number_format($product['revenue']) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- VIEW: CUSTOMERS --}}
    <div id="view-customers" class="view-content hidden space-y-4">
        @foreach($customerSegments as $segment)
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <div class="flex justify-between items-start">
                    <div class="flex gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center text-white text-2xl">👥</div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $segment['segment'] }}</h3>
                            <p class="text-gray-600">{{ $segment['customerCount'] }} active customers</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Total Revenue</div>
                        <div class="text-3xl font-bold text-purple-600">Rs {{ number_format($segment['totalRevenue']) }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4 bg-purple-50 p-4 rounded-xl">
                    <div><div class="text-sm text-gray-600">Avg Order Value</div><div class="font-bold text-gray-900">Rs {{ number_format($segment['averageOrderValue']) }}</div></div>
                    <div><div class="text-sm text-gray-600">Transactions</div><div class="font-bold text-gray-900">{{ $segment['transactionCount'] }}</div></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- VIEW: TEAM --}}
    <div id="view-team" class="view-content hidden space-y-4">
        @foreach($salesPeople as $person)
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <div class="flex justify-between items-start">
                    <div class="flex gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center text-white text-2xl">🏆</div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $person['name'] }}</h3>
                            <p class="text-gray-600">{{ $person['role'] }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold">Active</span>
                                <span class="flex items-center gap-1 text-sm font-bold text-gray-700">⭐ {{ $person['rating'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Month Sales</div>
                        <div class="text-3xl font-bold text-amber-600">Rs {{ number_format($person['monthSales']) }}</div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-xs mb-1">
                        <span>Target: Rs {{ number_format($person['targetMonth']) }}</span>
                        <span class="font-bold">{{ $person['achievement'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-amber-500 h-3 rounded-full" style="width: {{ min($person['achievement'], 100) }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- VIEW: VANS --}}
    <div id="view-vans" class="view-content hidden space-y-4">
        @foreach($vans as $van)
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <div class="flex justify-between items-start">
                    <div class="flex gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center text-white text-2xl">🚚</div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $van['vanNumber'] }}</h3>
                            <p class="text-gray-600">{{ $van['driverName'] }} • {{ $van['route'] }}</p>
                            <div class="mt-2 flex gap-2">
                                <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold capitalize">{{ $van['status'] }}</span>
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-bold">{{ $van['district'] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Today's Sales</div>
                        <div class="text-3xl font-bold text-green-600">Rs {{ number_format($van['todaySales']) }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 bg-gray-50 p-4 rounded-xl mt-4">
                    <div><div class="text-sm text-gray-500">Week Sales</div><div class="font-bold">Rs {{ number_format($van['weekSales']) }}</div></div>
                    <div><div class="text-sm text-gray-500">Month Sales</div><div class="font-bold">Rs {{ number_format($van['monthSales']) }}</div></div>
                    <div><div class="text-sm text-gray-500">Orders Today</div><div class="font-bold">{{ $van['ordersToday'] }}</div></div>
                    <div><div class="text-sm text-gray-500">Distance</div><div class="font-bold">{{ $van['kilometersToday'] }} km</div></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- VIEW: GEOGRAPHY --}}
    <div id="view-geography" class="view-content hidden space-y-4">
        @foreach($geographicSales as $geo)
            <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
                <div class="flex justify-between items-start">
                    <div class="flex gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center text-white text-2xl">📍</div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $geo['district'] }}</h3>
                            <p class="text-gray-600">{{ $geo['customerCount'] }} customers • {{ $geo['transactionCount'] }} transactions</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Revenue</div>
                        <div class="text-3xl font-bold text-green-600">Rs {{ number_format($geo['revenue']) }}</div>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($geo['topProducts'] as $prod)
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">{{ $prod }}</span>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- VIEW: TRENDS (Simple Bar Chart Visualization) --}}
    <div id="view-trends" class="view-content hidden">
        <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Revenue Trend (Last 10 Days)</h2>
            <div class="h-64 flex items-end justify-between gap-2">
                @php $maxRev = max(array_column($revenueTrends, 'revenue')); @endphp
                @foreach($revenueTrends as $trend)
                    @php $height = ($trend['revenue'] / $maxRev) * 100; @endphp
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="relative w-full bg-blue-100 rounded-t-lg hover:bg-blue-200 transition-all cursor-pointer" style="height: {{ $height }}%">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                Rs {{ number_format($trend['revenue']) }}
                            </div>
                            <div class="absolute bottom-0 w-full bg-blue-600 rounded-t-lg opacity-80" style="height: 100%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-2 rotate-0">{{ $trend['date'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<script>
    function switchTab(tabId) {
        // Hide all views
        document.querySelectorAll('.view-content').forEach(el => el.classList.add('hidden'));
        // Show target view
        document.getElementById('view-' + tabId).classList.remove('hidden');

        // Update Buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-gradient-to-br', 'from-blue-500', 'to-indigo-600', 'text-white', 'shadow-lg', 'border-transparent');
            btn.classList.add('bg-white', 'text-gray-700', 'border-gray-200');
        });

        // Activate clicked button
        const activeBtn = document.getElementById('tab-' + tabId);
        activeBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-200');
        activeBtn.classList.add('bg-gradient-to-br', 'from-blue-500', 'to-indigo-600', 'text-white', 'shadow-lg', 'border-transparent');
    }
</script>
@endsection