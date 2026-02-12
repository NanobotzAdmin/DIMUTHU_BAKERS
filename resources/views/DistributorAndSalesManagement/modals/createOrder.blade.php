{{-- resources/views/DistributorAndSalesManagement/modals/createOrder.blade.php --}}



<div id="create-order-modal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300" onclick="closeCreateOrderModal()"></div>

    {{-- Slide-in Panel --}}
    <div id="create-order-panel" class="absolute top-0 right-0 h-full w-full max-w-2xl bg-white shadow-2xl overflow-hidden flex flex-col transform transition-transform duration-300 translate-x-full">
        
        {{-- Header & Progress Bar --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6 flex-shrink-0">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Create New Order</h2>
                <button onclick="closeCreateOrderModal()" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 18 18"/></svg>
                </button>
            </div>

            {{-- Progress Bar --}}
            <div class="flex items-center justify-between px-2">
                @foreach([
                    1 => ['icon' => 'store', 'label' => 'Channel'],
                    2 => ['icon' => 'users', 'label' => 'Customer'],
                    3 => ['icon' => 'shopping-cart', 'label' => 'Products'],
                    4 => ['icon' => 'file-text', 'label' => 'Details'],
                    5 => ['icon' => 'check-circle', 'label' => 'Review']
                ] as $stepNum => $step)
                    <div class="step-indicator flex flex-col items-center relative z-10" id="step-indicator-{{ $stepNum }}">
                        <div class="step-circle w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 bg-gray-200 text-gray-400 border-2 border-transparent">
                            {{-- Icons --}}
                            @if($step['icon'] == 'store') <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2 2 0 0 1 4 12v0a2 2 0 0 1-2-2V7"/></svg>
                            @elseif($step['icon'] == 'users') <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            @elseif($step['icon'] == 'shopping-cart') <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            @elseif($step['icon'] == 'file-text') <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                            @elseif($step['icon'] == 'check-circle') <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            @endif
                        </div>
                        <span class="step-label mt-2 text-xs font-medium text-gray-400 transition-colors duration-300">{{ $step['label'] }}</span>
                    </div>
                    @if($stepNum < 5)
                        <div class="step-connector flex-1 h-1 bg-gray-200 -mt-6 mx-2 rounded-full transition-colors duration-300" id="step-connector-{{ $stepNum }}"></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Content Area --}}
        <div class="flex-1 overflow-y-auto p-6" id="wizard-content">
            
            {{-- STEP 1: CHANNEL --}}
            <div id="step-content-1" class="wizard-step">
                <h2 class="text-2xl text-gray-900 mb-2 font-bold">Select Order Channel</h2>
                <p class="text-gray-600 mb-6">Choose the type of order you want to create</p>

                {{-- Channel Selection --}}
                <div class="space-y-3 mb-6">
                    <button onclick="selectChannel('pos-pickup')" id="btn-channel-pos-pickup" class="channel-btn w-full p-5 rounded-2xl border-2 transition-all duration-300 text-left border-gray-200 bg-white hover:border-gray-300">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2 2 0 0 1 4 12v0a2 2 0 0 1-2-2V7"/></svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg text-gray-900 mb-1 font-semibold">POS Pickup</h3>
                                <p class="text-sm text-gray-600">Walk-in customer ordering for later pickup</p>
                            </div>
                        </div>
                    </button>

                    <button onclick="selectChannel('special-order')" id="btn-channel-special-order" class="channel-btn w-full p-5 rounded-2xl border-2 transition-all duration-300 text-left border-gray-200 bg-white hover:border-gray-300">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-purple-500 to-pink-600 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/></svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg text-gray-900 mb-1 font-semibold">Special Order</h3>
                                <p class="text-sm text-gray-600">Custom cakes, event orders, catering</p>
                            </div>
                        </div>
                    </button>

                    <button onclick="selectChannel('scheduled-production')" id="btn-channel-scheduled-production" class="channel-btn w-full p-5 rounded-2xl border-2 transition-all duration-300 text-left border-gray-200 bg-white hover:border-gray-300">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-orange-500 to-orange-600 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg text-gray-900 mb-1 font-semibold">Scheduled Production</h3>
                                <p class="text-sm text-gray-600">Recurring orders with automated scheduling</p>
                            </div>
                        </div>
                    </button>
                </div>

                {{-- Outlet Selection --}}
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2 font-medium">Select Outlet</label>
                    <select id="create-outlet" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                        <option value="">Choose outlet...</option>
                        <option value="-1">Warehouse</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Quotation Search for Auto-fill --}}
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2 font-medium">Load from Quotation</label>
                    <select id="quotation-search-select" class="w-full" style="width: 100%;">
                        <option value="">Search by number or customer...</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-2 text-purple-600">Selecting a quotation will auto-fill customer details and add products to cart.</p>
                </div>

                {{-- Delivery Method --}}
                <div>
                    <label class="block text-gray-700 mb-3 font-medium">Delivery Method</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="selectDeliveryMethod('pickup')" id="btn-method-pickup" class="delivery-btn h-20 rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-2 border-purple-500 bg-purple-50 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                            <span class="font-medium text-purple-700">Pickup</span>
                        </button>
                        <button onclick="selectDeliveryMethod('delivery')" id="btn-method-delivery" class="delivery-btn h-20 rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-2 border-gray-200 bg-white hover:border-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            <span class="font-medium text-gray-600">Delivery</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- STEP 2: CUSTOMER --}}
            <div id="step-content-2" class="wizard-step hidden">
                <h2 class="text-2xl text-gray-900 mb-2 font-bold">Customer Information</h2>
                <p class="text-gray-600 mb-6">Search for existing customer or create new</p>

                <div class="mb-6">
                    <label class="block text-gray-700 mb-3 font-medium">Search Customer</label>
                    <select id="customer-search-select" class="w-full" style="width: 100%;">
                        <option value="">Type to search customers...</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-2">Search by name, phone, or email. Leave empty to create new customer.</p>
                </div>

                <div class="border-t-2 border-gray-100 pt-6">
                    <p class="text-sm text-gray-500 mb-4">Customer Details</p>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Customer Name <span class="text-red-500">*</span></label>
                            <input type="text" id="create-cust-name" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors" placeholder="Enter customer name">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                            <input type="tel" id="create-cust-phone" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors" placeholder="+94 77 123 4567">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="create-cust-email" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors" placeholder="customer@example.com">
                        </div>
                        <div id="create-delivery-address-container" class="hidden">
                            <label class="block text-gray-700 mb-2">Delivery Address <span class="text-red-500">*</span></label>
                            <textarea id="create-cust-address" rows="3" class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors resize-none" placeholder="Enter delivery address"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 3: PRODUCTS --}}
            <div id="step-content-3" class="wizard-step hidden">
                <h2 class="text-2xl text-gray-900 mb-2 font-bold">Select Products</h2>
                <p class="text-gray-600 mb-6">Add products to the order</p>

                {{-- Cart Summary --}}
                <div id="cart-summary" class="hidden bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-4 border-2 border-purple-200 mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-gray-900 font-semibold">Order Items (<span id="cart-count">0</span>)</h3>
                        <div class="text-lg text-purple-700 font-bold" id="cart-total">Rs 0.00</div>
                    </div>
                    <div id="cart-items-list" class="space-y-2 max-h-48 overflow-y-auto pr-2"></div>
                </div>

                {{-- Empty State --}}
                <div id="cart-empty" class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-4 flex items-start gap-3 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-600 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <div>
                        <div class="text-yellow-900 font-medium">No items added yet</div>
                        <div class="text-sm text-yellow-700">Add at least one product to continue</div>
                    </div>
                </div>

                {{-- Search & Add Product --}}
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Search and Add Products</label>
                    <select id="product-search-select" class="w-full" style="width: 100%;">
                        <option value="">Type to search products...</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-2">Search by product name, reference number, or bin code. Products will be added to your cart.</p>
                </div>

                <div id="product-list" class="space-y-2 max-h-80 overflow-y-auto pr-2 hidden">
                    {{-- Products injected via JS --}}
                </div>
            </div>

            {{-- STEP 4: DETAILS --}}
            <div id="step-content-4" class="wizard-step hidden">
                <h2 class="text-2xl text-gray-900 mb-2 font-bold">Order Details</h2>
                <p class="text-gray-600 mb-6">Configure delivery, timing, and special requirements</p>

                {{-- Dynamic Date/Time Title --}}
                <div id="time-config-section" class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 mb-6">
                    <label id="time-config-label" class="block text-gray-700 mb-3 font-medium">Pickup Date & Time <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="date" id="create-date" onchange="toggleRecurrenceDate()" class="h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500">
                        <input type="time" id="create-time" class="h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500">
                    </div>
                </div>

                {{-- Special Order Fields --}}
                <div id="special-order-fields" class="hidden space-y-6 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-3 font-medium">Event Type</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button onclick="selectEventType('Wedding', this)" class="evt-btn h-12 rounded-xl border-2 transition-all duration-300 border-gray-200 bg-white text-gray-600 hover:border-gray-300">Wedding</button>
                            <button onclick="selectEventType('Birthday', this)" class="evt-btn h-12 rounded-xl border-2 transition-all duration-300 border-gray-200 bg-white text-gray-600 hover:border-gray-300">Birthday</button>
                            <button onclick="selectEventType('Corporate', this)" class="evt-btn h-12 rounded-xl border-2 transition-all duration-300 border-gray-200 bg-white text-gray-600 hover:border-gray-300">Corporate</button>
                        </div>
                        <input type="hidden" id="create-event-type">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Expected Guest Count</label>
                        <input type="number" id="create-guest-count" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500" placeholder="0">
                    </div>
                </div>

                {{-- Scheduled Fields --}}
                <div id="scheduled-fields" class="hidden mb-6 bg-orange-50 border-2 border-orange-200 rounded-xl p-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="create-recurring" onchange="toggleRecurring(this.checked)" class="w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>
                            <span class="text-gray-900 font-medium">Make this a recurring order</span>
                        </div>
                    </label>
                    <div id="recurrence-pattern-div" class="mt-4 hidden space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Recurrence Pattern</label>
                            <select id="create-recurrence" onchange="toggleRecurrenceDate()" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500">
                                <option value="">Select pattern...</option>
                                <option value="1">Daily</option>
                                <option value="2">Weekly</option>
                                <option value="3">Monthly</option>
                            </select>
                        </div>
                        <div id="recurrence-end-date-div" class="hidden">
                            <label class="block text-gray-700 mb-2 font-medium">Recurrence End Date</label>
                            <input type="date" id="create-recurrence-end-date" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2 font-medium">Special Instructions</label>
                    <textarea id="create-instructions" rows="4" class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 resize-none" placeholder="Add customization notes..."></textarea>
                </div>
            </div>

            {{-- STEP 5: REVIEW --}}
            <div id="step-content-5" class="wizard-step hidden">
                <h2 class="text-2xl text-gray-900 mb-2 font-bold">Review & Confirm</h2>
                <p class="text-gray-600 mb-6">Review all details before creating the order</p>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border-2 border-purple-200 mb-6">
                    {{-- Summary Header --}}
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b-2 border-purple-200">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center text-white" id="review-icon-container"></div>
                        <div>
                            <div class="text-xl text-gray-900 font-bold" id="review-channel"></div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span id="review-outlet"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Summary --}}
                    <div class="mb-6 pb-6 border-b-2 border-purple-200">
                        <h3 class="text-gray-900 mb-3 font-semibold">Customer Information</h3>
                        <div class="space-y-1 text-gray-600 text-sm">
                            <p id="review-cust-name" class="font-medium text-gray-900"></p>
                            <p id="review-cust-phone"></p>
                            <p id="review-cust-email"></p>
                            <p id="review-cust-address" class="mt-1 italic"></p>
                        </div>
                    </div>

                    {{-- Delivery Summary --}}
                    <div class="mb-6 pb-6 border-b-2 border-purple-200">
                        <h3 class="text-gray-900 mb-3 font-semibold" id="review-delivery-label">Pickup Details</h3>
                        <div class="flex items-center gap-4 text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                <span id="review-date"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span id="review-time"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="mb-6">
                        <h3 class="text-gray-900 mb-3 font-semibold">Order Items</h3>
                        <div id="review-items-list" class="space-y-2"></div>
                    </div>

                    {{-- Financials --}}
                    <div class="space-y-2 mb-2">
                        <div class="flex justify-between text-gray-600"><span>Subtotal</span><span id="review-subtotal"></span></div>
                        <div class="flex justify-between text-gray-600"><span>Tax (5%)</span><span id="review-tax"></span></div>
                        <div class="flex justify-between text-xl text-gray-900 pt-3 border-t-2 border-purple-200 font-bold">
                            <span>Grand Total</span><span id="review-total" class="text-purple-700"></span>
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div id="payment-details-section" class="bg-white rounded-2xl p-6 border-2 border-gray-200 hidden">
                    <h3 class="text-gray-900 mb-4 font-bold">Payment Details</h3>
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <button onclick="selectPaymentMethod('Cash', this)" class="pay-btn h-16 rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-1 border-gray-200 bg-white">
                            <span class="text-sm font-medium text-gray-600">Cash</span>
                        </button>
                        <button onclick="selectPaymentMethod('Card', this)" class="pay-btn h-16 rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-1 border-gray-200 bg-white">
                            <span class="text-sm font-medium text-gray-600">Card</span>
                        </button>
                        <button onclick="selectPaymentMethod('Bank Transfer', this)" class="pay-btn h-16 rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-1 border-gray-200 bg-white">
                            <span class="text-sm font-medium text-gray-600">Bank Transfer</span>
                        </button>
                    </div>

                    <div id="payment-details-inputs" class="grid grid-cols-2 gap-3 hidden">
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Payment Reference</label>
                            <input type="text" id="create-payment-ref" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500" placeholder="Transaction ID / Note">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Paid Amount</label>
                            <input type="number" id="create-paid-amount" step="0.01" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 border-t-2 border-gray-200 p-6 flex items-center justify-between flex-shrink-0">
            <button onclick="prevStep()" id="btn-wizard-back" class="h-12 px-6 rounded-xl flex items-center gap-2 transition-all duration-300 bg-gray-200 text-gray-400 cursor-not-allowed" disabled>
                Back
            </button>
            <div class="text-sm text-gray-500">Step <span id="current-step-num">1</span> of 5</div>
            <button onclick="nextStep()" id="btn-wizard-next" class="h-12 px-6 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-xl flex items-center gap-2 transition-all duration-300 shadow-lg">
                Next
            </button>
            <button onclick="submitCreateOrder()" id="btn-wizard-submit" class="hidden h-12 px-8 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl flex items-center gap-2 transition-all duration-300 shadow-lg">
                Create Order
            </button>
        </div>
    </div>
