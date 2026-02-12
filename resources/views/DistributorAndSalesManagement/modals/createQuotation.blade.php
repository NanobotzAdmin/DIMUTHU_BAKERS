<div id="createQuotationModal"
    class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 overflow-y-auto backdrop-blur-sm transition-all duration-300">
    @php
        use App\CommonVariables;
    @endphp
    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col my-8 transform transition-all scale-100">

        {{-- HEADER --}}
        <div
            class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-indigo-50 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl text-gray-900 font-bold">Create New Quotation</h2>
                    <p class="text-sm text-gray-600">Select customer and add products</p>
                </div>
            </div>
            <button onclick="closeCreateModal()"
                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-white rounded-lg transition-all">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- CONTENT --}}
        <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
            <div class="max-w-5xl mx-auto space-y-6">

                {{-- 1. Customer Selection --}}
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">
                        <svg class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Customer Selection
                    </h3>

                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Customer</label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </span>
                                <input type="text" id="qt-customer-search" oninput="searchQtCustomers(this.value)"
                                    placeholder="Search by name or phone..."
                                    class="w-full h-11 pl-10 pr-4 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500">

                                {{-- Dropdown Results --}}
                                <div id="qt-customer-results"
                                    class="hidden absolute top-full left-0 right-0 bg-white shadow-xl rounded-xl mt-2 max-h-60 overflow-y-auto border border-gray-100 z-20">
                                </div>
                            </div>
                            <button onclick="openQtNewCustomerModal()"
                                class="h-11 px-4 bg-purple-100 text-purple-700 hover:bg-purple-200 rounded-lg font-medium transition-colors">
                                + New Customer
                            </button>
                        </div>

                        {{-- Selected Customer Display --}}
                        <div id="qt-selected-customer"
                            class="hidden mt-4 p-4 bg-purple-50 border border-purple-200 rounded-lg flex justify-between items-center group">
                            <div>
                                <p class="font-bold text-gray-900" id="qt-cust-name"></p>
                                <p class="text-sm text-gray-600" id="qt-cust-phone"></p>
                                <input type="hidden" id="qt-customer-id">
                            </div>
                            <button onclick="clearQtCustomer()"
                                class="text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition-opacity">Remove</button>
                        </div>
                    </div>
                </div>

                {{-- 2. General Details --}}
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">Quotation Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Valid Until</label>
                            <input type="date" id="qt-valid-until"
                                class="w-full h-11 px-4 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="qt-status"
                                class="w-full h-11 px-4 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 bg-white">
                                <option value="{{ CommonVariables::$quotationStatusDraft }}">Draft</option>
                                <option value="{{ CommonVariables::$quotationStatusSent }}">Sent</option>
                                <option value="{{ CommonVariables::$quotationStatusPendingApproval }}">Pending Approval
                                </option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea id="qt-notes" rows="2"
                                class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500"
                                placeholder="Internal notes or customer remarks..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- 3. Products/Line Items --}}
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg mb-4 flex items-center gap-2 font-semibold text-gray-800">Line Items</h3>

                    {{-- Product Search --}}
                    <div class="relative mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Add Product</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </span>
                            <input type="text" id="qt-product-search" oninput="searchQtProducts(this.value)"
                                placeholder="Search products to add..."
                                class="w-full h-11 pl-10 pr-4 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500">

                            {{-- Product Dropdown --}}
                            <div id="qt-product-results"
                                class="hidden absolute top-full left-0 right-0 bg-white shadow-xl rounded-xl mt-2 max-h-60 overflow-y-auto border border-gray-100 z-20">
                            </div>
                        </div>
                    </div>

                    {{-- Items Table --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-600">Product</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-32 text-center">Unit
                                        Price</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-24 text-center">Qty</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-600 w-32 text-right">Total</th>
                                    <th class="px-4 py-3 w-16"></th>
                                </tr>
                            </thead>
                            <tbody id="qt-items-body" class="divide-y divide-gray-100">
                                <tr id="qt-empty-row">
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">No products added yet.
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-gray-50 border-t border-gray-200">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-700">Grand Total:
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-purple-600" id="qt-grand-total">Rs
                                        0.00</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- FOOTER --}}
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-end gap-3 flex-shrink-0">
            <button onclick="closeCreateModal()"
                class="h-11 px-6 text-gray-700 hover:bg-gray-200 rounded-lg transition-all font-medium">Cancel</button>
            <button onclick="submitQuotation()"
                class="h-11 px-6 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-lg transition-all shadow-md font-medium">Save
                Quotation</button>
        </div>
    </div>
