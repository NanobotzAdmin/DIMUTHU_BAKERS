@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 p-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <i class="bi bi-bank text-4xl text-amber-500"></i>
                    <h1 class="text-3xl font-bold text-gray-900">Bank Reconciliation</h1>
                </div>
                <p class="text-gray-600">Match bank statements with general ledger transactions</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button onclick="bankReconManager.showImportModal()"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 flex items-center gap-2 transition-colors">
                    <i class="bi bi-download"></i> Import Statement
                </button>
                <button onclick="bankReconManager.showAddTransactionModal()"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 flex items-center gap-2 transition-colors">
                    <i class="bi bi-plus-lg"></i> Add Transaction
                </button>
                <button onclick="bankReconManager.showAddAccountModal()"
                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 flex items-center gap-2 transition-colors shadow-sm">
                    <i class="bi bi-plus-lg"></i> Add Bank Account
                </button>
            </div>
        </div>

        <div class="max-w-[1600px] mx-auto space-y-6">

            <!-- Bank Account Selector & Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div class="flex-1 w-full lg:w-auto">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Bank Account</label>
                        <select id="bank-account-select" onchange="bankReconManager.selectAccount(this.value)"
                            class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm">
                            <!-- Dynamic Options -->
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full lg:w-auto flex-shrink-0">
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <div class="text-sm text-gray-600 mb-1">GL Balance</div>
                            <div class="text-xl font-bold text-gray-900" id="stat-gl-balance">-</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <div class="text-sm text-gray-600 mb-1">Last Reconciled</div>
                            <div class="text-xl font-bold text-gray-900" id="stat-last-recon">-</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <div class="text-sm text-gray-600 mb-1">Statement Balance</div>
                            <div class="text-xl font-bold text-gray-900" id="stat-statement-balance">-</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="text-sm text-gray-500 font-medium mb-2">Total Transactions</div>
                    <div class="text-2xl font-bold text-gray-900" id="stat-total-txns">0</div>
                    <p class="text-xs text-gray-500 mt-1">All transactions</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="text-sm text-gray-500 font-medium mb-2">Reconciled</div>
                    <div class="text-2xl font-bold text-green-600" id="stat-reconciled-count">0</div>
                    <p class="text-xs text-gray-500 mt-1" id="stat-reconciled-percent">0% complete</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="text-sm text-gray-500 font-medium mb-2">Outstanding Deposits</div>
                    <div class="text-2xl font-bold text-blue-600" id="stat-outstanding-dep">$0.00</div>
                    <p class="text-xs text-gray-500 mt-1">Uncleared deposits</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="text-sm text-gray-500 font-medium mb-2">Outstanding Withdrawals</div>
                    <div class="text-2xl font-bold text-red-600" id="stat-outstanding-with">$0.00</div>
                    <p class="text-xs text-gray-500 mt-1">Uncleared checks/payments</p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 border-b border-gray-200" id="recon-tabs">
                <button onclick="bankReconManager.switchTab('reconcile')" id="tab-btn-reconcile"
                    class="px-4 py-2 border-b-2 transition-colors font-medium border-amber-500 text-amber-500 flex items-center gap-2">
                    <i class="bi bi-check-circle"></i> Reconcile Transactions
                </button>
                <button onclick="bankReconManager.switchTab('history')" id="tab-btn-history"
                    class="px-4 py-2 border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900 flex items-center gap-2">
                    <i class="bi bi-arrow-counterclockwise"></i> Reconciliation History
                </button>
                <button onclick="bankReconManager.switchTab('all-transactions')" id="tab-btn-all-transactions"
                    class="px-4 py-2 border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900 flex items-center gap-2">
                    <i class="bi bi-list-ul"></i> All Transactions
                </button>
            </div>

            <!-- Reconcile Tab -->
            <div id="tab-content-reconcile" class="space-y-6">
                <!-- Search & Action -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="relative w-full md:w-96">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Search transactions..."
                            oninput="bankReconManager.handleSearch(this.value)"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                    </div>
                    <button onclick="bankReconManager.showCompleteModal()"
                        class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 flex items-center gap-2 transition-colors shadow-sm w-full md:w-auto justify-center">
                        <i class="bi bi-check-circle-fill"></i> Complete Reconciliation
                    </button>
                </div>

                <!-- Unreconciled Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="font-bold text-gray-900" id="unreconciled-header">Unreconciled Transactions (0)</h3>
                        <p class="text-xs text-gray-500 hidden md:block">Check off transactions against your bank statement
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-center w-12">
                                        <i class="bi bi-check-lg"></i>
                                    </th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">Description</th>
                                    <th class="px-4 py-3">Reference</th>
                                    <th class="px-4 py-3 text-center">Type</th>
                                    <th class="px-4 py-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="unreconciled-table-body">
                                <!-- Dynamic Rows -->
                            </tbody>
                        </table>
                    </div>
                    <div id="unreconciled-empty" class="hidden p-8 text-center text-gray-500">
                        <i class="bi bi-check-circle text-4xl text-gray-300 mb-2 block"></i>
                        <p>All transactions reconciled!</p>
                    </div>
                </div>

                <!-- Recently Reconciled Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-check-circle text-green-600"></i> Recently Reconciled
                        </h3>
                    </div>
                    <div class="p-4 space-y-2" id="reconciled-summary-list">
                        <!-- Dynamic List -->
                    </div>
                </div>
            </div>

            <!-- History Tab -->
            <div id="tab-content-history"
                class="hidden bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">Reconciliation History</h2>
                    <p class="text-sm text-gray-500">Previous bank reconciliations</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Statement Date</th>
                                <th class="px-4 py-3 text-right">Statement Bal</th>
                                <th class="px-4 py-3 text-right">GL Bal</th>
                                <th class="px-4 py-3 text-right">Difference</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3">Completed By</th>
                            </tr>
                        </thead>
                        <tbody id="history-table-body">
                            <!-- Dynamic Rows -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- All Transactions Tab -->
            <div id="tab-content-all-transactions"
                class="hidden bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">All Bank Transactions</h2>
                    <p class="text-sm text-gray-500">Complete transaction history for this account</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Description</th>
                                <th class="px-4 py-3">Reference</th>
                                <th class="px-4 py-3 text-center">Type</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                                <th class="px-4 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="all-txns-table-body">
                            <!-- Dynamic Rows -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Modals -->

    <!-- Import Statement Modal -->
    <div id="modal-import-statement"
        class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full m-4">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Import Bank Statement</h3>
                    <p class="text-sm text-gray-500">Upload a CSV file to import transactions</p>
                </div>
                <button onclick="bankReconManager.closeModal('import-statement')"
                    class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer"
                    onclick="document.getElementById('import-file').click()">
                    <i class="bi bi-cloud-arrow-up text-4xl text-gray-400 mb-2 block"></i>
                    <p class="text-sm text-gray-600 mb-2">Drag and drop your CSV file here, or click to browse</p>
                    <button
                        class="px-3 py-1 bg-white border border-gray-300 rounded text-xs font-medium text-gray-700 shadow-sm">Choose
                        File</button>
                    <input type="file" id="import-file" class="hidden" accept=".csv">
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                    <h4 class="text-xs font-bold text-blue-900 mb-2 uppercase">CSV Format Requirements:</h4>
                    <ul class="text-xs text-blue-800 space-y-1 list-disc list-inside">
                        <li>Columns: Date, Description, Reference, Type, Amount</li>
                        <li>Date format: YYYY-MM-DD</li>
                        <li>Type: "deposit" or "withdrawal"</li>
                        <li>Amount: Positive numbers only</li>
                    </ul>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-2 bg-gray-50 rounded-b-xl">
                <button onclick="bankReconManager.closeModal('import-statement')"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">Cancel</button>
                <button onclick="bankReconManager.importStatement()"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm shadow-sm">Import
                    Transactions</button>
            </div>
        </div>
    </div>

    <!-- Add Transaction Modal -->
    <div id="modal-add-txn" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full m-4">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Add Transaction</h3>
                    <p class="text-sm text-gray-500">Manually add a bank transaction</p>
                </div>
                <button onclick="bankReconManager.closeModal('add-txn')" class="text-gray-400 hover:text-gray-600"><i
                        class="bi bi-x-lg"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" id="txn-date"
                        class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" id="txn-desc" placeholder="e.g., Customer Payment - ABC Cafe"
                        class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reference (Optional)</label>
                    <input type="text" id="txn-ref" placeholder="CHK-1234"
                        class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select id="txn-type"
                            class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            <option value="deposit">Deposit (In)</option>
                            <option value="withdrawal">Withdrawal (Out)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                        <input type="number" id="txn-amount" step="0.01" placeholder="0.00"
                            class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                    </div>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-2 bg-gray-50 rounded-b-xl">
                <button onclick="bankReconManager.closeModal('add-txn')"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">Cancel</button>
                <button onclick="bankReconManager.addTransaction()"
                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 text-sm shadow-sm">Add
                    Transaction</button>
            </div>
        </div>
    </div>

    <!-- Add Bank Account Modal -->
    <div id="modal-add-account"
        class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full m-4">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Add Bank Account</h3>
                    <p class="text-sm text-gray-500">Link a bank account to a GL account for reconciliation</p>
                </div>
                <button onclick="bankReconManager.closeModal('add-account')" class="text-gray-400 hover:text-gray-600"><i
                        class="bi bi-x-lg"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Name</label>
                    <input type="text" id="acc-name" placeholder="e.g., Main Operating Account"
                        class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                        <input type="text" id="acc-bank-name" placeholder="e.g., Commercial Bank"
                            class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                        <input type="text" id="acc-number" placeholder="e.g., 1234567890"
                            class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link to GL Account</label>
                    <select id="acc-gl-id"
                        class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        <!-- Populated by JS -->
                    </select>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-2 bg-gray-50 rounded-b-xl">
                <button onclick="bankReconManager.closeModal('add-account')"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">Cancel</button>
                <button onclick="bankReconManager.addAccount()"
                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 text-sm shadow-sm">Add
                    Account</button>
            </div>
        </div>
    </div>

    <!-- Complete Reconciliation Modal -->
    <div id="modal-complete-recon"
        class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full m-4 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900">Complete Reconciliation</h3>
                <p class="text-sm text-gray-500">Enter statement details to finalize</p>
            </div>
            <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                <!-- Summary Box -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-2 border border-blue-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">GL Balance:</span>
                        <span class="font-mono font-medium" id="recon-modal-gl-bal">-</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Outstanding Deposits:</span>
                        <span class="font-mono text-blue-600" id="recon-modal-out-dep">-</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Outstanding Withdrawals:</span>
                        <span class="font-mono text-red-600" id="recon-modal-out-with">-</span>
                    </div>
                    <div class="flex justify-between text-sm pt-2 border-t border-gray-300">
                        <span class="text-gray-900 font-bold">Adjusted GL Balance:</span>
                        <span class="font-mono font-bold text-amber-600" id="recon-modal-adj-bal">-</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statement End Date</label>
                        <input type="date" id="recon-stmt-date"
                            class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statement Ending Balance</label>
                        <input type="number" id="recon-stmt-bal" step="0.01"
                            oninput="bankReconManager.calculateDifference()" placeholder="0.00"
                            class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm font-mono">
                    </div>
                </div>

                <!-- Difference Alert -->
                <div id="recon-difference-box" class="hidden rounded-lg p-4 bg-gray-50 border transition-all">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-gray-900">Difference:</span>
                        <span class="text-xl font-bold font-mono" id="recon-diff-amount">$0.00</span>
                    </div>
                    <p class="text-sm" id="recon-diff-msg"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                    <textarea id="recon-notes" rows="3" placeholder="Add notes..."
                        class="w-full p-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm"></textarea>
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 flex justify-end gap-2 bg-gray-50">
                <button onclick="bankReconManager.closeModal('complete-recon')"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm">Cancel</button>
                <button onclick="bankReconManager.completeReconciliation()"
                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 text-sm shadow-sm">Finalize</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const bankReconManager = {
            data: {
                bankAccounts: @json($bankAccounts),
                bankTransactions: @json($bankTransactions),
                reconciliations: @json($reconciliations),
                glAccounts: @json($glAccounts)
            },
            state: {
                selectedAccountId: 'BA-001',
                activeTab: 'reconcile',
                searchQuery: ''
            },

            init() {
                // Set default date for modals
                document.getElementById('txn-date').valueAsDate = new Date();
                document.getElementById('recon-stmt-date').valueAsDate = new Date();

                this.renderAccountSelect();
                this.updateUI();
            },

            // --- UI Rendering ---

            renderAccountSelect() {
                const select = document.getElementById('bank-account-select');
                select.innerHTML = this.data.bankAccounts.map(acc =>
                    `<option value="${acc.id}">${acc.accountName} - ${acc.bankName} (${acc.accountNumber})</option>`
                ).join('');
                select.value = this.state.selectedAccountId;
            },

            updateUI() {
                const account = this.getSelectedAccount();
                if (!account) return;

                const filteredTxns = this.getFilteredTransactions();
                const unreconciled = filteredTxns.filter(t => !t.isReconciled);
                const reconciled = filteredTxns.filter(t => t.isReconciled);

                // Update Stats Header
                document.getElementById('stat-gl-balance').innerText = this.formatCurrency(account.currentBalance);
                document.getElementById('stat-last-recon').innerText = account.lastReconciledDate ? account.lastReconciledDate : 'Never';
                document.getElementById('stat-statement-balance').innerText = account.lastReconciledBalance ? this.formatCurrency(account.lastReconciledBalance) : '-';

                // Update Cards
                document.getElementById('stat-total-txns').innerText = filteredTxns.length;
                document.getElementById('stat-reconciled-count').innerText = reconciled.length;

                const percent = filteredTxns.length > 0 ? Math.round((reconciled.length / filteredTxns.length) * 100) : 0;
                document.getElementById('stat-reconciled-percent').innerText = `${percent}% complete`;

                const outDeps = unreconciled.filter(t => t.type === 'deposit').reduce((sum, t) => sum + Number(t.amount), 0);
                const outWiths = unreconciled.filter(t => t.type === 'withdrawal').reduce((sum, t) => sum + Number(t.amount), 0);

                document.getElementById('stat-outstanding-dep').innerText = this.formatCurrency(outDeps);
                document.getElementById('stat-outstanding-with').innerText = this.formatCurrency(outWiths);

                // Update Tabs
                this.renderReconcileTab(unreconciled, reconciled);
                this.renderHistoryTab();
                this.renderAllTransactionsTab(filteredTxns);
            },

            renderReconcileTab(unreconciled, reconciled) {
                const tbody = document.getElementById('unreconciled-table-body');
                const emptyState = document.getElementById('unreconciled-empty');
                const headerCount = document.getElementById('unreconciled-header');

                headerCount.innerText = `Unreconciled Transactions (${unreconciled.length})`;

                if (unreconciled.length === 0) {
                    tbody.innerHTML = '';
                    emptyState.classList.remove('hidden');
                    tbody.parentElement.classList.add('hidden');
                } else {
                    emptyState.classList.add('hidden');
                    tbody.parentElement.classList.remove('hidden');
                    tbody.innerHTML = unreconciled.map(t => `
                                    <tr class="border-b hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" onchange="bankReconManager.toggleReconcile('${t.id}')"
                                                class="w-4 h-4 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500">
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">${t.transactionDate}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900">${t.description}</td>
                                        <td class="px-4 py-3 font-mono text-gray-500">${t.reference || '-'}</td>
                                         <td class="px-4 py-3 text-center">
                                            ${t.type === 'deposit'
                            ? '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">↓ Deposit</span>'
                            : '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-200">↑ Withdrawal</span>'}
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono text-gray-900">${this.formatCurrency(t.amount)}</td>
                                    </tr>
                                `).join('');
                }

                // Recent List
                const recentList = document.getElementById('reconciled-summary-list');
                if (reconciled.length === 0) {
                    recentList.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No reconciled transactions yet.</p>';
                } else {
                    recentList.innerHTML = reconciled.slice(0, 5).map(t => `
                                    <div class="flex items-center justify-between py-2 border-b last:border-0 hover:bg-gray-50 px-2 rounded">
                                        <div class="flex items-center gap-3">
                                            <i class="bi bi-check-circle-fill text-green-500 text-sm"></i>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">${t.description}</div>
                                                <div class="text-xs text-gray-500">${t.transactionDate}</div>
                                            </div>
                                        </div>
                                        <div class="text-sm font-mono text-gray-900">${this.formatCurrency(t.amount)}</div>
                                    </div>
                                `).join('');
                }
            },

            renderHistoryTab() {
                const history = this.data.reconciliations
                    .filter(r => r.bankAccountId === this.state.selectedAccountId)
                    .sort((a, b) => new Date(b.reconDate) - new Date(a.reconDate));

                document.getElementById('history-table-body').innerHTML = history.map(r => `
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-700">${r.reconDate}</td>
                                    <td class="px-4 py-3 text-gray-700">${r.statementEndDate}</td>
                                    <td class="px-4 py-3 text-right font-mono">${this.formatCurrency(r.statementBalance)}</td>
                                    <td class="px-4 py-3 text-right font-mono">${this.formatCurrency(r.glBalance)}</td>
                                    <td class="px-4 py-3 text-right font-mono ${r.difference != 0 ? 'text-red-600 font-bold' : 'text-green-600'}">${this.formatCurrency(r.difference)}</td>
                                    <td class="px-4 py-3 text-center">
                                         ${r.isBalanced
                        ? '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Balanced</span>'
                        : '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-200">Difference</span>'}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">${r.completedBy}</td>
                                </tr>
                            `).join('');
            },

            renderAllTransactionsTab(txns) {
                document.getElementById('all-txns-table-body').innerHTML = txns.map(t => `
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-gray-700">${t.transactionDate}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">${t.description}</td>
                                    <td class="px-4 py-3 font-mono text-gray-500">${t.reference || '-'}</td>
                                     <td class="px-4 py-3 text-center">
                                        ${t.type === 'deposit'
                        ? '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Deposit</span>'
                        : '<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-200">Withdrawal</span>'}
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono text-gray-900">${this.formatCurrency(t.amount)}</td>
                                     <td class="px-4 py-3 text-center">
                                        ${t.isReconciled
                        ? '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200"><i class="bi bi-check-lg"></i> Reconciled</span>'
                        : '<span class="bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">Unreconciled</span>'}
                                    </td>
                                </tr>
                            `).join('');
            },

            // --- Helpers ---

            getSelectedAccount() {
                return this.data.bankAccounts.find(a => a.id === this.state.selectedAccountId);
            },

            getFilteredTransactions() {
                let txns = this.data.bankTransactions.filter(t => t.bankAccountId === this.state.selectedAccountId);
                if (this.state.searchQuery) {
                    const q = this.state.searchQuery.toLowerCase();
                    txns = txns.filter(t => t.description.toLowerCase().includes(q) || (t.reference && t.reference.toLowerCase().includes(q)));
                }
                return txns.sort((a, b) => new Date(b.transactionDate) - new Date(a.transactionDate));
            },

            getReconciliationStats() {
                const account = this.getSelectedAccount();
                const txns = this.getFilteredTransactions(); // Should technically be all unreconciled, ignoring search for calc
                // Recalculating strict 'unreconciled' for the selected account regardless of search
                const allUnreconciled = this.data.bankTransactions.filter(t => t.bankAccountId === this.state.selectedAccountId && !t.isReconciled);

                const outDeps = allUnreconciled.filter(t => t.type === 'deposit').reduce((sum, t) => sum + Number(t.amount), 0);
                const outWiths = allUnreconciled.filter(t => t.type === 'withdrawal').reduce((sum, t) => sum + Number(t.amount), 0);
                const netOutstanding = outDeps - outWiths;

                return { outDeps, outWiths, netOutstanding, glBalance: Number(account.currentBalance) };
            },

            formatCurrency(amount) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
            },

            // --- Actions ---

            selectAccount(id) {
                this.state.selectedAccountId = id;
                this.updateUI();
            },

            switchTab(tabId) {
                this.state.activeTab = tabId;
                ['reconcile', 'history', 'all-transactions'].forEach(t => {
                    const btn = document.getElementById(`tab-btn-${t}`);
                    const content = document.getElementById(`tab-content-${t}`);
                    if (t === tabId) {
                        btn.className = "px-4 py-2 border-b-2 transition-colors font-medium border-amber-500 text-amber-500 flex items-center gap-2";
                        content.classList.remove('hidden');
                    } else {
                        btn.className = "px-4 py-2 border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900 flex items-center gap-2";
                        content.classList.add('hidden');
                    }
                });
            },

            handleSearch(query) {
                this.state.searchQuery = query;
                this.updateUI();
            },

            toggleReconcile(txnId) {
                const txn = this.data.bankTransactions.find(t => t.id === txnId);
                if (txn) {
                    txn.isReconciled = !txn.isReconciled;
                    txn.reconciledDate = txn.isReconciled ? new Date().toISOString().split('T')[0] : null;
                    this.updateUI();
                }
            },

            // --- Modal Logic ---

            showAddTransactionModal() {
                document.getElementById('modal-add-txn').classList.remove('hidden');
            },

            addTransaction() {
                // Collect Form Data
                const date = document.getElementById('txn-date').value;
                const desc = document.getElementById('txn-desc').value;
                const ref = document.getElementById('txn-ref').value;
                const type = document.getElementById('txn-type').value;
                const amount = document.getElementById('txn-amount').value;

                if (!date || !desc || !amount) {
                    Swal.fire('Error', 'Please fill in all required fields.', 'error');
                    return;
                }

                // Create Mock Txn
                const newTxn = {
                    id: `BT-NEW-${Date.now()}`,
                    bankAccountId: this.state.selectedAccountId,
                    transactionDate: date,
                    description: desc,
                    reference: ref,
                    type: type,
                    amount: parseFloat(amount),
                    isReconciled: false,
                    createdAt: new Date().toISOString()
                };

                this.data.bankTransactions.unshift(newTxn); // Add to top
                this.closeModal('add-txn');
                // clear form
                document.getElementById('txn-desc').value = '';
                document.getElementById('txn-amount').value = '';

                this.updateUI();
                Swal.fire({
                    icon: 'success',
                    title: 'Transaction Added',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500
                });
            },

            showCompleteModal() {
                const stats = this.getReconciliationStats();
                const adjBalance = stats.glBalance + stats.netOutstanding;

                document.getElementById('recon-modal-gl-bal').innerText = this.formatCurrency(stats.glBalance);
                document.getElementById('recon-modal-out-dep').innerText = `+ ${this.formatCurrency(stats.outDeps)}`;
                document.getElementById('recon-modal-out-with').innerText = `- ${this.formatCurrency(stats.outWiths)}`;
                document.getElementById('recon-modal-adj-bal').innerText = this.formatCurrency(adjBalance);

                // Store calculated adj balance for diff check
                this.currentAdjBalance = adjBalance;

                document.getElementById('modal-complete-recon').classList.remove('hidden');
                this.calculateDifference(); // Reset diff view
            },

            calculateDifference() {
                const stmtBal = parseFloat(document.getElementById('recon-stmt-bal').value) || 0;
                const diff = Math.abs(stmtBal - this.currentAdjBalance);
                const isBalanced = diff < 0.01;

                const diffBox = document.getElementById('recon-difference-box');
                const diffAmt = document.getElementById('recon-diff-amount');
                const diffMsg = document.getElementById('recon-diff-msg');

                if (stmtBal > 0) {
                    diffBox.classList.remove('hidden');
                    diffAmt.innerText = this.formatCurrency(diff);

                    if (isBalanced) {
                        diffBox.className = "rounded-lg p-4 bg-green-50 border border-green-200 transition-all";
                        diffAmt.className = "text-xl font-bold font-mono text-green-700";
                        diffMsg.className = "text-sm text-green-700 mt-1";
                        diffMsg.innerHTML = '<i class="bi bi-check-circle-fill"></i> Balanced! Ready to finalize.';
                    } else {
                        diffBox.className = "rounded-lg p-4 bg-red-50 border border-red-200 transition-all";
                        diffAmt.className = "text-xl font-bold font-mono text-red-700";
                        diffMsg.className = "text-sm text-red-700 mt-1";
                        diffMsg.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Discrepancy detected. Please review transactions.';
                    }
                } else {
                    diffBox.classList.add('hidden');
                }
            },

            completeReconciliation() {
                const stmtBal = parseFloat(document.getElementById('recon-stmt-bal').value) || 0;
                const diff = Math.abs(stmtBal - this.currentAdjBalance);
                const isBalanced = diff < 0.01;
                const date = document.getElementById('recon-stmt-date').value;

                // Create Mock Recon Record
                const newRecon = {
                    id: `BR-NEW-${Date.now()}`,
                    bankAccountId: this.state.selectedAccountId,
                    reconDate: new Date().toISOString().split('T')[0],
                    statementEndDate: date,
                    statementBalance: stmtBal,
                    glBalance: this.currentAdjBalance, // Simplified mock logic
                    difference: diff,
                    isBalanced: isBalanced,
                    status: isBalanced ? 'completed' : 'draft',
                    completedBy: 'Current User',
                };

                this.data.reconciliations.unshift(newRecon);

                // Update Account Last Recon Data
                const account = this.getSelectedAccount();
                account.lastReconciledDate = date;
                account.lastReconciledBalance = stmtBal;

                this.closeModal('complete-recon');
                this.updateUI();

                Swal.fire({
                    icon: isBalanced ? 'success' : 'warning',
                    title: isBalanced ? 'Reconciliation Complete' : 'Saved with Discrepancy',
                    text: isBalanced ? 'Account is balanced.' : 'Draft saved. Please investigate the difference.',
                });
            },

            showAddAccountModal() {
                // Populate GL Dropdown
                const glSelect = document.getElementById('acc-gl-id');
                // Filter for active assets starting with 10 for simplicity or just general assets
                const eligibleAccounts = this.data.glAccounts;

                glSelect.innerHTML = eligibleAccounts.map(a =>
                    `<option value="${a.id}">${a.code} - ${a.name}</option>`
                ).join('');

                document.getElementById('modal-add-account').classList.remove('hidden');
            },

            addAccount() {
                const name = document.getElementById('acc-name').value;
                const bank = document.getElementById('acc-bank-name').value;
                const num = document.getElementById('acc-number').value;
                const glId = document.getElementById('acc-gl-id').value;

                if (!name || !bank || !num || !glId) {
                    Swal.fire('Error', 'Please fill all fields', 'error');
                    return;
                }

                // Find GL Account details for mock linkage
                const glAccount = this.data.glAccounts.find(a => a.id == glId);

                const newAcc = {
                    id: `BA-NEW-${Date.now()}`,
                    accountName: name,
                    accountNumber: num,
                    bankName: bank,
                    glAccountId: glId,
                    glAccountCode: glAccount ? glAccount.code : 'UNKNOWN',
                    currentBalance: glAccount ? glAccount.currentBalance : 0,
                    isActive: true,
                    createdAt: new Date().toISOString()
                };

                this.data.bankAccounts.push(newAcc);
                this.state.selectedAccountId = newAcc.id; // Select new account

                this.closeModal('add-account');

                // Clear form
                document.getElementById('acc-name').value = '';
                document.getElementById('acc-bank-name').value = '';
                document.getElementById('acc-number').value = '';

                this.renderAccountSelect(); // Re-render dropdown
                this.updateUI();

                Swal.fire({
                    icon: 'success',
                    title: 'Account Added',
                    text: 'New bank account linked successfully.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500
                });
            },

            showImportModal() {
                document.getElementById('modal-import-statement').classList.remove('hidden');
            },

            importStatement() {
                this.closeModal('import-statement');
                const fileInput = document.getElementById('import-file');

                if (fileInput.files.length === 0) {
                    // Just a flexible check, strictly speaking we'd want a file
                    // But for demo, we'll allow proceeding or show helpful toast
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Statement Imported',
                    text: 'Transactions imported successfully (demo).',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            },

            closeModal(id) {
                document.getElementById(`modal-${id}`).classList.add('hidden');
            }

        };

        document.addEventListener('DOMContentLoaded', () => {
            bankReconManager.init();
        });
    </script>
@endsection