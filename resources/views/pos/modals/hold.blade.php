<!-- Hold Transaction Modal -->
<div id="modal-hold" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity btn-close-modal" data-target="modal-hold">
    </div>

    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden relative z-10">

            <!-- Header -->
            <div class="bg-gradient-to-r from-orange-600 to-amber-600 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="bi bi-clock text-white text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl text-white font-bold">Hold Transaction</h2>
                            <p class="text-orange-100 text-sm">Save for later</p>
                        </div>
                    </div>
                    <button
                        class="btn-close-modal w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors text-white"
                        data-target="modal-hold">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <form id="form-hold" class="p-6">
                <div class="mb-6">
                    <label class="block text-sm text-gray-700 mb-2 font-medium">
                        Customer Name or Reference:
                    </label>
                    <input type="text" id="hold-ref-input"
                        class="w-full h-12 px-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-orange-500 transition-colors"
                        placeholder="e.g., John Doe, Table 5, Order #123" required>
                    <p class="text-xs text-gray-500 mt-2">
                        Enter a name or reference to help identify this transaction later
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="button"
                        class="btn-close-modal flex-1 h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium"
                        data-target="modal-hold">
                        Cancel
                    </button>
                    <button type="submit" id="btn-confirm-hold"
                        class="flex-1 h-12 rounded-xl transition-all bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-700 hover:to-amber-700 text-white shadow-lg font-bold">
                        Hold Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>