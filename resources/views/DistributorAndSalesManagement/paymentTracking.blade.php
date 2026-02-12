@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6">
    
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            </div>
            <div>
                <h1 class="text-3xl text-gray-900 font-bold">Payment Tracking</h1>
                <p class="text-gray-600">Monitor receivables and payment analytics</p>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Total Receivables --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <span class="text-blue-600 font-bold text-xl">$</span>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Total Receivables</h3>
                <p class="text-2xl text-gray-900 font-bold">Rs {{ number_format($summary['totalReceivables'], 2) }}</p>
                <p class="text-sm text-gray-500">Outstanding balance</p>
            </div>

            {{-- Overdue --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Overdue</h3>
                <p class="text-2xl text-red-600 font-bold">Rs {{ number_format($summary['overdueAmount'], 2) }}</p>
                <p class="text-sm text-gray-500">{{ $summary['overdueCount'] }} invoices</p>
            </div>

            {{-- Due Soon --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Due Within 7 Days</h3>
                <p class="text-2xl text-orange-600 font-bold">Rs {{ number_format($summary['dueSoonAmount'], 2) }}</p>
                <p class="text-sm text-gray-500">{{ $summary['dueSoonCount'] }} invoices</p>
            </div>

            {{-- Recent Payments --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Last 30 Days</h3>
                <p class="text-2xl text-green-600 font-bold">Rs {{ number_format($summary['recentPaymentsAmount'], 2) }}</p>
                <p class="text-sm text-gray-500">{{ count($summary['recentPayments']) }} payments</p>
            </div>
        </div>

        {{-- Aging Analysis --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
            <h3 class="text-lg text-gray-900 mb-4 flex items-center gap-2 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                Aging Analysis
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @php
                    $buckets = ['0-30' => 'green', '31-60' => 'yellow', '61-90' => 'orange', '90+' => 'red'];
                @endphp
                @foreach($buckets as $bucket => $color)
                    @php 
                        $data = $summary['aging'][$bucket]; 
                        $colorClass = match($color) {
                            'green' => 'bg-green-50 border-green-200 text-green-700',
                            'yellow' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
                            'orange' => 'bg-orange-50 border-orange-200 text-orange-700',
                            'red' => 'bg-red-50 border-red-200 text-red-700',
                        };
                    @endphp
                    <div class="rounded-xl p-4 border-2 {{ $colorClass }} transition-all hover:scale-105 cursor-pointer">
                        <div class="text-sm mb-2 font-medium">{{ $bucket }} days</div>
                        <div class="text-2xl mb-1 font-bold">Rs {{ number_format($data['amount'], 2) }}</div>
                        <div class="text-xs opacity-75">{{ $data['count'] }} invoices</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-2xl p-2 shadow-sm border border-gray-100 flex gap-2 overflow-x-auto">
            <button onclick="filterTracking('all', this)" class="track-btn active flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md">
                <span>Outstanding</span>
            </button>
            <button onclick="filterTracking('overdue', this)" class="track-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 text-gray-600 hover:bg-gray-100">
                <span>Overdue</span>
            </button>
            <button onclick="filterTracking('due-soon', this)" class="track-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 text-gray-600 hover:bg-gray-100">
                <span>Due Soon</span>
            </button>
            <button onclick="filterTracking('paid', this)" class="track-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 text-gray-600 hover:bg-gray-100">
                <span>Paid</span>
            </button>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: List --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Search --}}
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" id="trackSearch" onkeyup="searchTracking()" placeholder="Search by invoice, customer..." class="w-full h-12 pl-12 pr-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                </div>
            </div>

            {{-- Invoices List --}}
            <div class="space-y-3" id="trackingList">
                @forelse($invoices as $inv)
                    @php
                        // Construct JSON for Modal
                        $json = json_encode([
                            'id' => $inv->id,
                            'invoiceNumber' => $inv->invoice_number,
                            'amountDue' => $inv->amount_due,
                            'amountPaid' => $inv->amount_paid,
                            'grandTotal' => $inv->grand_total,
                            // Add other fields as needed by RecordPaymentModal
                        ]);

                        $isOverdue = $inv->status === 'overdue';
                        $isDueSoon = false; // Logic simplified for blade
                        if($inv->amount_due > 0) {
                            $days = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($inv->due_date), false);
                            if($days >= 0 && $days <= 7) $isDueSoon = true;
                        }
                    @endphp
                    <div class="track-item bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all"
                         data-status="{{ $inv->status }}"
                         data-due-soon="{{ $isDueSoon ? 'true' : 'false' }}"
                         data-search="{{ strtolower($inv->invoice_number . ' ' . $inv->customer_name) }}">
                        
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg text-gray-900 font-semibold">{{ $inv->invoice_number }}</h3>
                                    @if($isOverdue)
                                        <span class="bg-red-100 text-red-700 border border-red-300 px-2 py-1 rounded-lg text-xs font-medium">Overdue</span>
                                    @endif
                                    @if($isDueSoon)
                                        <span class="bg-orange-100 text-orange-700 border border-orange-300 px-2 py-1 rounded-lg text-xs font-medium">Due Soon</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-gray-600 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <span>{{ $inv->customer_name }}</span>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span>Due: {{ $inv->due_date }}</span>
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-2xl text-gray-900 mb-1 font-bold">Rs {{ number_format($inv->amount_due, 2) }}</div>
                                @if($inv->amount_paid > 0)
                                    <div class="text-sm text-gray-500">Paid: Rs {{ number_format($inv->amount_paid, 2) }}</div>
                                @endif
                                
                                <div class="flex gap-2 justify-end mt-3">
                                    {{-- HISTORY BUTTON --}}
                                    @if(count($inv->payments) > 0)
                                        <button onclick='openHistoryModal({!! $json !!})' class="h-9 px-4 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg flex items-center gap-2 text-sm transition-all font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l4 2"/></svg>
                                            History
                                        </button>
                                    @endif

                                    {{-- PAY BUTTON --}}
                                    @if($inv->amount_due > 0)
                                        <button onclick='openPaymentModal({!! $json !!})' class="h-9 px-4 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg flex items-center gap-2 text-sm transition-all font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                            Pay
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-500">No invoices found.</div>
                @endforelse
            </div>
        </div>

        {{-- Right Column: Recent Payments --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg text-gray-900 mb-4 flex items-center gap-2 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" x2="12" y1="6" y2="18"/></svg>
                    Recent Payments
                </h3>
                @if($summary['recentPayments']->isEmpty())
                    <div class="text-center py-8 text-gray-500">No recent payments</div>
                @else
                    <div class="space-y-3">
                        @foreach($summary['recentPayments'] as $payment)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition-colors">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="text-2xl">
                                            @if($payment->method == 'cash') 💵 
                                            @elseif($payment->method == 'bank-transfer') 🏦 
                                            @else 💳 @endif
                                        </div>
                                        <div>
                                            <div class="text-gray-900 font-bold">Rs {{ number_format($payment->amount, 2) }}</div>
                                            <div class="text-xs text-gray-500 capitalize">{{ str_replace('-', ' ', $payment->method) }}</div>
                                        </div>
                                    </div>
                                    <span class="bg-green-100 text-green-700 border border-green-300 px-2 py-1 rounded-lg text-xs font-medium">Completed</span>
                                </div>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                                        <span>{{ $payment->invoiceNumber }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                                        <span>{{ $payment->paymentDate }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Include Payment Modal --}}
@include('DistributorAndSalesManagement.modals.paymentInvoice')
@include('DistributorAndSalesManagement.modals.paymentHistory')

{{-- JS Logic --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function filterTracking(mode, btn) {
        // UI
        document.querySelectorAll('.track-btn').forEach(b => {
            b.className = 'track-btn flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 text-gray-600 hover:bg-gray-100';
        });
        btn.className = 'track-btn active flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md';

        // Logic
        const rows = document.querySelectorAll('.track-item');
        rows.forEach(row => {
            let show = false;
            if(mode === 'all' && parseFloat(row.querySelector('.text-2xl').innerText.replace(/[^0-9.-]+/g,"")) > 0) show = true;
            if(mode === 'overdue' && row.dataset.status === 'overdue') show = true;
            if(mode === 'due-soon' && row.dataset.dueSoon === 'true') show = true;
            if(mode === 'paid' && row.dataset.status === 'paid') show = true;

            if(show) row.classList.remove('hidden'); else row.classList.add('hidden');
        });
    }

    function searchTracking() {
        const q = document.getElementById('trackSearch').value.toLowerCase();
        document.querySelectorAll('.track-item').forEach(row => {
            if(row.dataset.search.includes(q)) row.classList.remove('hidden');
            else row.classList.add('hidden');
        });
    }
</script>
@endsection