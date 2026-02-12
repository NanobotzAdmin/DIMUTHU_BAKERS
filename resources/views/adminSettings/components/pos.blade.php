{{-- 
    resources/views/settings/pos.blade.php 
--}}

<form id="pos-settings-form" action="" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- ShoppingCart Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">POS Configuration</h2>
                <p class="text-sm text-gray-600">Point of Sale settings, cash drawer, and receipts</p>
            </div>
        </div>

        {{-- Save Actions --}}
        <div id="pos-save-actions" class="hidden items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetPosForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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

    {{-- Cash Drawer Settings --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cash Drawer Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="opening_float" class="block text-sm font-medium text-gray-700 mb-1">Opening Float (Rs.)</label>
                    <input type="number" step="0.01" name="cash_drawer[opening_float]" id="opening_float" 
                        value="{{ old('cash_drawer.opening_float', $settings->cash_drawer['opening_float'] ?? '10000') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="10000">
                    <p class="text-xs text-gray-500 mt-1">Default starting cash in drawer</p>
                </div>

                <div>
                    <label for="alert_threshold" class="block text-sm font-medium text-gray-700 mb-1">Alert Threshold (Rs.)</label>
                    <input type="number" step="0.01" name="cash_drawer[alert_threshold]" id="alert_threshold" 
                        value="{{ old('cash_drawer.alert_threshold', $settings->cash_drawer['alert_threshold'] ?? '100000') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="100000">
                    <p class="text-xs text-gray-500 mt-1">Alert when cash exceeds this amount</p>
                </div>

                <div class="md:col-span-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="cash_drawer[require_float_verification]" id="require_float_verification" value="1"
                            {{ old('cash_drawer.require_float_verification', $settings->cash_drawer['require_float_verification'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="require_float_verification" class="text-sm text-gray-700 select-none">
                            Require opening float verification at shift start
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Receipt Settings --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Receipt Settings</h3>
            <div class="space-y-4">
                <div>
                    <label for="receipt_header_text" class="block text-sm font-medium text-gray-700 mb-1">Receipt Header Text</label>
                    <input type="text" name="receipt[header_text]" id="receipt_header_text" 
                        value="{{ old('receipt.header_text', $settings->receipt['header_text'] ?? 'Your Bakery - Colombo 3') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="Your Bakery - Colombo 3">
                </div>

                <div>
                    <label for="receipt_footer_text" class="block text-sm font-medium text-gray-700 mb-1">Receipt Footer Text</label>
                    <textarea name="receipt[footer_text]" id="receipt_footer_text" rows="2"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="Thank you for your business!">{{ old('receipt.footer_text', $settings->receipt['footer_text'] ?? 'Thank you for your business!') }}</textarea>
                </div>

                <div>
                    <label for="receipt_template" class="block text-sm font-medium text-gray-700 mb-1">Receipt Template</label>
                    <select name="receipt[template]" id="receipt_template" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                        @php $tpl = $settings->receipt['template'] ?? 'standard'; @endphp
                        <option value="standard" {{ $tpl == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="compact" {{ $tpl == 'compact' ? 'selected' : '' }}>Compact</option>
                        <option value="detailed" {{ $tpl == 'detailed' ? 'selected' : '' }}>Detailed</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="receipt[show_qr_code]" id="show_qr_code" value="1"
                            {{ old('receipt.show_qr_code', $settings->receipt['show_qr_code'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="show_qr_code" class="text-sm text-gray-700 select-none">
                            Show QR code for digital receipt
                        </label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="receipt[auto_print]" id="auto_print" value="1"
                            {{ old('receipt.auto_print', $settings->receipt['auto_print'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="auto_print" class="text-sm text-gray-700 select-none">
                            Automatically print customer receipt
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Methods --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Accepted Payment Methods</h3>
            <div class="space-y-2">
                @php 
                    $methods = $settings->payment_methods ?? [];
                    $paymentTypes = [
                        'cash' => ['label' => '💵 Cash'],
                        'credit_card' => ['label' => '💳 Credit Card (Visa, Mastercard)'],
                        'debit_card' => ['label' => '🏦 Debit Card'],
                        'mobile_payment' => ['label' => '📱 Mobile Payment (Dialog, Mobitel)'],
                        'gift_card' => ['label' => '🎁 Gift Cards'],
                    ];
                @endphp

                @foreach($paymentTypes as $key => $data)
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="payment_methods[{{ $key }}]" id="pm_{{ $key }}" value="1"
                            {{ old("payment_methods.$key", $methods[$key] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="pm_{{ $key }}" class="text-sm text-gray-700 font-medium select-none">
                            {{ $data['label'] }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Transaction Settings --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="max_discount_percent" class="block text-sm font-medium text-gray-700 mb-1">Maximum Discount (%)</label>
                    <input type="number" step="0.1" name="transactions[max_discount_percent]" id="max_discount_percent" 
                        value="{{ old('transactions.max_discount_percent', $settings->transactions['max_discount_percent'] ?? '20') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="20">
                </div>

                <div>
                    <label for="rounding_amount" class="block text-sm font-medium text-gray-700 mb-1">Rounding Amount (Rs.)</label>
                    <input type="number" step="0.1" name="transactions[rounding_amount]" id="rounding_amount" 
                        value="{{ old('transactions.rounding_amount', $settings->transactions['rounding_amount'] ?? '0.5') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="0.5">
                    <p class="text-xs text-gray-500 mt-1">Round totals to nearest Rs. 0.50</p>
                </div>

                <div class="md:col-span-2 space-y-2">
                    {{-- Allow Refunds Checkbox --}}
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="transactions[allow_refunds]" id="allow_refunds" value="1"
                            {{ old('transactions.allow_refunds', $settings->transactions['allow_refunds'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="allow_refunds" class="text-sm text-gray-700 select-none">
                            Allow refunds
                        </label>
                    </div>

                    {{-- Conditional Supervisor Approval --}}
                    <div id="supervisor_approval_container" class="flex items-center gap-2 ml-6 transition-all duration-300 {{ old('transactions.allow_refunds', $settings->transactions['allow_refunds'] ?? false) ? '' : 'hidden opacity-50' }}">
                        <input type="checkbox" name="transactions[require_supervisor_for_refunds]" id="require_supervisor_for_refunds" value="1"
                            {{ old('transactions.require_supervisor_for_refunds', $settings->transactions['require_supervisor_for_refunds'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="require_supervisor_for_refunds" class="text-sm text-gray-600 select-none">
                            Require supervisor approval for refunds
                        </label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="transactions[round_totals]" id="round_totals" value="1"
                            {{ old('transactions.round_totals', $settings->transactions['round_totals'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="round_totals" class="text-sm text-gray-700 select-none">
                            Round transaction totals
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions --}}
    <div id="pos-save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetPosForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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
        const form = document.getElementById('pos-settings-form');
        if (!form) return;

        const saveActionsTop = document.getElementById('pos-save-actions');
        const saveActionsBottom = document.getElementById('pos-save-actions-bottom');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        // Specific elements for conditional logic
        const allowRefundsCheckbox = document.getElementById('allow_refunds');
        const supervisorContainer = document.getElementById('supervisor_approval_container');

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

        // Toggle logic for Refunds -> Supervisor Approval
        function handleRefundLogic() {
            if (allowRefundsCheckbox && supervisorContainer) {
                if (allowRefundsCheckbox.checked) {
                    supervisorContainer.classList.remove('hidden', 'opacity-50');
                } else {
                    supervisorContainer.classList.add('hidden', 'opacity-50');
                    // Optional: Uncheck the supervisor box if main refunds are disabled
                    // document.getElementById('require_supervisor_for_refunds').checked = false;
                }
            }
        }

        function checkPosChanges() {
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

        inputs.forEach(input => {
            input.addEventListener('input', checkPosChanges);
            input.addEventListener('change', checkPosChanges);
        });

        if (allowRefundsCheckbox) {
            allowRefundsCheckbox.addEventListener('change', handleRefundLogic);
        }

        // Global Reset Function
        window.resetPosForm = function() {
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (!key) return;

                if (input.type === 'checkbox') {
                    input.checked = initialValues[key];
                } else {
                    input.value = initialValues[key];
                }
            });
            handleRefundLogic(); // Reset conditional visibility
            checkPosChanges();   // Reset save button visibility
        }
    })();
</script>