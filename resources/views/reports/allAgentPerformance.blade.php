@extends('layouts.app')

@section('content')

    <style>
        .report-header {
            background-color: #b45309; /* Amber 700 */
            color: white;
        }
        
        .metric-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1.25rem;
            background: white;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }
        
        .table-simple th {
            font-size: 0.75rem;
            background-color: #fffbeb; /* Amber 50 */
            color: #b45309; /* Amber 700 */
            padding: 0.6rem 0.75rem;
            text-transform: uppercase;
            font-weight: 800;
            border-bottom: 2px solid #fcd34d; /* Amber 300 */
            border-right: 1px solid #fef3c7;
            text-align: left;
            vertical-align: bottom;
        }
        
        .table-simple td {
            font-size: 0.85rem;
            padding: 0.75rem 0.75rem;
            border-bottom: 1px solid #f1f5f9;
            border-right: 1px solid #f8fafc;
            color: #334155;
            vertical-align: middle;
        }
        
        .table-simple tr:last-child td {
            border-bottom: none;
        }
        
        .section-title {
            color: #b45309; /* Amber 700 */
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }
        
        .badge-ramp {
            background-color: #fef3c7; /* Amber 100 */
            color: #d97706; /* Amber 600 */
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0.1rem 0.4rem;
            border-radius: 9999px;
            margin-left: 0.5rem;
            display: inline-block;
        }
        
        .badge-qualified {
            background-color: #dcfce7; /* Green 100 */
            color: #166534; /* Green 800 */
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            text-align: center;
            display: inline-block;
            width: 100%;
        }
        
        .badge-unqualified {
            background-color: #fff1f2; /* Rose 100 */
            color: #9f1239; /* Rose 800 */
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            text-align: center;
            display: inline-block;
            width: 100%;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">All Agent Performance Filters</h2>
            <form id="reportFilterForm" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                @csrf
                <div>
                    <label for="month" class="block text-sm font-semibold text-gray-700 mb-1">Select Month</label>
                    <input type="month" id="month" name="month" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm p-2.5">
                </div>
                <div>
                    <button type="submit" id="btnLoadReport"
                        class="w-full flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-amber-700 hover:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all">
                        <i class="bi bi-funnel mr-2"></i> Generate Report
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
        <div id="dashboardContainer" class="hidden max-w-6xl mx-auto bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="report-header p-6 flex justify-between items-end">
                <div>
                    <div class="text-xs uppercase tracking-widest font-semibold mb-1 opacity-80">Dimuthu Bake House — Agent Review</div>
                    <h1 class="text-3xl font-bold tracking-tight">All Agent Performance Report</h1>
                </div>
                <div class="text-right text-sm">
                    <div class="font-bold text-lg" id="lblMonthYear">July 2026</div>
                    <div class="opacity-80 mt-1">Generated: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</div>
                </div>
            </div>

            <!-- Leaderboard -->
            <div class="p-0 border-b border-gray-200">
                <div class="px-6 py-4 bg-white border-b border-gray-100 flex items-center justify-between">
                    <h2 class="section-title mb-0 flex-1">AGENT LEADERBOARD <span class="text-gray-400 font-normal normal-case ml-2">(sorted by achievement %)</span></h2>
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
                    M1 / M2 badges mark agents in ramp-up months (targets set manually by management). 5% bonus requires 100% achievement at all three levels — total, category, and SKU — calculated on net sales.
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-6">
                    <!-- Return % Trend -->
                    <div>
                        <h3 class="section-title">RETURN % TREND <span class="text-gray-400 font-normal normal-case text-xs ml-1">(3 months, team)</span></h3>
                        <table class="w-full table-simple border border-amber-100">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th class="text-right">Defect Returns</th>
                                    <th class="text-right">Other (violations)</th>
                                    <th class="text-right font-black">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tblReturnTrend">
                                <!-- JS Injected -->
                            </tbody>
                        </table>
                        <div class="text-sm text-gray-600 mt-2">Trend improving — two-visits rule and reason codes are working.</div>
                    </div>

                    <!-- Credit Ageing Summary -->
                    <div>
                        <h3 class="section-title">CREDIT AGEING SUMMARY <span class="text-gray-400 font-normal normal-case text-xs ml-1">(all agents, Rs.)</span></h3>
                        <table class="w-full table-simple border border-amber-100">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-2">
                    <!-- Outlet Base Movement -->
                    <div class="metric-card">
                        <h3 class="section-title">OUTLET BASE MOVEMENT (<span id="lblOutletMonth">JULY</span>)</h3>
                        <div class="space-y-4 mt-3 text-sm">
                            <div><span class="text-gray-600">Opening active outlets:</span> <strong class="text-gray-900 text-base" id="valOutOpen">0</strong></div>
                            <div>
                                <span class="text-gray-600">New shops:</span> <strong class="text-emerald-600" id="valOutNew">+0</strong> 
                                <span class="text-gray-400 mx-2">•</span> 
                                <span class="text-gray-600">Newly dormant:</span> <strong class="text-rose-600" id="valOutDormant">0</strong>
                            </div>
                            <div class="pt-2 border-t border-gray-100">
                                <span class="text-gray-600">Closing active outlets:</span> 
                                <strong class="text-gray-900 text-base" id="valOutClose">0</strong> 
                                <span class="text-emerald-600 font-medium" id="valOutGrowth">(+0%)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Month Targets -->
                    <div class="metric-card">
                        <h3 class="section-title">NEXT MONTH TARGETS (<span id="lblNextMonth">AUG</span>) — entered by management</h3>
                        <div class="mt-3 text-sm text-gray-700 space-y-2 leading-relaxed" id="containerNextTargets">
                            <!-- JS Injected -->
                            Greenglobal: <strong>Rs. 6,200,000</strong> • Lakmal: <strong>Rs. 4,500,000</strong><br>
                            Ruwan (M3 → 100%): <strong>Rs. 2,400,000</strong> • Nimal: <strong>Rs. 3,600,000</strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 border-t border-gray-100 text-xs text-gray-400 p-3 flex justify-between">
                <div class="flex gap-4">
                    <span class="flex items-center gap-1 text-rose-600 font-bold"><span class="w-3 h-3 bg-rose-600 inline-block mr-1"></span> Rule violation</span>
                    <span class="flex items-center gap-1 text-amber-600 font-bold"><span class="w-3 h-3 bg-amber-600 inline-block mr-1"></span> Warning</span>
                    <span class="flex items-center gap-1 text-emerald-600 font-bold"><span class="w-3 h-3 bg-emerald-600 inline-block mr-1"></span> On track</span>
                </div>
                <span>Dimuthu Bakers v2.0.1 — Report C — All Agent Performance (<span id="lblFooterMonth">July 2026</span>)</span>
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

            // Leaderboard
            const tblLb = document.getElementById('tblLeaderboard');
            tblLb.innerHTML = '';
            res.leaderboard.forEach((ag, idx) => {
                // Color Logic
                const achieveColor = ag.achievement >= 100 ? 'text-emerald-600 font-bold' : (ag.achievement >= 80 ? 'text-emerald-500 font-bold' : (ag.achievement >= 70 ? 'text-amber-500 font-bold' : 'text-rose-600 font-bold'));
                
                let remainingHtml = '';
                if (ag.achievement >= 100) {
                    remainingHtml = `<div class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded text-xs font-bold text-center inline-block">Achieved <i class="bi bi-check2"></i></div>`;
                } else {
                    remainingHtml = `<span class="font-bold text-gray-700 ${ag.achievement < 75 ? 'text-rose-600' : ''}">${formatMoney(ag.remaining)}</span>`;
                }

                const visitColor = ag.visit_compliance >= 90 ? 'text-emerald-600 font-bold' : (ag.visit_compliance >= 75 ? 'text-amber-500 font-bold' : 'text-rose-500 font-bold');
                const returnColor = ag.return_percent <= 3.5 ? 'text-emerald-600 font-bold' : (ag.return_percent <= 5.0 ? 'text-emerald-500 font-bold' : 'text-rose-600 font-bold');
                const collColor = ag.collection_percent >= 90 ? 'text-emerald-600 font-bold' : (ag.collection_percent >= 80 ? 'text-amber-500 font-bold' : 'text-amber-500 font-bold'); // as per mockup styling
                const utilColor = ag.credit_util <= 60 ? 'text-emerald-600 font-bold' : (ag.credit_util <= 85 ? 'text-amber-600 font-bold' : 'text-rose-600 font-bold');

                const bonusHtml = ag.bonus_qualified 
                    ? `<div class="badge-qualified">✔ Qualified</div>` 
                    : `<div class="badge-unqualified">✖ ${ag.achievement}%</div>`;

                const badgeHtml = ag.badge ? `<span class="badge-ramp">${ag.badge}</span>` : '';

                tblLb.innerHTML += `
                    <tr class="${idx % 2 === 0 ? 'bg-white' : 'bg-gray-50/50'} hover:bg-amber-50/30 transition-colors">
                        <td class="text-center text-gray-400 font-medium">${idx + 1}</td>
                        <td class="font-bold text-gray-900">${ag.agent_name} ${badgeHtml}</td>
                        <td class="text-right text-gray-700">${formatMoney(ag.sales)}</td>
                        <td class="text-right ${achieveColor}">${ag.achievement}%</td>
                        <td class="text-right">${remainingHtml}</td>
                        <td class="text-right ${visitColor}">${ag.visit_compliance}%</td>
                        <td class="text-right ${returnColor}">${ag.return_percent}%</td>
                        <td class="text-right ${collColor}">${ag.collection_percent}%</td>
                        <td class="text-center text-gray-700 font-medium">${ag.new_shops}</td>
                        <td class="text-right ${utilColor}">${ag.credit_util}%</td>
                        <td class="text-center">${bonusHtml}</td>
                    </tr>
                `;
            });

            // Return Trend
            const tblRet = document.getElementById('tblReturnTrend');
            tblRet.innerHTML = '';
            // Display oldest to newest (array might be newest to oldest, reverse it)
            const trendData = [...res.return_trend].reverse();
            trendData.forEach((rt, idx) => {
                const isLast = idx === trendData.length - 1;
                const totColor = isLast ? 'font-black' : 'font-bold text-gray-800';
                
                // Compare to previous for arrow
                let arrow = '';
                if (idx > 0) {
                    const prevTot = trendData[idx-1].total_pct;
                    if (rt.total_pct < prevTot) arrow = ' ▼';
                    else if (rt.total_pct > prevTot) arrow = ' ▲';
                }

                tblRet.innerHTML += `
                    <tr>
                        <td class="text-gray-700">${rt.month_label}</td>
                        <td class="text-right text-gray-600">${rt.defect_pct}%</td>
                        <td class="text-right ${rt.other_pct > 1.0 ? 'text-rose-600' : 'text-gray-600'}">${rt.other_pct}%</td>
                        <td class="text-right ${totColor}">${rt.total_pct}%${isLast ? arrow : ''}</td>
                    </tr>
                `;
            });

            // Credit Ageing
            const tblCred = document.getElementById('tblCreditAgeing');
            const ca = res.credit_ageing;
            tblCred.innerHTML = `
                <tr>
                    <td class="text-gray-700">Current</td>
                    <td class="text-right text-gray-700">${formatMoney(ca.current.amount)}</td>
                    <td class="text-right text-gray-600">${ca.current.pct}%</td>
                </tr>
                <tr>
                    <td class="text-gray-700">1 &ndash; 30 days</td>
                    <td class="text-right text-gray-700">${formatMoney(ca.days_30.amount)}</td>
                    <td class="text-right text-gray-600">${ca.days_30.pct}%</td>
                </tr>
                <tr>
                    <td class="text-gray-700">31 &ndash; 60 days</td>
                    <td class="text-right text-gray-700">${formatMoney(ca.days_60.amount)}</td>
                    <td class="text-right text-gray-600">${ca.days_60.pct}%</td>
                </tr>
                <tr class="bg-rose-50/50">
                    <td class="text-rose-700 font-bold">60+ days</td>
                    <td class="text-right text-rose-700 font-bold">${formatMoney(ca.days_60_plus.amount)}</td>
                    <td class="text-right text-rose-600 font-bold">${ca.days_60_plus.pct}%</td>
                </tr>
            `;

            // Outlet Movement
            const om = res.outlet_movement;
            document.getElementById('valOutOpen').innerText = om.opening;
            document.getElementById('valOutNew').innerText = `+${om.new_shops}`;
            document.getElementById('valOutDormant').innerText = `-${om.newly_dormant}`;
            document.getElementById('valOutClose').innerText = om.closing;
            
            const gSign = om.growth_pct >= 0 ? '+' : '';
            const gArrow = om.growth_pct >= 0 ? '▲' : '▼';
            const gColor = om.growth_pct >= 0 ? 'text-emerald-600' : 'text-rose-600';
            document.getElementById('valOutGrowth').className = `${gColor} font-medium`;
            document.getElementById('valOutGrowth').innerText = `(${gSign}${Math.abs(om.growth_pct)}% ${gArrow})`;

            // Next Targets
            const ntCont = document.getElementById('containerNextTargets');
            let ntHtml = '';
            // Group by 2 per line for compact display
            for (let i = 0; i < res.next_targets.targets.length; i += 2) {
                const t1 = res.next_targets.targets[i];
                let label1 = t1.agent_name;
                if (t1.badge) label1 += ` (${t1.badge})`;
                let val1 = t1.target === 'Pending' ? 'Pending' : `Rs. ${formatMoney(t1.target)}`;
                
                let lineHtml = `${label1}: <strong>${val1}</strong>`;

                if (i + 1 < res.next_targets.targets.length) {
                    const t2 = res.next_targets.targets[i+1];
                    let label2 = t2.agent_name;
                    if (t2.badge) label2 += ` (${t2.badge})`;
                    let val2 = t2.target === 'Pending' ? 'Pending' : `Rs. ${formatMoney(t2.target)}`;
                    
                    lineHtml += ` <span class="text-gray-400 mx-2">•</span> ${label2}: <strong>${val2}</strong>`;
                }
                ntHtml += `<div>${lineHtml}</div>`;
            }
            if (ntHtml === '') ntHtml = '<div class="text-gray-400 italic">No targets set for next month.</div>';
            ntCont.innerHTML = ntHtml;
        }
    </script>
@endsection
