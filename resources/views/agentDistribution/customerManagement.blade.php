@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#EDEFF5]" id="customer-management-app">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#F59E0B] to-[#D97706] rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="bi bi-people-fill text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Customer Management</h1>
                            <p class="text-gray-500 text-xs sm:text-sm">Manage B2B and B2C customers with location tracking
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1">
                        <button onclick="setView('list')" id="btn-view-list"
                            class="px-3 py-1.5 rounded bg-white shadow text-gray-800">
                            <i class="bi bi-list"></i>
                        </button>
                        <button onclick="setView('map')" id="btn-view-map"
                            class="px-3 py-1.5 rounded text-gray-600 hover:bg-white/50">
                            <i class="bi bi-map"></i>
                        </button>
                    </div>
                    <button onclick="openModal()"
                        class="px-4 py-2 bg-[#D4A017] hover:bg-[#B8860B] text-white rounded-lg flex items-center shadow-sm">
                        <i class="bi bi-plus-lg mr-2"></i> Add Customer
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6 max-w-[1800px] mx-auto">
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-500 text-xs font-bold uppercase">Total Customers</span>
                        <i class="bi bi-building text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900" id="stat-total">0</p>
                    <p class="text-xs text-gray-500 mt-1">B2B: <span id="stat-b2b">0</span> | B2C: <span
                            id="stat-b2c">0</span>
                    </p>
                </div>
                <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-500 text-xs font-bold uppercase">Assigned</span>
                        <i class="bi bi-check-circle text-green-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900" id="stat-assigned">0</p>
                    <p class="text-xs text-gray-500 mt-1">Unassigned: <span id="stat-unassigned">0</span></p>
                </div>
                <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-500 text-xs font-bold uppercase">Total Balance</span>
                        <i class="bi bi-currency-dollar text-orange-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900" id="stat-balance">Rs. 0</p>
                    <p class="text-xs text-gray-500 mt-1"><span id="stat-debtors">0</span> customers owing</p>
                </div>
                <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-500 text-xs font-bold uppercase">Overdue</span>
                        <i class="bi bi-exclamation-circle text-red-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900" id="stat-overdue">0</p>
                    <p class="text-xs text-gray-500 mt-1">Need attention</p>
                </div>
            </div>

            <!-- B2B Breakdown -->
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm mb-6">
                <h3 class="text-sm font-bold text-gray-900 mb-3">B2B Customer Types</h3>
                <div class="flex flex-wrap gap-3" id="b2b-types-container">
                    <!-- Injected JS -->
                </div>
            </div>

            <!-- Filters -->
            <div class="p-4 bg-white border border-gray-200 rounded-xl shadow-sm mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="lg:col-span-1 relative">
                        <i class="bi bi-search absolute left-3 top-3.5 text-gray-400"></i>
                        <input type="text" id="filter-search" onkeyup="renderList()" placeholder="Search customers..."
                            class="w-full pl-9 p-2.5 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <select id="filter-type" onchange="renderList()"
                        class="p-2.5 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="all">All Types</option>
                        <option value="b2b">B2B</option>
                        <option value="b2c">B2C</option>
                    </select>
                    <select id="filter-agent" onchange="renderList()"
                        class="p-2.5 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="all">All Agents</option>
                        @foreach($agents as $a)
                            <option value="{{ $a['id'] }}">{{ $a['agentName'] }}</option>
                        @endforeach
                    </select>
                    <select id="filter-route" onchange="renderList()"
                        class="p-2.5 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="all">All Routes</option>
                        @foreach($routes as $r)
                            <option value="{{ $r['id'] }}">{{ $r['routeName'] }}</option>
                        @endforeach
                    </select>
                    <select id="filter-status" onchange="renderList()"
                        class="p-2.5 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- List View -->
            <div id="view-list" class="grid grid-cols-1 gap-4">
                <!-- Injected list -->
            </div>

            <!-- Map View -->
            <div id="view-map" class="hidden">
                <div
                    class="bg-gray-100 border border-gray-300 rounded-xl h-[600px] flex items-center justify-center relative overflow-hidden">
                    <!-- Mock Map -->
                    <div class="absolute inset-0 bg-blue-100 opacity-20"
                        style="background-image: repeating-linear-gradient(45deg, #e5e7eb 25%, transparent 25%, transparent 75%, #e5e7eb 75%, #e5e7eb), repeating-linear-gradient(45deg, #e5e7eb 25%, #f3f4f6 25%, #f3f4f6 75%, #e5e7eb 75%, #e5e7eb); background-position: 0 0, 10px 10px; background-size: 20px 20px;">
                    </div>
                    <div class="z-10 text-center p-8 bg-white/90 backdrop-blur rounded-xl shadow-lg border border-gray-200">
                        <i class="bi bi-geo-alt-fill text-red-500 text-4xl mb-3 block"></i>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Map Integration Placeholder</h3>
                        <p class="text-gray-600 mb-4">Google Maps or Leaflet would render here showing customer locations.
                        </p>
                        <div class="flex gap-4 justify-center text-sm">
                            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                Assigned
                            </div>
                            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span>
                                Unassigned</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="no-results" class="hidden p-12 text-center bg-white rounded-xl border border-gray-200">
                <i class="bi bi-people text-gray-300 text-6xl mb-4 block"></i>
                <h3 class="text-gray-900 font-medium mb-1">No Customers Found</h3>
                <p class="text-gray-500">Try adjusting your filters.</p>
            </div>
        </div>
    </div>

    <!-- Include Create Customer Modal -->
    @include('agentDistribution.Modals.createCustomer')

    <script>
        const customers = @json($customers ?? []);
        const agents = @json($agents ?? []);
        const routes = @json($routes ?? []);
        const b2bTypesList = @json($b2bTypes ?? []);

        const state = {
            customers: customers,
            filtered: customers
        };

        document.addEventListener('DOMContentLoaded', () => {
            renderList();
            renderB2BStats();
            updateMainStats();
        });

        function setView(mode) {
            document.getElementById('view-list').classList.toggle('hidden', mode !== 'list');
            document.getElementById('view-map').classList.toggle('hidden', mode !== 'map');

            // Update button styles...
            const activeClass = 'bg-white shadow text-gray-800';
            const inactiveClass = 'text-gray-600 hover:bg-white/50';

            document.getElementById('btn-view-list').className = `px-3 py-1.5 rounded ${mode === 'list' ? activeClass : inactiveClass}`;
            document.getElementById('btn-view-map').className = `px-3 py-1.5 rounded ${mode === 'map' ? activeClass : inactiveClass}`;
        }

        function renderList() {
            // Filters
            const search = document.getElementById('filter-search').value.toLowerCase();
            const type = document.getElementById('filter-type').value;
            const agentId = document.getElementById('filter-agent').value;
            const routeId = document.getElementById('filter-route').value;
            const status = document.getElementById('filter-status').value;

            // Apply
            const filtered = state.customers.filter(c => {
                const mSearch = c.businessName.toLowerCase().includes(search) ||
                    c.contact.contactPerson.toLowerCase().includes(search);
                const mType = type === 'all' || c.customerType === type;
                const mAgent = agentId === 'all' || c.assignedAgentId == agentId;
                const mRoute = routeId === 'all' || c.assignedRouteId == routeId;
                const mStatus = status === 'all' || c.status === status;

                return mSearch && mType && mAgent && mRoute && mStatus;
            });

            // Update UI
            const container = document.getElementById('view-list');
            const empty = document.getElementById('no-results');

            if (filtered.length === 0) {
                container.innerHTML = '';
                empty.classList.remove('hidden');
            } else {
                empty.classList.add('hidden');
                container.innerHTML = filtered.map(c => buildCard(c)).join('');
            }

            state.filtered = filtered;
            // Optional: Update stats based on filter? kept global for now
        }

        function buildCard(c) {
            const agent = agents.find(a => a.id == c.assignedAgentId);
            const route = routes.find(r => r.id == c.assignedRouteId);

            const b2bBadgeColor = {
                wholesale: 'bg-purple-100 text-purple-800',
                retail_shop: 'bg-blue-100 text-blue-800',
                restaurant: 'bg-green-100 text-green-800',
                hotel: 'bg-orange-100 text-orange-800',
                agent: 'bg-indigo-100 text-indigo-800',
                other: 'bg-gray-100 text-gray-800'
            }[c.b2bType] || 'bg-gray-100 text-gray-800';

            // Serialize object for onclick (careful with quotes)
            const json = JSON.stringify(c).replace(/"/g, '&quot;');

            return `
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer" onclick="window.location.href='/distributor-customer-management/${c.id}'">
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <h3 class="text-gray-900 font-bold text-lg">${c.businessName}</h3>
                                <span class="px-2 py-0.5 rounded text-xs font-bold uppercase ${c.customerType === 'b2b' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'}">
                                    ${c.customerType}
                                </span>
                                ${c.b2bType ? `<span class="px-2 py-0.5 rounded text-xs font-medium capitalize ${b2bBadgeColor}">${c.b2bType.replace('_', ' ')}</span>` : ''}
                                ${c.status === 'inactive' ? `<span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-xs">Inactive</span>` : ''}
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-3 text-sm text-gray-600">
                                 <div class="flex items-center gap-2"><i class="bi bi-person"></i> ${c.contact.contactPerson}</div>
                                 <div class="flex items-center gap-2"><i class="bi bi-telephone"></i> ${c.contact.phoneNumber}</div>
                                 ${c.contact.email ? `<div class="flex items-center gap-2"><i class="bi bi-envelope"></i> ${c.contact.email}</div>` : ''}
                            </div>

                            <div class="flex items-start gap-2 text-sm text-gray-600 mb-3">
                                <i class="bi bi-geo-alt mt-0.5"></i>
                                <span>${c.location.address}, ${c.location.city}</span>
                                ${c.isVerified ? '<i class="bi bi-patch-check-fill text-green-500" title="Verified Location"></i>' : ''}
                            </div>

                             <div class="flex flex-wrap gap-3 text-xs mt-auto">
                                ${c.assignedAgentId
                    ? `<span class="px-2 py-1 bg-gray-50 rounded border border-gray-200"><i class="bi bi-person-badge mr-1"></i> Agent: <b>${agent ? agent.agentName : 'Unknown'}</b></span>`
                    : `<span class="px-2 py-1 bg-yellow-50 text-yellow-800 rounded border border-yellow-200">Unassigned Agent</span>`
                }
                                ${c.assignedRouteId
                    ? `<span class="px-2 py-1 bg-gray-50 rounded border border-gray-200"><i class="bi bi-signpost-split mr-1"></i> Route: <b>${route ? route.routeName : 'Unknown'}</b></span>`
                    : ''
                }
                             </div>
                        </div>

                        <div class="flex flex-col items-end gap-3 min-w-[200px]">
                            <div class="text-right">
                                 ${c.currentBalance > 0
                    ? `
                                        <div class="text-xs text-gray-500">Balance</div>
                                        <div class="text-lg font-bold text-gray-900">Rs. ${parseFloat(c.currentBalance).toLocaleString()}</div>
                                        ${c.creditDays > 30 ? `<div class="text-xs text-red-600 font-medium">Overdue by ${c.creditDays - 30} days</div>` : ''}
                                      `
                    : `<div class="text-sm text-green-600 font-medium">No Balance</div>`
                }
                            </div>

                            <div class="text-right text-xs text-gray-500">
                                <div>${c.totalOrders} orders</div>
                                <div>Rs. ${parseFloat(c.totalSales).toLocaleString()} sales</div>
                            </div>

                            <div class="flex items-center gap-2 mt-auto">
                                <button onclick="event.stopPropagation(); openCustomerModal(JSON.parse('${json}'))" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded border border-gray-200"><i class="bi bi-pencil"></i></button>
                                <button onclick="event.stopPropagation(); confirmDelete('${c.id}')" class="p-1.5 text-red-500 hover:bg-red-50 rounded border border-gray-200"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderB2BStats() {
            const counts = {};
            state.customers.forEach(c => {
                if (c.b2bType) counts[c.b2bType] = (counts[c.b2bType] || 0) + 1;
            });

            const badges = b2bTypesList.map(type => {
                const count = counts[type.id] || 0;
                if (count === 0 && type.id === 'other') return ''; // Optional: hide 'other' if 0? Or keep all. Keeping all for now as requested "load from CommonVariables".
                // Actually, user likely wants to see what's available or just populated ones but with correct tags. 
                // If I show all 0s it might clutter. But "load tags from CommonVariables" usually implies showing the catalog.
                // I'll show all.

                const color = {
                    wholesale: 'bg-purple-100 text-purple-800',
                    retail_shop: 'bg-blue-100 text-blue-800',
                    restaurant: 'bg-green-100 text-green-800',
                    hotel: 'bg-orange-100 text-orange-800',
                    agent: 'bg-indigo-100 text-indigo-800',
                    other: 'bg-gray-100 text-gray-800'
                }[type.id] || 'bg-gray-100 text-gray-800';

                return `
                    <div class="flex items-center gap-2 bg-white border border-gray-200 px-3 py-1.5 rounded-lg shadow-sm">
                        <span class="text-xs font-bold uppercase rounded px-1.5 py-0.5 ${color}">${type.label}</span>
                        <span class="text-sm font-medium text-gray-700">${count}</span>
                    </div>
                `;
            }).join('');

            document.getElementById('b2b-types-container').innerHTML = badges;
        }

        function updateMainStats() {
            const list = state.customers;
            document.getElementById('stat-total').textContent = list.length;
            document.getElementById('stat-b2b').textContent = list.filter(c => c.customerType === 'b2b').length;
            document.getElementById('stat-b2c').textContent = list.filter(c => c.customerType === 'b2c').length;
            document.getElementById('stat-assigned').textContent = list.filter(c => c.assignedAgentId).length;
            document.getElementById('stat-unassigned').textContent = list.filter(c => !c.assignedAgentId).length;
            const balance = list.reduce((sum, c) => sum + parseFloat(c.currentBalance), 0);
            document.getElementById('stat-balance').textContent = 'Rs. ' + balance.toLocaleString();
            document.getElementById('stat-debtors').textContent = list.filter(c => c.currentBalance > 0).length;
            document.getElementById('stat-overdue').textContent = list.filter(c => c.creditDays > 30).length;
        }

        // Proxy Open (for the top Add button)
        function openModal() {
            openCustomerModal();
        }

        function confirmDelete(id) {
            if (!id) return;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // API Call
                    fetch(`/api/customers/${id}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove locally
                                state.customers = state.customers.filter(c => c.id != id);
                                renderList();
                                updateMainStats(); // Update stats
                                Swal.fire('Deleted!', 'Customer has been deleted.', 'success');
                            } else {
                                Swal.fire('Error', data.message || 'Failed to delete', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'An error occurred while deleting', 'error');
                        });
                }
            });
        }

        // --- Customer Modal Logic ---
        let currentCustomer = null;
        const weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        function openCustomerModal(customer = null) {
            currentCustomer = customer;
            const modal = document.getElementById('customer-modal');
            const form = document.getElementById('customer-form');

            // Reset form
            form.reset();
            clearLocation();

            if (customer) {
                // Edit Mode
                document.getElementById('modal-title').textContent = 'Edit Customer';
                document.getElementById('modal-subtitle').textContent = 'Update customer details';
                document.getElementById('cust-id').value = customer.id;

                setCustomerType(customer.customerType);
                if (customer.customerType === 'b2b') {
                    document.getElementById('cust-b2b-type').value = customer.b2bType || 'retail_shop';
                }

                document.getElementById('cust-name').value = customer.businessName;
                document.getElementById('cust-trade-name').value = customer.tradeName || '';
                document.getElementById('cust-contact').value = customer.contact.contactPerson;
                document.getElementById('cust-phone').value = customer.contact.phoneNumber;
                document.getElementById('cust-email').value = customer.contact.email || '';

                // Location
                if (customer.location) {
                    document.getElementById('final-address').value = customer.location.address;
                    document.getElementById('final-city').value = customer.location.city;
                    document.getElementById('final-district').value = customer.location.district;
                    document.getElementById('final-lat').value = customer.location.latitude;
                    document.getElementById('final-lng').value = customer.location.longitude;

                    document.getElementById('loc-address').textContent = customer.location.address;
                    document.getElementById('loc-city').textContent = customer.location.city;
                    document.getElementById('loc-district').textContent = customer.location.district;
                    document.getElementById('loc-coords').textContent = `Lat: ${customer.location.latitude?.toFixed(4)}, Lng: ${customer.location.longitude?.toFixed(4)}`;
                    document.getElementById('loc-preview').classList.remove('hidden');
                }

                document.getElementById('cust-agent').value = customer.assignedAgentId || '';
                document.getElementById('cust-route').value = customer.assignedRouteId || '';
                document.getElementById('cust-sequence').value = customer.stopSequence || '';

                if (customer.creditTerms) {
                    document.getElementById('allow-credit').checked = customer.creditTerms.allowCredit;
                    document.getElementById('cust-limit').value = customer.creditTerms.creditLimit;
                    document.getElementById('cust-terms').value = customer.creditTerms.paymentTermsDays;
                } else {
                    document.getElementById('allow-credit').checked = false;
                }
                toggleCredit();

                // Visit Schedule
                document.getElementById('cust-frequency').value = customer.visitSchedule?.frequency || 'weekly';
                document.getElementById('cust-pref-time').value = customer.visitSchedule?.preferredTime || '';
                const days = customer.visitSchedule?.preferredDays || [];
                document.getElementById('cust-pref-days').value = JSON.stringify(days);
                renderPreferredDays(days);

                // Additional Info
                document.getElementById('cust-special-instr').value = customer.specialInstructions || '';
                document.getElementById('cust-delivery-instr').value = customer.deliveryInstructions || '';
                document.getElementById('cust-notes').value = customer.notes || '';

            } else {
                // Create Mode
                document.getElementById('modal-title').textContent = 'Add New Customer';
                document.getElementById('modal-subtitle').textContent = 'Create a new B2B or B2C customer';
                document.getElementById('cust-id').value = '';
                setCustomerType('b2b'); // Default
                document.getElementById('allow-credit').checked = true;
                toggleCredit();

                // Default Visit Schedule
                document.getElementById('cust-frequency').value = 'weekly';
                document.getElementById('cust-pref-days').value = '[]';
                renderPreferredDays([]);
            }

            modal.classList.remove('hidden');
        }

        function closeCustomerModal() {
            document.getElementById('customer-modal').classList.add('hidden');
            currentCustomer = null;
        }

        function setCustomerType(type) {
            document.getElementById('cust-type').value = type;

            const b2bBtn = document.getElementById('type-b2b');
            const b2cBtn = document.getElementById('type-b2c');
            const creditSection = document.getElementById('section-credit');
            const b2bTypeSection = document.getElementById('section-b2b-type');

            if (type === 'b2b') {
                b2bBtn.classList.add('border-[#D4A017]', 'bg-[#D4A017]/5');
                b2bBtn.classList.remove('border-gray-200');
                b2bBtn.querySelector('.bi-check-circle-fill').parentNode.classList.remove('hidden');

                b2cBtn.classList.remove('border-[#D4A017]', 'bg-[#D4A017]/5');
                b2cBtn.classList.add('border-gray-200');
                b2cBtn.querySelector('.bi-check-circle-fill').parentNode.classList.add('hidden');

                creditSection.classList.remove('hidden');
                b2bTypeSection.classList.remove('hidden');
            } else {
                b2cBtn.classList.add('border-[#D4A017]', 'bg-[#D4A017]/5');
                b2cBtn.classList.remove('border-gray-200');
                b2cBtn.querySelector('.bi-check-circle-fill').parentNode.classList.remove('hidden');

                b2bBtn.classList.remove('border-[#D4A017]', 'bg-[#D4A017]/5');
                b2bBtn.classList.add('border-gray-200');
                b2bBtn.querySelector('.bi-check-circle-fill').parentNode.classList.add('hidden');

                creditSection.classList.add('hidden');
                b2bTypeSection.classList.add('hidden');
            }
        }

        function toggleCredit() {
            const allowed = document.getElementById('allow-credit').checked;
            const fields = document.getElementById('credit-fields');
            if (allowed) {
                fields.classList.remove('hidden', 'opacity-50', 'pointer-events-none');
            } else {
                fields.classList.add('hidden');
            }
        }

        function togglePreferredDay(day) {
            const input = document.getElementById('cust-pref-days');
            let days = JSON.parse(input.value || '[]');

            if (days.includes(day)) {
                days = days.filter(d => d !== day);
            } else {
                days.push(day);
            }
            input.value = JSON.stringify(days);
            renderPreferredDays(days);
        }

        function renderPreferredDays(days) {
            weekDays.forEach(day => {
                const btn = document.getElementById('btn-' + day);
                if (days.includes(day)) {
                    btn.classList.add('bg-[#D4A017]', 'text-white');
                    btn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                } else {
                    btn.classList.remove('bg-[#D4A017]', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                }
            });
        }

        // Address Mock Logic
        function handleAddressInput() {
            const val = document.getElementById('loc-search').value;
            const btn = document.getElementById('manual-loc-btn');
            if (val.length > 5) {
                btn.classList.remove('hidden');
            } else {
                btn.classList.add('hidden');
            }
        }

        function useManualLocation() {
            const val = document.getElementById('loc-search').value;
            if (!val) return;

            // Mock Geo
            document.getElementById('final-address').value = val;
            document.getElementById('final-city').value = 'Colombo'; // Default mock
            document.getElementById('final-district').value = 'Western';
            document.getElementById('final-lat').value = (6.9271 + (Math.random() - 0.5) * 0.01).toFixed(6);
            document.getElementById('final-lng').value = (79.8612 + (Math.random() - 0.5) * 0.01).toFixed(6);

            document.getElementById('loc-address').textContent = val;
            document.getElementById('loc-city').textContent = 'Colombo';
            document.getElementById('loc-district').textContent = 'Western';
            document.getElementById('loc-coords').textContent = 'Lat/Lng: (Mocked)';

            document.getElementById('loc-preview').classList.remove('hidden');
            document.getElementById('loc-search').value = '';
            document.getElementById('manual-loc-btn').classList.add('hidden');
        }

        function clearLocation() {
            document.getElementById('final-address').value = '';
            document.getElementById('final-city').value = '';
            document.getElementById('final-district').value = '';
            document.getElementById('final-lat').value = '';
            document.getElementById('final-lng').value = '';
            document.getElementById('loc-preview').classList.add('hidden');
        }

        function saveCustomer() {
            // Basic Validation
            const name = document.getElementById('cust-name').value;
            const contact = document.getElementById('cust-contact').value;
            const phone = document.getElementById('cust-phone').value;

            if (!name || !contact || !phone) {
                Swal.fire('Error', 'Please fill in all required fields marked with *', 'error');
                return;
            }

            const data = {
                id: document.getElementById('cust-id').value || 'new_' + Date.now(),
                customerType: document.getElementById('cust-type').value,
                businessName: name,
                tradeName: document.getElementById('cust-trade-name').value,
                b2bType: document.getElementById('cust-type').value === 'b2b' ? document.getElementById('cust-b2b-type').value : null,
                contact: {
                    contactPerson: contact,
                    phoneNumber: phone,
                    email: document.getElementById('cust-email').value
                },
                location: {
                    address: document.getElementById('final-address').value || 'Unknown Address',
                    city: document.getElementById('final-city').value || 'Unknown',
                    district: document.getElementById('final-district').value || 'Unknown',
                    latitude: document.getElementById('final-lat').value || 0,
                    longitude: document.getElementById('final-lng').value || 0
                },
                assignedAgentId: document.getElementById('cust-agent').value,
                assignedRouteId: document.getElementById('cust-route').value,
                stopSequence: document.getElementById('cust-sequence').value,
                status: 'active', // Default
                currentBalance: 0, // Default
                // Mock defaults for display
                totalOrders: 0,
                totalSales: 0,
                creditDays: 0,

                creditTerms: {
                    allowCredit: document.getElementById('allow-credit').checked,
                    creditLimit: document.getElementById('cust-limit').value || 0,
                    paymentTermsDays: document.getElementById('cust-terms').value || 0
                },

                // New Fields
                visitSchedule: {
                    frequency: document.getElementById('cust-frequency').value,
                    preferredDays: JSON.parse(document.getElementById('cust-pref-days').value),
                    preferredTime: document.getElementById('cust-pref-time').value
                },
                specialInstructions: document.getElementById('cust-special-instr').value,
                deliveryInstructions: document.getElementById('cust-delivery-instr').value,
                notes: document.getElementById('cust-notes').value
            };

            if (document.getElementById('cust-type').value === 'b2b' && document.getElementById('allow-credit').checked) {
                data.creditTerms = {
                    creditLimit: document.getElementById('cust-limit').value,
                    paymentTermsDays: document.getElementById('cust-terms').value,
                    allowCredit: true
                };
            } else {
                data.creditTerms = null;
            }

            // API Call
            const url = currentCustomer
                ? `/api/customers/${currentCustomer.id}/update`
                : '/api/customers/create';

            const method = currentCustomer ? 'PUT' : 'POST';

            // Show loading
            const submitBtn = document.querySelector("button[onclick='saveCustomer()']");
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
            submitBtn.disabled = true;

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(result => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: result.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            closeCustomerModal();
                            window.location.reload(); // Reload to fetch fresh data
                        });
                    } else {
                        Swal.fire('Error', result.message || 'An error occurred', 'error');
                    }
                })
                .catch(error => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    console.error('Error:', error);
                    Swal.fire('Error', 'Network error or server failed', 'error');
                });
        }
    </script>

    {{-- Google Maps Places API --}}
    <script>
        // Initialize Google Maps Autocomplete
        let autocomplete;
        let geocoder;

        function initGoogleMaps() {
            const input = document.getElementById('loc-search');

            if (!window.google || !window.google.maps) {
                console.error('Google Maps API not loaded');
                return;
            }

            // Initialize Autocomplete
            autocomplete = new google.maps.places.Autocomplete(input, {
                componentRestrictions: { country: 'lk' }, // Restrict to Sri Lanka
                fields: ['address_components', 'geometry', 'formatted_address', 'name']
            });

            // Initialize Geocoder
            geocoder = new google.maps.Geocoder();

            // Listen for place selection
            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();

                if (!place.geometry) {
                    console.log('No details available for: ' + place.name);
                    return;
                }

                // Extract address components
                let address = place.formatted_address || place.name;
                let city = '';
                let district = '';
                let lat = place.geometry.location.lat();
                let lng = place.geometry.location.lng();

                // Parse address components
                if (place.address_components) {
                    for (let component of place.address_components) {
                        if (component.types.includes('locality')) {
                            city = component.long_name;
                        }
                        if (component.types.includes('administrative_area_level_2')) {
                            district = component.long_name;
                        }
                    }
                }

                // Update UI
                showLocationPreview(address, city, district, lat, lng);
            });
        }

        function showLocationPreview(address, city, district, lat, lng) {
            document.getElementById('loc-preview').classList.remove('hidden');
            document.getElementById('loc-address').innerText = address;
            document.getElementById('loc-city').innerText = city || 'Unknown';
            document.getElementById('loc-district').innerText = district || 'Unknown';
            document.getElementById('loc-coords').innerText = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;

            // Set hidden fields
            document.getElementById('final-address').value = address;
            document.getElementById('final-city').value = city;
            document.getElementById('final-district').value = district;
            document.getElementById('final-lat').value = lat;
            document.getElementById('final-lng').value = lng;
        }

        function clearLocation() {
            document.getElementById('loc-preview').classList.add('hidden');
            document.getElementById('loc-search').value = '';
            document.getElementById('final-address').value = '';
            document.getElementById('final-city').value = '';
            document.getElementById('final-district').value = '';
            document.getElementById('final-lat').value = '';
            document.getElementById('final-lng').value = '';
        }

        function handleAddressInput() {
            const input = document.getElementById('loc-search').value;
            // Show manual location button if user is typing
            if (input.length > 3) {
                document.getElementById('manual-loc-btn').classList.remove('hidden');
            } else {
                document.getElementById('manual-loc-btn').classList.add('hidden');
            }
        }

        function useManualLocation() {
            const address = document.getElementById('loc-search').value;
            if (!address || !geocoder) return;

            geocoder.geocode({ address: address + ', Sri Lanka' }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    const place = results[0];
                    let city = '';
                    let district = '';
                    let lat = place.geometry.location.lat();
                    let lng = place.geometry.location.lng();

                    // Parse address components
                    for (let component of place.address_components) {
                        if (component.types.includes('locality')) {
                            city = component.long_name;
                        }
                        if (component.types.includes('administrative_area_level_2')) {
                            district = component.long_name;
                        }
                    }

                    showLocationPreview(place.formatted_address, city, district, lat, lng);
                    document.getElementById('manual-loc-btn').classList.add('hidden');
                } else {
                    alert('Could not find location. Please try a different address.');
                }
            });
        }

        // Load Google Maps API dynamically
        function loadGoogleMapsAPI() {
            // IMPORTANT: Replace YOUR_API_KEY with your actual Google Maps API key
            const apiKey = 'AIzaSyDyD-Z0FCjm3sgq4hhNqgZfhiKjOWNuuXw';
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&callback=initGoogleMaps`;
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function () {
            loadGoogleMapsAPI();
        });
    </script>
@endsection