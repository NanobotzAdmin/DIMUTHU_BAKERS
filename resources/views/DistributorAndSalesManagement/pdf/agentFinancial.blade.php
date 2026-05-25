<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Financial Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #334155;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #0f172a;
            font-size: 24px;
            margin: 0 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            color: #64748b;
            margin: 0;
            font-size: 14px;
        }
        .summary-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 30px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 8px;
            vertical-align: top;
        }
        .summary-label {
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            font-size: 10px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
        }
        
        .section-title {
            color: #1e293b;
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #475569;
            text-align: left;
            padding: 10px;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #cbd5e1;
        }
        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fef3c7; color: #b45309; }
        .status-approved { background-color: #d1fae5; color: #047857; }
        .status-rejected { background-color: #ffe4e6; color: #be123c; }
        
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Dimuthu Bakers</h1>
        <p>Agent Financial Management Report</p>
        <p style="font-size: 11px; margin-top: 5px;">Generated on: {{ now()->format('M d, Y h:i A') }}</p>
    </div>

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>
                    <div class="summary-label">Total Payments</div>
                    <div class="summary-value" style="color: #059669;">Rs. {{ number_format($summary['totalPayments'], 2) }}</div>
                </td>
                <td>
                    <div class="summary-label">Pending Payments</div>
                    <div class="summary-value" style="color: #d97706;">{{ $summary['pendingPayments'] }}</div>
                </td>
                <td>
                    <div class="summary-label">Total Credit Notes</div>
                    <div class="summary-value" style="color: #2563eb;">Rs. {{ number_format($summary['totalCreditNotes'], 2) }}</div>
                </td>
                <td>
                    <div class="summary-label">Pending Credit Notes</div>
                    <div class="summary-value" style="color: #e11d48;">{{ $summary['pendingCreditNotes'] }}</div>
                </td>
                <td>
                    <div class="summary-label">Outstanding Balance</div>
                    <div class="summary-value" style="color: #4f46e5;">Rs. {{ number_format($summary['totalAgentOutstanding'], 2) }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Agent Payments Section -->
    <div class="section-title">Agent Payments</div>
    @if($payments->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Agent</th>
                <th>Method</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y h:i A') : $payment->created_at->format('M d, Y') }}</td>
                <td class="font-bold">{{ $payment->agent->agent_name ?? 'N/A' }}</td>
                <td>
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
                <td class="text-right font-bold" style="color: #4338ca;">Rs. {{ number_format($payment->amount, 2) }}</td>
                <td class="text-right">
                    @php
                        $statusClass = match((int)$payment->status) {
                            0 => 'status-pending',
                            1 => 'status-approved',
                            2 => 'status-rejected',
                            default => ''
                        };
                        $statusText = match((int)$payment->status) {
                            0 => 'Pending',
                            1 => 'Approved',
                            2 => 'Rejected',
                            default => 'Unknown'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p style="text-align: center; color: #94a3b8; padding: 20px;">No payment records found.</p>
    @endif

    <!-- Credit Notes Section -->
    <div style="page-break-before: always;"></div>
    
    <div class="section-title">Credit Notes</div>
    @if($creditNotes->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th>CN Number</th>
                <th>Agent</th>
                <th>Date</th>
                <th>Type</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($creditNotes as $note)
            <tr>
                <td class="font-bold">{{ $note->credit_note_number }}</td>
                <td>{{ $note->agent->agent_name ?? 'Unknown' }}</td>
                <td>{{ \Carbon\Carbon::parse($note->credit_note_date)->format('M d, Y') }}</td>
                <td>{{ $note->note_type == 1 ? 'Physical Return' : 'Customer Return' }}</td>
                <td class="text-right font-bold" style="color: #4338ca;">Rs. {{ number_format($note->total_amount, 2) }}</td>
                <td class="text-right">
                    @php
                        $statusClass = match((int)$note->status) {
                            0 => 'status-pending',
                            1 => 'status-approved',
                            2 => 'status-rejected',
                            3 => 'status-approved', // Used
                            default => ''
                        };
                        $statusText = match((int)$note->status) {
                            0 => 'Pending',
                            1 => 'Approved',
                            2 => 'Rejected',
                            3 => 'Used',
                            default => 'Unknown'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p style="text-align: center; color: #94a3b8; padding: 20px;">No credit note records found.</p>
    @endif

    <div class="footer">
        &copy; {{ date('Y') }} Dimuthu Bakers. All Rights Reserved.<br>
        Confidential Financial Report
    </div>

</body>
</html>
