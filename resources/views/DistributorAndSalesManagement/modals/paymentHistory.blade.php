{{-- resources/views/modals/payment-history.blade.php --}}

<div id="payment-history-modal" class="fixed inset-0 z-[70] hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300" onclick="closeHistoryModal()"></div>

    {{-- Sliding Panel --}}
    <div id="payment-history-panel" class="absolute top-0 right-0 h-full w-full max-w-2xl bg-white shadow-2xl overflow-hidden flex flex-col transform transition-transform duration-300 translate-x-full">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 flex-shrink-0">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" x2="12" y1="6" y2="18"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Payment History</h2>
                        <p class="text-white/80 text-sm" id="hist-inv-number"></p>
                    </div>
                </div>
                <button onclick="closeHistoryModal()" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 18 18"/></svg>
                </button>
            </div>

            {{-- Summary Stats --}}
            <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                <div class="grid grid-cols-2 gap-4 text-sm mb-3 border-b border-white/20 pb-3">
                    <div>
                        <div class="text-white/70 mb-1">Customer</div>
                        <div class="text-white font-medium" id="hist-cust-name"></div>
                    </div>
                    <div>
                        <div class="text-white/70 mb-1">Invoice Date</div>
                        <div class="text-white font-medium" id="hist-inv-date"></div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 text-sm pt-1">
                    <div>
                        <div class="text-white/70 mb-1">Total</div>
                        <div class="text-white text-lg font-bold" id="hist-total"></div>
                    </div>
                    <div>
                        <div class="text-white/70 mb-1">Paid</div>
                        <div class="text-green-300 text-lg font-bold" id="hist-paid"></div>
                    </div>
                    <div>
                        <div class="text-white/70 mb-1">Outstanding</div>
                        <div class="text-orange-300 text-lg font-bold" id="hist-due"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content List --}}
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <div id="history-empty" class="text-center py-12 bg-white rounded-2xl border-2 border-dashed border-gray-300 hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-300 mx-auto mb-4"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" x2="12" y1="6" y2="18"/></svg>
                <p class="text-gray-600 mb-2 font-medium">No payments recorded yet</p>
                <p class="text-sm text-gray-500">Payments will appear here once recorded</p>
            </div>

            <div id="history-content" class="space-y-4">
                <h3 class="text-lg text-gray-900 flex items-center gap-2 font-semibold mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Payment Records (<span id="hist-count">0</span>)
                </h3>
                <div id="history-list" class="space-y-4">
                    {{-- Items injected via JS --}}
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-white border-t-2 border-gray-200 p-6 flex justify-end flex-shrink-0">
            <button onclick="closeHistoryModal()" class="h-12 px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openHistoryModal(invoice) {
        // Validation (Same as Payment Modal)
        if (typeof invoice === 'string' || typeof invoice === 'number') {
             console.error("Error: Please pass the full invoice object to openHistoryModal.");
             return;
        }

        const modal = document.getElementById('payment-history-modal');
        const panel = document.getElementById('payment-history-panel');

        // Populate Summary
        // Handle potentially different key cases (snake_case vs camelCase) from Controller vs JS
        const invNum = invoice.invoiceNumber || invoice.invoice_number;
        const custName = invoice.customerName || invoice.customer_name;
        const invDate = invoice.invoiceDate || invoice.invoice_date;
        const grandTotal = parseFloat(invoice.grandTotal || invoice.grand_total || 0);
        const amtPaid = parseFloat(invoice.amountPaid || invoice.amount_paid || 0);
        // Calculate due if missing (or use existing)
        const amtDue = (invoice.amountDue !== undefined) ? parseFloat(invoice.amountDue) : (invoice.amount_due !== undefined ? parseFloat(invoice.amount_due) : grandTotal - amtPaid);

        document.getElementById('hist-inv-number').innerText = invNum;
        document.getElementById('hist-cust-name').innerText = custName;
        document.getElementById('hist-inv-date').innerText = new Date(invDate).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'});
        
        document.getElementById('hist-total').innerText = 'Rs ' + grandTotal.toLocaleString('en-LK', {minimumFractionDigits: 2});
        document.getElementById('hist-paid').innerText = 'Rs ' + amtPaid.toLocaleString('en-LK', {minimumFractionDigits: 2});
        
        const dueEl = document.getElementById('hist-due');
        dueEl.innerText = 'Rs ' + amtDue.toLocaleString('en-LK', {minimumFractionDigits: 2});
        dueEl.className = amtDue === 0 ? 'text-green-300 text-lg font-bold' : 'text-orange-300 text-lg font-bold';

        // Populate List
        const listContainer = document.getElementById('history-list');
        const emptyState = document.getElementById('history-empty');
        const contentDiv = document.getElementById('history-content');
        
        const payments = invoice.payments || [];
        document.getElementById('hist-count').innerText = payments.length;

        if (payments.length === 0) {
            emptyState.classList.remove('hidden');
            contentDiv.classList.add('hidden');
        } else {
            emptyState.classList.add('hidden');
            contentDiv.classList.remove('hidden');
            
            // Sort by date desc
            payments.sort((a, b) => new Date(b.paymentDate) - new Date(a.paymentDate));

            listContainer.innerHTML = payments.map(p => {
                // Determine icon based on method
                let icon = '💰'; // Default
                const m = p.method.toLowerCase();
                if(m.includes('cash')) icon = '💵';
                else if(m.includes('bank')) icon = '🏦';
                else if(m.includes('card')) icon = '💳';
                else if(m.includes('cheque')) icon = '📝';
                else if(m.includes('mobile')) icon = '📱';

                // Status Color
                let statusClass = 'bg-gray-100 text-gray-700 border-gray-200';
                if(p.status === 'completed' || !p.status) statusClass = 'bg-green-100 text-green-700 border-green-200';
                else if(p.status === 'pending') statusClass = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                else if(p.status === 'failed') statusClass = 'bg-red-100 text-red-700 border-red-200';

                return `
                <div class="bg-white border-2 border-gray-200 rounded-2xl p-5 hover:border-gray-300 transition-colors shadow-sm">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center text-2xl">
                                ${icon}
                            </div>
                            <div>
                                <div class="text-xl text-gray-900 font-bold">Rs ${parseFloat(p.amount).toLocaleString('en-LK', {minimumFractionDigits: 2})}</div>
                                <div class="text-sm text-gray-500 capitalize">${p.method.replace(/-/g, ' ')}</div>
                            </div>
                        </div>
                        <div class="px-3 py-1 rounded-lg text-xs font-bold border uppercase ${statusClass}">
                            ${p.status || 'Completed'}
                        </div>
                    </div>

                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            <span>Payment Date: ${new Date(p.paymentDate).toLocaleDateString()}</span>
                        </div>
                        ${p.referenceNumber ? `
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                            <span>Ref: ${p.referenceNumber}</span>
                        </div>` : ''}
                        ${p.notes ? `
                        <div class="pt-2 border-t border-gray-100 flex items-start gap-2 text-gray-600 italic">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                            <span>${p.notes}</span>
                        </div>` : ''}
                    </div>
                </div>`;
            }).join('');
        }

        // Show
        modal.classList.remove('hidden');
        setTimeout(() => {
            panel.classList.remove('translate-x-full');
        }, 10);
    }

    function closeHistoryModal() {
        const modal = document.getElementById('payment-history-modal');
        const panel = document.getElementById('payment-history-panel');
        
        panel.classList.add('translate-x-full');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>