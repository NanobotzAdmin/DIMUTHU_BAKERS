{{-- resources/views/modals/create-invoice.blade.php --}}

@php
    // --- DUMMY DATA FOR INITIAL RENDER ---
    $outlets = [
        ['id' => 'loc1', 'code' => 'MGM', 'name' => 'Maharagama'],
        ['id' => 'loc2', 'code' => 'NGE', 'name' => 'Nugegoda'],
    ];
@endphp

<div id="create-invoice-modal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300" onclick="closeCreateInvoiceModal()"></div>

    {{-- Sliding Panel --}}
    <div id="create-invoice-panel" class="absolute top-0 right-0 h-full w-full max-w-2xl bg-white shadow-2xl overflow-hidden flex flex-col transform transition-transform duration-300 translate-x-full">
        
        {{-- Header & Progress --}}
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 flex-shrink-0">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Create Invoice</h2>
                <button onclick="closeCreateInvoiceModal()" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 18 18"/></svg>
                </button>
            </div>

            {{-- Progress Bar --}}
            <div class="flex items-center justify-between px-2">
                @foreach([
                    1 => ['label' => 'Type', 'icon' => 'file'],
                    2 => ['label' => 'Customer', 'icon' => 'users'],
                    3 => ['label' => 'Products', 'icon' => 'package'],
                    4 => ['label' => 'Terms', 'icon' => 'percent'],
                    5 => ['label' => 'Review', 'icon' => 'check']
                ] as $stepNum => $step)
                    <div class="inv-step-indicator flex flex-col items-center relative z-10" id="inv-step-{{ $stepNum }}">
                        <div class="step-circle w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 bg-gray-200 text-gray-400 border-2 border-transparent">
                            <i class="step-icon" data-icon="{{ $step['icon'] }}"></i>
                        </div>
                        <span class="step-label mt-2 text-xs font-medium text-gray-400 transition-colors duration-300">{{ $step['label'] }}</span>
                    </div>
                    @if($stepNum < 5)
                        <div class="inv-step-connector flex-1 h-1 bg-gray-200 -mt-6 mx-2 rounded-full transition-colors duration-300" id="inv-conn-{{ $stepNum }}"></div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Content Area --}}
        <div class="flex-1 overflow-y-auto p-6" id="invoice-wizard-content">
            
            {{-- STEP 1: TYPE --}}
            <div id="inv-step-content-1" class="wizard-step">
                <h2 class="text-2xl text-gray-900 mb-2 font-bold">Select Invoice Type</h2>
                <p class="text-gray-600 mb-6">Choose how you want to create this invoice</p>

                <div class="space-y-3">
                    <button onclick="setInvoiceType('direct-sale')" id="btn-type-direct-sale" class="type-btn w-full p-5 rounded-2xl border-2 transition-all duration-300 text-left border-blue-300 bg-blue-50 shadow-md">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg text-gray-900 mb-1 font-semibold">Direct Sale</h3>
                                <p class="text-sm text-gray-600">Create invoice for walk-in or direct sales</p>
                            </div>
                        </div>
                    </button>

                    <button onclick="setInvoiceType('order-based')" id="btn-type-order-based" class="type-btn w-full p-5 rounded-2xl border-2 transition-all duration-300 text-left border-gray-200 bg-white hover:border-gray-300">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-purple-500 to-purple-600 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg text-gray-900 mb-1 font-semibold">From Order</h3>
                                <p class="text-sm text-gray-600">Convert existing confirmed order to invoice</p>
                            </div>
                        </div>
                    </button>

                    <button onclick="setInvoiceType('quotation-based')" id="btn-type-quotation-based" class="type-btn w-full p-5 rounded-2xl border-2 transition-all duration-300 text-left border-gray-200 bg-white hover:border-gray-300">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-green-500 to-green-600 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" x2="12" y1="6" y2="18"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg text-gray-900 mb-1 font-semibold">From Quotation</h3>
                                <p class="text-sm text-gray-600">Convert approved quotation to invoice</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            {{-- STEP 2a: LINK (Hidden by default) --}}
            <div id="inv-step-content-2a" class="wizard-step hidden">
                <h2 class="text-2xl text-gray-900 mb-2 font-bold" id="link-step-title">Select Source</h2>
                <div class="relative mb-4">
                    <input type="text" id="link-search" onkeyup="renderLinks()" placeholder="Search..." class="w-full h-12 pl-4 pr-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                </div>
                <div id="link-list" class="space-y-3 max-h-96 overflow-y-auto"></div>
            </div>

            {{-- STEP 2: CUSTOMER --}}
            <div id="inv-step-content-2" class="wizard-step hidden">
                <h2 class="text-2xl text-gray-900 mb-2 font-bold">Customer & Outlet</h2>
                
                {{-- Selected Customer Card (Hidden initially) --}}
                <div id="selected-customer-card" class="hidden bg-blue-50 rounded-2xl p-5 border-2 border-blue-200 mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900" id="sel-cust-name"></h3>
                            <p class="text-sm text-gray-600" id="sel-cust-phone"></p>
                        </div>
                        <button onclick="resetCustomer()" class="text-sm text-blue-600 font-medium">Change</button>
                    </div>
                </div>

                <div id="customer-search-container">
                    <label class="block text-gray-700 mb-2">Search Customer</label>
                    <input type="text" id="cust-search" onkeyup="renderCustomers()" placeholder="Search by name or phone..." class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl mb-4 focus:outline-none focus:border-blue-500">
                    <div id="customer-list" class="space-y-2 max-h-60 overflow-y-auto"></div>
                </div>

                <div class="mt-6">
                    <label class="block text-gray-700 mb-2">Select Outlet <span class="text-red-500">*</span></label>
                    <select id="inv-outlet" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                        <option value="">Choose outlet...</option>
                        @foreach($outlets as $outlet)
                            <option value="{{ $outlet['id'] }}">{{ $outlet['code'] }} - {{ $outlet['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- STEP 3: PRODUCTS --}}
            <div id="inv-step-content-3" class="wizard-step hidden">
                <h2 class="text-2xl text-gray-900 mb-2 font-bold">Products</h2>
                
                {{-- Cart --}}
                <div id="inv-cart-container" class="bg-gray-50 rounded-2xl p-4 border-2 border-gray-200 mb-6 hidden">
                    <div id="inv-cart-list" class="space-y-2 max-h-60 overflow-y-auto"></div>
                    <div class="mt-4 pt-4 border-t border-gray-300 flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span id="inv-cart-total" class="text-blue-600">Rs 0.00</span>
                    </div>
                </div>

                <div id="inv-empty-cart" class="text-center py-8 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 mb-6">
                    <p class="text-gray-500">No items added yet</p>
                </div>

                {{-- Product Search (Only if not linked) --}}
                <div id="product-search-section">
                    <div class="relative mb-4">
                        <input type="text" id="inv-prod-search" onkeyup="renderInvProducts()" placeholder="Search products..." class="w-full h-12 pl-4 pr-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-blue-500">
                    </div>
                    <div id="inv-product-list" class="space-y-2 max-h-60 overflow-y-auto"></div>
                </div>
            </div>

            {{-- STEP 4: TERMS --}}
            <div id="inv-step-content-4" class="wizard-step hidden">
                <h2 class="text-2xl text-gray-900 mb-4 font-bold">Terms & Details</h2>
                
                <div class="bg-gray-50 p-4 rounded-xl mb-6 flex justify-between items-center">
                    <span class="text-gray-700 font-medium">Line Items Total:</span>
                    <span id="term-line-total" class="text-xl font-bold text-gray-900">Rs 0.00</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 mb-1 text-sm">Invoice Date</label>
                        <input type="date" id="inv-date" class="w-full h-10 px-3 border-2 border-gray-200 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-1 text-sm">Payment Terms</label>
                        <select id="inv-terms" onchange="updateDueDate()" class="w-full h-10 px-3 border-2 border-gray-200 rounded-lg">
                            <option value="0">Immediate</option>
                            <option value="14" selected>Net 14</option>
                            <option value="30">Net 30</option>
                        </select>
                    </div>
                </div>

                {{-- Discount --}}
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2 text-sm font-bold">Discount</label>
                    <div class="flex gap-2 mb-2">
                        <button onclick="setDiscType('percentage')" id="btn-disc-percent" class="flex-1 h-10 border-2 border-blue-500 bg-blue-50 text-blue-700 rounded-lg text-sm">Percentage %</button>
                        <button onclick="setDiscType('fixed')" id="btn-disc-fixed" class="flex-1 h-10 border-2 border-gray-200 bg-white text-gray-600 rounded-lg text-sm">Fixed Amount</button>
                    </div>
                    <div class="flex gap-2">
                        <input type="number" id="inv-disc-val" oninput="calcTotals()" placeholder="0" class="flex-1 h-10 px-3 border-2 border-gray-200 rounded-lg">
                        <div id="disc-approval-badge" class="hidden px-3 py-2 bg-orange-100 text-orange-700 text-xs rounded-lg flex items-center">Approval Req.</div>
                    </div>
                </div>

                {{-- Tax --}}
                <div class="flex items-center justify-between mb-4">
                    <label class="text-gray-700">Enable Tax (VAT 5%)</label>
                    <input type="checkbox" id="inv-tax-check" onchange="calcTotals()" class="w-5 h-5 text-blue-600">
                </div>

                {{-- Summary Preview --}}
                <div class="bg-blue-50 p-4 rounded-xl space-y-2">
                    <div class="flex justify-between text-sm"><span>Subtotal</span><span id="prev-subtotal">0.00</span></div>
                    <div class="flex justify-between text-sm text-red-600"><span>Discount</span><span id="prev-discount">-0.00</span></div>
                    <div class="flex justify-between text-sm text-green-600"><span>Tax</span><span id="prev-tax">+0.00</span></div>
                    <div class="flex justify-between font-bold text-lg border-t border-blue-200 pt-2"><span>Grand Total</span><span id="prev-grand">0.00</span></div>
                </div>
            </div>

            {{-- STEP 5: REVIEW --}}
            <div id="step-content-5" class="wizard-step hidden">
                <h2 class="text-2xl text-gray-900 mb-4 font-bold">Review Invoice</h2>
                
                <div class="bg-white border-2 border-gray-200 rounded-xl p-5 mb-4">
                    <div class="flex justify-between mb-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Customer</p>
                            <p class="font-bold text-gray-900" id="rev-cust-name"></p>
                            <p class="text-sm text-gray-600" id="rev-cust-phone"></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase">Type</p>
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold uppercase" id="rev-type"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                        <div><p class="text-xs text-gray-500">Date</p><p class="font-medium" id="rev-date"></p></div>
                        <div><p class="text-xs text-gray-500">Due Date</p><p class="font-medium" id="rev-due"></p></div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-5">
                    <h3 class="font-bold text-gray-700 mb-3">Financials</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm"><span>Subtotal</span><span id="rev-sub"></span></div>
                        <div class="flex justify-between text-sm text-red-600"><span>Discount</span><span id="rev-disc"></span></div>
                        <div class="flex justify-between text-sm text-green-600"><span>Tax</span><span id="rev-tax"></span></div>
                        <div class="flex justify-between text-xl font-bold pt-2 border-t border-gray-200"><span>Grand Total</span><span id="rev-grand" class="text-blue-700"></span></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 border-t-2 border-gray-200 p-6 flex items-center justify-between flex-shrink-0">
            <button onclick="prevInvStep()" id="btn-inv-back" class="h-12 px-6 rounded-xl flex items-center gap-2 bg-gray-200 text-gray-400 cursor-not-allowed" disabled>Back</button>
            <button onclick="nextInvStep()" id="btn-inv-next" class="h-12 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl shadow-lg">Next</button>
            <button onclick="submitInvoice()" id="btn-inv-submit" class="hidden h-12 px-8 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-xl shadow-lg">Create Invoice</button>
        </div>
    </div>
</div>

<script>
    // --- ICONS (SVG Strings) ---
    const invIcons = {
        file: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>`,
        users: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
        package: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>`,
        percent: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" x2="5" y1="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>`,
        check: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`
    };

    // --- MOCK DATA ---
    const invData = {
        customers: [
            { id: 'c1', name: 'Nimal Perera', phone: '0771234567', type: 'individual' },
            { id: 'c2', name: 'Fab Foods Ltd', phone: '0112345678', type: 'business' }
        ],
        products: [
            { id: 'p1', name: 'Chocolate Cake', price: 1500 },
            { id: 'p2', name: 'Vanilla Cupcake', price: 150 },
            { id: 'p3', name: 'Chicken Bun', price: 100 }
        ],
        orders: [
            { id: 'o1', number: 'ORD-001', customer: 'Nimal Perera', total: 1650, items: [{id:'p1', name:'Chocolate Cake', price:1500, qty:1}, {id:'p2', name:'Vanilla Cupcake', price:150, qty:1}] },
            { id: 'o2', number: 'ORD-002', customer: 'Fab Foods Ltd', total: 5000, items: [{id:'p1', name:'Chocolate Cake', price:1500, qty:3}, {id:'p3', name:'Chicken Bun', price:100, qty:5}] }
        ],
        quotations: [
            { id: 'q1', number: 'QT-001', customer: 'Fab Foods Ltd', total: 10000, items: [{id:'p1', name:'Chocolate Cake', price:1500, qty:5}, {id:'p2', name:'Vanilla Cupcake', price:150, qty:10}] }
        ]
    };

    // --- STATE ---
    let invState = {
        step: 1,
        type: 'direct-sale',
        linkId: null, // Order/Quote ID
        customer: null,
        outletId: '',
        items: [],
        terms: {
            date: new Date().toISOString().split('T')[0],
            paymentDays: 14,
            discType: 'percentage',
            discVal: 0,
            taxEnabled: false,
            taxRate: 5
        }
    };

    // --- INITIALIZATION ---
    document.addEventListener('DOMContentLoaded', () => {
        // Inject icons
        document.querySelectorAll('.step-icon').forEach(el => {
            el.innerHTML = invIcons[el.dataset.icon];
        });
    });

    // --- PANEL LOGIC ---
    function openCreateInvoiceModal() {
        // Reset state
        invState = {
            step: 1,
            type: 'direct-sale',
            linkId: null,
            customer: null,
            outletId: '',
            items: [],
            terms: { date: new Date().toISOString().split('T')[0], paymentDays: 14, discType: 'percentage', discVal: 0, taxEnabled: false, taxRate: 5 }
        };
        
        // Reset DOM elements
        document.getElementById('inv-date').value = invState.terms.date;
        document.getElementById('inv-disc-val').value = '';
        document.getElementById('inv-tax-check').checked = false;
        document.getElementById('inv-outlet').value = '';
        document.getElementById('cust-search').value = '';
        document.getElementById('link-search').value = '';
        resetCustomer();
        
        setInvoiceType('direct-sale');
        updateInvStepUI();

        document.getElementById('create-invoice-modal').classList.remove('hidden');
        setTimeout(() => document.getElementById('create-invoice-panel').classList.remove('translate-x-full'), 10);
    }

    function closeCreateInvoiceModal() {
        document.getElementById('create-invoice-panel').classList.add('translate-x-full');
        setTimeout(() => document.getElementById('create-invoice-modal').classList.add('hidden'), 300);
    }

    // --- WIZARD NAVIGATION ---
    function nextInvStep() {
        if (!validateInvStep()) return;

        // Skip logic for Link/Customer steps
        if (invState.step === 1) {
            if (invState.type === 'direct-sale') {
                invState.step = 2; // Go to Customer
            } else {
                invState.step = 2.1; // Go to Link Selection (custom internal step)
                renderLinks();
            }
        } else if (invState.step === 2.1) {
            invState.step = 2; // Go to Customer
        } else if (invState.step === 2) {
            invState.step = 3;
        } else if (invState.step < 5) {
            invState.step++;
        }

        // Calculation trigger on Step 4 entry
        if (Math.floor(invState.step) === 4) calcTotals();
        
        // Review trigger on Step 5 entry
        if (Math.floor(invState.step) === 5) populateInvReview();

        updateInvStepUI();
    }

    function prevInvStep() {
        if (invState.step === 2.1) {
            invState.step = 1;
        } else if (invState.step === 2) {
            if (invState.type === 'direct-sale') invState.step = 1;
            else invState.step = 2.1;
        } else if (invState.step > 1) {
            invState.step--;
        }
        updateInvStepUI();
    }

    function updateInvStepUI() {
        // Hide all steps
        document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('hidden'));

        // Show current
        let contentId = `inv-step-content-${Math.floor(invState.step)}`;
        if (invState.step === 2.1) contentId = 'inv-step-content-2a';
        document.getElementById(contentId).classList.remove('hidden');

        // Progress Bar (Visual logic mapping 2.1 to 2)
        const uiStep = Math.floor(invState.step);
        for (let i = 1; i <= 5; i++) {
            const circle = document.querySelector(`#inv-step-${i} .step-circle`);
            const label = document.querySelector(`#inv-step-${i} .step-label`);
            const conn = document.getElementById(`inv-conn-${i}`);

            circle.className = 'step-circle w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 border-2 border-transparent';
            label.className = 'step-label mt-2 text-xs font-medium transition-colors duration-300';

            if (i === uiStep) {
                circle.classList.add('bg-gradient-to-br', 'from-blue-600', 'to-indigo-600', 'text-white', 'shadow-lg', 'scale-110');
                label.classList.add('text-blue-600', 'font-bold');
            } else if (i < uiStep) {
                circle.classList.add('bg-green-500', 'text-white');
                label.classList.add('text-green-600');
            } else {
                circle.classList.add('bg-gray-200', 'text-gray-400');
                label.classList.add('text-gray-400');
            }

            if (conn) {
                conn.className = `inv-step-connector flex-1 h-1 bg-gray-200 -mt-6 mx-2 rounded-full transition-colors duration-300 ${i < uiStep ? 'bg-green-500' : 'bg-gray-200'}`;
            }
        }

        // Buttons
        const backBtn = document.getElementById('btn-inv-back');
        const nextBtn = document.getElementById('btn-inv-next');
        const submitBtn = document.getElementById('btn-inv-submit');

        if (invState.step === 1) {
            backBtn.disabled = true;
            backBtn.classList.add('bg-gray-200', 'text-gray-400');
            backBtn.classList.remove('bg-white', 'text-gray-700', 'hover:bg-gray-50');
        } else {
            backBtn.disabled = false;
            backBtn.classList.remove('bg-gray-200', 'text-gray-400');
            backBtn.classList.add('bg-white', 'text-gray-700', 'hover:bg-gray-50');
        }

        if (invState.step === 5) {
            nextBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
        } else {
            nextBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
        }
    }

    function validateInvStep() {
        if (invState.step === 2.1) {
            if (!invState.linkId) { alert('Please select a source document'); return false; }
        }
        if (invState.step === 2) {
            if (!invState.customer) { alert('Please select a customer'); return false; }
            const outlet = document.getElementById('inv-outlet').value;
            if (!outlet) { alert('Please select an outlet'); return false; }
            invState.outletId = outlet;
        }
        if (invState.step === 3) {
            if (invState.items.length === 0) { alert('Please add at least one product'); return false; }
        }
        return true;
    }

    // --- STEP 1: TYPE ---
    function setInvoiceType(type) {
        invState.type = type;
        document.querySelectorAll('.type-btn').forEach(btn => btn.classList.replace('border-blue-300', 'border-gray-200'));
        document.querySelectorAll('.type-btn').forEach(btn => btn.classList.replace('bg-blue-50', 'bg-white'));
        
        const active = document.getElementById(`btn-type-${type}`);
        active.classList.replace('border-gray-200', 'border-blue-300');
        active.classList.replace('bg-white', 'bg-blue-50');
    }

    // --- STEP 2a: LINK ---
    function renderLinks() {
        const query = document.getElementById('link-search').value.toLowerCase();
        const list = document.getElementById('link-list');
        const src = invState.type === 'order-based' ? invData.orders : invData.quotations;
        
        const title = invState.type === 'order-based' ? 'Select Order' : 'Select Quotation';
        document.getElementById('link-step-title').innerText = title;

        const filtered = src.filter(i => i.number.toLowerCase().includes(query) || i.customer.toLowerCase().includes(query));
        
        list.innerHTML = filtered.map(i => `
            <div onclick="selectLink('${i.id}')" class="p-4 rounded-xl border-2 cursor-pointer transition-all ${invState.linkId === i.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-gray-300'}">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-bold text-gray-900">${i.number}</div>
                        <div class="text-sm text-gray-600">${i.customer}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold">Rs ${i.total}</div>
                        <div class="text-xs text-gray-500">${i.items.length} items</div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function selectLink(id) {
        invState.linkId = id;
        renderLinks();
        
        // Auto-fill data
        const src = invState.type === 'order-based' ? invData.orders : invData.quotations;
        const item = src.find(i => i.id === id);
        
        // Find customer mock
        const cust = invData.customers.find(c => c.name === item.customer);
        if(cust) selectCustomer(cust.id);

        // Map items
        invState.items = item.items.map(i => ({...i, total: i.price * i.qty}));
    }

    // --- STEP 2: CUSTOMER ---
    function renderCustomers() {
        const query = document.getElementById('cust-search').value.toLowerCase();
        const list = document.getElementById('customer-list');
        const filtered = invData.customers.filter(c => c.name.toLowerCase().includes(query) || c.phone.includes(query));
        
        if (query.length === 0) { list.innerHTML = ''; return; }

        list.innerHTML = filtered.map(c => `
            <div onclick="selectCustomer('${c.id}')" class="p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-blue-50">
                <div class="font-medium">${c.name}</div>
                <div class="text-xs text-gray-500">${c.phone}</div>
            </div>
        `).join('');
    }

    function selectCustomer(id) {
        const cust = invData.customers.find(c => c.id === id);
        invState.customer = cust;
        
        // UI
        document.getElementById('customer-search-container').classList.add('hidden');
        document.getElementById('selected-customer-card').classList.remove('hidden');
        document.getElementById('sel-cust-name').innerText = cust.name;
        document.getElementById('sel-cust-phone').innerText = cust.phone;
    }

    function resetCustomer() {
        invState.customer = null;
        document.getElementById('customer-search-container').classList.remove('hidden');
        document.getElementById('selected-customer-card').classList.add('hidden');
    }

    // --- STEP 3: PRODUCTS ---
    function renderInvProducts() {
        const query = document.getElementById('inv-prod-search').value.toLowerCase();
        const list = document.getElementById('inv-product-list');
        const filtered = invData.products.filter(p => p.name.toLowerCase().includes(query));

        if (query.length === 0) { list.innerHTML = ''; return; }

        list.innerHTML = filtered.map(p => `
            <div class="flex justify-between items-center p-3 bg-white border border-gray-200 rounded-lg">
                <div>
                    <div class="font-medium">${p.name}</div>
                    <div class="text-xs text-gray-500">Rs ${p.price}</div>
                </div>
                <button onclick="addInvItem('${p.id}')" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Add</button>
            </div>
        `).join('');
    }

    function addInvItem(id) {
        const prod = invData.products.find(p => p.id === id);
        const exists = invState.items.find(i => i.id === id);
        if (exists) {
            exists.qty++;
            exists.total = exists.qty * exists.price;
        } else {
            invState.items.push({ id: prod.id, name: prod.name, price: prod.price, qty: 1, total: prod.price });
        }
        renderInvCart();
        document.getElementById('inv-prod-search').value = '';
        renderInvProducts();
    }

    function renderInvCart() {
        const container = document.getElementById('inv-cart-list');
        const wrapper = document.getElementById('inv-cart-container');
        const empty = document.getElementById('inv-empty-cart');
        
        if (invState.items.length === 0) {
            wrapper.classList.add('hidden');
            empty.classList.remove('hidden');
            return;
        }

        wrapper.classList.remove('hidden');
        empty.classList.add('hidden');

        let total = 0;
        container.innerHTML = invState.items.map(item => {
            total += item.total;
            return `
            <div class="flex justify-between items-center bg-white p-2 rounded border border-gray-200">
                <div class="flex-1">
                    <div class="font-medium text-sm">${item.name}</div>
                    <div class="text-xs text-gray-500">${item.qty} x Rs ${item.price}</div>
                </div>
                <div class="text-sm font-bold">Rs ${item.total}</div>
                <button onclick="remInvItem('${item.id}')" class="ml-2 text-red-500">&times;</button>
            </div>`;
        }).join('');
        
        document.getElementById('inv-cart-count').innerText = invState.items.length;
        document.getElementById('inv-cart-total').innerText = 'Rs ' + total;
        
        // Update Step 4 view total
        document.getElementById('term-line-total').innerText = 'Rs ' + total;
    }

    function remInvItem(id) {
        invState.items = invState.items.filter(i => i.id !== id);
        renderInvCart();
    }

    // --- STEP 4: TERMS ---
    function setDiscType(type) {
        invState.terms.discType = type;
        const btnP = document.getElementById('btn-disc-percent');
        const btnF = document.getElementById('btn-disc-fixed');
        if(type === 'percentage') {
            btnP.classList.replace('bg-white', 'bg-blue-50'); btnP.classList.replace('text-gray-600', 'text-blue-700'); btnP.classList.replace('border-gray-200', 'border-blue-500');
            btnF.classList.replace('bg-blue-50', 'bg-white'); btnF.classList.replace('text-blue-700', 'text-gray-600'); btnF.classList.replace('border-blue-500', 'border-gray-200');
        } else {
            btnF.classList.replace('bg-white', 'bg-blue-50'); btnF.classList.replace('text-gray-600', 'text-blue-700'); btnF.classList.replace('border-gray-200', 'border-blue-500');
            btnP.classList.replace('bg-blue-50', 'bg-white'); btnP.classList.replace('text-blue-700', 'text-gray-600'); btnP.classList.replace('border-blue-500', 'border-gray-200');
        }
        calcTotals();
    }

    function calcTotals() {
        const subtotal = invState.items.reduce((s, i) => s + i.total, 0);
        const discVal = parseFloat(document.getElementById('inv-disc-val').value) || 0;
        const taxEnabled = document.getElementById('inv-tax-check').checked;
        
        let discAmt = 0;
        if(invState.terms.discType === 'percentage') {
            discAmt = subtotal * (discVal / 100);
        } else {
            discAmt = discVal;
        }

        // Approval check logic
        const badge = document.getElementById('disc-approval-badge');
        if ((discAmt / subtotal) > 0.1) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        const afterDisc = subtotal - discAmt;
        const taxAmt = taxEnabled ? (afterDisc * 0.05) : 0;
        const grand = afterDisc + taxAmt;

        document.getElementById('prev-subtotal').innerText = subtotal.toFixed(2);
        document.getElementById('prev-discount').innerText = '-' + discAmt.toFixed(2);
        document.getElementById('prev-tax').innerText = '+' + taxAmt.toFixed(2);
        document.getElementById('prev-grand').innerText = grand.toFixed(2);

        invState.calculations = { subtotal, discAmt, taxAmt, grand };
    }

    // --- STEP 5: REVIEW ---
    function populateInvReview() {
        document.getElementById('rev-cust-name').innerText = invState.customer.name;
        document.getElementById('rev-cust-phone').innerText = invState.customer.phone;
        document.getElementById('rev-type').innerText = invState.type.replace('-', ' ');
        document.getElementById('rev-date').innerText = document.getElementById('inv-date').value;
        document.getElementById('rev-due').innerText = 'Net ' + document.getElementById('inv-terms').value + ' Days';

        const c = invState.calculations;
        document.getElementById('rev-sub').innerText = 'Rs ' + c.subtotal.toFixed(2);
        document.getElementById('rev-disc').innerText = '- Rs ' + c.discAmt.toFixed(2);
        document.getElementById('rev-tax').innerText = '+ Rs ' + c.taxAmt.toFixed(2);
        document.getElementById('rev-grand').innerText = 'Rs ' + c.grand.toFixed(2);
    }

    function submitInvoice() {
        const btn = document.getElementById('btn-inv-submit');
        btn.innerHTML = 'Creating...';
        btn.disabled = true;
        setTimeout(() => {
            alert('Invoice Created Successfully!');
            closeCreateInvoiceModal();
            btn.innerHTML = 'Create Invoice';
            btn.disabled = false;
        }, 1500);
    }
</script>