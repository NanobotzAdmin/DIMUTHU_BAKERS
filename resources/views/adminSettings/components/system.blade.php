{{-- 
    resources/views/settings/system.blade.php 
--}}

<form id="system-settings-form" action="" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- Settings Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">System Configuration</h2>
                <p class="text-sm text-gray-600">Backup, security, performance, and maintenance</p>
            </div>
        </div>

        {{-- Save Actions --}}
        <div id="system-save-actions" class="hidden items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetSystemForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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

    {{-- Backup Settings --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Backup & Recovery</h3>
                <button type="button" onclick="triggerManualBackup()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Backup Now
                </button>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="backup[enabled]" id="backup_enabled" value="1"
                        {{ old('backup.enabled', $settings->backup['enabled'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="backup_enabled" class="text-sm font-medium text-gray-700 select-none">
                        Enable automatic backups
                    </label>
                </div>

                {{-- Conditional Backup Settings --}}
                <div id="backup-settings" class="grid grid-cols-1 md:grid-cols-2 gap-4 transition-all duration-300 {{ old('backup.enabled', $settings->backup['enabled'] ?? false) ? '' : 'hidden opacity-50' }}">
                    <div>
                        <label for="backup_frequency" class="block text-sm font-medium text-gray-700 mb-1">Backup Frequency</label>
                        <select name="backup[frequency]" id="backup_frequency" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            @php $freq = $settings->backup['frequency'] ?? 'daily'; @endphp
                            <option value="daily" {{ $freq == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $freq == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ $freq == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>

                    <div>
                        <label for="backup_retention" class="block text-sm font-medium text-gray-700 mb-1">Retention (days)</label>
                        <input type="number" name="backup[retention_days]" id="backup_retention" 
                            value="{{ old('backup.retention_days', $settings->backup['retention_days'] ?? '30') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                    </div>

                    <div>
                        <label for="backup_location" class="block text-sm font-medium text-gray-700 mb-1">Backup Location</label>
                        <select name="backup[location]" id="backup_location" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            @php $loc = $settings->backup['location'] ?? 'local'; @endphp
                            <option value="local" {{ $loc == 'local' ? 'selected' : '' }}>Local Storage</option>
                            <option value="s3" {{ $loc == 's3' ? 'selected' : '' }}>Amazon S3</option>
                            <option value="cloud" {{ $loc == 'cloud' ? 'selected' : '' }}>Cloud Storage</option>
                        </select>
                    </div>

                    @if(!empty($settings->backup['last_backup']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Backup</label>
                            <input type="text" value="{{ \Carbon\Carbon::parse($settings->backup['last_backup'])->format('Y-m-d H:i:s') }}" disabled 
                                class="w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 shadow-sm sm:text-sm cursor-not-allowed">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Security Settings --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Security</h3>
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-1">Session Timeout (minutes)</label>
                        <input type="number" name="security[session_timeout]" id="session_timeout" 
                            value="{{ old('security.session_timeout', $settings->security['session_timeout'] ?? '120') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                    </div>

                    <div class="flex items-center gap-2 md:pt-6">
                        <input type="checkbox" name="security[two_factor_auth]" id="two_factor_auth" value="1"
                            {{ old('security.two_factor_auth', $settings->security['two_factor_auth'] ?? false) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                        <label for="two_factor_auth" class="text-sm text-gray-700 select-none">
                            Require two-factor authentication
                        </label>
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Password Policy</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="pw_min_length" class="block text-sm font-medium text-gray-700 mb-1">Minimum Length</label>
                            <input type="number" name="security[password_policy][min_length]" id="pw_min_length" 
                                value="{{ old('security.password_policy.min_length', $settings->security['password_policy']['min_length'] ?? '8') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="security[password_policy][require_uppercase]" id="pw_uppercase" value="1"
                                    {{ old('security.password_policy.require_uppercase', $settings->security['password_policy']['require_uppercase'] ?? false) ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                                <label for="pw_uppercase" class="text-sm text-gray-700 select-none">Require uppercase</label>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="security[password_policy][require_numbers]" id="pw_numbers" value="1"
                                    {{ old('security.password_policy.require_numbers', $settings->security['password_policy']['require_numbers'] ?? false) ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                                <label for="pw_numbers" class="text-sm text-gray-700 select-none">Require numbers</label>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="security[password_policy][require_special_chars]" id="pw_special" value="1"
                                    {{ old('security.password_policy.require_special_chars', $settings->security['password_policy']['require_special_chars'] ?? false) ? 'checked' : '' }}
                                    class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                                <label for="pw_special" class="text-sm text-gray-700 select-none">Require special characters</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Performance --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="performance[cache_enabled]" id="cache_enabled" value="1"
                        {{ old('performance.cache_enabled', $settings->performance['cache_enabled'] ?? false) ? 'checked' : '' }}
                        onchange="toggleCacheTTL(this.checked)"
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="cache_enabled" class="text-sm font-medium text-gray-700 select-none">
                        Enable caching
                    </label>
                </div>

                <div>
                    <label for="cache_ttl" class="block text-sm font-medium text-gray-700 mb-1">Cache TTL (seconds)</label>
                    <input type="number" name="performance[cache_ttl]" id="cache_ttl" 
                        value="{{ old('performance.cache_ttl', $settings->performance['cache_ttl'] ?? '3600') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm disabled:bg-gray-100 disabled:text-gray-400 p-2"
                        {{ old('performance.cache_enabled', $settings->performance['cache_enabled'] ?? false) ? '' : 'disabled' }}>
                </div>

                <div>
                    <label for="log_level" class="block text-sm font-medium text-gray-700 mb-1">Log Level</label>
                    <select name="performance[log_level]" id="log_level" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                        @php $log = $settings->performance['log_level'] ?? 'error'; @endphp
                        <option value="error" {{ $log == 'error' ? 'selected' : '' }}>Error</option>
                        <option value="warn" {{ $log == 'warn' ? 'selected' : '' }}>Warning</option>
                        <option value="info" {{ $log == 'info' ? 'selected' : '' }}>Info</option>
                        <option value="debug" {{ $log == 'debug' ? 'selected' : '' }}>Debug</option>
                    </select>
                </div>

                <div>
                    <label for="max_upload_size" class="block text-sm font-medium text-gray-700 mb-1">Max Upload Size (MB)</label>
                    <input type="number" name="performance[max_upload_size]" id="max_upload_size" 
                        value="{{ old('performance.max_upload_size', $settings->performance['max_upload_size'] ?? '10') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                </div>
            </div>
        </div>
    </div>

    {{-- Maintenance Mode --}}
    <div class="bg-red-50/30 rounded-lg border-2 border-red-200 overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">Maintenance Mode</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Enable maintenance mode to prevent user access during system updates
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="maintenance[mode]" id="maintenance_mode" value="1"
                        {{ old('maintenance.mode', $settings->maintenance['mode'] ?? false) ? 'checked' : '' }}
                        class="w-5 h-5 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="maintenance_mode" class="text-sm font-medium text-gray-700 select-none">
                        Enable
                    </label>
                </div>
            </div>

            <div id="maintenance-settings" class="space-y-4 transition-all duration-300 {{ old('maintenance.mode', $settings->maintenance['mode'] ?? false) ? '' : 'hidden opacity-50' }}">
                <div>
                    <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-1">Maintenance Message</label>
                    <textarea name="maintenance[message]" id="maintenance_message" rows="3"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">{{ old('maintenance.message', $settings->maintenance['message'] ?? 'We are currently performing scheduled maintenance. Please check back later.') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Allowed IP Addresses</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($settings->maintenance['allowed_ips'] ?? [] as $ip)
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm border border-gray-200">
                                {{ $ip }}
                            </span>
                        @endforeach
                    </div>
                    {{-- Note: A real implementation would allow adding IPs dynamically here --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions --}}
    <div id="system-save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetSystemForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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
        const form = document.getElementById('system-settings-form');
        if (!form) return;

        const saveActionsTop = document.getElementById('system-save-actions');
        const saveActionsBottom = document.getElementById('system-save-actions-bottom');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        // Toggle Elements
        const backupCheck = document.getElementById('backup_enabled');
        const backupSettings = document.getElementById('backup-settings');
        
        const maintenanceCheck = document.getElementById('maintenance_mode');
        const maintenanceSettings = document.getElementById('maintenance-settings');

        const cacheCheck = document.getElementById('cache_enabled');
        const cacheTTL = document.getElementById('cache_ttl');

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

        // 1. Toggles Logic
        function handleToggle(check, target) {
            if (check && target) {
                if (check.checked) {
                    target.classList.remove('hidden', 'opacity-50');
                } else {
                    target.classList.add('hidden', 'opacity-50');
                }
            }
        }

        // 2. Cache TTL Toggle (Exposed to window for onchange attribute)
        window.toggleCacheTTL = function(isEnabled) {
            if(cacheTTL) {
                cacheTTL.disabled = !isEnabled;
                if(!isEnabled) {
                    cacheTTL.classList.add('bg-gray-100', 'text-gray-400');
                } else {
                    cacheTTL.classList.remove('bg-gray-100', 'text-gray-400');
                }
            }
            checkSystemChanges();
        };

        // 3. Backup Simulation
        window.triggerManualBackup = function() {
            // In a real app, this would be an AJAX call
            const btn = event.currentTarget; // Careful with 'event'
            const originalText = btn.innerHTML;
            
            btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Backing up...`;
            btn.disabled = true;

            setTimeout(() => {
                btn.innerHTML = `<svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Done!`;
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 2000);
            }, 1500);
        };

        // 4. Change Detection
        function checkSystemChanges() {
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
            input.addEventListener('input', checkSystemChanges);
            input.addEventListener('change', checkSystemChanges);
        });

        if (backupCheck) {
            backupCheck.addEventListener('change', () => handleToggle(backupCheck, backupSettings));
        }
        if (maintenanceCheck) {
            maintenanceCheck.addEventListener('change', () => handleToggle(maintenanceCheck, maintenanceSettings));
        }

        // Global Reset
        window.resetSystemForm = function() {
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (!key) return;

                if (input.type === 'checkbox') {
                    input.checked = initialValues[key];
                } else {
                    input.value = initialValues[key];
                }
            });
            
            handleToggle(backupCheck, backupSettings);
            handleToggle(maintenanceCheck, maintenanceSettings);
            toggleCacheTTL(cacheCheck ? cacheCheck.checked : false);
            checkSystemChanges();
        }

        // Initial Run
        handleToggle(backupCheck, backupSettings);
        handleToggle(maintenanceCheck, maintenanceSettings);

    })();
</script>