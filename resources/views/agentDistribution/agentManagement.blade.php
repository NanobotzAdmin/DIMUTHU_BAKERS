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
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out flex flex-col max-h-[90vh]">

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
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Agent Type *</label>
                            <select id="agentType" onchange="toggleAgentFields()"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                                <option value="1">Salaried</option>
                                <option value="2">Commission Only</option>
                                <option value="3">Credit Based</option>
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
                        <div>
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
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Commission Rate (%)</label>
                            <input type="number" step="0.1" id="commissionRate"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>
                        <div id="field-creditLimit" class="hidden">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Credit Limit (Rs)</label>
                            <input type="number" id="creditLimit"
                                class="block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                        </div>
                        <div id="field-creditDays" class="hidden">
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
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-start mb-4 border-b border-gray-100 pb-2">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900" id="view-name">Agent Name</h3>
                            <p class="text-sm text-gray-500" id="view-code">CODE</p>
                        </div>
                        <div class="flex gap-2" id="view-badges">
                            <!-- Badges -->
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <!-- Contact -->
                        <div class="col-span-2">
                            <h4 class="font-semibold text-gray-700">Contact Info</h4>
                        </div>
                        <div><span class="text-gray-500 block">Phone</span><span id="view-phone" class="font-medium"></span>
                        </div>
                        <div><span class="text-gray-500 block">Email</span><span id="view-email" class="font-medium"></span>
                        </div>
                        <div><span class="text-gray-500 block">NIC</span><span id="view-nic" class="font-medium"></span>
                        </div>
                        <div><span class="text-gray-500 block">Address</span><span id="view-address"
                                class="font-medium"></span></div>

                        <!-- Financials -->
                        <div class="col-span-2 mt-2">
                            <h4 class="font-semibold text-gray-700">Financials</h4>
                        </div>
                        <div><span class="text-gray-500 block">Base Salary</span><span id="view-salary"
                                class="font-medium"></span></div>
                        <div><span class="text-gray-500 block">Commission</span><span id="view-commission"
                                class="font-medium"></span></div>
                        <div><span class="text-gray-500 block">Credit Limit</span><span id="view-limit"
                                class="font-medium"></span></div>
                        <div><span class="text-gray-500 block">Outstanding</span><span id="view-outstanding"
                                class="font-medium text-red-600"></span></div>

                        <!-- Banking -->
                        <div class="col-span-2 mt-2">
                            <h4 class="font-semibold text-gray-700">Banking</h4>
                        </div>
                        <div class="col-span-2">
                            <span id="view-bank" class="block font-medium"></span>
                            <span id="view-account" class="text-gray-500 text-xs"></span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="closeViewModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Close</button>
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

        function openAgentModal(isEdit = false) {
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');

            if (!isEdit) {
                form.reset();
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
            document.getElementById('baseSalary').value = agent.baseSalary || '';
            document.getElementById('commissionRate').value = agent.commissionRate || '';
            document.getElementById('creditLimit').value = agent.creditLimit || '';
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
                bank_accounts: bankData  // Use collected bank data
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

            document.getElementById('view-name').innerText = agent.agentName;
            document.getElementById('view-code').innerText = agent.agentCode;
            document.getElementById('view-phone').innerText = agent.contactPhone || '-';
            document.getElementById('view-email').innerText = agent.contactEmail || '-';
            document.getElementById('view-nic').innerText = agent.nicNumber || '-';
            document.getElementById('view-address').innerText = agent.address || '-';

            document.getElementById('view-salary').innerText = agent.baseSalary ? 'Rs. ' + agent.baseSalary.toLocaleString() : '-';
            document.getElementById('view-commission').innerText = agent.commissionRate + '%';
            document.getElementById('view-limit').innerText = agent.creditLimit ? 'Rs. ' + agent.creditLimit.toLocaleString() : '-';
            document.getElementById('view-outstanding').innerText = 'Rs. ' + (agent.outstandingBalance || 0).toLocaleString();

            document.getElementById('view-bank').innerText = agent.bankName || '-';
            document.getElementById('view-account').innerText = (agent.bankAccountNumber || '') + (agent.bankBranch ? ' (' + agent.bankBranch + ')' : '');

            // Badges - map integer values to text with appropriate colors
            const badgeContainer = document.getElementById('view-badges');
            const typeColors = {1: 'bg-blue-100 text-blue-800', 2: 'bg-purple-100 text-purple-800', 3: 'bg-orange-100 text-orange-800'};
            const statusColors = {1: 'bg-green-100 text-green-800', 2: 'bg-red-100 text-red-800'};
            
            badgeContainer.innerHTML = `
                    <span class="px-2 py-1 rounded text-xs font-medium ${typeColors[agent.agentType] || 'bg-gray-100 text-gray-800'}">${getAgentTypeText(agent.agentType)}</span>
                    <span class="px-2 py-1 rounded text-xs font-medium ${statusColors[agent.employmentStatus] || 'bg-gray-100 text-gray-800'}">${getStatusText(agent.employmentStatus)}</span>
                `;

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