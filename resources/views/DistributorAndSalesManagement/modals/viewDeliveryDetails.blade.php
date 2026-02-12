<div id="view-delivery-details-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity opacity-0" id="view-delivery-backdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-0 text-center sm:items-center sm:p-0 md:justify-end">
            {{-- Panel --}}
            <div id="view-delivery-details-panel" class="relative transform overflow-hidden bg-white text-left shadow-xl transition-all sm:w-full md:max-w-[480px] h-[90vh] md:h-screen md:rounded-l-3xl translate-x-full duration-300 ease-in-out flex flex-col">
                
                {{-- Header --}}
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 flex-shrink-0">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h2 class="text-2xl text-white mb-1 font-bold">Delivery Details</h2>
                            <p class="text-purple-100" id="detail-invoice-number">#INV-000000</p>
                        </div>
                        <button type="button" onclick="closeViewDeliveryDetailsModal()" class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><line x1="18" x2="6" y1="6" y2="18"></line><line x1="6" x2="18" y1="6" y2="18"></line></svg>
                        </button>
                    </div>

                    <div class="flex gap-2 flex-wrap" id="detail-badges">
                        {{-- Badges will be injected here via JS --}}
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    
                    {{-- Customer Info --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <h3 class="text-gray-900 mb-3 flex items-center gap-2 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span id="detail-customer-label">Delivery Address</span>
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div id="detail-customer-name" class="text-gray-900 font-medium"></div>
                            <div id="detail-outlet-type" class="text-xs text-gray-500 uppercase hidden"></div>
                            <div id="detail-from-location" class="text-xs text-gray-500 hidden"></div>
                            <div id="detail-address" class="text-gray-600"></div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <span id="detail-phone"></span>
                            </div>
                            <div id="detail-email-container" class="flex items-center gap-2 text-gray-600 hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                                <span id="detail-email"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <h3 class="text-gray-900 mb-3 flex items-center gap-2 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><path d="m7.5 4.27 9 5.15"></path><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path><path d="m3.3 7 8.7 5 8.7-5"></path><path d="M12 22v-9"></path></svg>
                            Items to Deliver <span id="detail-items-count" class="text-gray-500 font-normal text-sm"></span>
                        </h3>
                        <div class="space-y-2" id="detail-items-list">
                            {{-- Items injected via JS --}}
                        </div>
                        <div class="pt-2 flex items-center justify-between border-t border-gray-200 mt-2">
                            <span class="text-gray-900 font-medium">Total Value</span>
                            <span class="text-gray-900 font-bold" id="detail-total-value"></span>
                        </div>
                    </div>

                    {{-- Schedule Info --}}
                    <div id="detail-schedule-section" class="bg-white border border-gray-200 rounded-xl p-4">
                        <h3 class="text-gray-900 mb-3 flex items-center gap-2 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            Schedule
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Date</span>
                                <span class="text-gray-900 font-medium" id="detail-schedule-date"></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Time Slot</span>
                                <span class="text-gray-900 capitalize font-medium" id="detail-time-slot"></span>
                            </div>
                            <div class="flex items-center justify-between hidden" id="detail-route-row">
                                <span class="text-gray-600">Route</span>
                                <span class="text-gray-900 font-medium" id="detail-route"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Assignment Info --}}
                    <div id="detail-assignment-section" class="bg-white border border-gray-200 rounded-xl p-4 hidden">
                        <h3 class="text-gray-900 mb-3 flex items-center gap-2 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                            Assignment
                        </h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between hidden" id="detail-driver-row">
                                <span class="text-gray-600 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    Driver
                                </span>
                                <span class="text-gray-900 font-medium" id="detail-driver-name"></span>
                            </div>
                            <div class="flex items-center justify-between hidden" id="detail-vehicle-row">
                                <span class="text-gray-600 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                    Vehicle
                                </span>
                                <span class="text-gray-900 font-medium" id="detail-vehicle-name"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Special Instructions --}}
                    <div id="detail-instructions-section" class="bg-orange-50 border border-orange-200 rounded-xl p-4 hidden">
                        <h3 class="text-gray-900 mb-2 flex items-center gap-2 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg>
                            Special Instructions
                        </h3>
                        <p class="text-sm text-gray-700" id="detail-instructions"></p>
                    </div>

                    {{-- Delivery Info (Completed) --}}
                    <div id="detail-delivered-section" class="bg-green-50 border border-green-200 rounded-xl p-4 hidden">
                        <h3 class="text-gray-900 mb-2 flex items-center gap-2 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            Delivered
                        </h3>
                        <div class="space-y-1 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Time</span>
                                <span class="text-gray-900 font-medium" id="detail-delivered-time"></span>
                            </div>
                            <div class="flex items-center justify-between hidden" id="detail-delivered-by-row">
                                <span class="text-gray-600">Delivered by</span>
                                <span class="text-gray-900 font-medium" id="detail-delivered-by"></span>
                            </div>
                        </div>
                    </div>

                     {{-- Failure Info --}}
                     <div id="detail-failure-section" class="bg-red-50 border border-red-200 rounded-xl p-4 hidden">
                        <h3 class="text-gray-900 mb-2 flex items-center gap-2 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg>
                            Failure Reason
                        </h3>
                        <p class="text-sm text-gray-700" id="detail-failure-reason"></p>
                    </div>

                    {{-- Notes --}}
                    <div id="detail-notes-section" class="bg-white border border-gray-200 rounded-xl p-4 hidden">
                        <h3 class="text-gray-900 mb-2 font-semibold">Notes</h3>
                        <p class="text-sm text-gray-600" id="detail-notes"></p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex-shrink-0 p-4 bg-gray-50 border-t border-gray-200 space-y-3">
                    <button id="btn-mark-transit" class="w-full h-12 bg-purple-600 hover:bg-purple-700 text-white rounded-xl transition-colors flex items-center justify-center gap-2 font-medium hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                        Mark In Transit
                    </button>

                    <div id="action-delivery-completion" class="grid grid-cols-2 gap-3 hidden">
                        <button id="btn-mark-delivered" class="h-12 bg-green-600 hover:bg-green-700 text-white rounded-xl transition-colors flex items-center justify-center gap-2 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            Delivered
                        </button>
                        <button id="btn-mark-failed" class="h-12 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors flex items-center justify-center gap-2 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg>
                            Failed
                        </button>
                    </div>

                    <button onclick="toast.info('Print functionality - Coming soon')" class="w-full h-12 bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 rounded-xl transition-colors flex items-center justify-center gap-2 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" x2="8" y1="13" y2="13"></line><line x1="16" x2="8" y1="17" y2="17"></line><line x1="10" x2="8" y1="9" y2="9"></line></svg>
                        Print Delivery Note
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>