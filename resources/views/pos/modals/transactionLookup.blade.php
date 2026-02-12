<div id="return-lookup-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col animate-fade-in-up">
        
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-white">
                        <i class="bi bi-search text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl text-white font-bold">Find Transaction</h2>
                        <p class="text-indigo-100 text-sm">Search by receipt number, transaction ID, or customer</p>
                    </div>
                </div>
                <button onclick="closeReturnModal()" class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors text-white">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        <div class="p-6 border-b border-gray-200">
            <div class="relative">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="bi bi-search"></i>
                </div>
                <input type="text" id="return-search-input" onkeyup="renderReturnList()"
                    placeholder="Enter receipt number, transaction ID, or customer name..."
                    class="w-full h-14 pl-12 pr-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 text-base">
            </div>
        </div>

        <div class="flex-1 overflow-hidden flex flex-col md:flex-row">
            
            <div class="w-full md:w-1/2 border-r border-gray-200 overflow-y-auto" id="return-list-container">
                </div>

            <div class="w-full md:w-1/2 overflow-y-auto bg-gray-50 md:bg-white" id="return-details-container">
                </div>
        </div>

        <div class="p-6 border-t border-gray-200 bg-gray-50">
            <div class="flex gap-3">
                <button onclick="closeReturnModal()" class="flex-1 h-12 bg-white hover:bg-gray-100 text-gray-700 rounded-xl border border-gray-300 transition-colors font-medium">
                    Cancel
                </button>
                <button id="btn-select-return" onclick="confirmReturnSelection()" disabled
                    class="flex-1 h-12 rounded-xl transition-all font-medium bg-gray-200 text-gray-400 cursor-not-allowed">
                    Select Transaction
                </button>
            </div>
        </div>
    </div>
</div>