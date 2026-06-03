@extends('layouts.app')
@section('title', 'Product Master Database')

@section('content')



    <div class="min-h-screen bg-gray-50 pb-10" x-data="{ viewMode: 'all', layoutMode: 'grid' }">

        {{-- Header --}}
        <div class="bg-white border-b-2 border-gray-200 ">
            <div class="max-w-[1800px] mx-auto px-4 sm:px-8 py-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                            <i class="bi bi-box-seam text-blue-600"></i>
                            Product Master Database
                        </h1>
                        <p class="text-gray-600 mt-1">Central repository for all products across the bakery</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                            <i class="bi bi-upload "></i>
                            Import
                        </button>
                        <button
                            class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                            <i class="bi bi-download"></i>
                            Export
                        </button>
                        <a href="{{ route('productRegistration.index') }}"
                            class="h-12 px-5 bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                            <i class="bi bi-plus text-white text-2xl"></i>
                            Add New Product
                        </a>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                    {{-- Total Card --}}
                    <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-blue-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-gray-600 text-sm">Total Products</span>
                            <i class="bi bi-box-seam text-blue-600 text-2xl"></i>
                        </div>
                        <div class="text-2xl md:text-3xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $stats['active'] }} active</div>
                    </div>
                @php
                    $colors = ['green', 'yellow', 'blue', 'purple', 'gray', 'indigo', 'red', 'pink', 'orange', 'teal'];
                @endphp

                    @foreach($categories as $category)
                        @php
                            $color = $colors[$loop->index % count($colors)];
                            $count = $categoryStats[$category->id] ?? 0;
                        @endphp
                        <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-{{ $color }}-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 text-sm truncate" title="{{ $category->category_name }}">{{ $category->category_name }}</span>
                                <i class="bi bi-star-fill text-{{ $color }}-500"></i>
                            </div>
                            <div class="text-2xl md:text-3xl font-bold text-{{ $color }}-600">{{ $count }}</div>
                            <div class="text-xs text-gray-500 mt-1 truncate" title="{{ $category->category_description }}">{{ $category->category_description ?? 'Products' }}</div>
                        </div>
                    @endforeach
                </div>

                {{-- Filters & Toolbar --}}
                <div class="flex flex-col gap-4 ">
                <div class="flex flex-col xl:flex-row gap-4 items-start xl:items-center w-full">
                    {{-- Search --}}
                    <div class="relative flex-1 w-full xl:max-w-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                        <input type="text" id="searchInput" onkeyup="filterProducts()" placeholder="Search products by name, category, brand..."
                            class="w-full h-12 pl-12 pr-4 border-2 border-gray-200 rounded-xl outline-none focus:border-blue-500 transition-all">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                        {{-- Category Filters --}}
                        <div class="flex items-center gap-2 bg-white border-2 border-gray-200 rounded-xl p-1 overflow-x-auto w-full xl:w-auto scrollbar-hide">
                            <button onclick="setCategoryFilter('all')" id="cat-filter-btn-all"
                                class="px-4 py-2 rounded-lg font-medium transition-all whitespace-nowrap text-sm bg-indigo-500 text-white">
                                All Categories
                            </button>
                            @foreach ($categories as $category)
                                <button onclick="setCategoryFilter('{{ $category->category_name }}')"
                                    id="cat-filter-btn-{{ Str::slug($category->category_name) }}"
                                    class="px-4 py-2 rounded-lg font-medium transition-all whitespace-nowrap text-sm text-gray-600 hover:bg-gray-100">
                                    {{ $category->category_name }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Item Type Filters --}}
                        <div class="flex items-center gap-2 bg-white border-2 border-gray-200 rounded-xl p-1 overflow-x-auto w-full xl:w-auto scrollbar-hide">
                            <button onclick="setItemTypeFilter('all')" id="itemtype-filter-btn-all"
                                class="px-4 py-2 rounded-lg font-medium transition-all whitespace-nowrap text-sm bg-blue-500 text-white">
                                All Item Types
                            </button>
                            <button onclick="setItemTypeFilter(1)" id="itemtype-filter-btn-1"
                                class="px-4 py-2 rounded-lg font-medium transition-all whitespace-nowrap text-sm text-gray-600 hover:bg-gray-100">
                                Selling products
                            </button>
                            <button onclick="setItemTypeFilter(2)" id="itemtype-filter-btn-2"
                                class="px-4 py-2 rounded-lg font-medium transition-all whitespace-nowrap text-sm text-gray-600 hover:bg-gray-100">
                                Product Containers
                            </button>
                        </div>
                    </div>

                    {{-- Layout Switcher --}}
                    <div class="flex items-center gap-2 bg-white border-2 border-gray-200 rounded-xl p-1 ml-auto xl:ml-0">
                        <button onclick="setLayout('grid')" id="layout-grid" class="p-2 rounded-lg transition-all bg-blue-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="7" height="7" x="3" y="3" rx="1" />
                                <rect width="7" height="7" x="14" y="3" rx="1" />
                                <rect width="7" height="7" x="14" y="14" rx="1" />
                                <rect width="7" height="7" x="3" y="14" rx="1" />
                            </svg>
                        </button>
                        <button onclick="setLayout('list')" id="layout-list" class="p-2 rounded-lg transition-all text-gray-600 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="8" x2="21" y1="6" y2="6" />
                                <line x1="8" x2="21" y1="12" y2="12" />
                                <line x1="8" x2="21" y1="18" y2="18" />
                                <line x1="3" x2="3.01" y1="6" y2="6" />
                                <line x1="3" x2="3.01" y1="12" y2="12" />
                                <line x1="3" x2="3.01" y1="18" y2="18" />
                            </svg>
                        </button>
                    </div>
                </div>
                
            </div>
        </div>

        {{-- Product List Container --}}
        <div class="max-w-[1800px] mx-auto px-4 sm:px-8 py-6">
            <div class="mb-4 flex items-center justify-between">
                <p class="text-gray-600">Showing <span class="font-bold text-gray-900" id="resultCount">0</span> products</p>
            </div>

            {{-- Grid/List will be injected here via JS --}}
            <div id="productsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                {{-- Content populated by JS --}}
            </div>

            {{-- Empty State --}}
            <div id="emptyState" class="hidden bg-white rounded-xl shadow-sm border-2 border-gray-200 p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m7.5 4.27 9 5.15" />
                    <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                    <path d="m3.3 7 8.7 5 8.7-5" />
                    <path d="M12 22V12" />
                </svg>
                <h3 class="font-bold text-gray-900 mb-2">No products found</h3>
                <p class="text-gray-600 mb-4">Try adjusting your search or filters</p>
                <button onclick="clearFilters()" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-medium transition-all">
                    Clear Filters
                </button>
            </div>
        </div>




        {{-- Product Type Update Modal --}}
        {{-- Product Type Update Modal --}}
        <div id="productTypesModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay, show/hide based on modal state. -->
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeTypeModal()"></div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel, show/hide based on modal state. -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Update Product Details & Prices
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <input type="hidden" id="editItemTypeId">
                                    
                                    <div>
                                        <label for="item_type" class="block text-sm font-medium text-gray-700">Item Type</label>
                                        <select name="item_type" id="item_type"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md border py-2 px-3 bg-white">
                                            <option value="1">Selling product</option>
                                            <option value="2">Product Containers</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="selling_price" class="block text-sm font-medium text-gray-700">Selling Price</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">Rs.</span>
                                            </div>
                                            <input type="number" step="0.01" name="selling_price" id="selling_price" oninput="calculateModalPrices()"
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 pr-12 sm:text-sm border-gray-300 rounded-md border py-2 px-3" placeholder="0.00">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="distributor_percentage" class="block text-sm font-medium text-gray-700">Distributor Percentage (%)</label>
                                        <input type="number" step="0.01" name="distributor_percentage" id="distributor_percentage" oninput="calculateModalPrices()"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md border py-2 px-3" placeholder="0.00">
                                        <p class="mt-1 text-xs text-indigo-600 font-medium">Distributor Price: Rs. <span id="distributor_price_display">0.00</span></p>
                                    </div>

                                    <div>
                                        <label for="wholesale_percentage" class="block text-sm font-medium text-gray-700">Wholesale Percentage (%)</label>
                                        <input type="number" step="0.01" name="wholesale_percentage" id="wholesale_percentage" oninput="calculateModalPrices()"
                                            class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md border py-2 px-3" placeholder="0.00">
                                        <p class="mt-1 text-xs text-purple-600 font-medium">Wholesale Price: Rs. <span id="wholesale_price_display">0.00</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" onclick="saveProductTypes()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Changes
                        </button>
                        <button type="button" onclick="closeTypeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Toast Notification --}}
        <div id="toast" class="fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-xl shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            <span id="toastMessage">Action successful</span>
        </div>

    </div>

    <script>
        // Initial Data passed from PHP
        const products = @json($products);
        const productTypes = @json($productTypes);

        // Map types to IDs for lookup
        const typesMap = {};
        const colors = ['green', 'yellow', 'blue', 'purple', 'gray', 'indigo', 'red', 'pink', 'orange', 'teal'];
        const iconPaths = [
            '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3"/>', // Utensils
            '<path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"/><path d="M8.5 8.5v.01"/><path d="M16 15.5v.01"/><path d="M12 12v.01"/><path d="M11 17v.01"/><path d="M7 14v.01"/>', // Cookie
            '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>', // ShoppingBag
            '<path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>', // Box
            '<rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/>' // Archive
        ];

        productTypes.forEach((type, index) => {
            const color = colors[index % colors.length];
            const iconPath = iconPaths[index % iconPaths.length];
            typesMap[type.id] = {
                name: type.product_type_name,
                color: `bg-${color}-100 text-${color}-700 border-${color}-300`,
                iconBg: `bg-${color}-50`,
                icon: iconPath
            };
        });

        // Default icon (used as fallback)
        const defaultIcon = iconPaths[4];

        // State
        let state = {
            viewMode: 'all',
            categoryFilter: 'all',
            itemTypeFilter: 'all',
            layoutMode: 'grid',
            searchQuery: ''
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            renderProducts();
        });

        // Filtering Logic
        function getFilteredProducts() {
            return products.filter(p => {
                // Check if any of the product's types match the selected view mode
                const matchesType = state.viewMode === 'all' || (p.allTypeIds && p.allTypeIds.includes(parseInt(state.viewMode))) || p.type == state.viewMode;
                const matchesCategory = state.categoryFilter === 'all' || p.productCategory === state.categoryFilter;
                const matchesItemType = state.itemTypeFilter === 'all' || p.itemType == state.itemTypeFilter;
                const matchesSearch = p.name.toLowerCase().includes(state.searchQuery.toLowerCase()) ||
                    p.category.toLowerCase().includes(state.searchQuery.toLowerCase()) ||
                    p.productCategory.toLowerCase().includes(state.searchQuery.toLowerCase()) ||
                    (p.brand && p.brand.toLowerCase().includes(state.searchQuery.toLowerCase()));
                return matchesType && matchesCategory && matchesItemType && matchesSearch;
            });
        }

        // Render Logic
        function renderProducts() {
            const filtered = getFilteredProducts();
            const container = document.getElementById('productsContainer');
            const emptyState = document.getElementById('emptyState');
            const countSpan = document.getElementById('resultCount');

            countSpan.textContent = filtered.length;

            if (filtered.length === 0) {
                container.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            container.classList.remove('hidden');
            emptyState.classList.add('hidden');

            // Handle Layout Classes
            if (state.layoutMode === 'grid') {
                container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4';
            } else {
                container.className = 'flex flex-col gap-2';
            }

            container.innerHTML = filtered.map(p => {
                // Use the first type for icon and color, but show all types
                const typeInfo = typesMap[p.type] || {
                    name: 'Unknown',
                    color: 'bg-gray-100 text-gray-700 border-gray-300',
                    iconBg: 'bg-gray-50',
                    icon: defaultIcon
                };

                const iconSvg = typeInfo.icon;
                const colors = typeInfo.color;
                const iconBg = typeInfo.iconBg;

                // Get all type information for display
                const allTypeBadges = p.allTypeNames && p.allTypeNames.length > 0 ?
                    p.allTypeNames.map(typeName => {
                        const type = productTypes.find(t => t.product_type_name === typeName);
                        if (type) {
                            const typeInfo = typesMap[type.id] || {
                                name: typeName,
                                color: 'bg-gray-100 text-gray-700 border-gray-300',
                                iconBg: 'bg-gray-50',
                                icon: defaultIcon
                            };
                            return `<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold ${typeInfo.color}">${typeName}</span>`;
                        }
                        return `<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-gray-100 text-gray-700 border-gray-300">${typeName}</span>`;
                    }).join(' ') :
                    `<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-gray-100 text-gray-700 border-gray-300">General</span>`;

                if (state.layoutMode === 'grid') {
                    return `
                    <div class="bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-blue-300 hover:shadow-md transition-all overflow-hidden group">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div class="p-3 rounded-xl ${iconBg} ${colors.split(' ')[1]}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${iconSvg}</svg>
                                </div>
                                ${p.brand ? `<div class="flex items-center gap-2 mb-3 text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l5 5a2 2 0 0 0 2.828 0l7.172-7.172a2 2 0 0 0 0-2.828l-5-5z"/><circle cx="7.5" cy="7.5" r=".5"/></svg>
                                ${p.brand}
                            </div>` : ''}
                            </div>
                            <h3 class="font-bold text-gray-900 mb-1">${p.name}</h3>
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="text-sm font-medium text-blue-600">${p.productCategory}</span>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md ${p.itemType == 2 ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-teal-100 text-teal-800 border border-teal-200'}">
                                    ${p.itemType == 2 ? 'Container' : 'Selling Product'}
                                </span>
                            </div>
                            <p class="hidden text-sm text-gray-600 mb-3">${p.unit}</p>
                            
                            <div class="space-y-1 mb-4">
                                <div class="flex gap-2 items-center text-sm">
                                    <span class="text-gray-500">Selling Price:</span>
                                    <span class="font-bold text-gray-900 border-b-2 border-blue-100">Rs. ${Math.round(parseFloat(p.sellingPrice || 0)).toLocaleString()}</span>
                                </div>
                                <div class="flex gap-2 items-center text-xs pt-1">
                                    <span class="text-gray-500">Distributor (${p.distributorPercentage || 0}%):</span>
                                    <span class="text-indigo-600 font-bold">Rs. ${(() => {
                                        const sellPrice = parseFloat(p.sellingPrice || 0);
                                        const wPercent = parseFloat(p.wholesalePercentage || 0);
                                        const dPercent = parseFloat(p.distributorPercentage || 0);
                                        const wPrice = Math.round(sellPrice * (1 - wPercent / 100));
                                        const dPrice = Math.round(wPrice * (1 - dPercent / 100));
                                        return dPrice.toLocaleString();
                                    })()}</span>
                                </div>
                                <div class="flex gap-2 items-center text-xs">
                                    <span class="text-gray-500">Wholesale (${p.wholesalePercentage || 0}%):</span>
                                    <span class="text-purple-600 font-bold">Rs. ${(() => {
                                        const sellPrice = parseFloat(p.sellingPrice || 0);
                                        const wPercent = parseFloat(p.wholesalePercentage || 0);
                                        const wPrice = Math.round(sellPrice * (1 - wPercent / 100));
                                        return wPrice.toLocaleString();
                                    })()}</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-1 mb-3 hidden">
                                ${allTypeBadges}
                            </div>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full ${p.isActive ? 'bg-green-500' : 'bg-red-500'}"></div>
                                    <span class="text-sm text-gray-600">${p.isActive ? 'Active' : 'Inactive'}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button onclick="openTypeModal('${p.id}')" class="p-2 hover:bg-blue-50  text-blue-600 rounded-lg transition-all"><i class="bi bi-pen"></i></button>
                                    <button onclick="deleteProduct('${p.id}')" class="hidden p-2 hover:bg-red-100 text-red-600 rounded-lg transition-all"><i class="bi bi-archive"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                } else {
                    // List Layout
                    return `
                    <div class="bg-white rounded-xl shadow-sm border-b border-gray-200 hover:bg-gray-50 transition-all p-4 flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="p-2 rounded-lg ${iconBg} ${colors.split(' ')[1]}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${iconSvg}</svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">${p.name}</div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-sm text-blue-600 font-medium">${p.productCategory}</span>
                                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded ${p.itemType == 2 ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-teal-100 text-teal-800 border border-teal-200'}">
                                        ${p.itemType == 2 ? 'Container' : 'Selling Product'}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500">${p.id}</div>
                            </div>
                        </div>
                        <div class="hidden md:flex flex-col items-end gap-1 w-48">
                            <div class="text-sm font-bold text-gray-900">Rs. ${Math.round(parseFloat(p.sellingPrice || 0)).toLocaleString()}</div>
                            <div class="flex gap-2">
                                <span class="text-[10px] bg-indigo-50 text-indigo-700 px-1.5 py-0.5 rounded font-bold">D: Rs. ${(() => {
                                    const sellPrice = parseFloat(p.sellingPrice || 0);
                                    const wPercent = parseFloat(p.wholesalePercentage || 0);
                                    const dPercent = parseFloat(p.distributorPercentage || 0);
                                    const wPrice = Math.round(sellPrice * (1 - wPercent / 100));
                                    const dPrice = Math.round(wPrice * (1 - dPercent / 100));
                                    return dPrice.toLocaleString();
                                })()}</span>
                                <span class="text-[10px] bg-purple-50 text-purple-700 px-1.5 py-0.5 rounded font-bold">W: Rs. ${(() => {
                                    const sellPrice = parseFloat(p.sellingPrice || 0);
                                    const wPercent = parseFloat(p.wholesalePercentage || 0);
                                    const wPrice = Math.round(sellPrice * (1 - wPercent / 100));
                                    return wPrice.toLocaleString();
                                })()}</span>
                            </div>
                        </div>
                        <div class="hidden md:block text-gray-700 text-sm">${p.unit}</div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full ${p.isActive ? 'bg-green-500' : 'bg-red-500'}"></div>
                                <span class="text-sm text-gray-600 w-16">${p.isActive ? 'Active' : 'Inactive'}</span>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="openTypeModal('${p.id}')" class="p-2 hover:bg-blue-50 text-blue-600 rounded-lg"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg></button>
                                <button onclick="deleteProduct('${p.id}')" class="p-2 hover:bg-red-50 text-red-600 rounded-lg"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button>
                            </div>
                        </div>
                    </div>`;
                }
            }).join('');
        }

        // Actions
        function setFilter(mode) {
            state.viewMode = mode;
            // Update Filter Button Styles
            document.querySelectorAll('[id^="filter-btn-"]').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('text-gray-600', 'hover:bg-gray-100');
            });

            const activeBtn = document.getElementById(`filter-btn-${mode}`);
            if(activeBtn) {
                activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-100');
                activeBtn.classList.add('bg-blue-500', 'text-white');
            }

            renderProducts();
        }

        function setCategoryFilter(category) {
            state.categoryFilter = category;
            // Update Filter Button Styles
            document.querySelectorAll('[id^="cat-filter-btn-"]').forEach(btn => {
                btn.classList.remove('bg-indigo-500', 'text-white');
                btn.classList.add('text-gray-600', 'hover:bg-gray-100');
            });

            const slug = category === 'all' ? 'all' : category.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            const activeBtn = document.getElementById(`cat-filter-btn-${slug}`);
            if(activeBtn) {
                activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-100');
                activeBtn.classList.add('bg-indigo-500', 'text-white');
            }

            renderProducts();
        }

        function setLayout(mode) {
            state.layoutMode = mode;
            document.getElementById('layout-grid').classList.toggle('bg-blue-500', mode === 'grid');
            document.getElementById('layout-grid').classList.toggle('text-white', mode === 'grid');
            document.getElementById('layout-grid').classList.toggle('text-gray-600', mode !== 'grid');

            document.getElementById('layout-list').classList.toggle('bg-blue-500', mode === 'list');
            document.getElementById('layout-list').classList.toggle('text-white', mode === 'list');
            document.getElementById('layout-list').classList.toggle('text-gray-600', mode !== 'list');

            renderProducts();
        }

        function filterProducts() {
            state.searchQuery = document.getElementById('searchInput').value;
            renderProducts();
        }

        function setItemTypeFilter(type) {
            state.itemTypeFilter = type;
            // Update Filter Button Styles
            document.querySelectorAll('[id^="itemtype-filter-btn-"]').forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('text-gray-600', 'hover:bg-gray-100');
            });

            const activeBtn = document.getElementById(`itemtype-filter-btn-${type}`);
            if(activeBtn) {
                activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-100');
                activeBtn.classList.add('bg-blue-500', 'text-white');
            }

            renderProducts();
        }

        function clearFilters() {
            state.searchQuery = '';
            state.viewMode = 'all';
            state.categoryFilter = 'all';
            state.itemTypeFilter = 'all';
            document.getElementById('searchInput').value = '';
            setFilter('all');
            setCategoryFilter('all');
            setItemTypeFilter('all');
        }

        function deleteProduct(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action will archive the product!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, archive it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to update status
                    fetch('{{ route('product.status.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Archived!',
                                'Product has been archived.',
                                'success'
                            ).then(() => {
                                window.location.reload(); 
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Something went wrong.',
                            'error'
                        );
                    });
                }
            })
        }

        // Modal Logic
        function openModal(type) {
            document.getElementById('modalTitle').textContent = type === 'add' ? 'Add Product' : 'Edit Product';
            document.getElementById('productModal').classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Product Type Update Logic
        function openTypeModal(id) {
            const product = products.find(p => p.id == id);
            if (!product) return;

            document.getElementById('editItemTypeId').value = id;
            document.getElementById('item_type').value = product.itemType || 1;
            document.getElementById('selling_price').value = product.sellingPrice || 0;
            document.getElementById('distributor_percentage').value = product.distributorPercentage || 0;
            document.getElementById('wholesale_percentage').value = product.wholesalePercentage || 0;
            
            calculateModalPrices();

            document.getElementById('productTypesModal').classList.remove('hidden');
        }

        function calculateModalPrices() {
            const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
            const distributorPercent = parseFloat(document.getElementById('distributor_percentage').value) || 0;
            const wholesalePercent = parseFloat(document.getElementById('wholesale_percentage').value) || 0;

            const wholesalePrice = Math.round(sellingPrice * (1 - wholesalePercent / 100));
            const distributorPrice = Math.round(wholesalePrice * (1 - distributorPercent / 100));

            document.getElementById('distributor_price_display').textContent = distributorPrice.toLocaleString();
            document.getElementById('wholesale_price_display').textContent = wholesalePrice.toLocaleString();
        }

        function closeTypeModal() {
            document.getElementById('productTypesModal').classList.add('hidden');
        }

        function saveProductTypes() {
            const itemId = document.getElementById('editItemTypeId').value;
            const itemType = document.getElementById('item_type').value;
            const sellingPrice = document.getElementById('selling_price').value;
            const distributorPercentage = document.getElementById('distributor_percentage').value;
            const wholesalePercentage = document.getElementById('wholesale_percentage').value;

            fetch('{{ route('product.item.types.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    item_id: itemId,
                    item_type: itemType,
                    selling_price: sellingPrice,
                    distributor_percentage: distributorPercentage,
                    wholesale_percentage: wholesalePercentage
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Product prices updated successfully');
                    closeTypeModal();
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    Swal.fire('Error', data.message || 'Failed to update prices', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Something went wrong', 'error');
            });
        }



        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toastMessage').textContent = message;
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }
    </script>

@endsection
