@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6">
    
    {{-- HEADER --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl text-gray-900 font-bold">Invoice Management</h1>
                    <p class="text-gray-600">Track invoices and manage payments</p>
                </div>
            </div>

            <button onclick="openCreateInvoiceModal()" class="h-12 px-6 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl flex items-center gap-2 shadow-lg transition-all duration-300">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                New Invoice
            </button>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            {{-- Total Value --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <span class="text-purple-600 font-bold text-xl">$</span>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Total Value</h3>
                <p class="text-2xl text-gray-900 font-bold">Rs {{ number_format($summary['totalValue'], 2) }}</p>
                <p class="text-sm text-gray-500">{{ $summary['totalInvoices'] }} invoices</p>
            </div>

            {{-- Paid --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Paid</h3>
                <p class="text-2xl text-gray-900 font-bold">Rs {{ number_format($summary['paidValue'], 2) }}</p>
                <p class="text-sm text-gray-500">{{ $summary['invoicesByStatus']['paid'] ?? 0 }} invoices</p>
            </div>

            {{-- Unpaid --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Unpaid</h3>
                <p class="text-2xl text-gray-900 font-bold">Rs {{ number_format($summary['unpaidValue'], 2) }}</p>
                <p class="text-sm text-gray-500">{{ $summary['invoicesByStatus']['sent'] ?? 0 }} pending</p>
            </div>

            {{-- Overdue --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Overdue</h3>
                <p class="text-2xl text-gray-900 font-bold">Rs {{ number_format($summary['overdueValue'], 2) }}</p>
                <p class="text-sm text-gray-500">{{ $summary['overdueCount'] }} invoices</p>
            </div>

            {{-- Avg Days --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Avg Payment Days</h3>
                <p class="text-2xl text-gray-900 font-bold">{{ number_format($summary['averageDaysToPayment'], 1) }}</p>
                <p class="text-sm text-gray-500">days</p>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="bg-white rounded-2xl p-2 shadow-sm border border-gray-100 flex gap-2 overflow-x-auto" id="filterTabs">
            @php
                $tabs = [
                    ['id' => 'all', 'label' => 'All', 'count' => $summary['totalInvoices']],
                    ['id' => 'sent', 'label' => 'Sent', 'count' => $summary['invoicesByStatus']['sent'] ?? 0],
                    ['id' => 'partially-paid', 'label' => 'Partial', 'count' => $summary['invoicesByStatus']['partially-paid'] ?? 0],
                    ['id' => 'paid', 'label' => 'Paid', 'count' => $summary['invoicesByStatus']['paid'] ?? 0],
                    ['id' => 'overdue', 'label' => 'Overdue', 'count' => $summary['invoicesByStatus']['overdue'] ?? 0],
                    ['id' => 'draft', 'label' => 'Draft', 'count' => $summary['invoicesByStatus']['draft'] ?? 0],
                ];
            @endphp

            @foreach($tabs as $tab)
            <button 
                onclick="filterInvoices('{{ $tab['id'] }}')"
                data-tab="{{ $tab['id'] }}"
                class="tab-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 {{ $loop->first ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                <span>{{ $tab['label'] }}</span>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $loop->first ? 'bg-white/20' : 'bg-gray-200' }}">
                    {{ $tab['count'] }}
                </span>
            </button>
            @endforeach
        </div>
    </div>

    {{-- SEARCH & SORT --}}
    <div class="mb-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input 
                type="text" 
                id="searchInput"
                onkeyup="searchInvoices()"
                placeholder="Search by invoice number, customer name, email..." 
                class="w-full h-12 pl-12 pr-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-emerald-500 transition-colors"
            >
        </div>
        <div class="flex gap-3">
            <select id="sortSelect" onchange="sortInvoices()" class="h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-emerald-500 transition-colors">
                <option value="date">Sort by Date</option>
                <option value="dueDate">Sort by Due Date</option>
                <option value="value">Sort by Value</option>
                <option value="customer">Sort by Customer</option>
            </select>
        </div>
    </div>

    {{-- INVOICES LIST --}}
    <div class="space-y-4" id="invoicesList">
        @forelse($invoices as $invoice)
            @php
                $colors = [
                    'draft' => 'bg-gray-100 text-gray-700 border-gray-300',
                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                    'sent' => 'bg-blue-100 text-blue-700 border-blue-300',
                    'partially-paid' => 'bg-indigo-100 text-indigo-700 border-indigo-300',
                    'paid' => 'bg-green-100 text-green-700 border-green-300',
                    'overdue' => 'bg-red-100 text-red-700 border-red-300',
                    'cancelled' => 'bg-slate-100 text-slate-700 border-slate-300',
                ];
                $colorClass = $colors[$invoice->status] ?? $colors['draft'];
                
                $daysUntilDue = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($invoice->due_date), false);
                $isDueSoon = $daysUntilDue <= 3 && $daysUntilDue > 0 && $invoice->amount_due > 0;
                $isOverdue = $daysUntilDue < 0 && $invoice->amount_due > 0;
                
                // IMPORTANT: Construct the JSON object properly for JS consumption
                // Ensure keys match what view-invoice.blade.php expects (camelCase or snake_case as defined in your JS)
                // Based on previous JS, it expects: invoiceNumber, status, customerName, customerPhone, customerEmail, invoiceDate, dueDate, grandTotal, amountPaid, amountDue, lineItems, payments
                $invoiceJson = [
                    'id' => $invoice->id,
                    'invoiceNumber' => $invoice->invoice_number,
                    'status' => $invoice->status,
                    'customerName' => $invoice->customer_name,
                    'customerPhone' => $invoice->customer_phone,
                    'customerEmail' => $invoice->customer_email,
                    'customerType' => $invoice->customer_type ?? 'individual',
                    'billingAddress' => $invoice->billing_address,
                    'invoiceDate' => $invoice->invoice_date,
                    'dueDate' => $invoice->due_date,
                    'grandTotal' => $invoice->grand_total,
                    'amountPaid' => $invoice->amount_paid,
                    'amountDue' => $invoice->amount_due,
                    'paymentTerms' => $invoice->payment_terms,
                    'salesPersonName' => $invoice->sales_person_name ?? null,
                    'daysOverdue' => $isOverdue ? abs(ceil($daysUntilDue)) : 0,
                    'lineItems' => $invoice->lineItems ?? [], // Ensure relationship exists
                    'payments' => $invoice->payments ?? [], // Ensure relationship exists
                    'discount' => $invoice->discount ?? 0,
                    'tax' => $invoice->tax ?? 0,
                    'subtotal' => $invoice->subtotal ?? 0,
                    'shippingCharges' => $invoice->shipping_charges ?? 0,
                    'adjustments' => $invoice->adjustments ?? 0,
                    'termsAndConditions' => $invoice->terms_and_conditions ?? '',
                    'customerNotes' => $invoice->customer_notes ?? '',
                    'internalNotes' => $invoice->internal_notes ?? '',
                    'quotationId' => $invoice->quotation_id ?? null,
                    'orderId' => $invoice->order_id ?? null,
                ];
            @endphp

            <div class="invoice-item bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300" 
                 data-status="{{ $invoice->status }}"
                 data-customer="{{ strtolower($invoice->customer_name) }}"
                 data-number="{{ strtolower($invoice->invoice_number) }}"
                 data-email="{{ strtolower($invoice->customer_email) }}"
                 data-value="{{ $invoice->grand_total }}"
                 data-date="{{ $invoice->invoice_date }}"
                 data-due="{{ $invoice->due_date }}">
                
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-xl text-gray-900 font-semibold">{{ $invoice->invoice_number }}</h3>
                                    
                                    <span class="{{ $colorClass }} border px-2 py-1 rounded-lg flex items-center gap-1.5 text-xs font-medium uppercase">
                                        {{ str_replace('-', ' ', $invoice->status) }}
                                    </span>

                                    @if($isDueSoon)
                                        <span class="bg-orange-100 text-orange-700 border border-orange-300 px-2 py-1 rounded-lg flex items-center gap-1.5 text-xs">
                                            Due in {{ ceil($daysUntilDue) }}d
                                        </span>
                                    @endif
                                    @if($isOverdue)
                                        <span class="bg-red-100 text-red-700 border border-red-300 px-2 py-1 rounded-lg flex items-center gap-1.5 text-xs">
                                            {{ abs(ceil($daysUntilDue)) }}d overdue
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-wrap items-center gap-4 mb-3 text-gray-600">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        <span>{{ $invoice->customer_name }}</span>
                                    </div>
                                    @if($invoice->customer_phone)
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-400">•</span>
                                        <span>{{ $invoice->customer_phone }}</span>
                                    </div>
                                    @endif
                                </div>

                                @if($invoice->amount_paid > 0) 
                                    <div class="mb-2 w-full ">
                                        <div class="flex items-center justify-between text-sm mb-1">
                                            <span class="text-gray-600">Payment Progress</span>
                                            <span class="text-gray-900">
                                                Rs {{ number_format($invoice->amount_paid, 2) }} / Rs {{ number_format($invoice->grand_total, 2) }}
                                            </span>
                                        </div>
                                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all"
                                                    style="width: {{ ($invoice->amount_paid / $invoice->grand_total) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span>Issued: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('M d, Y') }}</span>
                                    <span class="text-gray-400">•</span>
                                    <span>Due: {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-3 min-w-[220px]">
                        <div class="text-right">
                            <div class="text-2xl text-gray-900 mb-1 font-bold">Rs {{ number_format($invoice->grand_total, 2) }}</div>
                            @if($invoice->amount_due > 0)
                                <div class="text-sm">
                                    <span class="text-red-600 font-medium">Due: Rs {{ number_format($invoice->amount_due, 2) }}</span>
                                </div>
                            @endif
                            @if($invoice->status == 'paid')
                                <div class="text-sm text-green-600 flex items-center justify-end gap-1 font-medium">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Fully Paid
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            {{-- View Button --}}
                            <button onclick="openViewInvoiceModal(this)" 
                                    data-invoice="{{ json_encode($invoiceJson) }}" 
                                    class="h-10 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl flex items-center gap-2 transition-all duration-300 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                View
                            </button>

                            @if($invoice->amount_due > 0 && $invoice->status !== 'cancelled')
                            <button onclick="openPaymentModal({{ json_encode($invoiceJson) }})" class="h-10 px-4 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-xl flex items-center gap-2 transition-all duration-300 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                Payment
                            </button>
                            @endif

                            @if(($invoice->status === 'draft' || $invoice->status === 'pending') && $invoice->customer_email)
                            <button onclick="sendInvoice({{ $invoice->id }}, 'email')" class="h-10 px-4 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-xl flex items-center gap-2 transition-all duration-300 text-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <h3 class="text-xl text-gray-600 mb-2">No invoices found</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first invoice</p>
                <button onclick="openCreateInvoiceModal()" class="h-12 px-6 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-xl flex items-center gap-2 mx-auto shadow-lg transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Create Invoice
                </button>
            </div>
        @endforelse
    </div>
</div>

@include('DistributorAndSalesManagement.modals.createInvoice')
@include('DistributorAndSalesManagement.modals.viewInvoice')
@include('DistributorAndSalesManagement.modals.paymentInvoice')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Filtering Logic
    function filterInvoices(status) {
        const rows = document.querySelectorAll('.invoice-item');
        const buttons = document.querySelectorAll('.tab-btn');

        buttons.forEach(btn => {
            const isSelected = btn.dataset.tab === status;
            btn.className = isSelected 
                ? 'tab-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-md'
                : 'tab-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 text-gray-600 hover:bg-gray-100';
            
            const badge = btn.querySelector('span:last-child');
            badge.className = isSelected
                ? 'px-2 py-0.5 rounded-full text-xs bg-white/20'
                : 'px-2 py-0.5 rounded-full text-xs bg-gray-200';
        });

        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    // 2. Search Logic
    function searchInvoices() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.invoice-item');

        rows.forEach(row => {
            const text = (
                row.dataset.number + ' ' + 
                row.dataset.customer + ' ' + 
                row.dataset.email
            ).toLowerCase();

            if (text.includes(query)) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    // 3. Sorting Logic
    function sortInvoices() {
        const sortBy = document.getElementById('sortSelect').value;
        const container = document.getElementById('invoicesList');
        const rows = Array.from(document.querySelectorAll('.invoice-item'));

        rows.sort((a, b) => {
            if (sortBy === 'value') {
                return parseFloat(b.dataset.value) - parseFloat(a.dataset.value);
            } else if (sortBy === 'date') {
                return new Date(b.dataset.date) - new Date(a.dataset.date);
            } else if (sortBy === 'dueDate') {
                return new Date(a.dataset.due) - new Date(b.dataset.due);
            } else if (sortBy === 'customer') {
                return a.dataset.customer.localeCompare(b.dataset.customer);
            }
        });

        rows.forEach(row => container.appendChild(row));
    }

    // 4. SweetAlert Action Logic
    function sendInvoice(id, method) {
        Swal.fire({
            title: 'Send Invoice?',
            text: `Are you sure you want to send invoice #${id} via ${method}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, send it!',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Sent!',
                    'The invoice has been sent successfully.',
                    'success'
                );
            }
        });
    }
</script>
@endsection