<!-- Production Completion Modal -->
<div id="completion-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity opacity-0"
        id="completion-modal-backdrop"></div>

    <!-- Modal Panel Container -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div id="completion-modal-content"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all scale-95 opacity-0 sm:my-8 sm:w-full sm:max-w-3xl">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                        <h3 class="text-lg font-semibold" id="completion-title">Complete Production</h3>
                    </div>
                    <p class="text-sm text-gray-500">Record actual yield, waste, and quality inspection results</p>
                </div>

                <!-- Tabs Header -->
                <div class="px-6 pt-4 border-b border-gray-200">
                    <div class="flex -mb-px space-x-8" aria-label="Tabs">
                        <button onclick="CompletionModal.switchTab('yield')" id="tab-btn-yield"
                            class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm border-[#D4A017] text-[#D4A017]">
                            Yield
                        </button>
                        <button onclick="CompletionModal.switchTab('waste')" id="tab-btn-waste"
                            class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Waste
                        </button>
                        <button onclick="CompletionModal.switchTab('quality')" id="tab-btn-quality"
                            class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Quality
                        </button>
                        <button onclick="CompletionModal.switchTab('summary')" id="tab-btn-summary"
                            class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Summary
                        </button>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="px-6 py-6 max-h-[65vh] overflow-y-auto">

                    <!-- TAB: Yield -->
                    <div id="tab-content-yield" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Planned -->
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <label class="text-sm text-blue-700 block">Planned Yield</label>
                                <div class="text-2xl font-bold text-blue-900 mt-1" id="yield-planned-val"></div>
                            </div>

                            <!-- Actual -->
                            <div class="space-y-2">
                                <label for="comp-actual-yield" class="text-sm font-medium leading-none">Actual Yield
                                    *</label>
                                <div class="flex gap-2">
                                    <input type="number" id="comp-actual-yield"
                                        oninput="CompletionModal.updateYieldCalc()" placeholder="Enter actual yield"
                                        class="flex h-10 w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:border-transparent">
                                    <div class="flex items-center px-3 bg-gray-100 rounded border text-sm font-medium"
                                        id="comp-yield-unit">Unit</div>
                                </div>
                            </div>
                        </div>

                        <!-- Variance Box -->
                        <div id="yield-variance-box" class="rounded-lg p-4 border bg-gray-50 border-gray-200 hidden">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium">Yield Variance</span>
                                <i id="yield-variance-icon" data-lucide="thumbs-up" class="w-5 h-5"></i>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-600">Difference</div>
                                    <div id="yield-variance-diff" class="text-xl font-bold"></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Percentage</div>
                                    <div id="yield-variance-pct" class="text-xl font-bold"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Time & Staff -->
                        <div class="space-y-3 pt-2">
                            <label class="font-medium text-sm">Time Tracking</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs text-gray-600">Planned Duration</label>
                                    <input type="text" id="comp-time-planned" disabled
                                        class="mt-1 flex h-10 w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-500">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600">Actual Duration (minutes)</label>
                                    <input type="number" id="comp-time-actual"
                                        class="mt-1 flex h-10 w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#D4A017]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Waste -->
                    <div id="tab-content-waste" class="space-y-4 hidden">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-start gap-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"></i>
                            <div>
                                <h4 class="font-medium text-yellow-900 mb-1">Three-Stage Waste Recovery System</h4>
                                <p class="text-sm text-yellow-700">Record all waste by stage for accurate NRV costing
                                    and recovery tracking</p>
                            </div>
                        </div>

                        <!-- Add Waste Form -->
                        <div class="border rounded-lg p-4 bg-gray-50 space-y-3">
                            <h4 class="font-medium text-sm">Add Waste Entry</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-medium mb-1 block">Stage *</label>
                                    <select id="waste-stage"
                                        class="h-9 w-full rounded-md border border-gray-300 px-3 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#D4A017]">
                                        <option value="1">Stage 1 - Raw Material</option>
                                        <option value="2">Stage 2 - Production</option>
                                        <option value="3">Stage 3 - Finished Product</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-medium mb-1 block">Category *</label>
                                    <select id="waste-category"
                                        class="h-9 w-full rounded-md border border-gray-300 px-3 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#D4A017]">
                                        <option value="spillage">Spillage</option>
                                        <option value="burnt">Burnt/Overcooked</option>
                                        <option value="defect">Defect/Malformed</option>
                                        <option value="contamination">Contamination</option>
                                        <option value="expired">Expired</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-medium mb-1 block">Quantity *</label>
                                    <div class="flex gap-2">
                                        <input type="number" id="waste-qty" placeholder="0.0"
                                            class="h-9 w-full rounded-md border border-gray-300 px-3 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#D4A017]">
                                        <div class="flex items-center px-2 bg-gray-100 rounded border text-xs"
                                            id="waste-unit-display">Kg</div>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-medium mb-1 block">Reason *</label>
                                    <input type="text" id="waste-reason" placeholder="Reason..."
                                        class="h-9 w-full rounded-md border border-gray-300 px-3 py-1 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#D4A017]">
                                </div>
                            </div>
                            <button onclick="CompletionModal.addWaste()"
                                class="w-full inline-flex items-center justify-center rounded-md border border-gray-300 bg-white h-9 text-sm font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#D4A017]">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Add Waste Entry
                            </button>
                        </div>

                        <!-- Waste List -->
                        <div id="waste-list-container space-y-2">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium">Recorded Waste</label>
                                <span id="waste-total-badge"
                                    class="inline-flex items-center rounded-full border border-red-200 bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700">Total:
                                    0</span>
                            </div>
                            <div id="waste-entries-list" class="space-y-2">
                                <!-- JS injected -->
                                <div class="text-center py-4 text-gray-400 text-sm italic" id="waste-empty-msg">No waste
                                    recorded</div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Quality -->
                    <div id="tab-content-quality" class="space-y-4 hidden">
                        <!-- Overall Status -->
                        <div>
                            <label class="text-sm font-medium mb-2 block">Overall Quality Status</label>
                            <div class="flex gap-3">
                                <button onclick="CompletionModal.setQualityStatus(true)" id="btn-quality-pass"
                                    class="flex-1 inline-flex items-center justify-center rounded-md h-10 text-sm font-medium bg-green-600 text-white hover:bg-green-700">
                                    <i data-lucide="thumbs-up" class="w-4 h-4 mr-2"></i> Pass
                                </button>
                                <button onclick="CompletionModal.setQualityStatus(false)" id="btn-quality-fail"
                                    class="flex-1 inline-flex items-center justify-center rounded-md h-10 text-sm font-medium border border-gray-300 bg-white text-gray-700 hover:bg-red-50 hover:text-red-700">
                                    <i data-lucide="thumbs-down" class="w-4 h-4 mr-2"></i> Fail
                                </button>
                            </div>
                        </div>

                        <!-- Checklist -->
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Quality Inspection Checklist</label>
                            <div id="quality-checklist-container" class="space-y-2">
                                <!-- JS Injected -->
                            </div>
                        </div>

                        <!-- Notes & Batch -->
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium">Quality Inspector Notes</label>
                                <textarea id="quality-notes" rows="3"
                                    class="w-full mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#D4A017]"></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium">Batch Number</label>
                                    <input type="text" id="batch-number"
                                        class="w-full mt-1 h-9 rounded-md border border-gray-300 px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-[#D4A017]">
                                </div>
                                <div>
                                    <label class="text-sm font-medium">Expiry Date</label>
                                    <input type="date" id="expiry-date"
                                        class="w-full mt-1 h-9 rounded-md border border-gray-300 px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-[#D4A017]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Summary -->
                    <div id="tab-content-summary" class="space-y-4 hidden h-full">
                        <div
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200 space-y-4">
                            <h3 class="font-semibold text-lg">Production Summary</h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-600">Recipe</div>
                                    <div class="font-semibold" id="sum-recipe"></div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600">Batch Number</div>
                                    <div class="font-semibold" id="sum-batch"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 sm:gap-4">
                                <div class="bg-white rounded p-3 text-center sm:text-left">
                                    <div class="text-xs text-gray-600 mb-1">Planned Yield</div>
                                    <div class="text-lg font-bold" id="sum-planned"></div>
                                </div>
                                <div class="bg-white rounded p-3 text-center sm:text-left">
                                    <div class="text-xs text-gray-600 mb-1">Actual Yield</div>
                                    <div class="text-lg font-bold text-green-600" id="sum-actual"></div>
                                </div>
                                <div class="bg-white rounded p-3 text-center sm:text-left">
                                    <div class="text-xs text-gray-600 mb-1">Variance</div>
                                    <div class="text-lg font-bold" id="sum-variance"></div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white rounded p-3">
                                    <div class="text-xs text-gray-600 mb-1">Total Waste</div>
                                    <div class="text-lg font-bold text-red-600" id="sum-waste"></div>
                                </div>
                                <div class="bg-white rounded p-3">
                                    <div class="text-xs text-gray-600 mb-1">Quality Status</div>
                                    <div class="text-lg font-bold" id="sum-quality"></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Production Notes</label>
                            <textarea id="production-notes" rows="3" placeholder="Any final notes..."
                                class="w-full mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#D4A017]"></textarea>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 flex flex-row-reverse gap-3 rounded-b-lg border-t bg-gray-50/50">
                    <button type="button" onclick="CompletionModal.submit()"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus:outline-none focus:ring-2 focus:ring-[#D4A017] disabled:pointer-events-none disabled:opacity-50 bg-green-600 text-white hover:bg-green-700 h-10 px-4 py-2">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                        Complete Production
                    </button>
                    <button type="button" onclick="CompletionModal.close()"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus:outline-none focus:ring-2 focus:ring-red-500 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const CompletionModal = {
        task: null,
        onCompleteCallback: null,
        activeTab: 'yield',
        wastageEntries: [],
        qualityPassed: true,
        qualityChecks: {
            visual: { label: 'Visual Appearance', icon: 'camera', checked: true },
            taste: { label: 'Taste & Flavor', icon: 'check-circle', checked: true },
            texture: { label: 'Texture & Consistency', icon: 'package', checked: true },
            temperature: { label: 'Temperature Check', icon: 'alert-triangle', checked: true },
            weight: { label: 'Weight/Size Compliance', icon: 'scale', checked: true },
        },

        open: function (task, onCompleteCallback) {
            this.task = task;
            this.onCompleteCallback = onCompleteCallback;
            this.resetForm();

            // Populate basic fields
            document.getElementById('completion-title').innerText = `Complete Production - ${task.recipe}`;
            document.getElementById('yield-planned-val').innerText = `${task.qty} ${task.unit}`;
            document.getElementById('comp-yield-unit').innerText = task.unit;
            document.getElementById('comp-actual-yield').value = task.qty; // Default to planned
            document.getElementById('comp-time-planned').value = `${task.duration} minutes`;
            document.getElementById('comp-time-actual').value = task.duration; // Default
            document.getElementById('waste-unit-display').innerText = task.unit;

            // Generate Batch & Expiry
            document.getElementById('batch-number').value = this.generateBatchNum(task.recipe);
            const expDate = new Date();
            expDate.setDate(expDate.getDate() + 3); // Mock 3 days
            document.getElementById('expiry-date').valueAsDate = expDate;

            this.updateYieldCalc();
            this.renderQualityChecks();
            this.switchTab('yield');
            this.updateWasteList();

            // Show
            const modal = document.getElementById('completion-modal');
            const backdrop = document.getElementById('completion-modal-backdrop');
            const content = document.getElementById('completion-modal-content');
            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
            lucide.createIcons();
        },

        close: function () {
            const modal = document.getElementById('completion-modal');
            modal.classList.add('hidden');
        },

        resetForm: function () {
            this.wastageEntries = [];
            this.qualityPassed = true;
            this.setQualityStatus(true);
            Object.keys(this.qualityChecks).forEach(k => this.qualityChecks[k].checked = true);
            document.getElementById('comp-actual-yield').value = '';
            document.getElementById('waste-qty').value = '';
            document.getElementById('waste-reason').value = '';
            document.getElementById('quality-notes').value = '';
            document.getElementById('production-notes').value = '';
        },

        switchTab: function (tabId) {
            this.activeTab = tabId;
            // Update Headers
            ['yield', 'waste', 'quality', 'summary'].forEach(t => {
                const btn = document.getElementById(`tab-btn-${t}`);
                const content = document.getElementById(`tab-content-${t}`);
                if (t === tabId) {
                    btn.className = "whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm border-[#D4A017] text-[#D4A017]";
                    content.classList.remove('hidden');
                } else {
                    btn.className = "whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300";
                    content.classList.add('hidden');
                }
            });

            if (tabId === 'summary') this.renderSummary();
            lucide.createIcons();
        },

        // --- Logic: Yield ---
        updateYieldCalc: function () {
            const planned = this.task.qty || 0;
            const actual = parseFloat(document.getElementById('comp-actual-yield').value) || 0;
            const variance = actual - planned;
            const variancePct = planned > 0 ? (variance / planned) * 100 : 0;

            const box = document.getElementById('yield-variance-box');
            const diffEl = document.getElementById('yield-variance-diff');
            const pctEl = document.getElementById('yield-variance-pct');
            const icon = document.getElementById('yield-variance-icon');

            box.classList.remove('hidden');

            const sign = variance > 0 ? '+' : '';
            diffEl.innerText = `${sign}${variance.toFixed(2)} ${this.task.unit}`;
            pctEl.innerText = `${sign}${variancePct.toFixed(1)}%`;

            // Color Coding
            if (variance >= 0) {
                box.className = "rounded-lg p-4 border bg-green-50 border-green-200 mt-2";
                diffEl.className = "text-xl font-bold text-green-700";
                pctEl.className = "text-xl font-bold text-green-700";
                icon.setAttribute('data-lucide', 'thumbs-up');
                icon.className = "w-5 h-5 text-green-600";
            } else {
                box.className = "rounded-lg p-4 border bg-red-50 border-red-200 mt-2";
                diffEl.className = "text-xl font-bold text-red-700";
                pctEl.className = "text-xl font-bold text-red-700";
                icon.setAttribute('data-lucide', 'trending-down');
                icon.className = "w-5 h-5 text-red-600";
            }
            lucide.createIcons();
        },

        // --- Logic: Waste ---
        addWaste: function () {
            const qty = parseFloat(document.getElementById('waste-qty').value);
            const reason = document.getElementById('waste-reason').value;
            if (!qty || qty <= 0 || !reason) {
                alert('Please enter valid quantity and reason.');
                return;
            }

            const stage = document.getElementById('waste-stage').value;
            const cat = document.getElementById('waste-category').value;
            const stageLabels = { '1': 'Raw Material', '2': 'Production', '3': 'Finished Product' };

            this.wastageEntries.push({
                stage: stage,
                stageName: stageLabels[stage],
                category: cat,
                quantity: qty,
                reason: reason
            });

            // Reset fields
            document.getElementById('waste-qty').value = '';
            document.getElementById('waste-reason').value = '';

            this.updateWasteList();
        },

        removeWaste: function (index) {
            this.wastageEntries.splice(index, 1);
            this.updateWasteList();
        },

        updateWasteList: function () {
            const container = document.getElementById('waste-entries-list');
            const totalBadge = document.getElementById('waste-total-badge');
            const emptyMsg = document.getElementById('waste-empty-msg');

            if (this.wastageEntries.length === 0) {
                container.innerHTML = '';
                container.appendChild(emptyMsg);
                emptyMsg.style.display = 'block';
                totalBadge.innerText = `Total: 0 ${this.task.unit}`;
                return;
            }

            emptyMsg.style.display = 'none';
            let total = 0;

            container.innerHTML = this.wastageEntries.map((w, i) => {
                total += w.quantity;
                const stageColors = { '1': 'bg-yellow-100 text-yellow-700', '2': 'bg-orange-100 text-orange-700', '3': 'bg-red-100 text-red-700' };

                return `
                <div class="border rounded-lg p-3 bg-white flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold ${stageColors[w.stage]} border-transparent">${w.stageName}</span>
                            <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold border-gray-200 bg-gray-100 text-gray-700 capitalize">${w.category}</span>
                        </div>
                        <p class="text-sm text-gray-600"><strong>${w.quantity} ${this.task.unit}</strong> - ${w.reason}</p>
                    </div>
                    <button onclick="CompletionModal.removeWaste(${i})" class="text-red-500 hover:text-red-700 p-1"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                </div>`;
            }).join('');

            totalBadge.innerText = `Total: ${total.toFixed(2)} ${this.task.unit}`;
            lucide.createIcons();
        },

        // --- Logic: Quality ---
        setQualityStatus: function (passed) {
            this.qualityPassed = passed;
            const btnPass = document.getElementById('btn-quality-pass');
            const btnFail = document.getElementById('btn-quality-fail');

            if (passed) {
                btnPass.className = "flex-1 inline-flex items-center justify-center rounded-md h-10 text-sm font-medium bg-green-600 text-white hover:bg-green-700 shadow";
                btnFail.className = "flex-1 inline-flex items-center justify-center rounded-md h-10 text-sm font-medium border border-gray-300 bg-white text-gray-700 hover:bg-red-50 hover:text-red-700";
            } else {
                btnPass.className = "flex-1 inline-flex items-center justify-center rounded-md h-10 text-sm font-medium border border-gray-300 bg-white text-gray-700 hover:bg-green-50 hover:text-green-700";
                btnFail.className = "flex-1 inline-flex items-center justify-center rounded-md h-10 text-sm font-medium bg-red-600 text-white hover:bg-red-700 shadow";
            }
        },

        renderQualityChecks: function () {
            const container = document.getElementById('quality-checklist-container');
            container.innerHTML = Object.entries(this.qualityChecks).map(([key, check]) => {
                const colorClass = check.checked ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
                const iconColor = check.checked ? 'text-green-600' : 'text-red-600';
                const statusIcon = check.checked ? 'check-circle' : 'x-circle';

                return `
                <div onclick="CompletionModal.toggleCheck('${key}')" class="flex items-center justify-between p-3 rounded-lg border cursor-pointer select-none transition-colors ${colorClass}">
                    <div class="flex items-center gap-2">
                        <i data-lucide="${check.icon}" class="w-4 h-4 text-gray-600"></i>
                        <span class="font-medium text-sm">${check.label}</span>
                    </div>
                    <i data-lucide="${statusIcon}" class="w-5 h-5 ${iconColor}"></i>
                </div>`;
            }).join('');
            lucide.createIcons();
        },

        toggleCheck: function (key) {
            this.qualityChecks[key].checked = !this.qualityChecks[key].checked;
            this.renderQualityChecks();
        },

        // --- Logic: Summary & Submit ---
        renderSummary: function () {
            const actual = parseFloat(document.getElementById('comp-actual-yield').value) || 0;
            const planned = this.task.qty;
            const variance = actual - planned;
            const totalWaste = this.wastageEntries.reduce((a, b) => a + b.quantity, 0);
            const passedChecks = Object.values(this.qualityChecks).filter(c => c.checked).length;

            document.getElementById('sum-recipe').innerText = this.task.recipe;
            document.getElementById('sum-batch').innerText = document.getElementById('batch-number').value;
            document.getElementById('sum-planned').innerText = `${planned} ${this.task.unit}`;
            document.getElementById('sum-actual').innerText = `${actual} ${this.task.unit}`;

            const vEl = document.getElementById('sum-variance');
            vEl.innerText = `${variance > 0 ? '+' : ''}${variance.toFixed(2)} ${this.task.unit}`;
            vEl.className = `text-lg font-bold ${variance >= 0 ? 'text-green-600' : 'text-red-600'}`;

            document.getElementById('sum-waste').innerText = `${totalWaste.toFixed(2)} ${this.task.unit}`;

            const qEl = document.getElementById('sum-quality');
            qEl.innerText = this.qualityPassed ? 'PASS' : 'FAIL';
            qEl.className = `text-lg font-bold ${this.qualityPassed ? 'text-green-600' : 'text-red-600'}`;
        },

        generateBatchNum: function (recipeName) {
            const date = new Date().toISOString().slice(0, 10).replace(/-/g, '');
            const code = recipeName.substring(0, 3).toUpperCase();
            const time = new Date().getHours() + "" + new Date().getMinutes();
            return `${code}-${date}-${time}`;
        },

        submit: function () {
            const actualYield = parseFloat(document.getElementById('comp-actual-yield').value);
            if (isNaN(actualYield) || actualYield < 0) {
                alert("Please enter a valid actual yield.");
                this.switchTab('yield');
                return;
            }

            const data = {
                taskId: this.task.id,
                actualYield: actualYield,
                actualDuration: document.getElementById('comp-time-actual').value,
                waste: this.wastageEntries,
                quality: {
                    passed: this.qualityPassed,
                    checks: this.qualityChecks,
                    notes: document.getElementById('quality-notes').value
                },
                batchNumber: document.getElementById('batch-number').value,
                expiryDate: document.getElementById('expiry-date').value,
                productionNotes: document.getElementById('production-notes').value
            };

            if (this.onCompleteCallback) {
                this.onCompleteCallback(data);
            }
            this.close();
        }
    };
</script>