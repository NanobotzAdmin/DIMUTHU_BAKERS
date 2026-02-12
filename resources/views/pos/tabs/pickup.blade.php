<div class="p-6 border-b border-gray-200">
    <h2 class="text-xl text-gray-900 mb-4">Online Order Pickup</h2>

    <div class="relative">
        <div class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
        </div>
        <input type="text" id="pickup-search" placeholder="Search by order #, name, or phone..."
            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
    </div>

    <div class="mt-4 grid grid-cols-4 gap-3" id="pickup-stats">
    </div>
</div>

<div id="pickup-orders-list" class="flex-1 overflow-y-auto p-6 space-y-4">
</div>

<!-- Modal for Pickup Payment -->
<div id="pickup-payment-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl text-gray-900">Process Payment</h3>
            <button onclick="closePickupModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div class="mb-6">
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <div class="text-sm text-gray-600 mb-1">Order Number</div>
                <div id="modal-order-number" class="text-lg text-gray-900 font-medium">--</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <div class="text-sm text-gray-600 mb-1">Customer</div>
                <div id="modal-customer-name" class="text-lg text-gray-900 font-medium">--</div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="text-sm text-purple-700 mb-1">Amount to Collect</div>
                <div id="modal-total-amount" class="text-3xl text-purple-900 font-bold">Rs 0.00</div>
            </div>
        </div>

        <div class="space-y-3">
            <button onclick="processPickupPayment('cash')"
                class="w-full px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors flex items-center justify-center gap-2 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="12" x="2" y="6" rx="2" />
                    <circle cx="12" cy="12" r="2" />
                    <path d="M6 12h.01M18 12h.01" />
                </svg>
                Collect Cash Payment
            </button>
            <button onclick="processPickupPayment('card')"
                class="w-full px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="14" x="2" y="5" rx="2" />
                    <line x1="2" x2="22" y1="10" y2="10" />
                </svg>
                Process Card Payment
            </button>
            <button onclick="closePickupModal()"
                class="w-full px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors font-medium">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    // Pickup Logic
    var onlineOrders = []; // Global scope for now
    var selectedOrderIdForPayment = null;

    // Init
    $(document).ready(function () {
        fetchPickupData();
        document.getElementById('pickup-search').addEventListener('keyup', renderOrders);
    });

    function fetchPickupData() {
        $.ajax({
            url: "{{ route('pos.pickup') }}",
            method: 'GET',
            success: function (response) {
                onlineOrders = response;
                renderPickupStats();
                renderOrders();
            },
            error: function (err) {
                console.error('Error loading pickup orders', err);
                toastr.error('Failed to load pickup orders');
            }
        });
    }

    function getStatusColorClass(status) {
        switch (status) {
            case 'pending': return 'bg-yellow-100 text-yellow-800 border-yellow-200';
            case 'confirmed': return 'bg-blue-100 text-blue-800 border-blue-200';
            case 'preparing': return 'bg-purple-100 text-purple-800 border-purple-200';
            case 'ready': return 'bg-green-100 text-green-800 border-green-200';
            default: return 'bg-gray-100 text-gray-800 border-gray-200';
        }
    }

    function renderPickupStats() {
        const stats = {
            pending: onlineOrders.filter(o => o.status === 'pending').length,
            confirmed: onlineOrders.filter(o => o.status === 'confirmed').length,
            preparing: onlineOrders.filter(o => o.status === 'preparing').length,
            ready: onlineOrders.filter(o => o.status === 'ready').length
        };

        const html = `
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                <div class="text-xs text-yellow-700">Pending</div>
                <div class="text-2xl text-yellow-900">${stats.pending}</div>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <div class="text-xs text-blue-700">Confirmed</div>
                <div class="text-2xl text-blue-900">${stats.confirmed}</div>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                <div class="text-xs text-purple-700">Preparing</div>
                <div class="text-2xl text-purple-900">${stats.preparing}</div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                <div class="text-xs text-green-700">Ready</div>
                <div class="text-2xl text-green-900">${stats.ready}</div>
            </div>
        `;
        document.getElementById('pickup-stats').innerHTML = html;
    }

    function renderOrders() {
        const searchTerm = document.getElementById('pickup-search').value.toLowerCase();
        const container = document.getElementById('pickup-orders-list');
        container.innerHTML = '';

        const filtered = onlineOrders.filter(order => {
            if (['completed', 'cancelled', 'refunded'].includes(order.status)) return false;

            return order.orderNumber.toLowerCase().includes(searchTerm) ||
                order.customer.name.toLowerCase().includes(searchTerm) ||
                order.customer.phone.toLowerCase().includes(searchTerm);
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-gray-300 mx-auto mb-4"><i class="bi bi-box-seam text-6xl"></i></div>
                    <p class="text-gray-500">${searchTerm ? 'No orders found' : 'No pending online orders'}</p>
                </div>`;
            return;
        }

        filtered.forEach(order => {
            let paymentBadge = '';
            if (order.payment.method === 'online') paymentBadge = 'Paid Online';
            else if (order.payment.method === 'cash-on-pickup') paymentBadge = 'Cash';
            else paymentBadge = 'Card';

            const isPaid = order.payment.status === 'paid';

            const itemsHtml = order.items.map(item => `
                <div class="flex justify-between text-sm">
                    <span class="text-gray-700">${item.quantity}x ${item.productName}</span>
                    <span class="text-gray-900">Rs ${item.subtotal.toFixed(2)}</span>
                </div>
            `).join('');

            let actionBtn = '';
            if (order.status === 'pending') {
                actionBtn = `<button onclick="updatePickupStatus('${order.id}', 'confirmed')" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">Confirm Order</button>`;
            } else if (order.status === 'confirmed') {
                actionBtn = `<button onclick="updatePickupStatus('${order.id}', 'ready')" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm">Mark as Ready</button>`;
            } else if (order.status === 'preparing' || order.status === 'ready') {
                actionBtn = `<button onclick="initiatePickupCompletion('${order.id}')" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2 text-sm"><i class="bi bi-check-circle"></i> Complete Pickup</button>`;
            }

            const card = `
                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg text-gray-900 font-semibold">${order.orderNumber}</h3>
                                <span class="px-2 py-1 text-xs rounded-lg border font-medium ${getStatusColorClass(order.status)}">${order.status.toUpperCase()}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">${new Date(order.createdAt).toLocaleTimeString()} • ${order.pickup.scheduledTime}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl text-gray-900 font-bold">Rs ${order.summary.total.toFixed(2)}</div>
                            <div class="flex items-center justify-end gap-1 text-sm text-gray-600 mt-1">
                                <span>${paymentBadge}</span>
                                ${isPaid ? '<span class="text-green-600 ml-1"><i class="bi bi-check-circle-fill"></i></span>' : ''}
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 p-3 bg-gray-50 rounded-lg text-sm">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex items-center gap-2"><i class="bi bi-person text-gray-400"></i> ${order.customer.name}</div>
                            <div class="flex items-center gap-2"><i class="bi bi-telephone text-gray-400"></i> ${order.customer.phone}</div>
                        </div>
                    </div>

                    <div class="mb-3 space-y-1 border-t border-b border-gray-100 py-2">
                        ${itemsHtml}
                    </div>

                    ${(order.customerNotes) ? `
                        <div class="mb-3 p-2 bg-blue-50 border border-blue-200 rounded-lg flex gap-2 items-start">
                            <i class="bi bi-exclamation-circle text-blue-600 mt-0.5"></i>
                            <div class="text-sm text-blue-900">${order.customerNotes}</div>
                        </div>` : ''
                }

                    <div class="flex gap-2">
                        ${actionBtn}
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', card);
        });
    }

    function updatePickupStatus(orderId, newStatus) {
        const order = onlineOrders.find(o => o.id === orderId);
        if (order) {
            order.status = newStatus;
            // AJAX call to update status would go here
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                Toast.fire({ icon: 'success', title: `Order ${newStatus}` });
            }
            renderOrders();
            renderPickupStats();
        }
    }

    function initiatePickupCompletion(orderId) {
        const order = onlineOrders.find(o => o.id === orderId);
        if (!order) return;

        if (order.payment.status === 'pending' && order.payment.method !== 'online') {
            selectedOrderIdForPayment = orderId;
            document.getElementById('modal-order-number').innerText = order.orderNumber;
            document.getElementById('modal-customer-name').innerText = order.customer.name;
            document.getElementById('modal-total-amount').innerText = 'Rs ' + order.summary.total.toFixed(2);
            document.getElementById('pickup-payment-modal').classList.remove('hidden');
        } else {
            completeOrder(orderId);
        }
    }

    function processPickupPayment(method) {
        if (!selectedOrderIdForPayment) return;
        const order = onlineOrders.find(o => o.id === selectedOrderIdForPayment);
        if (order) {
            order.payment.status = 'paid';
            order.payment.method = method === 'cash' ? 'cash-on-pickup' : 'card-on-pickup';

            if (typeof Swal !== 'undefined') {
                Swal.fire('Success', `Payment received (${method.toUpperCase()}). Order completed!`, 'success');
            }
            closePickupModal();
            completeOrder(selectedOrderIdForPayment);
        }
    }

    function completeOrder(orderId) {
        const order = onlineOrders.find(o => o.id === orderId);
        if (order) {
            order.status = 'completed';
            renderOrders();
            renderPickupStats();
        }
    }

    function closePickupModal() {
        document.getElementById('pickup-payment-modal').classList.add('hidden');
        selectedOrderIdForPayment = null;
    }
</script>