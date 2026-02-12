@extends('layouts.app')

@section('content')
    <div class="space-y-6 p-6">
        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Online Ordering Settings</h1>
            <p class="text-gray-600 mt-1">Configure your online shop settings and preferences</p>
        </div>

        {{-- Master Toggle --}}
        <div class="bg-white rounded-lg border-2 border-[#D4A017] p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-lg">Online Ordering Status</h3>
                    <p class="text-sm text-gray-500">Enable or disable online ordering for all outlets</p>
                </div>
                <div class="flex items-center gap-3">
                    <span id="masterStatusBadge"
                        class="px-3 py-1 rounded-full text-sm font-medium {{ $config->enabled ? 'bg-primary text-primary-foreground' : 'border border-gray-300 text-gray-700' }}">
                        {{ $config->enabled ? 'Enabled' : 'Disabled' }}
                    </span>

                    <button type="button" onclick="toggleOnlineOrdering()"
                        class="{{ $config->enabled ? 'bg-black' : 'bg-gray-200' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2"
                        role="switch" aria-checked="{{ $config->enabled ? 'true' : 'false' }}">
                        <span aria-hidden="true"
                            class="{{ $config->enabled ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Settings Tabs --}}
        <div class="space-y-6">
            {{-- Tab Navigation --}}
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button onclick="switchTab('general')" id="tab-general"
                        class="tab-btn border-black text-black whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        General Settings
                    </button>
                    <button onclick="switchTab('outlets')" id="tab-outlets"
                        class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Outlet Configuration
                    </button>
                    <button onclick="switchTab('payment')" id="tab-payment"
                        class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Payment Settings
                    </button>
                </nav>
            </div>

            {{-- General Settings Content --}}
            <div id="content-general" class="tab-content space-y-6">
                {{-- Store Information --}}
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="font-semibold text-lg">Store Information</h3>
                        <p class="text-sm text-gray-500">Basic information about your online store</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="storeName" class="block text-sm font-medium text-gray-700">Store Name</label>
                                <input type="text" id="storeName" value="{{ $config->storeName }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm h-10 px-3 border"
                                    placeholder="Your Bakery">
                            </div>

                            <div class="space-y-2">
                                <label for="contactEmail" class="block text-sm font-medium text-gray-700">Contact
                                    Email</label>
                                <input type="email" id="contactEmail" value="{{ $config->contactEmail }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm h-10 px-3 border"
                                    placeholder="contact@yourbakery.com">
                            </div>

                            <div class="space-y-2">
                                <label for="contactPhone" class="block text-sm font-medium text-gray-700">Contact
                                    Phone</label>
                                <input type="text" id="contactPhone" value="{{ $config->contactPhone }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm h-10 px-3 border"
                                    placeholder="011-234-5678">
                            </div>

                            <div class="space-y-2">
                                <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                                <input type="text" id="currency" value="{{ $config->currency }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm h-10 px-3 border"
                                    placeholder="LKR">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="storeDescription" class="block text-sm font-medium text-gray-700">Store
                                Description</label>
                            <textarea id="storeDescription" rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm p-3 border"
                                placeholder="Welcome to our online bakery...">{{ $config->storeDescription }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Order Settings --}}
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="font-semibold text-lg">Order Settings</h3>
                        <p class="text-sm text-gray-500">Configure order-related settings</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="taxRate" class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                    </div>
                                    <input type="number" id="taxRate" step="0.1" value="{{ $config->taxRate * 100 }}"
                                        class="block w-full rounded-md border-gray-300 pl-10 focus:border-black focus:ring-black sm:text-sm h-10 border">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="minOrderValue" class="block text-sm font-medium text-gray-700">Minimum Order
                                    Value</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" id="minOrderValue"
                                        value="{{ $config->globalOrderSettings->minOrderValue }}"
                                        class="block w-full rounded-md border-gray-300 pl-10 focus:border-black focus:ring-black sm:text-sm h-10 border">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="leadTime" class="block text-sm font-medium text-gray-700">Default Pickup Lead
                                    Time (minutes)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <input type="number" id="leadTime"
                                        value="{{ $config->defaultPickupSettings->advanceOrderMinutes }}"
                                        class="block w-full rounded-md border-gray-300 pl-10 focus:border-black focus:ring-black sm:text-sm h-10 border">
                                </div>
                                <p class="text-xs text-gray-500">Minimum time customers must wait before pickup</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Allow Guest Checkout</span>
                                <span class="block text-sm text-gray-500">Allow customers to order without creating an
                                    account</span>
                            </div>
                            <button type="button" onclick="toggleSwitch(this)"
                                class="{{ $config->globalOrderSettings->allowGuestCheckout ? 'bg-black' : 'bg-gray-200' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2"
                                role="switch"
                                aria-checked="{{ $config->globalOrderSettings->allowGuestCheckout ? 'true' : 'false' }}">
                                <span aria-hidden="true"
                                    class="{{ $config->globalOrderSettings->allowGuestCheckout ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                </span>
                            </button>
                        </div>

                        <div>
                            <button type="button" onclick="saveGlobalSettings()"
                                class="inline-flex items-center rounded-md border border-transparent bg-black px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">
                                <svg class="mr-2 -ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                    </path>
                                </svg>
                                Save Global Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Outlet Configuration Content --}}
            <div id="content-outlets" class="tab-content hidden space-y-6">
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="font-semibold text-lg">Outlet Online Ordering</h3>
                        <p class="text-sm text-gray-500">Enable/disable online ordering for specific outlets</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($locations as $location)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <h3 class="font-semibold text-gray-900">{{ $location->name }}</h3>
                                            <span
                                                class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $location->enabled ? 'bg-primary text-primary-foreground' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $location->enabled ? 'Online' : 'Offline' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ $location->address }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button" onclick="toggleOutlet(this, '{{ $location->id }}')"
                                            class="{{ $location->enabled ? 'bg-black' : 'bg-gray-200' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2"
                                            role="switch" aria-checked="{{ $location->enabled ? 'true' : 'false' }}">
                                            <span aria-hidden="true"
                                                class="{{ $location->enabled ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500">No retail outlets configured</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Settings Content --}}
            <div id="content-payment" class="tab-content hidden space-y-6">
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="font-semibold text-lg">Payment Methods</h3>
                        <p class="text-sm text-gray-500">Configure available payment options</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Online Payment (Stripe)</span>
                                <span class="block text-sm text-gray-500">Accept credit/debit cards online</span>
                            </div>
                            <button type="button" onclick="toggleSwitch(this)"
                                class="{{ $config->paymentMethods->online->enabled ? 'bg-black' : 'bg-gray-200' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2"
                                role="switch"
                                aria-checked="{{ $config->paymentMethods->online->enabled ? 'true' : 'false' }}">
                                <span aria-hidden="true"
                                    class="{{ $config->paymentMethods->online->enabled ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                </span>
                            </button>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Cash on Pickup</span>
                                <span class="block text-sm text-gray-500">Allow customers to pay with cash when picking
                                    up</span>
                            </div>
                            <button type="button" onclick="toggleSwitch(this)"
                                class="{{ $config->paymentMethods->cashOnPickup->enabled ? 'bg-black' : 'bg-gray-200' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2"
                                role="switch"
                                aria-checked="{{ $config->paymentMethods->cashOnPickup->enabled ? 'true' : 'false' }}">
                                <span aria-hidden="true"
                                    class="{{ $config->paymentMethods->cashOnPickup->enabled ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                </span>
                            </button>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <span class="block text-sm font-medium text-gray-900">Card on Pickup</span>
                                <span class="block text-sm text-gray-500">Allow card payment at outlet during pickup</span>
                            </div>
                            <button type="button" onclick="toggleSwitch(this)"
                                class="{{ $config->paymentMethods->cardOnPickup->enabled ? 'bg-black' : 'bg-gray-200' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2"
                                role="switch"
                                aria-checked="{{ $config->paymentMethods->cardOnPickup->enabled ? 'true' : 'false' }}">
                                <span aria-hidden="true"
                                    class="{{ $config->paymentMethods->cardOnPickup->enabled ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="font-semibold text-lg">Stripe Configuration</h3>
                        <p class="text-sm text-gray-500">Configure your Stripe payment gateway</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-2">
                            <label for="stripePublishableKey" class="block text-sm font-medium text-gray-700">Publishable
                                Key</label>
                            <input type="text" id="stripePublishableKey"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm h-10 px-3 border"
                                placeholder="pk_test_...">
                        </div>

                        <div class="space-y-2">
                            <label for="stripeSecretKey" class="block text-sm font-medium text-gray-700">Secret Key</label>
                            <input type="password" id="stripeSecretKey"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black sm:text-sm h-10 px-3 border"
                                placeholder="sk_test_...">
                            <p class="text-xs text-gray-500">Your secret key will be encrypted and stored securely</p>
                        </div>

                        <div class="flex items-center gap-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="w-1 h-12 bg-blue-600 rounded"></div>
                            <div class="text-sm text-blue-900">
                                <strong>Test Mode:</strong> Use test API keys for development. Switch to live keys when
                                ready for production.
                            </div>
                        </div>

                        <div>
                            <button type="button" onclick="saveStripeSettings()"
                                class="inline-flex items-center rounded-md border border-transparent bg-black px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">
                                <svg class="mr-2 -ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                    </path>
                                </svg>
                                Save Stripe Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab Switching
        function switchTab(tabName) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active styles from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-black', 'text-black');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected content
            document.getElementById(`content-${tabName}`).classList.remove('hidden');

            // Add active styles to selected tab
            const selectedTab = document.getElementById(`tab-${tabName}`);
            selectedTab.classList.remove('border-transparent', 'text-gray-500');
            selectedTab.classList.add('border-black', 'text-black');
        }

        // Toggle Switch Helper
        function toggleSwitch(button) {
            const isChecked = button.getAttribute('aria-checked') === 'true';
            const newState = !isChecked;

            button.setAttribute('aria-checked', newState);

            if (newState) {
                button.classList.remove('bg-gray-200');
                button.classList.add('bg-black');
                button.querySelector('span').classList.remove('translate-x-0');
                button.querySelector('span').classList.add('translate-x-5');
            } else {
                button.classList.remove('bg-black');
                button.classList.add('bg-gray-200');
                button.querySelector('span').classList.remove('translate-x-5');
                button.querySelector('span').classList.add('translate-x-0');
            }
        }

        // Toggle Online Ordering
        function toggleOnlineOrdering() {
            const btn = document.querySelector('[onclick="toggleOnlineOrdering()"]');
            const badge = document.getElementById('masterStatusBadge');
            const isCurrentlyEnabled = btn.getAttribute('aria-checked') === 'true';

            toggleSwitch(btn); // Visual update

            // Update Badge text
            if (!isCurrentlyEnabled) {
                badge.textContent = 'Enabled';
                badge.classList.remove('bg-gray-100', 'text-gray-700', 'border', 'border-gray-300');
                badge.classList.add('bg-primary', 'text-primary-foreground'); // Assuming these classes exist or standard Tailwind
                badge.classList.add('bg-black', 'text-white'); // Fallback/Standard
                alert('Online ordering enabled');
            } else {
                badge.textContent = 'Disabled';
                badge.classList.remove('bg-primary', 'text-primary-foreground', 'bg-black', 'text-white');
                badge.classList.add('bg-gray-100', 'text-gray-700', 'border', 'border-gray-300'); // Fallback/Standard
                alert('Online ordering disabled');
            }
        }

        // Toggle Outlet
        function toggleOutlet(button, outletId) {
            toggleSwitch(button);
            const isEnabled = button.getAttribute('aria-checked') === 'true';
            // Find the badge relative to button to update text like "Online" / "Offline"
            const row = button.closest('.flex'); // Parent container
            // Note: in the blade structure, Badge is in the previous sibling div
            // Let's just create a toast notification for now
            alert(`Outlet ${isEnabled ? 'enabled' : 'disabled'}`);
        }

        // Save Handlers
        function saveGlobalSettings() {
            // Collect form data if needed
            alert('Global settings saved');
        }

        function saveStripeSettings() {
            alert('Stripe settings saved');
        }
    </script>
@endsection