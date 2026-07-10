<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Agent Shop Sales Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-name { 
            color: {{ $companyInfo['colors']['primary'] ?? '#ff8040' }}; 
            margin: 5px 0; 
            font-size: 24px;
            font-weight: bold;
        }
        .company-details { margin: 0; color: #555; font-size: 11px; }
        .report-title { 
            color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; 
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: none; }
        .header-table td { border: none; padding: 0; vertical-align: top; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .data-table th { background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: white; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background-color: #f0f0f0; }
        .total-row td { border-top: 2px solid {{ $companyInfo['colors']['primary'] ?? '#ff8040' }}; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 60%; text-align: left;">
                <table style="border: none; margin: 0; padding: 0; width: auto;">
                    <tr>
                        <td style="border: none; padding: 0; padding-right: 15px; vertical-align: middle;">
                            @if(isset($companyInfo['logos']['primary']))
                                <img src="{{ public_path($companyInfo['logos']['primary']) }}" style="max-height: 65px;" alt="Company Logo">
                            @endif
                        </td>
                        <td style="border: none; padding: 0; vertical-align: middle; text-align: left;">
                            <div class="company-name" style="margin-top: 0;">{{ $companyInfo['business_name'] ?? 'Dimuthu Bakers' }}</div>
                            <div class="company-details">
                                {{ $companyInfo['address']['street'] ?? '' }}, {{ $companyInfo['address']['city'] ?? '' }}<br>
                                Tel: {{ $companyInfo['contact']['phone'] ?? '' }} {{ isset($companyInfo['contact']['mobile']) ? ' / ' . $companyInfo['contact']['mobile'] : '' }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%; text-align: right; vertical-align: bottom;">
                <div class="report-title">Agent Shop Sales Report</div>
                <div style="font-size: 12px; color: #333; margin-top: 8px;">
                    <strong>Agent:</strong> {{ $agentName }}<br>
                    <strong style="display: inline-block; margin-top: 4px;">Date Range:</strong> {{ $dateRange }}
                </div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Customer Name</th>
                <th class="text-center">Visit Count</th>
                <th class="text-right">Total Sales (Rs)</th>
                <th class="text-right">Total Returns (Rs)</th>
                <th class="text-right">Cash Income (Rs)</th>
                <th class="text-right">Total Credit (Rs)</th>
                <th class="text-right">Outstanding (Rs)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sumVisits = 0;
                $sumSales = 0;
                $sumReturns = 0;
                $sumIncome = 0;
                $sumCredit = 0;
                $sumOutstanding = 0;
            @endphp
            @foreach($reportData as $index => $row)
                @php
                    $sumVisits += $row['visit_count'];
                    $sumSales += $row['total_sales'];
                    $sumReturns += $row['total_returns'];
                    $sumIncome += $row['cash_income'];
                    $sumCredit += $row['total_credit'];
                    $sumOutstanding += $row['outstanding_amount'];
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row['customer_name'] }}</td>
                    <td class="text-center">{{ $row['visit_count'] }}</td>
                    <td class="text-right">{{ number_format($row['total_sales'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['total_returns'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['cash_income'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['total_credit'], 2) }}</td>
                    <td class="text-right">{{ number_format($row['outstanding_amount'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" class="text-right">TOTAL</td>
                <td class="text-center">{{ $sumVisits }}</td>
                <td class="text-right">{{ number_format($sumSales, 2) }}</td>
                <td class="text-right">{{ number_format($sumReturns, 2) }}</td>
                <td class="text-right">{{ number_format($sumIncome, 2) }}</td>
                <td class="text-right">{{ number_format($sumCredit, 2) }}</td>
                <td class="text-right">{{ number_format($sumOutstanding, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
