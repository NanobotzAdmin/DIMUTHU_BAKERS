<div class="flex-1 p-6 bg-gray-50 overflow-auto h-full">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Planner Settings</h2>
            <p class="text-sm text-gray-600">Configure scheduling preferences and system defaults</p>
        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-3">
                    <div class="flex items-center gap-3">
                        <i data-lucide="settings" class="w-5 h-5 text-gray-600"></i>
                        <div>
                            <h3 class="font-medium text-gray-900">General</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Basic planner configuration</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none text-gray-700">Default View Mode</label>
                            <select class="flex h-10 w-full items-center justify-between rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="day">Day</option>
                                <option value="3-day" selected>3-Day</option>
                                <option value="week">Week</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none text-gray-700">Timezone</label>
                            <select class="flex h-10 w-full items-center justify-between rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="asia-colombo" selected>Asia/Colombo (UTC+5:30)</option>
                                <option value="utc">UTC</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-3">
                    <div class="flex items-center gap-3">
                        <i data-lucide="clock" class="w-5 h-5 text-gray-600"></i>
                        <div>
                            <h3 class="font-medium text-gray-900">Time & Scheduling</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Configure working hours and time slots</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none text-gray-700">Working Hours Start</label>
                            <input type="time" value="04:00" class="flex h-10 w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium leading-none text-gray-700">Working Hours End</label>
                            <input type="time" value="20:00" class="flex h-10 w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium leading-none text-gray-700">Time Slot Increment</label>
                        <select class="flex h-10 w-full items-center justify-between rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                            <option value="5">5 minutes</option>
                            <option value="15" selected>15 minutes</option>
                            <option value="30">30 minutes</option>
                            <option value="60">1 hour</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-3">
                    <div class="flex items-center gap-3">
                        <i data-lucide="users" class="w-5 h-5 text-gray-600"></i>
                        <div>
                            <h3 class="font-medium text-gray-900">Staff & Permissions</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Manage user access and roles</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-gray-600 italic">
                        Configure in Phase 0.6: Permissions & Access Control
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-3">
                    <div class="flex items-center gap-3">
                        <i data-lucide="bell" class="w-5 h-5 text-gray-600"></i>
                        <div>
                            <h3 class="font-medium text-gray-900">Notifications</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Alert preferences and reminders</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-sm text-gray-600 italic">
                        Configure in Phase 9.3: Notifications & Alerts
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-3">
                    <div class="flex items-center gap-3">
                        <i data-lucide="database" class="w-5 h-5 text-gray-600"></i>
                        <div>
                            <h3 class="font-medium text-gray-900">Data & Integration</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Sync and backup settings</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-100">
                        <div>
                            <div class="text-sm font-medium text-gray-900">Auto-save</div>
                            <div class="text-xs text-gray-500">Automatically save changes every 30 seconds</div>
                        </div>
                        <div class="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded border border-green-200">Enabled</div>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-100">
                        <div>
                            <div class="text-sm font-medium text-gray-900">Local Storage</div>
                            <div class="text-xs text-gray-500">Cache preferences locally</div>
                        </div>
                        <button onclick="clearCache()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-gray-300 bg-white hover:bg-gray-100 h-8 px-3 text-gray-700">
                            Clear Cache
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3 pb-6">
            <button onclick="resetSettings()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border border-gray-300 bg-white hover:bg-gray-100 h-10 px-4 py-2 text-gray-700">
                Reset to Defaults
            </button>
            <button onclick="saveSettings()" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-[#D4A017] hover:bg-[#B8860B] text-white h-10 px-4 py-2">
                Save Settings
            </button>
        </div>

    </div>
</div>


<script>
    // Initialize Icons
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Mock Functions for Buttons
    function saveSettings() {
        // Here you would gather values from inputs using document.getElementById() or similar
        const btn = event.target;
        const originalText = btn.innerText;
        
        btn.innerText = 'Saving...';
        btn.disabled = true;

        setTimeout(() => {
            alert('Settings saved successfully!');
            btn.innerText = originalText;
            btn.disabled = false;
        }, 800);
    }

    function resetSettings() {
        if(confirm('Are you sure you want to reset all settings to default?')) {
            alert('Settings reset to defaults.');
        }
    }

    function clearCache() {
        alert('Local cache cleared.');
    }
</script>