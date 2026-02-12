<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS - BakeryMate</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/bakery.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: flex;
        }

        /* Hide number input spinners */
        .no-spinner::-webkit-inner-spin-button,
        .no-spinner::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .no-spinner {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-900">

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 flex flex-col">

        <!-- Header -->
        <div class="bg-white border-b border-gray-200">
            <div class="px-4 lg:px-6 py-3 lg:py-4">
                <div class="flex items-center justify-between">
                    <!-- Brand -->
                    <div class="flex items-center gap-2 lg:gap-3">
                        <div
                            class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl lg:rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-cart-fill text-white text-lg lg:text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-base lg:text-2xl font-bold text-gray-900">Point of Sale</h1>
                            <div class="flex items-center gap-2 text-xs lg:text-sm text-gray-600">
                                <span class="hidden sm:inline">Terminal: T-001</span>
                                <span class="hidden sm:inline">|</span>
                                <span>Cashier:
                                    {{ Auth::user()->first_name . ' ' . Auth::user()->last_name ?? 'Staff' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Actions -->
                    <div class="flex items-center gap-2 lg:gap-3">
                        <!-- Branch -->
                        <div class="hidden md:flex items-center">
                            <div
                                class="flex items-center gap-2 px-4 py-1.5 bg-slate-100 border border-slate-200 rounded-full">
                                <span
                                    class="text-xs font-semibold uppercase tracking-wider text-slate-500">Branch</span>
                                <span class="text-sm font-bold text-indigo-700">
                                    {{ Auth::user()->currentBranch?->name ?? 'Not Selected' }}
                                </span>
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                </span>
                            </div>
                        </div>
                        <!-- Shortcuts Button -->
                        <button id="btn-shortcuts"
                            class="flex items-center gap-1.5 lg:gap-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-2 lg:px-3 py-1.5 lg:py-2 rounded-lg lg:rounded-xl transition-colors group"
                            title="Keyboard Shortcuts (?)">
                            <i class="bi bi-keyboard group-hover:animate-pulse"></i>
                            <span class="text-xs lg:text-sm hidden sm:inline">Shortcuts</span>
                            <span class="text-xs hidden lg:inline bg-indigo-200 px-1.5 py-0.5 rounded">?</span>
                        </button>

                        <!-- Fullscreen Toggle -->
                        <button id="btn-fullscreen"
                            class="flex items-center gap-1.5 lg:gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 lg:px-3 py-1.5 lg:py-2 rounded-lg lg:rounded-xl transition-colors"
                            title="Toggle Fullscreen (F11)">
                            <i class="bi bi-fullscreen" id="icon-fullscreen"></i>
                            <span class="text-xs lg:text-sm hidden sm:inline" id="text-fullscreen">Fullscreen</span>
                            <span class="text-xs hidden lg:inline bg-gray-200 px-1.5 py-0.5 rounded">F11</span>
                        </button>

                        <!-- Held Transactions Badge -->
                        <div id="badge-held" style="display: none;"
                            class="flex items-center gap-1.5 lg:gap-2 bg-orange-100 text-orange-700 px-2 lg:px-4 py-1.5 lg:py-2 rounded-lg lg:rounded-xl cursor-pointer hover:bg-orange-200 transition-colors">
                            <i class="bi bi-clock-history"></i>
                            <span class="text-xs lg:text-sm"><span id="count-held">0</span> Held</span>
                            <span class="text-xs hidden lg:inline bg-orange-200 px-1.5 py-0.5 rounded">F4</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-t border-gray-200 overflow-x-auto no-scrollbar">
                <div class="flex" style="min-width: max-content;" id="tabs-container">
                    <!-- Tabs will be rendered here by JS -->
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-hidden relative">

            <!-- POS Tab -->
            <div id="tab-pos" class="tab-content active h-full flex flex-col md:flex-row w-full">
                <!-- Product Grid Area (Left) -->
                <div class="flex-1 flex flex-col h-full bg-slate-50 relative overflow-hidden">
                    <!-- Loading State -->
                    <div id="loader" style="display: none;"
                        class="absolute inset-0 flex items-center justify-center bg-white/50 z-50">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                    </div>

                    <!-- Search & Barcode -->
                    <div class="p-4 bg-white border-b border-gray-200 space-y-3 flex-shrink-0">
                        <!-- Search Bar -->
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="text" id="product-search" placeholder="Search products..."
                                class="w-full h-12 pl-11 pr-4 bg-gray-50 border border-gray-200 rounded-xl text-base focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-colors" />
                        </div>

                        <!-- Barcode Scanner -->
                        <form id="form-barcode" class="relative">
                            <i
                                class="bi bi-upc-scan absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                            <input type="text" id="barcode-input" placeholder="Scan barcode or enter SKU..."
                                class="w-full h-12 pl-11 pr-4 bg-gray-50 border border-gray-200 rounded-xl text-base focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-colors" />
                        </form>
                    </div>

                    <!-- Category Tabs -->
                    <div class="px-4 py-3 bg-white border-b border-gray-200 overflow-x-auto flex-shrink-0">
                        <div class="flex gap-2 min-w-max" id="categories-container">
                            <!-- Categories rendered by JS -->
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="flex-1 overflow-y-auto p-4 bg-gray-50">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"
                            id="products-grid">
                            <!-- Products rendered by JS -->
                        </div>
                    </div>
                </div>

                <!-- Cart Area (Right) -->
                <!-- Cart Area (Right) -->
                <div
                    class="w-full md:w-96 lg:w-[450px] bg-gradient-to-br from-slate-50 to-purple-50 border-l border-gray-200 flex flex-col shadow-xl z-20">

                    <!-- Header -->
                    <div class="p-4 bg-white border-b border-gray-200 flex-shrink-0">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                                    <i class="bi bi-cart-fill text-white"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg text-gray-900 font-semibold">Current Order</h2>
                                    <p class="text-xs text-gray-500"><span id="cart-count">0</span> items</p>
                                </div>
                            </div>

                            <!-- Clear Cart Button -->
                            <button id="btn-clear-cart" style="display: none;"
                                class="w-10 h-10 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors shadow-sm"
                                title="Clear cart">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        <!-- Customer Selection -->
                        <div id="customer-section">
                            <!-- Populated by JS -->
                            <button id="btn-customer-select"
                                class="w-full h-12 bg-gray-50 hover:bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center gap-2 text-gray-600 hover:text-gray-700 transition-colors">
                                <i class="bi bi-person"></i>
                                <span class="text-sm font-medium">Select Customer (Optional)</span>
                            </button>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    <div class="flex-1 overflow-y-auto p-4" id="cart-items-container">
                        <!-- Cart items rendered by JS -->
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <i class="bi bi-cart text-6xl mb-3 opacity-20"></i>
                            <p class="text-lg text-gray-500">Cart is empty</p>
                            <p class="text-sm text-gray-400">Add items to get started</p>
                        </div>
                    </div>

                    <!-- Totals Section -->
                    <div id="cart-footer" style="display: none;"
                        class="p-4 bg-white border-t border-gray-200 space-y-3 flex-shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                        <!-- Subtotal -->
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="text-gray-900 font-medium" id="val-subtotal">Rs 0.00</span>
                        </div>

                        <!-- Discount -->
                        <div class="flex items-center justify-between text-sm" id="row-discount" style="display: none;">
                            <span class="text-gray-600">Discount:</span>
                            <span class="text-green-600 font-medium" id="val-discount">- Rs 0.00</span>
                        </div>

                        <!-- Tax -->
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Tax (0%):</span>
                            <span class="text-gray-900 font-medium" id="val-tax">Rs 0.00</span>
                        </div>

                        <!-- Total -->
                        <div class="pt-3 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-lg text-gray-900 font-bold">Total:</span>
                                <span class="text-2xl text-purple-600 font-bold" id="val-total">Rs 0.00</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-2 pt-2">
                            <button id="btn-discount"
                                class="w-full h-10 bg-orange-50 hover:bg-orange-100 text-orange-600 rounded-xl text-sm flex items-center justify-center gap-2 transition-colors font-medium">
                                <i class="bi bi-percent"></i>
                                Apply Discount to Total
                            </button>

                            <!-- Checkout Button -->
                            <button id="btn-checkout"
                                class="w-full h-14 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-xl text-lg font-bold transition-all shadow-lg hover:shadow-xl active:scale-95 flex items-center justify-center gap-2">
                                Checkout
                            </button>

                            <!-- Hold Transaction -->
                            <button id="btn-hold"
                                class="w-full h-10 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm flex items-center justify-center gap-2 transition-colors font-medium">
                                <i class="bi bi-clock"></i>
                                Hold Transaction
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Tabs Placeholders -->
            <div id="tab-online-pickup" class="tab-content h-full hidden w-full"></div>
            <div id="tab-orders" class="tab-content h-full hidden w-full"></div>
            <div id="tab-history" class="tab-content h-full hidden w-full"></div>
            <div id="tab-returns" class="tab-content h-full hidden w-full"></div>
            <div id="tab-cash-recon" class="tab-content h-full hidden w-full"></div>
            <div id="tab-shift-report" class="tab-content h-full hidden w-full"></div>
            @include('pos.modals.payment')
            @include('pos.modals.customer')
            @include('pos.modals.discount')
            @include('pos.modals.held')
            @include('pos.modals.hold')
            @include('pos.modals.shortcuts')
            @include('pos.modals.transactionLookup')

            <!-- Hidden Iframe for Printing -->
            <iframe id="receipt-print-frame" name="receipt-print-frame" style="display:none;"></iframe>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // State
                const state = {
                    activeTab: 'pos',
                    cart: [],
                    heldTransactions: [],
                    currentCustomer: null,
                    searchQuery: '',
                    selectedCategory: 'All', // Updated default
                    discount: 0,
                    discountType: 1, // 1: Percentage, 2: Fixed

                    // Customer Modal State
                    customerModal: {
                        view: 'list', // 'list' or 'quick-add'
                        searchQuery: '',
                        selectedId: null
                    },

                    // Discount Modal State
                    discountModal: {
                        type: 'percentage', // 'percentage' | 'fixed'
                        value: '0',
                        originalAmount: 0
                    },

                    // Payment Modal State
                    paymentModal: {
                        methods: [],
                        selectedType: 'cash',
                        amount: '',
                        reference: '',
                        totalToPay: 0
                    },

                    // Held Panel State
                    heldPanel: {
                        searchQuery: '',
                        selectedId: null
                    }
                };

                // Static Data (Mock)
                const tabs = [
                    { id: 'pos', label: 'Point of Sale', icon: 'bi-cart' },
                    { id: 'online-pickup', label: 'Online Pickup', icon: 'bi-bag' },
                    { id: 'orders', label: 'Incoming Orders', icon: 'bi-box-seam' },
                    { id: 'history', label: 'Transaction History', icon: 'bi-clock-history' },
                    { id: 'returns', label: 'Returns & Refunds', icon: 'bi-arrow-counterclockwise' },
                    { id: 'cash-recon', label: 'Cash Reconcilation', icon: 'bi-cash-stack' },
                    { id: 'shift-report', label: 'Shift Report', icon: 'bi-bar-chart' }
                ];



                // Dynamic Data
                let categories = [];
                let products = [];
                let customers = []; // Will be loaded via search/init

                $(document).ready(function () {
                    // Init
                    fetchPosData();
                    renderTabs();
                    // renderCategories(); // Called after fetch
                    // renderProducts(); // Called after fetch
                    renderCart();
                    renderTotals();
                    renderTotals();
                    updateCustomerUI();

                    // Customer Modal Logic
                    setupCustomerModalListeners();
                    setupDiscountModalListeners();
                    setupPaymentModalListeners();

                    // Event Listeners

                    // Tabs
                    // Tabs
                    $(document).on('click', '.tab-btn', function () {
                        const tabId = $(this).data('tab');
                        $('.tab-btn').removeClass('border-purple-600 text-purple-600 bg-purple-50').addClass('border-transparent text-gray-600 hover:bg-gray-50');
                        $(this).addClass('border-purple-600 text-purple-600 bg-purple-50').removeClass('border-transparent text-gray-600 hover:bg-gray-50');

                        $('.tab-content').removeClass('active').addClass('hidden').hide();
                        $(`#tab-${tabId}`).removeClass('hidden').addClass('active').css('display', 'flex');
                        state.activeTab = tabId;

                        // Load Tab Content if empty
                        const container = $(`#tab-${tabId}`);
                        if (container.children().length === 0) {
                            loadTabContent(tabId);
                        }
                    });

                    function loadTabContent(tabId) {
                        const container = $(`#tab-${tabId}`);
                        container.html('<div class="flex items-center justify-center h-full w-full"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div></div>');

                        $.get(`/pos/tabs/${tabId}`, function (html) {
                            container.html(html);
                            // Scripts in injected HTML are executed automatically by jQuery
                        }).fail(function () {
                            container.html('<div class="flex flex-col items-center justify-center h-full text-red-500"><i class="bi bi-exclamation-triangle text-4xl mb-2"></i><p>Failed to load content</p><button onclick="loadTabContent(\'' + tabId + '\')" class="mt-4 px-4 py-2 bg-red-100 hover:bg-red-200 rounded text-red-700">Retry</button></div>');
                        });
                    }

                    // Categories
                    $(document).on('click', '.cat-btn', function () {
                        state.selectedCategory = $(this).data('cat');
                        renderCategories(); // Re-render to update active styling
                        renderProducts();
                    });

                    // Search
                    $('#product-search').on('keyup', function () {
                        state.searchQuery = $(this).val().toLowerCase();
                        renderProducts();
                    });

                    // Barcode Submit
                    $('#form-barcode').on('submit', function (e) {
                        e.preventDefault();
                        const barcode = $('#barcode-input').val().trim().toUpperCase();
                        if (!barcode) return;

                        const product = products.find(p => p.sku === barcode);
                        if (product) {
                            if (product.stockLevel === 0) {
                                toastr.error('Product is out of stock!');
                            } else {
                                addToCart(product);
                                $('#barcode-input').val('');
                                toastr.success(`Added ${product.name}`);
                            }
                        } else {
                            toastr.warning(`Product not found: ${barcode}`);
                            $('#barcode-input').val('');
                        }
                    });

                    // Add to Cart
                    $(document).on('click', '.product-card', function () {
                        if ($(this).hasClass('out-of-stock')) return; // Prevent clicking out of stock

                        const id = $(this).data('id');
                        const product = products.find(p => p.id == id); // Loose equality for number/string mismatch
                        if (product) addToCart(product);
                    });



                    // Clear Cart
                    $('#btn-clear-cart').click(function () {
                        if (state.cart.length > 0 && Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, clear it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                state.cart = [];
                                state.discount = 0;
                                renderCart();
                                toastr.info('Cart cleared');
                            }
                        })) {
                        }
                    });

                    // Hold
                    $('#btn-hold').click(function () {
                        if (state.cart.length === 0) return toastr.error('Cart is empty');
                        $('#hold-ref-input').val(''); // Clear input
                        $('#modal-hold').show();
                        setTimeout(() => $('#hold-ref-input').focus(), 100);
                    });

                    $('#form-hold').on('submit', function (e) {
                        e.preventDefault();
                        const ref = $('#hold-ref-input').val().trim();
                        if (!ref) return;

                        state.heldTransactions.push({
                            id: Date.now(),
                            time: new Date().toLocaleTimeString(),
                            reference: ref,
                            total: calculateTotal(),
                            items: [...state.cart]
                        });
                        state.cart = [];
                        state.discount = 0;
                        renderCart();
                        updateHeldBadge();
                        $('#modal-hold').hide();
                        toastr.success('Transaction held successfully');
                    });

                    // Modals
                    $('.btn-close-modal').click(function () {
                        $('#' + $(this).data('target')).hide();
                    });

                    // Payment
                    $('#btn-checkout').click(function () {
                        if (state.cart.length === 0) return toastr.error('Empty Cart');
                        openPaymentModal();
                    });



                    // Customer
                    // Customer listeners handled dynamically by updateCustomerUI() -> No, init them here
                    setupCustomerModalListeners();

                    // Discount
                    $('#btn-discount').click(function () {
                        $('#modal-discount').show();
                    });

                    $('#btn-apply-discount').click(function () {
                        const val = parseFloat($('#custom-discount').val());
                        if (!isNaN(val)) {
                            state.discount = val;
                            renderTotals();
                            $('#modal-discount').hide();
                            toastr.success('Discount applied');
                        }
                    });

                    // Held Panel
                    $('#badge-held').click(function () {
                        renderHeldPanel();
                        $('#panel-held').show();
                    });

                    // Shortcuts
                    $('#btn-shortcuts').click(function () {
                        $('#modal-shortcuts').show();
                    });

                    // Fullscreen
                    $('#btn-fullscreen').click(function () {
                        if (!document.fullscreenElement) {
                            document.documentElement.requestFullscreen();
                        } else {
                            document.exitFullscreen();
                        }
                    });

                    // Keydown
                    $(document).keydown(function (e) {
                        if (e.key === 'F1') { e.preventDefault(); $('#btn-checkout').click(); }
                        if (e.key === 'F2') { e.preventDefault(); $('#btn-customer-select').click(); }
                        if (e.key === 'F3') { e.preventDefault(); $('#btn-discount').click(); }
                        if (e.key === 'F4') { e.preventDefault(); $('#badge-held').click(); }
                    });
                });

                // Functions
                function renderTabs() {
                    const html = tabs.map(t => `
                <button class="tab-btn lg:flex-1 px-4 lg:px-6 py-3 lg:py-4 flex items-center justify-center gap-2 transition-colors border-b-2 whitespace-nowrap ${t.id === 'pos' ? 'border-purple-600 text-purple-600 bg-purple-50' : 'border-transparent text-gray-600 hover:bg-gray-50'}"
                    style="min-width: 150px;" data-tab="${t.id}">
                    <i class="bi ${t.icon}"></i>
                    <span class="text-sm lg:text-base font-medium">${t.label}</span>
                </button>
            `).join('');
                    $('#tabs-container').html(html);
                }

                function fetchPosData() {
                    $('#loader').show();
                    $.ajax({
                        url: "{{ route('pos.data') }}",
                        method: 'GET',
                        success: function (response) {
                            if (response.success) {
                                categories = response.categories;
                                products = response.products;

                                renderCategories();
                                renderProducts();
                                toastr.success('Products loaded successfully');
                            } else {
                                toastr.error(response.message || 'Failed to load data');
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr);
                            toastr.error('Error loading POS data. Please refresh.');
                        },
                        complete: function () {
                            $('#loader').hide();
                        }
                    });
                }

                function renderCategories() {
                    const html = categories.map(c => `
                <button
                    class="cat-btn px-4 py-2 rounded-xl text-sm transition-all whitespace-nowrap ${state.selectedCategory === c
                            ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg'
                            : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                        }"
                    data-cat="${c}"
                >
                    ${c}
                </button>
            `).join('');
                    $('#categories-container').html(html);
                }

                function renderProducts() {
                    let p = products;

                    // Filter Category
                    if (state.selectedCategory !== 'All') {
                        p = p.filter(x => x.category === state.selectedCategory);
                    }

                    // Filter Search
                    if (state.searchQuery) {
                        const query = state.searchQuery;
                        p = p.filter(x => x.name.toLowerCase().includes(query) || x.sku.toLowerCase().includes(query));
                    }

                    if (p.length === 0) {
                        $('#products-grid').html(`
                     <div class="col-span-full flex flex-col items-center justify-center h-64 text-gray-500">
                        <i class="bi bi-search text-5xl mb-3 text-gray-300"></i>
                        <p class="text-lg">No products found</p>
                        <p class="text-sm">Try a different search or category</p>
                    </div>
                `);
                        return;
                    }

                    const html = p.map(product => {
                        const isOutOfStock = product.stockLevel === 0;
                        const isLowStock = !isOutOfStock && product.stockLevel <= product.lowStockThreshold;

                        return `
                <div 
                    class="product-card relative w-full bg-white rounded-2xl p-4 border-2 transition-all group ${isOutOfStock
                                ? 'border-gray-200 opacity-60 cursor-not-allowed out-of-stock'
                                : 'border-gray-200 hover:border-purple-400 hover:shadow-lg active:scale-95 cursor-pointer'
                            }"
                    data-id="${product.id}"
                >
                    <!-- Stock Badges -->
                    ${isLowStock ? `
                        <div class="absolute top-2 right-2 z-10">
                            <div class="bg-orange-100 text-orange-700 border border-orange-300 rounded-lg px-2 py-1 text-xs flex items-center gap-1 font-medium">
                                <i class="bi bi-exclamation-triangle-fill text-[10px]"></i> Low
                            </div>
                        </div>
                    ` : ''}
                    
                    ${isOutOfStock ? `
                        <div class="absolute top-2 right-2 z-10">
                            <div class="bg-red-100 text-red-700 border border-red-300 rounded-lg px-2 py-1 text-xs font-bold">
                                Out of Stock
                            </div>
                        </div>
                    ` : ''}

                    <!-- Product Image/Icon -->
                    <div class="w-full aspect-square bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                       ${product.image
                                ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">`
                                : `<i class="bi bi-box-seam text-purple-300 text-4xl group-hover:scale-110 transition-transform"></i>`
                            }
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-1">
                        <h3 class="text-sm text-gray-900 font-medium line-clamp-2 min-h-[2.5rem] leading-snug">
                            ${product.name}
                        </h3>
                        <p class="text-xs text-gray-500">${product.sku}</p>
                        <p class="text-lg font-bold text-purple-600">
                             Rs ${product.sellingPrice.toLocaleString('en-LK', { minimumFractionDigits: 2 })}
                        </p>
                        
                        <p class="text-xs ${isLowStock ? 'text-orange-600 font-medium' : 'text-gray-500'}">
                            ${product.stockLevel} in stock
                        </p>
                    </div>
                </div>
                `;
                    }).join('');
                    $('#products-grid').html(html);
                }

                function addToCart(product) {
                    const existing = state.cart.find(i => i.id === product.id);
                    if (existing) {
                        if (existing.quantity + 1 > product.stockLevel) {
                            toastr.error(`Only ${product.stockLevel} in stock!`);
                            return;
                        }
                        existing.quantity++;
                    } else {
                        state.cart.push({
                            id: product.id,
                            name: product.name,
                            sku: product.sku,
                            price: product.sellingPrice,
                            category: product.category,
                            quantity: 1,
                            stockLevel: product.stockLevel, // Store stock level
                            discount: 0
                        });
                    }
                    renderCart();
                }

                function removeFromCart(index) {
                    state.cart.splice(index, 1);
                    renderCart();
                    toastr.info('Item removed');
                }

                function updateCartQuantity(index, change) {
                    if (!state.cart[index]) return;

                    const item = state.cart[index];
                    const newQty = item.quantity + change;

                    if (newQty <= 0) {
                        removeFromCart(index);
                    } else {
                        if (newQty > item.stockLevel) {
                            toastr.error(`Only ${item.stockLevel} in stock!`);
                            return;
                        }
                        state.cart[index].quantity = newQty;
                        renderCart();
                    }
                }

                function setupCartListeners() {
                    // Remove
                    $(document).off('click', '.btn-remove').on('click', '.btn-remove', function () {
                        const index = $(this).data('index');
                        removeFromCart(index);
                    });

                    // Quantity Buttons
                    $(document).off('click', '.btn-qty-minus').on('click', '.btn-qty-minus', function () {
                        const index = $(this).data('index');
                        updateCartQuantity(index, -1);
                    });

                    $(document).off('click', '.btn-qty-plus').on('click', '.btn-qty-plus', function () {
                        const index = $(this).data('index');
                        updateCartQuantity(index, 1);
                    });

                    // Quantity Input - Real-time validation
                    $(document).off('input', '.input-qty').on('input', '.input-qty', function () {
                        const index = $(this).data('index');
                        const item = state.cart[index];
                        let val = $(this).val();

                        // Allow empty during typing
                        if (val === '') return;

                        let newQty = parseInt(val);

                        if (newQty > item.stockLevel) {
                            toastr.error(`Only ${item.stockLevel} in stock!`);
                            newQty = item.stockLevel;
                            $(this).val(newQty);
                        }

                        // Update state and totals if valid
                        if (!isNaN(newQty) && newQty >= 1) {
                            state.cart[index].quantity = newQty;
                            renderTotals();
                        }
                    });

                    // Quantity Input - Finalize on blur (handle empty/invalid)
                    $(document).off('blur', '.input-qty').on('blur', '.input-qty', function () {
                        const index = $(this).data('index');
                        let val = $(this).val();
                        let newQty = parseInt(val);

                        if (isNaN(newQty) || newQty < 1) {
                            newQty = 1;
                            $(this).val(1);
                            state.cart[index].quantity = newQty;
                            renderTotals();
                        }
                    });
                }

                // Init Cart Listeners
                setupCartListeners();

                function renderCart() {
                    $('#cart-count').text(state.cart.length);

                    if (state.cart.length === 0) {
                        $('#cart-footer').hide();
                        $('#btn-clear-cart').hide();
                        $('#cart-items-container').html(`
                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                        <i class="bi bi-cart text-6xl mb-3 opacity-20"></i>
                        <p class="text-lg text-gray-500">Cart is empty</p>
                        <p class="text-sm text-gray-400">Add items to get started</p>
                    </div>
                `);
                    } else {
                        $('#cart-footer').show();
                        $('#btn-clear-cart').show();

                        const html = state.cart.map((item, index) => `
                    <div class="bg-white border border-gray-200 rounded-xl p-3 hover:shadow-md transition-all mb-3 last:mb-0">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1 min-w-0 mr-2">
                                <h4 class="text-sm text-gray-900 truncate font-medium">${item.name}</h4>
                                <p class="text-xs text-gray-500">${item.sku}</p>
                                <p class="text-sm text-purple-600 mt-1">
                                    Rs ${item.price.toLocaleString('en-LK', { minimumFractionDigits: 2 })}
                                </p>
                            </div>
                            
                            <!-- Remove Button -->
                            <button class="btn-remove w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center transition-colors flex-shrink-0" data-index="${index}">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1">
                                <button class="btn-qty-minus w-8 h-8 rounded-lg bg-white hover:bg-gray-50 text-gray-700 flex items-center justify-center transition-colors active:scale-95 shadow-sm" data-index="${index}">
                                    <i class="bi bi-dash"></i>
                                </button>
                                
                                <input type="number" 
                                    class="input-qty w-12 h-8 text-center text-sm font-semibold bg-white border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 hover:border-purple-300 transition-all no-spinner" 
                                    value="${item.quantity}" 
                                    data-index="${index}"
                                    onclick="this.select()"
                                >
                                
                                <button class="btn-qty-plus w-8 h-8 rounded-lg bg-white hover:bg-gray-50 text-gray-700 flex items-center justify-center transition-colors active:scale-95 shadow-sm" data-index="${index}">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>

                            <!-- Line Total -->
                            <div class="text-right">
                                <p class="text-base text-gray-900 font-bold">
                                    Rs ${(item.price * item.quantity).toLocaleString('en-LK', { minimumFractionDigits: 2 })}
                                </p>
                            </div>
                        </div>
                    </div>
                `).join('');
                        $('#cart-items-container').html(html);
                    }
                    renderTotals();
                }

                function renderTotals() {
                    const subtotal = state.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    const tax = subtotal * 0.0; // 0% Tax
                    const total = (subtotal + tax) - state.discount;

                    $('#val-subtotal').text('Rs ' + subtotal.toLocaleString('en-LK', { minimumFractionDigits: 2 }));
                    $('#val-tax').text('Rs ' + tax.toLocaleString('en-LK', { minimumFractionDigits: 2 }));
                    $('#val-total').text('Rs ' + total.toLocaleString('en-LK', { minimumFractionDigits: 2 }));
                    $('#btn-total').text('Rs ' + total.toLocaleString('en-LK', { minimumFractionDigits: 2 }));

                    if (state.discount > 0) {
                        $('#row-discount').css('display', 'flex');
                        $('#val-discount').text('- Rs ' + state.discount.toLocaleString('en-LK', { minimumFractionDigits: 2 }));
                    } else {
                        $('#row-discount').hide();
                    }
                }

                function openDiscountModal() {
                    // Calculate Current Total (Subtotal + Tax) before Discount
                    const subtotal = state.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    const tax = subtotal * 0.0;
                    const currentTotal = subtotal + tax;

                    if (currentTotal === 0) return toastr.error('Cart is empty');

                    state.discountModal = {
                        type: 'percentage',
                        value: '0',
                        originalAmount: currentTotal
                    };

                    // If already has discount?
                    // Simplified: reset or load existing (requires storing discount type/val in main state, but main state only has total discount amount)
                    // For now, we reset or try to reverse calc if needed. Let's just reset for "New Discount".

                    renderDiscountModal();
                    $('#modal-discount').show();
                }

                function renderDiscountModal() {
                    const { type, value, originalAmount } = state.discountModal;
                    const numVal = parseFloat(value) || 0;
                    let discountAmount = 0;
                    let finalAmount = 0;

                    if (type === 'percentage') {
                        discountAmount = originalAmount * (numVal / 100);
                        $('#btn-dtype-perc').removeClass('bg-gray-100 text-gray-700 hover:bg-gray-200').addClass('bg-orange-600 text-white shadow-lg');
                        $('#btn-dtype-fixed').removeClass('bg-orange-600 text-white shadow-lg').addClass('bg-gray-100 text-gray-700 hover:bg-gray-200');
                        $('#dm-quick-select').show();
                        $('#dm-input-label').text('Discount Percentage:');
                        $('#dm-input-display').text(value + '%');
                    } else {
                        discountAmount = numVal;
                        $('#btn-dtype-fixed').removeClass('bg-gray-100 text-gray-700 hover:bg-gray-200').addClass('bg-orange-600 text-white shadow-lg');
                        $('#btn-dtype-perc').removeClass('bg-orange-600 text-white shadow-lg').addClass('bg-gray-100 text-gray-700 hover:bg-gray-200');
                        $('#dm-quick-select').hide();
                        $('#dm-input-label').text('Discount Amount (Rs):');
                        $('#dm-input-display').text('Rs ' + value);
                    }

                    // Cap Discount
                    if (discountAmount > originalAmount) discountAmount = originalAmount;
                    finalAmount = originalAmount - discountAmount;

                    // Update Summary
                    $('#dm-original-amount').text('Rs ' + originalAmount.toLocaleString('en-LK', { minimumFractionDigits: 2 }));
                    $('#dm-discount-amount').text('- Rs ' + discountAmount.toLocaleString('en-LK', { minimumFractionDigits: 2 }));
                    $('#dm-final-amount').text('Rs ' + finalAmount.toLocaleString('en-LK', { minimumFractionDigits: 2 }));

                    // Approval Logic (>15% AND Percentage type OR equivalent fixed)
                    const effPerc = (discountAmount / originalAmount) * 100;
                    const needsApproval = effPerc > 15;
                    const hasCode = $('#dm-manager-code').val().trim().length > 0;

                    if (needsApproval) {
                        $('#dm-approval-warning').show();
                        $('#dm-input-container').addClass('border-red-300').removeClass('border-gray-200');
                    } else {
                        $('#dm-approval-warning').hide();
                        $('#dm-input-container').removeClass('border-red-300').addClass('border-gray-200');
                    }

                    // Validate Apply Button
                    const isValid = numVal > 0 && discountAmount <= originalAmount;
                    const isApproved = !needsApproval || (needsApproval && hasCode); // Simple check: just need code entered

                    if (isValid && isApproved) {
                        $('#btn-dm-apply').prop('disabled', false).removeClass('bg-gray-200 text-gray-400 cursor-not-allowed').addClass('bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white shadow-lg');
                    } else {
                        $('#btn-dm-apply').prop('disabled', true).addClass('bg-gray-200 text-gray-400 cursor-not-allowed').removeClass('bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white shadow-lg');
                    }

                    if (state.discount > 0) $('#btn-dm-remove').show();
                    else $('#btn-dm-remove').hide();
                }

                function setupDiscountModalListeners() {
                    // Open (Override)
                    $('#btn-discount').off().click(function () {
                        openDiscountModal();
                    });

                    // Type Toggles
                    $('#btn-dtype-perc').click(function () {
                        state.discountModal.type = 'percentage';
                        renderDiscountModal();
                    });
                    $('#btn-dtype-fixed').click(function () {
                        state.discountModal.type = 'fixed';
                        renderDiscountModal();
                    });

                    // Numpad
                    $('.btn-numpad').click(function () {
                        const key = $(this).data('key');
                        let val = state.discountModal.value;
                        if (val === '0') val = '';

                        if (key === 'backspace') {
                            val = val.slice(0, -1);
                        } else if (key === '.') {
                            if (!val.includes('.')) val += '.';
                        } else {
                            val += key;
                        }

                        if (val === '') val = '0';
                        state.discountModal.value = val;
                        renderDiscountModal();
                    });

                    // Quick Select
                    $('.btn-quick-disc').click(function () {
                        state.discountModal.type = 'percentage';
                        state.discountModal.value = $(this).data('val').toString();
                        renderDiscountModal();
                    });

                    // Manager Code Input
                    $('#dm-manager-code').on('input', function () {
                        renderDiscountModal();
                    });

                    // Clear
                    $('#btn-dm-clear').click(function () {
                        state.discountModal.value = '0';
                        $('#dm-manager-code').val('');
                        renderDiscountModal();
                    });

                    // Remove Discount
                    $('#btn-dm-remove').click(function () {
                        state.discount = 0;
                        state.discountType = 0; // Reset discount type
                        renderTotals();
                        $('#modal-discount').hide();
                        toastr.info('Discount removed');
                    });

                    // Apply
                    $('#btn-dm-apply').click(function () {
                        const val = parseFloat(state.discountModal.value);
                        if (isNaN(val) || val <= 0) return;

                        // Calculate Discount
                        const subtotal = state.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                        const tax = subtotal * 0.0; // 0%
                        const currentTotal = subtotal + tax;

                        let discountAmount = 0;
                        if (state.discountModal.type === 'percentage') {
                            discountAmount = (currentTotal * val) / 100;
                            state.discountType = 1;
                        } else {
                            discountAmount = val;
                            state.discountType = 2;
                        }

                        if (discountAmount > currentTotal) {
                            return toastr.error('Discount cannot exceed total amount');
                        }

                        state.discount = discountAmount;
                        renderTotals();
                        $('#modal-discount').hide();
                        toastr.success('Discount applied');
                    });
                }

                function calculateTotal() {
                    const subtotal = state.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    const tax = subtotal * 0.0;
                    return (subtotal + tax) - state.discount;
                }

                // --- Customer Modal Logic ---

                function setupCustomerModalListeners() {
                    // Search
                    $('#customer-search').on('keyup', function () {
                        const query = $(this).val();
                        searchCustomers(query);
                    });

                    // Switch Views
                    $('#btn-show-quick-add').click(function () {
                        $('#view-customer-list').hide();
                        $('#modal-footer-actions').hide();
                        $('#view-quick-add').show();
                        $('#view-quick-add').addClass('flex');
                    });

                    $('#btn-cancel-quick-add').click(function () {
                        $('#view-quick-add').hide();
                        $('#view-quick-add').removeClass('flex');
                        $('#view-customer-list').show();
                        $('#modal-footer-actions').show();
                    });

                    // Quick Add Submit
                    $('#form-quick-add').off('submit').on('submit', function (e) {
                        e.preventDefault();
                        const name = $('#qa-name').val();
                        const phone = $('#qa-phone').val();

                        $.ajax({
                            url: "{{ route('pos.customers.store') }}",
                            method: 'POST',
                            data: {
                                name: name,
                                phone: phone,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                if (response.success) {
                                    toastr.success('Customer added successfully');
                                    // Select the new customer
                                    selectCustomer(response.customer);
                                    // Reset & Close
                                    $('#form-quick-add')[0].reset();
                                    $('#btn-cancel-quick-add').click(); // Switch back to view
                                    $('.btn-close-modal[data-target="modal-customer"]').click(); // Close modal
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function (xhr) {
                                let msg = 'Error adding customer';
                                if (xhr.responseJSON) {
                                    if (xhr.responseJSON.errors) {
                                        // Show first validation error
                                        msg = Object.values(xhr.responseJSON.errors)[0][0];
                                    } else if (xhr.responseJSON.message) {
                                        msg = xhr.responseJSON.message;
                                    }
                                }
                                toastr.error(msg);
                            }
                        });
                    });

                    // Walk-in Select
                    $('#btn-select-walkin').click(function () {
                        state.currentCustomer = null;
                        updateCustomerUI();
                        $('.btn-close-modal[data-target="modal-customer"]').click();
                    });
                }

                function searchCustomers(query = '') {
                    $.ajax({
                        url: "{{ route('pos.customers.search') }}",
                        method: 'POST',
                        data: {
                            query: query,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                renderCustomerList(response.customers);
                            }
                        }
                    });
                }

                function renderCustomerList(list) {
                    const container = $('#customer-list');

                    if (list.length === 0) {
                        container.html(`
                    <div class="flex flex-col items-center justify-center p-6 text-gray-400">
                        <i class="bi bi-search text-3xl mb-2"></i>
                        <p>No customers found</p>
                    </div>
               `);
                        return;
                    }

                    const html = list.map(c => `
                <div class="customer-row p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors"
                     onclick='selectCustomer(${JSON.stringify(c)})'>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">${c.name}</p>
                            <p class="text-xs text-gray-500">${c.phone || 'No Phone'}</p>
                        </div>
                        <div class="text-right">
                             <i class="bi bi-chevron-right text-gray-300"></i>
                        </div>
                    </div>
                </div>
            `).join('');

                    container.html(html);
                }

                function selectCustomer(customer) {
                    state.currentCustomer = customer;
                    updateCustomerUI();
                    $('.btn-close-modal[data-target="modal-customer"]').click();
                    toastr.info(`Customer selected: ${customer.name}`);
                }

                function updateCustomerUI() {
                    const section = $('#customer-section');
                    if (state.currentCustomer) {
                        section.html(`
                    <div class="w-full h-12 bg-purple-50 border border-purple-200 rounded-xl flex items-center justify-between px-3">
                        <div class="flex items-center gap-2 overflow-hidden">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-sm">
                                ${state.currentCustomer.name.charAt(0).toUpperCase()}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">${state.currentCustomer.name}</p>
                                <p class="text-xs text-gray-500 truncate">${state.currentCustomer.phone || ''}</p>
                            </div>
                        </div>
                        <button onclick="$('#modal-customer').show();" class="text-gray-400 hover:text-purple-600 transition-colors">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>
                `);
                    } else {
                        section.html(`
                    <button onclick="$('#modal-customer').show(); searchCustomers('');"
                        class="w-full h-12 bg-gray-50 hover:bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center gap-2 text-gray-600 hover:text-gray-700 transition-colors">
                        <i class="bi bi-person"></i>
                        <span class="text-sm font-medium">Select Customer (Optional)</span>
                    </button>
                `);
                    }
                }

                function openPaymentModal() {
                    const total = calculateTotal();
                    state.paymentModal = {
                        methods: [],
                        selectedType: 'cash',
                        amount: total > 0 ? total.toFixed(2) : '',
                        reference: '',
                        totalToPay: total
                    };

                    // Updates
                    $('#pm-total-display').text('Rs ' + total.toLocaleString('en-LK', { minimumFractionDigits: 2 }));

                    // Customer Info
                    if (state.currentCustomer) {
                        $('#pm-customer-info').show();
                        $('#pm-customer-name').text(state.currentCustomer.name);
                        // Credit (Mocked limit of 5000 for everyone for now)
                        const limit = 5000;
                        const creditAvail = limit - state.currentCustomer.balance;
                        $('#pm-credit-info').text('Credit Available: Rs ' + creditAvail.toLocaleString('en-LK', { minimumFractionDigits: 2 }));
                    } else {
                        $('#pm-customer-info').hide();
                    }

                    // Reset View
                    $('#pm-success-view').hide();
                    $('#pm-main-view').show();

                    renderPaymentModal();
                    $('#modal-payment').show();
                }

                function renderPaymentModal() {
                    const { methods, selectedType, amount, totalToPay } = state.paymentModal;
                    const totalPaid = methods.reduce((acc, m) => acc + m.amount, 0);
                    const remaining = totalToPay - totalPaid;
                    const isComplete = totalPaid >= totalToPay;

                    // 1. Update Type Buttons
                    $('.btn-pm-type').removeClass('bg-green-600 bg-blue-600 bg-purple-600 bg-orange-600 bg-pink-600 text-white shadow-lg').addClass('bg-gray-100 text-gray-700 hover:bg-gray-200');
                    const typeBtn = $(`.btn-pm-type[data-type="${selectedType}"]`);

                    // Color map
                    const colors = { 'cash': 'green', 'card': 'blue', 'mobile': 'purple', 'credit': 'orange', 'gift': 'pink' };
                    const color = colors[selectedType];
                    typeBtn.removeClass('bg-gray-100 text-gray-700 hover:bg-gray-200').addClass(`bg-${color}-600 text-white shadow-lg`);

                    // 2. Update Input Display
                    $('#pm-amount-display').text('Rs ' + (amount || '0.00'));

                    // 3. Dynamic Inputs
                    let dynamicHtml = '';
                    if (selectedType === 'cash') {
                        dynamicHtml = `
                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-2 font-medium">Quick Select:</label>
                    <div class="grid grid-cols-3 gap-2">
                        ${[100, 200, 500, 1000, 2000, 5000].map(amt => `
                            <button class="btn-pm-quick h-10 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg transition-colors text-sm font-medium" data-amt="${amt}">
                                Rs ${amt}
                            </button>
                        `).join('')}
                    </div>
                </div>`;
                    } else {
                        const label = selectedType === 'card' ? 'Last 4 Digits:' :
                            selectedType === 'mobile' ? 'Transaction ID:' :
                                selectedType === 'gift' ? 'Gift Card Code:' : 'Reference:';
                        const placeholder = selectedType === 'card' ? '1234' : 'Optional';

                        dynamicHtml = `
                 <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-2 font-medium">${label}</label>
                    <input type="text" id="pm-ref-input" value="${state.paymentModal.reference}" 
                        class="w-full h-12 px-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="${placeholder}">
                </div>`;
                    }
                    $('#pm-dynamic-inputs').html(dynamicHtml);

                    if (selectedType !== 'cash') {
                        $('#pm-ref-input').on('input', function () { state.paymentModal.reference = $(this).val(); });
                    }
                    $('.btn-pm-quick').off().click(function () {
                        state.paymentModal.amount = $(this).data('amt').toString();
                        renderPaymentModal();
                    });


                    // 4. Exact Remaining Button
                    const remDisplay = remaining > 0 ? remaining : 0;
                    $('#btn-pm-exact').text(`Exact Remaining (Rs ${remDisplay.toLocaleString('en-LK', { minimumFractionDigits: 2 })})`);

                    // 5. Payment List
                    if (methods.length === 0) {
                        $('#pm-list').html(`
                    <div class="text-center py-8 text-gray-400">
                        <i class="bi bi-wallet2 text-3xl mb-2 opacity-50 block"></i>
                        <p class="text-sm">No payments added yet</p>
                    </div>
                 `);
                    } else {
                        const listHtml = methods.map(pm => `
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded flex items-center justify-center bg-gray-100 text-gray-600">
                                <i class="bi bi-${getPaymentIcon(pm.type)}"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900 font-medium capitalize">${pm.type}</p>
                                ${pm.reference ? `<p class="text-xs text-gray-500">Ref: ${pm.reference}</p>` : ''}
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <p class="text-gray-900 font-bold">Rs ${pm.amount.toLocaleString('en-LK', { minimumFractionDigits: 2 })}</p>
                            <button class="btn-pm-remove w-7 h-7 bg-red-50 hover:bg-red-100 text-red-600 rounded flex items-center justify-center transition-colors" data-id="${pm.id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
                        $('#pm-list').html(listHtml);

                        $('.btn-pm-remove').off().click(function () {
                            const id = $(this).data('id');
                            state.paymentModal.methods = state.paymentModal.methods.filter(m => m.id !== id);
                            renderPaymentModal();
                        });
                    }

                    // 6. Summary Totals
                    $('#pm-summ-total').text('Rs ' + totalToPay.toLocaleString('en-LK', { minimumFractionDigits: 2 }));
                    $('#pm-summ-paid').text('Rs ' + totalPaid.toLocaleString('en-LK', { minimumFractionDigits: 2 }));

                    if (remaining > 0) {
                        $('#pm-summ-lbl-rem').text('Remaining:');
                        $('#pm-summ-rem').text('Rs ' + remaining.toLocaleString('en-LK', { minimumFractionDigits: 2 })).removeClass('text-green-600').addClass('text-orange-600');
                        $('#pm-warning-msg').show().text(`Add Rs ${remaining.toLocaleString('en-LK', { minimumFractionDigits: 2 })} more to complete`);
                    } else {
                        $('#pm-summ-lbl-rem').text('Change:');
                        $('#pm-summ-rem').text('Rs ' + Math.abs(remaining).toLocaleString('en-LK', { minimumFractionDigits: 2 })).removeClass('text-orange-600').addClass('text-green-600');
                        $('#pm-warning-msg').hide();
                    }

                    // 7. Complete Button State
                    if (isComplete) {
                        $('#btn-pm-complete').prop('disabled', false).removeClass('bg-gray-200 text-gray-400 cursor-not-allowed').addClass('bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white shadow-lg');
                    } else {
                        $('#btn-pm-complete').prop('disabled', true).addClass('bg-gray-200 text-gray-400 cursor-not-allowed').removeClass('bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white shadow-lg');
                    }

                    // 8. Add Payment Button State
                    const amtVal = parseFloat(amount);
                    if (amtVal > 0) {
                        $('#btn-pm-add').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                    } else {
                        $('#btn-pm-add').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                    }
                }

                function getPaymentIcon(type) {
                    switch (type) {
                        case 'cash': return 'cash';
                        case 'card': return 'credit-card';
                        case 'mobile': return 'phone';
                        case 'credit': return 'person-badge';
                        case 'gift': return 'gift';
                        case 'bank': return 'bank'; // Added bank icon
                        default: return 'wallet2';
                    }
                }

                function setupPaymentModalListeners() {
                    // Type Select
                    $('.btn-pm-type').click(function () {
                        state.paymentModal.selectedType = $(this).data('type');
                        renderPaymentModal();
                    });

                    // Numpad
                    $('.btn-pm-numpad').click(function () {
                        const key = $(this).data('key');
                        let val = state.paymentModal.amount;
                        if (val === '0') val = '';

                        if (key === 'backspace') {
                            val = val.slice(0, -1);
                        } else if (key === '.') {
                            if (!val.includes('.')) val += '.';
                        } else {
                            val += key;
                        }

                        // Allow empty string for backspace clearing
                        state.paymentModal.amount = val;
                        renderPaymentModal();
                    });

                    // Exact Remaining
                    $('#btn-pm-exact').click(function () {
                        const totalPaid = state.paymentModal.methods.reduce((acc, m) => acc + m.amount, 0);
                        const remaining = state.paymentModal.totalToPay - totalPaid;
                        if (remaining > 0) {
                            state.paymentModal.amount = remaining.toFixed(2);
                            renderPaymentModal();
                        }
                    });

                    // Clear Input
                    $('#btn-pm-clear').click(function () {
                        state.paymentModal.amount = '';
                        // state.paymentModal.reference = ''; // No longer directly stored in state.paymentModal.reference
                        $('#pm-input-card').val('');
                        $('#pm-input-ref').val('');
                        renderPaymentModal();
                    });

                    // Add Payment
                    $('#btn-pm-add').click(function () {
                        const amount = parseFloat(state.paymentModal.amount);
                        const typeStr = state.paymentModal.selectedType;

                        if (!amount || amount <= 0) return;

                        // Credit Check
                        if (typeStr === 'credit') {
                            if (!state.currentCustomer) return toastr.error('Select a customer for credit');
                            // Mock check
                            const limit = 5000;
                            const avail = limit - state.currentCustomer.balance;
                            if (amount > avail) return toastr.error(`Insufficient credit. Available: ${avail}`);
                        }

                        // Prevent overpayment unless cash (logic from react)
                        const totalPaid = state.paymentModal.methods.reduce((acc, m) => acc + m.amount, 0);
                        if (totalPaid + amount > state.paymentModal.totalToPay && typeStr !== 'cash') {
                            return toastr.error('Overpayment only allowed with Cash');
                        }

                        // Capture Extra Fields
                        let extraData = {};
                        if (typeStr === 'card') {
                            const digits = $('#pm-input-card').val();
                            if (digits) extraData.card_4_digits = digits;
                        } else if (['mobile', 'bank', 'credit', 'gift'].includes(typeStr)) { // Grouped other reference types
                            const ref = $('#pm-input-ref').val();
                            if (ref) extraData.reference = ref;
                        }

                        // Map Type to Integrity (CommonVariables)
                        // cash=1, card=2, bank/online=3, credit=4, gift=5
                        let typeInt = 1; // Default to cash
                        if (typeStr === 'card') typeInt = 2;
                        else if (['mobile', 'bank'].includes(typeStr)) typeInt = 3;
                        else if (typeStr === 'credit') typeInt = 4;
                        else if (typeStr === 'gift') typeInt = 5;


                        state.paymentModal.methods.push({
                            id: Date.now(),
                            type: typeInt, // Send Integer!
                            typeName: typeStr, // Keep for UI display if needed
                            amount: amount,
                            ...extraData
                        });

                        // Clear Inputs
                        state.paymentModal.amount = '';
                        $('#pm-input-card').val('');
                        $('#pm-input-ref').val('');
                        // Auto-calc remaining next? renderPaymentModal does it.
                        renderPaymentModal();
                    });

                    // Complete Sale
                    $('#btn-pm-complete').click(function () {
                        // Gather Data
                        const subtotal = state.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                        const tax = subtotal * 0.0; // 0% as per update
                        const total = (subtotal + tax) - state.discount;

                        const payload = {
                            cart: state.cart.map(i => ({
                                id: i.id,
                                quantity: i.quantity,
                                price: i.price
                            })),
                            paymentMethods: state.paymentModal.methods,
                            totals: {
                                subtotal: subtotal,
                                tax: tax,
                                total: total,
                                discount: state.discount,
                                discountType: state.discountType
                            },
                            customerId: state.currentCustomer ? state.currentCustomer.id : null,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        };

                        // Disable button to prevent double submit
                        $(this).prop('disabled', true).text('Processing...');

                        $.ajax({
                            url: "{{ route('pos.store') }}",
                            method: 'POST',
                            data: payload,
                            success: function (response) {
                                if (response.success) {
                                    $('#pm-main-view').hide();
                                    $('#pm-success-view').show();

                                    // Function to reset and close
                                    const resetAndClose = () => {
                                        $('#modal-payment').hide();
                                        // Reset EVERYTHING
                                        state.cart = [];
                                        state.currentCustomer = null;
                                        state.discount = 0;
                                        state.discountType = 1;
                                        state.paymentModal.methods = [];
                                        state.selectedCategory = 'All';
                                        $('#customer-search').val('');

                                        renderCart();

                                        // Reset Payment Modal UI
                                        $('#pm-success-view').hide();
                                        $('#pm-main-view').show();
                                        $('#btn-pm-complete').prop('disabled', false).text('Complete Sale');

                                        // Refresh Data
                                        updateCustomerUI();
                                        renderTotals();
                                        renderProductGrid();
                                        fetchPosData();
                                    };

                                    // Print Receipt Logic
                                    const invoiceId = response.invoice.id;
                                    $('#btn-pm-print-receipt').off().click(function () {
                                        const url = "{{ route('pos.receipt', ':id') }}".replace(':id', invoiceId);
                                        const iframe = document.getElementById('receipt-print-frame');
                                        if (iframe) iframe.src = url;

                                        setTimeout(resetAndClose, 500);
                                    });

                                    // Close Button Logic
                                    $('#btn-pm-close-success').off().click(function () {
                                        resetAndClose();
                                    });

                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Transaction completed successfully',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });

                                    // Reset button state for next time (though modal is hidden)
                                    $('#btn-pm-complete').text('Complete Sale');
                                } else {
                                    toastr.error(response.message || 'Transaction failed');
                                    $('#btn-pm-complete').prop('disabled', false).text('Complete Sale');
                                }
                            },
                            error: function (xhr) {
                                let msg = 'Transaction failed';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                toastr.error(msg);
                                $('#btn-pm-complete').prop('disabled', false).text('Complete Sale');
                            }
                        });
                    });
                }



                function updateHeldBadge() {
                    const count = state.heldTransactions.length;
                    $('#count-held').text(count);
                    if (count > 0) $('#badge-held').show();
                    else $('#badge-held').hide();
                }

                // --- Held Transactions Panel Logic ---

                function openHeldModal() {
                    state.heldPanel = { searchQuery: '', selectedId: null };
                    $('#held-search').val('');
                    renderHeldPanel();
                    $('#modal-held').show();
                }

                function renderHeldPanel() {
                    const listContainer = $('#held-list-container');
                    const query = state.heldPanel.searchQuery.toLowerCase();

                    // Filter
                    const list = state.heldTransactions.filter(t =>
                        (t.reference || '').toLowerCase().includes(query) ||
                        t.id.toString().includes(query)
                    ).sort((a, b) => b.id - a.id); // Newest first

                    $('#held-count-lbl').text(`${state.heldTransactions.length} transaction${state.heldTransactions.length !== 1 ? 's' : ''} on hold`);

                    if (list.length === 0) {
                        listContainer.html(`
                    <div class="flex flex-col items-center justify-center h-full text-gray-500 p-6">
                        <i class="bi bi-${state.heldTransactions.length === 0 ? 'clock' : 'search'} text-4xl mb-3 text-gray-300"></i>
                         <p>${state.heldTransactions.length === 0 ? 'No held transactions' : 'No matching transactions'}</p>
                    </div>
                 `);
                    } else {
                        const html = list.map(t => {
                            const isSelected = state.heldPanel.selectedId === t.id;
                            const timeAgo = getTimeAgo(t.id); // utilizing ID as timestamp
                            const ageColor = getAgeColor(t.id);

                            return `
                    <button class="w-full p-4 text-left hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0 ${isSelected ? 'bg-orange-50 border-l-4 border-l-orange-600' : ''}"
                        onclick="selectHeldTransaction(${t.id})">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="text-gray-900 font-medium">${t.reference || 'Unknown'}</p>
                                <p class="text-xs text-gray-500 mt-0.5">ID: ${t.id}</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-lg ${ageColor}">${timeAgo}</span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">${t.items.length} items</span>
                            <span class="text-orange-600 font-bold">Rs ${t.total.toLocaleString('en-LK', { minimumFractionDigits: 2 })}</span>
                        </div>
                    </button>
                    `;
                        }).join('');
                        listContainer.html(html);
                    }

                    renderHeldDetails();
                }

                function selectHeldTransaction(id) {
                    state.heldPanel.selectedId = id;
                    renderHeldPanel(); // Re-render list to update selection state
                }

                function renderHeldDetails() {
                    const id = state.heldPanel.selectedId;
                    if (!id) {
                        $('#held-details-empty').show();
                        $('#held-details-content').hide();
                        return;
                    }

                    const txn = state.heldTransactions.find(t => t.id === id);
                    if (!txn) {
                        // Should not happen, but reset if it does
                        state.heldPanel.selectedId = null;
                        renderHeldPanel();
                        return;
                    }

                    $('#held-details-empty').hide();
                    $('#held-details-content').css('display', 'flex'); // Ensure flex

                    // Bind Data
                    $('#hd-reference').text(txn.reference || 'No Reference');
                    $('#hd-time-ago').text(getTimeAgo(txn.id));
                    $('#hd-time-abs').text(txn.time);
                    $('#hd-id').text(txn.id);

                    $('#hd-item-count').text(`Items (${txn.items.length}):`);

                    const itemsHtml = txn.items.map(item => `
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <div class="flex items-start justify-between mb-1">
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 font-medium">${item.name}</p>
                            <p class="text-xs text-gray-500">${item.category || ''}</p>
                        </div>
                        <p class="text-sm text-gray-900 font-bold">Rs ${(item.price * item.quantity).toLocaleString('en-LK', { minimumFractionDigits: 2 })}</p>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>Qty: ${item.quantity} Ã— Rs ${item.price.toFixed(2)}</span>
                    </div>
                </div>
            `).join('');
                    $('#hd-items-list').html(itemsHtml);

                    // Totals
                    const subtotal = txn.total / 1.0; // Roughly reverse tax
                    const tax = txn.total - subtotal;

                    $('#hd-subtotal').text('Rs ' + subtotal.toLocaleString('en-LK', { minimumFractionDigits: 2 }));
                    $('#hd-tax').text('Rs ' + tax.toLocaleString('en-LK', { minimumFractionDigits: 2 }));
                    $('#hd-total').text('Rs ' + txn.total.toLocaleString('en-LK', { minimumFractionDigits: 2 }));

                    // Actions
                    $('#btn-hd-delete').off().click(() => {
                        if (confirm('Delete this held transaction?')) {
                            state.heldTransactions = state.heldTransactions.filter(t => t.id !== id);
                            state.heldPanel.selectedId = null;
                            renderHeldPanel();
                            updateHeldBadge();
                            toastr.info('Transaction deleted');
                        }
                    });

                    $('#btn-hd-recall').off().click(() => {
                        if (state.cart.length > 0 && !confirm('Current cart is not empty. Overwrite?')) return;

                        state.cart = [...txn.items]; // Clone items
                        state.discount = 0; // Reset discount on recall, or store it? Simple for now.
                        state.heldTransactions = state.heldTransactions.filter(t => t.id !== id);
                        state.heldPanel.selectedId = null;

                        // Close Modal
                        $('#modal-held').hide();

                        // Update Main UI
                        renderCart();
                        renderTotals();
                        updateHeldBadge();
                        toastr.success('Transaction recalled to cart');
                    });
                }

                // Helpers
                function getTimeAgo(timestamp) {
                    const diff = Date.now() - timestamp;
                    const minutes = Math.floor(diff / 60000);
                    if (minutes < 1) return 'Just now';
                    if (minutes < 60) return `${minutes}m ago`;
                    const hours = Math.floor(minutes / 60);
                    if (hours < 24) return `${hours}h ago`;
                    return '1d+ ago';
                }

                function getAgeColor(timestamp) {
                    const diff = Date.now() - timestamp;
                    const hours = diff / 3600000;
                    if (hours < 1) return 'text-green-600 bg-green-50';
                    if (hours < 4) return 'text-yellow-600 bg-yellow-50';
                    return 'text-red-600 bg-red-50';
                }

                // Listeners for Held Panel
                function setupHeldPanelListeners() {
                    // Open (Fixing the button issue) - attaching to doc in case of dynamic render, 
                    // though button is usually static. 
                    $(document).on('click', '#badge-held', function () {
                        openHeldModal();
                    });

                    // Search
                    $('#held-search').on('keyup', function () {
                        state.heldPanel.searchQuery = $(this).val();
                        renderHeldPanel();
                    });
                }

                // Call this in init
                setupHeldPanelListeners();
            </script>

</body>

</html>