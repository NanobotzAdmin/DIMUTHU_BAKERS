@extends('layouts.app')
@section('title', 'Create Transfer Request')

@section('content')

    {{--
    -------------------------------------------------------------------------
    MOCK PRODUCT DATA (Simulating Product Master)
    -------------------------------------------------------------------------
    --}}
    @php
        $allProducts = [
            ['id' => 'P001', 'name' => 'Butter (Unsalted)', 'category' => 'Dairy', 'unit' => 'kg', 'unitPrice' => 2500, 'type' => 'ingredient', 'stock' => 50],
            ['id' => 'P002', 'name' => 'Sugar (White)', 'category' => 'Dry Goods', 'unit' => 'kg', 'unitPrice' => 350, 'type' => 'ingredient', 'stock' => 100],
            ['id' => 'P003', 'name' => 'Vanilla Essence', 'category' => 'Flavoring', 'unit' => 'L', 'unitPrice' => 4500, 'type' => 'ingredient', 'stock' => 10],
            ['id' => 'P004', 'name' => 'Eggs', 'category' => 'Dairy', 'unit' => 'pcs', 'unitPrice' => 45, 'type' => 'ingredient', 'stock' => 500],
            ['id' => 'P005', 'name' => 'Wheat Flour', 'category' => 'Flour', 'unit' => 'kg', 'unitPrice' => 180, 'type' => 'ingredient', 'stock' => 200],
            ['id' => 'P006', 'name' => 'Chocolate Chips', 'category' => 'Baking', 'unit' => 'kg', 'unitPrice' => 3200, 'type' => 'ingredient', 'stock' => 30],
            ['id' => 'P007', 'name' => 'Cake Boxes (M)', 'category' => 'Packaging', 'unit' => 'pcs', 'unitPrice' => 80, 'type' => 'packaging', 'stock' => 1000],
            ['id' => 'P008', 'name' => 'Sponge Cake Base', 'category' => 'Semi-Finished', 'unit' => 'kg', 'unitPrice' => 800, 'type' => 'semi-finished', 'stock' => 20],
        ];

        $categories = array_unique(array_column($allProducts, 'category'));
    @endphp

    <div class="min-h-screen bg-slate-50 p-4 md:p-8">
        <div class="max-w-full mx-auto">

            {{-- Page Header --}}
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ url('manage-stock-transfers') }}"
                        class="p-2 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">New Transfer Request</h1>
                        <p class="text-gray-500 mt-1" id="page-subtitle">Select source and destination sections</p>
                    </div>
                </div>

                {{-- Progress Steps --}}
                <div
                    class="hidden md:flex items-center gap-4 bg-white px-6 py-3 rounded-xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-2">
                        <div id="step-icon-1"
                            class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm">
                            1</div>
                        <span id="step-text-1" class="text-sm font-medium text-gray-900">Sections</span>
                    </div>
                    <div class="w-12 h-0.5 bg-gray-200">
                        <div id="step-line-1" class="h-full bg-blue-600 w-0 transition-all duration-300"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div id="step-icon-2"
                            class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm transition-colors">
                            2</div>
                        <span id="step-text-2" class="text-sm font-medium text-gray-500 transition-colors">Products</span>
                    </div>
                    <div class="w-12 h-0.5 bg-gray-200">
                        <div id="step-line-2" class="h-full bg-blue-600 w-0 transition-all duration-300"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div id="step-icon-3"
                            class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm transition-colors">
                            3</div>
                        <span id="step-text-3" class="text-sm font-medium text-gray-500 transition-colors">Review</span>
                    </div>
                </div>
            </div>

            {{-- Main Wizard Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden min-h-[600px] flex flex-col">

                {{-- Content Area --}}
                <div class="flex-1 p-8">

                    {{-- STEP 1: SELECT SECTIONS --}}
                    <div id="step-1" class="max-w-6xl mx-auto h-full flex flex-col">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 flex-1 min-h-0">

                            {{-- FROM SECTION --}}
                            <div class="flex flex-col h-full border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                                <div class="bg-blue-50 p-4 border-b border-blue-100">
                                    <h3 class="font-bold text-gray-900 flex items-center gap-2 text-lg">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold border border-blue-200">A</span>
                                        Transfer From
                                    </h3>
                                    <p class="text-sm text-gray-500 ml-10">Select source inventory</p>
                                </div>

                                <div class="flex-1 overflow-y-auto p-4 space-y-2 bg-white" id="list-from">
                                    {{-- Main Warehouse --}}
                                    <div onclick="selectSource('warehouse', 1, 'Main Warehouse', this, null)"
                                        class="src-item w-full p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-all group">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xl">
                                            🏢</div>
                                        <div class="flex-1">
                                            <div class="font-bold text-gray-900 text-sm">Main Warehouse</div>
                                            <div class="text-xs text-gray-500">Central Inventory</div>
                                        </div>
                                        <div
                                            class="w-4 h-4 rounded-full border border-gray-300 group-[.selected]:bg-blue-600 group-[.selected]:border-blue-600">
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-100 my-2"></div>

                                    {{-- Branches & Depts --}}
                                    @foreach($branches as $branch)
                                        <div class="space-y-1">
                                            {{-- Branch Itself --}}
                                            <div onclick="selectSource('branch', '{{ $branch->id }}', '{{ $branch->name }}', this)"
                                                class="src-item w-full p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-all group">
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                                                    🏬</div>
                                                <div class="flex-1">
                                                    <div class="font-bold text-gray-900 text-sm">{{ $branch->name }}</div>
                                                    <div class="text-xs text-gray-500">Branch Stock</div>
                                                </div>
                                                <div
                                                    class="w-4 h-4 rounded-full border border-gray-300 group-[.selected]:bg-blue-600 group-[.selected]:border-blue-600">
                                                </div>
                                            </div>

                                            {{-- Nested Departments --}}
                                            @if($branch->departments->count() > 0)
                                                <div class="pl-8 space-y-1 border-l-2 border-gray-100 ml-5">
                                                    @foreach($branch->departments as $dept)
                                                        <div onclick="selectSource('department', '{{ $dept->id }}', '{{ $dept->name }}', this, '{{ $branch->name }}')"
                                                            class="src-item w-full p-2 rounded-lg border border-transparent hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-all group opacity-80 hover:opacity-100">
                                                            <div
                                                                class="w-8 h-8 rounded-md bg-orange-100 text-orange-600 flex items-center justify-center text-sm">
                                                                🍳</div>
                                                            <div class="flex-1">
                                                                <div class="font-bold text-gray-900 text-sm">{{ $dept->name }}</div>
                                                                <div class="text-xs text-gray-400">Department</div>
                                                            </div>
                                                            <div
                                                                class="w-3 h-3 rounded-full border border-gray-300 group-[.selected]:bg-blue-600 group-[.selected]:border-blue-600">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- TO SECTION --}}
                            <div class="flex flex-col h-full border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                                <div class="bg-indigo-50 p-4 border-b border-indigo-100">
                                    <h3 class="font-bold text-gray-900 flex items-center gap-2 text-lg">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold border border-indigo-200">B</span>
                                        Transfer To
                                    </h3>
                                    <p class="text-sm text-gray-500 ml-10">Select destination inventory</p>
                                </div>

                                <div class="flex-1 overflow-y-auto p-4 space-y-2 bg-white" id="list-dest">
                                    {{-- Branches & Depts --}}
                                    @foreach($branches as $branch)
                                        <div class="space-y-1">
                                            {{-- Branch Itself --}}
                                            <div onclick="selectDest('branch', '{{ $branch->id }}', '{{ $branch->name }}', this)"
                                                class="dest-item w-full p-3 rounded-xl border border-gray-100 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-all group">
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl">
                                                    🏬</div>
                                                <div class="flex-1">
                                                    <div class="font-bold text-gray-900 text-sm">{{ $branch->name }}</div>
                                                    <div class="text-xs text-gray-500">Branch Stock</div>
                                                </div>
                                                <div
                                                    class="w-4 h-4 rounded-full border border-gray-300 group-[.selected]:bg-indigo-600 group-[.selected]:border-indigo-600">
                                                </div>
                                            </div>

                                            {{-- Nested Departments --}}
                                            @if($branch->departments->count() > 0)
                                                <div class="pl-8 space-y-1 border-l-2 border-gray-100 ml-5">
                                                    @foreach($branch->departments as $dept)
                                                        <div onclick="selectDest('department', '{{ $dept->id }}', '{{ $dept->name }}', this, '{{ $branch->name }}')"
                                                            class="dest-item w-full p-2 rounded-lg border border-transparent hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-all group opacity-80 hover:opacity-100">
                                                            <div
                                                                class="w-8 h-8 rounded-md bg-orange-100 text-orange-600 flex items-center justify-center text-sm">
                                                                🍳</div>
                                                            <div class="flex-1">
                                                                <div class="font-bold text-gray-900 text-sm">{{ $dept->name }}</div>
                                                                <div class="text-xs text-gray-400">Department</div>
                                                            </div>
                                                            <div
                                                                class="w-3 h-3 rounded-full border border-gray-300 group-[.selected]:bg-indigo-600 group-[.selected]:border-indigo-600">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        {{-- Validation Msg --}}
                        <div id="step1-footer"
                            class="mt-4 flex justify-between items-center opacity-50 pointer-events-none transition-all flex-shrink-0">
                            <div class="text-sm text-gray-500">Select both a source and destination to continue.</div>
                            <button onclick="nextStep()" id="btn-step1-next"
                                class="px-6 py-2 bg-gray-200 text-gray-400 rounded-lg font-bold transition-all shadow-none">
                                Load Stock & Continue
                            </button>
                        </div>

                    </div>

                    {{-- STEP 2: SELECT PRODUCTS --}}
                    <div id="step-2" class="hidden flex flex-col h-full">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 h-full">

                            <div class="col-span-8 flex flex-col h-full">
                                <div class="flex items-center gap-4 mb-4">
                                    <div
                                        class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 h-12 flex items-center gap-3 focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-100 transition-all">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <input type="text" id="product-search" placeholder="Search available products..."
                                            class="bg-transparent border-none outline-none flex-1 text-gray-900 placeholder:text-gray-400">
                                    </div>
                                    <select id="category-filter"
                                        class="h-12 pl-4 pr-10 border border-gray-200 rounded-xl bg-gray-50 text-gray-700 outline-none cursor-pointer focus:border-blue-500"
                                        onchange="filterCategory(this.value)">
                                        <option value="all">All Product Types</option>
                                        @foreach($productTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->product_type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                                    <div id="product-grid" class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                                    </div>
                                    <div id="no-products-msg"
                                        class="hidden h-64 flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <p>No products found</p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="col-span-4 flex flex-col bg-slate-50 border border-gray-200 rounded-xl h-full overflow-hidden">
                                <div class="p-4 border-b border-gray-200 bg-white">
                                    <div class="flex justify-between items-center mb-1">
                                        <h3 class="font-bold text-gray-900">Selected Items</h3>
                                        <span id="selected-count"
                                            class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-bold">0</span>
                                    </div>
                                    <div class="text-sm text-gray-500">Total Value: <span id="running-total"
                                            class="font-mono text-gray-900 font-bold">Rs 0</span></div>
                                </div>

                                <div class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar relative">
                                    <div id="empty-cart"
                                        class="h-full flex flex-col items-center justify-center text-center p-6 text-gray-400 absolute inset-0">
                                        <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="text-sm">Click products on the left to add them</p>
                                    </div>
                                    <div id="selected-list" class="space-y-2 relative z-10"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3: REVIEW --}}
                    <div id="step-3" class="hidden max-w-4xl mx-auto space-y-8">
                        <div
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-6">
                                <div class="text-center">
                                    <div class="text-4xl mb-2" id="summary-icon-from"></div>
                                    <div class="font-bold text-gray-900 capitalize" id="summary-text-from"></div>
                                    <div class="text-xs text-blue-600 uppercase font-bold tracking-wider">Source</div>
                                </div>
                                <div class="flex flex-col items-center gap-1 opacity-50">
                                    <div class="w-16 h-0.5 bg-blue-400"></div>
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <div class="text-4xl mb-2" id="summary-icon-to"></div>
                                    <div class="font-bold text-gray-900 capitalize" id="summary-text-to"></div>
                                    <div class="text-xs text-indigo-600 uppercase font-bold tracking-wider">Destination
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500 mb-1">Total Estimated Value</div>
                                <div class="text-3xl font-bold text-gray-900" id="summary-total-value">Rs 0</div>
                                <div class="text-sm text-gray-500 mt-1"><span id="summary-item-count">0</span> Items
                                    Selected</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach(['low', 'medium', 'high', 'urgent'] as $p)
                                            <button onclick="setPriority('{{ $p }}')"
                                                class="priority-btn p-3 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-600 capitalize font-medium transition-all"
                                                data-priority="{{ $p }}">
                                                {{ $p }}
                                            </button>
                                        @endforeach
                                    </div>
                                    <input type="hidden" id="input-priority" value="medium">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date</label>
                                    <input type="date" id="input-date"
                                        class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 outline-none"
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                    <textarea id="input-notes"
                                        class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 outline-none h-32 resize-none"
                                        placeholder="Any special instructions..."></textarea>
                                </div>
                            </div>

                            <div
                                class="bg-white border border-gray-200 rounded-xl overflow-hidden flex flex-col h-[400px] shadow-sm">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 font-bold text-gray-700">Items
                                    Manifest</div>
                                <div id="review-list" class="flex-1 overflow-y-auto p-4 space-y-2">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Sticky Footer --}}
                <div class="p-6 bg-white border-t border-gray-200 flex justify-between items-center">
                    <button onclick="prevStep()" id="btn-back"
                        class="hidden px-6 py-3 rounded-xl text-gray-600 font-medium hover:bg-gray-100 transition-colors">
                        Back
                    </button>
                    <div class="flex-1"></div>
                    <button onclick="cancelTransfer()"
                        class="px-6 py-3 rounded-xl text-gray-500 font-medium hover:text-gray-700 hover:bg-gray-50 transition-colors mr-3">
                        Cancel
                    </button>
                    <button onclick="nextStep()" id="btn-next" disabled
                        class="px-8 py-3 rounded-xl bg-gray-200 text-gray-400 font-bold cursor-not-allowed transition-all shadow-none">
                        Continue
                    </button>
                    <button onclick="submitTransfer()" id="btn-submit"
                        class="hidden px-8 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold shadow-lg hover:shadow-xl hover:translate-y-[-1px] transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Confirm Transfer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- Data & State ---
        let allProducts = []; // Loaded dynamically

        let state = {
            step: 0,

            // State Structure
            fromType: 'warehouse', // defaults
            fromId: 1,
            fromName: 'Main Warehouse',
            fromParent: null,

            toType: null,
            toId: null,
            toName: null,
            toParent: null,
            toParent: null,

            selectedItems: [],
            searchQuery: '',
            categoryFilter: 'all',
            priority: 'medium'
        };

        // --- Initialization ---
        document.addEventListener('DOMContentLoaded', () => {
            state.step = 1;

            // Init visual state
            // By default Warehouse is selected in data, let's reflect that visually if needed
            const wareItem = document.querySelector('#list-from .src-item');
            if (wareItem && state.fromType === 'warehouse') {
                wareItem.classList.add('selected', 'border-blue-500', 'bg-blue-50', 'ring-1', 'ring-blue-200');
                wareItem.classList.remove('border-gray-100');
            }

            renderStep();
            setPriority('medium');
        });

        // --- Navigation Logic ---
        function cancelTransfer() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('manage-stock-transfers') }}";
                }
            });
        }

        function renderStep() {
            // Toggle Steps visibility
            ['step-1', 'step-2', 'step-3'].forEach(id => document.getElementById(id).classList.add('hidden'));
            document.getElementById(`step-${state.step}`).classList.remove('hidden');

            // Progress & Header
            const subtitles = ["Step 1: Select Source and Destination", "Step 2: Add Products", "Step 3: Review Details"];
            document.getElementById('page-subtitle').textContent = subtitles[state.step - 1];

            for (let i = 1; i <= 3; i++) {
                const icon = document.getElementById(`step-icon-${i}`);
                const text = document.getElementById(`step-text-${i}`);
                const line = document.getElementById(`step-line-${i - 1}`);

                if (i <= state.step) {
                    icon.className = 'w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm transition-colors';
                    text.className = 'text-sm font-medium text-gray-900 transition-colors';
                    if (line) { line.classList.remove('w-0'); line.classList.add('w-full'); }
                } else {
                    icon.className = 'w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm transition-colors';
                    text.className = 'text-sm font-medium text-gray-500 transition-colors';
                    if (line) { line.classList.remove('w-full'); line.classList.add('w-0'); }
                }
            }

            // Buttons
            const btnBack = document.getElementById('btn-back');
            const btnNext = document.getElementById('btn-next');
            const btnSubmit = document.getElementById('btn-submit');

            // Default Footer Button States
            btnBack.classList.remove('hidden');
            btnNext.classList.remove('hidden');
            btnSubmit.classList.add('hidden');

            if (state.step === 1) {
                // Step 1 uses custom footer button inside the Step 1 div, main footer buttons hidden
                btnBack.classList.add('hidden');
                btnNext.classList.add('hidden');
                validateStep1();
            } else if (state.step === 2) {
                btnNext.textContent = "Continue";
                renderProducts();
                renderCart();
                validateStep2();
            } else {
                // Step 3
                btnNext.classList.add('hidden');
                btnSubmit.classList.remove('hidden');
                renderSummary();
            }
        }

        window.nextStep = async function () {
            if (state.step === 1) {
                // Load Stock Data Logic
                const btn = document.getElementById('btn-step1-next');
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading Stock...';

                try {
                    const response = await fetch('/api/inventory/transfer/stock', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            source_type: state.fromType,
                            source_id: state.fromId
                        })
                    });

                    if (!response.ok) throw new Error('Failed to fetch stock');

                    const data = await response.json();
                    allProducts = data.items;

                    state.step++;
                    renderStep();

                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Failed to load stock for selected source: ' + error.message, 'error');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } else {
                state.step++;
                renderStep();
            }
        }

        window.prevStep = function () { state.step--; renderStep(); }

        function enableNextBtn(enable) {
            // Step 1 custom footer
            if (state.step === 1) {
                const footer = document.getElementById('step1-footer');
                const btn = document.getElementById('btn-step1-next');
                if (enable) {
                    footer.classList.remove('opacity-50', 'pointer-events-none');
                    btn.classList.remove('bg-gray-200', 'text-gray-400', 'shadow-none');
                    btn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700', 'shadow-lg');
                } else {
                    footer.classList.add('opacity-50', 'pointer-events-none');
                    btn.classList.add('bg-gray-200', 'text-gray-400', 'shadow-none');
                    btn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700', 'shadow-lg');
                }
                return;
            }

            // Main footer
            const btn = document.getElementById('btn-next');
            if (enable) {
                btn.disabled = false;
                btn.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed', 'shadow-none');
                btn.classList.add('bg-green-600', 'text-white', 'shadow-lg', 'hover:bg-green-700');
            } else {
                btn.disabled = true;
                btn.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed', 'shadow-none');
                btn.classList.remove('bg-green-600', 'text-white', 'shadow-lg', 'hover:bg-green-700');
            }
        }

        // --- STEP 1: Selection Logic ---
        window.selectSource = function (type, id, name, el, parentName = null) {
            state.fromType = type;
            state.fromId = id;
            state.fromName = name;
            state.fromParent = parentName;
            state.fromParent = parentName;

            // Visual Selection
            document.querySelectorAll('#list-from .src-item').forEach(item => {
                item.classList.remove('selected', 'border-blue-500', 'bg-blue-50', 'ring-1', 'ring-blue-200');
                item.classList.add('border-gray-100');
            });

            if (el) {
                el.classList.add('selected', 'border-blue-500', 'bg-blue-50', 'ring-1', 'ring-blue-200');
                el.classList.remove('border-gray-100');
            }

            validateStep1();
        }

        window.selectDest = function (type, id, name, el, parentName = null) {
            state.toType = type;
            state.toId = id;
            state.toName = name;
            state.toParent = parentName;

            // Visual Selection
            document.querySelectorAll('#list-dest .dest-item').forEach(item => {
                item.classList.remove('selected', 'border-indigo-500', 'bg-indigo-50', 'ring-1', 'ring-indigo-200');
                item.classList.add('border-gray-100');
            });

            if (el) {
                el.classList.add('selected', 'border-indigo-500', 'bg-indigo-50', 'ring-1', 'ring-indigo-200');
                el.classList.remove('border-gray-100');
            }

            validateStep1();
        }

        function validateStep1() {
            // Just check if both valid. 
            // We do basic distinct check if relevant (e.g. not same branch to same branch)
            // But source is usually warehouse or branch, dest is branch or dept.

            const isValid = state.fromId && state.toId;

            let isSame = false;
            // Simple same-type same-id check
            if (state.fromType == state.toType && state.fromId == state.toId) isSame = true;

            if (isValid && isSame) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Source and Destination cannot be the same',
                    showConfirmButton: false,
                    timer: 3000
                });
            }

            enableNextBtn(isValid && !isSame);
        }

        // --- STEP 2: Products ---
        document.getElementById('product-search').addEventListener('input', (e) => {
            state.searchQuery = e.target.value.toLowerCase();
            renderProducts();
        });

        window.filterCategory = function (cat) {
            state.categoryFilter = cat;
            renderProducts();
        }

        window.addItem = function (stockId) {
            // Use stock_id to find the specific batch/stock record
            const product = allProducts.find(p => p.stock_id === stockId);

            if (!product) return;

            // Check using stock_id to allow multiple batches of same product if needed, 
            // or just to ensure unique selection key
            const exists = state.selectedItems.find(i => i.stock_id === stockId);
            if (!exists) {
                state.selectedItems.push({ ...product, quantity: 1 });
                renderCart();
                renderProducts();
                validateStep2();
            }
        }

        function renderProducts() {
            const grid = document.getElementById('product-grid');
            grid.innerHTML = '';

            const filtered = allProducts.filter(p => {
                const matchesSearch = p.name.toLowerCase().includes(state.searchQuery);
                const matchesCat = state.categoryFilter === 'all' || p.product_type_id == state.categoryFilter;
                return matchesSearch && matchesCat;
            });

            if (filtered.length === 0) {
                document.getElementById('no-products-msg').classList.remove('hidden');
            } else {
                document.getElementById('no-products-msg').classList.add('hidden');

                filtered.forEach(p => {
                    // Check selection using stock_id
                    const isSelected = state.selectedItems.some(i => i.stock_id === p.stock_id);

                    const el = document.createElement('div');
                    let baseClass = "p-4 rounded-xl border transition-all cursor-pointer relative overflow-hidden ";
                    if (isSelected) {
                        baseClass += "border-green-500 bg-green-50 ring-1 ring-green-200";
                    } else {
                        baseClass += "border-gray-200 bg-white hover:border-blue-300 hover:shadow-md";
                    }
                    el.className = baseClass;

                    el.onclick = function () {
                        // Pass stock_id instead of product_item_id
                        if (!isSelected) window.addItem(p.stock_id);
                    };

                    // Added Batch info
                    let extraInfo = '';
                    if (p.batch && p.batch !== 'N/A') extraInfo += `<span class="text-xs bg-gray-100 text-gray-600 px-1 rounded mr-1">${p.batch}</span>`;
                    if (p.expiry && p.expiry !== 'N/A') extraInfo += `<span class="text-xs bg-red-50 text-red-600 px-1 rounded">Exp: ${p.expiry}</span>`;

                    el.innerHTML = `
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <div class="font-bold text-gray-900 leading-tight">${p.name}</div>
                                <div class="text-xs text-gray-500 uppercase font-bold tracking-wide mt-1">${p.category}</div>
                            </div>
                            ${isSelected ? '<div class="bg-green-500 text-white p-1 rounded-full"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>' : ''}
                        </div>
                        <div class="mb-2">${extraInfo}</div>
                        <div class="flex justify-between items-end mt-2">
                            <div class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">Avail: ${p.stock} ${p.unit}</div>
                            <div class="font-medium text-blue-600">Rs ${p.unitPrice}</div>
                        </div>
                    `;

                    grid.appendChild(el);
                });
            }
        }

        function renderCart() {
            const list = document.getElementById('selected-list');
            const empty = document.getElementById('empty-cart');
            const countSpan = document.getElementById('selected-count');
            const totalSpan = document.getElementById('running-total');

            list.innerHTML = '';
            let totalVal = 0;

            if (state.selectedItems.length === 0) {
                empty.classList.remove('hidden');
                countSpan.textContent = '0';
                totalSpan.textContent = 'Rs 0';
                return;
            }

            empty.classList.add('hidden');
            countSpan.textContent = state.selectedItems.length;

            state.selectedItems.forEach(item => {
                const val = item.quantity * item.unitPrice;
                totalVal += val;

                const row = document.createElement('div');
                row.className = "bg-white border border-gray-100 rounded-lg p-3 shadow-sm mb-2 group";

                row.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <div class="font-medium text-gray-800 text-sm leading-tight w-10/12">${item.name}</div>
                        <button type="button" class="text-gray-400 hover:text-red-500 transition-colors remove-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center bg-gray-50 rounded-lg border border-gray-200">
                            <button type="button" class="px-2 py-1 hover:bg-gray-200 text-gray-600 rounded-l-lg font-bold dec-btn">-</button>
                            <span class="w-8 text-center text-sm font-bold bg-white h-full flex items-center justify-center">${item.quantity}</span>
                            <button type="button" class="px-2 py-1 hover:bg-gray-200 text-gray-600 rounded-r-lg font-bold inc-btn">+</button>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500">Max: ${item.stock}</div>
                            <div class="text-sm font-bold text-blue-600">Rs ${val.toLocaleString()}</div>
                        </div>
                    </div>
                `;

                row.querySelector('.remove-btn').onclick = () => window.removeItem(item.stock_id);
                row.querySelector('.dec-btn').onclick = () => window.updateQty(item.stock_id, -1);
                row.querySelector('.inc-btn').onclick = () => window.updateQty(item.stock_id, 1);

                list.appendChild(row);
            });

            totalSpan.textContent = 'Rs ' + totalVal.toLocaleString();
        }

        window.updateQty = function (stockId, delta) {
            const idx = state.selectedItems.findIndex(i => i.stock_id === stockId);
            if (idx > -1) {
                let item = state.selectedItems[idx];
                let newQty = item.quantity + delta;

                // Validate against Max Stock
                if (newQty > item.stock) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Cannot exceed available stock limit',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    return;
                }

                if (newQty > 0) {
                    state.selectedItems[idx].quantity = newQty;
                    renderCart();
                }
            }
        }

        window.removeItem = function (stockId) {
            state.selectedItems = state.selectedItems.filter(i => i.stock_id !== stockId);
            renderCart();
            renderProducts();
            validateStep2();
        }

        function validateStep2() {
            enableNextBtn(state.selectedItems.length > 0);
        }

        // --- STEP 3 ---
        window.setPriority = function (p) {
            state.priority = p;
            document.querySelectorAll('.priority-btn').forEach(btn => {
                if (btn.dataset.priority === p) {
                    let color = p === 'urgent' ? 'red' : (p === 'high' ? 'orange' : (p === 'medium' ? 'yellow' : 'blue'));
                    btn.className = `priority-btn p-3 rounded-xl border-2 capitalize font-bold transition-all shadow-sm border-${color}-500 bg-${color}-50 text-${color}-700`;
                    btn.style.borderColor = p === 'urgent' ? '#ef4444' : p === 'high' ? '#f97316' : p === 'medium' ? '#eab308' : '#3b82f6';
                    btn.style.backgroundColor = '#eff6ff';
                } else {
                    btn.className = 'priority-btn p-3 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-600 capitalize font-medium transition-all';
                    btn.style = '';
                }
            });
        }

        function renderSummary() {
            const icons = { warehouse: '🏢', branch: '🏬', department: '🍳' };

            document.getElementById('summary-icon-from').textContent = icons[state.fromType] || '📦';
            let fromDisplay = state.fromName;
            if (state.fromParent) {
                fromDisplay = `<span class="text-sm font-normal text-gray-500">${state.fromParent}</span> <br> ${fromDisplay}`;
            }
            document.getElementById('summary-text-from').innerHTML = fromDisplay;

            document.getElementById('summary-icon-to').textContent = icons[state.toType] || '📦';
            let toDisplay = state.toName;
            if (state.toParent) {
                toDisplay = `<span class="text-sm font-normal text-gray-500">${state.toParent}</span> <br> ${toDisplay}`;
            }
            document.getElementById('summary-text-to').innerHTML = toDisplay;

            const totalVal = state.selectedItems.reduce((acc, i) => acc + (i.quantity * i.unitPrice), 0);
            document.getElementById('summary-total-value').textContent = 'Rs ' + totalVal.toLocaleString();
            document.getElementById('summary-item-count').textContent = state.selectedItems.length;

            const list = document.getElementById('review-list');
            list.innerHTML = '';
            state.selectedItems.forEach(item => {
                list.insertAdjacentHTML('beforeend', `
                    <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0">
                        <div>
                            <div class="font-medium text-gray-800">${item.name}</div>
                            <div class="text-xs text-gray-500">Qty: ${item.quantity} ${item.unit}</div>
                        </div>
                        <div class="font-mono text-gray-600 font-medium">Rs ${(item.quantity * item.unitPrice).toLocaleString()}</div>
                    </div>
                `);
            });
        }

        window.submitTransfer = function () {
            const btn = document.getElementById('btn-submit');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="animate-spin mr-2">⏳</span> Processing...';

            const payload = {
                order_number: 'TRF-' + Date.now(), // Or let backend generate
                source_type: state.fromType,
                source_id: state.fromId,
                destination_type: state.toType,
                destination_id: state.toId,
                priority: state.priority,
                scheduled_date: document.getElementById('input-date').value,
                notes: document.getElementById('input-notes').value,
                items: state.selectedItems.map(i => ({
                    product_item_id: i.id,
                    stock_id: i.stock_id,
                    quantity: i.quantity,
                    batch: i.batch || 'N/A'
                }))
            };

            $.ajax({
                url: "{{ route('inventory.transfer.store') }}",
                type: 'POST',
                data: JSON.stringify(payload),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Stock transfer request created successfully.',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = "{{ url('manage-stock-transfers') }}";
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    let msg = 'Failed to create transfer request.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg += ' ' + xhr.responseJSON.message;
                    }
                    Swal.fire('Error', msg, 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        }
    </script>
@endsection