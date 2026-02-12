{{-- 
    resources/views/settings/integrations.blade.php 
--}}
@php
    $settings = (object) [
        'integrations' => [
            [
                'id' => 'payhere',
                'name' => 'PayHere',
                'category' => 'payment',
                'status' => 'active',
                'enabled' => true,
                'last_sync' => now()->subHours(2),
                'config' => ['merchant_id' => '121XXXX']
            ],
            [
                'id' => 'stripe',
                'name' => 'Stripe',
                'category' => 'payment',
                'status' => 'inactive',
                'enabled' => false,
                'last_sync' => null,
                'config' => []
            ],
            [
                'id' => 'quickbooks',
                'name' => 'QuickBooks Online',
                'category' => 'accounting',
                'status' => 'active',
                'enabled' => true,
                'last_sync' => now()->subMinutes(45),
                'config' => []
            ],
            [
                'id' => 'xero',
                'name' => 'Xero',
                'category' => 'accounting',
                'status' => 'inactive',
                'enabled' => false,
                'last_sync' => null,
                'config' => []
            ]
        ],
        'webhooks' => [
            'enabled' => true,
            'url' => 'https://example.com/webhooks/bakery-erp',
            'secret' => 'whsec_randomstring123',
            'events' => ['order.created', 'inventory.low_stock']
        ]
    ];
@endphp

