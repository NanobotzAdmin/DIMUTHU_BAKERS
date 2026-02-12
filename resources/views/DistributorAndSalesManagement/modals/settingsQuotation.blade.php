{{-- resources/views/quotations/modals/settings.blade.php --}}

<div id="settingsModal"
    class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 overflow-y-auto backdrop-blur-sm transition-all duration-300">
    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col my-8 transform transition-all scale-100">

        {{-- ================= HEADER ================= --}}
        <div
            class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    {{-- Settings Icon --}}
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl text-gray-900 font-bold">Quotation Settings</h2>
                    <p class="text-sm text-gray-600">Configure company branding and letterhead</p>
                </div>
            </div>
            <button onclick="closeSettingsModal()"
                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- ================= TABS ================= --}}
        <div class="px-6 border-b border-gray-200 flex gap-1 bg-white flex-shrink-0">
            <button onclick="switchSettingsTab('company')" id="tab-btn-company"
                class="flex items-center gap-2 px-4 py-3 border-b-2 border-purple-600 text-purple-600 transition-all font-medium">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                    <line x1="9" y1="22" x2="9" y2="22"></line>
                    <line x1="15" y1="22" x2="15" y2="22"></line>
                    <line x1="12" y1="22" x2="12" y2="22"></line>
                    <line x1="12" y1="2" x2="12" y2="4"></line>
                    <line x1="8" y1="6" x2="8" y2="6"></line>
                    <line x1="16" y1="6" x2="16" y2="6"></line>
                </svg>
                Company Info
            </button>
            <button onclick="switchSettingsTab('contact')" id="tab-btn-contact"
                class="flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-600 hover:text-gray-900 transition-all font-medium">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path
                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                    </path>
                </svg>
                Contact & Bank
            </button>
            <button onclick="switchSettingsTab('defaults')" id="tab-btn-defaults"
                class="flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-600 hover:text-gray-900 transition-all font-medium">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Default Terms
            </button>
            <button onclick="switchSettingsTab('styling')" id="tab-btn-styling"
                class="flex items-center gap-2 px-4 py-3 border-b-2 border-transparent text-gray-600 hover:text-gray-900 transition-all font-medium">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="13.5" cy="6.5" r=".5"></circle>
                    <circle cx="17.5" cy="10.5" r=".5"></circle>
                    <circle cx="8.5" cy="7.5" r=".5"></circle>
                    <circle cx="6.5" cy="12.5" r=".5"></circle>
                    <path
                        d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z">
                    </path>
                </svg>
                Styling
            </button>
        </div>

        {{-- ================= CONTENT AREA ================= --}}
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <div class="max-w-4xl mx-auto space-y-6">

                {{-- TAB: COMPANY INFO --}}
                <div id="tab-content-company" class="settings-tab-content space-y-6">
                    {{-- Logo Upload --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">
                            <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            Company Logo
                        </h3>
                        <div class="flex flex-col md:flex-row gap-6 items-start">
                            <div class="flex-shrink-0">
                                <div class="w-48 h-48 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center bg-gray-50 overflow-hidden"
                                    id="logoPreviewContainer">
                                    <div class="text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-2" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                        <p class="text-sm">No logo uploaded</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-4">Upload your company logo to appear on quotations.
                                    Recommended size: 400x200px. Max file size: 2MB.</p>
                                <div class="flex gap-3">
                                    <input type="file" id="logoInput" class="hidden" accept="image/*"
                                        onchange="handleLogoUpload(this)">
                                    <button onclick="document.getElementById('logoInput').click()"
                                        class="h-10 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-lg flex items-center gap-2 text-sm transition-all shadow-md">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                        </svg>
                                        Upload Logo
                                    </button>
                                    <button onclick="removeLogo()"
                                        class="h-10 px-4 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg flex items-center gap-2 text-sm transition-all hidden"
                                        id="removeLogoBtn">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                        </svg>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Company Details --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">
                            <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect>
                                <line x1="9" y1="22" x2="9" y2="22"></line>
                                <line x1="15" y1="22" x2="15" y2="22"></line>
                                <line x1="12" y1="22" x2="12" y2="22"></line>
                                <line x1="12" y1="2" x2="12" y2="4"></line>
                                <line x1="8" y1="6" x2="8" y2="6"></line>
                                <line x1="16" y1="6" x2="16" y2="6"></line>
                            </svg>
                            Company Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Company Name *</label>
                                <input type="text" id="set_company_name"
                                    class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Tagline / Slogan</label>
                                <input type="text" id="set_tagline" placeholder="e.g., Freshly Baked, Daily Delivered"
                                    class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Business Reg. No.</label>
                                <input type="text" id="set_br_no"
                                    class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">VAT Number</label>
                                <input type="text" id="set_vat_no"
                                    class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB: CONTACT & BANK --}}
                <div id="tab-content-contact" class="hidden settings-tab-content space-y-6">
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">
                            <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            Contact Information
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Address *</label>
                                <input type="text" id="set_address"
                                    class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div><label class="block text-sm text-gray-600 mb-2">City *</label><input type="text"
                                        id="set_city"
                                        class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                                </div>
                                <div><label class="block text-sm text-gray-600 mb-2">District</label><input type="text"
                                        id="set_district"
                                        class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                                </div>
                                <div><label class="block text-sm text-gray-600 mb-2">Postal Code</label><input
                                        type="text" id="set_postal"
                                        class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div><label class="block text-sm text-gray-600 mb-2">Phone *</label><input type="tel"
                                        id="set_phone"
                                        class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                                </div>
                                <div><label class="block text-sm text-gray-600 mb-2">Email *</label><input type="email"
                                        id="set_email"
                                        class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">
                            <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            Bank Details (Optional)
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block text-sm text-gray-600 mb-2">Bank Name</label><input type="text"
                                    id="set_bank_name"
                                    class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            </div>
                            <div><label class="block text-sm text-gray-600 mb-2">Account Name</label><input type="text"
                                    id="set_acc_name"
                                    class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            </div>
                            <div><label class="block text-sm text-gray-600 mb-2">Account Number</label><input
                                    type="text" id="set_acc_no"
                                    class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            </div>
                            <div><label class="block text-sm text-gray-600 mb-2">Branch</label><input type="text"
                                    id="set_branch"
                                    class="w-full h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB: DEFAULTS --}}
                <div id="tab-content-defaults" class="hidden settings-tab-content space-y-6">
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <h3 class="text-lg mb-4 font-semibold text-gray-800">Default Terms</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Default Payment Terms</label>
                                <textarea id="set_def_payment" rows="2"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Default Terms & Conditions</label>
                                <textarea id="set_def_terms" rows="6"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 text-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Footer Text</label>
                                <textarea id="set_footer" rows="2" placeholder="Thank you message..."
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB: STYLING --}}
                <div id="tab-content-styling" class="hidden settings-tab-content space-y-6">
                    <div class="bg-white border border-gray-200 rounded-xl p-6">
                        <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">
                            <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="13.5" cy="6.5" r=".5"></circle>
                                <circle cx="17.5" cy="10.5" r=".5"></circle>
                                <circle cx="8.5" cy="7.5" r=".5"></circle>
                                <circle cx="6.5" cy="12.5" r=".5"></circle>
                                <path
                                    d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z">
                                </path>
                            </svg>
                            Brand Colors
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Primary Color</label>
                                <div class="flex gap-3 items-center">
                                    <input type="color" id="set_primary_color" value="#9333ea"
                                        class="w-16 h-10 rounded-lg border border-gray-300 cursor-pointer p-1">
                                    <input type="text" id="set_primary_text" value="#9333ea"
                                        class="flex-1 h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 uppercase">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Used for headers and accents</p>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Accent Color</label>
                                <div class="flex gap-3 items-center">
                                    <input type="color" id="set_accent_color" value="#4f46e5"
                                        class="w-16 h-10 rounded-lg border border-gray-300 cursor-pointer p-1">
                                    <input type="text" id="set_accent_text" value="#4f46e5"
                                        class="flex-1 h-10 px-4 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 uppercase">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Used for secondary elements</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================= FOOTER ================= --}}
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between flex-shrink-0">
            <div class="flex gap-2">
                <button onclick="alert('Export logic')"
                    class="h-10 px-4 text-gray-700 hover:bg-gray-200 rounded-lg flex items-center gap-2 text-sm transition-all">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Export
                </button>
                <button onclick="confirmReset()"
                    class="h-10 px-4 text-red-700 hover:bg-red-100 rounded-lg flex items-center gap-2 text-sm transition-all">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                    Reset
                </button>
            </div>

            <div class="flex gap-3">
                <button onclick="closeSettingsModal()"
                    class="h-10 px-6 text-gray-700 hover:bg-gray-200 rounded-lg transition-all font-medium">
                    Cancel
                </button>
                <button onclick="saveSettings()"
                    class="h-10 px-6 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg flex items-center gap-2 transition-all shadow-md font-medium">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // --- Modal Logic ---
    function toggleModal(modalId) {
        // Reuse generic toggle logic if available in parent, otherwise:
        const modal = document.getElementById(modalId);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    function closeSettingsModal() {
        document.getElementById('settingsModal').classList.add('hidden');
    }

    // Load Settings when modal opens
    function openSettingsModal() {
        document.getElementById('settingsModal').classList.remove('hidden');
        loadSettings();
    }

    function loadSettings() {
        fetch('{{ route("quotationManagement.getSettings") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const s = data.data;

                    // Company
                    if (s.company_name) document.getElementById('set_company_name').value = s.company_name;
                    if (s.tagline) document.getElementById('set_tagline').value = s.tagline;
                    if (s.br_no) document.getElementById('set_br_no').value = s.br_no;
                    if (s.vat_no) document.getElementById('set_vat_no').value = s.vat_no;

                    // Logo
                    if (s.logo_url) {
                        const container = document.getElementById('logoPreviewContainer');
                        container.innerHTML = `<img src="${s.logo_url}" alt="Company Logo" class="max-w-full max-h-full object-contain">`;
                        document.getElementById('removeLogoBtn').classList.remove('hidden');
                    }

                    // Contact
                    if (s.address) document.getElementById('set_address').value = s.address;
                    if (s.city) document.getElementById('set_city').value = s.city;
                    if (s.district) document.getElementById('set_district').value = s.district;
                    if (s.postal_code) document.getElementById('set_postal').value = s.postal_code;
                    if (s.phone) document.getElementById('set_phone').value = s.phone;
                    if (s.email) document.getElementById('set_email').value = s.email;

                    // Bank
                    if (s.bank_name) document.getElementById('set_bank_name').value = s.bank_name;
                    if (s.account_name) document.getElementById('set_acc_name').value = s.account_name;
                    if (s.account_number) document.getElementById('set_acc_no').value = s.account_number;
                    if (s.bank_branch) document.getElementById('set_branch').value = s.bank_branch;

                    // Defaults
                    if (s.default_payment_terms) document.getElementById('set_def_payment').value = s.default_payment_terms;
                    if (s.default_terms_conditions) document.getElementById('set_def_terms').value = s.default_terms_conditions;
                    if (s.footer_text) document.getElementById('set_footer').value = s.footer_text;

                    // Styling
                    if (s.primary_color) {
                        document.getElementById('set_primary_color').value = s.primary_color;
                        document.getElementById('set_primary_text').value = s.primary_color.toUpperCase();
                    }
                    if (s.accent_color) {
                        document.getElementById('set_accent_color').value = s.accent_color;
                        document.getElementById('set_accent_text').value = s.accent_color.toUpperCase();
                    }
                }
            })
            .catch(error => console.error('Error loading settings:', error));
    }

    // --- Tab Switching Logic ---
    function switchSettingsTab(tabName) {
        // Hide all contents
        ['company', 'contact', 'defaults', 'styling'].forEach(t => {
            document.getElementById(`tab-content-${t}`).classList.add('hidden');
            const btn = document.getElementById(`tab-btn-${t}`);
            btn.classList.remove('border-purple-600', 'text-purple-600');
            btn.classList.add('border-transparent', 'text-gray-600');
        });

        // Show active content
        document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');
        const activeBtn = document.getElementById(`tab-btn-${tabName}`);
        activeBtn.classList.remove('border-transparent', 'text-gray-600');
        activeBtn.classList.add('border-purple-600', 'text-purple-600');
    }

    // --- Logo Upload Logic ---
    function handleLogoUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate Size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire('Error', 'Image size must be less than 2MB', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const container = document.getElementById('logoPreviewContainer');
                container.innerHTML = `<img src="${e.target.result}" alt="Company Logo" class="max-w-full max-h-full object-contain">`;
                document.getElementById('removeLogoBtn').classList.remove('hidden');
                // You would typically upload this to server here
            }
            reader.readAsDataURL(file);
        }
    }

    function removeLogo() {
        const container = document.getElementById('logoPreviewContainer');
        container.innerHTML = `
            <div class="text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                <p class="text-sm">No logo uploaded</p>
            </div>
        `;
        document.getElementById('logoInput').value = '';
        document.getElementById('removeLogoBtn').classList.add('hidden');
    }

    // --- Save & Reset ---
    function saveSettings() {
        const formData = new FormData();

        // Company
        formData.append('company_name', document.getElementById('set_company_name').value);
        formData.append('tagline', document.getElementById('set_tagline').value);
        formData.append('br_no', document.getElementById('set_br_no').value);
        formData.append('vat_no', document.getElementById('set_vat_no').value);

        // Logo
        const logoInput = document.getElementById('logoInput');
        if (logoInput.files.length > 0) {
            formData.append('logo', logoInput.files[0]);
        }

        // Contact
        formData.append('address', document.getElementById('set_address').value);
        formData.append('city', document.getElementById('set_city').value);
        formData.append('district', document.getElementById('set_district').value);
        formData.append('postal_code', document.getElementById('set_postal').value);
        formData.append('phone', document.getElementById('set_phone').value);
        formData.append('email', document.getElementById('set_email').value);

        // Bank
        formData.append('bank_name', document.getElementById('set_bank_name').value);
        formData.append('account_name', document.getElementById('set_acc_name').value);
        formData.append('account_number', document.getElementById('set_acc_no').value);
        formData.append('bank_branch', document.getElementById('set_branch').value);

        // Defaults
        formData.append('default_payment_terms', document.getElementById('set_def_payment').value);
        formData.append('default_terms_conditions', document.getElementById('set_def_terms').value);
        formData.append('footer_text', document.getElementById('set_footer').value);

        // Styling
        formData.append('primary_color', document.getElementById('set_primary_color').value);
        formData.append('accent_color', document.getElementById('set_accent_color').value);

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('{{ route("quotationManagement.saveSettings") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Settings saved successfully',
                        icon: 'success',
                        confirmButtonColor: '#7c3aed'
                    }).then(() => {
                        closeSettingsModal();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to save settings', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An unexpected error occurred', 'error');
            });
    }

    function confirmReset() {
        Swal.fire({
            title: 'Reset to Defaults?',
            text: "This cannot be undone. All custom settings will be lost.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, Reset'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Reset!', 'Settings reset to defaults.', 'success');
                // Reset form fields logic here
            }
        });
    }

    // Sync Color Inputs
    document.getElementById('set_primary_color').addEventListener('input', function (e) {
        document.getElementById('set_primary_text').value = e.target.value.toUpperCase();
    });
    document.getElementById('set_primary_text').addEventListener('input', function (e) {
        document.getElementById('set_primary_color').value = e.target.value;
    });

    document.getElementById('set_accent_color').addEventListener('input', function (e) {
        document.getElementById('set_accent_text').value = e.target.value.toUpperCase();
    });
    document.getElementById('set_accent_text').addEventListener('input', function (e) {
        document.getElementById('set_accent_color').value = e.target.value;
    });
</script>