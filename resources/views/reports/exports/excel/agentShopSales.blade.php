<table border="1">
    <tr>
        <th colspan="4" rowspan="3" style="text-align: left; vertical-align: top; background-color: #ffffff; border: none;">
            <span style="color: {{ $companyInfo['colors']['primary'] ?? '#ff8040' }}; font-size: 24px; font-weight: bold;">
                {{ $companyInfo['business_name'] ?? 'Dimuthu Bakers' }}
            </span><br>
            <span style="font-size: 11px; color: #555555;">
                {{ $companyInfo['address']['street'] ?? '' }}, {{ $companyInfo['address']['city'] ?? '' }}<br>
                Tel: {{ $companyInfo['contact']['phone'] ?? '' }} {{ isset($companyInfo['contact']['mobile']) ? ' / ' . $companyInfo['contact']['mobile'] : '' }}
            </span>
        </th>
        <th colspan="4" style="text-align: right; vertical-align: top; background-color: #ffffff; border: none;">
            <span style="color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; font-size: 18px; font-weight: bold;">
                Agent Shop Sales Report
            </span>
        </th>
    </tr>
    <tr>
        <th colspan="4" style="text-align: right; vertical-align: bottom; background-color: #ffffff; border: none; font-size: 12px;">
            <strong>Agent:</strong> {{ $agentName }}
        </th>
    </tr>
    <tr>
        <th colspan="4" style="text-align: right; vertical-align: bottom; background-color: #ffffff; border: none; font-size: 12px;">
            <strong>Date Range:</strong> {{ $dateRange }}
        </th>
    </tr>
    <tr>
        <th colspan="8" style="border: none;"></th>
    </tr>
    <tr>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold;">#</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold;">Customer Name</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold;">Visit Count</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold;">Total Sales (Rs)</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold;">Total Returns (Rs)</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold;">Cash Income (Rs)</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold;">Total Credit (Rs)</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold;">Outstanding (Rs)</th>
    </tr>
    
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
            <td>{{ $index + 1 }}</td>
            <td>{{ $row['customer_name'] }}</td>
            <td>{{ $row['visit_count'] }}</td>
            <td>{{ $row['total_sales'] }}</td>
            <td>{{ $row['total_returns'] }}</td>
            <td>{{ $row['cash_income'] }}</td>
            <td>{{ $row['total_credit'] }}</td>
            <td>{{ $row['outstanding_amount'] }}</td>
        </tr>
    @endforeach
    
    <tr>
        <th colspan="2" style="font-weight: bold; text-align: right;">TOTAL</th>
        <th style="font-weight: bold;">{{ $sumVisits }}</th>
        <th style="font-weight: bold;">{{ $sumSales }}</th>
        <th style="font-weight: bold;">{{ $sumReturns }}</th>
        <th style="font-weight: bold;">{{ $sumIncome }}</th>
        <th style="font-weight: bold;">{{ $sumCredit }}</th>
        <th style="font-weight: bold;">{{ $sumOutstanding }}</th>
    </tr>
</table>
