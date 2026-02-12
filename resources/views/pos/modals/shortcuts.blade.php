<!-- Keyboard Shortcuts Modal -->
<div id="modal-shortcuts" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <!-- Overlay/Background (Click to close) -->
    <div class="absolute inset-0 btn-close-modal cursor-default" data-target="modal-shortcuts"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <!-- <Keyboard className="w-6 h-6 text-white" /> -->
                        <i class="bi bi-keyboard text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Keyboard Shortcuts</h2>
                        <p class="text-indigo-100 text-sm">Quick reference guide</p>
                    </div>
                </div>
                <button
                    class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors text-white btn-close-modal"
                    data-target="modal-shortcuts"
                >
                    <!-- <X className="w-5 h-5 text-white" /> -->
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Shortcuts List -->
        <div class="p-6 max-h-[600px] overflow-y-auto">
            <div class="grid gap-3">
                <!-- Checkout -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div>
                        <p class="text-gray-900 font-medium">Checkout</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <kbd class="px-3 py-1.5 bg-white border-2 border-gray-300 rounded-lg text-sm text-gray-700 shadow-sm font-bold font-mono">F1</kbd>
                    </div>
                </div>

                <!-- Select Customer -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div>
                        <p class="text-gray-900 font-medium">Select Customer</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <kbd class="px-3 py-1.5 bg-white border-2 border-gray-300 rounded-lg text-sm text-gray-700 shadow-sm font-bold font-mono">F2</kbd>
                    </div>
                </div>

                <!-- Discount -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div>
                        <p class="text-gray-900 font-medium">Discount</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <kbd class="px-3 py-1.5 bg-white border-2 border-gray-300 rounded-lg text-sm text-gray-700 shadow-sm font-bold font-mono">F3</kbd>
                    </div>
                </div>

                <!-- Held Transactions -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div>
                        <p class="text-gray-900 font-medium">Held Transactions</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <kbd class="px-3 py-1.5 bg-white border-2 border-gray-300 rounded-lg text-sm text-gray-700 shadow-sm font-bold font-mono">F4</kbd>
                    </div>
                </div>

                <!-- Fullscreen -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div>
                        <p class="text-gray-900 font-medium">Toggle Fullscreen</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <kbd class="px-3 py-1.5 bg-white border-2 border-gray-300 rounded-lg text-sm text-gray-700 shadow-sm font-bold font-mono">F11</kbd>
                    </div>
                </div>
            </div>

            <!-- Help Text -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <p className="text-sm text-blue-700 flex items-start gap-2">
                    <span class="text-lg">💡</span> 
                    <span>
                        <strong>Tip:</strong> Keyboard shortcuts won't work when typing in input fields. 
                        Press <kbd class="px-2 py-1 bg-white border border-blue-300 rounded-md text-xs font-bold text-blue-800">?</kbd> to view this guide anytime.
                    </span>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end">
            <button
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-colors font-medium btn-close-modal"
                data-target="modal-shortcuts"
            >
                Got it!
            </button>
        </div>
    </div>
</div>
