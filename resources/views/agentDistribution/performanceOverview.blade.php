@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="performance-overview-app">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Agent Performance Overview</h1>
                <p class="text-gray-600">Detailed month-wise achievement and commission tracking</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('financialDashboard.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
                    <i class="bi bi-arrow-left"></i>
                    Back to Settlements
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Agent</label>
                    <select id="agent-select" onchange="updateDashboard()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->agent_name }} ({{ $agent->agent_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select id="year-select" onchange="updateDashboard()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        @for($y = date('Y'); $y >= 2024; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                    <select id="month-select" onchange="updateDashboard()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endforeach
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
                    <span class="text-purple-700 text-xs font-semibold uppercase tracking-wider">Total Sales (Actual)</span>
                </div>
                <p class="text-2xl font-bold text-purple-900 mb-1" id="metric-total-sales">Rs. 0</p>
                <p class="text-purple-700 text-xs" id="metric-target-amt">Target: Rs. 0</p>
            </div>

            <!-- Net Commission -->
            <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-award text-green-600 text-xl"></i>
                    <span class="text-green-700 text-xs font-semibold uppercase tracking-wider">Monthly Commission</span>
                </div>
                <p class="text-2xl font-bold text-green-900 mb-1" id="metric-total-comm">Rs. 0</p>
                <p class="text-green-700 text-xs" id="metric-comm-status">Pending Calculation</p>
            </div>

            <!-- Orders Count -->
            <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-box-seam text-yellow-600 text-xl"></i>
                    <span class="text-yellow-700 text-xs font-semibold uppercase tracking-wider">Sales (Invoices)</span>
                </div>
                <p class="text-2xl font-bold text-yellow-900 mb-1" id="metric-order-count">0</p>
                <p class="text-yellow-700 text-xs" id="metric-getting-amt">Stock Requests: Rs. 0</p>
            </div>

            <!-- Overall Achievement -->
            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-bullseye text-blue-600 text-xl"></i>
                    <span class="text-blue-700 text-xs font-semibold uppercase tracking-wider">Target Achievement</span>
                </div>
                <p class="text-2xl font-bold text-blue-900 mb-1" id="metric-achievement">0%</p>
                <span id="metric-achievement-badge" class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">-</span>
            </div>
        </div>

        <!-- Performance Overview Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-gray-900 font-semibold mb-4">Sales vs Target (Last 6 Months)</h3>
                <div class="relative h-72">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-gray-900 font-semibold mb-4">Category Performance Breakdown</h3>
                <div class="relative h-72">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Commission Trend -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
            <h3 class="text-gray-900 font-semibold mb-4">Commission & Payment Status Trend</h3>
            <div class="relative h-72">
                <canvas id="commissionChart"></canvas>
            </div>
        </div>

        <!-- Detailed Targets Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-gray-900 font-semibold mb-4 text-purple-700">Detailed Category Achievement</h3>
                <div id="category-details" class="space-y-5"></div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="text-gray-900 font-semibold mb-4 text-green-700">Financial Gain Summary</h3>
                <div id="financial-summary"></div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const state = {
            charts: {},
            activeTarget: null
        };

        document.addEventListener('DOMContentLoaded', () => {
            updateDashboard();
        });

        async function updateDashboard() {
            const agentId = document.getElementById('agent-select').value;
            const year = document.getElementById('year-select').value;
            const month = document.getElementById('month-select').value;

            if (!agentId) return;

            try {
                const response = await fetch(`{{ route('agents.performanceOverview.getData') }}?agent_id=${agentId}&year=${year}&month=${month}`);
                const data = await response.json();

                if (data.success) {
                    state.activeTarget = data.target_details;
                    renderMetrics(data.metrics);
                    renderCharts(data.charts);
                    renderDetails(data.metrics, data.charts.categories);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            } catch (error) {
                console.error('Dashboard error:', error);
            }
        }

        function renderMetrics(metrics) {
            document.getElementById('metric-total-sales').textContent = formatCurrency(metrics.totalSales);
            document.getElementById('metric-target-amt').textContent = `Target: ${formatCurrency(metrics.targetAmount)}`;

            document.getElementById('metric-total-comm').textContent = formatCurrency(metrics.commission);
            
            const statusLabel = metrics.paymentStatus == 2 ? 'Paid' : (metrics.paymentStatus == 1 ? 'Processed' : 'Pending');
            const statusColor = metrics.paymentStatus == 2 ? 'text-green-600' : (metrics.paymentStatus == 1 ? 'text-blue-600' : 'text-amber-600');
            document.getElementById('metric-comm-status').innerHTML = `<span class="${statusColor} font-medium">${statusLabel}</span>`;

            document.getElementById('metric-order-count').textContent = metrics.orderCount;
            document.getElementById('metric-getting-amt').textContent = `Getting: ${formatCurrency(metrics.gettingAmount)}`;
            
            const ach = metrics.achievement;
            document.getElementById('metric-achievement').textContent = `${ach.toFixed(1)}%`;
            
            const accBadge = document.getElementById('metric-achievement-badge');
            if (ach >= 100) {
                accBadge.textContent = 'Excellent';
                accBadge.className = 'px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800';
            } else if (ach >= 80) {
                accBadge.textContent = 'Good';
                accBadge.className = 'px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800';
            } else {
                accBadge.textContent = 'Below Target';
                accBadge.className = 'px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800';
            }
        }

        function renderCharts(charts) {
            // Trend
            const ctxTrend = document.getElementById('trendChart').getContext('2d');
            if (state.charts.trend) state.charts.trend.destroy();
            state.charts.trend = new Chart(ctxTrend, {
                type: 'bar',
                data: {
                    labels: charts.trend.labels,
                    datasets: [
                        { label: 'Sales', data: charts.trend.sales, backgroundColor: '#8B5CF6', borderRadius: 4 },
                        { label: 'Stock Requests', data: charts.trend.getting, backgroundColor: '#E5E7EB', borderRadius: 4 },
                        { label: 'Target', data: charts.trend.targets, type: 'line', borderColor: '#10B981', tension: 0.4, borderWidth: 3 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // 2. Category Chart (Grouped Bar)
            const ctxCat = document.getElementById('categoryChart').getContext('2d');
            if (state.charts.category) state.charts.category.destroy();
            state.charts.category = new Chart(ctxCat, {
                type: 'bar',
                data: {
                    labels: charts.categories.map(c => c.name),
                    datasets: [
                        {
                            label: 'Actual Sales',
                            data: charts.categories.map(c => c.actual),
                            backgroundColor: '#8B5CF6',
                            borderRadius: 4,
                            barThickness: 20
                        },
                        {
                            label: 'Target Amount',
                            data: charts.categories.map(c => c.target),
                            backgroundColor: '#E5E7EB',
                            borderColor: '#10B981',
                            borderWidth: 1,
                            borderRadius: 4,
                            barThickness: 20
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.dataset.label}: ${formatCurrency(ctx.parsed.x)}`
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { display: false }
                        },
                        y: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Commission
            const ctxComm = document.getElementById('commissionChart').getContext('2d');
            if (state.charts.comm) state.charts.comm.destroy();
            state.charts.comm = new Chart(ctxComm, {
                type: 'line',
                data: {
                    labels: charts.commissions.map(c => c.label),
                    datasets: [{
                        label: 'Commission', 
                        data: charts.commissions.map(c => c.amount),
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` Rs. ${ctx.parsed.y} (${charts.commissions[ctx.dataIndex].status == 2 ? 'Paid' : 'Pending'})`
                            }
                        }
                    }
                }
            });
        }

        function renderDetails(metrics, categories) {
            const container = document.getElementById('category-details');
            if (categories.length === 0) {
                container.innerHTML = '<p class="text-gray-500 italic">No category targets defined.</p>';
            } else {
                container.innerHTML = categories.map(c => {
                    const pct = c.target > 0 ? (c.actual / c.target) * 100 : 0;
                    return `
                        <div>
                            <div class="flex justify-between text-sm mb-1 font-medium">
                                <span>${c.name}</span>
                                <span class="text-gray-600">${formatCurrency(c.actual)} / ${formatCurrency(c.target)}</span>
                            </div>
                            <div class="h-2.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full ${pct >= 100 ? 'bg-green-500' : (pct >= 80 ? 'bg-amber-500' : 'bg-red-500')}" 
                                     style="width: ${Math.min(pct, 100)}%"></div>
                            </div>
                            <p class="text-right text-xs mt-1 font-bold ${pct >= 100 ? 'text-green-600' : 'text-gray-500'}">${pct.toFixed(1)}%</p>
                        </div>
                    `;
                }).join('');
            }

            const summary = document.getElementById('financial-summary');
            if (!state.activeTarget) {
                summary.innerHTML = '<p class="text-center py-6 text-gray-500">Target Not Set</p>';
                return;
            }

            const cb = metrics.commissionBreakdown;
            
            summary.innerHTML = `
                <div class="space-y-4">
                    <div class="p-3 bg-gray-50 rounded-lg space-y-2 border border-gray-100">
                        <div class="flex justify-between text-sm hidden">
                            <span class="text-gray-500">Invoicing Comm. (${cb.invoicing_rate}%)</span>
                            <span class="font-medium">${formatCurrency(cb.base_commission)}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Bonus Comm. (${cb.bonus_rate}%)</span>
                            <span class="font-medium ${cb.bonus_commission > 0 ? 'text-green-600' : ''}">${formatCurrency(cb.bonus_commission)}</span>
                        </div>
                        <div class="pt-1 mt-1 border-t border-gray-200 flex justify-between font-bold">
                            <span class="text-gray-700">Total Bonus Commission (Earnings)</span>
                            <span class="text-amber-600">${formatCurrency(cb.bonus_commission)}</span>
                        </div>
                        <p class="text-[10px] text-gray-400 italic mt-1 text-right">* Calculated based on ${metrics.achievement.toFixed(1)}% achievement</p>
                    </div>

                    <div class="flex justify-between items-center px-2">
                        <span class="text-xs text-gray-500">Achievement Status:</span>
                        <span class="text-xs font-bold ${cb.is_target_achieved ? 'text-green-600' : 'text-amber-600'}">${cb.achievement_msg}</span>
                    </div>

                    <div class="flex justify-between bg-purple-50 p-4 rounded-xl border border-purple-100">
                        <div class="flex flex-col">
                            <span class="text-purple-700 font-bold uppercase tracking-wider text-[10px]">Net Earnings (EST)</span>
                            <span class="text-[10px] text-purple-400">Bonus Commission Only</span>
                        </div>
                        <span class="font-bold text-purple-900 text-xl">${formatCurrency(metrics.commission)}</span>
                    </div>
                </div>
            `;
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'LKR', minimumFractionDigits: 0 }).format(amount).replace('LKR', 'Rs.');
        }
    </script>
@endsection
