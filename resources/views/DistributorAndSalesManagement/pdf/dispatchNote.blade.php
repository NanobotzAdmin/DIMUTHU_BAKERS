<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dispatch Note - {{ $order->order_number }}</title>
    <style>
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

        .header-table {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #7e22ce;
            padding-bottom: 15px;
        }

        .header-left {
            text-align: left;
            vertical-align: top;
        }

        .header-right {
            text-align: right;
            vertical-align: bottom;
        }

        .logo {
            max-height: 70px;
            margin-bottom: 5px;
        }

        .company-name {
            font-weight: bold;
            font-size: 20px;
            color: #111;
            margin-bottom: 2px;
        }

        .text-purple {
            color: #7e22ce;
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
            color: #7e22ce;
            font-size: 18px;
        }

        .signatures-table {
            width: 100%;
            margin-top: 50px;
        }

        .signature-box {
            width: 30%;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 12px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
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
        .badge-purple { background-color: #f3e8ff; color: #7e22ce; border: 1px solid #d8b4fe; }
    </style>
</head>

<body>
    <div class="container">
        {{-- Header --}}
        <table class="header-table">
            <tr>
                <td class="header-left">
                    @if(isset($settings['logo_absolute_path']) && $settings['logo_absolute_path'])
                        <img src="{{ $settings['logo_absolute_path'] }}" class="logo" alt="Logo">
                    @elseif(isset($settings['logo_url']) && $settings['logo_url'])
                        <img src="{{ $settings['logo_url'] }}" class="logo" alt="Logo">
                    @endif
                    <div class="company-name">{{ $settings['company_name'] ?? 'Dimuthu Bakers' }}</div>
                    <div style="color: #4b5563; font-size: 12px;">{{ $settings['address'] ?? 'No. 123, Main Street, Colombo' }}</div>
                    <div style="color: #4b5563; font-size: 12px;">Tel: {{ $settings['phone'] ?? '+94 11 234 5678' }} | Email: {{ $settings['email'] ?? 'info@dimuthubakers.lk' }}</div>
                </td>
                <td class="header-right">
                    <div class="title">DISPATCH NOTE</div>
                    <div class="subtitle text-purple">#{{ $order->order_number }}</div>
                    <div style="margin-top: 10px;">
                        <span class="badge badge-purple">
                            {{ $order->order_type == 1 ? 'POS Pickup' : ($order->order_type == 2 ? 'Special Order' : ($order->order_type == 3 ? 'Scheduled' : 'Agent Order')) }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Info Section --}}
        <div style="width: 100%;">
            <div class="section-box">
                <div class="section-title">Customer / Recipient</div>
                @if($order->agent)
                    <div style="font-size: 15px; font-weight: 600;">{{ $order->agent->agent_name }}</div>
                    <div>Agent Code: {{ $order->agent->agent_code }}</div>
                    <div>Phone: {{ $order->agent->phone }}</div>
                    <div>Address: {{ $order->agent->address }}</div>
                @elseif($order->customer)
                    <div style="font-size: 15px; font-weight: 600;">{{ $order->customer->name }}</div>
                    <div>Phone: {{ $order->customer->phone }}</div>
                    <div>Address: {{ $order->customer->address }}</div>
                @else
                    <div style="font-size: 15px; font-weight: 600;">Walk-in Customer</div>
                @endif
            </div>

            <div class="section-box" style="float: right; text-align: right;">
                <div class="section-title">Dispatch Details</div>
                <div><strong>Dispatch Date:</strong> {{ $order->stockTransfers->first() && $order->stockTransfers->first()->dispatched_date ? \Carbon\Carbon::parse($order->stockTransfers->first()->dispatched_date)->tz('Asia/Colombo')->format('M j, Y, g:i A') : \Carbon\Carbon::now()->tz('Asia/Colombo')->format('M j, Y, g:i A') }}</div>
                <div><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->tz('Asia/Colombo')->format('M j, Y, g:i A') }}</div>
                @if($order->payment_completed == 2)
                    <div style="color: green; font-weight: bold; margin-top: 5px;">PAID</div>
                @elseif($order->payment_completed == 3)
                    <div style="color: blue; font-weight: bold; margin-top: 5px;">CREDIT</div>
                @else
                    <div style="color: red; font-weight: bold; margin-top: 5px;">UNPAID</div>
                @endif
            </div>
        </div>

        <div style="clear: both;"></div>

        {{-- Items Table --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Item Description</th>
                    <th class="text-right">Ordered Qty</th>
                    <th class="text-right">Dispatched Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderProducts as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ $item->productItem->product_name ?? 'Product' }}</div>
                            <div style="font-size: 11px; color: #6b7280;">{{ $item->productItem->reference_number ?? '' }}</div>
                        </td>
                        <td>{{ number_format($item->quantity, 2) }}</td>
                        <td style="font-weight: bold;">{{ number_format($item->dispatched_quantity ?? $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($order->notes)
            <div style="margin-top: -10px; margin-bottom: 20px;">
                <div class="section-title">Notes / Instructions</div>
                <div style="font-style: italic; color: #4b5563;">{{ $order->notes }}</div>
            </div>
        @endif

        {{-- Signatures --}}
        <table class="signatures-table">
            <tr>
                <td class="signature-box" style="width: 30%;">
                    Prepared By<br>
                    <span style="font-size: 10px; color: #6b7280;">(Authorized Personnel)</span>
                </td>
                <td style="width: 5%;"></td>
                <td class="signature-box" style="width: 30%;">
                    Dispatched By<br>
                    <span style="font-size: 10px; color: #6b7280;">(Driver/Delivery Staff)</span>
                </td>
                <td style="width: 5%;"></td>
                <td class="signature-box" style="width: 30%;">
                    Received By<br>
                    <span style="font-size: 10px; color: #6b7280;">(Customer/Agent Signature)</span>
                </td>
            </tr>
        </table>

        {{-- Footer --}}
        <div class="footer">
            {{ $settings['footer_text'] ?? 'Thank you for your business! This is a system-generated dispatch note.' }}
            <br>
            Printed at: {{ now()->format('Y-m-d') }}
        </div>
    </div>
</body>

</html>
