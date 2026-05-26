@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#EDEFF5]">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#F59E0B] to-[#D97706] rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="bi bi-sign-turn-right text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Route Management</h1>
                            <p class="text-gray-500 text-xs sm:text-sm">View routes, customers & map visualization</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Split Panel -->
        <div class="flex flex-col lg:flex-row h-[calc(100vh-180px)]">

            <!-- LEFT PANEL: Routes List -->
            <div class="w-full lg:w-[420px] xl:w-[460px] flex-shrink-0 border-r border-gray-200 bg-white flex flex-col overflow-hidden">

                <!-- Agent Select & Search -->
                <div class="p-4 border-b border-gray-100 bg-gray-50/80 space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Select Agent</label>
                        <div class="relative" id="customAgentDropdown">
                            <!-- Trigger Button -->
                            <button type="button" onclick="toggleAgentDropdown()" id="agentDropdownBtn" 
                                class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 text-sm bg-white font-medium shadow-sm transition-all text-left flex items-center justify-between cursor-pointer">
                                <span id="selectedAgentLabel" class="truncate text-gray-700">All Agents</span>
                                <i class="bi bi-chevron-down text-gray-400 text-xs transition-transform duration-200" id="agentChevron"></i>
                            </button>
                            
                            <!-- Icon overlay -->
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                                <i class="bi bi-person-badge"></i>
                            </div>

                            <!-- Dropdown Panel -->
                            <div id="agentDropdownPanel" class="hidden absolute left-0 right-0 mt-1.5 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                                <!-- Search Field -->
                                <div class="p-2 border-b border-gray-100 bg-gray-50/50">
                                    <div class="relative">
                                        <i class="bi bi-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                        <input type="text" id="agentDropdownSearch" placeholder="Search agents..." 
                                            class="w-full pl-8 pr-3 py-1.5 border border-gray-100 rounded-lg text-xs focus:ring-1 focus:ring-amber-400 focus:border-amber-400 bg-white"
                                            oninput="filterAgentDropdownOptions(event)">
                                    </div>
                                </div>
                                <!-- Options List -->
                                <div class="max-h-48 overflow-y-auto p-1 space-y-0.5" id="agentDropdownOptions">
                                    <div class="agent-option px-3 py-2 text-xs font-semibold text-amber-600 bg-amber-50/60 rounded-lg cursor-pointer hover:bg-amber-50" 
                                        data-value="" onclick="selectAgentOption('', 'All Agents')">
                                        All Agents
                                    </div>
                                    @foreach($agents as $agent)
                                        <div class="agent-option px-3 py-2 text-xs text-gray-700 rounded-lg cursor-pointer hover:bg-amber-50 hover:text-amber-800 transition-colors" 
                                            data-value="{{ $agent['id'] }}" 
                                            data-search="{{ strtolower($agent['agentName'] . ' ' . $agent['agentCode']) }}"
                                            onclick="selectAgentOption('{{ $agent['id'] }}', '{{ $agent['agentName'] }} ({{ $agent['agentCode'] }})')">
                                            {{ $agent['agentName'] }} <span class="text-gray-400 font-mono font-normal text-[10px] ml-1">({{ $agent['agentCode'] }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="agentSelect" value="">
                    </div>

                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search routes by name, code..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-400/40 focus:border-amber-400 text-sm bg-white transition-all"
                            oninput="filterRoutes()">
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-gray-500 font-medium" id="routeCountLabel">
                            {{ count($routes) }} routes
                        </span>
                        <div class="flex gap-1">
                            <button onclick="filterByStatus('all')" id="filterAll"
                                class="px-2.5 py-1 text-xs rounded-full font-medium transition-all bg-gray-900 text-white">All</button>
                            <button onclick="filterByStatus('active')" id="filterActive"
                                class="px-2.5 py-1 text-xs rounded-full font-medium transition-all bg-gray-100 text-gray-600 hover:bg-gray-200">Active</button>
                            <button onclick="filterByStatus('inactive')" id="filterInactive"
                                class="px-2.5 py-1 text-xs rounded-full font-medium transition-all bg-gray-100 text-gray-600 hover:bg-gray-200">Inactive</button>
                        </div>
                    </div>
                </div>

                <!-- Routes List -->
                <div class="flex-1 overflow-y-auto p-3 space-y-2" id="routesList">
                    @forelse($routes as $route)
                        <div class="route-card group cursor-pointer rounded-xl border-2 border-transparent bg-white hover:bg-amber-50/30 transition-all duration-300 relative overflow-hidden"
                            data-route-id="{{ $route['id'] }}"
                            data-status="{{ $route['status'] }}"
                            data-agent-id="{{ $route['agentId'] ?? '' }}"
                            data-search="{{ strtolower($route['routeName'] . ' ' . $route['routeCode'] . ' ' . ($route['agentName'] ?? '')) }}"
                            onclick="selectRoute({{ $route['id'] }})">

                            <!-- Accent bar -->
                            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-xl transition-colors duration-300
                                {{ $route['status'] == 1 ? 'bg-emerald-400' : 'bg-gray-300' }}"></div>

                            <div class="p-4 pl-5">
                                <!-- Route Header -->
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-gray-900 font-bold text-sm truncate">{{ $route['routeName'] }}</h3>
                                            @if($route['status'] == 1)
                                                <span class="flex-shrink-0 w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                            @else
                                                <span class="flex-shrink-0 w-2 h-2 rounded-full bg-gray-300"></span>
                                            @endif
                                        </div>
                                        <p class="text-gray-400 text-xs font-mono mt-0.5">{{ $route['routeCode'] }}</p>
                                    </div>
                                </div>

                                <!-- Route Meta -->
                                <div class="flex items-center flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500">
                                    <span class="flex items-center gap-1.5">
                                        <i class="bi bi-person-badge text-gray-400"></i>
                                        <span class="truncate max-w-[120px]">{{ $route['agentName'] ?? 'Unassigned' }}</span>
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <i class="bi bi-people text-gray-400"></i>
                                        {{ $route['customerCount'] }} {{ $route['customerCount'] == 1 ? 'stop' : 'stops' }}
                                    </span>
                                    @if($route['target_distance_km'])
                                        <span class="flex items-center gap-1.5">
                                            <i class="bi bi-signpost-2 text-gray-400"></i>
                                            {{ $route['target_distance_km'] }} km
                                        </span>
                                    @endif
                                </div>

                                <!-- Customer Preview (first 3) -->
                                @if($route['customerCount'] > 0)
                                    <div class="mt-3 pt-2 border-t border-gray-100">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            @foreach(array_slice($route['customers'], 0, 3) as $i => $cust)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-50 text-gray-600 text-[10px] rounded-full border border-gray-100">
                                                    <span class="w-3.5 h-3.5 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-[8px] font-bold flex-shrink-0">{{ $i + 1 }}</span>
                                                    <span class="truncate max-w-[80px]">{{ $cust['businessName'] }}</span>
                                                </span>
                                            @endforeach
                                            @if($route['customerCount'] > 3)
                                                <span class="text-[10px] text-gray-400 font-medium">+{{ $route['customerCount'] - 3 }} more</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center" id="noRoutesMsg">
                            <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                <i class="bi bi-sign-turn-right text-3xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-900 text-base font-semibold mb-1">No routes found</h3>
                            <p class="text-gray-500 text-sm mb-4">Create your first route to start managing deliveries</p>
                            <button onclick="openRouteModal()"
                                class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors">
                                <i class="bi bi-plus-lg mr-2"></i>Create Route
                            </button>
                        </div>
                    @endforelse

                    <!-- No Search Results -->
                    <div id="noSearchResults" class="hidden flex flex-col items-center justify-center py-16 text-center">
                        <i class="bi bi-search text-4xl text-gray-300 mb-3"></i>
                        <h3 class="text-gray-700 text-sm font-semibold">No matching routes</h3>
                        <p class="text-gray-400 text-xs mt-1">Try adjusting your search terms</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL: Map & Route Details -->
            <div class="flex-1 flex flex-col bg-gray-100 overflow-hidden relative">

                <!-- Map Info Bar (shown when route selected) -->
                <div id="mapInfoBar" class="hidden bg-white/95 backdrop-blur-sm border-b border-gray-200 px-5 py-3 z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-sm">
                                <i class="bi bi-geo-alt-fill text-white text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-gray-900 font-bold text-sm" id="selectedRouteName">-</h3>
                                <p class="text-gray-500 text-xs" id="selectedRouteCode">-</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-center">
                                <div class="text-sm font-bold text-gray-900" id="mapStatStops">0</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-wider">Stops</div>
                            </div>
                            <div class="w-px h-8 bg-gray-200"></div>
                            <div class="text-center">
                                <div class="text-sm font-bold text-gray-900" id="mapStatDistance">0 km</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-wider">Distance</div>
                            </div>
                            <div class="w-px h-8 bg-gray-200"></div>
                            <div class="text-center">
                                <div class="text-sm font-bold text-gray-900" id="mapStatAgent">-</div>
                                <div class="text-[10px] text-gray-500 uppercase tracking-wider">Agent</div>
                            </div>
                            <div class="w-px h-8 bg-gray-200"></div>
                            <button onclick="openRouteCustomersModal()" class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-3.5 py-1.5 rounded-lg flex items-center gap-1.5 shadow-sm text-xs font-bold transition-all transform active:scale-95 cursor-pointer">
                                <i class="bi bi-people-fill"></i>
                                View Customers
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Map Container -->
                <div class="flex-1 relative">
                    <!-- Map -->
                    <div id="routeMap" class="w-full h-full"></div>

                    <!-- Empty State (no route selected) -->
                    <div id="mapEmptyState" class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                        <div class="text-center max-w-sm">
                            <div class="relative inline-block mb-6">
                                <div class="w-24 h-24 rounded-3xl bg-gradient-to-br from-amber-100 to-amber-50 flex items-center justify-center shadow-inner">
                                    <i class="bi bi-map text-4xl text-amber-400"></i>
                                </div>
                                <div class="absolute -top-1 -right-1 w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center shadow-md animate-bounce">
                                    <i class="bi bi-cursor-fill text-white text-xs"></i>
                                </div>
                            </div>
                            <h3 class="text-gray-800 text-lg font-bold mb-2">Select a Route</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">Click on any route from the left panel to visualize its path and customer locations on the map</p>
                        </div>
                    </div>

                    <!-- Customer List Overlay (bottom sheet style) -->
                    <div id="customerListPanel"
                        class="hidden absolute bottom-0 left-0 right-0 max-h-[45%] bg-white/95 backdrop-blur-md rounded-t-2xl shadow-[0_-4px_24px_rgba(0,0,0,0.1)] z-10 flex flex-col transition-all duration-500 ease-out transform translate-y-full"
                        style="transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                        <!-- Drag handle -->
                        <div class="flex justify-center pt-2 pb-1 cursor-pointer" onclick="toggleCustomerPanel()">
                            <div class="w-10 h-1 rounded-full bg-gray-300"></div>
                        </div>
                        <div class="px-5 pb-2 flex items-center justify-between">
                            <h4 class="text-gray-900 font-bold text-sm">
                                <i class="bi bi-geo-alt text-amber-500 mr-1.5"></i>Route Stops
                                <span class="text-gray-400 font-normal ml-1" id="customerPanelCount">(0)</span>
                            </h4>
                            <button onclick="toggleCustomerPanel()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="bi bi-chevron-down text-lg"></i>
                            </button>
                        </div>
                        <div class="flex-1 overflow-y-auto px-4 pb-4" id="customerListContent">
                            <!-- Customer items rendered via JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Details Modal -->
        <div id="customer-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="customer-modal-title" role="dialog" aria-modal="true">
            <div id="customer-backdrop" class="fixed inset-0 bg-gray-900/70 transition-opacity opacity-0 duration-300 ease-out" onclick="closeCustomerModal()"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div id="customer-panel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4 flex items-center justify-between text-white">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="bi bi-shop text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-base" id="custModalBusinessName">Customer Details</h3>
                                <span class="text-[10px] text-amber-100 tracking-wider uppercase font-semibold" id="custModalB2BType">B2B Customer</span>
                            </div>
                        </div>
                        <button onclick="closeCustomerModal()" class="text-white/80 hover:text-white transition-colors">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-5 space-y-4">
                        <!-- Info Cards -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <span class="block text-[10px] text-gray-400 uppercase font-semibold">Stop Sequence</span>
                                <span class="text-base font-extrabold text-amber-600 mt-0.5 block" id="custModalSequence">Stop #1</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <span class="block text-[10px] text-gray-400 uppercase font-semibold">Saved Metrics</span>
                                <span class="text-xs font-semibold text-gray-700 mt-1 block" id="custModalMetrics">-</span>
                            </div>
                        </div>

                        <!-- Details list -->
                        <div class="space-y-3.5">
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center flex-shrink-0 text-amber-600">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="block text-[10px] text-gray-400 uppercase font-semibold">Address</span>
                                    <p class="text-sm text-gray-700 mt-0.5 leading-relaxed font-medium" id="custModalAddress">-</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center flex-shrink-0 text-amber-600">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="block text-[10px] text-gray-400 uppercase font-semibold">Contact Person</span>
                                    <p class="text-sm text-gray-700 mt-0.5 font-semibold" id="custModalContact">-</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center flex-shrink-0 text-amber-600">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="block text-[10px] text-gray-400 uppercase font-semibold">Phone Number</span>
                                    <p class="text-sm text-gray-700 mt-0.5 font-semibold" id="custModalPhone">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-100">
                        <button onclick="closeCustomerModal()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Route Customers Modal -->
        <div id="route-customers-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="route-customers-modal-title" role="dialog" aria-modal="true">
            <div id="route-customers-backdrop" class="fixed inset-0 bg-gray-900/70 transition-opacity opacity-0 duration-300 ease-out" onclick="closeRouteCustomersModal()"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div id="route-customers-panel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-100 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out flex flex-col max-h-[90vh]">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4 flex items-center justify-between text-white flex-shrink-0">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                <i class="bi bi-signpost-2 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-base" id="routeCustomersModalTitle">Route Customers</h3>
                                <span class="text-[10px] text-amber-100 tracking-wider uppercase font-semibold" id="routeCustomersModalSubtitle">Customer details card view</span>
                            </div>
                        </div>
                        <button onclick="closeRouteCustomersModal()" class="text-white/80 hover:text-white transition-colors">
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-5 overflow-y-auto bg-gray-50/50 flex-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="routeCustomersContainer">
                            <!-- Cards populated dynamically -->
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-100 flex-shrink-0">
                        <button onclick="closeRouteCustomersModal()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .route-card.selected {
            border-color: #D4A017 !important;
            background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%) !important;
            box-shadow: 0 0 0 1px rgba(212, 160, 23, 0.2), 0 4px 16px rgba(212, 160, 23, 0.12) !important;
        }
        .route-card.selected .absolute.left-0 {
            background: linear-gradient(to bottom, #D4A017, #B8860B) !important;
            width: 3px !important;
        }
        .route-card:not(.selected):hover {
            border-color: #E5E7EB;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        /* Customer list item hover */
        .customer-item {
            transition: all 0.2s ease;
        }
        .customer-item:hover {
            background: #FFFBEB;
            transform: translateX(4px);
        }

        /* Scrollbar styling */
        #routesList::-webkit-scrollbar,
        #customerListContent::-webkit-scrollbar {
            width: 4px;
        }
        #routesList::-webkit-scrollbar-track,
        #customerListContent::-webkit-scrollbar-track {
            background: transparent;
        }
        #routesList::-webkit-scrollbar-thumb,
        #customerListContent::-webkit-scrollbar-thumb {
            background: #D1D5DB;
            border-radius: 100px;
        }
        #routesList::-webkit-scrollbar-thumb:hover,
        #customerListContent::-webkit-scrollbar-thumb:hover {
            background: #9CA3AF;
        }

        /* Slide up animation for customer panel */
        #customerListPanel.visible {
            transform: translateY(0) !important;
        }

        /* Map custom InfoWindow */
        .gm-style-iw-d { overflow: hidden !important; }
        .gm-style-iw { padding: 0 !important; }

        /* Pulse animation for selected marker */
        @keyframes markerPulse {
            0% { box-shadow: 0 0 0 0 rgba(212, 160, 23, 0.5); }
            70% { box-shadow: 0 0 0 12px rgba(212, 160, 23, 0); }
            100% { box-shadow: 0 0 0 0 rgba(212, 160, 23, 0); }
        }
    </style>

    <script>
        // ==========================================
        // STATE
        // ==========================================
        const allRoutes = @json($routes);
        let selectedRouteId = null;
        let map = null;
        let mapMarkers = [];
        let mapPolyline = null;
        let directionsRenderer = null;
        let directionsService = null;
        let infoWindows = [];
        let currentFilter = 'all';
        let customerPanelOpen = false;

        // ==========================================
        // CUSTOM SEARCHABLE DROPDOWN FOR AGENTS
        // ==========================================
        function toggleAgentDropdown() {
            const panel = document.getElementById('agentDropdownPanel');
            const chevron = document.getElementById('agentChevron');
            const isHidden = panel.classList.contains('hidden');
            
            if (isHidden) {
                panel.classList.remove('hidden');
                chevron.classList.add('rotate-180');
                // Focus search input
                setTimeout(() => document.getElementById('agentDropdownSearch').focus(), 50);
            } else {
                closeAgentDropdown();
            }
        }

        function closeAgentDropdown() {
            const panel = document.getElementById('agentDropdownPanel');
            const chevron = document.getElementById('agentChevron');
            if (panel) panel.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
            const searchInput = document.getElementById('agentDropdownSearch');
            if (searchInput) {
                searchInput.value = '';
                filterAgentDropdownOptions({ target: { value: '' } });
            }
        }

        function selectAgentOption(value, label) {
            document.getElementById('agentSelect').value = value;
            document.getElementById('selectedAgentLabel').innerText = label;
            
            // Highlight selected option
            document.querySelectorAll('.agent-option').forEach(opt => {
                if (opt.getAttribute('data-value') === value) {
                    opt.classList.add('bg-amber-50', 'text-amber-800', 'font-semibold');
                    opt.classList.remove('text-gray-700');
                } else {
                    opt.classList.remove('bg-amber-50', 'text-amber-800', 'font-semibold');
                    opt.classList.add('text-gray-700');
                }
            });

            closeAgentDropdown();
            filterRoutes();
        }

        function filterAgentDropdownOptions(event) {
            const searchVal = (event?.target?.value || '').toLowerCase();
            const options = document.querySelectorAll('.agent-option');
            
            options.forEach(opt => {
                const searchData = opt.getAttribute('data-search') || '';
                const isAllOption = opt.getAttribute('data-value') === '';
                
                if (isAllOption || searchData.includes(searchVal)) {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('customAgentDropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                closeAgentDropdown();
            }
        });

        // ==========================================
        // CUSTOMER MODAL
        // ==========================================
        const customerModal = document.getElementById('customer-modal');
        const customerBackdrop = document.getElementById('customer-backdrop');
        const customerPanel = document.getElementById('customer-panel');

        function openCustomerDetails(cust, index) {
            document.getElementById('custModalBusinessName').innerText = cust.businessName || 'Customer Details';
            document.getElementById('custModalB2BType').innerText = cust.b2bType ? cust.b2bType.replace(/_/g, ' ') : 'B2B Customer';
            document.getElementById('custModalSequence').innerText = `Stop #${index}`;
            
            let metricsText = '-';
            if (cust.distanceKm || cust.durationMinutes) {
                const dist = cust.distanceKm ? `${parseFloat(cust.distanceKm).toFixed(1)} km` : '';
                const dur = cust.durationMinutes ? `${Math.round(cust.durationMinutes)} mins` : '';
                metricsText = [dist, dur].filter(Boolean).join(' · ');
            }
            document.getElementById('custModalMetrics').innerText = metricsText;
            document.getElementById('custModalAddress').innerText = cust.address || 'No address provided';
            document.getElementById('custModalContact').innerText = cust.contactPerson || 'Not specified';
            document.getElementById('custModalPhone').innerText = cust.phone || 'No phone number';

            customerModal.classList.remove('hidden');
            void customerModal.offsetWidth;
            customerBackdrop.classList.remove('opacity-0');
            customerBackdrop.classList.add('opacity-100');
            customerPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            customerPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }

        function closeCustomerModal() {
            customerBackdrop.classList.remove('opacity-100');
            customerBackdrop.classList.add('opacity-0');
            customerPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            customerPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => { customerModal.classList.add('hidden'); }, 300);
        }

        function openCustomerDetailsByIndex(routeId, customerIndex) {
            const route = allRoutes.find(r => r.id === routeId);
            if (!route || !route.customers || !route.customers[customerIndex]) return;
            openCustomerDetails(route.customers[customerIndex], customerIndex + 1);
        }

        // ==========================================
        // ROUTE CUSTOMERS MODAL
        // ==========================================
        function openRouteCustomersModal() {
            const route = allRoutes.find(r => r.id === selectedRouteId);
            if (!route) return;

            document.getElementById('routeCustomersModalTitle').innerText = `Customers on Route: ${route.routeName}`;
            document.getElementById('routeCustomersModalSubtitle').innerText = `${route.customerCount} assigned stops`;

            const container = document.getElementById('routeCustomersContainer');
            container.innerHTML = '';

            const customers = route.customers || [];
            if (customers.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-400">
                        <i class="bi bi-geo-alt text-4xl mb-2"></i>
                        <p class="text-sm font-medium">No customers assigned to this route</p>
                    </div>
                `;
            } else {
                customers.forEach((cust, index) => {
                    const isFirst = index === 0;
                    const isLast = index === customers.length - 1;
                    const dotColor = isFirst ? 'emerald' : (isLast ? 'red' : 'amber');
                    const badgeText = isFirst ? 'START' : (isLast ? 'END' : `STOP #${index + 1}`);
                    const badgeBg = isFirst ? 'bg-emerald-100 text-emerald-700' : (isLast ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700');

                    let metricsText = 'No route statistics yet';
                    if (cust.distanceKm || cust.durationMinutes) {
                        const dist = cust.distanceKm ? `${parseFloat(cust.distanceKm).toFixed(1)} km` : '';
                        const dur = cust.durationMinutes ? `${Math.round(cust.durationMinutes)} mins` : '';
                        metricsText = [dist, dur].filter(Boolean).join(' · ');
                    }

                    const card = document.createElement('div');
                    card.className = `bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition-all duration-300 relative overflow-hidden group`;
                    card.innerHTML = `
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-${isFirst ? 'emerald' : (isLast ? 'red' : 'amber')}-500"></div>

                        <div>
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <h4 class="font-bold text-gray-900 text-base leading-snug group-hover:text-amber-600 transition-colors">${cust.businessName}</h4>
                                    <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">${cust.b2bType ? cust.b2bType.replace(/_/g, ' ') : 'CUSTOMER'}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold ${badgeBg}">${badgeText}</span>
                            </div>

                            <div class="space-y-2 text-xs text-gray-600">
                                <div class="flex items-start gap-2">
                                    <i class="bi bi-geo-alt text-amber-500 mt-0.5 flex-shrink-0"></i>
                                    <span>${cust.address || 'No address provided'}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-person text-amber-500 flex-shrink-0"></i>
                                    <span>${cust.contactPerson || 'Not specified'}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-telephone text-amber-500 flex-shrink-0"></i>
                                    <span>${cust.phone || 'No phone number'}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-[10px] font-semibold text-gray-400"><i class="bi bi-signpost-2 mr-1"></i>${metricsText}</span>
                            <button onclick="closeRouteCustomersModal(); focusOnCustomer(${index});" class="text-xs bg-amber-500 hover:bg-amber-600 text-white font-bold py-1 px-3 rounded-lg flex items-center gap-1 transition-colors">
                                <i class="bi bi-geo-fill"></i> Show
                            </button>
                        </div>
                    `;
                    container.appendChild(card);
                });
            }

            const modal = document.getElementById('route-customers-modal');
            const backdrop = document.getElementById('route-customers-backdrop');
            const panel = document.getElementById('route-customers-panel');

            modal.classList.remove('hidden');
            void modal.offsetWidth;
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }

        function closeRouteCustomersModal() {
            const modal = document.getElementById('route-customers-modal');
            const backdrop = document.getElementById('route-customers-backdrop');
            const panel = document.getElementById('route-customers-panel');

            if (backdrop) backdrop.classList.remove('opacity-100');
            if (backdrop) backdrop.classList.add('opacity-0');
            if (panel) panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            if (panel) panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => { if (modal) modal.classList.add('hidden'); }, 300);
        }

        // ==========================================
        // SEARCH & FILTER
        // ==========================================
        function filterRoutes() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const selectedAgentId = document.getElementById('agentSelect').value;
            const cards = document.querySelectorAll('.route-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                const status = parseInt(card.getAttribute('data-status'));
                const agentId = card.getAttribute('data-agent-id');
                
                const matchesAgent = !selectedAgentId || agentId === selectedAgentId;
                const matchesSearch = searchData.includes(searchText);
                const matchesFilter = currentFilter === 'all' ||
                    (currentFilter === 'active' && status === 1) ||
                    (currentFilter === 'inactive' && status !== 1);

                if (matchesAgent && matchesSearch && matchesFilter) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('routeCountLabel').innerText = visibleCount + ' routes';
            const noResults = document.getElementById('noSearchResults');
            if (cards.length > 0 && visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        function filterByStatus(status) {
            currentFilter = status;
            // Update button styles
            ['All', 'Active', 'Inactive'].forEach(s => {
                const btn = document.getElementById('filter' + s);
                if (s.toLowerCase() === status) {
                    btn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                    btn.classList.add('bg-gray-900', 'text-white');
                } else {
                    btn.classList.remove('bg-gray-900', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                }
            });
            filterRoutes();
        }

        // ==========================================
        // ROUTE SELECTION & MAP
        // ==========================================
        function selectRoute(routeId) {
            selectedRouteId = routeId;
            const route = allRoutes.find(r => r.id === routeId);
            if (!route) return;

            // Update card selection
            document.querySelectorAll('.route-card').forEach(c => c.classList.remove('selected'));
            const selectedCard = document.querySelector(`.route-card[data-route-id="${routeId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
                selectedCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            // Update info bar
            document.getElementById('mapInfoBar').classList.remove('hidden');
            document.getElementById('selectedRouteName').innerText = route.routeName;
            document.getElementById('selectedRouteCode').innerText = route.routeCode + (route.description ? ' · ' + route.description : '');
            document.getElementById('mapStatStops').innerText = route.customerCount;
            document.getElementById('mapStatDistance').innerText = route.target_distance_km ? route.target_distance_km + ' km' : '-';
            document.getElementById('mapStatAgent').innerText = route.agentName || 'Unassigned';

            // Hide empty state
            document.getElementById('mapEmptyState').classList.add('hidden');

            // Initialize map if needed, then render
            if (!map) {
                initializeMap(() => renderRouteOnMap(route));
            } else {
                renderRouteOnMap(route);
            }

            // Render customer list panel
            renderCustomerList(route);
        }

        function initializeMap(callback) {
            const mapDiv = document.getElementById('routeMap');
            if (!mapDiv) return;

            // Wait for Google Maps to load
            if (typeof google === 'undefined' || !google.maps) {
                const checkInterval = setInterval(() => {
                    if (typeof google !== 'undefined' && google.maps) {
                        clearInterval(checkInterval);
                        createMap(mapDiv, callback);
                    }
                }, 200);
                setTimeout(() => clearInterval(checkInterval), 15000);
            } else {
                createMap(mapDiv, callback);
            }
        }

        function createMap(mapDiv, callback) {
            map = new google.maps.Map(mapDiv, {
                center: { lat: 6.9271, lng: 79.8612 },
                zoom: 12,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: [
                    { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] },
                    { featureType: 'transit', stylers: [{ visibility: 'off' }] },
                    { featureType: 'water', elementType: 'geometry.fill', stylers: [{ color: '#c8e8f5' }] },
                    { featureType: 'landscape.man_made', elementType: 'geometry.fill', stylers: [{ color: '#f2f0eb' }] },
                ],
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                }
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                polylineOptions: {
                    strokeColor: '#D4A017',
                    strokeWeight: 5,
                    strokeOpacity: 0.85,
                }
            });
            directionsRenderer.setMap(map);

            if (callback) callback();
        }

        function renderRouteOnMap(route) {
            if (!map) return;

            // Clear existing markers and polyline
            clearMapOverlays();

            const customers = route.customers || [];
            if (customers.length === 0) {
                map.setCenter({ lat: 6.9271, lng: 79.8612 });
                map.setZoom(12);
                return;
            }

            const bounds = new google.maps.LatLngBounds();

            // Create markers
            customers.forEach((cust, index) => {
                const position = { lat: cust.latitude, lng: cust.longitude };
                bounds.extend(position);

                // Custom marker with sequence number
                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    label: {
                        text: String(index + 1),
                        color: '#FFFFFF',
                        fontSize: '11px',
                        fontWeight: '700'
                    },
                    title: cust.businessName,
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 16,
                        fillColor: index === 0 ? '#059669' : (index === customers.length - 1 ? '#DC2626' : '#D4A017'),
                        fillOpacity: 1,
                        strokeColor: '#FFFFFF',
                        strokeWeight: 3,
                    },
                    zIndex: 100 + index,
                    animation: google.maps.Animation.DROP,
                });

                // Info window with premium styling
                const typeLabel = cust.b2bType ? cust.b2bType.replace(/_/g, ' ').toUpperCase() : 'CUSTOMER';
                const distanceInfo = cust.distanceKm ? `<span style="color:#059669; font-weight:600">${parseFloat(cust.distanceKm).toFixed(1)} km</span>` : '';
                const durationInfo = cust.durationMinutes ? `<span style="color:#D97706; font-weight:600">${Math.round(cust.durationMinutes)} min</span>` : '';

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div style="padding: 12px 14px; min-width: 220px; font-family: 'Inter', sans-serif;">
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                                <div style="width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#F59E0B,#D97706);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:12px;flex-shrink:0;">${index + 1}</div>
                                <div>
                                    <div style="font-weight:700;color:#111827;font-size:13px;">${cust.businessName}</div>
                                    <div style="font-size:10px;color:#6B7280;margin-top:1px;">${typeLabel}</div>
                                </div>
                            </div>
                            <div style="border-top:1px solid #F3F4F6;padding-top:8px;font-size:11px;color:#6B7280;line-height:1.6;">
                                <div><i class="bi bi-geo-alt" style="color:#D97706;margin-right:4px;"></i>${cust.address || 'No address'}</div>
                                ${cust.phone ? `<div><i class="bi bi-telephone" style="color:#D97706;margin-right:4px;"></i>${cust.phone}</div>` : ''}
                                ${cust.contactPerson ? `<div><i class="bi bi-person" style="color:#D97706;margin-right:4px;"></i>${cust.contactPerson}</div>` : ''}
                                ${(distanceInfo || durationInfo) ? `<div style="margin-top:6px;padding-top:6px;border-top:1px solid #F3F4F6;display:flex;gap:12px;">${distanceInfo}${durationInfo}</div>` : ''}
                                <div style="margin-top: 10px; border-top: 1px solid #F3F4F6; padding-top: 8px;">
                                    <button onclick="openCustomerDetailsByIndex(${route.id}, ${index})" style="width: 100%; border: none; background: #D4A017; color: white; border-radius: 6px; padding: 6px 12px; font-size: 11px; font-weight: 600; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#B8860B'" onmouseout="this.style.background='#D4A017'">
                                        <i class="bi bi-info-circle" style="margin-right: 4px;"></i>View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    `,
                    maxWidth: 300,
                });

                marker.addListener('click', () => {
                    closeAllInfoWindows();
                    infoWindow.open(map, marker);
                });

                marker.addListener('mouseover', () => {
                    closeAllInfoWindows();
                    infoWindow.open(map, marker);
                });

                mapMarkers.push(marker);
                infoWindows.push(infoWindow);
            });

            // Draw route using Directions Service for real road paths
            if (customers.length >= 2) {
                drawDirectionsRoute(customers);
            }

            // Fit bounds
            map.fitBounds(bounds);
            const listener = google.maps.event.addListenerOnce(map, 'idle', function() {
                if (map.getZoom() > 16) map.setZoom(16);
            });
        }

        function drawDirectionsRoute(customers) {
            if (!directionsService || !directionsRenderer) return;

            const origin = { lat: customers[0].latitude, lng: customers[0].longitude };
            const destination = { lat: customers[customers.length - 1].latitude, lng: customers[customers.length - 1].longitude };

            // Waypoints (everything between first and last)
            const waypoints = customers.slice(1, -1).map(c => ({
                location: { lat: c.latitude, lng: c.longitude },
                stopover: true
            }));

            directionsService.route({
                origin: origin,
                destination: destination,
                waypoints: waypoints,
                travelMode: google.maps.TravelMode.DRIVING,
                optimizeWaypoints: false
            }, (result, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                } else {
                    // Fallback: draw simple polyline
                    console.warn('Directions API failed, drawing straight lines');
                    drawFallbackPolyline(customers);
                }
            });
        }

        function drawFallbackPolyline(customers) {
            const path = customers.map(c => ({ lat: c.latitude, lng: c.longitude }));
            mapPolyline = new google.maps.Polyline({
                path: path,
                geodesic: true,
                strokeColor: '#D4A017',
                strokeOpacity: 0.9,
                strokeWeight: 4,
                icons: [{
                    icon: { path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW, scale: 3, fillColor: '#B8860B', fillOpacity: 1, strokeWeight: 0 },
                    offset: '100%',
                    repeat: '80px'
                }]
            });
            mapPolyline.setMap(map);
        }

        function clearMapOverlays() {
            mapMarkers.forEach(m => m.setMap(null));
            mapMarkers = [];
            infoWindows.forEach(iw => iw.close());
            infoWindows = [];
            if (mapPolyline) { mapPolyline.setMap(null); mapPolyline = null; }
            if (directionsRenderer) { directionsRenderer.setDirections({ routes: [] }); }
        }

        function closeAllInfoWindows() {
            infoWindows.forEach(iw => iw.close());
        }

        // ==========================================
        // CUSTOMER LIST PANEL
        // ==========================================
        function renderCustomerList(route) {
            const panel = document.getElementById('customerListPanel');
            const content = document.getElementById('customerListContent');
            const countEl = document.getElementById('customerPanelCount');
            const customers = route.customers || [];

            countEl.innerText = `(${customers.length})`;
            content.innerHTML = '';

            if (customers.length === 0) {
                content.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                        <i class="bi bi-geo-alt text-2xl mb-2"></i>
                        <p class="text-xs">No customers assigned to this route</p>
                    </div>
                `;
            } else {
                customers.forEach((cust, index) => {
                    const isFirst = index === 0;
                    const isLast = index === customers.length - 1;
                    const dotColor = isFirst ? 'bg-emerald-500' : (isLast ? 'bg-red-500' : 'bg-amber-500');
                    const labelTag = isFirst ? '<span class="text-[9px] text-emerald-600 font-bold ml-1">START</span>' :
                                     (isLast ? '<span class="text-[9px] text-red-600 font-bold ml-1">END</span>' : '');

                    const item = document.createElement('div');
                    item.className = 'customer-item flex items-center gap-3 p-3 rounded-xl cursor-pointer border border-transparent hover:border-amber-100';
                    item.onclick = () => focusOnCustomer(index);
                    item.innerHTML = `
                        <div class="flex flex-col items-center gap-0.5">
                            <div class="w-7 h-7 rounded-lg ${dotColor} text-white flex items-center justify-center text-xs font-bold shadow-sm">${index + 1}</div>
                            ${!isLast ? '<div class="w-0.5 h-4 bg-gray-200 rounded-full"></div>' : ''}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5">
                                <span class="text-sm font-semibold text-gray-900 truncate">${cust.businessName}</span>
                                ${labelTag}
                            </div>
                            <div class="text-xs text-gray-500 truncate mt-0.5">${cust.address || 'No address'}</div>
                            <div class="flex items-center gap-3 mt-1">
                                ${cust.b2bType ? `<span class="text-[10px] px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded border border-blue-100 uppercase tracking-wide font-medium">${cust.b2bType.replace(/_/g, ' ')}</span>` : ''}
                                ${cust.phone ? `<span class="text-[10px] text-gray-400"><i class="bi bi-telephone mr-0.5"></i>${cust.phone}</span>` : ''}
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button onclick="event.stopPropagation(); openCustomerDetailsByIndex(${route.id}, ${index})" class="w-8 h-8 rounded-lg flex items-center justify-center text-amber-500 hover:text-amber-700 hover:bg-amber-50 transition-colors" title="View Details">
                                <i class="bi bi-info-circle text-lg"></i>
                            </button>
                            <i class="bi bi-chevron-right text-gray-300 text-xs"></i>
                        </div>
                    `;
                    content.appendChild(item);
                });
            }

            // Show panel
            panel.classList.remove('hidden');
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    panel.classList.add('visible');
                    customerPanelOpen = true;
                });
            });
        }

        function toggleCustomerPanel() {
            const panel = document.getElementById('customerListPanel');
            if (customerPanelOpen) {
                panel.classList.remove('visible');
                customerPanelOpen = false;
            } else {
                panel.classList.add('visible');
                customerPanelOpen = true;
            }
        }

        function focusOnCustomer(index) {
            if (!map || !mapMarkers[index]) return;

            const marker = mapMarkers[index];
            map.panTo(marker.getPosition());
            map.setZoom(16);

            closeAllInfoWindows();
            if (infoWindows[index]) {
                infoWindows[index].open(map, marker);
            }

            // Bounce animation
            marker.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => marker.setAnimation(null), 1400);
        }

        // ==========================================
        // KEYBOARD SHORTCUT
        // ==========================================
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (!customerModal.classList.contains('hidden')) {
                    closeCustomerModal();
                }
                const routeCustModal = document.getElementById('route-customers-modal');
                if (routeCustModal && !routeCustModal.classList.contains('hidden')) {
                    closeRouteCustomersModal();
                }
            }
        });
    </script>

    {{-- Google Maps API loaded from env --}}
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&libraries=places,geometry&loading=async">
    </script>
@endsection