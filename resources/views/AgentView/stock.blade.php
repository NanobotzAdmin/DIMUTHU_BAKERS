@extends('layouts.app')

@section('title', 'My Stock')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Current Stock Inventory</h1>
            <p class="text-sm text-gray-500">Monitor loaded quantities and current balances inside your distribution vehicle.</p>
        </div>
        <a href="{{ route('agent-panel.dashboard') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors no-underline">
            ← Back to Dashboard
        </a>
    </div>

    @if($stock->count() > 0)
        <!-- Stock Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Items Card -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg">
                        <i class="bi bi-box-seam"></i>
                    </span>
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Products</span>
                        <h4 class="text-xl font-extrabold text-slate-900 mt-0.5" id="summaryTotalProducts">{{ $stock->count() }}</h4>
                    </div>
                </div>
            </div>

            <!-- Total Quantity Card -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg">
                        <i class="bi bi-layers"></i>
                    </span>
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Quantity</span>
                        <h4 class="text-xl font-extrabold text-slate-900 mt-0.5" id="summaryTotalQty">{{ number_format($stock->sum('quantity')) }}</h4>
                    </div>
                </div>
            </div>

            <!-- Estimated Value Card -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-lg">
                        <i class="bi bi-currency-dollar"></i>
                    </span>
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Est. Retail Value</span>
                        <h4 class="text-xl font-extrabold text-slate-900 mt-0.5" id="summaryTotalValue">Rs. {{ number_format($stock->sum(function($item) { return $item['quantity'] * $item['selling_price']; }), 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Category Filters -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-full sm:w-72 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="stockSearch" oninput="filterStock()" placeholder="Search product name or code..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
            </div>
            
            <div class="w-full sm:flex-1 overflow-x-auto py-1 flex items-center gap-1.5 justify-start sm:justify-end scrollbar-none" id="stockCategoryTabs">
                <button onclick="filterCategory('all')" class="category-tab px-3.5 py-1.5 bg-indigo-600 text-white rounded-full text-[10px] font-bold border border-indigo-600 transition-all cursor-pointer shadow-sm active-tab" data-category="all">
                    All Categories
                </button>
                @foreach($stock->pluck('category')->unique() as $cat)
                    <button onclick="filterCategory('{{ $cat }}')" class="category-tab px-3.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold border border-slate-100 transition-all cursor-pointer" data-category="{{ $cat }}">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Stock List Table -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/75">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Product Item / Reference</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Selling Price</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Wholesale Price</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-36">Loaded Qty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white" id="stockTableBody">
                        @foreach($stock as $item)
                            <tr class="stock-row hover:bg-slate-50/50 transition-colors" 
                                data-name="{{ strtolower($item['product_name']) }}" 
                                data-ref="{{ strtolower($item['reference_number']) }}"
                                data-category="{{ $item['category'] }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900 text-sm">{{ $item['product_name'] }}</div>
                                    <div class="text-[10px] font-mono text-gray-400 mt-0.5">#{{ $item['reference_number'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-50 border border-slate-200 text-slate-600">
                                        {{ $item['category'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-700 font-semibold">
                                    Rs. {{ number_format($item['selling_price'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-slate-500">
                                    Rs. {{ number_format($item['wholesale_price'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($item['quantity'] <= 5)
                                        <span class="px-3 py-1 bg-amber-50 text-amber-700 font-black text-sm rounded-lg border border-amber-100">
                                            {{ number_format($item['quantity']) }} <span class="text-[9px] font-bold uppercase tracking-wider">Low</span>
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 font-black text-sm rounded-lg border border-emerald-100">
                                            {{ number_format($item['quantity']) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden p-6 text-center text-gray-400 py-20 flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-50 text-slate-300 border border-slate-100 rounded-full flex items-center justify-center text-3xl mb-4 shadow-sm">
                <i class="bi bi-box-seam"></i>
            </div>
            <p class="font-extrabold text-gray-700 text-base">No Stock Allocated</p>
            <p class="text-xs mt-1 text-gray-400 max-w-sm">You currently do not have any active inventory in your vehicle. Stock is loaded when a daily delivery route or load request is approved.</p>
        </div>
    @endif
</div>

<script>
    let activeCat = 'all';

    function filterCategory(category) {
        activeCat = category;
        
        // Update tab styles
        document.querySelectorAll('.category-tab').forEach(tab => {
            if (tab.getAttribute('data-category') === category) {
                tab.className = 'category-tab px-3.5 py-1.5 bg-indigo-600 text-white rounded-full text-[10px] font-bold border border-indigo-600 transition-all cursor-pointer shadow-sm';
            } else {
                tab.className = 'category-tab px-3.5 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold border border-slate-100 transition-all cursor-pointer';
            }
        });

        filterStock();
    }

    function filterStock() {
        const query = document.getElementById('stockSearch').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.stock-row');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const ref = row.getAttribute('data-ref');
            const category = row.getAttribute('data-category');

            const matchesQuery = name.includes(query) || ref.includes(query);
            const matchesCategory = activeCat === 'all' || category === activeCat;

            if (matchesQuery && matchesCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection
