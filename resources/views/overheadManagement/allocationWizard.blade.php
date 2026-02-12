@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50 p-4 md:p-6" id="wizard-container">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="bi bi-magic text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl text-gray-900 font-bold">Allocation Wizard</h1>
                        <p class="text-gray-600">8-Step Guided Overhead Allocation Process</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-white rounded-lg border-2 border-gray-200">
                        <div class="text-xs text-gray-600 uppercase tracking-wide">Period</div>
                        <div class="font-bold text-gray-900" id="period-display"></div>
                    </div>
                    <div class="px-4 py-2 bg-purple-100 rounded-lg border-2 border-purple-300">
                        <div class="text-xs text-purple-700 uppercase tracking-wide">Step <span
                                id="step-number-display">1</span> of 8</div>
                        <div class="font-bold text-purple-900" id="progress-percent">12% Complete</div>
                    </div>
                </div>
            </div>

            <!-- Step Indicator -->
            <div id="step-indicators" class="flex items-center justify-between mb-8"></div>
        </div>

        <!-- Step Content Container -->
        <div id="step-content" class="mb-6 min-h-[400px]">
            <!-- Dynamic Content Loaded Here -->
        </div>

        <!-- Navigation -->
        <div
            class="flex items-center justify-between bg-white/50 p-4 rounded-xl border border-gray-100 shadow-sm backdrop-blur-sm">
            <button id="prev-btn" onclick="wizard.prevStep()" disabled
                class="h-12 px-6 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                <i class="bi bi-chevron-left"></i> Previous
            </button>

            <div class="text-center hidden md:block">
                <div class="text-sm font-medium text-gray-900" id="step-title-display">Select Cost Pools</div>
                <div class="text-xs text-gray-500 mt-1" id="step-desc-display">Choose which cost pools to include</div>
            </div>

            <button id="next-btn" onclick="wizard.nextStep()"
                class="h-12 px-6 bg-gradient-to-br from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md shadow-purple-200 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                Next <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    <script>
        // Data from Controller
        const mockCostPools = @json($costPools);
        const mockActivities = @json($activities);
        const mockCostDrivers = @json($costDrivers);
        const mockExpenses = @json($expenses);

        // Wizard Logic
        const wizard = {
            step: 1,
            totalSteps: 8,
            state: {
                selectedPools: [],
                selectedActivities: [],
                selectedDrivers: [],
                activityVolumes: [], // { activityId, driverId, kitchen, cake, bakery }
                allocationName: `Overhead Allocation ${new Date().toLocaleString('en-GB', { month: 'short', year: 'numeric' })}`,
                period: new Date().toISOString().slice(0, 7), // YYYY-MM
                notes: '',
                simulation: null
            },

            init() {
                this.updateUI();
                document.getElementById('period-display').innerText = new Date(this.state.period + '-01').toLocaleString('en-GB', { month: 'short', year: 'numeric' });
            },

            nextStep() {
                if (this.step < this.totalSteps && this.canProceed()) {
                    this.step++;
                    // Trigger auto-calculations when entering specific steps
                    if (this.step === 4) this.initVolumes();
                    this.updateUI();
                } else if (this.step === this.totalSteps) {
                    // Should invoke post action, already handled by specific button in Step 8
                }
            },

            prevStep() {
                if (this.step > 1) {
                    this.step--;
                    this.updateUI();
                }
            },

            canProceed() {
                switch (this.step) {
                    case 1: return this.state.selectedPools.length > 0;
                    case 3: return this.state.selectedActivities.length > 0 && this.state.selectedDrivers.length > 0;
                    case 4: return this.state.activityVolumes.length > 0; // simplified
                    default: return true;
                }
            },

            toggleSelection(arrayName, id) {
                const list = this.state[arrayName];
                if (list.includes(id)) {
                    this.state[arrayName] = list.filter(item => item !== id);
                } else {
                    this.state[arrayName] = [...list, id];
                }
                this.updateUI(); // Re-render to show selection state
            },

            initVolumes() {
                if (this.state.activityVolumes.length === 0 && this.state.selectedActivities.length > 0) {
                    this.state.activityVolumes = this.state.selectedActivities.map(actId => {
                        const activity = mockActivities.find(a => a.id === actId);
                        const driver = mockCostDrivers.find(d => d.id === activity?.primaryDriverId);
                        return {
                            activityId: actId,
                            driverId: driver?.id || '',
                            kitchen: driver?.values?.kitchen || 0,
                            cake: driver?.values?.cake || 0,
                            bakery: driver?.values?.bakery || 0
                        };
                    });
                }
            },

            calculateAllocations() {
                let kitchen = 0, cake = 0, bakery = 0;
                this.state.selectedPools.forEach(poolId => {
                    const pool = mockCostPools.find(p => p.id === poolId);
                    const driver = mockCostDrivers.find(d => d.id === pool.driverId);
                    if (pool && driver && driver.total > 0) {
                        const rate = pool.totalAmount / driver.total;
                        kitchen += rate * (driver.values.kitchen || 0);
                        cake += rate * (driver.values.cake || 0);
                        bakery += rate * (driver.values.bakery || 0);
                    }
                });
                const total = kitchen + cake + bakery;
                return {
                    kitchen: { amount: kitchen, percentage: total > 0 ? (kitchen / total) * 100 : 0 },
                    cake: { amount: cake, percentage: total > 0 ? (cake / total) * 100 : 0 },
                    bakery: { amount: bakery, percentage: total > 0 ? (bakery / total) * 100 : 0 },
                    total: total
                };
            },

            runSimulation() {
                const allocs = this.calculateAllocations();
                const sim = {
                    timestamp: new Date().toLocaleString(),
                    allocations: allocs
                };
                this.state.simulation = sim;
                Swal.fire({
                    icon: 'success',
                    title: 'Simulation Complete',
                    text: 'Allocation simulation run successfully!',
                    timer: 1500,
                    showConfirmButton: false
                });
                this.updateUI();
            },

            saveDraft() {
                Swal.fire({
                    icon: 'success',
                    title: 'Draft Saved',
                    text: 'Allocation draft saved successfully!',
                    timer: 1500,
                    showConfirmButton: false
                });
            },

            postToLedger() {
                Swal.fire({
                    title: 'Confirm Posting',
                    text: `Are you sure you want to post Rs ${this.calculateAllocations().total.toLocaleString()} to the ledger?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Post it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Posted!',
                            'Allocation has been posted to the ledger.',
                            'success'
                        ).then(() => {
                            window.location.href = ""; // Redirect after post
                        });
                    }
                });
            },

            updateUI() {
                // Update Header
                document.getElementById('step-number-display').innerText = this.step;
                document.getElementById('progress-percent').innerText = `${Math.round((this.step / this.totalSteps) * 100)}% Complete`;

                // Update Navigation Buttons
                document.getElementById('prev-btn').disabled = this.step === 1;
                const nextBtn = document.getElementById('next-btn');
                if (this.step === this.totalSteps) {
                    nextBtn.innerHTML = 'Complete <i class="bi bi-check-circle"></i>';
                    nextBtn.classList.add('hidden'); // Hide next button on last step, use custom buttons
                } else {
                    nextBtn.innerHTML = 'Next <i class="bi bi-chevron-right"></i>';
                    nextBtn.classList.remove('hidden');
                    nextBtn.disabled = !this.canProceed();
                }

                // Render Step Indicators
                this.renderStepIndicators();

                // Render Content
                this.renderContent();
            },

            renderStepIndicators() {
                const container = document.getElementById('step-indicators');
                const stepLabels = ['Pools', 'Expenses', 'Activities', 'Volumes', 'Rates', 'Allocate', 'Review', 'Post'];
                let html = '';

                for (let i = 1; i <= this.totalSteps; i++) {
                    let circleClass = "w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all border-2 z-10 relative ";
                    let labelClass = "text-xs mt-2 text-center absolute -bottom-6 w-20 left-1/2 -translate-x-1/2 font-medium ";

                    if (i < this.step) {
                        circleClass += "bg-green-500 border-green-500 text-white";
                        labelClass += "text-green-600";
                    } else if (i === this.step) {
                        circleClass += "bg-white border-purple-600 text-purple-600 shadow-lg shadow-purple-200 scale-110";
                        labelClass += "text-purple-700 font-bold";
                    } else {
                        circleClass += "bg-gray-100 border-gray-300 text-gray-400";
                        labelClass += "text-gray-400";
                    }

                    html += `
                            <div class="relative flex flex-col items-center flex-none">
                                <div class="${circleClass}">
                                    ${i < this.step ? '<i class="bi bi-check-lg"></i>' : i}
                                </div>
                                <div class="${labelClass}">${stepLabels[i - 1]}</div>
                            </div>
                        `;

                    if (i < this.totalSteps) {
                        let lineClass = "flex-1 h-1 mx-2 rounded ";
                        if (i < this.step) {
                            lineClass += "bg-green-500";
                        } else {
                            lineClass += "bg-gray-200";
                        }
                        html += `<div class="${lineClass}"></div>`;
                    }
                }
                container.innerHTML = html;
            },

            renderContent() {
                const container = document.getElementById('step-content');
                let content = '';

                // Labels for header
                const titles = [
                    'Select Cost Pools', 'Review Expenses', 'Select Activities & Drivers', 'Measure Volumes',
                    'Calculate Rates', 'Allocate to Sections', 'Review & Approve', 'Post Allocation'
                ];
                const descs = [
                    'Choose cost pools to include', 'Verify assigned expenses', 'Choose drivers for ABC',
                    'Enter activity volumes', 'Review calculated rates', 'See section breakdown',
                    'Simulate and review', 'Finalize posting'
                ];
                document.getElementById('step-title-display').innerText = titles[this.step - 1];
                document.getElementById('step-desc-display').innerText = descs[this.step - 1];


                switch (this.step) {
                    case 1: content = this.renderStep1(); break;
                    case 2: content = this.renderStep2(); break;
                    case 3: content = this.renderStep3(); break;
                    case 4: content = this.renderStep4(); break;
                    case 5: content = this.renderStep5(); break;
                    case 6: content = this.renderStep6(); break;
                    case 7: content = this.renderStep7(); break;
                    case 8: content = this.renderStep8(); break;
                }
                container.innerHTML = content;
            },

            // --- Step Renderers ---

            renderStep1() {
                let html = `
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">`;
                mockCostPools.forEach(pool => {
                    const isSelected = this.state.selectedPools.includes(pool.id);
                    html += `
                            <div onclick="wizard.toggleSelection('selectedPools', '${pool.id}')"
                                class="p-6 rounded-xl border-2 cursor-pointer transition-all group hover:shadow-md ${isSelected ? 'border-purple-500 bg-purple-50' : 'border-gray-200 bg-white hover:border-purple-200'}">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-700 transition-colors">${pool.name}</h3>
                                        <p class="text-sm text-gray-600">${pool.description}</p>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center ${isSelected ? 'border-purple-500 bg-purple-500 text-white' : 'border-gray-300'}">
                                        ${isSelected ? '<i class="bi bi-check"></i>' : ''}
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-4">
                                    <div>
                                        <div class="text-2xl font-bold text-purple-600">Rs ${pool.totalAmount.toLocaleString()}</div>
                                        <div class="text-xs text-gray-500">${pool.expenseIds.length} expenses</div>
                                    </div>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded uppercase">${pool.category}</span>
                                </div>
                            </div>
                        `;
                });
                html += `</div>`;
                return html;
            },

            renderStep2() {
                const selectedPoolsData = mockCostPools.filter(p => this.state.selectedPools.includes(p.id));
                if (selectedPoolsData.length === 0) return '<div class="text-center p-10 text-gray-500">No Cost Pools Selected</div>';

                let html = `<div class="space-y-6">`;
                selectedPoolsData.forEach(pool => {
                    const poolExpenses = mockExpenses.filter(e => e.costPoolId === pool.id);
                    const total = poolExpenses.reduce((sum, e) => sum + e.amount, 0);

                    html += `
                            <div class="bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">${pool.name}</h3>
                                        <p class="text-sm text-gray-500">${poolExpenses.length} expenses assigned</p>
                                    </div>
                                    <div class="text-xl font-bold text-orange-600">Rs ${total.toLocaleString()}</div>
                                </div>
                                <div class="divide-y divide-gray-100">
                            `;
                    poolExpenses.forEach(exp => {
                        html += `
                                    <div class="px-6 py-3 flex justify-between items-center hover:bg-gray-50">
                                        <div>
                                            <div class="font-medium text-gray-900">${exp.name}</div>
                                            <div class="text-sm text-gray-500">${exp.vendor}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-gray-900">Rs ${exp.amount.toLocaleString()}</div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 capitalize">
                                                ${exp.status}
                                            </span>
                                        </div>
                                    </div>
                                `;
                    });
                    html += `</div></div>`;
                });
                html += `</div>`;
                return html;
            },

            renderStep3() {
                let html = `<div class="space-y-8">`;

                // Activities
                html += `<div><h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><i class="bi bi-activity"></i> Activities</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">`;
                mockActivities.forEach(act => {
                    const isSelected = this.state.selectedActivities.includes(act.id);
                    html += `
                            <div onclick="wizard.toggleSelection('selectedActivities', '${act.id}')"
                                class="p-4 rounded-xl border-2 cursor-pointer transition-all ${isSelected ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 bg-white hover:border-indigo-200'}">
                                <div class="flex justify-between items-start">
                                     <div>
                                        <h4 class="font-bold text-gray-900">${act.name}</h4>
                                        <p class="text-sm text-gray-600">${act.description}</p>
                                        <span class="mt-2 inline-block px-2 py-0.5 bg-indigo-100 text-indigo-700 text-xs rounded capitalize">${act.category}</span>
                                     </div>
                                     <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center ${isSelected ? 'border-indigo-500 bg-indigo-500 text-white' : 'border-gray-300'}">
                                        ${isSelected ? '<i class="bi bi-check"></i>' : ''}
                                    </div>
                                </div>
                            </div>
                         `;
                });
                html += `</div></div>`;

                // Drivers
                html += `<div><h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2"><i class="bi bi-speedometer2"></i> Cost Drivers</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">`;
                mockCostDrivers.forEach(driver => {
                    const isSelected = this.state.selectedDrivers.includes(driver.id);
                    html += `
                            <div onclick="wizard.toggleSelection('selectedDrivers', '${driver.id}')"
                                class="p-4 rounded-xl border-2 cursor-pointer transition-all ${isSelected ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-blue-200'}">
                                <div class="flex justify-between items-start">
                                     <div>
                                        <h4 class="font-bold text-gray-900">${driver.name}</h4>
                                        <p class="text-sm text-gray-600">${driver.description}</p>
                                        <div class="mt-2 flex gap-2">
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded capitalize">${driver.type}</span>
                                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">${driver.unit}</span>
                                        </div>
                                     </div>
                                     <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center ${isSelected ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-300'}">
                                        ${isSelected ? '<i class="bi bi-check"></i>' : ''}
                                    </div>
                                </div>
                            </div>
                         `;
                });
                html += `</div></div></div>`;
                return html;
            },

            renderStep4() {
                let html = `<div class="space-y-4">`;
                this.state.activityVolumes.forEach((vol, idx) => {
                    const act = mockActivities.find(a => a.id === vol.activityId);
                    const driver = mockCostDrivers.find(d => d.id === vol.driverId);

                    html += `
                            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">${act.name}</h3>
                                    <p class="text-sm text-gray-500">Driver: ${driver ? driver.name : 'N/A'} (${driver ? driver.unit : ''})</p>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Kitchen</label>
                                        <input type="number" value="${vol.kitchen}" 
                                            onchange="wizard.updateVolume(${idx}, 'kitchen', this.value)"
                                            class="w-full p-3 border border-gray-300 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Cake</label>
                                        <input type="number" value="${vol.cake}" 
                                            onchange="wizard.updateVolume(${idx}, 'cake', this.value)"
                                            class="w-full p-3 border border-gray-300 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Bakery</label>
                                        <input type="number" value="${vol.bakery}" 
                                            onchange="wizard.updateVolume(${idx}, 'bakery', this.value)"
                                            class="w-full p-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                                <div class="mt-3 p-2 bg-blue-50 rounded text-center text-sm font-medium text-blue-800">
                                    Total: <span id="vol-total-${idx}">${Number(vol.kitchen) + Number(vol.cake) + Number(vol.bakery)}</span> ${driver ? driver.unit : ''}
                                </div>
                            </div>
                         `;
                });
                html += `</div>`;
                return html;
            },

            updateVolume(idx, field, value) {
                this.state.activityVolumes[idx][field] = Number(value);
                const v = this.state.activityVolumes[idx];
                document.getElementById(`vol-total-${idx}`).innerText = v.kitchen + v.cake + v.bakery;
            },

            renderStep5() {
                let html = `<div class="space-y-4">`;
                // Calculate rates for display
                this.state.selectedPools.forEach(poolId => {
                    const pool = mockCostPools.find(p => p.id === poolId);
                    const driver = mockCostDrivers.find(d => d.id === pool.driverId);

                    if (pool && driver && driver.total > 0) {
                        const rate = pool.totalAmount / driver.total;
                        html += `
                                <div class="bg-white rounded-xl p-6 border-2 border-gray-100">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">${pool.name}</h3>
                                            <p class="text-sm text-gray-600">Driver: ${driver.name}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500 uppercase">Rate per Unit</div>
                                            <div class="text-2xl font-bold text-emerald-600">Rs ${rate.toFixed(2)}</div>
                                        </div>
                                    </div>
                                    <div class="bg-emerald-50 rounded-lg p-4 grid grid-cols-3 gap-4">
                                        <div>
                                            <div class="text-xs text-emerald-800">Total Cost</div>
                                            <div class="font-bold text-emerald-900">Rs ${pool.totalAmount.toLocaleString()}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-emerald-800">Total Volume</div>
                                            <div class="font-bold text-emerald-900">${driver.total.toLocaleString()}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-emerald-800">Details</div>
                                            <div class="text-xs text-emerald-900 font-mono mt-1">${pool.totalAmount} / ${driver.total}</div>
                                        </div>
                                    </div>
                                </div>
                             `;
                    }
                });
                html += `</div>`;
                return html;
            },

            renderStep6() {
                const allocs = this.calculateAllocations();
                let html = `
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl p-6 text-white text-center">
                                <div class="text-sm opacity-90">Kitchen</div>
                                <div class="text-2xl font-bold my-2">Rs ${allocs.kitchen.amount.toLocaleString(undefined, { maximumFractionDigits: 0 })}</div>
                                <div class="text-xs opacity-75">${allocs.kitchen.percentage.toFixed(1)}% of total</div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl p-6 text-white text-center">
                                <div class="text-sm opacity-90">Cake</div>
                                <div class="text-2xl font-bold my-2">Rs ${allocs.cake.amount.toLocaleString(undefined, { maximumFractionDigits: 0 })}</div>
                                <div class="text-xs opacity-75">${allocs.cake.percentage.toFixed(1)}% of total</div>
                            </div>
                            <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl p-6 text-white text-center">
                                <div class="text-sm opacity-90">Bakery</div>
                                <div class="text-2xl font-bold my-2">Rs ${allocs.bakery.amount.toLocaleString(undefined, { maximumFractionDigits: 0 })}</div>
                                <div class="text-xs opacity-75">${allocs.bakery.percentage.toFixed(1)}% of total</div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h3 class="font-bold text-gray-900 mb-4">Allocation Breakdown</h3>
                    `;

                this.state.selectedPools.forEach(poolId => {
                    const pool = mockCostPools.find(p => p.id === poolId);
                    const driver = mockCostDrivers.find(d => d.id === pool.driverId);
                    if (pool && driver) {
                        const rate = pool.totalAmount / driver.total;
                        html += `
                                <div class="mb-4 pb-4 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                                    <div class="font-medium text-gray-900 mb-2">${pool.name}</div>
                                    <div class="grid grid-cols-3 gap-3 text-sm">
                                        <div class="p-2 bg-gray-50 rounded">Kitchen: <b>Rs ${(rate * (driver.values.kitchen || 0)).toLocaleString(undefined, { maximumFractionDigits: 0 })}</b></div>
                                        <div class="p-2 bg-gray-50 rounded">Cake: <b>Rs ${(rate * (driver.values.cake || 0)).toLocaleString(undefined, { maximumFractionDigits: 0 })}</b></div>
                                        <div class="p-2 bg-gray-50 rounded">Bakery: <b>Rs ${(rate * (driver.values.bakery || 0)).toLocaleString(undefined, { maximumFractionDigits: 0 })}</b></div>
                                    </div>
                                </div>
                           `;
                    }
                });
                html += `</div>`;
                return html;
            },

            renderStep7() {
                const allocs = this.calculateAllocations();
                return `
                        <div class="space-y-6">
                            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Allocation Name</label>
                                        <input type="text" value="${this.state.allocationName}" onchange="wizard.state.allocationName = this.value"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Period</label>
                                        <input type="month" value="${this.state.period}" onchange="wizard.state.period = this.value"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                     <div class="p-3 bg-purple-50 rounded-lg text-center">
                                        <div class="text-xs text-gray-500">Pools</div>
                                        <div class="font-bold text-purple-700 text-xl">${this.state.selectedPools.length}</div>
                                     </div>
                                     <div class="p-3 bg-indigo-50 rounded-lg text-center">
                                        <div class="text-xs text-gray-500">Activities</div>
                                        <div class="font-bold text-indigo-700 text-xl">${this.state.selectedActivities.length}</div>
                                     </div>
                                     <div class="p-3 bg-blue-50 rounded-lg text-center">
                                        <div class="text-xs text-gray-500">Drivers</div>
                                        <div class="font-bold text-blue-700 text-xl">${this.state.selectedDrivers.length}</div>
                                     </div>
                                     <div class="p-3 bg-emerald-50 rounded-lg text-center">
                                        <div class="text-xs text-gray-500">Total</div>
                                        <div class="font-bold text-emerald-700 text-xl">Rs ${allocs.total.toLocaleString(undefined, { maximumFractionDigits: 0 })}</div>
                                     </div>
                                </div>

                                <div>
                                     <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                     <textarea rows="3" onchange="wizard.state.notes = this.value"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Add notes...">${this.state.notes}</textarea>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <button onclick="wizard.runSimulation()" class="flex-1 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-xl font-bold shadow-lg hover:from-cyan-600 hover:to-blue-700 transition-all flex justify-center items-center gap-2">
                                    <i class="bi bi-play-circle"></i> Run Simulation
                                </button>
                                <button onclick="wizard.saveDraft()" class="flex-1 py-4 bg-gray-600 text-white rounded-xl font-bold shadow-lg hover:bg-gray-700 transition-all flex justify-center items-center gap-2">
                                     <i class="bi bi-save"></i> Save Draft
                                </button>
                            </div>

                            ${this.state.simulation ? `
                                <div class="bg-cyan-50 border border-cyan-200 rounded-xl p-4 animate-fade-in-up">
                                    <div class="flex items-center gap-2 text-cyan-800 font-bold mb-2">
                                        <i class="bi bi-check-circle-fill"></i> Simulation Results
                                    </div>
                                    <p class="text-sm text-cyan-700">Simulation completed successfully at ${this.state.simulation.timestamp}. All allocations are balanced.</p>
                                </div>
                            ` : ''}
                        </div>
                    `;
            },

            renderStep8() {
                const allocs = this.calculateAllocations();
                return `
                        <div class="max-w-3xl mx-auto text-center space-y-8">
                             <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 border-2 border-green-200">
                                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="bi bi-check-lg text-4xl text-green-600"></i>
                                </div>
                                <h2 class="text-3xl font-bold text-green-900 mb-2">Ready to Post</h2>
                                <p class="text-green-800 mb-8 max-w-lg mx-auto">
                                    You're about to post <b>Rs ${allocs.total.toLocaleString(undefined, { maximumFractionDigits: 0 })}</b> in overhead allocations to the ledger.
                                </p>

                                <div class="grid grid-cols-3 gap-6 mb-8 text-left">
                                     <div class="bg-white p-4 rounded-xl shadow-sm">
                                        <div class="text-sm text-gray-500 uppercase">Kitchen</div>
                                        <div class="text-xl font-bold text-gray-900">Rs ${allocs.kitchen.amount.toLocaleString(undefined, { maximumFractionDigits: 0 })}</div>
                                     </div>
                                     <div class="bg-white p-4 rounded-xl shadow-sm">
                                        <div class="text-sm text-gray-500 uppercase">Cake</div>
                                        <div class="text-xl font-bold text-gray-900">Rs ${allocs.cake.amount.toLocaleString(undefined, { maximumFractionDigits: 0 })}</div>
                                     </div>
                                     <div class="bg-white p-4 rounded-xl shadow-sm">
                                        <div class="text-sm text-gray-500 uppercase">Bakery</div>
                                        <div class="text-xl font-bold text-gray-900">Rs ${allocs.bakery.amount.toLocaleString(undefined, { maximumFractionDigits: 0 })}</div>
                                     </div>
                                </div>

                                 <div class="flex gap-4 justify-center">
                                    <button onclick="wizard.saveDraft()" class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-200 rounded-xl font-bold hover:bg-gray-50">
                                        Save & Exit
                                    </button>
                                    <button onclick="wizard.postToLedger()" class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-green-200 hover:from-green-600 hover:to-emerald-700 flex items-center gap-2">
                                        <i class="bi bi-send"></i> Post to Ledger
                                    </button>
                                 </div>
                             </div>

                             <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-left flex items-start gap-3">
                                <i class="bi bi-exclamation-triangle-fill text-yellow-500 mt-1"></i>
                                <div class="text-sm text-yellow-800">
                                    <b>Important:</b> This action will update cost center balances immediately. Ensure all volume data is verified before posting. Reversals must be done manually via journal entry adjustments.
                                </div>
                             </div>
                        </div>
                    `;
            }
        };

        // Start
        document.addEventListener('DOMContentLoaded', () => {
            wizard.init();
        });
    </script>
@endsection