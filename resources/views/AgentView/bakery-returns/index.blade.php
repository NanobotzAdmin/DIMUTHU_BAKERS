@extends('layouts.app')

@section('title', 'Bakery Returns & Credit Notes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Bakery Returns & Credit Notes</h1>
            <p class="text-sm text-gray-500">Track return requests, audit trails, and manage credit notes issued by the bakery.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openCreateReturnModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 shadow-sm cursor-pointer">
                <i class="bi bi-plus-lg"></i> Create Bakery Return
            </button>
            <a href="{{ route('agent-panel.dashboard') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors no-underline">
                ← Back
            </a>
        </div>
    </div>

    <!-- Stats Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- Main Stats Card (Spans 2 columns) -->
        <div class="lg:col-span-2 bg-slate-900 text-white p-6 rounded-2xl shadow-md flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Returns Value</span>
                <h2 id="statsTotalVal" class="text-3xl font-black mt-1">Rs. 0.00</h2>
            </div>
            <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4 relative z-10">
                <span class="text-xs text-slate-400 font-semibold" id="statsNotesCount">0 Total Return Notes</span>
                <span class="px-2.5 py-0.5 bg-white/10 rounded-full text-[10px] font-bold uppercase tracking-wider text-slate-300">Active History</span>
            </div>
            <!-- Decorative icon background -->
            <div class="absolute right-0 bottom-0 translate-x-6 translate-y-6 opacity-[0.03] pointer-events-none">
                <i class="bi bi-arrow-left-right text-[150px]"></i>
            </div>
        </div>

        <!-- Pending Card -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-clock-history"></i>
                </span>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending Approval</span>
                    <h4 id="statsPendingVal" class="text-lg font-extrabold text-slate-900 mt-0.5">Rs. 0.00</h4>
                </div>
            </div>
            <span class="text-xs text-slate-400 font-medium mt-4" id="statsPendingCount">0 Notes pending</span>
        </div>

        <!-- Approved Card -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="bi bi-check-circle"></i>
                </span>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Approved (Unused)</span>
                    <h4 id="statsApprovedVal" class="text-lg font-extrabold text-slate-900 mt-0.5">Rs. 0.00</h4>
                </div>
            </div>
            <span class="text-xs text-slate-400 font-medium mt-4" id="statsApprovedCount">0 Notes approved</span>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-3 border border-slate-100 rounded-2xl shadow-sm">
        <div class="flex flex-wrap items-center gap-1.5 w-full sm:w-auto" id="statusFilterTabs">
            <button onclick="setFilterStatus('all')" class="status-tab px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold border border-indigo-600 shadow-sm transition-all" data-status="all">
                All Notes
            </button>
            <button onclick="setFilterStatus('0')" class="status-tab px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100 rounded-xl text-xs font-bold transition-all" data-status="0">
                Pending
            </button>
            <button onclick="setFilterStatus('1')" class="status-tab px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100 rounded-xl text-xs font-bold transition-all" data-status="1">
                Approved
            </button>
            <button onclick="setFilterStatus('3')" class="status-tab px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100 rounded-xl text-xs font-bold transition-all" data-status="3">
                Used
            </button>
            <button onclick="setFilterStatus('2')" class="status-tab px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-100 rounded-xl text-xs font-bold transition-all" data-status="2">
                Rejected
            </button>
        </div>
        <div class="relative w-full sm:w-64">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" id="returnSearch" oninput="filterReturns()" placeholder="Search credit note number..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
        </div>
    </div>

    <!-- Returns List -->
    <div id="returnsList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Injected dynamically -->
        <div class="col-span-full py-16 flex flex-col items-center justify-center gap-3">
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-indigo-600 border-t-transparent"></div>
            <p class="text-xs font-semibold text-slate-400">Loading returns history...</p>
        </div>
    </div>
</div>

<!-- CREDIT NOTE DETAIL MODAL -->
<div id="detailModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeDetailModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full w-full">
            <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="detailNoteNumber">CN-00000000-0000</h3>
                    <p class="text-xs text-slate-500 mt-0.5" id="detailNoteDate">Date: 2026-06-16</p>
                </div>
                <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 rounded-lg">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                <!-- Metadata Info Cards -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100 text-xs">
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Status</span>
                        <span id="detailStatusBadge" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border">Pending</span>
                    </div>
                    <div class="bg-slate-50/50 p-3 rounded-xl border border-slate-100 text-xs">
                        <span class="text-slate-400 font-bold uppercase tracking-wider block mb-1">Return Type</span>
                        <span id="detailTypeLabel" class="font-bold text-slate-700">Physical Stock</span>
                    </div>
                </div>

                <!-- Rejection Reason if applicable -->
                <div id="detailRejectBox" class="hidden p-4 bg-red-50 border border-red-100 rounded-xl text-sm">
                    <div class="flex gap-2">
                        <i class="bi bi-x-circle-fill text-red-600 text-base"></i>
                        <div>
                            <h4 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-1">Return Rejected</h4>
                            <p id="detailRejectReason" class="text-xs text-red-700"></p>
                        </div>
                    </div>
                </div>

                <!-- Products Returned -->
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Returned Products</h4>
                    <div id="detailProductsList" class="divide-y divide-slate-100 border border-slate-100 rounded-xl p-3 bg-white">
                        <!-- Injected -->
                    </div>
                </div>

                <!-- Note Reason -->
                <div id="detailReasonContainer">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Overall Return Reason</h4>
                    <p id="detailReason" class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 italic"></p>
                </div>

                <!-- Audit Trail / Timeline -->
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Audit Trail / History</h4>
                    <div class="relative border-l-2 border-slate-100 pl-4 space-y-4 ml-1" id="detailHistoryTimeline">
                        <!-- Injected -->
                    </div>
                </div>
            </div>

            <!-- Footer Details -->
            <div class="bg-slate-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                <div>
                    <span class="text-xs text-slate-500 font-medium">Total Return Credit</span>
                    <h4 class="text-xl font-black text-indigo-600" id="detailTotalVal">Rs. 0.00</h4>
                </div>
                <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CREATE RETURN MODAL -->
<div id="createModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeCreateModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full w-full">
            <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">New Bakery Return Request</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Submit returned stock for bakery credit notes.</p>
                </div>
                <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 p-1.5 hover:bg-gray-100 rounded-lg">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <form id="createReturnForm" onsubmit="submitBakeryReturn(event)" class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                <!-- Return Type Selection -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Return Type *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex flex-col items-center gap-2 p-4 rounded-xl border border-indigo-100 bg-indigo-50/20 hover:bg-white cursor-pointer transition-all" id="typeLabel1">
                            <input type="radio" name="note_type" value="1" checked onchange="toggleReturnType(1)" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                            <span class="text-sm font-bold text-indigo-900 mt-1">🚚 Physical Stock</span>
                            <span class="text-[10px] text-slate-400 text-center">Unsold items left over in your truck inventory.</span>
                        </label>
                        <label class="flex flex-col items-center gap-2 p-4 rounded-xl border border-slate-100 bg-slate-50/20 hover:bg-white cursor-pointer transition-all" id="typeLabel2">
                            <input type="radio" name="note_type" value="2" onchange="toggleReturnType(2)" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500">
                            <span class="text-sm font-bold text-slate-700 mt-1">🔄 Customer Return</span>
                            <span class="text-[10px] text-slate-400 text-center">Defective or expired goods returned by retailers.</span>
                        </label>
                    </div>
                </div>

                <!-- Overall Reason -->
                <div>
                    <label for="createReason" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Overall Return Reason</label>
                    <textarea id="createReason" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" placeholder="Provide reason for this return..."></textarea>
                </div>

                <!-- Products Checklist -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Available Returnable Stock</label>
                        <span class="text-[10px] font-extrabold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded" id="availableItemsCount">0 Items Available</span>
                    </div>
                    
                    <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm">
                        <table class="min-w-full divide-y divide-slate-100 text-xs">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-500 uppercase text-[9px] w-10">Select</th>
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-500 uppercase text-[9px]">Product Item</th>
                                    <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase text-[9px] w-28">Available Stock</th>
                                    <th class="px-4 py-2.5 text-center font-bold text-slate-500 uppercase text-[9px] w-32">Return Quantity</th>
                                    <th class="px-4 py-2.5 text-left font-bold text-slate-500 uppercase text-[9px] w-48">Item Reason</th>
                                </tr>
                            </thead>
                            <tbody id="availableProductsTable" class="divide-y divide-slate-100 bg-white">
                                <!-- Loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>

            <div class="bg-slate-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                <div>
                    <span class="text-xs text-slate-500 font-medium">Estimated Return Value</span>
                    <h4 class="text-xl font-black text-emerald-600" id="estimatedTotalVal">Rs. 0.00</h4>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-sm font-semibold rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="button" onclick="submitBakeryReturn()" id="createSubmitBtn" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                        Submit Return
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let returnsHistory = [];
    let activeFilterStatus = 'all';
    let availablePhysicalStock = [];
    let availableReturnStock = [];
    let activeReturnType = 1;

    $(document).ready(function() {
        loadReturnsHistory();
        loadAvailableStock();
    });

    function loadReturnsHistory() {
        $.getJSON('/agent-panel/api/bakery-returns')
            .done(function(response) {
                if (response.status && response.data) {
                    returnsHistory = response.data;
                    updateStats();
                    renderReturnsList();
                }
            });
    }

    function loadAvailableStock() {
        $.getJSON('/agent-panel/api/bakery-returns/available')
            .done(function(response) {
                if (response.status && response.data) {
                    availablePhysicalStock = response.data.physical || [];
                    availableReturnStock = response.data.returns || [];
                }
            });
    }

    function updateStats() {
        let totalVal = 0;
        let pendingVal = 0;
        let approvedVal = 0;
        let pendingCount = 0;
        let approvedCount = 0;

        returnsHistory.forEach(note => {
            const val = parseFloat(note.total_amount) || 0;
            totalVal += val;

            if (note.status == 0) {
                pendingCount++;
                pendingVal += val;
            } else if (note.status == 1) {
                approvedCount++;
                approvedVal += val;
            }
        });

        $('#statsTotalVal').text('Rs. ' + totalVal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $('#statsNotesCount').text(`${returnsHistory.length} Total Return Notes`);
        
        $('#statsPendingVal').text('Rs. ' + pendingVal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $('#statsPendingCount').text(`${pendingCount} Notes pending`);

        $('#statsApprovedVal').text('Rs. ' + approvedVal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
        $('#statsApprovedCount').text(`${approvedCount} Notes approved`);
    }

    function setFilterStatus(status) {
        activeFilterStatus = status;
        $('#statusFilterTabs button').removeClass('bg-indigo-600 text-white border-indigo-600 shadow-sm')
                                    .addClass('bg-slate-50 hover:bg-slate-100 text-slate-600 border-slate-100');
        $(`#statusFilterTabs button[data-status="${status}"]`).addClass('bg-indigo-600 text-white border-indigo-600 shadow-sm')
                                                             .removeClass('bg-slate-50 hover:bg-slate-100 text-slate-600 border-slate-100');
        filterReturns();
    }

    function filterReturns() {
        const searchVal = $('#returnSearch').val().toLowerCase().trim();
        
        $('.return-card-item').each(function() {
            const no = $(this).data('number').toLowerCase();
            const status = $(this).data('status').toString();

            const matchesSearch = no.includes(searchVal);
            const matchesStatus = activeFilterStatus === 'all' || status === activeFilterStatus;

            if (matchesSearch && matchesStatus) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        });
    }

    function getStatusBadge(status) {
        switch (status) {
            case 0: return '<span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-100 text-[10px] font-bold rounded-full uppercase tracking-wider">Pending</span>';
            case 1: return '<span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 text-[10px] font-bold rounded-full uppercase tracking-wider">Approved</span>';
            case 2: return '<span class="px-2.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-100 text-[10px] font-bold rounded-full uppercase tracking-wider">Rejected</span>';
            case 3: return '<span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 border border-indigo-100 text-[10px] font-bold rounded-full uppercase tracking-wider">Used</span>';
            default: return '<span class="px-2.5 py-0.5 bg-slate-50 text-slate-700 border border-slate-100 text-[10px] font-bold rounded-full uppercase tracking-wider">Unknown</span>';
        }
    }

    function renderReturnsList() {
        const container = $('#returnsList');
        container.empty();

        if (returnsHistory.length === 0) {
            container.html(`
                <div class="col-span-full bg-white border border-gray-100 rounded-2xl p-16 text-center text-gray-400 shadow-sm">
                    <i class="bi bi-arrow-left-right text-4xl text-slate-300"></i>
                    <p class="mt-4 font-bold text-gray-700">No returns history found</p>
                    <p class="text-xs mt-1">Submit your first return to see records here.</p>
                </div>
            `);
            return;
        }

        returnsHistory.forEach(note => {
            const statusBadge = getStatusBadge(note.status);
            const total = parseFloat(note.total_amount) || 0;
            const typeLabel = note.note_type === 1 ? '🚚 Physical Stock' : '🔄 Customer Return';
            const typeColor = note.note_type === 1 ? 'border-blue-500' : 'border-amber-500';

            const card = `
                <div class="return-card-item bg-white border-l-[6px] ${typeColor} border-y border-r border-slate-100/80 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 flex flex-col justify-between"
                     data-id="${note.id}"
                     data-number="${note.credit_note_number}"
                     data-status="${note.status}">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-800 font-mono">${note.credit_note_number}</h4>
                                <span class="text-[10px] text-slate-400 font-medium">${new Date(note.credit_note_date).toLocaleDateString('en-GB', { timeZone: 'Asia/Colombo' })}</span>
                            </div>
                            ${statusBadge}
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-3 border-t border-slate-50">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">${typeLabel}</span>
                            <span class="text-xs font-semibold text-slate-500">${note.products ? note.products.length : 0} Items</span>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-between items-end">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Credit</span>
                            <span class="text-base font-black text-slate-900">Rs. ${total.toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                        </div>
                        <button onclick="viewReturnDetails(${note.id})" class="px-3.5 py-1.5 bg-slate-50 hover:bg-indigo-600 hover:text-white text-indigo-600 text-xs font-bold rounded-xl border border-indigo-50 hover:border-indigo-600 transition-all flex items-center gap-1 cursor-pointer">
                            <i class="bi bi-eye"></i> View Details
                        </button>
                    </div>
                </div>
            `;
            container.append(card);
        });
    }

    function viewReturnDetails(noteId) {
        const note = returnsHistory.find(n => n.id === noteId);
        if (!note) return;

        $('#detailNoteNumber').text(note.credit_note_number);
        $('#detailNoteDate').text('Date: ' + new Date(note.credit_note_date).toLocaleDateString('en-GB', { timeZone: 'Asia/Colombo' }));

        // Status Badge
        const sb = $('#detailStatusBadge');
        sb.html(getStatusLabelText(note.status));
        sb.removeClass().addClass(`px-2 py-0.5 rounded text-[10px] font-bold uppercase border ${getStatusLabelClass(note.status)}`);

        // Note Type
        $('#detailTypeLabel').text(note.note_type === 1 ? 'Physical Stock Return' : 'Customer Return');

        // Rejection Info
        if (note.status === 2 && note.reject_reason) {
            $('#detailRejectReason').text(note.reject_reason);
            $('#detailRejectBox').removeClass('hidden');
        } else {
            $('#detailRejectBox').addClass('hidden');
        }

        // Products List
        const list = $('#detailProductsList');
        list.empty();
        if (note.products && note.products.length > 0) {
            note.products.forEach(p => {
                const sub = parseFloat(p.total) || 0;
                list.append(`
                    <div class="py-2.5 flex justify-between items-center text-xs">
                        <div>
                            <p class="font-bold text-slate-700">${p.product ? p.product.product_name : 'Product'}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">${p.qty} Units × Rs. ${parseFloat(p.distributor_price).toLocaleString()}</p>
                            ${p.reason ? `<span class="inline-block mt-1 px-1.5 py-0.5 bg-slate-50 text-[9px] text-slate-400 rounded border border-slate-100 font-medium italic">Reason: ${p.reason}</span>` : ''}
                        </div>
                        <span class="font-bold text-slate-800">Rs. ${sub.toLocaleString()}</span>
                    </div>
                `);
            });
        } else {
            list.html('<p class="text-xs text-slate-400 italic py-2">No product details found.</p>');
        }

        // Reason
        if (note.reason) {
            $('#detailReason').text(note.reason);
            $('#detailReasonContainer').removeClass('hidden');
        } else {
            $('#detailReasonContainer').addClass('hidden');
        }

        // Timeline
        const timeline = $('#detailHistoryTimeline');
        timeline.empty();
        if (note.histories && note.histories.length > 0) {
            note.histories.forEach(h => {
                const user = h.creator ? h.creator.user_name : 'System';
                timeline.append(`
                    <div class="relative pl-5 text-xs">
                        <span class="absolute left-[-21px] top-1 w-2.5 h-2.5 rounded-full bg-slate-400 border-2 border-white shadow-sm"></span>
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <span class="font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded text-[10px]">${h.action}</span>
                            <span class="text-[10px] text-slate-400 font-medium">${new Date(h.created_at).toLocaleString('en-GB', { timeZone: 'Asia/Colombo' })}</span>
                        </div>
                        <p class="text-slate-500 text-[10px]">Performed by: <strong>${user}</strong></p>
                    </div>
                `);
            });
        } else {
            timeline.html('<p class="text-xs text-slate-400 italic pl-1">No audit history found.</p>');
        }

        // Total
        $('#detailTotalVal').text('Rs. ' + (parseFloat(note.total_amount) || 0).toLocaleString(undefined, { minimumFractionDigits: 2 }));

        // Show Modal
        $('#detailModal').removeClass('hidden');
        document.body.style.overflow = 'hidden';
    }

    function getStatusLabelText(status) {
        if (status === 0) return 'Pending';
        if (status === 1) return 'Approved';
        if (status === 2) return 'Rejected';
        if (status === 3) return 'Used';
        return 'Unknown';
    }

    function getStatusLabelClass(status) {
        if (status === 0) return 'bg-amber-50 text-amber-700 border-amber-100';
        if (status === 1) return 'bg-emerald-50 text-emerald-700 border-emerald-100';
        if (status === 2) return 'bg-rose-50 text-rose-700 border-rose-100';
        if (status === 3) return 'bg-indigo-50 text-indigo-700 border-indigo-100';
        return 'bg-slate-50 text-slate-600 border-slate-100';
    }

    function closeDetailModal() {
        $('#detailModal').addClass('hidden');
        document.body.style.overflow = '';
    }

    function openCreateReturnModal() {
        activeReturnType = 1;
        $('input[name="note_type"][value="1"]').prop('checked', true);
        toggleReturnType(1);
        $('#createReason').val('');
        $('#estimatedTotalVal').text('Rs. 0.00');
        
        $('#createModal').removeClass('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCreateModal() {
        $('#createModal').addClass('hidden');
        document.body.style.overflow = '';
    }

    function toggleReturnType(type) {
        activeReturnType = type;
        $('.grid-cols-2 label').removeClass('border-indigo-100 bg-indigo-50/20').addClass('border-slate-100 bg-slate-50/20');
        $(`#typeLabel${type}`).addClass('border-indigo-100 bg-indigo-50/20').removeClass('border-slate-100 bg-slate-50/20');
        $(`#typeLabel${type} span:first-of-type`).removeClass('text-slate-700').addClass('text-indigo-900');
        $(`.grid-cols-2 label:not(#typeLabel${type}) span:first-of-type`).removeClass('text-indigo-900').addClass('text-slate-700');

        renderAvailableProducts();
    }

    function renderAvailableProducts() {
        const tbody = $('#availableProductsTable');
        tbody.empty();

        const list = activeReturnType === 1 ? availablePhysicalStock : availableReturnStock;
        $('#availableItemsCount').text(`${list.length} Items Available`);

        if (list.length === 0) {
            tbody.html('<tr><td colspan="5" class="px-4 py-8 text-center text-slate-400 italic">No available stock items found for this return type.</td></tr>');
            return;
        }

        list.forEach(p => {
            const row = `
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-3 text-center">
                        <input type="checkbox" name="selected_items[]" value="${p.id}" onchange="updateEstimatedTotal()" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 select-item-check">
                    </td>
                    <td class="px-4 py-3 font-semibold text-slate-800">
                        ${p.product_name}
                        ${p.reason ? `<p class="text-[10px] text-slate-400 italic mt-0.5">Cust Reason: ${p.reason}</p>` : ''}
                    </td>
                    <td class="px-4 py-3 text-center font-bold text-slate-500">${p.quantity}</td>
                    <td class="px-4 py-3 text-center">
                        <input type="number" id="returnQty_${p.id}" max="${p.quantity}" min="0.001" step="any" value="${p.quantity}" oninput="updateEstimatedTotal()" class="w-20 px-2 py-1 border border-slate-200 rounded text-center font-bold text-slate-800 outline-none">
                    </td>
                    <td class="px-4 py-3">
                        <input type="text" id="returnReason_${p.id}" placeholder="Optional reason..." class="w-full px-2.5 py-1 border border-slate-200 rounded text-slate-700 outline-none">
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        updateEstimatedTotal();
    }

    function updateEstimatedTotal() {
        let estTotal = 0;
        const list = activeReturnType === 1 ? availablePhysicalStock : availableReturnStock;

        $('.select-item-check:checked').each(function() {
            const id = parseInt($(this).val());
            const p = list.find(item => item.id === id);
            if (p) {
                const qty = parseFloat($(`#returnQty_${id}`).val()) || 0;
                estTotal += qty * p.distributor_price;
            }
        });

        $('#estimatedTotalVal').text('Rs. ' + estTotal.toLocaleString(undefined, { minimumFractionDigits: 2 }));
    }

    function submitBakeryReturn(event) {
        if (event) event.preventDefault();

        const selectedChecks = $('.select-item-check:checked');
        if (selectedChecks.length === 0) {
            toastr.error('Please select at least one product item to return.');
            return;
        }

        const items = [];
        const list = activeReturnType === 1 ? availablePhysicalStock : availableReturnStock;
        let validationError = null;

        selectedChecks.each(function() {
            const id = parseInt($(this).val());
            const p = list.find(item => item.id === id);
            if (p) {
                const qty = parseFloat($(`#returnQty_${id}`).val()) || 0;
                if (qty <= 0) {
                    validationError = `Return quantity for ${p.product_name} must be greater than zero.`;
                    return false;
                }
                if (qty > p.quantity) {
                    validationError = `Return quantity for ${p.product_name} cannot exceed available quantity (${p.quantity}).`;
                    return false;
                }

                items.push({
                    id: p.id,
                    product_id: p.product_id,
                    quantity: qty,
                    distributor_price: p.distributor_price,
                    wholesale_price: p.wholesale_price,
                    retail_price: p.retail_price,
                    stm_stock_id: p.stm_stock_id,
                    reason: $(`#returnReason_${id}`).val().trim() || null
                });
            }
        });

        if (validationError) {
            toastr.error(validationError);
            return;
        }

        Swal.fire({
            title: 'Submit Bakery Return?',
            text: "Are you sure you want to submit this return request to the bakery?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Submit Return',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const submitBtn = $('#createSubmitBtn');
                submitBtn.prop('disabled', true).text('Submitting...');

                const payload = {
                    note_type: activeReturnType,
                    reason: $('#createReason').val().trim() || null,
                    items: items,
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '/agent-panel/api/bakery-returns/create',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(payload),
                    success: function(response) {
                        submitBtn.prop('disabled', false).text('Submit Return');
                        if (response.status) {
                            Swal.fire(
                                'Return Submitted!',
                                'Your bakery return request has been submitted and awaits approval.',
                                'success'
                            ).then(() => {
                                closeCreateModal();
                                loadReturnsHistory();
                                loadAvailableStock();
                            });
                        } else {
                            toastr.error(response.message || 'Failed to submit return request.');
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).text('Submit Return');
                        const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error submitting return.';
                        toastr.error(msg);
                    }
                });
            }
        });
    }

    // Close on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetailModal();
            closeCreateModal();
        }
    });
</script>
@endsection
