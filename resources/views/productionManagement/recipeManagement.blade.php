@extends('layouts.app')
@section('title', 'Recipe Management')

@section('content')


    <div class="min-h-screen bg-[#F5F5F7]">

        {{-- Header Section --}}
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#F59E0B] to-[#D97706] rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Recipe Management</h1>
                            <p class="text-gray-500 text-xs sm:text-sm">Create and manage recipes with multi-level bill of
                                materials</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <span
                        class="inline-flex items-center px-3 sm:px-4 py-1 rounded-full text-xs sm:text-sm font-medium bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-sm whitespace-nowrap">
                        Coming Soon
                    </span>
                    <button onclick="openCreateModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-[#F59E0B] to-[#D97706] text-white rounded-lg hover:from-[#D97706] hover:to-[#B45309] transition-all text-xs sm:text-sm font-medium shadow-sm whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="hidden sm:inline">Create Recipe</span>
                        <span class="sm:hidden">Create</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6 max-w-[1800px] mx-auto">

            {{-- Top Statistics Bar --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                @foreach($stats as $stat)
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="text-gray-500 text-sm mb-2">{{ $stat['label'] }}</div>
                                <div class="text-3xl font-bold text-gray-900">{{ $stat['value'] }}</div>
                            </div>
                            <div class="w-12 h-12 rounded-xl {{ $stat['bg'] }} flex items-center justify-center">
                                @if($stat['icon'] == 'book-open') <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                @elseif($stat['icon'] == 'check-circle') <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif($stat['icon'] == 'edit') <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                @elseif($stat['icon'] == 'calculator') <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Main Content Area --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- Left Panel - Recipe List --}}
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        {{-- Filter & Search Section --}}
                        <div class="p-6 border-b border-gray-100 space-y-4">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" placeholder="Search recipes..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <select
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F59E0B] text-sm"
                                    id="categoryFilter">
                                    <option value="all">Category</option>
                                    <option value="Bread">Bread</option>
                                    <option value="Pastry">Pastry</option>
                                    <option value="Cake">Cake</option>
                                    <option value="Waste Processing">♻️ Waste Processing</option>
                                </select>
                                <select
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F59E0B] text-sm">
                                    <option value="all">Status</option>
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                </select>
                                <select
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F59E0B] text-sm">
                                    <option value="all">Cost Range</option>
                                    <option value="low">Low</option>
                                    <option value="high">High</option>
                                </select>
                                <select
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F59E0B] text-sm">
                                    <option value="name">Sort by: Name</option>
                                    <option value="cost">Sort by: Cost</option>
                                </select>
                            </div>
                        </div>

                        {{-- Recipe Cards Grid --}}
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($recipes as $recipe)
                                    <div onclick="viewRecipe({{ json_encode($recipe) }})"
                                        class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-lg hover:scale-[1.02] transition-all cursor-pointer group">
                                        {{-- Recipe Image --}}
                                        <div
                                            class="aspect-video rounded-lg overflow-hidden mb-3 bg-gray-100 flex items-center justify-center">
                                            @if($recipe->image_paths && is_array($recipe->image_paths) && count($recipe->image_paths) > 0 && !empty($recipe->image_paths[0]))
                                                {{-- Debug: {{ $recipe->image_paths[0] }} --}}
                                                <img src="/storage/{{ $recipe->image_paths[0] }}" alt="{{ $recipe->name }}"
                                                    class="w-full h-full object-cover"
                                                    onerror="console.log('Image failed to load:', this.src); this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                <svg class="w-8 h-8 text-gray-300 hidden" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            @else
                                                {{-- No image: {{ $recipe->image_paths }} --}}
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            @endif
                                        </div>

                                        <div class="space-y-3">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="flex-1">
                                                    <h3
                                                        class="text-gray-900 font-medium group-hover:text-[#F59E0B] transition-colors">
                                                        {{ $recipe->name }}
                                                    </h3>
                                                    @if($recipe->is_waste)
                                                        <div class="flex items-center gap-1 mt-1">
                                                            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                                </path>
                                                            </svg>
                                                            <span class="text-xs text-green-600 font-medium">Waste Recovery</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $recipe->is_waste ? 'bg-green-100 text-green-700' : ($recipe->category == 'Pastry' ? 'bg-pink-100 text-pink-700' : ($recipe->category == 'Bread' ? 'bg-amber-100 text-amber-700' : ($recipe->category == 'Cake' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700'))) }}">
                                                    {{ $recipe->category }}
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-3 gap-2 text-xs border-t border-gray-100 pt-2">
                                                <div class="flex items-center gap-1 text-gray-600">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                        </path>
                                                    </svg>
                                                    <span>{{ $recipe->yield }}</span>
                                                </div>
                                                <div class="flex items-center gap-1 text-gray-600">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>{{ $recipe->prep_time }}</span>
                                                </div>
                                                <div class="flex items-center gap-1 text-gray-600">
                                                    <span>Rs. {{ number_format($recipe->cost, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 flex justify-center">
                                <button
                                    class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    Load More Recipes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Panel - Quick Info Sidebar --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Waste Recovery & Byproducts (sidebar placeholder - kept hidden) --}}
                    <div id="waste_recovery_section_sidebar" class="hidden space-y-4" style="display:none;">
                        <div class="rounded-2xl border-2 border-emerald-200 bg-emerald-50 p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                <div>
                                    <h4 class="text-emerald-900 font-semibold text-sm">Waste Recovery & Byproducts
                                        (Optional)</h4>
                                    <p class="text-xs text-emerald-700">Track recoverable waste and calculate NRV cost
                                        savings</p>
                                </div>
                            </div>

                            <div class="rounded-xl border border-emerald-200 bg-white p-3 space-y-3">
                                <div class="text-xs font-semibold text-emerald-800 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Byproducts
                                </div>
                                <div class="grid grid-cols-12 gap-2 text-xs text-gray-500">
                                    <div class="col-span-6">Byproduct</div>
                                    <div class="col-span-3 text-center">Quantity</div>
                                    <div class="col-span-3 text-center">Unit</div>
                                </div>
                                <div class="grid grid-cols-12 gap-2 items-center">
                                    <div class="col-span-6 relative">
                                        <input type="text" id="nrv-product-search-sidebar"
                                            class="w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B]"
                                            placeholder="Search byproduct...">
                                        <input type="hidden" id="nrv-product-id-sidebar"
                                            name="wastage_recovery_by_products[0][product_item_id]" value="">
                                        <div id="nrv-product-dropdown-sidebar"
                                            class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto hidden">
                                            <div id="nrv-product-results-sidebar" class="search-results"></div>
                                            <div id="nrv-product-no-results-sidebar" class="hidden px-4 py-2 text-gray-500">
                                                No products found</div>
                                        </div>
                                        <p class="text-[11px] text-gray-500 mt-1">Edge trimmings, crumbs, etc.</p>
                                    </div>
                                    <div class="col-span-3">
                                        <input type="number" step="0.01" name="wastage_recovery_by_products[0][quantity]"
                                            class="w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B]"
                                            placeholder="0">
                                    </div>
                                    <div class="col-span-3">
                                        <select name="wastage_recovery_by_products[0][unit]"
                                            class="w-full border border-gray-300 rounded-md py-1.5 px-2 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B] bg-white">
                                            <option>kg</option>
                                            <option>g</option>
                                            <option>pcs</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 space-y-3">
                                <div class="text-xs font-semibold text-amber-800 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v12m6-6H6"></path>
                                    </svg>
                                    NRV Cost Calculation
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <div>
                                        <label class="text-xs text-gray-700">Market Value (Rs)</label>
                                        <input type="number" step="0.01" id="nrv-market-sidebar"
                                            name="wastage_recovery_by_products_nrv_costs[market_value]"
                                            class="w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B]"
                                            placeholder="0.00">
                                        <p class="text-[11px] text-gray-500 mt-1">What it sells for</p>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-700">Processing Cost (Rs)</label>
                                        <input type="number" step="0.01" id="nrv-processing-sidebar"
                                            name="wastage_recovery_by_products_nrv_costs[processing_cost]"
                                            class="w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B]"
                                            placeholder="0.00">
                                        <p class="text-[11px] text-gray-500 mt-1">Cost to convert</p>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-700">Net Realizable Value</label>
                                        <div
                                            class="border border-emerald-300 bg-white rounded-md py-1.5 px-3 text-sm font-semibold text-emerald-700 flex items-center justify-between">
                                            <span id="nrv-net-label-sidebar">Rs. 0.00</span>
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                            </svg>
                                        </div>
                                        <p class="text-[11px] text-emerald-700 mt-1">Reduces recipe cost</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Chart Widget Placeholder (Pie Chart) --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-gray-900 font-semibold mb-4">Recipe Categories</h3>
                        <div class="relative h-64 w-full">
                            <canvas id="recipeCategoriesChart"></canvas>
                        </div>
                        <div class="w-full mt-4 space-y-2">
                            <div class="flex items-center justify-between text-sm"><span
                                    class="flex items-center gap-2"><span
                                        class="w-3 h-3 rounded-full bg-[#F59E0B]"></span>Bread</span><span
                                    class="text-gray-900">35%</span></div>
                            <div class="flex items-center justify-between text-sm"><span
                                    class="flex items-center gap-2"><span
                                        class="w-3 h-3 rounded-full bg-[#EC4899]"></span>Pastry</span><span
                                    class="text-gray-900">25%</span></div>
                            <div class="flex items-center justify-between text-sm"><span
                                    class="flex items-center gap-2"><span
                                        class="w-3 h-3 rounded-full bg-[#8B5CF6]"></span>Cakes</span><span
                                    class="text-gray-900">30%</span></div>
                        </div>
                    </div>

                    {{-- Cost Distribution Widget --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-gray-900 font-semibold mb-4">Cost Distribution</h3>
                        <div class="relative h-48 w-full">
                            <canvas id="costDistributionChart"></canvas>
                        </div>
                    </div>

                    {{-- Waste Processing Widget --}}
                    <div
                        class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 shadow-sm border-2 border-green-200">
                        <div class="flex items-center gap-2 mb-4">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-bold">Waste Processing</h3>
                                <p class="text-xs text-green-700 font-medium">Sustainability Recipes</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-white rounded-xl p-3 border border-green-200 shadow-sm">
                                <div class="text-2xl font-bold text-green-600 mb-1">{{ $wasteStats['total_recipes'] }}</div>
                                <div class="text-xs text-gray-600">Waste Recipes</div>
                            </div>
                            <div class="bg-white rounded-xl p-3 border border-green-200 shadow-sm">
                                <div class="text-2xl font-bold text-green-600 mb-1">{{ $wasteStats['total_byproducts'] }}
                                </div>
                                <div class="text-xs text-gray-600">Total Byproducts</div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl p-3 border border-green-200 shadow-sm mb-4">
                            <div class="text-xs text-gray-500 mb-1">Average Cost</div>
                            <div class="text-xl font-bold text-green-600">Rs.
                                {{ number_format($wasteStats['avg_cost'], 2) }}
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-green-200">
                            <div class="flex items-center gap-2 text-xs text-green-800 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                                <span>Reducing waste by 15% monthly</span>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Activity --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="text-gray-900 font-semibold mb-4">Recent Updates</h3>
                        <div class="space-y-4">
                            @foreach($recentActivity as $activity)
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg {{ $activity['color'] }} flex items-center justify-center flex-shrink-0">
                                        {{-- Icon based on action type --}}
                                        @if($activity['icon'] == 'edit')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        @elseif($activity['icon'] == 'plus')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        @elseif($activity['icon'] == 'archive')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-900">
                                            {{ $activity['action'] }}: <span
                                                class="font-medium">{{ $activity['target'] }}</span>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $activity['time'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="w-full mt-4 text-sm font-medium text-[#F59E0B] hover:text-[#D97706] hover:underline">
                            View All Activity
                        </button>
                    </div>

                </div>
            </div>

            {{-- Featured Recipes Carousel Section --}}
            <div class="mt-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-gray-900 font-bold">Most Used Recipes</h2>
                        <div class="flex items-center gap-2">
                            <button class="p-1 border border-gray-200 rounded hover:bg-gray-50"><svg
                                    class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg></button>
                            <button class="p-1 border border-gray-200 rounded hover:bg-gray-50"><svg
                                    class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg></button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($featuredRecipes as $feat)
                                <div class="bg-gray-50 rounded-xl p-4 hover:shadow-md transition-shadow cursor-pointer group"
                                    onclick="viewRecipe({{ json_encode($feat) }})">
                                    {{-- Featured Recipe Image --}}
                                    <div
                                        class="aspect-square rounded-lg overflow-hidden mb-3 bg-gray-100 flex items-center justify-center">
                                        @if($feat['image_paths'] && is_array($feat['image_paths']) && count($feat['image_paths']) > 0 && !empty($feat['image_paths'][0]))
                                            <img src="/storage/{{ $feat['image_paths'][0] }}" alt="{{ $feat['name'] }}"
                                                class="w-full h-full object-cover"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <svg class="w-8 h-8 text-gray-300 hidden" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        @else
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                    <h4 class="text-gray-900 font-medium mb-2">{{ $feat['name'] }}</h4>
                                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                        <svg class="w-4 h-4 {{ $feat['trend'] == 'up' ? 'text-green-500' : 'text-red-500' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        <span>Used {{ $feat['usage'] }} times</span>
                                    </div>
                                    <button
                                        class="w-full py-1.5 text-sm font-medium border border-gray-300 rounded bg-white text-gray-700 hover:bg-[#F59E0B] hover:text-white hover:border-[#F59E0B] transition-colors">
                                        Use Recipe
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Create Recipe Modal --}}
        {{-- Requires Alpine.js (x-data) for interactivity. If you don't use Alpine, remove x- logic and use vanilla JS --}}
        <div id="createRecipeModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity duration-300 ease-out modal-backdrop"
                onclick="closeCreateModal()"></div>

            {{-- Modal Panel --}}
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div
                        class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all duration-300 ease-out sm:my-8 sm:align-middle sm:max-w-4xl w-full modal-content scale-95 opacity-0">

                        {{-- Header --}}
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-0">
                            <div class="flex items-center gap-3 mb-5">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-[#F59E0B] to-[#D97706] rounded-lg flex items-center justify-center flex-shrink-0">
                                    {{-- BookOpen Icon --}}
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Create New
                                        Recipe</h2>
                                    <p class="text-sm text-gray-500">Fill in the details below to create a new recipe with
                                        multi-level bill of materials</p>
                                </div>
                            </div>
                        </div>

                        {{-- Scrollable Content Area --}}
                        <div class="px-4 sm:px-6 pb-6 max-h-[80vh] overflow-y-auto">

                            {{-- Product Selector --}}
                            <div
                                class="border-2 rounded-xl p-5 mt-2 transition-colors duration-300 bg-gradient-to-r from-amber-50 to-orange-50 border-amber-200">

                                <div class="flex items-start gap-3 mb-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold mb-1 text-amber-900">Select Product</h3>
                                        <p class="text-sm text-amber-700">
                                            Choose which product this recipe is for.

                                        </p>
                                    </div>
                                </div>

                                {{-- Product Search Input --}}
                                <div class="relative">
                                    <input type="text" id="main_product_search"
                                        placeholder="🔍 Search and select a product..."
                                        class="w-full h-14 border-2 rounded-lg px-4 bg-white focus:outline-none focus:ring-2 transition-colors border-amber-300 focus:ring-amber-400 hover:border-amber-400">
                                    <input type="hidden" id="selected_product_id" name="product_item_id">
                                    <input type="hidden" id="selected_category" name="category" value="bread">
                                    <input type="hidden" id="selected_is_waste" name="is_waste" value="0">

                                    {{-- Search Results Dropdown --}}
                                    <div id="main_product_dropdown"
                                        class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto hidden">
                                        <div id="main_product_results" class="search-results"></div>
                                        <div id="main_product_no_results" class="hidden px-4 py-2 text-gray-500">No products
                                            found</div>
                                    </div>
                                </div>

                                {{-- Product Selected Badge --}}
                                <div id="selected_product_badge"
                                    class="mt-3 rounded-lg p-3 flex items-center gap-3 transition-colors duration-300 hidden bg-white border border-amber-200">
                                    <div id="waste_icon" class="hidden">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                    </div>
                                    <div id="standard_icon">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">
                                            <span id="selected_product_name"></span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span id="selected_product_info"></span>
                                            <span id="waste_recovery_badge" class="ml-2 hidden">🌱 Waste Recovery
                                                Product</span>
                                        </div>
                                    </div>
                                    <button id="clear_product_selection" class="text-gray-400 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Waste Processing Banner (Conditional) --}}
                            <div id="waste_processing_banner"
                                class="hidden bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg my-4">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl mb-2 flex items-center gap-2 font-semibold">
                                            Waste Processing Recipe
                                        </h3>
                                        <p class="text-green-50 text-sm mb-3">
                                            You are creating a recipe that transforms waste/byproducts into valuable
                                            products. This supports our Three-Stage Waste Recovery System.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Main Grid --}}
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mt-4">

                                {{-- Left Column: Basic Info --}}
                                <div class="space-y-6">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <h3 class="text-blue-900 mb-1 flex items-center gap-2 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            Basic Information
                                        </h3>
                                        <p class="text-xs text-blue-700">Enter the fundamental details about your recipe</p>
                                    </div>

                                    {{-- Image Upload --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Recipe Image</label>
                                        <div id="image-upload-area"
                                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#F59E0B] transition-colors cursor-pointer bg-gray-50 hover:bg-white relative">
                                            <input type="file" id="recipe-image" name="image" accept="image/*"
                                                class="hidden">
                                            <div id="upload-placeholder"
                                                class="flex flex-col items-center gap-2 pointer-events-none">
                                                <div
                                                    class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-gray-700">Click to upload or drag and drop</p>
                                                <p class="text-xs text-gray-500">PNG, JPG up to 5MB</p>
                                            </div>
                                            <div id="image-preview" class="hidden flex flex-col items-center gap-2">
                                                <img id="preview-img" class="w-20 h-20 object-cover rounded-lg border"
                                                    src="" alt="Preview">
                                                <p class="text-sm text-gray-700" id="file-name"></p>
                                                <button type="button" id="remove-image"
                                                    class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Recipe Name --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Recipe Name *</label>
                                        <input type="text"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#F59E0B] focus:border-[#F59E0B] sm:text-sm transition-all duration-200"
                                            placeholder="e.g. Classic Chocolate Chip">
                                    </div>

                                    {{-- Category Tags Selection --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>

                                        {{-- Selected Tags Display --}}
                                        <div id="selected-categories"
                                            class="flex flex-wrap gap-2 mb-3 min-h-[2.5rem] p-2 border border-gray-300 rounded-md bg-gray-50">
                                            <!-- Selected category tags will appear here -->
                                        </div>

                                        {{-- Available Category Tags --}}
                                        <div class="border border-gray-200 rounded-md p-3 bg-white">
                                            <div class="text-xs text-gray-500 mb-2">Click to select categories:</div>
                                            <div class="flex flex-wrap gap-2">
                                                <button type="button" data-category="bread"
                                                    class="category-tag px-3 py-1 bg-amber-100 text-amber-800 text-sm rounded-full border border-amber-200 hover:bg-amber-200 transition-colors">
                                                    🍞 Bread
                                                </button>
                                                <button type="button" data-category="pastry"
                                                    class="category-tag px-3 py-1 bg-pink-100 text-pink-800 text-sm rounded-full border border-pink-200 hover:bg-pink-200 transition-colors">
                                                    🥐 Pastry
                                                </button>
                                                <button type="button" data-category="cake"
                                                    class="category-tag px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full border border-purple-200 hover:bg-purple-200 transition-colors">
                                                    🍰 Cake
                                                </button>
                                                <button type="button" data-category="waste_processing"
                                                    class="category-tag px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full border border-green-200 hover:bg-green-200 transition-colors">
                                                    ♻️ Waste Processing
                                                </button>
                                                <button type="button" data-category="beverage"
                                                    class="category-tag px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full border border-blue-200 hover:bg-blue-200 transition-colors">
                                                    🥤 Beverage
                                                </button>
                                                <button type="button" data-category="snack"
                                                    class="category-tag px-3 py-1 bg-orange-100 text-orange-800 text-sm rounded-full border border-orange-200 hover:bg-orange-200 transition-colors">
                                                    🍿 Snack
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Is Waste Recipe Checkbox --}}
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" id="is_waste_checkbox"
                                                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                        </path>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-700">Is Waste Recovery
                                                        Recipe</span>
                                                </div>
                                            </label>
                                            <p class="text-xs text-gray-500 mt-1 ml-7">
                                                Check if this recipe transforms waste/byproducts into valuable products
                                            </p>
                                        </div>

                                        {{-- Hidden input to store selected categories --}}
                                        <input type="hidden" id="selected_categories_input" name="categories" value="">
                                    </div>

                                    {{-- Yield & Prep Time --}}
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Recipe Yield</label>
                                            <div class="flex mt-1">
                                                <input type="text" id="recipe-yield-value" placeholder="24"
                                                    class="block w-full rounded-l-md border border-gray-300 py-2 px-3 focus:ring-[#F59E0B] focus:border-[#F59E0B] sm:text-sm transition-all duration-200">
                                                <select id="recipe-yield-unit"
                                                    class="rounded-r-md border border-l-0 border-gray-300 bg-gray-50 py-2 px-2 text-sm transition-all duration-200">
                                                    <option>pcs</option>
                                                    <option>kg</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Prep Time</label>
                                            <div class="flex mt-1">
                                                <input type="text" id="recipe-prep-time-value" placeholder="30"
                                                    class="block w-full rounded-l-md border border-gray-300 py-2 px-3 focus:ring-[#F59E0B] focus:border-[#F59E0B] sm:text-sm transition-all duration-200">
                                                <select id="recipe-prep-time-unit"
                                                    class="rounded-r-md border border-l-0 border-gray-300 bg-gray-50 py-2 px-2 text-sm transition-all duration-200">
                                                    <option>mins</option>
                                                    <option>hrs</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Shelf Life</label>
                                            <div class="flex mt-1">
                                                <input type="text" id="recipe-shelf-life-value" placeholder="7"
                                                    class="block w-full rounded-l-md border border-gray-300 py-2 px-3 focus:ring-[#F59E0B] focus:border-[#F59E0B] sm:text-sm transition-all duration-200">
                                                <select id="recipe-shelf-life-unit"
                                                    class="rounded-r-md border border-l-0 border-gray-300 bg-gray-50 py-2 px-2 text-sm transition-all duration-200">
                                                    <option>Days</option>
                                                    <option>Weeks</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="hidden">
                                            <label class="block text-sm font-medium text-gray-700">Version <span
                                                    class="text-xs text-gray-500">(Display Only)</span></label>
                                            <select id="recipe-version"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#F59E0B] focus:border-[#F59E0B] sm:text-sm bg-white transition-all duration-200">
                                                <option value="v1.0">v1.0</option>
                                                <option value="v1.1">v1.1</option>
                                                <option value="v1.2">v1.2</option>
                                                <option value="v2.0">v2.0</option>
                                                <option value="v2.1">v2.1</option>
                                                <option value="v3.0">v3.0</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right Column: Ingredients & Costs --}}
                                <div class="space-y-6">
                                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                        <h3 class="text-amber-900 mb-1 flex items-center gap-2 font-semibold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                            Ingredients & Costing
                                        </h3>
                                        <p class="text-xs text-amber-700">List all ingredients and calculate production
                                            costs</p>
                                    </div>

                                    {{-- Ingredients List --}}
                                    <div>
                                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                            Ingredients *
                                        </label>

                                        <div class="space-y-3 rounded-lg p-4 border bg-gray-50 border-gray-200">

                                            {{-- Headers --}}
                                            <div class="grid grid-cols-12 gap-2 text-xs text-gray-500 px-1">
                                                <div class="col-span-4">Ingredient Name</div>
                                                <div class="col-span-2 text-center">Aged</div>
                                                <div class="col-span-2">Days</div>
                                                <div class="col-span-2">Qty</div>
                                                <div class="col-span-2">Unit</div>
                                            </div>

                                            {{-- Dynamic Rows (with AJAX search) --}}
                                            <div id="ingredients-container">
                                                <div class="ingredient-row grid grid-cols-12 gap-2 items-center mb-2">
                                                    <div class="col-span-4 relative">
                                                        <input type="text" placeholder="e.g. Flour"
                                                            class="block w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B]"
                                                            name="ingredients[0][name]" data-index="0" autocomplete="off">
                                                        <!-- Product Search Dropdown -->
                                                        <div class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto hidden"
                                                            id="product-search-dropdown-0">
                                                            <div class="search-results"></div>
                                                            <div class="no-results hidden px-4 py-2 text-gray-500">No
                                                                products found</div>
                                                        </div>
                                                        <!-- Hidden input to store the selected product item ID -->
                                                        <input type="hidden" name="ingredients[0][product_item_id]"
                                                            value="">
                                                        <!-- Hidden input to store median cost per unit -->
                                                        <input type="hidden" name="ingredients[0][median_cost]" value="0"
                                                            class="ingredient-median-cost">
                                                    </div>
                                                    <div class="col-span-2 flex justify-center">
                                                        <input type="checkbox" name="ingredients[0][is_aged]"
                                                            class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2 mt-1"
                                                            onchange="toggleAgeInput(this, 0)">
                                                    </div>
                                                    <div class="col-span-2">
                                                        <input type="number" placeholder="Days"
                                                            class="block w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B] bg-gray-100"
                                                            name="ingredients[0][aged_days]" id="aged-days-0" disabled>
                                                    </div>
                                                    <div class="col-span-2">
                                                        <input type="number" step="0.01" placeholder="0"
                                                            class="block w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B]"
                                                            name="ingredients[0][quantity]">
                                                    </div>
                                                    <div class="col-span-2">
                                                        <select
                                                            class="block w-full border border-gray-300 rounded-md py-1.5 px-1 text-sm bg-white"
                                                            name="ingredients[0][unit]">
                                                            <option>g</option>
                                                            <option>ml</option>
                                                            <option>pcs</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-span-2 text-center">
                                                        <button type="button"
                                                            class="text-red-500 hover:text-red-700 remove-ingredient">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" id="add-ingredient"
                                                class="w-full mt-2 py-2 border-2 border-dashed border-gray-300 rounded-lg text-sm text-gray-600 hover:border-gray-400 hover:bg-gray-50 flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Add Another Ingredient
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Cost Breakdown --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Cost Breakdown
                                            (LKR)</label>
                                        <div class="bg-green-50 rounded-lg p-4 border border-green-200 space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-700">Material Cost</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs text-gray-500">Rs.</span>
                                                    <input type="number" value="0.00" id="material-cost" readonly
                                                        class="w-24 text-right border border-green-300 rounded px-2 py-1 text-sm bg-gray-50 cursor-not-allowed">
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-700">Overhead Cost</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs text-gray-500">Rs.</span>
                                                    <input type="number" value="0.00" id="overhead-cost"
                                                        class="w-24 text-right border border-green-300 rounded px-2 py-1 text-sm">
                                                </div>
                                            </div>
                                            <div class="h-px bg-green-300 my-2"></div>
                                            <div
                                                class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm">
                                                <span class="font-medium text-gray-900">Total Per Batch</span>
                                                <span id="total-per-batch" class="font-bold text-green-700">Rs. 0.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Waste Recovery & Byproducts (shown when waste is enabled) --}}
                                    <div id="waste_recovery_section" class="hidden space-y-4 mt-4">
                                        <div class="rounded-2xl border-2 border-emerald-200 bg-emerald-50 p-4">
                                            <div class="flex items-center gap-2 mb-4">
                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                    </path>
                                                </svg>
                                                <div>
                                                    <h4 class="text-emerald-900 font-semibold text-sm">Waste Recovery &
                                                        Byproducts (Optional)</h4>
                                                    <p class="text-xs text-emerald-700">Track recoverable waste and
                                                        calculate NRV cost savings</p>
                                                </div>
                                            </div>

                                            {{-- Dynamic Byproduct Rows Container --}}
                                            <div id="byproducts-container" class="space-y-4">
                                                {{-- Rows will be added here via JS --}}
                                            </div>

                                            {{-- Add Byproduct Button --}}
                                            <button type="button" onclick="addByproductRow()"
                                                class="mt-4 w-full py-2 border-2 border-dashed border-emerald-300 rounded-lg text-sm text-emerald-700 hover:bg-emerald-100 hover:border-emerald-400 flex items-center justify-center gap-2 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Add Byproduct
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- Instructions Section (Full Width) --}}
                            <div class="mt-6">
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                                    <h3 class="text-purple-900 mb-1 flex items-center gap-2 font-semibold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                        Preparation Instructions
                                    </h3>
                                    <p class="text-xs text-purple-700">Step-by-step instructions for making this recipe</p>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 space-y-3">
                                    <div id="instructions-container">
                                        <!-- Instructions will be added here dynamically -->
                                    </div>

                                    <button type="button" id="add-instruction"
                                        class="w-full mt-2 py-2 border-2 border-dashed border-gray-300 rounded-lg text-sm text-gray-600 hover:border-gray-400 hover:bg-white flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add Instruction Step
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row gap-3 border-t border-gray-200">
                            <button type="button"
                                class="flex-1 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-[#F59E0B] to-[#D97706] text-base font-medium text-white hover:from-[#D97706] hover:to-[#B45309] focus:outline-none sm:text-sm items-center gap-2 transition-all duration-200 hover:shadow-lg transform hover:scale-[1.02]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Recipe
                            </button>
                            <button type="button"
                                class="flex-1 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm items-center gap-2 transition-all duration-200 hover:shadow-md"
                                onclick="closeCreateModal()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Script for interactivity --}}
    <script>

        function closeCreateModal() {
            const modal = document.getElementById('createRecipeModal');
            const modalContent = modal.querySelector('.modal-content');
            const backdrop = modal.querySelector('.modal-backdrop');

            // Animate out
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            backdrop.classList.add('opacity-0');

            // Hide after animation
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }

        function viewRecipe(recipe) {
            // Logic to open view modal
            console.log('Viewing recipe:', recipe.name);
            alert('View details for: ' + recipe.name);
        }

        // Filter recipes based on selected category
        document.getElementById('categoryFilter').addEventListener('change', function () {
            const selectedCategory = this.value;
            const recipeCards = document.querySelectorAll('[onclick^="viewRecipe"]');

            recipeCards.forEach(card => {
                const recipeData = JSON.parse(card.getAttribute('onclick').match(/\(([^)]+)\)/)[1]);

                if (selectedCategory === 'all' || recipeData.category === selectedCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Search functionality
        document.querySelector('input[placeholder="Search recipes..."]').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const recipeCards = document.querySelectorAll('[onclick^="viewRecipe"]');

            recipeCards.forEach(card => {
                const recipeData = JSON.parse(card.getAttribute('onclick').match(/\(([^)]+)\)/)[1]);

                if (recipeData.name.toLowerCase().includes(searchTerm) ||
                    recipeData.category.toLowerCase().includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Dynamic ingredient management - initialize when modal opens
        let ingredientManagementInitialized = false;
        let submitListenerAdded = false;
        let nrvListenerAdded = false;

        function initializeIngredientManagement() {
            if (ingredientManagementInitialized) return;
            ingredientManagementInitialized = true;

            let ingredientIndex = 1;

            // Handle form submission - only for buttons inside the modal (add only once)
            if (!submitListenerAdded) {
                const modal = document.getElementById('createRecipeModal');
                modal.addEventListener('click', function (e) {
                    console.log('Modal click detected, target:', e.target, 'button text:', e.target.closest('button')?.textContent);
                    const button = e.target.closest('button');
                    if (button && button.textContent.includes('Create Recipe')) {
                        console.log('Create Recipe button clicked:', button);
                        e.preventDefault();
                        submitRecipeForm();
                    }
                });
                submitListenerAdded = true;
            }

            // Add new ingredient row
            document.getElementById('add-ingredient').addEventListener('click', function () {
                const container = document.getElementById('ingredients-container');
                const newRow = document.createElement('div');
                newRow.className = 'ingredient-row grid grid-cols-12 gap-2 items-center mb-2';
                newRow.innerHTML = '<div class="col-span-4 relative">' +
                    '<input type="text" ' +
                    'placeholder="e.g. Flour" ' +
                    'class="block w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B]" ' +
                    'name="ingredients[' + ingredientIndex + '][name]" ' +
                    'data-index="' + ingredientIndex + '" ' +
                    'autocomplete="off">' +
                    '<!-- Product Search Dropdown -->' +
                    '<div class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto hidden" ' +
                    'id="product-search-dropdown-' + ingredientIndex + '">' +
                    '<div class="search-results"></div>' +
                    '<div class="no-results hidden px-4 py-2 text-gray-500">No products found</div>' +
                    '</div>' +
                    '<!-- Hidden input to store the selected product item ID -->' +
                    '<input type="hidden" name="ingredients[' + ingredientIndex + '][product_item_id]" value="">' +
                    '<!-- Hidden input to store median cost per unit -->' +
                    '<input type="hidden" name="ingredients[' + ingredientIndex + '][median_cost]" value="0" class="ingredient-median-cost">' +
                    '</div>' +
                    '<div class="col-span-2 flex justify-center">' +
                    '<input type="checkbox" ' +
                    'name="ingredients[' + ingredientIndex + '][is_aged]" ' +
                    'class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 focus:ring-2 mt-1" ' +
                    'onchange="toggleAgeInput(this, ' + ingredientIndex + ')">' +
                    '</div>' +
                    '<div class="col-span-2">' +
                    '<input type="number" ' +
                    'placeholder="Days" ' +
                    'class="block w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B] bg-gray-100" ' +
                    'name="ingredients[' + ingredientIndex + '][aged_days]" ' +
                    'id="aged-days-' + ingredientIndex + '" ' +
                    'disabled>' +
                    '</div>' +
                    '<div class="col-span-2">' +
                    '<input type="number" placeholder="0" class="block w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B]" name="ingredients[' + ingredientIndex + '][quantity]">' +
                    '</div>' +
                    '<div class="col-span-2">' +
                    '<select class="block w-full border border-gray-300 rounded-md py-1.5 px-1 text-sm bg-white" name="ingredients[' + ingredientIndex + '][unit]">' +
                    '<option>g</option>' +
                    '<option>ml</option>' +
                    '<option>pcs</option>' +
                    '</select>' +
                    '</div>' +
                    '<div class="col-span-2 text-center">' +
                    '<button type="button" class="text-red-500 hover:text-red-700 remove-ingredient">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' +
                    '</button>' +
                    '</div>';

                container.appendChild(newRow);

                // Add event listeners to the new row
                const newInput = newRow.querySelector('input[type="text"][data-index="' + ingredientIndex + '"]');
                addProductSearchFunctionality(newInput);

                ingredientIndex++;
            });

            // Remove ingredient row
            document.addEventListener('click', function (e) {
                if (e.target.closest('.remove-ingredient')) {
                    const row = e.target.closest('.ingredient-row');
                    if (document.querySelectorAll('.ingredient-row').length > 1) {
                        row.remove();
                    }
                }
            });

            // Add product search functionality to initial input
            const initialInput = document.querySelector('input[type="text"][data-index="0"]');
            if (initialInput) {
                addProductSearchFunctionality(initialInput);
            }

            // NRV inputs listener (only once)
            if (!nrvListenerAdded) {
                nrvListenerAdded = true;
                const marketInput = document.getElementById('nrv-market');
                const processingInput = document.getElementById('nrv-processing');
                const netLabel = document.getElementById('nrv-net-label');

                const updateNRV = () => {
                    const market = parseFloat(marketInput?.value) || 0;
                    const processing = parseFloat(processingInput?.value) || 0;
                    const net = market - processing;
                    if (netLabel) {
                        netLabel.textContent = `Rs. ${net.toFixed(2)}`;
                    }
                };

                marketInput?.addEventListener('input', updateNRV);
                processingInput?.addEventListener('input', updateNRV);
            }
        }

        // Initialize ingredient management when modal opens
        function openCreateModal() {
            const modal = document.getElementById('createRecipeModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Trigger animation
            setTimeout(() => {
                const modalContent = modal.querySelector('.modal-content');
                const backdrop = modal.querySelector('.modal-backdrop');
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
                backdrop.classList.remove('opacity-0');
            }, 10);

            // Initialize ingredient management, main product search, cost calculation, category tags, and instructions after modal is shown
            setTimeout(function () {
                initializeIngredientManagement();
                initializeMainProductSearch();
                initializeCostCalculation();
                initializeCategoryTags();
                initializeInstructions();
                initializeImageUpload();
                initializeNrvProductSearch();
                initializeNrvCostProductSearch();
            }, 100);
        }

        // Main product search functionality (non-Alpine.js)
        let mainProductSearchInitialized = false;

        function initializeMainProductSearch() {
            if (mainProductSearchInitialized) return;
            mainProductSearchInitialized = true;

            const searchInput = document.getElementById('main_product_search');
            const dropdown = document.getElementById('main_product_dropdown');
            const resultsDiv = document.getElementById('main_product_results');
            const noResultsDiv = document.getElementById('main_product_no_results');
            const clearButton = document.getElementById('clear_product_selection');

            let searchTimeout;

            // Input event listener
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                const query = this.value.trim();

                if (query.length < 2) {
                    dropdown.classList.add('hidden');
                    return;
                }

                searchTimeout = setTimeout(() => {
                    searchMainProducts(query);
                }, 300);
            });

            // Focus event listener
            searchInput.addEventListener('focus', function () {
                const query = this.value.trim();
                if (query.length >= 2) {
                    searchMainProducts(query);
                }
            });

            // Blur event listener
            searchInput.addEventListener('blur', function () {
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            });

            // Clear selection button
            if (clearButton) {
                clearButton.addEventListener('click', function () {
                    clearMainProductSelection();
                });
            }
        }

        // Search main products via AJAX
        function searchMainProducts(query) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                return;
            }

            fetch('/recipe-management/search-product-items?query=' + encodeURIComponent(query), {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    displayMainProductResults(data.products || []);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    displayMainProductResults([]);
                });
        }

        // NRV Cost Calculation product search (non-Alpine)
        let nrvCostProductSearchInitialized = false;

        function initializeNrvCostProductSearch() {
            if (nrvCostProductSearchInitialized) return;
            nrvCostProductSearchInitialized = true;

            const searchInput = document.getElementById('nrv-product-search-cost');
            const dropdown = document.getElementById('nrv-product-dropdown-cost');
            const resultsDiv = document.getElementById('nrv-product-results-cost');
            const noResultsDiv = document.getElementById('nrv-product-no-results-cost');
            const hiddenId = document.getElementById('nrv-product-id-cost');
            const marketInput = document.getElementById('nrv-market');

            let searchTimeout;

            const performSearch = (query) => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) return;
                fetch('/recipe-management/search-product-items?query=' + encodeURIComponent(query), {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        const products = data.products || [];
                        if (products.length > 0) {
                            let html = '';
                            products.forEach(product => {
                                const cost = product.median_costing_price || 0;
                                html += '<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer nrv-cost-product-result border-b border-gray-100 last:border-b-0" ' +
                                    'data-id="' + product.id + '" ' +
                                    'data-name="' + product.product_name + '" ' +
                                    'data-cost="' + cost + '">' +
                                    '<div class="font-medium text-gray-900">' + product.product_name + '</div>' +
                                    '<div class="text-sm text-gray-600">Median Cost: Rs. ' + cost.toFixed(2) + '</div>' +
                                    '</div>';
                            });
                            resultsDiv.innerHTML = html;
                            resultsDiv.style.display = 'block';
                            noResultsDiv.classList.add('hidden');
                            dropdown.classList.remove('hidden');

                            dropdown.querySelectorAll('.nrv-cost-product-result').forEach(item => {
                                item.addEventListener('click', function () {
                                    const id = this.getAttribute('data-id');
                                    const name = this.getAttribute('data-name');
                                    const cost = parseFloat(this.getAttribute('data-cost')) || 0;
                                    searchInput.value = name;
                                    hiddenId.value = id;
                                    if (marketInput) {
                                        marketInput.value = cost.toFixed(2);
                                        const evt = new Event('input', { bubbles: true });
                                        marketInput.dispatchEvent(evt);
                                    }
                                    dropdown.classList.add('hidden');
                                });
                            });
                        } else {
                            resultsDiv.style.display = 'none';
                            noResultsDiv.classList.remove('hidden');
                            dropdown.classList.remove('hidden');
                        }
                    })
                    .catch(() => {
                        resultsDiv.style.display = 'none';
                        noResultsDiv.classList.remove('hidden');
                        dropdown.classList.remove('hidden');
                    });
            };

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    if (query.length < 2) {
                        dropdown.classList.add('hidden');
                        return;
                    }
                    searchTimeout = setTimeout(() => performSearch(query), 300);
                });

                searchInput.addEventListener('focus', function () {
                    const query = this.value.trim();
                    if (query.length >= 2) {
                        performSearch(query);
                    }
                });

                searchInput.addEventListener('blur', function () {
                    setTimeout(() => dropdown.classList.add('hidden'), 200);
                });
            }
        }

        // NRV byproduct product search (non-Alpine)
        let nrvProductSearchInitialized = false;

        function initializeNrvProductSearch() {
            if (nrvProductSearchInitialized) return;
            nrvProductSearchInitialized = true;

            const searchInput = document.getElementById('nrv-product-search');
            const dropdown = document.getElementById('nrv-product-dropdown');
            const resultsDiv = document.getElementById('nrv-product-results');
            const noResultsDiv = document.getElementById('nrv-product-no-results');
            const hiddenId = document.getElementById('nrv-product-id');
            const marketInput = document.getElementById('nrv-market');

            let searchTimeout;

            const performSearch = (query) => {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) return;
                fetch('/recipe-management/search-product-items?query=' + encodeURIComponent(query), {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        const products = data.products || [];
                        if (products.length > 0) {
                            let html = '';
                            products.forEach(product => {
                                const cost = product.median_costing_price || 0;
                                html += '<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer nrv-product-result border-b border-gray-100 last:border-b-0" ' +
                                    'data-id="' + product.id + '" ' +
                                    'data-name="' + product.product_name + '" ' +
                                    'data-cost="' + cost + '">' +
                                    '<div class="font-medium text-gray-900">' + product.product_name + '</div>' +
                                    '<div class="text-sm text-gray-600">Median Cost: Rs. ' + cost.toFixed(2) + '</div>' +
                                    '</div>';
                            });
                            resultsDiv.innerHTML = html;
                            resultsDiv.style.display = 'block';
                            noResultsDiv.classList.add('hidden');
                            dropdown.classList.remove('hidden');

                            dropdown.querySelectorAll('.nrv-product-result').forEach(item => {
                                item.addEventListener('click', function () {
                                    const id = this.getAttribute('data-id');
                                    const name = this.getAttribute('data-name');
                                    const cost = parseFloat(this.getAttribute('data-cost')) || 0;
                                    searchInput.value = name;
                                    hiddenId.value = id;
                                    if (marketInput) {
                                        marketInput.value = cost.toFixed(2);
                                        const evt = new Event('input', { bubbles: true });
                                        marketInput.dispatchEvent(evt);
                                    }
                                    dropdown.classList.add('hidden');
                                });
                            });
                        } else {
                            resultsDiv.style.display = 'none';
                            noResultsDiv.classList.remove('hidden');
                            dropdown.classList.remove('hidden');
                        }
                    })
                    .catch(() => {
                        resultsDiv.style.display = 'none';
                        noResultsDiv.classList.remove('hidden');
                        dropdown.classList.remove('hidden');
                    });
            };

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    if (query.length < 2) {
                        dropdown.classList.add('hidden');
                        return;
                    }
                    searchTimeout = setTimeout(() => performSearch(query), 300);
                });

                searchInput.addEventListener('focus', function () {
                    const query = this.value.trim();
                    if (query.length >= 2) {
                        performSearch(query);
                    }
                });

                searchInput.addEventListener('blur', function () {
                    setTimeout(() => dropdown.classList.add('hidden'), 200);
                });
            }
        }

        // Display main product search results
        function displayMainProductResults(products) {
            const dropdown = document.getElementById('main_product_dropdown');
            const resultsDiv = document.getElementById('main_product_results');
            const noResultsDiv = document.getElementById('main_product_no_results');

            if (products.length > 0) {
                let html = '';
                products.forEach(product => {
                    const cost = product.median_costing_price || 0;
                    html += '<div class="px-4 py-3 hover:bg-gray-50 cursor-pointer main-product-result border-b border-gray-100 last:border-b-0" ' +
                        'data-id="' + product.id + '" ' +
                        'data-name="' + product.product_name + '" ' +
                        'data-cost="' + cost + '">' +
                        '<div class="font-medium text-gray-900">' + product.product_name + '</div>' +
                        '<div class="text-sm text-gray-500">Median Cost: Rs. ' + cost.toFixed(2) + '</div>' +
                        '</div>';
                });

                resultsDiv.innerHTML = html;
                resultsDiv.style.display = 'block';
                noResultsDiv.classList.add('hidden');

                // Add click event listeners
                dropdown.querySelectorAll('.main-product-result').forEach(item => {
                    item.addEventListener('click', function () {
                        selectMainProduct({
                            id: this.getAttribute('data-id'),
                            product_name: this.getAttribute('data-name'),
                            selling_price: parseFloat(this.getAttribute('data-cost')) || 0
                        });
                    });
                });

                dropdown.classList.remove('hidden');
            } else {
                resultsDiv.style.display = 'none';
                noResultsDiv.classList.remove('hidden');
                dropdown.classList.remove('hidden');
            }
        }

        // Select main product
        function selectMainProduct(product) {
            // Update hidden inputs
            document.getElementById('selected_product_id').value = product.id;
            document.getElementById('selected_category').value = 'bread';
            document.getElementById('selected_is_waste').value = '0';

            // Update display
            document.getElementById('selected_product_name').textContent = product.product_name;
            document.getElementById('selected_product_info').textContent = 'Cost: Rs. ' + (product.selling_price || 0);

            // Show badge
            document.getElementById('selected_product_badge').classList.remove('hidden');

            // Hide dropdown
            document.getElementById('main_product_dropdown').classList.add('hidden');

            // Clear search input
            document.getElementById('main_product_search').value = product.product_name;
        }

        // Clear main product selection
        function clearMainProductSelection() {
            // Clear hidden inputs
            document.getElementById('selected_product_id').value = '';
            document.getElementById('selected_category').value = 'bread';
            document.getElementById('selected_is_waste').value = '0';

            // Clear display
            document.getElementById('selected_product_name').textContent = '';
            document.getElementById('selected_product_info').textContent = '';

            // Hide badge
            document.getElementById('selected_product_badge').classList.add('hidden');

            // Clear search input
            document.getElementById('main_product_search').value = '';
        }

        // Category tags and waste checkbox functionality
        let selectedCategories = new Set();

        let categoryTagsInitialized = false;

        function initializeCategoryTags() {
            if (categoryTagsInitialized) return;
            categoryTagsInitialized = true;
            // Is waste checkbox handler
            const wasteCheckbox = document.getElementById('is_waste_checkbox');
            if (wasteCheckbox) {
                wasteCheckbox.addEventListener('change', function () {
                    updateWasteProcessingUI();
                });
            }

            // Category tag click handlers
            document.querySelectorAll('.category-tag').forEach(tag => {
                tag.addEventListener('click', function () {
                    const category = this.getAttribute('data-category');
                    toggleCategory(category);
                });
            });
        }

        function toggleCategory(category) {
            const selectedContainer = document.getElementById('selected-categories');
            const hiddenInput = document.getElementById('selected_categories_input');

            if (selectedCategories.has(category)) {
                // Remove category
                selectedCategories.delete(category);
                removeCategoryTag(category);
            } else {
                // Add category
                selectedCategories.add(category);
                addCategoryTag(category);
            }

            // Update hidden input
            hiddenInput.value = Array.from(selectedCategories).join(',');

            // Update tag button states
            updateTagButtonStates();

            // Update waste checkbox if waste_processing category is toggled
            if (category === 'waste_processing') {
                document.getElementById('is_waste_checkbox').checked = selectedCategories.has('waste_processing');
            }

            // Update waste processing UI if needed
            updateWasteProcessingUI();
        }

        function addCategoryTag(category) {
            const selectedContainer = document.getElementById('selected-categories');

            // Create tag element
            const tagElement = document.createElement('div');
            tagElement.className = 'category-tag-selected flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full border border-blue-200';
            tagElement.setAttribute('data-category', category);

            // Add appropriate icon and text
            let icon = '';
            let text = '';
            switch (category) {
                case 'bread': icon = '🍞'; text = 'Bread'; break;
                case 'pastry': icon = '🥐'; text = 'Pastry'; break;
                case 'cake': icon = '🍰'; text = 'Cake'; break;
                case 'waste_processing': icon = '♻️'; text = 'Waste Processing'; break;
                case 'beverage': icon = '🥤'; text = 'Beverage'; break;
                case 'snack': icon = '🍿'; text = 'Snack'; break;
                default: text = category;
            }

            tagElement.innerHTML = `
                                <span>${icon} ${text}</span>
                                <button type="button" class="ml-1 text-blue-600 hover:text-blue-800" onclick="removeCategoryTag('${category}')">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            `;

            selectedContainer.appendChild(tagElement);
        }

        function removeCategoryTag(category) {
            selectedCategories.delete(category);

            // Remove from display
            const tagElement = document.querySelector(`.category-tag-selected[data-category="${category}"]`);
            if (tagElement) {
                tagElement.remove();
            }

            // Update hidden input
            document.getElementById('selected_categories_input').value = Array.from(selectedCategories).join(',');

            // Update button states
            updateTagButtonStates();

            // Update waste processing UI
            updateWasteProcessingUI();
        }

        function updateTagButtonStates() {
            document.querySelectorAll('.category-tag').forEach(button => {
                const category = button.getAttribute('data-category');
                if (selectedCategories.has(category)) {
                    button.classList.add('ring-2', 'ring-blue-500', 'bg-blue-200');
                } else {
                    button.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-200');
                }
            });
        }

        function updateWasteProcessingUI() {
            const isWasteProcessing = selectedCategories.has('waste_processing') || document.getElementById('is_waste_checkbox').checked;
            const wasteBanner = document.getElementById('waste_processing_banner');
            const wasteSection = document.getElementById('waste_recovery_section');

            if (isWasteProcessing) {
                wasteBanner.classList.remove('hidden');
                wasteSection?.classList.remove('hidden');
                // Update product selector styling if needed
                const productSelector = document.querySelector('.border-2.rounded-xl.p-5.mt-2');
                if (productSelector) {
                    productSelector.className = 'border-2 rounded-xl p-5 mt-2 transition-colors duration-300 bg-gradient-to-r from-green-50 to-emerald-50 border-green-200';
                }
            } else {
                wasteBanner.classList.add('hidden');
                wasteSection?.classList.add('hidden');
                // Reset product selector styling
                const productSelector = document.querySelector('.border-2.rounded-xl.p-5.mt-2');
                if (productSelector) {
                    productSelector.className = 'border-2 rounded-xl p-5 mt-2 transition-colors duration-300 bg-gradient-to-r from-amber-50 to-orange-50 border-amber-200';
                }
            }
        }

        function resetCategorySelection() {
            // Clear all selected categories
            selectedCategories.clear();

            // Clear display
            document.getElementById('selected-categories').innerHTML = '';

            // Reset waste checkbox
            document.getElementById('is_waste_checkbox').checked = false;

            // Update hidden input
            document.getElementById('selected_categories_input').value = '';

            // Reset button states
            updateTagButtonStates();

            // Reset waste processing UI
            updateWasteProcessingUI();
        }

        // Cost calculation functions


        function updateCostBreakdown() {
            const materialCost = calculateMaterialCost();
            const overheadCost = parseFloat(document.getElementById('overhead-cost').value) || 0;
            const totalCost = materialCost + overheadCost;

            // Update material cost input
            document.getElementById('material-cost').value = materialCost.toFixed(2);

            // Update total display
            document.getElementById('total-per-batch').textContent = 'Rs. ' + totalCost.toFixed(2);
        }

        let costCalculationInitialized = false;

        function initializeCostCalculation() {
            if (costCalculationInitialized) return;
            costCalculationInitialized = true;

            // Listen for changes on quantity inputs and overhead cost
            document.addEventListener('input', function (e) {
                if (e.target.matches('input[name*="[quantity]"], #overhead-cost')) {
                    calculateMaterialCost();
                    updateCostBreakdown();
                }
            });

            // Also listen for changes when ingredients are added/removed
            document.addEventListener('click', function (e) {
                if (e.target.closest('.remove-ingredient') || e.target.closest('#add-ingredient')) {
                    // Delay calculation to allow DOM updates
                    setTimeout(() => {
                        calculateMaterialCost();
                        updateCostBreakdown();
                    }, 100);
                }
            });

            // Initial calculation
            calculateMaterialCost();
            updateCostBreakdown();
        }

        // Calculate material cost based on median costs and quantities
        function calculateMaterialCost() {
            let totalMaterialCost = 0;

            // Get all ingredient rows
            const ingredientRows = document.querySelectorAll('.ingredient-row');

            ingredientRows.forEach(row => {
                const medianCostInput = row.querySelector('.ingredient-median-cost');
                const quantityInput = row.querySelector('input[name*="[quantity]"]');

                if (medianCostInput && quantityInput) {
                    const medianCost = parseFloat(medianCostInput.value) || 0;
                    const quantity = parseFloat(quantityInput.value) || 0;
                    totalMaterialCost += (medianCost * quantity);
                }
            });

            // Update the material cost field
            const materialCostField = document.getElementById('material-cost');
            if (materialCostField) {
                materialCostField.value = totalMaterialCost.toFixed(2);
            }

            return totalMaterialCost;
        }

        // Instruction steps functionality
        let instructionStepCount = 0;

        let instructionsInitialized = false;

        function initializeInstructions() {
            if (instructionsInitialized) return;
            instructionsInitialized = true;
            // Add initial instruction step
            addInstructionStep();

            // Add instruction button handler
            document.getElementById('add-instruction').addEventListener('click', function () {
                addInstructionStep();
            });
        }

        function addInstructionStep() {
            instructionStepCount++;
            const container = document.getElementById('instructions-container');

            const stepDiv = document.createElement('div');
            stepDiv.className = 'instruction-step flex gap-3 items-start';
            stepDiv.setAttribute('data-step', instructionStepCount);

            stepDiv.innerHTML = `
                                <span class="mt-2 bg-purple-100 text-purple-800 text-xs font-bold px-2 py-1 rounded-full step-number">${instructionStepCount}</span>
                                <textarea
                                    rows="2"
                                    placeholder="Describe this step..."
                                    class="instruction-text block w-full border border-gray-300 rounded-md py-2 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B]"
                                    name="instructions[${instructionStepCount - 1}][step_description]"
                                ></textarea>
                                <button type="button" class="remove-instruction mt-2 text-gray-400 hover:text-red-500 ${instructionStepCount === 1 ? 'hidden' : ''}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            `;

            container.appendChild(stepDiv);

            // Add remove handler
            stepDiv.querySelector('.remove-instruction').addEventListener('click', function () {
                removeInstructionStep(stepDiv);
            });

            // Update step numbers and hidden inputs
            updateInstructionSteps();
        }

        function removeInstructionStep(stepElement) {
            stepElement.remove();
            updateInstructionSteps();
        }

        function updateInstructionSteps() {
            const steps = document.querySelectorAll('.instruction-step');
            steps.forEach((step, index) => {
                // Update step number display
                step.querySelector('.step-number').textContent = index + 1;

                // Update textarea name attribute
                const textarea = step.querySelector('.instruction-text');
                textarea.name = `instructions[${index}][step_description]`;

                // Add hidden input for step_number
                let hiddenInput = step.querySelector('input[type="hidden"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    step.appendChild(hiddenInput);
                }
                hiddenInput.name = `instructions[${index}][step_number]`;
                hiddenInput.value = index + 1;

                // Show/hide remove button (keep at least one step)
                const removeBtn = step.querySelector('.remove-instruction');
                if (steps.length > 1) {
                    removeBtn.classList.remove('hidden');
                } else {
                    removeBtn.classList.add('hidden');
                }
            });

            instructionStepCount = steps.length;
        }

        // Image upload functionality
        let imageUploadInitialized = false;

        function initializeImageUpload() {
            if (imageUploadInitialized) return;
            imageUploadInitialized = true;

            const uploadArea = document.getElementById('image-upload-area');
            const fileInput = document.getElementById('recipe-image');
            const placeholder = document.getElementById('upload-placeholder');
            const preview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const fileName = document.getElementById('file-name');
            const removeBtn = document.getElementById('remove-image');

            // Click to select file
            uploadArea.addEventListener('click', function (e) {
                if (e.target !== removeBtn) {
                    fileInput.click();
                }
            });

            // File selection
            fileInput.addEventListener('change', function (e) {
                handleFileSelect(e.target.files[0]);
            });

            // Drag and drop
            uploadArea.addEventListener('dragover', function (e) {
                e.preventDefault();
                uploadArea.classList.add('border-[#F59E0B]');
                uploadArea.classList.remove('border-gray-300');
            });

            uploadArea.addEventListener('dragleave', function (e) {
                e.preventDefault();
                uploadArea.classList.remove('border-[#F59E0B]');
                uploadArea.classList.add('border-gray-300');
            });

            uploadArea.addEventListener('drop', function (e) {
                e.preventDefault();
                uploadArea.classList.remove('border-[#F59E0B]');
                uploadArea.classList.add('border-gray-300');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelect(files[0]);
                }
            });

            // Remove image
            removeBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                clearImage();
            });

            function handleFileSelect(file) {
                if (!file) return;

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file.');
                    return;
                }

                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB.');
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    fileName.textContent = file.name;
                    placeholder.classList.add('hidden');
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }

            function clearImage() {
                fileInput.value = '';
                previewImg.src = '';
                fileName.textContent = '';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }

        // Function to add product search functionality to an input
        function addProductSearchFunctionality(input) {
            let timeout;
            let currentIndex = input.getAttribute('data-index');
            let dropdown = document.getElementById('product-search-dropdown-' + currentIndex);

            input.addEventListener('input', function () {
                clearTimeout(timeout);
                const query = this.value;

                if (query.length < 2) {
                    dropdown.classList.add('hidden');
                    return;
                }

                timeout = setTimeout(() => {
                    searchProductItems(query, currentIndex);
                }, 300); // Debounce the search
            });

            input.addEventListener('focus', function () {
                if (this.value.length >= 2) {
                    searchProductItems(this.value, currentIndex);
                }
            });

            input.addEventListener('blur', function () {
                // Delay hiding to allow click event on dropdown items
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            });
        }

        // Function to search product items via AJAX
        function searchProductItems(query, index) {
            fetch('/recipe-management/search-product-items?query=' + encodeURIComponent(query), {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    displayProductSearchResults(data.products, index);
                })
                .catch(error => {
                    console.error('Error searching product items:', error);
                    // Show empty results on error
                    displayProductSearchResults([], index);

                    // If we get HTML response (authentication redirect), show user-friendly message
                    if (error.message.includes('DOCTYPE') || error.message.includes('HTTP 302') || error.message.includes('HTTP 401')) {
                        console.warn('Session may have expired. Please refresh the page and try again.');
                        // Show user notification
                        alert('Your session may have expired. Please refresh the page and try again.');
                    }
                });
        }

        // Function to display product search results
        function displayProductSearchResults(products, index) {
            const dropdown = document.getElementById('product-search-dropdown-' + index);
            const searchResultsDiv = dropdown.querySelector('.search-results');
            const noResultsDiv = dropdown.querySelector('.no-results');

            if (products.length > 0) {
                let html = '';
                products.forEach(product => {
                    // Use median costing price from stm_stock table
                    const cost = product.median_costing_price || 0;

                    html += '<div class="px-4 py-3 hover:bg-gray-100 cursor-pointer product-item-result border-b border-gray-100 last:border-b-0" ' +
                        'data-id="' + product.id + '" ' +
                        'data-name="' + product.product_name + '" ' +
                        'data-cost="' + cost + '" ' +
                        'data-category="bread">' +
                        '<div class="font-medium text-gray-900">' + product.product_name + '</div>' +
                        '<div class="text-sm text-gray-600">Median Cost: Rs. ' + cost.toFixed(2) + '</div>' +
                        '</div>';
                });

                searchResultsDiv.innerHTML = html;
                searchResultsDiv.style.display = 'block';
                noResultsDiv.classList.add('hidden');

                // Add click event listeners to results
                dropdown.querySelectorAll('.product-item-result').forEach(item => {
                    item.addEventListener('click', function () {
                        const selectedId = this.getAttribute('data-id');
                        const selectedName = this.getAttribute('data-name');
                        const selectedCost = this.getAttribute('data-cost');

                        // Find the ingredient row
                        const ingredientRow = dropdown.closest('.ingredient-row');

                        // Update the input field with the selected product name
                        const nameInput = ingredientRow.querySelector('input[data-index="' + index + '"]');
                        nameInput.value = selectedName;

                        // Update the hidden product_item_id field
                        const hiddenInput = ingredientRow.querySelector('input[type="hidden"][name*="product_item_id"]');
                        hiddenInput.value = selectedId;

                        // Store the median cost in hidden field
                        const medianCostInput = ingredientRow.querySelector('.ingredient-median-cost');
                        if (medianCostInput && selectedCost) {
                            medianCostInput.value = selectedCost;
                        }

                        // Hide the dropdown
                        dropdown.classList.add('hidden');

                        // Recalculate material cost
                        calculateMaterialCost();
                    });
                });

                dropdown.classList.remove('hidden');
            } else {
                searchResultsDiv.style.display = 'none';
                noResultsDiv.classList.remove('hidden');
                dropdown.classList.remove('hidden');
            }
        }

        // Function to submit the recipe form

        // Waste Recovery Byproducts functionality
        let byproductStepCount = 0;

        function addByproductRow() {
            byproductStepCount++;
            const container = document.getElementById('byproducts-container');
            const rowId = `byproduct-row-${byproductStepCount}`;

            const rowDiv = document.createElement('div');
            rowDiv.className = 'byproduct-row rounded-xl border border-emerald-200 bg-white p-3 space-y-3 relative group transition-all hover:shadow-md';
            rowDiv.id = rowId;
            rowDiv.setAttribute('data-index', byproductStepCount);

            rowDiv.innerHTML = `
                                 <button type="button" onclick="removeByproductRow('${rowId}')" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>

                                {{-- Byproduct Input --}}
                                <div class="space-y-2">
                                    <div class="text-xs font-semibold text-emerald-800 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        Byproduct #${document.querySelectorAll('.byproduct-row').length + 1}
                                    </div>
                                    <div class="grid grid-cols-12 gap-2 text-xs text-gray-500">
                                        <div class="col-span-6">Byproduct Name</div>
                                        <div class="col-span-3 text-center">Quantity</div>
                                        <div class="col-span-3 text-center">Unit</div>
                                    </div>
                                    <div class="grid grid-cols-12 gap-2 items-center">
                                        <div class="col-span-6 relative">
                                            <input type="text"
                                                class="w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B]"
                                                placeholder="Search byproduct..."
                                                name="wastage_recovery_by_products[${byproductStepCount}][product_name]"
                                                data-index="${byproductStepCount}"
                                                id="byproduct-search-${byproductStepCount}"
                                                autocomplete="off"
                                            >
                                            <input type="hidden" name="wastage_recovery_by_products[${byproductStepCount}][product_item_id]" id="byproduct-id-${byproductStepCount}" class="product-id-input">
                                            <div id="byproduct-search-dropdown-${byproductStepCount}" class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto hidden">
                                                <div class="search-results"></div>
                                                <div class="no-results hidden px-4 py-2 text-gray-500">No products found</div>
                                            </div>
                                        </div>
                                        <div class="col-span-3">
                                            <input type="number" step="0.01"
                                                name="wastage_recovery_by_products[${byproductStepCount}][quantity]"
                                                class="w-full border border-gray-300 rounded-md py-1.5 px-3 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B] nrv-calc-input"
                                                placeholder="0"
                                            >
                                        </div>
                                        <div class="col-span-3">
                                            <select name="wastage_recovery_by_products[${byproductStepCount}][unit]" class="w-full border border-gray-300 rounded-md py-1.5 px-2 text-sm focus:ring-[#F59E0B] focus:border-[#F59E0B] bg-white">
                                                <option>kg</option>
                                                <option>g</option>
                                                <option>pcs</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- NRV Calculation Sub-section --}}
                                <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 pt-2">
                                    <div class="text-[11px] font-semibold text-amber-800 mb-2 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        NRV Cost Calculation
                                    </div>

                                    {{-- Link to Product for NRV --}}
                                    <div class="mb-2 relative">
                                         <label class="text-[10px] text-amber-800 uppercase tracking-wider font-medium mb-1 block">Link to Product (Optional)</label>
                                         <input type="text"
                                                class="w-full border border-amber-200 bg-white rounded-md py-1 px-2 text-xs focus:ring-[#F59E0B] focus:border-[#F59E0B]"
                                                placeholder="Search product to link..."
                                                name="wastage_recovery_by_products[${byproductStepCount}][nrv_product_name]"
                                                id="nrv-search-${byproductStepCount}"
                                                autocomplete="off"
                                            >
                                            <input type="hidden" name="wastage_recovery_by_products[${byproductStepCount}][nrv_product_item_id]" id="nrv-id-${byproductStepCount}">
                                            <div id="nrv-search-dropdown-${byproductStepCount}" class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto hidden">
                                                <div class="search-results"></div>
                                                <div class="no-results hidden px-4 py-2 text-gray-500">No products found</div>
                                            </div>
                                    </div>

                                    <div class="grid grid-cols-4 gap-2">
                                        <div>
                                            <label class="text-[10px] text-amber-800 uppercase tracking-wider font-medium text-nowrap">Market Val.</label>
                                            <input type="number" step="0.01"
                                                name="wastage_recovery_by_products[${byproductStepCount}][market_value]"
                                                class="w-full border border-amber-200 bg-white rounded-md py-1 px-2 text-xs focus:ring-[#F59E0B] focus:border-[#F59E0B] nrv-calc-input"
                                                placeholder="0.00"
                                            >
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-amber-800 uppercase tracking-wider font-medium text-nowrap">Proc. Cost</label>
                                            <input type="number" step="0.01"
                                                name="wastage_recovery_by_products[${byproductStepCount}][processing_cost]"
                                                class="w-full border border-amber-200 bg-white rounded-md py-1 px-2 text-xs focus:ring-[#F59E0B] focus:border-[#F59E0B] nrv-calc-input"
                                                placeholder="0.00"
                                            >
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-emerald-800 uppercase tracking-wider font-medium text-nowrap">Unit NRV</label>
                                            <div class="border border-emerald-200 bg-white rounded-md py-1 px-2 text-xs font-semibold text-emerald-600 flex items-center justify-between h-[26px]">
                                                <span class="nrv-unit-value">0.00</span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-emerald-800 uppercase tracking-wider font-medium text-nowrap">Total NRV</label>
                                            <div class="border border-emerald-300 bg-emerald-50 rounded-md py-1 px-2 text-xs font-bold text-emerald-700 flex items-center justify-between h-[26px]">
                                                <span class="nrv-display-value">0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

            container.appendChild(rowDiv);

            // Initialize search for Byproduct Name
            initializeProductSearch(
                `byproduct-search-${byproductStepCount}`,
                `byproduct-search-dropdown-${byproductStepCount}`,
                `byproduct-id-${byproductStepCount}`
            );

            // Initialize search for NRV Product
            initializeProductSearch(
                `nrv-search-${byproductStepCount}`,
                `nrv-search-dropdown-${byproductStepCount}`,
                `nrv-id-${byproductStepCount}`
            );

            // Initialize NRV calculation listeners for this row
            const inputs = rowDiv.querySelectorAll('.nrv-calc-input');
            inputs.forEach(input => {
                input.addEventListener('input', () => calculateRowNRV(rowId));
            });
        }

        function removeByproductRow(rowId) {
            const row = document.getElementById(rowId);
            if (row) {
                row.remove();
            }
        }

        function calculateRowNRV(rowId) {
            const row = document.getElementById(rowId);
            if (!row) return;

            const quantity = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
            const marketValue = parseFloat(row.querySelector('input[name*="[market_value]"]').value) || 0;
            const processingCost = parseFloat(row.querySelector('input[name*="[processing_cost]"]').value) || 0;

            const unitNrv = Math.max(0, marketValue - processingCost);
            const totalNrv = unitNrv * quantity;

            // Update Unit NRV Display
            row.querySelector('.nrv-unit-value').textContent = unitNrv.toFixed(2);

            // Update Total NRV Display
            row.querySelector('.nrv-display-value').textContent = 'Rs. ' + totalNrv.toFixed(2);
        }

        function initializeProductSearch(inputId, dropdownId, hiddenId) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);
            if (!input || !dropdown) return;

            let timeout;

            input.addEventListener('input', function () {
                clearTimeout(timeout);
                const query = this.value;

                if (query.length < 2) {
                    dropdown.classList.add('hidden');
                    return;
                }

                timeout = setTimeout(() => {
                    fetch('/recipe-management/search-product-items?query=' + encodeURIComponent(query), {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(r => r.json())
                        .then(data => {
                            displayGenericSearchResults(data.products, dropdownId, inputId, hiddenId);
                        })
                        .catch(e => console.error(e));
                }, 300);
            });

            input.addEventListener('blur', function () {
                setTimeout(() => dropdown.classList.add('hidden'), 200);
            });
        }

        function displayGenericSearchResults(products, dropdownId, inputId, hiddenId) {
            const dropdown = document.getElementById(dropdownId);
            const resultsDiv = dropdown.querySelector('.search-results');
            const noResultsDiv = dropdown.querySelector('.no-results');

            if (products.length > 0) {
                let html = '';
                products.forEach(product => {
                    html += `<div class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm result-item"
                                             data-id="${product.id}" data-name="${product.product_name}">
                                        <div class="font-medium text-gray-900">${product.product_name}</div>
                                    </div>`;
                });
                resultsDiv.innerHTML = html;
                dropdown.classList.remove('hidden');
                noResultsDiv.classList.add('hidden');

                // Add click listeners
                dropdown.querySelectorAll('.result-item').forEach(item => {
                    item.addEventListener('click', function () {
                        document.getElementById(inputId).value = this.dataset.name;
                        const hiddenInput = document.getElementById(hiddenId);
                        if (hiddenInput) hiddenInput.value = this.dataset.id;
                        dropdown.classList.add('hidden');
                    });
                });

            } else {
                dropdown.classList.remove('hidden');
                resultsDiv.innerHTML = '';
                noResultsDiv.classList.remove('hidden');
            }
        }

        // Hook into existing updateWasteProcessingUI to add initial row
        const originalUpdateWasteProcessingUI = window.updateWasteProcessingUI || function () { };
        // We need to redefine or extend the existing UpdateWasteProcessingUI if it exists, 
        // OR just rely on the checkbox listener if that function isn't globally available here.
        // Looking at the code above line 1500 (not visible but assumed), there was likely a function called updateWasteProcessingUI.
        // Let's reimplement it to be safe and compatible.

        function updateWasteProcessingUI() {
            const isWaste = document.getElementById('is_waste_checkbox').checked;
            const banner = document.getElementById('waste_processing_banner');
            const section = document.getElementById('waste_recovery_section');
            const icon = document.getElementById('waste_icon');
            const stdIcon = document.getElementById('standard_icon');
            const badge = document.getElementById('waste_recovery_badge');

            if (isWaste) {
                banner.classList.remove('hidden');
                section.classList.remove('hidden');
                icon.classList.remove('hidden');
                stdIcon.classList.add('hidden');
                badge.classList.remove('hidden');

                // Add initial row if empty
                if (document.querySelectorAll('.byproduct-row').length === 0) {
                    addByproductRow();
                }
            } else {
                banner.classList.add('hidden');
                section.classList.add('hidden');
                icon.classList.add('hidden');
                stdIcon.classList.remove('hidden');
                badge.classList.add('hidden');
            }
        }

        // Add listener to checkbox
        document.getElementById('is_waste_checkbox').addEventListener('change', updateWasteProcessingUI);


        // Function to submit the recipe form
        function submitRecipeForm() {
            // console.log('submitRecipeForm called');

            const formDataObj = new FormData();

            // 1. Basic Information

            // Name
            const nameInput = document.querySelector('input[placeholder="e.g. Classic Chocolate Chip"]');
            formDataObj.append('name', nameInput ? nameInput.value : '');

            // Category
            formDataObj.append('category', selectedCategories.size > 0 ? Array.from(selectedCategories)[0] : 'bread');

            // Cost (Material Cost - usually calculated or manual)
            const costInput = document.getElementById('material-cost'); // Ensure this ID exists or use appropriate selector
            formDataObj.append('cost', costInput ? (parseFloat(costInput.value) || 0) : 0);

            // Status (1 = inactive, 2 = active)
            formDataObj.append('status', 2); // Default to active 

            // Is Waste
            formDataObj.append('is_waste', document.getElementById('is_waste_checkbox').checked ? 1 : 0);

            // Product Item ID
            formDataObj.append('product_item_id', document.getElementById('selected_product_id').value || null);

            // Yield
            const yieldValue = document.getElementById('recipe-yield-value').value;
            const yieldUnit = document.getElementById('recipe-yield-unit').value;
            formDataObj.append('yield', yieldValue ? `${yieldValue} ${yieldUnit}` : '');

            // Prep Time
            const prepTimeValue = document.getElementById('recipe-prep-time-value').value;
            const prepTimeUnit = document.getElementById('recipe-prep-time-unit').value;
            formDataObj.append('prep_time', prepTimeValue ? `${prepTimeValue} ${prepTimeUnit}` : '');

            // Shelf Life
            const shelfLifeValue = document.getElementById('recipe-shelf-life-value').value;
            // The controller expects separate unit? No, migration has shelf_life (int) and shelf_life_unit (string).
            // But checking controller validation: 'shelf_life' => 'nullable|integer', 'shelf_life_unit' => 'nullable|string'.
            formDataObj.append('shelf_life', shelfLifeValue || null);
            formDataObj.append('shelf_life_unit', document.getElementById('recipe-shelf-life-unit').value);

            // Version - NOT SAVED (Display Only)
            // formDataObj.append('version', document.getElementById('recipe-version').value || 'v1.0');

            // Image
            const imageInput = document.getElementById('recipe-image');
            if (imageInput && imageInput.files[0]) {
                formDataObj.append('image', imageInput.files[0]);
            }

            // 2. Ingredients
            const ingredientRows = document.querySelectorAll('.ingredient-row');
            ingredientRows.forEach((row, index) => {
                const nameInput = row.querySelector('input[name*="[name]"]').value;
                if (nameInput && nameInput.trim() !== '') {
                    formDataObj.append(`ingredients[${index}][name]`, nameInput);
                    formDataObj.append(`ingredients[${index}][product_item_id]`, row.querySelector('input[name*="[product_item_id]"]').value || '');
                    formDataObj.append(`ingredients[${index}][quantity]`, row.querySelector('input[name*="[quantity]"]').value || 0);
                    formDataObj.append(`ingredients[${index}][unit]`, row.querySelector('select[name*="[unit]"]').value);

                    const isAged = row.querySelector('input[name*="[is_aged]"]').checked;
                    formDataObj.append(`ingredients[${index}][is_aged]`, isAged ? 1 : 0);
                    if (isAged) {
                        formDataObj.append(`ingredients[${index}][aged_days]`, row.querySelector('input[name*="[aged_days]"]').value || 0);
                    }
                }
            });

            // 3. Instructions
            // Logic to find instruction textareas. They are usually generated dynamically.
            // Based on grep: name="instructions[${index}][step_description]"
            // We can iterate over them.
            const instructionTextareas = document.querySelectorAll('textarea[name^="instructions"][name$="[step_description]"]');
            instructionTextareas.forEach((textarea, index) => {
                if (textarea.value.trim() !== '') {
                    formDataObj.append(`instructions[${index}][step_description]`, textarea.value);
                    formDataObj.append(`instructions[${index}][step_number]`, index + 1);
                    formDataObj.append(`instructions[${index}][sort_order]`, index);
                }
            });

            // 4. Waste Recovery Byproducts
            if (document.getElementById('is_waste_checkbox').checked) {
                const byproductRows = document.querySelectorAll('.byproduct-row');
                byproductRows.forEach((row, index) => {
                    const name = row.querySelector(`input[name*="[product_name]"]`).value;
                    if (name) {
                        formDataObj.append(`wastage_recovery_by_products[${index}][product_item_id]`, row.querySelector('.product-id-input').value || '');
                        formDataObj.append(`wastage_recovery_by_products[${index}][product_name]`, name);
                        formDataObj.append(`wastage_recovery_by_products[${index}][quantity]`, row.querySelector(`input[name*="[quantity]"]`).value || 0);
                        formDataObj.append(`wastage_recovery_by_products[${index}][unit]`, row.querySelector(`select[name*="[unit]"]`).value || 'kg');

                        // NRV Data
                        formDataObj.append(`wastage_recovery_by_products[${index}][nrv_product_item_id]`, row.querySelector(`input[name*="[nrv_product_item_id]"]`).value || '');
                        formDataObj.append(`wastage_recovery_by_products[${index}][nrv_product_name]`, row.querySelector(`input[name*="[nrv_product_name]"]`).value || '');
                        formDataObj.append(`wastage_recovery_by_products[${index}][market_value]`, row.querySelector(`input[name*="[market_value]"]`).value || 0);
                        formDataObj.append(`wastage_recovery_by_products[${index}][processing_cost]`, row.querySelector(`input[name*="[processing_cost]"]`).value || 0);
                    }
                });
            }

            // Submit logic...
            fetch('/recipe-management', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formDataObj
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Recipe created successfully!');
                        // location.reload();
                        // Optionally close modal or clear form
                        closeCreateModal();
                        // Maybe refresh list?
                        location.reload();
                    } else {
                        console.error("Server Error:", data);
                        alert('Error creating recipe: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Check console for details.');
                });
        }

        function toggleAgeInput(checkbox, index) {
            // Find inputs relative to the checkbox if we passed 'this'
            // But since rows are dynamic, let's look for the input in the same row
            const row = checkbox.closest('.ingredient-row');
            const daysInput = row.querySelector('input[name*="[aged_days]"]');

            if (checkbox.checked) {
                daysInput.disabled = false;
                daysInput.classList.remove('bg-gray-100');
                daysInput.focus();
            } else {
                daysInput.disabled = true;
                daysInput.classList.add('bg-gray-100');
                daysInput.value = '';
            }
        }
    </script>

    {{-- Recipe Detail Modal --}}
    <div id="recipeDetailModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity duration-300 ease-out modal-backdrop"
            onclick="closeDetailModal()"></div>

        {{-- Modal Panel --}}
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                <div
                    class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl w-full">

                    {{-- Header --}}
                    <div id="detail-header"
                        class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
                        <div class="flex items-center gap-3">
                            <div id="detail-icon-container"
                                class="w-10 h-10 rounded-lg flex items-center justify-center bg-gradient-to-br from-[#F59E0B] to-[#D97706]">
                                <svg id="detail-icon-standard" class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                                <svg id="detail-icon-waste" class="w-5 h-5 text-white hidden" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h2 id="detail-recipe-name" class="text-gray-900 font-bold text-lg">Recipe Name</h2>
                                <p class="text-sm text-gray-500">Version <span id="detail-recipe-version">1.0</span></p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <span id="detail-badge-waste"
                                class="hidden bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold px-2.5 py-1 rounded-full items-center gap-1 shadow-sm">
                                <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                    </path>
                                </svg>
                                WASTE RECOVERY
                            </span>
                            <span id="detail-badge-category"
                                class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                Category
                            </span>
                            <button onclick="closeDetailModal()"
                                class="text-gray-400 hover:text-gray-500 transition-colors ml-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content Scroll Area --}}
                    <div class="p-6 max-h-[70vh] overflow-y-auto space-y-6">

                        {{-- Waste Processing Banner --}}
                        <div id="waste-processing-banner"
                            class="hidden bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-5 text-white shadow-md">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="flex items-center gap-2 mb-1 font-bold">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                            </path>
                                        </svg>
                                        Waste Processing Recipe
                                    </h3>
                                    <p class="text-sm text-green-50 mb-3 leading-relaxed">
                                        This recipe transforms waste/byproducts into valuable products, supporting the
                                        Three-Stage Waste Recovery System.
                                    </p>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div
                                            class="bg-white/10 rounded-lg px-3 py-2 backdrop-blur-sm border border-white/10">
                                            <div class="font-bold mb-0.5">♻️ Reduces Waste</div>
                                            <div class="text-green-100">Converts byproducts</div>
                                        </div>
                                        <div
                                            class="bg-white/10 rounded-lg px-3 py-2 backdrop-blur-sm border border-white/10">
                                            <div class="font-bold mb-0.5">💰 Lowers Costs</div>
                                            <div class="text-green-100">Uses free inputs</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="aspect-video rounded-xl overflow-hidden bg-gray-100 relative shadow-inner">
                            <img id="detail-recipe-image" src="" alt="Recipe" class="w-full h-full object-cover">
                            <div id="detail-recipe-placeholder"
                                class="absolute inset-0 flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        {{-- Quick Info Grid --}}
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-8 h-8 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </div>
                                <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Yield</div>
                                <div id="detail-recipe-yield" class="text-gray-900 font-bold mt-1">0 Units</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-8 h-8 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Prep Time</div>
                                <div id="detail-recipe-prep" class="text-gray-900 font-bold mt-1">45m</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-8 h-8 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="text-xs text-gray-500 font-medium uppercase tracking-wide">Cost/Unit</div>
                                <div id="detail-recipe-cost-unit" class="text-gray-900 font-bold mt-1">Rs. 0.00</div>
                            </div>
                        </div>

                        {{-- Ingredients Section --}}
                        <div>
                            <h3 id="detail-ingredients-title" class="text-gray-900 font-bold mb-3 flex items-center gap-2">
                                <span id="ingredients-icon-regular">📦</span>
                                <span id="ingredients-icon-waste" class="hidden">♻️</span>
                                <span id="ingredients-heading">Ingredients</span>
                            </h3>

                            <div id="detail-ingredients-container"
                                class="space-y-2 rounded-xl p-4 bg-gray-50 border border-gray-200">
                                {{-- Injected Ingredients --}}
                            </div>

                            {{-- Zero Cost Highlight --}}
                            <div id="zero-cost-highlight"
                                class="hidden mt-2 flex items-start gap-2 text-xs text-green-700 bg-green-50 rounded-lg p-3 border border-green-200">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span><strong>Zero-cost ingredients:</strong> Waste inputs have no material cost,
                                    significantly reducing production expenses.</span>
                            </div>
                        </div>

                        {{-- Instructions/Method --}}
                        <div>
                            <h3 id="detail-instructions-title" class="text-gray-900 font-bold mb-3 flex items-center gap-2">
                                <span>📝</span>
                                <span>Method / Instructions</span>
                            </h3>

                            <div id="detail-instructions-container"
                                class="space-y-0 rounded-xl overflow-hidden border border-gray-200">
                                {{-- Injected Instructions --}}
                            </div>
                        </div>

                        {{-- Cost Breakdown --}}
                        <div>
                            <h3 class="text-gray-900 font-bold mb-3 flex items-center gap-2">
                                💰 Cost Breakdown
                                <span id="nrv-tag"
                                    class="hidden text-[10px] text-green-700 bg-green-100 px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">with
                                    NRV</span>
                            </h3>

                            <div id="cost-breakdown-container"
                                class="space-y-3 rounded-xl p-4 border-2 border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50">
                                {{-- Dynamic Cost Content --}}
                            </div>
                        </div>

                    </div>

                    {{-- Footer Actions --}}
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center gap-3">
                        <button
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-[#F59E0B] to-[#D97706] hover:from-[#D97706] hover:to-[#B45309] text-white font-bold rounded-lg transition-all shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Recipe
                        </button>
                        <button
                            class="flex-1 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition-all shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                            Duplicate
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Charts
            initializeCharts();
        });

        function initializeCharts() {
            // Recipe Categories Pie Chart
            const ctxPie = document.getElementById('recipeCategoriesChart');
            if (ctxPie) {
                new Chart(ctxPie, {
                    type: 'doughnut',
                    data: {
                        labels: ['Bread', 'Pastry', 'Cakes'],
                        datasets: [{
                            data: [35, 25, 30],
                            backgroundColor: ['#F59E0B', '#EC4899', '#8B5CF6'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }

            // Cost Distribution Bar Chart
            const ctxBar = document.getElementById('costDistributionChart');
            if (ctxBar) {
                new Chart(ctxBar, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                        datasets: [{
                            label: 'Cost',
                            data: [12000, 19000, 3000, 5000, 2000],
                            backgroundColor: '#F59E0B',
                            borderRadius: 4,
                            barThickness: 20
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { display: false },
                                ticks: { display: false }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            }
        }

        // Modal Logic
        function viewRecipe(recipe) {
            const modal = document.getElementById('recipeDetailModal');
            if (!modal) return;

            // Determine if Waste Processing Recipe
            const isWaste = recipe.is_waste_processing_connection || recipe.name.toLowerCase().includes('pudding') || recipe.usage > 100; // Added usage logic as Mock for demo

            // --- Header & Theming ---
            const iconContainer = document.getElementById('detail-icon-container');
            const iconStandard = document.getElementById('detail-icon-standard');
            const iconWaste = document.getElementById('detail-icon-waste');
            const badgeWaste = document.getElementById('detail-badge-waste');
            const badgeCategory = document.getElementById('detail-badge-category');

            const wasteBanner = document.getElementById('waste-processing-banner');
            const ingredientsContainer = document.getElementById('detail-ingredients-container');
            const zeroCostHighlight = document.getElementById('zero-cost-highlight');
            const costContainer = document.getElementById('cost-breakdown-container');
            const nrvTag = document.getElementById('nrv-tag');
            const ingredientsHeading = document.getElementById('ingredients-heading');
            const iconRefWaste = document.getElementById('ingredients-icon-waste');
            const iconRefReg = document.getElementById('ingredients-icon-regular');

            if (isWaste) {
                // Apply Green Theme
                iconContainer.classList.remove('from-[#F59E0B]', 'to-[#D97706]');
                iconContainer.classList.add('from-green-500', 'to-emerald-600');

                iconStandard.classList.add('hidden');
                iconWaste.classList.remove('hidden');

                badgeWaste.classList.remove('hidden');

                wasteBanner.classList.remove('hidden');

                ingredientsContainer.className = "space-y-2 rounded-xl p-4 bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200";
                zeroCostHighlight.classList.remove('hidden');

                costContainer.className = "space-y-3 rounded-xl p-4 border-2 border-green-200 bg-gradient-to-br from-green-50 to-emerald-50";

                ingredientsHeading.textContent = "Waste/Byproduct Inputs";
                iconRefReg.classList.add('hidden');
                iconRefWaste.classList.remove('hidden');

            } else {
                // Apply Standard Amber Theme
                iconContainer.classList.add('from-[#F59E0B]', 'to-[#D97706]');
                iconContainer.classList.remove('from-green-500', 'to-emerald-600');

                iconStandard.classList.remove('hidden');
                iconWaste.classList.add('hidden');

                badgeWaste.classList.add('hidden');

                wasteBanner.classList.add('hidden');

                ingredientsContainer.className = "space-y-2 rounded-xl p-4 bg-gray-50 border border-gray-200";
                zeroCostHighlight.classList.add('hidden');

                costContainer.className = "space-y-3 rounded-xl p-4 border-2 border-amber-200 bg-gradient-to-br from-amber-50 to-orange-50";

                ingredientsHeading.textContent = "Ingredients";
                iconRefReg.classList.remove('hidden');
                iconRefWaste.classList.add('hidden');
            }

            // --- Populate Basic Info ---
            document.getElementById('detail-recipe-name').textContent = recipe.name || 'Unknown Recipe';
            document.getElementById('detail-recipe-yield').textContent = (recipe.yield || '1') + ' Batch';
            document.getElementById('detail-recipe-prep').textContent = recipe.prep_time || '40m';
            document.getElementById('detail-recipe-cost-unit').textContent = 'Rs. ' + (recipe.cost_per_unit || '0.00');

            badgeCategory.textContent = recipe.category || 'Bakery';
            badgeCategory.className = isWaste
                ? 'bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider'
                : 'bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wider';

            // --- Image Handling ---
            const img = document.getElementById('detail-recipe-image');
            const placeholder = document.getElementById('detail-recipe-placeholder');
            if (recipe.image_paths && recipe.image_paths.length > 0) {
                img.src = '/storage/' + recipe.image_paths[0];
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }

            // --- Populate Ingredients ---
            ingredientsContainer.innerHTML = '';

            const ingredients = recipe.ingredients || [
                { name: 'Flour', quantity: '500g', cost: 45.00 },
                { name: 'Sugar', quantity: '200g', cost: 12.50 },
                { name: 'Eggs', quantity: '4 pcs', cost: 60.00 }
            ];

            ingredients.forEach(ing => {
                const row = document.createElement('div');
                row.className = "flex items-center justify-between text-sm";

                if (isWaste) {
                    // Waste Style Ingredient Row
                    row.innerHTML = `
                            <span class="text-gray-700 flex items-center gap-2">
                                 <span class="text-green-600">♻️</span>
                                 ${ing.name || 'Waste Input'}
                            </span>
                            <span class="text-gray-900 font-bold">${ing.quantity || '0'}</span>
                        `;
                } else {
                    // Regular Style
                    row.innerHTML = `
                            <span class="text-gray-700">${ing.name || 'Ingredient'}</span>
                            <span class="text-gray-900 font-medium">${ing.quantity || '0'}</span>
                        `;
                }
                ingredientsContainer.appendChild(row);
            });

            // --- Populate Instructions ---
            const instructionsContainer = document.getElementById('detail-instructions-container');
            instructionsContainer.innerHTML = '';

            const instructions = recipe.instructions || [];

            // Sort instructions by step_number
            instructions.sort((a, b) => (a.step_number || 0) - (b.step_number || 0));

            if (instructions.length > 0) {
                const table = document.createElement('table');
                table.className = "w-full text-sm text-left";
                table.innerHTML = `
                       <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                           <tr>
                               <th scope="col" class="px-4 py-3 w-16 text-center">Step</th>
                               <th scope="col" class="px-4 py-3">Instruction</th>
                           </tr>
                       </thead>
                       <tbody class="divide-y divide-gray-100 bg-white">
                       </tbody>
                   `;

                const tbody = table.querySelector('tbody');

                instructions.forEach(inst => {
                    const stepRow = document.createElement('tr');
                    stepRow.className = "hover:bg-gray-50 transition-colors";
                    stepRow.innerHTML = `
                           <td class="px-4 py-3 text-center font-bold text-gray-400">
                               <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-gray-600 text-xs">${inst.step_number}</span>
                           </td>
                           <td class="px-4 py-3 text-gray-700 leading-relaxed">${inst.step_description}</td>
                       `;
                    tbody.appendChild(stepRow);
                });

                instructionsContainer.appendChild(table);
            } else {
                instructionsContainer.innerHTML = `
                        <div class="text-center py-6 text-gray-400 bg-gray-50">
                            <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            <span class="text-sm">No instructions available for this recipe.</span>
                        </div>
                    `;
            }

            // --- Build Cost Breakdown ---
            costContainer.innerHTML = '';

            if (isWaste) {
                // Waste Processing Cost Layout
                costContainer.innerHTML = `
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg p-3 text-white shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-green-100 uppercase tracking-wider font-bold">Waste Input Material</div>
                                        <div class="text-sm font-bold">Bread/Cake Trimmings</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] text-green-100 uppercase tracking-wider font-bold">Material Cost</div>
                                    <div class="text-lg font-bold">Rs. 0.00</div>
                                </div>
                            </div>
                             <div class="bg-white/10 rounded px-2 py-1.5 text-[10px] text-green-50 flex items-center gap-1 font-medium">
                                <span class="bg-white/20 rounded-full p-0.5"><svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                                Zero-cost input: This waste material has no purchase cost
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-3 border border-green-200">
                            <div class="text-xs text-gray-500 mb-2 font-bold uppercase tracking-wider">Processing Costs</div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">Additional Materials</span>
                                    <span class="text-gray-900 font-medium">Rs. 15.50</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">Overhead Costs</span>
                                    <span class="text-gray-900 font-medium">Rs. 12.00</span>
                                </div>
                                 <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">Labor Costs</span>
                                    <span class="text-gray-900 font-medium">Rs. 25.00</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-3 border-2 border-green-300 shadow-sm">
                             <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-900 font-bold">Total Cost Per Unit</span>
                                <span class="text-2xl font-bold text-green-600">Rs. ${(recipe.cost_per_unit || 52.50)}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-green-800 bg-green-50 rounded-lg px-3 py-2 border border-green-100">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                <span><strong>93% lower</strong> material cost vs. similar products</span>
                            </div>
                        </div>
                     `;
            } else {
                // Regular Recipe Cost Layout
                const hasNRV = recipe.id % 2 !== 0; // Mock logic for NRV presence
                if (hasNRV) nrvTag.classList.remove('hidden'); else nrvTag.classList.add('hidden');

                costContainer.innerHTML = `
                        <div class="bg-white rounded-lg p-3 border border-amber-200">
                            <div class="flex items-center justify-between text-sm mb-1">
                                <span class="text-gray-700 font-bold">Ingredient Costs</span>
                                <span class="text-gray-900 font-bold">Rs. 185.50</span>
                            </div>
                             <div class="text-xs text-gray-500">Raw materials for production</div>
                        </div>

                        ${hasNRV ? `
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg p-3 text-white shadow-sm">
                            <div class="flex items-center justify-between mb-2">
                                 <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-green-100 uppercase tracking-wider font-bold">Byproduct Recovery</div>
                                        <div class="text-sm font-bold">Trimmings (500g)</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] text-green-100 uppercase tracking-wider font-bold">NRV Savings</div>
                                    <div class="font-bold text-lg">-Rs. 3.50</div>
                                </div>
                            </div>
                            <div class="bg-white/10 rounded px-2 py-1.5 text-[10px] text-green-50 font-medium">
                                Market Value: Rs. 5.00 | Cost: Rs. 1.50 | Net: Rs. 3.50
                            </div>
                        </div>
                        ` : ''}

                        <div class="space-y-1 bg-white p-3 rounded-lg border border-gray-100">
                              <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Overhead Costs</span>
                                <span class="text-gray-900 font-medium">Rs. 45.00</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Labor Costs</span>
                                <span class="text-gray-900 font-medium">Rs. 120.00</span>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-3 border-2 border-amber-300 shadow-sm">
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center justify-between text-gray-600">
                                    <span>Total Direct Costs</span>
                                    <span>Rs. 350.50</span>
                                </div>
                                 ${hasNRV ? `
                                <div class="flex items-center justify-between text-green-600 font-bold bg-green-50 px-2 py-1 rounded">
                                    <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> Byproduct NRV</span>
                                    <span>-Rs. 3.50</span>
                                </div>
                                ` : ''}
                                <div class="h-px bg-amber-200 my-1"></div>
                                <div class="flex items-center justify-between text-gray-900 font-bold">
                                    <span>Net Cost Per Unit</span>
                                    <span class="text-lg">Rs. ${(recipe.cost_per_unit || 347.00)}</span>
                                </div>
                            </div>
                             ${hasNRV ? `
                            <div class="flex items-center gap-2 text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2 mt-3 border border-green-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                <span><strong>Rs. 3.50 savings</strong> from waste recovery (28% reduction)</span>
                            </div>
                             ` : ''}
                        </div>
                    `;
            }

            // Show Modal
            modal.classList.remove('hidden');
        }

        function closeDetailModal() {
            const modal = document.getElementById('recipeDetailModal');
            if (modal) modal.classList.add('hidden');
        }
    </script>
@endsection