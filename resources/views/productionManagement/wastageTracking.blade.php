@extends('layouts.app')
@section('title', 'Wastage Tracking')

@section('content')

{{-- 
    LOGIC BLOCK
    In a real app, move this data and calculation to your Controller.
--}}
@php
    // 1. Mock Data
    $wastageData = [
        [
            'id' => 'WASTE-001',
            'date' => '2025-12-06',
            'batchId' => 'BATCH-002',
            'recipeName' => 'Bread Loaves',
            'recipeIcon' => '🍞',
            'section' => 'Bakery',
            'wasteType' => 'Production Waste',
            'quantity' => 2,
            'unit' => 'loaves',
            'reason' => 'Quality Issue - Burnt',
            'estimatedValue' => 800,
            'recoverable' => false,
            'recoveryValue' => 0,
            'byproducts' => [
                ['name' => 'Bread Trimmings', 'quantity' => 0.9, 'unit' => 'kg', 'recoveryValue' => 180]
            ]
        ],
        [
            'id' => 'WASTE-002',
            'date' => '2025-12-06',
            'batchId' => 'BATCH-001',
            'recipeName' => 'Chocolate Cake',
            'recipeIcon' => '🎂',
            'section' => 'Kitchen',
            'wasteType' => 'Byproduct',
            'quantity' => 0.5,
            'unit' => 'kg',
            'reason' => 'Cake Scraps',
            'estimatedValue' => 250,
            'recoverable' => true,
            'recoveredTo' => 'Cake Pops',
            'recoveryValue' => 400,
            'byproducts' => []
        ],
        [
            'id' => 'WASTE-003',
            'date' => '2025-12-05',
            'batchId' => 'BATCH-003',
            'recipeName' => 'Croissants',
            'recipeIcon' => '🥐',
            'section' => 'Bakery',
            'wasteType' => 'Raw Material',
            'quantity' => 0.8,
            'unit' => 'kg',
            'reason' => 'Contamination',
            'estimatedValue' => 640,
            'recoverable' => false,
            'recoveryValue' => 0,
            'byproducts' => []
        ],
        [
            'id' => 'WASTE-004',
            'date' => '2025-12-05',
            'batchId' => 'BATCH-006',
            'recipeName' => 'Red Velvet Cake',
            'recipeIcon' => '❤️',
            'section' => 'Cake',
            'wasteType' => 'Byproduct',
            'quantity' => 0.4,
            'unit' => 'kg',
            'reason' => 'Cake Scraps',
            'estimatedValue' => 200,
            'recoverable' => true,
            'recoveredTo' => 'Cake Crumble Topping',
            'recoveryValue' => 320,
            'byproducts' => []
        ],
        [
            'id' => 'WASTE-005',
            'date' => '2025-12-04',
            'batchId' => 'BATCH-004',
            'recipeName' => 'Vanilla Cupcakes',
            'recipeIcon' => '🧁',
            'section' => 'Kitchen',
            'wasteType' => 'Production Waste',
            'quantity' => 5,
            'unit' => 'pcs',
            'reason' => 'Quality Issue - Undercooked',
            'estimatedValue' => 500,
            'recoverable' => false,
            'recoveryValue' => 0,
            'byproducts' => []
        ]
    ];

    // 2. Statistics Calculation
    $totalWasteCount = count($wastageData);
    $totalValue = array_reduce($wastageData, fn($carry, $item) => $carry + $item['estimatedValue'], 0);
    $recoveredValue = array_reduce($wastageData, function($carry, $item) {
        return $carry + ($item['recoverable'] ? $item['recoveryValue'] : 0);
    }, 0);
    
    $recoveryRate = $totalValue > 0 ? ($recoveredValue / $totalValue) * 100 : 0;
    $trueWaste = $totalValue - $recoveredValue;

    // 3. Helper for Colors (mimicking the React switch statement)
    function getWasteTypeClasses($type) {
        return match($type) {
            'Production Waste' => 'bg-red-100 text-red-700 border-red-300',
            'Byproduct' => 'bg-green-100 text-green-700 border-green-300',
            'Raw Material' => 'bg-orange-100 text-orange-700 border-orange-300',
            default => 'bg-gray-100 text-gray-700 border-gray-300',
        };
    }
    
    // 4. Dropdowns
    $sections = array_unique(array_column($wastageData, 'section'));
    $wasteTypes = ['Production Waste', 'Byproduct', 'Raw Material'];
