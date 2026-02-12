{{-- 
    resources/views/settings/dayend.blade.php 
--}}
@php
    $settings = (object) [
        'tasks' => [
            [
                'id' => 1,
                'order' => 1,
                'name' => 'Cash Register Reconciliation',
                'description' => 'Count physical cash and verify against POS system totals.',
                'required' => true,
                'default_owner_role' => 'cashier',
                'deadline_minutes' => 15
            ],
            [
                'id' => 2,
                'order' => 2,
                'name' => 'Wastage Recording',
                'description' => 'Weigh and record all unsold perishable items.',
                'required' => true,
                'default_owner_role' => 'store_manager',
                'deadline_minutes' => 20
            ],
            [
                'id' => 3,
                'order' => 3,
                'name' => 'Equipment Shutdown',
                'description' => 'Ensure ovens, mixers, and warmers are turned off safely.',
                'required' => false,
                'default_owner_role' => 'kitchen_staff',
                'deadline_minutes' => 10
            ],
            [
                'id' => 4,
                'order' => 4,
                'name' => 'Floor Cleaning',
                'description' => 'Sweep and mop the production and customer areas.',
                'required' => false,
                'default_owner_role' => 'cleaner',
                'deadline_minutes' => 30
            ],
        ],
        'approval_workflow' => [
            'require_approval' => true,
            'approver_role' => 'finance_manager',
            'auto_lock_timing' => 'immediate'
        ],
        'notifications' => [
            'reminder_time' => '18:30',
            'escalate_time' => '19:30',
            'recipients' => ['manager@bakery.com', 'owner@bakery.com']
        ]
    ];
@endphp

