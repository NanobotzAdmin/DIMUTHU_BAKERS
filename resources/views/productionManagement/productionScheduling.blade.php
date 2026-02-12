@extends('layouts.app')
@section('title', 'Production Scheduling')

@section('content')
    {{-- 
        PHP LOGIC & DATA SIMULATION 
        In a real app, this data would come from your Controller.
    --}}
    @php
        // 1. Helper to calculate Timeline CSS (Start 04:00, End 20:00 = 16 hours)
        function getTaskStyle($startTime, $durationMinutes) {
            $startHour = (int) explode(':', $startTime)[0];
            $startMinute = (int) explode(':', $startTime)[1];
            
            // Total minutes in the day view (16 hours * 60)
            $totalDayMinutes = 16 * 60; 
            
            // Calculate minutes since 04:00 AM
            $startOffsetMinutes = ($startHour - 4) * 60 + $startMinute;
            
            $leftPercent = ($startOffsetMinutes / $totalDayMinutes) * 100;
            $widthPercent = ($durationMinutes / $totalDayMinutes) * 100;
            
            // Enforce minimum width for visibility
            $widthPercent = max($widthPercent, 1.5);

            return "left: {$leftPercent}%; width: {$widthPercent}%;";
        }
    @endphp

    <div class="h-screen flex flex-col bg-[#F5F5F7] overflow-hidden">
        
        {{-- 1. Sticky Header --}}
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-3 sticky top-0 z-20 shadow-sm flex-shrink-0">
            <div class="flex flex-col lg:flex-row lg:items-center gap-3 lg:gap-4">
                {{-- Title --}}
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#8B5CF6] to-[#7C3AED] rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-gray-900 font-bold text-lg leading-tight">Production Scheduling</h1>
                        <p class="text-xs text-gray-500">Plan daily kitchen production</p>
                    </div>
                </div>

                {{-- Date Nav --}}
                <div class="flex items-center gap-2 flex-1 lg:mx-6">
                    <button id="btn-prev-date" onclick="changeDate(-1)" class="h-9 w-9 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    
                    <div class="flex-1 text-center min-w-0">
                        <div id="current-date-display" class="text-sm text-gray-900 font-medium truncate">{{ date('l, F j, Y') }}</div>
                        <div class="text-xs text-gray-500">
                             <span id="task-count-display">{{ count($tasks) }} tasks</span> • {{ count($pendingOrders) }} pending
                        </div>
                    </div>

                    <button id="btn-next-date" onclick="changeDate(1)" class="h-9 w-9 flex items-center justify-center border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <button id="btn-today" onclick="goToToday()" class="hidden sm:flex px-3 h-9 items-center border border-gray-200 rounded-lg hover:bg-gray-50 text-sm font-medium text-gray-700">Today</button>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <select class="h-9 pl-3 pr-8 border border-gray-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-purple-500 outline-none">
                        <option>All Status</option>
                        <option>Scheduled</option>
                        <option>In Progress</option>
                    </select>
                    <button onclick="openScheduleModal()" class="bg-gradient-to-r from-[#8B5CF6] to-[#7C3AED] text-white hover:from-[#7C3AED] hover:to-[#6D28D9] h-9 px-4 rounded-lg flex items-center text-sm font-medium shadow-sm transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="hidden sm:inline">Schedule</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- 2. Split View Layout --}}
        <div class="flex-1 flex overflow-hidden">
            
            {{-- LEFT: Timeline View (60-70%) --}}
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 scroll-smooth">
                
                {{-- Advanced Planner Banner --}}
                <div class="mb-4 bg-gradient-to-r from-purple-500 via-purple-600 to-indigo-600 rounded-2xl p-6 shadow-lg border border-purple-400">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                    {{-- Calendar Icon --}}
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Need Multi-Day Planning?</h3>
                                    <p class="text-purple-100 text-sm mt-0.5">Schedule across departments, resources & multiple days with advanced features</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 mt-3 text-sm text-purple-100">
                                <div class="flex items-center gap-1.5">
                                    {{-- CheckCircle Icon --}}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Multi-resource scheduling</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    {{-- CheckCircle Icon --}}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Drag & drop planning</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    {{-- CheckCircle Icon --}}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Conflict detection</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="window.location.href='/advanced-planner'" class="bg-white text-purple-600 hover:bg-purple-50 shadow-lg font-semibold px-6 py-3 rounded-lg flex items-center transition-colors">
                            Open Advanced Planner
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- Timeline Container --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 min-h-[500px] flex flex-col">
                    {{-- Timeline Header --}}
                    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50 rounded-t-2xl flex-shrink-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-gray-900 flex items-center gap-2 font-bold text-lg">
                                    <svg class="w-5 h-5 text-[#8B5CF6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Production Timeline
                                </h2>
                                <p class="text-xs text-gray-500 mt-1">04:00 AM - 08:00 PM • Click tasks for details</p>
                            </div>
                            {{-- Capacity Badges --}}
                            <div class="flex items-center gap-3">
                                <div class="hidden md:flex items-center gap-3">
                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg border border-gray-200 shadow-sm">
                                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                                        <span class="text-xs font-medium text-gray-700">Oven 67%</span>
                                    </div>
                                </div>
                                <div class="hidden md:flex items-center gap-3">
                                    <div class="flex items-center gap-2 px-3 py-1.5 bg-white rounded-lg border border-gray-200 shadow-sm">
                                        <i class="bi bi-people text-blue-500"></i>
                                        <span class="text-xs font-medium text-gray-700">Staff 3/5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline Grid --}}
                    <div class="flex-1 overflow-x-auto p-4 sm:p-6">
                        <div class="min-w-[800px]">
                            
                            <!-- Static header removed, now rendered by JS -->

                            <div id="timeline-grid-container">
                                <!-- Dynamic JS Render -->
                            </div>

                            {{-- Legend --}}
                            <div class="mt-6 pt-4 border-t border-gray-200 flex items-center justify-between flex-wrap gap-3">
                                <div class="flex items-center gap-4 text-xs">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-3 h-3 rounded bg-red-500"></div>
                                        <span class="text-gray-600">High Priority</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-3 h-3 rounded bg-amber-500"></div>
                                        <span class="text-gray-600">Medium</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-3 h-3 rounded bg-green-500"></div>
                                        <span class="text-gray-600">Low</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span>Has dependencies</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Pending Orders (30-40%) --}}
            <div class="w-full lg:w-[400px] xl:w-[450px] border-l border-gray-200 bg-white flex flex-col h-full shadow-xl z-10">
                
                {{-- Header --}}
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-4 border-b border-amber-100 flex-shrink-0">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <h2 class="text-gray-900 font-bold flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Pending Orders
                            </h2>
                            <p class="text-xs text-gray-600 mt-0.5">{{ count($pendingOrders) }} orders need scheduling</p>
                        </div>
                        <span class="bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ count($pendingOrders) }}</span>
                    </div>
                    
                    {{-- Search --}}
                    <div class="relative mt-3">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" onkeyup="filterOrders(this.value)" placeholder="Search orders..." class="w-full pl-9 h-9 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                    </div>
                </div>

                {{-- Scrollable List --}}
                <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50">
                    @foreach($pendingOrders as $order)
                    <div class="border border-amber-200 bg-white rounded-xl p-4 hover:shadow-md hover:border-amber-300 transition-all group cursor-pointer relative overflow-hidden">
                        {{-- Background Accent --}}
                        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-amber-50 to-orange-50 rounded-bl-full -z-0"></div>

                        {{-- Top Row --}}
                        <div class="flex items-start justify-between mb-3 relative z-10">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-bold text-gray-900">{{ $order['number'] }}</span>
                                    <span class="text-[10px] {{ $order['priority_color'] }} text-white px-2 py-0.5 rounded-full font-medium uppercase tracking-wide">{{ $order['priority'] }}</span>
                                </div>
                                <div class="text-xs text-gray-500 font-medium">{{ $order['customer'] }}</div>
                            </div>
                        </div>

                        {{-- Items List --}}
                        <div class="space-y-1.5 mb-3 bg-gray-50 rounded-lg p-2.5 border border-gray-100 relative z-10">
                            @foreach($order['items'] as $item)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-700 font-medium">{{ $item['name'] }}</span>
                                <span class="text-gray-900 bg-white px-1.5 py-0.5 rounded shadow-sm border border-gray-100">{{ $item['qty'] }}</span>
                            </div>
                            @endforeach
                        </div>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center gap-1.5 text-xs text-amber-700 font-medium bg-amber-50 px-2 py-1 rounded-md">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $order['date'] }}
                            </div>
                            
                            <button onclick='openScheduleForOrder(@json($order))' class="text-xs bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-lg font-medium shadow-sm flex items-center transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Schedule
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- 
        MODALS (Hidden by default, styled to match Shadcn)
    --}}

    {{-- Schedule Modal --}}
    <div id="scheduleModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-[2px] transition-opacity" onclick="closeScheduleModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <div class="flex items-center gap-2">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Schedule Production</h3>
                                        <p class="text-sm text-gray-500">Add a new task to the timeline.</p>
                                    </div>

                                </div>
                                <div class="mt-2">
                                    
                                    <div class="space-y-4">
                                        {{-- Date Selection --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                            <input type="date" id="schedule-date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm h-10 border px-3">
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Recipe</label>
                                                <select id="schedule-recipe" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm h-10 border px-3">
                                                    <!-- Dynamic -->
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Batch Size</label>
                                                <input type="number" id="schedule-batch" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm h-10 border px-3" placeholder="20">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                                                <input type="time" id="schedule-start" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm h-10 border px-3">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Resource Slot</label>
                                                <select id="schedule-oven" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm h-10 border px-3">
                                                    <!-- Dynamic options -->
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-end pt-2">
                                            <button type="button" onclick="addToScheduleTable()" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium flex items-center transition-colors">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                Add to List
                                            </button>
                                        </div>

                                        {{-- Added Items Table --}}
                                        <div class="mt-4 border rounded-lg overflow-hidden hidden" id="schedule-table-container">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipe</th>
                                                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch</th>
                                                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start</th>
                                                        <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200" id="schedule-table-body">
                                                    <!-- Dynamic Rows -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" class="inline-flex w-full justify-center rounded-md bg-purple-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-purple-500 sm:ml-3 sm:w-auto" onclick="submitSchedule()">Schedule Task</button>
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="closeScheduleModal()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Task Detail Modal --}}
    <div id="detailModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-[2px] transition-opacity" onclick="closeDetailModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div id="detail-icon-container" class="w-10 h-10 rounded-lg flex items-center justify-center">
                                    {{-- Dynamic Icon --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 leading-tight" id="detail-recipe"></h3>
                                    <p class="text-xs text-gray-500">Production Task Details</p>
                                </div>
                            </div>
                            <span id="detail-status" class="px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <span class="text-xs text-gray-500 block mb-1">Start Time</span>
                                <span class="text-sm font-semibold text-gray-900" id="detail-start"></span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <span class="text-xs text-gray-500 block mb-1">Duration</span>
                                <span class="text-sm font-semibold text-gray-900"><span id="detail-duration"></span> mins</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <span class="text-xs text-gray-500 block mb-1">Batch Size</span>
                                <span class="text-sm font-semibold text-gray-900" id="detail-batch"></span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <span class="text-xs text-gray-500 block mb-1">Assigned To</span>
                                <span class="text-sm font-semibold text-gray-900" id="detail-user"></span>
                            </div>
                        </div>

                        <div id="detail-notes-box" class="hidden bg-amber-50 border border-amber-100 p-3 rounded-lg">
                            <span class="text-xs font-bold text-amber-800 block mb-1">Notes</span>
                            <p class="text-sm text-amber-700" id="detail-notes"></p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row sm:px-6 gap-2">
                        <button type="button" onclick="startTask()" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:w-auto flex-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Start Production
                        </button>
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="closeDetailModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data from PHP
        const allTasks = @json($tasks);
        const allResources = @json($resources);
        const allRecipes = @json($recipes);
        let currentDate = new Date();
        let currentTaskId = null;

        function formatDate(date) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        function formatISODate(date) {
            return date.toISOString().split('T')[0];
        }

        function changeDate(offset) {
            currentDate.setDate(currentDate.getDate() + offset);
            updateDateDisplay();
            renderTimeline();
        }

        function goToToday() {
            currentDate = new Date();
            updateDateDisplay();
            renderTimeline();
        }

        function updateDateDisplay() {
            document.getElementById('current-date-display').textContent = formatDate(currentDate);
            
            // Filter tasks for count
            const dateStr = formatISODate(currentDate);
            const daysTasks = allTasks.filter(t => t.date === dateStr);
            document.getElementById('task-count-display').textContent = `${daysTasks.length} tasks`;
        }

        function getTaskStyle(startTime, durationMinutes) {
            const startHour = parseInt(startTime.split(':')[0]);
            const startMinute = parseInt(startTime.split(':')[1]);
            
            // Total minutes in the day view (16 hours * 60)
            const totalDayMinutes = 16 * 60; 
            
            // Calculate minutes since 04:00 AM
            const startOffsetMinutes = (startHour - 4) * 60 + startMinute;
            
            const leftPercent = (startOffsetMinutes / totalDayMinutes) * 100;
            let widthPercent = (durationMinutes / totalDayMinutes) * 100;
            
            // Enforce minimum width
            widthPercent = Math.max(widthPercent, 1.5);

            return `left: ${leftPercent}%; width: ${widthPercent}%;`;
        }

        function renderTimeline() {
            const container = document.getElementById('timeline-grid-container');
            const dateStr = formatISODate(currentDate);
            const daysTasks = allTasks.filter(t => t.date === dateStr);
            
            // Check if viewing today for current time line
            const todayStr = formatISODate(new Date());
            const isToday = dateStr === todayStr;
            let currentTimeLine = '';
            
            if (isToday) {
                const now = new Date();
                const nowHours = now.getHours();
                const nowMinutes = now.getMinutes();
                
                // Timeline starts at 04:00, ends at 20:00
                const timelineStartHour = 4;
                const totalMinutes = 16 * 60;
                
                const currentMinutes = (nowHours - timelineStartHour) * 60 + nowMinutes;
                const percent = (currentMinutes / totalMinutes) * 100;
                
                if (percent >= 0 && percent <= 100) {
                    currentTimeLine = `<div class="absolute top-0 bottom-0 w-0.5 bg-red-500 z-30 shadow-sm pointer-events-none" style="left: ${percent}%" title="Current Time">
                        <div class="absolute -top-1 -ml-1 w-2.5 h-2.5 rounded-full bg-red-500"></div>
                    </div>`;
                }
            }

            let html = '';

            // 1. Time Header
            html += `
            <div class="grid grid-cols-[140px_1fr] gap-4 mb-4">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider self-end pb-1">Resource</div>
                <div class="grid grid-cols-8 gap-0 border-b border-gray-100 pb-2">
                    ${['04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00'].map(t => `<div class="text-xs text-gray-400 text-left -ml-2">${t}</div>`).join('')}
                </div>
            </div>`;

            // 2. Resource Rows
            if (allResources.length > 0) {
                 allResources.forEach(res => {
                    const rowTasks = daysTasks.filter(t => t.oven == res.id); // 'oven' tracks resource ID in backend mapping
                    
                    html += `
                    <div class="mb-4 grid grid-cols-[140px_1fr] gap-4 group">
                        <div class="flex items-center gap-3 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-3 border border-orange-100 group-hover:border-orange-200 transition-colors h-20">
                            <div class="bg-white p-1.5 rounded-lg shadow-sm">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900 truncate" title="${res.name}">${res.name}</div>
                                <div class="text-[10px] text-gray-500 font-medium">${rowTasks.length} tasks</div>
                            </div>
                        </div>

                        <div class="relative h-20 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 hover:border-purple-200 transition-colors">
                            <div class="absolute inset-0 grid grid-cols-16 pointer-events-none">
                                ${Array(16).fill(0).map(() => `<div class="border-r border-gray-200/60 h-full"></div>`).join('')}
                            </div>
                            <!-- Current Time Line -->
                            ${currentTimeLine}
                            ${rowTasks.map(task => `
                                <div onclick='selectTask(${JSON.stringify(task)})' 
                                     class="absolute top-2 bottom-2 rounded-lg border-2 cursor-pointer hover:shadow-lg hover:scale-[1.02] hover:z-20 transition-all ${task.bg}"
                                     style="${getTaskStyle(task.start, task.duration)}">
                                    <div class="px-2 py-1.5 h-full flex flex-col justify-between">
                                        <div class="flex items-start justify-between gap-1">
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold truncate leading-tight">${task.recipe}</div>
                                                <div class="text-[10px] opacity-75 truncate">${task.batch}</div>
                                            </div>
                                            <div class="w-1.5 h-1.5 rounded-full ${task.dot} flex-shrink-0 mt-0.5"></div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>`;
                 });
            } else {
                html += `<div class="p-8 text-center text-gray-500">No resources available.</div>`;
            }

            container.innerHTML = html;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            renderTimeline();
        });

        function filterOrders(query) {
            query = query.toLowerCase();
            const container = document.querySelector('.overflow-y-auto.p-4.space-y-3'); // Pending orders container
            const cards = container.querySelectorAll('.border.border-amber-200'); // Order cards

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function openScheduleModal() {
            document.getElementById('scheduleModal').classList.remove('hidden');
            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('schedule-date').value = today;

            // Populate Recipes
            const recipeSelect = document.getElementById('schedule-recipe');
            if (recipeSelect.options.length <= 0) {
                 allRecipes.forEach(r => {
                    const opt = document.createElement('option');
                    opt.value = r.name; // Using name as per current simplified logic
                    opt.text = r.name;
                    recipeSelect.add(opt);
                 });
            }

            // Populate Resources
            const ovenSelect = document.getElementById('schedule-oven');
            if (ovenSelect.options.length <= 0) {
                 allResources.forEach(res => {
                    const opt = document.createElement('option');
                    opt.value = res.id;
                    opt.text = res.name;
                    ovenSelect.add(opt);
                 });
            }

            // Clear table
            document.getElementById('schedule-table-body').innerHTML = '';
            document.getElementById('schedule-table-container').classList.add('hidden');
        }

        function openScheduleForOrder(order) {
            openScheduleModal();
            // Pre-fill table with order items
            if (order.items && order.items.length > 0) {
                // Default start time: nearest hour
                const now = new Date();
                now.setMinutes(0);
                now.setHours(now.getHours() + 1);
                const defaultStart = now.toTimeString().substring(0, 5);

                order.items.forEach(item => {
                    // Try to match item name to recipe name?
                    // For now just use item name
                    const qty = parseInt(item.qty) || 0;
                    addToScheduleTable(item.name, qty, defaultStart);
                });
            }
        }
        
        function addToScheduleTable(recipeArg, batchArg, startArg) {
            // Can be called with args or empty (read inputs)
            let recipe, batch, start, date, resourceId;

            if (recipeArg) {
                // Programmatic
                date = document.getElementById('schedule-date').value;
                recipe = recipeArg;
                batch = batchArg;
                start = startArg;
                // Default resource? 
                resourceId = document.getElementById('schedule-oven').options[0]?.value; 
            } else {
                // Read Inputs
                date = document.getElementById('schedule-date').value;
                recipe = document.getElementById('schedule-recipe').value;
                batch = document.getElementById('schedule-batch').value;
                start = document.getElementById('schedule-start').value;
                resourceId = document.getElementById('schedule-oven').value;
            }

            if(!date || !recipe || !batch || !start) {
                if(!recipeArg) Swal.fire({ icon: 'error', title: 'Error', text: 'Please fill in all fields' });
                return;
            }
            
            // Get Resource Name
            const ovenSelect = document.getElementById('schedule-oven');
            // If programmatic, find resource name roughly or default
            let resourceName = "Default";
            if (resourceId) {
                // If inputs, find selected
                 const sel = Array.from(ovenSelect.options).find(o => o.value == resourceId);
                 if(sel) resourceName = sel.text;
                 else {
                     // If Programmatic and ID passed (not implemented fully), logic here.
                     // Just use first
                     resourceName = ovenSelect.options[0]?.text;
                     resourceId = ovenSelect.options[0]?.value;
                 }
            }


            const tbody = document.getElementById('schedule-table-body');
            const row = document.createElement('tr');
            
            // Store raw data in data attributes
            row.dataset.resourceId = resourceId;
            row.dataset.date = date;
            row.dataset.start = start;
            row.dataset.recipe = recipe;
            row.dataset.batch = batch;

            row.innerHTML = `
                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-900">${date}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs font-medium text-gray-900">${recipe}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">${batch}</td>
                <td class="px-3 py-2 whitespace-nowrap text-xs text-gray-500">${start} <span class="text-[10px] text-gray-400">(${resourceName})</span></td>
                <td class="px-3 py-2 whitespace-nowrap text-right text-xs font-medium">
                    <button onclick="this.closest('tr').remove(); checkTableVisibility();" class="text-red-600 hover:text-red-900">Remove</button>
                </td>
            `;

            tbody.appendChild(row);
            document.getElementById('schedule-table-container').classList.remove('hidden');

            // Reset inputs if manual
            if (!recipeArg) {
                document.getElementById('schedule-batch').value = '';
                // document.getElementById('schedule-start').value = ''; // Keep time for next
            }
        }

        function checkTableVisibility() {
             const tbody = document.getElementById('schedule-table-body');
             if(tbody.children.length === 0) {
                 document.getElementById('schedule-table-container').classList.add('hidden');
             }
        }

        function closeScheduleModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
        }

        function submitSchedule() {
            const rows = document.getElementById('schedule-table-body').querySelectorAll('tr');
            
            if (rows.length > 0) {
                // Bulk Submit from Table
                const promises = [];
                rows.forEach(row => {
                    const d = row.dataset;
                    
                    // Construct Timestamps
                    const startTimeDate = new Date(`${d.date}T${d.start}:00`);
                    const endTimeDate = new Date(startTimeDate.getTime() + 120 * 60000); // 120 mins default
                    
                    const startStr = d.date + ' ' + d.start + ':00';
                    const endStr = endTimeDate.getFullYear() + '-' + 
                              String(endTimeDate.getMonth() + 1).padStart(2,'0') + '-' + 
                              String(endTimeDate.getDate()).padStart(2,'0') + ' ' + 
                              String(endTimeDate.getHours()).padStart(2,'0') + ':' + 
                              String(endTimeDate.getMinutes()).padStart(2,'0') + ':00';

                    const p = fetch("{{ route('advancedPlanner.store') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                        body: JSON.stringify({
                            resource_id: d.resourceId,
                            start_time: startStr,
                            end_time: endStr,
                            quantity: d.batch,
                            notes: d.recipe
                        })
                    }).then(r => r.json());
                    promises.push(p);
                });

                Promise.all(promises)
                    .then(results => {
                         // Check failures?
                         Swal.fire({ icon: 'success', title: 'Success', text: `${results.length} tasks scheduled!` });
                         closeScheduleModal();
                         window.location.reload();
                    })
                    .catch(e => {
                        console.error(e);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Some tasks failed.' });
                    });

            } else {
                // Single Submit from Inputs (Fallback)
                const date = document.getElementById('schedule-date').value;
                const recipe = document.getElementById('schedule-recipe').value;
                const batch = document.getElementById('schedule-batch').value;
                const start = document.getElementById('schedule-start').value;
                const resourceId = document.getElementById('schedule-oven').value;

                if (!date || !recipe || !batch || !start || !resourceId) {
                     Swal.fire({ icon: 'error', title: 'Error', text: 'Please add items to list or fill fields.' });
                     return;
                }
                
                // ... Reuse single submit logic from before or just call addToScheduleTable then submit? 
                // Let's just do single fetch here to keep it simple
                const startTimeDate = new Date(`${date}T${start}:00`);
                const endTimeDate = new Date(startTimeDate.getTime() + 120 * 60000); 
                const startStr = date + ' ' + start + ':00';
                 // ... same date fmt ...
                const endStr = endTimeDate.getFullYear() + '-' + 
                          String(endTimeDate.getMonth() + 1).padStart(2,'0') + '-' + 
                          String(endTimeDate.getDate()).padStart(2,'0') + ' ' + 
                          String(endTimeDate.getHours()).padStart(2,'0') + ':' + 
                          String(endTimeDate.getMinutes()).padStart(2,'0') + ':00';

                fetch("{{ route('advancedPlanner.store') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: JSON.stringify({
                        resource_id: resourceId,
                        start_time: startStr,
                        end_time: endStr,
                        quantity: batch,
                        notes: recipe
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire({ icon: 'success', title: 'Success', text: 'Task scheduled!' });
                        closeScheduleModal();
                         window.location.reload(); 
                    } else {
                         Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                    }
                });
            }
        }

        function startTask() {
            if (!currentTaskId) return;

            // AJAX to Update Status
            // We use advancedPlanner.update route which expects schedule_id and status
            fetch("{{ route('advancedPlanner.update') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    schedule_id: currentTaskId,
                    status: 2 // CommonVariables::$productionInProgress (2)
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({ icon: 'success', title: 'Started', text: 'Production started!' });
                    closeDetailModal();
                    window.location.reload(); 
                } else {
                     Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Failed to update' });
                }
            })
            .catch(err => {
                 console.error(err);
                 Swal.fire({ icon: 'error', title: 'Error', text: 'Network error occurred.' });
            });
        }
    </script>
@endsection