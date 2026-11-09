<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Agent Review - {{ $data['agent']['name'] }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8px;
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
            margin-bottom: 4px;
        }

        .table-simple { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .table-simple th {
            font-size: 7px;
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
            font-size: 7.5px;
            padding: 2.5px 4px;
            border-bottom: 1px solid #f8fafc;
            border-right: 1px solid #f8fafc;
            color: #1e293b;
        }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .text-rose { color: #dc2626; }
        .text-emerald { color: #16a34a; }
        .text-amber { color: #d97706; }
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
                    <div class="report-title">Monthly Agent Review</div>
                </td>
                <td style="width: 35%; text-align: right;">
                    <div style="font-size: 11px; font-weight: bold; color: white;">{{ $monthYear }}</div>
                    <div style="font-size: 7px; color: #fed7aa; margin-top: 2px;">Generated: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</div>
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

    <!-- Top 2 Main Metric Cards -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
        <tr>
            <td style="width: 49%; vertical-align: top; padding-right: 1%;">
                <div class="card" style="border: 1.5px solid #ea580c;">
                    <div class="card-title">MONTHLY SALES</div>
                    <div style="font-size: 14px; font-weight: bold; color: #0f172a;">
                        Rs. {{ number_format($data['sales']['current']) }}
                        <span style="font-size: 10px;" class="{{ $data['sales']['percent_target'] >= 100 ? 'text-emerald' : ($data['sales']['percent_target'] >= 80 ? 'text-amber' : 'text-rose') }}">
                            {{ $data['sales']['percent_target'] }}% &#9650;
                        </span>
                    </div>
                    <div style="font-size: 7px; color: #64748b; margin-top: 2px;">
                        vs {{ $data['mom']['prev_month_label'] }}: Rs. {{ number_format($data['sales']['prev']) }} ({{ $data['sales']['growth'] >= 0 ? '+' : '' }}{{ $data['sales']['growth'] }}%)
                    </div>
                </div>
            </td>
            <td style="width: 49%; vertical-align: top; padding-left: 1%;">
                <div class="card" style="border: 1.5px solid #ea580c; text-align: right;">
                    <div class="card-title">REMAINING TO TARGET</div>
                    <div style="font-size: 14px; font-weight: bold;" class="{{ $data['sales']['remaining'] > 0 ? 'text-rose' : 'text-emerald' }}">
                        Rs. {{ number_format($data['sales']['remaining']) }}
                    </div>
                    <div style="font-size: 7px; color: #64748b; margin-top: 2px;">
                        @if($data['sales']['remaining'] > 0)
                            Target missed by {{ $data['sales']['target_missed_pct'] }}% &mdash; discuss in meeting
                        @else
                            Target achieved &check;
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Middle 2 Columns: Week-by-Week & MoM / Returns -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px;">
        <tr>
            <!-- Left: Week-by-Week -->
            <td style="width: 50%; vertical-align: top; padding-right: 4px;">
                <div class="card">
                    <div class="card-title">WEEK-BY-WEEK BREAKDOWN</div>
                    <table class="table-simple">
                        <thead>
                            <tr>
                                <th>Week</th>
                                <th class="text-right">Sales (Rs.)</th>
                                <th class="text-right">Visit Compliance</th>
                                <th class="text-right">Return %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['week_by_week'] ?? [] as $w)
                                <tr>
                                    <td>{{ $w['label'] }}</td>
                                    <td class="text-right font-bold">{{ number_format($w['sales']) }}</td>
                                    <td class="text-right {{ $w['visit_compliance'] >= 90 ? 'text-emerald' : ($w['visit_compliance'] >= 75 ? 'text-amber' : 'text-rose') }} font-bold">{{ $w['visit_compliance'] }}%</td>
                                    <td class="text-right {{ $w['return_percent'] <= 5.0 ? 'text-emerald' : 'text-rose' }} font-bold">{{ $w['return_percent'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="font-size: 6.5px; color: #64748b; font-style: italic;">
                        Pattern: compliance and sales dipped mid-month &mdash; review route discipline for weeks 29-30.
                    </div>
                </div>
            </td>

            <!-- Right: MoM & Returns Summary -->
            <td style="width: 50%; vertical-align: top; padding-left: 4px;">
                <!-- Month-Over-Month -->
                <div class="card" style="margin-bottom: 4px;">
                    <div class="card-title">MONTH-OVER-MONTH</div>
                    <table class="table-simple">
                        <thead>
                            <tr>
                                <th>Measure</th>
                                <th class="text-right">{{ $data['mom']['prev_month_label'] }}</th>
                                <th class="text-right">{{ $data['mom']['curr_month_label'] }}</th>
                                <th class="text-center">Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Sales (Rs.)</td>
                                <td class="text-right">{{ number_format($data['sales']['prev']) }}</td>
                                <td class="text-right font-bold">{{ number_format($data['sales']['current']) }}</td>
                                <td class="text-center font-bold {{ $data['mom']['sales_trend'] >= 0 ? 'text-emerald' : 'text-rose' }}">
                                    &#9650; {{ $data['mom']['sales_trend'] >= 0 ? '+' : '' }}{{ $data['mom']['sales_trend'] }}%
                                </td>
                            </tr>
                            <tr>
                                <td>Return %</td>
                                <td class="text-right">{{ $data['mom']['return_prev'] }}%</td>
                                <td class="text-right font-bold">{{ $data['mom']['return_curr'] }}%</td>
                                <td class="text-center font-bold {{ ($data['mom']['return_curr'] - $data['mom']['return_prev']) <= 0 ? 'text-emerald' : 'text-rose' }}">
                                    &#9660; {{ ($data['mom']['return_curr'] - $data['mom']['return_prev']) <= 0 ? 'improving' : 'watch' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Collection %</td>
                                <td class="text-right">{{ $data['mom']['collection_prev'] }}%</td>
                                <td class="text-right font-bold">{{ $data['mom']['collection_curr'] }}%</td>
                                <td class="text-center font-bold {{ ($data['mom']['collection_curr'] - $data['mom']['collection_prev']) >= 0 ? 'text-emerald' : 'text-rose' }}">
                                    &#9650; {{ $data['mom']['collection_curr'] - $data['mom']['collection_prev'] > 0 ? '+' : '' }}{{ $data['mom']['collection_curr'] - $data['mom']['collection_prev'] }} pts
                                </td>
                            </tr>
                            <tr>
                                <td>New shops</td>
                                <td class="text-right">{{ $data['mom']['new_shops_prev'] }}</td>
                                <td class="text-right font-bold">{{ $data['mom']['new_shops_curr'] }}</td>
                                <td class="text-center font-bold text-emerald">&#9650;</td>
                            </tr>
                            <tr>
                                <td>Dormant shops</td>
                                <td class="text-right">{{ $data['mom']['dormant_prev'] }}</td>
                                <td class="text-right font-bold text-rose">{{ $data['mom']['dormant_curr'] }}</td>
                                <td class="text-center font-bold text-rose">&#9650; watch</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Returns Summary -->
                <div class="card">
                    <div class="card-title">RETURNS SUMMARY ({{ strtoupper($data['mom']['curr_month_label']) }})</div>
                    <table class="table-simple">
                        <thead>
                            <tr>
                                <th>Direction</th>
                                <th class="text-right">Value (Rs.)</th>
                                <th class="text-right">% of Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1. Agent &rarr; Bakery</td>
                                <td class="text-right">{{ number_format($data['returns_summary']['agent_to_bakery']['value']) }}</td>
                                <td class="text-right">{{ $data['returns_summary']['agent_to_bakery']['pct'] }}%</td>
                            </tr>
                            <tr>
                                <td>2. Shop &rarr; Agent</td>
                                <td class="text-right">{{ number_format($data['returns_summary']['shop_to_agent']['value']) }}</td>
                                <td class="text-right">{{ $data['returns_summary']['shop_to_agent']['pct'] }}%</td>
                            </tr>
                            <tr style="font-weight: bold; background: #fff8ed;">
                                <td>Total returns</td>
                                <td class="text-right font-bold">{{ number_format($data['returns_summary']['total']['value']) }}</td>
                                <td class="text-right font-bold">{{ $data['returns_summary']['total']['pct'] }}%</td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="font-size: 6.5px; color: #475569; margin-top: 2px;">
                        <div><strong>Agent &rarr; Bakery:</strong> {{ $data['returns_summary']['agent_to_bakery']['reasons'] }}</div>
                        <div style="margin-top: 1px;"><strong>Shop &rarr; Agent:</strong> {{ $data['returns_summary']['shop_to_agent']['reasons'] }}</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Commission Section -->
    <div class="card">
        <div class="card-title">COMMISSION &mdash; {{ strtoupper($data['mom']['curr_month_label']) }} {{ $data['sales']['year'] ?? '' }}</div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 55%; vertical-align: top; padding-right: 6px; font-size: 7.5px;">
                    <div style="margin-bottom: 2px;">Invoiced value: <strong>Rs. {{ number_format($data['commission']['invoiced_value']) }}</strong> &mdash; Agent &rarr; Bakery returns: <strong>Rs. {{ number_format($data['commission']['agent_bakery_returns']) }}</strong></div>
                    <div style="margin-bottom: 2px;">Net sales: <strong>Rs. {{ number_format($data['commission']['net_sales']) }}</strong></div>
                    <div style="margin-bottom: 2px;">Invoicing commission ({{ $data['commission']['invoicing_rate'] }}% &times; net): <strong>Rs. {{ number_format($data['commission']['invoicing_commission']) }}</strong></div>
                    <div style="margin-bottom: 3px;">Target bonus: <strong class="{{ $data['commission']['bonus_qualified'] ? 'text-emerald' : 'text-rose' }}">Rs. {{ number_format($data['commission']['target_bonus']) }} &mdash; {{ $data['commission']['bonus_qualified'] ? 'qualified' : 'not qualified' }}</strong></div>
                    <div style="border-top: 1px solid #fed7aa; padding-top: 2px; font-weight: bold; font-size: 8.5px; color: #9a3412;">
                        Total payable: Rs. {{ number_format($data['commission']['total_payable']) }}
                    </div>
                </td>
                <td style="width: 45%; vertical-align: top;">
                    <table class="table-simple" style="margin-bottom: 2px;">
                        <thead>
                            <tr>
                                <th>Achievement Level</th>
                                <th class="text-right">%</th>
                                <th class="text-center">100%?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total sales</td>
                                <td class="text-right">{{ $data['commission']['total_sales_achievement'] }}%</td>
                                <td class="text-center font-bold {{ $data['commission']['total_sales_achievement'] >= 100 ? 'text-emerald' : 'text-rose' }}">{{ $data['commission']['total_sales_achievement'] >= 100 ? '✓' : '✗' }}</td>
                            </tr>
                            <tr>
                                <td>Category sales</td>
                                <td class="text-right">{{ $data['commission']['category_sales_achievement'] }}%</td>
                                <td class="text-center font-bold {{ $data['commission']['category_sales_achievement'] >= 100 ? 'text-emerald' : 'text-rose' }}">{{ $data['commission']['category_sales_achievement'] >= 100 ? '✓' : '✗' }}</td>
                            </tr>
                            <tr>
                                <td>SKU sales</td>
                                <td class="text-right">{{ $data['commission']['sku_sales_achievement'] }}%</td>
                                <td class="text-center font-bold {{ $data['commission']['sku_sales_achievement'] >= 100 ? 'text-emerald' : 'text-rose' }}">{{ $data['commission']['sku_sales_achievement'] >= 100 ? '✓' : '✗' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="font-size: 6.5px; color: #64748b;">
                        Bonus is all-or-nothing: 100% required at every level. Missing target by Rs. {{ number_format($data['sales']['remaining']) }} cost the full 5% bonus &mdash; about Rs. {{ number_format($data['commission']['missed_bonus_amount']) }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Credit & Collection -->
    <div class="card">
        <div class="card-title">CREDIT &amp; COLLECTION</div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 4px;">
                    <div style="border: 0.5px solid #fed7aa; padding: 4px; border-radius: 3px;">
                        <div style="font-size: 7px; font-weight: bold; color: #9a3412;">1. AGENT &rarr; BAKERY CREDIT POSITION</div>
                        <div style="font-size: 7px; margin-top: 2px;">Closing dues: <strong>Rs. {{ number_format($data['credit_collection']['agent_to_bakery']['closing_dues']) }}</strong> | Utilization: <strong class="text-amber">{{ $data['credit_collection']['agent_to_bakery']['utilization'] }}%</strong></div>
                        <div style="font-size: 7px;">Credit period: <strong>{{ $data['credit_collection']['agent_to_bakery']['credit_period'] }} days</strong></div>
                        <div style="font-size: 6.5px; color: #64748b; margin-top: 2px; border-top: 0.5px solid #ffedd5; padding-top: 1px;">
                            Ageing &mdash; Within term (0-14d): Rs. {{ number_format($data['credit_collection']['agent_to_bakery']['ageing']['0_14']) }} &bull; 15-30d: Rs. {{ number_format($data['credit_collection']['agent_to_bakery']['ageing']['15_30']) }} &bull; 31-60d: Rs. {{ number_format($data['credit_collection']['agent_to_bakery']['ageing']['31_60']) }} &bull; 60d+: <strong class="text-rose">Rs. {{ number_format($data['credit_collection']['agent_to_bakery']['ageing']['60_plus']) }}</strong>
                        </div>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; padding-left: 4px;">
                    <div style="border: 0.5px solid #fed7aa; padding: 4px; border-radius: 3px;">
                        <div style="font-size: 7px; font-weight: bold; color: #9a3412;">2. AGENT &rarr; RETAIL OUTLET CREDIT POSITION <span style="font-size: 6px; font-weight: normal; color: #64748b;">(billed to this agent)</span></div>
                        <div style="font-size: 7px; margin-top: 2px;">Outstanding on list dues: <strong>Rs. {{ number_format($data['credit_collection']['agent_to_outlet']['closing_dues']) }}</strong> | Collection rate: <strong class="text-amber">{{ $data['credit_collection']['agent_to_outlet']['collection_rate'] }}%</strong></div>
                        <div style="font-size: 6.5px; color: #64748b; margin-top: 2px; border-top: 0.5px solid #ffedd5; padding-top: 1px;">
                            Ageing &mdash; Current: Rs. {{ number_format($data['credit_collection']['agent_to_outlet']['ageing']['0_14']) }} &bull; 1-14d: Rs. {{ number_format($data['credit_collection']['agent_to_outlet']['ageing']['15_30']) }} &bull; 15-30d: Rs. {{ number_format($data['credit_collection']['agent_to_outlet']['ageing']['31_60']) }} &bull; 60d+: <strong class="text-rose">Rs. {{ number_format($data['credit_collection']['agent_to_outlet']['ageing']['60_plus']) }}</strong>
                        </div>
                        <div style="font-size: 6.5px; color: #dc2626; margin-top: 1px;">
                            Outlets with 2+ open credit bills: <strong>{{ $data['credit_collection']['agent_to_outlet']['outlets_with_2plus_bills_count'] }}</strong> (see list below)
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- A. Outlets With More Than 2 Credit Bills -->
    <div class="card">
        <div class="card-title">A. OUTLETS WITH MORE THAN 2 CREDIT BILLS (all-to-bill policy violation)</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th>Outlet</th>
                    <th class="text-center">Open Bills</th>
                    <th class="text-right">Total Balance (Rs.)</th>
                    <th class="text-right">Oldest Bill Age</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['outlets_with_2plus_bills'] ?? [] as $ob)
                    <tr>
                        <td class="font-bold">{{ $ob['outlet_name'] }}</td>
                        <td class="text-center font-bold text-rose">{{ $ob['open_bills'] }}</td>
                        <td class="text-right font-bold">{{ number_format($ob['total_balance']) }}</td>
                        <td class="text-right">{{ round(abs((float)($ob['oldest_bill_age'] ?? 0))) }} days</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No outlets with 2+ open bills</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- B. Top 5 Highest Credit Balance Retail Shops -->
    <div class="card">
        <div class="card-title">B. TOP 5 HIGHEST CREDIT BALANCE RETAIL SHOPS (this agent)</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15px;">#</th>
                    <th>Outlet</th>
                    <th class="text-right">Balance (Rs.)</th>
                    <th class="text-right">Days Outstanding</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['top_credit_shops'] ?? [] as $idx => $sh)
                    <tr style="{{ $sh['status'] === 'Overdue' ? 'background: #fff1f2;' : '' }}">
                        <td class="text-center font-bold" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $sh['outlet_name'] }}</td>
                        <td class="text-right font-bold {{ $sh['status'] === 'Overdue' ? 'text-rose' : '' }}">{{ number_format($sh['balance']) }}</td>
                        <td class="text-right font-bold">{{ round(abs((float)($sh['days_outstanding'] ?? 0))) }}</td>
                        <td class="text-center font-bold {{ $sh['status'] === 'Overdue' ? 'text-rose' : ($sh['status'] === 'Near limit' ? 'text-amber' : 'text-emerald') }}">
                            {{ $sh['status'] === 'Overdue' ? '&Delta; Overdue' : $sh['status'] }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No credit balance records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- SALES GROWTH Section -->
    <div style="font-size: 11px; font-weight: bold; color: #9a3412; margin-bottom: 6px; padding-bottom: 3px; border-bottom: 1.5px solid #fed7aa;">
        SALES GROWTH
    </div>

    <!-- 1. Outlet Growth -->
    <div class="card">
        <div class="card-title">1. OUTLET GROWTH ({{ strtoupper($data['mom']['curr_month_label']) }})</div>
        <div style="font-size: 7.5px; line-height: 1.4;">
            Opening active outlets: <strong>{{ $data['sales_growth']['outlet_growth']['opening'] }}</strong> &bull;
            New shops: <strong class="text-emerald">+{{ $data['sales_growth']['outlet_growth']['new_shops'] }}</strong> &bull;
            Newly dormant: <strong class="text-rose">-{{ $data['sales_growth']['outlet_growth']['newly_dormant'] }}</strong> &bull;
            Closing active outlets: <strong>{{ $data['sales_growth']['outlet_growth']['closing'] }} / {{ $data['sales_growth']['outlet_growth']['total'] }}</strong>
            <span class="{{ $data['sales_growth']['outlet_growth']['growth_pct'] >= 0 ? 'text-emerald' : 'text-rose' }} font-bold">
                ({{ $data['sales_growth']['outlet_growth']['growth_pct'] >= 0 ? '+' : '' }}{{ $data['sales_growth']['outlet_growth']['growth_pct'] }}% &#9650;)
            </span>
        </div>
        <div style="font-size: 7px; color: #64748b; margin-top: 2px;">
            <strong>New shops:</strong> {{ $data['sales_growth']['outlet_growth']['new_shops_list'] ?: 'None' }}
        </div>
    </div>

    <!-- 2. Top 5 Highest Sale Retail Shops -->
    <div class="card">
        <div class="card-title">2. TOP 5 HIGHEST SALE RETAIL SHOPS ({{ $data['mom']['curr_month_label'] }})</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15px;">#</th>
                    <th>Outlet</th>
                    <th class="text-right">Sales (Rs.)</th>
                    <th class="text-right">% of Agent Sales</th>
                    <th class="text-right">vs {{ $data['prev_month_label'] ?? 'Prev' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['sales_growth']['top_sale_shops'] ?? [] as $idx => $sh)
                    <tr>
                        <td class="text-center font-bold" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $sh['outlet_name'] }}</td>
                        <td class="text-right font-bold">{{ number_format($sh['sales']) }}</td>
                        <td class="text-right">{{ $sh['sales_pct'] }}%</td>
                        <td class="text-right font-bold {{ $sh['vs_prev_month'] >= 0 ? 'text-emerald' : 'text-rose' }}">
                            {{ $sh['vs_prev_month'] >= 0 ? '&#9650; +' : '&#9660; ' }}{{ $sh['vs_prev_month'] }}%
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No sales records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 3. Top 5 Fast Moving SKUs -->
    <div class="card">
        <div class="card-title">3. TOP 5 FAST MOVING SKUs (ranked by units sold in {{ $data['mom']['curr_month_label'] }})</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15px;">#</th>
                    <th>SKU</th>
                    <th class="text-right">Units Sold</th>
                    <th class="text-right">Units / Working Day</th>
                    <th class="text-right">Sales (Rs.)</th>
                    <th class="text-right">% of Agent Sales</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['sales_growth']['top_skus'] ?? [] as $idx => $sku)
                    <tr>
                        <td class="text-center font-bold" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $sku['sku_name'] }}</td>
                        <td class="text-right font-bold">{{ number_format($sku['units_sold']) }}</td>
                        <td class="text-right">{{ number_format($sku['units_per_day']) }}</td>
                        <td class="text-right font-bold">{{ number_format($sku['sales']) }}</td>
                        <td class="text-right font-bold">{{ $sku['sales_pct'] }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No SKU records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 4. Top 5 Highest Sales Growth Retail Shops -->
    <div class="card">
        <div class="card-title">4. TOP 5 HIGHEST SALES GROWTH RETAIL SHOPS (monthly view &mdash; {{ $data['mom']['curr_month_label'] }} vs {{ $data['mom']['prev_month_label'] }}; ranked by growth in Rs.)</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15px;">#</th>
                    <th>Outlet</th>
                    <th class="text-right">{{ $data['prev_month_label'] ?? 'Prev' }} (Rs.)</th>
                    <th class="text-right">{{ $data['current_month_label'] ?? 'Cur' }} (Rs.)</th>
                    <th class="text-right">Growth (Rs.)</th>
                    <th class="text-right">Growth %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['sales_growth']['top_growth_shops'] ?? [] as $idx => $gs)
                    <tr>
                        <td class="text-center font-bold" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $gs['outlet_name'] }}</td>
                        <td class="text-right">{{ number_format($gs['prev_sales']) }}</td>
                        <td class="text-right font-bold">{{ number_format($gs['current_sales']) }}</td>
                        <td class="text-right font-bold text-emerald">+{{ number_format($gs['growth_rs']) }}</td>
                        <td class="text-right font-bold text-emerald">+{{ $gs['growth_pct'] }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No sales growth records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Next Month Target -->
    <div class="card">
        <div class="card-title">{{ strtoupper($data['next_target']['label'] ?? 'Next') }} TARGET (entered by management &mdash; sign-off in meeting)</div>
        <div style="font-size: 7.5px; margin-bottom: 8px;">
            Proposed target: <strong>Rs. {{ is_numeric($data['next_target']['target']) ? number_format($data['next_target']['target']) : $data['next_target']['target'] }}</strong>
        </div>
        <table style="width: 100%; border: none; font-size: 7.5px;">
            <tr>
                <td style="width: 50%; border: none;">Agent signature: <span style="display: inline-block; border-bottom: 1.5px solid #64748b; width: 160px; height: 12px; margin-left: 4px;"></span></td>
                <td style="width: 50%; border: none; text-align: right;">Director signature: <span style="display: inline-block; border-bottom: 1.5px solid #64748b; width: 160px; height: 12px; margin-left: 4px;"></span></td>
            </tr>
        </table>
    </div>

    <!-- Color Legend & Footer -->
    <div style="margin-top: 8px; padding-top: 4px; border-top: 1px solid #fed7aa; font-size: 7px;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; text-align: left;">
                    <span style="color: #dc2626; font-weight: bold; margin-right: 12px;">&#9632; Rule violation</span>
                    <span style="color: #d97706; font-weight: bold; margin-right: 12px;">&#9632; Warning</span>
                    <span style="color: #16a34a; font-weight: bold;">&#9632; On track</span>
                </td>
                <td style="border: none; text-align: right; color: #64748b;">
                    Dimuthu Bakers v2.0.1 &mdash; Report B &mdash; Monthly Agent Review ({{ $data['agent']['name'] }})
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