@endphp

<div class="min-h-screen bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 p-4 md:p-6">
    
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                    {{-- Icon: Trash2 --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Wastage Tracking</h1>
                    <p class="text-gray-600">Three-Stage Waste Recovery System</p>
                </div>
            </div>

            {{-- Export Button --}}
            <button class="h-12 px-6 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all shadow-sm">
                {{-- Icon: Download --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                Export Report
            </button>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            {{-- Total Waste Events --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    <span class="text-sm text-gray-600">Total Waste Events</span>
                </div>
                <div class="text-3xl font-semibold text-gray-900">{{ $totalWasteCount }}</div>
            </div>

            {{-- Waste Value --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="1" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    <span class="text-sm text-gray-600">Waste Value</span>
                </div>
                <div class="text-3xl font-semibold text-gray-900">Rs {{ number_format($totalValue) }}</div>
            </div>

            {{-- Recovered Value --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-green-200">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                    <span class="text-sm text-gray-600">Recovered Value</span>
                </div>
                <div class="text-3xl font-semibold text-green-600">Rs {{ number_format($recoveredValue) }}</div>
            </div>

            {{-- Recovery Rate --}}
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-5 shadow-sm border-2 border-green-300">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.77 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                    <span class="text-sm text-green-700">Recovery Rate</span>
                </div>
                <div class="text-3xl font-semibold text-green-700">{{ number_format($recoveryRate, 1) }}%</div>
                {{-- Progress Bar --}}
                <div class="relative h-2 w-full overflow-hidden rounded-full bg-green-200 mt-2">
                    <div class="h-full bg-green-600 transition-all duration-300" style="width: {{ $recoveryRate }}%"></div>
                </div>
            </div>

            {{-- True Waste --}}
            <div class="bg-white rounded-xl p-5 shadow-sm border-2 border-red-200">
                <div class="flex items-center gap-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                    <span class="text-sm text-gray-600">True Waste</span>
                </div>
                <div class="text-3xl font-semibold text-red-600">Rs {{ number_format($trueWaste) }}</div>
            </div>
        </div>

        {{-- Filters (Functional UI mostly, needs Livewire/JS for instant filtering) --}}
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-sm text-gray-600 mb-2">Section</label>
                <select class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl outline-none focus:border-amber-500 transition-colors">
                    <option value="all">All Sections</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec }}">{{ $sec }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1">
                <label class="block text-sm text-gray-600 mb-2">Waste Type</label>
                <select class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl outline-none focus:border-amber-500 transition-colors">
                    <option value="all">All Types</option>
                    @foreach($wasteTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Wastage Records List --}}
    <div class="space-y-4">
        @forelse($wastageData as $waste)
            <div class="bg-white rounded-2xl p-5 shadow-sm border-2 transition-all {{ $waste['recoverable'] ? 'border-green-200' : 'border-red-200' }}">
                <div class="flex flex-col md:flex-row items-start gap-4">
                    
                    {{-- Recipe Icon Column --}}
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-16 h-16 rounded-xl flex items-center justify-center shadow-md {{ $waste['recoverable'] ? 'bg-green-500' : 'bg-red-500' }}">
                            <span class="text-3xl">{{ $waste['recipeIcon'] }}</span>
                        </div>
                        @if($waste['recoverable'])
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 bg-green-100 text-green-700 border-green-300 gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                                Recovered
                            </span>
                        @endif
                    </div>

                    {{-- Waste Info --}}
                    <div class="flex-1 w-full">
                        <div class="flex flex-col md:flex-row items-start justify-between mb-3">
                            <div>
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <h3 class="text-2xl font-semibold text-gray-900">{{ $waste['recipeName'] }}</h3>
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ getWasteTypeClasses($waste['wasteType']) }} border-2">
                                        {{ $waste['wasteType'] }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-4 text-gray-600 text-sm">
                                    <div class="flex items-center gap-2">
                                        {{-- Icon: Calendar --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                        <span>{{ $waste['date'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{-- Icon: ChefHat --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" x2="18" y1="17" y2="17"/></svg>
                                        <span>{{ $waste['section'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        {{-- Icon: Package --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                        <span>Batch: {{ $waste['batchId'] }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Status Badge (Right Side) --}}
                            <div class="mt-2 md:mt-0 flex items-center gap-2 px-4 py-2 rounded-xl {{ $waste['recoverable'] ? 'bg-green-100' : 'bg-red-100' }}">
                                @if($waste['recoverable'])
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                                @endif
                                <span class="font-medium {{ $waste['recoverable'] ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $waste['recoverable'] ? 'Recovered' : 'True Waste' }}
                                </span>
                            </div>
                        </div>

                        {{-- Waste Details Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3">
                            <div class="bg-gray-50 rounded-xl p-3">
                                <div class="text-sm text-gray-600 mb-1">Waste Quantity</div>
                                <div class="text-xl font-medium text-gray-900 flex items-center gap-2">
                                    {{-- Icon: Scale --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="m2 16 3-8 3 8c-.87.65-1.92 1-3 1s-2.13-.35-3-1Z"/><path d="M7 21h10"/><path d="M12 3v18"/><path d="M3 7h2c2 0 5-1 7-2 2 1 5 2 7 2h2"/></svg>
                                    {{ $waste['quantity'] }} {{ $waste['unit'] }}
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-3">
                                <div class="text-sm text-gray-600 mb-1">Estimated Value</div>
                                <div class="text-xl font-medium text-red-600 flex items-center gap-2">
                                    {{-- Icon: TrendingDown --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg>
                                    Rs {{ number_format($waste['estimatedValue']) }}
                                </div>
                            </div>

                            @if($waste['recoverable'])
                                <div class="bg-green-50 rounded-xl p-3 border-2 border-green-200">
                                    <div class="text-sm text-green-700 mb-1">Recovery Value</div>
                                    <div class="text-xl font-medium text-green-600 flex items-center gap-2">
                                        {{-- Icon: TrendingUp --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                                        Rs {{ number_format($waste['recoveryValue']) }}
                                    </div>
                                </div>

                                <div class="bg-green-50 rounded-xl p-3 border-2 border-green-200">
                                    <div class="text-sm text-green-700 mb-1">Recovered To</div>
                                    <div class="text-base text-green-900 font-medium">
                                        {{ $waste['recoveredTo'] }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Reason --}}
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-sm text-gray-600">Reason:</span>
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors bg-gray-200 text-gray-700">
                                {{ $waste['reason'] }}
                            </span>
                        </div>

                        {{-- Byproducts --}}
                        @if(!empty($waste['byproducts']))
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border-2 border-green-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.77 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                                    <span class="font-medium text-green-900">Byproducts Recovered</span>
                                </div>
                                <div class="space-y-2">
                                    @foreach($waste['byproducts'] as $byproduct)
                                        <div class="flex items-center justify-between bg-white rounded-lg p-3">
                                            <div class="flex items-center gap-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                                                <span class="text-gray-900">{{ $byproduct['name'] }}</span>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span class="text-gray-700">
                                                    {{ $byproduct['quantity'] }} {{ $byproduct['unit'] }}
                                                </span>
                                                <span class="text-green-600 font-medium">
                                                    +Rs {{ number_format($byproduct['recoveryValue']) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="bg-white rounded-2xl p-12 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Waste Records</h3>
                <p class="text-gray-600">No wastage found. Great job!</p>
            </div>
        @endforelse
    </div>
</div>

@endsection