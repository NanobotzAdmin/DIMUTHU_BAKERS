@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Variance Analysis</h1>
                <p class="text-gray-600 mt-1">
                    Analyze budget vs actual variances and identify trends
                </p>
            </div>
            <div class="flex items-center gap-3">
                <select
                    class="block w-48 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white border">
                    <option value="current-month">Current Month</option>
                    <option value="last-month">Last Month</option>
                    <option value="current-quarter">Current Quarter</option>
                    <option value="ytd">Year to Date</option>
                    <option value="last-year">Last Year</option>
                </select>
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-download"></i>
                    Export Report
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Total Budget -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="bi bi-bullseye text-blue-600 text-lg"></i>
                    </div>
                    <div class="text-sm text-gray-600">Total Budget</div>
                </div>
                <div class="text-2xl mt-1 font-bold text-gray-900">
                    Rs. {{ number_format($totalBudget) }}
                </div>
            </div>

            <!-- Actual Spend -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-[#D4A017]/10 rounded-lg">
                        <i class="bi bi-currency-dollar text-[#D4A017] text-lg"></i>
                    </div>
                    <div class="text-sm text-gray-600">Actual Spend</div>
                </div>
                <div class="text-2xl mt-1 font-bold text-[#D4A017]">
                    Rs. {{ number_format($totalActual) }}
                </div>
            </div>

            <!-- Total Variance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 rounded-lg {{ $totalVariance > 0 ? 'bg-red-100' : 'bg-green-100' }}">
                        <i
                            class="bi {{ $totalVariance > 0 ? 'bi-graph-up-arrow text-red-600' : 'bi-graph-down-arrow text-green-600' }} text-lg"></i>
                    </div>
                    <div class="text-sm text-gray-600">Total Variance</div>
                </div>
                <div class="text-2xl mt-1 font-bold {{ $totalVariance > 0 ? 'text-red-600' : 'text-green-600' }}">
                    Rs. {{ number_format(abs($totalVariance)) }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    {{ $totalVariance > 0 ? '+' : '' }}{{ $totalVariancePercent }}%
                </div>
            </div>

            <!-- Critical Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
                    </div>
                    <div class="text-sm text-gray-600">Critical Items</div>
                </div>
                <div class="text-2xl mt-1 font-bold text-red-600">
                    {{ $criticalVariancesCount }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Require attention
                </div>
            </div>

            <!-- Favorable Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i class="bi bi-check-circle-fill text-green-600 text-lg"></i>
                    </div>
                    <div class="text-sm text-gray-600">Favorable</div>
                </div>
                <div class="text-2xl mt-1 font-bold text-green-600">
                    {{ $favorableVariancesCount }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Under budget
                </div>
            </div>
        </div>

        <!-- Variance Trend Chart -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Variance Trend</h3>
                <p class="mt-1 text-sm text-gray-500">Budget vs Actual comparison over time</p>

                <div class="relative h-72 w-full mt-4">
                    <canvas id="varianceTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button onclick="switchTab('cost-pool')" id="tab-cost-pool"
                        class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm border-indigo-500 text-indigo-600 hover:text-indigo-800 hover:border-indigo-300">
                        By Cost Pool
                    </button>
                    <button onclick="switchTab('category')" id="tab-category"
                        class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        By Category
                    </button>
                    <button onclick="switchTab('monthly')" id="tab-monthly"
                        class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Monthly Detail
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Cost Pool Content -->
                <div id="content-cost-pool" class="tab-content block">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Cost Pool Variance Analysis</h3>
                            <p class="text-sm text-gray-500">Detailed variance by cost pool</p>
                        </div>
                        <select
                            class="block w-48 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white border">
                            <option value="all">All Categories</option>
                            <option value="fixed">Fixed Costs</option>
                            <option value="variable">Variable Costs</option>
                            <option value="semi-variable">Semi-Variable</option>
                        </select>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cost Pool</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Budget</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actual</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Variance (Rs.)</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Variance (%)</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Analysis</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($costPoolVariances as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $item['costPool'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-blue-600">Rs.
                                            {{ number_format($item['budget']) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-[#D4A017]">Rs.
                                            {{ number_format($item['actual']) }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $item['variance'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $item['variance'] > 0 ? '+' : '' }}Rs. {{ number_format($item['variance']) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">
                                            <span
                                                class="inline-flex items-center gap-1 {{ abs($item['variancePercent']) > 15 ? 'text-red-600' : (abs($item['variancePercent']) > 10 ? 'text-orange-600' : ($item['variancePercent'] < 0 ? 'text-green-600' : 'text-gray-600')) }}">
                                                @if($item['variance'] > 0) <i class="bi bi-graph-up-arrow text-xs"></i> @endif
                                                @if($item['variance'] < 0) <i class="bi bi-graph-down-arrow text-xs"></i> @endif
                                                {{ $item['variance'] > 0 ? '+' : '' }}{{ $item['variancePercent'] }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($item['status'] == 'critical') bg-red-100 text-red-800 
                                                @elseif($item['status'] == 'warning') bg-gray-100 text-gray-800 
                                                @elseif($item['status'] == 'favorable') bg-green-100 text-green-800 
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst($item['status']) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['reason'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 font-bold">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Total</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-blue-600">Rs.
                                        {{ number_format($totalBudget) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-[#D4A017]">Rs.
                                        {{ number_format($totalActual) }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $totalVariance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $totalVariance > 0 ? '+' : '' }}Rs. {{ number_format($totalVariance) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        {{ $totalVariance > 0 ? '+' : '' }}{{ $totalVariancePercent }}%
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Category Content -->
                <div id="content-category" class="tab-content hidden">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Variance by Cost Category</h3>
                        <p class="text-sm text-gray-500">Fixed vs Variable cost variance comparison</p>
                    </div>

                    <div class="relative h-72 w-full mb-6">
                        <canvas id="categoryChart"></canvas>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($varianceByCategory as $cat)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <div class="text-sm font-medium mb-3 text-gray-900">{{ $cat['category'] }}</div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Budget</span>
                                        <span class="text-blue-600 font-medium">Rs. {{ number_format($cat['budget']) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Actual</span>
                                        <span class="text-[#D4A017] font-medium">Rs. {{ number_format($cat['actual']) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm pt-2 border-t border-gray-100">
                                        <span class="text-gray-600">Variance</span>
                                        <span
                                            class="font-medium {{ $cat['variance'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $cat['variance'] > 0 ? '+' : '' }}Rs. {{ number_format($cat['variance']) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Variance %</span>
                                        <span
                                            class="font-medium {{ $cat['variance'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format(($cat['variance'] / $cat['budget']) * 100, 1) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Monthly Content -->
                <div id="content-monthly" class="tab-content hidden space-y-6">
                    @foreach($monthlyDetailedVariance as $monthlyData)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">{{ $monthlyData['month'] }} Variance Details</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Cost Pool</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Budget</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actual</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Variance</th>
                                            <th
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                %</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($monthlyData['costPools'] as $pool)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pool['name'] }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-blue-600">Rs.
                                                    {{ number_format($pool['budget']) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-[#D4A017]">Rs.
                                                    {{ number_format($pool['actual']) }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $pool['variance'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ $pool['variance'] > 0 ? '+' : '' }}Rs. {{ number_format($pool['variance']) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">
                                                    {{ $pool['variance'] > 0 ? '+' : '' }}{{ number_format(($pool['variance'] / $pool['budget']) * 100, 1) }}%
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50 font-bold">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Total</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-blue-600">
                                                Rs. {{ number_format(collect($monthlyData['costPools'])->sum('budget')) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-[#D4A017]">
                                                Rs. {{ number_format(collect($monthlyData['costPools'])->sum('actual')) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                                Rs. {{ number_format(collect($monthlyData['costPools'])->sum('variance')) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Insights Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Critical Variances -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-l-red-600">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center gap-2 mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
                        Critical Variances Requiring Action
                    </h3>
                    <div class="space-y-3">
                        @foreach(collect($costPoolVariances)->where('status', 'critical') as $item)
                            <div class="p-3 bg-red-50 rounded-lg">
                                <div class="flex items-start justify-between mb-1">
                                    <div class="font-medium text-gray-900">{{ $item['costPool'] }}</div>
                                    <span
                                        className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        +{{ $item['variancePercent'] }}%
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">{{ $item['reason'] }}</div>
                                <div class="text-sm text-red-600 mt-1 font-medium">
                                    Over budget by Rs. {{ number_format($item['variance']) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Favorable Performance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-l-green-600">
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center gap-2 mb-4">
                        <i class="bi bi-check-circle-fill text-green-600"></i>
                        Favorable Performance
                    </h3>
                    <div class="space-y-3">
                        @foreach(collect($costPoolVariances)->where('status', 'favorable') as $item)
                            <div class="p-3 bg-green-50 rounded-lg">
                                <div class="flex items-start justify-between mb-1">
                                    <div class="font-medium text-gray-900">{{ $item['costPool'] }}</div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ $item['variancePercent'] }}%
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">{{ $item['reason'] }}</div>
                                <div class="text-sm text-green-600 mt-1 font-medium">
                                    Saved Rs. {{ number_format(abs($item['variance'])) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching Logic
        function switchTab(tabName) {
            // Update Buttons
            document.querySelectorAll('nav button').forEach(btn => {
                btn.classList.remove('border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById('tab-' + tabName).classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('tab-' + tabName).classList.add('border-indigo-500', 'text-indigo-600');

            // Update Content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById('content-' + tabName).classList.remove('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Data from Controller
            const varianceTrendData = @json($varianceTrendData);
            const varianceByCategory = @json($varianceByCategory);

            // --- Variance Trend Chart (Line + Area) ---
            const trendCtx = document.getElementById('varianceTrendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: varianceTrendData.map(d => d.month),
                    datasets: [
                        {
                            label: 'Budget',
                            data: varianceTrendData.map(d => d.budget),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0
                        },
                        {
                            label: 'Actual',
                            data: varianceTrendData.map(d => d.actual),
                            borderColor: '#D4A017',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function (value) {
                                    return 'Rs. ' + new Intl.NumberFormat('en-IN', { notation: "compact" }).format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'LKR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            // --- Category Variance Chart (Bar) ---
            // Requires Tab to be visible to render properly initially, or handled on tab switch
            // Chart.js handles hidden canvases reasonably well but let's initialize it.
            const catCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(catCtx, {
                type: 'bar',
                data: {
                    labels: varianceByCategory.map(d => d.category),
                    datasets: [
                        {
                            label: 'Budget',
                            data: varianceByCategory.map(d => d.budget),
                            backgroundColor: '#3b82f6',
                            borderRadius: 4
                        },
                        {
                            label: 'Actual',
                            data: varianceByCategory.map(d => d.actual),
                            backgroundColor: '#D4A017',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rs. ' + new Intl.NumberFormat('en-IN', { notation: "compact" }).format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'LKR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection