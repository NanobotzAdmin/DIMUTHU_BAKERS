@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Budget Planning</h1>
                <p class="text-gray-600 mt-1">
                    Plan and manage overhead budgets by cost pool and period
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-files"></i>
                    Copy from Previous
                </button>
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-upload"></i>
                    Import Budget
                </button>
                <button
                    class="inline-flex items-center px-4 py-2 bg-[#D4A017] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#B8860B] focus:bg-[#B8860B] active:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-plus-lg"></i>
                    New Budget Period
                </button>
            </div>
        </div>

        <!-- Period Selector & Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Period Selector -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-1">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Budget Period</h3>
                    <div class="space-y-4">
                        <select id="periodSelector"
                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white border">
                            @foreach($budgetPeriods as $period)
                                <option value="{{ $period['id'] }}" {{ $loop->first ? 'selected' : '' }}
                                    data-start="{{ $period['startDate'] }}" data-end="{{ $period['endDate'] }}"
                                    data-total="{{ $period['totalBudget'] }}" data-actual="{{ $period['actualSpend'] }}"
                                    data-status="{{ $period['status'] }}">
                                    {{ $period['name'] }}
                                </option>
                            @endforeach
                        </select>

                        <div id="periodDetails" class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Start Date</span>
                                <span class="font-medium" id="currentStartDate">{{ $budgetPeriods[0]['startDate'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">End Date</span>
                                <span class="font-medium" id="currentEndDate">{{ $budgetPeriods[0]['endDate'] }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-gray-600">Status</span>
                                <span id="currentStatusBadge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($budgetPeriods[0]['status'] == 'active') bg-green-100 text-green-800 
                                    @elseif($budgetPeriods[0]['status'] == 'draft') bg-gray-100 text-gray-800 
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($budgetPeriods[0]['status']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Budget -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="bi bi-bullseye text-blue-600 text-lg"></i>
                        </div>
                        <div class="text-sm text-gray-600">Total Budget</div>
                    </div>
                    <div class="text-2xl mt-1 font-bold text-gray-900" id="currentTotalBudget">
                        Rs. {{ number_format($budgetPeriods[0]['totalBudget']) }}
                    </div>
                </div>
            </div>

            <!-- Actual Spend -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-[#D4A017]/10 rounded-lg">
                            <i class="bi bi-currency-dollar text-[#D4A017] text-lg"></i>
                        </div>
                        <div class="text-sm text-gray-600">Actual Spend</div>
                    </div>
                    <div class="text-2xl mt-1 font-bold text-[#D4A017]" id="currentActualSpend">
                        Rs. {{ number_format($budgetPeriods[0]['actualSpend']) }}
                    </div>
                </div>
            </div>

            <!-- Utilization -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="bi bi-graph-up text-green-600 text-lg"></i>
                        </div>
                        <div class="text-sm text-gray-600">Utilization</div>
                    </div>
                    @php
                        $utilization = ($budgetPeriods[0]['actualSpend'] / $budgetPeriods[0]['totalBudget']) * 100;
                    @endphp
                    <div class="text-2xl mt-1 font-bold text-green-600" id="currentUtilizationText">
                        {{ number_format($utilization, 1) }}%
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                        <div id="currentUtilizationBar" class="bg-green-600 h-2 rounded-full"
                            style="width: {{ $utilization }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cost Pool Budgets -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Cost Pool Budgets</h3>
                    <p class="mt-1 text-sm text-gray-500">Budget allocation by cost pool</p>
                </div>
                <div class="flex items-center gap-2">
                    <button
                        class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md font-medium text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 gap-2">
                        <i class="bi bi-download"></i>
                        Export
                    </button>
                    <button onclick="openModal('newBudgetModal')"
                        class="inline-flex items-center px-3 py-1.5 bg-[#D4A017] border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-[#B8860B] focus:outline-none transition ease-in-out duration-150 gap-2">
                        <i class="bi bi-plus-lg"></i>
                        Add Budget Line
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost
                                Pool</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Budget Amount</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actual Amount</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Variance</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">%
                                Variance</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($costPoolBudgets as $budget)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $budget['costPoolName'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $budget['category'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-blue-600">Rs.
                                    {{ number_format($budget['budgetAmount']) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-[#D4A017]">Rs.
                                    {{ number_format($budget['actualAmount']) }}</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $budget['variance'] < 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rs. {{ number_format(abs($budget['variance'])) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">
                                    <span
                                        class="inline-flex items-center gap-1 {{ abs($budget['variancePercent']) > 15 ? 'text-red-600' : (abs($budget['variancePercent']) > 10 ? 'text-orange-600' : 'text-green-600') }}">
                                        {{ abs($budget['variancePercent']) }}%
                                        @if($budget['variancePercent'] < 0) <i class="bi bi-graph-up-arrow text-xs"></i> @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if(abs($budget['variancePercent']) > 15) bg-red-100 text-red-800 
                                        @elseif(abs($budget['variancePercent']) > 10) bg-gray-100 text-gray-800 
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ abs($budget['variancePercent']) > 15 ? 'Critical' : (abs($budget['variancePercent']) > 10 ? 'Warning' : 'On Track') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button onclick="openEditModal('{{ json_encode($budget) }}')"
                                        class="text-gray-600 hover:text-indigo-900 mr-3"><i class="bi bi-pencil"></i></button>
                                    <button class="text-red-600 hover:text-red-900"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" colspan="2">Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-blue-600">Rs.
                                {{ number_format(collect($costPoolBudgets)->sum('budgetAmount')) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-[#D4A017]">Rs.
                                {{ number_format(collect($costPoolBudgets)->sum('actualAmount')) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">Rs.
                                {{ number_format(abs(collect($costPoolBudgets)->sum('variance'))) }}</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Budget Guidelines -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-l-blue-600">
            <div class="p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center gap-2 mb-4">
                    <i class="bi bi-info-circle text-blue-600"></i>
                    Budget Planning Guidelines
                </h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start gap-2">
                        <span class="text-[#D4A017] mt-1">•</span>
                        <span>Review historical data and trends before setting budgets</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-[#D4A017] mt-1">•</span>
                        <span>Consider seasonal variations and business growth projections</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-[#D4A017] mt-1">•</span>
                        <span>Fixed costs (rent, depreciation) are typically easier to budget accurately</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-[#D4A017] mt-1">•</span>
                        <span>Variable costs should be tied to production volume forecasts</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-[#D4A017] mt-1">•</span>
                        <span>Set variance thresholds: &lt;10% on track, 10-15% warning, &gt;15% critical</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- New Budget Modal -->
    <div id="newBudgetModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500/75 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeModal('newBudgetModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Add Budget Line</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 mb-4">Create a new budget allocation for a cost pool</p>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Cost Pool</label>
                                <select
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white border">
                                    <option value="utilities">Utilities</option>
                                    <option value="rent">Rent & Depreciation</option>
                                    <option value="qc">Quality Control</option>
                                    <option value="handling">Material Handling</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Annual Budget</label>
                                    <input type="number"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="0">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Category</label>
                                    <select
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white border">
                                        <option value="Fixed">Fixed</option>
                                        <option value="Variable">Variable</option>
                                        <option value="Semi-Variable">Semi-Variable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Allocation Method</label>
                                <select
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white border">
                                    <option value="equal">Equal Monthly</option>
                                    <option value="seasonal">Seasonal Pattern</option>
                                    <option value="custom">Custom Breakdown</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#D4A017] text-base font-medium text-white hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="closeModal('newBudgetModal')">
                        Create Budget
                    </button>
                    <button type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="closeModal('newBudgetModal')">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Budget Modal -->
    <div id="editBudgetModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500/75 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeModal('editBudgetModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="edit-modal-title">Edit Budget</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 mb-4">Adjust budget amounts and parameters</p>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Budget Amount (Annual)</label>
                                    <input type="number" id="editBudgetAmount"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="0">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Category</label>
                                    <select id="editBudgetCategory"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md bg-white border">
                                        <option value="Fixed">Fixed</option>
                                        <option value="Variable">Variable</option>
                                        <option value="Semi-Variable">Semi-Variable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    rows="3" placeholder="Add notes about this budget allocation..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#D4A017] text-base font-medium text-white hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="closeModal('editBudgetModal')">
                        Save Changes
                    </button>
                    <button type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="closeModal('editBudgetModal')">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function openEditModal(budgetDataRaw) {
            const budgetData = JSON.parse(budgetDataRaw);
            document.getElementById('edit-modal-title').innerText = 'Edit Budget - ' + budgetData.costPoolName;
            document.getElementById('editBudgetAmount').value = budgetData.budgetAmount;
            document.getElementById('editBudgetCategory').value = budgetData.category;
            openModal('editBudgetModal');
        }

        // Period Selector Logic
        document.getElementById('periodSelector').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];

            // Update Details
            document.getElementById('currentStartDate').innerText = selectedOption.getAttribute('data-start');
            document.getElementById('currentEndDate').innerText = selectedOption.getAttribute('data-end');

            // Update Status Badge
            const status = selectedOption.getAttribute('data-status');
            const badge = document.getElementById('currentStatusBadge');
            badge.innerText = status.charAt(0).toUpperCase() + status.slice(1);

            // Reset/Set classes
            badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
            if (status === 'active') badge.classList.add('bg-green-100', 'text-green-800');
            else if (status === 'draft') badge.classList.add('bg-gray-100', 'text-gray-800');
            else badge.classList.add('bg-gray-100', 'text-gray-800');

            // Update Summary Cards
            const total = parseFloat(selectedOption.getAttribute('data-total'));
            const actual = parseFloat(selectedOption.getAttribute('data-actual'));
            const utilization = total > 0 ? (actual / total) * 100 : 0;

            document.getElementById('currentTotalBudget').innerText = 'Rs. ' + new Intl.NumberFormat().format(total);
            document.getElementById('currentActualSpend').innerText = 'Rs. ' + new Intl.NumberFormat().format(actual);
            document.getElementById('currentUtilizationText').innerText = utilization.toFixed(1) + '%';
            document.getElementById('currentUtilizationBar').style.width = utilization + '%';
        });
    </script>
@endsection