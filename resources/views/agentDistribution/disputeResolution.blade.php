@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="dispute-app">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Dispute Resolution & Reconciliation</h1>
                <p class="text-gray-600">Manage settlement disputes and variance reconciliation</p>
            </div>
            <button onclick="openCreateModal()"
                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-exclamation-triangle mr-2"></i>
                New Dispute
            </button>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Open Disputes -->
            <div class="bg-red-50 p-6 rounded-xl border border-red-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                    <span class="text-red-700 text-xs font-semibold uppercase tracking-wider">Open Disputes</span>
                </div>
                <p class="text-2xl font-bold text-red-900" id="stat-open">0</p>
            </div>

            <!-- Investigating -->
            <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-clock-history text-yellow-600 text-xl"></i>
                    <span class="text-yellow-700 text-xs font-semibold uppercase tracking-wider">Investigating</span>
                </div>
                <p class="text-2xl font-bold text-yellow-900" id="stat-investigating">0</p>
            </div>

            <!-- Resolved -->
            <div class="bg-green-50 p-6 rounded-xl border border-green-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-check-circle text-green-600 text-xl"></i>
                    <span class="text-green-700 text-xs font-semibold uppercase tracking-wider">Resolved</span>
                </div>
                <p class="text-2xl font-bold text-green-900" id="stat-resolved">0</p>
            </div>

            <!-- Total Amount -->
            <div class="bg-orange-50 p-6 rounded-xl border border-orange-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-currency-dollar text-orange-600 text-xl"></i>
                    <span class="text-orange-700 text-xs font-semibold uppercase tracking-wider">Total Amount</span>
                </div>
                <p class="text-xl font-bold text-orange-900" id="stat-amount">Rs. 0.00</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="filter-status" onchange="applyFilters()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="all">All Status</option>
                        <option value="open" selected>Open</option>
                        <option value="investigating">Investigating</option>
                        <option value="resolved">Resolved</option>
                        <option value="escalated">Escalated</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select id="filter-priority" onchange="applyFilters()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="all">All Priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Disputes List -->
        <div id="disputes-list" class="space-y-4">
            <!-- Content injected by JS -->
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden bg-white p-12 rounded-xl shadow-sm border border-gray-200 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-check-lg text-3xl text-green-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Disputes Found</h3>
            <p class="text-gray-500">All settlements are in good standing based on current filters!</p>
        </div>

        <!-- Create Dispute Modal -->
        <div id="create-modal" class="fixed inset-0 bg-gray-900/75 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Create Dispute</h3>
                        <p class="text-sm text-gray-500">Record a dispute for a settlement</p>
                    </div>
                    <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form id="create-dispute-form" onsubmit="handleCreateDispute(event)" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Settlement</label>
                        <select id="create-settlement" required
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select settlement</option>
                            <!-- Options injected by JS -->
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select id="create-type"
                                class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="cash_variance">Cash Variance</option>
                                <option value="product_count">Product Count</option>
                                <option value="payment_method">Payment Method</option>
                                <option value="customer_claim">Customer Claim</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select id="create-priority"
                                class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (Rs.)</label>
                        <input type="number" id="create-amount" step="0.01"
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="create-description" rows="3" required
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Describe the dispute..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Agent Notes (Optional)</label>
                        <textarea id="create-notes" rows="2"
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Agent's explanation..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Create
                            Dispute</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- View Dispute Modal -->
        <div id="view-modal" class="fixed inset-0 bg-gray-900/75 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-6 border w-full max-w-3xl shadow-lg rounded-xl bg-white">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">Dispute Details</h3>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div id="view-content" class="space-y-6">
                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-x-8 gap-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Settlement</label>
                            <p class="text-base text-gray-900 font-medium" id="view-settlement">-</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Agent</label>
                            <p class="text-base text-gray-900" id="view-agent">-</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1" id="view-status-badge"></div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Amount</label>
                            <p class="text-base text-red-600 font-bold" id="view-amount">-</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Description</label>
                        <p class="text-gray-900 mt-1 bg-gray-50 p-3 rounded-lg border border-gray-100"
                            id="view-description">-</p>
                    </div>

                    <!-- Agent Notes -->
                    <div id="view-notes-section" class="hidden">
                        <label class="text-sm font-medium text-gray-500">Agent Notes</label>
                        <div class="mt-1 bg-blue-50 border border-blue-100 p-3 rounded-lg text-blue-900 text-sm"
                            id="view-notes"></div>
                    </div>

                    <!-- Timeline -->
                    <div>
                        <label class="text-sm font-medium text-gray-900 block mb-3">Timeline</label>
                        <div class="space-y-4 pl-2 border-l-2 border-gray-200" id="view-timeline">
                            <!-- Injected JS -->
                        </div>
                    </div>

                    <!-- Resolution Input -->
                    <div id="view-resolution-input" class="space-y-3 pt-4 border-t border-gray-100">
                        <label class="block text-sm font-medium text-gray-700">Resolution Notes</label>
                        <textarea id="resolution-notes" rows="3"
                            class="w-full border bg-gray-50 p-2 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Enter resolution details..."></textarea>
                    </div>

                    <!-- Resolution Display -->
                    <div id="view-resolution-display"
                        class="hidden mt-4 bg-green-50 border border-green-100 p-4 rounded-lg">
                        <h4 class="text-sm font-bold text-green-800 mb-1">Resolution</h4>
                        <p class="text-green-900 text-sm" id="resolved-resolution"></p>
                        <p class="text-green-700 text-xs mt-2" id="resolved-meta"></p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-100" id="view-actions">
                    <button onclick="openWriteOffModal()"
                        class="px-4 py-2 border border-orange-200 text-orange-700 hover:bg-orange-50 rounded-lg">
                        Write-Off
                    </button>

                    <button onclick="markInvestigating()"
                        class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg">
                        Mark Investigating
                    </button>

                    <button onclick="resolveDispute()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
                        <i class="bi bi-check-lg mr-2"></i> Resolve
                    </button>
                </div>

                <div class="hidden justify-end mt-8 pt-4 border-t border-gray-100" id="view-close-btn">
                    <button onclick="closeViewModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Write Off Modal -->
        <div id="writeoff-modal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-[60]">
            <div class="relative top-32 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Write-Off Variance</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Write-Off Amount (Rs.)</label>
                        <input type="number" id="writeoff-amount" step="0.01"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                        <textarea id="writeoff-reason" rows="3"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Explain the reason..."></textarea>
                    </div>

                    <div class="bg-orange-50 border border-orange-200 p-3 rounded-lg">
                        <p class="text-sm text-orange-800">
                            <i class="bi bi-info-circle mr-1"></i>
                            This will adjust the settlement variance and mark the dispute as resolved. Requires managerial
                            approval.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="closeWriteOffModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button onclick="confirmWriteOff()"
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">Confirm Write-Off</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Data Injection -->
    <script>
        // Initialize data from server (fallback to empty arrays if null)
        const serverAgents = @json($agents ?? []);
        const serverSettlements = @json($settlements ?? []);
        // Only use server disputes if localStorage is empty or for initial seed
        const serverDisputes = @json($disputes ?? []);
    </script>

    <script>
        // State Management
        let state = {
            agents: serverAgents,
            settlements: serverSettlements,
            disputes: [],
            currentDisputeId: null
        };

        // --- Initialization ---
        document.addEventListener('DOMContentLoaded', () => {
            loadData();
            renderSummaryStats();
            renderDisputes();
            initCreateModal();
        });

        function loadData() {
            const storedDisputes = localStorage.getItem('settlementDisputes');
            if (storedDisputes) {
                state.disputes = JSON.parse(storedDisputes);
            } else {
                // Seed with server data if local is empty
                state.disputes = serverDisputes;
                saveDisputes(state.disputes);
            }
        }

        function saveDisputes(disputes) {
            localStorage.setItem('settlementDisputes', JSON.stringify(disputes));
            state.disputes = disputes;
            renderSummaryStats();
            renderDisputes();
            // Also sync state.settlements status updates if needed here
        }

        // --- Rendering ---
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'LKR', minimumFractionDigits: 2 }).format(amount).replace('LKR', 'Rs.');
        }

        function formatDate(isoString) {
            if (!isoString) return '';
            return new Date(isoString).toLocaleString();
        }

        function renderSummaryStats() {
            const open = state.disputes.filter(d => d.status === 'open').length;
            const investigating = state.disputes.filter(d => d.status === 'investigating').length;
            const resolved = state.disputes.filter(d => d.status === 'resolved' || d.status === 'closed').length;

            // Sum absolute amounts of actionable disputes
            const totalAmount = state.disputes
                .filter(d => ['open', 'investigating'].includes(d.status))
                .reduce((sum, d) => sum + Math.abs(Number(d.amount)), 0);

            document.getElementById('stat-open').textContent = open;
            document.getElementById('stat-investigating').textContent = investigating;
            document.getElementById('stat-resolved').textContent = resolved;
            document.getElementById('stat-amount').textContent = formatCurrency(totalAmount);
        }

        function renderDisputes() {
            const listContainer = document.getElementById('disputes-list');
            const emptyState = document.getElementById('empty-state');
            const statusFilter = document.getElementById('filter-status').value;
            const priorityFilter = document.getElementById('filter-priority').value;

            // Filter Data
            const filtered = state.disputes.filter(d => {
                if (statusFilter !== 'all' && d.status !== statusFilter) return false;
                if (priorityFilter !== 'all' && d.priority !== priorityFilter) return false;
                return true;
            });

            if (filtered.length === 0) {
                listContainer.innerHTML = '';
                listContainer.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            listContainer.classList.remove('hidden');

            listContainer.innerHTML = filtered.map(d => {
                const statusConfig = getStatusConfig(d.status);
                const priorityClass = getPriorityClass(d.priority);

                return `
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-900">${d.settlementNumber}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusConfig.class}">
                                    <i class="${statusConfig.icon} mr-1"></i> ${statusConfig.label}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${priorityClass}">
                                    ${d.priority.toUpperCase()}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mb-3">
                                Agent: <span class="text-gray-900 font-medium">${d.agentName}</span> • 
                                Created: ${new Date(d.createdAt).toLocaleDateString()}
                            </p>
                            <p class="text-gray-700 mb-3">${d.description}</p>
                            <div class="flex items-center gap-6 text-sm">
                                <span class="text-gray-600">Type: <span class="capitalize font-medium text-gray-900">${d.type.replace('_', ' ')}</span></span>
                                <span class="text-red-600 font-bold">Amount: ${formatCurrency(d.amount)}</span>
                            </div>
                        </div>
                        <button onclick="openViewModal('${d.id}')" class="ml-4 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                            <i class="bi bi-eye mr-2"></i> View Details
                        </button>
                    </div>
                </div>
                `;
            }).join('');
        }

        // --- Helpers ---
        function getStatusConfig(status) {
            const map = {
                'open': { label: 'Open', class: 'bg-red-100 text-red-800', icon: 'bi bi-exclamation-triangle' },
                'investigating': { label: 'Investigating', class: 'bg-yellow-100 text-yellow-800', icon: 'bi bi-clock-history' },
                'resolved': { label: 'Resolved', class: 'bg-green-100 text-green-800', icon: 'bi bi-check-circle' },
                'closed': { label: 'Closed', class: 'bg-gray-100 text-gray-800', icon: 'bi bi-x-circle' },
                'escalated': { label: 'Escalated', class: 'bg-orange-100 text-orange-800', icon: 'bi bi-arrow-up-circle' }
            };
            return map[status] || map['open'];
        }

        function getPriorityClass(priority) {
            const map = {
                'low': 'bg-blue-100 text-blue-800',
                'medium': 'bg-yellow-100 text-yellow-800',
                'high': 'bg-red-100 text-red-800'
            };
            return map[priority] || map['medium'];
        }

        function applyFilters() {
            renderDisputes();
        }

        // --- Modal Logic: Create Dispute ---
        function initCreateModal() {
            const select = document.getElementById('create-settlement');
            // Filter settlements that are 'disputed' or 'reviewed' but not already in a specific state if needed
            // For simplified demo, showing all settlements or filtering
            const disputedOrReviewed = state.settlements;

            select.innerHTML = '<option value="">Select settlement</option>' +
                disputedOrReviewed.map(s => {
                    const agent = state.agents.find(a => a.id === s.agentId);
                    const variance = s.cashVariance !== 0 ? ` (Var: ${s.cashVariance})` : '';
                    return `<option value="${s.id}">${s.settlementNumber} - ${agent ? agent.agentName : 'Unknown'}${variance}</option>`;
                }).join('');
        }

        function openCreateModal() {
            // Reset form
            document.getElementById('create-dispute-form').reset();
            document.getElementById('create-modal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('create-modal').classList.add('hidden');
        }

        function handleCreateDispute(e) {
            e.preventDefault();

            const settlementId = document.getElementById('create-settlement').value;
            const settlement = state.settlements.find(s => s.id === settlementId);
            const agent = state.agents.find(a => a.id === settlement.agentId);

            if (!settlement) return;

            const newDispute = {
                id: `dispute_${Date.now()}`,
                settlementId: settlement.id,
                settlementNumber: settlement.settlementNumber,
                agentId: settlement.agentId,
                agentName: agent ? agent.agentName : 'Unknown',
                type: document.getElementById('create-type').value,
                status: 'open',
                priority: document.getElementById('create-priority').value,
                amount: Number(document.getElementById('create-amount').value),
                description: document.getElementById('create-description').value,
                agentNotes: document.getElementById('create-notes').value,
                createdAt: new Date().toISOString(),
                createdBy: 'Manager',
                timeline: [{
                    id: `tl_${Date.now()}`,
                    timestamp: new Date().toISOString(),
                    user: 'Manager',
                    action: 'Dispute created',
                    notes: document.getElementById('create-description').value
                }]
            };

            // Add to state
            saveDisputes([...state.disputes, newDispute]);

            // Simulating settlement update (in a real app this would call API)
            // Here we just toast
            showToast('Dispute created successfully', 'success');

            closeCreateModal();
        }

        // --- Modal Logic: View Dispute ---
        function openViewModal(id) {
            const dispute = state.disputes.find(d => d.id === id);
            if (!dispute) return;
            state.currentDisputeId = id;

            // Populate fields
            document.getElementById('view-settlement').textContent = dispute.settlementNumber;
            document.getElementById('view-agent').textContent = dispute.agentName;
            document.getElementById('view-amount').textContent = formatCurrency(dispute.amount);
            document.getElementById('view-description').textContent = dispute.description;

            // Badge
            const statusConfig = getStatusConfig(dispute.status);
            document.getElementById('view-status-badge').innerHTML =
                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusConfig.class}"><i class="${statusConfig.icon} mr-1"></i> ${statusConfig.label}</span>`;

            // Agent Notes
            const notesSection = document.getElementById('view-notes-section');
            const notesEl = document.getElementById('view-notes');
            if (dispute.agentNotes) {
                notesEl.textContent = dispute.agentNotes;
                notesSection.classList.remove('hidden');
            } else {
                notesSection.classList.add('hidden');
            }

            // Timeline
            const timelineEl = document.getElementById('view-timeline');
            timelineEl.innerHTML = dispute.timeline.map((t, idx) => `
                <div class="relative pl-4 pb-4 ${idx !== dispute.timeline.length - 1 ? 'border-l-2 border-gray-200' : ''} ml-1">
                    <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-amber-400 border-2 border-white"></div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">${t.action}</p>
                        <p class="text-xs text-gray-500">${new Date(t.timestamp).toLocaleString()} by ${t.user}</p>
                        ${t.notes ? `<p class="text-sm text-gray-600 mt-1">${t.notes}</p>` : ''}
                    </div>
                </div>
            `).join('');

            // Action Buttons Logic
            const isActive = ['open', 'investigating'].includes(dispute.status);
            const actionBtns = document.getElementById('view-actions');
            const closeBtn = document.getElementById('view-close-btn');
            const resolutionInp = document.getElementById('view-resolution-input');
            const resolutionDisp = document.getElementById('view-resolution-display');

            if (isActive) {
                actionBtns.classList.remove('hidden');
                actionBtns.classList.add('flex');
                closeBtn.classList.add('hidden');
                resolutionInp.classList.remove('hidden');
                resolutionDisp.classList.add('hidden');
                document.getElementById('resolution-notes').value = ''; // clear previous
            } else {
                actionBtns.classList.add('hidden');
                actionBtns.classList.remove('flex');
                closeBtn.classList.remove('hidden');
                closeBtn.classList.add('flex');
                resolutionInp.classList.add('hidden');

                if (dispute.resolution) {
                    resolutionDisp.classList.remove('hidden');
                    document.getElementById('resolved-resolution').textContent = dispute.resolution;
                    document.getElementById('resolved-meta').textContent = `Resolved on ${formatDate(dispute.resolvedAt)} by ${dispute.resolvedBy || 'Manager'}`;
                } else {
                    resolutionDisp.classList.add('hidden');
                }
            }

            document.getElementById('view-modal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('view-modal').classList.add('hidden');
            state.currentDisputeId = null;
        }

        function addTimelineEntry(dispute, action, notes) {
            const entry = {
                id: `tl_${Date.now()}`,
                timestamp: new Date().toISOString(),
                user: 'Manager',
                action: action,
                notes: notes
            };
            dispute.timeline.push(entry);
            return dispute;
        }

        function updateDisputeStatus(id, newStatus, notes, resolution = null) {
            const updatedDisputes = state.disputes.map(d => {
                if (d.id === id) {
                    let updated = { ...d }; // clone
                    addTimelineEntry(updated, `Status changed to ${newStatus}`, notes);
                    updated.status = newStatus;

                    if (resolution) {
                        updated.resolution = resolution;
                        updated.resolvedAt = new Date().toISOString();
                        updated.resolvedBy = 'Manager';
                    }
                    return updated;
                }
                return d;
            });

            saveDisputes(updatedDisputes);
        }

        function markInvestigating() {
            if (!state.currentDisputeId) return;
            updateDisputeStatus(state.currentDisputeId, 'investigating');
            showToast('Status updated to Investigating', 'success');
            openViewModal(state.currentDisputeId); // refresh modal
        }

        function resolveDispute() {
            const notes = document.getElementById('resolution-notes').value;
            if (!notes.trim()) {
                showToast('Please provide resolution notes', 'error');
                return;
            }

            updateDisputeStatus(state.currentDisputeId, 'resolved', 'Dispute manually resolved', notes);
            showToast('Dispute resolved successfully', 'success');
            openViewModal(state.currentDisputeId); // refresh
        }

        // --- Write Off Logic ---
        function openWriteOffModal() {
            // Keeps view modal open behind it
            const dispute = state.disputes.find(d => d.id === state.currentDisputeId);
            if (!dispute) return;

            document.getElementById('writeoff-amount').value = Math.abs(dispute.amount);
            document.getElementById('writeoff-reason').value = '';
            document.getElementById('writeoff-modal').classList.remove('hidden');
        }

        function closeWriteOffModal() {
            document.getElementById('writeoff-modal').classList.add('hidden');
        }

        function confirmWriteOff() {
            const amount = document.getElementById('writeoff-amount').value;
            const reason = document.getElementById('writeoff-reason').value;

            if (!reason.trim()) {
                showToast('Please provide a reason', 'error');
                return;
            }

            // Logic: 1. Create reconciliation action (optional log) 2. Update status 3. Close both modals
            updateDisputeStatus(state.currentDisputeId, 'resolved', `Write-off applied: Rs. ${amount}`, `Write-off: ${reason}`);

            showToast('Write-off applied successfully', 'success');

            closeWriteOffModal();
            openViewModal(state.currentDisputeId); // refresh main modal to show resolved state
        }

        // --- Utilities ---
        function showToast(message, type = 'success') {
            // Simple alert as placeholder for toast library, or implement a simple toast container
            // Existing app might have Swal or toastr. 
            // Using a dynamically created element for better UI feel than alert()
            const div = document.createElement('div');
            const colorClass = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            div.className = `fixed top-4 right-4 ${colorClass} text-white px-6 py-3 rounded-lg shadow-lg z-[70] transition-opacity duration-300 transform translate-y-0`;
            div.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'} mr-2"></i> ${message}`;

            document.body.appendChild(div);

            setTimeout(() => {
                div.style.opacity = '0';
                setTimeout(() => div.remove(), 300);
            }, 3000);
        }
    </script>
@endsection