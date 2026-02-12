<div id="history-list-panel" class="flex-1 flex flex-col border-gray-200 lg:border-r h-full">
    <div class="p-3 lg:p-4 bg-white border-b border-gray-200 space-y-3">
        <div class="relative">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <i class="bi bi-search"></i>
            </div>
            <input type="text" id="history-search" onkeyup="renderHistoryList()"
                placeholder="Search by receipt #, customer, or cashier..."
                class="w-full h-12 pl-10 pr-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>

        <button onclick="toggleHistoryFilters()"
            class="w-full h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm flex items-center justify-center gap-2 transition-colors">
            <i class="bi bi-funnel"></i> Filters <i id="filter-chevron"
                class="bi bi-chevron-down transition-transform"></i>
        </button>

        <div id="history-filters-section" class="grid grid-cols-2 gap-3 pt-2 border-t border-gray-200 hidden">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Date Range:</label>
                <select id="history-filter-date" onchange="renderHistoryList()"
                    class="w-full h-9 px-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="today">Today</option>
                    <option value="week">Last 7 Days</option>
                    <option value="month">Last 30 Days</option>
                    <option value="all">All Time</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Cashier:</label>
                <select id="history-filter-cashier" onchange="renderHistoryList()"
                    class="w-full h-9 px-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="all">All Cashiers</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 pt-2 border-t border-gray-200">
            <div class="bg-purple-50 rounded-lg p-2 text-center">
                <p class="text-xs text-purple-600">Transactions</p>
                <p id="stat-tx-count" class="text-lg text-purple-900 font-bold">0</p>
            </div>
            <div class="bg-green-50 rounded-lg p-2 text-center">
                <p class="text-xs text-green-600">Total Sales</p>
                <p id="stat-tx-total" class="text-lg text-green-900 font-bold">Rs 0</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-2 text-center">
                <p class="text-xs text-blue-600">Average</p>
                <p id="stat-tx-avg" class="text-lg text-blue-900 font-bold">Rs 0</p>
            </div>
        </div>
    </div>

    <div id="history-list-container" class="flex-1 overflow-y-auto divide-y divide-gray-200 p-4">
    </div>
</div>

<div id="history-details-panel"
    class="hidden lg:flex lg:w-1/3 bg-gray-50 flex-col fixed lg:relative inset-0 lg:inset-auto z-50 lg:z-auto h-full">
    <div id="history-empty-state" class="flex flex-col items-center justify-center h-full text-gray-400">
        <i class="bi bi-receipt text-5xl mb-3"></i>
        <p>Select a transaction</p>
        <p class="text-sm">to view details</p>
    </div>

    <div id="history-details-content" class="flex-col h-full hidden w-full">
        <div class="lg:hidden p-3 bg-white border-b border-gray-200">
            <button onclick="closeHistoryDetails()" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
                <i class="bi bi-arrow-left"></i> <span class="text-sm font-medium">Back to
                    Transactions</span>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 lg:p-6">
            <div class="bg-white rounded-xl p-4 mb-4 shadow-sm">
                <div class="text-center mb-4">
                    <p id="detail-receipt-no" class="text-2xl text-purple-600 font-bold">--</p>
                    <p id="detail-date" class="text-sm text-gray-500">--</p>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Transaction ID:</span>
                        <span id="detail-id" class="text-gray-900 text-xs font-mono">--</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Cashier:</span>
                        <span id="detail-cashier" class="text-gray-900 font-medium">--</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Customer:</span>
                        <span id="detail-customer" class="text-gray-900 font-medium">--</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 mb-4 shadow-sm">
                <h4 class="text-sm text-gray-700 font-bold mb-3">Items:</h4>
                <div id="detail-items-list" class="space-y-2">
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 mb-4 shadow-sm">
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span id="detail-subtotal" class="text-gray-900">Rs 0.00</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Tax:</span>
                        <span id="detail-tax" class="text-gray-900">Rs 0.00</span>
                    </div>
                    <div class="flex items-center justify-between text-orange-600" id="detail-discount-row">
                        <span>Discount:</span>
                        <span id="detail-discount">-Rs 0.00</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                        <span class="text-gray-900 font-bold">Total:</span>
                        <span id="detail-total" class="text-xl text-purple-600 font-bold">Rs 0.00</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm">
                <h4 class="text-sm text-gray-700 font-bold mb-3">Payment:</h4>
                <div id="detail-payments-list" class="space-y-2">
                </div>
            </div>
        </div>

        <div class="p-4 bg-white border-t border-gray-200 space-y-2">
            <button onclick="historyAction('reprint')"
                class="w-full h-12 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl text-sm flex items-center justify-center gap-2 transition-colors font-medium">
                <i class="bi bi-printer"></i> Reprint Receipt
            </button>
            <button onclick="historyAction('return')"
                class="w-full h-12 bg-orange-50 hover:bg-orange-100 text-orange-600 rounded-xl text-sm flex items-center justify-center gap-2 transition-colors font-medium">
                <i class="bi bi-arrow-counterclockwise"></i> Process Return
            </button>
            <button onclick="historyAction('void')"
                class="w-full h-12 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-sm flex items-center justify-center gap-2 transition-colors font-medium">
                <i class="bi bi-x-circle"></i> Void Transaction
            </button>
        </div>
    </div>
