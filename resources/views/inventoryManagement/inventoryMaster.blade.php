@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6 bg-gray-50 min-h-screen">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Inventory Master</h1>
                <p class="text-gray-600 mt-1">Complete view of all products across all locations</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Export
                </button>
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Refresh
                </button>
                <a href="{{ route('productRegistration.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Add Product
                </a>
            </div>
        </div>

        {{-- Summary Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Total Products --}}
            <div class="bg-white p-4 rounded-lg border border-blue-200 bg-blue-50 flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-medium">Total Products</p>
                    <p class="text-2xl font-bold text-blue-900 mt-1">{{ number_format($stats['totalProducts']) }}</p>
                </div>
                <i class="bi bi-box-seam h-8 w-8 text-2xl text-blue-600"></i>
            </div>

            {{-- Total Value --}}
            <div class="bg-white p-4 rounded-lg border border-green-200 bg-green-50 flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600 font-medium">Total Value</p>
                    <p class="text-2xl font-bold text-green-900 mt-1">Rs. {{ number_format($stats['totalValue'], 2) }}</p>
                </div>
                <i class="bi bi-currency-dollar h-8 w-8 text-2xl text-green-600"></i>
            </div>

            {{-- Low Stock --}}
            <div class="bg-white p-4 rounded-lg border border-yellow-200 bg-yellow-50 flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-600 font-medium">Low Stock</p>
                    <p class="text-2xl font-bold text-yellow-900 mt-1">{{ number_format($stats['lowStock']) }}</p>
                </div>
                <i class="bi bi-exclamation-triangle h-8 w-8 text-2xl text-yellow-600"></i>
            </div>

            {{-- Out of Stock --}}
            <div class="bg-white p-4 rounded-lg border border-red-200 bg-red-50 flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-600 font-medium">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-900 mt-1">{{ number_format($stats['outOfStock']) }}</p>
                </div>
                <i class="bi bi-x-circle h-8 w-8 text-2xl text-red-600"></i>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <form action="{{ route('inventoryMaster.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search products by name or code..."
                        class="block w-full p-3 bg-gray-50 pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-10">
                </div>
                <select name="location_type" onchange="this.form.submit()"
                    class="block p-3 bg-gray-50 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-10">
                    <option value="warehouse" {{ ($filters['location_type'] ?? 'warehouse') == 'warehouse' ? 'selected' : '' }}>Warehouse</option>
                    @foreach($branches as $branch)
                        <option value="branch-{{ $branch->id }}" {{ ($filters['location_type'] ?? '') == 'branch-' . $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
                <select name="status" onchange="this.form.submit()"
                    class="block p-3 bg-gray-50 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-10">
                    <option value="all" {{ ($filters['status'] ?? '') == 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="in-stock" {{ ($filters['status'] ?? '') == 'in-stock' ? 'selected' : '' }}>In Stock
                    </option>
                    <option value="low-stock" {{ ($filters['status'] ?? '') == 'low-stock' ? 'selected' : '' }}>Low Stock
                    </option>
                    <option value="out-of-stock" {{ ($filters['status'] ?? '') == 'out-of-stock' ? 'selected' : '' }}>Out of
                        Stock</option>
                    <option value="expiring-soon" {{ ($filters['status'] ?? '') == 'expiring-soon' ? 'selected' : '' }}>
                        Expiring Soon</option>
                </select>
                <select name="category" onchange="this.form.submit()"
                    class="block p-3 bg-gray-50 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm h-10">
                    <option value="all" {{ ($filters['category'] ?? '') == 'all' ? 'selected' : '' }}>All Categories</option>
                    @foreach($productTypes as $type)
                        <option value="{{ $type->id }}" {{ ($filters['category'] ?? '') == $type->id ? 'selected' : '' }}>
                            {{ $type->product_type_name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Product List --}}
        <div class="space-y-3">
            @forelse($filteredProducts as $product)
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden"
                    id="product-card-{{ $product->id }}">

                    {{-- Product Header --}}
                    <div class="p-4 cursor-pointer hover:bg-gray-50 transition-colors"
                        onclick="toggleProductExpansion('{{ $product->id }}')">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1">
                                <button class="p-0 h-auto hover:bg-transparent text-gray-400">
                                    <svg id="chevron-right-{{ $product->id }}" class="h-5 w-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                    </svg>
                                    <svg id="chevron-down-{{ $product->id }}" class="h-5 w-5 hidden" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium border border-gray-200 bg-white text-gray-600 rounded">{{ $product->code }}</span>
                                        <span
                                            class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded">{{ $product->section }}</span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span class="flex items-center gap-1">
                                            <i class="bi bi-box-seam"></i>
                                            <span class="capitalize">{{ str_replace('-', ' ', $product->category) }}</span>
                                        </span>
                                        <span>Unit: {{ $product->unit }}</span>
                                        <span class="flex items-center gap-1">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            <span>{{ count($product->batches) }}
                                                batch{{ count($product->batches) !== 1 ? 'es' : '' }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-8">
                                <div class="text-right">
                                    <p class="text-xs text-gray-400 font-bold uppercase">Total Stock</p>
                                    <p class="text-xl font-bold">{{ $product->totalQuantity }} {{ $product->unit }}</p>
                                    <div class="w-32 bg-gray-100 rounded-full h-1.5 mt-1 overflow-hidden">
                                        @php
                                            $percentage = $product->maxStock > 0 ? ($product->totalQuantity / $product->maxStock) * 100 : 0;
                                            $colorClass = 'bg-green-500';
                                            if ($percentage <= 20)
                                                $colorClass = 'bg-red-500';
                                            elseif ($percentage <= 50)
                                                $colorClass = 'bg-yellow-500';
                                        @endphp
                                        <div class="h-1.5 rounded-full {{ $colorClass }}"
                                            style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                </div>
                                <div class="text-right hidden xl:block">
                                    <p class="text-xs text-gray-400 font-bold uppercase">Reorder Point</p>
                                    <p class="text-lg font-semibold text-gray-700">{{ $product->reorderPoint }}
                                        {{ $product->unit }}</p>
                                </div>
                                <div class="text-right hidden lg:block">
                                    <p class="text-xs text-gray-400 font-bold uppercase">Total Value</p>
                                    <p class="text-lg font-semibold">Rs. {{ number_format($product->totalValue, 2) }}</p>
                                </div>
                                <div class="w-32 flex justify-end">
                                    @if($product->status === 'out-of-stock')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 gap-1">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            Out of Stock
                                        </span>
                                    @elseif($product->status === 'low-stock')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 gap-1">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            Low Stock
                                        </span>
                                    @elseif($product->status === 'expiring-soon')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 gap-1">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                            Expiring Soon
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 gap-1">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                            </svg>
                                            In Stock
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Batch Details (Warehouse View) --}}
                    <div id="details-{{ $product->id }}" class="hidden border-t bg-gray-50 p-4 transition-all duration-300">

                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="font-semibold text-sm text-gray-700 uppercase tracking-wide">Stock Details</h4>
                            {{-- Actions --}}
                            <div class="flex gap-2">
                                <a href="{{ route('manageStockTransfers.index') }}?product={{ $product->id }}"
                                    onclick="event.stopPropagation()"
                                    class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded text-xs font-medium text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                    Transfer
                                </a>
                                <a href="{{ route('stockAdjustments.index') }}?product={{ $product->id }}"
                                    onclick="event.stopPropagation()"
                                    class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded text-xs font-medium text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    Adjust
                                </a>
                                <a href="{{ route('createPurchaseOrder.index') }}?product={{ $product->id }}"
                                    onclick="event.stopPropagation()"
                                    class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded text-xs font-medium text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    Order
                                </a>
                            </div>
                        </div>

                        {{-- Batch/Stock Details View (Unified) --}}
                        @if(count($product->batches) === 0)
                            <div class="text-center py-6 text-gray-500">
                                <p>No stock batches found for this location.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto border border-gray-200 rounded-lg bg-white">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Batch Number</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Received Date</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Expiry</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Quantity</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Qty in Unit</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($product->batches as $batch)
                                            <tr class="{{ $batch['isExpiring'] ? 'bg-red-50' : '' }}">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                                    {{ $batch['batchNumber'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($batch['receivedDate'])->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($batch['expiryDate'])
                                                        {{ \Carbon\Carbon::parse($batch['expiryDate'])->format('d/m/Y') }}
                                                    @elseif($batch['expirePeriod'])
                                                        {{ $batch['expirePeriod'] }} days
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-bold">
                                                    {{ $batch['quantity'] }} {{ $batch['unit'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right font-mono">
                                                    @php
                                                        $unitSuffix = '';
                                                        $uType = $batch['unitType'] ?? null;
                                                        if($uType == 1 || $uType == 3) $unitSuffix = 'g';
                                                        elseif($uType == 2 || $uType == 4) $unitSuffix = 'ml';
                                                        elseif($uType == 5) $unitSuffix = 'pieces';
                                                    @endphp
                                                    @if(($batch['qtyInUnit'] ?? 0) > 0)
                                                        {{ number_format($batch['qtyInUnit']) }} 
                                                        @if(!empty($unitSuffix))
                                                            ({{ $unitSuffix }})
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg border border-gray-200 p-12 text-center shadow-sm">
                    <svg class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No products found</p>
                    <p class="text-gray-400 text-sm mt-2">Try adjusting your filters or search query</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function toggleProductExpansion(productId) {
            const details = document.getElementById('details-' + productId);
            const card = document.getElementById('product-card-' + productId);
            const chevronRight = document.getElementById('chevron-right-' + productId);
            const chevronDown = document.getElementById('chevron-down-' + productId);

            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                card.classList.add('ring-2', 'ring-indigo-500');
                chevronRight.classList.add('hidden');
                chevronDown.classList.remove('hidden');
            } else {
                details.classList.add('hidden');
                card.classList.remove('ring-2', 'ring-indigo-500');
                chevronRight.classList.remove('hidden');
                chevronDown.classList.add('hidden');
            }
        }
    </script>
@endsection