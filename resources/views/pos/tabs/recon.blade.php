<div class="flex-1 overflow-y-auto h-full">
    <div class="p-6 max-w-full mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl text-gray-900 mb-2 font-bold">Cash Reconciliation</h2>
            <p class="text-gray-600">Cashier: <span id="recon-cashier-name">Current User</span></p>
            <p id="recon-date" class="text-sm text-gray-500"></p>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 rounded-xl p-4">
                <p class="text-sm text-blue-600 mb-1">Total Transactions</p>
                <p id="recon-total-tx" class="text-2xl text-blue-900 font-bold">0</p>
            </div>
            <div class="bg-green-50 rounded-xl p-4">
                <p class="text-sm text-green-600 mb-1">Cash Transactions</p>
                <p id="recon-cash-tx" class="text-2xl text-green-900 font-bold">0</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4">
                <p class="text-sm text-purple-600 mb-1">Expected Cash</p>
                <p id="recon-expected-display" class="text-2xl text-purple-900 font-bold">Rs 0.00</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border-2 border-gray-200 p-6 mb-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg text-gray-900 font-bold">Count Cash by Denomination</h3>
                <button onclick="clearReconCounts()"
                    class="text-sm text-red-600 hover:text-red-700 underline font-medium">
                    Clear All
                </button>
            </div>

            <div id="recon-denominations-list" class="space-y-3">
            </div>
        </div>

        <div class="bg-white rounded-xl border-2 border-gray-200 p-6 mb-6 shadow-sm">
            <label class="block text-sm text-gray-700 mb-2 font-medium">Notes (Optional):</label>
            <textarea id="recon-notes"
                placeholder="Add any notes about discrepancies, errors, or special circumstances..."
                class="w-full h-24 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
        </div>
    </div>
</div>

<div class="w-96 bg-gray-50 border-l border-gray-200 flex flex-col h-full fixed right-0 lg:relative">
    <div class="flex-1 overflow-y-auto p-6">
        <h3 class="text-lg text-gray-900 mb-4 font-bold">Reconciliation Summary</h3>

        <div class="bg-white rounded-xl p-4 mb-4 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Expected Cash:</p>
            <p id="summary-expected" class="text-2xl text-gray-900 font-bold">Rs 0.00</p>
            <p id="summary-tx-count" class="text-xs text-gray-500 mt-1">From 0 cash transactions</p>
        </div>

        <div class="bg-white rounded-xl p-4 mb-4 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Actual Cash Counted:</p>
            <p id="summary-actual" class="text-2xl text-blue-600 font-bold">Rs 0.00</p>
            <p id="summary-note-count" class="text-xs text-gray-500 mt-1">0 notes/coins</p>
        </div>

        <div id="summary-variance-box"
            class="rounded-xl border-2 p-4 mb-6 bg-green-50 border-green-200 transition-colors">
            <div class="flex items-center gap-2 mb-2">
                <i id="summary-variance-icon" class="bi bi-check-circle-fill text-green-600 text-xl"></i>
                <p class="text-sm text-gray-700 font-medium">Variance:</p>
            </div>
            <p id="summary-variance-amount" class="text-3xl font-bold text-green-600">Rs 0.00</p>
            <p id="summary-variance-pct" class="text-sm text-gray-600 mt-1">0.00%</p>
        </div>

        <div id="summary-status-msg" class="rounded-xl p-4 mb-4 text-sm font-medium hidden">
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm">
            <p class="text-sm text-gray-700 mb-3 font-bold">Denomination Breakdown:</p>
            <div id="summary-breakdown-list" class="space-y-2 text-sm">
            </div>
        </div>
    </div>

    <div class="p-4 bg-white border-t border-gray-200 space-y-2">
        <button onclick="window.print()"
            class="w-full h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl flex items-center justify-center gap-2 transition-colors font-medium">
            <i class="bi bi-printer"></i> Print Summary
        </button>
        <button id="btn-submit-recon" onclick="openReconConfirmModal()" disabled
            class="w-full h-12 rounded-xl transition-all font-medium bg-gray-200 text-gray-400 cursor-not-allowed">
            Submit Reconciliation
        </button>
    </div>
</div>

<!-- Modal -->
<div id="recon-confirm-modal"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 animate-fade-in-up">
        <h3 class="text-xl text-gray-900 mb-4 font-bold">Confirm Reconciliation</h3>

        <div class="space-y-3 mb-6 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Expected:</span>
                <span id="modal-expected" class="text-gray-900 font-bold">Rs 0.00</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Actual:</span>
                <span id="modal-actual" class="text-gray-900 font-bold">Rs 0.00</span>
            </div>
            <div class="flex justify-between pt-3 border-t border-gray-200">
                <span class="text-gray-600">Variance:</span>
                <span id="modal-variance" class="font-bold">Rs 0.00</span>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-6">This will finalize your cash reconciliation for the day. Are
            you sure?</p>

        <div class="flex gap-3">
            <button onclick="closeReconConfirmModal()"
                class="flex-1 h-12 bg-white hover:bg-gray-100 text-gray-700 rounded-xl border border-gray-300 transition-colors font-medium">Cancel</button>
            <button onclick="submitReconciliation()"
                class="flex-1 h-12 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl transition-all font-medium">Confirm</button>
        </div>
    </div>
