<div class="p-6 max-w-full mx-auto w-full">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-2xl text-gray-900 mb-1 font-bold">Shift Report</h2>
            <p class="text-gray-600">Cashier: <span id="report-cashier-name">Current User</span></p>
        </div>
        <div class="flex gap-3">
            <select id="report-date-filter" onchange="updateReportDashboard()"
                class="h-10 px-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white text-sm">
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                <option value="week">Last 7 Days</option>
                <option value="month">Last 30 Days</option>
            </select>
            <button onclick="exportShiftReport()"
                class="h-10 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl flex items-center gap-2 transition-colors text-sm font-medium">
                <i class="bi bi-download"></i>
                Export
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <i class="bi bi-currency-dollar text-3xl opacity-80"></i>
                <i class="bi bi-graph-up text-xl opacity-60"></i>
            </div>
            <p class="text-sm opacity-80 mb-1">Total Sales</p>
            <p id="metric-total-sales" class="text-3xl font-bold">Rs 0</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <i class="bi bi-cart text-3xl opacity-80"></i>
            </div>
            <p class="text-sm opacity-80 mb-1">Transactions</p>
            <p id="metric-tx-count" class="text-3xl font-bold">0</p>
        </div>

        <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <i class="bi bi-award text-3xl opacity-80"></i>
            </div>
            <p class="text-sm opacity-80 mb-1">Average Sale</p>
            <p id="metric-avg-sale" class="text-3xl font-bold">Rs 0</p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-2">
                <i class="bi bi-percent text-3xl opacity-80"></i>
            </div>
            <p class="text-sm opacity-80 mb-1">Discounts Given</p>
            <p id="metric-discounts" class="text-3xl font-bold">Rs 0</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-lg text-gray-900 mb-4 font-bold">Hourly Sales Distribution</h3>
            <div class="relative h-64 w-full">
                <canvas id="hourlySalesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-lg text-gray-900 mb-4 font-bold">Payment Methods</h3>
            <div class="relative h-64 w-full flex items-center justify-center">
                <canvas id="paymentMethodChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200 mb-6 shadow-sm">
        <h3 class="text-lg text-gray-900 mb-4 font-bold">Payment Method Breakdown</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm text-gray-600 font-medium">Payment Method
                        </th>
                        <th class="text-right py-3 px-4 text-sm text-gray-600 font-medium">Amount</th>
                        <th class="text-right py-3 px-4 text-sm text-gray-600 font-medium">Percentage
                        </th>
                    </tr>
                </thead>
                <tbody id="payment-breakdown-body">
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-gray-200 mb-6 shadow-sm">
        <h3 class="text-lg text-gray-900 mb-4 font-bold">Top Selling Products</h3>
        <div id="top-products-list" class="space-y-3">
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="bi bi-arrow-counterclockwise text-red-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg text-gray-900 font-bold">Returns & Refunds</h3>
                    <p id="return-tx-count" class="text-sm text-gray-500">0 transactions</p>
                </div>
            </div>
            <div class="bg-red-50 rounded-xl p-4">
                <p class="text-sm text-red-600 mb-1">Total Refunded:</p>
                <p id="return-total-amount" class="text-3xl text-red-700 font-bold">Rs 0.00</p>
                <p id="return-percentage" class="text-sm text-red-600 mt-2">0% of total sales</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="bi bi-percent text-orange-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg text-gray-900 font-bold">Discounts</h3>
                    <p class="text-sm text-gray-500">Applied to transactions</p>
                </div>
            </div>
            <div class="bg-orange-50 rounded-xl p-4">
                <p class="text-sm text-orange-600 mb-1">Total Discounts:</p>
                <p id="discount-total-amount" class="text-3xl text-orange-700 font-bold">Rs 0.00</p>
                <p id="discount-rate" class="text-sm text-orange-600 mt-2">0% discount rate</p>
            </div>
        </div>
    </div>
</div>

<script>
    var reportData = [];
    var hourlyChartInstance = null;
    var paymentChartInstance = null;
    const CHART_COLORS = ['#8b5cf6', '#ec4899', '#3b82f6', '#10b981', '#f59e0b'];

    $(document).ready(function () {
        // Set Mock Cashier Name
        document.getElementById('report-cashier-name').textContent = "{{ auth()->user()->name ?? 'Cashier' }}";
        fetchReportData();
    });

    function fetchReportData() {
        $.ajax({
            url: "{{ route('pos.report') }}",
            method: 'GET',
            success: function (response) {
                reportData = response;
                updateReportDashboard();
            },
            error: function () {
                toastr.error('Failed to load shift report data');
            }
        });
    }

    function getFilteredTransactions() {
        const dateFilter = document.getElementById('report-date-filter').value;
        const now = new Date();
        const todayStart = new Date(now.getFullYear(), now.getMonth(), now.getDate());

        return reportData.filter(txn => {
            const txnDate = new Date(txn.timestamp);

            if (dateFilter === 'today') {
                return txnDate >= todayStart;
            } else if (dateFilter === 'yesterday') {
                const yesterdayStart = new Date(todayStart);
                yesterdayStart.setDate(yesterdayStart.getDate() - 1);
                const yesterdayEnd = new Date(todayStart);
                return txnDate >= yesterdayStart && txnDate < yesterdayEnd;
            } else if (dateFilter === 'week') {
                const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                return txnDate >= weekAgo;
            } else if (dateFilter === 'month') {
                const monthAgo = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
                return txnDate >= monthAgo;
            }
            return true;
        });
    }

    function updateReportDashboard() {
        const filteredTxns = getFilteredTransactions();

        const totalSales = filteredTxns.reduce((sum, t) => sum + t.total, 0);
        const totalTx = filteredTxns.length;
        const avgSale = totalTx > 0 ? totalSales / totalTx : 0;
        const totalDiscounts = filteredTxns.reduce((sum, t) => sum + t.discount, 0);

        document.getElementById('metric-total-sales').textContent = 'Rs ' + totalSales.toFixed(0);
        document.getElementById('metric-tx-count').textContent = totalTx;
        document.getElementById('metric-avg-sale').textContent = 'Rs ' + avgSale.toFixed(0);
        document.getElementById('metric-discounts').textContent = 'Rs ' + totalDiscounts.toFixed(0);

        const paymentStats = {};
        filteredTxns.forEach(txn => {
            txn.paymentMethods.forEach(pm => {
                const m = pm.method || 'unknown';
                paymentStats[m] = (paymentStats[m] || 0) + pm.amount;
            });
        });

        const hourlyData = Array(24).fill(0);
        filteredTxns.forEach(txn => {
            const h = new Date(txn.timestamp).getHours();
            hourlyData[h] += txn.total;
        });

        const productStats = {};
        filteredTxns.forEach(txn => {
            txn.items.forEach(item => {
                if (!productStats[item.productName]) {
                    productStats[item.productName] = { quantity: 0, revenue: 0 };
                }
                productStats[item.productName].quantity += item.quantity;
                productStats[item.productName].revenue += item.lineTotal;
            });
        });
        const topProducts = Object.entries(productStats)
            .map(([name, data]) => ({ name, ...data }))
            .sort((a, b) => b.revenue - a.revenue)
            .slice(0, 5);

        const returns = filteredTxns.filter(t => t.status === 'returned');
        const totalReturns = returns.reduce((sum, t) => sum + t.total, 0);

        renderHourlyChart(hourlyData);
        renderPaymentChart(paymentStats);
        renderPaymentTable(paymentStats, totalSales);
        renderTopProducts(topProducts, totalSales);
        updateReturnsAndDiscounts(returns.length, totalReturns, totalSales, totalDiscounts);
    }

    function renderHourlyChart(data) {
        const ctx = document.getElementById('hourlySalesChart').getContext('2d');
        if (hourlyChartInstance) hourlyChartInstance.destroy();

        const labels = data.map((_, i) => `${i.toString().padStart(2, '0')}:00`);

        hourlyChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales',
                    data: data,
                    backgroundColor: '#8b5cf6',
                    borderRadius: 4,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return 'Rs ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    function renderPaymentChart(stats) {
        const ctx = document.getElementById('paymentMethodChart').getContext('2d');
        if (paymentChartInstance) paymentChartInstance.destroy();

        const labels = Object.keys(stats).map(k => k.charAt(0).toUpperCase() + k.slice(1));
        const data = Object.values(stats);

        paymentChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: CHART_COLORS,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return ' ' + context.label + ': Rs ' + context.parsed.toFixed(2);
                            }
                        }
                    }
                }
            }
        });
    }

    function renderPaymentTable(stats, totalSales) {
        const tbody = document.getElementById('payment-breakdown-body');
        let html = '';

        Object.entries(stats).forEach(([method, amount]) => {
            const pct = totalSales > 0 ? ((amount / totalSales) * 100).toFixed(1) : 0;
            html += `
                <tr class="border-b border-gray-100 last:border-0">
                    <td class="py-3 px-4 capitalize flex items-center gap-2">
                        <i class="bi bi-credit-card text-gray-400"></i> ${method}
                    </td>
                    <td class="py-3 px-4 text-right text-gray-900">Rs ${amount.toFixed(2)}</td>
                    <td class="py-3 px-4 text-right text-gray-600">${pct}%</td>
                </tr>
            `;
        });

        html += `
            <tr class="bg-gray-50 font-bold">
                <td class="py-3 px-4">Total</td>
                <td class="py-3 px-4 text-right text-gray-900">Rs ${totalSales.toFixed(2)}</td>
                <td class="py-3 px-4 text-right text-gray-900">100%</td>
            </tr>
        `;
        tbody.innerHTML = html;
    }

    function renderTopProducts(products, totalSales) {
        const container = document.getElementById('top-products-list');
        if (products.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-4">No product sales data</p>';
            return;
        }

        container.innerHTML = products.map((prod, idx) => `
            <div class="flex items-center gap-4 border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                <div class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center font-bold text-sm">
                    ${idx + 1}
                </div>
                <div class="flex-1">
                    <p class="text-gray-900 font-medium">${prod.name}</p>
                    <p class="text-sm text-gray-500">${prod.quantity} units sold</p>
                </div>
                <div class="text-right">
                    <p class="text-gray-900 font-medium">Rs ${prod.revenue.toFixed(2)}</p>
                    <p class="text-sm text-gray-500">
                        ${totalSales > 0 ? ((prod.revenue / totalSales) * 100).toFixed(1) : 0}%
                    </p>
                </div>
            </div>
        `).join('');
    }

    function updateReturnsAndDiscounts(returnCount, returnTotal, totalSales, discountTotal) {
        document.getElementById('return-tx-count').textContent = `${returnCount} transactions`;
        document.getElementById('return-total-amount').textContent = 'Rs ' + returnTotal.toFixed(2);
        const returnPct = totalSales > 0 ? ((returnTotal / totalSales) * 100).toFixed(2) : 0;
        document.getElementById('return-percentage').textContent = `${returnPct}% of total sales`;

        document.getElementById('discount-total-amount').textContent = 'Rs ' + discountTotal.toFixed(2);
        const discountRate = (totalSales + discountTotal) > 0 ? ((discountTotal / (totalSales + discountTotal)) * 100).toFixed(2) : 0;
        document.getElementById('discount-rate').textContent = `${discountRate}% discount rate`;
    }

    function exportShiftReport() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Exporting...',
                text: 'Shift report is being generated.',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            alert("Exporting shift report...");
        }
    }
</script>