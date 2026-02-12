{{-- resources/views/modals/view-invoice.blade.php --}}

<div id="view-invoice-modal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300" onclick="closeViewInvoiceModal()"></div>

    {{-- Modal Content --}}
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="view-invoice-content" class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col transform transition-all duration-300 scale-95 opacity-0">
            
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-emerald-50 to-teal-50 flex-shrink-0">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4 flex-1">
                        {{-- Icon --}}
                        <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center flex-shrink-0 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        </div>

                        {{-- Title & Status --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2 flex-wrap">
                                <h2 class="text-2xl text-gray-900 font-bold" id="view-inv-number"></h2>
                                <span id="view-inv-status-badge" class="border px-3 py-1 rounded-lg flex items-center gap-1.5 text-sm font-medium"></span>
                                <span id="view-inv-overdue-badge" class="hidden bg-red-100 text-red-700 border border-red-300 px-3 py-1 rounded-lg flex items-center gap-1.5 text-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                                    <span id="view-inv-overdue-days"></span> days overdue
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-sm text-gray-600 flex-wrap">
                                <span>Issued: <span id="view-inv-date"></span></span>
                                <span class="text-gray-400">•</span>
                                <span>Due: <span id="view-inv-due"></span></span>
                                <span class="text-gray-400 hidden" id="view-inv-sales-sep">•</span>
                                <span class="hidden" id="view-inv-sales">Sales: <span id="view-inv-sales-name"></span></span>
                            </div>
                        </div>
                    </div>

                    <button onclick="closeViewInvoiceModal()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 18 18"/></svg>
                    </button>
                </div>
            </div>

            {{-- Action Bar --}}
            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between flex-shrink-0 flex-wrap gap-3">
                <div class="flex gap-2">
                    <button onclick="alert('Downloading PDF...')" class="h-9 px-4 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg flex items-center gap-2 text-sm transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        Download PDF
                    </button>
                    <button onclick="window.print()" class="h-9 px-4 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg flex items-center gap-2 text-sm transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                        Print
                    </button>
                </div>

                <div class="flex gap-2" id="view-inv-actions">
                    {{-- Dynamic Buttons injected via JS --}}
                </div>
            </div>

            {{-- Tabs --}}
            <div class="px-6 border-b border-gray-200 flex gap-1 bg-white flex-shrink-0">
                <button onclick="switchInvTab('details')" id="tab-btn-details" class="flex items-center gap-2 px-4 py-3 border-b-2 transition-all border-emerald-600 text-emerald-600 font-medium text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                    Details
                </button>
                <button onclick="switchInvTab('preview')" id="tab-btn-preview" class="flex items-center gap-2 px-4 py-3 border-b-2 transition-all border-transparent text-gray-600 hover:text-gray-900 font-medium text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    Preview
                </button>
                <button onclick="switchInvTab('payments')" id="tab-btn-payments" class="flex items-center gap-2 px-4 py-3 border-b-2 transition-all border-transparent text-gray-600 hover:text-gray-900 font-medium text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" x2="12" y1="6" y2="18"/></svg>
                    Payments
                    <span id="view-inv-payment-count" class="bg-emerald-100 text-emerald-700 text-xs px-2 py-0.5 rounded-full hidden">0</span>
                </button>
            </div>

            {{-- Tab Content Area --}}
            <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                
                {{-- TAB: DETAILS --}}
                <div id="tab-content-details" class="space-y-6">
                    {{-- Payment Status Card --}}
                    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg text-gray-900 font-semibold">Payment Status</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm text-gray-600 block">Total Amount</label>
                                <p class="text-2xl font-bold text-gray-900" id="det-total-amount">Rs 0.00</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 block">Amount Paid</label>
                                <p class="text-2xl font-bold text-green-600" id="det-paid-amount">Rs 0.00</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600 block">Amount Due</label>
                                <p class="text-2xl font-bold text-red-600" id="det-due-amount">Rs 0.00</p>
                            </div>
                        </div>
                        {{-- Progress Bar --}}
                        <div id="det-progress-container" class="mt-4 hidden">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-600">Payment Progress</span>
                                <span class="text-gray-900 font-medium" id="det-progress-text">0%</span>
                            </div>
                            <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                                <div id="det-progress-bar" class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full transition-all" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Info --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold">
                            <span id="det-cust-icon"></span> Customer Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><label class="text-gray-500">Customer Name</label><p class="text-gray-900 font-medium" id="det-cust-name"></p></div>
                            <div><label class="text-gray-500">Customer Type</label><p class="text-gray-900 capitalize" id="det-cust-type"></p></div>
                            <div><label class="text-gray-500">Phone</label><p class="text-gray-900" id="det-cust-phone"></p></div>
                            <div><label class="text-gray-500">Email</label><p class="text-gray-900" id="det-cust-email"></p></div>
                            <div class="md:col-span-2"><label class="text-gray-500">Billing Address</label><p class="text-gray-900" id="det-cust-address"></p></div>
                        </div>
                    </div>

                    {{-- Invoice Data --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            Invoice Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div><label class="text-gray-500">Invoice Date</label><p class="text-gray-900" id="det-inv-date"></p></div>
                            <div><label class="text-gray-500">Due Date</label><p class="text-gray-900" id="det-inv-due"></p></div>
                            <div><label class="text-gray-500">Payment Terms</label><p class="text-gray-900" id="det-inv-terms"></p></div>
                            <div id="det-src-quote-div" class="hidden"><label class="text-gray-500">Source Quotation</label><p class="text-emerald-600 font-medium" id="det-src-quote"></p></div>
                            <div id="det-src-order-div" class="hidden"><label class="text-gray-500">Source Order</label><p class="text-emerald-600 font-medium" id="det-src-order"></p></div>
                        </div>
                    </div>

                    {{-- Line Items --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><line x1="16.5" x2="7.5" y1="9.4" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" x2="12" y1="22.08" y2="12"/></svg>
                            Line Items
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200 text-gray-600">
                                    <tr>
                                        <th class="text-left py-3 px-4">Product/Service</th>
                                        <th class="text-right py-3 px-4">Qty</th>
                                        <th class="text-right py-3 px-4">Price</th>
                                        <th class="text-right py-3 px-4">Disc</th>
                                        <th class="text-right py-3 px-4">Tax</th>
                                        <th class="text-right py-3 px-4">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="det-line-items-body" class="divide-y divide-gray-100"></tbody>
                                <tfoot class="font-medium text-gray-700">
                                    <tr><td colspan="5" class="text-right py-2 px-4 pt-4">Subtotal</td><td class="text-right py-2 px-4 pt-4" id="det-ft-sub"></td></tr>
                                    <tr id="det-ft-disc-row" class="hidden"><td colspan="5" class="text-right py-1 px-4">Discount</td><td class="text-right py-1 px-4 text-red-600" id="det-ft-disc"></td></tr>
                                    <tr><td colspan="5" class="text-right py-1 px-4">Tax</td><td class="text-right py-1 px-4" id="det-ft-tax"></td></tr>
                                    <tr class="text-lg text-emerald-600 font-bold border-t border-gray-200"><td colspan="5" class="text-right py-3 px-4">Grand Total</td><td class="text-right py-3 px-4" id="det-ft-grand"></td></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- TAB: PREVIEW --}}
                <div id="tab-content-preview" class="hidden flex justify-center">
                    <div class="bg-white border border-gray-300 shadow-lg p-8 w-full max-w-[210mm] min-h-[297mm] text-sm text-gray-800">
                        {{-- PDF-like Layout --}}
                        <div class="border-b-2 border-emerald-600 pb-6 mb-6 flex justify-between items-start">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">INVOICE</h1>
                                <p class="text-xl text-emerald-600 font-bold" id="prev-inv-num"></p>
                            </div>
                            <div class="text-right text-gray-600">
                                <p class="font-bold text-gray-900">BakeryMate ERP</p>
                                <p>www.bakerymate.lk</p>
                                <p>+94 11 234 5678</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-8 mb-8">
                            <div>
                                <h3 class="text-gray-500 font-bold mb-2 uppercase text-xs">Bill To:</h3>
                                <p class="font-bold text-gray-900 text-lg" id="prev-cust-name"></p>
                                <p id="prev-cust-addr" class="whitespace-pre-line"></p>
                                <p id="prev-cust-contact"></p>
                            </div>
                            <div class="text-right space-y-1">
                                <div class="flex justify-end gap-4"><span class="text-gray-500 font-medium">Date:</span><span id="prev-date" class="font-bold"></span></div>
                                <div class="flex justify-end gap-4"><span class="text-gray-500 font-medium">Due:</span><span id="prev-due" class="font-bold"></span></div>
                            </div>
                        </div>

                        <table class="w-full mb-8">
                            <thead class="bg-emerald-50 text-emerald-900 uppercase text-xs font-bold">
                                <tr>
                                    <th class="text-left p-3">Description</th>
                                    <th class="text-right p-3">Qty</th>
                                    <th class="text-right p-3">Price</th>
                                    <th class="text-right p-3">Total</th>
                                </tr>
                            </thead>
                            <tbody id="prev-table-body" class="divide-y divide-gray-200"></tbody>
                            <tfoot>
                                <tr><td colspan="3" class="text-right p-2 pt-4 font-bold">Subtotal:</td><td class="text-right p-2 pt-4" id="prev-sub"></td></tr>
                                <tr><td colspan="3" class="text-right p-2 font-bold">Discount:</td><td class="text-right p-2 text-red-600" id="prev-disc"></td></tr>
                                <tr><td colspan="3" class="text-right p-2 font-bold">Tax:</td><td class="text-right p-2" id="prev-tax"></td></tr>
                                <tr class="bg-emerald-50 text-emerald-900"><td colspan="3" class="text-right p-3 font-bold text-lg">Total Due:</td><td class="text-right p-3 font-bold text-lg" id="prev-grand"></td></tr>
                            </tfoot>
                        </table>

                        <div class="border-t pt-4 text-gray-500 text-xs">
                            <p class="font-bold text-gray-700 mb-1">Terms & Conditions:</p>
                            <p id="prev-terms">Payment is due within the specified time. Please quote invoice number when remitting funds.</p>
                        </div>
                    </div>
                </div>

                {{-- TAB: PAYMENTS --}}
                <div id="tab-content-payments" class="hidden space-y-4">
                    <div id="pay-empty-state" class="text-center py-12 hidden">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" x2="12" y1="6" y2="18"/></svg>
                        </div>
                        <h3 class="text-lg text-gray-600 mb-2">No payments recorded</h3>
                        <p class="text-gray-500">Payment history will appear here once payments are made.</p>
                    </div>

                    <div id="pay-content">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6">
                            <h3 class="text-lg mb-4 font-semibold">Payment Summary</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div><label class="text-xs text-gray-500 uppercase">Total Payments</label><p class="text-2xl text-green-600 font-bold" id="pay-summ-total"></p></div>
                                <div><label class="text-xs text-gray-500 uppercase">Remaining</label><p class="text-2xl text-red-600 font-bold" id="pay-summ-rem"></p></div>
                                <div><label class="text-xs text-gray-500 uppercase">Invoice Total</label><p class="text-2xl text-gray-900 font-bold" id="pay-summ-inv"></p></div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-xl p-6">
                            <h3 class="text-lg mb-4 font-semibold">History</h3>
                            <div class="space-y-3" id="pay-history-list">
                                {{-- Payments injected here --}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // --- HELPERS ---
    const formatCurrency = (val) => 'Rs ' + parseFloat(val).toLocaleString('en-LK', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    const formatDate = (str) => new Date(str).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'});
    const formatDateTime = (str) => new Date(str).toLocaleString('en-US', {month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true});

    const statusConfigs = {
        'draft': { color: 'bg-gray-100 text-gray-700 border-gray-300', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>', label: 'Draft' },
        'pending': { color: 'bg-yellow-100 text-yellow-700 border-yellow-300', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>', label: 'Pending' },
        'sent': { color: 'bg-blue-100 text-blue-700 border-blue-300', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>', label: 'Sent' },
        'partially-paid': { color: 'bg-indigo-100 text-indigo-700 border-indigo-300', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>', label: 'Partially Paid' },
        'paid': { color: 'bg-green-100 text-green-700 border-green-300', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>', label: 'Paid' },
        'overdue': { color: 'bg-red-100 text-red-700 border-red-300', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>', label: 'Overdue' },
        'cancelled': { color: 'bg-slate-100 text-slate-700 border-slate-300', icon: '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 18 18"/></svg>', label: 'Cancelled' }
    };

    let currentInvoice = null;

    // --- MAIN FUNCTIONS ---
    function openViewInvoiceModal(button) {
        // Parse data
        currentInvoice = JSON.parse(button.dataset.invoice);
        const modal = document.getElementById('view-invoice-modal');
        const content = document.getElementById('view-invoice-content');

        // Reset Tab
        switchInvTab('details');

        // Populate Header
        document.getElementById('view-inv-number').innerText = currentInvoice.invoiceNumber;
        const conf = statusConfigs[currentInvoice.status] || statusConfigs['draft'];
        
        const badge = document.getElementById('view-inv-status-badge');
        badge.className = `border px-3 py-1 rounded-lg flex items-center gap-1.5 text-sm font-medium ${conf.color}`;
        badge.innerHTML = `${conf.icon} ${conf.label}`;

        // Overdue
        const odBadge = document.getElementById('view-inv-overdue-badge');
        if(currentInvoice.daysOverdue > 0) {
            odBadge.classList.remove('hidden');
            document.getElementById('view-inv-overdue-days').innerText = currentInvoice.daysOverdue;
        } else {
            odBadge.classList.add('hidden');
        }

        document.getElementById('view-inv-date').innerText = formatDateTime(currentInvoice.invoiceDate);
        document.getElementById('view-inv-due').innerText = formatDate(currentInvoice.dueDate);
        
        if(currentInvoice.salesPersonName) {
            document.getElementById('view-inv-sales').classList.remove('hidden');
            document.getElementById('view-inv-sales-sep').classList.remove('hidden');
            document.getElementById('view-inv-sales-name').innerText = currentInvoice.salesPersonName;
        } else {
            document.getElementById('view-inv-sales').classList.add('hidden');
            document.getElementById('view-inv-sales-sep').classList.add('hidden');
        }

        // Action Buttons
        const actionsDiv = document.getElementById('view-inv-actions');
        let btns = '';
        if(currentInvoice.amountDue > 0 && currentInvoice.status !== 'cancelled') {
            btns += `<button onclick="alert('Record Payment')" class="h-9 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white rounded-lg flex items-center gap-2 text-sm transition-all"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg> Record Payment</button>`;
        }
        if((currentInvoice.status === 'draft' || currentInvoice.status === 'pending') && currentInvoice.customerEmail) {
            btns += `<button onclick="alert('Send Email')" class="h-9 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 text-sm transition-all"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg> Email</button>`;
        }
        actionsDiv.innerHTML = btns;

        // Payment Count Badge
        const payCount = document.getElementById('view-inv-payment-count');
        if(currentInvoice.payments && currentInvoice.payments.length > 0) {
            payCount.innerText = currentInvoice.payments.length;
            payCount.classList.remove('hidden');
        } else {
            payCount.classList.add('hidden');
        }

        // Populate Content (Details Tab Logic)
        renderDetailsTab();

        // Show Modal
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeViewInvoiceModal() {
        const modal = document.getElementById('view-invoice-modal');
        const content = document.getElementById('view-invoice-content');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function switchInvTab(tabId) {
        // Reset Tabs
        ['details', 'preview', 'payments'].forEach(t => {
            const btn = document.getElementById(`tab-btn-${t}`);
            const content = document.getElementById(`tab-content-${t}`);
            
            if(t === tabId) {
                btn.classList.replace('border-transparent', 'border-emerald-600');
                btn.classList.replace('text-gray-600', 'text-emerald-600');
                content.classList.remove('hidden');
            } else {
                btn.classList.replace('border-emerald-600', 'border-transparent');
                btn.classList.replace('text-emerald-600', 'text-gray-600');
                content.classList.add('hidden');
            }
        });

        if(tabId === 'preview') renderPreviewTab();
        if(tabId === 'payments') renderPaymentsTab();
    }

    // --- RENDER LOGIC ---
    function renderDetailsTab() {
        // Payment Status
        document.getElementById('det-total-amount').innerText = formatCurrency(currentInvoice.grandTotal);
        document.getElementById('det-paid-amount').innerText = formatCurrency(currentInvoice.amountPaid);
        document.getElementById('det-due-amount').innerText = formatCurrency(currentInvoice.amountDue);

        // Progress
        const progContainer = document.getElementById('det-progress-container');
        if(currentInvoice.amountPaid > 0) {
            progContainer.classList.remove('hidden');
            const percent = (currentInvoice.amountPaid / currentInvoice.grandTotal) * 100;
            document.getElementById('det-progress-text').innerText = percent.toFixed(1) + '%';
            document.getElementById('det-progress-bar').style.width = percent + '%';
        } else {
            progContainer.classList.add('hidden');
        }

        // Customer
        document.getElementById('det-cust-icon').innerHTML = currentInvoice.customerType === 'corporate' 
            ? `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/><path d="M8 14h.01"/><path d="M16 14h.01"/></svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`;
        
        document.getElementById('det-cust-name').innerText = currentInvoice.customerName;
        document.getElementById('det-cust-type').innerText = currentInvoice.customerType;
        document.getElementById('det-cust-phone').innerText = currentInvoice.customerPhone || 'N/A';
        document.getElementById('det-cust-email').innerText = currentInvoice.customerEmail || 'N/A';
        
        const addr = currentInvoice.billingAddress;
        document.getElementById('det-cust-address').innerText = typeof addr === 'string' ? addr : (addr ? `${addr.street}, ${addr.city}` : 'N/A');

        // Invoice Meta
        document.getElementById('det-inv-date').innerText = formatDate(currentInvoice.invoiceDate);
        document.getElementById('det-inv-due').innerText = formatDate(currentInvoice.dueDate);
        document.getElementById('det-inv-terms').innerText = currentInvoice.paymentTerms;

        if(currentInvoice.quotationId) {
            document.getElementById('det-src-quote-div').classList.remove('hidden');
            document.getElementById('det-src-quote').innerText = currentInvoice.quotationId;
        }
        if(currentInvoice.orderId) {
            document.getElementById('det-src-order-div').classList.remove('hidden');
            document.getElementById('det-src-order').innerText = currentInvoice.orderId;
        }

        // Lines
        const linesBody = document.getElementById('det-line-items-body');
        linesBody.innerHTML = currentInvoice.lineItems.map(item => `
            <tr>
                <td class="py-3 px-4">
                    <p class="text-gray-900 font-medium">${item.productName}</p>
                    ${item.description ? `<p class="text-xs text-gray-500">${item.description}</p>` : ''}
                </td>
                <td class="text-right py-3 px-4">${item.quantity} ${item.unit}</td>
                <td class="text-right py-3 px-4">${formatCurrency(item.unitPrice)}</td>
                <td class="text-right py-3 px-4 text-gray-600">${item.discount > 0 ? '-' + formatCurrency(item.discount) : '-'}</td>
                <td class="text-right py-3 px-4 text-gray-600">${formatCurrency(item.tax)}</td>
                <td class="text-right py-3 px-4 font-medium text-gray-900">${formatCurrency(item.lineTotal)}</td>
            </tr>
        `).join('');

        // Footer Totals
        document.getElementById('det-ft-sub').innerText = formatCurrency(currentInvoice.subtotal);
        document.getElementById('det-ft-tax').innerText = formatCurrency(currentInvoice.tax);
        document.getElementById('det-ft-grand').innerText = formatCurrency(currentInvoice.grandTotal);
        
        if(currentInvoice.discount > 0) {
            document.getElementById('det-ft-disc-row').classList.remove('hidden');
            document.getElementById('det-ft-disc').innerText = '-' + formatCurrency(currentInvoice.discount);
        } else {
            document.getElementById('det-ft-disc-row').classList.add('hidden');
        }
    }

    function renderPreviewTab() {
        document.getElementById('prev-inv-num').innerText = currentInvoice.invoiceNumber;
        document.getElementById('prev-cust-name').innerText = currentInvoice.customerName;
        
        const addr = currentInvoice.billingAddress;
        document.getElementById('prev-cust-addr').innerText = typeof addr === 'string' ? addr : (addr ? `${addr.street}\n${addr.city}, ${addr.district}` : '');
        document.getElementById('prev-cust-contact').innerText = [currentInvoice.customerPhone, currentInvoice.customerEmail].filter(Boolean).join(' | ');

        document.getElementById('prev-date').innerText = formatDate(currentInvoice.invoiceDate);
        document.getElementById('prev-due').innerText = formatDate(currentInvoice.dueDate);

        // Table
        document.getElementById('prev-table-body').innerHTML = currentInvoice.lineItems.map(item => `
            <tr>
                <td class="p-3">
                    <div class="font-bold text-gray-900">${item.productName}</div>
                    <div class="text-xs text-gray-500">${item.description || ''}</div>
                </td>
                <td class="text-right p-3">${item.quantity}</td>
                <td class="text-right p-3">${formatCurrency(item.unitPrice)}</td>
                <td class="text-right p-3">${formatCurrency(item.lineTotal)}</td>
            </tr>
        `).join('');

        document.getElementById('prev-sub').innerText = formatCurrency(currentInvoice.subtotal);
        document.getElementById('prev-disc').innerText = '-' + formatCurrency(currentInvoice.discount);
        document.getElementById('prev-tax').innerText = formatCurrency(currentInvoice.tax);
        document.getElementById('prev-grand').innerText = formatCurrency(currentInvoice.grandTotal);
        document.getElementById('prev-terms').innerText = currentInvoice.termsAndConditions || "Payment is due within the specified time.";
    }

    function renderPaymentsTab() {
        const empty = document.getElementById('pay-empty-state');
        const content = document.getElementById('pay-content');

        if(!currentInvoice.payments || currentInvoice.payments.length === 0) {
            empty.classList.remove('hidden');
            content.classList.add('hidden');
            return;
        }

        empty.classList.add('hidden');
        content.classList.remove('hidden');

        document.getElementById('pay-summ-total').innerText = formatCurrency(currentInvoice.amountPaid);
        document.getElementById('pay-summ-rem').innerText = formatCurrency(currentInvoice.amountDue);
        document.getElementById('pay-summ-inv').innerText = formatCurrency(currentInvoice.grandTotal);

        document.getElementById('pay-history-list').innerHTML = currentInvoice.payments.map(p => `
            <div class="border border-gray-200 rounded-lg p-4 bg-white shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <div>
                            <p class="text-gray-900 font-bold">${formatCurrency(p.amount)}</p>
                            <p class="text-sm text-gray-600">${formatDateTime(p.paymentDate)}</p>
                        </div>
                    </div>
                    <span class="bg-emerald-100 text-emerald-700 border border-emerald-300 px-3 py-1 rounded-lg text-xs font-medium capitalize">
                        ${p.method.replace('-', ' ')}
                    </span>
                </div>
                ${p.referenceNumber ? `<p class="text-sm text-gray-600 mt-1">Ref: ${p.referenceNumber}</p>` : ''}
                ${p.notes ? `<p class="text-sm text-gray-500 italic mt-1">${p.notes}</p>` : ''}
            </div>
        `).join('');
    }
</script>