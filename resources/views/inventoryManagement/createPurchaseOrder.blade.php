@extends('layouts.app')
@section('title', 'Create Purchase Order')

@section('content')

    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8" id="po-wizard-app">

        <div class="mb-6">
            <h1 class="text-2xl font-bold flex items-center gap-3 text-gray-900">
                <svg class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Create New Purchase Order
            </h1>
            <p class="text-base text-gray-600 mt-1" id="step-description">
                Step 1 of 3 - Select Supplier
            </p>
        </div>

        <div class="flex items-center gap-2 mb-6">
            <div class="flex-1 h-2 rounded-full transition-colors duration-300 bg-purple-500" id="progress-1"></div>
            <div class="flex-1 h-2 rounded-full transition-colors duration-300 bg-gray-200" id="progress-2"></div>
            <div class="flex-1 h-2 rounded-full transition-colors duration-300 bg-gray-200" id="progress-3"></div>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="create-po-form" method="POST" action="{{ route('createPurchaseOrder.store') }}">
            @csrf
            <input type="hidden" name="supplier_id" id="input-supplier-id">
            <input type="hidden" name="products_json" id="input-products-json">
            <input type="hidden" name="total_amount" id="input-total-amount">

            <div id="step-1-content" class="space-y-4">
                <div class="bg-blue-50 rounded-xl p-4 border-2 border-blue-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="font-medium text-blue-900 mb-1">Choose Your Supplier</h4>
                            <p class="text-sm text-blue-800">
                                Select a supplier to create a purchase order. Only products linked to this supplier from
                                Product Master will be available.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 max-h-[500px] overflow-y-auto">
                    @foreach($suppliers as $supplier)
                        <div onclick="selectSupplier('{{ $supplier['id'] }}')" id="supplier-card-{{ $supplier['id'] }}"
                            class="supplier-card cursor-pointer p-5 rounded-2xl border-2 text-left transition-all border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50">
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $supplier['name'] }}</h3>
                                            <p class="text-sm text-gray-600">{{ $supplier['contactPerson'] }} •
                                                {{ $supplier['phone'] }}
                                            </p>
                                        </div>
                                        <svg id="check-{{ $supplier['id'] }}" class="hidden w-6 h-6 text-purple-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                        <div class="bg-yellow-50 rounded-lg p-2">
                                            <div class="flex items-center gap-1 text-yellow-600 mb-1">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                </svg>
                                                <span class="text-sm font-medium">Rating</span>
                                            </div>
                                            <div class="text-lg font-bold text-yellow-900">{{ $supplier['rating'] }}/5</div>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-2">
                                            <div class="flex items-center gap-1 text-green-600 mb-1">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                </svg>
                                                <span class="text-sm font-medium">On-Time</span>
                                            </div>
                                            <div class="text-lg font-bold text-green-900">{{ $supplier['onTimeDelivery'] }}%
                                            </div>
                                        </div>
                                        <div class="bg-blue-50 rounded-lg p-2">
                                            <div class="flex items-center gap-1 text-blue-600 mb-1">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-sm font-medium">Lead Time</span>
                                            </div>
                                            <div class="text-lg font-bold text-blue-900">{{ $supplier['leadTime'] }}d</div>
                                        </div>
                                        <div class="bg-purple-50 rounded-lg p-2">
                                            <div class="flex items-center gap-1 text-purple-600 mb-1">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-sm font-medium">Terms</span>
                                            </div>
                                            <div class="text-lg font-bold text-purple-900">
                                                {{ str_replace('credit-', '', $supplier['paymentTerms']) }}d
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="step-2-content" class="space-y-6 hidden">

                <div id="cart-summary-container" class="hidden bg-purple-50 rounded-xl p-4 border-2 border-purple-200">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span class="font-medium text-purple-900" id="cart-count-label">Selected Products (0)</span>
                        </div>
                        <div class="text-xl font-bold text-purple-700" id="cart-total-label">Rs 0</div>
                    </div>
                    <div class="space-y-2" id="cart-items-list">
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border-2 border-gray-200">
                    <div class="flex items-center gap-3 mb-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="product-search" onkeyup="renderProductList()"
                            placeholder="Search products from Product Master..." class="flex-1 outline-none">
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 border-2 border-gray-200 max-h-96 overflow-y-auto">
                    <h3 class="font-medium text-gray-900 mb-3" id="product-list-title">Available Products</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" id="available-products-grid">
                    </div>
                </div>

                <div id="empty-cart-warning" class="bg-yellow-50 rounded-xl p-4 border-2 border-yellow-200">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <div class="font-medium text-yellow-900 mb-1">No Products Selected</div>
                            <div class="text-sm text-yellow-800">Please select at least one product to continue</div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="step-3-content" class="space-y-6 hidden">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-5 border-2 border-purple-200">
                    <h3 class="font-medium text-gray-900 mb-4">Order Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-sm text-gray-600 mb-2">Supplier</h4>
                            <div class="font-medium text-gray-900" id="review-supplier-name"></div>
                            <div class="text-sm text-gray-600" id="review-supplier-contact"></div>
                            <div class="text-sm text-gray-600" id="review-supplier-email"></div>
                        </div>
                        <div class="bg-white rounded-lg p-4">
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div>
                                    <div class="text-sm text-gray-600 mb-1">Items</div>
                                    <div class="text-2xl font-bold text-purple-600" id="review-item-count">0</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600 mb-1">Total Qty</div>
                                    <div class="text-2xl font-bold text-purple-600" id="review-total-qty">0</div>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-600 mb-1">Value</div>
                                    <div class="text-2xl font-bold text-purple-600" id="review-total-value">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-5 py-3 border-b-2 border-gray-200">
                        <h3 class="font-medium text-gray-900">Order Items</h3>
                    </div>
                    <div class="p-5 space-y-2 max-h-64 overflow-y-auto" id="review-items-list">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Terms *</label>
                        <select name="payment_terms"
                            class="w-full h-12 px-4 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none">
                            <option value="cash">Cash on Delivery</option>
                            <option value="credit-7">7 Days Credit</option>
                            <option value="credit-15">15 Days Credit</option>
                            <option value="credit-30" selected>30 Days Credit</option>
                            <option value="credit-60">60 Days Credit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expected Delivery Date *</label>
                        <input type="date" name="delivery_date"
                            class="w-full h-12 px-4 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none"
                            value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="3" placeholder="Add any special instructions or notes..."
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 outline-none resize-none"></textarea>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl p-5 text-white">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-medium">Grand Total</span>
                        <span class="text-2xl font-bold" id="final-grand-total">Rs 0</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-6 border-t-2 border-gray-200 mt-6">
                <button type="button" id="btn-prev" onclick="changeStep(-1)"
                    class="hidden h-14 px-8 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all">
                    Previous
                </button>
                <button type="button" id="btn-cancel"
                    class="flex-1 h-14 px-8 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all">
                    Cancel
                </button>
                <button type="button" id="btn-next" onclick="changeStep(1)"
                    class="flex-1 h-14 px-8 bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl font-medium shadow-lg transition-all flex items-center justify-center gap-2">
                    Next Step
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </button>
                <button type="submit" id="btn-submit"
                    class="hidden flex-1 h-14 px-8 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-medium shadow-lg transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Create Purchase Order
                </button>
            </div>
        </form>

    </div>

    <script>
        // --- 1. INITIALIZE DATA FROM PHP ---
        const suppliers = @json($suppliers);
        const allProducts = @json($products);

        // --- 2. STATE MANAGEMENT ---
        let currentStep = 1;
        let selectedSupplierId = null;
        let cart = []; // Array of objects: { ...product, quantity, totalPrice }

        // --- 3. DOM ELEMENTS ---
        const steps = {
            1: document.getElementById('step-1-content'),
            2: document.getElementById('step-2-content'),
            3: document.getElementById('step-3-content')
        };
        const progressBars = {
            1: document.getElementById('progress-1'),
            2: document.getElementById('progress-2'),
            3: document.getElementById('progress-3')
        };
        const buttons = {
            prev: document.getElementById('btn-prev'),
            next: document.getElementById('btn-next'),
            cancel: document.getElementById('btn-cancel'),
            submit: document.getElementById('btn-submit')
        };

        // --- 4. STEP NAVIGATION LOGIC ---
        function changeStep(delta) {
            const nextStep = currentStep + delta;

            // Validation before moving forward
            if (delta > 0) {
                if (currentStep === 1 && !selectedSupplierId) {
                    alert("Please select a supplier to continue.");
                    return;
                }
                if (currentStep === 2 && cart.length === 0) {
                    alert("Please add at least one product to continue.");
                    return;
                }
            }

            currentStep = nextStep;
            renderStep();
        }

        function renderStep() {
            // Hide all contents
            Object.values(steps).forEach(el => el.classList.add('hidden'));
            // Show current content
            steps[currentStep].classList.remove('hidden');

            // Update Header Text
            const titles = { 1: 'Select Supplier', 2: 'Add Products', 3: 'Review & Submit' };
            document.getElementById('step-description').innerText = `Step ${currentStep} of 3 - ${titles[currentStep]}`;

            // Update Progress Bars
            progressBars[2].classList.toggle('bg-purple-500', currentStep >= 2);
            progressBars[2].classList.toggle('bg-gray-200', currentStep < 2);
            progressBars[3].classList.toggle('bg-purple-500', currentStep >= 3);
            progressBars[3].classList.toggle('bg-gray-200', currentStep < 3);

            // Update Buttons
            if (currentStep === 1) {
                buttons.prev.classList.add('hidden');
                buttons.cancel.classList.remove('hidden'); // Show cancel on step 1 (or act as prev)
                buttons.next.classList.remove('hidden');
                buttons.submit.classList.add('hidden');
            } else if (currentStep === 2) {
                buttons.prev.classList.remove('hidden');
                buttons.cancel.classList.add('hidden');
                buttons.next.classList.remove('hidden');
                buttons.submit.classList.add('hidden');
                renderProductList(); // Ensure products are filtered by supplier
            } else if (currentStep === 3) {
                buttons.prev.classList.remove('hidden');
                buttons.next.classList.add('hidden');
                buttons.submit.classList.remove('hidden');
                renderReviewSummary(); // Populate review data
            }
        }

        // --- 5. SUPPLIER SELECTION LOGIC ---
        function selectSupplier(id) {
            selectedSupplierId = id;

            // Visual Selection Logic
            document.querySelectorAll('.supplier-card').forEach(el => {
                el.classList.remove('border-purple-500', 'bg-purple-50');
                el.classList.add('border-gray-200', 'bg-white');
            });
            const selectedCard = document.getElementById('supplier-card-' + id);
            selectedCard.classList.remove('border-gray-200', 'bg-white');
            selectedCard.classList.add('border-purple-500', 'bg-purple-50');

            // Toggle Check Icon
            document.querySelectorAll('[id^="check-"]').forEach(el => el.classList.add('hidden'));
            document.getElementById('check-' + id).classList.remove('hidden');

            // Reset Cart if supplier changes (Optional, but good practice)
            // For this demo, let's assume changing supplier clears cart
            if (cart.length > 0 && cart[0].supplierId !== id) {
                cart = [];
                renderCart();
            }

            // Update hidden input
            document.getElementById('input-supplier-id').value = id;
        }

        // --- 6. PRODUCT & CART LOGIC ---
        function renderProductList() {
            const grid = document.getElementById('available-products-grid');
            const searchTerm = document.getElementById('product-search').value.toLowerCase();

            // 1. Filter Logic:
            // - If search term exists: Search ALL products (globally)
            // - If NO search term: Suggest products linked to the selected supplier

            let filteredProducts = [];

            if (searchTerm.length > 0) {
                // Search EVERYTHING (Global)
                filteredProducts = allProducts.filter(p =>
                    p.name.toLowerCase().includes(searchTerm) ||
                    (p.ref_number && p.ref_number.toLowerCase().includes(searchTerm))
                );
                document.getElementById('product-list-title').innerText = `Search Results (${filteredProducts.length})`;
            } else {
                // Initial State: Show Supplier Linked Products
                filteredProducts = allProducts.filter(p => p.supplierData && p.supplierData[selectedSupplierId]);

                const supplier = suppliers.find(s => s.id == selectedSupplierId);
                const supplierName = supplier ? supplier.name : 'Unknown Supplier';
                document.getElementById('product-list-title').innerText = `Available Products for ${supplierName}`;
            }

            grid.innerHTML = '';

            if (filteredProducts.length === 0) {
                if (searchTerm.length > 0) {
                    grid.innerHTML = `<div class="col-span-3 text-center py-8 text-gray-500">No matching products found.</div>`;
                } else {
                    grid.innerHTML = `<div class="col-span-3 text-center py-8 text-gray-500">No linked products found for this supplier. Search to find other products.</div>`;
                }
                return;
            }

            filteredProducts.forEach(product => {
                const isAdded = cart.some(c => c.id === product.id);
                const classes = isAdded
                    ? 'border-green-300 bg-green-50 cursor-not-allowed'
                    : 'border-gray-200 bg-white hover:border-purple-300 hover:bg-purple-50 cursor-pointer';

                // Resolve Supplier Specific Data
                let currentPrice = product.supplierPrice;
                let currentSKU = product.supplierSKU;

                if (product.supplierData && product.supplierData[selectedSupplierId]) {
                    currentPrice = product.supplierData[selectedSupplierId].price;
                    currentSKU = product.supplierData[selectedSupplierId].sku;
                } else {
                    currentPrice = 0; // Or indicate 'New Link'
                    currentSKU = '-';
                }

                const html = `
                                                            <div onclick="${isAdded ? '' : `addToCart('${product.id}')`}" class="p-4 rounded-xl border-2 text-left transition-all ${classes}">
                                                                <div class="flex items-start justify-between mb-2">
                                                                    <div>
                                                                        <div class="font-medium text-gray-900 mb-1">${product.name}</div>
                                                                        <div class="text-xs text-gray-500 mb-1">${product.ref_number || ''}</div>
                                                                        <div class="text-sm text-gray-600 capitalize">${product.category}</div>
                                                                    </div>
                                                                    ${isAdded ? '<svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' : ''}
                                                                </div>
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mb-2">${product.type}</span>
                                                                <div class="flex items-center justify-between text-sm">
                                                                    <span class="text-gray-600">SKU: ${currentSKU}</span>
                                                                    <span class="font-medium text-purple-600">Rs ${currentPrice}/${product.unit}</span>
                                                                </div>
                                                            </div>
                                                        `;
                grid.innerHTML += html;
            });
        }

        function addToCart(productId) {
            const product = allProducts.find(p => p.id == productId);
            if (!product) return;

            // Resolve Price
            let unitPrice = 0;
            let sku = '-';
            if (product.supplierData && product.supplierData[selectedSupplierId]) {
                unitPrice = parseFloat(product.supplierData[selectedSupplierId].price) || 0;
                sku = product.supplierData[selectedSupplierId].sku;
            }

            cart.push({
                ...product,
                supplierSKU: sku,
                supplierPrice: unitPrice,
                quantity: 1,
                totalPrice: unitPrice // 1 * unitPrice
            });
            renderCart();
            renderProductList(); // Re-render to show "Added" state
        }

        function updatePrice(productId, newPrice) {
            const item = cart.find(i => i.id == productId);
            if (!item) return;

            const price = parseFloat(newPrice);
            if (isNaN(price) || price < 0) return;

            item.supplierPrice = price;
            item.totalPrice = item.quantity * price;
            renderCart();
        }

        function updateQuantity(productId, delta) {
            const item = cart.find(i => i.id == productId);
            if (!item) return;

            const newQty = item.quantity + delta;
            if (newQty < 1) return;

            item.quantity = newQty;
            item.totalPrice = item.quantity * item.supplierPrice;
            renderCart();
        }

        function setQuantity(productId, value) {
            const item = cart.find(i => i.id == productId);
            if (!item) return;

            let newQty = parseInt(value);
            if (isNaN(newQty) || newQty < 1) {
                newQty = 1;
            }

            item.quantity = newQty;
            item.totalPrice = item.quantity * item.supplierPrice;
            renderCart();
        }

        function removeFromCart(productId) {
            cart = cart.filter(i => i.id != productId);
            renderCart();
            renderProductList(); // Re-render to remove "Added" state
        }

        function renderCart() {
            const container = document.getElementById('cart-summary-container');
            const list = document.getElementById('cart-items-list');
            const warning = document.getElementById('empty-cart-warning');

            if (cart.length === 0) {
                container.classList.add('hidden');
                warning.classList.remove('hidden');
                return;
            }

            container.classList.remove('hidden');
            warning.classList.add('hidden');

            // Update Totals
            const totalAmount = cart.reduce((sum, i) => sum + i.totalPrice, 0);
            document.getElementById('cart-count-label').innerText = `Selected Products (${cart.length})`;
            document.getElementById('cart-total-label').innerText = `Rs ${totalAmount.toLocaleString()}`;

            // Render Items
            list.innerHTML = '';
            cart.forEach(item => {
                const html = `
                        <div class="grid grid-cols-12 gap-4 items-center bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:border-purple-200 transition-all">
                            <div class="col-span-4">
                                <div class="font-bold text-gray-900">${item.name}</div>
                                <div class="text-xs text-gray-500 font-medium tracking-wide">SKU: ${item.supplierSKU}</div>
                            </div>

                            <div class="col-span-2 text-center">
                                 <div class="text-xs text-gray-400 mb-1 uppercase tracking-wider font-bold text-[10px]">Price</div>
                                 <div class="relative group">
                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs group-focus-within:text-purple-500">Rs</span>
                                    <input type="number" 
                                        value="${item.supplierPrice}" 
                                        onchange="updatePrice('${item.id}', this.value)"
                                        class="w-full text-center pl-6 pr-2 py-1.5 rounded-lg border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-100 outline-none text-sm font-semibold text-gray-700 transition-all bg-gray-50 focus:bg-white">
                                 </div>
                            </div>

                            <div class="col-span-3 text-center">
                                <div class="text-xs text-gray-400 mb-1 uppercase tracking-wider font-bold text-[10px]">Qty</div>
                                <div class="flex items-center justify-center">
                                    <button type="button" onclick="updateQuantity('${item.id}', -1)" class="w-8 h-8 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-600 flex items-center justify-center transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                    <input type="number" 
                                        value="${item.quantity}" 
                                        onchange="setQuantity('${item.id}', this.value)"
                                        class="w-14 h-8 text-center border-y border-gray-200 text-sm font-bold text-gray-900 focus:ring-0 outline-none appearance-none z-10">
                                    <button type="button" onclick="updateQuantity('${item.id}', 1)" class="w-8 h-8 rounded-r-lg border border-l-0 border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-600 flex items-center justify-center transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="col-span-2 text-right">
                                <div class="text-xs text-gray-400 mb-1 uppercase tracking-wider font-bold text-[10px]">Total</div>
                                <div class="font-bold text-gray-900 text-base">Rs ${item.totalPrice.toLocaleString()}</div>
                            </div>

                            <div class="col-span-1 text-right">
                                <button type="button" onclick="removeFromCart('${item.id}')" class="p-2 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                    `;
                list.innerHTML += html;
            });

            // Update hidden form inputs
            document.getElementById('input-products-json').value = JSON.stringify(cart);
            document.getElementById('input-total-amount').value = totalAmount;
        }

        // --- 7. REVIEW STEP LOGIC ---
        function renderReviewSummary() {
            const supplier = suppliers.find(s => s.id == selectedSupplierId);

            // Populate Supplier Info
            document.getElementById('review-supplier-name').innerText = supplier.name;
            document.getElementById('review-supplier-contact').innerText = supplier.contactPerson;
            document.getElementById('review-supplier-email').innerText = supplier.email;

            // Populate Stats
            const totalQty = cart.reduce((sum, i) => sum + i.quantity, 0);
            const totalValue = cart.reduce((sum, i) => sum + i.totalPrice, 0);

            document.getElementById('review-item-count').innerText = cart.length;
            document.getElementById('review-total-qty').innerText = totalQty;
            document.getElementById('review-total-value').innerText = `Rs ${totalValue.toLocaleString()}`;
            document.getElementById('final-grand-total').innerText = `Rs ${totalValue.toLocaleString()}`;

            // Populate List
            const list = document.getElementById('review-items-list');
            list.innerHTML = '';
            cart.forEach(item => {
                const html = `
                                                            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                                                <div>
                                                                    <div class="font-medium text-gray-900">${item.name}</div>
                                                                    <div class="text-sm text-gray-600">SKU: ${item.supplierSKU}</div>
                                                                </div>
                                                                <div class="text-center">
                                                                    <div class="font-medium text-gray-900">${item.quantity} ${item.unit}</div>
                                                                    <div class="text-sm text-gray-600">@ Rs ${item.supplierPrice}</div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <div class="font-medium text-gray-900">Rs ${item.totalPrice.toLocaleString()}</div>
                                                                </div>
                                                            </div>
                                                        `;
                list.innerHTML += html;
            });
        }

        // Initialize
        renderStep();

        // --- 8. AJAX SUBMISSION ---
        document.getElementById('create-po-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('btn-submit');
            const originalBtnText = submitBtn.innerHTML;

            // Validate before sending
            if (!selectedSupplierId) {
                Swal.fire('Error', 'Please select a supplier.', 'error');
                return;
            }
            if (cart.length === 0) {
                Swal.fire('Error', 'Please add products to the cart.', 'error');
                return;
            }

            // Ensure hidden inputs are updated
            formData.set('supplier_id', selectedSupplierId);
            formData.set('products_json', JSON.stringify(cart));
            formData.set('total_amount', cart.reduce((sum, i) => sum + i.totalPrice, 0));


            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            `;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) throw response;
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message || 'Purchase Order Created Successfully',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#8B5CF6'
                        }).then(() => {
                            window.location.href = "{{ route('purchaseOrderManage.index') }}";
                        });
                    } else {
                        throw new Error(data.message || 'Unknown error occurred');
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;

                    let errorMessage = 'Something went wrong. Please try again.';
                    if (err instanceof Response) {
                        err.json().then(errorData => {
                            if (errorData.errors) {
                                // Validation errors
                                errorMessage = Object.values(errorData.errors).flat().join('\n');
                            } else {
                                errorMessage = errorData.message || errorMessage;
                            }
                            Swal.fire('Error', errorMessage, 'error');
                        });
                    } else {
                        Swal.fire('Error', err.message || errorMessage, 'error');
                    }
                });
        });
    </script>
@endsection