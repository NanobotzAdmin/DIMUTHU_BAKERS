@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50 p-4 md:p-6"
        id="cost-pools-container">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="bi bi-box-seam text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl text-gray-900 font-bold">Cost Pools</h1>
                        <p class="text-gray-600">Manage overhead cost pools and allocation</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="costPoolsManager.openExpenseModal()"
                        class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all shadow-sm">
                        <i class="bi bi-currency-dollar text-xl"></i>
                        Add Expense
                    </button>
                    <button onclick="costPoolsManager.openPoolModal()"
                        class="h-12 px-5 bg-gradient-to-br from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                        <i class="bi bi-plus-lg text-xl"></i>
                        Add Cost Pool
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-purple-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Total Overhead</span>
                        <i class="bi bi-currency-dollar text-purple-500 text-xl"></i>
                    </div>
                    <div class="text-3xl text-purple-600 font-bold" id="stat-total-overhead">Rs 0</div>
                    <div class="text-sm text-gray-500 mt-1">Across all pools</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Active Pools</span>
                        <i class="bi bi-box-seam text-blue-500 text-xl"></i>
                    </div>
                    <div class="text-3xl text-blue-600 font-bold" id="stat-active-pools">0</div>
                    <div class="text-sm text-gray-500 mt-1">Total: <span id="stat-total-pools">0</span></div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Total Expenses</span>
                        <i class="bi bi-file-text text-green-500 text-xl"></i>
                    </div>
                    <div class="text-3xl text-green-600 font-bold" id="stat-total-expenses">0</div>
                    <div class="text-sm text-gray-500 mt-1">Tracked expenses</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-orange-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Cost Drivers</span>
                        <i class="bi bi-bullseye text-orange-500 text-xl"></i>
                    </div>
                    <div class="text-3xl text-orange-600 font-bold" id="stat-active-drivers">0</div>
                    <div class="text-sm text-gray-500 mt-1">Active drivers</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex gap-3 items-center mb-4 flex-wrap">
                <div class="flex-1 relative min-w-[250px]">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="search-input" placeholder="Search cost pools..."
                        onkeyup="costPoolsManager.handleSearch()"
                        class="w-full h-12 pl-11 pr-4 bg-white border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                </div>

                <div class="bg-white rounded-xl px-4 py-2 shadow-sm border-2 border-gray-100 flex items-center">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-funnel text-gray-400"></i>
                        <select id="filter-category" onchange="costPoolsManager.handleFilter()"
                            class="outline-none text-gray-700 font-medium bg-transparent h-8 border-none focus:ring-0 cursor-pointer">
                            <option value="all">All Categories</option>
                            <option value="utilities">Utilities</option>
                            <option value="rent">Rent</option>
                            <option value="salaries">Salaries</option>
                            <option value="equipment">Equipment</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="marketing">Marketing</option>
                            <option value="insurance">Insurance</option>
                            <option value="administrative">Administrative</option>
                            <option value="transport">Transport</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-2 bg-white rounded-xl p-1 border-2 border-gray-100">
                    <button onclick="costPoolsManager.setViewMode('grid')" id="view-grid"
                        class="px-3 py-2 rounded-lg transition-colors bg-purple-100 text-purple-700">
                        <i class="bi bi-grid-fill"></i>
                    </button>
                    <button onclick="costPoolsManager.setViewMode('list')" id="view-list"
                        class="px-3 py-2 rounded-lg transition-colors text-gray-500 hover:bg-gray-100">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div id="content-area">
            <!-- Dynamic Content Loaded Here -->
        </div>

        <!-- Add/Edit Cost Pool Modal -->
        <div id="pool-modal"
            class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all scale-100">
                <div
                    class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                    <h2 class="text-2xl font-bold text-gray-900" id="pool-modal-title">Add New Cost Pool</h2>
                    <button onclick="document.getElementById('pool-modal').classList.add('hidden')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="bi bi-x-lg text-gray-500"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <input type="hidden" id="pool-id">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pool Name *</label>
                        <input type="text" id="pool-name" placeholder="e.g., Facility Costs"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="pool-desc" placeholder="Describe what this cost pool includes..." rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors resize-none"></textarea>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select id="pool-category"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                            <option value="utilities">Utilities</option>
                            <option value="rent">Rent</option>
                            <option value="salaries">Salaries</option>
                            <option value="equipment">Equipment</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="marketing">Marketing</option>
                            <option value="insurance">Insurance</option>
                            <option value="administrative">Administrative</option>
                            <option value="transport">Transport</option>
                            <option value="miscellaneous">Miscellaneous</option>
                        </select>
                    </div>

                    <!-- Cost Behavior -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cost Behavior *</label>
                        <select id="pool-behavior"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                            <option value="fixed">Fixed</option>
                            <option value="variable">Variable</option>
                            <option value="semi-variable">Semi-Variable</option>
                            <option value="stepped">Stepped</option>
                        </select>
                    </div>

                    <!-- Allocation Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Allocation Method *</label>
                        <select id="pool-method" onchange="costPoolsManager.toggleDriverSelect()"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                            <option value="by-driver">By Driver</option>
                            <option value="equal">Equal Allocation</option>
                            <option value="by-usage">By Usage</option>
                            <option value="by-revenue">By Revenue</option>
                            <option value="abc">Activity-Based Costing</option>
                            <option value="manual">Manual</option>
                        </select>
                    </div>

                    <!-- Primary Cost Driver -->
                    <div id="driver-select-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Cost Driver</label>
                        <select id="pool-driver"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                            <option value="">Select a driver...</option>
                            <!-- Populated via JS -->
                        </select>
                    </div>

                    <!-- Info Box -->
                    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <i class="bi bi-info-circle text-blue-600 text-lg flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-blue-700">
                            <strong>Tip:</strong> Choose a cost behavior and allocation method that best
                            represents how this cost changes with activity. Fixed costs remain constant,
                            while variable costs change with production volume.
                        </div>
                    </div>
                </div>

                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3 z-10">
                    <button onclick="document.getElementById('pool-modal').classList.add('hidden')"
                        class="px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">Cancel</button>
                    <button onclick="costPoolsManager.savePool()"
                        class="px-6 py-3 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-xl font-medium hover:from-purple-600 hover:to-indigo-700 transition-all shadow-md">
                        <span id="pool-save-btn-text">Create Pool</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Add Expense Modal -->
        <div id="expense-modal"
            class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all scale-100">
                <div
                    class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                    <h2 class="text-2xl font-bold text-gray-900">Add New Expense</h2>
                    <button onclick="document.getElementById('expense-modal').classList.add('hidden')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="bi bi-x-lg text-gray-500"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Expense Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expense Name *</label>
                        <input type="text" id="exp-name" placeholder="e.g., Monthly Electricity Bill"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount (Rs) *</label>
                        <input type="number" id="exp-amount" placeholder="0.00" min="0" step="0.01"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                        <select id="exp-category"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                            <option value="utilities">Utilities</option>
                            <option value="rent">Rent</option>
                            <option value="salaries">Salaries</option>
                            <option value="equipment">Equipment</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="marketing">Marketing</option>
                            <option value="insurance">Insurance</option>
                            <option value="administrative">Administrative</option>
                            <option value="transport">Transport</option>
                            <option value="miscellaneous">Miscellaneous</option>
                        </select>
                    </div>

                    <!-- Cost Pool -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Assign to Cost Pool (Optional)</label>
                        <select id="exp-pool"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                            <option value="">None (Unassigned)</option>
                            <!-- Populated via JS -->
                        </select>
                    </div>

                    <!-- Vendor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                        <input type="text" id="exp-vendor" placeholder="e.g., Ceylon Electricity Board"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                    </div>

                    <!-- Frequency -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Frequency *</label>
                        <select id="exp-frequency"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                            <option value="one-time">One-Time</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>

                    <!-- Cost Behavior -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cost Behavior *</label>
                        <select id="exp-behavior"
                            class="w-full h-12 px-4 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors">
                            <option value="fixed">Fixed</option>
                            <option value="variable">Variable</option>
                            <option value="semi-variable">Semi-Variable</option>
                            <option value="stepped">Stepped</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="exp-desc" placeholder="Additional notes..." rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl outline-none focus:border-purple-500 transition-colors resize-none"></textarea>
                    </div>
                </div>

                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3 z-10">
                    <button onclick="document.getElementById('expense-modal').classList.add('hidden')"
                        class="px-6 py-3 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-colors">Cancel</button>
                    <button onclick="costPoolsManager.saveExpense()"
                        class="px-6 py-3 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-xl font-medium hover:from-purple-600 hover:to-indigo-700 transition-all shadow-md">
                        Add Expense
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        const costPoolsManager = {
            data: {
                pools: @json($costPools),
                expenses: @json($expenses),
                drivers: @json($costDrivers)
            },
            state: {
                searchQuery: '',
                filterCategory: 'all',
                viewMode: 'grid' // grid, list
            },

            init() {
                this.updateStats();
                this.renderContent();
                this.populateDropdowns();
            },

            // --- Stats ---
            updateStats() {
                const totalOverhead = this.data.pools.reduce((sum, p) => sum + (Number(p.totalAmount) || 0), 0);
                const activePools = this.data.pools.filter(p => p.isActive).length;
                const totalPools = this.data.pools.length;
                const totalExpenses = this.data.expenses.length;
                const activeDrivers = this.data.drivers.length; // Assuming all passed are active for now, or filter by isActive

                document.getElementById('stat-total-overhead').innerText = 'Rs ' + totalOverhead.toLocaleString();
                document.getElementById('stat-active-pools').innerText = activePools;
                document.getElementById('stat-total-pools').innerText = totalPools;
                document.getElementById('stat-total-expenses').innerText = totalExpenses;
                document.getElementById('stat-active-drivers').innerText = activeDrivers;
            },

            // --- Navigation & Filter ---
            handleSearch() {
                this.state.searchQuery = document.getElementById('search-input').value.toLowerCase();
                this.renderContent();
            },

            handleFilter() {
                this.state.filterCategory = document.getElementById('filter-category').value;
                this.renderContent();
            },

            setViewMode(mode) {
                this.state.viewMode = mode;
                document.getElementById('view-grid').className =
                    `px-3 py-2 rounded-lg transition-colors ${mode === 'grid' ? 'bg-purple-100 text-purple-700' : 'text-gray-500 hover:bg-gray-100'}`;
                document.getElementById('view-list').className =
                    `px-3 py-2 rounded-lg transition-colors ${mode === 'list' ? 'bg-purple-100 text-purple-700' : 'text-gray-500 hover:bg-gray-100'}`;
                this.renderContent();
            },

            // --- Rendering ---
            renderContent() {
                const container = document.getElementById('content-area');
                const filtered = this.data.pools.filter(p => {
                    const matchesSearch = p.name.toLowerCase().includes(this.state.searchQuery) ||
                        p.description.toLowerCase().includes(this.state.searchQuery);
                    const matchesCategory = this.state.filterCategory === 'all' || p.category === this.state
                        .filterCategory;
                    return matchesSearch && matchesCategory;
                });

                if (filtered.length === 0) {
                    container.innerHTML = this.renderEmptyState();
                    return;
                }

                if (this.state.viewMode === 'grid') {
                    container.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-2 gap-4">${filtered.map(p => this.renderPoolCard(p)).join('')}</div>`;
                } else {
                    container.innerHTML = `<div class="space-y-4">${filtered.map(p => this.renderPoolCard(p)).join('')}</div>`;
                }
            },

            renderPoolCard(pool) {
                const driver = this.data.drivers.find(d => d.id === pool.driverId);
                const poolExpenses = this.data.expenses.filter(e => e.costPoolId === pool.id);
                const colors = this.getCategoryColors(pool.category);

                return `
                        <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100 hover:shadow-md transition-all">
                             <div class="flex items-start justify-between mb-4">
                                 <div class="flex items-start gap-4 flex-1">
                                     <div class="w-14 h-14 bg-gradient-to-br ${colors.gradient} rounded-xl flex items-center justify-center flex-shrink-0 text-white">
                                         <i class="bi ${colors.icon} text-2xl"></i>
                                     </div>
                                     <div class="flex-1 min-w-0">
                                         <h3 class="text-xl font-bold text-gray-900 mb-1 truncate">${pool.name}</h3>
                                         <p class="text-sm text-gray-600 line-clamp-2">${pool.description}</p>
                                         <div class="flex flex-wrap items-center gap-2 mt-2">
                                             <span class="px-2 py-0.5 rounded text-xs bg-purple-100 text-purple-700 capitalize">${pool.category}</span>
                                             <span class="px-2 py-0.5 rounded text-xs bg-blue-100 text-blue-700 capitalize">${pool.costBehavior}</span>
                                              <span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700 capitalize">${pool.allocationMethod.replace('-', ' ')}</span>
                                             ${pool.isActive ? '<span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Active</span>' : ''}
                                         </div>
                                     </div>
                                 </div>
                                 <div class="flex gap-2 ml-4">
                                     <button onclick="costPoolsManager.editPool('${pool.id}')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
                                         <i class="bi bi-pencil"></i>
                                     </button>
                                     <button onclick="costPoolsManager.deletePool('${pool.id}')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors">
                                         <i class="bi bi-trash"></i>
                                     </button>
                                 </div>
                             </div>

                             <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-4 mb-4">
                                 <div class="flex items-center justify-between">
                                     <div>
                                         <div class="text-sm text-gray-600 mb-1">Total Amount</div>
                                         <div class="text-3xl font-bold text-purple-600">Rs ${(Number(pool.totalAmount) || 0).toLocaleString()}</div>
                                     </div>
                                     <div class="text-right">
                                         <div class="text-sm text-gray-600 mb-1">Expenses</div>
                                         <div class="text-2xl text-gray-900 font-bold">${poolExpenses.length}</div>
                                     </div>
                                 </div>
                             </div>

                              <div class="grid grid-cols-2 gap-3 mb-4">
                                 <div class="bg-gray-50 rounded-lg p-3">
                                     <div class="text-xs text-gray-600 mb-1">Primary Driver</div>
                                     <div class="text-sm text-gray-900 truncate flex items-center gap-1">
                                         <i class="bi bi-bullseye text-blue-500"></i> ${driver ? driver.name : 'None'}
                                     </div>
                                 </div>
                                 <div class="bg-gray-50 rounded-lg p-3">
                                     <div class="text-xs text-gray-600 mb-1">% of Total</div>
                                     <div class="text-sm text-gray-900">
                                         ${this.calculatePercentage(pool.totalAmount)}%
                                     </div>
                                 </div>
                             </div>

                             ${this.renderRecentExpenses(poolExpenses)}

                        </div>
                    `;
            },

            renderRecentExpenses(expenses) {
                if (expenses.length === 0) return '';
                const display = expenses.slice(0, 3);
                const more = expenses.length - 3;
                return `
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="text-xs text-gray-600 mb-2">Recent Expenses</div>
                            <div class="space-y-1">
                                ${display.map(e => `
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-700 truncate flex-1">${e.name}</span>
                                        <span class="text-gray-900 ml-2 font-medium">Rs ${e.amount.toLocaleString()}</span>
                                    </div>
                                `).join('')}
                                ${more > 0 ? `<div class="text-xs text-purple-600 font-medium">+${more} more</div>` : ''}
                            </div>
                        </div>
                    `;
            },

            renderEmptyState() {
                return `
                         <div class="bg-white rounded-2xl p-12 text-center border-2 border-dashed border-gray-200 col-span-full">
                             <i class="bi bi-box-seam text-6xl text-gray-300 mb-4 block"></i>
                             <h3 class="text-xl font-bold text-gray-900 mb-2">No cost pools found</h3>
                             <p class="text-gray-600 mb-6">
                                ${this.state.searchQuery || this.state.filterCategory !== 'all' ? 'Try adjusting your filters' : 'Get started by creating your first cost pool'}
                             </p>
                             ${(!this.state.searchQuery && this.state.filterCategory === 'all') ? `
                                  <button onclick="costPoolsManager.openPoolModal()" class="px-6 py-3 bg-gradient-to-br from-purple-500 to-indigo-600 text-white rounded-xl font-bold shadow-lg hover:from-purple-600 hover:to-indigo-700">
                                     Create Cost Pool
                                 </button>
                             ` : ''}
                         </div>
                    `;
            },

            // --- Helpers ---
            getCategoryColors(cat) {
                const map = {
                    utilities: { gradient: 'from-yellow-500 to-orange-600', icon: 'bi-lightning-charge' },
                    rent: { gradient: 'from-blue-500 to-indigo-600', icon: 'bi-house-door' },
                    salaries: { gradient: 'from-green-500 to-emerald-600', icon: 'bi-people' },
                    equipment: { gradient: 'from-purple-500 to-violet-600', icon: 'bi-tools' },
                    maintenance: { gradient: 'from-orange-500 to-red-600', icon: 'bi-wrench' },
                    marketing: { gradient: 'from-pink-500 to-rose-600', icon: 'bi-megaphone' },
                    insurance: { gradient: 'from-indigo-500 to-blue-600', icon: 'bi-shield-check' },
                    administrative: { gradient: 'from-gray-500 to-slate-600', icon: 'bi-file-text' },
                    transport: { gradient: 'from-teal-500 to-cyan-600', icon: 'bi-truck' },
                    miscellaneous: { gradient: 'from-gray-400 to-gray-500', icon: 'bi-three-dots' },
                };
                return map[cat] || { gradient: 'from-gray-400 to-gray-500', icon: 'bi-three-dots' };
            },

            calculatePercentage(amount) {
                const totalOverhead = this.data.pools.reduce((sum, p) => sum + (Number(p.totalAmount) || 0), 0);
                return totalOverhead > 0 ? ((amount / totalOverhead) * 100).toFixed(1) : '0.0';
            },

            populateDropdowns() {
                // Pool Modal Driver Select
                const driverSelect = document.getElementById('pool-driver');
                driverSelect.innerHTML = '<option value="">Select a driver...</option>' +
                    this.data.drivers.map(d => `<option value="${d.id}">${d.name} (${d.unit})</option>`).join('');

                // Expense Modal Pool Select
                const poolSelect = document.getElementById('exp-pool');
                poolSelect.innerHTML = '<option value="">None (Unassigned)</option>' +
                    this.data.pools.map(p => `<option value="${p.id}">${p.name} - Rs ${Number(p.totalAmount).toLocaleString()}</option>`).join('');
            },

            // --- CRUD Mock ---
            openPoolModal(id = null) {
                const modal = document.getElementById('pool-modal');
                const title = document.getElementById('pool-modal-title');
                const btnText = document.getElementById('pool-save-btn-text');

                // Clear
                document.getElementById('pool-id').value = '';
                document.getElementById('pool-name').value = '';
                document.getElementById('pool-desc').value = '';
                document.getElementById('pool-category').value = 'utilities';
                document.getElementById('pool-behavior').value = 'fixed';
                document.getElementById('pool-method').value = 'by-driver';
                document.getElementById('pool-driver').value = '';

                if (id) {
                    title.innerText = 'Edit Cost Pool';
                    btnText.innerText = 'Update Pool';
                    const pool = this.data.pools.find(p => p.id === id);
                    if (pool) {
                        document.getElementById('pool-id').value = pool.id;
                        document.getElementById('pool-name').value = pool.name;
                        document.getElementById('pool-desc').value = pool.description;
                        document.getElementById('pool-category').value = pool.category;
                        document.getElementById('pool-behavior').value = pool.costBehavior;
                        document.getElementById('pool-method').value = pool.allocationMethod;
                        document.getElementById('pool-driver').value = pool.driverId || '';
                    }
                } else {
                    title.innerText = 'Add New Cost Pool';
                    btnText.innerText = 'Create Pool';
                }
                this.toggleDriverSelect(); // Update visibility based on method
                modal.classList.remove('hidden');
            },

            savePool() {
                const id = document.getElementById('pool-id').value;
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: id ? 'Cost Pool updated successfully!' : 'Cost Pool created successfully!',
                    timer: 1500,
                    showConfirmButton: false
                });
                document.getElementById('pool-modal').classList.add('hidden');
            },

            deletePool(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.data.pools = this.data.pools.filter(p => p.id !== id);
                        this.updateStats();
                        this.renderContent();
                        Swal.fire(
                            'Deleted!',
                            'Cost pool has been deleted.',
                            'success'
                        );
                    }
                });
            },

            editPool(id) {
                this.openPoolModal(id);
            },

            toggleDriverSelect() {
                const method = document.getElementById('pool-method').value;
                const container = document.getElementById('driver-select-container');
                if (method === 'by-driver') {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            },

            // Expense Logic
            openExpenseModal() {
                document.getElementById('exp-name').value = '';
                document.getElementById('exp-amount').value = '';
                document.getElementById('exp-category').value = 'utilities';
                document.getElementById('exp-pool').value = '';
                document.getElementById('exp-vendor').value = '';
                document.getElementById('exp-frequency').value = 'monthly';
                document.getElementById('exp-behavior').value = 'fixed';
                document.getElementById('exp-desc').value = '';
                document.getElementById('expense-modal').classList.remove('hidden');
            },

            saveExpense() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Expense added successfully!',
                    timer: 1500,
                    showConfirmButton: false
                });
                document.getElementById('expense-modal').classList.add('hidden');
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            costPoolsManager.init();
        });
    </script>
@endsection