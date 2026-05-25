@extends('layouts.app')

@section('title', 'Agent Financial Management')

@php
    function getCNStatusConfig($status)
    {
        $configs = [
            0 => ['color' => 'bg-amber-100 text-amber-700 border-amber-300', 'label' => 'Pending Approval'],
            1 => ['color' => 'bg-emerald-100 text-emerald-700 border-emerald-300', 'label' => 'Approved'],
            2 => ['color' => 'bg-rose-100 text-rose-700 border-rose-300', 'label' => 'Rejected'],
            3 => ['color' => 'bg-blue-100 text-blue-700 border-blue-300', 'label' => 'Used'],
        ];
        return $configs[$status] ?? $configs[0];
    }
@endphp

@section('content')
<div class="min-h-screen bg-slate-50/50 p-4 md:p-8 font-sans" x-data="{ tab: 'payments' }">

    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold    text-slate-900 tracking-tight flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-200 flex items-center justify-center text-white">
                    <i class="bi bi-wallet2 text-xl"></i>
                </div>
                Agent Financial Management
            </h2>
            <p class="text-sm text-slate-500 mt-2 font-medium">
                Comprehensive overview of agent payments and credit notes.
            </p>
        </div>
        <div class="flex items-center gap-3">
             <button type="submit" form="filter-form" name="export" value="pdf" class="inline-flex items-center px-4 py-2.5 bg-rose-50 border border-rose-200 shadow-sm text-sm font-bold rounded-xl text-rose-700 hover:bg-rose-100 transition-all hover:shadow active:scale-95">
                <i class="bi bi-file-earmark-pdf-fill mr-2"></i> PDF
            </button>
            <button type="submit" form="filter-form" name="export" value="excel" class="inline-flex items-center px-4 py-2.5 bg-emerald-50 border border-emerald-200 shadow-sm text-sm font-bold rounded-xl text-emerald-700 hover:bg-emerald-100 transition-all hover:shadow active:scale-95">
                <i class="bi bi-file-earmark-excel-fill mr-2"></i> Excel
            </button>
             <button type="button" onclick="window.location.reload()" class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 shadow-sm text-sm font-bold rounded-xl text-slate-700 hover:bg-slate-50 transition-all hover:shadow active:scale-95">
                <i class="bi bi-arrow-clockwise mr-2"></i> Refresh Data
            </button>
        </div>
    </div>

    <!-- Summary Tiles (Glassmorphism/Modern style) -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <!-- Outstanding Tile -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-5 rounded-2xl border border-indigo-400/30 shadow-md relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <span class="block text-[11px] uppercase font-bold text-indigo-100 tracking-wider mb-1">Total Agent Outstanding</span>
                <span class="text-2xl font-bold text-white">Rs. {{ number_format($summary['totalAgentOutstanding'], 2) }}</span>
            </div>
        </div>
        
        <!-- Total Payments Tile -->
        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-5 rounded-2xl border border-emerald-400/30 shadow-md relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <span class="block text-[11px] uppercase font-bold text-emerald-100 tracking-wider mb-1">Total Payments (Filtered)</span>
                <span class="text-2xl font-bold text-white">Rs. {{ number_format($summary['totalPayments'], 2) }}</span>
            </div>
        </div>

        <!-- Pending Payments Tile -->
        <div class="bg-gradient-to-br from-amber-400 to-orange-500 p-5 rounded-2xl border border-amber-300/30 shadow-md relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <span class="block text-[11px] uppercase font-bold text-amber-50 tracking-wider mb-1 flex justify-between">
                    Pending Payments
                    <i class="bi bi-exclamation-circle text-white/80"></i>
                </span>
                <span class="text-2xl font-bold text-white">{{ $summary['pendingPayments'] }}</span>
            </div>
        </div>

        <!-- Total Credit Notes Tile -->
        <div class="bg-gradient-to-br from-blue-500 to-cyan-600 p-5 rounded-2xl border border-blue-400/30 shadow-md relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <span class="block text-[11px] uppercase font-bold text-blue-100 tracking-wider mb-1">Total Credit Notes (Filtered)</span>
                <span class="text-2xl font-bold text-white">Rs. {{ number_format($summary['totalCreditNotes'], 2) }}</span>
            </div>
        </div>

        <!-- Pending Credit Notes Tile -->
        <div class="bg-gradient-to-br from-rose-500 to-pink-600 p-5 rounded-2xl border border-rose-400/30 shadow-md relative overflow-hidden group hover:shadow-lg transition-all duration-300">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white rounded-full opacity-10 group-hover:scale-150 transition-transform duration-500"></div>
            <div class="relative z-10">
                <span class="block text-[11px] uppercase font-bold text-rose-100 tracking-wider mb-1 flex justify-between">
                    Pending Credit Notes
                    <i class="bi bi-exclamation-circle text-white/80"></i>
                </span>
                <span class="text-2xl font-bold text-white">{{ $summary['pendingCreditNotes'] }}</span>
            </div>
        </div>
    </div>

    <!-- Unified Filters Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
        <div class="bg-slate-50/80 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
            <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center">
                <i class="bi bi-funnel-fill mr-2 text-indigo-500"></i> Global Filters
            </h3>
        </div>
        <div class="p-6">
            <form id="filter-form" action="{{ route('agent-financial-management.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Agent Filter -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Agent</label>
                    <select name="agent_id" class="block w-full px-4 py-2.5 text-sm border border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl bg-slate-50/50 transition-all">
                        <option value="">All Agents</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->agent_name }} ({{ $agent->agent_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Duration (From - To)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="block w-full px-3 py-2 text-sm border border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl bg-slate-50/50 transition-all">
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="block w-full px-3 py-2 text-sm border border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl bg-slate-50/50 transition-all">
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Common Status</label>
                    <select name="status" class="block w-full px-4 py-2.5 text-sm border border-slate-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-xl bg-slate-50/50 transition-all">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Approved</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end justify-end gap-3">
                    <a href="{{ route('agent-financial-management.index') }}" class="inline-flex items-center px-4 py-2.5 border border-slate-200 text-xs font-bold rounded-xl text-slate-500 bg-white hover:bg-slate-50 transition-all active:scale-95">
                        Reset
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent text-xs font-black rounded-xl shadow-lg shadow-indigo-200 text-white bg-indigo-600 hover:bg-indigo-700 transition-all active:scale-95 uppercase tracking-widest">
                        <i class="bi bi-search mr-2"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs Header -->
    <div class="flex items-center gap-2 mb-6 border-b border-slate-200 pb-2 overflow-x-auto">
        <button @click="tab = 'payments'" :class="{ 'bg-slate-800 text-white shadow-md': tab === 'payments', 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200': tab !== 'payments' }" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 flex-shrink-0">
            <i class="bi bi-cash-stack"></i> Agent Payments
        </button>
        <button @click="tab = 'credit_notes'" :class="{ 'bg-slate-800 text-white shadow-md': tab === 'credit_notes', 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200': tab !== 'credit_notes' }" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 flex-shrink-0">
            <i class="bi bi-ticket-perforated"></i> Credit Notes
        </button>
    </div>

    <!-- Agent Payments Tab -->
    <div x-show="tab === 'payments'" x-cloak class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Agent</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Method</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-slate-700 whitespace-nowrap">
                                {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y h:i A') : $payment->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-800 whitespace-nowrap">
                                {{ $payment->agent->agent_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-indigo-700 whitespace-nowrap">
                                Rs. {{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                                @php
                                    $methodIcon = match((int)$payment->payment_method) {
                                        1 => '<i class="bi bi-cash text-emerald-600 mr-2"></i>',
                                        2 => '<i class="bi bi-credit-card text-blue-600 mr-2"></i>',
                                        3 => '<i class="bi bi-bank text-indigo-600 mr-2"></i>',
                                        4 => '<i class="bi bi-ticket-perforated text-orange-600 mr-2"></i>',
                                        default => '<i class="bi bi-question-circle text-slate-500 mr-2"></i>'
                                    };
                                    $methodText = match((int)$payment->payment_method) {
                                        1 => 'Cash',
                                        2 => 'Card',
                                        3 => 'Bank Transfer',
                                        4 => 'Credit Note',
                                        default => 'Other'
                                    };
                                @endphp
                                <div class="flex items-center text-sm font-medium text-slate-600">
                                    {!! $methodIcon !!} {{ $methodText }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = match((int)$payment->status) {
                                        0 => 'bg-amber-100 text-amber-700 border-amber-300',
                                        1 => 'bg-emerald-100 text-emerald-700 border-emerald-300',
                                        2 => 'bg-rose-100 text-rose-700 border-rose-300',
                                        default => 'bg-slate-100 text-slate-700 border-slate-300'
                                    };
                                    $statusText = match((int)$payment->status) {
                                        0 => 'Pending',
                                        1 => 'Approved',
                                        2 => 'Rejected',
                                        default => 'Unknown'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button onclick="viewPaymentDetails({{ $payment->id }})" class="text-indigo-600 hover:text-white hover:bg-indigo-600 bg-indigo-50 border border-indigo-100 px-4 py-1.5 rounded-lg text-sm font-bold transition-all shadow-sm">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-inbox text-4xl mb-3 text-slate-300"></i>
                                    <p class="font-medium text-slate-500">No payment records found matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 sm:px-6">
                {{ $payments->appends(request()->except('payments_page'))->links() }}
            </div>
        @endif
    </div>

    <!-- Credit Notes Tab -->
    <div x-show="tab === 'credit_notes'" x-cloak class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Credit Note #</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Agent</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($creditNotes as $note)
                        @php $cnStatus = getCNStatusConfig($note->status); @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $note->credit_note_number }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $note->note_type == 1 ? 'Physical Return' : 'Customer Return' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ $note->agent->agent_name ?? 'Unknown' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-700 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($note->credit_note_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-indigo-700 whitespace-nowrap">
                                Rs. {{ number_format($note->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="{{ $cnStatus['color'] }} border px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest inline-flex items-center gap-1.5">
                                    {{ $cnStatus['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <button onclick="viewNoteDetails({{ json_encode($note->load(['products.product', 'agent'])) }})" class="text-blue-600 hover:text-white hover:bg-blue-600 bg-blue-50 border border-blue-100 px-4 py-1.5 rounded-lg text-sm font-bold transition-all shadow-sm">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-receipt text-4xl mb-3 text-slate-300"></i>
                                    <p class="font-medium text-slate-500">No credit notes found matching your criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($creditNotes->hasPages())
            <div class="bg-slate-50 px-4 py-3 border-t border-slate-200 sm:px-6">
                {{ $creditNotes->appends(request()->except('cn_page'))->links() }}
            </div>
        @endif
    </div>
</div>

<!-- ======================= MODALS ======================= -->

<!-- 1. Payment Details Modal -->
<div id="paymentDetailsModal" class="hidden fixed inset-0 z-[60] overflow-y-auto px-4 py-6 sm:px-0 bg-slate-900/50 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-transparent" aria-hidden="true" onclick="closePaymentModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-slate-200 animate-in fade-in zoom-in duration-300">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight" id="payment-modal-title">Payment Approval</h3>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="flex items-center text-xs font-bold text-slate-500 uppercase tracking-widest" id="payment-subtitle-agent">Loading...</span>
                        <span class="flex items-center text-xs font-bold text-slate-500 uppercase tracking-widest border-l border-slate-200 pl-4" id="payment-subtitle-date">Loading...</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span id="payment-header-status-badge" class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Processing</span>
                    <button type="button" onclick="closePaymentModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 border border-slate-200 text-slate-500 hover:bg-slate-100 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <div class="px-8 py-8 h-[65vh] overflow-y-auto bg-slate-50/50" id="payment-modal-content">
                <div id="payment-modal-loading" class="flex flex-col items-center justify-center py-20">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                    <p class="mt-4 text-slate-500 font-medium">Gathering transaction details...</p>
                </div>
                
                <div id="payment-modal-data" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="bi bi-cash-stack text-4xl text-indigo-600"></i>
                            </div>
                            <span class="block text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">Total Payment</span>
                            <span class="text-3xl font-black text-slate-900" id="payment-detail-total">Rs. 0.00</span>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i id="payment-detail-method-icon" class="bi bi-credit-card text-4xl text-blue-600"></i>
                            </div>
                            <span class="block text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">Payment Method</span>
                            <span class="text-xl font-bold text-slate-900" id="payment-detail-method-text">Loading...</span>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i class="bi bi-layers text-4xl text-purple-600"></i>
                            </div>
                            <span class="block text-[10px] uppercase font-black text-slate-400 tracking-widest mb-1">Allocated Orders</span>
                            <span class="text-2xl font-black text-slate-900" id="payment-detail-count">0</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center">
                            <span class="w-8 h-[2px] bg-indigo-600 mr-3"></span> Distribution Breakdown
                        </h4>
                    </div>
                    
                    <div class="overflow-hidden border border-slate-200 rounded-2xl shadow-sm bg-white">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Order Ref</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Original Total</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-50/50">Payment Amount</th>
                                    <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Action</th>
                                </tr>
                            </thead>
                            <tbody id="payment-orders-list" class="divide-y divide-slate-50"></tbody>
                        </table>
                    </div>
                    
                    <div id="payment-credit-notes-section" class="mt-8 hidden">
                        <h4 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center mb-4">
                            <span class="w-8 h-[2px] bg-orange-500 mr-3"></span> Applied Credit Notes
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="payment-credit-notes-list"></div>
                    </div>

                    <!-- Notes Section -->
                    <div id="payment-notes-container" class="mt-8 hidden">
                        <h4 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center mb-4">
                            <span class="w-8 h-[2px] bg-slate-400 mr-3"></span> Agent Notes
                        </h4>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-sm italic text-slate-600" id="payment-notes">
                            No notes provided.
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white px-8 py-5 flex justify-between items-center border-t border-slate-100">
                @if(Auth::user()->hasPermission('can_payment_approve'))
                <div id="payment-bulk-approve-container" class="hidden">
                    <button id="btn-approve-all" type="button" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 active:scale-95">
                        <i class="bi bi-check2-all mr-2"></i> Approve All Related Orders
                    </button>
                </div>
                @endif
                <button type="button" onclick="closePaymentModal()" class="px-8 py-3 bg-slate-100 border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-200 transition-all ml-auto active:scale-95">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Credit Note Details Modal -->
<div id="cnDetailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeCnDetailModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden animate-in fade-in zoom-in duration-300 border border-slate-200">
            <div class="bg-white px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight" id="cnModalTitle">Credit Note Details</h2>
                    <p class="text-slate-500 text-sm font-bold tracking-widest uppercase mt-2" id="cnModalSubtitle">Details for CN-0000</p>
                </div>
                <button type="button" onclick="closeCnDetailModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 border border-slate-200 text-slate-500 hover:bg-slate-100 transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="p-8 max-h-[65vh] overflow-y-auto bg-slate-50/50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-6 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <div><p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1">Agent</p><p class="font-bold text-sm text-slate-800" id="cnDetailAgentName">-</p></div>
                            <div><p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1">Date</p><p class="font-bold text-sm text-slate-800" id="cnDetailDate">-</p></div>
                            <div><p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1">Type</p><p class="font-bold text-sm text-slate-800" id="cnDetailType">-</p></div>
                            <div><p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1">Status</p><div id="cnDetailStatusTag"></div></div>
                        </div>
                        
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                            <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-2">Reason</p>
                            <p class="text-sm text-slate-600 font-medium leading-relaxed" id="cnDetailReason">-</p>
                        </div>
                        
                        <div id="cnRejectReasonSection" class="hidden bg-rose-50 p-6 rounded-2xl shadow-sm border border-rose-100">
                            <p class="text-[10px] text-rose-500 uppercase font-black tracking-widest mb-2">Rejection Reason</p>
                            <p class="text-sm text-rose-700 font-medium leading-relaxed" id="cnDetailRejectReason">-</p>
                        </div>
                    </div>
                    <div class="bg-indigo-600 rounded-3xl p-8 flex flex-col justify-center items-center shadow-lg shadow-indigo-200 text-white relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
                        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-white opacity-10 rounded-full"></div>
                        <p class="text-indigo-200 text-center mb-3 uppercase text-xs font-black tracking-widest relative z-10">Total Credit Amount</p>
                        <p class="text-4xl font-black text-white text-center relative z-10" id="cnDetailTotalAmount">Rs 0.00</p>
                    </div>
                </div>
                
                <div class="mb-2">
                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest flex items-center mb-4">
                        <i class="bi bi-box-seam mr-2 text-indigo-500"></i> Returned Products
                    </h3>
                    <div class=" rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <table class="min-w-full text-left">
                            <thead class="bg-slate-50 sticky top-0">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-black tracking-widest">Product</th>
                                    <th class="px-6 py-4 text-center text-[10px] text-slate-400 uppercase font-black tracking-widest">Qty</th>
                                    <th class="px-6 py-4 text-[10px] text-slate-400 uppercase font-black tracking-widest">Reason</th>
                                    <th class="px-6 py-4 text-right text-[10px] text-slate-400 uppercase font-black tracking-widest">Total</th>
                                </tr>
                            </thead>
                            <tbody id="cnModalProductsTable" class="divide-y divide-slate-50 text-sm"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Actions Footer -->
            <div class="bg-white border-t border-slate-100 px-8 py-5">
                <div id="cnActionSection" class="flex flex-col md:flex-row gap-4">
                     @if(Auth::user()->hasPermission('can_approve_credit_note'))
                    <button onclick="approveNote()" class="flex-1 h-12 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 active:scale-95">
                        Approve Credit Note
                    </button>
                    @endif
                    {{-- <button onclick="showCnRejectInput()" class="flex-1 h-12 bg-white text-rose-600 border-2 border-rose-200 rounded-xl font-bold hover:bg-rose-50 hover:border-rose-300 transition-all active:scale-95">
                        Reject Request
                    </button> --}}
                </div>
                
                <div id="cnRejectArea" class="hidden animate-in slide-in-from-bottom-4 duration-300">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Reason for Rejection</label>
                    <textarea id="cnRejectReasonInput" rows="3" class="w-full p-4 border-2 border-slate-200 rounded-xl focus:outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 mb-4 transition-all text-sm font-medium" placeholder="Enter why this request is being rejected..."></textarea>
                    <div class="flex gap-3">
                        <button onclick="rejectNote()" class="flex-1 h-12 bg-rose-600 text-white rounded-xl font-bold hover:bg-rose-700 transition-all shadow-lg shadow-rose-200 active:scale-95">Confirm Reject</button>
                        <button onclick="hideCnRejectInput()" class="px-8 h-12 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition-all active:scale-95">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- Required Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    // ==========================================
    // AGENT PAYMENTS LOGIC
    // ==========================================
    function viewPaymentDetails(paymentId) {
        const modal = document.getElementById('paymentDetailsModal');
        const loading = document.getElementById('payment-modal-loading');
        const dataSection = document.getElementById('payment-modal-data');
        
        modal.classList.remove('hidden');
        loading.classList.remove('hidden');
        dataSection.classList.add('hidden');
        
        fetch(`/api/agent-payments/orders/${paymentId}`)
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    const payment = response.data;
                    document.getElementById('payment-modal-title').innerHTML = `Payment Approval <span class="text-sm font-normal text-slate-500">via ${payment.payment_method == 1 ? 'Cash' : (payment.payment_method == 2 ? 'Card' : (payment.payment_method == 4 ? 'Credit Note' : 'Bank Transfer'))}</span>`;
                    document.getElementById('payment-detail-total').textContent = `Rs. ${parseFloat(payment.amount).toLocaleString(undefined, {minimumFractionDigits: 2})}`;
                    document.getElementById('payment-detail-count').textContent = payment.distributions.length;
                    document.getElementById('payment-subtitle-agent').innerHTML = `<i class="bi bi-person-circle mr-1"></i> Agent: ${payment.agent ? payment.agent.agent_name : 'Unknown'}`;
                    document.getElementById('payment-subtitle-date').innerHTML = `<i class="bi bi-calendar3 mr-1"></i> Submitted: ${moment(payment.created_at).format('MMM DD, YYYY hh:mm A')}`;
                    
                    const methods = {
                        1: { text: 'Cash Payment', icon: 'bi-cash-stack text-emerald-600' },
                        2: { text: 'Card Payment', icon: 'bi-credit-card text-blue-600' },
                        3: { text: 'Bank Transfer', icon: 'bi-bank text-indigo-600' },
                        4: { text: 'Credit Note', icon: 'bi-ticket-perforated text-orange-600' }
                    };
                    const method = methods[payment.payment_method] || { text: 'Other', icon: 'bi-wallet2 text-slate-600' };
                    document.getElementById('payment-detail-method-text').textContent = method.text;
                    document.getElementById('payment-detail-method-icon').className = `bi ${method.icon.split(' ')[0]} text-4xl ${method.icon.split(' ')[1]}`;

                    const statuses = {
                        0: { text: 'Pending Approval', class: 'bg-amber-100 text-amber-800 border border-amber-200' },
                        1: { text: 'Approved & Settled', class: 'bg-emerald-100 text-emerald-800 border border-emerald-200' },
                        2: { text: 'Payment Rejected', class: 'bg-rose-100 text-rose-800 border border-rose-200' }
                    };
                    const status = statuses[payment.status] || { text: 'Unknown', class: 'bg-slate-100 text-slate-800 border border-slate-200' };
                    const badge = document.getElementById('payment-header-status-badge');
                    badge.className = `inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest ${status.class}`;
                    badge.innerHTML = status.text;

                    const bulkContainer = document.getElementById('payment-bulk-approve-container');
                    if (bulkContainer) {
                        if (payment.status == 0) {
                            bulkContainer.classList.remove('hidden');
                            document.getElementById('btn-approve-all').onclick = () => approveBulkPayments(payment.id);
                        } else {
                            bulkContainer.classList.add('hidden');
                        }
                    }

                    // Credit Notes
                    const cnSection = document.getElementById('payment-credit-notes-section');
                    const cnList = document.getElementById('payment-credit-notes-list');
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
                                            <span class="block text-[10px] text-orange-500 font-bold uppercase tracking-widest">Credit Note</span>
                                        </div>
                                    </div>
                                    <div class="text-right"><span class="block text-sm font-black text-orange-800">Rs. ${parseFloat(cn.total_amount).toLocaleString(undefined, {minimumFractionDigits: 2})}</span></div>
                                </div>`;
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
                    const ordersList = document.getElementById('payment-orders-list');
                    ordersList.innerHTML = '';
                    payment.distributions.forEach((dist, index) => {
                        const order = dist.order_request;
                        const paidAmount = parseFloat(order.paid_amount) || 0;
                        const grandTotal = parseFloat(order.grand_total) || 0;
                        const currentPayment = parseFloat(dist.payment_amount) || 0;
                        
                        let alreadyPaidBeforeThis = paidAmount;
                        if (payment.status == 1 && dist.status == 2) {
                            alreadyPaidBeforeThis = Math.max(0, paidAmount - currentPayment);
                        }

                        const percentage = (grandTotal > 0) ? (alreadyPaidBeforeThis / grandTotal) * 100 : 0;
                        const newPercentage = (grandTotal > 0) ? ((alreadyPaidBeforeThis + currentPayment) / grandTotal) * 100 : 0;
                        const isProcessed = dist.status != 1;
                        const rowId = `payment-accordion-${index}`;

                        ordersList.innerHTML += `
                            <tr class="hover:bg-slate-50 transition-colors cursor-pointer" onclick="togglePaymentAccordion('${rowId}')">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-indigo-700">#${order.order_number}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Order Type: ${order.order_type === 4 ? 'Agent Distribution' : 'Retail Sale'}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-slate-900">Rs. ${grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">Full Invoice Value</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap bg-indigo-50/50">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-indigo-800">Rs. ${currentPayment.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                                        <span class="text-[10px] text-indigo-400 font-bold">Allocated Portion</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold hover:bg-indigo-100 transition-all border border-indigo-100 shadow-sm">
                                        ${isProcessed ? 'View Details' : 'Verify Details'} <i class="bi bi-chevron-down ml-1.5 transition-transform" id="${rowId}-icon"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr id="${rowId}" class="hidden bg-slate-50/50">
                                <td colspan="4" class="px-6 py-8">
                                    <div class="max-w-xl">
                                        <h5 class="text-sm font-black text-indigo-700 mb-1">Order #${order.order_number}</h5>
                                        <p class="text-xs text-slate-600 mb-1">Order Total: Rs. ${grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2})}</p>
                                        <p class="text-xs text-slate-600 mb-1">Already Paid: Rs. ${alreadyPaidBeforeThis.toLocaleString(undefined, {minimumFractionDigits: 2})}</p>
                                        <p class="text-xs font-bold text-rose-600 mb-4">Remaining Balance: Rs. ${Math.max(0, grandTotal - alreadyPaidBeforeThis - currentPayment).toLocaleString(undefined, {minimumFractionDigits: 2})}</p>
                                        
                                        <div class="relative w-full bg-slate-200 rounded-full h-2 shadow-inner">
                                            <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: ${Math.min(100, percentage)}%"></div>
                                            <div class="bg-indigo-400 h-2 rounded-full absolute top-0 left-0 transition-all duration-1000 opacity-40 shadow-[0_0_8px_rgba(79,70,229,0.5)]" style="width: ${Math.min(100, newPercentage)}%"></div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-[10px] text-indigo-600 font-black italic tracking-wide">${isProcessed ? '* This payment is processed' : '* Includes this pending payment'}</span>
                                            <span class="text-[10px] font-black text-slate-400 uppercase">${Math.round(newPercentage)}% Completed</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    
                    loading.classList.add('hidden');
                    dataSection.classList.remove('hidden');
                } else {
                    Swal.fire('Error', 'Could not fetch payment details.', 'error');
                    closePaymentModal();
                }
            })
            .catch(() => {
                Swal.fire('Error', 'An internal error occurred.', 'error');
                closePaymentModal();
            });
    }

    function togglePaymentAccordion(id) {
        const row = document.getElementById(id);
        const icon = document.getElementById(id + '-icon');
        if (row.classList.contains('hidden')) {
            row.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            row.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }

    function closePaymentModal() {
        document.getElementById('paymentDetailsModal').classList.add('hidden');
    }

    function approveBulkPayments(paymentId) {
        Swal.fire({
            title: 'Bulk Approve All?',
            text: "Approve all linked order distributions and update agent balance?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, Approve All',
            customClass: {
                confirmButton: 'px-6 py-2.5 rounded-xl font-bold',
                cancelButton: 'px-6 py-2.5 rounded-xl font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/api/agent-payments/approve-bulk', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ agent_payment_id: paymentId })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            title: 'Approved!',
                            text: 'Orders processed successfully.',
                            icon: 'success',
                            confirmButtonColor: '#4f46e5'
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }

    // ==========================================
    // CREDIT NOTES LOGIC
    // ==========================================
    let selectedCnId = null;

    function viewNoteDetails(note) {
        selectedCnId = note.id;
        document.getElementById('cnModalSubtitle').innerText = 'Details for ' + note.credit_note_number;
        document.getElementById('cnDetailAgentName').innerText = note.agent ? note.agent.agent_name : '-';
        document.getElementById('cnDetailDate').innerText = moment(note.credit_note_date).format('MMM DD, YYYY');
        document.getElementById('cnDetailType').innerText = note.note_type == 1 ? 'Physical Return' : 'Customer Return';
        document.getElementById('cnDetailReason').innerText = note.reason || 'No reason provided';
        document.getElementById('cnDetailTotalAmount').innerText = 'Rs ' + parseFloat(note.total_amount).toLocaleString(undefined, {minimumFractionDigits: 2});

        const statuses = {
            0: { class: 'bg-amber-100 text-amber-700 border border-amber-300', label: 'Pending Approval' },
            1: { class: 'bg-emerald-100 text-emerald-700 border border-emerald-300', label: 'Approved' },
            2: { class: 'bg-rose-100 text-rose-700 border border-rose-300', label: 'Rejected' },
            3: { class: 'bg-blue-100 text-blue-700 border border-blue-300', label: 'Used' }
        };
        const st = statuses[note.status] || statuses[0];
        document.getElementById('cnDetailStatusTag').innerHTML = `<span class="${st.class} px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">${st.label}</span>`;

        if(note.status == 2 && note.reject_reason) {
            document.getElementById('cnRejectReasonSection').classList.remove('hidden');
            document.getElementById('cnDetailRejectReason').innerText = note.reject_reason;
        } else {
            document.getElementById('cnRejectReasonSection').classList.add('hidden');
        }

        const tbody = document.getElementById('cnModalProductsTable');
        tbody.innerHTML = '';
        note.products.forEach(item => {
            tbody.innerHTML += `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-800">${item.product ? item.product.product_name : 'Unknown Product'}</td>
                    <td class="px-6 py-4 text-center font-black text-slate-700">${item.qty}</td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-500">${item.reason || '-'}</td>
                    <td class="px-6 py-4 text-right font-black text-indigo-700">Rs ${parseFloat(item.total).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                </tr>
            `;
        });

        const cnActionSection = document.getElementById('cnActionSection');
        if (cnActionSection) {
            if(note.status == 0) {
                cnActionSection.classList.remove('hidden');
            } else {
                cnActionSection.classList.add('hidden');
            }
        }
        
        document.getElementById('cnRejectArea').classList.add('hidden');
        document.getElementById('cnDetailModal').classList.remove('hidden');
    }

    function closeCnDetailModal() {
        document.getElementById('cnDetailModal').classList.add('hidden');
        selectedCnId = null;
    }

    function showCnRejectInput() {
        document.getElementById('cnActionSection').classList.add('hidden');
        document.getElementById('cnRejectArea').classList.remove('hidden');
    }

    function hideCnRejectInput() {
        document.getElementById('cnRejectArea').classList.add('hidden');
        document.getElementById('cnActionSection').classList.remove('hidden');
    }

    function approveNote() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to approve this credit note.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, Approve!',
            customClass: {
                confirmButton: 'px-6 py-2.5 rounded-xl font-bold',
                cancelButton: 'px-6 py-2.5 rounded-xl font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) performCnAction('approve');
        });
    }

    function rejectNote() {
        const reason = document.getElementById('cnRejectReasonInput').value;
        if(!reason) {
            Swal.fire('Error', 'Please provide a reason for rejection', 'error');
            return;
        }
        performCnAction('reject', reason);
    }

    function performCnAction(action, reason = null) {
        const url = action === 'approve' ? `/credit-note/approve/${selectedCnId}` : `/credit-note/reject/${selectedCnId}`;
        const data = reason ? { reason: reason } : {};

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#059669'
                }).then(() => window.location.reload());
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    }
</script>
