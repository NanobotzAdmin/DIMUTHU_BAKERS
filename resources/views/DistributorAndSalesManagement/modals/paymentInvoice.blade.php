{{-- resources/views/modals/record-payment.blade.php --}}

<div id="payment-modal" class="fixed inset-0 z-[60] hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300" onclick="closePaymentModal()"></div>

    {{-- Content --}}
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="payment-modal-content" class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0">
            
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-teal-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl text-gray-900 font-bold">Record Payment</h2>
                        <p class="text-sm text-gray-600" id="pay-inv-number"></p>
                    </div>
                </div>
                <button onclick="closePaymentModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 18 18"/></svg>
                </button>
            </div>

            {{-- Form --}}
            <div class="p-6">
                <form id="payment-form" onsubmit="submitPayment(event)">
                    <div class="space-y-6">
                        
                        {{-- Summary Card --}}
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl p-4">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Invoice Total</p>
                                    <p class="text-lg text-gray-900 font-bold" id="pay-summ-total"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Already Paid</p>
                                    <p class="text-lg text-green-600 font-bold" id="pay-summ-paid"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Balance Due</p>
                                    <p class="text-lg text-red-600 font-bold" id="pay-summ-due"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Amount Input --}}
                        <div>
                            <label class="block text-sm text-gray-600 mb-2 font-medium">Payment Amount <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">$</span>
                                <input type="number" id="pay-amount" step="0.01" min="0" required class="w-full h-12 pl-10 pr-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500 text-lg font-bold text-gray-800">
                            </div>
                            
                            {{-- Quick Amount Buttons --}}
                            <div class="flex gap-2 mt-2" id="quick-pay-btns">
                                {{-- Injected via JS --}}
                            </div>
                        </div>

                        {{-- Date --}}
                        <div>
                            <label class="block text-sm text-gray-600 mb-2 font-medium">Payment Date <span class="text-red-500">*</span></label>
                            <input type="date" id="pay-date" required class="w-full h-12 px-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                        </div>

                        {{-- Method --}}
                        <div>
                            <label class="block text-sm text-gray-600 mb-2 font-medium">Payment Method <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach(['cash' => 'Cash', 'bank-transfer' => 'Bank Transfer', 'credit-card' => 'Credit Card', 'debit-card' => 'Debit Card', 'cheque' => 'Cheque', 'mobile-payment' => 'Mobile Payment'] as $val => $label)
                                    <button type="button" onclick="setPayMethod('{{$val}}', this)" class="pay-method-btn h-12 px-2 border-2 border-gray-300 text-gray-700 hover:border-gray-400 rounded-lg text-sm transition-all {{ $val === 'cash' ? 'active border-emerald-500 bg-emerald-50 text-emerald-700' : '' }}" data-value="{{$val}}">
                                        {{$label}}
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" id="pay-method" value="cash">
                        </div>

                        {{-- References --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Reference Number</label>
                                <input type="text" id="pay-ref" placeholder="TRF-123, CHQ-456" class="w-full h-12 px-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Receipt Number</label>
                                <input type="text" id="pay-receipt" class="w-full h-12 px-4 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-sm text-gray-600 mb-2">Notes</label>
                            <textarea id="pay-notes" rows="3" placeholder="Additional notes..." class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500 resize-none"></textarea>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <button type="button" onclick="closePaymentModal()" class="h-11 px-6 text-gray-700 hover:bg-gray-100 rounded-lg transition-all font-medium">Cancel</button>
                            <button type="submit" id="btn-pay-submit" class="h-11 px-6 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-lg flex items-center gap-2 transition-all font-bold shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Record Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let activePaymentInvoice = null;

    function openPaymentModal(invoice) {
        // Validation to ensure object is passed
        if (typeof invoice === 'string' || typeof invoice === 'number') {
             console.error("Error: Please pass the full invoice object, not just the ID.");
             return;
        }

        activePaymentInvoice = invoice;
        const modal = document.getElementById('payment-modal');
        const content = document.getElementById('payment-modal-content');

        // Populate Fields
        // Use safe optional chaining or fallback to '0' to prevent JS errors if data is missing
        document.getElementById('pay-inv-number').innerText = invoice.invoiceNumber || invoice.invoice_number;
        
        const grandTotal = invoice.grandTotal || invoice.grand_total || 0;
        const amountPaid = invoice.amountPaid || invoice.amount_paid || 0;
        const amountDue = invoice.amountDue || invoice.amount_due || 0;

        document.getElementById('pay-summ-total').innerText = 'Rs ' + parseFloat(grandTotal).toLocaleString('en-LK', {minimumFractionDigits: 2});
        document.getElementById('pay-summ-paid').innerText = 'Rs ' + parseFloat(amountPaid).toLocaleString('en-LK', {minimumFractionDigits: 2});
        document.getElementById('pay-summ-due').innerText = 'Rs ' + parseFloat(amountDue).toLocaleString('en-LK', {minimumFractionDigits: 2});

        // Form Defaults
        document.getElementById('pay-amount').value = parseFloat(amountDue).toFixed(2);
        document.getElementById('pay-date').value = new Date().toISOString().split('T')[0];
        document.getElementById('pay-receipt').value = 'RCP-' + Date.now();
        document.getElementById('pay-ref').value = '';
        document.getElementById('pay-notes').value = '';
        
        // Reset Method Buttons
        setPayMethod('cash', document.querySelector('.pay-method-btn[data-value="cash"]'));

        // Quick Buttons
        const qBtns = document.getElementById('quick-pay-btns');
        if(qBtns) {
            qBtns.innerHTML = `
                <button type="button" onclick="setPayAmount(${amountDue})" class="flex-1 h-8 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition-all border border-gray-200 font-medium">Full Amount</button>
                <button type="button" onclick="setPayAmount(${amountDue / 2})" class="flex-1 h-8 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition-all border border-gray-200 font-medium">50%</button>
                <button type="button" onclick="setPayAmount(${amountDue / 4})" class="flex-1 h-8 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition-all border border-gray-200 font-medium">25%</button>
            `;
        }

        // Show Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    // ... (Keep the rest of the functions: closePaymentModal, setPayAmount, etc.) ...
    
    function closePaymentModal() {
        const modal = document.getElementById('payment-modal');
        const content = document.getElementById('payment-modal-content');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    function setPayAmount(val) {
        document.getElementById('pay-amount').value = val.toFixed(2);
    }

    function setPayMethod(val, btn) {
        document.getElementById('pay-method').value = val;
        document.querySelectorAll('.pay-method-btn').forEach(b => {
            b.className = 'pay-method-btn h-12 px-2 border-2 border-gray-300 text-gray-700 hover:border-gray-400 rounded-lg text-sm transition-all';
        });
        if(btn) btn.className = 'pay-method-btn h-12 px-2 border-2 rounded-lg text-sm transition-all border-emerald-500 bg-emerald-50 text-emerald-700 font-bold';
    }

    function submitPayment(e) {
        e.preventDefault();
        // ... (rest of logic remains the same)
        const amount = parseFloat(document.getElementById('pay-amount').value);
        // Ensure you handle cases where activePaymentInvoice might have different key casing (snake vs camel)
        const due = parseFloat(activePaymentInvoice.amountDue || activePaymentInvoice.amount_due);

        if(amount <= 0) {
            Swal.fire('Error', 'Amount must be greater than 0', 'error');
            return;
        }

        if(amount > due) {
            Swal.fire({
                title: 'Overpayment?',
                text: 'The amount entered exceeds the balance due. Continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, record it!'
            }).then((result) => {
                if(result.isConfirmed) processPaymentSubmission();
            });
        } else {
            processPaymentSubmission();
        }
    }

    function processPaymentSubmission() {
        const btn = document.getElementById('btn-pay-submit');
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Recording...';
        btn.disabled = true;

        setTimeout(() => {
            Swal.fire('Success', 'Payment recorded successfully', 'success');
            closePaymentModal();
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1000);
    }
</script>