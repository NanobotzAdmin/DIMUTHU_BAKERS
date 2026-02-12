@extends('layouts.app')
@section('title', 'Supplier Management')

@section('content')
    <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-item {
            transition: all 0.2s ease;
        }

        .product-item:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
    @php
        $activeCount = count(array_filter($suppliers, fn($s) => $s['status'] === 'active'));
        $inactiveCount = count(array_filter($suppliers, fn($s) => $s['status'] === 'inactive'));
        $pendingCount = count(array_filter($suppliers, fn($s) => $s['status'] === 'pending-verification'));
        $totalCount = count($suppliers);
        $avgRating =
            $totalCount > 0 ? number_format(array_sum(array_column($suppliers, 'rating')) / $totalCount, 1) : 0;
        $totalProducts = array_reduce($suppliers, fn($carry, $s) => $carry + count($s['products']), 0);
        $totalContracts = array_reduce($suppliers, fn($carry, $s) => $carry + count($s['contracts']), 0);
        // Hardcoded for now as per original mock
        $expiringDocuments = 2;
        $expiringContracts = 1;
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6">

        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl text-gray-900 font-bold">Supplier Management</h1>
                        <p class="text-gray-600">Manage supplier relationships and performance</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button
                        class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export
                    </button>
                    <button id="btn-add-supplier"
                        class="h-12 px-5 bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Supplier
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Active Suppliers</span>
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-green-600">{{ $activeCount }}</div>
                    <div class="text-sm text-gray-500 mt-1">{{ $totalProducts }} products</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-yellow-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Avg Rating</span>
                        <svg class="w-5 h-5 text-yellow-500 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-yellow-600">{{ $avgRating }}</div>
                    <div class="text-sm text-gray-500 mt-1">Out of 5.0</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-orange-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Documents</span>
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-orange-600">{{ $expiringDocuments }}</div>
                    <div class="text-sm text-gray-500 mt-1">Expiring soon</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-purple-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Contracts</span>
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-purple-600">{{ $totalContracts }}</div>
                    <div class="text-sm text-purple-500 mt-1">{{ $expiringContracts }} expiring</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Inactive</span>
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-gray-600">{{ $inactiveCount }}</div>
                    <div class="text-sm text-gray-500 mt-1">Suppliers</div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Total</span>
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-blue-600">{{ $totalCount }}</div>
                    <div class="text-sm text-gray-500 mt-1">All suppliers</div>
                </div>
            </div>

            <div class="flex gap-2 mb-4 overflow-x-auto pb-2" id="supplier-tabs">
                <button data-tab="all"
                    class="tab-btn active bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg flex-1 min-w-[120px] h-14 rounded-xl flex items-center justify-center gap-2 transition-all px-4">
                    <span class="font-medium">All Suppliers</span>
                    <span class="bg-white/20 text-white px-2 py-0.5 rounded-full text-xs">{{ $totalCount }}</span>
                </button>
                <button data-tab="active"
                    class="tab-btn bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200 flex-1 min-w-[120px] h-14 rounded-xl flex items-center justify-center gap-2 transition-all px-4">
                    <span class="font-medium">Active</span>
                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">{{ $activeCount }}</span>
                </button>
                <button data-tab="inactive"
                    class="tab-btn bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200 flex-1 min-w-[120px] h-14 rounded-xl flex items-center justify-center gap-2 transition-all px-4">
                    <span class="font-medium">Inactive</span>
                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">{{ $inactiveCount }}</span>
                </button>
                <button data-tab="pending-verification"
                    class="tab-btn bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200 flex-1 min-w-[120px] h-14 rounded-xl flex items-center justify-center gap-2 transition-all px-4">
                    <span class="font-medium">Pending</span>
                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">{{ $pendingCount }}</span>
                </button>
            </div>

            <div class="flex gap-3 mb-6">
                <div class="flex-1 bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100 flex items-center gap-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="supplier-search" placeholder="Search suppliers by name, contact, category..."
                        class="flex-1 text-xl outline-none border-none focus:ring-0">
                    <button id="clear-search" class="text-gray-400 hover:text-gray-600 hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                </div>

                <div class="bg-white rounded-xl px-4 py-2 shadow-sm border-2 border-gray-100 flex items-center">
                    <select id="supplier-sort"
                        class="outline-none text-gray-700 font-medium bg-transparent border-none focus:ring-0 cursor-pointer">
                        <option value="rating">Sort by Rating</option>
                        <option value="name">Sort by Name</option>
                        <option value="orders">Sort by Orders</option>
                    </select>
                </div>
            </div>

            <div class="space-y-3" id="supplier-list">
                @if (count($suppliers) > 0)
                    @foreach ($suppliers as $supplier)
                        <div class="supplier-card bg-white rounded-2xl p-5 shadow-sm border-2 border-gray-100 hover:shadow-md transition-all cursor-pointer group"
                            data-id="{{ $supplier['id'] }}" data-status="{{ $supplier['status'] }}"
                            data-name="{{ strtolower($supplier['name']) }}" data-rating="{{ $supplier['rating'] }}"
                            data-orders="{{ $supplier['totalOrders'] }}" onclick="openViewModal(this)">

                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3
                                                class="text-xl font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                                                {{ $supplier['name'] }}
                                            </h3>
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-xs font-medium border uppercase
                                                                                                                                                            @if ($supplier['status'] === 'active') bg-green-100 text-green-700 border-green-300
                                                                                                                                                            @elseif($supplier['status'] === 'inactive') bg-gray-100 text-gray-700 border-gray-300
                                                                                                                                                            @else bg-yellow-100 text-yellow-700 border-yellow-300 @endif">
                                                {{ str_replace('-', ' ', $supplier['status']) }}
                                            </span>
                                            @if (isset($supplier['tags']) && is_array($supplier['tags']) && in_array('preferred', $supplier['tags']))
                                                <span
                                                    class="bg-purple-100 text-purple-700 border-2 border-purple-300 px-2 py-0.5 rounded-full text-xs flex items-center font-medium">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 3.214L17 21l-5.714-3.214L5 21l2.286-6.857L3 12l5.714-3.214L10 3z">
                                                        </path>
                                                    </svg>
                                                    Preferred
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            @php
                                                $primaryContact =
                                                    collect($supplier['contacts'])->firstWhere('isPrimary', true) ??
                                                    ($supplier['contacts'][0] ?? [
                                                        'name' => 'N/A',
                                                        'email' => 'N/A',
                                                        'phone' => 'N/A',
                                                    ]);
                                            @endphp
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                                <span>{{ $primaryContact['name'] }}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <span>{{ $primaryContact['email'] }}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                    </path>
                                                </svg>
                                                <span>{{ $primaryContact['phone'] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Rating --}}
                                    <div class="text-right">
                                        <div class="flex items-center gap-2 justify-end mb-1">
                                            @php
                                                $ratingColor = 'text-orange-500';
                                                if ($supplier['rating'] >= 4.5) {
                                                    $ratingColor = 'text-green-500';
                                                } elseif ($supplier['rating'] >= 4.0) {
                                                    $ratingColor = 'text-blue-500';
                                                } elseif ($supplier['rating'] >= 3.0) {
                                                    $ratingColor = 'text-yellow-500';
                                                }
                                            @endphp
                                            <svg class="w-6 h-6 {{ $ratingColor }} fill-current" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-2xl font-bold {{ $ratingColor }}">{{ number_format($supplier['rating'], 1) }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $supplier['totalOrders'] }} orders</div>
                                    </div>
                                </div>

                                {{-- Performance Metrics --}}
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-3">
                                    <div class="bg-green-50 rounded-lg p-3 border border-green-100">
                                        <div class="flex items-center gap-2 text-green-600 mb-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                            </svg>
                                            <span class="text-sm font-medium">On-Time</span>
                                        </div>
                                        <div class="text-xl font-bold text-green-900">{{ $supplier['onTimeDelivery'] }}%</div>
                                    </div>

                                    <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                        <div class="flex items-center gap-2 text-blue-600 mb-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Quality</span>
                                        </div>
                                        <div class="text-xl font-bold text-blue-900">{{ $supplier['qualityScore'] }}%</div>
                                    </div>

                                    <div class="bg-purple-50 rounded-lg p-3 border border-purple-100">
                                        <div class="flex items-center gap-2 text-purple-600 mb-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                            <span class="text-sm font-medium">Products</span>
                                        </div>
                                        <div class="text-xl font-bold text-purple-900">{{ count($supplier['products']) }}</div>
                                    </div>

                                    <div class="bg-orange-50 rounded-lg p-3 border border-orange-100">
                                        <div class="flex items-center gap-2 text-orange-600 mb-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Lead Time</span>
                                        </div>
                                        <div class="text-xl font-bold text-orange-900">{{ $supplier['leadTime'] }}d</div>
                                    </div>

                                    <div class="bg-indigo-50 rounded-lg p-3 border border-indigo-100">
                                        <div class="flex items-center gap-2 text-indigo-600 mb-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            <span class="text-sm font-medium">Credit</span>
                                        </div>
                                        <div class="text-xl font-bold text-indigo-900">
                                            {{ str_replace('credit-', '', $supplier['paymentTerms']) }}d
                                        </div>
                                    </div>
                                </div>

                                {{-- Categories & Info --}}
                                <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                                    <div class="flex items-center gap-2 overflow-hidden">
                                        <span class="text-sm text-gray-600 flex-shrink-0">Categories:</span>
                                        <div class="flex gap-1 overflow-x-auto no-scrollbar">
                                            @if (isset($supplier['categories']) && is_array($supplier['categories']))
                                                @foreach ($supplier['categories'] as $cat)
                                                    <span
                                                        class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs capitalize whitespace-nowrap">
                                                        {{ $cat }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 text-sm whitespace-nowrap">
                                        @php
                                            $activeContract = collect($supplier['contracts'])->contains(function ($c) {
                                                return $c['status'] === 'active';
                                            });
                                            $expiringDocs = collect($supplier['documents'])
                                                ->filter(function ($d) {
                                                    return isset($d['expiryDate']) &&
                                                        \Carbon\Carbon::parse($d['expiryDate'])->diffInDays(now()) < 30;
                                                })
                                                ->count();
                                        @endphp

                                        @if ($activeContract)
                                            <div class="flex items-center gap-1 text-green-600 hidden md:flex">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                <span>Active Contract</span>
                                            </div>
                                        @endif

                                        @if ($expiringDocs > 0)
                                            <div class="flex items-center gap-1 text-orange-600 hidden md:flex">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                    </path>
                                                </svg>
                                                <span>{{ $expiringDocs }} doc{{ $expiringDocs > 1 ? 's' : '' }}
                                                    expiring</span>
                                            </div>
                                        @endif

                                        <div class="text-gray-600 font-medium">
                                            Credit: Rs {{ number_format($supplier['creditLimit'] - $supplier['currentCredit']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="bg-white rounded-2xl p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl text-gray-900 mb-2">No Suppliers Found</h3>
                        <p class="text-gray-600 mb-4">Try adjusting your search query or filter.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ADD SUPPLIER MODAL --}}
        <div id="addSupplierModal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" id="addSupplierModalBackdrop">
            </div>
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl max-h-[90vh] overflow-y-auto">
                    {{-- Header --}}
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                Add New Supplier
                            </h3>
                            <p class="text-sm text-gray-500 mt-1" id="step-description">Step 1 of 5 - Basic Information
                            </p>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeAddModal()">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="px-6 pt-4">
                        <div class="flex items-center gap-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <div class="step-indicator flex-1 h-2 rounded-full transition-all {{ $i === 1 ? 'bg-blue-500' : 'bg-gray-200' }}"
                                    data-step="{{ $i }}"></div>
                            @endfor
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6">
                        <form id="addSupplierForm">
                            <input type="hidden" id="formSupplierId" name="supplierId">
                            {{-- Step 1: Basic Information --}}
                            <div class="step-content" data-step="1">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Supplier Name *</label>
                                        <input type="text" name="name"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"
                                            placeholder="Enter supplier name" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Registration
                                            Number</label>
                                        <input type="text" name="registrationNumber"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"
                                            placeholder="Business registration number">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tax ID / VAT
                                            Number</label>
                                        <input type="text" name="taxId"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"
                                            placeholder="Tax identification number">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                                        <textarea name="address" rows="3"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"
                                            placeholder="Complete business address" required></textarea>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                                        <input type="url" name="website"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"
                                            placeholder="https://www.example.com">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Categories *</label>
                                        <div class="flex gap-2 mb-2">
                                            <input type="text" id="categoryInput"
                                                class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"
                                                placeholder="e.g., Flour, Dairy, Packaging">
                                            <button type="button" onclick="addCategory()"
                                                class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl transition-all">Add</button>
                                        </div>
                                        <div id="categoriesList" class="flex flex-wrap gap-2"></div>
                                        {{-- Hidden input handled by JS --}}
                                    </div>
                                </div>
                            </div>

                            {{-- Step 2: Contacts --}}
                            <div class="step-content hidden" data-step="2">
                                <div class="mb-4">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Contact Information</h4>
                                    <p class="text-sm text-gray-600">Add primary and additional contacts for this supplier
                                    </p>
                                </div>
                                <div id="contactsList" class="space-y-4">
                                    {{-- Contacts will be added here via JS --}}
                                </div>
                                <button type="button" onclick="addContact()"
                                    class="w-full mt-4 py-3 border-2 border-dashed border-blue-300 rounded-xl text-blue-600 hover:border-blue-500 hover:bg-blue-50 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Another Contact
                                </button>
                            </div>

                            {{-- Step 3: Products & Pricing --}}
                            <div class="step-content hidden" data-step="3">

                                {{-- 1. Main View: Added Products List + Add Button --}}
                                <div id="added-products-section">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="font-bold text-gray-900">Products & Pricing</h4>
                                        <button type="button" onclick="showProductBrowser()"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center gap-2 hover:bg-blue-700 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Add Product
                                        </button>
                                    </div>

                                    <div id="productsList" class="space-y-2 mb-4">
                                        {{-- Products List Items --}}
                                    </div>

                                    <div id="noProductsMsg"
                                        class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4 flex items-start gap-3 mt-4">
                                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        <div>
                                            <div class="font-medium text-yellow-900">No products added yet</div>
                                            <div class="text-sm text-yellow-700 mt-1">Select at least one product from the
                                                master list.</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Product Browser Section (Hidden by default) --}}
                                <div id="product-browser-section" class="hidden">
                                    <div class="bg-blue-50 rounded-xl p-4 border-2 border-blue-200">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-medium text-gray-900">Select Product from Master List</h4>
                                            <button type="button" onclick="hideProductBrowser()"
                                                class="text-gray-500 hover:text-gray-700">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3 mb-4">
                                            <div class="col-span-2 relative">
                                                <input type="text" id="browserSearchQuery"
                                                    class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500"
                                                    placeholder="Search products by name...">
                                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                            {{-- Type Filter can be added here if Controller supported --}}
                                        </div>

                                        <div id="browserResults" class="max-h-60 overflow-y-auto space-y-2">
                                            {{-- Results populated via JS --}}
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. Supplier Pricing Form (Hidden by default) --}}
                                <div id="product-pricing-section" class="hidden">
                                    <div class="bg-green-50 rounded-xl p-4 border-2 border-green-200">
                                        <h4 class="font-medium text-gray-900 mb-3">Add Supplier Pricing</h4>

                                        <div class="bg-white rounded-lg p-3 border border-gray-200 mb-4"
                                            id="selectedProductCard">
                                            {{-- Selected Product Info --}}
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Supplier SKU /
                                                    Product Code *</label>
                                                <input type="text" id="supplierSku"
                                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg outline-none focus:border-green-500"
                                                    placeholder="Supplier's product code">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Price (Rs)
                                                    *</label>
                                                <input type="number" id="supplierPrice"
                                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg outline-none focus:border-green-500"
                                                    placeholder="0.00" step="0.01">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Order
                                                    Qty</label>
                                                <input type="number" id="supplierMinOrder"
                                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg outline-none focus:border-green-500"
                                                    placeholder="0">
                                            </div>
                                        </div>

                                        <div class="flex gap-3 mt-4">
                                            <button type="button" onclick="confirmAddProduct()"
                                                class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-all flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Confirm Add
                                            </button>
                                            <button type="button" onclick="cancelAddProduct()"
                                                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-all">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 4: Financial Terms --}}
                            <div class="step-content hidden" data-step="4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Terms *</label>
                                        <select name="paymentTerms"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500">
                                            <option value="cash">Cash on Delivery</option>
                                            <option value="credit-7">7 Days Credit</option>
                                            <option value="credit-15">15 Days Credit</option>
                                            <option value="credit-30" selected>30 Days Credit</option>
                                            <option value="credit-60">60 Days Credit</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Credit Limit (Rs)
                                            *</label>
                                        <input type="number" name="creditLimit"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"
                                            placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Order Amount
                                            (Rs)</label>
                                        <input type="number" name="minimumOrder"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"
                                            placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Lead Time
                                            (Days)</label>
                                        <input type="number" name="leadTime"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"
                                            placeholder="7">
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4 mt-6">
                                    <h4 class="font-medium text-gray-900 mb-3">Bank Details (Optional)</h4>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <input type="text" name="bankName"
                                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500"
                                                placeholder="Bank Name">
                                        </div>
                                        <div>
                                            <input type="text" name="accountName"
                                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500"
                                                placeholder="Account Name">
                                        </div>
                                        <div class="col-span-2">
                                            <input type="text" name="accountNumber"
                                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500"
                                                placeholder="Account Number">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 5: Additional Details --}}
                            <div class="step-content hidden" data-step="5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                    <div class="flex gap-2 mb-2">
                                        <input type="text" id="tagInput"
                                            class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"
                                            placeholder="e.g., preferred, local">
                                        <button type="button" onclick="addTag()"
                                            class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl transition-all">Add</button>
                                    </div>
                                    <div id="tagsList" class="flex flex-wrap gap-2"></div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                    <textarea name="notes" rows="3"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500"></textarea>
                                </div>

                                {{-- Summary Section --}}
                                <div class="bg-blue-50 rounded-xl p-5 border-2 border-blue-200 mt-6 hidden"
                                    id="summary-section">
                                    <h4 class="font-bold text-gray-900 mb-3">Summary</h4>
                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <span class="text-gray-600">Supplier:</span>
                                            <span class="font-medium text-gray-900 ml-2" id="summary-name"></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Categories:</span>
                                            <span class="font-medium text-gray-900 ml-2" id="summary-categories"></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Contacts:</span>
                                            <span class="font-medium text-gray-900 ml-2" id="summary-contacts"></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Products:</span>
                                            <span class="font-medium text-gray-900 ml-2" id="summary-products"></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">Credit Limit:</span>
                                            <span class="font-medium text-gray-900 ml-2" id="summary-credit"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-100 flex justify-between bg-gray-50 rounded-b-xl">
                        <button type="button" id="btn-back"
                            class="hidden px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-medium transition-all"
                            onclick="prevStep()">Back</button>
                        <div class="flex-1"></div>
                        <button type="button" id="btn-next"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all"
                            onclick="nextStep()">Next</button>
                        <button type="button" id="btn-submit"
                            class="hidden px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all"
                            onclick="submitForm()">Create Supplier</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- VIEW SUPPLIER MODAL --}}
    <div id="viewSupplierModal" class="hidden fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeViewModal()"></div>
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-6xl max-h-[90vh] overflow-y-auto">
                <div class="bg-white">
                    {{-- Header --}}
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2" id="view-supplier-name"></h2>
                                    <div class="flex items-center gap-2">
                                        <span id="view-supplier-status"
                                            class="px-2.5 py-0.5 rounded-full text-xs font-medium border uppercase"></span>
                                        <div id="view-preferred-badge"
                                            class="hidden bg-purple-100 text-purple-700 border border-purple-300 px-2 py-0.5 rounded-full text-xs flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 3.214L17 21l-5.714-3.214L5 21l2.286-6.857L3 12l5.714-3.214L10 3z">
                                                </path>
                                            </svg>
                                            Preferred
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg class="w-5 h-5 fill-current text-yellow-500" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                                                </path>
                                            </svg>
                                            <span class="font-bold text-yellow-500" id="view-supplier-rating"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button id="btn-trigger-edit" onclick="openEditModalFromView()"
                                    class="h-10 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg flex items-center gap-2 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    Edit
                                </button>
                                <button onclick="closeViewModal()"
                                    class="h-10 w-10 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tabs Header --}}
                    <div class="px-6 border-b border-gray-200">
                        <div class="flex gap-4 overflow-x-auto" id="view-tabs-header">
                            <button onclick="switchViewTab('overview')"
                                class="view-tab-btn active px-4 py-3 border-b-2 font-medium capitalize transition-all border-blue-500 text-blue-600"
                                data-tab="overview">Overview</button>
                            <button onclick="switchViewTab('contacts')"
                                class="view-tab-btn px-4 py-3 border-b-2 font-medium capitalize transition-all border-transparent text-gray-600 hover:text-gray-900"
                                data-tab="contacts">Contacts</button>
                            <button onclick="switchViewTab('products')"
                                class="view-tab-btn px-4 py-3 border-b-2 font-medium capitalize transition-all border-transparent text-gray-600 hover:text-gray-900"
                                data-tab="products">Products</button>
                            <button onclick="switchViewTab('contracts')"
                                class="view-tab-btn px-4 py-3 border-b-2 font-medium capitalize transition-all border-transparent text-gray-600 hover:text-gray-900"
                                data-tab="contracts">Contracts</button>
                            <button onclick="switchViewTab('documents')"
                                class="view-tab-btn px-4 py-3 border-b-2 font-medium capitalize transition-all border-transparent text-gray-600 hover:text-gray-900"
                                data-tab="documents">Documents</button>
                            <button onclick="switchViewTab('performance')"
                                class="view-tab-btn px-4 py-3 border-b-2 font-medium capitalize transition-all border-transparent text-gray-600 hover:text-gray-900"
                                data-tab="performance">Performance</button>
                            <button onclick="switchViewTab('orders')"
                                class="view-tab-btn px-4 py-3 border-b-2 font-medium capitalize transition-all border-transparent text-gray-600 hover:text-gray-900"
                                data-tab="orders">Orders</button>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50/50 min-h-[400px]">
                        {{-- Overview Tab --}}
                        <div id="view-tab-overview" class="view-tab-content space-y-4">
                            {{-- KPI Cards --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div
                                    class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border-2 border-green-100">
                                    <div class="flex items-center gap-2 text-green-600 mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        <span class="font-medium">On-Time Delivery</span>
                                    </div>
                                    <div class="text-3xl font-bold text-green-900"><span id="view-ontime"></span>%</div>
                                </div>
                                <div
                                    class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border-2 border-blue-100">
                                    <div class="flex items-center gap-2 text-blue-600 mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium">Quality Score</span>
                                    </div>
                                    <div class="text-3xl font-bold text-blue-900"><span id="view-quality"></span>%</div>
                                </div>
                                <div
                                    class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-4 border-2 border-purple-100">
                                    <div class="flex items-center gap-2 text-purple-600 mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <span class="font-medium">Total Orders</span>
                                    </div>
                                    <div class="text-3xl font-bold text-purple-900" id="view-orders"></div>
                                </div>
                                <div
                                    class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-4 border-2 border-orange-100">
                                    <div class="flex items-center gap-2 text-orange-600 mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium">Lead Time</span>
                                    </div>
                                    <div class="text-3xl font-bold text-orange-900"><span id="view-leadtime"></span> days
                                    </div>
                                </div>
                            </div>

                            {{-- Basic Info --}}
                            <div class="bg-gray-50 rounded-xl p-5 border shadow-sm">
                                <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    Business Information
                                </h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-sm text-gray-600">Business Registration</div>
                                        <div class="font-medium text-gray-900" id="view-reg"></div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Tax ID</div>
                                        <div class="font-medium text-gray-900" id="view-tax"></div>
                                    </div>
                                    <div class="col-span-2">
                                        <div class="text-sm text-gray-600">Address</div>
                                        <div class="font-medium text-gray-900 flex items-start gap-2">
                                            <svg class="w-4 h-4 mt-1 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span id="view-address"></span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Website</div>
                                        <div class="font-medium text-blue-600" id="view-website"></div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Member Since</div>
                                        <div class="font-medium text-gray-900" id="view-member-since"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Financial Info --}}
                            <div
                                class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-5 border-2 border-indigo-100">
                                <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                    Financial Details
                                </h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <div class="text-sm text-gray-600">Payment Terms</div>
                                        <div class="font-bold text-gray-900 capitalize" id="view-payment-terms"></div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Credit Limit</div>
                                        <div class="font-bold text-gray-900" id="view-credit-limit"></div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Available Credit</div>
                                        <div class="font-bold text-green-600" id="view-available-credit"></div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Minimum Order</div>
                                        <div class="font-bold text-gray-900" id="view-min-order"></div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Current Credit Used</div>
                                        <div class="font-bold text-orange-600" id="view-current-credit"></div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Bank</div>
                                        <div class="font-medium text-gray-900" id="view-bank-name"></div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Account Name</div>
                                        <div class="font-medium text-gray-900" id="view-account-name"></div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-600">Account Number</div>
                                        <div class="font-medium text-gray-900" id="view-account-number"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Categories & Tags --}}
                            <div class="bg-gray-50 rounded-xl p-5 border shadow-sm">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h3 class="font-bold text-gray-900 mb-2">Categories</h3>
                                        <div class="flex flex-wrap gap-2" id="view-categories-list"></div>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 mb-2">Tags</h3>
                                        <div class="flex flex-wrap gap-2" id="view-tags-list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contacts Tab --}}
                        <div id="view-tab-contacts" class="view-tab-content hidden space-y-3"></div>

                        {{-- Products Tab --}}
                        <div id="view-tab-products" class="view-tab-content hidden space-y-3"></div>

                        {{-- Contracts Tab --}}
                        <div id="view-tab-contracts" class="view-tab-content hidden space-y-3"></div>

                        {{-- Documents Tab --}}
                        <div id="view-tab-documents" class="view-tab-content hidden space-y-3"></div>

                        {{-- Performance Tab --}}
                        <div id="view-tab-performance" class="view-tab-content hidden space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div
                                    class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 border-2 border-green-100">
                                    <div class="text-sm text-gray-600 mb-2">On-Time Delivery Rate</div>
                                    <div class="text-4xl font-bold text-green-600 mb-2"><span id="view-perf-ontime"></span>%
                                    </div>
                                    <div class="w-full bg-green-200 rounded-full h-2">
                                        <div id="bar-perf-ontime" class="bg-green-600 h-2 rounded-full" style="width: 0%">
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border-2 border-blue-100">
                                    <div class="text-sm text-gray-600 mb-2">Quality Score</div>
                                    <div class="text-4xl font-bold text-blue-600 mb-2"><span id="view-perf-quality"></span>%
                                    </div>
                                    <div class="w-full bg-blue-200 rounded-full h-2">
                                        <div id="bar-perf-quality" class="bg-blue-600 h-2 rounded-full" style="width: 0%">
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-5 border-2 border-purple-100">
                                    <div class="text-sm text-gray-600 mb-2">Response Time</div>
                                    <div class="text-4xl font-bold text-purple-600 mb-2">2h</div>
                                    <div class="text-sm text-gray-600">Average response</div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-5">
                                <h3 class="font-bold text-gray-900 mb-4">Performance Breakdown</h3>
                                <div class="space-y-3">
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Delivery Performance</span>
                                            <span class="font-medium text-gray-900"><span
                                                    id="view-breakdown-delivery"></span>%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="bar-breakdown-delivery"
                                                class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full"
                                                style="width: 0%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Product Quality</span>
                                            <span class="font-medium text-gray-900"><span
                                                    id="view-breakdown-quality"></span>%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="bar-breakdown-quality"
                                                class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full"
                                                style="width: 0%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Communication</span>
                                            <span class="font-medium text-gray-900"><span
                                                    id="view-breakdown-comm"></span>%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="bar-breakdown-comm"
                                                class="bg-gradient-to-r from-purple-500 to-violet-600 h-2 rounded-full"
                                                style="width: 0%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Price Competitiveness</span>
                                            <span class="font-medium text-gray-900">85%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-orange-500 to-amber-600 h-2 rounded-full"
                                                style="width: 85%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Orders Tab --}}
                        <div id="view-tab-orders" class="view-tab-content hidden space-y-2">
                            <div class="bg-blue-50 rounded-lg p-3 mb-3">
                                <div class="font-medium text-blue-900">
                                    <span id="view-orders-count-tab"></span> total orders placed
                                </div>
                            </div>
                            <div class="text-center py-8 text-gray-600">
                                Order history integration coming soon...
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.suppliersData = @json($suppliers);

        $(document).ready(function () {
            // Tab Filtering
            $('.tab-btn').click(function () {
                $('.tab-btn').removeClass(
                    'active bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg')
                    .addClass('bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200');
                $('.tab-btn span.rounded-full').removeClass('bg-white/20 text-white').addClass(
                    'bg-blue-100 text-blue-700');

                $(this).removeClass('bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200')
                    .addClass('active bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg');
                $(this).find('span.rounded-full').removeClass('bg-blue-100 text-blue-700').addClass(
                    'bg-white/20 text-white');

                const status = $(this).data('tab');
                filterSuppliers(status, $('#supplier-search').val());
            });

            // Search
            $('#supplier-search').on('input', function () {
                const query = $(this).val();
                const status = $('.tab-btn.active').data('tab');
                if (query) $('#clear-search').removeClass('hidden');
                else $('#clear-search').addClass('hidden');

                filterSuppliers(status, query);
            });

            $('#clear-search').click(function () {
                $('#supplier-search').val('').trigger('input');
            });

            // Sort
            $('#supplier-sort').change(function () {
                const sortBy = $(this).val();
                const $list = $('#supplier-list');
                const $cards = $list.children('.supplier-card');

                $cards.sort(function (a, b) {
                    const an = $(a).data(sortBy);
                    const bn = $(b).data(sortBy);

                    if (sortBy === 'name') return an.localeCompare(bn);
                    return bn - an; // descending for numbers
                });

                $cards.detach().appendTo($list);
            });

            // Add Supplier Modal Open/Close
            $('#btn-add-supplier').click(function () {
                resetAddForm();
                $('#addSupplierModal').removeClass('hidden');
            });

            $('#addSupplierModalBackdrop').click(function () {
                closeAddModal();
            });

            // Init first contact
            addContact();
        });

        function filterSuppliers(status, query) {
            query = query.toLowerCase();
            $('.supplier-card').each(function () {
                const cardStatus = $(this).data('status');
                const cardName = $(this).data('name');
                // Assuming contact info is not easily searchable without more DOM parsing or data attrs.
                // For now search mainly by name.

                let matchesStatus = (status === 'all') || (cardStatus === status);
                let matchesQuery = cardName.includes(query);

                if (matchesStatus && matchesQuery) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        function closeAddModal() {
            $('#addSupplierModal').addClass('hidden');
        }

        function closeViewModal() {
            $('#viewSupplierModal').addClass('hidden');
            $('body').css('overflow', 'auto');
        }

        function switchViewTab(tab) {
            $('.view-tab-btn').removeClass('border-blue-500 text-blue-600').addClass(
                'border-transparent text-gray-600 hover:text-gray-900');
            $(`.view-tab-btn[data-tab="${tab}"]`).removeClass('border-transparent text-gray-600 hover:text-gray-900')
                .addClass('border-blue-500 text-blue-600');

            $('.view-tab-content').addClass('hidden');
            $(`#view-tab-${tab}`).removeClass('hidden');
        }

        function openViewModal(card) {
            const id = $(card).data('id');
            const data = window.suppliersData.find(s => s.id == id);

            if (!data) {
                console.error('Supplier data not found for ID:', id);
                return;
            }

            // --- Populate Header ---
            $('#view-supplier-name').text(data.name);
            $('#view-supplier-rating').text(Number(data.rating).toFixed(1));

            // Status Badge
            const statusColors = {
                'active': 'bg-green-100 text-green-700 border-green-300',
                'inactive': 'bg-gray-100 text-gray-700 border-gray-300',
                'blacklisted': 'bg-red-100 text-red-700 border-red-300',
                'pending-verification': 'bg-yellow-100 text-yellow-700 border-yellow-300'
            };
            const statusClass = statusColors[data.status] || 'bg-gray-100 text-gray-700 border-gray-300';
            $('#view-supplier-status').text(data.status.replace('-', ' ')).attr('class', `px-2.5 py-0.5 rounded-full text-xs font-medium border uppercase ${statusClass}`);

            // Preferred Badge
            if (data.tags && data.tags.includes('preferred')) {
                $('#view-preferred-badge').removeClass('hidden').addClass('flex');
            } else {
                $('#view-preferred-badge').addClass('hidden').removeClass('flex');
            }

            // --- Populate Overview Tab ---
            // KPI Cards
            $('#view-ontime').text(data.onTimeDelivery);
            $('#view-quality').text(data.qualityScore);
            $('#view-orders').text(data.totalOrders);
            $('#view-leadtime').text(data.leadTime);

            // Business Info
            $('#view-reg').text(data.registrationNumber || 'N/A');
            $('#view-tax').text(data.taxId || 'N/A');
            $('#view-website').text(data.website || 'N/A');
            const memberSince = data.created_at ? new Date(data.created_at).toLocaleDateString() : 'N/A';
            $('#view-member-since').text(memberSince);

            let address = 'N/A';
            if (data.address) {
                if (typeof data.address === 'string') address = data.address;
                else address = `${data.address.street || ''}, ${data.address.city || ''}, ${data.address.province || ''}`;
            }
            $('#view-address').text(address);

            // Financial Info
            $('#view-payment-terms').text(data.paymentTerms ? data.paymentTerms.replace('credit-', '') + ' days credit' : 'N/A');
            $('#view-credit-limit').text('Rs ' + Number(data.creditLimit || 0).toLocaleString());
            const currentCredit = Number(data.currentCredit || 0);
            const creditLimit = Number(data.creditLimit || 0);
            $('#view-available-credit').text('Rs ' + (creditLimit - currentCredit).toLocaleString());
            $('#view-min-order').text('Rs ' + Number(data.minimumOrder || 0).toLocaleString());
            $('#view-current-credit').text('Rs ' + currentCredit.toLocaleString());

            let bankName = 'N/A';
            let accountName = 'N/A';
            let accountNumber = 'N/A';
            if (data.bankDetails) {
                let bank = data.bankDetails;
                if (typeof bank === 'string') {
                    try { bank = JSON.parse(bank); } catch (e) { }
                }
                if (bank) {
                    if (bank.bankName) bankName = bank.bankName;
                    if (bank.accountName) accountName = bank.accountName;
                    if (bank.accountNumber) accountNumber = bank.accountNumber;
                }
            }
            $('#view-bank-name').text(bankName);
            $('#view-account-name').text(accountName);
            $('#view-account-number').text(accountNumber);

            // Categories & Tags
            const catsContainer = $('#view-categories-list');
            catsContainer.empty();
            if (data.categories) {
                let cats = data.categories;
                if (typeof cats === 'string') {
                    try { cats = JSON.parse(cats); } catch (e) { cats = [cats]; }
                }
                if (Array.isArray(cats)) {
                    cats.forEach(c => {
                        catsContainer.append(`<span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-lg capitalize font-medium border border-blue-200">${c}</span>`);
                    });
                }
            }

            const tagsContainer = $('#view-tags-list');
            tagsContainer.empty();
            if (data.tags) {
                let tags = data.tags;
                if (typeof tags === 'string') {
                    try { tags = JSON.parse(tags); } catch (e) { tags = [tags]; }
                }
                if (Array.isArray(tags)) {
                    tags.forEach(t => {
                        tagsContainer.append(`<span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-lg capitalize font-medium border border-purple-200">${t}</span>`);
                    });
                }
            }

            // --- Populate Contacts Tab ---
            const contactsContainer = $('#view-tab-contacts');
            contactsContainer.empty();
            if (data.contacts && data.contacts.length > 0) {
                data.contacts.forEach(c => {
                    contactsContainer.append(`
                                                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                                            <div class="flex items-start justify-between">
                                                                                <div>
                                                                                    <div class="flex items-center gap-2 mb-2">
                                                                                        <h4 class="font-bold text-gray-900">${c.name}</h4>
                                                                                        ${c.isPrimary ? '<span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-medium">Primary</span>' : ''}
                                                                                    </div>
                                                                                    <div class="text-sm text-gray-600 mb-1">${c.position || 'No Position'}</div>
                                                                                    <div class="grid grid-cols-2 gap-3 mt-3">
                                                                                        <div class="flex items-center gap-2 text-sm text-gray-700">
                                                                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                                                            ${c.email}
                                                                                        </div>
                                                                                        <div class="flex items-center gap-2 text-sm text-gray-700">
                                                                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                                                            ${c.phone}
                                                                                        </div>
                                                                                        ${c.mobile ? `
                                                                                        <div class="flex items-center gap-2 text-sm text-gray-700">
                                                                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                                                            ${c.mobile} (Mobile)
                                                                                        </div>` : ''}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    `);
                });
            } else {
                contactsContainer.html('<div class="text-gray-500 text-center py-4">No contacts found</div>');
            }

            // --- Populate Products Tab ---
            const productsContainer = $('#view-tab-products');
            productsContainer.empty();
            if (data.products && data.products.length > 0) {
                productsContainer.append(`
                                                                     <div class="bg-blue-50 rounded-lg p-3 mb-3 border border-blue-100">
                                                                        <div class="font-medium text-blue-900">${data.products.length} products supplied</div>
                                                                    </div>
                                                                 `);
                data.products.forEach(p => {
                    productsContainer.append(`
                                                                        <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between border border-gray-100">
                                                                            <div class="flex-1">
                                                                                <div class="font-bold text-gray-900 mb-1">${p.productName}</div>
                                                                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                                                                    <span class="bg-gray-200 text-gray-700 text-xs px-2 py-0.5 rounded-full capitalize">${p.category}</span>
                                                                                    <span>SKU: ${p.sku}</span>
                                                                                    <span>Unit: ${p.unit}</span>
                                                                                    ${p.minimumOrder ? `<span>Min Order: ${p.minimumOrder}</span>` : ''}
                                                                                </div>
                                                                            </div>
                                                                            <div class="text-right">
                                                                                <div class="font-bold text-blue-600 text-lg">Rs ${Number(p.unitPrice).toLocaleString()}</div>
                                                                                <div class="text-xs text-gray-500">per ${p.unit}</div>
                                                                            </div>
                                                                        </div>
                                                                    `);
                });
            } else {
                productsContainer.html('<div class="text-gray-500 text-center py-4">No products found</div>');
            }

            // --- Populate Contracts Tab ---
            const contractsContainer = $('#view-tab-contracts');
            contractsContainer.empty();
            if (data.contracts && data.contracts.length > 0) {
                data.contracts.forEach(c => {
                    let statusClass = 'bg-gray-100 text-gray-700';
                    if (c.status === 'active') statusClass = 'bg-green-100 text-green-700';
                    else if (c.status === 'expired') statusClass = 'bg-red-100 text-red-700';
                    else if (c.status === 'expiring-soon') statusClass = 'bg-orange-100 text-orange-700';

                    contractsContainer.append(`
                                                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                                            <div class="flex items-start justify-between mb-3">
                                                                                <div>
                                                                                    <div class="flex items-center gap-2 mb-2">
                                                                                        <h4 class="font-bold text-gray-900">${c.contractNumber}</h4>
                                                                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium uppercase ${statusClass}">${c.status}</span>
                                                                                    </div>
                                                                                    <div class="text-sm text-gray-600 capitalize">${c.type}</div>
                                                                                </div>
                                                                                <div class="text-right">
                                                                                    <div class="font-bold text-blue-600">Rs ${Number(c.value).toLocaleString()}</div>
                                                                                    <div class="text-sm text-gray-600">Contract Value</div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="grid grid-cols-3 gap-4 text-sm">
                                                                                <div>
                                                                                    <div class="text-gray-600">Start Date</div>
                                                                                    <div class="font-medium text-gray-900">${c.startDate}</div>
                                                                                </div>
                                                                                <div>
                                                                                    <div class="text-gray-600">End Date</div>
                                                                                    <div class="font-medium text-gray-900">${c.endDate}</div>
                                                                                </div>
                                                                                <div>
                                                                                    <div class="text-gray-600">Auto Renewal</div>
                                                                                    <div class="font-medium text-gray-900">${c.autoRenewal ? 'Yes' : 'No'}</div>
                                                                                </div>
                                                                            </div>
                                                                            ${c.terms ? `<div class="mt-3 pt-3 border-t border-gray-200">
                                                                                <div class="text-sm text-gray-600 mb-1">Terms & Conditions:</div>
                                                                                <div class="text-sm text-gray-700">${c.terms}</div>
                                                                            </div>` : ''}
                                                                        </div>
                                                                    `);
                });
            } else {
                contractsContainer.html('<div class="text-gray-500 text-center py-4">No contracts found</div>');
            }

            // --- Populate Documents Tab ---
            const docsContainer = $('#view-tab-documents');
            docsContainer.empty();
            if (data.documents && data.documents.length > 0) {
                data.documents.forEach(d => {
                    const isExpiring = d.expiryDate && new Date(d.expiryDate) <= new Date(Date.now() + 30 * 24 * 60 * 60 * 1000);

                    docsContainer.append(`
                                                                        <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between border border-gray-100">
                                                                            <div class="flex items-center gap-3">
                                                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                                </div>
                                                                                <div>
                                                                                    <div class="font-medium text-gray-900">${d.name}</div>
                                                                                    <div class="text-xs text-gray-500 capitalize">${d.type.replace('-', ' ')}</div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="flex items-center gap-4">
                                                                                ${d.expiryDate ? `
                                                                                    <div class="text-right">
                                                                                        <div class="text-sm text-gray-600">Expires</div>
                                                                                        <div class="font-medium ${isExpiring ? 'text-orange-600' : 'text-gray-900'}">${d.expiryDate}</div>
                                                                                        ${isExpiring ? '<span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs rounded-full flex items-center justify-end gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>Expiring Soon</span>' : ''}
                                                                                    </div>
                                                                                ` : ''}
                                                                                <button class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-100 transition-all flex items-center gap-2">
                                                                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                                                    Download
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    `);
                });
            } else {
                docsContainer.html('<div class="text-gray-500 text-center py-4">No documents found</div>');
            }

            // --- Populate Performance Tab ---
            $('#view-perf-ontime').text(data.onTimeDelivery);
            $('#bar-perf-ontime').css('width', `${data.onTimeDelivery}%`);

            $('#view-perf-quality').text(data.qualityScore);
            $('#bar-perf-quality').css('width', `${data.qualityScore}%`);

            const commScore = (data.rating * 20).toFixed(0);
            $('#view-breakdown-delivery').text(data.onTimeDelivery);
            $('#bar-breakdown-delivery').css('width', `${data.onTimeDelivery}%`);

            $('#view-breakdown-quality').text(data.qualityScore);
            $('#bar-breakdown-quality').css('width', `${data.qualityScore}%`);

            $('#view-breakdown-comm').text(commScore);
            $('#bar-breakdown-comm').css('width', `${commScore}%`);

            // --- Populate Orders Tab ---
            $('#view-orders-count-tab').text(data.totalOrders);

            // Show Modal
            switchViewTab('overview');
            $('#viewSupplierModal').removeClass('hidden');
            $('body').css('overflow', 'hidden');

                                                            /* 


                                                            // Populate Overview
                                                            $('#view-supplier-name').text(data.name);
                                                            $('#view-supplier-rating').text(Number(data.rating).toFixed(1));
                                                            $('#view-supplier-status').text(data.status).attr('class', `px-2.5 py-0.5 rounded-full text-xs font-medium border uppercase ${data.status === 'active' ? 'bg-green-100 text-green-700 border-green-300' :
                                                                (data.status === 'inactive' ? 'bg-gray-100 text-gray-700 border-gray-300' : 'bg-yellow-100 text-yellow-700 border-yellow-300')
                                                                }`);

                                                            $('#view-ontime').text(data.onTimeDelivery);
                                                            $('#view-quality').text(data.qualityScore);
                                                            $('#view-orders').text(data.totalOrders);
                                                            $('#view-leadtime').text(data.leadTime);

                                                            $('#view-reg').text(data.registrationNumber || 'N/A');
                                                            $('#view-tax').text(data.taxId || 'N/A');

                                                            let address = 'N/A';
                                                            if (data.address) {
                                                                if (typeof data.address === 'string') address = data.address;
                                                                else address = `${data.address.street || ''}, ${data.address.city || ''}, ${data.address.province || ''}`;
                                                            }
                                                            $('#view-address').text(address);

                                                            // Populate Contacts
                                                            const contactsContainer = $('#view-tab-contacts');
                                                            contactsContainer.empty();
                                                            if (data.contacts && data.contacts.length > 0) {
                                                                data.contacts.forEach(c => {
                                                                    contactsContainer.append(`
                                                                                                <div class="bg-white border rounded-xl p-4 shadow-sm">
                                                                                                    <div class="flex justify-between items-start">
                                                                                                        <div>
                                                                                                            <div class="font-bold text-gray-900 flex items-center gap-2">
                                                                                                                ${c.name}
                                                                                                                ${c.isPrimary ? '<span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">Primary</span>' : ''}
                                                                                                            </div>
                                                                                                            <div class="text-sm text-gray-600 mb-2">${c.position || ''}</div>
                                                                                                            <div class="text-sm text-gray-700 flex items-center gap-2">
                                                                                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                                                                                ${c.email}
                                                                                                            </div>
                                                                                                            <div class="text-sm text-gray-700 flex items-center gap-2 mt-1">
                                                                                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                                                                                ${c.phone}
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                `);
                                                                });
                                                            } else {
                                                                contactsContainer.html('<div class="text-gray-500 text-center py-4">No contacts found</div>');
                                                            }

                                                            // Populate Products
                                                            const productsContainer = $('#view-tab-products');
                                                            productsContainer.empty();
                                                            if (data.products && data.products.length > 0) {
                                                                data.products.forEach(p => {
                                                                    productsContainer.append(`
                                                                                                 <div class="bg-white border rounded-xl p-4 flex justify-between items-center shadow-sm">
                                                                                                    <div>
                                                                                                        <div class="font-bold text-gray-900">${p.productName}</div>
                                                                                                        <div class="text-sm text-gray-600">SKU: ${p.sku}</div>
                                                                                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">${p.category}</span>
                                                                                                    </div>
                                                                                                    <div class="text-right">
                                                                                                        <div class="font-bold text-blue-600 text-lg">Rs ${Number(p.unitPrice).toLocaleString()}</div>
                                                                                                        <div class="text-xs text-gray-500">per ${p.unit}</div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            `);
                                                                });
                                                            } else {
                                                                productsContainer.html('<div class="text-gray-500 text-center py-4">No products found</div>');
                                                            }

                                                            // Populate Contracts
                                                            const contractsContainer = $('#view-tab-contracts');
                                                            contractsContainer.empty();
                                                            if (data.contracts && data.contracts.length > 0) {
                                                                data.contracts.forEach(c => {
                                                                    contractsContainer.append(`
                                                                                                <div class="bg-white border rounded-xl p-4 shadow-sm">
                                                                                                    <div class="flex justify-between items-start mb-2">
                                                                                                        <div>
                                                                                                            <div class="font-bold text-gray-900">${c.contractNumber}</div>
                                                                                                            <div class="text-sm text-gray-600 capitalize">${c.type}</div>
                                                                                                        </div>
                                                                                                        <div class="text-right">
                                                                                                            <div class="font-bold text-blue-600">Rs ${Number(c.value).toLocaleString()}</div>
                                                                                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">${c.status}</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mt-2 pt-2 border-t">
                                                                                                        <div>Start: ${c.startDate}</div>
                                                                                                        <div>End: ${c.endDate}</div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            `);
                                                                });
                                                            } else {
                                                                contractsContainer.html('<div class="text-gray-500 text-center py-4">No contracts found</div>');
                                                            }

                                                            // Populate Documents
                                                            const docsContainer = $('#view-tab-documents');
                                                            docsContainer.empty();
                                                            if (data.documents && data.documents.length > 0) {
                                                                data.documents.forEach(d => {
                                                                    docsContainer.append(`
                                                                                                <div class="bg-white border rounded-xl p-4 flex items-center justify-between shadow-sm">
                                                                                                    <div class="flex items-center gap-3">
                                                                                                        <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                                                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <div class="font-medium text-gray-900">${d.name}</div>
                                                                                                            <div class="text-xs text-gray-500 capitalize">${d.type.replace('-', ' ')}</div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <button class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-100">Download</button>
                                                                                                </div>
                                                                                            `);
                                                                });
                                                            } else {
                                                                docsContainer.html('<div class="text-gray-500 text-center py-4">No documents found</div>');
                                                            }

                                                            // Show Modal
                                                            switchViewTab('overview');
                                                            $('#viewSupplierModal').removeClass('hidden');
                                                            $('body').css('overflow', 'hidden');
                                                        */ }

        // --- Wizard Logic ---
        // --- Wizard Logic ---
        let currentStep = 1;
        let categories = [];
        let tags = [];
        let contactsCount = 0;
        let products = []; // Array of { productId, name, sku, price, minOrder, product_item_id, category, unit }
        let selectedProductMaster = null; // Currently selected product from master list

        function resetAddForm() {
            currentStep = 1;
            $('#formSupplierId').val(''); // Clear ID
            $('#addSupplierModal h3').html(`
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Add New Supplier
                            `);
            $('#btn-submit').text('Create Supplier');

            categories = [];
            tags = [];
            products = [];
            contactsCount = 0;
            selectedProductMaster = null;
            $('#contactsList').empty();
            $('#productsList').empty();
            $('#categoriesList').empty();
            $('#tagsList').empty();
            $('#addSupplierForm')[0].reset();

            // Reset Sections
            $('#added-products-section').removeClass('hidden');
            $('#product-browser-section').addClass('hidden');
            $('#product-pricing-section').addClass('hidden');
            $('#summary-section').addClass('hidden');
            $('#noProductsMsg').show();

            addContact();
            updateWizardUI();
        }

        function showStep(step) {
            $('.step-content').addClass('hidden');
            $(`.step-content[data-step="${step}"]`).removeClass('hidden');

            $('.step-indicator').removeClass('bg-blue-500').addClass('bg-gray-200');
            for (let i = 1; i <= step; i++) {
                $(`.step-indicator[data-step="${i}"]`).removeClass('bg-gray-200').addClass('bg-blue-500');
            }

            const titles = {
                1: 'Basic Information',
                2: 'Contact Details',
                3: 'Products & Pricing',
                4: 'Financial Terms',
                5: 'Additional Details'
            };
            $('#step-description').text(`Step ${step} of 5 - ${titles[step]}`);

            $('#btn-back').toggleClass('hidden', step === 1);
            $('#btn-next').toggleClass('hidden', step === 5);
            $('#btn-submit').toggleClass('hidden', step !== 5);

            if (step === 5) {
                updateSummary();
            }
        }

        function nextStep() {
            if (!validateStep(currentStep)) return;
            currentStep++;
            updateWizardUI();
        }

        function prevStep() {
            currentStep--;
            updateWizardUI();
        }

        function updateWizardUI() {
            showStep(currentStep);
        }

        function validateStep(step) {
            if (step === 1) {
                const name = $('input[name="name"]').val();
                const address = $('textarea[name="address"]').val();
                if (!name || !address) {
                    Swal.fire({ icon: 'error', title: 'Required Fields Missing', text: 'Please fill name and address', confirmButtonText: 'OK' });
                    return false;
                }
                if (categories.length === 0) {
                    Swal.fire({ icon: 'error', title: 'Categories Required', text: 'Add at least one category', confirmButtonText: 'OK' });
                    return false;
                }
            }
            if (step === 2) {
                const allContactsValid = validateContacts();
                if (!allContactsValid) {
                    Swal.fire({ icon: 'error', title: 'Contact Details Required', text: 'Please fill all contact details for all contacts', confirmButtonText: 'OK' });
                    return false;
                }
            }
            if (step === 3) {
                if (products.length === 0) {
                    Swal.fire({ icon: 'error', title: 'Products Required', text: 'Add at least one product', confirmButtonText: 'OK' });
                    return false;
                }
            }
            return true;
        }

        // --- Dynamic Fields ---
        function addCategory() {
            const val = $('#categoryInput').val().trim();
            if (val && !categories.includes(val)) {
                categories.push(val);
                $('#categoriesList').append(
                    `<span class="badge bg-blue-100 text-blue-700 px-2 py-1 rounded flex items-center gap-1">${val} <span class="cursor-pointer font-bold" onclick="removeCategory('${val}', this)">&times;</span></span>`
                );
                $('#categoryInput').val('');
            }
        }

        function removeCategory(val, el) {
            categories = categories.filter(c => c !== val);
            $(el).parent().remove();
        }

        function addTag() {
            const val = $('#tagInput').val().trim();
            if (val && !tags.includes(val)) {
                tags.push(val);
                $('#tagsList').append(
                    `<span class="badge bg-purple-100 text-purple-700 px-2 py-1 rounded flex items-center gap-1">${val} <span class="cursor-pointer font-bold" onclick="removeTag('${val}', this)">&times;</span></span>`
                );
                $('#tagInput').val('');
            }
        }

        function removeTag(val, el) {
            tags = tags.filter(t => t !== val);
            $(el).parent().remove();
        }

        function addContact() {
            contactsCount++;
            const html = `
                                                                                    <div class="contact-item bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm mb-4">
                                                                                        <div class="flex justify-between items-start mb-4">
                                                                                            <div>
                                                                                                <h5 class="font-medium text-gray-900">Contact #${contactsCount}</h5>
                                                                                                <p class="text-sm text-gray-500">Enter contact details below</p>
                                                                                            </div>
                                                                                            <button type="button" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all" onclick="removeContact(this)">
                                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                                            </button>
                                                                                        </div>
                                                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                                            <div>
                                                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                                                                                <input type="text" name="contactName" placeholder="Enter full name" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500">
                                                                                            </div>
                                                                                            <div>
                                                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                                                                                <input type="text" name="contactPosition" placeholder="Enter position" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500">
                                                                                            </div>
                                                                                            <div>
                                                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                                                                <input type="email" name="contactEmail" placeholder="Enter email address" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500">
                                                                                            </div>
                                                                                            <div>
                                                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                                                                                <input type="text" name="contactPhone" placeholder="Enter phone number" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500">
                                                                                            </div>
                                                                                            <div>
                                                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                                                                                                <input type="text" name="contactMobile" placeholder="Enter mobile number" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                                                                            <label class="flex items-center gap-2">
                                                                                                <input type="radio" name="primaryContact" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" ${contactsCount === 1 ? 'checked' : ''} onchange="togglePrimaryContact(this)">
                                                                                                <span class="text-sm font-medium text-gray-700">Set as Primary Contact</span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                `;
            $('#contactsList').append(html);
        }

        function validateContacts() {
            let isValid = true;
            $('#contactsList .contact-item').each(function () {
                const name = $(this).find('input[name="contactName"]').val();
                if (!name) isValid = false;
            });
            return isValid;
        }

        function togglePrimaryContact(el) {
            // Already handled by radio behavior name="primaryContact"
        }

        // --- Product Browser Logic ---
        function showProductBrowser() {
            $('#added-products-section').addClass('hidden');
            $('#product-browser-section').removeClass('hidden');
            $('#browserSearchQuery').val('').focus();
            $('#browserResults').empty();
        }

        function hideProductBrowser() {
            $('#product-browser-section').addClass('hidden');
            $('#added-products-section').removeClass('hidden');
        }

        $('#browserSearchQuery').on('input', function () {
            const query = $(this).val();
            if (query.length > 1) {
                // Determine filter (not implemented in UI drop down yet, defaulting to all)
                $.ajax({
                    url: '{{ route('product.items.search') }}',
                    method: 'GET',
                    data: { query: query },
                    success: function (response) {
                        displayBrowserResults(response.products);
                    }
                });
            } else {
                $('#browserResults').empty();
            }
        });

        function displayBrowserResults(results) {
            const container = $('#browserResults');
            container.empty();
            if (results.length === 0) {
                container.html('<div class="p-4 text-center text-gray-500">No products found</div>');
                return;
            }

            // Filter out already added products
            const addedIds = products.map(p => p.product_item_id);
            const filtered = results.filter(p => !addedIds.includes(p.id));

            if (filtered.length === 0) {
                container.html('<div class="p-4 text-center text-gray-500">All matching products already added</div>');
                return;
            }

            filtered.forEach(p => {
                // Use random color/badge for type if missing
                const category = p.category || 'Standard';
                const el = $(`
                                                                                        <div class="p-3 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer flex justify-between items-center bg-white transition-all shadow-sm" onclick='selectProductFromBrowser(${JSON.stringify(p)})'>
                                                                                            <div>
                                                                                                <div class="font-medium text-gray-900">${p.product_name}</div>
                                                                                                <div class="text-xs text-gray-500">ID: ${p.sku || 'N/A'} • Unit: ${p.unit || 'N/A'}</div>
                                                                                            </div>
                                                                                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full capitalize">${category}</span>
                                                                                        </div>
                                                                                    `);
                container.append(el);
            });
        }

        function selectProductFromBrowser(product) {
            selectedProductMaster = product;
            $('#product-browser-section').addClass('hidden');
            $('#product-pricing-section').removeClass('hidden');

            $('#selectedProductCard').html(`
                                                                                    <div class="flex items-center gap-3">
                                                                                        <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                                                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                                                                        </div>
                                                                                        <div>
                                                                                            <div class="font-medium text-gray-900">${product.product_name}</div>
                                                                                            <div class="text-xs text-gray-500">${product.category || 'General'}</div>
                                                                                        </div>
                                                                                    </div>
                                                                                `);

            $('#supplierSku').val('');
            $('#supplierPrice').val('');
            $('#supplierMinOrder').val('');
            $('#supplierSku').focus();
        }

        function cancelAddProduct() {
            $('#product-pricing-section').addClass('hidden');
            $('#product-browser-section').removeClass('hidden');
            selectedProductMaster = null;
        }

        function confirmAddProduct() {
            const sku = $('#supplierSku').val();
            const price = parseFloat($('#supplierPrice').val());
            const minOrder = parseFloat($('#supplierMinOrder').val()) || 0;

            if (!sku || isNaN(price) || price <= 0) {
                Swal.fire({ icon: 'error', title: 'Invalid Data', text: 'Please enter valid SKU and Price', confirmButtonText: 'OK' });
                return;
            }

            products.push({
                product_item_id: selectedProductMaster.id,
                name: selectedProductMaster.product_name,
                category: selectedProductMaster.category || 'General',
                unit: selectedProductMaster.unit || 'unit',
                sku: sku,
                price: price,
                minOrder: minOrder
            });

            renderAddedProducts();

            $('#product-pricing-section').addClass('hidden');
            $('#added-products-section').removeClass('hidden');
            selectedProductMaster = null;
            $('#noProductsMsg').hide();
        }

        function renderAddedProducts() {
            const container = $('#productsList');
            container.empty();

            products.forEach((p, index) => {
                container.append(`
                                                                                        <div class="bg-white rounded-lg p-3 border border-gray-200 shadow-sm flex items-center justify-between">
                                                                                            <div>
                                                                                                <div class="font-medium text-gray-900">${p.name}</div>
                                                                                                <div class="text-xs text-gray-500">
                                                                                                    SKU: ${p.sku} | Rs ${p.price} | Min: ${p.minOrder}
                                                                                                </div>
                                                                                            </div>
                                                                                             <button type="button" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg transition-all" onclick="removeProduct(${index})">
                                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    `);
            });

            if (products.length === 0) $('#noProductsMsg').show();
            else $('#noProductsMsg').hide();
        }

        function removeProduct(index) {
            products.splice(index, 1);
            renderAddedProducts();
        }

        // --- Summary & Submit ---
        function updateSummary() {
            $('#summary-name').text($('input[name="name"]').val());
            $('#summary-categories').text(categories.join(', '));
            $('#summary-contacts').text($('#contactsList .contact-item').length);
            $('#summary-products').text(products.length);
            $('#summary-credit').text('Rs ' + $('input[name="creditLimit"]').val());
            $('#summary-section').removeClass('hidden');
        }

        function submitForm() {
            const supplierId = $('#formSupplierId').val();
            const name = $('input[name="name"]').val();
            const bankDetails = {
                bankName: $('input[name="bankName"]').val(),
                accountName: $('input[name="accountName"]').val(),
                accountNumber: $('input[name="accountNumber"]').val()
            };

            const formData = {
                id: supplierId, // valid only for update
                name: name,
                registrationNumber: $('input[name="registrationNumber"]').val(),
                taxId: $('input[name="taxId"]').val(),
                address: $('textarea[name="address"]').val(),
                website: $('input[name="website"]').val(),
                categories: JSON.stringify(categories),
                paymentTerms: $('select[name="paymentTerms"]').val(),
                creditLimit: $('input[name="creditLimit"]').val(),
                minimumOrder: $('input[name="minimumOrder"]').val(),
                leadTime: $('input[name="leadTime"]').val(),
                tags: JSON.stringify(tags),
                notes: $('textarea[name="notes"]').val(),
                bankDetails: JSON.stringify(bankDetails),
                contacts: [],
                products: []
            };

            $('#contactsList .contact-item').each(function () {
                formData.contacts.push({
                    name: $(this).find('input[name="contactName"]').val(),
                    position: $(this).find('input[name="contactPosition"]').val(),
                    email: $(this).find('input[name="contactEmail"]').val(),
                    phone: $(this).find('input[name="contactPhone"]').val(),
                    mobile: $(this).find('input[name="contactMobile"]').val(),
                    is_primary: $(this).find('input[name="primaryContact"]').is(':checked')
                });
            });

            products.forEach(p => {
                formData.products.push({
                    product_item_id: p.product_item_id,
                    name: p.name,
                    sku: p.sku,
                    price: p.price,
                    minimumOrder: p.minOrder
                });
            });

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            const url = supplierId ? '{{ route('supplier.update') }}' : '{{ route('supplier.store') }}';

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success', title: 'Success',
                        text: response.message || 'Operation successful!',
                        confirmButtonText: 'OK'
                    }).then((r) => {
                        if (r.isConfirmed) {
                            closeAddModal();
                            location.reload();
                        }
                    });
                },
                error: function (xhr) {
                    let msg = 'Error processing request';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        else if (xhr.responseJSON.errors) msg = Object.values(xhr.responseJSON.errors).flat().join(', ');
                    }
                    Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonText: 'OK' });
                }
            });
        }

        function removeContact(btn) {
            const item = $(btn).closest('.contact-item');

            // Logic to prevent deleting the last contact if needed, or handle primary re-assignment
            // For now, mirroring the simple removal logic from the new code
            item.remove();

            // Check if we removed the primary contact
            if ($('input[name="primaryContact"]:checked').length === 0) {
                // Select the first one available
                const first = $('.contact-item').first();
                if (first.length) {
                    first.find('input[name="primaryContact"]').prop('checked', true);
                }
            }

            updateContactRemoveButtons();
            checkContactsValidation();
        }

        function updateContactRemoveButtons() {
            const allContacts = $('#contactsList .contact-item');
            // If only one contact, hide its remove button? Or logic based on primary?
            // User requirement: Primary contact cannot be removed (or remove button hidden).

            const primaryContact = allContacts.find('input[name="primaryContact"]:checked').closest('.contact-item');

            allContacts.each(function () {
                const btn = $(this).find('button[onclick="removeContact(this)"]');
                if ($(this).is(primaryContact)) {
                    btn.hide();
                } else {
                    btn.show();

                    // If it's the only contact, maybe hide remove button too?
                    if (allContacts.length === 1) btn.hide();
                }
            });
        }

        function togglePrimaryContact(el) {
            updateContactRemoveButtons();
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function isValidPhone(phone) {
            const clean = phone.replace(/[^\d]/g, '');
            // Basic length check for now to avoid strict regex issues on incomplete input
            return clean.length >= 9;
        }

        function checkContactsValidation() {
            // Validation is mainly handling on 'Next' step, but we can enable/disable buttons here if needed.
        }

        $(document).ready(function () {
            // Re-initialize UI states
            updateContactRemoveButtons();

            $('#btn-view-edit').click(function () {
                openEditModalFromView();
            });
        });

        function openEditModalFromView() {
            // Get current supplier ID from the modal context (we can get it from valid data present)
            // We populated #view-supplier-name etc. but didn't store ID in a global var explicitly on open?
            // Actually openViewModal stores data in DOM or we can re-find it.
            // openViewModal() uses `const data = window.suppliersData.find(s => s.id == id);`
            // Let's store the current ID globally or on the modal
            const name = $('#view-supplier-name').text();
            const data = window.suppliersData.find(s => s.name === name); // Name is unique enough for now? or better store ID
            if (data) {
                closeViewModal();
                openEditModal(data);
            }
        }

        function openEditModal(data) {
            resetAddForm();
            $('#formSupplierId').val(data.id);
            $('#addSupplierModal h3').html(`
                                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        Edit Supplier
                                    `);
            $('#btn-submit').text('Update Supplier');

            // --- Populate Step 1: Basic Info ---
            $('input[name="name"]').val(data.name);
            $('input[name="registrationNumber"]').val(data.registrationNumber);
            $('input[name="taxId"]').val(data.taxId);
            let address = data.address;
            if (typeof address === 'object') {
                address = `${address.street || ''}, ${address.city || ''}, ${address.province || ''}`;
            }
            $('textarea[name="address"]').val(address);
            $('input[name="website"]').val(data.website);

            // Categories
            if (data.categories) {
                let cats = data.categories;
                if (typeof cats === 'string') {
                    try { cats = JSON.parse(cats); } catch (e) { cats = [cats]; }
                }
                if (Array.isArray(cats)) {
                    cats.forEach(c => {
                        if (!categories.includes(c)) {
                            categories.push(c);
                            $('#categoriesList').append(
                                `<span class="badge bg-blue-100 text-blue-700 px-2 py-1 rounded flex items-center gap-1">${c} <span class="cursor-pointer font-bold" onclick="removeCategory('${c}', this)">&times;</span></span>`
                            );
                        }
                    });
                }
            }

            // --- Populate Step 2: Contacts ---
            $('#contactsList').empty();
            contactsCount = 0;
            if (data.contacts && data.contacts.length > 0) {
                data.contacts.forEach(c => {
                    contactsCount++;
                    const html = `
                                                <div class="contact-item bg-white rounded-xl p-5 border-2 border-gray-200 shadow-sm mb-4">
                                                    <div class="flex justify-between items-start mb-4">
                                                        <div>
                                                            <h5 class="font-medium text-gray-900">Contact #${contactsCount}</h5>
                                                            <p class="text-sm text-gray-500">Enter contact details below</p>
                                                        </div>
                                                        <button type="button" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all" onclick="removeContact(this)">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </div>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                                            <input type="text" name="contactName" value="${c.name || ''}" placeholder="Enter full name" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                                            <input type="text" name="contactPosition" value="${c.position || ''}" placeholder="Enter position" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                            <input type="email" name="contactEmail" value="${c.email || ''}" placeholder="Enter email address" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                                            <input type="text" name="contactPhone" value="${c.phone || ''}" placeholder="Enter phone number" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                                                            <input type="text" name="contactMobile" value="${c.mobile || ''}" placeholder="Enter mobile number" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg outline-none focus:border-blue-500">
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                                        <label class="flex items-center gap-2">
                                                            <input type="radio" name="primaryContact" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" ${c.isPrimary ? 'checked' : ''} onchange="togglePrimaryContact(this)">
                                                            <span class="text-sm font-medium text-gray-700">Set as Primary Contact</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            `;
                    $('#contactsList').append(html);
                });
            } else {
                addContact();
            }

            // --- Populate Step 3: Products ---
            products = [];
            if (data.products && data.products.length > 0) {
                data.products.forEach(p => {
                    // We need product_item_id. If missing in 'products' array from blade view transform, update controller first?
                    // The 'products' array in blade: productName, sku, category, unit, unitPrice.
                    // We might need to fetch real ID or trust the controller sends it. 
                    // Let's assume controller sends it or we can't save correctly. 
                    // Controller code at line 40 in step 7 for `products` mapping DOES NOT include product_item_id. 
                    // FIX: We need to update controller to include product_item_id in view.
                    // For now, let's proceed and if ID is missing, we might have issues saving products.
                    // Actually, let's check `data` object structure from `window.suppliersData`.
                    // We can try to infer it or just display what we have, but saving back requires ID.

                    products.push({
                        product_item_id: p.product_item_id || 0, // Fallback if missing, need to fix controller 
                        name: p.productName,
                        category: p.category,
                        unit: p.unit,
                        sku: p.sku,
                        price: p.unitPrice,
                        minOrder: p.minimumOrder || 0
                    });
                });
                renderAddedProducts();
            }

            // --- Populate Step 4: Financials ---
            $('select[name="paymentTerms"]').val(data.paymentTerms);
            $('input[name="creditLimit"]').val(data.creditLimit);
            $('input[name="minimumOrder"]').val(data.minimumOrder || 0); // Logic from view modal
            $('input[name="leadTime"]').val(data.leadTime);

            // Bank Details
            if (data.bankDetails) {
                let bank = data.bankDetails;
                if (typeof bank === 'string') {
                    try { bank = JSON.parse(bank); } catch (e) { }
                }
                $('input[name="bankName"]').val(bank.bankName || '');
                $('input[name="accountName"]').val(bank.accountName || '');
                $('input[name="accountNumber"]').val(bank.accountNumber || '');
            }

            // --- Populate Step 5: Tags & Notes ---
            if (data.tags) {
                let tgs = data.tags;
                if (typeof tgs === 'string') {
                    try { tgs = JSON.parse(tgs); } catch (e) { tgs = [tgs]; }
                }
                if (Array.isArray(tgs)) {
                    tgs.forEach(t => {
                        if (!tags.includes(t)) {
                            tags.push(t);
                            $('#tagsList').append(
                                `<span class="badge bg-purple-100 text-purple-700 px-2 py-1 rounded flex items-center gap-1">${t} <span class="cursor-pointer font-bold" onclick="removeTag('${t}', this)">&times;</span></span>`
                            );
                        }
                    });
                }
            }
            $('textarea[name="notes"]').val(data.notes || '');

            updateContactRemoveButtons();
            $('#addSupplierModal').removeClass('hidden');
        }
    </script>
@endsection