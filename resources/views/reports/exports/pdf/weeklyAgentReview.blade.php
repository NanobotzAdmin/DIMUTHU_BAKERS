<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Weekly Agent Review - {{ $data['agent']['name'] }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 7.5px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.25;
        }

        .header-bar {
            background-color: #8c4314;
            color: #ffffff;
            padding: 8px 12px;
            margin-bottom: 6px;
        }
        .header-table { width: 100%; border-collapse: collapse; border: none; }
        .header-table td { border: none; padding: 0; vertical-align: middle; }
        .company-subtitle {
            color: #fed7aa;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .report-title {
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }

        .sub-header {
            background-color: #fff8ed;
            border: 1px solid #fed7aa;
            padding: 4px 8px;
            margin-bottom: 6px;
            font-size: 7.5px;
        }

        .card {
            border: 1px solid #fed7aa;
            border-radius: 4px;
            padding: 5px 7px;
            background: #ffffff;
            margin-bottom: 6px;
        }

        .card-title {
            font-size: 7.5px;
            font-weight: bold;
            color: #9a3412;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .table-simple { width: 100%; border-collapse: collapse; margin-bottom: 3px; }
        .table-simple th {
            font-size: 6.8px;
            background-color: #fff8ed;
            color: #9a3412;
            padding: 3px 4px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1.5px solid #fed7aa;
            border-right: 1px solid #ffedd5;
            text-align: left;
        }
        .table-simple th.text-right, .table-simple td.text-right { text-align: right !important; }
        .table-simple th.text-center, .table-simple td.text-center { text-align: center !important; }
        .table-simple th.text-left, .table-simple td.text-left { text-align: left !important; }

        .table-simple td {
            font-size: 7.2px;
            padding: 2.5px 4px;
            border-bottom: 1px solid #f8fafc;
            border-right: 1px solid #f8fafc;
            color: #1e293b;
        }

        .row-violation {
            background-color: #fff1f2 !important;
        }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .text-rose { color: #dc2626 !important; font-weight: bold; }
        .text-emerald { color: #16a34a !important; font-weight: bold; }
        .text-amber { color: #d97706 !important; font-weight: bold; }
        .font-bold { font-weight: bold; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <!-- Top Bakery Header Banner -->
    <div class="header-bar">
        <table class="header-table">
            <tr>
                <td style="width: 65%; text-align: left;">
                    <div class="company-subtitle">DIMUTHU BAKE HOUSE &mdash; KOTTAWA BRANCH</div>
                    <div class="report-title">Weekly Agent Review</div>
                </td>
                <td style="width: 35%; text-align: right;">
                    <div style="font-size: 10px; font-weight: bold; color: white;">{{ $data['week_info']['date_range_label'] }}</div>
                    <div style="font-size: 7px; color: #fed7aa; margin-top: 2px;">{{ $data['week_info']['generated_at'] }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Agent Subheader Strip -->
    <div class="sub-header">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 33%; border: none;"><strong>Agent:</strong> {{ $data['agent']['name'] }} ({{ $data['agent']['code'] }})</td>
                <td style="width: 34%; border: none;"><strong>Routes:</strong> {{ $data['agent']['routes'] }}</td>
                <td style="width: 33%; border: none; text-align: right;"><strong>Monthly Target:</strong> Rs. {{ number_format($data['monthly_target']) }}</td>
            </tr>
        </table>
    </div>

    <!-- Top 4 KPI Cards -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
        <tr>
            <!-- Card 1: Weekly Sales -->
            <td style="width: 25%; vertical-align: top; padding-right: 3px;">
                <div class="card" style="border-bottom: 3px solid {{ $data['weekly_sales']['target_percent'] >= 90 ? '#16a34a' : ($data['weekly_sales']['target_percent'] >= 75 ? '#d97706' : '#dc2626') }};">
                    <div class="card-title">WEEKLY SALES</div>
                    <div style="font-size: 12px; font-weight: bold; color: #0f172a;">Rs. {{ number_format($data['weekly_sales']['amount']) }}</div>
                    <div style="font-size: 6.8px; margin-top: 2px;">
                        <span class="{{ $data['weekly_sales']['target_percent'] >= 90 ? 'text-emerald' : 'text-amber' }}">{{ $data['weekly_sales']['target_percent'] }}% of weekly target &#9650;</span><br>
                        <span class="{{ $data['weekly_sales']['vs_last_week_percent'] >= 0 ? 'text-emerald' : 'text-rose' }}">{{ $data['weekly_sales']['vs_last_week_percent'] >= 0 ? '+' : '' }}{{ $data['weekly_sales']['vs_last_week_percent'] }}% vs last week</span>
                    </div>
                </div>
            </td>

            <!-- Card 2: Visit Compliance -->
            <td style="width: 25%; vertical-align: top; padding-right: 3px; padding-left: 1.5px;">
                <div class="card" style="border-bottom: 3px solid {{ $data['visit_compliance']['percent'] >= 90 ? '#16a34a' : ($data['visit_compliance']['percent'] >= 80 ? '#d97706' : '#dc2626') }};">
                    <div class="card-title">VISIT COMPLIANCE</div>
                    <div style="font-size: 12px; font-weight: bold; color: #0f172a;">{{ $data['visit_compliance']['percent'] }}%</div>
                    <div style="font-size: 6.8px; color: #64748b; margin-top: 2px;">
                        {{ $data['visit_compliance']['visited'] }} of {{ $data['visit_compliance']['total'] }} outlets visited
                    </div>
                </div>
            </td>

            <!-- Card 3: Return % -->
            <td style="width: 25%; vertical-align: top; padding-right: 1.5px; padding-left: 3px;">
                <div class="card" style="border-bottom: 3px solid {{ $data['returns']['is_above_threshold'] ? '#dc2626' : '#16a34a' }};">
                    <div class="card-title">RETURN %</div>
                    <div style="font-size: 12px; font-weight: bold;" class="{{ $data['returns']['is_above_threshold'] ? 'text-rose' : 'text-emerald' }}">{{ number_format($data['returns']['percent'], 1) }}%</div>
                    <div style="font-size: 6.8px; margin-top: 2px;" class="{{ $data['returns']['is_above_threshold'] ? 'text-rose' : 'text-emerald' }}">
                        {{ $data['returns']['is_above_threshold'] ? 'Above 5% threshold ▲' : 'On track ✓' }}
                    </div>
                </div>
            </td>

            <!-- Card 4: Credit Utilization -->
            <td style="width: 25%; vertical-align: top; padding-left: 3px;">
                <div class="card" style="border-bottom: 3px solid {{ $data['credit_utilization']['percent'] > 90 ? '#dc2626' : ($data['credit_utilization']['percent'] > 75 ? '#d97706' : '#16a34a') }};">
                    <div class="card-title">CREDIT UTILIZATION</div>
                    <div style="font-size: 12px; font-weight: bold;" class="{{ $data['credit_utilization']['percent'] > 90 ? 'text-rose' : 'text-amber' }}">{{ $data['credit_utilization']['percent'] }}%</div>
                    <div style="font-size: 6.8px; color: #64748b; margin-top: 2px;">
                        Rs. {{ number_format($data['credit_utilization']['used']) }} / {{ number_format($data['credit_utilization']['limit']) }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Monthly Target Run-Rate Box -->
    <div class="card" style="background-color: #fffaf5; border: 1px solid #fed7aa; margin-bottom: 6px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 45%; vertical-align: middle; border-right: 1px solid #fed7aa; padding-right: 8px;">
                    <div style="font-size: 7px; font-weight: bold; color: #9a3412; text-transform: uppercase;">REMAINING TO MONTHLY TARGET</div>
                    <div style="font-size: 13px; font-weight: bold; color: #9a3412; margin-top: 2px;">Rs. {{ number_format($data['target_progress']['remaining']) }}</div>
                    <div style="font-size: 6.8px; color: #64748b; margin-top: 1px;">
                        MTD sales <strong>Rs. {{ number_format($data['target_progress']['mtd_sales']) }}</strong> of <strong>Rs. {{ number_format($data['target_progress']['target']) }}</strong> target
                    </div>
                </td>
                <td style="width: 55%; vertical-align: middle; padding-left: 8px;">
                    <table style="width: 100%; border: none;">
                        <tr>
                            <td style="font-size: 7px; color: #334155;">
                                <strong>Weeks left in {{ $data['target_progress']['month_name'] }}: {{ $data['target_progress']['weeks_left'] }} &bull; Working days left: {{ $data['target_progress']['working_days_left'] }}</strong><br>
                                Required weekly run rate: <strong>Rs. {{ number_format($data['target_progress']['required_weekly_run_rate']) }}</strong><br>
                                Required daily run rate: <strong>Rs. {{ number_format($data['target_progress']['required_daily_run_rate']) }}</strong>
                            </td>
                            <td style="text-align: right; vertical-align: middle;">
                                @if($data['target_progress']['is_on_track'])
                                    <span style="display: inline-block; padding: 3px 6px; background-color: #dcfce7; color: #166534; border: 1px solid #86efac; border-radius: 4px; font-weight: bold; font-size: 7.5px;">
                                        On track to achieve &check;
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 3px 6px; background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; border-radius: 4px; font-weight: bold; font-size: 7.5px;">
                                        At risk of missing &#9650;
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Daily Outlet Visits Table -->
    <div class="card" style="margin-bottom: 6px;">
        <div class="card-title">DAILY OUTLET VISITS <span style="font-weight: normal; color: #94a3b8; font-size: 6.5px;">(minimum 30 per day)</span></div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th style="width: 40%;">Day</th>
                    <th class="text-center" style="width: 30%;">Outlets Visited</th>
                    <th style="width: 30%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['daily_visits'] as $day)
                    <tr class="{{ $day['status'] !== 'OK' ? 'row-violation' : '' }}">
                        <td><strong>{{ $day['day'] }}</strong></td>
                        <td class="text-center font-bold {{ $day['status'] !== 'OK' ? 'text-rose' : '' }}">{{ $day['outlets_visited'] }}</td>
                        <td>
                            @if($day['status'] === 'OK')
                                <span class="text-emerald">&check; OK</span>
                            @else
                                <span class="text-rose">&#10007; Below 30</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="font-size: 6.8px; color: #475569; padding-top: 2px; border-top: 1px solid #ffedd5;">
            Routes run this week: <strong>{{ $data['routes_run'] }}</strong> (<span class="text-emerald">Must {{ $data['must_routes'] }} &check;</span>) &bull; Missed outlets: <strong>{{ $data['missed_outlets'] }}</strong>
        </div>
    </div>

    <!-- Returns: 2 Tables Side-by-Side -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
        <tr>
            <!-- Left: Agent -> Bakery Returns -->
            <td style="width: 50%; vertical-align: top; padding-right: 3px;">
                <div class="card">
                    <div class="card-title">1. AGENT &rarr; BAKERY RETURNS <span style="font-weight: normal; color: #94a3b8; font-size: 6px;">(5 reasons; 'Other' specified)</span></div>
                    <table class="table-simple">
                        <thead>
                            <tr>
                                <th style="width: 55%;">Reason</th>
                                <th class="text-right" style="width: 25%;">Value (Rs.)</th>
                                <th class="text-right" style="width: 20%;">% of Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['agent_bakery_returns']['reasons'] as $r)
                                <tr>
                                    <td>{{ $r['reason'] }}</td>
                                    <td class="text-right font-bold">{{ number_format($r['value']) }}</td>
                                    <td class="text-right {{ $r['percent'] > 5 ? 'text-rose' : '' }}">{{ number_format($r['percent'], 1) }}%</td>
                                </tr>
                            @endforeach
                            <tr style="background-color: #fff8ed; font-weight: bold;">
                                <td><strong>Total</strong></td>
                                <td class="text-right font-bold">{{ number_format($data['agent_bakery_returns']['total']) }}</td>
                                <td class="text-right {{ $data['agent_bakery_returns']['percent_of_sales'] > 5 ? 'text-rose' : 'text-emerald' }} font-bold">{{ number_format($data['agent_bakery_returns']['percent_of_sales'], 1) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="font-size: 6.5px; color: #64748b; font-style: italic;">
                        Top returned product: <strong>{{ $data['agent_bakery_returns']['top_product_name'] }}</strong> &mdash; {{ $data['agent_bakery_returns']['top_product_qty'] }} units
                    </div>
                </div>
            </td>

            <!-- Right: Retail Shop -> Agent Returns -->
            <td style="width: 50%; vertical-align: top; padding-left: 3px;">
                <div class="card">
                    <div class="card-title">2. RETAIL SHOP &rarr; AGENT RETURNS <span style="font-weight: normal; color: #94a3b8; font-size: 6px;">(4 reasons; 'Other' specified)</span></div>
                    <table class="table-simple">
                        <thead>
                            <tr>
                                <th style="width: 45%;">Outlet</th>
                                <th class="text-right" style="width: 25%;">Value (Rs.)</th>
                                <th style="width: 30%;">Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['shop_agent_returns']['items'] as $sr)
                                <tr>
                                    <td>{{ $sr['outlet'] }}</td>
                                    <td class="text-right font-bold">{{ number_format($sr['value']) }}</td>
                                    <td style="color: #64748b;">{{ $sr['reason'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center" style="color: #94a3b8; padding: 4px;">No shop returns recorded</td>
                                </tr>
                            @endforelse
                            <tr style="background-color: #fff8ed; font-weight: bold;">
                                <td><strong>Total</strong></td>
                                <td class="text-right font-bold">{{ number_format($data['shop_agent_returns']['total']) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="font-size: 6.5px; color: #64748b; font-style: italic;">
                        Return discipline: monitor retail shelf storage and rotation.
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Credit & Collection (2 Side-by-Side Cards) -->
    <div style="font-size: 7.5px; font-weight: bold; color: #9a3412; text-transform: uppercase; margin-bottom: 3px;">CREDIT & COLLECTION</div>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
        <tr>
            <!-- Left: Agent -> Bakery Credit -->
            <td style="width: 50%; vertical-align: top; padding-right: 3px;">
                <div class="card">
                    <div class="card-title">1. AGENT &rarr; BAKERY CREDIT POSITION</div>
                    <div style="font-size: 7px; line-height: 1.4; color: #334155;">
                        Credit sales this week: <strong>Rs. {{ number_format($data['agent_bakery_credit']['credit_sales']) }}</strong><br>
                        Collections this week: <strong>Rs. {{ number_format($data['agent_bakery_credit']['collections']) }}</strong>
                        <span class="text-emerald">({{ $data['agent_bakery_credit']['collection_rate'] }}% collection rate)</span><br>
                        Closing dues: <strong>Rs. {{ number_format($data['agent_bakery_credit']['closing_dues']) }}</strong> | Credit period: <strong>{{ $data['agent_bakery_credit']['credit_period'] }} days</strong> | Aged 14+ days: <strong class="text-rose">Rs. {{ number_format($data['agent_bakery_credit']['aged_14_days']) }} &#9650;</strong>
                    </div>
                </div>
            </td>

            <!-- Right: Agent -> Retail Outlet Credit -->
            <td style="width: 50%; vertical-align: top; padding-left: 3px;">
                <div class="card">
                    <div class="card-title">2. AGENT &rarr; RETAIL OUTLET CREDIT POSITION <span style="font-weight: normal; color: #94a3b8; font-size: 6px;">(bill-to-bill policy)</span></div>
                    <div style="font-size: 7px; line-height: 1.4; color: #334155;">
                        Credit issued to outlets: <strong>Rs. {{ number_format($data['shop_credit']['credit_issued']) }}</strong><br>
                        Collected from outlets: <strong>Rs. {{ number_format($data['shop_credit']['collected']) }}</strong>
                        <span class="text-amber">({{ $data['shop_credit']['collection_rate'] }}% collection rate)</span><br>
                        Outstanding outlet dues: <strong>Rs. {{ number_format($data['shop_credit']['outstanding_dues']) }}</strong> | Aged 14+ days: <strong class="text-rose">Rs. {{ number_format($data['shop_credit']['aged_14_days']) }} &#9650;</strong><br>
                        Outlets with 2+ open credit bills: <strong class="text-rose">{{ $data['shop_credit']['over_two_bills_count'] }} &#9650;</strong>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Page Break for Clean 2-Page Document -->
    <div class="page-break"></div>

    <!-- Page 2 Header Bar -->
    <div class="header-bar" style="margin-top: 10px;">
        <table class="header-table">
            <tr>
                <td style="width: 65%; text-align: left;">
                    <div class="company-subtitle">DIMUTHU BAKE HOUSE &mdash; KOTTAWA BRANCH</div>
                    <div class="report-title">Weekly Agent Review (Cont.)</div>
                </td>
                <td style="width: 35%; text-align: right;">
                    <div style="font-size: 10px; font-weight: bold; color: white;">{{ $data['agent']['name'] }} &bull; {{ $data['week_info']['date_range_label'] }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- 4. Outlets with More than 2 Credit Bills -->
    <div class="card" style="margin-bottom: 6px;">
        <div class="card-title">4. OUTLETS WITH MORE THAN 2 CREDIT BILLS <span style="font-weight: normal; color: #94a3b8; font-size: 6.5px;">(bill-to-bill policy violation)</span></div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th style="width: 45%;">Outlet</th>
                    <th class="text-center" style="width: 15%;">Open Bills</th>
                    <th class="text-right" style="width: 20%;">Total Balance (Rs.)</th>
                    <th class="text-right" style="width: 20%;">Oldest Bill Age</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['outlets_over_two_bills'] as $ot)
                    <tr class="row-violation">
                        <td><strong>{{ $ot['outlet'] }}</strong></td>
                        <td class="text-center font-bold text-rose">{{ $ot['open_bills'] }}</td>
                        <td class="text-right font-bold text-rose">{{ number_format($ot['balance']) }}</td>
                        <td class="text-right">{{ (int) $ot['days_outstanding'] }} days</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="color: #94a3b8; padding: 4px;">No outlets have more than 2 open credit bills (policy respected).</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 3. Top 5 Highest Credit Balance Retail Shops -->
    <div class="card" style="margin-bottom: 6px;">
        <div class="card-title">3. TOP 5 HIGHEST CREDIT BALANCE RETAIL SHOPS <span style="font-weight: normal; color: #94a3b8; font-size: 6.5px;">(this agent)</span></div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 8%;">#</th>
                    <th style="width: 42%;">Outlet</th>
                    <th class="text-right" style="width: 20%;">Balance (Rs.)</th>
                    <th class="text-right" style="width: 15%;">Days Outstanding</th>
                    <th class="text-center" style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['top_credit_shops'] as $idx => $cs)
                    @php
                        $isOverdue = str_contains($cs['status'], 'Overdue');
                        $isNearLimit = str_contains($cs['status'], 'Near limit');
                    @endphp
                    <tr class="{{ $isOverdue ? 'row-violation' : '' }}">
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td><strong>{{ $cs['outlet'] }}</strong></td>
                        <td class="text-right font-bold {{ $isOverdue ? 'text-rose' : ($isNearLimit ? 'text-amber' : '') }}">{{ number_format($cs['balance']) }}</td>
                        <td class="text-right font-bold">{{ (int) $cs['days_outstanding'] }}</td>
                        <td class="text-center {{ $isOverdue ? 'text-rose' : ($isNearLimit ? 'text-amber' : 'text-emerald') }}">
                            {{ $isOverdue ? '▲ Overdue' : $cs['status'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center" style="color: #94a3b8; padding: 4px;">No outstanding credit balances.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Sales Growth Section -->
    <div style="font-size: 7.5px; font-weight: bold; color: #9a3412; text-transform: uppercase; margin-bottom: 3px;">SALES GROWTH</div>

    <!-- 1. Outlet Growth -->
    <div class="card" style="margin-bottom: 6px;">
        <div class="card-title">1. OUTLET GROWTH</div>
        <div style="font-size: 7px; color: #334155;">
            New shops added: <strong class="text-emerald">+{{ $data['outlet_growth']['new_shops'] }}</strong>
            @if(!empty($data['outlet_growth']['new_shop_names']))
                <span style="color: #64748b;">({{ implode(' + ', array_slice($data['outlet_growth']['new_shop_names'], 0, 3)) }})</span>
            @endif
            &bull; Dormant shops (no order 14+ days): <strong class="text-rose">{{ $data['outlet_growth']['dormant_shops'] }} &#9650;</strong>
            &bull; Active outlets: <strong>{{ $data['outlet_growth']['active_outlets'] }} / {{ $data['outlet_growth']['total_outlets'] }}</strong>
        </div>
    </div>

    <!-- 2. Top 5 Highest Sale Retail Shops -->
    <div class="card" style="margin-bottom: 6px;">
        <div class="card-title">2. TOP 5 HIGHEST SALE RETAIL SHOPS <span style="font-weight: normal; color: #94a3b8; font-size: 6.5px;">(this week)</span></div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 8%;">#</th>
                    <th style="width: 42%;">Outlet</th>
                    <th class="text-right" style="width: 20%;">Sales (Rs.)</th>
                    <th class="text-right" style="width: 15%;">% of Agent Sales</th>
                    <th class="text-right" style="width: 15%;">vs Last Week</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['top_sales_shops'] as $idx => $ss)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td><strong>{{ $ss['outlet'] }}</strong></td>
                        <td class="text-right font-bold">{{ number_format($ss['sales']) }}</td>
                        <td class="text-right">{{ number_format($ss['percent_of_agent_sales'], 1) }}%</td>
                        <td class="text-right {{ $ss['vs_last_week_percent'] >= 0 ? 'text-emerald' : 'text-rose' }}">
                            {{ $ss['vs_last_week_percent'] >= 0 ? '▲ +' : '▼ ' }}{{ number_format($ss['vs_last_week_percent'], 1) }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center" style="color: #94a3b8; padding: 4px;">No shop sales recorded this week.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 3. Top 5 Fast Moving SKUs -->
    <div class="card" style="margin-bottom: 8px;">
        <div class="card-title">3. TOP 5 FAST MOVING SKUS <span style="font-weight: normal; color: #94a3b8; font-size: 6.5px;">(ranked by units sold this week)</span></div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 6%;">#</th>
                    <th style="width: 36%;">SKU</th>
                    <th class="text-right" style="width: 15%;">Units Sold</th>
                    <th class="text-right" style="width: 15%;">Units / Working Day</th>
                    <th class="text-right" style="width: 14%;">Sales (Rs.)</th>
                    <th class="text-right" style="width: 14%;">% of Agent Sales</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['top_skus'] as $idx => $sku)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td><strong>{{ $sku['sku'] }}</strong></td>
                        <td class="text-right font-bold">{{ number_format($sku['units_sold']) }}</td>
                        <td class="text-right">{{ number_format($sku['units_per_day']) }}</td>
                        <td class="text-right font-bold">{{ number_format($sku['sales']) }}</td>
                        <td class="text-right">{{ number_format($sku['percent_of_agent_sales'], 1) }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="color: #94a3b8; padding: 4px;">No SKUs sold this week.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Sign-off strip matching Mockup -->
    <div class="card" style="border: 1px solid #fed7aa; background-color: #fffaf5; margin-bottom: 8px; padding: 8px 12px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; font-size: 7.5px; vertical-align: bottom;">
                    <span style="font-weight: bold; color: #9a3412;">Agent signature:</span>
                    <span style="display: inline-block; border-bottom: 1.5px solid #64748b; height: 14px; width: 140px; vertical-align: bottom; margin-left: 4px;">&nbsp;</span>
                </td>
                <td style="width: 50%; border: none; font-size: 7.5px; text-align: right; vertical-align: bottom;">
                    <span style="font-weight: bold; color: #9a3412;">Director signature:</span>
                    <span style="display: inline-block; border-bottom: 1.5px solid #64748b; height: 14px; width: 140px; vertical-align: bottom; margin-left: 4px;">&nbsp;</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Legend & Footer -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 4px;">
        <tr>
            <td style="width: 50%; font-size: 6.8px;">
                <span class="text-rose">&#9632; Rule violation</span> &nbsp;&nbsp;
                <span class="text-amber">&#9632; Warning</span> &nbsp;&nbsp;
                <span class="text-emerald">&#9632; On track</span>
            </td>
            <td style="width: 50%; text-align: right; font-size: 6.8px; color: #94a3b8;">
                Dimuthu Bakers v2.0.1 &mdash; Report A &mdash; (Agent: {{ $data['agent']['name'] }})
            </td>
        </tr>
    </table>
</body>
</html>
