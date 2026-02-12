<!-- Order Details Modal -->
<div id="orderDetailModal" class="fixed inset-0 z-50 hidden" aria-labelledby="detail-modal-title" role="dialog"
    aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeDetailsModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
            <!-- Modal Panel -->
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-5xl max-h-[90vh] flex flex-col">

                <!-- Header -->
                <div
                    class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-purple-600" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1" />
                            <circle cx="20" cy="21" r="1" />
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 1.99-1.72l1.38-6.28h-12" />
                        </svg>
                        Purchase Order Details
                    </h3>
                    <p class="text-base text-gray-600" id="detailModalPoNumberHeader"></p>
                </div>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6">

                    <!-- Header Info Card -->
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-5 border-2 border-purple-200">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 mb-2" id="detailModalPoNumber"></h2>
                                <span id="detailModalStatus"
                                    class="inline-flex items-center rounded-full border px-3 py-1 text-sm font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"></span>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-600 mb-1">Grand Total</div>
                                <div class="text-3xl font-bold text-purple-600" id="detailModalGrandTotalMain"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-center gap-2 text-gray-600 mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="m7.5 4.27 9 5.15" />
                                        <path
                                            d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                        <path d="m3.3 7 8.7 5 8.7-5" />
                                        <path d="M12 22v-9" />
                                    </svg>
                                    <span class="text-sm">Items</span>
                                </div>
                                <div class="text-xl font-bold text-gray-900" id="detailModalItemsCount"></div>
                            </div>

                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-center gap-2 text-gray-600 mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" x2="16" y1="2" y2="6" />
                                        <line x1="8" x2="8" y1="2" y2="6" />
                                        <line x1="3" x2="21" y1="10" y2="10" />
                                    </svg>
                                    <span class="text-sm">Created</span>
                                </div>
                                <div class="text-lg font-medium text-gray-900" id="detailModalCreated"></div>
                            </div>

                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-center gap-2 text-gray-600 mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    <span class="text-sm">Expected</span>
                                </div>
                                <div class="text-lg font-medium text-gray-900" id="detailModalExpected"></div>
                            </div>

                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex items-center gap-2 text-gray-600 mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <line x1="12" x2="12" y1="2" y2="22" />
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                    </svg>
                                    <span class="text-sm">Payment</span>
                                </div>
                                <div class="text-lg font-medium text-gray-900" id="detailModalPayment"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Information -->
                    <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-5 py-3 border-b-2 border-gray-200">
                            <h3 class="font-medium text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="4" y="2" width="16" height="20" rx="2" ry="2" />
                                    <path d="M9 22v-4h6v4" />
                                    <path d="M8 6h.01" />
                                    <path d="M16 6h.01" />
                                    <path d="M12 6h.01" />
                                    <path d="M12 10h.01" />
                                    <path d="M12 14h.01" />
                                    <path d="M16 10h.01" />
                                    <path d="M16 14h.01" />
                                    <path d="M8 10h.01" />
                                    <path d="M8 14h.01" />
                                </svg>
                                Supplier Information
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="4" y="2" width="16" height="20" rx="2" ry="2" />
                                        <path d="M9 22v-4h6v4" />
                                        <path d="M8 6h.01" />
                                        <path d="M16 6h.01" />
                                        <path d="M12 6h.01" />
                                        <path d="M12 10h.01" />
                                        <path d="M12 14h.01" />
                                        <path d="M16 10h.01" />
                                        <path d="M16 14h.01" />
                                        <path d="M8 10h.01" />
                                        <path d="M8 14h.01" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2" id="detailModalSupplierName">
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 flex-shrink-0"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                                <circle cx="12" cy="7" r="4" />
                                            </svg>
                                            <div>
                                                <div class="font-medium text-gray-900">Contact Person</div>
                                                <div id="detailModalContactPerson">Available in details</div>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 flex-shrink-0"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                                <polyline points="22,6 12,13 2,6" />
                                            </svg>
                                            <div>
                                                <div class="font-medium text-gray-900">Email</div>
                                                <div id="detailModalSupplierEmail">Available in details</div>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-2 text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 flex-shrink-0"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path
                                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                            </svg>
                                            <div>
                                                <div class="font-medium text-gray-900">Phone</div>
                                                <div id="detailModalSupplierPhone">Available in details</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-5 py-3 border-b-2 border-gray-200">
                            <h3 class="font-medium text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m7.5 4.27 9 5.15" />
                                    <path
                                        d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                    <path d="m3.3 7 8.7 5 8.7-5" />
                                    <path d="M12 22v-9" />
                                </svg>
                                Order Items (<span id="detailModalItemsHeaderCount"></span>)
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b-2 border-gray-200">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-700">Product</th>
                                        <th class="px-5 py-3 text-left text-sm font-medium text-gray-700">Category</th>
                                        <th class="px-5 py-3 text-right text-sm font-medium text-gray-700">Quantity</th>
                                        <th class="px-5 py-3 text-right text-sm font-medium text-gray-700">Unit Price
                                        </th>
                                        <th class="px-5 py-3 text-right text-sm font-medium text-gray-700">Total</th>
                                        <th
                                            class="px-5 py-3 text-center text-sm font-medium text-gray-700 received-col hidden">
                                            Received</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200" id="detailModalItemsTableBody">
                                    <!-- Items populated via JS -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals -->
                        <div class="bg-gray-50 px-5 py-4 border-t-2 border-gray-200">
                            <div class="flex justify-end">
                                <div class="w-80 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700">Subtotal</span>
                                        <span class="font-medium text-gray-900" id="detailModalSubtotal"></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700">Tax</span>
                                        <span class="font-medium text-gray-900" id="detailModalTax"></span>
                                    </div>
                                    <div class="h-px bg-gray-300 my-2"></div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-medium text-gray-900">Grand Total</span>
                                        <span class="text-2xl font-bold text-purple-600"
                                            id="detailModalGrandTotalBottom"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div id="detailModalNotesSection" class="bg-blue-50 rounded-xl p-4 border-2 border-blue-200 hidden">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" x2="8" y1="13" y2="13" />
                                <line x1="16" x2="8" y1="17" y2="17" />
                                <polyline points="10 9 9 9 8 9" />
                            </svg>
                            <div>
                                <h4 class="font-medium text-blue-900 mb-1">Order Notes</h4>
                                <p class="text-blue-800" id="detailModalNotes"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Trail -->
                    <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-5 py-3 border-b-2 border-gray-200">
                            <h3 class="font-medium text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M3 3v5h5" />
                                    <path d="M3.05 13A9 9 0 1 0 6 5.3L3 8" />
                                    <path d="M12 7v5l4 2" />
                                </svg>
                                Audit Trail (<span id="detailModalAuditCount">0</span> events)
                            </h3>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4" id="detailModalAuditTrailBody">
                                <!-- Audit info populated by JS -->
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Actions -->
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex gap-3">
                    <button id="downloadPdfBtn"
                        class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="7 10 12 15 17 10" />
                            <line x1="12" x2="12" y1="15" y2="3" />
                        </svg>
                        Download PDF
                    </button>

                    <button
                        onclick="toastr.success('Email sent!', {description: 'Purchase order has been sent to supplier'})"
                        class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                            <polyline points="22,6 12,13 2,6" />
                        </svg>
                        Email to Supplier
                    </button>

                    <div class="flex-1"></div>

                    <button onclick="closeDetailsModal()"
                        class="h-12 px-6 bg-gradient-to-br from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl font-medium shadow-md transition-all">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>