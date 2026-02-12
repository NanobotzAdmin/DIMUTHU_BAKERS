@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#EDEFF5]" id="settlement-list-app">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#F59E0B] to-[#D97706] rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="bi bi-bag-check text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Agent Settlements</h1>
                            <p class="text-gray-500 text-xs sm:text-sm">Review and approve daily agent settlements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 max-w-[1800px] mx-auto">
        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total -->
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi bi-files text-blue-600 text-xl"></i>
                    <span class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Total</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 mb-1" id="stat-total">0</p>
                <p class="text-gray-500 text-xs">Settlements</p>
            </div>

            <!-- Pending -->
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi bi-clock-history text-yellow-600 text-xl"></i>
                    <span class="text-yellow-700 text-xs font-semibold uppercase tracking-wider">Pending</span>
                </div>
                <p class="text-2xl font-bold text-yellow-900 mb-1" id="stat-pending">0</p>
                <p class="text-yellow-700 text-xs">Needs Review</p>
            </div>

            <!-- Sales -->
            <div class="p-4 bg-green-50 border border-green-200 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi bi-currency-dollar text-green-600 text-xl"></i>
                    <span class="text-green-700 text-xs font-semibold uppercase tracking-wider">Total Sales</span>
                </div>
                <p class="text-xl font-bold text-green-900 mb-1" id="stat-sales">Rs. 0</p>
                <p class="text-green-700 text-xs">Gross Revenue</p>
            </div>

            <!-- Variances -->
            <div id="card-variance" class="p-4 bg-green-50 border border-green-200 rounded-xl shadow-sm transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <i id="icon-variance" class="bi bi-exclamation-triangle text-green-600 text-xl"></i>
                    <span class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Variances</span>
                </div>
                <p id="stat-variance-count" class="text-2xl font-bold text-green-900 mb-1">0</p>
                <p id="stat-variance-amt" class="text-xs text-green-700">Rs. 0</p>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                <p class="text-gray-500 text-xs mb-1">Total Cash Collected</p>
                <p class="text-lg font-bold text-gray-900" id="stat-cash-collected">Rs. 0</p>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                <p class="text-gray-500 text-xs mb-1">Total Due to Bakery</p>
                <p class="text-lg font-bold text-gray-900" id="stat-due-bakery">Rs. 0</p>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                <p class="text-gray-500 text-xs mb-1">Total Commission</p>
                <p class="text-lg font-bold text-gray-900" id="stat-commission">Rs. 0</p>
            </div>
        </div>

        <!-- Status Summary -->
        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm mb-6 flex flex-wrap gap-4 items-center">
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="bi bi-clock text-yellow-600"></i> Pending: <span id="summ-pending" class="font-bold">0</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="bi bi-eye text-blue-600"></i> Reviewed: <span id="summ-reviewed" class="font-bold">0</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="bi bi-check-circle text-green-600"></i> Approved: <span id="summ-approved"
                    class="font-bold">0</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700">
                <i class="bi bi-exclamation-circle text-red-600"></i> Disputed: <span id="summ-disputed"
                    class="font-bold">0</span>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2 relative">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                    <i class="bi bi-search absolute left-3 top-8 text-gray-400"></i>
                    <input type="text" id="filter-search" onkeyup="renderList()"
                        placeholder="Search by settlement #, agent name..."
                        class="w-full pl-9 p-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select id="filter-status" onchange="renderList()"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        <option value="all">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="approved">Approved</option>
                        <option value="disputed">Disputed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Agent</label>
                    <select id="filter-agent" onchange="renderList()"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        <option value="all">All Agents</option>
                        <!-- Injected JS -->
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
                    <select id="filter-date" onchange="renderList()"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        <option value="today">Today</option>
                        <option value="week">Last 7 Days</option>
                        <option value="month" selected>Last 30 Days</option>
                        <option value="all">All Time</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <input type="checkbox" id="filter-variance" onchange="renderList()"
                    class="text-amber-500 focus:ring-amber-500 border-gray-300 rounded">
                <label for="filter-variance" class="text-sm text-gray-700 select-none cursor-pointer">Show only settlements
                    with cash variance</label>
            </div>

            <!-- Active Filters Display -->
            <div id="active-filters-bar"
                class="hidden mt-4 pt-4 border-t border-gray-200 flex flex-wrap gap-2 items-center">
                <span class="text-xs text-gray-500 font-medium">Active Filters:</span>
                <!-- Injected Badges -->
                <button onclick="resetFilters()" class="text-xs text-gray-500 hover:text-gray-700 underline ml-2">Clear
                    All</button>
            </div>
        </div>

        <!-- Settlement List -->
        <div id="list-container" class="space-y-4">
            <!-- Injected JS -->
        </div>

        <div id="no-results" class="hidden p-12 text-center bg-white rounded-xl border border-gray-200">
            <i class="bi bi-file-earmark-x text-gray-300 text-6xl mb-4 block"></i>
            <h3 class="text-gray-900 font-medium mb-1">No Settlements Found</h3>
            <p class="text-gray-500">Try adjusting your search criteria.</p>
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
            populateAgents();
            renderList();
        });

        function populateAgents() {
            const sel = document.getElementById('filter-agent');
            state.agents.forEach(a => {
                sel.innerHTML += `<option value="${a.id}">${a.agentName} (${a.agentCode})</option>`;
            });
        }

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

            const container = document.getElementById('list-container');
            const empty = document.getElementById('no-results');

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
            const borderClass = hasVar ? 'border-l-4 border-l-red-500' : 'border-l-4 border-l-transparent';
            const stlDate = new Date(s.settlementDate).toLocaleDateString();

            return `
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 ${borderClass} hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-3">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-gray-900 font-bold">${s.settlementNumber}</h3>
                                    ${getStatusBadge(s.status)}
                                    ${hasVar ? '<i class="bi bi-exclamation-triangle-fill text-red-600"></i>' : ''}
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <span class="flex items-center gap-1"><i class="bi bi-person"></i> ${agent ? agent.agentName : 'Unknown'} (${agent ? agent.agentCode : ''})</span>
                                    <span class="flex items-center gap-1"><i class="bi bi-calendar"></i> ${stlDate}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                    <a href="/agent-distribution/settlements/${s.id}" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 bg-white inline-flex items-center"><i class="bi bi-eye mr-2"></i>View</a>
                            ${getActionButtons(s)}
                        </div>
                    </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-3 bg-gray-50 rounded-lg mb-3">
                            <div><p class="text-xs text-gray-500">Total Sales</p><p class="text-sm font-medium text-gray-900">${formatCurrency(s.totalSales)}</p></div>
                            <div><p class="text-xs text-gray-500">Expected Cash</p><p class="text-sm font-medium text-gray-900">${formatCurrency(s.expectedCash)}</p></div>
                            <div><p class="text-xs text-gray-500">Actual Cash</p><p class="text-sm font-medium text-gray-900">${formatCurrency(s.actualCash)}</p></div>
                            <div><p class="text-xs text-gray-500">Variance</p>${getVarianceBadge(s.cashVariance)}</div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-3">
                            <div><span class="text-gray-500 text-xs">Collections</span><br><span class="text-green-600 font-medium">${formatCurrency(s.totalCollections)}</span></div>
                            <div><span class="text-gray-500 text-xs">Returns</span><br><span class="text-orange-600 font-medium">${formatCurrency(s.returnedValue)}</span></div>
                            <div><span class="text-gray-500 text-xs">Due to Bakery</span><br><span class="text-purple-600 font-medium">${formatCurrency(s.amountDueToBakery)}</span></div>
                            <div><span class="text-gray-500 text-xs">Commission</span><br><span class="text-blue-600 font-medium">${formatCurrency(s.commissionEarned)}</span></div>
                        </div>

                        ${hasVar && s.varianceNotes ? `
                            <div class="text-xs p-2 bg-red-50 text-red-800 rounded mb-2">
                                <span class="font-bold">Variance Note:</span> ${s.varianceNotes}
                            </div>
                        ` : ''}

                        ${s.notes ? `
                            <div class="text-xs p-2 bg-gray-50 text-gray-600 rounded">
                                <span class="font-bold">Note:</span> ${s.notes}
                            </div>
                        ` : ''}
                    </div>
                `;
        }

        function getActionButtons(s) {
            let btns = '';
            if (s.status === 'pending') {
                btns += `<button onclick="handleAction('${s.id}', 'reviewed')" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 ml-1"><i class="bi bi-eye mr-1"></i>Review</button>`;
                if (Math.abs(s.cashVariance) === 0) {
                    btns += `<button onclick="handleAction('${s.id}', 'approved')" class="px-3 py-1.5 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 ml-1"><i class="bi bi-check-lg mr-1"></i>Approve</button>`;
                }
            }
            if (s.status === 'reviewed') {
                btns += `<button onclick="handleAction('${s.id}', 'approved')" class="px-3 py-1.5 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 ml-1"><i class="bi bi-check-lg mr-1"></i>Approve</button>`;
            }
            if (s.status === 'pending' || s.status === 'reviewed') {
                btns += `<button onclick="handleAction('${s.id}', 'disputed')" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 ml-1"><i class="bi bi-x-lg mr-1"></i>Dispute</button>`;
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
                    inputAttributes: {
                        'aria-label': 'Reason for dispute'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Submit Dispute',
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: (reason) => {
                        if (!reason) {
                            Swal.showValidationMessage('Please enter a reason');
                        }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        state.settlements[idx].status = 'disputed';
                        state.settlements[idx].notes = (state.settlements[idx].notes ? state.settlements[idx].notes + ' | ' : '') + "DISPUTED: " + result.value;
                        renderList();
                        Swal.fire('Disputed!', 'The settlement has been marked as disputed.', 'success');
                    }
                });
            } else {
                // Confirm approval
                if (type === 'approved') {
                    Swal.fire({
                        title: 'Approve Settlement?',
                        text: "You are about to approve this settlement.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Approve it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateStatus(idx, type);
                        }
                    });
                } else {
                    updateStatus(idx, type);
                }
            }
        }

        function updateStatus(idx, type) {
            state.settlements[idx].status = type;
            if (type === 'reviewed') {
                state.settlements[idx].reviewedAt = new Date().toISOString();
            } else if (type === 'approved') {
                state.settlements[idx].approvedAt = new Date().toISOString();
            }
            renderList();
            const msg = type === 'reviewed' ? 'Settlement marked as reviewed' : 'Settlement approved successfully';
            const icon = type === 'reviewed' ? 'info' : 'success';
            Swal.fire('Success!', msg, icon);
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
            const varCountEl = document.getElementById('stat-variance-count');
            const varAmtEl = document.getElementById('stat-variance-amt');

            varCountEl.textContent = varCount;
            varAmtEl.textContent = (varAmt >= 0 ? '+' : '') + formatCurrency(varAmt);

            if (varCount > 0) {
                varEl.className = "p-4 bg-red-50 border border-red-200 rounded-xl shadow-sm";
                varIcon.className = "bi bi-exclamation-triangle text-red-600 text-xl";
                varCountEl.className = "text-2xl font-bold text-red-900 mb-1";
                varAmtEl.className = "text-xs text-red-700";
            } else {
                varEl.className = "p-4 bg-green-50 border border-green-200 rounded-xl shadow-sm";
                varIcon.className = "bi bi-check-circle text-green-600 text-xl";
                varCountEl.className = "text-2xl font-bold text-green-900 mb-1";
                varAmtEl.className = "text-xs text-green-700";
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
            const styles = {
                pending: 'bg-yellow-100 text-yellow-800',
                reviewed: 'bg-blue-100 text-blue-800',
                approved: 'bg-green-100 text-green-800',
                disputed: 'bg-red-100 text-red-800'
            };
            return `<span class="px-2 py-0.5 rounded-full text-xs font-medium capitalize ${styles[status] || 'bg-gray-100'}">${status}</span>`;
        }

        function getVarianceBadge(v) {
            if (v === 0) return `<span class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"><i class="bi bi-check"></i> Perfect</span>`;
            if (v > 0) return `<span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">+${formatCurrency(v)}</span>`;
            return `<span class="px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">${formatCurrency(v)}</span>`;
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