<form id="customer-delivery-form" onsubmit="handleCreateDeliverySubmit(event, 'customer')" class="space-y-6 hidden detail-form">
    {{-- Customer Info --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <h3 class="text-gray-900 mb-4 font-bold">Customer Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 mb-2 font-medium">Customer Name *</label>
                <input
                    type="text"
                    name="customer_name"
                    placeholder="Enter customer name"
                    class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors"
                    required
                />
            </div>

            <div>
                <label class="block text-gray-700 mb-2 font-medium">Customer Type *</label>
                <select
                    name="customer_type"
                    class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors"
                >
                    <option value="corporate">Corporate</option>
                    <option value="retail">Retail</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 mb-2 font-medium">Delivery Address *</label>
                <textarea
                    name="delivery_address"
                    placeholder="Enter full delivery address"
                    rows="2"
                    class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors resize-none"
                    required
                ></textarea>
            </div>

            <div>
                <label class="block text-gray-700 mb-2 font-medium">Contact Phone *</label>
                <input
                    type="tel"
                    name="contact_phone"
                    placeholder="+94 77 123 4567"
                    class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors"
                    required
                />
            </div>

            <div>
                <label class="block text-gray-700 mb-2 font-medium">Contact Email</label>
                <input
                    type="email"
                    name="contact_email"
                    placeholder="customer@email.com"
                    class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors"
                />
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-900 font-bold">Items to Deliver *</h3>
            <button
                type="button"
                onclick="addCreateItem('customer')"
                class="flex items-center gap-2 h-10 px-4 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-xl transition-colors font-medium"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"></line><line x1="5" x2="19" y1="12" y2="12"></line></svg>
                Add Item
            </button>
        </div>

        <div id="customer-items-container" class="space-y-3">
            {{-- Initial Item Row --}}
            <div class="flex gap-3 items-start item-row">
                <input
                    type="text"
                    name="items[0][name]"
                    placeholder="Product name"
                    class="flex-1 h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors"
                    required
                />
                <input
                    type="number"
                    name="items[0][quantity]"
                    placeholder="Qty"
                    min="1"
                    class="w-24 h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors"
                    required
                />
                <select
                    name="items[0][unit]"
                    class="w-28 h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors"
                >
                    <option value="pcs">pcs</option>
                    <option value="kg">kg</option>
                    <option value="loaves">loaves</option>
                    <option value="boxes">boxes</option>
                </select>
                {{-- Remove button hidden for first item usually, or handled via JS --}}
                <button type="button" onclick="removeCreateItem(this)" class="w-12 h-12 rounded-xl bg-red-100 hover:bg-red-200 flex items-center justify-center transition-colors">
                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><line x1="18" x2="6" y1="6" y2="18"></line><line x1="6" x2="18" y1="6" y2="18"></line></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Priority & Instructions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-gray-700 mb-2 font-medium">Priority</label>
            <select
                name="priority"
                class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors"
            >
                <option value="low">Low</option>
                <option value="standard" selected>Standard</option>
                <option value="urgent">Urgent</option>
            </select>
        </div>

        <div>
            <label class="block text-gray-700 mb-2 font-medium">Special Instructions</label>
            <input
                type="text"
                name="special_instructions"
                placeholder="e.g., Call before arrival"
                class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500 transition-colors"
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
            class="flex-1 h-12 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl transition-all shadow-lg font-bold"
        >
            Create Delivery
        </button>
    </div>
</form>
