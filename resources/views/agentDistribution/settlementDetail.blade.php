@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="settlement-detail-app">
        <!-- Header -->
        <div class="mb-6 flex items-center gap-4">
            <a href="/settlement-list"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 flex items-center">
                <i class="bi bi-arrow-left mr-2"></i> Back
            </a>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $settlement['settlementNumber'] }}</h1>
                    <span id="status-badge"></span>
                    @if(abs($settlement['cashVariance']) > 0)
                        <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl" title="Has Variance"></i>
                    @endif
                </div>
                <p class="text-gray-600">Settlement for {{ $agent['agentName'] }} ({{ $agent['agentCode'] }}) -
                    {{ date('d/m/Y', strtotime($settlement['settlementDate'])) }}</p>
            </div>
            <div>
                <button onclick="window.print()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                    <i class="bi bi-printer mr-2"></i> Print
                </button>
            </div>
        </div>

        <!-- Actions Panel -->
        <div id="action-panel"
            class="hidden p-4 bg-blue-50 border border-blue-200 rounded-xl mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <i class="bi bi-file-earmark-check text-blue-600 text-2xl"></i>
                <div>
                    <h3 class="text-blue-900 font-bold">Action Required</h3>
                    <p class="text-blue-700 text-sm" id="action-text">This settlement needs review.</p>
                </div>
            </div>
            <div class="flex items-center gap-2" id="action-buttons">
                <!-- Injected via JS -->
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="p-4 bg-purple-50 border border-purple-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-currency-dollar text-purple-600 text-xl"></i>
                    <span class="text-purple-700 text-xs font-bold uppercase">Total Sales</span>
                </div>
                <p class="text-2xl font-bold text-purple-900 mb-2">Rs. {{ number_format($settlement['totalSales'], 2) }}</p>
                <div class="text-xs text-purple-700 space-y-1">
                    <div class="flex justify-between"><span>Cash:</span>
                        <span>{{ number_format($settlement['cashSales'], 2) }}</span></div>
                    <div class="flex justify-between"><span>Credit:</span>
                        <span>{{ number_format($settlement['creditSales'], 2) }}</span></div>
                </div>
            </div>

            <div
                class="p-4 {{ abs($settlement['cashVariance']) > 0 ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200' }} rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i
                        class="bi bi-cash-coin {{ abs($settlement['cashVariance']) > 0 ? 'text-red-600' : 'text-green-600' }} text-xl"></i>
                    <span
                        class="{{ abs($settlement['cashVariance']) > 0 ? 'text-red-700' : 'text-green-700' }} text-xs font-bold uppercase">Cash
                        Reconciliation</span>
                </div>
                <p
                    class="text-2xl font-bold {{ abs($settlement['cashVariance']) > 0 ? 'text-red-900' : 'text-green-900' }} mb-2">
                    {{ $settlement['cashVariance'] > 0 ? '+' : '' }}{{ number_format($settlement['cashVariance'], 2) }}
                </p>
                <div
                    class="text-xs {{ abs($settlement['cashVariance']) > 0 ? 'text-red-700' : 'text-green-700' }} space-y-1">
                    <div class="flex justify-between"><span>Expected:</span>
                        <span>{{ number_format($settlement['expectedCash'], 2) }}</span></div>
                    <div class="flex justify-between"><span>Actual:</span>
                        <span>{{ number_format($settlement['actualCash'], 2) }}</span></div>
                </div>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-graph-up text-blue-600 text-xl"></i>
                    <span class="text-blue-700 text-xs font-bold uppercase">Collections</span>
                </div>
                <p class="text-2xl font-bold text-blue-900 mb-2">Rs. {{ number_format($settlement['totalCollections'], 2) }}
                </p>
                <p class="text-xs text-blue-700">{{ count($collections) }} transactions</p>
            </div>

            <div class="p-4 bg-orange-50 border border-orange-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-arrow-return-left text-orange-600 text-xl"></i>
                    <span class="text-orange-700 text-xs font-bold uppercase">Returns</span>
                </div>
                <p class="text-2xl font-bold text-orange-900 mb-2">Rs. {{ number_format($settlement['returnedValue'], 2) }}
                </p>
                <p class="text-xs text-orange-700">{{ count($returns) }} items returned</p>
            </div>
        </div>

        <!-- Tabs Layout -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-200 bg-gray-50 flex overflow-x-auto">
                <button onclick="switchTab('overview')" id="tab-overview"
                    class="tab-btn px-6 py-3 text-sm font-medium text-amber-600 border-b-2 border-amber-500 bg-white focus:outline-none">Overview</button>
                <button onclick="switchTab('sales')" id="tab-sales"
                    class="tab-btn px-6 py-3 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none">Sales
                    ({{ count($sales) }})</button>
                <button onclick="switchTab('collections')" id="tab-collections"
                    class="tab-btn px-6 py-3 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none">Collections
                    ({{ count($collections) }})</button>
                <button onclick="switchTab('returns')" id="tab-returns"
                    class="tab-btn px-6 py-3 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none">Returns
                    ({{ count($returns) }})</button>
                <button onclick="switchTab('timeline')" id="tab-timeline"
                    class="tab-btn px-6 py-3 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none">Timeline</button>
            </div>

            <div class="p-6 min-h-[400px]">
                <!-- Overview Tab -->
                <div id="content-overview" class="tab-content space-y-6">
                    <!-- Accountability & Load -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-3">Agent Accountability</h3>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Loaded Value</span>
                                    <span class="font-medium text-gray-900">Rs.
                                        {{ number_format($settlement['loadedValue'], 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Less: Returns</span>
                                    <span class="font-medium text-red-600">(Rs.
                                        {{ number_format($settlement['returnedValue'], 2) }})</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Less: Collections</span>
                                    <span class="font-medium text-green-600">(Rs.
                                        {{ number_format($settlement['totalCollections'], 2) }})</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Less: Cash Sales</span>
                                    <span class="font-medium text-blue-600">(Rs.
                                        {{ number_format($settlement['cashSales'], 2) }})</span>
                                </div>
                                <div class="pt-2 border-t border-gray-300 flex justify-between font-bold">
                                    <span class="text-purple-900">Amount Due to Bakery</span>
                                    <span class="text-purple-900">Rs.
                                        {{ number_format($settlement['amountDueToBakery'], 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-3">Load Information</h3>
                            <div class="bg-white p-4 rounded-xl border border-gray-200 grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500">Load Number</p>
                                    <p class="font-medium">{{ $load['loadNumber'] }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Items Loaded</p>
                                    <p class="font-medium">{{ $load['totalQuantity'] }} units</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Total Value</p>
                                    <p class="font-medium">Rs. {{ number_format($load['totalValue'], 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Load Date</p>
                                    <p class="font-medium">{{ $load['loadDate'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($settlement['notes'])
                        <div>
                            <h3 class="font-bold text-gray-900 mb-2">Notes</h3>
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-gray-800">
                                {{ $settlement['notes'] }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sales Tab -->
                <div id="content-sales" class="tab-content hidden space-y-4">
                    @foreach($sales as $sale)
                        <div
                            class="p-4 border border-gray-200 rounded-lg flex justify-between items-center bg-white hover:bg-gray-50">
                            <div>
                                <p class="font-bold text-gray-900">{{ $sale['customerName'] }}</p>
                                <p class="text-xs text-gray-600">{{ $sale['invoiceNumber'] }} •
                                    {{ date('H:i', strtotime($sale['saleDate'])) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">Rs. {{ number_format($sale['totalAmount'], 2) }}</p>
                                <span
                                    class="text-xs px-2 py-0.5 rounded-full {{ $sale['paymentMethod'] == 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }} capitalize">
                                    {{ $sale['paymentMethod'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Collections Tab -->
                <div id="content-collections" class="tab-content hidden space-y-4">
                    @foreach($collections as $col)
                        <div
                            class="p-4 border border-gray-200 rounded-lg flex justify-between items-center bg-white hover:bg-gray-50">
                            <div>
                                <p class="font-bold text-gray-900">{{ $col['customerName'] }}</p>
                                <p class="text-xs text-gray-600">{{ $col['receiptNumber'] }} •
                                    {{ date('H:i', strtotime($col['collectionDate'])) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">Rs. {{ number_format($col['amount'], 2) }}</p>
                                <span
                                    class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded capitalize">{{ $col['paymentMethod'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Returns Tab -->
                <div id="content-returns" class="tab-content hidden space-y-4">
                    @foreach($returns as $ret)
                        <div
                            class="p-4 border border-gray-200 rounded-lg flex justify-between items-start bg-white hover:bg-gray-50">
                            <div>
                                <p class="font-bold text-gray-900">{{ $ret['returnNumber'] }}</p>
                                <p class="text-xs text-gray-600">{{ $ret['notes'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-orange-600">Rs. {{ number_format($ret['totalValue'], 2) }}</p>
                                <p class="text-xs text-gray-500">{{ $ret['totalQuantity'] }} units</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Timeline Tab -->
                <div id="content-timeline" class="tab-content hidden">
                    <div
                        class="relative pl-8 space-y-8 before:absolute before:left-3 before:top-2 before:bottom-0 before:w-0.5 before:bg-gray-200">
                        <div class="relative">
                            <div class="absolute -left-8 bg-blue-100 p-1.5 rounded-full ring-4 ring-white">
                                <i class="bi bi-file-earmark-check text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Submitted</p>
                                <p class="text-xs text-gray-500">
                                    {{ date('d M Y, H:i', strtotime($settlement['submittedAt'])) }}</p>
                            </div>
                        </div>
                        <!-- Mock logic for rest -->
                        @if($settlement['status'] != 'pending')
                            <div class="relative">
                                <div class="absolute -left-8 bg-purple-100 p-1.5 rounded-full ring-4 ring-white">
                                    <i class="bi bi-eye text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Reviewed</p>
                                    <p class="text-xs text-gray-500">
                                        {{ date('d M Y, H:i', strtotime($settlement['submittedAt'] . ' + 1 hour')) }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        const settlement = @json($settlement);

        document.addEventListener('DOMContentLoaded', () => {
            renderStatus();
            renderActions();
        });

        function switchTab(tab) {
            // Headers
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-amber-600', 'border-b-2', 'border-amber-500', 'bg-white');
                btn.classList.add('text-gray-600');
            });
            document.getElementById(`tab-${tab}`).classList.add('text-amber-600', 'border-b-2', 'border-amber-500', 'bg-white');
            document.getElementById(`tab-${tab}`).classList.remove('text-gray-600');

            // Content
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            document.getElementById(`content-${tab}`).classList.remove('hidden');
        }

        function renderStatus() {
            const s = settlement.status;
            let badge = '';
            const styles = {
                pending: 'bg-yellow-100 text-yellow-800',
                reviewed: 'bg-blue-100 text-blue-800',
                approved: 'bg-green-100 text-green-800',
                disputed: 'bg-red-100 text-red-800'
            };
            const icon = {
                pending: 'bi-clock',
                reviewed: 'bi-eye',
                approved: 'bi-check-circle-fill',
                disputed: 'bi-x-circle-fill'
            };

            const style = styles[s] || styles.pending;
            const ic = icon[s] || icon.pending;

            document.getElementById('status-badge').innerHTML =
                `<span class="px-3 py-1 rounded-full text-sm font-medium capitalize flex items-center gap-2 ${style}">
                    <i class="bi ${ic}"></i> ${s}
                 </span>`;
        }

        function renderActions() {
            const s = settlement.status;
            const panel = document.getElementById('action-panel');
            const container = document.getElementById('action-buttons');

            if (s === 'approved') {
                panel.classList.add('hidden');
                return;
            }

            panel.classList.remove('hidden');
            let btns = '';

            if (s === 'pending') {
                btns += `<button onclick="doAction('review')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Mark as Reviewed</button>`;
            }

            if (s === 'pending' || s === 'reviewed') {
                btns += `<button onclick="doAction('approve')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium">Approve</button>`;
                btns += `<button onclick="doAction('dispute')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Dispute</button>`;
            }

            if (s === 'disputed') {
                btns += `<button onclick="doAction('resolve')" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 font-medium">Resolve Dispute</button>`;
            }

            container.innerHTML = btns;
        }

        function doAction(action) {
            if (action === 'dispute') {
                Swal.fire({
                    title: 'Dispute Settlement',
                    input: 'textarea',
                    inputPlaceholder: 'Reason...',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    confirmButtonColor: '#d33'
                }).then(r => {
                    if (r.isConfirmed && r.value) {
                        Swal.fire('Disputed', 'Settlement marked as disputed', 'success');
                        // Update UI mock
                        settlement.status = 'disputed';
                        renderStatus();
                        renderActions();
                    }
                });
            } else if (action === 'approve') {
                Swal.fire({
                    title: 'Approve?',
                    text: 'This will lock the settlement.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Approve',
                    confirmButtonColor: '#10b981'
                }).then(r => {
                    if (r.isConfirmed) {
                        Swal.fire('Approved', 'Settlement approved.', 'success');
                        settlement.status = 'approved';
                        renderStatus();
                        renderActions();
                    }
                });
            } else if (action === 'review') {
                settlement.status = 'reviewed';
                renderStatus();
                renderActions();
                Swal.fire('Reviewed', 'Marked as reviewed', 'success');
            } else if (action === 'resolve') {
                settlement.status = 'reviewed';
                renderStatus();
                renderActions();
                Swal.fire('Resolved', 'Dispute resolved, moved to reviewed status.', 'success');
            }
        }
    </script>
@endsection