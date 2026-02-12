@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-[1600px] mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-900">
                    <i class="bi bi-book text-amber-500"></i>
                    Chart of Accounts
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Manage your general ledger account structure and balances
                </p>
            </div>
            <button onclick="chartOfAccountsManager.openAddModal()"
                class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 flex items-center gap-2 text-sm font-medium shadow-sm transition-all hover:shadow">
                <i class="bi bi-plus-lg"></i> Add Account
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Total Accounts</div>
                <div class="text-3xl font-bold text-gray-900" id="stat-total-accounts">-</div>
                <div class="text-xs text-gray-400 mt-1"><span id="stat-active-accounts">-</span> active</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Total Assets</div>
                <div class="text-3xl font-bold text-blue-600" id="stat-total-assets">-</div>
                <div class="text-xs text-gray-400 mt-1">Current value</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Total Liabilities</div>
                <div class="text-3xl font-bold text-red-600" id="stat-total-liabilities">-</div>
                <div class="text-xs text-gray-400 mt-1">Amount owed</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Net Position</div>
                <div class="text-3xl font-bold text-green-600" id="stat-net-position">-</div>
                <div class="text-xs text-gray-400 mt-1">Assets - Liabilities</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="filter-search" oninput="chartOfAccountsManager.filter()"
                    placeholder="Search accounts by code, name, or description..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
            </div>
            <select id="filter-type" onchange="chartOfAccountsManager.filter()"
                class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500 bg-white min-w-[150px]">
                <option value="all">All Types</option>
                <option value="asset">Assets</option>
                <option value="liability">Liabilities</option>
                <option value="equity">Equity</option>
                <option value="revenue">Revenue</option>
                <option value="expense">Expense</option>
            </select>
        </div>

        <!-- Tabs Header -->
        <div class="border-b border-gray-200 flex overflow-x-auto mb-4" id="type-tabs">
            <button onclick="chartOfAccountsManager.setTypeTab('all')"
                class="px-6 py-3 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 text-gray-500 whitespace-nowrap active-tab"
                data-tab="all">All</button>
            <button onclick="chartOfAccountsManager.setTypeTab('asset')"
                class="px-6 py-3 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 text-gray-500 whitespace-nowrap"
                data-tab="asset">Assets</button>
            <button onclick="chartOfAccountsManager.setTypeTab('liability')"
                class="px-6 py-3 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 text-gray-500 whitespace-nowrap"
                data-tab="liability">Liabilities</button>
            <button onclick="chartOfAccountsManager.setTypeTab('equity')"
                class="px-6 py-3 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 text-gray-500 whitespace-nowrap"
                data-tab="equity">Equity</button>
            <button onclick="chartOfAccountsManager.setTypeTab('revenue')"
                class="px-6 py-3 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 text-gray-500 whitespace-nowrap"
                data-tab="revenue">Revenue</button>
            <button onclick="chartOfAccountsManager.setTypeTab('expense')"
                class="px-6 py-3 text-sm font-medium border-b-2 border-transparent hover:text-gray-700 text-gray-500 whitespace-nowrap"
                data-tab="expense">Expenses</button>
        </div>

        <!-- Accounts List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- Table Header -->
            <div
                class="bg-gray-50 border-b border-gray-100 px-4 py-3 grid grid-cols-12 gap-4 text-xs font-semibold text-gray-500 uppercase">
                <div class="col-span-1"></div> <!-- Expand icon -->
                <div class="col-span-1">Code</div>
                <div class="col-span-4">Account Name</div>
                <div class="col-span-1">Type</div>
                <div class="col-span-2">Category</div>
                <div class="col-span-2 text-right">Balance</div>
                <div class="col-span-1 text-right">Actions</div>
            </div>

            <!-- List Content -->
            <div id="accounts-list" class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                <!-- Dynamic Content -->
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="account-modal"
        class="fixed inset-0 bg-gray-900/75 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-900" id="modal-title">Add New Account</h3>
                <p class="text-sm text-gray-500" id="modal-desc">Create a new general ledger account.</p>
            </div>

            <div class="p-6 overflow-y-auto space-y-4">
                <input type="hidden" id="form-id">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Code <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="form-code" placeholder="e.g., 1100"
                            class="w-full border p-2 bg-gray-50 border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500 font-mono">
                        <p class="text-xs text-gray-500 mt-1">4-digit account code (cannot be changed)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="form-name" placeholder="e.g., Accounts Receivable"
                            class="w-full border p-2 bg-gray-50 border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Type <span
                                class="text-red-500">*</span></label>
                        <select id="form-type" onchange="chartOfAccountsManager.updateCategories()"
                            class="w-full border p-2 bg-gray-50 border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="asset">Asset</option>
                            <option value="liability">Liability</option>
                            <option value="equity">Equity</option>
                            <option value="revenue">Revenue</option>
                            <option value="expense">Expense</option>
                        </select>
                        <p id="type-edit-helper" class="text-xs text-gray-500 mt-1 hidden">Type cannot be changed</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category <span
                                class="text-red-500">*</span></label>
                        <select id="form-category"
                            class="w-full border p-2 bg-gray-50 border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                            <!-- Dynamic Options -->
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sub-Category (Optional)</label>
                        <input type="text" id="form-sub-category" placeholder="e.g., Cash & Equivalents"
                            class="w-full border p-2 bg-gray-50 border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Normal Balance</label>
                        <select id="form-normal-balance"
                            class="w-full border p-2 bg-gray-50 border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="debit">Debit</option>
                            <option value="credit">Credit</option>
                        </select>
                        <p id="balance-edit-helper" class="text-xs text-gray-500 mt-1 hidden">Cannot be changed</p>
                    </div>
                </div>

                <div id="parent-account-container">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent Account (Optional)</label>
                    <select id="form-parent"
                        class="w-full border p-2 bg-gray-50 border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="">None (Top-Level Account)</option>
                        <!-- Dynamic Options -->
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Select a parent to create a sub-account</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="form-description" rows="3" placeholder="Describe the purpose of this account..."
                        class="w-full border p-2 bg-gray-50 border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 hidden" id="opening-balance-container">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Opening Balance</label>
                        <input type="number" id="form-opening-balance" step="0.01" placeholder="0.00"
                            class="w-full border p-2 bg-gray-50 border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500 text-right">
                        <p class="text-xs text-gray-500 mt-1">For debit balances, enter positive numbers. The system handles
                            the sign automatically.</p>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Active</span>
                            <p class="text-sm text-gray-500">Can this account be used in transactions?</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="form-active" class="sr-only peer" checked>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500">
                            </div>
                        </label>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Allow Manual Entry</span>
                            <p class="text-sm text-gray-500">Can users create manual journal entries to this account?</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="form-manual" class="sr-only peer" checked>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500">
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-xl flex justify-end gap-3">
                <button onclick="chartOfAccountsManager.closeModal()"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="btn-save" onclick="chartOfAccountsManager.saveAccount()"
                    class="px-6 py-2 bg-[#D4A017] text-white rounded-lg hover:bg-[#B8860B] transition-colors font-medium shadow-sm">Create
                    Account</button>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const chartOfAccountsManager = {
            data: {
                accounts: @json($glAccounts)
            },
            state: {
                expanded: new Set(),
                filterSearch: '',
                filterType: 'all', // Matches Header Select
                currentTypeTab: 'all', // Matches Tabs
                editingId: null
            },
            config: {
                categories: {
                    asset: ['current-asset', 'fixed-asset', 'other-asset'],
                    liability: ['current-liability', 'long-term-liability'],
                    equity: ['equity'],
                    revenue: ['revenue', 'operating-revenue', 'other-income'],
                    expense: ['expense', 'cogs', 'operating-expense', 'other-expense']
                },
                badges: {
                    asset: { icon: 'bi-building', color: 'bg-blue-500', text: 'Asset' },
                    liability: { icon: 'bi-receipt', color: 'bg-red-500', text: 'Liability' },
                    equity: { icon: 'bi-graph-up', color: 'bg-purple-500', text: 'Equity' },
                    revenue: { icon: 'bi-currency-dollar', color: 'bg-green-500', text: 'Revenue' },
                    expense: { icon: 'bi-file-text', color: 'bg-orange-500', text: 'Expense' }
                }
            },

            init() {
                this.updateStats();
                this.renderAccounts();
            },

            // --- Logic ---
            getFilteredAccounts() {
                let filtered = this.data.accounts;

                // Tab Filter
                if (this.state.currentTypeTab !== 'all') {
                    filtered = filtered.filter(a => a.type === this.state.currentTypeTab);
                }

                // Search Filter
                if (this.state.filterSearch) {
                    const q = this.state.filterSearch.toLowerCase();
                    filtered = filtered.filter(a =>
                        a.code.toLowerCase().includes(q) ||
                        a.name.toLowerCase().includes(q)
                    );
                }

                // Dropdown Filter (Override Tab if used? Or combine? User asks for "Type Filter" AND Tabs. Usually Tabs drive main view)
                // Let's make the dropdown sync with tabs or act as secondary. 
                // Implementation: The dropdown updates the view, same as tabs. 
                // Let's assume they control the same state `currentTypeTab`.

                return filtered;
            },

            getTreeStructure(accounts) {
                const map = {};
                const roots = [];

                // Initialize map
                accounts.forEach(a => {
                    map[a.id] = { ...a, children: [] };
                });

                // Build relationship
                accounts.forEach(a => {
                    if (a.parentId && map[a.parentId]) {
                        map[a.parentId].children.push(map[a.id]);
                    } else {
                        roots.push(map[a.id]);
                    }
                });

                return roots;
            },

            // --- Stats ---
            updateStats() {
                const active = this.data.accounts.filter(a => a.isActive).length;
                const assets = this.data.accounts.filter(a => a.type === 'asset').reduce((s, a) => s + parseFloat(a.currentBalance || 0), 0);
                const liabilities = this.data.accounts.filter(a => a.type === 'liability').reduce((s, a) => s + parseFloat(a.currentBalance || 0), 0);

                document.getElementById('stat-total-accounts').innerText = this.data.accounts.length;
                document.getElementById('stat-active-accounts').innerText = active;
                document.getElementById('stat-total-assets').innerText = this.fmt(assets);
                document.getElementById('stat-total-liabilities').innerText = this.fmt(liabilities);
                document.getElementById('stat-net-position').innerText = this.fmt(assets - liabilities);
            },

            // --- Rendering ---
            renderAccounts() {
                const filtered = this.getFilteredAccounts();
                const tree = this.getTreeStructure(filtered);
                const container = document.getElementById('accounts-list');

                if (tree.length === 0) {
                    container.innerHTML = `<div class="p-12 text-center text-gray-500">
                                                    <i class="bi bi-search text-3xl mb-3 block text-gray-300"></i> No accounts found
                                                </div>`;
                    return;
                }

                container.innerHTML = tree.map(root => this.renderNode(root, 0)).join('');
            },

            renderNode(node, level) {
                const hasChildren = node.children && node.children.length > 0;
                const isExpanded = this.state.expanded.has(node.id);
                const padding = level * 32; // Indentation
                const config = this.config.badges[node.type] || { icon: 'bi-question', color: 'bg-gray-500', text: node.type };

                let html = `
                                            <div class="group hover:bg-gray-50 transition-colors">
                                                <div class="px-4 py-3 grid grid-cols-12 gap-4 items-center">
                                                    <div class="col-span-1 flex justify-end">
                                                        ${hasChildren ? `
                                                            <button onclick="chartOfAccountsManager.toggleExpand(${node.id})" class="text-gray-400 hover:text-gray-600 p-1">
                                                                <i class="bi ${isExpanded ? 'bi-chevron-down' : 'bi-chevron-right'}"></i>
                                                            </button>
                                                        ` : ''}
                                                    </div>
                                                    <div class="col-span-1 font-mono text-sm font-semibold text-gray-700">${node.code}</div>
                                                    <div class="col-span-4 flex items-center gap-2" style="padding-left: ${padding}px">
                                                        <span class="w-6 h-6 rounded flex items-center justify-center text-white text-xs ${config.color}">
                                                            <i class="bi ${config.icon}"></i>
                                                        </span>
                                                        <div>
                                                            <div class="font-medium text-gray-900 ${!node.isActive ? 'text-gray-400' : ''}">${node.name}</div>
                                                            ${!node.isActive ? '<span class="text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">Inactive</span>' : ''}
                                                        </div>
                                                    </div>
                                                    <div class="col-span-1">
                                                        <span class="text-xs px-2 py-1 rounded text-white ${config.color}">${config.text}</span>
                                                    </div>
                                                    <div class="col-span-2 text-sm text-gray-600 capitalize">
                                                       ${(node.category || '').replace(/-/g, ' ')}
                                                    </div>
                                                    <div class="col-span-2 text-right font-mono text-sm ${parseFloat(node.currentBalance) < 0 ? 'text-red-600' : 'text-green-600'}">
                                                        ${this.fmt(node.currentBalance)}
                                                    </div>
                                                    <div class="col-span-1 text-right opacity-100 group-hover:opacity-100 transition-opacity">
                                                        <button onclick="chartOfAccountsManager.openEditModal(${node.id})" class="text-gray-400 hover:text-blue-600 p-1"><i class="bi bi-pencil"></i></button>
                                                        <button onclick="chartOfAccountsManager.deleteAccount(${node.id})" class="text-gray-400 hover:text-red-600 p-1 ml-1"><i class="bi bi-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>`;

                if (hasChildren && isExpanded) {
                    html += `<div class="border-l-2 border-gray-100 ml-8">`; // Visual guide
                    html += node.children.map(child => this.renderNode(child, level + 1)).join('');
                    html += `</div>`;
                }

                return html;
            },

            toggleExpand(id) {
                if (this.state.expanded.has(id)) {
                    this.state.expanded.delete(id);
                } else {
                    this.state.expanded.add(id);
                }
                this.renderAccounts();
            },

            setTypeTab(type) {
                this.state.currentTypeTab = type;

                // Update UI Params
                document.querySelectorAll('#type-tabs button').forEach(btn => {
                    btn.classList.remove('border-amber-500', 'text-amber-600', 'active-tab');
                    if (btn.getAttribute('data-tab') === type) {
                        btn.classList.add('border-amber-500', 'text-amber-600', 'active-tab');
                    } else {
                        btn.classList.add('border-transparent');
                    }
                });

                // Sync Dropdown
                document.getElementById('filter-type').value = type;

                this.renderAccounts();
            },

            filter() {
                this.state.filterSearch = document.getElementById('filter-search').value;
                const typeVal = document.getElementById('filter-type').value;
                if (typeVal !== this.state.currentTypeTab) {
                    this.setTypeTab(typeVal);
                } else {
                    this.renderAccounts();
                }
            },

            // --- Modal & CRUD ---
            updateCategories() {
                const type = document.getElementById('form-type').value;
                const cats = this.config.categories[type] || [];
                document.getElementById('form-category').innerHTML = cats.map(c =>
                    `<option value="${c}">${c.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</option>`
                ).join('');

                // Update Parent Select with compatible types
                const parents = this.data.accounts.filter(a => a.type === type); // Simple check: parents must match type for now
                document.getElementById('form-parent').innerHTML = '<option value="">None (Top Level)</option>' +
                    parents.map(a => `<option value="${a.id}">${a.code} - ${a.name}</option>`).join('');

                // Suggest Normal Balance
                if (type === 'asset' || type === 'expense') document.getElementById('form-normal-balance').value = 'debit';
                else document.getElementById('form-normal-balance').value = 'credit';
            },

            openAddModal() {
                this.state.editingId = null;
                document.getElementById('modal-title').innerText = "Add New Account";
                document.getElementById('modal-desc').innerText = "Create a new general ledger account. Make sure to use the correct account code and type.";
                document.getElementById('btn-save').innerText = "Create Account";

                document.getElementById('form-id').value = '';
                document.getElementById('form-code').value = '';
                document.getElementById('form-name').value = '';
                document.getElementById('form-type').value = 'asset';
                document.getElementById('form-type').disabled = false;
                document.getElementById('form-sub-category').value = '';
                document.getElementById('form-description').value = '';
                document.getElementById('form-opening-balance').value = '';
                document.getElementById('form-parent').value = '';

                document.getElementById('type-edit-helper').classList.add('hidden');
                document.getElementById('balance-edit-helper').classList.add('hidden');
                document.getElementById('parent-account-container').classList.remove('hidden');

                this.updateCategories(); // Reset options

                document.getElementById('opening-balance-container').classList.remove('hidden');
                document.getElementById('account-modal').classList.remove('hidden');
            },

            openEditModal(id) {
                const acc = this.data.accounts.find(a => a.id === id);
                if (!acc) return;

                this.state.editingId = id;
                document.getElementById('modal-title').innerText = "Edit Account";
                document.getElementById('modal-desc').innerText = "Update account details. Some fields cannot be changed after creation.";
                document.getElementById('btn-save').innerText = "Update Account";

                document.getElementById('form-id').value = acc.id;
                document.getElementById('form-code').value = acc.code;
                document.getElementById('form-name').value = acc.name;
                document.getElementById('form-type').value = acc.type;
                document.getElementById('form-type').disabled = true; // Lock Type on Edit

                document.getElementById('type-edit-helper').classList.remove('hidden');
                document.getElementById('balance-edit-helper').classList.remove('hidden');
                document.getElementById('parent-account-container').classList.add('hidden');

                this.updateCategories(); // Refresh cats

                document.getElementById('form-category').value = acc.category;
                document.getElementById('form-sub-category').value = acc.subCategory || '';
                document.getElementById('form-description').value = acc.description || '';
                document.getElementById('form-opening-balance').value = acc.openingBalance || 0;
                document.getElementById('form-parent').value = acc.parentId || '';
                document.getElementById('form-normal-balance').disabled = true; // Lock Balance Type

                document.getElementById('opening-balance-container').classList.add('hidden');
                document.getElementById('account-modal').classList.remove('hidden');
            },

            closeModal() {
                document.getElementById('account-modal').classList.add('hidden');
            },

            saveAccount() {
                // Collect Data
                if (!document.getElementById('form-type').value) return; // Basic validation check

                const data = {
                    id: this.state.editingId ? parseInt(this.state.editingId) : Date.now(),
                    code: document.getElementById('form-code').value,
                    name: document.getElementById('form-name').value,
                    type: document.getElementById('form-type').value,
                    category: document.getElementById('form-category').value,
                    subCategory: document.getElementById('form-sub-category').value,
                    description: document.getElementById('form-description').value,
                    openingBalance: parseFloat(document.getElementById('form-opening-balance').value) || 0,
                    currentBalance: parseFloat(document.getElementById('form-opening-balance').value) || 0, // Simplified: sync logic?
                    parentId: document.getElementById('form-parent').value ? parseInt(document.getElementById('form-parent').value) : null,
                    isActive: document.getElementById('form-active').checked,
                    allowManualEntry: document.getElementById('form-manual').checked
                };

                if (!data.code || !data.name) {
                    Swal.fire('Error', 'Code and Name are required.', 'error');
                    return;
                }

                if (this.state.editingId) {
                    // Update
                    const idx = this.data.accounts.findIndex(a => a.id === this.state.editingId);
                    if (idx > -1) {
                        this.data.accounts[idx] = { ...this.data.accounts[idx], ...data };
                        Swal.fire('Updated', 'Account updated successfully', 'success');
                    }
                } else {
                    // Create
                    this.data.accounts.push(data);
                    Swal.fire('Created', 'Account created successfully', 'success');
                }

                this.closeModal();
                this.updateStats();
                this.renderAccounts();
            },

            deleteAccount(id) {
                const acc = this.data.accounts.find(a => a.id === id);
                // Check children
                if (this.data.accounts.some(a => a.parentId === id)) {
                    Swal.fire('Cannot Delete', 'This account has sub-accounts. Please delete or move them first.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Delete Account?',
                    text: `Are you sure you want to delete ${acc.code} - ${acc.name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.data.accounts = this.data.accounts.filter(a => a.id !== id);
                        this.updateStats();
                        this.renderAccounts();
                        Swal.fire('Deleted', 'Account successfully removed.', 'success');
                    }
                });
            },

            // --- Helpers ---
            fmt(num) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(num);
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            chartOfAccountsManager.init();
        });
    </script>

    <style>
        .active-tab {
            color: #d97706 !important;
            /* amber-600 */
            border-bottom-color: #f59e0b !important;
            /* amber-500 */
        }
    </style>
@endsection