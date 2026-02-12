{{--
resources/views/settings/notifications.blade.php
--}}
@php
    $settings = (object) [
        'channels' => [
            [
                'id' => 1,
                'type' => 'email',
                'enabled' => true
            ],
            [
                'id' => 2,
                'type' => 'sms',
                'enabled' => true
            ],
            [
                'id' => 3,
                'type' => 'push',
                'enabled' => false
            ]
        ],
        'email_config' => [
            'provider' => 'smtp',
            'from_name' => 'Bakery ERP System',
            'from_address' => 'alerts@bakery.com',
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => '2525',
            'api_key' => ''
        ],
        'sms_config' => [
            'provider' => 'twilio',
            'from_number' => '+15551234567',
            'account_sid' => 'ACxxxxxxxxxxxx',
            'auth_token' => 'xxxxxxxxxxxx'
        ],
        'rules' => [
            [
                'id' => 1,
                'name' => 'Low Stock Alert',
                'event' => 'Inventory < 10%',
                'channels' => ['email', 'push'],
                'recipients' => ['Store Manager'],
                'enabled' => true,
                'schedule' => 'Instant'
            ],
            [
                'id' => 2,
                'name' => 'Day-End Missing',
                'event' => 'Not submitted by 7PM',
                'channels' => ['sms', 'email'],
                'recipients' => ['Owner', 'Manager'],
                'enabled' => true,
                'schedule' => 'Daily at 19:00'
            ],
            [
                'id' => 3,
                'name' => 'Large Order Received',
                'event' => 'Order > $500',
                'channels' => ['push'],
                'recipients' => ['Kitchen Head'],
                'enabled' => false,
                'schedule' => 'Instant'
            ]
        ]
    ];
@endphp

