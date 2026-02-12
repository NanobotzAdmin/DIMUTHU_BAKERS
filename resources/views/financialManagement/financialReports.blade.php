@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-[1600px] mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-900">
                    <i class="bi bi-bar-chart-fill text-amber-500"></i>
                    Financial Reports
                </h1>
                <p class="text-gray-600 mt-1">
                    Comprehensive financial reporting and analysis
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div
                    class="flex items-center gap-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-lg p-1 shadow-sm">
                    <i class="bi bi-calendar px-2 text-gray-400"></i>
                    <input type="date" id="start-date" class="border-0 focus:ring-0 text-sm p-1 rounded"
                        onchange="financialReportsManager.updateReports()">
                    <span class="text-gray-400">to</span>
                    <input type="date" id="end-date" class="border-0 focus:ring-0 text-sm p-1 rounded"
                        onchange="financialReportsManager.updateReports()">
                </div>
                <button onclick="financialReportsManager.exportReport()"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 flex items-center gap-2 text-sm font-medium shadow-sm transition-all hover:shadow">
                    <i class="bi bi-download"></i> Export PDF
                </button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="w-full">
            <div class="flex gap-1 bg-gray-100 p-1 rounded-lg w-max mb-6">
                <button onclick="financialReportsManager.switchTab('cash-flow')" id="tab-btn-cash-flow"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all bg-white text-gray-900 shadow-sm flex items-center gap-2">
                    <i class="bi bi-graph-up-arrow"></i> Cash Flow Statement
                </button>
                <button onclick="financialReportsManager.switchTab('aged-ar')" id="tab-btn-aged-ar"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all text-gray-600 hover:text-gray-900 hover:bg-white/50 flex items-center gap-2">
                    <i class="bi bi-people"></i> Aged Receivables (AR)
                </button>
                <button onclick="financialReportsManager.switchTab('aged-ap')" id="tab-btn-aged-ap"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all text-gray-600 hover:text-gray-900 hover:bg-white/50 flex items-center gap-2">
                    <i class="bi bi-file-earmark-text"></i> Aged Payables (AP)
                </button>
            </div>

            <!-- Content -->

            <!-- Cash Flow Tab -->
            <div id="tab-content-cash-flow" class="space-y-6 animate-fade-in">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">Statement of Cash Flows (Indirect Method)</h2>
                        <p class="text-sm text-gray-500" id="cash-flow-period-label">Period: -</p>
                    </div>
                    <div class="p-6 space-y-8">

                        <!-- Operating -->
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4 text-lg">Cash Flows from Operating Activities</h3>
                            <div class="space-y-3 pl-4 border-l-2 border-gray-100">
                                <div
                                    class="flex justify-between py-1 group hover:bg-gray-50 rounded px-2 -ml-2 transition-colors">
                                    <span class="text-gray-700 font-medium">Net Profit</span>
                                    <span class="font-mono font-medium" id="cf-net-profit">-</span>
                                </div>

                                <div class="pl-4 space-y-2">
                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mt-2">
                                        Adjustments for non-cash items</div>
                                    <div
                                        class="flex justify-between py-1 text-sm group hover:bg-gray-50 rounded px-2 -ml-2">
                                        <span class="text-gray-600">Depreciation and amortization</span>
                                        <span class="font-mono text-gray-700" id="cf-depreciation">-</span>
                                    </div>

                                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mt-2">Changes in
                                        working capital</div>
                                    <div
                                        class="flex justify-between py-1 text-sm group hover:bg-gray-50 rounded px-2 -ml-2">
                                        <span class="text-gray-600"><span id="cf-ar-label">Change</span> in Accounts
                                            Receivable</span>
                                        <span class="font-mono text-gray-700" id="cf-change-ar">-</span>
                                    </div>
                                    <div
                                        class="flex justify-between py-1 text-sm group hover:bg-gray-50 rounded px-2 -ml-2">
                                        <span class="text-gray-600"><span id="cf-inv-label">Change</span> in
                                            Inventory</span>
                                        <span class="font-mono text-gray-700" id="cf-change-inv">-</span>
                                    </div>
                                    <div
                                        class="flex justify-between py-1 text-sm group hover:bg-gray-50 rounded px-2 -ml-2">
                                        <span class="text-gray-600"><span id="cf-ap-label">Change</span> in Accounts
                                            Payable</span>
                                        <span class="font-mono text-gray-700" id="cf-change-ap">-</span>
                                    </div>
                                </div>

                                <div
                                    class="flex justify-between py-3 border-t border-gray-200 mt-2 bg-gray-50 px-3 rounded-lg">
                                    <span class="text-gray-900 font-bold">Net Cash from Operating Activities</span>
                                    <span class="font-mono font-bold" id="cf-net-operating">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Investing -->
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4 text-lg">Cash Flows from Investing Activities</h3>
                            <div class="space-y-3 pl-4 border-l-2 border-gray-100">
                                <div class="flex justify-between py-1 group hover:bg-gray-50 rounded px-2 -ml-2">
                                    <span class="text-gray-600"><span id="cf-fa-label">Purchase/Sale</span> of fixed
                                        assets</span>
                                    <span class="font-mono text-gray-700" id="cf-change-fa">-</span>
                                </div>
                                <div
                                    class="flex justify-between py-3 border-t border-gray-200 mt-2 bg-gray-50 px-3 rounded-lg">
                                    <span class="text-gray-900 font-bold">Net Cash from Investing Activities</span>
                                    <span class="font-mono font-bold" id="cf-net-investing">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Financing -->
                        <div>
                            <h3 class="font-bold text-gray-900 mb-4 text-lg">Cash Flows from Financing Activities</h3>
                            <div class="space-y-3 pl-4 border-l-2 border-gray-100">
                                <div class="flex justify-between py-1 group hover:bg-gray-50 rounded px-2 -ml-2">
                                    <span class="text-gray-600"><span id="cf-ltl-label">Proceeds/Payment</span> of long-term
                                        debt</span>
                                    <span class="font-mono text-gray-700" id="cf-change-ltl">-</span>
                                </div>
                                <div class="flex justify-between py-1 group hover:bg-gray-50 rounded px-2 -ml-2">
                                    <span class="text-gray-600"><span id="cf-equity-label">Capital/Distribution</span>
                                        equity</span>
                                    <span class="font-mono text-gray-700" id="cf-change-equity">-</span>
                                </div>
                                <div
                                    class="flex justify-between py-3 border-t border-gray-200 mt-2 bg-gray-50 px-3 rounded-lg">
                                    <span class="text-gray-900 font-bold">Net Cash from Financing Activities</span>
                                    <span class="font-mono font-bold" id="cf-net-financing">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="bg-slate-50 border-t border-gray-200 p-6">
                        <div class="space-y-3 max-w-2xl ml-auto">
                            <div class="flex justify-between py-2 text-lg font-bold border-b border-gray-300">
                                <span class="text-gray-900">Net Increase/(Decrease) in Cash</span>
                                <span class="font-mono" id="cf-net-change">-</span>
                            </div>
                            <div class="flex justify-between py-1 text-gray-600">
                                <span>Cash at beginning of period</span>
                                <span class="font-mono" id="cf-opening-cash">-</span>
                            </div>
                            <div
                                class="flex justify-between py-3 bg-white border border-gray-200 rounded-lg px-4 shadow-sm items-center mt-4">
                                <span class="text-gray-900 font-bold text-lg">Cash at end of period</span>
                                <span class="font-mono text-amber-600 font-bold text-xl" id="cf-closing-cash">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aged AR Tab -->
            <div id="tab-content-aged-ar" class="hidden space-y-6 animate-fade-in">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-xl border border-green-100 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Current (0-30 days)</div>
                        <div class="text-2xl font-bold text-green-600" id="ar-sum-30">-</div>
                        <div class="text-xs text-gray-400 mt-2" id="ar-pct-30">% of total</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-yellow-100 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">31-60 days</div>
                        <div class="text-2xl font-bold text-yellow-600" id="ar-sum-60">-</div>
                        <div class="text-xs text-gray-400 mt-2" id="ar-pct-60">% of total</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-orange-100 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">61-90 days</div>
                        <div class="text-2xl font-bold text-orange-600" id="ar-sum-90">-</div>
                        <div class="text-xs text-gray-400 mt-2" id="ar-pct-90">% of total</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-red-100 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Over 90 days</div>
                        <div class="text-2xl font-bold text-red-600" id="ar-sum-90p">-</div>
                        <div class="text-xs text-gray-400 mt-2" id="ar-pct-90p">% of total</div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900">Aged Receivables Detail</h3>
                        <p class="text-sm text-gray-500">Outstanding customer invoices</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead
                                class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3">Invoice #</th>
                                    <th class="px-6 py-3">Customer</th>
                                    <th class="px-6 py-3">Invoice Date</th>
                                    <th class="px-6 py-3">Due Date</th>
                                    <th class="px-6 py-3 text-right">Amount</th>
                                    <th class="px-6 py-3 text-center">Days Outstanding</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody id="ar-table-body">
                                <!-- Dynamic Rows -->
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-200 font-bold">
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-gray-900">Total Accounts Receivable</td>
                                    <td class="px-6 py-3 text-right font-mono text-amber-600" id="ar-total-amount">-</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Aged AP Tab -->
            <div id="tab-content-aged-ap" class="hidden space-y-6 animate-fade-in">
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-xl border border-green-100 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Current (0-30 days)</div>
                        <div class="text-2xl font-bold text-green-600" id="ap-sum-30">-</div>
                        <div class="text-xs text-gray-400 mt-2" id="ap-pct-30">% of total</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-yellow-100 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">31-60 days</div>
                        <div class="text-2xl font-bold text-yellow-600" id="ap-sum-60">-</div>
                        <div class="text-xs text-gray-400 mt-2" id="ap-pct-60">% of total</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-orange-100 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">61-90 days</div>
                        <div class="text-2xl font-bold text-orange-600" id="ap-sum-90">-</div>
                        <div class="text-xs text-gray-400 mt-2" id="ap-pct-90">% of total</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-red-100 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Over 90 days</div>
                        <div class="text-2xl font-bold text-red-600" id="ap-sum-90p">-</div>
                        <div class="text-xs text-gray-400 mt-2" id="ap-pct-90p">% of total</div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-bold text-gray-900">Aged Payables Detail</h3>
                        <p class="text-sm text-gray-500">Outstanding vendor bills</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead
                                class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3">Bill #</th>
                                    <th class="px-6 py-3">Vendor</th>
                                    <th class="px-6 py-3">Bill Date</th>
                                    <th class="px-6 py-3">Due Date</th>
                                    <th class="px-6 py-3 text-right">Amount</th>
                                    <th class="px-6 py-3 text-center">Days Outstanding</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody id="ap-table-body">
                                <!-- Dynamic Rows -->
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-200 font-bold">
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-gray-900">Total Accounts Payable</td>
                                    <td class="px-6 py-3 text-right font-mono text-amber-600" id="ap-total-amount">-</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const financialReportsManager = {
            data: {
                accounts: @json($glAccounts),
                journalEntries: @json($journalEntries)
            },
            state: {
                activeTab: 'cash-flow',
                startDate: '2024-01-01',
                endDate: new Date().toISOString().split('T')[0]
            },

            init() {
                // Initialize Dates
                document.getElementById('start-date').value = this.state.startDate;
                document.getElementById('end-date').value = this.state.endDate;

                this.updateReports();
                console.log('Financial Reports Initialized', this.data);
            },

            updateReports() {
                this.state.startDate = document.getElementById('start-date').value;
                this.state.endDate = document.getElementById('end-date').value;

                // Update Labels
                document.getElementById('cash-flow-period-label').innerText = `For the period ${this.state.startDate} to ${this.state.endDate}`;

                this.calculateCashFlow();
                this.calculateAgedAR();
                this.calculateAgedAP();
            },

            calculateCashFlow() {
                // 1. Net Profit
                const expense = this.data.accounts.filter(a => a.type === 'expense').reduce((sum, a) => sum + Math.abs(Number(a.currentBalance)), 0);
                const revenue = this.data.accounts.filter(a => a.type === 'revenue').reduce((sum, a) => sum + Math.abs(Number(a.currentBalance)), 0);
                const netProfit = revenue - expense;

                // 2. Operating Activities
                const depreciation = this.getAccountBalance('6400'); // Exp is positive 

                // Working Capital Changes (Simplified: Current - Opening)
                const changeAR = this.getChangeInBalance('1200'); // Increase is Bad for Cash
                const changeInv = this.getChangeInBalanceGroups(['1400', '1410']);
                const changeAP = this.getChangeInBalanceGroups(['2000', '2100']); // Increase is Good for Cash

                // Assuming positive change in asset = negative cash flow
                // Assuming positive change in liability = positive cash flow
                const operatingCashFlow = netProfit + depreciation - changeAR - changeInv + changeAP;

                // 3. Investing
                const changeFA = this.getChangeInBalanceGroups(['1600']); // Purchases
                const investingCashFlow = -changeFA;

                // 4. Financing
                const changeLTL = this.getChangeInBalance('2500');
                const changeEquity = this.getChangeInBalance('3000');
                const financingCashFlow = changeLTL + changeEquity;

                // Net Change
                const netCashChange = operatingCashFlow + investingCashFlow + financingCashFlow;

                // Cash Balances
                const cashAccounts = this.data.accounts.filter(a => a.code.startsWith('10'));
                const openingCash = cashAccounts.reduce((sum, a) => sum + Number(a.openingBalance), 0);
                const closingCash = cashAccounts.reduce((sum, a) => sum + Number(a.currentBalance), 0);

                // --- Render ---
                this.setVal('cf-net-profit', netProfit);
                this.setVal('cf-depreciation', depreciation);

                this.setChangeLabel('cf-ar-label', changeAR, 'Increase', 'Decrease');
                this.setVal('cf-change-ar', Math.abs(changeAR), true); // show negative if outflow? Logic is intricate, keeping simpler
                // Proper Cash Flow convention: (Increase)
                document.getElementById('cf-change-ar').innerText = changeAR >= 0 ? `(${this.fmt(Math.abs(changeAR))})` : this.fmt(Math.abs(changeAR));

                this.setChangeLabel('cf-inv-label', changeInv, 'Increase', 'Decrease');
                document.getElementById('cf-change-inv').innerText = changeInv >= 0 ? `(${this.fmt(Math.abs(changeInv))})` : this.fmt(Math.abs(changeInv));

                this.setChangeLabel('cf-ap-label', changeAP, 'Increase', 'Decrease');
                this.setVal('cf-change-ap', Math.abs(changeAP));

                this.setVal('cf-net-operating', operatingCashFlow, false, true); // Colorize

                // Investing Render
                this.setChangeLabel('cf-fa-label', changeFA, 'Purchase', 'Sale');
                document.getElementById('cf-change-fa').innerText = `(${this.fmt(Math.abs(changeFA))})`; // Typically purchase
                this.setVal('cf-net-investing', investingCashFlow, false, true);

                // Financing Render
                this.setChangeLabel('cf-ltl-label', changeLTL, 'Proceeds from', 'Repayment of');
                this.setVal('cf-change-ltl', Math.abs(changeLTL));
                this.setChangeLabel('cf-equity-label', changeEquity, 'Capital Contributed', 'Distributions to');
                this.setVal('cf-change-equity', Math.abs(changeEquity));
                this.setVal('cf-net-financing', financingCashFlow, false, true);

                // Summary
                this.setVal('cf-net-change', netCashChange, false, true);
                this.setVal('cf-opening-cash', openingCash);
                this.setVal('cf-closing-cash', closingCash);

            },

            calculateAgedAR() {
                const today = new Date();
                const invoices = [];

                // Filter Sales Entries
                const arEntries = this.data.journalEntries.filter(e => e.type === 'sales' && e.status === 'posted');

                arEntries.forEach(e => {
                    const line = e.lines.find(l => l.accountCode === '1200');
                    if (!line) return;

                    const date = new Date(e.entryDate);
                    const days = Math.floor((today - date) / (1000 * 60 * 60 * 24));
                    let bucket = '90+';
                    if (days <= 30) bucket = '0-30';
                    else if (days <= 60) bucket = '31-60';
                    else if (days <= 90) bucket = '61-90';

                    invoices.push({
                        id: e.id,
                        number: e.entryNumber,
                        customer: e.description.split(' - ')[0],
                        date: e.entryDate,
                        due: this.addDays(date, 30).toISOString().split('T')[0],
                        amount: line.debit,
                        days: days,
                        bucket: bucket
                    });
                });

                // Calculate Totals per bucket
                const totals = {
                    '0-30': this.sumBucket(invoices, '0-30'),
                    '31-60': this.sumBucket(invoices, '31-60'),
                    '61-90': this.sumBucket(invoices, '61-90'),
                    '90+': this.sumBucket(invoices, '90+')
                };
                const grandTotal = Object.values(totals).reduce((a, b) => a + b, 0);

                // Render Summary Cards
                this.renderBucketCard('ar', '30', totals['0-30'], grandTotal);
                this.renderBucketCard('ar', '60', totals['31-60'], grandTotal);
                this.renderBucketCard('ar', '90', totals['61-90'], grandTotal);
                this.renderBucketCard('ar', '90p', totals['90+'], grandTotal);

                document.getElementById('ar-total-amount').innerText = this.fmt(grandTotal);

                // Render Table
                const tbody = document.getElementById('ar-table-body');
                if (invoices.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500">No outstanding receivables</td></tr>';
                } else {
                    tbody.innerHTML = invoices.sort((a, b) => b.days - a.days).map(i => `
                            <tr class="hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                <td class="px-6 py-3 font-mono text-gray-900">${i.number}</td>
                                <td class="px-6 py-3 text-gray-700 font-medium">${i.customer}</td>
                                <td class="px-6 py-3 text-gray-500">${i.date}</td>
                                <td class="px-6 py-3 text-gray-500">${i.due}</td>
                                <td class="px-6 py-3 text-right font-mono text-gray-900">${this.fmt(i.amount)}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="${this.getAgeColor(i.days)} font-medium">${i.days} days</span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    ${this.getBucketBadge(i.bucket)}
                                </td>
                            </tr>
                        `).join('');
                }
            },

            calculateAgedAP() {
                const today = new Date();
                const bills = [];

                const apEntries = this.data.journalEntries.filter(e => e.type === 'purchase' || (e.type === 'general' && e.lines.some(l => l.accountCode.startsWith('2'))));

                apEntries.forEach(e => {
                    const line = e.lines.find(l => l.accountCode.startsWith('20') || l.accountCode.startsWith('21')); // AP Line
                    if (!line || line.credit === 0) return;

                    const date = new Date(e.entryDate);
                    const days = Math.floor((today - date) / (1000 * 60 * 60 * 24));
                    let bucket = '90+';
                    if (days <= 30) bucket = '0-30';
                    else if (days <= 60) bucket = '31-60';
                    else if (days <= 90) bucket = '61-90';

                    bills.push({
                        id: e.id,
                        number: e.entryNumber,
                        vendor: e.description.split(' - ')[0],
                        date: e.entryDate,
                        due: this.addDays(date, 30).toISOString().split('T')[0],
                        amount: line.credit,
                        days: days,
                        bucket: bucket
                    });
                });

                // Totals
                const totals = {
                    '0-30': this.sumBucket(bills, '0-30'),
                    '31-60': this.sumBucket(bills, '31-60'),
                    '61-90': this.sumBucket(bills, '61-90'),
                    '90+': this.sumBucket(bills, '90+')
                };
                const grandTotal = Object.values(totals).reduce((a, b) => a + b, 0);

                // Summary Cards
                this.renderBucketCard('ap', '30', totals['0-30'], grandTotal);
                this.renderBucketCard('ap', '60', totals['31-60'], grandTotal);
                this.renderBucketCard('ap', '90', totals['61-90'], grandTotal);
                this.renderBucketCard('ap', '90p', totals['90+'], grandTotal);

                document.getElementById('ap-total-amount').innerText = this.fmt(grandTotal);

                // Table
                const tbody = document.getElementById('ap-table-body');
                if (bills.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500">No outstanding payables</td></tr>';
                } else {
                    tbody.innerHTML = bills.sort((a, b) => b.days - a.days).map(b => `
                            <tr class="hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                <td class="px-6 py-3 font-mono text-gray-900">${b.number}</td>
                                <td class="px-6 py-3 text-gray-700 font-medium">${b.vendor}</td>
                                <td class="px-6 py-3 text-gray-500">${b.date}</td>
                                <td class="px-6 py-3 text-gray-500">${b.due}</td>
                                <td class="px-6 py-3 text-right font-mono text-gray-900">${this.fmt(b.amount)}</td>
                                <td class="px-6 py-3 text-center">
                                    <span class="${this.getAgeColor(b.days)} font-medium">${b.days} days</span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    ${this.getBucketBadge(b.bucket)}
                                </td>
                            </tr>
                        `).join('');
                }
            },

            // --- Helpers ---
            getAccountBalance(code) {
                const acc = this.data.accounts.find(a => a.code === code);
                return acc ? Number(acc.currentBalance) : 0;
            },

            getChangeInBalance(code) {
                const acc = this.data.accounts.find(a => a.code === code);
                return acc ? (Number(acc.currentBalance) - Number(acc.openingBalance)) : 0;
            },

            getChangeInBalanceGroups(codes) {
                return codes.reduce((sum, code) => sum + this.getChangeInBalance(code), 0);
            },

            setVal(id, val, isParentheses = false, colorize = false) {
                const el = document.getElementById(id);
                if (!el) return;

                let text = this.fmt(Math.abs(val));
                if (isParentheses || val < 0) text = `(${text})`;

                el.innerText = text;

                if (colorize) {
                    el.className = `font-mono font-bold ${val >= 0 ? 'text-green-600' : 'text-red-600'}`;
                }
            },

            setChangeLabel(id, val, posText, negText) {
                const el = document.getElementById(id);
                if (el) el.innerText = val >= 0 ? posText : negText;
            },

            sumBucket(data, bucket) {
                return data.filter(d => d.bucket === bucket).reduce((sum, d) => sum + d.amount, 0);
            },

            renderBucketCard(prefix, suffix, amount, total) {
                document.getElementById(`${prefix}-sum-${suffix}`).innerText = this.fmt(amount);
                const pct = total > 0 ? Math.round((amount / total) * 100) : 0;
                document.getElementById(`${prefix}-pct-${suffix}`).innerText = `${pct}% of total`;
            },

            getAgeColor(days) {
                if (days <= 30) return 'text-green-600';
                if (days <= 60) return 'text-yellow-600';
                if (days <= 90) return 'text-orange-600';
                return 'text-red-600';
            },

            getBucketBadge(bucket) {
                const colors = {
                    '0-30': 'bg-green-100 text-green-800 border-green-200',
                    '31-60': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                    '61-90': 'bg-orange-100 text-orange-800 border-orange-200',
                    '90+': 'bg-red-100 text-red-800 border-red-200'
                };
                return `<span class="px-2.5 py-0.5 rounded border text-xs font-medium ${colors[bucket]}">${bucket} days</span>`;
            },

            addDays(date, days) {
                const result = new Date(date);
                result.setDate(result.getDate() + days);
                return result;
            },

            fmt(num) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(num);
            },

            switchTab(id) {
                this.state.activeTab = id;
                ['cash-flow', 'aged-ar', 'aged-ap'].forEach(t => {
                    const btn = document.getElementById(`tab-btn-${t}`);
                    const content = document.getElementById(`tab-content-${t}`);
                    if (t === id) {
                        btn.className = "px-4 py-2 rounded-md text-sm font-medium transition-all bg-white text-gray-900 shadow-sm flex items-center gap-2";
                        content.classList.remove('hidden');
                    } else {
                        btn.className = "px-4 py-2 rounded-md text-sm font-medium transition-all text-gray-600 hover:text-gray-900 hover:bg-white/50 flex items-center gap-2";
                        content.classList.add('hidden');
                    }
                });
            },

            exportReport() {
                Swal.fire({
                    icon: 'success',
                    title: 'Export Started',
                    text: 'Your PDF report is generating...',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            financialReportsManager.init();
        });
    </script>
@endsection