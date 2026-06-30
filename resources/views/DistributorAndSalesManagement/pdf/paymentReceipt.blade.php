<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Payment Receipt - REC-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 20px 40px 130px 40px;
        }

        body {
            font-family: sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .footer-wrapper {
            position: fixed;
            bottom: -115px;
            left: 0;
            right: 0;
            height: 115px;
            width: 100%;
        }

        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #10b981;
            padding-bottom: 15px;
        }

        .header-left {
            text-align: left;
            vertical-align: top;
        }

        .header-right {
            text-align: right;
            vertical-align: top;
        }

        .logo {
            max-height: 70px;
            vertical-align: top;
        }

        .company-name {
            font-weight: bold;
            font-size: 20px;
            color: #059669;
            margin-bottom: 2px;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            color: #111;
            margin: 0;
            line-height: 1;
        }

        .subtitle {
            font-size: 16px;
            font-weight: 600;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .section-box {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            text-align: left;
            background-color: #f9fafb;
            color: #4b5563;
            font-weight: 600;
            padding: 10px;
            border-top: 1px solid #e5e7eb;
            border-bottom: 2px solid #e5e7eb;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }

        .text-right {
            text-align: right;
        }

        .total-row td {
            border-top: 2px solid #e5e7eb;
            font-weight: bold;
            font-size: 15px;
            color: #111;
            padding-top: 15px;
        }

        .total-amount {
            color: #059669;
            font-size: 20px;
        }

        .signatures-table {
            width: 100%;
            margin-top: 10px;
        }

        .signature-box {
            width: 30%;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 12px;
        }

        .footer {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>

<body>
    <div class="container">
        {{-- Header --}}
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 72px; vertical-align: top; padding-right: 6px;">
                                @if(isset($settings['logo_absolute_path']) && $settings['logo_absolute_path'])
                                    <img src="{{ $settings['logo_absolute_path'] }}" class="logo" alt="Logo" style="max-height: 70px; max-width: 70px;">
                                @elseif(isset($settings['logo_url']) && $settings['logo_url'])
                                    <img src="{{ $settings['logo_url'] }}" class="logo" alt="Logo" style="max-height: 70px; max-width: 70px;">
                                @endif
                            </td>
                            <td style="vertical-align: top;">
                                <div class="company-name" style="font-family: 'Cinzel', 'Playfair Display', Georgia, serif; font-size: 18px; color: #956b41ff; letter-spacing: 0.04em;">{{ $settings['company_name'] ?? 'Dimuthu Bakers' }}</div>
                                <div style="color: #4b5563; font-size: 11px; margin-top: 3px; font-weight: 500;">{{ $settings['address'] ?? 'No. 123, Main Street, Colombo' }}</div>
                                <div style="color: #6b7280; font-size: 11px; margin-top: 2px;">Tel: {{ $settings['phone'] ?? '+94 11 234 5678' }}@if(!empty($settings['mobile'])) | Mob: {{ $settings['mobile'] }}@endif</div>
                                <div style="color: #6b7280; font-size: 11px; margin-top: 1px;">Email: {{ $settings['email'] ?? 'info@dimuthubakers.lk' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="header-right">
                    <div class="title" style="letter-spacing: 1px; color: #10b981;">RECEIPT</div>
                    <div class="subtitle" style="font-size: 14px; font-weight: bold; margin-top: 5px; color: #047857;">#REC-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div style="margin-top: 10px;">
                        <span class="badge {{ $payment->status == 1 ? 'badge-success' : ($payment->status == 2 ? 'badge-danger' : 'badge-warning') }}" style="font-size: 9px; padding: 3px 10px; border-radius: 9999px;">
                            {{ $payment->status == 1 ? 'Approved' : ($payment->status == 2 ? 'Rejected' : 'Pending Approval') }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Info Section --}}
        <div style="width: 100%;">
            <div class="section-box">
                <div class="section-title">Submitted By (Agent)</div>
                @if($payment->agent)
                    <div style="font-size: 15px; font-weight: 600;">{{ $payment->agent->agent_name }}</div>
                    <div>Agent Code: {{ $payment->agent->agent_code }}</div>
                    <div>Phone: {{ $payment->agent->phone }}</div>
                    <div>Address: {{ $payment->agent->address }}</div>
                @else
                    <div style="font-size: 15px; font-weight: 600;">Unknown Agent</div>
                @endif
            </div>

            <div class="section-box" style="float: right; text-align: right;">
                <div class="section-title">Payment Info</div>
                <div><strong>Submission Date:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->tz('Asia/Colombo')->format('M j, Y, g:i A') }}</div>
                <div><strong>Payment Method:</strong> 
                    @php
                        $methods = [1 => 'Cash', 2 => 'Card', 3 => 'Bank Transfer', 4 => 'Credit Note'];
                        echo $methods[$payment->payment_method] ?? 'Other';
                    @endphp
                </div>
                <div><strong>Outstanding Balance:</strong> Rs. {{ number_format($payment->agent->outstanding_balance ?? 0, 2) }}</div>
            </div>
        </div>

        <div style="clear: both;"></div>

        {{-- Allocations Table --}}
        <div class="section-title">Distribution Breakdown (Orders Cleared)</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Order Ref</th>
                    <th>Order Total</th>
                    <th class="text-right">Allocated Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payment->distributions as $index => $dist)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: 600;">#{{ $dist->orderRequest->order_number ?? 'N/A' }}</div>
                            <div style="font-size: 11px; color: #6b7280;">
                                Order Type: {{ $dist->orderRequest->order_type == 4 ? 'Agent Distribution' : 'Other' }}
                            </div>
                        </td>
                        <td>Rs. {{ number_format($dist->orderRequest->grand_total ?? 0, 2) }}</td>
                        <td class="text-right" style="font-weight: bold; color: #047857;">Rs. {{ number_format($dist->payment_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="color: #6b7280;">No order allocations found for this payment.</td>
                    </tr>
                @endforelse
                
                {{-- Applied Credit Notes --}}
                @if($payment->creditNotes->isNotEmpty())
                    <tr>
                        <td colspan="4" style="background-color: #f9fafb; font-weight: bold; padding: 6px 10px; font-size: 11px; color: #4b5563;">APPLIED CREDIT NOTES</td>
                    </tr>
                    @foreach($payment->creditNotes as $cnIndex => $cn)
                        <tr>
                            <td>CN-{{ $cnIndex + 1 }}</td>
                            <td>
                                <div style="font-weight: 600;">#{{ $cn->credit_note_number }}</div>
                                <div style="font-size: 11px; color: #6b7280;">Reason: {{ $cn->reason }}</div>
                            </td>
                            <td>Rs. {{ number_format($cn->total_amount, 2) }}</td>
                            <td class="text-right" style="font-weight: bold; color: #b45309;">-Rs. {{ number_format($cn->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                @endif

                <tr class="total-row">
                    <td colspan="2"></td>
                    <td class="text-right">Total Amount:</td>
                    <td class="text-right total-amount">Rs. {{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        @if($payment->notes)
            <div style="margin-top: 15px; margin-bottom: 20px;">
                <div class="section-title">Notes / Remarks</div>
                <div style="font-style: italic; color: #4b5563; line-height: 1.2;">{{ $payment->notes }}</div>
            </div>
        @endif

        <div class="footer-wrapper">
            {{-- Signatures --}}
            <table class="signatures-table">
                <tr>
                    <td class="signature-box" style="width: 30%;">
                        Agent Signature
                    </td>
                    <td style="width: 5%;"></td>
                    <td class="signature-box" style="width: 30%;">
                        Prepared By
                    </td>
                    <td style="width: 5%;"></td>
                    <td class="signature-box" style="width: 30%;">
                        Authorized Manager
                    </td>
                </tr>
            </table>

            {{-- Footer --}}
            <div class="footer">
                {{ $settings['footer_text'] ?? 'Thank you! This is a system-generated Payment Receipt document.' }}
                <br>
                Printed at: {{ now()->format('Y-m-d H:i') }}
            </div>
        </div>
    </div>
</body>

</html>
