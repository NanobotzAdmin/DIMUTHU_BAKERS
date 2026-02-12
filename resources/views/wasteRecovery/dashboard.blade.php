@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1600px] mx-auto space-y-6">
    <div>
        <h1 class="flex items-center gap-3 text-2xl font-bold">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Waste Recovery Dashboard
        </h1>
        <p class="text-gray-600 mt-1">Daily waste management and recovery analytics</p>
    </div>

    <div class="flex gap-2" id="period-selector">
        <button onclick="changePeriod('today')" class="px-4 py-2 rounded-md border border-gray-200 bg-[#D4A017] text-white font-medium" data-period="today">Today</button>
        <button onclick="changePeriod('week')" class="px-4 py-2 rounded-md border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 font-medium" data-period="week">This Week</button>
        <button onclick="changePeriod('month')" class="px-4 py-2 rounded-md border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 font-medium" data-period="month">This Month</button>
    </div>

    <div class="rounded-xl border border-blue-200 bg-blue-50 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-blue-900 font-bold text-lg leading-none">Today's Waste Status</h3>
            <p class="text-blue-700 text-sm mt-2">{{ $todayStatus['formattedDate'] }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div class="bg-white rounded-lg p-4 border-2 border-green-200">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-bold text-green-900 text-xs uppercase tracking-wider">Stage 1: Fresh Products</h3>
                            <p class="text-xs text-green-700">No action needed</p>
                        </div>
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div class="space-y-1 text-sm">
                        <p class="text-gray-700">• {{ $todayStatus['fresh'] }} batches in fresh stage</p>
                        @if($todayStatus['needDayOldTransfer'] > 0)
                            <p class="text-orange-700 font-bold mt-2">⚠️ {{ $todayStatus['needDayOldTransfer'] }} batches ready for transfer</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-lg p-4 border-2 border-orange-200">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-bold text-orange-900 text-xs uppercase tracking-wider">Stage 2: Day-Old Products</h3>
                            <p class="text-xs text-orange-700">Action: Discount & Sell</p>
                        </div>
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                    </div>
                    <div class="space-y-1 text-sm">
                        <p class="text-orange-700">⚠️ {{ $todayStatus['dayOld'] }} batches at discounted pricing</p>
                        @if($todayStatus['needWasteTransfer'] > 0)
                            <p class="text-red-700 font-bold">🔴 {{ $todayStatus['needWasteTransfer'] }} batches past selling threshold</p>
                        @endif
                    </div>
                    @if($todayStatus['needWasteTransfer'] > 0)
                        <button class="w-full mt-3 bg-red-600 hover:bg-red-700 text-white py-2 rounded text-xs font-bold uppercase">Transfer to Waste</button>
                    @endif
                </div>

                <div class="bg-white rounded-lg p-4 border-2 border-red-200">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="font-bold text-red-900 text-xs uppercase tracking-wider">Stage 3: Waste Recovery</h3>
                            <p class="text-xs text-red-700">Action: Process Now</p>
                        </div>
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="space-y-1 text-sm">
                        <p class="text-red-700 font-bold">🔴 URGENT: {{ $todayStatus['waste'] }} batches awaiting processing</p>
                        <p class="text-gray-500 text-xs mt-1">Process immediately to maximize recovery</p>
                    </div>
                    <button class="w-full mt-3 bg-green-600 hover:bg-green-700 text-white py-2 rounded text-xs font-bold uppercase">Process Batches</button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Original Value</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">${{ number_format($stats['financial']['totalOriginalValue'], 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">All tracked items</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">NRV Write-downs</p>
            <p class="text-3xl font-bold text-orange-600 mt-2">${{ number_format($stats['financial']['totalNRVWritedown'], 2) }}</p>
            <p class="text-xs text-orange-400 mt-1">{{ number_format(($stats['financial']['totalNRVWritedown'] / $stats['financial']['totalOriginalValue']) * 100, 1) }}% of original</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Waste Loss</p>
            <p class="text-3xl font-bold text-red-600 mt-2">${{ number_format($stats['financial']['totalWasteLoss'], 2) }}</p>
            <p class="text-xs text-red-400 mt-1">{{ number_format(($stats['financial']['totalWasteLoss'] / $stats['financial']['totalOriginalValue']) * 100, 1) }}% of original</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Recovery Income</p>
            <p class="text-3xl font-bold text-green-600 mt-2">${{ number_format($stats['financial']['totalRecoveryIncome'], 2) }}</p>
            <p class="text-xs text-green-400 mt-1">{{ $stats['averageRecoveryEfficiency'] }}% efficiency</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="font-bold text-lg leading-none">Recovery Method Performance</h3>
            <p class="text-sm text-gray-500 mt-1">Comparison of waste recovery methods</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-gray-600">
                        <th class="py-3 px-4 font-bold">Method</th>
                        <th class="py-3 px-4 text-right">Batches</th>
                        <th class="py-3 px-4 text-right">Input Weight</th>
                        <th class="py-3 px-4 text-right">Output Weight</th>
                        <th class="py-3 px-4 text-right">Revenue</th>
                        <th class="py-3 px-4 text-right">Cost</th>
                        <th class="py-3 px-4 text-right">Net Recovery</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wasteSummary as $data)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-4 font-bold text-gray-800">{{ $data['name'] }}</td>
                        <td class="py-3 px-4 text-right">{{ $data['count'] }}</td>
                        <td class="py-3 px-4 text-right">{{ $data['input'] }}kg</td>
                        <td class="py-3 px-4 text-right">{{ $data['output'] }}kg</td>
                        <td class="py-3 px-4 text-right text-green-700 font-medium">${{ number_format($data['revenue'], 2) }}</td>
                        <td class="py-3 px-4 text-right text-red-600">${{ number_format($data['cost'], 2) }}</td>
                        <td class="py-3 px-4 text-right font-bold text-green-700">${{ number_format($data['net'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="font-bold text-lg leading-none">Product Waste Analysis</h3>
        <p class="text-sm text-gray-500 mt-1">Waste rates and recovery performance by product</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-gray-600">
                    <th class="py-3 px-4 font-bold">Product</th>
                    <th class="py-3 px-4 text-right">Total Produced</th>
                    <th class="py-3 px-4 text-right">Waste Qty</th>
                    <th class="py-3 px-4 text-right">Waste %</th>
                    <th class="py-3 px-4 text-right">Original Cost</th>
                    <th class="py-3 px-4 text-right">Recovery Value</th>
                    <th class="py-3 px-4 text-right">Net Loss</th>
                    <th class="py-3 px-4 text-center">Performance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productAnalysis as $product)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4 font-bold text-gray-800">{{ $product['productName'] }}</td>
                    <td class="py-3 px-4 text-right">{{ number_format($product['totalProduced']) }}</td>
                    <td class="py-3 px-4 text-right">{{ number_format($product['totalWaste']) }}</td>
                    <td class="py-3 px-4 text-right">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold 
                            {{ $product['wastePercentage'] > 10 ? 'bg-red-100 text-red-800' : 
                               ($product['wastePercentage'] > 5 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800') }}">
                            {{ number_format($product['wastePercentage'], 1) }}%
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right font-medium text-gray-600">
                        ${{ number_format($product['totalOriginalCost'], 2) }}
                    </td>
                    <td class="py-3 px-4 text-right font-bold text-green-700">
                        ${{ number_format($product['totalRecoveryValue'], 2) }}
                    </td>
                    <td class="py-3 px-4 text-right font-bold text-red-700">
                        ${{ number_format($product['totalNetLoss'], 2) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if($product['wastePercentage'] > 8)
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-red-100 text-red-800 text-[10px] font-black uppercase">
                                🔴 High Waste
                            </span>
                        @elseif($product['wastePercentage'] > 5)
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-orange-100 text-orange-800 text-[10px] font-black uppercase">
                                🟡 Review
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-100 text-green-800 text-[10px] font-black uppercase">
                                ✅ Good
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="p-6 bg-gray-50 border-t border-gray-200 space-y-3">
        <h4 class="font-bold text-sm text-gray-700">Recommendations:</h4>
        @foreach($productAnalysis as $product)
            @if($product['wastePercentage'] > 8)
                <div class="flex items-start gap-3 p-3 bg-red-50 border border-red-100 rounded-lg">
                    <span class="text-red-600 font-bold text-lg">🔴</span>
                    <p class="text-sm text-red-800">
                        <strong>{{ $product['productName'] }}:</strong> High waste rate ({{ $product['wastePercentage'] }}%) - Review production schedule and demand forecasting to reduce losses.
                    </p>
                </div>
            @endif
        @endforeach
        
        @php
            $bestPerformer = collect($productAnalysis)->sortBy('wastePercentage')->first();
        @endphp
        
        @if($bestPerformer)
            <div class="flex items-start gap-3 p-3 bg-green-50 border border-green-100 rounded-lg">
                <span class="text-green-600 font-bold text-lg">✅</span>
                <p class="text-sm text-green-800">
                    <strong>Best Performer:</strong> {{ $bestPerformer['productName'] }} with {{ $bestPerformer['wastePercentage'] }}% waste rate. Maintain current inventory levels.
                </p>
            </div>
        @endif
    </div>
</div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="font-bold text-lg leading-none">Waste Trends - Last 6 Months</h3>
            <p class="text-sm text-gray-500 mt-1">Historical waste analysis and recovery performance</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-gray-600">
                        <th class="py-3 px-4 font-bold">Month</th>
                        <th class="py-3 px-4 text-right">Production Value</th>
                        <th class="py-3 px-4 text-right">Waste Value</th>
                        <th class="py-3 px-4 text-right">Waste %</th>
                        <th class="py-3 px-4 text-right">Recovery Income</th>
                        <th class="py-3 px-4 text-right">Net Loss</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wasteTrends as $trend)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-4 font-bold text-gray-800">{{ $trend['month'] }}</td>
                        <td class="py-3 px-4 text-right">${{ number_format($trend['production']) }}</td>
                        <td class="py-3 px-4 text-right text-red-600 font-medium">${{ number_format($trend['wasteValue']) }}</td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1 font-bold">
                                {{ $trend['wastePercent'] }}%
                                @if($trend['trendUp'])
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                @else
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right text-green-700 font-medium">${{ number_format($trend['recoveryIncome']) }}</td>
                        <td class="py-3 px-4 text-right text-red-600 font-bold">-${{ number_format($trend['netLoss']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 p-6 bg-gray-50 gap-4">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
            <h3 class="font-bold text-blue-900">Trend Analysis</h3>
        </div>
        <p class="text-sm text-blue-800 leading-relaxed">
            @php
                $firstTrend = reset($wasteTrends);
                $lastTrend = end($wasteTrends);
            @endphp

            @if($lastTrend['wastePercent'] > $firstTrend['wastePercent'])
                <span class="font-bold">📈 Waste increasing in {{ $lastTrend['month'] }} (seasonal effect?).</span> 
                Review holiday production forecasts and adjustment protocols to mitigate excess inventory.
            @else
                <span class="font-bold">📉 Overall downward trend in waste.</span> 
                Your waste management initiatives are showing measurable results. Keep maintaining current inventory optimization.
            @endif
        </p>
    </div>

    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="font-bold text-green-900">Recovery Impact</h3>
        </div>
        <p class="text-sm text-green-800 leading-relaxed">
            @php
                $currentMonth = end($wasteTrends);
                $offset = ($currentMonth['wasteValue'] > 0) ? ($currentMonth['recoveryIncome'] / $currentMonth['wasteValue']) * 100 : 0;
            @endphp
            Recovery income offsets <span class="font-bold">{{ number_format($offset, 1) }}%</span> of waste loss. 
            There is a significant opportunity to improve margins by shifting more "Disposed" items toward "Bio-Gas" or "Animal Feed" recovery streams.
        </p>
    </div>
</div>
    </div>
</div>

<script>
    function changePeriod(period) {
        const buttons = document.querySelectorAll('#period-selector button');
        buttons.forEach(btn => {
            btn.className = "px-4 py-2 rounded-md border bg-white text-gray-700 hover:bg-gray-50 font-medium";
            if(btn.getAttribute('data-period') === period) {
                btn.className = "px-4 py-2 rounded-md border bg-[#D4A017] text-white font-medium";
            }
        });
        // Add logic to fetch new data or filter if needed
        console.log("Period changed to:", period);
    }
</script>
@endsection