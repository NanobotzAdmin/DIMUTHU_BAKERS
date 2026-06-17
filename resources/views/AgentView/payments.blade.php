@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Payments</h1>
                <p class="text-sm text-gray-500">Track and review customer payments collected during your distribution
                    route.</p>
            </div>
            <div class="flex gap-2">
                <button onclick="openRecordPaymentDrawer()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition-colors cursor-pointer shadow-sm border-none">
                    + Record Payment
                </button>
                <a href="{{ route('agent-panel.dashboard') }}"
                    class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors no-underline">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        @if($payments->count() > 0)
            <!-- Collections Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Collections Card -->
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg">
                            <i class="bi bi-receipt"></i>
                        </span>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Total Payments</span>
                            <h4 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $totalCount }} Payments</h4>
                        </div>
                    </div>
                </div>

                <!-- Approved Amount Card -->
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg">
                            <i class="bi bi-cash-stack"></i>
                        </span>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Approved Payments</span>
                            <h4 class="text-xl font-extrabold text-slate-900 mt-0.5">Rs. {{ number_format($approvedSum, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Pending Amount Card -->
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-lg">
                            <i class="bi bi-clock-history"></i>
                        </span>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Pending Approval</span>
                            <h4 class="text-xl font-extrabold text-slate-900 mt-0.5">Rs. {{ number_format($pendingSum, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters & Search Bar -->
            <div
                class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-3 border border-slate-100 rounded-2xl shadow-sm">
                <div class="flex flex-wrap items-center gap-1.5 w-full sm:w-auto">
                    @php
                        $activeStatus = request('status', 'all');
                    @endphp
                    <a href="{{ route('agent-panel.payments', ['status' => 'all', 'search' => request('search')]) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all no-underline border border-solid {{ $activeStatus === 'all' ? 'border-indigo-600 bg-indigo-600 text-white shadow-sm' : 'border-slate-100 bg-slate-50 hover:bg-slate-100 text-slate-600' }}">
                        All Collections
                    </a>
                    <a href="{{ route('agent-panel.payments', ['status' => '0', 'search' => request('search')]) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all no-underline border border-solid {{ $activeStatus === '0' ? 'border-indigo-600 bg-indigo-600 text-white shadow-sm' : 'border-slate-100 bg-slate-50 hover:bg-slate-100 text-slate-600' }}">
                        Pending
                    </a>
                    <a href="{{ route('agent-panel.payments', ['status' => '1', 'search' => request('search')]) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all no-underline border border-solid {{ $activeStatus === '1' ? 'border-indigo-600 bg-indigo-600 text-white shadow-sm' : 'border-slate-100 bg-slate-50 hover:bg-slate-100 text-slate-600' }}">
                        Approved
                    </a>
                    <a href="{{ route('agent-panel.payments', ['status' => '2', 'search' => request('search')]) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all no-underline border border-solid {{ $activeStatus === '2' ? 'border-indigo-600 bg-indigo-600 text-white shadow-sm' : 'border-slate-100 bg-slate-50 hover:bg-slate-100 text-slate-600' }}">
                        Rejected
                    </a>
                </div>

                <form method="GET" action="{{ route('agent-panel.payments') }}" class="relative w-full sm:w-64">
                    <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search Receipt ID or notes..."
                        class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </form>
            </div>

            <!-- Payments Table -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/75">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date /
                                    Time</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Receipt
                                    ID</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Payment Method</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Instructions / Notes</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white" id="paymentsTableBody">
                            @foreach($payments as $payment)
                                @php
                                    $methodLabel = [1 => 'Cash', 2 => 'Card', 3 => 'Bank Transfer', 4 => 'Credit Note'][$payment->payment_method] ?? 'Other';
                                    $statusLabel = 'Pending';
                                    $statusClass = 'bg-amber-50 text-amber-700 border border-amber-100';
                                    if ($payment->status == 1) {
                                        $statusLabel = 'Approved';
                                        $statusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                                    } elseif ($payment->status == 2) {
                                        $statusLabel = 'Rejected';
                                        $statusClass = 'bg-rose-50 text-rose-700 border border-rose-100';
                                    }
                                    $receiptId = 'REC-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT);
                                @endphp
                                <tr class="payment-row hover:bg-slate-50/50 transition-colors cursor-pointer"
                                    onclick="openViewPaymentModal({{ $payment->id }})" data-receipt="{{ strtolower($receiptId) }}"
                                    data-notes="{{ strtolower($payment->notes) }}" data-status="{{ $payment->status }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        <span class="flex items-center gap-1.5">
                                            <i class="bi bi-calendar-event text-slate-400"></i>
                                            {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d h:i A') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 font-mono">
                                        {{ $receiptId }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-100 border-solid">
                                            {{ $methodLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate" title="{{ $payment->notes }}">
                                        {{ $payment->notes ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="px-2.5 py-0.5 text-xs font-semibold rounded-full border border-solid {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-right text-slate-900">
                                        Rs. {{ number_format($payment->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($payments->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        @else
            <div
                class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden p-6 text-center text-gray-400 py-20 flex flex-col items-center justify-center">
                <div
                    class="w-16 h-16 bg-slate-50 text-slate-300 border border-slate-100 rounded-full flex items-center justify-center text-3xl mb-4 shadow-sm">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <p class="font-extrabold text-gray-700 text-base">No Collections Recorded</p>
                <p class="text-xs mt-1 text-gray-400 max-w-sm">You have not submitted or recorded any payments yet. Tap "+
                    Record Payment" above to record customer collections.</p>
            </div>
        @endif
    </div>

    <!-- VIEW PAYMENT DETAIL MODAL -->
    <div id="viewPaymentModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm"
                onclick="closeViewPaymentModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full w-full">
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Payment Details</h3>
                        <p class="text-xs text-slate-500 mt-0.5" id="detailReceiptId">REC-00000</p>
                    </div>
                    <button type="button" onclick="closeViewPaymentModal()"
                        class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 rounded-lg border-none bg-transparent cursor-pointer">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                    <!-- Summary Card -->
                    <div
                        class="bg-slate-50 p-4 rounded-xl border border-slate-100 border-solid flex justify-around items-center text-xs">
                        <div class="text-center flex-1">
                            <span class="text-slate-400 font-bold block mb-1">Total Paid</span>
                            <span id="detailTotalPaid" class="font-extrabold text-base text-indigo-600">Rs. 0.00</span>
                        </div>
                        <div class="w-[1px] h-8 bg-slate-200"></div>
                        <div class="text-center flex-1">
                            <span class="text-slate-400 font-bold block mb-1">Payment Method</span>
                            <span id="detailMethodText" class="font-bold text-sm text-slate-700">-</span>
                        </div>
                    </div>

                    <!-- Order Allocations -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Order Allocations</h4>
                        <div class="border border-slate-100 border-solid rounded-xl overflow-hidden shadow-sm">
                            <table class="min-w-full divide-y divide-slate-100 text-xs">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-2.5 text-left font-bold text-slate-500 uppercase text-[9px]">
                                            Order Request</th>
                                        <th
                                            class="px-4 py-2.5 text-right font-bold text-slate-500 uppercase text-[9px] w-40">
                                            Order Total</th>
                                        <th
                                            class="px-4 py-2.5 text-right font-bold text-slate-500 uppercase text-[9px] w-40">
                                            Allocated Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="detailAllocationsTable" class="divide-y divide-slate-100 bg-white">
                                    <!-- Injected -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div id="detailPaymentNotesContainer">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Agent Notes</h4>
                        <p id="detailPaymentNotes"
                            class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 border-solid italic">
                            No notes provided.</p>
                    </div>

                    <!-- Audit Trail / History -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Audit Trail / History
                        </h4>
                        <div class="relative border-l-2 border-solid border-slate-100 pl-4 space-y-4 ml-1"
                            id="detailPaymentHistoryTimeline">
                            <!-- Injected -->
                        </div>
                    </div>
                </div>

                <!-- Footer Details -->
                <div class="bg-slate-50 px-6 py-4 border-t border-gray-100 flex justify-end">
                    <button type="button" onclick="closeViewPaymentModal()"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm border-none cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT-SIDE SLIDE-OVER DRAWER FOR RECORD PAYMENT -->
    <div id="paymentDrawerBackdrop"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300"
        onclick="closeRecordPaymentDrawer()"></div>

    <div id="recordPaymentDrawer"
        class="fixed inset-y-0 right-0 z-50 w-full max-w-2xl bg-white shadow-2xl border-l border-solid border-slate-100 flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">
        <!-- Header -->
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Record New Payment</h3>
                <p class="text-xs text-slate-500 mt-0.5">Collect and allocate customer payments against outstanding order
                    requests.</p>
            </div>
            <button type="button" onclick="closeRecordPaymentDrawer()"
                class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 rounded-lg border-none bg-transparent cursor-pointer">
                <i class="bi bi-x-lg text-lg leading-none"></i>
            </button>
        </div>

        <!-- Drawer Content (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6">
            <!-- Distribution Type -->
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Distribution
                    Type</label>
                <div class="flex bg-slate-100 p-1 rounded-xl">
                    <button type="button" onclick="setAutoDistribute(true)" id="btnAutoDist"
                        class="flex-1 py-2 text-center rounded-lg text-xs font-bold transition-all bg-white text-slate-800 shadow-sm border-0 cursor-pointer">
                        Auto Distribute
                    </button>
                    <button type="button" onclick="setAutoDistribute(false)" id="btnManualDist"
                        class="flex-1 py-2 text-center rounded-lg text-xs font-bold transition-all text-slate-600 hover:text-slate-800 border-0 bg-transparent cursor-pointer">
                        Manual Select
                    </button>
                </div>
            </div>

            <!-- Manual Order Checkboxes -->
            <div id="manualOrdersListContainer" class="hidden">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Select Orders to
                    Pay</label>
                <div id="manualOrdersCheckboxList"
                    class="border border-solid border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100 divide-solid max-h-48 overflow-y-auto bg-white">
                    <!-- Injected -->
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Payment
                    Method</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <button type="button" onclick="selectPaymentMethod('Cash')"
                        class="method-btn py-2.5 text-center rounded-xl text-xs font-bold border border-solid border-indigo-600 bg-indigo-600 text-white shadow-sm cursor-pointer"
                        data-method="Cash">Cash</button>
                    <button type="button" onclick="selectPaymentMethod('Bank Transfer')"
                        class="method-btn py-2.5 text-center rounded-xl text-xs font-bold border border-solid border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 cursor-pointer"
                        data-method="Bank Transfer">Bank Transfer</button>
                    <button type="button" onclick="selectPaymentMethod('Cheque')"
                        class="method-btn py-2.5 text-center rounded-xl text-xs font-bold border border-solid border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 cursor-pointer"
                        data-method="Cheque">Cheque</button>
                    <button type="button" onclick="selectPaymentMethod('Credit Note')"
                        class="method-btn py-2.5 text-center rounded-xl text-xs font-bold border border-solid border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 cursor-pointer"
                        data-method="Credit Note">Credit Note</button>
                </div>
            </div>

            <!-- Credit Notes Section -->
            <div id="creditNoteListContainer"
                class="hidden space-y-3 bg-slate-50 p-4 rounded-xl border border-solid border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Select Approved Credit Notes</h4>
                <div id="creditNotesCheckboxList" class="space-y-2 max-h-40 overflow-y-auto">
                    <!-- Injected -->
                </div>
            </div>

            <!-- Amount & Notes -->
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="paymentAmount"
                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Amount (Rs.)
                        *</label>
                    <input type="number" step="0.01" min="0.01" id="paymentAmount"
                        class="w-full px-3 py-2 border border-solid border-gray-200 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                        placeholder="0.00">
                    <span id="amountHintText" class="text-[9px] text-indigo-600 mt-1 block hidden"></span>
                </div>
                <div>
                    <label for="paymentNotes"
                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Reference /
                        Notes</label>
                    <textarea id="paymentNotes" rows="3"
                        class="w-full border border-solid border-gray-200 rounded-xl px-3 py-2 text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none"
                        placeholder="Provide payment details or references..."></textarea>
                </div>
            </div>
        </div>

        <!-- Drawer Footer -->
        <div class="bg-slate-50 px-6 py-4 border-t border-gray-100 border-solid flex justify-between items-center shrink-0">
            <button type="button" onclick="closeRecordPaymentDrawer()"
                class="px-4 py-2 border border-solid border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold rounded-xl transition-all cursor-pointer bg-white">
                Cancel
            </button>
            <button type="button" onclick="submitRecordedPayment()" id="btnSubmitPayment"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 shadow-sm cursor-pointer border-none">
                <i class="bi bi-check2-circle"></i> Confirm & Record
            </button>
        </div>
    </div>

    <script>
        // Injected Server Data
        const paymentsList = @json($payments->items());
        const currentAgentId = {{ $agent->id ?? 'null' }};

        let activeStatus = 'all';

        // Form State
        let isBulkPayment = false;
        let isAutoDistribute = true;
        let selectedMethod = 'Cash';
        let ordersList = [];
        let creditNotesList = [];
        let selectedOrdersForBulk = [];
        let selectedCreditNotes = [];

        // Helper: format datetime
        window.formatDateTimeGMT = function (dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            return d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        };

        // --- 1. DETAILS MODAL ---
        function openViewPaymentModal(paymentId) {
            const payment = paymentsList.find(p => p.id === paymentId);
            if (!payment) return;

            const receiptId = 'REC-' + String(payment.id).padStart(5, '0');
            $('#detailReceiptId').text(receiptId);
            $('#detailTotalPaid').text('Rs. ' + (parseFloat(payment.amount) || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }));

            const methodText = { 1: 'Cash', 2: 'Card', 3: 'Bank Transfer', 4: 'Credit Note' }[payment.payment_method] ?? 'Other';
            $('#detailMethodText').text(methodText);
            $('#detailPaymentNotes').text(payment.notes || 'No notes provided.');

            // Render Distributions Table
            const tbody = $('#detailAllocationsTable');
            tbody.empty();
            if (payment.distributions && payment.distributions.length > 0) {
                payment.distributions.forEach(dist => {
                    const total = parseFloat(dist.order_request?.grand_total) || 0;
                    const paid = parseFloat(dist.payment_amount) || 0;
                    tbody.append(`
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3 font-semibold text-slate-800">
                                        #${dist.order_request?.order_number || 'N/A'}
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-600">Rs. ${total.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    <td class="px-4 py-3 text-right font-bold text-emerald-600">Rs. ${paid.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                </tr>
                            `);
                });
            } else {
                tbody.html('<tr><td colspan="3" class="px-4 py-4 text-center text-slate-400 italic">No allocations recorded.</td></tr>');
            }

            // Render Timeline
            const timeline = $('#detailPaymentHistoryTimeline');
            timeline.empty();
            if (payment.history && payment.history.length > 0) {
                payment.history.forEach(log => {
                    const creator = log.creator ? (log.creator.first_name + ' ' + (log.creator.last_name || '')) : 'System';
                    let stColor = 'bg-amber-50 text-amber-600 border-amber-200';
                    if (log.status == 1) stColor = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                    else if (log.status == 2) stColor = 'bg-rose-50 text-rose-600 border-rose-200';

                    timeline.append(`
                                <div class="relative pl-5 text-xs">
                                    <span class="absolute left-[-21px] top-1 w-2.5 h-2.5 rounded-full bg-slate-400 border-2 border-white shadow-sm"></span>
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <span class="font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded text-[10px]">${log.action}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">${window.formatDateTimeGMT(log.created_at)}</span>
                                    </div>
                                    <p class="text-slate-500 text-[10px]">${log.description || ''} (By: <strong>${creator}</strong>)</p>
                                </div>
                            `);
                });
            } else {
                timeline.html('<p class="text-xs text-slate-400 italic pl-1">No timeline logs found.</p>');
            }

            $('#viewPaymentModal').removeClass('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeViewPaymentModal() {
            $('#viewPaymentModal').addClass('hidden');
            document.body.style.overflow = '';
        }

        // --- 2. RECORD PAYMENT DRAWER ---
        function openRecordPaymentDrawer() {
            // Reset states
            isAutoDistribute = true;
            selectedMethod = 'Cash';
            selectedOrdersForBulk = [];
            selectedCreditNotes = [];
            ordersList = [];
            creditNotesList = [];

            $('#paymentAmount').val('').prop('readonly', false).removeClass('bg-slate-100');
            $('#paymentNotes').val('');
            $('#amountHintText').addClass('hidden');

            // Reset UI buttons
            setAutoDistribute(true);
            selectPaymentMethod('Cash');

            // Fetch Orders having remaining balance
            $.getJSON('/agent-panel/api/orders')
                .done(response => {
                    if (response.status && response.data) {
                        // Filter orders where due balance > 0
                        ordersList = response.data.filter(order => {
                            const paid = order.payments ? order.payments.filter(p => p.status !== 3).reduce((sum, p) => sum + parseFloat(p.payment_amount), 0) : 0;
                            order.due_amount = parseFloat(order.grand_total) - paid;
                            return order.due_amount > 0.01;
                        });
                        populateManualOrdersCheckboxList();
                    }
                })
                .fail(() => toastr.error('Failed to retrieve orders list.'));

            // Open Drawer
            $('#paymentDrawerBackdrop').removeClass('hidden');
            setTimeout(() => $('#paymentDrawerBackdrop').addClass('opacity-100'), 10);
            $('#recordPaymentDrawer').removeClass('translate-x-full');
            document.body.style.overflow = 'hidden';
        }

        function closeRecordPaymentDrawer() {
            $('#paymentDrawerBackdrop').removeClass('opacity-100');
            setTimeout(() => $('#paymentDrawerBackdrop').addClass('hidden'), 300);
            $('#recordPaymentDrawer').addClass('translate-x-full');
            document.body.style.overflow = '';
        }

        function setAutoDistribute(isAuto) {
            isAutoDistribute = isAuto;
            if (isAuto) {
                $('#btnAutoDist').addClass('bg-white text-slate-800 shadow-sm').removeClass('text-slate-600 hover:text-slate-800 bg-transparent');
                $('#btnManualDist').removeClass('bg-white text-slate-800 shadow-sm').addClass('text-slate-600 hover:text-slate-800 bg-transparent');
                $('#manualOrdersListContainer').addClass('hidden');
            } else {
                $('#btnAutoDist').removeClass('bg-white text-slate-800 shadow-sm').addClass('text-slate-600 hover:text-slate-800 bg-transparent');
                $('#btnManualDist').addClass('bg-white text-slate-800 shadow-sm').removeClass('text-slate-600 hover:text-slate-800 bg-transparent');
                $('#manualOrdersListContainer').removeClass('hidden');
            }
            toggleAmountInputState();
        }

        function selectPaymentMethod(method) {
            selectedMethod = method;

            // Update styling
            document.querySelectorAll('.method-btn').forEach(btn => {
                if (btn.getAttribute('data-method') === method) {
                    btn.className = 'method-btn py-2.5 text-center rounded-xl text-xs font-bold border border-solid border-indigo-600 bg-indigo-600 text-white shadow-sm cursor-pointer';
                } else {
                    btn.className = 'method-btn py-2.5 text-center rounded-xl text-xs font-bold border border-solid border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 cursor-pointer';
                }
            });

            if (method === 'Credit Note') {
                $('#creditNoteListContainer').removeClass('hidden');
                fetchCreditNotes();
            } else {
                $('#creditNoteListContainer').addClass('hidden');
            }
            toggleAmountInputState();
        }

        // Recalculates amount input fields
        function toggleAmountInputState() {
            if (selectedMethod === 'Credit Note') {
                $('#paymentAmount').prop('readonly', true).addClass('bg-slate-100');
                $('#amountHintText').text('Amount is automatically calculated from selected credit notes.').removeClass('hidden');
                recalculateCreditNoteTotal();
            } else if (!isAutoDistribute) {
                $('#paymentAmount').prop('readonly', true).addClass('bg-slate-100');
                $('#amountHintText').text('Amount is automatically calculated from the selected orders above.').removeClass('hidden');
                recalculateManualOrdersTotal();
            } else {
                $('#paymentAmount').prop('readonly', false).removeClass('bg-slate-100');
                $('#amountHintText').addClass('hidden');
            }
        }

        function populateManualOrdersCheckboxList() {
            const container = $('#manualOrdersCheckboxList');
            container.empty();
            if (ordersList.length === 0) {
                container.html('<div class="p-4 text-center text-slate-400 italic">No outstanding orders found.</div>');
                return;
            }
            ordersList.forEach(order => {
                container.append(`
                            <label class="flex items-center justify-between p-3 cursor-pointer hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="bulk_orders" value="${order.id}" onchange="onManualOrderToggle()" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                    <div>
                                        <span class="text-xs font-bold text-slate-900">${order.order_number}</span>
                                        <span class="text-[10px] text-slate-400 block">Total: Rs. ${parseFloat(order.grand_total).toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-slate-600">Due: Rs. ${order.due_amount.toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                            </label>
                        `);
            });
        }

        function onManualOrderToggle() {
            selectedOrdersForBulk = [];
            document.querySelectorAll('input[name="bulk_orders"]:checked').forEach(cb => {
                selectedOrdersForBulk.push(parseInt(cb.value));
            });
            if (!isAutoDistribute) {
                recalculateManualOrdersTotal();
            }
        }

        function recalculateManualOrdersTotal() {
            let total = 0;
            selectedOrdersForBulk.forEach(id => {
                const order = ordersList.find(o => o.id === id);
                if (order) total += order.due_amount;
            });
            $('#paymentAmount').val(total.toFixed(2));
        }

        function fetchCreditNotes() {
            const container = $('#creditNotesCheckboxList');
            container.html('<div class="py-4 text-center text-slate-400 italic"><i class="bi bi-arrow-clockwise animate-spin mr-1"></i>Loading credit notes...</div>');

            $.getJSON('/agent-panel/api/bakery-returns?status=1')
                .done(response => {
                    if (response.status && response.data) {
                        creditNotesList = response.data;
                        renderCreditNotes();
                    }
                })
                .fail(() => container.html('<div class="p-3 text-center text-rose-500 text-xs">Failed to load credit notes.</div>'));
        }

        function renderCreditNotes() {
            const container = $('#creditNotesCheckboxList');
            container.empty();
            if (creditNotesList.length === 0) {
                container.html('<div class="p-4 text-center text-slate-400 italic">No approved credit notes available.</div>');
                return;
            }
            creditNotesList.forEach(cn => {
                container.append(`
                            <label class="flex items-center justify-between p-3 bg-white rounded-xl border border-solid border-slate-100 cursor-pointer hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="credit_notes" value="${cn.id}" onchange="onCreditNoteToggle()" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                    <div>
                                        <span class="text-xs font-bold text-slate-900">${cn.credit_note_number}</span>
                                        <span class="text-[10px] text-slate-400 block">Date: ${new Date(cn.credit_note_date).toLocaleDateString()}</span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-indigo-600">Rs. ${parseFloat(cn.total_amount).toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                            </label>
                        `);
            });
        }

        function onCreditNoteToggle() {
            selectedCreditNotes = [];
            document.querySelectorAll('input[name="credit_notes"]:checked').forEach(cb => {
                selectedCreditNotes.push(parseInt(cb.value));
            });
            if (selectedMethod === 'Credit Note') {
                recalculateCreditNoteTotal();
            }
        }

        function recalculateCreditNoteTotal() {
            let total = 0;
            selectedCreditNotes.forEach(id => {
                const cn = creditNotesList.find(c => c.id === id);
                if (cn) total += parseFloat(cn.total_amount);
            });
            $('#paymentAmount').val(total.toFixed(2));
        }

        // --- 3. SUBMIT PAYMENT ---
        function submitRecordedPayment() {
            const amount = parseFloat($('#paymentAmount').val());
            if (!amount || isNaN(amount) || amount <= 0) {
                Swal.fire('Error', 'Please enter or select a valid payment amount.', 'error');
                return;
            }

            const notes = $('#paymentNotes').val();

            // Bulk payment validation
            if (!isAutoDistribute && selectedOrdersForBulk.length === 0) {
                Swal.fire('Error', 'Please select at least one order to distribute the payment to.', 'error');
                return;
            }

            const totalDue = ordersList.reduce((sum, o) => sum + o.due_amount, 0);
            if (amount > totalDue + 0.01) {
                Swal.fire('Invalid Amount', `Amount exceeds total outstanding balance (Rs. ${totalDue.toLocaleString()})`, 'error');
                return;
            }

            // Build manual distributions
            let distributions = [];
            if (!isAutoDistribute) {
                let remainingPayment = amount;
                for (const id of selectedOrdersForBulk) {
                    if (remainingPayment <= 0.005) break;
                    const order = ordersList.find(o => o.id === id);
                    if (!order) continue;
                    const allocated = Math.min(order.due_amount, remainingPayment);
                    const roundedAllocated = Math.round(allocated * 100) / 100;
                    if (roundedAllocated > 0) {
                        distributions.push({
                            order_id: id,
                            amount: roundedAllocated
                        });
                        remainingPayment = Math.round((remainingPayment - roundedAllocated) * 100) / 100;
                    }
                }
            }

            // Fire API
            $('#btnSubmitPayment').prop('disabled', true).html('<i class="bi bi-arrow-clockwise animate-spin text-sm"></i> Recording...');
            $.ajax({
                url: '/agent-panel/api/orders/bulk-payment',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    agent_id: currentAgentId,
                    amount: amount,
                    method: selectedMethod,
                    notes: notes,
                    is_auto: isAutoDistribute ? 1 : 0,
                    distributions: distributions,
                    credit_note_ids: selectedMethod === 'Credit Note' ? selectedCreditNotes : []
                },
                success: function (response) {
                    if (response.status) {
                        Swal.fire('Success', 'Bulk payment recorded successfully and pending approval.', 'success')
                            .then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', response.message || 'Failed to record bulk payment.', 'error');
                        $('#btnSubmitPayment').prop('disabled', false).html('<i class="bi bi-check2-circle"></i> Confirm & Record');
                    }
                },
                error: function (xhr) {
                    const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error submitting bulk payment request.';
                    Swal.fire('Failed', msg, 'error');
                    $('#btnSubmitPayment').prop('disabled', false).html('<i class="bi bi-check2-circle"></i> Confirm & Record');
                }
            });
        }

        // --- 4. LIST SEARCH & FILTERS ---
        function filterStatus(status) {
            activeStatus = status;
            document.querySelectorAll('.status-tab').forEach(tab => {
                if (tab.getAttribute('data-status') === status) {
                    tab.className = 'status-tab px-4 py-2 rounded-xl text-xs font-bold transition-all border border-indigo-600 bg-indigo-600 text-white shadow-sm cursor-pointer border-solid';
                } else {
                    tab.className = 'status-tab px-4 py-2 rounded-xl text-xs font-bold transition-all border border-solid border-slate-100 bg-slate-50 hover:bg-slate-100 text-slate-600 cursor-pointer';
                }
            });
            filterPayments();
        }

        function filterPayments() {
            const query = document.getElementById('paymentsSearch').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.payment-row');

            rows.forEach(row => {
                const receipt = row.getAttribute('data-receipt');
                const notes = row.getAttribute('data-notes');
                const status = row.getAttribute('data-status');

                const matchesQuery = receipt.includes(query) || notes.includes(query);
                const matchesStatus = activeStatus === 'all' || status === activeStatus;

                if (matchesQuery && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Close Modals on Escape
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeViewPaymentModal();
                closeRecordPaymentDrawer();
            }
        });
    </script>
@endsection