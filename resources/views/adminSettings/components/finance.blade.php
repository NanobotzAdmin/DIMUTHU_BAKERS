{{-- 
    resources/views/settings/finance.blade.php 
--}}

<form id="finance-settings-form" action="" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- DollarSign Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Finance & Accounting</h2>
                <p class="text-sm text-gray-600">GL accounts, tax settings, and accounting configuration</p>
            </div>
        </div>

        {{-- Save Actions (Hidden by default) --}}
        <div id="finance-save-actions" class="hidden items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetFinanceForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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

    {{-- GL Account Mappings Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-3 mb-4">
                <h3 class="text-lg font-semibold text-gray-900">General Ledger Account Mappings</h3>
                <div class="group relative">
                    {{-- Info Icon --}}
                    <svg class="w-4 h-4 text-gray-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{-- Tooltip --}}
                    <div class="invisible group-hover:visible absolute left-0 top-6 w-64 p-2 bg-gray-900 text-white text-xs rounded shadow-lg z-10">
                        These accounts are used for automatic journal entry generation
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="gl_cash_on_hand" class="block text-sm font-medium text-gray-700 mb-1">Cash on Hand</label>
                    <input type="text" name="gl_accounts[cash_on_hand]" id="gl_cash_on_hand" 
                        value="{{ old('gl_accounts.cash_on_hand', $settings->gl_accounts['cash_on_hand'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="1010">
                </div>

                <div>
                    <label for="gl_sales_revenue" class="block text-sm font-medium text-gray-700 mb-1">Sales Revenue</label>
                    <input type="text" name="gl_accounts[sales_revenue]" id="gl_sales_revenue" 
                        value="{{ old('gl_accounts.sales_revenue', $settings->gl_accounts['sales_revenue'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="4010">
                </div>

                <div>
                    <label for="gl_cogs" class="block text-sm font-medium text-gray-700 mb-1">Cost of Goods Sold</label>
                    <input type="text" name="gl_accounts[cost_of_goods_sold]" id="gl_cogs" 
                        value="{{ old('gl_accounts.cost_of_goods_sold', $settings->gl_accounts['cost_of_goods_sold'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="5010">
                </div>

                <div>
                    <label for="gl_finished_goods" class="block text-sm font-medium text-gray-700 mb-1">Finished Goods Inventory</label>
                    <input type="text" name="gl_accounts[finished_goods_inventory]" id="gl_finished_goods" 
                        value="{{ old('gl_accounts.finished_goods_inventory', $settings->gl_accounts['finished_goods_inventory'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="1310">
                </div>

                <div>
                    <label for="gl_wip" class="block text-sm font-medium text-gray-700 mb-1">Work in Progress (WIP)</label>
                    <input type="text" name="gl_accounts[work_in_progress]" id="gl_wip" 
                        value="{{ old('gl_accounts.work_in_progress', $settings->gl_accounts['work_in_progress'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="1320">
                </div>

                <div>
                    <label for="gl_raw_materials" class="block text-sm font-medium text-gray-700 mb-1">Raw Materials Inventory</label>
                    <input type="text" name="gl_accounts[raw_materials]" id="gl_raw_materials" 
                        value="{{ old('gl_accounts.raw_materials', $settings->gl_accounts['raw_materials'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="1300">
                </div>

                <div>
                    <label for="gl_card_clearing" class="block text-sm font-medium text-gray-700 mb-1">Card Payment Clearing</label>
                    <input type="text" name="gl_accounts[card_payment_clearing]" id="gl_card_clearing" 
                        value="{{ old('gl_accounts.card_payment_clearing', $settings->gl_accounts['card_payment_clearing'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="1015">
                </div>

                <div>
                    <label for="gl_customer_deposits" class="block text-sm font-medium text-gray-700 mb-1">Customer Deposits (Liability)</label>
                    <input type="text" name="gl_accounts[customer_deposits]" id="gl_customer_deposits" 
                        value="{{ old('gl_accounts.customer_deposits', $settings->gl_accounts['customer_deposits'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="2110">
                </div>

                <div>
                    <label for="gl_shipping" class="block text-sm font-medium text-gray-700 mb-1">Shipping Revenue</label>
                    <input type="text" name="gl_accounts[shipping]" id="gl_shipping" 
                        value="{{ old('gl_accounts.shipping', $settings->gl_accounts['shipping'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="4020">
                </div>
            </div>
        </div>
    </div>

    {{-- Tax Settings Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tax Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tax_vat_rate" class="block text-sm font-medium text-gray-700 mb-1">VAT Rate (%)</label>
                    <input type="number" step="0.01" name="tax_settings[vat_rate]" id="tax_vat_rate" 
                        value="{{ old('tax_settings.vat_rate', $settings->tax_settings['vat_rate'] ?? '18') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="18">
                    <p class="text-xs text-gray-500 mt-1">Standard VAT rate in Sri Lanka is 18%</p>
                </div>

                <div>
                    <label for="tax_vat_reg_number" class="block text-sm font-medium text-gray-700 mb-1">VAT Registration Number</label>
                    <input type="text" name="tax_settings[vat_registration_number]" id="tax_vat_reg_number" 
                        value="{{ old('tax_settings.vat_registration_number', $settings->tax_settings['vat_registration_number'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="123456789">
                </div>

                <div>
                    <label for="tax_filing_frequency" class="block text-sm font-medium text-gray-700 mb-1">Tax Filing Frequency</label>
                    <select name="tax_settings[filing_frequency]" id="tax_filing_frequency" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                        @php $freq = $settings->tax_settings['filing_frequency'] ?? 'monthly'; @endphp
                        <option value="monthly" {{ $freq == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ $freq == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="annually" {{ $freq == 'annually' ? 'selected' : '' }}>Annually</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Accounting Period Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Accounting Period</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Period</label>
                    <input type="text" value="{{ $settings->accounting_period['current_period'] ?? now()->format('F Y') }}" disabled
                        class="w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 shadow-sm cursor-not-allowed sm:text-sm p-2">
                    <p class="text-xs text-gray-500 mt-1">Format: Month YYYY</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Period Locking</label>
                    <div class="flex items-center gap-2 h-10">
                        <input type="checkbox" name="accounting_period[lock_closed_periods]" id="lock_closed_periods" value="1"
                            {{ old('accounting_period.lock_closed_periods', $settings->accounting_period['lock_closed_periods'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="lock_closed_periods" class="text-sm text-gray-700 select-none">
                            Prevent edits to closed periods
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Journal Entry Settings Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Journal Entry Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="je_auto_numbering_prefix" class="block text-sm font-medium text-gray-700 mb-1">Auto-numbering Prefix</label>
                    <input type="text" name="journal_entry_settings[auto_numbering_prefix]" id="je_auto_numbering_prefix" 
                        value="{{ old('journal_entry_settings.auto_numbering_prefix', $settings->journal_entry_settings['auto_numbering_prefix'] ?? 'JE') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="JE">
                    <p class="text-xs text-gray-500 mt-1">Format: JE-2024-0001</p>
                </div>

                <div>
                    <label for="je_require_approval_above" class="block text-sm font-medium text-gray-700 mb-1">Require Approval Above (Rs.)</label>
                    <input type="number" name="journal_entry_settings[require_approval_above]" id="je_require_approval_above" 
                        value="{{ old('journal_entry_settings.require_approval_above', $settings->journal_entry_settings['require_approval_above'] ?? '50000') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="50000">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Automatic Posting</label>
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="journal_entry_settings[auto_post_pos]" id="auto_post_pos" value="1"
                                {{ old('journal_entry_settings.auto_post_pos', $settings->journal_entry_settings['auto_post_pos'] ?? false) ? 'checked' : '' }}
                                class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                            <label for="auto_post_pos" class="text-sm text-gray-700 select-none">
                                Auto-post POS transactions
                            </label>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="journal_entry_settings[auto_post_production]" id="auto_post_production" value="1"
                                {{ old('journal_entry_settings.auto_post_production', $settings->journal_entry_settings['auto_post_production'] ?? false) ? 'checked' : '' }}
                                class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                            <label for="auto_post_production" class="text-sm text-gray-700 select-none">
                                Auto-post production journals
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions --}}
    <div id="finance-save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetFinanceForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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
    // Self-executing anonymous function to keep scope clean for this tab
    (function() {
        const formId = 'finance-settings-form';
        const form = document.getElementById(formId);
        
        // Guard clause in case this script runs but form isn't present
        if (!form) return;

        const saveActionsTop = document.getElementById('finance-save-actions');
        const saveActionsBottom = document.getElementById('finance-save-actions-bottom');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        let initialValues = {};

        // Helper to get value based on type
        const getValue = (input) => {
            if (input.type === 'checkbox') return input.checked;
            return input.value;
        };

        // Capture initial state
        inputs.forEach(input => {
            // Using ID or Name as key
            const key = input.id || input.name; 
            if(key) initialValues[key] = getValue(input);
        });

        function checkFinanceChanges() {
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
            input.addEventListener('input', checkFinanceChanges);
            input.addEventListener('change', checkFinanceChanges);
        });

        // Global function for the reset button
        window.resetFinanceForm = function() {
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (!key) return;

                if (input.type === 'checkbox') {
                    input.checked = initialValues[key];
                } else {
                    input.value = initialValues[key];
                }
            });
            checkFinanceChanges();
        }
    })();
</script>