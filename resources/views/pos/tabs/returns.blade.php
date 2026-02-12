<div class="h-full flex items-center justify-center p-4">
    <button onclick="openReturnModal()"
        class="px-6 lg:px-8 py-3 lg:py-4 bg-gradient-to-r from-red-600 to-orange-600 hover:from-red-700 hover:to-orange-700 text-white rounded-xl lg:rounded-2xl shadow-lg flex items-center gap-2 lg:gap-3 transition-all transform hover:scale-105">
        <i class="bi bi-arrow-counterclockwise text-2xl"></i>
        <div class="text-left">
            <p class="text-base lg:text-lg font-bold">Start Return Process</p>
            <p class="text-xs lg:text-sm text-red-100">Look up original transaction</p>
        </div>
    </button>
</div>

<script>
    var returnTransactions = [];
    var selectedReturnTxnId = null;

    // Note: No init call here because returns uses a modal triggered by button
    // But we should probably pre-fetch or fetch on open

    function fetchReturnTransactions() {
        $.ajax({
            url: "{{ route('pos.returns') }}",
            method: 'GET',
            success: function (response) {
                returnTransactions = response;
                renderReturnList();
            },
            error: function () {
                toastr.error('Failed to load return transactions');
            }
        });
    }

    const formatReturnDate = (ts) => new Date(ts).toLocaleString('en-LK', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });

    function openReturnModal() {
        // Lazy load data on open if needed
        if (returnTransactions.length === 0) fetchReturnTransactions();

        document.getElementById('return-lookup-modal').classList.remove('hidden');
        document.getElementById('return-search-input').focus();
        selectedReturnTxnId = null;
        renderReturnList();
        renderReturnDetails();
        updateSelectButton();
    }

    function closeReturnModal() {
        document.getElementById('return-lookup-modal').classList.add('hidden');
        document.getElementById('return-search-input').value = '';
    }

    function confirmReturnSelection() {
        if (!selectedReturnTxnId) return;

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Transaction Selected',
                text: `Proceeding with return for Transaction ID: ${selectedReturnTxnId}`,
                icon: 'success',
                confirmButtonColor: '#4f46e5'
            });
        } else {
            alert(`Selected Transaction: ${selectedReturnTxnId}`);
        }
        closeReturnModal();
    }

    function renderReturnList() {
        // Handle null/undef in case called before fetch
        if (!returnTransactions) return;

        const query = document.getElementById('return-search-input').value.toLowerCase();
        const container = document.getElementById('return-list-container');

        const filtered = returnTransactions.filter(txn => {
            return txn.id.toLowerCase().includes(query) ||
                txn.receiptNumber.toLowerCase().includes(query) ||
                txn.cashier.toLowerCase().includes(query) ||
                (txn.customer?.name || '').toLowerCase().includes(query);
        }).sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-500 p-6">
                    <i class="bi bi-${query ? 'search' : 'receipt'} text-4xl mb-3 text-gray-300"></i>
                    <p>${query ? 'No transactions found' : 'No transactions available'}</p>
                    <p class="text-sm">${query ? 'Try a different search term' : 'Start searching to find transactions'}</p>
                </div>`;
            return;
        }

        container.innerHTML = `<div class="divide-y divide-gray-200">` + filtered.map(txn => {
            const isSelected = selectedReturnTxnId === txn.id;
            const activeClass = isSelected ? 'bg-indigo-50 border-l-4 border-indigo-600' : 'hover:bg-gray-50';

            return `
                <button onclick="selectReturnTxn('${txn.id}')" 
                    class="w-full p-4 text-left transition-colors ${activeClass}">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-gray-900 font-medium">Receipt #${txn.receiptNumber}</p>
                            <p class="text-xs text-gray-500 mt-0.5">ID: ${txn.id}</p>
                        </div>
                        <span class="text-sm text-indigo-600 font-bold">Rs ${txn.total.toFixed(2)}</span>
                    </div>
                    <div class="space-y-1 text-xs text-gray-600">
                        <div class="flex items-center gap-1"><i class="bi bi-calendar"></i> <span>${formatReturnDate(txn.timestamp)}</span></div>
                        <div class="flex items-center gap-1"><i class="bi bi-person"></i> <span>${txn.customer?.name || 'Walk-in'}</span></div>
                        <div class="flex items-center gap-1"><i class="bi bi-basket"></i> <span>${txn.items.length} item${txn.items.length !== 1 ? 's' : ''}</span></div>
                    </div>
                </button>
            `;
        }).join('') + `</div>`;
    }

    function selectReturnTxn(id) {
        selectedReturnTxnId = id;
        renderReturnList();
        renderReturnDetails();
        updateSelectButton();
    }

    function renderReturnDetails() {
        const container = document.getElementById('return-details-container');
        const txn = returnTransactions.find(t => t.id === selectedReturnTxnId);

        if (!txn) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-400 p-6">
                    <i class="bi bi-receipt text-5xl mb-3"></i>
                    <p>Select a transaction to view details</p>
                </div>`;
            return;
        }

        const itemsHtml = txn.items.map(item => `
            <div class="bg-gray-50 rounded-lg p-3 mb-2">
                <div class="flex items-start justify-between mb-1">
                    <div class="flex-1">
                        <p class="text-sm text-gray-900 font-medium">${item.productName}</p>
                        <p class="text-xs text-gray-500">${item.productSKU}</p>
                    </div>
                    <p class="text-sm text-gray-900 font-bold">Rs ${item.lineTotal.toFixed(2)}</p>
                </div>
                <div class="text-xs text-gray-600">Qty: ${item.quantity} × Rs ${item.unitPrice.toFixed(2)}</div>
            </div>
        `).join('');

        const paymentsHtml = txn.paymentMethods.map(pm => `
            <div class="flex items-center justify-between text-sm bg-green-50 px-3 py-2 rounded-lg mb-1">
                <span class="text-green-700 capitalize">${pm.method}</span>
                <span class="text-green-900 font-bold">Rs ${pm.amount.toFixed(2)}</span>
            </div>
        `).join('');

        container.innerHTML = `
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-xl text-gray-900 font-bold mb-2">Receipt #${txn.receiptNumber}</h3>
                    <div class="space-y-1 text-sm text-gray-600">
                        <div class="flex items-center gap-2"><i class="bi bi-calendar"></i> <span>${formatReturnDate(txn.timestamp)}</span></div>
                        <div class="flex items-center gap-2"><i class="bi bi-person"></i> <span>${txn.customer?.name || 'Walk-in Customer'}</span></div>
                        <div class="flex items-center gap-2"><i class="bi bi-person-badge"></i> <span>Cashier: ${txn.cashier}</span></div>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-sm text-gray-700 font-bold mb-3">Items (${txn.items.length}):</h4>
                    <div>${itemsHtml}</div>
                </div>

                <div class="mb-6">
                    <h4 class="text-sm text-gray-700 font-bold mb-3">Payment:</h4>
                    <div class="bg-gray-50 rounded-xl p-4 space-y-2 mb-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="text-gray-900">Rs ${txn.subtotal.toFixed(2)}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Tax:</span>
                            <span class="text-gray-900">Rs ${txn.tax.toFixed(2)}</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                            <span class="text-gray-900 font-bold">Total:</span>
                            <span class="text-xl text-indigo-600 font-bold">Rs ${txn.total.toFixed(2)}</span>
                        </div>
                    </div>
                    <div>${paymentsHtml}</div>
                </div>
            </div>
        `;
    }

    function updateSelectButton() {
        const btn = document.getElementById('btn-select-return');
        if (selectedReturnTxnId) {
            btn.disabled = false;
            btn.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            btn.classList.add('bg-gradient-to-r', 'from-indigo-600', 'to-purple-600', 'hover:from-indigo-700', 'hover:to-purple-700', 'text-white', 'cursor-pointer');
        } else {
            btn.disabled = true;
            btn.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            btn.classList.remove('bg-gradient-to-r', 'from-indigo-600', 'to-purple-600', 'hover:from-indigo-700', 'hover:to-purple-700', 'text-white', 'cursor-pointer');
        }
    }
</script>