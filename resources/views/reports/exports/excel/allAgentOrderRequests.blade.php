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
        <th colspan="3" style="text-align: right; vertical-align: top; background-color: #ffffff; border: none;">
            <span style="color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; font-size: 18px; font-weight: bold;">
                Agent Order Requests Summary Report
            </span>
        </th>
    </tr>
    <tr>
        <th colspan="3" style="text-align: right; vertical-align: bottom; background-color: #ffffff; border: none; font-size: 12px;">
            <strong>Date:</strong> {{ $selectedDate ?? ($dateRange ?? 'All Time') }}
        </th>
    </tr>
    <tr>
        <th colspan="3" style="text-align: right; vertical-align: bottom; background-color: #ffffff; border: none; font-size: 12px;">
            <strong>Generated At:</strong> {{ now()->format('Y-m-d H:i') }}
        </th>
    </tr>
    <tr>
        <th colspan="7" style="border: none;"></th>
    </tr>

    <!-- Table Header matching bottom overview table -->
    <tr>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold; text-align: center;">#</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold; text-align: left;">Agent Name</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold; text-align: left;">Agent Code</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold; text-align: center;">Total Orders</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold; text-align: right;">Total Purchase Amount (Rs)</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold; text-align: right;">Outstanding (Rs)</th>
        <th style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold; text-align: right;">Paid Amount (Rs)</th>
    </tr>

    <!-- Table Data Rows -->
    @forelse($reportData as $index => $row)
        <tr>
            <td style="text-align: center;">{{ $index + 1 }}</td>
            <td style="text-align: left;">{{ $row['agent_name'] }}</td>
            <td style="text-align: left;">{{ $row['agent_code'] }}</td>
            <td style="text-align: center;">{{ $row['total_orders'] }}</td>
            <td style="text-align: right;">{{ number_format($row['total_order_amount'], 2, '.', '') }}</td>
            <td style="text-align: right;">{{ number_format($row['outstanding'], 2, '.', '') }}</td>
            <td style="text-align: right;">{{ number_format($row['paid_amount'], 2, '.', '') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align: center;">No agent order requests found for the selected date.</td>
        </tr>
    @endforelse

    <!-- Table Footer matching bottom overview table -->
    <tr>
        <th colspan="3" style="background-color: #f1f5f9; font-weight: bold; text-align: right;">TOTAL</th>
        <th style="background-color: #f1f5f9; font-weight: bold; text-align: center; color: #15803d;">{{ $summary['total_orders'] }}</th>
        <th style="background-color: #f1f5f9; font-weight: bold; text-align: right;">{{ number_format($summary['total_order_amount'], 2, '.', '') }}</th>
        <th style="background-color: #f1f5f9; font-weight: bold; text-align: right;">{{ number_format($summary['outstanding'], 2, '.', '') }}</th>
        <th style="background-color: #f1f5f9; font-weight: bold; text-align: right;">{{ number_format($summary['paid_amount'], 2, '.', '') }}</th>
    </tr>
</table>
