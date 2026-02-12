@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Cost Allocation Reports</h1>
                <p class="text-gray-600 mt-1">
                    Detailed allocation breakdown and analysis
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-printer"></i>
                    Print
                </button>
                <button
                    class="inline-flex items-center px-4 py-2 bg-[#D4A017] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#B8860B] focus:bg-[#B8860B] active:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-download"></i>
                    Export PDF
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-600">Total Overhead</div>
                    <div class="text-2xl mt-1 font-medium">Rs. {{ number_format($allocationSummary['totalOverhead']) }}
                    </div>
                    <div class="text-xs text-gray-500 mt-2">Period: {{ $allocationSummary['period'] }}</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-600">Allocated</div>
                    <div class="text-2xl mt-1 text-green-600 font-medium">
                        Rs. {{ number_format($allocationSummary['allocatedOverhead']) }}
                    </div>
                    <div class="text-xs text-gray-500 mt-2">
                        {{ $allocationSummary['allocationRate'] }}% allocation rate
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-600">Unallocated</div>
                    <div class="text-2xl mt-1 text-orange-600 font-medium">
                        Rs. {{ number_format($allocationSummary['unallocatedOverhead']) }}
                    </div>
                    <div class="text-xs text-gray-500 mt-2">
                        {{ number_format(($allocationSummary['unallocatedOverhead'] / $allocationSummary['totalOverhead']) * 100, 1) }}%
                        remaining
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-sm text-gray-600">Cost Pools</div>
                    <div class="text-2xl mt-1 font-medium">{{ $allocationSummary['costPools'] }}</div>
                    <div class="text-xs text-gray-500 mt-2">
                        {{ $allocationSummary['activities'] }} activities
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
                            <input type="text" placeholder="Search products or cost pools..."
                                class="pl-10 block w-full p-2 border bg-gray-50 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                    <select
                        class="block w-[180px] pl-3 pr-10 py-2 text-base border bg-gray-50 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="current">Current Period</option>
                        <option value="previous">Previous Period</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                    </select>
                    <select
                        class="block w-[200px] pl-3 pr-10 py-2 text-base border bg-gray-50 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="all">All Cost Pools</option>
                        <option value="utilities">Utilities</option>
                        <option value="rent">Rent & Depreciation</option>
                        <option value="qc">Quality Control</option>
                        <option value="handling">Material Handling</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="space-y-4">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button onclick="switchTab('by-pool')" id="tab-by-pool"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm border-indigo-500 text-indigo-600">
                        By Cost Pool
                    </button>
                    <button onclick="switchTab('by-product')" id="tab-by-product"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        By Product
                    </button>
                    <button onclick="switchTab('by-activity')" id="tab-by-activity"
                        class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        By Activity
                    </button>
                </nav>
            </div>

            <!-- By Cost Pool Content -->
            <div id="content-by-pool" class="tab-content">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Allocation by Cost Pool</h3>
                        <p class="mt-1 text-sm text-gray-500">Detailed breakdown of how each cost pool is allocated to
                            products</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($costPoolAllocations as $pool)
                                <div class="border border-gray-300 rounded-lg">
                                    <button onclick="togglePool('{{ $pool['id'] }}')"
                                        class="w-full p-4 flex items-center justify-between hover:bg-gray-50 focus:outline-none">
                                        <div class="flex items-center gap-3">
                                            <i id="icon-{{ $pool['id'] }}" class="bi bi-chevron-right text-gray-400"></i>
                                            <div class="text-left">
                                                <div class="font-medium text-gray-900">{{ $pool['name'] }}</div>
                                                <div class="text-sm text-gray-600">Total: Rs.
                                                    {{ number_format($pool['total']) }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <div class="text-right">
                                                <div class="text-sm text-green-600">Allocated: Rs.
                                                    {{ number_format($pool['allocated']) }}</div>
                                                @if($pool['unallocated'] > 0)
                                                    <div class="text-xs text-orange-600">Unallocated: Rs.
                                                        {{ number_format($pool['unallocated']) }}</div>
                                                @endif
                                            </div>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pool['unallocated'] == 0 ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ number_format(($pool['allocated'] / $pool['total']) * 100, 1) }}%
                                            </span>
                                        </div>
                                    </button>

                                    <div id="details-{{ $pool['id'] }}" class="hidden border-t border-gray-200 p-4">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th
                                                            class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Product</th>
                                                        <th
                                                            class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Amount</th>
                                                        <th
                                                            class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            % of Pool</th>
                                                        <th
                                                            class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Allocation</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    @foreach($pool['products'] as $product)
                                                        <tr>
                                                            <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                                                {{ $product['name'] }}</td>
                                                            <td
                                                                class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-900">
                                                                Rs. {{ number_format($product['amount']) }}</td>
                                                            <td
                                                                class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-900">
                                                                {{ number_format($product['percentage'], 1) }}%</td>
                                                            <td class="px-3 py-2 whitespace-nowrap text-right">
                                                                <div class="w-20 bg-gray-200 rounded-full h-2 ml-auto">
                                                                    <div class="bg-[#D4A017] h-2 rounded-full"
                                                                        style="width: {{ $product['percentage'] }}%"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- By Product Content -->
            <div id="content-by-product" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Allocation by Product</h3>
                        <p class="mt-1 text-sm text-gray-500">Overhead allocation details for each product</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($productAllocationDetail as $item)
                                <div class="border border-gray-300 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ $item['product'] }}</h3>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600">Total Overhead</div>
                                            <div class="text-xl font-medium text-[#D4A017]">Rs.
                                                {{ number_format($item['totalOverhead']) }}</div>
                                        </div>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Cost Pool</th>
                                                    <th
                                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Activity</th>
                                                    <th
                                                        class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Driver Qty</th>
                                                    <th
                                                        class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach($item['allocations'] as $alloc)
                                                    <tr>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $alloc['costPool'] }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-600">
                                                            {{ $alloc['activity'] }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-900">
                                                            {{ $alloc['driver'] }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-900">Rs.
                                                            {{ number_format($alloc['amount']) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-gray-50">
                                                <tr>
                                                    <td colspan="3" class="px-3 py-2 font-medium text-gray-900">Total</td>
                                                    <td class="px-3 py-2 text-right font-medium text-gray-900">Rs.
                                                        {{ number_format($item['totalOverhead']) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- By Activity Content -->
            <div id="content-by-activity" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Allocation by Activity Driver</h3>
                        <p class="mt-1 text-sm text-gray-500">How activity drivers distribute costs across products</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($activityDriverReport as $activity)
                                <div class="border border-gray-300 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">{{ $activity['activity'] }}</h3>
                                            <div class="text-sm text-gray-600 mt-1">
                                                Total:
                                                {{ number_format($activity['totalHours'] ?? $activity['totalInspections']) }}
                                                units
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600">Rate per Unit</div>
                                            <div class="text-xl font-medium text-[#D4A017]">Rs.
                                                {{ number_format($activity['rate'], 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Product</th>
                                                    <th
                                                        class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Driver Units</th>
                                                    <th
                                                        class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Allocated Cost</th>
                                                    <th
                                                        class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        % of Activity</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach($activity['products'] as $product)
                                                    @php
                                                        $totalUnits = $activity['totalHours'] ?? $activity['totalInspections'];
                                                        $percentage = ($product['hours'] / $totalUnits) * 100;
                                                    @endphp
                                                    <tr>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900">
                                                            {{ $product['name'] }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-900">
                                                            {{ $product['hours'] }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-right text-gray-900">Rs.
                                                            {{ number_format($product['cost']) }}</td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-right">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                {{ number_format($percentage, 1) }}%
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variance Alert -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-l-orange-500">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center gap-2 mb-2">
                    <i class="bi bi-exclamation-circle text-orange-500"></i>
                    Allocation Variance Alert
                </h3>
                <div class="text-gray-600">
                    Total unallocated overhead: <span class="font-medium">Rs.
                        {{ number_format($allocationSummary['unallocatedOverhead']) }}</span>
                    ({{ number_format(($allocationSummary['unallocatedOverhead'] / $allocationSummary['totalOverhead']) * 100, 1) }}%)
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    This variance may be due to inactive products, incomplete driver data, or period-end adjustments.
                </p>
            </div>
        </div>
    </div>

    <script>
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

        function togglePool(poolId) {
            const details = document.getElementById('details-' + poolId);
            const icon = document.getElementById('icon-' + poolId);

            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
                icon.classList.remove('bi-chevron-right');
                icon.classList.add('bi-chevron-down');
            } else {
                details.classList.add('hidden');
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-right');
            }
        }
    </script>
@endsection