{{-- resources/views/quotations/modals/detail.blade.php --}}

<div id="quotationDetailModal"
    class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 overflow-y-auto backdrop-blur-sm transition-all duration-300">
    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col my-8 transform transition-all scale-100">

        {{-- ================= HEADER ================= --}}
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50 flex-shrink-0">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-4 flex-1">
                    {{-- Icon --}}
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                    </div>

                    {{-- Title & Status --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h2 class="text-2xl text-gray-900 font-bold" id="modal-quotation-number">QT-000</h2>

                            {{-- Dynamic Status Badge --}}
                            <span id="modal-status-badge"
                                class="border px-3 py-1 rounded-lg flex items-center gap-1.5 text-xs font-bold">
                                {{-- Icon and Label injected via JS --}}
                            </span>

                            <span id="modal-version-badge"
                                class="hidden bg-blue-100 text-blue-700 border border-blue-300 px-3 py-1 rounded-lg text-xs font-semibold">
                                Version <span id="modal-version-number">1</span>
                            </span>

                            <span id="modal-expiry-badge"
                                class="hidden bg-orange-100 text-orange-700 border border-orange-300 px-3 py-1 rounded-lg flex items-center gap-1.5 text-xs font-semibold">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                    </path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                Expires in <span id="modal-expiry-days">0</span> days
                            </span>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span>Created: <span id="modal-created-at">...</span></span>
                            <span class="text-gray-400">•</span>
                            <span>Sales: <span id="modal-sales-person">...</span></span>
                        </div>
                    </div>

                    {{-- Close Button --}}
                    <button onclick="closeDetailModal()"
                        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ================= ACTION BAR ================= --}}
        <div class="px-6 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between flex-shrink-0">
            <div class="flex gap-2">
                <button onclick="downloadQuotationPdf()"
                    class="h-9 px-4 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg flex items-center gap-2 text-sm transition-all">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Download PDF
                </button>
                <button onclick="window.print()"
                    class="h-9 px-4 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg flex items-center gap-2 text-sm transition-all">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    Print
                </button>
                <button onclick="alert('Link Copied')"
                    class="h-9 px-4 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg flex items-center gap-2 text-sm transition-all">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    Copy Link
                </button>
            </div>

            <div class="flex gap-2" id="modal-actions-container">
                {{-- Dynamic Actions injected via JS based on status --}}
            </div>
        </div>

        {{-- ================= TABS ================= --}}
        <div class="px-6 border-b border-gray-200 flex gap-1 bg-white flex-shrink-0">
            <button onclick="switchTab('details')" id="tab-btn-details"
                class="flex items-center gap-2 px-4 py-3 border-b-2 border-purple-600 text-purple-600 transition-all font-medium">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                    <polyline points="14 2 14 8 20 8" />
                </svg>
                Details
            </button>
            <button onclick="switchTab('preview')" id="tab-btn-preview"
                class="flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-600 hover:text-gray-900 transition-all font-medium">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                Preview
            </button>
            <button onclick="switchTab('history')" id="tab-btn-history"
                class="flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-600 hover:text-gray-900 transition-all font-medium">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                History
            </button>
        </div>

        {{-- ================= CONTENT AREA ================= --}}
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50">

            {{-- TAB: DETAILS --}}
            <div id="tab-content-details" class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">
                        <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Customer Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Customer Name</label>
                            <p class="text-gray-900 font-medium" id="modal-customer-name">...</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Customer Type</label>
                            <p class="text-gray-900 capitalize" id="modal-customer-type">...</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Phone</label>
                            <p class="text-gray-900" id="modal-customer-phone">...</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Email</label>
                            <p class="text-gray-900" id="modal-customer-email">...</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">
                        <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Quotation Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">Quotation Date</label>
                            <p class="text-gray-900" id="modal-quotation-date">...</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Valid Until</label>
                            <p class="text-gray-900" id="modal-valid-until">...</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Event Type</label>
                            <p class="text-gray-900 capitalize" id="modal-event-type">...</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">
                        <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                            </path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        Line Items
                    </h3>
                    <div class="overflow-x-auto rounded-lg border border-gray-100">
                        <table class="w-full">
                            <thead class="bg-purple-50">
                                <tr>
                                    <th class="text-left py-3 px-4 text-sm font-semibold text-gray-600">Product/Service
                                    </th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Qty</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Unit Price</th>
                                    <th class="text-right py-3 px-4 text-sm font-semibold text-gray-600">Total</th>
                                </tr>
                            </thead>
                            <tbody id="modal-line-items-body" class="divide-y divide-gray-100">
                                {{-- Rows injected via JS --}}
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="text-right py-3 px-4 text-lg font-bold">Grand Total</td>
                                    <td class="text-right py-3 px-4 text-xl font-bold text-purple-600"
                                        id="modal-grand-total">...</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg mb-4 font-semibold text-gray-800">Terms & Conditions</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">Payment Terms</label>
                            <p class="text-gray-900 whitespace-pre-line" id="details-payment-terms"></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Terms & Conditions</label>
                            <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line"
                                id="details-terms-conditions"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: PREVIEW --}}
            <div id="tab-content-preview" class="hidden">
                <div class="bg-white border border-gray-200 rounded-xl p-8 max-w-4xl mx-auto shadow-sm">
                    {{-- Header --}}
                    <div class="border-b-2 border-purple-600 pb-4 mb-4">
                        <div class="flex justify-between items-end">
                            {{-- Company Logo & Details --}}
                            <div class="text-left">
                                <div id="preview-company-logo-container" class="mb-2 hidden">
                                    <img id="preview-company-logo" src="" alt="Company Logo"
                                        class="max-h-16 object-contain">
                                </div>
                                <div>
                                    <p class="text-gray-900 font-bold text-lg leading-tight" id="preview-company-name">
                                        BakeryMate ERP</p>
                                    <p class="text-sm text-gray-500 leading-tight" id="preview-company-address">
                                        www.bakerymate.lk</p>
                                    <p class="text-sm text-gray-500 leading-tight" id="preview-company-phone">+94 11 234
                                        5678</p>
                                    <p class="text-sm text-gray-500 leading-tight" id="preview-company-email"></p>
                                </div>
                            </div>

                            {{-- Quotation Title --}}
                            <div class="text-right">
                                <div class="text-right mb-2">
                                    <h1 class="text-3xl font-bold text-gray-900 leading-none mb-1">QUOTATION</h1>
                                    <p class="text-lg text-purple-600 font-medium" id="preview-quotation-number">...</p>
                                </div>
                                <div class="text-right space-y-1">
                                    <div class="flex justify-end gap-4">
                                        <span class="text-gray-500">Date:</span>
                                        <span class="text-gray-900 font-medium" id="preview-date">...</span>
                                    </div>
                                    <div class="flex justify-end gap-4">
                                        <span class="text-gray-500">Valid Until:</span>
                                        <span class="text-gray-900 font-medium" id="preview-valid-until">...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bill To --}}
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Bill To:</h3>
                            <p class="text-gray-900 font-medium text-lg" id="preview-customer-name">...</p>
                            <p class="text-gray-600" id="preview-customer-email">...</p>
                            <p class="text-gray-600" id="preview-customer-phone">...</p>
                        </div>

                    </div>

                    {{-- Table --}}
                    <table class="w-full mb-8">
                        <thead class="bg-gray-50 border-y border-gray-200">
                            <tr>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600">Description</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-600">Qty</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-600">Price</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-600">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="preview-table-body" class="text-gray-700">
                            {{-- Injected JS --}}
                        </tbody>
                        <tfoot class="border-t border-gray-200">
                            <tr>
                                <td colspan="3" class="text-right py-3 px-4 text-xl font-bold text-gray-900">Total</td>
                                <td class="text-right py-3 px-4 text-xl font-bold text-purple-600" id="preview-total">
                                    ...</td>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- Terms & Conditions Section --}}
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-8 mb-8 bg-blue-50 p-4 rounded-lg">
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 mb-2 uppercase">Payment Terms</h4>
                            <p class="text-sm text-gray-600 whitespace-pre-line" id="preview-payment-terms"></p>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-700 mb-2 uppercase">Terms & Conditions</h4>
                            <p class="text-sm text-gray-600 whitespace-pre-line" id="preview-terms-conditions"></p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="text-center text-sm text-gray-500 mt-12 pt-8 border-t border-gray-100">
                        <p id="preview-footer-text">Thank you for your business!</p>
                    </div>
                </div>
            </div>

            {{-- TAB: HISTORY --}}
            <div id="tab-content-history" class="hidden">
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg mb-4 font-semibold">Activity Timeline</h3>
                    <div class="relative border-l-2 border-gray-200 ml-4 space-y-8 pl-6 py-2">
                        {{-- Static Example for Demo - in real app, iterate over history array --}}
                        <div class="relative">
                            <span
                                class="absolute -left-[33px] bg-purple-100 h-8 w-8 rounded-full flex items-center justify-center border-4 border-white">
                                <svg class="w-4 h-4 text-purple-600" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </span>
                            <h4 class="text-gray-900 font-medium">Quotation Created</h4>
                            <p class="text-sm text-gray-500" id="history-created-at">...</p>
                            <p class="text-sm text-gray-600">Created by System</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@php
    use App\CommonVariables;
