@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-[1600px] mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-900">
                    <i class="bi bi-journal-text text-amber-500"></i>
                    Journal Entries
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Create and manage general ledger journal entries
                </p>
            </div>
            <button onclick="journalEntryManager.openAddModal()"
                class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 flex items-center gap-2 text-sm font-medium shadow-sm transition-all hover:shadow">
                <i class="bi bi-plus-lg"></i> New Entry
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Total Entries</div>
                <div class="text-3xl font-bold text-gray-900" id="stat-total">-</div>
                <div class="text-xs text-gray-400 mt-1">All time</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Draft</div>
                <div class="text-3xl font-bold text-gray-500" id="stat-draft">-</div>
                <div class="text-xs text-gray-400 mt-1">Awaiting posting</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Posted</div>
                <div class="text-3xl font-bold text-green-600" id="stat-posted">-</div>
                <div class="text-xs text-gray-400 mt-1">Finalized entries</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Reversed</div>
                <div class="text-3xl font-bold text-red-600" id="stat-reversed">-</div>
                <div class="text-xs text-gray-400 mt-1">Cancelled entries</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="filter-search" oninput="journalEntryManager.filter()"
                    placeholder="Search by number, reference, or description..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>
            <select id="filter-status" onchange="journalEntryManager.filter()"
                class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500 bg-white">
                <option value="all">All Status</option>
                <option value="draft">Draft</option>
                <option value="posted">Posted</option>
                <option value="reversed">Reversed</option>
            </select>
            <select id="filter-type" onchange="journalEntryManager.filter()"
                class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500 bg-white">
                <option value="all">All Types</option>
                <option value="manual">Manual</option>
                <option value="system">System</option>
                <option value="adjustment">Adjustment</option>
                <option value="sales">Sales</option>
                <option value="purchase">Purchase</option>
                <option value="reversal">Reversal</option>
            </select>
            <div class="flex items-center gap-2">
                <input type="date" id="filter-start-date" onchange="journalEntryManager.filter()"
                    class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                <span class="text-gray-400">-</span>
                <input type="date" id="filter-end-date" onchange="journalEntryManager.filter()"
                    class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>
        </div>

        <!-- Entry List Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-bold text-gray-900">Journal Entries <span id="entry-count"
                        class="text-gray-500 font-normal ml-1">(-)</span></h2>
            </div>
            <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100 sticky top-0 bg-white z-10">
                        <tr>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Entry #</th>
                            <th class="px-6 py-3">Reference</th>
                            <th class="px-6 py-3 w-[25%]">Description</th>
                            <th class="px-6 py-3 text-right">Debit</th>
                            <th class="px-6 py-3 text-right">Credit</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="entries-table-body" class="divide-y divide-gray-100">
                        <!-- Dynamic Content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals -->

    <!-- Add Entry Modal -->
    <div id="add-entry-modal" class="fixed inset-0 bg-gray-900/75 bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-5xl max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Create Journal Entry</h3>
                    <p class="text-sm text-gray-500">Record a manual journal entry. Debits must equal credits.</p>
                </div>
                <button onclick="journalEntryManager.closeAddModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 space-y-6">
                <!-- Form Header -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date <span
                                class="text-red-500">*</span></label>
                        <input type="date" id="form-date"
                            class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference (Optional)</label>
                        <input type="text" id="form-reference" placeholder="e.g., Invoice #1234"
                            class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Entry Type</label>
                        <input type="text" value="Manual Entry" disabled
                            class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="form-description" placeholder="Describe the purpose of this entry..."
                        class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                </div>

                <!-- Lines -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-semibold text-gray-900">Journal Entry Lines</label>
                        <button onclick="journalEntryManager.addLine()"
                            class="px-3 py-1.5 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2 transition-colors">
                            <i class="bi bi-plus"></i> Add Line
                        </button>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200 text-xs text-gray-500 uppercase font-medium">
                                <tr>
                                    <th class="px-4 py-2 text-left w-[35%]">Account</th>
                                    <th class="px-4 py-2 text-left w-[30%]">Line Description</th>
                                    <th class="px-4 py-2 text-right w-[15%]">Debit</th>
                                    <th class="px-4 py-2 text-right w-[15%]">Credit</th>
                                    <th class="px-2 py-2 w-[5%]"></th>
                                </tr>
                            </thead>
                            <tbody id="form-lines-body" class="divide-y divide-gray-100">
                                <!-- Dynamic Lines -->
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-200 font-bold">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-right text-gray-900">Totals:</td>
                                    <td class="px-4 py-3 text-right font-mono" id="form-total-debit">0.00</td>
                                    <td class="px-4 py-3 text-right font-mono" id="form-total-credit">0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- Balance Check -->
                    <div id="balance-check-alert"
                        class="px-4 py-3 rounded-lg border text-sm font-medium flex items-center gap-2">
                        <!-- Dynamic Content -->
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                    <textarea id="form-notes" rows="3" placeholder="Add any additional notes or explanations..."
                        class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500"></textarea>
                </div>

            </div>

            <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl flex justify-end gap-3">
                <button onclick="journalEntryManager.closeAddModal()"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button onclick="journalEntryManager.saveEntry()" id="btn-save-entry"
                    class="px-6 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">Create
                    Entry</button>
            </div>
        </div>
    </div>

    <!-- View Detail Modal -->
    <div id="view-detail-modal"
        class="fixed inset-0 bg-gray-900/75 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Journal Entry Details</h3>
                    <p class="text-sm text-gray-500" id="detail-entry-number">-</p>
                </div>
                <button onclick="journalEntryManager.closeDetailModal()"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto" id="detail-content">
                <!-- Dynamic Content -->
            </div>
            <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-xl flex justify-end">
                <button onclick="journalEntryManager.closeDetailModal()"
                    class="px-4 py-2 border border-gray-300 bg-white rounded-lg text-gray-700 hover:bg-gray-50">Close</button>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const journalEntryManager = {
            data: {
                entries: @json($journalEntries),
                accounts: @json($glAccounts)
            },
            state: {
                searchQuery: '',
                statusFilter: 'all',
                typeFilter: 'all',
                startDate: '',
                endDate: '',
                formLines: []
            },

            init() {
                this.renderEntries();
                this.updateStats();
                // Set default form date for modal (when opened, but good to have)
                document.getElementById('form-date').value = new Date().toISOString().split('T')[0];
            },

            filter() {
                this.state.searchQuery = document.getElementById('filter-search').value.toLowerCase();
                this.state.statusFilter = document.getElementById('filter-status').value;
                this.state.typeFilter = document.getElementById('filter-type').value;
                this.state.startDate = document.getElementById('filter-start-date').value;
                this.state.endDate = document.getElementById('filter-end-date').value;
                this.renderEntries();
            },

            getFilteredEntries() {
                return this.data.entries.filter(e => {
                    const matchSearch = e.entryNumber.toLowerCase().includes(this.state.searchQuery) ||
                        (e.reference && e.reference.toLowerCase().includes(this.state.searchQuery)) ||
                        e.description.toLowerCase().includes(this.state.searchQuery);

                    const matchStatus = this.state.statusFilter === 'all' || e.status === this.state.statusFilter;
                    const matchType = this.state.typeFilter === 'all' || e.type === this.state.typeFilter;

                    let matchDate = true;
                    if (this.state.startDate) matchDate = matchDate && e.date >= this.state.startDate;
                    if (this.state.endDate) matchDate = matchDate && e.date <= this.state.endDate;

                    return matchSearch && matchStatus && matchType && matchDate;
                }).sort((a, b) => new Date(b.date) - new Date(a.date)); // Sort Desc
            },

            renderEntries() {
                const entries = this.getFilteredEntries();
                document.getElementById('entry-count').innerText = `(${entries.length})`;

                const tbody = document.getElementById('entries-table-body');
                if (entries.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="9" class="text-center py-12 text-gray-500">
                            <i class="bi bi-journal-x text-3xl mb-3 block text-gray-300"></i> No journal entries found
                         </td></tr>`;
                    return;
                }

                tbody.innerHTML = entries.map(e => {
                    const totalDebit = e.lines.reduce((sum, l) => sum + parseFloat(l.debit), 0);
                    const totalCredit = e.lines.reduce((sum, l) => sum + parseFloat(l.credit), 0);

                    let statusBadge = '';
                    if (e.status === 'posted') statusBadge = '<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="bi bi-check-circle mr-1"></i>Posted</span>';
                    else if (e.status === 'draft') statusBadge = '<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600"><i class="bi bi-pencil mr-1"></i>Draft</span>';
                    else if (e.status === 'reversed') statusBadge = '<span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="bi bi-x-circle mr-1"></i>Reversed</span>';

                    let actions = `<button onclick="journalEntryManager.viewDetail('${e.id}')" class="text-gray-500 hover:text-blue-600 px-2 py-1 rounded hover:bg-gray-100 transition-colors" title="View"><i class="bi bi-eye"></i></button>`;

                    if (e.status === 'draft') {
                        actions += `<button onclick="journalEntryManager.postEntry('${e.id}')" class="text-green-600 hover:text-green-800 px-2 py-1 rounded hover:bg-green-50 transition-colors ml-1" title="Post"><i class="bi bi-check-lg"></i></button>`;
                        actions += `<button onclick="journalEntryManager.deleteEntry('${e.id}')" class="text-red-500 hover:text-red-700 px-2 py-1 rounded hover:bg-red-50 transition-colors ml-1" title="Delete"><i class="bi bi-trash"></i></button>`;
                    } else if (e.status === 'posted' && !e.reversedEntryId && e.type !== 'reversal') {
                        actions += `<button onclick="journalEntryManager.reverseEntry('${e.id}')" class="text-orange-500 hover:text-orange-700 px-2 py-1 rounded hover:bg-orange-50 transition-colors ml-1" title="Reverse"><i class="bi bi-arrow-counterclockwise"></i></button>`;
                    }

                    return `
                            <tr class="hover:bg-gray-50 group border-b border-gray-100 last:border-0 transition-colors">
                                <td class="px-6 py-3 text-gray-600">${this.formatDate(e.date)}</td>
                                <td class="px-6 py-3 font-mono text-blue-600 font-medium">${e.entryNumber}</td>
                                <td class="px-6 py-3 text-gray-500">${e.reference || '-'}</td>
                                <td class="px-6 py-3 text-gray-800">
                                    <div>${e.description}</div>
                                    ${e.reversedEntryId ? `<div class="text-xs text-gray-400 mt-0.5">Reverses: ${this.getEntryNum(e.reversedEntryId)}</div>` : ''}
                                </td>
                                <td class="px-6 py-3 text-right font-mono text-green-600">${this.fmt(totalDebit)}</td>
                                <td class="px-6 py-3 text-right font-mono text-red-600">${this.fmt(totalCredit)}</td>
                                <td class="px-6 py-3"><span class="capitalize text-xs px-2 py-0.5 rounded bg-gray-50 border border-gray-200 text-gray-600">${e.type}</span></td>
                                <td class="px-6 py-3">${statusBadge}</td>
                                <td class="px-6 py-3 text-right whitespace-nowrap opacity-100 group-hover:opacity-100 transition-opacity">
                                    ${actions}
                                </td>
                            </tr>
                        `;
                }).join('');
            },

            updateStats() {
                document.getElementById('stat-total').innerText = this.data.entries.length;
                document.getElementById('stat-draft').innerText = this.data.entries.filter(e => e.status === 'draft').length;
                document.getElementById('stat-posted').innerText = this.data.entries.filter(e => e.status === 'posted').length;
                document.getElementById('stat-reversed').innerText = this.data.entries.filter(e => e.status === 'reversed').length;
            },

            // --- Form Logic ---
            openAddModal() {
                document.getElementById('add-entry-modal').classList.remove('hidden');
                // Reset Form
                document.getElementById('form-date').value = new Date().toISOString().split('T')[0];
                document.getElementById('form-reference').value = '';
                document.getElementById('form-description').value = '';
                document.getElementById('form-notes').value = '';
                this.state.formLines = [
                    { accountId: '', description: '', debit: 0, credit: 0 },
                    { accountId: '', description: '', debit: 0, credit: 0 }
                ];
                this.renderFormLines();
            },

            closeAddModal() {
                document.getElementById('add-entry-modal').classList.add('hidden');
            },

            addLine() {
                this.state.formLines.push({ accountId: '', description: '', debit: 0, credit: 0 });
                this.renderFormLines();
            },

            removeLine(index) {
                if (this.state.formLines.length <= 2) {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: 'Journal entry must have at least 2 lines', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
                    return;
                }
                this.state.formLines.splice(index, 1);
                this.renderFormLines();
            },

            updateLine(index, field, value) {
                this.state.formLines[index][field] = value;
                if (field === 'debit' && parseFloat(value) > 0) this.state.formLines[index].credit = 0;
                if (field === 'credit' && parseFloat(value) > 0) this.state.formLines[index].debit = 0;
                this.renderFormLines();
            },

            renderFormLines() {
                const tbody = document.getElementById('form-lines-body');
                const accountOptions = this.data.accounts.map(a => `<option value="${a.id}">${a.code} - ${a.name}</option>`).join('');

                tbody.innerHTML = this.state.formLines.map((line, idx) => `
                        <tr class="group hover:bg-gray-50">
                            <td class="px-2 py-2">
                                <select onchange="journalEntryManager.updateLine(${idx}, 'accountId', this.value)" class="w-full p-2 bg-gray-50 border border-gray-300 rounded text-sm focus:ring-amber-500 focus:border-amber-500 py-1">
                                    <option value="">Select Account</option>
                                    ${this.data.accounts.map(a => `<option value="${a.id}" ${line.accountId == a.id ? 'selected' : ''}>${a.code} - ${a.name}</option>`).join('')}
                                </select>
                            </td>
                            <td class="px-2 py-2">
                                <input type="text" value="${line.description}" oninput="journalEntryManager.updateLine(${idx}, 'description', this.value)" placeholder="Line description..." class="w-full p-2 bg-gray-50 border border-gray-300 rounded text-sm focus:ring-amber-500 focus:border-amber-500 py-1">
                            </td>
                            <td class="px-2 py-2">
                                 <input type="number" step="0.01" min="0" value="${line.debit || ''}" oninput="journalEntryManager.updateLine(${idx}, 'debit', this.value)" placeholder="0.00" class="w-full p-2 bg-gray-50 border border-gray-300 rounded text-sm focus:ring-amber-500 focus:border-amber-500 py-1 text-right font-mono">
                            </td>
                            <td class="px-2 py-2">
                                 <input type="number" step="0.01" min="0" value="${line.credit || ''}" oninput="journalEntryManager.updateLine(${idx}, 'credit', this.value)" placeholder="0.00" class="w-full p-2 bg-gray-50 border border-gray-300 rounded text-sm focus:ring-amber-500 focus:border-amber-500 py-1 text-right font-mono">
                            </td>
                            <td class="px-2 py-2 text-center">
                                <button onclick="journalEntryManager.removeLine(${idx})" class="text-red-400 hover:text-red-600 transition-colors"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    `).join('');

                this.validateForm();
            },

            validateForm() {
                const totalDr = this.state.formLines.reduce((sum, l) => sum + (parseFloat(l.debit) || 0), 0);
                const totalCr = this.state.formLines.reduce((sum, l) => sum + (parseFloat(l.credit) || 0), 0);
                const diff = Math.abs(totalDr - totalCr);
                const isBalanced = diff < 0.01;

                document.getElementById('form-total-debit').innerText = this.fmt(totalDr);
                document.getElementById('form-total-credit').innerText = this.fmt(totalCr);

                const alertEl = document.getElementById('balance-check-alert');
                const saveBtn = document.getElementById('btn-save-entry');

                if (isBalanced) {
                    alertEl.className = "px-4 py-3 rounded-lg border text-sm font-medium flex items-center gap-2 bg-green-50 text-green-700 border-green-200 mt-2";
                    alertEl.innerHTML = '<i class="bi bi-check-circle-fill"></i> Entry is balanced (Debits = Credits)';
                    saveBtn.disabled = false;
                } else {
                    alertEl.className = "px-4 py-3 rounded-lg border text-sm font-medium flex items-center gap-2 bg-red-50 text-red-700 border-red-200 mt-2";
                    alertEl.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Entry is not balanced. Difference: ${this.fmt(diff)} ${totalDr > totalCr ? '(Debits > Credits)' : '(Credits > Debits)'}`;
                    saveBtn.disabled = true;
                }
            },

            saveEntry() {
                // Final validation (Required fields)
                if (!document.getElementById('form-date').value || !document.getElementById('form-description').value) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Please fill in required fields (Date, Description)' });
                    return;
                }
                const validLines = this.state.formLines.filter(l => l.accountId && (parseFloat(l.debit) > 0 || parseFloat(l.credit) > 0));
                if (validLines.length < 2) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Journal entry must have at least 2 valid lines' });
                    return;
                }

                // Construct Object
                const newEntry = {
                    id: `JE-${Math.floor(Math.random() * 10000)}`,
                    entryNumber: `JE-${new Date().getFullYear()}-${String(this.data.entries.length + 1).padStart(3, '0')}`,
                    reference: document.getElementById('form-reference').value,
                    date: document.getElementById('form-date').value,
                    description: document.getElementById('form-description').value,
                    notes: document.getElementById('form-notes').value,
                    type: 'manual',
                    status: 'draft', // Default to draft
                    createdBy: 'Current User',
                    createdAt: new Date().toISOString().replace('T', ' ').split('.')[0],
                    lines: validLines.map(l => ({
                        ...l, accountCode: this.data.accounts.find(a => a.id == l.accountId).code,
                        description: l.description || document.getElementById('form-description').value
                    }))
                };

                // Add to List
                this.data.entries.unshift(newEntry);
                this.renderEntries();
                this.updateStats();
                this.closeAddModal();
                Swal.fire({ icon: 'success', title: 'Success', text: 'Journal entry created successfully', timer: 2000, showConfirmButton: false });
            },

            // --- Actions ---
            getEntryNum(id) {
                const e = this.data.entries.find(x => x.id === id);
                return e ? e.entryNumber : id;
            },

            viewDetail(id) {
                const entry = this.data.entries.find(e => e.id === id);
                if (!entry) return;

                document.getElementById('detail-entry-number').innerText = entry.entryNumber;
                const container = document.getElementById('detail-content');

                // Render Details
                container.innerHTML = `
                        <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg mb-6">
                            <div><p class="text-xs text-gray-500">Date</p><p class="font-medium text-sm">${this.formatDate(entry.date)}</p></div>
                            <div><p class="text-xs text-gray-500">Reference</p><p class="font-medium text-sm">${entry.reference || '-'}</p></div>
                            <div><p class="text-xs text-gray-500">Type</p><p class="font-medium text-sm capitalize">${entry.type}</p></div>
                            <div><p class="text-xs text-gray-500">Status</p><span class="capitalize text-xs font-bold px-2 py-0.5 rounded bg-white border border-gray-200">${entry.status}</span></div>
                            <div class="col-span-2"><p class="text-xs text-gray-500">Description</p><p class="font-medium text-sm">${entry.description}</p></div>
                            ${entry.notes ? `<div class="col-span-2"><p class="text-xs text-gray-500">Notes</p><p class="text-sm italic">${entry.notes}</p></div>` : ''}
                        </div>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500">
                                    <tr><th class="px-4 py-2 text-left">Account</th><th class="px-4 py-2 text-left">Description</th><th class="px-4 py-2 text-right">Debit</th><th class="px-4 py-2 text-right">Credit</th></tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    ${entry.lines.map(l => {
                    const acc = this.data.accounts.find(a => a.id == l.accountId) || { code: l.accountCode, name: l.accountName || '' };
                    return `
                                            <tr>
                                                <td class="px-4 py-2 font-mono text-gray-700">${acc.code} <span class="text-gray-400">-</span> ${acc.name}</td>
                                                <td class="px-4 py-2 text-gray-500">${l.description}</td>
                                                <td class="px-4 py-2 text-right font-mono text-green-600">${parseFloat(l.debit) > 0 ? this.fmt(l.debit) : '-'}</td>
                                                <td class="px-4 py-2 text-right font-mono text-red-600">${parseFloat(l.credit) > 0 ? this.fmt(l.credit) : '-'}</td>
                                            </tr>`;
                }).join('')}
                                </tbody>
                                <tfoot class="bg-gray-50 font-bold border-t border-gray-200">
                                    <tr>
                                        <td colspan="2" class="px-4 py-2 text-right">Totals:</td>
                                        <td class="px-4 py-2 text-right text-green-700">${this.fmt(entry.lines.reduce((s, l) => s + parseFloat(l.debit), 0))}</td>
                                        <td class="px-4 py-2 text-right text-red-700">${this.fmt(entry.lines.reduce((s, l) => s + parseFloat(l.credit), 0))}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mt-4 grid grid-cols-3 gap-4 text-xs text-gray-500">
                             <div>Created By: <span class="text-gray-800 font-medium">${entry.createdBy}</span></div>
                             <div>At: <span class="text-gray-800 font-medium">${entry.createdAt}</span></div>
                             ${entry.postedAt ? `<div>Posted At: <span class="text-gray-800 font-medium">${entry.postedAt}</span></div>` : ''}
                        </div>
                     `;

                document.getElementById('view-detail-modal').classList.remove('hidden');
            },

            closeDetailModal() {
                document.getElementById('view-detail-modal').classList.add('hidden');
            },

            postEntry(id) {
                Swal.fire({
                    title: 'Post Journal Entry?',
                    text: 'This will update account balances and lock the entry.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    confirmButtonText: 'Yes, Post Entry'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const idx = this.data.entries.findIndex(e => e.id === id);
                        if (idx > -1) {
                            this.data.entries[idx].status = 'posted';
                            this.data.entries[idx].postedAt = new Date().toISOString().replace('T', ' ').split('.')[0];
                            this.renderEntries();
                            this.updateStats();
                            Swal.fire('Posted!', 'Journal entry has been posted.', 'success');
                        }
                    }
                })
            },

            reverseEntry(id) {
                Swal.fire({
                    title: 'Reverse Journal Entry?',
                    text: 'This will create a new reversal entry to offset this one.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#F59E0B',
                    confirmButtonText: 'Yes, Reverse Entry'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const original = this.data.entries.find(e => e.id === id);
                        if (original) {
                            // Update Original
                            original.status = 'reversed'; // Visual only? Usually stays posted but marked. Design says status changes to reversed.

                            // Creates New Entry
                            const reversal = {
                                ...JSON.parse(JSON.stringify(original)), // Deep Clone
                                id: `JE-${Math.floor(Math.random() * 10000)}`,
                                entryNumber: `JE-${new Date().getFullYear()}-${String(this.data.entries.length + 1).padStart(3, '0')}`,
                                description: `Reversal of ${original.entryNumber}`,
                                reference: `REV-${original.entryNumber}`,
                                type: 'reversal',
                                status: 'reversed',
                                reversedEntryId: original.id,
                                createdAt: new Date().toISOString().replace('T', ' ').split('.')[0],
                                postedAt: new Date().toISOString().replace('T', ' ').split('.')[0],
                                lines: original.lines.map(l => ({
                                    ...l,
                                    debit: l.credit, // Swap
                                    credit: l.debit
                                }))
                            };

                            this.data.entries.unshift(reversal);
                            this.renderEntries();
                            this.updateStats();
                            Swal.fire('Reversed!', 'Journal entry has been reversed.', 'success');
                        }
                    }
                })
            },

            deleteEntry(id) {
                Swal.fire({
                    title: 'Delete Entry?',
                    text: 'Are you sure you want to delete this draft entry?',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    confirmButtonText: 'Yes, Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.data.entries = this.data.entries.filter(e => e.id !== id);
                        this.renderEntries();
                        this.updateStats();
                        Swal.fire('Deleted', 'Draft entry has been removed.', 'success');
                    }
                })
            },

            // --- Helpers ---
            fmt(num) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(num);
            },
            formatDate(dateStr) {
                if (!dateStr) return '-';
                const d = new Date(dateStr);
                return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            journalEntryManager.init();
        });
    </script>
@endsection