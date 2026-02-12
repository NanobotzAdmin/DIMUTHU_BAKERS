@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-indigo-50 to-purple-50 p-4 md:p-6" id="abc-container">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="bi bi-activity text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl text-gray-900 font-bold">Activity-Based Costing</h1>
                        <p class="text-gray-600">Advanced overhead allocation through ABC</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button id="add-activity-btn" onclick="abcManager.openActivityModal()"
                        class="h-12 px-5 bg-gradient-to-br from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                        <i class="bi bi-plus-lg"></i>
                        Add Activity
                    </button>
                    <button id="add-driver-btn" onclick="abcManager.openDriverModal()"
                        class="hidden h-12 px-5 bg-gradient-to-br from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                        <i class="bi bi-plus-lg"></i>
                        Add Cost Driver
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-indigo-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Total Activities</span>
                        <i class="bi bi-activity text-indigo-500 text-xl"></i>
                    </div>
                    <div class="text-3xl text-indigo-600 font-bold" id="stat-total-activities">0</div>
                    <div class="text-sm text-gray-500 mt-1"><span id="stat-active-activities">0</span> active</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Cost Drivers</span>
                        <i class="bi bi-bullseye text-blue-500 text-xl"></i>
                    </div>
                    <div class="text-3xl text-blue-600 font-bold" id="stat-total-drivers">0</div>
                    <div class="text-sm text-gray-500 mt-1"><span id="stat-active-drivers">0</span> active</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-purple-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Estimated Cost</span>
                        <i class="bi bi-currency-dollar text-purple-500 text-xl"></i>
                    </div>
                    <div class="text-3xl text-purple-600 font-bold" id="stat-estimated-cost">Rs 0</div>
                    <div class="text-sm text-gray-500 mt-1">Planned</div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-orange-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-600">Variance</span>
                        <i class="bi bi-graph-up-arrow text-red-500 text-xl" id="variance-icon"></i>
                    </div>
                    <div class="text-3xl font-bold" id="stat-variance">Rs 0</div>
                    <div class="text-sm text-gray-500 mt-1" id="stat-variance-percent">0%</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 mb-4">
                <button onclick="abcManager.switchTab('activities')" id="tab-activities"
                    class="flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg font-medium">
                    <i class="bi bi-activity"></i>
                    <span>Activities</span>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-white/20 text-white" id="badge-activities">0</span>
                </button>

                <button onclick="abcManager.switchTab('drivers')" id="tab-drivers"
                    class="flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200 font-medium">
                    <i class="bi bi-bullseye"></i>
                    <span>Cost Drivers</span>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700" id="badge-drivers">0</span>
                </button>

                <button onclick="abcManager.switchTab('mapping')" id="tab-mapping"
                    class="flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200 font-medium">
                    <i class="bi bi-link-45deg"></i>
                    <span>Activity Mapping</span>
                </button>
            </div>

            <!-- Search -->
            <div class="relative" id="search-container">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="search-input" placeholder="Search activities..." onkeyup="abcManager.handleSearch()"
                    class="w-full h-12 pl-11 pr-4 bg-white border-2 border-gray-200 rounded-xl outline-none focus:border-indigo-500 transition-colors">
            </div>
        </div>

        <!-- Content Area -->
        <div id="content-area">
            <!-- Dynamic Content Loaded Here -->
        </div>

        <!-- Add Activity Modal -->
        <div id="activity-modal"
            class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all scale-100">
                <div
                    class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                    <h2 class="text-2xl font-bold text-gray-900" id="activity-modal-title">Add New Activity</h2>
                    <button onclick="document.getElementById('activity-modal').classList.add('hidden')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="bi bi-x-lg text-gray-500"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <input type="hidden" id="act-id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Activity Name *</label>
                        <input type="text" id="act-name"
                            class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="act-desc" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select id="act-category"
                            class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="unit-level">Unit Level</option>
                            <option value="batch-level">Batch Level</option>
                            <option value="product-level">Product Level</option>
                            <option value="facility-level">Facility Level</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Primary Cost Driver *</label>
                        <select id="act-driver"
                            class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <!-- Populated via JS -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link to Cost Pools</label>
                        <div id="act-pools-list"
                            class="border border-gray-200 rounded-xl p-3 max-h-40 overflow-y-auto space-y-2">
                            <!-- Populated via JS -->
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Cost (Rs)</label>
                            <input type="number" id="act-est-cost"
                                class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Actual Cost (Rs)</label>
                            <input type="number" id="act-act-cost"
                                class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <!-- Info Box -->
                    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <i class="bi bi-info-circle text-blue-600 text-lg flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-blue-700">
                            <strong>ABC Tip:</strong> Activities represent tasks or processes that consume
                            resources. Choose a cost driver that best reflects how this activity consumes
                            resources (e.g., machine hours for machine setup).
                        </div>
                    </div>
                </div>
                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3 z-10">
                    <button onclick="document.getElementById('activity-modal').classList.add('hidden')"
                        class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">Cancel</button>
                    <button onclick="abcManager.saveActivity()"
                        class="px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-lg hover:from-indigo-600 hover:to-purple-700 font-medium shadow-md">Save
                        Activity</button>
                </div>


            </div>
        </div>

        <!-- Add Driver Modal -->
        <div id="driver-modal"
            class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4 backdrop-blur-sm">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all scale-100">
                <div
                    class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                    <h2 class="text-2xl font-bold text-gray-900" id="driver-modal-title">Add New Cost Driver</h2>
                    <button onclick="document.getElementById('driver-modal').classList.add('hidden')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="bi bi-x-lg text-gray-500"></i>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <input type="hidden" id="drv-id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Driver Name *</label>
                        <input type="text" id="drv-name"
                            class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="drv-desc" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Driver Type *</label>
                            <select id="drv-type"
                                class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="units-produced">Units Produced</option>
                                <option value="labor-hours">Labor Hours</option>
                                <option value="machine-hours">Machine Hours</option>
                                <option value="floor-space">Floor Space</option>
                                <option value="headcount">Headcount</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit of Measure *</label>
                            <input type="text" id="drv-unit" placeholder="e.g. Hours"
                                class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Values by Section</label>
                        <div class="space-y-3">
                            <div class="bg-blue-50 p-3 rounded-lg flex items-center gap-4">
                                <label class="w-24 text-sm font-medium text-gray-700">Kitchen</label>
                                <input type="number" id="drv-val-kitchen"
                                    class="flex-1 h-9 px-3 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="0">
                            </div>
                            <div class="bg-purple-50 p-3 rounded-lg flex items-center gap-4">
                                <label class="w-24 text-sm font-medium text-gray-700">Cake</label>
                                <input type="number" id="drv-val-cake"
                                    class="flex-1 h-9 px-3 border border-gray-300 rounded focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="0">
                            </div>
                            <div class="bg-orange-50 p-3 rounded-lg flex items-center gap-4">
                                <label class="w-24 text-sm font-medium text-gray-700">Bakery</label>
                                <input type="number" id="drv-val-bakery"
                                    class="flex-1 h-9 px-3 border border-gray-300 rounded focus:ring-orange-500 focus:border-orange-500"
                                    placeholder="0">
                            </div>
                        </div>
                    </div>
                    <!-- Info Box -->
                    <div class="flex items-start gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                        <i class="bi bi-info-circle text-blue-600 text-lg flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-blue-700">
                            <strong>Allocation Basis:</strong> Cost drivers are used to allocate overhead
                            costs to different sections based on actual resource consumption. Accurate
                            measurement is critical for fair cost allocation.
                        </div>
                    </div>
                </div>
                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3 z-10">
                    <button onclick="document.getElementById('driver-modal').classList.add('hidden')"
                        class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">Cancel</button>
                    <button onclick="abcManager.saveDriver()"
                        class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-cyan-600 text-white rounded-lg hover:from-blue-600 hover:to-cyan-700 font-medium shadow-md">Save
                        Driver</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        const abcManager = {
            data: {
                activities: @json($activities),
                drivers: @json($costDrivers),
                pools: @json($costPools)
            },
            state: {
                activeTab: 'activities', // activities, drivers, mapping
                searchQuery: ''
            },

            init() {
                this.updateStats();
                this.renderContent();
                this.setupEventListeners();
            },

            setupEventListeners() {
                // Any specific bind events here if needed, mostly handled by attributes
            },

            // --- Stats Logic ---
            updateStats() {
                const totalAct = this.data.activities.length;
                const activeAct = this.data.activities.filter(a => a.isActive).length;
                document.getElementById('stat-total-activities').innerText = totalAct;
                document.getElementById('stat-active-activities').innerText = activeAct;

                const totalDrv = this.data.drivers.length;
                const activeDrv = this.data.drivers.filter(d => d.isActive).length;
                document.getElementById('stat-total-drivers').innerText = totalDrv;
                document.getElementById('stat-active-drivers').innerText = activeDrv;

                const totalEst = this.data.activities.reduce((sum, a) => sum + (Number(a.estimatedCost) || 0), 0);
                document.getElementById('stat-estimated-cost').innerText = 'Rs ' + totalEst.toLocaleString();

                const totalActCost = this.data.activities.reduce((sum, a) => sum + (Number(a.actualCost) || 0), 0);
                const variance = totalActCost - totalEst;
                const varPercent = totalEst > 0 ? (variance / totalEst) * 100 : 0;

                const varEl = document.getElementById('stat-variance');
                const varPctEl = document.getElementById('stat-variance-percent');
                const varIcon = document.getElementById('variance-icon');

                varEl.innerText = (variance >= 0 ? '+' : '') + 'Rs ' + variance.toLocaleString();
                varPctEl.innerText = (varPercent >= 0 ? '+' : '') + varPercent.toFixed(1) + '%';

                if (variance >= 0) {
                    varEl.className = 'text-3xl font-bold text-red-600';
                    varPctEl.className = 'text-sm mt-1 text-red-600';
                    varIcon.className = 'bi bi-graph-up-arrow text-red-500 text-xl';
                } else {
                    varEl.className = 'text-3xl font-bold text-green-600';
                    varPctEl.className = 'text-sm mt-1 text-green-600';
                    varIcon.className = 'bi bi-graph-down-arrow text-green-500 text-xl';
                }

                // Update Badges
                document.getElementById('badge-activities').innerText = totalAct;
                document.getElementById('badge-drivers').innerText = totalDrv;
            },

            // --- Navigation Logic ---
            switchTab(tab) {
                this.state.activeTab = tab;
                this.state.searchQuery = '';
                document.getElementById('search-input').value = '';

                // Buttons styling
                const tabs = ['activities', 'drivers', 'mapping'];
                tabs.forEach(t => {
                    const btn = document.getElementById(`tab-${t}`);
                    const badge = document.getElementById(`badge-${t}`);
                    if (t === tab) {
                        btn.className = `flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all text-white shadow-lg font-medium ${tab === 'activities' ? 'bg-gradient-to-br from-indigo-500 to-purple-600' :
                            tab === 'drivers' ? 'bg-gradient-to-br from-blue-500 to-cyan-600' :
                                'bg-gradient-to-br from-purple-500 to-pink-600'
                            }`;
                        if (badge) badge.className = "px-2 py-0.5 rounded-full text-xs bg-white/20 text-white";
                    } else {
                        btn.className = "flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200 font-medium";
                        if (badge) {
                            badge.className = t === 'activities' ? "px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-700" : "px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700";
                        }
                    }
                });

                // Toggle Action Buttons and Search
                const addActBtn = document.getElementById('add-activity-btn');
                const addDrvBtn = document.getElementById('add-driver-btn');
                const searchCont = document.getElementById('search-container');
                const searchInput = document.getElementById('search-input');

                addActBtn.classList.add('hidden');
                addDrvBtn.classList.add('hidden');
                searchCont.classList.remove('hidden');

                if (tab === 'activities') {
                    addActBtn.classList.remove('hidden');
                    searchInput.placeholder = "Search activities...";
                } else if (tab === 'drivers') {
                    addDrvBtn.classList.remove('hidden');
                    searchInput.placeholder = "Search cost drivers...";
                } else {
                    searchCont.classList.add('hidden');
                }

                this.renderContent();
            },

            handleSearch() {
                this.state.searchQuery = document.getElementById('search-input').value.toLowerCase();
                this.renderContent();
            },

            // --- Rendering Logic ---
            renderContent() {
                const container = document.getElementById('content-area');
                if (this.state.activeTab === 'activities') {
                    this.renderActivities(container);
                } else if (this.state.activeTab === 'drivers') {
                    this.renderDrivers(container);
                } else {
                    this.renderMapping(container);
                }
            },

            renderActivities(container) {
                const filtered = this.data.activities.filter(a =>
                    a.name.toLowerCase().includes(this.state.searchQuery) ||
                    a.description.toLowerCase().includes(this.state.searchQuery)
                );

                if (filtered.length === 0) {
                    container.innerHTML = this.renderEmptyState('activities');
                    return;
                }

                let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                filtered.forEach(act => {
                    const driver = this.data.drivers.find(d => d.id === act.primaryDriverId);
                    const varAmt = (act.actualCost || 0) - (act.estimatedCost || 0);
                    const varPct = act.estimatedCost > 0 ? (varAmt / act.estimatedCost) * 100 : 0;

                    // Category Colors
                    let catColor = 'from-gray-500 to-gray-600';
                    if (act.category === 'unit-level') catColor = 'from-blue-500 to-cyan-600';
                    else if (act.category === 'batch-level') catColor = 'from-purple-500 to-indigo-600';
                    else if (act.category === 'product-level') catColor = 'from-green-500 to-emerald-600';
                    else if (act.category === 'facility-level') catColor = 'from-orange-500 to-amber-600';

                    html += `
                                    <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100 hover:shadow-md transition-all">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-start gap-4 flex-1">
                                                <div class="w-14 h-14 bg-gradient-to-br ${catColor} rounded-xl flex items-center justify-center flex-shrink-0 text-white">
                                                    <i class="bi bi-activity text-2xl"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-xl font-bold text-gray-900 mb-1 truncate">${act.name}</h3>
                                                    <p class="text-sm text-gray-600 line-clamp-2">${act.description}</p>
                                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                                        <span class="px-2 py-0.5 rounded text-xs bg-indigo-100 text-indigo-700 capitalize">${act.category.replace('-', ' ')}</span>
                                                        ${act.isActive ? '<span class="px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Active</span>' : ''}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex gap-2 ml-4">
                                                <button onclick="abcManager.editActivity('${act.id}')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button onclick="abcManager.deleteActivity('${act.id}')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-4">
                                                <div class="text-xs text-gray-600 mb-1">Estimated Cost</div>
                                                <div class="text-xl font-bold text-purple-600">Rs ${(act.estimatedCost || 0).toLocaleString()}</div>
                                            </div>
                                            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4">
                                                <div class="text-xs text-gray-600 mb-1">Actual Cost</div>
                                                <div class="text-xl font-bold text-blue-600">Rs ${(act.actualCost || 0).toLocaleString()}</div>
                                            </div>
                                        </div>

                                        ${varAmt !== 0 ? `
                                        <div class="mb-4 p-3 rounded-xl border ${varAmt > 0 ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200'}">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-700">Variance</span>
                                                <div class="text-right">
                                                    <div class="font-bold ${varAmt > 0 ? 'text-red-600' : 'text-green-600'}">${varAmt > 0 ? '+' : ''}Rs ${Math.abs(varAmt).toLocaleString()}</div>
                                                    <div class="text-xs ${varAmt > 0 ? 'text-red-600' : 'text-green-600'}">${varPct > 0 ? '+' : ''}${varPct.toFixed(1)}%</div>
                                                </div>
                                            </div>
                                        </div>
                                        ` : ''}

                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <div class="text-xs text-gray-600 mb-1">Primary Driver</div>
                                                <div class="text-sm text-gray-900 truncate flex items-center gap-1">
                                                    <i class="bi bi-bullseye text-blue-500"></i> ${driver ? driver.name : 'None'}
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <div class="text-xs text-gray-600 mb-1">Cost Pools</div>
                                                <div class="text-sm text-gray-900">${(act.costPoolIds || []).length} linked</div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                });
                html += '</div>';
                container.innerHTML = html;
            },

            renderDrivers(container) {
                const filtered = this.data.drivers.filter(d =>
                    d.name.toLowerCase().includes(this.state.searchQuery) ||
                    d.description.toLowerCase().includes(this.state.searchQuery)
                );

                if (filtered.length === 0) {
                    container.innerHTML = this.renderEmptyState('drivers');
                    return;
                }

                let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                filtered.forEach(drv => {
                    const total = drv.total || 0;
                    const k = drv.values?.kitchen || 0;
                    const c = drv.values?.cake || 0;
                    const b = drv.values?.bakery || 0;

                    // Type Badge Colors - simplified
                    let typeColor = 'bg-blue-100 text-blue-700';
                    let loadColor = 'from-blue-500 to-cyan-600'; // Default icon bg

                    html += `
                                     <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100 hover:shadow-md transition-all">
                                         <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-start gap-4 flex-1">
                                                <div class="w-14 h-14 bg-gradient-to-br ${loadColor} rounded-xl flex items-center justify-center flex-shrink-0 text-white">
                                                    <i class="bi bi-bullseye text-2xl"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-xl font-bold text-gray-900 mb-1 truncate">${drv.name}</h3>
                                                    <p class="text-sm text-gray-600 line-clamp-2">${drv.description}</p>
                                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                                        <span class="px-2 py-0.5 rounded text-xs ${typeColor} capitalize">${drv.type.replace('-', ' ')}</span>
                                                        <span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700">${drv.unit}</span>
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="flex gap-2 ml-4">
                                                <button onclick="abcManager.editDriver('${drv.id}')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button onclick="abcManager.deleteDriver('${drv.id}')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                         </div>

                                         <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4 mb-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <div class="text-xs text-gray-600 mb-1">Total Volume</div>
                                                    <div class="text-2xl font-bold text-blue-600">${total.toLocaleString()}</div>
                                                </div>
                                                 <div class="text-right">
                                                    <div class="text-xs text-gray-600 mb-1">Unit</div>
                                                    <div class="text-lg font-medium text-gray-900">${drv.unit}</div>
                                                </div>
                                            </div>
                                         </div>

                                         <div class="space-y-3">
                                            <div class="text-sm font-medium text-gray-700">Distribution by Section</div>
                                            ${this.renderProgressBar('Kitchen', k, total, 'bg-gradient-to-r from-blue-500 to-cyan-600', drv.unit)}
                                            ${this.renderProgressBar('Cake', c, total, 'bg-gradient-to-r from-purple-500 to-indigo-600', drv.unit)}
                                            ${this.renderProgressBar('Bakery', b, total, 'bg-gradient-to-r from-orange-500 to-amber-600', drv.unit)}
                                         </div>
                                     </div>
                                `;
                });
                html += '</div>';
                container.innerHTML = html;
            },

            renderProgressBar(label, value, total, colorClass, unit) {
                const pct = total > 0 ? (value / total) * 100 : 0;
                return `
                                <div class="bg-gray-50 rounded-lg p-2">
                                    <div class="flex justify-between mb-1 text-xs">
                                        <span class="text-gray-700">${label}</span>
                                        <span class="font-bold text-gray-900">${value.toLocaleString()} ${unit}</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full ${colorClass}" style="width: ${pct}%"></div>
                                    </div>
                                </div>
                            `;
            },

            renderMapping(container) {
                if (this.data.activities.length === 0) {
                    container.innerHTML = this.renderEmptyState('activities');
                    return;
                }

                let html = `
                                <div class="bg-white rounded-2xl p-6 shadow-sm border-2 border-gray-100 space-y-4">
                                    <h2 class="text-xl font-bold text-gray-900 mb-2">Activity-Pool-Driver Mapping</h2>
                                    <div class="space-y-4">
                            `;

                this.data.activities.forEach(act => {
                    const driver = this.data.drivers.find(d => d.id === act.primaryDriverId);
                    const linkedPools = this.data.pools.filter(p => (act.costPoolIds || []).includes(p.id));

                    html += `
                                    <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-6 border border-gray-200">
                                         <div class="flex flex-col md:flex-row items-center gap-6">
                                            <div class="flex-1 w-full">
                                                <div class="flex items-center gap-2 mb-2 text-indigo-600 text-sm font-bold">
                                                    <i class="bi bi-activity"></i> Activity
                                                </div>
                                                <h3 class="font-bold text-gray-900">${act.name}</h3>
                                                <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs rounded capitalize">${act.category}</span>
                                            </div>
                                            <i class="bi bi-arrow-right text-gray-400 text-xl hidden md:block"></i>
                                            <i class="bi bi-arrow-down text-gray-400 text-xl md:hidden"></i>
                                            <div class="flex-1 w-full">
                                                 <div class="flex items-center gap-2 mb-2 text-purple-600 text-sm font-bold">
                                                    <i class="bi bi-box"></i> Cost Pools
                                                </div>
                                                ${linkedPools.length > 0 ?
                            `<div class="space-y-1">${linkedPools.map(p => `<div class="text-xs bg-purple-50 p-1.5 rounded text-purple-900">${p.name}</div>`).join('')}</div>` :
                            '<div class="text-xs text-gray-400 italic">No pools linked</div>'
                        }
                                            </div>
                                            <i class="bi bi-arrow-right text-gray-400 text-xl hidden md:block"></i>
                                            <i class="bi bi-arrow-down text-gray-400 text-xl md:hidden"></i>
                                            <div class="flex-1 w-full">
                                                 <div class="flex items-center gap-2 mb-2 text-blue-600 text-sm font-bold">
                                                    <i class="bi bi-bullseye"></i> Driver
                                                </div>
                                                ${driver ?
                            `<div><div class="font-bold text-gray-900 text-sm">${driver.name}</div><div class="text-xs text-gray-500">${driver.total.toLocaleString()} ${driver.unit}</div></div>` :
                            '<div class="text-xs text-gray-400 italic">No driver assigned</div>'
                        }
                                            </div>
                                         </div>
                                    </div>
                                `;
                });
                html += '</div></div>';
                container.innerHTML = html;
            },

            renderEmptyState(type) {
                return `
                                <div class="bg-white rounded-2xl p-12 text-center border-2 border-dashed border-gray-200">
                                    <i class="bi bi-${type === 'drivers' ? 'bullseye' : 'activity'} text-6xl text-gray-300 mb-4 block"></i>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">No ${type} found</h3>
                                    <p class="text-gray-500 mb-6">Get started by creating your first ${type === 'drivers' ? 'Cost Driver' : 'Activity'}.</p>
                                    <button onclick="abcManager.${type === 'drivers' ? 'openDriverModal' : 'openActivityModal'}()" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg hover:bg-indigo-700">
                                        Create ${type === 'drivers' ? 'Driver' : 'Activity'}
                                    </button>
                                </div>
                            `;
            },

            // --- Modal & Action Logic ---
            openActivityModal(id = null) {
                const modal = document.getElementById('activity-modal');
                const title = document.getElementById('activity-modal-title');

                // Clear vals
                document.getElementById('act-id').value = '';
                document.getElementById('act-name').value = '';
                document.getElementById('act-desc').value = '';
                document.getElementById('act-category').value = 'unit-level';
                document.getElementById('act-est-cost').value = '';
                document.getElementById('act-act-cost').value = '';

                // Populate Drivers
                const driverSelect = document.getElementById('act-driver');
                driverSelect.innerHTML = this.data.drivers.map(d => `<option value="${d.id}">${d.name} (${d.unit})</option>`).join('');

                // Populate Pools
                const poolsList = document.getElementById('act-pools-list');
                poolsList.innerHTML = this.data.pools.map(p => `
                                 <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="checkbox" name="act-pool-check" value="${p.id}" class="rounded text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm text-gray-700 flex-1">${p.name}</span>
                                    <span class="text-xs text-gray-500">Rs ${p.totalAmount.toLocaleString()}</span>
                                </label>
                            `).join('');

                if (id) {
                    title.innerText = "Edit Activity";
                    const act = this.data.activities.find(a => a.id === id);
                    if (act) {
                        document.getElementById('act-id').value = act.id;
                        document.getElementById('act-name').value = act.name;
                        document.getElementById('act-desc').value = act.description;
                        document.getElementById('act-category').value = act.category;
                        document.getElementById('act-driver').value = act.primaryDriverId;
                        document.getElementById('act-est-cost').value = act.estimatedCost;
                        document.getElementById('act-act-cost').value = act.actualCost;

                        document.querySelectorAll('input[name="act-pool-check"]').forEach(cb => {
                            if ((act.costPoolIds || []).includes(cb.value)) cb.checked = true;
                        });
                    }
                } else {
                    title.innerText = "Add New Activity";
                }

                modal.classList.remove('hidden');
            },

            saveActivity() {
                const id = document.getElementById('act-id').value;
                // Mock Saving
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: id ? 'Activity updated successfully!' : 'Activity created successfully!',
                    timer: 1500,
                    showConfirmButton: false
                });
                document.getElementById('activity-modal').classList.add('hidden');
                // In real app, update data and re-render
            },

            deleteActivity(id) {
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
                        this.data.activities = this.data.activities.filter(a => a.id !== id);
                        this.updateStats();
                        this.renderContent();
                        Swal.fire(
                            'Deleted!',
                            'Activity has been deleted.',
                            'success'
                        );
                    }
                });
            },

            editActivity(id) {
                this.openActivityModal(id);
            },

            // --- Driver Modal Logic ---
            openDriverModal(id = null) {
                const modal = document.getElementById('driver-modal');
                const title = document.getElementById('driver-modal-title');

                // Clear
                document.getElementById('drv-id').value = '';
                document.getElementById('drv-name').value = '';
                document.getElementById('drv-desc').value = '';
                document.getElementById('drv-type').value = 'units-produced';
                document.getElementById('drv-unit').value = '';
                document.getElementById('drv-val-kitchen').value = '';
                document.getElementById('drv-val-cake').value = '';
                document.getElementById('drv-val-bakery').value = '';


                if (id) {
                    title.innerText = "Edit Cost Driver";
                    const drv = this.data.drivers.find(d => d.id === id);
                    if (drv) {
                        document.getElementById('drv-id').value = drv.id;
                        document.getElementById('drv-name').value = drv.name;
                        document.getElementById('drv-desc').value = drv.description;
                        document.getElementById('drv-type').value = drv.type;
                        document.getElementById('drv-unit').value = drv.unit;
                        document.getElementById('drv-val-kitchen').value = drv.values.kitchen;
                        document.getElementById('drv-val-cake').value = drv.values.cake;
                        document.getElementById('drv-val-bakery').value = drv.values.bakery;
                    }
                } else {
                    title.innerText = "Add New Cost Driver";
                }
                modal.classList.remove('hidden');
            },

            saveDriver() {
                const id = document.getElementById('drv-id').value;
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: id ? 'Cost Driver updated successfully!' : 'Cost Driver created successfully!',
                    timer: 1500,
                    showConfirmButton: false
                });
                document.getElementById('driver-modal').classList.add('hidden');
            },

            editDriver(id) {
                this.openDriverModal(id);
            },

            deleteDriver(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Deleting this driver may affect linked activities!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.data.drivers = this.data.drivers.filter(d => d.id !== id);
                        this.updateStats();
                        this.renderContent();
                        Swal.fire(
                            'Deleted!',
                            'Cost Driver has been deleted.',
                            'success'
                        );
                    }
                });
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            abcManager.init();
        });
    </script>
@endsection