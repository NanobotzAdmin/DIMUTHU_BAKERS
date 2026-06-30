{{-- resources/views/modals/view-order.blade.php --}}

{{-- MODAL HTML STRUCTURE --}}
<div id="view-order-modal"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-2 md:p-4 transition-opacity duration-300">
    <div class="bg-white rounded-2xl md:rounded-3xl shadow-2xl w-full max-w-6xl max-h-[95vh] md:max-h-[90vh] overflow-hidden flex flex-col transform transition-all duration-300 scale-95"
        id="view-order-modal-content">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-4 md:px-8 py-4 md:py-6">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-4">
                    {{-- Dynamic Channel Icon --}}
                    <div id="modal-channel-icon-container"
                        class="w-10 h-10 md:w-14 md:h-14 bg-white/20 rounded-xl md:rounded-2xl flex items-center justify-center flex-shrink-0 text-white">
                    </div>
                    <div>
                        <div class="mb-1 md:mb-2">
                            <h2 id="modal-order-number" class="text-lg md:text-2xl text-white font-bold break-all md:break-normal leading-tight"></h2>
                        </div>
                        <div class="flex items-center gap-1.5 md:gap-3 mb-2 flex-wrap">
                            <span id="modal-channel-badge"
                                class="border px-1.5 py-0.5 md:px-3 md:py-1 rounded-md md:rounded-lg text-[10px] md:text-sm font-medium"></span>
                            <span id="modal-status-badge"
                                class="border px-1.5 py-0.5 md:px-3 md:py-1 rounded-md md:rounded-lg flex items-center gap-1 md:gap-1.5 text-[10px] md:text-sm font-medium"></span>
                            <span id="modal-priority-badge"
                                class="border px-1.5 py-0.5 md:px-3 md:py-1 rounded-md md:rounded-lg text-[10px] md:text-sm font-medium hidden"></span>
                            <span id="modal-downloaded-badge"
                                class="bg-green-100 text-green-700 border border-green-300 px-1.5 py-0.5 md:px-3 md:py-1 rounded-md md:rounded-lg items-center gap-1 text-[10px] md:text-sm font-semibold hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 md:w-4 md:h-4">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Downloaded
                            </span>
                        </div>
                        <div class="flex flex-col md:flex-row md:flex-wrap md:items-center gap-1 md:gap-x-6 md:gap-y-2 text-purple-100 text-[11px] md:text-sm mt-2">
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-3 h-3 md:w-4 md:h-4">
                                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                    <line x1="16" x2="16" y1="2" y2="6" />
                                    <line x1="8" x2="8" y1="2" y2="6" />
                                    <line x1="3" x2="21" y1="10" y2="10" />
                                </svg>
                                <span id="modal-header-created-at"></span>
                            </div>
                            <div class="flex items-center gap-1.5 md:border-l md:border-white/20 md:pl-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="w-3 h-3 md:w-4 md:h-4">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                <span id="modal-header-outlet-code"></span>
                            </div>
                            <div class="flex items-center gap-1.5 md:border-l md:border-white/20 md:pl-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 md:w-4 md:h-4"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span id="modal-header-agent-name" class="font-bold"></span>
                            </div>
                            <div class="flex items-center gap-1.5 md:border-l md:border-white/20 md:pl-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 md:w-4 md:h-4"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <span id="modal-header-agent-phone"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <button onclick="closeOrderModal()"
                    class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 18 18" />
                    </svg>
                </button>
            </div>

            {{-- Quick Actions --}}
            <div class="flex gap-2 mt-4 flex-wrap">
                <button id="modal-print-so-btn" onclick="printModalSalesOrder('print')"
                    class="h-8 md:h-9 px-3 md:px-4 bg-white/20 hover:bg-white/30 text-white rounded-lg flex items-center justify-center gap-1.5 md:gap-2 text-xs md:text-sm transition-colors w-full md:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <polyline points="6 9 6 2 18 2 18 9" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <rect width="12" height="8" x="6" y="14" />
                    </svg>
                    Print Sales Order
                </button>
                <button id="modal-download-so-btn" onclick="printModalSalesOrder('download')"
                    class="h-8 md:h-9 px-3 md:px-4 bg-white/20 hover:bg-white/30 text-white rounded-lg flex items-center justify-center gap-1.5 md:gap-2 text-xs md:text-sm transition-colors w-full md:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" x2="12" y1="15" y2="3" />
                    </svg>
                    Download Sales Order
                </button>
                <button id="modal-print-btn" onclick="printModalDispatchNote()"
                    class="h-8 md:h-9 px-3 md:px-4 bg-white/20 hover:bg-white/30 text-white rounded-lg flex items-center justify-center gap-1.5 md:gap-2 text-xs md:text-sm transition-colors hidden w-full md:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <polyline points="6 9 6 2 18 2 18 9" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <rect width="12" height="8" x="6" y="14" />
                    </svg>
                    Download Dispatch Note
                </button>
            </div>
        </div>

        {{-- Scrollable Content --}}
        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <div class="max-w-5xl mx-auto space-y-6">

                {{-- Progress Bar --}}
                <div id="modal-progress-section"
                    class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-4 md:p-6 border-2 border-purple-200">
                    <h3 class="text-base md:text-lg text-gray-900 mb-4 font-semibold">Order Progress</h3>
                    <div class="flex items-start justify-between overflow-x-auto pb-2" id="modal-progress-steps">
                    </div>
                </div>

                {{-- Order Items - MOVED TO TOP --}}
                <div class="bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                        <h3 class="text-base md:text-lg text-gray-900 font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-purple-600">
                                <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"></path>
                            </svg>
                            Order Items
                        </h3>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="space-y-3 overflow-x-auto" id="modal-order-items-container">
                            <!-- Items will be injected here via JS -->
                        </div>
                    </div>
                </div>

                {{-- Order Notes --}}
                <div id="modal-notes-section" class="bg-amber-50 rounded-2xl p-6 border-2 border-amber-200 hidden">
                    <h3 class="text-lg text-gray-900 mb-3 flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                        </svg>
                        Order Notes / Special Instructions
                    </h3>
                    <p id="modal-order-notes" class="text-gray-700 whitespace-pre-wrap font-medium text-sm"></p>
                </div>

                {{-- Rejection Information --}}
                <div id="modal-rejection-section" class="bg-red-50 rounded-2xl p-6 border-2 border-red-200 hidden">
                    <h3 class="text-lg text-gray-900 mb-3 flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y1="16" />
                        </svg>
                        Rejection Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-3">
                        <div>
                            <span class="text-gray-500 font-medium">Rejected By:</span>
                            <span id="modal-rejected-by" class="text-gray-900 font-bold ml-1"></span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-medium">Rejected At:</span>
                            <span id="modal-rejected-at" class="text-gray-900 font-medium ml-1"></span>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-500 font-medium text-xs mb-1">Reason for Rejection</p>
                        <p id="modal-rejection-reason" class="text-red-700 whitespace-pre-wrap font-semibold text-sm bg-red-100/50 p-3 rounded-lg border border-red-200"></p>
                    </div>
                </div>

                {{-- Customer Information --}}
                <div class="bg-gray-50 rounded-2xl p-4 md:p-6">
                    <h3 class="text-base md:text-lg text-gray-900 mb-4 flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Agent Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Agent Name</p>
                            <p id="modal-cust-name" class="text-gray-900 font-bold text-lg"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Phone Number</p>
                            <p id="modal-cust-phone" class="text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Agent Code</p>
                            <p id="modal-outlet-code" class="text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Agent Type</p>
                            <p id="modal-agent-type" class="text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">NIC Number</p>
                            <p id="modal-agent-nic" class="text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Outstanding Balance</p>
                            <p id="modal-agent-balance" class="text-red-600 font-bold"></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500 mb-1">Address</p>
                            <p id="modal-agent-address" class="text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Email Address</p>
                            <p id="modal-agent-email" class="text-gray-900 font-medium"></p>
                        </div>
                        <div class="hidden">
                            <p class="text-sm text-gray-500 mb-1">Created At</p>
                            <p id="modal-created-at" class="text-gray-900 font-medium"></p>
                        </div>
                        <div class="hidden">
                            <p class="text-sm text-gray-500 mb-1">Destination Branch</p>
                            <p id="modal-req-branch" class="text-gray-900 font-medium"></p>
                        </div>
                        <div class="hidden">
                            <p class="text-sm text-gray-500 mb-1">Source Branch</p>
                            <p id="modal-req-from-branch" class="text-gray-900 font-medium"></p>
                        </div>
                    </div>
                </div>

                {{-- Delivery/Pickup Info --}}
                <div class="bg-gray-50 rounded-2xl p-4 md:p-6">
                    <h3 id="modal-delivery-title"
                        class="text-base md:text-lg text-gray-900 mb-4 flex items-center gap-2 font-semibold">
                    </h3>
                    <div class="space-y-3" id="modal-delivery-content">
                    </div>
                </div>

                {{-- Special Event Details (Hidden by default) --}}
                <div id="modal-special-section" class="bg-purple-50 rounded-2xl p-6 border-2 border-purple-200 hidden">
                    <h3 class="text-lg text-gray-900 mb-4 flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="text-purple-600">
                            <rect x="3" y="8" width="18" height="4" rx="1" />
                            <path d="M12 8v13" />
                            <path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7" />
                            <path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5" />
                        </svg>
                        Event Details
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Event Type</p>
                            <p id="modal-event-type" class="text-gray-900 capitalize font-medium"></p>
                        </div>
                    </div>
                </div>


                {{-- Recurring Info (Hidden by default) --}}
                <div id="modal-recurring-section"
                    class="bg-orange-50 rounded-2xl p-6 border-2 border-orange-200 hidden">
                    <h3 class="text-lg text-gray-900 mb-3 flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="text-orange-600">
                            <path d="m17 2 4 4-4 4" />
                            <path d="M3 11v-1a4 4 0 0 1 4-4h14" />
                            <path d="m7 22-4-4 4-4" />
                            <path d="M21 13v1a4 4 0 0 1-4 4H3" />
                        </svg>
                        Recurring Order
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Instance</span>
                            <span id="modal-recurring-instance" class="text-gray-900 font-bold"></span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-gray-50 rounded-2xl p-6 hidden">
                    <h3 class="text-lg text-gray-900 mb-4 font-semibold">Actions</h3>
                    <div class="space-y-2" id="modal-actions-container">
                        {{-- Buttons injected via JS --}}
                    </div>
                </div>

                {{-- Payment Details --}}
                <div id="modal-payments-section" class="bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg text-gray-900 font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-emerald-600">
                                <rect width="20" height="14" x="2" y="5" rx="2"></rect>
                                <line x1="2" x2="22" y1="10" y2="10"></line>
                            </svg>
                            Payment Details
                        </h3>
                    </div>
                    <div class="p-6" id="modal-payments-container">
                        {{-- Payment records injected via JS --}}
                    </div>
                </div>

                {{-- Audit Trail --}}
                <div id="modal-audit-section" class="bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg text-gray-900 font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-amber-600">
                                <path d="M12 8v4l3 3"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            Audit Trail
                        </h3>
                    </div>
                    <div class="p-6" id="modal-audit-container">
                        {{-- Audit trail items injected via JS --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 px-4 md:px-8 py-4 md:py-5 flex items-center justify-between border-t border-gray-200">
            <div class="flex items-center gap-2 md:gap-3 flex-wrap" id="modal-footer-actions">
                {{-- Primary action buttons will be injected here --}}
            </div>
            <button onclick="closeOrderModal()"
                class="h-10 md:h-11 px-4 md:px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-colors font-medium text-sm md:text-base">
                Close
            </button>
        </div>
    </div>
</div>

{{-- MODAL LOGIC --}}
<script>
    // Inject Auth Branch ID (only if not already defined)
    if (typeof authCurrentBranchId === 'undefined') {
        var authCurrentBranchId = {{ auth()->user()->current_branch_id ?? 'null' }};
    }

    // Define helper icons
    const modalIcons = {
        store: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2 2 0 0 1 4 12v0a2 2 0 0 1-2-2V7"/></svg>`,
        gift: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/></svg>`,
        repeat: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>`,
        check: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`,
        clock: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
        package: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>`,
        truck: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>`,
        alert: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>`,
        x: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`
    };

    function disableButton(btn) {
        if (!btn) return;
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    function enableButton(btn) {
        if (!btn) return;
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    function disableAllOrderModalButtons() {
        disableButton(document.getElementById('btn-order-approve'));
        disableButton(document.getElementById('btn-order-reject'));
        disableButton(document.getElementById('btn-order-dispatch'));
    }

    function enableAllOrderModalButtons() {
        enableButton(document.getElementById('btn-order-approve'));
        enableButton(document.getElementById('btn-order-reject'));
        enableButton(document.getElementById('btn-order-dispatch'));
    }

    function getModalChannelConfig(channel) {
        const configs = {
            'pos-pickup': {
                color: 'bg-blue-100 text-blue-700 border-blue-300',
                label: 'POS Pickup',
                icon: modalIcons.store
            },
            'special-order': {
                color: 'bg-purple-100 text-purple-700 border-purple-300',
                label: 'Special Order',
                icon: modalIcons.gift
            },
            'scheduled-production': {
                color: 'bg-orange-100 text-orange-700 border-orange-300',
                label: 'Scheduled',
                icon: modalIcons.repeat
            },
            'agent-order': {
                color: 'bg-green-100 text-green-700 border-green-300',
                label: 'Agent Order',
                icon: modalIcons.truck
            }
        };
        return configs[channel] || configs['pos-pickup'];
    }

    function getModalStatusConfig(status) {
        const configs = {
            'pending-approval': {
                color: 'bg-yellow-100 text-yellow-700 border-yellow-300',
                label: 'Pending Approval',
                icon: modalIcons.clock
            },
            'approved': {
                color: 'bg-blue-100 text-blue-700 border-blue-300',
                label: 'Approved',
                icon: modalIcons.check
            },
            'in-production': {
                color: 'bg-indigo-100 text-indigo-700 border-indigo-300',
                label: 'In Production',
                icon: modalIcons.store
            },
            'ready-for-pickup': {
                color: 'bg-teal-100 text-teal-700 border-teal-300',
                label: 'Ready',
                icon: modalIcons.package
            },
            'out-for-delivery': {
                color: 'bg-orange-100 text-orange-700 border-orange-300',
                label: 'Out for Delivery',
                icon: modalIcons.truck
            },
            'dispatch-confirmed': {
                color: 'bg-emerald-100 text-emerald-700 border-emerald-300',
                label: 'Dispatch Confirmed',
                icon: modalIcons.check
            },
            'completed': {
                color: 'bg-green-100 text-green-700 border-green-300',
                label: 'Completed',
                icon: modalIcons.check
            },
            'rejected': {
                color: 'bg-red-100 text-red-700 border-red-300',
                label: 'Rejected',
                icon: modalIcons.x
            }
        };
        return configs[status] || {
            color: 'bg-gray-100',
            label: status,
            icon: ''
        };
    }

    function openOrderModal(button) {
        // Parse the data attribute
        const order = JSON.parse(button.dataset.order);
        const modal = document.getElementById('view-order-modal');
        const modalContent = document.getElementById('view-order-modal-content');

        // Populate Fields
        document.getElementById('modal-order-number').innerText = order.orderNumber;

        const channelConf = getModalChannelConfig(order.channel);
        const statusConf = getModalStatusConfig(order.status);

        // Badges
        const chBadge = document.getElementById('modal-channel-badge');
        chBadge.className = `border px-3 py-1 rounded-lg text-sm font-medium ${channelConf.color}`;
        chBadge.innerText = channelConf.label;
        document.getElementById('modal-channel-icon-container').innerHTML = channelConf.icon;

        const stBadge = document.getElementById('modal-status-badge');
        stBadge.className =
            `border px-1.5 py-0.5 md:px-3 md:py-1 rounded-md md:rounded-lg flex items-center gap-1 md:gap-1.5 text-[10px] md:text-sm font-medium ${statusConf.color}`;
        
        // Add specific sizing to the status icon SVG string before inserting
        let statusIconHtml = statusConf.icon;
        if(statusIconHtml) {
            statusIconHtml = statusIconHtml.replace('<svg', '<svg class="w-3 h-3 md:w-4 md:h-4"');
        }
        stBadge.innerHTML = `${statusIconHtml} ${statusConf.label}`;

        // Priority
        const prBadge = document.getElementById('modal-priority-badge');
        if (order.priority !== 'normal') {
            prBadge.classList.remove('hidden');
            prBadge.innerText = order.priority.charAt(0).toUpperCase() + order.priority.slice(1);
            prBadge.className =
                `border px-3 py-1 rounded-lg text-sm font-medium ${order.priority === 'high' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100'}`;
        } else {
            prBadge.classList.add('hidden');
        }

        // Downloaded Badge
        const dnBadge = document.getElementById('modal-downloaded-badge');
        if (order.is_downloaded) {
            dnBadge.classList.remove('hidden');
            dnBadge.classList.add('flex');
        } else {
            dnBadge.classList.add('hidden');
            dnBadge.classList.remove('flex');
        }

        // Print Button Visibility
        const printBtn = document.getElementById('modal-print-btn');
        if (['out-for-delivery', 'dispatch-confirmed', 'completed'].includes(order.status)) {
            printBtn.classList.remove('hidden');
            printBtn.dataset.orderId = order.id;
        } else {
            printBtn.classList.add('hidden');
        }

        // Print SO Buttons dataset setup
        const printSoBtn = document.getElementById('modal-print-so-btn');
        const downloadSoBtn = document.getElementById('modal-download-so-btn');
        if (printSoBtn) printSoBtn.dataset.orderId = order.id;
        if (downloadSoBtn) downloadSoBtn.dataset.orderId = order.id;

        // Details
        const createdAt = window.formatDateTimeGMT(order.created_at || new Date());
        document.getElementById('modal-header-created-at').innerText = createdAt;
        document.getElementById('modal-created-at').innerText = createdAt;

        document.getElementById('modal-header-outlet-code').innerText = order.outletCode || 'N/A';
        document.getElementById('modal-outlet-code').innerText = order.outletCode || 'N/A';

        document.getElementById('modal-header-agent-name').innerText = order.customerName;
        document.getElementById('modal-header-agent-phone').innerText = order.customerPhone || 'N/A';

        document.getElementById('modal-cust-name').innerText = order.customerName;
        document.getElementById('modal-cust-phone').innerText = order.customerPhone || 'N/A';

        // Agent detailed info
        if (order.agent_info) {
            document.getElementById('modal-agent-type').innerText = order.agent_info.type || 'N/A';
            document.getElementById('modal-agent-nic').innerText = order.agent_info.nic || 'N/A';
            document.getElementById('modal-agent-balance').innerText = 'Rs ' + (order.agent_info.balance || '0.00');
            document.getElementById('modal-agent-address').innerText = order.agent_info.address || 'N/A';
            document.getElementById('modal-agent-email').innerText = order.agent_info.email || 'N/A';
        } else {
            document.getElementById('modal-agent-type').innerText = 'N/A';
            document.getElementById('modal-agent-nic').innerText = 'N/A';
            document.getElementById('modal-agent-balance').innerText = 'N/A';
            document.getElementById('modal-agent-address').innerText = 'N/A';
            document.getElementById('modal-agent-email').innerText = 'N/A';
        }

        document.getElementById('modal-req-branch').innerText = order.requestBranchName || 'N/A';
        document.getElementById('modal-req-from-branch').innerText = order.reqFromBranchName || 'N/A';

        // Delivery/Pickup
        const deliveryTitle = document.getElementById('modal-delivery-title');
        const deliveryContent = document.getElementById('modal-delivery-content');

        if (order.deliveryMethod === 'pickup') {
            deliveryTitle.innerHTML = `${modalIcons.package} Pickup Information`;
            deliveryContent.innerHTML = `
                <div>
                    <p class="text-sm text-gray-500 mb-1">Pickup Date</p>
                    <p class="text-gray-900 font-medium">${window.formatDateTimeGMT(order.pickupDate)}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Location</p>
                    <p class="text-gray-900 font-medium">${order.outletCode}</p>
                </div>
            `;
        } else {
            deliveryTitle.innerHTML = `${modalIcons.truck} Delivery Information`;
            deliveryContent.innerHTML = `
                 <div>
                    <p class="text-sm text-gray-500 mb-1">Delivery Date</p>
                    <p class="text-gray-900 font-medium">${(order.deliveryDate)}</p>
                </div>
            `;
        }

        // Sections Visibility
        const recSection = document.getElementById('modal-recurring-section');
        if (order.isRecurring) {
            recSection.classList.remove('hidden');
            document.getElementById('modal-recurring-instance').innerText = '#' + order.instanceNumber;
        } else {
            recSection.classList.add('hidden');
        }

        const specialSection = document.getElementById('modal-special-section');
        if (order.channel === 'special-order') {
            specialSection.classList.remove('hidden');
            document.getElementById('modal-event-type').innerText = order.eventType || 'General';
        } else {
            specialSection.classList.add('hidden');
        }

        // Populate Order Notes
        const notesSection = document.getElementById('modal-notes-section');
        const notesText = document.getElementById('modal-order-notes');
        if (order.notes && order.notes.trim() !== '') {
            notesSection.classList.remove('hidden');
            notesText.innerText = order.notes;
        }

        // Populate Rejection Details
        const rejectionSection = document.getElementById('modal-rejection-section');
        if (order.status === 'rejected') {
            rejectionSection.classList.remove('hidden');
            document.getElementById('modal-rejected-by').innerText = order.rejectedByName || 'N/A';
            document.getElementById('modal-rejected-at').innerText = order.rejectedAt || 'N/A';
            document.getElementById('modal-rejection-reason').innerText = order.rejectionReason || 'No reason provided';
        } else {
            rejectionSection.classList.add('hidden');
        }

        // Streamlined Progress
        // Steps: Pending -> Approved -> In Production -> Dispatched -> Completed
        if (order.status === 'cancelled' || order.status === 'rejected') {
            document.getElementById('modal-progress-section').classList.add('hidden');
        } else {
            const steps = ['pending-approval', 'approved', 'out-for-delivery', 'completed'];
            const stepContainer = document.getElementById('modal-progress-steps');
            let stepsHtml = '';

            document.getElementById('modal-progress-section').classList.remove('hidden');
            steps.forEach((step, index) => {
                const isCompleted = steps.indexOf(order.status) >= index;
                const labels = {
                    'pending-approval': 'Pending',
                    'approved': 'Approved',
                    'out-for-delivery': 'Dispatched',
                    'completed': 'Completed'
                };
                const colorClass = isCompleted ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-400';

                // Add Line before step (except the first one)
                if (index > 0) {
                    const isLineColored = steps.indexOf(order.status) >= index;
                    const lineColor = isLineColored ? 'bg-purple-600' : 'bg-gray-200';
                    stepsHtml +=
                        `<div class="flex-1 h-0.5 md:h-1 rounded ${lineColor} mx-1 md:mx-2 mt-[10px] md:mt-[14px] min-w-[1rem] md:min-w-[1.5rem]"></div>`;
                }

                stepsHtml += `
                    <div class="flex flex-col items-center flex-shrink-0 min-w-[40px] md:min-w-[50px]">
                        <div class="w-5 h-5 md:w-8 md:h-8 rounded-full flex items-center justify-center transition-all ${colorClass}">
                            <div class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-current"></div>
                        </div>
                        <p class="text-[9px] md:text-[10px] mt-1.5 md:mt-2 text-center text-gray-400 font-medium ${isCompleted ? 'text-purple-700' : ''}">${labels[step]}</p>
                    </div>
                `;
            });
            stepContainer.innerHTML = stepsHtml;
        }

        // Action Buttons with Permissions - MOVED UP to avoid temporal dead zone
        const currentBranchId = authCurrentBranchId;
        const isSource = (currentBranchId === null) || (currentBranchId == order.reqFromBranchId) || (order
            .reqFromBranchId === null);
        const isRequester = currentBranchId == order.umBranchId;

        // Render Order Items with Better UI
        const itemsContainer = document.getElementById('modal-order-items-container');
        if (order.products && order.products.length > 0) {
            // Check if editable
            const editableStatuses = ['pending-approval', 'approved', 'rejected', 'out-for-delivery',
                'ready-for-pickup'
            ];
            const isEditable = editableStatuses.includes(order.status);

            // Helper function to format numbers (remove unnecessary decimals)
            const formatQuantity = (qty) => {
                const num = parseFloat(qty);
                return num % 1 === 0 ? num.toString() : num.toFixed(3).replace(/\.?0+$/, '');
            };

            // Create table HTML
            let tableHtml = `
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[500px] md:min-w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Product</th>
                                <th class="text-center py-3 px-2 text-sm font-semibold text-gray-700 w-28">Requested</th>
                                <th class="text-center py-3 px-2 text-sm font-semibold text-gray-700 w-28">${order.status === 'pending-approval' ? 'Approve' : 'Dispatch'}</th>
                                <th class="text-right py-3 px-2 text-sm font-semibold text-gray-700 w-32">Unit Price</th>
                                <th class="text-right py-3 px-2 text-sm font-semibold text-gray-700 w-32">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
            `;

            let totalAmount = 0;
            order.products.forEach((item, index) => {
                const bgClass = index % 2 === 0 ? 'bg-gray-50' : 'bg-white';
                const requestedQty = formatQuantity(item.quantity);
                const defaultQty = Math.round(parseFloat(item.quantity)) || 0;

                const dispatchInput = isEditable ?
                    `<input type="number" min="0" step="1" onkeydown="if(['.', 'e', 'E', '-', '+'].includes(event.key)) event.preventDefault();" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="order-item-qty w-full px-2 py-1.5 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-purple-500 focus:border-purple-500" value="${defaultQty}" data-item-id="${item.product_item_id}">` :
                    `<span class="font-medium text-gray-900">${requestedQty}</span>`;

                // Calculate subtotal correctly
                const subtotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
                totalAmount += subtotal;

                tableHtml += `
                    <tr class="${bgClass} hover:bg-purple-50 transition-colors">
                        <td class="py-2 md:py-3 px-2">
                            <div>
                                <p class="text-gray-900 font-medium text-xs md:text-sm">${item.name}</p>
                                ${item.notes ? `<p class="text-[10px] md:text-xs text-gray-500 mt-0.5">${item.notes}</p>` : ''}
                            </div>
                        </td>
                        <td class="py-2 md:py-3 px-2 text-center">
                            <span class="inline-block px-2 md:px-3 py-0.5 md:py-1 bg-gray-100 text-gray-700 rounded-lg font-medium text-xs md:text-sm">
                                ${requestedQty}${item.dispatched_quantity != null && parseFloat(item.dispatched_quantity) != parseFloat(item.quantity) ? ` <span class="text-orange-600 font-semibold">(${formatQuantity(item.dispatched_quantity)})</span>` : ''}
                            </span>
                        </td>
                        <td class="py-2 md:py-3 px-2 text-center">
                            ${dispatchInput}
                        </td>
                        <td class="py-2 md:py-3 px-2 text-right text-gray-700 text-xs md:text-sm">
                            Rs ${parseFloat(item.unit_price).toLocaleString('en-LK', { minimumFractionDigits: 2 })}
                        </td>
                        <td class="py-2 md:py-3 px-2 text-right font-semibold text-gray-900 text-xs md:text-sm">
                            Rs ${subtotal.toLocaleString('en-LK', { minimumFractionDigits: 2 })}
                        </td>
                    </tr>
                `;
            });

            tableHtml += `
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-300 bg-gray-100">
                                <td colspan="4" class="py-4 px-2 text-right font-bold text-gray-700 uppercase tracking-wider">Grand Total</td>
                                <td class="py-4 px-2 text-right font-black text-purple-700 text-lg">
                                    Rs ${totalAmount.toLocaleString('en-LK', { minimumFractionDigits: 2 })}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;

            itemsContainer.innerHTML = tableHtml;
        } else {
            itemsContainer.innerHTML =
                '<p class="text-gray-500 text-sm text-center py-8">No items found for this order.</p>';
        }

        // Footer Action Buttons (Approve & Reject)
        const footerActionsContainer = document.getElementById('modal-footer-actions');
        let footerActionsHtml = '';

        // Action Buttons (Other actions like Dispatch)
        const actionsContainer = document.getElementById('modal-actions-container');
        let actionsHtml = '';
        console.log(order.reqFromBranchId)

        // Footer buttons: Approve and Reject
        // Footer buttons: Approve and Reject
        // MODIFIED: 0 (Pending) -> 1 (Approved) -> 5 (Dispatched)

        // 1. Status 0 (Pending Approval) -> Show Approve Button (Transitions to 1) and Reject Button (Transitions to 2)
        if (order.status === 'pending-approval') {
            footerActionsHtml +=
                `<button id="btn-order-approve" onclick="approveOrder('${order.id}')" class="h-10 md:h-11 px-4 md:px-6 bg-purple-600 hover:bg-purple-700 text-white rounded-xl flex items-center justify-center gap-2 transition-colors font-medium text-sm md:text-base">${modalIcons.check} Approve Order</button>`;

            footerActionsHtml +=
                `<button id="btn-order-reject" onclick="rejectOrder('${order.id}')" class="h-10 md:h-11 px-4 md:px-6 bg-red-600 hover:bg-red-700 text-white rounded-xl flex items-center justify-center gap-2 transition-colors font-medium text-sm md:text-base">${modalIcons.x} Reject Order</button>`;
        }

        // 2. Status 1 (Approved) -> Show Dispatch Button (Transitions to 5)
        if (order.status === 'approved') {
            footerActionsHtml +=
                `<button id="btn-order-dispatch" onclick="dispatchOrder('${order.id}')" class="h-10 md:h-11 px-4 md:px-6 bg-orange-600 hover:bg-orange-700 text-white rounded-xl flex items-center justify-center gap-2 transition-colors font-medium text-sm md:text-base">${modalIcons.truck} Dispatch Order</button>`;
        }

        // 3. Status 5 (Dispatched) -> Completed is handled via mobile app only
        // No web action button for completing orders

        footerActionsContainer.innerHTML = footerActionsHtml;
        actionsContainer.innerHTML = actionsHtml;

        // Render Payment Details
        const paymentsContainer = document.getElementById('modal-payments-container');
        if (order.payment_records && order.payment_records.length > 0) {
            let paymentsHtml = `
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Date</th>
                                <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Method</th>
                                <th class="text-right py-3 px-2 text-sm font-semibold text-gray-700">Amount</th>
                                <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Reference</th>
                                <th class="text-center py-3 px-2 text-sm font-semibold text-gray-700">Status</th>
                                <th class="text-center py-3 px-2 text-sm font-semibold text-gray-700 hidden">Action</th>
                                <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
            `;
            let totalPaid = 0;
            order.payment_records.forEach((payment, index) => {
                const bgClass = index % 2 === 0 ? 'bg-gray-50' : 'bg-white';
                const statusClass = payment.status === 'Active'
                    ? 'bg-green-100 text-green-700'
                    : (payment.status === 'Pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                
                // Approve button for pending payments
                let actionBtn = '';
                if (payment.status_raw === 1) {
                    const approvalUrl = `{{ route('orderManagement.paymentApprovalView', ['id' => ':id']) }}`.replace(':id', payment.id);
                    actionBtn = `
                        <a href="${approvalUrl}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <i class="bi bi-shield-check mr-1"></i> Review
                        </a>
                    `;
                }

                const amount = parseFloat(payment.amount.replace(/,/g, ''));
                if (payment.status === 'Active') totalPaid += amount;

                paymentsHtml += `
                    <tr class="${bgClass} hover:bg-green-50 transition-colors">
                        <td class="py-3 px-2 text-sm text-gray-700">${window.formatDateTimeGMT(payment.date)}</td>
                        <td class="py-3 px-2 text-sm text-gray-700">${payment.method}</td>
                        <td class="py-3 px-2 text-sm text-right font-semibold text-gray-900">Rs ${payment.amount}</td>
                        <td class="py-3 px-2 text-sm text-gray-700">${payment.reference}</td>
                        <td class="py-3 px-2 text-center">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium ${statusClass}">${payment.status}</span>
                                </td>
                                <td class="hidden">${actionBtn}</td>
                        <td class="py-3 px-2 text-sm text-gray-500">${payment.notes}</td>
                    </tr>
                `;
            });
            paymentsHtml += `
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-300 bg-gray-100">
                                <td colspan="2" class="py-3 px-2 text-right font-bold text-gray-700">Total Paid</td>
                                <td class="py-3 px-2 text-right font-black text-emerald-700">Rs ${totalPaid.toLocaleString('en-LK', { minimumFractionDigits: 2 })}</td>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
            paymentsContainer.innerHTML = paymentsHtml;
        } else {
            paymentsContainer.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No payments recorded yet.</p>';
        }

        // Render Audit Trail
        const auditContainer = document.getElementById('modal-audit-container');
        if (order.history && order.history.length > 0) {
            let auditHtml = '<div class="relative">';
            // Vertical line
            auditHtml += '<div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-gray-200"></div>';

            order.history.forEach((entry, index) => {
                const isLast = index === order.history.length - 1;
                const dotColor = isLast ? 'bg-purple-600 ring-4 ring-purple-100' : 'bg-gray-400';

                auditHtml += `
                    <div class="relative flex gap-4 pb-6 ${isLast ? 'pb-0' : ''}">
                        <div class="flex-shrink-0 w-[31px] flex justify-center pt-1">
                            <div class="w-3 h-3 rounded-full ${dotColor} z-10"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-semibold text-gray-900 text-sm">${entry.action}</span>
                                <span class="text-xs text-gray-400">${window.formatDateTimeGMT(entry.created_at)}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-0.5">${entry.description || ''}</p>
                            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                ${entry.user_name}
                            </p>
                        </div>
                    </div>
                `;
            });
            auditHtml += '</div>';
            auditContainer.innerHTML = auditHtml;
        } else {
            auditContainer.innerHTML = '<p class="text-gray-500 text-sm text-center py-4">No history recorded yet.</p>';
        }

        // Show Modal with Animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function printModalDispatchNote() {
        const orderId = document.getElementById('modal-print-btn').dataset.orderId;
        const url = `{{ route('orderManagement.printDispatchNote', ['id' => ':id']) }}`.replace(':id', orderId);
        window.open(url, '_blank');
    }

    function printModalSalesOrder(action) {
        const btn = document.getElementById('modal-print-so-btn');
        const orderId = btn ? btn.dataset.orderId : null;
        if (!orderId) return;
        const url = `{{ route('orderManagement.printSalesOrder', ['id' => ':id']) }}`.replace(':id', orderId) + `?action=${action}`;
        window.open(url, '_blank');
    }

    // Dispatch Function
    function dispatchOrder(orderId) {
        // Collect updated quantities
        const items = [];
        document.querySelectorAll('.order-item-qty').forEach(input => {
            items.push({
                product_item_id: input.dataset.itemId,
                quantity: input.value
            });
        });

        Swal.fire({
            title: 'Dispatch Order?',
            text: "This will dispatch the order with the entered quantities.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Dispatch it!'
        }).then((result) => {
            if (result.isConfirmed) {
                disableAllOrderModalButtons();
                // Call API
                fetch('{{ route('orderManagement.dispatchOrder') }}', { // Need to define route
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        items: items
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            enableAllOrderModalButtons();
                            return response.text().then(text => {
                                throw new Error(text)
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Dispatched!',
                                'Order has been dispatched.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            enableAllOrderModalButtons();
                            Swal.fire(
                                'Error!',
                                data.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        enableAllOrderModalButtons();
                        console.error('Dispatch Error:', error);
                        // Try to extract message if it's JSON string in error
                        let msg = 'Server error occurred.';
                        try {
                            // Check if error message is JSON
                            const errObj = JSON.parse(error.message);
                            if (errObj.message) msg = errObj.message;
                        } catch (e) {
                            // If HTML or plain text, use specific substring or generic
                            if (error.message && error.message.length < 200) msg = error.message;
                        }

                        Swal.fire(
                            'Error!',
                            msg,
                            'error'
                        );
                    });
            }
        });
    }

    // Approve Dispatch Function (Transitions from 5 to 6)
    function approveDispatch(orderId) {
        Swal.fire({
            title: 'Confirm Delivery?',
            text: "This will confirm that the order has been delivered and update stock locations.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Confirm it!'
        }).then((result) => {
            if (result.isConfirmed) {
                disableAllOrderModalButtons();
                // Call API
                fetch('{{ route('orderManagement.confirmDispatch') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: orderId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Confirmed!',
                                'Delivery has been confirmed.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            enableAllOrderModalButtons();
                            Swal.fire(
                                'Error!',
                                data.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        enableAllOrderModalButtons();
                        console.error(error);
                        Swal.fire(
                            'Error!',
                            'Server error occurred.',
                            'error'
                        );
                    });
            }
        });
    }

    // Complete Order Function (Transitions from 6 to 7)
    function completeOrder(orderId) {
        Swal.fire({
            title: 'Complete Order?',
            text: "This will mark the order as fully settled and completed.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Complete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                disableAllOrderModalButtons();
                // Call API
                fetch('{{ route('orderManagement.completeOrder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: orderId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Completed!',
                                'Order has been fully completed.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            enableAllOrderModalButtons();
                            Swal.fire(
                                'Error!',
                                data.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        enableAllOrderModalButtons();
                        console.error(error);
                        Swal.fire(
                            'Error!',
                            'Server error occurred.',
                            'error'
                        );
                    });
            }
        });
    }

    function approveOrder(orderId) {
        // Collect updated quantities from the table
        const items = [];
        document.querySelectorAll('.order-item-qty').forEach(input => {
            items.push({
                product_item_id: input.dataset.itemId,
                quantity: input.value
            });
        });

        Swal.fire({
            title: 'Approve Order?',
            text: "This will approve the order and update the agent's outstanding balance if applicable.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#7c3aed',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve it!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                disableAllOrderModalButtons();
                return fetch('{{ route('orderManagement.approveOrder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        items: items
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        enableAllOrderModalButtons();
                        return response.json().then(json => { throw new Error(json.message || 'Server error'); });
                    }
                    return response.json();
                })
                .catch(error => {
                    enableAllOrderModalButtons();
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Approved!',
                    text: 'Order has been approved successfully.',
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            } else if (result.isConfirmed && !result.value.success) {
                enableAllOrderModalButtons();
                Swal.fire('Error', result.value.message || 'Approval failed', 'error');
            }
        });
    }

    function rejectOrder(orderId) {
        Swal.fire({
            title: 'Reject Order?',
            text: "Please provide a reason for rejecting this order:",
            input: 'textarea',
            inputPlaceholder: 'Type rejection reason here...',
            inputAttributes: {
                'aria-label': 'Type rejection reason here'
            },
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Reject it!',
            inputValidator: (value) => {
                if (!value || value.trim() === "") {
                    return 'A rejection reason is required!'
                }
            },
            showLoaderOnConfirm: true,
            preConfirm: (reason) => {
                disableAllOrderModalButtons();
                return fetch('{{ route('orderManagement.rejectOrder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        reason: reason
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        enableAllOrderModalButtons();
                        return response.json().then(json => { throw new Error(json.message || 'Server error'); });
                    }
                    return response.json();
                })
                .catch(error => {
                    enableAllOrderModalButtons();
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Rejected!',
                    text: 'Order has been rejected successfully.',
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            } else if (result.isConfirmed && !result.value.success) {
                enableAllOrderModalButtons();
                Swal.fire('Error', result.value.message || 'Rejection failed', 'error');
            }
        });
    }

    // Approve specific payment record
    function approvePaymentDetail(paymentId, orderId) {
        Swal.fire({
            title: 'Approve Payment?',
            text: "This will finalize the payment, update the order balance and agent outstanding balance.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                disableAllOrderModalButtons();
                fetch('{{ route('orderManagement.approvePayment') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        payment_id: paymentId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: data.message,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        enableAllOrderModalButtons();
                        Swal.fire('Error', data.message || 'Something went wrong', 'error');
                    }
                })
                .catch(error => {
                    enableAllOrderModalButtons();
                    console.error(error);
                    Swal.fire('Error', 'Server communication error', 'error');
                });
            }
        });
    }

    function closeOrderModal() {
        const modal = document.getElementById('view-order-modal');
        const modalContent = document.getElementById('view-order-modal-content');

        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>