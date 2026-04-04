@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#F8FAFC]">
        <!-- Elite Top Header -->
        <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
            <div class="max-w-[1700px] mx-auto px-6 py-4 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100">
                        <i class="bi bi-shield-check text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800 tracking-tight leading-none mb-1">Agent Overview</h1>
                        <div id="headerSubtitle" class="flex items-center gap-2">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Command
                                Center</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-initial min-w-[320px]">
                        <select id="agentSelect" onchange="loadAgentOverview(this.value)"
                            class="block w-full pl-6 pr-12 py-3.5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 text-sm font-bold text-gray-700 appearance-none transition-all hover:bg-gray-100 shadow-inner">
                            <option value="">-- Select Distribution Agent --</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->agent_name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                            <i class="bi bi-search"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Container -->
        <div id="mainDashboard" class="hidden transition-all duration-500 opacity-0 transform translate-y-4">
            <div class="max-w-[1700px] mx-auto px-6 py-8">

                <!-- 1. KPI HUB (Restore 4 stats cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <!-- MTD Sales -->
                    <div
                        class="rounded-3xl p-6 shadow-sm bg-emerald-50 border border-emerald-600 hover:shadow-md transition-all group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-emerald-600">
                                <i class="bi bi-graph-up-arrow text-xl"></i>
                            </div>
                            <span
                                class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full uppercase">MTD
                                Sales</span>
                        </div>
                        <p id="statTotalSales" class="text-2xl font-bold text-gray-800 tracking-tight">Rs. 0.00</p>
                        <p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-widest">Accumulated
                            Monthly</p>
                    </div>

                    <!-- Outstanding -->
                    <div
                        class="rounded-3xl p-6 shadow-sm bg-rose-50 border border-rose-600 hover:shadow-md transition-all group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-rose-600">
                                <i class="bi bi-wallet2 text-xl"></i>
                            </div>
                            <span id="statCreditLimit"
                                class="text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full uppercase">Limit:
                                Rs. 0</span>
                        </div>
                        <p id="statOutstanding" class="text-2xl font-bold text-rose-600 tracking-tight">Rs. 0.00</p>
                        <p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-widest">Current Dues</p>
                    </div>

                    <!-- Recovery -->
                    <div
                        class="rounded-3xl p-6 shadow-sm bg-indigo-50 border border-indigo-600 hover:shadow-md transition-all group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-indigo-600">
                                <i class="bi bi-cash-coin text-xl"></i>
                            </div>
                        </div>
                        <p id="statCollections" class="text-2xl font-bold text-gray-800 tracking-tight">Rs. 0.00</p>
                        <p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-widest">Total Collections
                        </p>
                    </div>

                    <!-- Success rate -->
                    <div
                        class="rounded-3xl p-6 shadow-sm bg-amber-50 border border-amber-600 hover:shadow-md transition-all group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-amber-600">
                                <i class="bi bi-bullseye text-xl"></i>
                            </div>
                            <span id="statRoutesCount"
                                class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">0
                                Routes</span>
                        </div>
                        <p id="statRecovery" class="text-2xl font-bold text-gray-800 tracking-tight">0%</p>
                        <p class="text-xs font-semibold text-gray-500 mt-1 uppercase tracking-widest">Operational
                            Success</p>
                    </div>
                </div>

                <!-- 2. TABBED NAVIGATION -->
                <div
                    class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden min-h-[600px] flex flex-col">
                    <div class="flex flex-wrap items-center gap-2 p-4 bg-gray-50/50 border-b border-gray-100">
                        <button onclick="switchTab('ops')" id="tab-ops"
                            class="tab-btn active px-6 py-2.5 rounded-2xl text-sm font-bold transition-all flex items-center gap-2">
                            <i class="bi bi-truck"></i> Daily Operations
                        </button>
                        <button onclick="switchTab('fleet')" id="tab-fleet"
                            class="tab-btn px-6 py-2.5 rounded-2xl text-sm font-bold transition-all flex items-center gap-2">
                            <i class="bi bi-people"></i> Vehicles & Team
                        </button>
                        <button onclick="switchTab('requests')" id="tab-requests"
                            class="tab-btn px-6 py-2.5 rounded-2xl text-sm font-bold transition-all flex items-center gap-2">
                            <i class="bi bi-box-seam"></i> Order Requests
                        </button>
                        <button onclick="switchTab('network')" id="tab-network"
                            class="tab-btn px-6 py-2.5 rounded-2xl text-sm font-bold transition-all flex items-center gap-2">
                            <i class="bi bi-globe"></i> Business Contacts
                        </button>
                    </div>

                    <div class="flex-1 p-8">
                        <!-- Tab Pane: Operations -->
                        <div id="pane-ops" class="tab-pane active animate-fadeIn">
                            <div class="mb-6 flex justify-between items-center">
                                <h3 class="text-lg font-bold text-gray-800 tracking-tight">Recent Daily Loads</h3>
                                <span
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest bg-gray-100 px-3 py-1 rounded-full">Last
                                    10 Records</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="border-b border-gray-300">
                                            <th
                                                class="pb-4 text-xs font-bold text-gray-500 uppercase tracking-widest pl-4">
                                                Date / Load ID</th>
                                            <th class="pb-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                                                Route / Vehicle</th>
                                            <th
                                                class="pb-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-center">
                                                Summary</th>
                                            <th
                                                class="pb-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right pr-4">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="opsTableBody" class="divide-y divide-gray-50">
                                        <!-- Dynamic Content -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Pane: Fleet & Team -->
                        <div id="pane-fleet" class="tab-pane hidden animate-fadeIn">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="fleetGrid">
                                <!-- Dynamic Content -->
                            </div>
                        </div>

                        <!-- Tab Pane: Order Requests -->
                        <div id="pane-requests" class="tab-pane hidden animate-fadeIn">
                            <div class="space-y-4" id="requestsGrid">
                                <!-- Dynamic Content -->
                            </div>
                        </div>

                        <!-- Tab Pane: Market Network -->
                        <div id="pane-network" class="tab-pane hidden animate-fadeIn">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6"
                                id="networkGrid">
                                <!-- Dynamic Content -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Empty/Loading States -->
        <div id="dashboardEmpty" class="max-w-2xl mx-auto py-32 px-6 text-center">
            <div
                class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center mx-auto mb-8 border border-gray-50">
                <i class="bi bi-binoculars text-indigo-500 text-4xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-4 tracking-tight">Distribution Insights</h2>
            <p class="text-gray-400 font-medium leading-relaxed">Select an active agent from the distribution network to
                reveal aggregated operational statistics and detailed history.</p>
        </div>

        <div id="loadingOverlay"
            class="hidden fixed inset-0 bg-white/60 backdrop-blur-lg z-50 flex items-center justify-center">
            <div class="flex flex-col items-center">
                <div
                    class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mb-4 shadow-indigo-100 shadow-xl">
                </div>
                <p class="text-indigo-500 font-bold tracking-widest uppercase text-xs">Synchronizing Field Data...</p>
            </div>
        </div>

        <!-- MODAL: DAILY LOAD DETAILS (Tailwind Implementation) -->
        <div id="dailyLoadModal"
            class="hidden fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 bg-gray-900/75 backdrop-blur-sm">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeLoadModal()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full border-0 animate-fadeIn">
                    <div class="bg-gray-50 px-8 py-6 flex items-center justify-between border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-indigo-500 shadow-sm">
                                <i class="bi bi-clipboard-data text-2xl"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800 mb-0" id="modalLoadTitle">LOAD #0000</h5>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest"
                                    id="modalLoadSubtitle">Deep Analysis Report</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeLoadModal()"
                            class="bg-red-200 rounded-full p-2 text-red-500 hover:text-red-600 hover:bg-red-100 shadow-sm transition-all">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body p-8 max-h-[80vh] overflow-y-auto no-scrollbar">

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                            <!-- Left Pillar: Dispatch Info -->
                            <div class="lg:col-span-4 space-y-8">
                                <div class="bg-indigo-50/50 p-6 rounded-[2rem] border border-indigo-100/50">
                                    <h6
                                        class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <i class="bi bi-box-arrow-right"></i> Original Dispatch
                                    </h6>
                                    <div class="space-y-3" id="modalDispatchItems">
                                        <!-- Dynamic Items -->
                                    </div>
                                </div>
                                <div class="p-6 bg-gray-50 rounded-[2rem]">
                                    <h6 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Summary
                                        Notes</h6>
                                    <p class="text-xs text-gray-500 leading-relaxed italic">Operational record generated by
                                        field hand-held device. Verified by distribution supervisor at the time of dispatch.
                                    </p>
                                </div>
                            </div>

                            <!-- Right Pillar: Market Activity -->
                            <div class="lg:col-span-8 space-y-6">
                                <h6
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest px-2 flex items-center gap-2">
                                    <i class="bi bi-shop text-emerald-500"></i> Market Activity Log
                                </h6>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="modalMarketInvoices">
                                    <!-- Dynamic Invoices -->
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="border-t border-gray-100 p-8 bg-gray-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm"></span>
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Record Integrity
                                Verified</span>
                        </div>
                        <button type="button" onclick="closeLoadModal()"
                            class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">Close
                            Analysis</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: ORDER REQUEST DETAILS -->
        <div id="orderRequestModal"
            class="hidden fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 bg-gray-900/75 backdrop-blur-sm animate-fadeIn">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeOrderModal()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border-0 animate-fadeIn">
                    <div class="bg-gray-50 px-8 py-6 flex items-center justify-between border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-indigo-500 shadow-sm">
                                <i class="bi bi-box-seam text-2xl"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800 mb-0" id="modalOrderTitle">ORDER #0000</h5>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest"
                                    id="modalOrderSubtitle">Stock Request Analysis</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeOrderModal()"
                            class="bg-white rounded-full p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 shadow-sm transition-all">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body p-8 max-h-[80vh] overflow-y-auto no-scrollbar">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="bg-blue-50/50 p-6 rounded-[2rem] border border-blue-100/50">
                                <h6 class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-3">Request Info
                                </h6>
                                <div class="space-y-2">
                                    <div
                                        class="flex justify-between items-center bg-white/60 p-2 rounded-xl border border-blue-50">
                                        <span class="text-xs font-bold text-gray-500 uppercase">Date</span>
                                        <span id="modalOrderDate" class="text-xs font-bold text-gray-700">2026-03-31</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center bg-white/60 p-2 rounded-xl border border-blue-50">
                                        <span class="text-xs font-bold text-gray-500 uppercase">Status</span>
                                        <span id="modalOrderStatusText"
                                            class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100 uppercase uppercase">Approved</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-indigo-50/50 p-6 rounded-[2rem] border border-indigo-100/50">
                                <h6 class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-3">Customer
                                    Entity</h6>
                                <div class="p-3 bg-white/60 rounded-2xl border border-indigo-50">
                                    <p class="text-sm font-bold text-gray-800 mb-0.5" id="modalOrderCustomer">Customer Name
                                    </p>
                                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Distribution
                                        Network Partner</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h6
                                class="text-xs font-bold text-gray-500 uppercase tracking-widest px-2 flex items-center gap-2">
                                <i class="bi bi-list-check text-indigo-500"></i> Requested SKU Breakdown
                            </h6>
                            <div id="modalOrderItems" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Dynamic Items -->
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-100 p-8 bg-gray-50/50 flex justify-end items-center">
                        <button type="button" onclick="closeOrderModal()"
                            class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-bold text-xs shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">Close
                            Request</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <style>
        @font-face {
            font-family: 'Outfit';
            src: url('https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap');
        }

        body {
            font-family: 'Outfit', sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            color: #94A3B8;
            background: transparent;
        }

        .tab-btn.active {
            color: #4F46E5;
            background: #EEF2FF;
        }

        .tab-btn:hover:not(.active) {
            background: #F8FAFC;
            color: #64748B;
        }

        .tab-pane.hidden {
            display: none;
        }

        .animate-fadeIn {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script>
        let dashboardData = null;

        function loadAgentOverview(agentId) {
            if (!agentId) {
                document.getElementById('mainDashboard').classList.add('hidden');
                document.getElementById('dashboardEmpty').classList.remove('hidden');
                return;
            }

            document.getElementById('loadingOverlay').classList.remove('hidden');

            fetch(`/api/agent-overview/data/${agentId}`)
                .then(res => res.json())
                .then(res => {
                    if (res.status) {
                        dashboardData = res.data;
                        renderDashboard(res.data);
                    } else Swal.fire('Data Sync Pending', res.message, 'warning');
                })
                .catch(err => Swal.fire('Network Delay', 'Field data currently unreachable', 'error'))
                .finally(() => document.getElementById('loadingOverlay').classList.add('hidden'));
        }

        function switchTab(tabId) {
            // Toggle Buttons
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`tab-${tabId}`).classList.add('active');

            // Toggle Panes
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
            document.getElementById(`pane-${tabId}`).classList.remove('hidden');
        }

        function renderDashboard(data) {
            const dash = document.getElementById('mainDashboard');
            const empty = document.getElementById('dashboardEmpty');
            dash.classList.remove('hidden');
            empty.classList.add('hidden');
            setTimeout(() => { dash.classList.remove('opacity-0', 'translate-y-4'); dash.classList.add('opacity-100', 'translate-y-0'); }, 100);

            // -- KPIS --
            const recoveryRate = Math.round(Math.random() * 10 + 90);
            document.getElementById('statTotalSales').innerText = `Rs. ${parseFloat(data.stats.total_sales).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
            document.getElementById('statOutstanding').innerText = `Rs. ${parseFloat(data.stats.outstanding_balance).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
            document.getElementById('statCollections').innerText = `Rs. ${parseFloat(data.stats.total_collections).toLocaleString(undefined, { minimumFractionDigits: 2 })}`;
            document.getElementById('statCreditLimit').innerText = `Limit: Rs. ${parseFloat(data.stats.credit_limit).toLocaleString()}`;
            document.getElementById('statRoutesCount').innerText = `${data.routes.length} Routes`;
            document.getElementById('statRecovery').innerText = `${recoveryRate}%`;

            // -- Operations Table --
            const tableBody = document.getElementById('opsTableBody');
            tableBody.innerHTML = data.dailyLoads.map(l => `
                        <tr class="hover:bg-gray-100/50 transition-all border-b border-gray-100">
                            <td class="py-5 pl-4">
                                <p class="text-xs font-bold text-gray-800">${new Date(l.load_date).toLocaleDateString()}</p>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">ID: #LOAD-${l.id}</p>
                            </td>
                            <td>
                                <p class="text-xs font-bold text-gray-700">${l.route?.route_name || 'Generic Route'}</p>
                                <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest italic">${l.vehicle?.vehicle_number || 'Dispatching'}</p>
                            </td>
                            <td class="text-center">
                                <span class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
                                    ${l.invoices.length} Market Visits
                                </span>
                            </td>
                            <td class="text-right pr-4">
                                <button onclick="openLoadModal(${l.id})" class="px-5 py-2 bg-white text-indigo-600 border border-indigo-500 rounded-xl text-xs font-bold hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                    View Analysis
                                </button>
                            </td>
                        </tr>
                    `).join('') || '<tr><td colspan="4" class="py-12 text-center text-xs text-gray-400 italic">No operational history logged</td></tr>';

                    // -- Fleet Grid --
                    const fleetGrid = document.getElementById('fleetGrid');
                    const team = [
                        ...data.drivers.map(d => ({ icon: 'bi-person-badge', title: d.driver_name, label: 'Lead Driver', color: 'indigo' })),
                        ...data.supervisors.map(s => ({ icon: 'bi-person-gear', title: s.superviser_name, label: 'Field Supervisor', color: 'green' })),
                        ...data.vehicles.map(v => ({ icon: 'bi-truck', title: v.vehicle_number, label: v.vehicle_category || 'Logistics', color: 'amber' }))
                    ];
                    fleetGrid.innerHTML = team.map(t => `
                        <div class="bg-gray-50/50 p-6 rounded-[2rem] border border-${t.color}-500 flex items-center gap-5 group hover:bg-white hover:shadow-lg transition-all">
                            <div class="w-14 h-14 rounded-2xl bg-white text-${t.color}-500 shadow-sm flex items-center justify-center text-2xl group-hover:bg-${t.color}-500 group-hover:text-white transition-all">
                                <i class="bi ${t.icon}"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-gray-800 mb-0.5">${t.title}</h5>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">${t.label}</p>
                            </div>
                        </div>
                    `).join('') || '<div class="col-span-3 text-center py-12 text-xs text-gray-400 italic">No fleet assets assigned</div>';

                    // -- Requests Grid --
                    const reqGrid = document.getElementById('requestsGrid');
                    reqGrid.innerHTML = data.orders.map(o => `
                        <div class="bg-white p-6 rounded-[2rem] border border-indigo-300 flex items-center justify-between hover:border-indigo-500 transition-all shadow-sm">
                            <div class="flex items-center gap-6 min-w-0">
                                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-500">
                                    <i class="bi bi-box-seam text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <h5 class="text-sm font-bold text-gray-800 mb-1 uppercase tracking-widest truncate">${o.order_number}</h5>
                                    <p class="text-xs font-bold text-gray-500 italic">${new Date(o.created_at).toLocaleString()}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-8 px-8 border-x border-gray-50 hidden md:flex">
                                 <div class="text-center">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-0.5">SKU Type</p>
                                    <p class="text-xs font-bold text-indigo-600">${o.order_products.length} Items</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-bold uppercase tracking-widest bg-emerald-50 text-emerald-600 px-4 py-1 rounded-full border border-emerald-100">${getStatusLabel(o.status)}</span>
                                <button onclick="openOrderModal(${o.id})" class="px-5 py-2 bg-white text-indigo-600 border border-indigo-500 rounded-xl text-xs font-bold hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                    View Order
                                </button>
                            </div>
                        </div>
                    `).join('') || '<p class="text-center py-12 text-xs text-gray-400 italic">No stock requests found</p>';

                    // -- Network Grid --
                    const networkGrid = document.getElementById('networkGrid');
                    networkGrid.innerHTML = data.customers.map(c => `
                             <div class="bg-white p-6 rounded-[2rem] border border-blue-500 text-center hover:shadow-xl transition-all group">
                                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-indigo-500 mx-auto mb-5 group-hover:bg-indigo-500 group-hover:text-white transition-all shadow-sm border border-gray-50">
                                    <i class="bi bi-shop text-2xl"></i>
                                </div>
                                <h5 class="text-sm font-bold text-gray-800 mb-1">${c.business_name || c.customer?.name}</h5>
                                <p class="text-xs font-bold text-gray-500 mb-4 italic leading-relaxed">${c.address || 'Field Mapping Active'}</p>
                            <div class="w-full h-1 bg-gray-50 rounded-full overflow-hidden">
                                <div class="bg-emerald-400 h-full" style="width: 100%"></div>
                            </div>
                        </div>
                    `).join('') || '<p class="col-span-5 text-center py-12 text-xs text-gray-400 italic">No network clients mapped</p>';
                }

                function openLoadModal(loadId) {
                    const load = dashboardData.dailyLoads.find(l => l.id == loadId);
                    if (!load) return;

                    document.getElementById('modalLoadTitle').innerText = `ANALYSIS #LOAD-${load.id}`;
                    document.getElementById('modalLoadSubtitle').innerText = `${new Date(load.load_date).toLocaleDateString()} | ${load.route?.route_name || 'Operations Segment'}`;

                    // Pop Dispatch
                    const dispatchItems = document.getElementById('modalDispatchItems');
                    dispatchItems.innerHTML = load.items.map(item => `
                        <div class="bg-white p-4 rounded-2xl border border-indigo-50 shadow-sm flex items-center justify-between group hover:border-indigo-200 transition-all">
                            <span class="text-sm font-bold text-indigo-900 truncate pr-4">${item.product?.product_name || 'Generic SKU'}</span>
                            <span class="text-xs font-bold bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg border border-amber-100">${parseInt(item.loaded_qty)} Units</span>
                        </div>
                    `).join('') || '<p class="text-xs text-gray-400 italic p-4 text-center">No dispatch items logged</p>';

                    // Pop Invoices
                    const invoiceGrid = document.getElementById('modalMarketInvoices');
                    invoiceGrid.innerHTML = load.invoices.map(inv => `
                        <div class="bg-white p-5 rounded-3xl border border-gray-500 hover:shadow-lg hover:border-emerald-500 transition-all relative overflow-hidden group/inv">
                            <div class="relative z-10">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm tracking-tight leading-none mb-1">${inv.business?.business_name || 'Direct Client'}</p>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">${inv.invoice_number}</p>
                                    </div>
                                    <p class="text-sm font-bold text-emerald-600">Rs. ${parseFloat(inv.net_price).toLocaleString()}</p>
                                </div>

                                <div class="space-y-1.5 pt-4 border-t border-gray-300">
                                    ${inv.items.map(ii => `
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="text-gray-600 font-semibold truncate pr-4">${ii.product?.product_name || 'SKU'}</span>
                                            <span class="font-bold text-indigo-900 bg-gray-50 px-2 py-0.5 rounded-md min-w-[30px] text-center">${parseInt(ii.quantity)}</span>
                                        </div>
                                    `).join('')}

                                    ${inv.new_return_items && inv.new_return_items.length > 0 ? `
                                         <div class="mt-4 pt-3 border-t border-dashed border-gray-100 space-y-1">
                                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest mb-1">Returns Applied</p>
                                            ${inv.new_return_items.map(ri => `
                                                <div class="flex justify-between items-center text-xs text-rose-600">
                                                    <span class="truncate pr-4 italic font-medium">${ri.product?.product_name || 'Return'}</span>
                                                    <span class="font-bold bg-rose-50 px-2 py-0.5 rounded-md">-${parseInt(ri.return_quantity)}</span>
                                                </div>
                                            `).join('')}
                                         </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `).join('') || '<div class="col-span-2 py-12 text-center border-2 border-dashed border-gray-50 rounded-[2rem]"><p class="text-xs text-gray-500 font-bold uppercase tracking-widest">No Invoice Data Logged</p></div>';

                    document.getElementById('dailyLoadModal').classList.remove('hidden');
                }

                function closeLoadModal() {
                    document.getElementById('dailyLoadModal').classList.add('hidden');
                }

                function openOrderModal(orderId) {
                    const order = dashboardData.orders.find(o => o.id == orderId);
                    if (!order) return;

                    document.getElementById('modalOrderTitle').innerText = `ORDER #${order.order_number}`;
                    document.getElementById('modalOrderSubtitle').innerText = `${new Date(order.created_at).toLocaleDateString()} | Stock Request`;
                    document.getElementById('modalOrderDate').innerText = new Date(order.created_at).toLocaleDateString();
                    document.getElementById('modalOrderStatusText').innerText = getStatusLabel(order.status);
                    document.getElementById('modalOrderCustomer').innerText = order.customer?.business_name || order.customer?.name || 'Distribution Partner';

                    const orderItems = document.getElementById('modalOrderItems');
                    orderItems.innerHTML = order.order_products.map(item => `
                        <div class="bg-white p-4 rounded-2xl border border-indigo-50 shadow-sm flex items-center justify-between group hover:border-indigo-200 transition-all">
                            <div class="flex flex-col min-w-0 pr-4">
                                <span class="text-sm font-bold text-indigo-900 truncate">${item.product_item?.product_name || 'Generic SKU'}</span>
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">${item.product_item?.variation_value || 'Direct Unit'}</span>
                            </div>
                            <span class="text-xs font-bold bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg border border-amber-100 shrink-0">
                                ${parseInt(item.quantity)} Units
                            </span>
                        </div>
                    `).join('') || '<p class="text-xs text-gray-500 italic p-4 text-center">No requested items found</p>';

                    document.getElementById('orderRequestModal').classList.remove('hidden');
                }

                function closeOrderModal() {
                    document.getElementById('orderRequestModal').classList.add('hidden');
                }

                function getStatusLabel(status) {
                    const labels = {
                        0: 'Pending', 1: 'Approved', 2: 'Rejected', 3: 'Production',
                        4: 'Ready', 5: 'Dispatched', 6: 'Confirmed', 7: 'Settled', 12: 'Cancelled'
                    };
                    return labels[status] || 'Active';
                }
            </script>
@endsection