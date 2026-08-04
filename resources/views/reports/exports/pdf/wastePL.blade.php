<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Waste Profit & Loss Statement</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #111827; margin: 0; padding: 0; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; border: none; }
        .header-table td { border: none; padding: 0; vertical-align: top; }
        .company-name { 
            color: {{ $companyInfo['colors']['primary'] ?? '#ff8040' }}; 
            margin: 0 0 4px 0; 
            font-size: 22px;
            font-weight: bold;
        }
        .company-details { margin: 0; color: #4b5563; font-size: 11px; line-height: 1.4; }
        .report-title { 
            color: {{ $companyInfo['colors']['secondary'] ?? '#0d108e' }}; 
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        
        .section-title {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
            margin-top: 15px;
        }
        
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .data-table td { padding: 8px 12px; font-size: 11px; border-bottom: 1px solid #f3f4f6; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }
        
        .total-box {
            padding: 10px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .net-loss-box {
            background-color: #111827;
            color: #ffffff;
            padding: 16px 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            width: 48%;
            float: left;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <!-- Top Bakery Header -->
    <table class="header-table">
        <tr>
            <td style="width: 60%; text-align: left;">
                <table style="border: none; margin: 0; padding: 0; width: auto;">
                    <tr>
                        <td style="border: none; padding: 0; padding-right: 15px; vertical-align: middle;">
                            @if(isset($companyInfo['logos']['primary']))
                                <img src="{{ public_path($companyInfo['logos']['primary']) }}" style="max-height: 60px;" alt="Company Logo">
                            @endif
                        </td>
                        <td style="border: none; padding: 0; vertical-align: middle; text-align: left;">
                            <div class="company-name">{{ $companyInfo['business_name'] ?? 'Dimuthu Bakers' }}</div>
                            <div class="company-details">
                                {{ $companyInfo['address']['street'] ?? '' }}, {{ $companyInfo['address']['city'] ?? '' }}<br>
                                Tel: {{ $companyInfo['contact']['phone'] ?? '' }} {{ isset($companyInfo['contact']['mobile']) ? ' / ' . $companyInfo['contact']['mobile'] : '' }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%; text-align: right; vertical-align: bottom;">
                <div class="report-title">Waste Profit & Loss</div>
                <div style="font-size: 11px; color: #4b5563; margin-top: 4px;">
                    <strong>Period:</strong> December 2025<br>
                    <strong>Currency:</strong> LKR (Rs.)<br>
                    <strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Section I: Production Cost -->
    <div class="section-title" style="color: #6b7280;">I. PRODUCTION COST (Waste Items)</div>
    <table class="data-table">
        <tr>
            <td>Beginning Inventory (Waste Pool)</td>
            <td class="text-right font-mono font-bold">Rs. {{ number_format($wastePL['beginningInventory'], 2) }}</td>
        </tr>
        <tr>
            <td>+ Production Costs of Items Entering Waste Stream</td>
            <td class="text-right font-mono font-bold">Rs. {{ number_format($wastePL['productionCosts'], 2) }}</td>
        </tr>
        <tr>
            <td style="color: #dc2626;">- Ending Inventory (Unprocessed Waste)</td>
            <td class="text-right font-mono font-bold" style="color: #dc2626;">(Rs. {{ number_format($wastePL['endingInventory'], 2) }})</td>
        </tr>
    </table>
    <div class="total-box" style="background-color: #f9fafb; border-right: 4px solid #1f2937;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 0; font-weight: bold;">TOTAL COST OF WASTE ITEMS</td>
                <td style="border: none; padding: 0; text-align: right;" class="font-mono font-bold">Rs. {{ number_format($wastePL['costOfWasteItems'], 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Section II: Revenue from Waste Recovery -->
    <div class="section-title" style="color: #16a34a;">II. REVENUE FROM WASTE RECOVERY</div>
    <table class="data-table">
        <tr>
            <td>Day-Old Product Sales (Discounted Revenue)</td>
            <td class="text-right font-mono font-bold" style="color: #15803d;">Rs. {{ number_format($wastePL['dayOldSales'], 2) }}</td>
        </tr>
        <tr>
            <td>Direct Waste Recovery Income (Animal Feed/Bio-Gas)</td>
            <td class="text-right font-mono font-bold" style="color: #15803d;">Rs. {{ number_format($wastePL['wasteRecoveryIncome'], 2) }}</td>
        </tr>
    </table>
    <div class="total-box" style="background-color: #f0fdf4; border-right: 4px solid #16a34a; color: #166534;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 0; font-weight: bold;">TOTAL WASTE REVENUE</td>
                <td style="border: none; padding: 0; text-align: right;" class="font-mono font-bold">Rs. {{ number_format($wastePL['totalRevenue'], 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Section III: Waste-Related Expenses -->
    <div class="section-title" style="color: #dc2626;">III. WASTE-RELATED EXPENSES</div>
    <table class="data-table">
        <tr>
            <td>NRV Write-downs (Stage 1 to 2 Valuation Loss)</td>
            <td class="text-right font-mono font-bold" style="color: #b91c1c;">Rs. {{ number_format($wastePL['nrvWritedowns'], 2) }}</td>
        </tr>
        <tr>
            <td>Actual Waste Inventory Loss (Stage 3 Items)</td>
            <td class="text-right font-mono font-bold" style="color: #b91c1c;">Rs. {{ number_format($wastePL['wasteLoss'], 2) }}</td>
        </tr>
        <tr>
            <td>Recovery Processing Costs (Labor/Energy)</td>
            <td class="text-right font-mono font-bold" style="color: #b91c1c;">Rs. {{ number_format($wastePL['processingCosts'], 2) }}</td>
        </tr>
        <tr>
            <td>Third-Party Disposal & Landfill Fees</td>
            <td class="text-right font-mono font-bold" style="color: #b91c1c;">Rs. {{ number_format($wastePL['disposalCosts'], 2) }}</td>
        </tr>
    </table>
    <div class="total-box" style="background-color: #fef2f2; border-right: 4px solid #dc2626; color: #991b1b;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 0; font-weight: bold;">TOTAL WASTE EXPENSES</td>
                <td style="border: none; padding: 0; text-align: right;" class="font-mono font-bold">Rs. {{ number_format($wastePL['totalExpenses'], 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Net Waste Loss -->
    <div class="net-loss-box">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; padding: 0; vertical-align: middle;">
                    <div style="font-size: 9px; color: #9ca3af; text-transform: uppercase; letter-spacing: 2px;">Final Result</div>
                    <div style="font-size: 20px; font-weight: bold; font-style: italic; margin-top: 2px;">NET WASTE LOSS</div>
                </td>
                <td style="border: none; padding: 0; text-align: right; vertical-align: middle;" class="font-mono">
                    <span style="font-size: 24px; font-weight: bold; color: #ef4444;">
                        (Rs. {{ number_format($wastePL['netWasteLoss'], 2) }})
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 15px;">
        <div class="summary-card" style="background-color: #eff6ff; border-color: #dbeafe;">
            <div style="font-size: 9px; font-weight: bold; color: #3b82f6; text-transform: uppercase;">Recovery Rate</div>
            <div style="font-size: 16px; font-weight: bold; color: #1e3a8a; margin-top: 4px;">{{ $wastePL['recoveryRate'] }}%</div>
            <div style="font-size: 10px; color: #1d4ed8; margin-top: 4px;">Percentage of total waste-related costs recovered through sales and processing.</div>
        </div>

        <div class="summary-card" style="background-color: #fff7ed; border-color: #ffedd5; float: right;">
            <div style="font-size: 9px; font-weight: bold; color: #f97316; text-transform: uppercase;">Financial Efficiency</div>
            <div style="font-size: 16px; font-weight: bold; color: #7c2d12; margin-top: 4px;">Low Impact</div>
            <div style="font-size: 10px; color: #c2410c; margin-top: 4px;">Waste loss represents approx. 4.2% of total production value for this period.</div>
        </div>
    </div>
</body>
</html>
