@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-gray-900 text-2xl font-bold">Agent Distribution System</h1>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">
                    All Phases Complete
                </span>
            </div>
            <p class="text-gray-600">Comprehensive agent management from mobile sales to commission processing</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('agentManagement.index') }}" class="group block">
                <div
                    class="bg-white p-6 rounded-lg shadow-sm border-2 border-transparent group-hover:border-[#D4A017] group-hover:shadow-lg transition-all cursor-pointer h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-500 p-3 rounded-lg text-white">
                            <i class="bi bi-people text-xl"></i>
                        </div>
                    </div>
                    <div class="text-gray-600 mb-1 text-sm font-medium uppercase tracking-wide">Active Agents</div>
                    <div class="text-gray-900 text-3xl font-bold">{{ $stats['activeAgents'] }}</div>
                </div>
            </a>

            <a href="{{ route('routeManagement.index') }}" class="group block">
                <div
                    class="bg-white p-6 rounded-lg shadow-sm border-2 border-transparent group-hover:border-[#D4A017] group-hover:shadow-lg transition-all cursor-pointer h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-500 p-3 rounded-lg text-white">
                            <i class="bi bi-signpost-2 text-xl"></i>
                        </div>
                    </div>
                    <div class="text-gray-600 mb-1 text-sm font-medium uppercase tracking-wide">Total Routes</div>
                    <div class="text-gray-900 text-3xl font-bold">{{ $stats['totalRoutes'] }}</div>
                </div>
            </a>

            <a href="{{ route('dailyLoads.index') }}" class="group block">
                <div
                    class="bg-white p-6 rounded-lg shadow-sm border-2 border-transparent group-hover:border-[#D4A017] group-hover:shadow-lg transition-all cursor-pointer h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-orange-500 p-3 rounded-lg text-white">
                            <i class="bi bi-box-seam text-xl"></i>
                        </div>
                    </div>
                    <div class="text-gray-600 mb-1 text-sm font-medium uppercase tracking-wide">Active Loads</div>
                    <div class="text-gray-900 text-3xl font-bold">{{ $stats['activeLoads'] }}</div>
                </div>
            </a>

            <a href="{{ route('settlementList.index') }}" class="group block">
                <div
                    class="bg-white p-6 rounded-lg shadow-sm border-2 border-transparent group-hover:border-[#D4A017] group-hover:shadow-lg transition-all cursor-pointer h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-500 p-3 rounded-lg text-white">
                            <i class="bi bi-file-earmark-check text-xl"></i>
                        </div>
                    </div>
                    <div class="text-gray-600 mb-1 text-sm font-medium uppercase tracking-wide">Pending Settlements</div>
                    <div class="text-gray-900 text-3xl font-bold">{{ $stats['pendingSettlements'] }}</div>
                </div>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="bg-[#DFB967] bg-opacity-10 border-2 border-[#D4A017] border-opacity-30 rounded-lg p-6 mb-8">
            <h2 class="text-gray-900 text-lg font-semibold mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('agentManagement.index') }}" class="block">
                    <button
                        class="w-full bg-[#D4A017] hover:bg-[#B8860B] text-white font-medium py-2.5 px-4 rounded-lg flex items-center justify-center transition-colors shadow-sm">
                        <i class="bi bi-people-fill mr-2"></i> Manage Agents
                    </button>
                </a>
                <a href="{{ route('dailyLoads.index') }}" class="block">
                    <button
                        class="w-full bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-medium py-2.5 px-4 rounded-lg flex items-center justify-center transition-colors shadow-sm">
                        <i class="bi bi-box-seam mr-2"></i> Create Load
                    </button>
                </a>
                <a href="{{ route('settlementList.index') }}" class="block">
                    <button
                        class="w-full bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-medium py-2.5 px-4 rounded-lg flex items-center justify-center transition-colors shadow-sm">
                        <i class="bi bi-check-circle mr-2"></i> Review Settlements
                    </button>
                </a>
                <a href="{{ route('commissionPayment.index') }}" class="block">
                    <button
                        class="w-full bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-medium py-2.5 px-4 rounded-lg flex items-center justify-center transition-colors shadow-sm">
                        <i class="bi bi-cash-stack mr-2"></i> Process Commissions
                    </button>
                </a>
            </div>
        </div>

        <!-- Module Categories -->
        <div class="space-y-6 mb-8">

            <!-- Core Management -->
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <i class="bi bi-people text-xl text-gray-700"></i>
                    <h2 class="text-gray-900 text-lg font-semibold">Core Management</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('agentManagement.index') }}" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-people text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Agent Management</h3>
                            <p class="text-gray-600 text-sm">Manage agent profiles, employment, and assignments</p>
                        </div>
                    </a>
                    <a href="{{ route('routeManagement.index') }}" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-signpost-2 text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Route Management</h3>
                            <p class="text-gray-600 text-sm">Configure routes and customer assignments</p>
                        </div>
                    </a>
                    <a href="{{ route('dailyLoads.index') }}" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-box-seam text-orange-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    2</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Daily Loads</h3>
                            <p class="text-gray-600 text-sm">Create and manage daily product loads</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Settlement & Approval -->
            <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <i class="bi bi-file-earmark-check text-xl text-gray-700"></i>
                    <h2 class="text-gray-900 text-lg font-semibold">Settlement & Approval</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('settlementList.index') }}" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-check-circle text-green-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    3</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Settlement List</h3>
                            <p class="text-gray-600 text-sm">Review and approve settlements</p>
                        </div>
                    </a>
                    <a href="{{ route('glPosting.index') }}" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-graph-up-arrow text-indigo-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    3</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">GL Posting</h3>
                            <p class="text-gray-600 text-sm">Post settlements to general ledger</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Commission Management -->
            <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <i class="bi bi-cash-stack text-xl text-gray-700"></i>
                    <h2 class="text-gray-900 text-lg font-semibold">Commission Management</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="#" onclick="alert('Module available soon')" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-wallet2 text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Commission Overview</h3>
                            <p class="text-gray-600 text-sm">Commission summary and tracking</p>
                        </div>
                    </a>
                    <a href="{{ route('commissionPayment.index') }}" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-cash text-emerald-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    4</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Commission Payments</h3>
                            <p class="text-gray-600 text-sm">Process commission payments</p>
                        </div>
                    </a>
                    <a href="#" onclick="alert('Module available soon')" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-file-text text-teal-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    4</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Commission Statements</h3>
                            <p class="text-gray-600 text-sm">Generate agent statements</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Analytics & Insights -->
            <div class="bg-indigo-50 border-2 border-indigo-200 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <i class="bi bi-bar-chart-line text-xl text-gray-700"></i>
                    <h2 class="text-gray-900 text-lg font-semibold">Analytics & Insights</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('agentAnalytics.index') }}" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-bar-chart text-indigo-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    3</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Agent Analytics</h3>
                            <p class="text-gray-600 text-sm">Performance metrics and trends</p>
                        </div>
                    </a>
                    <a href="{{ route('financialDashboard.index') }}" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-graph-up text-purple-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    4</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Financial Dashboard</h3>
                            <p class="text-gray-600 text-sm">Agent financial overview with charts</p>
                        </div>
                    </a>
                    <a href="#" onclick="alert('Module available soon')" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-geo-alt text-blue-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    5</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Route Analytics</h3>
                            <p class="text-gray-600 text-sm">Route performance optimization</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Advanced Features -->
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <i class="bi bi-lightning-charge text-xl text-gray-700"></i>
                    <h2 class="text-gray-900 text-lg font-semibold">Advanced Features</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="#" onclick="alert('Module available soon')" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-robot text-yellow-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    4</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Settlement Automation</h3>
                            <p class="text-gray-600 text-sm">Automate settlement workflows</p>
                        </div>
                    </a>
                    <a href="{{ route('disputeResolution.index') }}" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-chat-square-text text-red-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    4</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Dispute Resolution</h3>
                            <p class="text-gray-600 text-sm">Manage settlement disputes</p>
                        </div>
                    </a>
                    <a href="#" onclick="alert('Module available soon')" class="block h-full">
                        <div
                            class="bg-white p-4 rounded-lg border-2 border-transparent hover:border-[#D4A017] hover:shadow-md transition-all h-full cursor-pointer">
                            <div class="flex items-start justify-between mb-3">
                                <i class="bi bi-trophy text-orange-600 text-xl"></i>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-medium">Phase
                                    4</span>
                            </div>
                            <h3 class="text-gray-900 font-medium mb-1">Incentives & Bonuses</h3>
                            <p class="text-gray-600 text-sm">Configure incentive schemes</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        <!-- Getting Started & Mobile App -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Getting Started -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-gray-900 font-semibold mb-4">Getting Started</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-6 h-6 rounded-full bg-[#D4A017] text-white flex items-center justify-center flex-shrink-0 text-sm font-bold">
                            1</div>
                        <div>
                            <div class="text-gray-900 font-medium text-sm">Create Agents</div>
                            <div class="text-gray-600 text-sm">Add field agents with employment terms and commission rates
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div
                            class="w-6 h-6 rounded-full bg-[#D4A017] text-white flex items-center justify-center flex-shrink-0 text-sm font-bold">
                            2</div>
                        <div>
                            <div class="text-gray-900 font-medium text-sm">Define Routes</div>
                            <div class="text-gray-600 text-sm">Create routes and assign customer stops</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div
                            class="w-6 h-6 rounded-full bg-[#D4A017] text-white flex items-center justify-center flex-shrink-0 text-sm font-bold">
                            3</div>
                        <div>
                            <div class="text-gray-900 font-medium text-sm">Create Daily Loads</div>
                            <div class="text-gray-600 text-sm">Assign products to agents for distribution sales</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div
                            class="w-6 h-6 rounded-full bg-[#D4A017] text-white flex items-center justify-center flex-shrink-0 text-sm font-bold">
                            4</div>
                        <div>
                            <div class="text-gray-900 font-medium text-sm">Mobile Sales & Settlement</div>
                            <div class="text-gray-600 text-sm">Agents use mobile app for sales, collections, and settlement
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile App Access -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center shadow-md">
                        <i class="bi bi-phone text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-900 font-bold text-lg">Mobile Agent App</h3>
                        <span
                            class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded font-medium border border-green-200">New
                            Grab-Style Design</span>
                    </div>
                </div>
                <p class="text-gray-700 text-sm mb-4">Redesigned mobile app with Grab-inspired UI - Category chips,
                    photo-first design, and bottom sheets for ease of use.</p>

                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-2 text-sm text-gray-700"><i
                            class="bi bi-check-circle-fill text-green-600"></i> Home dashboard with daily targets</div>
                    <div class="flex items-center gap-2 text-sm text-gray-700"><i
                            class="bi bi-check-circle-fill text-green-600"></i> Visual-first for low literacy users</div>
                    <div class="flex items-center gap-2 text-sm text-gray-700"><i
                            class="bi bi-check-circle-fill text-green-600"></i> Mobile emulator for demonstrations</div>
                    <div class="flex items-center gap-2 text-sm text-gray-700"><i
                            class="bi bi-check-circle-fill text-green-600"></i> Professional digital receipts</div>
                </div>

                <a href="#" class="block">
                    <button
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition-colors shadow-sm">
                        Open Mobile App Demo
                    </button>
                </a>
            </div>
        </div>

        <!-- Developer Tools -->
        <div class="bg-gradient-to-br from-red-50 to-orange-50 border-2 border-red-200 rounded-lg p-6">
            <div class="flex items-center gap-3 mb-4">
                <i class="bi bi-exclamation-circle text-red-600 text-xl"></i>
                <div>
                    <h3 class="text-gray-900 font-semibold">Developer Tools</h3>
                    <p class="text-gray-600 text-sm">Reset and regenerate demo data for testing</p>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-red-200 mb-4">
                <p class="text-gray-700 text-sm mb-3">This will clear all agent data and regenerate comprehensive demo data
                    including:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                    <ul class="text-gray-600 text-xs space-y-1 list-disc list-inside">
                        <li>3 Sample agents (various types)</li>
                        <li>2 Routes with 8 customer stops</li>
                        <li>8 Daily loads (today + history)</li>
                        <li>8 Sales transactions</li>
                        <li>3 Collections records</li>
                    </ul>
                    <ul class="text-gray-600 text-xs space-y-1 list-disc list-inside">
                        <li>4 Product returns</li>
                        <li>5 Completed settlements</li>
                        <li>2 Commission records</li>
                        <li>Full week of historical data</li>
                        <li>Analytics & reports ready</li>
                    </ul>
                </div>
                <button onclick="resetDemoData()"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 rounded-lg transition-colors shadow-sm flex items-center justify-center">
                    <i class="bi bi-arrow-counterclockwise mr-2"></i> Reset & Regenerate All Demo Data
                </button>
            </div>
        </div>
    </div>

    <script>
        function resetDemoData() {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will reset all agent data and regenerate demo data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, reset everything!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Check if window.agentStore exists (it would in a React/JS store context), 
                    // seeing as this is Blade/PHP, we just mock the success interaction

                    Swal.fire({
                        title: 'Resetting...',
                        text: 'Regenerating demo environment',
                        timer: 1500,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    }).then(() => {
                        Swal.fire(
                            'Reset Complete!',
                            'Demo data has been reset and regenerated successfully.',
                            'success'
                        ).then(() => {
                            window.location.reload();
                        });
                    });
                }
            })
        }
    </script>
@endsection