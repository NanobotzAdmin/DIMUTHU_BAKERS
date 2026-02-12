@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Overhead Analytics</h1>
                <p class="text-gray-600 mt-1">
                    Comprehensive overhead cost analysis and insights
                </p>
            </div>
            <div class="flex items-center gap-3">
                <select
                    class="block w-[180px] pl-3 pr-10 py-2 text-base border bg-gray-50 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="1month">Last Month</option>
                    <option value="3months">Last 3 Months</option>
                    <option value="6months" selected>Last 6 Months</option>
                    <option value="1year">Last Year</option>
                    <option value="custom">Custom Range</option>
                </select>
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-funnel"></i>
                    Filter
                </button>
                <button
                    class="inline-flex items-center px-4 py-2 bg-[#D4A017] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#B8860B] focus:bg-[#B8860B] active:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-download"></i>
                    Export Report
                </button>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gray-100 rounded-lg text-[#D4A017]">
                                <i class="bi bi-currency-dollar text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Overhead</p>
                                <p class="text-2xl mt-1 font-bold">Rs. 53,000</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-green-600">
                            <i class="bi bi-arrow-up-right"></i>
                            <span class="text-sm">4.2%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gray-100 rounded-lg text-green-600">
                                <i class="bi bi-bullseye text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Allocated Overhead</p>
                                <p class="text-2xl mt-1 font-bold">Rs. 52,000</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-green-600">
                            <i class="bi bi-arrow-up-right"></i>
                            <span class="text-sm">4.8%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gray-100 rounded-lg text-blue-600">
                                <i class="bi bi-graph-up-arrow text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Allocation Rate</p>
                                <p class="text-2xl mt-1 font-bold">98.1%</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-green-600">
                            <i class="bi bi-arrow-up-right"></i>
                            <span class="text-sm">0.5%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gray-100 rounded-lg text-purple-600">
                                <i class="bi bi-box-seam text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Active Cost Pools</p>
                                <p class="text-2xl mt-1 font-bold">5</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-green-600">
                            <i class="bi bi-arrow-up-right"></i>
                            <span class="text-sm">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Overhead Cost Trend -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-1">Overhead Cost Trend</h3>
                    <p class="text-sm text-gray-500 mb-4">Monthly overhead vs allocated costs</p>
                    <div class="h-80">
                        <canvas id="overheadTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Cost Pool Distribution -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-1">Cost Pool Distribution</h3>
                    <p class="text-sm text-gray-500 mb-4">Breakdown of overhead by cost pool</p>
                    <div class="h-80 relative">
                        <canvas id="costPoolPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Cost Analysis -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center gap-2 mb-1">
                    <i class="bi bi-activity text-[#D4A017]"></i>
                    Activity Cost Analysis
                </h3>
                <p class="text-sm text-gray-500 mb-4">Cost breakdown by activity driver</p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Activity</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Cost</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Driver Quantity</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rate per Unit</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    % of Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($activityCostData as $activity)
                                @php
                                    $totalCost = collect($activityCostData)->sum('cost');
                                    $percentage = ($activity['cost'] / $totalCost) * 100;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $activity['activity'] }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900">Rs.
                                        {{ number_format($activity['cost']) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900">
                                        {{ number_format($activity['driver']) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-gray-900">Rs.
                                        {{ number_format($activity['rate'], 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ number_format($percentage, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                            <tr>
                                <td class="px-4 py-3 font-medium text-gray-900">Total</td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900">
                                    Rs. {{ number_format(collect($activityCostData)->sum('cost')) }}
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Product Cost Breakdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-1">Product Cost Breakdown</h3>
                    <p class="text-sm text-gray-500 mb-4">Total cost composition by product</p>
                    <div class="h-80">
                        <canvas id="productCostChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Allocation Rate Trends -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-1">Allocation Rate Trends</h3>
                    <p class="text-sm text-gray-500 mb-4">Activity rates over time</p>
                    <div class="h-80">
                        <canvas id="rateTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Profitability Analysis -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center gap-2 mb-1">
                    <i class="bi bi-box text-[#D4A017]"></i>
                    Product Profitability Analysis
                </h3>
                <p class="text-sm text-gray-500 mb-4">Product costs with overhead allocation and profit margins</p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Materials</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Labor</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Overhead</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Cost</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Margin %</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($productAllocationData as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $product['product'] }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-blue-600">Rs.
                                        {{ number_format($product['materials']) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-green-600">Rs.
                                        {{ number_format($product['labor']) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm text-[#D4A017]">Rs.
                                        {{ number_format($product['overhead']) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium text-gray-900">Rs.
                                        {{ number_format($product['total']) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product['margin'] >= 30 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $product['margin'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Insights & Recommendations -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-l-[#D4A017]">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center gap-2 mb-4">
                    <i class="bi bi-graph-up-arrow text-[#D4A017]"></i>
                    Key Insights & Recommendations
                </h3>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="w-2 h-2 bg-green-600 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="font-medium text-gray-900">High Allocation Rate</p>
                            <p class="text-sm text-gray-600">
                                Current overhead allocation rate is 98.1%, indicating efficient overhead distribution.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-2 h-2 bg-yellow-600 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="font-medium text-gray-900">Setup Cost Increasing</p>
                            <p class="text-sm text-gray-600">
                                Setup hours activity rate has increased 4.3% over 6 months. Consider process optimization.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="font-medium text-gray-900">Product Mix Optimization</p>
                            <p class="text-sm text-gray-600">
                                Danish and Croissant show highest margins (35% and 32%). Consider increasing production
                                volume.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-2 h-2 bg-red-600 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="font-medium text-gray-900">Utilities Cost Pool Growth</p>
                            <p class="text-sm text-gray-600">
                                Utilities represent 28.3% of overhead. Review energy efficiency opportunities.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // 1. Overhead Cost Trend (Area Chart)
            const overheadTrendCtx = document.getElementById('overheadTrendChart').getContext('2d');
            const trendData = @json($monthlyTrendData);
            new Chart(overheadTrendCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.month),
                    datasets: [
                        {
                            label: 'Total Overhead',
                            data: trendData.map(d => d.overhead),
                            borderColor: '#D4A017',
                            backgroundColor: 'rgba(212, 160, 23, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Allocated',
                            data: trendData.map(d => d.allocated),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: false } // Start Y axis based on data range
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': Rs. ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // 2. Cost Pool Distribution (Pie Chart)
            const costPoolCtx = document.getElementById('costPoolPieChart').getContext('2d');
            const poolData = @json($costPoolDistribution);
            new Chart(costPoolCtx, {
                type: 'doughnut', // Doughnut usually looks better for distribution
                data: {
                    labels: poolData.map(d => d.name),
                    datasets: [{
                        data: poolData.map(d => d.value),
                        backgroundColor: poolData.map(d => d.color),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    if (label) label += ': ';
                                    label += 'Rs. ' + context.parsed.toLocaleString();
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            // 3. Product Cost Breakdown (Stacked Bar)
            const productCostCtx = document.getElementById('productCostChart').getContext('2d');
            const productData = @json($productAllocationData);
            new Chart(productCostCtx, {
                type: 'bar',
                data: {
                    labels: productData.map(d => d.product),
                    datasets: [
                        { label: 'Materials', data: productData.map(d => d.materials), backgroundColor: '#3b82f6' },
                        { label: 'Labor', data: productData.map(d => d.labor), backgroundColor: '#10b981' },
                        { label: 'Overhead', data: productData.map(d => d.overhead), backgroundColor: '#D4A017' }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true },
                        y: { stacked: true }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': Rs. ' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // 4. Allocation Rate Trends (Line Chart)
            const rateTrendCtx = document.getElementById('rateTrendChart').getContext('2d');
            const rateData = @json($allocationRateTrend);
            new Chart(rateTrendCtx, {
                type: 'line',
                data: {
                    labels: rateData.map(d => d.month),
                    datasets: [
                        { label: 'Machine Hours', data: rateData.map(d => d.machineHours), borderColor: '#D4A017', tension: 0.3 },
                        { label: 'Labor Hours', data: rateData.map(d => d.laborHours), borderColor: '#10b981', tension: 0.3 },
                        { label: 'Setup Hours', data: rateData.map(d => d.setupHours), borderColor: '#3b82f6', tension: 0.3 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': Rs. ' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    }
                }
            });

        });
    </script>
@endsection