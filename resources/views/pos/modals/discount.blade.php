<!-- Discount Modal -->
<div id="modal-discount" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" role="dialog"
    aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity btn-close-modal"
        data-target="modal-discount"></div>

    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full flex flex-col relative z-10 transform transition-all max-h-[90vh] overflow-y-auto">

            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-600 to-red-600 p-6 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="bi bi-percent text-white text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl text-white font-bold">Transaction Discount</h2>
                            <p class="text-orange-100 text-sm">Apply discount to total</p>
                        </div>
                    </div>
                    <button
                        class="btn-close-modal w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors text-white"
                        data-target="modal-discount">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Summary Card -->
                <div class="bg-gray-50 rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                        <span>Original Amount:</span>
                        <span class="text-gray-900 font-medium" id="dm-original-amount">Rs 0.00</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                        <span>Discount Amount:</span>
                        <span class="text-orange-600 font-medium" id="dm-discount-amount">- Rs 0.00</span>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                        <span class="text-gray-900 font-bold">Final Amount:</span>
                        <span class="text-xl text-green-600 font-bold" id="dm-final-amount">Rs 0.00</span>
                    </div>
                </div>

                <!-- Type Toggles -->
                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-2 font-medium">Discount Type:</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button id="btn-dtype-perc"
                            class="h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-orange-600 text-white shadow-lg font-medium">
                            <i class="bi bi-percent"></i>
                            <span>Percentage</span>
                        </button>
                        <button id="btn-dtype-fixed"
                            class="h-14 rounded-xl flex items-center justify-center gap-2 transition-all bg-gray-100 text-gray-700 hover:bg-gray-200 font-medium">
                            <i class="bi bi-currency-dollar"></i>
                            <span>Fixed Amount</span>
                        </button>
                    </div>
                </div>

                <!-- Quick Select (Only for Percentage) -->
                <div id="dm-quick-select" class="mb-4">
                    <label class="block text-sm text-gray-700 mb-2 font-medium">Quick Select:</label>
                    <div class="grid grid-cols-4 gap-2">
                        <button
                            class="btn-quick-disc h-10 rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-700 font-medium transition-colors"
                            data-val="5">5%</button>
                        <button
                            class="btn-quick-disc h-10 rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-700 font-medium transition-colors"
                            data-val="10">10%</button>
                        <button
                            class="btn-quick-disc h-10 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-medium transition-colors"
                            data-val="15">15%</button>
                        <button
                            class="btn-quick-disc h-10 rounded-lg bg-red-100 hover:bg-red-200 text-red-700 font-medium transition-colors"
                            data-val="20">20%</button>
                    </div>
                </div>

                <!-- Input Display -->
                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-2 font-medium" id="dm-input-label">Discount
                        Percentage:</label>
                    <div class="h-16 bg-gray-50 border-2 border-gray-200 rounded-xl flex items-center justify-center overflow-hidden relative"
                        id="dm-input-container">
                        <p class="text-3xl text-gray-900 font-bold" id="dm-input-display">0%</p>
                    </div>
                </div>

                <!-- Approval Warning -->
                <div id="dm-approval-warning" style="display: none;"
                    class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4 flex items-start gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="text-sm text-red-900 mb-2 font-medium">
                            This discount requires manager approval (> 15%)
                        </p>
                        <input type="password" id="dm-manager-code" placeholder="Enter manager code"
                            class="w-full h-10 px-3 border border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm">
                    </div>
                </div>

                <!-- Numpad -->
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="1">1</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="2">2</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="3">3</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="4">4</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="5">5</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="6">6</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="7">7</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="8">8</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="9">9</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key=".">.</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="0">0</button>
                    <button
                        class="btn-numpad h-12 bg-gray-100 hover:bg-gray-200 text-gray-900 rounded-xl text-lg font-medium transition-colors active:scale-95"
                        data-key="backspace"><i class="bi bi-backspace"></i></button>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button id="btn-dm-clear"
                        class="flex-1 h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium">
                        Clear
                    </button>
                    <button
                        class="flex-1 h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium btn-close-modal"
                        data-target="modal-discount">
                        Cancel
                    </button>
                    <button id="btn-dm-apply" disabled
                        class="flex-1 h-12 rounded-xl transition-all bg-gray-200 text-gray-400 cursor-not-allowed font-bold">
                        Apply Discount
                    </button>
                </div>

                <!-- Remove Discount -->
                <button id="btn-dm-remove" style="display: none;"
                    class="w-full h-10 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl mt-3 text-sm transition-colors font-medium">
                    Remove Current Discount
                </button>
            </div>
        </div>
    </div>
</div>