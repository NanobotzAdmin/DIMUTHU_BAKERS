<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Financial Report</title>
</head>
<body>

    <!-- Header Section -->
    <table>
        <tr>
            <th colspan="5" style="font-size: 24px; font-weight: bold; text-align: center; color: #0f172a; height: 40px; background-color: #f1f5f9;">
                DIMUTHU BAKERS
            </th>
        </tr>
        <tr>
            <th colspan="5" style="font-size: 16px; text-align: center; color: #334155; height: 30px; background-color: #f8fafc;">
                AGENT FINANCIAL MANAGEMENT REPORT
            </th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; color: #64748b; font-style: italic; height: 20px;">
                Generated on: {{ now()->format('M d, Y h:i A') }}
            </th>
        </tr>
        <tr><td colspan="5"></td></tr>
    </table>

    <!-- Summary Section -->
    <table>
        <tr>
            <th colspan="5" style="font-size: 14px; font-weight: bold; background-color: #1e293b; color: #ffffff; text-align: left; padding: 5px;">
                FINANCIAL SUMMARY
            </th>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #f8fafc; border: 1px solid #cbd5e1;">Total Payments</td>
            <td style="color: #059669; font-weight: bold; border: 1px solid #cbd5e1;">Rs. {{ number_format($summary['totalPayments'], 2) }}</td>
            <td style="border: 1px solid #ffffff;"></td>
            <td style="font-weight: bold; background-color: #f8fafc; border: 1px solid #cbd5e1;">Pending Payments</td>
            <td style="color: #d97706; font-weight: bold; border: 1px solid #cbd5e1;">{{ $summary['pendingPayments'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #f8fafc; border: 1px solid #cbd5e1;">Total Credit Notes</td>
            <td style="color: #2563eb; font-weight: bold; border: 1px solid #cbd5e1;">Rs. {{ number_format($summary['totalCreditNotes'], 2) }}</td>
            <td style="border: 1px solid #ffffff;"></td>
            <td style="font-weight: bold; background-color: #f8fafc; border: 1px solid #cbd5e1;">Pending Credit Notes</td>
            <td style="color: #e11d48; font-weight: bold; border: 1px solid #cbd5e1;">{{ $summary['pendingCreditNotes'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; background-color: #f8fafc; border: 1px solid #cbd5e1;">Used Credit Notes</td>
            <td style="color: #0284c7; font-weight: bold; border: 1px solid #cbd5e1;">Rs. {{ number_format($summary['usedCreditNotes'], 2) }}</td>
            <td style="border: 1px solid #ffffff;"></td>
            <td style="font-weight: bold; background-color: #f8fafc; border: 1px solid #cbd5e1;">Total Outstanding</td>
            <td style="color: #4f46e5; font-weight: bold; border: 1px solid #cbd5e1;">Rs. {{ number_format($summary['totalAgentOutstanding'], 2) }}</td>
        </tr>
        <tr><td colspan="5"></td></tr>
    </table>

    <!-- Agent Payments Data -->
    <table>
        <tr>
            <th colspan="5" style="font-size: 14px; font-weight: bold; background-color: #4338ca; color: #ffffff; text-align: left; padding: 5px;">
                AGENT PAYMENTS
            </th>
        </tr>
        <tr>
            <th style="background-color: #e0e7ff; color: #3730a3; font-weight: bold; border: 1px solid #c7d2fe;">Date</th>
            <th style="background-color: #e0e7ff; color: #3730a3; font-weight: bold; border: 1px solid #c7d2fe;">Agent</th>
            <th style="background-color: #e0e7ff; color: #3730a3; font-weight: bold; border: 1px solid #c7d2fe;">Method</th>
            <th style="background-color: #e0e7ff; color: #3730a3; font-weight: bold; border: 1px solid #c7d2fe;">Amount</th>
            <th style="background-color: #e0e7ff; color: #3730a3; font-weight: bold; border: 1px solid #c7d2fe;">Status</th>
        </tr>
        @forelse($payments as $payment)
        <tr>
            <td style="border: 1px solid #e2e8f0;">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d H:i') : $payment->created_at->format('Y-m-d') }}</td>
            <td style="border: 1px solid #e2e8f0;">{{ $payment->agent->agent_name ?? 'N/A' }}</td>
            <td style="border: 1px solid #e2e8f0;">
                @php
                    $methodText = match((int)$payment->payment_method) {
                        1 => 'Cash',
                        2 => 'Card',
                        3 => 'Bank Transfer',
                        4 => 'Credit Note',
                        default => 'Other'
                    };
                @endphp
                {{ $methodText }}
            </td>
            <td style="border: 1px solid #e2e8f0;">{{ $payment->amount }}</td>
            <td style="border: 1px solid #e2e8f0;">
                @php
                    $statusText = match((int)$payment->status) {
                        0 => 'Pending',
                        1 => 'Approved',
                        2 => 'Rejected',
                        default => 'Unknown'
                    };
                @endphp
                {{ $statusText }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align: center; font-style: italic; border: 1px solid #e2e8f0;">No payments found.</td>
        </tr>
        @endforelse
        <tr><td colspan="5"></td></tr>
    </table>

    <!-- Credit Notes Data -->
    <table>
        <tr>
            <th colspan="6" style="font-size: 14px; font-weight: bold; background-color: #be185d; color: #ffffff; text-align: left; padding: 5px;">
                CREDIT NOTES
            </th>
        </tr>
        <tr>
            <th style="background-color: #fce7f3; color: #831843; font-weight: bold; border: 1px solid #fbcfe8;">CN Number</th>
            <th style="background-color: #fce7f3; color: #831843; font-weight: bold; border: 1px solid #fbcfe8;">Agent</th>
            <th style="background-color: #fce7f3; color: #831843; font-weight: bold; border: 1px solid #fbcfe8;">Date</th>
            <th style="background-color: #fce7f3; color: #831843; font-weight: bold; border: 1px solid #fbcfe8;">Type</th>
            <th style="background-color: #fce7f3; color: #831843; font-weight: bold; border: 1px solid #fbcfe8;">Amount</th>
            <th style="background-color: #fce7f3; color: #831843; font-weight: bold; border: 1px solid #fbcfe8;">Status</th>
        </tr>
        @forelse($creditNotes as $note)
        <tr>
            <td style="border: 1px solid #e2e8f0;">{{ $note->credit_note_number }}</td>
            <td style="border: 1px solid #e2e8f0;">{{ $note->agent->agent_name ?? 'Unknown' }}</td>
            <td style="border: 1px solid #e2e8f0;">{{ \Carbon\Carbon::parse($note->credit_note_date)->format('Y-m-d') }}</td>
            <td style="border: 1px solid #e2e8f0;">{{ $note->note_type == 1 ? 'Physical Return' : 'Customer Return' }}</td>
            <td style="border: 1px solid #e2e8f0;">{{ $note->total_amount }}</td>
            <td style="border: 1px solid #e2e8f0;">
                @php
                    $statusText = match((int)$note->status) {
                        0 => 'Pending',
                        1 => 'Approved',
                        2 => 'Rejected',
                        3 => 'Used',
                        default => 'Unknown'
                    };
                @endphp
                {{ $statusText }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align: center; font-style: italic; border: 1px solid #e2e8f0;">No credit notes found.</td>
        </tr>
        @endforelse
    </table>

</body>
</html>
