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
    </style>

    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Agent Shop Sales Report</h2>
                <p class="text-sm text-gray-500 mt-1">View detailed sales, returns, and outstanding collections by agent
                    customers.</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <form id="reportFilterForm" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                @csrf
                <div>
                    <label for="agent_id" class="block text-sm font-semibold text-gray-700 mb-1">Select Agent</label>
                    <select id="agent_id" name="agent_id" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5">
                        <option value="">-- Choose an Agent --</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->agent_name }} ({{ $agent->agent_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-1">Start Date</label>
                    <input type="date" id="start_date" name="start_date" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5">
                </div>
                <div>
                    <button type="submit" id="btnLoadReport"
                        class="w-full flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <i class="bi bi-funnel mr-2"></i> Load Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Report Data Table Container -->
        <div id="reportContainer" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="exportToExcel()" class="flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>
                <button type="button" onclick="exportToPDF()" class="flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-custom">
                    <thead>
                        <tr>
                            <th class="w-12 text-center px-4">#</th>
                            <th class="text-left">Customer Name</th>
                            <th class="text-center">Visit Count</th>
                            <th class="text-right">Total Sales (Rs)</th>
                            <th class="text-right">Total Returns (Rs)</th>
                            <th class="text-right">Cash Income (Rs)</th>
                            <th class="text-right">Outstanding Amount (Rs)</th>
                            <th class="text-right">Total Credit (Rs)</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">
                        <!-- Data injected via AJAX -->
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 border-t-2 border-gray-200">
                            <th colspan="2" class="text-right uppercase px-4 py-3">Total</th>
                            <th class="text-center font-bold text-green-700" id="ft_visit_count">0</th>
                            <th class="text-right" id="ft_total_sales">0.00</th>
                            <th class="text-right" id="ft_total_returns">0.00</th>
                            <th class="text-right" id="ft_cash_income">0.00</th>
                            <th class="text-right" id="ft_outstanding">0.00</th>
                            <th class="text-right" id="ft_total_credit">0.00</th>
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
            <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">No invoices found for the selected agent and date range.
            </p>
        </div>
    </div>

    <!-- Customer Details Modal -->
    <div id="detailsModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="closeDetailsModal()"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-6xl border border-gray-100">
                <div class="bg-white px-6 py-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-5">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-shop text-indigo-600"></i>
                            <span id="modalCustomerName">Customer Details</span>
                            <span
                                class="bg-indigo-100 text-indigo-800 text-xs px-2.5 py-0.5 rounded-full font-semibold ml-2 border border-indigo-200 shadow-sm"
                                id="modalVisitBadge">Visits: 0</span>
                        </h3>
                        <button onclick="closeDetailsModal()"
                            class="text-gray-400 hover:text-gray-500 bg-gray-50 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <!-- Customer Info -->
                    <div
                        class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase">Phone Number</span>
                            <span class="block text-sm font-medium text-gray-900 mt-0.5" id="modalCustomerPhone">-</span>
                        </div>
                        <div class="md:col-span-2">
                            <span class="block text-xs font-semibold text-gray-500 uppercase">Address</span>
                            <span class="block text-sm font-medium text-gray-900 mt-0.5" id="modalCustomerAddress">-</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase">Customer Name</span>
                            <span class="block text-sm font-medium text-gray-900 mt-0.5" id="modalCustomerType">-</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase">Payment Terms</span>
                            <span class="block text-sm font-medium text-gray-900 mt-0.5" id="modalPaymentTerms">-</span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-500 uppercase">Credit Limit</span>
                            <span class="block text-sm font-medium text-gray-900 mt-0.5" id="modalCreditLimit">-</span>
                        </div>
                    </div>

                    <!-- Financial Details Grid -->
                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3 border-b border-gray-50 pb-2">
                        Financial Breakdown</h4>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                        <div class="p-3 bg-white border border-gray-200 rounded-xl shadow-sm text-center">
                            <span class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide">Total
                                Sales</span>
                            <span class="block text-base font-bold text-gray-900 mt-1" id="modalTotalSales">0.00</span>
                        </div>
                        <div class="p-3 bg-white border border-gray-200 rounded-xl shadow-sm text-center">
                            <span class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide">Total
                                Returns</span>
                            <span class="block text-base font-bold text-red-600 mt-1" id="modalTotalReturns">0.00</span>
                        </div>
                        <div class="p-3 bg-white border border-gray-200 rounded-xl shadow-sm text-center">
                            <span class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide">Cash
                                Income</span>
                            <span class="block text-base font-bold text-emerald-600 mt-1" id="modalCashIncome">0.00</span>
                        </div>
                        <div class="p-3 bg-white border border-gray-200 rounded-xl shadow-sm text-center">
                            <span class="block text-[10px] font-semibold text-gray-500 uppercase tracking-wide">Total
                                Credit</span>
                            <span class="block text-base font-bold text-amber-600 mt-1" id="modalTotalCredit">0.00</span>
                        </div>
                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl shadow-sm text-center">
                            <span
                                class="block text-[10px] font-semibold text-rose-500 uppercase tracking-wide">Outstanding</span>
                            <span class="block text-lg font-black text-rose-700 mt-1" id="modalOutstanding">0.00</span>
                        </div>
                    </div>

                    <!-- Products Stack -->
                    <div class="flex flex-col gap-6">
                        <!-- Sales Products -->
                        <div class="px-2">
                            <div class="flex justify-between items-center mb-3">
                                <h4
                                    class="text-sm font-bold text-indigo-800 uppercase tracking-wider flex items-center gap-2">
                                    <i class="bi bi-cart-check"></i> Sales Products
                                </h4>
                                <input type="text" id="searchSales" onkeyup="handleSearch('sales')" placeholder="Search..."
                                    class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <table class="w-full mini-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center px-4 w-12">#</th>
                                            <th class="text-left px-4">Product Name</th>
                                            <th class="text-center px-4">Qty</th>
                                            <th class="text-right px-4">Total (Rs)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalSalesProducts">
                                        <!-- Injected via JS -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-50 font-bold">
                                            <td colspan="2" class="text-right px-4 border-t border-gray-200">Total</td>
                                            <td class="text-center px-4 border-t border-gray-200" id="modalSalesQtyTotal">0
                                            </td>
                                            <td class="text-right px-4 border-t border-gray-200 text-indigo-700"
                                                id="modalSalesAmountTotal">0.00</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div id="salesPagination" class="flex justify-end gap-1 mt-2"></div>
                        </div>

                        <!-- Return Products -->
                        <div class="px-2">
                            <div class="flex justify-between items-center mb-3">
                                <h4
                                    class="text-sm font-bold text-rose-800 uppercase tracking-wider flex items-center gap-2">
                                    <i class="bi bi-arrow-return-left"></i> Return Products
                                </h4>
                                <input type="text" id="searchReturns" onkeyup="handleSearch('returns')"
                                    placeholder="Search..."
                                    class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-rose-500 focus:border-rose-500">
                            </div>
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <table class="w-full mini-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center px-4 w-12">#</th>
                                            <th class="text-left px-4">Product Name</th>
                                            <th class="text-center px-4">Qty</th>
                                            <th class="text-right px-4">Total (Rs)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalReturnProducts">
                                        <!-- Injected via JS -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-rose-50 font-bold">
                                            <td colspan="2" class="text-right px-4 border-t border-rose-100">Total</td>
                                            <td class="text-center px-4 border-t border-rose-100 text-rose-700"
                                                id="modalReturnsQtyTotal">0</td>
                                            <td class="text-right px-4 border-t border-rose-100 text-rose-700"
                                                id="modalReturnsAmountTotal">0.00</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div id="returnsPagination" class="flex justify-end gap-1 mt-2"></div>
                        </div>
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
        // Store report data globally for modal access
        let currentReportData = [];

        // Modal Pagination State
        let currentSalesProducts = [];
        let currentReturnProducts = [];
        let salesPage = 1;
        let returnsPage = 1;
        const itemsPerPage = 5;
        let salesSearch = '';
        let returnsSearch = '';

        const formatMoney = (amount) => Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        document.getElementById('reportFilterForm').addEventListener('submit', function (e) {
            e.preventDefault();
            loadReportData();
        });

        function loadReportData() {
            const formData = new FormData(document.getElementById('reportFilterForm'));

            // UI States
            document.getElementById('reportContainer').classList.add('hidden');
            document.getElementById('emptyState').classList.add('hidden');
            document.getElementById('loadingIndicator').classList.remove('hidden');

            $.ajax({
                url: "{{ route('reports.agentShopSales.data') }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    document.getElementById('loadingIndicator').classList.add('hidden');

                    if (response.success && response.data.length > 0) {
                        currentReportData = response.data;
                        renderTable(1);
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
        const mainItemsPerPage = 10;

        function renderTable(page = 1) {
            currentMainPage = page;
            const data = currentReportData;
            const tbody = document.getElementById('reportTableBody');
            tbody.innerHTML = '';

            let sumSales = 0;
            let sumReturns = 0;
            let sumIncome = 0;
            let sumCredit = 0;
            let sumOutstanding = 0;
            let sumVisits = 0;

            // Calculate totals for ALL data
            data.forEach((row) => {
                sumSales += Number(row.total_sales);
                sumReturns += Number(row.total_returns);
                sumIncome += Number(row.cash_income);
                sumCredit += Number(row.total_credit);
                sumOutstanding += Number(row.outstanding_amount);
                sumVisits += Number(row.visit_count);
            });

            // Pagination slice
            const totalPages = Math.ceil(data.length / mainItemsPerPage);
            const startIndex = (page - 1) * mainItemsPerPage;
            const endIndex = startIndex + mainItemsPerPage;
            const paginatedData = data.slice(startIndex, endIndex);

            paginatedData.forEach((row, i) => {
                const index = startIndex + i;
                const tr = document.createElement('tr');
                tr.innerHTML = `
                        <td class="text-center text-gray-500 font-medium">${index + 1}</td>
                        <td class="font-medium text-gray-900">${row.customer_name}</td>
                        <td class="text-center font-bold" style="color: #16a34a;">${row.visit_count}</td>
                        <td class="text-right text-gray-600">${formatMoney(row.total_sales)}</td>
                        <td class="text-right text-gray-600">${formatMoney(row.total_returns)}</td>
                        <td class="text-right text-gray-600">${formatMoney(row.cash_income)}</td>
                        <td class="text-right font-bold" style="color: #dc2626;">${formatMoney(row.outstanding_amount)}</td>
                        <td class="text-right text-gray-600">${formatMoney(row.total_credit)}</td>
                        <td class="text-center">
                            <button type="button" onclick="viewCustomerDetails(${index})" class="action-btn action-btn-view">
                                <i class="bi bi-eye"></i> View
                            </button>
                        </td>
                    `;
                tbody.appendChild(tr);
            });

            // Update footer totals
            document.getElementById('ft_visit_count').innerText = sumVisits;
            document.getElementById('ft_total_sales').innerText = formatMoney(sumSales);
            document.getElementById('ft_total_returns').innerText = formatMoney(sumReturns);
            document.getElementById('ft_cash_income').innerText = formatMoney(sumIncome);
            document.getElementById('ft_total_credit').innerText = formatMoney(sumCredit);
            document.getElementById('ft_outstanding').innerText = formatMoney(sumOutstanding);

            // Render pagination buttons
            const paginationContainer = document.getElementById('mainTablePagination');
            if (paginationContainer) {
                let btnHtml = '';
                if (totalPages > 1) {
                    for (let i = 1; i <= totalPages; i++) {
                        const activeClass = i === page
                            ? 'bg-indigo-600 text-white border-indigo-600'
                            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
                        btnHtml += `<button type="button" onclick="renderTable(${i})" class="px-3 py-1 border text-sm font-medium rounded transition-colors ${activeClass}">${i}</button>`;
                    }
                }
                paginationContainer.innerHTML = btnHtml;
            }
        }

        async function viewCustomerDetails(index) {
            const row = currentReportData[index];
            if (!row) return;

            document.getElementById('modalCustomerName').innerText = row.customer_name;
            document.getElementById('modalCustomerPhone').innerText = row.phone;
            document.getElementById('modalCustomerAddress').innerText = row.address;
            document.getElementById('modalCustomerType').innerText = row.customer_name;
            document.getElementById('modalPaymentTerms').innerText = row.payment_terms;
            document.getElementById('modalCreditLimit').innerText = formatMoney(row.credit_limit);
            document.getElementById('modalVisitBadge').innerText = 'Visits: ' + row.visit_count;

            document.getElementById('modalTotalSales').innerText = formatMoney(row.total_sales);
            document.getElementById('modalTotalReturns').innerText = formatMoney(row.total_returns);
            document.getElementById('modalCashIncome').innerText = formatMoney(row.cash_income);
            document.getElementById('modalTotalCredit').innerText = formatMoney(row.total_credit);
            document.getElementById('modalOutstanding').innerText = formatMoney(row.outstanding_amount);

            // Show loading in tables
            document.getElementById('modalSalesProducts').innerHTML = '<tr><td colspan="4" class="text-center text-gray-500 py-4"><div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-600"></div> Loading...</td></tr>';
            document.getElementById('modalReturnProducts').innerHTML = '<tr><td colspan="4" class="text-center text-gray-500 py-4"><div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-rose-600"></div> Loading...</td></tr>';
            document.getElementById('salesPagination').innerHTML = '';
            document.getElementById('returnsPagination').innerHTML = '';

            document.getElementById('detailsModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            try {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                
                const response = await fetch("{{ route('reports.agentShopSales.customerDetails') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        business_id: row.id || row.business_id,
                        start_date: startDate,
                        end_date: endDate
                    })
                });

                const res = await response.json();
                if (res.success) {
                    currentSalesProducts = res.sales_products || [];
                    currentReturnProducts = res.return_products || [];

                    document.getElementById('searchSales').value = '';
                    document.getElementById('searchReturns').value = '';
                    salesSearch = '';
                    returnsSearch = '';

                    renderPaginatedTable('sales', 1);
                    renderPaginatedTable('returns', 1);
                } else {
                    Swal.fire('Error', 'Failed to fetch customer details.', 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'An error occurred while fetching details.', 'error');
            }
        }

        function handleSearch(type) {
            if (type === 'sales') {
                salesSearch = document.getElementById('searchSales').value.toLowerCase();
                renderPaginatedTable('sales', 1);
            } else {
                returnsSearch = document.getElementById('searchReturns').value.toLowerCase();
                renderPaginatedTable('returns', 1);
            }
        }

        function renderPaginatedTable(type, page) {
            const isSales = type === 'sales';
            const rawData = isSales ? currentSalesProducts : currentReturnProducts;
            const searchStr = isSales ? salesSearch : returnsSearch;
            const tbody = document.getElementById(isSales ? 'modalSalesProducts' : 'modalReturnProducts');
            const paginationContainer = document.getElementById(isSales ? 'salesPagination' : 'returnsPagination');

            // Filter
            let filteredData = rawData.filter(p => p.name.toLowerCase().includes(searchStr));

            // Totals
            let qtyTotal = 0;
            let amountTotal = 0;
            filteredData.forEach(p => {
                qtyTotal += Math.round(p.quantity);
                amountTotal += Number(p.total_price);
            });

            // Update Footers
            if (isSales) {
                document.getElementById('modalSalesQtyTotal').innerText = qtyTotal;
                document.getElementById('modalSalesAmountTotal').innerText = formatMoney(amountTotal);
                salesPage = page;
            } else {
                document.getElementById('modalReturnsQtyTotal').innerText = qtyTotal;
                document.getElementById('modalReturnsAmountTotal').innerText = formatMoney(amountTotal);
                returnsPage = page;
            }

            // Pagination Logic
            const totalPages = Math.ceil(filteredData.length / itemsPerPage) || 1;
            if (page > totalPages) page = totalPages;
            if (page < 1) page = 1;

            const startIdx = (page - 1) * itemsPerPage;
            const pagedData = filteredData.slice(startIdx, startIdx + itemsPerPage);

            tbody.innerHTML = '';
            if (pagedData.length > 0) {
                pagedData.forEach((p, idx) => {
                    const rowNum = startIdx + idx + 1;
                    tbody.innerHTML += `
                            <tr class="product-row">
                                <td class="text-center px-4 text-gray-500 font-medium">${rowNum}</td>
                                <td class="font-medium px-4 product-name">${p.name}</td>
                                <td class="text-center ${!isSales ? 'text-rose-600' : ''} px-4 qty-val">${Math.round(p.quantity)}</td>
                                <td class="text-right font-medium ${!isSales ? 'text-rose-600' : ''} px-4 amount-val" data-val="${p.total_price}">${formatMoney(p.total_price)}</td>
                            </tr>
                        `;
                });
            } else {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center text-gray-400 py-4">No ${isSales ? 'sales' : 'return'} items</td></tr>`;
            }

            // Render Pagination Buttons
            let btnHtml = '';
            if (totalPages > 1) {
                const colorTheme = isSales ? 'indigo' : 'rose';
                for (let i = 1; i <= totalPages; i++) {
                    const activeClass = i === page
                        ? `bg-${colorTheme}-600 text-white border-${colorTheme}-600`
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50';
                    btnHtml += `<button type="button" onclick="renderPaginatedTable('${type}', ${i})" class="px-2 py-1 border text-xs font-medium rounded transition-colors ${activeClass}">${i}</button>`;
                }
            }
            paginationContainer.innerHTML = btnHtml;
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function exportToExcel() {
            if (currentReportData.length === 0) {
                Swal.fire('Info', 'No data to export.', 'info');
                return;
            }
            const agentId = document.getElementById('agent_id').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            
            const url = `{{ route('reports.agentShopSales.export') }}?type=excel&agent_id=${agentId}&start_date=${startDate}&end_date=${endDate}`;
            window.location.href = url;
        }

        function exportToPDF() {
            if (currentReportData.length === 0) {
                Swal.fire('Info', 'No data to export.', 'info');
                return;
            }
            const agentId = document.getElementById('agent_id').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            
            const url = `{{ route('reports.agentShopSales.export') }}?type=pdf&agent_id=${agentId}&start_date=${startDate}&end_date=${endDate}`;
            window.location.href = url;
        }

        // Set default dates to today on load
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').value = today;
            document.getElementById('end_date').value = today;
        });
    </script>
@endsection