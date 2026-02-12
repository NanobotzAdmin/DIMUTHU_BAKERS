@extends('layouts.app')
@section('title', 'Production Management')

@section('content')
    {{-- 
        DUMMY DATA FOR UI SIMULATION 
        In a real controller, pass these variables via compact('stats', 'batches', etc.)
    --}}
    @php
        $currentDate = now();
        $daysInMonth = $currentDate->daysInMonth;
        
        $stats = [
            [
                'label' => 'Total Production',
                'value' => '12,450',
                'unit' => 'Units',
                'icon' => 'box', // simulated icon logic
                'color' => 'text-blue-600',
                'bgColor' => 'bg-blue-50',
                'trend' => '+12.5%',
                'trendUp' => true,
            ],
            [
                'label' => 'Efficiency Rate',
                'value' => '94.2',
                'unit' => '%',
                'icon' => 'activity',
                'color' => 'text-green-600',
                'bgColor' => 'bg-green-50',
                'trend' => '+2.4%',
                'trendUp' => true,
            ],
            [
                'label' => 'Active Batches',
                'value' => '8',
                'unit' => 'Running',
                'icon' => 'layers',
                'color' => 'text-purple-600',
                'bgColor' => 'bg-purple-50',
                'status' => '2 requiring QC',
            ],
            [
                'label' => 'Material Alerts',
                'value' => '3',
                'unit' => 'Critical',
                'icon' => 'alert',
                'color' => 'text-amber-600',
                'bgColor' => 'bg-amber-50',
                'warning' => true,
            ],
        ];

        $quickActions = [
            ['title' => 'New Batch', 'desc' => 'Start production run', 'color' => 'from-blue-500 to-blue-600', 'icon' => 'plus'],
            ['title' => 'Recipe Manager', 'desc' => 'Update formulations', 'color' => 'from-emerald-500 to-emerald-600', 'icon' => 'book'],
            ['title' => 'QC Dashboard', 'desc' => 'Verify quality logs', 'color' => 'from-purple-500 to-purple-600', 'icon' => 'clipboard'],
        ];

        $batches = [
            ['id' => 'B-2024-001', 'recipe' => 'Whole Wheat Bread', 'user' => 'John D.', 'qty' => 500, 'unit' => 'Loaves', 'status' => 'in-progress', 'progress' => 65, 'time' => '08:00 AM', 'qc' => false],
            ['id' => 'B-2024-002', 'recipe' => 'Butter Cake', 'user' => 'Sarah M.', 'qty' => 200, 'unit' => 'Units', 'status' => 'completed', 'progress' => 100, 'time' => '07:30 AM', 'qc' => true],
            ['id' => 'B-2024-003', 'recipe' => 'Croissants', 'user' => 'Mike R.', 'qty' => 1000, 'unit' => 'Pcs', 'status' => 'preparing', 'progress' => 10, 'time' => '09:15 AM', 'qc' => false],
            ['id' => 'B-2024-004', 'recipe' => 'Chocolate Muffins', 'user' => 'John D.', 'qty' => 350, 'unit' => 'Pcs', 'status' => 'quality-check', 'progress' => 90, 'time' => '08:45 AM', 'qc' => true],
        ];

        $alerts = [
            ['material' => 'Flour (Type 55)', 'type' => 'low-stock', 'current' => '50kg', 'threshold' => '100kg', 'severity' => 'critical', 'action' => 'Reorder Immediately'],
            ['material' => 'Fresh Yeast', 'type' => 'expiring', 'date' => '2024-12-15', 'days_left' => 3, 'severity' => 'warning', 'action' => 'Prioritize Usage'],
        ];
        
        // Helper for status colors
        function getStatusColor($status) {
            return match($status) {
                'completed' => 'bg-green-100 text-green-700 border-green-200',
                'in-progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                'quality-check' => 'bg-purple-100 text-purple-700 border-purple-200',
                default => 'bg-gray-100 text-gray-700 border-gray-200',
            };
        }
    @endphp

    <div class="min-h-screen bg-[#F5F5F7] pb-10">
        
        {{-- Header Section --}}
        <div class="bg-white border-b border-gray-200 px-6 py-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F59E0B] to-[#D97706] rounded-xl flex items-center justify-center shadow-sm">
                            {{-- ChefHat Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" y1="17" x2="18" y2="17"/></svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">Production Management</h1>
                            <p class="text-gray-500 text-sm">Comprehensive production planning and tracking system</p>
                        </div>
                    </div>
                </div>
                <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-sm">
                    Coming Soon
                </span>
            </div>
        </div>

        <div class="p-6 max-w-[1800px] mx-auto">
            
            {{-- Top Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                @foreach($stats as $stat)
                <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer border border-gray-100">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl {{ $stat['bgColor'] }} flex items-center justify-center">
                            {{-- Dynamic Icons based on simulation --}}
                            @if($stat['icon'] == 'box')
                                <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            @elseif($stat['icon'] == 'activity')
                                <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            @elseif($stat['icon'] == 'layers')
                                <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            @else
                                <svg class="w-6 h-6 {{ $stat['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            @endif
                        </div>

                        @if(isset($stat['trend']))
                        <div class="flex items-center gap-1 px-2 py-1 rounded-lg {{ $stat['trendUp'] ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                            <svg class="w-3 h-3 {{ !$stat['trendUp'] ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            <span class="text-xs font-medium">{{ $stat['trend'] }}</span>
                        </div>
                        @endif

                        @if(isset($stat['warning']))
                        <div class="flex items-center gap-1 px-2 py-1 rounded-lg bg-amber-100">
                             <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <div class="text-gray-500 text-sm">{{ $stat['label'] }}</div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-gray-900">{{ $stat['value'] }}</span>
                            <span class="text-sm text-gray-500">{{ $stat['unit'] }}</span>
                        </div>
                        @if(isset($stat['status']))
                            <div class="text-sm text-gray-600 mt-1">{{ $stat['status'] }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Quick Actions Panel --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                @foreach($quickActions as $action)
                <button class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all border border-gray-100 group text-left">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br {{ $action['color'] }} rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            {{-- Simple Icons --}}
                            @if($action['icon'] == 'plus')
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            @elseif($action['icon'] == 'book')
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            @else
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-gray-900 font-semibold mb-1 group-hover:text-[#F59E0B] transition-colors">
                                {{ $action['title'] }}
                            </h3>
                            <p class="text-sm text-gray-500">{{ $action['desc'] }}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-[#F59E0B] group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </div>
                </button>
                @endforeach
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Active Batches Table --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 h-full">
                        {{-- Table Header --}}
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-bold text-gray-900">Active Production Batches</h2>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ count($batches) }} Active
                                </span>
                            </div>

                            {{-- Filters --}}
                            <div class="flex items-center gap-3">
                                <div class="relative flex-1">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    <input 
                                        type="text" 
                                        placeholder="Search batches..." 
                                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F59E0B] focus:border-transparent"
                                    >
                                </div>
                                <select class="w-[180px] px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F59E0B]">
                                    <option value="all">All Status</option>
                                    <option value="preparing">Preparing</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="quality-check">Quality Check</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        {{-- Table Content --}}
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipe</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($batches as $batch)
                                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                                <span class="text-sm font-medium text-gray-900">{{ $batch['id'] }}</span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">Started: {{ $batch['time'] }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $batch['recipe'] }}</div>
                                            <div class="text-xs text-gray-500">By {{ $batch['user'] }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 font-medium">{{ $batch['qty'] }} {{ $batch['unit'] }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ getStatusColor($batch['status']) }}">
                                                {{ ucfirst(str_replace('-', ' ', $batch['status'])) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 w-32">
                                            <div class="space-y-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-600">{{ $batch['progress'] }}%</span>
                                                    @if($batch['qc'])
                                                        <svg class="w-3 h-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @endif
                                                </div>
                                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                                    <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $batch['progress'] }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button class="p-1 hover:bg-gray-100 rounded text-gray-500 hover:text-blue-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </button>
                                                <button class="p-1 hover:bg-gray-100 rounded text-gray-500 hover:text-[#F59E0B]">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Table Footer --}}
                        <div class="p-4 border-t border-gray-100 flex items-center justify-center">
                            <button class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                View All Batches
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Production Calendar Widget --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-gray-900">Production Calendar</h3>
                            <svg class="w-5 h-5 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>

                        {{-- Calendar Header --}}
                        <div class="flex items-center justify-between mb-4">
                            <button class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <span class="text-sm font-medium text-gray-900">{{ $currentDate->format('F Y') }}</span>
                            <button class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>

                        {{-- Weekdays --}}
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                                <div class="text-center text-xs text-gray-500 py-2">{{ $day }}</div>
                            @endforeach
                        </div>

                        {{-- Days Grid (Simplified logic for visuals) --}}
                        <div class="grid grid-cols-7 gap-1">
                            @for($i = 1; $i <= $daysInMonth; $i++)
                                @php
                                    $isToday = $i == date('j');
                                    // Simulation: Even days are production, Multiples of 5 are high volume
                                    $hasProduction = $i % 2 == 0;
                                    $isHighVolume = $i % 5 == 0;
                                @endphp
                                <button class="aspect-square rounded-lg text-sm transition-all hover:scale-105 flex items-center justify-center
                                    {{ $isToday ? 'bg-[#F59E0B] text-white font-bold ring-2 ring-[#F59E0B] ring-offset-2' : 
                                       ($hasProduction 
                                            ? ($isHighVolume ? 'bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white' : 'bg-[#F59E0B]/20 text-[#D97706]')
                                            : 'hover:bg-gray-100 text-gray-700') 
                                    }}">
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>

                        {{-- Legend --}}
                        <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                            <div class="flex items-center gap-2 text-xs">
                                <div class="w-4 h-4 rounded bg-[#F59E0B]/20"></div>
                                <span class="text-gray-600">Scheduled Production</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <div class="w-4 h-4 rounded bg-gradient-to-br from-[#F59E0B] to-[#D97706]"></div>
                                <span class="text-gray-600">High Volume Day</span>
                            </div>
                        </div>

                        {{-- Quick Calendar Stats --}}
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">14</div>
                                    <div class="text-xs text-gray-500">Days Scheduled</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900">6</div>
                                    <div class="text-xs text-gray-500">High Volume</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Production Management Navigation Cards --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Wastage Tracking Card --}}
                <a href="{{ url('/production/wastage-tracking') }}" class="block bg-gradient-to-br from-red-50 via-orange-50 to-amber-50 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all border-2 border-red-200 hover:border-red-300 group text-left cursor-pointer decoration-0">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-orange-600 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform shadow-md">
                            {{-- Trash2 Icon --}}
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">
                                Wastage Tracking
                            </h3>
                            <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                                Monitor waste recovery system, track byproducts, and optimize resource utilization
                            </p>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <span class="text-gray-600">5 waste events today</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                    <span class="text-gray-600">68% recovery rate</span>
                                </div>
                            </div>
                        </div>
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-red-600 group-hover:translate-x-2 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                </a>

                {{-- Batch Tracking Card --}}
                <a href="{{ url('/production/batch-tracking') }}" class="block bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all border-2 border-blue-200 hover:border-blue-300 group text-left cursor-pointer decoration-0">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform shadow-md">
                            {{-- BarChart3 Icon --}}
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 3v18h18"></path>
                                <path d="M18 17V9"></path>
                                <path d="M13 17V5"></path>
                                <path d="M8 17v-3"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                Batch Tracking & History
                            </h3>
                            <p class="text-gray-600 mb-4 text-sm leading-relaxed">
                                View production batch analytics, performance metrics, and historical data
                            </p>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                    <span class="text-gray-600">24 completed batches</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                    <span class="text-gray-600">96.5% avg efficiency</span>
                                </div>
                            </div>
                        </div>
                        <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-2 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                </a>
            </div>

            {{-- Material Alerts Section --}}
            <div class="mt-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">Material Alerts</h2>
                                    <p class="text-sm text-gray-500">Critical inventory notifications</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                {{ count($alerts) }} Alerts
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($alerts as $alert)
                            <div class="rounded-xl p-4 {{ $alert['severity'] === 'critical' ? 'bg-red-50 border border-red-100' : 'bg-amber-50 border border-amber-100' }} hover:shadow-md transition-shadow cursor-pointer">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            @if($alert['type'] == 'low-stock')
                                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            @else
                                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @endif
                                            <span class="text-sm font-bold text-gray-900">{{ $alert['material'] }}</span>
                                        </div>
                                        
                                        @if(isset($alert['current']))
                                            <div class="text-xs text-gray-600 mb-1">
                                                Current: {{ $alert['current'] }} / Threshold: {{ $alert['threshold'] }}
                                            </div>
                                        @endif
                                        
                                        @if(isset($alert['date']))
                                            <div class="text-xs text-red-600 mb-1">
                                                Expires: {{ $alert['date'] }} ({{ $alert['days_left'] }} days left)
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $alert['severity'] === 'critical' ? 'bg-red-600 text-white' : 'bg-amber-600 text-white' }}">
                                        {{ ucfirst($alert['severity']) }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-700 font-medium">{{ $alert['action'] }}</span>
                                    <button class="px-3 py-1 bg-white border border-gray-200 rounded text-xs font-medium hover:bg-gray-50 transition-colors">
                                        Take Action
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection