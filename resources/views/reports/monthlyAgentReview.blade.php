@extends('layouts.app')

@section('content')

    <style>
        .report-header {
            background-color: #b45309;
            /* Amber 700 */
            color: white;
        }

        .metric-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1.25rem;
            background: white;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .large-metric-card {
            border: 2px solid #d97706;
            /* Amber 600 */
        }

        .table-simple th {
            font-size: 0.75rem;
            background-color: #f8fafc;
            color: #b45309;
            /* Amber 700 */
            padding: 0.5rem 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }

        .table-simple td {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .table-simple tr:last-child td {
            border-bottom: none;
        }

        .section-title {
            color: #b45309;
            /* Amber 700 */
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Monthly Agent Review</h2>
                <p class="text-sm text-gray-500 mt-1">Review monthly sales performance, MoM comparison, commission, and credit position by agent.</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <form id="reportFilterForm" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                @csrf
                <div>
                    <label for="agent_id" class="block text-sm font-semibold text-gray-700 mb-1">Select Agent</label>
                    <select id="agent_id" name="agent_id" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2.5">
                        <option value="">-- Choose an Agent --</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->agent_name }} ({{ $agent->agent_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="month" class="block text-sm font-semibold text-gray-700 mb-1">Select Month</label>
                    <input type="month" id="month" name="month" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2.5">
                </div>
                <div class="flex gap-2">
                    <button type="submit" id="btnLoadReport"
                        class="flex-1 flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <i class="bi bi-funnel mr-2"></i> Generate Report
                    </button>
                    <button type="button" onclick="exportToPDF()"
                        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md transition-all active:scale-95">
                        <i class="bi bi-file-earmark-pdf-fill text-lg"></i> Export PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden py-12 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-amber-600"></div>
            <p class="mt-2 text-sm text-gray-500 font-medium">Generating monthly review...</p>
        </div>

        <!-- Dashboard Container -->
        <div id="dashboardContainer"
            class="hidden max-w-5xl mx-auto bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="report-header p-6 flex justify-between items-end">
                <div>
                    <div class="text-xs uppercase tracking-widest font-semibold mb-1 opacity-80">Dimuthu Bake House — Agent
                        Review</div>
                    <h1 class="text-3xl font-bold tracking-tight">Monthly Agent Review</h1>
                </div>
                <div class="text-right text-sm">
                    <div class="font-bold text-lg" id="lblMonthYear">July 2026</div>
                    <div class="opacity-80 mt-1">Generated: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <!-- Sub Header -->
            <div class="bg-amber-50 border-b border-amber-200 px-6 py-3 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    <div>
                        <span class="text-amber-700">Agent:</span> <span class="font-bold text-gray-900"
                            id="lblAgentName">Agent Name (Code)</span>
                    </div>
                    <div>
                        <span class="text-amber-700">Routes:</span> <span class="font-bold text-gray-900"
                            id="lblRoutes">Routes List</span>
                    </div>
                    <div>
                        <span class="text-amber-700">Monthly Target:</span> <span class="font-bold text-gray-900"
                            id="lblMonthlyTarget">Rs. 0</span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- 2 Main Metric Cards Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Monthly Sales -->
                    <div class="metric-card large-metric-card">
                        <div class="text-xs uppercase text-gray-500 font-bold tracking-wider mb-1">Monthly Sales</div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl font-black text-amber-900" id="valMonthlySales">Rs. 0</span>
                            <span class="text-lg font-bold" id="valPercentTarget">0%</span>
                        </div>
                        <div class="text-sm text-gray-600 mt-2" id="valMomSalesDesc">vs June: Rs. 0 (+0%)</div>
                    </div>

                    <!-- Remaining to Target -->
                    <div class="metric-card large-metric-card flex flex-col justify-between text-right">
                        <div class="text-xs uppercase text-gray-500 font-bold tracking-wider mb-1">Remaining to Target</div>
                        <div class="text-3xl font-black text-rose-600" id="valRemainingTarget">Rs. 0</div>
                        <div class="text-sm text-gray-500 mt-2" id="valTargetRemark">Target missed by 0% — discuss in
                            meeting</div>
                    </div>
                </div>

                <!-- Middle Section: Week-by-Week & MoM -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-6">
                    <!-- Week-by-Week Breakdown -->
                    <div>
                        <h3 class="section-title">Week-By-Week Breakdown</h3>
                        <table class="w-full table-simple border border-gray-200">
                            <thead>
                                <tr>
                                    <th>Week</th>
                                    <th class="text-right">Sales (Rs.)</th>
                                    <th class="text-right">Visit Compliance</th>
                                    <th class="text-right">Return %</th>
                                </tr>
                            </thead>
                            <tbody id="tblWeekByWeek">
                                <!-- JS Injected -->
                            </tbody>
                        </table>
                        <div class="text-xs text-gray-500 italic mt-2">Pattern: compliance and sales dipped mid-month —
                            review route discipline.</div>
                    </div>

                    <!-- Right Column: MoM and Policy Returns -->
                    <div class="flex flex-col gap-6">
                        <!-- Month-Over-Month -->
                        <div>
                            <h3 class="section-title">Month-Over-Month</h3>
                            <table class="w-full table-simple border border-gray-200">
                                <thead>
                                    <tr>
                                        <th>Measure</th>
                                        <th class="text-right" id="thPrevMonth">June</th>
                                        <th class="text-right" id="thCurrMonth">July</th>
                                        <th class="text-center">Trend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-medium">Sales (Rs.)</td>
                                        <td class="text-right" id="momPrevSales">0</td>
                                        <td class="text-right" id="momCurrSales">0</td>
                                        <td class="text-center font-bold" id="momSalesTrend">-</td>
                                    </tr>
                                    <tr>
                                        <td class="font-medium">Return %</td>
                                        <td class="text-right" id="momPrevReturn">0%</td>
                                        <td class="text-right" id="momCurrReturn">0%</td>
                                        <td class="text-center font-bold" id="momReturnTrend">-</td>
                                    </tr>
                                    <tr>
                                        <td class="font-medium">Collection %</td>
                                        <td class="text-right" id="momPrevCollection">0%</td>
                                        <td class="text-right" id="momCurrCollection">0%</td>
                                        <td class="text-center font-bold" id="momCollectionTrend">-</td>
                                    </tr>
                                    <tr>
                                        <td class="font-medium">New shops</td>
                                        <td class="text-right" id="momPrevNewShops">0</td>
                                        <td class="text-right" id="momCurrNewShops">0</td>
                                        <td class="text-center font-bold" id="momNewShopsTrend">-</td>
                                    </tr>
                                    <tr>
                                        <td class="font-medium">Dormant shops</td>
                                        <td class="text-right" id="momPrevDormant">0</td>
                                        <td class="text-right" id="momCurrDormant">0</td>
                                        <td class="text-center font-bold" id="momDormantTrend">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Policy-Violation Returns -->
                        <div>
                            <h3 class="section-title">Policy-Violation Returns (<span id="lblPolicyMonth">July</span>)</h3>
                            <table class="w-full table-simple border border-gray-200 bg-rose-50/30">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Outlet</th>
                                        <th class="text-right">Value (Rs.)</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody id="tblPolicyReturns">
                                    <!-- JS Injected -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Commission Section -->
                <div class="metric-card mb-6">
                    <h3 class="section-title">Commission — <span id="lblCommissionMonth">July 2026</span></h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Side -->
                        <div class="space-y-3 text-sm">
                            <div><span class="text-gray-600">Invoiced value:</span> <strong id="valComInvoiced">Rs.
                                    0</strong> &mdash; <span class="text-gray-600">Returns:</span> <strong
                                    id="valComReturns">Rs. 0</strong></div>
                            <div><span class="text-gray-600">Net sales:</span> <strong class="text-gray-900 text-base"
                                    id="valComNetSales">Rs. 0</strong></div>
                            <div><span class="text-gray-600">Invoicing commission (<span id="valComInvRate">0</span>% x
                                    net):</span> <strong class="text-gray-900" id="valComInvAmt">Rs. 0</strong></div>
                            <div><span class="text-gray-600">Target bonus:</span> <strong id="valComBonusAmt"
                                    class="text-rose-600">Rs. 0 — not qualified</strong></div>
                            <div class="pt-2 border-t border-gray-100"><span class="text-gray-600 font-bold">Total
                                    payable:</span> <strong class="text-amber-700 text-lg" id="valComTotal">Rs. 0</strong>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div>
                            <table class="w-full table-simple border border-amber-100 bg-amber-50/50">
                                <thead>
                                    <tr>
                                        <th class="text-amber-800">Achievement Level</th>
                                        <th class="text-right text-amber-800">%</th>
                                        <th class="text-center text-amber-800">100%?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-medium">Total sales</td>
                                        <td class="text-right" id="valAchieveTotalPct">0%</td>
                                        <td class="text-center font-bold" id="valAchieveTotalIcon">✖</td>
                                    </tr>
                                    <tr>
                                        <td class="font-medium">Category sales</td>
                                        <td class="text-right text-gray-400">N/A</td>
                                        <td class="text-center font-bold text-gray-400">-</td>
                                    </tr>
                                    <tr>
                                        <td class="font-medium">SKU sales</td>
                                        <td class="text-right text-gray-400">N/A</td>
                                        <td class="text-center font-bold text-gray-400">-</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-xs text-gray-500 mt-2">
                                Bonus is all-or-nothing: 100% required at every level. Missing target by <strong
                                    class="text-gray-800" id="valMissTargetAmt">Rs. 0</strong> cost the full bonus.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Sections -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-2">
                    <!-- Credit Position -->
                    <div class="metric-card">
                        <h3 class="section-title">Credit Position (This Agent)</h3>
                        <div class="space-y-3 text-sm">
                            <div><span class="text-gray-600">Closing dues:</span> <strong class="text-gray-900"
                                    id="valCredClosing">Rs. 0</strong> <span class="text-gray-400 mx-2">|</span> <span
                                    class="text-gray-600">Utilization:</span> <strong class="text-amber-600"
                                    id="valCredUtil">0%</strong></div>
                            <div class="pt-2">
                                <span class="text-gray-600">Ageing — Current:</span> <strong class="text-gray-900"
                                    id="valCredCur">Rs. 0</strong>
                                <span class="text-gray-400 mx-1">•</span> <span class="text-gray-600">1-30d:</span> <strong
                                    class="text-gray-900" id="valCred30">Rs. 0</strong>
                            </div>
                            <div>
                                <span class="text-gray-600">31-60d:</span> <strong class="text-amber-600" id="valCred60">Rs.
                                    0</strong>
                                <span class="text-gray-400 mx-1">•</span> <span class="text-gray-600">60d+:</span> <strong
                                    class="text-rose-600" id="valCred60Plus">Rs. 0</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Next Target -->
                    <div class="metric-card flex flex-col justify-between">
                        <h3 class="section-title"><span id="lblNextMonth">Next Month</span> Target (entered by management —
                            sign-off in meeting)</h3>
                        <div class="space-y-4 mt-2">
                            <div class="text-sm"><span class="text-gray-600">Proposed target:</span> <strong
                                    class="text-gray-900">________________________</strong></div>
                            <div class="text-sm flex justify-between">
                                <div><span class="text-gray-600">Agent signature:</span> ___________________</div>
                                <div><span class="text-gray-600">Director signature:</span> ___________________</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 border-t border-gray-100 text-xs text-gray-400 p-3 text-right">
                Bakery Mate v2.0.1
            </div>
        </div>
    </div>

    <script>
        const formatMoney = (amount) => Number(amount).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

        document.getElementById('reportFilterForm').addEventListener('submit', function (e) {
            e.preventDefault();
            loadReportData();
        });

        // Set default month to current month on load
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            document.getElementById('month').value = `${year}-${month}`;
        });

        function loadReportData() {
            const formData = new FormData(document.getElementById('reportFilterForm'));
            const selectedMonthStr = document.getElementById('month').value; // YYYY-MM

            document.getElementById('dashboardContainer').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.remove('hidden');

            $.ajax({
                url: "{{ route('reports.monthlyAgentReview.data') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    document.getElementById('loadingIndicator').classList.add('hidden');

                    if (response.success) {
                        populateDashboard(response, selectedMonthStr);
                        document.getElementById('dashboardContainer').classList.remove('hidden');
                    }
                },
                error: function (xhr) {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    let msg = 'Failed to load report data.';
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    Swal.fire('Error', msg, 'error');
                }
            });
        }

        function populateDashboard(res, selectedMonthStr) {
            const selectedDate = new Date(selectedMonthStr + '-01');
            const monthName = selectedDate.toLocaleString('default', { month: 'long' });
            const year = selectedDate.getFullYear();

            let nextDate = new Date(selectedDate);
            nextDate.setMonth(nextDate.getMonth() + 1);
            const nextMonthName = nextDate.toLocaleString('default', { month: 'long' });

            // Header Info
            document.getElementById('lblMonthYear').innerText = `${monthName} ${year}`;
            document.getElementById('lblAgentName').innerText = `${res.agent.name} (${res.agent.code})`;
            document.getElementById('lblRoutes').innerText = res.agent.routes;
            document.getElementById('lblMonthlyTarget').innerText = `Rs. ${formatMoney(res.monthly_target)}`;

            // Top Cards
            document.getElementById('valMonthlySales').innerText = `Rs. ${formatMoney(res.sales.current)}`;
            const pctColor = res.sales.percent_target >= 100 ? 'text-emerald-600' : 'text-emerald-500';
            document.getElementById('valPercentTarget').innerHTML = `<span class="${pctColor}">${res.sales.percent_target}% ${res.sales.percent_target >= 100 ? '▲' : '▲'}</span>`;

            const growthSign = res.sales.growth >= 0 ? '+' : '';
            document.getElementById('valMomSalesDesc').innerText = `vs ${res.mom.prev_month_label}: Rs. ${formatMoney(res.sales.prev)} (${growthSign}${res.sales.growth}%)`;

            document.getElementById('valRemainingTarget').innerText = `Rs. ${formatMoney(res.sales.remaining)}`;
            if (res.sales.remaining > 0) {
                document.getElementById('valTargetRemark').innerText = `Target missed by ${100 - res.sales.percent_target}% — discuss in meeting`;
            } else {
                document.getElementById('valTargetRemark').innerText = `Target achieved!`;
                document.getElementById('valTargetRemark').className = "text-sm text-emerald-600 mt-2 font-medium";
                document.getElementById('valRemainingTarget').className = "text-3xl font-black text-emerald-600";
            }

            // Week-by-Week Table
            const tblWbw = document.getElementById('tblWeekByWeek');
            tblWbw.innerHTML = '';
            res.week_by_week.forEach(w => {
                const isPoorCompliance = w.visit_compliance < 90;
                const isHighReturn = w.return_percent > 5;
                tblWbw.innerHTML += `
                                        <tr>
                                            <td>${w.label}</td>
                                            <td class="text-right font-medium">${formatMoney(w.sales)}</td>
                                            <td class="text-right ${isPoorCompliance ? 'text-amber-600 font-bold' : ''}">${w.visit_compliance}%</td>
                                            <td class="text-right ${isHighReturn ? 'text-rose-600 font-bold' : ''}">${w.return_percent}%</td>
                                        </tr>
                                    `;
            });

            // MoM Table
            document.getElementById('thPrevMonth').innerText = res.mom.prev_month_label;
            document.getElementById('thCurrMonth').innerText = res.mom.curr_month_label;

            document.getElementById('momPrevSales').innerText = formatMoney(res.sales.prev);
            document.getElementById('momCurrSales').innerText = formatMoney(res.sales.current);
            document.getElementById('momSalesTrend').innerHTML = getTrendHtml(res.mom.sales_trend, true, '%');

            document.getElementById('momPrevReturn').innerText = `${res.mom.return_prev}%`;
            document.getElementById('momCurrReturn').innerText = `${res.mom.return_curr}%`;
            document.getElementById('momReturnTrend').innerHTML = getTrendHtml(res.mom.return_prev - res.mom.return_curr, true, '%', true); // positive is improving

            document.getElementById('momPrevCollection').innerText = `${res.mom.collection_prev}%`;
            document.getElementById('momCurrCollection').innerText = `${res.mom.collection_curr}%`;
            document.getElementById('momCollectionTrend').innerHTML = getTrendHtml(res.mom.collection_curr - res.mom.collection_prev, true, ' pts');

            document.getElementById('momPrevNewShops').innerText = res.mom.new_shops_prev;
            document.getElementById('momCurrNewShops').innerText = res.mom.new_shops_curr;
            document.getElementById('momNewShopsTrend').innerHTML = getTrendHtml(res.mom.new_shops_curr - res.mom.new_shops_prev, false, '', false, true);

            document.getElementById('momPrevDormant').innerText = res.mom.dormant_prev;
            document.getElementById('momCurrDormant').innerText = res.mom.dormant_curr;
            document.getElementById('momDormantTrend').innerHTML = getTrendHtml(res.mom.dormant_curr - res.mom.dormant_prev, false, '', false, false, true);

            // Policy Returns
            document.getElementById('lblPolicyMonth').innerText = res.mom.curr_month_label;
            const tblPol = document.getElementById('tblPolicyReturns');
            tblPol.innerHTML = '';
            res.policy_returns.forEach(pr => {
                tblPol.innerHTML += `
                                        <tr>
                                            <td class="text-gray-600">${pr.date}</td>
                                            <td class="font-medium">${pr.outlet}</td>
                                            <td class="text-right text-rose-600 font-bold">${formatMoney(pr.value)}</td>
                                            <td class="text-gray-600">${pr.remark}</td>
                                        </tr>
                                    `;
            });
            if (res.policy_returns.length === 0) {
                tblPol.innerHTML = `<tr><td colspan="4" class="text-center text-gray-500 py-3">No policy-violation returns</td></tr>`;
            }

            // Commission
            document.getElementById('lblCommissionMonth').innerText = `${monthName} ${year}`;
            document.getElementById('valComInvoiced').innerText = `Rs. ${formatMoney(res.commission.invoiced_value)}`;
            document.getElementById('valComReturns').innerText = `Rs. ${formatMoney(res.commission.returns)}`;
            document.getElementById('valComNetSales').innerText = `Rs. ${formatMoney(res.commission.net_sales)}`;
            document.getElementById('valComInvRate').innerText = res.commission.invoicing_rate;
            document.getElementById('valComInvAmt').innerText = `Rs. ${formatMoney(res.commission.invoicing_commission)}`;

            if (res.commission.target_bonus > 0) {
                document.getElementById('valComBonusAmt').innerText = `Rs. ${formatMoney(res.commission.target_bonus)}`;
                document.getElementById('valComBonusAmt').className = "text-emerald-600";
            } else {
                document.getElementById('valComBonusAmt').innerText = `Rs. 0 — not qualified`;
                document.getElementById('valComBonusAmt').className = "text-rose-600";
            }
            document.getElementById('valComTotal').innerText = `Rs. ${formatMoney(res.commission.total_payable)}`;

            document.getElementById('valAchieveTotalPct').innerText = `${res.sales.percent_target}%`;
            document.getElementById('valAchieveTotalIcon').innerHTML = res.sales.percent_target >= 100 ? '<span class="text-emerald-600">✔</span>' : '<span class="text-rose-600">✖</span>';
            document.getElementById('valMissTargetAmt').innerText = `Rs. ${formatMoney(res.sales.remaining)}`;

            // Credit
            document.getElementById('valCredClosing').innerText = `Rs. ${formatMoney(res.credit.closing_dues)}`;
            document.getElementById('valCredUtil').innerText = `${res.credit.utilization}%`;
            document.getElementById('valCredCur').innerText = `Rs. ${formatMoney(res.credit.current)}`;
            document.getElementById('valCred30').innerText = `Rs. ${formatMoney(res.credit.days_1_30)}`;
            document.getElementById('valCred60').innerText = `Rs. ${formatMoney(res.credit.days_31_60)}`;
            document.getElementById('valCred60Plus').innerText = `Rs. ${formatMoney(res.credit.days_60_plus)}`;

            document.getElementById('lblNextMonth').innerText = nextMonthName;
        }

        // Helper for Trend arrows
        function getTrendHtml(val, showPlus, suffix, customImprove = false, noNumber = false, inverseGood = false) {
            let num = Number(val);
            if (num === 0) return `<span class="text-gray-400">-</span>`;

            // Logic for dormant shops: negative is good, positive is bad. Inverse good means positive is bad.
            let isGood = num > 0;
            if (inverseGood) isGood = num < 0;
            if (customImprove) isGood = num > 0; // custom string improving

            const color = isGood ? 'text-emerald-600' : 'text-rose-600';
            const arrow = num > 0 ? '▲' : '▼';
            const sign = (num > 0 && showPlus) ? '+' : '';

            let displayVal = noNumber ? '' : `${sign}${Math.abs(num)}${suffix}`;
            if (customImprove && num > 0) displayVal = ' improving';
            if (inverseGood && num > 0 && noNumber) displayVal = ' watch';

            return `<span class="${color}">${arrow} ${displayVal}</span>`;
        }

        function exportToPDF() {
            const agentId = document.getElementById('agent_id').value;
            const month = document.getElementById('month').value;

            if (!agentId || !month) {
                Swal.fire('Warning', 'Please select an agent and month first.', 'warning');
                return;
            }

            const url = `{{ route('reports.monthlyAgentReview.exportPdf') }}?agent_id=${agentId}&month=${month}`;
            window.open(url, '_blank');
        }
    </script>
@endsection