<form id="integrations-settings-form" action="" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- Plug Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Integrations</h2>
                <p class="text-sm text-gray-600">Payment gateways, accounting software, and webhooks</p>
            </div>
        </div>

        {{-- Save Actions --}}
        <div id="integration-save-actions" class="hidden items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetIntegrationForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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

    {{-- Payment Gateways --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Payment Gateways</h3>
        <div class="grid gap-4">
            @foreach($settings->integrations as $integration)
                @if($integration['category'] === 'payment')
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    {{-- Status Icon --}}
                                    @if($integration['status'] === 'active')
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @elseif($integration['status'] === 'error')
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif

                                    <h4 class="text-lg font-semibold text-gray-900">{{ $integration['name'] }}</h4>
                                    <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800">
                                        {{ ucfirst($integration['category']) }}
                                    </span>
                                    @if(!empty($integration['last_sync']))
                                        <span class="text-xs text-gray-500">
                                            Last sync: {{ \Carbon\Carbon::parse($integration['last_sync'])->format('Y-m-d H:i') }}
                                        </span>
                                    @endif
                                </div>

                                @if($integration['id'] === 'payhere' && $integration['enabled'])
                                    <div class="mt-3 space-y-2">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Merchant ID</label>
                                            <input type="text" value="{{ $integration['config']['merchant_id'] ?? '' }}" disabled 
                                                class="bg-gray-50 w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm cursor-not-allowed">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <input type="hidden" name="integrations[{{ $integration['id'] }}][enabled]" id="int_input_{{ $integration['id'] }}" value="{{ $integration['enabled'] ? '1' : '0' }}">
                            <button type="button" 
                                onclick="toggleIntegration('{{ $integration['id'] }}')"
                                id="int_btn_{{ $integration['id'] }}"
                                class="inline-flex items-center px-3 py-2 border shadow-sm text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017] {{ $integration['enabled'] ? 'border-transparent text-white bg-green-600 hover:bg-green-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }}">
                                {{ $integration['enabled'] ? 'Connected' : 'Connect' }}
                            </button>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Accounting Software --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Accounting Software</h3>
        <div class="grid gap-4">
            @foreach($settings->integrations as $integration)
                @if($integration['category'] === 'accounting')
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    {{-- Status Icon --}}
                                    @if($integration['status'] === 'active')
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @elseif($integration['status'] === 'error')
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif

                                    <h4 class="text-lg font-semibold text-gray-900">{{ $integration['name'] }}</h4>
                                    <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">
                                        {{ ucfirst($integration['category']) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">
                                    @if($integration['id'] === 'quickbooks') Sync GL accounts, invoices, and journal entries
                                    @elseif($integration['id'] === 'xero') Two-way sync with Xero accounting platform
                                    @endif
                                </p>
                            </div>

                            <input type="hidden" name="integrations[{{ $integration['id'] }}][enabled]" id="int_input_{{ $integration['id'] }}" value="{{ $integration['enabled'] ? '1' : '0' }}">
                            <button type="button" 
                                onclick="toggleIntegration('{{ $integration['id'] }}')"
                                id="int_btn_{{ $integration['id'] }}"
                                class="inline-flex items-center px-3 py-2 border shadow-sm text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017] {{ $integration['enabled'] ? 'border-transparent text-white bg-green-600 hover:bg-green-700' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }}">
                                {{ $integration['enabled'] ? 'Disconnect' : 'Connect' }}
                            </button>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Webhooks --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Webhooks</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="webhooks[enabled]" id="webhook_enabled" value="1"
                        {{ old('webhooks.enabled', $settings->webhooks['enabled'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="webhook_enabled" class="text-sm font-medium text-gray-700 select-none">
                        Enable outgoing webhooks
                    </label>
                </div>

                {{-- Webhook Details --}}
                <div id="webhook-details" class="space-y-4 transition-all duration-300 {{ old('webhooks.enabled', $settings->webhooks['enabled'] ?? false) ? '' : 'hidden opacity-50' }}">
                    <div>
                        <label for="webhook_url" class="block text-sm font-medium text-gray-700 mb-1">Webhook URL</label>
                        <input type="url" name="webhooks[url]" id="webhook_url" 
                            value="{{ old('webhooks.url', $settings->webhooks['url'] ?? '') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                            placeholder="https://your-app.com/webhook">
                    </div>

                    <div>
                        <label for="webhook_secret" class="block text-sm font-medium text-gray-700 mb-1">Webhook Secret</label>
                        <input type="password" name="webhooks[secret]" id="webhook_secret" 
                            value="{{ old('webhooks.secret', $settings->webhooks['secret'] ?? '') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                            placeholder="Enter secret for signature validation">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Events to Send</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($settings->webhooks['events'] ?? [] as $event)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm border border-blue-200">
                                    {{ $event }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions --}}
    <div id="integration-save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetIntegrationForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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
        const form = document.getElementById('integrations-settings-form');
        if (!form) return;

        const saveActionsTop = document.getElementById('integration-save-actions');
        const saveActionsBottom = document.getElementById('integration-save-actions-bottom');
        const inputs = form.querySelectorAll('input');
        
        // Webhook Toggle
        const webhookCheck = document.getElementById('webhook_enabled');
        const webhookDetails = document.getElementById('webhook-details');

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

        // 1. Integration Toggle Logic
        window.toggleIntegration = function(id) {
            const input = document.getElementById('int_input_' + id);
            const btn = document.getElementById('int_btn_' + id);
            
            if (input && btn) {
                const isEnabled = input.value === '1';
                // Toggle Value
                input.value = isEnabled ? '0' : '1';
                
                // Toggle Button State
                if (!isEnabled) { // Becoming Enabled
                    btn.classList.remove('border-gray-300', 'text-gray-700', 'bg-white', 'hover:bg-gray-50');
                    btn.classList.add('border-transparent', 'text-white', 'bg-green-600', 'hover:bg-green-700');
                    btn.textContent = id === 'quickbooks' || id === 'xero' ? 'Disconnect' : 'Connected';
                } else { // Becoming Disabled
                    btn.classList.remove('border-transparent', 'text-white', 'bg-green-600', 'hover:bg-green-700');
                    btn.classList.add('border-gray-300', 'text-gray-700', 'bg-white', 'hover:bg-gray-50');
                    btn.textContent = 'Connect';
                }
                
                checkIntegrationChanges();
            }
        };

        // 2. Webhook Visibility
        function handleWebhookToggle() {
            if (webhookCheck && webhookDetails) {
                if (webhookCheck.checked) {
                    webhookDetails.classList.remove('hidden', 'opacity-50');
                } else {
                    webhookDetails.classList.add('hidden', 'opacity-50');
                }
            }
        }

        // 3. Change Detection
        function checkIntegrationChanges() {
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

        // Listeners
        inputs.forEach(input => {
            input.addEventListener('input', checkIntegrationChanges);
            input.addEventListener('change', checkIntegrationChanges);
        });

        if (webhookCheck) {
            webhookCheck.addEventListener('change', handleWebhookToggle);
        }

        // Global Reset
        window.resetIntegrationForm = function() {
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (!key) return;

                if (input.type === 'checkbox') {
                    input.checked = initialValues[key];
                } else {
                    input.value = initialValues[key];
                }
            });
            
            // Re-render buttons based on reset values
            const integrationInputs = form.querySelectorAll('input[name^="integrations"][name$="[enabled]"]');
            integrationInputs.forEach(inp => {
                // Extract ID from name "integrations[ID][enabled]"
                const idMatch = inp.name.match(/integrations\[(.*?)\]\[enabled\]/);
                if(idMatch && idMatch[1]) {
                    const id = idMatch[1];
                    const btn = document.getElementById('int_btn_' + id);
                    const isEnabled = inp.value === '1';
                    
                    if (isEnabled) {
                        btn.classList.remove('border-gray-300', 'text-gray-700', 'bg-white', 'hover:bg-gray-50');
                        btn.classList.add('border-transparent', 'text-white', 'bg-green-600', 'hover:bg-green-700');
                        btn.textContent = id === 'quickbooks' || id === 'xero' ? 'Disconnect' : 'Connected';
                    } else {
                        btn.classList.remove('border-transparent', 'text-white', 'bg-green-600', 'hover:bg-green-700');
                        btn.classList.add('border-gray-300', 'text-gray-700', 'bg-white', 'hover:bg-gray-50');
                        btn.textContent = 'Connect';
                    }
                }
            });

            handleWebhookToggle();
            checkIntegrationChanges();
        }

    })();
</script>