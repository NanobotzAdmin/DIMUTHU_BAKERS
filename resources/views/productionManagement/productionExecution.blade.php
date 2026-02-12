@extends('layouts.app')
@section('title', 'Production Management')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 p-4 md:p-6">

        {{-- HEADER (Same as before) --}}
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="bi bi-egg-fried text-white text-3xl"></i>
                    </div>
                    <div class="relative">
                        <p class="text-sm text-gray-500 mb-1">Production Section</p>
                        <button onclick="toggleSectionDropdown()" class="flex items-center gap-2 cursor-pointer group">
                            <h1 class="text-3xl text-gray-900 font-bold" id="current-section-name">Kitchen</h1>
                            <i id="section-chevron"
                                class="bi bi-chevron-down text-xl text-gray-400 group-hover:text-gray-600 transition-transform"></i>
                        </button>
                        <p class="text-gray-600 mt-1">Today's schedule - {{ date('m/d/Y') }}</p>
                        <div id="section-dropdown"
                            class="hidden absolute left-0 top-full mt-2 bg-white rounded-xl shadow-2xl border-2 border-gray-200 py-2 min-w-[200px] z-20">
                        </div>
                    </div>
                </div>
                {{-- Quick Stats --}}
                <div class="flex gap-3 overflow-x-auto pb-2 md:pb-0">
                    <div class="bg-white rounded-xl p-4 shadow-sm min-w-[120px]">
                        <div class="flex items-center gap-2 mb-1"><i class="bi bi-clock text-gray-500 text-xl"></i><span
                                class="text-2xl text-gray-900" id="stat-pending">0</span></div>
                        <div class="text-sm text-gray-600">Pending</div>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm min-w-[120px]">
                        <div class="flex items-center gap-2 mb-1"><i
                                class="bi bi-play-circle-fill text-blue-500 text-xl"></i><span
                                class="text-2xl text-gray-900" id="stat-inprogress">0</span></div>
                        <div class="text-sm text-gray-600">In Progress</div>
                    </div>
                    <div class="bg-white rounded-xl p-4 shadow-sm min-w-[120px]">
                        <div class="flex items-center gap-2 mb-1"><i
                                class="bi bi-check-circle-fill text-green-500 text-xl"></i><span
                                class="text-2xl text-gray-900" id="stat-completed">0</span></div>
                        <div class="text-sm text-gray-600">Completed</div>
                    </div>
                </div>
            </div>
            {{-- Filter Tabs --}}
            <div class="flex gap-2 overflow-x-auto" id="filter-tabs-container"></div>
        </div>

        {{-- BATCH LIST CONTAINER --}}
        <div id="batch-list-container" class="space-y-4"></div>

    </div>

    {{-- MODALS --}}
    <div id="modal-detail" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModals()"></div>
        <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto z-50 p-6 shadow-2xl relative">
            <button onclick="closeModals()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
            <div id="modal-detail-content" class="space-y-5">
            </div>
        </div>
    </div>

    @include('productionManagement.components.planner.modals.qualityCheckModal')

    {{-- SCRIPTS --}}
    <script>
        const state = {
            batches: @json($batches),
            departments: @json($departments),
            selectedSection: @json(count($departments) > 0 ? $departments[0] : 'Kitchen'),
            filterStatus: 'all',
            selectedBatchId: null,
            form: { actualOutput: '', wasteReason: '', notes: '' }
        };

        document.addEventListener('DOMContentLoaded', renderAll);

        // --- Render Core Functions (Header, Stats, List) ---
        function renderAll() {
            renderHeader();
            renderStats();
            renderFilters();
            renderBatches();
        }

        function renderHeader() {
            const sections = state.departments && state.departments.length > 0 ? state.departments : ['Kitchen'];
            const dropdown = document.getElementById('section-dropdown');
            dropdown.innerHTML = sections.map(sec => `
                                                                                            <button onclick="setSection('${sec}')" class="w-full text-left px-5 py-3 text-xl transition-colors ${sec === state.selectedSection ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold' : 'hover:bg-gray-100 text-gray-700'}">${sec}</button>
                                                                                        `).join('');
            document.getElementById('current-section-name').textContent = state.selectedSection;
        }

        function renderStats() {
            const sectionBatches = state.batches.filter(b => b.section === state.selectedSection);
            document.getElementById('stat-pending').innerText = sectionBatches.filter(b => b.status === 'pending').length;
            document.getElementById('stat-inprogress').innerText = sectionBatches.filter(b => b.status === 'in-progress').length;
            document.getElementById('stat-completed').innerText = sectionBatches.filter(b => b.status === 'completed').length;
        }

        function renderFilters() {
            const statuses = ['all', 'pending', 'in-progress', 'completed'];
            const container = document.getElementById('filter-tabs-container');
            const sectionBatches = state.batches.filter(b => b.section === state.selectedSection);

            container.innerHTML = statuses.map(status => {
                const isActive = state.filterStatus === status;
                const count = status === 'all' ? sectionBatches.length : sectionBatches.filter(b => b.status === status).length;
                const icon = status === 'pending' ? 'bi-clock' : status === 'in-progress' ? 'bi-play-fill' : status === 'completed' ? 'bi-check-circle-fill' : 'bi-list-check';
                return `
                                                                                                <button onclick="setFilter('${status}')" class="flex-1 h-14 rounded-xl flex items-center justify-center gap-2 transition-all min-w-[140px] ${isActive ? 'bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-50'}">
                                                                                                    <i class="bi ${icon} text-lg"></i>
                                                                                                    <span class="font-medium capitalize">${status === 'in-progress' ? 'In Progress' : status === 'all' ? 'All Batches' : status}</span>
                                                                                                    <span class="px-2 py-0.5 rounded-full text-xs ${isActive ? 'bg-white/20 text-white' : 'bg-gray-200 text-gray-700'}">${count}</span>
                                                                                                </button>`;
            }).join('');
        }

        function renderBatches() {
            const container = document.getElementById('batch-list-container');
            let filtered = state.batches.filter(b => b.section === state.selectedSection);
            if (state.filterStatus !== 'all') filtered = filtered.filter(b => b.status === state.filterStatus);

            if (filtered.length === 0) {
                container.innerHTML = `<div class="bg-white rounded-2xl p-12 text-center"><i class="bi bi-list-check text-4xl text-gray-400"></i><h3 class="text-xl text-gray-900 mt-4">No Batches</h3></div>`;
                return;
            }

            container.innerHTML = filtered.map((batch, idx) => {
                const progress = batch.totalSteps > 0 ? Math.round((batch.currentStep / batch.totalSteps) * 100) : 0;

                // Simple list view of ingredients
                const ingHtml = batch.ingredients.map(ing => `
                                                                                                <span class="text-xs px-2 py-1 rounded-md flex items-center gap-1 ${ing.status === 'ok' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                                                                                                    ${ing.name} ${ing.status === 'ok' ? '✓' : ''}
                                                                                                </span>`).join('');

                let buttons = `<button onclick="openDetailModal('${batch.id}')" class="h-12 px-6 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all"><i class="bi bi-file-text text-lg"></i> View Details</button>`;
                if (batch.status === 'pending') buttons = `<button onclick="startBatch(${batch.id})" class="h-12 px-6 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all"><i class="bi bi-play-fill text-lg"></i> Start Batch</button>` + buttons;
                else if (batch.status === 'in-progress') buttons = `<button onclick="openDetailModal(${batch.id})" class="h-12 px-6 bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all animate-pulse"><i class="bi bi-eye text-lg"></i> Continue Working</button>` + buttons;

                return `
                                                                                            <div class="bg-white rounded-2xl p-5 shadow-sm border-2 transition-all ${batch.status === 'in-progress' ? 'border-blue-300 shadow-lg' : (batch.isWasteProcessing ? 'border-green-200 bg-gradient-to-br from-white to-green-50' : 'border-gray-100')}">
                                                                                                <div class="flex items-start gap-4">
                                                                                                    <div class="flex flex-col items-center gap-2">
                                                                                                        <div class="w-16 h-16 rounded-xl flex items-center justify-center shadow-md ${batch.status === 'completed' ? 'bg-green-500' : batch.status === 'in-progress' ? 'bg-blue-500' : 'bg-gray-400'}">
                                                                                                            <span class="text-2xl font-bold text-white">${idx + 1}</span>
                                                                                                        </div>
                                                                                                        <div class="text-4xl">${batch.recipeIcon}</div>
                                                                                                    </div>
                                                                                                    <div class="flex-1">
                                                                                                        <div class="flex flex-col md:flex-row md:items-start justify-between mb-3">
                                                                                                            <div>
                                                                                                                <h3 class="text-2xl text-gray-900 mb-2 flex items-center gap-2 flex-wrap">
                                                                                                                    ${batch.recipeName}
                                                                                                                    ${batch.isWasteProcessing ? '<span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1"><i class="bi bi-recycle"></i> WASTE RECOVERY</span>' : ''}
                                                                                                                    <span class="text-xs px-2 py-1 rounded-full border ${getPriorityClass(batch.priority)}">${batch.priority.toUpperCase()}</span>
                                                                                                                </h3>
                                                                                                                <div class="flex flex-wrap items-center gap-4 text-gray-600">
                                                                                                                    <div class="flex items-center gap-2"><i class="bi bi-clock"></i> <span>${batch.scheduledTime}</span></div>
                                                                                                                    <div class="flex items-center gap-2"><i class="bi bi-box-seam"></i> <span>${batch.quantity} ${batch.unit}</span></div>
                                                                                                                    <div class="flex items-center gap-2"><i class="bi bi-people"></i> <span>${batch.assignedTo}</span></div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="flex items-center gap-2 mt-2 md:mt-0">
                                                                                                                <div class="w-4 h-4 rounded-full shadow-lg ${getStatusColor(batch.status)}"></div>
                                                                                                                <span class="font-medium text-gray-700 capitalize">${batch.status.replace('-', ' ')}</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        ${batch.status !== 'pending' ? `<div class="mb-3"><div class="flex justify-between mb-1"><span class="text-xs">Progress</span><span class="text-xs font-bold">${progress}%</span></div><div class="h-2 w-full bg-gray-200 rounded-full"><div class="h-full bg-blue-600" style="width:${progress}%"></div></div></div>` : ''}
                                                                                                        <div class="flex items-center gap-2 flex-wrap mb-3">${ingHtml}</div>
                                                                                                        <div class="flex gap-3 mt-4">${buttons}</div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>`;
            }).join('');
        }

        // --- 5. DETAILED MODAL LOGIC (Matches React Snippet) ---

        function openDetailModal(id) {
            state.selectedBatchId = id;
            const batch = state.batches.find(b => b.id == id);
            const content = document.getElementById('modal-detail-content');

            // 1. Waste Processing Banner Logic
            let wasteBannerHtml = '';
            if (batch.isWasteProcessing) {
                wasteBannerHtml = `
                                                                                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-5 text-white shadow-lg">
                                                                                                <div class="flex items-start gap-3">
                                                                                                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                                                                                        <i class="bi bi-recycle text-2xl"></i>
                                                                                                    </div>
                                                                                                    <div class="flex-1">
                                                                                                        <h3 class="flex items-center gap-2 mb-2 font-bold">
                                                                                                            <i class="bi bi-leaf"></i> Waste Processing Recipe
                                                                                                        </h3>
                                                                                                        <p class="text-sm text-green-50 mb-3">
                                                                                                            This batch converts waste/byproducts into valuable products, supporting our Three-Stage Waste Recovery System.
                                                                                                        </p>
                                                                                                        <div class="flex items-center gap-2 text-xs bg-white/10 rounded-lg px-3 py-2 backdrop-blur-sm">
                                                                                                            <i class="bi bi-graph-down-arrow"></i>
                                                                                                            <span>Reducing waste and production costs through recovery</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>`;
            }

            // 2. Batch Progress Logic
            let progressHtml = '';
            if (batch.status !== 'pending') {
                const pct = ((batch.currentStep / batch.totalSteps) * 100).toFixed(0);
                progressHtml = `
                                                                                            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-4 border-2 border-blue-200">
                                                                                                <div class="flex items-center justify-between mb-2">
                                                                                                    <div>
                                                                                                        <h3 class="text-base text-gray-900 mb-1">Overall Progress</h3>
                                                                                                        <p class="text-xs text-gray-600">Step ${batch.currentStep} of ${batch.totalSteps} completed</p>
                                                                                                    </div>
                                                                                                    <div class="text-2xl font-bold text-blue-600">${pct}%</div>
                                                                                                </div>
                                                                                                <div class="h-3 w-full bg-blue-200 rounded-full overflow-hidden">
                                                                                                    <div class="h-full bg-blue-600 transition-all" style="width: ${pct}%"></div>
                                                                                                </div>
                                                                                            </div>`;
            }

            // 3. Ingredients Checklist Logic (Detailed)
            const ingredientsHtml = batch.ingredients.map(ing => {
                const isWasteInput = ing.name.includes('Waste') || ing.name.includes('Trimmings') || ing.name.includes('Scraps');
                const cardClass = ing.status === 'ok'
                    ? (isWasteInput ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300' : 'bg-green-50 border-green-200')
                    : 'bg-red-50 border-red-200';

                return `
                                                                                            <div class="p-3 rounded-xl border-2 ${cardClass}">
                                                                                                <div class="flex items-center gap-2 mb-1">
                                                                                                    ${ing.status === 'ok' ? '<i class="bi bi-check-circle-fill text-green-600"></i>' : '<i class="bi bi-exclamation-circle-fill text-red-600"></i>'}
                                                                                                    <span class="font-medium text-sm text-gray-900 flex items-center gap-1.5">
                                                                                                        ${isWasteInput ? '<i class="bi bi-leaf-fill text-green-600"></i>' : ''}
                                                                                                        ${ing.name}
                                                                                                    </span>
                                                                                                </div>
                                                                                                <div class="text-xs text-gray-700">Need: <span class="font-medium">${ing.needed} ${ing.unit}</span></div>
                                                                                                <div class="text-xs ${ing.status === 'ok' ? 'text-green-700' : 'text-red-700'}">Available: <span class="font-medium">${ing.available} ${ing.unit}</span></div>
                                                                                                ${isWasteInput ? '<div class="text-xs text-green-600 font-medium mt-1 flex items-center gap-1"><i class="bi bi-graph-down-arrow"></i> Rs. 0 cost</div>' : ''}
                                                                                            </div>`;
            }).join('');

            // 4. Production Steps Logic (Detailed Badges)
            const stepsHtml = batch.steps ? batch.steps.map((step, idx) => {
                const isActive = step.inProgress;
                const isCompleted = step.completed;
                const isPending = !isActive && !isCompleted;

                // Status Icons & Classes
                let stepIcon = '<div class="w-8 h-8 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-400 font-bold">' + (idx + 1) + '</div>';
                let stepClass = 'border-gray-100 bg-white opacity-60';

                // Timing Info
                let timeInfo = '';
                if (isCompleted) {
                    timeInfo = `<div class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                                                                    <span>Start: ${step.startTime}</span>
                                                                                    <span>End: ${step.endTime}</span>
                                                                               </div>`;
                } else if (isActive) {
                    timeInfo = `<div class="text-xs text-blue-600 mt-1 flex items-center gap-2">
                                                                            <span>Started: ${step.startTime || 'Just Now'}</span>
                                                                       </div>
                                                                       <div class="flex items-center gap-2 mt-2"><i class="bi bi-hourglass-split text-blue-600 animate-spin"></i> <span class="text-xs text-blue-600 font-medium">In progress...</span></div>`;
                }


                if (isCompleted) {
                    stepIcon = '<div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center shadow-md"><i class="bi bi-check-lg"></i></div>';
                    stepClass = 'border-green-200 bg-green-50/50';
                } else if (isActive) {
                    stepIcon = '<div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md animate-pulse font-bold">' + (idx + 1) + '</div>';
                    stepClass = 'border-blue-200 bg-white shadow-md transform scale-[1.02] transition-all';
                }

                // Action Button
                let actionBtn = '';
                if (isActive) {
                    actionBtn = `
                                                                                <button onclick="completeStep(${step.id})" class="text-sm px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-1 transition-all shadow-sm">
                                                                                    <span>Complete</span> <i class="bi bi-arrow-right"></i>
                                                                                </button>`;
                }

                return `
                                                                                <div class="flex items-center gap-4 p-3 rounded-xl border-2 ${stepClass}">
                                                                                    <div class="flex-shrink-0">${stepIcon}</div>
                                                                                    <div class="flex-1">
                                                                                        <h4 class="font-medium text-gray-900 ${isCompleted ? 'line-through text-gray-500' : ''}">${step.name}</h4>
                                                                                        <div class="flex items-center gap-3 text-xs text-gray-500 mt-0.5">
                                                                                            <span><i class="bi bi-clock"></i> ${step.duration}m</span>
                                                                                            <span><i class="bi bi-tag"></i> ${step.type}</span>
                                                                                        </div>
                                                                                        ${timeInfo}
                                                                                    </div>
                                                                                    <div>${actionBtn}</div>
                                                                                </div>`;
            }).join('') : '';

            // 5. Complete Batch / Start Batch Button
            let completeBtn = '';

            // Check if final step is completed
            const lastStep = batch.steps && batch.steps.length > 0 ? batch.steps[batch.steps.length - 1] : null;
            const isLastStepCompleted = lastStep && lastStep.completed;

            if (batch.status === 'completed') {
                completeBtn = `
                                                                <div class="flex gap-3 pt-4 border-t-2 border-gray-200">
                                                                    <div class="flex-1 h-14 bg-gray-100 text-gray-500 rounded-xl flex items-center justify-center gap-3 font-medium">
                                                                        <i class="bi bi-check-all text-xl"></i> Production Completed
                                                                    </div>
                                                                </div>`;
            } else if (batch.status === 'in-progress' && isLastStepCompleted) {
                completeBtn = `
                                                                <div id="modal-complete-btn-container" class="pt-4 border-t-2 border-gray-200">
                                                                    <button onclick="openQualityModal()" class="w-full h-14 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl flex items-center justify-center gap-3 font-medium shadow-lg transition-all">
                                                                        ✅ Complete Batch & Quality Check
                                                                    </button>
                                                                </div>`;
            } else if (batch.status === 'pending') {
                completeBtn = `
                                                                <div class="flex gap-3 pt-4 border-t-2 border-gray-200">
                                                                    <button onclick="confirmStartBatch(${batch.id})" class="flex-1 h-14 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 text-white rounded-xl flex items-center justify-center gap-3 font-medium shadow-lg transition-all">
                                                                        <i class="bi bi-play-fill text-xl"></i> Start Batch
                                                                    </button>
                                                                </div>`;
            }


            // Assemble Final HTML
            content.innerHTML = `
                                                                            {{-- Header --}}
                                                                            <div class="mb-0">
                                                                                <div class="flex items-center gap-3 mb-1">
                                                                                    <span class="text-4xl">${batch.recipeIcon}</span>
                                                                                    <h2 class="text-2xl font-bold">${batch.recipeName}</h2>
                                                                                    <span class="text-xs px-2 py-1 rounded-full border ${getPriorityClass(batch.priority)}">${batch.priority.toUpperCase()}</span>
                                                                                </div>
                                                                                <p class="text-sm text-gray-600">Batch ${batch.id} • ${batch.quantity} ${batch.unit} • ${batch.section}</p>
                                                                            </div>

                                                                            ${wasteBannerHtml}
                                                                            ${progressHtml}

                                                                            {{-- Ingredients --}}
                                                                            <div>
                                                                                <h3 class="text-lg text-gray-900 mb-3 flex items-center gap-2">
                                                                                    ${batch.isWasteProcessing ? '<i class="bi bi-recycle text-green-600"></i> Waste/Byproduct Inputs' : '<i class="bi bi-box-seam text-amber-600"></i> Ingredients Required'}
                                                                                </h3>
                                                                                ${batch.isWasteProcessing ? '<div class="mb-3 flex items-start gap-2 text-sm text-green-700 bg-green-50 rounded-lg p-3 border border-green-200"><i class="bi bi-leaf"></i> <span><strong>Zero-cost inputs:</strong> Waste materials listed below have no purchase cost</span></div>' : ''}
                                                                                <div class="grid grid-cols-2 gap-2">${ingredientsHtml}</div>
                                                                            </div>

                                                                            {{-- Steps --}}
                                                                            <div>
                                                                                <h3 class="text-lg text-gray-900 mb-3 flex items-center gap-2"><i class="bi bi-list-check text-amber-600"></i> Production Steps</h3>
                                                                                <div class="space-y-2">${stepsHtml}</div>
                                                                            </div>

                                                                            ${completeBtn}
                                                                        `;
            document.getElementById('modal-detail').classList.remove('hidden');
        }

        // --- Interactive Logic ---
        function toggleSectionDropdown() {
            document.getElementById('section-dropdown').classList.toggle('hidden');
            document.getElementById('section-chevron').classList.toggle('rotate-180');
        }

        function setSection(section) {
            state.selectedSection = section;
            toggleSectionDropdown();
            renderAll();
        }

        function setFilter(status) {
            state.filterStatus = status;
            renderFilters();
            renderBatches();
        }

        // This is called when "Start Batch" is clicked on the CARD.
        // It now just opens the detail modal where the actual start button resides.
        function startBatch(id) {
            openDetailModal(id);
        }

        // This is called when "Start Batch" is clicked INSIDE the modal.
        function confirmStartBatch(id) {
            const batch = state.batches.find(b => b.id == id);
            if (!batch) return;

            // AJAX call to start batch
            fetch('{{ route("production.startBatch") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ schedule_id: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Batch Started!',
                            text: 'Production has officially begun.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Start',
                            text: data.message || 'Unknown error occurred.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'System Error',
                        text: 'Could not connect to the server.'
                    });
                });
        }

        function completeStep(stepId) {
            const batch = state.batches.find(b => b.id == state.selectedBatchId);
            if (!batch) return;

            fetch('{{ route("production.completeStep") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    schedule_id: batch.id,
                    instruction_id: stepId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Step Completed',
                            text: 'Moving to the next step...',
                            timer: 1000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Action Failed',
                            text: data.message || 'Could not complete step.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'System Error',
                        text: 'An unexpected error occurred.'
                    });
                });
        }

        // --- 6. QUALITY MODAL LOGIC NEW ---

        function openQualityModal() {
            const batch = state.batches.find(b => b.id === state.selectedBatchId);
            if (!batch) return;

            // hide detail modal
            document.getElementById('modal-detail').classList.add('hidden');

            // Populate Header & Summary
            document.getElementById('qc-recipe-icon').innerText = batch.recipeIcon;
            document.getElementById('qc-recipe-name').innerText = batch.recipeName;
            document.getElementById('qc-batch-id').innerText = batch.id;
            document.getElementById('qc-target-qty').innerText = batch.quantity;
            document.getElementById('qc-target-unit').innerText = batch.unit;
            document.getElementById('qc-output-unit').innerText = batch.unit;
            document.getElementById('qc-preset-all').innerText = batch.quantity;
            document.getElementById('qc-waste-unit').innerText = batch.unit;

            // Reset Form State
            document.getElementById('qc-actual-output').value = '';
            document.getElementById('qc-notes').value = '';
            document.getElementById('qc-waste-reason-val').value = '';
            state.form.actualOutput = '';
            state.form.wasteReason = '';

            // Reset Waste Buttons
            document.querySelectorAll('.waste-reason-btn').forEach(btn => {
                btn.classList.remove('border-red-500', 'bg-red-50', 'text-red-900', 'ring-2', 'ring-red-200');
                btn.classList.add('border-gray-200', 'text-gray-600', 'bg-white');
            });

            // Handle Byproducts (Mock logic)
            const byproductSection = document.getElementById('qc-byproduct-section');
            const byproductList = document.getElementById('qc-byproduct-list');

            if (batch.isWasteProcessing && batch.byproducts && batch.byproducts.length > 0) {
                const products = batch.byproducts || [];
                byproductList.innerHTML = products.map((bp, idx) => `
                                                                                            <div class="bg-white border-2 border-green-200 rounded-xl p-4">
                                                                                                <div class="flex items-center gap-2 mb-3">
                                                                                                    <i class="bi bi-speedometer2 text-green-600"></i>
                                                                                                    <span class="font-bold text-gray-900">${bp.name}</span>
                                                                                                </div>
                                                                                                <div class="grid grid-cols-3 gap-3">
                                                                                                    <div>
                                                                                                        <label class="block text-xs font-bold text-green-700 mb-1">Expected</label>
                                                                                                        <div class="bg-green-100 rounded-lg px-3 py-2 text-center font-bold text-green-900">${bp.expected} ${bp.unit}</div>
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <label class="block text-xs font-bold text-green-700 mb-1">Actual *</label>
                                                                                                        <input type="number" step="0.01" class="w-full h-10 px-3 text-center rounded-lg border-2 border-green-300 focus:border-green-500 outline-none font-bold" placeholder="0.0">
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <label class="block text-xs font-bold text-green-700 mb-1">Variance</label>
                                                                                                        <div class="bg-gray-100 rounded-lg px-3 py-2 text-center text-xs text-gray-500">-</div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        `).join('');
                byproductSection.classList.remove('hidden');
            } else {
                byproductSection.classList.add('hidden');
            }

            checkVariance();
            document.getElementById('quality-check-modal').classList.remove('hidden');
        }

        // --- QC Helper Functions ---

        function setActualFromTarget(diff) {
            const batch = state.batches.find(b => b.id === state.selectedBatchId);
            const val = Math.max(0, batch.quantity - diff);
            document.getElementById('qc-actual-output').value = val;
            state.form.actualOutput = val;
            checkVariance();
            validateCompletion();
        }

        function checkVariance() {
            const batch = state.batches.find(b => b.id === state.selectedBatchId);
            const actual = parseFloat(state.form.actualOutput);
            const wasteSection = document.getElementById('qc-waste-section');
            const wasteAmountSpan = document.getElementById('qc-waste-amount');

            if (!isNaN(actual) && actual < batch.quantity) {
                const waste = batch.quantity - actual;
                wasteAmountSpan.innerText = waste;
                wasteSection.classList.remove('hidden');
            } else {
                wasteSection.classList.add('hidden');
                state.form.wasteReason = '';
                document.getElementById('qc-waste-reason-val').value = '';
                document.querySelectorAll('.waste-reason-btn').forEach(btn => {
                    btn.classList.remove('border-red-500', 'bg-red-50', 'text-red-900', 'ring-2', 'ring-red-200');
                    btn.classList.add('border-gray-200', 'text-gray-600', 'bg-white');
                });
            }
        }

        function selectWasteReason(btn, reason) {
            document.querySelectorAll('.waste-reason-btn').forEach(b => {
                b.classList.remove('border-red-500', 'bg-red-50', 'text-red-900', 'ring-2', 'ring-red-200');
                b.classList.add('border-gray-200', 'text-gray-600', 'bg-white');
            });
            btn.classList.remove('border-gray-200', 'text-gray-600', 'bg-white');
            btn.classList.add('border-red-500', 'bg-red-50', 'text-red-900', 'ring-2', 'ring-red-200');

            state.form.wasteReason = reason;
            document.getElementById('qc-waste-reason-val').value = reason;
            validateCompletion();
        }

        function validateCompletion() {
            const btn = document.getElementById('qc-submit-btn');
            const batch = state.batches.find(b => b.id === state.selectedBatchId);
            const actual = parseFloat(state.form.actualOutput);
            const hasWaste = !isNaN(actual) && actual < batch.quantity;
            const wasteReason = state.form.wasteReason;

            if (state.form.actualOutput === '' || isNaN(actual)) {
                btn.disabled = true;
                return;
            }
            if (hasWaste && !wasteReason) {
                btn.disabled = true;
                return;
            }
            btn.disabled = false;
        }

        function handleSubmitCompletion() {
            const batch = state.batches.find(b => b.id === state.selectedBatchId);

            fetch('{{ route("production.completeBatch") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    schedule_id: batch.id,
                    actual_output: state.form.actualOutput,
                    waste_reason: state.form.wasteReason || '',
                    notes: document.getElementById('qc-notes').value || ''
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Batch Completed!',
                            text: 'Quality check recorded and batch finalized.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Complete',
                            text: data.message || 'Could not finalize batch.'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'System Error',
                        text: 'Could not connect to the server.'
                    });
                });
        }

        function closeModals() {
            document.getElementById('modal-detail').classList.add('hidden');
            const qc = document.getElementById('quality-check-modal');
            if (qc) qc.classList.add('hidden');
            const mq = document.getElementById('modal-quality');
            if (mq) mq.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const qcInput = document.getElementById('qc-actual-output');
            if (qcInput) {
                qcInput.addEventListener('input', (e) => {
                    state.form.actualOutput = e.target.value;
                    checkVariance();
                    validateCompletion();
                });
            }
        });

        function getPriorityClass(p) { return p === 'high' ? 'bg-red-100 text-red-700 border-red-300' : p === 'medium' ? 'bg-orange-100 text-orange-700 border-orange-300' : 'bg-blue-100 text-blue-700 border-blue-300'; }
        function getStatusColor(s) { return s === 'completed' ? 'bg-green-500' : s === 'in-progress' ? 'bg-blue-500' : 'bg-gray-400'; }

    </script>
@endsection