@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="reports-app">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Reports & Variance Management</h1>
            <p class="text-gray-600">Generate reports and manage cash variances</p>
        </div>

        <!-- Report Configuration -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Configuration</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select id="report-type" onchange="toggleDateInputs()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        <option value="daily">Daily Settlement Summary</option>
                        <option value="agent-performance">Agent Performance</option>
                        <option value="variance">Variance Analysis</option>
                        <option value="commission">Commission Report</option>
                    </select>
                </div>

                <div id="single-date-container">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Date</label>
                    <input type="date" id="report-date"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                </div>

                <div id="date-range-container" class="contents hidden">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" id="start-date"
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" id="end-date"
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="generateReport()"
                    class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                    <i class="bi bi-bar-chart-fill mr-2"></i>
                    Generate Report
                </button>
                <button onclick="printReport()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="bi bi-printer mr-2"></i>
                    Print
                </button>
                <button onclick="exportReport()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="bi bi-download mr-2"></i>
                    Export
                </button>
            </div>
        </div>

        <!-- Daily Settlement Summary Report -->
        <div id="report-daily" class="hidden space-y-6 report-container">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="text-center mb-6">
                    <h2 class="text-gray-900 text-xl font-bold mb-1">Daily Settlement Summary</h2>
                    <p class="text-gray-600" id="daily-date-display"></p>
                </div>

                <!-- Summary Metrics -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-blue-600 text-sm mb-1 font-medium">Agents Reporting</p>
                        <p class="text-2xl font-bold text-blue-900" id="daily-agent-count">0</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-purple-600 text-sm mb-1 font-medium">Total Sales</p>
                        <p class="text-xl font-bold text-purple-900" id="daily-total-sales">Rs. 0.00</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-green-600 text-sm mb-1 font-medium">Total Cash</p>
                        <p class="text-xl font-bold text-green-900" id="daily-total-cash">Rs. 0.00</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <p class="text-orange-600 text-sm mb-1 font-medium">Commission</p>
                        <p class="text-xl font-bold text-orange-900" id="daily-total-commission">Rs. 0.00</p>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="mb-6">
                    <h3 class="text-gray-900 font-semibold mb-3">Payment Methods</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-3 border border-gray-200 rounded-lg">
                            <p class="text-gray-600 text-sm">Cash</p>
                            <p class="text-gray-900 font-medium" id="daily-cash-sales">Rs. 0.00</p>
                        </div>
                        <div class="p-3 border border-gray-200 rounded-lg">
                            <p class="text-gray-600 text-sm">Credit</p>
                            <p class="text-gray-900 font-medium" id="daily-credit-sales">Rs. 0.00</p>
                        </div>
                        <div class="p-3 border border-gray-200 rounded-lg">
                            <p class="text-gray-600 text-sm">Cheque</p>
                            <p class="text-gray-900 font-medium" id="daily-cheque-sales">Rs. 0.00</p>
                        </div>
                    </div>
                </div>

                <!-- Agent Details Table -->
                <div>
                    <h3 class="text-gray-900 font-semibold mb-3">Agent Details</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-gray-700 bg-gray-50 border-b">
                                <tr>
                                    <th class="px-3 py-2">Agent</th>
                                    <th class="px-3 py-2 text-right">Sales</th>
                                    <th class="px-3 py-2 text-right">Cash</th>
                                    <th class="px-3 py-2 text-right">Variance</th>
                                    <th class="px-3 py-2 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody id="daily-details-body">
                                <!-- Injected JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agent Performance Report -->
        <div id="report-performance" class="hidden report-container">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="text-center mb-6">
                    <h2 class="text-gray-900 text-xl font-bold mb-1">Agent Performance Report</h2>
                    <p class="text-gray-600" id="perf-date-display"></p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-700 bg-gray-50 border-b">
                            <tr>
                                <th class="px-3 py-3">Agent</th>
                                <th class="px-3 py-3 text-right">Total Sales</th>
                                <th class="px-3 py-3 text-right">Days Worked</th>
                                <th class="px-3 py-3 text-right">Avg/Day</th>
                                <th class="px-3 py-3 text-right">Accuracy</th>
                                <th class="px-3 py-3 text-right">Commission</th>
                            </tr>
                        </thead>
                        <tbody id="perf-details-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Variance Report -->
        <div id="report-variance" class="hidden report-container">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="text-center mb-6">
                    <h2 class="text-gray-900 text-xl font-bold mb-1">Variance Analysis Report</h2>
                    <p class="text-gray-600" id="var-date-display"></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <!-- Summary Cards -->
                    <div class="p-4 bg-gray-50 rounded-lg text-center">
                        <p class="text-gray-600 text-sm mb-1">Total Variances</p>
                        <p class="text-2xl font-bold text-gray-900" id="var-count">0</p>
                    </div>
                    <div class="p-4 bg-red-50 rounded-lg text-center">
                        <p class="text-red-600 text-sm mb-1 font-medium">Shortages</p>
                        <p class="text-xl font-bold text-red-900" id="var-shortage-amt">Rs. 0</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-lg text-center">
                        <p class="text-blue-600 text-sm mb-1 font-medium">Surpluses</p>
                        <p class="text-xl font-bold text-blue-900" id="var-surplus-amt">Rs. 0</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg text-center" id="var-net-card">
                        <p class="text-sm mb-1 font-medium">Net Variance</p>
                        <p class="text-xl font-bold" id="var-net-amt">Rs. 0</p>
                    </div>
                </div>

                <div class="space-y-3" id="var-list">
                    <!-- Injected JS -->
                </div>
            </div>
        </div>

        <!-- Commission Report -->
        <div id="report-commission" class="hidden report-container">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="text-center mb-6">
                    <h2 class="text-gray-900 text-xl font-bold mb-1">Commission Report</h2>
                    <p class="text-gray-600" id="comm-date-display"></p>
                    <p class="text-2xl font-bold text-gray-900 mt-2" id="comm-total">Total: Rs. 0.00</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-gray-700 bg-gray-50 border-b">
                            <tr>
                                <th class="px-3 py-3">Agent</th>
                                <th class="px-3 py-3 text-right">Total Sales</th>
                                <th class="px-3 py-3 text-right">Rate</th>
                                <th class="px-3 py-3 text-right">Commission</th>
                                <th class="px-3 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="comm-details-body">
                        </tbody>
                    </table>
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
            sales: serverSales
        };

        document.addEventListener('DOMContentLoaded', () => {
            // Init Dates
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('report-date').value = today;
            document.getElementById('end-date').value = today;

            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            document.getElementById('start-date').value = thirtyDaysAgo.toISOString().split('T')[0];

            // Init Default View
            toggleDateInputs();
            generateReport();
        });

        function toggleDateInputs() {
            const type = document.getElementById('report-type').value;
            const singleContainer = document.getElementById('single-date-container');
            const rangeContainer = document.getElementById('date-range-container');

            if (type === 'daily') {
                singleContainer.classList.remove('hidden');
                rangeContainer.classList.add('hidden', 'contents'); // 'contents' class handling for grid layout
                rangeContainer.style.display = 'none'; // Ensure hide
                singleContainer.style.display = 'block';
            } else {
                singleContainer.classList.add('hidden');
                singleContainer.style.display = 'none';
                rangeContainer.classList.remove('hidden');
                rangeContainer.style.display = 'contents'; // Use contents to flatten into grid
            }
        }

        function generateReport() {
            const type = document.getElementById('report-type').value;

            // Hide all reports
            document.querySelectorAll('.report-container').forEach(el => el.classList.add('hidden'));

            if (type === 'daily') {
                renderDailyReport();
            } else if (type === 'agent-performance') {
                renderPerformanceReport();
            } else if (type === 'variance') {
                renderVarianceReport();
            } else if (type === 'commission') {
                renderCommissionReport();
            }

            showToast('Report generated successfully');
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'LKR', minimumFractionDigits: 2 }).format(amount).replace('LKR', 'Rs.');
        }

        // --- Report Logic ---

        function renderDailyReport() {
            const date = document.getElementById('report-date').value;
            const container = document.getElementById('report-daily');
            container.classList.remove('hidden');

            document.getElementById('daily-date-display').textContent = new Date(date).toLocaleDateString();

            const data = state.settlements.filter(s => s.settlementDate === date);

            // Metrics
            const totalSales = data.reduce((sum, s) => sum + Number(s.totalSales), 0);
            const totalCash = data.reduce((sum, s) => sum + Number(s.actualCash), 0);
            const totalCommission = data.reduce((sum, s) => sum + Number(s.commissionEarned), 0);

            document.getElementById('daily-agent-count').textContent = data.length;
            document.getElementById('daily-total-sales').textContent = formatCurrency(totalSales);
            document.getElementById('daily-total-cash').textContent = formatCurrency(totalCash);
            document.getElementById('daily-total-commission').textContent = formatCurrency(totalCommission);

            // Payment Methods
            const cashSales = data.reduce((sum, s) => sum + Number(s.cashSales), 0);
            const creditSales = data.reduce((sum, s) => sum + Number(s.creditSales), 0);
            const chequeSales = data.reduce((sum, s) => sum + Number(s.chequeSales), 0);

            document.getElementById('daily-cash-sales').textContent = formatCurrency(cashSales);
            document.getElementById('daily-credit-sales').textContent = formatCurrency(creditSales);
            document.getElementById('daily-cheque-sales').textContent = formatCurrency(chequeSales);

            // Table
            const tbody = document.getElementById('daily-details-body');
            tbody.innerHTML = data.map(s => {
                const agent = state.agents.find(a => a.id === s.agentId);
                const varianceClass = s.cashVariance === 0 ? 'text-green-600' : (s.cashVariance > 0 ? 'text-blue-600' : 'text-red-600');
                const statusClass = getStatusBadgeClass(s.status);

                return `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-3 py-2 font-medium text-gray-900">
                            ${agent ? agent.agentName : 'Unknown'}<br>
                            <span class="text-xs text-gray-500">${agent ? agent.agentCode : ''}</span>
                        </td>
                        <td class="px-3 py-2 text-right">${formatCurrency(s.totalSales)}</td>
                        <td class="px-3 py-2 text-right">${formatCurrency(s.actualCash)}</td>
                        <td class="px-3 py-2 text-right ${varianceClass}">
                            ${s.cashVariance > 0 ? '+' : ''}${formatCurrency(s.cashVariance)}
                        </td>
                        <td class="px-3 py-2 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium capitalize ${statusClass}">${s.status}</span>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderPerformanceReport() {
            const start = document.getElementById('start-date').value;
            const end = document.getElementById('end-date').value;
            const container = document.getElementById('report-performance');
            container.classList.remove('hidden');

            document.getElementById('perf-date-display').textContent = `${new Date(start).toLocaleDateString()} - ${new Date(end).toLocaleDateString()}`;

            const periodSettlements = state.settlements.filter(s => s.settlementDate >= start && s.settlementDate <= end);
            const tbody = document.getElementById('perf-details-body');

            const reportData = state.agents.map(agent => {
                const agentStls = periodSettlements.filter(s => s.agentId === agent.id);
                if (agentStls.length === 0) return null;

                const totalSales = agentStls.reduce((sum, s) => sum + Number(s.totalSales), 0);
                const daysWorked = agentStls.length;
                const avg = totalSales / daysWorked;
                const varianceCount = agentStls.filter(s => Math.abs(s.cashVariance) > 0).length;
                const accuracy = ((daysWorked - varianceCount) / daysWorked) * 100;
                const totalComm = agentStls.reduce((sum, s) => sum + Number(s.commissionEarned), 0);

                return { agent, totalSales, daysWorked, avg, accuracy, totalComm };
            }).filter(Boolean).sort((a, b) => b.totalSales - a.totalSales);

            tbody.innerHTML = reportData.map(d => {
                const accColor = d.accuracy >= 95 ? 'bg-green-100 text-green-800' : (d.accuracy >= 90 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');

                return `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-3 py-3 font-medium text-gray-900">
                            ${d.agent.agentName}
                            <div class="text-xs text-gray-500">${d.agent.agentCode}</div>
                        </td>
                        <td class="px-3 py-3 text-right">${formatCurrency(d.totalSales)}</td>
                        <td class="px-3 py-3 text-right">${d.daysWorked}</td>
                        <td class="px-3 py-3 text-right">${formatCurrency(d.avg)}</td>
                        <td class="px-3 py-3 text-right">
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${accColor}">${d.accuracy.toFixed(0)}%</span>
                        </td>
                        <td class="px-3 py-3 text-right">${formatCurrency(d.totalComm)}</td>
                    </tr>
                `;
            }).join('');
        }

        function renderVarianceReport() {
            const start = document.getElementById('start-date').value;
            const end = document.getElementById('end-date').value;
            const container = document.getElementById('report-variance');
            container.classList.remove('hidden');

            document.getElementById('var-date-display').textContent = `${new Date(start).toLocaleDateString()} - ${new Date(end).toLocaleDateString()}`;

            const variances = state.settlements.filter(s =>
                s.settlementDate >= start && s.settlementDate <= end && Math.abs(s.cashVariance) > 0
            );

            // Stats
            const count = variances.length;
            const shortageAmt = variances.filter(v => v.cashVariance < 0).reduce((sum, v) => sum + Math.abs(v.cashVariance), 0);
            const surplusAmt = variances.filter(v => v.cashVariance > 0).reduce((sum, v) => sum + v.cashVariance, 0);
            const net = surplusAmt - shortageAmt;

            document.getElementById('var-count').textContent = count;
            document.getElementById('var-shortage-amt').textContent = formatCurrency(shortageAmt);
            document.getElementById('var-surplus-amt').textContent = formatCurrency(surplusAmt);

            const netEl = document.getElementById('var-net-amt');
            netEl.textContent = (net >= 0 ? '+' : '') + formatCurrency(net);
            const netCard = document.getElementById('var-net-card');
            if (net >= 0) {
                netCard.className = 'p-4 bg-green-50 rounded-lg text-center';
                netEl.className = 'text-xl font-bold text-green-900';
            } else {
                netCard.className = 'p-4 bg-red-50 rounded-lg text-center';
                netEl.className = 'text-xl font-bold text-red-900';
            }

            // List
            const list = document.getElementById('var-list');
            list.innerHTML = variances.map(v => {
                const agent = state.agents.find(a => a.id === v.agentId);
                const isShortage = v.cashVariance < 0;
                const borderClass = isShortage ? 'border-l-4 border-l-red-500' : 'border-l-4 border-l-blue-500';
                const colorClass = isShortage ? 'text-red-600' : 'text-blue-600';
                const typeLabel = isShortage ? 'Shortage' : 'Surplus';
                const badgeClass = isShortage ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800';

                return `
                    <div class="p-4 bg-white border border-gray-200 shadow-sm rounded-lg ${borderClass}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-900 font-medium">${v.settlementNumber}</p>
                                <p class="text-gray-600 text-sm">${agent ? agent.agentName : 'Unknown'} (${agent ? agent.agentCode : ''})</p>
                                <p class="text-gray-500 text-xs">${new Date(v.settlementDate).toLocaleDateString()}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold ${colorClass}">
                                    ${v.cashVariance > 0 ? '+' : ''}${formatCurrency(v.cashVariance)}
                                </p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-medium ${badgeClass}">
                                    ${typeLabel}
                                </span>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mt-3 pt-2 border-t border-gray-100">
                            <span class="font-medium">Effect:</span> ${v.varianceNotes || 'No explanation provided'}
                        </p>
                    </div>
                `;
            }).join('');
        }

        function renderCommissionReport() {
            const start = document.getElementById('start-date').value;
            const end = document.getElementById('end-date').value;
            const container = document.getElementById('report-commission');
            container.classList.remove('hidden');

            document.getElementById('comm-date-display').textContent = `${new Date(start).toLocaleDateString()} - ${new Date(end).toLocaleDateString()}`;

            const periodSettlements = state.settlements.filter(s => s.settlementDate >= start && s.settlementDate <= end);
            const reportData = state.agents.map(agent => {
                const agentStls = periodSettlements.filter(s => s.agentId === agent.id);
                if (agentStls.length === 0) return null;

                const totalSales = agentStls.reduce((sum, s) => sum + Number(s.totalSales), 0);
                const totalComm = agentStls.reduce((sum, s) => sum + Number(s.commissionEarned), 0);

                return { agent, totalSales, totalComm };
            }).filter(Boolean).sort((a, b) => b.totalComm - a.totalComm);

            const grandTotal = reportData.reduce((sum, d) => sum + d.totalComm, 0);
            document.getElementById('comm-total').textContent = `Total: ${formatCurrency(grandTotal)}`;

            const tbody = document.getElementById('comm-details-body');
            tbody.innerHTML = reportData.map(d => `
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-3 py-3 font-medium text-gray-900">
                        ${d.agent.agentName}
                        <div class="text-xs text-gray-500">${d.agent.agentCode}</div>
                    </td>
                    <td class="px-3 py-3 text-right">${formatCurrency(d.totalSales)}</td>
                    <td class="px-3 py-3 text-right">${d.agent.commissionRate}%</td>
                    <td class="px-3 py-3 text-right font-medium">${formatCurrency(d.totalComm)}</td>
                    <td class="px-3 py-3 text-center">
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">Pending</span>
                    </td>
                </tr>
            `).join('');
        }

        function getStatusBadgeClass(status) {
            switch (status) {
                case 'approved': return 'bg-green-100 text-green-800';
                case 'reviewed': return 'bg-blue-100 text-blue-800';
                case 'disputed': return 'bg-red-100 text-red-800';
                default: return 'bg-yellow-100 text-yellow-800';
            }
        }

        function printReport() {
            window.print();
            showToast('Printing report...', 'info');
        }

        function exportReport() {
            showToast('Export functionality simulating...', 'info');
        }

        function showToast(message, type = 'success') {
            const div = document.createElement('div');
            const bg = type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-blue-600');
            div.className = `fixed top-4 right-4 ${bg} text-white px-6 py-3 rounded-lg shadow-lg z-[70] transition-opacity duration-300 transform translate-y-0`;
            div.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-info-circle'} mr-2"></i> ${message}`;
            document.body.appendChild(div);
            setTimeout(() => {
                div.style.opacity = '0';
                setTimeout(() => div.remove(), 300);
            }, 3000);
        }
    </script>
@endsection