@extends('layouts.app')

@section('content')
<div class="px-2 py-2 md:px-6 md:py-6">
    <!-- View Toggler (Main Container) -->
    <div id="target-main-view">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight font-sans">Agent Monthly Targets</h1>
                <p class="mt-1 text-base text-gray-500 font-medium font-sans">Manage and monitor performance goals for your agent network.</p>
            </div>
            <div class="flex items-center gap-3 font-sans">
                <button class="h-12 px-6 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg shadow-indigo-100 transition-all flex items-center border-0 active:scale-95" onclick="showCreateView()">
                    <i class="bi bi-plus-lg mr-2"></i> Create Target
                </button>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 transform transition-all hover:shadow-md font-sans">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                <div class="md:col-span-12 lg:col-span-5">
                    <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider mb-2">Find Agent</label>
                    <select id="filter-agent" class="w-full h-12 rounded-xl border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-gray-700 font-medium select2">
                        <option value="">All Agents</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->agent_name }} ({{ $agent->agent_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-6 lg:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Year</label>
                    <select id="filter-year" class="w-full h-12 rounded-xl border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-semibold px-4 text-gray-700">
                        <option value="">All Years</option>
                        @php $currentYear = date('Y'); @endphp
                        @for($y = $currentYear - 2; $y <= $currentYear + 1; $y++)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="md:col-span-6 lg:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Month</label>
                    <select id="filter-month" class="w-full h-12 rounded-xl border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-semibold px-4 text-gray-700">
                        <option value="">All Months</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-12 lg:col-span-1">
                    <button class="w-full h-12 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl transition-all flex items-center justify-center border-0" onclick="resetFilters()">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Targets List Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden font-sans">
            <div class="overflow-x-auto">
                <table class="w-full text-left" id="targets-list-table">
                    <thead class="bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Agent</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Period</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Sales Target</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest">Comm.</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Payment</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-5 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($monthlyTargets as $target)
                        <tr class="hover:bg-gray-50/50 transition-colors target-row" 
                            data-agent="{{ $target->agent_id }}" 
                            data-year="{{ $target->target_year }}" 
                            data-month="{{ $target->target_month }}">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                        {{ substr($target->agent->agent_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $target->agent->agent_name }}</div>
                                        <div class="text-xs text-gray-500 font-medium">{{ $target->agent->agent_code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-gray-100 text-gray-700 text-sm font-bold">
                                    {{ date('M', mktime(0, 0, 0, $target->target_month, 1)) }} {{ $target->target_year }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="font-bold text-gray-900">LKR {{ number_format($target->monthly_sales_target, 2) }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="font-bold text-indigo-600">LKR {{ number_format($target->monthly_commission, 2) }}</span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($target->payment_status == 1)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-blue-50 text-blue-600 border border-blue-100">Processed</span>
                                    @elseif($target->payment_status == 2)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-green-50 text-green-600 border border-green-100">Paid</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter bg-gray-50 text-gray-500 border border-gray-100">Pending</span>
                                    @endif
                                    <button onclick="updatePaymentStatus({{ $target->id }}, {{ $target->payment_status }})" class="w-7 h-7 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-indigo-600 hover:bg-white hover:shadow-sm transition-all border-0" title="Change Payment Status">
                                        <i class="bi bi-arrow-repeat text-sm"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($target->status == 1)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span> Active
                                    </span>
                                @elseif($target->status == 2)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span> Inactive
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-600 border border-orange-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mr-2"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <button class="h-9 px-4 rounded-lg bg-white border border-gray-200 text-gray-600 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all font-bold text-sm shadow-sm flex items-center justify-center ml-auto" onclick="editTarget({{ $target->agent_id }}, {{ $target->target_year }}, {{ $target->target_month }})">
                                    <i class="bi bi-pencil mr-2"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                                        <i class="bi bi-folder-x text-3xl text-gray-300"></i>
                                    </div>
                                    <p class="text-gray-400 font-medium font-sans">No records found matching filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Form View (Hidden by default) -->
    <div id="target-editor-view" class="hidden animate-fadeIn font-sans">
        <!-- Editor Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <button class="mb-2 text-indigo-600 hover:text-indigo-700 text-sm font-bold flex items-center transition-colors px-0 bg-transparent border-0" onclick="hideEditorView()">
                    <i class="bi bi-arrow-left mr-1"></i> Back to List
                </button>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight" id="editor-title">Configure Targets</h1>
            </div>
        </div>

        <!-- Initial Selectors -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                <div class="md:col-span-12 lg:col-span-5">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">Target Agent</label>
                        <button type="button" class="hidden text-indigo-600 hover:text-indigo-700 text-sm font-bold flex items-center transition-colors px-0 bg-transparent border-0" onclick="openQuickAgentModal()">
                            <i class="bi bi-person-plus-fill mr-1"></i> Quick Recruit
                        </button>
                    </div>
                    <select id="agent-selector" class="w-full h-12 rounded-xl border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-gray-700 font-medium select2">
                        <option value="">Select Agent...</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->agent_name }} ({{ $agent->agent_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-6 lg:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Target Year</label>
                    <select id="year-selector" class="w-full h-12 rounded-xl border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-semibold px-4 text-gray-700">
                        @php $currentYear = date('Y'); @endphp
                        @for($y = $currentYear - 1; $y <= $currentYear + 2; $y++)
                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="md:col-span-6 lg:col-span-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wider">Target Month</label>
                    <select id="month-selector" class="w-full h-12 rounded-xl border-gray-100 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-semibold px-4 text-gray-700">
                        @php $currentMonth = date('n'); @endphp
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-12 lg:col-span-1">
                    <button id="verify-selection" class="w-full h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl transition-all flex items-center justify-center border-0" onclick="loadTargetsForSelection()">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Target Detail Editor Section (Visible after verify/load) -->
        <div id="target-detail-editor" class="space-y-8 animate-fadeIn" style="display: none;">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Panel: Summary & Status -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
                        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 px-6 py-5">
                            <h3 class="text-white font-bold flex items-center">
                                <i class="bi bi-graph-up-arrow mr-2.5"></i> Financial Objective
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Monthly Sales Target (LKR)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">LKR</span>
                                    <input type="text" id="monthly-sales-target" class="w-full h-14 pl-14 pr-4 rounded-xl border-gray-100 bg-gray-50 text-2xl font-bold text-indigo-700 focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all amount-input" placeholder="0.00">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-6 hidden">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Base Salary</label>
                                    <input type="number" id="base-salary" class="w-full h-12 px-4 rounded-xl border-gray-100 bg-gray-50 font-bold text-gray-700 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 transition-all" placeholder="0.00">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Comm. Rate (%)</label>
                                    <input type="number" id="commission-rate" class="w-full h-12 px-4 rounded-xl border-gray-100 bg-gray-50 font-bold text-gray-700 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 transition-all" placeholder="0.00">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Inv. Comm. (%)</label>
                                    <input type="number" id="invoicing-commission-rate" class="w-full h-12 px-4 rounded-xl border-gray-100 bg-gray-50 font-bold text-gray-700 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 transition-all" placeholder="15.00">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Target Comm. (%)</label>
                                    <input type="number" id="target-commission-rate" class="w-full h-12 px-4 rounded-xl border-gray-100 bg-gray-50 font-bold text-gray-700 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 transition-all" placeholder="5.00">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-6 hidden">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Threshold (%)</label>
                                    <input type="number" id="achievement-threshold" class="w-full h-12 px-4 rounded-xl border-gray-100 bg-gray-50 font-bold text-gray-700 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 transition-all" placeholder="80.00">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Redu. Comm. (%)</label>
                                    <input type="number" id="reduced-target-commission-rate" class="w-full h-12 px-4 rounded-xl border-gray-100 bg-gray-50 font-bold text-gray-700 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 transition-all" placeholder="4.00">
                                </div>
                            </div>
                            <div class="mb-6 hidden">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">Planning Status</label>
                                <select id="target-status" class="w-full h-12 rounded-xl border-gray-100 bg-gray-50 font-bold transition-all px-4 text-gray-700">
                                    <option value="1" class="text-green-600 font-bold font-sans">Active</option>
                                    <option value="2" class="text-red-500 font-bold font-sans">Inactive</option>
                                    <option value="0" class="text-orange-500 font-bold font-sans">Draft</option>
                                </select>
                            </div>
                            <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100/50">
                                <div class="flex gap-3">
                                    <i class="bi bi-info-circle-fill text-indigo-500 mt-0.5"></i>
                                    <p class="text-sm text-indigo-700/80 leading-relaxed font-medium">Define overall total revenue goals before distributing by categories.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Tables -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Category Targets -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between bg-gray-50/50 gap-4">
                            <div>
                                <h3 class="font-bold text-gray-900">Category Goals</h3>
                                <p class="text-xs text-gray-500 font-medium">Target revenue by product categories</p>
                            </div>
                            <button class="h-10 px-4 rounded-lg bg-indigo-600 text-white text-sm font-bold shadow-sm hover:bg-indigo-700 transition-all flex items-center border-0" onclick="addRow('category-targets-table')">
                                <i class="bi bi-plus-lg mr-2"></i> Add Category
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse table-auto" id="category-targets-table">
                                <thead class="bg-gray-50/80 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest w-2/5">Category</th>
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Amount (LKR)</th>
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Weight (%)</th>
                                        <th class="px-6 py-3 w-16"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SKU Targets -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between bg-gray-50/50 gap-4">
                            <div>
                                <h3 class="font-bold text-gray-900">SKU Detailed Targets</h3>
                                <p class="text-xs text-gray-500 font-medium font-sans">Granular planning per product SKU</p>
                            </div>
                            <button class="h-10 px-4 rounded-lg bg-indigo-600 text-white text-sm font-bold shadow-sm hover:bg-indigo-700 transition-all flex items-center border-0" onclick="addRow('sku-targets-table')">
                                <i class="bi bi-plus-lg mr-2"></i> Add Item
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse table-auto" id="sku-targets-table">
                                <thead class="bg-gray-50/80 border-b border-gray-100 font-sans">
                                    <tr>
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest w-2/5 font-sans">Product / SKU</th>
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest text-center font-sans">Quantity</th>
                                        <th class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest text-center font-sans">Weight (%)</th>
                                        <th class="px-6 py-3 w-16"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 font-sans"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Save Controller -->
            <div class="sticky bottom-0 bg-white/90 backdrop-blur-md border-t border-gray-100 p-5 -mx-6 md:-mx-8 px-8 flex flex-col md:flex-row items-center justify-between gap-4 z-50 rounded-t-3xl shadow-[0_-10px_30px_-15px_rgba(0,0,0,0.1)]">
                <div class="flex items-center gap-3 font-sans">
                    <div class="h-10 px-4 rounded-full bg-gray-100 flex items-center text-sm font-bold text-gray-600 font-sans">
                        <i class="bi bi-calculator mr-2 text-indigo-500"></i> Total Weight: <span id="total-weight-percentage" class="ml-1 text-indigo-700 font-bold font-sans">0%</span>
                    </div>
                </div>
                <div class="flex gap-3 w-full md:w-auto font-sans">
                    <button class="flex-1 md:flex-none h-12 px-8 rounded-xl bg-gray-50 text-gray-500 font-bold hover:bg-gray-100 transition-all border-0 font-sans" onclick="hideEditorView()">Discard</button>
                    <button class="flex-1 md:flex-none h-12 px-10 rounded-xl bg-green-600 hover:bg-green-700 text-white font-bold shadow-lg shadow-green-100 transition-all transform active:scale-95 flex items-center justify-center group border-0 font-sans" id="save-targets">
                        <i class="bi bi-check-circle-fill mr-2.5"></i> Save Targets
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Row Template -->
<template id="category-row-template">
    <tr class="hover:bg-gray-50/50 transition-colors border-0">
        <td class="px-6 py-3 border-0">
            <select class="w-full h-11 border border-gray-100 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 font-semibold text-gray-700 select-category font-sans px-3">
                <option value="">Choose Category</option>
                @foreach($productCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->category_name }} ({{ $cat->category_code }})</option>
                @endforeach
            </select>
        </td>
        <td class="px-6 py-3 border-0">
            <div class="relative max-w-[180px] mx-auto">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold font-sans">LKR</span>
                <input type="text" class="w-full h-11 pl-12 pr-4 border border-gray-100 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 font-bold text-gray-700 target-amount amount-input font-sans" placeholder="0.00">
            </div>
        </td>
        <td class="px-6 py-3 border-0">
            <div class="flex items-center justify-center gap-2 max-w-[100px] mx-auto font-sans">
                <input type="number" class="w-16 h-11 border border-gray-100 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 font-bold text-indigo-600 text-center target-percentage font-sans" placeholder="0">
                <span class="text-gray-400 font-bold font-sans">%</span>
            </div>
        </td>
        <td class="px-6 py-3 text-right border-0">
            <button class="w-9 h-9 flex items-center justify-center rounded-full text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all remove-row border-0 bg-transparent font-sans">
                <i class="bi bi-trash3-fill"></i>
            </button>
        </td>
    </tr>
</template>

<!-- SKU Row Template -->
<template id="sku-row-template">
    <tr class="hover:bg-gray-50/50 transition-colors border-0">
        <td class="px-6 py-3 border-0">
            <select class="w-full h-11 border border-gray-100 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 font-semibold text-gray-700 select-sku font-sans px-3">
                <option value="">Select SKU</option>
                @foreach($productItems as $item)
                    <option value="{{ $item->id }}" data-category-id="{{ $item->pm_product_category_id }}" data-category-name="{{ $item->category->category_name ?? 'Unknown' }}">
                        {{ $item->product_name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td class="px-6 py-3 border-0">
            <div class="relative max-w-[180px] mx-auto">
                <input type="number" class="w-full h-11 px-4 border border-gray-100 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 font-bold text-gray-700 target-qty font-sans" placeholder="0">
            </div>
        </td>
        <td class="px-6 py-3 border-0">
            <div class="flex items-center justify-center gap-2 max-w-[100px] mx-auto font-sans">
                <input type="number" class="w-16 h-11 border border-gray-100 rounded-xl bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 font-bold text-indigo-600 text-center target-percentage font-sans" placeholder="0">
                <span class="text-gray-400 font-bold font-sans">%</span>
            </div>
        </td>
        <td class="px-6 py-3 text-right border-0">
            <button class="w-9 h-9 flex items-center justify-center rounded-full text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all remove-row border-0 bg-transparent font-sans">
                <i class="bi bi-trash3-fill"></i>
            </button>
        </td>
    </tr>
</template>

<!-- Quick Add Agent Modal -->
<div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300 hidden" id="quickAgentModal">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden transform transition-all scale-95 duration-300" id="modal-container">
        <div class="px-8 pt-8 flex justify-between items-center font-sans">
            <h5 class="text-2xl font-black text-gray-900 tracking-tight font-sans">Quick Recruit</h5>
            <button type="button" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 border-0" onclick="closeQuickAgentModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="p-8 font-sans">
            <p class="text-gray-500 text-sm mb-8 leading-relaxed font-medium text-center font-sans">Establish a new agent profile instantly.</p>
            <form id="quick-agent-form" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 font-sans">Display Name *</label>
                    <input type="text" id="quick-agent-name" class="w-full h-14 px-5 rounded-2xl border-gray-100 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-gray-700 font-sans" placeholder="e.g. Namal Perera" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 font-sans">Primary Phone</label>
                    <input type="tel" id="quick-agent-phone" class="w-full h-14 px-5 rounded-2xl border-gray-100 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-gray-700 font-sans" placeholder="+94 7X XXX XXXX">
                </div>
                <div class="pt-4">
                    <button type="button" class="w-full h-14 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 tracking-wide transition-all border-0 font-sans" onclick="submitQuickAgent()">
                        Onboard Agent
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Logic Moved Inside Content Section -->
<script>
    // Number Formatting Helpers
    function formatNumberWithCommas(value) {
        if (value === undefined || value === null || value === '') return '';
        let valStr = value.toString().replace(/[^\d.]/g, '');
        const parts = valStr.split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join('.');
    }

    function stripCommas(value) {
        if (value === undefined || value === null || value === '') return '';
        return value.toString().replace(/,/g, '');
    }

    function applyFormattingToInput(input) {
        let cursorPosition = input.selectionStart;
        let oldLength = input.value.length;
        input.value = formatNumberWithCommas(input.value);
        let newLength = input.value.length;
        input.setSelectionRange(cursorPosition + (newLength - oldLength), cursorPosition + (newLength - oldLength));
    }

    document.addEventListener('DOMContentLoaded', function() {
        const saveBtn = document.getElementById('save-targets');

        // Initialize Select2
        initSelect2();

        if (saveBtn) {
            saveBtn.addEventListener('click', saveTargets);
        }

        // Filter listeners
        $('#filter-agent, #filter-year, #filter-month').on('change', applyFilters);

        // Delegation for remove row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('tr');
                row.style.opacity = '0';
                row.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    row.remove();
                    updateCalculatedWeights();
                }, 300);
            }
        });

        // Track changes for real-time validation
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('target-percentage') || 
                e.target.classList.contains('target-amount') || 
                e.target.classList.contains('target-qty') || 
                e.target.id === 'monthly-sales-target') {
                
                if (e.target.classList.contains('amount-input') || e.target.id === 'monthly-sales-target') {
                    applyFormattingToInput(e.target);
                }
                
                updateCalculatedWeights();
            }
        });
    });

    function initSelect2() {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2').each(function() {
                // Destroy if already initialized to avoid duplication
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                
                const isFilter = this.id.includes('filter');
                $(this).select2({
                    placeholder: isFilter ? "All Agents" : "Select Agent...",
                    allowClear: true,
                    width: '100%'
                });
            });
        }
    }

    // Workflow Toggling - Explicitly Global
    window.showCreateView = function() {
        resetEditorForm();
        document.getElementById('editor-title').innerText = 'Create New Targets';
        document.getElementById('target-main-view').classList.add('hidden');
        document.getElementById('target-editor-view').classList.remove('hidden');
        document.getElementById('target-detail-editor').style.display = 'none';
        initSelect2();
    }

    window.editTarget = function(agentId, year, month) {
        window.showCreateView();
        document.getElementById('editor-title').innerText = 'Edit Performance Targets';
        
        if ($('.select2').length) {
            $('#agent-selector').val(agentId).trigger('change');
        } else {
            document.getElementById('agent-selector').value = agentId;
        }
        document.getElementById('year-selector').value = year;
        document.getElementById('month-selector').value = month;

        loadTargetsForSelection();
    }

    window.hideEditorView = function() {
        document.getElementById('target-editor-view').classList.add('hidden');
        document.getElementById('target-main-view').classList.remove('hidden');
    }

    window.applyFilters = function() {
        const agent = document.getElementById('filter-agent').value;
        const year = document.getElementById('filter-year').value;
        const month = document.getElementById('filter-month').value;

        document.querySelectorAll('.target-row').forEach(row => {
            const rowAgent = row.dataset.agent;
            const rowYear = row.dataset.year;
            const rowMonth = row.dataset.month;

            const agentMatch = !agent || agent == rowAgent;
            const yearMatch = !year || year == rowYear;
            const monthMatch = !month || month == rowMonth;

            if (agentMatch && yearMatch && monthMatch) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    window.resetFilters = function() {
        $('#filter-agent').val('').trigger('change');
        document.getElementById('filter-year').value = '';
        document.getElementById('filter-month').value = '';
        applyFilters();
    }

    // Data Management
    async function loadTargetsForSelection() {
        const agentId = document.getElementById('agent-selector').value;
        const year = document.getElementById('year-selector').value;
        const month = document.getElementById('month-selector').value;

        if (!agentId) return;

        const verifyBtn = document.getElementById('verify-selection');
        const originalHtml = verifyBtn.innerHTML;
        verifyBtn.innerHTML = '<svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        verifyBtn.disabled = true;

        try {
            const response = await fetch(`{{ route('agents.monthlyTargets.load') }}?agent_id=${agentId}&year=${year}&month=${month}`);
            const result = await response.json();

            if (result.success) {
                document.getElementById('target-detail-editor').style.display = 'block';
                fillFormData(result.data);
                updateCalculatedWeights();
                setTimeout(() => document.getElementById('target-detail-editor').scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
            }
        } catch (error) { toastr.error('Fetch error.'); }
        finally { verifyBtn.innerHTML = originalHtml; verifyBtn.disabled = false; }
    }

    function fillFormData(data) {
        document.querySelector('#category-targets-table tbody').innerHTML = '';
        document.querySelector('#sku-targets-table tbody').innerHTML = '';
        document.getElementById('monthly-sales-target').value = '';

        if (!data) {
            document.getElementById('base-salary').value = '';
            document.getElementById('commission-rate').value = '';
            document.getElementById('invoicing-commission-rate').value = '15.00';
            document.getElementById('target-commission-rate').value = '5.00';
            document.getElementById('achievement-threshold').value = '80.00';
            document.getElementById('reduced-target-commission-rate').value = '5.00';
            document.getElementById('target-status').value = '1';
            addRow('category-targets-table');
            addRow('sku-targets-table');
            return;
        }

        document.getElementById('monthly-sales-target').value = formatNumberWithCommas(data.monthly_sales_target);
        document.getElementById('base-salary').value = data.base_salary;
        document.getElementById('commission-rate').value = data.commission_rate;
        document.getElementById('invoicing-commission-rate').value = data.invoicing_commission_rate;
        document.getElementById('target-commission-rate').value = data.target_commission_rate;
        document.getElementById('achievement-threshold').value = data.achievement_threshold;
        document.getElementById('reduced-target-commission-rate').value = data.reduced_target_commission_rate;
        document.getElementById('target-status').value = data.status || '1';

        if (data.category_targets?.length) {
            data.category_targets.forEach(ct => {
                const row = addRow('category-targets-table');
                row.querySelector('.select-category').value = ct.pm_product_category_id;
                row.querySelector('.target-amount').value = formatNumberWithCommas(ct.target_amount);
                row.querySelector('.target-percentage').value = ct.target_percentage;
            });
        } else { addRow('category-targets-table'); }

        if (data.item_targets?.length) {
            data.item_targets.forEach(it => {
                const row = addRow('sku-targets-table');
                row.querySelector('.select-sku').value = it.pm_product_item_id;
                row.querySelector('.target-qty').value = it.target_qty;
                row.querySelector('.target-percentage').value = it.target_percentage;
            });
        } else { addRow('sku-targets-table'); }
        
        updateCalculatedWeights();
    }

    function addRow(tableId) {
        const tbody = document.querySelector(`#${tableId} tbody`);
        const templateId = tableId === 'category-targets-table' ? 'category-row-template' : 'sku-row-template';
        const template = document.getElementById(templateId);
        const clone = template.content.cloneNode(true);
        const row = clone.querySelector('tr');
        tbody.appendChild(clone);
        return row;
    }

    function updateCalculatedWeights() {
        let totalWeight = 0;
        let totalAmount = 0;
        document.querySelectorAll('#category-targets-table .target-percentage').forEach(input => totalWeight += parseFloat(input.value) || 0);
        document.querySelectorAll('#category-targets-table .target-amount').forEach(input => totalAmount += parseFloat(stripCommas(input.value)) || 0);
        
        const badge = document.getElementById('total-weight-percentage');
        const mainTargetInput = document.getElementById('monthly-sales-target');
        const mainTarget = parseFloat(stripCommas(mainTargetInput.value)) || 0;
        
        let diffText = '';
        if (mainTarget > 0) {
            const diff = totalAmount - mainTarget;
            if (Math.abs(diff) > 0.01) {
                diffText = ` | diff: LKR ${diff.toLocaleString()}`;
                badge.classList.add('text-red-500');
            } else {
                badge.classList.remove('text-red-500');
                badge.classList.add('text-green-600');
            }
        }
        
        badge.innerText = `Weight: ${totalWeight}% | Total: LKR ${totalAmount.toLocaleString()}${diffText}`;
    }

    async function saveTargets() {
        const categoryTargets = [];
        document.querySelectorAll('#category-targets-table tbody tr').forEach(row => {
            if (row.querySelector('.select-category').value) {
                categoryTargets.push({
                    pm_product_category_id: row.querySelector('.select-category').value,
                    target_amount: stripCommas(row.querySelector('.target-amount').value),
                    target_percentage: row.querySelector('.target-percentage').value,
                });
            }
        });

        const itemTargets = [];
        document.querySelectorAll('#sku-targets-table tbody tr').forEach(row => {
            const skuSelect = row.querySelector('.select-sku');
            if (skuSelect.value) {
                const selectedOption = skuSelect.options[skuSelect.selectedIndex];
                itemTargets.push({
                    pm_product_item_id: skuSelect.value,
                    target_qty: row.querySelector('.target-qty').value,
                    target_percentage: row.querySelector('.target-percentage').value,
                    category_id: selectedOption.dataset.categoryId,
                    category_name: selectedOption.dataset.categoryName
                });
            }
        });

        // Validation: Category sum must match Monthly Sales Target
        const monthlySalesTarget = parseFloat(stripCommas(document.getElementById('monthly-sales-target').value)) || 0;
        let categoryTotal = 0;
        categoryTargets.forEach(t => categoryTotal += parseFloat(t.target_amount) || 0);

        if (Math.abs(monthlySalesTarget - categoryTotal) > 0.01) {
            Swal.fire({
                title: 'Data Inconsistency',
                text: `Monthly Sales Target (LKR ${monthlySalesTarget.toLocaleString()}) must exactly match the sum of Category Goals (LKR ${categoryTotal.toLocaleString()}).`,
                icon: 'warning',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        // Validation: SKU sum for each category must not exceed Category Target
        const catAmountMap = {};
        categoryTargets.forEach(ct => catAmountMap[ct.pm_product_category_id] = parseFloat(ct.target_amount) || 0);

        const skuSumByCat = {};
        itemTargets.forEach(it => {
            skuSumByCat[it.category_id] = (skuSumByCat[it.category_id] || 0) + (parseFloat(it.target_amount) || 0);
        });

        for (const catId in skuSumByCat) {
            const catTarget = catAmountMap[catId] || 0;
            if (skuSumByCat[catId] > catTarget + 0.01) {
                const catName = itemTargets.find(it => it.category_id == catId).category_name;
                Swal.fire({
                    title: 'Category Limit Exceeded',
                    text: `Sum of SKU targets for category "${catName}" (LKR ${skuSumByCat[catId].toLocaleString()}) exceeds its Category Goal (LKR ${catTarget.toLocaleString()}).`,
                    icon: 'warning',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }
        }

        const saveBtn = document.getElementById('save-targets');
        const originalHtml = saveBtn.innerHTML;
        saveBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 text-white inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
        saveBtn.disabled = true;

        try {
            const response = await fetch(`{{ route('agents.monthlyTargets.save') }}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    agent_id: document.getElementById('agent-selector').value,
                    year: document.getElementById('year-selector').value,
                    month: document.getElementById('month-selector').value,
                    monthly_sales_target: stripCommas(document.getElementById('monthly-sales-target').value),
                    base_salary: document.getElementById('base-salary').value,
                    commission_rate: document.getElementById('commission-rate').value,
                    invoicing_commission_rate: document.getElementById('invoicing-commission-rate').value,
                    target_commission_rate: document.getElementById('target-commission-rate').value,
                    achievement_threshold: document.getElementById('achievement-threshold').value,
                    reduced_target_commission_rate: document.getElementById('reduced-target-commission-rate').value,
                    status: document.getElementById('target-status').value,
                    category_targets: categoryTargets,
                    item_targets: itemTargets
                })
            });
            const result = await response.json();
            if (result.success) {
                Swal.fire({ title: 'Success!', text: result.message, icon: 'success', timer: 2000, showConfirmButton: false })
                    .then(() => window.location.reload());
            } else { Swal.fire('Error', result.message, 'error'); }
        } catch (e) { toastr.error('Sync failure.'); }
        finally { saveBtn.innerHTML = originalHtml; saveBtn.disabled = false; }
    }

    function resetEditorForm() {
        $('#agent-selector').val('').trigger('change');
        document.getElementById('monthly-sales-target').value = '';
        document.getElementById('base-salary').value = '';
        document.getElementById('commission-rate').value = '';
        document.getElementById('invoicing-commission-rate').value = '15.00';
        document.getElementById('target-commission-rate').value = '5.00';
        document.getElementById('achievement-threshold').value = '80.00';
        document.getElementById('reduced-target-commission-rate').value = '4.00';
        document.querySelector('#category-targets-table tbody').innerHTML = '';
        document.querySelector('#sku-targets-table tbody').innerHTML = '';
        document.getElementById('target-status').value = '1';
    }

    window.openQuickAgentModal = function() {
        document.getElementById('quickAgentModal').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('quickAgentModal').style.opacity = '1';
            document.getElementById('modal-container').classList.remove('scale-95');
            document.getElementById('modal-container').classList.add('scale-100');
        }, 10);
    }

    window.closeQuickAgentModal = function() {
        document.getElementById('quickAgentModal').style.opacity = '0';
        document.getElementById('modal-container').classList.add('scale-95');
        document.getElementById('modal-container').classList.remove('scale-100');
        setTimeout(() => document.getElementById('quickAgentModal').classList.add('hidden'), 300);
    }

    window.submitQuickAgent = async function() {
        const name = document.getElementById('quick-agent-name').value;
        const phone = document.getElementById('quick-agent-phone').value;
        if (!name) return;
        try {
            const response = await fetch(`{{ route('agents.quickSave') }}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ agent_name: name, contact_phone: phone })
            });
            const result = await response.json();
            if (result.success) {
                const newOption = new Option(`${result.agent.agent_name} (${result.agent.agent_code})`, result.agent.id, true, true);
                $('#agent-selector').append(newOption).trigger('change');
                closeQuickAgentModal();
            }
        } catch (e) { toastr.error('Sync failure.'); }
    }

    window.updatePaymentStatus = async function(targetId, currentStatus) {
        const { value: newStatus } = await Swal.fire({
            title: 'Update Payment Status',
            input: 'select',
            inputOptions: {
                '0': 'Pending',
                '1': 'Processed',
                '2': 'Paid'
            },
            inputValue: currentStatus,
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    resolve();
                });
            }
        });

        if (newStatus !== undefined) {
             try {
                const response = await fetch(`{{ route('agents.monthlyTargets.updatePaymentStatus') }}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        id: targetId,
                        payment_status: newStatus
                    })
                });
                const result = await response.json();
                if (result.success) {
                    toastr.success('Payment status updated.');
                    setTimeout(() => location.reload(), 500);
                } else {
                    toastr.error(result.message || 'Update failed.');
                }
            } catch (error) {
                toastr.error('Network error.');
            }
        }
    }
</script>

<style>
    .font-sans { font-family: 'Inter', sans-serif !important; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fadeIn { animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f8fafc; }
    ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .select2-container--default .select2-selection--single {
        border-radius: 0.75rem !important;
        border: 1px solid #f1f5f9 !important;
        height: 3rem !important;
        background-color: #f8fafc !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding-left: 1.25rem !important;
        font-weight: 600 !important;
        color: #475569 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 3rem !important; }
</style>
@endsection
