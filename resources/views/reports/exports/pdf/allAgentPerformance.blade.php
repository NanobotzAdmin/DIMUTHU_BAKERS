<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Agent Performance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #1e293b; margin: 0; padding: 0; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; border: none; }
        .header-table td { border: none; padding: 0; vertical-align: top; }
        .company-name { 
            color: {{ $companyInfo['colors']['primary'] ?? '#ff8040' }}; 
            margin: 0 0 2px 0; 
            font-size: 18px;
            font-weight: bold;
        }
        .company-details { margin: 0; color: #64748b; font-size: 9px; line-height: 1.3; }
        .report-title { 
            color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; 
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 8px 10px;
            background: #ffffff;
            margin-bottom: 10px;
        }

        .card-title {
            font-size: 9px;
            font-weight: bold;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .table-simple { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .table-simple th {
            font-size: 8px;
            background-color: #f8fafc;
            color: #64748b;
            padding: 4px 5px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        .table-simple td {
            font-size: 8.5px;
            padding: 4px 5px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-rose { color: #e11d48; }
        .text-emerald { color: #059669; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <!-- Top Bakery Header -->
    <table class="header-table">
        <tr>
            <td style="width: 60%; text-align: left;">
                <table style="border: none; margin: 0; padding: 0; width: auto;">
                    <tr>
                        <td style="border: none; padding: 0; padding-right: 10px; vertical-align: middle;">
                            @if(isset($companyInfo['logos']['primary']))
                                <img src="{{ public_path($companyInfo['logos']['primary']) }}" style="max-height: 50px;" alt="Company Logo">
                            @endif
                        </td>
                        <td style="border: none; padding: 0; vertical-align: middle; text-align: left;">
                            <div class="company-name">{{ $companyInfo['business_name'] ?? 'Dimuthu Bakers' }}</div>
                            <div class="company-details">
                                {{ $companyInfo['address']['street'] ?? '' }}, {{ $companyInfo['address']['city'] ?? '' }}<br>
                                Tel: {{ $companyInfo['contact']['phone'] ?? '' }} {{ isset($companyInfo['contact']['mobile']) ? ' / ' . $companyInfo['contact']['mobile'] : '' }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%; text-align: right; vertical-align: bottom;">
                <div class="report-title">All Agent Performance</div>
                <div style="font-size: 10px; color: #64748b;">
                    <strong>Month:</strong> {{ $monthYear }}<br>
                    <strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Leaderboard Table -->
    <div class="card">
        <div class="card-title">Agent Leaderboard (Sorted by Achievement %)</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Agent</th>
                    <th class="text-right">Sales (Rs.)</th>
                    <th class="text-center">Achievement</th>
                    <th class="text-right">Remaining (Rs.)</th>
                    <th class="text-center">Visits</th>
                    <th class="text-center">Returns</th>
                    <th class="text-center">Collections</th>
                    <th class="text-center">New Shops</th>
                    <th class="text-center">Credit Util.</th>
                    <th class="text-center">Bonus</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['leaderboard'] as $index => $row)
                    <tr>
                        <td class="text-center font-bold">{{ $index + 1 }}</td>
                        <td class="font-bold">
                            {{ $row['agent_name'] }}
                            @if($row['badge'])
                                <span style="font-size: 7.5px; background: #e0f2fe; color: #0369a1; padding: 1px 3px; border-radius: 2px;">{{ $row['badge'] }}</span>
                            @endif
                        </td>
                        <td class="text-right font-bold">Rs. {{ number_format($row['sales']) }}</td>
                        <td class="text-center font-bold {{ $row['achievement'] >= 100 ? 'text-emerald' : '' }}">{{ $row['achievement'] }}%</td>
                        <td class="text-right">Rs. {{ number_format($row['remaining']) }}</td>
                        <td class="text-center">{{ $row['visit_compliance'] }}%</td>
                        <td class="text-center font-bold {{ $row['return_percent'] > 5 ? 'text-rose' : '' }}">{{ $row['return_percent'] }}%</td>
                        <td class="text-center">{{ $row['collection_percent'] }}%</td>
                        <td class="text-center text-emerald font-bold">+{{ $row['new_shops'] }}</td>
                        <td class="text-center">{{ $row['credit_util'] }}%</td>
                        <td class="text-center font-bold {{ $row['bonus_qualified'] ? 'text-emerald' : 'text-rose' }}">
                            {{ $row['bonus_qualified'] ? 'Qualified' : 'Not Met' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Outlet Base Movement & 3-Month Return Trend -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td style="width: 49%; vertical-align: top; padding-right: 1%;">
                <div class="card">
                    <div class="card-title">Outlet Base Movement</div>
                    <table style="width: 100%; font-size: 9px; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 3px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">Active opening outlets:</td>
                            <td style="padding: 3px 0; text-align: right;" class="text-right font-bold">{{ $data['outlet_movement']['opening'] }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">New shops added:</td>
                            <td style="padding: 3px 0; text-align: right;" class="text-right font-bold text-emerald">+{{ $data['outlet_movement']['new_shops'] }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">Newly dormant outlets:</td>
                            <td style="padding: 3px 0; text-align: right;" class="text-right font-bold text-rose">-{{ $data['outlet_movement']['newly_dormant'] }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; font-weight: bold;">Closing active outlets:</td>
                            <td style="padding: 3px 0; text-align: right;" class="text-right font-bold">{{ $data['outlet_movement']['closing'] }} (<span class="{{ $data['outlet_movement']['growth_pct'] >= 0 ? 'text-emerald' : 'text-rose' }}">{{ $data['outlet_movement']['growth_pct'] >= 0 ? '+' : '' }}{{ $data['outlet_movement']['growth_pct'] }}%</span>)</td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="width: 49%; vertical-align: top; padding-left: 1%;">
                <div class="card">
                    <div class="card-title">3-Month Return Trend</div>
                    <table class="table-simple">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th class="text-center">Defect %</th>
                                <th class="text-center">Other %</th>
                                <th class="text-right">Total Returns</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['return_trend'] as $rt)
                                <tr>
                                    <td>{{ $rt['month_label'] }}</td>
                                    <td class="text-center">{{ $rt['defect_pct'] }}%</td>
                                    <td class="text-center font-bold text-rose">{{ $rt['other_pct'] }}%</td>
                                    <td class="text-right font-bold">Rs. {{ number_format($rt['total_val']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Credit Ageing Summary -->
    <div class="card">
        <div class="card-title">Overall Credit Ageing Summary</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center">Current (0-30 days)</th>
                    <th class="text-center">31–60 days</th>
                    <th class="text-center">61–90 days</th>
                    <th class="text-center">90+ days</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center font-bold">Rs. {{ number_format($data['credit_ageing']['current']['amount']) }} ({{ $data['credit_ageing']['current']['pct'] }}%)</td>
                    <td class="text-center font-bold">Rs. {{ number_format($data['credit_ageing']['days_30']['amount']) }} ({{ $data['credit_ageing']['days_30']['pct'] }}%)</td>
                    <td class="text-center font-bold">Rs. {{ number_format($data['credit_ageing']['days_60']['amount']) }} ({{ $data['credit_ageing']['days_60']['pct'] }}%)</td>
                    <td class="text-center font-bold text-rose">Rs. {{ number_format($data['credit_ageing']['days_60_plus']['amount']) }} ({{ $data['credit_ageing']['days_60_plus']['pct'] }}%)</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
