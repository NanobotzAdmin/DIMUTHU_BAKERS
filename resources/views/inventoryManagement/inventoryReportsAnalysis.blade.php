@extends('layouts.app')

@section('content')
@php
    // --- STATE MANAGEMENT (React setActiveTab equivalent) ---
    // --- STATE MANAGEMENT (React setActiveTab equivalent) ---
    // $activeTab logic moved to Alpine.js


    // --- DUMMY DATA (React inventoryValuationStore equivalent) ---
    
    // Inventory Items
    $inventoryItems = collect([
        (object)[
            'id' => 1, 'itemCode' => 'RAW-001', 'itemName' => 'Organic Flour', 'categoryType' => 'raw-materials',
            'quantityOnHand' => 500, 'unitOfMeasure' => 'kg', 'averageCost' => 2.50, 'totalValue' => 1250.00,
            'locationName' => 'Warehouse A', 'valuationLayers' => [1, 2], 'daysInInventory' => 15, 
            'agingBucket' => '0-30', 'lastReceiptDate' => '2023-12-01', 'nrvAdjustmentNeeded' => 0
        ],
        (object)[
            'id' => 2, 'itemCode' => 'FIN-992', 'itemName' => 'Artisan Sourdough', 'categoryType' => 'finished-goods',
            'quantityOnHand' => 120, 'unitOfMeasure' => 'units', 'averageCost' => 5.00, 'totalValue' => 600.00,
            'locationName' => 'Cold Storage', 'valuationLayers' => [1], 'daysInInventory' => 95, 
            'agingBucket' => '91-180', 'lastReceiptDate' => '2023-09-15', 'nrvAdjustmentNeeded' => 50.00
        ],
        (object)[
            'id' => 3, 'itemCode' => 'PKG-042', 'itemName' => 'Eco Boxes', 'categoryType' => 'packaging',
            'quantityOnHand' => 1000, 'unitOfMeasure' => 'pcs', 'averageCost' => 0.45, 'totalValue' => 450.00,
            'locationName' => 'Warehouse B', 'valuationLayers' => [1, 2, 3], 'daysInInventory' => 45, 
            'agingBucket' => '31-60', 'lastReceiptDate' => '2023-11-10', 'nrvAdjustmentNeeded' => 0
        ],
    ]);

    // Totals for Summary Cards
    $totalValue = $inventoryItems->sum('totalValue');
    $categoryTotals = [
        'raw-materials' => $inventoryItems->where('categoryType', 'raw-materials')->sum('totalValue'),
        'finished-goods' => $inventoryItems->where('categoryType', 'finished-goods')->sum('totalValue'),
        'packaging' => $inventoryItems->where('categoryType', 'packaging')->sum('totalValue'),
        'semi-finished' => $inventoryItems->where('categoryType', 'semi-finished')->sum('totalValue'),
        'resale-items' => $inventoryItems->where('categoryType', 'resale-items')->sum('totalValue'),
        'waste' => $inventoryItems->where('categoryType', 'waste')->sum('totalValue'),
    ];

    // Variance Data
    $priceVariances = collect([
        (object)[
            'id' => 101, 'transactionDate' => '2023-12-10', 'itemName' => 'Yeast Bulk', 'itemCode' => 'RAW-005',
            'supplierName' => 'Global Ingredients', 'quantity' => 100, 'poUnitPrice' => 10.00, 
            'invoiceUnitPrice' => 10.50, 'totalPriceVariance' => -50.00, 'varianceType' => 'unfavorable'
        ],
        (object)[
            'id' => 102, 'transactionDate' => '2023-12-12', 'itemName' => 'Sugar Bag', 'itemCode' => 'RAW-008',
            'supplierName' => 'SweetCorp', 'quantity' => 50, 'poUnitPrice' => 5.00, 
            'invoiceUnitPrice' => 4.80, 'totalPriceVariance' => 10.00, 'varianceType' => 'favorable'
        ]
    ]);

    $varianceStats = (object)[
        'netVariance' => $priceVariances->sum('totalPriceVariance'),
        'totalFavorable' => $priceVariances->where('varianceType', 'favorable')->sum('totalPriceVariance'),
        'favorableCount' => $priceVariances->where('varianceType', 'favorable')->count(),
        'totalUnfavorable' => abs($priceVariances->where('varianceType', 'unfavorable')->sum('totalPriceVariance')),
        'unfavorableCount' => $priceVariances->where('varianceType', 'unfavorable')->count(),
    ];

    // Supplier Data
    $suppliers = collect([
        (object)[
            'supplierId' => 1, 'supplierName' => 'Global Ingredients', 'supplierType' => 'Raw Materials',
            'performanceScore' => 88, 'rating' => 'good', 'totalPurchaseValue' => 15000.00, 
            'totalPurchaseOrders' => 24, 'onTimeDeliveryRate' => 92.5, 'onTimeDeliveries' => 22, 
            'lateDeliveries' => 2, 'rejectionRate' => 1.2, 'rejectedGRNs' => 1, 'totalPriceVariance' => 450.00
        ],
        (object)[
            'supplierId' => 2, 'supplierName' => 'SweetCorp', 'supplierType' => 'Additives',
            'performanceScore' => 95, 'rating' => 'excellent', 'totalPurchaseValue' => 8200.00, 
            'totalPurchaseOrders' => 12, 'onTimeDeliveryRate' => 100, 'onTimeDeliveries' => 12, 
            'lateDeliveries' => 0, 'rejectionRate' => 0, 'rejectedGRNs' => 0, 'totalPriceVariance' => -120.00
        ]
    ]);

    // Reconciliation Data
    $latestReconciliation = (object)[
        'period' => '2023-12', 'reconciliationDate' => now(), 'performedBy' => 'Admin User', 'isReconciled' => false,
        'subLedgerRawMaterials' => 12500.00, 'glRawMaterials' => 12500.00, 'varianceRawMaterials' => 0,
        'subLedgerPackaging' => 3000.00, 'glPackaging' => 3200.00, 'variancePackaging' => -200.00,
        'subLedgerSemiFinished' => 5000.00, 'glSemiFinished' => 5000.00, 'varianceSemiFinished' => 0,
        'subLedgerFinishedGoods' => 45000.00, 'glFinishedGoods' => 45000.00, 'varianceFinishedGoods' => 0,
        'subLedgerResaleItems' => 0, 'glResaleItems' => 0, 'varianceResaleItems' => 0,
        'subLedgerWaste' => 150.00, 'glWaste' => 150.00, 'varianceWaste' => 0,
        'subLedgerTotal' => 65650.00, 'glTotal' => 65850.00, 'varianceTotal' => -200.00,
        'varianceReason' => 'Unposted goods receipt for packaging material (PO#882) caused sub-ledger mismatch.'
    ];

    // Helper function for currency (financeStore.formatCurrency)
    function formatCurrency($value) {
        return 'Rs. ' . number_format($value, 2);
    }
