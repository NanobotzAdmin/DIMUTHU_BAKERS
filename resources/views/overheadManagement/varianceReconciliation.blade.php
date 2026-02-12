@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="bi bi-bar-chart-steps text-[#D4A017]"></i>
                    Overhead Variance Reconciliation
                </h1>
                <p class="text-gray-600 mt-1">Analyze and reconcile over/under-applied overhead variances</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100">
                <div class="flex flex-col">
                    <span class="text-sm text-gray-600 font-medium">Periods Analyzed</span>
                    <span class="text-3xl font-bold text-gray-900 mt-1">{{ count($variances) }}</span>
                    <span class="text-xs text-gray-500 mt-1">Monthly variance tracking</span>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100">
                <div class="flex flex-col">
                    <span class="text-sm text-gray-600 font-medium">Unreconciled Variance</span>
                    <span class="text-3xl font-bold text-yellow-600 mt-1">
                        Rs.
                        {{ number_format(collect($variances)->where('hasReconciliationEntry', false)->where('variance', '!=', 0)->sum(function ($v) {
        return abs($v['variance']); })) }}
                    </span>
                    <span class="text-xs text-gray-500 mt-1">Needs reconciliation</span>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100">
                <div class="flex flex-col">
                    <span class="text-sm text-gray-600 font-medium">Reconciled Periods</span>
                    <span class="text-3xl font-bold text-green-600 mt-1">
                        {{ collect($variances)->where('hasReconciliationEntry', true)->count() }}
                    </span>
                    <span class="text-xs text-gray-500 mt-1">Completed reconciliations</span>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100">
                <div class="flex flex-col">
                    <span class="text-sm text-gray-600 font-medium">Current Period</span>
                    <span class="text-2xl font-bold text-gray-900 mt-1">{{ $currentPeriodVariance['period'] }}</span>
                    <div class="mt-1">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium border
                            @if($currentPeriodVariance['varianceType'] == 'under-applied') bg-red-100 text-red-800 border-red-200
                            @elseif($currentPeriodVariance['varianceType'] == 'over-applied') bg-blue-100 text-blue-800 border-blue-200
                            @else bg-green-100 text-green-800 border-green-200 @endif capitalize">
                            {{ $currentPeriodVariance['varianceType'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Period Detail -->
        @if($currentPeriodVariance)
            <div class="bg-white rounded-xl shadow-sm border-2 border-[#D4A017] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Current Period Analysis: {{ $currentPeriodVariance['period'] }}
                    </h3>
                    <p class="text-sm text-gray-500">Detailed variance breakdown</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Actual -->
                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                            <div class="text-sm text-gray-600 mb-1">Actual Overhead Expenses</div>
                            <div class="text-2xl font-bold text-blue-900">Rs.
                                {{ number_format($currentPeriodVariance['actualOverhead']) }}</div>
                            <div class="text-xs text-gray-600 mt-1">{{ $currentPeriodVariance['expenseCount'] }} expenses
                                recorded</div>
                        </div>
                        <!-- Applied -->
                        <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                            <div class="text-sm text-gray-600 mb-1">Applied Overhead (Allocated)</div>
                            <div class="text-2xl font-bold text-green-900">Rs.
                                {{ number_format($currentPeriodVariance['appliedOverhead']) }}</div>
                            <div class="text-xs text-gray-600 mt-1">{{ $currentPeriodVariance['allocationCount'] }} allocations
                                posted</div>
                        </div>
                        <!-- Variance -->
                        <div class="rounded-lg p-4 border
                            @if($currentPeriodVariance['varianceType'] == 'under-applied') bg-red-50 border-red-100
                            @elseif($currentPeriodVariance['varianceType'] == 'over-applied') bg-purple-50 border-purple-100
                            @else bg-gray-50 border-gray-200 @endif">
                            <div class="text-sm text-gray-600 mb-1 flex items-center gap-2">
                                Variance
                                @if($currentPeriodVariance['varianceType'] == 'under-applied') <i
                                    class="bi bi-graph-up-arrow text-red-600"></i>
                                @elseif($currentPeriodVariance['varianceType'] == 'over-applied') <i
                                    class="bi bi-graph-down-arrow text-purple-600"></i>
                                @else <i class="bi bi-check-circle text-green-600"></i> @endif
                            </div>
                            <div class="text-2xl font-bold
                                 @if($currentPeriodVariance['varianceType'] == 'under-applied') text-red-900
                                 @elseif($currentPeriodVariance['varianceType'] == 'over-applied') text-purple-900
                                 @else text-gray-900 @endif">
                                Rs. {{ number_format(abs($currentPeriodVariance['variance'])) }}
                            </div>
                            <div class="text-xs text-gray-600 mt-1">
                                {{ number_format($currentPeriodVariance['variancePercent'], 1) }}% of actual</div>
                        </div>
                    </div>

                    <!-- Explanation -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                        <h4 class="font-medium text-gray-900 mb-2">Variance Explanation</h4>
                        @if($currentPeriodVariance['varianceType'] == 'under-applied')
                            <div class="text-sm text-gray-700 space-y-2">
                                <p><strong>Under-Applied Overhead:</strong> Actual overhead expenses exceeded the amount allocated
                                    (Rs. {{ number_format($currentPeriodVariance['appliedOverhead']) }}).</p>
                                <p>This means we didn't charge enough overhead to products. The variance needs to be reconciled by
                                    increasing COGS or adjusting WIP/FG/COGS.</p>
                            </div>
                        @elseif($currentPeriodVariance['varianceType'] == 'over-applied')
                            <div class="text-sm text-gray-700 space-y-2">
                                <p><strong>Over-Applied Overhead:</strong> Allocated overhead exceeded actual expenses (Rs.
                                    {{ number_format($currentPeriodVariance['actualOverhead']) }}).</p>
                                <p>This means we charged too much overhead to products. The variance needs to be reconciled by
                                    decreasing COGS or adjusting WIP/FG/COGS.</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-700">Overhead is balanced - actual expenses match allocated amounts within
                                tolerance.</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    @if(!$currentPeriodVariance['hasReconciliationEntry'] && abs($currentPeriodVariance['variance']) >= 100)
                        <div>
                            <button onclick="openReconcileModal('{{ $currentPeriodVariance['period'] }}')"
                                class="inline-flex items-center px-4 py-2 bg-[#D4A017] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#B8860B] focus:outline-none transition ease-in-out duration-150 gap-2">
                                <i class="bi bi-gear"></i> Reconcile Variance
                            </button>
                        </div>
                    @elseif($currentPeriodVariance['hasReconciliationEntry'])
                        <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg p-3">
                            <i class="bi bi-check-circle text-green-600"></i>
                            <span class="text-sm text-green-900 font-medium">Variance has been reconciled</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- History Table -->
        <div class="bg-white rounded-xl shadow-sm border-2 border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Variance History</h3>
                <p class="text-sm text-gray-500">All periods with variance analysis</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Period</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actual OH</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Applied OH</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Variance</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Variance %</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($variances as $variance)
                            <tr class="hover:bg-gray-50 cursor-pointer">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $variance['period'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">Rs.
                                    {{ number_format($variance['actualOverhead']) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">Rs.
                                    {{ number_format($variance['appliedOverhead']) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium
                                     @if($variance['varianceType'] == 'under-applied') text-red-700
                                     @elseif($variance['varianceType'] == 'over-applied') text-purple-700
                                     @else text-gray-700 @endif">
                                    {{ $variance['variance'] >= 0 ? '+' : '' }}Rs. {{ number_format($variance['variance']) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                    {{ number_format($variance['variancePercent'], 1) }}%</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($variance['varianceType'] == 'under-applied') bg-red-100 text-red-800
                                        @elseif($variance['varianceType'] == 'over-applied') bg-purple-100 text-purple-800
                                        @else bg-green-100 text-green-800 @endif capitalize">
                                        {{ $variance['varianceType'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($variance['hasReconciliationEntry'])
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="bi bi-check-circle mr-1"></i> Reconciled
                                        </span>
                                    @elseif(abs($variance['variance']) < 100)
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border border-gray-300 text-gray-500">N/A</span>
                                    @else
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="bi bi-exclamation-triangle mr-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    @if($variance['hasReconciliationEntry'])
                                        <button class="text-indigo-600 hover:text-indigo-900">View JE</button>
                                    @elseif(abs($variance['variance']) >= 100)
                                        <button onclick="openReconcileModal('{{ $variance['period'] }}')"
                                            class="text-[#D4A017] hover:text-[#B8860B] font-bold">Reconcile</button>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Help Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h4 class="text-lg font-bold text-blue-900 mb-2">Understanding Overhead Variances</h4>
            <div class="text-sm text-blue-900 space-y-2">
                <p><strong>Actual Overhead:</strong> Total of all overhead expenses recorded in the period.</p>
                <p><strong>Applied Overhead:</strong> Total overhead allocated to production departments in the period.</p>
                <div class="border-t border-blue-200 my-2 pt-2">
                    <p><strong>Under-Applied (Actual > Applied):</strong> Costs not fully allocated. Products under-costed.
                    </p>
                    <p><strong>Over-Applied (Actual < Applied):</strong> Costs over-allocated. Products over-costed.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reconcile Modal -->
    <div id="reconcileModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500/75 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeReconcileModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Reconcile Overhead
                        Variance</h3>

                    <div id="reconcileContent">
                        <!-- Dynamic Content -->
                    </div>

                    <div class="mt-4">
                        <h4 class="font-medium text-gray-900 mb-2">Reconciliation Method</h4>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" id="method-cogs" name="reconcileMethod" value="cogs" checked
                                    onchange="updatePreview('cogs')"
                                    class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <label for="method-cogs" class="flex-1 cursor-pointer">
                                    <div class="font-medium text-gray-900">Write Off to COGS (Simple Method)</div>
                                    <div class="text-sm text-gray-500 mt-1">Entire variance charged/credited directly to
                                        Cost of Goods Sold. Best for immaterial variances.</div>
                                </label>
                            </div>
                            <div class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" id="method-prorate" name="reconcileMethod" value="prorate"
                                    onchange="updatePreview('prorate')"
                                    class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <label for="method-prorate" class="flex-1 cursor-pointer">
                                    <div class="font-medium text-gray-900">Prorate to WIP/FG/COGS (Accurate Method)</div>
                                    <div class="text-sm text-gray-500 mt-1">Variance allocated proportionally based on
                                        account balances. Best for material variances.</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center gap-2">
                        <input type="checkbox" id="autoPost"
                            class="h-4 w-4 text-[#D4A017] focus:ring-[#D4A017] border-gray-300 rounded">
                        <label for="autoPost" class="text-sm font-medium text-gray-700">Auto-post journal entry (otherwise
                            saved as draft)</label>
                    </div>

                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-900">
                        <p class="font-bold">Journal Entry Preview:</p>
                        <p id="jePreview" class="mt-2 font-mono text-xs whitespace-pre-line">
                            <!-- Preview Text -->
                        </p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="confirmReconciliation()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#D4A017] text-base font-medium text-white hover:bg-[#B8860B] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Generate Reconciliation Entry
                    </button>
                    <button type="button" onclick="closeReconcileModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const variances = @json($variances);
        let currentVariance = null;

        function openReconcileModal(period) {
            currentVariance = variances.find(v => v.period === period);
            if (!currentVariance) return;

            const absVariance = Math.abs(currentVariance.variance).toLocaleString();

            // Populate Header
            document.getElementById('reconcileContent').innerHTML = `
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <h4 class="font-medium mb-1">Period: ${currentVariance.period}</h4>
                    <div class="grid grid-cols-3 gap-3 text-sm text-gray-600">
                        <div>Actual: <span class="font-medium text-gray-900">Rs. ${currentVariance.actualOverhead.toLocaleString()}</span></div>
                        <div>Applied: <span class="font-medium text-gray-900">Rs. ${currentVariance.appliedOverhead.toLocaleString()}</span></div>
                        <div>Variance: <span class="font-medium text-red-700">Rs. ${absVariance}</span></div>
                    </div>
                </div>
            `;

            // Reset Inputs
            document.getElementById('method-cogs').checked = true;
            document.getElementById('autoPost').checked = false;

            // Initial Preview
            updatePreview('cogs');

            document.getElementById('reconcileModal').classList.remove('hidden');
        }

        function closeReconcileModal() {
            document.getElementById('reconcileModal').classList.add('hidden');
            currentVariance = null;
        }

        function updatePreview(method) {
            if (!currentVariance) return;

            const absVar = Math.abs(currentVariance.variance);
            const absVarStr = absVar.toLocaleString();
            let preview = '';

            if (method === 'cogs') {
                if (currentVariance.varianceType === 'under-applied') {
                    preview = `DR: COGS - Manufacturing Overhead   Rs. ${absVarStr}\nCR: Manufacturing Overhead Control    Rs. ${absVarStr}`;
                } else {
                    preview = `DR: Manufacturing Overhead Control    Rs. ${absVarStr}\nCR: COGS - Manufacturing Overhead   Rs. ${absVarStr}`;
                }
            } else {
                // Mock Proration: 30% WIP, 20% FG, 50% COGS
                const wip = (absVar * 0.3).toLocaleString();
                const fg = (absVar * 0.2).toLocaleString();
                const cogs = (absVar * 0.5).toLocaleString();

                if (currentVariance.varianceType === 'under-applied') {
                    preview = `DR: WIP - Applied Overhead          Rs. ${wip}\nDR: Finished Goods                  Rs. ${fg}\nDR: COGS - Mfg Overhead             Rs. ${cogs}\nCR: Manufacturing Overhead Control  Rs. ${absVarStr}`;
                } else {
                    preview = `DR: Manufacturing Overhead Control  Rs. ${absVarStr}\nCR: WIP - Applied Overhead          Rs. ${wip}\nCR: Finished Goods                  Rs. ${fg}\nCR: COGS - Mfg Overhead             Rs. ${cogs}`;
                }
            }
            document.getElementById('jePreview').textContent = preview;
        }

        function confirmReconciliation() {
            if (!currentVariance) return;

            const isAutoPost = document.getElementById('autoPost').checked;
            const msg = isAutoPost ? 'Variance reconciliation journal entry posted successfully!' : 'Variance reconciliation journal entry created as draft.';

            // Simulate Success
            closeReconcileModal();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: msg,
                timer: 2000,
                showConfirmButton: false
            });
        }
    </script>
@endsection