@extends('layouts.app')

@section('title', 'Agent Payment Management')

@section('content')
<div class="max-w-full mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl sm:truncate">
                Agent Payment Management
            </h2>
            <p class="text-sm text-gray-500">
                Monitor and manage all payments received from agents.
            </p>
        </div>
        <div class="flex items-center gap-3">
             <button type="button" onclick="window.location.reload()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="bi bi-arrow-clockwise mr-2"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white shadow-md sm:rounded-2xl border border-gray-100 mb-8 overflow-hidden">
        <div class="bg-gray-50/50 border-b border-gray-100 px-6 py-4">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center">
                <i class="bi bi-funnel-fill mr-2 text-indigo-500"></i> Search & Filter
            </h3>
        </div>
        <div class="px-6 py-6">
            <form action="{{ route('agent-payments.index') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Agent Filter -->
                <div class="lg:col-span-1">
                    <label for="agent_id" class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2">Agent</label>
                    <div class="relative">
                        <select id="agent_id" name="agent_id" class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all bg-gray-50/30">
                            <option value="">All Agents</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->agent_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="lg:col-span-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2">Duration (From - To)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="relative">
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="block w-full px-3 py-2 text-sm border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all bg-gray-50/30">
                        </div>
                        <div class="relative">
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="block w-full px-3 py-2 text-sm border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all bg-gray-50/30">
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2">Current Status</label>
                    <select id="status" name="status" class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all bg-gray-50/30">
                        <option value="">All Statuses</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Approved</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Payment Type -->
                <div>
                    <label for="payment_method" class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-2">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl transition-all bg-gray-50/30">
                        <option value="">All Methods</option>
                        <option value="1" {{ request('payment_method') === '1' ? 'selected' : '' }}>Cash</option>
                        <option value="2" {{ request('payment_method') === '2' ? 'selected' : '' }}>Card</option>
                        <option value="3" {{ request('payment_method') === '3' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="4" {{ request('payment_method') === '4' ? 'selected' : '' }}>Credit Note</option>
                    </select>
                </div>

                <div class="lg:col-span-5 flex justify-end gap-3 pt-4 mt-4 border-t border-gray-50">
                    <a href="{{ route('agent-payments.index') }}" class="inline-flex items-center px-6 py-2.5 border border-gray-200 text-xs font-bold rounded-xl text-gray-500 bg-white hover:bg-gray-50 transition-all hover:text-gray-700">
                        Reset Filters
                    </a>
                    <button type="submit" class="inline-flex items-center px-8 py-2.5 border border-transparent text-xs font-black rounded-xl shadow-lg shadow-indigo-200 text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-95 uppercase tracking-widest">
                        <i class="bi bi-search mr-2"></i> Find Transactions
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payment->payment_date ? $payment->payment_date->format('M d, Y h:i A') : $payment->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ $payment->agent->agent_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                            Rs. {{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php
                                $methodText = match((int)$payment->payment_method) {
                                    1 => 'Cash',
                                    2 => 'Card',
                                    3 => 'Bank Transfer',
                                    4 => 'Credit Note',
                                    default => 'Other'
                                };
                                $methodIcon = match((int)$payment->payment_method) {
                                    1 => 'bi-cash-stack text-green-600',
                                    2 => 'bi-credit-card text-blue-600',
                                    3 => 'bi-bank text-purple-600',
                                    4 => 'bi-ticket-perforated text-orange-600',
                                    default => 'bi-wallet2 text-gray-600'
                                };
                            @endphp
                            <div class="flex items-center gap-2">
                                <i class="bi {{ $methodIcon }}"></i>
                                {{ $methodText }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match((int)$payment->status) {
                                    0 => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    1 => 'bg-green-100 text-green-800 border-green-200',
                                    2 => 'bg-red-100 text-red-800 border-red-200',
                                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                                };
                                $statusText = match((int)$payment->status) {
                                    0 => 'Pending',
                                    1 => 'Approved',
                                    2 => 'Rejected',
                                    default => 'Unknown'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="viewPaymentDetails({{ $payment->id }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition-colors">
                                View Orders
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            No payment records found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Details Modal -->
<div id="paymentDetailsModal" class="hidden fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 bg-gray-900/75 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed bg-gray-500/90 " aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200">
            <div class="bg-white">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900" id="modal-title">
                            Payment Approval
                        </h3>
                        <div class="flex items-center gap-4 mt-1">
                            <span class="flex items-center text-xs text-gray-500" id="payment-subtitle-agent">
                                <i class="bi bi-person-circle mr-1"></i> Loading...
                            </span>
                            <span class="flex items-center text-xs text-gray-500" id="payment-subtitle-date">
                                <i class="bi bi-calendar3 mr-1"></i> Loading...
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="header-status-badge" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            <i class="bi bi-clock-history mr-1.5"></i> Processing
                        </span>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="px-6 py-6" id="modal-content">
                    <!-- Loading State -->
                    <div id="modal-loading" class="flex flex-col items-center justify-center py-20">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                        <p class="mt-4 text-gray-500 font-medium tracking-tight">Gathering transaction details...</p>
                    </div>
                    
                    <!-- Dynamic Content -->
                    <div id="modal-data" class="hidden">
                        <!-- Summary Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="bi bi-cash-stack text-4xl text-indigo-600"></i>
                                </div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">Total Payment</span>
                                <span class="text-2xl font-black text-gray-900" id="detail-total">Rs. 0.00</span>
                            </div>
                            
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i id="detail-method-icon" class="bi bi-credit-card text-4xl text-blue-600"></i>
                                </div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">Payment Method</span>
                                <span class="text-xl font-bold text-gray-900" id="detail-method-text">Loading...</span>
                            </div>

                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="bi bi-layers text-4xl text-purple-600"></i>
                                </div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-1">Allocated Orders</span>
                                <span class="text-2xl font-black text-gray-900" id="detail-count">0</span>
                            </div>
                        </div>

                        <!-- Table Section -->
                        <div class="mb-4 flex items-center justify-between">
                            <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest flex items-center">
                                <span class="w-8 h-[2px] bg-indigo-600 mr-3"></span>
                                Distribution Breakdown
                            </h4>
                        </div>
                        
                        <div class="overflow-hidden border border-gray-200 rounded-xl shadow-sm bg-gray-50/30">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Order Reference</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Original Total</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-indigo-50/50">Payment Amount</th>
                                        <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="orders-list" class="bg-white divide-y divide-gray-100">
                                    <!-- Rows and Accordions will be injected here -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Credit Notes Section -->
                        <div id="credit-notes-section" class="mt-8 hidden">
                            <div class="mb-4">
                                <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest flex items-center">
                                    <span class="w-8 h-[2px] bg-orange-500 mr-3"></span>
                                    Applied Credit Notes
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="credit-notes-list">
                                <!-- Credit Note Cards will be injected here -->
                            </div>
                        </div>

                        <!-- Notes Section (Optional if you have notes in DB) -->
                        <div id="payment-notes-container" class="mt-8 hidden">
                            <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-widest mb-2">Agent Notes</label>
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-sm italic text-gray-600" id="payment-notes">
                                No notes provided.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100">
                <div id="bulk-approve-container" class="flex gap-3">
                    <button id="btn-approve-all" type="button" class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="bi bi-check2-all mr-2"></i> Approve All Related Orders
                    </button>
                    <button id="btn-reject-payment" type="button" class="inline-flex items-center px-6 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 transition-all shadow-md hover:shadow-lg">
                        <i class="bi bi-x-circle mr-2"></i> Reject Payment
                    </button>
                </div>
                <button type="button" onclick="closeModal()" class="px-6 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    function viewPaymentDetails(paymentId) {
        const modal = document.getElementById('paymentDetailsModal');
        const loading = document.getElementById('modal-loading');
        const dataSection = document.getElementById('modal-data');
        
        modal.classList.remove('hidden');
        loading.classList.remove('hidden');
        dataSection.classList.add('hidden');
        
        $.ajax({
            url: `/api/agent-payments/orders/${paymentId}`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const payment = response.data;
                    
                    // Header Update
                    document.getElementById('modal-title').innerHTML = `Payment Approval <span class="text-sm font-normal text-gray-500">via ${payment.payment_method == 1 ? 'Cash' : (payment.payment_method == 2 ? 'Card' : (payment.payment_method == 4 ? 'Credit Note' : 'Bank Transfer'))}</span>`;
                    
                    // Stats Update
                    document.getElementById('detail-total').textContent = `Rs. ${parseFloat(payment.amount).toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                    document.getElementById('detail-count').textContent = payment.distributions.length;
                    
                    // Header Details
                    document.getElementById('payment-subtitle-agent').innerHTML = `<i class="bi bi-person-circle mr-1"></i> Agent: <span class="font-bold text-gray-800 ml-1">${payment.agent ? payment.agent.agent_name : 'Unknown Agent'}</span>`;
                    document.getElementById('payment-subtitle-date').innerHTML = `<i class="bi bi-calendar3 mr-1"></i> Submitted: <span class="font-bold text-gray-800 ml-1">${moment(payment.created_at).format('MMM DD, YYYY hh:mm A')}</span>`;
                    
                    // Method Mapping
                    const methods = {
                        1: { text: 'Cash Payment', icon: 'bi-cash-stack text-green-600' },
                        2: { text: 'Card Payment', icon: 'bi-credit-card text-blue-600' },
                        3: { text: 'Bank Transfer', icon: 'bi-bank text-purple-600' },
                        4: { text: 'Credit Note', icon: 'bi-ticket-perforated text-orange-600' }
                    };
                    const method = methods[payment.payment_method] || { text: 'Other', icon: 'bi-wallet2 text-gray-600' };
                    
                    const methodTextEl = document.getElementById('detail-method-text');
                    const methodIconEl = document.getElementById('detail-method-icon');
                    methodTextEl.textContent = method.text;
                    methodIconEl.className = `bi ${method.icon.split(' ')[0]} text-4xl ${method.icon.split(' ')[1]}`;

                    // Status Badge Mapping
                    const statuses = {
                        0: { text: 'Pending Approval', class: 'bg-yellow-100 text-yellow-800 border border-yellow-200' },
                        1: { text: 'Approved & Settled', class: 'bg-green-100 text-green-800 border border-green-200' },
                        2: { text: 'Payment Rejected', class: 'bg-red-100 text-red-800 border border-red-200' }
                    };
                    const status = statuses[payment.status] || { text: 'Unknown', class: 'bg-gray-100 text-gray-800' };
                    const badge = document.getElementById('header-status-badge');
                    badge.className = `inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider ${status.class}`;
                    badge.innerHTML = `<i class="bi ${payment.status == 0 ? 'bi-clock-history' : (payment.status == 1 ? 'bi-check2-circle' : 'bi-x-circle')} mr-1.5"></i> ${status.text}`;

                    // Bulk Approve Button Control
                    const bulkContainer = document.getElementById('bulk-approve-container');
                    if (payment.status == 0) {
                        bulkContainer.classList.remove('hidden');
                        document.getElementById('btn-approve-all').onclick = () => approveBulkPayments(payment.id);
                        document.getElementById('btn-reject-payment').onclick = () => rejectPayment(payment.id);
                    } else {
                        bulkContainer.classList.add('hidden');
                    }

                    // Credit Notes Handling
                    const cnSection = document.getElementById('credit-notes-section');
                    const cnList = document.getElementById('credit-notes-list');
                    cnList.innerHTML = '';
                    
                    if (payment.credit_notes && payment.credit_notes.length > 0) {
                        cnSection.classList.remove('hidden');
                        payment.credit_notes.forEach(cn => {
                            cnList.innerHTML += `
                                <div class="bg-orange-50/50 border border-orange-100 rounded-xl p-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600">
                                            <i class="bi bi-ticket-perforated text-xl"></i>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-black text-orange-700">#${cn.credit_note_number}</span>
                                            <span class="block text-[10px] text-orange-500 font-bold">Credit Note</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-sm font-black text-orange-800">Rs. ${parseFloat(cn.total_amount).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        cnSection.classList.add('hidden');
                    }

                    // Notes
                    const notesContainer = document.getElementById('payment-notes-container');
                    if (payment.notes) {
                        notesContainer.classList.remove('hidden');
                        document.getElementById('payment-notes').textContent = payment.notes;
                    } else {
                        notesContainer.classList.add('hidden');
                    }

                    // Orders Table
                    const ordersList = document.getElementById('orders-list');
                    ordersList.innerHTML = '';
                    
                    payment.distributions.forEach((dist, index) => {
                        const order = dist.order_request;
                        const paidAmount = parseFloat(order.paid_amount) || 0;
                        const grandTotal = parseFloat(order.grand_total) || 0;
                        const currentPayment = parseFloat(dist.payment_amount) || 0;
                        
                        // For already approved payments, order.paid_amount ALREADY includes currentPayment
                        // For pending payments, order.paid_amount does NOT include currentPayment
                        let alreadyPaidBeforeThis = paidAmount;
                        if (payment.status == 1 && dist.status == 2) { // Main is Approved AND Dist is Approved
                            alreadyPaidBeforeThis = Math.max(0, paidAmount - currentPayment);
                        }

                        const isRejected = (payment.status == 2 || dist.status == 3);
                        const isProcessed = dist.status != 1; // Not Pending

                        // Percentage Calculations
                        const percentage = (grandTotal > 0) ? (alreadyPaidBeforeThis / grandTotal) * 100 : 0;
                        const newPercentage = isRejected ? percentage : ((grandTotal > 0) ? ((alreadyPaidBeforeThis + currentPayment) / grandTotal) * 100 : 0);

                        const rowId = `accordion-${index}`;

                        const row = `
                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="toggleAccordion('${rowId}')">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-indigo-700">#${order.order_number}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Order Type: ${order.order_type === 4 ? 'Agent Distribution' : 'Retail Sale'}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900">Rs. ${grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                                        <span class="text-[10px] text-gray-400 font-medium">Full Invoice Value</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap bg-indigo-50/30">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-indigo-800">Rs. ${currentPayment.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                                        <span class="text-[10px] text-indigo-400 font-bold">Allocated Portion</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold hover:bg-indigo-100 transition-all border border-indigo-100">
                                        ${isProcessed ? 'View Details' : 'Verify Details'} <i class="bi bi-chevron-down ml-1.5 transition-transform" id="${rowId}-icon"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr id="${rowId}" class="hidden bg-gray-50/50">
                                <td colspan="4" class="px-6 py-8">
                                    <div class="max-w-xl">
                                        <h5 class="text-sm font-black text-indigo-700 mb-1">Order #${order.order_number}</h5>
                                        <p class="text-xs text-gray-600 mb-1">Order Total: Rs. ${grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</p>
                                        <p class="text-xs text-gray-600 mb-1">Already Paid: Rs. ${alreadyPaidBeforeThis.toLocaleString(undefined, {minimumFractionDigits: 2})}</p>
                                        <p class="text-xs font-bold text-red-600 mb-4">Remaining Balance: Rs. ${Math.max(0, grandTotal - alreadyPaidBeforeThis - (isRejected ? 0 : currentPayment)).toLocaleString(undefined, {minimumFractionDigits: 2})}</p>
                                        
                                        <div class="relative w-full bg-gray-200 rounded-full h-2 shadow-inner">
                                            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: ${Math.min(100, percentage)}%"></div>
                                            <div class="bg-indigo-400 h-2 rounded-full absolute top-0 left-0 transition-all duration-1000 opacity-40 shadow-[0_0_8px_rgba(79,70,229,0.5)]" style="width: ${Math.min(100, newPercentage)}%"></div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-[10px] ${isRejected ? 'text-red-600' : (isProcessed ? 'text-gray-500' : 'text-indigo-600')} font-black italic tracking-wide">${isRejected ? '* This payment was rejected' : (isProcessed ? '* This payment is processed' : '* Includes this pending payment')}</span>
                                            <span class="text-[10px] font-black text-gray-400 uppercase">${Math.round(newPercentage)}% Completed</span>
                                        </div>

                                        ${!isProcessed ? `
                                            <div class="mt-6 pt-4 border-t border-gray-200/60 flex items-center gap-4 hidden">
                                                <a href="/order-management/payment-approval/${dist.id}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 flex items-center bg-white px-3 py-1.5 rounded border border-gray-200 shadow-sm transition-all hover:shadow-md">
                                                    Go to Full Verification Page <i class="bi bi-box-arrow-up-right ml-2"></i>
                                                </a>
                                            </div>
                                        ` : ''}
                                    </div>
                                </td>
                            </tr>
                        `;
                        ordersList.innerHTML += row;
                    });
                    
                    loading.classList.add('hidden');
                    dataSection.classList.remove('hidden');
                } else {
                    Swal.fire('Error', 'Could not fetch payment details.', 'error');
                    closeModal();
                }
            },
            error: function() {
                Swal.fire('Error', 'An internal error occurred.', 'error');
                closeModal();
            }
        });
    }

    function toggleAccordion(id) {
        const row = document.getElementById(id);
        const icon = document.getElementById(id + '-icon');
        const isHidden = row.classList.contains('hidden');
        
        // Close others? (Optional)
        // document.querySelectorAll('[id^="accordion-"]').forEach(el => {
        //     if(el.id !== id) el.classList.add('hidden');
        // });

        if (isHidden) {
            row.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            row.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }

    function approveBulkPayments(paymentId) {
        Swal.fire({
            title: 'Bulk Approve All?',
            text: "This will approve all linked order distributions and update the agent's balance.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, Approve All',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: '/api/agent-payments/approve-bulk',
                    type: 'POST',
                    data: {
                        agent_payment_id: paymentId,
                        _token: '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.message);
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Approved!',
                    text: 'All related orders have been processed.',
                    icon: 'success'
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }

    function rejectPayment(paymentId) {
        Swal.fire({
            title: 'Reject Agent Payment?',
            text: "Please enter the reason for rejecting this payment:",
            input: 'text',
            inputPlaceholder: 'Reason for rejection...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, Reject It',
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to specify a reason!'
                }
            },
            showLoaderOnConfirm: true,
            preConfirm: (reason) => {
                return $.ajax({
                    url: '/api/agent-payments/reject',
                    type: 'POST',
                    data: {
                        agent_payment_id: paymentId,
                        rejection_reason: reason,
                        _token: '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.message);
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Rejected!',
                    text: 'The payment has been rejected.',
                    icon: 'success'
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }

    function closeModal() {
        document.getElementById('paymentDetailsModal').classList.add('hidden');
    }
</script>
@endsection
