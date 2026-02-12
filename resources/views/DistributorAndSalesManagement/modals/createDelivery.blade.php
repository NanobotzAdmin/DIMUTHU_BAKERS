<div id="create-delivery-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity opacity-0" id="create-delivery-backdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            {{-- Panel --}}
            <div id="create-delivery-panel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl max-h-[90vh] overflow-y-auto opacity-0 scale-95 duration-300 ease-out">
                
                {{-- Header --}}
                <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between z-10">
                    <div class="flex items-center gap-3">
                        <button
                            id="create-back-btn"
                            onclick="goBackToSelection()"
                            class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors hidden"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600"><line x1="19" x2="5" y1="12" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        </button>
                        <div>
                            <h2 class="text-2xl text-gray-900 font-bold" id="create-modal-title">Select Delivery Type</h2>
                            <p class="text-gray-600" id="create-modal-subtitle">Choose the type of delivery</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        onclick="closeCreateDeliveryModal()"
                        class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600"><line x1="18" x2="6" y1="6" y2="18"></line><line x1="6" x2="18" y1="6" y2="18"></line></svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-6">
                    {{-- Type Selection Grid --}}
                    <div id="delivery-type-selection" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Customer Delivery --}}
                        <button
                            onclick="selectDeliveryType('customer')"
                            class="group p-6 bg-white border-2 border-gray-200 rounded-2xl hover:border-blue-500 hover:shadow-lg transition-all text-left"
                        >
                            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 group-hover:text-white transition-colors"><path d="m7.5 4.27 9 5.15"></path><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path><path d="m3.3 7 8.7 5 8.7-5"></path><path d="M12 22v-9"></path></svg>
                            </div>
                            <h3 class="text-lg text-gray-900 mb-2 font-bold">Customer Delivery</h3>
                            <p class="text-sm text-gray-600">
                                Deliver products to corporate or retail customers from invoices
                            </p>
                            <div class="mt-4 text-sm text-blue-600 group-hover:text-blue-700 font-medium">
                                Select →
                            </div>
                        </button>

                        {{-- Outlet Transfer --}}
                        <button
                            onclick="selectDeliveryType('outlet-transfer')"
                            class="group p-6 bg-white border-2 border-gray-200 rounded-2xl hover:border-green-500 hover:shadow-lg transition-all text-left"
                        >
                            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 group-hover:text-white transition-colors"><path d="m2 22 1-1h3l9-9"></path><path d="M3 21v-8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8"></path><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M6 2v2"></path><path d="M6 9v2"></path><path d="M18 9v2"></path></svg>
                            </div>
                            <h3 class="text-lg text-gray-900 mb-2 font-bold">Outlet Transfer</h3>
                            <p class="text-sm text-gray-600">
                                Transfer goods from main bakery to company-owned or third-party outlets
                            </p>
                            <div class="mt-4 text-sm text-green-600 group-hover:text-green-700 font-medium">
                                Select →
                            </div>
                        </button>

                        {{-- Ad-hoc Delivery --}}
                        <button
                            onclick="selectDeliveryType('adhoc')"
                            class="group p-6 bg-white border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:shadow-lg transition-all text-left"
                        >
                            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-orange-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600 group-hover:text-white transition-colors"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" x2="8" y1="13" y2="13"></line><line x1="16" x2="8" y1="17" y2="17"></line><line x1="10" x2="8" y1="9" y2="9"></line></svg>
                            </div>
                            <h3 class="text-lg text-gray-900 mb-2 font-bold">Ad-hoc Delivery</h3>
                            <p class="text-sm text-gray-600">
                                One-time special deliveries, samples, or emergency orders
                            </p>
                            <div class="mt-4 text-sm text-orange-600 group-hover:text-orange-700 font-medium">
                                Select →
                            </div>
                        </button>
                    </div>

                    {{-- Include Forms --}}
                    @include('DistributorAndSalesManagement.modals.forms.customerDeliveryForm')
                    @include('DistributorAndSalesManagement.modals.forms.outletTransferForm')
                    @include('DistributorAndSalesManagement.modals.forms.adhocDeliveryForm')

                </div>
            </div>
        </div>
    </div>
</div>
