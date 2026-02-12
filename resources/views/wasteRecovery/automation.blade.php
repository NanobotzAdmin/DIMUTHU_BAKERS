@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1600px] mx-auto space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-900">
            <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Waste Recovery Automation
        </h1>
        <p class="text-gray-600 mt-1">
            Automated workflows, rules, and integrations
        </p>
    </div>

    {{-- Statistics Overview --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="pb-3 border-b border-gray-100 mb-3">
                <p class="text-sm font-medium text-gray-500">Active Rules</p>
                <h3 class="text-3xl font-bold text-green-600">{{ $stats['activeRules'] }}</h3>
            </div>
            <p class="text-xs text-gray-500">of {{ $stats['totalRules'] }} total rules</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="pb-3 border-b border-gray-100 mb-3">
                <p class="text-sm font-medium text-gray-500">Rule Executions</p>
                <h3 class="text-3xl font-bold text-blue-600">{{ number_format($stats['totalRuleExecutions']) }}</h3>
            </div>
            <p class="text-xs text-gray-500">Total automated actions</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="pb-3 border-b border-gray-100 mb-3">
                <p class="text-sm font-medium text-gray-500">Active Alerts</p>
                <h3 class="text-3xl font-bold text-orange-600">{{ $stats['activeAlerts'] }}</h3>
            </div>
            <p class="text-xs text-gray-500">Require attention</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="pb-3 border-b border-gray-100 mb-3">
                <p class="text-sm font-medium text-gray-500">Integration Events</p>
                <h3 class="text-3xl font-bold text-purple-600">{{ number_format($stats['processedEvents']) }}</h3>
            </div>
            <p class="text-xs text-gray-500">Processed successfully</p>
        </div>
    </div>

    {{-- Active Alerts --}}
    @if(count($alerts) > 0)
    <div class="bg-orange-50 border-2 border-orange-200 rounded-xl overflow-hidden shadow-sm animate-fade-in">
        <div class="p-4 border-b border-orange-200 bg-orange-100/50">
            <h3 class="text-orange-900 font-bold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Active Alerts ({{ count($alerts) }})
            </h3>
        </div>
        <div class="p-4 space-y-3">
            @foreach($alerts as $alert)
            <div id="alert-{{$alert->id}}" class="bg-white rounded-lg p-4 border-2 border-orange-200 shadow-sm transition-all hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase text-white {{ $alert->severity == 'critical' ? 'bg-red-600' : 'bg-orange-500' }}">
                                {{ strtoupper($alert->severity) }}
                            </span>
                            <span class="font-semibold text-gray-900">{{ $alert->title }}</span>
                            <span class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($alert->createdAt)->format('M d, Y h:i A') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-700 mb-2">{{ $alert->message }}</p>
                        @if($alert->productCode)
                            <p class="text-xs text-gray-500">Product: <span class="font-mono">{{ $alert->productCode }}</span></p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        @if($alert->actionRequired)
                        <button onclick="takeAction('{{ $alert->actionUrl }}')" class="bg-[#D4A017] text-white px-3 py-1.5 rounded text-xs font-bold uppercase hover:bg-[#B8860B] transition-colors shadow-sm">
                            Take Action
                        </button>
                        @endif
                        <button onclick="acknowledgeAlert('{{ $alert->id }}')" class="border border-gray-300 text-gray-700 px-3 py-1.5 rounded text-xs font-bold uppercase hover:bg-gray-50 transition-colors">
                            Acknowledge
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Automation Rules List --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="font-bold text-lg text-gray-900">Automation Rules</h3>
            <p class="text-sm text-gray-500">Configure automated workflows for waste management</p>
        </div>
        <div class="p-6 space-y-3">
            @foreach($rules as $rule)
            <div
                onclick="selectRule('{{ $rule->id }}')"
                id="rule-card-{{ $rule->id }}"
                class="rule-card group border rounded-xl p-4 cursor-pointer transition-all duration-200 hover:shadow-md {{ $rule->isActive ? 'border-2 border-green-200 bg-white' : 'border-gray-200 bg-gray-50' }}"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 rounded-lg transition-colors {{ $rule->isActive ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-500' }}">
                                @if($rule->trigger == 'time-based')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @elseif($rule->trigger == 'event-based')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-semibold text-gray-900">{{ $rule->name }}</h3>
                                    <span class="px-2 py-0.5 rounded border border-gray-200 bg-gray-50 text-[10px] uppercase font-bold text-gray-500">
                                        Priority {{ $rule->priority }}
                                    </span>
                                    <span id="badge-{{ $rule->id }}" class="px-2 py-0.5 rounded text-[10px] uppercase font-bold {{ $rule->isActive ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}">
                                        {{ $rule->isActive ? '✓ Active' : '○ Inactive' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-1">{{ $rule->description }}</p>
                            </div>
                        </div>

                        {{-- Collapsed Details --}}
                        <div class="mt-3 pl-14 space-y-2 hidden md:block">
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="text-gray-500 flex items-center">Trigger:
                                    <span class="ml-1 px-1.5 py-0.5 rounded border bg-white font-mono text-gray-700 capitalize">
                                        {{ str_replace('-', ' ', $rule->trigger) }}
                                    </span>
                                </span>
                                <span class="text-gray-500 flex items-center">Conditions:
                                    @foreach($rule->conditions as $cond)
                                        <span class="ml-1 px-1.5 py-0.5 rounded border bg-white font-mono text-gray-700">
                                            {{ $cond['type'] }} {{ $cond['operator'] }} {{ $cond['value'] }}
                                        </span>
                                    @endforeach
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-2 items-end">
                        <div class="flex items-center gap-2" onclick="event.stopPropagation()">
                             {{-- Switch Component --}}
                            <button
                                type="button"
                                onclick="toggleRule('{{ $rule->id }}')"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $rule->isActive ? 'bg-green-600' : 'bg-gray-200' }}"
                                role="switch"
                                aria-checked="{{ $rule->isActive ? 'true' : 'false' }}"
                            >
                                <span
                                    aria-hidden="true"
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $rule->isActive ? 'translate-x-5' : 'translate-x-0' }}"
                                ></span>
                            </button>
                            <span id="status-text-{{ $rule->id }}" class="text-xs font-medium text-gray-500 w-8">
                                {{ $rule->isActive ? 'On' : 'Off' }}
                            </span>
                        </div>
                        <button
                            onclick="executeRule('{{ $rule->id }}'); event.stopPropagation();"
                            class="flex items-center gap-1 text-xs font-bold text-gray-600 hover:text-blue-600 transition-colors py-1 px-2 rounded hover:bg-gray-100"
                        >
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            Run Now
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Selected Rule Detail Panel --}}
    <div id="rule-detail-panel" class="hidden transition-all duration-300 ease-in-out">
        <div class="bg-white border-2 border-[#D4A017] rounded-xl shadow-lg overflow-hidden">
            <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-200 flex justify-between items-start">
                <div>
                    <h3 class="flex items-center gap-2 text-lg font-bold text-gray-900">
                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Rule Details: <span id="detail-title"></span>
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Detailed configuration and execution history</p>
                </div>
                <button onclick="closePanel()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-bold text-gray-900 mb-3">Rule Information</h4>
                            <div class="space-y-2 text-sm bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <div class="flex justify-between"><span class="text-gray-500">Rule ID:</span><span id="detail-id" class="font-mono text-gray-900"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Status:</span><span id="detail-status"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Priority:</span><span id="detail-priority" class="text-gray-900"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Trigger Type:</span><span id="detail-trigger" class="capitalize text-gray-900"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Created By:</span><span id="detail-createdBy" class="text-gray-900"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Created At:</span><span id="detail-createdAt" class="text-gray-900"></span></div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 mb-3">Conditions</h4>
                            <div id="detail-conditions" class="space-y-2"></div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-bold text-gray-900 mb-3">Actions</h4>
                            <div id="detail-actions" class="space-y-2"></div>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-900 mb-3">Execution History</h4>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100 space-y-2 text-sm">
                                <div class="flex justify-between"><span class="text-gray-500">Total Executions:</span><span id="detail-executions" class="font-bold text-gray-900"></span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Last Executed:</span><span id="detail-lastExecuted" class="text-gray-900"></span></div>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button id="detail-execute-btn" class="flex-1 bg-[#D4A017] hover:bg-[#B8860B] text-white py-2 px-4 rounded-lg font-bold shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                Execute Now
                            </button>
                            <button id="detail-toggle-btn" class="flex-1 border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 py-2 px-4 rounded-lg font-bold transition-all active:scale-95 flex items-center justify-center gap-2">
                                {{-- Icon and Text set dynamically --}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Integration Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <div class="mb-4">
                <h3 class="font-bold text-lg text-gray-900">Integration Status</h3>
                <p class="text-sm text-gray-500">Connected modules and data flow</p>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>
                            <p class="font-medium text-gray-900">Production Module</p>
                            <p class="text-xs text-gray-600">Auto-creates tracking records</p>
                        </div>
                    </div>
                    <span class="bg-green-100 text-green-800 text-[10px] uppercase font-bold px-2 py-1 rounded">Connected</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>
                            <p class="font-medium text-gray-900">Inventory Module</p>
                            <p class="text-xs text-gray-600">Syncs waste inventory levels</p>
                        </div>
                    </div>
                    <span class="bg-green-100 text-green-800 text-[10px] uppercase font-bold px-2 py-1 rounded">Connected</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>
                            <p class="font-medium text-gray-900">Financial Module</p>
                            <p class="text-xs text-gray-600">Auto-generates journal entries</p>
                        </div>
                    </div>
                    <span class="bg-green-100 text-green-800 text-[10px] uppercase font-bold px-2 py-1 rounded">Connected</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <div>
                            <p class="font-medium text-gray-900">POS System</p>
                            <p class="text-xs text-gray-600">Day-old sales tracking</p>
                        </div>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-[10px] uppercase font-bold px-2 py-1 rounded">Pending</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <div class="mb-4">
                <h3 class="font-bold text-lg text-gray-900">Automation Benefits</h3>
                <p class="text-sm text-gray-500">Time and cost savings from automation</p>
            </div>
            
            <div class="space-y-4">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center gap-2 mb-2 text-blue-900">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        <h4 class="font-semibold">Time Savings</h4>
                    </div>
                    <p class="text-2xl font-bold text-blue-900 mb-1">~2 hours/day</p>
                    <p class="text-sm text-blue-700">Automated stage transitions and journal entries eliminate manual data entry</p>
                </div>

                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center gap-2 mb-2 text-green-900">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <h4 class="font-semibold">Error Reduction</h4>
                    </div>
                    <p class="text-2xl font-bold text-green-900 mb-1">95%</p>
                    <p class="text-sm text-green-700">Automated workflows reduce human error in waste tracking and accounting</p>
                </div>

                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                    <div class="flex items-center gap-2 mb-2 text-purple-900">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        <h4 class="font-semibold">Response Time</h4>
                    </div>
                    <p class="text-2xl font-bold text-purple-900 mb-1">Real-time</p>
                    <p class="text-sm text-purple-700">Instant alerts and actions ensure timely waste processing decisions</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize data from PHP
    const rules = @json($rules);
    let selectedRuleId = null;

    function toggleRule(id) {
        // Find rule and toggle state
        const rule = rules.find(r => r.id === id);
        if (rule) {
            rule.isActive = !rule.isActive;
            
            // Visual Update in List
            const switchBtn = document.querySelector(`#rule-card-${id} button[role="switch"]`);
            const switchNode = switchBtn.querySelector('span');
            const statusText = document.getElementById(`status-text-${id}`);
            const badge = document.getElementById(`badge-${id}`);
            const card = document.getElementById(`rule-card-${id}`);

            if (rule.isActive) {
                switchBtn.classList.remove('bg-gray-200');
                switchBtn.classList.add('bg-green-600');
                switchBtn.setAttribute('aria-checked', 'true');
                switchNode.classList.remove('translate-x-0');
                switchNode.classList.add('translate-x-5');
                statusText.innerText = 'On';
                
                badge.className = 'px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-green-100 text-green-800';
                badge.innerText = '✓ Active';
                
                card.classList.remove('bg-gray-50', 'border-gray-200');
                card.classList.add('bg-white', 'border-green-200');
            } else {
                switchBtn.classList.remove('bg-green-600');
                switchBtn.classList.add('bg-gray-200');
                switchBtn.setAttribute('aria-checked', 'false');
                switchNode.classList.remove('translate-x-5');
                switchNode.classList.add('translate-x-0');
                statusText.innerText = 'Off';

                badge.className = 'px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-gray-200 text-gray-600';
                badge.innerText = '○ Inactive';

                card.classList.remove('bg-white', 'border-green-200');
                card.classList.add('bg-gray-50', 'border-gray-200');
            }

            // Update Detail Pane if this rule is selected
            if (selectedRuleId === id) {
                updateDetailPanel(rule);
            }

            // Mock Toast
            const msg = rule.isActive ? 'Rule activated successfully' : 'Rule deactivated';
            const color = rule.isActive ? '#10B981' : '#6B7280';
            
            // If we had Swal
            if(typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: rule.isActive ? 'success' : 'info',
                    title: msg
                });
            } else {
                console.log(msg); // Fallback
            }
        }
    }

    function executeRule(id) {
        const rule = rules.find(r => r.id === id);
        if(!rule) return;

        // Visual feedback
        if(typeof Swal !== 'undefined') {
             Swal.fire({
                title: 'Executing Rule',
                text: `Running workflow: ${rule.name}...`,
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
             }).then(() => {
                 Swal.fire({
                    title: 'Success',
                    text: 'Rule executed manually',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                 });
                 // Mock update LAST EXECUTION
                 rule.lastExecuted = new Date().toISOString();
                 rule.executionCount++;
                 
                 // Update detail panel if open
                 if(selectedRuleId === id) {
                     updateDetailPanel(rule);
                 }
             });
        }
    }

    function selectRule(id) {
        selectedRuleId = id;
        const rule = rules.find(r => r.id === id);
        
        // Highlight Card logic
        document.querySelectorAll('.rule-card').forEach(c => {
            c.classList.remove('ring-2', 'ring-[#D4A017]', 'bg-yellow-50');
            // Re-apply original bg logic if needed, but simplest is just remove ring
        });
        
        // Add highlight
        const card = document.getElementById(`rule-card-${id}`);
        // We need to keep the green border reference but override bg
        card.classList.add('ring-2', 'ring-[#D4A017]');
        
        updateDetailPanel(rule);
        
        // Show panel
        const panel = document.getElementById('rule-detail-panel');
        panel.classList.remove('hidden');
        // Scroll to panel
        panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function updateDetailPanel(rule) {
        document.getElementById('detail-title').innerText = rule.name;
        document.getElementById('detail-id').innerText = rule.id;
        document.getElementById('detail-priority').innerText = rule.priority;
        document.getElementById('detail-trigger').innerText = rule.trigger.replace('-', ' ');
        document.getElementById('detail-createdBy').innerText = rule.createdBy;
        document.getElementById('detail-createdAt').innerText = new Date(rule.createdAt).toLocaleDateString();

        const statusSpan = document.getElementById('detail-status');
        if(rule.isActive) {
             statusSpan.className = 'px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-green-100 text-green-800';
             statusSpan.innerText = 'Active';
        } else {
             statusSpan.className = 'px-2 py-0.5 rounded text-[10px] uppercase font-bold bg-gray-200 text-gray-600';
             statusSpan.innerText = 'Inactive';
        }

        // Conditions
        const condContainer = document.getElementById('detail-conditions');
        condContainer.innerHTML = rule.conditions.map(c => `
             <div class="bg-white border rounded p-3 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium capitalize text-gray-700">${c.type}</span>
                <span class="text-gray-500">${c.operator}</span>
                <span class="font-mono font-bold text-gray-900">${c.value}</span>
             </div>
        `).join('');

        // Actions
        const actContainer = document.getElementById('detail-actions');
        actContainer.innerHTML = rule.actions.map(a => `
             <div class="bg-blue-50 rounded p-3 text-sm border border-blue-100">
                <div class="flex items-center gap-2 mb-1">
                   <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                   <span class="font-bold text-blue-900 capitalize">${a.type.replace('-', ' ')}</span>
                </div>
                ${a.targetStage ? `<p class="text-xs text-blue-800 ml-6">Target: <span class="font-semibold">${a.targetStage}</span></p>` : ''}
                ${a.alertRecipients ? `<p class="text-xs text-blue-800 ml-6">Recipients: ${a.alertRecipients.join(', ')}</p>` : ''}
             </div>
        `).join('');

        // Execution
        document.getElementById('detail-executions').innerText = rule.executionCount;
        document.getElementById('detail-lastExecuted').innerText = rule.lastExecuted ? new Date(rule.lastExecuted).toLocaleString() : 'Never';

        // Buttons
        const toggleBtn = document.getElementById('detail-toggle-btn');
        toggleBtn.onclick = () => toggleRule(rule.id);
        if(rule.isActive) {
            toggleBtn.innerHTML = `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Deactivate`;
        } else {
            toggleBtn.innerHTML = `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Activate`;
        }
        
        document.getElementById('detail-execute-btn').onclick = () => executeRule(rule.id);
    }

    function closePanel() {
        document.getElementById('rule-detail-panel').classList.add('hidden');
        selectedRuleId = null;
        // remove highlights
         document.querySelectorAll('.rule-card').forEach(c => {
            c.classList.remove('ring-2', 'ring-[#D4A017]');
        });
    }

    function acknowledgeAlert(id) {
        document.getElementById(`alert-${id}`).remove();
        if(typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: 'Alert acknowledged'
            });
        }
        
        // Check if no alerts left
        const remaining = document.querySelectorAll('[id^="alert-"]').length;
        if(remaining === 0) {
            // Remove parent container logic if needed, but for now just leave empty or hide
        }
    }
    
    function takeAction(url) {
        if(url && url !== '#') {
            window.location.href = url;
        } else {
            console.log('Action triggered');
        }
    }
</script>
@endsection