<form id="notifications-settings-form" action="" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- Bell Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Notifications</h2>
                <p class="text-sm text-gray-600">Email, SMS, and alert configuration</p>
            </div>
        </div>

        {{-- Save Actions --}}
        <div id="notification-save-actions" class="hidden items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetNotificationForm()"
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                Cancel
            </button>
            <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                    </path>
                </svg>
                Save Changes
            </button>
        </div>
    </div>

    {{-- Channels --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Channels</h3>
            <div class="space-y-3">
                @foreach($settings->channels as $channel)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">
                                @if($channel['type'] == 'email') 📧
                                @elseif($channel['type'] == 'sms') 📱
                                @else 🔔
                                @endif
                            </span>
                            <div>
                                <p class="font-medium text-gray-900 capitalize">{{ $channel['type'] }}</p>
                                <p class="text-sm text-gray-600">
                                    @if($channel['type'] == 'email') Send email notifications
                                    @elseif($channel['type'] == 'sms') Send SMS text messages
                                    @else Browser push notifications
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Channel Toggle --}}
                        <input type="hidden" name="channels[{{ $channel['id'] }}][enabled]" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="channels[{{ $channel['id'] }}][enabled]" value="1"
                                id="channel_toggle_{{ $channel['type'] }}" data-type="{{ $channel['type'] }}"
                                class="channel-toggle sr-only peer" {{ $channel['enabled'] ? 'checked' : '' }}>

                            {{-- SWITCH TRACK:
                            We use Blade to set the initial color.
                            We added 'toggle-bg' class for JS targeting. --}}
                            <div
                                class="toggle-bg w-11 h-6 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all {{ $channel['enabled'] ? 'bg-green-500' : 'bg-red-500' }}">
                            </div>

                            <span class="ml-3 text-sm font-medium text-gray-700 peer-checked:text-gray-900 status-text">
                                {{ $channel['enabled'] ? 'Enabled' : 'Disabled' }}
                            </span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Email Config --}}
    <div id="email-config-card"
        class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden transition-all duration-300 {{ collect($settings->channels)->firstWhere('type', 'email')['enabled'] ? '' : 'hidden opacity-50' }}">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Configuration</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email_provider" class="block text-sm font-medium text-gray-700 mb-1">Provider</label>
                    <select name="email_config[provider]" id="email_provider"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                        @php $eProvider = $settings->email_config['provider'] ?? 'smtp'; @endphp
                        <option value="smtp" {{ $eProvider == 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="sendgrid" {{ $eProvider == 'sendgrid' ? 'selected' : '' }}>SendGrid</option>
                        <option value="ses" {{ $eProvider == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                    </select>
                </div>

                <div>
                    <label for="from_name" class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
                    <input type="text" name="email_config[from_name]" id="from_name"
                        value="{{ old('email_config.from_name', $settings->email_config['from_name'] ?? 'Your Bakery ERP') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                </div>

                <div class="md:col-span-2">
                    <label for="from_address" class="block text-sm font-medium text-gray-700 mb-1">From Email
                        Address</label>
                    <input type="email" name="email_config[from_address]" id="from_address"
                        value="{{ old('email_config.from_address', $settings->email_config['from_address'] ?? 'noreply@yourbakery.com') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                </div>

                {{-- SMTP Fields --}}
                <div id="smtp-fields" class="contents {{ $eProvider == 'smtp' ? '' : 'hidden' }}">
                    <div>
                        <label for="smtp_host" class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
                        <input type="text" name="email_config[smtp_host]" id="smtp_host"
                            value="{{ old('email_config.smtp_host', $settings->email_config['smtp_host'] ?? '') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                            placeholder="smtp.gmail.com">
                    </div>
                    <div>
                        <label for="smtp_port" class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
                        <input type="number" name="email_config[smtp_port]" id="smtp_port"
                            value="{{ old('email_config.smtp_port', $settings->email_config['smtp_port'] ?? '587') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                    </div>
                </div>

                {{-- API Key Field (Non-SMTP) --}}
                <div id="email-api-field" class="md:col-span-2 {{ $eProvider != 'smtp' ? '' : 'hidden' }}">
                    <label for="email_api_key" class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                    <input type="password" name="email_config[api_key]" id="email_api_key"
                        value="{{ old('email_config.api_key', $settings->email_config['api_key'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="Enter API Key">
                </div>
            </div>
        </div>
    </div>

    {{-- SMS Config --}}
    <div id="sms-config-card"
        class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden transition-all duration-300 {{ collect($settings->channels)->firstWhere('type', 'sms')['enabled'] ? '' : 'hidden opacity-50' }}">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">SMS Configuration</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="sms_provider" class="block text-sm font-medium text-gray-700 mb-1">Provider</label>
                    <select name="sms_config[provider]" id="sms_provider"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                        @php $sProvider = $settings->sms_config['provider'] ?? 'twilio'; @endphp
                        <option value="twilio" {{ $sProvider == 'twilio' ? 'selected' : '' }}>Twilio (International)
                        </option>
                        <option value="dialog" {{ $sProvider == 'dialog' ? 'selected' : '' }}>Dialog (Sri Lanka)</option>
                        <option value="mobitel" {{ $sProvider == 'mobitel' ? 'selected' : '' }}>Mobitel (Sri Lanka)
                        </option>
                    </select>
                </div>

                <div>
                    <label for="sms_from_number" class="block text-sm font-medium text-gray-700 mb-1">From
                        Number</label>
                    <input type="text" name="sms_config[from_number]" id="sms_from_number"
                        value="{{ old('sms_config.from_number', $settings->sms_config['from_number'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="+94771234567">
                </div>

                {{-- Twilio Specifics --}}
                <div id="twilio-fields" class="contents {{ $sProvider == 'twilio' ? '' : 'hidden' }}">
                    <div>
                        <label for="twilio_sid" class="block text-sm font-medium text-gray-700 mb-1">Account SID</label>
                        <input type="text" name="sms_config[account_sid]" id="twilio_sid"
                            value="{{ old('sms_config.account_sid', $settings->sms_config['account_sid'] ?? '') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                    </div>
                    <div>
                        <label for="twilio_token" class="block text-sm font-medium text-gray-700 mb-1">Auth
                            Token</label>
                        <input type="password" name="sms_config[auth_token]" id="twilio_token"
                            value="{{ old('sms_config.auth_token', $settings->sms_config['auth_token'] ?? '') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notification Rules --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Rules</h3>
            <div class="space-y-3">
                @foreach($settings->rules as $rule)
                    <div id="rule-card-{{ $rule['id'] }}"
                        class="p-4 rounded-lg border-2 transition-colors duration-200 {{ $rule['enabled'] ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-medium text-gray-900">{{ $rule['name'] }}</h4>
                                    <span class="text-xs px-2 py-0.5 bg-gray-200 text-gray-700 rounded">
                                        {{ $rule['event'] }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">
                                    Channels: {{ implode(', ', array_map('strtoupper', $rule['channels'])) }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Recipients: {{ implode(', ', $rule['recipients']) }}
                                </p>
                                @if(isset($rule['schedule']))
                                    <p class="text-sm text-gray-600 mt-1">
                                        Schedule: {{ $rule['schedule'] }}
                                    </p>
                                @endif
                            </div>

                            {{-- Rule Toggle --}}
                            <input type="hidden" name="rules[{{ $rule['id'] }}][enabled]" value="0">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="rules[{{ $rule['id'] }}][enabled]" value="1"
                                    id="rule_toggle_{{ $rule['id'] }}" data-rule-id="{{ $rule['id'] }}"
                                    class="rule-toggle sr-only peer" {{ $rule['enabled'] ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                                </div>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions --}}
    <div id="notification-save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetNotificationForm()"
            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
            Cancel
        </button>
        <button type="submit"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                </path>
            </svg>
            Save Changes
        </button>
    </div>

</form>

<script>
    (function () {
        const form = document.getElementById('notifications-settings-form');
        if (!form) return;

        const saveActionsTop = document.getElementById('notification-save-actions');
        const saveActionsBottom = document.getElementById('notification-save-actions-bottom');
        const inputs = form.querySelectorAll('input, select');

        // Channel Logic
        const channelToggles = document.querySelectorAll('.channel-toggle');
        const emailCard = document.getElementById('email-config-card');
        const smsCard = document.getElementById('sms-config-card');

        // Email Provider Logic
        const emailProviderSelect = document.getElementById('email_provider');
        const smtpFields = document.getElementById('smtp-fields');
        const emailApiField = document.getElementById('email-api-field');

        // SMS Provider Logic
        const smsProviderSelect = document.getElementById('sms_provider');
        const twilioFields = document.getElementById('twilio-fields');

        // Rule Logic
        const ruleToggles = document.querySelectorAll('.rule-toggle');

        let initialValues = {};

        const getValue = (input) => {
            if (input.type === 'checkbox') return input.checked;
            return input.value;
        };

        inputs.forEach(input => {
            const key = input.id || input.name;
            if (key) initialValues[key] = getValue(input);
        });

        // 1. Channel Visibility
        function handleChannelToggle(toggle) {
    const type = toggle.dataset.type;
    const container = toggle.parentElement;
    const statusText = container.querySelector('.status-text');
    const toggleBg = container.querySelector('.toggle-bg'); // Select the switch track

    // Update Text
    if(statusText) statusText.textContent = toggle.checked ? 'Enabled' : 'Disabled';

    // Update Color manually to be 100% sure
    if(toggleBg) {
        if(toggle.checked) {
            toggleBg.classList.remove('bg-red-500');
            toggleBg.classList.add('bg-green-500');
        } else {
            toggleBg.classList.remove('bg-green-500');
            toggleBg.classList.add('bg-red-500');
        }
    }

    // Toggle Visibility of Config Cards
    if (type === 'email' && emailCard) {
        if (toggle.checked) emailCard.classList.remove('hidden', 'opacity-50');
        else emailCard.classList.add('hidden', 'opacity-50');
    }
    if (type === 'sms' && smsCard) {
        if (toggle.checked) smsCard.classList.remove('hidden', 'opacity-50');
        else smsCard.classList.add('hidden', 'opacity-50');
    }
}

        // 2. Email Provider Fields
        function updateEmailFields() {
            if (!emailProviderSelect) return;
            if (emailProviderSelect.value === 'smtp') {
                smtpFields.classList.remove('hidden');
                emailApiField.classList.add('hidden');
            } else {
                smtpFields.classList.add('hidden');
                emailApiField.classList.remove('hidden');
            }
        }

        // 3. SMS Provider Fields
        function updateSmsFields() {
            if (!smsProviderSelect) return;
            if (smsProviderSelect.value === 'twilio') {
                twilioFields.classList.remove('hidden');
            } else {
                twilioFields.classList.add('hidden');
            }
        }

        // 4. Rule Visual Update
        function updateRuleCard(toggle) {
            const id = toggle.dataset.ruleId;
            const card = document.getElementById(`rule-card-${id}`);
            if (card) {
                if (toggle.checked) {
                    card.classList.remove('bg-gray-50', 'border-gray-200');
                    card.classList.add('bg-green-50', 'border-green-200');
                } else {
                    card.classList.remove('bg-green-50', 'border-green-200');
                    card.classList.add('bg-gray-50', 'border-gray-200');
                }
            }
        }

        // 5. Check for Changes
        function checkNotifChanges() {
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
            input.addEventListener('input', checkNotifChanges);
            input.addEventListener('change', checkNotifChanges);
        });

        channelToggles.forEach(toggle => {
            toggle.addEventListener('change', () => handleChannelToggle(toggle));
        });

        if (emailProviderSelect) emailProviderSelect.addEventListener('change', updateEmailFields);
        if (smsProviderSelect) smsProviderSelect.addEventListener('change', updateSmsFields);

        ruleToggles.forEach(toggle => {
            toggle.addEventListener('change', () => updateRuleCard(toggle));
        });

        // Global Reset
        window.resetNotificationForm = function () {
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (!key) return;
                if (input.type === 'checkbox') input.checked = initialValues[key];
                else input.value = initialValues[key];
            });

            // Re-apply logic
            channelToggles.forEach(t => handleChannelToggle(t));
            updateEmailFields();
            updateSmsFields();
            ruleToggles.forEach(t => updateRuleCard(t));
            checkNotifChanges();
        }

    })();
</script>