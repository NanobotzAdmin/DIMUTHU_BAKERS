@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="analytics-app">
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Agent Performance Analytics</h1>
                <p class="text-gray-600">Comprehensive performance metrics and insights</p>
            </div>
            <div>
                <select id="date-range-select" onchange="updateDashboard()"
                    class="w-full md:w-48 px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    <option value="week">Last 7 Days</option>
                    <option value="month" selected>Last 30 Days</option>
                    <option value="quarter">Last 90 Days</option>
                </select>
            </div>
        </div>

        <!-- Overall Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Sales -->
            <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-currency-dollar text-purple-600 text-xl"></i>
                    <span class="text-purple-700 text-xs font-semibold uppercase tracking-wider">Total Sales</span>
                </div>
                <p class="text-2xl font-bold text-purple-900 mb-1" id="metric-total-sales">Rs. 0</p>
                <p class="text-purple-700 text-xs" id="metric-avg-daily">Avg Daily: Rs. 0</p>
            </div>

            <!-- Transactions -->
            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-bar-chart-fill text-blue-600 text-xl"></i>
                    <span class="text-blue-700 text-xs font-semibold uppercase tracking-wider">Transactions</span>
                </div>
                <p class="text-2xl font-bold text-blue-900 mb-1" id="metric-transactions">0</p>
                <p class="text-blue-700 text-xs" id="metric-avg-txn">Avg Value: Rs. 0</p>
            </div>

            <!-- Cash Accuracy -->
            <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-bullseye text-green-600 text-xl"></i>
                    <span class="text-green-700 text-xs font-semibold uppercase tracking-wider">Cash Accuracy</span>
                </div>
                <p class="text-2xl font-bold text-green-900 mb-1" id="metric-accuracy">0%</p>
                <p class="text-green-700 text-xs" id="metric-variance-rate">Variance Rate: 0%</p>
            </div>

            <!-- Total Commission -->
            <div class="bg-orange-50 p-4 rounded-xl border border-orange-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-award-fill text-orange-600 text-xl"></i>
                    <span class="text-orange-700 text-xs font-semibold uppercase tracking-wider">Total Commission</span>
                </div>
                <p class="text-2xl font-bold text-orange-900" id="metric-commission">Rs. 0</p>
            </div>
        </div>

        <!-- Payment Method Distribution -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
            <h3 class="text-gray-900 font-semibold mb-4">Payment Method Distribution</h3>
            <div class="space-y-4">
                <!-- Cash -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-700 text-sm">Cash</span>
                        <span class="text-green-600 font-medium" id="pm-cash-pct">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div id="pm-cash-bar" class="bg-green-500 h-3 rounded-full transition-all duration-500"
                            style="width: 0%"></div>
                    </div>
                    <p class="text-gray-600 text-xs mt-1" id="pm-cash-amt">Rs. 0</p>
                </div>

                <!-- Credit -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-700 text-sm">Credit</span>
                        <span class="text-orange-600 font-medium" id="pm-credit-pct">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div id="pm-credit-bar" class="bg-orange-500 h-3 rounded-full transition-all duration-500"
                            style="width: 0%"></div>
                    </div>
                    <p class="text-gray-600 text-xs mt-1" id="pm-credit-amt">Rs. 0</p>
                </div>

                <!-- Cheque -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-gray-700 text-sm">Cheque</span>
                        <span class="text-blue-600 font-medium" id="pm-cheque-pct">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div id="pm-cheque-bar" class="bg-blue-500 h-3 rounded-full transition-all duration-500"
                            style="width: 0%"></div>
                    </div>
                    <p class="text-gray-600 text-xs mt-1" id="pm-cheque-amt">Rs. 0</p>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <i class="bi bi-trophy-fill text-amber-500 text-xl"></i>
                <h3 class="text-gray-900 font-semibold">Top 5 Performers</h3>
            </div>
            <div class="space-y-3" id="top-performers-list">
                <!-- Injected JS -->
            </div>
        </div>

        <!-- All Agents Table -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
            <h3 class="text-gray-900 font-semibold mb-4">All Agents Performance Summary</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-gray-700 bg-gray-50 border-b">
                        <tr>
                            <th class="px-3 py-3">Agent</th>
                            <th class="px-3 py-3 text-right">Sales</th>
                            <th class="px-3 py-3 text-right">Transactions</th>
                            <th class="px-3 py-3 text-right">Avg/Trans</th>
                            <th class="px-3 py-3 text-right">Days</th>
                            <th class="px-3 py-3 text-right">Avg/Day</th>
                            <th class="px-3 py-3 text-right">Accuracy</th>
                            <th class="px-3 py-3 text-right">Commission</th>
                        </tr>
                    </thead>
                    <tbody id="agents-table-body">
                        <!-- Injected JS -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Performers Alert -->
        <div id="alerts-container" class="hidden bg-orange-50 border border-orange-200 rounded-xl p-6">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-triangle-fill text-orange-600 text-xl mt-1"></i>
                <div class="flex-1">
                    <h3 class="text-orange-900 font-semibold mb-2">Performance Alerts</h3>
                    <p class="text-orange-800 text-sm mb-3">The following agents need attention based on cash accuracy:</p>
                    <div class="space-y-2" id="alerts-list">
                        <!-- Injected JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Server Data
        const serverAgents = @json($agents ?? []);
        const serverSettlements = @json($settlements ?? []);
        const serverSales = @json($sales ?? []);

        const state = {
            agents: serverAgents,
            settlements: serverSettlements,
            sales: serverSales,
            dateRange: 'month'
        };

        document.addEventListener('DOMContentLoaded', () => {
            updateDashboard();
        });

        function updateDashboard() {
            state.dateRange = document.getElementById('date-range-select').value;
            const { startDate, endDate, daysInPeriod } = getDateFilter(state.dateRange);

            // Filter Data
            const filteredSettlements = state.settlements.filter(s =>
                s.settlementDate >= startDate && s.settlementDate <= endDate
            );
            const filteredSales = state.sales.filter(s =>
                s.saleDate >= startDate && s.saleDate <= endDate
            );

            // Calculate Overall Metrics
            const totalSales = filteredSettlements.reduce((sum, s) => sum + Number(s.totalSales), 0);
            const totalComm = filteredSettlements.reduce((sum, s) => sum + Number(s.commissionEarned), 0);

            const totalTxns = filteredSales.length;
            const avgTxnValue = totalTxns > 0 ? totalSales / totalTxns : 0; // Using Total Sales / Txn Count approximation for dashboard consistency with logic
            // Alternatively, summarize actual sales record amounts if available
            const actualSalesSum = filteredSales.reduce((sum, s) => sum + Number(s.totalAmount), 0);
            // Let's use actualSalesSum for Avg Value to be precise if sales records exist
            const calcAvgTxnValue = totalTxns > 0 ? actualSalesSum / totalTxns : 0;

            const varianceCount = filteredSettlements.filter(s => Math.abs(s.cashVariance) > 0).length;
            const totalSettlements = filteredSettlements.length;
            const varianceRate = totalSettlements > 0 ? (varianceCount / totalSettlements) * 100 : 0;
            const cashAccuracy = 100 - varianceRate;
            const avgDailySales = totalSales / daysInPeriod; // Adjusted for selected period length

            // UI Updates - Overview
            document.getElementById('metric-total-sales').textContent = formatCurrency(totalSales);
            document.getElementById('metric-avg-daily').textContent = `Avg Daily: ${formatCurrency(avgDailySales)}`;

            document.getElementById('metric-transactions').textContent = totalTxns.toLocaleString();
            document.getElementById('metric-avg-txn').textContent = `Avg Value: ${formatCurrency(calcAvgTxnValue)}`;

            document.getElementById('metric-accuracy').textContent = `${cashAccuracy.toFixed(1)}%`;
            document.getElementById('metric-variance-rate').textContent = `Variance Rate: ${varianceRate.toFixed(1)}%`;

            document.getElementById('metric-commission').textContent = formatCurrency(totalComm);

            // Payment Method Distribution
            const pmStats = { cash: 0, credit: 0, cheque: 0, total: 0 };
            filteredSales.forEach(s => {
                if (pmStats[s.paymentMethod] !== undefined) {
                    pmStats[s.paymentMethod] += Number(s.totalAmount);
                    pmStats.total += Number(s.totalAmount);
                }
            });

            updatePmBar('cash', pmStats.cash, pmStats.total);
            updatePmBar('credit', pmStats.credit, pmStats.total);
            updatePmBar('cheque', pmStats.cheque, pmStats.total);

            // Agent Performance Logic
            const agentPerf = state.agents.map(agent => {
                const agentStls = filteredSettlements.filter(s => s.agentId === agent.id);
                const agentSls = filteredSales.filter(s => s.agentId === agent.id);

                const aSales = agentStls.reduce((sum, s) => sum + Number(s.totalSales), 0);
                const aComm = agentStls.reduce((sum, s) => sum + Number(s.commissionEarned), 0);
                const aTxns = agentSls.length;
                const aAvgTxn = aTxns > 0 ? (agentSls.reduce((sum, s) => sum + Number(s.totalAmount), 0) / aTxns) : 0;
                const aDays = agentStls.length;
                const aAvgDaily = aDays > 0 ? aSales / aDays : 0;
                const aVarCount = agentStls.filter(s => Math.abs(s.cashVariance) > 0).length;
                const aAccuracy = aDays > 0 ? ((aDays - aVarCount) / aDays) * 100 : 100;

                return { agent, aSales, aTxns, aAvgTxn, aDays, aAvgDaily, aAccuracy, aComm };
            }).sort((a, b) => b.aSales - a.aSales);

            // Top Performers
            const topList = document.getElementById('top-performers-list');
            topList.innerHTML = agentPerf.slice(0, 5).map((p, idx) => `
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0 font-bold">
                        ${idx + 1}
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-900 font-medium">${p.agent.agentName}</p>
                        <p class="text-gray-600 text-sm">${p.agent.agentCode}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-900 font-bold">${formatCurrency(p.aSales)}</p>
                        <div class="flex items-center gap-2 justify-end mt-1">
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">${p.aAccuracy.toFixed(0)}% acc</span>
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">${p.aTxns} sales</span>
                        </div>
                    </div>
                </div>
            `).join('');

            // Full Table
            const tableBody = document.getElementById('agents-table-body');
            tableBody.innerHTML = agentPerf.map(p => {
                const accColor = p.aAccuracy >= 95 ? 'bg-green-100 text-green-800' : (p.aAccuracy >= 90 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                return `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-3 py-3 font-medium text-gray-900">
                            ${p.agent.agentName}
                            <div class="text-xs text-gray-500">${p.agent.agentCode}</div>
                        </td>
                        <td class="px-3 py-3 text-right">${formatCurrency(p.aSales)}</td>
                        <td class="px-3 py-3 text-right">${p.aTxns}</td>
                        <td class="px-3 py-3 text-right">${formatCurrency(p.aAvgTxn)}</td>
                        <td class="px-3 py-3 text-right">${p.aDays}</td>
                        <td class="px-3 py-3 text-right">${formatCurrency(p.aAvgDaily)}</td>
                        <td class="px-3 py-3 text-right">
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${accColor}">${p.aAccuracy.toFixed(0)}%</span>
                        </td>
                        <td class="px-3 py-3 text-right text-gray-900">${formatCurrency(p.aComm)}</td>
                    </tr>
                `;
            }).join('');

            // Low Performers Alert
            const lowPerformers = agentPerf.filter(p => p.aSales > 0 && p.aAccuracy < 90).slice(0, 3); // Top 3 worst active
            const alertsContainer = document.getElementById('alerts-container');
            const alertsList = document.getElementById('alerts-list');

            if (lowPerformers.length > 0) {
                alertsContainer.classList.remove('hidden');
                alertsList.innerHTML = lowPerformers.map(p => `
                    <div class="flex items-center justify-between p-2 bg-white rounded shadow-sm border border-orange-100">
                        <span class="text-gray-900 text-sm font-medium">
                            ${p.agent.agentName} (${p.agent.agentCode})
                        </span>
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-lg text-xs font-bold">
                            ${p.aAccuracy.toFixed(0)}% accuracy
                        </span>
                    </div>
                `).join('');
            } else {
                alertsContainer.classList.add('hidden');
            }
        }

        function updatePmBar(type, amount, total) {
            const pct = total > 0 ? (amount / total) * 100 : 0;
            document.getElementById(`pm-${type}-pct`).textContent = `${pct.toFixed(1)}%`;
            document.getElementById(`pm-${type}-amt`).textContent = formatCurrency(amount);
            document.getElementById(`pm-${type}-bar`).style.width = `${pct}%`;
        }

        function getDateFilter(range) {
            const today = new Date();
            // Reset time to end of day for inclusive comparison if needed, or stick to ISO string slicing
            const endStr = today.toISOString().split('T')[0];

            let start = new Date(today);
            let days = 30;

            if (range === 'week') {
                start.setDate(today.getDate() - 7);
                days = 7;
            } else if (range === 'month') {
                start.setDate(today.getDate() - 30);
                days = 30;
            } else if (range === 'quarter') {
                start.setDate(today.getDate() - 90);
                days = 90;
            }

            return {
                startDate: start.toISOString().split('T')[0],
                endDate: endStr,
                daysInPeriod: days
            };
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'LKR', minimumFractionDigits: 0 }).format(amount).replace('LKR', 'Rs.');
        }
    </script>
@endsection