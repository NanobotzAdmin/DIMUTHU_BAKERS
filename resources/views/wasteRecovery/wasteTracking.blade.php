@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1600px] mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col">
        <h1 class="flex items-center gap-3 text-2xl font-bold">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Waste Tracking & Stage Transitions
        </h1>
        <p class="text-gray-600 mt-1">
            Monitor waste through three stages with automated journal entries
        </p>
    </div>

    <!-- Statistics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex flex-col">
                <p class="text-sm text-gray-500 font-medium">Active Records</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ $stats['activeRecords'] }}</h3>
                <p class="text-xs text-gray-400 mt-1">Currently tracking</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex flex-col">
                <p class="text-sm text-gray-500 font-medium">Total NRV Write-down</p>
                <h3 class="text-3xl font-bold text-orange-600">${{ number_format($stats['financial']['totalNRVWritedown'], 2) }}</h3>
                <p class="text-xs text-gray-400 mt-1">Stage 1 → 2 losses</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex flex-col">
                <p class="text-sm text-gray-500 font-medium">Net Waste Loss</p>
                <h3 class="text-3xl font-bold text-red-600">${{ number_format($stats['financial']['netWasteLoss'], 2) }}</h3>
                <p class="text-xs text-gray-400 mt-1">After recovery</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex flex-col">
                <p class="text-sm text-gray-500 font-medium">Recovery Efficiency</p>
                <h3 class="text-3xl font-bold text-green-600">{{ number_format($stats['averageRecoveryEfficiency'], 1) }}%</h3>
                <p class="text-xs text-gray-400 mt-1">Average recovery rate</p>
            </div>
        </div>
    </div>

    <!-- Stage Distribution -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-lg font-semibold mb-4">Inventory by Stage</h3>
        <p class="text-sm text-gray-500 mb-4">Current distribution across three stages</p>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-green-50 rounded-lg p-4 border-2 border-green-200">
                <div class="flex justify-between items-center text-green-800 font-medium mb-2">
                    <span class="text-sm">Stage 1: Fresh</span>
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <p class="text-2xl font-bold text-green-900">{{ $stats['byStage']['fresh'] }}</p>
                <p class="text-xs text-green-700 mt-1">Full price items</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4 border-2 border-orange-200">
                <div class="flex justify-between items-center text-orange-800 font-medium mb-2">
                    <span class="text-sm">Stage 2: Day-Old</span>
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <p class="text-2xl font-bold text-orange-900">{{ $stats['byStage']['dayOld'] }}</p>
                <p class="text-xs text-orange-700 mt-1">Discounted items</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border-2 border-red-200">
                <div class="flex justify-between items-center text-red-800 font-medium mb-2">
                    <span class="text-sm">Stage 3: Waste</span>
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <p class="text-2xl font-bold text-red-900">{{ $stats['byStage']['waste'] }}</p>
                <p class="text-xs text-red-700 mt-1">For recovery</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 border-2 border-blue-200">
                <div class="flex justify-between items-center text-blue-800 font-medium mb-2">
                    <span class="text-sm">Recovered</span>
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <p class="text-2xl font-bold text-blue-900">{{ $stats['byStage']['recovered'] }}</p>
                <p class="text-xs text-blue-700 mt-1">Processed items</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border-2 border-gray-200">
                <div class="flex justify-between items-center text-gray-800 font-medium mb-2">
                    <span class="text-sm">Disposed</span>
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['byStage']['disposed'] }}</p>
                <p class="text-xs text-gray-700 mt-1">No recovery</p>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    @if(count($alerts) > 0)
    <div class="bg-orange-50 p-6 rounded-xl border border-orange-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-orange-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Waste Alerts ({!! count($alerts) !!})
            </h3>
        </div>
        <div class="space-y-3">
            @foreach($alerts as $alert)
            <div class="bg-white rounded-lg p-4 border-2 border-orange-200 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase text-white
                                {{ $alert->urgency === 'critical' ? 'bg-red-600' : 
                                   ($alert->urgency === 'high' ? 'bg-orange-500' : 
                                   ($alert->urgency === 'medium' ? 'bg-yellow-500' : 'bg-blue-500')) }}">
                                {{ $alert->urgency }}
                            </span>
                            <span class="font-semibold">{{ $alert->productName }}</span>
                            <span class="text-sm text-gray-600">({{ $alert->trackingNumber }})</span>
                        </div>
                        <p class="text-sm text-gray-700 mb-2">{{ $alert->message }}</p>
                        <div class="flex items-center gap-4 text-xs text-gray-600 flex-wrap">
                            <span>Age: {{ $alert->daysOld }} days</span>
                            <span>Qty: {{ $alert->quantity }}</span>
                            <span>Current Value: ${{ number_format($alert->currentValue, 2) }}</span>
                            <span class="text-red-600 font-medium">Potential Loss: ${{ number_format($alert->potentialLoss, 2) }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if($alert->recommendedAction === 'transfer-to-day-old')
                            <button onclick="handleTransition({{ $alert->trackingId }}, 'day-old')" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1.5 rounded text-sm font-medium transition-colors">
                                Transfer to Day-Old
                            </button>
                        @elseif($alert->recommendedAction === 'transfer-to-waste')
                            <button onclick="handleTransition({{ $alert->trackingId }}, 'waste')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-sm font-medium transition-colors">
                                Transfer to Waste
                            </button>
                        @elseif($alert->recommendedAction === 'process-now')
                            <button onclick="openRecoveryModalById({{ $alert->trackingId }})" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-sm font-medium transition-colors">
                                Process Recovery
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="flex gap-2" id="filter-buttons">
        <button onclick="filterStage('all')" class="px-4 py-2 rounded-md border border-gray-200 text-sm font-medium transition-colors bg-[#D4A017] text-white hover:bg-[#B8860B]" data-stage="all">All Stages ({{ count($trackingRecords) }})</button>
        <button onclick="filterStage('fresh')" class="px-4 py-2 rounded-md border border-gray-200 text-sm font-medium transition-colors bg-white hover:bg-green-50 text-gray-700" data-stage="fresh">Fresh ({{ $stats['byStage']['fresh'] }})</button>
        <button onclick="filterStage('day-old')" class="px-4 py-2 rounded-md border border-gray-200 text-sm font-medium transition-colors bg-white hover:bg-orange-50 text-gray-700" data-stage="day-old">Day-Old ({{ $stats['byStage']['dayOld'] }})</button>
        <button onclick="filterStage('waste')" class="px-4 py-2 rounded-md border border-gray-200 text-sm font-medium transition-colors bg-white hover:bg-red-50 text-gray-700" data-stage="waste">Waste ({{ $stats['byStage']['waste'] }})</button>
    </div>

    <!-- Tracking Records Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Tracking Records</h3>
            <p class="text-sm text-gray-500" id="table-description">All active waste tracking records</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 font-semibold text-sm">Tracking #</th>
                        <th class="py-3 px-4 font-semibold text-sm">Product</th>
                        <th class="py-3 px-4 font-semibold text-sm text-center">Age</th>
                        <th class="py-3 px-4 font-semibold text-sm text-center">Stage</th>
                        <th class="py-3 px-4 font-semibold text-sm text-right">Qty</th>
                        <th class="py-3 px-4 font-semibold text-sm text-right">Original Cost</th>
                        <th class="py-3 px-4 font-semibold text-sm text-right">Current Value</th>
                        <th class="py-3 px-4 font-semibold text-sm text-right">NRV Loss</th>
                        <th class="py-3 px-4 font-semibold text-sm text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="records-table-body">
                    @foreach($trackingRecords as $record)
                    @php
                        $profile = $productProfiles[$record->productCode] ?? null;
                    @endphp
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors record-row" data-stage="{{ $record->currentStage }}">
                        <td class="py-3 px-4 font-mono text-sm">{{ $record->trackingNumber }}</td>
                        <td class="py-3 px-4">
                            <div class="font-medium">{{ $record->productName }}</div>
                            <div class="text-xs text-gray-500">{{ $record->productCode }}</div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-1 bg-gray-100 rounded text-xs border border-gray-200">{{ $record->days_old }} days</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold capitalize
                                {{ $record->currentStage == 'fresh' ? 'bg-green-100 text-green-800' : 
                                   ($record->currentStage == 'day-old' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800') }}">
                                {{ $record->currentStage }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">{{ $record->quantity }} {{ $record->unitOfMeasure }}</td>
                        <td class="py-3 px-4 text-right text-gray-600">${{ number_format($record->originalCost, 2) }}</td>
                        <td class="py-3 px-4 text-right font-medium">${{ number_format($record->currentValue, 2) }}</td>
                        <td class="py-3 px-4 text-right text-red-600">${{ number_format($record->totalNRVWritedown, 2) }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex gap-1 justify-center">
                                @if($record->currentStage === 'fresh' && $record->days_old >= ($profile['dayOldStartDay'] ?? 1))
                                    <button onclick="handleTransition({{ $record->id }}, 'day-old')" class="bg-white border hover:bg-orange-50 text-orange-600 px-3 py-1 rounded text-sm flex items-center gap-1 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        Day-Old
                                    </button>
                                @endif
                                
                                @if($record->currentStage === 'day-old' && $record->days_old >= ($profile['wasteThresholdDay'] ?? 3))
                                    <button onclick="handleTransition({{ $record->id }}, 'waste')" class="bg-white border hover:bg-red-50 text-red-600 px-3 py-1 rounded text-sm flex items-center gap-1 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        Waste
                                    </button>
                                @endif

                                @if($record->currentStage === 'waste')
                                    <button onclick="openRecoveryModalById({{ $record->id }})" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors shadow-sm">
                                        Process
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if(count($trackingRecords) === 0)
                    <tr>
                        <td colspan="9" class="py-8 text-center text-gray-500">No records found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detailed Recovery Processing Modal -->
<div id="recoveryModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4 transition-opacity duration-200 opacity-0">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-transform duration-200 scale-95">
        <div class="p-6 border-b bg-gray-50">
            <h3 class="text-xl font-bold" id="modalTitle">Process Waste Recovery</h3>
            <p class="text-sm text-gray-500 font-mono" id="modalSubtitle"></p>
        </div>
        
        <div class="p-6 max-h-[70vh] overflow-y-auto">
            <!-- Record Summary -->
            <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg mb-6 border border-gray-100">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Current Quantity</p>
                    <p class="font-bold text-lg" id="modalQty"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Current Value</p>
                    <p class="font-bold text-lg" id="modalValue"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Original Cost</p>
                    <p class="font-bold text-lg text-gray-600" id="modalOriginalCost"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Loss So Far</p>
                    <p class="font-bold text-lg text-red-600" id="modalTotalLoss"></p>
                </div>
            </div>
            
            <h4 class="font-semibold mb-3 text-lg">Select Recovery Method:</h4>
            <div class="space-y-3" id="methodList">
                <!-- Methods will be populated by JS -->
            </div>
        </div>

        <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
            <button onclick="closeModal()" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100 font-medium transition-colors">Cancel</button>
        </div>
    </div>
</div>

<script>
    // --- Data Initialization ---
    window.trackingRecords = @json($trackingRecords);
    window.recoveryMethods = @json($recoveryMethods);
    window.productProfiles = @json($productProfiles);
    
    // --- State ---
    let currentRecord = null;
    let currentFilter = 'all';

    // --- DOM Elements ---
    const modal = document.getElementById('recoveryModal');
    const modalContent = modal.querySelector('div'); // The inner content div for animation

    // --- Interaction Functions ---

    function filterStage(stage) {
        currentFilter = stage;
        const rows = document.querySelectorAll('.record-row');
        const buttons = document.querySelectorAll('#filter-buttons button');
        const description = document.getElementById('table-description');

        // Update Description
        description.innerText = stage === 'all' ? 'All active waste tracking records' : `Records in ${stage} stage`;
        
        // Update Buttons UI
        buttons.forEach(btn => {
            const btnStage = btn.getAttribute('data-stage');
            // Reset to default
            btn.className = "px-4 py-2 rounded-md border text-sm font-medium transition-colors bg-white text-gray-700 hover:bg-gray-50";
            if (btnStage === 'fresh') btn.classList.add('hover:bg-green-50');
            if (btnStage === 'day-old') btn.classList.add('hover:bg-orange-50');
            if (btnStage === 'waste') btn.classList.add('hover:bg-red-50');

            // Set Active
            if(btnStage === stage) {
                if (stage === 'all') btn.className = "px-4 py-2 rounded-md border text-sm font-medium transition-colors bg-[#D4A017] text-white hover:bg-[#B8860B]";
                if (stage === 'fresh') btn.className = "px-4 py-2 rounded-md border text-sm font-medium transition-colors bg-green-600 text-white hover:bg-green-700";
                if (stage === 'day-old') btn.className = "px-4 py-2 rounded-md border text-sm font-medium transition-colors bg-orange-600 text-white hover:bg-orange-700";
                if (stage === 'waste') btn.className = "px-4 py-2 rounded-md border text-sm font-medium transition-colors bg-red-600 text-white hover:bg-red-700";
            }
        });

        // Filter Rows
        rows.forEach(row => {
            if (stage === 'all' || row.getAttribute('data-stage') === stage) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function handleTransition(id, toStage) {
        // Mock transition logic
        // In a real app, this would use fetch() to call an API endpoint
        const record = window.trackingRecords.find(r => r.id === id);
        if(!record) return;

        // Optimistic UI Update (or just a toast in this demo)
        // Swal would be better here if available, using alert for now as per minimal requirements or standard browser API
        const success = confirm(`Confirm transition of ${record.trackingNumber} to ${toStage}?`);
        if(success) {
            // Visualize change - in real app reload or update row
             alert(`Successfully transitioned ${record.productName} to ${toStage}.\nJournal Entry created automatically.`);
             location.reload(); // Simple reload to simulate state refresh
        }
    }

    function openRecoveryModalById(id) {
        const record = window.trackingRecords.find(r => r.id === id);
        if(!record) return;
        currentRecord = record;

        // Populate Modal Details
        document.getElementById('modalTitle').innerText = `Recovery: ${record.productName}`;
        document.getElementById('modalSubtitle').innerText = `${record.trackingNumber} - ${record.productCode}`;
        
        document.getElementById('modalQty').innerText = `${record.quantity} ${record.unitOfMeasure}`;
        document.getElementById('modalValue').innerText = '$' + Number(record.currentValue).toFixed(2);
        document.getElementById('modalOriginalCost').innerText = '$' + Number(record.originalCost).toFixed(2);
        
        const loss = record.originalCost - record.currentValue;
        document.getElementById('modalTotalLoss').innerText = '$' + loss.toFixed(2);

        // Populate Methods
        const methodList = document.getElementById('methodList');
        methodList.innerHTML = ''; // Clear previous

        window.recoveryMethods.forEach(method => {
            // Calculation Logic
            // Revenue = Qty * NRV
            const revenue = record.quantity * method.nrvPerKg; 
            // Cost = Qty * ProcessingCost
            const cost = record.quantity * method.processingCostPerKg;
            const net = revenue - cost;
            const isPositive = net >= 0;
            
            // Output Weight (Mock calculation: simple 80% efficiency for demo)
            const outputWeight = record.quantity * 0.8; 
            const efficiency = 80.0;

            const div = document.createElement('div');
            div.className = "border border-gray-200 rounded-lg p-4 hover:border-[#D4A017] cursor-pointer transition-colors bg-white hover:shadow-sm";
            div.onclick = () => submitRecovery(method.method, net);
            
            div.innerHTML = `
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <h4 class="font-semibold text-gray-900">${method.name}</h4>
                        <p class="text-sm text-gray-600">${method.description}</p>
                    </div>
                    <div class="bg-green-100 p-1.5 rounded-full">
                         <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-3 mt-3">
                    <div class="bg-blue-50 rounded p-2 text-center">
                        <p class="text-[10px] uppercase font-bold text-blue-700 tracking-wider">Output</p>
                        <p class="font-bold text-sm text-blue-900">${outputWeight.toFixed(1)}kg</p>
                    </div>
                    <div class="bg-green-50 rounded p-2 text-center">
                        <p class="text-[10px] uppercase font-bold text-green-700 tracking-wider">Revenue</p>
                        <p class="font-bold text-sm text-green-900">$${revenue.toFixed(2)}</p>
                    </div>
                    <div class="bg-red-50 rounded p-2 text-center">
                        <p class="text-[10px] uppercase font-bold text-red-700 tracking-wider">Cost</p>
                        <p class="font-bold text-sm text-red-900">$${cost.toFixed(2)}</p>
                    </div>
                    <div class="bg-purple-50 rounded p-2 text-center">
                        <p class="text-[10px] uppercase font-bold text-purple-700 tracking-wider">Net</p>
                        <p class="font-bold text-sm ${isPositive ? 'text-green-700' : 'text-red-700'}">
                            ${isPositive ? '+' : ''}$${net.toFixed(2)}
                        </p>
                    </div>
                </div>
            `;
            methodList.appendChild(div);
        });

        // Show Modal with animation
        modal.classList.remove('hidden');
        // Force reflow
        void modal.offsetWidth; 
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
        modal.classList.add('flex');
    }

    function closeModal() {
        // Animation out
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
        currentRecord = null;
    }

    function submitRecovery(methodName, netValue) {
        if(!currentRecord) return;
        
        const formattedNet = (netValue >= 0 ? '+' : '-') + '$' + Math.abs(netValue).toFixed(2);
        
        if(confirm(`Process ${currentRecord.productName} via ${methodName}?\nEstimated Net: ${formattedNet}`)) {
             alert(`Recovery processed successfully via ${methodName}.\nInventory updated.`);
             closeModal();
             location.reload();
        }
    }

    // Close modal on outside click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Initialize Default View
    filterStage('all');
</script>
@endsection