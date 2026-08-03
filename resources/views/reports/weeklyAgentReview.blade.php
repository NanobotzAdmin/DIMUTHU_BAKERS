@extends('layouts.app')

@section('content')

    <style>
        .report-header {
            background-color: #b45309; /* Amber 700 */
            color: white;
        }
        
        .metric-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.25rem;
            background: white;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }
        
        .metric-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .metric-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }
        
        .large-metric-card {
            border: 2px solid #d97706; /* Amber 600 */
            background: #fffbeb; /* Amber 50 */
        }
        
        .table-simple th {
            font-size: 0.8rem;
            background-color: #f8fafc;
            color: #64748b;
            padding: 0.75rem 1rem;
            text-transform: uppercase;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        
        .table-simple td {
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        
        .table-simple tr:last-child td {
            border-bottom: none;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Weekly Agent Review Filters</h2>
            <form id="reportFilterForm" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
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
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1">Start Date</label>
                    <input type="date" id="start_date" name="start_date" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2.5">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2.5">
                </div>
                <div>
                    <button type="submit" id="btnLoadReport"
                        class="w-full flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-amber-700 hover:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                        <i class="bi bi-funnel mr-2"></i> Load Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden py-12 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-amber-600"></div>
            <p class="mt-2 text-sm text-gray-500 font-medium">Generating review...</p>
        </div>

        <!-- Dashboard Container -->
        <div id="dashboardContainer" class="hidden">
            <!-- Header -->
            <div class="report-header rounded-t-xl p-6 flex justify-between items-end">
                <div>
                    <div class="text-xs uppercase tracking-widest font-semibold mb-1 opacity-80">Dimuthu Bake House — Agent Review</div>
                    <h1 class="text-3xl font-bold tracking-tight">Weekly Agent Review</h1>
                </div>
                <div class="text-right text-sm">
                    <div class="font-medium" id="lblDateRange">13 - 19 Jul 2026</div>
                    <div class="opacity-80 mt-1">Generated: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <!-- Sub Header -->
            <div class="bg-amber-50 border-x border-b border-amber-200 p-4 mb-6 rounded-b-xl text-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <span class="font-semibold text-amber-900">Agent:</span> <span class="font-bold text-gray-900" id="lblAgentName">Agent Name (Code)</span>
                    </div>
                    <div>
                        <span class="font-semibold text-amber-900">Routes:</span> <span class="text-gray-900" id="lblRoutes">Routes List</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="font-semibold text-amber-900">Monthly Target:</span> <span class="font-bold text-gray-900" id="lblMonthlyTarget">Rs. 0</span> <span class="text-gray-500 italic">(entered by management)</span>
                    </div>
                </div>
            </div>

            <!-- 4 Metrics Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Weekly Sales -->
                <div class="metric-card">
                    <div class="metric-title">Weekly Sales</div>
                    <div class="metric-value" id="valWeeklySales">Rs. 0</div>
                </div>

                <!-- Visit Compliance -->
                <div class="metric-card">
                    <div class="metric-title">Visit Compliance</div>
                    <div class="metric-value" id="valVisitCompliance">0%</div>
                    <div class="text-xs font-medium text-amber-600 mt-2" id="valVisitDesc">0 of 0 outlets visited</div>
                </div>

                <!-- Return % -->
                <div class="metric-card">
                    <div class="metric-title">Return %</div>
                    <div class="metric-value text-rose-600" id="valReturnPercent">0%</div>
                    <div class="text-xs font-medium text-rose-600 mt-2" id="valReturnDesc">Above 5% threshold</div>
                </div>

                <!-- Credit Utilization -->
                <div class="metric-card">
                    <div class="metric-title">Credit Utilization</div>
                    <div class="metric-value text-amber-600" id="valCreditUtil">0%</div>
                    <div class="text-xs font-medium text-gray-500 mt-2" id="valCreditDesc">Rs. 0 / 0</div>
                </div>
            </div>

            <!-- Large Target Metric -->
            <div class="metric-card large-metric-card mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="metric-title text-amber-800">Remaining to Monthly Target</div>
                        <div class="text-4xl font-black text-amber-700 mt-1" id="valRemainingTarget">Rs. 0</div>
                        <div class="text-sm text-gray-600 mt-2">MTD sales <span id="valMtdSales" class="font-semibold">Rs. 0</span> of <span id="valMtdTarget" class="font-semibold">Rs. 0</span> target</div>
                    </div>
                </div>
            </div>

            <!-- Two Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Daily Outlet Visits -->
                <div>
                    <div class="flex justify-between items-end mb-3">
                        <h3 class="text-amber-800 font-bold uppercase tracking-wider text-sm">Daily Outlet Visits <span class="text-gray-400 text-xs normal-case font-normal">(minimum 30 per day)</span></h3>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        <table class="w-full table-simple">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th class="text-center">Outlets Visited</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tblDailyVisits">
                                <!-- JS Injected -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Returns By Reason -->
                <div>
                    <div class="flex justify-between items-end mb-3">
                        <h3 class="text-amber-800 font-bold uppercase tracking-wider text-sm">Returns By Reason <span class="text-gray-400 text-xs normal-case font-normal">(manufacturing defects only allowed)</span></h3>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm mb-3">
                        <table class="w-full table-simple">
                            <thead>
                                <tr>
                                    <th>Reason</th>
                                    <th class="text-right">Value (Rs.)</th>
                                    <th class="text-right">% of Sales</th>
                                </tr>
                            </thead>
                            <tbody id="tblReturnsByReason">
                                <!-- JS Injected -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-sm text-gray-600">
                        Top returned product: <strong class="text-gray-800" id="valTopReturnProduct">None</strong> — <span id="valTopReturnQty">0</span> units
                    </div>
                </div>
            </div>

            <!-- Bottom Two Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-8">
                <!-- Credit & Collections -->
                <div class="metric-card">
                    <h3 class="text-amber-800 font-bold uppercase tracking-wider text-sm mb-4">Credit & Collections</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-600">Credit sales this week:</span>
                            <span class="font-bold text-gray-900" id="valCreditSalesThisWeek">Rs. 0</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-600">Collections this week:</span>
                            <span class="font-bold text-gray-900"><span id="valCollectionsThisWeek">Rs. 0</span> <span class="text-emerald-600 font-medium" id="valCollectionRate">(0% collection rate)</span></span>
                        </div>
                        <div class="flex justify-between pt-1">
                            <span class="text-gray-600">Closing dues:</span>
                            <span><strong class="text-gray-900" id="valClosingDues">Rs. 0</strong> <span class="text-gray-400 mx-1">|</span> <span class="text-gray-500">Aged 30+ days:</span> <strong class="text-rose-600" id="valAged30Days">Rs. 0</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Outlet Growth -->
                <div class="metric-card">
                    <h3 class="text-amber-800 font-bold uppercase tracking-wider text-sm mb-4">Outlet Growth</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-600">New shops added:</span>
                            <span class="font-bold text-emerald-600" id="valNewShops">+0</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-600">Dormant shops (no order 14+ days):</span>
                            <span class="font-bold text-rose-600" id="valDormantShops">0</span>
                        </div>
                        <div class="flex justify-between pt-1">
                            <span class="text-gray-600">Active outlets:</span>
                            <span class="font-bold text-gray-900" id="valActiveOutlets">0 / 0</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const formatMoney = (amount) => Number(amount).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        const formatMoneyDecimals = (amount) => Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        document.getElementById('reportFilterForm').addEventListener('submit', function (e) {
            e.preventDefault();
            loadReportData();
        });

        // Set default dates to this week (Monday to Sunday) on load
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date();
            const dayOfWeek = today.getDay() || 7; // Treat Sunday (0) as 7
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - dayOfWeek + 1);
            const endOfWeek = new Date(today);
            endOfWeek.setDate(today.getDate() - dayOfWeek + 7);

            document.getElementById('start_date').value = startOfWeek.toISOString().split('T')[0];
            document.getElementById('end_date').value = endOfWeek.toISOString().split('T')[0];
        });

        function loadReportData() {
            const formData = new FormData(document.getElementById('reportFilterForm'));
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            document.getElementById('dashboardContainer').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.remove('hidden');

            $.ajax({
                url: "{{ route('reports.weeklyAgentReview.data') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    document.getElementById('loadingIndicator').classList.add('hidden');

                    if (response.success) {
                        populateDashboard(response, startDate, endDate);
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

        function populateDashboard(res, startDate, endDate) {
            // Header Info
            const sDate = new Date(startDate).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
            const eDate = new Date(endDate).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
            document.getElementById('lblDateRange').innerText = `${sDate} - ${eDate}`;
            
            document.getElementById('lblAgentName').innerText = `${res.agent.name} (${res.agent.code})`;
            document.getElementById('lblRoutes').innerText = res.agent.routes;
            document.getElementById('lblMonthlyTarget').innerText = `Rs. ${formatMoney(res.monthly_target)}`;

            // 4 Metrics
            document.getElementById('valWeeklySales').innerText = `Rs. ${formatMoney(res.weekly_sales)}`;
            document.getElementById('valVisitCompliance').innerText = `${res.visit_compliance.percent}%`;
            document.getElementById('valVisitDesc').innerText = `${res.visit_compliance.visited} of ${res.visit_compliance.total} outlets visited`;
            
            const retEl = document.getElementById('valReturnPercent');
            const retDescEl = document.getElementById('valReturnDesc');
            retEl.innerText = `${res.returns.percent}%`;
            if (res.returns.percent > 5) {
                retEl.className = 'metric-value text-rose-600';
                retDescEl.className = 'text-xs font-medium text-rose-600 mt-2';
                retDescEl.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Above 5% threshold`;
            } else {
                retEl.className = 'metric-value text-emerald-600';
                retDescEl.className = 'text-xs font-medium text-emerald-600 mt-2';
                retDescEl.innerHTML = `<i class="bi bi-check-circle"></i> Within threshold`;
            }

            document.getElementById('valCreditUtil').innerText = `${res.credit_utilization.percent}%`;
            document.getElementById('valCreditDesc').innerText = `Rs. ${formatMoney(res.credit_utilization.used)} / ${formatMoney(res.credit_utilization.limit)}`;

            // Target
            document.getElementById('valRemainingTarget').innerText = `Rs. ${formatMoney(res.target_progress.remaining)}`;
            document.getElementById('valMtdSales').innerText = `Rs. ${formatMoney(res.target_progress.mtd_sales)}`;
            document.getElementById('valMtdTarget').innerText = `Rs. ${formatMoney(res.target_progress.target)}`;

            // Daily Visits Table
            const tbodyVisits = document.getElementById('tblDailyVisits');
            tbodyVisits.innerHTML = '';
            res.daily_visits.forEach(day => {
                let statusHtml = day.status === 'OK' 
                    ? `<span class="text-emerald-600 font-bold"><i class="bi bi-check2"></i> OK</span>`
                    : `<span class="text-rose-600 font-bold"><i class="bi bi-x-lg"></i> Below 30</span>`;
                
                tbodyVisits.innerHTML += `
                    <tr>
                        <td>${day.day}</td>
                        <td class="text-center font-semibold ${day.status !== 'OK' ? 'text-rose-600' : ''}">${day.outlets_visited}</td>
                        <td>${statusHtml}</td>
                    </tr>
                `;
            });

            // Returns Table
            const tbodyReturns = document.getElementById('tblReturnsByReason');
            tbodyReturns.innerHTML = '';
            res.returns_by_reason.forEach(reason => {
                tbodyReturns.innerHTML += `
                    <tr>
                        <td>${reason.reason}</td>
                        <td class="text-right font-medium">${formatMoney(reason.value)}</td>
                        <td class="text-right font-bold ${reason.percent > 1 ? 'text-rose-600' : 'text-gray-600'}">${reason.percent}%</td>
                    </tr>
                `;
            });
            if (res.returns_by_reason.length === 0) {
                tbodyReturns.innerHTML = `<tr><td colspan="3" class="text-center text-gray-400 py-4">No returns recorded</td></tr>`;
            }

            document.getElementById('valTopReturnProduct').innerText = res.top_return_product.name;
            document.getElementById('valTopReturnQty').innerText = res.top_return_product.qty;

            // Credit & Collections
            document.getElementById('valCreditSalesThisWeek').innerText = `Rs. ${formatMoney(res.credit_collections.credit_sales)}`;
            document.getElementById('valCollectionsThisWeek').innerText = `Rs. ${formatMoney(res.credit_collections.collections)}`;
            document.getElementById('valCollectionRate').innerText = `(${res.credit_collections.collection_rate}% collection rate)`;
            document.getElementById('valClosingDues').innerText = `Rs. ${formatMoney(res.credit_collections.closing_dues)}`;
            document.getElementById('valAged30Days').innerText = `Rs. ${formatMoney(res.credit_collections.aged_30_days)}`;

            // Outlet Growth
            document.getElementById('valNewShops').innerText = `+${res.outlet_growth.new_shops}`;
            document.getElementById('valDormantShops').innerText = res.outlet_growth.dormant_shops;
            document.getElementById('valActiveOutlets').innerText = `${res.outlet_growth.active_outlets} / ${res.outlet_growth.total_outlets}`;
        }
    </script>
@endsection