<form id="dayend-settings-form" action="" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- Lock Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Day-End Configuration</h2>
                <p class="text-sm text-gray-600">Customize the 14-task day-end checklist and workflow</p>
            </div>
        </div>

        {{-- Save Actions --}}
        <div id="dayend-save-actions" class="hidden items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetDayEndForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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

    {{-- Summary Card --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h3 class="font-semibold text-blue-900">Day-End Task Summary</h3>
                <p class="text-sm text-blue-800 mt-1">
                    <strong id="summary-required-count">0</strong> required tasks, <strong id="summary-optional-count">0</strong> optional tasks
                </p>
                <p class="text-sm text-blue-700 mt-1">
                    Total estimated time: <strong id="summary-total-time">0 minutes</strong>
                </p>
            </div>
        </div>
    </div>

    {{-- Task Checklist --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Task Checklist Configuration</h3>
            <div class="space-y-3">
                @php
                    // Sort tasks by order
                    $tasks = collect($settings->tasks)->sortBy('order');
                @endphp

                @foreach($tasks as $task)
                <div id="task-card-{{ $task['id'] }}" class="border rounded-lg p-4 transition-colors duration-200 {{ $task['required'] ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-start gap-4">
                        {{-- Order Number --}}
                        <div class="flex-shrink-0">
                            <div id="task-badge-{{ $task['id'] }}" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $task['required'] ? 'bg-red-500 text-white' : 'bg-gray-400 text-white' }}">
                                {{ $task['order'] }}
                            </div>
                        </div>

                        {{-- Task Details --}}
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <div class="font-medium text-gray-900 mb-1">{{ $task['name'] }}</div>
                                <p class="text-sm text-gray-600">{{ $task['description'] }}</p>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="text-xs text-gray-500">
                                        Owner: <span class="font-medium">{{ ucwords(str_replace('_', ' ', $task['default_owner_role'])) }}</span>
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        Deadline: <span class="font-medium">{{ $task['deadline_minutes'] }} min</span>
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label for="deadline_{{ $task['id'] }}" class="text-xs text-gray-600 font-medium">Deadline (min)</label>
                                <input type="number" name="tasks[{{ $task['id'] }}][deadline_minutes]" id="deadline_{{ $task['id'] }}"
                                    value="{{ old("tasks.{$task['id']}.deadline_minutes", $task['deadline_minutes']) }}"
                                    class="task-deadline-input h-8 w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] text-sm p-2">
                                
                                <div class="flex items-center gap-2 mt-1">
                                    <input type="hidden" name="tasks[{{ $task['id'] }}][required]" value="0"> {{-- Fallback --}}
                                    <input type="checkbox" name="tasks[{{ $task['id'] }}][required]" id="required_{{ $task['id'] }}" value="1"
                                        {{ old("tasks.{$task['id']}.required", $task['required']) ? 'checked' : '' }}
                                        data-task-id="{{ $task['id'] }}"
                                        class="task-required-checkbox w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                                    <label for="required_{{ $task['id'] }}" class="text-xs text-gray-700 select-none">
                                        Required
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Approval Workflow --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Approval Workflow</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center gap-2 h-10">
                    <input type="checkbox" name="approval_workflow[require_approval]" id="require_approval" value="1"
                        {{ old('approval_workflow.require_approval', $settings->approval_workflow['require_approval'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="require_approval" class="text-sm text-gray-700 font-medium select-none">
                        Require manager approval to close day-end
                    </label>
                </div>

                <div id="approval-options" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 transition-all duration-300 {{ old('approval_workflow.require_approval', $settings->approval_workflow['require_approval'] ?? false) ? '' : 'hidden opacity-50' }}">
                    <div>
                        <label for="approver_role" class="block text-sm font-medium text-gray-700 mb-1">Approver Role</label>
                        <select name="approval_workflow[approver_role]" id="approver_role" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            @php $role = $settings->approval_workflow['approver_role'] ?? 'store_manager'; @endphp
                            <option value="finance_manager" {{ $role == 'finance_manager' ? 'selected' : '' }}>Finance Manager</option>
                            <option value="store_manager" {{ $role == 'store_manager' ? 'selected' : '' }}>Store Manager</option>
                            <option value="super_admin" {{ $role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>

                    <div>
                        <label for="auto_lock_timing" class="block text-sm font-medium text-gray-700 mb-1">Auto-Lock Timing</label>
                        <select name="approval_workflow[auto_lock_timing]" id="auto_lock_timing" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            @php $timing = $settings->approval_workflow['auto_lock_timing'] ?? 'immediate'; @endphp
                            <option value="immediate" {{ $timing == 'immediate' ? 'selected' : '' }}>Immediate (upon approval)</option>
                            <option value="next_business_day" {{ $timing == 'next_business_day' ? 'selected' : '' }}>Next Business Day</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifications --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notifications & Reminders</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="reminder_time" class="block text-sm font-medium text-gray-700 mb-1">Reminder Time</label>
                    <input type="time" name="notifications[reminder_time]" id="reminder_time" 
                        value="{{ old('notifications.reminder_time', $settings->notifications['reminder_time'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                    <p class="text-xs text-gray-500 mt-1">Send reminder to start day-end process</p>
                </div>

                <div>
                    <label for="escalate_time" class="block text-sm font-medium text-gray-700 mb-1">Escalation Time</label>
                    <input type="time" name="notifications[escalate_time]" id="escalate_time" 
                        value="{{ old('notifications.escalate_time', $settings->notifications['escalate_time'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                    <p class="text-xs text-gray-500 mt-1">Escalate to manager if not started</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Recipients</label>
                    <div class="flex flex-wrap gap-2">
                        @if(isset($settings->notifications['recipients']))
                            @foreach($settings->notifications['recipients'] as $email)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm border border-gray-200">
                                    {{ $email }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions --}}
    <div id="dayend-save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetDayEndForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
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
        const form = document.getElementById('dayend-settings-form');
        if (!form) return;

        const saveActionsTop = document.getElementById('dayend-save-actions');
        const saveActionsBottom = document.getElementById('dayend-save-actions-bottom');
        const inputs = form.querySelectorAll('input, select');
        
        // Summary Elements
        const summaryRequired = document.getElementById('summary-required-count');
        const summaryOptional = document.getElementById('summary-optional-count');
        const summaryTime = document.getElementById('summary-total-time');

        // Approval Toggle Elements
        const approvalCheck = document.getElementById('require_approval');
        const approvalOptions = document.getElementById('approval-options');

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

        // 1. Live Summary Calculation
        function updateSummary() {
            const requiredChecks = document.querySelectorAll('.task-required-checkbox');
            const deadlineInputs = document.querySelectorAll('.task-deadline-input');
            
            let reqCount = 0;
            let totalTasks = requiredChecks.length;
            let totalMinutes = 0;

            requiredChecks.forEach(cb => {
                if (cb.checked) reqCount++;
                
                // Update styling of the row based on state
                const taskId = cb.dataset.taskId;
                const card = document.getElementById(`task-card-${taskId}`);
                const badge = document.getElementById(`task-badge-${taskId}`);
                
                if(card && badge) {
                    if(cb.checked) {
                        card.classList.remove('bg-gray-50', 'border-gray-200');
                        card.classList.add('bg-red-50', 'border-red-200');
                        badge.classList.remove('bg-gray-400');
                        badge.classList.add('bg-red-500');
                    } else {
                        card.classList.remove('bg-red-50', 'border-red-200');
                        card.classList.add('bg-gray-50', 'border-gray-200');
                        badge.classList.remove('bg-red-500');
                        badge.classList.add('bg-gray-400');
                    }
                }
            });

            deadlineInputs.forEach(inp => {
                totalMinutes += parseInt(inp.value) || 0;
            });

            if(summaryRequired) summaryRequired.textContent = reqCount;
            if(summaryOptional) summaryOptional.textContent = totalTasks - reqCount;
            if(summaryTime) summaryTime.textContent = `${totalMinutes} minutes`;
        }

        // 2. Approval Workflow Toggle
        function handleApprovalToggle() {
            if (approvalCheck && approvalOptions) {
                if (approvalCheck.checked) {
                    approvalOptions.classList.remove('hidden', 'opacity-50');
                } else {
                    approvalOptions.classList.add('hidden', 'opacity-50');
                }
            }
        }

        // 3. Change Detection
        function checkDayEndChanges() {
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
            input.addEventListener('input', () => {
                checkDayEndChanges();
                if(input.classList.contains('task-deadline-input')) updateSummary();
            });
            input.addEventListener('change', () => {
                checkDayEndChanges();
                if(input.classList.contains('task-required-checkbox')) updateSummary();
            });
        });

        if (approvalCheck) {
            approvalCheck.addEventListener('change', handleApprovalToggle);
        }

        // Global Reset
        window.resetDayEndForm = function() {
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (!key) return;

                if (input.type === 'checkbox') {
                    input.checked = initialValues[key];
                } else {
                    input.value = initialValues[key];
                }
            });
            handleApprovalToggle();
            updateSummary();
            checkDayEndChanges();
        }

        // Initial Run
        updateSummary();
        handleApprovalToggle();

    })();
</script>