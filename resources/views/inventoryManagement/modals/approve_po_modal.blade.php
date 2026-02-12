<!-- Approval Modal -->
<div id="approvalModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeApprovalModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <!-- Header -->
                    <div class="mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                    <polyline points="22 4 12 14.01 9 11.01" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900" id="modal-title">
                                    Approve Purchase Order
                                </h3>
                                <p class="text-base text-gray-500 mt-1">
                                    <span id="modalPoNumber" class="font-medium text-gray-700"></span> • <span
                                        id="modalSupplierName"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="space-y-4">
                        <!-- Order Summary -->
                        <div class="bg-green-50/50 rounded-xl p-5 border-2 border-green-100">
                            <h3 class="font-semibold text-green-900 mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                                    <rect x="9" y="3" width="6" height="4" rx="2" />
                                </svg>
                                Order Summary
                            </h3>
                            <div class="grid grid-cols-3 gap-6 text-center divide-x divide-green-200">
                                <div class="px-2">
                                    <div class="text-xs font-medium uppercase tracking-wider text-green-700 mb-1">Items
                                    </div>
                                    <div class="text-2xl font-bold text-green-900" id="modalItemsCount"></div>
                                </div>
                                <div class="px-2">
                                    <div class="text-xs font-medium uppercase tracking-wider text-green-700 mb-1">Total
                                        Amount</div>
                                    <div class="text-2xl font-bold text-green-900" id="modalTotalAmount"></div>
                                </div>
                                <div class="px-2">
                                    <div class="text-xs font-medium uppercase tracking-wider text-green-700 mb-1">
                                        Delivery</div>
                                    <div class="text-lg font-bold text-green-900 mt-1" id="modalDeliveryDate"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Warning/Info -->
                        <div class="bg-amber-50 rounded-xl p-4 border border-amber-200 shadow-sm">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                                    <line x1="12" x2="12" y1="9" y2="13" />
                                    <line x1="12" x2="12" y1="17" y2="17" />
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-amber-900 mb-1">Confirmation Required</h4>
                                    <p class="text-sm text-amber-800 leading-relaxed">
                                        By approving this purchase order, you authorize the purchase of goods worth Rs
                                        <span id="modalAuthAmount" class="font-bold"></span> from <span
                                            id="modalAuthSupplier" class="font-bold"></span>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="bg-gray-50 px-4 py-4 sm:flex sm:flex-row gap-3 border-t border-gray-100">
                    <button type="button" onclick="closeApprovalModal()"
                        class="flex-1 inline-flex justify-center items-center h-12 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-xl font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmApproval()"
                        class="flex-[2] inline-flex justify-center items-center h-12 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-bold shadow-lg shadow-green-200 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Approve Purchase Order
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>