</div>

<script>
    var transactionHistory = [];
    var historySelectedTxnId = null;

    $(document).ready(function () {
        fetchTransactionHistory();
    });

    function fetchTransactionHistory() {
        $.ajax({
            url: "{{ route('pos.history') }}",
            method: 'GET',
            success: function (response) {
                transactionHistory = response;
                populateCashierFilter();
                renderHistoryList();
            },
            error: function () {
                toastr.error('Failed to load transaction history');
            }
        });
    }

    const formatHistoryDate = (ts) => new Date(ts).toLocaleString('en-LK', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });

    function populateCashierFilter() {
        const cashiers = [...new Set(transactionHistory.map(t => t.cashier))];
        const select = document.getElementById('history-filter-cashier');
        select.innerHTML = '<option value="all">All Cashiers</option>';
        cashiers.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c;
            opt.textContent = c;
            select.appendChild(opt);
        });
    }

    function toggleHistoryFilters() {
        const section = document.getElementById('history-filters-section');
        const chevron = document.getElementById('filter-chevron');

        section.classList.toggle('hidden');
        if (section.classList.contains('hidden')) {
            chevron.classList.remove('rotate-180');
        } else {
            chevron.classList.add('rotate-180');
        }
    }

    function renderHistoryList() {
        const searchQuery = document.getElementById('history-search').value.toLowerCase();
        const dateFilter = document.getElementById('history-filter-date').value;
        const cashierFilter = document.getElementById('history-filter-cashier').value;

        const filtered = transactionHistory.filter(txn => {
            const matchesSearch = !searchQuery ||
                txn.receiptNumber.toLowerCase().includes(searchQuery) ||
                txn.cashier.toLowerCase().includes(searchQuery) ||
                (txn.customer?.name || '').toLowerCase().includes(searchQuery);

            const matchesCashier = cashierFilter === 'all' || txn.cashier === cashierFilter;

            const txnDate = new Date(txn.timestamp);
            const now = new Date();
            let matchesDate = true;
            const todayStart = new Date(); todayStart.setHours(0, 0, 0, 0);

            if (dateFilter === 'today') {
                matchesDate = txnDate >= todayStart;
            } else if (dateFilter === 'week') {
                const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                matchesDate = txnDate >= weekAgo;
            } else if (dateFilter === 'month') {
                const monthAgo = new Date(now.getTime() - 30 * 24 * 60 * 60 * 1000);
                matchesDate = txnDate >= monthAgo;
            }

            return matchesSearch && matchesCashier && matchesDate;
        });

        filtered.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));

        const totalSales = filtered.reduce((sum, t) => sum + t.total, 0);
        const avgSales = filtered.length > 0 ? totalSales / filtered.length : 0;

        document.getElementById('stat-tx-count').textContent = filtered.length;
        document.getElementById('stat-tx-total').textContent = 'Rs ' + totalSales.toFixed(0);
        document.getElementById('stat-tx-avg').textContent = 'Rs ' + avgSales.toFixed(0);

        const container = document.getElementById('history-list-container');

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center h-64 text-gray-500 p-6">
                    <i class="bi bi-receipt text-4xl mb-3 text-gray-300"></i>
                    <p>No transactions found</p>
                    <p class="text-sm">Try adjusting your filters</p>
                </div>`;
            return;
        }

        container.innerHTML = filtered.map(txn => {
            const isSelected = historySelectedTxnId == txn.id;
            const activeClass = isSelected ? 'bg-purple-100 border-2 border-purple-900' : 'hover:bg-gray-50';

            return `
                <button onclick="selectHistoryTxn('${txn.id}')" 
                    class="w-full  p-4 text-left transition-colors rounded-md border-2 border-transparent ${activeClass}">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-gray-900 font-medium">Receipt #${txn.receiptNumber}</p>
                            <p class="text-xs text-gray-500 mt-0.5">${formatHistoryDate(txn.timestamp)}</p>
                        </div>
                        <span class="text-sm text-purple-600 font-bold">Rs ${txn.total.toFixed(2)}</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-600">
                        <span class="flex items-center gap-1">
                            <i class="bi bi-person"></i> ${txn.customer?.name || 'Walk-in'}
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="bi bi-basket"></i> ${txn.items.length} items
                        </span>
                    </div>
                </button>
            `;
        }).join('');
    }

    function selectHistoryTxn(id) {
        historySelectedTxnId = id;
        // Use loose equality (==) because id passed from HTML is string, but JSON id might be int
        const txn = transactionHistory.find(t => t.id == id);

        renderHistoryList();

        if (!txn) return;

        if (window.innerWidth < 1024) {
            document.getElementById('history-list-panel').classList.add('hidden');
            document.getElementById('history-details-panel').classList.remove('hidden');
            document.getElementById('history-details-panel').classList.add('flex');
        }

        document.getElementById('history-empty-state').classList.add('hidden');
        document.getElementById('history-details-content').classList.remove('hidden');
        document.getElementById('history-details-content').classList.add('flex');

        document.getElementById('detail-receipt-no').textContent = `Receipt #${txn.receiptNumber}`;
        document.getElementById('detail-date').textContent = formatHistoryDate(txn.timestamp);
        document.getElementById('detail-id').textContent = txn.id;
        document.getElementById('detail-cashier').textContent = txn.cashier;
        document.getElementById('detail-customer').textContent = txn.customer?.name || 'Walk-in';

        const itemsHtml = txn.items.map(item => `
            <div class="flex items-start justify-between text-sm">
                <div class="flex-1">
                    <p class="text-gray-900">${item.productName}</p>
                    <p class="text-xs text-gray-500">${item.quantity} × Rs ${item.unitPrice.toFixed(2)}</p>
                </div>
                <p class="text-gray-900">Rs ${item.lineTotal.toFixed(2)}</p>
            </div>
        `).join('');
        document.getElementById('detail-items-list').innerHTML = itemsHtml;

        document.getElementById('detail-subtotal').textContent = `Rs ${txn.subtotal.toFixed(2)}`;
        document.getElementById('detail-tax').textContent = `Rs ${txn.tax.toFixed(2)}`;

        if (txn.discount > 0) {
            document.getElementById('detail-discount-row').classList.remove('hidden');
            document.getElementById('detail-discount').textContent = `-Rs ${txn.discount.toFixed(2)}`;

            // Set Label based on Type
            const labelEl = document.querySelector('#detail-discount-row span:first-child');
            if (txn.discountType === 1) {
                labelEl.textContent = 'Discount (Percentage):';
            } else if (txn.discountType === 2) {
                labelEl.textContent = 'Discount (Fixed Amount):';
            } else {
                labelEl.textContent = 'Discount:';
            }
        } else {
            document.getElementById('detail-discount-row').classList.add('hidden');
        }

        document.getElementById('detail-total').textContent = `Rs ${txn.total.toFixed(2)}`;

        const paymentsHtml = txn.paymentMethods.map(pm => `
            <div class="flex items-center justify-between text-sm bg-green-50 px-3 py-2 rounded-lg">
                <span class="text-green-700 capitalize">${pm.method}</span>
                <span class="text-green-900 font-bold">Rs ${pm.amount.toFixed(2)}</span>
            </div>
        `).join('');
        document.getElementById('detail-payments-list').innerHTML = paymentsHtml;
    }

    function closeHistoryDetails() {
        document.getElementById('history-list-panel').classList.remove('hidden');
        document.getElementById('history-details-panel').classList.add('hidden');
        document.getElementById('history-details-panel').classList.remove('flex');
    }

    function historyAction(type) {
        if (!historySelectedTxnId) return;

        if (type === 'reprint') {
            const url = "{{ route('pos.receipt', ':id') }}".replace(':id', historySelectedTxnId);
            const iframe = document.getElementById('receipt-print-frame');
            if (iframe) {
                iframe.src = url;
            } else {
                window.open(url, '_blank', 'width=350,height=600');
            }
            return;
        }

        let msg = '';
        switch (type) {
            case 'return': msg = 'Opening Return Process...'; break;
            case 'void': msg = 'Voiding Transaction...'; break;
        }
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type === 'void' ? 'warning' : 'info',
                title: msg,
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            alert(msg);
        }
    }
</script>