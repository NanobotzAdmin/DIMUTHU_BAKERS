@extends('layouts.app')
@section('title', 'Batch Tracking & History')

@section('content')

{{-- 
    LOGIC BLOCK
    In a real app, this data would come from your Controller.
--}}
@php
    // 1. Mock Data
    $batchHistory = [
        [
            'id' => 'BATCH-001',
            'recipeName' => 'Chocolate Cake',
            'recipeIcon' => '🎂',
            'section' => 'Kitchen',
            'scheduledTime' => '08:00 AM',
            'startedAt' => '08:15 AM',
            'completedAt' => '10:45 AM',
            'date' => '2025-12-06',
            'status' => 'completed',
            'priority' => 'high',
            'assignedTo' => 'Station 1',
            'targetQuantity' => 10,
            'actualOutput' => 10,
            'wasteQuantity' => 0,
            'efficiency' => 100,
            'plannedDuration' => 150,
            'actualDuration' => 150,
            'byproducts' => [
                ['name' => 'Cake Scraps', 'expected' => 0.5, 'actual' => 0.5, 'unit' => 'kg', 'variance' => 0]
            ],
            'qualityNotes' => 'Perfect batch, all cakes passed quality check',
            'currentStep' => null, 'totalSteps' => null, 'estimatedCompletion' => null, 'wasteReason' => null
        ],
        [
            'id' => 'BATCH-002',
            'recipeName' => 'Bread Loaves',
            'recipeIcon' => '🍞',
            'section' => 'Bakery',
            'scheduledTime' => '06:00 AM',
            'startedAt' => '06:00 AM',
            'completedAt' => '09:45 AM',
            'date' => '2025-12-06',
            'status' => 'completed',
            'priority' => 'medium',
            'assignedTo' => 'Station 2',
            'targetQuantity' => 50,
            'actualOutput' => 48,
            'wasteQuantity' => 2,
            'wasteReason' => 'Quality Issue - Burnt',
            'efficiency' => 96,
            'plannedDuration' => 225,
            'actualDuration' => 225,
            'byproducts' => [
                ['name' => 'Bread Trimmings', 'expected' => 1.2, 'actual' => 0.9, 'unit' => 'kg', 'variance' => -25]
            ],
            'qualityNotes' => '2 loaves burnt due to oven temperature spike',
            'currentStep' => null, 'totalSteps' => null, 'estimatedCompletion' => null
        ],
        [
            'id' => 'BATCH-006',
            'recipeName' => 'Red Velvet Cake',
            'recipeIcon' => '❤️',
            'section' => 'Cake',
            'scheduledTime' => '09:00 AM',
            'startedAt' => '09:15 AM',
            'completedAt' => null,
            'date' => '2025-12-06',
            'status' => 'in-progress',
            'priority' => 'high',
            'assignedTo' => 'Cake Station 1',
            'targetQuantity' => 3,
            'actualOutput' => null,
            'wasteQuantity' => null,
            'efficiency' => null,
            'plannedDuration' => 210,
            'actualDuration' => null,
            'currentStep' => 5,
            'totalSteps' => 10,
            'estimatedCompletion' => '12:30 PM',
            'byproducts' => [], 'qualityNotes' => null, 'wasteReason' => null
        ],
        [
            'id' => 'BATCH-003',
            'recipeName' => 'Croissants',
            'recipeIcon' => '🥐',
            'section' => 'Bakery',
            'scheduledTime' => '10:00 AM',
            'startedAt' => null,
            'completedAt' => null,
            'date' => '2025-12-06',
            'status' => 'pending',
            'priority' => 'high',
            'assignedTo' => 'Station 3',
            'targetQuantity' => 100,
            'actualOutput' => null,
            'wasteQuantity' => null,
            'efficiency' => null,
            'plannedDuration' => 180,
            'actualDuration' => null,
            'currentStep' => null, 'totalSteps' => null, 'estimatedCompletion' => null,
            'byproducts' => [], 'qualityNotes' => null, 'wasteReason' => null
        ],
        [
            'id' => 'BATCH-005',
            'recipeName' => 'Dinner Rolls',
            'recipeIcon' => '🥖',
            'section' => 'Bakery',
            'scheduledTime' => '07:00 AM',
            'startedAt' => '07:05 AM',
            'completedAt' => '09:30 AM',
            'date' => '2025-12-05',
            'status' => 'completed',
            'priority' => 'low',
            'assignedTo' => 'Station 2',
            'targetQuantity' => 80,
            'actualOutput' => 82,
            'wasteQuantity' => 0,
            'efficiency' => 102.5,
            'plannedDuration' => 120,
            'actualDuration' => 145,
            'byproducts' => [
                ['name' => 'Bread Trimmings', 'expected' => 0.4, 'actual' => 0.6, 'unit' => 'kg', 'variance' => 50]
            ],
            'qualityNotes' => 'Exceeded target - dough expanded more than expected',
            'currentStep' => null, 'totalSteps' => null, 'estimatedCompletion' => null, 'wasteReason' => null
        ],
        [
            'id' => 'BATCH-008',
            'recipeName' => 'Tiramisu',
            'recipeIcon' => '☕',
            'section' => 'Cake',
            'scheduledTime' => '10:00 AM',
            'startedAt' => '10:00 AM',
            'completedAt' => '11:45 AM',
            'date' => '2025-12-05',
            'status' => 'completed',
            'priority' => 'medium',
            'assignedTo' => 'Cake Station 1',
            'targetQuantity' => 8,
            'actualOutput' => 8,
            'wasteQuantity' => 0,
            'efficiency' => 100,
            'plannedDuration' => 105,
            'actualDuration' => 105,
            'byproducts' => [
                ['name' => 'Broken Ladyfingers', 'expected' => 0.2, 'actual' => 0.15, 'unit' => 'kg', 'variance' => -25]
            ],
            'qualityNotes' => 'Perfect execution',
            'currentStep' => null, 'totalSteps' => null, 'estimatedCompletion' => null, 'wasteReason' => null
        ]
    ];

    // 2. Statistics Logic
    $totalBatches = count($batchHistory);
    $completedBatches = array_filter($batchHistory, fn($b) => $b['status'] === 'completed');
    $inProgressBatches = array_filter($batchHistory, fn($b) => $b['status'] === 'in-progress');
    $pendingBatches = array_filter($batchHistory, fn($b) => $b['status'] === 'pending');

    $completedCount = count($completedBatches);
    $inProgressCount = count($inProgressBatches);
    
    // Efficiency
    $totalEfficiency = array_reduce($completedBatches, fn($sum, $b) => $sum + ($b['efficiency'] ?? 0), 0);
    $avgEfficiency = $completedCount > 0 ? $totalEfficiency / $completedCount : 0;

    // Targets vs Actuals
    $totalTarget = array_reduce($completedBatches, fn($sum, $b) => $sum + $b['targetQuantity'], 0);
    $totalActual = array_reduce($completedBatches, fn($sum, $b) => $sum + ($b['actualOutput'] ?? 0), 0);

    // Helpers
    $sections = array_unique(array_column($batchHistory, 'section'));
    $statuses = ['completed', 'in-progress', 'pending'];

    function getStatusColor($status) {
        return match($status) {
            'completed' => 'bg-green-500',
            'in-progress' => 'bg-blue-500',
            'pending' => 'bg-gray-400',
            default => 'bg-gray-400',
        };
    }

    function getPriorityClasses($priority) {
        return match($priority) {
            'high' => 'bg-red-100 text-red-700 border-red-300',
            'medium' => 'bg-orange-100 text-orange-700 border-orange-300',
            'low' => 'bg-blue-100 text-blue-700 border-blue-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }
@endphp

<div class="min-h-screen bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 p-4 md:p-6" x-data="{ searchTerm: '', selectedSection: 'all', selectedStatus: 'all' }">
    
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    {{-- Icon: BarChart3 --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Batch Tracking & History</h1>
                    <p class="text-gray-600">Production batch analytics and performance</p>
                </div>
            </div>

            <button class="h-12 px-6 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center justify-center gap-2 font-medium transition-all shadow-sm">
                {{-- Icon: Download --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                Export Report
            </button>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            {{-- Total Batches --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                    <span class="text-sm text-gray-600">Total Batches</span>
                </div>
                <div class="text-3xl font-semibold text-gray-900">{{ $totalBatches }}</div>
            </div>

            {{-- Completed --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-green-200">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span class="text-sm text-gray-600">Completed</span>
                </div>
                <div class="text-3xl font-semibold text-green-600">{{ $completedCount }}</div>
            </div>

            {{-- In Progress --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-blue-200">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="10" x2="14" y1="2" y2="2"/><line x1="12" x2="15" y1="14" y2="11"/><circle cx="12" cy="14" r="8"/></svg>
                    <span class="text-sm text-gray-600">In Progress</span>
                </div>
                <div class="text-3xl font-semibold text-blue-500">{{ $inProgressCount }}</div>
            </div>

            {{-- Avg Efficiency --}}
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 shadow-sm border-2 border-blue-300">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    <span class="text-sm text-blue-700">Avg Efficiency</span>
                </div>
                <div class="text-3xl font-semibold text-blue-700">{{ number_format($avgEfficiency, 1) }}%</div>
                {{-- Progress Bar --}}
                <div class="relative h-2 w-full overflow-hidden rounded-full bg-blue-200 mt-2">
                    <div class="h-full bg-blue-600" style="width: {{ $avgEfficiency }}%"></div>
                </div>
            </div>

            {{-- Output vs Target --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                    <span class="text-sm text-gray-600">Output vs Target</span>
                </div>
                <div class="text-3xl font-semibold text-gray-900">
                    {{ $totalActual }}/{{ $totalTarget }}
                </div>
            </div>
        </div>

        {{-- Filters (Functional UI mostly, assumes JS or Livewire for instant filter) --}}
        <div class="flex flex-col md:flex-row gap-3 mb-4">
            <div class="flex-1 relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" placeholder="Search by batch ID or recipe name..." 
                    class="w-full h-12 pl-12 pr-4 bg-white border-2 border-gray-200 rounded-xl outline-none focus:border-amber-500 transition-colors">
            </div>

            <div>
                <select class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl outline-none focus:border-amber-500 transition-colors">
                    <option value="all">All Sections</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec }}">{{ $sec }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl outline-none focus:border-amber-500 transition-colors">
                    <option value="all">All Statuses</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}">{{ ucwords(str_replace('-', ' ', $st)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Batch List --}}
    <div class="space-y-4">
        @forelse($batchHistory as $batch)
            @php
                $progress = ($batch['currentStep'] && $batch['totalSteps']) 
                    ? ($batch['currentStep'] / $batch['totalSteps']) * 100 
                    : 0;
            @endphp

            <div class="bg-white rounded-2xl p-5 shadow-sm border-2 transition-all {{ $batch['status'] === 'in-progress' ? 'border-blue-300 shadow-lg' : 'border-gray-100' }}">
                <div class="flex flex-col lg:flex-row items-start gap-4">
                    
                    {{-- Icon --}}
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center shadow-md {{ getStatusColor($batch['status']) }}">
                            <span class="text-3xl text-white drop-shadow-sm">{{ $batch['recipeIcon'] }}</span>
                        </div>
                        <div class="w-4 h-4 rounded-full {{ getStatusColor($batch['status']) }} shadow-lg"></div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 w-full">
                        <div class="flex flex-col lg:flex-row items-start justify-between mb-3">
                            <div class="mb-2 lg:mb-0">
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <h3 class="text-2xl font-semibold text-gray-900">{{ $batch['recipeName'] }}</h3>
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ getPriorityClasses($batch['priority']) }} border-2 uppercase">
                                        {{ $batch['priority'] }}
                                    </span>
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold bg-gray-200 text-gray-700">
                                        {{ $batch['id'] }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-4 text-gray-600 text-sm">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                        <span>{{ $batch['date'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" x2="18" y1="17" y2="17"/></svg>
                                        <span>{{ $batch['section'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        <span>{{ $batch['assignedTo'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span>Scheduled: {{ $batch['scheduledTime'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 rounded-full {{ getStatusColor($batch['status']) }} shadow-lg"></div>
                                <span class="font-medium text-gray-700 capitalize">
                                    {{ str_replace('-', ' ', $batch['status']) }}
                                </span>
                            </div>
                        </div>

                        {{-- Progress Bar for In-Progress --}}
                        @if($batch['status'] === 'in-progress')
                            <div class="mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600">
                                        Progress: Step {{ $batch['currentStep'] }} of {{ $batch['totalSteps'] }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-700">{{ round($progress) }}%</span>
                                </div>
                                <div class="relative h-3 w-full overflow-hidden rounded-full bg-blue-100">
                                    <div class="h-full bg-blue-500 transition-all duration-300" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        @endif

                        {{-- Performance Metrics Grid (Completed) --}}
                        @if($batch['status'] === 'completed')
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-3">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <div class="text-xs text-gray-600 mb-1">Target</div>
                                    <div class="text-lg font-medium text-gray-900">{{ $batch['targetQuantity'] }}</div>
                                </div>

                                <div class="rounded-xl p-3 {{ ($batch['actualOutput'] ?? 0) >= $batch['targetQuantity'] ? 'bg-green-50' : 'bg-red-50' }}">
                                    <div class="text-xs text-gray-600 mb-1">Actual Output</div>
                                    <div class="text-lg font-medium {{ ($batch['actualOutput'] ?? 0) >= $batch['targetQuantity'] ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $batch['actualOutput'] }}
                                    </div>
                                </div>

                                <div class="rounded-xl p-3 {{ $batch['wasteQuantity'] > 0 ? 'bg-red-50' : 'bg-green-50' }}">
                                    <div class="text-xs text-gray-600 mb-1">Waste</div>
                                    <div class="text-lg font-medium {{ $batch['wasteQuantity'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $batch['wasteQuantity'] ?? 0 }}
                                    </div>
                                </div>

                                <div class="rounded-xl p-3 {{ ($batch['efficiency'] ?? 0) >= 95 ? 'bg-green-50' : (($batch['efficiency'] ?? 0) >= 85 ? 'bg-orange-50' : 'bg-red-50') }}">
                                    <div class="text-xs text-gray-600 mb-1">Efficiency</div>
                                    <div class="text-lg font-medium flex items-center gap-1 {{ ($batch['efficiency'] ?? 0) >= 95 ? 'text-green-600' : (($batch['efficiency'] ?? 0) >= 85 ? 'text-orange-600' : 'text-red-600') }}">
                                        @if(($batch['efficiency'] ?? 0) >= 100)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
                                        @endif
                                        {{ $batch['efficiency'] }}%
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-3">
                                    <div class="text-xs text-gray-600 mb-1">Duration</div>
                                    <div class="text-lg font-medium text-gray-900">{{ $batch['actualDuration'] }} min</div>
                                </div>
                            </div>
                        @endif

                        {{-- Quality Notes --}}
                        @if($batch['qualityNotes'])
                            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-3 mb-3">
                                <div class="text-sm text-blue-900">{{ $batch['qualityNotes'] }}</div>
                            </div>
                        @endif

                        {{-- Action Button --}}
                        <div class="flex gap-3">
                            {{-- We use a simple JS function to open the modal and populate it with data from a data attribute --}}
                            <button onclick="openBatchModal({{ json_encode($batch) }})" 
                                class="h-12 px-6 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                View Full Details
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                </div>
                <h3 class="text-xl text-gray-900 mb-2">No Batches Found</h3>
                <p class="text-gray-600">No batches match your search criteria</p>
            </div>
        @endforelse
    </div>

    {{-- 
        MODAL COMPONENT 
        Hidden by default. Toggled by JS logic below.
    --}}
    <div id="batchModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeBatchModal()"></div>
        
        {{-- Modal Content --}}
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto m-4 animate-in fade-in zoom-in duration-200">
            
            {{-- Header --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span id="modalIcon" class="text-4xl"></span>
                            <h2 id="modalTitle" class="text-2xl font-bold text-gray-900"></h2>
                            <span id="modalPriority" class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-2 uppercase"></span>
                        </div>
                        <p id="modalSubtitle" class="text-base text-gray-600"></p>
                    </div>
                    <button onclick="closeBatchModal()" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-6">
                
                {{-- Timeline Section --}}
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-5 border-2 border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Batch Timeline
                    </h3>
                    <div class="space-y-3" id="modalTimeline">
                        {{-- Populated by JS --}}
                    </div>
                </div>

                {{-- Metrics Section (Completed Only) --}}
                <div id="modalMetricsWrapper">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                        Performance Metrics
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
                            <div class="text-sm text-gray-600 mb-2">Output Performance</div>
                            <div class="text-3xl text-gray-900 mb-1" id="modalOutput"></div>
                            <div class="text-sm font-medium" id="modalEfficiency"></div>
                        </div>
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
                            <div class="text-sm text-gray-600 mb-2">Waste Analysis</div>
                            <div class="text-3xl mb-1" id="modalWaste"></div>
                            <div class="text-sm text-gray-600" id="modalWasteReason"></div>
                        </div>
                        <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
                            <div class="text-sm text-gray-600 mb-2">Time Performance</div>
                            <div class="text-3xl text-gray-900 mb-1" id="modalDuration"></div>
                            <div class="text-sm" id="modalPlannedDuration"></div>
                        </div>
                    </div>
                </div>

                {{-- Byproducts Section --}}
                <div id="modalByproductsWrapper" class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 border-2 border-green-300">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Byproducts & Recovery</h3>
                    <div class="space-y-3" id="modalByproductsList">
                        {{-- Populated by JS --}}
                    </div>
                </div>

                {{-- Quality Notes Section --}}
                <div id="modalQualityWrapper" class="bg-blue-50 rounded-xl p-5 border-2 border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Quality Notes</h3>
                    <p class="text-gray-700" id="modalQualityText"></p>
                </div>

            </div>
        </div>
    </div>

</div>

{{-- 
    Simple JavaScript to handle the Modal opening/closing and data population.
    In a real Livewire app, you would likely use Livewire.dispatch('openModal', { batch: id }) instead.
--}}
<script>
    function openBatchModal(batch) {
        const modal = document.getElementById('batchModal');
        
        // Populate Header
        document.getElementById('modalIcon').textContent = batch.recipeIcon;
        document.getElementById('modalTitle').textContent = batch.recipeName;
        document.getElementById('modalSubtitle').textContent = `Batch ${batch.id} • ${batch.section} • ${batch.date}`;
        
        // Priority Badge
        const pBadge = document.getElementById('modalPriority');
        pBadge.textContent = batch.priority;
        pBadge.className = `inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold border-2 uppercase ${getPriorityClassesJS(batch.priority)}`;

        // Timeline
        const timeline = document.getElementById('modalTimeline');
        timeline.innerHTML = `
            <div class="flex items-center gap-4"><div class="w-32 text-sm text-gray-600">Scheduled:</div><div class="font-medium text-gray-900">${batch.scheduledTime}</div></div>
            ${batch.startedAt ? `<div class="flex items-center gap-4"><div class="w-32 text-sm text-gray-600">Started:</div><div class="font-medium text-gray-900">${batch.startedAt}</div></div>` : ''}
            ${batch.completedAt ? `<div class="flex items-center gap-4"><div class="w-32 text-sm text-gray-600">Completed:</div><div class="font-medium text-green-600">${batch.completedAt}</div></div>` : ''}
            ${batch.estimatedCompletion && !batch.completedAt ? `<div class="flex items-center gap-4"><div class="w-32 text-sm text-gray-600">Est. Completion:</div><div class="font-medium text-blue-600">${batch.estimatedCompletion}</div></div>` : ''}
        `;

        // Metrics (Only if completed)
        const metricsWrapper = document.getElementById('modalMetricsWrapper');
        if (batch.status === 'completed') {
            metricsWrapper.style.display = 'block';
            document.getElementById('modalOutput').textContent = `${batch.actualOutput}/${batch.targetQuantity}`;
            
            const effEl = document.getElementById('modalEfficiency');
            effEl.textContent = `${batch.efficiency}% efficiency`;
            effEl.className = `text-sm font-medium ${batch.efficiency >= 95 ? 'text-green-600' : 'text-orange-600'}`;

            const wasteEl = document.getElementById('modalWaste');
            wasteEl.textContent = batch.wasteQuantity || 0;
            wasteEl.className = `text-3xl mb-1 ${batch.wasteQuantity > 0 ? 'text-red-600' : 'text-green-600'}`;
            document.getElementById('modalWasteReason').textContent = batch.wasteQuantity > 0 ? `Reason: ${batch.wasteReason}` : 'No waste - perfect batch!';

            document.getElementById('modalDuration').textContent = `${batch.actualDuration} min`;
            const durEl = document.getElementById('modalPlannedDuration');
            durEl.textContent = `Planned: ${batch.plannedDuration} min`;
            durEl.className = `text-sm ${batch.actualDuration <= batch.plannedDuration ? 'text-green-600' : 'text-orange-600'}`;

        } else {
            metricsWrapper.style.display = 'none';
        }

        // Byproducts
        const byproductsWrapper = document.getElementById('modalByproductsWrapper');
        const byproductsList = document.getElementById('modalByproductsList');
        if (batch.byproducts && batch.byproducts.length > 0) {
            byproductsWrapper.style.display = 'block';
            byproductsList.innerHTML = batch.byproducts.map(bp => `
                <div class="bg-white rounded-xl p-4">
                    <div class="grid grid-cols-4 gap-4">
                        <div><div class="text-sm text-gray-600 mb-1">Product</div><div class="font-medium text-gray-900">${bp.name}</div></div>
                        <div><div class="text-sm text-gray-600 mb-1">Expected</div><div class="text-gray-900">${bp.expected} ${bp.unit}</div></div>
                        <div><div class="text-sm text-gray-600 mb-1">Actual</div><div class="text-gray-900">${bp.actual} ${bp.unit}</div></div>
                        <div>
                            <div class="text-sm text-gray-600 mb-1">Variance</div>
                            <div class="flex items-center gap-1 ${bp.variance > 0 ? 'text-orange-600' : 'text-green-600'}">
                                ${bp.variance}%
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            byproductsWrapper.style.display = 'none';
        }

        // Quality Notes
        const qualityWrapper = document.getElementById('modalQualityWrapper');
        if (batch.qualityNotes) {
            qualityWrapper.style.display = 'block';
            document.getElementById('modalQualityText').textContent = batch.qualityNotes;
        } else {
            qualityWrapper.style.display = 'none';
        }

        modal.classList.remove('hidden');
    }

    function closeBatchModal() {
        document.getElementById('batchModal').classList.add('hidden');
    }

    function getPriorityClassesJS(priority) {
        if(priority === 'high') return 'bg-red-100 text-red-700 border-red-300';
        if(priority === 'medium') return 'bg-orange-100 text-orange-700 border-orange-300';
        if(priority === 'low') return 'bg-blue-100 text-blue-700 border-blue-300';
        return 'bg-gray-100 text-gray-700 border-gray-300';
    }
</script>

@endsection