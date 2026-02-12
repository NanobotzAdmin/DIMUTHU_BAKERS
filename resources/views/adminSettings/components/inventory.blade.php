{{-- 
    resources/views/settings/inventory.blade.php 
--}}

<form id="inventory-settings-form" action="" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- Package Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Inventory Settings</h2>
                <p class="text-sm text-gray-600">Valuation, stock alerts, and units of measure</p>
            </div>
        </div>

        {{-- Save Actions --}}
        <div id="inventory-save-actions" class="hidden items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetInventoryForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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

    {{-- Valuation Method --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Inventory Valuation</h3>
            <div>
                <label for="valuation_method" class="block text-sm font-medium text-gray-700 mb-1">Valuation Method</label>
                <select name="valuation_method" id="valuation_method" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                    @php $method = $settings->valuation_method ?? 'fifo'; @endphp
                    <option value="fifo" {{ $method == 'fifo' ? 'selected' : '' }}>FIFO (First In, First Out)</option>
                    <option value="weighted_average" {{ $method == 'weighted_average' ? 'selected' : '' }}>Weighted Average Cost</option>
                </select>
                <p id="valuation-help" class="text-xs text-gray-500 mt-1">
                    {{ $method == 'fifo' ? 'Oldest inventory costs are expensed first' : 'Average cost of all inventory is used' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Stock Alerts --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Stock Alerts</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="stock_alerts[low_stock_enabled]" id="low_stock_enabled" value="1"
                        {{ old('stock_alerts.low_stock_enabled', $settings->stock_alerts['low_stock_enabled'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="low_stock_enabled" class="text-sm text-gray-700 font-medium select-none">
                        Enable low stock email alerts
                    </label>
                </div>

                {{-- Conditional Section --}}
                <div id="stock-alerts-content" class="space-y-4 transition-all duration-300 {{ old('stock_alerts.low_stock_enabled', $settings->stock_alerts['low_stock_enabled'] ?? false) ? '' : 'hidden opacity-50' }}">
                    <div>
                        <label for="critical_stock" class="block text-sm font-medium text-gray-700 mb-1">Critical Stock Level (%)</label>
                        <input type="number" step="1" name="stock_alerts[critical_stock_percent]" id="critical_stock" 
                            value="{{ old('stock_alerts.critical_stock_percent', $settings->stock_alerts['critical_stock_percent'] ?? '50') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                            placeholder="50">
                        <p class="text-xs text-gray-500 mt-1">Alert when stock falls below this % of reorder point</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Recipients</label>
                        <div id="email-recipients-list" class="flex flex-wrap gap-2 mb-2">
                            {{-- Existing Recipients --}}
                            @if(isset($settings->stock_alerts['email_recipients']))
                                @foreach($settings->stock_alerts['email_recipients'] as $email)
                                    <span class="email-tag inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                        {{ $email }}
                                        <input type="hidden" name="stock_alerts[email_recipients][]" value="{{ $email }}">
                                        <button type="button" onclick="removeParentTag(this)" class="hover:text-red-600 focus:outline-none">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </span>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addEmailRecipient()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Email
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Units of Measure --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Units of Measure</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Static Units (Display Only) --}}
                @foreach(['weight' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'], 
                          'volume' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'], 
                          'quantity' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800']] as $type => $style)
                <div>
                    <label class="block text-sm font-medium text-gray-700 capitalize">{{ $type }}</label>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($settings->units_of_measure[$type] ?? [] as $unit)
                            <span class="px-3 py-1 {{ $style['bg'] }} {{ $style['text'] }} rounded text-sm select-none">
                                {{ $unit }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endforeach

                {{-- Custom Units (Dynamic) --}}
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Custom Units</label>
                    <div id="custom-units-list" class="flex flex-wrap gap-2 mt-2 mb-2">
                        @if(isset($settings->units_of_measure['custom']))
                            @foreach($settings->units_of_measure['custom'] as $unit)
                                <span class="custom-unit-tag inline-flex items-center gap-1 px-3 py-1 bg-orange-100 text-orange-800 rounded text-sm">
                                    {{ $unit }}
                                    <input type="hidden" name="units_of_measure[custom][]" value="{{ $unit }}">
                                    <button type="button" onclick="removeParentTag(this)" class="hover:text-red-600 focus:outline-none">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </span>
                            @endforeach
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <input type="text" id="new-custom-unit" placeholder="Enter custom unit" 
                            class="w-40 rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                        <button type="button" onclick="addCustomUnit()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock Movement Settings --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Stock Movement Settings</h3>
            <div class="space-y-4">
                <div>
                    <label for="approval_percent" class="block text-sm font-medium text-gray-700 mb-1">Require Approval for Adjustments Above (%)</label>
                    <input type="number" step="0.1" name="stock_movements[require_approval_above_percent]" id="approval_percent" 
                        value="{{ old('stock_movements.require_approval_above_percent', $settings->stock_movements['require_approval_above_percent'] ?? '10') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="10">
                    <p class="text-xs text-gray-500 mt-1">Adjustments exceeding this % of item value require manager approval</p>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="stock_movements[require_reason]" id="require_reason" value="1"
                            {{ old('stock_movements.require_reason', $settings->stock_movements['require_reason'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="require_reason" class="text-sm text-gray-700 select-none">
                            Require reason for all stock adjustments
                        </label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="stock_movements[auto_po]" id="auto_po" value="1"
                            {{ old('stock_movements.auto_po', $settings->stock_movements['auto_po'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="auto_po" class="text-sm text-gray-700 select-none">
                            Auto-create purchase orders when stock below reorder point
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions --}}
    <div id="inventory-save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetInventoryForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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
        const form = document.getElementById('inventory-settings-form');
        if (!form) return;

        const saveActionsTop = document.getElementById('inventory-save-actions');
        const saveActionsBottom = document.getElementById('inventory-save-actions-bottom');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        // Stock Alert Logic
        const lowStockCheckbox = document.getElementById('low_stock_enabled');
        const stockAlertsContent = document.getElementById('stock-alerts-content');
        
        // Valuation Help Text
        const valuationSelect = document.getElementById('valuation_method');
        const valuationHelp = document.getElementById('valuation-help');

        let initialValues = {};

        const getValue = (input) => {
            if (input.type === 'checkbox') return input.checked;
            return input.value;
        };

        // Capture initial values (simplified for demo, complex arrays need careful handling)
        inputs.forEach(input => {
            const key = input.id || input.name;
            if(key) initialValues[key] = getValue(input);
        });

        // 1. Toggle Logic
        if (lowStockCheckbox) {
            lowStockCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    stockAlertsContent.classList.remove('hidden', 'opacity-50');
                } else {
                    stockAlertsContent.classList.add('hidden', 'opacity-50');
                }
            });
        }

        if (valuationSelect) {
            valuationSelect.addEventListener('change', function() {
                if (this.value === 'fifo') {
                    valuationHelp.textContent = 'Oldest inventory costs are expensed first';
                } else {
                    valuationHelp.textContent = 'Average cost of all inventory is used';
                }
            });
        }

        // 2. Change Detection
        function checkInventoryChanges() {
            // Simplified change detection (Trigger on any input event)
            const toggle = (el, show) => {
                if (show) {
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                } else {
                    el.classList.add('hidden');
                    el.classList.remove('flex');
                }
            };
            toggle(saveActionsTop, true);
            toggle(saveActionsBottom, true);
        }

        inputs.forEach(input => {
            input.addEventListener('input', checkInventoryChanges);
            input.addEventListener('change', checkInventoryChanges);
        });

        // 3. Dynamic Email Recipients
        window.addEmailRecipient = function() {
            const email = prompt('Enter email address:');
            if (email) {
                const container = document.getElementById('email-recipients-list');
                const span = document.createElement('span');
                span.className = 'email-tag inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm';
                span.innerHTML = `
                    ${email}
                    <input type="hidden" name="stock_alerts[email_recipients][]" value="${email}">
                    <button type="button" onclick="removeParentTag(this)" class="hover:text-red-600 focus:outline-none">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                container.appendChild(span);
                checkInventoryChanges();
            }
        };

        // 4. Dynamic Custom Units
        window.addCustomUnit = function() {
            const input = document.getElementById('new-custom-unit');
            const unit = input.value.trim();
            if (unit) {
                const container = document.getElementById('custom-units-list');
                const span = document.createElement('span');
                span.className = 'custom-unit-tag inline-flex items-center gap-1 px-3 py-1 bg-orange-100 text-orange-800 rounded text-sm';
                span.innerHTML = `
                    ${unit}
                    <input type="hidden" name="units_of_measure[custom][]" value="${unit}">
                    <button type="button" onclick="removeParentTag(this)" class="hover:text-red-600 focus:outline-none">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                container.appendChild(span);
                input.value = '';
                checkInventoryChanges();
            }
        };

        // 5. Shared Remove Function
        window.removeParentTag = function(button) {
            button.parentElement.remove();
            checkInventoryChanges();
        };

        // 6. Reset Function (Reload page is often easiest for complex array resets)
        window.resetInventoryForm = function() {
            window.location.reload();
        }
    })();
</script>