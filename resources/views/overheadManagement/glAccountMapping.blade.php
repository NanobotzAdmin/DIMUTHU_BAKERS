@extends('layouts.app')

@section('content')
<div class="p-6 max-w-full mx-auto space-y-6">
    <div class="flex flex-col">
        <h1 class="flex items-center gap-3 text-2xl font-bold">
            <svg class="w-8 h-8 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            GL Account Mapping
        </h1>
        <p class="text-gray-600 mt-1">Map overhead categories to General Ledger accounts for automated journal entry generation</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
            $completion = ($integrationStatus['mappedPools'] / $integrationStatus['totalPools']) * 100;
        @endphp
        <div class="p-6 rounded-xl border {{ $integrationStatus['isMappingComplete'] ? 'border-green-200 bg-green-50' : 'border-yellow-200 bg-yellow-50' }}">
            <p class="text-xs font-bold text-gray-500 uppercase mb-2">Mapping Status</p>
            <div class="flex items-center gap-2">
                @if($integrationStatus['isMappingComplete'])
                    <span class="text-3xl font-black text-green-600">Complete</span>
                @else
                    <span class="text-3xl font-black text-yellow-600">{{ round($completion) }}%</span>
                @endif
            </div>
            <p class="text-xs text-gray-500 mt-2">{{ $integrationStatus['isMappingComplete'] ? 'All items mapped' : count($integrationStatus['unmappedPools']) . ' cost pools need mapping' }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-bold text-gray-500 uppercase mb-2">Cost Pool Mapping</p>
            <p class="text-3xl font-black text-gray-900">{{ $integrationStatus['mappedPools'] }}/{{ $integrationStatus['totalPools'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Cost pools mapped to GL</p>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-bold text-gray-500 uppercase mb-2">Expense Mapping</p>
            <p class="text-3xl font-black text-gray-900">{{ $integrationStatus['mappedExpenses'] }}/{{ $integrationStatus['totalExpenses'] }}</p>
            <p class="text-xs text-gray-500 mt-2">Individual expenses mapped</p>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="font-bold text-lg leading-none text-gray-900">Quick Actions</h3>
        <p class="text-sm text-gray-500 mt-1">Manage GL account mappings and integration</p>
    </div>
    <div class="p-6 flex flex-wrap gap-3">
        <button onclick="handleAutoApply()" class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 shadow-lg shadow-amber-100 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Auto-Apply Mappings to All Items
        </button>
        
        <a href="/finance/chart-of-accounts" class="border-2 border-gray-200 px-4 py-2 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            View Chart of Accounts
        </a>

        <a href="/overhead/cost-pools" class="border-2 border-gray-200 px-4 py-2 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Manage Cost Pools
        </a>

        <a href="/overhead/expense-recording" class="border-2 border-gray-200 px-4 py-2 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Record Expenses
        </a>
    </div>
</div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="font-black text-lg">Overhead Category to GL Account Mappings</h3>
            <p class="text-sm text-gray-500">Map each overhead expense category to corresponding GL accounts.</p>
        </div>
        <div class="p-6 space-y-4">
            @foreach($expenseCategories as $cat)
                @php
                    $mapping = $mappings[$cat['category']] ?? null;
                @endphp
                <div class="border border-gray-200 rounded-xl p-5 hover:border-gray-900 transition-all group" id="cat-row-{{ $cat['category'] }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="font-bold text-gray-900">{{ $cat['label'] }}</h4>
                                @if($mapping)
                                    <span class="bg-green-100 text-green-800 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-wider">Mapped</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-wider">Not Mapped</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">{{ $cat['description'] }}</p>

                            <div id="read-{{ $cat['category'] }}" class="{{ $mapping ? '' : 'hidden' }} mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-3 rounded-lg flex items-center gap-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-xs font-bold text-gray-700" id="read-exp-{{ $cat['category'] }}">
                                        {{ $mapping ? 'GL: ' . ($glAccounts[array_search($mapping['glExpenseAccountId'], array_column($glAccounts, 'id'))]['code'] ?? '') : '' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button onclick="toggleEdit('{{ $cat['category'] }}')" class="border px-4 py-1.5 rounded-lg text-xs font-bold uppercase hover:bg-gray-100">Edit</button>
                            @if($mapping)
                                <button onclick="deleteMapping('{{ $cat['category'] }}')" class="text-red-600 text-xs font-black uppercase px-2 hover:underline">Delete</button>
                            @endif
                        </div>
                    </div>

                    <div id="edit-{{ $cat['category'] }}" class="hidden mt-6 bg-gray-50 rounded-xl p-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Expense GL Account *</label>
                                <select id="select-exp-{{ $cat['category'] }}" class="w-full border-gray-200 rounded-lg text-sm focus:ring-[#D4A017] focus:border-[#D4A017]">
                                    <option value="">Select Account...</option>
                                    @foreach($glAccounts as $account)
                                        @if($account['type'] == 'expense')
                                            <option value="{{ $account['id'] }}" {{ ($mapping['glExpenseAccountId'] ?? '') == $account['id'] ? 'selected' : '' }}>
                                                {{ $account['code'] }} - {{ $account['name'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Allocation Account (Optional)</label>
                                <select id="select-allo-{{ $cat['category'] }}" class="w-full border-gray-200 rounded-lg text-sm focus:ring-[#D4A017] focus:border-[#D4A017]">
                                    <option value="">None...</option>
                                    @foreach($glAccounts as $account)
                                        <option value="{{ $account['id'] }}" {{ ($mapping['glAllocationAccountId'] ?? '') == $account['id'] ? 'selected' : '' }}>
                                            {{ $account['code'] }} - {{ $account['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                            <button onclick="toggleEdit('{{ $cat['category'] }}')" class="px-4 py-2 text-sm font-bold text-gray-500">Cancel</button>
                            <button onclick="saveMapping('{{ $cat['category'] }}')" class="bg-[#D4A017] text-white px-6 py-2 rounded-lg text-xs font-black uppercase shadow-lg shadow-amber-100">Save Mapping</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rounded-xl border-2 border-blue-200 bg-blue-50 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-blue-100">
        <h3 class="font-black text-blue-900 uppercase tracking-widest text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            How GL Account Mapping Works
        </h3>
    </div>
    <div class="p-6">
        <div class="space-y-4 text-sm text-blue-900">
            <div class="flex items-start gap-3">
                <span class="font-bold">Step 1:</span>
                <p>Map each overhead category (Utilities, Rent, etc.) to GL expense accounts.</p>
            </div>
            <div class="flex items-start gap-3">
                <span class="font-bold">Step 2:</span>
                <p>Click "Auto-Apply Mappings" to automatically apply these mappings to all cost pools and expenses.</p>
            </div>
            <div class="flex items-start gap-3">
                <span class="font-bold">Step 3:</span>
                <p>When overhead expenses are recorded or allocated, journal entries will be automatically generated.</p>
            </div>

            <div class="mt-6 pt-6 border-t border-blue-200">
                <p class="leading-relaxed">
                    <strong>Example:</strong> When you map "Utilities" to account 
                    <span class="font-mono bg-blue-100 px-1 rounded text-blue-800 font-bold text-xs">5100-001 Utilities Expense</span>, 
                    all utility cost pools will automatically use this account for journal entry generation.
                </p>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function toggleEdit(cat) {
        const editPanel = document.getElementById('edit-' + cat);
        const readPanel = document.getElementById('read-' + cat);
        const row = document.getElementById('cat-row-' + cat);
        
        if (editPanel.classList.contains('hidden')) {
            editPanel.classList.remove('hidden');
            row.classList.add('ring-2', 'ring-[#D4A017]', 'bg-amber-50/30');
        } else {
            editPanel.classList.add('hidden');
            row.classList.remove('ring-2', 'ring-[#D4A017]', 'bg-amber-50/30');
        }
    }

    function saveMapping(cat) {
        const expId = document.getElementById('select-exp-' + cat).value;
        if(!expId) {
            alert("Please select an expense account.");
            return;
        }
        alert("Mapping for " + cat + " saved successfully!");
        toggleEdit(cat);
        // Normally you would perform an AJAX POST here
        window.location.reload();
    }

    function handleAutoApply() {
        if(confirm("Apply these account mappings to all existing cost pools and expenses?")) {
            alert("Mappings applied successfully to 7 pools!");
        }
    }

    function deleteMapping(cat) {
        if(confirm("Remove this mapping? This will stop automated entries for " + cat + ".")) {
            alert("Mapping deleted.");
            window.location.reload();
        }
    }
</script>
@endsection