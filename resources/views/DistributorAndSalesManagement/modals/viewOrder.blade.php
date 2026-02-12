{{-- resources/views/modals/view-order.blade.php --}}

{{-- MODAL HTML STRUCTURE --}}
<div id="view-order-modal"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col transform transition-all duration-300 scale-95"
        id="view-order-modal-content">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-8 py-6">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-4">
                    {{-- Dynamic Channel Icon --}}
                    <div id="modal-channel-icon-container"
                        class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0 text-white">
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                            <h2 id="modal-order-number" class="text-2xl text-white font-bold"></h2>
                            <span id="modal-channel-badge"
                                class="border px-3 py-1 rounded-lg text-sm font-medium"></span>
                            <span id="modal-status-badge"
                                class="border px-3 py-1 rounded-lg flex items-center gap-1.5 text-sm font-medium"></span>
                            <span id="modal-priority-badge"
                                class="border px-3 py-1 rounded-lg text-sm font-medium hidden"></span>
                        </div>
                        <div class="flex items-center gap-3 text-purple-100 text-sm">
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                    <line x1="16" x2="16" y1="2" y2="6" />
                                    <line x1="8" x2="8" y1="2" y2="6" />
                                    <line x1="3" x2="21" y1="10" y2="10" />
                                </svg>
                                <span id="modal-created-at"></span>
                            </div>
                            <span>•</span>
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                <span id="modal-outlet-code"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <button onclick="closeOrderModal()"
                    class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 18 18" />
                    </svg>
                </button>
            </div>

            {{-- Quick Actions --}}
            <div class="flex gap-2 mt-4 flex-wrap">
                <button onclick="alert('Print functionality')"
                    class="h-9 px-4 bg-white/20 hover:bg-white/30 text-white rounded-lg flex items-center gap-2 text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <rect width="12" height="8" x="6" y="14" />
                    </svg>
                    Print
                </button>
                <button onclick="alert('Download functionality')"
                    class="h-9 px-4 bg-white/20 hover:bg-white/30 text-white rounded-lg flex items-center gap-2 text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" x2="12" y1="15" y2="3" />
                    </svg>
                    Download
                </button>
            </div>
        </div>

        {{-- Scrollable Content --}}
        <div class="flex-1 overflow-y-auto p-6 md:p-8">
            <div class="max-w-5xl mx-auto space-y-6">

                {{-- Progress Bar --}}
                <div id="modal-progress-section"
                    class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border-2 border-purple-200">
                    <h3 class="text-lg text-gray-900 mb-4 font-semibold">Order Progress</h3>
                    <div class="flex items-start justify-between overflow-x-auto" id="modal-progress-steps">
                    </div>
                </div>

                {{-- Order Items - MOVED TO TOP --}}
                <div class="bg-white rounded-2xl border-2 border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg text-gray-900 font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="text-purple-600">
                                <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"></path>
                            </svg>
                            Order Items
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3" id="modal-order-items-container">
                            <!-- Items will be injected here via JS -->
                        </div>
                    </div>
                </div>

                {{-- Customer Information --}}
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h3 class="text-lg text-gray-900 mb-4 flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Customer Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Customer Name</p>
                            <p id="modal-cust-name" class="text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Phone</p>
                            <p id="modal-cust-phone" class="text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Outlet Code</p>
                            <p id="modal-outlet-code" class="text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Created At</p>
                            <p id="modal-created-at" class="text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Destination Branch</p>
                            <p id="modal-req-branch" class="text-gray-900 font-medium"></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Source Branch</p>
                            <p id="modal-req-from-branch" class="text-gray-900 font-medium"></p>
                        </div>
                    </div>
                </div>

                {{-- Delivery/Pickup Info --}}
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h3 id="modal-delivery-title"
                        class="text-lg text-gray-900 mb-4 flex items-center gap-2 font-semibold">
                    </h3>
                    <div class="space-y-3" id="modal-delivery-content">
                    </div>
                </div>

                {{-- Special Event Details (Hidden by default) --}}
                <div id="modal-special-section"
                    class="bg-purple-50 rounded-2xl p-6 border-2 border-purple-200 hidden">
                    <h3 class="text-lg text-gray-900 mb-4 flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-purple-600">
                            <rect x="3" y="8" width="18" height="4" rx="1" />
                            <path d="M12 8v13" />
                            <path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7" />
                            <path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5" />
                        </svg>
                        Event Details
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Event Type</p>
                            <p id="modal-event-type" class="text-gray-900 capitalize font-medium"></p>
                        </div>
                    </div>
                </div>


                {{-- Recurring Info (Hidden by default) --}}
                <div id="modal-recurring-section"
                    class="bg-orange-50 rounded-2xl p-6 border-2 border-orange-200 hidden">
                    <h3 class="text-lg text-gray-900 mb-3 flex items-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="text-orange-600">
                            <path d="m17 2 4 4-4 4" />
                            <path d="M3 11v-1a4 4 0 0 1 4-4h14" />
                            <path d="m7 22-4-4 4-4" />
                            <path d="M21 13v1a4 4 0 0 1-4 4H3" />
                        </svg>
                        Recurring Order
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Instance</span>
                            <span id="modal-recurring-instance" class="text-gray-900 font-bold"></span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h3 class="text-lg text-gray-900 mb-4 font-semibold">Actions</h3>
                    <div class="space-y-2" id="modal-actions-container">
                        {{-- Buttons injected via JS --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 px-8 py-5 flex items-center justify-between border-t border-gray-200">
            <div class="flex items-center gap-3" id="modal-footer-actions">
                {{-- Primary action buttons will be injected here --}}
            </div>
            <button onclick="closeOrderModal()"
                class="h-11 px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-colors font-medium">
                Close
            </button>
        </div>
    </div>
</div>

{{-- MODAL LOGIC --}}
<script>
    // Inject Auth Branch ID (only if not already defined)
    if (typeof authCurrentBranchId === 'undefined') {
        var authCurrentBranchId = {{ auth()->user()->current_branch_id ?? 'null' }};
    }

    // Define helper icons
    const modalIcons = {
        store: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2v0a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2 2 0 0 1 4 12v0a2 2 0 0 1-2-2V7"/></svg>`,
        gift: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1"/><path d="M12 8v13"/><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"/><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"/></svg>`,
        repeat: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>`,
        check: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`,
        clock: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
        package: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22v-9"/></svg>`,
        truck: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>`,
        alert: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>`
    };

    function getModalChannelConfig(channel) {
        const configs = {
            'pos-pickup': {
                color: 'bg-blue-100 text-blue-700 border-blue-300',
                label: 'POS Pickup',
                icon: modalIcons.store
            },
            'special-order': {
                color: 'bg-purple-100 text-purple-700 border-purple-300',
                label: 'Special Order',
                icon: modalIcons.gift
            },
            'scheduled-production': {
                color: 'bg-orange-100 text-orange-700 border-orange-300',
                label: 'Scheduled',
                icon: modalIcons.repeat
            },
            'agent-order': {
                color: 'bg-green-100 text-green-700 border-green-300',
                label: 'Agent Order',
                icon: modalIcons.user
            }
        };
        return configs[channel] || configs['pos-pickup'];
    }

    function getModalStatusConfig(status) {
        const configs = {
            'pending-approval': {
                color: 'bg-yellow-100 text-yellow-700 border-yellow-300',
                label: 'Pending Approval',
                icon: modalIcons.clock
            },
            'approved': {
                color: 'bg-blue-100 text-blue-700 border-blue-300',
                label: 'Approved',
                icon: modalIcons.check
            },
            'in-production': {
                color: 'bg-indigo-100 text-indigo-700 border-indigo-300',
                label: 'In Production',
                icon: modalIcons.store
            },
            'ready-for-pickup': {
                color: 'bg-teal-100 text-teal-700 border-teal-300',
                label: 'Ready',
                icon: modalIcons.package
            },
            'out-for-delivery': {
                color: 'bg-cyan-100 text-cyan-700 border-cyan-300',
                label: 'Delivery',
                icon: modalIcons.truck
            },
            'completed': {
                color: 'bg-green-100 text-green-700 border-green-300',
                label: 'Completed',
                icon: modalIcons.check
            },
            'cancelled': {
                color: 'bg-red-100 text-red-700 border-red-300',
                label: 'Cancelled',
                icon: modalIcons.alert
            }
        };
        return configs[status] || {
            color: 'bg-gray-100',
            label: status,
            icon: ''
        };
    }

    function openOrderModal(button) {
        // Parse the data attribute
        const order = JSON.parse(button.dataset.order);
        const modal = document.getElementById('view-order-modal');
        const modalContent = document.getElementById('view-order-modal-content');

        // Populate Fields
        document.getElementById('modal-order-number').innerText = order.orderNumber;

        const channelConf = getModalChannelConfig(order.channel);
        const statusConf = getModalStatusConfig(order.status);

        // Badges
        const chBadge = document.getElementById('modal-channel-badge');
        chBadge.className = `border px-3 py-1 rounded-lg text-sm font-medium ${channelConf.color}`;
        chBadge.innerText = channelConf.label;
        document.getElementById('modal-channel-icon-container').innerHTML = channelConf.icon;

        const stBadge = document.getElementById('modal-status-badge');
        stBadge.className =
            `border px-3 py-1 rounded-lg flex items-center gap-1.5 text-sm font-medium ${statusConf.color}`;
        stBadge.innerHTML = `${statusConf.icon} ${statusConf.label}`;

        // Priority
        const prBadge = document.getElementById('modal-priority-badge');
        if (order.priority !== 'normal') {
            prBadge.classList.remove('hidden');
            prBadge.innerText = order.priority.charAt(0).toUpperCase() + order.priority.slice(1);
            prBadge.className =
                `border px-3 py-1 rounded-lg text-sm font-medium ${order.priority === 'high' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100'}`;
        } else {
            prBadge.classList.add('hidden');
        }

        // Details
        document.getElementById('modal-created-at').innerText = new Date().toLocaleDateString();
        document.getElementById('modal-outlet-code').innerText = order.outletCode;
        document.getElementById('modal-cust-name').innerText = order.customerName;
        document.getElementById('modal-cust-phone').innerHTML = order.customerPhone || 'N/A';
        document.getElementById('modal-req-branch').innerText = order.requestBranchName || 'N/A';
        document.getElementById('modal-req-from-branch').innerText = order.reqFromBranchName || 'N/A';

        // Delivery/Pickup
        const deliveryTitle = document.getElementById('modal-delivery-title');
        const deliveryContent = document.getElementById('modal-delivery-content');

        if (order.deliveryMethod === 'pickup') {
            deliveryTitle.innerHTML = `${modalIcons.package} Pickup Information`;
            deliveryContent.innerHTML = `
                <div>
                    <p class="text-sm text-gray-500 mb-1">Pickup Date</p>
                    <p class="text-gray-900 font-medium">${order.pickupDate || 'N/A'} at ${order.pickupTime || ''}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Location</p>
                    <p class="text-gray-900 font-medium">${order.outletCode}</p>
                </div>
            `;
        } else {
            deliveryTitle.innerHTML = `${modalIcons.truck} Delivery Information`;
            deliveryContent.innerHTML = `
                 <div>
                    <p class="text-sm text-gray-500 mb-1">Delivery Date</p>
                    <p class="text-gray-900 font-medium">${order.deliveryDate || 'N/A'} at ${order.deliveryTime || ''}</p>
                </div>
            `;
        }

        // Sections Visibility
        const recSection = document.getElementById('modal-recurring-section');
        if (order.isRecurring) {
            recSection.classList.remove('hidden');
            document.getElementById('modal-recurring-instance').innerText = '#' + order.instanceNumber;
        } else {
            recSection.classList.add('hidden');
        }

        const specialSection = document.getElementById('modal-special-section');
        if (order.channel === 'special-order') {
            specialSection.classList.remove('hidden');
            document.getElementById('modal-event-type').innerText = order.eventType || 'General';
        } else {
            specialSection.classList.add('hidden');
        }

        // Streamlined Progress
        // Only show: pending, dispatched, approved, rejected
        if (order.status === 'cancelled' || order.status === 'rejected') {
            document.getElementById('modal-progress-section').classList.add('hidden');
        } else {
            const steps = ['pending-approval', 'out-for-delivery', 'approved'];
            const stepContainer = document.getElementById('modal-progress-steps');
            let stepsHtml = '';

            document.getElementById('modal-progress-section').classList.remove('hidden');
            steps.forEach((step, index) => {
                const isCompleted = steps.indexOf(order.status) >= index;
                const labels = {
                    'pending-approval': 'Pending',
                    'out-for-delivery': 'Dispatched',
                    'approved': 'Approved'
                };
                const colorClass = isCompleted ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-400';

                // Add Line before step (except the first one)
                if (index > 0) {
                    const isLineColored = steps.indexOf(order.status) >= index;
                    const lineColor = isLineColored ? 'bg-purple-600' : 'bg-gray-200';
                    stepsHtml +=
                        `<div class="flex-1 h-1 rounded ${lineColor} mx-2 mt-[14px] min-w-[2rem]"></div>`;
                }

                stepsHtml += `
                    <div class="flex flex-col items-center flex-shrink-0 min-w-[60px]">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all ${colorClass}">
                            <div class="w-2 h-2 rounded-full bg-current"></div>
                        </div>
                        <p class="text-xs mt-2 text-center text-gray-400 font-medium ${isCompleted ? 'text-purple-700' : ''}">${labels[step]}</p>
                    </div>
                `;
            });
            stepContainer.innerHTML = stepsHtml;
        }

        // Action Buttons with Permissions - MOVED UP to avoid temporal dead zone
        const currentBranchId = authCurrentBranchId;
        const isSource = (currentBranchId === null) || (currentBranchId == order.reqFromBranchId) || (order
            .reqFromBranchId === null);
        const isRequester = currentBranchId == order.umBranchId;

        // Render Order Items with Better UI
        const itemsContainer = document.getElementById('modal-order-items-container');
        if (order.products && order.products.length > 0) {
            // Check if editable
            const editableStatuses = ['pending-approval', 'approved', 'rejected', 'out-for-delivery',
                'ready-for-pickup'
            ];
            const isEditable = editableStatuses.includes(order.status);

            // Helper function to format numbers (remove unnecessary decimals)
            const formatQuantity = (qty) => {
                const num = parseFloat(qty);
                return num % 1 === 0 ? num.toString() : num.toFixed(3).replace(/\.?0+$/, '');
            };

            // Create table HTML
            let tableHtml = `
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Product</th>
                                <th class="text-center py-3 px-2 text-sm font-semibold text-gray-700 w-28">Requested</th>
                                <th class="text-center py-3 px-2 text-sm font-semibold text-gray-700 w-28">Dispatch</th>
                                <th class="text-right py-3 px-2 text-sm font-semibold text-gray-700 w-32">Unit Price</th>
                                <th class="text-right py-3 px-2 text-sm font-semibold text-gray-700 w-32">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
            `;

            let totalAmount = 0;
            order.products.forEach((item, index) => {
                const bgClass = index % 2 === 0 ? 'bg-gray-50' : 'bg-white';
                const requestedQty = formatQuantity(item.quantity);

                const dispatchInput = isEditable ?
                    `<input type="number" min="0" step="0.001" class="order-item-qty w-full px-2 py-1.5 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-purple-500 focus:border-purple-500" value="${requestedQty}" data-item-id="${item.product_item_id}">` :
                    `<span class="font-medium text-gray-900">${requestedQty}</span>`;

                // Calculate subtotal correctly
                const subtotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
                totalAmount += subtotal;

                tableHtml += `
                    <tr class="${bgClass} hover:bg-purple-50 transition-colors">
                        <td class="py-3 px-2">
                            <div>
                                <p class="text-gray-900 font-medium">${item.name}</p>
                                ${item.notes ? `<p class="text-xs text-gray-500 mt-0.5">${item.notes}</p>` : ''}
                            </div>
                        </td>
                        <td class="py-3 px-2 text-center">
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded-lg font-medium text-sm">
                                ${requestedQty}
                            </span>
                        </td>
                        <td class="py-3 px-2 text-center">
                            ${dispatchInput}
                        </td>
                        <td class="py-3 px-2 text-right text-gray-700">
                            Rs ${parseFloat(item.unit_price).toLocaleString('en-LK', {minimumFractionDigits: 2})}
                        </td>
                        <td class="py-3 px-2 text-right font-semibold text-gray-900">
                            Rs ${subtotal.toLocaleString('en-LK', {minimumFractionDigits: 2})}
                        </td>
                    </tr>
                `;
            });

            tableHtml += `
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-300 bg-gray-100">
                                <td colspan="4" class="py-4 px-2 text-right font-bold text-gray-700 uppercase tracking-wider">Grand Total</td>
                                <td class="py-4 px-2 text-right font-black text-purple-700 text-lg">
                                    Rs ${totalAmount.toLocaleString('en-LK', {minimumFractionDigits: 2})}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;

            itemsContainer.innerHTML = tableHtml;
        } else {
            itemsContainer.innerHTML =
                '<p class="text-gray-500 text-sm text-center py-8">No items found for this order.</p>';
        }

        // Footer Action Buttons (Approve & Reject)
        const footerActionsContainer = document.getElementById('modal-footer-actions');
        let footerActionsHtml = '';

        // Action Buttons (Other actions like Dispatch)
        const actionsContainer = document.getElementById('modal-actions-container');
        let actionsHtml = '';
        console.log(order.reqFromBranchId)

        // Footer buttons: Approve and Reject
        // Footer buttons: Approve and Reject
        // MODIFIED: 0 -> 5 -> 1 Flow

        // 1. Status 0 (Pending) -> Show Dispatch Button (Transitions to 5)
        if (order.status === 'pending-approval') {
            footerActionsHtml +=
                `<button onclick="dispatchOrder('${order.id}')" class="h-11 px-6 bg-orange-600 hover:bg-orange-700 text-white rounded-xl flex items-center justify-center gap-2 transition-colors font-medium">${modalIcons.truck} Dispatch</button>`;

            footerActionsHtml +=
                `<button onclick="alert('Action: Reject')" class="h-11 px-6 bg-red-600 hover:bg-red-700 text-white rounded-xl flex items-center justify-center gap-2 transition-colors font-medium">✕ Reject</button>`;
        }

        // 2. Status 5 (Out for Delivery/Dispatched) -> Show Approve Button (Transitions to 1)
        if (order.status === 'out-for-delivery') {
            footerActionsHtml +=
                `<button onclick="approveDispatch('${order.id}')" class="h-11 px-6 bg-green-600 hover:bg-green-700 text-white rounded-xl flex items-center justify-center gap-2 transition-colors font-medium">${modalIcons.check} Approve</button>`;
        }

        // Other action buttons in the Actions section (Body)
        if (order.status === 'approved') {
            actionsHtml +=
                `<button onclick="alert('Action: Start')" class="w-full h-11 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl flex items-center justify-center gap-2 transition-colors font-medium">${modalIcons.store} Start Production</button>`;
        } else if (order.status === 'in-production') {
            actionsHtml +=
                `<button onclick="alert('Action: Ready')" class="w-full h-11 bg-teal-600 hover:bg-teal-700 text-white rounded-xl flex items-center justify-center gap-2 transition-colors font-medium">${modalIcons.package} Mark as Ready</button>`;
        } else if (order.status === 'ready-for-pickup') {
            actionsHtml +=
                `<button onclick="alert('Action: Complete')" class="w-full h-11 bg-green-600 hover:bg-green-700 text-white rounded-xl flex items-center justify-center gap-2 transition-colors font-medium">${modalIcons.check} Complete Order</button>`;
        }

        footerActionsContainer.innerHTML = footerActionsHtml;
        actionsContainer.innerHTML = actionsHtml;

        // Show Modal with Animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    // Dispatch Function
    function dispatchOrder(orderId) {
        // Collect updated quantities
        const items = [];
        document.querySelectorAll('.order-item-qty').forEach(input => {
            items.push({
                product_item_id: input.dataset.itemId,
                quantity: input.value
            });
        });

        Swal.fire({
            title: 'Dispatch Order?',
            text: "This will dispatch the order with the entered quantities.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Dispatch it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Call API
                fetch('{{ route('orderManagement.dispatchOrder') }}', { // Need to define route
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            items: items
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                throw new Error(text)
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Dispatched!',
                                'Order has been dispatched.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Dispatch Error:', error);
                        // Try to extract message if it's JSON string in error
                        let msg = 'Server error occurred.';
                        try {
                            // Check if error message is JSON
                            const errObj = JSON.parse(error.message);
                            if (errObj.message) msg = errObj.message;
                        } catch (e) {
                            // If HTML or plain text, use specific substring or generic
                            if (error.message && error.message.length < 200) msg = error.message;
                        }

                        Swal.fire(
                            'Error!',
                            msg,
                            'error'
                        );
                    });
            }
        });
    }

    // Approve Dispatch Function
    function approveDispatch(orderId) {
        Swal.fire({
            title: 'Approve Dispatch?',
            text: "This will update barcode locations and confirm the dispatch.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Call API
                fetch('{{ route('orderManagement.approveDispatch') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            order_id: orderId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Approved!',
                                'Dispatch has been approved.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        Swal.fire(
                            'Error!',
                            'Server error occurred.',
                            'error'
                        );
                    });
            }
        });
    }

    function approveOrder(orderId) {
        Swal.fire({
            title: 'Approve Order?',
            text: "This will move the order to 'Approved' status.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Call API
                fetch('{{ route('orderManagement.updateStatus') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            order_id: orderId,
                            status: 1 // Approved
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Approved!',
                                'Order has been approved.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'Server error occurred.',
                            'error'
                        );
                    });
            }
        });
    }

    function closeOrderModal() {
        const modal = document.getElementById('view-order-modal');
        const modalContent = document.getElementById('view-order-modal-content');

        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>
