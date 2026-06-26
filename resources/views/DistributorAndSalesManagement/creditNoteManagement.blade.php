@extends('layouts.app')

@section('title', 'Credit Note Management')

@php
    function getStatusConfig($status)
    {
        $configs = [
            0 => [
                'color' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                'label' => 'Pending Approval',
                'icon' => 'clock',
            ],
            1 => [
                'color' => 'bg-green-100 text-green-700 border-green-300',
                'label' => 'Approved',
                'icon' => 'check-circle',
            ],
            2 => [
                'color' => 'bg-red-100 text-red-700 border-red-300',
                'label' => 'Rejected',
                'icon' => 'x-circle',
            ],
            3 => [
                'color' => 'bg-blue-100 text-blue-700 border-blue-300',
                'label' => 'Used',
                'icon' => 'archive',
            ],
        ];
        return $configs[$status] ?? $configs[0];
    }
@endphp

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-blue-50 p-4 md:p-6 font-sans text-slate-800">

        {{-- HEADER --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4 flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="text-white">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10 9 9 9 8 9" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Credit Note Management</h1>
                        <p class="text-gray-600">Approve or reject sales return credit notes from agents</p>
                    </div>
                </div>
            </div>

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                {{-- Total --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </div>
                    </div>
                    <h3 class="text-gray-600 mb-1">Total Notes</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['total'] }}</p>
                </div>

                {{-- Pending --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                    </div>
                    <h3 class="text-gray-600 mb-1">Pending</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['pending'] }}</p>
                </div>

                {{-- Approved Value --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                    </div>
                    <h3 class="text-gray-600 mb-1">Approved Value</h3>
                    <p class="text-2xl font-bold text-gray-900">Rs {{ number_format($summary['total_value'], 2) }}</p>
                </div>

                {{-- Rejected --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </div>
                    </div>
                    <h3 class="text-gray-600 mb-1">Rejected</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $summary['rejected'] }}</p>
                </div>
            </div>

            {{-- FILTERS --}}
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="flex-1 relative">
                    <form action="{{ route('credit-note-management.index') }}" method="GET" class="flex gap-2">
                        <div class="relative flex-1">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by CN number or Agent name..."
                                class="w-full h-12 pl-12 pr-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors">
                        </div>
                        
                        <select name="agent_id" onchange="this.form.submit()"
                            class="h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors">
                            <option value="all" {{ request('agent_id') == 'all' ? 'selected' : '' }}>All Agents</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->agent_name }} ({{ $agent->agent_code }})
                                </option>
                            @endforeach
                        </select>

                        <select name="status" onchange="this.form.submit()"
                            class="h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Approved</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Rejected</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Used</option>
                        </select>

                        <button type="submit" class="h-12 px-6 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors shadow-md">
                            Filter
                        </button>

                        <a href="{{ route('credit-note.export', request()->all()) }}" 
                            class="h-12 px-6 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors shadow-md flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                            Export Excel
                        </a>
                    </form>
                </div>
            </div>
        </div>

        {{-- LIST --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm font-semibold uppercase tracking-wider">
                        <th class="px-6 py-4">Credit Note</th>
                        <th class="px-6 py-4">Agent</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($creditNotes as $note)
                        @php 
                            $status = getStatusConfig($note->status);
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $note->credit_note_number }}</div>
                                <div class="text-xs text-gray-500 uppercase">{{ $note->note_type == 1 ? 'Physical Return' : 'Customer Return' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $note->agent->agent_name ?? 'Unknown Agent' }}</div>
                                <div class="text-xs text-gray-500">{{ $note->agent->agent_code ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($note->credit_note_date)->tz('Asia/Colombo')->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                Rs {{ number_format($note->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="{{ $status['color'] }} border px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1.5 w-fit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $status['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="viewNoteDetails({{ json_encode($note->load(['products.product', 'agent'])) }})"
                                    class="h-10 px-4 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors font-medium text-sm">
                                    View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="mb-2">No credit notes found</div>
                                <div class="text-sm">Try adjusting your search or filters</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $creditNotes->links() }}
        </div>
    </div>

    {{-- DETAIL MODAL --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm" onclick="closeDetailModal()"></div>
            
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden animate-in fade-in zoom-in duration-300">
                {{-- Modal Header --}}
                <div class="bg-blue-600  px-8 py-6 text-white flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold" id="modalTitle">Credit Note Details</h2>
                        <p class="text-blue-100 text-sm" id="modalSubtitle">Details for CN-0000</p>
                    </div>
                    <button onclick="closeDetailModal()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/20 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="9" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        {{-- Information Section --}}
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Agent</p>
                                    <p class="font-medium" id="detailAgentName">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Date</p>
                                    <p class="font-medium" id="detailDate">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Type</p>
                                    <p class="font-medium" id="detailType">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-semibold">Status</p>
                                    <div id="detailStatusTag"></div>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Reason</p>
                                <p class="text-sm text-gray-700 italic" id="detailReason">-</p>
                            </div>
                            <div id="rejectReasonSection" class="hidden">
                                <p class="text-xs text-red-500 uppercase font-semibold">Rejection Reason</p>
                                <p class="text-sm text-red-700 italic" id="detailRejectReason">-</p>
                            </div>
                        </div>

                        {{-- Summary Section --}}
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col justify-center">
                            <p class="text-slate-500 text-center mb-2 uppercase text-xs font-bold tracking-wider">Total Credit Amount</p>
                            <p class="text-4xl font-black text-slate-900 text-center" id="detailTotalAmount">Rs 0.00</p>
                        </div>
                    </div>

                    {{-- Products Table --}}
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                            Returned Products
                        </h3>
                        <div class="max-h-64 overflow-y-auto rounded-xl border border-gray-100">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 sticky top-0">
                                    <tr class="text-xs text-slate-500 uppercase font-bold">
                                        <th class="px-4 py-3">Product / Customer</th>
                                        <th class="px-4 py-3 text-center">Return Date</th>
                                        <th class="px-4 py-3">Reason</th>
                                        <th class="px-4 py-3 text-center">Qty</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="modalProductsTable" class="divide-y divide-gray-50 text-sm">
                                    {{-- JS Injected --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Audit Trail (History) --}}
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Audit Trail
                        </h3>
                        <div class="max-h-64 overflow-y-auto rounded-xl border border-gray-200 p-4 bg-slate-50 space-y-4" id="modalHistoryContainer">
                            {{-- JS Injected --}}
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div id="actionSection" class="flex flex-col md:flex-row gap-4 pt-6 border-t">
                        <button onclick="approveNote()" class="flex-1 h-12 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-green-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Approve Credit Note
                        </button>
                        <button onclick="showRejectInput()" class="flex-1 h-12 bg-red-50 text-red-600 border-2 border-red-100 rounded-xl font-bold hover:bg-red-100 transition-all flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Reject Request
                        </button>
                    </div>

                    {{-- Reject Input Area (Hidden initially) --}}
                    <div id="rejectArea" class="hidden pt-6 border-t animate-in slide-in-from-bottom-4 duration-300">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Reason for Rejection</label>
                        <textarea id="rejectReasonInput" rows="3" class="w-full p-4 border-2 border-gray-400 rounded-2xl focus:outline-none focus:border-red-500 mb-4" placeholder="Enter why this request is being rejected..."></textarea>
                        <div class="flex gap-2">
                            <button onclick="rejectNote()" class="flex-1 h-12 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all">Confirm Reject</button>
                            <button onclick="hideRejectInput()" class="px-6 h-12 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-all">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let selectedNoteId = null;

        function viewNoteDetails(note) {
            selectedNoteId = note.id;
            document.getElementById('modalSubtitle').innerText = 'Details for ' + note.credit_note_number;
            document.getElementById('detailAgentName').innerText = note.agent ? note.agent.agent_name : '-';
            document.getElementById('detailDate').innerText = note.credit_note_date;
            document.getElementById('detailType').innerText = note.note_type == 1 ? 'Physical Return' : 'Customer Return';
            document.getElementById('detailReason').innerText = note.reason || 'No reason provided';
            document.getElementById('detailTotalAmount').innerText = 'Rs ' + parseFloat(note.total_amount).toLocaleString(undefined, {minimumFractionDigits: 2});

            // Status Tag
            const statusConfig = {
                0: { class: 'bg-yellow-100 text-yellow-700 border-yellow-300', label: 'Pending Approval' },
                1: { class: 'bg-green-100 text-green-700 border-green-300', label: 'Approved' },
                2: { class: 'bg-red-100 text-red-700 border-red-300', label: 'Rejected' },
                3: { class: 'bg-blue-100 text-blue-700 border-blue-300', label: 'Used' }
            };
            const config = statusConfig[note.status] || statusConfig[0];
            document.getElementById('detailStatusTag').innerHTML = `<span class="${config.class} border px-2 py-0.5 rounded-full text-xs font-bold">${config.label}</span>`;

            // Reject Reason
            if(note.status == 2 && note.reject_reason) {
                document.getElementById('rejectReasonSection').classList.remove('hidden');
                document.getElementById('detailRejectReason').innerText = note.reject_reason;
            } else {
                document.getElementById('rejectReasonSection').classList.add('hidden');
            }

            // Products Table
            const productsTable = document.getElementById('modalProductsTable');
            productsTable.innerHTML = '';
            note.products.forEach(item => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td class="px-4 py-3">
                        <div class="font-bold text-slate-900">${item.product ? item.product.product_name : 'Unknown Product'}</div>
                    </td>
                    <td class="px-4 py-3 text-center text-xs text-slate-600">${note.credit_note_date}</td>
                    <td class="px-4 py-3 text-xs text-slate-500 italic">${item.reason || '-'}</td>
                    <td class="px-4 py-3 text-center font-bold text-slate-700">${item.qty}</td>
                    <td class="px-4 py-3 text-right font-black text-slate-900">Rs ${parseFloat(item.total).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                `;
                productsTable.appendChild(row);
            });

            // History/Audit Trail
            const historyContainer = document.getElementById('modalHistoryContainer');
            historyContainer.innerHTML = '';
            if (note.histories && note.histories.length > 0) {
                note.histories.forEach(hist => {
                    let dateStr = hist.created_at;
                    try {
                        if (typeof moment !== 'undefined') {
                            dateStr = moment(hist.created_at).format('YYYY-MM-DD HH:mm:ss');
                        } else {
                            dateStr = new Date(hist.created_at).toLocaleString('en-GB', { timeZone: 'Asia/Colombo' });
                        }
                    } catch (e) {}
                    
                    const creatorName = hist.creator ? hist.creator.user_name : 'System';
                    
                    let statusBadge = '';
                    if (hist.status === 0) statusBadge = '<span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded text-xs font-semibold">Created</span>';
                    else if (hist.status === 1) statusBadge = '<span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-semibold">Approved</span>';
                    else if (hist.status === 2) statusBadge = '<span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-semibold">Rejected</span>';
                    
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex flex-col md:flex-row md:justify-between border-b border-gray-200 pb-2 last:border-0 last:pb-0';
                    itemDiv.innerHTML = `
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                ${statusBadge}
                                <span class="font-bold text-sm text-slate-800">${hist.action}</span>
                            </div>
                            <p class="text-sm text-slate-600">${hist.description || ''}</p>
                        </div>
                        <div class="text-xs text-slate-400 mt-1 md:mt-0 text-right">
                            <div>by <span class="font-semibold text-slate-600">${creatorName}</span></div>
                            <div>${dateStr}</div>
                        </div>
                    `;
                    historyContainer.appendChild(itemDiv);
                });
            } else {
                historyContainer.innerHTML = '<p class="text-gray-500 text-center py-4 text-sm">No history records found.</p>';
            }

            // Action Buttons Visibility
            if(note.status == 0) {
                document.getElementById('actionSection').classList.remove('hidden');
            } else {
                document.getElementById('actionSection').classList.add('hidden');
            }
            
            document.getElementById('rejectArea').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            selectedNoteId = null;
        }

        function showRejectInput() {
            document.getElementById('actionSection').classList.add('hidden');
            document.getElementById('rejectArea').classList.remove('hidden');
        }

        function hideRejectInput() {
            document.getElementById('rejectArea').classList.add('hidden');
            document.getElementById('actionSection').classList.remove('hidden');
        }

        function approveNote() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to approve this credit note.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Approve!'
            }).then((result) => {
                if (result.isConfirmed) {
                    performAction('approve');
                }
            })
        }

        function rejectNote() {
            const reason = document.getElementById('rejectReasonInput').value;
            if(!reason) {
                Swal.fire('Error', 'Please provide a reason for rejection', 'error');
                return;
            }
            performAction('reject', reason);
        }

        function performAction(action, reason = null) {
            const url = action === 'approve' ? `/credit-note/approve/${selectedNoteId}` : `/credit-note/reject/${selectedNoteId}`;
            const data = reason ? { reason: reason } : {};

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire('Success', data.message, 'success').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Something went wrong!', 'error');
            });
        }
    </script>
@endsection
