@extends('layouts.app')

@section('content')

    <!-- Flatpickr -->
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/flatpickr.min.css') }}">
    <script src="{{ asset('vendor/flatpickr/flatpickr.min.js') }}"></script>

    <style>
        .sku-report-wrapper {
            background: #fff;
            color: #1f2937;
            padding: 0;
            font-family: 'Segoe UI', Calibri, Arial, sans-serif;
            width: 100%;
        }

        .page-box {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .head-banner {
            background: #7c3f0f;
            color: #fff;
            padding: 16px 24px;
        }

        .head-banner h1 {
            font-size: 22px;
            font-weight: 800;
            margin: 0;
            line-height: 1.3;
        }

        .head-banner .sub {
            font-size: 13.5px;
            opacity: 0.9;
            margin-top: 4px;
        }

        .sel-bar {
            background: #f9fafb;
            border-bottom: 1px solid #d1d5db;
            padding: 16px 24px;
            font-size: 14px;
        }

        .sel-bar table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .sel-bar td {
            padding: 0 14px 0 0;
            vertical-align: bottom;
            white-space: nowrap;
        }

        .sel-bar label {
            display: block;
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 5px;
            letter-spacing: 0.75px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .ctl-input {
            border: 1px solid #d1d5db;
            background: #fff;
            padding: 9px 12px;
            border-radius: 6px;
            font-size: 14px;
            color: #1f2937;
            font-weight: 600;
            outline: none;
            width: 100%;
        }

        .ctl-input:focus {
            border-color: #b45309;
        }

        .btn-run {
            background: #b45309;
            color: #fff;
            padding: 9px 22px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .btn-run:hover {
            background: #92400e;
        }

        .btn-pdf {
            background: #16a34a;
            color: #fff;
            padding: 9px 18px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .btn-pdf:hover {
            background: #15803d;
        }

        /* Table Data matching provided HTML */
        table.d {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.d th {
            background: #fdf3e7;
            color: #92650f;
            text-align: left;
            padding: 11px 18px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 700;
            font-size: 13.5px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        table.d th.n,
        table.d td.n {
            text-align: right !important;
        }

        table.d td {
            padding: 9px 18px;
            border-bottom: 1px solid #eee;
            color: #1f2937;
            font-size: 14px;
        }

        table.d tr.cat td {
            background: #f3f4f6;
            font-weight: 800;
            color: #7c3f0f;
            padding: 10px 18px;
            font-size: 15px;
        }

        table.d tr.sub td {
            font-weight: 800;
            background: #fafafa;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14.5px;
            padding: 10px 18px;
        }

        table.d tr.tot td {
            font-weight: 800;
            background: #1f2937;
            color: #fff !important;
            padding: 12px 18px;
            font-size: 15px;
        }

        table.d tr.tot td[style] {
            color: #fff !important;
        }

        .note-footer {
            font-size: 12.5px;
            color: #6b7280;
            padding: 14px 24px;
            background: #fff;
            line-height: 1.5;
            border-top: 1px solid #f3f4f6;
        }

        /* Missing Target Badge */
        .no-target-badge {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            cursor: help;
        }

        .no-target-badge .dash {
            color: #9ca3af;
            font-weight: 700;
            font-size: 14px;
        }

        .no-target-badge .badge-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 17px;
            height: 17px;
            font-size: 11px;
            font-weight: 800;
            color: #92400e;
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 50%;
            line-height: 1;
            transition: transform 0.15s ease, background-color 0.15s ease;
        }

        .no-target-badge:hover .badge-icon {
            transform: scale(1.18);
            background: #fde68a;
            border-color: #f59e0b;
        }

        /* Loading */
        #loadingSpinner {
            padding: 30px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <div class="sku-report-wrapper">
            <div class="page-box">
                <!-- Header Banner -->
                <div class="head-banner">
                    <h1>SKU Target vs Actual</h1>
                    <div class="sub" id="lblHeaderSubtitle">Dimuthu Bake House &nbsp;•&nbsp; Please select an Agent and Date Range to Run Report</div>
                </div>

                <!-- Selection Controls Form -->
                <div class="sel-bar">
                    <form id="skuReportForm" onsubmit="return false;">
                        <table>
                            <tr>
                                <td style="width: 35%;">
                                    <label for="agent_id">AGENT</label>
                                    <select id="agent_id" name="agent_id" class="ctl-input" required>
                                        <option value="" selected>-- Select Agent --</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}">{{ $agent->agent_name }} ({{ $agent->agent_code }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 25%;">
                                    <label for="from_date">FROM</label>
                                    <input type="text" id="from_date" name="start_date" class="ctl-input" placeholder="Select From Date" readonly required>
                                </td>
                                <td style="width: 25%;">
                                    <label for="to_date">TO</label>
                                    <input type="text" id="to_date" name="end_date" class="ctl-input" placeholder="Select To Date" readonly required>
                                </td>
                                <td style="width: 15%; padding-right: 0;">
                                    <label>&nbsp;</label>
                                    <div style="display: flex; gap: 6px;">
                                        <button type="button" class="btn-run" onclick="loadSkuReport()">
                                            Run Report
                                        </button>
                                        <button type="button" class="btn-pdf" onclick="exportSkuPdf()" title="Export PDF">
                                            <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>

                <!-- Initial Empty State -->
                <div id="emptyPrompt" class="py-16 text-center text-gray-500 font-medium bg-white">
                    <i class="bi bi-funnel text-3xl text-amber-700/70 mb-2 block"></i>
                    Please select an <strong>Agent</strong>, <strong>From Date</strong>, and <strong>To Date</strong> above, then click <strong>Run Report</strong>.
                </div>

                <!-- Loading State -->
                <div id="loadingSpinner" style="display: none;">
                    <div class="inline-block animate-spin rounded-full h-7 w-7 border-4 border-amber-700 border-t-transparent mb-2"></div>
                    <div>Compiling SKU targets and actual sales...</div>
                </div>

                <!-- Data Table -->
                <div id="reportContent" style="display: none;">
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
                        <tbody id="tblSkuData">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Footnote -->
                <div class="note-footer" id="reportFootnote" style="display: none;">
                    Units. Target = monthly SKU target prorated to the selected dates. Red = below target (left behind), green = target achieved. &nbsp;•&nbsp; <span style="display: inline-flex; align-items: center; gap: 4px;"><span style="display:inline-flex; align-items:center; justify-content:center; width:15px; height:15px; font-size:10px; font-weight:800; color:#92400e; background:#fef3c7; border:1px solid:#fcd34d; border-radius:50%;">!</span> <strong>Target not added for this item in selected period</strong></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const formatNumber = (val) => Math.round(Number(val) || 0).toLocaleString('en-US');

        document.addEventListener('DOMContentLoaded', function () {
            // Flatpickr for From Date (empty by default)
            flatpickr("#from_date", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y",
                allowInput: false,
                placeholder: "Select From Date"
            });

            // Flatpickr for To Date (empty by default)
            flatpickr("#to_date", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y",
                allowInput: false,
                placeholder: "Select To Date"
            });
        });

        function loadSkuReport() {
            const agentId = document.getElementById('agent_id').value;
            const startDate = document.getElementById('from_date').value;
            const endDate = document.getElementById('to_date').value;

            if (!agentId || !startDate || !endDate) {
                Swal.fire('Warning', 'Please select an agent, from date, and to date first.', 'warning');
                return;
            }

            document.getElementById('emptyPrompt').style.display = 'none';
            document.getElementById('loadingSpinner').style.display = 'block';
            document.getElementById('reportContent').style.display = 'none';
            document.getElementById('reportFootnote').style.display = 'none';

            const formData = new FormData();
            formData.append('agent_id', agentId);
            formData.append('start_date', startDate);
            formData.append('end_date', endDate);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('reports.skuTargetVsActual.data') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    document.getElementById('loadingSpinner').style.display = 'none';

                    if (res.success) {
                        populateSkuTable(res);
                        document.getElementById('reportContent').style.display = 'block';
                        document.getElementById('reportFootnote').style.display = 'block';
                    } else {
                        document.getElementById('emptyPrompt').style.display = 'block';
                        Swal.fire('Error', res.message || 'Failed to load SKU report.', 'error');
                    }
                },
                error: function (xhr) {
                    document.getElementById('loadingSpinner').style.display = 'none';
                    document.getElementById('emptyPrompt').style.display = 'block';
                    Swal.fire('Error', 'Failed to retrieve SKU report data.', 'error');
                }
            });
        }

        function populateSkuTable(res) {
            // Update subtitle
            document.getElementById('lblHeaderSubtitle').innerHTML = 
                `${res.header_info.company_name} &nbsp;•&nbsp; ${res.header_info.agent_label} &nbsp;•&nbsp; ${res.header_info.from_date} – ${res.header_info.to_date}`;

            const tbody = document.getElementById('tblSkuData');
            tbody.innerHTML = '';

            // Render categories and items
            res.categories.forEach(cat => {
                // Category Header Row
                tbody.innerHTML += `
                    <tr class="cat">
                        <td colspan="5">${cat.category_name}</td>
                    </tr>
                `;

                // Product Rows
                cat.items.forEach(it => {
                    let targetHtml, achHtml, gapHtml;

                    if (it.has_target) {
                        const achColor = it.is_achieved ? '#16a34a' : '#dc2626';
                        const gapColor = it.is_achieved ? '#16a34a' : '#dc2626';
                        const gapPrefix = it.gap > 0 ? '+' : (it.gap < 0 ? '-' : '');
                        const gapDisplay = gapPrefix + formatNumber(Math.abs(it.gap));

                        targetHtml = `<td class="n">${formatNumber(it.target)}</td>`;
                        achHtml = `<td class="n" style="color:${achColor};font-weight:700;">${it.achievement}%</td>`;
                        gapHtml = `<td class="n" style="color:${gapColor};font-weight:600;">${gapDisplay}</td>`;
                    } else {
                        targetHtml = `
                            <td class="n">
                                <span class="no-target-badge" title="Target not added for this item in selected period">
                                    <span class="dash">-</span>
                                    <span class="badge-icon" title="Target not added for this item in selected period">!</span>
                                </span>
                            </td>
                        `;
                        achHtml = `<td class="n" style="color:#9ca3af;font-weight:600;">-</td>`;
                        gapHtml = `<td class="n" style="color:#9ca3af;font-weight:600;">-</td>`;
                    }

                    tbody.innerHTML += `
                        <tr class="">
                            <td>${it.name}</td>
                            ${targetHtml}
                            <td class="n">${formatNumber(it.actual)}</td>
                            ${achHtml}
                            ${gapHtml}
                        </tr>
                    `;
                });

                // Category Subtotal Row
                let subTargetHtml, subAchHtml, subGapHtml;
                if (cat.has_target) {
                    const subAchColor = cat.is_achieved ? '#16a34a' : '#dc2626';
                    const subGapColor = cat.is_achieved ? '#16a34a' : '#dc2626';
                    const subGapPrefix = cat.subtotal_gap > 0 ? '+' : (cat.subtotal_gap < 0 ? '-' : '');
                    const subGapDisplay = subGapPrefix + formatNumber(Math.abs(cat.subtotal_gap));

                    subTargetHtml = `<td class="n">${formatNumber(cat.subtotal_target)}</td>`;
                    subAchHtml = `<td class="n" style="color:${subAchColor};font-weight:700;">${cat.subtotal_achievement}%</td>`;
                    subGapHtml = `<td class="n" style="color:${subGapColor};font-weight:700;">${subGapDisplay}</td>`;
                } else {
                    subTargetHtml = `<td class="n" style="color:#9ca3af;font-weight:600;">-</td>`;
                    subAchHtml = `<td class="n" style="color:#9ca3af;font-weight:600;">-</td>`;
                    subGapHtml = `<td class="n" style="color:#9ca3af;font-weight:600;">-</td>`;
                }

                tbody.innerHTML += `
                    <tr class="sub">
                        <td>Total for ${cat.category_name}</td>
                        ${subTargetHtml}
                        <td class="n">${formatNumber(cat.subtotal_actual)}</td>
                        ${subAchHtml}
                        ${subGapHtml}
                    </tr>
                `;
            });

            // Grand Total Row
            let totTargetHtml, totAchHtml, totGapHtml;
            if (res.totals.target > 0) {
                const totGapPrefix = res.totals.gap > 0 ? '+' : (res.totals.gap < 0 ? '-' : '');
                const totGapDisplay = totGapPrefix + formatNumber(Math.abs(res.totals.gap));
                totTargetHtml = `<td class="n">${formatNumber(res.totals.target)}</td>`;
                totAchHtml = `<td class="n" style="color:#fff !important;">${res.totals.achievement}%</td>`;
                totGapHtml = `<td class="n" style="color:#fff !important;">${totGapDisplay}</td>`;
            } else {
                totTargetHtml = `<td class="n" style="color:#9ca3af;">-</td>`;
                totAchHtml = `<td class="n" style="color:#9ca3af;">-</td>`;
                totGapHtml = `<td class="n" style="color:#9ca3af;">-</td>`;
            }

            tbody.innerHTML += `
                <tr class="tot">
                    <td>TOTAL</td>
                    ${totTargetHtml}
                    <td class="n">${formatNumber(res.totals.actual)}</td>
                    ${totAchHtml}
                    ${totGapHtml}
                </tr>
            `;
        }

        function exportSkuPdf() {
            const agentId = document.getElementById('agent_id').value;
            const startDate = document.getElementById('from_date').value;
            const endDate = document.getElementById('to_date').value;

            if (!agentId || !startDate || !endDate) {
                Swal.fire('Warning', 'Please select an agent and date range.', 'warning');
                return;
            }

            const url = `{{ route('reports.skuTargetVsActual.exportPdf') }}?agent_id=${agentId}&start_date=${startDate}&end_date=${endDate}`;
            window.open(url, '_blank');
        }
    </script>
@endsection

