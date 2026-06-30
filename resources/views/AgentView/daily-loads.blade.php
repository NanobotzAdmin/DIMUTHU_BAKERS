@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#EDEFF5]">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#059669] to-[#047857] rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                        <i class="bi bi-calendar2-day text-white text-2xl"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Daily Loads</h1>
                        <p class="text-gray-500 text-xs sm:text-sm">View, verify, and start loaded route trips</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                <a href="{{ route('agent-panel.daily-loads.create') }}"
                   class="bg-[#059669] hover:bg-[#047857] text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-colors text-sm font-semibold cursor-pointer">
                    <i class="bi bi-plus-lg mr-2"></i>
                    Create Daily Load
                </a>
                <a href="{{ route('agent-panel.dashboard') }}"
                   class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg flex items-center shadow-sm transition-colors text-sm font-medium">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-[1800px] mx-auto">
        <!-- Loads List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="loadsContainer">
            @forelse($loads as $load)
                @php
                    $statusClasses = [
                        1 => 'bg-amber-100 text-amber-800 border-amber-200',
                        2 => 'bg-blue-100 text-blue-800 border-blue-200',
                        3 => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                        4 => 'bg-purple-100 text-purple-800 border-purple-200',
                        5 => 'bg-emerald-100 text-emerald-800 border-emerald-200'
                    ];
                    $statusLabels = [
                        1 => 'Loading / Draft',
                        2 => 'Loaded',
                        3 => 'Started / On Trip',
                        4 => 'Unloaded',
                        5 => 'Finished'
                    ];
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-gray-900 font-bold text-lg">LOAD-{{ $load->id }}</h3>
                                <p class="text-gray-500 text-xs mt-1">Date: {{ $load->load_date->format('M d, Y') }}</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusClasses[$load->load_status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                {{ $statusLabels[$load->load_status] ?? 'Unknown' }}
                            </span>
                        </div>

                        <!-- Info details -->
                        <div class="space-y-3 mt-4 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-sign-turn-right text-gray-400"></i>
                                <span class="font-medium text-gray-800">Route:</span>
                                <span>{{ $load->route ? $load->route->route_name : 'No Route Assigned' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-person-badge text-gray-400"></i>
                                <span class="font-medium text-gray-800">Driver:</span>
                                <span>{{ $load->driver ? $load->driver->driver_name : 'Unassigned' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-truck text-gray-400"></i>
                                <span class="font-medium text-gray-800">Vehicle:</span>
                                <span>{{ $load->vehicle ? $load->vehicle->vehicle_number : 'Unassigned' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-shield-check text-gray-400"></i>
                                <span class="font-medium text-gray-800">Supervisor:</span>
                                <span>{{ $load->supervisor ? $load->supervisor->superviser_name : 'Unassigned' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            <i class="bi bi-box-seam mr-1"></i> {{ $load->items_count }} Product Items
                        </div>
                        <a href="{{ route('agent-panel.daily-loads.show', $load->id) }}"
                           class="bg-[#059669] hover:bg-[#047857] text-white px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                            <i class="bi bi-eye"></i> View details
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-12 text-center bg-white rounded-xl border border-gray-200 border-dashed">
                    <i class="bi bi-box-seam text-gray-400 text-6xl mb-4 block"></i>
                    <h3 class="text-gray-900 text-lg font-medium mb-2">No loads found</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Wait for your supervisor to approve and allocate loads for your route.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
