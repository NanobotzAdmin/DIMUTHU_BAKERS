<!-- Customer Select Modal -->
<div id="modal-customer" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" role="dialog"
    aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity btn-close-modal"
        data-target="modal-customer"></div>

    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col relative z-10 transform transition-all">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-6 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="bi bi-person-fill text-white text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl text-white font-bold">Select Customer</h2>
                            <p class="text-blue-100 text-sm">Choose existing or walk-in customer</p>
                        </div>
                    </div>
                    <button
                        class="btn-close-modal w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors text-white"
                        data-target="modal-customer">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Content Area (Switches between List and Quick Add) -->
            <div class="flex-1 overflow-hidden flex flex-col p-6 relative" id="customer-modal-content">

                <!-- View: Customer List -->
                <div id="view-customer-list" class="flex flex-col h-full">
                    <!-- Search Bar -->
                    <div class="mb-4 flex-shrink-0">
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="customer-search" placeholder="Search by name, phone, code..."
                                class="w-full h-12 pl-10 pr-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                        </div>
                    </div>

                    <!-- Quick Customer Actions -->
                    <div class="flex gap-2 mb-4 flex-shrink-0">
                        <button id="btn-select-walkin"
                            class="flex-1 h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors flex items-center justify-center gap-2 font-medium">
                            <i class="bi bi-person"></i>
                            Walk-in Customer
                        </button>
                        <button id="btn-show-quick-add"
                            class="flex-1 h-12 bg-green-50 hover:bg-green-100 text-green-700 rounded-xl transition-colors flex items-center justify-center gap-2 font-medium">
                            <i class="bi bi-person-plus-fill"></i>
                            Quick Add Customer
                        </button>
                    </div>

                    <!-- Customer List Container -->
                    <div class="flex-1 overflow-y-auto border border-gray-200 rounded-xl p-1" id="customer-list">
                        <!-- Rendered by JS -->
                    </div>
                </div>

                <!-- View: Quick Add Form -->
                <div id="view-quick-add" style="display: none;" class="flex flex-col h-full">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex-shrink-0">
                        <div class="flex gap-3">
                            <i class="bi bi-info-circle-fill text-blue-600 mt-0.5"></i>
                            <p class="text-sm text-blue-700">
                                Quickly add a new customer with basic details. You can add more information later from
                                Customer Management.
                            </p>
                        </div>
                    </div>

                    <form id="form-quick-add" class="flex-1 flex flex-col">
                        <div class="space-y-4 flex-1">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Customer Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="qa-name" required placeholder="Enter customer name"
                                    class="w-full h-12 px-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="qa-phone" required placeholder="+94 XX XXX XXXX"
                                    class="w-full h-12 px-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100 flex-shrink-0">
                            <button type="button" id="btn-cancel-quick-add"
                                class="flex-1 h-12 bg-white hover:bg-gray-50 text-gray-700 rounded-xl border border-gray-300 transition-colors font-medium">
                                Back
                            </button>
                            <button type="submit"
                                class="flex-1 h-12 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl shadow-lg shadow-green-200 transition-all font-bold">
                                Add & Select
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Footer (Only for List View) -->
            <div id="modal-footer-actions" class="p-6 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                <div class="flex gap-3">
                    <button
                        class="btn-close-modal flex-1 h-12 bg-white hover:bg-gray-100 text-gray-700 rounded-xl border border-gray-300 transition-colors font-medium"
                        data-target="modal-customer">
                        Cancel
                    </button>
                    <!-- Confirm Selection Button (Hidden until selection made?) Or handled by row click -->
                    <!-- We will rely on row click for selection for speed, but could have a button if needed. 
                          The React design implies row click selects state, then "Select Customer" confirms. 
                          Let's implement 'Select Customer' button logic. -->
                    <button id="btn-confirm-customer" disabled
                        class="flex-1 h-12 rounded-xl transition-all font-bold text-white bg-gray-300 cursor-not-allowed">
                        Select Customer
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>