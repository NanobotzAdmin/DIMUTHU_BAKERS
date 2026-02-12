@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="automation-app">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Settlement Automation</h1>
                <p class="text-gray-600">Automate settlement workflows and batch operations</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="runAutomation()"
                    class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                    <i class="bi bi-play-fill mr-2"></i>
                    Run Automation
                </button>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Pending Review -->
            <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-clock-history text-yellow-600 text-xl"></i>
                    <span class="text-yellow-700 text-xs font-semibold uppercase tracking-wider">Pending Review</span>
                </div>
                <p class="text-2xl font-bold text-yellow-900" id="stat-pending">0</p>
            </div>

            <!-- Reviewed -->
            <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-file-check text-blue-600 text-xl"></i>
                    <span class="text-blue-700 text-xs font-semibold uppercase tracking-wider">Reviewed</span>
                </div>
                <p class="text-2xl font-bold text-blue-900" id="stat-reviewed">0</p>
            </div>

            <!-- Ready for GL -->
            <div class="bg-green-50 p-6 rounded-xl border border-green-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-check-circle text-green-600 text-xl"></i>
                    <span class="text-green-700 text-xs font-semibold uppercase tracking-wider">Ready for GL</span>
                </div>
                <p class="text-2xl font-bold text-green-900" id="stat-approved">0</p>
            </div>

            <!-- Active Rules -->
            <div class="bg-purple-50 p-6 rounded-xl border border-purple-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-lightning-charge text-purple-600 text-xl"></i>
                    <span class="text-purple-700 text-xs font-semibold uppercase tracking-wider">Active Rules</span>
                </div>
                <p class="text-2xl font-bold text-purple-900" id="stat-active-rules">0</p>
            </div>
        </div>

        <!-- Automation Rules -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Automation Rules</h3>
                <button onclick="openRuleModal()"
                    class="inline-flex items-center px-3 py-1.5 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition-colors">
                    <i class="bi bi-gear-fill mr-2"></i>
                    New Rule
                </button>
            </div>

            <div id="rules-list" class="space-y-3">
                <!-- Injected JS -->
            </div>
        </div>

        <!-- Batch Selection Control (Hidden by default) -->
        <div id="batch-control"
            class="hidden bg-amber-50 border border-amber-200 p-4 rounded-xl mb-6 flex items-center justify-between">
            <p class="text-gray-900 font-medium"><span id="selected-count">0</span> settlement(s) selected</p>
            <div class="flex items-center gap-2">
                <button onclick="openBatchModal()"
                    class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                    <i class="bi bi-check2-all mr-2"></i>
                    Batch Process
                </button>
                <button onclick="clearSelection()"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-white transition-colors">
                    Clear
                </button>
            </div>
        </div>

        <!-- Pending Settlements -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pending Settlements</h3>
                <button onclick="toggleSelectAll()" id="select-all-btn"
                    class="px-3 py-1.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    Select All
                </button>
            </div>

            <div id="settlements-list" class="space-y-3">
                <!-- Injected JS -->
            </div>
        </div>

        <!-- Recent Batch Operations -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Batch Operations</h3>
            <div id="batch-ops-list" class="space-y-3">
                <!-- Injected JS -->
            </div>
        </div>

        <!-- Create Rule Modal -->
        <div id="rule-modal" class="fixed inset-0 bg-gray-900/75 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Create Automation Rule</h3>
                    <button onclick="closeRuleModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form id="create-rule-form" onsubmit="handleCreateRule(event)" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rule Name</label>
                        <input type="text" id="rule-name" required placeholder="e.g., Auto-approve small variances"
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rule Type</label>
                        <select id="rule-type"
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="auto_review">Auto Review</option>
                            <option value="auto_approve">Auto Approve</option>
                            <option value="variance_alert">Variance Alert</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Variance (Rs.)</label>
                        <input type="number" id="rule-variance" required value="0" step="0.01"
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="rule-enabled" checked
                            class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                        <label for="rule-enabled" class="text-sm font-medium text-gray-700">Enable this rule
                            immediately</label>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeRuleModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">Create
                            Rule</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Batch Modal -->
        <div id="batch-modal" class="fixed inset-0 bg-gray-900/75 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-32 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Batch Operation</h3>
                <p class="text-gray-600 mb-4">Process <span id="batch-count" class="font-bold">0</span> selected
                    settlement(s)</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Operation Type</label>
                        <select id="batch-type"
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="review">Mark as Reviewed</option>
                            <option value="approve">Approve</option>
                            <option value="post_gl">Post to GL</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="closeBatchModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button onclick="handleBatchProcess()"
                        class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">Process Batch</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const serverSettlements = @json($settlements ?? []);
    </script>

    <script>
        // State
        const state = {
            settlements: [],
            rules: [],
            batchOps: [],
            selectedIds: []
        };

        // Defaults
        const defaultRules = [
            {
                id: 'rule_1',
                name: 'Auto-Review Clean Settlements',
                enabled: true,
                type: 'auto_review',
                conditions: { maxVariance: 0 },
                createdAt: new Date().toISOString()
            },
            {
                id: 'rule_2',
                name: 'Auto-Approve Small Variances',
                enabled: false,
                type: 'auto_approve',
                conditions: { maxVariance: 50 },
                createdAt: new Date().toISOString()
            },
            {
                id: 'rule_3',
                name: 'Alert on Large Variances',
                enabled: true,
                type: 'variance_alert',
                conditions: { maxVariance: 1000 },
                createdAt: new Date().toISOString()
            }
        ];

        document.addEventListener('DOMContentLoaded', () => {
            // Init State
            state.settlements = serverSettlements;
            loadLocalData();
            renderAll();
        });

        function loadLocalData() {
            const storedRules = localStorage.getItem('automationRules');
            const storedOps = localStorage.getItem('batchOperations');

            if (storedRules) {
                state.rules = JSON.parse(storedRules);
            } else {
                state.rules = defaultRules;
                localStorage.setItem('automationRules', JSON.stringify(defaultRules));
            }

            if (storedOps) {
                state.batchOps = JSON.parse(storedOps);
            }
        }

        function saveRules(rules) {
            state.rules = rules;
            localStorage.setItem('automationRules', JSON.stringify(rules));
            renderRules();
            renderStats();
        }

        function saveBatchOps(ops) {
            state.batchOps = ops;
            localStorage.setItem('batchOperations', JSON.stringify(ops));
            renderBatchOps();
        }

        // --- Rendering ---
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'LKR', minimumFractionDigits: 2 }).format(amount).replace('LKR', 'Rs.');
        }

        function renderAll() {
            renderStats();
            renderRules();
            renderSettlements();
            renderBatchOps();
            updateBatchControl();
        }

        function renderStats() {
            document.getElementById('stat-pending').textContent = state.settlements.filter(s => s.status === 'pending').length;
            document.getElementById('stat-reviewed').textContent = state.settlements.filter(s => s.status === 'reviewed').length;
            document.getElementById('stat-approved').textContent = state.settlements.filter(s => s.status === 'approved' && !s.glPosted).length;
            document.getElementById('stat-active-rules').textContent = state.rules.filter(r => r.enabled).length;
        }

        function renderRules() {
            const list = document.getElementById('rules-list');
            list.innerHTML = state.rules.map(r => `
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-sm transition-shadow">
                    <div class="flex items-center gap-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" ${r.enabled ? 'checked' : ''} onchange="toggleRule('${r.id}')">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                        </label>
                        <div>
                            <h4 class="text-gray-900 font-medium">${r.name}</h4>
                            <p class="text-gray-600 text-sm">
                                ${getRuleDescription(r)}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium ${r.enabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${r.enabled ? 'Active' : 'Inactive'}
                        </span>
                        <button onclick="deleteRule('${r.id}')" class="p-2 text-red-400 hover:text-red-600 rounded-full hover:bg-red-50">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function getRuleDescription(rule) {
            if (rule.type === 'auto_review') return `Auto-review settlements with variance ≤ Rs. ${rule.conditions.maxVariance}`;
            if (rule.type === 'auto_approve') return `Auto-approve settlements with variance ≤ Rs. ${rule.conditions.maxVariance}`;
            if (rule.type === 'variance_alert') return `Send variance alerts if > Rs. ${rule.conditions.maxVariance}`;
            return '';
        }

        function renderSettlements() {
            const list = document.getElementById('settlements-list');
            // Show pending and reviewed primarily for automation actions
            const actionable = state.settlements.filter(s => !s.glPosted);

            list.innerHTML = actionable.slice(0, 10).map(s => {
                const isSelected = state.selectedIds.includes(s.id);
                const varianceClass = Math.abs(s.cashVariance) > 0 ? 'text-red-600' : 'text-green-600';

                return `
                <div class="flex items-center gap-4 p-3 border rounded-lg transition-colors ${isSelected ? 'border-amber-500 bg-amber-50' : 'border-gray-200'}">
                    <input type="checkbox" class="w-5 h-5 text-amber-600 border-gray-300 rounded focus:ring-amber-500" 
                        ${isSelected ? 'checked' : ''} onchange="toggleSelection('${s.id}')">

                    <div class="flex-1">
                        <p class="text-gray-900 font-medium">${s.settlementNumber}</p>
                        <p class="text-gray-600 text-sm">${new Date(s.settlementDate).toLocaleDateString()}</p>
                    </div>

                    <div class="text-right">
                        <p class="text-gray-900">${formatCurrency(s.totalSales)}</p>
                        <p class="text-sm ${varianceClass}">Variance: ${formatCurrency(s.cashVariance)}</p>
                    </div>

                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize 
                        ${getStatusClass(s.status)}">
                        ${s.status}
                    </span>
                </div>
                `;
            }).join('');
        }

        function getStatusClass(status) {
            switch (status) {
                case 'approved': return 'bg-green-100 text-green-800';
                case 'reviewed': return 'bg-blue-100 text-blue-800';
                case 'pending': return 'bg-yellow-100 text-yellow-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function renderBatchOps() {
            const list = document.getElementById('batch-ops-list');
            const recent = [...state.batchOps].reverse().slice(0, 5);

            list.innerHTML = recent.map(op => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-gray-900 capitalize font-medium">${op.type.replace('_', ' ')}</p>
                        <p class="text-gray-600 text-xs">${new Date(op.completedAt || op.startedAt).toLocaleString()}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-900 text-sm">${op.processedItems} / ${op.totalItems} processed</p>
                        ${op.failedItems > 0 ? `<p class="text-red-600 text-xs">${op.failedItems} failed</p>` : ''}
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium ${op.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${op.status}
                    </span>
                </div>
            `).join('');
        }

        // --- Interaction ---
        function toggleSelection(id) {
            if (state.selectedIds.includes(id)) {
                state.selectedIds = state.selectedIds.filter(i => i !== id);
            } else {
                state.selectedIds.push(id);
            }
            renderSettlements();
            updateBatchControl();
        }

        function toggleSelectAll() {
            const actionable = state.settlements.filter(s => !s.glPosted);
            if (state.selectedIds.length === actionable.length) {
                state.selectedIds = [];
                document.getElementById('select-all-btn').textContent = 'Select All';
            } else {
                state.selectedIds = actionable.map(s => s.id);
                document.getElementById('select-all-btn').textContent = 'Deselect All';
            }
            renderSettlements();
            updateBatchControl();
        }

        function clearSelection() {
            state.selectedIds = [];
            document.getElementById('select-all-btn').textContent = 'Select All';
            renderSettlements();
            updateBatchControl();
        }

        function updateBatchControl() {
            const control = document.getElementById('batch-control');
            const count = document.getElementById('selected-count');

            if (state.selectedIds.length > 0) {
                control.classList.remove('hidden');
                count.textContent = state.selectedIds.length;
            } else {
                control.classList.add('hidden');
            }
        }

        // --- Rules CRUD ---
        function openRuleModal() {
            document.getElementById('create-rule-form').reset();
            document.getElementById('rule-modal').classList.remove('hidden');
        }

        function closeRuleModal() {
            document.getElementById('rule-modal').classList.add('hidden');
        }

        function handleCreateRule(e) {
            e.preventDefault();
            const newRule = {
                id: `rule_${Date.now()}`,
                name: document.getElementById('rule-name').value,
                type: document.getElementById('rule-type').value,
                enabled: document.getElementById('rule-enabled').checked,
                conditions: {
                    maxVariance: Number(document.getElementById('rule-variance').value)
                },
                createdAt: new Date().toISOString()
            };

            saveRules([...state.rules, newRule]);
            showToast('Rule created successfully');
            closeRuleModal();
        }

        function toggleRule(id) {
            const updated = state.rules.map(r =>
                r.id === id ? { ...r, enabled: !r.enabled } : r
            );
            saveRules(updated);
        }

        function deleteRule(id) {
            if (!confirm('Delete this rule?')) return;
            const updated = state.rules.filter(r => r.id !== id);
            saveRules(updated);
        }

        // --- Automation Logic ---
        function runAutomation() {
            const activeRules = state.rules.filter(r => r.enabled);
            if (activeRules.length === 0) {
                showToast('No active rules to run', 'info');
                return;
            }

            let processedCount = 0;
            let alertCount = 0;

            // Iterate settlements
            state.settlements = state.settlements.map(s => {
                if (s.status !== 'pending') return s; // Only process pending for now

                let updatedS = { ...s };
                let modified = false;

                activeRules.forEach(rule => {
                    const variance = Math.abs(s.cashVariance);

                    if (rule.type === 'variance_alert' && variance > rule.conditions.maxVariance) {
                        // Just alert/toast
                        alertCount++;
                        setTimeout(() => showToast(`High Variance Alert: ${s.settlementNumber} (Rs. ${variance})`, 'error'), 500 * alertCount);
                    }

                    // Logic priorities: Approve > Review
                    if (rule.type === 'auto_approve' && variance <= rule.conditions.maxVariance) {
                        updatedS.status = 'approved';
                        modified = true;
                    } else if (rule.type === 'auto_review' && variance <= rule.conditions.maxVariance && updatedS.status !== 'approved') {
                        updatedS.status = 'reviewed';
                        modified = true;
                    }
                });

                if (modified) processedCount++;
                return updatedS;
            });

            renderAll();
            if (processedCount > 0) {
                showToast(`Automation complete: ${processedCount} settlements processed`);
            } else {
                showToast('Automation complete: No changes made');
            }
        }

        // --- Batch Logic ---
        function openBatchModal() {
            document.getElementById('batch-count').textContent = state.selectedIds.length;
            document.getElementById('batch-modal').classList.remove('hidden');
        }

        function closeBatchModal() {
            document.getElementById('batch-modal').classList.add('hidden');
        }

        function handleBatchProcess() {
            const type = document.getElementById('batch-type').value;
            const total = state.selectedIds.length;
            let processed = 0;
            let failed = 0;

            // Simulate update based on type
            state.settlements = state.settlements.map(s => {
                if (!state.selectedIds.includes(s.id)) return s;

                // Simple validation logic
                if (type === 'review') {
                    processed++;
                    return { ...s, status: 'reviewed' };
                }
                if (type === 'approve') {
                    if (s.status === 'reviewed') {
                        processed++;
                        return { ...s, status: 'approved' };
                    } else {
                        failed++; // Can't approve pending directly in this strict flow example
                        return s;
                    }
                }
                if (type === 'post_gl') {
                    if (s.status === 'approved') {
                        processed++;
                        return { ...s, glPosted: true };
                    } else {
                        failed++;
                        return s;
                    }
                }
                return s;
            });

            // Log Op
            const newOp = {
                id: `batch_${Date.now()}`,
                type: type,
                status: failed === 0 ? 'completed' : (processed > 0 ? 'completed' : 'failed'), // mixed
                totalItems: total,
                processedItems: processed,
                failedItems: failed,
                completedAt: new Date().toISOString()
            };
            saveBatchOps([...state.batchOps, newOp]);

            renderAll();
            closeBatchModal();
            clearSelection();
            showToast(`Batch processed: ${processed} success, ${failed} failed`);
        }

        function showToast(message, type = 'success') {
            const div = document.createElement('div');
            const bg = type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-blue-600');
            div.className = `fixed top-4 right-4 ${bg} text-white px-6 py-3 rounded-lg shadow-lg z-[70] transition-opacity duration-300 transform translate-y-0`;
            div.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-info-circle'} mr-2"></i> ${message}`;
            document.body.appendChild(div);
            setTimeout(() => {
                div.style.opacity = '0';
                setTimeout(() => div.remove(), 300);
            }, 3000);
        }
    </script>
@endsection