<!-- Payment Modal -->
<div id="modal-payment" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" role="dialog"
    aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity btn-close-modal"
        data-target="modal-payment"></div>

    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[95vh] overflow-hidden flex flex-col relative z-10">

            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 flex-shrink-0">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="bi bi-wallet2 text-white text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl text-white font-bold">Payment</h2>
                            <p class="text-purple-100 text-sm" id="pm-subtitle">Select payment method</p>
                        </div>
                    </div>
                    <button
                        class="btn-close-modal w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors text-white"
                        data-target="modal-payment">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Total & Customer Info -->
                <div class="flex gap-4">
                    <div class="flex-1 bg-white/20 rounded-xl p-4 backdrop-blur-sm">
                        <p class="text-purple-100 text-sm mb-1">Total Amount:</p>
                        <p class="text-3xl text-white font-bold" id="pm-total-display">Rs 0.00</p>
                    </div>
                    <div class="flex-1 bg-white/20 rounded-xl p-4 backdrop-blur-sm" id="pm-customer-info"
                        style="display: none;">
                        <p class="text-purple-100 text-sm mb-1">Customer:</p>
                        <p class="text-lg text-white font-medium" id="pm-customer-name">Name</p>
                        <p class="text-xs text-purple-100 mt-1" id="pm-credit-info">Credit Available: Rs 0.00</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6" id="pm-content">
                <div id="pm-success-view" style="display: none;"
                    class="flex flex-col items-center justify-center h-full">
                    <div
                        class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4 animate-bounce">
                        <i class="bi bi-check-lg text-4xl text-green-600"></i>
                    </div>
                    <p class="text-2xl text-green-600 font-bold mb-4">Payment Successful!</p>
                    <div class="flex gap-4">
                        <button id="btn-pm-close-success"
                            class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-colors font-medium">
                            Close
                        </button>
                        <button id="btn-pm-print-receipt"
                            class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl shadow-lg transition-colors flex items-center gap-2">
                            <i class="bi bi-printer"></i> Print Receipt
                        </button>
                    </div>
                </div>

                <div id="pm-main-view" class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-full">
                    <!-- LEFT: Payment Entry -->
                    <div class="flex flex-col h-full">
                        <!-- Payment Type Selector -->
                        <div class="mb-4 flex-shrink-0">
                            <label class="block text-sm text-gray-700 mb-2 font-medium">Payment Method:</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button
                                    class="btn-pm-type h-14 rounded-xl flex flex-col items-center justify-center gap-1 transition-all bg-green-600 text-white shadow-lg"
                                    data-type="cash">
                                    <i class="bi bi-cash"></i>
                                    <span class="text-xs font-medium">Cash</span>
                                </button>
                                <button
                                    class="btn-pm-type h-14 rounded-xl flex flex-col items-center justify-center gap-1 transition-all bg-gray-100 text-gray-700 hover:bg-gray-200"
                                    data-type="card">
                                    <i class="bi bi-credit-card"></i>
                                    <span class="text-xs font-medium">Card</span>
                                </button>
                                <button
                                    class="btn-pm-type h-14 rounded-xl flex flex-col items-center justify-center gap-1 transition-all bg-gray-100 text-gray-700 hover:bg-gray-200"
                                    data-type="mobile">
                                    <i class="bi bi-phone"></i>
                                    <span class="text-xs font-medium">Mobile</span>
                                </button>
                                <button
                                    class="btn-pm-type h-14 rounded-xl flex flex-col items-center justify-center gap-1 transition-all bg-gray-100 text-gray-700 hover:bg-gray-200"
                                    data-type="credit">
                                    <i class="bi bi-person-badge"></i>
                                    <span class="text-xs font-medium">Credit</span>
                                </button>
                                <button
                                    class="btn-pm-type h-14 rounded-xl flex flex-col items-center justify-center gap-1 transition-all bg-gray-100 text-gray-700 hover:bg-gray-200"
                                    data-type="gift">
                                    <i class="bi bi-gift"></i>
                                    <span class="text-xs font-medium">Gift</span>
                                </button>
                            </div>
                        </div>

                        <!-- Amount Input -->
                        <div class="mb-4 flex-shrink-0">
                            <label class="block text-sm text-gray-700 mb-2 font-medium">Amount:</label>
                            <div
                                class="h-16 bg-gray-50 border-2 border-gray-200 rounded-xl flex items-center justify-center overflow-hidden">
                                <p class="text-3xl text-gray-900 font-bold" id="pm-amount-display">Rs 0.00</p>
                            </div>
                        </div>

                        <!-- Dynamic Inputs (Reference / Quick Amounts) -->
                        <div id="pm-dynamic-inputs" class="mb-4 flex-shrink-0">
                            <!-- Injected by JS -->
                        </div>

                        <!-- Controls -->
                        <div class="mt-auto">
                            <button id="btn-pm-exact"
                                class="w-full h-10 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-xl mb-4 transition-colors text-sm font-medium">
                                Exact Remaining
                            </button>

                            <!-- Numpad -->
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="1">1</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="2">2</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="3">3</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="4">4</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="5">5</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="6">6</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="7">7</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="8">8</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="9">9</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key=".">.</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="0">0</button>
                                <button
                                    class="btn-pm-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                                    data-key="backspace"><i class="bi bi-backspace"></i></button>
                            </div>

                            <div class="flex gap-2">
                                <button id="btn-pm-clear"
                                    class="flex-1 h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium">Clear</button>
                                <button id="btn-pm-add"
                                    class="flex-1 h-12 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-xl shadow-lg transition-all font-bold flex items-center justify-center gap-2">
                                    <i class="bi bi-plus-lg"></i> Add Payment
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: Payment Summary -->
                    <div class="flex flex-col h-full">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex-1 flex flex-col">
                            <h3 class="text-sm text-gray-700 mb-3 font-medium">Payment Summary:</h3>

                            <div id="pm-list" class="flex-1 overflow-y-auto space-y-2 mb-4">
                                <!-- JS Rendered List -->
                                <div class="text-center py-8 text-gray-400">
                                    <i class="bi bi-wallet2 text-3xl mb-2 opacity-50 block"></i>
                                    <p class="text-sm">No payments added yet</p>
                                </div>
                            </div>

                            <!-- Calculations -->
                            <div class="space-y-2 pt-3 border-t border-gray-300 flex-shrink-0">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Amount:</span>
                                    <span class="text-gray-900 font-medium" id="pm-summ-total">Rs 0.00</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Paid:</span>
                                    <span class="text-orange-600 font-medium" id="pm-summ-paid">Rs 0.00</span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-gray-300">
                                    <span class="text-gray-900 font-bold" id="pm-summ-lbl-rem">Remaining:</span>
                                    <span class="text-lg text-orange-600 font-bold" id="pm-summ-rem">Rs 0.00</span>
                                </div>
                            </div>
                        </div>

                        <button id="btn-pm-complete" disabled
                            class="w-full h-14 rounded-xl text-lg transition-all mt-4 bg-gray-200 text-gray-400 cursor-not-allowed font-bold">
                            Complete Sale
                        </button>
                        <p class="text-xs text-orange-600 text-center mt-2" id="pm-warning-msg">Add remaining amount to
                            complete</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>