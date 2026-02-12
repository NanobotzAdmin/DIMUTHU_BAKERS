@extends('layouts.app')

@section('content')
<div id="dayEndProcessContainer" class="p-8 max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Day-End Process</h1>
            <p class="text-gray-600 mt-1">
                {{ date('l, F j, Y', strtotime($today)) }} • {{ $location }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            @if($isLocked)
                <span class="inline-flex items-center px-4 py-2 border border-red-200 bg-red-50 text-red-700 rounded-full text-sm font-bold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Day Locked
                </span>
                <button onclick="openUnlockModal()" class="flex items-center gap-2 text-sm font-bold border px-4 py-2 rounded-lg bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    Unlock Day
                </button>
            @else
                <span class="inline-flex items-center px-4 py-2 border border-blue-200 bg-blue-50 text-blue-700 rounded-full text-sm font-bold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    In Progress
                </span>
            @endif
            <button onclick="window.location.reload()" class="flex items-center gap-2 text-sm font-bold border px-4 py-2 rounded-lg bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Refresh
            </button>
        </div>
    </div>

    {{-- Progress Overview --}}
    <div class="p-6 border-2 border-[#D4A017] rounded-xl bg-gradient-to-br from-amber-50 to-white shadow-sm">
        <div class="flex items-start gap-6">
            <div class="w-16 h-16 bg-[#D4A017] rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tight">Overall Progress</h3>
                    <span class="text-2xl font-black text-[#D4A017]">{{ $summary['progress'] }}%</span>
                </div>
                <div class="w-full bg-white h-4 rounded-full border border-amber-200 overflow-hidden mb-4">
                    <div class="bg-[#D4A017] h-full transition-all duration-700" style="width: {{ $summary['progress'] }}%"></div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs font-bold uppercase tracking-wider">
                    <div class="text-green-600">
                        <span class="text-gray-600 font-normal normal-case">Completed:</span>
                        <span class="ml-1">{{ collect($tasks)->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="text-blue-600">
                        <span class="text-gray-600 font-normal normal-case">In Progress:</span>
                        <span class="ml-1">{{ collect($tasks)->where('status', 'in-progress')->count() }}</span>
                    </div>
                    <div class="text-gray-500">
                        <span class="text-gray-600 font-normal normal-case">Pending:</span>
                        <span class="ml-1">{{ collect($tasks)->where('status', 'pending')->count() }}</span>
                    </div>
                    <div class="text-red-600">
                        <span class="text-gray-600 font-normal normal-case">Failed:</span>
                        <span class="ml-1">{{ collect($tasks)->where('status', 'failed')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Sales Summary --}}
        <div class="p-6 rounded-xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="text-sm text-blue-600 font-bold">Sales</div>
            </div>
            <div class="text-2xl font-black text-gray-900 mb-1">
                Rs. {{ number_format($summary['salesSummary']['totalSales'], 2) }}
            </div>
            <div class="text-xs text-gray-600 font-medium">
                {{ $summary['salesSummary']['totalTransactions'] }} transactions
            </div>
        </div>

        {{-- Cash Summary --}}
        <div class="p-6 rounded-xl border border-green-200 bg-gradient-to-br from-green-50 to-white shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-sm text-green-600 font-bold">Cash to Deposit</div>
            </div>
            <div class="text-2xl font-black text-gray-900 mb-1">
                Rs. {{ number_format($summary['cashSummary']['depositAmount'], 2) }}
            </div>
            <div class="text-xs text-gray-600 font-medium">
                Expected: Rs. {{ number_format($summary['cashSummary']['expectedCash'], 2) }}
            </div>
        </div>

        {{-- Production Summary --}}
        <div class="p-6 rounded-xl border border-purple-200 bg-gradient-to-br from-purple-50 to-white shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div class="text-sm text-purple-600 font-bold">Production</div>
            </div>
            <div class="text-2xl font-black text-gray-900 mb-1">
                {{ $summary['productionSummary']['batchesCompleted'] }}
            </div>
            <div class="text-xs text-gray-600 font-medium">
                Batches completed
            </div>
        </div>

        {{-- Finance Summary --}}
        <div class="p-6 rounded-xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="text-sm text-amber-600 font-bold">GL Status</div>
            </div>
            <div class="text-2xl font-black text-gray-900 mb-1 capitalize">
                {{ $summary['financeSummary']['trialBalanceStatus'] }}
            </div>
            <div class="text-xs text-gray-600 font-medium">
                {{ $summary['financeSummary']['journalEntriesPosted'] }} entries posted
            </div>
        </div>
    </div>

    {{-- Task Categories --}}
    @php
        $categories = ['sales', 'cash', 'inventory', 'production', 'finance'];
    @endphp

    <div class="space-y-6">
        @foreach($categories as $category)
            @php
                $categoryTasks = collect($tasks)->where('category', $category);
            @endphp
            @if($categoryTasks->count() > 0)
                <div class="bg-white border-2 border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    {{-- Category Header --}}
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-200">
                                    {{-- Dynamic Icons based on category --}}
                                    @if($category == 'sales') <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    @elseif($category == 'cash') <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @elseif($category == 'inventory') <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @elseif($category == 'production') <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                    @elseif($category == 'finance') <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 capitalize">{{ $category }}</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $categoryTasks->where('status', 'completed')->count() }} of {{ $categoryTasks->count() }} completed
                                    </p>
                                </div>
                            </div>
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-800 h-2 rounded-full" style="width: {{ ($categoryTasks->where('status', 'completed')->count() / $categoryTasks->count()) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Task List --}}
                    <div class="divide-y divide-gray-200">
                        @foreach($categoryTasks as $task)
                            <div onclick="openTask(this)" data-task='{{ json_encode($task) }}' class="p-4 hover:bg-gray-50 transition-colors cursor-pointer group">
                                <div class="flex items-center gap-4">
                                    {{-- Status Icon --}}
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center border 
                                        {{ $task['status'] == 'completed' ? 'border-green-200 bg-green-50 text-green-600' : 
                                          ($task['status'] == 'in-progress' ? 'border-blue-200 bg-blue-50 text-blue-600' : 
                                          ($task['status'] == 'failed' ? 'border-red-200 bg-red-50 text-red-600' : 
                                          ($task['status'] == 'skipped' ? 'border-orange-200 bg-orange-50 text-orange-600' : 'border-gray-200 bg-gray-50 text-gray-400'))) }}">
                                        @if($task['status'] == 'completed') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($task['status'] == 'in-progress') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @elseif($task['status'] == 'failed') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @elseif($task['status'] == 'skipped') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        @else <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </div>

                                    {{-- Task Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $task['title'] }}</h4>
                                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded {{ $task['priority'] == 'critical' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                                                {{ $task['priority'] }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500">{{ $task['desc'] }}</p>
                                        @if($task['errorMessage'])
                                            <p class="text-sm text-red-600 mt-1 flex items-center gap-1 font-medium">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $task['errorMessage'] }}
                                            </p>
                                        @endif
                                    </div>

                                    <svg class="w-5 h-5 text-gray-300 group-hover:text-gray-500 transition-all transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Actions / Completion --}}
    <div class="p-6 border-2 border-gray-200 bg-gray-50 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <h3 class="font-bold text-gray-900 mb-1">Ready to Close Day-End?</h3>
            @if(!$readyToClose && count($blockers) > 0)
                <div class="text-sm text-red-600">
                    <p class="font-medium mb-1">Please complete:</p>
                    <ul class="list-disc list-inside space-y-1 text-gray-600">
                        @foreach($blockers as $blocker)
                            <li>{{ $blocker }}</li>
                        @endforeach
                    </ul>
                </div>
            @elseif($readyToClose)
                <p class="text-sm text-green-600 font-bold">All critical tasks completed. Ready to lock the day.</p>
            @endif
        </div>
        <div class="flex gap-3">
            <button class="bg-white border-2 border-gray-900 text-gray-900 px-6 py-2 rounded-xl font-bold hover:bg-gray-50 flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Generate Report
            </button>
            <button 
            <button 
                onclick="openLockModal()"
                id="lockDayButton"
                @if(!$readyToClose || $isLocked) disabled @endif
                class="bg-[#D4A017] text-white px-8 py-2 rounded-xl font-bold flex items-center gap-2 hover:bg-[#B8860B] shadow-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all uppercase tracking-tight"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Complete & Lock Day-End
            </button>
        </div>
    </div>

    {{-- Task Modal --}}
    {{-- Task Modal --}}
    <div 
        id="taskModal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4 transition-opacity duration-300 opacity-0 h-full"
    >
        <div 
            class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all duration-300 scale-95"
            id="taskModalContent"
        >
            <div id="taskModalBody">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-black text-gray-900" id="taskTitle"></h3>
                    <p class="text-sm text-gray-500 mt-1" id="taskDesc"></p>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <span 
                            id="taskPriority"
                            class="text-xs font-bold px-3 py-1 rounded-full uppercase border"
                        ></span>
                        <span 
                            id="taskStatus"
                            class="text-xs font-bold px-3 py-1 rounded-full uppercase border"
                        ></span>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Task Notes</label>
                        <textarea 
                            id="taskNotes"
                            rows="4" 
                            class="w-full border border-gray-200 rounded-xl focus:ring-[#D4A017] focus:border-[#D4A017] p-3 text-sm" 
                            placeholder="Add notes about completing this task..."
                        ></textarea>
                    </div>

                    <div id="taskCompletedInfo" class="hidden p-3 bg-green-50 border border-green-200 rounded-lg text-sm">
                        <div class="flex items-center gap-2 text-green-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span class="font-bold">Completed</span>
                        </div>
                        <p class="text-gray-600 mt-1">
                            <span id="taskCompletedAt"></span>
                            <span id="taskCompletedByWrapper" class="hidden"> by <span id="taskCompletedBy"></span></span>
                        </p>
                        <p id="taskCompletedNotes" class="hidden text-gray-700 mt-2 italic px-2 border-l-2 border-green-300"></p>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 flex justify-end gap-3 border-t border-gray-200">
                    <button onclick="closeTaskModal()" class="px-6 py-2 text-sm font-bold text-gray-500 hover:text-gray-700">Cancel</button>
                    
                    <button id="skipTaskBtn" onclick="skipTask()" class="hidden px-4 py-2 text-orange-600 font-bold hover:bg-orange-50 rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Skip Task
                    </button>

                    <button 
                        id="completeTaskBtn"
                        onclick="completeTask()" 
                        class="px-6 py-2 bg-[#D4A017] text-white rounded-lg font-bold shadow-lg shadow-amber-100 uppercase text-xs hover:bg-[#B8860B] transition-colors flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Mark Complete
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Lock Modal --}}
    {{-- Lock Modal --}}
    <div 
        id="lockModal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4 transition-opacity duration-300 opacity-0"
    >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all duration-300 scale-95" id="lockModalContent">
            <div class="p-6">
                <h3 class="text-xl font-black text-gray-900 mb-2">Lock Day-End for {{ $today }}?</h3>
                <p class="text-sm text-gray-500 mb-6">This will prevent any modifications to transactions for this date. The day can be unlocked later with manager approval.</p>
                
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div class="text-sm text-amber-800">
                            <p class="font-bold mb-1">Important:</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>No transactions can be added or edited</li>
                                <li>Reports will be finalized</li>
                                <li>Unlocking requires approval</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button onclick="closeLockModal()" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700">Cancel</button>
                    <button onclick="lockDay()" class="bg-red-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-red-700 transition-colors">
                        Lock Day
                    </button>
                </div>
            </div>
        </div>
    </div>

     {{-- Unlock Modal --}}
     {{-- Unlock Modal --}}
    <div 
        id="unlockModal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4 transition-opacity duration-300 opacity-0"
    >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all duration-300 scale-95" id="unlockModalContent">
            <div class="p-6">
                <h3 class="text-xl font-black text-gray-900 mb-2">Unlock Day-End?</h3>
                <p class="text-sm text-gray-500 mb-4">Please provide a reason for unlocking this closed day.</p>
                
                <div class="space-y-4">
                     <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Reason</label>
                        <textarea 
                            id="unlockReason"
                            rows="3" 
                            class="w-full border-gray-200 rounded-xl focus:ring-red-500 focus:border-red-500 p-3 text-sm" 
                            placeholder="Why do you need to unlock?"
                        ></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button onclick="closeUnlockModal()" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700">Cancel</button>
                    <button onclick="unlockDay()" class="bg-orange-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-orange-700 transition-colors">
                        Unlock Day
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Global variable for current task
    let currentTask = null;

    console.log('Day End Process Script Loaded');

    // Helper to toggle modal visibility with transitions
    function toggleModal(modalId, show) {
        const modal = document.getElementById(modalId);
        if (!modal) {
            console.error(`Modal with ID '${modalId}' not found`);
            return;
        }
        
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Small delay to allow display:flex to apply before opacity transition
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                const content = modal.querySelector('div[class*="transform"]');
                if(content) {
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                }
            }, 10);
        } else {
            modal.classList.add('opacity-0');
            const content = modal.querySelector('div[class*="transform"]');
            if(content) {
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
            }
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300); // Match transition duration
        }
    }

    // Task Modal Functions
    function openTask(element) {
        console.log('openTask called', element);
        const taskData = element.getAttribute('data-task');
        if (!taskData) {
            console.error('No task data found on element');
            return;
        }
        
        try {
            currentTask = JSON.parse(taskData);
            console.log('Task parsed', currentTask);
            
            // Populate Modal Fields
            setText('taskTitle', currentTask.title);
            setText('taskDesc', currentTask.desc);
            
            const priorityEl = document.getElementById('taskPriority');
            if (priorityEl) {
                priorityEl.textContent = currentTask.priority;
                priorityEl.className = 'text-xs font-bold px-3 py-1 rounded-full uppercase border ' + 
                    (currentTask.priority === 'critical' ? 'bg-red-50 text-red-700 border-red-200' : 'bg-orange-50 text-orange-700 border-orange-200');
            }

            const statusEl = document.getElementById('taskStatus');
            if (statusEl) {
                statusEl.textContent = currentTask.status;
                let statusClass = 'bg-gray-50 text-gray-700 border-gray-200';
                if(currentTask.status === 'completed') statusClass = 'bg-green-50 text-green-700 border-green-200';
                else if(currentTask.status === 'in-progress') statusClass = 'bg-blue-50 text-blue-700 border-blue-200';
                statusEl.className = 'text-xs font-bold px-3 py-1 rounded-full uppercase border ' + statusClass;
            }

            const notesEl = document.getElementById('taskNotes');
            if (notesEl) notesEl.value = currentTask.notes || '';

            // Handle Completion Info
            const completedInfo = document.getElementById('taskCompletedInfo');
            if (completedInfo) {
                if(currentTask.completedAt) {
                    completedInfo.classList.remove('hidden');
                    setText('taskCompletedAt', new Date(currentTask.completedAt).toLocaleString());
                    
                    const byWrapper = document.getElementById('taskCompletedByWrapper');
                    if (byWrapper) {
                        if(currentTask.completedBy) {
                            byWrapper.classList.remove('hidden');
                            setText('taskCompletedBy', currentTask.completedBy);
                        } else {
                            byWrapper.classList.add('hidden');
                        }
                    }
                    
                    const completedNotes = document.getElementById('taskCompletedNotes');
                    if (completedNotes) {
                        if(currentTask.notes) {
                            completedNotes.textContent = currentTask.notes;
                            completedNotes.classList.remove('hidden');
                        } else {
                            completedNotes.classList.add('hidden');
                        }
                    }
                } else {
                    completedInfo.classList.add('hidden');
                }
            }

            // Handle Buttons
            const skipBtn = document.getElementById('skipTaskBtn');
            if (skipBtn) {
                if(currentTask.priority === 'optional' && currentTask.status !== 'completed') {
                    skipBtn.classList.remove('hidden');
                } else {
                    skipBtn.classList.add('hidden');
                }
            }

            toggleModal('taskModal', true);

        } catch (e) {
            console.error('Error parsing task data', e);
            fireSwal('error', 'Error', 'Failed to load task details.');
        }
    }

    function closeTaskModal() {
        toggleModal('taskModal', false);
        currentTask = null;
    }

    function completeTask() {
        if(!currentTask) return;
        const notesEl = document.getElementById('taskNotes');
        const notes = notesEl ? notesEl.value : '';
        
        fireSwal('success', 'Task Completed', `Task "${currentTask.title}" marked as completed!`)
            .then(() => {
                closeTaskModal();
                window.location.reload();
            });
    }

    function skipTask() {
        if(!currentTask) return;
        fireSwal('info', 'Task Skipped', `Task "${currentTask.title}" has been skipped.`)
            .then(() => {
                closeTaskModal();
            });
    }

    // Lock Modal Functions
    function openLockModal() {
        toggleModal('lockModal', true);
    }

    function closeLockModal() {
        toggleModal('lockModal', false);
    }

    function lockDay() {
        fireSwal('success', 'Day Locked', 'Day has been locked successfully.')
            .then(() => {
                closeLockModal();
                window.location.reload();
            });
    }

    // Unlock Modal Functions
    function openUnlockModal() {
        console.log('openUnlockModal called');
        toggleModal('unlockModal', true);
    }

    function closeUnlockModal() {
        toggleModal('unlockModal', false);
    }

    function unlockDay() {
        const reasonEl = document.getElementById('unlockReason');
        const reason = reasonEl ? reasonEl.value : '';
        
        if (!reason.trim()) {
            fireSwal('warning', 'Validation Error', 'Please provide a reason for unlocking.');
            return;
        }
        
        fireSwal('success', 'Day Unlocked', `Day unlocked successfully.`)
            .then(() => {
                closeUnlockModal();
                if(reasonEl) reasonEl.value = '';
            });
    }

    // Helpers
    function setText(id, text) {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    }

    function fireSwal(icon, title, text) {
        if (typeof Swal !== 'undefined') {
            return Swal.fire({
                icon: icon,
                title: title,
                text: text,
                confirmButtonColor: '#D4A017'
            });
        } else {
            alert(text);
            return Promise.resolve();
        }
    }

    // Close Modals on Outside Click - Changed to addEventListener for safety
    window.addEventListener('click', function(event) {
        if (event.target.id === 'taskModal') closeTaskModal();
        if (event.target.id === 'lockModal') closeLockModal();
        if (event.target.id === 'unlockModal') closeUnlockModal();
    });
</script>
@endsection