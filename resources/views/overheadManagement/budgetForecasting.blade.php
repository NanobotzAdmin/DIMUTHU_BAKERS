@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Budget Forecasting</h1>
                <p class="text-gray-600 mt-1">
                    Predict future overhead costs using historical data and trends
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-gear"></i>
                    Configure Model
                </button>
                <button
                    class="inline-flex items-center px-4 py-2 bg-[#D4A017] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#B8860B] focus:bg-[#B8860B] active:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-play-fill"></i>
                    Run Forecast
                </button>
            </div>
        </div>

        <!-- Forecasting Parameters -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Forecasting Parameters</h3>
                <p class="mt-1 text-sm text-gray-500">Configure forecast model settings</p>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Forecast Method</label>
                        <select
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="ml">Machine Learning</option>
                            <option value="linear">Linear Regression</option>
                            <option value="moving">Moving Average</option>
                            <option value="exponential">Exponential Smoothing</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Forecast Horizon</label>
                        <select
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="3">3 Months</option>
                            <option value="6">6 Months</option>
                            <option value="12" selected>12 Months</option>
                            <option value="24">24 Months</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Confidence Level</label>
                        <select
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="90">90%</option>
                            <option value="95" selected>95%</option>
                            <option value="99">99%</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Growth Rate Assumption: <span
                                id="growthRateValue">5</span>%</label>
                        <input type="range" min="-10" max="20" value="5"
                            class="w-full h-2 bg-gray-200 rounded-lg border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 appearance-none cursor-pointer"
                            oninput="document.getElementById('growthRateValue').innerText = this.value">
                    </div>
                </div>
            </div>
        </div>

        <!-- Forecast Visualization -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Overhead Forecast with Confidence Intervals
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">Historical data and predicted future values</p>
                    </div>
                    <button
                        class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 gap-2">
                        <i class="bi bi-download"></i>
                        Export Forecast
                    </button>
                </div>

                <div class="relative h-96 w-full">
                    <canvas id="forecastChart"></canvas>
                </div>

                <!-- Forecast Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <div class="text-sm text-gray-600 mb-1">Last Actual (Mar 24)</div>
                        <div class="text-xl font-bold text-blue-600">Rs. 57,000</div>
                    </div>
                    <div class="bg-[#D4A017]/10 p-4 rounded-lg border border-[#D4A017]/20">
                        <div class="text-sm text-gray-600 mb-1">Forecast (Dec 24)</div>
                        <div class="text-xl font-bold text-[#D4A017]">Rs. 64,000</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                        <div class="text-sm text-gray-600 mb-1">Expected Growth</div>
                        <div class="text-xl font-bold text-green-600">+12.3%</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                        <div class="text-sm text-gray-600 mb-1">Forecast Accuracy</div>
                        <div class="text-xl font-bold text-purple-600">94.5%</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cost Pool Forecasts -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Cost Pool Forecasts</h3>
                <p class="mt-1 text-sm text-gray-500">Predicted monthly costs by cost pool</p>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cost Pool</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Current Monthly</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Forecast Monthly</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Change</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Growth Rate</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Confidence</th>
                                <th
                                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Key Driver</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($costPoolForecasts as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $item['costPool'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-blue-600">Rs.
                                        {{ number_format($item['currentMonthly']) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-[#D4A017]">Rs.
                                        {{ number_format($item['forecastMonthly']) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        <span
                                            class="{{ $item['forecastMonthly'] > $item['currentMonthly'] ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $item['forecastMonthly'] > $item['currentMonthly'] ? '+' : '' }}
                                            Rs. {{ number_format($item['forecastMonthly'] - $item['currentMonthly']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        <span
                                            class="inline-flex items-center {{ $item['growthRate'] > 0 ? 'text-red-600' : 'text-gray-600' }}">
                                            @if($item['growthRate'] > 0)
                                                <i class="bi bi-graph-up-arrow mr-1"></i>
                                                +{{ $item['growthRate'] }}%
                                            @else
                                                {{ $item['growthRate'] }}%
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['confidence'] === 'high' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($item['confidence']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['driver'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Total</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-blue-600">
                                    Rs. {{ number_format(collect($costPoolForecasts)->sum('currentMonthly')) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-[#D4A017]">
                                    Rs. {{ number_format(collect($costPoolForecasts)->sum('forecastMonthly')) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">
                                    +Rs.
                                    {{ number_format(collect($costPoolForecasts)->sum('forecastMonthly') - collect($costPoolForecasts)->sum('currentMonthly')) }}
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Scenario Analysis -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center gap-2">
                    <i class="bi bi-lightning-fill text-[#D4A017]"></i>
                    Scenario Analysis
                </h3>
                <p class="mt-1 text-sm text-gray-500">Compare different forecast scenarios</p>

                <div class="mt-6 flex flex-col gap-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Scenario</th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Q1 2024</th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Q2 2024</th>
                                    <th
                                        class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Annual Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($scenarioComparison as $scenario)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $scenario['scenario'] === 'Base Case' ? 'bg-[#D4A017] text-white' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $scenario['scenario'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rs.
                                            {{ number_format($scenario['q1']) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">Rs.
                                            {{ number_format($scenario['q2']) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold">Rs.
                                            {{ number_format($scenario['total']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="h-64">
                        <canvas id="scenarioChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forecast Insights -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-l-blue-600">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center gap-2">
                    <i class="bi bi-exclamation-circle-fill text-blue-600"></i>
                    Forecast Insights & Recommendations
                </h3>

                <div class="space-y-3 mt-4">
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="font-medium text-blue-900 mb-1">Upward Trend Detected</div>
                        <div class="text-sm text-gray-600">
                            Overall overhead costs show a consistent upward trend of approximately 8% annually.
                            This is driven primarily by utilities and quality control costs.
                        </div>
                    </div>
                    <div class="p-3 bg-[#D4A017]/10 rounded-lg border border-[#D4A017]/20">
                        <div class="font-medium text-[#D4A017] mb-1">Seasonal Patterns</div>
                        <div class="text-sm text-gray-600">
                            Historical data shows higher overhead costs in Q2 and Q4, likely due to seasonal
                            production volume increases. Consider adjusting budgets accordingly.
                        </div>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                        <div class="font-medium text-green-900 mb-1">Forecast Confidence</div>
                        <div class="text-sm text-gray-600">
                            The model shows high confidence (94.5% accuracy) for the next 6 months.
                            Confidence decreases for predictions beyond 9 months due to market uncertainties.
                        </div>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-lg border border-purple-100">
                        <div class="font-medium text-purple-900 mb-1">Budget Recommendation</div>
                        <div class="text-sm text-gray-600">
                            Based on the base case scenario, recommend setting annual budget at Rs. 720,000
                            with quarterly reviews to adjust for variances.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data passed from controller
            const forecastData = @json($forecastData);
            const scenarioData = @json($scenarioComparison);
            const historicalData = @json($historicalData);

            // --- Forecast Line Chart ---
            const forecastCtx = document.getElementById('forecastChart').getContext('2d');

            // Prepare labels (months)
            const labels = [...historicalData.map(d => d.month), ...forecastData.map(d => d.month)];

            // Prepare datasets
            const actuals = [...historicalData.map(d => d.actual), ...forecastData.map(d => d.actual)]; // Should have nulls for future
            const forecasts = new Array(historicalData.length).fill(null).concat(forecastData.map(d => d.forecast));
            const upperBounds = new Array(historicalData.length).fill(null).concat(forecastData.map(d => d.upper));
            const lowerBounds = new Array(historicalData.length).fill(null).concat(forecastData.map(d => d.lower));

            new Chart(forecastCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Upper Bound',
                            data: upperBounds,
                            borderColor: 'transparent',
                            backgroundColor: 'rgba(212, 160, 23, 0.1)',
                            fill: '+1', // Fill to next dataset (Lower Bound)
                            pointRadius: 0,
                            tension: 0.4
                        },
                        {
                            label: 'Lower Bound',
                            data: lowerBounds,
                            borderColor: 'transparent',
                            backgroundColor: 'rgba(212, 160, 23, 0.1)', // Same color to create the band
                            fill: false,
                            pointRadius: 0,
                            tension: 0.4
                        },
                        {
                            label: 'Actual',
                            data: actuals,
                            borderColor: '#3b82f6',
                            backgroundColor: '#3b82f6',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: false
                        },
                        {
                            label: 'Forecast',
                            data: forecasts,
                            borderColor: '#D4A017',
                            backgroundColor: '#D4A017',
                            borderWidth: 2,
                            borderDash: [5, 5],
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
                        },
                        legend: {
                            labels: {
                                filter: function (item, chart) {
                                    // Hide Upper/Lower bound legends if desired, or keep them
                                    return !item.text.includes('Bound');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function (value, index, values) {
                                    return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'LKR', maximumSignificantDigits: 3 }).format(value);
                                }
                            }
                        }
                    }
                }
            });

            // --- Scenario Bar Chart ---
            const scenarioCtx = document.getElementById('scenarioChart').getContext('2d');

            new Chart(scenarioCtx, {
                type: 'bar',
                data: {
                    labels: scenarioData.map(d => d.scenario),
                    datasets: [
                        {
                            label: 'Q1',
                            data: scenarioData.map(d => d.q1),
                            backgroundColor: '#3b82f6',
                        },
                        {
                            label: 'Q2',
                            data: scenarioData.map(d => d.q2),
                            backgroundColor: '#10b981',
                        },
                        {
                            label: 'Q3',
                            data: scenarioData.map(d => d.q3),
                            backgroundColor: '#D4A017',
                        },
                        {
                            label: 'Q4',
                            data: scenarioData.map(d => d.q4),
                            backgroundColor: '#8b5cf6',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value, index, values) {
                                    return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'LKR', notation: "compact" }).format(value);
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection