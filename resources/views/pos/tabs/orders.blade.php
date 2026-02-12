<div id="orders-list-panel" class="flex-1 flex flex-col border-gray-200 lg:border-r h-full">
    <div class="p-3 lg:p-4 bg-white border-b border-gray-200">
        <div class="grid grid-cols-2 gap-2 lg:gap-3 mb-2 lg:mb-3">
            <div>
                <label class="block text-xs text-gray-600 mb-1">Order Type:</label>
                <select id="filter-type" onchange="renderIncomingOrders()"
                    class="w-full h-9 lg:h-10 px-2 lg:px-3 border border-gray-300 rounded-lg text-xs lg:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Types</option>
                    <option value="delivery">Delivery</option>
                    <option value="pickup">Pickup</option>
                    <option value="outlet-transfer">Outlet Transfer</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Status:</label>
                <select id="filter-status" onchange="renderIncomingOrders()"
                    class="w-full h-9 lg:h-10 px-2 lg:px-3 border border-gray-300 rounded-lg text-xs lg:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="preparing">Preparing</option>
                    <option value="ready">Ready</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2">
            <div class="bg-yellow-50 rounded-lg p-2 text-center">
                <p class="text-xs text-yellow-600">Pending</p>
                <p id="stat-pending" class="text-base lg:text-lg text-yellow-900 font-bold">0</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-2 text-center">
                <p class="text-xs text-orange-600">Preparing</p>
                <p id="stat-preparing" class="text-base lg:text-lg text-orange-900 font-bold">0</p>
            </div>
            <div class="bg-green-50 rounded-lg p-2 text-center">
                <p class="text-xs text-green-600">Ready</p>
                <p id="stat-ready" class="text-base lg:text-lg text-green-900 font-bold">0</p>
            </div>
        </div>
    </div>

    <div id="incoming-orders-list" class="flex-1 overflow-y-auto divide-y divide-gray-200">
    </div>
</div>

<div id="order-details-panel"
    class="hidden lg:flex lg:w-96 bg-gray-50 flex-col fixed lg:relative inset-0 lg:inset-auto z-50 lg:z-auto h-full">
    <div id="empty-state-msg" class="flex flex-col items-center justify-center h-full text-gray-400">
        <i class="bi bi-box-seam text-5xl mb-3"></i>
        <p>Select an order</p>
        <p class="text-sm">to view details</p>
    </div>
    <div id="order-details-content" class="flex flex-col h-full w-full hidden">
    </div>
</div>

<script>
    var incomingOrders = [];
    var currentSelectedOrderId = null;

    $(document).ready(function () {
        fetchIncomingOrders();
    });

    function fetchIncomingOrders() {
        $.ajax({
            url: "{{ route('pos.orders') }}",
            method: 'GET',
            success: function (response) {
                incomingOrders = response;
                renderIncomingOrders();
            },
            error: function () {
                toastr.error('Failed to load incoming orders');
            }
        });
    }

    function getStatusColor(status) {
        switch (status) {
            case 'pending': return 'bg-yellow-100 text-yellow-700';
            case 'preparing': return 'bg-orange-100 text-orange-700';
            case 'ready': return 'bg-green-100 text-green-700';
            default: return 'bg-gray-100 text-gray-700';
        }
    }

    function getTypeDetails(type) {
        switch (type) {
            case 'delivery': return { icon: '<i class="bi bi-truck"></i>', color: 'bg-blue-100 text-blue-700' };
            case 'pickup': return { icon: '<i class="bi bi-shop"></i>', color: 'bg-green-100 text-green-700' };
            case 'outlet-transfer': return { icon: '<i class="bi bi-box-seam"></i>', color: 'bg-purple-100 text-purple-700' };
            default: return { icon: '<i class="bi bi-box"></i>', color: 'bg-gray-100 text-gray-700' };
        }
    }

    function formatDateLabel(dateStr) {
        const d = new Date(dateStr);
        const now = new Date();
        const tomorrow = new Date(now);
        tomorrow.setDate(tomorrow.getDate() + 1);

        if (d.toDateString() === now.toDateString()) return 'Today';
        if (d.toDateString() === tomorrow.toDateString()) return 'Tomorrow';
        return d.toLocaleDateString('en-LK', { month: 'short', day: 'numeric' });
    }

    function formatTime(dateStr) {
        return new Date(dateStr).toLocaleTimeString('en-LK', { hour: '2-digit', minute: '2-digit' });
    }

    function renderIncomingOrders() {
        const filterType = document.getElementById('filter-type').value;
        const filterStatus = document.getElementById('filter-status').value;
        const listContainer = document.getElementById('incoming-orders-list');

        const filtered = incomingOrders.filter(order => {
            const typeMatch = filterType === 'all' || order.orderType === filterType;
            const statusMatch = filterStatus === 'all' || order.status === filterStatus;
            return typeMatch && statusMatch && order.status !== 'completed';
        }).sort((a, b) => new Date(a.deliveryDate) - new Date(b.deliveryDate));

        document.getElementById('stat-pending').textContent = incomingOrders.filter(o => o.status === 'pending').length;
        document.getElementById('stat-preparing').textContent = incomingOrders.filter(o => o.status === 'preparing').length;
        document.getElementById('stat-ready').textContent = incomingOrders.filter(o => o.status === 'ready').length;

        if (filtered.length === 0) {
            listContainer.innerHTML = `
                <div class="flex flex-col items-center justify-center h-64 text-gray-500 p-6">
                    <i class="bi bi-inbox text-4xl mb-3 text-gray-300"></i>
                    <p>No orders found</p>
                </div>`;
            return;
        }

        listContainer.innerHTML = filtered.map(order => {
            const typeInfo = getTypeDetails(order.orderType);
            const isSelected = currentSelectedOrderId === order.id;
            const activeClass = isSelected ? 'bg-blue-50 border-l-4 border-blue-600' : 'hover:bg-gray-50';

            return `
                <button onclick="selectIncomingOrder('${order.id}')" 
                    class="w-full p-3 lg:p-4 text-left transition-colors border-l-4 border-transparent ${activeClass}">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center ${typeInfo.color}">
                                ${typeInfo.icon}
                            </div>
                            <div>
                                <p class="text-sm lg:text-base text-gray-900 font-medium">${order.orderNumber}</p>
                                <p class="text-xs text-gray-500">${order.customerName}</p>
                            </div>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-lg font-medium uppercase ${getStatusColor(order.status)}">
                            ${order.status}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-gray-600 mb-2">
                        <span class="flex items-center gap-1"><i class="bi bi-calendar"></i> ${formatDateLabel(order.deliveryDate)}</span>
                        <span class="flex items-center gap-1"><i class="bi bi-box"></i> ${order.items.length} items</span>
                    </div>
                    <div class="text-sm text-blue-600 font-bold">Rs ${order.total.toFixed(2)}</div>
                </button>
            `;
        }).join('');
    }

    function selectIncomingOrder(orderId) {
        currentSelectedOrderId = orderId;
        const order = incomingOrders.find(o => o.id === orderId);

        renderIncomingOrders();

        if (!order) return;

        const listPanel = document.getElementById('orders-list-panel');
        const detailsPanel = document.getElementById('order-details-panel');

        if (window.innerWidth < 1024) {
            listPanel.classList.add('hidden');
            detailsPanel.classList.remove('hidden');
            detailsPanel.classList.add('flex');
        }

        document.getElementById('empty-state-msg').classList.add('hidden');
        const contentDiv = document.getElementById('order-details-content');
        contentDiv.classList.remove('hidden');

        const typeInfo = getTypeDetails(order.orderType);

        let itemsHtml = order.items.map(item => `
            <div class="bg-gray-50 rounded-lg p-3 mb-2">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gray-900 font-medium">${item.productName}</p>
                        <p class="text-xs text-gray-500">Qty: ${item.quantity} × Rs ${item.unitPrice.toFixed(2)}</p>
                    </div>
                    <p class="text-sm text-gray-900 font-bold">Rs ${item.lineTotal.toFixed(2)}</p>
                </div>
            </div>
        `).join('');

        let actionButtons = `
            <button onclick="printOrderTicket('${order.id}')" class="w-full h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm flex items-center justify-center gap-2 transition-colors font-medium">
                <i class="bi bi-printer"></i> Print Order Ticket
            </button>
        `;

        if (order.status === 'pending') {
            actionButtons += `
                <button onclick="updateStatus('${order.id}', 'preparing')" class="w-full h-12 bg-orange-50 hover:bg-orange-100 text-orange-600 rounded-xl text-sm flex items-center justify-center gap-2 transition-colors font-medium mt-2">
                    <i class="bi bi-clock"></i> Mark as Preparing
                </button>`;
        } else if (order.status === 'preparing') {
            actionButtons += `
                <button onclick="updateStatus('${order.id}', 'ready')" class="w-full h-12 bg-green-50 hover:bg-green-100 text-green-600 rounded-xl text-sm flex items-center justify-center gap-2 transition-colors font-medium mt-2">
                    <i class="bi bi-check-circle"></i> Mark as Ready
                </button>`;
        } else if (order.status === 'ready') {
            actionButtons += `
                <button onclick="updateStatus('${order.id}', 'completed')" class="w-full h-12 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl text-sm flex items-center justify-center gap-2 transition-all font-medium mt-2">
                    <i class="bi bi-check-circle-fill"></i> Complete & Hand Over
                </button>`;
        }

        let locationHtml = '';
        if (order.deliveryAddress) locationHtml = `<div class="flex items-start gap-2"><i class="bi bi-geo-alt text-gray-400 mt-1"></i> <span class="text-gray-600 text-xs">${order.deliveryAddress}</span></div>`;
        if (order.pickupLocation) locationHtml = `<div class="flex items-center gap-2"><i class="bi bi-shop text-gray-400"></i> <span class="text-gray-600">${order.pickupLocation}</span></div>`;

        contentDiv.innerHTML = `
            <div class="lg:hidden p-3 bg-white border-b border-gray-200">
                <button onclick="backToOrderList()" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
                    <i class="bi bi-arrow-left"></i> <span class="text-sm font-medium">Back to Orders</span>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="bg-white rounded-xl p-4 mb-4 shadow-sm">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="text-xl text-gray-900 font-bold">${order.orderNumber}</p>
                            <span class="inline-block text-xs px-2 py-1 rounded-lg mt-1 font-medium uppercase ${getStatusColor(order.status)}">${order.status}</span>
                        </div>
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl ${typeInfo.color}">${typeInfo.icon}</div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center gap-2"><i class="bi bi-person text-gray-400"></i> <span class="text-gray-900 font-medium">${order.customerName}</span></div>
                        ${order.customerPhone ? `<div class="flex items-center gap-2"><i class="bi bi-telephone text-gray-400"></i> <span class="text-gray-600">${order.customerPhone}</span></div>` : ''}
                        <div class="flex items-center gap-2"><i class="bi bi-calendar text-gray-400"></i> <span class="text-gray-600">${formatDateLabel(order.deliveryDate)} - ${formatTime(order.deliveryDate)}</span></div>
                        ${locationHtml}
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 mb-4 shadow-sm">
                    <h4 class="text-sm text-gray-700 font-bold mb-3">Order Items:</h4>
                    <div class="space-y-3">${itemsHtml}</div>
                    <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between">
                        <span class="text-gray-900 font-bold">Total:</span>
                        <span class="text-xl text-blue-600 font-bold">Rs ${order.total.toFixed(2)}</span>
                    </div>
                </div>

                ${order.notes ? `
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                    <h4 class="text-sm text-yellow-800 font-bold mb-2">Order Notes:</h4>
                    <p class="text-sm text-yellow-900">${order.notes}</p>
                </div>` : ''}
            </div>

            <div class="p-4 bg-white border-t border-gray-200">
                ${actionButtons}
            </div>
        `;
    }

    function backToOrderList() {
        document.getElementById('orders-list-panel').classList.remove('hidden');
        document.getElementById('order-details-panel').classList.add('hidden');
        document.getElementById('order-details-panel').classList.remove('flex');
    }

    function updateStatus(id, status) {
        const order = incomingOrders.find(o => o.id === id);
        if (order) {
            order.status = status;
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                Toast.fire({ icon: 'success', title: `Order marked as ${status}` });
            }
            if (status === 'completed') {
                currentSelectedOrderId = null;
                backToOrderList();
            } else {
                selectIncomingOrder(id);
            }
            renderIncomingOrders();
        }
    }

    function printOrderTicket(id) {
        alert(`Printing ticket for Order ID: ${id}`);
    }
</script>