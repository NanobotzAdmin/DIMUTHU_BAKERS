@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-[1600px] mx-auto space-y-6">
        <!-- Header -->
        <div>
            <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-900">
                <i class="bi bi-currency-dollar text-amber-500 text-3xl"></i>
                Financial Management
            </h1>
            <p class="text-gray-600 mt-1">
                Complete accounting and financial management system with double-entry bookkeeping
            </p>
        </div>

        <!-- Financial Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Assets -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Total Assets</div>
                <div class="text-3xl font-bold text-blue-600">
                    ${{ number_format($stats['totalAssets'], 2) }}
                </div>
                <div class="text-xs text-gray-400 mt-1">Current value</div>
            </div>

            <!-- Liabilities -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Total Liabilities</div>
                <div class="text-3xl font-bold text-red-600">
                    ${{ number_format($stats['totalLiabilities'], 2) }}
                </div>
                <div class="text-xs text-gray-400 mt-1">Amount owed</div>
            </div>

            <!-- Equity -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Total Equity</div>
                <div class="text-3xl font-bold text-purple-600">
                    ${{ number_format($stats['totalEquity'], 2) }}
                </div>
                <div class="text-xs text-gray-400 mt-1">Owner's equity</div>
            </div>

            <!-- Net Profit -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="text-sm text-gray-500 mb-1">Net Profit (YTD)</div>
                <div class="text-3xl font-bold {{ $stats['netProfit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    ${{ number_format($stats['netProfit'], 2) }}
                </div>
                <div class="text-xs text-gray-400 mt-1">Year to date</div>
            </div>
        </div>

        <!-- System Status -->
        <div
            class="rounded-xl border p-6 {{ $stats['isBalanced'] ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 rounded-full flex items-center justify-center {{ $stats['isBalanced'] ? 'bg-green-100' : 'bg-red-100' }}">
                    <i class="bi bi-scales text-2xl {{ $stats['isBalanced'] ? 'text-green-600' : 'text-red-600' }}"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold {{ $stats['isBalanced'] ? 'text-green-900' : 'text-red-900' }}">
                        {{ $stats['isBalanced'] ? 'Books are Balanced ✓' : 'Books are Out of Balance' }}
                    </h3>
                    <p class="text-sm {{ $stats['isBalanced'] ? 'text-green-700' : 'text-red-700' }}">
                        {{ $stats['isBalanced']
        ? "All {$stats['postedEntries']} posted journal entries are balanced. Your accounting records are accurate."
        : 'Your trial balance is out of balance. Please review your journal entries.' 
                            }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Finance Modules -->
        <div>
            <h2 class="text-xl font-semibold mb-4 text-gray-900">Finance Modules</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Chart of Accounts -->
                <a href="{{ route('chartOfAccounts.index') }}"
                    class="block bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer group">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-blue-500 flex items-center justify-center text-white text-xl">
                                <i class="bi bi-book"></i>
                            </div>
                            <i
                                class="bi bi-arrow-right text-gray-400 group-hover:text-amber-500 transition-colors text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Chart of Accounts</h3>
                        <p class="text-sm text-gray-500 mb-4">Manage your general ledger account structure and balances</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-gray-600">Total Accounts:</span> <span
                                    class="font-semibold">{{ $stats['totalAccounts'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Active:</span> <span
                                    class="font-semibold">{{ $stats['activeAccounts'] }}</span></div>
                        </div>
                    </div>
                </a>

                <!-- Journal Entries -->
                <a href="{{ route('journalEntries.index') }}"
                    class="block bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer group">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-green-500 flex items-center justify-center text-white text-xl">
                                <i class="bi bi-file-text"></i>
                            </div>
                            <i
                                class="bi bi-arrow-right text-gray-400 group-hover:text-amber-500 transition-colors text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Journal Entries</h3>
                        <p class="text-sm text-gray-500 mb-4">Create and manage general ledger journal entries</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-gray-600">Total Entries:</span> <span
                                    class="font-semibold">{{ $stats['totalEntries'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Posted:</span> <span
                                    class="font-semibold">{{ $stats['postedEntries'] }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Draft:</span> <span
                                    class="font-semibold">{{ $stats['draftEntries'] }}</span></div>
                        </div>
                    </div>
                </a>

                <!-- Trial Balance & Reports -->
                <a href="{{ route('trialbalanceAndReports.index') }}"
                    class="block bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer group">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-purple-500 flex items-center justify-center text-white text-xl">
                                <i class="bi bi-scales"></i>
                            </div>
                            <i
                                class="bi bi-arrow-right text-gray-400 group-hover:text-amber-500 transition-colors text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Trial Balance & Reports</h3>
                        <p class="text-sm text-gray-500 mb-4">View trial balance, income statement, and balance sheet</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-gray-600">Balance Status:</span> <span
                                    class="font-semibold">{{ $stats['isBalanced'] ? 'Balanced ✓' : 'Out of Balance' }}</span>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Expense Management -->
                <a href="{{ route('expenseManagement.index') }}"
                    class="block bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer group">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-orange-500 flex items-center justify-center text-white text-xl">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <i
                                class="bi bi-arrow-right text-gray-400 group-hover:text-amber-500 transition-colors text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Expense Management</h3>
                        <p class="text-sm text-gray-500 mb-4">Track and categorize business expenses</p>
                    </div>
                </a>

                <!-- Financial Reports -->
                <a href="{{ route('financialReports.index') }}"
                    class="block bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer group">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-indigo-500 flex items-center justify-center text-white text-xl">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <i
                                class="bi bi-arrow-right text-gray-400 group-hover:text-amber-500 transition-colors text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Financial Reports</h3>
                        <p class="text-sm text-gray-500 mb-4">Generate comprehensive financial reports and analysis</p>
                    </div>
                </a>

                <!-- Inventory GL Mapping -->
                <a href="{{ route('inventoryGlMapping.index') }}"
                    class="block bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer group">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="w-12 h-12 rounded-lg bg-amber-500 flex items-center justify-center text-white text-xl">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <i
                                class="bi bi-arrow-right text-gray-400 group-hover:text-amber-500 transition-colors text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Inventory GL Mapping</h3>
                        <p class="text-sm text-gray-500 mb-4">Configure GL accounts for inventory and procurement</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Key Features -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Financial Management Features</h3>
            <p class="text-sm text-gray-500 mb-6">Complete double-entry accounting system</p>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-8">
                @php
                    $features = [
                        'Double-entry accounting',
                        'Chart of accounts with 60+ accounts',
                        'Manual and automated journals',
                        'General ledger tracking',
                        'Trial balance validation',
                        'Income statement (P&L)',
                        'Balance sheet',
                        'Multi-location support',
                        'Cost center tracking',
                        'Opening balances',
                        'Period closing',
                        'Audit trail'
                    ];
                @endphp
                @foreach($features as $feature)
                    <div class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-green-500 mt-0.5"></i>
                        <span class="text-sm text-gray-700">{{ $feature }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('journalEntries.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium text-sm">
                    <i class="bi bi-file-text mr-2"></i> Create Journal Entry
                </a>
                <a href="{{ route('trialbalanceAndReports.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                    <i class="bi bi-scales mr-2"></i> View Trial Balance
                </a>
                <a href="{{ route('chartOfAccounts.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                    <i class="bi bi-book mr-2"></i> Manage Accounts
                </a>
                <a href="{{ route('financialReports.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-sm">
                    <i class="bi bi-graph-up-arrow mr-2"></i> Financial Reports
                </a>
            </div>
        </div>
    </div>
@endsection