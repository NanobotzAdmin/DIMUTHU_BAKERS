{{--
================================================
MODAL: ADD/EDIT CUSTOMER
================================================
--}}
<div id="customer-modal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4 transition-all backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col">

        {{-- Modal Header --}}
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 flex-shrink-0">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Add New Customer</h2>
                <p class="text-sm text-gray-500">Create a new B2B or B2C customer profile</p>
            </div>
            <button id="btn-close-modal" class="p-2 hover:bg-gray-200 rounded-full transition-colors">
                <i data-lucide="x" class="w-6 h-6 text-gray-500"></i>
            </button>
        </div>

        {{-- Modal Body (Scrollable) --}}
        <div class="p-6 overflow-y-auto flex-1 space-y-8">

            {{-- 1. Customer Type --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Customer Type <span
                        class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <input type="hidden" id="customerType" value="b2b">
                    <button type="button"
                        class="type-btn flex-1 p-4 border-2 rounded-xl transition-all border-[#D4A017] bg-[#D4A017]/5"
                        data-type="b2b">
                        <i data-lucide="building" class="w-6 h-6 mx-auto mb-2 text-blue-600"></i>
                        <div class="font-bold text-gray-900">B2B</div>
                        <div class="text-xs text-gray-500 mt-1">Business Customer</div>
                    </button>
                    <button type="button"
                        class="type-btn flex-1 p-4 border-2 border-gray-300 hover:border-gray-400 rounded-xl transition-all"
                        data-type="b2c">
                        <i data-lucide="user" class="w-6 h-6 mx-auto mb-2 text-green-600"></i>
                        <div class="font-bold text-gray-900">B2C</div>
                        <div class="text-xs text-gray-500 mt-1">Direct Consumer</div>
                    </button>
                </div>
            </div>

            {{-- 2. B2B Type (Conditional) --}}
            <div id="b2b-types-section">
                <label class="block text-sm font-semibold text-gray-700 mb-2">B2B Customer Type <span
                        class="text-red-500">*</span></label>
                <select id="b2bType"
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                    <option value="wholesale">Wholesale</option>
                    <option value="retail_shop">Retail Shop</option>
                    <option value="restaurant">Restaurant</option>
                    <option value="hotel">Hotel</option>
                    <option value="agent">Agent/Sub-distributor</option>
                    <option value="other">Other</option>
                </select>
            </div>

            {{-- 3. Basic Information --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Business/Customer Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="businessName" placeholder="e.g. ABC Supermarket"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                    <p id="err-businessName" class="error-msg text-xs text-red-500 mt-1 hidden">Business name is
                        required</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Trade Name (Optional)</label>
                    <input type="text" placeholder="Display name if different"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all">
                </div>
            </div>

            {{-- 4. Contact Information --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center"><i data-lucide="phone"
                            class="w-4 h-4 text-blue-600"></i></div>
                    Contact Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 border border-gray-200 rounded-2xl bg-gray-50/50">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="contactPerson" placeholder="Mr. Perera"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                        <p id="err-contactPerson" class="error-msg text-xs text-red-500 mt-1 hidden">Contact person is
                            required</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="phoneNumber" placeholder="+94 77 123 4567"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                        <p id="err-phoneNumber" class="error-msg text-xs text-red-500 mt-1 hidden">Phone number is
                            required</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alternate Phone</label>
                        <input type="text" id="alternatePhone" placeholder="+94 11 222 3333"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" placeholder="email@example.com"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                </div>
            </div>

            {{-- 5. Location --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center"><i data-lucide="map-pin"
                            class="w-4 h-4 text-red-600"></i></div>
                    Location
                </h3>
                <div class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">Search Address <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="search"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                        <input type="text" id="addressSearch" placeholder="Type address to search..."
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none shadow-sm">
                    </div>

                    {{-- Mock Manual Selection --}}
                    <button id="manual-location-btn" type="button"
                        class="hidden w-full py-2 bg-blue-50 text-blue-600 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4"></i> <span id="manual-location-text">Use as
                            location</span>
                    </button>

                    {{-- Selected Location Display --}}
                    <div id="selected-location-display"
                        class="hidden border border-green-200 bg-green-50 rounded-xl p-4 flex items-start gap-3">
                        <div class="bg-white p-2 rounded-full border border-green-100 shadow-sm">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-bold text-gray-900" id="location-address">Selected Address</div>
                            <div class="text-xs text-gray-500 mt-1">Coordinates: 6.9271, 79.8612 (Mock)</div>
                        </div>
                        <button id="btn-clear-location" class="text-gray-400 hover:text-red-500">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>

                    {{-- Map Placeholder --}}
                    <div
                        class="h-48 bg-gray-100 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400">
                        <i data-lucide="map" class="w-10 h-10 mb-2 opacity-50"></i>
                        <span class="text-sm">Map Preview</span>
                    </div>
                </div>
            </div>

            {{-- 6. Assignment --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center"><i
                            data-lucide="user-check" class="w-4 h-4 text-indigo-600"></i></div>
                    Assignment
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Agent</label>
                        <select id="assignedAgentId"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                            <option value="">Unassigned</option>
                            <option value="1">Agent 1</option>
                            <option value="2">Agent 2</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Route</label>
                        <select id="assignedRouteId"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                            <option value="">Unassigned</option>
                            <option value="1">Route A</option>
                            <option value="2">Route B</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stop Sequence</label>
                        <input type="number" id="stopSequence" placeholder="e.g. 5"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                </div>
            </div>

            {{-- 7. Credit Terms (B2B Only) --}}
            <div id="credit-terms-section">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center"><i
                            data-lucide="dollar-sign" class="w-4 h-4 text-amber-600"></i></div>
                    Credit Terms
                </h3>
                <div class="bg-amber-50 rounded-2xl p-5 border border-amber-100">
                    <label class="flex items-center gap-3 mb-4 cursor-pointer">
                        <input type="checkbox" id="allowCredit" checked
                            class="w-5 h-5 rounded text-amber-600 focus:ring-amber-500 border-gray-300">
                        <span class="font-medium text-gray-900">Allow credit sales for this customer</span>
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="credit-fields">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Credit Limit (Rs) <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rs</span>
                                <input type="number" id="creditLimit" value="50000"
                                    class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Terms</label>
                            <select id="paymentTermsDays"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none">
                                <option value="0">Cash Only</option>
                                <option value="7">7 Days</option>
                                <option value="15">15 Days</option>
                                <option value="30" selected>30 Days</option>
                                <option value="45">45 Days</option>
                                <option value="60">60 Days</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 8. Visit Schedule --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center"><i
                            data-lucide="calendar" class="w-4 h-4 text-teal-600"></i></div>
                    Visit Schedule
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                        <select id="visitFrequency"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                            <option value="daily">Daily</option>
                            <option value="weekly" selected>Weekly</option>
                            <option value="biweekly">Bi-Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Time</label>
                        <input type="time" id="preferredTime"
                            class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Visit Days</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                            <button type="button"
                                class="day-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-600 hover:bg-gray-200">
                                {{ $day }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 9. Notes --}}
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gray-200 flex items-center justify-center"><i
                            data-lucide="file-text" class="w-4 h-4 text-gray-600"></i></div>
                    Additional Notes
                </h3>
                <textarea id="notes" rows="3" placeholder="Special instructions, delivery notes, etc..."
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none resize-none"></textarea>
            </div>

        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50 flex-shrink-0">
            <button id="btn-cancel-modal"
                class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                Cancel
            </button>
            <button id="btn-save-customer"
                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#D4A017] to-[#B8860B] text-white font-bold shadow-lg hover:shadow-xl hover:opacity-90 transition-all transform active:scale-95">
                Save Customer
            </button>
        </div>
    </div>
</div>