<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SKU Target vs Actual - {{ $data['agent']['name'] }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.25;
        }

        .header-bar {
            background-color: #7c3f0f;
            color: #ffffff;
            padding: 8px 12px;
            margin-bottom: 6px;
        }
        .header-table { width: 100%; border-collapse: collapse; border: none; }
        .header-table td { border: none; padding: 0; vertical-align: middle; }
        .company-subtitle {
            color: #fed7aa;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .report-title {
            color: #ffffff;
            font-size: 15px;
            font-weight: bold;
            margin: 0;
        }

        .sub-header {
            background-color: #fff8ed;
            border: 1px solid #fed7aa;
            padding: 6px 10px;
            margin-bottom: 6px;
            font-size: 8px;
        }
        .sub-table { width: 100%; border-collapse: collapse; border: none; }
        .sub-table td { border: none; padding: 0; vertical-align: middle; }
        .sub-label { font-size: 7px; color: #9a3412; font-weight: bold; text-transform: uppercase; margin-bottom: 1px; letter-spacing: 0.5px; }
        .sub-value { font-size: 8.5px; font-weight: bold; color: #1e293b; }

        .table-card {
            border: 1px solid #fed7aa;
            border-radius: 4px;
            overflow: hidden;
            background: #ffffff;
            margin-bottom: 6px;
        }

        table.d {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        table.d th {
            background: #fff8ed;
            color: #9a3412;
            text-align: left;
            padding: 5px 8px;
            border-bottom: 1.5px solid #fed7aa;
            border-right: 1px solid #ffedd5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5px;
        }
        table.d th.n, table.d td.n {
            text-align: right !important;
        }
        table.d td {
            padding: 4px 8px;
            border-bottom: 1px solid #f8fafc;
            border-right: 1px solid #f8fafc;
            color: #1e293b;
        }
        table.d tr.cat td {
            background: #f3f4f6;
            font-weight: bold;
            color: #7c3f0f;
            padding: 5px 8px;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8.5px;
        }
        table.d tr.sub td {
            font-weight: bold;
            background: #fafafa;
            border-top: 1px solid #fed7aa;
            border-bottom: 1px solid #fed7aa;
            font-size: 8px;
        }
        table.d tr.tot td {
            font-weight: bold;
            background: #1f2937;
            color: #ffffff !important;
            padding: 6px 8px;
            font-size: 8.5px;
        }
        table.d tr.tot td[style] {
            color: #ffffff !important;
        }

        .note {
            font-size: 7.5px;
            color: #64748b;
            padding: 4px 2px;
            line-height: 1.3;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Top Bakery Header Banner -->
    <div class="header-bar">
        <table class="header-table">
            <tr>
                <td style="width: 65%; text-align: left;">
                    <div class="company-subtitle">{{ $data['header_info']['company_name'] }}</div>
                    <div class="report-title">SKU Target vs Actual</div>
                </td>
                <td style="width: 35%; text-align: right;">
                    <div style="font-size: 10px; font-weight: bold; color: white;">{{ $data['header_info']['date_range_label'] }}</div>
                    <div style="font-size: 7px; color: #fed7aa; margin-top: 2px;">Generated: {{ $data['header_info']['generated_at'] }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Agent & Period Subheader Strip -->
    <div class="sub-header">
        <table class="sub-table">
            <tr>
                <td style="width: 40%;">
                    <div class="sub-label">AGENT</div>
                    <div class="sub-value">{{ $data['header_info']['agent_label'] }}</div>
                </td>
                <td style="width: 30%;">
                    <div class="sub-label">FROM</div>
                    <div class="sub-value">{{ $data['header_info']['from_date'] }}</div>
                </td>
                <td style="width: 30%; text-align: right;">
                    <div class="sub-label">TO</div>
                    <div class="sub-value">{{ $data['header_info']['to_date'] }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- SKU Target vs Actual Table -->
    <div class="table-card">
        <table class="d">
            <thead>
                <tr>
                    <th style="width: 44%;">SKU</th>
                    <th class="n" style="width: 14%;">Target</th>
                    <th class="n" style="width: 14%;">Actual</th>
                    <th class="n" style="width: 14%;">Achievement</th>
                    <th class="n" style="width: 14%;">Gap</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['categories'] as $cat)
                    <tr class="cat">
                        <td colspan="5">{{ $cat['category_name'] }}</td>
                    </tr>
                    @foreach($cat['items'] as $it)
                        @php
                            $hasTarget = !empty($it['has_target']);
                            if ($hasTarget) {
                                $achColor = $it['is_achieved'] ? '#16a34a' : '#dc2626';
                                $gapColor = $it['is_achieved'] ? '#16a34a' : '#dc2626';
                                $gapPrefix = $it['gap'] > 0 ? '+' : ($it['gap'] < 0 ? '-' : '');
                                $gapDisplay = $gapPrefix . number_format(abs($it['gap']));
                            }
                        @endphp
                        <tr class="">
                            <td>{{ $it['name'] }}</td>
                            <td class="n">
                                @if($hasTarget)
                                    {{ number_format($it['target']) }}
                                @else
                                    <span style="color:#94a3b8; font-weight:bold;">-</span>
                                    <span style="display:inline-block; font-size:7px; font-weight:bold; background:#fef3c7; color:#b45309; border:0.5px solid #fde68a; border-radius:50%; width:10px; height:10px; text-align:center; line-height:9px; vertical-align:middle; margin-left:2px;">!</span>
                                @endif
                            </td>
                            <td class="n">{{ number_format($it['actual']) }}</td>
                            <td class="n" style="{{ $hasTarget ? 'color:'.$achColor.';font-weight:bold;' : 'color:#94a3b8;font-weight:bold;' }}">
                                {{ $hasTarget ? $it['achievement'].'%' : '-' }}
                            </td>
                            <td class="n" style="{{ $hasTarget ? 'color:'.$gapColor.';' : 'color:#94a3b8;font-weight:bold;' }}">
                                {{ $hasTarget ? $gapDisplay : '-' }}
                            </td>
                        </tr>
                    @endforeach
                    @php
                        $subHasTarget = !empty($cat['has_target']);
                        if ($subHasTarget) {
                            $subAchColor = $cat['is_achieved'] ? '#16a34a' : '#dc2626';
                            $subGapColor = $cat['is_achieved'] ? '#16a34a' : '#dc2626';
                            $subGapPrefix = $cat['subtotal_gap'] > 0 ? '+' : ($cat['subtotal_gap'] < 0 ? '-' : '');
                            $subGapDisplay = $subGapPrefix . number_format(abs($cat['subtotal_gap']));
                        }
                    @endphp
                    <tr class="sub">
                        <td>Total for {{ $cat['category_name'] }}</td>
                        <td class="n">{{ $subHasTarget ? number_format($cat['subtotal_target']) : '-' }}</td>
                        <td class="n">{{ number_format($cat['subtotal_actual']) }}</td>
                        <td class="n" style="{{ $subHasTarget ? 'color:'.$subAchColor.';font-weight:bold;' : 'color:#94a3b8;font-weight:bold;' }}">
                            {{ $subHasTarget ? $cat['subtotal_achievement'].'%' : '-' }}
                        </td>
                        <td class="n" style="{{ $subHasTarget ? 'color:'.$subGapColor.';' : 'color:#94a3b8;font-weight:bold;' }}">
                            {{ $subHasTarget ? $subGapDisplay : '-' }}
                        </td>
                    </tr>
                @endforeach
                @php
                    $totHasTarget = ($data['totals']['target'] > 0);
                    if ($totHasTarget) {
                        $totGapPrefix = $data['totals']['gap'] > 0 ? '+' : ($data['totals']['gap'] < 0 ? '-' : '');
                        $totGapDisplay = $totGapPrefix . number_format(abs($data['totals']['gap']));
                    }
                @endphp
                <tr class="tot">
                    <td>TOTAL</td>
                    <td class="n">{{ $totHasTarget ? number_format($data['totals']['target']) : '-' }}</td>
                    <td class="n">{{ number_format($data['totals']['actual']) }}</td>
                    <td class="n" style="color:#ffffff !important;">{{ $totHasTarget ? $data['totals']['achievement'].'%' : '-' }}</td>
                    <td class="n" style="color:#ffffff !important;">{{ $totHasTarget ? $totGapDisplay : '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Note Footnote -->
    <div class="note">
        Units. Target = monthly SKU target prorated to the selected dates. Red = below target (left behind), green = target achieved. (! = Target not added for this item in selected period)
    </div>
</body>
</html>
