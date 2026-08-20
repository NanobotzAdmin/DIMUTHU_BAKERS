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
                Agent Order Requests Report
            </span>
        </th>
    </tr>
    <tr>
        <th colspan="3" style="text-align: right; vertical-align: bottom; background-color: #ffffff; border: none; font-size: 12px;">
            <strong>Agent:</strong> {{ $agent->agent_name ?? 'Unknown' }}
        </th>
    </tr>
    <tr>
        <th colspan="3" style="text-align: right; vertical-align: bottom; background-color: #ffffff; border: none; font-size: 12px;">
            <strong>Date:</strong> {{ $selectedDate ?? ($dateRange ?? 'All Time') }}
        </th>
    </tr>
    {{-- Temporarily hidden Date Range (uncomment when needed)
    <tr>
        <th colspan="3" style="text-align: right; vertical-align: bottom; background-color: #ffffff; border: none; font-size: 12px;">
            <strong>Date Range:</strong> {{ $dateRange }}
        </th>
    </tr>
    --}}
    <tr>
        <th colspan="7" style="border: none;"></th>
    </tr>
    
    <!-- Summary Section -->
    <tr>
        <th colspan="2" style="background-color: #f3f4f6; text-align: left; font-weight: bold;">Summary</th>
        <th colspan="5" style="background-color: #f3f4f6;"></th>
    </tr>
    <tr>
        <td colspan="2">Total Purchase Amount (Rs)</td>
        <td colspan="5" style="font-weight: bold;">{{ number_format($summary['total_order_amount'], 2, '.', '') }}</td>
    </tr>
    <tr>
        <td colspan="2">Total Outstanding (Rs)</td>
        <td colspan="5" style="font-weight: bold;">{{ number_format($summary['total_outstanding'], 2, '.', '') }}</td>
    </tr>
    <tr>
        <td colspan="2">Total Paid Amount (Rs)</td>
        <td colspan="5" style="font-weight: bold;">{{ number_format($summary['total_paid_amount'], 2, '.', '') }}</td>
    </tr>
    <tr>
        <th colspan="7" style="border: none;"></th>
    </tr>

    <!-- Orders Section -->
    <tr>
        <th colspan="7" style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold; font-size: 14px; text-align: left;">
            Order Requests
        </th>
    </tr>
    <tr>
        <th style="background-color: #e2e8f0; font-weight: bold;">#</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Order No</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Delivery Date</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Grand Total (Rs)</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Outstanding (Rs)</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Paid (Rs)</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Status</th>
    </tr>
    @forelse($orders as $index => $order)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $order['order_number'] }}</td>
            <td>{{ $order['delivery_date'] }}</td>
            <td>{{ $order['grand_total'] }}</td>
            <td>{{ $order['outstanding'] }}</td>
            <td>{{ $order['paid_amount'] }}</td>
            <td>{{ $order['status'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align: center;">No orders found for the selected date.</td>
        </tr>
    @endforelse

    <tr>
        <th colspan="7" style="border: none;"></th>
    </tr>

    <!-- Payments Section -->
    {{-- 
    <tr>
        <th colspan="7" style="background-color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; color: #ffffff; font-weight: bold; font-size: 14px; text-align: left;">
            Payment Details
        </th>
    </tr>
    <tr>
        <th style="background-color: #e2e8f0; font-weight: bold;">#</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Payment No</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Payment Date</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Amount (Rs)</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Method</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Reference/Notes</th>
        <th style="background-color: #e2e8f0; font-weight: bold;">Status</th>
    </tr>
    @forelse($payments as $index => $payment)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $payment['payment_number'] }}</td>
            <td>{{ $payment['payment_date'] }}</td>
            <td>{{ $payment['payment_amount'] }}</td>
            <td>{{ $payment['payment_method'] }}</td>
            <td>{{ $payment['payment_reference'] }}</td>
            <td>{{ $payment['status'] }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" style="text-align: center;">No payments found in the selected date range.</td>
        </tr>
    @endforelse
    <tr>
        <th colspan="3" style="text-align: right; font-weight: bold;">Total Payment Amount (Rs)</th>
        <th style="font-weight: bold;">{{ number_format($summary['total_payment_amount'], 2, '.', '') }}</th>
        <th colspan="3"></th>
    </tr>
    --}}
</table>
