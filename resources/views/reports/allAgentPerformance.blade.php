@extends('layouts.app')

@section('content')

    <!-- Flatpickr & MonthSelect Plugin -->
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/plugins/monthSelect/style.css') }}">
    <script src="{{ asset('vendor/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendor/flatpickr/plugins/monthSelect/index.js') }}"></script>

    <style>
        .report-header {
            background-color: #8c4314; /* Deep warm bakery brown from mockup */
            color: white;
        }

        .metric-card {
            border: 1px solid #fed7aa; /* Soft warm amber border */
            border-radius: 0.5rem;
            padding: 1.25rem;
            background: #ffffff;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.02);
        }

        .table-simple th {
            font-size: 0.72rem;
            background-color: #fff8ed; /* Warm cream/peach matching mockup */
            color: #9a3412; /* Brown amber 800 */
            padding: 0.65rem 0.75rem;
            text-transform: uppercase;
            font-weight: 800;
            border-bottom: 2px solid #fed7aa; /* Warm amber border */
            border-right: 1px solid #ffedd5;
            text-align: left;
            vertical-align: bottom;
            letter-spacing: 0.02em;
        }

        .table-simple th.text-right,
        .table-simple td.text-right {
            text-align: right !important;
        }

        .table-simple th.text-center,
        .table-simple td.text-center {
            text-align: center !important;
        }

        .table-simple th.text-left,
        .table-simple td.text-left {
            text-align: left !important;
        }

        .table-simple td {
            font-size: 0.82rem;
            padding: 0.65rem 0.75rem;
            border-bottom: 1px solid #f8fafc;
            border-right: 1px solid #f8fafc;
            color: #1e293b;
            vertical-align: middle;
        }

        .table-simple tr:last-child td {
            border-bottom: none;
        }

        .section-title {
            color: #9a3412; /* Warm bakery brown matching mockup */
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.82rem;
            margin-bottom: 0.75rem;
            letter-spacing: 0.02em;
        }

        .badge-ramp {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0.05rem 0.4rem;
            border-radius: 0.25rem;
            display: inline-block;
        }

        /* Direct explicit color classes that bypass table-simple specificity */
        .color-track,
        .table-simple td .color-track,
        .table-simple td.color-track {
            color: #16a34a !important; /* Green On-Track */
            font-weight: 700 !important;
        }

        .color-warning,
        .table-simple td .color-warning,
        .table-simple td.color-warning {
            color: #d97706 !important; /* Amber Warning */
            font-weight: 700 !important;
        }

        .color-violation,
        .table-simple td .color-violation,
        .table-simple td.color-violation {
            color: #dc2626 !important; /* Red Rule Violation */
            font-weight: 700 !important;
        }

        .row-violation,
        .table-simple tr.row-violation,
        .table-simple tr.row-violation td {
            background-color: #fff1f2 !important; /* Soft rose background */
        }

        .row-track-highlight,
        .table-simple tr.row-track-highlight,
        .table-simple tr.row-track-highlight td {
            background-color: #f0fdf4 !important; /* Soft green background */
        }

        /* Bakery-themed Flatpickr Month Picker Calendar */
        .flatpickr-calendar {
            background: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #fed7aa;
            padding: 0.5rem;
            font-family: inherit;
        }

        .flatpickr-months {
            border-bottom: 1px solid #ffedd5;
            padding-bottom: 0.25rem;
        }

        .flatpickr-current-month {
            font-size: 0.95rem;
            font-weight: 700;
            color: #9a3412;
            padding-top: 4px;
        }

        .flatpickr-current-month .cur-year {
            font-weight: 700;
            color: #9a3412;
        }

        .flatpickr-monthSelect-month {
            border-radius: 0.5rem;
            padding: 0.65rem 0.4rem;
            font-weight: 600;
            font-size: 0.82rem;
            color: #374151;
            transition: all 0.15s ease;
        }

        .flatpickr-monthSelect-month:hover {
            background: #fff8ed;
            color: #9a3412;
            border-color: #fed7aa;
        }

        .flatpickr-monthSelect-month.selected {
            background: #8c4314 !important;
            border-color: #8c4314 !important;
            color: #ffffff !important;
            font-weight: 700;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">All Agent Performance Report</h2>
                <p class="text-sm text-gray-500 mt-1">View monthly performance leaderboard, outlet movement, return trends, and credit ageing for all agents.</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <form id="reportFilterForm" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                @csrf
                <div>
                    <label for="month" class="block text-sm font-semibold text-gray-700 mb-1">Select Month</label>
                    <div class="relative rounded-lg shadow-sm">
                        <input type="text" id="month" name="month" required readonly
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2.5 pr-10 cursor-pointer bg-white"
                            placeholder="Select month...">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-amber-700 hover:text-amber-800 transition-colors z-10"
                             onclick="if(window.fpMonthPicker) window.fpMonthPicker.open();"
                             title="Click to open calendar">
                            <i class="bi bi-calendar3 text-lg"></i>
                        </div>
                    </div>
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
            <p class="mt-2 text-sm text-gray-500 font-medium">Generating performance report...</p>
        </div>

        <!-- Dashboard Container -->
        <div id="dashboardContainer"
            class="hidden max-w-full mx-auto bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="report-header p-6 flex justify-between items-end">
                <div>
                    <div class="text-xs uppercase tracking-widest font-semibold mb-1 opacity-85">DIMUTHU BAKE HOUSE &mdash; KOTTAWA BRANCH</div>
                    <h1 class="text-3xl font-bold tracking-tight text-white">All Agent Performance Report</h1>
                </div>
                <div class="text-right text-sm">
                    <div class="font-bold text-lg text-white" id="lblMonthYear">July 2026</div>
                    <div class="opacity-85 mt-1 text-white">Generated: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <!-- Leaderboard -->
            <div class="p-0 border-b border-gray-200">
                <div class="px-6 py-4 bg-white border-b border-amber-100 flex items-center justify-between">
                    <h2 class="section-title mb-0 flex-1">AGENT LEADERBOARD <span
                            class="text-gray-400 font-normal normal-case ml-2">(sorted by achievement %)</span></h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-simple" style="min-width: 900px;">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>Agent</th>
                                <th class="text-right">Sales<br>(Rs.)</th>
                                <th class="text-right">Achievement<br>%</th>
                                <th class="text-right">Remaining<br>(Rs.)</th>
                                <th class="text-right">Visit<br>Compliance</th>
                                <th class="text-right">Return<br>%</th>
                                <th class="text-right">Collection<br>%</th>
                                <th class="text-center">New<br>Shops</th>
                                <th class="text-right">Credit<br>Util.</th>
                                <th class="text-center w-28">5% Bonus</th>
                            </tr>
                        </thead>
                        <tbody id="tblLeaderboard">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>
                <div class="text-xs text-gray-500 px-6 py-3 bg-gray-50 border-t border-gray-100">
                    M1 / M2 badges mark agents in ramp-up months (targets set manually by management). 5% bonus requires
                    100% achievement at all three levels &mdash; total, category, and SKU &mdash; calculated on net sales.
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Return % Trend -->
                    <div>
                        <h3 class="section-title">RETURN % TREND <span
                                class="text-gray-400 font-normal normal-case text-xs ml-1">(3 months, team)</span></h3>
                        <table class="w-full table-simple border border-amber-200">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th class="text-right">Listed Reasons</th>
                                    <th class="text-right">Other (specified)</th>
                                    <th class="text-right font-black">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tblReturnTrend">
                                <!-- JS Injected -->
                            </tbody>
                        </table>
                        <div id="returnTrendNote" class="text-xs text-gray-600 mt-2">Trend improving &mdash; reason codes now show where returns come from.</div>
                    </div>

                    <!-- 1. AGENT -> BAKERY CREDIT AGEING -->
                    <div>
                        <h3 class="section-title">1. AGENT &rarr; BAKERY CREDIT AGEING <span
                                class="text-gray-400 font-normal normal-case text-xs ml-1">(all agents, Rs.)</span></h3>
                        <table class="w-full table-simple border border-amber-200">
                            <thead>
                                <tr>
                                    <th>Bucket</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-right">% of Dues</th>
                                </tr>
                            </thead>
                            <tbody id="tblCreditAgeing">
                                <!-- JS Injected -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. Agent -> Retail Outlet Credit Summary -->
                <div class="mb-8">
                    <h3 class="section-title">2. AGENT &rarr; RETAIL OUTLET CREDIT SUMMARY <span
                            class="text-gray-400 font-normal normal-case text-xs ml-1">(per agent, Rs.)</span></h3>
                    <table class="w-full table-simple border border-amber-200">
                        <thead>
                            <tr>
                                <th>Agent</th>
                                <th class="text-right">Credit Issued</th>
                                <th class="text-right">Collected</th>
                                <th class="text-right">Outstanding</th>
                                <th class="text-right">Collection %</th>
                            </tr>
                        </thead>
                        <tbody id="tblAgentCreditSummary">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>

                <!-- 3. Top 5 Highest Credit Balance Retail Shops -->
                <div class="mb-8">
                    <h3 class="section-title">3. TOP 5 HIGHEST CREDIT BALANCE RETAIL SHOPS <span
                            class="text-gray-400 font-normal normal-case text-xs ml-1">(branch-wide, across all agents)</span></h3>
                    <table class="w-full table-simple border border-amber-200">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>Outlet</th>
                                <th>Agent</th>
                                <th class="text-right">Balance (Rs.)</th>
                                <th class="text-right">Days Outstanding</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tblTopCreditShops">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>

                <!-- SALES GROWTH Section -->
                <div class="mb-4 mt-6 pt-4 border-t-2 border-amber-200">
                    <h2 class="section-title text-base mb-1">SALES GROWTH <span class="text-gray-400 font-normal normal-case text-xs ml-1">(branch-wide, all agents)</span></h2>
                </div>

                <!-- 1. Outlet Growth -->
                <div class="mb-8">
                    <h3 class="section-title">1. OUTLET GROWTH (<span id="lblOutletMonth">JULY</span>)</h3>
                    <div class="metric-card">
                        <div class="text-sm">
                            <span class="text-gray-600">Opening active outlets:</span> <strong class="text-gray-900 text-base font-bold" id="valOutOpen">0</strong>
                            <span class="text-gray-400 mx-2">&bull;</span>
                            <span class="text-gray-600">New shops:</span> <strong class="color-track" id="valOutNew">+0</strong>
                            <span class="text-gray-400 mx-2">&bull;</span>
                            <span class="text-gray-600">Newly dormant:</span> <strong class="color-violation" id="valOutDormant">0</strong>
                            <span class="text-gray-400 mx-3">&rarr;</span>
                            <span class="text-gray-600">Closing active outlets:</span>
                            <strong class="text-gray-900 text-base font-bold" id="valOutClose">0</strong>
                            <span class="color-track" id="valOutGrowth">(+0%)</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Top 5 Highest Sale Retail Shops -->
                <div class="mb-8">
                    <h3 class="section-title">2. TOP 5 HIGHEST SALE RETAIL SHOPS <span
                            class="text-gray-400 font-normal normal-case text-xs ml-1" id="lblTopSaleMonth">(July)</span></h3>
                    <table class="w-full table-simple border border-amber-200">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>Outlet</th>
                                <th>Agent</th>
                                <th class="text-right">Sales (Rs.)</th>
                                <th class="text-right" id="thVsLastMonth">vs June</th>
                            </tr>
                        </thead>
                        <tbody id="tblTopSaleShops">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>

                <!-- 3. Top 5 Fast Moving SKUs -->
                <div class="mb-8">
                    <h3 class="section-title">3. TOP 5 FAST MOVING SKUs <span
                            class="text-gray-400 font-normal normal-case text-xs ml-1" id="lblSkuMonth">(ranked by units sold in July)</span></h3>
                    <table class="w-full table-simple border border-amber-200">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>SKU</th>
                                <th class="text-right">Units Sold</th>
                                <th class="text-right">Units / Working Day</th>
                                <th class="text-right">Sales (Rs.)</th>
                                <th class="text-right">% of Branch Sales</th>
                            </tr>
                        </thead>
                        <tbody id="tblTopSkus">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>

                <!-- 4. Top 5 Highest Sales Growth Retail Shops -->
                <div class="mb-8">
                    <h3 class="section-title">4. TOP 5 HIGHEST SALES GROWTH RETAIL SHOPS <span
                            class="text-gray-400 font-normal normal-case text-xs ml-1" id="lblGrowthSubtitle">(monthly view &mdash; July vs June; ranked by growth in Rs.)</span></h3>
                    <table class="w-full table-simple border border-amber-200">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>Outlet</th>
                                <th>Agent</th>
                                <th class="text-right" id="thPrevMonth">June (Rs.)</th>
                                <th class="text-right" id="thCurMonth">July (Rs.)</th>
                                <th class="text-right">Growth (Rs.)</th>
                                <th class="text-right">Growth %</th>
                            </tr>
                        </thead>
                        <tbody id="tblTopGrowthShops">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>

                <!-- Next Month Targets -->
                <div class="mb-4 mt-6 pt-4 border-t-2 border-amber-200">
                    <div class="metric-card">
                        <h3 class="section-title">NEXT MONTH TARGETS (<span id="lblNextMonth">AUG</span>) &mdash; ENTERED BY MANAGEMENT</h3>
                        <div class="mt-2 text-sm text-gray-800 leading-relaxed" id="containerNextTargets">
                            <!-- JS Injected -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer & Color Legend -->
            <div class="bg-gray-50 border-t border-amber-200 text-xs text-gray-500 p-3 flex flex-wrap justify-between items-center gap-2">
                <div class="flex items-center gap-5">
                    <span class="flex items-center color-violation">
                        <span class="w-3 h-3 bg-red-600 inline-block rounded-sm mr-1.5"></span> Rule violation
                    </span>
                    <span class="flex items-center color-warning">
                        <span class="w-3 h-3 bg-amber-500 inline-block rounded-sm mr-1.5"></span> Warning
                    </span>
                    <span class="flex items-center color-track">
                        <span class="w-3 h-3 bg-emerald-600 inline-block rounded-sm mr-1.5"></span> On track
                    </span>
                </div>
                <span class="text-gray-400 font-medium">Dimuthu Bakers v2.0.1 &mdash; Report C &mdash; All Agent Performance (<span id="lblFooterMonth">July 2026</span>)</span>
            </div>
        </div>
    </div>

    <script>
        const formatMoney = (amount) => Number(amount).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

        window.fpMonthPicker = null;

        document.getElementById('reportFilterForm').addEventListener('submit', function (e) {
            e.preventDefault();
            loadReportData();
        });

        // Initialize Month Calendar Picker
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const defaultMonthStr = `${year}-${month}`;

            if (typeof flatpickr !== 'undefined' && typeof monthSelectPlugin !== 'undefined') {
                window.fpMonthPicker = flatpickr("#month", {
                    plugins: [
                        new monthSelectPlugin({
                            shorthand: true,
                            dateFormat: "Y-m",
                            altFormat: "F Y",
                            theme: "light"
                        })
                    ],
                    altInput: true,
                    altInputClass: "block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2.5 pr-10 cursor-pointer bg-white",
                    defaultDate: defaultMonthStr
                });
            } else {
                document.getElementById('month').value = defaultMonthStr;
            }
        });

        function loadReportData() {
            const formData = new FormData(document.getElementById('reportFilterForm'));
            const selectedMonthStr = document.getElementById('month').value;

            document.getElementById('dashboardContainer').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.remove('hidden');

            $.ajax({
                url: "{{ route('reports.allAgentPerformance.data') }}",
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
            const monthShort = selectedDate.toLocaleString('default', { month: 'short' }).toUpperCase();
            const year = selectedDate.getFullYear();

            // Header Info
            const fullLabel = `${monthName} ${year}`;
            document.getElementById('lblMonthYear').innerText = fullLabel;
            document.getElementById('lblFooterMonth').innerText = fullLabel;
            document.getElementById('lblOutletMonth').innerText = monthShort;
            document.getElementById('lblNextMonth').innerText = res.next_targets.label.toUpperCase();

            // 1. Leaderboard
            const tblLb = document.getElementById('tblLeaderboard');
            tblLb.innerHTML = '';
            res.leaderboard.forEach((ag, idx) => {
                // Color Logic as per mockup:
                // >= 80% is Green, 70-79% is Amber, < 70% is Red
                const achieveClass = ag.achievement >= 80 ? 'color-track' : (ag.achievement >= 70 ? 'color-warning' : 'color-violation');

                // Remaining
                let remainingHtml = '';
                if (ag.achievement >= 100) {
                    remainingHtml = `<span class="color-track">Achieved &check;</span>`;
                } else if (ag.achievement < 70) {
                    remainingHtml = `<span class="color-violation">${formatMoney(ag.remaining)}</span>`;
                } else {
                    remainingHtml = `<span class="text-gray-800 font-medium">${formatMoney(ag.remaining)}</span>`;
                }

                // Visit compliance: >=90 green, 75-89 amber, <75 red
                const visitClass = ag.visit_compliance >= 90 ? 'color-track' : (ag.visit_compliance >= 75 ? 'color-warning' : 'color-violation');

                // Return %: <= 5.0 green, > 5.0 red
                const returnClass = ag.return_percent <= 5.0 ? 'color-track' : 'color-violation';

                // Collection %: >=90 green, 80-89 amber, <80 red
                const collClass = ag.collection_percent >= 90 ? 'color-track' : (ag.collection_percent >= 80 ? 'color-warning' : 'color-violation');

                // Credit util: <=60 green, 61-80 amber, >80 red
                const utilClass = ag.credit_util <= 60 ? 'color-track' : (ag.credit_util <= 80 ? 'color-warning' : 'color-violation');

                // 5% Bonus text
                const bonusHtml = ag.bonus_qualified
                    ? `<span class="color-track">&check; Yes</span>`
                    : `<span class="color-violation">&cross; ${ag.achievement}%</span>`;

                const badgeHtml = ag.badge ? `<br><span class="badge-ramp mt-0.5">${ag.badge}</span>` : '';

                tblLb.innerHTML += `
                    <tr class="${idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20'} hover:bg-amber-50/40 transition-colors">
                        <td class="text-center text-gray-500 font-bold">${idx + 1}</td>
                        <td class="font-bold text-gray-900">${ag.agent_name} ${badgeHtml}</td>
                        <td class="text-right text-gray-800 font-medium">${formatMoney(ag.sales)}</td>
                        <td class="text-right"><span class="${achieveClass}">${ag.achievement}%</span></td>
                        <td class="text-right">${remainingHtml}</td>
                        <td class="text-right"><span class="${visitClass}">${ag.visit_compliance}%</span></td>
                        <td class="text-right"><span class="${returnClass}">${ag.return_percent}%</span></td>
                        <td class="text-right"><span class="${collClass}">${ag.collection_percent}%</span></td>
                        <td class="text-center text-gray-800 font-medium">${ag.new_shops}</td>
                        <td class="text-right"><span class="${utilClass}">${ag.credit_util}%</span></td>
                        <td class="text-center">${bonusHtml}</td>
                    </tr>
                `;
            });

            // Return % Trend
            const tblRet = document.getElementById('tblReturnTrend');
            tblRet.innerHTML = '';
            const trendData = [...res.return_trend].reverse();
            trendData.forEach((rt, idx) => {
                const isLast = idx === trendData.length - 1;
                const totColor = isLast ? 'font-black text-gray-900' : 'font-bold text-gray-800';
                const rowClass = isLast ? 'row-track-highlight' : (idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20');

                let arrow = '';
                if (idx > 0) {
                    const prevTot = trendData[idx - 1].total_pct;
                    if (rt.total_pct < prevTot) arrow = ' <span class="color-track">▼</span>';
                    else if (rt.total_pct > prevTot) arrow = ' <span class="color-violation">▲</span>';
                }

                tblRet.innerHTML += `
                    <tr class="${rowClass} hover:bg-amber-50/40 transition-colors">
                        <td class="text-gray-900 font-medium">${rt.month_label}</td>
                        <td class="text-right text-gray-700 font-medium">${rt.defect_pct}%</td>
                        <td class="text-right"><span class="${rt.other_pct > 1.0 ? 'color-violation' : 'text-gray-700 font-medium'}">${rt.other_pct}%</span></td>
                        <td class="text-right ${totColor}">${rt.total_pct}%${arrow}</td>
                    </tr>
                `;
            });

            // 1. Agent -> Bakery Credit Ageing
            const tblCred = document.getElementById('tblCreditAgeing');
            const ca = res.credit_ageing;
            tblCred.innerHTML = `
                <tr class="bg-white hover:bg-amber-50/40 transition-colors">
                    <td class="text-gray-800 font-medium">Within term (0 &ndash; 14 days)</td>
                    <td class="text-right text-gray-800 font-medium">${formatMoney(ca.current.amount)}</td>
                    <td class="text-right text-gray-700">${ca.current.pct}%</td>
                </tr>
                <tr class="bg-amber-50/20 hover:bg-amber-50/40 transition-colors">
                    <td class="text-gray-800 font-medium">15 &ndash; 30 days</td>
                    <td class="text-right text-gray-800 font-medium">${formatMoney(ca.days_30.amount)}</td>
                    <td class="text-right text-gray-700">${ca.days_30.pct}%</td>
                </tr>
                <tr class="bg-white hover:bg-amber-50/40 transition-colors">
                    <td class="text-gray-800 font-medium">31 &ndash; 60 days</td>
                    <td class="text-right text-gray-800 font-medium">${formatMoney(ca.days_60.amount)}</td>
                    <td class="text-right text-gray-700">${ca.days_60.pct}%</td>
                </tr>
                <tr class="row-violation hover:bg-rose-50 transition-colors">
                    <td class="color-violation">60+ days</td>
                    <td class="text-right color-violation">${formatMoney(ca.days_60_plus.amount)}</td>
                    <td class="text-right color-violation">${ca.days_60_plus.pct}%</td>
                </tr>
            `;

            // 2. Agent -> Retail Outlet Credit Summary
            const tblAcs = document.getElementById('tblAgentCreditSummary');
            if (tblAcs) {
                tblAcs.innerHTML = '';
                if (res.agent_credit_summary && res.agent_credit_summary.length > 0) {
                    res.agent_credit_summary.forEach((ac, idx) => {
                        const collClass = ac.collection_pct >= 90 ? 'color-track' : (ac.collection_pct >= 80 ? 'color-warning' : 'color-violation');
                        const outClass = (ac.collection_pct < 80 || ac.outstanding > 200000) ? 'color-violation' : 'text-gray-800 font-medium';

                        tblAcs.innerHTML += `
                            <tr class="${idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20'} hover:bg-amber-50/40 transition-colors">
                                <td class="font-bold text-gray-900">${ac.agent_name}</td>
                                <td class="text-right text-gray-800 font-medium">${formatMoney(ac.credit_issued)}</td>
                                <td class="text-right text-gray-800 font-medium">${formatMoney(ac.collected)}</td>
                                <td class="text-right"><span class="${outClass}">${formatMoney(ac.outstanding)}</span></td>
                                <td class="text-right"><span class="${collClass}">${ac.collection_pct}%</span></td>
                            </tr>
                        `;
                    });
                } else {
                    tblAcs.innerHTML = '<tr><td colspan="5" class="text-center text-gray-400 py-3">No agent credit data available</td></tr>';
                }
            }

            // 3. Top 5 Highest Credit Balance Retail Shops
            const tblTcs = document.getElementById('tblTopCreditShops');
            if (tblTcs) {
                tblTcs.innerHTML = '';
                if (res.top_credit_shops && res.top_credit_shops.length > 0) {
                    res.top_credit_shops.forEach((sh, idx) => {
                        let statusBadge = '';
                        let rowClass = idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20';
                        let balanceClass = 'text-gray-800 font-medium';

                        if (sh.status === 'Overdue') {
                            statusBadge = '<span class="color-violation">&Delta; Overdue</span>';
                            balanceClass = 'color-violation';
                            rowClass = 'row-violation';
                        } else if (sh.status === 'Near limit') {
                            statusBadge = '<span class="color-warning">Near limit</span>';
                            balanceClass = 'text-gray-800 font-medium';
                        } else {
                            statusBadge = '<span class="color-track">Within term</span>';
                            balanceClass = 'text-gray-800 font-medium';
                        }

                        tblTcs.innerHTML += `
                            <tr class="${rowClass} hover:bg-amber-50/40 transition-colors">
                                <td class="text-center text-gray-500 font-bold">${idx + 1}</td>
                                <td class="font-bold text-gray-900">${sh.outlet_name}</td>
                                <td class="text-gray-800">${sh.agent_name}</td>
                                <td class="text-right"><span class="${balanceClass}">${formatMoney(sh.balance)}</span></td>
                                <td class="text-right text-gray-700 font-medium">${Math.round(Math.abs(Number(sh.days_outstanding) || 0))}</td>
                                <td class="text-center">${statusBadge}</td>
                            </tr>
                        `;
                    });
                } else {
                    tblTcs.innerHTML = '<tr><td colspan="6" class="text-center text-gray-400 py-3">No overdue credit balance found</td></tr>';
                }
            }

            // 1. Outlet Growth (Outlet Movement)
            const om = res.outlet_movement;
            document.getElementById('valOutOpen').innerText = om.opening;
            document.getElementById('valOutNew').innerText = `+${om.new_shops}`;
            document.getElementById('valOutDormant').innerText = `-${om.newly_dormant}`;
            document.getElementById('valOutClose').innerText = om.closing;

            const gSign = om.growth_pct >= 0 ? '+' : '';
            const gArrow = om.growth_pct >= 0 ? '▲' : '▼';
            const gColorClass = om.growth_pct >= 0 ? 'color-track' : 'color-violation';
            document.getElementById('valOutGrowth').className = gColorClass;
            document.getElementById('valOutGrowth').innerText = `(${gSign}${Math.abs(om.growth_pct)}% ${gArrow})`;

            // 2. Top 5 Highest Sale Retail Shops
            if (document.getElementById('lblTopSaleMonth')) {
                document.getElementById('lblTopSaleMonth').innerText = `(${monthName})`;
            }
            if (document.getElementById('thVsLastMonth')) {
                document.getElementById('thVsLastMonth').innerText = `vs ${res.prev_month_label || 'June'}`;
            }
            const tblTss = document.getElementById('tblTopSaleShops');
            if (tblTss) {
                tblTss.innerHTML = '';
                if (res.top_sale_shops && res.top_sale_shops.length > 0) {
                    res.top_sale_shops.forEach((sh, idx) => {
                        const vsSign = sh.vs_last_month >= 0 ? '+' : '';
                        const vsArrow = sh.vs_last_month >= 0 ? '▲' : '▼';
                        const vsClass = sh.vs_last_month >= 0 ? 'color-track' : 'color-violation';

                        tblTss.innerHTML += `
                            <tr class="${idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20'} hover:bg-amber-50/40 transition-colors">
                                <td class="text-center text-gray-500 font-bold">${idx + 1}</td>
                                <td class="font-bold text-gray-900">${sh.outlet_name}</td>
                                <td class="text-gray-800">${sh.agent_name}</td>
                                <td class="text-right font-bold text-gray-900">${formatMoney(sh.sales)}</td>
                                <td class="text-right"><span class="${vsClass}">${vsArrow} ${vsSign}${sh.vs_last_month}%</span></td>
                            </tr>
                        `;
                    });
                } else {
                    tblTss.innerHTML = '<tr><td colspan="5" class="text-center text-gray-400 py-3">No sales records found</td></tr>';
                }
            }

            // 3. Top 5 Fast Moving SKUs
            if (document.getElementById('lblSkuMonth')) {
                document.getElementById('lblSkuMonth').innerText = `(ranked by units sold in ${monthName})`;
            }
            const tblSkus = document.getElementById('tblTopSkus');
            if (tblSkus) {
                tblSkus.innerHTML = '';
                if (res.top_skus && res.top_skus.length > 0) {
                    res.top_skus.forEach((sku, idx) => {
                        const isTop = idx === 0;
                        tblSkus.innerHTML += `
                            <tr class="${idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20'} hover:bg-amber-50/40 transition-colors">
                                <td class="text-center text-gray-500 font-bold">${idx + 1}</td>
                                <td class="font-bold text-gray-900">${sku.sku_name}</td>
                                <td class="text-right font-bold ${isTop ? 'text-gray-900' : 'text-gray-800'}">${formatMoney(sku.units_sold)}</td>
                                <td class="text-right text-gray-700">${formatMoney(sku.units_per_day)}</td>
                                <td class="text-right text-gray-800 font-medium">${formatMoney(sku.sales)}</td>
                                <td class="text-right font-bold text-gray-900">${sku.branch_pct}%</td>
                            </tr>
                        `;
                    });
                } else {
                    tblSkus.innerHTML = '<tr><td colspan="6" class="text-center text-gray-400 py-3">No SKU sales found</td></tr>';
                }
            }

            // 4. Top 5 Highest Sales Growth Retail Shops
            if (document.getElementById('lblGrowthSubtitle')) {
                document.getElementById('lblGrowthSubtitle').innerText = `(monthly view \u2014 ${res.current_month_label || monthName} vs ${res.prev_month_label || 'June'}; ranked by growth in Rs.)`;
            }
            if (document.getElementById('thPrevMonth')) {
                document.getElementById('thPrevMonth').innerText = `${res.prev_month_label || 'June'} (Rs.)`;
            }
            if (document.getElementById('thCurMonth')) {
                document.getElementById('thCurMonth').innerText = `${res.current_month_label || monthShort} (Rs.)`;
            }
            const tblTgs = document.getElementById('tblTopGrowthShops');
            if (tblTgs) {
                tblTgs.innerHTML = '';
                if (res.top_growth_shops && res.top_growth_shops.length > 0) {
                    res.top_growth_shops.forEach((sh, idx) => {
                        const sign = sh.growth_rs >= 0 ? '+' : '';
                        const pctSign = sh.growth_pct >= 0 ? '+' : '';
                        const colorClass = sh.growth_rs >= 0 ? 'color-track' : 'color-violation';

                        tblTgs.innerHTML += `
                            <tr class="${idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20'} hover:bg-amber-50/40 transition-colors">
                                <td class="text-center text-gray-500 font-bold">${idx + 1}</td>
                                <td class="font-bold text-gray-900">${sh.outlet_name}</td>
                                <td class="text-gray-800">${sh.agent_name}</td>
                                <td class="text-right text-gray-700">${formatMoney(sh.prev_sales)}</td>
                                <td class="text-right text-gray-900 font-bold">${formatMoney(sh.current_sales)}</td>
                                <td class="text-right"><span class="${colorClass}">${sign}${formatMoney(sh.growth_rs)}</span></td>
                                <td class="text-right"><span class="${colorClass}">${pctSign}${sh.growth_pct}%</span></td>
                            </tr>
                        `;
                    });
                } else {
                    tblTgs.innerHTML = '<tr><td colspan="7" class="text-center text-gray-400 py-3">No growth records found</td></tr>';
                }
            }

            // Next Targets
            const ntCont = document.getElementById('containerNextTargets');
            let ntItems = [];
            res.next_targets.targets.forEach(t => {
                let label = t.agent_name;
                if (t.badge) label += ` (${t.badge})`;
                let val = t.target === 'Pending' ? 'Pending' : `Rs. ${formatMoney(t.target)}`;
                ntItems.push(`${label}: <strong class="text-gray-900">${val}</strong>`);
            });

            if (ntItems.length > 0) {
                ntCont.innerHTML = ntItems.join(' <span class="text-gray-400 mx-2">&bull;</span> ');
            } else {
                ntCont.innerHTML = '<span class="text-gray-400 italic">No targets set for next month.</span>';
            }
        }

        function exportToPDF() {
            const month = document.getElementById('month').value;

            if (!month) {
                Swal.fire('Warning', 'Please select a month first.', 'warning');
                return;
            }

            const url = `{{ route('reports.allAgentPerformance.exportPdf') }}?month=${month}`;
            window.open(url, '_blank');
        }
    </script>
@endsection
