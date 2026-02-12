<!-- Held Transactions Modal (Master-Detail) -->
<div id="modal-held" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity btn-close-modal" data-target="modal-held">
    </div>

    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden flex flex-col relative z-10">

            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-600 to-amber-600 p-6 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="bi bi-clock-history text-white text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl text-white font-bold">Held Transactions</h2>
                            <p class="text-orange-100 text-sm" id="held-count-lbl">0 transactions on hold</p>
                        </div>
                    </div>
                    <button
                        class="btn-close-modal w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors text-white"
                        data-target="modal-held">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-hidden flex">
                <!-- Left: Transaction List -->
                <div class="w-1/2 border-r border-gray-200 flex flex-col">
                    <!-- Search -->
                    <div class="p-4 border-b border-gray-200">
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="held-search"
                                class="w-full h-12 pl-10 pr-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="Search by reference, ID...">
                        </div>
                    </div>

                    <!-- List Container -->
                    <div class="flex-1 overflow-y-auto" id="held-list-container">
                        <!-- JS Rendered List -->
                        <div class="flex flex-col items-center justify-center h-full text-gray-500 p-6">
                            <i class="bi bi-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>No held transactions</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Details -->
                <div class="w-1/2 flex flex-col bg-gray-50" id="held-details-pane">
                    <!-- Empty State -->
                    <div id="held-details-empty" class="flex flex-col items-center justify-center h-full text-gray-400">
                        <i class="bi bi-eye text-4xl mb-3"></i>
                        <p>Select a transaction to view details</p>
                    </div>

                    <!-- Content State -->
                    <div id="held-details-content" style="display: none;" class="flex-col h-full">
                        <div class="flex-1 overflow-y-auto p-6">
                            <!-- Txn Header -->
                            <div class="mb-6">
                                <h3 class="text-xl text-gray-900 mb-2 font-bold" id="hd-reference">Ref Name</h3>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <span class="px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700" id="hd-time-ago">10m
                                        ago</span>
                                    <span id="hd-time-abs">10:30 AM</span>
                                </div>
                            </div>

                            <!-- Items -->
                            <div class="mb-6">
                                <h4 class="text-sm text-gray-700 mb-3 font-bold" id="hd-item-count">Items (0):</h4>
                                <div class="space-y-2" id="hd-items-list">
                                    <!-- JS Items -->
                                </div>
                            </div>

                            <!-- Totals -->
                            <div class="bg-white border border-gray-200 rounded-xl p-4 space-y-2 shadow-sm">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="text-gray-900" id="hd-subtotal">Rs 0.00</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Tax (8%):</span>
                                    <span class="text-gray-900" id="hd-tax">Rs 0.00</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                                    <span class="text-gray-900 font-bold">Total:</span>
                                    <span class="text-xl text-orange-600 font-bold" id="hd-total">Rs 0.00</span>
                                </div>
                            </div>

                            <div class="mt-6 text-xs text-gray-500 space-y-1">
                                <p>Transaction ID: <span id="hd-id"></span></p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="p-6 border-t border-gray-200 bg-white">
                            <div class="flex gap-3">
                                <button id="btn-hd-delete"
                                    class="flex-1 h-12 bg-red-100 hover:bg-red-200 text-red-700 rounded-xl transition-colors flex items-center justify-center gap-2 font-medium">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                                <button id="btn-hd-recall"
                                    class="flex-1 h-12 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white rounded-xl transition-all flex items-center justify-center gap-2 font-bold">
                                    <i class="bi bi-arrow-counterclockwise"></i> Recall to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>