<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Weekly Agent Review Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1e293b; margin: 0; padding: 0; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; border: none; }
        .header-table td { border: none; padding: 0; vertical-align: top; }
        .company-name { 
            color: {{ $companyInfo['colors']['primary'] ?? '#ff8040' }}; 
            margin: 0 0 3px 0; 
            font-size: 20px;
            font-weight: bold;
        }
        .company-details { margin: 0; color: #64748b; font-size: 10px; line-height: 1.3; }
        .report-title { 
            color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; 
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .sub-header {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .metrics-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 10px;
        }
        .metric-card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            background: #ffffff;
            vertical-align: top;
        }
        .metric-title {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .metric-value {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }

        .large-metric {
            border: 2px solid #d97706;
            background-color: #fffbeb;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .table-simple { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .table-simple th {
            font-size: 9px;
            background-color: #f8fafc;
            color: #64748b;
            padding: 6px 8px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        .table-simple td {
            font-size: 10px;
            padding: 6px 8px;
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
                        <td style="border: none; padding: 0; padding-right: 12px; vertical-align: middle;">
                            @if(isset($companyInfo['logos']['primary']))
                                <img src="{{ public_path($companyInfo['logos']['primary']) }}" style="max-height: 55px;" alt="Company Logo">
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
                <div class="report-title">Weekly Agent Review</div>
                <div style="font-size: 10px; color: #64748b; margin-top: 2px;">
                    <strong>Date Range:</strong> {{ $startDate }} to {{ $endDate }}<br>
                    <strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Sub Header Details -->
    <div class="sub-header">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 35%;"><strong>Agent:</strong> {{ $data['agent']['name'] }} ({{ $data['agent']['code'] }})</td>
                <td style="border: none; width: 35%;"><strong>Routes:</strong> {{ $data['agent']['routes'] }}</td>
                <td style="border: none; width: 30%; text-align: right;"><strong>Monthly Target:</strong> Rs. {{ number_format($data['monthly_target']) }}</td>
            </tr>
        </table>
    </div>

    <!-- 4 Metrics Row -->
    <table class="metrics-grid">
        <tr>
            <td class="metric-card" style="width: 25%;">
                <div class="metric-title">Weekly Sales</div>
                <div class="metric-value">Rs. {{ number_format($data['weekly_sales']) }}</div>
            </td>
            <td class="metric-card" style="width: 25%;">
                <div class="metric-title">Visit Compliance</div>
                <div class="metric-value">{{ $data['visit_compliance']['percent'] }}%</div>
                <div style="font-size: 9px; color: #d97706; margin-top: 3px;">{{ $data['visit_compliance']['visited'] }} of {{ $data['visit_compliance']['total'] }} outlets visited</div>
            </td>
            <td class="metric-card" style="width: 25%;">
                <div class="metric-title">Return %</div>
                <div class="metric-value {{ $data['returns']['percent'] > 5 ? 'text-rose' : 'text-emerald' }}">{{ $data['returns']['percent'] }}%</div>
                <div style="font-size: 9px; margin-top: 3px;" class="{{ $data['returns']['percent'] > 5 ? 'text-rose' : 'text-emerald' }}">
                    {{ $data['returns']['percent'] > 5 ? 'Above 5% threshold' : 'Within threshold' }}
                </div>
            </td>
            <td class="metric-card" style="width: 25%;">
                <div class="metric-title">Credit Utilization</div>
                <div class="metric-value" style="color: #d97706;">{{ $data['credit_utilization']['percent'] }}%</div>
                <div style="font-size: 9px; color: #64748b; margin-top: 3px;">Rs. {{ number_format($data['credit_utilization']['used']) }} / {{ number_format($data['credit_utilization']['limit']) }}</div>
            </td>
        </tr>
    </table>

    <!-- Large Target Metric -->
    <div class="large-metric">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none;">
                    <div class="metric-title" style="color: #92400e;">Remaining to Monthly Target</div>
                    <div style="font-size: 22px; font-weight: bold; color: #b45309; margin-top: 2px;">Rs. {{ number_format($data['target_progress']['remaining']) }}</div>
                    <div style="font-size: 10px; color: #475569; margin-top: 3px;">
                        MTD sales <strong>Rs. {{ number_format($data['target_progress']['mtd_sales']) }}</strong> of <strong>Rs. {{ number_format($data['target_progress']['target']) }}</strong> target
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tables Row (Daily Visits & Returns by Reason) -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td style="width: 49%; vertical-align: top; padding-right: 1%;">
                <div style="font-size: 10px; font-weight: bold; color: #92400e; text-transform: uppercase; margin-bottom: 5px;">Daily Outlet Visits</div>
                <table class="table-simple">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th class="text-center">Outlets Visited</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['daily_visits'] as $day)
                            <tr>
                                <td>{{ $day['day'] }}</td>
                                <td class="text-center font-bold {{ $day['status'] !== 'OK' ? 'text-rose' : '' }}">{{ $day['outlets_visited'] }}</td>
                                <td>
                                    @if($day['status'] === 'OK')
                                        <span class="text-emerald font-bold">OK</span>
                                    @else
                                        <span class="text-rose font-bold">Below 30</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td style="width: 49%; vertical-align: top; padding-left: 1%;">
                <div style="font-size: 10px; font-weight: bold; color: #92400e; text-transform: uppercase; margin-bottom: 5px;">Returns By Reason</div>
                <table class="table-simple">
                    <thead>
                        <tr>
                            <th>Reason</th>
                            <th class="text-right">Value (Rs.)</th>
                            <th class="text-right">% of Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['returns_by_reason'] as $reason)
                            <tr>
                                <td>{{ $reason['reason'] }}</td>
                                <td class="text-right">{{ number_format($reason['value']) }}</td>
                                <td class="text-right font-bold {{ $reason['percent'] > 1 ? 'text-rose' : '' }}">{{ $reason['percent'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center" style="color: #94a3b8; padding: 12px;">No returns recorded</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="font-size: 10px; color: #475569; margin-top: 4px;">
                    Top returned product: <strong>{{ $data['top_return_product']['name'] }}</strong> — {{ $data['top_return_product']['qty'] }} units
                </div>
            </td>
        </tr>
    </table>

    <!-- Bottom Section: Credit & Collections and Outlet Growth -->
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 49%; vertical-align: top; padding-right: 1%;">
                <div class="metric-card">
                    <div style="font-size: 10px; font-weight: bold; color: #92400e; text-transform: uppercase; margin-bottom: 8px;">Credit & Collections</div>
                    <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 4px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">Credit sales this week:</td>
                            <td style="padding: 4px 0; text-align: right; font-weight: bold;" class="text-right">Rs. {{ number_format($data['credit_collections']['credit_sales']) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">Collections this week:</td>
                            <td style="padding: 4px 0; text-align: right;" class="text-right">
                                <strong>Rs. {{ number_format($data['credit_collections']['collections']) }}</strong>
                                <span class="text-emerald">({{ $data['credit_collections']['collection_rate'] }}%)</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0; color: #64748b;">Closing dues:</td>
                            <td style="padding: 4px 0; text-align: right;" class="text-right">
                                <strong>Rs. {{ number_format($data['credit_collections']['closing_dues']) }}</strong> | Aged 30+: <strong class="text-rose">Rs. {{ number_format($data['credit_collections']['aged_30_days']) }}</strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="width: 49%; vertical-align: top; padding-left: 1%;">
                <div class="metric-card">
                    <div style="font-size: 10px; font-weight: bold; color: #92400e; text-transform: uppercase; margin-bottom: 8px;">Outlet Growth</div>
                    <table style="width: 100%; font-size: 10px; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 4px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">New shops added:</td>
                            <td style="padding: 4px 0; text-align: right; font-weight: bold;" class="text-right text-emerald">+{{ $data['outlet_growth']['new_shops'] }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0; color: #64748b; border-bottom: 1px solid #f1f5f9;">Dormant shops (14+ days):</td>
                            <td style="padding: 4px 0; text-align: right; font-weight: bold;" class="text-right text-rose">{{ $data['outlet_growth']['dormant_shops'] }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0; color: #64748b;">Active outlets:</td>
                            <td style="padding: 4px 0; text-align: right; font-weight: bold;" class="text-right">{{ $data['outlet_growth']['active_outlets'] }} / {{ $data['outlet_growth']['total_outlets'] }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
