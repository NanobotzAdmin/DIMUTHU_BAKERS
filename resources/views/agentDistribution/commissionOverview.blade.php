@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="commission-overview-app">
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Commission Overview</h1>
                <p class="text-gray-600">Performance and earnings summary for the last 30 days</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                    <i class="bi bi-printer mr-2"></i> Print Report
                </button>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                    <i class="bi bi-currency-dollar text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Sales</p>
                    <p class="text-2xl font-bold text-gray-900">Rs. {{ number_format($stats->total_sales ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                    <i class="bi bi-award text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Est. Commission</p>
                    <p class="text-2xl font-bold text-gray-900">Rs. {{ number_format(($stats->total_sales ?? 0) * 0.15, 2) }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                    <i class="bi bi-file-earmark-text text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Invoices</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats->total_invoices ?? 0) }}</p>
                </div>
            </div>
        </div>

        <!-- Agent Breakdown Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                <h3 class="text-gray-900 font-semibold text-lg">Agent Performance Breakdown</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-gray-700 bg-gray-50 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Agent Name</th>
                            <th class="px-6 py-4 font-semibold">Agent Code</th>
                            <th class="px-6 py-4 font-semibold text-right">Total Sales</th>
                            <th class="px-6 py-4 font-semibold text-right">Est. Commission (15%)</th>
                            <th class="px-6 py-4 font-semibold text-center">Trend</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($agentBreakdown as $row)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $row->agent_name }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $row->agent_code }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">Rs. {{ number_format($row->sales, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-green-600 font-bold">Rs. {{ number_format($row->commission, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center">
                                        <div class="w-16 h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="bg-amber-400 h-full" style="width: {{ rand(40, 95) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="bi bi-inbox text-4xl block mb-2 opacity-20"></i>
                                    No performance data available for the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 text-amber-800 text-sm flex items-start gap-3">
            <i class="bi bi-info-circle-fill text-amber-500 mt-0.5"></i>
            <div>
                <p class="font-semibold mb-1">Calculation Logic</p>
                <p>Commission estimates are based on the standard 15% invoicing rate. Actual payouts may vary based on tiered target achievements and management approvals.</p>
            </div>
        </div>
    </div>
@endsection