</div>

<script>
    // --- MOCK DATA ---
    const products = []; // Will be loaded via API

    const recentCustomers = []; // Will be loaded via API

    // --- STATE ---
    let formState = {
        step: 1,
        channel: 'pos-pickup',
        outletId: '',
        deliveryMethod: 'pickup',
        cart: [],
        customer_id: null,
        details: { eventType: '', recurring: false }
    };

    let selectedCategory = 'all';

    // --- MAIN FUNCTIONS ---

    function openCreateOrderModal(defaultChannel = 'pos-pickup') {
        // Reset
        formState = { step: 1, channel: defaultChannel, outletId: '', deliveryMethod: 'pickup', cart: [], customer_id: null, quotation_id: null, details: { eventType: '', recurring: false } };
        document.getElementById('create-outlet').value = "";
        document.getElementById('create-cust-name').value = "";
        document.getElementById('create-cust-phone').value = "";
        document.getElementById('create-cust-email').value = "";
        document.getElementById('create-cust-address').value = "";
        document.getElementById('create-date').value = "";
        document.getElementById('create-time').value = "";
        
        selectChannel(defaultChannel);
        updateStepUI();
        initializeCustomerSearch();
        initializeProductSearch();
        initializeQuotationSearch();
        
        const modal = document.getElementById('create-order-modal');
        modal.classList.remove('hidden');
        setTimeout(() => document.getElementById('create-order-panel').classList.remove('translate-x-full'), 10);
    }

    function initializeProductSearch() {
        // Initialize Select2 for product search if not already initialized
        const $productSearch = $('#product-search-select');
        if ($productSearch.data('select2')) {
            $productSearch.select2('destroy');
        }
        
        $productSearch.select2({
            placeholder: 'Type to search products...',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("orderManagement.searchProducts") }}',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        _token: '{{ csrf_token() }}'
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            templateResult: formatProductResult,
            templateSelection: formatProductSelection
        }).on('select2:select', function (e) {
            const product = e.params.data;
            addToCartFromSelect(product);
            $(this).val(null).trigger('change'); // Clear selection
        });
    }

    function initializeQuotationSearch() {
        const $qtSearch = $('#quotation-search-select');
        if ($qtSearch.data('select2')) {
            $qtSearch.select2('destroy');
        }

        $qtSearch.select2({
            placeholder: 'Search by number or customer...',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("orderManagement.searchQuotations") }}',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        _token: '{{ csrf_token() }}'
                    };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            templateResult: formatQuotationResult,
            templateSelection: formatQuotationSelection
        }).on('select2:select', function (e) {
            const quotation = e.params.data;
            applyQuotationData(quotation);
        }).on('select2:clear', function (e) {
             clearQuotationData();
        });
    }

    function formatQuotationResult(q) {
        if (q.loading) return q.text;
        return $(`<div class="py-2">
            <div class="font-medium text-gray-900">${q.text}</div>
            <div class="text-sm text-gray-500">Items: ${q.products.length}</div>
        </div>`);
    }

    function formatQuotationSelection(q) {
        return q.text || q.id;
    }

    function applyQuotationData(q) {
        formState.quotation_id = q.id;
        
        // 1. Auto-select channel to Special Order (required for Customer/Quotation flow usually)
        if (formState.channel !== 'special-order') {
             selectChannel('special-order');
        }

        // 2. Set Customer & Lock fields
        // 2. Set Customer & Lock fields
        if (q.customer) {
            fillCustomerFields(q.customer);
            // Lock fields
            document.getElementById('create-cust-name').readOnly = true;
            document.getElementById('create-cust-phone').readOnly = true;
            document.getElementById('create-cust-email').readOnly = true;
            document.getElementById('create-cust-address').readOnly = true;

            // Auto-populate Customer Select2
            const $custSelect = $('#customer-search-select');
            // Check if option exists
            if ($custSelect.find("option[value='" + q.customer.id + "']").length) {
                $custSelect.val(q.customer.id).trigger('change');
            } else { 
                const newOption = new Option(q.customer.text || q.customer.name, q.customer.id, true, true);
                $custSelect.append(newOption).trigger('change');
            }
        }

        // 3. Populate Cart
        formState.cart = []; // Clear current cart
        if (q.products && q.products.length > 0) {
            q.products.forEach(p => {
                formState.cart.push({
                    id: p.product_item_id || p.id,
                    name: p.product_name || p.name,
                    price: parseFloat(p.price || p.unit_price || 0),
                    reference: '', // Not in qt payload?
                    quantity: parseFloat(p.quantity || 1),
                    total: parseFloat(p.price || 0) * parseFloat(p.quantity || 1)
                });
            });
            renderCart();
            // Update UI for cart
            document.getElementById('cart-summary').classList.remove('hidden');
            document.getElementById('cart-empty').classList.add('hidden');
        }
        
        Swal.fire({
            icon: 'success',
            title: 'Quotation Applied',
            text: 'Customer details and products have been loaded.',
            timer: 2000,
            showConfirmButton: false
        });
    }

    function clearQuotationData() {
        formState.quotation_id = null;
        // Unlock customer fields
        document.getElementById('create-cust-name').readOnly = false;
        document.getElementById('create-cust-phone').readOnly = false;
        document.getElementById('create-cust-email').readOnly = false;
        document.getElementById('create-cust-address').readOnly = false;
        
        // Clear Customer Select2
        $('#customer-search-select').val(null).trigger('change');
    }

    function formatProductResult(product) {
        if (product.loading) return product.text;
        
        return $(`
            <div class="flex items-center justify-between py-2">
                <div>
                    <div class="font-medium text-gray-900">${product.product_name}</div>
                    <div class="text-sm text-gray-500">${product.reference_number}</div>
                </div>
                <div class="text-right">
                    <div class="font-bold text-purple-600">Rs ${product.price}</div>
                    <div class="text-xs text-gray-500">${product.price_raw > 0 ? 'In Stock' : 'No Stock'}</div>
                </div>
            </div>
        `);
    }

    function formatProductSelection(product) {
        return product.product_name || product.text;
    }

    function addToCartFromSelect(productData) {
        const product = {
            id: productData.id,
            name: productData.product_name,
            price: parseFloat(productData.price_raw),
            reference: productData.reference_number
        };
        
        const existing = formState.cart.find(i => String(i.id) === String(product.id));
        if (existing) {
            existing.quantity++;
            existing.total = existing.quantity * existing.price;
        } else {
            formState.cart.push({ ...product, quantity: 1, total: product.price });
        }
        renderCart();
    }

    function closeCreateOrderModal() {
        document.getElementById('create-order-panel').classList.add('translate-x-full');
        setTimeout(() => document.getElementById('create-order-modal').classList.add('hidden'), 300);
    }

    function nextStep() {
        if (!validateStep()) return;
        
        // Skip customer step (step 2) if not special order
        if (formState.step === 1 && formState.channel !== 'special-order') {
            formState.step = 3; // Skip to products
        } else if (formState.step < 5) {
            formState.step++;
        }
        
        updateStepUI();
    }

    function prevStep() {
        // Skip customer step (step 2) if not special order
        if (formState.step === 3 && formState.channel !== 'special-order') {
            formState.step = 1; // Skip back to outlet
        } else if (formState.step > 1) {
            formState.step--;
        }
        
        updateStepUI();
    }

    function updateStepUI() {
        // 1. Show correct step content
        document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('hidden'));
        document.getElementById(`step-content-${formState.step}`).classList.remove('hidden');

        // 2. Update Progress Bar
        for (let i = 1; i <= 5; i++) {
            const circle = document.querySelector(`#step-indicator-${i} .step-circle`);
            const label = document.querySelector(`#step-indicator-${i} .step-label`);
            const connector = document.getElementById(`step-connector-${i}`);

            // Reset classes
            circle.className = 'step-circle w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 border-2 border-transparent';
            label.className = 'step-label mt-2 text-xs font-medium transition-colors duration-300';
            
            if (i === formState.step) {
                // Active
                circle.classList.add('bg-gradient-to-br', 'from-purple-600', 'to-pink-600', 'text-white', 'shadow-lg', 'scale-110');
                label.classList.add('text-purple-600', 'font-bold');
            } else if (i < formState.step) {
                // Completed
                circle.classList.add('bg-green-500', 'text-white');
                label.classList.add('text-green-600');
            } else {
                // Future
                circle.classList.add('bg-gray-200', 'text-gray-400');
                label.classList.add('text-gray-400');
            }

            // Update Connector
            if (connector) {
                connector.className = `step-connector flex-1 h-1 -mt-6 mx-2 rounded-full transition-colors duration-300 ${i < formState.step ? 'bg-green-500' : 'bg-gray-200'}`;
            }
        }

        // 3. Footer Buttons
        document.getElementById('current-step-num').innerText = formState.step;
        const backBtn = document.getElementById('btn-wizard-back');
        const nextBtn = document.getElementById('btn-wizard-next');
        const submitBtn = document.getElementById('btn-wizard-submit');

        if (formState.step === 1) {
            backBtn.disabled = true;
            backBtn.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            backBtn.classList.remove('bg-white', 'border-2', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
        } else {
            backBtn.disabled = false;
            backBtn.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            backBtn.classList.add('bg-white', 'border-2', 'border-gray-300', 'text-gray-700', 'hover:bg-gray-50');
        }

        if (formState.step === 5) {
            nextBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
            populateReview();
        } else {
            nextBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
        }

        // Specific render triggers
        // if(formState.step === 3) renderProducts();
    }

    function validateStep() {
        if (formState.step === 1) {
            const outlet = document.getElementById('create-outlet').value;
            if (!outlet) {
                Swal.fire({
                    icon: 'warning',             // Optional: Adds a warning icon
                    text: 'Please select an outlet',
                    confirmButtonText: 'OK',     // Explicitly names the button
                    allowOutsideClick: false,    // Prevents closing by clicking the background
                    allowEscapeKey: false        // Prevents closing by pressing Esc
                });
            return false;
}
            formState.outletId = outlet;
        }
        if (formState.step === 2) {
            // Only validate customer info for special orders
            if (formState.channel === 'special-order') {
                const name = document.getElementById('create-cust-name').value;
                const phone = document.getElementById('create-cust-phone').value;
                const addr = document.getElementById('create-cust-address').value;
                if (!name || !phone) {
                    Swal.fire({
                        icon: 'warning',             // Optional: Adds a warning icon
                        text: 'Name and Phone are required',
                        confirmButtonText: 'OK',     // Explicitly names the button
                        allowOutsideClick: false,    // Prevents closing by clicking the background
                        allowEscapeKey: false        // Prevents closing by pressing Esc
                    });
                return false;
}
                if (formState.deliveryMethod === 'delivery' && !addr) {
                    Swal.fire({
                        icon: 'warning',             // Optional: Adds a warning icon
                        text: 'Address is required for delivery',
                        confirmButtonText: 'OK',     // Explicitly names the button
                        allowOutsideClick: false,    // Prevents closing by clicking the background
                        allowEscapeKey: false        // Prevents closing by pressing Esc
                    });
                return false;
}
            }
        }
        if (formState.step === 3) {
            if (formState.cart.length === 0) {
                Swal.fire({
                    icon: 'warning',             // Optional: Adds a warning icon
                    text: 'Add at least one product',
                    confirmButtonText: 'OK',     // Explicitly names the button
                    allowOutsideClick: false,    // Prevents closing by clicking the background
                    allowEscapeKey: false        // Prevents closing by pressing Esc
                });
            return false;
}
        }
        if (formState.step === 4) {
            const date = document.getElementById('create-date').value;
            const time = document.getElementById('create-time').value;
            if (!date || !time) {
                Swal.fire({
                    icon: 'warning',             // Optional: Adds a warning icon
                    text: 'Date and Time are required',
                    confirmButtonText: 'OK',     // Explicitly names the button
                    allowOutsideClick: false,    // Prevents closing by clicking the background
                    allowEscapeKey: false        // Prevents closing by pressing Esc
                });
            return false;
}
            if (formState.channel === 'scheduled-production' && formState.details.recurring) {
                if(!document.getElementById('create-recurrence').value) {
                    Swal.fire({
                        icon: 'warning',             // Optional: Adds a warning icon
                        text: 'Select recurrence pattern',
                        confirmButtonText: 'OK',     // Explicitly names the button
                        allowOutsideClick: false,    // Prevents closing by clicking the background
                        allowEscapeKey: false        // Prevents closing by pressing Esc
                    });
                return false;
}
            }
        }
        return true;
    }

    // --- STEP 1 LOGIC ---
    function selectChannel(channel) {
        formState.channel = channel;
        // Reset styles
        document.querySelectorAll('.channel-btn').forEach(btn => btn.className = 'channel-btn w-full p-5 rounded-2xl border-2 transition-all duration-300 text-left border-gray-200 bg-white hover:border-gray-300');
        
        // Active style
        const activeBtn = document.getElementById(`btn-channel-${channel}`);
        let colorClass = 'border-purple-300 bg-purple-50 shadow-md scale-105';
        if(channel === 'pos-pickup') colorClass = 'border-blue-300 bg-blue-50 shadow-md scale-105';
        if(channel === 'scheduled-production') colorClass = 'border-orange-300 bg-orange-50 shadow-md scale-105';
        activeBtn.className = `channel-btn w-full p-5 rounded-2xl border-2 transition-all duration-300 text-left ${colorClass}`;

        // Toggle Fields later
        const specialF = document.getElementById('special-order-fields');
        const schedF = document.getElementById('scheduled-fields');
        const paymentDetailsSection = document.getElementById('payment-details-section');

        if(channel === 'special-order') {
            specialF.classList.remove('hidden');
            paymentDetailsSection.classList.remove('hidden');
        } else {
            specialF.classList.add('hidden');
            paymentDetailsSection.classList.add('hidden');
        }

        if(channel === 'scheduled-production') schedF.classList.remove('hidden');
        else schedF.classList.add('hidden');
    }

    function selectDeliveryMethod(method) {
        formState.deliveryMethod = method;
        const pBtn = document.getElementById('btn-method-pickup');
        const dBtn = document.getElementById('btn-method-delivery');
        const addrContainer = document.getElementById('create-delivery-address-container');
        const timeLabel = document.getElementById('time-config-label');
        const timeSection = document.getElementById('time-config-section');

        if (method === 'pickup') {
            pBtn.className = 'delivery-btn h-20 rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-2 border-purple-500 bg-purple-50 shadow-md';
            dBtn.className = 'delivery-btn h-20 rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-2 border-gray-200 bg-white hover:border-gray-300';
            pBtn.querySelector('span').classList.replace('text-gray-600', 'text-purple-700');
            pBtn.querySelector('svg').classList.replace('text-gray-400', 'text-purple-600');
            dBtn.querySelector('span').classList.replace('text-purple-700', 'text-gray-600');
            dBtn.querySelector('svg').classList.replace('text-purple-600', 'text-gray-400');
            
            addrContainer.classList.add('hidden');
            timeLabel.innerHTML = 'Pickup Date & Time <span class="text-red-500">*</span>';
            timeSection.classList.remove('bg-green-50', 'border-green-200');
            timeSection.classList.add('bg-blue-50', 'border-blue-200');
        } else {
            dBtn.className = 'delivery-btn h-20 rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-2 border-purple-500 bg-purple-50 shadow-md';
            pBtn.className = 'delivery-btn h-20 rounded-xl border-2 transition-all duration-300 flex flex-col items-center justify-center gap-2 border-gray-200 bg-white hover:border-gray-300';
            dBtn.querySelector('span').classList.replace('text-gray-600', 'text-purple-700');
            dBtn.querySelector('svg').classList.replace('text-gray-400', 'text-purple-600');
            pBtn.querySelector('span').classList.replace('text-purple-700', 'text-gray-600');
            pBtn.querySelector('svg').classList.replace('text-purple-600', 'text-gray-400');

            addrContainer.classList.remove('hidden');
            timeLabel.innerHTML = 'Delivery Date & Time <span class="text-red-500">*</span>';
            timeSection.classList.remove('bg-blue-50', 'border-blue-200');
            timeSection.classList.add('bg-green-50', 'border-green-200');
        }
    }

    // --- STEP 2 LOGIC ---
    function initializeCustomerSearch() {
        // Initialize Select2 for customer search if not already initialized
        const $customerSearch = $('#customer-search-select');
        if ($customerSearch.data('select2')) {
            $customerSearch.select2('destroy');
        }
        
        $customerSearch.select2({
            placeholder: 'Type to search customers...',
            allowClear: true,
            minimumInputLength: 2,
            ajax: {
                url: '{{ route("orderManagement.searchCustomers") }}',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        _token: '{{ csrf_token() }}'
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            templateResult: formatCustomerResult,
            templateSelection: formatCustomerSelection
        }).on('select2:select', function (e) {
            const customer = e.params.data;
            fillCustomerFields(customer);
        });
    }

    function formatCustomerResult(customer) {
        if (customer.loading) return customer.text;
        
        return $(`
            <div class="py-2">
                <div class="font-medium text-gray-900">${customer.name}</div>
                <div class="text-sm text-gray-500">${customer.phone || 'No phone'} ${customer.email ? '• ' + customer.email : ''}</div>
            </div>
        `);
    }

    function formatCustomerSelection(customer) {
        return customer.name || customer.text;
    }

    function fillCustomerFields(customer) {
        formState.customer_id = customer.id;
        document.getElementById('create-cust-name').value = customer.name || '';
        document.getElementById('create-cust-phone').value = customer.phone || '';
        document.getElementById('create-cust-email').value = customer.email || '';
        document.getElementById('create-cust-address').value = customer.address || '';
    }

    // Clear selected customer ID if manual edits are made
    ['create-cust-name', 'create-cust-phone', 'create-cust-email', 'create-cust-address'].forEach(id => {
        document.getElementById(id).addEventListener('input', () => {
            formState.customer_id = null;
        });
    });

    // --- STEP 3 LOGIC ---
    function filterCategory(cat, btn) {
        selectedCategory = cat;
        document.querySelectorAll('.cat-btn').forEach(b => {
            b.className = 'cat-btn flex-shrink-0 px-4 py-2 rounded-lg transition-all duration-300 bg-white border-2 border-gray-200 text-gray-600 hover:border-gray-300';
        });
        btn.className = 'cat-btn active flex-shrink-0 px-4 py-2 rounded-lg transition-all duration-300 bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-md';
        // renderProducts();
    }

    function renderProducts() {
        const container = document.getElementById('product-list');
        // Since we are using Select2 for search/add, we don't need to filter a list here.
        // This function might be deprecated or just used for showing 'Products' if needed.
        // For now, let's keep it empty or remove the filtering logic that depends on 'product-search' input.
        // If you want to show a list of all products (which is expensive if many), you'd need logic.
        // But the error was about 'product-search' value null.
        
        // Let's clear the container or show something relevant if not using search input for filtering a list.
        // container.innerHTML = ''; 
        
        // Actually, looking at the UI, Step 3 has 'product-search-select' (Select2) AND used to have a list.
        // If we strictly use Select2 to add to cart, we might not need to render a list of all products to filter.
        
        // I will just make it safe and do nothing if no 'product-search' element exists, 
        // to stop the console error.
        
        const searchInput = document.getElementById('product-search');
        if (!searchInput) return; // Exit if input doesn't exist

        const search = searchInput.value.toLowerCase();


    }

    function addToCart(id) {
        const product = products.find(p => p.id === id);
        const existing = formState.cart.find(i => i.id === id);
        if (existing) {
            existing.quantity++;
            existing.total = existing.quantity * existing.price;
        } else {
            formState.cart.push({ ...product, quantity: 1, total: product.price });
        }
        renderCart();
        // renderProducts();
    }

    function updateCartQty(id, change) {
        // Convert to string for comparison since IDs might be numbers or strings
        const itemId = String(id);
        const item = formState.cart.find(i => String(i.id) === itemId);
        if (item) {
            item.quantity += change;
            item.total = item.quantity * item.price;
            if (item.quantity <= 0) {
                formState.cart = formState.cart.filter(i => String(i.id) !== itemId);
            }
        }
        renderCart();
        // renderProducts();
    }

    function renderCart() {
        const container = document.getElementById('cart-items-list');
        const summary = document.getElementById('cart-summary');
        const empty = document.getElementById('cart-empty');

        if (formState.cart.length === 0) {
            summary.classList.add('hidden');
            empty.classList.remove('hidden');
            return;
        }

        summary.classList.remove('hidden');
        empty.classList.add('hidden');

        let total = 0;
        container.innerHTML = formState.cart.map(item => {
            total += item.total;
            return `
            <div class="bg-white rounded-xl p-3 flex items-center gap-3 border border-gray-200">
                <div class="flex-1 min-w-0">
                    <div class="text-gray-900 text-sm font-medium">${item.name}</div>
                    <div class="text-xs text-gray-500">Rs ${item.price.toLocaleString()} each</div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="updateCartQty('${item.id}', -1)" class="w-7 h-7 bg-gray-100 hover:bg-gray-200 rounded flex items-center justify-center text-gray-700 font-bold transition-colors">−</button>
                    <span class="w-10 text-center text-sm font-medium text-gray-900">${item.quantity}</span>
                    <button onclick="updateCartQty('${item.id}', 1)" class="w-7 h-7 bg-purple-100 hover:bg-purple-200 rounded flex items-center justify-center text-purple-700 font-bold transition-colors">+</button>
                </div>
                <div class="w-20 text-right text-sm font-bold text-gray-900">Rs ${item.total.toLocaleString()}</div>
                <button onclick="removeFromCart('${item.id}')" class="text-red-500 hover:text-red-700 transition-colors" title="Remove item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>`;
        }).join('');

        document.getElementById('cart-count').innerText = formState.cart.length;
        document.getElementById('cart-total').innerText = 'Rs ' + total.toLocaleString();
    }

    function removeFromCart(id) {
        const itemId = String(id);
        formState.cart = formState.cart.filter(i => String(i.id) !== itemId);
        renderCart();
        // renderProducts();
    }

    // --- STEP 4 LOGIC ---
    function selectEventType(type, btn) {
        // Map event type names to integers: 1=Wedding, 2=Birthday, 3=Corporate
        const eventTypeMap = {
            'Wedding': 1,
            'Birthday': 2,
            'Corporate': 3
        };
        formState.details.eventType = eventTypeMap[type];
        document.querySelectorAll('.evt-btn').forEach(b => b.className = 'evt-btn h-12 rounded-xl border-2 transition-all duration-300 border-gray-200 bg-white text-gray-600 hover:border-gray-300');
        btn.className = 'evt-btn h-12 rounded-xl border-2 transition-all duration-300 border-purple-500 bg-purple-50 text-purple-700';
    }

    function toggleRecurring(checked) {
        formState.details.recurring = checked;
        const div = document.getElementById('recurrence-pattern-div');
        if(checked) div.classList.remove('hidden'); else div.classList.add('hidden');
    }

    function toggleRecurrenceDate() {
        const pattern = parseInt(document.getElementById('create-recurrence').value);
        const endDateDiv = document.getElementById('recurrence-end-date-div');
        const startDateStr = document.getElementById('create-date').value;
        const endDateInput = document.getElementById('create-recurrence-end-date');

        if (pattern) {
            endDateDiv.classList.remove('hidden');
            
            if (startDateStr) {
                const startDate = new Date(startDateStr);
                const yyyyStart = startDate.getFullYear();
                const mmStart = String(startDate.getMonth() + 1).padStart(2, '0');
                const ddStart = String(startDate.getDate()).padStart(2, '0');
                
                // Reset custom validation
                endDateInput.onchange = null;

                // Calculate min date based on pattern (start date + 1 interval)
                let minDateObj = new Date(startDate);

                if (pattern === 1) { // Daily
                    endDateInput.step = "1";
                    minDateObj.setDate(startDate.getDate() + 1);
                } else if (pattern === 2) { // Weekly
                    // Set min to start date so step calculates correctly from it? 
                    // No, user wants valid end dates.
                    // If start is 30th, valid end dates: 6th, 13th...
                    // HTML5 step works from min attribute.
                    // If we set MIN = 6th (next week), step=7, then 6th, 13th are valid.
                    endDateInput.step = "7";
                    minDateObj.setDate(startDate.getDate() + 7);
                } else if (pattern === 3) { // Monthly
                    endDateInput.step = "1"; // Cannot use step for months
                    minDateObj.setMonth(startDate.getMonth() + 1);
                    
                    // Add manual validation for Monthly
                    endDateInput.onchange = function() {
                        validateRecurrenceEnd(this, startDate);
                    };
                }

                // Set Min Date
                const yyyy = minDateObj.getFullYear();
                const mm = String(minDateObj.getMonth() + 1).padStart(2, '0');
                const dd = String(minDateObj.getDate()).padStart(2, '0');
                endDateInput.min = `${yyyy}-${mm}-${dd}`;
                
                // Re-validate current value if exists
                if (endDateInput.value && endDateInput.value < endDateInput.min) {
                    endDateInput.value = '';
                }
            }
        } else {
            endDateDiv.classList.add('hidden');
        }
    }

    function validateRecurrenceEnd(input, startDate) {
        if (!input.value) return;
        const selectedDate = new Date(input.value);
        
        const expectedDay = startDate.getDate();
        const selectedDay = selectedDate.getDate();
        
        // Get the last day of the selected month
        const lastDayOfSelectedMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0).getDate();
        
        // Validation Logic:
        // 1. Exact day match (e.g. 15th to 15th)
        // 2. Or if selected date is the last day of the month AND expected day is greater than or equal to it
        //    (e.g. Start on 31st, End on Feb 28th -> 31 >= 28 -> Valid)
        const isValid = (selectedDay === expectedDay) || 
                        (selectedDay === lastDayOfSelectedMonth && expectedDay >= lastDayOfSelectedMonth);

        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                text: `For monthly recurrence starting on the ${expectedDay}th, please select an end date that falls on the same day (or the last day of the month).`,
                confirmButtonColor: '#9333ea'
            });
            input.value = ''; // Clear invalid value
        }
    }

    // --- STEP 5 LOGIC ---
    function populateReview() {
        // Channel Icon & Label
        let iconSvg = '';
        let label = '';
        if (formState.channel === 'pos-pickup') { 
            iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2 2 0 0 1 4 12v0a2 2 0 0 1-2-2V7"/></svg>`;
            label = 'POS Pickup';
        } else if (formState.channel === 'special-order') {
            iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/></svg>`;
            label = 'Special Order';
        } else {
            iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>`;
            label = 'Scheduled Production';
        }
        document.getElementById('review-icon-container').innerHTML = iconSvg;
        document.getElementById('review-channel').innerText = label;

        // Outlet Name
        const outletSelect = document.getElementById('create-outlet');
        document.getElementById('review-outlet').innerText = outletSelect.options[outletSelect.selectedIndex].text;

        // Customer
        document.getElementById('review-cust-name').innerText = document.getElementById('create-cust-name').value;
        document.getElementById('review-cust-phone').innerText = document.getElementById('create-cust-phone').value;
        document.getElementById('review-cust-email').innerText = document.getElementById('create-cust-email').value;
        if(formState.deliveryMethod === 'delivery') {
            document.getElementById('review-cust-address').innerText = document.getElementById('create-cust-address').value;
        } else {
            document.getElementById('review-cust-address').innerText = '';
        }

        // Delivery
        document.getElementById('review-delivery-label').innerText = formState.deliveryMethod === 'pickup' ? 'Pickup Details' : 'Delivery Details';
        document.getElementById('review-date').innerText = document.getElementById('create-date').value;
        document.getElementById('review-time').innerText = document.getElementById('create-time').value;

        // Items
        let subtotal = 0;
        document.getElementById('review-items-list').innerHTML = formState.cart.map(item => {
            subtotal += item.total;
            return `
            <div class="flex justify-between items-center bg-white rounded-lg p-3">
                <div class="flex-1"><div class="text-gray-900 font-medium">${item.name}</div><div class="text-sm text-gray-500">${item.quantity} x Rs ${item.price}</div></div>
                <div class="text-gray-900 font-bold">Rs ${item.total}</div>
            </div>`;
        }).join('');

        const tax = subtotal * 0.05;
        document.getElementById('review-subtotal').innerText = 'Rs ' + subtotal.toLocaleString();
        document.getElementById('review-tax').innerText = 'Rs ' + tax.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('review-total').innerText = 'Rs ' + (subtotal + tax).toLocaleString(undefined, {minimumFractionDigits: 2});
    }

    function selectPaymentMethod(method, btn) {
        // Map methods to integers: Cash=1, Card=2, Bank Transfer=3
        const paymentMap = {
            'Cash': 1,
            'Card': 2,
            'Bank Transfer': 3
        };
        formState.details.paymentMethod = paymentMap[method];

        document.querySelectorAll('.pay-btn').forEach(b => {
            b.classList.remove('border-purple-500', 'bg-purple-50');
            b.classList.add('border-gray-200', 'bg-white');
            b.querySelector('span').classList.remove('text-purple-700');
            b.querySelector('span').classList.add('text-gray-600');
        });
        btn.classList.remove('border-gray-200', 'bg-white');
        btn.classList.add('border-purple-500', 'bg-purple-50');
        btn.querySelector('span').classList.add('text-purple-700');

        // Show inputs
        document.getElementById('payment-details-inputs').classList.remove('hidden');

        // Auto-fill paid amount
        const totalText = document.getElementById('cart-total').innerText.replace('Rs ', '').replace(/,/g, '');
        const currentPaid = document.getElementById('create-paid-amount').value;
        if(!currentPaid || parseFloat(currentPaid) === 0) {
            document.getElementById('create-paid-amount').value = parseFloat(totalText).toFixed(2);
        }
    }

    function submitCreateOrder() {
        const btn = document.getElementById('btn-wizard-submit');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating...';
        btn.disabled = true;

        // Convert channel to integer: 1=pos_pickup, 2=special_order, 3=scheduled_production
        const orderTypeMap = {
            'pos-pickup': 1,
            'special-order': 2,
            'scheduled-production': 3
        };

        // Convert delivery method to integer: 1=Pickup, 2=Delivery
        const deliveryTypeMap = {
            'pickup': 1,
            'delivery': 2
        };

        // Date/Time
        const dateVal = document.getElementById('create-date').value;
        const timeVal = document.getElementById('create-time').value;

        // Prepare order data
        const orderData = {
            order_type: orderTypeMap[formState.channel],
            delivery_type: deliveryTypeMap[formState.deliveryMethod],
            delivery_date: `${dateVal} ${timeVal}:00`, // Combined DateTime
            branch_id: formState.outletId,
            notes: document.getElementById('create-instructions').value,
            products: formState.cart.map(item => ({
                product_item_id: item.id,
                quantity: item.quantity,
                unit_price: item.price
            })),
            quotation_id: formState.quotation_id || null
        };
        
        // For scheduled production, handle recurrence
        if (formState.channel === 'scheduled-production') {
             // Handle recurrence date
             if (formState.details.recurring) {
                 orderData.recurrence_pattern = parseInt(document.getElementById('create-recurrence').value) || null;
                 orderData.end_date = document.getElementById('create-recurrence-end-date').value || null;
             }
        }
        
        // end_time logic removed as it's now covered by delivery_date

        // Add customer for special orders
        if (formState.channel === 'special-order') {
            
            // If satisfied with selected customer (from autocomplete), use that ID directly
            if (formState.customer_id) {
                orderData.customer_id = formState.customer_id;
                orderData.payment_details = formState.details.paymentMethod || null;
                orderData.payment_reference = document.getElementById('create-payment-ref').value;
                orderData.paid_amount = document.getElementById('create-paid-amount').value || 0;
                orderData.event_type = formState.details.eventType || null;
                orderData.guest_count = parseInt(document.getElementById('create-guest-count').value) || null;
                submitOrderToAPI(orderData, btn, originalText);
                return;
            }

            // Otherwise, create new customer
            const customerName = document.getElementById('create-cust-name').value;
            const customerPhone = document.getElementById('create-cust-phone').value;
            const customerEmail = document.getElementById('create-cust-email').value;
            const customerAddress = document.getElementById('create-cust-address').value;

            // Create customer first
            fetch('{{ route("orderManagement.createCustomer") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: customerName,
                    phone: customerPhone,
                    email: customerEmail,
                    address: customerAddress
                })
            })
            .then(response => response.json())
            .then(customerData => {
                if (customerData.success) {
                    orderData.customer_id = customerData.customer.id;
                    orderData.payment_details = formState.details.paymentMethod || null;
                    orderData.payment_reference = document.getElementById('create-payment-ref').value;
                    orderData.paid_amount = document.getElementById('create-paid-amount').value || 0;
                    orderData.event_type = formState.details.eventType || null;
                    orderData.guest_count = parseInt(document.getElementById('create-guest-count').value) || null;
                    submitOrderToAPI(orderData, btn, originalText);
                } else {
                    throw new Error('Failed to create customer');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to create customer. Please try again.',
                    confirmButtonColor: '#9333ea'
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        } else {
            submitOrderToAPI(orderData, btn, originalText);
        }
    }


    function submitOrderToAPI(orderData, btn, originalText) {
        fetch('{{ route("orderManagement.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Order Created Successfully!',
                    text: 'Order number: ' + data.order.order_number,
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                }).then(() => {
                    closeCreateOrderModal();
                    location.reload();
                });
            } else {
                throw new Error(data.message || 'Failed to create order');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to create order: ' + error.message
            });
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>