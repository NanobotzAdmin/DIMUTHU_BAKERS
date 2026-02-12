@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-[1600px] mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-900">
                    <i class="bi bi-scale text-amber-500"></i>
                    Trial Balance & Reports
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    View trial balance, income statement, and balance sheet
                </p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="w-full">
            <div class="flex gap-1 bg-gray-100 p-1 rounded-lg w-max mb-6">
                <button onclick="trialBalanceManager.switchTab('trial-balance')" id="tab-btn-trial-balance"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all bg-white text-gray-900 shadow-sm flex items-center gap-2">
                    <i class="bi bi-scale"></i> Trial Balance
                </button>
                <button onclick="trialBalanceManager.switchTab('income-statement')" id="tab-btn-income-statement"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all text-gray-600 hover:text-gray-900 hover:bg-white/50 flex items-center gap-2">
                    <i class="bi bi-graph-up-arrow"></i> Income Statement
                </button>
                <button onclick="trialBalanceManager.switchTab('balance-sheet')" id="tab-btn-balance-sheet"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all text-gray-600 hover:text-gray-900 hover:bg-white/50 flex items-center gap-2">
                    <i class="bi bi-building"></i> Balance Sheet
                </button>
            </div>

            <!-- Content -->

            <!-- Trial Balance Tab -->
            <div id="tab-content-trial-balance" class="space-y-6 animate-fade-in">
                <!-- Filters -->
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-calendar text-gray-400"></i>
                            <label class="text-sm font-medium text-gray-700">As of Date:</label>
                            <input type="date" id="tb-as-of-date"
                                class="border p-2 bg-gray-50 border-gray-300 rounded text-sm focus:ring-amber-500 focus:border-amber-500"
                                onchange="trialBalanceManager.renderTrialBalance()">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="tb-show-zero" class="rounded text-amber-600 focus:ring-amber-500"
                                onchange="trialBalanceManager.renderTrialBalance()">
                            <label for="tb-show-zero" class="text-sm text-gray-700">Show zero balances</label>
                        </div>
                    </div>
                    <button onclick="trialBalanceManager.exportCSV('trial-balance')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                        <i class="bi bi-download"></i> Export CSV
                    </button>
                </div>

                <!-- Balance Status -->
                <div id="tb-status-card" class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div id="tb-status-icon-bg"
                                class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                <i id="tb-status-icon" class="bi bi-check-lg text-xl text-green-600"></i>
                            </div>
                            <div>
                                <h3 id="tb-status-title" class="text-lg font-bold text-green-900">Trial Balance is Balanced
                                    ✓</h3>
                                <p id="tb-status-msg" class="text-sm text-green-700">All debits equal credits. Your books
                                    are in balance.</p>
                            </div>
                        </div>
                        <div class="flex gap-8">
                            <div class="text-right">
                                <p class="text-sm text-gray-600 mb-1">Total Debits</p>
                                <p id="tb-total-debits" class="text-2xl font-bold text-green-600">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600 mb-1">Total Credits</p>
                                <p id="tb-total-credits" class="text-2xl font-bold text-red-600">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">Trial Balance</h2>
                        <p class="text-sm text-gray-500" id="tb-date-label">As of -</p>
                    </div>
                    <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                        <table class="w-full text-sm text-left">
                            <thead
                                class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100 sticky top-0 z-10 w-full">
                                <tr>
                                    <th class="px-6 py-3 w-24">Code</th>
                                    <th class="px-6 py-3">Account Name</th>
                                    <th class="px-6 py-3 w-32">Type</th>
                                    <th class="px-6 py-3 text-right">Debit</th>
                                    <th class="px-6 py-3 text-right">Credit</th>
                                </tr>
                            </thead>
                            <tbody id="tb-table-body">
                                <!-- Dynamic Rows -->
                            </tbody>
                            <tfoot class="bg-gray-50 border-t-2 border-gray-200 font-bold sticky bottom-0 z-10">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-gray-900">TOTAL:</td>
                                    <td class="px-6 py-4 text-right font-mono text-green-600" id="tb-footer-debits">-</td>
                                    <td class="px-6 py-4 text-right font-mono text-red-600" id="tb-footer-credits">-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Income Statement Tab -->
            <div id="tab-content-income-statement" class="hidden space-y-6 animate-fade-in">
                <!-- Filters -->
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-calendar text-gray-400"></i>
                            <label class="text-sm font-medium text-gray-700">From:</label>
                            <input type="date" id="is-start-date" class="border p-2 bg-gray-50 border-gray-300 rounded text-sm w-36"
                                onchange="trialBalanceManager.renderIncomeStatement()">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">To:</label>
                            <input type="date" id="is-end-date" class="border p-2 bg-gray-50 border-gray-300 rounded text-sm w-36"
                                onchange="trialBalanceManager.renderIncomeStatement()">
                        </div>
                    </div>
                    <button onclick="trialBalanceManager.exportCSV('income-statement')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                        <i class="bi bi-download"></i> Export CSV
                    </button>
                </div>

                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Total Revenue</div>
                        <div class="text-2xl font-bold text-green-600" id="is-total-revenue">-</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Total Expenses</div>
                        <div class="text-2xl font-bold text-red-600" id="is-total-expenses">-</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Net Profit</div>
                        <div class="text-2xl font-bold text-gray-900" id="is-net-profit">-</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Profit Margin</div>
                        <div class="text-2xl font-bold text-blue-600" id="is-profit-margin">-</div>
                    </div>
                </div>

                <!-- Report Body -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">Income Statement</h2>
                        <p class="text-sm text-gray-500" id="is-period-label">Period: -</p>
                    </div>
                    <div class="p-8 space-y-8" id="is-body">
                        <!-- Dynamic Content -->
                    </div>
                </div>
            </div>

            <!-- Balance Sheet Tab -->
            <div id="tab-content-balance-sheet" class="hidden space-y-6 animate-fade-in">
                <!-- Filters -->
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-calendar text-gray-400"></i>
                            <label class="text-sm font-medium text-gray-700">As of Date:</label>
                            <input type="date" id="bs-as-of-date" class="border p-2 bg-gray-50 border-gray-300 rounded text-sm"
                                onchange="trialBalanceManager.renderBalanceSheet()">
                        </div>
                    </div>
                    <button onclick="trialBalanceManager.exportCSV('balance-sheet')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                        <i class="bi bi-download"></i> Export CSV
                    </button>
                </div>

                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Total Assets</div>
                        <div class="text-2xl font-bold text-blue-600" id="bs-total-assets">-</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Total Liabilities</div>
                        <div class="text-2xl font-bold text-red-600" id="bs-total-liabilities">-</div>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-sm text-gray-500 mb-1">Total Equity</div>
                        <div class="text-2xl font-bold text-purple-600" id="bs-total-equity">-</div>
                    </div>
                </div>

                <!-- Balance Indicator -->
                <div id="bs-status-card" class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div id="bs-status-icon-bg"
                                class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <i id="bs-status-icon" class="bi bi-building text-green-600"></i>
                            </div>
                            <div>
                                <h3 id="bs-status-title" class="text-lg font-bold text-green-900">Balance Sheet is Balanced
                                    ✓</h3>
                                <p id="bs-status-msg" class="text-sm text-green-700">Assets = Liabilities + Equity</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 mb-1">Liabilities + Equity</p>
                            <p id="bs-total-liab-equity" class="text-xl font-bold text-gray-900">-</p>
                        </div>
                    </div>
                </div>

                <!-- Report Body -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">Balance Sheet</h2>
                        <p class="text-sm text-gray-500" id="bs-date-label">As of -</p>
                    </div>
                    <div class="p-8 space-y-8" id="bs-body">
                        <!-- Dynamic Content -->
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
        const trialBalanceManager = {
            data: {
                accounts: @json($glAccounts)
            },
            state: {
                activeTab: 'trial-balance',
                asOfDate: new Date().toISOString().split('T')[0],
                startDate: '2024-01-01',
                endDate: new Date().toISOString().split('T')[0],
                showZero: false
            },

            init() {
                // Init Dates
                document.getElementById('tb-as-of-date').value = this.state.asOfDate;
                document.getElementById('bs-as-of-date').value = this.state.asOfDate;
                document.getElementById('is-start-date').value = this.state.startDate;
                document.getElementById('is-end-date').value = this.state.endDate;

                this.renderTrialBalance();
                this.renderIncomeStatement();
                this.renderBalanceSheet();
            },

            // --- Render Logic ---

            renderTrialBalance() {
                this.state.asOfDate = document.getElementById('tb-as-of-date').value;
                this.state.showZero = document.getElementById('tb-show-zero').checked;

                document.getElementById('tb-date-label').innerText = `As of ${this.formatDate(this.state.asOfDate)}`;

                let lines = this.data.accounts.map(acc => {
                    // Logic to determine Debit/Credit based on Type and Balance
                    // Assets/Expenses: Positive Balance = Debit
                    // Liab/Equity/Revenue: Positive Balance = Credit
                    // This implies the 'currentBalance' stored is natural sign?
                    // Let's assume currentBalance is Signed (Asset +, Liab/Eq -).

                    // Actually, based on previous mock data:
                    // Assets are positive. Liabilities are Positive numbers in mock but conceptually credit.
                    // Revenue is positive number but conceptually credit.
                    // Let's normalize for Trial Balance presentation.

                    let dr = 0;
                    let cr = 0;
                    const bal = parseFloat(acc.currentBalance);
                    const type = acc.type;

                    // Simple Rule for this Mock:
                    if (['asset', 'expense'].includes(type)) {
                        if (bal >= 0) dr = bal; else cr = Math.abs(bal);
                    } else {
                        if (bal >= 0) cr = bal; else dr = Math.abs(bal);
                    }

                    return { ...acc, debit: dr, credit: cr };
                });

                if (!this.state.showZero) {
                    lines = lines.filter(l => l.debit !== 0 || l.credit !== 0);
                }

                // Calculate Totals
                const totalDr = lines.reduce((sum, l) => sum + l.debit, 0);
                const totalCr = lines.reduce((sum, l) => sum + l.credit, 0);
                const diff = Math.abs(totalDr - totalCr);
                const isBalanced = diff < 0.01;

                // Update Status Info
                const statusCard = document.getElementById('tb-status-card');
                const statusIconBg = document.getElementById('tb-status-icon-bg');
                const statusIcon = document.getElementById('tb-status-icon');
                const statusTitle = document.getElementById('tb-status-title');
                const statusMsg = document.getElementById('tb-status-msg');

                if (isBalanced) {
                    statusCard.className = "bg-green-50 border border-green-200 rounded-xl p-6 transition-colors";
                    statusIconBg.className = "w-12 h-12 rounded-full bg-green-100 flex items-center justify-center";
                    statusIcon.className = "bi bi-check-lg text-xl text-green-600";
                    statusTitle.className = "text-lg font-bold text-green-900";
                    statusTitle.innerText = "Trial Balance is Balanced ✓";
                    statusMsg.className = "text-sm text-green-700";
                    statusMsg.innerText = "All debits equal credits. Your books are in balance.";
                } else {
                    statusCard.className = "bg-red-50 border border-red-200 rounded-xl p-6 transition-colors";
                    statusIconBg.className = "w-12 h-12 rounded-full bg-red-100 flex items-center justify-center";
                    statusIcon.className = "bi bi-exclamation-triangle text-xl text-red-600";
                    statusTitle.className = "text-lg font-bold text-red-900";
                    statusTitle.innerText = "Trial Balance is Out of Balance";
                    statusMsg.className = "text-sm text-red-700";
                    statusMsg.innerText = `Difference: ${this.fmt(diff)} - Please review journal entries.`;
                }

                document.getElementById('tb-total-debits').innerText = this.fmt(totalDr);
                document.getElementById('tb-total-credits').innerText = this.fmt(totalCr);
                document.getElementById('tb-footer-debits').innerText = this.fmt(totalDr);
                document.getElementById('tb-footer-credits').innerText = this.fmt(totalCr);

                // Render Table
                const tbody = document.getElementById('tb-table-body');
                if (lines.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-8 text-gray-500"><i class="bi bi-scale text-2xl mb-2 block"></i>No accounts with balances found</td></tr>';
                } else {
                    tbody.innerHTML = lines.map(line => `
                            <tr class="hover:bg-gray-50 border-b border-gray-100 group">
                                <td class="px-6 py-2 font-mono text-gray-700 group-hover:text-amber-600 font-medium">${line.code}</td>
                                <td class="px-6 py-2 text-gray-800">${line.name} <span class="text-xs text-gray-400 ml-2">(${this.formatStr(line.subCategory)})</span></td>
                                <td class="px-6 py-2">
                                    <span class="px-2 py-0.5 rounded text-xs border border-gray-200 bg-gray-50 text-gray-500 uppercase">${line.type}</span>
                                </td>
                                <td class="px-6 py-2 text-right font-mono text-gray-900">${line.debit > 0 ? this.fmt(line.debit) : '-'}</td>
                                <td class="px-6 py-2 text-right font-mono text-gray-900">${line.credit > 0 ? this.fmt(line.credit) : '-'}</td>
                            </tr>
                        `).join('');
                }
            },

            renderIncomeStatement() {
                this.state.startDate = document.getElementById('is-start-date').value;
                this.state.endDate = document.getElementById('is-end-date').value;
                document.getElementById('is-period-label').innerText = `Period: ${this.formatDate(this.state.startDate)} to ${this.formatDate(this.state.endDate)}`;

                // Filter Data
                const revenue = this.data.accounts.filter(a => a.type === 'revenue');
                const expense = this.data.accounts.filter(a => a.type === 'expense');

                const totalRev = revenue.reduce((sum, a) => sum + parseFloat(a.currentBalance), 0);
                const totalExp = expense.reduce((sum, a) => sum + parseFloat(a.currentBalance), 0);
                const netProfit = totalRev - totalExp;
                const margin = totalRev > 0 ? (netProfit / totalRev) * 100 : 0;

                // Update Overview
                document.getElementById('is-total-revenue').innerText = this.fmt(totalRev);
                document.getElementById('is-total-expenses').innerText = this.fmt(totalExp);
                const netEl = document.getElementById('is-net-profit');
                netEl.innerText = this.fmt(netProfit);
                netEl.className = `text-2xl font-bold ${netProfit >= 0 ? 'text-green-600' : 'text-red-600'}`;

                const marginEl = document.getElementById('is-profit-margin');
                marginEl.innerText = `${margin.toFixed(1)}%`;
                marginEl.className = `text-2xl font-bold ${margin >= 0 ? 'text-green-600' : 'text-red-600'}`;

                // Render Details grouped by SubCategory
                const renderSection = (title, accounts, total, colorClass) => {
                    const grouped = this.groupBy(accounts, 'subCategory');
                    let html = `<div class="mb-8">
                            <h3 class="text-lg font-bold mb-4 pb-2 border-b-2 ${colorClass.border} ${colorClass.text}">${title}</h3>`;

                    for (const [cat, accs] of Object.entries(grouped)) {
                        const catTotal = accs.reduce((sum, a) => sum + parseFloat(a.currentBalance), 0);
                        html += `
                                <div class="mb-6">
                                    <h4 class="font-bold text-gray-700 mb-2 pl-2 border-l-4 border-gray-200">${this.formatStr(cat)}</h4>
                                    ${accs.map(a => `
                                        <div class="flex justify-between py-1 pl-6 pr-4 hover:bg-gray-50 border-b border-gray-50">
                                            <span class="text-sm text-gray-600">${a.code} - ${a.name}</span>
                                            <span class="font-mono text-sm font-medium text-gray-800">${this.fmt(a.currentBalance)}</span>
                                        </div>
                                    `).join('')}
                                    <div class="flex justify-between py-2 pl-4 pr-4 bg-gray-50 font-semibold border-t border-gray-100 mt-1">
                                        <span class="text-sm text-gray-500">Total ${this.formatStr(cat)}</span>
                                        <span class="font-mono text-gray-900">${this.fmt(catTotal)}</span>
                                    </div>
                                </div>
                            `;
                    }
                    html += `<div class="flex justify-between py-3 px-4 ${colorClass.bg} border-t-2 ${colorClass.border} font-bold ${colorClass.text}">
                                    <span>TOTAL ${title}</span>
                                    <span class="font-mono">${this.fmt(total)}</span>
                                 </div>
                            </div>`;
                    return html;
                };

                const body = document.getElementById('is-body');
                body.innerHTML = renderSection('REVENUE', revenue, totalRev, { text: 'text-green-700', border: 'border-green-200', bg: 'bg-green-50' }) +
                    renderSection('EXPENSES', expense, totalExp, { text: 'text-red-700', border: 'border-red-200', bg: 'bg-red-50' });

                // Net Profit Row
                body.innerHTML += `
                         <div class="flex justify-between py-4 px-4 border-t-4 border-gray-300 font-bold text-xl ${netProfit >= 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'}">
                            <span>NET PROFIT (LOSS)</span>
                            <span class="font-mono">${this.fmt(netProfit)}</span>
                         </div>
                    `;
            },

            renderBalanceSheet() {
                this.state.asOfDate = document.getElementById('bs-as-of-date').value;
                document.getElementById('bs-date-label').innerText = `As of ${this.formatDate(this.state.asOfDate)}`;

                const assets = this.data.accounts.filter(a => a.type === 'asset');
                const liabs = this.data.accounts.filter(a => a.type === 'liability'); // already pos in mock
                const equity = this.data.accounts.filter(a => a.type === 'equity'); // already pos in mock

                const totAssets = assets.reduce((s, a) => s + parseFloat(a.currentBalance), 0);
                const totLiabs = liabs.reduce((s, a) => s + parseFloat(a.currentBalance), 0);
                const totEquity = equity.reduce((s, a) => s + parseFloat(a.currentBalance), 0);
                const totLiabEq = totLiabs + totEquity;

                const diff = Math.abs(totAssets - totLiabEq);
                const isBalanced = diff < 0.01;

                // Update KPI & Status
                document.getElementById('bs-total-assets').innerText = this.fmt(totAssets);
                document.getElementById('bs-total-liabilities').innerText = this.fmt(totLiabs);
                document.getElementById('bs-total-equity').innerText = this.fmt(totEquity);
                document.getElementById('bs-total-liab-equity').innerText = this.fmt(totLiabEq);

                const statusCard = document.getElementById('bs-status-card');
                const title = document.getElementById('bs-status-title');
                const msg = document.getElementById('bs-status-msg');
                const icon = document.getElementById('bs-status-icon');
                const iconBg = document.getElementById('bs-status-icon-bg');

                if (isBalanced) {
                    statusCard.className = "bg-green-50 border border-green-200 rounded-xl p-6 transition-colors";
                    iconBg.className = "w-10 h-10 rounded-full bg-green-100 flex items-center justify-center";
                    icon.className = "bi bi-building text-green-600";
                    title.className = "text-lg font-bold text-green-900";
                    title.innerText = "Balance Sheet is Balanced ✓";
                    msg.className = "text-sm text-green-700";
                    msg.innerText = "Assets = Liabilities + Equity";
                } else {
                    statusCard.className = "bg-red-50 border border-red-200 rounded-xl p-6 transition-colors";
                    iconBg.className = "w-10 h-10 rounded-full bg-red-100 flex items-center justify-center";
                    icon.className = "bi bi-exclamation-triangle text-red-600";
                    title.className = "text-lg font-bold text-red-900";
                    title.innerText = "Balance Sheet is Out of Balance";
                    msg.className = "text-sm text-red-700";
                    msg.innerText = `Difference: ${this.fmt(diff)}`;
                }

                // Render Groups
                const renderSection = (title, accounts, total, colorClass) => {
                    const grouped = this.groupBy(accounts, 'category');
                    let html = `<div class="mb-8">
                            <h3 class="text-lg font-bold mb-4 pb-2 border-b-2 ${colorClass.border} ${colorClass.text}">${title}</h3>`;

                    for (const [cat, accs] of Object.entries(grouped)) {
                        const catTotal = accs.reduce((sum, a) => sum + parseFloat(a.currentBalance), 0);
                        html += `
                                <div class="mb-6">
                                    <h4 class="font-bold text-gray-700 mb-2 pl-2 border-l-4 border-gray-200">${this.formatStr(cat)}</h4>
                                    ${accs.map(a => `
                                        <div class="flex justify-between py-1 pl-6 pr-4 hover:bg-gray-50 border-b border-gray-50">
                                            <span class="text-sm text-gray-600">${a.code} - ${a.name}</span>
                                            <span class="font-mono text-sm font-medium text-gray-800">${this.fmt(a.currentBalance)}</span>
                                        </div>
                                    `).join('')}
                                    <div class="flex justify-between py-2 pl-4 pr-4 bg-gray-50 font-semibold border-t border-gray-100 mt-1">
                                        <span class="text-sm text-gray-500">Total ${this.formatStr(cat)}</span>
                                        <span class="font-mono text-gray-900">${this.fmt(catTotal)}</span>
                                    </div>
                                </div>
                            `;
                    }

                    html += `<div class="flex justify-between py-3 px-4 ${colorClass.bg} border-t-2 ${colorClass.border} font-bold ${colorClass.text}">
                                    <span>TOTAL ${title}</span>
                                    <span class="font-mono">${this.fmt(total)}</span>
                                 </div>
                            </div>`;
                    return html;
                };

                const body = document.getElementById('bs-body');
                body.innerHTML = renderSection('ASSETS', assets, totAssets, { text: 'text-blue-700', border: 'border-blue-200', bg: 'bg-blue-50' }) +
                    renderSection('LIABILITIES', liabs, totLiabs, { text: 'text-red-700', border: 'border-red-200', bg: 'bg-red-50' }) +
                    renderSection('EQUITY', equity, totEquity, { text: 'text-purple-700', border: 'border-purple-200', bg: 'bg-purple-50' });

                body.innerHTML += `
                         <div class="flex justify-between py-4 px-4 bg-gray-100 border-t-4 border-gray-300 font-bold text-xl mt-6">
                            <span>TOTAL LIABILITIES + EQUITY</span>
                            <span class="font-mono">${this.fmt(totLiabEq)}</span>
                         </div>
                     `;
            },

            // --- Helpers ---
            switchTab(id) {
                this.state.activeTab = id;
                ['trial-balance', 'income-statement', 'balance-sheet'].forEach(t => {
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

            groupBy(array, key) {
                return array.reduce((result, currentValue) => {
                    (result[currentValue[key]] = result[currentValue[key]] || []).push(currentValue);
                    return result;
                }, {});
            },

            fmt(num) {
                return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(num);
            },

            formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            },

            formatStr(str) {
                if (!str) return '';
                return str.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            },

            exportCSV(type) {
                Swal.fire({
                    icon: 'success',
                    title: 'Export Started',
                    text: `Exporting ${this.formatStr(type)} to CSV...`,
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            trialBalanceManager.init();
        });
    </script>
@endsection