</div>

<script>
    // Recon Script
    // We use const where appropriate but vars might be needed if re-declared
    // To be safe in partials, assume function scope if possible or distinct names.

    // Configuration
    const DENOMINATIONS = [
        { value: 5000, label: 'Rs 5000' },
        { value: 1000, label: 'Rs 1000' },
        { value: 500, label: 'Rs 500' },
        { value: 100, label: 'Rs 100' },
        { value: 50, label: 'Rs 50' },
        { value: 20, label: 'Rs 20' },
        { value: 10, label: 'Rs 10' },
        { value: 5, label: 'Rs 5' },
        { value: 2, label: 'Rs 2' },
        { value: 1, label: 'Rs 1' },
        { value: 0.5, label: 'Rs 0.50' },
        { value: 0.25, label: 'Rs 0.25' }
    ];

    var reconCounts = {};
    var reconExpectedCash = 0;
    var reconCashTxCount = 0;
    var reconTransactions = [];

    $(document).ready(function () {
        fetchReconData();
    });

    function fetchReconData() {
        $.ajax({
            url: "{{ route('pos.recon') }}",
            method: 'GET',
            success: function (response) {
                reconTransactions = response;
                initializeRecon();
                renderDenominationInputs();
                calculateReconStats();
                updateReconUI();
            },
            error: function () {
                toastr.error('Failed to load reconciliation data');
            }
        });
    }

    function initializeRecon() {
        DENOMINATIONS.forEach(d => reconCounts[d.value] = 0);

        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('recon-date').textContent = new Date().toLocaleDateString('en-LK', options);

        reconExpectedCash = reconTransactions.reduce((total, txn) => {
            const cashPart = (txn.paymentMethods || [])
                .filter(pm => pm.method === 'cash')
                .reduce((sum, pm) => sum + pm.amount, 0);
            return total + cashPart;
        }, 0);

        reconCashTxCount = reconTransactions.filter(txn =>
            (txn.paymentMethods || []).some(pm => pm.method === 'cash')
        ).length;

        document.getElementById('recon-total-tx').textContent = reconTransactions.length;
        document.getElementById('recon-cash-tx').textContent = reconCashTxCount;
        document.getElementById('recon-expected-display').textContent = 'Rs ' + reconExpectedCash.toFixed(2);
    }

    function renderDenominationInputs() {
        const container = document.getElementById('recon-denominations-list');
        container.innerHTML = DENOMINATIONS.map(denom => `
            <div class="flex items-center gap-3 border-b border-gray-100 pb-2 last:border-0">
                <div class="w-24 text-gray-900 font-medium">${denom.label}</div>
                <div class="flex items-center gap-2">
                    <button onclick="updateReconCount(${denom.value}, -1)" 
                        class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors font-bold">-</button>
                    
                    <input type="number" id="input-denom-${denom.value}" 
                        value="0" min="0"
                        onchange="manualReconInput(${denom.value}, this.value)"
                        class="w-20 h-10 px-3 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold">
                    
                    <button onclick="updateReconCount(${denom.value}, 1)" 
                        class="w-8 h-8 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors font-bold">+</button>
                </div>
                <div class="flex-1 text-right text-gray-700 font-mono" id="total-denom-${denom.value}">
                    = Rs 0.00
                </div>
            </div>
        `).join('');
    }

    function updateReconCount(value, change) {
        const current = reconCounts[value] || 0;
        const newCount = Math.max(0, current + change);
        reconCounts[value] = newCount;

        document.getElementById(`input-denom-${value}`).value = newCount;
        updateReconUI();
    }

    function manualReconInput(value, inputValue) {
        const count = parseInt(inputValue) || 0;
        reconCounts[value] = Math.max(0, count);
        updateReconUI();
    }

    function clearReconCounts() {
        if (confirm('Clear all counts?')) {
            DENOMINATIONS.forEach(d => {
                reconCounts[d.value] = 0;
                document.getElementById(`input-denom-${d.value}`).value = 0;
            });
            updateReconUI();
        }
    }

    function updateReconUI() {
        let actualCash = 0;
        let noteCount = 0;

        DENOMINATIONS.forEach(d => {
            const count = reconCounts[d.value];
            const total = count * d.value;
            actualCash += total;
            noteCount += count;
            document.getElementById(`total-denom-${d.value}`).textContent = '= Rs ' + total.toFixed(2);
        });

        const variance = actualCash - reconExpectedCash;
        const variancePct = reconExpectedCash > 0 ? (variance / reconExpectedCash) * 100 : 0;
        const absVariance = Math.abs(variance);

        document.getElementById('summary-expected').textContent = 'Rs ' + reconExpectedCash.toFixed(2);
        document.getElementById('summary-tx-count').textContent = `From ${reconCashTxCount} cash transactions`;

        document.getElementById('summary-actual').textContent = 'Rs ' + actualCash.toFixed(2);
        document.getElementById('summary-note-count').textContent = `${noteCount} notes/coins`;

        const varianceEl = document.getElementById('summary-variance-amount');
        const boxEl = document.getElementById('summary-variance-box');
        const iconEl = document.getElementById('summary-variance-icon');
        const msgEl = document.getElementById('summary-status-msg');

        varianceEl.textContent = (variance >= 0 ? '+' : '') + 'Rs ' + variance.toFixed(2);
        document.getElementById('summary-variance-pct').textContent = (variancePct >= 0 ? '+' : '') + variancePct.toFixed(2) + '%';

        boxEl.className = 'rounded-xl border-2 p-4 mb-6 transition-colors';
        varianceEl.className = 'text-3xl font-bold';
        msgEl.className = 'rounded-xl p-4 mb-4 text-sm font-medium flex items-center';

        if (absVariance < 10) {
            boxEl.classList.add('bg-green-50', 'border-green-200');
            varianceEl.classList.add('text-green-600');
            iconEl.className = 'bi bi-check-circle-fill text-green-600 text-xl';

            msgEl.classList.remove('hidden');
            msgEl.classList.add('bg-green-50', 'border', 'border-green-200', 'text-green-900');
            msgEl.innerHTML = '<i class="bi bi-check-circle mr-2"></i> Cash balanced! Variance within acceptable range.';
        } else if (absVariance < 100) {
            boxEl.classList.add('bg-yellow-50', 'border-yellow-200');
            varianceEl.classList.add('text-yellow-600');
            iconEl.className = 'bi bi-exclamation-triangle-fill text-yellow-600 text-xl';

            msgEl.classList.remove('hidden');
            msgEl.classList.add('bg-yellow-50', 'border', 'border-yellow-200', 'text-yellow-900');
            msgEl.innerHTML = '<i class="bi bi-exclamation-triangle mr-2"></i> Minor discrepancy detected. Please verify count.';
        } else {
            boxEl.classList.add('bg-red-50', 'border-red-200');
            varianceEl.classList.add('text-red-600');
            iconEl.className = 'bi bi-exclamation-triangle-fill text-red-600 text-xl';

            msgEl.classList.remove('hidden');
            msgEl.classList.add('bg-red-50', 'border', 'border-red-200', 'text-red-900');
            msgEl.innerHTML = '<i class="bi bi-exclamation-triangle mr-2"></i> Significant discrepancy! Manager approval required.';
        }

        const breakdownContainer = document.getElementById('summary-breakdown-list');
        const activeDenoms = DENOMINATIONS.filter(d => reconCounts[d.value] > 0);

        if (activeDenoms.length > 0) {
            breakdownContainer.innerHTML = activeDenoms.map(d => `
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">${d.label} × ${reconCounts[d.value]}</span>
                    <span class="text-gray-900 font-medium">Rs ${(reconCounts[d.value] * d.value).toFixed(2)}</span>
                </div>
            `).join('');
        } else {
            breakdownContainer.innerHTML = '<span class="text-gray-400 italic">No cash counted yet</span>';
        }

        const submitBtn = document.getElementById('btn-submit-recon');
        if (actualCash > 0) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-purple-600', 'hover:from-blue-700', 'hover:to-purple-700', 'text-white', 'cursor-pointer');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-purple-600', 'hover:from-blue-700', 'hover:to-purple-700', 'text-white', 'cursor-pointer');
        }
    }

    function calculateReconStats() {
        updateReconUI();
    }

    function openReconConfirmModal() {
        const actual = Object.entries(reconCounts).reduce((acc, [val, count]) => acc + (parseFloat(val) * count), 0);
        const variance = actual - reconExpectedCash;

        document.getElementById('modal-expected').innerText = 'Rs ' + reconExpectedCash.toFixed(2);
        document.getElementById('modal-actual').innerText = 'Rs ' + actual.toFixed(2);

        const varEl = document.getElementById('modal-variance');
        varEl.innerText = (variance >= 0 ? '+' : '') + 'Rs ' + variance.toFixed(2);
        varEl.className = Math.abs(variance) < 10 ? 'text-green-600 font-bold' : (Math.abs(variance) < 100 ? 'text-yellow-600 font-bold' : 'text-red-600 font-bold');

        document.getElementById('recon-confirm-modal').classList.remove('hidden');
    }

    function closeReconConfirmModal() {
        document.getElementById('recon-confirm-modal').classList.add('hidden');
    }

    function submitReconciliation() {
        const notes = document.getElementById('recon-notes').value;
        const actual = Object.entries(reconCounts).reduce((acc, [val, count]) => acc + (parseFloat(val) * count), 0);

        const data = {
            expected: reconExpectedCash,
            actual: actual,
            variance: actual - reconExpectedCash,
            counts: reconCounts,
            notes: notes,
            timestamp: new Date().toISOString()
        };

        console.log('Reconciliation Submitted:', data);

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Reconciliation Submitted',
                text: 'Cash drawer closed successfully.',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            alert('Reconciliation Submitted Successfully!');
        }

        closeReconConfirmModal();
    }
</script>