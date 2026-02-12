@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="commission-payments-app">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Commission Payments</h1>
                <p class="text-gray-600">Process and track agent commission payments</p>
            </div>
            <button onclick="openCreateBatchModal()"
                class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors shadow-sm">
                <i class="bi bi-file-earmark-plus mr-2"></i>
                Create Batch
            </button>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-clock-history text-yellow-600 text-xl"></i>
                    <span class="text-yellow-700 text-xs font-semibold uppercase tracking-wider">Pending</span>
                </div>
                <p class="text-2xl font-bold text-yellow-900 mb-1" id="stat-pending-count">0</p>
                <p class="text-yellow-700 text-xs" id="stat-pending-amt">Rs. 0</p>
            </div>

            <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-check-circle-fill text-green-600 text-xl"></i>
                    <span class="text-green-700 text-xs font-semibold uppercase tracking-wider">Paid</span>
                </div>
                <p class="text-2xl font-bold text-green-900 mb-1" id="stat-paid-count">0</p>
                <p class="text-green-700 text-xs" id="stat-paid-amt">Rs. 0</p>
            </div>

            <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-bank text-red-600 text-xl"></i>
                    <span class="text-red-700 text-xs font-semibold uppercase tracking-wider">Total Tax Withheld</span>
                </div>
                <p class="text-xl font-bold text-red-900" id="stat-tax">Rs. 0</p>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-graph-up text-blue-600 text-xl"></i>
                    <span class="text-blue-700 text-xs font-semibold uppercase tracking-wider">Total Payments</span>
                </div>
                <p class="text-2xl font-bold text-blue-900" id="stat-total">0</p>
            </div>
        </div>

        <!-- Bulk Actions (Hidden by default) -->
        <div id="bulk-actions"
            class="hidden mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <p class="text-gray-900 font-medium"><span id="selected-count">0</span> payment(s) selected</p>
                <p class="text-gray-600 text-sm">Total: <span id="selected-total">Rs. 0</span></p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="openProcessModal()"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-sm inline-flex items-center">
                    <i class="bi bi-check-lg mr-2"></i> Process Payments
                </button>
                <button onclick="clearSelection()"
                    class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Clear
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="filter-status" onchange="renderPayments()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                        <option value="all">All Status</option>
                        <option value="pending" selected>Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Agent</label>
                    <select id="filter-agent" onchange="renderPayments()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                        <option value="all">All Agents</option>
                        <!-- Injected via JS -->
                    </select>
                </div>
                <div class="md:col-span-2">
                    <button onclick="toggleSelectAll()" id="btn-select-all"
                        class="w-full md:w-auto px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Select All Pending
                    </button>
                </div>
            </div>
        </div>

        <!-- Payments List -->
        <div id="payments-list" class="space-y-4">
            <!-- Injected via JS -->
            <div id="empty-state" class="hidden p-12 text-center bg-white rounded-xl border border-gray-200">
                <i class="bi bi-cash-stack text-gray-300 text-6xl mb-4 block"></i>
                <h3 class="text-gray-900 font-medium mb-1">No Commission Payments</h3>
                <p class="text-gray-500 mb-6">Create a commission batch to get started.</p>
                <button onclick="openCreateBatchModal()"
                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                    Create Batch
                </button>
            </div>
        </div>
    </div>

    <!-- Create Batch Modal -->
    <div id="create-batch-modal"
        class="fixed inset-0 z-50 hidden bg-gray-900/75 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Create Commission Batch</h3>
                <p class="text-sm text-gray-500">Calculate commissions for all agents</p>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Period Start</label>
                    <input type="date" id="batch-start"
                        class="w-full p-2 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Period End</label>
                    <input type="date" id="batch-end"
                        class="w-full p-2 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                </div>
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                    <i class="bi bi-info-circle mr-1"></i> This will calculate commissions for all active agents based on approved settlements in the selected period.
                </div>
            </div>
            <div class="p-6 bg-gray-50 rounded-b-xl flex justify-end gap-2">
                <button onclick="closeCreateBatchModal()"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-lg">Cancel</button>
                <button onclick="createBatch()"
                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">Create Batch</button>
            </div>
        </div>
    </div>

    <!-- Process Payment Modal -->
    <div id="process-modal"
        class="fixed inset-0 z-50 hidden bg-gray-900/75 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Process Payments</h3>
                <p class="text-sm text-gray-500">Processing <span id="proc-count">0</span> payment(s)</p>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select id="proc-method"
                        class="w-full p-2 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                    <input type="date" id="proc-date"
                        class="w-full p-2 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reference ID *</label>
                    <input type="text" id="proc-ref" placeholder="Transaction ID / Cheque No"
                        class="w-full p-2 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="proc-notes" rows="2"
                        class="w-full p-2 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500"></textarea>
                </div>
                <div class="p-3 bg-gray-100 rounded-lg flex justify-between items-center font-bold text-gray-800">
                    <span>Total Amount:</span>
                    <span id="proc-total">Rs. 0</span>
                </div>
            </div>
            <div class="p-6 bg-gray-50 rounded-b-xl flex justify-end gap-2">
                <button onclick="closeProcessModal()"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-lg">Cancel</button>
                <button onclick="processPayments()"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Confirm Payment</button>
            </div>
        </div>
    </div>

    <script>
        // Server Data
        const serverAgents = @json($agents ?? []);
        const serverSettlements = @json($settlements ?? []);

        const state = {
            agents: serverAgents,
            settlements: serverSettlements,
            payments: [],
            selectedIds: new Set()
        };

        document.addEventListener('DOMContentLoaded', () => {
            // Init LocalStorage
            const stored = localStorage.getItem('commissionPayments');
            if (stored) {
                state.payments = JSON.parse(stored);
            }

            populateAgents();
            renderPayments();
            updateStats();

            // Init UI Defaults
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            document.getElementById('batch-start').value = firstDay.toISOString().split('T')[0];
            document.getElementById('batch-end').value = now.toISOString().split('T')[0];
            document.getElementById('proc-date').value = now.toISOString().split('T')[0];
        });

        function populateAgents() {
            const options = state.agents.map(a => `<option value="${a.id}">${a.agentName}</option>`).join('');
            document.getElementById('filter-agent').innerHTML += options;
        }

        // --- Logic ---

        function createBatch() {
            const start = document.getElementById('batch-start').value;
            const end = document.getElementById('batch-end').value;

            const newPayments = [];

            state.agents.forEach(agent => {
                const agentStls = state.settlements.filter(s =>
                    s.agentId === agent.id &&
                    s.settlementDate >= start &&
                    s.settlementDate <= end &&
                    s.status === 'approved'
                );

                if (agentStls.length === 0) return;

                const totalSales = agentStls.reduce((sum, s) => sum + Number(s.totalSales), 0);
                const gross = agentStls.reduce((sum, s) => sum + Number(s.commissionEarned), 0);
                const tax = gross * 0.10;
                const net = gross - tax;

                if (gross <= 0) return;

                newPayments.push({
                    id: 'pay_' + Date.now() + '_' + agent.id,
                    agentId: agent.id,
                    agentName: agent.agentName,
                    agentCode: agent.agentCode,
                    periodStart: start,
                    periodEnd: end,
                    totalSales,
                    commissionRate: agent.commissionRate,
                    grossCommission: gross,
                    deductions: { tax, other: 0 },
                    netCommission: net,
                    paymentStatus: 'pending',
                    createdAt: new Date().toISOString()
                });
            });

            if (newPayments.length === 0) {
                showToast('No eligible commissions found for this period', 'error');
                return;
            }

            state.payments = [...state.payments, ...newPayments];
            savePayments();
            closeCreateBatchModal();

            // Reset filter to pending to show new items
            document.getElementById('filter-status').value = 'pending';
            renderPayments();
            updateStats();
            showToast(`Created ${newPayments.length} commission payment(s)`);
        }

        function processPayments() {
            const ref = document.getElementById('proc-ref').value;
            if (!ref.trim()) { showToast('Reference ID is required', 'error'); return; }

            const method = document.getElementById('proc-method').value;
            const date = document.getElementById('proc-date').value;
            const notes = document.getElementById('proc-notes').value;

            state.payments = state.payments.map(p => {
                if (state.selectedIds.has(p.id)) {
                    return {
                        ...p,
                        paymentStatus: 'paid',
                        paymentMethod: method,
                        paymentDate: date,
                        paymentReference: ref,
                        notes: notes,
                        paidAt: new Date().toISOString()
                    };
                }
                return p;
            });

            savePayments();
            clearSelection();
            closeProcessModal();
            renderPayments();
            updateStats();
            showToast('Payments processed successfully');
        }

        function savePayments() {
            localStorage.setItem('commissionPayments', JSON.stringify(state.payments));
        }

        // --- UI Rendering ---

        function renderPayments() {
            const fStatus = document.getElementById('filter-status').value;
            const fAgent = document.getElementById('filter-agent').value;
            const list = document.getElementById('payments-list');
            const empty = document.getElementById('empty-state');

            const filtered = state.payments.filter(p => {
                if (fStatus !== 'all' && p.paymentStatus !== fStatus) return false;
                if (fAgent !== 'all' && p.agentId !== fAgent) return false;
                return true;
            });

            if (filtered.length === 0) {
                list.innerHTML = '';
                list.appendChild(empty);
                empty.classList.remove('hidden');
                return;
            }

            empty.classList.add('hidden');
            list.innerHTML = filtered.map(p => {
                const isSelected = state.selectedIds.has(p.id);
                const isPending = p.paymentStatus === 'pending';
                const statusBadge = getStatusBadge(p.paymentStatus);
                const borderClass = isSelected ? 'border-amber-500 ring-1 ring-amber-500' : 'border-gray-200';

                return `
                    <div class="bg-white p-4 rounded-xl shadow-sm border ${borderClass} transition-all">
                        <div class="flex items-start gap-4">
                            ${isPending ? `
                                <div class="pt-1">
                                    <input type="checkbox" onchange="toggleSelection('${p.id}')" ${isSelected ? 'checked' : ''} class="w-5 h-5 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                                </div>
                            ` : ''}

                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-gray-900 font-bold">${p.agentName}</h3>
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">${p.agentCode}</span>
                                    ${statusBadge}
                                </div>
                                <p class="text-gray-500 text-sm mb-3">
                                    Period: ${new Date(p.periodStart).toLocaleDateString()} - ${new Date(p.periodEnd).toLocaleDateString()}
                                </p>

                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                    <div><p class="text-xs text-gray-500">Total Sales</p><p class="text-sm font-medium text-gray-900">${formatCurrency(p.totalSales)}</p></div>
                                    <div><p class="text-xs text-gray-500">Gross Comm.</p><p class="text-sm font-medium text-gray-900">${formatCurrency(p.grossCommission)}</p></div>
                                    <div><p class="text-xs text-gray-500 text-red-600">Tax</p><p class="text-sm text-red-600">-${formatCurrency(p.deductions.tax)}</p></div>
                                    <div><p class="text-xs text-gray-500">Net Commission</p><p class="text-sm font-bold text-green-600">${formatCurrency(p.netCommission)}</p></div>
                                    <div><p class="text-xs text-gray-500">Rate</p><p class="text-sm font-medium text-gray-900">${p.commissionRate}%</p></div>
                                </div>

                                ${p.paymentStatus === 'paid' ? `
                                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg grid grid-cols-3 gap-4 text-xs">
                                        <div><span class="text-green-700 block">Date</span><span class="font-medium text-green-900">${new Date(p.paymentDate).toLocaleDateString()}</span></div>
                                        <div><span class="text-green-700 block">Ref</span><span class="font-medium text-green-900">${p.paymentReference}</span></div>
                                        <div><span class="text-green-700 block">Method</span><span class="font-medium text-green-900 capitalize">${p.paymentMethod?.replace('_', ' ')}</span></div>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            updateBulkActions();
        }

        function getStatusBadge(status) {
            const styles = {
                pending: 'bg-yellow-100 text-yellow-800 bi-clock',
                paid: 'bg-green-100 text-green-800 bi-check-circle-fill',
                failed: 'bg-red-100 text-red-800 bi-exclamation-triangle-fill'
            };
            const style = styles[status] || 'bg-gray-100';
            const icon = style.split(' ').pop();
            return `<span class="px-2 py-0.5 rounded-full text-xs font-medium capitalize flex items-center gap-1 ${style.replace(icon, '')}"><i class="bi ${icon}"></i> ${status}</span>`;
        }

        // --- Selection Logic ---

        function toggleSelection(id) {
            if (state.selectedIds.has(id)) {
                state.selectedIds.delete(id);
            } else {
                state.selectedIds.add(id);
            }
            renderPayments(); // Re-render to update checkbox visuals
        }

        function toggleSelectAll() {
            const pending = state.payments.filter(p => p.paymentStatus === 'pending');
            if (state.selectedIds.size === pending.length && pending.length > 0) {
                state.selectedIds.clear();
            } else {
                pending.forEach(p => state.selectedIds.add(p.id));
            }
            renderPayments();
        }

        function clearSelection() {
            state.selectedIds.clear();
            renderPayments();
        }

        function updateBulkActions() {
            if (state.selectedIds.size > 0) {
                document.getElementById('bulk-actions').classList.remove('hidden');
                document.getElementById('selected-count').textContent = state.selectedIds.size;

                const total = state.payments
                    .filter(p => state.selectedIds.has(p.id))
                    .reduce((sum, p) => sum + Number(p.netCommission), 0);

                document.getElementById('selected-total').textContent = formatCurrency(total);
            } else {
                document.getElementById('bulk-actions').classList.add('hidden');
            }
        }

        // --- Stats ---

        function updateStats() {
            const pending = state.payments.filter(p => p.paymentStatus === 'pending');
            const paid = state.payments.filter(p => p.paymentStatus === 'paid');

            document.getElementById('stat-pending-count').textContent = pending.length;
            document.getElementById('stat-pending-amt').textContent = formatCurrency(pending.reduce((s, p) => s + p.netCommission, 0));

            document.getElementById('stat-paid-count').textContent = paid.length;
            document.getElementById('stat-paid-amt').textContent = formatCurrency(paid.reduce((s, p) => s + p.netCommission, 0));

            document.getElementById('stat-tax').textContent = formatCurrency(state.payments.reduce((s, p) => s + p.deductions.tax, 0));
            document.getElementById('stat-total').textContent = state.payments.length;
        }


        // --- Modals ---
        function openCreateBatchModal() {
            document.getElementById('create-batch-modal').classList.remove('hidden');
        }
        function closeCreateBatchModal() {
            document.getElementById('create-batch-modal').classList.add('hidden');
        }
        function openProcessModal() {
            if (state.selectedIds.size === 0) return;

            document.getElementById('proc-count').textContent = state.selectedIds.size;
            const total = state.payments
                .filter(p => state.selectedIds.has(p.id))
                .reduce((sum, p) => sum + Number(p.netCommission), 0);
            document.getElementById('proc-total').textContent = formatCurrency(total);
            document.getElementById('proc-ref').value = '';

            document.getElementById('process-modal').classList.remove('hidden');
        }
        function closeProcessModal() {
            document.getElementById('process-modal').classList.add('hidden');
        }

        // --- Utils ---
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'LKR', minimumFractionDigits: 2 }).format(amount).replace('LKR', 'Rs.');
        }
        function showToast(message, type = 'success') {
            const div = document.createElement('div');
            const bg = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            div.className = `fixed top-4 right-4 ${bg} text-white px-6 py-3 rounded-lg shadow-lg z-[80] transition-opacity`;
            div.innerHTML = `<i class="bi bi-info-circle mr-2"></i> ${message}`;
            document.body.appendChild(div);
            setTimeout(() => { div.style.opacity = '0'; setTimeout(() => div.remove(), 300); }, 3000);
        }
    </script>
@endsection