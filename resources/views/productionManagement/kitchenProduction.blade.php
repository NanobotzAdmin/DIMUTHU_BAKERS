@extends('layouts.app')
@section('title', 'Production Management')

@section('content')

    {{--
    1. MOCK DATA
    In a real app, this comes from your Controller.
    We define it here so the page works immediately for you.
    --}}
    @php
        $batches = [
            [
                'id' => 'BATCH-001',
                'recipeName' => 'Chocolate Cake',
                'recipeIcon' => '🎂',
                'section' => 'Kitchen',
                'scheduledTime' => '08:00 AM',
                'quantity' => 10,
                'unit' => 'units',
                'status' => 'in-progress',
                'priority' => 'high',
                'assignedTo' => 'Station 1',
                'startedAt' => '08:15 AM',
                'currentStep' => 3,
                'totalSteps' => 8,
                'estimatedCompletion' => '10:30 AM',
                'isWasteProcessing' => false,
                'ingredients' => [
                    ['name' => 'All-Purpose Flour', 'needed' => '5 kg', 'available' => '45 kg', 'status' => 'ok'],
                    ['name' => 'Sugar', 'needed' => '3 kg', 'available' => '65 kg', 'status' => 'ok'],
                    ['name' => 'Fresh Eggs', 'needed' => '30 pcs', 'available' => '120 pcs', 'status' => 'ok'],
                ],
                'steps' => [
                    ['id' => 1, 'name' => 'Preheat oven to 180°C', 'duration' => 10, 'type' => 'prep', 'completed' => true, 'temp' => 180],
                    ['id' => 2, 'name' => 'Mix dry ingredients', 'duration' => 5, 'type' => 'mixing', 'completed' => true],
                    ['id' => 3, 'name' => 'Cream butter and sugar', 'duration' => 8, 'type' => 'mixing', 'completed' => false, 'inProgress' => true],
                    ['id' => 4, 'name' => 'Add eggs one at a time', 'duration' => 5, 'type' => 'mixing', 'completed' => false],
                ]
            ],
            [
                'id' => 'BATCH-002',
                'recipeName' => 'Bread Loaves',
                'recipeIcon' => '🍞',
                'section' => 'Bakery',
                'scheduledTime' => '06:00 AM',
                'quantity' => 50,
                'unit' => 'loaves',
                'status' => 'completed',
                'priority' => 'medium',
                'assignedTo' => 'Station 2',
                'startedAt' => '06:00 AM',
                'completedAt' => '09:45 AM',
                'currentStep' => 10,
                'totalSteps' => 10,
                'actualOutput' => 48,
                'wasteQuantity' => 2,
                'wasteReason' => 'Quality Issue',
                'isWasteProcessing' => false,
                'ingredients' => [
                    ['name' => 'All-Purpose Flour', 'needed' => '15 kg', 'available' => '45 kg', 'status' => 'ok'],
                ],
                'steps' => []
            ],
            [
                'id' => 'BATCH-003',
                'recipeName' => 'Croissants',
                'recipeIcon' => '🥐',
                'section' => 'Bakery',
                'scheduledTime' => '10:00 AM',
                'quantity' => 100,
                'unit' => 'pcs',
                'status' => 'pending',
                'priority' => 'high',
                'assignedTo' => 'Station 3',
                'currentStep' => 0,
                'totalSteps' => 12,
                'estimatedDuration' => '180 mins',
                'isWasteProcessing' => false,
                'ingredients' => [
                    ['name' => 'Butter', 'needed' => '4 kg', 'available' => '8 kg', 'status' => 'ok'],
                ],
                'steps' => [
                    ['id' => 1, 'name' => 'Prepare dough', 'duration' => 30, 'type' => 'prep', 'completed' => false]
                ]
            ],
            [
                'id' => 'BATCH-007',
                'recipeName' => 'Breadcrumbs (Recycled)',
                'recipeIcon' => '♻️',
                'section' => 'Kitchen',
                'scheduledTime' => '02:00 PM',
                'quantity' => 500,
                'unit' => 'g',
                'status' => 'pending',
                'priority' => 'low',
                'assignedTo' => 'Station 2',
                'currentStep' => 0,
                'totalSteps' => 3,
                'estimatedDuration' => '30 mins',
                'isWasteProcessing' => true,
                'ingredients' => [
                    ['name' => 'Bread Trimmings', 'needed' => '600 g', 'available' => '850 g', 'status' => 'ok']
                ],
                'steps' => [
                    ['id' => 1, 'name' => 'Dry trimmings', 'duration' => 15, 'type' => 'baking', 'completed' => false]
                ]
            ]
        ];

        // Helper functions for classes
        function getStatusColor($status)
        {
            return match ($status) {
                'completed' => 'bg-green-500',
                'in-progress' => 'bg-blue-500',
                'pending' => 'bg-gray-400',
                default => 'bg-gray-400'
            };
        }

        function getPriorityClass($priority)
        {
            return match ($priority) {
                'high' => 'bg-red-100 text-red-700 border-red-300',
                'medium' => 'bg-orange-100 text-orange-700 border-orange-300',
                'low' => 'bg-blue-100 text-blue-700 border-blue-300',
                default => 'bg-gray-100 text-gray-700 border-gray-300'
            };
        }
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 p-4 md:p-6" id="production-app">

        {{-- Header Section --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                        {{-- ChefHat Icon (SVG) --}}
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Production Section</p>
                        <div class="relative group">
                            <button class="flex items-center gap-2 cursor-pointer">
                                <h1 class="text-3xl text-gray-900 font-bold">Kitchen</h1>
                                <svg class="w-7 h-7 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                        </div>
                        <p class="text-gray-600 mt-1">Today's production schedule - {{ date('m/d/Y') }}</p>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="flex gap-3">
                    <div class="bg-white rounded-xl p-4 shadow-sm min-w-[120px]">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span class="text-2xl text-gray-900" id="stat-pending">0</span>
                        </div>
                        <div class="text-sm text-gray-600">Pending</div>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm min-w-[120px]">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            <span class="text-2xl text-gray-900" id="stat-progress">0</span>
                        </div>
                        <div class="text-sm text-gray-600">In Progress</div>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm min-w-[120px]">
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span class="text-2xl text-gray-900" id="stat-completed">0</span>
                        </div>
                        <div class="text-sm text-gray-600">Completed</div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="flex gap-2">
                <button onclick="filterBatches('all')" class="filter-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg" data-filter="all">
                    <span class="font-medium">All Batches</span>
                </button>
                <button onclick="filterBatches('pending')" class="filter-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-white text-gray-700 hover:bg-gray-50" data-filter="pending">
                    <span class="font-medium">Pending</span>
                </button>
                <button onclick="filterBatches('in-progress')" class="filter-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-white text-gray-700 hover:bg-gray-50" data-filter="in-progress">
                    <span class="font-medium">In Progress</span>
                </button>
                <button onclick="filterBatches('completed')" class="filter-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-white text-gray-700 hover:bg-gray-50" data-filter="completed">
                    <span class="font-medium">Completed</span>
                </button>
            </div>
        </div>

        {{-- Batch List --}}
        <div class="space-y-4" id="batch-container">
            @foreach($batches as $index => $batch)
                @php 
                    $progress = ($batch['totalSteps'] > 0) ? ($batch['currentStep'] / $batch['totalSteps']) * 100 : 0;
                @endphp

                <div class="batch-card bg-white rounded-2xl p-5 shadow-sm border-2 transition-all {{ $batch['status'] === 'in-progress' ? 'border-blue-300 shadow-lg' : ($batch['isWasteProcessing'] ? 'border-green-200 bg-gradient-to-br from-white to-green-50' : 'border-gray-100') }}" 
                     data-status="{{ $batch['status'] }}"
                     data-section="{{ $batch['section'] }}"
                     data-id="{{ $batch['id'] }}">

                    <div class="flex items-start gap-4">
                        {{-- Icon & Number --}}
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center shadow-md {{ getStatusColor($batch['status']) }}">
                                <span class="text-2xl font-bold text-white">{{ $loop->iteration }}</span>
                            </div>
                            <div class="text-4xl">{{ $batch['recipeIcon'] }}</div>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="text-2xl text-gray-900">{{ $batch['recipeName'] }}</h3>
                                        @if($batch['isWasteProcessing'])
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white border-0 gap-1">
                                                ♻️ RECOVERY
                                            </span>
                                        @endif
                                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold uppercase {{ getPriorityClass($batch['priority']) }}">
                                            {{ $batch['priority'] }}
                                        </span>
                                    </div>

                                    {{-- Meta Data --}}
                                    <div class="flex items-center gap-4 text-gray-600 text-sm">
                                        <div class="flex items-center gap-1">
                                            <span>🕒 {{ $batch['scheduledTime'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span>📦 {{ $batch['quantity'] }} {{ $batch['unit'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span>👨‍🍳 {{ $batch['assignedTo'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status Badge --}}
                                <div class="flex items-center gap-2">
                                    <div class="w-4 h-4 rounded-full {{ getStatusColor($batch['status']) }} shadow-lg"></div>
                                    <span class="font-medium text-gray-700 capitalize">{{ str_replace('-', ' ', $batch['status']) }}</span>
                                </div>
                            </div>

                            {{-- Progress Bar --}}
                            @if($batch['status'] !== 'pending')
                                <div class="mb-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm text-gray-600">Step {{ $batch['currentStep'] }} of {{ $batch['totalSteps'] }}</span>
                                        <span class="text-sm font-medium text-gray-700">{{ number_format($progress, 0) }}%</span>
                                    </div>
                                    <div class="h-3 w-full overflow-hidden rounded-full bg-secondary bg-gray-100">
                                        <div class="h-full bg-blue-600 transition-all duration-500 ease-in-out" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            @endif

                            {{-- Action Buttons --}}
                            <div class="flex gap-3 mt-4">
                                @if($batch['status'] === 'pending')
                                    <button onclick="startBatch('{{ $batch['id'] }}')" class="h-12 px-6 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                                        ▶ Start Batch
                                    </button>
                                @elseif($batch['status'] === 'in-progress')
                                    <button onclick="openDetailModal('{{ $batch['id'] }}')" class="h-12 px-6 bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all animate-pulse">
                                        👁 Continue Working
                                    </button>
                                @else
                                    <button onclick="openDetailModal('{{ $batch['id'] }}')" class="h-12 px-6 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                                        📄 View Details
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- 
        =========================================
        MODALS (Hidden by default, vanilla JS) 
        =========================================
    --}}

    {{-- Overlay --}}
    <div id="modal-overlay" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden transition-opacity" onclick="closeAllModals()"></div>

    {{-- Batch Detail Modal --}}
    <div id="batch-detail-modal" class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-3xl translate-x-[-50%] translate-y-[-50%] gap-4 border bg-white p-6 shadow-lg duration-200 sm:rounded-lg max-h-[90vh] overflow-y-auto hidden">

        <div class="flex flex-col space-y-1.5 text-center sm:text-left mb-4">
            <h2 id="modal-title" class="text-2xl font-semibold leading-none tracking-tight flex items-center gap-3">
                {{-- Injected via JS --}}
            </h2>
            <p id="modal-desc" class="text-sm text-gray-500"></p>
        </div>

        <div class="space-y-5">
            {{-- Progress Section (Dynamic) --}}
            <div id="modal-progress-container" class="bg-blue-50 rounded-xl p-4 border-2 border-blue-200 hidden">
                 {{-- Injected via JS --}}
            </div>

            {{-- Ingredients (Dynamic) --}}
            <div>
                <h3 class="text-lg text-gray-900 mb-3 font-semibold">Ingredients Required</h3>
                <div id="modal-ingredients-grid" class="grid grid-cols-2 gap-2">
                    {{-- Injected via JS --}}
                </div>
            </div>

            {{-- Steps (Dynamic) --}}
            <div>
                <h3 class="text-lg text-gray-900 mb-3 font-semibold">Production Steps</h3>
                <div id="modal-steps-list" class="space-y-2">
                    {{-- Injected via JS --}}
                </div>
            </div>

            {{-- Complete Button --}}
            <div id="modal-complete-btn-container" class="pt-4 border-t-2 border-gray-200 hidden">
                <button onclick="openQualityModal()" class="w-full h-14 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl flex items-center justify-center gap-3 font-medium shadow-lg transition-all">
                    ✅ Complete Batch & Quality Check
                </button>
            </div>

            <button onclick="closeAllModals()" class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:outline-none">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="sr-only">Close</span>
            </button>
        </div>
    </div>

    {{-- Quality Check Modal --}}
    <div id="quality-check-modal" class="fixed left-[50%] top-[50%] z-50 grid w-full max-w-2xl translate-x-[-50%] translate-y-[-50%] gap-4 border bg-white p-6 shadow-lg duration-200 sm:rounded-lg hidden">
        <div class="flex flex-col space-y-1.5 text-center sm:text-left">
            <h2 class="text-2xl font-semibold flex items-center gap-2">
                ✅ Complete Batch - Quality Check
            </h2>
            <p class="text-sm text-gray-500">Record final output and waste.</p>
        </div>

        <div class="space-y-6">
            {{-- Actual Output Input --}}
            <div>
                <label class="block text-lg font-medium text-gray-700 mb-3">Actual Output Produced *</label>
                <div class="flex items-center gap-3">
                    <input type="number" id="quality-actual-input" class="flex-1 h-16 px-4 text-2xl rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none" placeholder="Enter quantity">
                    <span id="quality-unit" class="text-2xl text-gray-600">units</span>
                </div>
            </div>

            {{-- Waste Logic (Shown via JS) --}}
            <div id="quality-waste-container" class="bg-orange-50 border-2 border-orange-200 rounded-xl p-5 hidden">
                <h4 class="text-lg font-medium text-orange-900 mb-4">
                    🗑 Waste Detected: <span id="waste-amount">0</span>
                </h4>
                <label class="block text-sm font-medium text-gray-700 mb-2">Waste Reason</label>
                <select id="waste-reason" class="w-full h-12 rounded-xl border-gray-300">
                    <option value="">Select Reason...</option>
                    <option value="Burnt">Burnt</option>
                    <option value="Quality Issue">Quality Issue</option>
                    <option value="Dropped">Dropped</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button onclick="closeAllModals()" class="flex-1 h-14 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium text-lg">
                    Cancel
                </button>
                <button onclick="submitCompletion()" class="flex-1 h-14 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl font-medium text-lg shadow-lg">
                    Confirm Completion
                </button>
            </div>
        </div>
    </div>

    {{-- 
        =========================================
        MANUAL JAVASCRIPT
        =========================================
    --}}
    <script>
        // 1. Load Data from PHP
        const batches = @json($batches);
        let currentBatchId = null;

        // 2. Initialize View
        document.addEventListener('DOMContentLoaded', () => {
            updateStats();
        });

        // 3. Filtering Logic
        function filterBatches(status) {
            // Update Buttons
            document.querySelectorAll('.filter-btn').forEach(btn => {
                const isActive = btn.getAttribute('data-filter') === status;
                btn.className = isActive 
                    ? 'filter-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg'
                    : 'filter-btn flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-white text-gray-700 hover:bg-gray-50';
            });

            // Hide/Show Cards
            const cards = document.querySelectorAll('.batch-card');
            cards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                if (status === 'all' || cardStatus === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function updateStats() {
            const counts = {
                pending: batches.filter(b => b.status === 'pending').length,
                progress: batches.filter(b => b.status === 'in-progress').length,
                completed: batches.filter(b => b.status === 'completed').length
            };
            document.getElementById('stat-pending').innerText = counts.pending;
            document.getElementById('stat-progress').innerText = counts.progress;
            document.getElementById('stat-completed').innerText = counts.completed;
        }

        // 4. Modal Logic
        function openDetailModal(batchId) {
            const batch = batches.find(b => b.id === batchId);
            if(!batch) return;
            currentBatchId = batchId;

            // Populate Header
            document.getElementById('modal-title').innerHTML = `<span class="text-4xl">${batch.recipeIcon}</span> ${batch.recipeName}`;
            document.getElementById('modal-desc').innerText = `Batch ${batch.id} • ${batch.quantity} ${batch.unit} • ${batch.section}`;

            // Populate Ingredients
            const ingGrid = document.getElementById('modal-ingredients-grid');
            ingGrid.innerHTML = batch.ingredients.map(ing => `
                <div class="p-3 rounded-xl border-2 ${ing.status === 'ok' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'}">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-medium text-sm text-gray-900">${ing.name}</span>
                    </div>
                    <div class="text-xs text-gray-700">Need: <b>${ing.needed}</b></div>
                    <div class="text-xs text-green-700">Avail: <b>${ing.available}</b></div>
                </div>
            `).join('');

            // Populate Steps
            const stepsList = document.getElementById('modal-steps-list');
            if(batch.steps && batch.steps.length > 0) {
                stepsList.innerHTML = batch.steps.map(step => `
                    <div class="p-4 rounded-xl border-2 transition-all ${step.completed ? 'bg-green-50 border-green-300' : (step.inProgress ? 'bg-blue-50 border-blue-300 shadow-md' : 'bg-white border-gray-200')}">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold shadow-sm ${step.completed ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600'}">
                                ${step.completed ? '✓' : step.id}
                            </div>
                            <div class="flex-1">
                                <h4 class="text-base text-gray-900">${step.name}</h4>
                                <span class="text-xs bg-gray-200 px-2 py-1 rounded">${step.duration} min</span>
                            </div>
                             ${!step.completed && step.inProgress ? `
                                 <button onclick="completeStep(${step.id})" class="h-10 px-4 bg-green-500 text-white rounded-lg text-sm">Done</button>
                             ` : ''}
                        </div>
                    </div>
                `).join('');
            } else {
                stepsList.innerHTML = '<p class="text-gray-500 italic">No specific steps defined.</p>';
            }

            // Show/Hide Elements based on status
            const progressContainer = document.getElementById('modal-progress-container');
            const completeBtn = document.getElementById('modal-complete-btn-container');

            if (batch.status === 'in-progress') {
                const progress = (batch.currentStep / batch.totalSteps) * 100;
                progressContainer.innerHTML = `
                    <div class="flex justify-between mb-2"><span class="text-sm">Overall Progress</span><span class="font-bold text-blue-600">${progress.toFixed(0)}%</span></div>
                    <div class="h-2 bg-white rounded-full"><div class="h-full bg-blue-500 rounded-full" style="width: ${progress}%"></div></div>
                `;
                progressContainer.classList.remove('hidden');

                // Show complete button if on last step or generally in progress (simplified for demo)
                completeBtn.classList.remove('hidden');
            } else {
                progressContainer.classList.add('hidden');
                completeBtn.classList.add('hidden');
            }

            // Show Overlay and Modal
            document.getElementById('modal-overlay').classList.remove('hidden');
            document.getElementById('batch-detail-modal').classList.remove('hidden');
        }

        function completeStep(stepId) {
            alert('Step ' + stepId + ' marked complete! (In real app, this sends AJAX request)');
            // In real app: Update JS object, re-render modal content
            const batch = batches.find(b => b.id === currentBatchId);
            const step = batch.steps.find(s => s.id === stepId);
            step.completed = true;
            step.inProgress = false;
            // Mock moving to next
            const next = batch.steps.find(s => s.id === stepId + 1);
            if(next) next.inProgress = true;

            openDetailModal(currentBatchId); // Re-render
        }

        function openQualityModal() {
            document.getElementById('batch-detail-modal').classList.add('hidden');
            document.getElementById('quality-check-modal').classList.remove('hidden');

            const batch = batches.find(b => b.id === currentBatchId);
            document.getElementById('quality-unit').innerText = batch.unit;

            // Input listener for waste
            const input = document.getElementById('quality-actual-input');
            input.value = '';
            input.onkeyup = function() {
                const val = parseFloat(this.value);
                const wasteContainer = document.getElementById('quality-waste-container');
                const wasteSpan = document.getElementById('waste-amount');

                if(val < batch.quantity) {
                    wasteContainer.classList.remove('hidden');
                    wasteSpan.innerText = (batch.quantity - val) + ' ' + batch.unit;
                } else {
                    wasteContainer.classList.add('hidden');
                }
            };
        }

        function submitCompletion() {
            const val = document.getElementById('quality-actual-input').value;
            if(!val) return alert('Please enter actual output');

            alert('Batch Completed! Output: ' + val + '. Data saved.');
            closeAllModals();
            // In real app, reload page or remove card via JS
            location.reload(); 
        }

        function closeAllModals() {
            document.getElementById('modal-overlay').classList.add('hidden');
            document.getElementById('batch-detail-modal').classList.add('hidden');
            document.getElementById('quality-check-modal').classList.add('hidden');
        }

        function startBatch(id) {
            alert('Batch ' + id + ' Started!');
            location.reload(); // Simulate status change
        }
    </script>

@endsection