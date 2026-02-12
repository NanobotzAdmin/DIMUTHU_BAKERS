<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order {{ $order->po_number }}</title>
    <style>
        /* Base Settings */
        @page { margin: 40px; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.4;
        }

        /* Utility Helpers */
        .w-full { width: 100%; }
        .w-half { width: 50%; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .mb-10 { margin-bottom: 10px; }
        .mb-20 { margin-bottom: 20px; }
        .mt-20 { margin-top: 20px; }
        .text-muted { color: #666; font-size: 11px; }

        /* Color Palette - Professional Slate Blue Theme */
        .brand-color { color: #2c3e50; }
        .bg-gray { background-color: #f3f4f6; }
        .bg-dark { background-color: #2c3e50; color: #fff; }

        /* Header Layout */
        .header-table {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 20px;
            font-weight: 800;
            margin: 0;
            color: #2c3e50;
        }
        .po-title {
            font-size: 28px;
            font-weight: bold;
            color: #d4a373; /* Gold/Wheat accent for Bakery theme */
            text-align: right;
        }

        /* Info Boxes */
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #eee;
            vertical-align: top;
        }
        .box-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 10px;
            font-size: 12px;
            text-transform: uppercase;
            text-align: left;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .items-table tr:nth-child(even) { background-color: #fcfcfc; }
        
        /* Totals Section */
        .totals-table {
            width: 40%;
            float: right;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .total-final {
            font-size: 16px;
            font-weight: bold;
            background-color: #2c3e50;
            color: white;
        }

        /* Status Badge */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
        }
        .status-pending { background-color: #f59e0b; }
        .status-approved { background-color: #10b981; }
        .status-sent { background-color: #3b82f6; }
        .status-received { background-color: #6366f1; }
        .status-partial { background-color: #8b5cf6; }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <!-- <h1 class="company-name">BAKERY MATE</h1> -->
                <img src="{{ public_path('images/logo.png') }}" alt="Logo" width="100">
                <div class="text-muted mt-20">
                    123 Bakery Street<br>
                    Food City, FC 12345<br>
                    +1 234 567 890
                </div>
            </td>
            <td width="50%" style="vertical-align: top; text-align: right;">
                <div class="po-title">PURCHASE ORDER</div>
                <div class="mt-20">
                    <strong>PO Number:</strong> #{{ $order->po_number }}<br>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y') }}<br>
                    
                    @php
                        $statusColors = [
                            0 => ['text' => 'Pending', 'class' => 'status-pending'],
                            1 => ['text' => 'Approved', 'class' => 'status-approved'],
                            2 => ['text' => 'Sent', 'class' => 'status-sent'],
                            3 => ['text' => 'Partial', 'class' => 'status-partial'],
                            4 => ['text' => 'Received', 'class' => 'status-received'],
                        ];
                        $status = $statusColors[$order->status] ?? ['text' => 'Unknown', 'class' => 'bg-gray'];
                    @endphp
                    <br>
                    <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td width="48%" class="info-box">
                <div class="box-title">Vendor</div>
                <span class="text-bold" style="font-size: 14px;">{{ $order->supplier->name ?? 'Unknown Vendor' }}</span><br>
                @if($order->supplier->address)
                    {{ $order->supplier->address }}<br>
                @endif
                <div class="mt-20"></div>
                @if($order->supplier->primaryContact)
                    <span class="text-muted">CONTACT:</span> {{ $order->supplier->primaryContact->name }}<br>
                    <span class="text-muted">EMAIL:</span> {{ $order->supplier->primaryContact->email }}<br>
                    <span class="text-muted">PHONE:</span> {{ $order->supplier->primaryContact->phone }}
                @endif
            </td>
            <td width="4%"></td> <td width="48%" class="info-box">
                <div class="box-title">Ship To</div>
                <span class="text-bold" style="font-size: 14px;">Bakery Mate Warehouse</span><br>
                123 Bakery Street<br>
                Food City, FC 12345<br>
                Sri Lanka<br>
                <div class="mt-20"></div>
                <span class="text-muted">ATTN:</span> Receiving Dept.
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="45%">Item Description</th>
                <th width="15%" class="text-right">Qty</th>
                <th width="15%" class="text-right">Unit Price</th>
                <th width="20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                @php
                    // Logic to build product name string
                    $productName = $item->productItem->product_name 
                        ?? ($item->productItem->product->product_name ?? 'Unknown Item');
                    
                    if (isset($item->productItem->brand->brand_name)) {
                        $productName .= ' - ' . $item->productItem->brand->brand_name;
                    }
                    
                    $variationStr = '';
                    if (isset($item->productItem->variation->variation_name) && isset($item->productItem->variationValue->variation_value)) {
                        $variationStr = $item->productItem->variation->variation_name . ': ' . $item->productItem->variationValue->variation_value;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <span class="text-bold">{{ $productName }}</span>
                        @if($variationStr)
                            <br><span class="text-muted" style="font-size: 10px;">{{ $variationStr }}</span>
                        @endif
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="w-full">
        <tr>
            <td width="60%" style="vertical-align: top;">
                @if($order->notes)
                    <div class="info-box" style="background: white; border: none; padding-left: 0;">
                        <div class="box-title">Notes & Instructions</div>
                        <p style="font-size: 11px; color: #555;">{{ $order->notes }}</p>
                    </div>
                @endif
            </td>
            <td width="40%" style="vertical-align: top;">
                <table class="totals-table">
                    <tr>
                        <td class="text-right">Subtotal:</td>
                        <td class="text-right text-bold">{{ number_format($order->items->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right total-final" style="border-bottom: none;">Total:</td>
                        <td class="text-right total-final" style="border-bottom: none;">{{ number_format($order->items->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">
        Bakery Mate System &copy; {{ date('Y') }} | Thank you for your business.
    </div>
</body>
</html>