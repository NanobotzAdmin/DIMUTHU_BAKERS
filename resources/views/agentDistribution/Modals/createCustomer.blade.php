<!-- Customer Modal -->
<div id="customer-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/75 hidden">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 my-8 max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div
            class="p-6 border-b border-gray-100 flex justify-between items-center bg-white rounded-t-xl sticky top-0 z-10">
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-1" id="modal-title">Add New Customer</h2>
                <p class="text-sm text-gray-600" id="modal-subtitle">Create a new B2B or B2C customer</p>
            </div>
            <button onclick="closeCustomerModal()" class="text-gray-400 hover:text-gray-600 p-1">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <!-- Scrollable Content -->
        <div class="p-6 overflow-y-auto flex-1 space-y-6">
            <form id="customer-form" onsubmit="event.preventDefault(); saveCustomer();">
                <input type="hidden" id="cust-id">

                <!-- Customer Type -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Customer Type <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-4">
                        <div onclick="setCustomerType('b2b')" id="type-b2b"
                            class="flex-1 p-4 border-2 rounded-xl cursor-pointer transition-all border-[#D4A017] bg-[#D4A017]/5 relative">
                            <div class="flex flex-col items-center">
                                <i class="bi bi-building text-2xl text-blue-600 mb-2"></i>
                                <span class="text-gray-900 font-bold">B2B</span>
                                <span class="text-xs text-gray-500 mt-1">Business Customer</span>
                            </div>
                            <div class="absolute top-2 right-2 text-blue-600"><i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                        <div onclick="setCustomerType('b2c')" id="type-b2c"
                            class="flex-1 p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-gray-300 relative">
                            <div class="flex flex-col items-center">
                                <i class="bi bi-person text-2xl text-green-600 mb-2"></i>
                                <span class="text-gray-900 font-bold">B2C</span>
                                <span class="text-xs text-gray-500 mt-1">Direct Consumer</span>
                            </div>
                            <div class="absolute top-2 right-2 text-gray-300 hidden"><i
                                    class="bi bi-check-circle-fill"></i></div>
                        </div>
                    </div>
                    <input type="hidden" id="cust-type" value="b2b">
                </div>

                <!-- B2B Type -->
                <div id="section-b2b-type" class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        B2B Customer Type <span class="text-red-500">*</span>
                    </label>
                    <select id="cust-b2b-type"
                        class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        <option value="wholesale">Wholesale</option>
                        <option value="retail_shop">Retail Shop</option>
                        <option value="restaurant">Restaurant</option>
                        <option value="hotel">Hotel</option>
                        <option value="agent">Agent/Sub-distributor</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Business Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="cust-name" required placeholder="e.g. ABC Supermarket"
                            class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trade Name</label>
                        <input type="text" id="cust-trade-name" placeholder="Display name if different"
                            class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="mb-6">
                    <h3 class="text-gray-900 font-bold mb-3 flex items-center gap-2"><i
                            class="bi bi-telephone text-gray-400"></i> Contact Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="cust-contact" required placeholder="Name"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="cust-phone" required placeholder="+94 7X XXX XXXX"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="cust-email" placeholder="email@example.com"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="mb-6">
                    <h3 class="text-gray-900 font-bold mb-3 flex items-center gap-2"><i
                            class="bi bi-geo-alt text-gray-400"></i> Location</h3>

                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search Address</label>
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-3 text-gray-400"></i>
                            <input type="text" id="loc-search" oninput="handleAddressInput()"
                                placeholder="Type to search..."
                                class="w-full pl-10 pr-12 p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">

                            <button type="button" onclick="getCurrentLocation()"
                                class="absolute right-2 top-2 p-1 text-gray-400 hover:text-amber-600"
                                title="Get Current Location">
                                <i class="bi bi-crosshair text-lg"></i>
                            </button>
                        </div>
                        <div id="manual-loc-btn" class="hidden mt-2">
                            <button type="button" onclick="useManualLocation()"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                <i class="bi bi-pin-map-fill"></i> Use this address manually
                            </button>
                        </div>
                    </div>

                    <!-- Selected Location Preview -->
                    <div id="loc-preview" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-geo-alt-fill text-red-600 text-xl"></i>
                            <div class="flex-1">
                                <p class="text-gray-900 font-medium" id="loc-address">123 Example St</p>
                                <p class="text-sm text-gray-600"><span id="loc-city">Colombo</span>, <span
                                        id="loc-district">Western</span></p>
                                <p class="text-xs text-gray-400 mt-1" id="loc-coords">Lat: 0.0, Lng: 0.0</p>
                            </div>
                            <button type="button" onclick="clearLocation()"
                                class="text-sm text-gray-500 hover:text-red-500">Clear</button>
                        </div>
                        <input type="hidden" id="final-address">
                        <input type="hidden" id="final-city">
                        <input type="hidden" id="final-district">
                        <input type="hidden" id="final-lat">
                        <input type="hidden" id="final-lng">
                    </div>
                </div>

                <!-- Assignment -->
                <div class="mb-6">
                    <h3 class="text-gray-900 font-bold mb-3 flex items-center gap-2"><i
                            class="bi bi-person-badge text-gray-400"></i> Assignment</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
                            <select id="cust-agent"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                <option value="">Unassigned</option>
                                @foreach($agents as $a)
                                    <option value="{{ $a['id'] }}">{{ $a['agentName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Route</label>
                            <select id="cust-route"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                <option value="">Unassigned</option>
                                @foreach($routes as $r)
                                    <option value="{{ $r['id'] }}">{{ $r['routeName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stop Sequence</label>
                            <input type="number" id="cust-sequence" min="1" placeholder="#"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>
                </div>

                <!-- Credit Terms (B2B Only) -->
                <div id="section-credit" class="mb-6">
                    <h3 class="text-gray-900 font-bold mb-3 flex items-center gap-2"><i
                            class="bi bi-cash-coin text-gray-400"></i> Credit Terms</h3>
                    <div class="flex items-center gap-2 mb-4">
                        <input type="checkbox" id="allow-credit" onchange="toggleCredit()" checked
                            class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                        <label for="allow-credit" class="text-sm font-medium text-gray-700">Allow Credit Sales</label>
                    </div>
                    <div id="credit-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Credit Limit (Rs.)</label>
                            <input type="number" id="cust-limit" placeholder="50000"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Terms</label>
                            <select id="cust-terms"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
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

                <!-- Visit Schedule -->
                <div class="mb-6">
                    <h3 class="text-gray-900 font-bold mb-3 flex items-center gap-2"><i
                            class="bi bi-calendar-event text-gray-400"></i> Visit Schedule</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Visit Frequency</label>
                            <select id="cust-frequency"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="biweekly">Bi-weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Time</label>
                            <input type="time" id="cust-pref-time"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>

                    <!-- Preferred Days -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Visit Days</label>
                        <div class="flex flex-wrap gap-2" id="pref-days-container">
                            <button type="button" onclick="togglePreferredDay('monday')" id="btn-monday"
                                class="px-3 py-1.5 rounded-lg text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-transparent">Mon</button>
                            <button type="button" onclick="togglePreferredDay('tuesday')" id="btn-tuesday"
                                class="px-3 py-1.5 rounded-lg text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-transparent">Tue</button>
                            <button type="button" onclick="togglePreferredDay('wednesday')" id="btn-wednesday"
                                class="px-3 py-1.5 rounded-lg text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-transparent">Wed</button>
                            <button type="button" onclick="togglePreferredDay('thursday')" id="btn-thursday"
                                class="px-3 py-1.5 rounded-lg text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-transparent">Thu</button>
                            <button type="button" onclick="togglePreferredDay('friday')" id="btn-friday"
                                class="px-3 py-1.5 rounded-lg text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-transparent">Fri</button>
                            <button type="button" onclick="togglePreferredDay('saturday')" id="btn-saturday"
                                class="px-3 py-1.5 rounded-lg text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-transparent">Sat</button>
                            <button type="button" onclick="togglePreferredDay('sunday')" id="btn-sunday"
                                class="px-3 py-1.5 rounded-lg text-sm bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all border border-transparent">Sun</button>
                        </div>
                        <input type="hidden" id="cust-pref-days" value="[]">
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-6">
                    <h3 class="text-gray-900 font-bold mb-3 flex items-center gap-2"><i
                            class="bi bi-info-circle text-gray-400"></i> Additional Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                            <textarea id="cust-special-instr" rows="2"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                placeholder="Any special requirements..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Instructions</label>
                            <textarea id="cust-delivery-instr" rows="2"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                placeholder="Delivery notes for agent..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Internal Notes</label>
                            <textarea id="cust-notes" rows="2"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                placeholder="Internal notes (not visible to agent)..."></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-100 flex justify-end gap-3 bg-white rounded-b-xl sticky bottom-0 z-10">
            <button onclick="closeCustomerModal()"
                class="px-5 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg font-medium transition-colors">Cancel</button>
            <button onclick="saveCustomer()"
                class="px-5 py-2.5 bg-[#D4A017] text-white hover:bg-[#B8860B] rounded-lg shadow-sm font-medium transition-colors flex items-center gap-2">
                <i class="bi bi-check2"></i> Save Customer
            </button>
        </div>
    </div>
</div>