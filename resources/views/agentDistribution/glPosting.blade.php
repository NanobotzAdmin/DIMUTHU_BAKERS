@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#EDEFF5]" id="gl-posting-app">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#F59E0B] to-[#D97706] rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="bi bi-check2-circle text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">General Ledger Posting</h1>
                            <p class="text-gray-500 text-xs sm:text-sm">Post approved agent settlements to the general ledger</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 max-w-[1800px] mx-auto">
        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Awaiting Posting -->
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi bi-clock-history text-yellow-600 text-xl"></i>
                    <span class="text-yellow-700 text-xs font-semibold uppercase tracking-wider">Awaiting Posting</span>
                </div>
                <p class="text-2xl font-bold text-yellow-900 mb-1" id="stat-unposted-count">0</p>
                <p class="text-yellow-700 text-xs" id="stat-unposted-val">Rs. 0 in sales</p>
            </div>

            <!-- Posted -->
            <div class="p-4 bg-green-50 border border-green-200 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi bi-check-circle text-green-600 text-xl"></i>
                    <span class="text-green-700 text-xs font-semibold uppercase tracking-wider">Posted</span>
                </div>
                <p class="text-2xl font-bold text-green-900 mb-1" id="stat-posted-count">0</p>
                <p class="text-green-700 text-xs" id="stat-posted-val">Rs. 0 in sales</p>
            </div>

            <!-- Cash to Post -->
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi bi-currency-dollar text-blue-600 text-xl"></i>
                    <span class="text-blue-700 text-xs font-semibold uppercase tracking-wider">Cash to Post</span>
                </div>
                <p class="text-xl font-bold text-blue-900 mb-1" id="stat-unposted-cash">Rs. 0</p>
            </div>

            <!-- Commission to Post -->
            <div class="p-4 bg-purple-50 border border-purple-200 rounded-xl shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <i class="bi bi-cash-stack text-purple-600 text-xl"></i>
                    <span class="text-purple-700 text-xs font-semibold uppercase tracking-wider">Commission to Post</span>
                </div>
                <p class="text-xl font-bold text-purple-900 mb-1" id="stat-unposted-comm">Rs. 0</p>
            </div>
        </div>

        <!-- Bulk Actions Panel (Hidden by default) -->
        <div id="bulk-actions-panel"
            class="hidden p-4 mb-6 bg-amber-50 border border-amber-200 rounded-xl shadow-sm transition-all">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <p class="text-gray-900 font-medium"><span id="selected-count" class="font-bold">0</span> settlement(s)
                        selected</p>
                    <p class="text-gray-600 text-sm">Total Value: <span id="selected-total-val" class="font-bold">Rs.
                            0</span></p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="openPostingModal()"
                        class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 font-medium shadow-sm flex items-center">
                        <i class="bi bi-check2-square mr-2"></i> Post Selected to GL
                    </button>
                    <button onclick="clearSelection()"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">
                        Clear Selection
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select id="filter-status" onchange="renderList()"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        <option value="approved">Approved (Not Posted)</option>
                        <option value="gl_posted">GL Posted</option>
                        <option value="all">All</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Date Range</label>
                    <select id="filter-date" onchange="renderList()"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        <option value="today">Today</option>
                        <option value="week">Last 7 Days</option>
                        <option value="month">Last 30 Days</option>
                        <option value="all" selected>All Time</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="toggleSelectAllUnposted()" id="btn-select-all"
                        class="w-full px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium">
                        Select All Unposted
                    </button>
                </div>
            </div>
        </div>

        <!-- List Container -->
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

    <!-- Posting Modal -->
    <div id="modal-posting" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 bg-opacity-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 transform transition-all scale-100">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 className="text-lg font-bold text-gray-900">Confirm GL Posting</h3>
                <button onclick="closePostingModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="bi bi-x-lg"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Posting Date</label>
                    <input type="date" id="post-date" class="w-full p-2 border border-gray-300 rounded-lg text-sm"
                        value="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                    <textarea id="post-notes" rows="3" class="w-full p-2 border border-gray-300 rounded-lg text-sm"
                        placeholder="Add optional memo..."></textarea>
                </div>
                <div class="bg-amber-50 p-3 rounded text-sm text-gray-700">
                    <i class="bi bi-info-circle mr-1"></i> You are posting <span id="modal-count" class="font-bold">0</span>
                    settlements.
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-3">
                <button onclick="closePostingModal()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                <button onclick="executePosting()"
                    class="px-4 py-2 bg-green-600 text-white hover:bg-green-700 rounded-lg shadow-sm font-medium">Confirm
                    Post</button>
            </div>
        </div>
    </div>

    <!-- Journal Preview Modal -->
    <div id="modal-preview" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 bg-opacity-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 h-[80vh] flex flex-col">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Journal Entry Preview</h3>
                <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="bi bi-x-lg"></i></button>
            </div>
            <div class="p-6 overflow-y-auto flex-1 bg-gray-50" id="preview-content">
                <!-- Injected -->
            </div>
            <div class="p-4 border-t border-gray-100 flex justify-end">
                <button onclick="closePreviewModal()"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">Close</button>
            </div>
        </div>
    </div>

    <script>
        const serverAgents = @json($agents ?? []);
        const serverSettlements = @json($settlements ?? []);

        const state = {
            agents: serverAgents,
            settlements: serverSettlements,
            selectedIds: []
        };

        document.addEventListener('DOMContentLoaded', () => {
            renderList();
        });

        function renderList() {
            // Filters
            const status = document.getElementById('filter-status').value;
            const dateRange = document.getElementById('filter-date').value;

            // Date Logic
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            let minDate = new Date(0);

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
                const matchesStatus = status === 'all' ||
                    (status === 'gl_posted' && s.glPosted) ||
                    (status === 'approved' && !s.glPosted);
                const matchesDate = new Date(s.settlementDate) >= minDate;
                return matchesStatus && matchesDate;
            });

            updateStats(state.settlements);
            updateBulkUI(); // update in case filter hides selected items (though logic holds IDs)

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
            const isPosted = s.glPosted;
            const isSelected = state.selectedIds.includes(s.id);
            const dateStr = new Date(s.settlementDate).toLocaleDateString();

            const borderColor = isSelected ? 'border-amber-400 ring-1 ring-amber-400' : 'border-gray-200';

            return `
                <div class="bg-white p-4 rounded-xl shadow-sm border ${borderColor} transition-all relative">
                    <div class="flex items-start gap-4">
                        ${!isPosted ? `
                            <div class="pt-1">
                                <input type="checkbox" onchange="toggleSelection('${s.id}')" ${isSelected ? 'checked' : ''} class="w-5 h-5 text-amber-600 focus:ring-amber-500 border-gray-300 rounded cursor-pointer">
                            </div>
                        ` : '<div class="w-5"></div>'}

                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                 <h3 class="text-gray-900 font-bold">${s.settlementNumber}</h3>
                                 ${isPosted ?
                    `<span class="px-2 py-0.5 rounded bg-green-100 text-green-800 text-xs font-semibold flex items-center gap-1"><i class="bi bi-check-circle-fill"></i> GL Posted</span>` :
                    `<span class="px-2 py-0.5 rounded bg-yellow-100 text-yellow-800 text-xs font-semibold flex items-center gap-1"><i class="bi bi-clock-fill"></i> Awaiting Posting</span>`
                }
                            </div>
                            <p class="text-gray-600 text-sm mb-3">
                                ${agent ? agent.agentName : 'Unknown'} (${agent ? agent.agentCode : ''}) - ${dateStr}
                            </p>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-gray-50 p-3 rounded-lg text-sm">
                                <div><span class="text-gray-500 text-xs">Total Sales</span><br><span class="font-medium">Rs. ${parseFloat(s.totalSales).toLocaleString()}</span></div>
                                <div><span class="text-gray-500 text-xs">Actual Cash</span><br><span class="font-medium">Rs. ${parseFloat(s.actualCash).toLocaleString()}</span></div>
                                <div><span class="text-gray-500 text-xs">Commission</span><br><span class="font-medium">Rs. ${parseFloat(s.commissionEarned).toLocaleString()}</span></div>
                                <div>
                                    <span class="text-gray-500 text-xs">Cash Variance</span><br>
                                    <span class="font-medium ${s.cashVariance == 0 ? 'text-gray-900' : (s.cashVariance > 0 ? 'text-blue-600' : 'text-red-600')}">
                                        ${parseFloat(s.cashVariance) > 0 ? '+' : ''}${parseFloat(s.cashVariance).toLocaleString()}
                                    </span>
                                </div>
                            </div>

                            ${isPosted && s.glJournalEntryId ? `
                                <div class="mt-3 text-xs text-green-700 bg-green-50 px-2 py-1 rounded inline-block">
                                    Journal Entry: <span class="font-mono font-bold">${s.glJournalEntryId}</span>
                                </div>
                            ` : ''}
                        </div>

                        <div class="flex flex-col gap-2">
                            <button onclick="previewJournal('${s.id}')" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 bg-white"><i class="bi bi-eye mr-2"></i>Preview</button>
                            <a href="/agent-distribution/settlements/${s.id}" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 bg-white text-center">Details</a>
                        </div>
                    </div>
                </div>
            `;
        }

        // --- Selection Logic ---
        function toggleSelection(id) {
            if (state.selectedIds.includes(id)) {
                state.selectedIds = state.selectedIds.filter(i => i !== id);
            } else {
                state.selectedIds.push(id);
            }
            renderList(); // Efficient enough for this list size
        }

        function toggleSelectAllUnposted() {
            const unposted = state.settlements.filter(s => !s.glPosted).map(s => s.id);

            // If all filtered unposted are already selected, deselect them. Otherwise, select all.
            const allSelected = unposted.every(id => state.selectedIds.includes(id));

            if (allSelected) {
                state.selectedIds = [];
            } else {
                state.selectedIds = [...unposted];
            }
            renderList();
        }

        function clearSelection() {
            state.selectedIds = [];
            renderList();
        }

        function updateBulkUI() {
            const panel = document.getElementById('bulk-actions-panel');
            const count = state.selectedIds.length;

            if (count > 0) {
                panel.classList.remove('hidden');
                document.getElementById('selected-count').textContent = count;

                const total = state.settlements
                    .filter(s => state.selectedIds.includes(s.id))
                    .reduce((sum, s) => sum + parseFloat(s.totalSales), 0);

                document.getElementById('selected-total-val').textContent = 'Rs. ' + total.toLocaleString();
            } else {
                panel.classList.add('hidden');
            }
        }

        // --- JE Generator Logic ---
        function generateEntries(s) {
            const agent = state.agents.find(a => a.id === s.agentId);
            const jeId = s.glJournalEntryId || `JE-TEMP-${Date.now()}`;
            const entries = [];

            // Entry 1: Master
            entries.push({
                no: jeId + '-1',
                desc: `Settlement ${s.settlementNumber} - ${agent.agentName}`,
                lines: [
                    { acct: '1010 - Cash in Hand', memo: `Cash from ${agent.agentName}`, dr: s.actualCash, cr: 0 },
                    { acct: '1210 - Agent AR - Credit', memo: 'Credit Sales', dr: s.amountDueToBakery, cr: 0 }, // Simplified
                    { acct: '2310 - Agent Accountability', memo: 'Settlement Relief', dr: 0, cr: parseFloat(s.actualCash) + parseFloat(s.amountDueToBakery) }
                ]
            });

            // Entry 2: Commission
            if (s.commissionEarned > 0) {
                entries.push({
                    no: jeId + '-2',
                    desc: `Commission - ${s.settlementNumber}`,
                    lines: [
                        { acct: '5210 - Agent Commission Exp', memo: `${agent.commissionRate}% comm`, dr: s.commissionEarned, cr: 0 },
                        { acct: '2320 - Agent Comm Payable', memo: `Payable to ${agent.agentCode}`, dr: 0, cr: s.commissionEarned }
                    ]
                });
            }

            // Entry 3: Variance
            if (s.cashVariance != 0) {
                const v = parseFloat(s.cashVariance);
                if (v < 0) { // Shortage
                    entries.push({
                        no: jeId + '-3',
                        desc: `Cash Shortage - ${s.settlementNumber}`,
                        lines: [
                            { acct: '5310 - Cash Shortage Exp', memo: s.varianceNotes || 'Shortage', dr: Math.abs(v), cr: 0 },
                            { acct: '1010 - Cash in Hand', memo: 'Shortage Adj', dr: 0, cr: Math.abs(v) }
                        ]
                    });
                } else { // Surplus
                    entries.push({
                        no: jeId + '-3',
                        desc: `Cash Surplus - ${s.settlementNumber}`,
                        lines: [
                            { acct: '1010 - Cash in Hand', memo: 'Surplus Adj', dr: v, cr: 0 },
                            { acct: '4910 - Cash Surplus Income', memo: s.varianceNotes || 'Surplus', dr: 0, cr: v }
                        ]
                    });
                }
            }
            return entries;
        }

        // --- Actions ---
        function openPostingModal() {
            document.getElementById('modal-count').textContent = state.selectedIds.length;
            document.getElementById('modal-posting').classList.remove('hidden');
        }

        function closePostingModal() {
            document.getElementById('modal-posting').classList.add('hidden');
        }

        function executePosting() {
            const count = state.selectedIds.length;
            // Mock update
            state.selectedIds.forEach(id => {
                const s = state.settlements.find(i => i.id === id);
                if (s) {
                    s.status = 'gl_posted';
                    s.glPosted = true;
                    s.glJournalEntryId = `JE-NEW-${Math.floor(Math.random() * 10000)}`;
                }
            });

            clearSelection();
            closePostingModal();
            Swal.fire('Success', `Successfully posted ${count} settlements to General Ledger.`, 'success');
        }

        function previewJournal(id) {
            const s = state.settlements.find(i => i.id === id);
            if (!s) return;

            const entries = generateEntries(s);
            const container = document.getElementById('preview-content');

            let html = '';
            entries.forEach(e => {
                html += `
                    <div class="bg-white border border-gray-200 rounded-lg p-4 mb-4 shadow-sm">
                        <div class="mb-2">
                            <span class="font-bold text-gray-900">${e.no}</span>
                            <p class="text-sm text-gray-600">${e.desc}</p>
                        </div>
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left py-2 px-2">Account</th>
                                    <th class="text-right py-2 px-2">Debit</th>
                                    <th class="text-right py-2 px-2">Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                let tDr = 0, tCr = 0;
                e.lines.forEach(line => {
                    tDr += parseFloat(line.dr);
                    tCr += parseFloat(line.cr);
                    html += `
                        <tr class="border-b border-gray-100 last:border-0">
                             <td class="py-2 px-2">
                                <div class="font-medium text-gray-900">${line.acct}</div>
                                <div class="text-xs text-gray-500">${line.memo}</div>
                             </td>
                             <td class="text-right py-2 px-2 text-gray-800">${line.dr > 0 ? parseFloat(line.dr).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '-'}</td>
                             <td class="text-right py-2 px-2 text-gray-800">${line.cr > 0 ? parseFloat(line.cr).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '-'}</td>
                        </tr>
                    `;
                });

                html += `
                            </tbody>
                            <tfoot class="bg-gray-50 font-bold border-t border-gray-200">
                                 <tr>
                                    <td class="py-2 px-2">Total</td>
                                    <td class="text-right py-2 px-2">${tDr.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                    <td class="text-right py-2 px-2">${tCr.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                                 </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
            });

            container.innerHTML = html;
            document.getElementById('modal-preview').classList.remove('hidden');
        }

        function closePreviewModal() {
            document.getElementById('modal-preview').classList.add('hidden');
        }

        function updateStats(data) {
            const unposted = data.filter(s => !s.glPosted);
            const posted = data.filter(s => s.glPosted);

            document.getElementById('stat-unposted-count').textContent = unposted.length;
            document.getElementById('stat-unposted-val').textContent = 'Rs. ' + unposted.reduce((a, b) => a + parseFloat(b.totalSales), 0).toLocaleString() + ' in sales';

            document.getElementById('stat-posted-count').textContent = posted.length;
            document.getElementById('stat-posted-val').textContent = 'Rs. ' + posted.reduce((a, b) => a + parseFloat(b.totalSales), 0).toLocaleString() + ' in sales';

            document.getElementById('stat-unposted-cash').textContent = 'Rs. ' + unposted.reduce((a, b) => a + parseFloat(b.actualCash), 0).toLocaleString();
            document.getElementById('stat-unposted-comm').textContent = 'Rs. ' + unposted.reduce((a, b) => a + parseFloat(b.commissionEarned), 0).toLocaleString();
        }
    </script>
@endsection