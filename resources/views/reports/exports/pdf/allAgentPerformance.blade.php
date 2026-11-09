<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Agent Performance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 8px; color: #1e293b; margin: 0; padding: 0; }
        .header-bar { 
            background-color: #8c4314; 
            color: white; 
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 4px;
        }
        .header-table { width: 100%; border-collapse: collapse; border: none; }
        .header-table td { border: none; padding: 0; vertical-align: middle; }
        .company-subtitle { 
            color: #fed7aa; 
            font-size: 7.5px;
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

        .card {
            border: 1px solid #fed7aa;
            border-radius: 4px;
            padding: 5px 7px;
            background: #ffffff;
            margin-bottom: 7px;
        }

        .card-title {
            font-size: 8px;
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

        .badge-ramp {
            background-color: #fef3c7;
            color: #b45309;
            border: 0.5px solid #fde68a;
            font-size: 6px;
            font-weight: bold;
            padding: 0.5px 2.5px;
            border-radius: 2px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Top Bakery Header Banner -->
    <div class="header-bar">
        <table class="header-table">
            <tr>
                <td style="width: 65%; text-align: left;">
                    <div class="company-subtitle">DIMUTHU BAKE HOUSE &mdash; KOTTAWA BRANCH</div>
                    <div class="report-title">All Agent Performance Report</div>
                </td>
                <td style="width: 35%; text-align: right;">
                    <div style="font-size: 11px; font-weight: bold; color: white;">{{ $monthYear }}</div>
                    <div style="font-size: 7.5px; color: #fed7aa; margin-top: 2px;">Generated: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Leaderboard Table -->
    <div class="card">
        <div class="card-title">Agent Leaderboard (Sorted by Achievement %)</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15px;">#</th>
                    <th>Agent</th>
                    <th class="text-right">Sales (Rs.)</th>
                    <th class="text-right">Achieve %</th>
                    <th class="text-right">Remaining (Rs.)</th>
                    <th class="text-right">Visit Comp.</th>
                    <th class="text-right">Return %</th>
                    <th class="text-right">Collect %</th>
                    <th class="text-center">New Shops</th>
                    <th class="text-right">Credit Util.</th>
                    <th class="text-center">5% Bonus</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['leaderboard'] as $index => $row)
                    <tr style="{{ $index % 2 === 1 ? 'background: #fffdfa;' : '' }}">
                        <td class="text-center font-bold" style="color: #64748b;">{{ $index + 1 }}</td>
                        <td class="font-bold">
                            {{ $row['agent_name'] }}
                            @if($row['badge'])
                                <span class="badge-ramp">{{ $row['badge'] }}</span>
                            @endif
                        </td>
                        <td class="text-right font-bold">{{ number_format($row['sales']) }}</td>
                        <td class="text-right font-bold {{ $row['achievement'] >= 80 ? 'text-emerald' : ($row['achievement'] >= 70 ? 'text-amber' : 'text-rose') }}">{{ $row['achievement'] }}%</td>
                        <td class="text-right">
                            @if($row['achievement'] >= 100)
                                <span class="text-emerald font-bold">Achieved &check;</span>
                            @elseif($row['achievement'] < 70)
                                <span class="text-rose font-bold">{{ number_format($row['remaining']) }}</span>
                            @else
                                {{ number_format($row['remaining']) }}
                            @endif
                        </td>
                        <td class="text-right font-bold {{ $row['visit_compliance'] >= 90 ? 'text-emerald' : ($row['visit_compliance'] >= 75 ? 'text-amber' : 'text-rose') }}">{{ $row['visit_compliance'] }}%</td>
                        <td class="text-right font-bold {{ $row['return_percent'] <= 5 ? 'text-emerald' : 'text-rose' }}">{{ $row['return_percent'] }}%</td>
                        <td class="text-right font-bold {{ $row['collection_percent'] >= 90 ? 'text-emerald' : ($row['collection_percent'] >= 80 ? 'text-amber' : 'text-rose') }}">{{ $row['collection_percent'] }}%</td>
                        <td class="text-center font-bold">{{ $row['new_shops'] }}</td>
                        <td class="text-right font-bold {{ $row['credit_util'] <= 60 ? 'text-emerald' : ($row['credit_util'] <= 80 ? 'text-amber' : 'text-rose') }}">{{ $row['credit_util'] }}%</td>
                        <td class="text-center font-bold {{ $row['bonus_qualified'] ? 'text-emerald' : 'text-rose' }}">
                            {{ $row['bonus_qualified'] ? '&check; Yes' : '&cross; ' . $row['achievement'] . '%' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="font-size: 6.5px; color: #64748b; margin-top: 3px;">
            M1 / M2 badges mark agents in ramp-up months (targets set manually by management). 5% bonus requires 100% achievement at all three levels &mdash; total, category, and SKU &mdash; calculated on net sales.
        </div>
    </div>

    <!-- Return % Trend & 1. Credit Ageing -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 7px;">
        <tr>
            <td style="width: 49%; vertical-align: top; padding-right: 1%;">
                <div class="card">
                    <div class="card-title">Return % Trend (3 months, team)</div>
                    <table class="table-simple">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-right">Listed Reasons</th>
                                <th class="text-right">Other (specified)</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $trendReversed = array_reverse($data['return_trend']); @endphp
                            @foreach($trendReversed as $idx => $rt)
                                <tr style="{{ $loop->last ? 'background: #f0fdf4;' : ($idx % 2 === 1 ? 'background: #fffdfa;' : '') }}">
                                    <td>{{ $rt['month_label'] }}</td>
                                    <td class="text-right">{{ $rt['defect_pct'] }}%</td>
                                    <td class="text-right {{ $rt['other_pct'] > 1 ? 'text-rose font-bold' : '' }}">{{ $rt['other_pct'] }}%</td>
                                    <td class="text-right font-bold">{{ $rt['total_pct'] }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="font-size: 6.5px; color: #64748b; margin-top: 2px;">
                        Trend improving &mdash; reason codes now show where returns come from.
                    </div>
                </div>
            </td>
            <td style="width: 49%; vertical-align: top; padding-left: 1%;">
                <div class="card">
                    <div class="card-title">1. Agent &rarr; Bakery Credit Ageing (all agents, Rs.)</div>
                    <table class="table-simple">
                        <thead>
                            <tr>
                                <th>Bucket</th>
                                <th class="text-right">Amount (Rs.)</th>
                                <th class="text-right">% of Dues</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Within term (0 &ndash; 14 days)</td>
                                <td class="text-right">{{ number_format($data['credit_ageing']['current']['amount']) }}</td>
                                <td class="text-right">{{ $data['credit_ageing']['current']['pct'] }}%</td>
                            </tr>
                            <tr style="background: #fffdfa;">
                                <td>15 &ndash; 30 days</td>
                                <td class="text-right">{{ number_format($data['credit_ageing']['days_30']['amount']) }}</td>
                                <td class="text-right">{{ $data['credit_ageing']['days_30']['pct'] }}%</td>
                            </tr>
                            <tr>
                                <td>31 &ndash; 60 days</td>
                                <td class="text-right">{{ number_format($data['credit_ageing']['days_60']['amount']) }}</td>
                                <td class="text-right">{{ $data['credit_ageing']['days_60']['pct'] }}%</td>
                            </tr>
                            <tr style="background: #fff1f2;">
                                <td class="text-rose font-bold">60+ days</td>
                                <td class="text-right font-bold text-rose">{{ number_format($data['credit_ageing']['days_60_plus']['amount']) }}</td>
                                <td class="text-right font-bold text-rose">{{ $data['credit_ageing']['days_60_plus']['pct'] }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- 2. Agent -> Retail Outlet Credit Summary -->
    <div class="card">
        <div class="card-title">2. Agent &rarr; Retail Outlet Credit Summary (per agent, Rs.)</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th>Agent</th>
                    <th class="text-right">Credit Issued</th>
                    <th class="text-right">Collected</th>
                    <th class="text-right">Outstanding</th>
                    <th class="text-right">Collection %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['agent_credit_summary'] ?? [] as $idx => $acs)
                    <tr style="{{ $idx % 2 === 1 ? 'background: #fffdfa;' : '' }}">
                        <td class="font-bold">{{ $acs['agent_name'] }}</td>
                        <td class="text-right">{{ number_format($acs['credit_issued']) }}</td>
                        <td class="text-right">{{ number_format($acs['collected']) }}</td>
                        <td class="text-right {{ ($acs['collection_pct'] < 80 || $acs['outstanding'] > 200000) ? 'text-rose font-bold' : '' }}">{{ number_format($acs['outstanding']) }}</td>
                        <td class="text-right font-bold {{ $acs['collection_pct'] >= 90 ? 'text-emerald' : ($acs['collection_pct'] >= 80 ? 'text-amber' : 'text-rose') }}">{{ $acs['collection_pct'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- 3. Top 5 Highest Credit Balance Retail Shops -->
    <div class="card">
        <div class="card-title">3. Top 5 Highest Credit Balance Retail Shops (branch-wide, across all agents)</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15px;">#</th>
                    <th>Outlet</th>
                    <th>Agent</th>
                    <th class="text-right">Balance (Rs.)</th>
                    <th class="text-right">Days Outstanding</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['top_credit_shops'] ?? [] as $idx => $tcs)
                    <tr style="{{ $tcs['status'] === 'Overdue' ? 'background: #fff1f2;' : ($idx % 2 === 1 ? 'background: #fffdfa;' : '') }}">
                        <td class="text-center font-bold" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $tcs['outlet_name'] }}</td>
                        <td>{{ $tcs['agent_name'] }}</td>
                        <td class="text-right font-bold {{ $tcs['status'] === 'Overdue' ? 'text-rose' : '' }}">{{ number_format($tcs['balance']) }}</td>
                        <td class="text-right">{{ round(abs((float)($tcs['days_outstanding'] ?? 0))) }}</td>
                        <td class="text-center font-bold {{ $tcs['status'] === 'Overdue' ? 'text-rose' : ($tcs['status'] === 'Near limit' ? 'text-amber' : 'text-emerald') }}">
                            {{ $tcs['status'] === 'Overdue' ? '&Delta; Overdue' : $tcs['status'] }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No overdue records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- SALES GROWTH -->
    <div style="font-size: 11px; font-weight: bold; color: #9a3412; margin-bottom: 6px; padding-bottom: 3px; border-bottom: 1.5px solid #fed7aa;">
        SALES GROWTH <span style="font-size: 7.5px; color: #64748b; font-weight: normal;">(branch-wide, all agents)</span>
    </div>

    <!-- 1. Outlet Growth -->
    <div class="card">
        <div class="card-title">1. Outlet Growth ({{ $data['current_month_label'] ?? 'Current' }})</div>
        <div style="font-size: 8px; line-height: 1.5;">
            Opening active outlets: <strong>{{ $data['outlet_movement']['opening'] }}</strong> &bull;
            New shops: <strong class="text-emerald">+{{ $data['outlet_movement']['new_shops'] }}</strong> &bull;
            Newly dormant: <strong class="text-rose">-{{ $data['outlet_movement']['newly_dormant'] }}</strong> &rarr;
            Closing active outlets: <strong>{{ $data['outlet_movement']['closing'] }}</strong>
            <span class="{{ $data['outlet_movement']['growth_pct'] >= 0 ? 'text-emerald' : 'text-rose' }} font-bold">
                ({{ $data['outlet_movement']['growth_pct'] >= 0 ? '+' : '' }}{{ $data['outlet_movement']['growth_pct'] }}%)
            </span>
        </div>
    </div>

    <!-- 2. Top 5 Highest Sale Retail Shops -->
    <div class="card">
        <div class="card-title">2. Top 5 Highest Sale Retail Shops ({{ $data['current_month_label'] ?? 'Current' }})</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15px;">#</th>
                    <th>Outlet</th>
                    <th>Agent</th>
                    <th class="text-right">Sales (Rs.)</th>
                    <th class="text-right">vs {{ $data['prev_month_label'] ?? 'Prev' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['top_sale_shops'] ?? [] as $idx => $tss)
                    <tr style="{{ $idx % 2 === 1 ? 'background: #fffdfa;' : '' }}">
                        <td class="text-center font-bold" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $tss['outlet_name'] }}</td>
                        <td>{{ $tss['agent_name'] }}</td>
                        <td class="text-right font-bold">{{ number_format($tss['sales']) }}</td>
                        <td class="text-right font-bold {{ $tss['vs_last_month'] >= 0 ? 'text-emerald' : 'text-rose' }}">
                            {{ $tss['vs_last_month'] >= 0 ? '▲ +' : '▼ ' }}{{ $tss['vs_last_month'] }}%
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
        <div class="card-title">3. Top 5 Fast Moving SKUs (ranked by units sold in {{ $data['current_month_label'] ?? 'Current' }})</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15px;">#</th>
                    <th>SKU</th>
                    <th class="text-right">Units Sold</th>
                    <th class="text-right">Units / Working Day</th>
                    <th class="text-right">Sales (Rs.)</th>
                    <th class="text-right">% of Branch Sales</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['top_skus'] ?? [] as $idx => $sku)
                    <tr style="{{ $idx % 2 === 1 ? 'background: #fffdfa;' : '' }}">
                        <td class="text-center font-bold" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $sku['sku_name'] }}</td>
                        <td class="text-right font-bold">{{ number_format($sku['units_sold']) }}</td>
                        <td class="text-right">{{ number_format($sku['units_per_day']) }}</td>
                        <td class="text-right">{{ number_format($sku['sales']) }}</td>
                        <td class="text-right font-bold">{{ $sku['branch_pct'] }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No SKU records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 4. Top 5 Highest Sales Growth Retail Shops -->
    <div class="card">
        <div class="card-title">4. Top 5 Highest Sales Growth Retail Shops (monthly view &mdash; {{ $data['current_month_label'] ?? '' }} vs {{ $data['prev_month_label'] ?? '' }}; ranked by growth in Rs.)</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center" style="width: 15px;">#</th>
                    <th>Outlet</th>
                    <th>Agent</th>
                    <th class="text-right">{{ $data['prev_month_label'] ?? 'Prev' }} (Rs.)</th>
                    <th class="text-right">{{ $data['current_month_label'] ?? 'Cur' }} (Rs.)</th>
                    <th class="text-right">Growth (Rs.)</th>
                    <th class="text-right">Growth %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['top_growth_shops'] ?? [] as $idx => $tgs)
                    <tr style="{{ $idx % 2 === 1 ? 'background: #fffdfa;' : '' }}">
                        <td class="text-center font-bold" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td class="font-bold">{{ $tgs['outlet_name'] }}</td>
                        <td>{{ $tgs['agent_name'] }}</td>
                        <td class="text-right">{{ number_format($tgs['prev_sales']) }}</td>
                        <td class="text-right font-bold">{{ number_format($tgs['current_sales']) }}</td>
                        <td class="text-right font-bold text-emerald">+{{ number_format($tgs['growth_rs']) }}</td>
                        <td class="text-right font-bold text-emerald">+{{ $tgs['growth_pct'] }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No growth records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Next Month Targets -->
    <div class="card">
        <div class="card-title">Next Month Targets ({{ $data['next_targets']['label'] ?? 'Next' }}) &mdash; Entered by Management</div>
        <div style="font-size: 8px; line-height: 1.6;">
            @foreach($data['next_targets']['targets'] ?? [] as $t)
                <span>{{ $t['agent_name'] }}{{ $t['badge'] ? ' (' . $t['badge'] . ')' : '' }}: <strong>Rs. {{ is_numeric($t['target']) ? number_format($t['target']) : $t['target'] }}</strong></span>
                @if(!$loop->last) &bull; @endif
            @endforeach
        </div>
    </div>

    <!-- Legend -->
    <div style="margin-top: 8px; padding-top: 4px; border-top: 1px solid #fed7aa; font-size: 7px;">
        <span style="color: #dc2626; font-weight: bold; margin-right: 15px;">&#9632; Rule violation</span>
        <span style="color: #d97706; font-weight: bold; margin-right: 15px;">&#9632; Warning</span>
        <span style="color: #16a34a; font-weight: bold;">&#9632; On track</span>
    </div>
</body>
</html>
