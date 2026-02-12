@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Product Costing Analysis</h1>
                <p class="text-gray-600 mt-1">
                    Detailed cost breakdown and profitability analysis by product
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-calculator"></i>
                    Cost Simulator
                </button>
                <button
                    class="inline-flex items-center px-4 py-2 bg-[#D4A017] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#B8860B] focus:bg-[#B8860B] active:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-download"></i>
                    Export Analysis
                </button>
            </div>
        </div>

        <!-- Summary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="bi bi-box-seam text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Products</p>
                            <p class="text-2xl mt-1 font-bold">{{ count($productCostData) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="bi bi-currency-dollar text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Avg. Margin</p>
                            <p class="text-2xl mt-1 font-bold">
                                {{ number_format(collect($productCostData)->avg('marginPercent'), 1) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-[#D4A017]/20 rounded-lg">
                            <i class="bi bi-bullseye text-[#D4A017] text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Avg. Overhead</p>
                            <p class="text-2xl mt-1 font-bold">
                                Rs. {{ number_format(collect($productCostData)->avg('overhead')) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="bi bi-graph-up text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Top Margin</p>
                            <p class="text-2xl mt-1 font-bold">
                                {{ number_format(collect($productCostData)->max('marginPercent'), 1) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" placeholder="Search products by name or SKU..."
                                class="pl-10 block w-full p-3 border bg-gray-50 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    <select
                        class="block w-[180px] pl-3 pr-10 py-2 text-base border bg-gray-50 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="all">All Categories</option>
                        <option value="Bread">Bread</option>
                        <option value="Pastry">Pastry</option>
                    </select>
                    <select
                        class="block w-[200px] pl-3 pr-10 py-2 text-base border bg-gray-50 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="name">Sort by Name</option>
                        <option value="margin-high">Margin (High to Low)</option>
                        <option value="margin-low">Margin (Low to High)</option>
                        <option value="cost-high">Cost (High to Low)</option>
                        <option value="cost-low">Cost (Low to High)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="space-y-4">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button onclick="switchTab('breakdown')" id="tab-breakdown"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm border-indigo-500 text-indigo-600">
                        Cost Breakdown
                    </button>
                    <button onclick="switchTab('comparison')" id="tab-comparison"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Comparison
                    </button>
                    <button onclick="switchTab('trends')" id="tab-trends"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Trends
                    </button>
                    <button onclick="switchTab('scenarios')" id="tab-scenarios"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Scenarios
                    </button>
                </nav>
            </div>

            <!-- Cost Breakdown Content -->
            <div id="content-breakdown" class="tab-content">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($productCostData as $product)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $product['name'] }}</h3>
                                        <p class="text-sm text-gray-600">{{ $product['sku'] }} • {{ $product['category'] }}</p>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product['status'] === 'profitable' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        <i
                                            class="bi {{ $product['status'] === 'profitable' ? 'bi-check-circle' : 'bi-exclamation-triangle' }} mr-1"></i>
                                        {{ number_format($product['marginPercent'], 1) }}%
                                    </span>
                                </div>

                                <div class="space-y-3">
                                    <!-- Materials -->
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Materials</span>
                                            <span class="font-medium">Rs. {{ number_format($product['materials']) }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full"
                                                style="width: {{ ($product['materials'] / $product['totalCost']) * 100 }}%">
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ number_format(($product['materials'] / $product['totalCost']) * 100, 1) }}%</div>
                                    </div>
                                    <!-- Labor -->
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Labor</span>
                                            <span class="font-medium">Rs. {{ number_format($product['labor']) }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full"
                                                style="width: {{ ($product['labor'] / $product['totalCost']) * 100 }}%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ number_format(($product['labor'] / $product['totalCost']) * 100, 1) }}%</div>
                                    </div>
                                    <!-- Overhead -->
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600">Overhead</span>
                                            <span class="font-medium">Rs. {{ number_format($product['overhead']) }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-[#D4A017] h-2 rounded-full"
                                                style="width: {{ ($product['overhead'] / $product['totalCost']) * 100 }}%">
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ number_format(($product['overhead'] / $product['totalCost']) * 100, 1) }}%</div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                    <div>
                                        <div class="text-sm text-gray-600">Total Cost</div>
                                        <div class="text-xl font-medium text-gray-900">Rs.
                                            {{ number_format($product['totalCost']) }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-600">Margin</div>
                                        <div
                                            class="text-xl font-medium {{ $product['status'] === 'profitable' ? 'text-green-600' : 'text-orange-600' }}">
                                            Rs. {{ number_format($product['margin']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Comparison Content -->
            <div id="content-comparison" class="tab-content hidden space-y-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Cost Component Comparison</h3>
                        <div class="h-96">
                            <canvas id="costComparisonChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Detailed Product Comparison</h3>
                        <p class="mt-1 text-sm text-gray-500">Side-by-side cost and margin analysis</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Materials</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Labor</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Overhead</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Cost</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Price</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Margin</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($productCostData as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $product['name'] }}</div>
                                            <div class="text-sm text-gray-600">{{ $product['sku'] }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm text-blue-600">Rs.
                                            {{ number_format($product['materials']) }}</td>
                                        <td class="px-6 py-4 text-right text-sm text-green-600">Rs.
                                            {{ number_format($product['labor']) }}</td>
                                        <td class="px-6 py-4 text-right text-sm text-[#D4A017]">Rs.
                                            {{ number_format($product['overhead']) }}</td>
                                        <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">Rs.
                                            {{ number_format($product['totalCost']) }}</td>
                                        <td class="px-6 py-4 text-right text-sm text-gray-900">Rs.
                                            {{ number_format($product['sellingPrice']) }}</td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product['marginPercent'] >= 30 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ number_format($product['marginPercent'], 1) }}%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            @if($product['trend'] == 'up') <i class="bi bi-arrow-up-right text-green-600"></i>
                                            @elseif($product['trend'] == 'down') <i
                                                class="bi bi-arrow-down-right text-red-600"></i>
                                            @else <i class="bi bi-dash text-gray-400 font-bold" style="font-size: 1.2em;"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Trends Content -->
            <div id="content-trends" class="tab-content hidden space-y-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Profitability Trend Analysis</h3>
                        <div class="h-96">
                            <canvas id="profitabilityTrendChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Top Performers -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Top Performers</h3>
                            <p class="mt-1 text-sm text-gray-500">Products with highest margins</p>
                        </div>
                        <div class="p-6 space-y-3">
                            @foreach(collect($productCostData)->sortByDesc('marginPercent')->take(5) as $index => $product)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 bg-[#D4A017] text-white rounded-full flex items-center justify-center text-sm font-medium">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $product['name'] }}</div>
                                            <div class="text-sm text-gray-600">Rs. {{ number_format($product['totalCost']) }}
                                                cost</div>
                                        </div>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ number_format($product['marginPercent'], 1) }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Needs Review -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Needs Review</h3>
                            <p class="mt-1 text-sm text-gray-500">Products with lower margins</p>
                        </div>
                        <div class="p-6 space-y-3">
                            @foreach(collect($productCostData)->sortBy('marginPercent')->take(5) as $index => $product)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <i class="bi bi-exclamation-triangle text-orange-600 text-xl"></i>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $product['name'] }}</div>
                                            <div class="text-sm text-gray-600">Rs. {{ number_format($product['totalCost']) }}
                                                cost</div>
                                        </div>
                                    </div>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ number_format($product['marginPercent'], 1) }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scenarios Content -->
            <div id="content-scenarios" class="tab-content hidden space-y-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">What-If Scenario Analysis</h3>
                        <div class="h-96">
                            <canvas id="scenarioChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-l-green-600">
                        <div class="p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4">Reduce Setup Time</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Potential Savings</span>
                                    <span class="font-medium text-green-600">Rs. 1,200</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Margin Improvement</span>
                                    <span class="font-medium text-green-600">+1.5%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Implementation</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Medium</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-l-blue-600">
                        <div class="p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4">Optimize Energy Usage</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Potential Savings</span>
                                    <span class="font-medium text-blue-600">Rs. 900</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Margin Improvement</span>
                                    <span class="font-medium text-blue-600">+0.9%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Implementation</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Easy</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-l-purple-600">
                        <div class="p-6">
                            <h3 class="text-base font-semibold text-gray-900 mb-4">Improve QC Efficiency</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Potential Savings</span>
                                    <span class="font-medium text-purple-600">Rs. 600</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Margin Improvement</span>
                                    <span class="font-medium text-purple-600">+0.6%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Implementation</span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Hard</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching Logic
        function switchTab(tabName) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Show selected content
            document.getElementById('content-' + tabName).classList.remove('hidden');

            // Reset all tabs styles
            document.querySelectorAll('nav button').forEach(el => {
                el.classList.remove('border-indigo-500', 'text-indigo-600');
                el.classList.add('border-transparent', 'text-gray-500');
            });
            // Set active tab style
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            activeTab.classList.add('border-indigo-500', 'text-indigo-600');
        }

        // Chart.js Implementations
        document.addEventListener('DOMContentLoaded', function () {

            // 1. Cost Component Comparison Chart (Stacked Bar)
            const costCtx = document.getElementById('costComparisonChart').getContext('2d');
            const costData = @json($costComparisonData);
            new Chart(costCtx, {
                type: 'bar',
                data: {
                    labels: costData.map(d => d.name),
                    datasets: [
                        {
                            label: 'Materials',
                            data: costData.map(d => d.materials),
                            backgroundColor: '#3b82f6',
                        },
                        {
                            label: 'Labor',
                            data: costData.map(d => d.labor),
                            backgroundColor: '#10b981',
                        },
                        {
                            label: 'Overhead',
                            data: costData.map(d => d.overhead),
                            backgroundColor: '#D4A017',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true },
                        y: {
                            stacked: true,
                            title: { display: true, text: 'Percentage' }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + context.parsed.y + '%';
                                }
                            }
                        }
                    }
                }
            });

            // 2. Profitability Trend Chart (Line)
            const trendCtx = document.getElementById('profitabilityTrendChart').getContext('2d');
            const trendData = @json($profitabilityTrend);
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.month),
                    datasets: [
                        {
                            label: 'Baguette',
                            data: trendData.map(d => d.baguette),
                            borderColor: '#3b82f6',
                            tension: 0.3
                        },
                        {
                            label: 'Croissant',
                            data: trendData.map(d => d.croissant),
                            borderColor: '#10b981',
                            tension: 0.3
                        },
                        {
                            label: 'Danish',
                            data: trendData.map(d => d.danish),
                            borderColor: '#D4A017',
                            tension: 0.3
                        },
                        {
                            label: 'Sourdough',
                            data: trendData.map(d => d.sourdough),
                            borderColor: '#8b5cf6',
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { title: { display: true, text: 'Margin %' } }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + context.parsed.y + '%';
                                }
                            }
                        }
                    }
                }
            });

            // 3. Scenario Analysis Chart (Combo: Bar + Line)
            const scenarioCtx = document.getElementById('scenarioChart').getContext('2d');
            const scenarioData = @json($scenarioComparison);
            new Chart(scenarioCtx, {
                data: {
                    labels: scenarioData.map(d => d.scenario),
                    datasets: [
                        {
                            type: 'bar',
                            label: 'Baguette Cost',
                            data: scenarioData.map(d => d.baguette),
                            backgroundColor: '#3b82f6',
                            yAxisID: 'y',
                        },
                        {
                            type: 'bar',
                            label: 'Croissant Cost',
                            data: scenarioData.map(d => d.croissant),
                            backgroundColor: '#10b981',
                            yAxisID: 'y',
                        },
                        {
                            type: 'bar',
                            label: 'Danish Cost',
                            data: scenarioData.map(d => d.danish),
                            backgroundColor: '#D4A017',
                            yAxisID: 'y',
                        },
                        {
                            type: 'line',
                            label: 'Avg Margin %',
                            data: scenarioData.map(d => d.avgMargin),
                            borderColor: '#ef4444',
                            borderWidth: 2,
                            yAxisID: 'y1',
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: { display: true, text: 'Cost (Rs.)' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: { display: true, text: 'Margin %' },
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });

        });
    </script>
@endsection