@endphp

<script>
    // State
    let currentQuotationId = null;

    // --- Configuration Maps ---
    const statusConfig = {
        [{{ CommonVariables::$quotationStatusDraft }}]: { color: 'bg-gray-100 text-gray-700 border-gray-300', label: 'Draft' },
        [{{ CommonVariables::$quotationStatusPendingApproval }}]: { color: 'bg-yellow-100 text-yellow-700 border-yellow-300', label: 'Pending Approval' },
        [{{ CommonVariables::$quotationStatusApproved }}]: { color: 'bg-blue-100 text-blue-700 border-blue-300', label: 'Approved' },
        [{{ CommonVariables::$quotationStatusSent }}]: { color: 'bg-indigo-100 text-indigo-700 border-indigo-300', label: 'Sent' },
        [{{ CommonVariables::$quotationStatusCustomerAccepted }}]: { color: 'bg-green-100 text-green-700 border-green-300', label: 'Accepted' },
        [{{ CommonVariables::$quotationStatusCustomerRejected }}]: { color: 'bg-red-100 text-red-700 border-red-300', label: 'Rejected' },
        [{{ CommonVariables::$quotationStatusExpired }}]: { color: 'bg-orange-100 text-orange-700 border-orange-300', label: 'Expired' },
        [{{ CommonVariables::$quotationStatusConverted }}]: { color: 'bg-emerald-100 text-emerald-700 border-emerald-300', label: 'Converted' },
        [{{ CommonVariables::$quotationStatusCancelled }}]: { color: 'bg-slate-100 text-slate-700 border-slate-300', label: 'Cancelled' }
    };

    const currencyFormatter = new Intl.NumberFormat('en-LK', {
        style: 'currency',
        currency: 'LKR',
        minimumFractionDigits: 2
    });

    const dateFormatter = (dateString) => {
        if (!dateString) return '';
        return new Date(dateString).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    };

    // --- Main Functions ---

    function openDetailModal(quotation) {
        currentQuotationId = quotation.id;
        // 1. Reset Tabs
        switchTab('details');

        // 2. Populate Header
        document.getElementById('modal-quotation-number').innerText = quotation.quotation_number;
        document.getElementById('modal-created-at').innerText = dateFormatter(quotation.created_at);
        document.getElementById('modal-sales-person').innerText = quotation.creator ? quotation.creator.name : 'System';

        // 3. Status Badge Logic
        const config = statusConfig[quotation.status] || statusConfig[{{ CommonVariables::$quotationStatusDraft }}];
        const badge = document.getElementById('modal-status-badge');
        badge.className = `border px-3 py-1 rounded-lg flex items-center gap-1.5 text-xs font-bold ${config.color}`;
        badge.innerText = config.label;

        // 4. Expiry Logic
        const today = new Date();
        const expiry = new Date(quotation.valid_until);
        const diffDays = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));
        const expiryBadge = document.getElementById('modal-expiry-badge');

        if (diffDays <= 7 && diffDays > 0 && quotation.status === {{ CommonVariables::$quotationStatusSent }}) {
            expiryBadge.classList.remove('hidden');
            document.getElementById('modal-expiry-days').innerText = diffDays;
        } else {
            expiryBadge.classList.add('hidden');
        }

        // 5. Populate Details Tab
        const customer = quotation.customer || {};
        document.getElementById('modal-customer-name').innerText = customer.name || 'Unknown';
        document.getElementById('modal-customer-type').innerText = customer.type || '-';
        document.getElementById('modal-customer-phone').innerText = customer.phone || '-';
        document.getElementById('modal-customer-email').innerText = customer.email || '-';
        document.getElementById('modal-quotation-date').innerText = dateFormatter(quotation.created_at);
        document.getElementById('modal-valid-until').innerText = dateFormatter(quotation.valid_until);
        document.getElementById('modal-event-type').innerText = '-'; // event_type not in schema yet
        document.getElementById('modal-grand-total').innerText = currencyFormatter.format(quotation.grand_total);

        // 6. Populate Table (Both Detail and Preview)
        const generateRows = (items) => {
            if (!items || items.length === 0) return '<tr><td colspan="4" class="p-4 text-center text-gray-500">No items</td></tr>';
            return items.map(item => {
                const productName = item.product_item ? item.product_item.product_name : 'Unknown Item';
                const unitPrice = parseFloat(item.price || 0);
                const subtotal = parseFloat(item.subtotal || 0);
                return `
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-900">${productName}</td>
                    <td class="text-right py-3 px-4 text-gray-700">${item.quantity}</td>
                    <td class="text-right py-3 px-4 text-gray-700">${currencyFormatter.format(unitPrice)}</td>
                    <td class="text-right py-3 px-4 font-medium text-gray-900">${currencyFormatter.format(subtotal)}</td>
                </tr>
                `;
            }).join('');
        };

        const products = quotation.products || [];
        document.getElementById('modal-line-items-body').innerHTML = generateRows(products);
        document.getElementById('preview-table-body').innerHTML = generateRows(products);

        // 7. Populate Preview Header
        document.getElementById('preview-quotation-number').innerText = quotation.quotation_number;
        document.getElementById('preview-customer-name').innerText = customer.name || '';
        document.getElementById('preview-customer-email').innerText = customer.email || '';
        document.getElementById('preview-customer-phone').innerText = customer.phone || '';
        document.getElementById('preview-date').innerText = dateFormatter(quotation.created_at);
        document.getElementById('preview-valid-until').innerText = dateFormatter(quotation.valid_until);
        document.getElementById('preview-total').innerText = currencyFormatter.format(quotation.grand_total);

        // Load Company Settings for Preview
        loadPreviewSettings(quotation);

        // 8. Populate History
        document.getElementById('history-created-at').innerText = dateFormatter(quotation.created_at) + ' ' + new Date(quotation.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        // 9. Dynamic Action Buttons
        const actionsContainer = document.getElementById('modal-actions-container');
        actionsContainer.innerHTML = ''; // Clear previous

        // Add Download PDF button
        actionsContainer.innerHTML += `
            <button onclick="downloadQuotationPdf()" class="hidden h-9 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg flex items-center gap-2 text-sm transition-all shadow-md">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="17" x2="12" y2="12"></line><line x1="12" y1="17" x2="16" y2="13"></line><line x1="12" y1="17" x2="8" y2="13"></line></svg>
                Download PDF
            </button>`;

        if (quotation.status === {{ CommonVariables::$quotationStatusPendingApproval }}) {
            actionsContainer.innerHTML += `
                <button onclick="alert('Approve logic')" class="h-9 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center gap-2 text-sm transition-all shadow-md">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    Approve
                </button>`;
        }
        if (quotation.status === {{ CommonVariables::$quotationStatusCustomerAccepted }}) {
            actionsContainer.innerHTML += `
                <button onclick="alert('Convert logic')" class="h-9 px-4 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-lg flex items-center gap-2 text-sm transition-all shadow-md">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                    Convert to Order
                </button>`;
        }
        if (quotation.status === {{ CommonVariables::$quotationStatusDraft }} || quotation.status === {{ CommonVariables::$quotationStatusApproved }}) {
            actionsContainer.innerHTML += `
                <button onclick="alert('Send Email logic')" class="h-9 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2 text-sm transition-all shadow-md">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Send Email
                </button>`;
        }

        // Show Modal
        document.getElementById('quotationDetailModal').classList.remove('hidden');
    }

    function loadPreviewSettings(quotation) {
        fetch('{{ route("quotationManagement.getSettings") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const s = data.data;

                    if (s.company_name) document.getElementById('preview-company-name').innerText = s.company_name;
                    if (s.address) document.getElementById('preview-company-address').innerText = s.address;
                    if (s.phone) document.getElementById('preview-company-phone').innerText = s.phone;
                    if (s.email) document.getElementById('preview-company-email').innerText = s.email;
                    if (s.footer_text) document.getElementById('preview-footer-text').innerText = s.footer_text;

                    // New fields with fallback to quotation notes
                    document.getElementById('preview-payment-terms').innerText = s.default_payment_terms || '';
                    document.getElementById('preview-terms-conditions').innerText = s.default_terms_conditions || (quotation ? quotation.notes : '') || '';

                    // Also update Details Tab
                    document.getElementById('details-payment-terms').innerText = s.default_payment_terms || '';
                    document.getElementById('details-terms-conditions').innerText = s.default_terms_conditions || (quotation ? quotation.notes : '') || '';

                    const logoContainer = document.getElementById('preview-company-logo-container');
                    const logoimg = document.getElementById('preview-company-logo');

                    if (s.logo_url) {
                        logoimg.src = s.logo_url;
                        logoContainer.classList.remove('hidden');
                    } else {
                        logoContainer.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Error loading preview settings:', error));
    }

    function downloadQuotationPdf() {
        if (!currentQuotationId) return Swal.fire('Error', 'Quotation ID not found', 'error');
        window.location.href = `/api/quotation-management/download-pdf/${currentQuotationId}`;
    }

    function closeDetailModal() {
        document.getElementById('quotationDetailModal').classList.add('hidden');
    }

    function switchTab(tabName) {
        // Hide all contents
        ['details', 'preview', 'history'].forEach(t => {
            document.getElementById(`tab-content-${t}`).classList.add('hidden');
            const btn = document.getElementById(`tab-btn-${t}`);
            btn.classList.remove('border-purple-600', 'text-purple-600');
            btn.classList.add('border-transparent', 'text-gray-600');
        });

        // Show active content
        document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');
        const activeBtn = document.getElementById(`tab-btn-${tabName}`);
        activeBtn.classList.remove('border-transparent', 'text-gray-600');
        activeBtn.classList.add('border-purple-600', 'text-purple-600');
    }
</script>