@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#EDEFF5] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('agent-panel.daily-loads') }}" 
                   class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-700 shadow-sm hover:bg-gray-50 transition-all">
                    <i class="bi bi-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create Daily Load</h1>
                    <p class="text-sm text-gray-500">Add a new daily load plan with assignments and products</p>
                </div>
            </div>
        </div>

        <div id="loadingState" class="bg-white rounded-2xl p-12 text-center border border-gray-200 shadow-sm">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-[#059669] border-t-transparent mb-4"></div>
            <p class="text-gray-500 font-medium">Loading required data...</p>
        </div>

        <form id="dailyLoadForm" class="hidden grid grid-cols-1 lg:grid-cols-3 gap-8" onsubmit="submitForm(event)">
            <!-- Left 2 Columns: Main Form Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Info Section -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm space-y-6">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="bi bi-calendar-check text-[#059669]"></i>
                        Load & Route Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Load Date *</label>
                            <div class="relative">
                                <i class="bi bi-calendar absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="date" id="loadDate" required
                                       class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#059669]/40 focus:border-[#059669] text-sm bg-white font-medium shadow-sm transition-all"
                                       value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Route *</label>
                            <select id="routeSelect" required onchange="handleRouteSelection()"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#059669]/40 focus:border-[#059669] text-sm bg-white font-medium shadow-sm transition-all">
                                <option value="">Select Route</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <textarea id="notes" rows="3" placeholder="Enter special notes here..."
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#059669]/40 focus:border-[#059669] text-sm bg-white font-medium shadow-sm transition-all"></textarea>
                    </div>
                </div>

                <!-- Product Items Section -->
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="bi bi-box-seam text-[#059669]"></i>
                            Product Items
                        </h2>
                        <button type="button" onclick="openProductModal()"
                                class="bg-[#059669] hover:bg-[#047857] text-white px-4 py-2 rounded-xl flex items-center gap-2 text-sm font-bold shadow-sm transition-all cursor-pointer">
                            <i class="bi bi-plus-lg"></i> Add Product
                        </button>
                    </div>

                    <div id="emptyProducts" class="py-12 text-center border border-dashed border-gray-200 rounded-xl">
                        <i class="bi bi-basket text-gray-300 text-5xl mb-3 block"></i>
                        <p class="text-gray-500 font-medium text-sm">No products added yet. Click "Add Product" above.</p>
                    </div>

                    <div id="productsTableContainer" class="hidden overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200 text-gray-500 text-xs font-bold uppercase tracking-wider">
                                    <th class="pb-3">Product Name</th>
                                    <th class="pb-3 text-right">Price</th>
                                    <th class="pb-3 text-center">Qty / Stock</th>
                                    <th class="pb-3 text-right">Subtotal</th>
                                    <th class="pb-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody id="selectedProductsList" class="divide-y divide-gray-100 text-sm">
                                <!-- Dynamically Added Products -->
                            </tbody>
                        </table>

                        <div class="flex justify-between items-center pt-6 mt-4 border-t border-gray-200">
                            <span class="text-gray-600 font-semibold">Total Value</span>
                            <span id="totalValueDisplay" class="text-2xl font-extrabold text-[#059669]">Rs. 0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Assignments -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm space-y-6">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="bi bi-person-badge text-[#059669]"></i>
                        Assignments
                    </h2>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Supervisor</label>
                        <select id="supervisorSelect"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#059669]/40 focus:border-[#059669] text-sm bg-white font-medium shadow-sm transition-all">
                            <option value="">Select Supervisor</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Driver</label>
                        <select id="driverSelect"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#059669]/40 focus:border-[#059669] text-sm bg-white font-medium shadow-sm transition-all">
                            <option value="">Select Driver</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vehicle</label>
                        <select id="vehicleSelect" onchange="handleVehicleSelection()"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#059669]/40 focus:border-[#059669] text-sm bg-white font-medium shadow-sm transition-all">
                            <option value="">Select Vehicle</option>
                        </select>
                    </div>

                    <div id="startingMileageContainer" class="hidden">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Starting Mileage (km)</label>
                        <input type="number" id="startingMileage" step="0.01" placeholder="0.00"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#059669]/40 focus:border-[#059669] text-sm bg-white font-medium shadow-sm transition-all">
                    </div>

                    <button type="submit" id="submitBtn"
                            class="w-full bg-[#059669] hover:bg-[#047857] text-white py-4 rounded-xl flex items-center justify-center gap-2 font-bold shadow-md hover:shadow-lg transition-all transform active:scale-95 cursor-pointer">
                        <i class="bi bi-save"></i> Save Daily Load
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add Product Modal -->
<div id="productModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="closeProductModal()"></div>
    
    <!-- Modal Content -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl w-full max-w-2xl border border-gray-200 shadow-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Add Products</h3>
                <button type="button" onclick="closeProductModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="p-6">
                <!-- Search bar -->
                <div class="relative mb-6">
                    <i class="bi bi-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="modalSearch" oninput="filterModalProducts()" placeholder="Search product name or ref number..."
                           class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#059669]/40 focus:border-[#059669] text-sm bg-white font-medium shadow-sm transition-all">
                </div>

                <!-- List of Products -->
                <div class="max-h-96 overflow-y-auto divide-y divide-gray-100 pr-1" id="modalProductsList">
                    <!-- Products rendered via JS -->
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                <button type="button" onclick="closeProductModal()" 
                        class="px-5 py-2.5 border border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-100 text-sm transition-all">
                    Cancel
                </button>
                <button type="button" id="bulkAddBtn" onclick="addSelectedProducts()" disabled
                        class="px-5 py-2.5 bg-[#059669] disabled:bg-gray-300 text-white rounded-xl font-semibold hover:bg-[#047857] text-sm transition-all">
                    Add Selected (0)
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // State
    let routes = [];
    let supervisors = [];
    let drivers = [];
    let vehicles = [];
    let products = [];
    
    let selectedProducts = []; // items: { product_item_id, product_name, quantity, price, stock_quantity }
    let tempSelectedIds = new Set();

    document.addEventListener('DOMContentLoaded', () => {
        fetchInitialData();
    });

    function fetchInitialData() {
        fetch('{{ route("agent-panel.daily-loads.create-data") }}')
            .then(res => res.json())
            .then(res => {
                if (res.status) {
                    routes = res.data.routes || [];
                    supervisors = res.data.supervisors || [];
                    drivers = res.data.drivers || [];
                    vehicles = res.data.vehicles || [];
                    products = res.data.products || [];

                    populateDropdowns();

                    document.getElementById('loadingState').classList.add('hidden');
                    document.getElementById('dailyLoadForm').classList.remove('hidden');
                } else {
                    alert('Error: ' + res.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Failed to load initial data. Please try again.');
            });
    }

    function populateDropdowns() {
        // Routes dropdown
        const routeSelect = document.getElementById('routeSelect');
        routes.forEach(route => {
            const opt = document.createElement('option');
            opt.value = route.id;
            opt.textContent = `${route.route_code} — ${route.route_name} (${route.customers_count} customers)`;
            opt.dataset.supervisorId = route.sm_superviser_id || '';
            routeSelect.appendChild(opt);
        });

        // Supervisors dropdown
        const supervisorSelect = document.getElementById('supervisorSelect');
        supervisors.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.superviser_name;
            supervisorSelect.appendChild(opt);
        });

        // Drivers dropdown
        const driverSelect = document.getElementById('driverSelect');
        drivers.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = d.driver_name;
            driverSelect.appendChild(opt);
        });

        // Vehicles dropdown
        const vehicleSelect = document.getElementById('vehicleSelect');
        vehicles.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.id;
            opt.textContent = v.vehicle_number;
            vehicleSelect.appendChild(opt);
        });
    }

    function handleRouteSelection() {
        const routeSelect = document.getElementById('routeSelect');
        const selectedOption = routeSelect.options[routeSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.supervisorId) {
            const superId = selectedOption.dataset.supervisorId;
            const supervisorSelect = document.getElementById('supervisorSelect');
            supervisorSelect.value = superId;
        }
    }

    function handleVehicleSelection() {
        const vehicleSelect = document.getElementById('vehicleSelect');
        const mileageContainer = document.getElementById('startingMileageContainer');
        if (vehicleSelect.value) {
            mileageContainer.classList.remove('hidden');
        } else {
            mileageContainer.classList.add('hidden');
            document.getElementById('startingMileage').value = '';
        }
    }

    // Modal
    function openProductModal() {
        tempSelectedIds.clear();
        updateBulkAddButton();
        filterModalProducts();
        document.getElementById('productModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('modalSearch').value = '';
    }

    function filterModalProducts() {
        const query = document.getElementById('modalSearch').value.toLowerCase();
        const container = document.getElementById('modalProductsList');
        container.innerHTML = '';

        const filtered = products.filter(p => 
            p.product_name.toLowerCase().includes(query) || 
            (p.reference_number && p.reference_number.toLowerCase().includes(query))
        );

        if (filtered.length === 0) {
            container.innerHTML = `<div class="text-center py-6 text-gray-500 text-sm">No products found.</div>`;
            return;
        }

        filtered.forEach(p => {
            const isAlreadyAdded = selectedProducts.some(sp => sp.product_item_id === p.id);
            const isTempSelected = tempSelectedIds.has(p.id);

            const div = document.createElement('div');
            div.className = `flex items-center justify-between py-3.5 ${isAlreadyAdded ? 'opacity-50' : 'cursor-pointer hover:bg-gray-50 transition-colors'}`;
            if (!isAlreadyAdded) {
                div.onclick = () => toggleTempProduct(p.id);
            }

            const checkbox = isAlreadyAdded 
                ? '<i class="bi bi-check-circle-fill text-gray-400 text-lg"></i>' 
                : (isTempSelected 
                    ? '<i class="bi bi-check-circle-fill text-[#059669] text-lg"></i>' 
                    : '<i class="bi bi-circle text-gray-300 text-lg"></i>');

            div.innerHTML = `
                <div class="flex-1">
                    <div class="font-semibold text-gray-900">${p.product_name}</div>
                    <div class="text-xs text-gray-500 mt-1">
                        Ref: ${p.reference_number || 'N/A'} | Price: Rs. ${formatPrice(p.wholesale_price || p.selling_price)} | <span class="${p.stock_quantity > 0 ? 'text-[#059669]' : 'text-red-500'} font-medium">${Math.round(p.stock_quantity)} in stock</span>
                    </div>
                </div>
                <div class="flex-shrink-0 ml-4">${checkbox}</div>
            `;
            container.appendChild(div);
        });
    }

    function toggleTempProduct(id) {
        if (tempSelectedIds.has(id)) {
            tempSelectedIds.delete(id);
        } else {
            tempSelectedIds.add(id);
        }
        updateBulkAddButton();
        filterModalProducts();
    }

    function updateBulkAddButton() {
        const btn = document.getElementById('bulkAddBtn');
        btn.disabled = tempSelectedIds.size === 0;
        btn.textContent = `Add Selected (${tempSelectedIds.size})`;
    }

    function addSelectedProducts() {
        tempSelectedIds.forEach(id => {
            const product = products.find(p => p.id === id);
            if (product && !selectedProducts.some(sp => sp.product_item_id === id)) {
                selectedProducts.push({
                    product_item_id: product.id,
                    product_name: product.product_name,
                    quantity: 1,
                    price: product.wholesale_price || product.selling_price,
                    stock_quantity: product.stock_quantity
                });
            }
        });

        closeProductModal();
        renderSelectedProducts();
    }

    function removeProduct(productId) {
        selectedProducts = selectedProducts.filter(p => p.product_item_id !== productId);
        renderSelectedProducts();
    }

    function updateQty(productId, qty) {
        const item = selectedProducts.find(p => p.product_item_id === productId);
        if (item) {
            const parsed = parseFloat(qty) || 0;
            if (parsed > item.stock_quantity) {
                alert(`Stock Limit exceeded. Only ${Math.round(item.stock_quantity)} available.`);
                renderSelectedProducts();
                return;
            }
            item.quantity = parsed;
            document.getElementById(`subtotal-${productId}`).textContent = 'Rs. ' + formatPrice(item.quantity * item.price);
            updateTotalSum();
        }
    }

    function renderSelectedProducts() {
        const list = document.getElementById('selectedProductsList');
        const empty = document.getElementById('emptyProducts');
        const container = document.getElementById('productsTableContainer');

        list.innerHTML = '';

        if (selectedProducts.length === 0) {
            empty.classList.remove('hidden');
            container.classList.add('hidden');
            return;
        }

        empty.classList.add('hidden');
        container.classList.remove('hidden');

        selectedProducts.forEach(item => {
            const row = document.createElement('tr');
            row.className = 'border-b border-gray-100 hover:bg-gray-50/50 transition-colors';
            row.innerHTML = `
                <td class="py-4 font-semibold text-gray-900">${item.product_name}</td>
                <td class="py-4 text-right font-medium text-gray-600">Rs. ${formatPrice(item.price)}</td>
                <td class="py-4">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="adjustQty(${item.product_item_id}, -1)" 
                                class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-all">
                            <i class="bi bi-minus"></i>
                        </button>
                        <input type="number" step="1" min="1" max="${item.stock_quantity}" value="${item.quantity}" 
                               oninput="updateQty(${item.product_item_id}, this.value)"
                               class="w-16 text-center py-1.5 border border-gray-200 rounded-lg text-sm font-bold text-gray-900 focus:ring-1 focus:ring-[#059669] focus:border-[#059669]">
                        <button type="button" onclick="adjustQty(${item.product_item_id}, 1)"
                                class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-all">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    <div class="text-center text-[10px] text-gray-400 mt-1">Stock: ${Math.round(item.stock_quantity)}</div>
                </td>
                <td class="py-4 text-right font-bold text-gray-900" id="subtotal-${item.product_item_id}">Rs. ${formatPrice(item.quantity * item.price)}</td>
                <td class="py-4 text-right">
                    <button type="button" onclick="removeProduct(${item.product_item_id})" 
                            class="text-red-500 hover:text-red-700 w-8 h-8 rounded-lg hover:bg-red-50 transition-all flex items-center justify-center ml-auto">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            list.appendChild(row);
        });

        updateTotalSum();
    }

    function adjustQty(productId, delta) {
        const item = selectedProducts.find(p => p.product_item_id === productId);
        if (item) {
            const nextQty = Math.max(1, item.quantity + delta);
            if (nextQty > item.stock_quantity) {
                alert(`Stock Limit exceeded. Only ${Math.round(item.stock_quantity)} available.`);
                return;
            }
            item.quantity = nextQty;
            renderSelectedProducts();
        }
    }

    function updateTotalSum() {
        const sum = selectedProducts.reduce((acc, item) => acc + (item.quantity * item.price), 0);
        document.getElementById('totalValueDisplay').textContent = 'Rs. ' + formatPrice(sum);
    }

    function formatPrice(value) {
        return parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Submit
    function submitForm(event) {
        event.preventDefault();

        if (selectedProducts.length === 0) {
            alert('Please add at least one product item.');
            return;
        }

        const confirmCreate = confirm('Are you sure you want to create this daily load?');
        if (!confirmCreate) return;

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent mr-2"></div> Creating...';

        const payload = {
            route_id: document.getElementById('routeSelect').value,
            supervisor_id: document.getElementById('supervisorSelect').value || null,
            driver_id: document.getElementById('driverSelect').value || null,
            vehicle_id: document.getElementById('vehicleSelect').value || null,
            load_date: document.getElementById('loadDate').value,
            starting_mileage: document.getElementById('startingMileage').value || null,
            notes: document.getElementById('notes').value.trim() || null,
            items: selectedProducts.map(p => ({
                product_item_id: p.product_item_id,
                quantity: p.quantity,
                price: p.price
            }))
        };

        fetch('{{ route("agent-panel.daily-loads.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(res => {
            if (res.status) {
                alert('Daily load created successfully.');
                window.location.href = '{{ route("agent-panel.daily-loads") }}';
            } else {
                alert('Failed: ' + res.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-save"></i> Save Daily Load';
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-save"></i> Save Daily Load';
        });
    }
</script>
@endsection
