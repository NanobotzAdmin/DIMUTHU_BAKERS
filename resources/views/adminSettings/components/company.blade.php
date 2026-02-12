<form id="company-settings-form" action="" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- Building2 Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Company Settings</h2>
                <p class="text-sm text-gray-600">Basic business information and contact details</p>
            </div>
        </div>

        {{-- Save Actions (Hidden by default, shown via JS on change) --}}
        <div id="save-actions" class="hidden flex items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                Cancel
            </button>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                {{-- Save Icon --}}
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Save Changes
            </button>
        </div>
    </div>

    {{-- Business Information Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">Business Name (Legal)</label>
                    <input type="text" name="business_name" id="business_name" 
                        value="{{ old('business_name', $settings->business_name ?? 'Your Bakery (Pvt) Ltd') }}"
                        class="w-full  border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="Your Bakery (Pvt) Ltd">
                </div>
                
                <div>
                    <label for="trading_name" class="block text-sm font-medium text-gray-700 mb-1">Trading Name</label>
                    <input type="text" name="trading_name" id="trading_name" 
                        value="{{ old('trading_name', $settings->trading_name ?? 'Your Bakery') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="Your Bakery">
                </div>
                
                <div>
                    <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-1">Tax ID / VAT Number</label>
                    <input type="text" name="tax_id" id="tax_id" 
                        value="{{ old('tax_id', $settings->tax_id ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="123456789V">
                </div>
                
                <div>
                    <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-1">Business Registration Number</label>
                    <input type="text" name="registration_number" id="registration_number" 
                        value="{{ old('registration_number', $settings->registration_number ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="PV 12345">
                </div>
            </div>
        </div>
    </div>

    {{-- Address Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Business Address</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="address_street" class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                    <input type="text" name="address[street]" id="address_street" 
                        value="{{ old('address.street', $settings->address['street'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="123 Galle Road">
                </div>
                
                <div>
                    <label for="address_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="address[city]" id="address_city" 
                        value="{{ old('address.city', $settings->address['city'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="Colombo 3">
                </div>
                
                <div>
                    <label for="address_province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                    <input type="text" name="address[province]" id="address_province" 
                        value="{{ old('address.province', $settings->address['province'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="Western">
                </div>
                
                <div>
                    <label for="address_postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                    <input type="text" name="address[postal_code]" id="address_postal_code" 
                        value="{{ old('address.postal_code', $settings->address['postal_code'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="00300">
                </div>
            </div>
        </div>
    </div>

    {{-- Contact Information Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="contact[phone]" id="contact_phone" 
                        value="{{ old('contact.phone', $settings->contact['phone'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="+94 11 234 5678">
                </div>
                
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="contact[email]" id="contact_email" 
                        value="{{ old('contact.email', $settings->contact['email'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="info@yourbakery.com">
                </div>
                
                <div class="md:col-span-2">
                    <label for="contact_website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="contact[website]" id="contact_website" 
                        value="{{ old('contact.website', $settings->contact['website'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="www.yourbakery.com">
                </div>
            </div>
        </div>
    </div>

    {{-- System Settings Card --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="fiscal_year" class="block text-sm font-medium text-gray-700 mb-1">Fiscal Year Start</label>
                    <input type="text" name="fiscal_year_start" id="fiscal_year" 
                        value="{{ old('fiscal_year_start', $settings->fiscal_year_start ?? '01-01') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-300 focus:ring-gray-300 sm:text-sm p-2"
                        placeholder="01-01">
                    <p class="text-xs text-gray-500 mt-1">Format: MM-DD (e.g., 01-01 for January 1)</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                    <input type="text" value="{{ $settings->timezone ?? 'Asia/Colombo' }}" disabled
                        class="w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 shadow-sm cursor-not-allowed sm:text-sm p-2">
                    <p class="text-xs text-gray-500 mt-1">Fixed to Asia/Colombo for Sri Lanka</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                    <input type="text" value="Rs. (Sri Lankan Rupees)" disabled
                        class="w-full rounded-md border-gray-200 bg-gray-50 text-gray-500 shadow-sm cursor-not-allowed sm:text-sm p-2">
                    <p class="text-xs text-gray-500 mt-1">System currency is locked</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions (Duplicate for accessibility/UX on long pages) --}}
    <div id="save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
            Cancel
        </button>
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
            </svg>
            Save Changes
        </button>
    </div>

</form>

{{-- Flash Message (Success) --}}
@if(session('success'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" 
    class="fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded shadow-lg flex items-center gap-2">
    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    {{ session('success') }}
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('company-settings-form');
        const saveActionsTop = document.getElementById('save-actions');
        const saveActionsBottom = document.getElementById('save-actions-bottom');
        const inputs = form.querySelectorAll('input');
        
        // Store initial values to compare against
        let initialValues = {};
        inputs.forEach(input => {
            initialValues[input.name] = input.value;
        });

        // Function to check if form has changed
        function checkForChanges() {
            let hasChanged = false;
            inputs.forEach(input => {
                if (input.value !== initialValues[input.name]) {
                    hasChanged = true;
                }
            });

            if (hasChanged) {
                saveActionsTop.classList.remove('hidden');
                saveActionsTop.classList.add('flex');
                
                saveActionsBottom.classList.remove('hidden');
                saveActionsBottom.classList.add('flex');
            } else {
                saveActionsTop.classList.add('hidden');
                saveActionsTop.classList.remove('flex');
                
                saveActionsBottom.classList.add('hidden');
                saveActionsBottom.classList.remove('flex');
            }
        }

        // Attach listener to all inputs
        inputs.forEach(input => {
            input.addEventListener('input', checkForChanges);
        });

        // Global reset function
        window.resetForm = function() {
            inputs.forEach(input => {
                input.value = initialValues[input.name];
            });
            checkForChanges();
        }
    });
</script>