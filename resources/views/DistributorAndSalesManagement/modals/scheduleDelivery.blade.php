<div id="schedule-delivery-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity opacity-0" id="schedule-delivery-backdrop"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            {{-- Panel --}}
            <div id="schedule-delivery-panel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl max-h-[90vh] overflow-y-auto opacity-0 scale-95 duration-300 ease-out">
                
                {{-- Header --}}
                <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-2xl text-gray-900 font-bold">Schedule Delivery</h2>
                        <p class="text-gray-600 font-medium" id="schedule-invoice-number">#INV-000000</p>
                    </div>
                    <button type="button" onclick="closeScheduleModal()" class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600"><line x1="18" x2="6" y1="6" y2="18"></line><line x1="6" x2="18" y1="6" y2="18"></line></svg>
                    </button>
                </div>

                <form id="schedule-delivery-form" onsubmit="handleScheduleSubmit(event)" class="p-6 space-y-6">
                    <input type="hidden" id="schedule-delivery-id" name="delivery_id">

                    {{-- Delivery Summary --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 flex-shrink-0 mt-1"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <div>
                                <h3 class="text-gray-900 mb-1 font-bold" id="schedule-customer-name">Customer Name</h3>
                                <p class="text-sm text-gray-600" id="schedule-address">Address Line 1</p>
                                <p class="text-sm text-gray-600" id="schedule-phone">+1 234 567 890</p>
                                <div class="mt-2 text-sm text-gray-700">
                                    <strong id="schedule-items-count">0 item(s)</strong> - Total: <span id="schedule-total-value">Rs 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Date Selection --}}
                    <div>
                        <label class="flex items-center gap-2 text-gray-700 mb-2 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            Delivery Date
                        </label>
                        <input
                            type="date"
                            id="schedule-date"
                            name="scheduled_date"
                            min="{{ date('Y-m-d') }}"
                            class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors"
                            required
                        >
                    </div>

                    {{-- Time Slot Selection --}}
                    <div>
                        <label class="flex items-center gap-2 text-gray-700 mb-2 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            Time Slot
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <input type="hidden" id="schedule-time-slot" name="time_slot" required>
                            
                            <button type="button" onclick="selectTimeSlot('morning')" id="btn-slot-morning" class="time-slot-btn p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-purple-300 transition-all text-left">
                                <div class="text-sm text-gray-900 mb-1 capitalize font-bold">Morning</div>
                                <div class="text-xs text-gray-600">6:00 AM - 12:00 PM</div>
                            </button>
                            
                            <button type="button" onclick="selectTimeSlot('afternoon')" id="btn-slot-afternoon" class="time-slot-btn p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-purple-300 transition-all text-left">
                                <div class="text-sm text-gray-900 mb-1 capitalize font-bold">Afternoon</div>
                                <div class="text-xs text-gray-600">12:00 PM - 6:00 PM</div>
                            </button>
                            
                            <button type="button" onclick="selectTimeSlot('evening')" id="btn-slot-evening" class="time-slot-btn p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-purple-300 transition-all text-left">
                                <div class="text-sm text-gray-900 mb-1 capitalize font-bold">Evening</div>
                                <div class="text-xs text-gray-600">6:00 PM - 9:00 PM</div>
                            </button>
                        </div>
                    </div>

                    {{-- Driver Selection --}}
                    <div>
                        <label class="flex items-center gap-2 text-gray-700 mb-2 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Assign Driver (Optional)
                        </label>
                        <select
                            id="schedule-driver"
                            name="driver_id"
                            onchange="checkAssignmentStatus()"
                            class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors"
                        >
                            <option value="">Select driver later</option>
                            {{-- Drivers will be populated here via JS or assume available for now --}}
                            <option value="1">John Doe - ✅ Available</option>
                            <option value="2">Jane Smith - 📦 2 active</option>
                        </select>
                    </div>

                    {{-- Vehicle Selection --}}
                    <div>
                        <label class="flex items-center gap-2 text-gray-700 mb-2 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                            Assign Vehicle (Optional)
                        </label>
                        <select
                            id="schedule-vehicle"
                            name="vehicle_id"
                            onchange="checkAssignmentStatus()"
                            class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors"
                        >
                            <option value="">Select vehicle later</option>
                            {{-- Vehicles populated here --}}
                            <option value="1">Van 1 (ABC-123) - ✅ Available</option>
                            <option value="2">Truck 2 (XYZ-789) - 🚚 In Use</option>
                        </select>
                    </div>

                    {{-- Warning if incomplete --}}
                    <div id="schedule-warning" class="bg-orange-50 border border-orange-200 rounded-xl p-4 flex items-start gap-3 hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600 flex-shrink-0 mt-0.5"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" x2="12" y1="9" y2="13"></line><line x1="12" x2="12.01" y1="17" y2="17"></line></svg>
                        <div class="text-sm text-orange-800">
                            <strong>Note:</strong> You can assign driver and vehicle later. The delivery will be marked as "scheduled" until both are assigned.
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <button
                            type="button"
                            onclick="closeScheduleModal()"
                            class="flex-1 h-12 px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors font-medium"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="flex-1 h-12 px-6 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl transition-all shadow-lg font-bold"
                        >
                            Schedule Delivery
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
