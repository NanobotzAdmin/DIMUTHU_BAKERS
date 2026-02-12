{{-- 
    resources/views/settings/ai.blade.php 
--}}

<form id="ai-settings-form" action="" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- Bot Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">AI Assistant Configuration</h2>
                <p class="text-sm text-gray-600">Configure ChatGPT-style assistant for BakeryMate</p>
            </div>
        </div>

        {{-- Save Actions --}}
        <div id="ai-save-actions" class="hidden items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetAiForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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

    {{-- Enable/Disable --}}
    <div class="bg-blue-50/30 rounded-lg border-2 border-blue-200 p-6">
        <div class="flex items-center gap-3">
            <input type="checkbox" name="enabled" id="ai_enabled" value="1"
                {{ old('enabled', $settings->enabled ?? false) ? 'checked' : '' }}
                class="w-5 h-5 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
            <label for="ai_enabled" class="text-lg font-semibold text-gray-900 cursor-pointer select-none">
                Enable AI Assistant
            </label>
        </div>
        <p class="text-sm text-gray-600 mt-2 ml-8">
            Activate the ChatGPT-style assistant at <strong>/ai-assistant</strong> for real-time queries
        </p>
    </div>

    {{-- Conditional Settings Container --}}
    <div id="ai-settings-container" class="space-y-6 transition-all duration-300 {{ old('enabled', $settings->enabled ?? false) ? '' : 'hidden opacity-50' }}">
        
        {{-- API Configuration --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">API Configuration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="provider" class="block text-sm font-medium text-gray-700 mb-1">AI Provider</label>
                        <select name="provider" id="provider" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            @php $provider = $settings->provider ?? 'openai'; @endphp
                            <option value="openai" {{ $provider == 'openai' ? 'selected' : '' }}>OpenAI (GPT-4)</option>
                            <option value="anthropic" {{ $provider == 'anthropic' ? 'selected' : '' }}>Anthropic (Claude)</option>
                        </select>
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                        <select name="model" id="model" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            {{-- Options populated via JS based on provider --}}
                            @php $model = $settings->model ?? 'gpt-4'; @endphp
                            <option value="{{ $model }}">{{ $model }}</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="api_key" class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                        <div class="flex gap-2">
                            <input type="password" name="api_key" id="api_key" 
                                value="{{ old('api_key', $settings->api_key ?? '') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                                placeholder="sk-...">
                            <button type="button" onclick="toggleApiKeyVisibility()" class="px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                {{-- Eye Icon --}}
                                <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                {{-- Eye Off Icon (Hidden initially) --}}
                                <svg id="eye-off-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                        <p id="api-key-help" class="text-xs text-gray-500 mt-1">
                            Get your API key from platform.openai.com
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Query Limits --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Query Limits</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="limit_per_user" class="block text-sm font-medium text-gray-700 mb-1">Per User (queries/day)</label>
                        <input type="number" name="query_limits[per_user]" id="limit_per_user" 
                            value="{{ old('query_limits.per_user', $settings->query_limits['per_user'] ?? '100') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                            placeholder="100">
                    </div>
                    
                    <div>
                        <label for="limit_per_system" class="block text-sm font-medium text-gray-700 mb-1">System Total (queries/day)</label>
                        <input type="number" name="query_limits[per_system]" id="limit_per_system" 
                            value="{{ old('query_limits.per_system', $settings->query_limits['per_system'] ?? '1000') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                            placeholder="1000">
                    </div>
                </div>
            </div>
        </div>

        {{-- Context & Data Access --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Context & Data Access</h3>
                <div class="space-y-4">
                    <div>
                        <label for="context_window" class="block text-sm font-medium text-gray-700 mb-1">Context Window (days)</label>
                        <input type="number" name="context[window_days]" id="context_window" 
                            value="{{ old('context.window_days', $settings->context['window_days'] ?? '30') }}"
                            class="w-48 rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                            placeholder="30">
                        <p class="text-xs text-gray-500 mt-1">How many days of historical data to include</p>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="context[include_financial]" id="include_financial" value="1"
                                {{ old('context.include_financial', $settings->context['include_financial'] ?? false) ? 'checked' : '' }}
                                class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                            <label for="include_financial" class="text-sm text-gray-700 select-none">
                                Include financial data (sales, revenue, GL)
                            </label>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="context[include_inventory]" id="include_inventory" value="1"
                                {{ old('context.include_inventory', $settings->context['include_inventory'] ?? false) ? 'checked' : '' }}
                                class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                            <label for="include_inventory" class="text-sm text-gray-700 select-none">
                                Include inventory data (stock levels, products)
                            </label>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="context[include_customer]" id="include_customer" value="1"
                                {{ old('context.include_customer', $settings->context['include_customer'] ?? false) ? 'checked' : '' }}
                                class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                            <label for="include_customer" class="text-sm text-gray-700 select-none">
                                Include customer data (orders, preferences)
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enabled Features --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Enabled Features</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="features[sales_analysis]" id="feat_sales" value="1"
                            {{ old('features.sales_analysis', $settings->features['sales_analysis'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="feat_sales" class="text-sm text-gray-700 select-none">
                            📊 Sales Analysis & Insights
                        </label>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="features[inventory_forecasting]" id="feat_inventory" value="1"
                            {{ old('features.inventory_forecasting', $settings->features['inventory_forecasting'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="feat_inventory" class="text-sm text-gray-700 select-none">
                            📦 Inventory Forecasting
                        </label>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="features[financial_insights]" id="feat_finance" value="1"
                            {{ old('features.financial_insights', $settings->features['financial_insights'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="feat_finance" class="text-sm text-gray-700 select-none">
                            💰 Financial Insights & Reports
                        </label>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="features[day_end_status]" id="feat_dayend" value="1"
                            {{ old('features.day_end_status', $settings->features['day_end_status'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="feat_dayend" class="text-sm text-gray-700 select-none">
                            🔒 Day-End Status & Checklist
                        </label>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="features[product_recommendations]" id="feat_product" value="1"
                            {{ old('features.product_recommendations', $settings->features['product_recommendations'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="feat_product" class="text-sm text-gray-700 select-none">
                            🍰 Product Recommendations
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions --}}
    <div id="ai-save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetAiForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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
        const form = document.getElementById('ai-settings-form');
        if (!form) return;

        const saveActionsTop = document.getElementById('ai-save-actions');
        const saveActionsBottom = document.getElementById('ai-save-actions-bottom');
        const inputs = form.querySelectorAll('input, select');
        
        // Toggle Elements
        const enabledCheck = document.getElementById('ai_enabled');
        const settingsContainer = document.getElementById('ai-settings-container');
        
        // Provider/Model Logic
        const providerSelect = document.getElementById('provider');
        const modelSelect = document.getElementById('model');
        const apiKeyHelp = document.getElementById('api-key-help');

        const models = {
            'openai': [
                { value: 'gpt-4', text: 'GPT-4 (Best quality)' },
                { value: 'gpt-4-turbo', text: 'GPT-4 Turbo (Faster)' },
                { value: 'gpt-3.5-turbo', text: 'GPT-3.5 Turbo (Economical)' }
            ],
            'anthropic': [
                { value: 'claude-3-opus', text: 'Claude 3 Opus' },
                { value: 'claude-3-sonnet', text: 'Claude 3 Sonnet' }
            ]
        };

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

        // 1. Main Enable Toggle
        function handleEnableToggle() {
            if (enabledCheck && settingsContainer) {
                if (enabledCheck.checked) {
                    settingsContainer.classList.remove('hidden', 'opacity-50');
                } else {
                    settingsContainer.classList.add('hidden', 'opacity-50');
                }
            }
        }

        // 2. Dynamic Model Selection
        function updateModels() {
            if (!providerSelect || !modelSelect) return;
            
            const provider = providerSelect.value;
            const currentModel = modelSelect.value;
            const availableModels = models[provider] || [];

            // Clear options
            modelSelect.innerHTML = '';

            availableModels.forEach(m => {
                const option = document.createElement('option');
                option.value = m.value;
                option.text = m.text;
                if (m.value === currentModel) option.selected = true; // Try to keep selection
                modelSelect.appendChild(option);
            });

            // Update help text
            if(apiKeyHelp) {
                apiKeyHelp.textContent = provider === 'openai' 
                    ? 'Get your API key from platform.openai.com' 
                    : 'Get your API key from console.anthropic.com';
            }
        }

        // 3. API Key Visibility
        window.toggleApiKeyVisibility = function() {
            const input = document.getElementById('api_key');
            const eye = document.getElementById('eye-icon');
            const eyeOff = document.getElementById('eye-off-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                eye.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        };

        // 4. Change Detection
        function checkAiChanges() {
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
            input.addEventListener('input', checkAiChanges);
            input.addEventListener('change', checkAiChanges);
        });

        if (enabledCheck) {
            enabledCheck.addEventListener('change', handleEnableToggle);
        }

        if (providerSelect) {
            providerSelect.addEventListener('change', () => {
                updateModels();
                checkAiChanges();
            });
        }

        // Global Reset
        window.resetAiForm = function() {
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (!key) return;

                if (input.type === 'checkbox') {
                    input.checked = initialValues[key];
                } else {
                    input.value = initialValues[key];
                }
            });
            handleEnableToggle();
            updateModels(); // Reset models list based on original provider
            checkAiChanges();
        }

        // Initial Run
        handleEnableToggle();
        // Don't call updateModels() initially to preserve server-side rendered selected option
    })();
</script>