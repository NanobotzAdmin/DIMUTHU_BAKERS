<!DOCTYPE html>
<html>

<head>
    <title>Receipt #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            max-width: 300px;
            margin: 0 auto;
            padding: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .border-b {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 2px 0;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="text-center">
        <h2 style="margin:0;">NANO BAKERS</h2>
        <p style="margin:0;">123 Main Street, Colombo</p>
        <p style="margin:0;">Tel: 011-2345678</p>
    </div>
    <div class="border-b"></div>
    <div>
        Invoice: {{ $invoice->invoice_number }}<br>
        Date: {{ $invoice->created_at->format('Y-m-d H:i') }}<br>
        Cashier: {{ $invoice->creator ? $invoice->creator->name : 'N/A' }}<br>
        @if($invoice->customer)
            Customer: {{ $invoice->customer->name }}
        @endif
    </div>
    <div class="border-b"></div>
    <table>
        <thead>
            <tr class="text-left">
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->productItem->product_name ?? 'Item' }}</td>
                    <td class="text-right">{{ number_format($item->qty, 0) }}</td>
                    <td class="text-right">{{ number_format($item->invoiced_price * $item->qty, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="border-b"></div>
    <table style="font-weight: bold;">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">{{ number_format($invoice->total_price, 2) }}</td>
        </tr>
        @if($invoice->discount_value > 0)
            <tr>
                <td>Discount:</td>
                <td class="text-right">-{{ number_format($invoice->discount_value, 2) }}</td>
            </tr>
        @endif
        @if($invoice->tax_amount > 0)
            <tr>
                <td>Tax:</td>
                <td class="text-right">{{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
        @endif
        <tr style="font-size: 14px;">
            <td>TOTAL:</td>
            <td class="text-right">{{ number_format($invoice->payble_amount, 2) }}</td>
        </tr>
    </table>
    <div class="border-b"></div>
    <div>
        @foreach($invoice->payments as $pm)
            <div style="display:flex; justify-content:space-between;">
                <span style="text-transform: capitalize;">{{ 
                        $pm->payment_type == 1 ? 'Cash' :
            ($pm->payment_type == 2 ? 'Card' :
                ($pm->payment_type == 3 ? 'Bank' : 'Other')) 
                    }}</span>
                <span>{{ number_format($pm->paid_amount, 2) }}</span>
            </div>
        @endforeach
    </div>
    <div class="border-b"></div>
    <div class="text-center" style="margin-top: 10px;">
        <p>Thank you for your visit!</p>
        <p style="font-size: 10px;">Powered by BakeryMate</p>
    </div>

    <button class="no-print" onclick="window.print()" style="margin-top:20px; width:100%; padding: 10px;">Print
        Receipt</button>
</body>

</html>