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
                            <p class="text-gray-500 text-xs sm:text-sm">Manage field agents and distribution representatives</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
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
                    <option value="2">Commission Only</option>
                    <option value="3">Credit Based</option>
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
                            <th scope="col" class="px-6 py-3">Commission</th>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $agent['agentName'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $typeLabels = [1 => 'Salaried', 2 => 'Commission Only', 3 => 'Credit Based'];
                                        $typeColors = [1 => 'bg-blue-100 text-blue-800', 2 => 'bg-purple-100 text-purple-800', 3 => 'bg-orange-100 text-orange-800'];
                                    @endphp
                                    <span class="{{ $typeColors[$agent['agentType']] ?? 'bg-gray-100 text-gray-800' }} text-xs px-2.5 py-0.5 rounded font-medium">
                                        {{ $typeLabels[$agent['agentType']] ?? $agent['agentType'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusLabels = [1 => 'Active', 2 => 'Inactive'];
                                        $statusColors = [1 => 'bg-green-100 text-green-800', 2 => 'bg-red-100 text-red-800'];
                                    @endphp
                                    <span class="{{ $statusColors[$agent['employmentStatus']] ?? 'bg-gray-100 text-gray-800' }} text-xs px-2.5 py-0.5 rounded font-medium">
                                        {{ $statusLabels[$agent['employmentStatus']] ?? $agent['employmentStatus'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center mb-1"><i class="bi bi-telephone mr-2 text-gray-400"></i>
                                        {{ $agent['contactPhone'] }}</div>
                                    <div class="flex items-center"><i class="bi bi-envelope mr-2 text-gray-400"></i>
                                        {{ $agent['contactEmail'] }}</div>
                                </td>
                                <td class="px-6 py-4 md:whitespace-nowrap">{{ $agent['commissionRate'] }}%</td>
                                <td
                                    class="px-6 py-4 md:whitespace-nowrap font-medium {{ $agent['outstandingBalance'] > 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    Rs. {{ number_format($agent['outstandingBalance'], 2) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="viewAgent('{{ json_encode($agent) }}')"
                                            class="text-gray-500 hover:text-blue-600 p-1"><i class="bi bi-eye"></i></button>
                                        <button onclick="editAgent('{{ json_encode($agent) }}')"
                                            class="text-gray-500 hover:text-amber-600 p-1"><i class="bi bi-pencil"></i></button>
                                        <button onclick="deleteAgent('{{ $agent['id'] }}', '{{ $agent['agentName'] }}')"
                                            class="text-gray-500 hover:text-red-600 p-1"><i class="bi bi-trash"></i></button>
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
                            <label class="block text-xs font-medium text-gray-700 mb-1">Agent Name *</label>
                            <input type="text" id="agentName"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                required>
                        </div>
                        <div class="hidden">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Agent Type *</label>
                            <select id="agentType" onchange="toggleAgentFields()"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                                <option value="1">Salaried</option>
                                <option value="2">Commission Only</option>
                                <option selected value="3">Credit Based</option>
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

                        <!-- Financials -->
                        <div class="md:col-span-2 mt-2">
                            <h4 class="text-sm font-semibold text-gray-900 border-b pb-1 mb-2">Financial Terms</h4>
                        </div>

                        <div id="field-baseSalary">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Base Salary (Rs)</label>
                            <input type="number" id="baseSalary"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>
                        <div class="hidden">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Commission Rate (%)</label>
                            <input type="number" step="0.1" id="commissionRate"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>
                        <div id="field-creditLimit">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Credit Limit (Rs)</label>
                            <input type="number" id="creditLimit"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
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

                        <!-- Sales Targets -->
                        <div class="md:col-span-2 mt-4">
                            <h4 class="text-sm font-semibold text-gray-900 border-b pb-1 mb-3 flex items-center">
                                <i class="bi bi-graph-up-arrow text-amber-600 mr-2"></i>Sales Targets
                            </h4>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Monthly Sales Target (Rs)</label>
                            <input type="number" step="0.01" id="monthlySalesTarget"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                placeholder="Enter monthly sales target amount">
                        </div>

                        <!-- Category Targets -->
                        <div class="md:col-span-2 mt-2">
                            <div class="flex justify-between items-center border-b pb-1 mb-3">
                                <h4 class="text-sm font-semibold text-gray-900 flex items-center">
                                    <i class="bi bi-tags text-blue-600 mr-2"></i>Category Targets
                                </h4>
                                <button type="button" onclick="addCategoryTargetCard()"
                                    class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-full flex items-center">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div id="categoryTargetsContainer" class="md:col-span-2 space-y-3">
                            <!-- Category target cards will be added here dynamically -->
                        </div>

                        <!-- SKU Targets -->
                        <div class="md:col-span-2 mt-2">
                            <div class="flex justify-between items-center border-b pb-1 mb-3">
                                <h4 class="text-sm font-semibold text-gray-900 flex items-center">
                                    <i class="bi bi-box-seam text-green-600 mr-2"></i>SKU Targets
                                </h4>
                                <button type="button" onclick="addItemTargetCard()"
                                    class="text-xs bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-full flex items-center">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div id="itemTargetsContainer" class="md:col-span-2 space-y-3">
                            <!-- SKU target cards will be added here dynamically -->
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
                <div class="bg-white px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-full flex items-center justify-center text-amber-600">
                            <i class="bi bi-person-badge text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight" id="view-name">Agent Name</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded" id="view-code">CODE</span>
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
                                        <span id="view-address" class="text-sm font-semibold text-gray-900 whitespace-pre-line"></span>
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
                                    <span class="text-xs text-gray-500 block">Base Salary</span>
                                    <span id="view-salary" class="text-sm font-bold text-gray-900"></span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Commission</span>
                                    <span id="view-commission" class="text-sm font-bold text-gray-900 text-purple-600"></span>
                                </div>
                                <div class="col-span-2 border-t border-gray-200 pt-3">
                                    <span class="text-xs text-gray-500 block">Credit Terms</span>
                                    <div class="flex items-center gap-3 mt-1">
                                        <div>
                                            <span class="text-[10px] text-gray-400 uppercase block">Limit</span>
                                            <span id="view-limit" class="text-sm font-bold text-gray-900"></span>
                                        </div>
                                        <div class="w-px h-8 bg-gray-200 mx-2"></div>
                                        <div>
                                            <span class="text-[10px] text-gray-400 uppercase block">Period</span>
                                            <span id="view-credit-days" class="text-sm font-bold text-gray-900"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-2 bg-red-50 -mx-4 -mb-4 p-4 rounded-b-xl flex justify-between items-center">
                                    <span class="text-sm font-bold text-red-700">Outstanding Balance</span>
                                    <span id="view-outstanding" class="text-lg font-black text-red-600"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Targets Section -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center gap-2">
                                <i class="bi bi-graph-up-arrow"></i> Performance Targets
                            </h4>
                            <div class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                                Monthly Goal: <span id="view-monthly-target">Rs. 0.00</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Category Targets View -->
                            <div class="border border-blue-100 rounded-xl overflow-hidden bg-white">
                                <div class="bg-blue-50 px-4 py-2 border-b border-blue-100">
                                    <span class="text-xs font-bold text-blue-700 uppercase flex items-center gap-2">
                                        <i class="bi bi-tags"></i> Category Targets
                                    </span>
                                </div>
                                <div class="p-0 overflow-x-auto">
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-gray-50 text-gray-500 border-b">
                                            <tr>
                                                <th class="px-4 py-2">Category</th>
                                                <th class="px-4 py-2 text-right">Target</th>
                                                <th class="px-4 py-2 text-right">%</th>
                                            </tr>
                                        </thead>
                                        <tbody id="view-cat-targets-body" class="divide-y divide-gray-100">
                                            <!-- Category targets will be inserted here -->
                                        </tbody>
                                        <tfoot id="view-cat-targets-empty" class="hidden">
                                            <tr>
                                                <td colspan="3" class="px-4 py-4 text-center text-gray-400 italic">No category targets set</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- SKU Targets View -->
                            <div class="border border-green-100 rounded-xl overflow-hidden bg-white">
                                <div class="bg-green-50 px-4 py-2 border-b border-green-100">
                                    <span class="text-xs font-bold text-green-700 uppercase flex items-center gap-2">
                                        <i class="bi bi-box-seam"></i> SKU Targets
                                    </span>
                                </div>
                                <div class="p-0 overflow-x-auto">
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-gray-50 text-gray-500 border-b">
                                            <tr>
                                                <th class="px-4 py-2">Product Item</th>
                                                <th class="px-4 py-2 text-right">Target</th>
                                                <th class="px-4 py-2 text-right">%</th>
                                            </tr>
                                        </thead>
                                        <tbody id="view-item-targets-body" class="divide-y divide-gray-100">
                                            <!-- SKU targets will be inserted here -->
                                        </tbody>
                                        <tfoot id="view-item-targets-empty" class="hidden">
                                            <tr>
                                                <td colspan="3" class="px-4 py-4 text-center text-gray-400 italic">No SKU targets set</td>
                                            </tr>
                                        </tfoot>
                                    </table>
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
                        <div id="view-bank-empty" class="hidden bg-gray-50 rounded-xl p-6 text-center text-gray-400 italic text-sm">
                            No bank account information available
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end items-center gap-3 border-t border-gray-100 sticky bottom-0">
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

    <script>
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
                2: 'Commission Only',
                3: 'Credit Based'
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
                            <input type="text" class="bank-name block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm" 
                                value="${bankData?.bank_name || ''}" required>
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
                const bankName = card.querySelector('.bank-name').value.trim();
                const accountNumber = card.querySelector('.account-number').value.trim();
                const branch = card.querySelector('.branch').value.trim();
                const isPrimary = card.querySelector('.is-primary').checked;
                
                if (bankName && accountNumber) {
                    bankAccounts.push({
                        bank_name: bankName,
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

        // Category Targets Management
        let categoryTargetCounter = 0;
        const productCategories = @json($productCategories);

        function addCategoryTargetCard(data = null) {
            const cardId = `catTarget${categoryTargetCounter++}`;
            const container = document.getElementById('categoryTargetsContainer');

            let optionsHtml = '<option value="">Select Category</option>';
            productCategories.forEach(cat => {
                const selected = data && data.pm_product_category_id == cat.id ? 'selected' : '';
                optionsHtml += `<option value="${cat.id}" ${selected}>${cat.category_name}${cat.category_code ? ' (' + cat.category_code + ')' : ''}</option>`;
            });

            const cardHtml = `
                <div id="${cardId}" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-3">
                        <h5 class="text-sm font-semibold text-blue-700"><i class="bi bi-tags mr-1"></i>Category Target</h5>
                        <button type="button" onclick="removeCategoryTargetCard('${cardId}')" 
                            class="text-red-600 hover:text-red-800 text-sm">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Category *</label>
                            <select class="cat-select block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                ${optionsHtml}
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Target Amount (Rs)</label>
                            <input type="number" step="0.01" class="cat-amount block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                value="${data?.target_amount || ''}" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Target %</label>
                            <input type="number" step="0.01" max="100" class="cat-percentage block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                                value="${data?.target_percentage || ''}" placeholder="0.00">
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', cardHtml);
        }

        function removeCategoryTargetCard(cardId) {
            document.getElementById(cardId)?.remove();
        }

        function collectCategoryTargets() {
            const cards = document.querySelectorAll('#categoryTargetsContainer > div');
            const targets = [];
            cards.forEach(card => {
                const catId = card.querySelector('.cat-select').value;
                const amount = card.querySelector('.cat-amount').value;
                const percentage = card.querySelector('.cat-percentage').value;
                if (catId) {
                    targets.push({
                        pm_product_category_id: parseInt(catId),
                        target_amount: amount || null,
                        target_percentage: percentage || null,
                    });
                }
            });
            return targets;
        }

        function loadCategoryTargets(data) {
            document.getElementById('categoryTargetsContainer').innerHTML = '';
            categoryTargetCounter = 0;
            if (data && data.length > 0) {
                data.forEach(t => addCategoryTargetCard(t));
            }
        }

        // SKU Targets Management
        let itemTargetCounter = 0;
        const productItems = @json($productItems);

        function addItemTargetCard(data = null) {
            const cardId = `itemTarget${itemTargetCounter++}`;
            const container = document.getElementById('itemTargetsContainer');

            let optionsHtml = '<option value="">Select Product Item</option>';
            productItems.forEach(item => {
                const selected = data && data.pm_product_item_id == item.id ? 'selected' : '';
                optionsHtml += `<option value="${item.id}" ${selected}>${item.product_name}</option>`;
            });

            const cardHtml = `
                <div id="${cardId}" class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex justify-between items-start mb-3">
                        <h5 class="text-sm font-semibold text-green-700"><i class="bi bi-box-seam mr-1"></i>SKU Target</h5>
                        <button type="button" onclick="removeItemTargetCard('${cardId}')" 
                            class="text-red-600 hover:text-red-800 text-sm">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Product Item *</label>
                            <select class="item-select block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                ${optionsHtml}
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Target Amount (Rs)</label>
                            <input type="number" step="0.01" class="item-amount block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" 
                                value="${data?.target_amount || ''}" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Target %</label>
                            <input type="number" step="0.01" max="100" class="item-percentage block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" 
                                value="${data?.target_percentage || ''}" placeholder="0.00">
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', cardHtml);
        }

        function removeItemTargetCard(cardId) {
            document.getElementById(cardId)?.remove();
        }

        function collectItemTargets() {
            const cards = document.querySelectorAll('#itemTargetsContainer > div');
            const targets = [];
            cards.forEach(card => {
                const itemId = card.querySelector('.item-select').value;
                const amount = card.querySelector('.item-amount').value;
                const percentage = card.querySelector('.item-percentage').value;
                if (itemId) {
                    targets.push({
                        pm_product_item_id: parseInt(itemId),
                        target_amount: amount || null,
                        target_percentage: percentage || null,
                    });
                }
            });
            return targets;
        }

        function loadItemTargets(data) {
            document.getElementById('itemTargetsContainer').innerHTML = '';
            itemTargetCounter = 0;
            if (data && data.length > 0) {
                data.forEach(t => addItemTargetCard(t));
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
                loadCategoryTargets([]); // Clear category targets
                loadItemTargets([]); // Clear SKU targets
                document.getElementById('monthlySalesTarget').value = '';
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
            document.getElementById('baseSalary').value = agent.baseSalary || '';
            document.getElementById('commissionRate').value = agent.commissionRate || '';
            document.getElementById('creditLimit').value = agent.creditLimit || '';
            document.getElementById('creditPeriodDays').value = agent.creditPeriodDays || '';
            document.getElementById('monthlySalesTarget').value = agent.monthlySalesTarget || '';

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

            // Load targets
            loadCategoryTargets(agent.category_targets || []);
            loadItemTargets(agent.item_targets || []);

            toggleAgentFields();

            modalTitle.innerText = "Edit Agent";
            modalBtn.innerText = "Update Agent";
            openAgentModal(true);
        }

        function toggleAgentFields() {
            const type = document.getElementById('agentType').value;
            const salaryField = document.getElementById('field-baseSalary');
            const limitField = document.getElementById('field-creditLimit');
            const daysField = document.getElementById('field-creditDays');

            if (type === '1') { // Salaried
                salaryField.classList.remove('hidden');
                limitField.classList.add('hidden');
                daysField.classList.add('hidden');
            } else if (type === '2') { // Commission Only
                salaryField.classList.add('hidden');
                limitField.classList.add('hidden');
                daysField.classList.add('hidden');
            } else if (type === '3') { // Credit Based
                salaryField.classList.add('hidden');
                limitField.classList.remove('hidden');
                daysField.classList.remove('hidden');
            }
        }

        function submitAgentForm() {
            const isEdit = modalBtn.innerText.includes('Update');
            const agentId = document.getElementById('agent-form').dataset.agentId || null;
            
            // Collect bank data from cards
            const bankData = collectBankData();
            
            // Validate bank accounts
            if (bankData.length === 0) {
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
                base_salary: document.getElementById('baseSalary').value || null,
                commission_rate: document.getElementById('commissionRate').value || null,
                credit_limit: document.getElementById('creditLimit').value || null,
                credit_period_days: document.getElementById('creditPeriodDays').value || null,
                monthly_sales_target: document.getElementById('monthlySalesTarget').value || null,
                bank_accounts: bankData,
                category_targets: collectCategoryTargets(),
                item_targets: collectItemTargets(),
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

        function deleteAgent(agentId, name) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Deactivate " + name + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, deactivate'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/agents/${agentId}/deactivate`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deactivated!', data.message, 'success').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Failed to deactivate agent: ' + error.message, 'error');
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
            const formatCurrency = (val) => val ? 'Rs. ' + parseFloat(val).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '-';
            
            document.getElementById('view-salary').innerText = formatCurrency(agent.baseSalary);
            document.getElementById('view-commission').innerText = agent.commissionRate ? agent.commissionRate + '%' : '0%';
            document.getElementById('view-limit').innerText = formatCurrency(agent.creditLimit);
            document.getElementById('view-credit-days').innerText = agent.creditPeriodDays ? agent.creditPeriodDays + ' Days' : '-';
            document.getElementById('view-outstanding').innerText = formatCurrency(agent.outstandingBalance || 0);

            // Targets
            document.getElementById('view-monthly-target').innerText = formatCurrency(agent.monthlySalesTarget || 0);
            
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
            const typeColors = {1: 'bg-blue-100 text-blue-800', 2: 'bg-purple-100 text-purple-800', 3: 'bg-orange-100 text-orange-800'};
            const statusColors = {1: 'bg-green-100 text-green-800', 2: 'bg-red-100 text-red-800'};
            
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
    </script>
@endsection