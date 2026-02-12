<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Quotation</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
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
            /* purple-700 */
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
            max-height: 60px;
            margin-bottom: 5px;
        }

        .company-name {
            font-weight: bold;
            font-size: 18px;
            color: #111;
        }

        .text-purple {
            color: #7e22ce;
        }

        .title {
            font-size: 30px;
            font-weight: bold;
            color: #111;
            margin: 0;
            line-height: 1;
        }

        .subtitle {
            font-size: 16px;
            font-weight: 500;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-label {
            color: #6b7280;
            /* gray-500 */
            padding-right: 10px;
        }

        .info-value {
            font-weight: 500;
            color: #111;
        }

        .bill-to {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
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
            border-bottom: 1px solid #e5e7eb;
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
            border-top: 1px solid #e5e7eb;
            font-weight: bold;
            font-size: 16px;
            color: #111;
        }

        .total-amount {
            color: #7e22ce;
        }

        .terms-container {
            width: 100%;
            margin-bottom: 20px;
            background-color: #eff6ff;
            /* blue-50 */
            border-radius: 8px;
            padding: 15px;
        }

        .term-box {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        .term-title {
            font-size: 12px;
            font-weight: bold;
            color: #374151;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .term-text {
            font-size: 12px;
            color: #4b5563;
            white-space: pre-line;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
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
                    <div class="company-name">{{ $settings['company_name'] ?? 'BakeryMate ERP' }}</div>
                    <div style="color: #6b7280; font-size: 12px;">{{ $settings['address'] ?? 'www.bakerymate.lk' }}
                    </div>
                    <div style="color: #6b7280; font-size: 12px;">{{ $settings['phone'] ?? '+94 11 234 5678' }}</div>
                    <div style="color: #6b7280; font-size: 12px;">{{ $settings['email'] ?? '' }}</div>
                </td>
                <td class="header-right">
                    <div class="title">QUOTATION</div>
                    <div class="subtitle text-purple">{{ $quotation->quotation_number }}</div>
                    <br>
                    <table align="right" style="width: auto;">
                        <tr>
                            <td class="info-label text-right">Date:</td>
                            <td class="info-value">{{ $quotation->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="info-label text-right">Valid Until:</td>
                            <td class="info-value">
                                {{ \Carbon\Carbon::parse($quotation->valid_until)->format('M d, Y') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- Bill To --}}
        <div class="bill-to">
            <div class="section-title">Bill To:</div>
            <div style="font-size: 16px; font-weight: 500;">{{ $quotation->customer->name ?? 'Unknown' }}</div>
            <div style="color: #4b5563;">{{ $quotation->customer->email ?? '' }}</div>
            <div style="color: #4b5563;">{{ $quotation->customer->phone ?? '' }}</div>
        </div>

        {{-- Items Table --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->products as $item)
                    <tr>
                        <td>{{ $item->productItem->product_name ?? 'Item' }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" class="text-right">Total</td>
                    <td class="text-right total-amount">{{ number_format($quotation->grand_total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Terms --}}
        <div class="terms-container">
            <div style="margin-bottom: 20px;">
                <div class="term-title">Payment Terms</div>
                <div class="term-text">
                    {{ $settings['default_payment_terms'] ?? '' }}
                </div>
            </div>
            <div>
                <div class="term-title">Terms & Conditions</div>
                <div class="term-text">
                    {{ $settings['default_terms_conditions'] ?? ($quotation->notes ?? '') }}
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            {{ $settings['footer_text'] ?? 'Thank you for your business!' }}
        </div>
    </div>
</body>

</html>