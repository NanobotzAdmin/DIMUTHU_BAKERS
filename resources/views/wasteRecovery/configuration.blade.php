@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1600px] mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-900">
            <i class="bi bi-leaf text-green-600 text-3xl"></i>
            Waste Recovery Configuration
        </h1>
        <p class="text-gray-600 mt-1">
            Configure three-stage waste recovery system with NRV accounting
        </p>
    </div>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Card 1 --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="pb-2">
                <p class="text-sm text-gray-500 font-medium">Recovery Methods</p>
                <h3 class="text-3xl font-bold text-green-600">
                    {{ $stats->activeRecoveryMethods }}/{{ $stats->totalRecoveryMethods }}
                </h3>
            </div>
            <div>
                <p class="text-sm text-gray-600">Active methods</p>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="pb-2">
                <p class="text-sm text-gray-500 font-medium">Product Profiles</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ $stats->activeProductProfiles }}</h3>
            </div>
            <div>
                <p class="text-sm text-gray-600">Configured products</p>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="pb-2">
                <p class="text-sm text-gray-500 font-medium">Avg Shelf Life</p>
                <h3 class="text-3xl font-bold text-orange-600">{{ number_format($stats->averageShelfLife, 1) }} days</h3>
            </div>
            <div>
                <p class="text-sm text-gray-600">Across all products</p>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <div class="pb-2">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">System Status</p>
                        <h3 class="text-2xl mt-1">
                            @if($stats->configActive)
                            <span class="bg-green-100 text-green-800 text-lg px-3 py-1 rounded-full inline-flex items-center">
                                <i class="bi bi-check-circle mr-1"></i> Active
                            </span>
                            @else
                            <span class="bg-red-100 text-red-800 text-lg px-3 py-1 rounded-full inline-flex items-center">
                                <i class="bi bi-exclamation-circle mr-1"></i> Inactive
                            </span>
                            @endif
                        </h3>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600">Configuration status</p>
            </div>
        </div>
    </div>

    {{-- Three-Stage Model Overview --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
        <div class="p-6 border-b border-blue-200/50">
            <h3 class="text-xl font-semibold text-blue-900">Three-Stage Waste Recovery Model</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Stage 1 --}}
                <div class="bg-white rounded-lg p-4 border-2 border-green-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                        <h3 class="font-semibold text-green-900">FRESH PRODUCTS</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p><strong>Age:</strong> 0-<span id="display-fresh-days">{{ $config->freshProductDays ?? 1 }}</span> days</p>
                        <p><strong>Price:</strong> Full price (100%)</p>
                        <p><strong>Cost Basis:</strong> Standard cost</p>
                        <p><strong>GL Account:</strong> 1500 - Finished Goods</p>
                        <p><strong>Action:</strong> Sell at regular price</p>
                    </div>
                </div>

                {{-- Stage 2 --}}
                <div class="bg-white rounded-lg p-4 border-2 border-orange-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-orange-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
                        <h3 class="font-semibold text-orange-900">DAY-OLD PRODUCTS</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p><strong>Age:</strong> <span id="display-fresh-days-end">{{ $config->freshProductDays ?? 1 }}</span>-<span id="display-day-old-days">{{ $config->dayOldDays ?? 2 }}</span> days</p>
                        <p><strong>Price:</strong> Discounted (<span id="display-day-old-discount">{{ $config->dayOldPricePercent ?? 40 }}</span>%)</p>
                        <p><strong>Cost Basis:</strong> NRV (market value)</p>
                        <p><strong>GL Account:</strong> 1510 - Day-Old Products</p>
                        <p><strong>Action:</strong> Sell at discount</p>
                        <p class="text-orange-700"><strong>JE:</strong> NRV write-down recorded</p>
                    </div>
                </div>

                {{-- Stage 3 --}}
                <div class="bg-white rounded-lg p-4 border-2 border-red-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center font-bold">3</div>
                        <h3 class="font-semibold text-red-900">WASTE RECOVERY</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p><strong>Age:</strong> <span id="display-waste-days">{{ $config->wasteThresholdDays ?? 3 }}</span>+ days</p>
                        <p><strong>Price:</strong> Recovery value (<span id="display-waste-percent">{{ $config->wasteRecoveryPercent ?? 10 }}</span>%)</p>
                        <p><strong>Cost Basis:</strong> Recovery NRV</p>
                        <p><strong>GL Account:</strong> 1340 - Waste Inventory</p>
                        <p><strong>Action:</strong> Process for recovery</p>
                        <p class="text-red-700"><strong>JE:</strong> Waste loss recorded</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- General Configuration --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <form id="configForm">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">General Configuration</h3>
                    <p class="text-sm text-gray-500">Stage thresholds and NRV percentages</p>
                </div>
                <div class="flex gap-2">
                    {{-- Edit Button --}}
                    <button 
                        id="editBtn"
                        type="button"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center transition-colors duration-200"
                    >
                        <i class="bi bi-gear mr-2"></i> Edit
                    </button>

                    {{-- Save/Cancel Buttons --}}
                    <div id="actionBtns" class="flex gap-2 hidden">
                        <button 
                            id="cancelBtn"
                            type="button"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                        >
                            Cancel
                        </button>
                        <button 
                            id="saveBtn"
                            type="button"
                            class="px-4 py-2 bg-[#D4A017] border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017] flex items-center transition-colors duration-200"
                        >
                            <i class="bi bi-save mr-2"></i> Save
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Stage Thresholds --}}
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900">Stage Thresholds (Days)</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fresh Product Days</label>
                            <input
                                type="number"
                                name="freshProductDays"
                                id="freshProductDays"
                                value="{{ $config->freshProductDays }}"
                                disabled
                                class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors duration-200 bg-gray-100 text-gray-500"
                            />
                            <p class="text-xs text-gray-600 mt-1">Products are fresh for this many days</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Day-Old Product Days</label>
                            <input
                                type="number"
                                name="dayOldDays"
                                id="dayOldDays"
                                value="{{ $config->dayOldDays }}"
                                disabled
                                class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors duration-200 bg-gray-100 text-gray-500"
                            />
                            <p class="text-xs text-gray-600 mt-1">Products sold as day-old until this day</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waste Threshold Days</label>
                            <input
                                type="number"
                                name="wasteThresholdDays"
                                id="wasteThresholdDays"
                                value="{{ $config->wasteThresholdDays }}"
                                disabled
                                class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors duration-200 bg-gray-100 text-gray-500"
                            />
                            <p class="text-xs text-gray-600 mt-1">Products become waste after this day</p>
                        </div>
                    </div>

                    {{-- NRV Percentages --}}
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900">NRV Percentages</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Day-Old Price % (of original)</label>
                            <input
                                type="number"
                                name="dayOldPricePercent"
                                id="dayOldPricePercent"
                                value="{{ $config->dayOldPricePercent }}"
                                disabled
                                class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors duration-200 bg-gray-100 text-gray-500"
                            />
                            <p class="text-xs text-gray-600 mt-1">
                                Day-old products sold at <span id="calc-day-old-percent">{{ $config->dayOldPricePercent }}</span>% of original price
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Waste Recovery % (of original cost)</label>
                            <input
                                type="number"
                                name="wasteRecoveryPercent"
                                id="wasteRecoveryPercent"
                                value="{{ $config->wasteRecoveryPercent }}"
                                disabled
                                class="mt-1 block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-colors duration-200 bg-gray-100 text-gray-500"
                            />
                            <p class="text-xs text-gray-600 mt-1">
                                Waste valued at <span id="calc-waste-percent">{{ $config->wasteRecoveryPercent }}</span>% of original cost
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="font-semibold mb-2 text-sm text-gray-900">Example Calculation:</h4>
                            <div class="text-sm space-y-1 text-gray-700">
                                <p>Original Price: Rs. 75 | Original Cost: Rs. 45</p>
                                <p class="text-orange-700">
                                    Day-Old Price: Rs. <span id="ex-day-old-price"></span> 
                                    (<span id="ex-day-old-pct"></span>% of Rs. 75)
                                </p>
                                <p class="text-orange-700">
                                    Day-Old NRV: Rs. <span id="ex-day-old-nrv"></span>
                                </p>
                                <p class="text-red-700">
                                    Waste Recovery Value: Rs. <span id="ex-waste-val"></span> 
                                    (<span id="ex-waste-pct"></span>% of Rs. 45)
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Auto-Processing Settings --}}
                    <div class="space-y-4 md:col-span-2">
                        <h3 class="font-semibold text-gray-900">Auto-Processing Settings</h3>
                        
                        <div class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                name="autoTransferToWaste"
                                id="autoTransferToWaste"
                                disabled
                                {{ $config->autoTransferToWaste ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded disabled:opacity-50"
                            />
                            <label for="autoTransferToWaste" class="text-sm text-gray-700 cursor-pointer">
                                Auto-transfer products to waste stage (recommended: disabled for manual control)
                            </label>
                        </div>

                        <div class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                name="autoCalculateNRV"
                                id="autoCalculateNRV"
                                disabled
                                {{ $config->autoCalculateNRV ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded disabled:opacity-50"
                            />
                            <label for="autoCalculateNRV" class="text-sm text-gray-700 cursor-pointer">
                                Auto-calculate NRV adjustments
                            </label>
                        </div>

                        <div class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                name="requireApprovalForDisposal"
                                id="requireApprovalForDisposal"
                                disabled
                                {{ $config->requireApprovalForDisposal ? 'checked' : '' }}
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded disabled:opacity-50"
                            />
                            <label for="requireApprovalForDisposal" class="text-sm text-gray-700 cursor-pointer">
                                Require approval for waste disposal
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Recovery Methods --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900">Recovery Methods</h3>
            <p class="text-sm text-gray-500">
                Configure waste recovery options and their financial parameters
            </p>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($recoveryMethods as $method)
                <div class="border border-gray-200 rounded-lg p-4" id="method-card-{{ $method->id }}">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-lg text-gray-900">{{ $method->name }}</h3>
                                <span 
                                    class="method-status-badge px-2.5 py-0.5 rounded-full text-xs font-medium {{ $method->isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}"
                                >
                                    {{ $method->isActive ? 'Active' : 'Inactive' }}
                                </span>
                                @if($method->requiresApproval)
                                    <span class="border border-gray-200 text-gray-600 px-2.5 py-0.5 rounded-full text-xs font-medium">Requires Approval</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600">{{ $method->description }}</p>
                        </div>
                        <button
                            onclick="toggleMethodActive('{{ $method->method }}', '{{ $method->name }}', this)"
                            data-active="{{ $method->isActive ? 'true' : 'false' }}"
                            class="method-toggle-btn inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            {{ $method->isActive ? 'Disable' : 'Enable' }}
                        </button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 rounded p-3">
                            <p class="text-xs text-gray-600 mb-1">NRV per Kg</p>
                            <p class="font-semibold text-green-700">Rs. {{ number_format($method->nrvPerKg, 2) }}/kg</p>
                        </div>

                        <div class="bg-gray-50 rounded p-3">
                            <p class="text-xs text-gray-600 mb-1">Processing Cost</p>
                            <p class="font-semibold text-red-700">Rs. {{ number_format($method->processingCostPerKg, 2) }}/kg</p>
                        </div>

                        <div class="bg-gray-50 rounded p-3">
                            <p class="text-xs text-gray-600 mb-1">Net Recovery</p>
                            @php $net = $method->nrvPerKg - $method->processingCostPerKg; @endphp
                            <p 
                                class="font-semibold {{ $net >= 0 ? 'text-green-700' : 'text-red-700' }}"
                            >
                                Rs. {{ number_format($net, 2) }}/kg
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded p-3">
                            <p class="text-xs text-gray-600 mb-1">CO₂ Offset</p>
                            <p class="font-semibold text-blue-700">{{ $method->co2OffsetPerKg ? $method->co2OffsetPerKg . 'kg/kg' : 'N/A' }}</p>
                        </div>
                    </div>

                    @if(!empty($method->environmentalBenefit))
                        <div class="mt-3 bg-green-50 border border-green-200 rounded p-3">
                            <p class="text-sm text-green-800">
                                <i class="bi bi-leaf mr-1"></i>
                                <strong>Environmental Benefit:</strong> {{ $method->environmentalBenefit }}
                            </p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Recovery Method Comparison --}}
            <div class="mt-6">
                <h3 class="font-semibold mb-3 text-gray-900">Recovery Method Comparison</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue/kg</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cost/kg</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net/kg</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($methodComparison as $m)
                            <tr class="hover:bg-gray-50" id="comparison-row-{{ $m->method }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $m->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-700">Rs. {{ number_format($m->nrvPerKg, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-700">Rs. {{ number_format($m->costPerKg, 2) }}</td>
                                <td 
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold {{ $m->netPerKg >= 0 ? 'text-green-700' : 'text-red-700' }}"
                                >
                                    Rs. {{ number_format($m->netPerKg, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span 
                                        class="comparison-status-badge px-2.5 py-0.5 rounded-full text-xs font-medium {{ $m->isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}"
                                    >
                                        {{ $m->isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Waste Profiles --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Product Waste Profiles</h3>
                <p class="text-sm text-gray-500">Configure waste parameters for each product</p>
            </div>
            <button class="px-4 py-2 bg-[#D4A017] border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017] flex items-center">
                <i class="bi bi-box-seam mr-2"></i> Add Product
            </button>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Shelf Life</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Original Price</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Day-Old Price</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Waste Value</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recovery Method</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($productProfiles as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $product->productName }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->productCode }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border border-gray-300 bg-white text-gray-700 shadow-sm">
                                    {{ $product->shelfLifeDays }} days
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">Rs. {{ number_format($product->originalSellingPrice, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-orange-700">
                                Rs. {{ number_format($product->dayOldSellingPrice, 2) }}
                                <span class="text-xs text-gray-500 ml-1">({{ round(($product->dayOldSellingPrice / $product->originalSellingPrice) * 100) }}%)</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-700">Rs. {{ number_format($product->wasteRecoveryValue, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $product->preferredRecoveryMethod }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span 
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}"
                                >
                                    {{ $product->isActive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Help Card --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
        <div class="p-6 border-b border-blue-200/50">
            <h3 class="text-xl font-semibold text-blue-900">Configuration Guide</h3>
        </div>
        <div class="p-6 text-sm text-blue-900 space-y-3">
            <div>
                <p class="font-semibold">Stage Thresholds:</p>
                <p class="text-blue-700 mt-1">
                    Define when products move from fresh → day-old → waste based on age in days
                </p>
            </div>
            <div>
                <p class="font-semibold">NRV Percentages:</p>
                <p class="text-blue-700 mt-1">
                    Day-old price % determines discount pricing. Waste recovery % sets the expected recovery value.
                </p>
            </div>
            <div>
                <p class="font-semibold">Recovery Methods:</p>
                <p class="text-blue-700 mt-1">
                    Choose best method: Animal Feed (best net recovery), Compost (environmental), Bio-Gas (requires infrastructure), Disposal (last resort)
                </p>
            </div>
            <div>
                <p class="font-semibold">Product Profiles:</p>
                <p class="text-blue-700 mt-1">
                    Each product can have custom shelf life and recovery settings. System auto-calculates NRV based on global percentages.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('configForm');
        const editBtn = document.getElementById('editBtn');
        const actionBtns = document.getElementById('actionBtns');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveBtn = document.getElementById('saveBtn');
        const inputs = form.querySelectorAll('input');

        // Elements for calculations
        const els = {
            freshDays: document.getElementById('freshProductDays'),
            dayOldDays: document.getElementById('dayOldDays'),
            wasteDays: document.getElementById('wasteThresholdDays'),
            dayOldPct: document.getElementById('dayOldPricePercent'),
            wastePct: document.getElementById('wasteRecoveryPercent'),
            // Display elements
            dispFreshDays: document.getElementById('display-fresh-days'),
            dispFreshDaysEnd: document.getElementById('display-fresh-days-end'),
            dispDayOldDays: document.getElementById('display-day-old-days'),
            dispDayOldDisc: document.getElementById('display-day-old-discount'),
            dispWasteDays: document.getElementById('display-waste-days'),
            dispWastePct: document.getElementById('display-waste-percent'),
            calcDayOldPct: document.getElementById('calc-day-old-percent'),
            calcWastePct: document.getElementById('calc-waste-percent'),
            // Example calc
            exDayOldPrice: document.getElementById('ex-day-old-price'),
            exDayOldPct: document.getElementById('ex-day-old-pct'),
            exDayOldNrv: document.getElementById('ex-day-old-nrv'),
            exWasteVal: document.getElementById('ex-waste-val'),
            exWastePct: document.getElementById('ex-waste-pct')
        };

        // Initialize calculations
        updateCalculations();

        // Event Listeners for Edit Mode
        editBtn.addEventListener('click', function() {
            enableEdit(true);
        });

        cancelBtn.addEventListener('click', function() {
            form.reset();
            enableEdit(false);
            updateCalculations(); // Reset stats display
        });

        saveBtn.addEventListener('click', function() {
            // Mock Save
            enableEdit(false);
            Swal.fire({
                icon: 'success',
                title: 'Configuration Updated',
                text: 'Configuration updated successfully',
                timer: 2000,
                showConfirmButton: false
            });
        });

        // Event Listeners for Live Calculations
        inputs.forEach(input => {
            input.addEventListener('input', updateCalculations);
        });

        function enableEdit(enable) {
            editBtn.style.display = enable ? 'none' : 'flex';
            actionBtns.style.display = enable ? 'flex' : 'none';
            inputs.forEach(input => {
                input.disabled = !enable;
                if(enable) {
                    input.classList.remove('bg-gray-100', 'text-gray-500');
                    input.classList.add('bg-white', 'text-gray-900');
                } else {
                    input.classList.add('bg-gray-100', 'text-gray-500');
                    input.classList.remove('bg-white', 'text-gray-900');
                }
            });
        }

        function updateCalculations() {
            // Get values
            const v = {
                fresh: els.freshDays.value || 0,
                dayOld: els.dayOldDays.value || 0,
                waste: els.wasteDays.value || 0,
                doPct: els.dayOldPct.value || 0,
                wPct: els.wastePct.value || 0
            };

            // Update Three-Stage Model Text using textContent
            els.dispFreshDays.textContent = v.fresh;
            els.dispFreshDaysEnd.textContent = v.fresh;
            els.dispDayOldDays.textContent = v.dayOld;
            els.dispDayOldDisc.textContent = v.doPct;
            els.dispWasteDays.textContent = v.waste;
            els.dispWastePct.textContent = v.wPct;

            // Update NRV Section Text
            els.calcDayOldPct.textContent = v.doPct;
            els.calcWastePct.textContent = v.wPct;

            // Update Example Calculation
            const exPrice = 75;
            const exCost = 45;
            const doPrice = (exPrice * v.doPct) / 100;
            const doCostReduced = (exCost * v.doPct) / 100;
            const doNrv = Math.min(doPrice, doCostReduced);
            const wVal = (exCost * v.wPct) / 100;

            els.exDayOldPrice.textContent = doPrice.toFixed(0);
            els.exDayOldPct.textContent = v.doPct;
            els.exDayOldNrv.textContent = doNrv.toFixed(0);
            els.exWasteVal.textContent = wVal.toFixed(0);
            els.exWastePct.textContent = v.wPct;
        }

        // Global function for toggle method to access
        window.toggleMethodActive = function(code, name, btn) {
            const isActive = btn.getAttribute('data-active') === 'true';
            const newState = !isActive;
            
            // Update button state
            btn.setAttribute('data-active', newState);
            btn.textContent = newState ? 'Disable' : 'Enable';
            
            // Update Card Badge
            // Note: We need the element. The button is inside the card.
            // Closest .border container is the card.
            const card = btn.closest('.border');
            if(card) {
                const badge = card.querySelector('.method-status-badge');
                updateBadge(badge, newState);
            }

            // Update Comparison Table using ID targeting
            const row = document.getElementById('comparison-row-' + code);
            if(row) {
                const statusSpan = row.querySelector('.comparison-status-badge');
                updateBadge(statusSpan, newState);
            }

            Swal.fire({
                icon: 'success',
                title: newState ? 'Method Enabled' : 'Method Disabled',
                text: `${name} ${newState ? 'enabled' : 'disabled'}`,
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        };

        function updateBadge(el, active) {
            if(!el) return;
            el.textContent = active ? 'Active' : 'Inactive';
            if (active) {
                el.classList.remove('bg-gray-100', 'text-gray-800');
                el.classList.add('bg-green-100', 'text-green-800');
            } else {
                el.classList.remove('bg-green-100', 'text-green-800');
                el.classList.add('bg-gray-100', 'text-gray-800');
            }
        }
    });
</script>
@endsection