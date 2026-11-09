@extends('layouts.app')

@section('content')

    <!-- Flatpickr & MonthSelect Plugin -->
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/plugins/monthSelect/style.css') }}">
    <script src="{{ asset('vendor/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendor/flatpickr/plugins/monthSelect/index.js') }}"></script>

    <style>
        .report-header {
            background-color: #8c4314; /* Deep warm bakery brown matching mockup */
            color: white;
        }

        .metric-card {
            border: 1px solid #fed7aa; /* Soft warm amber border */
            border-radius: 0.5rem;
            padding: 1.25rem;
            background: #ffffff;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.02);
        }

        .large-metric-card {
            border: 2px solid #ea580c;
        }

        .table-simple th {
            font-size: 0.72rem;
            background-color: #fff8ed; /* Warm cream/peach matching mockup */
            color: #9a3412; /* Brown amber 800 */
            padding: 0.65rem 0.75rem;
            text-transform: uppercase;
            font-weight: 800;
            border-bottom: 2px solid #fed7aa;
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
            color: #9a3412; /* Warm bakery brown */
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

        /* Color classes */
        .color-track,
        .table-simple td .color-track,
        .table-simple td.color-track {
            color: #16a34a !important; /* Green */
            font-weight: 700 !important;
        }

        .color-warning,
        .table-simple td .color-warning,
        .table-simple td.color-warning {
            color: #d97706 !important; /* Amber */
            font-weight: 700 !important;
        }

        .color-violation,
        .table-simple td .color-violation,
        .table-simple td.color-violation {
            color: #dc2626 !important; /* Red */
            font-weight: 700 !important;
        }

        .row-violation,
        .table-simple tr.row-violation,
        .table-simple tr.row-violation td {
            background-color: #fff1f2 !important; /* Soft rose */
        }

        .row-track-highlight,
        .table-simple tr.row-track-highlight,
        .table-simple tr.row-track-highlight td {
            background-color: #f0fdf4 !important; /* Soft green */
        }

        /* Flatpickr Month Picker */
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
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Monthly Agent Review</h2>
                <p class="text-sm text-gray-500 mt-1">Review monthly sales performance, MoM comparison, commission, credit position, and sales growth by agent.</p>
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
            <p class="mt-2 text-sm text-gray-500 font-medium">Generating monthly review...</p>
        </div>

        <!-- Dashboard Container -->
        <div id="dashboardContainer"
            class="hidden max-w-full mx-auto bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
            <!-- Header Banner Matching Mockup 2 -->
            <div class="report-header p-5 flex justify-between items-end">
                <div>
                    <div class="text-xs uppercase tracking-widest font-bold mb-1 opacity-90">DIMUTHU BAKE HOUSE &mdash; KOTTAWA BRANCH</div>
                    <h1 class="text-2xl font-black tracking-tight">Monthly Agent Review</h1>
                </div>
                <div class="text-right text-xs">
                    <div class="font-bold text-base" id="lblMonthYear">July 2026</div>
                    <div class="opacity-90 mt-0.5">Generated: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <!-- Agent Strip -->
            <div class="bg-amber-50/80 border-b border-amber-200 px-6 py-2.5 text-xs">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    <div>
                        <span class="text-gray-500 font-bold uppercase">Agent:</span> <strong class="text-gray-900" id="lblAgentName">Agent Name (Code)</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 font-bold uppercase">Routes:</span> <strong class="text-gray-900" id="lblRoutes">Routes</strong>
                    </div>
                    <div>
                        <span class="text-gray-500 font-bold uppercase">Monthly Target:</span> <strong class="text-gray-900" id="lblMonthlyTarget">Rs. 0</strong>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Top 2 Main Metric Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Monthly Sales -->
                    <div class="metric-card large-metric-card">
                        <div class="text-xs uppercase text-gray-500 font-bold tracking-wider mb-1">MONTHLY SALES</div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-2xl font-black text-gray-900" id="valMonthlySales">Rs. 0</span>
                            <span class="text-base font-bold" id="valPercentTarget">0%</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-2" id="valMomSalesDesc">vs June: Rs. 0 (+0%)</div>
                    </div>

                    <!-- Remaining to Target -->
                    <div class="metric-card large-metric-card flex flex-col justify-between text-right">
                        <div class="text-xs uppercase text-gray-500 font-bold tracking-wider mb-1">REMAINING TO TARGET</div>
                        <div class="text-2xl font-black text-rose-600" id="valRemainingTarget">Rs. 0</div>
                        <div class="text-xs text-gray-500 mt-2" id="valTargetRemark">Target missed by 0% &mdash; discuss in meeting</div>
                    </div>
                </div>

                <!-- Middle Section: Week-by-Week & MoM / Returns Summary -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-6">
                    <!-- Week-by-Week Breakdown -->
                    <div>
                        <h3 class="section-title">WEEK-BY-WEEK BREAKDOWN</h3>
                        <table class="w-full table-simple border border-amber-200">
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
                        <div class="text-xs text-gray-500 italic mt-2" id="valWbwInsight">Pattern: compliance and sales dipped mid-month &mdash; review route discipline for weeks 29-30.</div>
                    </div>

                    <!-- Right Column: MoM & Returns Summary -->
                    <div class="flex flex-col gap-6">
                        <!-- Month-Over-Month -->
                        <div>
                            <h3 class="section-title">MONTH-OVER-MONTH</h3>
                            <table class="w-full table-simple border border-amber-200">
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
                                        <td class="font-medium text-gray-800">Sales (Rs.)</td>
                                        <td class="text-right font-medium" id="momPrevSales">0</td>
                                        <td class="text-right font-medium" id="momCurrSales">0</td>
                                        <td class="text-center font-bold" id="momSalesTrend">-</td>
                                    </tr>
                                    <tr>
                                        <td class="font-medium text-gray-800">Return %</td>
                                        <td class="text-right font-medium" id="momPrevReturn">0%</td>
                                        <td class="text-right font-medium" id="momCurrReturn">0%</td>
                                        <td class="text-center font-bold" id="momReturnTrend">-</td>
                                    </tr>
                                    <tr>
                                        <td class="font-medium text-gray-800">Collection %</td>
                                        <td class="text-right font-medium" id="momPrevCollection">0%</td>
                                        <td class="text-right font-medium" id="momCurrCollection">0%</td>
                                        <td class="text-center font-bold" id="momCollectionTrend">-</td>
                                    </tr>
                                    <tr>
                                        <td class="font-medium text-gray-800">New shops</td>
                                        <td class="text-right font-medium" id="momPrevNewShops">0</td>
                                        <td class="text-right font-medium" id="momCurrNewShops">0</td>
                                        <td class="text-center font-bold" id="momNewShopsTrend">-</td>
                                    </tr>
                                    <tr>
                                        <td class="font-medium text-gray-800">Dormant shops</td>
                                        <td class="text-right font-medium" id="momPrevDormant">0</td>
                                        <td class="text-right font-medium" id="momCurrDormant">0</td>
                                        <td class="text-center font-bold" id="momDormantTrend">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Returns Summary -->
                        <div>
                            <h3 class="section-title">RETURNS SUMMARY (<span id="lblReturnsMonth">JULY</span>)</h3>
                            <table class="w-full table-simple border border-amber-200">
                                <thead>
                                    <tr>
                                        <th>Direction</th>
                                        <th class="text-right">Value (Rs.)</th>
                                        <th class="text-right">% of Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white">
                                        <td class="font-medium text-gray-800">1. Agent &rarr; Bakery</td>
                                        <td class="text-right font-medium text-gray-800" id="valRetAgentBakery">0</td>
                                        <td class="text-right text-gray-700" id="valRetAgentBakeryPct">0%</td>
                                    </tr>
                                    <tr class="bg-amber-50/20">
                                        <td class="font-medium text-gray-800">2. Shop &rarr; Agent</td>
                                        <td class="text-right font-medium text-gray-800" id="valRetShopAgent">0</td>
                                        <td class="text-right text-gray-700" id="valRetShopAgentPct">0%</td>
                                    </tr>
                                    <tr class="bg-white font-bold">
                                        <td class="text-gray-900 font-bold">Total returns</td>
                                        <td class="text-right text-gray-900 font-black" id="valRetTotal">0</td>
                                        <td class="text-right font-black" id="valRetTotalPct">0%</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-xs text-gray-600 mt-2 space-y-1">
                                <div><strong class="text-gray-800">Agent &rarr; Bakery:</strong> <span id="lblAgentBakeryBreakdown">None</span></div>
                                <div><strong class="text-gray-800">Shop &rarr; Agent:</strong> <span id="lblShopAgentBreakdown">None</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commission Section -->
                <div class="metric-card mb-6">
                    <h3 class="section-title">COMMISSION &mdash; <span id="lblCommissionMonth">JULY 2026</span></h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <!-- Left Side -->
                        <div class="space-y-2.5 text-xs text-gray-700">
                            <div><span class="text-gray-600">Invoiced value:</span> <strong class="text-gray-900" id="valComInvoiced">Rs. 0</strong> &mdash; <span class="text-gray-600">Agent &rarr; Bakery returns:</span> <strong class="text-gray-900" id="valComReturns">Rs. 0</strong></div>
                            <div><span class="text-gray-600">Net sales:</span> <strong class="text-gray-900 text-sm font-bold" id="valComNetSales">Rs. 0</strong></div>
                            <div><span class="text-gray-600">Invoicing commission (<span id="valComInvRate">15</span>% &times; net):</span> <strong class="text-gray-900 font-bold" id="valComInvAmt">Rs. 0</strong></div>
                            <div><span class="text-gray-600">Target bonus:</span> <strong id="valComBonusAmt" class="color-violation">Rs. 0 &mdash; not qualified</strong></div>
                            <div class="pt-2 border-t border-amber-200"><span class="text-gray-700 font-bold uppercase">Total payable:</span> <strong class="text-amber-800 text-base font-black ml-1" id="valComTotal">Rs. 0</strong></div>
                        </div>

                        <!-- Right Side: Achievement Level Table -->
                        <div>
                            <table class="w-full table-simple border border-amber-200">
                                <thead>
                                    <tr>
                                        <th>Achievement Level</th>
                                        <th class="text-right">%</th>
                                        <th class="text-center w-20">100%?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white">
                                        <td class="font-medium text-gray-800">Total sales</td>
                                        <td class="text-right font-medium" id="valAchieveTotalPct">0%</td>
                                        <td class="text-center font-bold" id="valAchieveTotalIcon">&cross;</td>
                                    </tr>
                                    <tr class="bg-amber-50/20">
                                        <td class="font-medium text-gray-800">Category sales</td>
                                        <td class="text-right font-medium" id="valAchieveCategoryPct">0%</td>
                                        <td class="text-center font-bold" id="valAchieveCategoryIcon">&cross;</td>
                                    </tr>
                                    <tr class="bg-white">
                                        <td class="font-medium text-gray-800">SKU sales</td>
                                        <td class="text-right font-medium" id="valAchieveSkuPct">0%</td>
                                        <td class="text-center font-bold" id="valAchieveSkuIcon">&cross;</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-xs text-gray-500 mt-2" id="valBonusRuleNote">
                                Bonus is all-or-nothing: 100% required at every level. Missing target by <strong class="text-gray-800" id="valMissTargetAmt">Rs. 0</strong> cost the full 5% bonus &mdash; about <strong class="text-gray-800" id="valMissBonusAmt">Rs. 0</strong>.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credit & Collection Section -->
                <div class="mb-6">
                    <h3 class="section-title">CREDIT &amp; COLLECTION</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Box 1: Agent -> Bakery Credit Position -->
                        <div class="metric-card">
                            <h4 class="text-xs font-black uppercase text-amber-900 mb-2">1. AGENT &rarr; BAKERY CREDIT POSITION</h4>
                            <div class="text-xs text-gray-700 space-y-1.5">
                                <div><span class="text-gray-600">Closing dues:</span> <strong class="text-gray-900 font-bold" id="valAgBakeryClosing">Rs. 0</strong> <span class="text-gray-400 mx-2">|</span> <span class="text-gray-600">Utilization:</span> <strong class="color-warning" id="valAgBakeryUtil">0%</strong></div>
                                <div><span class="text-gray-600">Credit period:</span> <strong class="text-gray-900" id="valAgBakeryPeriod">14.4 days</strong></div>
                                <div class="pt-1.5 border-t border-amber-100">
                                    <span class="text-gray-600 font-medium">Ageing &mdash;</span>
                                    <span>Within term (0&ndash;14d): <strong class="text-gray-900" id="valAgAgeing0_14">Rs. 0</strong></span> &bull;
                                    <span>15&ndash;30d: <strong class="color-warning" id="valAgAgeing15_30">Rs. 0</strong></span> &bull;
                                    <span>31&ndash;60d: <strong class="color-violation" id="valAgAgeing31_60">Rs. 0</strong></span> &bull;
                                    <span>60d+: <strong class="color-violation" id="valAgAgeing60Plus">Rs. 0</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Box 2: Agent -> Retail Outlet Credit Position -->
                        <div class="metric-card">
                            <h4 class="text-xs font-black uppercase text-amber-900 mb-2">2. AGENT &rarr; RETAIL OUTLET CREDIT POSITION <span class="text-gray-400 font-normal lowercase">(billed to this agent)</span></h4>
                            <div class="text-xs text-gray-700 space-y-1.5">
                                <div><span class="text-gray-600">Outstanding on list dues:</span> <strong class="text-gray-900 font-bold" id="valOutletClosing">Rs. 0</strong> <span class="text-gray-400 mx-2">|</span> <span class="text-gray-600">Collection rate:</span> <strong class="color-warning" id="valOutletCollectionRate">0%</strong></div>
                                <div class="pt-1.5 border-t border-amber-100">
                                    <span class="text-gray-600 font-medium">Ageing &mdash;</span>
                                    <span>Current: <strong class="text-gray-900" id="valOutAgeing0_14">Rs. 0</strong></span> &bull;
                                    <span>1&ndash;14d: <strong class="text-gray-900" id="valOutAgeing15_30">Rs. 0</strong></span> &bull;
                                    <span>15&ndash;30d: <strong class="color-warning" id="valOutAgeing31_60">Rs. 0</strong></span> &bull;
                                    <span>60d+: <strong class="color-violation" id="valOutAgeing60Plus">Rs. 0</strong></span>
                                </div>
                                <div class="pt-1 text-gray-600">
                                    Outlets with 2+ open credit bills: <strong class="color-violation" id="valOut2PlusCount">0</strong> <span class="text-gray-400">(see list below)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- A. Outlets With More Than 2 Credit Bills -->
                <div class="mb-6">
                    <h3 class="section-title">A. OUTLETS WITH MORE THAN 2 CREDIT BILLS <span class="text-gray-400 font-normal normal-case text-xs ml-1">(all-to-bill policy violation)</span></h3>
                    <table class="w-full table-simple border border-amber-200">
                        <thead>
                            <tr>
                                <th>Outlet</th>
                                <th class="text-center w-28">Open Bills</th>
                                <th class="text-right">Total Balance (Rs.)</th>
                                <th class="text-right">Oldest Bill Age</th>
                            </tr>
                        </thead>
                        <tbody id="tblOutlets2PlusBills">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>

                <!-- B. Top 5 Highest Credit Balance Retail Shops -->
                <div class="mb-6">
                    <h3 class="section-title">B. TOP 5 HIGHEST CREDIT BALANCE RETAIL SHOPS <span class="text-gray-400 font-normal normal-case text-xs ml-1">(this agent)</span></h3>
                    <table class="w-full table-simple border border-amber-200">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>Outlet</th>
                                <th class="text-right">Balance (Rs.)</th>
                                <th class="text-right">Days Outstanding</th>
                                <th class="text-center w-28">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tblTopCreditShops">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>

                <!-- SALES GROWTH Section -->
                <div class="mb-4 mt-6 pt-4 border-t-2 border-amber-200">
                    <h2 class="section-title text-base mb-1">SALES GROWTH</h2>
                </div>

                <!-- 1. Outlet Growth -->
                <div class="mb-6">
                    <h3 class="section-title">1. OUTLET GROWTH (<span id="lblOutletMonth">JULY</span>)</h3>
                    <div class="metric-card">
                        <div class="text-xs text-gray-700">
                            <span class="text-gray-600">Opening active outlets:</span> <strong class="text-gray-900 font-bold" id="valOutOpen">0</strong>
                            <span class="text-gray-400 mx-2">&bull;</span>
                            <span class="text-gray-600">New shops:</span> <strong class="color-track" id="valOutNew">+0</strong>
                            <span class="text-gray-400 mx-2">&bull;</span>
                            <span class="text-gray-600">Newly dormant:</span> <strong class="color-violation" id="valOutDormant">0</strong>
                            <span class="text-gray-400 mx-2">&bull;</span>
                            <span class="text-gray-600">Closing active outlets:</span> <strong class="text-gray-900 font-bold" id="valOutClose">0 / 0</strong>
                            <span class="color-track ml-1 font-bold" id="valOutGrowth">(+0% ▲)</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-2" id="valNewShopsListContainer">
                            <strong class="text-gray-700">New shops:</strong> <span id="valNewShopsList">None</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Top 5 Highest Sale Retail Shops -->
                <div class="mb-6">
                    <h3 class="section-title">2. TOP 5 HIGHEST SALE RETAIL SHOPS (<span id="lblTopSaleMonth">July</span>)</h3>
                    <table class="w-full table-simple border border-amber-200">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>Outlet</th>
                                <th class="text-right">Sales (Rs.)</th>
                                <th class="text-right">% of Agent Sales</th>
                                <th class="text-right" id="thTopSaleVs">vs June</th>
                            </tr>
                        </thead>
                        <tbody id="tblTopSaleShops">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>

                <!-- 3. Top 5 Fast Moving SKUs -->
                <div class="mb-6">
                    <h3 class="section-title">3. TOP 5 FAST MOVING SKUs <span class="text-gray-400 font-normal normal-case text-xs ml-1" id="lblSkuMonth">(ranked by units sold in July)</span></h3>
                    <table class="w-full table-simple border border-amber-200">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>SKU</th>
                                <th class="text-right">Units Sold</th>
                                <th class="text-right">Units / Working Day</th>
                                <th class="text-right">Sales (Rs.)</th>
                                <th class="text-right">% of Agent Sales</th>
                            </tr>
                        </thead>
                        <tbody id="tblTopSkus">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>

                <!-- 4. Top 5 Highest Sales Growth Retail Shops -->
                <div class="mb-6">
                    <h3 class="section-title">4. TOP 5 HIGHEST SALES GROWTH RETAIL SHOPS <span class="text-gray-400 font-normal normal-case text-xs ml-1" id="lblGrowthSubtitle">(monthly view &mdash; July vs June; ranked by growth in Rs.)</span></h3>
                    <table class="w-full table-simple border border-amber-200">
                        <thead>
                            <tr>
                                <th class="w-10 text-center">#</th>
                                <th>Outlet</th>
                                <th class="text-right" id="thGrowthPrev">June (Rs.)</th>
                                <th class="text-right" id="thGrowthCurr">July (Rs.)</th>
                                <th class="text-right">Growth (Rs.)</th>
                                <th class="text-right">Growth %</th>
                            </tr>
                        </thead>
                        <tbody id="tblTopGrowthShops">
                            <!-- JS Injected -->
                        </tbody>
                    </table>
                </div>

                <!-- Next Month Target -->
                <div class="metric-card mb-6">
                    <h3 class="section-title mb-2"><span id="lblNextMonthTarget">AUGUST</span> TARGET <span class="text-gray-400 font-normal normal-case text-xs ml-1">(entered by management &mdash; sign-off in meeting)</span></h3>
                    <div class="text-xs text-gray-700 space-y-4">
                        <div><span class="text-gray-600">Proposed target:</span> <strong class="text-gray-900 text-sm ml-1" id="valNextProposedTarget">Rs. 0</strong></div>
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-4 pt-4 text-xs text-gray-700">
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium">Agent signature:</span>
                                <span class="inline-block border-b-2 border-gray-500 ml-2" style="border-bottom: 2px solid #64748b; height: 18px; width: 220px;"></span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium">Director signature:</span>
                                <span class="inline-block border-b-2 border-gray-500 ml-2" style="border-bottom: 2px solid #64748b; height: 18px; width: 220px;"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Color Legend & Footer -->
                <div class="pt-4 border-t border-amber-200 flex flex-col md:flex-row md:items-center md:justify-between text-xs text-gray-500 gap-2">
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-red-600 inline-block rounded-xs"></span> <strong class="text-gray-700">Rule violation</strong></span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-amber-500 inline-block rounded-xs"></span> <strong class="text-gray-700">Warning</strong></span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-green-600 inline-block rounded-xs"></span> <strong class="text-gray-700">On track</strong></span>
                    </div>
                    <div class="text-right text-gray-400" id="lblFooterReport">
                        Dimuthu Bakers v2.0.1 &mdash; Report B &mdash; Monthly Agent Review
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const formatMoney = (amount) => Number(amount || 0).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

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

            // 1. Header Banner & Strip
            document.getElementById('lblMonthYear').innerText = `${monthName} ${year}`;
            document.getElementById('lblAgentName').innerText = `${res.agent.name} (${res.agent.code})`;
            document.getElementById('lblRoutes').innerText = res.agent.routes;
            document.getElementById('lblMonthlyTarget').innerText = `Rs. ${formatMoney(res.monthly_target)}`;
            document.getElementById('lblFooterReport').innerText = `Dimuthu Bakers v2.0.1 — Report B — Monthly Agent Review (${res.agent.name})`;

            // 2. Top Metric Cards
            document.getElementById('valMonthlySales').innerText = `Rs. ${formatMoney(res.sales.current)}`;
            const achieveClass = res.sales.percent_target >= 100 ? 'color-track' : (res.sales.percent_target >= 80 ? 'color-warning' : 'color-violation');
            const achieveArrow = res.sales.percent_target >= 100 ? '▲' : '▲';
            document.getElementById('valPercentTarget').innerHTML = `<span class="${achieveClass}">${res.sales.percent_target}% ${achieveArrow}</span>`;

            const gSign = res.sales.growth >= 0 ? '+' : '';
            document.getElementById('valMomSalesDesc').innerText = `vs ${res.mom.prev_month_label}: Rs. ${formatMoney(res.sales.prev)} (${gSign}${res.sales.growth}%)`;

            document.getElementById('valRemainingTarget').innerText = `Rs. ${formatMoney(res.sales.remaining)}`;
            if (res.sales.remaining > 0) {
                document.getElementById('valTargetRemark').innerText = `Target missed by ${res.sales.target_missed_pct}% — discuss in meeting`;
                document.getElementById('valRemainingTarget').className = "text-2xl font-black color-violation";
            } else {
                document.getElementById('valTargetRemark').innerText = `Target achieved! Eligible for bonus.`;
                document.getElementById('valTargetRemark').className = "text-xs color-track mt-2 font-bold";
                document.getElementById('valRemainingTarget').className = "text-2xl font-black color-track";
            }

            // 3. Week-by-Week Breakdown
            const tblWbw = document.getElementById('tblWeekByWeek');
            tblWbw.innerHTML = '';
            res.week_by_week.forEach((w, idx) => {
                const compClass = w.visit_compliance >= 90 ? 'color-track' : (w.visit_compliance >= 75 ? 'color-warning' : 'color-violation');
                const retClass = w.return_percent <= 5.0 ? 'color-track' : 'color-violation';
                const rowBg = idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20';

                tblWbw.innerHTML += `
                    <tr class="${rowBg} hover:bg-amber-50/40 transition-colors">
                        <td class="font-medium text-gray-900">${w.label}</td>
                        <td class="text-right font-medium text-gray-800">${formatMoney(w.sales)}</td>
                        <td class="text-right"><span class="${compClass}">${w.visit_compliance}%</span></td>
                        <td class="text-right"><span class="${retClass}">${w.return_percent}%</span></td>
                    </tr>
                `;
            });

            // 4. Month-Over-Month Table
            document.getElementById('thPrevMonth').innerText = res.mom.prev_month_label;
            document.getElementById('thCurrMonth').innerText = res.mom.curr_month_label;

            document.getElementById('momPrevSales').innerText = formatMoney(res.sales.prev);
            document.getElementById('momCurrSales').innerText = formatMoney(res.sales.current);
            document.getElementById('momSalesTrend').innerHTML = getTrendHtml(res.mom.sales_trend, true, '%');

            document.getElementById('momPrevReturn').innerText = `${res.mom.return_prev}%`;
            document.getElementById('momCurrReturn').innerText = `${res.mom.return_curr}%`;
            document.getElementById('momReturnTrend').innerHTML = getTrendHtml(res.mom.return_prev - res.mom.return_curr, true, '%', true);

            document.getElementById('momPrevCollection').innerText = `${res.mom.collection_prev}%`;
            document.getElementById('momCurrCollection').innerText = `${res.mom.collection_curr}%`;
            document.getElementById('momCollectionTrend').innerHTML = getTrendHtml(res.mom.collection_curr - res.mom.collection_prev, true, ' pts');

            document.getElementById('momPrevNewShops').innerText = res.mom.new_shops_prev;
            document.getElementById('momCurrNewShops').innerText = res.mom.new_shops_curr;
            document.getElementById('momNewShopsTrend').innerHTML = getTrendHtml(res.mom.new_shops_curr - res.mom.new_shops_prev, false, '', false, true);

            document.getElementById('momPrevDormant').innerText = res.mom.dormant_prev;
            document.getElementById('momCurrDormant').innerText = res.mom.dormant_curr;
            document.getElementById('momDormantTrend').innerHTML = getTrendHtml(res.mom.dormant_curr - res.mom.dormant_prev, false, '', false, false, true);

            // 5. Returns Summary
            document.getElementById('lblReturnsMonth').innerText = monthName.toUpperCase();
            document.getElementById('valRetAgentBakery').innerText = formatMoney(res.returns_summary.agent_to_bakery.value);
            document.getElementById('valRetAgentBakeryPct').innerText = `${res.returns_summary.agent_to_bakery.pct}%`;
            document.getElementById('valRetShopAgent').innerText = formatMoney(res.returns_summary.shop_to_agent.value);
            document.getElementById('valRetShopAgentPct').innerText = `${res.returns_summary.shop_to_agent.pct}%`;
            document.getElementById('valRetTotal').innerText = formatMoney(res.returns_summary.total.value);
            document.getElementById('valRetTotalPct').innerText = `${res.returns_summary.total.pct}%`;

            document.getElementById('lblAgentBakeryBreakdown').innerText = res.returns_summary.agent_to_bakery.reasons;
            document.getElementById('lblShopAgentBreakdown').innerText = res.returns_summary.shop_to_agent.reasons;

            // 6. Commission
            document.getElementById('lblCommissionMonth').innerText = `${monthName.toUpperCase()} ${year}`;
            document.getElementById('valComInvoiced').innerText = `Rs. ${formatMoney(res.commission.invoiced_value)}`;
            document.getElementById('valComReturns').innerText = `Rs. ${formatMoney(res.commission.agent_bakery_returns)}`;
            document.getElementById('valComNetSales').innerText = `Rs. ${formatMoney(res.commission.net_sales)}`;
            document.getElementById('valComInvRate').innerText = res.commission.invoicing_rate;
            document.getElementById('valComInvAmt').innerText = `Rs. ${formatMoney(res.commission.invoicing_commission)}`;

            if (res.commission.bonus_qualified) {
                document.getElementById('valComBonusAmt').innerText = `Rs. ${formatMoney(res.commission.target_bonus)} — qualified ✓`;
                document.getElementById('valComBonusAmt').className = "color-track";
            } else {
                document.getElementById('valComBonusAmt').innerText = `Rs. 0 — not qualified`;
                document.getElementById('valComBonusAmt').className = "color-violation";
            }
            document.getElementById('valComTotal').innerText = `Rs. ${formatMoney(res.commission.total_payable)}`;

            document.getElementById('valAchieveTotalPct').innerText = `${res.commission.total_sales_achievement}%`;
            document.getElementById('valAchieveTotalIcon').innerHTML = res.commission.total_sales_achievement >= 100 ? '<span class="color-track">&check;</span>' : '<span class="color-violation">&cross;</span>';

            document.getElementById('valAchieveCategoryPct').innerText = `${res.commission.category_sales_achievement}%`;
            document.getElementById('valAchieveCategoryIcon').innerHTML = res.commission.category_sales_achievement >= 100 ? '<span class="color-track">&check;</span>' : '<span class="color-violation">&cross;</span>';

            document.getElementById('valAchieveSkuPct').innerText = `${res.commission.sku_sales_achievement}%`;
            document.getElementById('valAchieveSkuIcon').innerHTML = res.commission.sku_sales_achievement >= 100 ? '<span class="color-track">&check;</span>' : '<span class="color-violation">&cross;</span>';

            document.getElementById('valMissTargetAmt').innerText = `Rs. ${formatMoney(res.sales.remaining)}`;
            document.getElementById('valMissBonusAmt').innerText = `Rs. ${formatMoney(res.commission.missed_bonus_amount)}`;

            // 7. Credit & Collection
            const cab = res.credit_collection.agent_to_bakery;
            document.getElementById('valAgBakeryClosing').innerText = `Rs. ${formatMoney(cab.closing_dues)}`;
            document.getElementById('valAgBakeryUtil').innerText = `${cab.utilization}%`;
            document.getElementById('valAgBakeryPeriod').innerText = `${cab.credit_period} days`;
            document.getElementById('valAgAgeing0_14').innerText = `Rs. ${formatMoney(cab.ageing['0_14'])}`;
            document.getElementById('valAgAgeing15_30').innerText = `Rs. ${formatMoney(cab.ageing['15_30'])}`;
            document.getElementById('valAgAgeing31_60').innerText = `Rs. ${formatMoney(cab.ageing['31_60'])}`;
            document.getElementById('valAgAgeing60Plus').innerText = `Rs. ${formatMoney(cab.ageing['60_plus'])}`;

            const cao = res.credit_collection.agent_to_outlet;
            document.getElementById('valOutletClosing').innerText = `Rs. ${formatMoney(cao.closing_dues)}`;
            document.getElementById('valOutletCollectionRate').innerText = `${cao.collection_rate}%`;
            document.getElementById('valOutAgeing0_14').innerText = `Rs. ${formatMoney(cao.ageing['0_14'])}`;
            document.getElementById('valOutAgeing15_30').innerText = `Rs. ${formatMoney(cao.ageing['15_30'])}`;
            document.getElementById('valOutAgeing31_60').innerText = `Rs. ${formatMoney(cao.ageing['31_60'])}`;
            document.getElementById('valOutAgeing60Plus').innerText = `Rs. ${formatMoney(cao.ageing['60_plus'])}`;
            document.getElementById('valOut2PlusCount').innerText = cao.outlets_with_2plus_bills_count;

            // 8. Outlets with 2+ open bills
            const tbl2Plus = document.getElementById('tblOutlets2PlusBills');
            tbl2Plus.innerHTML = '';
            if (res.outlets_with_2plus_bills && res.outlets_with_2plus_bills.length > 0) {
                res.outlets_with_2plus_bills.forEach((ob, idx) => {
                    tbl2Plus.innerHTML += `
                        <tr class="${idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20'} hover:bg-amber-50/40 transition-colors">
                            <td class="font-bold text-gray-900">${ob.outlet_name}</td>
                            <td class="text-center font-bold text-gray-800">${ob.open_bills}</td>
                            <td class="text-right font-medium text-gray-800">${formatMoney(ob.total_balance)}</td>
                            <td class="text-right text-gray-700">${Math.round(Math.abs(Number(ob.oldest_bill_age) || 0))} days</td>
                        </tr>
                    `;
                });
            } else {
                tbl2Plus.innerHTML = '<tr><td colspan="4" class="text-center text-gray-400 py-3">No outlets with 2+ open bills</td></tr>';
            }

            // 9. Top 5 Highest Credit Balance Retail Shops
            const tblTcs = document.getElementById('tblTopCreditShops');
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
                    } else {
                        statusBadge = '<span class="color-track">Within term</span>';
                    }

                    tblTcs.innerHTML += `
                        <tr class="${rowClass} hover:bg-amber-50/40 transition-colors">
                            <td class="text-center text-gray-500 font-bold">${idx + 1}</td>
                            <td class="font-bold text-gray-900">${sh.outlet_name}</td>
                            <td class="text-right"><span class="${balanceClass}">${formatMoney(sh.balance)}</span></td>
                            <td class="text-right text-gray-700 font-medium">${Math.round(Math.abs(Number(sh.days_outstanding) || 0))}</td>
                            <td class="text-center">${statusBadge}</td>
                        </tr>
                    `;
                });
            } else {
                tblTcs.innerHTML = '<tr><td colspan="5" class="text-center text-gray-400 py-3">No credit balances found</td></tr>';
            }

            // 10. Sales Growth
            // 1. Outlet Growth
            const og = res.sales_growth.outlet_growth;
            document.getElementById('lblOutletMonth').innerText = monthName.toUpperCase();
            document.getElementById('valOutOpen').innerText = og.opening;
            document.getElementById('valOutNew').innerText = `+${og.new_shops}`;
            document.getElementById('valOutDormant').innerText = `-${og.newly_dormant}`;
            document.getElementById('valOutClose').innerText = `${og.closing} / ${og.total}`;

            const ogSign = og.growth_pct >= 0 ? '+' : '';
            const ogArrow = og.growth_pct >= 0 ? '▲' : '▼';
            const ogClass = og.growth_pct >= 0 ? 'color-track' : 'color-violation';
            document.getElementById('valOutGrowth').className = `${ogClass} ml-1 font-bold`;
            document.getElementById('valOutGrowth').innerText = `(${ogSign}${Math.abs(og.growth_pct)}% ${ogArrow})`;

            document.getElementById('valNewShopsList').innerText = og.new_shops_list || 'None';

            // 2. Top 5 Highest Sale Retail Shops
            document.getElementById('lblTopSaleMonth').innerText = monthName;
            document.getElementById('thTopSaleVs').innerText = `vs ${res.prev_month_label || 'Prev'}`;
            const tblTss = document.getElementById('tblTopSaleShops');
            tblTss.innerHTML = '';
            if (res.sales_growth.top_sale_shops && res.sales_growth.top_sale_shops.length > 0) {
                res.sales_growth.top_sale_shops.forEach((sh, idx) => {
                    const vsSign = sh.vs_prev_month >= 0 ? '+' : '';
                    const vsArrow = sh.vs_prev_month >= 0 ? '▲' : '▼';
                    const vsClass = sh.vs_prev_month >= 0 ? 'color-track' : 'color-violation';

                    tblTss.innerHTML += `
                        <tr class="${idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20'} hover:bg-amber-50/40 transition-colors">
                            <td class="text-center text-gray-500 font-bold">${idx + 1}</td>
                            <td class="font-bold text-gray-900">${sh.outlet_name}</td>
                            <td class="text-right font-bold text-gray-900">${formatMoney(sh.sales)}</td>
                            <td class="text-right text-gray-700">${sh.sales_pct}%</td>
                            <td class="text-right"><span class="${vsClass}">${vsArrow} ${vsSign}${sh.vs_prev_month}%</span></td>
                        </tr>
                    `;
                });
            } else {
                tblTss.innerHTML = '<tr><td colspan="5" class="text-center text-gray-400 py-3">No sales found</td></tr>';
            }

            // 3. Top 5 Fast Moving SKUs
            document.getElementById('lblSkuMonth').innerText = `(ranked by units sold in ${monthName})`;
            const tblSkus = document.getElementById('tblTopSkus');
            tblSkus.innerHTML = '';
            if (res.sales_growth.top_skus && res.sales_growth.top_skus.length > 0) {
                res.sales_growth.top_skus.forEach((sku, idx) => {
                    tblSkus.innerHTML += `
                        <tr class="${idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20'} hover:bg-amber-50/40 transition-colors">
                            <td class="text-center text-gray-500 font-bold">${idx + 1}</td>
                            <td class="font-bold text-gray-900">${sku.sku_name}</td>
                            <td class="text-right font-bold text-gray-900">${formatMoney(sku.units_sold)}</td>
                            <td class="text-right text-gray-700">${formatMoney(sku.units_per_day)}</td>
                            <td class="text-right font-medium text-gray-800">${formatMoney(sku.sales)}</td>
                            <td class="text-right font-bold text-gray-900">${sku.sales_pct}%</td>
                        </tr>
                    `;
                });
            } else {
                tblSkus.innerHTML = '<tr><td colspan="6" class="text-center text-gray-400 py-3">No SKU records found</td></tr>';
            }

            // 4. Top 5 Highest Sales Growth Retail Shops
            document.getElementById('lblGrowthSubtitle').innerText = `(monthly view — ${monthName} vs ${res.prev_month_label || 'Prev'}; ranked by growth in Rs.)`;
            document.getElementById('thGrowthPrev').innerText = `${res.prev_month_label || 'Prev'} (Rs.)`;
            document.getElementById('thGrowthCurr').innerText = `${monthName} (Rs.)`;
            const tblTgs = document.getElementById('tblTopGrowthShops');
            tblTgs.innerHTML = '';
            if (res.sales_growth.top_growth_shops && res.sales_growth.top_growth_shops.length > 0) {
                res.sales_growth.top_growth_shops.forEach((sh, idx) => {
                    const gSign = sh.growth_rs >= 0 ? '+' : '';
                    const gPctSign = sh.growth_pct >= 0 ? '+' : '';
                    const gClass = sh.growth_rs >= 0 ? 'color-track' : 'color-violation';

                    tblTgs.innerHTML += `
                        <tr class="${idx % 2 === 0 ? 'bg-white' : 'bg-amber-50/20'} hover:bg-amber-50/40 transition-colors">
                            <td class="text-center text-gray-500 font-bold">${idx + 1}</td>
                            <td class="font-bold text-gray-900">${sh.outlet_name}</td>
                            <td class="text-right text-gray-700">${formatMoney(sh.prev_sales)}</td>
                            <td class="text-right font-bold text-gray-900">${formatMoney(sh.current_sales)}</td>
                            <td class="text-right"><span class="${gClass}">${gSign}${formatMoney(sh.growth_rs)}</span></td>
                            <td class="text-right"><span class="${gClass}">${gPctSign}${sh.growth_pct}%</span></td>
                        </tr>
                    `;
                });
            } else {
                tblTgs.innerHTML = '<tr><td colspan="6" class="text-center text-gray-400 py-3">No sales growth records found</td></tr>';
            }

            // 11. Next Month Target
            document.getElementById('lblNextMonthTarget').innerText = res.next_target.label.toUpperCase();
            let nextTargetValStr = res.next_target.target === 'Pending' ? 'Pending' : `Rs. ${formatMoney(res.next_target.target)}`;
            document.getElementById('valNextProposedTarget').innerText = nextTargetValStr;
        }

        // Helper for Trend arrows
        function getTrendHtml(val, showPlus, suffix, customImprove = false, noNumber = false, inverseGood = false) {
            let num = Number(val);
            if (num === 0) return `<span class="text-gray-400">-</span>`;

            let isGood = num > 0;
            if (inverseGood) isGood = num < 0;
            if (customImprove) isGood = num > 0;

            const color = isGood ? 'color-track' : 'color-violation';
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
