<!-- Product Grid Area (Left) -->
<div class="flex-1 flex flex-col h-full bg-slate-50 relative overflow-hidden">
    <!-- Loading State -->
    <div id="loader" style="display: none;" class="absolute inset-0 flex items-center justify-center bg-white/50 z-50">
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
            <i class="bi bi-upc-scan absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
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
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="products-grid">
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
            <span class="text-gray-600">Tax (8%):</span>
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