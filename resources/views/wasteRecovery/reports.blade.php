@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1600px] mx-auto space-y-6">
    <div>
        <h1 class="flex items-center gap-3 text-2xl font-bold">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Waste Recovery Reports
        </h1>
        <p class="text-gray-600 mt-1">Comprehensive financial and operational reports</p>
    </div>

    <div class="flex gap-2">
        <button class="px-4 py-2 rounded-md border border-gray-200 bg-[#D4A017] text-white font-medium">This Month</button>
        <button class="px-4 py-2 rounded-md border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 font-medium">This Quarter</button>
        <button class="px-4 py-2 rounded-md border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 font-medium">This Year</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="report-tabs">
        <div onclick="showReport('pl')" id="tab-pl" class="cursor-pointer p-4 rounded-xl border-2 border-[#D4A017] bg-white shadow-sm transition-all active-tab">
            <h3 class="font-bold flex items-center gap-2 text-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Waste P&L
            </h3>
            <p class="text-xs text-gray-500 mt-2">Profit & Loss statement for waste operations</p>
        </div>

        <div onclick="showReport('category')" id="tab-category" class="cursor-pointer p-4 rounded-xl border-2 border-transparent bg-white shadow-sm hover:border-gray-200 transition-all">
            <h3 class="font-bold flex items-center gap-2 text-blue-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                By Category
            </h3>
            <p class="text-xs text-gray-500 mt-2">Waste cost breakdown by product category</p>
        </div>

        <div onclick="showReport('efficiency')" id="tab-efficiency" class="cursor-pointer p-4 rounded-xl border-2 border-transparent bg-white shadow-sm hover:border-gray-200 transition-all">
            <h3 class="font-bold flex items-center gap-2 text-orange-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                Efficiency
            </h3>
            <p class="text-xs text-gray-500 mt-2">Recovery efficiency and KPI analysis</p>
        </div>

        <div onclick="showReport('environmental')" id="tab-environmental" class="cursor-pointer p-4 rounded-xl border-2 border-transparent bg-white shadow-sm hover:border-gray-200 transition-all">
            <h3 class="font-bold flex items-center gap-2 text-green-600">
                🌱 Environmental
            </h3>
            <p class="text-xs text-gray-500 mt-2">Environmental impact and sustainability metrics</p>
        </div>
    </div>

    <div id="report-pl-content" class="report-content">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-8">
        <div class="flex justify-between items-center mb-8 border-b border-gray-200 pb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-900 uppercase">Waste Profit & Loss Statement</h2>
                <p class="text-sm text-gray-500 font-medium">Reporting Period: December 2025 | Currency: LKR (Rs.)</p>
            </div>
            <button class="bg-white border-2 border-gray-400 px-6 py-2 rounded-lg text-sm font-black hover:bg-gray-900 hover:text-white transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                DOWNLOAD PDF REPORT
            </button>
        </div>

        <div class="space-y-10">
            <section>
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">I. PRODUCTION COST (Waste Items)</h3>
                <div class="space-y-3 border-l-4 border-gray-100 ml-1 pl-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Beginning Inventory (Waste Pool)</span>
                        <span class="font-mono font-bold text-gray-900">Rs. {{ number_format($wastePL['beginningInventory'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">+ Production Costs of Items Entering Waste Stream</span>
                        <span class="font-mono font-bold text-gray-900">Rs. {{ number_format($wastePL['productionCosts'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-red-600 border-b pb-2">
                        <span class="font-medium">- Ending Inventory (Unprocessed Waste)</span>
                        <span class="font-mono font-bold">(Rs. {{ number_format($wastePL['endingInventory'], 2) }})</span>
                    </div>
                    <div class="flex justify-between text-lg font-black bg-gray-50 p-4 -ml-4 rounded-r-lg border-r-4 border-gray-800">
                        <span>TOTAL COST OF WASTE ITEMS</span>
                        <span class="font-mono text-gray-900">Rs. {{ number_format($wastePL['costOfWasteItems'], 2) }}</span>
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-xs font-black text-green-600 uppercase tracking-[0.2em] mb-4">II. REVENUE FROM WASTE RECOVERY</h3>
                <div class="space-y-3 border-l-4 border-green-100 ml-1 pl-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Day-Old Product Sales (Discounted Revenue)</span>
                        <span class="font-mono font-bold text-green-700">Rs. {{ number_format($wastePL['dayOldSales'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm border-b pb-2">
                        <span class="text-gray-600">Direct Waste Recovery Income (Animal Feed/Bio-Gas)</span>
                        <span class="font-mono font-bold text-green-700">Rs. {{ number_format($wastePL['wasteRecoveryIncome'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-black bg-green-50 p-4 -ml-4 rounded-r-lg border-r-4 border-green-600 text-green-800">
                        <span>TOTAL WASTE REVENUE</span>
                        <span class="font-mono text-green-700">Rs. {{ number_format($wastePL['totalRevenue'], 2) }}</span>
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-xs font-black text-red-600 uppercase tracking-[0.2em] mb-4">III. WASTE-RELATED EXPENSES</h3>
                <div class="space-y-3 border-l-4 border-red-100 ml-1 pl-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">NRV Write-downs (Stage 1 to 2 Valuation Loss)</span>
                        <span class="font-mono font-bold text-red-700">Rs. {{ number_format($wastePL['nrvWritedowns'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Actual Waste Inventory Loss (Stage 3 Items)</span>
                        <span class="font-mono font-bold text-red-700">Rs. {{ number_format($wastePL['wasteLoss'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Recovery Processing Costs (Labor/Energy)</span>
                        <span class="font-mono font-bold text-red-700">Rs. {{ number_format($wastePL['processingCosts'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm border-b pb-2">
                        <span class="text-gray-600">Third-Party Disposal & Landfill Fees</span>
                        <span class="font-mono font-bold text-red-700">Rs. {{ number_format($wastePL['disposalCosts'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-black bg-red-50 p-4 -ml-4 rounded-r-lg border-r-4 border-red-600 text-red-800">
                        <span>TOTAL WASTE EXPENSES</span>
                        <span class="font-mono text-red-700">Rs. {{ number_format($wastePL['totalExpenses'], 2) }}</span>
                    </div>
                </div>
            </section>

            <div class="pt-10">
                <div class="flex justify-between items-center bg-gray-900 text-white p-8 rounded-xl shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Final Result</span>
                        <h2 class="text-3xl font-black italic mt-1">NET WASTE LOSS</h2>
                    </div>
                    <span class="text-4xl font-mono font-black relative z-10 text-red-500">
                        (Rs. {{ number_format($wastePL['netWasteLoss'], 2) }})
                    </span>
                    <svg class="absolute right-0 bottom-0 text-gray-800 w-64 h-64 -mb-20 -mr-20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div class="bg-blue-50 border-2 border-blue-100 p-6 rounded-xl">
                        <p class="text-xs font-black text-blue-400 uppercase tracking-widest mb-1">Recovery Rate</p>
                        <p class="text-xl font-bold text-blue-900">{{ $wastePL['recoveryRate'] }}%</p>
                        <p class="text-sm text-blue-700 mt-2">Percentage of total waste-related costs recovered through sales and processing.</p>
                    </div>
                    <div class="bg-orange-50 border-2 border-orange-100 p-6 rounded-xl">
                        <p class="text-xs font-black text-orange-400 uppercase tracking-widest mb-1">Financial Efficiency</p>
                        <p class="text-xl font-bold text-orange-900">Low Impact</p>
                        <p class="text-sm text-orange-700 mt-2">Waste loss represents approx. 4.2% of total production value for this period.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div id="report-category-content" class="report-content hidden">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
             <div class="p-6 border-b border-gray-200">
                 <h2 class="text-xl font-bold">Waste Cost Breakdown by Category</h2>
             </div>
             <table class="w-full text-sm">
                 <thead class="bg-gray-50 border-b border-gray-200">
                     <tr>
                         <th class="py-4 px-6 text-left">Category</th>
                         <th class="py-4 px-6 text-right">Waste Cost</th>
                         <th class="py-4 px-6 text-right">Waste %</th>
                         <th class="py-4 px-6 text-right">Net Impact</th>
                     </tr>
                 </thead>
                 <tbody>
                     @foreach($categories as $name => $data)
                     <tr class="border-b border-gray-200">
                         <td class="py-4 px-6 font-bold">{{ $name }}</td>
                         <td class="py-4 px-6 text-right text-orange-600 font-mono">${{ number_format($data['waste']) }}</td>
                         <td class="py-4 px-6 text-right">
                             <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded-full font-bold text-xs">{{ $data['pct'] }}%</span>
                         </td>
                         <td class="py-4 px-6 text-right text-red-600 font-bold font-mono">${{ number_format($data['net']) }}</td>
                     </tr>
                     @endforeach
                 </tbody>
             </table>

             <div class="mt-8 space-y-4 px-6 pb-6">
    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Category Impact Analysis</h3>
    
    <div class="bg-orange-50 border-2 border-orange-100 rounded-xl p-4 flex items-start gap-4 transition-all hover:shadow-md">
        <div class="bg-orange-500 p-2 rounded-lg text-white shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-sm text-orange-900 leading-relaxed">
                <strong>Waste Impact on Net Profit:</strong> 
                @php
                    $totalNetImpact = array_sum(array_column($categories, 'net'));
                    $totalProduction = array_sum(array_column($categories, 'prod'));
                    $profitImpactPct = $totalProduction > 0 ? ($totalNetImpact / $totalProduction) * 100 : 0;
                @endphp
                <span class="font-bold underline">{{ number_format($profitImpactPct, 1) }}%</span> of production value is lost to non-recovered waste. 
                (Total Net Impact: <span class="font-mono font-bold text-red-700">Rs. {{ number_format($totalNetImpact, 2) }}</span>)
            </p>
        </div>
    </div>

    @php
        // Identify Highest Waste Category
        $highestWasteCat = collect($categories)->sortByDesc('pct')->keys()->first();
        $highestWastePct = collect($categories)->max('pct');

        // Identify Best Performer
        $bestPerformerCat = collect($categories)->sortBy('pct')->keys()->first();
        $bestPerformerPct = collect($categories)->min('pct');
    @endphp

    <div class="bg-red-50 border-2 border-red-100 rounded-xl p-4 flex items-start gap-4 transition-all hover:shadow-md">
        <div class="bg-red-600 p-2 rounded-lg text-white shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
        </div>
        <div>
            <p class="text-sm text-red-900 leading-relaxed">
                <strong>Highest Waste Category:</strong> 
                <span class="font-black">{{ $highestWasteCat }}</span> at 
                <span class="font-bold">{{ number_format($highestWastePct, 1) }}%</span>. 
                Urgent review of production forecasting and batch sizing is required for this category.
            </p>
        </div>
    </div>

    <div class="bg-green-50 border-2 border-green-100 rounded-xl p-4 flex items-start gap-4 transition-all hover:shadow-md">
        <div class="bg-green-600 p-2 rounded-lg text-white shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-sm text-green-900 leading-relaxed">
                <strong>Best Performer:</strong> 
                <span class="font-black">{{ $bestPerformerCat }}</span> at 
                <span class="font-bold">{{ number_format($bestPerformerPct, 1) }}%</span>. 
                Excellent waste control achieved here. Recommended to study these inventory protocols for replication across other categories.
            </p>
        </div>
    </div>
</div>
        </div>
    </div>


    <div id="report-efficiency-content" class="report-content hidden">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-xl font-black text-gray-900 uppercase">Recovery Efficiency & KPI Analysis</h2>
                <p class="text-sm text-gray-500 font-medium">Reporting Period: December 2025</p>
            </div>
            <button class="bg-white border px-4 py-2 rounded text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export PDF
            </button>
        </div>

        <div class="space-y-10">
            <section>
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Financial Performance Indicators</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border-2 border-gray-100 rounded-xl p-6 transition-hover hover:border-orange-200">
                        <p class="text-sm font-bold text-gray-500 mb-2">Waste as % of Production Cost</p>
                        <div class="flex items-baseline gap-3">
                            <p class="text-4xl font-black text-orange-600">{{ $efficiencyKPIs['wasteProductionRatio']['actual'] }}%</p>
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-black rounded-full uppercase">Target: < {{ $efficiencyKPIs['wasteProductionRatio']['target'] }}%</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-4 font-medium italic">Above target - production volume adjustments needed.</p>
                    </div>

                    <div class="border-2 border-gray-100 rounded-xl p-6 transition-hover hover:border-blue-200">
                        <p class="text-sm font-bold text-gray-500 mb-2">Recovery Efficiency</p>
                        <div class="flex items-baseline gap-3">
                            <p class="text-4xl font-black text-blue-600">{{ $efficiencyKPIs['recoveryEfficiency']['actual'] }}%</p>
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-black rounded-full uppercase">Target: > {{ $efficiencyKPIs['recoveryEfficiency']['target'] }}%</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-4 font-medium italic">Critical - review recovery methods and market pricing.</p>
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Operational Flow Metrics</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border-2 border-gray-100 rounded-xl p-6">
                        <p class="text-sm font-bold text-gray-500 mb-2">Average Days to Recovery</p>
                        <div class="flex items-baseline gap-2">
                            <p class="text-4xl font-black text-gray-800">{{ $efficiencyKPIs['avgDaysToRecovery']['actual'] }}</p>
                            <span class="text-sm font-bold text-gray-400">days</span>
                            <span class="ml-4 px-3 py-1 bg-orange-100 text-orange-800 text-xs font-black rounded-full uppercase">Target: < 3.0</span>
                        </div>
                    </div>

                    <div class="border-2 border-gray-100 rounded-xl p-6">
                        <p class="text-sm font-bold text-gray-500 mb-2">Disposal Rate (Landfill)</p>
                        <div class="flex items-baseline gap-3">
                            <p class="text-4xl font-black text-red-600">{{ $efficiencyKPIs['disposalRate']['actual'] }}%</p>
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-black rounded-full uppercase">Target: < 15%</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-gray-900 rounded-2xl p-8 text-white relative overflow-hidden">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em] mb-6 relative z-10">Strategic Recommendations</h3>
                <div class="space-y-4 relative z-10">
                    <div class="flex items-center gap-4 bg-white/10 p-4 rounded-lg border border-white/10">
                        <span class="w-8 h-8 flex items-center justify-center bg-red-500 rounded-full font-black text-xs">01</span>
                        <p class="text-sm font-medium"><strong>Priority 1:</strong> Reduce overall waste rate to below 8% through stricter demand forecasting.</p>
                    </div>
                    <div class="flex items-center gap-4 bg-white/10 p-4 rounded-lg border border-white/10">
                        <span class="w-8 h-8 flex items-center justify-center bg-orange-500 rounded-full font-black text-xs">02</span>
                        <p class="text-sm font-medium"><strong>Priority 2:</strong> Improve recovery efficiency by optimizing high-margin recovery streams like Bio-Gas.</p>
                    </div>
                    <div class="flex items-center gap-4 bg-white/10 p-4 rounded-lg border border-white/10">
                        <span class="w-8 h-8 flex items-center justify-center bg-blue-500 rounded-full font-black text-xs">03</span>
                        <p class="text-sm font-medium"><strong>Priority 3:</strong> Increase day-old sales conversion to 75% through dynamic promotional pricing.</p>
                    </div>
                </div>
                <svg class="absolute right-0 top-0 text-white/5 w-64 h-64 -mt-10 -mr-10" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
            </section>
        </div>
    </div>
</div>

    <div id="report-environmental-content" class="report-content hidden">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-xl font-black text-gray-900 flex items-center gap-2 uppercase">
                    <span>🌱</span> Environmental Impact Report
                </h2>
                <p class="text-sm text-gray-500 font-medium">Period: December 2025</p>
            </div>
            <button class="bg-white border px-4 py-2 rounded text-sm font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export PDF
            </button>
        </div>

        <div class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border-2 border-green-100 rounded-xl p-6 bg-green-50/50">
                    <p class="text-xs font-black text-green-700 uppercase tracking-widest mb-1">Waste Diverted from Landfill</p>
                    <div class="flex items-baseline gap-3">
                        <p class="text-4xl font-black text-green-900">{{ number_format($envImpact['diversionRate'], 0) }}%</p>
                        <span class="px-2 py-1 bg-green-200 text-green-900 text-[10px] font-black rounded uppercase">Target: >85%</span>
                    </div>
                    <p class="text-xs text-green-700 mt-3 font-medium italic">
                        {{ $envImpact['wasteDiverted'] }}kg diverted / {{ ($envImpact['wasteDiverted'] + $envImpact['wasteToLandfill']) }}kg total
                    </p>
                </div>

                <div class="border-2 border-blue-100 rounded-xl p-6 bg-blue-50/50">
                    <p class="text-xs font-black text-blue-700 uppercase tracking-widest mb-1">CO₂ Offset (Recovery Activities)</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-4xl font-black text-blue-900">{{ number_format($envImpact['totalCO2Offset'], 0) }}kg</p>
                        <span class="text-sm font-bold text-blue-700">CO₂/month</span>
                    </div>
                    <p class="text-xs text-blue-700 mt-3 font-medium italic">
                        Equivalent to planting {{ round($envImpact['totalCO2Offset'] / 20) }} trees
                    </p>
                </div>

                <div class="border-2 border-orange-100 rounded-xl p-6 bg-orange-50/50">
                    <p class="text-xs font-black text-orange-700 uppercase tracking-widest mb-1">Animal Feed Produced</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-4xl font-black text-orange-900">{{ number_format($envImpact['animalFeedProduced'], 0) }}kg</p>
                        <span class="text-sm font-bold text-orange-700">/month</span>
                    </div>
                    <p class="text-xs text-orange-700 mt-3 font-medium italic">Supporting local farms and reducing feed costs</p>
                </div>

                <div class="border-2 border-yellow-100 rounded-xl p-6 bg-yellow-50/50">
                    <p class="text-xs font-black text-yellow-700 uppercase tracking-widest mb-1">Compost Produced</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-4xl font-black text-yellow-900">{{ number_format($envImpact['compostProduced'], 0) }}kg</p>
                        <span class="text-sm font-bold text-yellow-700">/month</span>
                    </div>
                    <p class="text-xs text-yellow-700 mt-3 font-medium italic">Enriching soil and supporting sustainable agriculture</p>
                </div>
            </div>

            <div class="border-2 border-green-100 rounded-2xl p-6 bg-gradient-to-r from-green-50 to-blue-50">
                <h3 class="font-black text-gray-900 text-sm uppercase tracking-widest mb-4">Environmental Impact Summary</h3>
                <div class="grid grid-cols-1 gap-3">
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-green-600 font-bold">✅</span>
                        <p class="text-gray-800"><strong>Waste Diversion:</strong> {{ $envImpact['diversionRate'] }}% diverted from landfills via recovery programs.</p>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-green-600 font-bold">✅</span>
                        <p class="text-gray-800"><strong>Carbon Footprint:</strong> Offset {{ $envImpact['totalCO2Offset'] }}kg CO₂ through composting and bio-gas.</p>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-green-600 font-bold">✅</span>
                        <p class="text-gray-800"><strong>Circular Economy:</strong> Produced {{ $envImpact['animalFeedProduced'] }}kg of feed for local livestock.</p>
                    </div>
                    @if($envImpact['wasteToLandfill'] > 0)
                    <div class="flex items-center gap-3 text-sm">
                        <span class="text-red-600 font-bold">⚠️</span>
                        <p class="text-red-900"><strong>Improvement Needed:</strong> {{ $envImpact['wasteToLandfill'] }}kg sent to landfill - explore more recovery options.</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="font-black text-gray-900 text-sm uppercase tracking-widest">Sustainability Goals Progress</h3>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                        <span>Waste Diversion Rate</span>
                        <span class="text-green-700">{{ $envImpact['diversionRate'] }}% / 85%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden border border-gray-200">
                        <div class="bg-green-600 h-full rounded-full transition-all duration-1000" style="width: {{ min($envImpact['diversionRate'], 100) }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                        <span>Carbon Offset Goal</span>
                        <span class="text-blue-700">{{ $envImpact['totalCO2Offset'] }}kg / 300kg</span>
                    </div>
                    <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden border border-gray-200">
                        <div class="bg-blue-600 h-full rounded-full transition-all duration-1000" style="width: {{ min(($envImpact['totalCO2Offset'] / 300) * 100, 100) }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                        <span>Zero Waste to Landfill</span>
                        @php
                            $landfillPct = 100 - (($envImpact['wasteToLandfill'] / ($envImpact['wasteDiverted'] + $envImpact['wasteToLandfill'])) * 100);
                        @endphp
                        <span class="text-yellow-700">{{ number_format($landfillPct, 1) }}% / 100%</span>
                    </div>
                    <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden border border-gray-200">
                        <div class="bg-yellow-500 h-full rounded-full transition-all duration-1000" style="width: {{ $landfillPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script>
    function showReport(type) {
        // Hide all contents
        document.querySelectorAll('.report-content').forEach(el => el.classList.add('hidden'));
        
        // Remove active ring from all tabs
        document.querySelectorAll('#report-tabs > div').forEach(el => el.classList.remove('ring-2', 'ring-[#D4A017]'));

        // Show selected content
        document.getElementById('report-' + type + '-content').classList.remove('hidden');
        
        // Highlight active tab
        document.getElementById('tab-' + type).classList.add('ring-2', 'ring-[#D4A017]');
    }
</script>

<style>
    /* Mimicking Shadcn active tab behavior */
    .active-tab {
        transform: translateY(-2px);
    }
</style>
@endsection