@endphp

<div class="p-6 max-w-[1600px] mx-auto space-y-6 bg-gray-50 min-h-screen">
    <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#D4A017" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Inventory Reports & Analysis</h1>
            <p class="text-gray-600">Inventory valuation, aging analysis, GL reconciliation, and supplier performance</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 font-medium">Total Inventory Value</p>
            <h3 class="text-3xl font-bold text-blue-600 mt-1">{{ formatCurrency($totalValue) }}</h3>
            <p class="text-xs text-gray-400 mt-2">{{ $inventoryItems->count() }} items</p>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 font-medium">Raw Materials</p>
            <h3 class="text-2xl font-bold text-green-600 mt-1">{{ formatCurrency($categoryTotals['raw-materials'] ?? 0) }}</h3>
            <p class="text-xs text-gray-400 mt-2">{{ $inventoryItems->where('categoryType', 'raw-materials')->count() }} items</p>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 font-medium">Finished Goods</p>
            <h3 class="text-2xl font-bold text-purple-600 mt-1">{{ formatCurrency($categoryTotals['finished-goods'] ?? 0) }}</h3>
            <p class="text-xs text-gray-400 mt-2">{{ $inventoryItems->where('categoryType', 'finished-goods')->count() }} items</p>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 font-medium">Price Variance (Net)</p>
            <h3 class="text-2xl font-bold mt-1 {{ $varianceStats->netVariance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ formatCurrency(abs($varianceStats->netVariance)) }}
            </h3>
            <p class="text-xs text-gray-400 mt-2">{{ $varianceStats->netVariance >= 0 ? 'Favorable' : 'Unfavorable' }}</p>
        </div>
    </div>

    <div class="flex gap-2 border-b border-gray-400 overflow-x-auto pb-2">
        @foreach(['valuation' => 'Inventory Valuation', 'aging' => 'Aging Analysis', 'reconciliation' => 'GL Reconciliation', 'variance' => 'Price Variance', 'supplier' => 'Supplier Performance'] as $key => $label)
            <button onclick="switchTab('{{ $key }}')" 
               id="tab-btn-{{ $key }}"
               class="tab-btn px-4 py-2 rounded-t-lg whitespace-nowrap text-sm font-medium transition-colors {{ $key === 'valuation' ? 'bg-[#D4A017] text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border-none shadow-sm overflow-hidden">
        
        <div id="tab-content-valuation" class="tab-content">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-bold">Inventory Valuation Report</h2>
                        <p class="text-sm text-gray-500">Current inventory value by item with cost layers (FIFO method)</p>
                    </div>
                    <button class="flex items-center gap-2 px-3 py-1.5 border rounded-lg text-sm hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Export
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="p-4 font-semibold">Item Code</th>
                                <th class="p-4 font-semibold">Item Name</th>
                                <th class="p-4 font-semibold">Category</th>
                                <th class="p-4 font-semibold text-right">Quantity</th>
                                <th class="p-4 font-semibold text-right">Avg Cost</th>
                                <th class="p-4 font-semibold text-right">Total Value</th>
                                <th class="p-4 font-semibold">Location</th>
                                <th class="p-4 font-semibold text-center">Layers</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($inventoryItems as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4 font-mono text-xs">{{ $item->itemCode }}</td>
                                    <td class="p-4 font-medium">{{ $item->itemName }}</td>
                                    <td class="p-4"><span class="px-2 py-0.5 border rounded-full text-xs bg-gray-50">{{ $item->categoryType }}</span></td>
                                    <td class="p-4 text-right">{{ $item->quantityOnHand }} {{ $item->unitOfMeasure }}</td>
                                    <td class="p-4 text-right">{{ formatCurrency($item->averageCost) }}</td>
                                    <td class="p-4 text-right font-bold">{{ formatCurrency($item->totalValue) }}</td>
                                    <td class="p-4 text-gray-500">{{ $item->locationName }}</td>
                                    <td class="p-4 text-center"><span class="px-2 py-0.5 border rounded text-xs">{{ count($item->valuationLayers) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold">
                            <tr>
                                <td colspan="5" class="p-4 text-right">Total Inventory Value:</td>
                                <td class="p-4 text-right text-blue-600">{{ formatCurrency($totalValue) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($categoryTotals as $category => $value)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-sm text-gray-600 mb-1 font-bold">{{ strtoupper(str_replace('-', ' ', $category)) }}</p>
                            <p class="text-xl font-semibold">{{ formatCurrency($value) }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $inventoryItems->where('categoryType', $category)->count() }} items
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="tab-content-aging" class="tab-content hidden">
            <div class="p-6">
                <h2 class="text-lg font-bold mb-1">Inventory Aging Analysis</h2>
                <p class="text-sm text-gray-500 mb-6">Age of inventory by days in stock</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="p-4 font-semibold">Item Code</th>
                                <th class="p-4 font-semibold">Item Name</th>
                                <th class="p-4 text-right font-semibold">Quantity</th>
                                <th class="p-4 text-right font-semibold">Value</th>
                                <th class="p-4 text-right font-semibold">Days in Stock</th>
                                <th class="p-4 font-semibold">Aging Bucket</th>
                                <th class="p-4 font-semibold">Last Receipt</th>
                                <th class="p-4 text-center font-semibold">NRV Check</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($inventoryItems as $item)
                                @php
                                    $agingColor = $item->daysInInventory <= 30 ? 'bg-green-100 text-green-800' : ($item->daysInInventory <= 60 ? 'bg-yellow-100 text-yellow-800' : ($item->daysInInventory <= 90 ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800'));
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4 font-mono text-xs">{{ $item->itemCode }}</td>
                                    <td class="p-4 font-medium">{{ $item->itemName }}</td>
                                    <td class="p-4 text-right">{{ $item->quantityOnHand }}</td>
                                    <td class="p-4 text-right">{{ formatCurrency($item->totalValue) }}</td>
                                    <td class="p-4 text-right font-bold">{{ $item->daysInInventory }} days</td>
                                    <td class="p-4"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $agingColor }}">{{ $item->agingBucket }}</span></td>
                                    <td class="p-4 text-gray-500">{{ $item->lastReceiptDate }}</td>
                                    <td class="p-4 text-center">
                                        @if($item->nrvAdjustmentNeeded > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-800 rounded text-xs font-bold uppercase">Write-down Needed</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs font-bold uppercase text-center">OK</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-8">
                    <h3 class="font-bold mb-4">Aging Summary</h3>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @foreach(['0-30', '31-60', '61-90', '91-180', '180+'] as $bucket)
                            @php
                                $bItems = $inventoryItems->where('agingBucket', $bucket);
                            @endphp
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <p class="text-xs text-gray-500 font-bold uppercase">{{ $bucket }} days</p>
                                <p class="text-xl font-bold mt-1">{{ formatCurrency($bItems->sum('totalValue')) }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $bItems->count() }} items</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-content-reconciliation" class="tab-content hidden">
        <div class="">

            <div class="p-6 space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold">GL Reconciliation</h2>
                        <p class="text-sm text-gray-500">Reconcile inventory sub-ledger with general ledger</p>
                    </div>
                    <button class="bg-[#D4A017] text-white px-4 py-2 rounded-lg font-bold shadow-sm hover:bg-[#B8860B] transition">
                        Run Reconciliation
                    </button>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-4 rounded-xl border">
                    <div><p class="text-xs text-gray-500 uppercase font-bold">Period</p><p class="font-bold">{{ $latestReconciliation->period }}</p></div>
                    <div><p class="text-xs text-gray-500 uppercase font-bold">Date</p><p class="font-bold">{{ date('m/d/Y') }}</p></div>
                    <div><p class="text-xs text-gray-500 uppercase font-bold">Performed By</p><p class="font-bold">{{ $latestReconciliation->performedBy }}</p></div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Status</p>
                        <span class="px-2 py-0.5 rounded text-xs font-bold {{ $latestReconciliation->isReconciled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $latestReconciliation->isReconciled ? 'RECONCILED' : 'VARIANCE DETECTED' }}
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto border rounded-xl">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="p-4 font-bold">Category</th>
                                <th class="p-4 text-right font-bold">Sub-Ledger</th>
                                <th class="p-4 text-right font-bold">GL Balance</th>
                                <th class="p-4 text-right font-bold">Variance</th>
                                <th class="p-4 text-center font-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @php
                                $reconRows = [
                                    ['name' => 'Raw Materials', 'sub' => $latestReconciliation->subLedgerRawMaterials, 'gl' => $latestReconciliation->glRawMaterials, 'var' => $latestReconciliation->varianceRawMaterials],
                                    ['name' => 'Packaging', 'sub' => $latestReconciliation->subLedgerPackaging, 'gl' => $latestReconciliation->glPackaging, 'var' => $latestReconciliation->variancePackaging],
                                    ['name' => 'Semi-Finished', 'sub' => $latestReconciliation->subLedgerSemiFinished, 'gl' => $latestReconciliation->glSemiFinished, 'var' => $latestReconciliation->varianceSemiFinished],
                                    ['name' => 'Finished Goods', 'sub' => $latestReconciliation->subLedgerFinishedGoods, 'gl' => $latestReconciliation->glFinishedGoods, 'var' => $latestReconciliation->varianceFinishedGoods],
                                ];
                            @endphp
                            @foreach($reconRows as $row)
                                <tr>
                                    <td class="p-4 font-medium">{{ $row['name'] }}</td>
                                    <td class="p-4 text-right">{{ formatCurrency($row['sub']) }}</td>
                                    <td class="p-4 text-right">{{ formatCurrency($row['gl']) }}</td>
                                    <td class="p-4 text-right font-bold {{ abs($row['var']) < 1 ? 'text-green-600' : 'text-red-600' }}">{{ formatCurrency($row['var']) }}</td>
                                    <td class="p-4 text-center">
                                        @if(abs($row['var']) < 1)
                                            <span class="text-green-600 font-bold">✔</span>
                                        @else
                                            <span class="text-red-600 font-bold">✘</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100 font-black">
                            <tr>
                                <td class="p-4 uppercase">Total</td>
                                <td class="p-4 text-right">{{ formatCurrency($latestReconciliation->subLedgerTotal) }}</td>
                                <td class="p-4 text-right">{{ formatCurrency($latestReconciliation->glTotal) }}</td>
                                <td class="p-4 text-right {{ abs($latestReconciliation->varianceTotal) < 1 ? 'text-green-600' : 'text-red-600' }}">{{ formatCurrency($latestReconciliation->varianceTotal) }}</td>
                                <td class="p-4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if(!$latestReconciliation->isReconciled)
                    <div class="bg-red-50 border border-red-200 p-4 rounded-xl">
                        <h4 class="text-red-900 font-black uppercase text-xs mb-1">Variance Reason</h4>
                        <p class="text-sm text-red-800">{{ $latestReconciliation->varianceReason }}</p>
                    </div>
                @endif

                
                <div class="bg-blue-50 border border-blue-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6 pb-3">
                        <h3 class="text-lg font-bold text-blue-900">How GL Reconciliation Works</h3>
                    </div>
                    
                    <div class="p-6 pt-0 text-sm text-blue-900 space-y-2">
                        <p>
                            <strong class="font-bold">Sub-Ledger:</strong> 
                            Sum of all inventory item values from the inventory system
                        </p>
                        <p>
                            <strong class="font-bold">GL Balance:</strong> 
                            Current balance of inventory GL accounts (1300, 1310, 1320, 1500, etc.)
                        </p>
                        <p>
                            <strong class="font-bold">Variance:</strong> 
                            Difference between sub-ledger and GL (should be close to zero)
                        </p>
    
                        <p class="mt-3 font-bold">Common Causes of Variance:</p>
                        <ul class="ml-4 space-y-1">
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Unposted goods receipts or adjustments</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Manual journal entries not reflected in inventory system</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Timing differences (transactions in different periods)</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>System errors or data entry mistakes</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
            
        </div>

        <div id="tab-content-variance" class="tab-content hidden">
            <div class="p-6">
                <h2 class="text-lg font-bold mb-1">Purchase Price Variance Analysis</h2>
                <p class="text-sm text-gray-500 mb-6">Compare PO prices vs actual invoice prices</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-50 border border-green-200 p-4 rounded-xl">
                        <p class="text-xs text-green-800 font-bold uppercase">Favorable</p>
                        <p class="text-2xl font-bold text-green-900 mt-1">{{ formatCurrency($varianceStats->totalFavorable) }}</p>
                        <p class="text-xs text-green-700">{{ $varianceStats->favorableCount }} transactions</p>
                    </div>
                    <div class="bg-red-50 border border-red-200 p-4 rounded-xl">
                        <p class="text-xs text-red-800 font-bold uppercase">Unfavorable</p>
                        <p class="text-2xl font-bold text-red-900 mt-1">{{ formatCurrency($varianceStats->totalUnfavorable) }}</p>
                        <p class="text-xs text-red-700">{{ $varianceStats->unfavorableCount }} transactions</p>
                    </div>
                    <div class="p-4 rounded-xl border {{ $varianceStats->netVariance >= 0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                        <p class="text-xs font-bold uppercase {{ $varianceStats->netVariance >= 0 ? 'text-green-800' : 'text-red-800' }}">Net Variance</p>
                        <p class="text-2xl font-bold mt-1 {{ $varianceStats->netVariance >= 0 ? 'text-green-900' : 'text-red-900' }}">{{ formatCurrency(abs($varianceStats->netVariance)) }}</p>
                        <p class="text-xs {{ $varianceStats->netVariance >= 0 ? 'text-green-700' : 'text-red-700' }} font-bold uppercase">{{ $varianceStats->netVariance >= 0 ? 'Favorable' : 'Unfavorable' }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto border rounded-xl">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="p-4 font-bold">Date</th>
                                <th class="p-4 font-bold">Item</th>
                                <th class="p-4 font-bold">Supplier</th>
                                <th class="p-4 text-right font-bold">Qty</th>
                                <th class="p-4 text-right font-bold">PO Price</th>
                                <th class="p-4 text-right font-bold">Invoice</th>
                                <th class="p-4 text-right font-bold">Variance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($priceVariances as $v)
                                <tr>
                                    <td class="p-4 text-gray-500">{{ $v->transactionDate }}</td>
                                    <td class="p-4"><p class="font-bold">{{ $v->itemName }}</p><p class="text-xs text-gray-400">{{ $v->itemCode }}</p></td>
                                    <td class="p-4">{{ $v->supplierName }}</td>
                                    <td class="p-4 text-right">{{ $v->quantity }}</td>
                                    <td class="p-4 text-right">{{ formatCurrency($v->poUnitPrice) }}</td>
                                    <td class="p-4 text-right">{{ formatCurrency($v->invoiceUnitPrice) }}</td>
                                    <td class="p-4 text-right font-bold {{ $v->varianceType == 'favorable' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ formatCurrency(abs($v->totalPriceVariance)) }}
                                        <span class="text-[10px] uppercase font-bold ml-1">{{ $v->varianceType == 'favorable' ? '↓' : '↑' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="tab-content-supplier" class="tab-content hidden">
            <div class="p-6 space-y-6">
                <h2 class="text-lg font-bold">Supplier Performance Scorecard</h2>
                @foreach($suppliers as $supplier)
                    <div class="border rounded-2xl p-6 bg-white hover:border-[#D4A017] transition-all duration-300">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-xl font-black text-gray-800">{{ $supplier->supplierName }}</h3>
                                <p class="text-sm text-gray-400 font-bold uppercase tracking-wider">{{ $supplier->supplierType }}</p>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center gap-1">
                                    <span class="text-4xl font-black text-[#D4A017]">{{ $supplier->performanceScore }}</span>
                                    <span class="text-gray-300 text-sm font-bold">/100</span>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $supplier->rating == 'excellent' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $supplier->rating }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-gray-50 p-4 rounded-xl border border-dashed">
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Purchase Value</p>
                                <p class="text-lg font-black text-gray-800">{{ formatCurrency($supplier->totalPurchaseValue) }}</p>
                                <p class="text-xs text-gray-400 font-bold">{{ $supplier->totalPurchaseOrders }} Orders</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-dashed">
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">On-Time Rate</p>
                                <p class="text-lg font-black text-gray-800">{{ $supplier->onTimeDeliveryRate }}%</p>
                                <p class="text-xs text-gray-400 font-bold">{{ $supplier->onTimeDeliveries }} / {{ $supplier->onTimeDeliveries + $supplier->lateDeliveries }} deliveries</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-dashed">
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Rejection Rate</p>
                                <p class="text-lg font-black text-red-600">{{ $supplier->rejectionRate }}%</p>
                                <p class="text-xs text-gray-400 font-bold">{{ $supplier->rejectedGRNs }} rejected</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-xl border border-dashed">
                                <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Price Variance</p>
                                <p class="text-lg font-black {{ $supplier->totalPriceVariance <= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ formatCurrency(abs($supplier->totalPriceVariance)) }}
                                </p>
                                <p class="text-xs font-bold uppercase {{ $supplier->totalPriceVariance <= 0 ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $supplier->totalPriceVariance <= 0 ? 'Favorable' : 'Unfavorable' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function switchTab(tabKey) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
        });

        // Show selected tab content
        document.getElementById('tab-content-' + tabKey).classList.remove('hidden');

        // Reset all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-[#D4A017]', 'text-white', 'shadow-md');
            btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        });

        // Activate selected button
        const activeBtn = document.getElementById('tab-btn-' + tabKey);
        activeBtn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
        activeBtn.classList.add('bg-[#D4A017]', 'text-white', 'shadow-md');
    }
</script>