</div>

{{-- New Customer Modal (Reuse structure or simplified) --}}
<div id="qt-new-customer-modal"
    class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-[60] backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-900">New Customer</h3>
            <button onclick="closeQtNewCustomerModal()" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg></button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                <input type="text" id="qt-new-cust-name" class="w-full h-10 px-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                <input type="text" id="qt-new-cust-phone" class="w-full h-10 px-3 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select id="qt-new-cust-type" class="w-full h-10 px-3 border border-gray-300 rounded-lg bg-white">
                    <option value="individual">Individual</option>
                    <option value="corporate">Corporate</option>
                </select>
            </div>
            <button onclick="saveQtNewCustomer()"
                class="w-full h-11 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium mt-2">Create
                Customer</button>
        </div>
    </div>
</div>

<script>
    // State
    let qtCart = [];
    let qtSelectedCustomer = null;

    // --- Modal Control ---
    function closeCreateModal() {
        document.getElementById('createQuotationModal').classList.add('hidden');
        resetQtForm();
    }

    function resetQtForm() {
        qtCart = [];
        qtSelectedCustomer = null;
        renderQtCart();
        clearQtCustomer();
        document.getElementById('qt-notes').value = '';
        document.getElementById('qt-valid-until').value = '';
        document.getElementById('qt-status').value = '{{ CommonVariables::$quotationStatusDraft }}';
        document.getElementById('qt-customer-search').value = '';
        document.getElementById('qt-product-search').value = '';
    }

    // --- Customer Logic ---
    function searchQtCustomers(query) {
        if (query.length < 2) {
            document.getElementById('qt-customer-results').classList.add('hidden');
            return;
        }

        fetch('{{ route("orderManagement.searchCustomers") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ query: query })
        })
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('qt-customer-results');
                list.innerHTML = '';
                if (data.length > 0) {
                    list.classList.remove('hidden');
                    data.forEach(cust => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0';
                        div.innerHTML = `<div class="font-medium text-gray-900">${cust.name}</div><div class="text-xs text-gray-500">${cust.phone}</div>`;
                        div.onclick = () => selectQtCustomer(cust);
                        list.appendChild(div);
                    });
                } else {
                    list.classList.add('hidden');
                }
            });
    }

    function selectQtCustomer(cust) {
        qtSelectedCustomer = cust;
        document.getElementById('qt-customer-id').value = cust.id;
        document.getElementById('qt-cust-name').innerText = cust.name;
        document.getElementById('qt-cust-phone').innerText = cust.phone;

        document.getElementById('qt-selected-customer').classList.remove('hidden');
        document.getElementById('qt-customer-search').parentElement.classList.add('hidden'); // Hide search box
        document.getElementById('qt-customer-results').classList.add('hidden');
    }

    function clearQtCustomer() {
        qtSelectedCustomer = null;
        document.getElementById('qt-customer-id').value = '';
        document.getElementById('qt-selected-customer').classList.add('hidden');
        document.getElementById('qt-customer-search').parentElement.classList.remove('hidden');
        document.getElementById('qt-customer-search').value = '';
    }

    // --- New Customer Modal ---
    function openQtNewCustomerModal() { document.getElementById('qt-new-customer-modal').classList.remove('hidden'); }
    function closeQtNewCustomerModal() { document.getElementById('qt-new-customer-modal').classList.add('hidden'); }

    function saveQtNewCustomer() {
        const name = document.getElementById('qt-new-cust-name').value;
        const phone = document.getElementById('qt-new-cust-phone').value;
        const type = document.getElementById('qt-new-cust-type').value;

        if (!name || !phone) return Swal.fire('Error', 'Name and Phone required', 'error');

        fetch('{{ route("orderManagement.createCustomer") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ name, phone, type })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.customer) {
                    selectQtCustomer(data.customer);
                    closeQtNewCustomerModal();
                    Swal.fire('Success', 'Customer Created', 'success');
                }
            });
    }

    // --- Product Logic ---
    function searchQtProducts(query) {
        if (query.length < 2) {
            document.getElementById('qt-product-results').classList.add('hidden');
            return;
        }

        // Pass dummy channel/date just to satisfy controller validation if needed, 
        // but searchProducts only requires query usually.
        // Checking controller: orderManageSearchProducts requires branch_id? No, usually just search.
        // Controller code: uses request->search_term. and branch_id optional?
        // Let's assume standard search.

        fetch('{{ route("orderManagement.searchProducts") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ query: query }) // Controller expects 'query'
        })
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('qt-product-results');
                list.innerHTML = '';
                if (data.length > 0) {
                    list.classList.remove('hidden');
                    data.forEach(prod => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0 flex justify-between';
                        div.innerHTML = `<div><div class="font-medium text-gray-900">${prod.product_name}</div><div class="text-xs text-gray-500">${prod.reference_number || prod.bin_code || ''}</div></div><div class="font-bold text-purple-600">Rs ${prod.price}</div>`;
                        div.onclick = () => addQtProduct(prod);
                        list.appendChild(div);
                    });
                } else {
                    list.classList.add('hidden');
                }
            });
    }

    function addQtProduct(prod) {
        // Check duplicate
        const exists = qtCart.find(p => p.id === prod.id);
        if (exists) {
            exists.quantity++;
        } else {
            qtCart.push({
                id: prod.id,
                name: prod.product_name,
                unit_price: parseFloat(prod.price_raw),
                quantity: 1
            });
        }
        document.getElementById('qt-product-search').value = '';
        document.getElementById('qt-product-results').classList.add('hidden');
        renderQtCart();
    }

    function removeQtProduct(id) {
        qtCart = qtCart.filter(p => p.id !== id);
        renderQtCart();
    }

    function updateQtQty(id, qty) {
        const item = qtCart.find(p => p.id === id);
        if (item) {
            item.quantity = parseFloat(qty);
            if (item.quantity <= 0) removeQtProduct(id);
            else renderQtCart();
        }
    }

    function updateQtPrice(id, price) {
        const item = qtCart.find(p => p.id === id);
        if (item) {
            item.unit_price = parseFloat(price);
            renderQtCart();
        }
    }

    function renderQtCart() {
        const body = document.getElementById('qt-items-body');
        body.innerHTML = '';
        let total = 0;

        if (qtCart.length === 0) {
            body.innerHTML = `<tr id="qt-empty-row"><td colspan="5" class="px-4 py-8 text-center text-gray-500">No products added yet.</td></tr>`;
        } else {
            qtCart.forEach(item => {
                const rowTotal = item.quantity * item.unit_price;
                total += rowTotal;

                const tr = document.createElement('tr');
                tr.className = 'border-b border-gray-50 hover:bg-gray-50';
                tr.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">${item.name}</td>
                    <td class="px-4 py-3 text-center">
                        <input type="number" value="${item.unit_price}" onchange="updateQtPrice(${item.id}, this.value)" class="w-24 text-center border border-gray-200 rounded px-1 text-sm">
                    </td>
                    <td class="px-4 py-3 text-center">
                        <input type="number" value="${item.quantity}" onchange="updateQtQty(${item.id}, this.value)" class="w-16 text-center border border-gray-200 rounded px-1 text-sm bg-gray-50">
                    </td>
                    <td class="px-4 py-3 text-right text-sm font-bold text-gray-900">Rs ${rowTotal.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                    <td class="px-4 py-3 text-center">
                        <button onclick="removeQtProduct(${item.id})" class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
                    </td>
                `;
                body.appendChild(tr);
            });
        }
        document.getElementById('qt-grand-total').innerText = 'Rs ' + total.toLocaleString(undefined, { minimumFractionDigits: 2 });
    }

    // --- Submit ---
    function submitQuotation() {
        if (!qtSelectedCustomer) return Swal.fire('Error', 'Please select a customer', 'error');
        if (qtCart.length === 0) return Swal.fire('Error', 'Please add at least one product', 'error');

        const payload = {
            customer_id: qtSelectedCustomer.id,
            valid_until: document.getElementById('qt-valid-until').value,
            status: document.getElementById('qt-status').value,
            notes: document.getElementById('qt-notes').value,
            products: qtCart.map(p => ({
                product_item_id: p.id,
                quantity: p.quantity,
                unit_price: p.unit_price
            }))
        };

        fetch('{{ route("quotationManagement.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(payload)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', 'Quotation created successfully', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Something went wrong', 'error');
                }
            });
    }
</script>