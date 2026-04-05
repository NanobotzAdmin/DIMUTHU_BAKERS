@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#EDEFF5]" id="settlement-list-app">
        <!-- Header -->
        <!-- Header -->
        <div class="bg-white border-b border-gray-100 px-4 sm:px-8 py-6 sm:py-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between max-w-[1800px] mx-auto">
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-[#F59E0B] rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm text-white">
                            <i class="bi bi-briefcase text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-[#0F172A] text-2xl font-bold tracking-tight">Agent Targets</h1>
                            <p class="text-[#64748B] text-sm">Review and approve monthly agent targets</p>
                        </div>
                    </div>
                    <button onclick="toggleCreateModal(true)"
                        class="inline-flex items-center gap-2 bg-[#E17100] hover:bg-[#C16100] text-white px-5 py-2.5 rounded-xl transition-all shadow-md font-bold text-sm">
                        <i class="bi bi-plus-lg"></i>
                        <span>Create Target</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Create Settlement Modal -->
        <div id="create-modal" class="fixed inset-0 z-[100] hidden">
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="toggleCreateModal(false)"></div>
            <div class="absolute inset-y-0 right-0 w-full max-w-md bg-white shadow-2xl flex flex-col">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Create Settlement</h2>
                        <p class="text-sm text-gray-500">Add a new settlement record manually</p>
                    </div>
                    <button onclick="toggleCreateModal(false)" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6">
                    <form id="create-settlement-form" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Select Agent *</label>
                            <select name="agent_id" id="create-agent-id" onchange="loadAgentLoads(this.value)"
                                class="w-full border-gray-200 rounded-lg focus:ring-[#E17100] focus:border-[#E17100]" required>
                                <option value="">Choose an agent...</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent['id'] }}">{{ $agent['agentName'] }} ({{ $agent['agentCode'] }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Daily Load *</label>
                            <select name="daily_load_id" id="create-load-id"
                                class="w-full border-gray-200 rounded-lg focus:ring-[#E17100] focus:border-[#E17100]" required>
                                <option value="">Select agent first...</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Settlement Date *</label>
                            <input type="date" name="settlement_date" value="{{ date('Y-m-d') }}"
                                class="w-full border-gray-200 rounded-lg focus:ring-[#E17100] focus:border-[#E17100]" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Total Sales</label>
                                <input type="number" step="0.01" name="total_sales" value="0"
                                    class="w-full border-gray-200 rounded-lg focus:ring-[#E17100] focus:border-[#E17100]" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Cash Sales</label>
                                <input type="number" step="0.01" name="cash_sales" value="0"
                                    class="w-full border-gray-200 rounded-lg focus:ring-[#E17100] focus:border-[#E17100]" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Credit Sales</label>
                                <input type="number" step="0.01" name="credit_sales" value="0"
                                    class="w-full border-gray-200 rounded-lg focus:ring-[#E17100] focus:border-[#E17100]" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Cheque Sales</label>
                                <input type="number" step="0.01" name="cheque_sales" value="0"
                                    class="w-full border-gray-200 rounded-lg focus:ring-[#E17100] focus:border-[#E17100]" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Commission Earned</label>
                            <input type="number" step="0.01" name="commission_earned" value="0"
                                class="w-full border-gray-200 rounded-lg focus:ring-[#E17100] focus:border-[#E17100]" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" rows="3"
                                class="w-full border-gray-200 rounded-lg focus:ring-[#E17100] focus:border-[#E17100]" 
                                placeholder="Any additional information..."></textarea>
                        </div>
                    </form>
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50 flex gap-3">
                    <button onclick="toggleCreateModal(false)" 
                        class="flex-1 px-4 py-2 text-gray-700 font-semibold hover:bg-gray-100 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button onclick="submitCreateForm()"
                        class="flex-1 bg-[#E17100] hover:bg-[#C16100] text-white px-4 py-2 rounded-xl font-bold shadow-lg transition-all">
                        Save Settlement
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-8 max-w-[1800px] mx-auto space-y-6">
            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Settlements -->
                <div class="p-5 bg-white border border-blue-100 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="bi bi-tablet"></i>
                        </div>
                        <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Total</span>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-gray-900 mb-0.5" id="stat-total">0</p>
                        <p class="text-gray-500 text-xs font-medium">Settlements</p>
                    </div>
                </div>

                <!-- Needs Review -->
                <div class="p-5 bg-[#FFFBEB] border border-yellow-100 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600">
                            <i class="bi bi-clock"></i>
                        </div>
                        <span class="text-yellow-700 text-[10px] font-bold uppercase tracking-widest">Pending</span>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-yellow-900 mb-0.5" id="stat-pending">0</p>
                        <p class="text-yellow-700 text-xs font-medium">Needs Review</p>
                    </div>
                </div>

                <!-- Total Sales -->
                <div class="p-5 bg-[#F0FDF4] border border-green-100 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <span class="text-green-700 text-[10px] font-bold uppercase tracking-widest">Total Sales</span>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-green-900 mb-0.5" id="stat-sales">Rs. 0</p>
                        <p class="text-green-700 text-xs font-medium">Gross Revenue</p>
                    </div>
                </div>

                <!-- Variances -->
                <div id="card-variance" class="p-5 bg-[#F0FDF4] border border-green-100 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <div id="icon-variance-bg" class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                            <i id="icon-variance" class="bi bi-check-circle"></i>
                        </div>
                        <span class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">Variances</span>
                    </div>
                    <div>
                        <p id="stat-variance-count" class="text-3xl font-black text-green-900 mb-0.5">0</p>
                        <p id="stat-variance-amt" class="text-xs font-bold text-green-700 tracking-tight">+Rs. 0</p>
                    </div>
                </div>
            </div>

            <!-- Secondary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Total Cash Collected</p>
                    <p class="text-2xl font-black text-gray-900" id="stat-cash-collected">Rs. 0</p>
                </div>
                <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Total Due to Bakery</p>
                    <p class="text-2xl font-black text-gray-900" id="stat-due-bakery">Rs. 0</p>
                </div>
                <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Total Commission</p>
                    <p class="text-2xl font-black text-gray-900" id="stat-commission">Rs. 0</p>
                </div>
            </div>

            <!-- Status Counts Row -->
            <div class="p-4 bg-white border border-gray-100 rounded-2xl shadow-sm flex flex-wrap gap-6 items-center justify-center sm:justify-start">
                <div class="flex items-center gap-2 text-sm text-gray-600 font-semibold">
                    <i class="bi bi-clock-history text-yellow-600"></i> Pending: <span id="summ-pending" class="text-gray-900">0</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600 font-semibold">
                    <i class="bi bi-eye text-blue-600"></i> Reviewed: <span id="summ-reviewed" class="text-gray-900">0</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600 font-semibold">
                    <i class="bi bi-check-circle-fill text-green-600"></i> Approved: <span id="summ-approved" class="text-gray-900">0</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600 font-semibold">
                    <i class="bi bi-exclamation-octagon-fill text-red-600"></i> Disputed: <span id="summ-disputed" class="text-gray-900">0</span>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <!-- Search -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Search</label>
                        <div class="relative group">
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#E17100] transition-colors"></i>
                            <input type="text" id="filter-search" onkeyup="renderList()"
                                placeholder="Search by settlement #, agent name..."
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#E17100]/20 focus:border-[#E17100] transition-all text-sm outline-none">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                        <select id="filter-status" onchange="renderList()"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#E17100]/20 focus:border-[#E17100] transition-all text-sm outline-none appearance-none cursor-pointer text-gray-700">
                            <option value="all">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="reviewed">Reviewed</option>
                            <option value="approved">Approved</option>
                            <option value="disputed">Disputed</option>
                        </select>
                    </div>

                    <!-- Agent Filter -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Agent</label>
                        <select id="filter-agent" onchange="renderList()"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#E17100]/20 focus:border-[#E17100] transition-all text-sm outline-none appearance-none cursor-pointer text-gray-700">
                            <option value="all">All Agents</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent['id'] }}">{{ $agent['agentName'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Date Range</label>
                        <select id="filter-date" onchange="renderList()"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#E17100]/20 focus:border-[#E17100] transition-all text-sm outline-none appearance-none cursor-pointer text-gray-700">
                            <option value="today">Today</option>
                            <option value="week">Last 7 Days</option>
                            <option value="month" selected>Last 30 Days</option>
                            <option value="year">Last Year</option>
                            <option value="all">All Time</option>
                        </select>
                    </div>
                </div>

                <!-- Variance Toggle -->
                <div class="mt-4 flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="filter-variance" onchange="renderList()" class="sr-only peer">
                        <div class="w-5 h-5 border-2 border-gray-300 rounded peer-checked:bg-[#E17100] peer-checked:border-[#E17100] transition-all relative">
                            <i class="bi bi-check absolute inset-0 text-white opacity-0 peer-checked:opacity-100 flex items-center justify-center text-sm"></i>
                        </div>
                        <span class="ml-3 text-sm font-semibold text-gray-600">Show only settlements with cash variance</span>
                    </label>
                </div>
            </div>

            <!-- Active Filters Bar -->
            <div id="active-filters-bar" class="hidden mt-4 pt-4 border-t border-gray-200 flex flex-wrap gap-2 items-center">
                <span class="text-xs text-gray-500 font-medium">Active Filters:</span>
                <!-- Injected Badges -->
                <button onclick="resetFilters()" class="text-xs text-gray-500 hover:text-gray-700 underline ml-2">Clear
                    All</button>
            </div>
        </div>

            <!-- Settlement List Grid -->
            <div id="settlement-list" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Injected JS -->
            </div>
            
            <!-- Empty State -->
            <div id="empty-state" class="hidden py-24 bg-white border border-gray-100 rounded-[32px] shadow-sm flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-[32px] flex items-center justify-center text-gray-300 mb-6">
                    <i class="bi bi-file-earmark-x text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Settlements Found</h3>
                <p class="text-gray-500 max-w-xs mx-auto">Try adjusting your search criteria or clear the filters to see all results.</p>
            </div>
        </div>
    </div>
    </div>

    <script>
        const serverAgents = @json($agents ?? []);
        const serverSettlements = @json($settlements ?? []);

        const state = {
            agents: serverAgents,
            settlements: serverSettlements
        };

        document.addEventListener('DOMContentLoaded', () => {
            renderList();
        });

        function renderList() {
            // Filters
            const search = document.getElementById('filter-search').value.toLowerCase();
            const status = document.getElementById('filter-status').value;
            const agentId = document.getElementById('filter-agent').value;
            const dateRange = document.getElementById('filter-date').value;
            const onlyVar = document.getElementById('filter-variance').checked;

            // Date Logic
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            let minDate = new Date(0); // Epoch

            if (dateRange === 'today') {
                minDate = today;
            } else if (dateRange === 'week') {
                minDate = new Date(today);
                minDate.setDate(today.getDate() - 7);
            } else if (dateRange === 'month') {
                minDate = new Date(today);
                minDate.setDate(today.getDate() - 30);
            }

            const filtered = state.settlements.filter(s => {
                const matchesSearch = s.settlementNumber.toLowerCase().includes(search) ||
                    state.agents.find(a => a.id === s.agentId)?.agentName.toLowerCase().includes(search);
                const matchesStatus = status === 'all' || s.status === status;
                const matchesAgent = agentId === 'all' || s.agentId === agentId;
                const matchesDate = new Date(s.settlementDate) >= minDate;
                const matchesVar = !onlyVar || Math.abs(s.cashVariance) > 0;

                return matchesSearch && matchesStatus && matchesAgent && matchesDate && matchesVar;
            });

            updateStats(filtered);
            updateActiveFiltersBar(search, status, agentId, dateRange, onlyVar);

            const container = document.getElementById('settlement-list');
            const empty = document.getElementById('empty-state');

            if (filtered.length === 0) {
                container.innerHTML = '';
                empty.classList.remove('hidden');
                return;
            }

            empty.classList.add('hidden');
            container.innerHTML = filtered.map(s => buildCard(s)).join('');
        }

        function buildCard(s) {
            const agent = state.agents.find(a => a.id === s.agentId);
            const hasVar = Math.abs(s.cashVariance) > 0;
            const stlDate = new Date(s.settlementDate).toLocaleDateString();

            return `
                <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100 hover:shadow-md transition-all group">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-[#E17100] group-hover:bg-[#E17100]/10 transition-colors">
                                <i class="bi bi-file-earmark-text text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-0.5">${s.settlementNumber}</h3>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-500">${agent ? agent.agentName : 'Unknown Agent'}</span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span class="text-sm font-medium text-gray-500">${stlDate}</span>
                                </div>
                            </div>
                        </div>
                        ${getStatusBadge(s.status)}
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="p-4 bg-gray-50 rounded-2xl">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Sales</p>
                            <p class="text-lg font-black text-gray-900">${formatCurrency(s.totalSales)}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Actual Cash</p>
                            <p class="text-lg font-black text-gray-900">${formatCurrency(s.actualCash)}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                        <div class="flex items-center gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Variance</p>
                                ${getVarianceBadge(s.cashVariance)}
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="/agent-distribution/settlements/${s.id}" 
                                class="w-10 h-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center hover:bg-[#E17100]/10 hover:text-[#E17100] transition-all" 
                                title="View Details">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            ${getActionButtons(s)}
                        </div>
                    </div>
                    
                    ${hasVar && s.varianceNotes ? `
                        <div class="mt-4 p-3 bg-red-50 rounded-xl border border-red-100 flex gap-3">
                            <i class="bi bi-info-circle text-red-400 mt-0.5"></i>
                            <p class="text-xs text-red-700 leading-relaxed font-medium">
                                <span class="font-bold">Variance Note:</span> ${s.varianceNotes}
                            </p>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        function getActionButtons(s) {
            let btns = '';
            // Only show quick actions if pending
            if (s.status === 'pending') {
                btns += `
                    <button onclick="handleAction('${s.id}', 'approved')" 
                        class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center hover:bg-green-100 transition-all"
                        title="Quick Approve">
                        <i class="bi bi-check-lg"></i>
                    </button>
                    <button onclick="handleAction('${s.id}', 'disputed')" 
                        class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center hover:bg-red-100 transition-all"
                        title="Dispute">
                        <i class="bi bi-x-lg"></i>
                    </button>
                `;
            }
            return btns;
        }

        function handleAction(id, type) {
            const idx = state.settlements.findIndex(s => s.id === id);
            if (idx === -1) return;

            if (type === 'disputed') {
                Swal.fire({
                    title: 'Dispute Settlement',
                    input: 'textarea',
                    inputLabel: 'Reason for dispute',
                    inputPlaceholder: 'Enter the reason here...',
                    showCancelButton: true,
                    confirmButtonText: 'Submit Dispute',
                    confirmButtonColor: '#d33',
                    showLoaderOnConfirm: true,
                    preConfirm: (reason) => {
                        if (!reason) {
                            Swal.showValidationMessage('Please enter a reason');
                        }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        updateStatus(idx, id, type, result.value);
                    }
                });
            } else {
                if (type === 'approved') {
                    Swal.fire({
                        title: 'Approve Settlement?',
                        text: "You are about to approve this settlement.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        confirmButtonText: 'Yes, Approve it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateStatus(idx, id, type);
                        }
                    });
                } else {
                    updateStatus(idx, id, type);
                }
            }
        }

        function updateStatus(idx, id, type, notes = '') {
            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/agent-distribution/settlements/update-status',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    status: type,
                    notes: notes
                },
                success: function(response) {
                    if (response.status) {
                        state.settlements[idx].status = type;
                        if (notes) {
                            state.settlements[idx].notes = (state.settlements[idx].notes ? state.settlements[idx].notes + ' | ' : '') + notes;
                        }
                        renderList();
                        
                        const msg = type === 'reviewed' ? 'Settlement marked as reviewed' : 
                                  type === 'approved' ? 'Settlement approved successfully' : 
                                  'Settlement marked as disputed';
                        
                        Swal.fire('Success!', msg, 'success');
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    let msg = 'Failed to update status';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error!', msg, 'error');
                }
            });
        }

        function toggleCreateModal(show) {
            document.getElementById('create-modal').classList.toggle('hidden', !show);
            if (!show) {
                document.getElementById('create-settlement-form').reset();
                document.getElementById('create-load-id').innerHTML = '<option value="">Select agent first...</option>';
            }
        }

        function loadAgentLoads(agentId) {
            const loadSelect = document.getElementById('create-load-id');
            if (!agentId) {
                loadSelect.innerHTML = '<option value="">Select agent first...</option>';
                return;
            }

            loadSelect.innerHTML = '<option value="">Loading loads...</option>';
            loadSelect.disabled = true;

            fetch(`/agent-distribution/settlements/agent-loads/${agentId}`)
                .then(res => res.json())
                .then(data => {
                    loadSelect.disabled = false;
                    if (data.status && data.data.length > 0) {
                        let html = '<option value="">Select a daily load...</option>';
                        data.data.forEach(load => {
                            html += `<option value="${load.id}">${load.load_number} (${load.load_date})</option>`;
                        });
                        loadSelect.innerHTML = html;
                    } else {
                        loadSelect.innerHTML = '<option value="">No loads found for this agent</option>';
                    }
                })
                .catch(err => {
                    loadSelect.disabled = false;
                    loadSelect.innerHTML = '<option value="">Error loading loads</option>';
                    console.error(err);
                });
        }

        function submitCreateForm() {
            const form = document.getElementById('create-settlement-form');
            const $form = $(form);

            // Basic validation
            if (!document.getElementById('create-agent-id').value || !document.getElementById('create-load-id').value) {
                Swal.fire('Error!', 'Please select agent and daily load', 'error');
                return;
            }

            Swal.fire({
                title: 'Creating Settlement...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('settlements.store') }}",
                type: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    if (response.status) {
                        Swal.fire('Success!', response.message, 'success');
                        toggleCreateModal(false);
                        window.location.reload(); 
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    let msg = 'Failed to create settlement';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error!', msg, 'error');
                }
            });
        }

        function updateStats(filtered) {
            const total = filtered.length;
            const pending = filtered.filter(s => s.status === 'pending').length;
            const sales = filtered.reduce((sum, s) => sum + Number(s.totalSales), 0);

            const varCount = filtered.filter(s => Math.abs(s.cashVariance) > 0).length;
            const varAmt = filtered.reduce((sum, s) => sum + Number(s.cashVariance), 0);

            document.getElementById('stat-total').textContent = total;
            document.getElementById('stat-pending').textContent = pending;
            document.getElementById('stat-sales').textContent = formatCurrency(sales);

            const varEl = document.getElementById('card-variance');
            const varIcon = document.getElementById('icon-variance');
            const varIconBg = document.getElementById('icon-variance-bg');
            const varCountEl = document.getElementById('stat-variance-count');
            const varAmtEl = document.getElementById('stat-variance-amt');

            varCountEl.textContent = varCount;
            varAmtEl.textContent = (varAmt >= 0 ? '+' : '') + formatCurrency(varAmt);

            if (varCount > 0) {
                varEl.className = "p-5 bg-red-50 border border-red-100 rounded-2xl shadow-sm flex flex-col justify-between";
                varIcon.className = "bi bi-exclamation-triangle";
                varIconBg.className = "w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600";
                varCountEl.className = "text-3xl font-black text-red-900 mb-0.5";
                varAmtEl.className = "text-xs font-bold text-red-700 tracking-tight";
            } else {
                varEl.className = "p-5 bg-[#F0FDF4] border border-green-100 rounded-2xl shadow-sm flex flex-col justify-between";
                varIcon.className = "bi bi-check-circle";
                varIconBg.className = "w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-600";
                varCountEl.className = "text-3xl font-black text-green-900 mb-0.5";
                varAmtEl.className = "text-xs font-bold text-green-700 tracking-tight";
            }

            document.getElementById('stat-cash-collected').textContent = formatCurrency(filtered.reduce((sum, s) => sum + Number(s.actualCash), 0));
            document.getElementById('stat-due-bakery').textContent = formatCurrency(filtered.reduce((sum, s) => sum + Number(s.amountDueToBakery), 0));
            document.getElementById('stat-commission').textContent = formatCurrency(filtered.reduce((sum, s) => sum + Number(s.commissionEarned), 0));

            document.getElementById('summ-pending').textContent = pending;
            document.getElementById('summ-reviewed').textContent = filtered.filter(s => s.status === 'reviewed').length;
            document.getElementById('summ-approved').textContent = filtered.filter(s => s.status === 'approved').length;
            document.getElementById('summ-disputed').textContent = filtered.filter(s => s.status === 'disputed').length;
        }

        function updateActiveFiltersBar(search, status, agentId, dateRange, onlyVar) {
            const bar = document.getElementById('active-filters-bar');

            if (!search && status === 'all' && agentId === 'all' && dateRange === 'month' && !onlyVar) {
                bar.classList.add('hidden');
                return;
            }

            bar.classList.remove('hidden');
            // Simple rebuilding logic for badges could populate here, simplified for now:
            // bar.innerHTML = ... 
        }

        function resetFilters() {
            document.getElementById('filter-search').value = '';
            document.getElementById('filter-status').value = 'all';
            document.getElementById('filter-agent').value = 'all';
            document.getElementById('filter-date').value = 'month';
            document.getElementById('filter-variance').checked = false;
            renderList();
        }

        function getStatusBadge(status) {
            const badges = {
                'pending': '<span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full uppercase tracking-wider">Pending</span>',
                'reviewed': '<span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wider">Reviewed</span>',
                'approved': '<span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase tracking-wider">Approved</span>',
                'disputed': '<span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full uppercase tracking-wider">Disputed</span>'
            };
            return badges[status] || status;
        }

        function getVarianceBadge(v) {
            const val = Number(v);
            if (val === 0) return '<span class="text-sm font-black text-green-600">None</span>';
            const color = val > 0 ? 'text-green-600' : 'text-red-600';
            return `<span class="text-sm font-black ${color}">${val > 0 ? '+' : ''}${formatCurrency(val)}</span>`;
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'LKR', minimumFractionDigits: 0 }).format(amount).replace('LKR', 'Rs.');
        }

        function showToast(message) {
            const div = document.createElement('div');
            div.className = `fixed top-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-lg z-[80] transition-opacity`;
            div.textContent = message;
            document.body.appendChild(div);
            setTimeout(() => { div.style.opacity = '0'; setTimeout(() => div.remove(), 300); }, 3000);
        }
    </script>
@endsection