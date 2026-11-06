@extends('layouts.app')

@section('content')

    <!-- Flatpickr -->
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/flatpickr.min.css') }}">
    <script src="{{ asset('vendor/flatpickr/flatpickr.min.js') }}"></script>

    <style>
        .report-header {
            background-color: #8c4314; /* Deep warm bakery brown matching mockup */
            color: white;
        }

        .metric-card {
            border: 1px solid #fed7aa; /* Soft warm amber border */
            border-radius: 0.5rem;
            padding: 1.15rem;
            background: #ffffff;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.02);
            position: relative;
            overflow: hidden;
        }

        .metric-title {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9a3412;
            font-weight: 800;
            margin-bottom: 0.35rem;
        }

        .metric-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.2;
        }

        .runrate-card {
            border: 1px solid #fed7aa;
            background: #fffaf5;
            border-radius: 0.5rem;
            padding: 1.25rem 1.5rem;
        }

        .table-simple th {
            font-size: 0.72rem;
            background-color: #fff8ed; /* Warm cream matching mockup */
            color: #9a3412; /* Brown amber 800 */
            padding: 0.6rem 0.75rem;
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
            padding: 0.55rem 0.75rem;
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
            letter-spacing: 0.02em;
        }

        /* Status color utility classes */
        .color-track {
            color: #16a34a !important; /* Green */
            font-weight: 700 !important;
        }

        .color-warning {
            color: #d97706 !important; /* Amber */
            font-weight: 700 !important;
        }

        .color-violation {
            color: #dc2626 !important; /* Red */
            font-weight: 700 !important;
        }

        .row-violation,
        .table-simple tr.row-violation,
        .table-simple tr.row-violation td {
            background-color: #fff1f2 !important; /* Soft rose */
        }

        /* Color indicator bottom bars for top KPI cards */
        .bar-track {
            height: 4px;
            background-color: #16a34a;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .bar-warning {
            height: 4px;
            background-color: #d97706;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .bar-violation {
            height: 4px;
            background-color: #dc2626;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        /* Flatpickr Styling */
        .flatpickr-calendar {
            background: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #fed7aa;
            padding: 0.5rem;
            font-family: inherit;
        }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange {
            background: #8c4314 !important;
            border-color: #8c4314 !important;
            color: #fff !important;
        }
        .flatpickr-day.inRange {
            background: #fff8ed !important;
            border-color: #ffedd5 !important;
            color: #9a3412 !important;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Page Title & Description -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-2">
                    <span class="inline-block w-2.5 h-6 bg-amber-700 rounded-sm"></span>
                    Weekly Agent Review
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Comprehensive weekly operational, sales, compliance, and credit audit per agent.</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-orange-200 p-5 mb-6">
            <form id="reportFilterForm" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                @csrf
                <!-- Agent Select -->
                <div class="md:col-span-5">
                    <label for="agent_id" class="block text-xs font-bold uppercase tracking-wider text-amber-900 mb-1.5">Select Agent</label>
                    <select id="agent_id" name="agent_id" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-600 focus:ring-amber-600 sm:text-sm p-2.5 font-medium text-gray-800">
                        <option value="">-- Choose an Agent --</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->agent_name }} ({{ $agent->agent_code }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Calendar Week Picker -->
                <div class="md:col-span-4">
                    <label for="week_picker" class="block text-xs font-bold uppercase tracking-wider text-amber-900 mb-1.5">Select Week / Date</label>
                    <div class="relative">
                        <input type="text" id="week_picker" readonly placeholder="Click to choose week..."
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-600 focus:ring-amber-600 sm:text-sm p-2.5 pr-10 font-bold text-gray-800 cursor-pointer bg-amber-50/30">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-amber-700">
                            <i class="bi bi-calendar-week-fill text-lg"></i>
                        </span>
                    </div>
                    <!-- Hidden inputs for controller start_date & end_date -->
                    <input type="hidden" id="start_date" name="start_date">
                    <input type="hidden" id="end_date" name="end_date">
                </div>

                <!-- Action Buttons -->
                <div class="md:col-span-3 flex gap-2">
                    <button type="submit" id="btnLoadReport"
                        class="flex-1 flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-bold rounded-lg shadow text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all active:scale-95">
                        <i class="bi bi-funnel mr-1.5"></i> Generate
                    </button>
                    <button type="button" onclick="exportToPDF()"
                        class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-sm font-bold shadow transition-all active:scale-95">
                        <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden py-16 text-center">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-amber-600 border-t-transparent"></div>
            <p class="mt-3 text-sm text-gray-600 font-bold">Compiling weekly performance metrics...</p>
        </div>

        <!-- Dashboard Container matching Mockup 1 -->
        <div id="dashboardContainer" class="hidden space-y-6">

            <!-- 1. Top Header Banner -->
            <div class="report-header rounded-t-xl p-5 flex flex-col md:flex-row justify-between items-start md:items-end shadow-sm">
                <div>
                    <div class="text-[11px] uppercase tracking-widest font-extrabold mb-1 opacity-90">DIMUTHU BAKE HOUSE — KOTTAWA BRANCH</div>
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight">Weekly Agent Review</h1>
                </div>
                <div class="text-left md:text-right text-xs mt-3 md:mt-0 font-medium">
                    <div class="font-bold text-sm" id="lblDateRange">Week 29 • 13 - 19 Jul 2026</div>
                    <div class="opacity-80 mt-0.5" id="lblGenerated">Generated: 15 Jul 2026, 4:30 PM</div>
                </div>
            </div>

            <!-- 2. Subheader Strip -->
            <div class="bg-amber-50/70 border-x border-b border-orange-200 p-4 -mt-6 rounded-b-xl text-xs md:text-sm font-medium text-gray-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-2">
                <div>
                    <span class="font-bold text-amber-950">Agent:</span> <span class="font-black text-gray-900" id="lblAgentName">Greenglobal (AG-002)</span>
                </div>
                <div>
                    <span class="font-bold text-amber-950">Routes:</span> <span class="text-gray-800" id="lblRoutes">Kottawa 1, Kottawa 2, Piliyandala</span>
                </div>
                <div>
                    <span class="font-bold text-amber-950">Monthly Target:</span> <span class="font-black text-gray-900" id="lblMonthlyTarget">Rs. 6,000,000</span> <span class="text-gray-500 italic text-xs">(entered by management)</span>
                </div>
            </div>

            <!-- 3. Top 4 KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1: Weekly Sales -->
                <div class="metric-card">
                    <div class="metric-title">WEEKLY SALES</div>
                    <div class="metric-value text-gray-900" id="valWeeklySales">Rs. 0</div>
                    <div class="mt-2 text-xs flex items-center justify-between font-semibold">
                        <span id="valWeeklyTargetPct" class="color-track">0% of weekly target ▲</span>
                        <span id="valSalesVsLastWeek" class="text-gray-600">+0% vs last week</span>
                    </div>
                    <div id="barWeeklySales" class="bar-track"></div>
                </div>

                <!-- Card 2: Visit Compliance -->
                <div class="metric-card">
                    <div class="metric-title">VISIT COMPLIANCE</div>
                    <div class="metric-value text-gray-900" id="valVisitCompliance">0%</div>
                    <div class="mt-2 text-xs font-semibold text-gray-600" id="valVisitDesc">
                        0 of 0 outlets visited
                    </div>
                    <div id="barVisitCompliance" class="bar-warning"></div>
                </div>

                <!-- Card 3: Return % -->
                <div class="metric-card">
                    <div class="metric-title">RETURN %</div>
                    <div class="metric-value text-gray-900" id="valReturnPercent">0.0%</div>
                    <div class="mt-2 text-xs font-semibold" id="valReturnDesc">
                        0% threshold
                    </div>
                    <div id="barReturnPercent" class="bar-violation"></div>
                </div>

                <!-- Card 4: Credit Utilization -->
                <div class="metric-card">
                    <div class="metric-title">CREDIT UTILIZATION</div>
                    <div class="metric-value text-gray-900" id="valCreditUtil">0%</div>
                    <div class="mt-2 text-xs font-semibold text-gray-600" id="valCreditDesc">
                        Rs. 0 / 0
                    </div>
                    <div id="barCreditUtil" class="bar-warning"></div>
                </div>
            </div>

            <!-- 4. Monthly Target Run-Rate Box -->
            <div class="runrate-card shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                    <!-- Left: Remaining to Target -->
                    <div class="lg:col-span-5 border-b lg:border-b-0 lg:border-r border-orange-200 pb-4 lg:pb-0 lg:pr-6">
                        <div class="text-xs uppercase font-extrabold text-amber-900 tracking-wider">REMAINING TO MONTHLY TARGET</div>
                        <div class="text-3xl lg:text-4xl font-black text-amber-900 mt-1" id="valRemainingTarget">Rs. 0</div>
                        <div class="text-xs text-gray-600 mt-1.5">
                            MTD sales <span id="valMtdSales" class="font-bold text-gray-900">Rs. 0</span> of <span id="valMtdTarget" class="font-bold text-gray-900">Rs. 0</span> target
                        </div>
                    </div>

                    <!-- Right: Run-rate stats -->
                    <div class="lg:col-span-7 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-xs">
                        <div class="space-y-1 text-gray-700">
                            <div class="font-bold" id="valPaceWeeks">Weeks left in July: 2 • Working days left: 12</div>
                            <div>Required weekly run rate: <strong class="text-gray-900" id="valReqWeeklyPace">Rs. 0</strong></div>
                            <div>Required daily run rate: <strong class="text-gray-900" id="valReqDailyPace">Rs. 0</strong></div>
                        </div>
                        <div id="badgeRunRateStatus" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300">
                            <i class="bi bi-check-circle-fill"></i> On track to achieve ✓
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Daily Outlet Visits (minimum 30 per day) -->
            <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
                <div class="bg-amber-50/50 px-5 py-3 border-b border-orange-200 flex justify-between items-center">
                    <span class="section-title">DAILY OUTLET VISITS <span class="text-gray-400 font-normal lowercase">(minimum 30 per day)</span></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-simple">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Day</th>
                                <th class="text-center" style="width: 30%;">Outlets Visited</th>
                                <th style="width: 30%;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tblDailyVisits">
                            <!-- JS populated -->
                        </tbody>
                    </table>
                </div>
                <div class="bg-amber-50/30 px-5 py-2.5 border-t border-orange-200 text-xs font-semibold text-gray-700 flex flex-wrap gap-4" id="lblDailyVisitsFoot">
                    <span>Routes run this week: <strong class="text-gray-900" id="valRoutesRun">3</strong> (<span class="color-track font-bold">Must 3 ✓</span>)</span>
                    <span class="text-gray-300">|</span>
                    <span>Missed outlets: <strong class="text-gray-900" id="valMissedOutlets">4</strong></span>
                </div>
            </div>

            <!-- 6. Returns Section (2 Side-by-Side Tables) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- 1. Agent -> Bakery Returns -->
                <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden flex flex-col">
                    <div class="bg-amber-50/50 px-5 py-3 border-b border-orange-200">
                        <span class="section-title">1. AGENT &rarr; BAKERY RETURNS <span class="text-gray-400 font-normal lowercase">(5 reasons; 'Other' must be specified)</span></span>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full table-simple">
                            <thead>
                                <tr>
                                    <th style="width: 55%;">Reason</th>
                                    <th class="text-right" style="width: 25%;">Value (Rs.)</th>
                                    <th class="text-right" style="width: 20%;">% of Sales</th>
                                </tr>
                            </thead>
                            <tbody id="tblAgentBakeryReturns">
                                <!-- JS populated -->
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-amber-50/30 px-5 py-2.5 border-t border-orange-200 text-xs text-gray-700">
                        Top returned product: <strong class="text-gray-900" id="valTopReturnProd">Sandwich Bread 400g</strong> &mdash; <span class="font-bold text-gray-900" id="valTopReturnQty">187</span> units <span class="text-gray-500 italic">(production quality signal)</span>
                    </div>
                </div>

                <!-- 2. Retail Shop -> Agent Returns -->
                <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden flex flex-col">
                    <div class="bg-amber-50/50 px-5 py-3 border-b border-orange-200">
                        <span class="section-title">2. RETAIL SHOP &rarr; AGENT RETURNS <span class="text-gray-400 font-normal lowercase">(4 reasons; 'Other' must be specified)</span></span>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full table-simple">
                            <thead>
                                <tr>
                                    <th style="width: 45%;">Outlet</th>
                                    <th class="text-right" style="width: 25%;">Value (Rs.)</th>
                                    <th style="width: 30%;">Reason</th>
                                </tr>
                            </thead>
                            <tbody id="tblShopAgentReturns">
                                <!-- JS populated -->
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-amber-50/30 px-5 py-2.5 border-t border-orange-200 text-xs text-gray-700" id="lblShopReturnsFoot">
                        <span class="font-semibold text-gray-800">Return discipline:</span> monitor retail shelf storage and rotation closely.
                    </div>
                </div>
            </div>

            <!-- 7. Credit & Collection (2 Side-by-Side Cards) -->
            <div>
                <h3 class="section-title mb-3">CREDIT & COLLECTION</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- 1. Agent -> Bakery Credit Position -->
                    <div class="metric-card space-y-2.5 text-xs text-gray-700">
                        <div class="font-extrabold text-amber-900 uppercase text-[11px] pb-1 border-b border-orange-100">
                            1. AGENT &rarr; BAKERY CREDIT POSITION
                        </div>
                        <div class="flex justify-between">
                            <span>Credit sales this week:</span>
                            <strong class="text-gray-900" id="valBakeryCreditSales">Rs. 0</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Collections this week:</span>
                            <span>
                                <strong class="text-gray-900" id="valBakeryCollections">Rs. 0</strong>
                                <span class="color-track ml-1" id="valBakeryColRate">(0% collection rate)</span>
                            </span>
                        </div>
                        <div class="flex flex-wrap justify-between pt-1 border-t border-orange-100 text-gray-800">
                            <span>Closing dues: <strong class="text-gray-900" id="valBakeryClosingDues">Rs. 0</strong></span>
                            <span>Credit period: <strong class="text-gray-900" id="valBakeryCreditPeriod">14 days</strong></span>
                            <span>Aged 14+ days: <strong class="color-violation" id="valBakeryAged14">Rs. 0 ▲</strong></span>
                        </div>
                    </div>

                    <!-- 2. Agent -> Retail Outlet Credit Position -->
                    <div class="metric-card space-y-2.5 text-xs text-gray-700">
                        <div class="font-extrabold text-amber-900 uppercase text-[11px] pb-1 border-b border-orange-100">
                            2. AGENT &rarr; RETAIL OUTLET CREDIT POSITION <span class="text-gray-400 font-normal lowercase">(bill-to-bill policy)</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Credit issued to outlets:</span>
                            <strong class="text-gray-900" id="valOutletCreditIssued">Rs. 0</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Collected from outlets:</span>
                            <span>
                                <strong class="text-gray-900" id="valOutletCollected">Rs. 0</strong>
                                <span class="color-warning ml-1" id="valOutletColRate">(0% collection rate)</span>
                            </span>
                        </div>
                        <div class="flex flex-wrap justify-between pt-1 border-t border-orange-100 text-gray-800">
                            <span>Outstanding dues: <strong class="text-gray-900" id="valOutletOutstanding">Rs. 0</strong></span>
                            <span>Aged 14+ days: <strong class="color-violation" id="valOutletAged14">Rs. 0 ▲</strong></span>
                        </div>
                        <div class="pt-1 text-right">
                            <span class="text-gray-700">Outlets with 2+ open credit bills: </span>
                            <strong class="color-violation" id="valOverTwoBillsCount">0 ▲</strong>
                            <span class="text-gray-400 text-[11px] italic">(listed below)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 8. Outlets with More Than 2 Credit Bills (bill-to-bill violation) -->
            <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
                <div class="bg-amber-50/50 px-5 py-3 border-b border-orange-200">
                    <span class="section-title">4. OUTLETS WITH MORE THAN 2 CREDIT BILLS <span class="text-gray-400 font-normal lowercase">(bill-to-bill policy violation)</span></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-simple">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Outlet</th>
                                <th class="text-center" style="width: 20%;">Open Bills</th>
                                <th class="text-right" style="width: 20%;">Total Balance (Rs.)</th>
                                <th class="text-right" style="width: 20%;">Oldest Bill Age</th>
                            </tr>
                        </thead>
                        <tbody id="tblOverTwoBills">
                            <!-- JS populated -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 9. Top 5 Highest Credit Balance Retail Shops -->
            <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
                <div class="bg-amber-50/50 px-5 py-3 border-b border-orange-200">
                    <span class="section-title">3. TOP 5 HIGHEST CREDIT BALANCE RETAIL SHOPS <span class="text-gray-400 font-normal lowercase">(this agent)</span></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-simple">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 8%;">#</th>
                                <th style="width: 37%;">Outlet</th>
                                <th class="text-right" style="width: 20%;">Balance (Rs.)</th>
                                <th class="text-right" style="width: 17%;">Days Outstanding</th>
                                <th class="text-center" style="width: 18%;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tblTopCreditShops">
                            <!-- JS populated -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 10. Sales Growth Section -->
            <div class="space-y-6">
                <h3 class="section-title">SALES GROWTH</h3>

                <!-- 1. Outlet Growth -->
                <div class="bg-white rounded-xl shadow-sm border border-orange-200 p-4 text-xs">
                    <div class="font-extrabold text-amber-900 uppercase text-[11px] mb-2">1. OUTLET GROWTH</div>
                    <div class="flex flex-wrap items-center gap-y-2 gap-x-6 text-gray-700">
                        <div>
                            New shops added: <strong class="color-track" id="valNewShopsCount">+0</strong>
                            <span class="text-gray-500 italic ml-1" id="valNewShopsList"></span>
                        </div>
                        <div class="text-gray-300">|</div>
                        <div>
                            Dormant shops (no order 14+ days): <strong class="color-violation" id="valDormantShops">0 ▲</strong>
                        </div>
                        <div class="text-gray-300">|</div>
                        <div>
                            Active outlets: <strong class="text-gray-900" id="valActiveOutlets">0 / 0</strong>
                        </div>
                    </div>
                </div>

                <!-- 2. Top 5 Highest Sale Retail Shops -->
                <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
                    <div class="bg-amber-50/50 px-5 py-3 border-b border-orange-200">
                        <span class="section-title">2. TOP 5 HIGHEST SALE RETAIL SHOPS <span class="text-gray-400 font-normal lowercase">(this week)</span></span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full table-simple">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%;">#</th>
                                    <th style="width: 42%;">Outlet</th>
                                    <th class="text-right" style="width: 20%;">Sales (Rs.)</th>
                                    <th class="text-right" style="width: 15%;">% of Agent Sales</th>
                                    <th class="text-right" style="width: 15%;">vs Last Week</th>
                                </tr>
                            </thead>
                            <tbody id="tblTopSalesShops">
                                <!-- JS populated -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Top 5 Fast Moving SKUs -->
                <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
                    <div class="bg-amber-50/50 px-5 py-3 border-b border-orange-200">
                        <span class="section-title">3. TOP 5 FAST MOVING SKUS <span class="text-gray-400 font-normal lowercase">(ranked by units sold this week)</span></span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full table-simple">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 6%;">#</th>
                                    <th style="width: 34%;">SKU</th>
                                    <th class="text-right" style="width: 15%;">Units Sold</th>
                                    <th class="text-right" style="width: 15%;">Units / Working Day</th>
                                    <th class="text-right" style="width: 15%;">Sales (Rs.)</th>
                                    <th class="text-right" style="width: 15%;">% of Agent Sales</th>
                                </tr>
                            </thead>
                            <tbody id="tblTopSkus">
                                <!-- JS populated -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 11. Legend & Footer matching Mockup 1 -->
            <div class="pt-4 border-t border-orange-200 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 gap-3 pb-8">
                <div class="flex items-center gap-5 font-bold">
                    <span class="flex items-center gap-1.5 text-red-600">
                        <span class="w-3 h-3 bg-red-600 rounded-sm inline-block"></span> Rule violation
                    </span>
                    <span class="flex items-center gap-1.5 text-amber-600">
                        <span class="w-3 h-3 bg-amber-600 rounded-sm inline-block"></span> Warning
                    </span>
                    <span class="flex items-center gap-1.5 text-emerald-600">
                        <span class="w-3 h-3 bg-emerald-600 rounded-sm inline-block"></span> On track
                    </span>
                </div>
                <div class="text-gray-400 font-medium" id="lblReportFooter">
                    Dimuthu Bakers v2.0.1 — Report A — Page 1 of 2 (Agent: Greenglobal)
                </div>
            </div>

        </div>
    </div>

    <script>
        const formatMoney = (amount) => Math.round(Number(amount) || 0).toLocaleString('en-US');
        const formatDecimal = (amount, dec = 1) => (Number(amount) || 0).toFixed(dec);

        let fpPicker = null;

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Flatpickr for Week selection
            fpPicker = flatpickr("#week_picker", {
                mode: "single",
                dateFormat: "Y-m-d",
                defaultDate: "today",
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates.length > 0) {
                        setWeekFromDate(selectedDates[0]);
                    }
                }
            });

            // Set default week (Monday to Sunday)
            setWeekFromDate(new Date());

            // Select Agent if one is available
            const agentSelect = document.getElementById('agent_id');
            if (agentSelect.options.length > 1) {
                // If Greenglobal exists, select it by default, else option 1
                for (let i = 0; i < agentSelect.options.length; i++) {
                    if (agentSelect.options[i].text.toLowerCase().includes('greenglobal')) {
                        agentSelect.selectedIndex = i;
                        break;
                    }
                }
                if (agentSelect.selectedIndex <= 0) {
                    agentSelect.selectedIndex = 1;
                }
            }
        });

        function setWeekFromDate(date) {
            const d = new Date(date);
            const dayOfWeek = d.getDay() || 7; // Monday = 1, Sunday = 7
            
            // Calculate Monday
            const monday = new Date(d);
            monday.setDate(d.getDate() - dayOfWeek + 1);
            
            // Calculate Sunday
            const sunday = new Date(monday);
            sunday.setDate(monday.getDate() + 6);

            const sStr = monday.toISOString().split('T')[0];
            const eStr = sunday.toISOString().split('T')[0];

            document.getElementById('start_date').value = sStr;
            document.getElementById('end_date').value = eStr;

            // Calculate week number
            const target = new Date(monday.valueOf());
            const dayNr = (monday.getDay() + 6) % 7;
            target.setDate(target.getDate() - dayNr + 3);
            const firstThursday = target.valueOf();
            target.setMonth(0, 1);
            if (target.getDay() !== 4) {
                target.setMonth(0, 1 + ((4 - target.getDay()) + 7) % 7);
            }
            const weekNum = 1 + Math.ceil((firstThursday - target) / 604800000);

            const sFmt = monday.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' });
            const eFmt = sunday.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
            document.getElementById('week_picker').value = `${sFmt} - ${eFmt} (Week ${weekNum})`;
        }

        document.getElementById('reportFilterForm').addEventListener('submit', function (e) {
            e.preventDefault();
            loadReportData();
        });

        function loadReportData() {
            const agentId = document.getElementById('agent_id').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            if (!agentId) {
                Swal.fire('Warning', 'Please select an agent first.', 'warning');
                return;
            }

            document.getElementById('dashboardContainer').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.remove('hidden');

            const formData = new FormData();
            formData.append('agent_id', agentId);
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('reports.weeklyAgentReview.data') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    if (res.success) {
                        populateDashboard(res);
                        document.getElementById('dashboardContainer').classList.remove('hidden');
                    } else {
                        Swal.fire('Error', res.message || 'Failed to compile report.', 'error');
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

        function populateDashboard(res) {
            // 1. Header Banner & Subheader
            document.getElementById('lblDateRange').innerText = res.week_info.date_range_label;
            document.getElementById('lblGenerated').innerText = `Generated: ${res.week_info.generated_at}`;

            document.getElementById('lblAgentName').innerText = `${res.agent.name} (${res.agent.code})`;
            document.getElementById('lblRoutes').innerText = res.agent.routes;
            document.getElementById('lblMonthlyTarget').innerText = `Rs. ${formatMoney(res.monthly_target)}`;
            document.getElementById('lblReportFooter').innerText = `Dimuthu Bakers v2.0.1 — Report A — Page 1 of 2 (Agent: ${res.agent.name})`;

            // 2. Top 4 KPI Cards
            // Weekly Sales
            document.getElementById('valWeeklySales').innerText = `Rs. ${formatMoney(res.weekly_sales.amount)}`;
            const sTgtEl = document.getElementById('valWeeklyTargetPct');
            sTgtEl.innerText = `${res.weekly_sales.target_percent}% of weekly target ▲`;
            if (res.weekly_sales.target_percent >= 90) {
                sTgtEl.className = 'color-track';
                document.getElementById('barWeeklySales').className = 'bar-track';
            } else if (res.weekly_sales.target_percent >= 75) {
                sTgtEl.className = 'color-warning';
                document.getElementById('barWeeklySales').className = 'bar-warning';
            } else {
                sTgtEl.className = 'color-violation';
                document.getElementById('barWeeklySales').className = 'bar-violation';
            }

            const vsLastWeek = Number(res.weekly_sales.vs_last_week_percent) || 0;
            const vsLastEl = document.getElementById('valSalesVsLastWeek');
            vsLastEl.innerText = `${vsLastWeek >= 0 ? '+' : ''}${vsLastWeek}% vs last week`;
            vsLastEl.className = vsLastWeek >= 0 ? 'color-track' : 'color-violation';

            // Visit Compliance
            document.getElementById('valVisitCompliance').innerText = `${res.visit_compliance.percent}%`;
            document.getElementById('valVisitDesc').innerText = `${res.visit_compliance.visited} of ${res.visit_compliance.total} outlets visited`;
            if (res.visit_compliance.percent >= 90) {
                document.getElementById('barVisitCompliance').className = 'bar-track';
            } else if (res.visit_compliance.percent >= 80) {
                document.getElementById('barVisitCompliance').className = 'bar-warning';
            } else {
                document.getElementById('barVisitCompliance').className = 'bar-violation';
            }

            // Return %
            document.getElementById('valReturnPercent').innerText = `${formatDecimal(res.returns.percent)}%`;
            const retDesc = document.getElementById('valReturnDesc');
            if (res.returns.is_above_threshold) {
                retDesc.innerHTML = `<span class="color-violation">Above 5% threshold ▲</span>`;
                document.getElementById('barReturnPercent').className = 'bar-violation';
            } else {
                retDesc.innerHTML = `<span class="color-track">On track ✓</span>`;
                document.getElementById('barReturnPercent').className = 'bar-track';
            }

            // Credit Utilization
            document.getElementById('valCreditUtil').innerText = `${res.credit_utilization.percent}%`;
            document.getElementById('valCreditDesc').innerText = `Rs. ${formatMoney(res.credit_utilization.used)} / ${formatMoney(res.credit_utilization.limit)}`;
            if (res.credit_utilization.percent > 90) {
                document.getElementById('barCreditUtil').className = 'bar-violation';
            } else if (res.credit_utilization.percent > 75) {
                document.getElementById('barCreditUtil').className = 'bar-warning';
            } else {
                document.getElementById('barCreditUtil').className = 'bar-track';
            }

            // 3. Monthly Target Run-Rate Card
            document.getElementById('valRemainingTarget').innerText = `Rs. ${formatMoney(res.target_progress.remaining)}`;
            document.getElementById('valMtdSales').innerText = `Rs. ${formatMoney(res.target_progress.mtd_sales)}`;
            document.getElementById('valMtdTarget').innerText = `Rs. ${formatMoney(res.target_progress.target)}`;
            document.getElementById('valPaceWeeks').innerText = `Weeks left in ${res.target_progress.month_name}: ${res.target_progress.weeks_left} • Working days left: ${res.target_progress.working_days_left}`;
            document.getElementById('valReqWeeklyPace').innerText = `Rs. ${formatMoney(res.target_progress.required_weekly_run_rate)}`;
            document.getElementById('valReqDailyPace').innerText = `Rs. ${formatMoney(res.target_progress.required_daily_run_rate)}`;

            const rrBadge = document.getElementById('badgeRunRateStatus');
            if (res.target_progress.is_on_track) {
                rrBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-300';
                rrBadge.innerHTML = `<i class="bi bi-check-circle-fill"></i> On track to achieve ✓`;
            } else {
                rrBadge.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-300';
                rrBadge.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> At risk of missing ▲`;
            }

            // 4. Daily Outlet Visits Table
            const tbodyVisits = document.getElementById('tblDailyVisits');
            tbodyVisits.innerHTML = '';
            res.daily_visits.forEach(item => {
                const isOk = item.status === 'OK';
                tbodyVisits.innerHTML += `
                    <tr class="${!isOk ? 'row-violation' : ''}">
                        <td class="font-bold">${item.day}</td>
                        <td class="text-center font-extrabold ${!isOk ? 'color-violation' : ''}">${item.outlets_visited}</td>
                        <td>
                            ${isOk 
                                ? `<span class="color-track">✓ OK</span>` 
                                : `<span class="color-violation">✗ Below 30</span>`
                            }
                        </td>
                    </tr>
                `;
            });
            document.getElementById('valRoutesRun').innerText = res.routes_run;
            document.getElementById('valMissedOutlets').innerText = res.missed_outlets;

            // 5. Returns Section
            // Agent -> Bakery Returns Table
            const tbodyAgentBakeryRet = document.getElementById('tblAgentBakeryReturns');
            tbodyAgentBakeryRet.innerHTML = '';
            res.agent_bakery_returns.reasons.forEach(r => {
                tbodyAgentBakeryRet.innerHTML += `
                    <tr>
                        <td class="font-medium">${r.reason}</td>
                        <td class="text-right font-bold">${formatMoney(r.value)}</td>
                        <td class="text-right ${r.percent > 5 ? 'color-violation' : 'text-gray-600'}">${formatDecimal(r.percent)}%</td>
                    </tr>
                `;
            });
            // Total Row
            tbodyAgentBakeryRet.innerHTML += `
                <tr class="bg-amber-50/50 font-black">
                    <td class="text-amber-950">Total</td>
                    <td class="text-right text-amber-950 font-black">${formatMoney(res.agent_bakery_returns.total)}</td>
                    <td class="text-right ${res.agent_bakery_returns.percent_of_sales > 5 ? 'color-violation' : 'color-track'}">${formatDecimal(res.agent_bakery_returns.percent_of_sales)}%</td>
                </tr>
            `;
            document.getElementById('valTopReturnProd').innerText = res.agent_bakery_returns.top_product_name;
            document.getElementById('valTopReturnQty').innerText = res.agent_bakery_returns.top_product_qty;

            // Retail Shop -> Agent Returns Table
            const tbodyShopAgentRet = document.getElementById('tblShopAgentReturns');
            tbodyShopAgentRet.innerHTML = '';
            if (res.shop_agent_returns.items && res.shop_agent_returns.items.length > 0) {
                res.shop_agent_returns.items.forEach(sr => {
                    tbodyShopAgentRet.innerHTML += `
                        <tr>
                            <td class="font-medium">${sr.outlet}</td>
                            <td class="text-right font-bold">${formatMoney(sr.value)}</td>
                            <td class="text-gray-600">${sr.reason}</td>
                        </tr>
                    `;
                });
            } else {
                tbodyShopAgentRet.innerHTML = `<tr><td colspan="3" class="text-center text-gray-400 py-3">No direct shop returns recorded this week</td></tr>`;
            }
            tbodyShopAgentRet.innerHTML += `
                <tr class="bg-amber-50/50 font-black">
                    <td class="text-amber-950">Total</td>
                    <td class="text-right text-amber-950 font-black">${formatMoney(res.shop_agent_returns.total)}</td>
                    <td></td>
                </tr>
            `;

            // 6. Credit & Collections
            document.getElementById('valBakeryCreditSales').innerText = `Rs. ${formatMoney(res.agent_bakery_credit.credit_sales)}`;
            document.getElementById('valBakeryCollections').innerText = `Rs. ${formatMoney(res.agent_bakery_credit.collections)}`;
            document.getElementById('valBakeryColRate').innerText = `(${res.agent_bakery_credit.collection_rate}% collection rate)`;
            document.getElementById('valBakeryClosingDues').innerText = `Rs. ${formatMoney(res.agent_bakery_credit.closing_dues)}`;
            document.getElementById('valBakeryCreditPeriod').innerText = `${res.agent_bakery_credit.credit_period} days`;
            document.getElementById('valBakeryAged14').innerText = `Rs. ${formatMoney(res.agent_bakery_credit.aged_14_days)} ▲`;

            document.getElementById('valOutletCreditIssued').innerText = `Rs. ${formatMoney(res.shop_credit.credit_issued)}`;
            document.getElementById('valOutletCollected').innerText = `Rs. ${formatMoney(res.shop_credit.collected)}`;
            document.getElementById('valOutletColRate').innerText = `(${res.shop_credit.collection_rate}% collection rate)`;
            document.getElementById('valOutletOutstanding').innerText = `Rs. ${formatMoney(res.shop_credit.outstanding_dues)}`;
            document.getElementById('valOutletAged14').innerText = `Rs. ${formatMoney(res.shop_credit.aged_14_days)} ▲`;
            document.getElementById('valOverTwoBillsCount').innerText = `${res.shop_credit.over_two_bills_count} ▲`;

            // 7. Outlets with More than 2 Credit Bills
            const tbodyOverTwo = document.getElementById('tblOverTwoBills');
            tbodyOverTwo.innerHTML = '';
            if (res.outlets_over_two_bills && res.outlets_over_two_bills.length > 0) {
                res.outlets_over_two_bills.forEach(ot => {
                    tbodyOverTwo.innerHTML += `
                        <tr class="row-violation">
                            <td class="font-bold text-gray-900">${ot.outlet}</td>
                            <td class="text-center font-black color-violation">${ot.open_bills}</td>
                            <td class="text-right font-black color-violation">${formatMoney(ot.balance)}</td>
                            <td class="text-right font-medium text-gray-700">${Math.round(Math.abs(Number(ot.days_outstanding) || 0))} days</td>
                        </tr>
                    `;
                });
            } else {
                tbodyOverTwo.innerHTML = `<tr><td colspan="4" class="text-center text-gray-400 py-3">No outlets currently have more than 2 open credit bills (Bill-to-bill policy respected).</td></tr>`;
            }

            // 8. Top 5 Highest Credit Balance Retail Shops
            const tbodyCreditShops = document.getElementById('tblTopCreditShops');
            tbodyCreditShops.innerHTML = '';
            if (res.top_credit_shops && res.top_credit_shops.length > 0) {
                res.top_credit_shops.forEach((cs, idx) => {
                    let statusClass = 'color-track';
                    let rowClass = '';
                    if (cs.status === 'Overdue' || cs.status.includes('Overdue')) {
                        statusClass = 'color-violation';
                        rowClass = 'row-violation';
                    } else if (cs.status === 'Near limit') {
                        statusClass = 'color-warning';
                    }
                    const daysInt = Math.round(Math.abs(Number(cs.days_outstanding) || 0));

                    tbodyCreditShops.innerHTML += `
                        <tr class="${rowClass}">
                            <td class="text-center font-bold text-gray-500">${idx + 1}</td>
                            <td class="font-bold text-gray-900">${cs.outlet}</td>
                            <td class="text-right font-black ${statusClass}">${formatMoney(cs.balance)}</td>
                            <td class="text-right font-bold text-gray-800">${daysInt}</td>
                            <td class="text-center ${statusClass}">
                                ${cs.status.includes('Overdue') ? '▲ Overdue' : cs.status}
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbodyCreditShops.innerHTML = `<tr><td colspan="5" class="text-center text-gray-400 py-3">No outstanding balances across outlets.</td></tr>`;
            }

            // 9. Outlet Growth
            document.getElementById('valNewShopsCount').innerText = `+${res.outlet_growth.new_shops}`;
            if (res.outlet_growth.new_shop_names && res.outlet_growth.new_shop_names.length > 0) {
                document.getElementById('valNewShopsList').innerText = `(${res.outlet_growth.new_shop_names.slice(0, 3).join(' + ')}${res.outlet_growth.new_shop_names.length > 3 ? ' + ' + (res.outlet_growth.new_shop_names.length - 3) + ' more' : ''})`;
            } else {
                document.getElementById('valNewShopsList').innerText = '';
            }
            document.getElementById('valDormantShops').innerText = `${res.outlet_growth.dormant_shops} ▲`;
            document.getElementById('valActiveOutlets').innerText = `${res.outlet_growth.active_outlets} / ${res.outlet_growth.total_outlets}`;

            // 10. Top 5 Highest Sale Retail Shops
            const tbodySalesShops = document.getElementById('tblTopSalesShops');
            tbodySalesShops.innerHTML = '';
            if (res.top_sales_shops && res.top_sales_shops.length > 0) {
                res.top_sales_shops.forEach((ss, idx) => {
                    const vsLast = Number(ss.vs_last_week_percent) || 0;
                    tbodySalesShops.innerHTML += `
                        <tr>
                            <td class="text-center font-bold text-gray-500">${idx + 1}</td>
                            <td class="font-bold text-gray-900">${ss.outlet}</td>
                            <td class="text-right font-black text-gray-900">${formatMoney(ss.sales)}</td>
                            <td class="text-right font-semibold text-gray-600">${formatDecimal(ss.percent_of_agent_sales)}%</td>
                            <td class="text-right ${vsLast >= 0 ? 'color-track' : 'color-violation'}">
                                ${vsLast >= 0 ? '▲ +' : '▼ '}${formatDecimal(vsLast)}%
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbodySalesShops.innerHTML = `<tr><td colspan="5" class="text-center text-gray-400 py-3">No sales recorded this week.</td></tr>`;
            }

            // 11. Top 5 Fast Moving SKUs
            const tbodySkus = document.getElementById('tblTopSkus');
            tbodySkus.innerHTML = '';
            if (res.top_skus && res.top_skus.length > 0) {
                res.top_skus.forEach((sku, idx) => {
                    tbodySkus.innerHTML += `
                        <tr>
                            <td class="text-center font-bold text-gray-500">${idx + 1}</td>
                            <td class="font-bold text-gray-900">${sku.sku}</td>
                            <td class="text-right font-black text-gray-900">${formatMoney(sku.units_sold)}</td>
                            <td class="text-right font-semibold text-gray-700">${formatMoney(sku.units_per_day)}</td>
                            <td class="text-right font-black text-gray-900">${formatMoney(sku.sales)}</td>
                            <td class="text-right font-semibold text-gray-600">${formatDecimal(sku.percent_of_agent_sales)}%</td>
                        </tr>
                    `;
                });
            } else {
                tbodySkus.innerHTML = `<tr><td colspan="6" class="text-center text-gray-400 py-3">No product items sold this week.</td></tr>`;
            }
        }

        function exportToPDF() {
            const agentId = document.getElementById('agent_id').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            if (!agentId || !startDate || !endDate) {
                Swal.fire('Warning', 'Please select an agent and week first.', 'warning');
                return;
            }

            const url = `{{ route('reports.weeklyAgentReview.exportPdf') }}?agent_id=${agentId}&start_date=${startDate}&end_date=${endDate}`;
            window.open(url, '_blank');
        }
    </script>
@endsection
