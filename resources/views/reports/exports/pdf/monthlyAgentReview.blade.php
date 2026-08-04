<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Agent Review Report</title>
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

        .sub-header {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            padding: 8px 10px;
            border-radius: 5px;
            margin-bottom: 12px;
            font-size: 10px;
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
            font-size: 8.5px;
            background-color: #f8fafc;
            color: #64748b;
            padding: 5px 6px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        .table-simple td {
            font-size: 9.5px;
            padding: 5px 6px;
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
                <div class="report-title">Monthly Agent Review</div>
                <div style="font-size: 10px; color: #64748b;">
                    <strong>Month:</strong> {{ $monthYear }}<br>
                    <strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Sub Header Details -->
    <div class="sub-header">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 33%;"><strong>Agent:</strong> {{ $data['agent']['name'] }} ({{ $data['agent']['code'] }})</td>
                <td style="border: none; width: 34%;"><strong>Routes:</strong> {{ $data['agent']['routes'] }}</td>
                <td style="border: none; width: 33%; text-align: right;"><strong>Monthly Target:</strong> Rs. {{ number_format($data['monthly_target']) }}</td>
            </tr>
        </table>
    </div>

    <!-- 2 Top Metric Cards Row -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td style="width: 49%; vertical-align: top; padding-right: 1%;">
                <div class="card">
                    <div class="card-title">Monthly Sales Performance</div>
                    <div style="font-size: 20px; font-weight: bold; color: #0f172a;">Rs. {{ number_format($data['sales']['current']) }}</div>
                    <div style="font-size: 9.5px; color: #475569; margin-top: 3px;">
                        MoM Growth: <strong class="{{ $data['sales']['growth'] >= 0 ? 'text-emerald' : 'text-rose' }}">{{ $data['sales']['growth'] >= 0 ? '+' : '' }}{{ $data['sales']['growth'] }}%</strong> vs Prev Month (Rs. {{ number_format($data['sales']['prev']) }})
                    </div>
                </div>
            </td>
            <td style="width: 49%; vertical-align: top; padding-left: 1%;">
                <div class="card" style="border: 2px solid #d97706; background-color: #fffbeb;">
                    <div class="card-title">Target Achievement</div>
                    <div style="font-size: 20px; font-weight: bold; color: #b45309;">{{ $data['sales']['percent_target'] }}% Target Reached</div>
                    <div style="font-size: 9.5px; color: #475569; margin-top: 3px;">
                        Remaining to target: <strong style="color: #b45309;">Rs. {{ number_format($data['sales']['remaining']) }}</strong>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Month-over-Month Comparison -->
    <div class="card">
        <div class="card-title">Month-over-Month (MoM) Comparison</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th>KPI / Metric</th>
                    <th class="text-right">{{ $data['mom']['prev_month_label'] }} (Prev)</th>
                    <th class="text-right">{{ $data['mom']['curr_month_label'] }} (Current)</th>
                    <th class="text-right">MoM Trend</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Sales (Rs.)</td>
                    <td class="text-right">Rs. {{ number_format($data['sales']['prev']) }}</td>
                    <td class="text-right font-bold">Rs. {{ number_format($data['sales']['current']) }}</td>
                    <td class="text-right font-bold {{ $data['mom']['sales_trend'] >= 0 ? 'text-emerald' : 'text-rose' }}">{{ $data['mom']['sales_trend'] >= 0 ? '+' : '' }}{{ $data['mom']['sales_trend'] }}%</td>
                </tr>
                <tr>
                    <td>Return Rate (%)</td>
                    <td class="text-right">{{ $data['mom']['return_prev'] }}%</td>
                    <td class="text-right font-bold {{ $data['mom']['return_curr'] > 5 ? 'text-rose' : 'text-emerald' }}">{{ $data['mom']['return_curr'] }}%</td>
                    <td class="text-right font-bold {{ ($data['mom']['return_curr'] - $data['mom']['return_prev']) <= 0 ? 'text-emerald' : 'text-rose' }}">
                        {{ ($data['mom']['return_curr'] - $data['mom']['return_prev']) > 0 ? '+' : '' }}{{ number_format($data['mom']['return_curr'] - $data['mom']['return_prev'], 1) }}%
                    </td>
                </tr>
                <tr>
                    <td>Collection Rate (%)</td>
                    <td class="text-right">{{ $data['mom']['collection_prev'] }}%</td>
                    <td class="text-right font-bold">{{ $data['mom']['collection_curr'] }}%</td>
                    <td class="text-right font-bold {{ ($data['mom']['collection_curr'] - $data['mom']['collection_prev']) >= 0 ? 'text-emerald' : 'text-rose' }}">
                        {{ ($data['mom']['collection_curr'] - $data['mom']['collection_prev']) > 0 ? '+' : '' }}{{ $data['mom']['collection_curr'] - $data['mom']['collection_prev'] }}%
                    </td>
                </tr>
                <tr>
                    <td>New Outlets Added</td>
                    <td class="text-right">+{{ $data['mom']['new_shops_prev'] }}</td>
                    <td class="text-right font-bold text-emerald">+{{ $data['mom']['new_shops_curr'] }}</td>
                    <td class="text-right font-bold">{{ $data['mom']['new_shops_curr'] - $data['mom']['new_shops_prev'] > 0 ? '+' : '' }}{{ $data['mom']['new_shops_curr'] - $data['mom']['new_shops_prev'] }}</td>
                </tr>
                <tr>
                    <td>Dormant Outlets (14+ days)</td>
                    <td class="text-right">{{ $data['mom']['dormant_prev'] }}</td>
                    <td class="text-right font-bold text-rose">{{ $data['mom']['dormant_curr'] }}</td>
                    <td class="text-right font-bold {{ ($data['mom']['dormant_curr'] - $data['mom']['dormant_prev']) <= 0 ? 'text-emerald' : 'text-rose' }}">
                        {{ ($data['mom']['dormant_curr'] - $data['mom']['dormant_prev']) > 0 ? '+' : '' }}{{ $data['mom']['dormant_curr'] - $data['mom']['dormant_prev'] }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Week-by-Week Breakdown -->
    <div class="card">
        <div class="card-title">Week-by-Week Breakdown</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th>Week Period</th>
                    <th class="text-right">Sales (Rs.)</th>
                    <th class="text-right">Visit Compliance</th>
                    <th class="text-right">Return %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['week_by_week'] as $week)
                    <tr>
                        <td>{{ $week['label'] }}</td>
                        <td class="text-right font-bold">Rs. {{ number_format($week['sales']) }}</td>
                        <td class="text-right">{{ $week['visit_compliance'] }}%</td>
                        <td class="text-right font-bold {{ $week['return_percent'] > 5 ? 'text-rose' : '' }}">{{ $week['return_percent'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Commission & Earnings -->
    <div class="card">
        <div class="card-title">Commission & Earnings Statement</div>
        <table style="width: 100%; border-collapse: collapse; font-size: 9.5px;">
            <tr>
                <td style="padding: 4px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">Invoiced Value:</td>
                <td style="padding: 4px 0; text-align: right;" class="text-right font-bold">Rs. {{ number_format($data['commission']['invoiced_value']) }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">Total Returns:</td>
                <td style="padding: 4px 0; text-align: right;" class="text-right text-rose font-bold">(Rs. {{ number_format($data['commission']['returns']) }})</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">Net Eligible Sales:</td>
                <td style="padding: 4px 0; text-align: right;" class="text-right font-bold">Rs. {{ number_format($data['commission']['net_sales']) }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">Invoicing Commission ({{ $data['commission']['invoicing_rate'] }}%):</td>
                <td style="padding: 4px 0; text-align: right;" class="text-right font-bold text-emerald">Rs. {{ number_format($data['commission']['invoicing_commission'], 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">Target Bonus Rate ({{ $data['commission']['target_rate'] }}%):</td>
                <td style="padding: 4px 0; text-align: right;" class="text-right font-bold text-emerald">Rs. {{ number_format($data['commission']['target_bonus'], 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-size: 11px; font-weight: bold;">Total Estimated Payable:</td>
                <td style="padding: 6px 0; text-align: right; font-size: 12px; font-weight: bold; color: #059669;" class="text-right">Rs. {{ number_format($data['commission']['total_payable'], 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Credit Position (Ageing) -->
    <div class="card">
        <div class="card-title">Credit Position & Ageing (Closing Dues: Rs. {{ number_format($data['credit']['closing_dues']) }})</div>
        <table class="table-simple">
            <thead>
                <tr>
                    <th class="text-center">Current (0-30 days)</th>
                    <th class="text-center">31–60 days</th>
                    <th class="text-center">61–90 days</th>
                    <th class="text-center">90+ days</th>
                    <th class="text-center">Credit Util.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center font-bold">Rs. {{ number_format($data['credit']['current']) }}</td>
                    <td class="text-center font-bold">Rs. {{ number_format($data['credit']['days_1_30']) }}</td>
                    <td class="text-center font-bold">Rs. {{ number_format($data['credit']['days_31_60']) }}</td>
                    <td class="text-center font-bold text-rose">Rs. {{ number_format($data['credit']['days_60_plus']) }}</td>
                    <td class="text-center font-bold">{{ $data['credit']['utilization'] }}%</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
