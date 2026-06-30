@extends('layouts.app')

@section('title', 'Agent Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-blue-900 to-indigo-800 text-white rounded-2xl p-8 mb-8 shadow-lg relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">Agent Portal</h1>
                @if($agent)
                    <p class="mt-2 text-blue-100 text-lg">
                        Welcome back, <span class="font-bold text-white">{{ $agent->agent_name }}</span>!
                    </p>
                    <div class="mt-4 flex items-center gap-2 flex-wrap">
                        <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold uppercase tracking-wider text-blue-200">
                            {{ $agent->agent_code }}
                        </span>
                        <span class="text-sm font-medium text-blue-200">
                            {{ ucfirst($agent->agent_type) }} Agent
                        </span>
                    </div>
                @else
                    <p class="mt-2 text-blue-100 text-lg">Welcome back to your dashboard!</p>
                @endif
            </div>
            <a href="{{ route('agent-panel.dashboard') }}" class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-lg transition-colors border border-white/10 no-underline">
                Refresh Dashboard
            </a>
        </div>
        <div class="absolute right-0 bottom-0 opacity-10 translate-x-1/4 translate-y-1/4 pointer-events-none">
            <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
        </div>
    </div>

    <!-- Stats & Target Progress -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Key Metrics -->
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Today's Sales -->
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Today's Sales</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">Rs {{ number_format($todaySales, 2) }}</h3>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Customers</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($totalCustomers) }}</h3>
                    </div>
                </div>

                <!-- Commission -->
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </span>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Est. Monthly Commission</p>
                        <h3 class="text-2xl font-black text-gray-900 mt-1">Rs {{ number_format($commissionAmount, 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Target Progress Card -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Monthly Sales Target</h3>
                    <span class="text-sm font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                        {{ $progressPercentage }}% Completed
                    </span>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Achieved: <span class="font-bold text-gray-900">Rs {{ number_format($achievedSales, 2) }}</span></span>
                        <span class="text-gray-500">Target: <span class="font-bold text-gray-900">Rs {{ number_format($targetAmount, 2) }}</span></span>
                    </div>
                    <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-blue-500 transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credit Info Column -->
        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-6">Credit & Limit Info</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Credit Limit</span>
                        <span class="text-sm font-bold text-gray-950">Rs {{ number_format($agent->credit_limit ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Outstanding Balance</span>
                        <span class="text-sm font-bold text-red-600">Rs {{ number_format($agent->outstanding_balance ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">Available Balance</span>
                        <span class="text-sm font-bold text-emerald-600">Rs {{ number_format(($agent->credit_limit ?? 0) - ($agent->outstanding_balance ?? 0), 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-500">Credit Period</span>
                        <span class="text-sm font-bold text-gray-950">{{ $agent->credit_period_days ?? 0 }} Days</span>
                    </div>
                </div>
            </div>
            <div class="pt-6">
                <a href="{{ route('agent-panel.payments') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-colors no-underline">
                    View Payment Transactions
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Panel & Recent Visits -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Quick Links Grid -->
        <div class="lg:col-span-1 bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('agent-panel.customers') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-indigo-500 bg-gray-50/50 hover:bg-white transition-all text-center no-underline">
                    <span class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </span>
                    <span class="text-xs font-bold text-gray-700">Customers</span>
                </a>
                <a href="{{ route('agent-panel.orders') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-blue-500 bg-gray-50/50 hover:bg-white transition-all text-center no-underline">
                    <span class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </span>
                    <span class="text-xs font-bold text-gray-700">Orders</span>
                </a>
                <a href="{{ route('agent-panel.daily-loads') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-amber-500 bg-gray-50/50 hover:bg-white transition-all text-center no-underline">
                    <span class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </span>
                    <span class="text-xs font-bold text-gray-700">Daily Loads</span>
                </a>
                <a href="{{ route('agent-panel.bakery-returns') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-100 hover:border-rose-500 bg-gray-50/50 hover:bg-white transition-all text-center no-underline">
                    <span class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-6m0 0l-3 3m3-3l3 3m-9 0v6m0-6L7 9m3 3l-3 3M3 5h18M3 19h18"/></svg>
                    </span>
                    <span class="text-xs font-bold text-gray-700">Returns</span>
                </a>
            </div>
        </div>

        <!-- Recent Visits -->
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Recent Visits / Sales</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Customer</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Time</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($recentVisits as $visit)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ $visit['customer_name'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-950">
                                    Rs {{ number_format($visit['amount'], 2) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ $visit['time'] }} ({{ $visit['date'] }})
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700">
                                        {{ $visit['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                    No recent visits recorded today.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
