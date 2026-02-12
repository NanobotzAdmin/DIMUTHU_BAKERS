@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="financial-dashboard-app">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Agent Financial Dashboard</h1>
            <p class="text-gray-600">Comprehensive financial overview and performance metrics</p>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Agent</label>
                    <select id="agent-select" onchange="handleAgentChange()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        <!-- Populated by JS -->
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time Period</label>
                    <select id="period-select" onchange="handlePeriodChange()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        <option value="month">Last 30 Days</option>
                        <option value="quarter">Last 90 Days</option>
                        <option value="year">Last 12 Months</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Sales -->
            <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-currency-dollar text-purple-600 text-xl"></i>
                    <span class="text-purple-700 text-xs font-semibold uppercase tracking-wider">Total Sales</span>
                </div>
                <p class="text-2xl font-bold text-purple-900 mb-1" id="metric-total-sales">Rs. 0</p>
                <p class="text-purple-700 text-xs" id="metric-days-worked">0 days worked</p>
            </div>

            <!-- Total Commission -->
            <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-award text-green-600 text-xl"></i>
                    <span class="text-green-700 text-xs font-semibold uppercase tracking-wider">Total Commission</span>
                </div>
                <p class="text-2xl font-bold text-green-900 mb-1" id="metric-total-comm">Rs. 0</p>
                <p class="text-green-700 text-xs" id="metric-comm-rate">0% rate</p>
            </div>

            <!-- Pending Commission -->
            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-clock-history text-yellow-600 text-xl"></i>
                    <span class="text-yellow-700 text-xs font-semibold uppercase tracking-wider">Pending Commission</span>
                </div>
                <p class="text-2xl font-bold text-yellow-900" id="metric-pending-comm">Rs. 0</p>
            </div>

            <!-- Cash Accuracy -->
            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-bullseye text-blue-600 text-xl"></i>
                    <span class="text-blue-700 text-xs font-semibold uppercase tracking-wider">Cash Accuracy</span>
                </div>
                <p class="text-2xl font-bold text-blue-900 mb-1" id="metric-accuracy">0%</p>
                <span id="metric-accuracy-badge"
                    class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Excellent</span>
            </div>
        </div>

        <!-- Performance Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center gap-2 mb-3">
                    <i class="bi bi-graph-up text-amber-500 text-lg"></i>
                    <h3 class="text-gray-900 font-semibold">Average Daily Sales</h3>
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-2" id="overview-avg-sales">Rs. 0</p>
                <p class="text-gray-600 text-sm">Per working day</p>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center gap-2 mb-3">
                    <i class="bi bi-bag-check text-amber-500 text-lg"></i>
                    <h3 class="text-gray-900 font-semibold">Avg Transaction Value</h3>
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-2" id="overview-avg-txn">Rs. 0</p>
                <p class="text-gray-600 text-sm" id="overview-txn-count">0 transactions</p>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center gap-2 mb-3">
                    <i class="bi bi-check-circle text-amber-500 text-lg"></i>
                    <h3 class="text-gray-900 font-semibold">Commission Paid</h3>
                </div>
                <p class="text-3xl font-bold text-green-600 mb-2" id="overview-paid-comm">Rs. 0</p>
                <p class="text-gray-600 text-sm" id="overview-paid-pct">0% of total</p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Trend Chart -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-gray-900 font-semibold mb-4">Monthly Sales & Commission Trend</h3>
                <div class="relative h-72">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Payment Method Chart -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-gray-900 font-semibold mb-4">Payment Method Distribution</h3>
                <div class="relative h-72 flex justify-center">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Commission History Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
            <h3 class="text-gray-900 font-semibold mb-4">Commission History (Last 6 Periods)</h3>
            <div class="relative h-72">
                <canvas id="commissionChart"></canvas>
            </div>
        </div>

        <!-- Recent Settlements -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-gray-900 font-semibold mb-4">Recent Settlements</h3>
            <div id="recent-list" class="space-y-3">
                <!-- Injected JS -->
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Server Data
        const serverAgents = @json($agents ?? []);
        const serverSettlements = @json($settlements ?? []);
        const serverCommissions = @json($commissionPayments ?? []);

        const state = {
            agents: serverAgents,
            settlements: serverSettlements,
            commissions: serverCommissions,
            selectedAgentId: null,
            period: 'month',
            charts: {}
        };

        document.addEventListener('DOMContentLoaded', () => {
            initAgents();
            if (state.agents.length > 0) {
                state.selectedAgentId = state.agents[0].id;
                updateDashboard();
            }
        });

        function initAgents() {
            const select = document.getElementById('agent-select');
            select.innerHTML = state.agents.map(a =>
                `<option value="${a.id}">${a.agentName} (${a.agentCode})</option>`
            ).join('');
        }

        function handleAgentChange() {
            state.selectedAgentId = document.getElementById('agent-select').value;
            updateDashboard();
        }

        function handlePeriodChange() {
            state.period = document.getElementById('period-select').value;
            updateDashboard();
        }

        function updateDashboard() {
            if (!state.selectedAgentId) return;

            const agent = state.agents.find(a => a.id === state.selectedAgentId);
            const { startDate, endDate } = getPeriodDates(state.period);

            // Filter Data
            const filteredSettlements = state.settlements.filter(s =>
                s.agentId === state.selectedAgentId &&
                s.settlementDate >= startDate &&
                s.settlementDate <= endDate
            );

            const filteredCommissions = state.commissions.filter(c =>
                c.agentId === state.selectedAgentId &&
                c.periodEnd >= startDate &&
                c.periodEnd <= endDate
            );

            const allAgentCommissions = state.commissions.filter(c => c.agentId === state.selectedAgentId); // For history chart logic

            // Calc Metrics
            const totalSales = filteredSettlements.reduce((sum, s) => sum + s.totalSales, 0);
            // Using all matching commissions for total/pending/paid logic within period if intended, or lifetime? 
            // Based on React code, it seemed to be period based for commissioning stats.
            // Actually commissions usually lag, but let's stick to period filter for specific period performance, 
            // but maybe pending is a "current state" thing. Let's filter by period for report consistency.

            const totalCommission = filteredCommissions.reduce((sum, c) => sum + Number(c.netCommission), 0);
            const paidCommission = filteredCommissions.filter(c => c.paymentStatus === 'paid').reduce((sum, c) => sum + Number(c.netCommission), 0);
            const pendingCommission = filteredCommissions.filter(c => c.paymentStatus === 'pending').reduce((sum, c) => sum + Number(c.netCommission), 0);

            const daysWorked = filteredSettlements.length;
            const avgDailySales = daysWorked > 0 ? totalSales / daysWorked : 0;

            // Mock transaction count estimate from totalSales for Average Transaction Value logic demo
            const estTxnCount = Math.round(totalSales / 2000); // assume 2000 avg
            const avgTxnValue = estTxnCount > 0 ? totalSales / estTxnCount : 0;

            // Cash Accuracy
            const varianceCount = filteredSettlements.filter(s => Math.abs(s.cashVariance) > 0).length;
            const accuracy = daysWorked > 0 ? ((daysWorked - varianceCount) / daysWorked) * 100 : 100;

            // Update UI
            document.getElementById('metric-total-sales').textContent = formatCurrency(totalSales);
            document.getElementById('metric-days-worked').textContent = `${daysWorked} days worked`;

            document.getElementById('metric-total-comm').textContent = formatCurrency(totalCommission);
            document.getElementById('metric-comm-rate').textContent = `${agent.commissionRate}% rate`;

            document.getElementById('metric-pending-comm').textContent = formatCurrency(pendingCommission);

            document.getElementById('metric-accuracy').textContent = `${accuracy.toFixed(1)}%`;
            const accBadge = document.getElementById('metric-accuracy-badge');
            if (accuracy >= 95) {
                accBadge.textContent = 'Excellent';
                accBadge.className = 'px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800';
            } else if (accuracy >= 90) {
                accBadge.textContent = 'Good';
                accBadge.className = 'px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800';
            } else {
                accBadge.textContent = 'Needs Improvement';
                accBadge.className = 'px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800';
            }

            document.getElementById('overview-avg-sales').textContent = formatCurrency(avgDailySales);
            document.getElementById('overview-avg-txn').textContent = formatCurrency(avgTxnValue);
            document.getElementById('overview-txn-count').textContent = `~${estTxnCount} transactions`;

            document.getElementById('overview-paid-comm').textContent = formatCurrency(paidCommission);
            const paidPct = totalCommission > 0 ? (paidCommission / totalCommission) * 100 : 0;
            document.getElementById('overview-paid-pct').textContent = `${paidPct.toFixed(0)}% of total`;

            // Render Lists
            renderRecentActivity(filteredSettlements);

            // Render Charts
            updateCharts(filteredSettlements, allAgentCommissions);
        }

        function renderRecentActivity(settlements) {
            const list = document.getElementById('recent-list');
            const recent = [...settlements].sort((a, b) => new Date(b.settlementDate) - new Date(a.settlementDate)).slice(0, 5);

            list.innerHTML = recent.map(s => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <p class="text-gray-900 font-medium">${s.settlementNumber}</p>
                        <p class="text-gray-600 text-sm">${new Date(s.settlementDate).toLocaleDateString()}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-900 font-bold">${formatCurrency(s.totalSales)}</p>
                        <p class="text-green-600 text-sm">Comm: ${formatCurrency(s.commissionEarned)}</p>
                    </div>
                    <span class="ml-3 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 capitalize">
                        ${s.status}
                    </span>
                </div>
            `).join('');
        }

        // --- Charting ---
        function updateCharts(settlements, allCommissions) {
            // Prepare Data

            // 1. Trend (Monthly Aggregation)
            const monthlyData = {};
            settlements.forEach(s => {
                const m = s.settlementDate.substring(0, 7); // YYYY-MM
                if (!monthlyData[m]) monthlyData[m] = { sales: 0, comm: 0 };
                monthlyData[m].sales += s.totalSales;
                monthlyData[m].comm += s.commissionEarned;
            });
            const sortedMonths = Object.keys(monthlyData).sort();

            // 2. Payment Method
            const paymentData = { cash: 0, credit: 0, cheque: 0 };
            settlements.forEach(s => {
                paymentData.cash += Number(s.cashSales);
                paymentData.credit += Number(s.creditSales);
                paymentData.cheque += Number(s.chequeSales);
            });

            // 3. Commission History (Last 6 distinct periods from allCommissions)
            // Sort by date
            const sortedComms = [...allCommissions].sort((a, b) => new Date(a.periodEnd) - new Date(b.periodEnd)).slice(-6);

            // --- Render Trend Chart ---
            const ctxTrend = document.getElementById('trendChart').getContext('2d');
            if (state.charts.trend) state.charts.trend.destroy();

            state.charts.trend = new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: sortedMonths,
                    datasets: [
                        {
                            label: 'Sales',
                            data: sortedMonths.map(m => monthlyData[m].sales),
                            borderColor: '#8B5CF6',
                            backgroundColor: '#8B5CF6',
                            tension: 0.4
                        },
                        {
                            label: 'Commission',
                            data: sortedMonths.map(m => monthlyData[m].comm),
                            borderColor: '#10B981',
                            backgroundColor: '#10B981',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false }
                }
            });

            // --- Render Payment Pie ---
            const ctxPie = document.getElementById('paymentChart').getContext('2d');
            if (state.charts.pie) state.charts.pie.destroy();

            state.charts.pie = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: ['Cash', 'Credit', 'Cheque'],
                    datasets: [{
                        data: [paymentData.cash, paymentData.credit, paymentData.cheque],
                        backgroundColor: ['#10B981', '#F59E0B', '#3B82F6']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // --- Render Commission Bar ---
            const ctxBar = document.getElementById('commissionChart').getContext('2d');
            if (state.charts.bar) state.charts.bar.destroy();

            state.charts.bar = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: sortedComms.map(c => new Date(c.periodEnd).toLocaleDateString(undefined, { month: 'short', year: '2-digit' })),
                    datasets: [
                        {
                            label: 'Gross',
                            data: sortedComms.map(c => c.grossCommission),
                            backgroundColor: '#8B5CF6'
                        },
                        {
                            label: 'Net Pay',
                            data: sortedComms.map(c => c.netCommission),
                            backgroundColor: '#10B981'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        }

        function getPeriodDates(period) {
            const end = new Date();
            const start = new Date();
            if (period === 'month') start.setDate(start.getDate() - 30);
            else if (period === 'quarter') start.setDate(start.getDate() - 90);
            else if (period === 'year') start.setFullYear(start.getFullYear() - 1);

            return {
                startDate: start.toISOString().split('T')[0],
                endDate: end.toISOString().split('T')[0]
            };
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'LKR', minimumFractionDigits: 0 }).format(amount).replace('LKR', 'Rs.');
        }
    </script>
@endsection