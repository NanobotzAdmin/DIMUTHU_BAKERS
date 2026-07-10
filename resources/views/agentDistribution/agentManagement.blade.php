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
                            <i class="bi bi-truck text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Agent Management</h1>
                            <p class="text-gray-500 text-xs sm:text-sm">Manage field agents and distribution representatives
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <a href="{{ url('/agent-monthly-targets') }}" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-colors text-sm font-medium">
                        <i class="bi bi-graph-up-arrow mr-2"></i>
                        Monthly Targets
                    </a>
                    <button onclick="openAllAgentsMap()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-colors text-sm font-medium">
                        <i class="bi bi-geo-alt mr-2"></i>
                        View on Map
                    </button>
                    
                    <button onclick="openAgentModal()"
                        class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-colors text-sm font-medium">
                        <i class="bi bi-plus-lg mr-2"></i>
                        Add Agent
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6 max-w-[1800px] mx-auto">
            <!-- Filters and Search -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="agentSearch" placeholder="Search agents..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                    </div>

                    <select id="filterType"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="all">All Types</option>
                        <option value="1">Salaried</option>
                        <option value="2">Cash</option>
                        <option value="3">Credit</option>
                    </select>

                    <select id="filterStatus"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="all">All Status</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex justify-between items-center mb-4">
                <div class="text-gray-600 text-sm">
                    Showing <span id="agentCount">{{ count($agents) }}</span> agent(s)
                </div>
            </div>

            <!-- Agents Table -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3">Agent Code</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Contact</th>
                                <th scope="col" class="px-6 py-3">Outstanding</th>
                                <th scope="col" class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="agentsTableBody">
                            @forelse($agents as $agent)
                                <tr class="bg-white hover:bg-gray-50 agent-row"
                                    data-search="{{ strtolower($agent['agentName'] . ' ' . $agent['agentCode']) }}"
                                    data-type="{{ $agent['agentType'] }}" data-status="{{ $agent['employmentStatus'] }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $agent['agentCode'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $agent['agentName'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $typeLabels = [1 => 'Salaried', 2 => 'Cash', 3 => 'Credit'];
                                            $typeColors = [1 => 'bg-blue-100 text-blue-800', 2 => 'bg-purple-100 text-purple-800', 3 => 'bg-orange-100 text-orange-800'];
                                        @endphp
                                        <span
                                            class="{{ $typeColors[$agent['agentType']] ?? 'bg-gray-100 text-gray-800' }} text-xs px-2.5 py-0.5 rounded font-medium">
                                            {{ $typeLabels[$agent['agentType']] ?? $agent['agentType'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusLabels = [1 => 'Active', 2 => 'Inactive'];
                                            $statusColors = [1 => 'bg-green-100 text-green-800', 2 => 'bg-red-100 text-red-800'];
                                        @endphp
                                        <span
                                            class="{{ $statusColors[$agent['employmentStatus']] ?? 'bg-gray-100 text-gray-800' }} text-xs px-2.5 py-0.5 rounded font-medium">
                                            {{ $statusLabels[$agent['employmentStatus']] ?? $agent['employmentStatus'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center mb-1"><i class="bi bi-telephone mr-2 text-gray-400"></i>
                                            {{ $agent['contactPhone'] }}</div>
                                        <div class="flex items-center"><i class="bi bi-envelope mr-2 text-gray-400"></i>
                                            {{ $agent['contactEmail'] }}</div>
                                    </td>
                                    <td
                                        class="px-6 py-4 md:whitespace-nowrap font-medium {{ ($agent['outstandingBalance'] ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        Rs. {{ number_format($agent['outstandingBalance'] ?? 0, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="openAgentHistoryMap('{{ $agent['id'] }}', '{{ $agent['agentName'] }}')"
                                                class="text-gray-500 hover:text-indigo-600 p-1" title="Track on Map">
                                                <i class="bi bi-geo-alt"></i>
                                            </button>
                                            <button onclick="viewAgent('{{ json_encode($agent) }}')"
                                                class="text-gray-500 hover:text-blue-600 p-1" title="View Details"><i
                                                    class="bi bi-eye"></i></button>
                                            <button onclick="editAgent('{{ json_encode($agent) }}')"
                                                class="text-gray-500 hover:text-amber-600 p-1" title="Edit Agent"><i
                                                    class="bi bi-pencil"></i></button>
                                            <button
                                                onclick="toggleAgentStatus('{{ $agent['id'] }}', '{{ $agent['agentName'] }}', {{ $agent['employmentStatus'] }})"
                                                class="p-1 {{ $agent['employmentStatus'] == 1 ? 'text-gray-500 hover:text-red-600' : 'text-gray-500 hover:text-green-600' }}"
                                                title="{{ $agent['employmentStatus'] == 1 ? 'Deactivate' : 'Activate' }}">
                                                <i
                                                    class="bi {{ $agent['employmentStatus'] == 1 ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        No agents found. Create your first agent to get started.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- No Results Msg (JS) -->
                <div id="noAgentsFound" class="hidden px-6 py-12 text-center text-gray-500">
                    <i class="bi bi-search text-4xl mb-3 block text-gray-300"></i>
                    No matching agents found
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="agent-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div id="agent-backdrop"
            class="fixed inset-0 bg-gray-900/75 transition-opacity opacity-0 transition-opacity duration-300 ease-out"
            onclick="closeAgentModal()"></div>

        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div id="agent-panel"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out flex flex-col max-h-[90vh]">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Add New Agent</h3>
                    <p class="text-sm text-gray-500" id="modal-desc">Create a new field agent profile</p>
                </div>

                <form id="agent-form" onsubmit="event.preventDefault(); submitAgentForm();"
                    class="flex-1 overflow-y-auto p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Info -->
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-semibold text-gray-900 border-b pb-1 mb-2">Basic Info</h4>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Agent Name <span class="text-red-500">*</span></label>
                            <input type="text" id="agentName"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                required>
                        </div>
                        <div class="">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Agent Type <span class="text-red-500">*</span></label>
                            <select id="agentType" onchange="toggleAgentFields()"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                                {{-- <option value="1">Salaried</option> --}}
                                <option value="">Select Agent Type</option>
                                <option value="2">Cash</option>
                                <option value="3">Credit</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" id="contactPhone"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="contactEmail"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">NIC Number</label>
                            <input type="text" id="nicNumber"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>
                        <div class="hidden">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select id="employmentStatus"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                            <textarea id="address" rows="2"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"></textarea>
                        </div>
                        <div class="hidden">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Vehicle Category</label>
                            <input type="text" id="vehicleCategory"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                placeholder="e.g. Van, Lorry">
                        </div>

                        <!-- Financials -->
                        <div class="md:col-span-2 mt-2">
                            <h4 class="text-sm font-semibold text-gray-900 border-b pb-1 mb-2">Credit Information</h4>
                        </div>

                        <div id="field-creditLimit">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Credit Limit (Rs)</label>
                            <input type="text" id="creditLimit"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                placeholder="0.00">
                        </div>
                        <div id="field-creditDays">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Credit Period (Days)</label>
                            <input type="number" id="creditPeriodDays"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>

                        <!-- Banking Details -->
                        <div class="md:col-span-2 mt-2">
                            <div class="flex justify-between items-center border-b pb-1 mb-3">
                                <h4 class="text-sm font-semibold text-gray-900">Banking Details</h4>
                                <button type="button" onclick="addBankCard()"
                                    class="text-xs bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-full flex items-center">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Bank Cards Container -->
                        <div id="bankCardsContainer" class="md:col-span-2 space-y-3">
                            <!-- Bank cards will be added here dynamically -->
                        </div>

                    </div>
                </form>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200">
                    <button type="submit" onclick="submitAgentForm()" id="modalSubmitBtn"
                        class="inline-flex w-full justify-center rounded-md bg-[#D4A017] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#B8860B] sm:ml-3 sm:w-auto">Save
                        Agent</button>
                    <button type="button" onclick="closeAgentModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="view-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="view-title" role="dialog"
        aria-modal="true">
        <div id="view-backdrop"
            class="fixed inset-0 bg-gray-900/75 transition-opacity opacity-0 transition-opacity duration-300 ease-out"
            onclick="closeViewModal()"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div id="view-panel"
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out flex flex-col max-h-[90vh]">

                <!-- Modal Header -->
                <div
                    class="bg-white px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-full flex items-center justify-center text-amber-600">
                            <i class="bi bi-person-badge text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight" id="view-name">Agent Name</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded"
                                    id="view-code">CODE</span>
                                <div id="view-badges" class="flex gap-1">
                                    <!-- Badges will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-y-auto p-6 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Contact Info Section -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                <i class="bi bi-person-lines-fill"></i> Contact Information
                            </h4>
                            <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                                <div class="flex items-start gap-3">
                                    <i class="bi bi-telephone text-gray-400 mt-0.5"></i>
                                    <div>
                                        <span class="text-xs text-gray-500 block">Phone Number</span>
                                        <span id="view-phone" class="text-sm font-semibold text-gray-900"></span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="bi bi-envelope text-gray-400 mt-0.5"></i>
                                    <div>
                                        <span class="text-xs text-gray-500 block">Email Address</span>
                                        <span id="view-email" class="text-sm font-semibold text-gray-900"></span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="bi bi-card-text text-gray-400 mt-0.5"></i>
                                    <div>
                                        <span class="text-xs text-gray-500 block">NIC Number</span>
                                        <span id="view-nic" class="text-sm font-semibold text-gray-900"></span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <i class="bi bi-geo-alt text-gray-400 mt-0.5"></i>
                                    <div>
                                        <span class="text-xs text-gray-500 block">Residential Address</span>
                                        <span id="view-address"
                                            class="text-sm font-semibold text-gray-900 whitespace-pre-line"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Financial Terms Section -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                <i class="bi bi-currency-dollar"></i> Financial Terms
                            </h4>
                            <div class="bg-gray-50 rounded-xl p-4 grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-500 block">Credit Limit</span>
                                    <span id="view-credit-limit" class="text-sm font-bold text-gray-900"></span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Credit Period</span>
                                    <span id="view-credit-days" class="text-sm font-bold text-gray-900"></span>
                                </div>
                                <div
                                    class="col-span-2 bg-red-50 -mx-4 -mb-4 p-4 rounded-b-xl flex justify-between items-center">
                                    <span class="text-sm font-bold text-red-700">Outstanding Balance</span>
                                    <span id="view-outstanding" class="text-lg font-black text-red-600"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Targets Navigation Section -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                <i class="bi bi-calendar-check"></i> Monthly Targets
                            </h4>
                            <a id="view-manage-targets-link" href="{{ url('/agent-monthly-targets') }}" class="text-amber-600 hover:text-amber-700 text-xs font-bold flex items-center gap-1">
                                Manage Monthly Targets <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <p class="text-xs text-blue-700 leading-relaxed mb-0">
                                Sales, Category, and SKU targets are now managed on a per-month basis. Click "Manage Monthly Targets" to view or update targets for this agent.
                            </p>
                        </div>

                        <!-- Target Tables -->
                        <div id="view-targets-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Category Targets -->
                            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mt-0.5">Category Targets</span>
                                </div>
                                <div class="overflow-x-auto min-h-[100px]">
                                    <table class="w-full text-xs">
                                        <tbody id="view-cat-targets-body"></tbody>
                                    </table>
                                    <div id="view-cat-targets-empty" class="hidden p-6 text-center text-gray-400 italic">No targets defined</div>
                                </div>
                            </div>
                            <!-- SKU Targets -->
                            <div class="bg-white border border-gray-100 rounded-xl overflow-hidden">
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mt-0.5">SKU Targets</span>
                                </div>
                                <div class="overflow-x-auto min-h-[100px]">
                                    <table class="w-full text-xs">
                                        <tbody id="view-item-targets-body"></tbody>
                                    </table>
                                    <div id="view-item-targets-empty" class="hidden p-6 text-center text-gray-400 italic">No targets defined</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Banking Information Section -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-bank"></i> Banking Information
                        </h4>
                        <div id="view-bank-accounts-container" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Bank account cards will be inserted here -->
                        </div>
                        <div id="view-bank-empty"
                            class="hidden bg-gray-50 rounded-xl p-6 text-center text-gray-400 italic text-sm">
                            No bank account information available
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div
                    class="bg-gray-50 px-6 py-4 flex justify-end items-center gap-3 border-t border-gray-100 sticky bottom-0">
                    <button type="button" onclick="closeViewModal()"
                        class="px-5 py-2 rounded-lg bg-white border border-gray-300 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                        Close
                    </button>
                    <button type="button" id="view-edit-btn"
                        class="px-5 py-2 rounded-lg bg-amber-500 text-sm font-bold text-white hover:bg-amber-600 transition-colors shadow-sm flex items-center gap-2">
                        <i class="bi bi-pencil"></i> Edit Agent
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Tracking Modal -->
    <div id="map-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="map-backdrop"
            class="fixed inset-0 bg-gray-900/75 transition-opacity opacity-0 transition-opacity duration-300 ease-out"
            onclick="closeMapModal()"></div>

        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div id="map-panel"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out flex flex-col max-h-[90vh]">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold leading-6 text-gray-900" id="map-modal-title">Agent Locations</h3>
                        <p class="text-sm text-gray-500" id="map-modal-desc">Real-time tracking visualizer</p>
                    </div>
                    <!-- Date Selector -->
                    <div id="map-date-container" class="flex items-center gap-2">
                        <label for="map-history-date" class="text-xs font-medium text-gray-700">Select Date:</label>
                        <input type="date" id="map-history-date" 
                            class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:ring-amber-500 focus:border-amber-500"
                            value="{{ date('Y-m-d') }}" onchange="handleMapDateChange()">
                    </div>
                </div>

                <div class="p-4 flex-1">
                    <div id="agent-track-map" class="w-full h-[600px] rounded-lg bg-gray-100 border border-gray-200"></div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100 sticky bottom-0">
                    <div id="map-info-legend" class="text-xs text-gray-600 flex items-center gap-4">
                        <!-- Legend dynamically populated -->
                    </div>
                    <button type="button" onclick="closeMapModal()"
                        class="px-5 py-2 rounded-lg bg-white border border-gray-300 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Number Formatting Helpers
        function formatNumberWithCommas(value) {
            if (value === undefined || value === null || value === '') return '';
            let valStr = value.toString().replace(/[^\d.]/g, '');
            const parts = valStr.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join('.');
        }

        function stripCommas(value) {
            if (!value) return '';
            return value.toString().replace(/,/g, '');
        }

        // Add event listener for credit limit formatting
        document.addEventListener('DOMContentLoaded', function() {
            const creditLimitInput = document.getElementById('creditLimit');
            if (creditLimitInput) {
                creditLimitInput.addEventListener('input', function(e) {
                    let cursorPosition = this.selectionStart;
                    let oldLength = this.value.length;
                    
                    this.value = formatNumberWithCommas(this.value);
                    
                    let newLength = this.value.length;
                    this.setSelectionRange(cursorPosition + (newLength - oldLength), cursorPosition + (newLength - oldLength));
                });
            }
        });

        // Modal Logic
        const modal = document.getElementById('agent-modal');
        const backdrop = document.getElementById('agent-backdrop');
        const panel = document.getElementById('agent-panel');
        const form = document.getElementById('agent-form');
        const modalTitle = document.getElementById('modal-title');
        const modalBtn = document.getElementById('modalSubmitBtn');

        // Mapping functions for display
        function getAgentTypeText(type) {
            const types = {
                1: 'Salaried',
                2: 'Cash',
                3: 'Credit'
            };
            return types[type] || type;
        }

        function getStatusText(status) {
            const statuses = {
                1: 'Active',
                2: 'Inactive'
            };
            return statuses[status] || status;
        }

        function getStatusBadgeClass(status) {
            return status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        }

        // Bank Cards Management
        const soBanks = {!! json_encode($soBanks) !!};
        let bankCardCounter = 0;

        function addBankCard(bankData = null) {
            const cardId = `bankCard${bankCardCounter++}`;
            const container = document.getElementById('bankCardsContainer');
            const isFirstCard = container.children.length === 0;

            // First card should be primary by default
            const isPrimary = bankData?.is_primary || isFirstCard;

            const cardHtml = `
                    <div id="${cardId}" class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <h5 class="text-sm font-semibold text-gray-700">Bank Account ${bankCardCounter}</h5>
                            <button type="button" onclick="removeBankCard('${cardId}')" 
                                class="text-red-600 hover:text-red-800 text-sm">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bank Name *</label>
                                <select class="bank-id block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm" required>
                                    <option value="">Select Bank</option>
                                    ${soBanks.map(bank => `<option value="${bank.id}" ${bankData?.bank_id == bank.id ? 'selected' : ''}>${bank.bank_name} (${bank.bank_code})</option>`).join('')}
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Account Owner Name</label>
                                <input type="text" class="account-owner-name block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm" 
                                    value="${bankData?.account_owner_name || ''}">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Account Number *</label>
                                <input type="text" class="account-number block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm" 
                                    value="${bankData?.account_number || ''}" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Branch</label>
                                <input type="text" class="branch block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm" 
                                    value="${bankData?.branch || ''}">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center">
                                    <input type="checkbox" class="is-primary rounded border-gray-300 text-amber-600 focus:ring-amber-500" 
                                        ${isPrimary ? 'checked' : ''} 
                                        onchange="handlePrimaryChange(this, '${cardId}')">
                                    <span class="ml-2 text-xs text-gray-700">Primary Account</span>
                                </label>
                            </div>
                        </div>
                    </div>
                `;

            container.insertAdjacentHTML('beforeend', cardHtml);
            updateRemoveButtons();
        }

        function removeBankCard(cardId) {
            const card = document.getElementById(cardId);
            if (card) {
                const wasPrimary = card.querySelector('.is-primary').checked;
                card.remove();

                // If removed card was primary, make first remaining card primary
                if (wasPrimary) {
                    const firstCard = document.querySelector('#bankCardsContainer > div');
                    if (firstCard) {
                        firstCard.querySelector('.is-primary').checked = true;
                    }
                }

                updateRemoveButtons();
            }
        }

        function updateRemoveButtons() {
            const cards = document.querySelectorAll('#bankCardsContainer > div');
            cards.forEach((card, index) => {
                const removeBtn = card.querySelector('button[onclick^="removeBankCard"]');
                if (removeBtn) {
                    // Show remove button only if there's more than one card
                    removeBtn.style.display = cards.length > 1 ? 'block' : 'none';
                }
            });
        }

        function handlePrimaryChange(checkbox, currentCardId) {
            if (checkbox.checked) {
                // Uncheck all other primary checkboxes
                document.querySelectorAll('#bankCardsContainer .is-primary').forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            } else {
                // Don't allow unchecking if it's the only checked primary
                const primaryCheckboxes = Array.from(document.querySelectorAll('#bankCardsContainer .is-primary'));
                const checkedCount = primaryCheckboxes.filter(cb => cb.checked).length;

                if (checkedCount === 0) {
                    // Re-check this one - at least one must be primary
                    checkbox.checked = true;
                    Swal.fire({
                        icon: 'info',
                        title: 'Primary Required',
                        text: 'At least one bank account must be marked as primary',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            }
        }

        function collectBankData() {
            const cards = document.querySelectorAll('#bankCardsContainer > div');
            const bankAccounts = [];

            cards.forEach(card => {
                const bankId = card.querySelector('.bank-id').value;
                const accountOwnerName = card.querySelector('.account-owner-name').value.trim();
                const accountNumber = card.querySelector('.account-number').value.trim();
                const branch = card.querySelector('.branch').value.trim();
                const isPrimary = card.querySelector('.is-primary').checked;

                if (bankId && accountNumber) {
                    bankAccounts.push({
                        bank_id: bankId,
                        account_owner_name: accountOwnerName,
                        account_number: accountNumber,
                        branch: branch,
                        is_primary: isPrimary
                    });
                }
            });

            return bankAccounts;
        }

        function loadBankCards(bankAccountsData) {
            // Clear existing cards
            document.getElementById('bankCardsContainer').innerHTML = '';
            bankCardCounter = 0;

            if (bankAccountsData && bankAccountsData.length > 0) {
                bankAccountsData.forEach(bankData => {
                    addBankCard(bankData);
                });
            } else {
                // Add one empty card by default
                addBankCard();
            }
        }

        function openAgentModal(isEdit = false) {
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');

            if (!isEdit) {
                form.reset();
                document.getElementById('agent-form').dataset.agentId = '';
                loadBankCards(); // Load one empty card
                modalTitle.innerText = "Add New Agent";
                modalBtn.innerText = "Create Agent";
                toggleAgentFields();
            }
        }

        function closeAgentModal() {
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        function editAgent(agentJson) {
            const agent = JSON.parse(agentJson);

            // Store agent ID in form dataset for update operation  
            document.getElementById('agent-form').dataset.agentId = agent.id;

            document.getElementById('agentName').value = agent.agentName || '';
            document.getElementById('agentType').value = agent.agentType || '1';
            document.getElementById('contactPhone').value = agent.contactPhone || '';
            document.getElementById('contactEmail').value = agent.contactEmail || '';
            document.getElementById('nicNumber').value = agent.nicNumber || '';
            document.getElementById('employmentStatus').value = agent.employmentStatus || '1';
            document.getElementById('address').value = agent.address || '';
            document.getElementById('vehicleCategory').value = agent.vehicleCategory || '';
            document.getElementById('creditLimit').value = formatNumberWithCommas(agent.creditLimit) || '';
            document.getElementById('creditPeriodDays').value = agent.creditPeriodDays || '';

            // Load bank cards from agent data
            let bankAccountsData = [];
            if (agent.bank_accounts && Array.isArray(agent.bank_accounts)) {
                bankAccountsData = agent.bank_accounts;
            } else if (agent.bankName && agent.bankAccountNumber) {
                bankAccountsData = [{
                    bank_name: agent.bankName,
                    account_number: agent.bankAccountNumber,
                    branch: agent.bankBranch || '',
                    is_primary: true
                }];
            }
            loadBankCards(bankAccountsData);

            toggleAgentFields();

            modalTitle.innerText = "Edit Agent";
            modalBtn.innerText = "Update Agent";
            openAgentModal(true);
        }

        function toggleAgentFields() {
            const type = document.getElementById('agentType').value;
            const limitField = document.getElementById('field-creditLimit');
            const daysField = document.getElementById('field-creditDays');

            if (type === '3') { // Credit Based
                limitField.classList.remove('hidden');
                daysField.classList.remove('hidden');
            } else {
                limitField.classList.add('hidden');
                daysField.classList.add('hidden');
            }
        }

        function submitAgentForm() {
            const isEdit = modalBtn.innerText.includes('Update');
            const agentId = document.getElementById('agent-form').dataset.agentId || null;

            // Collect bank data from cards
            const bankAccounts = collectBankData();

            // Validate bank accounts
            if (bankAccounts.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Bank Account',
                    text: 'Please add at least one bank account',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Collect form data
            const formData = {
                agent_name: document.getElementById('agentName').value,
                agent_type: parseInt(document.getElementById('agentType').value),
                status: parseInt(document.getElementById('employmentStatus').value),
                phone: document.getElementById('contactPhone').value,
                email: document.getElementById('contactEmail').value,
                nic_number: document.getElementById('nicNumber').value,
                address: document.getElementById('address').value,
                vehicle_category: document.getElementById('vehicleCategory').value,
                credit_limit: stripCommas(document.getElementById('creditLimit').value) || null,
                credit_period_days: document.getElementById('creditPeriodDays').value || null,
                bank_accounts: bankAccounts
            };

            const url = isEdit ? `/api/agents/${agentId}/update` : '/api/agents/create';
            const method = isEdit ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: isEdit ? 'Updated!' : 'Created!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            closeAgentModal();
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to save agent: ' + error.message
                    });
                });
        }

        function toggleAgentStatus(agentId, name, currentStatus) {
            const isActivating = currentStatus != 1;
            const actionText = isActivating ? 'Activate' : 'Deactivate';
            const confirmButtonColor = isActivating ? '#10B981' : '#d33';

            Swal.fire({
                title: 'Are you sure?',
                text: `${actionText} ${name}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#3085d6',
                confirmButtonText: `Yes, ${actionText}`
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/agents/${agentId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(isActivating ? 'Activated!' : 'Deactivated!', data.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', `Failed to ${actionText.toLowerCase()} agent: ` + error.message, 'error');
                        });
                }
            });
        }

        // View Modal Logic
        const viewModal = document.getElementById('view-modal');
        const viewBackdrop = document.getElementById('view-backdrop');
        const viewPanel = document.getElementById('view-panel');

        function viewAgent(agentJson) {
            const agent = JSON.parse(agentJson);

            // Basic Info
            document.getElementById('view-name').innerText = agent.agentName;
            document.getElementById('view-code').innerText = agent.agentCode;
            document.getElementById('view-phone').innerText = agent.contactPhone || '-';
            document.getElementById('view-email').innerText = agent.contactEmail || '-';
            document.getElementById('view-nic').innerText = agent.nicNumber || '-';
            document.getElementById('view-address').innerText = agent.address || '-';

            // Financials
            const formatCurrency = (val) => val ? 'Rs. ' + parseFloat(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
 
            document.getElementById('view-credit-limit').innerText = formatCurrency(agent.creditLimit);
            document.getElementById('view-credit-days').innerText = agent.creditPeriodDays ? agent.creditPeriodDays + ' Days' : '-';
 
            document.getElementById('view-outstanding').innerText = formatCurrency(agent.outstandingBalance || 0);

            // Vehicle Category
            if (agent.vehicleCategory) {
                const badges = document.getElementById('view-badges');
                badges.innerHTML += `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-tight bg-gray-100 text-gray-700"><i class="bi bi-truck mr-1"></i>${agent.vehicleCategory}</span>`;
            }

            // Banking Information Section (Updated)
            document.getElementById('view-manage-targets-link').href = `{{ url('/agent-monthly-targets') }}?agent_id=${agent.id}`;

            // Category Targets
            const catBody = document.getElementById('view-cat-targets-body');
            const catEmpty = document.getElementById('view-cat-targets-empty');
            catBody.innerHTML = '';
            if (agent.category_targets && agent.category_targets.length > 0) {
                catEmpty.classList.add('hidden');
                agent.category_targets.forEach(t => {
                    catBody.innerHTML += `
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-4 py-2 font-medium text-gray-900">${t.category_name}</td>
                                <td class="px-4 py-2 text-right font-bold text-blue-600">${formatCurrency(t.target_amount)}</td>
                                <td class="px-4 py-2 text-right">
                                    <span class="bg-gray-100 px-2 py-0.5 rounded text-[10px] font-bold text-gray-600">${t.target_percentage || 0}%</span>
                                </td>
                            </tr>
                        `;
                });
            } else {
                catEmpty.classList.remove('hidden');
            }

            // SKU Targets
            const itemBody = document.getElementById('view-item-targets-body');
            const itemEmpty = document.getElementById('view-item-targets-empty');
            itemBody.innerHTML = '';
            if (agent.item_targets && agent.item_targets.length > 0) {
                itemEmpty.classList.add('hidden');
                agent.item_targets.forEach(t => {
                    itemBody.innerHTML += `
                            <tr class="hover:bg-green-50/30 transition-colors">
                                <td class="px-4 py-2 font-medium text-gray-900">${t.product_name}</td>
                                <td class="px-4 py-2 text-right font-bold text-green-600">${formatCurrency(t.target_amount)}</td>
                                <td class="px-4 py-2 text-right">
                                    <span class="bg-gray-100 px-2 py-0.5 rounded text-[10px] font-bold text-gray-600">${t.target_percentage || 0}%</span>
                                </td>
                            </tr>
                        `;
                });
            } else {
                itemEmpty.classList.remove('hidden');
            }

            // Bank Accounts
            const bankContainer = document.getElementById('view-bank-accounts-container');
            const bankEmpty = document.getElementById('view-bank-empty');
            bankContainer.innerHTML = '';
            if (agent.bank_accounts && agent.bank_accounts.length > 0) {
                bankEmpty.classList.add('hidden');
                agent.bank_accounts.forEach(acc => {
                    bankContainer.innerHTML += `
                            <div class="border ${acc.is_primary ? 'border-amber-200 bg-amber-50/30' : 'border-gray-200 bg-white'} rounded-xl p-4 relative">
                                ${acc.is_primary ? '<span class="absolute top-2 right-2 bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold uppercase">Primary</span>' : ''}
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-gray-100 shadow-sm">
                                        <i class="bi bi-bank2 text-gray-400"></i>
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">${acc.bank_name}</span>
                                        <span class="text-[11px] font-semibold text-gray-600 block mb-1">${acc.account_owner_name || 'Account Owner N/A'}</span>
                                        <span class="text-xs text-blue-600 font-medium block">${acc.account_number}</span>
                                        <span class="text-[10px] text-gray-500 uppercase">${acc.branch || 'Main Branch'}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                });
            } else {
                bankEmpty.classList.remove('hidden');
            }

            // Badges
            const badgeContainer = document.getElementById('view-badges');
            const typeColors = { 1: 'bg-blue-100 text-blue-800', 2: 'bg-purple-100 text-purple-800', 3: 'bg-orange-100 text-orange-800' };
            const statusColors = { 1: 'bg-green-100 text-green-800', 2: 'bg-red-100 text-red-800' };

            badgeContainer.innerHTML = `
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-tight ${typeColors[agent.agentType] || 'bg-gray-100 text-gray-800'}">${getAgentTypeText(agent.agentType)}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-tight ${statusColors[agent.employmentStatus] || 'bg-gray-100 text-gray-800'}">${getStatusText(agent.employmentStatus)}</span>
                `;

            // Setup Edit Button
            document.getElementById('view-edit-btn').onclick = () => {
                closeViewModal();
                editAgent(agentJson);
            };

            viewModal.classList.remove('hidden');
            setTimeout(() => {
                viewBackdrop.classList.remove('opacity-0');
                viewBackdrop.classList.add('opacity-100');
                viewPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                viewPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 10);
        }

        function closeViewModal() {
            viewBackdrop.classList.remove('opacity-100');
            viewBackdrop.classList.add('opacity-0');
            viewPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            viewPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => viewModal.classList.add('hidden'), 300);
        }

        // Search & Filter Logic
        function filterAgents() {
            const search = document.getElementById('agentSearch').value.toLowerCase();
            const type = document.getElementById('filterType').value;
            const status = document.getElementById('filterStatus').value;
            const rows = document.querySelectorAll('.agent-row');

            let visibleCount = 0;

            rows.forEach(row => {
                const rowSearch = row.dataset.search;
                const rowType = row.dataset.type;
                const rowStatus = row.dataset.status;

                const matchSearch = rowSearch.includes(search);
                const matchType = type === 'all' || rowType === type;
                const matchStatus = status === 'all' || rowStatus === status;

                if (matchSearch && matchType && matchStatus) {
                    row.style.display = 'table-row';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('agentCount').innerText = visibleCount;
            const noRes = document.getElementById('noAgentsFound');
            if (visibleCount === 0 && rows.length > 0) {
                noRes.classList.remove('hidden');
            } else {
                noRes.classList.add('hidden');
            }
        }

        document.getElementById('agentSearch').addEventListener('keyup', filterAgents);
        document.getElementById('filterType').addEventListener('change', filterAgents);
        document.getElementById('filterStatus').addEventListener('change', filterAgents);

        // ==========================================
        // MAP TRACKING LOGIC
        // ==========================================
        const mapModal = document.getElementById('map-modal');
        const mapBackdrop = document.getElementById('map-backdrop');
        const mapPanel = document.getElementById('map-panel');
        const mapTitle = document.getElementById('map-modal-title');
        const mapDesc = document.getElementById('map-modal-desc');
        const mapDateContainer = document.getElementById('map-date-container');
        const mapHistoryDate = document.getElementById('map-history-date');
        const mapLegend = document.getElementById('map-info-legend');

        let trackMapObj = null;
        let trackMarkers = [];
        let trackPath = null;
        let activeAgentId = null;
        let mapMode = 'all'; // 'all' or 'single'

        let directionsService = null;
        let directionsRenderer = null;

        function initTrackMap() {
            if (trackMapObj) return;
            const mapDiv = document.getElementById('agent-track-map');
            const defaultCenter = { lat: 6.9271, lng: 79.8612 }; // Default Colombo center
            trackMapObj = new google.maps.Map(mapDiv, {
                center: defaultCenter,
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: trackMapObj,
                suppressMarkers: true,
                polylineOptions: {
                    strokeColor: '#2563EB',
                    strokeWeight: 4
                }
            });
        }

        function clearMap() {
            trackMarkers.forEach(marker => marker.setMap(null));
            trackMarkers = [];
            if (trackPath) {
                trackPath.setMap(null);
                trackPath = null;
            }
            if (directionsRenderer) {
                directionsRenderer.setDirections({routes: []});
            }
        }

        function openMapModal() {
            mapModal.classList.remove('hidden');
            setTimeout(() => {
                mapBackdrop.classList.remove('opacity-0');
                mapBackdrop.classList.add('opacity-100');
                mapPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                mapPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
                initTrackMap();
            }, 10);
        }

        function closeMapModal() {
            mapBackdrop.classList.remove('opacity-100');
            mapBackdrop.classList.add('opacity-0');
            mapPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            mapPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => {
                mapModal.classList.add('hidden');
                clearMap();
            }, 300);
        }

        function handleMapDateChange() {
            if (mapMode === 'all') {
                loadAllAgentsLocations();
            } else {
                loadAgentHistoryFromDate();
            }
        }

        function openAllAgentsMap() {
            mapMode = 'all';
            mapHistoryDate.value = new Date().toISOString().split('T')[0];
            openMapModal();
            loadAllAgentsLocations();
        }

        function loadAllAgentsLocations() {
            const dateVal = mapHistoryDate.value || new Date().toISOString().split('T')[0];
            mapTitle.innerText = "All Agents Locations";
            mapDesc.innerText = `Agent coordinates for date: ${dateVal}`;
            mapLegend.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 flex items-center justify-center text-[10px] text-white font-bold">A</span>
                    <span>Active Agents</span>
                </div>
            `;
            
            // Fetch locations
            fetch(`/api/agents/locations/all?date=${dateVal}`)
                .then(res => res.json())
                .then(res => {
                    clearMap();
                    if (!res.status || res.data.length === 0) {
                        mapLegend.innerHTML = `<span class="text-red-500 font-semibold">No agent locations for this date</span>`;
                        Swal.fire('No Data', 'No agent tracking data available for the selected date.', 'info');
                        return;
                    }

                    const bounds = new google.maps.LatLngBounds();
                    res.data.forEach(loc => {
                        const pos = { lat: loc.lat, lng: loc.long };
                        bounds.extend(pos);

                        const marker = new google.maps.Marker({
                            position: pos,
                            map: trackMapObj,
                            title: `${loc.agent_name} (${loc.agent_code})`,
                            icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
                        });

                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div class="p-2">
                                    <h4 class="font-bold text-sm text-gray-900">${loc.agent_name}</h4>
                                    <p class="text-xs text-gray-500 mb-1">Code: ${loc.agent_code}</p>
                                    <p class="text-xs text-gray-600 mb-1">Phone: ${loc.phone || '-'}</p>
                                    <p class="text-[10px] text-gray-400">Last updated: ${loc.date}</p>
                                </div>
                            `
                        });

                        marker.addListener('click', () => {
                            infoWindow.open(trackMapObj, marker);
                        });

                        trackMarkers.push(marker);
                    });

                    trackMapObj.fitBounds(bounds);
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Failed to fetch agent locations.', 'error');
                });
        }

        function openAgentHistoryMap(agentId, agentName) {
            mapMode = 'single';
            activeAgentId = agentId;
            mapTitle.innerText = `${agentName} Location History`;
            mapDesc.innerText = `Track coordinates history`;
            mapHistoryDate.value = new Date().toISOString().split('T')[0];
            
            openMapModal();
            loadAgentHistoryFromDate();
        }

        function loadAgentHistoryFromDate() {
            if (!activeAgentId) return;
            const dateVal = mapHistoryDate.value;
            if (!dateVal) return;

            fetch(`/api/agents/${activeAgentId}/locations/history?date=${dateVal}`)
                .then(res => res.json())
                .then(res => {
                    clearMap();
                    if (!res.status || res.data.history.length === 0) {
                        mapLegend.innerHTML = `<span class="text-red-500 font-semibold">No history data for this date</span>`;
                        Swal.fire('No Data', 'No location history found for the selected date.', 'info');
                        return;
                    }

                    const history = res.data.history;
                    mapLegend.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-full bg-blue-600 flex items-center justify-center text-[10px] text-white font-bold">S</span>
                            <span>Start (${history[0].date})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-full bg-red-600 flex items-center justify-center text-[10px] text-white font-bold">E</span>
                            <span>End (${history[history.length - 1].date})</span>
                        </div>
                        <div class="text-[11px] text-gray-500 font-medium">Points: ${history.length}</div>
                    `;

                    const pathCoords = [];
                    const bounds = new google.maps.LatLngBounds();

                    history.forEach((point, index) => {
                        const pos = { lat: point.lat, lng: point.long };
                        pathCoords.push(pos);
                        bounds.extend(pos);

                        const isStart = index === 0;
                        const isEnd = index === history.length - 1;

                        if (isStart || isEnd) {
                            const marker = new google.maps.Marker({
                                position: pos,
                                map: trackMapObj,
                                label: isStart ? 'S' : 'E',
                                title: `${isStart ? 'Start' : 'End'} point at ${point.date}`,
                                icon: isStart ? 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png' : 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
                            });

                            const infoWindow = new google.maps.InfoWindow({
                                content: `<div class="p-1 text-xs font-semibold">${isStart ? 'Start' : 'End'}: ${point.date}</div>`
                            });

                            marker.addListener('click', () => {
                                infoWindow.open(trackMapObj, marker);
                            });

                            trackMarkers.push(marker);
                        } else {
                            // Intermediate point - draw a small yellow circle
                            const marker = new google.maps.Marker({
                                position: pos,
                                map: trackMapObj,
                                title: `Time: ${point.date}`,
                                icon: {
                                    path: google.maps.SymbolPath.CIRCLE,
                                    scale: 5,
                                    fillColor: '#F59E0B',
                                    fillOpacity: 1.0,
                                    strokeColor: '#D97706',
                                    strokeWeight: 1.5
                                }
                            });

                            const infoWindow = new google.maps.InfoWindow({
                                content: `<div class="p-1 text-xs font-semibold">Time: ${point.date}</div>`
                            });

                            marker.addListener('click', () => {
                                infoWindow.open(trackMapObj, marker);
                            });

                            trackMarkers.push(marker);
                        }
                    });

                    // Draw route: use DirectionsService if 2 or more points
                    if (history.length >= 2) {
                        const chunkSize = 20; // 1 origin, 18 waypoints, 1 destination
                        let chunkIndex = 0;

                        function requestNextChunk() {
                            if (chunkIndex >= history.length - 1) return;

                            const startIdx = chunkIndex;
                            const endIdx = Math.min(chunkIndex + chunkSize, history.length - 1);
                            
                            const chunk = history.slice(startIdx, endIdx + 1);
                            const chunkWaypoints = [];
                            for (let i = 1; i < chunk.length - 1; i++) {
                                chunkWaypoints.push({
                                    location: new google.maps.LatLng(chunk[i].lat, chunk[i].long),
                                    stopover: true
                                });
                            }

                            const request = {
                                origin: new google.maps.LatLng(chunk[0].lat, chunk[0].long),
                                destination: new google.maps.LatLng(chunk[chunk.length - 1].lat, chunk[chunk.length - 1].long),
                                waypoints: chunkWaypoints,
                                travelMode: google.maps.TravelMode.DRIVING
                            };

                            directionsService.route(request, function(result, status) {
                                if (status == google.maps.DirectionsStatus.OK) {
                                    const renderer = new google.maps.DirectionsRenderer({
                                        map: trackMapObj,
                                        suppressMarkers: true,
                                        preserveViewport: true,
                                        polylineOptions: {
                                            strokeColor: '#2563EB',
                                            strokeWeight: 4
                                        }
                                    });
                                    renderer.setDirections(result);
                                    trackMarkers.push(renderer);
                                } else {
                                    console.warn("Directions chunk failed due to " + status + ". Falling back to polyline.");
                                    const chunkCoords = chunk.map(p => ({ lat: p.lat, lng: p.long }));
                                    drawFallbackPolyline(chunkCoords);
                                }

                                // Move to next chunk
                                chunkIndex = endIdx;
                                requestNextChunk();
                            });
                        }

                        requestNextChunk();
                    } else {
                        drawFallbackPolyline(pathCoords);
                    }

                    function drawFallbackPolyline(coords) {
                        const lineSymbol = {
                            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                            scale: 2.5,
                            strokeColor: '#1E3A8A'
                        };

                        const polyline = new google.maps.Polyline({
                            path: coords,
                            geodesic: true,
                            strokeColor: '#2563EB',
                            strokeOpacity: 1.0,
                            strokeWeight: 4,
                            icons: [{
                                icon: lineSymbol,
                                offset: '100%',
                                repeat: '80px'
                            }],
                            map: trackMapObj
                        });
                        trackMarkers.push(polyline);
                    }

                    trackMapObj.fitBounds(bounds);
                    // Ensure appropriate zoom level if single coordinate
                    if (history.length === 1) {
                        trackMapObj.setZoom(16);
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Failed to fetch location history.', 'error');
                });
        }
    </script>

    {{-- Google Maps API loaded from config passed --}}
    @if(isset($googleMapsKey))
        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&libraries=places,geometry&loading=async"></script>
    @endif
@endsection