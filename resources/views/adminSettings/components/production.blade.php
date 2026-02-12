{{-- 
    resources/views/settings/production.blade.php 
--}}

<form id="production-settings-form" action="" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- Factory Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Production Settings</h2>
                <p class="text-sm text-gray-600">Batch numbering, waste recovery, and production defaults</p>
            </div>
        </div>

        {{-- Save Actions --}}
        <div id="production-save-actions" class="hidden items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetProductionForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                Cancel
            </button>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Save Changes
            </button>
        </div>
    </div>

    {{-- Batch Numbering --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Batch Numbering</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="batch_format" class="block text-sm font-medium text-gray-700 mb-1">Batch Number Format</label>
                    <input type="text" name="batch_numbering[format]" id="batch_format" 
                        value="{{ old('batch_numbering.format', $settings->batch_numbering['format'] ?? 'BATCH-YYYYMMDD-####') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="BATCH-YYYYMMDD-####">
                    <p class="text-xs text-gray-500 mt-1">Use YYYY for year, MM for month, DD for day, #### for sequence</p>
                </div>

                <div>
                    <label for="current_sequence" class="block text-sm font-medium text-gray-700 mb-1">Current Sequence Number</label>
                    <input type="number" name="batch_numbering[current_sequence]" id="current_sequence" 
                        value="{{ old('batch_numbering.current_sequence', $settings->batch_numbering['current_sequence'] ?? '1234') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="1234">
                    <p class="text-xs text-gray-500 mt-1">Next batch will be: BATCH-{{ now()->format('Ymd') }}-{{ str_pad(($settings->batch_numbering['current_sequence'] ?? 0) + 1, 4, '0', STR_PAD_LEFT) }}</p>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="batch_numbering[auto_increment]" id="auto_increment" value="1"
                            {{ old('batch_numbering.auto_increment', $settings->batch_numbering['auto_increment'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="auto_increment" class="text-sm text-gray-700 select-none">
                            Automatically increment sequence number
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Waste Recovery - Three-Stage NRV System --}}
    <div class="bg-orange-50/30 rounded-lg border-2 border-orange-200 overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="text-xl">♻️</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Three-Stage Waste Recovery System</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Net Realizable Value (NRV) cost accounting for waste conversion
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="waste_recovery[enabled]" id="waste_enabled" value="1"
                        {{ old('waste_recovery.enabled', $settings->waste_recovery['enabled'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="waste_enabled" class="text-sm font-medium text-gray-700 select-none">
                        Enable Waste Recovery
                    </label>
                </div>
            </div>

            {{-- Collapsible Content --}}
            <div id="waste-recovery-content" class="space-y-6 transition-all duration-300 {{ old('waste_recovery.enabled', $settings->waste_recovery['enabled'] ?? false) ? '' : 'hidden opacity-50' }}">
                
                {{-- Method Display --}}
                <div class="bg-white rounded-lg p-4 border border-orange-200">
                    <label class="block text-sm font-medium text-gray-700">Recovery Method</label>
                    <p class="text-sm font-medium text-gray-900 mt-1">Three-Stage NRV</p>
                    <p class="text-xs text-gray-600 mt-1">Converts waste into other products using staged NRV calculations</p>
                </div>

                {{-- Threshold Alert --}}
                <div>
                    <label for="threshold_alert" class="block text-sm font-medium text-gray-700 mb-1">Waste Threshold Alert (%)</label>
                    <input type="number" step="0.1" name="waste_recovery[threshold_alert_percent]" id="threshold_alert" 
                        value="{{ old('waste_recovery.threshold_alert_percent', $settings->waste_recovery['threshold_alert_percent'] ?? '10') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="10">
                    <p class="text-xs text-gray-500 mt-1">Alert when waste exceeds this percentage of production</p>
                </div>

                {{-- Recovery Stages --}}
                <div>
                    <h4 class="font-medium text-gray-900 mb-3">Recovery Stage Percentages</h4>
                    <div class="space-y-3">
                        
                        {{-- Stage 1 --}}
                        <div class="flex items-center gap-4 bg-green-50 p-3 rounded-lg border border-green-200">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                            </div>
                            <div class="flex-1">
                                <label for="stage1_recovery" class="block text-sm font-medium text-gray-900">Stage 1 - Fresh Waste (same day)</label>
                                <p class="text-xs text-gray-600">Highest recovery value</p>
                            </div>
                            <div class="w-32">
                                <div class="flex items-center gap-2">
                                    <input type="number" step="0.01" name="waste_recovery[stage1_recovery_percent]" id="stage1_recovery"
                                        value="{{ old('waste_recovery.stage1_recovery_percent', $settings->waste_recovery['stage1_recovery_percent'] ?? '0') }}"
                                        class="nrv-input w-full rounded-md border-gray-300 shadow-sm text-right focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </div>
                        </div>

                        {{-- Stage 2 --}}
                        <div class="flex items-center gap-4 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center text-sm font-bold">2</div>
                            </div>
                            <div class="flex-1">
                                <label for="stage2_recovery" class="block text-sm font-medium text-gray-900">Stage 2 - Day-old Waste</label>
                                <p class="text-xs text-gray-600">Medium recovery value</p>
                            </div>
                            <div class="w-32">
                                <div class="flex items-center gap-2">
                                    <input type="number" step="0.01" name="waste_recovery[stage2_recovery_percent]" id="stage2_recovery"
                                        value="{{ old('waste_recovery.stage2_recovery_percent', $settings->waste_recovery['stage2_recovery_percent'] ?? '0') }}"
                                        class="nrv-input w-full rounded-md border-gray-300 shadow-sm text-right focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </div>
                        </div>

                        {{-- Stage 3 --}}
                        <div class="flex items-center gap-4 bg-orange-50 p-3 rounded-lg border border-orange-200">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                            </div>
                            <div class="flex-1">
                                <label for="stage3_recovery" class="block text-sm font-medium text-gray-900">Stage 3 - Older Waste</label>
                                <p class="text-xs text-gray-600">Lowest recovery value</p>
                            </div>
                            <div class="w-32">
                                <div class="flex items-center gap-2">
                                    <input type="number" step="0.01" name="waste_recovery[stage3_recovery_percent]" id="stage3_recovery"
                                        value="{{ old('waste_recovery.stage3_recovery_percent', $settings->waste_recovery['stage3_recovery_percent'] ?? '0') }}"
                                        class="nrv-input w-full rounded-md border-gray-300 shadow-sm text-right focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Example Calculation (Live Update via JS) --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-medium text-blue-900 mb-2">Example NRV Calculation</h4>
                    <p class="text-sm text-blue-800">
                        If a batch costs <strong>Rs. 1,000</strong> and produces waste:
                    </p>
                    <ul class="text-sm text-blue-800 mt-2 space-y-1 ml-4 list-disc">
                        <li>Stage 1 (same day): Recovered value = Rs. <span id="calc-result-1">0.00</span></li>
                        <li>Stage 2 (day-old): Recovered value = Rs. <span id="calc-result-2">0.00</span></li>
                        <li>Stage 3 (older): Recovered value = Rs. <span id="calc-result-3">0.00</span></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    {{-- Production Defaults --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Production Defaults</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="shelf_life" class="block text-sm font-medium text-gray-700 mb-1">Default Shelf Life (Hours)</label>
                    <input type="number" name="default_settings[shelf_life_hours]" id="shelf_life" 
                        value="{{ old('default_settings.shelf_life_hours', $settings->default_settings['shelf_life_hours'] ?? '24') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="24">
                    <p class="text-xs text-gray-500 mt-1">Default shelf life for produced batches</p>
                </div>

                <div class="space-y-3 md:pt-6">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="default_settings[require_qc_approval]" id="require_qc_approval" value="1"
                            {{ old('default_settings.require_qc_approval', $settings->default_settings['require_qc_approval'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="require_qc_approval" class="text-sm text-gray-700 select-none">
                            Require QC approval before releasing to inventory
                        </label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="default_settings[track_batch_genealogy]" id="track_batch_genealogy" value="1"
                            {{ old('default_settings.track_batch_genealogy', $settings->default_settings['track_batch_genealogy'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="track_batch_genealogy" class="text-sm text-gray-700 select-none">
                            Track batch genealogy (ingredient traceability)
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions --}}
    <div id="production-save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetProductionForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
            Cancel
        </button>
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
            </svg>
            Save Changes
        </button>
    </div>

</form>

<script>
    (function() {
        const form = document.getElementById('production-settings-form');
        if (!form) return;

        const saveActionsTop = document.getElementById('production-save-actions');
        const saveActionsBottom = document.getElementById('production-save-actions-bottom');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        // Waste Toggle Elements
        const wasteEnabledCheck = document.getElementById('waste_enabled');
        const wasteContent = document.getElementById('waste-recovery-content');

        // Calculation Elements
        const stage1Input = document.getElementById('stage1_recovery');
        const stage2Input = document.getElementById('stage2_recovery');
        const stage3Input = document.getElementById('stage3_recovery');
        const res1 = document.getElementById('calc-result-1');
        const res2 = document.getElementById('calc-result-2');
        const res3 = document.getElementById('calc-result-3');

        let initialValues = {};

        const getValue = (input) => {
            if (input.type === 'checkbox') return input.checked;
            return input.value;
        };

        // Capture initial values
        inputs.forEach(input => {
            const key = input.id || input.name;
            if(key) initialValues[key] = getValue(input);
        });

        // Toggle Waste Visibility
        function handleWasteToggle() {
            if (wasteEnabledCheck && wasteContent) {
                if (wasteEnabledCheck.checked) {
                    wasteContent.classList.remove('hidden', 'opacity-50');
                } else {
                    wasteContent.classList.add('hidden', 'opacity-50');
                }
            }
        }

        // Live NRV Calculation
        function updateCalculations() {
            const baseCost = 1000;
            const calc = (val) => ((baseCost * (parseFloat(val) || 0)) / 100).toFixed(2);

            if(res1 && stage1Input) res1.textContent = calc(stage1Input.value);
            if(res2 && stage2Input) res2.textContent = calc(stage2Input.value);
            if(res3 && stage3Input) res3.textContent = calc(stage3Input.value);
        }

        // Check for dirty state
        function checkProductionChanges() {
            let hasChanged = false;
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (key && getValue(input) !== initialValues[key]) {
                    hasChanged = true;
                }
            });

            const toggle = (el, show) => {
                if (show) {
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                } else {
                    el.classList.add('hidden');
                    el.classList.remove('flex');
                }
            };

            toggle(saveActionsTop, hasChanged);
            toggle(saveActionsBottom, hasChanged);
        }

        // Event Listeners
        inputs.forEach(input => {
            input.addEventListener('input', checkProductionChanges);
            input.addEventListener('change', checkProductionChanges);
        });

        if (wasteEnabledCheck) {
            wasteEnabledCheck.addEventListener('change', handleWasteToggle);
        }

        const calcInputs = [stage1Input, stage2Input, stage3Input];
        calcInputs.forEach(input => {
            if(input) input.addEventListener('input', updateCalculations);
        });

        // Initial Run
        updateCalculations();
        handleWasteToggle();

        // Global Reset Function
        window.resetProductionForm = function() {
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (!key) return;

                if (input.type === 'checkbox') {
                    input.checked = initialValues[key];
                } else {
                    input.value = initialValues[key];
                }
            });
            handleWasteToggle();
            updateCalculations();
            checkProductionChanges();
        }
    })();
</script>