@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 p-4 md:p-6">
    
    {{-- HEADER --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
            <div>
                <h1 class="text-3xl text-gray-900 font-bold">Delivery Scheduling</h1>
                <p class="text-gray-600">Manage and track deliveries</p>
            </div>
        </div>

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-600"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Pending</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $summary['pending'] }}</p>
                <p class="text-sm text-gray-500">Awaiting schedule</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Today's Deliveries</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $summary['todayDeliveries'] }}</p>
                <p class="text-sm text-gray-500">Scheduled for today</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">In Transit</h3>
                <p class="text-2xl font-bold text-purple-600">{{ $summary['inTransit'] }}</p>
                <p class="text-sm text-gray-500">Out for delivery</p>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                </div>
                <h3 class="text-gray-600 mb-1">Completed Today</h3>
                <p class="text-2xl font-bold text-green-600">{{ $summary['completedToday'] }}</p>
                <p class="text-sm text-gray-500">Delivered successfully</p>
            </div>
        </div>

        {{-- CONTROLS --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 space-y-4 mb-6">
            <div class="flex flex-wrap items-center gap-3">
                
                {{-- View Toggle --}}
                <div class="flex gap-2 bg-gray-100 p-1 rounded-xl">
                    <button onclick="switchView('calendar')" id="btn-view-calendar" class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all bg-white text-purple-600 shadow-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        Calendar
                    </button>
                    <button onclick="switchView('route')" id="btn-view-route" class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all text-gray-600 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        Routes
                    </button>
                    <button onclick="switchView('list')" id="btn-view-list" class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all text-gray-600 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
                        List
                    </button>
                </div>

                {{-- Search --}}
                <div class="flex-1 min-w-[200px] relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" id="deliverySearch" onkeyup="filterDeliveries()" placeholder="Search deliveries..." class="w-full h-10 pl-10 pr-4 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition-colors">
                </div>

                {{-- Status Filter --}}
                <select id="statusFilter" onchange="filterDeliveries()" class="h-10 px-4 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition-colors">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="in-transit">In Transit</option>
                    <option value="delivered">Delivered</option>
                </select>

                {{-- Create Button --}}
                <div class="flex items-center gap-3">
                    <button onclick="openCreateDeliveryModal()" class="flex items-center gap-2 h-10 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl transition-colors shadow-lg shadow-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"></line><line x1="5" x2="19" y1="12" y2="12"></line></svg>
                        <span class="font-bold">Create Delivery</span>
                    </button>
                    <div class="h-10 w-px bg-gray-200"></div>
                    <button class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2zm12-4h-2V6h2zm-4 0H8V6h4z"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- VIEW: CALENDAR --}}
        <div id="view-calendar" class="view-section bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Calendar logic handled visually for this week starting today --}}
            @php
                $weekDates = [];
                $start = \Carbon\Carbon::now();
                for($i=0; $i<7; $i++) {
                    $weekDates[] = $start->copy()->addDays($i);
                }
                $timeSlots = ['morning' => '6 AM - 12 PM', 'afternoon' => '12 PM - 6 PM', 'evening' => '6 PM - 9 PM'];
            @endphp
            
            <div class="overflow-x-auto">
                <div class="min-w-[1200px]">
                    {{-- Header Row --}}
                    <div class="grid grid-cols-8 border-b border-gray-200">
                        <div class="p-3 text-sm text-gray-600 bg-gray-50 font-medium">Time Slot</div>
                        @foreach($weekDates as $date)
                            <div class="p-3 text-center border-l border-gray-200 {{ $date->isToday() ? 'bg-purple-50' : 'bg-gray-50' }}">
                                <div class="text-sm text-gray-600">{{ $date->format('D') }}</div>
                                <div class="text-lg font-bold {{ $date->isToday() ? 'text-purple-600' : 'text-gray-900' }}">{{ $date->format('M d') }}</div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Time Slots --}}
                    @foreach($timeSlots as $slotKey => $slotLabel)
                        <div class="grid grid-cols-8 border-b border-gray-200 min-h-[120px]">
                            <div class="p-3 bg-gray-50 border-r border-gray-200">
                                <div class="text-sm text-gray-900 capitalize font-bold">{{ $slotKey }}</div>
                                <div class="text-xs text-gray-500">{{ $slotLabel }}</div>
                            </div>
                            @foreach($weekDates as $date)
                                <div class="p-2 border-l border-gray-200 {{ $date->isToday() ? 'bg-purple-50/30' : '' }}">
                                    @php
                                        $dateStr = $date->format('Y-m-d');
                                        $slotDeliveries = $calendarData[$dateStr][$slotKey] ?? [];
                                    @endphp
                                    <div class="space-y-2">
                                        @foreach($slotDeliveries as $delivery)
                                            <div onclick="openViewDeliveryDetailsModal(this)" data-delivery="{{ json_encode($delivery) }}" class="bg-white border-l-4 border-purple-500 rounded-lg p-2 hover:shadow-md transition-all cursor-pointer border border-gray-200">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="text-xs text-purple-600 font-bold">
                                                        {{ $delivery->deliveryType === 'customer' ? $delivery->invoiceNumber : ($delivery->deliveryType === 'outlet-transfer' ? 'Transfer' : 'Ad-hoc') }}
                                                    </span>
                                                    <span class="text-xs">
                                                        @if($delivery->priority == 'urgent') 🔴 @elseif($delivery->priority == 'low') 🔵 @else ⚪ @endif
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-900 truncate mb-1">
                                                    {{ $delivery->deliveryType === 'customer' ? $delivery->customerName : ($delivery->deliveryType === 'outlet-transfer' ? $delivery->outletName : 'Special') }}
                                                </div>
                                                <span class="text-[10px] px-1.5 py-0.5 bg-gray-100 border border-gray-300 rounded uppercase font-bold text-gray-600">
                                                    {{ $delivery->status }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- VIEW: ROUTE --}}
        <div id="view-route" class="view-section hidden space-y-4">
            @foreach($routeData as $route => $items)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 border-b border-gray-200 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            <div>
                                <h3 class="text-lg text-gray-900 font-bold">{{ $route }}</h3>
                                <p class="text-sm text-gray-600">{{ count($items) }} deliveries</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($items as $delivery)
                             @include('DistributorAndSalesManagement.partials.deliveryCard', ['delivery' => $delivery])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- VIEW: LIST --}}
        <div id="view-list" class="view-section hidden bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm text-gray-600 font-medium">Reference</th>
                            <th class="px-4 py-3 text-left text-sm text-gray-600 font-medium">Customer/Outlet</th>
                            <th class="px-4 py-3 text-left text-sm text-gray-600 font-medium">Address</th>
                            <th class="px-4 py-3 text-left text-sm text-gray-600 font-medium">Scheduled</th>
                            <th class="px-4 py-3 text-left text-sm text-gray-600 font-medium">Driver</th>
                            <th class="px-4 py-3 text-left text-sm text-gray-600 font-medium">Status</th>
                            <th class="px-4 py-3 text-left text-sm text-gray-600 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="listTableBody">
                        @foreach($deliveries as $delivery)
                            <tr class="hover:bg-gray-50 transition-colors delivery-row" 
                                data-status="{{ $delivery->status }}"
                                data-search="{{ strtolower(($delivery->invoiceNumber ?? '') . ' ' . ($delivery->customerName ?? '') . ' ' . ($delivery->outletName ?? '') . ' ' . $delivery->deliveryAddress) }}"
                                data-delivery="{{ json_encode($delivery) }}">
                                <td class="px-4 py-3 text-sm text-purple-600 font-medium">
                                    {{ $delivery->deliveryType === 'customer' ? $delivery->invoiceNumber : ($delivery->deliveryType === 'outlet-transfer' ? 'Transfer' : 'Ad-hoc') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $delivery->deliveryType === 'customer' ? $delivery->customerName : ($delivery->deliveryType === 'outlet-transfer' ? $delivery->outletName : 'Special') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 max-w-[200px] truncate" title="{{ $delivery->deliveryAddress }}">
                                    {{ $delivery->deliveryAddress }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    @if($delivery->scheduledDate)
                                        <div>{{ \Carbon\Carbon::parse($delivery->scheduledDate)->format('M d') }}</div>
                                        <div class="text-xs text-gray-400 capitalize">{{ $delivery->timeSlot }}</div>
                                    @else
                                        <span class="text-gray-400 italic">Unscheduled</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $delivery->driver ?? 'Unassigned' }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-gray-100 text-gray-700 border-gray-300',
                                            'scheduled' => 'bg-blue-100 text-blue-700 border-blue-300',
                                            'in-transit' => 'bg-purple-100 text-purple-700 border-purple-300',
                                            'delivered' => 'bg-green-100 text-green-700 border-green-300',
                                        ];
                                        $color = $statusColors[$delivery->status] ?? 'bg-gray-100';
                                    @endphp
                                    <span class="{{ $color }} text-xs px-2 py-1 border rounded-lg font-medium capitalize">
                                        {{ $delivery->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        @if($delivery->status === 'pending')
                                            <button onclick="openScheduleModal(this.closest('tr'))" class="h-8 px-3 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg text-xs transition-colors font-medium">Schedule</button>
                                        @endif
                                        <button onclick="openViewDeliveryDetailsModal(this.closest('tr'))" class="h-8 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs transition-colors font-medium">View</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('DistributorAndSalesManagement.modals.viewDeliveryDetails')
@include('DistributorAndSalesManagement.modals.scheduleDelivery')
@include('DistributorAndSalesManagement.modals.createDelivery')

{{-- Scripts --}}
<script>
    function switchView(viewName) {
        // UI Buttons
        const buttons = {
            'calendar': document.getElementById('btn-view-calendar'),
            'route': document.getElementById('btn-view-route'),
            'list': document.getElementById('btn-view-list')
        };
        
        Object.keys(buttons).forEach(key => {
            if(key === viewName) {
                buttons[key].classList.remove('text-gray-600', 'hover:text-gray-900');
                buttons[key].classList.add('bg-white', 'text-purple-600', 'shadow-sm', 'font-medium');
            } else {
                buttons[key].classList.add('text-gray-600', 'hover:text-gray-900');
                buttons[key].classList.remove('bg-white', 'text-purple-600', 'shadow-sm', 'font-medium');
            }
        });

        // Toggle Sections
        document.querySelectorAll('.view-section').forEach(el => el.classList.add('hidden'));
        document.getElementById('view-' + viewName).classList.remove('hidden');
    }

    function filterDeliveries() {
        const query = document.getElementById('deliverySearch').value.toLowerCase();
        const status = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.delivery-row');

        rows.forEach(row => {
            const matchesSearch = row.dataset.search.includes(query);
            const matchesStatus = status === 'all' || row.dataset.status === status;
            
            if(matchesSearch && matchesStatus) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
        // Note: For calendar/route views, complex DOM manipulation is needed for filtering in vanilla JS. 
        // This simple filter works best for the List view.
    }

    // Modal Functions
    function openViewDeliveryDetailsModal(element) {
        // 1. Get Delivery Data
        let delivery;
        if (element && element.dataset.delivery) {
            try {
                delivery = JSON.parse(element.dataset.delivery);
            } catch (e) {
                console.error("Error parsing delivery data", e);
                return;
            }
        }
        
        if (!delivery) return;

        // 2. Populate Header
        document.getElementById('detail-invoice-number').textContent = delivery.invoiceNumber || 'N/A';
        
        // Badges
        const badgesContainer = document.getElementById('detail-badges');
        badgesContainer.innerHTML = '';
        
        // Status Badge logic
        const statusColors = {
            'pending': 'bg-gray-100 text-gray-700 border-gray-300',
            'scheduled': 'bg-blue-100 text-blue-700 border-blue-300',
            'assigned': 'bg-indigo-100 text-indigo-700 border-indigo-300',
            'in-transit': 'bg-purple-100 text-purple-700 border-purple-300',
            'delivered': 'bg-green-100 text-green-700 border-green-300',
            'failed': 'bg-red-100 text-red-700 border-red-300',
            'cancelled': 'bg-gray-100 text-gray-700 border-gray-300'
        };
        const statusColor = statusColors[delivery.status] || statusColors['pending'];
        badgesContainer.innerHTML += `<span class="${statusColor} px-3 py-1 rounded-lg border uppercase text-xs font-bold">${delivery.status.replace('-', ' ')}</span>`;

        // Priority Badge
        const priorityColors = {
            'urgent': 'bg-red-100 text-red-700 border-red-300',
            'standard': 'bg-blue-100 text-blue-700 border-blue-300',
            'low': 'bg-gray-100 text-gray-700 border-gray-300'
        };
        const priorityColor = priorityColors[delivery.priority] || priorityColors['standard'];
        badgesContainer.innerHTML += `<span class="${priorityColor} px-3 py-1 rounded-lg border uppercase text-xs font-bold">${delivery.priority}</span>`;

        // Type Badge
        const typeInfo = {
            'customer': { label: 'Customer Delivery', color: 'bg-blue-100 text-blue-700 border-blue-300' },
            'outlet-transfer': { label: 'Outlet Transfer', color: 'bg-green-100 text-green-700 border-green-300' },
            'adhoc': { label: 'Ad-hoc Delivery', color: 'bg-orange-100 text-orange-700 border-orange-300' }
        };
        const type = typeInfo[delivery.deliveryType] || typeInfo['customer'];
        badgesContainer.innerHTML += `<span class="${type.color} px-3 py-1 rounded-lg border uppercase text-xs font-bold">${type.label}</span>`;


        // 3. Populate Customer Info
        const customerLabel = document.getElementById('detail-customer-label');
        const customerName = document.getElementById('detail-customer-name');
        const outletType = document.getElementById('detail-outlet-type');
        const fromLocation = document.getElementById('detail-from-location');
        
        customerLabel.textContent = delivery.deliveryType === 'outlet-transfer' ? 'Outlet Information' : 'Delivery Address';
        
        if (delivery.deliveryType === 'outlet-transfer') {
            customerName.textContent = delivery.outletName || 'N/A';
            outletType.textContent = delivery.outletType === 'company-owned' ? '🏢 Company Owned' : '🏪 Third-party Outlet';
            outletType.classList.remove('hidden');
            if (delivery.fromLocationName) {
                fromLocation.textContent = 'From: ' + delivery.fromLocationName;
                fromLocation.classList.remove('hidden');
            } else {
                fromLocation.classList.add('hidden');
            }
        } else {
            customerName.textContent = delivery.customerName || 'N/A';
            outletType.classList.add('hidden');
            fromLocation.classList.add('hidden');
        }

        document.getElementById('detail-address').textContent = delivery.deliveryAddress || '';
        document.getElementById('detail-phone').textContent = delivery.contactPhone || 'N/A';
        
        if (delivery.contactEmail) {
            document.getElementById('detail-email').textContent = delivery.contactEmail;
            document.getElementById('detail-email-container').classList.remove('hidden');
        } else {
            document.getElementById('detail-email-container').classList.add('hidden');
        }


        // 4. Populate Items
        const itemsList = document.getElementById('detail-items-list');
        itemsList.innerHTML = '';
        if (delivery.items && delivery.items.length > 0) {
            document.getElementById('detail-items-count').textContent = `(${delivery.items.length})`;
            delivery.items.forEach(item => {
                itemsList.innerHTML += `
                    <div class="flex items-center justify-between text-sm py-2 border-b border-gray-100 last:border-0">
                        <span class="text-gray-900">${item.productName}</span>
                        <span class="text-gray-600 font-medium">${item.quantity} ${item.unit}</span>
                    </div>
                `;
            });
        } else {
            document.getElementById('detail-items-count').textContent = '(0)';
            itemsList.innerHTML = '<div class="text-sm text-gray-500 italic py-2">No items listed</div>';
        }
        document.getElementById('detail-total-value').textContent = 'Rs ' + (delivery.totalValue ? delivery.totalValue.toLocaleString() : '0');


        // 5. Schedule Info
        if (delivery.scheduledDate) {
            document.getElementById('detail-schedule-section').classList.remove('hidden');
            const date = new Date(delivery.scheduledDate);
            document.getElementById('detail-schedule-date').textContent = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            
            // Time Slot Label Logic matching React component
            const timeSlotLabels = {
                'morning': '6:00 AM - 12:00 PM',
                'afternoon': '12:00 PM - 6:00 PM',
                'evening': '6:00 PM - 9:00 PM'
            };
            const timeSlotLabel = timeSlotLabels[delivery.timeSlot] || delivery.timeSlot;
            document.getElementById('detail-time-slot').textContent = `${delivery.timeSlot} (${timeSlotLabel})`;

            if (delivery.route) {
                document.getElementById('detail-route').textContent = delivery.route;
                document.getElementById('detail-route-row').classList.remove('hidden');
            } else {
                document.getElementById('detail-route-row').classList.add('hidden');
            }
        } else {
             document.getElementById('detail-schedule-section').classList.add('hidden');
        }


        // 6. Assignment Info
        if (delivery.driverName || delivery.vehicleName) {
            document.getElementById('detail-assignment-section').classList.remove('hidden');
            if (delivery.driverName) {
                document.getElementById('detail-driver-name').textContent = delivery.driverName;
                document.getElementById('detail-driver-row').classList.remove('hidden');
            } else {
                document.getElementById('detail-driver-row').classList.add('hidden');
            }
            if (delivery.vehicleName) {
                document.getElementById('detail-vehicle-name').textContent = delivery.vehicleName;
                document.getElementById('detail-vehicle-row').classList.remove('hidden');
            } else {
                document.getElementById('detail-vehicle-row').classList.add('hidden');
            }
        } else {
            document.getElementById('detail-assignment-section').classList.add('hidden');
        }


        // 7. Special Instructions
        if (delivery.specialInstructions) {
            document.getElementById('detail-instructions').textContent = delivery.specialInstructions;
            document.getElementById('detail-instructions-section').classList.remove('hidden');
        } else {
            document.getElementById('detail-instructions-section').classList.add('hidden');
        }


        // 8. Delivered Info
        if (delivery.deliveredAt) {
            document.getElementById('detail-delivered-section').classList.remove('hidden');
            const date = new Date(delivery.deliveredAt);
            document.getElementById('detail-delivered-time').textContent = date.toLocaleDateString('en-US', {
                month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit'
            });
            if (delivery.deliveredBy) {
                document.getElementById('detail-delivered-by').textContent = delivery.deliveredBy;
                document.getElementById('detail-delivered-by-row').classList.remove('hidden');
            } else {
                document.getElementById('detail-delivered-by-row').classList.add('hidden');
            }
        } else {
            document.getElementById('detail-delivered-section').classList.add('hidden');
        }

        // 9. Failure Info
        if (delivery.failureReason) {
            document.getElementById('detail-failure-reason').textContent = delivery.failureReason;
            document.getElementById('detail-failure-section').classList.remove('hidden');
        } else {
            document.getElementById('detail-failure-section').classList.add('hidden');
        }

        // 10. Notes
        if (delivery.notes) {
            document.getElementById('detail-notes').textContent = delivery.notes;
            document.getElementById('detail-notes-section').classList.remove('hidden');
        } else {
            document.getElementById('detail-notes-section').classList.add('hidden');
        }


        // 11. Actions Visibility
        const btnMarkTransit = document.getElementById('btn-mark-transit');
        const actionCompletion = document.getElementById('action-delivery-completion');
        
        btnMarkTransit.classList.add('hidden');
        actionCompletion.classList.add('hidden');

        if (delivery.status === 'assigned') {
            btnMarkTransit.classList.remove('hidden');
            // Attach specific handler if needed, currently just UI logic
            btnMarkTransit.onclick = () => { /* Logic to mark in transit */ };
        } else if (delivery.status === 'in-transit') {
            actionCompletion.classList.remove('hidden');
        }


        // Open Modal
        const modal = document.getElementById('view-delivery-details-modal');
        const panel = document.getElementById('view-delivery-details-panel');
        const backdrop = document.getElementById('view-delivery-backdrop');
        
        modal.classList.remove('hidden');
        // Small delay to ensure transition happens
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('translate-x-full');
        }, 10);
    }

    function closeViewDeliveryDetailsModal() {
        const modal = document.getElementById('view-delivery-details-modal');
        const panel = document.getElementById('view-delivery-details-panel');
        const backdrop = document.getElementById('view-delivery-backdrop');
        
        panel.classList.add('translate-x-full');
        backdrop.classList.add('opacity-0');
        
        // Wait for transition to finish
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Schedule Modal Functions
    function openScheduleModal(element) {
        let delivery;
        if (element && element.dataset.delivery) {
            try {
                delivery = JSON.parse(element.dataset.delivery);
            } catch (e) {
                console.error("Error parsing delivery data", e);
                return;
            }
        }
        
        if (!delivery) return;

        // Populate Modal Fields
        document.getElementById('schedule-delivery-id').value = delivery.id;
        document.getElementById('schedule-invoice-number').textContent = delivery.invoiceNumber || 'N/A';
        document.getElementById('schedule-customer-name').textContent = delivery.customerName || (delivery.deliveryType === 'outlet-transfer' ? delivery.outletName : 'N/A');
        document.getElementById('schedule-address').textContent = delivery.deliveryAddress || '';
        document.getElementById('schedule-phone').textContent = delivery.contactPhone || '';
        
        document.getElementById('schedule-items-count').textContent = (delivery.items ? delivery.items.length : 0) + ' item(s)';
        document.getElementById('schedule-total-value').textContent = 'Rs ' + (delivery.totalValue ? delivery.totalValue.toLocaleString() : '0');

        // Set default date (today)
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('schedule-date').value = delivery.scheduledDate ? delivery.scheduledDate.split(' T')[0] : today;

        // Reset Selection (Time Slot)
        document.querySelectorAll('.time-slot-btn').forEach(btn => {
            btn.classList.remove('border-purple-500', 'bg-purple-50');
            btn.classList.add('border-gray-200', 'bg-white');
        });
        document.getElementById('schedule-time-slot').value = '';

        // Reset Drivers/Vehicles (in real app, these might need selecting if already assigned)
        document.getElementById('schedule-driver').value = delivery.driverId || '';
        document.getElementById('schedule-vehicle').value = delivery.vehicleId || '';
        checkAssignmentStatus();


        // Open Modal
        const modal = document.getElementById('schedule-delivery-modal');
        const panel = document.getElementById('schedule-delivery-panel');
        const backdrop = document.getElementById('schedule-delivery-backdrop');

        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeScheduleModal() {
        const modal = document.getElementById('schedule-delivery-modal');
        const panel = document.getElementById('schedule-delivery-panel');
        const backdrop = document.getElementById('schedule-delivery-backdrop');

        panel.classList.remove('scale-100');
        panel.classList.add('opacity-0', 'scale-95');
        backdrop.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function selectTimeSlot(slot) {
        document.getElementById('schedule-time-slot').value = slot;
        
        // Update UI
        document.querySelectorAll('.time-slot-btn').forEach(btn => {
            btn.classList.remove('border-purple-500', 'bg-purple-50');
            btn.classList.add('border-gray-200', 'bg-white');
        });
        
        const selectedBtn = document.getElementById('btn-slot-' + slot);
        selectedBtn.classList.remove('border-gray-200', 'bg-white');
        selectedBtn.classList.add('border-purple-500', 'bg-purple-50');
    }

    function checkAssignmentStatus() {
        const driver = document.getElementById('schedule-driver').value;
        const vehicle = document.getElementById('schedule-vehicle').value;
        const warning = document.getElementById('schedule-warning');

        if (!driver || !vehicle) {
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
    }

    function handleScheduleSubmit(event) {
        event.preventDefault();
        
        const date = document.getElementById('schedule-date').value;
        const timeSlot = document.getElementById('schedule-time-slot').value;

        if (!date) {
            alert('Please select a delivery date'); // Using alert for simplicity, toast would be better 
            return;
        }

        if (!timeSlot) {
            alert('Please select a time slot');
            return;
        }

        // Simulate AJAX request
        // In a real app: fetch('/api/schedule-delivery', { method: 'POST', body: new FormData(event.target) })
        
        // For prototype/demo purposes, verify success visually
        alert('Delivery scheduled successfully!'); // Or replace with toast if available
        closeScheduleModal();
        // Optionally refresh page or row status
        // location.reload(); 
    }

    // Create Delivery Modal Functions
    function openCreateDeliveryModal() {
        goBackToSelection();
        const modal = document.getElementById('create-delivery-modal');
        const panel = document.getElementById('create-delivery-panel');
        const backdrop = document.getElementById('create-delivery-backdrop');

        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'scale-95');
            panel.classList.add('scale-100');
        }, 10);
    }

    function closeCreateDeliveryModal() {
        const modal = document.getElementById('create-delivery-modal');
        const panel = document.getElementById('create-delivery-panel');
        const backdrop = document.getElementById('create-delivery-backdrop');

        panel.classList.remove('scale-100');
        panel.classList.add('opacity-0', 'scale-95');
        backdrop.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function selectDeliveryType(type) {
        document.getElementById('delivery-type-selection').classList.add('hidden');
        document.getElementById('create-back-btn').classList.remove('hidden');
        
        // Hide all forms
        document.querySelectorAll('.detail-form').forEach(form => form.classList.add('hidden'));

        // Map types to form IDs
        const formIds = {
            'customer': 'customer-delivery-form',
            'outlet-transfer': 'outlet-transfer-form',
            'adhoc': 'adhoc-delivery-form'
        };

        // Show selected form
        const formId = formIds[type];
        if (formId && document.getElementById(formId)) {
            document.getElementById(formId).classList.remove('hidden');
        } else {
            console.error('Form not found for type:', type);
        }

        // Update Header
        const titles = {
            'customer': 'Create Delivery',
            'outlet-transfer': 'Create Delivery',
            'adhoc': 'Create Delivery'
        };
        const subtitles = {
            'customer': 'Deliver to customer address',
            'outlet-transfer': 'Transfer goods to outlet',
            'adhoc': 'One-time special delivery'
        };
        document.getElementById('create-modal-title').textContent = titles[type];
        document.getElementById('create-modal-subtitle').textContent = subtitles[type];
    }

    function goBackToSelection() {
        document.getElementById('delivery-type-selection').classList.remove('hidden');
        document.getElementById('create-back-btn').classList.add('hidden');
        
        document.querySelectorAll('.detail-form').forEach(form => form.classList.add('hidden'));

        document.getElementById('create-modal-title').textContent = 'Select Delivery Type';
        document.getElementById('create-modal-subtitle').textContent = 'Choose the type of delivery';
    }

    // Dynamic Items Logic
    function addCreateItem(type) {
        const container = document.getElementById(type + '-items-container');
        const index = container.children.length;
        
        const row = document.createElement('div');
        row.className = 'flex gap-3 items-start item-row';
        row.innerHTML = `
            <input
                type="text"
                name="items[${index}][name]"
                placeholder="Product name"
                class="flex-1 h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-${type === 'outlet-transfer' ? 'green' : (type === 'adhoc' ? 'orange' : 'blue')}-500 transition-colors"
                required
            />
            <input
                type="number"
                name="items[${index}][quantity]"
                placeholder="Qty"
                min="1"
                class="w-24 h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-${type === 'outlet-transfer' ? 'green' : (type === 'adhoc' ? 'orange' : 'blue')}-500 transition-colors"
                required
            />
            <select
                name="items[${index}][unit]"
                class="w-28 h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-${type === 'outlet-transfer' ? 'green' : (type === 'adhoc' ? 'orange' : 'blue')}-500 transition-colors"
            >
                <option value="pcs">pcs</option>
                <option value="kg">kg</option>
                <option value="loaves">loaves</option>
                <option value="boxes">boxes</option>
            </select>
            <button type="button" onclick="removeCreateItem(this)" class="w-12 h-12 rounded-xl bg-red-100 hover:bg-red-200 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><line x1="18" x2="6" y1="6" y2="18"></line><line x1="6" x2="18" y1="6" y2="18"></line></svg>
            </button>
        `;
        container.appendChild(row);
    }

    function removeCreateItem(btn) {
        const row = btn.closest('.item-row');
        // Don't remove if it's the only one (optional UX choice, keeping logic simple)
        if (row.parentElement.children.length > 1) {
            row.remove();
        } else {
            // Optional: clear inputs
            row.querySelectorAll('input').forEach(input => input.value = '');
        }
    }

    // Outlet Selection Logic
    function updateOutletDetails(select) {
        const option = select.selectedOptions[0];
        const container = document.getElementById('outlet-details-container');
        
        if (select.value) {
            container.classList.remove('hidden');
            document.getElementById('outlet-address').value = option.dataset.address || '';
            document.getElementById('outlet-phone').value = option.dataset.phone || '';
            document.getElementById('outlet-contact').value = option.dataset.contact || '';
        } else {
            container.classList.add('hidden');
        }
    }

    function handleCreateDeliverySubmit(event, type) {
        event.preventDefault();
        
        // Validation handled by HTML5 attributes mostly
        // Perform AJAX or Form Submit
        
        // Mock Success
        alert(type.replace('-', ' ') + ' delivery created successfully!');
        
        // Clean up
        event.target.reset();
        closeCreateDeliveryModal();
        // location.reload();
    }
</script>
@endsection