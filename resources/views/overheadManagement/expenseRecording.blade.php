@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 p-6" id="expense-recording-container">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3 text-gray-900">
                    <i class="bi bi-receipt text-amber-500 text-4xl"></i>
                    Overhead Expense Recording
                </h1>
                <p class="text-gray-600 mt-1">
                    Record overhead expenses with automatic GL journal entry generation
                </p>
            </div>
            <button onclick="expenseManager.toggleAddForm()"
                class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-lg flex items-center gap-2 font-medium transition-colors shadow-sm">
                <i class="bi bi-plus-lg"></i>
                Record New Expense
            </button>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <div class="text-sm text-gray-500 mb-1">Total Expenses</div>
                <div class="text-3xl font-bold text-gray-900" id="stat-total-count">0</div>
                <div class="text-xs text-gray-400 mt-1">All overhead expenses</div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <div class="text-sm text-gray-500 mb-1">Total Amount</div>
                <div class="text-3xl font-bold text-gray-900" id="stat-total-amount">Rs 0</div>
                <div class="text-xs text-gray-400 mt-1">Sum of all expenses</div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200" id="card-je-status">
                <div class="text-sm text-gray-500 mb-1">With Journal Entries</div>
                <div class="text-3xl font-bold flex items-center gap-2" id="stat-je-count">
                    0/0
                </div>
                <div class="text-xs text-gray-400 mt-1">GL integration status</div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <div class="text-sm text-gray-500 mb-1">Pending JE Generation</div>
                <div class="text-3xl font-bold text-gray-900" id="stat-pending-count">0</div>
                <div class="text-xs text-gray-400 mt-1">Need journal entries</div>
            </div>
        </div>

        <!-- Batch Actions -->
        <div id="batch-action-card" class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6 hidden">
            <h3 class="text-yellow-900 font-bold flex items-center gap-2 text-lg mb-1">
                <i class="bi bi-exclamation-circle"></i>
                <span id="batch-count-text">0 Expenses Without Journal Entries</span>
            </h3>
            <p class="text-yellow-700 text-sm mb-4">
                Generate journal entries for expenses that don't have them yet
            </p>
            <div class="flex gap-3">
                <button onclick="expenseManager.handleBatchGenerateJE()"
                    class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors shadow-sm text-sm">
                    <i class="bi bi-file-text"></i>
                    Generate Journal Entries
                </button>
                <button onclick="window.location.href='/overhead/gl-mapping'"
                    class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors text-sm">
                    <i class="bi bi-book"></i>
                    Check GL Account Mapping
                </button>
            </div>
        </div>

        <!-- Add Expense Form -->
        <div id="add-form-card" class="bg-white border-t-4 border-amber-500 rounded-xl shadow-sm mb-6 hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Record New Overhead Expense</h2>
                <p class="text-sm text-gray-500">Enter expense details and optionally auto-generate journal entry</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expense Category <span
                                class="text-red-500">*</span></label>
                        <select id="frm-category" onchange="expenseManager.updateJePreview()"
                            class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                            <option value="utilities">Utilities</option>
                            <option value="rent">Rent & Facility</option>
                            <option value="salaries">Salaries</option>
                            <option value="equipment">Equipment</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="marketing">Marketing</option>
                            <option value="insurance">Insurance</option>
                            <option value="administrative">Administrative</option>
                            <option value="transport">Transportation</option>
                            <option value="miscellaneous">Miscellaneous</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expense Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="frm-name" placeholder="e.g., Electricity Bill - December"
                            class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (Rs.) <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="frm-amount" placeholder="0.00" step="0.01"
                            oninput="expenseManager.updateJePreview()"
                            class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" id="frm-date"
                            class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                        <select id="frm-status" onchange="expenseManager.updateJePreview()"
                            class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="scheduled">Scheduled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                        <input type="text" id="frm-vendor" placeholder="Vendor name"
                            class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="frm-desc" rows="2" placeholder="Additional details..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"></textarea>
                    </div>
                </div>

                <!-- GL Integration Options -->
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <h4 class="font-medium text-gray-900">GL Integration Options</h4>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="frm-auto-generate" checked onchange="expenseManager.toggleAutoPost()"
                            class="w-4 h-4 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                        <label for="frm-auto-generate" class="text-sm font-medium text-gray-700">Auto-generate journal entry
                            when expense is recorded</label>
                    </div>

                    <div id="auto-post-container" class="flex items-center gap-2 ml-6">
                        <input type="checkbox" id="frm-auto-post"
                            class="w-4 h-4 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                        <label for="frm-auto-post" class="text-sm font-medium text-gray-700">Auto-post journal entry
                            (otherwise saved as draft)</label>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-900">
                        <p><strong>Journal Entry Preview:</strong></p>
                        <p class="mt-1" id="je-preview-dr">DR: Utilities Expense - Rs. 0</p>
                        <p id="je-preview-cr">CR: Cash on Hand - Rs. 0</p>
                    </div>
                </div>

                <div class="flex gap-3 justify-end pt-2">
                    <button onclick="expenseManager.toggleAddForm()"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">Cancel</button>
                    <button onclick="expenseManager.handleAddExpense()"
                        class="px-4 py-2 bg-amber-500 text-white rounded-lg font-medium hover:bg-amber-600 shadow-sm flex items-center gap-2">
                        <i class="bi bi-plus-lg"></i>
                        Record Expense
                    </button>
                </div>
            </div>
        </div>

        <!-- Expenses List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Recent Overhead Expenses</h2>
                <p class="text-sm text-gray-500">All recorded expenses with GL integration status</p>
            </div>
            <div class="p-6">
                <div id="expenses-list" class="space-y-3">
                    <!-- Dynamic Content -->
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mt-6 text-sm text-blue-900 space-y-2">
            <h3 class="font-bold text-lg mb-2">How Automatic Journal Entry Generation Works</h3>
            <p><strong>When you record an overhead expense:</strong></p>
            <p>1. If "Auto-generate journal entry" is enabled, a journal entry is automatically created</p>
            <p>2. The expense account is determined from the GL account mapping for the expense category</p>
            <p>3. If the expense is marked as "Paid", it credits Cash on Hand (1100-001)</p>
            <p>4. If the expense is "Pending" or "Scheduled", it credits Accounts Payable (2100-001)</p>
            <p>5. The journal entry is linked to the expense for easy tracking and audit trail</p>
            <div class="mt-4 pt-4 border-t border-blue-200">
                <strong>Example:</strong> Recording a Rs. 15,000 electricity bill (paid) generates:<br>
                DR: Utilities Expense (5100-001) Rs. 15,000<br>
                CR: Cash on Hand (1100-001) Rs. 15,000
            </div>
        </div>

    </div>

    <script>
        const expenseManager = {
            data: {
                expenses: @json($overheadExpenses)
            },
            state: {
                showAddForm: false
            },

            init() {
                // Set default date
                document.getElementById('frm-date').valueAsDate = new Date();
                this.updateStats();
                this.renderList();
                this.updateJePreview();
            },

            toggleAddForm() {
                this.state.showAddForm = !this.state.showAddForm;
                const form = document.getElementById('add-form-card');
                if (this.state.showAddForm) {
                    form.classList.remove('hidden');
                } else {
                    form.classList.add('hidden');
                    this.resetForm();
                }
            },

            resetForm() {
                document.getElementById('frm-category').value = 'utilities';
                document.getElementById('frm-name').value = '';
                document.getElementById('frm-amount').value = '';
                document.getElementById('frm-date').valueAsDate = new Date();
                document.getElementById('frm-status').value = 'paid';
                document.getElementById('frm-vendor').value = '';
                document.getElementById('frm-desc').value = '';
                document.getElementById('frm-auto-generate').checked = true;
                this.toggleAutoPost();
                this.updateJePreview();
            },

            toggleAutoPost() {
                const autoGen = document.getElementById('frm-auto-generate').checked;
                const container = document.getElementById('auto-post-container');
                if (autoGen) {
                    container.classList.remove('invisible');
                } else {
                    container.classList.add('invisible');
                }
            },

            updateJePreview() {
                const catIdx = document.getElementById('frm-category');
                const cat = catIdx.options[catIdx.selectedIndex].text;
                const amt = Number(document.getElementById('frm-amount').value).toLocaleString();
                const status = document.getElementById('frm-status').value;

                const creditAcc = status === 'paid' ? 'Cash on Hand' : 'Accounts Payable';

                document.getElementById('je-preview-dr').innerText = `DR: ${cat} Expense - Rs. ${amt}`;
                document.getElementById('je-preview-cr').innerText = `CR: ${creditAcc} - Rs. ${amt}`;
            },

            updateStats() {
                const total = this.data.expenses.length;
                const totalAmount = this.data.expenses.reduce((sum, e) => sum + Number(e.amount), 0);
                const withJe = this.data.expenses.filter(e => e.glJournalEntryId).length;
                const pending = total - withJe;

                document.getElementById('stat-total-count').innerText = total;
                document.getElementById('stat-total-amount').innerText = 'Rs ' + totalAmount.toLocaleString();

                const jeStatusEl = document.getElementById('card-je-status');
                const jeCountEl = document.getElementById('stat-je-count');

                jeCountEl.innerHTML = `${withJe === total && total > 0 ? '<i class="bi bi-check-circle text-green-600"></i>' : '<i class="bi bi-exclamation-circle text-yellow-500"></i>'} ${withJe}/${total}`;

                if (withJe === total && total > 0) {
                    jeStatusEl.className = "bg-green-50 p-4 rounded-xl shadow-sm border border-green-200";
                } else {
                    jeStatusEl.className = "bg-yellow-50 p-4 rounded-xl shadow-sm border border-yellow-200";
                }

                document.getElementById('stat-pending-count').innerText = pending;

                const batchCard = document.getElementById('batch-action-card');
                if (pending > 0) {
                    batchCard.classList.remove('hidden');
                    document.getElementById('batch-count-text').innerText = `${pending} Expenses Without Journal Entries`;
                } else {
                    batchCard.classList.add('hidden');
                }
            },

            renderList() {
                const container = document.getElementById('expenses-list');

                if (this.data.expenses.length === 0) {
                    container.innerHTML = `
                             <div class="text-center py-12 text-gray-500">
                                <i class="bi bi-receipt text-6xl text-gray-300 mb-4 block"></i>
                                <p>No overhead expenses recorded yet</p>
                                <p class="text-sm mt-1">Click "Record New Expense" to get started</p>
                            </div>
                        `;
                    return;
                }

                container.innerHTML = this.data.expenses.map(exp => {
                    const hasJe = !!exp.glJournalEntryId;
                    let statusClasses = '';
                    if (exp.status === 'paid') statusClasses = 'bg-green-100 text-green-800 border-green-200';
                    else if (exp.status === 'pending') statusClasses = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                    else statusClasses = 'bg-gray-100 text-gray-800 border-gray-200';

                    return `
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-600 transition-colors bg-white">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="font-semibold text-gray-900">${exp.name}</h3>
                                            <span class="px-2 py-0.5 rounded text-xs border bg-white text-gray-600 capitalize">${exp.category}</span>
                                            <span class="px-2 py-0.5 rounded text-xs border capitalize ${statusClasses}">${exp.status}</span>
                                        </div>

                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <span class="font-medium text-gray-900">Rs. ${Number(exp.amount).toLocaleString()}</span>
                                            <span>•</span>
                                            <span>${exp.date}</span>
                                            ${exp.vendor ? `<span>•</span><span>${exp.vendor}</span>` : ''}
                                        </div>

                                        ${exp.description ? `<p class="text-sm text-gray-500 mt-1">${exp.description}</p>` : ''}

                                        <div class="mt-2 flex items-center gap-2">
                                             ${hasJe ? `
                                                <div class="flex items-center gap-2">
                                                    <i class="bi bi-check-circle-fill text-green-600 text-sm"></i>
                                                    <span class="text-sm text-green-700 font-medium">
                                                        Journal Entry: ${exp.glJournalEntryId}
                                                    </span>
                                                </div>
                                             ` : `
                                                 <div class="flex items-center gap-2">
                                                    <i class="bi bi-exclamation-circle-fill text-yellow-500 text-sm"></i>
                                                    <span class="text-sm text-yellow-700">No journal entry</span>
                                                </div>
                                             `}
                                        </div>
                                    </div>

                                    <div class="flex gap-2 ml-4">
                                         ${hasJe ? `
                                            <button onclick="expenseManager.viewJe('${exp.glJournalEntryId}')"
                                                class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-700"
                                                title="View Journal Entry">
                                                <i class="bi bi-book"></i>
                                            </button>
                                         ` : ''}
                                        <button onclick="expenseManager.deleteExpense('${exp.id}')"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-red-500 hover:bg-red-50 hover:text-red-700"
                                            title="Delete Expense">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                         `;
                }).join('');
            },

            // --- Actions ---
            handleAddExpense() {
                // Validation
                const name = document.getElementById('frm-name').value;
                const amount = document.getElementById('frm-amount').value;
                if (!name || amount <= 0) {
                    Swal.fire('Error', 'Please enter expense name and amount', 'error');
                    return;
                }

                const autoGen = document.getElementById('frm-auto-generate').checked;
                const autoPost = document.getElementById('frm-auto-post').checked;

                // MOCK Create
                const newExp = {
                    id: 'oxp-' + Math.floor(Math.random() * 1000),
                    category: document.getElementById('frm-category').value,
                    name: name,
                    description: document.getElementById('frm-desc').value,
                    amount: amount,
                    status: document.getElementById('frm-status').value,
                    date: document.getElementById('frm-date').value,
                    vendor: document.getElementById('frm-vendor').value,
                    glJournalEntryId: autoGen ? 'JE-NEW-' + Math.floor(Math.random() * 100) : null
                };

                this.data.expenses.unshift(newExp);
                this.updateStats();
                this.renderList();
                this.toggleAddForm();

                let msg = 'Expense recorded successfully';
                if (autoGen) {
                    msg += ` and journal entry ${autoPost ? 'posted' : 'created as draft'}`;
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: msg,
                    timer: 2000,
                    showConfirmButton: false
                });
            },

            deleteExpense(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Associated journal entry will be reversed.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.data.expenses = this.data.expenses.filter(e => e.id !== id);
                        this.updateStats();
                        this.renderList();
                        Swal.fire(
                            'Deleted!',
                            'Expense deleted and journal entry reversed.',
                            'success'
                        );
                    }
                });
            },

            handleBatchGenerateJE() {
                const pendingCount = this.data.expenses.filter(e => !e.glJournalEntryId).length;

                // Mock Batch Process
                this.data.expenses.forEach(e => {
                    if (!e.glJournalEntryId) {
                        e.glJournalEntryId = 'JE-BATCH-' + Math.floor(Math.random() * 1000);
                    }
                });

                this.updateStats();
                this.renderList();

                Swal.fire({
                    icon: 'success',
                    title: 'Batch Complete',
                    text: `Generated ${pendingCount} journal entries`,
                    timer: 2000,
                    showConfirmButton: false
                });
            },

            viewJe(id) {
                // Mock redirect
                Swal.fire('Info', `Redirecting to Journal Entry ${id}...`, 'info');
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            expenseManager.init();
        });
    </script>
@endsection