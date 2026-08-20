@extends('layouts.app')

@section('content')

    <style>
        /* Table Styles matching system */
        .table-custom thead th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-custom tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.875rem;
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
            transition: background-color 0.2s ease-in-out;
        }

        .table-custom tfoot th {
            background-color: #f8fafc;
            color: #1e293b;
            font-weight: 700;
            padding: 1rem;
            border-top: 2px solid #e2e8f0;
        }

        .action-btn {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.75rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .action-btn-view {
            background-color: #f0f9ff;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }

        .action-btn-view:hover {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .action-btn-excel {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .action-btn-excel:hover {
            background-color: #d1fae5;
            color: #047857;
        }

        /* Modal mini tables */
        .mini-table th {
            font-size: 0.7rem;
            background: #f8fafc;
            color: #64748b;
            padding: 0.5rem;
            text-transform: uppercase;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
        }

        .mini-table td {
            font-size: 0.8rem;
            padding: 0.5rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        /* Status badges */
        .status-badge {
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            letter-spacing: 0.02em;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status-dispatched {
            background-color: #e0e7ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
        }

        .status-completed {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Agent Order Requests Report</h2>
                <p class="text-sm text-gray-500 mt-1">Overview of all agents with order request totals, payments, and
                    outstanding balances.</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <form id="reportFilterForm" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                @csrf
                <!-- Single Date Filter -->
                <div>
                    <label for="filter_date" class="block text-sm font-semibold text-gray-700 mb-1">Select Date</label>
                    <input type="date" id="filter_date" name="date" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5">
                </div>

                {{-- Temporarily hidden Date Range (uncomment when needed)
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1">Start Date</label>
                    <input type="date" id="start_date" name="start_date"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5">
                </div>
                --}}

                <div class="flex items-center gap-3">
                    <button type="submit" id="btnLoadReport"
                        class="flex-1 flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <i class="bi bi-funnel mr-2"></i> Load Report
                    </button>
                    <button type="button" id="btnExportAllExcel" onclick="exportAllAgentsExcel()"
                        class="flex-1 flex justify-center items-center px-4 py-2.5 border border-emerald-600 text-sm font-medium rounded-lg shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                        <i class="bi bi-file-earmark-excel mr-2"></i> Excel
                    </button>
                </div>
            </form>
        </div>

        <!-- Report Data Table Container -->
        <div id="reportContainer" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full table-custom">
                    <thead>
                        <tr>
                            <th class="w-12 text-center px-4">#</th>
                            <th class="text-left">Agent Name</th>
                            <th class="text-left">Agent Code</th>
                            <th class="text-center">Total Orders</th>
                            <th class="text-right">Total Purchase Amount (Rs)</th>
                            <th class="text-right">Outstanding (Rs)</th>
                            <th class="text-right">Paid Amount (Rs)</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">
                        <!-- Data injected via AJAX -->
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 border-t-2 border-gray-200">
                            <th colspan="3" class="text-right uppercase px-4 py-3">Total</th>
                            <th class="text-center font-bold text-green-700" id="ft_total_orders">0</th>
                            <th class="text-right" id="ft_total_order_amount">0.00</th>
                            <th class="text-right" id="ft_outstanding">0.00</th>
                            <th class="text-right" id="ft_paid_amount">0.00</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- Pagination Controls -->
            <div id="mainTablePagination" class="flex justify-end gap-2 mt-4 px-4 py-2 border-t border-gray-100"></div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden py-12 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            <p class="mt-2 text-sm text-gray-500 font-medium">Fetching report data...</p>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden py-16 text-center bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <i class="bi bi-clipboard-x text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No Data Found</h3>
            <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">No order requests found for the selected date.
            </p>
        </div>
    </div>

    <!-- Agent Details Modal -->
    <div id="detailsModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeDetailsModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-6xl border border-gray-100">
                <div class="bg-white px-6 py-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-5">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-person-badge text-indigo-600"></i>
                            <span id="modalAgentName">Agent Details</span>
                            <span
                                class="bg-indigo-100 text-indigo-800 text-xs px-2.5 py-0.5 rounded-full font-semibold ml-2 border border-indigo-200 shadow-sm"
                                id="modalAgentCode"></span>
                        </h3>
                        <button onclick="closeDetailsModal()"
                            class="text-gray-400 hover:text-gray-500 bg-gray-50 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm text-center">
                            <span class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide">Total
                                Purchase Amount</span>
                            <span class="block text-lg font-bold text-gray-900 mt-1" id="modalTotalOrderAmount">0.00</span>
                        </div>
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl shadow-sm text-center">
                            <span
                                class="block text-[10px] font-semibold text-rose-500 uppercase tracking-wide">Outstanding</span>
                            <span class="block text-lg font-black text-rose-700 mt-1" id="modalOutstanding">0.00</span>
                        </div>
                        <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm text-center">
                            <span class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide">Paid
                                Amount</span>
                            <span class="block text-lg font-bold text-emerald-600 mt-1" id="modalPaidAmount">0.00</span>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="flex flex-col gap-6">
                        <div class="px-2">
                            <div class="flex justify-between items-center mb-3">
                                <h4
                                    class="text-sm font-bold text-indigo-800 uppercase tracking-wider flex items-center gap-2">
                                    <i class="bi bi-receipt-cutoff"></i> Order Requests
                                </h4>
                                <input type="text" id="searchOrders" onkeyup="handleSearch('orders')"
                                    placeholder="Search orders..."
                                    class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <table class="w-full mini-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center px-3 w-10">#</th>
                                            <th class="text-left px-3">Order No</th>
                                            <th class="text-center px-3">Delivery Date</th>
                                            <th class="text-right px-3">Grand Total</th>
                                            <th class="text-right px-3">Outstanding</th>
                                            <th class="text-right px-3">Paid</th>
                                            <th class="text-center px-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalOrdersBody">
                                        <!-- Injected via JS -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-50 font-bold">
                                            <td colspan="3" class="text-right px-3 border-t border-gray-200">Total</td>
                                            <td class="text-right px-3 border-t border-gray-200 text-indigo-700"
                                                id="modalOrdersGrandTotal">0.00</td>
                                            <td class="text-right px-3 border-t border-gray-200 text-rose-700"
                                                id="modalOrdersOutstandingTotal">0.00</td>
                                            <td class="text-right px-3 border-t border-gray-200 text-emerald-700"
                                                id="modalOrdersPaidTotal">0.00</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div id="ordersPagination" class="flex justify-end gap-1 mt-2"></div>
                        </div>

                        <!-- Payments Table -->
                        {{--
                        <div class="px-2">
                            <div class="flex justify-between items-center mb-3">
                                <h4
                                    class="text-sm font-bold text-emerald-800 uppercase tracking-wider flex items-center gap-2">
                                    <i class="bi bi-credit-card"></i> Payment Details
                                </h4>
                                <input type="text" id="searchPayments" onkeyup="handleSearch('payments')"
                                    placeholder="Search payments..."
                                    class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <table class="w-full mini-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center px-3 w-10">#</th>
                                            <th class="text-center px-3">Payment Date</th>
                                            <th class="text-left px-3">Payment No</th>
                                            <th class="text-right px-3">Amount (Rs)</th>
                                            <th class="text-center px-3">Method</th>
                                            <th class="text-left px-3">Reference/Notes</th>
                                            <th class="text-center px-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalPaymentsBody">
                                        <!-- Injected via JS -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-emerald-50 font-bold">
                                            <td colspan="3" class="text-right px-3 border-t border-emerald-100">Total
                                            </td>
                                            <td class="text-right px-3 border-t border-emerald-100 text-emerald-700"
                                                id="modalPaymentsTotal">0.00</td>
                                            <td colspan="3" class="border-t border-emerald-100"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div id="paymentsPagination" class="flex justify-end gap-1 mt-2"></div>
                        </div>
                        --}}
                    </div>

                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-100">
                    <button type="button" onclick="closeDetailsModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none transition-colors shadow-sm">
                        Close Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store report data globally
        let currentReportData = [];

        // Modal state
        let currentOrders = [];
        let currentPayments = [];
        let ordersPage = 1;
        let paymentsPage = 1;
        const itemsPerPage = 10;
        const modalItemsPerPage = 8;
        let ordersSearch = '';
        let paymentsSearch = '';

        const formatMoney = (amount) => Number(amount).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        function getPaginationArray(currentPage, totalPages) {
            const delta = 1;
            const range = [];
            for (let i = Math.max(2, currentPage - delta); i <= Math.min(totalPages - 1, currentPage + delta); i++) {
                range.push(i);
            }
            if (currentPage - delta > 2) range.unshift('...');
            if (currentPage + delta < totalPages - 1) range.push('...');
            range.unshift(1);
            if (totalPages > 1) range.push(totalPages);
            return range;
        }

        function getStatusBadge(status, statusCode) {
            const classMap = {
                0: 'status-pending',
                1: 'status-approved',
                2: 'status-rejected',
                3: 'status-dispatched',
                4: 'status-dispatched',
                5: 'status-dispatched',
                6: 'status-dispatched',
                7: 'status-completed',
            };
            const iconMap = {
                0: 'bi-hourglass-split',
                1: 'bi-check-circle',
                2: 'bi-x-circle',
                3: 'bi-gear',
                4: 'bi-box-seam',
                5: 'bi-truck',
                6: 'bi-check2-square',
                7: 'bi-check-all',
            };
            const cls = classMap[statusCode] || 'status-pending';
            const icon = iconMap[statusCode] || 'bi-question-circle';
            return `<span class="status-badge ${cls}"><i class="bi ${icon}"></i> ${status}</span>`;
        }

        function getPaymentStatusBadge(status, statusCode) {
            const classMap = {
                0: 'status-pending',
                1: 'status-approved',
                2: 'status-rejected',
            };
            const cls = classMap[statusCode] || 'status-pending';
            return `<span class="status-badge ${cls}">${status}</span>`;
        }

        // Form Submit
        document.getElementById('reportFilterForm').addEventListener('submit', function (e) {
            e.preventDefault();
            loadReportData();
        });

        function loadReportData() {
            const formData = new FormData(document.getElementById('reportFilterForm'));

            document.getElementById('reportContainer').classList.add('hidden');
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.remove('hidden');

            $.ajax({
                url: "{{ route('reports.agentOrderRequests.data') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    document.getElementById('loadingIndicator').classList.add('hidden');

                    if (response.success && response.data.length > 0) {
                        currentReportData = response.data;
                        renderMainTable(1);
                        document.getElementById('reportContainer').classList.remove('hidden');
                    } else {
                        document.getElementById('emptyState').classList.remove('hidden');
                    }
                },
                error: function (xhr) {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                    let msg = 'Failed to load report data.';
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    Swal.fire('Error', msg, 'error');
                }
            });
        }

        let currentMainPage = 1;

        function renderMainTable(page = 1) {
            currentMainPage = page;
            const data = currentReportData;
            const tbody = document.getElementById('reportTableBody');
            tbody.innerHTML = '';

            let sumOrders = 0;
            let sumOrderAmount = 0;
            let sumPaid = 0;
            let sumOutstanding = 0;

            // Calculate totals for ALL data
            data.forEach((row) => {
                sumOrders += Number(row.total_orders);
                sumOrderAmount += Number(row.total_order_amount);
                sumPaid += Number(row.paid_amount);
                sumOutstanding += Number(row.outstanding);
            });

            // Pagination slice
            const totalPages = Math.ceil(data.length / itemsPerPage);
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedData = data.slice(startIndex, endIndex);

            paginatedData.forEach((row, i) => {
                const index = startIndex + i;
                const outstandingColor = Number(row.outstanding) > 0 ? 'color: #dc2626; font-weight: 700;' :
                    'color: #16a34a; font-weight: 600;';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                                <td class="text-center text-gray-500 font-medium">${index + 1}</td>
                                <td class="font-medium text-gray-900">${row.agent_name}</td>
                                <td class="text-gray-600"><span class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded font-mono">${row.agent_code}</span></td>
                                <td class="text-center font-bold" style="color: #4f46e5;">${row.total_orders}</td>
                                <td class="text-right text-gray-700">${formatMoney(row.total_order_amount)}</td>
                                <td class="text-right" style="${outstandingColor}">${formatMoney(row.outstanding)}</td>
                                <td class="text-right text-emerald-600 font-semibold">${formatMoney(row.paid_amount)}</td>
                                <td class="text-center">
                                    <button type="button" onclick="viewAgentDetails(${index})" class="action-btn action-btn-view">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                    <button type="button" onclick="exportAgentExcel(${row.agent_id})" class="action-btn action-btn-excel ms-1">
                                        <i class="bi bi-file-earmark-excel"></i> Excel
                                    </button>
                                </td>
                            `;
                tbody.appendChild(tr);
            });

            // Update footer totals
            document.getElementById('ft_total_orders').innerText = sumOrders;
            document.getElementById('ft_total_order_amount').innerText = formatMoney(sumOrderAmount);
            document.getElementById('ft_paid_amount').innerText = formatMoney(sumPaid);
            document.getElementById('ft_outstanding').innerText = formatMoney(sumOutstanding);

            // Render pagination buttons
            const paginationContainer = document.getElementById('mainTablePagination');
            if (paginationContainer) {
                let btnHtml = '';
                if (totalPages > 1) {
                    const pages = getPaginationArray(page, totalPages);
                    pages.forEach(p => {
                        if (p === '...') {
                            btnHtml += `<span class="px-3 py-1 text-sm font-medium text-gray-500 flex items-end">...</span>`;
                        } else {
                            const activeClass = p === page ?
                                'bg-indigo-600 text-white border-indigo-600' :
                                'bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
                            btnHtml +=
                                `<button type="button" onclick="renderMainTable(${p})" class="px-3 py-1 border text-sm font-medium rounded transition-colors ${activeClass}">${p}</button>`;
                        }
                    });
                }
                paginationContainer.innerHTML = btnHtml;
            }
        }

        // Export All Agents Summary Excel
        function exportAllAgentsExcel() {
            const dateInput = document.getElementById('filter_date');
            const selectedDate = dateInput ? dateInput.value : '';
            const startDate = document.getElementById('start_date')?.value || selectedDate;
            const endDate = document.getElementById('end_date')?.value || selectedDate;

            let url = "{{ route('reports.agentOrderRequests.exportAllExcel') }}";
            const params = [];
            if (selectedDate) {
                params.push(`date=${encodeURIComponent(selectedDate)}`);
                params.push(`start_date=${encodeURIComponent(selectedDate)}`);
                params.push(`end_date=${encodeURIComponent(selectedDate)}`);
            } else if (startDate && endDate) {
                params.push(`start_date=${encodeURIComponent(startDate)}`);
                params.push(`end_date=${encodeURIComponent(endDate)}`);
            }

            if (params.length > 0) {
                url += `?${params.join('&')}`;
            }

            window.location.href = url;
        }

        // Export Agent Excel
        function exportAgentExcel(agentId) {
            const dateInput = document.getElementById('filter_date');
            const selectedDate = dateInput ? dateInput.value : '';
            const startDate = document.getElementById('start_date')?.value || selectedDate;
            const endDate = document.getElementById('end_date')?.value || selectedDate;

            let url = `/reports/agent-order-requests/export-excel?agent_id=${agentId}`;
            if (selectedDate) {
                url += `&date=${selectedDate}&start_date=${selectedDate}&end_date=${selectedDate}`;
            } else if (startDate && endDate) {
                url += `&start_date=${startDate}&end_date=${endDate}`;
            }

            window.location.href = url;
        }

        async function viewAgentDetails(index) {
            const row = currentReportData[index];
            if (!row) return;

            document.getElementById('modalAgentName').innerText = row.agent_name;
            document.getElementById('modalAgentCode').innerText = row.agent_code;
            document.getElementById('modalTotalOrderAmount').innerText = formatMoney(row.total_order_amount);
            document.getElementById('modalPaidAmount').innerText = formatMoney(row.paid_amount);
            document.getElementById('modalOutstanding').innerText = formatMoney(row.outstanding);

            // Show loading in tables
            document.getElementById('modalOrdersBody').innerHTML =
                '<tr><td colspan="7" class="text-center text-gray-500 py-4"><div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-600"></div> Loading orders...</td></tr>';
            if (document.getElementById('modalPaymentsBody')) {
                document.getElementById('modalPaymentsBody').innerHTML =
                    '<tr><td colspan="7" class="text-center text-gray-500 py-4"><div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-emerald-600"></div> Loading payments...</td></tr>';
            }
            if (document.getElementById('ordersPagination')) document.getElementById('ordersPagination').innerHTML = '';
            if (document.getElementById('paymentsPagination')) document.getElementById('paymentsPagination').innerHTML = '';

            document.getElementById('detailsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            try {
                const selectedDate = document.getElementById('filter_date')?.value || '';
                const startDate = document.getElementById('start_date')?.value || selectedDate;
                const endDate = document.getElementById('end_date')?.value || selectedDate;

                const response = await fetch("{{ route('reports.agentOrderRequests.details') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        agent_id: row.agent_id,
                        date: selectedDate,
                        start_date: startDate,
                        end_date: endDate
                    })
                });

                const res = await response.json();
                if (res.success) {
                    currentOrders = res.orders || [];
                    currentPayments = res.payments || [];

                    if (document.getElementById('searchOrders')) document.getElementById('searchOrders').value = '';
                    if (document.getElementById('searchPayments')) document.getElementById('searchPayments').value = '';
                    ordersSearch = '';
                    paymentsSearch = '';

                    renderPaginatedTable('orders', 1);
                    renderPaginatedTable('payments', 1);
                } else {
                    Swal.fire('Error', 'Failed to fetch agent details.', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'An error occurred while fetching details.', 'error');
            }
        }

        function handleSearch(type) {
            if (type === 'orders') {
                ordersSearch = document.getElementById('searchOrders').value.toLowerCase();
                renderPaginatedTable('orders', 1);
            } else {
                paymentsSearch = document.getElementById('searchPayments').value.toLowerCase();
                renderPaginatedTable('payments', 1);
            }
        }

        function renderPaginatedTable(type, page) {
            const isOrders = type === 'orders';
            const rawData = isOrders ? currentOrders : currentPayments;
            const searchStr = isOrders ? ordersSearch : paymentsSearch;
            const tbody = document.getElementById(isOrders ? 'modalOrdersBody' : 'modalPaymentsBody');
            const paginationContainer = document.getElementById(isOrders ? 'ordersPagination' : 'paymentsPagination');
            if (!tbody) return;

            // Filter
            let filteredData;
            if (isOrders) {
                filteredData = rawData.filter(o =>
                    o.order_number.toLowerCase().includes(searchStr) ||
                    o.status.toLowerCase().includes(searchStr)
                );
            } else {
                filteredData = rawData.filter(p =>
                    p.payment_number.toLowerCase().includes(searchStr) ||
                    p.payment_method.toLowerCase().includes(searchStr) ||
                    p.payment_reference.toLowerCase().includes(searchStr)
                );
            }

            // Totals
            if (isOrders) {
                let grandTotal = 0,
                    paidTotal = 0,
                    outTotal = 0;
                filteredData.forEach(o => {
                    grandTotal += Number(o.grand_total);
                    paidTotal += Number(o.paid_amount);
                    outTotal += Number(o.outstanding);
                });
                document.getElementById('modalOrdersGrandTotal').innerText = formatMoney(grandTotal);
                document.getElementById('modalOrdersPaidTotal').innerText = formatMoney(paidTotal);
                document.getElementById('modalOrdersOutstandingTotal').innerText = formatMoney(outTotal);
                ordersPage = page;
            } else {
                let payTotal = 0;
                filteredData.forEach(p => {
                    payTotal += Number(p.payment_amount);
                });
                if (document.getElementById('modalPaymentsTotal')) document.getElementById('modalPaymentsTotal').innerText = formatMoney(payTotal);
                paymentsPage = page;
            }

            // Pagination Logic
            const totalPages = Math.ceil(filteredData.length / modalItemsPerPage) || 1;
            if (page > totalPages) page = totalPages;
            if (page < 1) page = 1;

            const startIdx = (page - 1) * modalItemsPerPage;
            const pagedData = filteredData.slice(startIdx, startIdx + modalItemsPerPage);

            tbody.innerHTML = '';
            if (pagedData.length > 0) {
                if (isOrders) {
                    pagedData.forEach((o, idx) => {
                        const rowNum = startIdx + idx + 1;
                        const outColor = Number(o.outstanding) > 0 ? 'text-rose-600 font-semibold' :
                            'text-green-600';
                        tbody.innerHTML += `
                                        <tr>
                                            <td class="text-center px-3 text-gray-500 font-medium">${rowNum}</td>
                                            <td class="px-3 font-medium font-mono text-indigo-700">${o.order_number}</td>
                                            <td class="text-center px-3">${o.delivery_date}</td>
                                            <td class="text-right px-3 font-medium">${formatMoney(o.grand_total)}</td>
                                            <td class="text-right px-3 ${outColor}">${formatMoney(o.outstanding)}</td>
                                            <td class="text-right px-3 text-emerald-600">${formatMoney(o.paid_amount)}</td>
                                            <td class="text-center px-3">${getStatusBadge(o.status, o.status_code)}</td>
                                        </tr>
                                    `;
                    });
                } else {
                    pagedData.forEach((p, idx) => {
                        const rowNum = startIdx + idx + 1;
                        tbody.innerHTML += `
                                        <tr>
                                            <td class="text-center px-3 text-gray-500 font-medium">${rowNum}</td>
                                            <td class="text-center px-3">${p.payment_date}</td>
                                            <td class="px-3 font-mono text-indigo-700">${p.payment_number}</td>
                                            <td class="text-right px-3 font-medium text-emerald-700">${formatMoney(p.payment_amount)}</td>
                                            <td class="text-center px-3">${p.payment_method}</td>
                                            <td class="px-3 text-gray-600">${p.payment_reference}</td>
                                            <td class="text-center px-3">${getPaymentStatusBadge(p.status, p.status_code)}</td>
                                        </tr>
                                    `;
                    });
                }
            } else {
                const cols = isOrders ? 7 : 7;
                const label = isOrders ? 'orders' : 'payments';
                tbody.innerHTML =
                    `<tr><td colspan="${cols}" class="text-center text-gray-400 py-4">No ${label} found</td></tr>`;
            }

            // Render Pagination Buttons
            let btnHtml = '';
            if (totalPages > 1) {
                const colorTheme = isOrders ? 'indigo' : 'emerald';
                const pages = getPaginationArray(page, totalPages);
                pages.forEach(p => {
                    if (p === '...') {
                        btnHtml += `<span class="px-2 py-1 text-xs font-medium text-gray-500 flex items-end">...</span>`;
                    } else {
                        const activeClass = p === page ?
                            `bg-${colorTheme}-600 text-white border-${colorTheme}-600` :
                            'bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
                        btnHtml +=
                            `<button type="button" onclick="renderPaginatedTable('${type}', ${p})" class="px-2 py-1 border text-xs font-medium rounded transition-colors ${activeClass}">${p}</button>`;
                    }
                });
            }
            paginationContainer.innerHTML = btnHtml;
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Set default date and auto-load on page load
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('filter_date');
            if (dateInput) {
                dateInput.value = today;
            }
            if (document.getElementById('start_date')) {
                const firstOfMonth = today.substring(0, 7) + '-01';
                document.getElementById('start_date').value = firstOfMonth;
            }
            if (document.getElementById('end_date')) {
                document.getElementById('end_date').value = today;
            }
            // Auto-load report
            loadReportData();
        });
    </script>
@endsection