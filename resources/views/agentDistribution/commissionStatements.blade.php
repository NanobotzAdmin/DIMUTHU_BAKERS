@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="commission-statements-app">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Commission Statements</h1>
                <p class="text-gray-600">Generate and view agent commission statements</p>
            </div>
            <button onclick="openGenerateModal()"
                class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors shadow-sm">
                <i class="bi bi-file-earmark-text-fill mr-2"></i>
                Generate Statement
            </button>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-files text-blue-600 text-xl"></i>
                    <span class="text-blue-700 text-xs font-semibold uppercase tracking-wider">Total Statements</span>
                </div>
                <p class="text-2xl font-bold text-blue-900" id="stat-total">0</p>
            </div>

            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-calendar-event text-yellow-600 text-xl"></i>
                    <span class="text-yellow-700 text-xs font-semibold uppercase tracking-wider">Unpaid</span>
                </div>
                <p class="text-2xl font-bold text-yellow-900" id="stat-unpaid">0</p>
            </div>

            <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-graph-up-arrow text-green-600 text-xl"></i>
                    <span class="text-green-700 text-xs font-semibold uppercase tracking-wider">Paid</span>
                </div>
                <p class="text-2xl font-bold text-green-900" id="stat-paid">0</p>
            </div>

            <div class="p-4 bg-orange-50 border border-orange-200 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-currency-dollar text-orange-600 text-xl"></i>
                    <span class="text-orange-700 text-xs font-semibold uppercase tracking-wider">Total Owed</span>
                </div>
                <p class="text-xl font-bold text-orange-900" id="stat-owed">Rs. 0</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Agent</label>
                    <select id="filter-agent" onchange="renderStatements()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500">
                        <option value="all">All Agents</option>
                        <!-- Injected via JS -->
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                    <select id="filter-status" onchange="renderStatements()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500">
                        <option value="all">All Status</option>
                        <option value="unpaid">Unpaid</option>
                        <option value="partial">Partially Paid</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Statements List -->
        <div id="statements-list" class="space-y-4">
            <!-- Injected via JS -->
            <div id="empty-state" class="hidden p-12 text-center bg-white rounded-xl border border-gray-200">
                <i class="bi bi-file-earmark-text text-gray-300 text-6xl mb-4 block"></i>
                <h3 class="text-gray-900 font-medium mb-1">No Commission Statements</h3>
                <p class="text-gray-500 mb-6">Generate your first commission statement to see it here.</p>
                <button onclick="openGenerateModal()"
                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
                    Generate Statement
                </button>
            </div>
        </div>

    </div>

    <!-- Generate Modal -->
    <div id="generate-modal"
        class="fixed inset-0 z-50 hidden bg-gray-900/75 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Generate Commission Statement</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
                    <select id="gen-agent"
                        class="w-full p-2 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Select Agent</option>
                        <!-- Populated via JS -->
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Period Start</label>
                        <input type="date" id="gen-start"
                            class="w-full p-2 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Period End</label>
                        <input type="date" id="gen-end"
                            class="w-full p-2 border rounded-lg border-gray-300 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                </div>
            </div>
            <div class="p-6 bg-gray-50 rounded-b-xl flex justify-end gap-2">
                <button onclick="closeGenerateModal()"
                    class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-lg">Cancel</button>
                <button onclick="generateStatement()"
                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">Generate</button>
            </div>
        </div>
    </div>

    <!-- View Statement Modal (Invoice Style) -->
    <div id="view-modal" class="fixed inset-0 z-50 hidden bg-gray-900/75 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] flex flex-col">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-xl">
                <h3 class="text-lg font-bold text-gray-900">Statement Details</h3>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-8" id="printable-area">
                <!-- Header -->
                <div class="text-center mb-8 border-b-2 border-gray-200 pb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">COMMISSION STATEMENT</h1>
                    <p class="text-xl text-gray-600 mb-4" id="view-number">CST-000000</p>
                    <div class="flex justify-between text-sm mt-4 px-4">
                        <div class="text-left">
                            <p class="text-gray-500">Period Coverage</p>
                            <p class="text-gray-900 font-medium" id="view-period">Jan 01, 2026 - Jan 31, 2026</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-500">Generated Date</p>
                            <p class="text-gray-900 font-medium" id="view-generated">Jan 31, 2026</p>
                        </div>
                    </div>
                </div>

                <!-- Report Body -->
                <div class="space-y-8">
                    <!-- Agent Info -->
                    <div>
                        <h3 class="text-gray-900 font-bold border-b border-gray-200 pb-2 mb-4">Agent Information</h3>
                        <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm px-4">
                            <div>
                                <span class="text-gray-500 block">Agent Name</span>
                                <span class="text-gray-900 font-medium" id="view-agent-name">John Doe</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Agent Code</span>
                                <span class="text-gray-900 font-medium" id="view-agent-code">AGT001</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Days Worked</span>
                                <span class="text-gray-900 font-medium" id="view-days">20 days</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Settlements Processed</span>
                                <span class="text-gray-900 font-medium" id="view-count">20</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Summary -->
                    <div>
                        <h3 class="text-gray-900 font-bold border-b border-gray-200 pb-2 mb-4">Sales Summary</h3>
                        <div class="px-4 space-y-2 text-sm">
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Cash Sales</span>
                                <span class="text-gray-900" id="view-cash">Rs. 0.00</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Credit Sales</span>
                                <span class="text-gray-900" id="view-credit">Rs. 0.00</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Cheque Sales</span>
                                <span class="text-gray-900" id="view-cheque">Rs. 0.00</span>
                            </div>
                            <div class="flex justify-between py-2 border-t border-gray-300 mt-2 font-bold text-base">
                                <span class="text-gray-900">Total Sales</span>
                                <span class="text-gray-900" id="view-total-sales">Rs. 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Calculation -->
                    <div>
                        <h3 class="text-gray-900 font-bold border-b border-gray-200 pb-2 mb-4">Commission Calculation</h3>
                        <div class="px-4 space-y-2 text-sm">
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Total Eligible Sales</span>
                                <span class="text-gray-900" id="view-calc-sales">Rs. 0.00</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Commission Rate</span>
                                <span class="text-gray-900" id="view-rate">5.0%</span>
                            </div>
                            <div class="flex justify-between py-2 border-t border-gray-200 mt-2 font-medium text-green-700">
                                <span>Gross Commission</span>
                                <span id="view-gross">Rs. 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Deductions & Net -->
                    <div>
                        <h3 class="text-gray-900 font-bold border-b border-gray-200 pb-2 mb-4">Deductions & Net Payable</h3>
                        <div class="px-4 space-y-2 text-sm">
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Tax (10%)</span>
                                <span class="text-red-600" id="view-tax">-Rs. 0.00</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-600">Total Deductions</span>
                                <span class="text-red-600 font-medium" id="view-deductions">-Rs. 0.00</span>
                            </div>

                            <div
                                class="mt-6 p-6 bg-green-50 border-2 border-green-500 rounded-lg flex justify-between items-center">
                                <span class="text-green-900 text-lg font-bold">NET COMMISSION PAYABLE</span>
                                <span class="text-green-900 text-3xl font-bold" id="view-net">Rs. 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="px-4 pt-4">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Paid Amount</span>
                            <span class="text-gray-900 font-medium" id="view-paid-amt">Rs. 0.00</span>
                        </div>
                        <div class="flex justify-between py-2 mt-2">
                            <span class="text-gray-900 font-medium">Balance Due</span>
                            <span class="text-orange-600 font-bold" id="view-balance">Rs. 0.00</span>
                        </div>
                    </div>

                    <div class="text-center text-xs text-gray-500 pt-8 border-t border-gray-200">
                        <p>This is a computer-generated document. No signature is required.</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 rounded-b-xl border-t border-gray-200 flex justify-end gap-3 print:hidden">
                <button onclick="window.print()"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 shadow-sm">
                    <i class="bi bi-printer mr-2"></i> Print
                </button>
                <button onclick="showToast('Downloading PDF...')"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 shadow-sm">
                    <i class="bi bi-download mr-2"></i> PDF
                </button>
                <button onclick="closeViewModal()"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">Close</button>
            </div>
        </div>
    </div>

    <script>
        // Server Data
        const serverAgents = @json($agents ?? []);
        const serverSettlements = @json($settlements ?? []);

        const state = {
            agents: serverAgents,
            settlements: serverSettlements,
            statements: []
        };

        document.addEventListener('DOMContentLoaded', () => {
            // Init LocalStorage
            const stored = localStorage.getItem('commissionStatements');
            if (stored) {
                state.statements = JSON.parse(stored);
            }

            // Init UI
            populateAgents();
            renderStatements();
            updateStats();

            // Default Dates (Current Month)
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            document.getElementById('gen-start').value = firstDay.toISOString().split('T')[0];
            document.getElementById('gen-end').value = now.toISOString().split('T')[0];
        });

        function populateAgents() {
            const options = state.agents.map(a => `<option value="${a.id}">${a.agentName} (${a.agentCode})</option>`).join('');
            document.getElementById('filter-agent').innerHTML += options;
            document.getElementById('gen-agent').innerHTML += options;
        }

        // --- Logic ---

        function generateStatement() {
            const agentId = document.getElementById('gen-agent').value;
            const start = document.getElementById('gen-start').value;
            const end = document.getElementById('gen-end').value;

            if (!agentId) { showToast('Please select an agent', 'error'); return; }

            const agent = state.agents.find(a => a.id === agentId);

            // Find settlements
            const matchSettlements = state.settlements.filter(s =>
                s.agentId === agentId &&
                s.settlementDate >= start &&
                s.settlementDate <= end &&
                s.status === 'approved'
            );

            if (matchSettlements.length === 0) {
                showToast('No approved settlements found for this period', 'error');
                return;
            }

            // Calcs
            const totalSales = matchSettlements.reduce((sum, s) => sum + Number(s.totalSales), 0);
            const cashSales = matchSettlements.reduce((sum, s) => sum + Number(s.cashSales), 0);
            const creditSales = matchSettlements.reduce((sum, s) => sum + Number(s.creditSales), 0);
            const chequeSales = matchSettlements.reduce((sum, s) => sum + Number(s.chequeSales), 0);
            const grossCommission = matchSettlements.reduce((sum, s) => sum + Number(s.commissionEarned), 0);

            const tax = grossCommission * 0.10;
            const deductions = tax; // Expandable later
            const net = grossCommission - deductions;

            const newStmt = {
                id: 'stmt_' + Date.now(),
                statementNumber: 'CST-' + Date.now().toString().slice(-6),
                agentId: agent.id,
                agentName: agent.agentName,
                agentCode: agent.agentCode,
                periodStart: start,
                periodEnd: end,
                totalSales, cashSales, creditSales, chequeSales,
                daysWorked: matchSettlements.length,
                settlementCount: matchSettlements.length,
                commissionRate: agent.commissionRate,
                grossCommission,
                deductions: { tax, other: 0 },
                netCommission: net,
                paymentStatus: 'unpaid',
                paidAmount: 0,
                balanceOwed: net,
                generatedAt: new Date().toISOString()
            };

            state.statements.unshift(newStmt);
            saveStatements();

            closeGenerateModal();
            renderStatements();
            updateStats();
            showToast('Statement generated successfully');
        }

        function saveStatements() {
            localStorage.setItem('commissionStatements', JSON.stringify(state.statements));
        }

        function renderStatements() {
            const filterAgent = document.getElementById('filter-agent').value;
            const filterStatus = document.getElementById('filter-status').value;
            const list = document.getElementById('statements-list');
            const empty = document.getElementById('empty-state');

            const filtered = state.statements.filter(s => {
                if (filterAgent !== 'all' && s.agentId !== filterAgent) return false;
                if (filterStatus !== 'all' && s.paymentStatus !== filterStatus) return false;
                return true;
            });

            if (filtered.length === 0) {
                list.innerHTML = '';
                list.appendChild(empty);
                empty.classList.remove('hidden');
                return;
            }

            empty.classList.add('hidden');
            list.innerHTML = filtered.map(s => {
                const statusClass = s.paymentStatus === 'paid' ? 'bg-green-100 text-green-800' : (s.paymentStatus === 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800');

                return `
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-gray-900 font-bold">${s.statementNumber}</h3>
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">${s.agentName}</span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium capitalize ${statusClass}">${s.paymentStatus}</span>
                                </div>
                                <p class="text-gray-500 text-sm mb-4">
                                    Period: ${new Date(s.periodStart).toLocaleDateString()} - ${new Date(s.periodEnd).toLocaleDateString()}
                                </p>

                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                    <div><p class="text-xs text-gray-500">Total Sales</p><p class="text-sm font-medium text-gray-900">${formatCurrency(s.totalSales)}</p></div>
                                    <div><p class="text-xs text-gray-500">Days Worked</p><p class="text-sm font-medium text-gray-900">${s.daysWorked}</p></div>
                                    <div><p class="text-xs text-gray-500">Gross Comm.</p><p class="text-sm font-medium text-gray-900">${formatCurrency(s.grossCommission)}</p></div>
                                    <div><p class="text-xs text-gray-500">Net Payable</p><p class="text-sm font-bold text-green-600">${formatCurrency(s.netCommission)}</p></div>
                                    <div><p class="text-xs text-gray-500">Balance Owed</p><p class="text-sm font-bold text-orange-600">${formatCurrency(s.balanceOwed)}</p></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick='viewStatement("${s.id}")' class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg border border-gray-200"><i class="bi bi-eye"></i></button>
                                <button onclick="window.print()" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg border border-gray-200"><i class="bi bi-printer"></i></button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function viewStatement(id) {
            const stmt = state.statements.find(s => s.id === id);
            if (!stmt) return;

            document.getElementById('view-number').textContent = stmt.statementNumber;
            document.getElementById('view-period').textContent = `${new Date(stmt.periodStart).toLocaleDateString()} - ${new Date(stmt.periodEnd).toLocaleDateString()}`;
            document.getElementById('view-generated').textContent = new Date(stmt.generatedAt).toLocaleDateString();

            document.getElementById('view-agent-name').textContent = stmt.agentName;
            document.getElementById('view-agent-code').textContent = stmt.agentCode;
            document.getElementById('view-days').textContent = `${stmt.daysWorked} days`;
            document.getElementById('view-count').textContent = stmt.settlementCount;

            document.getElementById('view-cash').textContent = formatCurrency(stmt.cashSales);
            document.getElementById('view-credit').textContent = formatCurrency(stmt.creditSales);
            document.getElementById('view-cheque').textContent = formatCurrency(stmt.chequeSales);
            document.getElementById('view-total-sales').textContent = formatCurrency(stmt.totalSales);

            document.getElementById('view-calc-sales').textContent = formatCurrency(stmt.totalSales);
            document.getElementById('view-rate').textContent = `${stmt.commissionRate}%`;
            document.getElementById('view-gross').textContent = formatCurrency(stmt.grossCommission);

            document.getElementById('view-tax').textContent = `-${formatCurrency(stmt.deductions.tax)}`;
            const totalDed = stmt.deductions.tax + stmt.deductions.other;
            document.getElementById('view-deductions').textContent = `-${formatCurrency(totalDed)}`;
            document.getElementById('view-net').textContent = formatCurrency(stmt.netCommission);

            document.getElementById('view-paid-amt').textContent = formatCurrency(stmt.paidAmount);
            document.getElementById('view-balance').textContent = formatCurrency(stmt.balanceOwed);

            document.getElementById('view-modal').classList.remove('hidden');
        }

        function updateStats() {
            const total = state.statements.length;
            const unpaid = state.statements.filter(s => s.paymentStatus === 'unpaid').length;
            const paid = state.statements.filter(s => s.paymentStatus === 'paid').length;
            const owed = state.statements.reduce((sum, s) => sum + s.balanceOwed, 0);

            document.getElementById('stat-total').textContent = total;
            document.getElementById('stat-unpaid').textContent = unpaid;
            document.getElementById('stat-paid').textContent = paid;
            document.getElementById('stat-owed').textContent = formatCurrency(owed);
        }

        // --- Modals ---
        function openGenerateModal() {
            document.getElementById('generate-modal').classList.remove('hidden');
        }
        function closeGenerateModal() {
            document.getElementById('generate-modal').classList.add('hidden');
        }
        function closeViewModal() {
            document.getElementById('view-modal').classList.add('hidden');
        }

        // --- Utils ---
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'LKR', minimumFractionDigits: 2 }).format(amount).replace('LKR', 'Rs.');
        }
        function showToast(message, type = 'success') {
            const div = document.createElement('div');
            const bg = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            div.className = `fixed top-4 right-4 ${bg} text-white px-6 py-3 rounded-lg shadow-lg z-[80] transition-opacity`;
            div.innerHTML = `<i class="bi bi-info-circle mr-2"></i> ${message}`;
            document.body.appendChild(div);
            setTimeout(() => { div.style.opacity = '0'; setTimeout(() => div.remove(), 300); }, 3000);
        }
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #view-modal,
            #view-modal * {
                visibility: visible;
            }

            #view-modal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: white;
            }

            #printable-area {
                padding: 0;
                overflow: visible;
            }

            button {
                display: none !important;
            }
        }
    </style>
@endsection