<form id="outlet-transfer-form" onsubmit="handleCreateDeliverySubmit(event, 'outlet-transfer')" class="space-y-6 hidden detail-form">
    {{-- Transfer Info --}}
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        <h3 class="text-gray-900 mb-4 font-bold">Transfer Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 mb-2 font-medium">From Location</label>
                <input
                    type="text"
                    value="Main Bakery"
                    disabled
                    class="w-full h-12 px-4 bg-gray-100 border-2 border-gray-200 rounded-xl text-gray-600"
                />
            </div>

            <div>
                <label class="block text-gray-700 mb-2 font-medium">To Outlet *</label>
                <select
                    id="outlet-select"
                    name="outlet_id"
                    onchange="updateOutletDetails(this)"
                    class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 transition-colors"
                    required
                >
                    <option value="">Select outlet...</option>
                    {{-- Assuming outlets are passed to view or we use static for now/ajax --}}
                    {{-- In a real scenario, this would be populated by backend data --}}
                    <option value="1" data-address="123 Main St, City" data-phone="0112345678" data-contact="John Manager">Colombo Outlet (🏢 Company)</option>
                    <option value="2" data-address="456 Beach Rd, Mount" data-phone="0118765432" data-contact="Jane Owner">Mount Lavinia Outlet (🏪 Third-party)</option>
                </select>
            </div>

            <div class="md:col-span-2 hidden" id="outlet-details-container">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2 font-medium">Delivery Address</label>
                        <input
                            type="text"
                            id="outlet-address"
                            disabled
                            class="w-full h-12 px-4 bg-gray-100 border-2 border-gray-200 rounded-xl text-gray-600"
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Contact Phone</label>
                        <input
                            type="text"
                            id="outlet-phone"
                            disabled
                            class="w-full h-12 px-4 bg-gray-100 border-2 border-gray-200 rounded-xl text-gray-600"
                        />
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Contact Person</label>
                        <input
                            type="text"
                            id="outlet-contact"
                            disabled
                            class="w-full h-12 px-4 bg-gray-100 border-2 border-gray-200 rounded-xl text-gray-600"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-900 font-bold">Items to Transfer *</h3>
            <button
                type="button"
                onclick="addCreateItem('outlet-transfer')"
                class="flex items-center gap-2 h-10 px-4 bg-green-100 hover:bg-green-200 text-green-700 rounded-xl transition-colors font-medium"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"></line><line x1="5" x2="19" y1="12" y2="12"></line></svg>
                Add Item
            </button>
        </div>

        <div id="outlet-transfer-items-container" class="space-y-3">
            <div class="flex gap-3 items-start item-row">
                <input
                    type="text"
                    name="items[0][name]"
                    placeholder="Product name"
                    class="flex-1 h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 transition-colors"
                    required
                />
                <input
                    type="number"
                    name="items[0][quantity]"
                    placeholder="Qty"
                    min="1"
                    class="w-24 h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 transition-colors"
                    required
                />
                <select
                    name="items[0][unit]"
                    class="w-28 h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 transition-colors"
                >
                    <option value="pcs">pcs</option>
                    <option value="kg">kg</option>
                    <option value="loaves">loaves</option>
                    <option value="boxes">boxes</option>
                </select>
                <button type="button" onclick="removeCreateItem(this)" class="w-12 h-12 rounded-xl bg-red-100 hover:bg-red-200 flex items-center justify-center transition-colors">
                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><line x1="18" x2="6" y1="6" y2="18"></line><line x1="6" x2="18" y1="6" y2="18"></line></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Priority & Notes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-gray-700 mb-2 font-medium">Priority</label>
            <select
                name="priority"
                class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 transition-colors"
            >
                <option value="low">Low</option>
                <option value="standard" selected>Standard</option>
                <option value="urgent">Urgent</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-700 mb-2 font-medium">Transfer Notes</label>
            <input
                type="text"
                name="notes"
                placeholder="e.g., Daily stock replenishment"
                class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 transition-colors"
            />
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3 pt-4 border-t border-gray-200">
        <button
            type="button"
            onclick="closeCreateDeliveryModal()"
            class="flex-1 h-12 px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium"
        >
            Cancel
        </button>
        <button
            type="submit"
            class="flex-1 h-12 px-6 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all shadow-lg font-bold"
        >
            Create Transfer
        </button>
    </div>
</form>
